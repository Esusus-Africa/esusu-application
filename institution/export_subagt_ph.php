<?php
include("../config/session1.php");

//Get value(s) from database
if ($stmt = $link->prepare("SELECT phone FROM user WHERE created_by = '$institution_id' AND role != 'institution_super_admin'")){
    $stmt->execute();
    $stmt->bind_result($phone);

    header('Content-type: text/plain');
    header('Content-Disposition: attachment; filename=sub-agent_phone.txt');

    //Export values to file download
    while ($stmt->fetch()) {
        echo $phone.",\n";
    }
    $stmt->close();
}
?>