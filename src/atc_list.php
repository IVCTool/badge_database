<?php require_once('Connections/badgesdbcon.php'); ?>
<?php require_once('globals.php'); ?>
<?php require_once('include/getsqlvaluestring.php'); ?>
<?php

 
$query_cases = "SELECT * FROM abstracttcs";
$cases = mysqli_query($badgesdbcon, $query_cases) or die(mysqli_error());
$row_cases = mysqli_fetch_assoc($cases);
$totalRows_cases = mysqli_num_rows($cases);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Abstract Test Case List</title>
<link href="css/public.css" rel="stylesheet" type="text/css" />
</head>

<body>
<h1>Abstract Test Case List</h1>
<p>Abstract test cases are documents that describe what needs to be tested to ensure a system complies with a particular interoperability requirement. Software developers use these documents to develop executable test cases that are used by the IVCT tool as part of the certification process.</p>
<p>There are a total of <?php echo $totalRows_cases ?> abstract test cases in the database.</p>
<p>&nbsp;</p>
<table border="1">
  <tr>
    <th>Identifier</td>
    <th>Name</td>
    <th>Description</td>
    <th>Document Link</th>
  </tr>
  <?php do { ?>
    <tr>
      <td><?php echo $row_cases['identifier']; ?></td>
      <td><?php echo $row_cases['name']; ?></td>
      <td><?php echo $row_cases['description']; ?></td>
      <td><a href="<?php echo $urlStub . $atcURL . $row_cases['filename']; ?>"><?php echo $row_cases['filename'] ?></a></td>
    </tr>
    <?php } while ($row_cases = mysqli_fetch_assoc($cases)); ?>
</table>
<p>[ <a href="index.php">Home</a> | <a href="browse.html">Browse</a> ]</p>
</body>
</html>
<?php
mysqli_free_result($cases);
?>
