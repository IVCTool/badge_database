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
}//end function
}//end if

?>
<?php
$insertSQL = sprintf("INSERT INTO executabletcs (description, classname, version, abstracttcs_id) VALUES (%s, %s, %s, %f)",
                     GetSQLValueString($_POST['description'], "text"),
					 GetSQLValueString($_POST['classname'], "text"),
                     GetSQLValueString($_POST['version'], "text"),
					 GetSQLValueString($_POST['aid'], "int")
					 );

  mysql_select_db($database_badgesdbcon, $badgesdbcon);
  $Result1 = mysql_query($insertSQL, $badgesdbcon) or die(mysql_error());

//this is the redirect
header(sprintf("Location: %s", "new_executabletcs.php"));

?>