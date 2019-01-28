<?php require_once('Connections/badgesdbcon.php'); ?>
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

$colname_etc = "-1";
if (isset($_POST['id'])) {
  $colname_etc = $_POST['id'];
}
mysql_select_db($database_badgesdbcon, $badgesdbcon);
$query_etc = sprintf("SELECT * FROM executabletcs WHERE id = %s", GetSQLValueString($colname_etc, "int"));
$etc = mysql_query($query_etc, $badgesdbcon) or die(mysql_error());
$row_etc = mysql_fetch_assoc($etc);
$totalRows_etc = mysql_num_rows($etc);$colname_etc = "-1";
if (isset($_POST['id'])) {
  $colname_etc = $_POST['id'];
}
mysql_select_db($database_badgesdbcon, $badgesdbcon);
$query_etc = sprintf("SELECT * FROM executabletcs WHERE id = %s", GetSQLValueString($colname_etc, "int"));
$etc = mysql_query($query_etc, $badgesdbcon) or die(mysql_error());
$row_etc = mysql_fetch_assoc($etc);
$totalRows_etc = mysql_num_rows($etc);

mysql_select_db($database_badgesdbcon, $badgesdbcon);
$query_atcs = "SELECT * FROM abstracttcs";
$atcs = mysql_query($query_atcs, $badgesdbcon) or die(mysql_error());
$row_atcs = mysql_fetch_assoc($atcs);
$totalRows_atcs = mysql_num_rows($atcs);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Edit Executable Test Case</title>
<link href="css/admin.css" rel="stylesheet" type="text/css" />
</head>

<body>
<h1>Edit Executable Test Case</h1>
<form id="form1" name="form1" method="post" action="etc_edit_proc.php">
<p>Description: <br />
  <label for="description"></label>
  <textarea name="description" id="description" cols="45" rows="5"><?php echo $row_etc['Description']; ?></textarea>
  </p>
<p>Classname: 
  <label for="classname"></label>
  <input name="classname" type="text" id="classname" value="<?php echo $row_etc['classname']; ?>" />
</p>
<p>Version: 
  <label for="version"></label>
  <input name="version" type="text" id="version" value="<?php echo $row_etc['version']; ?>" />
</p>
<p>Abstract Test Case: 
  <label for="atc"></label>
  <select name="atc" id="atc">
    <?php
do {  
?>
    <option value="<?php echo $row_atcs['id']?>"<?php if (!(strcmp($row_atcs['id'], $row_etc['abstracttcs_id']))) {echo "selected=\"selected\"";} ?>><?php echo $row_atcs['identifier']?></option>
    <?php
} while ($row_atcs = mysql_fetch_assoc($atcs));
  $rows = mysql_num_rows($atcs);
  if($rows > 0) {
      mysql_data_seek($atcs, 0);
	  $row_atcs = mysql_fetch_assoc($atcs);
  }
?>
  </select>
</p>
</form>
<p>[ <a href="editor_start.php">Editor Home</a> ]</p>
</body>
</html>
<?php
mysql_free_result($etc);

mysql_free_result($atcs);
?>
