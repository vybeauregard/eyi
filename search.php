<div><a href="http://feeds.vybeauregard.net/eyi/" title="RSS 2.0" id="xml">RSS 2.0</a><span id="ranting">...ranting since 2002</span></div>
<?php
require_once('global_vars.php');
//ini_set("display_errors","2");
//ERROR_REPORTING(E_ALL);
if(!empty($_GET['q'])) {
	$q = $_GET['q'];
	//$html_q = preg_replace('/\s/', '%20', $q);
	$q = trim($q);
}
function generate_page_links($q, $cur_page, $num_pages) {
	$html_q = preg_replace('/\s/', '%20', $q);
    $page_links = '';
    // If this page is not the first page, generate the "previous" link
    if($cur_page > 1) {
        $page_links .= '<a href="' . $_SERVER['PHP_SELF'] . '?q=' . $html_q . '&amp;page=' . ($cur_page - 1) . '">&larr;</a> ';
    }
    else {
    $page_links .= '&larr; ';
    }
    // Loop through the pages generating the page number links
    for ($i = 1; $i <= $num_pages; $i++) {
        if($cur_page == $i) {
        $page_links .= ' ' . $i;
        }
        else {
            $page_links .= ' <a href="' . $_SERVER['PHP_SELF'] . '?q=' . $html_q . '&amp;page=' . $i . '"> ' . $i . '</a>';
        }
    }
    // If this page is not the last page, generate the "next" link
    if($cur_page < $num_pages) {
        $page_links .= ' <a href="' . $_SERVER['PHP_SELF'] . '?q=' . $html_q . '&amp;page=' . ($cur_page + 1) . '">&rarr;</a> ';
    }
    else {
        $page_links .= ' &rarr;';
    }
    return $page_links;
}
?>
<div class="eyisearchbox">
<form method="get" action="<?php $_SERVER['PHP_SELF']; ?>">
<h1>
<?php $echosearch = stripslashes($q); ?>
<input type="text" id="q" name="q" class="eyisearch" value="<?php echo $echosearch; ?>" />
<input type="submit" value="search" class="eyisearch" id="submit" />
</h1>
</form>
</div>
<?php
// Take search string from the GET, echo back to user.
if(!empty($q)) {
	// Clean up the search string
	$search_query = "SELECT pubdate, short_title, rant, content FROM rant";
	// Extract the search keywords into an array
	$search = trim($q);
	$clean_search = str_replace(',', ' ', $search);
	$search_words = explode(' ', $clean_search);
	$final_seach_words = array();
	if(count($search_words) > 0) {
		foreach($search_words as $word) {
			if(!empty($word)) {
			$final_search_words[] = $word;
			}
		}
	}
	if(empty($final_search_words)) {
		//include('eyi.php');
		echo '</div>';
		include('http://www.vybeauregard.net/footer.html');
		echo '</body></html>';
		exit;
	}
	// Generate a WHERE Clause using all of the search keywords
	$where_list = array();
	if(count($final_search_words) > 0) {
		foreach($final_search_words as $word) {
		$where_list[] = "MATCH(title, content) AGAINST('%$word%' IN BOOLEAN MODE)";
		}
	}
//SELECT * FROM mytable WHERE MATCH(title,content) AGAINST('search terms' IN BOOLEAN MODE)
	$where_clause = implode(' OR ', $where_list);
	// Add the keyword WHERE clause to the search query
	if(!empty($where_clause)) {
		$search_query .= " WHERE $where_clause";
	}
	// Custom function grabs context from search text
	function search_context($string,$query) {
		$buffer = 57;
		$start_position = strpos($string,$query);
		$start_position -= $buffer;
		if($start_position < 0) {
			$start_position = 0;
			$context = '';
		}
		else {
			$context = '&hellip;';
		}
		$query_length = strlen($query);
		$query_length += $buffer * 2;
		$context .= substr($string, $start_position, $query_length);
		return $context;
	}
  // Calculate pagination information
  $cur_page = isset($_GET['page']) ? $_GET['page'] : 1;
  $results_per_page = 6; // number of results per page
  $skip = (($cur_page -1) * $results_per_page);
	// Now that the query is formed, query the database
	$dbc = mysql_connect(DB_HOST, DB_USER, DB_PASSWORD); 
	mysql_select_db (DB_NAME);
	$result = mysql_query($search_query, $dbc);
	$total = mysql_num_rows($result);
  	$query = $search_query . " LIMIT $skip, $results_per_page";
  	$result = mysql_query($query, $dbc);
  	$num_pages = ceil($total / $results_per_page);
	if($total != 1) {
		if($total == 0) {
			echo '<p class="eyisearch">Sorry, your search for <em>' . stripslashes($q) . '</em> returned ' . $total . ' results.  Please try a different query.</p>';
		}
		else {
			echo '<p class="eyisearch">Your search for <em>' . stripslashes($q) . '</em> returned ' . $total . ' results:</p>';
		}
	}
	else {
	echo '<p class="eyisearch">GOOGLEWHACK!!!  Your search for <em>' . stripslashes($q) . '</em> returned ' . $total . ' result!</p>';
	}
	echo '<div class="entries"><ul class="eyisearch"><li>';
	while($row = mysql_fetch_array($result)) {
		$pubdate = date('d.M.', strtotime($row['pubdate']));
		$pubdate .= (date('Y', strtotime($row['pubdate'])) - 2000);
		$title = $row['short_title'];
		$rant = $row['rant'];
		$content = strip_tags($row['content']);
		echo '<div class="eyisearch_results"><span class="eyi_ranttitle"><a href="' . $_SERVER['PHP_SELF'] . '?' . $rant . '">' . $title . '</a></span>';
		echo '<span class="eyi_pubdate"><em>' . $pubdate . '</em></span></div><div class="eyi_quote">';
		echo search_context($content, $q) . '&hellip;</div>';
	}
	echo '</li></ul></div>';
// Generate navigational page links if we have more than one page
	if($num_pages > 1) {
	echo '<div class="pagination"><span class="eyisearch">';
	echo generate_page_links($q, $cur_page, $num_pages);
	echo '</span></div>';
}
 	// there are search results; end the page without the rantlist from eyi.php
	if($num_pages != 0) {
	echo '<span id="eyilinkback"><a href="' . $_SERVER['PHP_SELF'] . '">Entertain Yourself Immediately</a></span>';
	echo '</div>';
	include('http://www.vybeauregard.net/footer.html');
	echo '</body></html>';
	exit;
	}
}
//.?.>
/*<form action = "random.php">
<h1>
<input type="submit" value="random!" class="eyisearch" id="submit" />
</h1>
</form>
*/
?>