<html>
<head></head>
<body>
<?php 
// Enter your password below, then upload and load this page. Copy the output to prefs.php, then delete the file from the server.
$pwd = "password";
$hash = password_hash($pwd,PASSWORD_DEFAULT);
echo $hash; ?>
</body>
</html>