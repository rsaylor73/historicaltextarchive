<?php
$link1 = " | <b><a href=\"books.php\">E-books</a></b>";
$link2 = " | <b>Section Listing</b>";

require "settings.php";
include "roman.php";
// Global MySQL connection
$linkID = @mysql_connect("$server", "$username", "$password");
mysql_select_db("$database", $linkID);

if ($_GET['action'] == "listbooks") {
	$result = $core->new_mysql("SELECT * FROM `sections` WHERE `secid` = '$_GET[sid]'");
	while ($row = $result->fetch_assoc()) {
		$link2 = " | <b><a href=\"books.php?action=listbooks&sid=$row[secid]\">$row[secname]</a></b>";
	}
}

if ($_GET['action'] == "nextpre") {
	$resultID2 = $core->new_mysql("SELECT * FROM `books_pre` WHERE `bid` = '$_GET[bid]'");
	while($row2=$resultID2->fetch_assoc()) {
		$bid = $row2['bid'];
		$title = $row2['title'];
	}
        $resultID2 = $core->new_mysql("SELECT * FROM `books` WHERE `bid` = '$bid'");
        while($row2=$resultID2->fetch_assoc()) {
		$sid = $row2['sid'];
	}

	$resultID = $core->new_mysql("SELECT * FROM `sections` WHERE `secid` = '$sid'");
	while ($row = $resultID->fetch_assoc()) {
		$link2 = " | <b><a href=\"books.php?action=listbooks&sid=$row[secid]\">$row[secname]</a> | $title</b>";
	}
}




//
if ($_GET['action'] == "nextchapter") {
	$resultID2 = $core->new_mysql("SELECT * FROM `books_chapters` WHERE `bid` = '$_GET[bid]' AND `cid` = '$_GET[cid]'");
	while ($row2 = $resultID2->fetch_assoc()) {
		$bid = $row2['bid'];
		$title = $row2['title'];
	}
	$resultID2 = $core->new_mysql("SELECT * FROM `books` WHERE `bid` = '$bid'");
        while ($row2 = $resultID2->fetch_assoc()) {
		$sid = $row2['sid'];
	}

	$resultID = $core->new_mysql("SELECT * FROM `sections` WHERE `secid` = '$sid'");
	while ($row = $resultID->fetch_assoc()) {
		$link2 = " | <b><a href=\"books.php?action=listbooks&sid=$row[secid]\">$row[secname]</a> | $title</b>";
	}
}
//



if ($_GET['action'] == "post") {
	$resultID2 = $core->new_mysql("SELECT * FROM `books_post` WHERE `bid` = '$_GET[bid]' AND `orderby` = '$_GET[pid]'");
	while ($row2 = $resultID2->fetch_assoc()) {
		$bid = $row2['bid'];
		$title = $row2['title'];
	}
	$resultID2 = $core->new_mysql("SELECT * FROM `books` WHERE `bid` = '$bid'");
	while ($row2 = $resultID2->fetch_assoc()) {
		$sid = $row2['sid'];
	}

	$resultID = $core->new_mysql("SELECT * FROM `sections` WHERE `secid` = '$sid'");
	while ($row = $resultID->fetch_assoc()) {
		$link2 = " | <b><a href=\"books.php?action=listbooks&sid=$row[secid]\">$row[secname]</a> | $title</b>";
	}

}
//
if ($_GET['action'] == "toc") {
	$resultID2 = $core->new_mysql("SELECT * FROM `books_post` WHERE `bid` = '$_GET[bid]'");
	while ($row2 = $resultID2->fetch_assoc()) {
		$bid = $row2['bid'];
		$title = "TOC";
	}
	$resultID2 = $core->new_mysql("SELECT * FROM `books` WHERE `bid` = '$bid'");
	while ($row2 = $resultID2->fetch_assoc()) {
		$sid = $row2['sid'];
	}

	$resultID = $core->new_mysql("SELECT * FROM `sections` WHERE `secid` = '$sid'");
	while ($row = $resultID->fetch_assoc()) {
		$link2 = " | <b><a href=\"books.php?action=listbooks&sid=$row[secid]\">$row[secname]</a> | $title</b>";
	}
}

include "header.inc.php";

if (($_GET['action'] == "") and ($_POST['action'] == "")) {
	// categories - this section lists the available categories
	$total = "0";
	$resultID = $core->new_mysql("SELECT * FROM `books`");
	while ($row = $resultID->fetch_assoc()) {
		$total++;
	}
	print "The Historical Text Archive contains $total books.<br><br>\n";

	$resultID = $core->new_mysql("SELECT * FROM `sections` ORDER BY `secname` ASC");
	while ($row = $resultID->fetch_assoc()) {
		$books = "0";
		$resultID2 = $core->new_mysql("SELECT * FROM `books` WHERE `sid` = '$row[secid]'");
		while ($row2 = $resultID2->fetch_assoc()) {
			$books++;
		}
		if ($books > 0) {
			print "<li><a href=\"books.php?action=listbooks&sid=$row[secid]\">$row[secname]</a> ($books books)</li>\n";
		}

	}


	print "<br>\n";

	print "<br><b>Most read:<br></b>\n";
	$resultID = $core->new_mysql("SELECT * FROM `books` ORDER BY `pageviews` DESC LIMIT 0,10");
	while ($row = $resultID->fetch_assoc()) {
		print "<li class=list><a href=\"books.php?action=nextpre&bid=$row[bid]\">$row[title]</a></li>\n";
	}
}




