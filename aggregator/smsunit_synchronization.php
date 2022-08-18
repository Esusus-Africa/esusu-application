<?php
include("../config/session.php");

echo number_format(($aggwallet_balance/$fetchsys_config['fax']),2,'.',',')." SMS";
?>