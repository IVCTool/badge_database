<?php require_once('Connections/badgesdbcon.php'); ?>
<?php require_once('include/getsqlvaluestring.php'); ?>
<?php

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "updateform")) {
  $updateSQL = sprintf("UPDATE requirements SET identifier=%s, `description`=%s, reqcategories_id=%s WHERE id=%s", GetSQLValueString($badgesdbcon, $_POST['identifier'], "text"),
  GetSQLValueString($badgesdbcon, $_POST['description'], "text"),
  GetSQLValueString($badgesdbcon, $_POST['catagory'], "int"),
  GetSQLValueString($badgesdbcon, $_POST['id'], "int"));

   
  $Result1 = mysqli_query($badgesdbcon, $updateSQL) or die(mysqli_error());

  $updateGoTo = "new_requirement.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

$colname_record = "-1";
if (isset($_POST['id'])) {
  $colname_record = $_POST['id'];
}
 
$query_record = sprintf("SELECT * FROM requirements WHERE id = %s", GetSQLValueString($badgesdbcon, $colname_record, "int"));
$record = mysqli_query($badgesdbcon, $query_record) or die(mysqli_error());
$row_record = mysqli_fetch_assoc($record);
$totalRows_record = mysqli_num_rows($record);

 
$query_catagories = "SELECT * FROM reqcategories ORDER BY identifier ASC";
$catagories = mysqli_query($badgesdbcon, $query_catagories) or die(mysqli_error());
$row_catagories = mysqli_fetch_assoc($catagories);
$totalRows_catagories = mysqli_num_rows($catagories);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Update interoperability requirement</title>
<link href="css/admin.css" rel="stylesheet" type="text/css" />
</head>

<body>
<h1>Update interoperability requirement</h1>
<form id="updateform" name="updateform" method="POST" action="<?php echo $editFormAction; ?>">
  <p>
    <input type="hidden" name="id" value="<?php echo $_POST['id'] ?>" />
    <strong>Identifier:</strong>
    <label for="identifier"></label>
  <input name="identifier" type="text" id="identifier" value="<?php echo $row_record['identifier']; ?>" />
  <br />
  <strong>Description: 
  <label for="description"></label>
  <textarea name="description" id="description" cols="45" rows="5"><?php echo $row_record['description']; ?></textarea>
  </strong>
  <br />
  <strong>Category:</strong>
  <label for="catagory"></label>
  <select name="catagory" id="catagory">
    <?php
do {  
?>
    <option value="<?php echo $row_catagories['id']?>"<?php if (!(strcmp($row_catagories['id'], $row_record['reqcategories_id']))) {echo "selected=\"selected\"";} ?>><?php echo $row_catagories['name']?></option>
    <?php
} while ($row_catagories = mysqli_fetch_assoc($catagories));
  $rows = mysqli_num_rows($catagories);
  if($rows > 0) {
      mysqli_data_seek($catagories, 0);
	  $row_catagories = mysqli_fetch_assoc($catagories);
  }
?>
  </select>
  </p>
  <p>
    <input type="submit" name="Submit" id="Submit" value="Submit" />
  </p>
  <input type="hidden" name="MM_update" value="updateform" />
</form>
<p>[ <a href="editor_start.php">Editor home</a> | <a href="<?php echo $logoutAction ?>">Log out</a> ]</p>
</body>
</html>
<?php
mysqli_free_result($record);

mysqli_free_result($catagories);
?>
