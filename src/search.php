<?php $imgDir = "./badge_graphics"; ?>
<?php require_once('Connections/badgesdbcon.php'); ?>
<?php require_once('include/getsqlvaluestring.php'); ?>
<?php

$term_requirements = "bananna";
if (isset($_POST['searchterm'])) {
  $term_requirements = $_POST['searchterm'];
}
 
$query_requirements = sprintf("SELECT * FROM requirements WHERE (description like %s or identifier like %s)", GetSQLValueString($badgesdbcon, "%" . $term_requirements . "%", "text"),GetSQLValueString($badgesdbcon, "%" . $term_requirements . "%", "text"));
$requirements = mysqli_query($badgesdbcon, $query_requirements) or die(mysqli_error());
$row_requirements = mysqli_fetch_assoc($requirements);
$totalRows_requirements = mysqli_num_rows($requirements);

$term_categories = "bananna";
if (isset($_POST['searchterm'])) {
  $term_categories = $_POST['searchterm'];
}
 
$query_categories = sprintf("SELECT * FROM reqcategories WHERE (description like %s or name like %s)", GetSQLValueString($badgesdbcon, "%" . $term_categories . "%", "text"),GetSQLValueString($badgesdbcon, "%" . $term_categories . "%", "text"));
$categories = mysqli_query($badgesdbcon, $query_categories) or die(mysqli_error());
$row_categories = mysqli_fetch_assoc($categories);
$totalRows_categories = mysqli_num_rows($categories);

$term_badges = "bananna";
if (isset($_POST['searchterm'])) {
  $term_badges = $_POST['searchterm'];
}
 
$query_badges = sprintf("SELECT * FROM badges WHERE (description like %s or identifier like %s)", GetSQLValueString($badgesdbcon, "%" . $term_badges . "%", "text"),GetSQLValueString($badgesdbcon, "%" . $term_badges . "%", "text"));
$badges = mysqli_query($badgesdbcon, $query_badges) or die(mysqli_error());
$row_badges = mysqli_fetch_assoc($badges);
$totalRows_badges = mysqli_num_rows($badges);


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
	} while ($row_badges = mysqli_fetch_assoc($badges));
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
	} while ($row_requirements = mysqli_fetch_assoc($requirements));
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
	} while ($row_categories = mysqli_fetch_assoc($categories));
?>
</table>
<?php
} else {
?>
<p>No results from requirement categories.</p>
<?php
}//end if there's any requirements results
?>

<p>[ <a href="index.php">home</a> ]</p>

</body>
</html>
<?php
mysqli_free_result($requirements);

mysqli_free_result($categories);

mysqli_free_result($badges);
?>