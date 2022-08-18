<div class="box">
	       <div class="box-body">
			<div class="panel panel-success">
            <div class="panel-heading bg-blue">
            <h3 class="panel-title"><i class="fa fa-globe"></i>  Subscription Form</h3>
            </div>

             <div class="box-body">
              
<form class="form-horizontal" method="post" enctype="multipart/form-data" action="process_saassub1.php">

<div class="box-body">

<?php
$pcode = $_GET['pcode'];
$detect_subplan = mysqli_query($link, "SELECT * FROM saas_subscription_plan WHERE plan_code = '$pcode'");
$fetch_subplan = mysqli_fetch_array($detect_subplan);
$search_currency = mysqli_query($link, "SELECT * FROM systemset");
$fetch_currency = mysqli_fetch_object($search_currency);
$amount_per_months = $fetch_subplan['amount_per_months'];
?>

<input name="plan_code" type="hidden" class="form-control" value="<?php echo $fetch_subplan['plan_code']; ?>" id="HideValueFrank"/>

<input name="amount_per_months" type="hidden" class="form-control" value="<?php echo $fetch_subplan['amount_per_months']; ?>" id="HideValueFrank"/>

<input name="expiration_grace" type="hidden" class="form-control" value="<?php echo $fetch_subplan['expiration_grace']; ?>" id="HideValueFrank"/>

<input name="sms_allocated" type="hidden" class="form-control" value="<?php echo $fetch_subplan['sms_allocated']; ?>" id="HideValueFrank"/>

<input name="staff_limit" type="hidden" class="form-control" value="<?php echo $fetch_subplan['staff_limit']; ?>" id="HideValueFrank"/>

<input name="branch_limit" type="hidden" class="form-control" value="<?php echo $fetch_subplan['branch_limit']; ?>" id="HideValueFrank"/>

<input name="customer_limit" type="hidden" class="form-control" value="<?php echo $fetch_subplan['customers_limit']; ?>" id="HideValueFrank"/>

<div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Amount (per month)</label>
                  <div class="col-sm-9">
                  <span style="color: orange; font-size: 21px"><b><?php echo $fetch_currency->currency.number_format($fetch_subplan['amount_per_months'],2,'.',','); ?></b></span>
                  </div>
                  </div>

 <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">SMS Allocated</label>
                  <div class="col-sm-9">
                 <span style="color: orange; font-size: 21px"><b><?php echo number_format($fetch_subplan['sms_allocated']); ?></b> <i>Bonus</i></span>
                  </div>
                  </div>

  <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Other Features</label>
                  <div class="col-sm-9">
                 <span style="color: orange; font-size: 18px"><?php echo $fetch_subplan['others']; ?></span>
                  </div>
                  </div>

<?php
if($amount_per_months == "0")
{
?>
  
 <?php
}
else{
?>
  <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Coupon Code</label>
                  <div class="col-sm-9">
                 <input name="ccode" type="text" class="form-control" placeholder="Enter Coupon Code" />
                 <span style="color: orange; font-size: 15px;">You can enter <b>coupon code</b> here if available</span>
                  </div>
                  </div>
<?php } ?>

 <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Starting From</label>
                  <div class="col-sm-9">
                 <input name="dfrom" type="text" class="form-control" value="<?php echo date('Y-m-d'); ?>" readonly/>
                  </div>
                  </div>

 <?php
if($amount_per_months == "0")
{
  $refid = rand(10000000,99999999);
?>
<div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Number of Month(s)</label>
                  <div class="col-sm-9">
                   <input name="dto" type="hidden" class="form-control" value="1"/>
                   <input name="refid" type="hidden" class="form-control" value="<?php echo $refid; ?>"/>
                   <span style="color: orange; font-size: 18px;"><b>NOTE:</b> that this subscription plan last for just 30 days with limited features as described above.</span>
                 </div>
               </div>
 <?php
}
else{
?>
 <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Number of Month(s)</label>
                  <div class="col-sm-9">
                 <input name="dto" type="number" class="form-control" placeholder="Enter Number of month(s) you wish to subscribe for, in order to use the Software" required/>
                 <span style="color: orange; font-size: 18px;"><b>NOTE:</b> You are to enter the number of months you want to subscribe for here in order to use the software</span>
                  </div>
                  </div>
<?php
}
?>


</div>

<div align="right">
     <div class="box-footer">
        <button name="submit" type="submit" class="btn bg-blue btn-flat"><i class="fa fa-cloud-upload">&nbsp;Proceed</i></button>

     </div>
</div>
 </form>

</div>	
</div>	
</div>
</div>