<?php
    // DISPLAYING ENTRIES
    
    function toArray ($filepath) {
        $rows = [];
        $file = fopen($filepath, "r");
        while ($row = fgetcsv($file)) {
            $rows[] = $row;
        }
        fclose($file);

        $headers = array_shift($rows);
        $entries = [];
        foreach ($rows as $row) {
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
        $ids = [];
        foreach ($entries as $key => $val) {
            $ids[$key] = $val['id'];
        }
        
        if (count($ids) > 0) {
            $id = max($ids) + 1;
        } else {
            $id = 1;
        }
        return $id;
    }
    
    function dateSort($entries) {
        $dates = [];
        foreach ($entries as $key => $val) {
            $dates[$key] = $val['date'];
             
        }
        array_multisort($dates, SORT_DESC, $entries);
        return $entries;
    }
    
	function showPages($page, $total_pages) {
        if ($total_pages > 0) { ?>
            <ul class="pages">
            <?php if ($page > 1) { echo "<li class=\"page\"><a href=\"?page=". $page-1 ."\">‹ Prev</a></li>"; } ?>
            <?php if ($page > 3) { echo "<li class=\"page\"><a href=\"?page=1\">1</a></li>"; echo "<li class=\"dots\">...</li>"; } ?>
            <?php if ($page-2 > 0) { echo "<li class=\"page\"><a href=\"?page=". $page-2 ."\">". $page-2 ."</a></li>"; } 
                  if ($page-1 > 0) { echo "<li class=\"page\"><a href=\"?page=". $page-1 ."\">". $page-1 ."</a></li>"; } ?>
            <li class="active"><?php echo $page ?></li>
            <?php if ($page+1 < $total_pages+1) { echo "<li class=\"page\"><a href=\"?page=". $page+1 ."\">". $page+1 ."</a></li>"; } 
                  if ($page+2 < $total_pages+1) { echo "<li class=\"page\"><a href=\"?page=". $page+2 ."\">". $page+2 ."</a></li>"; } ?>
            <?php if ($page < $total_pages-2) { echo "<li class=\"dots\">...</li>"; echo "<li class=\"page\"><a href=\"?page=". $total_pages ."\">". $total_pages ."</a></li>"; } ?>
            <?php if ($page < $total_pages) { echo "<li class=\"page\"><a href=\"?page=". $page+1 ."\">Next ›</a></li>"; } ?>
            </ul>
<?php }
	}
    
    function displayEntry($name, $url, $date, $comment, $reply) {
        include "templates/entry.php";
    }

    
    // ENTRY VALIDATION
    
    
    function checkName($name) {
        if (!empty($name) && !preg_match("/^[a-zA-Z-'\s]*$/", $name)) {
            return false;
        }
        
        return true;
    }
    
    function checkUrl($url) {
        if (!empty($url) && !preg_match('/^(http|https):\/\/(([A-Z0-9][A-Z0-9_-]*)(\.[A-Z0-9][A-Z0-9_-]*)+)(:(\d+))?\/?/i', $url)) {
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
        $answer = strtolower($answer);
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
    
    function addComment($filepath, $name, $url, $comment) {
        $id = getID($filepath);
        $newentry = array($id, $name, $url, date("Y-m-d H:i:s"), $comment, "");
        
        $file = fopen($filepath,"a");
        fputcsv($file, $newentry);
        fclose($file);
    }
?>