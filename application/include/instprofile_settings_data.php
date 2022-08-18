<div class="row"> 
    
   <section class="content">
<?php
$id = $_GET['idm'];
$search_inst = mysqli_query($link, "SELECT * FROM institution_data WHERE institution_id = '$id'");
$fetch_inst = mysqli_fetch_object($search_inst);
$iaccount_type = $fetch_inst->account_type;
?>
       <div class="box box-danger">
           <div class="box-body">
             <div class="table-responsive"> 
            <div class="box-body">
          <a href="listinstitution.php?id=<?php echo $_SESSION['tid']; ?>&&mid=NDE5"> <button type="button" class="btn btn-flat bg-orange"><i class="fa fa-reply-all"></i>&nbsp;Back</button></a>

     <div class="col-md-14">
            <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
             <li <?php echo ($_GET['tab'] == 'tab_1') ? "class='active'" : ''; ?>><a href="instprofile_settings.php?id=<?php echo $_SESSION['tid']; ?>&&idm=<?php echo $_GET['idm']; ?>&&mid=NDE5&&tab=tab_1">Billing Settings</a></li>

             <li <?php echo ($_GET['tab'] == 'tab_2') ? "class='active'" : ''; ?>><a href="instprofile_settings.php?id=<?php echo $_SESSION['tid']; ?>&&idm=<?php echo $_GET['idm']; ?>&&mid=NDE5&&tab=tab_2">Label Changing</a></li>

             <li <?php echo ($_GET['tab'] == 'tab_3') ? "class='active'" : ''; ?>><a href="instprofile_settings.php?id=<?php echo $_SESSION['tid']; ?>&&idm=<?php echo $_GET['idm']; ?>&&mid=NDE5&&tab=tab_3">Global Settings</a></li>
             
             <li <?php echo ($_GET['tab'] == 'tab_4') ? "class='active'" : ''; ?>><a href="instprofile_settings.php?id=<?php echo $_SESSION['tid']; ?>&&idm=<?php echo $_GET['idm']; ?>&&mid=NDE5&&tab=tab_4">Mobile App Instance</a></li>

             <li <?php echo ($_GET['tab'] == 'tab_5') ? "class='active'" : ''; ?>><a href="instprofile_settings.php?id=<?php echo $_SESSION['tid']; ?>&&idm=<?php echo $_GET['idm']; ?>&&mid=NDE5&&tab=tab_5">Email Config</a></li>

             <li <?php echo ($_GET['tab'] == 'tab_6') ? "class='active'" : ''; ?>><a href="instprofile_settings.php?id=<?php echo $_SESSION['tid']; ?>&&idm=<?php echo $_GET['idm']; ?>&&mid=NDE5&&tab=tab_6">Livechat Widget</a></li>

             <li <?php echo ($_GET['tab'] == 'tab_7') ? "class='active'" : ''; ?>><a href="instprofile_settings.php?id=<?php echo $_SESSION['tid']; ?>&&idm=<?php echo $_GET['idm']; ?>&&mid=NDE5&&tab=tab_7">KYC Checklist</a></li>
            </ul>
            <div class="tab-content">
