<?php
  // User name and password for authentication

  require_once('global_vars.php');
  if (!isset($_SERVER['PHP_AUTH_USER']) || !isset($_SERVER['PHP_AUTH_PW']) ||
    ($_SERVER['PHP_AUTH_USER'] != USERNAME) || ($_SERVER['PHP_AUTH_PW'] != PASSWORD)) {
    // The user name/password are incorrect so send the authentication headers
    header('HTTP/1.1 401 Unauthorized');
    header('WWW-Authenticate: Basic realm="EYI Admin"');
    exit('Sorry, you have not been cleared to post to <a href="http://eyi.vybeauregard.net/">eyi.vybeauregard.net</a>.');
  }
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" 
   "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<title>posting to eyi!</title>
   <link rel="stylesheet" href="../deep.css" type="text/css" />
</head>
<body>
<?php
  $dbc = mysql_connect(DB_HOST, DB_USER, DB_PASSWORD);
  mysql_select_db(DB_NAME);
if(isset($_POST['submit'])) {
	$rant = $_POST['rant'];
	$title = $_POST['title'];
	$short_title = $_POST['short_title'];
	$content = $_POST['content'];
	$rss_summary = $_POST['rss_summary'];

	$query = "INSERT INTO rant (rant, title, short_title, content, rss_summary, pubdate) VALUES ('$rant', '$title', '$short_title', '$content', '$rss_summary', NOW())";
	mysql_query($query, $dbc)
		or die('failure: ' . mysql_error());
	echo '<p>Your <a href="index.php?'. $_POST['rant'] . '">rant</a> has been posted successfully!</p>';
	mysql_close($dbc);
	exit;
	}
if(isset($_POST['preview'])) {
	$rant = $_POST['rant'];
	$title = $_POST['title'];
	$short_title = $_POST['short_title'];
	$content = $_POST['content'];
	$rss_summary = $_POST['rss_summary'];
}	

?>
<div style="width:45%, float:left">
<p class="title">New EYI Post</p>
<form method="post" action="<?php $_SERVER['PHP_SELF'] ?>#preview">
<label for="rant">Rant (appears in URL, lower case)</label><br />
<input type="text" name="rant" value="<?php echo stripslashes($rant); ?>" /><br />
<label for="title">Title (appears on rant page, capitals acceptable)</label><br />
<input type="text" name="title" value="<?php echo stripslashes($title); ?>" /><br />
<label for="short_title">Short Title (appears on rant index, capitals acceptable)</label><br />
<input type="text" name="short_title" value="<?php echo stripslashes($short_title); ?>" /><br />
<label for="content">Content (include html markup - <strong>don't forget your &lt;p&gt; and &lt;/p&gt; tags!</strong>)</label><br />
<textarea name="content" rows="20" cols="70"><?php echo stripslashes($content); ?></textarea><br />
<label for="rss_summary">Summary (for RSS feed)</label><br />
<textarea name="rss_summary" rows="6" cols="70"><?php echo stripslashes($rss_summary); ?></textarea><br />
<input type="submit" name="preview" value="Preview" />
<input type="submit" name="submit" value="Submit" />
</form></div>
<hr id="preview" />
<div style="width:45%, float:right">
<?php
	echo '<div class="rss_preview"><em>rss preview:</em><br /><a href="?' . stripslashes($rant) . '">' . stripslashes($short_title) . '</a><br />' . stripslashes($rss_summary) . '</div><div class="title">' . stripslashes($title) . '</div><div class="content">' . stripslashes($content) . '</div>';
?>
<form method="post" action="<?php $_SERVER['PHP_SELF']; ?>">
<input type="hidden" name="post_title" value="<?php $title; ?>" />
<input type="hidden" name="post_short_title" value="<?php $short_title; ?>" />
<input type="hidden" name="post_content" value="<?php $content; ?>" />
<input type="hidden" name="post_rss_summary" value="<?php $rss_summary; ?>" />

</form>
</div>
</body>
</html>