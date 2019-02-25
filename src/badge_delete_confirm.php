<?php require_once('Connections/badgesdbcon.php'); ?>
<?php require_once('include/getsqlvaluestring.php'); ?>
<?php

$colname_badge = "-1";
if (isset($_POST['id'])) {
  $colname_badge = $_POST['id'];
}
 
$query_badge = sprintf("SELECT * FROM badges WHERE id = %s", GetSQLValueString($badgesdbcon, $colname_badge, "int"));
$badge = mysqli_query($badgesdbcon, $query_badge) or die(mysqli_error());
$row_badge = mysqli_fetch_assoc($badge);
$totalRows_badge = mysqli_num_rows($badge);

$id_number = "-1";
if (isset($_POST['id'])) {
  $id_number = $_POST['id'];
}
 
$query_number = sprintf("SELECT COUNT(*) as total FROM badges_has_badges WHERE badges_id_dependency=%s", GetSQLValueString($badgesdbcon, $id_number, "int"));
$number = mysqli_query($badgesdbcon, $query_number) or die(mysqli_error());
$row_number = mysqli_fetch_assoc($number);
$totalRows_number = mysqli_num_rows($number);
$ndep = $row_number['total'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Confirm delete badge</title>
<link href="css/admin.css" rel="stylesheet" type="text/css" />
</head>

<body>
<h1>Confirm badge deletion</h1>
<p>Are you sure you want to delete this badge?</p>
<p><?php echo $row_badge['identifier'] ?>: <?php echo $row_badge['description'] ?>.</p>
<p>It is a dependancy of  <strong><?php echo $ndep ?></strong> badges (dependancy relationships will also be deleted).</p>

<table cellspacing="20">
<tr>
<td>
<form action="badge_delete.php" method="post" enctype="multipart/form-data" name="delete">
<input type="hidden" name="id" value="<?php echo $_POST['id'] ?>" />
<input value="Delete" type="submit" /></form>
</td>
<td>
<form action="new_badge.php" method="post" enctype="multipart/form-data" name="delete"><input value="Cancel" type="submit" /></form>
</td>
</tr>
</table>
</body>
</html>
<?php
mysqli_free_result($badge);

mysqli_free_result($number);
?>
