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
$insertSQL = sprintf("INSERT INTO abstracttcs (description, filename, name, identifier, requirements_id) VALUES (%s, %s, %s, %s, %f)",
                     GetSQLValueString($badgesdbcon, $_POST['description'], "text"),
                     GetSQLValueString($badgesdbcon, $target_file_name, "text"),
					 GetSQLValueString($badgesdbcon, $_POST['name'], "text"),
                     GetSQLValueString($badgesdbcon, $_POST['identifier'], "text"),
					 GetSQLValueString($badgesdbcon, $_POST['requirement'], "int")
					 );

   
  $Result1 = mysqli_query($badgesdbcon, $insertSQL) or die(mysqli_error());

//this is the redirect
header(sprintf("Location: %s", "new_abstracttestcase.php"));

?>