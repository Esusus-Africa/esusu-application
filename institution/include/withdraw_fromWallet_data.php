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
             <li <?php echo ($_GET['tab'] == 'tab_1') ? "class='active'" : ''; ?>><a href="withdraw_fromWallet.php?id=<?php echo $_SESSION['tid']; ?>&&mid=NDA0<?php echo (isset($_GET['uid'])) ? "&&uid=".$_GET['uid'] : ""; ?>&&tab=tab_1">Withdraw Fund</a></li>
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
  $amount = preg_replace('/[^0-9.]/', '', mysqli_real_escape_string($link, $_POST['amount']));
  $remark = mysqli_real_escape_string($link, $_POST['remark']);
  $tpin = mysqli_real_escape_string($link, $_POST['tpin']);
  //$remark .= "<br><b>Posted by:<br>".$name.'</b>';
  $date_time = date("Y-m-d");
  $final_date_time = date ('Y-m-d h:i:s');
  $txid = date("yd").time();

  $search_memset = mysqli_query($link, "SELECT * FROM member_settings WHERE companyid = '$institution_id'");
  $fetch_memset = mysqli_fetch_array($search_memset);
  $sysabb = $fetch_memset['sender_id'];
  //$sys_abb = $get_sys['abb'];
  
  $otp_code = substr((uniqid(rand(),1)),3,6);
    
  $otpChecker = "otp";
  								
  //SMS DATA
  $search_gateway = mysqli_query($link, "SELECT * FROM sms WHERE smsuser = '' AND status = 'Activated'");
  $fetch_gateway = mysqli_fetch_object($search_gateway);
  $gateway_uname = $fetch_gateway->username;
  $gateway_pass = $fetch_gateway->password;
  $gateway_api = $fetch_gateway->api;
  
  $sms_refid = "EA-smsCharges-".time();
  $sms_rate = $fetchsys_config['fax'];
  $imywallet_balance = $itransfer_balance - $sms_rate;
  $currenctdate = date("Y-m-d h:i:s");

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

  $sms = "$sysabb>>>Dear $myname! Your One Time Password is $otp_code";

  //Sender Phone Number
  $phone = $ph;
  
  //Data Parser (array size = 10)
  $mydata = $txid."|".$account."|".$amount."|".$ptype."|".$remark."|".$final_date_time."|".$ph."|".$em."|".$myname."|".$detectRightReceiver."|".$receiverVANo."|".$receiverAcctId."|".$receiverBalance."|".$userAcct;
  
  $key = base64_encode($mydata);

  if($amount <= 0){
      
      echo "<div class='alert bg-orange'>Oops! Amount Entered is not Valid!!</div>";
      
  }
  elseif($active_status == "Suspended"){
        
      echo "<div class='alert bg-orange'>Oops! You are unable to make withdrawal at the moment!!</div>";
    
  }
  elseif($myiepin != $tpin){
      
      echo "<div class='alert bg-orange'>Opps!...Invalid Transaction Pin Entered</div>";
    
  }
  elseif($receiverBalance < $amount){
	    
      echo "<div class='alert bg-orange'>Oops! Insufficient fund in customer's Wallet!!</div>";
      
  }
  elseif($itransfer_balance < $sms_rate){
      
      echo "<div class='alert bg-orange'>Oops! Insufficient fund in your Wallet to Send OTP to Customer!!</div>";
      
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
      $insert = mysqli_query($link, "INSERT INTO wallet_history VALUES(null,'$institution_id','$sms_refid','$ph','','$sms_rate','Debit','NGN','Charges','SMS Content: $sms','successful','$currenctdate','$iuid','$imywallet_balance','')");
      $update = mysqli_query($link, "UPDATE user SET transfer_balance = '$imywallet_balance' WHERE id = '$iuid'");
      
      if(!($insert && $update))
      {
          echo "<div class='alert bg-orange'>Unable to Withdraw Fund...please try again later</div>".mysqli_error($link);
      }
      else{
          
          include("../cron/send_general_sms.php");
          echo "<div align='center'><img src='../image/checkmark.gif'><p style='color: #38A1F3;'>Great! Otp has been sent to customer mobile phone to complete this transaction!!</p></div>";
          echo '<meta http-equiv="refresh" content="10;url=withdraw_fromWallet.php?id='.$_SESSION['tid'].'&&mid=NDA0&&tab=tab_1&&key='.$key.'&&'.$otpChecker.'">';
        
      }
   
  }
  
}
?>



