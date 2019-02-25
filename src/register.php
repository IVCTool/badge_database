<?php require_once('Connections/badgesdbcon.php'); ?>
<?php  require_once('include/getsqlvaluestring.php'); ?>

<?php

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {

  $insertSQL = sprintf("INSERT INTO users (username, password, email) VALUES (%s, %s, %s);",
                       GetSQLValueString($badgesdbcon,  $_POST['username'], "text"),
                       GetSQLValueString($badgesdbcon,  md5($_POST['password']), "text"),
                       GetSQLValueString($badgesdbcon,  $_POST['email'], "text"));

//echo $insertSQL;
  $Result1 = mysqli_query($badgesdbcon, $insertSQL);
	
	
  $insertGoTo = "register_proc.html";
  //header(sprintf("Location: %s", $insertGoTo));
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Register for Editor Status</title>
<script src="SpryAssets/SpryValidationTextField.js" type="text/javascript"></script>
<script src="SpryAssets/SpryValidationPassword.js" type="text/javascript"></script>
<link href="SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css" />
<link href="SpryAssets/SpryValidationPassword.css" rel="stylesheet" type="text/css" />
<link href="css/public.css" rel="stylesheet" type="text/css" />
</head>

<body>
<h1>Register</h1>
<p>Fill in the form below to register as a badge editor. A site administrator will review your application before you recieve access.</p>
<p>If you simply want to view badges, there is no need to register.</p>

<form id="form1" name="form1" method="POST" action="<?php echo $editFormAction; ?>">
  <p>username: <span id="sprytextfield1">
  <label for="username"></label>
  <input type="text" name="username" id="username" tabindex="1" />
  <span class="textfieldRequiredMsg">A value is required.</span><span class="textfieldMinCharsMsg">Minimum number of characters not met.</span><span class="textfieldMaxCharsMsg">Exceeded maximum number of characters.</span></span><br />
    password: <span id="sprypassword1">
    <label for="password"></label>
    <input type="password" name="password" id="password" tabindex="2" />
    <span class="passwordRequiredMsg">A value is required.</span><span class="passwordMaxCharsMsg">Exceeded maximum number of characters.</span><span class="passwordMinCharsMsg">Minimum number of characters not met.</span></span><br />
  e-mail: <span id="sprytextfield2">
  <label for="email"></label>
  <input type="text" name="email" id="email" tabindex="3" />
  <span class="textfieldRequiredMsg">A value is required.</span><span class="textfieldInvalidFormatMsg">Invalid format.</span></span></p>
  <p><br />
    <input type="submit" name="button" id="button" value="Submit" />
  </p>
  <input type="hidden" name="MM_insert" value="form1" />
</form>
<p>&nbsp;</p>
<script type="text/javascript">
var sprytextfield1 = new Spry.Widget.ValidationTextField("sprytextfield1", "none", {minChars:1, maxChars:25});
var sprypassword1 = new Spry.Widget.ValidationPassword("sprypassword1", {maxChars:255, minChars:1});
var sprytextfield2 = new Spry.Widget.ValidationTextField("sprytextfield2", "email");
</script>
</body>
</html>