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
	
  $logoutGoTo = "index.html";
  if ($logoutGoTo) {
    header("Location: $logoutGoTo");
    exit;
  }
}
?>
<?php
if (!isset($_SESSION)) {
  session_start();
}
$MM_authorizedUsers = "";
$MM_donotCheckaccess = "true";

// *** Restrict Access To Page: Grant or deny access to this page
function isAuthorized($strUsers, $strGroups, $UserName, $UserGroup) { 
  // For security, start by assuming the visitor is NOT authorized. 
  $isValid = False; 

  // When a visitor has logged into this site, the Session variable MM_Username set equal to their username. 
  // Therefore, we know that a user is NOT logged in if that Session variable is blank. 
  if (!empty($UserName)) { 
    // Besides being logged in, you may restrict access to only certain users based on an ID established when they login. 
    // Parse the strings into arrays. 
    $arrUsers = Explode(",", $strUsers); 
    $arrGroups = Explode(",", $strGroups); 
    if (in_array($UserName, $arrUsers)) { 
      $isValid = true; 
    } 
    // Or, you may restrict access to only certain users based on their username. 
    if (in_array($UserGroup, $arrGroups)) { 
      $isValid = true; 
    } 
    if (($strUsers == "") && true) { 
      $isValid = true; 
    } 
  } 
  return $isValid; 
}

$MM_restrictGoTo = "access_denied.html";
if (!((isset($_SESSION['MM_Username'])) && (isAuthorized("",$MM_authorizedUsers, $_SESSION['MM_Username'], $_SESSION['MM_UserGroup'])))) {   
  $MM_qsChar = "?";
  $MM_referrer = $_SERVER['PHP_SELF'];
  if (strpos($MM_restrictGoTo, "?")) $MM_qsChar = "&";
  if (isset($_SERVER['QUERY_STRING']) && strlen($_SERVER['QUERY_STRING']) > 0) 
  $MM_referrer .= "?" . $_SERVER['QUERY_STRING'];
  $MM_restrictGoTo = $MM_restrictGoTo. $MM_qsChar . "accesscheck=" . urlencode($MM_referrer);
  header("Location: ". $MM_restrictGoTo); 
  exit;
}
?>
<?php
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

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

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "newform")) {
  $insertSQL = sprintf("INSERT INTO reqcategories (name, `description`, identifier) VALUES (%s, %s, %s)",
                       GetSQLValueString($_POST['name'], "text"),
                       GetSQLValueString($_POST['description'], "text"),
                       GetSQLValueString($_POST['identifier'], "text"));

  mysql_select_db($database_badgesdbcon, $badgesdbcon);
  $Result1 = mysql_query($insertSQL, $badgesdbcon) or die(mysql_error());

  $insertGoTo = "new_requirement_catagory.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}

mysql_select_db($database_badgesdbcon, $badgesdbcon);
$query_reqcatrecords = "SELECT * FROM reqcategories";
$reqcatrecords = mysql_query($query_reqcatrecords, $badgesdbcon) or die(mysql_error());
$row_reqcatrecords = mysql_fetch_assoc($reqcatrecords);
$totalRows_reqcatrecords = mysql_num_rows($reqcatrecords);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Requirement Catagories</title>
<link href="css/admin.css" rel="stylesheet" type="text/css" />
</head>

<body>
<h1>Interoperability Requirement Catagories</h1>
<p>&nbsp;</p>
<table width="100%" border="1">
  <tr>
    <td><strong>Identifier</strong></td>
    <td><strong>Name</strong></td>
    <td><strong>Description</strong></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
<?php
//The server behaviour fetches the first one
do {
?>
	
	<tr>
    <form action="reqcatagories_update.php" method="post" id="updateform">
    <td><?php echo $row_reqcatrecords["identifier"] ?></td>
    <td><?php echo $row_reqcatrecords["name"] ?></td>
    <td><?php echo $row_reqcatrecords["description"] ?></td>
    <td><input type="hidden" name="id" id="hiddenField" value="<?php echo $row_reqcatrecords["id"]?>"/>      <input type="submit" name="button2" id="button2" value="Edit" /></td>
    </form>
    <form action="reqcatagory_delete_confirm.php" method="post" enctype="multipart/form-data" id="deleteform">
    <td><input type="hidden" name="id" id="hiddenField" value="<?php echo $row_reqcatrecords["id"]?>"/>
      <input type="submit" name="button2" id="button2" value="Delete" /></td>
    </form>
	</tr>
    
<?php
	//now get the next one
	$row_reqcatrecords = mysqli_fetch_assoc($reqcatrecords);
} while ($row_reqcatrecords = mysql_fetch_assoc($reqcatrecords));
?>
<form method="POST" action="<?php echo $editFormAction; ?>" name="newform">
<tr>
<td><label for="identifier"></label>
  <input type="text" name="identifier" id="identifier" /></td>
<td><label for="name"></label>
  <input type="text" name="name" id="name" /></td>
<td><label for="description"></label>
  <textarea name="description" id="description" cols="45" rows="5"></textarea></td>
<td><input type="submit" name="button" id="button" value="Add new" /></td>
<td>&nbsp;</td>
</tr>
<input type="hidden" name="MM_insert" value="newform" />
</form>
</table>

<p>&nbsp;</p>
[ <a href="editor_start.php">Editor home</a> | <a href="<?php echo $logoutAction ?>">Log out</a> ]
</body>
</html>
<?php
mysql_free_result($reqcatrecords);
?>
