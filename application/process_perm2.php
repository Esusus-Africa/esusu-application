<?php
include("../config/connect.php");

$val = $_POST['val'];   

$apply = $_POST['apply']; 

$permid = $_POST['permid']; 

mysqli_query($link, "UPDATE my_permission2 SET $val = '$apply' WHERE id = '$permid'") or die("Error: " . mysql_error($link));

?>