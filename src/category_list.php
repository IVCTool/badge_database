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
$query_categories = "SELECT * FROM reqcategories";
$categories = mysql_query($query_categories, $badgesdbcon) or die(mysql_error());
$row_categories = mysql_fetch_assoc($categories);
$totalRows_categories = mysql_num_rows($categories);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Interoperability Requirement Categories</title>
<link href="css/public.css" rel="stylesheet" type="text/css" />
</head>

<body>
<h1>Interoperability Requirement Categories</h1>
<p>There are a total of <?php echo $totalRows_categories ?> interoperability requirement categories in the database.</p>

<table border="1">
  <tr>
    <th>Identifier</th>
    <th>Name</th>
    <th>Description</th>
  </tr>
  <?php do { ?>
    <tr>
      <td><?php echo $row_categories['identifier']; ?></td>
      <td><?php echo $row_categories['name']; ?></td>
      <td><?php echo $row_categories['description']; ?></td    
      ><td><form action="category_details.php" method="post" enctype="application/x-www-form-urlencoded" name="form1" id="form1">
        <input name="id" type="hidden" id="id" value="<?php echo $row_categories['id']; ?>" />
        <input type="submit" name="button" id="button" value="Details" />
      </form></td>
    </tr>
    <?php } while ($row_categories = mysql_fetch_assoc($categories)); ?>
</table>
<p>[ <a href="index.html">Home / Search</a> | <a href="browse.html">Browse</a> ]</p>
</body>
</html>
<?php
mysql_free_result($categories);
?>
