<?php

// Admin login information
$username = "admin";
// The following should be a hashed password generated using hasher.php. Escape all dollar signs and make sure there's no spaces. Delete hasher.php from your server after you do this!!!!
$password_hash = "\$2y\$10\$EUnJSAcmgA5f0.UI3YiLee22OedojMo2v0MHcnV6DNCUtXewmhyLy";

// Set to true if you want to preapprove comments, false if not
$approve = true;

// Security question and answer (case-insensitive)
$securityq = "Is water liquid at 0&deg;C?";
$securitya = "no";

// Text to display with no entries
$noentries = "<p>No entries have been left yet. The first one could be you!</p>";

// Entries per page
$per_page = 10;

// Entry date sort order (true for descending, false for ascending)
$descending = true;

?>