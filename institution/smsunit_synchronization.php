<?php
include("../config/session1.php");

echo number_format(($iassigned_walletbal/$fetchsys_config['fax']),2,'.',',')." SMS";
?>