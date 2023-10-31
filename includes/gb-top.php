<?php 
ob_start();
session_start();

include "functions.php";
include "prefs.php";

include "templates/top.php";

$_SESSION['name'] = null;
$_SESSION['url'] = null;
$_SESSION['comment'] = null;

$msg = null;

if (isset($_GET['p']) && $_GET['p'] == "success") {
    $msg .= "<p>Comment successfully submitted for approval!</p>";
}

if (isset($_POST['submit'])) {
    $error = null;
    
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
    
    $_SESSION['name'] = $name;
    $_SESSION['url'] = $url;
    $_SESSION['comment'] = $comment;
    
    $error .= validateComment($name, $url, $security, $securitya, $comment);
    
    if ($error == null) {
        if ($approve) {
            $csvpath = "queue.csv";
        } else {
            $csvpath = "entries.csv";
        }
        
        $date = date("Y-m-d H:i:s");
        addComment($csvpath, $name, $url, $date, str_replace(array("\r\n", "\r", "\n"), "<br />", $comment), "");
        $_SESSION['name'] = '';
        $_SESSION['url'] = '';
        $_SESSION['comment'] = '';
        
        flush();
        header('Location: sign.php?p=success');
    } else {
        $msg .= "<p>Your comment could not be posted. See the following errors:</p><p>".$error."</p>";
    }
}

include "templates/gb-header.php";
?>