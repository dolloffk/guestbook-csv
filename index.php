<?php 
include "includes/gb-top.php";

$filepath = "entries.csv";
$entries = dateSort(toArray($filepath));

$total_entries = countComments($entries);
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? $_GET['page'] : 1;
$total_pages = ceil($total_entries/$per_page);
$calc_page = ($page - 1) * $per_page;
$page_entries = array_slice($entries, $calc_page, $per_page);

if ($total_entries == 0) {
    echo $noentries;
} else {
    showPages($page, $total_pages);
    foreach ($page_entries as $rows) {
        $name = $rows['name'];
        $url = $rows['url'];
        $date = date_create($rows['date']);
        $comment = $rows['comment'];
        $reply = $rows['reply'];
        displayEntry($name, $url, $date, $comment, $reply);
    }
    showPages($page, $total_pages);
}
    
include "includes/gb-bottom.php";
?>
