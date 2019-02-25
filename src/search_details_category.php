<?php require_once('Connections/badgesdbcon.php'); ?>
<?php require_once('include/getsqlvaluestring.php'); ?>
<?php

$colname_requirements = "-1";
if (isset($_POST['id'])) {
  $colname_requirements = $_POST['id'];
}
 
$query_requirements = sprintf("SELECT * FROM requirements WHERE reqcategories_id = %s", GetSQLValueString($badgesdbcon, $colname_requirements, "int"));
$requirements = mysqli_query($badgesdbcon, $query_requirements) or die(mysqli_error());
$row_requirements = mysqli_fetch_assoc($requirements);
$totalRows_requirements = mysqli_num_rows($requirements);

$colname_category = "-1";
if (isset($_POST['id'])) {
  $colname_category = $_POST['id'];
}
 
$query_category = sprintf("SELECT * FROM reqcategories WHERE id = %s", GetSQLValueString($badgesdbcon, $colname_category, "int"));
$category = mysqli_query($badgesdbcon, $query_category) or die(mysqli_error());
$row_category = mysqli_fetch_assoc($category);
$totalRows_category = mysqli_num_rows($category);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Search results - requirement category</title>
<link href="css/public.css" rel="stylesheet" type="text/css" />
</head>

<body>
<h1>Search Details - Requirement Category</h1>
<p><strong>id:</strong> <?php echo $row_category['id'] ?><br />
  <strong>Identifier:</strong> <?php echo $row_category['identifier'] ?><br />
  <strong>Name:</strong> <?php echo $row_category['name'] ?><br />
  <strong>Description:</strong> <?php echo $row_category['description'] ?></p>
<h2>Requirements in this category (<?php echo $totalRows_requirements ?>)</h2>
<?php if ($totalRows_requirements > 0) { ?>
<table width="100%" border="1" cellpadding="1">
  <tr>
    <th scope="col">Identifier</th>
    <th scope="col">Name</th>
    <th scope="col">Description</th>
    <th scope="col">&nbsp;</th>
  </tr>
<?php do { ?>
  <tr>
    <td><?php echo $row_requirements['identifier'] ?></td>
    <td><?php echo $row_requirements['name'] ?></td>
    <td><?php echo $row_requirements['description'] ?></td>
    <td><form action="search_details_requirement.php" method="post" enctype="multipart/form-data" name="form1" id="form1">
      <input name="id" type="hidden" id="id" value="<?php echo $row_requirements['id']; ?>" />
      <input type="submit" name="button" id="button" value="Details" />
    </form></td>
  </tr>
<?php } while ($row_requirements = mysqli_fetch_assoc($requirements)); ?>
</table>
<?php } else { ?>
<p><strong>No requirements in this categrory.</strong></p>
<?php } //end if totalrows ?>
<p>&nbsp;</p>
<p>[ <a href="index.php">Home</a> ]
</body>
</html>
<?php
mysqli_free_result($requirements);

mysqli_free_result($category);
?>
