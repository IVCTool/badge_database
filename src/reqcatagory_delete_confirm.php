<?php require_once('Connections/badgesdbcon.php'); ?>
<?php require_once('include/getsqlvaluestring.php'); ?>
<?php


$colname_cat = "-1";
if (isset($_POST['id'])) {
  $colname_cat = $_POST['id'];
}
 
$query_cat = sprintf("SELECT * FROM reqcategories WHERE id = %s", GetSQLValueString($badgesdbcon, $colname_cat, "int"));

$cat = mysqli_query($badgesdbcon, $query_cat);
$row_cat = mysqli_fetch_assoc($cat);
$totalRows_cat = mysqli_num_rows($cat);

$id_number = "-1";
if (isset($_POST['id'])) {
  $id_number = $_POST['id'];
}
 
$query_number = sprintf("SELECT COUNT(*) as total FROM requirements WHERE reqcategories_id=%s", GetSQLValueString($badgesdbcon,  $id_number, "int"));
$number = mysqli_query($badgesdbcon, $query_number) or die(mysqli_error());
$row_number = mysqli_fetch_assoc($number);
$totalRows_number = mysqli_num_rows($number);
$ndep = $row_number['total'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Confirm delete requirement category</title>
<link href="css/admin.css" rel="stylesheet" type="text/css" />
</head>

<body>
<h1>Confirm requirement category deletion</h1>
<p>Are you sure you want to delete this requirement catagory?</p>
<p><?php echo $row_cat['identifier'] ?> - <?php echo $row_cat['name'] ?>: <?php echo $row_cat['description'] ?>.</p>
<p>It is used by <strong><?php echo $ndep ?></strong> requirements.</p>

<table cellspacing="20">
<tr>
<td>
<form action="reqcatagories_delete.php" method="post" enctype="multipart/form-data" name="delete">
<input type="hidden" name="id" value="<?php echo $_POST['id'] ?>" />
<input value="Delete" type="submit" /></form>
</td>
<td>
<form action="new_requirement_catagory.php" method="post" enctype="multipart/form-data" name="delete"><input value="Cancel" type="submit" /></form>
</td>
</tr>
</table>
</body>
</html>
<?php
mysqli_free_result($cat);

mysqli_free_result($number);
?>
