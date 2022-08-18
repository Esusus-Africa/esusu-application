<div class="row">
	      <section class="content">
	          
       <div class="box box-default">
           <div class="box-body">
             <div class="table-responsive"> 
            <div class="box-body">
<button type="button" class="btn btn-flat bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>" align="left" disabled>&nbsp;<b>Super Wallet:</b>&nbsp;
    <strong class="alert bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">
    <?php
    echo "<span id='wallet_balance'>".$vcurrency.number_format($vwallet_balance,2,'.',',')."</span>";
    ?> 
    </strong>
</button>

<button type="button" class="btn btn-flat bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>" align="left" disabled>&nbsp;<b>Transfer Wallet:</b>&nbsp;
    <strong class="alert bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">
    <?php
    echo $vcurrency.number_format($vtransfer_balance,2,'.',',');
    ?> 
    </strong>
</button>

<hr>
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
</hr>
  
            <div class="col-md-14">
            <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
             <li <?php echo ($_GET['tab'] == 'tab_1') ? "class='active'" : ''; ?>><a href="p_to_p.php?id=<?php echo $_SESSION['tid']; ?>&&mid=NDA0&&tab=tab_1">Fund other Wallet</a></li>
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
if(isset($_POST['osavings']))
{
  $ptype = "p2p-transfer";
  $account =  mysqli_real_escape_string($link, $_POST['author']);
  $amount = mysqli_real_escape_string($link, $_POST['amount']);
  $remark = mysqli_real_escape_string($link, $_POST['remark']);
  //$remark .= "<br><b>Posted by:<br>".$name.'</b>';
  $date_time = date("Y-m-d");
  $final_date_time = date ('Y-m-d H:i:s', strtotime($date_time));
  $txid = date("yd").time();

  $search_memset = mysqli_query($link, "SELECT * FROM member_settings WHERE companyid = '$vcreated_by'");
  $fetch_memset = mysqli_fetch_array($search_memset);
  $sysabb = $fetch_memset['sender_id'];
  $votp_option = $fetch_memset['otp_option'];
  //$sys_abb = $get_sys['abb'];
  
  $otp_code = ($votp_option == "Yes" || $votp_option == "Both") ? substr((uniqid(rand(),1)),3,6) : $myvepin;
    
  $otpChecker = ($votp_option == "Yes" || $votp_option == "Both") ? "otp" : "pin";
  
  $sms = "$sysabb>>>Dear $vc_name! Your One Time Password is $otp_code";
								
  //SMS DATA
  $search_gateway = mysqli_query($link, "SELECT * FROM sms WHERE smsuser = '' AND status = 'Activated'");
  $fetch_gateway = mysqli_fetch_object($search_gateway);
  $gateway_uname = $fetch_gateway->username;
  $gateway_pass = $fetch_gateway->password;
  $gateway_api = $fetch_gateway->api;
  
  $sms_refid = uniqid("EA-smsCharges-").time();
  $sms_rate = $fetchsys_config['fax'];
  $vmywallet_balance = $vwallet_balance - $sms_rate;
  $currenctdate = date("Y-m-d H:i:s");
  
  //Receivers Details
  $search_cbalance = mysqli_query($link, "SELECT * FROM borrowers WHERE virtual_acctno = '$account'");
  $fetch_cbalance = mysqli_fetch_array($search_cbalance);
  $mycnum = mysqli_num_rows($search_cbalance);
  $myccum_phone = $fetch_cbalance['phone'];
  $myccum_emil = $fetch_cbalance['email'];
  $myccum_fullname = $fetch_cbalance['lname'].' '.$fetch_cbalance['fname'].' '.$fetch_cbalance['mname'];
  $myccum_vnumber = $fetch_cbalance['virtual_number'];
  $myccum_acctid = $fetch_cbalance['account'];
  $myccum_balance = $fetch_cbalance['wallet_balance'];
  
  $search_myibalance = mysqli_query($link, "SELECT * FROM user WHERE virtual_acctno = '$account'");
  $fetch_myibalance = mysqli_fetch_array($search_myibalance);
  $myinum = mysqli_num_rows($search_myibalance);
  $myi_phone = $fetch_myibalance['phone'];
  $myi_email = $fetch_myibalance['email'];
  $myi_name = $fetch_myibalance['name'].' '.$fetch_myibalance['lname'].' '.$fetch_myibalance['mname'];
  $myi_vnumber = $fetch_myibalance['virtual_number'];
  $myi_id = $fetch_myibalance['id'];
  $myi_balance = $fetch_myibalance['transfer_balance'];
  
  //Detect Right Receiver
  $ph = ($mycnum == 1 && $myinum == 0) ? $myccum_phone : $myi_phone;
  $em = ($mycnum == 1 && $myinum == 0) ? $myccum_emil : $myi_email;
  $myname = ($mycnum == 1 && $myinum == 0) ? $myccum_fullname : $myi_name;
  $receiverVANo = ($mycnum == 1 && $myinum == 0) ? $myccum_vnumber : $myi_vnumber;
  $receiverAcctId = ($mycnum == 1 && $myinum == 0) ? $myccum_acctid : $myi_id;
  $receiverBalance = ($mycnum == 1 && $myinum == 0) ? $myccum_balance : $myi_balance;
  $detectRightReceiver = ($mycnum == 1 && $myinum == 0) ? "Customer" : "Institution";

  //Sender Phone Number
  $phone = $vo_phone;
  
  //Data Parser (array size = 10)
  $mydata = $txid."|".$account."|".$amount."|".$ptype."|".$remark."|".$final_date_time."|".$ph."|".$em."|".$myname."|".$detectRightReceiver."|".$receiverVANo."|".$receiverAcctId."|".$receiverBalance;
  
  $key = base64_encode($mydata);

  $debitWAllet = "Yes";

  if($amount < 0){
      
      echo "<div class='alert bg-orange'>Oops! Amount Entered is not Valid!!</div>";
      
  }
  elseif($vactive_status == "Suspended"){
        
    echo "<div class='alert bg-orange'>Oops! You are unable to make transfer at the moment!!</div>";
    
  }
  elseif(($vtransfer_balance < $amount && ($votp_option == "Yes" || $votp_option == "Both")) || ($vtransfer_balance < $amount && $votp_option == "No")){
	    
      echo "<div class='alert bg-orange'>Oops! You have insufficient fund in your Wallet!!</div>";
      
  }
  elseif($vwallet_balance < $sms_rate && ($votp_option == "Yes" || $votp_option == "Both")){
      
      echo "<div class='alert bg-orange'>Oops! You have insufficient fund in your Wallet to Receive OTP!!</div>";
      
  }
  elseif($amount > $vtransferLimitPerTrans){

    echo "<div class='alert bg-orange'>Sorry! You can not transfer more than ".$vcurrency.number_format($vtransferLimitPerTrans,2,'.',',')." at once!!</div>";

  }
  elseif($vmyDailyTransferLimit == $vtransferLimitPerDay || (($amount + $vmyDailyTransferLimit) > $vtransferLimitPerDay)){

        echo "<div class='alert bg-orange'>Oops! You have reached your daily limit of ".$vcurrency.number_format($vtransferLimitPerDay,2,'.',',')."</div>";

  }
  elseif($vvirtual_acctno === "$account" || $vuid === "$userAcct"){
    
    mysqli_query($link, "UPDATE user SET status = 'Suspended' WHERE id = '$vuid'");
    mysqli_query($link, "INSERT INTO wallet_case VALUES(null,'$vcreated_by','','System','$vuid','Suspended','Frudulent Act Detected','$currenctdate')");
    
    echo "<div class='alert bg-orange'>Oops! Request Failed!!</div>";
    
  }
  else{
      
      $insert = mysqli_query($link, "INSERT INTO otp_confirmation VALUES(null,'$vuid','$otp_code','$mydata','Pending','$currenctdate')");
      
      if(!$update)
      {
          echo "<div class='alert bg-orange'>Unable to Transfer Fund.....Please try again later</div>".mysqli_error($link);
      }
      else{
          
          ($votp_option == "No" ? "" : ($debitWAllet == "No" ? $sendSMS->vendorGeneralAlert($sysabb, $vo_phone, $sms, $vcreated_by, $sms_refid, $sms_rate, $vuid, $vmywallet_balance, $debitWallet, $vendorid) : ($debitWAllet == "Yes" && $sms_rate <= $vwallet_balance ? $sendSMS->vendorGeneralAlert($sysabb, $vo_phone, $sms, $vcreated_by, $sms_refid, $sms_rate, $vuid, $vmywallet_balance, $debitWallet, $vendorid) : "")));
          echo ($votp_option == "Yes" || $votp_option == "Both") ? "<div align='center'><img src='../image/checkmark.gif'><p style='color: #38A1F3;'>Great! Otp has been sent to your mobile phone to complete this transaction!!</p></div>" : "<div align='center'><img src='../image/checkmark.gif'><p style='color: #38A1F3;'>Transfer Request Received Successfully! Wait a moment to complete the transaction with your Pin</p></div>";
          echo '<meta http-equiv="refresh" content="2;url=wallet-towallet.php?id='.$_SESSION['tid'].'&&mid=NDA0&&tab=tab_1&&key='.$key.'&&'.$otpChecker.'">';
        
      }
   
  }
  
}
?>



