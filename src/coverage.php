<?php require_once('Connections/badgesdbcon.php'); ?>
<?php require_once('include/getsqlvaluestring.php'); ?>
<?php

//this gets the requirements with no Abstract Test Case
$query_requirements = "select * from requirements where requirements.id NOT IN (select requirements_id FROM  abstracttcs_has_requirements)";
$requirements = mysqli_query($badgesdbcon, $query_requirements) or die(mysqli_error());
$row_requirements = mysqli_fetch_assoc($requirements);
$totalRows_requirements = mysqli_num_rows($requirements);

//This gets the abstract test cases that do not have any executable test case related to them.
$query_noexecutable = "SELECT * FROM abstracttcs WHERE id NOT IN (SELECT abstracttcs_id FROM executabletcs)";
$noexecutable = mysqli_query($badgesdbcon, $query_noexecutable) or die(mysqli_error());
$row_noexecutable = mysqli_fetch_assoc($noexecutable);
$totalRows_noexecutable = mysqli_num_rows($noexecutable);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Coverage Report</title>
<link href="css/admin.css" rel="stylesheet" type="text/css" />
</head>

<body>
<h1>Coverage Report</h1>
<p>These reports show how many interoperability requirements have not yet been addressed. The first stage is the creation of abstract test cases; once an abstract test case is ready it can be implemented in code and becomes an executable test case.</p>
	
<p>The following <?php echo $totalRows_noexecutable; ?> abstract test cases do no have an executable test case.</p>
	
<table width="200" border="1">
  <tbody>
    <tr>
      <th scope="col">Identifier</th>
	  <th scope="col">Name</th>
      <th scope="col">Description</th>
    </tr>
	<?php do { ?>
    <tr>
      <td><?php echo $row_noexecutable['identifier']; ?></td>
      <td><?php echo $row_noexecutable['name']; ?></td>
	  <td><?php echo $row_noexecutable['description']; ?></td>
    </tr>
	<?php } while ($row_noexecutable = mysqli_fetch_assoc($noexecutable)); ?>
  </tbody>
</table>
<p>&nbsp;</p>
<p>The following <?php echo $totalRows_requirements; ?> requirements do not have an associated abstract test case.</p>
<table width="200" border="1">
  <tr>
    <th scope="col">Identifier</th>
    <th scope="col">Description</th>
    <th scope="col">&nbsp;</th>
  </tr>
      <?php do { ?>
  <tr>
      <td><?php echo $row_requirements['identifier']; ?></td>
      <td><?php echo $row_requirements['description']; ?></td>
      <td><form id="form1" name="form1" method="post" action="requirements_update.php">
        <input name="id" type="hidden" id="id" value="<?php echo $row_requirements['id']; ?>" />
        <input type="submit" name="Edit" id="Edit" value="Edit" />
      </form></td>
  </tr>
        <?php } while ($row_requirements = mysqli_fetch_assoc($requirements)); ?>
</table>
<p>&nbsp;</p>
<p>[ <a href="editor_start.php">Editor Home</a> ]</p>
</body>
</html>
<?php
mysqli_free_result($requirements);
?>
