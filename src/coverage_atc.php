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
$query_Recordset1 = "select * from abstracttcs where abstracttcs.id NOT IN (select executabletcs.abstracttcs_id from executabletcs)";
$Recordset1 = mysql_query($query_Recordset1, $badgesdbcon) or die(mysql_error());
$row_Recordset1 = mysql_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysql_num_rows($Recordset1);
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
      <?php } while ($row_Recordset1 = mysql_fetch_assoc($Recordset1)); ?>
  </tr>
</table>
<p>&nbsp;</p>
<p>[<a href="editor_start.php"> Editor Home</a> ]</p>
</body>
</html>
<?php
mysql_free_result($Recordset1);
?>
