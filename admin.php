<?php
require "settings.php";

// Global MySQL connection
//$linkID = @mysql_connect("$server", "$username", "$password");
//mysql_select_db("$database", $linkID);
// note: it appears admin area is not used. - RBS Jan 15, 2017

if ($_POST['action'] == "login") {
   if ($_POST['admin_p'] == $adminpass) {
      $expire=time()+60*60*24*30;
      setcookie("admin", "45VkQwsXdf90#2kVg83XslkrW", $expire);
      include "header.inc.php";
      print "<br><center><b><font color=blue>You are now logged in. Click <a href=\"admin.php\">here</a> to goto
      the admin menu.</font></b></center><br>\n";
      include "footer.inc.php";
      die;
   } else {
      include "header.inc.php";
      print "<br><center><font color=red><b>Incorrect Password!</b></font></center><br>\n";
      print "<form action=\"admin.php\" method=\"post\">
      <input type=\"hidden\" name=\"action\" value=\"login\">
      <table border=1 width=100%>
      <tr><td>Admin Password:</td><td><input type=\"password\" name=\"admin_p\" size=40></td></tr>
      <tr><td colspan=2 align=center><input type=\"submit\" value=\"Login\"></td></tr>
      </table>
      </form>\n";
      include "footer.inc.php";
      die;
   }
} else {
   if ($_POST['action'] != "loginas") {
      if ($_GET['action'] != "logout") {
         include "header.inc.php";
      }
   }
}

