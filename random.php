<?php
require_once('global_vars.php');
$dbc = mysql_connect(DB_HOST, DB_USER, DB_PASSWORD) 
	or die('Cannot connect to the database because: ' . mysql_error());
mysql_select_db (DB_NAME);

$query = "SELECT id FROM rant ORDER BY id DESC LIMIT 1";
$result = mysql_query($query, $dbc);
$row = mysql_fetch_array($result);
$id = $row['id'];
$_GET['id']= mt_rand(0, $id);
include('rant.php');
exit;
?>