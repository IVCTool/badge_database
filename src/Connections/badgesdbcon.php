<?php
# FileName="Connection_php_mysql.htm"
# Type="MYSQL"
# HTTP="true"
$hostname_badgesdbcon = "localhost";
$database_badgesdbcon = "badgesdb";
$username_badgesdbcon = "adminer";
$password_badgesdbcon = "MSG134password";
$badgesdbcon = mysqli_connect($hostname_badgesdbcon, $username_badgesdbcon, $password_badgesdbcon, $database_badgesdbcon);
?>