if ($_COOKIE['admin'] == "45VkQwsXdf90#2kVg83XslkrW") {
	print "<br><b>Admin Area</b><br><br>\n";
   // admin area

	// --------------------------------------
	//
	// Main Menu
	//
	//
   // --------------------------------------

   if (($_GET['action'] == "") and ($_POST['action'] == "")) {
		print "
		<li><a href=\"admin.php?action=article\">New Article</a></li>
		<li><a href=\"admin.php?action=article_l\">Manage Articles</a></li>
		<li>Links</li>
		<li>Settings</li>
		<li><a href=\"admin.php?action=category\">Categories</a></li>
		<li><a href=\"admin.php?action=logout\">Logout</a></li>\n";
   }
	if ($_GET['action'] == "article_l") {

      print "
		<table border=1 width=100%>
		<tr><td><b>Title</b></td><td><b>Action</b></td></tr>\n";
      $resultID = $core->new_mysql("SELECT * FROM `articals`");
      while ($row = $resultID->fetch_assoc()) {
			print "<tr><td>$row[title]</td><td><a href=\"admin.php?action=edit_a&item=$row[id]\">Edit</a> |
			<a href=\"admin.php?action=delete_a&item=$row[id]\">Delete</a></td></tr>\n";
		}
		print "</table>\n";
	}
	if ($_GET['action'] == "edit_a") {
      $resultID = $core->new_mysql("SELECT * FROM `articals` WHERE `id` = '$_GET[item]'");
      while ($row = $resultID->fetch_assoc()) {
	      $sValue = $row['artical'];
         $sValue = str_replace("'", "`", $sValue);
	      $sValue = str_replace(Chr(13), "", $sValue);
   	   $sValue = ereg_replace("\n", " ", $sValue);
	      $resultID2 = $core->new_mysql("SELECT * FROM `category`");
              while ($row2 = $resultID2->fetch_assoc()) {
         	$category .= "<option>$row2[category]";
	      }

	      print "<form action=\"admin.php\" method=post>
   	   <input type=\"hidden\" name=\"action\" value=\"edit_a\">
			<input type=\"hidden\" name=\"item\" value=\"$row[id]\">
      	<table border=\"1\" width=100%>
	      <tr><td><b>Select Category:</b></td><td><select name=\"category\"><option selected>$row[category]</option>$category</select></td></tr>
   	   <tr><td><b>Title of Article:</b></td><td><input type=\"text\" name=\"title\" value=\"$row[title]\" size=40></td></tr>
      	<tr><td colspan=2>\n";
	      ?>
   	   <script type="text/javascript">
      	<!--
	      // Automatically calculates the editor base path based on the _samples directory.
   	   // This is usefull only for these samples. A real application should use something like this:
      	// oFCKeditor.BasePath = '/fckeditor/' ;        // '/fckeditor/' is the default value.
	      var sBasePath = document.location.href.substring(0,document.location.href.lastIndexOf('_samples')) ;

   	   var oFCKeditor = new FCKeditor( 'FCKeditor1' ) ;
      	oFCKeditor.BasePath     = 'fckeditor/' ;
	      oFCKeditor.Height       = 300 ;
   	   oFCKeditor.Width        = 500 ;
      	oFCKeditor.Value        = '<?=$sValue?>' ;
	      oFCKeditor.Create() ;
   	   //-->
      	</script>

	      <?php
   	   print "</td></tr>
      	<tr><td colspan=2 align=center><input type=\"submit\" value=\"Edit Article\"></td></tr>
	      </table>
   	   </form>\n";
		}
	}
	if ($_POST['action'] == "edit_a") {
		$sValue = stripslashes( $_POST['FCKeditor1'] ) ;
		$result = $core->new_mysql("UPDATE `articals` SET
		`artical` = '$sValue',
		`title` = '$_POST[title]',
		`category` = '$_POST[category]'
		 WHERE `id` = '$_POST[item]'");
		if ($result == "TRUE") {
			print "<br>The article was updated.\n";
		} else {
			print "<font color=red>MySQL error</font>";
		}
	}
	if ($_GET['action'] == "delete_a") {
		$result = $core->new_mysql("DELETE FROM `articals` WHERE `id` = '$_GET[item]'");
		if ($result == "TRUE") {
			print "Article was removed.<br>\n";
		} else {
			print "<font color=red>MySQL error</font>\n";
		}
	}
	if ($_GET['action'] == "article") {
      $resultID = $core->new_mysql("SELECT * FROM `category`");
      while ($row = $resultID->fetch_assoc()) {
			$category .= "<option>$row[category]";
		}
		print "<form action=\"admin.php\" method=post>
		<input type=\"hidden\" name=\"action\" value=\"article\">
		<table border=\"1\" width=100%>
		<tr><td><b>Select Category:</b></td><td><select name=\"category\">$category</select></td></tr>
		<tr><td><b>Title of Article:</b></td><td><input type=\"text\" name=\"title\" size=40></td></tr>
		<tr><td colspan=2>\n";
		?>
      <script type="text/javascript">
		<!--
		// Automatically calculates the editor base path based on the _samples directory.
		// This is usefull only for these samples. A real application should use something like this:
		// oFCKeditor.BasePath = '/fckeditor/' ;        // '/fckeditor/' is the default value.
		var sBasePath = document.location.href.substring(0,document.location.href.lastIndexOf('_samples')) ;

		var oFCKeditor = new FCKeditor( 'FCKeditor1' ) ;
		oFCKeditor.BasePath     = 'fckeditor/' ;
		oFCKeditor.Height       = 300 ;
		oFCKeditor.Width			= 500 ;
		oFCKeditor.Value        = '' ;
		oFCKeditor.Create() ;
		//-->
      </script>

		<?php
		print "</td></tr>
		<tr><td colspan=2 align=center><input type=\"submit\" value=\"Create Article\"></td></tr>
		</table>
		</form>\n";
	}
	if ($_POST['action'] == "article") {
		$sValue = stripslashes( $_POST['FCKeditor1'] ) ;
		$sValue = str_replace("'", "`", $sValue);
		$result = $core->new_mysql("INSERT into `articals` (`title`,`artical`,`category`) VALUES ('$_POST[title]','$sValue','$_POST[category]')");
		if ($result == "TRUE") {
			print "Article was added.<br>\n";
		} else {
			print "There was a MySQL error.<br>\n";
		}
	}
	if ($_GET['action'] == "category") {
      print "<div id=\"category\">
      <form id=\"category\" onsubmit=\"return false;\">\n";
      print "<input type=\"hidden\" name=\"ref\" value=\"$_GET[item]\">\n";
      print "<table border=1 width=100%>
      <tr bgcolor=CCCCFF><td><b>Category</b></td><td><b>Delete</b></td></tr>\n";
      $resultID = $core->new_mysql("SELECT * FROM `category`");
      while ($row = $resultID->fetch_assoc()) {
         if ($x % 2) {
            $bgcolor = "bgcolor=#FFFFCC";
         } else {
            $bgcolor = "bgcolor=#FFFFFF";
         }
         print "<tr $bgcolor><td>$row[category]</td><td><input type=\"checkbox\" name=\"del$row[id]\" style=\"background:red\" value=\"on\" onclick=\"confirmdelete()\"></td></tr>\n";
      }
      print "<tr><td colspan=2><b>Add Category: <input type=\"text\" name=\"category\" size=50 ><input type=\"button\" value=\"Add\" onclick=\"sendRequest()\"></td></tr>\n";
      print "</table>
      </form></div>\n";
	}
   if ($_GET['action'] == "logout") {
      $value = "";
      setcookie("logged", $value);
      setcookie("uuname", $value);
      setcookie("admin", $value);
      include "header.inc.php";
      print "<br>You have been logged out. Please close the browser.<br>\n";

   }



   print "<center><br><a href=\"admin.php\">Admin Home</a></center>\n";
} else {
   print "<form action=\"admin.php\" method=\"post\">
   <input type=\"hidden\" name=\"action\" value=\"login\">
   <table border=1 width=100%>
   <tr><td>Admin Password:</td><td><input type=\"password\" name=\"admin_p\" size=40></td></tr>
   <tr><td colspan=2 align=center><input type=\"submit\" value=\"Login\"></td></tr>
   </table>
   </form>\n";
}
include "footer.inc.php";
?>