// This section will display a list of books in the selected category
if ($_GET['action'] == "listbooks") {
	$resultID = $core->new_mysql("SELECT * FROM `books` WHERE `sid` = '$_GET[sid]' ORDER BY `bid` DESC");
	while ($row = $resultID->fetch_assoc()) {
		$count++;
	}
	print "This section contains $count books<br><br>\n";

	$resultID = $core->new_mysql("SELECT * FROM `books` WHERE `sid` = '$_GET[sid]' ORDER BY `bid` DESC");
	while ($row = $resultID->fetch_assoc()) {
		print "<li><a href=\"books.php?action=nextpre&bid=$row[bid]\">$row[title]</a></li>\n";
	}
}


// This section will display only the pre sections.
if ($_GET['action'] == "nextpre") {
	if ($_GET['pre'] == "") {
		$_GET['pre'] = "1";
	}

	$resultID = $core->new_mysql("SELECT * FROM `books_pre` WHERE `bid` = '$_GET[bid]' AND `orderby` = '$_GET[pre]'");
	while ($row = $resultID->fetch_assoc()) {
		$title = $row['title'];
		$content = $row['content'];
		$next_pre = $row['orderby'] + 1;
		$prev_pre = $_GET['pre'] - 1;
		$resultID2 = $core->new_mysql("SELECT * FROM `books_pre` WHERE `bid`='$_GET[bid]' AND `orderby` = '$prev_pre'");
		while ($row2 = $resultID2->fetch_assoc()) {
			$q = "<a href=\"books.php?action=nextpre&bid=$row[bid]&pre=$prev_pre\">$row2[title]</a>";
		}
		$resultID2 = $core->new_mysql("SELECT * FROM `books_pre` WHERE `bid`='$_GET[bid]' AND `orderby` = '$next_pre'");
		while ($row2 = $resultID2->fetch_assoc()) {
			$p = "<a href=\"books.php?action=nextpre&bid=$row[bid]&pre=$next_pre\">$row2[title]</a>";
		}
	}


	if ($prev_pre <= "0") {
		$q = "<a href=\"books.php?action=toc&bid=$_GET[bid]\">TOC</a>";
	}
	if ($found != "1") {
		// make $p = next chapter instead of pre
		$resultID = $core->new_mysql("SELECT * FROM `books_chapters` WHERE `bid` = '$_GET[bid]' AND `cid` = '1'");
		while ($row = $resultID->fetch_assoc()) {
			$p = "<a href=\"books.php?action=nextchapter&bid=$row[bid]&cid=1\">1: $row[title]</a>";
		}
	}

	print "<h2>$title</h2>
   <center>$q || $p</center><br><br>
   $content<br><br>
   <hr>\n";
}


// This section displays the chapters
if ($_GET['action'] == "nextchapter") {
	if ($_GET['cid'] == "") {
		$cid = "1";
		$_GET['cid'] = "1";
	}
	$prev_cid = $_GET['cid'] - 1;
	$next_cid = $_GET['cid'] + 1;

	$resultID = $core->new_mysql("SELECT * FROM `books_chapters` WHERE `bid` = '$_GET[bid]' AND `cid` = '$_GET[cid]'");
	while ($row = $resultID->fetch_assoc()) {
		$title = $row['title'];
		$content = $row['content'];
		$resultID2 = $core->new_mysql("SELECT * FROM `books_chapters` WHERE `bid`='$_GET[bid]' AND `cid` = '$prev_cid'");
		while ($row2=$resultID2->fetch_assoc()) {
	        	$q = "<< <a href=\"books.php?action=nextchapter&bid=$row[bid]&cid=$prev_cid\">$prev_cid: $row2[title]</a>";
		}

		$resultID2 = $core->new_mysql("SELECT * FROM `books_chapters` WHERE `bid`='$_GET[bid]' AND `cid` = '$next_cid'");
		while ($row2 = $resultID2->fetch_assoc()) {
			$p = "<a href=\"books.php?action=nextchapter&bid=$row[bid]&cid=$next_cid\">$next_cid: $row2[title]</a> >>";
		}
	}


	if ($q == "") {
		$resultID = $core->new_mysql("SELECT * FROM `books_pre` WHERE `bid` = '$_GET[bid]' ORDER BY `orderby` ASC");
		while ($row = $resultID->fetch_assoc()) {
			$q = "<a href=\"books.php?action=nextpre&bid=$row[bid]&pre=$row[orderby]\">$row[title]</a>";
		}
	}
	if ($p == "") {
		$resultID = $core->new_mysql("SELECT * FROM `books_post` WHERE `bid` = '$_GET[bid]' ORDER BY `orderby` DESC");
		while ($row = $resultID->fetch_assoc()) {
			$p = "<a href=\"books.php?action=post&bid=$row[bid]&pid=$row[orderby]\">$row[title]</a>";
		}
	}
	if ($p == "") {
		$p = "<a href=\"books.php?action=toc&bid=$_GET[bid]\">TOC</a>";
	}
   print "<h2>$_GET[cid]: $title</h2>
   <center>$q || $p</center><br><br>
   $content
   <hr><center>$q || $p</center>\n";
}



