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

$term_requirements = "bananna";
if (isset($_POST['searchterm'])) {
  $term_requirements = $_POST['searchterm'];
}
mysql_select_db($database_badgesdbcon, $badgesdbcon);
$query_requirements = sprintf("SELECT * FROM requirements WHERE (description like %s or identifier like %s)", GetSQLValueString("%" . $term_requirements . "%", "text"),GetSQLValueString("%" . $term_requirements . "%", "text"));
$requirements = mysql_query($query_requirements, $badgesdbcon) or die(mysql_error());
$row_requirements = mysql_fetch_assoc($requirements);
$totalRows_requirements = mysql_num_rows($requirements);

$term_categories = "bananna";
if (isset($_POST['searchterm'])) {
  $term_categories = $_POST['searchterm'];
}
mysql_select_db($database_badgesdbcon, $badgesdbcon);
$query_categories = sprintf("SELECT * FROM reqcategories WHERE (description like %s or name like %s)", GetSQLValueString("%" . $term_categories . "%", "text"),GetSQLValueString("%" . $term_categories . "%", "text"));
$categories = mysql_query($query_categories, $badgesdbcon) or die(mysql_error());
$row_categories = mysql_fetch_assoc($categories);
$totalRows_categories = mysql_num_rows($categories);

$term_badges = "bananna";
if (isset($_POST['searchterm'])) {
  $term_badges = $_POST['searchterm'];
}
mysql_select_db($database_badgesdbcon, $badgesdbcon);
$query_badges = sprintf("SELECT * FROM badges WHERE (description like %s or identifier like %s)", GetSQLValueString("%" . $term_badges . "%", "text"),GetSQLValueString("%" . $term_badges . "%", "text"));
$badges = mysql_query($query_badges, $badgesdbcon) or die(mysql_error());
$row_badges = mysql_fetch_assoc($badges);
$totalRows_badges = mysql_num_rows($badges);


?>
<html>
<head>
<link href="css/public.css" rel="stylesheet" type="text/css" />
<title>Seach Results</title>
</head>
<body>
<h1>Search Results</h1>
<p><strong>Results from badges: </strong>
  <?php //echo $query_badges ?><br>
</p>
<?php
//only show the table if there was anything to find
if ($totalRows_badges > 0) {
?>
<table width="100%" border="1" cellpadding="1">
  <tr>
    <th scope="col">Badge Graphic</th>
    <th scope="col">Identifier</th>
    <th scope="col">Description</th>
    <th scope="col">&nbsp;</th>
  </tr>
<?php 
	do { 
?>
  <tr>
    <td><img src="<?php echo $imgDir . "/" . $row_badges['graphicfile'] ?>" width="100" height="137" /></td>
    <td><?php echo $row_badges['identifier'] ?></td>
    <td><?php echo $row_badges['description'] ?></td>
    <td><form action="search_details_badge.php" method="post" enctype="multipart/form-data" name="form1">
      <input type="hidden" name="id" value="<?php echo $row_badges['id'] ?>">
      <input type="submit" value="Details">
    </form></td>
  </tr>
<?php
	//now get the next one
	} while ($row_badges = mysql_fetch_assoc($badges));
?>
</table>
<?php
} else {
?>
<p>No results from badges.</p>
<?php
}//end if there's any badge results
?>


<p><strong>Results from requirements:</strong>
<?php //echo $query_requirements ?></p>
<?php
//only show the table if there was anything to find
if ($totalRows_requirements > 0) {
?>
<table width="100%" border="1" cellpadding="1">
  <tr>
    <th scope="col">Identifier</th>
    <th scope="col">Description</th>
    <th scope="col">&nbsp;</th>
  </tr>
<?php 
	do { 
?>
  <tr>
    <td><?php echo $row_requirements['identifier'] ?></td>
    <td><?php echo $row_requirements['description'] ?></td>
    <td><form action="search_details_requirement.php" method="post" enctype="multipart/form-data" name="form1">
      <input type="hidden" name="id" value="<?php echo $row_requirements['id'] ?>">
      <input type="submit" value="Details">
    </form></td>
  </tr>
<?php
	//now get the next one
	} while ($row_requirements = mysql_fetch_assoc($requirements));
?>
</table>
<?php
} else {
?>
<p>No results from interoperability requirements.</p>
<?php
}//end if there's any requirements results
?>

<p><strong>Results from requirement categories:</strong>
<?php //echo $query_categories ?></p>
<?php
//only show the table if there was anything to find
if ($totalRows_categories > 0) {
?>
<table width="100%" border="1" cellpadding="1">
  <tr>
    <th scope="col">Identifier</th>
    <th scope="col">Description</th>
    <th scope="col">&nbsp;</th>
  </tr>
<?php 
	do { 
?>
  <tr>
    <td><?php echo $row_categories['identifier'] ?></td>
    <td><?php echo $row_categories['description'] ?></td>
    <td><form action="search_details_category.php" method="post" enctype="multipart/form-data" name="form1">
      <input type="hidden" name="id" value="<?php echo $row_categories['id'] ?>">
      <input type="submit" value="Details">
    </form></td>
  </tr>
<?php
	//now get the next one
	} while ($row_categories = mysql_fetch_assoc($categories));
?>
</table>
<?php
} else {
?>
<p>No results from requirement categories.</p>
<?php
}//end if there's any requirements results
?>

<p>[ <a href="index.html">home</a> ]</p>

</body>
</html>
<?php
mysql_free_result($requirements);

mysql_free_result($categories);

mysql_free_result($badges);
?>