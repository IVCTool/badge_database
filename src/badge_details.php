<?php require_once('Connections/badgesdbcon.php'); ?>
<?php $target_dir = "badge_graphics/"; ?>
<?php require_once('include/getsqlvaluestring.php'); ?>
<?php

if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($badgesdbcon, $theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  $theValue = function_exists("mysqli_real_escape_string") ? mysqli_real_escape_string($theValue) : mysqli_escape_string($theValue);

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

$colname_badge = "-1";
if (isset($_POST['id'])) {
  $colname_badge = $_POST['id'];
}
 
$query_badge = sprintf("SELECT * FROM badges WHERE id = %s", GetSQLValueString($badgesdbcon, $colname_badge, "int"));
$badge = mysqli_query($badgesdbcon, $query_badge) or die(mysqli_error());
$row_badge = mysqli_fetch_assoc($badge);
$totalRows_badge = mysqli_num_rows($badge);

$theid_badge_dependancies = "-1";
if (isset($_POST['id'])) {
  $theid_badge_dependancies = $_POST['id'];
}
 
$query_badge_dependancies = sprintf("SELECT * FROM badges WHERE badges.id IN  (select badges_id_dependency from badges inner join badges_has_badges ON badges.id=badges_has_badges.badges_id WHERE badges.id=%s)", GetSQLValueString($badgesdbcon, $theid_badge_dependancies, "int"));
$badge_dependancies = mysqli_query($badgesdbcon, $query_badge_dependancies) or die(mysqli_error());
$row_badge_dependancies = mysqli_fetch_assoc($badge_dependancies);
$totalRows_badge_dependancies = mysqli_num_rows($badge_dependancies);

$theid_requirements = "-1";
if (isset($_POST['id'])) {
  $theid_requirements = $_POST['id'];
}
 
$query_requirements = sprintf("SELECT requirements.id, requirements.identifier, requirements.description,  CONCAT(reqcategories.identifier, ' - ' , reqcategories.description)  AS reqidentifier FROM requirements INNER JOIN reqcategories  ON requirements.reqcategories_id=reqcategories.id WHERE requirements.id IN (SELECT requirements_id FROM badges_has_requirements WHERE badges_id=%s)", GetSQLValueString($badgesdbcon, $theid_requirements, "int"));
$requirements = mysqli_query($badgesdbcon, $query_requirements) or die(mysqli_error());
$row_requirements = mysqli_fetch_assoc($requirements);
$totalRows_requirements = mysqli_num_rows($requirements);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Badge Details</title>
<link href="css/public.css" rel="stylesheet" type="text/css" />
</head>

<body>
<h1><?php echo $row_badge['identifier']; ?> Badge Details</h1>
<p><strong>Identifier:</strong> <?php echo $row_badge['identifier']; ?><br />
<strong>Description:</strong> <?php echo $row_badge['description']; ?></p>
<p>This badge has <?php echo $totalRows_requirements ?> direct requirement<?php if ($totalRows_requirements != 1) { echo "s"; }?>. and <?php echo $totalRows_badge_dependancies ?> prerequisite badge<?php if ($totalRows_badge_dependancies != 1) { echo "s"; }?>.</p>
<p>  <img src="<?php echo $target_dir . $row_badge['graphicfile'] ?>" width="100" height="137"/></p>
<p>
  <?php if ($totalRows_badge_dependancies > 0) { ?>
</p>
<h2>Prerequisit badges </h2>
<table border="1">
  <tr>
    <th scope="col">Graphic</th>
    <th scope="col">Identifier</th>
    <th scope="col">Description</th>
  </tr>
  <?php do { ?>
  <tr>
    <td><img src="<?php echo $target_dir . $row_badge_dependancies['graphicfile'] ?>" width="100" height="137"/></td>
    <td><?php echo $row_badge_dependancies['identifier']; ?></td>
    <td><?php echo $row_badge_dependancies['description']; ?></td>
    <td><form action="badge_details.php" method="post" enctype="multipart/form-data" name="form2" id="form2">
      <input name="id" type="hidden" id="id" value="<?php echo $row_badge_dependancies['id']; ?>" />
      <input type="submit" name="Submit" id="Submit" value="Details" />
    </form></td>
  </tr>
  <?php } while ($row_badge_dependancies = mysqli_fetch_assoc($badge_dependancies)); ?>
</table>
<?php }//end if there are badge dependancies ?>
<p></p>
<h2>Interoperability requirements for this badge</h2>
<table border="1">
  <tr>
    <th scope="col">Identifier</th>
    <th scope="col">Description</th>
  </tr>
  <?php do { ?>
    <tr>
      <td><?php echo $row_requirements['identifier']; ?></td>
      <td><?php echo $row_requirements['description']; ?></td>
      <td><form action="requirement_details.php" method="post" enctype="application/x-www-form-urlencoded" name="form1" id="form1">
      <input name="id" type="hidden" value="<?php echo $row_requirements['id'] ?>" /><input name="" type="Submit" value="Details" /></form></td>
    </tr>
    <?php } while ($row_requirements = mysqli_fetch_assoc($requirements)); ?>
</table>
<p>&nbsp;</p>
<p>[ <a href="index.php">Home/Search</a> | <a href="browse.html">Browse</a> | <a href="badge_list.php">Badges</a> ]</p>
</body>
</html>
<?php
mysqli_free_result($requirements);

mysqli_free_result($badge);

mysqli_free_result($badge_dependancies);

mysqli_free_result($requirements);
?>
