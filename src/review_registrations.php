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

$MM_restrictGoTo = "index.html";
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

mysql_select_db($database_badgesdbcon, $badgesdbcon);
$query_unapproved = "SELECT * FROM users WHERE approved = 0";
$unapproved = mysql_query($query_unapproved, $badgesdbcon) or die(mysql_error());
$row_unapproved = mysql_fetch_assoc($unapproved);
$totalRows_unapproved = mysql_num_rows($unapproved);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Review Unapproved Registrations</title>
<link href="css/admin.css" rel="stylesheet" type="text/css" />
</head>

<body>
<h1>Review Unapproved Registrations</h1>
<table width="100%" border="1" cellpadding="1">
  <tr>
    <th scope="col">Username</th>
    <th scope="col">e-mail</th>
    <th scope="col">&nbsp;</th>
    <th scope="col">&nbsp;</th>
  </tr>
 <?php 
  $rnum=0;
  do {
	$rnum++;
?>
  <tr>
    <td><?php echo $row_unapproved["username"]?></td>
    <td><?php echo $row_unapproved["email"] ?></td>
    <td><form action="approve_registration.php" method="POST" enctype="application/x-www-form-urlencoded" name="approve" id="approve">
    <input name="id" value="<?php echo $row_unapproved['id'] ?>" type="hidden" formenctype="multipart/form-data"/>
    <input type="submit" name="Approve" id="approve" value="Approve" />
    </form>
    <form action="delete_registration.php" method="post" enctype="application/x-www-form-urlencoded" name="deny" id="deny">
    <input name="id" value="<?php echo $row_unapproved['id'] ?>" type="hidden" formenctype="multipart/form-data"/>
      <input type="submit" name="Deny" id="deny" value="Deny" />
    </form></td>
    <td>&nbsp;</td>
  </tr>
<?php
  } while( $row_unapproved = mysql_fetch_assoc($unapproved));
?>
</table>
<p>&nbsp;</p>
<p>[ <a href="editor_start.php">Editor Home</a> ]</p>
</body>
</html>
<?php
mysql_free_result($unapproved);
?>