<?php
if(isset($_GET['tab']) == true)
{
  $tab = $_GET['tab'];
  if($tab == 'tab_1')
  {
  ?>
             <div class="tab-pane <?php echo ($_GET['tab'] == 'tab_1') ? 'active' : ''; ?>" id="tab_1">

             <div class="box-body">

<?php
$insid = $_GET['idm'];
$se = mysqli_query($link, "SELECT * FROM maintenance_history WHERE company_id = '$insid'") or die (mysqli_error($link));
if(mysqli_num_rows($se) == 1)
{
$sel = mysqli_fetch_array($se);
$cust_mfee = $sel['cust_mfee'];
$lbooking = $sel['loan_booking'];
$tcharges_type = $sel['tcharges_type'];
$t_charges = $sel['t_charges'];
$capped_amt = $sel['capped_amt'];
$status = $sel['status'];
$billing_type = $sel['billing_type'];
$mybvn_fee = $sel['bvn_fee'];
$myussd_scost = $sel['ussd_session_cost'];
$bank_transferCharges = $sel['bank_transfer_charges'];
$bank_transferCommission = $sel['bank_transfer_commission'];
$card_transferCharges = $sel['transferToCardCharges'];
$card_transferCommission = $sel['transferToCardCommission'];
$verveCard_LinkingFee = $sel['verveCardLinkingFee'];
$airtimeData_comm = $sel['airtimeData_comm'];
$billpay_comm = $sel['billpay_comm'];
$walletPmtType = $sel['walletPaymentType'];
$walletPmtCharges = $sel['walletPaymentCharges'];
$walletPmtChargesCapped = $sel['walletPaymentChargesCapped'];
$smsfee = $sel['smscharges'];
$ninVCharges = $sel['ninVerificationCharges'];
$bvnVCharges = $sel['bvnVerificationCharges'];
//$esusupay_charges = $sel['esusupay_charges'];
//$pos_charges = $sel['pos_charges'];
?>
<form class="form-horizontal" method="post" enctype="multipart/form-data">

<?php
if(isset($_POST['Update_charges']))
{
  $insid = $_GET['idm'];
  $cust_mfee =  mysqli_real_escape_string($link, $_POST['cust_mfee']);
  $lbooking =  mysqli_real_escape_string($link, $_POST['lbooking']);
  $tcharges_type = mysqli_real_escape_string($link, $_POST['tcharges_type']);
  $t_charges =  mysqli_real_escape_string($link, $_POST['t_charges']);
  $capped_amt =  mysqli_real_escape_string($link, $_POST['capped_amt']);
  $status =  mysqli_real_escape_string($link, $_POST['status']);
  $billtype = mysqli_real_escape_string($link, $_POST['billtype']);
  $bvn_fee = mysqli_real_escape_string($link, $_POST['bvn_fee']);
  $ussd_session_cost = mysqli_real_escape_string($link, $_POST['ussd_session_cost']);
  $bank_transfer_charges = mysqli_real_escape_string($link, $_POST['bank_transfer_charges']);
  $bank_transfer_commission = mysqli_real_escape_string($link, $_POST['bank_transfer_commission']);
  $card_transfer_charges = mysqli_real_escape_string($link, $_POST['card_transfer_charges']);
  $card_transfer_commission = mysqli_real_escape_string($link, $_POST['card_transfer_commission']);
  $vervecard_linkingfee = mysqli_real_escape_string($link, $_POST['vervecard_linkingfee']);
  $airtimeData_commission = mysqli_real_escape_string($link, $_POST['airtimedata_comm']);
  $billpayment_commission = mysqli_real_escape_string($link, $_POST['billpay_comm']);
  $walletPaymentType = mysqli_real_escape_string($link, $_POST['walletPaymentType']);
  $walletPaymentCharges = mysqli_real_escape_string($link, $_POST['walletPaymentCharges']);
  $walletPaymentChargesCapped = mysqli_real_escape_string($link, $_POST['walletPaymentChargesCapped']);
  $smscharges = mysqli_real_escape_string($link, $_POST['smscharges']);
  $ninVerifCharge = mysqli_real_escape_string($link, $_POST['ninVerifCharge']);
  $bvnVerifCharge = mysqli_real_escape_string($link, $_POST['bvnVerifCharge']);
  //$esusuPayCharges = mysqli_real_escape_string($link, $_POST['esusupay_charges']);
  //$posCharges = mysqli_real_escape_string($link, $_POST['pos_charges']);  esusupay_charges = '$esusuPayCharges', pos_charges = '$posCharges',
  
  $insert = mysqli_query($link, "UPDATE maintenance_history SET cust_mfee='$cust_mfee', loan_booking = '$lbooking', tcharges_type = '$tcharges_type', t_charges = '$t_charges', capped_amt = '$capped_amt', status = '$status', billing_type = '$billtype', bvn_fee = '$bvn_fee', ussd_session_cost = '$ussd_session_cost', bank_transfer_charges = '$bank_transfer_charges', transferToCardCharges = '$card_transfer_charges', verveCardLinkingFee = '$vervecard_linkingfee', bank_transfer_commission = '$bank_transfer_commission', transferToCardCommission = '$card_transfer_commission', airtimeData_comm = '$airtimeData_commission', billpay_comm = '$billpayment_commission', walletPaymentType = '$walletPaymentType', walletPaymentCharges = '$walletPaymentCharges', walletPaymentChargesCapped = '$walletPaymentChargesCapped', smscharges = '$smscharges', ninVerificationCharges = '$ninVerifCharge', bvnVerificationCharges = '$bvnVerifCharge' WHERE company_id = '$insid'") or die ("Error: " . mysqli_error($link));
  if(!$insert)
  {
    echo "<div class='alert alert-info'>Error.....Please try again later</div>";
  }
  else{
    echo "<div class='alert alert-success'>Setup Updated Successfully!</div>";
    echo "<script>window.location='instprofile_settings.php?id=".$_SESSION['tid']."&&idm=".$_GET['idm']."&&mid=".base64_encode("419")."&&tab=tab_1'; </script>";
  }
}
?>

<div class="box-body">

<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Billing Type</label>
                  <div class="col-sm-10">
                  <select name="billtype" class="form-control select2" required style="width:100%">
           <option value="<?php echo $billing_type; ?>" selected><?php echo $billing_type; ?></option>
           <option value="PAYG">PAYG</option>
           <option value="Hybrid">Hybrid</option>
           <option value="PAYGException">PAYGException</option>
          </select>
                  </div>
                  </div>
                  
 <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Customer Charges</label>
                  <div class="col-sm-10">
                  <input name="cust_mfee" type="text" class="form-control" placeholder="Maintenance Fee per Customer Registered" value="<?php echo $cust_mfee; ?>" required>
                  </div>
                  </div>
                  
<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Loan Booking</label>
                  <div class="col-sm-10">
                  <input name="lbooking" type="text" class="form-control" placeholder="Maintenance Fee per Loan Booking" value="<?php echo $lbooking; ?>" required>
                  </div>
                  </div>
                  
<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">BVN Charges</label>
                  <div class="col-sm-10">
                  <input name="bvn_fee" type="text" class="form-control" placeholder="BVN Charges" value="<?php echo $mybvn_fee; ?>" required>
                  </div>
                  </div>
                  
<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">USSD Session Cost</label>
                  <div class="col-sm-10">
                  <input name="ussd_session_cost" type="text" class="form-control" placeholder="USSD Session Cost" value="<?php echo $myussd_scost; ?>" required>
                  </div>
                  </div>
                  
<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Bank Transfer Charges</label>
                  <div class="col-sm-10">
                  <input name="bank_transfer_charges" type="text" class="form-control" placeholder="Bank Transfer Charges" value="<?php echo $bank_transferCharges; ?>" required>
                  </div>
                  </div>

<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Transfer Commission</label>
                  <div class="col-sm-10">
                  <input name="bank_transfer_commission" type="text" class="form-control" placeholder="Bank Transfer Commission" value="<?php echo $bank_transferCommission; ?>" required>
                  </div>
                  </div>
                  
<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Transfer to Card Charges</label>
                  <div class="col-sm-10">
                  <input name="card_transfer_charges" type="text" class="form-control" placeholder="Transfer to Card Charges" value="<?php echo $card_transferCharges; ?>" required>
                  </div>
                  </div>

<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Transfer to Card Commission</label>
                  <div class="col-sm-10">
                  <input name="card_transfer_commission" type="text" class="form-control" placeholder="Transfer to Card Commission" value="<?php echo $card_transferCommission; ?>" required>
                  </div>
                  </div>
                  
<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Vervecard Linking Fee</label>
                  <div class="col-sm-10">
                  <input name="vervecard_linkingfee" type="text" class="form-control" placeholder="Vervecard Linking Fee" value="<?php echo $verveCard_LinkingFee; ?>" required>
                  </div>
                  </div>

<!--<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">esusuPAY Charges</label>
                  <div class="col-sm-10">
                  <input name="esusupay_charges" type="text" class="form-control" placeholder="esusuPAY Charges" value="<?php echo $esusupay_charges; ?>" required>
                  </div>
                  </div>

<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">POS Charges</label>
                  <div class="col-sm-10">
                  <input name="pos_charges" type="text" class="form-control" placeholder="POS Charges" value="<?php echo $pos_charges; ?>" required>
                  </div>
                  </div>-->

<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">SMS Charges</label>
                  <div class="col-sm-10">
                  <input name="smscharges" type="text" class="form-control" placeholder="SMS Charges" value="<?php echo $smsfee; ?>" required>
                  </div>
                  </div>
                 
<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Transaction Charges Type</label>
                  <div class="col-sm-10">
                  <select name="tcharges_type" class="form-control select2" required style="width:100%">
           <option value="<?php echo $tcharges_type; ?>" selected><?php echo $tcharges_type; ?></option>
           <option value="flat">flat</option>
           <option value="percentage">percentage</option>
          </select>
                  </div>
                  </div>

 <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Transactional Charges</label>
                  <div class="col-sm-10">
                  <input name="t_charges" type="text" class="form-control" placeholder="Transactional Charges" value="<?php echo $t_charges; ?>" required>
                  </div>
                  </div>

<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Capped Amount</label>
                  <div class="col-sm-10">
                  <input name="capped_amt" type="text" class="form-control" placeholder="Capped Amount for Transactional Charges" value="<?php echo $capped_amt; ?>" required>
                  </div>
                  </div>

<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Airtime/Data Commission</label>
                  <div class="col-sm-10">
                  <input name="airtimedata_comm" type="text" class="form-control" placeholder="Airtime/Data Commission" value="<?php echo $airtimeData_comm; ?>" required>
                  </div>
                  </div>

<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Billpayment Commission</label>
                  <div class="col-sm-10">
                  <input name="billpay_comm" type="text" class="form-control" placeholder="Billpayment Commission" value="<?php echo $billpay_comm; ?>" required>
                  </div>
                  </div>

<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">wallet Payment Type</label>
                  <div class="col-sm-10">
                  <select name="walletPaymentType" class="form-control select2" required style="width:100%">
                    <option value="<?php echo $walletPmtType; ?>" selected><?php echo $walletPmtType; ?></option>
                    <option value="Flat">Flat</option>
                    <option value="Percentage">Percentage</option>
                  </select>
                  </div>
                  </div>

<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Wallet Payment Charges</label>
                  <div class="col-sm-10">
                  <input name="walletPaymentCharges" type="text" class="form-control" placeholder="Wallet Payment Charges" value="<?php echo $walletPmtCharges; ?>" required>
                  </div>
                  </div>

<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Wallet Payment Charges (Capped)</label>
                  <div class="col-sm-10">
                  <input name="walletPaymentChargesCapped" type="text" class="form-control" placeholder="Wallet Payment Charges (Capped)" value="<?php echo $walletPmtChargesCapped; ?>" required>
                  </div>
                  </div>

<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">NIN Verification Charges</label>
                  <div class="col-sm-10">
                  <input name="ninVerifCharge" type="text" class="form-control" placeholder="NIN Verification Charges" value="<?php echo $ninVCharges; ?>" required>
                  </div>
                  </div>
                  
<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">BVN Verification Charges</label>
                  <div class="col-sm-10">
                  <input name="bvnVerifCharge" type="text" class="form-control" placeholder="BVN Verification Charges" value="<?php echo $bvnVCharges; ?>" required>
                  </div>
                  </div>

<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Status</label>
                  <div class="col-sm-10">
                  <select name="status" class="form-control select2" required style="width:100%">
           <option value="<?php echo $status; ?>" selected><?php echo $status; ?></option>
           <option value="NotActivated">NotActivated</option>
           <option value="Activated">Activated</option>
          </select>
                  </div>
                  </div>

</div>

<div align="right">
     <div class="box-footer">
        <button name="Update_charges" type="submit" class="btn bg-blue btn-flat"><i class="fa fa-save">&nbsp;Update Settings</i></button>

     </div>
</div>

 </form>
<?php
}
else{
?>

<form class="form-horizontal" method="post" enctype="multipart/form-data">

<?php
if(isset($_POST['Save_charges']))
{
  $insid = $_GET['idm'];
  $cust_mfee =  mysqli_real_escape_string($link, $_POST['cust_mfee']);
  $lbooking =  mysqli_real_escape_string($link, $_POST['lbooking']);
  $t_charges =  mysqli_real_escape_string($link, $_POST['t_charges']);
  $capped_amt =  mysqli_real_escape_string($link, $_POST['capped_amt']);
  $tcharges_type = mysqli_real_escape_string($link, $_POST['tcharges_type']);
  $billtype = mysqli_real_escape_string($link, $_POST['billtype']);
  $bvn_fee = mysqli_real_escape_string($link, $_POST['bvn_fee']);
  $ussd_session_cost = mysqli_real_escape_string($link, $_POST['ussd_session_cost']);
  $bank_transfer_charges = mysqli_real_escape_string($link, $_POST['bank_transfer_charges']);
  $bank_transfer_commission = mysqli_real_escape_string($link, $_POST['bank_transfer_commission']);
  $card_transfer_charges = mysqli_real_escape_string($link, $_POST['card_transfer_charges']);
  $card_transfer_commission = mysqli_real_escape_string($link, $_POST['card_transfer_commission']);
  $vervecard_linkingfee = mysqli_real_escape_string($link, $_POST['vervecard_linkingfee']);
  $airtimeData_commission = mysqli_real_escape_string($link, $_POST['airtimedata_comm']);
  $billpayment_commission = mysqli_real_escape_string($link, $_POST['billpay_comm']);
  $walletPaymentType = mysqli_real_escape_string($link, $_POST['walletPaymentType']);
  $walletPaymentCharges = mysqli_real_escape_string($link, $_POST['walletPaymentCharges']);
  $walletPaymentChargesCapped = mysqli_real_escape_string($link, $_POST['walletPaymentChargesCapped']);
  $smscharges = mysqli_real_escape_string($link, $_POST['smscharges']);
  $ninVerifCharge = mysqli_real_escape_string($link, $_POST['ninVerifCharge']);
  $bvnVerifCharge = mysqli_real_escape_string($link, $_POST['bvnVerifCharge']);
  //$esusuPayCharges = mysqli_real_escape_string($link, $_POST['esusupay_charges']);
  //$posCharges = mysqli_real_escape_string($link, $_POST['pos_charges']);

  $insert = mysqli_query($link, "INSERT INTO maintenance_history VALUES(null,'$insid','$cust_mfee','$lbooking','$tcharges_type','$t_charges','$capped_amt','Activated','$billtype','$bvn_fee','$ussd_session_cost','$bank_transfer_charges','$card_transfer_charges','$vervecard_linkingfee','$bank_transfer_commission','$card_transfer_commission','$airtimeData_commission','$billpayment_commission','$walletPaymentType','$walletPaymentCharges','$walletPaymentChargesCapped','$smscharges', '$ninVerifCharge', '$bvnVerifCharge')") or die ("Error: " . mysqli_error($link));
  if(!$insert)
  {
    echo "<div class='alert alert-info'>Error.....Please try again later</div>";
  }
  else{
    echo "<div class='alert alert-success'>Setup Saved Successfully!</div>";
    echo "<script>window.location='instprofile_settings.php?id=".$_SESSION['tid']."&&idm=".$_GET['idm']."&&mid=".base64_encode("419")."&&tab=tab_1'; </script>";
  }
}
?>

<div class="box-body">

  <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Billing Type</label>
                  <div class="col-sm-10">
                  <select name="billtype" class="form-control select2" required style="width:100%">
           <option value="" selected>Select Billing Type</option>
           <option value="PAYG">PAYG</option>
           <option value="Hybrid">Hybrid</option>
           <option value="PAYGException">PAYGException</option>
          </select>
                  </div>
                  </div>
                  
 <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Customer Charges</label>
                  <div class="col-sm-10">
                  <input name="cust_mfee" type="text" class="form-control" value="2.5" placeholder="Maintenance Fee per Customer Registered" required>
                  </div>
                  </div>
                  
<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Loan Booking</label>
                  <div class="col-sm-10">
                  <input name="lbooking" type="text" class="form-control" placeholder="Maintenance Fee per Loan Booking" required>
                  </div>
                  </div>
                  
<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">BVN Charges</label>
                  <div class="col-sm-10">
                  <input name="bvn_fee" type="text" class="form-control" placeholder="BVN Charges" required>
                  </div>
                  </div>
                  
<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">USSD Session Cost</label>
                  <div class="col-sm-10">
                  <input name="ussd_session_cost" type="text" class="form-control" placeholder="USSD Session Cost" required>
                  </div>
                  </div>
                  
<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Bank Transfer Charges</label>
                  <div class="col-sm-10">
                  <input name="bank_transfer_charges" type="text" class="form-control" placeholder="Bank Transfer Charges" required>
                  </div>
                  </div>

                  <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Transfer Commission</label>
                  <div class="col-sm-10">
                  <input name="bank_transfer_commission" type="text" class="form-control" placeholder="Bank Transfer Commission" required>
                  </div>
                  </div>
                  
<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Transfer to Card Charges</label>
                  <div class="col-sm-10">
                  <input name="card_transfer_charges" type="text" class="form-control" placeholder="Transfer to Card Charges" required>
                  </div>
                  </div>

<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Transfer to Card Commission</label>
                  <div class="col-sm-10">
                  <input name="card_transfer_commission" type="text" class="form-control" placeholder="Transfer to Card Commission" required>
                  </div>
                  </div>
                  
<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Vervecard Linking Fee</label>
                  <div class="col-sm-10">
                  <input name="vervecard_linkingfee" type="text" class="form-control" placeholder="Vervecard Linking Fee" required>
                  </div>
                  </div>

<!--<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">esusuPAY Charges</label>
                  <div class="col-sm-10">
                  <input name="esusupay_charges" type="text" class="form-control" placeholder="esusuPAY Charges" required>
                  </div>
                  </div>

 <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">POS Charges</label>
                  <div class="col-sm-10">
                  <input name="pos_charges" type="text" class="form-control" placeholder="POS Charges" required>
                  </div>
                  </div>-->

<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">SMS Charges</label>
                  <div class="col-sm-10">
                  <input name="smscharges" type="text" class="form-control" placeholder="SMS Charges" required>
                  </div>
                  </div>
                  
<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Transaction Charges Type</label>
                  <div class="col-sm-10">
                  <select name="tcharges_type" class="form-control select2" required style="width:100%">
           <option value="" selected>Select Transaction Charges Type</option>
           <option value="flat">flat</option>
           <option value="percentage">percentage</option>
          </select>
                  </div>
                  </div>

 <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Transactional Charges</label>
                  <div class="col-sm-10">
                  <input name="t_charges" type="text" class="form-control" placeholder="Transactional Charges" required>
                  </div>
                  </div>

<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Capped Amount</label>
                  <div class="col-sm-10">
                  <input name="capped_amt" type="text" class="form-control" placeholder="Capped Amount for Transactional Charges" required>
                  </div>
                  </div>

<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Airtime/Data Commission</label>
                  <div class="col-sm-10">
                  <input name="airtimedata_comm" type="text" class="form-control" placeholder="Airtime/Data Commission" required>
                  </div>
                  </div>

<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Billpayment Commission</label>
                  <div class="col-sm-10">
                  <input name="billpay_comm" type="text" class="form-control" placeholder="Billpayment Commission" required>
                  </div>
                  </div>

<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">wallet Payment Type</label>
                  <div class="col-sm-10">
                  <select name="walletPaymentType" class="form-control select2" required style="width:100%">
                    <option value="" selected>Select Wallet Payment Type</option>
                    <option value="Flat">Flat</option>
                    <option value="Percentage">Percentage</option>
                  </select>
                  </div>
                  </div>

<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Wallet Payment Charges</label>
                  <div class="col-sm-10">
                  <input name="walletPaymentCharges" type="text" class="form-control" placeholder="Wallet Payment Charges" required>
                  </div>
                  </div>

<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Wallet Payment Charges (Capped)</label>
                  <div class="col-sm-10">
                  <input name="walletPaymentChargesCapped" type="text" class="form-control" placeholder="Wallet Payment Charges (Capped)" required>
                  </div>
                  </div>

<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">NIN Verification Charges</label>
                  <div class="col-sm-10">
                  <input name="ninVerifCharge" type="text" class="form-control" placeholder="NIN Verification Charges" required>
                  </div>
                  </div>
                  
<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">BVN Verification Charges</label>
                  <div class="col-sm-10">
                  <input name="bvnVerifCharge" type="text" class="form-control" placeholder="BVN Verification Charges" required>
                  </div>
                  </div>

</div>

<div align="right">
     <div class="box-footer">
        <button name="Save_charges" type="submit" class="btn bg-blue btn-flat"><i class="fa fa-save">&nbsp;Save Settings</i></button>

     </div>
</div>

 </form>

<?php
}
?>
       
              </div>
              <!-- /.tab-pane -->
  <?php
  }
  elseif($tab == 'tab_2')
  {
  ?>
              <div class="tab-pane <?php echo ($_GET['tab'] == 'tab_2') ? 'active' : ''; ?>" id="tab_2">

<?php
if(isset($_POST['Update_bill']))
{
  $insid = $_GET['idm'];
  $sno =  mysqli_real_escape_string($link, $_POST['sno']);
  $evn =  mysqli_real_escape_string($link, $_POST['evn']);
  $bvn =  mysqli_real_escape_string($link, $_POST['bvn']);
  $date_time = date("Y-m-d h:i:s");

  $insert = mysqli_query($link, "UPDATE label_settings SET sno = '$sno', evn = '$evn', bvn = '$bvn', dateUpdated = '$date_time' WHERE companyid = '$insid'") or die ("Error: " . mysqli_error($link));
  if(!$insert)
  {
    echo "<div class='alert alert-info'>Error.....Please try again later</div>";
  }
  else{
    echo "<div class='alert alert-success'>Setup Updated Successfully!</div>";
  }
}
?>
             <div class="box-body">

<?php
$insid = $_GET['idm'];
$search_others = mysqli_query($link, "SELECT * FROM label_settings WHERE companyid='$insid'");
if(mysqli_num_rows($search_others) == 1){
$row = mysqli_fetch_assoc($search_others);
?>
             <form class="form-horizontal" method="post" enctype="multipart/form-data">

             <div class="box-body">
            
      <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Serial No.</label>
                  <div class="col-sm-10">
                  <input name="sno" type="text" class="form-control" placeholder="Change Label" value="<?php echo $row['sno']; ?>">
                  </div>
                  </div>

      <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">EVN</label>
                  <div class="col-sm-10">
                  <input name="evn" type="text" class="form-control" placeholder="Change Label" value="<?php echo $row['evn']; ?>">
                  </div>
                  </div>

      <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">BVN</label>
                  <div class="col-sm-10">
                  <input name="bvn" type="text" class="form-control" placeholder="Change Label" value="<?php echo $row['bvn']; ?>">
                  </div>
                  </div>
      
       </div>
       
        <div align="right">
              <div class="box-footer">
                        <button name="Update_label" type="submit" class="btn bg-blue btn-flat"><i class="fa fa-save">&nbsp;Update</i></button>
              </div>
        </div>
        
       </form> 
<?php
}
else{
?>
      <form class="form-horizontal" method="post" enctype="multipart/form-data">
<?php
if(isset($_POST['Save_bill']))
{
  $insid = $_GET['idm'];
  $sno =  mysqli_real_escape_string($link, $_POST['sno']);
  $evn =  mysqli_real_escape_string($link, $_POST['evn']);
  $bvn =  mysqli_real_escape_string($link, $_POST['bvn']);
  $date_time = date("Y-m-d h:i:s");

  $insert = mysqli_query($link, "INSERT INTO label_settings VALUES(null,'$insid','$sno','$evn','$bvn','$date_time','$date_time')") or die ("Error: " . mysqli_error($link));
  if(!$insert)
  {
    echo "<div class='alert alert-info'>Error.....Please try again later</div>";
  }
  else{
    echo "<div class='alert alert-success'>Setup Saved Successfully!</div>";
  }
}
?>
             <div class="box-body">
            
      <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Serial No.</label>
                  <div class="col-sm-10">
                  <input name="sno" type="text" class="form-control" placeholder="Change Label">
                  </div>
                  </div>

      <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">EVN</label>
                  <div class="col-sm-10">
                  <input name="evn" type="text" class="form-control" placeholder="Change Label">
                  </div>
                  </div>

      <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">BVN</label>
                  <div class="col-sm-10">
                  <input name="bvn" type="text" class="form-control" placeholder="Change Label">
                  </div>
                  </div>
      
       </div>
       
       <div align="right">
              <div class="box-footer">
                        <button name="Save_bill" type="submit" class="btn bg-blue btn-flat"><i class="fa fa-save">&nbsp;Save</i></button>

              </div>
        </div>

       </form>
<?php
}
?>

<?php 
}
elseif($tab == 'tab_3')
{   
    $searchlast_code = mysqli_query($link, "SELECT dedicated_ussd_prefix FROM member_settings WHERE dedicated_ussd_prefix != '' ORDER BY id DESC");
    $fetchlast_code = mysqli_fetch_array($searchlast_code);
    $lastussdcode_prefix = ($fetchlast_code['dedicated_ussd_prefix'] == "") ? 0 : $fetchlast_code['dedicated_ussd_prefix'];
    
    $searchlast_code1 = mysqli_query($link, "SELECT dedicated_ledgerAcctNo_prefix FROM member_settings WHERE dedicated_ledgerAcctNo_prefix != '' ORDER BY id DESC");
    $fetchlast_code1 = mysqli_fetch_array($searchlast_code1);
    $lastledgerAcctNo_prefix = ($fetchlast_code1['dedicated_ledgerAcctNo_prefix'] == "") ? "00" : $fetchlast_code1['dedicated_ledgerAcctNo_prefix'];

?>
              <div class="tab-pane <?php echo ($_GET['tab'] == 'tab_3') ? 'active' : ''; ?>" id="tab_3">

                <?php
                if(isset($_POST['update'])){

                    $id= mysqli_real_escape_string($link, $_POST['companyid']);
                    $cname = mysqli_real_escape_string($link, $_POST['cname']);
                    $senderid = mysqli_real_escape_string($link, $_POST['senderid']);
                    $ussd_status = mysqli_real_escape_string($link, $_POST['ussd_status']);
                    $dedicated_ussd_prefix = mysqli_real_escape_string($link, $_POST['dedicated_ussd_prefix']);
                                  
                    //MODULE SETTINGS
                    $branch_manager = mysqli_real_escape_string($link, $_POST['branch_manager']);
                    $dept_settings = mysqli_real_escape_string($link, $_POST['dept_settings']);
                    $permission_manager = mysqli_real_escape_string($link, $_POST['permission_manager']);
                    $subagent_manager = mysqli_real_escape_string($link, $_POST['subagent_manager']);
                    $staff_manager = mysqli_real_escape_string($link, $_POST['staff_manager']);
                    $vendor_manager = mysqli_real_escape_string($link, $_POST['vendor_manager']);
                    $customer_manager = mysqli_real_escape_string($link, $_POST['customer_manager']);
                    $wallet_manager = mysqli_real_escape_string($link, $_POST['wallet_manager']);
                    $card_issuance_manager = mysqli_real_escape_string($link, $_POST['card_issuance_manager']);
                    $loan_manager = mysqli_real_escape_string($link, $_POST['loan_manager']);
                    $investment_manager = mysqli_real_escape_string($link, $_POST['investment_manager']);
                    $teller_manager = mysqli_real_escape_string($link, $_POST['teller_manager']);
                    $charges_manager = mysqli_real_escape_string($link, $_POST['charges_manager']);
                    $savings_account = mysqli_real_escape_string($link, $_POST['savings_account']);
                    $reports_module = mysqli_real_escape_string($link, $_POST['reports_module']);
                    $payroll_module = mysqli_real_escape_string($link, $_POST['payroll_module']);
                    $income_module = mysqli_real_escape_string($link, $_POST['income_module']);
                    $expenses_module = mysqli_real_escape_string($link, $_POST['expenses_module']);
                    $general_settings = mysqli_real_escape_string($link, $_POST['general_settings']);
                    $subagent_wallet = mysqli_real_escape_string($link, $_POST['subagent_wallet']);
                    $otp_option = mysqli_real_escape_string($link, $_POST['otp_option']);
                                  
                    $currency = mysqli_real_escape_string($link, $_POST['currency']);
                    $theme_color = mysqli_real_escape_string($link, $_POST['theme_color']);
                    $alternate_color = mysqli_real_escape_string($link, $_POST['alternate_color']);
                    $login_background = mysqli_real_escape_string($link, $_POST['login_background']);
                    $login_bottoncolor = mysqli_real_escape_string($link, $_POST['login_bottoncolor']);
                    $tlimit = mysqli_real_escape_string($link, $_POST['tlimit']);
                                  
                    $remita_merchantid = mysqli_real_escape_string($link, $_POST['remita_merchantid']);
                    $remita_apikey = mysqli_real_escape_string($link, $_POST['remita_apikey']);
                    $remita_serviceid = mysqli_real_escape_string($link, $_POST['remita_serviceid']);
                    $remita_apitoken = mysqli_real_escape_string($link, $_POST['remita_apitoken']);

                    $mobileapp_link = mysqli_real_escape_string($link, $_POST['mobileapp_link']);
                    $tsavings_subacct = mysqli_real_escape_string($link, $_POST['tsavings_subacct']);
                    $ts_roi_type = mysqli_real_escape_string($link, $_POST['ts_roi_type']);
                    $ts_roi = mysqli_real_escape_string($link, $_POST['ts_roi']);

                    $product_manager = mysqli_real_escape_string($link, $_POST['product_manager']);
                    $editoption = mysqli_real_escape_string($link, $_POST['editoption']);
                    $takafulmenu = mysqli_real_escape_string($link, $_POST['takafulmenu']);
                    $healthmenu = mysqli_real_escape_string($link, $_POST['healthmenu']);
                    $groupcontribution = mysqli_real_escape_string($link, $_POST['groupcontribution']);
                    $pos_manager = mysqli_real_escape_string($link, $_POST['pos_manager']);
                    $nip_route = mysqli_real_escape_string($link, $_POST['nip_route']);
                    $invite_manager = mysqli_real_escape_string($link, $_POST['invite_manager']);
                    $halalpay_module = mysqli_real_escape_string($link, $_POST['halalpay_module']);
                    $wallet_creation = mysqli_real_escape_string($link, $_POST['wallet_creation']);
                    $bvn_route = mysqli_real_escape_string($link, $_POST['bvn_route']);
                    $account_manager = mysqli_real_escape_string($link, $_POST['account_manager']);
                    $bvn_manager = mysqli_real_escape_string($link, $_POST['bvn_manager']);
                    $sms_checker = mysqli_real_escape_string($link, $_POST['sms_checker']);
                    $va_provider = implode(',', $_POST['va_provider']);
                    $defaultAcct = implode(',', $_POST['defaultAcct']);
                    $pending_manager = mysqli_real_escape_string($link, $_POST['pending_manager']);
                    $pool_account = mysqli_real_escape_string($link, $_POST['pool_account']);
                    $airtime = mysqli_real_escape_string($link, $_POST['airtime']);
                    $databundle = mysqli_real_escape_string($link, $_POST['databundle']);
                    $billpayment = mysqli_real_escape_string($link, $_POST['billpayment']);
                    $va_fortill = mysqli_real_escape_string($link, $_POST['va_fortill']);
                    $cardless_wroute = mysqli_real_escape_string($link, $_POST['cardless_wroute']);
                    $dedicated_sms_gateway = mysqli_real_escape_string($link, $_POST['dedicated_sms_gateway']);
                    $newLedgerAcctNo_prefix = mysqli_real_escape_string($link, $_POST['lastledgerAcctNo_prefix']);
                    $donation_manager = mysqli_real_escape_string($link, $_POST['donation_manager']);
                    $copyright = mysqli_real_escape_string($link, $_POST['copyright']);
                    $upfront_payment = mysqli_real_escape_string($link, $_POST['upfront_payment']);
                    $enable_bvn = mysqli_real_escape_string($link, $_POST['enable_bvn']);
                    $enable_acct_verification = mysqli_real_escape_string($link, $_POST['enable_acct_verification']);
                    $cardtokenization_subacct = mysqli_real_escape_string($link, $_POST['cardtokenization_subacct']);
                    $allow_login_otp = mysqli_real_escape_string($link, $_POST['allow_login_otp']);
                    $merchantWalletID = mysqli_real_escape_string($link, $_POST['merchantWalletID']);
                    $verification_manager = mysqli_real_escape_string($link, $_POST['verification_manager']);
                    $idVTyp = implode(',', $_POST['idVType']);
                    $enrolment_manager = mysqli_real_escape_string($link, $_POST['enrolment_manager']);
                                      
                    //image
                    $image = addslashes(file_get_contents($_FILES['image']['tmp_name']));
                    $image3 = addslashes(file_get_contents($_FILES['image3']['tmp_name']));

                    $target_dir = "../img/";
                    $target_dir3 = "../img/";

                    $target_file = $target_dir.basename($_FILES["image"]["name"]);
                    $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);

                    $target_file3 = $target_dir3.basename($_FILES["image3"]["name"]);
                    $imageFileType3 = pathinfo($target_file3,PATHINFO_EXTENSION);

                    $check = getimagesize($_FILES["image"]["tmp_name"]);
                    $check3 = getimagesize($_FILES["image3"]["tmp_name"]);
                  
                    $sourcepath = $_FILES["image"]["tmp_name"];
                    $targetpath = "../img/" . $_FILES["image"]["name"];
                    move_uploaded_file($sourcepath,$targetpath);

                    $sourcepath3 = $_FILES["image3"]["tmp_name"];
                    $targetpath3 = "../img/" . $_FILES["image3"]["name"];
                    move_uploaded_file($sourcepath3,$targetpath3);
                    
                    $location = $_FILES['image']['name'];  

                    $lbackg = $_FILES["image3"]["name"]; 

                    mysqli_query($link, "UPDATE borrowers SET dedicated_ussd_prefix = '$dedicated_ussd_prefix' WHERE branchid = '$id'");
                    mysqli_query($link, "UPDATE user SET dedicated_ussd_prefix = '$dedicated_ussd_prefix' WHERE created_by = '$id'");
                    mysqli_query($link, "UPDATE loan_product SET dedicated_ussd_prefix = '$dedicated_ussd_prefix' WHERE merchantid = '$id'");

                    if($image == "" && $image3 == ""){
                        ($ussd_status == "Active") ? mysqli_query($link,"UPDATE member_settings SET cname='$cname',sender_id='$senderid',currency='$currency',dedicated_ussd_prefix='$dedicated_ussd_prefix',ussd_status='$ussd_status',theme_color='$theme_color',alternate_color='$alternate_color',login_background='$login_background',login_bottoncolor='$login_bottoncolor',tlimit='$tlimit',dept_settings='$dept_settings',subagent_wallet='$subagent_wallet',remitaMerchantId='$remita_merchantid',remitaApiKey='$remita_apikey',remitaServiceId='$remita_serviceid',remitaApiToken='$remita_apitoken',branch_manager='$branch_manager',permission_manager='$permission_manager',subagent_manager='$subagent_manager',staff_manager='$staff_manager',vendor_manager='$vendor_manager',customer_manager='$customer_manager',wallet_manager='$wallet_manager',card_issuance_manager='$card_issuance_manager',loan_manager='$loan_manager',investment_manager='$investment_manager',teller_manager='$teller_manager',charges_manager='$charges_manager',savings_account='$savings_account',reports_module='$reports_module',payroll_module='$payroll_module',income_module='$income_module',expenses_module='$expenses_module',general_settings='$general_settings',otp_option='$otp_option',mobileapp_link='$mobileapp_link',tsavings_subacct='$tsavings_subacct',ts_roi_type='$ts_roi_type',ts_roi='$ts_roi',product_manager='$product_manager',editoption='$editoption',takafulmenu='$takafulmenu',healthmenu='$healthmenu',groupcontribution='$groupcontribution',pos_manager='$pos_manager',nip_route='$nip_route',invite_manager='$invite_manager',halalpay_module='$halalpay_module',wallet_creation='$wallet_creation',bvn_route='$bvn_route',account_manager='$account_manager',bvn_manager='$bvn_manager',sms_checker='$sms_checker',va_provider='$va_provider',defaultAcct='$defaultAcct',pending_manager='$pending_manager',pool_account='$pool_account',airtime='$airtime',databundle='$databundle',billpayment='$billpayment',va_fortill='$va_fortill',cardless_wroute='$cardless_wroute',dedicated_sms_gateway='$dedicated_sms_gateway',dedicated_ledgerAcctNo_prefix='$newLedgerAcctNo_prefix',donation_manager='$donation_manager',copyright='$copyright',upfront_payment='$upfront_payment',enable_bvn='$enable_bvn',enable_acct_verification='$enable_acct_verification',cardtokenization_subacct='$cardtokenization_subacct',allow_login_otp='$allow_login_otp',merchantWalletID='$merchantWalletID',verification_manager='$verification_manager',idVType='$idVTyp',enrolment_manager='$enrolment_manager' WHERE companyid ='$id'") or die("Error:" . mysqli_error($link)) : "";
                        ($ussd_status == "NotActive") ? mysqli_query($link,"UPDATE member_settings SET cname='$cname',sender_id='$senderid',currency='$currency',ussd_status='$ussd_status',theme_color='$theme_color',alternate_color='$alternate_color',login_background='$login_background',login_bottoncolor='$login_bottoncolor',tlimit='$tlimit',dept_settings='$dept_settings',subagent_wallet='$subagent_wallet',remitaMerchantId='$remita_merchantid',remitaApiKey='$remita_apikey',remitaServiceId='$remita_serviceid',remitaApiToken='$remita_apitoken',branch_manager='$branch_manager',permission_manager='$permission_manager',subagent_manager='$subagent_manager',staff_manager='$staff_manager',vendor_manager='$vendor_manager',customer_manager='$customer_manager',wallet_manager='$wallet_manager',card_issuance_manager='$card_issuance_manager',loan_manager='$loan_manager',investment_manager='$investment_manager',teller_manager='$teller_manager',charges_manager='$charges_manager',savings_account='$savings_account',reports_module='$reports_module',payroll_module='$payroll_module',income_module='$income_module',expenses_module='$expenses_module',general_settings='$general_settings',otp_option='$otp_option',mobileapp_link='$mobileapp_link',tsavings_subacct='$tsavings_subacct',ts_roi_type='$ts_roi_type',ts_roi='$ts_roi',product_manager='$product_manager',editoption='$editoption',takafulmenu='$takafulmenu',healthmenu='$healthmenu',groupcontribution='$groupcontribution',pos_manager='$pos_manager',nip_route='$nip_route',invite_manager='$invite_manager',halalpay_module='$halalpay_module',wallet_creation='$wallet_creation',bvn_route='$bvn_route',account_manager='$account_manager',bvn_manager='$bvn_manager',sms_checker='$sms_checker',va_provider='$va_provider',defaultAcct='$defaultAcct',pending_manager='$pending_manager',pool_account='$pool_account',airtime='$airtime',databundle='$databundle',billpayment='$billpayment',va_fortill='$va_fortill',cardless_wroute='$cardless_wroute',dedicated_sms_gateway='$dedicated_sms_gateway',dedicated_ledgerAcctNo_prefix='$newLedgerAcctNo_prefix',donation_manager='$donation_manager',copyright='$copyright',upfront_payment='$upfront_payment',enable_bvn='$enable_bvn',enable_acct_verification='$enable_acct_verification',cardtokenization_subacct='$cardtokenization_subacct',allow_login_otp='$allow_login_otp',merchantWalletID='$merchantWalletID',verification_manager='$verification_manager',idVType='$idVTyp',enrolment_manager='$enrolment_manager' WHERE companyid ='$id'") or die("Error:" . mysqli_error($link)) : "";
                        echo "<script>alert('Data Updated Successfully!'); </script>";
                    }elseif($image != "" && $image3 == ""){
                        ($ussd_status == "Active") ? mysqli_query($link,"UPDATE member_settings SET cname='$cname',logo='$location',sender_id='$senderid',currency='$currency',dedicated_ussd_prefix='$dedicated_ussd_prefix',ussd_status='$ussd_status',theme_color='$theme_color',alternate_color='$alternate_color',login_background='$login_background',login_bottoncolor='$login_bottoncolor',tlimit='$tlimit',dept_settings='$dept_settings',subagent_wallet='$subagent_wallet',remitaMerchantId='$remita_merchantid',remitaApiKey='$remita_apikey',remitaServiceId='$remita_serviceid',remitaApiToken='$remita_apitoken',branch_manager='$branch_manager',permission_manager='$permission_manager',subagent_manager='$subagent_manager',staff_manager='$staff_manager',vendor_manager='$vendor_manager',customer_manager='$customer_manager',wallet_manager='$wallet_manager',card_issuance_manager='$card_issuance_manager',loan_manager='$loan_manager',investment_manager='$investment_manager',teller_manager='$teller_manager',charges_manager='$charges_manager',savings_account='$savings_account',reports_module='$reports_module',payroll_module='$payroll_module',income_module='$income_module',expenses_module='$expenses_module',general_settings='$general_settings',otp_option='$otp_option',mobileapp_link='$mobileapp_link',tsavings_subacct='$tsavings_subacct',ts_roi_type='$ts_roi_type',ts_roi='$ts_roi',product_manager='$product_manager',editoption='$editoption',takafulmenu='$takafulmenu',healthmenu='$healthmenu',groupcontribution='$groupcontribution',pos_manager='$pos_manager',nip_route='$nip_route',invite_manager='$invite_manager',halalpay_module='$halalpay_module',wallet_creation='$wallet_creation',bvn_route='$bvn_route',account_manager='$account_manager',bvn_manager='$bvn_manager',sms_checker='$sms_checker',va_provider='$va_provider',defaultAcct='$defaultAcct',pending_manager='$pending_manager',pool_account='$pool_account',airtime='$airtime',databundle='$databundle',billpayment='$billpayment',va_fortill='$va_fortill',cardless_wroute='$cardless_wroute',dedicated_sms_gateway='$dedicated_sms_gateway',dedicated_ledgerAcctNo_prefix='$newLedgerAcctNo_prefix',donation_manager='$donation_manager',copyright='$copyright',upfront_payment='$upfront_payment',enable_bvn='$enable_bvn',enable_acct_verification='$enable_acct_verification',cardtokenization_subacct='$cardtokenization_subacct',allow_login_otp='$allow_login_otp',merchantWalletID='$merchantWalletID',verification_manager='$verification_manager',idVType='$idVTyp',enrolment_manager='$enrolment_manager' WHERE companyid ='$id'")or die("Error:" . mysqli_error($link)) : "";
                        ($ussd_status == "NotActive") ? mysqli_query($link,"UPDATE member_settings SET cname='$cname',logo='$location',sender_id='$senderid',currency='$currency',ussd_status='$ussd_status',theme_color='$theme_color',alternate_color='$alternate_color',login_background='$login_background',login_bottoncolor='$login_bottoncolor',tlimit='$tlimit',dept_settings='$dept_settings',subagent_wallet='$subagent_wallet',remitaMerchantId='$remita_merchantid',remitaApiKey='$remita_apikey',remitaServiceId='$remita_serviceid',remitaApiToken='$remita_apitoken',branch_manager='$branch_manager',permission_manager='$permission_manager',subagent_manager='$subagent_manager',staff_manager='$staff_manager',vendor_manager='$vendor_manager',customer_manager='$customer_manager',wallet_manager='$wallet_manager',card_issuance_manager='$card_issuance_manager',loan_manager='$loan_manager',investment_manager='$investment_manager',teller_manager='$teller_manager',charges_manager='$charges_manager',savings_account='$savings_account',reports_module='$reports_module',payroll_module='$payroll_module',income_module='$income_module',expenses_module='$expenses_module',general_settings='$general_settings',otp_option='$otp_option',mobileapp_link='$mobileapp_link',tsavings_subacct='$tsavings_subacct',ts_roi_type='$ts_roi_type',ts_roi='$ts_roi',product_manager='$product_manager',editoption='$editoption',takafulmenu='$takafulmenu',healthmenu='$healthmenu',groupcontribution='$groupcontribution',pos_manager='$pos_manager',nip_route='$nip_route',invite_manager='$invite_manager',halalpay_module='$halalpay_module',wallet_creation='$wallet_creation',bvn_route='$bvn_route',account_manager='$account_manager',bvn_manager='$bvn_manager',sms_checker='$sms_checker',va_provider='$va_provider',defaultAcct='$defaultAcct',pending_manager='$pending_manager',pool_account='$pool_account',airtime='$airtime',databundle='$databundle',billpayment='$billpayment',va_fortill='$va_fortill',cardless_wroute='$cardless_wroute',dedicated_sms_gateway='$dedicated_sms_gateway',dedicated_ledgerAcctNo_prefix='$newLedgerAcctNo_prefix',donation_manager='$donation_manager',copyright='$copyright',upfront_payment='$upfront_payment',enable_bvn='$enable_bvn',enable_acct_verification='$enable_acct_verification',cardtokenization_subacct='$cardtokenization_subacct',allow_login_otp='$allow_login_otp',merchantWalletID='$merchantWalletID',verification_manager='$verification_manager',idVType='$idVTyp',enrolment_manager='$enrolment_manager' WHERE companyid ='$id'") or die("Error:" . mysqli_error($link)) : "";
                        echo "<script>alert('Data Updated Successfully!'); </script>";
                    }elseif($image == "" && $image3 != ""){
                        ($ussd_status == "Active") ? mysqli_query($link,"UPDATE member_settings SET cname='$cname',frontpg_backgrd='$lbackg',sender_id='$senderid',currency='$currency',dedicated_ussd_prefix='$dedicated_ussd_prefix',ussd_status='$ussd_status',theme_color='$theme_color',alternate_color='$alternate_color',login_background='$login_background',login_bottoncolor='$login_bottoncolor',tlimit='$tlimit',dept_settings='$dept_settings',subagent_wallet='$subagent_wallet',remitaMerchantId='$remita_merchantid',remitaApiKey='$remita_apikey',remitaServiceId='$remita_serviceid',remitaApiToken='$remita_apitoken',branch_manager='$branch_manager',permission_manager='$permission_manager',subagent_manager='$subagent_manager',staff_manager='$staff_manager',vendor_manager='$vendor_manager',customer_manager='$customer_manager',wallet_manager='$wallet_manager',card_issuance_manager='$card_issuance_manager',loan_manager='$loan_manager',investment_manager='$investment_manager',teller_manager='$teller_manager',charges_manager='$charges_manager',savings_account='$savings_account',reports_module='$reports_module',payroll_module='$payroll_module',income_module='$income_module',expenses_module='$expenses_module',general_settings='$general_settings',otp_option='$otp_option',mobileapp_link='$mobileapp_link',tsavings_subacct='$tsavings_subacct',ts_roi_type='$ts_roi_type',ts_roi='$ts_roi',product_manager='$product_manager',editoption='$editoption',takafulmenu='$takafulmenu',healthmenu='$healthmenu',groupcontribution='$groupcontribution',pos_manager='$pos_manager',nip_route='$nip_route',invite_manager='$invite_manager',halalpay_module='$halalpay_module',wallet_creation='$wallet_creation',bvn_route='$bvn_route',account_manager='$account_manager',bvn_manager='$bvn_manager',sms_checker='$sms_checker',va_provider='$va_provider',defaultAcct='$defaultAcct',pending_manager='$pending_manager',pool_account='$pool_account',airtime='$airtime',databundle='$databundle',billpayment='$billpayment',va_fortill='$va_fortill',cardless_wroute='$cardless_wroute',dedicated_sms_gateway='$dedicated_sms_gateway',dedicated_ledgerAcctNo_prefix='$newLedgerAcctNo_prefix',donation_manager='$donation_manager',copyright='$copyright',upfront_payment='$upfront_payment',enable_bvn='$enable_bvn',enable_acct_verification='$enable_acct_verification',cardtokenization_subacct='$cardtokenization_subacct',allow_login_otp='$allow_login_otp',merchantWalletID='$merchantWalletID',verification_manager='$verification_manager',idVType='$idVTyp',enrolment_manager='$enrolment_manager' WHERE companyid ='$id'") or die("Error:" . mysqli_error($link)) : "";
                        ($ussd_status == "NotActive") ? mysqli_query($link,"UPDATE member_settings SET cname='$cname',frontpg_backgrd='$lbackg',sender_id='$senderid',currency='$currency',ussd_status='$ussd_status',theme_color='$theme_color',alternate_color='$alternate_color',login_background='$login_background',login_bottoncolor='$login_bottoncolor',tlimit='$tlimit',dept_settings='$dept_settings',subagent_wallet='$subagent_wallet',remitaMerchantId='$remita_merchantid',remitaApiKey='$remita_apikey',remitaServiceId='$remita_serviceid',remitaApiToken='$remita_apitoken',branch_manager='$branch_manager',permission_manager='$permission_manager',subagent_manager='$subagent_manager',staff_manager='$staff_manager',vendor_manager='$vendor_manager',customer_manager='$customer_manager',wallet_manager='$wallet_manager',card_issuance_manager='$card_issuance_manager',loan_manager='$loan_manager',investment_manager='$investment_manager',teller_manager='$teller_manager',charges_manager='$charges_manager',savings_account='$savings_account',reports_module='$reports_module',payroll_module='$payroll_module',income_module='$income_module',expenses_module='$expenses_module',general_settings='$general_settings',otp_option='$otp_option',mobileapp_link='$mobileapp_link',tsavings_subacct='$tsavings_subacct',ts_roi_type='$ts_roi_type',ts_roi='$ts_roi',product_manager='$product_manager',editoption='$editoption',takafulmenu='$takafulmenu',healthmenu='$healthmenu',groupcontribution='$groupcontribution',pos_manager='$pos_manager',nip_route='$nip_route',invite_manager='$invite_manager',halalpay_module='$halalpay_module',wallet_creation='$wallet_creation',bvn_route='$bvn_route',account_manager='$account_manager',bvn_manager='$bvn_manager',sms_checker='$sms_checker',va_provider='$va_provider',defaultAcct='$defaultAcct',pending_manager='$pending_manager',pool_account='$pool_account',airtime='$airtime',databundle='$databundle',billpayment='$billpayment',va_fortill='$va_fortill',cardless_wroute='$cardless_wroute',dedicated_sms_gateway='$dedicated_sms_gateway',dedicated_ledgerAcctNo_prefix='$newLedgerAcctNo_prefix',donation_manager='$donation_manager',copyright='$copyright',upfront_payment='$upfront_payment',enable_bvn='$enable_bvn',enable_acct_verification='$enable_acct_verification',cardtokenization_subacct='$cardtokenization_subacct',allow_login_otp='$allow_login_otp',merchantWalletID='$merchantWalletID',verification_manager='$verification_manager',idVType='$idVTyp',enrolment_manager='$enrolment_manager' WHERE companyid ='$id'") or die("Error:" . mysqli_error($link)) : "";
                        echo "<script>alert('Data Updated Successfully!'); </script>";
                    }elseif($image != "" && $image3 != ""){
                        ($ussd_status == "Active") ? mysqli_query($link,"UPDATE member_settings SET cname='$cname',logo='$location',frontpg_backgrd='$lbackg',sender_id='$senderid',currency='$currency',dedicated_ussd_prefix='$dedicated_ussd_prefix',ussd_status='$ussd_status',theme_color='$theme_color',alternate_color='$alternate_color',login_background='$login_background',login_bottoncolor='$login_bottoncolor',tlimit='$tlimit',dept_settings='$dept_settings',subagent_wallet='$subagent_wallet',remitaMerchantId='$remita_merchantid',remitaApiKey='$remita_apikey',remitaServiceId='$remita_serviceid',remitaApiToken='$remita_apitoken',branch_manager='$branch_manager',permission_manager='$permission_manager',subagent_manager='$subagent_manager',staff_manager='$staff_manager',vendor_manager='$vendor_manager',customer_manager='$customer_manager',wallet_manager='$wallet_manager',card_issuance_manager='$card_issuance_manager',loan_manager='$loan_manager',investment_manager='$investment_manager',teller_manager='$teller_manager',charges_manager='$charges_manager',savings_account='$savings_account',reports_module='$reports_module',payroll_module='$payroll_module',income_module='$income_module',expenses_module='$expenses_module',general_settings='$general_settings',otp_option='$otp_option',mobileapp_link='$mobileapp_link',tsavings_subacct='$tsavings_subacct',ts_roi_type='$ts_roi_type',ts_roi='$ts_roi',product_manager='$product_manager',editoption='$editoption',takafulmenu='$takafulmenu',healthmenu='$healthmenu',groupcontribution='$groupcontribution',pos_manager='$pos_manager',nip_route='$nip_route',invite_manager='$invite_manager',halalpay_module='$halalpay_module',wallet_creation='$wallet_creation',bvn_route='$bvn_route',account_manager='$account_manager',bvn_manager='$bvn_manager',sms_checker='$sms_checker',va_provider='$va_provider',defaultAcct='$defaultAcct',pending_manager='$pending_manager',pool_account='$pool_account',airtime='$airtime',databundle='$databundle',billpayment='$billpayment',va_fortill='$va_fortill',dedicated_sms_gateway='$dedicated_sms_gateway',dedicated_ledgerAcctNo_prefix='$newLedgerAcctNo_prefix',donation_manager='$donation_manager',copyright='$copyright',upfront_payment='$upfront_payment',enable_bvn='$enable_bvn',enable_acct_verification='$enable_acct_verification',cardtokenization_subacct='$cardtokenization_subacct',allow_login_otp='$allow_login_otp',merchantWalletID='$merchantWalletID',verification_manager='$verification_manager',idVType='$idVTyp',enrolment_manager='$enrolment_manager' WHERE companyid ='$id'") or die("Error:" . mysqli_error($link)) : "";
                        ($ussd_status == "NotActive") ? mysqli_query($link,"UPDATE member_settings SET cname='$cname',logo='$location',frontpg_backgrd='$lbackg',sender_id='$senderid',currency='$currency',ussd_status='$ussd_status',theme_color='$theme_color',alternate_color='$alternate_color',login_background='$login_background',login_bottoncolor='$login_bottoncolor',tlimit='$tlimit',dept_settings='$dept_settings',subagent_wallet='$subagent_wallet',remitaMerchantId='$remita_merchantid',remitaApiKey='$remita_apikey',remitaServiceId='$remita_serviceid',remitaApiToken='$remita_apitoken',branch_manager='$branch_manager',permission_manager='$permission_manager',subagent_manager='$subagent_manager',staff_manager='$staff_manager',vendor_manager='$vendor_manager',customer_manager='$customer_manager',wallet_manager='$wallet_manager',card_issuance_manager='$card_issuance_manager',loan_manager='$loan_manager',investment_manager='$investment_manager',teller_manager='$teller_manager',charges_manager='$charges_manager',savings_account='$savings_account',reports_module='$reports_module',payroll_module='$payroll_module',income_module='$income_module',expenses_module='$expenses_module',general_settings='$general_settings',otp_option='$otp_option',mobileapp_link='$mobileapp_link',tsavings_subacct='$tsavings_subacct',ts_roi_type='$ts_roi_type',ts_roi='$ts_roi',product_manager='$product_manager',editoption='$editoption',takafulmenu='$takafulmenu',healthmenu='$healthmenu',groupcontribution='$groupcontribution',pos_manager='$pos_manager',nip_route='$nip_route',invite_manager='$invite_manager',halalpay_module='$halalpay_module',wallet_creation='$wallet_creation',bvn_route='$bvn_route',account_manager='$account_manager',bvn_manager='$bvn_manager',sms_checker='$sms_checker',va_provider='$va_provider',defaultAcct='$defaultAcct',pending_manager='$pending_manager',pool_account='$pool_account',airtime='$airtime',databundle='$databundle',billpayment='$billpayment',va_fortill='$va_fortill',cardless_wroute='$cardless_wroute',dedicated_sms_gateway='$dedicated_sms_gateway',dedicated_ledgerAcctNo_prefix='$newLedgerAcctNo_prefix',donation_manager='$donation_manager',copyright='$copyright',upfront_payment='$upfront_payment',enable_bvn='$enable_bvn',enable_acct_verification='$enable_acct_verification',cardtokenization_subacct='$cardtokenization_subacct',allow_login_otp='$allow_login_otp',merchantWalletID='$merchantWalletID',verification_manager='$verification_manager',idVType='$idVTyp',enrolment_manager='$enrolment_manager' WHERE companyid ='$id'") or die("Error:" . mysqli_error($link)) : "";
                        echo "<script>alert('Data Updated Successfully!'); </script>";
                    }
                    
                  }
                ?>
             <div class="box-body">

<?php
$insid = $_GET['idm'];
$search_others = mysqli_query($link, "SELECT * FROM member_settings WHERE companyid='$insid'");
if(mysqli_num_rows($search_others) == 1){
$row = mysqli_fetch_assoc($search_others);

$myMerchantWalletID = $row['merchantWalletID'];
$getDetails = mysqli_query($link, "SELECT * FROM user WHERE created_by = '$insid' AND id = '$myMerchantWalletID'") or die ("Error:" . mysqli_error($link));
$rowsDetails = mysqli_fetch_array($getDetails);
?>
             <form class="form-horizontal" method="post" enctype="multipart/form-data">

             <div class="box-body">
        
      <div class="form-group">
            <label for="" class="col-sm-3 control-label" style="color: blue;">Your Logo</label>
      <div class="col-sm-9">
               <input type='file' name="image" onChange="readURL(this);"/>
               <img id="blah"  src="<?php echo $fetchsys_config['file_baseurl'].$row['logo'];?>" alt="Upload Logo Here" height="100" width="100"/>
      </div>
      </div>
      
      <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Business Name</label>
                  <div class="col-sm-9">
                  <input name="cname" type="text" class="form-control" value="<?php echo $row['cname']; ?>" required/>
                  </div>
                  </div>
          
          <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color: blue;">ID</label>
                  <div class="col-sm-9">
                  <input name="companyid" type="text" class="form-control" value="<?php echo $row['companyid'];?>" readonly="readonly">
                  </div>
                  </div>


      <div class="form-group">
        <label for="" class="col-sm-3 control-label" style="color: blue;">Upload Login Background</label>
        <div class="col-sm-9">
          <input type='file' name="image3"/>
          <img src="<?php echo $fetchsys_config['file_baseurl'].$row['frontpg_backgrd']; ?>" alt="Background Here" height="100" width="100"/>
        </div>
      </div>    
    
    <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Sender ID</label>
                  <div class="col-sm-9">
                  <input type="text" name="senderid" type="text" class="form-control" value="<?php echo $row['sender_id']; ?>" required/>
                  </div>
                  </div>
    
    <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Dedicated USSD Prefix</label>
                  <div class="col-sm-9">
                  <input type="text" name="dedicated_ussd_prefix" type="text" class="form-control" value="<?php echo ($row['dedicated_ussd_prefix'] == "") ? ($lastussdcode_prefix + 1) : $row['dedicated_ussd_prefix']; ?>"/>
                  </div>
                  </div>
    
    <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">USSD Status</label>
                  <div class="col-sm-9">
                  <select name="ussd_status"  class="form-control select2" required>
                      <option value="<?php echo $row['ussd_status']; ?>"><?php echo $row['ussd_status']; ?></option>
                      <option value="Active">Active</option>
                      <option value="NotActive">NotActive</option>
                    </select>
                  </div>
                  </div>

      <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">BVN Verification</label>
                  <div class="col-sm-9">
                  <select name="enable_bvn" class="form-control select2" required>
                      <option value="<?php echo $row['enable_bvn']; ?>"><?php echo $row['enable_bvn']; ?></option>
                      <option value="Yes">Yes</option>
                      <option value="No">No</option>
                    </select>
                  </div>
                  </div>

      <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Account Verification</label>
                  <div class="col-sm-9">
                  <select name="enable_acct_verification" class="form-control select2" required>
                      <option value="<?php echo $row['enable_acct_verification']; ?>"><?php echo $row['enable_acct_verification']; ?></option>
                      <option value="Yes">Yes</option>
                      <option value="No">No</option>
                    </select>
                  </div>
                  </div>

      <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Allow Login OTP</label>
                  <div class="col-sm-9">
                  <select name="allow_login_otp" class="form-control select2" required>
                      <option value="<?php echo $row['allow_login_otp']; ?>"><?php echo $row['allow_login_otp']; ?></option>
                      <option value="Yes">Yes</option>
                      <option value="No">No</option>
                    </select>
                  </div>
                  </div>

      <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Ledger Account No. Prefix</label>
                  <div class="col-sm-9">
                  <input type="text" name="lastledgerAcctNo_prefix" type="text" class="form-control" value="<?php echo ($row['dedicated_ledgerAcctNo_prefix'] == "") ? "" : $row['dedicated_ledgerAcctNo_prefix']; ?>" required/>
                  <?php echo ($fetchlast_code1['dedicated_ledgerAcctNo_prefix'] == "") ? "No Prefix Mapped Yet!" : "The Last Prefix Mapped is: ".$lastledgerAcctNo_prefix; ?>
                  </div>
                  </div>

      <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Mobile App Link</label>
                  <div class="col-sm-9">
                  <input type="text" name="mobileapp_link" type="text" class="form-control" value="<?php echo $row['mobileapp_link']; ?>"/>
                  </div>
                  </div>

        <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">App Copyright</label>
                  <div class="col-sm-9">
                  <input type="text" name="copyright" type="text" class="form-control" value="<?php echo ($row['copyright'] == "") ? "Copyright ".date('Y').". Powered by Esusu Africa." : $row['copyright']; ?>"/>
                  </div>
                  </div>

        <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Wallet Merchant Account</label>
                  <div class="col-sm-9">
                  <select name="merchantWalletID"  class="form-control select2">

                    <option value="<?php echo $myMerchantWalletID; ?>"><?php echo ($myMerchantWalletID == "") ? "Select Account" : $rowsDetails['name'].' '.$rowsDetails['fname']; ?></option>

                    <?php
                    $get = mysqli_query($link, "SELECT * FROM user WHERE created_by = '$insid' ORDER BY id DESC") or die (mysqli_error($link));
                    while($rows = mysqli_fetch_array($get))
                    {
                    ?>
                      <option value="<?php echo $rows['id']; ?>"><?php echo $rows['name'].' '.$rows['lname']; ?></option>
                    <?php } ?>

                  </select>
                  </div>
                  </div>

        <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Savings Sub-Acct.</label>
                  <div class="col-sm-9">
                  <input type="text" name="tsavings_subacct" type="text" class="form-control" value="<?php echo $row['tsavings_subacct']; ?>"/>
                  </div>
                  </div>

        <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Card Tokenization Sub-Acct.</label>
                  <div class="col-sm-9">
                  <input type="text" name="cardtokenization_subacct" type="text" class="form-control" value="<?php echo $row['cardtokenization_subacct']; ?>"/>
                  </div>
                  </div>

        <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Savings ROI Type</label>
                  <div class="col-sm-9">
                  <select name="ts_roi_type"  class="form-control select2">
                      <option value="<?php echo $row['ts_roi_type']; ?>"><?php echo $row['ts_roi_type']; ?></option>
                      <option value="Ratio">Ratio</option>
                      <option value="Percentage">Percentage</option>
                      <option value="Flat Rate">Flat Rate</option>
                    </select>
                  </div>
                  </div>

                  <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Savings ROI</label>
                  <div class="col-sm-9">
                  <input type="text" name="ts_roi" type="text" class="form-control" value="<?php echo $row['ts_roi']; ?>"/>
                  </div>
                  </div>

                  <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Pay Upfront Interest</label>
                  <div class="col-sm-9">
                  <select name="upfront_payment"  class="form-control select2">
                      <option value="<?php echo $row['upfront_payment']; ?>"><?php echo $row['upfront_payment']; ?></option>
                      <option value="Yes">Yes</option>
                      <option value="No">No</option>
                    </select>
                  </div>
                  </div>
                  
                  <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Identity Verification Type</label>
                  <div class="col-sm-9">
                  <select name="idVType[]" class="form-control select2" style="width: 100%;" multiple="multiple" /required>
                    <?php
                    if($row['idVType'] == ""){
                      echo '<option value="" selected>---Select Settings----</option>';
                    }
                    else{
                      $explodeVA = explode(",",$row['idVType']);
                      $countVA = (count($explodeVA) - 1);
                      for($i = 0; $i <= $countVA; $i++){
                          echo '<option value="'.$explodeVA[$i].'" selected="selected">'.$explodeVA[$i].'</option>';
                      }
                    }
                    ?>

                    <option disabled>FILTER BY TYPE</option>
                    <option value="NIN-SEARCH">NIN-SEARCH</option>
 					          <option value="NIN-PHONE-SEARCH">NIN-PHONE-SEARCH</option>
                    <option value="NIN-DEMOGRAPHIC-SEARCH">NIN-DEMOGRAPHIC-SEARCH</option>
                    <option value="BVN-FULL-DETAILS">BVN-FULL-DETAILS</option>
                  </select>
                  </div>
                  </div>

                  <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">NIP Switch</label>
                  <div class="col-sm-9">
                  <select name="nip_route" class="form-control select2" required>
                      <option value="<?php echo $row['nip_route']; ?>"><?php echo $row['nip_route']; ?></option>
                      <option value="ProvidusBank">ProvidusBank</option>
                      <option value="AccessBank">AccessBank</option>
                      <option value="SterlingBank">SterlingBank</option>
                      <option value="Wallet Africa">Wallet Africa</option>
                      <option value="GTBank">GTBank</option>
                      <option value="RubiesBank">Rubies Bank</option>
                      <option value="PrimeAirtime">Prime Airtime</option>
                      <option value="SuntrustBank">Suntrust Bank</option>
                      <option value="None">None</option>
                  </select>
                  </div>
                  </div>

                  <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Cardless Withdrawal Route</label>
                  <div class="col-sm-9">
                  <select name="cardless_wroute" class="form-control select2" required>
                      <option value="<?php echo $row['cardless_wroute']; ?>"><?php echo $row['cardless_wroute']; ?></option>
                      <option value="CGate">CGate</option>
                      <option value="GTBank">GTBank</option>
                      <option value="None">None</option>
                  </select>
                  </div>
                  </div>

                  <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Airtime Route</label>
                  <div class="col-sm-9">
                  <select name="airtime" class="form-control select2" required>
                      <option value="<?php echo $row['airtime']; ?>"><?php echo $row['airtime']; ?></option>
                      <option value="Estore">Estore</option>
                      <option value="Rubies">Rubies</option>
                      <option value="PrimeAirtime">Prime Airtime</option>
                      <option value="MyFlex">MyFlex</option>
                      <option value="None">None</option>
                  </select>
                  </div>
                  </div>

                  <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Databundle Route</label>
                  <div class="col-sm-9">
                  <select name="databundle" class="form-control select2" required>
                      <option value="<?php echo $row['databundle']; ?>"><?php echo $row['databundle']; ?></option>
                      <option value="Estore">Estore</option>
                      <option value="Rubies">Rubies</option>
                      <option value="PrimeAirtime">Prime Airtime</option>
                      <option value="MyFlex">MyFlex</option>
                      <option value="None">None</option>
                  </select>
                  </div>
                  </div>

                  <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Billpayment Route</label>
                  <div class="col-sm-9">
                  <select name="billpayment" class="form-control select2" required>
                      <option value="<?php echo $row['billpayment']; ?>"><?php echo $row['billpayment']; ?></option>
                      <option value="Estore">Estore</option>
                      <option value="Rubies">Rubies</option>
                      <option value="PrimeAirtime">Prime Airtime</option>
                      <option value="MyFlex">MyFlex</option>
                      <option value="None">None</option>
                  </select>
                  </div>
                  </div>

                  <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Virtual Account Provider</label>
                  <div class="col-sm-9">
                  <select name="va_provider[]" class="form-control select2" style="width: 100%;" multiple="multiple" /required>
                    <?php
                    if($row['va_provider'] == ""){
                      echo '<option value="" selected>---Select Settings----</option>';
                    }
                    else{
                      $explodeVA = explode(",",$row['va_provider']);
                      $countVA = (count($explodeVA) - 1);
                      for($i = 0; $i <= $countVA; $i++){
                          echo '<option value="'.$explodeVA[$i].'" selected="selected">'.$explodeVA[$i].'</option>';
                      }
                    }
                    ?>

                    <option disabled>FILTER BY PARTNER</option>
                    <option value="Monnify">Monnify</option>
                    <option value="Rubies Bank">Rubies Bank</option>
                    <option value="Flutterwave">Flutterwave</option>
                    <option value="Payant">Payant</option>
                    <option value="Providus Bank">Providus Bank</option>
                    <option value="Wema Bank">Wema Bank</option>
                    <option value="Sterling Bank">Sterling Bank</option>
                  </select>
                  </div>
                  </div>


                  <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Default Wallet Acct. Provider</label>
                  <div class="col-sm-9">
                  <select name="defaultAcct[]" class="form-control select2" style="width: 100%;" multiple="multiple" /required>
                  <?php
                    if($row['defaultAcct'] == ""){
                      echo '<option value="" selected>---Select Settings----</option>';
                    }
                    else{
                      $explodeVA = explode(",",$row['defaultAcct']);
                      $countVA = (count($explodeVA) - 1);
                      for($i = 0; $i <= $countVA; $i++){
                          echo '<option value="'.$explodeVA[$i].'" selected="selected">'.$explodeVA[$i].'</option>';
                      }
                    }
                    ?>

                    <option disabled>FILTER BY PARTNER BANK</option>
                    <option value="Monnify">Monnify</option>
                    <option value="Rubies Bank">Rubies Bank</option>
                    <option value="Flutterwave">Flutterwave</option>
                    <option value="Payant">Payant</option>
                    <option value="Providus Bank">Providus Bank</option>
                    <option value="Wema Bank">Wema Bank</option>
                    <option value="Sterling Bank">Sterling Bank</option>
                    <option value="None">None</option>
                  </select>
                  </div>
                  </div>


                  <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">BVN Route</label>
                  <div class="col-sm-9">
                  <select name="bvn_route" class="form-control select2">
                      <option value="<?php echo $row['bvn_route']; ?>"><?php echo $row['bvn_route']; ?></option>
                      <option value="ProvidusBank">ProvidusBank</option>
                      <option value="SterlingBank">SterlingBank</option>
                      <option value="Wallet Africa">Wallet Africa</option>
                      <option value="None">None</option>
                  </select>
                  </div>
                  </div>

                  <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">VA for TILL</label>
                  <div class="col-sm-9">
                  <select name="va_fortill" class="form-control select2" required>
                      <option value="<?php echo $row['va_fortill']; ?>"><?php echo $row['va_fortill']; ?></option>
                      <option disabled>FILTER BY PARTNER BANK</option>
                      <option value="Monnify">Monnify</option>
                      <option value="Rubies Bank">Rubies Bank</option>
                      <option value="Flutterwave">Flutterwave</option>
                      <option value="Payant">Payant</option>
                      <option value="Providus Bank">Providus Bank</option>
                      <option value="Wema Bank">Wema Bank</option>
                      <option value="Sterling Bank">Sterling Bank</option>
                      <option value="None">None</option>
                  </select>
                  </div>
                  </div>

                  <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">SMS Checker</label>
                  <div class="col-sm-9">
                  <select name="sms_checker"  class="form-control select2" required>
                      <option value="<?php echo $row['sms_checker']; ?>"><?php echo $row['sms_checker']; ?></option>
                      <option value="On">On</option>
                      <option value="Off">Off</option>
                    </select>
                  </div>
                  </div>

    <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Dedicated SMS Gateway</label>
                  <div class="col-sm-9">
                  <select name="dedicated_sms_gateway"  class="form-control select2" required>
                      <option value="<?php echo $row['dedicated_sms_gateway']; ?>"><?php echo $row['dedicated_sms_gateway']; ?></option>
                      <option value="On">On</option>
                      <option value="Off">Off</option>
                    </select>
                  </div>
                  </div>

                  <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Donation Manager (for Customer)</label>
                  <div class="col-sm-9">
                  <select name="donation_manager"  class="form-control select2" required>
                      <option value="<?php echo $row['donation_manager']; ?>"><?php echo $row['donation_manager']; ?></option>
                      <option value="On">On</option>
                      <option value="Off">Off</option>
                    </select>
                  </div>
                  </div>

                  
    <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Branch Manager</label>
                  <div class="col-sm-9">
                  <select name="branch_manager"  class="form-control select2" required>
                      <option value="<?php echo $row['branch_manager']; ?>"><?php echo $row['branch_manager']; ?></option>
                      <option value="On">On</option>
                      <option value="Off">Off</option>
                    </select>
                  </div>
                  </div>
                  
    <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Department Manager</label>
                  <div class="col-sm-9">
                  <select name="dept_settings"  class="form-control select2" required>
                      <option value="<?php echo $row['dept_settings']; ?>"><?php echo $row['dept_settings']; ?></option>
                      <option value="On">On</option>
                      <option value="Off">Off</option>
                    </select>
                  </div>
                  </div>
                  
    <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Permission Manager</label>
                  <div class="col-sm-9">
                  <select name="permission_manager"  class="form-control select2" required>
                      <option value="<?php echo $row['permission_manager']; ?>"><?php echo $row['permission_manager']; ?></option>
                      <option value="On">On</option>
                      <option value="Off">Off</option>
                    </select>
                  </div>
                  </div>
                  
    <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Subagent Manager</label>
                  <div class="col-sm-9">
                  <select name="subagent_manager"  class="form-control select2" required>
                      <option value="<?php echo $row['subagent_manager']; ?>"><?php echo $row['subagent_manager']; ?></option>
                      <option value="On">On</option>
                      <option value="Off">Off</option>
                    </select>
                  </div>
                  </div>

                  <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Invite Manager</label>
                  <div class="col-sm-9">
                  <select name="invite_manager"  class="form-control select2" required>
                      <option value="<?php echo $row['invite_manager']; ?>"><?php echo $row['invite_manager']; ?></option>
                      <option value="On">On</option>
                      <option value="Off">Off</option>
                    </select>
                  </div>
                  </div>


                  <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">HalalPAY Module</label>
                  <div class="col-sm-9">
                  <select name="halalpay_module"  class="form-control select2" required>
                      <option value="<?php echo $row['halalpay_module']; ?>"><?php echo $row['halalpay_module']; ?></option>
                      <option value="On">On</option>
                      <option value="Off">Off</option>
                    </select>
                  </div>
                  </div>

                  
    <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Staff Manager</label>
                  <div class="col-sm-9">
                  <select name="staff_manager"  class="form-control select2" required>
                      <option value="<?php echo $row['staff_manager']; ?>"><?php echo $row['staff_manager']; ?></option>
                      <option value="On">On</option>
                      <option value="Off">Off</option>
                    </select>
                  </div>
                  </div>
                  
    <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Vendor Manager</label>
                  <div class="col-sm-9">
                  <select name="vendor_manager"  class="form-control select2" required>
                      <option value="<?php echo $row['vendor_manager']; ?>"><?php echo $row['vendor_manager']; ?></option>
                      <option value="On">On</option>
                      <option value="Off">Off</option>
                    </select>
                  </div>
                  </div>
                  
    <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Customer Manager</label>
                  <div class="col-sm-9">
                  <select name="customer_manager"  class="form-control select2" required>
                      <option value="<?php echo $row['customer_manager']; ?>"><?php echo $row['customer_manager']; ?></option>
                      <option value="On">On</option>
                      <option value="Off">Off</option>
                    </select>
                  </div>
                  </div>

                  <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Account Manager</label>
                  <div class="col-sm-9">
                  <select name="account_manager" class="form-control select2">
                      <option value="<?php echo $row['account_manager']; ?>"><?php echo $row['account_manager']; ?></option>
                      <option value="On">On</option>
                      <option value="Off">Off</option>
                  </select>
                  </div>
                  </div>

                  <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Pending Manager</label>
                  <div class="col-sm-9">
                  <select name="pending_manager" class="form-control select2">
                      <option value="<?php echo $row['pending_manager']; ?>"><?php echo $row['pending_manager']; ?></option>
                      <option value="On">On</option>
                      <option value="Off">Off</option>
                  </select>
                  </div>
                  </div>
                  
                 <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Verification Manager</label>
                  <div class="col-sm-9">
                  <select name="verification_manager"  class="form-control select2" required>
                      <option value="<?php echo $row['verification_manager']; ?>"><?php echo $row['verification_manager']; ?></option>
                      <option value="On">On</option>
                      <option value="Off">Off</option>
                    </select>
                  </div>
                  </div>

                  <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Enrolment Manager</label>
                  <div class="col-sm-9">
                  <select name="enrolment_manager"  class="form-control select2" required>
                      <option value="<?php echo $row['enrolment_manager']; ?>"><?php echo $row['enrolment_manager']; ?></option>
                      <option value="On">On</option>
                      <option value="Off">Off</option>
                    </select>
                  </div>
                  </div>
                  
    <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Wallet Manager</label>
                  <div class="col-sm-9">
                  <select name="wallet_manager"  class="form-control select2" required>
                      <option value="<?php echo $row['wallet_manager']; ?>"><?php echo $row['wallet_manager']; ?></option>
                      <option value="On">On</option>
                      <option value="Off">Off</option>
                    </select>
                  </div>
                  </div>

                  <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Pool Account</label>
                  <div class="col-sm-9">
                  <select name="pool_account"  class="form-control select2" required>
                      <option value="<?php echo $row['pool_account']; ?>"><?php echo $row['pool_account']; ?></option>
                      <option value="On">On</option>
                      <option value="Off">Off</option>
                    </select>
                  </div>
                  </div>

                  <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">BVN Manager</label>
                  <div class="col-sm-9">
                  <select name="bvn_manager"  class="form-control select2" required>
                      <option value="<?php echo $row['bvn_manager']; ?>"><?php echo $row['bvn_manager']; ?></option>
                      <option value="On">On</option>
                      <option value="Off">Off</option>
                    </select>
                  </div>
                  </div>

                  <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Wallet Creation</label>
                  <div class="col-sm-9">
                  <select name="wallet_creation"  class="form-control select2" required>
                      <option value="<?php echo $row['wallet_creation']; ?>"><?php echo $row['wallet_creation']; ?></option>
                      <option value="On">On</option>
                      <option value="Off">Off</option>
                    </select>
                  </div>
                  </div>

      <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Pos Manager</label>
                  <div class="col-sm-9">
                  <select name="pos_manager"  class="form-control select2" required>
                      <option value="<?php echo $row['pos_manager']; ?>"><?php echo $row['pos_manager']; ?></option>
                      <option value="On">On</option>
                      <option value="Off">Off</option>
                    </select>
                  </div>
                  </div>
                  
    <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Card Issuance Manager</label>
                  <div class="col-sm-9">
                  <select name="card_issuance_manager"  class="form-control select2" required>
                      <option value="<?php echo $row['card_issuance_manager']; ?>"><?php echo $row['card_issuance_manager']; ?></option>
                      <option value="On">On</option>
                      <option value="Off">Off</option>
                    </select>
                  </div>
                  </div>
                  
    <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Loan Manager</label>
                  <div class="col-sm-9">
                  <select name="loan_manager"  class="form-control select2" required>
                      <option value="<?php echo $row['loan_manager']; ?>"><?php echo $row['loan_manager']; ?></option>
                      <option value="On">On</option>
                      <option value="Off">Off</option>
                    </select>
                  </div>
                  </div>
                  
    <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Investment Manager</label>
                  <div class="col-sm-9">
                  <select name="investment_manager"  class="form-control select2" required>
                      <option value="<?php echo $row['investment_manager']; ?>"><?php echo $row['investment_manager']; ?></option>
                      <option value="On">On</option>
                      <option value="Off">Off</option>
                    </select>
                  </div>
                  </div>
                  
    <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Teller Manager</label>
                  <div class="col-sm-9">
                  <select name="teller_manager"  class="form-control select2" required>
                      <option value="<?php echo $row['teller_manager']; ?>"><?php echo $row['teller_manager']; ?></option>
                      <option value="On">On</option>
                      <option value="Off">Off</option>
                    </select>
                  </div>
                  </div>
                  
    <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Charges Manager</label>
                  <div class="col-sm-9">
                  <select name="charges_manager"  class="form-control select2" required>
                      <option value="<?php echo $row['charges_manager']; ?>"><?php echo $row['charges_manager']; ?></option>
                      <option value="On">On</option>
                      <option value="Off">Off</option>
                    </select>
                  </div>
                  </div>
                  
    <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Savings Manager</label>
                  <div class="col-sm-9">
                  <select name="savings_account"  class="form-control select2" required>
                      <option value="<?php echo $row['savings_account']; ?>"><?php echo $row['savings_account']; ?></option>
                      <option value="On">On</option>
                      <option value="Off">Off</option>
                    </select>
                  </div>
                  </div>
                  
    <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Report Manager</label>
                  <div class="col-sm-9">
                  <select name="reports_module"  class="form-control select2" required>
                      <option value="<?php echo $row['reports_module']; ?>"><?php echo $row['reports_module']; ?></option>
                      <option value="On">On</option>
                      <option value="Off">Off</option>
                    </select>
                  </div>
                  </div>
                  
    <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Payroll Manager</label>
                  <div class="col-sm-9">
                  <select name="payroll_module"  class="form-control select2" required>
                      <option value="<?php echo $row['payroll_module']; ?>"><?php echo $row['payroll_module']; ?></option>
                      <option value="On">On</option>
                      <option value="Off">Off</option>
                    </select>
                  </div>
                  </div>
                  
    <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Income Manager</label>
                  <div class="col-sm-9">
                  <select name="income_module"  class="form-control select2" required>
                      <option value="<?php echo $row['income_module']; ?>"><?php echo $row['income_module']; ?></option>
                      <option value="On">On</option>
                      <option value="Off">Off</option>
                    </select>
                  </div>
                  </div>
                  
    <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Expenses Manager</label>
                  <div class="col-sm-9">
                  <select name="expenses_module"  class="form-control select2" required>
                      <option value="<?php echo $row['expenses_module']; ?>"><?php echo $row['expenses_module']; ?></option>
                      <option value="On">On</option>
                      <option value="Off">Off</option>
                    </select>
                  </div>
                  </div>
                  
    <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">General Settings</label>
                  <div class="col-sm-9">
                  <select name="general_settings"  class="form-control select2" required>
                      <option value="<?php echo $row['general_settings']; ?>"><?php echo $row['general_settings']; ?></option>
                      <option value="On">On</option>
                      <option value="Off">Off</option>
                    </select>
                  </div>
                  </div>

      <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Product Manager</label>
                  <div class="col-sm-9">
                  <select name="product_manager"  class="form-control select2" required>
                      <option value="<?php echo $row['general_settings']; ?>"><?php echo $row['product_manager']; ?></option>
                      <option value="On">On</option>
                      <option value="Off">Off</option>
                    </select>
                  </div>
                  </div>
                  
    <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Sub-Agent / Staff Wallet</label>
                  <div class="col-sm-9">
                  <select name="subagent_wallet"  class="form-control select2" required>
                      <option value="<?php echo $row['subagent_wallet']; ?>"><?php echo $row['subagent_wallet']; ?></option>
                      <option value="Enabled">Enable</option>
                      <option value="Disabled">Disable</option>
                    </select>
                  </div>
                  </div>

                  <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Profile Edit Option</label>
                  <div class="col-sm-9">
                  <select name="editoption"  class="form-control select2" required>
                      <option value="<?php echo $row['editoption']; ?>"><?php echo $row['editoption']; ?></option>
                      <option value="Enabled">Enable</option>
                      <option value="Disabled">Disable</option>
                    </select>
                  </div>
                  </div>

      <div class="form-group">
          <label for="" class="col-sm-3 control-label" style="color:blue;">Customer Takaful Menu</label>
          <div class="col-sm-9">
              <select name="takafulmenu"  class="form-control select2" required>
                  <option value="<?php echo $row['takafulmenu']; ?>"><?php echo $row['takafulmenu']; ?></option>
                  <option value="Enabled">Enable</option>
                  <option value="Disabled">Disable</option>
              </select>
          </div>
      </div>


      <div class="form-group">
          <label for="" class="col-sm-3 control-label" style="color:blue;">Customer Health Menu</label>
          <div class="col-sm-9">
              <select name="healthmenu"  class="form-control select2" required>
                  <option value="<?php echo $row['healthmenu']; ?>"><?php echo $row['healthmenu']; ?></option>
                  <option value="Enabled">Enable</option>
                  <option value="Disabled">Disable</option>
              </select>
          </div>
      </div>

      <div class="form-group">
          <label for="" class="col-sm-3 control-label" style="color:blue;">Group Contribution</label>
          <div class="col-sm-9">
              <select name="groupcontribution"  class="form-control select2" required>
                  <option value="<?php echo $row['groupcontribution']; ?>"><?php echo $row['groupcontribution']; ?></option>
                  <option value="On">On</option>
                  <option value="Off">Off</option>
              </select>
          </div>
      </div>

    <div class="form-group">
                      <label for="" class="col-sm-3 control-label" style="color:blue;">Currency</label>
                      <div class="col-sm-9">
            <select name="currency"  class="form-control select2" required>
              <option value="<?php echo $row['currency']; ?>"><?php echo $row['currency']; ?></option>
              <option value="NGN">NGN</option>
              <option value="USD">USD</option>
              <option value="EUR">EUR</option>
              <option value="GHS">GHS</option>
              <option value="KES">KES</option>
              <option value="UGX">UGX</option>
              <option value="TZS">TZS</option>
              <option value="ZMW">ZMW</option>
            </select>                 
            </div>
      </div>
      
      <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Transfer Limit (with PIN)</label>
                  <div class="col-sm-9">
                  <input type="number" name="tlimit" type="text" class="form-control" placeholder="Transfer Limit with Staff Pin" value="<?php echo $row['tlimit']; ?>" required>
                  </div>
                  </div>
      
      <div class="form-group">
		                  <label for="" class="col-sm-3 control-label" style="color:blue;">Theme Color</label>
		                  <div class="col-sm-9">
						<select name="theme_color" class="form-control">
							<option value="<?php echo $row['theme_color']; ?>"><?php echo $row['theme_color']; ?></option>
							<option value="black">Black</option>
							<option value="blue">Blue</option>
							<option value="green">Green</option>
							<option value="red">Red</option>
              <option value="orange">Orange</option>
							<option value="yellow">Yellow</option>
							<option value="purple">Purple</option>
							<option value="white">white</option>
							<option value="">Default</option>
						</select>                 
						</div>
		                </div>
		                
	<div class="form-group">
		                  <label for="" class="col-sm-3 control-label" style="color:blue;">Alternate Color</label>
		                  <div class="col-sm-9">
						<select name="alternate_color" class="form-control">
							<option value="<?php echo $row['alternate_color']; ?>"><?php echo $row['alternate_color']; ?></option>
							<option value="black">Black</option>
							<option value="blue">Blue</option>
							<option value="green">Green</option>
							<option value="red">Red</option>
              <option value="orange">Orange</option>
							<option value="yellow">Yellow</option>
							<option value="purple">Purple</option>
							<option value="white">white</option>
							<option value="">Default</option>
						</select>                 
						</div>
		                </div>
		                
    <div class="form-group">
		                  <label for="" class="col-sm-3 control-label" style="color:blue;">Login Background</label>
		                  <div class="col-sm-9">
						<select name="login_background" class="form-control">
							<option value="<?php echo $row['login_background']; ?>"><?php echo $row['login_background']; ?></option>
							<option value="black">Black</option>
							<option value="blue">Blue</option>
							<option value="green">Green</option>
							<option value="red">Red</option>
                            <option value="orange">Orange</option>
							<option value="yellow">Yellow</option>
							<option value="purple">Purple</option>
							<option value="white">white</option>
							<option value="">Default</option>
						</select>                 
						</div>
		                </div>
		                
    <div class="form-group">
		                  <label for="" class="col-sm-3 control-label" style="color:blue;">Login Action Color</label>
		                  <div class="col-sm-9">
						<select name="login_bottoncolor" class="form-control">
							<option value="<?php echo $row['login_bottoncolor']; ?>"><?php echo $row['login_bottoncolor']; ?></option>
							<option value="black">Black</option>
							<option value="blue">Blue</option>
							<option value="green">Green</option>
							<option value="red">Red</option>
                            <option value="orange">Orange</option>
							<option value="yellow">Yellow</option>
							<option value="purple">Purple</option>
							<option value="white">white</option>
							<option value="">Default</option>
						</select>                 
						</div>
		                </div>
		                
		<div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Remita Merchant ID</label>
                  <div class="col-sm-9">
                  <input type="text" name="remita_merchantid" type="text" class="form-control" value="<?php echo $row['remitaMerchantId']; ?>" placeholder="Remita Merchant ID"/>
                  </div>
                  </div>
                  
        <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Remita Api Key</label>
                  <div class="col-sm-9">
                  <input type="text" name="remita_apikey" type="text" class="form-control" value="<?php echo $row['remitaApiKey']; ?>" placeholder="Remita API Key"/>
                  </div>
                  </div>
                  
        <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Remita Service ID</label>
                  <div class="col-sm-9">
                  <input type="text" name="remita_serviceid" type="text" class="form-control" value="<?php echo $row['remitaServiceId']; ?>" placeholder="Remita Service ID"/>
                  </div>
                  </div>
                  
        <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Remita API Token</label>
                  <div class="col-sm-9">
                  <input type="text" name="remita_apitoken" type="text" class="form-control" value="<?php echo $row['remitaApiToken']; ?>" placeholder="Remita API Token"/>
                  </div>
                  </div>
                  
        <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">OTP Verification</label>
                  <div class="col-sm-9">
                  <select name="otp_option"  class="form-control select2" required>
                      <option value="<?php echo $row['otp_option']; ?>" selected><?php echo $row['otp_option']; ?></option>
                      <option value="Yes">Yes</option>
                      <option value="No">No</option>
                      <option value="Both">Both</option>
                    </select>
                    <span style="color: <?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>;"> <b>NOTE:</b> If OTP is set to <b>YES</b>, It will override Email Notification and vice-versa BUT if set to <b>BOTH</b> OTP and Email Notification will be Activated</span>
                  </div>
                  </div>
          
       </div>
       
        <div align="right">
              <div class="box-footer">
                        <button name="update" type="submit" class="btn bg-blue"><i class="fa fa-save">&nbsp;Update</i></button>

              </div>
        </div>
       </form> 
<?php
}
else{
?>
      <form class="form-horizontal" method="post" enctype="multipart/form-data">

                            <?php
                              if(isset($_POST['save'])){

                                  $id= mysqli_real_escape_string($link, $_POST['companyid']);
                                  $cname = mysqli_real_escape_string($link, $_POST['cname']);
                                  $senderid = mysqli_real_escape_string($link, $_POST['senderid']);
                                  $ussd_status = mysqli_real_escape_string($link, $_POST['ussd_status']);
                                  $dedicated_ussd_prefix = mysqli_real_escape_string($link, $_POST['dedicated_ussd_prefix']);
                                  
                                  //MODULE SETTINGS
                                  $branch_manager = mysqli_real_escape_string($link, $_POST['branch_manager']);
                                  $dept_settings = mysqli_real_escape_string($link, $_POST['dept_settings']);
                                  $permission_manager = mysqli_real_escape_string($link, $_POST['permission_manager']);
                                  $subagent_manager = mysqli_real_escape_string($link, $_POST['subagent_manager']);
                                  $staff_manager = mysqli_real_escape_string($link, $_POST['staff_manager']);
                                  $vendor_manager = mysqli_real_escape_string($link, $_POST['vendor_manager']);
                                  $customer_manager = mysqli_real_escape_string($link, $_POST['customer_manager']);
                                  $wallet_manager = mysqli_real_escape_string($link, $_POST['wallet_manager']);
                                  $card_issuance_manager = mysqli_real_escape_string($link, $_POST['card_issuance_manager']);
                                  $loan_manager = mysqli_real_escape_string($link, $_POST['loan_manager']);
                                  $investment_manager = mysqli_real_escape_string($link, $_POST['investment_manager']);
                                  $teller_manager = mysqli_real_escape_string($link, $_POST['teller_manager']);
                                  $charges_manager = mysqli_real_escape_string($link, $_POST['charges_manager']);
                                  $savings_account = mysqli_real_escape_string($link, $_POST['savings_account']);
                                  $reports_module = mysqli_real_escape_string($link, $_POST['reports_module']);
                                  $payroll_module = mysqli_real_escape_string($link, $_POST['payroll_module']);
                                  $income_module = mysqli_real_escape_string($link, $_POST['income_module']);
                                  $expenses_module = mysqli_real_escape_string($link, $_POST['expenses_module']);
                                  $general_settings = mysqli_real_escape_string($link, $_POST['general_settings']);
                                  $subagent_wallet = mysqli_real_escape_string($link, $_POST['subagent_wallet']);
                                  $otp_option = mysqli_real_escape_string($link, $_POST['otp_option']);
                                
                                  $currency = mysqli_real_escape_string($link, $_POST['currency']);
                                  $theme_color = mysqli_real_escape_string($link, $_POST['theme_color']);
                                  $alternate_color = mysqli_real_escape_string($link, $_POST['alternate_color']);
                                  $login_background = mysqli_real_escape_string($link, $_POST['login_background']);
                                  $login_bottoncolor = mysqli_real_escape_string($link, $_POST['login_bottoncolor']);
                                  $tlimit = mysqli_real_escape_string($link, $_POST['tlimit']);
                                  
                                  $remita_merchantid = mysqli_real_escape_string($link, $_POST['remita_merchantid']);
                                  $remita_apikey = mysqli_real_escape_string($link, $_POST['remita_apikey']);
                                  $remita_serviceid = mysqli_real_escape_string($link, $_POST['remita_serviceid']);
                                  $remita_apitoken = mysqli_real_escape_string($link, $_POST['remita_apitoken']);

                                  $mobileapp_link = mysqli_real_escape_string($link, $_POST['mobileapp_link']);
                                  $tsavings_subacct = mysqli_real_escape_string($link, $_POST['tsavings_subacct']);
                                  $ts_roi_type = mysqli_real_escape_string($link, $_POST['ts_roi_type']);
                                  $ts_roi = mysqli_real_escape_string($link, $_POST['ts_roi']);

                                  $product_manager = mysqli_real_escape_string($link, $_POST['product_manager']);
                                  $editoption = mysqli_real_escape_string($link, $_POST['editoption']);
                                  $takafulmenu = mysqli_real_escape_string($link, $_POST['takafulmenu']);
                                  $healthmenu = mysqli_real_escape_string($link, $_POST['healthmenu']);
                                  $groupcontribution = mysqli_real_escape_string($link, $_POST['groupcontribution']);
                                  $pos_manager = mysqli_real_escape_string($link, $_POST['pos_manager']);
                                  $nip_route = mysqli_real_escape_string($link, $_POST['nip_route']);
                                  $invite_manager = mysqli_real_escape_string($link, $_POST['invite_manager']);
                                  $halalpay_module = mysqli_real_escape_string($link, $_POST['halalpay_module']);
                                  $wallet_creation = mysqli_real_escape_string($link, $_POST['wallet_creation']);
                                  $bvn_route = mysqli_real_escape_string($link, $_POST['bvn_route']);
                                  $account_manager = mysqli_real_escape_string($link, $_POST['account_manager']);
                                  $bvn_manager = mysqli_real_escape_string($link, $_POST['bvn_manager']);
                                  $sms_checker = mysqli_real_escape_string($link, $_POST['sms_checker']);
                                  $va_provider = implode(',', mysqli_real_escape_string($link, $_POST['va_provider']));
                                  $defaultAcct = implode(',', mysqli_real_escape_string($link, $_POST['defaultAcct']));
                                  $pending_manager = mysqli_real_escape_string($link, $_POST['pending_manager']);
                                  $pool_account = mysqli_real_escape_string($link, $_POST['pool_account']);
                                  $airtime = mysqli_real_escape_string($link, $_POST['airtime']);
                                  $databundle = mysqli_real_escape_string($link, $_POST['databundle']);
                                  $billpayment = mysqli_real_escape_string($link, $_POST['billpayment']);
                                  $va_fortill = mysqli_real_escape_string($link, $_POST['va_fortill']);
                                  $cardless_wroute = mysqli_real_escape_string($link, $_POST['cardless_wroute']);
                                  $dedicated_sms_gateway = mysqli_real_escape_string($link, $_POST['dedicated_sms_gateway']);
                                  $newLedgerAcctNo_prefix = mysqli_real_escape_string($link, $_POST['lastledgerAcctNo_prefix']);
                                  $donation_manager = mysqli_real_escape_string($link, $_POST['donation_manager']);
                                  $copyright = mysqli_real_escape_string($link, $_POST['copyright']);
                                  $upfront_payment = mysqli_real_escape_string($link, $_POST['upfront_payment']);
                                  $enable_bvn = mysqli_real_escape_string($link, $_POST['enable_bvn']);
                                  $enable_acct_verification = mysqli_real_escape_string($link, $_POST['enable_acct_verification']);
                                  $cardtokenization_subacct = mysqli_real_escape_string($link, $_POST['cardtokenization_subacct']);
                                  $allow_login_otp = mysqli_real_escape_string($link, $_POST['allow_login_otp']);
                                  $merchantWalletID = mysqli_real_escape_string($link, $_POST['merchantWalletID']);
                                  $verification_manager = mysqli_real_escape_string($link, $_POST['verification_manager']);
                                  $idVType = implode(',', mysqli_real_escape_string($link, $_POST['idVType']));
                                  $enrolment_manager = mysqli_real_escape_string($link, $_POST['enrolment_manager']);

                                  //image
                                  $image = addslashes(file_get_contents($_FILES['image']['tmp_name']));
                                  $image3 = addslashes(file_get_contents($_FILES['image3']['tmp_name']));

                                  $target_dir = "../img/";
                                  $target_dir3 = "../img/";

                                  $target_file = $target_dir.basename($_FILES["image"]["name"]);
                                  $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
                
                                  $target_file3 = $target_dir3.basename($_FILES["image3"]["name"]);
                                  $imageFileType3 = pathinfo($target_file3,PATHINFO_EXTENSION);
                
                                  $check = getimagesize($_FILES["image"]["tmp_name"]);
                                  $check3 = getimagesize($_FILES["image3"]["tmp_name"]);
                  
                                  $sourcepath = $_FILES["image"]["tmp_name"];
                                  $targetpath = "../img/" . $_FILES["image"]["name"];
                                  move_uploaded_file($sourcepath,$targetpath);
                
                                  $sourcepath3 = $_FILES["image3"]["tmp_name"];
                                  $targetpath3 = "../img/" . $_FILES["image3"]["name"];
                                  move_uploaded_file($sourcepath3,$targetpath3);
                                    
                                  $location = $_FILES['image']['name'];  
                
                                  $lbackg = $_FILES["image3"]["name"];      
                            
                                  ($ussd_status == "Active") ? mysqli_query($link, "INSERT INTO member_settings VALUES(null,'$id','$cname','$location','$lbackg','$senderid','$currency','$dedicated_ussd_prefix','$ussd_status','$theme_color','$alternate_color','$login_background','$login_bottoncolor','$tlimit','','','','$dept_settings','$subagent_wallet','$remita_merchantid','$remita_apikey','$remita_serviceid','$remita_apitoken','$branch_manager','$permission_manager','$subagent_manager','$staff_manager','$vendor_manager','$bvn_manager','$customer_manager','$wallet_manager','$card_issuance_manager','$loan_manager','$investment_manager','$teller_manager','$charges_manager','$savings_account','$reports_module','$payroll_module','$income_module','$expenses_module','$general_settings','$otp_option','$mobileapp_link','$tsavings_subacct','$ts_roi_type','$ts_roi','$product_manager','$editoption','$takafulmenu','$healthmenu','$groupcontribution','$pos_manager','$nip_route','$invite_manager','$halalpay_module','$wallet_creation','$bvn_route','$account_manager','$sms_checker','$va_provider','$defaultAcct','$pending_manager','$pool_account','$airtime','$databundle','$billpayment','$va_fortill','$cardless_wroute','$dedicated_sms_gateway','$newLedgerAcctNo_prefix','$donation_manager','$copyright','$upfront_payment','$cardtokenization_subacct','$enable_bvn','$enable_acct_verification','$allow_login_otp','$merchantWalletID','$verification_manager','$idVType','$enrolment_manager')") or die(mysqli_error()) : "";
                                  ($ussd_status == "NotActive") ? mysqli_query($link, "INSERT INTO member_settings VALUES(null,'$id','$cname','$location','$lbackg','$senderid','$currency','','$ussd_status','$theme_color','$alternate_color','$login_background','$login_bottoncolor','$tlimit','','','','$dept_settings','$subagent_wallet','$remita_merchantid','$remita_apikey','$remita_serviceid','$remita_apitoken','$branch_manager','$permission_manager','$subagent_manager','$staff_manager','$vendor_manager','$bvn_manager','$customer_manager','$wallet_manager','$card_issuance_manager','$loan_manager','$investment_manager','$teller_manager','$charges_manager','$savings_account','$reports_module','$payroll_module','$income_module','$expenses_module','$general_settings','$otp_option','$mobileapp_link','$tsavings_subacct','$ts_roi_type','$ts_roi','$product_manager','$editoption','$takafulmenu','$healthmenu','$groupcontribution','$pos_manager','$nip_route','$invite_manager','$halalpay_module','$wallet_creation','$bvn_route','$account_manager','$sms_checker','$va_provider','$defaultAcct','$pending_manager','$pool_account','$airtime','$databundle','$billpayment','$va_fortill','$cardless_wroute','$dedicated_sms_gateway','$newLedgerAcctNo_prefix','$donation_manager','$copyright','$upfront_payment','$cardtokenization_subacct','$enable_bvn','$enable_acct_verification','$allow_login_otp','$merchantWalletID','$verification_manager','$idVType','$enrolment_manager')") or die(mysqli_error()) : "";
                                  mysqli_query($link, "UPDATE borrowers SET dedicated_ussd_prefix = '$dedicated_ussd_prefix' WHERE branchid = '$id'");
                                  mysqli_query($link, "UPDATE user SET dedicated_ussd_prefix = '$dedicated_ussd_prefix' WHERE created_by = '$id'");
                                  mysqli_query($link, "UPDATE loan_product SET dedicated_ussd_prefix = '$dedicated_ussd_prefix' WHERE merchantid = '$id'");
                                  echo "<script>alert('Data Saved Successfully!'); </script>";
                  
                              }
                              ?>

             <div class="box-body">
        
      <div class="form-group">
            <label for="" class="col-sm-3 control-label" style="color: blue;">Your Logo</label>
      <div class="col-sm-9">
               <input type='file' name="image" onChange="readURL(this);" />
               <img id="blah" alt="Upload Logo Here" height="100" width="100"/>
      </div>
      </div>
      
      <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Business Name</label>
                  <div class="col-sm-9">
                  <input name="cname" type="text" class="form-control" placeholder="Business Name Here" required/>
                  </div>
                  </div>
          
          <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color: blue;">ID</label>
                  <div class="col-sm-9">
                  <input name="companyid" type="text" class="form-control" value="<?php echo $insid; ?>" readonly="readonly">
                  </div>
                  </div>


      <div class="form-group">
        <label for="" class="col-sm-3 control-label" style="color: blue;">Upload Login Background</label>
        <div class="col-sm-9">
          <input type='file' name="image3" class="btn bg-orange"/>
        </div>
      </div>    
          
    <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Sender ID</label>
                  <div class="col-sm-9">
                  <input type="text" name="senderid" type="text" class="form-control" placeholder="Client Sender ID" maxlength="11" required/>
                  </div>
                  </div>
    
    <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Dedicated USSD Prefix</label>
                  <div class="col-sm-9">
                  <input type="text" name="dedicated_ussd_prefix" type="text" class="form-control" value="<?php echo ($lastussdcode_prefix + 1); ?>" placeholder="Dedicated USSD Prefix"/>
                  </div>
                  </div>
                  
    <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">USSD Status</label>
                  <div class="col-sm-9">
                  <select name="ussd_status" class="form-control select2" required>
                      <option value="" selected>Select Status</option>
                      <option value="Active">Active</option>
                      <option value="NotActive">NotActive</option>
                    </select>
                  </div>
                  </div>

    <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Enable BVN Verification</label>
                  <div class="col-sm-9">
                  <select name="enable_bvn" class="form-control select2" required>
                      <option value="">Select Settings</option>
                      <option value="Yes">Yes</option>
                      <option value="No">No</option>
                    </select>
                  </div>
                  </div>

      <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Enable Account Verification</label>
                  <div class="col-sm-9">
                  <select name="enable_acct_verification" class="form-control select2" required>
                      <option value="">Select Settings</option>
                      <option value="Yes">Yes</option>
                      <option value="No">No</option>
                    </select>
                  </div>
                  </div>

      <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Allow Login OTP</label>
                  <div class="col-sm-9">
                  <select name="allow_login_otp" class="form-control select2" required>
                      <option value="">Select Settings</option>
                      <option value="Yes">Yes</option>
                      <option value="No">No</option>
                    </select>
                  </div>
                  </div>

    <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Ledger Account No. Prefix</label>
                  <div class="col-sm-9">
                  <input type="text" name="lastledgerAcctNo_prefix" type="text" class="form-control" value="" required/>
                  <?php echo ($fetchlast_code1['dedicated_ledgerAcctNo_prefix'] == "") ? "No Prefix Mapped Yet!" : "The Last Prefix Mapped is: ".$lastledgerAcctNo_prefix; ?>
                  </div>
                  </div>

                  <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Mobile App Link</label>
                  <div class="col-sm-9">
                  <input type="text" name="mobileapp_link" type="text" class="form-control" placeholder="Client Mobile App Link"/>
                  </div>
                  </div>

                  <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">App Copyright</label>
                  <div class="col-sm-9">
                  <input type="text" name="copyright" type="text" class="form-control" value="Copyright <?php echo date('Y'); ?>. Powered by Esusu Africa." placeholder="App Copyright" required/>
                  </div>
                  </div>

                  <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Wallet Merchant Account</label>
                  <div class="col-sm-9">
                  <select name="merchantWalletID"  class="form-control select2">
                    <option value="">Select Account</option>
                    <?php
                    $get = mysqli_query($link, "SELECT * FROM user WHERE created_by = '$insid' ORDER BY id DESC") or die (mysqli_error($link));
                    while($rows = mysqli_fetch_array($get))
                    {
                    ?>
                      <option value="<?php echo $rows['id']; ?>"><?php echo $rows['name'].' '.$rows['lname']; ?></option>
                    <?php } ?>

                  </select>
                  </div>
                  </div>

                  <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Savings Sub-Acct.</label>
                  <div class="col-sm-9">
                  <input type="text" name="tsavings_subacct" type="text" class="form-control" placeholder="Target Savings Sub-Account"/>
                  </div>
                  </div>

                  <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Card Tokenization Sub-Acct.</label>
                  <div class="col-sm-9">
                  <input type="text" name="cardtokenization_subacct" type="text" class="form-control" placeholder="Card Tokenization Sub-Account"/>
                  </div>
                  </div>

                  <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Savings ROI Type</label>
                  <div class="col-sm-9">
                  <select name="ts_roi_type"  class="form-control select2">
                      <option value="" selected>Select Savings ROI Type</option>
                      <option value="Ratio">Ratio</option>
                      <option value="Percentage">Percentage</option>
                      <option value="Flat Rate">Flat Rate</option>
                    </select>
                  </div>
                  </div>

                  <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Savings ROI</label>
                  <div class="col-sm-9">
                  <input type="number" name="ts_roi" type="text" class="form-control" placeholder="Enter ROI without any symbol"/>
                  </div>
                  </div>

                  <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Pay Upfront Interest</label>
                  <div class="col-sm-9">
                  <select name="upfront_payment" class="form-control select2">
                      <option value="" selected>Select Settings</option>
                      <option value="Yes">Yes</option>
                      <option value="No">No</option>
                    </select>
                  </div>
                  </div>
                  
                  <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Identity Verification Type</label>
                  <div class="col-sm-9">
                  <select name="idVType[]" class="form-control select2" style="width: 100%;" multiple="multiple" /required>
                    <option value="" selected="selected">---Select Settings---</option>
                    <option value="NIN-SEARCH">NIN-SEARCH</option>
 					<option value="NIN-PHONE-SEARCH">NIN-PHONE-SEARCH</option>
                    <option value="NIN-DEMOGRAPHIC-SEARCH">NIN-DEMOGRAPHIC-SEARCH</option>
                    <option value="BVN-FULL-DETAILS">BVN-FULL-DETAILS</option>
                  </select>
                  </div>
                  </div>

                  <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">NIP Switch</label>
                  <div class="col-sm-9">
                  <select name="nip_route" class="form-control select2" required>
                      <option value="" selected>Select Settings</option>
                      <option value="ProvidusBank">ProvidusBank</option>
                      <option value="AccessBank">AccessBank</option>
                      <option value="SterlingBank">SterlingBank</option>
                      <option value="Wallet Africa">Wallet Africa</option>
                      <option value="GTBank">GTBank</option>
                      <option value="RubiesBank">Rubies Bank</option>
                      <option value="PrimeAirtime">Prime Airtime</option>
                      <option value="SuntrustBank">Suntrust Bank</option>
                      <option value="None">None</option>
                    </select>
                  </div>
                  </div>

                  <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Cardless Withdrawal Route</label>
                  <div class="col-sm-9">
                  <select name="cardless_wroute" class="form-control select2" required>
                      <option value="" selected>Select Settings</option>
                      <option value="CGate">CGate</option>
                      <option value="GTBank">GTBank</option>
                      <option value="None">None</option>
                  </select>
                  </div>
                  </div>

                  <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Airtime Route</label>
                  <div class="col-sm-9">
                  <select name="airtime" class="form-control select2" required>
                      <option value="" selected>Select Settings</option>
                      <option value="Estore">Estore</option>
                      <option value="Rubies">Rubies</option>
                      <option value="PrimeAirtime">Prime Airtime</option>
                      <option value="MyFlex">MyFlex</option>
                      <option value="None">None</option>
                  </select>
                  </div>
                  </div>

                  <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Databundle Route</label>
                  <div class="col-sm-9">
                  <select name="databundle" class="form-control select2" required>
                      <option value="" selected>Select Settings</option>
                      <option value="Estore">Estore</option>
                      <option value="Rubies">Rubies</option>
                      <option value="PrimeAirtime">Prime Airtime</option>
                      <option value="MyFlex">MyFlex</option>
                      <option value="None">None</option>
                  </select>
                  </div>
                  </div>

                  <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Billpayment Route</label>
                  <div class="col-sm-9">
                  <select name="billpayment" class="form-control select2" required>
                      <option value="" selected>Select Settings</option>
                      <option value="Estore">Estore</option>
                      <option value="Rubies">Rubies</option>
                      <option value="PrimeAirtime">Prime Airtime</option>
                      <option value="MyFlex">MyFlex</option>
                      <option value="None">None</option>
                  </select>
                  </div>
                  </div>
                  
                  
                  <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Virtual Account Provider</label>
                  <div class="col-sm-9">
                  <select name="va_provider[]" class="form-control select2" style="width: 100%;" multiple="multiple" /required>
                    <option value="" selected="selected">---Select Settings---</option>
                    <option value="Monnify">Monnify</option>
                    <option value="Rubies Bank">Rubies Bank</option>
                    <option value="Flutterwave">Flutterwave</option>
                    <option value="Payant">Payant</option>
                    <option value="Providus Bank">Providus Bank</option>
                    <option value="Wema Bank">Wema Bank</option>
                  </select>
                  </div>
                  </div>

                  <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Default Wallet Acct. Provider</label>
                  <div class="col-sm-9">
                  <select name="defaultAcct[]" class="form-control select2" style="width: 100%;" multiple="multiple" /required>
                    <option value="" selected>---Select Settings----</option>
                    <option value="Monnify">Monnify</option>
                    <option value="Rubies Bank">Rubies Bank</option>
                    <option value="Flutterwave">Flutterwave</option>
                    <option value="Payant">Payant</option>
                    <option value="Providus Bank">Providus Bank</option>
                    <option value="Wema Bank">Wema Bank</option>
                    <option value="None">None</option>
                  </select>
                  </div>
                  </div>

                  <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">BVN Route</label>
                  <div class="col-sm-9">
                  <select name="bvn_route" class="form-control select2">
                  <option value="" selected>Select Settings</option>
                      <option value="ProvidusBank">ProvidusBank</option>
                      <option value="SterlingBank">SterlingBank</option>
                      <option value="Wallet Africa">Wallet Africa</option>
                      <option value="None">None</option>
                  </select>
                  </div>
                  </div>

                  <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">VA for TILL</label>
                  <div class="col-sm-9">
                  <select name="va_fortill" class="form-control select2" required>
                      <option value="" selected>Select Settings</option>
                      <option value="Monnify">Monnify</option>
                      <option value="Rubies Bank">Rubies Bank</option>
                      <option value="Flutterwave">Flutterwave</option>
                      <option value="Payant">Payant</option>
                      <option value="Providus Bank">Providus Bank</option>
                      <option value="Wema Bank">Wema Bank</option>
                      <option value="None">None</option>
                  </select>
                  </div>
                  </div>

      <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Dedicated SMS Gateway</label>
                  <div class="col-sm-9">
                  <select name="dedicated_sms_gateway"  class="form-control select2" required>
                      <option value="" selected>Select Settings</option>
                      <option value="On">On</option>
                      <option value="Off">Off</option>
                    </select>
                  </div>
                  </div>

                  <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">SMS Checker</label>
                  <div class="col-sm-9">
                  <select name="sms_checker"  class="form-control select2" required>
                      <option value="" selected>Select Settings</option>
                      <option value="On">On</option>
                      <option value="Off">Off</option>
                    </select>
                  </div>
                  </div>

                  <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Donation Manager (for Customer)</label>
                  <div class="col-sm-9">
                  <select name="donation_manager"  class="form-control select2" required>
                      <option value="" selected>Select Settings</option>
                      <option value="On">On</option>
                      <option value="Off">Off</option>
                    </select>
                  </div>
                  </div>
                  
    <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Branch Manager</label>
                  <div class="col-sm-9">
                  <select name="branch_manager"  class="form-control select2" required>
                      <option value="" selected>Select Settings</option>
                      <option value="On">On</option>
                      <option value="Off">Off</option>
                    </select>
                  </div>
                  </div>
                  
    <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Department Manager</label>
                  <div class="col-sm-9">
                  <select name="dept_settings"  class="form-control select2" required>
                      <option value="" selected>Select Settings</option>
                      <option value="On">On</option>
                      <option value="Off">Off</option>
                    </select>
                  </div>
                  </div>
                  
    <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Permission Manager</label>
                  <div class="col-sm-9">
                  <select name="permission_manager"  class="form-control select2" required>
                      <option value="" selected>Select Settings</option>
                      <option value="On">On</option>
                      <option value="Off">Off</option>
                    </select>
                  </div>
                  </div>
                  
    <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Subagent Manager</label>
                  <div class="col-sm-9">
                  <select name="subagent_manager"  class="form-control select2" required>
                      <option value="" selected>Select Settings</option>
                      <option value="On">On</option>
                      <option value="Off">Off</option>
                    </select>
                  </div>
                  </div>

                  <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Invite Manager</label>
                  <div class="col-sm-9">
                  <select name="invite_manager"  class="form-control select2" required>
                      <option value="" selected>Select Settings</option>
                      <option value="On">On</option>
                      <option value="Off">Off</option>
                    </select>
                  </div>
                  </div>

                  <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">HalalPAY Module</label>
                  <div class="col-sm-9">
                  <select name="halalpay_module"  class="form-control select2" required>
                      <option value="" selected>Select Settings</option>
                      <option value="On">On</option>
                      <option value="Off">Off</option>
                    </select>
                  </div>
                  </div>
                  
    <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Staff Manager</label>
                  <div class="col-sm-9">
                  <select name="staff_manager"  class="form-control select2" required>
                      <option value="" selected>Select Settings</option>
                      <option value="On">On</option>
                      <option value="Off">Off</option>
                    </select>
                  </div>
                  </div>
                  
    <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Vendor Manager</label>
                  <div class="col-sm-9">
                  <select name="vendor_manager"  class="form-control select2" required>
                      <option value="" selected>Select Settings</option>
                      <option value="On">On</option>
                      <option value="Off">Off</option>
                    </select>
                  </div>
                  </div>
                  
    <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Customer Manager</label>
                  <div class="col-sm-9">
                  <select name="customer_manager"  class="form-control select2" required>
                      <option value="" selected>Select Settings</option>
                      <option value="On">On</option>
                      <option value="Off">Off</option>
                    </select>
                  </div>
                  </div>

                  <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Account Manager</label>
                  <div class="col-sm-9">
                  <select name="account_manager" class="form-control select2">
                    <option value="" selected>Select Settings</option>
                      <option value="On">On</option>
                      <option value="Off">Off</option>
                  </select>
                  </div>
                  </div>

                  <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Pending Manager</label>
                  <div class="col-sm-9">
                  <select name="pending_manager" class="form-control select2">
                    <option value="" selected>Select Settings</option>
                      <option value="On">On</option>
                      <option value="Off">Off</option>
                  </select>
                  </div>
                  </div>
                  
    <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Verification Manager</label>
                  <div class="col-sm-9">
                  <select name="verification_manager"  class="form-control select2" required>
                      <option value="" selected>Select Settings</option>
                      <option value="On">On</option>
                      <option value="Off">Off</option>
                    </select>
                  </div>
                  </div>

    <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Enrolment Manager</label>
                  <div class="col-sm-9">
                  <select name="enrolment_manager"  class="form-control select2" required>
                      <option value="" selected>Select Settings</option>
                      <option value="On">On</option>
                      <option value="Off">Off</option>
                    </select>
                  </div>
                  </div>
                  
    <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Wallet Manager</label>
                  <div class="col-sm-9">
                  <select name="wallet_manager"  class="form-control select2" required>
                      <option value="" selected>Select Settings</option>
                      <option value="On">On</option>
                      <option value="Off">Off</option>
                    </select>
                  </div>
                  </div>

                  <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Pool Account</label>
                  <div class="col-sm-9">
                  <select name="pool_account"  class="form-control select2" required>
                      <option value="" selected>Select Settings</option>
                      <option value="On">On</option>
                      <option value="Off">Off</option>
                    </select>
                  </div>
                  </div>

                  <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Wallet Creation</label>
                  <div class="col-sm-9">
                  <select name="wallet_creation"  class="form-control select2" required>
                      <option value="" selected>Select Settings</option>
                      <option value="On">On</option>
                      <option value="Off">Off</option>
                    </select>
                  </div>
                  </div>

                  <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">BVN Manager</label>
                  <div class="col-sm-9">
                  <select name="bvn_manager"  class="form-control select2" required>
                      <option value="" selected>Select Settings</option>
                      <option value="On">On</option>
                      <option value="Off">Off</option>
                    </select>
                  </div>
                  </div>


      <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Pos Manager</label>
                  <div class="col-sm-9">
                  <select name="pos_manager"  class="form-control select2" required>
                      <option value="" selected>Select Settings</option>
                      <option value="On">On</option>
                      <option value="Off">Off</option>
                    </select>
                  </div>
                  </div>
                  
    <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Card Issuance Manager</label>
                  <div class="col-sm-9">
                  <select name="card_issuance_manager"  class="form-control select2" required>
                      <option value="" selected>Select Settings</option>
                      <option value="On">On</option>
                      <option value="Off">Off</option>
                    </select>
                  </div>
                  </div>
                  
    <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Loan Manager</label>
                  <div class="col-sm-9">
                  <select name="loan_manager"  class="form-control select2" required>
                      <option value="" selected>Select Settings</option>
                      <option value="On">On</option>
                      <option value="Off">Off</option>
                    </select>
                  </div>
                  </div>
                  
    <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Investment Manager</label>
                  <div class="col-sm-9">
                  <select name="investment_manager"  class="form-control select2" required>
                      <option value="" selected>Select Settings</option>
                      <option value="On">On</option>
                      <option value="Off">Off</option>
                    </select>
                  </div>
                  </div>
                  
    <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Teller Manager</label>
                  <div class="col-sm-9">
                  <select name="teller_manager"  class="form-control select2" required>
                      <option value="" selected>Select Settings</option>
                      <option value="On">On</option>
                      <option value="Off">Off</option>
                    </select>
                  </div>
                  </div>
                  
    <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Charges Manager</label>
                  <div class="col-sm-9">
                  <select name="charges_manager"  class="form-control select2" required>
                      <option value="" selected>Select Settings</option>
                      <option value="On">On</option>
                      <option value="Off">Off</option>
                    </select>
                  </div>
                  </div>
                  
    <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Savings Manager</label>
                  <div class="col-sm-9">
                  <select name="savings_account"  class="form-control select2" required>
                      <option value="" selected>Select Settings</option>
                      <option value="On">On</option>
                      <option value="Off">Off</option>
                    </select>
                  </div>
                  </div>
                  
    <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Report Manager</label>
                  <div class="col-sm-9">
                  <select name="reports_module"  class="form-control select2" required>
                      <option value="" selected>Select Settings</option>
                      <option value="On">On</option>
                      <option value="Off">Off</option>
                    </select>
                  </div>
                  </div>
                  
    <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Payroll Manager</label>
                  <div class="col-sm-9">
                  <select name="payroll_module"  class="form-control select2" required>
                      <option value="" selected>Select Settings</option>
                      <option value="On">On</option>
                      <option value="Off">Off</option>
                    </select>
                  </div>
                  </div>
                  
    <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Income Manager</label>
                  <div class="col-sm-9">
                  <select name="income_module"  class="form-control select2" required>
                      <option value="" selected>Select Settings</option>
                      <option value="On">On</option>
                      <option value="Off">Off</option>
                    </select>
                  </div>
                  </div>
                  
    <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Expenses Manager</label>
                  <div class="col-sm-9">
                  <select name="expenses_module"  class="form-control select2" required>
                      <option value="" selected>Select Settings</option>
                      <option value="On">On</option>
                      <option value="Off">Off</option>
                    </select>
                  </div>
                  </div>
                  
    <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">General Settings</label>
                  <div class="col-sm-9">
                  <select name="general_settings"  class="form-control select2" required>
                      <option value="" selected>Select Settings</option>
                      <option value="On">On</option>
                      <option value="Off">Off</option>
                    </select>
                  </div>
                  </div>

      <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Product Manager</label>
                  <div class="col-sm-9">
                  <select name="product_manager"  class="form-control select2" required>
                  <option value="" selected>Select Settings</option>
                      <option value="On">On</option>
                      <option value="Off">Off</option>
                    </select>
                  </div>
                  </div>
                  
    <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Sub-Agent / Staff Wallet</label>
                  <div class="col-sm-9">
                  <select name="subagent_wallet"  class="form-control select2" required>
                      <option value="" selected>Select Settings</option>
                      <option value="Enabled">Enable</option>
                      <option value="Disabled">Disable</option>
                    </select>
                  </div>
                  </div>

                  <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Profile Edit Option</label>
                  <div class="col-sm-9">
                  <select name="editoption"  class="form-control select2" required>
                      <option value="" selected>Select Settings</option>
                      <option value="Enabled">Enable</option>
                      <option value="Disabled">Disable</option>
                    </select>
                  </div>
                  </div>

       <div class="form-group">
          <label for="" class="col-sm-3 control-label" style="color:blue;">Customer Takaful Menu</label>
          <div class="col-sm-9">
              <select name="takafulmenu"  class="form-control select2" required>
              <option value="" selected>Select Settings</option>
                  <option value="Enabled">Enable</option>
                  <option value="Disabled">Disable</option>
              </select>
          </div>
      </div>

      <div class="form-group">
          <label for="" class="col-sm-3 control-label" style="color:blue;">Customer Health Menu</label>
          <div class="col-sm-9">
              <select name="healthmenu"  class="form-control select2" required>
                  <option value="" selected>Select Settings</option>
                  <option value="Enabled">Enable</option>
                  <option value="Disabled">Disable</option>
              </select>
          </div>
      </div>

      <div class="form-group">
          <label for="" class="col-sm-3 control-label" style="color:blue;">Group Contribution</label>
          <div class="col-sm-9">
              <select name="groupcontribution"  class="form-control select2" required>
                  <option value="" selected>Select Settings</option>
                  <option value="On">On</option>
                  <option value="Off">Off</option>
              </select>
          </div>
      </div>

    <div class="form-group">
                      <label for="" class="col-sm-3 control-label" style="color:blue;">Currency</label>
                      <div class="col-sm-9">
            <select name="currency"  class="form-control select2" required>
              <option value="NGN">NGN</option>
              <option value="USD">USD</option>
              <option value="EUR">EUR</option>
              <option value="GHS">GHS</option>
              <option value="KES">KES</option>
              <option value="UGX">UGX</option>
              <option value="TZS">TZS</option>
              <option value="ZMW">ZMW</option>
            </select>                 
            </div>
                    </div>
                    
    <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Transfer Limit (with PIN)</label>
                  <div class="col-sm-9">
                  <input type="number" name="tlimit" type="text" class="form-control" placeholder="Transfer Limit with Staff Pin" required/>
                  </div>
                  </div>
                    
    <div class="form-group">
		                  <label for="" class="col-sm-3 control-label" style="color:blue;">Theme Color</label>
		                  <div class="col-sm-9">
						<select name="theme_color" class="form-control">
						    <option value="" selected>Default</option>
							<option value="black">Black</option>
							<option value="blue">Blue</option>
							<option value="green">Green</option>
							<option value="red">Red</option>
              <option value="orange">Orange</option>
							<option value="yellow">Yellow</option>
							<option value="white">white</option>
							<option value="purple">Purple</option>
						</select>                 
						</div>
		                </div>
		                
	<div class="form-group">
		                  <label for="" class="col-sm-3 control-label" style="color:blue;">Alternate Color</label>
		                  <div class="col-sm-9">
						<select name="alternate_color" class="form-control">
							<option value="" selected>Default</option>
							<option value="black">Black</option>
							<option value="blue">Blue</option>
							<option value="green">Green</option>
							<option value="red">Red</option>
              <option value="orange">Orange</option>
							<option value="yellow">Yellow</option>
							<option value="white">white</option>
							<option value="purple">Purple</option>
						</select>                 
						</div>
		                </div>
		                
		<div class="form-group">
		                  <label for="" class="col-sm-3 control-label" style="color:blue;">Login Background</label>
		                  <div class="col-sm-9">
						<select name="login_background" class="form-control">
							<option value="" selected>Default</option>
							<option value="black">Black</option>
							<option value="blue">Blue</option>
							<option value="green">Green</option>
							<option value="red">Red</option>
              <option value="orange">Orange</option>
							<option value="yellow">Yellow</option>
							<option value="white">white</option>
							<option value="purple">Purple</option>
						</select>                 
						</div>
		                </div>
		                
    <div class="form-group">
		                  <label for="" class="col-sm-3 control-label" style="color:blue;">Login Action Color</label>
		                  <div class="col-sm-9">
						<select name="login_bottoncolor" class="form-control">
							<option value="" selected>Default</option>
							<option value="black">Black</option>
							<option value="blue">Blue</option>
							<option value="green">Green</option>
							<option value="red">Red</option>
              <option value="orange">Orange</option>
							<option value="yellow">Yellow</option>
							<option value="white">white</option>
							<option value="purple">Purple</option>
						</select>                 
						</div>
		                </div>
		                
		<div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Remita Merchant ID</label>
                  <div class="col-sm-9">
                  <input type="text" name="remita_merchantid" type="text" class="form-control" placeholder="Remita Merchant ID"/>
                  </div>
                  </div>
                  
        <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Remita Api Key</label>
                  <div class="col-sm-9">
                  <input type="text" name="remita_apikey" type="text" class="form-control" placeholder="Remita API Key"/>
                  </div>
                  </div>
                  
        <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Remita Service ID</label>
                  <div class="col-sm-9">
                  <input type="text" name="remita_serviceid" type="text" class="form-control" placeholder="Remita Service ID"/>
                  </div>
                  </div>
                  
        <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Remita API Token</label>
                  <div class="col-sm-9">
                  <input type="text" name="remita_apitoken" type="text" class="form-control" placeholder="Remita API Token"/>
                  </div>
                  </div>
                  
        <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">OTP Verification</label>
                  <div class="col-sm-9">
                  <select name="otp_option"  class="form-control select2" required>
                      <option value="" selected>Select Option</option>
                      <option value="Yes">Yes</option>
                      <option value="No">No</option>
                      <option value="Both">Both</option>
                    </select>
                    <span style="color: <?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>;"> <b>NOTE:</b> If OTP is set to <b>YES</b>, It will override Email Notification and vice-versa BUT if set to <b>BOTH</b> OTP and Email Notification will be Activated</span>
                  </div>
                  </div>
          
       </div>
       
        <div align="right">
              <div class="box-footer">
                        <button name="save" type="submit" class="btn bg-blue"><i class="fa fa-plus">&nbsp;Save</i></button>

              </div>
        </div>
       </form> 
<?php } ?>
              <!-- /.tab-pane -->
              
            </div>
              
<?php
}
elseif($tab == 'tab_4'){
    $insid = $_GET['idm'];
    $searchInst = mysqli_query($link, "SELECT * FROM institution_data WHERE institution_id = '$insid'");
    $fetchInst = mysqli_fetch_array($searchInst);
    $apiKey = $fetchInst['api_key'];
    $activationKey = $fetchInst['activationKey'];
?>

    <div class="tab-pane <?php echo ($_GET['tab'] == 'tab_4') ? 'active' : ''; ?>" id="tab_4">

<?php
if($apiKey == "" || $activationKey == "")
{
?>
       
      <form class="form-horizontal" method="post" enctype="multipart/form-data">

<?php
if(isset($_POST['generateKey'])){
    
    $instIDE = $fetchInst['id'];
    $regDate = $fetchInst['reg_date'];
    
    $encoded = base64_encode($instIDE.'|'.$regDate.'|'.$insid);
    $myhash = hash("sha256",$encoded);
   
    $encoded3 = $instIDE.$insid.date("s");
    $activationKey = crc32($encoded3);
    
    mysqli_query($link, "UPDATE institution_data SET api_key = '$myhash', activationKey = '$activationKey' WHERE institution_id = '$insid'");
    
    echo "<div class='alert alert-success'>Key Generated Successfully!!</div>";
    echo "<script>window.location='instprofile_settings.php?id=".$_SESSION['tid']."&&idm=".$_GET['idm']."&&mid=".base64_encode("419")."&&tab=tab_4'; </script>";
    
}
?>

        <div class="box-body">
            
            <button name="generateKey" type="submit" class="btn bg-blue"><i class="fa fa-refresh">&nbsp;Generate Key</i></button>
            
        </div>

      </form> 
      
<?php
}
else{
?>

      <form class="form-horizontal" method="post" enctype="multipart/form-data">
        
        <div class="form-group">
            <label for="" class="col-sm-2 control-label" style="color:blue;">API Key</label>
            <div class="col-sm-6">
              <input name="apiKey" type="text" class="form-control" value="<?php echo $apiKey; ?>" readonly="readonly"/>
            </div>
        </div>
          
        <div class="form-group">
          <label for="" class="col-sm-2 control-label" style="color: blue;">Activation Key</label>
          <div class="col-sm-6">
            <input name="activationPin" type="text" class="form-control" value="<?php echo $activationKey; ?>" readonly="readonly"/>
          </div>
        </div>

        <div class="form-group">
          <label for="" class="col-sm-2 control-label" style="color: blue;"></label>
          <div class="col-sm-6">
            <button name="reGenerateKey" type="submit" class="btn bg-blue"><i class="fa fa-refresh">&nbsp;Re-generate Key</i></button>
            </div>
        </div>

<?php
if(isset($_POST['reGenerateKey'])){

    $instIDE = $fetchInst['id'];
    $regDate = $fetchInst['reg_date'];
    
    $encoded = base64_encode($instIDE.'|'.$regDate.'|'.$insid).time();
    $myhash = hash("sha256",$encoded);
   
    $encoded3 = $instIDE.$insid.date("s");
    $activationKey = crc32($encoded3);
    
    mysqli_query($link, "UPDATE institution_data SET api_key = '$myhash', activationKey = '$activationKey' WHERE institution_id = '$insid'");
    
    echo "<div class='alert alert-success'>Key Generated Successfully!!</div>";
    echo "<script>window.location='instprofile_settings.php?id=".$_SESSION['tid']."&&idm=".$_GET['idm']."&&mid=".base64_encode("419")."&&tab=tab_4'; </script>";

}
?>

      </form> 


<?php } ?>

    </div>
    
    <!-- /.tab-pane -->

<?php
  }
  elseif($tab == 'tab_5')
  {
  ?>
              <div class="tab-pane <?php echo ($_GET['tab'] == 'tab_5') ? 'active' : ''; ?>" id="tab_5">

<?php
if(isset($_POST['update_token']))
{
  $insid = $_GET['idm'];
  $product_name = mysqli_real_escape_string($link, $_POST['product_name']);
  $product_url =  mysqli_real_escape_string($link, $_POST['product_url']);
  $logo_url =  mysqli_real_escape_string($link, $_POST['logo_url']);
  $support_email =  mysqli_real_escape_string($link, $_POST['support_email']);
  $live_chat =  mysqli_real_escape_string($link, $_POST['live_chat']);
  $email_from =  mysqli_real_escape_string($link, $_POST['email_from']);
  $email_sender_name =  mysqli_real_escape_string($link, $_POST['email_sender_name']);
  $company_address =  mysqli_real_escape_string($link, $_POST['company_address']);
  $brand_color =  mysqli_real_escape_string($link, $_POST['brand_color']);
  $status =  mysqli_real_escape_string($link, $_POST['status']);

  $insert = mysqli_query($link, "UPDATE email_config SET product_name = '$product_name', product_url = '$product_url', logo_url = '$logo_url', support_email = '$support_email', live_chat = '$live_chat', email_from = '$email_from', email_sender_name = '$email_sender_name', company_address = '$company_address', brand_color = '$brand_color', status = '$status' WHERE companyid = '$insid'") or die ("Error: " . mysqli_error($link));
  
  echo "<div class='alert alert-success'>Setup Updated Successfully!</div>";

}
?>
             <div class="box-body">

<?php
$insid = $_GET['idm'];
$search_others = mysqli_query($link, "SELECT * FROM email_config WHERE companyid = '$insid'");
if(mysqli_num_rows($search_others) == 1){
$rows_other = mysqli_fetch_array($search_others);
?>
            <form class="form-horizontal" method="post" enctype="multipart/form-data">

            <div class="box-body">

            <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Product Name</label>
                  <div class="col-sm-10">
                  <input name="product_name" type="text" class="form-control" placeholder="Product Name" value="<?php echo $rows_other['product_name']; ?>" required>
                  </div>
                  </div>

            <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Product URL</label>
                  <div class="col-sm-10">
                  <input name="product_url" type="text" class="form-control" placeholder="Product URL" value="<?php echo $rows_other['product_url']; ?>" required>
                  </div>
                  </div>

            <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Logo URL</label>
                  <div class="col-sm-10">
                  <input name="logo_url" type="text" class="form-control" placeholder="Logo URL" value="<?php echo $rows_other['logo_url']; ?>" required>
                  </div>
                  </div>

            <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Support Email</label>
                  <div class="col-sm-10">
                  <input name="support_email" type="text" class="form-control" placeholder="Support Email" value="<?php echo $rows_other['support_email']; ?>" required>
                  </div>
                  </div>

            <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Live Chat URL</label>
                  <div class="col-sm-10">
                  <input name="live_chat" type="text" class="form-control" placeholder="Live Chat URL" value="<?php echo $rows_other['live_chat']; ?>" required>
                  </div>
                  </div>

            <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">From</label>
                  <div class="col-sm-10">
                  <input name="email_from" type="text" class="form-control" placeholder="Sender Email" value="<?php echo $rows_other['email_from']; ?>" required>
                  </div>
                  </div>

            <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Sender Name</label>
                  <div class="col-sm-10">
                  <input name="email_sender_name" type="text" class="form-control" placeholder="Sender Name" value="<?php echo $rows_other['email_sender_name']; ?>" required>
                  </div>
                  </div>

            <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Company Address</label>
                  <div class="col-sm-10">
                  <input name="company_address" type="text" class="form-control" placeholder="Company Address" value="<?php echo $rows_other['company_address']; ?>" required>
                  </div>
                  </div>

            <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Brand Color</label>
                  <div class="col-sm-10">
                  <select name="brand_color" class="form-control select2" required style="width:100%">
                    <option value="<?php echo $rows_other['brand_color']; ?>" selected><?php echo $rows_other['brand_color']; ?></option>
                    <option value="blue">blue</option>
                    <option value="green">green</option>
                    <option value="red">red</option>
                    <option value="orange">orange</option>
                  </select>
                  </div>
                  </div>

            <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Status</label>
                  <div class="col-sm-10">
                  <select name="status" class="form-control select2" required style="width:100%">
                    <option value="<?php echo $rows_other['status']; ?>" selected><?php echo $rows_other['status']; ?></option>
                    <option value="NotActivated">NotActivated</option>
                    <option value="Activated">Activated</option>
                  </select>
                  </div>
                  </div>
			
			 </div>
			 
			  <div align="right">
              <div class="box-footer">
                	<button name="update_token" type="submit" class="btn bg-blue btn-flat"><i class="fa fa-save">&nbsp;Update</i></button>
              </div>
			  </div>
			  
			 </form>
<?php
}
else{
?>
      <form class="form-horizontal" method="post" enctype="multipart/form-data">
<?php
if(isset($_POST['save_token']))
{
  $insid = $_GET['idm'];
  $product_name = mysqli_real_escape_string($link, $_POST['product_name']);
  $product_url =  mysqli_real_escape_string($link, $_POST['product_url']);
  $logo_url =  mysqli_real_escape_string($link, $_POST['logo_url']);
  $support_email =  mysqli_real_escape_string($link, $_POST['support_email']);
  $live_chat =  mysqli_real_escape_string($link, $_POST['live_chat']);
  $email_from =  mysqli_real_escape_string($link, $_POST['email_from']);
  $email_sender_name =  mysqli_real_escape_string($link, $_POST['email_sender_name']);
  $company_address =  mysqli_real_escape_string($link, $_POST['company_address']);
  $brand_color =  mysqli_real_escape_string($link, $_POST['brand_color']);

  $insert = mysqli_query($link, "INSERT INTO email_config VALUES(null,'$insid','$product_name','$product_url','$logo_url','$support_email','$live_chat','$email_from','$email_sender_name','$company_address','$brand_color','Activated')") or die ("Error: " . mysqli_error($link));
  
  echo "<div class='alert alert-success'>Setup Saved Successfully!</div>";
}
?>
             <div class="box-body">

             <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Product Name</label>
                  <div class="col-sm-10">
                  <input name="product_name" type="text" class="form-control" placeholder="Product Name" required>
                  </div>
                  </div>

            <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Product URL</label>
                  <div class="col-sm-10">
                  <input name="product_url" type="text" class="form-control" placeholder="Product URL" required>
                  </div>
                  </div>

            <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Logo URL</label>
                  <div class="col-sm-10">
                  <input name="logo_url" type="text" class="form-control" placeholder="Logo URL" required>
                  </div>
                  </div>

            <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Support Email</label>
                  <div class="col-sm-10">
                  <input name="support_email" type="text" class="form-control" placeholder="Support Email" required>
                  </div>
                  </div>

            <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Live Chat URL</label>
                  <div class="col-sm-10">
                  <input name="live_chat" type="text" class="form-control" placeholder="Live Chat URL" required>
                  </div>
                  </div>

            <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">From</label>
                  <div class="col-sm-10">
                  <input name="email_from" type="text" class="form-control" placeholder="Sender Email" required>
                  </div>
                  </div>

            <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Sender Name</label>
                  <div class="col-sm-10">
                  <input name="email_sender_name" type="text" class="form-control" placeholder="Sender Name" required>
                  </div>
                  </div>

            <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Company Address</label>
                  <div class="col-sm-10">
                  <input name="company_address" type="text" class="form-control" placeholder="Company Address" required>
                  </div>
                  </div>

            <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Brand Color</label>
                  <div class="col-sm-10">
                  <select name="brand_color" class="form-control select2" required style="width:100%">
                    <option value="" selected>Select Brand Color</option>
                    <option value="blue">blue</option>
                    <option value="green">green</option>
                    <option value="red">red</option>
                    <option value="orange">orange</option>
                  </select>
                  </div>
                  </div>
			
			 </div>
			 
			  <div align="right">
              <div class="box-footer">
                	<button name="save_token" type="submit" class="btn bg-blue btn-flat"><i class="fa fa-save">&nbsp;Save</i></button>
              </div>
			  </div>

       </form>
<?php
}
?>

    </div>
    
    <!-- /.tab-pane -->

    <?php
  }
  elseif($tab == 'tab_6')
  {
  ?>
              <div class="tab-pane <?php echo ($_GET['tab'] == 'tab_6') ? 'active' : ''; ?>" id="tab_6">

<?php
if(isset($_POST['update_widget']))
{
  $insid = $_GET['idm'];
  $livechat_widget = base64_encode($_POST['livechat_widget']);
  $status =  mysqli_real_escape_string($link, $_POST['status']);

  $insert = mysqli_query($link, "UPDATE dedicated_livechat_widget SET livechat_widget = '$livechat_widget', status = '$status' WHERE companyid = '$insid'") or die ("Error: " . mysqli_error($link));
  
  echo "<div class='alert alert-success'>Setup Updated Successfully!</div>";

}
?>
             <div class="box-body">

<?php
$insid = $_GET['idm'];
$search_others = mysqli_query($link, "SELECT * FROM dedicated_livechat_widget WHERE companyid = '$insid'");
if(mysqli_num_rows($search_others) == 1){
$rows_other = mysqli_fetch_array($search_others);
?>
            <form class="form-horizontal" method="post" enctype="multipart/form-data">

            <div class="box-body">

            <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Livechat Widget</label>
                  <div class="col-sm-10">
                  <textarea name="livechat_widget"  class="form-control" rows="8" cols="80" required><?php echo base64_decode($rows_other['livechat_widget']); ?></textarea>
                  </div>
                  </div>

            <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Status</label>
                  <div class="col-sm-10">
                  <select name="status" class="form-control select2" required style="width:100%">
                    <option value="<?php echo $rows_other['status']; ?>" selected><?php echo $rows_other['status']; ?></option>
                    <option value="NotActivated">NotActivated</option>
                    <option value="Activated">Activated</option>
                  </select>
                  </div>
                  </div>
			
			 </div>
			 
			  <div align="right">
              <div class="box-footer">
                	<button name="update_widget" type="submit" class="btn bg-blue btn-flat"><i class="fa fa-save">&nbsp;Update</i></button>
              </div>
			  </div>
			  
			 </form>
<?php
}
else{
?>
      <form class="form-horizontal" method="post" enctype="multipart/form-data">
<?php
if(isset($_POST['save_widget']))
{
  $insid = $_GET['idm'];
  $livechat_widget = base64_encode($_POST['livechat_widget']);

  $insert = mysqli_query($link, "INSERT INTO dedicated_livechat_widget VALUES(null,'$insid','$livechat_widget','Activated')") or die ("Error: " . mysqli_error($link));
  
  echo "<div class='alert alert-success'>Setup Saved Successfully!</div>";
}
?>
             <div class="box-body">

             <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Livechat Widget</label>
                  <div class="col-sm-10">
                  <textarea name="livechat_widget"  class="form-control" rows="8" cols="80" required></textarea>
                  </div>
                  </div>
			
			      </div>
			 
			  <div align="right">
              <div class="box-footer">
                	<button name="save_widget" type="submit" class="btn bg-blue btn-flat"><i class="fa fa-save">&nbsp;Save</i></button>
              </div>
			  </div>

       </form>
<?php
}
?>

    </div>
    
    <!-- /.tab-pane -->

    <?php
  }
  elseif($tab == 'tab_7')
  {
  ?>
              <div class="tab-pane <?php echo ($_GET['tab'] == 'tab_7') ? 'active' : ''; ?>" id="tab_7">

<?php
if(isset($_POST['update_ReqKYC']))
{
  $insid = $_GET['idm'];
  $bvn =  mysqli_real_escape_string($link, $_POST['bvn']);
  $ValidID =  mysqli_real_escape_string($link, $_POST['ValidID']);
  $UtilityBills =  mysqli_real_escape_string($link, $_POST['UtilityBills']);
  $Signature =  mysqli_real_escape_string($link, $_POST['Signature']);
  $biodata =  mysqli_real_escape_string($link, $_POST['biodata']);
  $nok =  mysqli_real_escape_string($link, $_POST['nok']);
  $occupation =  mysqli_real_escape_string($link, $_POST['occupation']);

  $insert = mysqli_query($link, "UPDATE required_kyc SET bvn = '$bvn', ValidID = '$ValidID', UtilityBills = '$UtilityBills', mySignature = '$Signature', biodata = '$biodata', nok = '$nok', occupation = '$occupation' WHERE companyid = '$insid'") or die ("Error: " . mysqli_error($link));
  
  echo "<div class='alert alert-success'>Setup Updated Successfully!</div>";

}
?>
             <div class="box-body">

<?php
$insid = $_GET['idm'];
$search_otherskyc = mysqli_query($link, "SELECT * FROM required_kyc WHERE companyid = '$insid'");
if(mysqli_num_rows($search_otherskyc) == 1){
$rows_otherkyc = mysqli_fetch_array($search_otherskyc);
?>
            <form class="form-horizontal" method="post" enctype="multipart/form-data">

            <div class="box-body">

            <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">BVN</label>
                  <div class="col-sm-10">
                  <select name="bvn" class="form-control select2" required style="width:100%">
                    <option value="<?php echo $rows_otherkyc['bvn']; ?>" selected><?php echo $rows_otherkyc['bvn']; ?></option>
                    <option value="Required">Required</option>
                    <option value="Optional">Optional</option>
                  </select>
                  </div>
                  </div>


                  <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">ValidID</label>
                  <div class="col-sm-10">
                  <select name="ValidID" class="form-control select2" required style="width:100%">
                    <option value="<?php echo $rows_otherkyc['ValidID']; ?>" selected><?php echo $rows_otherkyc['ValidID']; ?></option>
                    <option value="Required">Required</option>
                    <option value="Optional">Optional</option>
                  </select>
                  </div>
                  </div>
                  

                  <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">UtilityBills</label>
                  <div class="col-sm-10">
                  <select name="UtilityBills" class="form-control select2" required style="width:100%">
                    <option value="<?php echo $rows_otherkyc['UtilityBills']; ?>" selected><?php echo $rows_otherkyc['UtilityBills']; ?></option>
                    <option value="Required">Required</option>
                    <option value="Optional">Optional</option>
                  </select>
                  </div>
                  </div>

                  
                  <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Signature</label>
                  <div class="col-sm-10">
                  <select name="Signature" class="form-control select2" required style="width:100%">
                    <option value="<?php echo $rows_otherkyc['mySignature']; ?>" selected><?php echo $rows_otherkyc['mySignature']; ?></option>
                    <option value="Required">Required</option>
                    <option value="Optional">Optional</option>
                  </select>
                  </div>
                  </div>

                  <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Biodata</label>
                  <div class="col-sm-10">
                  <select name="biodata" class="form-control select2" required style="width:100%">
                    <option value="<?php echo $rows_otherkyc['biodata']; ?>" selected><?php echo $rows_otherkyc['biodata']; ?></option>
                    <option value="Required">Required</option>
                    <option value="Optional">Optional</option>
                  </select>
                  </div>
                  </div>

                  <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Next of Kin</label>
                  <div class="col-sm-10">
                  <select name="nok" class="form-control select2" required style="width:100%">
                    <option value="<?php echo $rows_otherkyc['nok']; ?>" selected><?php echo $rows_otherkyc['nok']; ?></option>
                    <option value="Required">Required</option>
                    <option value="Optional">Optional</option>
                  </select>
                  </div>
                  </div>

                  <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Occupation</label>
                  <div class="col-sm-10">
                  <select name="occupation" class="form-control select2" required style="width:100%">
                    <option value="<?php echo $rows_otherkyc['occupation']; ?>" selected><?php echo $rows_otherkyc['occupation']; ?></option>
                    <option value="Required">Required</option>
                    <option value="Optional">Optional</option>
                  </select>
                  </div>
                  </div>
			
			 </div>
			 
			  <div align="right">
              <div class="box-footer">
                	<button name="update_ReqKYC" type="submit" class="btn bg-blue btn-flat"><i class="fa fa-save">&nbsp;Update</i></button>
              </div>
			  </div>
			  
			 </form>
<?php
}
else{
?>
      <form class="form-horizontal" method="post" enctype="multipart/form-data">
<?php
if(isset($_POST['save_ReqKYC']))
{
  $insid = $_GET['idm'];
  $bvn =  mysqli_real_escape_string($link, $_POST['bvn']);
  $ValidID =  mysqli_real_escape_string($link, $_POST['ValidID']);
  $UtilityBills =  mysqli_real_escape_string($link, $_POST['UtilityBills']);
  $Signature =  mysqli_real_escape_string($link, $_POST['Signature']);
  $biodata =  mysqli_real_escape_string($link, $_POST['biodata']);
  $nok =  mysqli_real_escape_string($link, $_POST['nok']);
  $occupation =  mysqli_real_escape_string($link, $_POST['occupation']);

  $insert = mysqli_query($link, "INSERT INTO required_kyc VALUES(null,'$insid','$bvn','$ValidID','$UtilityBills','$Signature','$biodata','$nok','$occupation')") or die ("Error: " . mysqli_error($link));
  
  echo "<div class='alert alert-success'>Setup Saved Successfully!</div>";
}
?>
             <div class="box-body">

             <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">BVN</label>
                  <div class="col-sm-10">
                  <select name="bvn" class="form-control select2" required style="width:100%">
                    <option value="" selected>Select Settings</option>
                    <option value="Required">Required</option>
                    <option value="Optional">Optional</option>
                  </select>
                  </div>
                  </div>

                  <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">ValidID</label>
                  <div class="col-sm-10">
                  <select name="ValidID" class="form-control select2" required style="width:100%">
                    <option value="" selected>Select Settings</option>
                    <option value="Required">Required</option>
                    <option value="Optional">Optional</option>
                  </select>
                  </div>
                  </div>
                  
                  <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">UtilityBills</label>
                  <div class="col-sm-10">
                  <select name="UtilityBills" class="form-control select2" required style="width:100%">
                    <option value="" selected>Select Settings</option>
                    <option value="Required">Required</option>
                    <option value="Optional">Optional</option>
                  </select>
                  </div>
                  </div>
                  
                  <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Signature</label>
                  <div class="col-sm-10">
                  <select name="Signature" class="form-control select2" required style="width:100%">
                    <option value="" selected>Select Settings</option>
                    <option value="Required">Required</option>
                    <option value="Optional">Optional</option>
                  </select>
                  </div>
                  </div>

                  <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Biodata</label>
                  <div class="col-sm-10">
                  <select name="biodata" class="form-control select2" required style="width:100%">
                    <option value="" selected>Select Settings</option>
                    <option value="Required">Required</option>
                    <option value="Optional">Optional</option>
                  </select>
                  </div>
                  </div>

                  <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Next of Kin</label>
                  <div class="col-sm-10">
                  <select name="nok" class="form-control select2" required style="width:100%">
                    <option value="" selected>Select Settings</option>
                    <option value="Required">Required</option>
                    <option value="Optional">Optional</option>
                  </select>
                  </div>
                  </div>

                  <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Occupation</label>
                  <div class="col-sm-10">
                  <select name="occupation" class="form-control select2" required style="width:100%">
                    <option value="" selected>Select Settings</option>
                    <option value="Required">Required</option>
                    <option value="Optional">Optional</option>
                  </select>
                  </div>
                  </div>
			
			      </div>
			 
			  <div align="right">
              <div class="box-footer">
                	<button name="save_ReqKYC" type="submit" class="btn bg-blue btn-flat"><i class="fa fa-save">&nbsp;Save</i></button>
              </div>
			  </div>

       </form>
<?php
}
?>

    </div>
    
    <!-- /.tab-pane -->
    
<?php } } ?>
            <!-- /.tab-content -->
      
          </div>          
      </div>
    
              </div>
  
</div>  
</div>
</div>
</section>  
</div>