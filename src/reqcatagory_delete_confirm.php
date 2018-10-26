<?php require_once('Connections/badgesdbcon.php'); ?>
<?php
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

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

$colname_cat = "-1";
if (isset($_POST['id'])) {
  $colname_cat = $_POST['id'];
}
mysql_select_db($database_badgesdbcon, $badgesdbcon);
$query_cat = sprintf("SELECT * FROM reqcategories WHERE id = %s", GetSQLValueString($colname_cat, "int"));
$cat = mysql_query($query_cat, $badgesdbcon) or die(mysql_error());
$row_cat = mysql_fetch_assoc($cat);
$totalRows_cat = mysql_num_rows($cat);

$id_number = "-1";
if (isset($_POST['id'])) {
  $id_number = $_POST['id'];
}
mysql_select_db($database_badgesdbcon, $badgesdbcon);
$query_number = sprintf("SELECT COUNT(*) as total FROM requirements WHERE reqcategories_id=%s", GetSQLValueString($id_number, "int"));
$number = mysql_query($query_number, $badgesdbcon) or die(mysql_error());
$row_number = mysql_fetch_assoc($number);
$totalRows_number = mysql_num_rows($number);
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
mysql_free_result($cat);

mysql_free_result($number);
?>
