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

mysql_select_db($database_badgesdbcon, $badgesdbcon);
$query_atcs = "SELECT * FROM abstracttcs ORDER BY identifier ASC";
$atcs = mysql_query($query_atcs, $badgesdbcon) or die(mysql_error());
$row_atcs = mysql_fetch_assoc($atcs);
$totalRows_atcs = mysql_num_rows($atcs);

mysql_select_db($database_badgesdbcon, $badgesdbcon);
$query_requirements = "SELECT * FROM requirements ORDER BY identifier ASC";
$requirements = mysql_query($query_requirements, $badgesdbcon) or die(mysql_error());
$row_requirements = mysql_fetch_assoc($requirements);
$totalRows_requirements = mysql_num_rows($requirements);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Abstract Test Cases</title>
<link href="css/admin.css" rel="stylesheet" type="text/css" />
</head>

<body>
<h1>Abstract Test Cases</h1>
<p>Each abstract test case addresses a single interoperability requirement. The abstract test case is used by software developers to create executable test cases that can be run by IVCT.</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<table border="1">
  <tr>
    <th>File Name</th>
    <th>Identifier</th>
    <th>Name</th>
    <th>Description</th>
    <th>Requirement</th>
  </tr>
  
  <!-- this is the part for displaying the records -->
  <?php if ($totalRows_atcs > 0) { ?>
  <?php do { ?>

    <tr>
      <td><?php echo $row_atcs['filename']; ?></td>
      <td><?php echo $row_atcs['identifier']; ?></td>
      <td><?php echo $row_atcs['name']; ?></td>
      <td><?php echo $row_atcs['description']; ?></td>
      <td>&nbsp;</td>
      <td><form action="atc_edit.php" method="post" enctype="multipart/form-data" name="delete" id="edit">
        <input name="id" type="hidden" id="id" value="<?php echo $row_atcs['id']; ?>" />
        <input type="submit" name="submit2" id="submit2" value="Edit" />
      </form></td>
      <td><form action="atc_delete_confirm.php" method="post" enctype="multipart/form-data" name="delete" id="delete">
        <input name="id" type="hidden" id="id" value="<?php echo $row_atcs['id']; ?>" />
        <input type="submit" name="submit2" id="submit2" value="Delete" />
      </form></td>
    </tr>
    <?php } while ($row_atcs = mysql_fetch_assoc($atcs)); ?>
    <?php } //end if there are any records to show ?>
    
    <!-- This is the add new part -->    
    <tr>
    	<form action="insert_new_abstracttestcase.php" method="post" enctype="multipart/form-data">
    	<td><input type="file" name="fileToUpload" id="fileToUpload"/></td>
    	<td><label for="identifier"></label>
    	  <input type="text" name="identifier" id="identifier" /></td>
    	<td><label for="name"></label>
    	  <input type="text" name="name" id="name" /></td>
      	<td><label for="description"></label>
      	  <input type="text" name="description" id="description" /></td>
    	<td><label for="requirement"></label>
    	  <select name="requirement" id="requirement">
    	    <?php
do {  
?>
    	    <option value="<?php echo $row_requirements['id']?>"><?php echo $row_requirements['identifier']?></option>
    	    <?php
} while ($row_requirements = mysql_fetch_assoc($requirements));
  $rows = mysql_num_rows($requirements);
  if($rows > 0) {
      mysql_data_seek($requirements, 0);
	  $row_requirements = mysql_fetch_assoc($requirements);
  }
?>
          </select></td>
          <td><input type="submit" name="submit" id="submit" value="Create" /></td>        
        </form>
  </tr>
</table>
<p>[ <a href="editor_start.php">Editor Home</a> | <a href="<?php echo $logoutAction ?>">Log out</a> ]</p>
</body>
</html>
<?php
mysql_free_result($atcs);

mysql_free_result($requirements);
?>
