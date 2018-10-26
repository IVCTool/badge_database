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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "updateform")) {
  $updateSQL = sprintf("UPDATE requirements SET identifier=%s, `description`=%s, reqcategories_id=%s WHERE id=%s",
                       GetSQLValueString($_POST['identifier'], "text"),
                       GetSQLValueString($_POST['description'], "text"),
                       GetSQLValueString($_POST['catagory'], "int"),
                       GetSQLValueString($_POST['id'], "int"));

  mysql_select_db($database_badgesdbcon, $badgesdbcon);
  $Result1 = mysql_query($updateSQL, $badgesdbcon) or die(mysql_error());

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
mysql_select_db($database_badgesdbcon, $badgesdbcon);
$query_record = sprintf("SELECT * FROM requirements WHERE id = %s", GetSQLValueString($colname_record, "int"));
$record = mysql_query($query_record, $badgesdbcon) or die(mysql_error());
$row_record = mysql_fetch_assoc($record);
$totalRows_record = mysql_num_rows($record);

mysql_select_db($database_badgesdbcon, $badgesdbcon);
$query_catagories = "SELECT * FROM reqcategories ORDER BY identifier ASC";
$catagories = mysql_query($query_catagories, $badgesdbcon) or die(mysql_error());
$row_catagories = mysql_fetch_assoc($catagories);
$totalRows_catagories = mysql_num_rows($catagories);
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
} while ($row_catagories = mysql_fetch_assoc($catagories));
  $rows = mysql_num_rows($catagories);
  if($rows > 0) {
      mysql_data_seek($catagories, 0);
	  $row_catagories = mysql_fetch_assoc($catagories);
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
mysql_free_result($record);

mysql_free_result($catagories);
?>