<?php
if (isset($_POST['confirm']))
{
    //include("../config/walletafrica_restfulapis_call.php");
    
    //$result = array();
    //$result1 = array();
    $myotp = $_POST['otp'];
    
	$verify_otp = mysqli_query($link, "SELECT * FROM otp_confirmation WHERE otp_code = '$myotp' AND userid = '$vuid' AND status = 'Pending'");
	$fetch_data = mysqli_fetch_array($verify_otp);
	$otpnum = mysqli_num_rows($verify_otp);
	
	if($otpnum == 0){
						        
	    echo "<div class='alert bg-orange'>Opps!...Invalid OTP Entered!!</div>";
						        
	}else{

	    $concat = $fetch_data['data'];
    
        $datetime = $fetch_data['datetime'];
                                
        $parameter = (explode('|',$concat));
                                
        $txid = $parameter[0];
        $virtual_acctno = $parameter[1];
        $amount = $parameter[2];
        $ptype = $parameter[3];
        $remark = $parameter[4];
        $final_date_time = $parameter[5];
        $phone = $parameter[6];
        $em = $parameter[7];
        $myname = $parameter[8];
        $detectRightReceiver = $parameter[9];
        $receiver_virtual_number = $parameter[10];
        $receiverAcctId = $parameter[11];
        $receiverBal = $parameter[12];
        
        $search_gateway = mysqli_query($link, "SELECT * FROM sms WHERE smsuser = '' AND status = 'Activated'");
        $fetch_gateway = mysqli_fetch_object($search_gateway);
        $gateway_uname = $fetch_gateway->username;
        $gateway_pass = $fetch_gateway->password;
        $gateway_api = $fetch_gateway->api;
        
        $search_memset = mysqli_query($link, "SELECT * FROM member_settings WHERE companyid = '$vcreated_by'");
        $fetch_memset = mysqli_fetch_array($search_memset);
            
        //Sender Parameters
        $amountDebited = $amount;
        $senderBalance = $vtransfer_balance - $amountDebited;
                
        //Receivers Parameters
        $amountCredited = $amountDebited;
        $receiverBalance = $receiverBal + $amountCredited;
        $sms_rate = $fetchsys_config['fax'];
        $refid = "EA-smsCharges-".time();
        $sysabb = $fetch_memset['sender_id'];
                
        $sms = "$sysabb>>>CR";
        $sms .= " Amt: ".$vcurrency.$amountCredited."";
        $sms .= " Acc: ".ccMasking($receiverAcctId)."";
        $sms .= " Desc: ".substr($remark,0,20)." - | ".$txid."";
        $sms .= " Time: ".$final_date_time."";
        $sms .= " Bal: ".$vcurrency.number_format($receiverBalance,2,'.',',')."";
                
        $max_per_page = 153;
        $sms_length = strlen($sms);
        $calc_length = ceil($sms_length / $max_per_page);
        $sms_charges = $calc_length * $sms_rate;
        $debitWAllet = "Yes";
                
        $update = mysqli_query($link, "UPDATE user SET transfer_balance = '$senderBalance' WHERE id = '$vuid'");
        ($detectRightReceiver == "Customer") ? $update = mysqli_query($link, "UPDATE borrowers SET wallet_balance = '$receiverBalance' WHERE virtual_acctno = '$virtual_acctno'") or die (mysqli_error($link)) : "";
        ($detectRightReceiver == "Institution") ? $update = mysqli_query($link, "UPDATE user SET transfer_balance = '$receiverBalance' WHERE virtual_acctno = '$virtual_acctno'") or die (mysqli_error($link)) : "";
        $insert = mysqli_query($link, "INSERT INTO wallet_history VALUES(null,'$vcreated_by','$txid','$receiverAcctId','','$amountCredited','Debit','$vcurrency','$ptype','$remark','successful','$final_date_time','$vuid','$senderBalance','$receiverBalance')") or die (mysqli_error($link));
        mysqli_query($link, "DELETE FROM otp_confirmation WHERE userid = '$vuid' AND otp_code = '$myotp' AND status = 'Pending'");
        	    
        if($senderBalance < $sms_charges){
        	        
        	echo "";
        	        
        }else{
        
            $myRemainingBalance = $senderBalance - $sms_charges;
        
            ($debitWAllet == "No" ? $sendSMS->vendorGeneralAlert($sysabb, $phone, $sms, $vcreated_by, $refid, $sms_charges, $vuid, $myRemainingBalance, $debitWallet, $vendorid) : ($debitWAllet == "Yes" && $sms_charges <= $senderBalance ? $sendSMS->vendorGeneralAlert($sysabb, $phone, $sms, $vcreated_by, $refid, $sms_charges, $vuid, $myRemainingBalance, $debitWallet, $vendorid) : ""));
        	        
        }
        
        $sendSMS->walletCreditEmailNotifier($em, $txid, $final_date_time, $vc_name, $myname, $receiverAcctId, $vcurrency, $amountCredited, $receiverBalance, $vemailConfigStatus, $vfetch_emailConfig);
        	    
        echo "<div class='alert bg-blue'>Transaction Successful! Current Wallet Balance is: <b style='color: orange;'>".$vcurrency.number_format($senderBalance,2,'.',',')."</b></div>";
        echo '<meta http-equiv="refresh" content="3;url=p_to_p.php?id='.$_SESSION['tid'].'&&mid=NDA0&&tab=tab_1">';
	    
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
                        <input name="author" type="text" class="form-control" id="verify_virtualacct" onkeyup="verifyVA();" placeholder="Enter Recipient Wallet Account Number" required>
                        <div id="myVA"></div>
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
                        <button name="osavings" type="submit" class="btn bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>"><i class="fa fa-forward">&nbsp;Transfer Fund</i></button>
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