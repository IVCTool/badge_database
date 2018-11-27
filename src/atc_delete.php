<?php 
require_once('Connections/badgesdbcon.php'); 
require_once('globals.php');
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

$colname_recordset = "-1";
if (isset($_POST['id'])) {
  $colname_recordset = $_POST['id'];
}
mysql_select_db($database_badgesdbcon, $badgesdbcon);
$query_recordset = sprintf("SELECT * FROM abstracttcs WHERE id = %s", GetSQLValueString($colname_recordset, "int"));
$recordset = mysql_query($query_recordset, $badgesdbcon) or die(mysql_error());
$row_recordset = mysql_fetch_assoc($recordset);
$totalRows_recordset = mysql_num_rows($recordset);

//Get rid of the file
$target_file = $atcsURL . $row_recordset["filename"];
echo $target_file;
unlink($target_file);

//Delete the record
$query_delete = sprintf("DELETE FROM abstracttcs WHERE id = %s", GetSQLValueString($colname_recordset, "int"));
echo $query_delete;
$delresult = mysql_query($query_delete, $badgesdbcon) or die(mysql_error());

/* Redirect to a different page in the current directory that was requested */
$host  = $_SERVER['HTTP_HOST'];
$uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
$extra = 'new_abstracttestcase.php';
header("Location: http://$host$uri/$extra");
exit;

mysql_free_result($recordset);
?>
