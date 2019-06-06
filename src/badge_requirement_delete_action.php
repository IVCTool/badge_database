<?php require_once('Connections/badgesdbcon.php'); ?>
<?php require_once('include/getsqlvaluestring.php'); ?>

<?php 


$deleteSQL = sprintf("DELETE FROM badges_has_requirements WHERE (badges_id=%s AND requirements_id=%s)",
             GetSQLValueString($badgesdbcon, $_POST['badgeid'], "int"),
			 GetSQLValueString($badgesdbcon, $_POST['reqid'], "int"));
			 
 
$result = mysqli_query($badgesdbcon, $deleteSQL) or die(mysqli_error());

$returnHeader = "Location: badge_requirements.php?id=" . $_POST['badgeid'];
header($returnHeader);

?>
