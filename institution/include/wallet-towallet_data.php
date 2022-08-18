<div class="row">
	      <section class="content">
       <div class="box box-default">
           <div class="box-body">
             <div class="table-responsive"> 
            <div class="box-body">
<button type="button" class="btn btn-flat bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>" align="left" disabled>&nbsp;<b>Transfer Wallet:</b>&nbsp;
    <strong class="alert bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">
    <?php
    echo $icurrency.number_format($itransfer_balance,2,'.',',');
    ?> 
    </strong>
</button>

<hr>
<div class="slideshow-container">
<div class="alert bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>" style="font-size:17px;" align="center">
    <p>
        <?php
        //echo $walletafrica_status;
        if($mo_virtualacct_status == "Active" && ($walletafrica_status == "NotActive" || $walletafrica_status == "Active")){
            
            include("../config/monnify_virtualaccount.php");
            
        }
        elseif($mo_virtualacct_status == "NotActive" && $walletafrica_status == "Active"){
            
            include("../config/walletafrica_restfulapis_call.php");
            include("walletafrica_virtulaccount.php");
            
        }
        ?>
    </p>
</div>

<a class="myprev" onclick="plusSlides(-1)">&#10094;</a>
<a class="mynext" onclick="plusSlides(1)">&#10095;</a>
</div>
</hr>
  
            <div class="col-md-14">
            <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
             <li <?php echo ($_GET['tab'] == 'tab_1') ? "class='active'" : ''; ?>><a href="wallet-towallet.php?id=<?php echo $_SESSION['tid']; ?>&&mid=NDA0&&tab=tab_1">Fund Customer Wallet</a></li>

             <li <?php echo ($_GET['tab'] == 'tab_2') ? "class='active'" : ''; ?>><a href="wallet-towallet.php?id=<?php echo $_SESSION['tid']; ?>&&mid=NDA0&&tab=tab_2">Fund Other Wallet</a></li>
            
             <!--<li <?php //echo ($_GET['tab'] == 'tab_3') ? "class='active'" : ''; ?>><?php echo ($isubagent_wallet == "Enabled" && $irole != 'tqwjr_product_marketer') ? '<a href="wallet-towallet.php?id='.$_SESSION['tid'].'&&mid=NDA0&&tab=tab_3">Fund Sub-agent Wallet</a>' : ''; ?></li>-->
             
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
           
			 <form class="form-horizontal" method="post" enctype="multipart/form-data">

