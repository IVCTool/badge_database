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

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "updateform")) {
  $updateSQL = sprintf("UPDATE reqcategories SET name=%s, `description`=%s, identifier=%s WHERE id=%s",
                       GetSQLValueString($badgesdbcon,  $_POST['name'], "text"),
                       GetSQLValueString($badgesdbcon,  $_POST['description'], "text"),
                       GetSQLValueString($badgesdbcon,  $_POST['identifier'], "text"),
                       GetSQLValueString($badgesdbcon,  $_POST['id'], "int"));

   
  $Result1 = mysqli_query($badgesdbcon, $updateSQL);

  $updateGoTo = "new_requirement_catagory.php";
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
 
$query_record = sprintf("SELECT * FROM reqcategories WHERE id = %s", GetSQLValueString($badgesdbcon,  $colname_record, "int"));

$record = mysqli_query($badgesdbcon, $query_record);
$row_record = mysqli_fetch_assoc($record);
$totalRows_record = mysqli_num_rows($record);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Update requirement catagory record</title>
<link href="css/admin.css" rel="stylesheet" type="text/css" />
</head>

<body>
<h1>Update requirement catagory record</h1>
<form action="<?php echo $editFormAction; ?>" method="POST" name="updateform"><input name="id" type="hidden" value="<?php echo $_POST['id'] ?>" />
  <p>Identifier:
    <input type="text" name="identifier" id="identifier" value="<?php echo $row_record['identifier'] ?>"/>
    <br />
    Name: <input name="name" type="text" value="<?php echo $row_record['name'] ?>" maxlength="255" />
    <br />
    Description:<br/>
    <textarea name="description" cols="80" rows="5"><?php echo $row_record['description'] ?></textarea>
  </p>
  <p>
    <input type="submit" name="submit" id="submit" value="Submit" />
  </p>
  <input type="hidden" name="MM_update" value="updateform" />
</form>
<P>[ <a href="editor_start.php">Editor home</a> | <a href="<?php echo $logoutAction ?>">Log out</a> ]</P>

</body>
</html>
<?php
mysqli_free_result($record);
?>
