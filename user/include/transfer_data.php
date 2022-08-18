<div class="box-body">
             <div class="table-responsive"> 
            <div class="box-body">
<?php

require_once "../config/nipBankTransfer_class.php";

if(isMobileDevice()){
    //Your content or code for mobile devices goes here
?>

<button type="button" class="btn btn-flat bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>" align="left" disabled>&nbsp;<b>Wallet Balance:</b>&nbsp;
<strong class="alert bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">
<?php
echo $bbcurrency.number_format($bwallet_balance,2,'.',',');
?> 
</strong>
  </button>


<?php
}
else{
    ?>
    
            <a href="dashboard.php?tid=<?php echo $_SESSION['tid']; ?>&&acn=<?php echo $_GET['acn']; ?>&&mid=NDAx"><button type="button" class="btn btn-flat bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>"><i class="fa fa-mail-reply-all"></i>&nbsp;Back</button> </a> 
<button type="button" class="btn btn-flat bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>" align="left" disabled>&nbsp;<b>Wallet Balance:</b>&nbsp;
<strong class="alert bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">
<?php
echo $bbcurrency.number_format($bwallet_balance,2,'.',',');
?> 
</strong>
  </button>
  
<?php    
}
$my_systemset = mysqli_query($link, "SELECT * FROM systemset");
$my_row1 = mysqli_fetch_object($my_systemset);
$intlpayment_charges = $my_row1->intlpayment_charges;
$transfer_charges = $my_row1->transfer_charges;
?>
<hr>
<div class="alert bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>">
    <p><b style="font-size:18px;">NOTICE:</b> Transfer charges to any local bank in Nigeria is: <b><?php echo $my_row1->currency.$transfer_charges; ?></b> WHILE <b><?php echo ($intlpayment_charges * 100); ?>%</b> will be charged for every international transfer and other mobile money wallet.
    </p>
</div>
</hr>

            <div class="col-md-14">
            <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
             <li <?php echo ($_GET['tab'] == 'tab_1') ? "class='active'" : ''; ?>><a href="transfer.php?tid=<?php echo $_SESSION['tid']; ?>&&acn=<?php echo $_GET['acn']; ?>&&mid=NDA4&&tab=tab_1">African Bank Account Transfers</a></li>

             <li <?php echo ($_GET['tab'] == 'tab_2') ? "class='active'" : ''; ?>><a href="transfer.php?id=<?php echo $_SESSION['tid']; ?>&&acn=<?php echo $_GET['acn']; ?>&&mid=NDA4&&tab=tab_2">International Transfers</a></li>

              <li <?php echo ($_GET['tab'] == 'tab_3') ? "class='active'" : ''; ?>><a href="transfer.php?id=<?php echo $_SESSION['tid']; ?>&&acn=<?php echo $_GET['acn']; ?>&&mid=NDA4&&tab=tab_3">Mpesa Mobile Money Transfer</a></li>

              <li <?php echo ($_GET['tab'] == 'tab_4') ? "class='active'" : ''; ?>><a href="transfer.php?id=<?php echo $_SESSION['tid']; ?>&&acn=<?php echo $_GET['acn']; ?>&&mid=NDA4&&tab=tab_4">Ghana Mobile Money Transfer</a></li>

              <li <?php echo ($_GET['tab'] == 'tab_5') ? "class='active'" : ''; ?>><a href="transfer.php?id=<?php echo $_SESSION['tid']; ?>&&acn=<?php echo $_GET['acn']; ?>&&mid=NDA4&&tab=tab_5">Ugandan Mobile Money Transfer</a></li>
              
              <li <?php echo ($_GET['tab'] == 'tab_8') ? "class='active'" : ''; ?>><a href="transfer.php?id=<?php echo $_SESSION['tid']; ?>&&acn=<?php echo $_GET['acn']; ?>&&mid=NDA4&&tab=tab_8">Check Exchange Rate</a></li>
              
              <!--<li <?php echo ($_GET['tab'] == 'tab_9') ? "class='active'" : ''; ?>><a href="transfer.php?id=<?php echo $_SESSION['tid']; ?>&&acn=<?php echo $_GET['acn']; ?>&&mid=NDA4&&tab=tab_9">Transfer to Payvice Wallet</a></li>-->
              
              <li <?php echo ($_GET['tab'] == 'tab_10') ? "class='active'" : ''; ?>><?php echo ($card_id != "" && $issurer == "Mastercard") ? '<a href="transfer.php?id='.$_SESSION['tid'].'&&acn='.$_GET['acn'].'&&mid=OTAw&&tab=tab_10">Transfer to My Mastercard</a>' : ''; ?></li>
              
              <li <?php echo ($_GET['tab'] == 'tab_11') ? "class='active'" : ''; ?>><?php echo ($card_id != "" && $issurer == "VerveCard") ? '<a href="transfer.php?id='.$_SESSION['tid'].'&&acn='.$_GET['acn'].'&&mid=OTAw&&tab=tab_11">Transfer to My Vervecard</a>' : ''; ?></li>
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
if(isset($_POST['africa_save']))
{
    include("../config/restful_apicalls.php");
    
    $reference =  "EA-afrTransva-".myreference(10);
    $afcountry =  mysqli_real_escape_string($link, $_POST['country']);
    $account_number =  mysqli_real_escape_string($link, $_POST['account_number']);
    $bank_code =  mysqli_real_escape_string($link, $_POST['bank_code']);
	$b_name = mysqli_real_escape_string($link, $_POST['b_name']);
	$amount = preg_replace('/[^0-9.]/', '', mysqli_real_escape_string($link, $_POST['amt_totransfer']));
    $afnarration =  mysqli_real_escape_string($link, $_POST['reasons']);
    $SessionID = ($inip_route == "SterlingBank") ? mysqli_real_escape_string($link, $_POST['stSessionId']) : "";
    $NEStatus = ($inip_route == "SterlingBank") ? mysqli_real_escape_string($link, $_POST['NEStatus']) : "";
    $senderAcctNo = ($inip_route == "SterlingBank") ? mysqli_real_escape_string($link, $_POST['FromAcct']) : "";
	$phone = $phone2;
	$currenctdate = date("Y-m-d H:i:s");
	
	$query = mysqli_query($link, "SELECT * FROM systemset") or die (mysqli_error($link));
    $r = mysqli_fetch_object($query);
    $transfer_charges = $r->transfer_charges;
    $sysabb = ($bsender_id == "") ? $r->abb : $bsender_id;
    
    $otp_code = ($selfServiceTransAuth == "On" || $otp_option == "Yes" || $otp_option == "Both") ? substr((uniqid(rand(),1)),3,6) : $myuepin;
    
    $otpChecker = ($selfServiceTransAuth == "On" || $otp_option == "Yes" || $otp_option == "Both") ? "otp" : "pin";
								
	$sms = "$sysabb>>>Dear $myln! Your One Time Password is $otp_code";
	
	$maintenance_row = mysqli_num_rows($bsearch_maintenance_model);
    $debitWAllet = ($bgetSMS_ProviderNum == 1 || ($maintenance_row == 1 && $bbilling_type == "PAYGException")) ? "No" : "Yes";
    
    $search_insti = mysqli_query($link, "SELECT * FROM institution_data WHERE institution_id = '$bbranchid'");
    $fetch_myinst = mysqli_fetch_array($search_insti);
    $iwallet_balance = $fetch_myinst['wallet_balance'];
                            	
    $sms_rate = $r->fax;
    $imywallet_balance = $iwallet_balance - $sms_rate;
    $sms_refid = "EA-smsCharges-".time();
	
	//New AMount + Charges
    $newAmount = $amount + $transfer_charges;
    $senderBalance = $bwallet_balance - $newAmount;
	
	//Data Parser (array size = 6)
    $mydata = $reference."|".$bank_code."|".$account_number."|".$b_name."|".$amount."|".$newAmount."|".$afnarration."|".$SessionID."|".$NEStatus."|".$senderAcctNo."|".$bwallet_balance;
    
    $key = base64_encode($mydata);

    if($inip_route == ""){

        echo "<div class='alert bg-orange'>Sorry! Service not active yet, please contact the administrator to activate this features!!</div>";

    }
    elseif($bactive_status == "Suspended"){
        
        echo "<div class='alert bg-orange'>Oops! You are unable to make transfer at the moment!!</div>";
        
    }
    elseif($amount <= 0){

        echo "<div class='alert bg-orange'>Sorry! Invalid amount entered!!</div>";

    }
	elseif($bwallet_balance < $newAmount){
	    
	    echo "<div class='alert bg-orange'>Oops! You have Insufficient Fund in your Wallet!!</div>";
	    
    }
    elseif($amount > $btransferLimitPerTrans){

        echo "<div class='alert bg-orange'>Sorry! You can not transfer more than ".$bbcurrency.number_format($btransferLimitPerTrans,2,'.',',')." at once!!</div>";

    }
    elseif($bmyDailyTransferLimit == $btransferLimitPerDay || (($amount + $bmyDailyTransferLimit) > $btransferLimitPerDay)){

        echo "<div class='alert bg-orange'>Oops! You have reached your daily limit of ".$bbcurrency.number_format($btransferLimitPerDay,2,'.',',')."</div>";

    }
    elseif($bvirtual_acctno === "$account_number" || $acctno === "$account_number"){
        
        mysqli_query($link, "UPDATE borrowers SET status = 'Suspended' WHERE account = '$acctno'");
        mysqli_query($link, "INSERT INTO wallet_case VALUES(null,'$bbranchid','$bsbranchid','System','$acctno','Suspended','Frudulent Act Detected','$currenctdate')");
        
        echo "<div class='alert bg-orange'>Oops! Request Failed!!</div>";
        
    }
	else{
	    
	    $update = mysqli_query($link,"INSERT INTO otp_confirmation VALUES(null,'$acctno','$otp_code','$mydata','Pending','$currenctdate')") or die(mysqli_error($link));
	    
        if(!$update)
        {
            echo "<div class='alert bg-orange'>Unable to Transfer Fund.....Please try again later</div>";
        }
        else{
            $iuid = "";
            ($debitWAllet == "No" && ($selfServiceTransAuth == "On" || $otp_option == "Yes" || $otp_option == "Both") ? $sendSMS->instGeneralAlert($bozeki_password, $bozeki_url, $sysabb, $phone2, $sms, $bbranchid, $sms_refid, $sms_rate, $iuid, $imywallet_balance, $debitWallet) : ($debitWAllet == "Yes" && $sms_rate <= $iwallet_balance && ($selfServiceTransAuth == "On" || $otp_option == "Yes" || $otp_option == "Both") ? $sendSMS->instGeneralAlert($bozeki_password, $bozeki_url, $isenderid, $phone2, $sms, $bbranchid, $sms_refid, $sms_rate, $iuid, $imywallet_balance, $debitWallet) : ""));
            echo ($selfServiceTransAuth == "On" || $otp_option == "Yes" || $otp_option == "Both") ? "<div align='center'><img src='".$fetchsys_config['file_baseurl']."checkmark.gif'><p style='color: #38A1F3;'>Great! Otp has been sent to your mobile phone to complete this transaction!!</p></div>" : "<div align='center'><img src='".$fetchsys_config['file_baseurl']."checkmark.gif'><p style='color: #38A1F3;'>Transfer Request Received Successfully! Wait a moment to complete the transaction with your Pin</p></div>";
            echo '<meta http-equiv="refresh" content="2;url=transfer.php?tid='.$_SESSION['tid'].'&&acn='.$acctno.'&&mid=NDA4&&tab=tab_1&&token='.$key.'&&'.$otpChecker.'">';
        }
        
	}
	
}
?>