<?php
if(isset($_POST['csave']))
{
  $ptype = "p2p-transfer";
  $account =  mysqli_real_escape_string($link, $_POST['author']);
  $amount = preg_replace('/[^0-9.]/', '', mysqli_real_escape_string($link, $_POST['amount_tofund']));
  $remark = mysqli_real_escape_string($link, $_POST['remark']);
  //$remark .= "<br><b>Posted by:<br>".$name.'</b>';
  $date_time = date("Y-m-d");
  $final_date_time = date ('Y-m-d h:i:s');
  $txid = 'EA-p2pFunding-'.time();

  $search_memset = mysqli_query($link, "SELECT * FROM member_settings WHERE companyid = '$institution_id'");
  $fetch_memset = mysqli_fetch_array($search_memset);
  $sysabb = $fetch_memset['sender_id'];
  //$sys_abb = $get_sys['abb'];
  
  $otp_code = ($iotp_option == "Yes" || $iotp_option == "Both") ? substr((uniqid(rand(),1)),3,6) : $myiepin;
    
  $otpChecker = ($iotp_option == "Yes" || $iotp_option == "Both") ? "otp" : "pin";
  
  $sms = "$sysabb>>>Dear $iname! Your One Time Password is $otp_code";
  
  $sms_refid = uniqid("EA-smsCharges-").time();
  $sms_rate = $fetchsys_config['fax'];
  $imywallet_balance = $iassigned_walletbal - $sms_rate;
  $currenctdate = date("Y-m-d H:i:s");

  $searchVirtualAcct = mysqli_query($link, "SELECT * FROM virtual_account WHERE account_number = '$account'");
  $fetchVirtualAcct = mysqli_fetch_array($searchVirtualAcct);
  $userAcct = $fetchVirtualAcct['userid'];
  
  //Receivers Details
  $search_cbalance = mysqli_query($link, "SELECT * FROM borrowers WHERE account = '$userAcct'");
  $fetch_cbalance = mysqli_fetch_object($search_cbalance);
  $cust_wallet_balance = $fetch_cbalance->wallet_balance;
  $ph = $fetch_cbalance->phone;
  $em = $fetch_cbalance->email;
  $myname = $fetch_cbalance->lname.' '.$fetch_cbalance->fname.' '.$fetch_cbalance->mname; 

  //Sender Phone Number
  $phone = $myiphone;

  $debitWAllet = ($getSMS_ProviderNum == 1 || ($maintenance_row == 1 && $billing_type == "PAYGException")) ? "No" : "Yes"; 
  
  //Data Parser (array size = 10)
  $mydata = $txid."|".$account."|".$amount."|".$ptype."|".$remark."|".$final_date_time."|".$ph."|".$em."|".$myname."|".$userAcct;

  $key = base64_encode($mydata);
  
  if($amount <= 0){
      
      echo "<div class='alert bg-orange'>Oops! Amount Entered is not Valid!!</div>";
      
  }
  elseif($active_status == "Suspended"){
        
    echo "<div class='alert bg-orange'>Oops! You are unable to make transfer at the moment!!</div>";
    
}
  elseif(($itransfer_balance < $amount && ($iotp_option == "Yes" || $iotp_option == "Both")) || ($itransfer_balance < $amount && $iotp_option == "No")){
      
      echo "<div class='alert bg-orange'>Oops! You have insufficient fund in your Wallet!!</div>";
      
  }
  elseif($iassigned_walletbal < ($fetchsys_config['fax']) && ($iotp_option == "Yes" || $iotp_option == "Both")){
      
      echo "<div class='alert bg-orange'>Oops! You have insufficient fund in your Wallet to Receive OTP!!</div>";
      
  }
  elseif($amount > $itransferLimitPerTrans){

    echo "<div class='alert bg-orange'>Sorry! You can not transfer more than ".$icurrency.number_format($itransferLimitPerTrans,2,'.',',')." at once!!</div>";

  }
  elseif($imyDailyTransferLimit == $itransferLimitPerDay || (($amount + $imyDailyTransferLimit) > $itransferLimitPerDay)){

        echo "<div class='alert bg-orange'>Oops! You have reached your daily limit of ".$icurrency.number_format($itransferLimitPerDay,2,'.',',')."</div>";

  }
  elseif($ivirtual_acctno === "$account" || $iuid === "$userAcct"){
        
    mysqli_query($link, "UPDATE user SET status = 'Suspended' WHERE id = '$iuid'");
    mysqli_query($link, "INSERT INTO wallet_case VALUES(null,'$institution_id','$isbranchid','System','$iuid','Suspended','Frudulent Act Detected','$currenctdate')");

    echo "<div class='alert bg-orange'>Oops! Request Failed!!</div>";
        
  }
  else{
      
      $update = mysqli_query($link,"INSERT INTO otp_confirmation VALUES(null,'$iuid','$otp_code','$mydata','Pending','$currenctdate')") or die(mysqli_error());
      
      if(!$update)
      {
          echo "<div class='alert bg-orange'>Unable to Transfer Fund.....Please try again later</div>";
      }
      else{
          
          ($iotp_option == "No" ? "" : ($debitWAllet == "No" ? $sendSMS->instGeneralAlert($ozeki_password, $ozeki_url, $isenderid, $myiphone, $sms, $institution_id, $sms_refid, $sms_rate, $iuid, $imywallet_balance, $debitWallet) : ($debitWAllet == "Yes" && $sms_rate <= $iwallet_balance ? $sendSMS->instGeneralAlert($ozeki_password, $ozeki_url, $isenderid, $myiphone, $sms, $institution_id, $sms_refid, $sms_rate, $iuid, $imywallet_balance, $debitWallet) : "")));
          echo ($otp_option == "Yes" || $otp_option == "Both") ? "<div align='center'><img src='".$fetchsys_config['file_baseurl']."checkmark.gif'><p style='color: #38A1F3;'>Great! Otp has been sent to your mobile phone to complete this transaction!!</p></div>" : "<div align='center'><img src='".$fetchsys_config['file_baseurl']."checkmark.gif'><p style='color: #38A1F3;'>Transfer Request Received Successfully! Wait a moment to complete the transaction with your Pin</p></div>";
          echo '<meta http-equiv="refresh" content="2;url=wallet-towallet.php?id='.$_SESSION['tid'].'&&mid=NDA0&&tab=tab_1&&key='.$key.'&&'.$otpChecker.'">';
        
      }
   
  }
  
}
?>




