<?php 
include "functions.php";
include "prefs.php";

include "templates/top.php";

session_start();
$_SESSION['name'] = NULL;
$_SESSION['url'] = NULL;
$_SESSION['comment'] = NULL;

$msg = NULL;

if (isset($_GET['p']) && $_GET['p'] == "success") {
    $msg .= "<p>Comment successfully submitted for approval!</p>";
}

if (isset($_POST['submit'])) {
    $error = NULL;
    
    if ((!empty($_POST['name'])) || (!empty($_POST['url']))) {
        $error .= "No bots! ";
    }
    
    if (empty($_POST['username'])) {
        $error .= "Name is a required field.&nbsp;";
    }
    
    if (empty($_POST['security'])) {
        $error .= "Please answer the security question.&nbsp;";
    }
    
    if (empty($_POST['comment'])) {
        $error .= "Comment is a required field.&nbsp;";
    }
    
    $name = strip_tags($_POST['username']);
    $url = strip_tags($_POST['website']);
    $security = $_POST['security'];
    $comment = htmlentities(strip_tags($_POST['comment']));
    $ip = $_SERVER['REMOTE_ADDR'];
    
    $_SESSION['name'] = $name;
    $_SESSION['url'] = $url;
    $_SESSION['comment'] = $comment;
    
    $error .= validateComment($name, $url, $security, $securitya, $comment);
    
    if ($error == NULL) {
        if ($approve == TRUE) {
            $csvpath = "queue.csv";
        } else {
            $csvpath = "entries.csv";
        }
        
        addComment($csvpath, $name, $url, $ip, str_replace(array("\r\n", "\r", "\n"), "<br />", $comment));
        $_SESSION['name'] = '';
        $_SESSION['url'] = '';
        $_SESSION['comment'] = '';
        header('Location: sign?p=success');
    } else {
        $msg .= "<p>Your comment could not be posted. See the following errors:</p><p>".$error."</p>";
    }
}

include "templates/gb-header.php";
?>