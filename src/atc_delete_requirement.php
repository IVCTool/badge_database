<?php
//this page simply deletes a requirement from the list of
// those covered by and ATC, and then redirects back to the ATC edit page.
require_once('Connections/badgesdbcon.php');
require_once('include/getsqlvaluestring.php');

$query = sprintf("DELETE FROM abstracttcs_has_requirements WHERE (requirements_id=%s AND abstracttcs_id=%s)", GetSQLValueString($badgesdbcon, $_POST['requirementid'], "int"), GetSQLValueString($badgesdbcon, $_POST['atcsid'], "int"));
$delq = mysqli_query($badgesdbcon, $query) or die(mysqli_error());

$url =  "atc_edit.php?id=" . $_POST['atcsid'];
header('Location: ' . $url);
?>