<?php
if (isset($_POST['confirm']))
{
    
    $myotp = $_POST['otp'];
    $key = base64_decode($_GET['key']);
    
    $search_memset = mysqli_query($link, "SELECT * FROM member_settings WHERE companyid = '$institution_id'");
    $fetch_memset = mysqli_fetch_array($search_memset);
				    
    $verify_otp = mysqli_query($link, "SELECT * FROM otp_confirmation WHERE otp_code = '$myotp' AND userid = '$iuid' AND status = 'Pending' AND data = '$key'");
    $fetch_data = mysqli_fetch_array($verify_otp);
    $otpnum = mysqli_num_rows($verify_otp);

    $verify_otp1 = mysqli_query($link, "SELECT * FROM otp_confirmation WHERE otp_code = '$myotp' AND userid = '$iuid' AND status = 'Pending'");
	  $otpnum1 = mysqli_num_rows($verify_otp1);
    
    if($otpnum1 > 1){
		
      mysqli_query($link, "DELETE FROM otp_confirmation WHERE userid = '$iuid' AND otp_code = '$myotp' AND status = 'Pending'");
          
        echo "<div class='alert bg-orange'>Duplicate Transaction is not allowed!!</div>";
        echo '<meta http-equiv="refresh" content="2;url=wallet-towallet.php?id='.$_SESSION['tid'].'&&mid=NDA0&&tab=tab_1">';
  
    }
    elseif($otpnum == 0 && $otpnum1 == "1"){
    
        echo "<div class='alert bg-orange'>Opps!...Invalid Credential!!</div>";
    
    }
    else{

	      $concat = $fetch_data['data'];
    
        $datetime = $fetch_data['datetime'];
                                
        $parameter = (explode('|',$concat));
                                
        $txid = $parameter[0];
        $account = $parameter[1];
        $amount = $parameter[2];
        $ptype = $parameter[3];
        $remark = $parameter[4];
        $final_date_time = $parameter[5];
        $ph = $parameter[6];
        $em = $parameter[7];
        $myname = $parameter[8];
        $userAcct= $parameter[9];
        $phone = $ph;

        if($itransfer_balance < $amount){
            
            mysqli_query($link, "DELETE FROM otp_confirmation WHERE userid = '$acctno' AND otp_code = '$myotp' AND status = 'Pending'");
            
            echo "<div class='alert alert-danger'>Oops! You have insufficient fund in your Wallet!!</div>";
            echo '<meta http-equiv="refresh" content="2;url=wallet-towallet.php?id='.$_SESSION['tid'].'&&mid=NDA0&&tab=tab_1">';
            
        }else{

          //Fetch transfer receiver
          $search_receiver = mysqli_query($link, "SELECT * FROM borrowers WHERE virtual_acctno = '$account'");
          $fetch_receiver = mysqli_fetch_array($search_receiver);
          $receiver_virtual_number = $fetch_receiver['virtual_number'];
          $customer_acctid = $fetch_receiver['account'];
          $receiver_balance = $fetch_receiver['wallet_balance'];
              
          //Sender Parameters
          $amountDebited = $amount;
          $senderBalance = $itransfer_balance - $amount;

          $update = mysqli_query($link, "UPDATE user SET transfer_balance = '$senderBalance' WHERE id = '$iuid'");

          //Receivers Parameters
          $amountCredited = $amount;
          $receiverBalance = $receiver_balance + $amount;

          $update = mysqli_query($link, "UPDATE borrowers SET wallet_balance = '$receiverBalance' WHERE account = '$userAcct'") or die (mysqli_error($link));
                  
          $sms_rate = $fetchsys_config['fax'];
          $refid = uniqid("EA-smsCharges-").time();
          $sysabb = $fetch_memset['sender_id'];

          $debitWAllet = ($getSMS_ProviderNum == 1 || ($maintenance_row == 1 && $billing_type == "PAYGException")) ? "No" : "Yes"; 
                  
          $sms = "$sysabb>>>CR";
          $sms .= " Amt: ".$icurrency.$amountCredited."";
          $sms .= " Acc: ".ccMasking($account)."";
          $sms .= " Desc: ".substr($remark,0,20)." - | ".$txid."";
          $sms .= " Time: ".$final_date_time."";
          $sms .= " Bal: ".$icurrency.number_format($receiverBalance,2,'.',',')."";
                  
          $max_per_page = 153;
          $sms_length = strlen($sms);
          $calc_length = ceil($sms_length / $max_per_page);
                    
          $sms_charges = $calc_length * $sms_rate;
          $merchantName = $inst_name;
          $senderName = $iname;
          $senderAccount = $ivirtual_acctno;
          $myRemainingBalance = $senderBalance - $sms_charges;
          $senderEmail = $myiemail_addrs;
                  
          $insert = mysqli_query($link, "INSERT INTO wallet_history VALUES(null,'$institution_id','$txid','$customer_acctid','','$amountCredited','Debit','$icurrency','$ptype','$remark','successful','$final_date_time','$iuid','$senderBalance','$receiverBalance')") or die (mysqli_error($link));
                        
          $userType = "user";
          ($debitWAllet == "No" ? $sendSMS->userGeneralAlert($ozeki_password, $ozeki_url, $sysabb, $phone, $sms, $institution_id, $refid, $sms_charges, $iuid, $myRemainingBalance, $debitWallet, $userType) : ($debitWAllet == "Yes" && $sms_charges <= $senderBalance ? $sendSMS->userGeneralAlert($ozeki_password, $ozeki_url, $sysabb, $phone, $sms, $institution_id, $refid, $sms_charges, $iuid, $myRemainingBalance, $debitWallet, $userType) : ""));
          $sendSMS->walletCreditEmailNotifier($em, $txid, $final_date_time, $inst_name, $myname, $account, $icurrency, $amount, $totalwallet_balance, $iemailConfigStatus, $ifetch_emailConfig);
          $sendSMS->walletDebitEmailNotifier($senderEmail, $txid, $senderName, $final_date_time, $inst_name, $senderAccount, $merchantName, $icurrency, $amount, $myRemainingBalance, $iemailConfigStatus, $ifetch_emailConfig);

          mysqli_query($link, "DELETE FROM otp_confirmation WHERE userid = '$iuid' AND otp_code = '$myotp' AND status = 'Pending'");

          echo "<div class='alert bg-blue'>Transaction Successful! Current Transfer Wallet Balance is: <b style='color: orange;'>".$icurrency.number_format($senderBalance,2,'.',',')."</b></div>";
          echo '<meta http-equiv="refresh" content="3;url=wallet-towallet.php?id='.$_SESSION['tid'].'&&mid=NDA0&&tab=tab_1">';

        }
	    
	}
    
}
?>



