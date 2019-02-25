<?php require_once('Connections/badgesdbcon.php'); ?>

<?php 

$sql="UPDATE users SET approved=1 WHERE id=" . $_POST["id"];

 
$Result1 = mysqli_query($badgesdbcon, $sql);

header(sprintf("Location: %s", "review_registrations.php"));