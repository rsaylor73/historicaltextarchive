<?php
$link1 = " | <b><a href=\"sections.php\">Articles</a></b>";
$link2 = " | <b>Section Listing</b>";
require "settings.php";

// Global MySQL connection
//$linkID = @mysql_connect("$server", "$username", "$password");
//mysql_select_db("$database", $linkID);


if ($_GET['action'] == "list") {
	$sql = "SELECT * FROM `sections` WHERE `secid` = '$_GET[secid]'";
	$result = $core->new_mysql($sql);
	while ($row = $result->fetch_assoc()) {
                $link2 = " | <b><a href=\"sections.php?action=list&secid=$_GET[secid]\">$row[secname]</a></b>";
	}

	/*
	$resultID = @mysql_query("SELECT * FROM `sections` WHERE `secid` = '$_GET[secid]'", $linkID);
	for ($x= 0; $x < mysql_num_rows($resultID); $x++) {
   	$row = mysql_fetch_assoc($resultID);
		$link2 = " | <b><a href=\"sections.php?action=list&secid=$_GET[secid]\">$row[secname]</a></b>";
	}
	*/
}
if ($_GET['action'] == "read") {
	$sql = "SELECT * FROM `seccont` WHERE `artid` = '$_GET[artid]'";
	$result = $core->new_mysql($sql);
	while ($row = $result->fetch_assoc()) {
		$sql2 = "SELECT * FROM `sections` WHERE `secid` = '$row[secid]'";
		$result2 = $core->new_mysql($sql2);
		while ($row2 = $result2->fetch_assoc()) {
			$linkA = "<b><a href=\"sections.php?action=list&secid=$row2[secid]\">$row2[secname]</a>";
		}
		$counter = $row['counter'] + 1;
		$content = $row['content'];
		$title = $row['title'];
	}
	$link2 = " | $linkA | <b>$title</b>";
/*
   $resultID = @mysql_query("SELECT * FROM `seccont` WHERE `artid` = '$_GET[artid]'", $linkID);
   for ($x= 0; $x < mysql_num_rows($resultID); $x++) {
      $row = mysql_fetch_assoc($resultID);
	   $resultID2 = @mysql_query("SELECT * FROM `sections` WHERE `secid` = '$row[secid]'", $linkID);
   	for ($x2= 0; $x2 < mysql_num_rows($resultID2); $x2++) {
      	$row2 = mysql_fetch_assoc($resultID2);
			$linkA = "<b><a href=\"sections.php?action=list&secid=$row2[secid]\">$row2[secname]</a>";
		}
      $counter = $row['counter'] + 1;
      $content = $row['content'];
      $title = $row['title'];
   }
$link2 = " | $linkA | <b>$title</b>";
*/
}

include "header.inc.php";
include "roman.php";

