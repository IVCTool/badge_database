<?php require_once('Connections/badgesdbcon.php'); ?>
<?php require_once('include/getsqlvaluestring.php'); ?>
<?php

if ((isset($_POST['id'])) && ($_POST['id'] != "")) {
  $deleteSQL = sprintf("DELETE FROM requirements WHERE id=%s",
                       GetSQLValueString($badgesdbcon, $_POST['id'], "int"));

   
  $Result1 = mysqli_query($badgesdbcon, $deleteSQL) or die(mysqli_error());
  
  //and get rid of any badge dependencies on this requirement.
  $deleteDepSQL = sprintf("DELETE FROM badges_has_requirements WHERE requirements_id=%s",
                       GetSQLValueString($badgesdbcon, $_POST['id'], "int"));

   
  $Result2 = mysqli_query($badgesdbcon, $deleteDepSQL) or die(mysqli_error());

  $deleteGoTo = "new_requirement.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $deleteGoTo .= (strpos($deleteGoTo, '?')) ? "&" : "?";
    $deleteGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $deleteGoTo));
}
?>
