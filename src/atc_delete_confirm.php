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

$colname_case = "-1";
if (isset($_GET['id'])) {
  $colname_case = $_GET['id'];
}
 
$query_case = sprintf("SELECT * FROM abstracttcs WHERE id = %s", GetSQLValueString($badgesdbcon, $colname_case, "int"));
$case = mysqli_query($badgesdbcon, $query_case) or die(mysqli_error());
$row_case = mysqli_fetch_assoc($case);
$totalRows_case = mysqli_num_rows($case);$colname_case = "-1";
if (isset($_POST['id'])) {
  $colname_case = $_POST['id'];
}
 
$query_case = sprintf("SELECT * FROM abstracttcs WHERE id = %s", GetSQLValueString($badgesdbcon, $colname_case, "int"));
$case = mysqli_query($badgesdbcon, $query_case) or die(mysqli_error());
$row_case = mysqli_fetch_assoc($case);
$totalRows_case = mysqli_num_rows($case);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Confirm Abstract Test Case Deletion</title>
<link href="css/admin.css" rel="stylesheet" type="text/css" />
</head>

<body>
<h1>Confirm abstract test case deletion</h1>
<p>Are you sure you want to delete this abstract test case?</p>
<p>&nbsp;<?php echo $row_case['identifier']; ?>: <?php echo $row_case['name']; ?><br />
  <?php echo $row_case['description']; ?></p>
<p><table cellspacing="20">
<tr>
<td>
<form action="atc_delete.php" method="post" enctype="multipart/form-data" name="delete">
<input type="hidden" name="id" value="<?php echo $_POST['id'] ?>" />
<input value="Delete" type="submit" /></form>
</td>
<td>
<form action="new_abstracttestcase.php" method="post" enctype="multipart/form-data" name="delete"><input value="Cancel" type="submit" /></form>
</td>
</tr>
</table></p>
</body>
</html>
<?php
mysqli_free_result($case);
?>
