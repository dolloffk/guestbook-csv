<?php
    
    function toArray ($filepath) {
        $entry = [];
        $file = fopen($filepath, "r");
        while ($row = fgetcsv($file)) {
            $entry[] = $row;
        }
        fclose($file);

        $headers = array_shift($entry);
        $entries = [];
        foreach ($entry as $row) {
            $entries[] = array_combine($headers, $row);
        }
        
        return $entries;
    }
    
    function countComments($entries) {
        $total_comments = count($entries);
        return $total_comments;
    }
    
    function getID($filepath) {
        $entries = toArray($filepath);
        $ids = array_column($entries, 'id');
        
        if (count($ids) > 0) {
            $id = max($ids) + 1; 
        } else {
            $id = 1;
        }
        return $id;
    }
    
    function dateSort($entries, $descending) {
        if ($descending) {
            array_multisort(array_column($entries, 'date'), SORT_DESC, $entries);
        } else {
            array_multisort(array_column($entries, 'date'), SORT_ASC, $entries);
        }
        return $entries;
    }
    
	function showPages($page, $total_pages) {
        if ($total_pages > 0) { ?>
            <ul class="pages">
            <?php 
            if ($page > 1) { echo "<li class=\"page\"><a href=\"?page=". $page-1 ."\">‹ Prev</a></li>"; } 
            if ($page > 3) { echo "<li class=\"page\"><a href=\"?page=1\">1</a></li>"; echo "<li class=\"dots\">...</li>"; }
            if ($page-2 > 0) { echo "<li class=\"page\"><a href=\"?page=". $page-2 ."\">". $page-2 ."</a></li>"; } 
            if ($page-1 > 0) { echo "<li class=\"page\"><a href=\"?page=". $page-1 ."\">". $page-1 ."</a></li>"; } ?>
            <li class="active"><?php echo $page; ?></li>
            <?php 
            if ($page+1 < $total_pages+1) { echo "<li class=\"page\"><a href=\"?page=". $page+1 ."\">". $page+1 ."</a></li>"; } 
            if ($page+2 < $total_pages+1) { echo "<li class=\"page\"><a href=\"?page=". $page+2 ."\">". $page+2 ."</a></li>"; }
            if ($page < $total_pages-2) { echo "<li class=\"dots\">...</li>"; echo "<li class=\"page\"><a href=\"?page=". $total_pages ."\">". $total_pages ."</a></li>"; }
            if ($page < $total_pages) { echo "<li class=\"page\"><a href=\"?page=". $page+1 ."\">Next ›</a></li>"; } ?>
            </ul>
<?php 
        }
    }
    
    function displayEntry($name, $url, $date, $comment, $reply) {
        include "templates/entry.php";
    }

    function checkName($name) {
        if (!empty($name) && !preg_match("/^[a-zA-Z-'\s]*$/", $name)) {
            return false;
        }
        
        return true;
    }
    
    function checkUrl($url) {
        if (!empty($url) && !filter_var($url, FILTER_VALIDATE_URL)) {
            return false;
        }
        
        return true;
    }
    
    function isBot() {
        $bots = array("Indy", "Blaiz", "Java", "libwww-perl", "Python", "OutfoxBot", "User-Agent", "PycURL", "AlphaServer", "T8Abot", "Syntryx", "WinHttp", "WebBandit", "nicebot", "Teoma", "alexa", "froogle", "inktomi", "looksmart", "URL_Spider_SQL", "Firefly", "NationalDirectory", "Ask Jeeves", "TECNOSEEK", "InfoSeek", "WebFindBot", "girafabot", "crawler", "www.galaxy.com", "Googlebot", "Scooter", "Slurp", "appie", "FAST", "WebBug", "Spade", "ZyBorg", "rabaz");

        foreach ($bots as $bot) {
            if (stripos($_SERVER['HTTP_USER_AGENT'], $bot) !== false) {
                return true;
            }
        }

        if (empty($_SERVER['HTTP_USER_AGENT']) || $_SERVER['HTTP_USER_AGENT'] == " ") {
            return true;
        }
        
        return false;
    }
    
    function checkSecurity($answer, $correct) {
        $answer = trim(strtolower($answer));
        if ((!empty($answer)) && ($answer !== $correct)) {
            return false;
        }
        
        return true;
    }
    
    function checkComment($text) {
        $exploits = "/(content-type|bcc:|cc:|document.cookie|onclick|onload|alert)/i";
        if ((isBot() !== false) || (preg_match($exploits, $text)) || (preg_match("/(<.*>)/i", $text))) {
            return false;
        }
        
        return true;
    }
    
    function validateComment($name, $url, $security, $correct, $comment) {
        if (checkName($name) == false) {
            $error .= "The name you provided isn't valid - it should only consist of letters.&nbsp;";
        }
        
        if (checkUrl($url) == false) {
            $error .= "The URL you provided isn't valid.&nbsp;";
        }
        
        if (checkSecurity($security, $correct) == false) {
            $error .= "Wrong security question answer!&nbsp;";
        }
        
        if (checkComment($comment) == false) {
            $error .= "No bots or HTML!&nbsp;";
        }
        
        if (substr_count($comment, 'http://') > 0) {
            $error .= "Please only include URLs in the website field.";
        }
        
        return $error;
    }
    
    function addComment($filepath, $name, $url, $date, $comment, $reply) {
        try {
            $id = getID($filepath);
            $newentry = array($id, $name, $url, $date, $comment, $reply);
            
            $file = fopen($filepath,"a");
            fputcsv($file, $newentry);
            fclose($file);
            
            return true;
        } catch (Exception $ex) {
            echo $ex->getMessage();
            return false;
        }
    }
    
    function deleteComment($id, $filepath, $headers) {
        try {
            $entries = toArray($filepath);
            $key = array_search($id, array_column($entries, 'id'));
            if ($key !== false) {
                unset($entries[$key]);
                
                $file = fopen($filepath,"w");
                fputcsv($file, $headers);
                foreach ($entries as $entry) {
                     fputcsv($file, $entry);
                }
                fclose($file);
                return true;
            } else {
                echo "The entry you're trying to delete doesn't exist!";
                return false;
            }
        } catch (Exception $ex) {
            echo $ex->getMessage();
            return false;
        }
    }

    function updateComment($key, $id, $name, $url, $date, $comment, $reply, $headers) {
        try {
            if ($key !== false) {
                $entries = toArray("entries.csv");
                $entries[$key] = array($id, $name, $url, $date, $comment, $reply);
                
                $file = fopen("entries.csv","w");
                fputcsv($file, $headers);
                foreach ($entries as $entry) {
                  fputcsv($file, $entry);
                }
                fclose($file);
                return true;
            } else {
                echo "The entry you're trying to update doesn't exist!";
                return false;
            }
        } catch (Exception $ex)  {
            echo $ex->getMessage();
            return false;
        }
    }
    
    function showEntries($entries, $status) {
?>
        <table>
        <tr><th>Name</th> <th>URL</th> <th>Date</th> <th>Comment</th> <th>Reply</th> <th>Edit</th></tr>
<?php   foreach ($entries as $entry) { ?>
            <tr>
            <td><?php echo $entry['name']; ?></td>
            <td><a href="<?php echo $entry['url']; ?>"><?php echo $entry['url']; ?></a></td>
            <td><?php echo date_format(date_create($entry['date']), "Y-m-d"); ?></td>
            <td><?php echo substr(htmlentities($entry['comment']), 0, 30) . "..."; ?></td>
            <td><?php echo substr(htmlentities($entry['reply']), 0, 30) . "..." ; ?></td>
            <td><a href="?p=edit&<?php echo $status; ?>=<?php echo $entry['id']; ?>">Edit</a></td>
            </tr>
<?php
        }
?>
        </table>
<?php
    }
?>
