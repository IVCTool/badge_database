<?php
# FileName="Connection_php_mysql.htm"
# Type="MYSQL"
# HTTP="true"
$hostname_badgesdbcon = "localhost";
$database_badgesdbcon = "badgesdb";
$username_badgesdbcon = "root";
$password_badgesdbcon = "msg134password";
$badgesdbcon = mysql_pconnect($hostname_badgesdbcon, $username_badgesdbcon, $password_badgesdbcon) or trigger_error(mysql_error(),E_USER_ERROR); 
?>