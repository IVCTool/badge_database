<?php require_once('Connections/badgesdbcon.php'); ?>
<?php require_once('include/getsqlvaluestring.php'); ?>
<?php

 
$query_requirements = "select * from requirements where requirements.id NOT IN (select abstracttcs.id from abstracttcs)";
$requirements = mysqli_query($badgesdbcon, $query_requirements) or die(mysqli_error());
$row_requirements = mysqli_fetch_assoc($requirements);
$totalRows_requirements = mysqli_num_rows($requirements);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link href="css/admin.css" rel="stylesheet" type="text/css" />
</head>

<body>
<h1>Coverage Report: Requirements</h1>
<p>The following requirements DO NOT have an associated executable test case.</p>
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
