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

$colname_abstracttestcase = "-1";
if (isset($_POST['id'])) {
  $colname_abstracttestcase = $_POST['id'];
} elseif (isset($_GET['id'])) {
	$colname_abstracttestcase = $_GET['id'];
}
 
$query_abstracttestcase = sprintf("SELECT * FROM abstracttcs WHERE id = %s", GetSQLValueString($badgesdbcon, $colname_abstracttestcase, "int"));
$abstracttestcase = mysqli_query($badgesdbcon, $query_abstracttestcase) or die(mysqli_error());
$row_abstracttestcase = mysqli_fetch_assoc($abstracttestcase);
$totalRows_abstracttestcase = mysqli_num_rows($abstracttestcase);

//This query gets all the requirements that are already attached to this case.
$query_casereqs = sprintf("SELECT * FROM requirements where id IN (Select requirements_id from abstracttcs_has_requirements where abstracttcs_id=%s)", GetSQLValueString($badgesdbcon, $colname_abstracttestcase, "int"));
$casereqs = mysqli_query($badgesdbcon, $query_casereqs) or die(mysqli_error());
$row_casereqs = mysqli_fetch_assoc($casereqs);
$totalRows_casereqs = mysqli_num_rows($casereqs);
 
//This query gets all the requirements that are not already attached to this abstract test case.
$query_requirements = sprintf("select * from requirements where id NOT IN (Select requirements_id from abstracttcs_has_requirements where abstracttcs_id=%s)", GetSQLValueString($badgesdbcon, $colname_abstracttestcase, "int"));
$requirements = mysqli_query($badgesdbcon, $query_requirements) or die(mysqli_error());
$row_requirements = mysqli_fetch_assoc($requirements);
$totalRows_requirements = mysqli_num_rows($requirements);


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Edit Abstract Test Case</title>
<link href="css/admin.css" rel="stylesheet" type="text/css" />
</head>

<body>
<h1>Edit Abstract Test Case</h1>
<form action="atc_edit_proc.php" method="post" enctype="multipart/form-data" name="form1" id="form1">
  <p>
    <input name="id" type="hidden" id="id" value="<?php echo $row_abstracttestcase['id']; ?>" />
    Identifier: 
    <input name="identifier" type="text" id="textfield" value="<?php echo $row_abstracttestcase['identifier']; ?>" />
  </p>
  <p>Name::
    <label for="name"></label>
    <input name="name" type="text" id="name" value="<?php echo $row_abstracttestcase['name']; ?>" />
  </p>
  <p>Description: 
    <label for="description"></label>
    <input name="description" type="text" id="description" value="<?php echo $row_abstracttestcase['description']; ?>" />
  </p>
  <p>Version: 
    <label for="version"></label>
    <input name="version" type="text" id="version" value="<?php echo $row_abstracttestcase['version']; ?>" />
  </p>

  <p>
    <input name="oldfile" type="hidden" id="oldfile" value="<?php echo $row_abstracttestcase['filename']; ?>" />
    New File: 
    <label for="file"></label>
  <a href="./<?php echo $atcsURL . $row_abstracttestcase['filename']; ?>" target="new">view existing file</a></p>
  <p>
    <input type="submit" name="button" id="button" value="Save Basic Test Case Info" />
  </p>
</form>
	
<table>
	<tr><th>Requirement Identifier</th><th>Description</th></tr>
	<?php if ($totalRows_casereqs > 0) {
		do { ?>
		<form action="atc_delete_requirement.php" method="post" enctype="multipart/form-data" >
		<tr><td><?php echo $row_casereqs['identifier']; ?></td>
			<td><?php echo $row_casereqs['description']; ?></td>
			<td><input type="hidden" name="requirementid" value="<?php echo $row_casereqs['id']; ?>"/>
				<input type="hidden" name="atcsid" value="<?php echo $colname_abstracttestcase; ?>"/>
				<input type="submit" value="Delete Requirement"/></td>
		</tr>
		</form>
	<?php	} while ($row_casereqs = mysqli_fetch_assoc($casereqs));//end do
	}//end if 
	?>
	
	<tr>
	<form action="atc_add_requirement.php" method="post" enctype="multipart/form-data">
	<td> <select name="requirementid" id="requirement">
      	<?php do {  ?>
      		<option value="<?php echo $row_requirements['id']?>"<?php if (!(strcmp($row_requirements['id'], $row_abstracttestcase['requirements_id']))) {echo "selected=\"selected\"";} ?>><?php echo $row_requirements['identifier']?></option>
      	<?php } while ($row_requirements = mysqli_fetch_assoc($requirements));
  			$rows = mysqli_num_rows($requirements);
  			if($rows > 0) {
      			mysqli_data_seek($requirements, 0);
	  			$row_requirements = mysqli_fetch_assoc($requirements);
  			} ?>
		</select>
	</td>
	<td><input type="hidden" name="atcsid" value="<?php echo $colname_abstracttestcase; ?>" /><input type="submit" value="Add Requirement"/></td>
	</form>
	</tr>
	

</table>

<p> [<a href="editor_start.php"> Editor Home </a>| <a href="<?php echo $logoutAction ?>">Log out</a> ] </p>
</body>
</html>
<?php
mysqli_free_result($abstracttestcase);

mysqli_free_result($requirements);
?>
