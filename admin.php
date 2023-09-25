<?php
include "prefs.php";
include "includes/functions.php";
include "templates/top.php"; 

session_start();?>
<div class="wrapper">
<?php
if (!isset($_SESSION['loggedin'])) {
    if (isset($_GET['p']) && $_GET['p'] == "login") {
        if (($_POST['username'] == $username) && (password_verify($_POST['password'], $password_hash))) {
            session_regenerate_id();
            $_SESSION['loggedin'] = TRUE;
            $_SESSION['name'] = $_POST['username'];
            $_SESSION['id'] = session_id();
            header('Location: admin');
        } else {
            $error = "<p>There was an error logging in. Try again.</p>";
        }
}
?>
    <h1>Admin panel login</h1>
    <?php echo $error; ?>
    <form action="?p=login" method="post">
    <p><label for="username">Name</label> <input type="text" name="username" id="username" required /><br />
    <label for="password">Password</label> <input type="password" name="password" id="password" required /><br />
    <input type="submit" id="submit"  value="Login" /></p></form>
<?php
} else {
?>

<?php 
    $tab = $_GET['p'];
    if ($tab=="") { $tab = "home"; }
    
    switch($tab) {
        case "comments":
            $entries = dateSort(toArray("entries.csv"));
?>
            <h1>Approved comments</h1>
            <table>
            <tr><th>Name</th> <th>URL</th> <th>Date</th> <th>Comment</th> <th>Reply</th> <th>Edit</th></tr>
<?php
            foreach ($entries as $rows) {
?>
                <tr>
                <td><?php echo $rows['name']; ?></td>
                <td><a href="<?php echo $rows['url']; ?>"><?php echo $rows['url']; ?></a></td>
                <td><?php echo date_format(date_create($rows['date']), "Y-m-d"); ?></td>
                <td><?php echo substr(htmlentities($rows['comment']), 0, 30) . "..."; ?></td>
                <td><?php echo substr(htmlentities($rows['reply']), 0, 30) . "..." ; ?></td>
                <td><a href="?p=edit&approved=<?php echo $rows['id']; ?>">Edit</a></td>
                </tr>
<?php
            }
?>
            </table>
    
            <p><a href="admin">Back to admin panel</a></p>
<?php
            break;
        case "pendingcomments":
            $entries = dateSort(toArray("queue.csv")); 
?>
            <h1>Pending comments</h1>
            <table>
            <tr><th>Name</th> <th>URL</th> <th>Date</th> <th>Comment</th> <th>Edit</th></tr>
<?php
            foreach ($entries as $rows) {
?>
                <tr>
                <td><?php echo $rows['name']; ?></td>
                <td><a href="<?php echo $rows['url']; ?>"><?php echo $rows['url']; ?></a></td>
                <td><?php echo date_format(date_create($rows['date']), "Y-m-d"); ?></td>
                <td><?php echo substr(htmlentities($rows['comment']), 0, 30) . "..."; ?></td>
                <td><?php echo substr(htmlentities($rows['reply']), 0, 30) . "..." ; ?></td>
                <td><a href="?p=edit&pending=<?php echo $rows['id']; ?>">Edit</a></td>
                </tr>
<?php
            }
?>
            </table>
            
            <p><a href="admin">Back to admin panel</a></p>
<?php
            break;
        case "edit":
            if (isset($_GET['pending'])) {
                $entry_id = $_GET['pending'];
                $entries = toArray("queue.csv");
                $submittext = "Save and approve";
                $back = "pendingcomments";
            } else if (isset($_GET['approved'])) {
                $entry_id = $_GET['approved'];
                $entries = toArray("entries.csv");
                $submittext = "Save changes";
                $back = "comments";
            }
            
            $key = array_search($entry_id, array_column($entries, 'id'));
            if ($key !== false) {
                $entry = $entries[$key];
        ?>
                <h1>Edit entry</h1>
                <form method="post" action="?p=editcomment">
                <div><label for="name">Name</label> <input type="text" name="name" value="<?php echo $entry['name']; ?>" /></div>
                <div><label for="url">URL</label> <input type="text" name="url" value="<?php echo $entry['url']; ?>" /></div>
                <div><label for="date">Date</label> <input type="text" name="date" readonly value="<?php echo $entry['date']; ?>" /></div>
                <div><label for="comment">Comment</label><br/>
                <textarea rows="6" name="comment"><?php echo $entry['comment']; ?></textarea></div>
                <div><label for="comment">Reply</label><br/>
                <textarea rows="6" name="reply"><?php echo $entry['reply']; ?></textarea></div>
                
                <input type="hidden" name="comment_id" value="<?php echo $entry_id; ?>" />
                <input type="hidden" name="comment_key" value="<?php echo $key; ?>" />
                <input type="hidden" name="comment_status" value="<?php echo $back; ?>" />

                <input type="submit" name="submit" value="<?php echo $submittext; ?>" />
                <input type="submit" name="delete" value="Delete" />
                </form>

                <p><a href="?p=<?php echo $back; ?>">Back to list</a></p>
<?php 
            } else {
?>
                <h1>Edit comment</h1>
                <p>This comment ID doesn't exist.</p>
                <p><a href="?p=<?php echo $back; ?>">Back to list</a></p>
<?php
            }
            break;
        case "editcomment": ?>
                <h1>Edit comment</h1>
<?php
            if (isset($_POST['submit'])) {
                $reply = str_replace(array("\r\n", "\r", "\n"), "<br />", $_POST['reply']);
                if ($_POST['comment_status'] == "pendingcomments") {
                    $entries = toArray("queue.csv");
                    $key = array_search($_POST['comment_id'], array_column($entries, 'id'));
                    if ($key !== false) {
                        unset($entries[$key]);
                    }
                    
                    $file = fopen("queue.csv","w");
                    $headers = ["id","name","url","date","comment","reply"];
                    fputcsv($file, $headers);
                    foreach ($entries as $row) {
                      fputcsv($file, $row);
                    }
                    fclose($file);
                    
                    $path = "entries.csv";
                    $new_id = getID($path);
                    $newentry = array($new_id, $_POST['name'], $_POST['url'], $_POST['date'], $_POST['comment'], $reply);
                    
                    $file = fopen("entries.csv","a");
                    fputcsv($file, $newentry);
                    fclose($file);
                } else if ($_POST['comment_status'] == "comments") {
                    $newentry = array($_POST['comment_id'], $_POST['name'], $_POST['url'], $_POST['date'], $_POST['comment'], $reply);
                    $entries = toArray("entries.csv");
                    $key = array_search($_POST['comment_id'], array_column($entries, 'id'));
                    if ($key !== false) {
                        $entries[$key] = $newentry;
                    }
                    
                    $file = fopen("entries.csv","w");
                    $headers = ["id","name","url","date","comment","reply"];
                    fputcsv($file, $headers);
                    foreach ($entries as $row) {
                      fputcsv($file, $row);
                    }
                    fclose($file);
                }
?>
                <p>Entry edited successfully.</p>
                <p><a href="?p=<?php echo $_POST['comment_status']; ?>">Back to list of comments</a></p>
<?php
            } else {
?>
                <p>Oops! You must have gotten here by mistake.</p>
                <p><a href="admin">Back to admin panel</a></p>
<?php
            }
            break;
        case "home": ?>
            <h1>Admin</h1>
            <ul>
                <li><a href="?p=comments">Approved comments</a></li>
                <li><a href="?p=pendingcomments">Pending comments</a></li>
                <li><a href="index">View guestbook</a></li>
            </ul>

            <a href="?p=logout">Logout</a>
<?php
            break;
        case "logout":
            session_destroy();
            header('Location: admin');
            break;
    }
?>

<?php } ?>
</div>
<?php include "templates/bottom.php"; ?>