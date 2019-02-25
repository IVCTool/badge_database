<?php require_once('Connections/badgesdbcon.php'); ?>

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
}
}//end if insert function


$deleteSQL = sprintf("DELETE FROM badges_has_requirements WHERE (badges_id=%s AND requirements_id=%s)",
             GetSQLValueString($badgesdbcon, $_POST['badgeid'], "int"),
			 GetSQLValueString($badgesdbcon, $_POST['reqid'], "int"));
			 
 
$result = mysqli_query($badgesdbcon, $deleteSQL) or die(mysqli_error());

header('Location: editor_start.php');

?>
