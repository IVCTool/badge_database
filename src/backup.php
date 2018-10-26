<?php
//initialize the session
if (!isset($_SESSION)) {
  session_start();
}

// ** Logout the current user. **
$logoutAction = $_SERVER['PHP_SELF']."?doLogout=true";
if ((isset($_SERVER['QUERY_STRING'])) && ($_SERVER['QUERY_STRING'] != "")){
  $logoutAction .="&". htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_GET['doLogout'])) &&($_GET['doLogout']=="true")){
  //to fully log out a visitor we need to clear the session varialbles
  $_SESSION['MM_Username'] = NULL;
  $_SESSION['MM_UserGroup'] = NULL;
  $_SESSION['PrevUrl'] = NULL;
  unset($_SESSION['MM_Username']);
  unset($_SESSION['MM_UserGroup']);
  unset($_SESSION['PrevUrl']);
	
  $logoutGoTo = "index.html";
  if ($logoutGoTo) {
    header("Location: $logoutGoTo");
    exit;
  }
}
?>
<?php require_once('Connections/badgesdbcon.php'); ?>
<?php
$backupDir = "backup";
$path = "/var/www/$backupDir";
$buDate = date("d-m-Y-H-i-s");

//first we need to make up the file name
$dumpFileName = "$database_badgesdbcon" . "_" . "$buDate.gz";
$fileName = "$path/$dumpFileName";

//Now the Linux command to do it
//$command = "mysqldump --opt -h $hostname_badgesdbcon -u $username_badgesdbcon //--password=$password_badgesdbcon $database_badgesdbcon | gzip > $fileName";
$command = "mysqldump -h $hostname_badgesdbcon -u $username_badgesdbcon --password=$password_badgesdbcon $database_badgesdbcon | gzip > $fileName";

system($command);

//Now backup the images that go with the badges as well.
$graphicsFile = "badge_graphics_$buDate.tar.gz";
$graphicsPath = "/var/www/badge_graphics";
$command2 = "tar -zcf $path/$graphicsFile $graphicsPath";
//echo $command2;
system($command2);

?>
<html>
<head>
<title>Database back-up</title>
<link href="css/admin.css" rel="stylesheet" type="text/css" />
</head>
<body>
<h1>Database backed-up</h1>
<?php //echo $command ?>
<?php //echo $command2 ?>
<ul>
  <li><a href="<?php echo "$backupDir/$dumpFileName" ?>">Download the latest database dump file</a> backup</li>
  <li><a href="<?php echo "$backupDir/$graphicsFile" ?>">Download the latest badge graphics</a> backup</li>
</ul>
<p>Older back-ups area available to the server system administrator.</p>

<p>[ <a href="editor_start.php">Editor home</a> | <a href="<?php echo $logoutAction ?>">Logout</a> ]</p>
</body>
