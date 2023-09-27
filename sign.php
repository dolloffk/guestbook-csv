<?php 
include "includes/gb-top.php";
?>

<h2>Sign guestbook</h2>
<?php echo $msg; ?>
<form id="comment" method="post" action="sign.php">
<div><label for="username">Name</label><br/><input type="text" name="username" role="username" placeholder="Name" value="<?php echo $_SESSION['name']; ?>"></input></div>
<div><label for="website">Website (optional)</label><br/><input type="text" name="website" role="website" placeholder="http://" value="<?php echo $_SESSION['url']; ?>"></input></div>
<div><label for="security"><?php echo $securityq; ?></label><br/><input type="text" name="security" role="security" placeholder="yes/no"></input></div>
<div><label for="comment">Comment</label><br/>
<textarea rows="6" name="comment"><?php echo $_SESSION['comment']; ?></textarea></div>

<!-- spambot traps -->
<div style="display:none;">
<input type="text" name="name" />
<input type="text" name="url" />
</div>

<input type="submit" name="submit" value="Submit" />

<?php 
include "includes/gb-bottom.php";
?>
