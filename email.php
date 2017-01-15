<?php
require "settings.php";


// Global MySQL connection
//$linkID = @mysql_connect("$server", "$username", "$password");
//mysql_select_db("$database", $linkID);


if ($_GET['action'] == "section") {
   $resultID = $core->new_mysql("SELECT * FROM `seccont` WHERE `artid` = '$_GET[artid]'");
   while ($row = $resultID->fetch_assoc()) {
      $title = $row['title'];
   }
	$date = date("Y");
	print "<b>Historical Text Archive &copy 2003 - $date</b><br>
	Email this page: <a href=\"http://$_SERVER[HTTP_HOST]/sections.php?action=read&artid=$_GET[artid]\">http://$_SERVER[HTTP_HOST]/sections.php?action=read&artid=$_GET[artid]</a><br>$title<br>\n";
	print "<form action=\"email.php\" method=post>
	<input type=\"hidden\" name=\"action\" value=\"article\">
	<input type=\"hidden\" name=\"item\" value=\"$_GET[artid]\">
	<table border=0 width=100%>
	<tr><td>Recipient(s):*</td><td><input type=text name=\"to\" size=40></td></tr>
	<tr><td>Your email:</td><td><input type=text name=\"from\" size=40></td></tr>
	<tr><td colspan=2>Add a message to your email:<br>
	<textarea name=\"msg\" cols=60 rows=4></textarea></td></tr>
	<tr><td colspan=2><input type=\"submit\" value=\"Email\"></td></tr>
	</table>
	* separate multiple addresses with ,<br>\n";
}

if ($_POST['action'] == "article") {
	$msg = "You have been invited to read an article on Historical Text Archive.<br><br>
	From: $_POST[from]<br><br>
	Article: <a href=\"http://$_SERVER[HTTP_HOST]/sections.php?action=read&artid=$_POST[item]\">http://$_SERVER[HTTP_HOST]/sections.php?action=read&artid=$_POST[item]</a><br><br>
	$_POST[msg]<br>";
	$subj = "You have been sent an article to read.";
	mail($_POST['to'],$subj,$msg,$extra);
	print "<br>Your email has been sent.<br>\n";
}

?>