<?php
if(!(isset($_GET['otp']) || isset($_GET['pin'])))
{
?>

            <div class="box-body">
                
              <div class="form-group">
                <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Wallet Account No.</label>
                <div class="col-sm-6">
                    <select name="author"  class="form-control select2" required>
                      <option value="" selected>Select Customer Wallet Account</option>
                        <?php
                        ($individual_customer_records != "1" && $branch_customer_records != "1") ? $search = mysqli_query($link, "SELECT * FROM borrowers WHERE branchid = '$institution_id' AND virtual_acctno != '' ORDER BY id") or die (mysqli_error($link)) : "";
                        ($individual_customer_records === "1" && $branch_customer_records != "1") ? $search = mysqli_query($link, "SELECT * FROM borrowers WHERE branchid = '$institution_id' AND lofficer = '$iuid' AND virtual_acctno != '' ORDER BY id") or die (mysqli_error($link)) : "";
                        ($individual_customer_records != "1" && $branch_customer_records === "1") ? $search = mysqli_query($link, "SELECT * FROM borrowers WHERE branchid = '$institution_id' AND sbranchid = '$isbranchid' AND virtual_acctno != '' ORDER BY id") or die (mysqli_error($link)) : "";
                        while($get_search = mysqli_fetch_array($search))
                        {
                        ?>
                      <option value="<?php echo $get_search['virtual_acctno']; ?>"><?php echo $get_search['virtual_acctno']; ?>&nbsp; [<?php echo $get_search['fname']; ?>&nbsp;<?php echo $get_search['lname']; ?> : <?php echo $get_search['currency'].number_format($get_search['wallet_balance'],2,'.',','); ?>]</option>
                        <?php } ?>
                </select>
                </div>
                <label for="" class="col-sm-3 control-label"></label>
              </div>
              
              <div class="form-group">
                <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Amount to Fund</label>
                <div class="col-sm-6">
                    <input name="amount_tofund" type="number" class="form-control" placeholder="Enter Amount Here" required>
                </div>
                <label for="" class="col-sm-3 control-label"></label>
              </div>
              
              <div class="form-group">
                <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Remark</label>
                <div class="col-sm-6">
                    <textarea name="remark"  class="form-control" rows="2" cols="80" placeholder="e.g Manual Funding to Wallet from XXXXXX"></textarea>
                </div>
                <label for="" class="col-sm-3 control-label"></label>
              </div>

            </div>
            
            <div class="form-group" align="right">
                <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;"></label>
                <div class="col-sm-6">
                    <button name="csave" type="submit" onclick="var e=this;setTimeout(function(){e.disabled=true;},0);return true;" class="btn bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>"><i class="fa fa-forward">&nbsp;Transfer Fund</i></button>
                </div>
                <label for="" class="col-sm-3 control-label"></label>
            </div>

<?php
}
else{
    include("otp_confirmation.php");
}
?>

      
       </form>  

      </div>
    </div>
      <!-- /.tab-pane -->
  <?php
  }
  elseif($tab == 'tab_2')
  {
  ?>

      <div class="tab-pane <?php echo ($_GET['tab'] == 'tab_2') ? 'active' : ''; ?>" id="tab_2">

        <div class="box-body">
           
       <form class="form-horizontal" method="post" enctype="multipart/form-data">

<?php
if(isset($_POST['osavings']))
{
  $ptype = "p2p-transfer";
  $account =  mysqli_real_escape_string($link, $_POST['author']);
  $amount = preg_replace('/[^0-9.]/', '', mysqli_real_escape_string($link, $_POST['amount']));
  $remark = mysqli_real_escape_string($link, $_POST['remark']);
  //$remark .= "<br><b>Posted by:<br>".$name.'</b>';
  $date_time = date("Y-m-d");
  $final_date_time = date ('Y-m-d h:i:s');
  $txid = date("yd").time();

  $search_memset = mysqli_query($link, "SELECT * FROM member_settings WHERE companyid = '$institution_id'");
  $fetch_memset = mysqli_fetch_array($search_memset);
  $sysabb = $fetch_memset['sender_id'];
  //$sys_abb = $get_sys['abb'];
  
  $otp_code = ($iotp_option == "Yes" || $iotp_option == "Both") ? substr((uniqid(rand(),1)),3,6) : $myiepin;
    
  $otpChecker = ($iotp_option == "Yes" || $iotp_option == "Both") ? "otp" : "pin";
  
  $sms = "$sysabb>>>Dear $iname! Your One Time Password is $otp_code";
  
  $sms_refid = "EA-smsCharges-".time();
  $sms_rate = $fetchsys_config['fax'];
  $imywallet_balance = $iassigned_walletbal - $sms_rate;
  $currenctdate = date("Y-m-d H:i:s");

  $searchVirtualAcct = mysqli_query($link, "SELECT * FROM virtual_account WHERE account_number = '$account'");
  $fetchVirtualAcct = mysqli_fetch_array($searchVirtualAcct);
  $userAcct = $fetchVirtualAcct['userid'];
  
  //Receivers Details
  $search_cbalance = mysqli_query($link, "SELECT * FROM borrowers WHERE account = '$userAcct'");
  $fetch_cbalance = mysqli_fetch_array($search_cbalance);
  $mycnum = mysqli_num_rows($search_cbalance);
  $myccum_phone = $fetch_cbalance['phone'];
  $myccum_emil = $fetch_cbalance['email'];
  $myccum_fullname = $fetch_cbalance['fname'].' '.$fetch_cbalance['lname'].' '.$fetch_cbalance['mname'];
  $myccum_vnumber = $fetch_cbalance['virtual_number'];
  $myccum_acctid = $fetch_cbalance['account'];
  
  $search_myibalance = mysqli_query($link, "SELECT * FROM user WHERE id = '$userAcct'");
  $fetch_myibalance = mysqli_fetch_array($search_myibalance);
  $myinum = mysqli_num_rows($search_myibalance);
  $myi_phone = $fetch_myibalance['phone'];
  $myi_email = $fetch_myibalance['email'];
  $myi_name = $fetch_myibalance['name'].' '.$fetch_myibalance['lname'].' '.$fetch_myibalance['mname'];
  $myi_id = $fetch_myibalance['id'];
  
  //Detect Right Receiver
  $ph = ($mycnum == 1 && $myinum == 0) ? $myccum_phone : $myi_phone;
  $em = ($mycnum == 1 && $myinum == 0) ? $myccum_emil : $myi_email;
  $myname = ($mycnum == 1 && $myinum == 0) ? $myccum_fullname : $myi_name;
  $receiverVANo = ($mycnum == 1 && $myinum == 0) ? $myccum_vnumber : $myi_vnumber;
  $receiverAcctId = ($mycnum == 1 && $myinum == 0) ? $myccum_acctid : $myi_id;
  $receiverBalance = ($mycnum == 1 && $myinum == 0) ? $myccum_balance : $myi_balance;
  $detectRightReceiver = ($mycnum == 1 && $myinum == 0) ? "Customer" : "Institution";

  //Sender Phone Number
  $phone = $myiphone;

  $debitWAllet = ($getSMS_ProviderNum == 1 || ($maintenance_row == 1 && $billing_type == "PAYGException")) ? "No" : "Yes"; 
  
  //Data Parser (array size = 10)
  $mydata = $txid."|".$account."|".$amount."|".$ptype."|".$remark."|".$final_date_time."|".$ph."|".$em."|".$myname."|".$detectRightReceiver."|".$receiverAcctId;

  $key = base64_encode($mydata);

  if($amount <= 0){
      
      echo "<div class='alert bg-orange'>Oops! Amount Entered is not Valid!!</div>";
      
  }
  elseif($active_status == "Suspended"){
        
    echo "<div class='alert bg-orange'>Oops! You are unable to make transfer at the moment!!</div>";
    
}
  elseif(($itransfer_balance < $amount && ($iotp_option == "Yes" || $iotp_option == "Both")) || ($itransfer_balance < $amount && $iotp_option == "No")){
	    
      echo "<div class='alert bg-orange'>Oops! You have insufficient fund in your Wallet!!</div>";
      
  }
  elseif($iassigned_walletbal < $sms_rate && ($iotp_option == "Yes" || $iotp_option == "Both")){
      
      echo "<div class='alert bg-orange'>Oops! You have insufficient fund in your Wallet to Receive OTP!!</div>";
      
  }
  elseif($amount > $itransferLimitPerTrans){

    echo "<div class='alert bg-orange'>Sorry! You can not transfer more than ".$icurrency.number_format($itransferLimitPerTrans,2,'.',',')." at once!!</div>";

  }
  elseif($imyDailyTransferLimit == $itransferLimitPerDay || (($amount + $imyDailyTransferLimit) > $itransferLimitPerDay)){

      echo "<div class='alert bg-orange'>Oops! You have reached your daily limit of ".$icurrency.number_format($itransferLimitPerDay,2,'.',',')."</div>";

  }
  elseif($ivirtual_acctno === "$account" || $iuid === "$userAcct"){
      
      mysqli_query($link, "UPDATE user SET status = 'Suspended' WHERE id = '$iuid'");
      mysqli_query($link, "INSERT INTO wallet_case VALUES(null,'$institution_id','$isbranchid','System','$iuid','Suspended','Frudulent Act Detected','$currenctdate')");
      
      echo "<div class='alert bg-orange'>Oops! Request Failed!!</div>";
      
  }
  else{
      
      $insert = mysqli_query($link, "INSERT INTO otp_confirmation VALUES(null,'$iuid','$otp_code','$mydata','Pending','$currenctdate')");
      
      if(!$insert)
      {
          echo "<div class='alert bg-orange'>Unable to Transfer Fund.....Please try again later</div>".mysqli_error($link);
      }
      else{
          
        ($iotp_option == "No" ? "" : ($debitWAllet == "No" ? $sendSMS->instGeneralAlert($ozeki_password, $ozeki_url, $isenderid, $myiphone, $sms, $institution_id, $sms_refid, $sms_rate, $iuid, $imywallet_balance, $debitWallet) : ($debitWAllet == "Yes" && $sms_rate <= $iwallet_balance ? $sendSMS->instGeneralAlert($ozeki_password, $ozeki_url, $isenderid, $myiphone, $sms, $institution_id, $sms_refid, $sms_rate, $iuid, $imywallet_balance, $debitWallet) : "")));
        echo ($iotp_option == "Yes" || $iotp_option == "Both") ? "<div align='center'><img src='".$fetchsys_config['file_baseurl']."checkmark.gif'><p style='color: #38A1F3;'>Great! Otp has been sent to your mobile phone to complete this transaction!!</p></div>" : "<div align='center'><img src='".$fetchsys_config['file_baseurl']."checkmark.gif'><p style='color: #38A1F3;'>Transfer Request Received Successfully! Wait a moment to complete the transaction with your Pin</p></div>";
        echo '<meta http-equiv="refresh" content="2;url=wallet-towallet.php?id='.$_SESSION['tid'].'&&mid=NDA0&&tab=tab_2&&key='.$key.'&&'.$otpChecker.'">';
        
      }
   
  }
  
}
?>



<?php
if (isset($_POST['confirm']))
{
    
    $myotp = $_POST['otp'];
    $key = base64_decode($_GET['key']);

    $search_memset = mysqli_query($link, "SELECT * FROM member_settings WHERE companyid = '$institution_id'");
    $fetch_memset = mysqli_fetch_array($search_memset);
    
	  $verify_otp = mysqli_query($link, "SELECT * FROM otp_confirmation WHERE otp_code = '$myotp' AND userid = '$iuid' AND status = 'Pending' AND data = '$key'");
	  $fetch_data = mysqli_fetch_array($verify_otp);
	  $otpnum = mysqli_num_rows($verify_otp);

    $verify_otp1 = mysqli_query($link, "SELECT * FROM otp_confirmation WHERE otp_code = '$myotp' AND userid = '$iuid' AND status = 'Pending'");
	  $otpnum1 = mysqli_num_rows($verify_otp1);
    
    if($otpnum1 > 1){
		
        mysqli_query($link, "DELETE FROM otp_confirmation WHERE userid = '$iuid' AND otp_code = '$myotp' AND status = 'Pending'");
          
        echo "<div class='alert bg-orange'>Duplicate Transaction is not allowed!!</div>";
        echo '<meta http-equiv="refresh" content="2;url=transfer_fund.php?id='.$_SESSION['tid'].'&&mid=NDA0&&tab=tab_1">';
  
    }
    elseif($otpnum == 0 && $otpnum1 == "1"){
    
        echo "<div class='alert bg-orange'>Opps!...Invalid Credential!!</div>";
    
    }
    else{

	      $concat = $fetch_data['data'];
    
        $datetime = $fetch_data['datetime'];
                                
        $parameter = (explode('|',$concat));
                                
        $txid = $parameter[0];
        $account = $parameter[1];
        $amount = $parameter[2];
        $ptype = $parameter[3];
        $remark = $parameter[4];
        $final_date_time = $parameter[5];
        $phone = $parameter[6];
        $em = $parameter[7];
        $myname = $parameter[8];
        $detectRightReceiver = $parameter[9];
        $receiverAcctId = $parameter[10];

        //Receivers Details for Customer
        $search_cbalance = mysqli_query($link, "SELECT * FROM borrowers WHERE account = '$receiverAcctId'");
        $fetch_cbalance = mysqli_fetch_array($search_cbalance);
        $mycnum = mysqli_num_rows($search_cbalance);
        
        //Receivers Details for Institution
        $search_myibalance = mysqli_query($link, "SELECT * FROM user WHERE id = '$receiverAcctId'");
        $fetch_myibalance = mysqli_fetch_array($search_myibalance);
        $myinum = mysqli_num_rows($search_myibalance);
        
        $currentReceiverBalance = ($mycnum == 1 && $myinum == 0) ? $fetch_cbalance['wallet_balance'] : $fetch_myibalance['transfer_balance'];

        if($itransfer_balance < $amount){
            
            mysqli_query($link, "DELETE FROM otp_confirmation WHERE userid = '$acctno' AND otp_code = '$myotp' AND status = 'Pending'");
            
            echo "<div class='alert alert-danger'>Oops! You have insufficient fund in your Wallet!!</div>";
            echo '<meta http-equiv="refresh" content="2;url=wallet-towallet.php?id='.$_SESSION['tid'].'&&mid=NDA0&&tab=tab_1">';
            
        }else{
        
          //Sender Parameters
          $amountDebited = $amount;
          $senderBalance = $itransfer_balance - $amount;
          //Receivers Parameters
          $amountCredited = $amount;
          $receiverBalance = $currentReceiverBalance + $amount;

          $update = mysqli_query($link, "UPDATE user SET transfer_balance = '$senderBalance' WHERE id = '$iuid'");

          $search_insti = mysqli_query($link, "SELECT * FROM institution_data WHERE institution_id = '$institution_id'");
          $fetch_myinst = mysqli_fetch_array($search_insti);
          $iwallet_balance = $fetch_myinst['wallet_balance'];
          $sms_rate = $fetchsys_config['fax'];
          $refid = uniqid("EA-smsCharges-").time();
          $sysabb = $fetch_memset['sender_id'];

          $sms = "$sysabb>>>CR";
          $sms .= " Amt: ".$icurrency.$amountCredited."";
          $sms .= " Acc: ".ccMasking($account)."";
          $sms .= " Desc: ".substr($remark,0,20)." - | ".$txid."";
          $sms .= " Time: ".$final_date_time."";
          $sms .= " Bal: ".$icurrency.number_format($receiverBalance,2,'.',',')."";

          ($detectRightReceiver == "Customer") ? $update = mysqli_query($link, "UPDATE borrowers SET wallet_balance = '$receiverBalance' WHERE account = '$receiverAcctId'") or die (mysqli_error($link)) : "";
          ($detectRightReceiver == "Institution") ? $update = mysqli_query($link, "UPDATE user SET transfer_balance = '$receiverBalance' WHERE id = '$receiverAcctId'") or die (mysqli_error($link)) : "";
                  
          $debitWAllet = ($getSMS_ProviderNum == 1 || ($maintenance_row == 1 && $billing_type == "PAYGException")) ? "No" : "Yes";
          $max_per_page = 153;
          $sms_length = strlen($sms);
          $calc_length = ceil($sms_length / $max_per_page);
          $sms_charges = $calc_length * $sms_rate;
          $merchantName = $inst_name;
          $senderName = $iname;
          $senderAccount = $ivirtual_acctno;
          $myRemainingBalance = $iwallet_balance - $sms_charges;
          $senderEmail = $myiemail_addrs;
                  
          $insert = mysqli_query($link, "INSERT INTO wallet_history VALUES(null,'$institution_id','$txid','$receiverAcctId','','$amountCredited','Debit','$icurrency','$ptype','$remark','successful','$final_date_time','$iuid','$senderBalance','$receiverBalance')") or die (mysqli_error($link));
                    
          ($debitWAllet == "No" ? $sendSMS->instGeneralAlert($ozeki_password, $ozeki_url, $sysabb, $phone, $sms, $institution_id, $refid, $sms_charges, $iuid, $imywallet_balance, $debitWallet) : ($debitWAllet == "Yes" && $sms_charges <= $iwallet_balance ? $sendSMS->instGeneralAlert($ozeki_password, $ozeki_url, $sysabb, $phone, $sms, $institution_id, $refid, $sms_charges, $iuid, $imywallet_balance, $debitWallet) : ""));
          $sendSMS->walletCreditEmailNotifier($em, $txid, $final_date_time, $inst_name, $myname, $account, $icurrency, $amount, $totalwallet_balance, $iemailConfigStatus, $ifetch_emailConfig);
          $sendSMS->walletDebitEmailNotifier($senderEmail, $txid, $senderName, $final_date_time, $inst_name, $senderAccount, $merchantName, $icurrency, $amount, $myRemainingBalance, $iemailConfigStatus, $ifetch_emailConfig);
          
          mysqli_query($link, "DELETE FROM otp_confirmation WHERE userid = '$iuid' AND otp_code = '$myotp' AND status = 'Pending'");

          echo "<div class='alert bg-blue'>Transaction Successful! Current Transfer Wallet Balance is: <b style='color: orange;'>".$icurrency.number_format($senderBalance,2,'.',',')."</b></div>";
          echo '<meta http-equiv="refresh" content="3;url=wallet-towallet.php?id='.$_SESSION['tid'].'&&mid=NDA0&&tab=tab_2">';

        }
	    
	}
    
}
?>



<?php
if(!(isset($_GET['otp']) || isset($_GET['pin'])))
{
?>
             <div class="box-body">
                 
                 <div class="form-group">
                    <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Wallet Account No.</label>
                    <div class="col-sm-6">
                    <?php
                    $uid = $_GET['uid'];
                    $searchVA = mysqli_query($link, "SELECT * FROM virtual_account WHERE account_number = '$uid'");
                    $fetchVA = mysqli_fetch_array($searchVA);
                    if(isset($_GET['uid'])){
                    ?>
                      <input name="author" type="hidden" class="form-control" value="<?php echo $uid; ?>">
                      <input name="myauthor" type="text" class="form-control" value="<?php echo $fetchVA['account_name']; ?>" readonly required>
                    <?php
                    }
                    else{
                    ?>
                      <input name="author" type="text" class="form-control" id="verify_virtualacct" onkeyup="verifyVA();" placeholder="Enter Recipient Wallet Account Number" required>
                      <div id="myVA"></div>
                    <?php
                    }
                    ?>
                    </div>
                    <label for="" class="col-sm-3 control-label"></label>
                  </div>
                  
                  <div class="form-group">
                    <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Amount to Fund</label>
                    <div class="col-sm-6">
                        <input name="amount" type="number" class="form-control" placeholder="Enter Amount Here" required>
                    </div>
                    <label for="" class="col-sm-3 control-label"></label>
                  </div>
                  
                  <div class="form-group">
                    <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Remark</label>
                    <div class="col-sm-6">
                        <textarea name="remark"  class="form-control" rows="2" cols="80" placeholder="e.g Manual Funding to Wallet from Admin"></textarea>
                    </div>
                    <label for="" class="col-sm-3 control-label"></label>
                  </div>

                </div>
                
                <div class="form-group" align="right">
                    <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;"></label>
                    <div class="col-sm-6">
                        <button name="osavings" type="submit" onclick="var e=this;setTimeout(function(){e.disabled=true;},0);return true;" class="btn bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>"><i class="fa fa-forward">&nbsp;Transfer Fund</i></button>
                    </div>
                    <label for="" class="col-sm-3 control-label"></label>
                </div>
                
<?php
}
else{
    include("otp_confirmation.php");
}
?>
      
       </form> 

      </div>
    </div>

  <?php
  }
