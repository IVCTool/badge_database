<?php require_once('Connections/badgesdbcon.php'); ?>
<?php
//initialize the session
if (!isset($_SESSION)) {
  session_start();
}

// ** Logout the current user. **
$logoutAction = $_SERVER['PHP_SELF']."?doLogout=true";
if ((isset($_SERVER['QUERY_STRING'])) && ($_SERVER['QUERY_STRING'] != "")){
  $logoutAction .="&". htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_GET['doLogout'])) &&($_GET['doLogout']=="true")){
  //to fully log out a visitor we need to clear the session varialbles
  $_SESSION['MM_Username'] = NULL;
  $_SESSION['MM_UserGroup'] = NULL;
  $_SESSION['PrevUrl'] = NULL;
  unset($_SESSION['MM_Username']);
  unset($_SESSION['MM_UserGroup']);
  unset($_SESSION['PrevUrl']);
	
  $logoutGoTo = "index.php";
  if ($logoutGoTo) {
    header("Location: $logoutGoTo");
    exit;
  }
}
?>
<?php require_once('Connections/badgesdbcon.php'); ?>
<?php require_once('include/getsqlvaluestring.php'); ?>
<?php

if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($badgesdbcon, $theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  $theValue = function_exists("mysqli_real_escape_string") ? mysqli_real_escape_string($theValue) : mysqli_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

 
$query_badges = "SELECT * FROM badges ORDER BY identifier ASC";
$badges = mysqli_query($badgesdbcon, $query_badges) or die(mysqli_error());
$row_badges = mysqli_fetch_assoc($badges);
$totalRows_badges = mysqli_num_rows($badges);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Edit Badges</title>
<link href="css/admin.css" rel="stylesheet" type="text/css" />
</head>

<body>
<h1>Interoperability Badges</h1>
<table width="100%" border="1">
  <tr>
    <td><strong>Identifier</strong></td>
    <td><strong>Description</strong></td>
    <td><strong>Graphic</strong></td>
    <td><strong>Badge dependancies</strong></td>
    <td><strong>Requirements</strong></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
<?php
//The server behaviour fetches the first one
do {
?>
	
	<tr>
    <form action="badge_update.php" method="post" id="updateform">
    <td><?php echo $row_badges["identifier"] ?></td>
    <td><?php echo $row_badges["description"] ?></td>
    <td><?php echo '<img width="100" height="137" src="badge_graphics/' . $row_badges["graphicfile"] . '" />' ?></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td><input type="hidden" name="id" id="hiddenField" value="<?php echo $row_badges["id"]?>"/>      <input type="submit" name="button2" id="button2" value="Edit" /></td>
    </form>
    <form action="badge_delete_confirm.php" method="post" enctype="multipart/form-data" id="deleteform">
    <td><input type="hidden" name="id" id="hiddenField" value="<?php echo $row_badges["id"]?>"/>
      <input name="graphicfile" type="hidden" id="graphicfile" value="<?php echo $row_badges['graphic']; ?>" />
      <input type="submit" name="button2" id="button2" value="Delete" /></td>
    </form>
	</tr>
<?php
	//now get the next one
	$row_badges = mysqli_fetch_assoc($badges);
} while ($row_badges = mysqli_fetch_assoc($badges));
?>
<form action="insert_new_badge.php" method="POST" enctype="multipart/form-data" name="insert" id="insert">
<tr>
<td><label for="identifier"></label>
  <input type="text" name="identifier" id="identifier" /></td>
<td><label for="description"></label>
  <textarea name="description" id="description" cols="45" rows="5"></textarea></td>
  <td><label for="fileToUpload"></label>
    <input type="file" name="fileToUpload" id="fileToUpload" accept="img/jpg" /></td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td><input type="submit" name="button" id="button" value="Add new" /></td>
<td>&nbsp;</td>
</tr>
</form>
</table>

<p>[ <a href="editor_start.php">Editor home</a> | <a href="<?php echo $logoutAction ?>">Logout</a> ]</p>
</body>
</html>
<?php
mysqli_free_result($badges);
?>
