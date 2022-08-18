<div class="box">
         <div class="box-body">
      <div class="panel panel-success">
            <div class="panel-heading bg-blue">
<?php
if(isMobileDevice()){
    //Your content or code for mobile devices goes here
?>

<h3 class="panel-title"> <a href="mywallet_history.php?tid=<?php echo $_SESSION['tid']; ?>&&acn=<?php echo $_SESSION['acctno']; ?>&&mid=NDA4&&tab=tab_1"><button type="button" class="btn btn-flat bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>"><i class="fa fa-mail-reply-all"></i>&nbsp;Back</button></a> <a href="fund_wallet.php?id=<?php echo $_SESSION['tid']; ?>&&acn=<?php echo $_SESSION['acctno']; ?>&&mid=NDA4"><button type="button" class="btn btn-flat bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>"><i class="fa fa-refresh"></i>&nbsp;Refresh</button></a>
<button type="button" class="btn btn-flat bg-white" align="left" style="color: black;">&nbsp;<b>Balance:</b>&nbsp;
<strong class="alert bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>">
<?php
echo "<span id='wallet_balance'>".$bbcurrency.number_format($bwallet_balance,2,'.',',')."</span>";
?> 
</strong>
  </button>
</h3>

<?php
}
else{
    ?>
            <h3 class="panel-title"> <a href="mywallet_history.php?tid=<?php echo $_SESSION['tid']; ?>&&acn=<?php echo $_SESSION['acctno']; ?>&&mid=NDA4&&tab=tab_1"><button type="button" class="btn btn-flat bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>"><i class="fa fa-mail-reply-all"></i>&nbsp;Back</button></a> <a href="fund_wallet.php?id=<?php echo $_SESSION['tid']; ?>&&acn=<?php echo $_SESSION['acctno']; ?>&&mid=NDA4"><button type="button" class="btn btn-flat bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>"><i class="fa fa-refresh"></i>&nbsp;Refresh</button></a>
            <button type="button" class="btn btn-flat bg-white" align="left" style="color: black;">&nbsp;<b>Balance:</b>&nbsp;
            <strong class="alert bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>">
            <?php
            echo "<span id='wallet_balance'>".$bbcurrency.number_format($bwallet_balance,2,'.',',')."</span>";
            ?> 
            </strong>
              </button>
            </h3>

<?php    
}
?>
            </div>
<?php
    $systemset = mysqli_query($link, "SELECT * FROM systemset");
    $row1 = mysqli_fetch_object($systemset);
    
    $localpayment_charges = $row1->localpayment_charges;
    $capped_amount = $row1->capped_amount;
    $intlpayment_charges = $row1->intlpayment_charges;
?>
             <div class="box-body">
          
       <form class="form-horizontal" method="post" enctype="multipart/form-data" action="process_walletfund.php">
<hr>
<div class="alert bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>">
    
    Please be informed that your information is secured as you fund your <b>SUPER WALLET</b> here.
    <br>
    <b style="font-size:18px;">NOTICE:</b> 
    <p>(1). Note that for Local Payments with Mastercard, Visa, Bank Account, USSD: the charges is <b><?php echo ($localpayment_charges * 100).'%'; ?></b> fees subject to cap of <b><?php echo $row1->currency.number_format($capped_amount,2,'.',','); ?></b>
    </p>
    <p>(2). Note that for International Card Payments with Mastercard, Visa, AMEX: the charges is <b><?php echo ($intlpayment_charges * 100).'%'; ?></b>
    </p>
</div>
</hr>
             <div class="box-body">
                 
                 <div class="form-group">
                    <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Amount:</label>
                    <div class="col-sm-6">
                        <input name="amount" type="text" class="form-control" placeholder="Enter Amount" required>
                    </div>
                    <label for="" class="col-sm-3 control-label"></label>
                </div>
      
            </div>
            
            <div class="form-group" align="right">
                <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;"></label>
                <div class="col-sm-6">
                    <button name="FundWallet" type="submit" class="btn bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?> btn-flat"><i class="fa fa-mobile">&nbsp;Fund Wallet</i></button>
                </div>
                <label for="" class="col-sm-3 control-label"></label>
             </div>
             
       </form>

</div>  
</div>  
</div>
</div>              