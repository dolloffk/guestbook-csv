<div class="q">
<p><?php echo $comment; ?></p>

<div class="details"><p><?php if (!empty($url)) { echo "<a href=\"". $url ."\">". $name ."</a>"; } else { echo $name; } ?>, 
<?php echo date_format($date, "F d, Y"); ?></p>
</div>
</div>

<?php if (!empty($reply)) { ?>
<div class="a">
<p><?php echo $reply; ?></p>
</div>
<?php } ?>