<?php
if (isset($_POST['confirm']))
{
    //$result = array();
    //$result1 = array();
    $myotp = $_POST['otp'];
    $key = base64_decode($_GET['key']);
    
	$verify_otp = mysqli_query($link, "SELECT * FROM otp_confirmation WHERE otp_code = '$myotp' AND userid = '$iuid' AND status = 'Pending' AND data = '$key'");
	$fetch_data = mysqli_fetch_array($verify_otp);
	$otpnum = mysqli_num_rows($verify_otp);
	
	if($otpnum == 0){
						        
	    echo "<div class='alert bg-orange'>Opps!...Invalid Credentials!!</div>";
						        
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
        $em = $parameter[7].','.$myiemail_addrs;
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
        
        $search_memset = mysqli_query($link, "SELECT * FROM member_settings WHERE companyid = '$institution_id'");
        $fetch_memset = mysqli_fetch_array($search_memset);
            
        //Sender Parameters
        $amountDebited = $amount;
        $withdrawalBalance = $itransfer_balance + $amount;

        $update = mysqli_query($link, "UPDATE user SET transfer_balance = '$withdrawalBalance' WHERE id = '$iuid'");
                
        //Receivers Parameters
        $amountCredited = $amount;
        $receiverBalance = $receiverBal - $amount;
        $sms_rate = $fetchsys_config['fax'];
        $refid = "EA-smsCharges-".time();
        $sysabb = $fetch_memset['sender_id'];

        ($detectRightReceiver == "Customer") ? $update = mysqli_query($link, "UPDATE borrowers SET wallet_balance = '$receiverBalance' WHERE account = '$userAcct'") or die (mysqli_error($link)) : "";
        ($detectRightReceiver == "Institution") ? $update = mysqli_query($link, "UPDATE user SET transfer_balance = '$receiverBalance' WHERE id = '$userAcct'") or die (mysqli_error($link)) : "";
                
        $sms = "$sysabb>>>DR";
        $sms .= " Amt: ".$icurrency.$amountCredited."";
        $sms .= " Acc: ".ccMasking($account)."";
        $sms .= " Desc: ".$ptype." - | ".$txid."";
        $sms .= " Time: ".convertDateTime($final_date_time)."";
        $sms .= " Bal: ".$icurrency.number_format($receiverBalance,2,'.',',')."";
                
        $max_per_page = 153;
        $sms_length = strlen($sms);
        $calc_length = ceil($sms_length / $max_per_page);
                	
        $sms_charges = $calc_length * $sms_rate;
        
        $insert = mysqli_query($link, "INSERT INTO wallet_history VALUES(null,'$institution_id','$txid','$iuid','','$amountCredited','Debit','$icurrency','$ptype','$remark','successful','$final_date_time','$userAcct','$receiverBalance','$withdrawalBalance')") or die ("Error: " . mysqli_error($link));
        $insert = mysqli_query($link, "INSERT INTO wallet_history VALUES(null,'$institution_id','$txid','$iuid','$amountCredited','','Credit','$icurrency','$ptype','$remark','successful','$final_date_time','$receiverAcctId','$receiverBalance','$withdrawalBalance')") or die ("Error: " . mysqli_error($link));
            	    
        if($withdrawalBalance < $sms_charges){
        	        
        	  echo "";
        	        
        }else{
        	
        	$myRemainingBalance = $withdrawalBalance - $sms_charges;
            mysqli_query($link, "INSERT INTO wallet_history VALUE(null,'$institution_id','$refid','$phone','','$sms_charges','Debit','NGN','Charges','SMS Content: $sms','successful','$final_date_time','$iuid','$myRemainingBalance','')");
            $update = mysqli_query($link, "UPDATE user SET transfer_balance = '$myRemainingBalance' WHERE id = '$iuid'") or die (mysqli_error($link));
            
            include("../cron/send_general_sms.php");
        	        
        }
        mysqli_query($link, "DELETE FROM otp_confirmation WHERE userid = '$iuid' AND otp_code = '$myotp' AND status = 'Pending'");
        include("alert_sender/send_sp2ptransfer_alertemail.php");
        
        echo "<div class='alert bg-blue'>Transaction Successful!</div>";
        echo '<meta http-equiv="refresh" content="10;url=wallet-towallet.php?id='.$_SESSION['tid'].'&&mid=NDA0&&tab=tab_1">';
	    
	}
    
}
?>



<?php
if(!(isset($_GET['otp']) || isset($_GET['pin'])))
{
?>
             <div class="box-body">

                 <div class="form-group">
                    <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Withdraw From</label>
                    <div class="col-sm-6">
                    <?php
                    $uid = $_GET['uid'];
                    $searchVA = mysqli_query($link, "SELECT * FROM virtual_account WHERE account_number = '$uid'");
                    $fetchVA = mysqli_fetch_array($searchVA);
                    if(isset($_GET['uid'])){
                        $inst_Id = $fetchVA['companyid'];

                        $search_memset = mysqli_query($link, "SELECT * FROM member_settings WHERE companyid = '$inst_Id'");
                        $detect_memset = mysqli_fetch_array($search_memset);
                    ?>
                      <input name="author" type="hidden" class="form-control" value="<?php echo $uid; ?>">
                      <input name="authorName" type="text" class="form-control" value="<?php echo strtoupper($fetchVA['account_name'])." (".strtoupper($detect_memset['cname']).")"; ?>" readonly required>
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
                    <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Amount to Withdraw</label>
                    <div class="col-sm-6">
                        <input name="amount" type="number" class="form-control" placeholder="Enter Amount Here" required>
                    </div>
                    <label for="" class="col-sm-3 control-label"></label>
                  </div>
                  
                  <div class="form-group">
                    <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Remark</label>
                    <div class="col-sm-6">
                        <textarea name="remark"  class="form-control" rows="2" cols="80" placeholder="e.g Manual Withdrawal from <?php echo $fetchVA['account_name']; ?> wallet"></textarea>
                    </div>
                    <label for="" class="col-sm-3 control-label"></label>
                  </div>

                  <div class="form-group">
                    <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Transaction Pin</label>
                    <div class="col-sm-6">
                        <input name="tpin" type="password" inputmode="numeric" pattern="[0-9]*" class="form-control" placeholder="Enter Your Transaction Pin" required>
                    </div>
                    <label for="" class="col-sm-3 control-label"></label>
                  </div>

                </div>
                
                <div class="form-group" align="right">
                    <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;"></label>
                    <div class="col-sm-6">
                        <button name="osavings" type="submit" class="btn bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>"><i class="fa fa-minus">&nbsp;Withdraw Fund</i></button>
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