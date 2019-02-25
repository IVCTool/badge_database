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
<?php require_once('include/getsqlvaluestring.php'); ?>
<?php

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST['badges_id'])) && ($_POST['badges_id'] != "")) {
  $deleteSQL = sprintf("DELETE FROM badges_has_badges WHERE (badges_id=%s AND badges_id_dependency=%s)",
                       GetSQLValueString($badgesdbcon, $_POST['badges_id'], "int"),
					   GetSQLValueString($badgesdbcon, $_POST['badges_id_dependency'], "int"));

   
  $Result1 = mysqli_query($badgesdbcon, $deleteSQL) or die(mysqli_error());
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "formadd")) {
  $insertSQL = sprintf("INSERT INTO badges_has_badges (badges_id, badges_id_dependency) VALUES (%s, %s)",
                       GetSQLValueString($badgesdbcon, $_POST['badges_id'], "int"),
                       GetSQLValueString($badgesdbcon, $_POST['badges_id_dependency'], "int"));

   
  $Result1 = mysqli_query($badgesdbcon, $insertSQL) or die(mysqli_error());
}

$colname_badge = "-1";
if (isset($_POST['id'])) {
  $colname_badge = $_POST['id'];
}
 
$query_badge = sprintf("SELECT * FROM badges WHERE id = %s", GetSQLValueString($badgesdbcon, $colname_badge, "int"));
$badge = mysqlI_query($badgesdbcon, $query_badge) or die(mysqli_error());
$row_badge = mysqli_fetch_assoc($badge);
$totalRows_badge = mysqli_num_rows($badge);

$colname_otherbadges = "-1";
if (isset($_POST['id'])) {
  $colname_otherbadges = $_POST['id'];
}
 
$query_otherbadges = sprintf("SELECT * FROM badges WHERE id <> %s ORDER BY identifier ASC", GetSQLValueString($badgesdbcon, $colname_otherbadges, "int"));
$otherbadges = mysqli_query($badgesdbcon, $query_otherbadges) or die(mysqli_error());
$row_otherbadges = mysqli_fetch_assoc($otherbadges);
$totalRows_otherbadges = mysqli_num_rows($otherbadges);

$theid_dependencies = "-1";
if (isset($_POST['id'])) {
  $theid_dependencies = $_POST['id'];
}
 
$query_dependencies = sprintf("SELECT * FROM badges WHERE badges.id IN  (select badges_id_dependency from badges inner join badges_has_badges ON badges.id=badges_has_badges.badges_id WHERE badges.id=%s) ORDER BY identifier", GetSQLValueString($badgesdbcon, $theid_dependencies, "int"));
$dependencies = mysqli_query($badgesdbcon, $query_dependencies) or die(mysqli_error());
$row_dependencies = mysqli_fetch_assoc($dependencies);
$totalRows_dependencies = mysqli_num_rows($dependencies);
 $target_dir = "badge_graphics/" ?>
<?php
$colname_badge = "-1";
if (isset($_POST['id'])) {
  $colname_badge = $_POST['id'];
}
 
$query_badge = sprintf("SELECT * FROM badges WHERE id = %s", GetSQLValueString($badgesdbcon, $colname_badge, "int"));
$badge = mysqli_query($badgesdbcon, $query_badge) or die(mysqli_error());
$row_badge = mysqli_fetch_assoc($badge);
$totalRows_badge = mysqli_num_rows($badge);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link href="css/admin.css" rel="stylesheet" type="text/css" />
</head>

<body>
<h1>Edit Badge-to-Badge Dependancies
</h1>
<p><strong>Identifier:</strong> <?php echo $row_badge['identifier'] ?><br />
  Description:</strong> <?php echo $row_badge['description'] ?>
  <br /> 
  <img src="<?php echo $target_dir . $row_badge['graphicfile'] ?>" width="100" height="137"/>
</p>
<p>Dependancies</p>
<table width="100%" border="1" cellpadding="1">
  <tr>
    <td><strong>Badge Identifier</strong></td>
    <td><strong>Badge Desciption</strong></td>
    <td>&nbsp;</td>
  </tr>
  <?php do { ?>
  <tr>
    <td><?php echo $row_dependencies['identifier'] ?></td>
    <td><?php echo $row_dependencies['description'] ?></td>
    <td><form action="" method="post" enctype="multipart/form-data" name="formdelete" id="formdelete">
      <input name="badges_id" type="hidden" id="badges_id" value="<?php echo $row_badge['id']; ?>" />
      <input name="badges_id_dependency" type="hidden" id="badges_id_dependency" value="<?php echo $row_dependencies['id']; ?>" />
      <input name="id" type="hidden" id="id" value="<?php echo $row_badge['id']; ?>" />
      <input type="submit" name="delete" id="delete" value="Delete" />
    </form></td>
  </tr>
<?php } while ($row_dependencies = mysqli_fetch_assoc($dependencies)); ?>
</table>
<p>Add a new dependancy on an existing badge:

<form action="<?php echo $editFormAction; ?>" method="POST" enctype="multipart/form-data" name="formadd" id="formadd"> <input name="badges_id" type="hidden" id="hiddenField" value="<?php echo $row_badge['id']; ?>" /> 
  <label for="badges_id_dependency"></label>
  <select name="badges_id_dependency" id="badges_id_dependency">
    <?php
do {  
?>
    <option value="<?php echo $row_otherbadges['id']?>"><?php echo $row_otherbadges['identifier']?></option>
    <?php
} while ($row_otherbadges = mysqli_fetch_assoc($otherbadges));
  $rows = mysqli_num_rows($otherbadges);
  if($rows > 0) {
      mysqli_data_seek($otherbadges, 0);
	  $row_otherbadges = mysqli_fetch_assoc($otherbadges);
  }
?>
  </select>
  <input type="submit" name="button" id="button" value="Add" />
  <input name="id" type="hidden" id="id" value="<?php echo $row_badge['id']; ?>" />
  <input type="hidden" name="MM_insert" value="formadd" />
</form></p>
<p>[ <a href="editor_start.php">Editor home</a> | <a href="new_badge.php">Badges</a> | <a href="<?php echo $logoutAction ?>">Logout</a> ]</p>
</body>
</html>
<?php
mysqli_free_result($badge);

mysqli_free_result($otherbadges);

mysqli_free_result($dependencies);
?>
