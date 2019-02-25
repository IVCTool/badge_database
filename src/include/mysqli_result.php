<?php
// This include is meant to provide the functionality of mysql_result that is missing
// from the new mysqli API.  
//
// The code came from tuxedobob's comment here: http://ca3.php.net/manual/en/class.mysqli-result.php
function mysqli_result($res, $row, $field=0) { 
    $res->data_seek($row); 
    $datarow = $res->fetch_array(); 
    return $datarow[$field]; 
}  

?>