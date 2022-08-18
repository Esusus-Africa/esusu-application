<?php
include("../config/session1.php");

echo number_format(($vwallet_balance/$fetchsys_config['fax']),2,'.',',')." SMS";
?>