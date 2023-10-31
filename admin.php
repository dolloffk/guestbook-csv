<?php
    ob_start();
    session_start();

    include "prefs.php";
    include "includes/functions.php";
    include "templates/top.php"; 
?>

<?php
    $error = "";

    // Display login form if not logged in
    if (!isset($_SESSION['loggedin'])) {
        if (isset($_GET['p']) && $_GET['p'] == "login") {
            if (($_POST['username'] == $username) && (password_verify($_POST['password'], $password_hash))) {
                // Log in, start a new session, and redirect back to the admin panel
                $_SESSION['loggedin'] = TRUE;
                $_SESSION['name'] = $_POST['username'];
                header('Location: admin.php');
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
        $headers = array("id","name","url","date","comment","reply");
        $tab = $_GET['p'];
        if ($tab=="") { $tab = "home"; }
        
        switch($tab) {
            case "comments":
                $entries = dateSort(toArray("entries.csv"), true);
                echo "<h1>Approved comments</h1>";
                if (countComments($entries) > 0) {
                    showEntries($entries, "approved");
                } else {
                    echo "<p>No entries to show.</p>";
                }
                echo "<p><a href=\"admin.php\">Back to admin panel</a></p>";
                break;
                
            case "pendingcomments":
                $entries = dateSort(toArray("queue.csv"), true); 
                echo "<h1>Pending comments</h1>";
                if (countComments($entries) > 0) {
                    showEntries($entries, "pending");
                } else {
                    echo "<p>No entries to show.</p>";
                }
                echo "<p><a href=\"admin.php\">Back to admin panel</a></p>";
                break;
                
            case "edit":
                if (isset($_GET['pending'])) {
                    $entry_id = $_GET['pending'];
                    $entries = toArray("queue.csv");
                    $submittext = "Save and approve";
                    $back = "pendingcomments";
                } elseif (isset($_GET['approved'])) {
                    $entry_id = $_GET['approved'];
                    $entries = toArray("entries.csv");
                    $submittext = "Save changes";
                    $back = "comments";
                }
                
                $key = array_search($entry_id, array_column($entries, 'id'));
                echo "<h1>Edit entry</h1>";
                if ($key !== false) {
                    $entry = $entries[$key];
?>
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
                <p>This comment ID doesn't exist.</p>
                <p><a href="?p=<?php echo $back; ?>">Back to list</a></p>
<?php
                }
                break;
            case "editcomment":
                echo "<h1>Edit entry</h1>";
                if (isset($_POST['submit'])) {
                    $reply = str_replace(array("\r\n", "\r", "\n"), "<br />", $_POST['reply']);
                    if ($_POST['comment_status'] == "pendingcomments") {
                        if (deleteComment($_POST['comment_id'], "queue.csv", $headers)) {
                            if (addComment("entries.csv", $_POST['name'], $_POST['url'], $_POST['date'], $_POST['comment'], $reply)) {
                                echo "<p>Entry approved successfully.</p>";
                            } else {
                                echo "<p>An error occurred processing this request.</p>";
                            }
                        }
                    } elseif ($_POST['comment_status'] == "comments") {
                        if (updateComment($_POST['comment_key'], $_POST['comment_id'], $_POST['name'], $_POST['url'], $_POST['date'], $_POST['comment'], $reply, $headers)) {
                            echo "<p>Entry updated successfully.</p>";
                        } else {
                            echo "<p>An error occurred processing this request.</p>";
                        }
                    }
?>
                <p><a href="?p=<?php echo $_POST['comment_status']; ?>">Back to list of comments</a></p>
<?php
                } elseif (isset($_POST['delete'])) {
                    if ($_POST['comment_status'] == "pendingcomments") {
                        $filepath = "queue.csv";
                    } elseif ($_POST['comment_status'] == "comments") {
                        $filepath = "entries.csv";
                    }
                    if (deleteComment($_POST['comment_id'], $filepath, $headers)) {
                        echo "<p>Entry deleted successfully.</p>";
                    } else {
                        echo "<p>An error occurred processing this request.</p>";
                    }
?>
                <p><a href="?p=<?php echo $_POST['comment_status']; ?>">Back to list of comments</a></p>
<?php
                } else {
?>
                <p>Oops! You must have gotten here by mistake.</p>
                <p><a href="admin.php">Back to admin panel</a></p>
<?php
                }
                break;
            case "home": 
?>
            <h1>Admin</h1>
            <ul>
                <li><a href="?p=comments">Approved comments</a></li>
                <li><a href="?p=pendingcomments">Pending comments</a></li>
                <li><a href="index.php">View guestbook</a></li>
            </ul>

            <a href="?p=logout">Logout</a>
<?php
                break;
            case "logout":
                session_destroy();
                header('Location: admin.php');
                break;
        }
    } 

    include "templates/bottom.php"; ?>