<?php
if (isset($_POST['confirm']))
{

    $myotp = $_POST['otp'];
    $token = base64_decode($_GET['token']);
    $currenctdate = date("Y-m-d H:i:s");
    
    $verify_otp = mysqli_query($link, "SELECT * FROM otp_confirmation WHERE otp_code = '$myotp' AND userid = '$acctno' AND status = 'Pending' AND data = '$token'");
	$fetch_data = mysqli_fetch_array($verify_otp);
	$otpnum = mysqli_num_rows($verify_otp);
	
	$verify_otp1 = mysqli_query($link, "SELECT * FROM otp_confirmation WHERE otp_code = '$myotp' AND userid = '$acctno' AND status = 'Pending'");
	$otpnum1 = mysqli_num_rows($verify_otp1);
	
	if($otpnum1 > 1){
		
		mysqli_query($link, "UPDATE borrowers SET status = 'Suspended' WHERE account = '$acctno'");
        mysqli_query($link, "INSERT INTO wallet_case VALUES(null,'$bbranchid','$bsbranchid','System','$acctno','Suspended','Frudulent Act Detected','$currenctdate')");
        mysqli_query($link, "DELETE FROM otp_confirmation WHERE userid = '$acctno' AND otp_code = '$myotp' AND status = 'Pending'");
        
	    echo "<div class='alert bg-orange'>Duplicate Transaction is not allowed!!</div>";
	    echo '<meta http-equiv="refresh" content="2;url=transfer.php?tid='.$_SESSION['tid'].'&&acn='.$acctno.'&&mid=NDA4&&tab=tab_1">';
						        
	}				    
	elseif($otpnum == 0 && $otpnum1 == 0){
						        
	    echo "<div class='alert bg-orange'>Opps!...Invalid Credential!!</div>";
						        
	}else{
	    
	    $concat = $fetch_data['data'];
    
        $datetime = $fetch_data['datetime'];
                                
        $parameter = (explode('|',$concat));
        
        $tReference = $parameter[0];
        $recipientBankCode = $parameter[1];
	    $recipientAcctNo = $parameter[2];
	    $accountName = $parameter[3];
	    $amountWithNoCharges = $parameter[4];
	    $amountWithCharges = $parameter[5];
        $narration = $parameter[6];
        $SessionID = $parameter[7];
        $NEStatus = $parameter[8];
        $senderAcctNo = $parameter[9];
        $balanceLeft = $parameter[10];
        $currency = $bbcurrency;
        $originatorName = $accountName;
	    $tramsferid = substr((uniqid(rand(),1)),3,6);
	    $totalWalletBalance = $bwallet_balance - $amountWithCharges;
        $calcCharges = $amountWithCharges - $amountWithNoCharges;
        
        //Get my wallet balance after debiting
        $senderBalance = $totalWalletBalance;
        $senderEmail = $email2;
        $senderName = $bname;
        $merchantName = $binst_name;
        $liveBalanceLeft = ($balanceLeft > $bwallet_balance) ? $bwallet_balance : $balanceLeft;
        
        if($bwallet_balance < $amountWithCharges){
            
            mysqli_query($link, "DELETE FROM otp_confirmation WHERE userid = '$acctno' AND otp_code = '$myotp' AND status = 'Pending'");
            
            echo "<div class='alert alert-danger'>Oops! You have insufficient fund in your Wallet!!</div>";
            echo '<meta http-equiv="refresh" content="2;url=transfer.php?tid='.$_SESSION['tid'].'&&acn='.$acctno.'&&mid=NDA4&&tab=tab_1">';
            
        }
        elseif($inip_route == "Wallet Africa"){
                
                //Debit Customer Wallet
                mysqli_query($link, "UPDATE borrowers SET wallet_balance = '$senderBalance' WHERE account = '$acctno'");
                
                //Fetch Bank Name
                $search_bankname = mysqli_query($link, "SELECT * FROM bank_list WHERE bankcode = '$recipientBankCode'");
                $fetch_bankname = mysqli_fetch_array($search_bankname);
                $mybank_name = $fetch_bankname['bankname'];
    
                $transactionDateTime = date("Y-m-d h:i:s");
                //8 rows [0 - 8]
                $dataToProcess = $tReference."|".$amountWithNoCharges."|".$narration."|".$accountName."|".$mybank_name."|".$senderName."|".$recipientAcctNo."|".$recipientBankCode."|".$senderBalance;
                //insert txt waiting list
                $mytxtstatus = 'Pending';
                mysqli_query($link, "INSERT INTO api_txtwaitinglist VALUE(null,'$acctno','$tReference','$dataToProcess','$mytxtstatus','$transactionDateTime')");
    
                //fetch endpoint
                $search_restapi1 = mysqli_query($link, "SELECT * FROM restful_apisetup WHERE api_name = 'walletafrica_transfer'");
                $fetch_restapi1 = mysqli_fetch_object($search_restapi1);
                $client = $fetch_restapi1->api_url;
    
                $result = $new->walletAfricaNIPBankTransfer($walletafrica_skey,$recipientBankCode,$recipientAcctNo,$accountName,$tReference,$amountWithNoCharges,$client);
                
                $decodePro = json_decode($result, true);
    
                if($decodePro['ResponseCode'] == "100" || $decodePro['ResponseCode'] == "200" || $result['ResponseCode'] == "100" || $result['ResponseCode'] == "200"){
                    
                    $gatewayResponse = $decodePro['Message'];
                    $recipient = $recipientAcctNo.', '.$accountName.', '.$mybank_name;
    
                    //UPDATE WAITING TXT
                    mysqli_query($link, "UPDATE api_txtwaitinglist SET status = '$result' WHERE userid = '$acctno' AND refid = '$tReference' AND status = '$mytxtstatus'");
                    
                    //Log successful transaction history
                    mysqli_query($link, "INSERT INTO wallet_history VALUE(null,'$bbranchid','$tReference','$recipient','','$amountWithNoCharges','Debit','$bbcurrency','BANK_TRANSFER','Transfer to $recipient','successful','$transactionDateTime','$acctno','$senderBalance','$inip_route')");
                    mysqli_query($link, "INSERT INTO wallet_history VALUE(null,'$bbranchid','$tReference','$recipient','','$calcCharges','Debit','$bbcurrency','Charges','Transfer to $recipient, Gateway Response: $gatewayResponse','successful','$transactionDateTime','$acctno','$senderBalance','$inip_route')");                
    
                    $sendSMS->bankTransferEmailNotifier($senderEmail, $tReference, $senderName, $transactionDateTime, $accountName, $recipientAcctNo, $mybank_name, $merchantName, $bbcurrency, $amountWithNoCharges, $senderBalance, $emailConfigStatus, $fetch_emailConfig);
    
                    mysqli_query($link, "DELETE FROM otp_confirmation WHERE userid = '$acctno' AND otp_code = '$myotp' AND status = 'Pending'");
                    
                    echo "<div class='alert bg-blue'>Transaction Successful! Current Wallet Balance is: <b style='color: white;'>".$bbcurrency.number_format($senderBalance,2,'.',',')."</b></div>";
                    echo '<meta http-equiv="refresh" content="10;url=transfer.php?tid='.$_SESSION['tid'].'&&acn='.$acctno.'&&mid=NDA4&&tab=tab_1">';
                        
                }
                else{

                    $defaultBalance = $liveBalanceLeft;
    
                    //Reverse to Customer Wallet
                    mysqli_query($link, "UPDATE borrowers SET wallet_balance = '$defaultBalance' WHERE account = '$acctno'");
                    
                    //UPDATE WAITING TXT
                    mysqli_query($link, "UPDATE api_txtwaitinglist SET status = '$result' WHERE userid = '$acctno' AND refid = '$tReference' AND status = '$mytxtstatus'");
    
                    mysqli_query($link, "DELETE FROM otp_confirmation WHERE userid = '$acctno' AND otp_code = '$myotp' AND status = 'Pending'");
                      
                    echo "<div class='alert bg-orange'>Sorry! Unable to conclude transaction, please try again later!!</div>";
                    echo '<meta http-equiv="refresh" content="5;url=transfer.php?tid='.$_SESSION['tid'].'&&acn='.$acctno.'&&mid=NDA4&&tab=tab_1">';
                    
                }
    
            }
            elseif($inip_route == "ProvidusBank"){
                
                //Debit Customer Wallet
                mysqli_query($link, "UPDATE borrowers SET wallet_balance = '$senderBalance' WHERE account = '$acctno'");
                
                //Fetch Bank Name
                $search_bankname = mysqli_query($link, "SELECT * FROM bank_list2 WHERE bankcode = '$recipientBankCode'");
                $fetch_bankname = mysqli_fetch_array($search_bankname);
                $mybank_name = $fetch_bankname['bankname'];
    
                $transactionDateTime = date("Y-m-d h:i:s");
                //8 rows [0 - 8]
                $dataToProcess = $tReference."|".$amountWithNoCharges."|".$narration."|".$accountName."|".$mybank_name."|".$senderName."|".$recipientAcctNo."|".$recipientBankCode."|".$senderBalance;
                //insert txt waiting list
                $mytxtstatus = 'Pending';
                mysqli_query($link, "INSERT INTO api_txtwaitinglist VALUE(null,'$acctno','$tReference','$dataToProcess','$mytxtstatus','$transactionDateTime')");
    
                //fetch endpoint
                $search_restapi1 = mysqli_query($link, "SELECT * FROM restful_apisetup WHERE api_name = 'providus_endpoint'");
                $fetch_restapi1 = mysqli_fetch_object($search_restapi1);
                $api_url = $fetch_restapi1->api_url;
    
                $client = new SoapClient($api_url);
    
                $result = $new->providusNIPBankTransfer($providusUName,$providusPass,$amountWithNoCharges,$currency,$narration,$tReference,$recipientAcctNo,$recipientBankCode,$accountName,$originatorName,$client);
    
                if($result['responseCode'] == "00"){
    
                    $gatewayResponse = $result['responseMessage'];
                    $recipient = $recipientAcctNo.', '.$accountName.', '.$mybank_name;
    
                    //UPDATE WAITING TXT
                    mysqli_query($link, "UPDATE api_txtwaitinglist SET status = '$result' WHERE userid = '$acctno' AND refid = '$tReference' AND status = '$mytxtstatus'");
                    
                    //Log successful transaction history
                    mysqli_query($link, "INSERT INTO wallet_history VALUE(null,'$bbranchid','$tReference','$recipient','','$amountWithNoCharges','Debit','$bbcurrency','BANK_TRANSFER','Transfer to $recipient','successful','$transactionDateTime','$acctno','$senderBalance','$inip_route')");
                    mysqli_query($link, "INSERT INTO wallet_history VALUE(null,'$bbranchid','$tReference','$recipient','','$calcCharges','Debit','$bbcurrency','Charges','Transfer to $recipient, Gateway Response: $gatewayResponse','successful','$transactionDateTime','$acctno','$senderBalance','$inip_route')");
    
                    $sendSMS->bankTransferEmailNotifier($senderEmail, $tReference, $senderName, $transactionDateTime, $accountName, $recipientAcctNo, $mybank_name, $merchantName, $bbcurrency, $amountWithNoCharges, $senderBalance, $emailConfigStatus, $fetch_emailConfig);
    
                    mysqli_query($link, "DELETE FROM otp_confirmation WHERE userid = '$acctno' AND otp_code = '$myotp' AND status = 'Pending'");
                        
                    echo "<div class='alert bg-blue'>Transaction Successful! Current Wallet Balance is: <b style='color: white;'>".$bbcurrency.number_format($senderBalance,2,'.',',')."</b></div>";
                    echo '<meta http-equiv="refresh" content="10;url=transfer.php?tid='.$_SESSION['tid'].'&&acn='.$acctno.'&&mid=NDA4&&tab=tab_1">';
    
                }
                else{
    
                    $defaultBalance = $liveBalanceLeft;
    
                    //Reverse to Customer Wallet
                    mysqli_query($link, "UPDATE borrowers SET wallet_balance = '$defaultBalance' WHERE account = '$acctno'");
    
                    //UPDATE WAITING TXT
                    mysqli_query($link, "UPDATE api_txtwaitinglist SET status = '$result' WHERE userid = '$acctno' AND refid = '$tReference' AND status = '$mytxtstatus'");
    
                    mysqli_query($link, "DELETE FROM otp_confirmation WHERE userid = '$acctno' AND otp_code = '$myotp' AND status = 'Pending'");
                     
                    echo "<div class='alert bg-orange'>Sorry! Unable to conclude transaction, please try again later!!</div>";
                    echo '<meta http-equiv="refresh" content="5;url=transfer.php?tid='.$_SESSION['tid'].'&&acn='.$acctno.'&&mid=NDA4&&tab=tab_1">';
                
                }
    
            }elseif($inip_route == "SterlingBank"){
                
                //Debit Customer Wallet
                mysqli_query($link, "UPDATE borrowers SET wallet_balance = '$senderBalance' WHERE account = '$acctno'");
                
                //Fetch Bank Name
                $search_bankname = mysqli_query($link, "SELECT * FROM bank_list3 WHERE bankcode = '$recipientBankCode'");
                $fetch_bankname = mysqli_fetch_array($search_bankname);
                $mybank_name = $fetch_bankname['bankname'];
    
                $transactionDateTime = date("Y-m-d h:i:s");
                //9 rows [0 - 9]
                $dataToProcess = $tramsferid."|".$SessionID."|".$senderAcctNo."|".$recipientAcctNo."|".$amountWithNoCharges."|".$recipientBankCode."|".$NEStatus."|".$accountName."|".$tReference."|".$senderBalance;
                //insert txt waiting list
                $mytxtstatus = 'Pending';
                mysqli_query($link, "INSERT INTO api_txtwaitinglist VALUE(null,'$acctno','$tReference','$dataToProcess','$mytxtstatus','$transactionDateTime')");
    
                $search_restapi1 = mysqli_query($link, "SELECT * FROM restful_apisetup WHERE api_name = 'sterling_InterbankTransferReq'");
                $fetch_restapi1 = mysqli_fetch_object($search_restapi1);
                $client = $fetch_restapi1->api_url;
    
                $data_to_send_server = array(
                    "Referenceid"=>$tramsferid,
                    "SessionID"=>$SessionID,
                    "FromAccount"=>$senderAcctNo,
                    "ToAccount"=>$recipientAcctNo,
                    "Amount"=>$amountWithNoCharges,
                    "DestinationBankCode"=>$recipientBankCode,
                    "NEResponse"=>$NEStatus,
                    "BenefiName"=>$accountName,
                    "PaymentReference"=>$tReference,
                    "RequestType"=>160,
                    "Translocation"=>"100,100"
                );
    
                $process = $new->sterlingNIPBankTransfer($data_to_send_server,$client);
    
                $processReturn = $process['data'];
    
                if($processReturn['status'] == "00"){
    
                    $gatewayResponse = $processReturn['ResponseText'];
                    $recipient = $recipientAcctNo.', '.$accountName.', '.$mybank_name;
    
                    //UPDATE WAITING TXT
                    mysqli_query($link, "UPDATE api_txtwaitinglist SET status = '$processReturn' WHERE userid = '$acctno' AND refid = '$tReference' AND status = '$mytxtstatus'");
    
                    //Log successful transaction history
                    mysqli_query($link, "INSERT INTO wallet_history VALUE(null,'$bbranchid','$tReference','$recipient','','$amountWithNoCharges','Debit','$bbcurrency','BANK_TRANSFER','Transfer to $recipient','successful','$transactionDateTime','$acctno','$senderBalance','$inip_route')");
                    mysqli_query($link, "INSERT INTO wallet_history VALUE(null,'$bbranchid','$tReference','$recipient','','$calcCharges','Debit','$bbcurrency','Charges','Transfer to $recipient, Gateway Response: $gatewayResponse','successful','$transactionDateTime','$acctno','$senderBalance','$inip_route')");
    
                    $sendSMS->bankTransferEmailNotifier($senderEmail, $tReference, $senderName, $transactionDateTime, $accountName, $recipientAcctNo, $mybank_name, $merchantName, $bbcurrency, $amountWithNoCharges, $senderBalance, $emailConfigStatus, $fetch_emailConfig);
    
                    mysqli_query($link, "DELETE FROM otp_confirmation WHERE userid = '$acctno' AND otp_code = '$myotp' AND status = 'Pending'");
                        
                    echo "<div class='alert bg-blue'>Transaction Successful! Current Wallet Balance is: <b style='color: white;'>".$bbcurrency.number_format($senderBalance,2,'.',',')."</b></div>";
                    echo '<meta http-equiv="refresh" content="10;url=transfer.php?tid='.$_SESSION['tid'].'&&acn='.$acctno.'&&mid=NDA4&&tab=tab_1">';
    
                }
                else{
    
                    $defaultBalance = $liveBalanceLeft;
    
                    //Reverse to Customer Wallet
                    mysqli_query($link, "UPDATE borrowers SET wallet_balance = '$defaultBalance' WHERE account = '$acctno'");
    
                    //UPDATE WAITING TXT
                    mysqli_query($link, "UPDATE api_txtwaitinglist SET status = '$processReturn' WHERE userid = '$acctno' AND refid = '$tReference' AND status = '$mytxtstatus'");
    
                    mysqli_query($link, "DELETE FROM otp_confirmation WHERE userid = '$acctno' AND otp_code = '$myotp' AND status = 'Pending'");
                    
                    echo "<div class='alert bg-orange'>Opps!...Unable to conclude transaction, Please Try again later</div>";
                    echo '<meta http-equiv="refresh" content="5;url=transfer.php?tid='.$_SESSION['tid'].'&&acn='.$acctno.'&&mid=NDA4&&tab=tab_1">';
    
                }
    
            }
            elseif($inip_route == "RubiesBank"){
                
                //Debit Customer Wallet
                mysqli_query($link, "UPDATE borrowers SET wallet_balance = '$senderBalance' WHERE account = '$acctno'");
                
                //Fetch Bank Name
                $search_bankname = mysqli_query($link, "SELECT * FROM bank_list4 WHERE bankcode = '$recipientBankCode'");
                $fetch_bankname = mysqli_fetch_array($search_bankname);
                $mybank_name = $fetch_bankname['bankname'];
                $draccountname = $bname;
    
                $transactionDateTime = date("Y-m-d h:i:s");
                //8 rows [0 - 8]
                $dataToProcess = $tReference."|".$amountWithNoCharges."|".$narration."|".$accountName."|".$mybank_name."|".$draccountname."|".$recipientAcctNo."|".$recipientBankCode."|".$senderBalance;
                //insert txt waiting list
                $mytxtstatus = 'Pending';
                mysqli_query($link, "INSERT INTO api_txtwaitinglist VALUE(null,'$acctno','$tReference','$dataToProcess','$mytxtstatus','$transactionDateTime')");
    
                $result = $new->rubiesNIPBankTransfer($tReference,$amountWithNoCharges,$narration,$accountName,$recipientBankCode,$recipientAcctNo,$rubbiesSecKey,$link,$draccountname);
    
                $rubbies_generate = json_decode($result, true);
    
                if($rubbies_generate['responsecode'] == "00" && $rubbies_generate['nibsscode'] == "00"){
    
                    $gatewayResponse = $rubbies_generate['nibssresponsemessage'];
                    $recipient = $recipientAcctNo.', '.$accountName.', '.$mybank_name;
    
                    //UPDATE WAITING TXT
                    mysqli_query($link, "UPDATE api_txtwaitinglist SET status = '$result' WHERE userid = '$acctno' AND refid = '$tReference' AND status = '$mytxtstatus'");
                    
                    //Log successful transaction history
                    mysqli_query($link, "INSERT INTO wallet_history VALUE(null,'$bbranchid','$tReference','$recipient','','$amountWithNoCharges','Debit','$bbcurrency','BANK_TRANSFER','Transfer to $recipient','successful','$transactionDateTime','$acctno','$senderBalance','$inip_route')");
                    mysqli_query($link, "INSERT INTO wallet_history VALUE(null,'$bbranchid','$tReference','$recipient','','$calcCharges','Debit','$bbcurrency','Charges','Transfer to $recipient, Gateway Response: $gatewayResponse','successful','$transactionDateTime','$acctno','$senderBalance','$inip_route')");
    
                    $sendSMS->bankTransferEmailNotifier($senderEmail, $tReference, $senderName, $transactionDateTime, $accountName, $recipientAcctNo, $mybank_name, $merchantName, $bbcurrency, $amountWithNoCharges, $senderBalance, $emailConfigStatus, $fetch_emailConfig);
    
                    mysqli_query($link, "DELETE FROM otp_confirmation WHERE userid = '$acctno' AND otp_code = '$myotp' AND status = 'Pending'");
                        
                    echo "<div class='alert bg-blue'>Transaction Successful! Current Wallet Balance is: <b style='color: white;'>".$bbcurrency.number_format($senderBalance,2,'.',',')."</b></div>";
                    echo '<meta http-equiv="refresh" content="10;url=transfer.php?tid='.$_SESSION['tid'].'&&acn='.$acctno.'&&mid=NDA4&&tab=tab_1">';
    
                }
                else{
    
                    $defaultBalance = $liveBalanceLeft;
    
                    //Reverse to Customer Wallet
                    mysqli_query($link, "UPDATE borrowers SET wallet_balance = '$defaultBalance' WHERE account = '$acctno'");
    
                    //UPDATE WAITING TXT
                    mysqli_query($link, "UPDATE api_txtwaitinglist SET status = '$result' WHERE userid = '$acctno' AND refid = '$tReference' AND status = '$mytxtstatus'");
    
                    mysqli_query($link, "DELETE FROM otp_confirmation WHERE userid = '$acctno' AND otp_code = '$myotp' AND status = 'Pending'");
                     
                    echo "<div class='alert bg-orange'>Sorry! Unable to conclude transaction, please try again later!!</div>";
                    echo '<meta http-equiv="refresh" content="5;url=transfer.php?tid='.$_SESSION['tid'].'&&acn='.$acctno.'&&mid=NDA4&&tab=tab_1">';
                
                }
    
            }
            elseif($inip_route == "PrimeAirtime"){
                
                //Debit Customer Wallet
                mysqli_query($link, "UPDATE borrowers SET wallet_balance = '$senderBalance' WHERE account = '$acctno'");
                
                //Fetch Bank Name
                $search_bankname = mysqli_query($link, "SELECT * FROM bank_list WHERE bankcode = '$recipientBankCode'");
                $fetch_bankname = mysqli_fetch_array($search_bankname);
                $mybank_name = $fetch_bankname['bankname'];
    
                $transactionDateTime = date("Y-m-d h:i:s");
                //8 rows [0 - 8]
                $dataToProcess = $tReference."|".$amountWithNoCharges."|".$narration."|".$accountName."|".$mybank_name."|".$senderName."|".$recipientAcctNo."|".$recipientBankCode."|".$senderBalance;
                //insert txt waiting list
                $mytxtstatus = 'Pending';
                mysqli_query($link, "INSERT INTO api_txtwaitinglist VALUE(null,'$acctno','$tReference','$dataToProcess','$mytxtstatus','$transactionDateTime')");
    
                $result = $new->primeAirtimeFT($link,$accessToken,$recipientBankCode,$recipientAcctNo,$amountWithNoCharges,$narration,$tReference);
                
                $decodePro = json_decode($result, true);
    
                if($decodePro['status'] == "201" || $decodePro['status'] == "200"){
                    
                    $gatewayResponse = $decodePro['message'];
                    $recipient = $recipientAcctNo.', '.$accountName.', '.$mybank_name;
    
                    //UPDATE WAITING TXT
                    mysqli_query($link, "UPDATE api_txtwaitinglist SET status = '$result' WHERE userid = '$acctno' AND refid = '$tReference' AND status = '$mytxtstatus'");
                    
                    //Log successful transaction history
                    mysqli_query($link, "INSERT INTO wallet_history VALUE(null,'$bbranchid','$tReference','$recipient','','$amountWithNoCharges','Debit','$bbcurrency','BANK_TRANSFER','Transfer to $recipient','successful','$transactionDateTime','$acctno','$senderBalance','$inip_route')");
                    mysqli_query($link, "INSERT INTO wallet_history VALUE(null,'$bbranchid','$tReference','$recipient','','$calcCharges','Debit','$bbcurrency','Charges','Transfer to $recipient, Gateway Response: $gatewayResponse','successful','$transactionDateTime','$acctno','$senderBalance','$inip_route')");                
    
                    $sendSMS->bankTransferEmailNotifier($senderEmail, $tReference, $senderName, $transactionDateTime, $accountName, $recipientAcctNo, $mybank_name, $merchantName, $bbcurrency, $amountWithNoCharges, $senderBalance, $emailConfigStatus, $fetch_emailConfig);
    
                    mysqli_query($link, "DELETE FROM otp_confirmation WHERE userid = '$acctno' AND otp_code = '$myotp' AND status = 'Pending'");
                        
                    echo "<div class='alert bg-blue'>Transaction Successful! Current Wallet Balance is: <b style='color: white;'>".$bbcurrency.number_format($senderBalance,2,'.',',')."</b></div>";
                    echo '<meta http-equiv="refresh" content="10;url=transfer.php?tid='.$_SESSION['tid'].'&&acn='.$acctno.'&&mid=NDA4&&tab=tab_1">';
                        
                }
                else{
    
                    $defaultBalance = $liveBalanceLeft;
    
                    //Reverse to Customer Wallet
                    mysqli_query($link, "UPDATE borrowers SET wallet_balance = '$defaultBalance' WHERE account = '$acctno'");
                    
                    //UPDATE WAITING TXT
                    mysqli_query($link, "UPDATE api_txtwaitinglist SET status = '$result' WHERE userid = '$acctno' AND refid = '$tReference' AND status = '$mytxtstatus'");
    
                    mysqli_query($link, "DELETE FROM otp_confirmation WHERE userid = '$acctno' AND otp_code = '$myotp' AND status = 'Pending'");
                      
                    echo "<div class='alert bg-orange'>Sorry! Unable to conclude transaction, please try again later!!</div>";
                    echo '<meta http-equiv="refresh" content="5;url=transfer.php?tid='.$_SESSION['tid'].'&&acn='.$acctno.'&&mid=NDA4&&tab=tab_1">';
                    
                }
    
            }
            elseif($inip_route == "SuntrustBank"){
                
                //Debit Customer Wallet
                mysqli_query($link, "UPDATE borrowers SET wallet_balance = '$senderBalance' WHERE account = '$acctno'");
                
                //Fetch Bank Name
                $search_bankname = mysqli_query($link, "SELECT * FROM bank_list WHERE bankcode = '$recipientBankCode'");
                $fetch_bankname = mysqli_fetch_array($search_bankname);
                $mybank_name = $fetch_bankname['bankname'];
    
                $transactionDateTime = date("Y-m-d h:i:s");
                //8 rows [0 - 8]
                $dataToProcess = $tReference."|".$amountWithNoCharges."|".$narration."|".$accountName."|".$mybank_name."|".$senderName."|".$recipientAcctNo."|".$recipientBankCode."|".$senderBalance;
                //insert txt waiting list
                $mytxtstatus = 'Pending';
                mysqli_query($link, "INSERT INTO api_txtwaitinglist VALUE(null,'$acctno','$tReference','$dataToProcess','$mytxtstatus','$transactionDateTime')");
    
                $result = $new->newSunTrustNIPBankTransfer($link,$tReference,$narration,$amountWithNoCharges,$recipientAcctNo,$recipientBankCode,$onePipeSKey,$onePipeApiKey);
                
                $decodePro = json_decode($result, true);
    
                $responseCode = $decodePro['data']['provider_response_code'];
    
                if($responseCode == "00"){
                
                    $gatewayResponse = $decodePro['message'];
                    $recipient = $recipientAcctNo.', '.$accountName.', '.$mybank_name;
                
                    //UPDATE WAITING TXT
                    mysqli_query($link, "UPDATE api_txtwaitinglist SET status = '$result' WHERE userid = '$acctno' AND refid = '$tReference' AND status = '$mytxtstatus'");
                
                    //Log successful transaction history
                    mysqli_query($link, "INSERT INTO wallet_history VALUE(null,'$bbranchid','$tReference','$recipient','','$amountWithNoCharges','Debit','$bbcurrency','BANK_TRANSFER','Transfer to $recipient','successful','$transactionDateTime','$acctno','$senderBalance','$inip_route')");
                    mysqli_query($link, "INSERT INTO wallet_history VALUE(null,'$bbranchid','$tReference','$recipient','','$calcCharges','Debit','$bbcurrency','Charges','Transfer to $recipient, Gateway Response: $gatewayResponse','successful','$transactionDateTime','$acctno','$senderBalance','$inip_route')");                
    
                    $sendSMS->bankTransferEmailNotifier($senderEmail, $tReference, $senderName, $transactionDateTime, $accountName, $recipientAcctNo, $mybank_name, $merchantName, $bbcurrency, $amountWithNoCharges, $senderBalance, $emailConfigStatus, $fetch_emailConfig);
    
                    mysqli_query($link, "DELETE FROM otp_confirmation WHERE userid = '$acctno' AND otp_code = '$myotp' AND status = 'Pending'");
                
                    echo "<div class='alert bg-blue'>Transaction Successful! Current Wallet Balance is: <b style='color: white;'>".$bbcurrency.number_format($senderBalance,2,'.',',')."</b></div>";
                    echo '<meta http-equiv="refresh" content="10;url=transfer.php?tid='.$_SESSION['tid'].'&&acn='.$acctno.'&&mid=NDA4&&tab=tab_1">';
                
                }
                else{
    
                    $defaultBalance = $liveBalanceLeft;
    
                    //Reverse to Customer Wallet
                    mysqli_query($link, "UPDATE borrowers SET wallet_balance = '$defaultBalance' WHERE account = '$acctno'");
                    
                    //UPDATE WAITING TXT
                    mysqli_query($link, "UPDATE api_txtwaitinglist SET status = '$result' WHERE userid = '$acctno' AND refid = '$tReference' AND status = '$mytxtstatus'");
    
                    mysqli_query($link, "DELETE FROM otp_confirmation WHERE userid = '$acctno' AND otp_code = '$myotp' AND status = 'Pending'");
                      
                    echo "<div class='alert bg-orange'>Sorry! Unable to conclude transaction, please try again later!!</div>";
                    echo '<meta http-equiv="refresh" content="5;url=transfer.php?tid='.$_SESSION['tid'].'&&acn='.$acctno.'&&mid=NDA4&&tab=tab_1">';
                    
                }
    
            }
            else{
    
                mysqli_query($link, "DELETE FROM otp_confirmation WHERE userid = '$acctno' AND otp_code = '$myotp' AND status = 'Pending'");
                     
                echo "<div class='alert bg-orange'>Opps!...Service not available at the moment!!</div>";
                echo '<meta http-equiv="refresh" content="5;url=transfer.php?tid='.$_SESSION['tid'].'&&acn='.$acctno.'&&mid=NDA4&&tab=tab_1">';
    
            }
	    
	}
    
}
?>



