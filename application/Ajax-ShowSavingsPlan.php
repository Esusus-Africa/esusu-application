<?php
include ("../config/connect.php");
$PostType = $_GET['PostType'];

$detect_subplan = mysqli_query($link, "SELECT * FROM savings_plan WHERE plan_code = '$PostType'");
$fetch_subplan = mysqli_fetch_array($detect_subplan);

$search_currency = mysqli_query($link, "SELECT * FROM systemset");
$fetch_currency = mysqli_fetch_object($search_currency);
?>

<input name="merchantid_others" type="hidden" class="form-control" value="<?php echo $fetch_subplan['merchantid_others']; ?>" id="HideValueFrank"/>
<input name="categories" type="hidden" class="form-control" value="<?php echo $fetch_subplan['categories']; ?>" id="HideValueFrank"/>

<div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Plan Name</label>
                  <div class="col-sm-9">
                  <span style="color: orange; font-size: 21px"><b><?php echo $fetch_subplan['plan_name']; ?></b></span>
                  </div>
                  </div>

 <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Plan Category</label>
                  <div class="col-sm-9">
                 <span style="color: orange; font-size: 21px"><b><?php echo $fetch_subplan['categories']; ?></b></span>
                  </div>
                  </div>

<div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Amount</label>
                  <div class="col-sm-9">
                  <span style="color: orange; font-size: 21px"><b><?php echo $fetch_currency->currency.number_format($fetch_subplan['amount'],2,'.',','); ?></b></span>
                  </div>
                  </div>



 <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Dividend / Interest</label>
                  <div class="col-sm-9">
                 <span style="color: orange; font-size: 21px"><b><?php echo ($fetch_subplan['dividend_type'] == 'Flat Rate') ? number_format($fetch_subplan['dividend'],2,'.',',').' [Flat Rate]' : $fetch_subplan['dividend'].'%' ; ?></b></span>
                  </div>
                  </div>

  <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Savings Interval</label>
                  <div class="col-sm-9">
                 <span style="color: orange; font-size: 21px"><b><?php echo ucfirst($fetch_subplan['savings_interval']); ?></b></span>
                  </div>
                  </div>

  <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Disbursement Period</label>
                  <div class="col-sm-9">
                 <span style="color: orange; font-size: 21px"><b><?php echo ucfirst($fetch_subplan['disbursement_interval']); ?></b></span>
                  </div>
                  </div>

  <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Lock Period</label>
                  <div class="col-sm-9">
                 <span style="color: orange; font-size: 21px"><b><?php echo ($fetch_subplan['lock_period'] == '0') ? 'NIL' : $fetch_subplan['lock_period'].' month(s)'; ?></b> <i><b>(on Monthly Basis OR NIL)</b></i></span>
                  </div>
                  </div>

 <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Current Date</label>
                  <div class="col-sm-9">
                 <input name="dfrom" type="text" class="form-control" value="<?php echo date('Y-m-d'); ?>" readonly/>
                  </div>
                  </div>

<div align="right">
     <div class="box-footer">
        <button name="submit" type="submit" class="btn bg-blue btn-flat"><i class="fa fa-cloud-upload">&nbsp;Subscribe</i></button>

     </div>
</div>