<?php
require_once('global_vars.php');
// get the page id and make sure it is valid
if (isset ($_GET['id'])) {
	$id = $_GET['id'];
		if(is_numeric($id)) {
	$query = "SELECT * FROM rant WHERE id = " . $id;
                if($id=='0') {
                include('frontpage.html');
                exit;
                }
        }
	else {
	include('home.php');
	exit;
	}
}
// get the page id from rant column (via either ?rant= or ?=)
elseif (isset ($_GET['rant'])) {
	$rant = $_GET['rant'];
	$query = "SELECT * FROM rant WHERE rant = '" . $rant . "'";
}
elseif (!isset ($_GET['id']) && !isset ($_GET['rant'])) {
	if(isset($_SERVER['QUERY_STRING'])) {
		$rant = $_SERVER['QUERY_STRING'];
		$query = "SELECT * FROM rant WHERE rant = '" . $rant . "'";
	}
	else{
	echo 'neither id or rant have been specified.';
	exit;
	}
}
$dbc = mysql_connect(DB_HOST, DB_USER, DB_PASSWORD) 
	or die('Cannot connect to the database because: ' . mysql_error());
// run query as determined above
mysql_select_db (DB_NAME);
$result = mysql_query($query, $dbc);
if((mysql_num_rows($result)) != 1) {
	include('home.php');
	exit;
}
while ($row = mysql_fetch_array($result)) {
// assign vars to all data
$id = $row['id'];
$title = $row['title'];
$rant = $row['rant'];
$pubdate = strtotime($row['pubdate']);
$content = $row['content'];
}
mysql_close($dbc);
// check vars to verify page exists
if(empty($id)) {
	echo 'page not found, title is empty!';
	exit;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<title><?php
echo $title;
?></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="alternate" type="application/atom+xml" title="Entertain Yourself Immediately" href="http://feeds.vybeauregard.net/eyi/" />
<link rel="stylesheet" href="../deep.css" type="text/css" />
<link rel="shortcut icon" href="favicon.ico" />
</head>
<body>
<div class="title">
	<?php

echo $title;
?>
</div>
<div class="content"><?php echo $content; ?></div>
<div class="center">
<?php
include "id_increment.php";
?>
</div>
<div class="left">
	<a href="index.php">&laquo;Entertain Yourself some more...</a>
</div>
<div class="right"> 
	<?php
echo date("jF", $pubdate) . '2k' . (date("Y", $pubdate) - 2000);
?>
</div>
<script type="text/javascript">
var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
</script>
<script type="text/javascript">
var pageTracker = _gat._getTracker("UA-1802625-7");
pageTracker._initData();
pageTracker._trackPageview();
</script>
</body>
</html>