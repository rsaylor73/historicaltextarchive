<?php
require "settings.php";
include "header.inc.php";

$linkID = @mysql_connect("$server", "$username", "$password");
mysql_select_db("$database", $linkID);


?>

            <font face="Verdana,Arial,Helvetica"><p>Welcome to Don Mabry's <b>Historical Text Archive !</b></p><p>

<p>The <i>HTA</i> is in its 25th year of publishing high quality articles, books, essays, documents, historical photos, and links, screened for content, for a broad range of historical subjects. It was founded in 1990 in Mississippi and is one of the oldest history sites on the Internet. This site is dynamic with regular additions to its contents and its link collection.<br><br><div class=more><a href="about.php">Read more.</a></div></p>

<p>
		The site is divided into three sections: <a href="sections.php">articles</a>, <a href=books.php>e-books</a>, and <a href=links.php>links</a>. The article section contains the articles, documents, essays, and photographs. You can reach any of these by using the navigation table on the left of the screen or by using the breadcrumbs.</p>

<p>Enjoy the site! <br><br><div class=more><a href="support.php">Read more...</a></div></p></p>
<p><b>HTA Contents:</b> 

<?php
	// version 2 - Robert Saylor (Jan 14, 2017)

	// init vars
	$books = "0";
	$links = "0";
	$art = "0";

	// count books
	$sql = "SELECT * FROM `books`";
	$result = $core->new_mysql($sql);
	while ($row = $result->fetch_assoc()) {
		$books++;
	}

	// count links
	$sql = "SELECT * FROM `links_links`";
        $result = $core->new_mysql($sql);
        while ($row = $result->fetch_assoc()) {
		$links++;
	}

	// count art
	$sql = "SELECT * FROM `seccont`";
        $result = $core->new_mysql($sql);
        while ($row = $result->fetch_assoc()) {
		$art++;
	}

	/*
	$books = "0";
	$resultID = @mysql_query("SELECT * FROM `books`", $linkID);
	for ($x= 0; $x < mysql_num_rows($resultID); $x++) {
	$row = mysql_fetch_assoc($resultID);
		$books++;
	}
	*/

	/*
	$links = "0";
	$resultID = @mysql_query("SELECT * FROM `links_links`", $linkID);
	for ($x= 0; $x < mysql_num_rows($resultID); $x++) {
		$row = mysql_fetch_assoc($resultID);
		$links++;
	}
	*/

	/*
	$art = "0";
	$resultID = @mysql_query("SELECT * FROM `seccont`", $linkID);
	for ($x= 0; $x < mysql_num_rows($resultID); $x++) {
		$row = mysql_fetch_assoc($resultID);
		$art++;
	}
	*/
?>

<?=$art?> <a href="sections.php">articles</a>, 
<?=$books?> <a href="books.php">books</a> and
<?=$links?> <a href="links.php">links</a>.

</p>

<b>10 MOST RECENT ARTICLES:</b><br>

<?php
	$sql = "SELECT * FROM `seccont` ORDER BY `artid` DESC LIMIT 0,10";
	$result = $core->new_mysql($sql);
	while ($row = $result->fetch_assoc()) {
		if (strlen($row['title']) <= 50) {
			$str = $row['title'];
		} else {
			$str =  $row['title'];
			$str =  substr($str,0,47);
			$str .= '...';
		}
		print "<li><a href=\"sections.php?action=read&artid=$row[artid]\" title=\"$row[title]\">$str</a></li>\n";
	}

	print "<br><br><b>5 MOST RECENT BOOKS:</b><br>\n";
	$sql = "SELECT * FROM `books` ORDER BY `bid` DESC LIMIT 0,5";
	$result = $core->new_mysql($sql);
	while ($row = $result->fetch_assoc()) {
		if (strlen($row['title']) <= 50) {
			$str = $row['title'];
		} else{
			$str =  $row['title'];
			$str =  substr($str,0,47);
			$str .= '...';
		}
		print "<li><a href=\"books.php?action=nextpre&bid=$row[bid]\" title=\"$row[title]\">$str</a></li>\n";
	}


/*
   $resultID = @mysql_query("SELECT * FROM `seccont` ORDER BY `artid` DESC LIMIT 0,10", $linkID);
   for ($x= 0; $x < mysql_num_rows($resultID); $x++) {
      $row = mysql_fetch_assoc($resultID);
      if (strlen($row['title']) <= 50) {
         $str = $row['title'];
      } else{
         $str =  $row['title'];
         $str =  substr($str,0,47);
         $str .= '...';
      }
      print "<li><a href=\"sections.php?action=read&artid=$row[artid]\" title=\"$row[title]\">$str</a></li>\n";
   }
*/

/*
	print "<br><br><b>5 MOST RECENT BOOKS:</b><br>\n";
   $resultID = @mysql_query("SELECT * FROM `books` ORDER BY `bid` DESC LIMIT 0,5", $linkID);
   for ($x= 0; $x < mysql_num_rows($resultID); $x++) {
      $row = mysql_fetch_assoc($resultID);
      if (strlen($row['title']) <= 50) {
         $str = $row['title'];
      } else{
         $str =  $row['title'];
         $str =  substr($str,0,47);
         $str .= '...';
      }
      print "<li><a href=\"books.php?action=nextpre&bid=$row[bid]\" title=\"$row[title]\">$str</a></li>\n";
   }
*/

include "footer.inc.php";
?>
