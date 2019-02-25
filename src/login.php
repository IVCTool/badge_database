<?php error_reporting(E_ALL); ?>
<?php require_once('Connections/badgesdbcon.php'); ?>
<?php  require_once('include/mysqli_result.php'); ?>
<?php  require_once('include/getsqlvaluestring.php'); ?>
<?php 
// *** Validate request to login to this site.
if (!isset($_SESSION)) {
  session_start();
}

//$loginFormAction = $_SERVER['PHP_SELF'];
if (isset($_GET['accesscheck'])) {
  $_SESSION['PrevUrl'] = $_GET['accesscheck'];
}

if (isset($_POST['username'])) {
  $loginUsername=$_POST['username'];
  $password=md5($_POST['password']);
  $MM_fldUserAuthorization = "approved";
  $MM_redirectLoginSuccess = "editor_start.php";
  $MM_redirectLoginFailed = "access_denied.html";
  $MM_redirecttoReferrer = false;
 

  
  $LoginRS__query=sprintf("SELECT username, password, approved FROM users WHERE username=%s AND password=%s",
  GetSQLValueString($badgesdbcon, $loginUsername, "text"), GetSQLValueString($badgesdbcon, $password, "text")); 
   
  $LoginRS = mysqli_query($badgesdbcon, $LoginRS__query);
  $loginFoundUser = mysqli_num_rows($LoginRS);
  
  //echo $loginFoundUser;
  if ($loginFoundUser) {
    
    $loginStrGroup  = mysqli_result($LoginRS,0,'approved');
    
	session_regenerate_id(true);
    //declare two session variables and assign them
    $_SESSION['MM_Username'] = $loginUsername;
    $_SESSION['MM_UserGroup'] = $loginStrGroup;	      

    if (isset($_SESSION['PrevUrl']) && false) {
      $MM_redirectLoginSuccess = $_SESSION['PrevUrl'];	
    }
    header("Location: " . $MM_redirectLoginSuccess );
  }
  else {
   header("Location: ". $MM_redirectLoginFailed );
  }
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Login</title>
<link href="css/public.css" rel="stylesheet" type="text/css" />
</head>

<body>
<h1>Log in</h1>
<form id="form1" name="form1" method="POST" action="login.php">
  <p>username: 
    <label for="username"></label>
  <input type="text" name="username" id="username" tabindex="1" />
  <br />
  password: 
  <label for="password"></label>
  <input type="password" name="password" id="password" tabindex="2" />
  </p>
  <p>
    <input type="submit" name="button" id="button" value="Log in" />
  </p>
</form>
<p>&nbsp;</p>
</body>
</html>