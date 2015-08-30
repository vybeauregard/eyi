<?php
include('search.php');
date_default_timezone_set("America/Chicago");
?>
<div class="container">
<div id="entries">
<div class="container">
<?php
require_once('global_vars.php');
$dbc = mysql_connect(DB_HOST, DB_USER, DB_PASSWORD) 
	or die('Cannot connect to the database because: ' . mysql_error());
mysql_select_db (DB_NAME);

$query = "SELECT pubdate, short_title, rant FROM rant ORDER BY id DESC";
$result = mysql_query($query, $dbc);
$i = 0;
while($row = mysql_fetch_array($result)) {
// get first entry's month status - even or odd - to determine L or R placement
	while($i == 0) {
	    $month = date('m', strtotime($row['pubdate']));
	    $even = ($month % 2);
	    $ul = 0;
	    $id = $row['id'];
	  if($even != 0){ //if another month is skipped, change this back to $even == 0
	    echo '<ul class="l">';
	    $leftright = 'l';
	  }else{
	    echo '<ul class="r">';
	    $leftright = 'r';
	  }
	  $i++;
	}
// continue through the rest of the display loop
//set up row display vars
$left = '</div>'."\n".'<div class="clear"></div>'."\n".'<div class="container">'."\n".'<ul class="l">'."\n";
$right = '<ul class="r">'."\n";
	if(date('m', strtotime($row['pubdate'])) != $month) {
		if($ul == 1) {
			echo '</ul>'."\n";
		}
		if($even != 0) {
			if($leftright!= 'l'){
				$leftright = 'l';
				echo $left;
			}else{
				$leftright = 'r';
				echo $right;
			}
		}
		else {
			if($leftright != 'r'){
				$leftright = 'r';
				echo $right;
			}else{
				$leftright = 'l';
				echo $left;
			}
		}
	}
	$pubdate = date('d.M.', strtotime($row['pubdate']));
	$pubdate .= (date('Y', strtotime($row['pubdate'])) - 2000);
	echo '<li>' . $pubdate;
	echo ' <a href="?rant=' . $row['rant'] . '">';
	echo strtolower($row['short_title']) . '</a></li>' . "\n";

	$month = date('m', strtotime($row['pubdate']));
	$even = ($month % 2);
	$pubdate = ($row['pubdate']);
	$ul = 1;
}
?>
<li>09.Jul.2 <a href="frontpage.html">lamenting the woes of ms frontpage</a></li>
    </ul>
  </div>
  <div class="clear"></div>
</div>
</div>