if (($_GET['action'] == "") and ($_POST['action'] == "")) {
   // categories - this section lists the available categories
   $total = "0";
//   $resultID = @mysql_query("SELECT * FROM `books`", $linkID);
//   for ($x= 0; $x < mysql_num_rows($resultID); $x++) {
//      $row = mysql_fetch_assoc($resultID);
//      $total++;
//   }

   $xy1 = "0";
   $xy2 = "0";

	$sql = "SELECT * FROM `seccont`";
	$result = $core->new_mysql($sql);
	while ($row = $result->fetch_assoc()) {
		$xy1++;
	}

	/*
   $resultID = @mysql_query("SELECT * FROM `seccont` ", $linkID);
   for ($x= 0; $x < mysql_num_rows($resultID); $x++) {
      $row = mysql_fetch_assoc($resultID);
      $xy1++;
   }
	*/

	$sql = "SELECT * FROM `sections` ORDER BY `secname` ASC";
        $result = $core->new_mysql($sql);
        while ($row = $result->fetch_assoc()) {
		$xy2++;
	}

	/*
   $resultID = @mysql_query("SELECT * FROM `sections` ORDER BY `secname` ASC", $linkID);
   for ($x= 0; $x < mysql_num_rows($resultID); $x++) {
      $row = mysql_fetch_assoc($resultID);
		$xy2++;
	}
	*/

   print "The Historical Text Archive contains $xy1 articles in $xy2 sections<br><br>";

	$sql = "SELECT * FROM `sections` ORDER BY `secname` ASC";
	$result = $core->new_mysql($sql);
	while($row = $result->fetch_assoc()) {
		$art = "0";
		$sql2 = "SELECT * FROM `seccont` WHERE `secid` = '$row[secid]'";
		$result2 = $core->new_mysql($sql2);
		while($row2 = $result2->fetch_assoc()) {
			$art++;
		}
		if ($art > 0) {
			print "<li><a href=\"sections.php?action=list&secid=$row[secid]&total=$art\">$row[secname]</a> ($art Articles)</li>\n";
		}
	}

	/*
   $resultID = @mysql_query("SELECT * FROM `sections` ORDER BY `secname` ASC", $linkID);
   for ($x= 0; $x < mysql_num_rows($resultID); $x++) {
      $row = mysql_fetch_assoc($resultID);
      $art = "0";
      $resultID2 = @mysql_query("SELECT * FROM `seccont` WHERE `secid` = '$row[secid]'", $linkID);
      for ($x2= 0; $x2 < mysql_num_rows($resultID2); $x2++) {
         $row2 = mysql_fetch_assoc($resultID2);
         $art++;
      }
      if ($art > 0) {
         print "<li><a href=\"sections.php?action=list&secid=$row[secid]&total=$art\">$row[secname]</a> ($art Articles)</li>\n";
      }
   }
	*/

   print "<br>\n";
}
if ($_GET['action'] == "list") {

	$total = "0";
	$sql = "SELECT * FROM `seccont` WHERE `secid` = '$_GET[secid]' ORDER BY `title` ASC";
	$result = $core->new_mysql($sql);
	while ($row = $result->fetch_assoc()) {
		$total++;
	}

	/*
   $resultID = @mysql_query("SELECT * FROM `seccont` WHERE `secid` = '$_GET[secid]' ORDER BY `title` ASC", $linkID);
   for ($x= 0; $x < mysql_num_rows($resultID); $x++) {
      $row = mysql_fetch_assoc($resultID);
		$total++;
	}
	*/

	print "This section contains $total articles<br><br>\n";

	$sql = "SELECT * FROM `seccont` WHERE `secid` = '$_GET[secid]' ORDER BY `title` ASC";
	$result = $core->new_mysql($sql);
	while ($row = $result->fetch_assoc()) {
                if (strlen($row['title']) <= 50) {
                        $str = $row['title'];
                } else{
                        $str =  $row['title'];
                $str =  substr($str,0,47);
                $str .= '...';
                }
                print "<li><a href=\"sections.php?action=read&artid=$row[artid]\" title=\"$row[title]\">$str</a> (read: $row[counter])</li>\n";
	}

	/*
   $resultID = @mysql_query("SELECT * FROM `seccont` WHERE `secid` = '$_GET[secid]' ORDER BY `title` ASC", $linkID);
   for ($x= 0; $x < mysql_num_rows($resultID); $x++) {
      $row = mysql_fetch_assoc($resultID);
		if (strlen($row['title']) <= 50) {
			$str = $row['title'];
		} else{
			$str =  $row['title'];
   		$str =  substr($str,0,47);
   		$str .= '...';
		}
		print "<li><a href=\"sections.php?action=read&artid=$row[artid]\" title=\"$row[title]\">$str</a> (read: $row[counter])</li>\n";
	}
	*/
}


if ($_GET['action'] == "read") {
	$sql = "SELECT * FROM `seccont` WHERE `artid` = '$_GET[artid]'";
	$result = $core->new_mysql($sql);
	$row = $result->fetch_assoc();
        $counter = $row['counter'] + 1;
        $content = $row['content'];
        $title = $row['title'];
	$sql2 = "UPDATE `seccont` SET `counter` = '$counter' WHERE `artid` = '$_GET[artid]'";
	$result2 = $core->new_mysql($sql2);


	/*
   $resultID = @mysql_query("SELECT * FROM `seccont` WHERE `artid` = '$_GET[artid]'", $linkID);
   for ($x= 0; $x < mysql_num_rows($resultID); $x++) {
      $row = mysql_fetch_assoc($resultID);
		$counter = $row['counter'] + 1;
		$content = $row['content'];
		$title = $row['title'];
	}
	$result = mysql_query("UPDATE `seccont` SET `counter` = '$counter' WHERE `artid` = '$_GET[artid]'", $linkID);
	*/

	print "<hr>
	<table border=0 width=100%>
	<tr><td width=25%><font size=-1><a href=\"email.php?action=section&artid=$row[artid]\" target=_blank>Email to a friend</a></font></td>
	<td><a href=\"email.php?action=section&artid=$row[artid]\" target=_blank><img src=email_icon.gif border=0></a></td></tr>
	<tr><td width=25%><font size=-1><a href=\"print.php?action=section&artid=$row[artid]\" target=_blank>Printer friendly</font></td>
	<td><a href=\"print.php?action=section&artid=$row[artid]\" target=_blank><img src=print_icon.gif border=0></a></td></tr>
	</table>
	<hr>
	<h2>$title</h2>
	<br>$content<br>\n";
}
include "footer.inc.php";
?>
