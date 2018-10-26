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

mysql_select_db($database_badgesdbcon, $badgesdbcon);
$query_category = "SELECT * FROM reqcategories ORDER BY identifier ASC";
$category = mysql_query($query_category, $badgesdbcon) or die(mysql_error());
$row_category = mysql_fetch_assoc($category);
$totalRows_category = mysql_num_rows($category);

$colname_requirements = "-1";
if (isset($_POST['id'])) {
  $colname_requirements = $_POST['id'];
}
mysql_select_db($database_badgesdbcon, $badgesdbcon);
$query_requirements = sprintf("SELECT * FROM requirements WHERE reqcategories_id = %s ORDER BY identifier ASC", GetSQLValueString($colname_requirements, "int"));
$requirements = mysql_query($query_requirements, $badgesdbcon) or die(mysql_error());
$row_requirements = mysql_fetch_assoc($requirements);
$totalRows_requirements = mysql_num_rows($requirements);
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
    <?php } while ($row_requirements = mysql_fetch_assoc($requirements)); ?>
</table>
<p>[ <a href="index.html">Home / Search</a> | <a href="browse.html">Browse</a> | <a href="category_list.php">Categories</a> ]</p>
</body>
</html>
<?php
mysql_free_result($category);

mysql_free_result($requirements);
?>