elseif($tab == 'tab_3' && $isubagent_wallet == "Enabled")
  {
  ?>

      <div class="tab-pane <?php echo ($_GET['tab'] == 'tab_3') ? 'active' : ''; ?>" id="tab_3">

        <div class="box-body">
           
       <form class="form-horizontal" method="post" enctype="multipart/form-data">
<?php
if(isset($_POST['asave']))
{
  function myreference($limit)
  {
    return substr(base_convert(sha1(uniqid(mt_rand())), 16, 36), 0, $limit);
  }
  try{
    $ptype = "p2p-transfer";
    $account =  mysqli_real_escape_string($link, $_POST['sub_agent']);
    $amount = preg_replace('/[^0-9.]/', '', mysqli_real_escape_string($link, $_POST['amount']));
    //$currency = mysqli_real_escape_string($link, $_POST['currency']);
    $remark = mysqli_real_escape_string($link, $_POST['remark']);
    //$remark .= "<br><b>Posted by:<br>".$name.'</b>';
    $date_time = date("Y-m-d");
    $final_date_time = date ('Y-m-d h:i:s', strtotime($date_time));
    $txid = 'EA-p2pFunding-'.myreference(10);
    $tpin = mysqli_real_escape_string($link, $_POST['tpin']);

    $search_memset = mysqli_query($link, "SELECT * FROM member_settings WHERE companyid = '$institution_id'");
    $fetch_memset = mysqli_fetch_array($search_memset);
      
    if($amount < 0){
      throw new UnexpectedValueException();
    }
    elseif(preg_match('/^[0-9]+(?:\.[0-9]{0,2})?$/', $amount) == FALSE)
    {
      throw new UnexpectedValueException();
    }
    elseif($iwallet_balance < ($amount + $fetchsys_config['fax'])){
        echo "<div class='alert alert-danger'>Oops! You have insufficient fund in your Wallet!!</div>";
    }
    elseif($tpin != $myiepin){
	    echo "<script>alert('Oops! Invalid Transaction Pin!'); </script>";
	}
    else{
      $search_abalance = mysqli_query($link, "SELECT * FROM user WHERE id = '$account'");
      $fetch_abalance = mysqli_fetch_object($search_abalance);
      $agent_wallet_balance = $fetch_abalance->wallet_balance;
      $receiverBalance = $agent_wallet_balance + $amount;
      $ph = $fetch_abalance->phone;
      $em = $fetch_abalance->email;
      $myname = $fetch_abalance->name;

      $merchantName = $inst_name;
      $senderName = $iname;
      $senderAccount = $ivirtual_acctno;
      $myRemainingBalance = $senderBalance - $sms_charges;
      $senderEmail = $myiemail_addrs;

      if(mysqli_num_rows($search_abalance) == 1) {
        
        $remain_merchantbalance = $iwallet_balance - $amount;
        $update = mysqli_query($link, "UPDATE institution_data SET wallet_balance = '$remain_merchantbalance' WHERE institution_id = '$institution_id'");
        $update = mysqli_query($link, "UPDATE user SET wallet_balance = '$receiverBalance' WHERE id = '$account'") or die (mysqli_error($link));
        $insert = mysqli_query($link, "INSERT INTO wallet_history VALUES(null,'$institution_id','$txid','$account','','$amount','Debit','$icurrency','$ptype','$remark','successful','$final_date_time','$iuid','$remain_merchantbalance','$receiverBalance')") or die (mysqli_error($link));
        if(!($update && $insert))
        {
          echo "<div class='alert bg-orange'>Unable to Transfer Fund.....Please try again later</div>";
        }
        else{
          include("alert_sender/p2p_alert.php");
          include("alert_sender/send_sp2ptransfer_alertemail.php");
          include("alert_sender/send_sp2ptransfer_alertemail2.php");
          echo "<div align='center'><img src='../image/checkmark.gif'><p style='color: #38A1F3;'>Transaction Successful!</p><p style='color: #38A1F3;'>Current Wallet Balance: <b style='color: orange;'>".$icurrency.number_format($receiverBalance,2,'.',',')."</b></p></div>";
        }

      }else{
        echo "<div class='alert bg-orange'>Oops! Institution ID / Phone Number does not exist!!</div>";
      }

    }
  }catch(UnexpectedValueException $ex)
  {
    echo "<div class='alert alert-danger'>Invalid Amount Entered!</div>";
  }
}
?>
             <div class="box-body">
                 
                 <div class="form-group">
                    <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Sub-agent</label>
                    <div class="col-sm-6">
                        <select name="sub_agent"  class="form-control select2" required>
                          <option value="" selected>Select Sub-Agent Account</option>
                            <?php
                            $search = mysqli_query($link, "SELECT * FROM user WHERE created_by = '$institution_id' AND (role != 'agent_manager' OR role != 'institution_super_admin' OR role != 'merchant_super_admin')");
                            while($get_search = mysqli_fetch_array($search))
                            {
                            ?>
                          <option value="<?php echo $get_search['id']; ?>"><?php echo $get_search['name']; ?> [<?php echo $icurrency.number_format($get_search['wallet_balance'],2,'.',','); ?>]</option>
                            <?php } ?>
                        </select>
                    </div>
                    <label for="" class="col-sm-3 control-label"></label>
                  </div>
                  
                  <div class="form-group">
                    <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Amount to Fund</label>
                    <div class="col-sm-6">
                        <input name="amount" type="number" class="form-control" placeholder="Enter Amount Here">
                    </div>
                    <label for="" class="col-sm-3 control-label"></label>
                  </div>
                  
                  <div class="form-group">
                    <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Transaction Pin</label>
                    <div class="col-sm-6">
                        <input name="tpin" type="password" class="form-control" maxlength="4" placeholder="Transaction Pin" required>
                    </div>
                    <label for="" class="col-sm-3 control-label"></label>
                  </div>
                  
                  <div class="form-group">
                    <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Remark</label>
                    <div class="col-sm-6">
                        <textarea name="remark"  class="form-control" rows="2" cols="80" placeholder="e.g Manual Funding to Wallet from Admin"></textarea>
                    </div>
                    <label for="" class="col-sm-3 control-label"></label>
                  </div>

                </div>
                
                <div class="form-group" align="right">
                    <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;"></label>
                    <div class="col-sm-6">
                        <button name="asave" type="submit" onclick="var e=this;setTimeout(function(){e.disabled=true;},0);return true;" class="btn bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>"><i class="fa fa-forward">&nbsp;Transfer Fund</i></button>
                    </div>
                    <label for="" class="col-sm-3 control-label"></label>
                </div>
      
       </form> 

      </div>
    </div>

  <?php
  }
}
  ?>
	
</div>	
</div>
</div>
</div>
</div>
</div>
</div>
</section>
</div>