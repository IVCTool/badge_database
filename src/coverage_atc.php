<?php require_once('Connections/badgesdbcon.php'); ?>
<?php require_once('include/getsqlvaluestring.php'); ?>
<?php

 
$query_Recordset1 = "select * from abstracttcs where abstracttcs.id NOT IN (select executabletcs.abstracttcs_id from executabletcs)";
$Recordset1 = mysqli_query($badgesdbcon, $query_Recordset1) or die(mysqli_error());
$row_Recordset1 = mysqli_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysqli_num_rows($Recordset1);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Abstract Test Case Coverage</title>
<link href="css/admin.css" rel="stylesheet" type="text/css" />
</head>

<body>
<h1>Coverage Report: Abstract test cases</h1>
<p>This report shows all the abstract test cases that DO NOT have an executable test case associated with them.</p>
<table width="200" border="1">
  <tr>
    <th scope="col">File name</th>
    <th scope="col">Identiier</th>
    <th scope="col">Name</th>
    <th scope="col">Description</th>
    <th scope="col">Version</th>
    <th scope="col">&nbsp;</th>
  </tr>
  <tr>
    <?php do { ?>
      <td><?php echo $row_Recordset1['filename']; ?></td>
      <td><?php echo $row_Recordset1['identifier']; ?></td>
      <td><?php echo $row_Recordset1['name']; ?></td>
      <td><?php echo $row_Recordset1['description']; ?></td>
      <td><?php echo $row_Recordset1['version']; ?></td>
      <td><form id="form1" name="form1" method="post" action="atc_edit.php">
        <input name="id" type="hidden" id="id" value="<?php echo $row_Recordset1['id']; ?>" />
      <input name="" type="submit" id="submit" value="Edit" /></form></td>
      <?php } while ($row_Recordset1 = mysqli_fetch_assoc($Recordset1)); ?>
  </tr>
</table>
<p>&nbsp;</p>
<p>[<a href="editor_start.php"> Editor Home</a> ]</p>
</body>
</html>
<?php
mysqli_free_result($Recordset1);
?>
