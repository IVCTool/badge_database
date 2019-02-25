<?php require_once('Connections/badgesdbcon.php'); ?>
<?php $target_dir = "badge_graphics/"; ?>
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
<?php require_once('include/getsqlvaluestring.php'); ?>
<?php

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "addform")) {
  $insertSQL = sprintf("INSERT INTO badges_has_requirements (requirements_id, badges_id) VALUES (%s, %s)",
                       GetSQLValueString($badgesdbcon, $_POST['requirementid'], "int"),
                       GetSQLValueString($badgesdbcon, $_POST['id'], "int"));

   
  $Result1 = mysqli_query($badgesdbcon, $insertSQL) or die(mysqli_error());
}

$colname_badge = "-1";
if (isset($_POST['id'])) {
  $colname_badge = $_POST['id'];
}
 
$query_badge = sprintf("SELECT * FROM badges WHERE id = %s", GetSQLValueString($badgesdbcon, $colname_badge, "int"));
$badge = mysqli_query($badgesdbcon, $query_badge) or die(mysqli_error());
$row_badge = mysqli_fetch_assoc($badge);
$totalRows_badge = mysqli_num_rows($badge);

 
$query_requirements = "SELECT id, CONCAT(identifier, ' ', description) AS iddes FROM requirements ORDER BY identifier";
$requirements = mysqli_query($badgesdbco, $query_requirementsn) or die(mysqli_error());
$row_requirements = mysqli_fetch_assoc($requirements);
$totalRows_requirements = mysqli_num_rows($requirements);

$theid_depreq = "-1";
if (isset($_POST['id'])) {
  $theid_depreq = $_POST['id'];
}
 
$query_depreq = sprintf("SELECT requirements.id, requirements.identifier, requirements.description,  CONCAT(reqcategories.identifier, ' - ' , reqcategories.description)  AS reqidentifier FROM requirements INNER JOIN reqcategories  ON requirements.reqcategories_id=reqcategories.id WHERE requirements.id IN (SELECT requirements_id FROM badges_has_requirements WHERE badges_id=%s) ORDER BY requirements.identifier", GetSQLValueString($badgesdbcon, $theid_depreq, "int"));
$depreq = mysqli_query($badgesdbcon, $query_depreq) or die(mysqli_error());
$row_depreq = mysqli_fetch_assoc($depreq);
$totalRows_depreq = mysqli_num_rows($depreq);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Badge Interoperability Requirements</title>
<link href="css/admin.css" rel="stylesheet" type="text/css" />
</head>

<body>
<h1>Edit Badge Interoperability Requirements</h1>
<p><strong>Identifier:</strong> <?php echo $row_badge['identifier'] ?><br />
  Description:</strong> <?php echo $row_badge['description'] ?>
  <br /> 
  <img src="<?php echo $target_dir . $row_badge['graphicfile'] ?>" width="100" height="137"/>
</p>
<p>Requirements (<?php echo $totalRows_depreq ?>)</p>
<table width="100%" border="1" cellpadding="1">
  <tr>
    <td><strong>Identifier</strong></td>
    <td><strong>Description</strong></td>
    <td>&nbsp;</td>
  </tr>
<?php 
	$i = 0;
	do {
?>
  <tr>
    <td><?php echo $row_depreq['reqidentifier'] ?></td>
    <td><?php echo $row_depreq['description'] ?></td>
    <td><form action="badge_requirement_delete_action.php" method="post" enctype="multipart/form-data" name="formdelete<?php echo ++$i ?>" id="formdelete<?php echo ++$i ?>">
      <input type="hidden" name="badgeid" id="badgeid" value="<?php echo $row_badge['id']; ?>" />
      <input type="hidden" name="reqid" id="reqid" value="<?php echo $row_depreq['id']; ?>" />
      <input type="submit" name="button2" id="button2" value="Delete" />
    </form></td>
  </tr>
<?php 
	} while ($row_depreq = mysqli_fetch_assoc($depreq));
?>
</table>
<p>&nbsp;</p>
<form action="<?php echo $editFormAction; ?>" method="POST" enctype="multipart/form-data" name="addform" id="addform">
  <input name="id" type="hidden" id="id" value="<?php echo $row_badge['id']; ?>" />
  <label for="requirementid"></label>
  <select name="requirementid" id="requirementid">
    <?php
do {  
?>
    <option value="<?php echo $row_requirements['id']?>"><?php echo $row_requirements['iddes']?></option>
    <?php
} while ($row_requirements = mysqli_fetch_assoc($requirements));
  $rows = mysqli_num_rows($requirements);
  if($rows > 0) {
      mysqli_data_seek($requirements, 0);
	  $row_requirements = mysqli_fetch_assoc($requirements);
  }
?>
  </select>
  <input type="submit" name="button" id="button" value="Add" />
  <input type="hidden" name="MM_insert" value="addform" />
</form>
<p>&nbsp;</p>
<p>[ <a href="editor_start.php">Editor home</a> |<a href="new_badge.php"> Badges</a> | <a href="<?php echo $logoutAction ?>">Log out</a> ]</p>
</body>
</html>
<?php
mysqli_free_result($badge);

mysqli_free_result($requirements);

mysqli_free_result($depreq);
?>
