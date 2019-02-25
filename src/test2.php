<?php  require_once('Connections/badgesdbcon.php'); ?>
<?php
	$sql = "INSERT INTO users (username, password, email) VALUES ('name', 'password', 'email') ";
	echo $sql;
	$r = mysqli_query($badgesdbcon, $sql);
	
?>