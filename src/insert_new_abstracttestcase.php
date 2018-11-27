<?php 
require_once('Connections/badgesdbcon.php'); 
require_once('globals.php');
echo $atcsURL;
?>
<?php
$target_dir = $atcsURL;
$target_file_name = basename($_FILES["fileToUpload"]["name"]);
$target_file = $target_dir . $target_file_name;

//Make sure the file is unique
$i = 0;
while (file_exists($target_file)) {
	$i++;
	$target_file_name = $i . $target_file_name;
	$target_file = $target_dir . $target_file_name;
}

//Now move it into the directo
$uploadOK = move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file);
if (!$uploadOK) {
	echo "Problem uploading file: " . $target_file;
	echo error_getlast();
	die();
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
}//end function
}//end if

?>
<?php
$insertSQL = sprintf("INSERT INTO abstracttcs (description, filename, name, identifier, requirements_id) VALUES (%s, %s, %s, %s, %f)",
                     GetSQLValueString($_POST['description'], "text"),
                     GetSQLValueString($target_file_name, "text"),
					 GetSQLValueString($_POST['name'], "text"),
                     GetSQLValueString($_POST['identifier'], "text"),
					 GetSQLValueString($_POST['requirement'], "int")
					 );

  mysql_select_db($database_badgesdbcon, $badgesdbcon);
  $Result1 = mysql_query($insertSQL, $badgesdbcon) or die(mysql_error());

//this is the redirect
header(sprintf("Location: %s", "new_abstracttestcase.php"));

?>