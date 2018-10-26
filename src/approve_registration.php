<?php require_once('Connections/badgesdbcon.php'); ?>

<?php 

$sql="UPDATE users SET approved=1 WHERE id=" . $_POST["id"];

mysql_select_db($database_badgesdbcon, $badgesdbcon);
$Result1 = mysql_query($sql, $badgesdbcon) or die(mysql_error());

header(sprintf("Location: %s", "review_registrations.php"));