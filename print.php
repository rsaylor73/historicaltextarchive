<?php
require "settings.php";


// Global MySQL connection
//$linkID = @mysql_connect("$server", "$username", "$password");
//mysql_select_db("$database", $linkID);


if ($_GET['action'] == "section") {
   $resultID = $core->new_mysql("SELECT * FROM `seccont` WHERE `artid` = '$_GET[artid]'");
   while ($row = $resultID->fetch_assoc()) {
      $article = $row['content'];
      $title = $row['title'];
   }
	?>
	<p><A HREF="#" onClick="window.print()"><img src="print_icon.gif" alt="Printer friendly version" border=0></a>
 	<A HREF="#" onClick="window.print()">Print this page</A></p>
	<?php
	$date = date("Y");
	print "<b>Historical Text Archive &copy 1990 - $date</b><br>
	Printer friendly version of: <a href=\"http://$_SERVER[HTTP_HOST]/sections.php?action=read&artid=$_GET[artid]\">http://$_SERVER[HTTP_HOST]/sections.php?action=read&artid=$_GET[artid]</a><br><br>\n";
	print "<br>$title</b><br><br>
	$article<br>\n";
}

?>
