<?php require_once('Connections/badgesdbcon.php'); ?>
<?php require_once('globals.php'); ?>
<?php



//Fist check if a new graphic was indicated, if it was
// then upload it and set it as the graphic file name.  If it
// wasn't, then just use the old one.
$uploadOk = 1;
$updateGraphic = 0;
if($_FILES['graphic']['size'] > 0) {
	$updateGraphic = 1;
	$target_file_name = basename($_FILES["graphic"]["name"]);
	$target_file = $bgURL . $target_file_name;}
else {
	$target_file_name = $_POST['oldgrphic'];
}//end if the file is empty


if ($updateGraphic == 1) {
$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
// Check if image file is a actual image or fake image
if(isset($_POST["submit"])) {
    $check = getimagesize($_FILES["graphic"]["tmp_name"]);
    if($check !== false) {
        echo "File is an image - " . $check["mime"] . ".";
        $uploadOk = 1;
    } else {
        echo "File is not an image.";
        $uploadOk = 0;
    }//end if check
}//endif $_POST

// Check if file already exists and increment the name until it is unique
$i = 0;
if (file_exists($target_file)) {
	while(file_exists($target_file)) {
		$i++;
		$target_file_name = $i . $target_file_name;
		$target_file = $bgURL . $target_file_name;
	}//end while
}//end if file exitist

 // Check file size
if ($_FILES["graphic"]["size"] > 500000) {
    echo "Sorry, your file is too large.";
    $uploadOk = 0;
}//endif file is too big

// Allow certain file formats
if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
&& $imageFileType != "gif" ) {
    echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
    $uploadOk = 0;
}//endif allowed format

// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
    echo "Sorry, your file was not uploaded.";
// if everything is ok, try to upload file
} else {
    if (move_uploaded_file($_FILES["graphic"]["tmp_name"], $target_file)) {
        echo "The file ". $target_file_name . " has been uploaded.";
    } else {
        echo "Sorry, there was an error uploading your file.";
		echo '<pre>';
	    echo 'Here is some more debugging info:';
	    print_r($_FILES);
    	print "</pre>";
    }
	
	//since we are updating, we need to unlink the old file, and by this time it's done
	if (unlink($bgURL . $_POST["oldgraphic"])) {
		echo "Previous badge graphic file: " . $bgURL . $_POST["oldgraphic"] . " has been removed.";
	} else {
		echo "Previous badge graphic file: " . $bgURL . $_POST["oldgraphic"] . " has no been removed due to an error while unlinking the file.";
	}//end if the file was removed
	
}//endif upload OK

}//endif we should update it at all. (from line 22)
?>

<?php require_once('include/getsqlvaluestring.php'); ?>
<?php

/* Not really sure what this does
$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}
*/

//Only try to do the update, $uploadOk will be 1 if it worked, or if it wasn't required.
$updateSQL = "NULL";
$result;
if($uploadOk) {

	if ($updateGraphic == 1) { //we need to update the file name
		$updateSQL = sprintf("UPDATE badges SET `description`=%s, graphicfile=%s, identifier=%s WHERE id=%s",
                     GetSQLValueString($badgesdbcon, $_POST['description'], "text"),
                     GetSQLValueString($badgesdbcon, $target_file_name, "text"),
                     GetSQLValueString($badgesdbcon, $_POST['identifier'], "text"),
					 GetSQLValueString($badgesdbcon, $_POST['id'], "int"));
	} else { // we don't need to update it
		$updateSQL = sprintf("UPDATE badges SET `description`=%s, identifier=%s WHERE id=%s",
            	         GetSQLValueString($badgesdbcon, $_POST['description'], "text"),
        	             GetSQLValueString($badgesdbcon, $_POST['identifier'], "text"),
						 GetSQLValueString($badgesdbcon, $_POST['id'], "int"));
	}//end make up query string

	 
	$result = mysqli_query($badgesdbcon, $updateSQL) or die(mysqli_error());

//this is the redirect, we don't need it or do we
/*
  $insertGoTo = "new_badge.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
*/
}//endif upload ok

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Badge updated</title>
<link href="css/admin.css" rel="stylesheet" type="text/css" />
</head>

<body>
<?php if ($result) { ?>
<h1>Badge updated</h1>
<?php } else { ?>
<h1>Badge not updated</h1>
<p>For debugging:</p>  
<p><?php echo $updateSQL; ?></p>
<p><?php foreach ($_FILES as $key => $value) {
	echo "$key: $value<br>";
	foreach ($value as $key2 => $value2) {
    	echo "$key2: $value2<br>";
	}
} ?></p>
<?php } //end if ?>

<p>[ <a href="editor_start.php">Editor Home</a> | <a href="new_badge.php">Badges</a> ]</p>
</body>
</html>