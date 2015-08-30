<?php
require_once('global_vars.php');
$dbc = mysql_connect(DB_HOST, DB_USER, DB_PASSWORD); 
mysql_select_db (DB_NAME);
// set previous href
$previous = '<a href="rant.php?id=' . ($id - 1) . '">&lt; previous</a> | ';
// set next href
$query = "SELECT id FROM rant ORDER BY id DESC LIMIT 1";
$nextid = mysql_query($query, $dbc);
$result = mysql_fetch_array($nextid);
($result["id"] > $id) ? $next = ' | <a href="rant.php?id=' . ($id + 1) . '">next &gt;</a>' : $next = '';
echo $previous;
echo '<a href="random.php">random</a>';
echo $next;
?>