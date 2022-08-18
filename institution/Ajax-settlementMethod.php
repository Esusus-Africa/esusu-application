<?php
include ("../config/session1.php");
$PostType = $_GET['PostType'];
$TIDOperator = $_GET['TIDOperator'];

if($PostType == "Transfer Wallet")
{
?>          
                  <input name="pos_walletid" type="hidden" value="">
<?php
}else{
    //Do nothing
}
?>