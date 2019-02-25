<?php require_once('Connections/badgesdbcon.php'); ?>
<?php require_once('include/getsqlvaluestring.php'); ?>
<?php

 
$query_category = "SELECT * FROM reqcategories ORDER BY identifier ASC";
$category = mysqli_query($badgesdbcon, $query_category) or die(mysqli_error());
$row_category = mysqli_fetch_assoc($category);
$totalRows_category = mysqli_num_rows($category);

$colname_requirements = "-1";
if (isset($_POST['id'])) {
  $colname_requirements = $_POST['id'];
}
 
$query_requirements = sprintf("SELECT * FROM requirements WHERE reqcategories_id = %s ORDER BY identifier ASC", GetSQLValueString($badgesdbcon, $colname_requirements, "int"));
$requirements = mysqli_query($badgesdbcon, $query_requirements) or die(mysqli_error());
$row_requirements = mysqli_fetch_assoc($requirements);
$totalRows_requirements = mysqli_num_rows($requirements);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Requirement Category Details</title>
<link href="css/public.css" rel="stylesheet" type="text/css" />
</head>

<body>
<h1><?php echo $row_category['identifier']; ?> Category Details</h1>
<p><strong>Identifier:</strong> <?php echo $row_category['identifier']; ?><br />
<strong>Name:</strong> <?php echo $row_category['name']; ?><br />
<strong>Description:</strong> <?php echo $row_category['description']; ?></p>
<p>There are a total of <?php echo $totalRows_requirements ?> interoperability requirements in this category.</p>
<p>&nbsp;</p>
<table border="1">
  <tr>
    <th>Identifier</th>
    <th>Description</th>
  </tr>
  <?php do { ?>
    <tr>
      <td><?php echo $row_requirements['identifier']; ?></td>
      <td><?php echo $row_requirements['description']; ?></td>
      <td><form action="requirement_details.php" method="post" enctype="application/x-www-form-urlencoded" name="form1" id="form1">
        <input name="id" type="hidden" id="id" value="<?php echo $row_requirements['id']; ?>" />
        <input type="submit" name="button" id="button" value="Details" />
      </form></td>
    </tr>
    <?php } while ($row_requirements = mysqli_fetch_assoc($requirements)); ?>
</table>
<p>[ <a href="index.php">Home / Search</a> | <a href="browse.html">Browse</a> | <a href="category_list.php">Categories</a> ]</p>
</body>
</html>
<?php
mysqli_free_result($category);

mysqli_free_result($requirements);
?>
