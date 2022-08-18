<?php
include ("../config/session.php");
$PostType = $_GET['PostType'];
$TIDOperator = $_GET['TIDOperator'];

if($PostType == "Transfer Wallet")
{
?>          
                  <input name="pos_walletid" type="hidden" value="">
                
<?php
}
elseif($PostType == "POS Wallet"){
    $searchPOSWallet = mysqli_query($link, "SELECT * FROM pos_wallet WHERE tid = '$TIDOperator'");
    $fetchPOSWallet = mysqli_fetch_array($searchPOSWallet);
    $posWalletID = $fetchPOSWallet['walletid'];
?>

                <div class="form-group">
                <label for="" class="col-sm-4 control-label" style="color:blue;">Wallet ID:</label>
                <div class="col-sm-7">
                <?php
                if($posWalletID == ""){

                    echo '<input name="pos_walletid" type="text" placeholder="Enter POS Wallet ID" class="form-control" required>';

                }else{
                ?>

                  <input name="pos_walletid" type="text" value="<?php echo $posWalletID; ?>" placeholder="Enter POS Wallet ID" class="form-control" readonly>

                <?php
                }
                ?>
                  </div>
                <label for="" class="col-sm-1 control-label"></label>
                </div>

<?php
}else{
    //Do nothing
}
?>