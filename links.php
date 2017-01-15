<?php
require "settings.php";
// Global MySQL connection
//$linkID = @mysql_connect("$server", "$username", "$password");
//mysql_select_db("$database", $linkID);

$link1 = " | <b><a href=\"links.php\">Links</a></b>";
$link2 = " | <b>Category Listing</b>";

if ($_GET['action'] == "sub") {
   $resultID = $core->new_mysql("SELECT * FROM `links_categories` WHERE `cid` = '$_GET[cid]'");
   while ($row = $resultID->fetch_assoc()) {
		$link2 = " | <b><a href=\"links.php?action=sub&cid=$row[cid]\">$row[title]</a>";
	}
}
//
if ($_GET['action'] == "links") {
   $resultID = $core->new_mysql("SELECT * FROM `links_categories` WHERE `cid` = '$_GET[cid]'");
   while ($row = $resultID->fetch_assoc()) {
		$link2 = " | <b><a href=\"links.php?action=sub&cid=$row[cid]\">$row[title]</a>";
	}
   $resultID = $core->new_mysql("SELECT * FROM `links_subcategories` WHERE `cid` = '$_GET[cid]' AND `sid` = '$_GET[sid]'");
   while ($row = $resultID->fetch_assoc()) {
		$link2 .= " | <b><a href=\"links.php?action=links&cid=$_GET[cid]&sid=$_GET[sid]\">$row[title]</a>";
	}
}
if ($_GET['action'] == "links2") {
	$links2 = " | Loading link...";
}
include "header.inc.php";

if (($_GET['action'] == "") and ($_POST['action'] == "")) {
   // categories - this section lists the available categories


   $resultID = $core->new_mysql("SELECT * FROM `links_categories` ORDER BY `title` ASC");
   while ($row = $resultID->fetch_assoc()) {
      $count = "0";
      $resultID2 = $core->new_mysql("SELECT * FROM `links_links` WHERE `cid` = '$row[cid]' ORDER BY `title` ASC");
      while ($row2 = $resultID2->fetch_assoc()) {
         $count++;
      }
	$c_total = $c_total + $count;
      $count2 = "1";
      $resultID2 = $core->new_mysql("SELECT * FROM `links_subcategories` WHERE `cid` = '$row[cid]' ORDER BY `title` ASC");
      while ($row2 = $resultID2->fetch_assoc()) {
         $count2++;
      }
	$c_total2 = $c_total2 + $count2;
      //print "The Historical Text Archive contains 7775 links in 24 categories<br><br>\n";


		if ($count > 0) {
			$html .= "<li><a href=\"links.php?action=sub&cid=$row[cid]\">$row[title]</a>($count links in $count2 subcat.)</li>";
		}
	}
	print "The Historical Text Archive contains $c_total links in $c_total2 categories<br><br>\n";
	print "$html";
}

if ($_GET['action'] == "sub") {


   $resultID = $core->new_mysql("SELECT * FROM `links_subcategories` WHERE `cid` = '$_GET[cid]' ORDER BY `title` ASC");
   while ($row = $resultID->fetch_assoc()) {
      $count = "0";
		$found = "1";
		$count3++;
      $resultID2 = $core->new_mysql("SELECT * FROM `links_links` WHERE `cid` = '$_GET[cid]' and `sid` = '$row[sid]' ORDER BY `title` ASC");
      while ($row2 = $resultID2->fetch_assoc()) {
         $count++;
         $count2++;
      }
	}

	if ($found != "1") {
      $resultID2 = $core->new_mysql("SELECT * FROM `links_links` WHERE `cid` = '$_GET[cid]' ORDER BY `title` ASC");
      while ($row2 = $resultID2->fetch_assoc()) {
         $count++;
         $count2++;
      }
		print "This category contains $count2 links.\n";
	} else {
	   print "This category contains $count2 links in $count3 subcategories<br><br>\n";
	}

	// normal with general
	$count = 0;
   $resultID = $core->new_mysql("SELECT * FROM `links_links` WHERE `cid` = '$_GET[cid]' and `sid` = '0'");
   while ($row = $resultID->fetch_assoc()) {
		$count++;
		$dis = "1";
   }
	if ($dis == "1") {
		print "<li><a href=\"links.php?action=links&cid=$row[cid]&sid=0\">General</a>($count links)</li>\n";
	}

	// normal with subs
   $resultID = $core->new_mysql("SELECT * FROM `links_subcategories` WHERE `cid` = '$_GET[cid]' ORDER BY `title` ASC");
   while ($row = $resultID->fetch_assoc()) {
		$count = "0";
	   $resultID2 = $core->new_mysql("SELECT * FROM `links_links` WHERE `cid` = '$_GET[cid]' and `sid` = '$row[sid]' ORDER BY `title` ASC");
           while ($row2 = $resultID2->fetch_assoc()) {
			$count++;
			$count2++;
		}
		if ($count > 0) {
			print "<li><a href=\"links.php?action=links&cid=$row[cid]&sid=$row[sid]\">$row[title]</a>($count links)</li>\n";
		}
	}
}
if ($_GET['action'] == "links") {
   $resultID = $core->new_mysql("SELECT * FROM `links_links` WHERE `cid` = '$_GET[cid]' and `sid` = '$_GET[sid]' ORDER BY `title` ASC");
   while ($row = $resultID->fetch_assoc()) {
		$count4++;
	}

	print "This subcategory contains $count4 links<br><br>\n";

   $resultID = $core->new_mysql("SELECT * FROM `links_links` WHERE `cid` = '$_GET[cid]' and `sid` = '$_GET[sid]' ORDER BY `title` ASC");
   while ($row = $resultID->fetch_assoc()) {
		print "<li><a href=\"links.php?action=links2&lid=$row[lid]\" target=_blank>$row[title]</a>($row[hits] clicks)<br>
		$row[description]</li>";
	}
}
if ($_GET['action'] == "links2") {
   $resultID = $core->new_mysql("SELECT * FROM `links_links` WHERE `lid` = '$_GET[lid]'");
   while ($row = $resultID->fetch_assoc()) {
		$url = $row['url'];
		$hits = $row['hits'] + 1;
	}
	$resultID = $core->new_mysql("UPDATE `links_links` SET `hits` = '$hits' WHERE `lid` = '$_GET[lid]'");
	print "<meta http-equiv=\"refresh\" content=\"1;url=$url\">
	<br><br><center>Loading <a href=\"$url\">$url</a> Click on the link if the page does not refresh...<br>\n";
}

include "footer.inc.php";
?>
