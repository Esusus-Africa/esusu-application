<div class="row">
	      <section class="content">
	          
       <div class="box box-default">
           <div class="box-body">
             <div class="table-responsive"> 
            <div class="box-body">
            <a href="mywallet_history.php?tid=<?php echo $_SESSION['tid']; ?>&&acn=<?php echo $_SESSION['acctno']; ?>&&mid=NDA4&&tab=tab_1"><button type="button" class="btn btn-flat bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>"><i class="fa fa-mail-reply-all"></i>&nbsp;Back</button></a> 
<button type="button" class="btn btn-flat bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>" align="left" disabled>&nbsp;<b>Wallet Balance:</b>&nbsp;
<strong class="alert bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">
<?php
echo $bbcurrency.number_format($bwallet_balance,2,'.',',');
?> 
</strong>
  </button>
  
            <div class="col-md-14">
            <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
             <li <?php echo ($_GET['tab'] == 'tab_2') ? "class='active'" : ''; ?>><a href="wallet-towallet.php?id=<?php echo $_SESSION['tid']; ?>&&acn=<?php echo $_SESSION['acctno']; ?>&&mid=NDA4&&tab=tab_2">Fund other Wallet</a></li>

              </ul>
             <div class="tab-content">

      <div class="tab-pane <?php echo ($_GET['tab'] == 'tab_2') ? 'active' : ''; ?>" id="tab_2">

        <div class="box-body">
           
       <form class="form-horizontal" method="post" enctype="multipart/form-data">
