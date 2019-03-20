<?php 
require_once('Connections/badgesdbcon.php'); 
require_once('globals.php');
require_once('include/getsqlvaluestring.php');
//echo $atcsURL;

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


$insertSQL = sprintf("INSERT INTO abstracttcs (description, filename, name, identifier, version) VALUES (%s, %s, %s, %s, %s)", GetSQLValueString($badgesdbcon, $_POST['description'], "text"), GetSQLValueString($badgesdbcon, $target_file_name, "text"), GetSQLValueString($badgesdbcon, $_POST['name'], "text"), GetSQLValueString($badgesdbcon, $_POST['identifier'], "text"), GetSQLValueString($badgesdbcon, $_POST['version'], "text"));

$Result1 = mysqli_query($badgesdbcon, $insertSQL) or die(mysqli_error());
  
//this is the redirect
header('Location: new_abstracttestcase.php');

?>