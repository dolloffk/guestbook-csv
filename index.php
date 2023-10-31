<?php 
    include "includes/gb-top.php";

    $filepath = "entries.csv";
    $entries = dateSort(toArray($filepath),$descending);

    $totalEntries = countComments($entries);
    $page = isset($_GET['page']) && is_numeric($_GET['page']) ? $_GET['page'] : 1;
    $totalPages = ceil($totalEntries/$perPage);
    $pageStart = ($page - 1) * $perPage;
    $pageEntries = array_slice($entries, $pageStart, $perPage);

    if ($totalEntries == 0) {
        echo $noEntries;
    } else {
        showPages($page, $totalPages);
        foreach ($pageEntries as $entry) {
            $name = $entry['name'];
            $url = $entry['url'];
            $date = date_create($entry['date']);
            $comment = $entry['comment'];
            $reply = $entry['reply'];
            displayEntry($name, $url, $date, $comment, $reply);
        }
        showPages($page, $totalPages);
    }
        
    include "includes/gb-bottom.php";
?>
