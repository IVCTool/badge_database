<?php require_once('Connections/badgesdbcon.php'); ?>
<?php require_once('include/getsqlvaluestring.php'); ?>
require_once('globals.php');
?>

<?php

$colname_recordset = "-1";
if (isset($_POST['id'])) {
  $colname_recordset = $_POST['id'];
}
 
$query_recordset = sprintf("SELECT * FROM abstracttcs WHERE id = %s", GetSQLValueString($badgesdbcon,  $colname_recordset, "int"));
$recordset = mysqli_query($badgesdbcon, $query_recordset) or die(mysqli_error());
$row_recordset = mysqli_fetch_assoc($recordset);
$totalRows_recordset = mysqli_num_rows($recordset);

//Get rid of the file
$target_file = $atcsURL . $row_recordset["filename"];
echo $target_file;
unlink($target_file);

//Delete the record
$query_delete = sprintf("DELETE FROM abstracttcs WHERE id = %s", GetSQLValueString($badgesdbcon, $colname_recordset, "int"));
echo $query_delete;
$delresult = mysqli_query($badgesdbcon, $query_delete) or die(mysqli_error());

/* Redirect to a different page in the current directory that was requested */
$host  = $_SERVER['HTTP_HOST'];
$uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
$extra = 'new_abstracttestcase.php';
header("Location: http://$host$uri/$extra");
exit;

mysqli_free_result($recordset);
?>
