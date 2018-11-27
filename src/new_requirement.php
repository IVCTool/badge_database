<?php require_once('Connections/badgesdbcon.php'); ?>
<?php
//initialize the session
if (!isset($_SESSION)) {
  session_start();
}

// ** Logout the current user. **
$logoutAction = $_SERVER['PHP_SELF']."?doLogout=true";
if ((isset($_SERVER['QUERY_STRING'])) && ($_SERVER['QUERY_STRING'] != "")){
  $logoutAction .="&". htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_GET['doLogout'])) &&($_GET['doLogout']=="true")){
  //to fully log out a visitor we need to clear the session varialbles
  $_SESSION['MM_Username'] = NULL;
  $_SESSION['MM_UserGroup'] = NULL;
  $_SESSION['PrevUrl'] = NULL;
  unset($_SESSION['MM_Username']);
  unset($_SESSION['MM_UserGroup']);
  unset($_SESSION['PrevUrl']);
	
  $logoutGoTo = "index.php";
  if ($logoutGoTo) {
    header("Location: $logoutGoTo");
    exit;
  }
}
?>
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

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "newform")) {
  $insertSQL = sprintf("INSERT INTO requirements (identifier, `description`, reqcategories_id) VALUES (%s, %s, %s)",
                       GetSQLValueString($_POST['identifier'], "text"),
                       GetSQLValueString($_POST['description'], "text"),
                       GetSQLValueString($_POST['catagory'], "int"));

  mysql_select_db($database_badgesdbcon, $badgesdbcon);
  $Result1 = mysql_query($insertSQL, $badgesdbcon) or die(mysql_error());

  $insertGoTo = "new_requirement.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}

mysql_select_db($database_badgesdbcon, $badgesdbcon);
//$query_records = "SELECT * FROM requirements";
$query_records = "select requirements.*, reqcategories.name FROM requirements INNER JOIN reqcategories on requirements.reqcategories_id = reqcategories.id";
$records = mysql_query($query_records, $badgesdbcon) or die(mysql_error());
$row_records = mysql_fetch_assoc($records);
$totalRows_records = mysql_num_rows($records);

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
<title>Edit Requirements</title>
<link href="css/admin.css" rel="stylesheet" type="text/css" />
</head>

<body>
<h1>Interoperability Requirements</h1>
<p>&nbsp;</p>
<table width="100%" border="1">
  <tr>
    <td><strong>Identifier</strong></td>
    <td><strong>Description</strong></td>
    <td><strong>Category</strong></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
<?php
//The server behaviour fetches the first one
do {
?>
	
	<tr>
    <form action="requirements_update.php" method="post" id="updateform">
    <td><?php echo $row_records["identifier"] ?></td>
    <td><?php echo $row_records["description"] ?></td>
    <td><?php echo $row_records["name"] ?></td>
    <td><input type="hidden" name="id" id="hiddenField" value="<?php echo $row_records["id"]?>"/>      <input type="submit" name="button2" id="button2" value="Edit" /></td>
    </form>
    <form action="requirement_delete_confirm.php" method="post" enctype="multipart/form-data" id="deleteform">
    <td><input type="hidden" name="id" id="hiddenField" value="<?php echo $row_records["id"]?>"/>
      <input type="submit" name="button2" id="button2" value="Delete" /></td>
    </form>
	</tr>
    
<?php
	//now get the next one
	$row_records = mysqli_fetch_assoc($records);
} while ($row_records = mysql_fetch_assoc($records));
?>
<form method="POST" action="<?php echo $editFormAction; ?>" name="newform">
<tr>
<td><label for="identifier"></label>
  <input type="text" name="identifier" id="identifier" /></td>
<td><label for="description"></label>
  <textarea name="description" id="description" cols="45" rows="5"></textarea></td>
  <td><label for="catagory"></label>
    <select name="catagory" id="catagory">
      <?php
do {  
?>
      <option value="<?php echo $row_catagories['id']?>"><?php echo $row_catagories['name']?></option>
      <?php
} while ($row_catagories = mysql_fetch_assoc($catagories));
  $rows = mysql_num_rows($catagories);
  if($rows > 0) {
      mysql_data_seek($catagories, 0);
	  $row_catagories = mysql_fetch_assoc($catagories);
  }
?>
    </select></td>
<td><input type="submit" name="button" id="button" value="Add new" /></td>
<td>&nbsp;</td>
</tr>
<input type="hidden" name="MM_insert" value="newform" />
</form>
</table>

<p>&nbsp;</p>
[ <a href="editor_start.php">Editor home</a> | <a href="<?php echo $logoutAction ?>">Log out</a> ]
</body>
</html>
<?php
mysql_free_result($records);

mysql_free_result($catagories);
?>
