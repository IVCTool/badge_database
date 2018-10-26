<?php require_once('Connections/badgesdbcon.php'); ?>
<?php
$target_dir = "badge_graphics/";
$target_file_name = basename($_FILES["fileToUpload"]["name"]);
$target_file = $target_dir . $target_file_name;
echo $target_file; echo "<br/>";

$uploadOk = 1;
$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
// Check if image file is a actual image or fake image
if(isset($_POST["submit"])) {
    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
    if($check !== false) {
        echo "File is an image - " . $check["mime"] . ".";
        $uploadOk = 1;
    } else {
        echo "File is not an image.";
        $uploadOk = 0;
    }
}

// Check if file already exists and increment the name until it is unique
$i = 0;
if (file_exists($target_file)) {
	while(file_exists($target_file)) {
		$i++;
		$target_file_name = $i . $target_file_name;
		$target_file = $target_dir . $target_file_name;
	}//end while
}//end if file exitist

 // Check file size
if ($_FILES["fileToUpload"]["size"] > 500000) {
    echo "Sorry, your file is too large.";
    $uploadOk = 0;
}

// Allow certain file formats
if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
&& $imageFileType != "gif" ) {
    echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
    $uploadOk = 0;
}

// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
    echo "Sorry, your file was not uploaded.";
// if everything is ok, try to upload file
} else {
    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
        echo "The file ". $target_file_name . " has been uploaded.";
    } else {
        echo "Sorry, there was an error uploading your file.";
		echo '<pre>';
    echo 'Here is some more debugging info:';
    print_r($_FILES);
    print "</pre>";
    }
}//endif upload OK
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

/* Not really sure what this does
$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}
*/

//Only try to do the insert if the upload worked
if($uploadOk) {

$insertSQL = sprintf("INSERT INTO badges (`description`, graphicfile, identifier) VALUES (%s, %s, %s)",
                     GetSQLValueString($_POST['description'], "text"),
                     GetSQLValueString($target_file_name, "text"),
                     GetSQLValueString($_POST['identifier'], "text"));

  mysql_select_db($database_badgesdbcon, $badgesdbcon);
  $Result1 = mysql_query($insertSQL, $badgesdbcon) or die(mysql_error());

//this is the redirect
header(sprintf("Location: %s", "new_badge.php"));

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
