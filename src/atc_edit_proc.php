<?php
require_once('Connections/badgesdbcon.php');


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

 
$target_dir = "atcs_files/";

//Fist check if a new new file was indicated, if it was
// then upload it and set it as the file name.  If it
// wasn't, then just use the old one.
$uploadOk = 1;
$updateFile = 0;
$target_file_name = "";
if($_FILES['file']['size'] > 0) {
	$updateFile = 1;
	$target_file_name = basename($_FILES["file"]["name"]);
	$target_file = $target_dir . $target_file_name;}
else {
	$target_file_name = $_POST['oldfile'];
}//end if the file is empty

//Deal with updating the file if we need to
if ($updateFile == 1) {

// Check if file already exists and increment the name until it is unique
$i = 0;
if (file_exists($target_file)) {
	while(file_exists($target_file)) {
		$i++;
		$target_file_name = $i . $target_file_name;
		$target_file = $target_dir . $target_file_name;
	}//end while
}//end if file exists

 // Check file size
if ($_FILES["file"]["size"] > 500000) {
    echo "Sorry, your file is too large.";
    $uploadOk = 0;
}//endif file is too big

// Allow certain file formats
$fileType = pathinfo($target_file,PATHINFO_EXTENSION);
if($fFileType != "doc" && $fileType != "docx" && $fileType != "txt"
&& $fileType != "pdf" ) {
    echo "Sorry, only DOC, DOCX, TXT & PDF files are allowed.";
    $uploadOk = 0;
}//endif allowed format

// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
    echo "Sorry, your file was not uploaded.";
// if everything is ok, try to upload file
} else {
    if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
        //echo "The file ". $target_file_name . " has been uploaded.";
    } else {
        echo "Sorry, there was an error uploading your file.";
		echo '<pre>';
	    echo 'Here is some more debugging info:';
	    print_r($_FILES);
    	print "</pre>";
    }
	
	//since we are updating, we need to unlink the old file, and by this time it's done
	if (unlink($target_dir . $_POST["oldfile"])) {
		//echo "Previous abstract test case file: " . $target_dir . $_POST["oldfile"] . " has been removed.";
	} else {
		//echo "Previous abstract test case file: " . $target_dir . $_POST["oldfile"] . " has not been removed due to an error while unlinking the file.";
	}//end if the file was removed
	
}//endif upload OK


}//endif we should update it at all. (from line 22)

//Now we need to update the record.

$SQL = sprintf("UPDATE abstracttcs SET filename=%s, identifier=%s, name=%s, description=%s, version=%s, requirements_id=%d WHERE id=%d;",
	GetSQLValueString($badgesdbcon, $target_file_name, "text"),
	GetSQLValueString($badgesdbcon, $_POST["identifier"], "text"),
	GetSQLValueString($badgesdbcon, $_POST["name"], "text"),
	GetSQLValueString($badgesdbcon, $_POST["description"], "text"),
	GetSQLValueString($badgesdbcon, $_POST["version"], "text"),
	GetSQLValueString($badgesdbcon, $_POST["requirementsid"], "int"),
	GetSQLValueString($badgesdbcon, $_POST["id"], "int") );

echo $SQL;
 
$res = mysqli_query($badgesdbcon, $SQL) or die(mysqli_error());

//and redirect to the list of Abstract test cases
header("Location: new_abstracttestcase.php");
die();

?>