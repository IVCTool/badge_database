<?php $imgDir = "./badge_graphics"; ?>
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

$colname_badge = "-1";
if (isset($_POST['id'])) {
  $colname_badge = $_POST['id'];
}
mysql_select_db($database_badgesdbcon, $badgesdbcon);
$query_badge = sprintf("SELECT * FROM badges WHERE id = %s", GetSQLValueString($colname_badge, "int"));
$badge = mysql_query($query_badge, $badgesdbcon) or die(mysql_error());
$row_badge = mysql_fetch_assoc($badge);
$totalRows_badge = mysql_num_rows($badge);

$badgeid_requirements = "-1";
if (isset($_POST['id'])) {
  $badgeid_requirements = $_POST['id'];
}
mysql_select_db($database_badgesdbcon, $badgesdbcon);
$query_requirements = sprintf("SELECT requirements.id, requirements.identifier, requirements.description,  CONCAT(reqcategories.identifier, ' - ' , reqcategories.description)  AS reqidentifier FROM requirements INNER JOIN reqcategories  ON requirements.reqcategories_id=reqcategories.id WHERE requirements.id IN (SELECT requirements_id FROM badges_has_requirements WHERE badges_id=%s)", GetSQLValueString($badgeid_requirements, "int"));
$requirements = mysql_query($query_requirements, $badgesdbcon) or die(mysql_error());
$row_requirements = mysql_fetch_assoc($requirements);
$totalRows_requirements = mysql_num_rows($requirements);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Search Results - Badge Details</title>
<link href="css/public.css" rel="stylesheet" type="text/css" />
</head>

<body>
<h1>Search Details - Badge</h1>
<p><strong>id:</strong> <?php echo $row_badge['id'] ?><br />
<strong>Identifier:</strong> <?php echo $row_badge['identifier'] ?><br />
<strong>Description:</strong> <?php echo $row_badge['description'] ?><br />
<strong>Graphic:</strong>  <br /><img src="<?php echo $imgDir . "/" . $row_badge['graphicfile'] ?>" width="100" height="137" alt="" /></p>
<h2>Requirements for this badge (<?php echo $totalRows_requirements ?>)</h2>
<table width="100%" border="1" cellpadding="1">
  <tr>
    <th scope="col">Identifier</th>
    <th scope="col">Description</th>
    <th scope="col">Category</th>
    <th scope="col">&nbsp;</th>
  </tr>
<?php do { ?>
  <tr>
    <td><?php echo $row_requirements['identifier'] ?></td>
    <td><?php echo $row_requirements['description'] ?></td>
    <td><?php echo $row_requirements['reqidentifier'] ?></td>
    <td><form action="search_details_requirement.php" method="post" enctype="multipart/form-data" name="form1" id="form1">
      <input type="hidden" name="id" id="id" value="<?php echo $row_requirements['id'] ?>"/>
      <input type="submit" name="button" id="button" value="Details" />
    </form></td>
  </tr>
<?php } while ($row_requirements = mysql_fetch_assoc($requirements)); ?>
</table>
<p>&nbsp;</p>
<p>[ <a href="index.html">Home</a> ]</p>
</body>
</html>
<?php
mysql_free_result($badge);

mysql_free_result($requirements);
?>
