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
$query_badges = "SELECT * FROM badges";
$badges = mysql_query($query_badges, $badgesdbcon) or die(mysql_error());
$row_badges = mysql_fetch_assoc($badges);
$totalRows_badges = mysql_num_rows($badges);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Badge List</title>
<link href="css/public.css" rel="stylesheet" type="text/css" />
</head>

<body>
<h1>Interoperability Badge List</h1>
<p>There are a total of <?php echo $totalRows_badges; ?> badges in the database.</p>
<p>&nbsp;</p>
<table width="100%" border="1" cellpadding="1">
  <tr>
    <th scope="col">Identifier</th>
    <th scope="col">Description</th>
    <th scope="col">Graphic</th>
    <th scope="col"></th>
  </tr>
 <?php do { ?>
  <tr>
    <td><?php echo $row_badges["identifier"] ?></td>
    <td><?php echo $row_badges["description"] ?></td>
    <td><?php echo '<img width="100" height="137" src="badge_graphics/' . $row_badges["graphicfile"] . '" />' ?></td>
    <td><form action="badge_details.php" method="post" enctype="multipart/form-data" name="form1" id="form1">
      <input type="hidden" name="id" id="id" value="<?php echo $row_badges["id"] ?>"/>
    <input type="submit" name="button" id="button" value="Details" />
    </form></td>
  </tr>
<?php
	//now get the next one
	$row_badges = mysql_fetch_assoc($badges);
	
 } while($row_badges);
?>
</table>

<p>&nbsp;</p>
<p>[ <a href="index.html">Home</a> | <a href="browse.html">Browse</a> ]</p>
</body>
</html>
<?php
mysql_free_result($badges);
?>
