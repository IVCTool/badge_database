<?php
//this page simply adds a requirement from the list of
// those covered by and ATC, and then redirects back to the ATC edit page.
require_once('Connections/badgesdbcon.php');
require_once('include/getsqlvaluestring.php');

$query = sprintf("INSERT INTO abstracttcs_has_requirements (abstracttcs_id, requirements_id) VALUES ( %s, %s)", GetSQLValueString($badgesdbcon, $_POST['atcsid'], "int"), GetSQLValueString($badgesdbcon, 
$_POST['requirementid'], "int"));
//echo $query;

$addq = mysqli_query($badgesdbcon, $query) or die(mysqli_error());

$url =  "atc_edit.php?id=" . $_POST['atcsid'];
header('Location: ' . $url);
?>