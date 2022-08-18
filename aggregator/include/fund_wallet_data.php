<div class="box">
	       <div class="box-body">
			<div class="panel panel-success">
            <div class="panel-heading bg-blue">
            <h3 class="panel-title"> <a href="mywallet.php?id=<?php echo $_SESSION['tid']; ?>&&mid=NDA0&&tab=tab_1"><button type="button" class="btn btn-flat bg-orange"><i class="fa fa-mail-reply-all"></i>&nbsp;Back</button></a> <a href="fund_wallet.php?id=<?php echo $_SESSION['tid']; ?>&&mid=NDA0"><button type="button" class="btn btn-flat bg-orange"><i class="fa fa-refresh"></i>&nbsp;Refresh</button></a> 
            
<button type="button" class="btn btn-flat bg-white" align="left" style="color: black;">&nbsp;<b>Transfer Balance:</b>&nbsp;
<strong class="alert bg-orange">
<?php
echo "<span>".$aggcurrency.number_format($aggwallet_balance,2,'.',',')."</span>";
?>
</strong>
  </button>
           
            </h3>
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
<div class="alert bg-orange">
    
    Please be informed that your information is secured as you fund your <b>SUPER WALLET</b> here.
    <br>
    <b style="font-size:18px;">NOTICE:</b> 
    <p>(1). Note that for Local Payments with Mastercard, Visa, Bank Account, USSD: the charges is <?php echo ($localpayment_charges * 100).'%'; ?> fees subject to cap of <?php echo $row1->currency.number_format($capped_amount,2,'.',','); ?>
    </p>
    <p>(2). Note that for International Card Payments with Mastercard, Visa, AMEX: the charges is <?php echo ($intlpayment_charges * 100).'%'; ?>
    </p>
</div>
</hr>
            <div class="box-body">
                 
                 <div class="form-group">
                    <label for="" class="col-sm-3 control-label" style="color:blue;">Amount:</label>
                    <div class="col-sm-6">
                        <input name="amount" type="text" class="form-control" placeholder="Enter Amount" required>
                    </div>
                    <label for="" class="col-sm-3 control-label"></label>
                </div>
      
            </div>
            
            <div class="form-group" align="right">
                <label for="" class="col-sm-3 control-label" style="color:blue;"></label>
                <div class="col-sm-6">
                    <button name="FundWallet" type="submit" class="btn bg-blue btn-flat"><i class="fa fa-mobile">&nbsp;Fund Wallet</i></button>
                </div>
                <label for="" class="col-sm-3 control-label"></label>
             </div>
			  
			 </form>

</div>	
</div>	
</div>
</div>