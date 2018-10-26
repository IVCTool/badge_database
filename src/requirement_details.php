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

$theid_requirement = "-1";
if (isset($_POST['id'])) {
  $theid_requirement = $_POST['id'];
}
mysql_select_db($database_badgesdbcon, $badgesdbcon);
$query_requirement = sprintf("SELECT requirements.*, reqcategories.name FROM (requirements INNER JOIN reqcategories on requirements.reqcategories_id = reqcategories.id) WHERE requirements.id=%s", GetSQLValueString($theid_requirement, "int"));
$requirement = mysql_query($query_requirement, $badgesdbcon) or die(mysql_error());
$row_requirement = mysql_fetch_assoc($requirement);
$totalRows_requirement = mysql_num_rows($requirement);

$theid_badges = "-1";
if (isset($_POST['id'])) {
  $theid_badges = $_POST['id'];
}
mysql_select_db($database_badgesdbcon, $badgesdbcon);
$query_badges = sprintf("SELECT id,identifier,description,graphicfile FROM (badges INNER JOIN badges_has_requirements ON badges.id=badges_has_requirements.badges_id) WHERE badges_has_requirements.requirements_id=%s", GetSQLValueString($theid_badges, "int"));
$badges = mysql_query($query_badges, $badgesdbcon) or die(mysql_error());
$row_badges = mysql_fetch_assoc($badges);
$totalRows_badges = mysql_num_rows($badges);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Interoperability Requirement Details</title>
<link href="css/public.css" rel="stylesheet" type="text/css" />
</head>

<body>
<h1>Requirement <?php echo $row_requirement['identifier']; ?> details</h1>
<p><strong>Identifier:</strong> <?php echo $row_requirement['identifier']; ?><br />
  <strong>Category:</strong> <?php echo $row_requirement['name']; ?><br />
  <strong>Description:</strong> <?php echo $row_requirement['description']; ?></p>
<h2>A total of <?php echo $totalRows_badges ?> badges use this requirement directly</h2>
<p>This requirement may also apply to badges that have one or more badge prerequisites.</p>
<?php if ($totalRows_badges > 0) { ?>
  <table border="1">
    <tr>
      <th>Identifier</th>
      <th>Description</th>
      <th>Graphic</th>
    </tr>
    <?php do { ?>
      <tr>
        <td><?php echo $row_badges['identifier']; ?></td>
        <td><?php echo $row_badges['description']; ?></td>
        <td><?php echo '<img width="100" height="137" src="badge_graphics/' . $row_badges["graphicfile"] . '" />' ?></td>
        <td><form action="badge_details.php" method="post" enctype="application/x-www-form-urlencoded" name="form1" id="form1">
          <input name="id" type="hidden" id="id" value="<?php echo $row_badges['id']; ?>" />
          <input type="submit" name="button" id="button" value="Details" />
        </form></td>
      </tr>
      <?php } while ($row_badges = mysql_fetch_assoc($badges)); ?>
</table>
<?php } //end if there are rows to show ?>
<br />

<p>[ <a href="index.html">Home / Search</a> | <a href="browse.html">Browse</a> | <a href="requirements_list.php">Requirements</a> ]</p>
</body>
</html>
<?php
mysql_free_result($requirement);

mysql_free_result($badges);
?>
