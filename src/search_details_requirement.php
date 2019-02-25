<?php $imgdir = "./badge_graphics"; ?>
<?php require_once('Connections/badgesdbcon.php'); ?>
<?php require_once('include/getsqlvaluestring.php'); ?>
<?php

$theid_requirement = "-1";
if (isset($_POST['id'])) {
  $theid_requirement = $_POST['id'];
}
 
$query_requirement = sprintf("select requirements.*, reqcategories.name FROM requirements INNER JOIN reqcategories on requirements.reqcategories_id = reqcategories.id WHERE requirements.id = %s", GetSQLValueString($badgesdbcon, $theid_requirement, "int"));
$requirement = mysqli_query($badgesdbcon, $query_requirement) or die(mysqli_error());
$row_requirement = mysqli_fetch_assoc($requirement);
$totalRows_requirement = mysqli_num_rows($requirement);

$reqid_badges = "-1";
if (isset($_POST['id'])) {
  $reqid_badges = $_POST['id'];
}
 
$query_badges = sprintf("select * from badges where id=(select badges_id from badges_has_requirements WHERE requirements_id = %s)", GetSQLValueString($badgesdbcon, $reqid_badges, "int"));
$badges = mysqli_query($badgesdbcon, $query_badges) or die(mysqli_error());
$row_badges = mysqli_fetch_assoc($badges);
$totalRows_badges = mysqli_num_rows($badges);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Search - Interoperability requirement details</title>
<link href="css/public.css" rel="stylesheet" type="text/css" />
</head>

<body>
<h1>Search Details - Interoperability Requirement</h1>
<p><strong>id:</strong> <?php echo $row_requirement['id'] ?><br />
<strong>Identifier:</strong> <?php echo $row_requirement['identifier'] ?><br />
<strong>Catagory:</strong> <?php echo $row_requirement['name'] ?><br />
<strong>Description:</strong> <?php echo $row_requirement['description'] ?><br />
</p>
<h2>Badges with this requirement (<?php echo $totalRows_badges ?>)</h2>
<?php if ($totalRows_badges > 0) { ?>
<table width="100%" border="1" cellpadding="1">
  <tr>
    <th scope="col">Graphic</th>
    <th scope="col">Idenifier</th>
    <th scope="col">Description</th>
    <th scope="col">&nbsp;</th>
  </tr>
  <tr>
    <td><img src="<?php echo $imgdir . "/" . $row_badges['graphicfile'] ?>" width="100" height="137"/></td>
    <td><?php echo $row_badges['identifier'] ?></td>
    <td><?php echo $row_badges['description'] ?></td>
    <td><form action="search_details_badge.php" method="post" enctype="multipart/form-data" name="form1" id="form1">
      <input name="id" type="hidden" value="<?php echo $row_badges['id']; ?>" />
      <input type="submit" name="button" id="button" value="Details" />
    </form></td>
  </tr>
</table>
<?php } //end if there are badges ?>
<p>&nbsp;</p>
<p>[ <a href="index.php">Home</a> ]</p>

</body>
</html>
<?php
mysqli_free_result($requirement);

mysqli_free_result($badges);
?>