// This section displays the post parts of the main chapters
if ($_GET['action'] == "post") {
   if ($_GET['pid'] == "") {
      $pid = "1";
      $_GET['pid'] = "1";
   }
   $prev_pid = $_GET['pid'] - 1;
   $next_pid = $_GET['pid'] + 1;

   $resultID = $core->new_mysql("SELECT * FROM `books_post` WHERE `bid` = '$_GET[bid]' AND `orderby` = '$_GET[pid]'");
   while ($row = $resultID->fetch_assoc()) {
      $row = mysql_fetch_assoc($resultID);
      $title = $row['title'];
      $content = $row['content'];
      $resultID2 = $core->new_mysql("SELECT * FROM `books_post` WHERE `bid`='$_GET[bid]' AND `orderby` = '$prev_pid'");
      while ($row2 = $resultID2->fetch_assoc()) {
         $q = "<< <a href=\"books.php?action=post&bid=$row[bid]&pid=$prev_pid\">$row2[title]</a>";
      }
      $resultID2 = $core->new_mysql("SELECT * FROM `books_post` WHERE `bid`='$_GET[bid]' AND `orderby` = '$next_pid'");
      while ($row2 = $resultID2->fetch_assoc()) {
         $found = "1";
         $p = "<a href=\"books.php?action=post&bid=$row[bid]&pid=$next_pid\">$row2[title]</a> >>";
      }
   }
   if ($q == "") {
      $resultID = $core->new_mysql("SELECT * FROM `books_chapters` WHERE `bid` = '$_GET[bid]' ORDER BY `cid` ASC");
      while ($row = $resultID->fetch_assoc()) {
         $row = mysql_fetch_assoc($resultID);
	 $bid = $row['bid'];
         $q = "<a href=\"books.php?action=nextchapter&bid=$row[bid]&cid=$row[cid]\">$row[cid]: $row[title]</a>";
      }

   }
   if ($p == "") {
		$p = "<a href=\"books.php?action=toc&bid=$_GET[bid]\">TOC</a>";
   }
   print "<h2>$title</h2>
   <center>$q || $p</center><br><br>
   $content
   <hr><center>$q || $p</center>\n";
}









// finish here robert

if ($_GET['action'] == "toc") {
	$resultID = $core->new_mysql("SELECT * FROM `books` WHERE `bid` = '$_GET[bid]'");
	while ($row = $resultID->fetch_assoc()) {
		$author = $row['author'];
		$title = $row['title'];
	}
	print "<b>$title</b><br><br>
	by <b>$author</b><br><br>\n";
	print "<blockquote>";
	// pre
	$count = "1";
   $resultID = $core->new_mysql("SELECT * FROM `books_pre` WHERE `bid` = '$_GET[bid]' ORDER BY `orderby` ASC");
   while ($row = $resultID->fetch_assoc()) {
		rn($count);
		print "<a href=\"books.php?action=nextpre&bid=$row[bid]&pre=$row[orderby]\">$row[title]</a><br>\n";
		$count++;
	}

	// chapters
	print "<br>\n";
   $count = "1";
   $resultID = $core->new_mysql("SELECT * FROM `books_chapters` WHERE `bid` = '$_GET[bid]' ORDER BY `cid` ASC");
   while ($row = $resultID->fetch_assoc()) {
		print "$count. <a href=\"books.php?action=nextchapter&bid=$row[bid]&cid=$row[cid]\">$row[title]</a><br>\n";
		$count++;
	}	

   // post
   print "<br>\n";
   $count = "1";
   $resultID = $core->new_mysql("SELECT * FROM `books_post` WHERE `bid` = '$_GET[bid]' ORDER BY `orderby` ASC");
   while ($row = $resultID->fetch_assoc()) {
		abc($count);
      print "<a href=\"books.php?action=post&bid=$row[bid]&pid=$row[orderby]\">$row[title]</a><br>\n";
      $count++;
   }
	print "</blockquote>";
}


include "footer.inc.php";
?>
