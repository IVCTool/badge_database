<?php require_once('Connections/badgesdbcon.php'); ?>

<?php 

$sql="DELETE FROM users WHERE id=" . $_POST["id"];


 
$Result1 = mysqli_query($badgesdbcon, $sql);

header(sprintf("Location: %s", "review_registrations.php"));