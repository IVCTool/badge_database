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

$colname_req = "-1";
if (isset($_POST['id'])) {
  $colname_req = $_POST['id'];
}
mysql_select_db($database_badgesdbcon, $badgesdbcon);
$query_req = sprintf("SELECT * FROM requirements WHERE id = %s", GetSQLValueString($colname_req, "int"));
$req = mysql_query($query_req, $badgesdbcon) or die(mysql_error());
$row_req = mysql_fetch_assoc($req);
$totalRows_req = mysql_num_rows($req);

$id_number = "-1";
if (isset($_POST['id'])) {
  $id_number = $_POST['id'];
}
mysql_select_db($database_badgesdbcon, $badgesdbcon);
$query_number = sprintf("SELECT COUNT(*) as total FROM badges_has_requirements WHERE requirements_id=%s", GetSQLValueString($id_number, "int"));
$number = mysql_query($query_number, $badgesdbcon) or die(mysql_error());
$row_number = mysql_fetch_assoc($number);
$totalRows_number = mysql_num_rows($number);
$ndep = $row_number['total'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Confirm delete requirement</title>
<link href="css/admin.css" rel="stylesheet" type="text/css" />
</head>

<body>
<h1>Confirm requirement deletion</h1>
<p>Are you sure you want to delete this requirement?</p>
<p><?php echo $row_req['identifier'] ?>: <?php echo $row_req['description'] ?>.</p>
<p>It is used by <strong><?php echo $ndep ?></strong> badges (the badge's dependency on this requirement will also be deleted).</p>

<table cellspacing="20">
<tr>
<td>
<form action="requirements_delete.php" method="post" enctype="multipart/form-data" name="delete">
<input type="hidden" name="id" value="<?php echo $_POST['id'] ?>" />
<input value="Delete" type="submit" /></form>
</td>
<td>
<form action="new_requirement.php" method="post" enctype="multipart/form-data" name="delete"><input value="Cancel" type="submit" /></form>
</td>
</tr>
</table>
</body>
</html>
<?php
mysql_free_result($req);

mysql_free_result($number);
?>
