<?php require_once('Connections/badgesdbcon.php'); ?>
<?php 
require_once('Connections/badgesdbcon.php');
require_once('globals.php');
?>
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

 
$query_etcs = "SELECT executabletcs.id AS id, executabletcs.description as description, executabletcs.classname as classname, executabletcs.version as version, abstracttcs.id as aid, abstracttcs.name as aname FROM (executabletcs JOIN abstracttcs ON (executabletcs.abstracttcs_id = abstracttcs.id))";
$etcs = mysqli_query($badgesdbcon, $query_etcs) or die(mysqli_error());
$row_etcs = mysqli_fetch_assoc($etcs);
$totalRows_etcs = mysqli_num_rows($etcs);

 
$query_atcs = "SELECT * FROM abstracttcs";
$atcs = mysqli_query($badgesdbcon, $query_atcs) or die(mysqli_error());
$row_atcs = mysqli_fetch_assoc($atcs);
$totalRows_atcs = mysqli_num_rows($atcs);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Executable Test Cases</title>
<link href="css/admin.css" rel="stylesheet" type="text/css" />
</head>

<body>
<h1>Executable Test Cases</h1>
<p>Each executable test case implements a single abstract test case. The executable test cases are run by IVCT.</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<table border="1">
  <tr>
    <th>Description</th>
    <th>Classname</th>
    <th>version</th>
    <th>Abstract Test Case</th>

  </tr>
  
  <!-- this is the part for displaying the records -->
  <?php if ($totalRows_etcs > 0) { ?>
  <?php do { ?>
  <tr>
    <td><a target="_blank"><?php echo $row_etcs['description']; ?></a></td>
    <td><?php echo $row_etcs['classname']; ?></td>
    <td><?php echo $row_etcs['version']; ?></td>
    <td><a href="view_atc.php?id=<?php echo $row_etcs['aid']; ?>"><?php echo $row_etcs['aname']; ?></a></td>

    <td><form action="etc_edit.php" method="post" enctype="multipart/form-data" name="delete" id="edit">
      <input name="id" type="hidden" id="id" value="<?php echo $row_etcs['id']; ?>"/>
      <input type="submit" name="submit2" id="submit2" value="Edit" />
    </form></td>
    <td><form action="etc_delete_confirm.php" method="post" enctype="multipart/form-data" name="delete" id="delete">
      <input name="id" type="hidden" id="id" value="<?php echo $row_etcs['id']; ?>" />
      <input type="submit" name="submit2" id="submit2" value="Delete" />
    </form></td>
  </tr>
  
<?php } while ($row_etcs = mysqli_fetch_assoc($etcs)); ?>
<?php } //end if there are any records to show ?>
    
    <!-- This is the add new part -->    
    <tr>
    	<form action="insert_new_executabletestcase.php" method="post" enctype="multipart/form-data">
    	<td><label for="description2"></label>
    	  <input type="text" name="description" id="description2" /></td>
    	<td><label for="classname"></label>
    	  <input type="text" name="classname" id="classname" /></td>
    	<td><label for="version"></label>
    	  <input type="text" name="version" id="version" /></td>
      	<td><label for="aid"></label>
      	  <select name="aid" id="aid">
      	    <?php
do {  
?>
      	    <option value="<?php echo $row_atcs['id']?>"><?php echo $row_atcs['identifier']?></option>
      	    <?php
} while ($row_atcs = mysqli_fetch_assoc($atcs));
  $rows = mysqli_num_rows($atcs);
  if($rows > 0) {
      mysqli_data_seek($atcs, 0);
	  $row_atcs = mysqli_fetch_assoc($atcs);
  }
?>
   	      </select>      	  <label for="description"></label></td>
    	<td><label for="requirement"></label></td>
          <td><input type="submit" name="submit" id="submit" value="Create" /></td>        
        </form>
  </tr>
</table>
<p>[ <a href="editor_start.php">Editor Home</a> | <a href="<?php echo $logoutAction ?>">Log out</a> ]</p>
</body>
</html>
<?php
mysqli_free_result($etcs);

mysqli_free_result($atcs);
?>
