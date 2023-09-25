<?php

// Admin login information
$username = "admin";
// The following should be a hashed password generated using hasher.php. Escape all dollar signs and make sure there's no spaces. Delete hasher.php from your server after you do this!!!!
$password_hash = "\$2y\$10\$TdtmGxWa3eHL97E12XPkVe1wCPXFelIRiikHcP2PFxGUwO6eXCS6m";

// Set to TRUE if you want to preapprove comments, FALSE if not
$approve = TRUE;

// Security question and answer (case-insensitive)
$securityq = "Is water liquid at 0&deg;C?";
$securitya = "no";

// Text to display with no entries
$noentries = "<p>No entries have been left yet. The first one could be you!</p>";

// Entries per page
$per_page = 10;

?>