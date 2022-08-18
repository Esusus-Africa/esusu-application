<?php
include("../config/session.php");

echo number_format(($bwallet_balance/$fetchsys_config['fax']),2,'.',',')." unit(s)";
?>