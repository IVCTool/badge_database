<?php require_once('Connections/badgesdbcon.php'); ?>
<?php require_once('include/getsqlvaluestring.php'); ?>
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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "newform")) {
  $insertSQL = sprintf("INSERT INTO requirements (identifier, `description`, reqcategories_id) VALUES (%s, %s, %s)",
                       GetSQLValueString($badgesdbcon, $_POST['identifier'], "text"),
                       GetSQLValueString($badgesdbcon, $_POST['description'], "text"),
                       GetSQLValueString($badgesdbcon, $_POST['catagory'], "int"));

   
  $Result1 = mysqli_query($badgesdbcon, $insertSQL) or die(mysqli_error());

  $insertGoTo = "new_requirement.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}

 
//$query_records = "SELECT * FROM requirements";
$query_records = "select requirements.*, reqcategories.name FROM requirements INNER JOIN reqcategories on requirements.reqcategories_id = reqcategories.id";
$records = mysqli_query($badgesdbcon, $query_records) or die(mysqli_error());
$row_records = mysqli_fetch_assoc($records);
$totalRows_records = mysqli_num_rows($records);

 
$query_catagories = "SELECT * FROM reqcategories ORDER BY identifier ASC";
$catagories = mysqli_query($badgesdbcon, $query_catagories) or die(mysqli_error());
$row_catagories = mysqli_fetch_assoc($catagories);
$totalRows_catagories = mysqli_num_rows($catagories);
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
} while ($row_records = mysqli_fetch_assoc($records));
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
} while ($row_catagories = mysqli_fetch_assoc($catagories));
  $rows = mysqli_num_rows($catagories);
  if($rows > 0) {
      mysqli_data_seek($catagories, 0);
	  $row_catagories = mysqli_fetch_assoc($catagories);
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
mysqli_free_result($records);

mysqli_free_result($catagories);
?>