<?php
if(!(isset($_GET['otp']) || isset($_GET['pin'])))
{
?>

             <div class="box-body">
                 
                 <?php
                  if($inip_route == "Wallet Africa" || $inip_route == "PrimeAirtime" || $inip_route == "SuntrustBank")
                  {
                 ?>
                 
                 

                 <div class="form-group">
                    <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Country</label>
                    <div class="col-sm-6">
                        <select name="country"  class="form-control select2" id="country" onchange="loadbank();" required>
                          <option value="" selected>Please Select Country</option>
                          <option value="NG">Nigeria</option>
                          
                        </select>
                    </div>
                    <label for="" class="col-sm-3 control-label"></label>
                  </div>
                  
                  <div class="form-group">
                    <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Account Number</label>
                    <div class="col-sm-6">
                        <input name="account_number" type="text" id="account_number" onkeydown="loadaccount();" class="form-control" placeholder="Bank Account Number" required>
                    </div>
                    <label for="" class="col-sm-3 control-label"></label>
                  </div>
                  
                  <div class="form-group">
                    <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Bank Name</label>
                    <div class="col-sm-6">
                        <div id="bank_list">------------</div>
                    </div>
                    <label for="" class="col-sm-3 control-label"></label>
                  </div>

                  <?php
                  }
                  elseif($inip_route == "ProvidusBank" || $inip_route == "AccessBank" || $inip_route == "SterlingBank" || $inip_route == "RubiesBank"){
                  ?>

                  <div class="form-group">
                    <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Account Number</label>
                    <div class="col-sm-6">
                        <input name="account_number" type="text" id="accountNo" onkeydown="fetchbanklist();" class="form-control" placeholder="Bank Account Number" required>
                    </div>
                    <label for="" class="col-sm-3 control-label"></label>
                  </div>

                  <div class="form-group">
                    <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Recipient Bank</label>
                    <div class="col-sm-6">
                        <?php 
                        if($inip_route == "ProvidusBank"){
                        ?>
                          <select name="bank_code"  class="form-control select2" id="bankCode" onchange="fetchbanklist();" required>
                        <?php
                        }
                        elseif($inip_route == "SterlingBank"){
                        ?>
                          <select name="bank_code"  class="form-control select2" id="sterlingBankCode" onchange="fetchsterlingbanklist();" required>
                        <?php
                        }
                        elseif($inip_route == "RubiesBank"){
                        ?>
                          <select name="bank_code"  class="form-control select2" id="rubiesBankCode" onchange="fetchrubiesbanklist();" required>
                        <?php
                        }
                        ?>
                          <option value="" selected>Select Bank</option>
                          <?php
                            if($inip_route == "ProvidusBank"){ //ROUTE FOR PROVIDUS BANK

                                try
                                {
                                    $search_restapi1 = mysqli_query($link, "SELECT * FROM restful_apisetup WHERE api_name = 'providus_endpoint'");
                                    $fetch_restapi1 = mysqli_fetch_object($search_restapi1);
                                    $api_url1 = $fetch_restapi1->api_url;
                                    
                                    $client = new SoapClient($api_url1);

                                    $response = $client->GetNIPBanks();
                                
                                    $process = json_decode(json_encode($response), true);
            
                                    $processReturn = $process['return'];
                                    
                                    $process2 = json_decode($processReturn, true);
                                    
                                    $processReturn2 = $process2['banks'];
                                    
                                    $i = 0;
                                    
                                    foreach($processReturn2 as $key){
                                        
                                        echo "<option value=".$processReturn2[$i]['bankCode'].">".$processReturn2[$i]['bankName']." - ".$processReturn2[$i]['bankCode']."</option>";
                                        $i++;
                                        
                                    }
                                
                                }
                                catch( Exception $e )
                                {
                                    // You should not be here anymore
                                    echo $e->getMessage();
                                }
                            
                            }elseif($inip_route == "SterlingBank"){ //ROUTE FOR STERLING BANK
                              
                                require_once '../config/nipBankTransfer_class.php';
                                
                                $search_restapi1 = mysqli_query($link, "SELECT * FROM restful_apisetup WHERE api_name = 'sterling_GetBankListReq'");
                                $fetch_restapi1 = mysqli_fetch_object($search_restapi1);
                                $client = $fetch_restapi1->api_url;
                                
                                $data_to_send_server = array(
                                      "Referenceid"=>date("ymi").time(),
                                      "RequestType"=>152,
                                      "Translocation"=>"100,100"
                                  );
                                  
                                  $process = $new->sterlingNIPBankTransfer($data_to_send_server,$client);
                                  
                                  $processReturn = $process['data']['response'];
                                  
                                  $process2 = json_decode($processReturn, true);
                                  
                                  $i = 0;
                                      
                                  foreach($process2 as $key){
                                      
                                      echo "<option value=".$process2[$i]['BANKCODE'].">".$process2[$i]['BANKNAME']." - ".$process2[$i]['BANKCODE']."</option>";
                                      $i++;
                                          
                                  }
                                
                            }elseif($inip_route == "RubiesBank"){ //ROUTE FOR RUBIES BANK
                              
                                require_once '../config/nipBankTransfer_class.php';
                                    
                                $banklist = $new->rubiesListNIPBank($link,$rubbiesSecKey);
                                
                                $i = 0;
                                
                                foreach($banklist as $key){
                                      
                                      echo "<option value=".$banklist[$i]['bankcode'].">".$banklist[$i]['bankname']." - ".$banklist[$i]['bankcode']."</option>";
                                      $i++;
                                    
                                }
                                
                            }else{
                                //Do Nothing
                            }
                          ?>
                        </select>
                    </div>
                    <label for="" class="col-sm-3 control-label"></label>
                  </div>

                  <?php
                  }
                  else{

                    echo "<div align='center' style='font-size:20px;'>Kindly contact the Administrator to Activate this features if needed!!</div>";

                  }
                  ?>
                  
                  <div class="form-group">
                    <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Beneficiary Name</label>
                    <div class="col-sm-6">
                        <div id="act_numb">------------</div>
                    </div>
                    <label for="" class="col-sm-3 control-label"></label>
                  </div>
                  
                  <div class="form-group">
                    <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Amount</label>
                    <div class="col-sm-6">
                        <input name="amt_totransfer" type="text" class="form-control" placeholder="Enter Amount to Transfer" required>
                    </div>
                    <label for="" class="col-sm-3 control-label"></label>
                  </div>
                  
                  <div class="form-group">
                    <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Remark</label>
                    <div class="col-sm-6">
                        <textarea name="reasons" class="form-control" placeholder="State Reasons for Transfer e.g. For House rent, Pay Bonus etc." rows="2" cols="5" required></textarea>
                    </div>
                    <label for="" class="col-sm-3 control-label"></label>
                  </div>
			
			 </div>
			 
			 <div class="form-group" align="right">
                <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;"></label>
                <div class="col-sm-6">
                    <button name="africa_save" type="submit" onclick="var e=this;setTimeout(function(){e.disabled=true;},0);return true;" class="btn bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?> btn-flat"><i class="fa fa-forward">&nbsp;Initiate Transfer</i></button>
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
if(isset($_POST['inter_save']))
{
    include("../config/restful_apicalls.php");
    
    $reference =  "EA-intTransva-".myreference(10);
    $account_number =  mysqli_real_escape_string($link, $_POST['account_number']);
    $routing_number =  mysqli_real_escape_string($link, $_POST['routing_number']);
    $swift_code =  mysqli_real_escape_string($link, $_POST['swift_code']);
    $bank_name = mysqli_real_escape_string($link, $_POST['bank_name']);
	$b_name = mysqli_real_escape_string($link, $_POST['b_name']);
	$b_addrs = mysqli_real_escape_string($link, $_POST['b_addrs']);
	$b_country =  mysqli_real_escape_string($link, $_POST['b_country']);
	$currency = mysqli_real_escape_string($link, $_POST['currency']);
	$amountWithNoCharges = preg_replace('/[^0-9.]/', '', mysqli_real_escape_string($link, $_POST['amt_totransfer']));
	$intnarration =  mysqli_real_escape_string($link, $_POST['intreasons']);
	$currenctdate = date("Y-m-d H:i:s");
	
	$query = mysqli_query($link, "SELECT * FROM systemset") or die (mysqli_error($link));
    $r = mysqli_fetch_object($query);
    $intlpayment_charges = $row1->intlpayment_charges;
    $sysabb = ($bsender_id == "") ? $r->abb : $bsender_id;
    
    //Currency conversion
    $search_restapi = mysqli_query($link, "SELECT * FROM restful_apisetup WHERE api_name = 'Exchange_Rate'");
	$fetch_restapi = mysqli_fetch_object($search_restapi);
	$api_url = $fetch_restapi->api_url;
	
	// Pass the parameter here
	$postdata =  array(
	    "secret_key"	=>	$rave_secret_key,
	    "service"       =>  "rates_convert",
	    "service_method"    =>  "post",
	    "service_version"   =>  "v1",
	    "service_channel"   =>  "transactions",
	    "service_channel_group" =>  "merchants",
	    "service_payload"   =>  [
	        "FromCurrency"  =>  $currency,
	        "ToCurrency"    =>  $bbcurrency,
	        "Amount"        =>  $amountWithNoCharges
	        ]
	    );
	    
	$make_call = callAPI('POST', $api_url, json_encode($postdata));
	$result = json_decode($make_call, true);
    
    $convertedAmount = $result['data']['ToAmount'];
    
    $otp_code = ($selfServiceTransAuth == "On" || $otp_option == "Yes" || $otp_option == "Both") ? substr((uniqid(rand(),1)),3,6) : $myuepin;
    
    $otpChecker = ($selfServiceTransAuth == "On" || $otp_option == "Yes" || $otp_option == "Both") ? "otp" : "pin";
								
	$sms = "$sysabb>>>Dear $myln! Your One Time Password is $otp_code";
    
    $search_insti = mysqli_query($link, "SELECT * FROM institution_data WHERE institution_id = '$bbranchid'");
    $fetch_myinst = mysqli_fetch_array($search_insti);
    $iwallet_balance = $fetch_myinst['wallet_balance'];
                            	
    $sms_rate = $r->fax;
    $imywallet_balance = $iwallet_balance - $sms_rate;
    $sms_refid = "EA-smsCharges-".time();
	
	//New AMount + Charges
    $amountWithCharges = ($intlpayment_charges * $convertedAmount) + $convertedAmount;
    $senderBalance = $bwallet_balance - $amountWithCharges;
	
	//Data Parser (array size = 11)
    $mydata = $convertedAmount."|".$amountWithCharges."|".$intnarration."|".$currency."|".$reference."|".$b_name."|".$account_number."|".$routing_number."|".$swift_code."|".$bank_name."|".$b_addrs."|".$b_country."|".$bwallet_balance;
    
    if($amountWithNoCharges <= 0){

        echo "<div class='alert bg-orange'>Sorry! Invalid amount entered!!</div>";

    }
    elseif($bactive_status == "Suspended"){
        
        echo "<div class='alert bg-orange'>Oops! You are unable to make transfer at the moment!!</div>";
        
    }
	elseif($bwallet_balance < $amountWithCharges){
	    
	    echo "<div class='alert bg-orange'>Sorry! You need to have upto $currency.number_format($amountWithCharges,2,'.',',') in your wallet to proceed!!</div>";
	    
    }
    elseif($amountWithNoCharges > $btransferLimitPerTrans){

        echo "<div class='alert bg-orange'>Sorry! You can not transfer more than ".$bbcurrency.number_format($btransferLimitPerTrans,2,'.',',')." at once!!</div>";

    }
    elseif($bmyDailyTransferLimit == $btransferLimitPerDay || (($amountWithNoCharges + $bmyDailyTransferLimit) > $btransferLimitPerDay)){

        echo "<div class='alert bg-orange'>Oops! You have reached your daily limit of ".$bbcurrency.number_format($btransferLimitPerDay,2,'.',',')."</div>";

    }
    elseif($bvirtual_acctno === "$account_number" || $acctno === "$account_number"){
        
        mysqli_query($link, "UPDATE borrowers SET status = 'Suspended' WHERE account = '$acctno'");
        mysqli_query($link, "INSERT INTO wallet_case VALUES(null,'$bbranchid','$bsbranchid','System','$acctno','Suspended','Frudulent Act Detected','$currenctdate')");
        
        echo "<div class='alert bg-orange'>Oops! Request Failed!!</div>";
        
    }
	else{
        
        $update = mysqli_query($link,"INSERT INTO otp_confirmation VALUES(null,'$acctno','$otp_code','$mydata','Pending','$currenctdate')") or die(mysqli_error($link));
 
        if(!$update)
        {
            echo "<div class='alert bg-orange'>Unable to Transfer Fund.....Please try again later</div>";
        }
        else{
            $iuid = "";
            ($debitWAllet == "No" && ($selfServiceTransAuth == "On" || $otp_option == "Yes" || $otp_option == "Both") ? $sendSMS->instGeneralAlert($bozeki_password, $bozeki_url, $sysabb, $phone2, $sms, $bbranchid, $sms_refid, $sms_rate, $iuid, $imywallet_balance, $debitWallet) : ($debitWAllet == "Yes" && $sms_rate <= $iwallet_balance && ($selfServiceTransAuth == "On" || $otp_option == "Yes" || $otp_option == "Both") ? $sendSMS->instGeneralAlert($bozeki_password, $bozeki_url, $isenderid, $phone2, $sms, $bbranchid, $sms_refid, $sms_rate, $iuid, $imywallet_balance, $debitWallet) : ""));
            echo ($selfServiceTransAuth == "On" || $otp_option == "Yes" || $otp_option == "Both") ? "<div align='center'><img src='".$fetchsys_config['file_baseurl']."checkmark.gif'><p style='color: #38A1F3;'>Great! Otp has been sent to your mobile phone to complete this transaction!!</p></div>" : "<div align='center'><img src='".$fetchsys_config['file_baseurl']."checkmark.gif'><p style='color: #38A1F3;'>Transfer Request Received Successfully! Wait a moment to complete the transaction with your Pin</p></div>";
            echo '<meta http-equiv="refresh" content="2;url=transfer.php?tid='.$_SESSION['tid'].'&&acn='.$acctno.'&&mid=NDA4&&tab=tab_2&&'.$otpChecker.'">';
        }
        
	}
	
}
?>


<?php
if (isset($_POST['confirm']))
{
    
    include("../config/walletafrica_restfulapis_call.php");
    include("../config/intlrestful_apicalls.php");
    
    $result = array();
    $result1 = array();
    $result2 = array();
    $myotp = $_POST['otp'];
    $currenctdate = date("Y-m-d H:i:s");
    
    $verify_otp = mysqli_query($link, "SELECT * FROM otp_confirmation WHERE otp_code = '$myotp' AND userid = '$acctno' AND status = 'Pending'");
	$fetch_data = mysqli_fetch_array($verify_otp);
	$otpnum = mysqli_num_rows($verify_otp);
						    
	$verify_otp1 = mysqli_query($link, "SELECT * FROM otp_confirmation WHERE otp_code = '$myotp' AND userid = '$acctno' AND status = 'Pending'");
	$otpnum1 = mysqli_num_rows($verify_otp1);
	
	if($otpnum1 > 1){
		
		mysqli_query($link, "UPDATE borrowers SET status = 'Suspended' WHERE account = '$acctno'");
        mysqli_query($link, "INSERT INTO wallet_case VALUES(null,'$bbranchid','$bsbranchid','System','$acctno','Suspended','Frudulent Act Detected','$currenctdate')");
        mysqli_query($link, "DELETE FROM otp_confirmation WHERE userid = '$acctno' AND otp_code = '$myotp' AND status = 'Pending'");
        
	    echo "<div class='alert bg-orange'>Duplicate Transaction is not allowed!!</div>";
	    echo '<meta http-equiv="refresh" content="2;url=transfer.php?tid='.$_SESSION['tid'].'&&acn='.$acctno.'&&mid=NDA4&&tab=tab_2">';
						        
	}				    
	elseif($otpnum == 0 && $otpnum1 == 0){
						        
	    echo "<div class='alert bg-orange'>Opps!...Invalid Credential!!</div>";
						        
	}else{

	    $concat = $fetch_data['data'];
    
        $datetime = $fetch_data['datetime'];
                                
        $parameter = (explode('|',$concat));
        
        $amountWithNoCharges = $parameter[0];
	    $amountWithCharges = $parameter[1];
        $intnarration = $parameter[2];
        $currency = $parameter[3];
        $tReference = $parameter[4];
        $accountName = $parameter[5];
        $recipientAcctNo = $parameter[6];
        $routing_number = $parameter[7];
        $swift_code = $parameter[8];
        $mybank_name = $parameter[9];
        $b_addrs = $parameter[10];
        $b_country = $parameter[11];
        $balanceLeft = $parameter[12];
        $defaultCustomerBal = $bwallet_balance - $amountWithCharges;
	    $calcCharges = $amountWithCharges - $amountWithNoCharges;
            
        //Get my wallet balance after debiting
        $senderBalance = $defaultCustomerBal;
        $senderEmail = $email2;
        $senderName = $bname;
        $merchantName = $binst_name;
        $liveBalanceLeft = ($balanceLeft > $bwallet_balance) ? $bwallet_balance : $balanceLeft;
        
        if($bwallet_balance < $amountWithCharges){
            
            mysqli_query($link, "DELETE FROM otp_confirmation WHERE userid = '$acctno' AND otp_code = '$myotp' AND status = 'Pending'");
            
            echo "<div class='alert alert-danger'>Oops! You have insufficient fund in your Wallet!!</div>";
            echo '<meta http-equiv="refresh" content="2;url=transfer.php?tid='.$_SESSION['tid'].'&&acn='.$acctno.'&&mid=NDA4&&tab=tab_2">';
            
        }else{
            
            //Debit Customer Wallet
            mysqli_query($link, "UPDATE borrowers SET wallet_balance = '$senderBalance' WHERE account = '$acctno'");
        
            $transactionDateTime = date("Y-m-d h:i:s");
            //13 rows [0 - 13]
            $dataToProcess = $amountWithNoCharges."|".$rave_secret_key."|".$intnarration."|".$currency."|".$tReference."|".$accountName."|".$recipientAcctNo."|".$routing_number."|".$swift_code."|".$mybank_name."|".$b_addrs."|".$b_country."|".$bbcurrency."|".$senderBalance;
            //insert txt waiting list
            $mytxtstatus = 'Pending';
            mysqli_query($link, "INSERT INTO api_txtwaitinglist VALUE(null,'$acctno','$tReference','$dataToProcess','$mytxtstatus','$transactionDateTime')");
    
            //fetch endpoint
            $search_restapi1 = mysqli_query($link, "SELECT * FROM restful_apisetup WHERE api_name = 'transfer'");
            $fetch_restapi1 = mysqli_fetch_object($search_restapi1);
            $api_url1 = $fetch_restapi1->api_url;
            
            // Pass the parameter here
            $postdata1 =  array(
                    "amount"            =>  $amountWithNoCharges,
                    "seckey"            =>  $rave_secret_key,
                    "narration"         =>  $intnarration,
                    "currency"          =>  $currency,
                    "reference"         =>  $tReference,
                    "beneficiary_name"  =>  $accountName,
                    "meta"          =>  [
                        "AccountNumber"     =>  $recipientAcctNo,
                        "RoutingNumber"     =>  $routing_number,
                        "SwiftCode"         =>  $swift_code,
                        "BankName"          =>  $mybank_name,
                        "BeneficiaryName"   =>  $accountName,
                        "BeneficiaryAddress"=>  $b_addrs,
                        "BeneficiaryCountry"=>  $b_country  
                    ],
                    "debit_currency"    =>  $bbcurrency
            );
                  
            $make_call1 = intlCallAPI('POST', $api_url1, json_encode($postdata1));
            $result1 = json_decode($make_call1, true);
                
            if($result1['status'] == "success"){
                    
                $transfer_id = $result1['data']['id'];
                $transfers_fee = "Gateway Fee: ".$bbcurrency.$result1['data']['fee']." | Inhouse Fee: ".$bbcurrency.$calcCharges;
                $status = $result1['data']['status'];
                $recipient = $recipientAcctNo.', '.$accountName.', '.$routing_number.', '.$mybank_name;
                
                //UPDATE WAITING TXT
                mysqli_query($link, "UPDATE api_txtwaitinglist SET status = '$make_call1' WHERE userid = '$acctno' AND refid = '$tReference' AND status = '$mytxtstatus'");
    
                //Log successful transaction history
                mysqli_query($link, "INSERT INTO wallet_history VALUE(null,'$bbranchid','$tReference','$recipient','','$amountWithNoCharges','Debit','$currency','BANK_TRANSFER','Transfer to $recipient','successful','$transactionDateTime','$acctno','$senderBalance','Flutterwave')");
                mysqli_query($link, "INSERT INTO wallet_history VALUE(null,'$bbranchid','$tReference','$recipient','','$calcCharges','Debit','$currency','Charges','Transfer to $recipient, Remark: $mnarration','successful','$transactionDateTime','$acctno','$senderBalance','Flutterwave')");
    
                $sendSMS->bankTransferEmailNotifier($senderEmail, $tReference, $senderName, $transactionDateTime, $accountName, $recipientAcctNo, $mybank_name, $merchantName, $bbcurrency, $amountWithNoCharges, $senderBalance, $emailConfigStatus, $fetch_emailConfig);
    
                mysqli_query($link, "DELETE FROM otp_confirmation WHERE userid = '$acctno' AND otp_code = '$myotp' AND status = 'Pending'");
                    
                echo "<div class='alert bg-blue'>Transfer Initiated Successful! Current Wallet Balance is: <b style='color: white;'>".$bbcurrency.number_format($senderBalance,2,'.',',')."</b></div>";
            	echo '<meta http-equiv="refresh" content="3;url=transfer.php?tid='.$_SESSION['tid'].'&&acn='.$acctno.'&&mid=NDA4&&tab=tab_2">';
                    
            }
            else{
    
                $defaultBalance = $liveBalanceLeft;
    
                //Reverse to Customer Wallet
                mysqli_query($link, "UPDATE borrowers SET wallet_balance = '$defaultBalance' WHERE account = '$acctno'");
                
                //UPDATE WAITING TXT
                mysqli_query($link, "UPDATE api_txtwaitinglist SET status = '$make_call1' WHERE userid = '$acctno' AND refid = '$tReference' AND status = '$mytxtstatus'");
    
                mysqli_query($link, "DELETE FROM otp_confirmation WHERE userid = '$acctno' AND otp_code = '$myotp' AND status = 'Pending'");
    
                echo "<div class='alert bg-orange'>Sorry! Unable to conclude transaction, please try again later!!</div>";
                echo '<meta http-equiv="refresh" content="3;url=transfer.php?tid='.$_SESSION['tid'].'&&acn='.$acctno.'&&mid=NDA4&&tab=tab_2">';
                
            }
            
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
                    <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Account Number</label>
                    <div class="col-sm-6">
                        <input name="account_number" type="text" class="form-control" placeholder="Bank Account Number" required>
                    </div>
                    <label for="" class="col-sm-3 control-label"></label>
                  </div>
                  
                  <div class="form-group">
                    <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Routing Number</label>
                    <div class="col-sm-6">
                        <input name="routing_number" type="text" class="form-control" placeholder="Bank Routing Number" required>
                    </div>
                    <label for="" class="col-sm-3 control-label"></label>
                  </div>
                  
                  <div class="form-group">
                    <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Swiftcode</label>
                    <div class="col-sm-6">
                        <input name="swift_code" type="text" class="form-control" placeholder="Bank SwiftCode" required>
                    </div>
                    <label for="" class="col-sm-3 control-label"></label>
                  </div>
                  
                  <div class="form-group">
                    <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Bank Name</label>
                    <div class="col-sm-6">
                        <input name="bank_name" type="text" class="form-control" placeholder="e.g. BANK OF AMERICA, N.A., SAN FRANCISCO, CA" required>
                    </div>
                    <label for="" class="col-sm-3 control-label"></label>
                  </div>
                  
                  <div class="form-group">
                    <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Beneficiary Name</label>
                    <div class="col-sm-6">
                        <input name="b_name" type="text" class="form-control" placeholder="Enter Beneficiary Name e.g. Mark Cuban" required>
                    </div>
                    <label for="" class="col-sm-3 control-label"></label>
                  </div>
                  
                  <div class="form-group">
                    <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Beneficiary Address</label>
                    <div class="col-sm-6">
                        <input name="b_addrs" type="text" id="act_numb" class="form-control" placeholder="Enter Beneficiary Address e.g. San Francisco, 4 Newton" required>
                    </div>
                    <label for="" class="col-sm-3 control-label"></label>
                  </div>
                  
                  <div class="form-group">
                    <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Beneficiary Country</label>
                    <div class="col-sm-6">
                        <select name="b_country"  class="form-control select2" id="country" required>
                          <option selected>Select Country</option>
                          <option value="NG">Nigeria</option>
                          <option value="GH">Ghana</option>
                          <option value="KE">Kenya</option>
                          <option value="UG">Uganda </option>
                          <option value="TZ">Tanzania</option>
                          <option value="US">United States</option>
                          <option value="OT">Other countries</option>
                        </select>
                    </div>
                    <label for="" class="col-sm-3 control-label"></label>
                  </div>
                  
                  <div class="form-group">
                    <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Currency</label>
                    <div class="col-sm-6">
                        <select name="currency"  class="form-control select2" required>
                          <option value="" selected>Please Select Currency</option>
                          <option value="NGN">NGN</option>
                          <option value="USD">USD</option>
                          <option value="GBP">GBP</option>
                          <option value="EUR">EUR</option>
                          <option value="GHS">GHS</option>
                          <option value="KES">KES</option>
                          <option value="UGX">UGX</option>
                          <option value="TZS">TZS</option>
                        </select>
                    </div>
                    <label for="" class="col-sm-3 control-label"></label>
                  </div>
                  
                  <div class="form-group">
                    <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Amount</label>
                    <div class="col-sm-6">
                        <input name="amt_totransfer" type="text" class="form-control" placeholder="Enter Amount to Transfer" required>
                    </div>
                    <label for="" class="col-sm-3 control-label"></label>
                  </div>
                  
                  <div class="form-group">
                    <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Remark</label>
                    <div class="col-sm-6">
                        <textarea name="intreasons" class="form-control" placeholder="State Reasons for Transfer e.g. For House rent, Pay Bonus etc." rows="2" cols="5" required></textarea>
                    </div>
                    <label for="" class="col-sm-3 control-label"></label>
                  </div>
      
            </div>
            
            <div class="form-group" align="right">
                <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;"></label>
                <div class="col-sm-6">
                    <button name="inter_save" type="submit" onclick="var e=this;setTimeout(function(){e.disabled=true;},0);return true;" class="btn bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?> btn-flat"><i class="fa fa-forward">&nbsp;Initiate Transfer</i></button>
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
  elseif($tab == 'tab_3')
  {
  ?> 

      <div class="tab-pane <?php echo ($_GET['tab'] == 'tab_3') ? 'active' : ''; ?>" id="tab_3">

        <div class="box-body">

          <form class="form-horizontal" method="post" enctype="multipart/form-data">

<?php
if(isset($_POST['mpesa_save']))
{
    include("../config/restful_apicalls.php");
    
    $reference =  "EA-mpsTransva-".myreference(10);
    $account_number =  mysqli_real_escape_string($link, $_POST['account_number']);
    $routing_number =  mysqli_real_escape_string($link, $_POST['routing_number']);
    $currency = mysqli_real_escape_string($link, $_POST['currency']);
	$amountWithNoCharges = preg_replace('/[^0-9.]/', '', mysqli_real_escape_string($link, $_POST['amt_totransfer']));
	$bname = mysqli_real_escape_string($link, $_POST['b_name']);
	$mnarration =  mysqli_real_escape_string($link, $_POST['reasons']);
	$currenctdate = date("Y-m-d H:i:s");

	$query = mysqli_query($link, "SELECT * FROM systemset") or die (mysqli_error($link));
    $r = mysqli_fetch_object($query);
    $intlpayment_charges = $row1->intlpayment_charges;
    $sysabb = ($bsender_id == "") ? $r->abb : $bsender_id;
    
    //Currency conversion
    $search_restapi = mysqli_query($link, "SELECT * FROM restful_apisetup WHERE api_name = 'Exchange_Rate'");
	$fetch_restapi = mysqli_fetch_object($search_restapi);
	$api_url = $fetch_restapi->api_url;
	
	// Pass the parameter here
	$postdata =  array(
	    "secret_key"	=>	$rave_secret_key,
	    "service"       =>  "rates_convert",
	    "service_method"    =>  "post",
	    "service_version"   =>  "v1",
	    "service_channel"   =>  "transactions",
	    "service_channel_group" =>  "merchants",
	    "service_payload"   =>  [
	        "FromCurrency"  =>  $currency,
	        "ToCurrency"    =>  $bbcurrency,
	        "Amount"        =>  $amountWithNoCharges
	        ]
	    );
	    
	$make_call = callAPI('POST', $api_url, json_encode($postdata));
	$result = json_decode($make_call, true);
    
    $convertedAmount = $result['data']['ToAmount'];
    
    $otp_code = ($selfServiceTransAuth == "On" || $otp_option == "Yes" || $otp_option == "Both") ? substr((uniqid(rand(),1)),3,6) : $myuepin;
    
    $otpChecker = ($selfServiceTransAuth == "On" || $otp_option == "Yes" || $otp_option == "Both") ? "otp" : "pin";
								
	$sms = "$sysabb>>>Dear $myln! Your One Time Password is $otp_code";
    
    $search_insti = mysqli_query($link, "SELECT * FROM institution_data WHERE institution_id = '$bbranchid'");
    $fetch_myinst = mysqli_fetch_array($search_insti);
    $iwallet_balance = $fetch_myinst['wallet_balance'];
                            	
    $sms_rate = $r->fax;
    $imywallet_balance = $iwallet_balance - $sms_rate;
    $sms_refid = "EA-smsCharges-".time();
	
	//New Amount + Charges
    $amountWithCharges = ($intlpayment_charges * $convertedAmount) + $convertedAmount;
    $senderBalance = $bwallet_balance - $amountWithCharges;
	
	//Data Parser (array size = 6)
    $mydata = $account_number."|".$convertedAmount."|".$amountWithCharges."|".$mnarration."|".$currency."|".$reference."|".$bname."|".$bwallet_balance;
    
    if($amountWithNoCharges <= 0){

        echo "<div class='alert bg-orange'>Sorry! Invalid amount entered!!</div>";

    }
    elseif($bactive_status == "Suspended"){
        
        echo "<div class='alert bg-orange'>Oops! You are unable to make transfer at the moment!!</div>";
        
    }
	elseif($bwallet_balance < $amountWithCharges){
	    
	    echo "<div class='alert bg-orange'>Sorry! You need to have upto $bbcurrency.number_format($amountWithCharges,2,'.',',') in your wallet to proceed!!</div>";
	    
    }
    elseif($amountWithNoCharges > $btransferLimitPerTrans){

        echo "<div class='alert bg-orange'>Sorry! You can not transfer more than ".$bbcurrency.number_format($btransferLimitPerTrans,2,'.',',')." at once!!</div>";

    }
    elseif($bmyDailyTransferLimit == $btransferLimitPerDay || (($amountWithNoCharges + $bmyDailyTransferLimit) > $btransferLimitPerDay)){

        echo "<div class='alert bg-orange'>Oops! You have reached your daily limit of ".$bbcurrency.number_format($btransferLimitPerDay,2,'.',',')."</div>";

    }
    elseif($bvirtual_acctno === "$account_number" || $acctno === "$account_number"){
        
        mysqli_query($link, "UPDATE borrowers SET status = 'Suspended' WHERE account = '$acctno'");
        mysqli_query($link, "INSERT INTO wallet_case VALUES(null,'$bbranchid','$bsbranchid','System','$acctno','Suspended','Frudulent Act Detected','$currenctdate')");
        
        echo "<div class='alert bg-orange'>Oops! Request Failed!!</div>";
        
    }
	else{
        
        $update = mysqli_query($link,"INSERT INTO otp_confirmation VALUES(null,'$acctno','$otp_code','$mydata','Pending','$currenctdate')") or die(mysqli_error($link));
    
        if(!($update && $insert))
        {
            echo "<div class='alert bg-orange'>Unable to Transfer Fund.....Please try again later</div>";
        }
        else{
            $iuid = "";
            ($debitWAllet == "No" && ($selfServiceTransAuth == "On" || $otp_option == "Yes" || $otp_option == "Both") ? $sendSMS->instGeneralAlert($bozeki_password, $bozeki_url, $sysabb, $phone2, $sms, $bbranchid, $sms_refid, $sms_rate, $iuid, $imywallet_balance, $debitWallet) : ($debitWAllet == "Yes" && $sms_rate <= $iwallet_balance && ($selfServiceTransAuth == "On" || $otp_option == "Yes" || $otp_option == "Both") ? $sendSMS->instGeneralAlert($bozeki_password, $bozeki_url, $isenderid, $phone2, $sms, $bbranchid, $sms_refid, $sms_rate, $iuid, $imywallet_balance, $debitWallet) : ""));
            echo ($selfServiceTransAuth == "On" || $otp_option == "Yes" || $otp_option == "Both") ? "<div align='center'><img src='".$fetchsys_config['file_baseurl']."checkmark.gif'><p style='color: #38A1F3;'>Great! Otp has been sent to your mobile phone to complete this transaction!!</p></div>" : "<div align='center'><img src='".$fetchsys_config['file_baseurl']."checkmark.gif'><p style='color: #38A1F3;'>Transfer Request Received Successfully! Wait a moment to complete the transaction with your Pin</p></div>";
            echo '<meta http-equiv="refresh" content="2;url=transfer.php?tid='.$_SESSION['tid'].'&&acn='.$acctno.'&&mid=NDA4&&tab=tab_3&&'.$otpChecker.'">';
        }
    	
	}
}
?>




<?php
if (isset($_POST['confirm']))
{
    
    include("../config/intlrestful_apicalls.php");
    
    $result = array();
    $result1 = array();
    $result2 = array();
    $myotp = $_POST['otp'];
    $currenctdate = date("Y-m-d H:i:s");
    
    $verify_otp = mysqli_query($link, "SELECT * FROM otp_confirmation WHERE otp_code = '$myotp' AND userid = '$acctno' AND status = 'Pending'");
	$fetch_data = mysqli_fetch_array($verify_otp);
	$otpnum = mysqli_num_rows($verify_otp);
						    
	$verify_otp1 = mysqli_query($link, "SELECT * FROM otp_confirmation WHERE otp_code = '$myotp' AND userid = '$acctno' AND status = 'Pending'");
	$otpnum1 = mysqli_num_rows($verify_otp1);
	
	if($otpnum1 > 1){
		
		mysqli_query($link, "UPDATE borrowers SET status = 'Suspended' WHERE account = '$acctno'");
        mysqli_query($link, "INSERT INTO wallet_case VALUES(null,'$bbranchid','$bsbranchid','System','$acctno','Suspended','Frudulent Act Detected','$currenctdate')");
        mysqli_query($link, "DELETE FROM otp_confirmation WHERE userid = '$acctno' AND otp_code = '$myotp' AND status = 'Pending'");
        
	    echo "<div class='alert bg-orange'>Duplicate Transaction is not allowed!!</div>";
	    echo '<meta http-equiv="refresh" content="2;url=transfer.php?tid='.$_SESSION['tid'].'&&acn='.$acctno.'&&mid=NDA4&&tab=tab_3">';
						        
	}				    
	elseif($otpnum == 0 && $otpnum1 == 0){
						        
	    echo "<div class='alert bg-orange'>Opps!...Invalid Credential!!</div>";
						        
	}else{
	    
	    $concat = $fetch_data['data'];
    
        $datetime = $fetch_data['datetime'];
                                
        $parameter = (explode('|',$concat));
    
        $recipientAcctNo = $parameter[0];
        $amountWithNoCharges = $parameter[1];
        $amountWithCharges = $parameter[2];
        $mnarration = $parameter[3];
        $currency = $parameter[4];
        $tReference = $parameter[5];
        $accountName = $parameter[6];
        $balanceLeft = $parameter[7];
        $defaultCustomerBal = $bwallet_balance - $amountWithCharges;
        $calcCharges = $amountWithCharges - $amountWithNoCharges;
	                
        //Get my wallet balance after debiting
        $senderBalance = $defaultCustomerBal;
        $senderEmail = $email2;
        $senderName = $bname;
        $merchantName = $binst_name;
        $liveBalanceLeft = ($balanceLeft > $bwallet_balance) ? $bwallet_balance : $balanceLeft;
        
        if($bwallet_balance < $amountWithCharges){
            
            mysqli_query($link, "DELETE FROM otp_confirmation WHERE userid = '$acctno' AND otp_code = '$myotp' AND status = 'Pending'");
            
            echo "<div class='alert alert-danger'>Oops! You have insufficient fund in your Wallet!!</div>";
            echo '<meta http-equiv="refresh" content="2;url=transfer.php?tid='.$_SESSION['tid'].'&&acn='.$acctno.'&&mid=NDA4&&tab=tab_3">';
            
        }else{
            
            //Debit Customer Wallet
            mysqli_query($link, "UPDATE borrowers SET wallet_balance = '$senderBalance' WHERE account = '$acctno'");
        
            $transactionDateTime = date("Y-m-d h:i:s");
            //9 rows [0 - 9]
            $dataToProcess = "MPS|".$recipientAcctNo."|".$amountWithNoCharges."|".$rave_secret_key."|".$mnarration."|".$currency."|".$tReference."|".$accountName."|".$bbcurrency."|".$senderBalance;
            //insert txt waiting list
            $mytxtstatus = 'Pending';
            mysqli_query($link, "INSERT INTO api_txtwaitinglist VALUE(null,'$acctno','$tReference','$dataToProcess','$mytxtstatus','$transactionDateTime')");
            
            //fetch endpoint
            $search_restapi1 = mysqli_query($link, "SELECT * FROM restful_apisetup WHERE api_name = 'transfer'");
            $fetch_restapi1 = mysqli_fetch_object($search_restapi1);
            $api_url1 = $fetch_restapi1->api_url;
                
            // Pass the parameter here
            $postdata1 =  array(
                    "account_bank"      =>  "MPS",
                    "account_number"    =>  $recipientAcctNo,
                    "amount"            =>  $amountWithNoCharges,
                    "seckey"            =>  $rave_secret_key,
                    "narration"         =>  $mnarration,
                    "currency"          =>  $currency,
                    "reference"         =>  $tReference,
                    "beneficiary_name"  =>  $accountName,
                    "debit_currency"    =>  $bbcurrency
            );
                  
            $make_call1 = intlCallAPI('POST', $api_url1, json_encode($postdata1));
            $result1 = json_decode($make_call1, true);
                
            if($result1['status'] == "success"){
                    
                $transfer_id = $result['data']['id'];
                $transfers_fee = "Gateway Fee: ".$bbcurrency.$result['data']['fee']." | Inhouse Fee: ".$bbcurrency.$calcCharges;
                $mybank_name = $result['data']['bank_name'];
                $status = $result['data']['status'];
                $recipient = $recipientAcctNo.', '.$accountName.', MPS, '.$mybank_name;
                
                //UPDATE WAITING TXT
                mysqli_query($link, "UPDATE api_txtwaitinglist SET status = '$make_call1' WHERE userid = '$acctno' AND refid = '$tReference' AND status = '$mytxtstatus'");
    
                //Log successful transaction history
                mysqli_query($link, "INSERT INTO wallet_history VALUE(null,'$bbranchid','$tReference','$recipient','','$amountWithNoCharges','Debit','$currency','BANK_TRANSFER','Transfer to $recipient','successful','$transactionDateTime','$acctno','$senderBalance','Flutterwave')");
                mysqli_query($link, "INSERT INTO wallet_history VALUE(null,'$bbranchid','$tReference','$recipient','','$calcCharges','Debit','$currency','Charges','Transfer to $recipient, Remark: $mnarration','successful','$transactionDateTime','$acctno','$senderBalance','Flutterwave')");
    
                $sendSMS->bankTransferEmailNotifier($senderEmail, $tReference, $senderName, $transactionDateTime, $accountName, $recipientAcctNo, $mybank_name, $merchantName, $bbcurrency, $amountWithNoCharges, $senderBalance, $emailConfigStatus, $fetch_emailConfig);
    
                mysqli_query($link, "DELETE FROM otp_confirmation WHERE userid = '$acctno' AND otp_code = '$myotp' AND status = 'Pending'");
                    
                echo "<div class='alert bg-blue'>Transfer Initiated Successful! Current Wallet Balance is: <b style='color: white;'>".$bbcurrency.number_format($senderBalance,2,'.',',')."</b></div>";
            	echo '<meta http-equiv="refresh" content="3;url=transfer.php?tid='.$_SESSION['tid'].'&&acn='.$acctno.'&&mid=NDA4&&tab=tab_3">';
                    
            }
            else{
    
                $defaultBalance = $liveBalanceLeft;
    
                //Reverse to Customer Wallet
                mysqli_query($link, "UPDATE borrowers SET wallet_balance = '$defaultBalance' WHERE account = '$acctno'");
                
                //UPDATE WAITING TXT
                mysqli_query($link, "UPDATE api_txtwaitinglist SET status = '$make_call1' WHERE userid = '$acctno' AND refid = '$tReference' AND status = '$mytxtstatus'");
    
                mysqli_query($link, "DELETE FROM otp_confirmation WHERE userid = '$acctno' AND otp_code = '$myotp' AND status = 'Pending'");
    
                echo "<div class='alert bg-orange'>Sorry! Unable to conclude transaction, please try again later!!</div>";
                echo '<meta http-equiv="refresh" content="3;url=transfer.php?tid='.$_SESSION['tid'].'&&acn='.$acctno.'&&mid=NDA4&&tab=tab_3">';
                
            }
            
        }
	    
	}
        
}
?>



<?php
if(!(isset($_GET['otp']) || isset($_GET['pin'])))
{
?>
            <div class="box-body">
                
                <input name="account_bank" type="hidden" class="form-control" value="MPS">
                
                <div class="form-group">
                    <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Account Number</label>
                    <div class="col-sm-6">
                        <input name="account_number" type="text" class="form-control" placeholder="Recipient Mpesa Number" required>
                        <span style="color: <?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>"><b>NOTE:</b> It should always come with the prefix <b>233</b></span>
                    </div>
                    <label for="" class="col-sm-3 control-label"></label>
                </div>
                
                <div class="form-group">
                    <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Currency</label>
                    <div class="col-sm-6">
                        <select name="currency"  class="form-control select2" required>
                          <option value="" selected>Select Currency</option>
                          <option value="GHS">GHS</option>
                          <option value="KES">KES</option>
                          <option value="UGX">UGX</option>
                          <option value="TZS">TZS</option>
                        </select>
                    </div>
                    <label for="" class="col-sm-3 control-label"></label>
                </div>
                
                <div class="form-group">
                    <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Amount</label>
                    <div class="col-sm-6">
                        <input name="amt_totransfer" type="text" class="form-control" placeholder="Enter Amount to Transfer" required>
                    </div>
                    <label for="" class="col-sm-3 control-label"></label>
                </div>
                
                <div class="form-group">
                    <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Beneficiary Name</label>
                    <div class="col-sm-6">
                        <input name="b_name" type="text" id="act_numb" class="form-control" placeholder="Enter Beneficiary Name e.g. Kwame Adew" required>
                    </div>
                    <label for="" class="col-sm-3 control-label"></label>
                </div>
                
                <div class="form-group">
                    <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Remark</label>
                    <div class="col-sm-6">
                        <textarea name="reasons" class="form-control" placeholder="State Reasons for Transfer e.g. For House rent, Pay Bonus etc." rows="2" cols="5" required></textarea>
                    </div>
                    <label for="" class="col-sm-3 control-label"></label>
                </div>

            </div>
            
            <div class="form-group" align="right">
                <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;"></label>
                <div class="col-sm-6">
                    <button name="mpesa_save" type="submit" onclick="var e=this;setTimeout(function(){e.disabled=true;},0);return true;" class="btn bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?> btn-flat"><i class="fa fa-forward">&nbsp;Initiate Transfer</i></button>
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
  elseif($tab == 'tab_4')
  {
  ?> 

      <div class="tab-pane <?php echo ($_GET['tab'] == 'tab_4') ? 'active' : ''; ?>" id="tab_4">

        <div class="box-body">

          <form class="form-horizontal" method="post" enctype="multipart/form-data">

<?php
if(isset($_POST['ghana_save']))
{
    include("../config/restful_apicalls.php");
    
    $reference =  "EA-ghsTransva-".myreference(10);
    $country =  mysqli_real_escape_string($link, $_POST['country']);
    $bank_id = mysqli_real_escape_string($link, $_POST['bank_id']);
    $branch_code = mysqli_real_escape_string($link, $_POST['branch_code']);
    $account_bank =  mysqli_real_escape_string($link, $_POST['account_bank']);
    $account_number =  mysqli_real_escape_string($link, $_POST['account_number']);
    $currency = "GHS";
	$amountWithNoCharges = preg_replace('/[^0-9.]/', '', mysqli_real_escape_string($link, $_POST['amt_totransfer']));
	$bname = mysqli_real_escape_string($link, $_POST['b_name']);
	$mnarration =  mysqli_real_escape_string($link, $_POST['reasons']);
	$currenctdate = date("Y-m-d H:i:s");

	$query = mysqli_query($link, "SELECT * FROM systemset") or die (mysqli_error($link));
    $r = mysqli_fetch_object($query);
    $intlpayment_charges = $row1->intlpayment_charges;
    $sysabb = ($bsender_id == "") ? $r->abb : $bsender_id;
    
    //Currency conversion
    $search_restapi = mysqli_query($link, "SELECT * FROM restful_apisetup WHERE api_name = 'Exchange_Rate'");
	$fetch_restapi = mysqli_fetch_object($search_restapi);
	$api_url = $fetch_restapi->api_url;
	
	// Pass the parameter here
	$postdata =  array(
	    "secret_key"	=>	$rave_secret_key,
	    "service"       =>  "rates_convert",
	    "service_method"    =>  "post",
	    "service_version"   =>  "v1",
	    "service_channel"   =>  "transactions",
	    "service_channel_group" =>  "merchants",
	    "service_payload"   =>  [
	        "FromCurrency"  =>  $currency,
	        "ToCurrency"    =>  $bbcurrency,
	        "Amount"        =>  $amountWithNoCharges
	        ]
	    );
	    
	$make_call = callAPI('POST', $api_url, json_encode($postdata));
	$result = json_decode($make_call, true);
    
    $convertedAmount = $result['data']['ToAmount'];
    
    $otp_code = ($selfServiceTransAuth == "On" || $otp_option == "Yes" || $otp_option == "Both") ? substr((uniqid(rand(),1)),3,6) : $myuepin;
    
    $otpChecker = ($selfServiceTransAuth == "On" || $otp_option == "Yes" || $otp_option == "Both") ? "otp" : "pin";
								
	$sms = "$sysabb>>>Dear $myln! Your One Time Password is $otp_code";
    
    $search_insti = mysqli_query($link, "SELECT * FROM institution_data WHERE institution_id = '$bbranchid'");
    $fetch_myinst = mysqli_fetch_array($search_insti);
    $iwallet_balance = $fetch_myinst['wallet_balance'];
                            	
    $sms_rate = $r->fax;
    $imywallet_balance = $iwallet_balance - $sms_rate;
    $sms_refid = "EA-smsCharges-".time();
	
	//New Amount + Charges
    $amountWithCharges = ($intlpayment_charges * $convertedAmount) + $convertedAmount;
	$senderBalance = $bwallet_balance - $amountWithCharges;

	//Data Parser (array size = 8)
    $mydata = $account_bank."|".$account_number."|".$convertedAmount."|".$amountWithCharges."|".$mnarration."|".$currency."|".$reference."|".$bname."|".$branch_code."|".$senderBalance."|".$bwallet_balance;
    
    if($amountWithNoCharges <= 0){

        echo "<div class='alert bg-orange'>Sorry! Invalid amount entered!!</div>";

    }
    elseif($bactive_status == "Suspended"){
        
        echo "<div class='alert bg-orange'>Oops! You are unable to make transfer at the moment!!</div>";
        
    }
	elseif($bwallet_balance < $amountWithCharges){
	    
	    echo "<div class='alert bg-orange'>Sorry! You need to have upto $currency.number_format($amountWithCharges,2,'.',',') in your wallet to proceed!!</div>";
	    
    }
    elseif($amountWithNoCharges > $btransferLimitPerTrans){

        echo "<div class='alert bg-orange'>Sorry! You can not transfer more than ".$bbcurrency.number_format($btransferLimitPerTrans,2,'.',',')." at once!!</div>";

    }
    elseif($bmyDailyTransferLimit == $btransferLimitPerDay || (($amountWithNoCharges + $bmyDailyTransferLimit) > $btransferLimitPerDay)){

        echo "<div class='alert bg-orange'>Oops! You have reached your daily limit of ".$bbcurrency.number_format($btransferLimitPerDay,2,'.',',')."</div>";

    }
    elseif($bvirtual_acctno === "$account_number" || $acctno === "$account_number"){
        
        mysqli_query($link, "UPDATE borrowers SET status = 'Suspended' WHERE account = '$acctno'");
        mysqli_query($link, "INSERT INTO wallet_case VALUES(null,'$bbranchid','$bsbranchid','System','$acctno','Suspended','Frudulent Act Detected','$currenctdate')");
        
        echo "<div class='alert bg-orange'>Oops! Request Failed!!</div>";
        
    }
	else{
        
        $update = mysqli_query($link,"INSERT INTO otp_confirmation VALUES(null,'$acctno','$otp_code','$mydata','Pending','$currenctdate')") or die(mysqli_error($link));
    
        if(!$update)
        {
            echo "<div class='alert bg-orange'>Unable to Transfer Fund.....Please try again later</div>";
        }
        else{
            $iuid = "";
            ($debitWAllet == "No" && ($selfServiceTransAuth == "On" || $otp_option == "Yes" || $otp_option == "Both") ? $sendSMS->instGeneralAlert($bozeki_password, $bozeki_url, $sysabb, $phone2, $sms, $bbranchid, $sms_refid, $sms_rate, $iuid, $imywallet_balance, $debitWallet) : ($debitWAllet == "Yes" && $sms_rate <= $iwallet_balance && ($selfServiceTransAuth == "On" || $otp_option == "Yes" || $otp_option == "Both") ? $sendSMS->instGeneralAlert($bozeki_password, $bozeki_url, $isenderid, $phone2, $sms, $bbranchid, $sms_refid, $sms_rate, $iuid, $imywallet_balance, $debitWallet) : ""));
            echo ($selfServiceTransAuth == "On" || $otp_option == "Yes" || $otp_option == "Both") ? "<div align='center'><img src='".$fetchsys_config['file_baseurl']."checkmark.gif'><p style='color: #38A1F3;'>Great! Otp has been sent to your mobile phone to complete this transaction!!</p></div>" : "<div align='center'><img src='".$fetchsys_config['file_baseurl']."checkmark.gif'><p style='color: #38A1F3;'>Transfer Request Received Successfully! Wait a moment to complete the transaction with your Pin</p></div>";
            echo '<meta http-equiv="refresh" content="2;url=transfer.php?tid='.$_SESSION['tid'].'&&acn='.$acctno.'&&mid=NDA4&&tab=tab_4&&'.$otpChecker.'">';
        }
	}
} 
?>



<?php
if (isset($_POST['confirm']))
{    
    include("../config/intlrestful_apicalls.php");
    
    $result = array();
    $result1 = array();
    $result2 = array();
    $myotp = $_POST['otp'];
    $currenctdate = date("Y-m-d H:i:s");
    
    $verify_otp = mysqli_query($link, "SELECT * FROM otp_confirmation WHERE otp_code = '$myotp' AND userid = '$acctno' AND status = 'Pending'");
	$fetch_data = mysqli_fetch_array($verify_otp);
	$otpnum = mysqli_num_rows($verify_otp);
	
	$verify_otp1 = mysqli_query($link, "SELECT * FROM otp_confirmation WHERE otp_code = '$myotp' AND userid = '$acctno' AND status = 'Pending'");
	$otpnum1 = mysqli_num_rows($verify_otp1);
	
	if($otpnum1 > 1){
		
		mysqli_query($link, "UPDATE borrowers SET status = 'Suspended' WHERE account = '$acctno'");
        mysqli_query($link, "INSERT INTO wallet_case VALUES(null,'$bbranchid','$bsbranchid','System','$acctno','Suspended','Frudulent Act Detected','$currenctdate')");
        mysqli_query($link, "DELETE FROM otp_confirmation WHERE userid = '$acctno' AND otp_code = '$myotp' AND status = 'Pending'");
        
	    echo "<div class='alert bg-orange'>Duplicate Transaction is not allowed!!</div>";
	    echo '<meta http-equiv="refresh" content="2;url=transfer.php?tid='.$_SESSION['tid'].'&&acn='.$acctno.'&&mid=NDA4&&tab=tab_4">';
						        
	}				    
	elseif($otpnum == 0 && $otpnum1 == 0){
						        
	    echo "<div class='alert bg-orange'>Opps!...Invalid Credential!!</div>";
						        
	}else{
	    
	    $concat = $fetch_data['data'];
    
        $datetime = $fetch_data['datetime'];
                                
        $parameter = (explode('|',$concat));
        
        $account_bank = $parameter[0];
        $recipientAcctNo = $parameter[1];
        $amountWithNoCharges = $parameter[2];
        $amountWithCharges = $parameter[3];
        $mnarration = $parameter[4];
        $currency = $parameter[5];
        $tReference = $parameter[6];
        $accountName = $parameter[7];
        $branch_code = $parameter[8];
        $balanceLeft = $parameter[10];
        $defaultCustomerBal = $bwallet_balance - $amountWithCharges;
        $calcCharges = $amountWithCharges - $amountWithNoCharges;
            
        //Get my wallet balance after debiting
        $senderBalance = $defaultCustomerBal;
        $senderEmail = $email2;
        $senderName = $bname;
        $merchantName = $binst_name;
        $liveBalanceLeft = ($balanceLeft > $bwallet_balance) ? $bwallet_balance : $balanceLeft;
        
        if($bwallet_balance < $amountWithCharges){
            
            mysqli_query($link, "DELETE FROM otp_confirmation WHERE userid = '$acctno' AND otp_code = '$myotp' AND status = 'Pending'");
            
            echo "<div class='alert alert-danger'>Oops! You have insufficient fund in your Wallet!!</div>";
            echo '<meta http-equiv="refresh" content="2;url=transfer.php?tid='.$_SESSION['tid'].'&&acn='.$acctno.'&&mid=NDA4&&tab=tab_4">';
            
        }else{
            
            //Debit Customer Wallet
            mysqli_query($link, "UPDATE borrowers SET wallet_balance = '$senderBalance' WHERE account = '$acctno'");

            $transactionDateTime = date("Y-m-d h:i:s");
            //10 rows [0 - 10]
            $dataToProcess = $account_bank."|".$recipientAcctNo."|".$amountWithNoCharges."|".$rave_secret_key."|".$mnarration."|".$currency."|".$tReference."|".$accountName."|".$bbcurrency."|".$branch_code."|".$senderBalance;
            //insert txt waiting list
            $mytxtstatus = 'Pending';
            mysqli_query($link, "INSERT INTO api_txtwaitinglist VALUE(null,'$acctno','$tReference','$dataToProcess','$mytxtstatus','$transactionDateTime')");
    
            //fetch endpoint
            $search_restapi1 = mysqli_query($link, "SELECT * FROM restful_apisetup WHERE api_name = 'transfer'");
            $fetch_restapi1 = mysqli_fetch_object($search_restapi1);
            $api_url1 = $fetch_restapi1->api_url;
                
            // Pass the parameter here
            $postdata1 =  array(
                    "account_bank"      =>  $account_bank,
                    "account_number"    =>  $recipientAcctNo,
                    "amount"            =>  $amountWithNoCharges,
                    "seckey"            =>  $rave_secret_key,
                    "narration"         =>  $mnarration,
                    "currency"          =>  $currency,
                    "reference"         =>  $tReference,
                    "beneficiary_name"  =>  $accountName,
                    "debit_currency"    =>  $bbcurrency,
                    "destination_branch_code"=> $branch_code
            );
                
            $make_call1 = intlCallAPI('POST', $api_url1, json_encode($postdata1));
            $result1 = json_decode($make_call1, true);
                
            if($result1['status'] == "success"){
                    
                $transfer_id = $result['data']['id'];
                $transfers_fee = "Gateway Fee: ".$bbcurrency.$result['data']['fee']." | Inhouse Fee: ".$bbcurrency.$calcCharges;
                $mybank_name = $result['data']['bank_name'];
                $status = $result['data']['status'];
                $recipient = $recipientAcctNo.', '.$accountName.', '.$account_bank.', '.$mybank_name;
    
                //UPDATE WAITING TXT
                mysqli_query($link, "UPDATE api_txtwaitinglist SET status = '$make_call1' WHERE userid = '$acctno' AND refid = '$tReference' AND status = '$mytxtstatus'");
                
                //Log successful transaction history
                mysqli_query($link, "INSERT INTO wallet_history VALUE(null,'$bbranchid','$tReference','$recipient','','$amountWithNoCharges','Debit','$currency','BANK_TRANSFER','Transfer to $recipient','successful','$transactionDateTime','$acctno','$senderBalance','Flutterwave')");
                mysqli_query($link, "INSERT INTO wallet_history VALUE(null,'$bbranchid','$tReference','$recipient','','$calcCharges','Debit','$currency','Charges','Transfer to $recipient, Remark: $mnarration','successful','$transactionDateTime','$acctno','$senderBalance','Flutterwave')");
    
                $sendSMS->bankTransferEmailNotifier($senderEmail, $tReference, $senderName, $transactionDateTime, $accountName, $recipientAcctNo, $mybank_name, $merchantName, $bbcurrency, $amountWithNoCharges, $senderBalance, $emailConfigStatus, $fetch_emailConfig);
    
                mysqli_query($link, "DELETE FROM otp_confirmation WHERE userid = '$acctno' AND otp_code = '$myotp' AND status = 'Pending'");
                    
                echo "<div class='alert bg-blue'>Transfer Initiated Successful! Current Wallet Balance is: <b style='color: white;'>".$bbcurrency.number_format($senderBalance,2,'.',',')."</b></div>";
            	echo '<meta http-equiv="refresh" content="3;url=transfer.php?tid='.$_SESSION['tid'].'&&acn='.$acctno.'&&mid=NDA4&&tab=tab_4">';
                    
            }
            else{
    
                $defaultBalance = $liveBalanceLeft;
    
                //Reverse to Customer Wallet
                mysqli_query($link, "UPDATE borrowers SET wallet_balance = '$defaultBalance' WHERE account = '$acctno'");
    
                //UPDATE WAITING TXT
                mysqli_query($link, "UPDATE api_txtwaitinglist SET status = '$make_call1' WHERE userid = '$acctno' AND refid = '$tReference' AND status = '$mytxtstatus'");
    
                mysqli_query($link, "DELETE FROM otp_confirmation WHERE userid = '$acctno' AND otp_code = '$myotp' AND status = 'Pending'");
                
                echo "<div class='alert bg-orange'>Sorry! Unable to conclude transaction, please try again later!!</div>";
                echo '<meta http-equiv="refresh" content="3;url=transfer.php?tid='.$_SESSION['tid'].'&&acn='.$acctno.'&&mid=NDA4&&tab=tab_4">';
                
            }
            
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
                    <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Country</label>
                    <div class="col-sm-6">
                        <select name="country"  class="form-control select2" id="country" onchange="loadbank1();" required>
                          <option value="" selected>Please Select Country</option>
                          <option value="GH">Ghana</option>
                        </select>
                    </div>
                    <label for="" class="col-sm-3 control-label"></label>
                </div>
                
                <div class="form-group">
                    <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Bank</label>
                    <div class="col-sm-6">
                        <div id="bank_list1"></div>
                    </div>
                    <label for="" class="col-sm-3 control-label"></label>
                </div>
                
                <div class="form-group">
                    <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Bank Branch</label>
                    <div class="col-sm-6">
                        <div id="branch_code"></div>
                    </div>
                    <label for="" class="col-sm-3 control-label"></label>
                </div>
                
                <div class="form-group">
                    <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Account Bank</label>
                    <div class="col-sm-6">
                        <select name="account_bank"  class="form-control select2" required>
                          <option value="" selected>Select Account Bank</option>
                          <option value="MTN">MTN</option>
                          <option value="TIGO">TIGO</option>
                          <option value="VODAFONE">VODAFONE</option>
                          <option value="AIRTEL">AIRTEL</option>
                        </select>
                    </div>
                    <label for="" class="col-sm-3 control-label"></label>
                </div>
                
                <div class="form-group">
                    <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Account Number</label>
                    <div class="col-sm-6">
                        <input name="account_number" type="text" class="form-control" placeholder="Recipient Mobile Money Number" required>
                    </div>
                    <label for="" class="col-sm-3 control-label"></label>
                </div>
                
                <div class="form-group">
                    <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Amount</label>
                    <div class="col-sm-6">
                        <input name="amt_totransfer" type="text" class="form-control" placeholder="Enter Amount to Transfer" required>
                    </div>
                    <label for="" class="col-sm-3 control-label"></label>
                </div>
                
                <div class="form-group">
                    <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Beneficiary Name</label>
                    <div class="col-sm-6">
                        <input name="b_name" type="text" id="act_numb" class="form-control" placeholder="Enter Beneficiary Name e.g. Kwame Adew" required> 
                    </div>
                    <label for="" class="col-sm-3 control-label"></label>
                </div>
                
                <div class="form-group">
                    <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Remark</label>
                    <div class="col-sm-6">
                        <textarea name="reasons" class="form-control" placeholder="State Reasons for Transfer e.g. For House rent, Pay Bonus etc." rows="2" cols="5" required></textarea>
                    </div>
                    <label for="" class="col-sm-3 control-label"></label>
                </div>

            </div>
            
            <div class="form-group" align="right">
                <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;"></label>
                <div class="col-sm-6">
                    <button name="ghana_save" type="submit" onclick="var e=this;setTimeout(function(){e.disabled=true;},0);return true;" class="btn bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?> btn-flat"><i class="fa fa-forward">&nbsp;Initiate Transfer</i></button>
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
  elseif($tab == 'tab_5')
  {
  ?> 

      <div class="tab-pane <?php echo ($_GET['tab'] == 'tab_5') ? 'active' : ''; ?>" id="tab_5">

        <div class="box-body">

          <form class="form-horizontal" method="post" enctype="multipart/form-data">

<?php
if(isset($_POST['ugandan_save']))
{
    include("../config/restful_apicalls.php");
    
    $reference =  "EA-ugxTransva-".myreference(10);
    $acctbank =  mysqli_real_escape_string($link, $_POST['account_bank']);
    $account_number =  mysqli_real_escape_string($link, $_POST['account_number']);
    $currency = "UGX";
    $amountWithNoCharges = preg_replace('/[^0-9.]/', '', mysqli_real_escape_string($link, $_POST['amt_totransfer']));
	$bname = mysqli_real_escape_string($link, $_POST['b_name']);
	$mnarration =  mysqli_real_escape_string($link, $_POST['reasons']);
	$currenctdate = date("Y-m-d H:i:s");

	$query = mysqli_query($link, "SELECT * FROM systemset") or die (mysqli_error($link));
    $r = mysqli_fetch_object($query);
    $intlpayment_charges = $row1->intlpayment_charges;
    $sysabb = ($bsender_id == "") ? $r->abb : $bsender_id;
    
    //Currency conversion
    $search_restapi = mysqli_query($link, "SELECT * FROM restful_apisetup WHERE api_name = 'Exchange_Rate'");
	$fetch_restapi = mysqli_fetch_object($search_restapi);
	$api_url = $fetch_restapi->api_url;
	
	// Pass the parameter here
	$postdata =  array(
	    "secret_key"	=>	$rave_secret_key,
	    "service"       =>  "rates_convert",
	    "service_method"    =>  "post",
	    "service_version"   =>  "v1",
	    "service_channel"   =>  "transactions",
	    "service_channel_group" =>  "merchants",
	    "service_payload"   =>  [
	        "FromCurrency"  =>  $currency,
	        "ToCurrency"    =>  $bbcurrency,
	        "Amount"        =>  $amountWithNoCharges
	        ]
	    );
	    
	$make_call = callAPI('POST', $api_url, json_encode($postdata));
	$result = json_decode($make_call, true);
    
    $convertedAmount = $result['data']['ToAmount'];
    
    $otp_code = ($selfServiceTransAuth == "On" || $otp_option == "Yes" || $otp_option == "Both") ? substr((uniqid(rand(),1)),3,6) : $myuepin;
    
    $otpChecker = ($selfServiceTransAuth == "On" || $otp_option == "Yes" || $otp_option == "Both") ? "otp" : "pin";
								
	$sms = "$sysabb>>>Dear $myln! Your One Time Password is $otp_code";
								
	$search_insti = mysqli_query($link, "SELECT * FROM institution_data WHERE institution_id = '$bbranchid'");
    $fetch_myinst = mysqli_fetch_array($search_insti);
    $iwallet_balance = $fetch_myinst['wallet_balance'];
                            	
    $sms_rate = $r->fax;
    $imywallet_balance = $iwallet_balance - $sms_rate;
    $sms_refid = "EA-smsCharges-".time();
	
	//New Amount + Charges
    $amountWithCharges = ($intlpayment_charges * $convertedAmount) + $convertedAmount;
    $senderBalance = $bwallet_balance - $amountWithCharges;
	
	//Data Parser (array size = 7)
    $mydata = $acctbank."|".$account_number."|".$convertedAmount."|".$amountWithCharges."|".$mnarration."|".$currency."|".$reference."|".$bname."|".$bwallet_balance;
    
    if($amountWithNoCharges <= 0){

        echo "<div class='alert bg-orange'>Sorry! Invalid amount entered!!</div>";

    }
    elseif($bactive_status == "Suspended"){
        
        echo "<div class='alert bg-orange'>Oops! You are unable to make transfer at the moment!!</div>";
        
    }
	elseif($bwallet_balance < $amountWithCharges){
	    
	    echo "<div class='alert bg-orange'>Sorry! You need to have upto $currency.number_format($amountWithCharges,2,'.',',') in your wallet to proceed!!</div>";
	    
    }
    elseif($amountWithNoCharges > $btransferLimitPerTrans){

        echo "<div class='alert bg-orange'>Sorry! You can not transfer more than ".$bbcurrency.number_format($btransferLimitPerTrans,2,'.',',')." at once!!</div>";

    }
    elseif($bmyDailyTransferLimit == $btransferLimitPerDay || (($amountWithNoCharges + $bmyDailyTransferLimit) > $btransferLimitPerDay)){

        echo "<div class='alert bg-orange'>Oops! You have reached your daily limit of ".$bbcurrency.number_format($btransferLimitPerDay,2,'.',',')."</div>";

    }
    elseif($bvirtual_acctno === "$account_number" || $acctno === "$account_number"){
        
        mysqli_query($link, "UPDATE borrowers SET status = 'Suspended' WHERE account = '$acctno'");
        mysqli_query($link, "INSERT INTO wallet_case VALUES(null,'$bbranchid','$bsbranchid','System','$acctno','Suspended','Frudulent Act Detected','$currenctdate')");
        
        echo "<div class='alert bg-orange'>Oops! Request Failed!!</div>";
        
    }
	else{
        
        $update = mysqli_query($link,"INSERT INTO otp_confirmation VALUES(null,'$acctno','$otp_code','$mydata','Pending','$currenctdate')") or die(mysqli_error($link));
    
        if(!($update && $insert))
        {
            echo "<div class='alert bg-orange'>Unable to Transfer Fund.....Please try again later</div>";
        }
        else{
            $iuid = "";
            ($debitWAllet == "No" && ($selfServiceTransAuth == "On" || $otp_option == "Yes" || $otp_option == "Both") ? $sendSMS->instGeneralAlert($bozeki_password, $bozeki_url, $sysabb, $phone2, $sms, $bbranchid, $sms_refid, $sms_rate, $iuid, $imywallet_balance, $debitWallet) : ($debitWAllet == "Yes" && $sms_rate <= $iwallet_balance && ($selfServiceTransAuth == "On" || $otp_option == "Yes" || $otp_option == "Both") ? $sendSMS->instGeneralAlert($bozeki_password, $bozeki_url, $isenderid, $phone2, $sms, $bbranchid, $sms_refid, $sms_rate, $iuid, $imywallet_balance, $debitWallet) : ""));
            echo ($selfServiceTransAuth == "On" || $otp_option == "Yes" || $otp_option == "Both") ? "<div align='center'><img src='".$fetchsys_config['file_baseurl']."checkmark.gif'><p style='color: #38A1F3;'>Great! Otp has been sent to your mobile phone to complete this transaction!!</p></div>" : "<div align='center'><img src='".$fetchsys_config['file_baseurl']."checkmark.gif'><p style='color: #38A1F3;'>Transfer Request Received Successfully! Wait a moment to complete the transaction with your Pin</p></div>";
            echo '<meta http-equiv="refresh" content="2;url=transfer.php?tid='.$_SESSION['tid'].'&&acn='.$acctno.'&&mid=NDA4&&tab=tab_5&&'.$otpChecker.'">';
        }
	}
}
?>



<?php
if (isset($_POST['confirm']))
{
    include("../config/intlrestful_apicalls.php");
    
    $result = array();
    $result1 = array();
    $result2 = array();
    $myotp = $_POST['otp'];
    $currenctdate = date("Y-m-d H:i:s");
    
    $verify_otp = mysqli_query($link, "SELECT * FROM otp_confirmation WHERE otp_code = '$myotp' AND userid = '$acctno' AND status = 'Pending'");
	$fetch_data = mysqli_fetch_array($verify_otp);
	$otpnum = mysqli_num_rows($verify_otp);
	
	$verify_otp1 = mysqli_query($link, "SELECT * FROM otp_confirmation WHERE otp_code = '$myotp' AND userid = '$acctno' AND status = 'Pending'");
	$otpnum1 = mysqli_num_rows($verify_otp1);
	
	if($otpnum1 > 1){
		
		mysqli_query($link, "UPDATE borrowers SET status = 'Suspended' WHERE account = '$acctno'");
        mysqli_query($link, "INSERT INTO wallet_case VALUES(null,'$bbranchid','$bsbranchid','System','$acctno','Suspended','Frudulent Act Detected','$currenctdate')");
        mysqli_query($link, "DELETE FROM otp_confirmation WHERE userid = '$acctno' AND otp_code = '$myotp' AND status = 'Pending'");
        
	    echo "<div class='alert bg-orange'>Duplicate Transaction is not allowed!!</div>";
	    echo '<meta http-equiv="refresh" content="2;url=transfer.php?tid='.$_SESSION['tid'].'&&acn='.$acctno.'&&mid=NDA4&&tab=tab_5">';
						        
	}				    
	elseif($otpnum == 0 && $otpnum1 == 0){
						        
	    echo "<div class='alert bg-orange'>Opps!...Invalid Credential!!</div>";
						        
	}else{
	    
	    $concat = $fetch_data['data'];
    
        $datetime = $fetch_data['datetime'];
                                
        $parameter = (explode('|',$concat));

        $acctbank = $parameter[0];
        $recipientAcctNo = $parameter[1];
        $amountWithNoCharges = $parameter[2];
        $amountWithCharges = $parameter[3];
        $mnarration = $parameter[4];
        $currency = $parameter[5];
        $tReference = $parameter[6];
        $accountName = $parameter[7];
        $balanceLeft = $parameter[8];
        $defaultCustomerBal = $bwallet_balance - $amountWithCharges;
        $calcCharges = $amountWithCharges - $amountWithNoCharges;
                    
        //Get my wallet balance after debiting
        $senderBalance = $defaultCustomerBal;
        $senderEmail = $email2;
        $senderName = $bname;
        $merchantName = $binst_name;
        $liveBalanceLeft = ($balanceLeft > $bwallet_balance) ? $bwallet_balance : $balanceLeft;
        
        if($bwallet_balance < $amountWithCharges){
            
            mysqli_query($link, "DELETE FROM otp_confirmation WHERE userid = '$acctno' AND otp_code = '$myotp' AND status = 'Pending'");
            
            echo "<div class='alert alert-danger'>Oops! You have insufficient fund in your Wallet!!</div>";
            echo '<meta http-equiv="refresh" content="2;url=transfer.php?tid='.$_SESSION['tid'].'&&acn='.$acctno.'&&mid=NDA4&&tab=tab_5">';
            
        }else{
            
            //Debit Customer Wallet
            mysqli_query($link, "UPDATE borrowers SET wallet_balance = '$senderBalance' WHERE account = '$acctno'");

            $transactionDateTime = date("Y-m-d h:i:s");
            //9 rows [0 - 9]
            $dataToProcess = $acctbank."|".$recipientAcctNo."|".$amountWithNoCharges."|".$rave_secret_key."|".$mnarration."|".$currency."|".$tReference."|".$accountName."|".$bbcurrency."|".$senderBalance;
            //insert txt waiting list
            $mytxtstatus = 'Pending';
            mysqli_query($link, "INSERT INTO api_txtwaitinglist VALUE(null,'$acctno','$tReference','$dataToProcess','$mytxtstatus','$transactionDateTime')");
    
            //fetch endpoint
            $search_restapi1 = mysqli_query($link, "SELECT * FROM restful_apisetup WHERE api_name = 'transfer'");
            $fetch_restapi1 = mysqli_fetch_object($search_restapi1);
            $api_url1 = $fetch_restapi1->api_url;
                
            // Pass the parameter here
            $postdata1 =  array(
                    "account_bank"      =>  $acctbank,
                    "account_number"    =>  $recipientAcctNo,
                    "amount"            =>  $amountWithNoCharges,
                    "seckey"            =>  $rave_secret_key,
                    "narration"         =>  $mnarration,
                    "currency"          =>  $currency,
                    "reference"         =>  $tReference,
                    "beneficiary_name"  =>  $accountName,
                    "debit_currency"    =>  $bbcurrency
            );
                
            $make_call1 = intlCallAPI('POST', $api_url1, json_encode($postdata1));
            $result1 = json_decode($make_call1, true);
                
            if($result['status'] == "success"){
                    
                $transfer_id = $result['data']['id'];
                $transfers_fee = "Gateway Fee: ".$bbcurrency.$result['data']['fee']." | Inhouse Fee: ".$bbcurrency.$calcCharges;
                $mybank_name = $result['data']['bank_name'];
                $status = $result['data']['status'];
                $recipient = $recipientAcctNo.', '.$accountName.', '.$acctbank.', '.$mybank_name;
    
                //UPDATE WAITING TXT
                mysqli_query($link, "UPDATE api_txtwaitinglist SET status = '$make_call1' WHERE userid = '$acctno' AND refid = '$tReference' AND status = '$mytxtstatus'");
                    
                //Log successful transaction history
                mysqli_query($link, "INSERT INTO wallet_history VALUE(null,'$bbranchid','$tReference','$recipient','','$amountWithNoCharges','Debit','$currency','BANK_TRANSFER','Transfer to $recipient','successful','$transactionDateTime','$acctno','$senderBalance','Flutterwave')");
                mysqli_query($link, "INSERT INTO wallet_history VALUE(null,'$bbranchid','$tReference','$recipient','','$calcCharges','Debit','$currency','Charges','Transfer to $recipient, Remark: $mnarration','successful','$transactionDateTime','$acctno','$senderBalance','Flutterwave')");
    
                $sendSMS->bankTransferEmailNotifier($senderEmail, $tReference, $senderName, $transactionDateTime, $accountName, $recipientAcctNo, $mybank_name, $merchantName, $bbcurrency, $amountWithNoCharges, $senderBalance, $emailConfigStatus, $fetch_emailConfig);
    
                mysqli_query($link, "DELETE FROM otp_confirmation WHERE userid = '$acctno' AND otp_code = '$myotp' AND status = 'Pending'");
                    
                echo "<div class='alert bg-blue'>Transfer Initiated Successful! Current Wallet Balance is: <b style='color: white;'>".$bbcurrency.number_format($senderBalance,2,'.',',')."</b></div>";
            	echo '<meta http-equiv="refresh" content="3;url=transfer.php?tid='.$_SESSION['tid'].'&&acn='.$acctno.'&&mid=NDA4&&tab=tab_5">';
                    
            }
            else{
    
                $defaultBalance = $liveBalanceLeft;
    
                //Reverse to Customer Wallet
                mysqli_query($link, "UPDATE borrowers SET wallet_balance = '$defaultBalance' WHERE account = '$acctno'");
                
                //UPDATE WAITING TXT
                mysqli_query($link, "UPDATE api_txtwaitinglist SET status = '$make_call1' WHERE userid = '$acctno' AND refid = '$tReference' AND status = '$mytxtstatus'");
    
                mysqli_query($link, "DELETE FROM otp_confirmation WHERE userid = '$acctno' AND otp_code = '$myotp' AND status = 'Pending'");
    
                echo "<div class='alert bg-orange'>Sorry! Unable to conclude transaction, please try again later!!</div>";
                echo '<meta http-equiv="refresh" content="3;url=transfer.php?tid='.$_SESSION['tid'].'&&acn='.$acctno.'&&mid=NDA4&&tab=tab_5">';
                
            }
            
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
                    <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Account Bank</label>
                    <div class="col-sm-6">
                        <select name="account_bank"  class="form-control select2" required>
                          <option value="" selected>Select Account Bank</option>
                          <option value="MTN">MTN</option>
                          <option value="AIRTEL">AIRTEL</option>
                        </select>  
                    </div>
                    <label for="" class="col-sm-3 control-label"></label>
                </div>
                
                <div class="form-group">
                    <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Account Number</label>
                    <div class="col-sm-6">
                        <input name="account_number" type="text" class="form-control" placeholder="Recipient Mobile Money Number" required>
                        <span style="color: <?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>"><b>NOTE:</b> It should always come with the prefix <b>256</b></span>
                    </div>
                    <label for="" class="col-sm-3 control-label"></label>
                </div>
                
                <div class="form-group">
                    <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Amount</label>
                    <div class="col-sm-6">
                        <input name="amt_totransfer" type="text" class="form-control" placeholder="Enter Amount to Transfer" required>
                    </div>
                    <label for="" class="col-sm-3 control-label"></label>
                </div>
                
                <div class="form-group">
                    <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Beneficiary Name</label>
                    <div class="col-sm-6">
                        <input name="b_name" type="text" id="act_numb" class="form-control" placeholder="Enter Beneficiary Name e.g. Kwame Adew" required>
                    </div>
                    <label for="" class="col-sm-3 control-label"></label>
                </div>
                
                <div class="form-group">
                    <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Remark</label>
                    <div class="col-sm-6">
                        <textarea name="reasons" class="form-control" placeholder="State Reasons for Transfer e.g. For House rent, Pay Bonus etc." rows="2" cols="5" required></textarea>
                    </div>
                    <label for="" class="col-sm-3 control-label"></label>
                </div>

            </div>
            
            <div class="form-group" align="right">
                <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;"></label>
                <div class="col-sm-6">
                    <button name="ugandan_save" type="submit" onclick="var e=this;setTimeout(function(){e.disabled=true;},0);return true;" class="btn bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?> btn-flat"><i class="fa fa-forward">&nbsp;Initiate Transfer</i></button>
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
  elseif($tab == 'tab_8')
  {
  ?>
  
    <div class="tab-pane <?php echo ($_GET['tab'] == 'tab_8') ? 'active' : ''; ?>" id="tab_8">

             <div class="box-body">
           
       <form class="form-horizontal" method="post" enctype="multipart/form-data">

             <div class="box-body">
                 
                 <div class="form-group">
                    <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Origin Currency</label>
                    <div class="col-sm-6">
                        <select name="orcurrency"  class="form-control select2" required>
                          <option value="" selected>Select Origin Currency</option>
                          <option value="NGN">NGN</option>
                          <option value="USD">USD</option>
                          <option value="GBP">GBP</option>
                          <option value="EUR">EUR</option>
                          <option value="GHS">GHS</option>
                          <option value="KES">KES</option>
                          <option value="UGX">UGX</option>
                          <option value="TZS">TZS</option>
                        </select>
                    </div>
                    <label for="" class="col-sm-3 control-label"></label>
                </div>
                
                <div class="form-group">
                    <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Destination Currency</label>
                    <div class="col-sm-6">
                        <select name="decurrency"  class="form-control select2" required>
                          <option value="" selected>Select Destination Currency</option>
                          <option value="NGN">NGN</option>
                          <option value="USD">USD</option>
                          <option value="GBP">GBP</option>
                          <option value="EUR">EUR</option>
                          <option value="GHS">GHS</option>
                          <option value="KES">KES</option>
                          <option value="UGX">UGX</option>
                          <option value="TZS">TZS</option>
                        </select>
                    </div>
                    <label for="" class="col-sm-3 control-label"></label>
                </div>
                
                <div class="form-group">
                    <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Amount</label>
                    <div class="col-sm-6">
                        <input name="amt_toconvert" type="text" class="form-control" placeholder="Enter Amount to Convert" required>
                    </div>
                    <label for="" class="col-sm-3 control-label"></label>
                </div>
      
            </div>
            
            <div class="form-group" align="right">
                <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;"></label>
                <div class="col-sm-6">
                    <button name="Convert_save" type="submit" class="btn bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?> btn-flat"><i class="fa fa-refresh">&nbsp;Convert</i></button>
                </div>
                <label for="" class="col-sm-3 control-label"></label>
             </div>
        
<?php
if(isset($_POST['Convert_save']))
{
    include("../config/restful_apicalls.php");
    
    $result = array();
    $orcurrency =  mysqli_real_escape_string($link, $_POST['orcurrency']);
    $decurrency =  mysqli_real_escape_string($link, $_POST['decurrency']);
    $amt_toconvert =  mysqli_real_escape_string($link, $_POST['amt_toconvert']);
    
    $systemset = mysqli_query($link, "SELECT * FROM systemset");
	$row1 = mysqli_fetch_object($systemset);
	$seckey = $row1->secret_key;
	
	$search_restapi = mysqli_query($link, "SELECT * FROM restful_apisetup WHERE api_name = 'Exchange_Rate'");
	$fetch_restapi = mysqli_fetch_object($search_restapi);
	$api_url = $fetch_restapi->api_url;
	
	// Pass the parameter here
	$postdata =  array(
	    "secret_key"	=>	$seckey,
	    "service"       =>  "rates_convert",
	    "service_method"    =>  "post",
	    "service_version"   =>  "v1",
	    "service_channel"   =>  "transactions",
	    "service_channel_group" =>  "merchants",
	    "service_payload"   =>  [
	        "FromCurrency"  =>  $orcurrency,
	        "ToCurrency"    =>  $decurrency,
	        "Amount"        =>  $amt_toconvert
	        ]
	    );
	    
	    $make_call = callAPI('POST', $api_url, json_encode($postdata));
		$result = json_decode($make_call, true);

		if($result['status'] == "success"){
		    echo "<hr>";
		    echo '<div class="form-group">
                    <label for="" class="col-sm-3 control-label" style="color:'.(($myaltrow['theme_color'] == "") ? "blue" : $myaltrow['theme_color']).';">Exchange Rate:</label>
                    <div class="col-sm-6">
                        <input type="text" class="form-control" value="'.$result['data']['ToCurrencyName'].$result['data']['Rate'].' per '.$orcurrency.'" readonly/>
                    </div>
                    <label for="" class="col-sm-3 control-label"></label>
                </div>';
                
            echo '<div class="form-group">
                    <label for="" class="col-sm-3 control-label" style="color:'.(($myaltrow['theme_color'] == "") ? "blue" : $myaltrow['theme_color']).';">Exchange Fee:</label>
                    <div class="col-sm-6">
                        <input type="text" class="form-control" value="'.$result['data']['Fee'].'" readonly/>
                    </div>
                    <label for="" class="col-sm-3 control-label"></label>
                </div>';
                
            echo '<div class="form-group">
                    <label for="" class="col-sm-3 control-label" style="color:'.(($myaltrow['theme_color'] == "") ? "blue" : $myaltrow['theme_color']).';">Convert From:</label>
                    <div class="col-sm-6">
                        <input type="text" class="form-control" value="'.$orcurrency.number_format($amt_toconvert,2,'.',',').'" readonly/>
                    </div>
                    <label for="" class="col-sm-3 control-label"></label>
                </div>';
                
            echo '<div class="form-group">
                    <label for="" class="col-sm-3 control-label" style="color:'.(($myaltrow['theme_color'] == "") ? "blue" : $myaltrow['theme_color']).';">Result To:</label>
                    <div class="col-sm-6">
                        <input type="text" class="form-control" value="'.$result['data']['ToCurrencyName'].number_format($result['data']['ToAmount'],2,'.',',').'" readonly/>
                    </div>
                    <label for="" class="col-sm-3 control-label"></label>
                </div>';
		}
		else{
		    echo "<p style='color: blue; font-size:18px;'>".$result['data']['Message']."</p>";
		}
}
?>     
       </form> 

      </div>
    </div>

  <?php
  }
  elseif($tab == 'tab_9'){
      $search_payvice = mysqli_query($link, "SELECT * FROM payvice_accesspoint");
	  $payvice_num = mysqli_num_rows($search_payvice);
      ?>
      
      
      
      <div class="tab-pane <?php echo ($_GET['tab'] == 'tab_9') ? 'active' : ''; ?>" id="tab_9">

        <div class="box-body">

          <form class="form-horizontal" method="post" enctype="multipart/form-data">


<?php
if(!(isset($_GET['otp']) || isset($_GET['pin'])))
{
?>
            <div class="box-body">
                
                <div class="form-group">
                    <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Wallet ID</label>
                    <div class="col-sm-6">
                        <input name="beneficiary" type="text" class="form-control" placeholder="Enter your Payvice Wallet ID" required>
                    </div>
                    <label for="" class="col-sm-3 control-label"></label>
                </div>
                
                <div class="form-group">
                    <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Amount</label>
                    <div class="col-sm-6">
                        <input name="amt_totransfer" type="text" class="form-control" placeholder="Enter Amount to Transfer" required>
                        <span style="color: <?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>;">Enter amount you want to transfer from your <b>Wallet Balance</b> to your <b>Payvice Wallet</b></span>
                    </div>
                    <label for="" class="col-sm-3 control-label"></label>
                </div>
                
                <div class="form-group">
                    <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Transaction Pin</label>
                    <div class="col-sm-6">
                        <input name="tpin" type="password" class="form-control" placeholder="Enter your 4 digits Transaction Pin" required>
                    </div>
                    <label for="" class="col-sm-3 control-label"></label>
                </div>

            </div>
            
            <div class="form-group" align="right">
                <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;"></label>
                <div class="col-sm-6">
                    <button name="Payvice_transfer" type="submit" onclick="var e=this;setTimeout(function(){e.disabled=true;},0);return true;" class="btn bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?> btn-flat" <?php echo ($payvice_num == 1) ? "" : "disabled"; ?>><i class="fa fa-forward">&nbsp;Transfer Fund</i></button>
                </div>
                <label for="" class="col-sm-3 control-label"></label>
             </div>
             
<?php
}
else{
    include("otp_confirmation.php");
}
?>
      
<?php
if(isset($_POST['Payvice_transfer']))
{
    include("../config/restful_apicalls.php");
    
    $result = array();
    $beneficiary =  mysqli_real_escape_string($link, $_POST['beneficiary']);
    $amt_totransfer =  mysqli_real_escape_string($link, $_POST['amt_totransfer']);
    //$myaccount = $_SESSION['acctno'];
   
    $systemset = mysqli_query($link, "SELECT * FROM systemset");
	$row1 = mysqli_fetch_object($systemset);
	$seckey = $row1->secret_key;
	$vat_rate = $row1->vat_rate;
	$transfer_charges = $row1->transfer_charges;
	
	$newAmount = ($vat_rate * $amt_totransfer) + $amt_totransfer + $transfer_charges;
	
	$remainBalance = $bwallet_balance - $newAmount;
	
	$payvice = mysqli_query($link, "SELECT * FROM payvice_accesspoint");
	$row_payvice = mysqli_fetch_object($payvice);
	$pv_walletid = $row_payvice->pv_walletid;
	$pv_username = $row_payvice->pv_username;
	$pv_tpin = $row_payvice->pv_tpin;
	$pv_password = $row_payvice->pv_password;
    
    if($amt_totransfer <= 0){

        echo "<div class='alert bg-orange'>Sorry! Invalid amount entered!!</div>";

    }
	elseif($bwallet_balance < $newAmount){
	    
	    echo "<script>alert('Insufficient Fund in your Wallet!!'); </script>";
	    
		echo "<script>window.location='transfer.php?id=".$_SESSION['tid']."&&acn=".$_GET['acn']."&&mid=NDA4&&tab=tab_9'; </script>";
		
	}
	else{
	
	$search_restapi = mysqli_query($link, "SELECT * FROM restful_apisetup WHERE api_name = 'payvice_lookup'");
	$fetch_restapi = mysqli_fetch_object($search_restapi);
	$api_url = $fetch_restapi->api_url;
	
	// Pass the parameter here
	$postdata =  array(
	    "vice_id"	=>	$pv_walletid,
	    "user_name" =>  $pv_username
	    );
	    
	    $make_call = callAPI('POST', $api_url, json_encode($postdata));
		$result = json_decode($make_call, true);
		
		$mylookup = $result['token'];
		
	$search_restapi = mysqli_query($link, "SELECT * FROM restful_apisetup WHERE api_name = 'payvice_wallet2wallet'");
	$fetch_restapi = mysqli_fetch_object($search_restapi);
	$api_url = $fetch_restapi->api_url;
	
	// Pass the parameter here
	$postdata2 =  array(
	    "vice_id"	    =>	$pv_walletid,
	    "user_name"     =>  $pv_username,
	    "amount"        =>  $amt_totransfer,
	    "beneficiary"   =>  $beneficiary,
	    "auth"          =>  $pv_tpin,
	    "token"         =>  $mylookup,
	    "pwd"           =>  $pv_password,
	    );
	    
	    $make_call2 = callAPI('POST', $api_url, json_encode($postdata2));
		$result = json_decode($make_call2, true);
		
		$txn_ref = $result['txn_ref'];

		if($result['status'] == "1")
		{
		    $icm_id = "ICM".rand(100000,999999);
        
            $revenue = $newAmount - $amt_totransfer;
            
            $icm_date = date("Y/m/d");
        
		    $insert = mysqli_query($link, "INSERT INTO transaction VALUES(null,'$txn_ref','Transfer','Wallet-to-Wallet','$acctno','----','$myfn','$myln','$email2','$phone2','$amt_totransfer','System','Transfer from Esusu Super Wallet to Payvice Wallet',NOW(),'$bbranchid','$bsbranchid')");
		    $insert = mysqli_query($link, "INSERT INTO income VALUES(null,'','$icm_id','Revenue','$revenue','$icm_date','Super Wallet to Payvice Wallet Transfer')");
		    $update_records = mysqli_query($link, "UPDATE borrowers SET wallet_balance = '$remainBalance' WHERE account = '$acctno'");
		    
		    echo "<div class='alert bg-orange'>".$result['message']."</div>";
		    echo "<script>window.location='transfer.php?id=".$_SESSION['tid']."&&acn=".$_GET['acn']."&&mid=NDA4&&tab=tab_9'; </script>";
		}
		else{
		    echo "<p style='color: blue; font-size:18px;'>".$result['message']."</p>";
		}
		
	}
}
?>     
      
      
      </form> 

      </div>
    </div>
      
      
<?php  
  }
  elseif($tab == 'tab_10' && $card_id != "" && $issurer == "Mastercard"){
?>
      
      <div class="tab-pane <?php echo ($_GET['tab'] == 'tab_10') ? 'active' : ''; ?>" id="tab_10">

        <div class="box-body">

          <form class="form-horizontal" method="post" enctype="multipart/form-data">

<?php
if(isset($_POST['Mastercard_transfer']))
{
    include("../config/restful_apicalls.php");
    
    $reference =  "EA-fundCard-".myreference(10);
    $amount = preg_replace('/[^0-9.]/', '', mysqli_real_escape_string($link, $_POST['amount']));
    $amountWithNoCharges = $amount * 100;
    $currenctdate = date("Y-m-d H:i:s");
    
    $query = mysqli_query($link, "SELECT * FROM systemset") or die (mysqli_error($link));
    $r = mysqli_fetch_object($query);
    $transfer_charges = $r->transfer_charges;
    $sysabb = ($bsender_id == "") ? $r->abb : $bsender_id;
    
    $otp_code = ($selfServiceTransAuth == "On" || $otp_option == "Yes" || $otp_option == "Both") ? substr((uniqid(rand(),1)),3,6) : $myuepin;
    
    $otpChecker = ($selfServiceTransAuth == "On" || $otp_option == "Yes" || $otp_option == "Both") ? "otp" : "pin";
								
	$sms = "$sysabb>>>Dear $myln! Your One Time Password is $otp_code";
								
	//SMS DATA
	$search_gateway = mysqli_query($link, "SELECT * FROM sms WHERE smsuser = '' AND status = 'Activated'");
    $fetch_gateway = mysqli_fetch_object($search_gateway);
    $gateway_uname = $fetch_gateway->username;
    $gateway_pass = $fetch_gateway->password;
    $gateway_api = $fetch_gateway->api;
    
    $search_insti = mysqli_query($link, "SELECT * FROM institution_data WHERE institution_id = '$bbranchid'");
    $fetch_myinst = mysqli_fetch_array($search_insti);
    $iwallet_balance = $fetch_myinst['wallet_balance'];
                            	
    $sms_rate = $r->fax;
    $imywallet_balance = $iwallet_balance - $sms_rate;
    $sms_refid = "EA-smsCharges-".time();
	
	//New AMount + Charges
    $amountWithCharges = $amountWithNoCharges + $transfer_charges;
	
	//Data Parser (array size = 2)
    $mydata = $reference."|".$amountWithNoCharges."|".$amountWithCharges;
    
    if($amount <= 0){

        echo "<div class='alert bg-orange'>Sorry! Invalid amount entered!!</div>";

    }
    elseif($bwallet_balance < $amountWithCharges){
	    
	    echo "<div class='alert bg-orange'>Oops! You have Insufficient Fund in your Wallet!!</div>";
	    
	}
	else{
	    
	    $update = mysqli_query($link,"INSERT INTO otp_confirmation VALUES(null,'$acctno','$otp_code','$mydata','Pending','$currenctdate')") or die(mysqli_error($link));
            
        if(!($update && $insert))
        {
            echo "<div class='alert bg-orange'>Unable to Transfer Fund.....Please try again later</div>";
        }
        else{
            $iuid = "";
            ($debitWAllet == "No" && ($selfServiceTransAuth == "On" || $otp_option == "Yes" || $otp_option == "Both") ? $sendSMS->instGeneralAlert($bozeki_password, $bozeki_url, $sysabb, $phone2, $sms, $bbranchid, $sms_refid, $sms_rate, $iuid, $imywallet_balance, $debitWallet) : ($debitWAllet == "Yes" && $sms_rate <= $iwallet_balance && ($selfServiceTransAuth == "On" || $otp_option == "Yes" || $otp_option == "Both") ? $sendSMS->instGeneralAlert($bozeki_password, $bozeki_url, $isenderid, $phone2, $sms, $bbranchid, $sms_refid, $sms_rate, $iuid, $imywallet_balance, $debitWallet) : ""));
            echo ($selfServiceTransAuth == "On" || $otp_option == "Yes" || $otp_option == "Both") ? "<div align='center'><img src='".$fetchsys_config['file_baseurl']."checkmark.gif'><p style='color: #38A1F3;'>Great! Otp has been sent to your mobile phone to complete this transaction!!</p></div>" : "<div align='center'><img src='".$fetchsys_config['file_baseurl']."checkmark.gif'><p style='color: #38A1F3;'>Transfer Request Received Successfully! Wait a moment to complete the transaction with your Pin</p></div>";
            echo '<meta http-equiv="refresh" content="2;url=transfer.php?tid='.$_SESSION['tid'].'&&acn='.$acctno.'&&mid=NDA4&&tab=tab_10&&'.$otpChecker.'">';
        }
        
	}
	
}
?>



<?php
if (isset($_POST['confirm']))
{    
    $result = array();
    $myotp = $_POST['otp'];
    
    $verify_otp = mysqli_query($link, "SELECT * FROM otp_confirmation WHERE otp_code = '$myotp' AND userid = '$acctno' AND status = 'Pending'");
	$fetch_data = mysqli_fetch_array($verify_otp);
	$otpnum = mysqli_num_rows($verify_otp);
	
	if($otpnum == 0){
						        
	    echo "<div class='alert bg-orange'>Opps!...Invalid Credential!!</div>";
						        
	}else{
	    
	    $concat = $fetch_data['data'];
    
        $datetime = $fetch_data['datetime'];
                                
        $parameter = (explode('|',$concat));
        
        $tReference = $parameter[0];
        $amountWithNoCharges = $parameter[1];
        $amountWithCharges = $parameter[2];
        $calcCharges = $amountWithCharges - $amountWithNoCharges;
            
        //Get my wallet balance after debiting
        $senderBalance = $bwallet_balance - $amountWithCharges;
        $senderEmail = $email2;
        $senderName = $bname;
        $merchantName = $institution_row['institution_name'];
            
        $passcode = substr($phone2, -13).$amountWithNoCharges.$bbcurrency.$bancore_merchantID.$bancore_mprivate_key;
        $encKey = hash('sha256',$passcode);
          	
        $api_name =  "card_load";
        $search_restapi1 = mysqli_query($link, "SELECT * FROM atmcard_gateway_apis WHERE api_name = '$api_name' AND issuer_name = '$issurer'");
        $fetch_restapi1 = mysqli_fetch_object($search_restapi1);
        $api_url1 = $fetch_restapi1->api_url;
            
        function cardLoader($ph, $cardcurrency, $amt, $orderID, $debug=false){
            global $bancore_merchantID,$encKey,$api_url1;
              		
            $url = '?accountID='.substr($ph, -13);
            $url.= '&merchantID='.$bancore_merchantID;
            $url.= '&currency='.urlencode($cardcurrency);
            $url.= '&amount='.urlencode($amt);
            $url.= '&orderID='.urlencode($orderID);
            $url.= '&encKey='.$encKey;
                            		  
            $urltouse =  $api_url.$url;
              			  
            //if ($debug) { echo "Request: <br>$urltouse<br><br>"; }
              		
            //Open the URL to send the message
            $response = file_get_contents($urltouse);
                            
            if ($debug) {
                //echo "Response: <br><pre>".
                //str_replace(array("<",">"),array("&lt;","&gt;"),$response).
                //"</pre><br>"; 
              	//echo substr($response, 112);
            }
            return($response);
        }
            
        $debug = true;
      	$cardChecker = cardLoader($phone2,$bbcurrency,$amountWithNoCharges,$tReference,$debug);
      	$iparr = split ("\&", $cardChecker);
      	$regStatus = substr("$iparr[0]",7);
      		
      	if($regStatus == 100 || $regStatus != 30){
  			    
  			mysqli_query($link, "DELETE FROM otp_confirmation WHERE userid = '$acctno' AND otp_code = '$myotp' AND status = 'Pending'");
  			    
            echo '<meta http-equiv="refresh" content="5;url=transfer.php?id='.$_SESSION['tid'].'&&acn='.$_GET['acn'].'&&mid=OTAw&&tab=tab_10">';
            echo '<br>';
            echo'<span class="itext" style="color: orange;">General failure. Please try again</span>';
          			
        }
        elseif($regStatus == 30){

            $final_date_time = date('Y-m-d h:i:s');

        	mysqli_query($link, "UPDATE borrowers SET wallet_balance = '$remainBalance' WHERE account = '$acctno'");
          	mysqli_query($link, "INSERT INTO wallet_history VALUE(null,'$bbranchid','$txid','$acctno','','$amt_totransfer','Debit','$currency','Topup-Prepaid_Card','Response: Prepaid Card was Topup with $currency.$amt_totransfer','successful','$final_date_time','$acctno','$remainBalance','')");
          	mysqli_query($link, "DELETE FROM otp_confirmation WHERE userid = '$acctno' AND otp_code = '$myotp' AND status = 'Pending'");
          		
            echo '<meta http-equiv="refresh" content="5;url=transfer.php?id='.$_SESSION['tid'].'&&acn='.$_GET['acn'].'&&mid=OTAw&&tab=tab_10">';
            echo '<br>';
            echo'<span class="itext" style="color: blue;">Prepaid Card Topup Successfully</span>';
        
        }
        else{
            
            echo "<div class='alert bg-orange'>Opps!...Unable to conclude transaction,, Please Try again later</div>";
            
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
                    <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Amount</label>
                    <div class="col-sm-6">
                        <input name="amount" type="text" class="form-control" placeholder="Enter Amount to Transfer" /required>
                    </div>
                    <label for="" class="col-sm-3 control-label"></label>
                </div>

            </div>
            
            <div class="form-group" align="right">
                <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;"></label>
                <div class="col-sm-6">
                    <button name="Mastercard_transfer" type="submit" onclick="var e=this;setTimeout(function(){e.disabled=true;},0);return true;" class="btn bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>" <?php echo ($card_id != "NULL") ? "" : "disabled"; ?>><i class="fa fa-forward">&nbsp;Transfer Fund</i></button>
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
  elseif($tab == 'tab_11' && $card_id != "" && $issurer == "VerveCard"){
?>
      
      <div class="tab-pane <?php echo ($_GET['tab'] == 'tab_11') ? 'active' : ''; ?>" id="tab_11">

        <div class="box-body">

          <form class="form-horizontal" method="post" enctype="multipart/form-data">


<?php
if(isset($_POST['Vervecard_transfer']))
{
    
    $reference = date("yd").time();
    $amountWithNoCharges = preg_replace('/[^0-9.]/', '', mysqli_real_escape_string($link, $_POST['amount']));
    $pan = mysqli_real_escape_string($link, $_POST['pan']);
    $currenctdate = date("Y-m-d H:i:s");
    
    $query = mysqli_query($link, "SELECT * FROM systemset") or die (mysqli_error($link));
    $r = mysqli_fetch_object($query);
    $sysabb = ($bbranchid == "") ? $r->abb : $bsender_id;
    $smsfee = ($bbranchid == "") ? 0 : $fetchsys_config['fax'];
    
    $otp_code = ($selfServiceTransAuth == "On" || $otp_option == "Yes" || $otp_option == "Both") ? substr((uniqid(rand(),1)),3,6) : $myuepin;
    
    $otpChecker = ($selfServiceTransAuth == "On" || $otp_option == "Yes" || $otp_option == "Both") ? "otp" : "pin";
								
	$sms = "$sysabb>>>Dear $myln! Your One Time Password is $otp_code";
    
    $search_insti = mysqli_query($link, "SELECT * FROM institution_data WHERE institution_id = '$bbranchid'");
    $fetch_myinst = mysqli_fetch_array($search_insti);
    $iwallet_balance = $fetch_myinst['wallet_balance'];
                            	
    $sms_rate = $r->fax;
    $imywallet_balance = $iwallet_balance - $sms_rate - $smsfee;
    $sms_refid = "EA-smsCharges-".time();
	
	//New AMount + Charges
    $amountWithCharges = $amountWithNoCharges + $transferToCardCharges;
	
	//Data Parser (array size = 2)
    $mydata = $reference."|".$amountWithNoCharges."|".$amountWithCharges;
    
    if($amountWithNoCharges <= 0){

        echo "<div class='alert bg-orange'>Sorry! Invalid amount entered!!</div>";

    }
    elseif($bwallet_balance < $amountWithCharges){
	    
	    echo "<div class='alert bg-orange'>Oops! You have Insufficient Fund in your Wallet!!</div>";
	    
	}
	else{
	    
	    $update = mysqli_query($link,"INSERT INTO otp_confirmation VALUES(null,'$acctno','$otp_code','$mydata','Pending','$currenctdate')") or die(mysqli_error($link));
   
        if(!$update)
        {
            echo "<div class='alert bg-orange'>Unable to Transfer Fund.....Please try again later</div>";
        }
        else{
            $iuid = "";
            ($debitWAllet == "No" && ($selfServiceTransAuth == "On" || $otp_option == "Yes" || $otp_option == "Both") ? $sendSMS->instGeneralAlert($bozeki_password, $bozeki_url, $sysabb, $phone2, $sms, $bbranchid, $sms_refid, $sms_rate, $iuid, $imywallet_balance, $debitWallet) : ($debitWAllet == "Yes" && $sms_rate <= $iwallet_balance && ($selfServiceTransAuth == "On" || $otp_option == "Yes" || $otp_option == "Both") ? $sendSMS->instGeneralAlert($bozeki_password, $bozeki_url, $isenderid, $phone2, $sms, $bbranchid, $sms_refid, $sms_rate, $iuid, $imywallet_balance, $debitWallet) : ""));
            echo ($selfServiceTransAuth == "On" || $otp_option == "Yes" || $otp_option == "Both") ? "<div align='center'><img src='".$fetchsys_config['file_baseurl']."checkmark.gif'><p style='color: #38A1F3;'>Great! Otp has been sent to your mobile phone to complete this transaction!!</p></div>" : "<div align='center'><img src='".$fetchsys_config['file_baseurl']."checkmark.gif'><p style='color: #38A1F3;'>Transfer Request Received Successfully! Wait a moment to complete the transaction with your Pin</p></div>";
            echo '<meta http-equiv="refresh" content="2;url=transfer.php?tid='.$_SESSION['tid'].'&&acn='.$acctno.'&&mid=OTAw&&tab=tab_11&&'.$otpChecker.'">';
        }
        
	}
	
}
?>


<?php
if (isset($_POST['confirm']))
{
    include("../config/walletafrica_restfulapis_call.php");
    include("../cron/mygeneral_sms.php");
    
    $result1 = array();
    $myotp = $_POST['otp'];
    
    $verify_otp = mysqli_query($link, "SELECT * FROM otp_confirmation WHERE otp_code = '$myotp' AND userid = '$acctno' AND status = 'Pending'");
	$fetch_data = mysqli_fetch_array($verify_otp);
	$otpnum = mysqli_num_rows($verify_otp);
	
	if($otpnum == 0){
						        
	    echo "<div class='alert bg-orange'>Opps!...Invalid Credential!!</div>";
						        
	}else{
	    
	    $concat = $fetch_data['data'];
    
        $datetime = $fetch_data['datetime'];
                                
        $parameter = (explode('|',$concat));
        
        $tReference = $parameter[0];
        $amountWithNoCharges = preg_replace('/[^0-9.]/', '', number_format($parameter[1],0,'',''));
        $amountWithCharges = $parameter[2];
        $calcCharges = $amountWithCharges - $amountWithNoCharges;
          
        //Get my wallet balance after debiting
        $senderBalance = $bwallet_balance - $amountWithCharges;

        $transactionDateTime = date("Y-m-d H:i:s");
                
        //Customer Details
        $pan = $card_id;
        $cust_fname = $myfn;
        $cust_lname = $myln;
        $cust_email = $email2;
            	
        //Fetch Gateway
        $api_name =  "card_load";
        $search_restapi = mysqli_query($link, "SELECT * FROM atmcard_gateway_apis WHERE api_name = '$api_name' AND issuer_name = '$issurer'");
        $fetch_restapi = mysqli_fetch_object($search_restapi);
        $api_url = $fetch_restapi->api_url;
            	
        $client = new SoapClient($api_url);
    
        $param = array(
            'appId'=>$verveAppId,
            'appKey'=>$verveAppKey,
            'currencyCode'=>"566",
            'emailAddress'=>$cust_email,
            'firstName'=>$cust_fname,
            'lastName'=>$cust_lname,
            'mobileNr'=>$phone2,
            'amount'=>$amountWithNoCharges,
            'pan'=>$pan,
            'PaymentRef'=>$tReference
        );
            
        $response = $client->PostIswCardFund($param);
                
        $process = json_decode(json_encode($response), true);
                
        $responseCode = $process['PostIswCardFundResult']['responseCode']; //90000 OR 99
        $responseCodeGrouping = $process['PostIswCardFundResult']['responseCodeGrouping'];

        $api_name5 = "display_card_bal";
		$issurer5 = "VerveCard";
		$search_restapi5 = mysqli_query($link, "SELECT * FROM atmcard_gateway_apis WHERE api_name = '$api_name5' AND issuer_name = '$issurer5'");
		$fetch_restapi5 = mysqli_fetch_object($search_restapi5);
		$api_url5 = $fetch_restapi5->api_url;

		$client5 = new SoapClient($api_url5);

		$param5 = array(
			'AccountNo'=>$pan,
			'appId'=>$verveAppId,
			'appKey'=>$verveAppKey
		);
		  
		$response5 = $client5->GetIswPrepaidCardAccountBalance($param5);
				  
		$process5 = json_decode(json_encode($response5), true);
				  
		$statusCode5 = $process5['GetIswPrepaidCardAccountBalanceResult']['StatusCode'];
				  
		$availableBalance5 = $process5['GetIswPrepaidCardAccountBalanceResult']['JsonData'];
				  
		$decodeProcess5 = json_decode($availableBalance5, true);
			  
		$availbal = $decodeProcess5['availableBalance'] / 100;

		$tType = "Credit";
		$PAN_Masked = panNumberMasking($pan);
		$STAN = rand(000000,999999);
		$RRN = date("y").time();
		$TrxnAmount = $amountWithNoCharges;
		$CurrCode = $bbcurrency;
        $DateTime = date('m/d/Y').' '.(date(h) + 1).':'.date('i A');
        $email = $email2;
        $fname = $myfn;
        $lname = $myln;
        $account = $acctno;
        $isenderid = $bsender_id;
                
        $sms = "$isenderid>>>CR. Your Verve card with Pan Number: $PAN_Masked has been credited with $bbcurrency".number_format($amountWithNoCharges,2,'.',',').". Card Balance: $bbcurrency".number_format($availbal,2,'.',',').". ";
        $sms .= "Date ".$DateTime."";
                
        if($responseCode === "90000"){            
                
            $transferCode = $process['PostIswCardFundResult']['transferCode'];
            $pin = $process['PostIswCardFundResult']['pin'];
            $cardPan = $process['PostIswCardFundResult']['cardPan'];
            $cvv = $process['PostIswCardFundResult']['cvv'];
            $expiryDate = $process['PostIswCardFundResult']['expiryDate'];
            $currenctdate = date("Y-m-d H:i:s");
    
            //$message = ($card_id == "NULL") ? "Card Pin is: ".$pin : "";

            $sendSMS->smsWithNoCharges($isenderid, $phone2, $sms, $tReference, $acctno);
			$sendSMS->cardEmailNotifier($email, $tType, $RRN, $myfn, $myln, $PAN_Masked, $DateTime, $acctno, $CurrCode, $TrxnAmount, $availbal, $emailConfigStatus, $fetch_emailConfig);
            
            mysqli_query($link, "UPDATE borrowers SET wallet_balance = '$senderBalance' WHERE account = '$acctno'");
                    
            mysqli_query($link, "INSERT INTO wallet_history VALUE(null,'$bbranchid','$tReference','self','','$amountWithNoCharges','Debit','$bbcurrency','Topup-Prepaid_Card','Response: Prepaid Card was Topup with $bbcurrency.$amountWithNoCharges','successful','$currenctdate','$acctno','$senderBalance','')");
                    
            mysqli_query($link, "INSERT INTO wallet_history VALUE(null,'$bbranchid','$tReference','self','','$calcCharges','Debit','$bbcurrency','Stamp Duty','Response: Prepaid Card was Topup with $bbcurrency.$amountWithNoCharges','successful','$currenctdate','$acctno','$senderBalance','')");
                
            mysqli_query($link, "INSERT INTO cardTransaction VALUES(null,'$acctno','$myfn','$myln','$email2','$phone2','$pan','$PAN_Masked','$bsender_id, $bstate $bcountry','$DateTime','$STAN','$RRN','$TrxnAmount','$availbal','$CurrCode','$tType','$bbranchid','$bsbranchid','$acctno')");

            mysqli_query($link, "DELETE FROM otp_confirmation WHERE userid = '$acctno' AND otp_code = '$myotp' AND status = 'Pending'");
                        
            echo '<meta http-equiv="refresh" content="5;url=transfer.php?id='.$_SESSION['tid'].'&&acn='.$acctno.'&&mid=OTAw&&tab=tab_11">';
            echo '<br>';
            echo'<span class="itext" style="color: blue;">Prepaid Card Topup Successfully</span>';
                
        }
        else{
                
            /*mysqli_query($link, "UPDATE borrowers SET wallet_balance = '$senderBalance' WHERE account = '$acctno'");
                
            mysqli_query($link, "INSERT INTO wallet_history VALUE(null,'$bbranchid','$reference','self','$amountWithNoCharges','$bbcurrency','Topup-Prepaid_Card','Response: Prepaid Card was Topup with $bbcurrency.$amountWithNoCharges','successful','$currenctdate','$acctno','$senderBalance','')");
                
            mysqli_query($link, "INSERT INTO wallet_history VALUE(null,'$bbranchid','$reference','self','$calcCharges','$bbcurrency','Stamp Duty','Response: Prepaid Card was Topup with $bbcurrency.$amountWithNoCharges','successful','$currenctdate','$acctno','$senderBalance','')");

            mysqli_query($link, "INSERT INTO cardTransaction VALUES(null,'$acctno','$myfn','$myln','$email2','$phone2','$pan','$PAN_Masked','$bsender_id, $bstate $bcountry','$DateTime','$STAN','$RRN','$TrxnAmount','$availbal','$CurrCode','$tType','$bbranchid','$bsbranchid','$acctno')");
                
            mysqli_query($link, "UPDATE otp_confirmation SET status = 'batchCardDisbursement' WHERE userid = '$acctno' AND otp_code = '$myotp' AND status = 'Pending'");
                
            $debug = true;
            sendSms($isenderid,$phone2,$sms,$debug);
            include("../config/cardEmailNotifier.php");*/
                
            //echo '<meta http-equiv="refresh" content="5;url=transfer.php?id='.$_SESSION['tid'].'&&acn='.$acctno.'&&mid=OTAw&&tab=tab_11">';
            //echo '<br>';
            echo'<span class="itext" style="color: orange;">Transfer failed!...Try again later!!</span>';
            //echo'<span class="itext" style="color: orange;">Great! Your request has been received and it will be process shortly!!</span>';
                
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
                    <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Amount</label>
                    <div class="col-sm-6">
                        <input name="amount" type="text" class="form-control" placeholder="Enter Amount to Transfer" /required>
                    </div>
                    <label for="" class="col-sm-3 control-label"></label>
                </div>

            </div>
            
            <div class="form-group" align="right">
                <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;"></label>
                <div class="col-sm-6">
                    <button name="Vervecard_transfer" type="submit" onclick="var e=this;setTimeout(function(){e.disabled=true;},0);return true;" class="btn bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?> btn-flat" <?php echo ($card_id != "NULL") ? "" : "disabled"; ?>><i class="fa fa-upload">&nbsp;Transfer Fund</i></button>
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
  
  
  
      <!-- /.tab-pane -->
	
</div>
</div>
</div>
</div>
</div>