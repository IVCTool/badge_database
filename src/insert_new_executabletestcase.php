<?php 
require_once('Connections/badgesdbcon.php'); 
require_once('globals.php');
?>


<?php
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($badgesdbcon, $theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  $theValue = function_exists("mysqli_real_escape_string") ? mysqli_real_escape_string($theValue) : mysqli_escape_string($theValue);

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
                     GetSQLValueString($badgesdbcon, $_POST['description'], "text"),
					 GetSQLValueString($badgesdbcon, $_POST['classname'], "text"),
                     GetSQLValueString($badgesdbcon, $_POST['version'], "text"),
					 GetSQLValueString($badgesdbcon, $_POST['aid'], "int")
					 );

   
  $Result1 = mysqli_query($badgesdbcon, $insertSQL) or die(mysqli_error());

//this is the redirect
header(sprintf("Location: %s", "new_executabletcs.php"));

?>