<?php
if(isset($_POST['asave']))
{    
    $ptype = "p2p-transfer";
    $account =  mysqli_real_escape_string($link, $_POST['author']);
    $amount = preg_replace('/[^0-9.]/', '', mysqli_real_escape_string($link, $_POST['amount']));
    //$currency = mysqli_real_escape_string($link, $_POST['currency']);
    $remark = mysqli_real_escape_string($link, $_POST['remark']);
    //$remark .= "<br><b>Posted by:<br>".self.'</b>';
    $date_time = date("Y-m-d");
    $final_date_time = date('Y-m-d h:i:s');
    $txid = 'EA-p2pFunding-'.time();
    //$tpin = mysqli_real_escape_string($link, $_POST['tpin']);
    
    //OTP / Pin Section
	$otpcode = ($otp_option == "Yes" || $otp_option == "Both") ? substr((uniqid(rand(),1)),3,6) : $myuepin;
	
	$otpChecker = ($otp_option == "Yes" || $otp_option == "Both") ? "otp" : "pin";
	
	$currenctdate = date("Y-m-d H:i:s");
                                
    $query = mysqli_query($link, "SELECT * FROM systemset") or die (mysqli_error($link));
    $r = mysqli_fetch_object($query);
    $sysabb = ($bsender_id == "") ? $r->abb : $bsender_id;
								
	$sms = "$sysabb>>>Dear $myln! Your One Time Password is $otpcode";
                        		
    $search_insti = mysqli_query($link, "SELECT * FROM institution_data WHERE institution_id = '$bbranchid'");
    $fetch_myinst = mysqli_fetch_array($search_insti);
    $iwallet_balance = $fetch_myinst['wallet_balance'];
                            	
    $sms_rate = $r->fax;
    $imywallet_balance = $iwallet_balance - $sms_rate;
    $sms_refid = uniqid("EA-smsCharges-").time();

    $search_memset = mysqli_query($link, "SELECT * FROM member_settings WHERE companyid = '$bbranchid'");
    $fetch_memset = mysqli_fetch_array($search_memset);
    //$sys_abb = $get_sys['abb'];

    $searchVirtualAcct = mysqli_query($link, "SELECT * FROM virtual_account WHERE account_number = '$account' AND companyid = '$bbranchid'");
    $fetchVirtualAcct = mysqli_fetch_array($searchVirtualAcct);
    $userAcct = $fetchVirtualAcct['userid'];

    //Receivers Details for Customer
    $search_cbalance = mysqli_query($link, "SELECT * FROM borrowers WHERE account = '$userAcct'");
    $fetch_cbalance = mysqli_fetch_array($search_cbalance);
    $mycnum = mysqli_num_rows($search_cbalance);
    $myccum_phone = $fetch_cbalance['phone'];
    $myccum_emil = $fetch_cbalance['email'];
    $myccum_fullname = $fetch_cbalance['fname'].' '.$fetch_cbalance['lname'].' '.$fetch_cbalance['mname'];
    $myccum_acctid = $fetch_cbalance['account'];

    //Receivers Details for Institution
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
    $receiverAcctId = ($mycnum == 1 && $myinum == 0) ? $myccum_acctid : $myi_id;
    $detectRightReceiver = ($mycnum == 1 && $myinum == 0) ? "Customer" : "Institution";
    $phone = $phone2;

    $maintenance_row = mysqli_num_rows($bsearch_maintenance_model);
    $debitWAllet = ($bgetSMS_ProviderNum == 1 || ($maintenance_row == 1 && $bbilling_type == "PAYGException")) ? "No" : "Yes";
    
    //Data Parser (array size = 10)
    $mydata = $txid."|".$account."|".$amount."|".$ptype."|".$remark."|".$final_date_time."|".$ph."|".$em."|".$myname."|".$detectRightReceiver."|".$receiverAcctId."|".$bwallet_balance;
        
    $key = base64_encode($mydata);
      
    if($amount <= 0){
      echo "<div class='alert alert-danger'>Oops! Amount Entered is not Valid!!</div>";
    }
    elseif($bactive_status == "Suspended"){
        
        echo "<div class='alert bg-orange'>Oops! You are unable to make transfer at the moment!!</div>";
        
    }
    elseif(preg_match('/^[0-9]+(?:\.[0-9]{0,2})?$/', $amount) == FALSE)
    {
      echo "<div class='alert alert-danger'>Invalid Amount Entered!</div>";
    }
    elseif($bwallet_balance < $amount){
        echo "<div class='alert alert-danger'>Oops! You have insufficient fund in your Wallet!!</div>";
    }
    elseif($amount > $btransferLimitPerTrans){

        echo "<div class='alert bg-orange'>Sorry! You can not transfer more than ".$bbcurrency.number_format($btransferLimitPerTrans,2,'.',',')." at once!!</div>";

    }
    elseif($bmyDailyTransferLimit == $btransferLimitPerDay || (($amount + $bmyDailyTransferLimit) > $btransferLimitPerDay)){

        echo "<div class='alert bg-orange'>Oops! You have reached your daily limit of ".$bbcurrency.number_format($btransferLimitPerDay,2,'.',',')."</div>";

    }
    elseif($bvirtual_acctno === "$account" || $acctno === "$userAcct"){
        
        mysqli_query($link, "UPDATE borrowers SET status = 'Suspended' WHERE account = '$acctno'");
        mysqli_query($link, "INSERT INTO wallet_case VALUES(null,'$bbranchid','$bsbranchid','System','$acctno','Suspended','Frudulent Act Detected','$currenctdate')");
        
        echo "<div class='alert bg-orange'>Oops! Request Failed!!</div>";
        
    }
    else{
        
        $update = mysqli_query($link,"INSERT INTO otp_confirmation VALUES(null,'$acctno','$otpcode','$mydata','Pending','$currenctdate')") or die(mysqli_error());
        
        if(!$update)
        {
            echo "<div class='alert bg-orange'>Unable to Transfer Fund.....Please try again later</div>";
        }
        else{
            $iuid = "";
            ($debitWAllet == "No" && ($otp_option == "Yes" || $otp_option == "Both") ? $sendSMS->instGeneralAlert($bozeki_password, $bozeki_url, $sysabb, $phone2, $sms, $bbranchid, $sms_refid, $sms_rate, $iuid, $imywallet_balance, $debitWallet) : ($debitWAllet == "Yes" && $sms_rate <= $iwallet_balance ? $sendSMS->instGeneralAlert($ozeki_password, $ozeki_url, $isenderid, $phone2, $sms, $institution_id, $sms_refid, $sms_rate, $iuid, $imywallet_balance, $debitWallet) : ""));
            echo ($iotp_option == "Yes" || $iotp_option == "Both") ? "<div align='center'><img src='".$fetchsys_config['file_baseurl']."checkmark.gif'><p style='color: #38A1F3;'>Great! Otp has been sent to your mobile phone to complete this transaction!!</p></div>" : "<div align='center'><img src='".$fetchsys_config['file_baseurl']."checkmark.gif'><p style='color: #38A1F3;'>Transfer Request Received Successfully! Wait a moment to complete the transaction with your Pin</p></div>";
            echo '<meta http-equiv="refresh" content="2;url=wallet-towallet.php?tid='.$_SESSION['tid'].'&&acn='.$acctno.'&&mid=NDA4&&tab=tab_2&&key='.$key.'&&'.$otpChecker.'">';
        }
        
    }
    
}
?>


