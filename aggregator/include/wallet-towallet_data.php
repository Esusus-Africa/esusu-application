<div class="row">
	      <section class="content">
       <div class="box box-default">
           <div class="box-body">
             <div class="table-responsive"> 
            <div class="box-body">
<button type="button" class="btn btn-flat bg-orange" align="left" disabled>&nbsp;<b>Transfer Wallet:</b>&nbsp;
    <strong class="alert bg-blue">
    <?php
    echo $aggcurrency.number_format($aggwallet_balance,2,'.',',');
    ?> 
    </strong>
</button>

<hr>
<div class="slideshow-container">
<div class="alert bg-orange" style="font-size:17px;" align="center">
    <p>
        <?php
        //echo $walletafrica_status;
        if($mo_virtualacct_status == "Active" && ($walletafrica_status == "NotActive" || $walletafrica_status == "Active")){
            
            include("../config/monnify_virtualaccount_esusu.php");
            
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

             <li <?php echo ($_GET['tab'] == 'tab_2') ? "class='active'" : ''; ?>><a href="wallet-towallet.php?id=<?php echo $_SESSION['tid']; ?>&&mid=NDA0&&tab=tab_2">Fund Other Wallet</a></li>

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

  $otp_code = $control_pin;
    
  $otpChecker = "pin";

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
  $myccum_balance = $fetch_cbalance['wallet_balance'];
  
  $search_myibalance = mysqli_query($link, "SELECT * FROM user WHERE id = '$userAcct'");
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
  $phone = $myiphone;
  
  //Data Parser (array size = 10)
  $mydata = $txid."|".$account."|".$amount."|".$ptype."|".$remark."|".$final_date_time."|".$ph."|".$em."|".$myname."|".$detectRightReceiver."|".$receiverVANo."|".$receiverAcctId."|".$receiverBalance."|".$userAcct;
  
  $key = base64_encode($mydata);

  if($amount <= 0){
      
      echo "<div class='alert bg-orange'>Oops! Amount Entered is not Valid!!</div>";
      
  }
  elseif($gactive_status == "Suspended"){
        
    echo "<div class='alert bg-orange'>Oops! You are unable to make transfer at the moment!!</div>";
  
  }
  elseif($aggwallet_balance < $amount){
	    
      echo "<div class='alert bg-orange'>Oops! You have insufficient fund in your Wallet!!</div>";
      
  }
  elseif($amount > $aggrtransferLimitPerTrans){

    echo "<div class='alert bg-orange'>Sorry! You can not transfer more than ".$aggcurrency.number_format($aggrtransferLimitPerTrans,2,'.',',')." at once!!</div>";

  }
  elseif($amyDailyTransferLimit == $aggrtransferLimitPerDay || (($amount + $amyDailyTransferLimit) > $aggrtransferLimitPerDay)){

        echo "<div class='alert bg-orange'>Oops! You have reached your daily limit of ".$aggcurrency.number_format($aggrtransferLimitPerDay,2,'.',',')."</div>";

  }
  elseif($virtual_acctno === "$account" || $aggr_id === "$userAcct"){
    
      mysqli_query($link, "UPDATE user SET status = 'Suspended' WHERE id = '$aggr_id'");
      mysqli_query($link, "INSERT INTO wallet_case VALUES(null,'','','System','$aggr_id','Suspended','Frudulent Act Detected','$currenctdate')");

      echo "<div class='alert bg-orange'>Oops! Request Failed!!</div>";
    
  }
  else{
      
      $insert = mysqli_query($link, "INSERT INTO otp_confirmation VALUES(null,'$uid','$otp_code','$mydata','Pending','$currenctdate')");
      
      if(!$insert)
      {
          echo "<div class='alert bg-orange'>Unable to Transfer Fund.....Please try again later</div>".mysqli_error($link);
      }
      else{
          
          echo "<div align='center'><img src='../image/checkmark.gif'><p style='color: #38A1F3;'>Transfer Request Received Successfully! Wait a moment to complete the transaction with your Pin</p></div>";
          echo '<meta http-equiv="refresh" content="2;url=wallet-towallet.php?id='.$_SESSION['tid'].'&&mid=NDA0&&tab=tab_2&&key='.$key.'&&'.$otpChecker.'">';
        
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
    $key = base64_decode($_GET['key']);
    
	$verify_otp = mysqli_query($link, "SELECT * FROM otp_confirmation WHERE otp_code = '$myotp' AND userid = '$uid' AND status = 'Pending' AND data = '$key'");
	$fetch_data = mysqli_fetch_array($verify_otp);
	$otpnum = mysqli_num_rows($verify_otp);
	
	if($otpnum == 0){
						        
	    echo "<div class='alert bg-orange'>Opps!...Invalid Pin Entered!!</div>";
						        
	}else{

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
        $receiver_virtual_number = $parameter[10];
        $receiverAcctId = $parameter[11];
        $receiverBal = $parameter[12];
        $userAcct = $parameter[13];
        
        $search_gateway = mysqli_query($link, "SELECT * FROM sms WHERE smsuser = '' AND status = 'Activated'");
        $fetch_gateway = mysqli_fetch_object($search_gateway);
        $gateway_uname = $fetch_gateway->username;
        $gateway_pass = $fetch_gateway->password;
        $gateway_api = $fetch_gateway->api;
            
        //Sender Parameters
        $amountDebited = $amount;
        $senderBalance = $aggwallet_balance - $amount - $sms_charges;
        
        $update = mysqli_query($link, "UPDATE user SET transfer_balance = '$senderBalance' WHERE id = '$aggr_id'");
                
        //Receivers Parameters
        $amountCredited = $amount;
        $receiverBalance = $receiverBal + $amount;
        $sms_rate = $fetchsys_config['fax'];
        $refid = uniqid("EA-smsCharges-").time();
        $sysabb = $fetchsys_config['abb'];

        ($detectRightReceiver == "Customer") ? $update = mysqli_query($link, "UPDATE borrowers SET wallet_balance = '$receiverBalance' WHERE account = '$userAcct'") or die (mysqli_error($link)) : "";
        ($detectRightReceiver == "Institution") ? $update = mysqli_query($link, "UPDATE user SET transfer_balance = '$receiverBalance' WHERE id = '$userAcct'") or die (mysqli_error($link)) : "";
                
        $sms = "$sysabb>>>CR";
        $sms .= " Amt: ".$aggcurrency.$amountCredited."";
        $sms .= " Acc: ".ccMasking($account)."";
        $sms .= " Desc: ".substr($remark,0,20)." - | ".$txid."";
        $sms .= " Time: ".$final_date_time."";
        $sms .= " Bal: ".$aggcurrency.number_format($receiverBalance,2,'.',',')."";
                
        $max_per_page = 153;
        $sms_length = strlen($sms);
        $calc_length = ceil($sms_length / $max_per_page);
                	
        $sms_charges = $calc_length * $sms_rate;
        $merchantName = "Esusu Africa Aggregator";
        $senderName = $name;
        $senderAccount = $virtual_acctno;
        $myRemainingBalance = $senderBalance - $sms_charges;
        $senderEmail = $aggemail;
        $debitWAllet = ($getSMS_ProviderNum == 1) ? "No" : "Yes";
        $institution_id = "";
                
        $insert = mysqli_query($link, "INSERT INTO wallet_history VALUES(null,'','$txid','$receiverAcctId','','$amountCredited','Debit','$aggcurrency','$ptype','$remark','successful','$final_date_time','$aggr_id','$senderBalance','$receiverBalance')") or die (mysqli_error($link));
            	    
        if($senderBalance < $sms_charges){
        	        
        	  echo "";
        	        
        }else{
              
              $userType = "user";
              ($debitWAllet == "No" ? $sendSMS->userGeneralAlert($ozeki_password, $ozeki_url, $sysabb, $phone, $sms, $institution_id, $refid, $sms_charges, $aggr_id, $myRemainingBalance, $debitWallet, $userType) : ($debitWAllet == "Yes" && $sms_charges <= $senderBalance ? $sendSMS->userGeneralAlert($ozeki_password, $ozeki_url, $sysabb, $phone, $sms, $institution_id, $refid, $sms_charges, $aggr_id, $myRemainingBalance, $debitWallet, $userType) : ""));
        	        
        }
        mysqli_query($link, "DELETE FROM otp_confirmation WHERE userid = '$aggr_id' AND otp_code = '$myotp' AND status = 'Pending'");
        
        $sendSMS->walletCreditEmailNotifier($em, $txid, $final_date_time, $aggrinst_name, $myname, $account, $aggcurrency, $amount, $totalwallet_balance, $aggremailConfigStatus, $aggrfetch_emailConfig);
        $sendSMS->walletDebitEmailNotifier($senderEmail, $txid, $senderName, $final_date_time, $aggrinst_name, $senderAccount, $merchantName, $aggcurrency, $amount, $myRemainingBalance, $aggremailConfigStatus, $aggrfetch_emailConfig);
        
        echo "<div class='alert bg-blue'>Transaction Successful! Current Transfer Wallet Balance is: <b style='color: white;'>".$aggcurrency.number_format($senderBalance,2,'.',',')."</b></div>";
        echo '<meta http-equiv="refresh" content="3;url=wallet-towallet.php?id='.$_SESSION['tid'].'&&mid=NDA0&&tab=tab_2">';
	    
	}
    
}
?>



<?php
if(!(isset($_GET['pin'])))
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
elseif($tab == 'tab_3' && $isubagent_wallet == "Enabled")
  {
  ?>

    <div class="tab-pane <?php echo ($_GET['tab'] == 'tab_3') ? 'active' : ''; ?>" id="tab_3">


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