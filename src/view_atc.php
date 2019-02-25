<?php require_once('globals.php'); ?>
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
<?php require_once('include/getsqlvaluestring.php'); ?>
<?php

$colname_atc = "-1";
if (isset($_GET['id'])) {
  $colname_atc = $_GET['id'];
}
 
$query_atc = sprintf("SELECT * FROM abstracttcs WHERE id = %s", GetSQLValueString($badgesdbcon, $colname_atc, "int"));
$atc = mysqli_query($badgesdbcon, $query_atc) or die(mysqli_error());
$row_atc = mysqli_fetch_assoc($atc);
$totalRows_atc = mysqli_num_rows($atc);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>View Abstract Test Case</title>
<link href="css/admin.css" rel="stylesheet" type="text/css" />
</head>

<body>
<h1>View Abstract Test Case
</h1>
<p><strong>Identifier:</strong> <?php echo $row_atc['identifier']; ?><br />
  <strong>Name:</strong> <?php echo $row_atc['name']; ?><br />
  <strong>Description:</strong> <?php echo $row_atc['description']; ?><br />
<strong>Test case file:</strong> <a href="<?php echo $urlStub . $atcsURL . $row_atc['filename']; ?>" target="_blank"><?php echo $row_atc['filename']; ?></a></p>
<form id="form1" name="form1" method="post" action="atc_edit.php">
  <input type="submit" name="submit" id="submit" value="Edit" />
  <input type="hidden" name="id" id="id" value="<?php echo $_GET['id']; ?>" />
</form>
<p>[ <a href="editor_start.php">Editor home</a> ]<br />
  <br />
</p>
</body>
</html>
<?php
mysqli_free_result($atc);
?>