<?php
if (isset($_POST['confirm']))
{    
    $result = array();
    $myotp = $_POST['otp'];
    
    $search_memset = mysqli_query($link, "SELECT * FROM member_settings WHERE companyid = '$bbranchid'");
    $fetch_memset = mysqli_fetch_array($search_memset);
				    
	$verify_otp = mysqli_query($link, "SELECT * FROM otp_confirmation WHERE otp_code = '$myotp' AND userid = '$acctno' AND status = 'Pending'");
	$fetch_data = mysqli_fetch_array($verify_otp);
	$otpnum = mysqli_num_rows($verify_otp);
	
	$verify_otp1 = mysqli_query($link, "SELECT * FROM otp_confirmation WHERE otp_code = '$myotp' AND userid = '$acctno' AND status = 'Pending'");
	$otpnum1 = mysqli_num_rows($verify_otp1);
    
    if($otpnum1 > 1){
		
        mysqli_query($link, "DELETE FROM otp_confirmation WHERE userid = '$acctno' AND otp_code = '$myotp' AND status = 'Pending'");
          
        echo "<div class='alert bg-orange'>Duplicate Transaction is not allowed!!</div>";
        echo '<meta http-equiv="refresh" content="2;url=wallet-towallet.php?tid='.$_SESSION['tid'].'&&acn='.$acctno.'&&mid=NDA4&&tab=tab_2">';
  
    }
    elseif($otpnum == 0 && $otpnum1 == 0){
        
        mysqli_query($link, "DELETE FROM otp_confirmation WHERE userid = '$acctno' AND status = 'Pending'");
        
        echo "<div class='alert bg-orange'>Opps!...Invalid Request Sent!!</div>";
        echo '<meta http-equiv="refresh" content="2;url=wallet-towallet.php?tid='.$_SESSION['tid'].'&&acn='.$acctno.'&&mid=NDA4&&tab=tab_2">';
    
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
        $detectRightReceiver = $parameter[9];
        $userAcct = $parameter[10];
        $balanceLeft = $parameter[11];
        $accno = ccMasking($account);
        $final_date_time = date('Y-m-d h:i:s');
        
        //Receivers Details for Customer
        $search_cbalance = mysqli_query($link, "SELECT * FROM borrowers WHERE account = '$userAcct'");
        $fetch_cbalance = mysqli_fetch_array($search_cbalance);
        $mycnum = mysqli_num_rows($search_cbalance);
        
        //Receivers Details for Institution
        $search_myibalance = mysqli_query($link, "SELECT * FROM user WHERE id = '$userAcct'");
        $fetch_myibalance = mysqli_fetch_array($search_myibalance);
        $myinum = mysqli_num_rows($search_myibalance);
        
        $currentReceiverBalance = ($mycnum == 1 && $myinum == 0) ? $fetch_cbalance['wallet_balance'] : $fetch_myibalance['transfer_balance'];

        $amountCredited = $amount;
        //Remaining Sender Balance
        $senderBalance = $bwallet_balance - $amountCredited;
        //Receiver Balance after transfer
        $receiverBalance = $currentReceiverBalance + $amountCredited;

        if($bwallet_balance < $amountCredited){
            
            mysqli_query($link, "DELETE FROM otp_confirmation WHERE userid = '$acctno' AND otp_code = '$myotp' AND status = 'Pending'");
            
            echo "<div class='alert alert-danger'>Oops! You have insufficient fund in your Wallet!!</div>";
            echo '<meta http-equiv="refresh" content="2;url=wallet-towallet.php?tid='.$_SESSION['tid'].'&&acn='.$acctno.'&&mid=NDA4&&tab=tab_2">';
            
        }else{
            
            $message = "$bsenderid>>>CR";
            $message .= " Amt: ".$bbcurrency.number_format($amount,2,'.',',')."";
            $message .= " Acc: ".$accno."";
            $message .= " Desc: ".substr($remark,0,20)." - ".$txid."";
            $message .= " Time: ".$final_date_time."";
            $message .= " Bal: ".$bbcurrency.number_format($receiverBalance,2,'.',',')."";
            
            $update = mysqli_query($link, "UPDATE borrowers SET wallet_balance = '$senderBalance' WHERE account = '$acctno'");
            
            $search_insti = mysqli_query($link, "SELECT * FROM institution_data WHERE institution_id = '$bbranchid'");
            $fetch_myinst = mysqli_fetch_array($search_insti);
            $iwallet_balance = $fetch_myinst['wallet_balance'];
                                        
            $sms_rate = $fetchsys_config['fax'];
            $imywallet_balance = $iwallet_balance - $sms_rate;
            $sms_refid = uniqid("EA-smsCharges-").time();
    
            $maintenance_row = mysqli_num_rows($bsearch_maintenance_model);
            $debitWAllet = ($bgetSMS_ProviderNum == 1 || ($maintenance_row == 1 && $bbilling_type == "PAYGException")) ? "No" : "Yes";
            
            ($detectRightReceiver == "Customer") ? $update = mysqli_query($link, "UPDATE borrowers SET wallet_balance = '$receiverBalance' WHERE account = '$userAcct'") or die (mysqli_error($link)) : "";
            ($detectRightReceiver == "Institution") ? $update = mysqli_query($link, "UPDATE user SET transfer_balance = '$receiverBalance' WHERE id = '$userAcct'") or die (mysqli_error($link)) : "";
            $insert = mysqli_query($link, "INSERT INTO wallet_history VALUES(null,'$bbranchid','$txid','$account','','$amountCredited','Debit','$bbcurrency','$ptype','$remark','successful','$final_date_time','$acctno','$senderBalance','$receiverBalance')") or die (mysqli_error($link));

            $iuid = "";
            ($debitWAllet == "No" ? $sendSMS->instGeneralAlert($bozeki_password, $bozeki_url, $bsenderid, $ph, $message, $bbranchid, $sms_refid, $sms_rate, $iuid, $imywallet_balance, $debitWallet) : ($debitWAllet == "Yes" && $sms_rate <= $iwallet_balance ? $sendSMS->instGeneralAlert($ozeki_password, $ozeki_url, $isenderid, $ph, $message, $institution_id, $sms_refid, $sms_rate, $iuid, $imywallet_balance, $debitWallet) : ""));
            $sendSMS->walletCreditEmailNotifier($em, $txid, $final_date_time, $binst_name, $myname, $account, $bbcurrency, $amountCredited, $receiverBalance, $emailConfigStatus, $fetch_emailConfig);
            $sendSMS->walletDebitEmailNotifier($em, $txid, $bname, $final_date_time, $binst_name, $bvirtual_acctno, $binst_name, $bbcurrency, $amount, $senderBalance, $emailConfigStatus, $emailConfigStatus);
            
            mysqli_query($link, "DELETE FROM otp_confirmation WHERE userid = '$acctno' AND otp_code = '$myotp' AND status = 'Pending'");
            
            echo "<div class='alert bg-blue'>Transaction Successful! Current Wallet Balance is: <b style='color: orange;'>".$bbcurrency.number_format($senderBalance,2,'.',',')."</b></div>";
            echo '<meta http-equiv="refresh" content="2;url=wallet-towallet.php?tid='.$_SESSION['tid'].'&&acn='.$acctno.'&&mid=NDA4&&tab=tab_2">';
            
        }
						        
	}
						    
}
?>


<?php
if(!(isset($_GET['otp']) || isset($_GET['pin'])))
{
?>

      <div class="alert bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>"><b>Note that wallet transfer attract zero charges</b></div>
      <hr>

      <div class="box-body">
                 
      <div class="form-group">
        <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Wallet Account No.</label>
        <div class="col-sm-6">
            <input name="author" type="text" class="form-control" id="verify_virtualacct" onkeyup="verifyVA();" placeholder="Enter Recipient Wallet Account Number" required/>
            <div id="myVA"></div>
        </div>
        <label for="" class="col-sm-3 control-label"></label>
      </div>
      
      
      <div class="form-group">
        <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Amount to Fund</label>
        <div class="col-sm-6">
            <input name="amount" type="number" class="form-control" placeholder="Enter Amount Here" required/>
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
                <button name="asave" type="submit" onclick="var e=this;setTimeout(function(){e.disabled=true;},0);return true;" class="btn bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?> btn-flat"><i class="fa fa-forward">&nbsp;Transfer Fund</i></button>
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

	
</div>	
</div>
</div>
</div>
</div>
</div>
</div>
</section>
</div>