<?php require_once('Connections/badgesdbcon.php'); ?>
<?php require_once('include/getsqlvaluestring.php'); ?>
<?php

 
$query_requirements = "SELECT requirements.*, reqcategories.name FROM requirements INNER JOIN reqcategories on requirements.reqcategories_id = reqcategories.id";
$requirements = mysqli_query($badgesdbcon, $query_requirements) or die(mysqli_error());
$row_requirements = mysqli_fetch_assoc($requirements);
$totalRows_requirements = mysqli_num_rows($requirements); 
$query_requirements = "SELECT requirements.*, reqcategories.name FROM requirements INNER JOIN reqcategories on requirements.reqcategories_id = reqcategories.id";
$requirements = mysqli_query($badgesdbcon, $query_requirements) or die(mysqli_error());
$row_requirements = mysqli_fetch_assoc($requirements);
$totalRows_requirements = mysqli_num_rows($requirements);
$query_requirements = "select requirements.*, reqcategories.name FROM requirements INNER JOIN reqcategories on requirements.reqcategories_id = reqcategories.id";
$requirements = mysqli_query($badgesdbcon, $query_requirements) or die(mysqli_error());
$row_requirements = mysqli_fetch_assoc($requirements);
$totalRows_requirements = mysqli_num_rows($requirements);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Interoperability Requirements List</title>
<link href="css/public.css" rel="stylesheet" type="text/css" />
</head>

<body>
<h1>Interoperability Requirements List</h1>
<p>There are a total of <?php echo $totalRows_requirements ?> interoperability requirements in the database.</p>
<table border="1">
  <tr>
    <th>Identifier</th>
    <th>Description</th>
    <th>Category</th>
  </tr>
  <?php do { ?>
    <tr>
      <td><?php echo $row_requirements['identifier']; ?></td>
      <td><?php echo $row_requirements['description']; ?></td>
      <td><?php echo $row_requirements['name']; ?></td>
      <td><form id="form1" name="form1" method="post" action="requirement_details.php">
        <input name="id" type="hidden" id="id" value="<?php echo $row_requirements['id']; ?>" />
        <input type="submit" name="button" id="button" value="Details" />
      </form></td>
    </tr>
    <?php } while ($row_requirements = mysqli_fetch_assoc($requirements)); ?>
</table>
<p>[ <a href="index.php">Home / Search</a> | <a href="browse.html">Browse</a> ]</p>
</body>
</html>
<?php
mysqli_free_result($requirements);
?>
