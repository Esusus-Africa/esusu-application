<div class="row">
	      <section class="content">
        
       <div class="box box-default">
           <div class="box-body">
             <div class="table-responsive"> 
            <div class="box-body">
            <a href="mywallet.php?id=<?php echo $_SESSION['tid']; ?>&&mid=NDA0&&tab=tab_1"><button type="button" class="btn btn-flat bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>"><i class="fa fa-mail-reply-all"></i>&nbsp;Back</button></a> 
<button type="button" class="btn btn-flat bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>" align="left" disabled>&nbsp;<b>Super Wallet:</b>&nbsp;
<strong class="alert bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">
<?php
echo $vcurrency.number_format($vwallet_balance,2,'.',',');

$my_systemset = mysqli_query($link, "SELECT * FROM systemset");
$my_row1 = mysqli_fetch_object($my_systemset);
$vat_rate = $my_row1->vat_rate;
$transfer_charges = $my_row1->transfer_charges;
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
 <!--<a href="wallet-towallet.php?id=<?php echo $_SESSION['tid']; ?>&&mid=NDA0"><button type="button" class="btn btn-flat bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>"><i class="fa fa-exchange"></i>&nbsp;Wallet-to-Wallet</button> </a>-->

<hr>
<div class="slideshow-container">
<div class="alert bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>" style="font-size:17px;" align="center">
    <p>
        <?php
        $search_memset = mysqli_query($link, "SELECT * FROM member_settings WHERE companyid = '$vcreated_by'");
        $fetch_memset = mysqli_fetch_array($search_memset);
        $votp_option = $fetch_memset['otp_option'];
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
             <li <?php echo ($_GET['tab'] == 'tab_1') ? "class='active'" : ''; ?>><a href="transfer_fund.php?id=<?php echo $_SESSION['tid']; ?>&&mid=NDA0&&tab=tab_1">African Bank Account Transfers</a></li>

             <li <?php echo ($_GET['tab'] == 'tab_2') ? "class='active'" : ''; ?>><a href="transfer_fund.php?id=<?php echo $_SESSION['tid']; ?>&&mid=NDA0&&tab=tab_2">International Transfers</a></li>

              <li <?php echo ($_GET['tab'] == 'tab_3') ? "class='active'" : ''; ?>><a href="transfer_fund.php?id=<?php echo $_SESSION['tid']; ?>&&mid=NDA0&&tab=tab_3">Mpesa Mobile Money Transfer</a></li>

              <li <?php echo ($_GET['tab'] == 'tab_4') ? "class='active'" : ''; ?>><a href="transfer_fund.php?id=<?php echo $_SESSION['tid']; ?>&&mid=NDA0&&tab=tab_4">Ghana Mobile Money Transfer</a></li>

              <li <?php echo ($_GET['tab'] == 'tab_5') ? "class='active'" : ''; ?>><a href="transfer_fund.php?id=<?php echo $_SESSION['tid']; ?>&&mid=NDA0&&tab=tab_5">Ugandan Mobile Money Transfer</a></li>

              <!--<li <?php //echo ($_GET['tab'] == 'tab_6') ? "class='active'" : ''; ?>><a href="transfer_fund.php?id=<?php //echo $_SESSION['tid']; ?>&&mid=NDA0&&tab=tab_6">Transfer to a saved beneficiary</a></li>-->
              
              <!--<li <?php //echo ($_GET['tab'] == 'tab_7') ? "class='active'" : ''; ?>><a href="transfer_fund.php?id=<?php //echo $_SESSION['tid']; ?>&&mid=NDA0&&tab=tab_7">Bulk Transfer</a></li>-->
              
              <li <?php echo ($_GET['tab'] == 'tab_8') ? "class='active'" : ''; ?>><a href="transfer_fund.php?id=<?php echo $_SESSION['tid']; ?>&&mid=NDA0&&tab=tab_8">Check Exchange Rate</a></li>
              
              <!--<li <?php //echo ($_GET['tab'] == 'tab_9') ? "class='active'" : ''; ?>><a href="transfer_fund.php?id=<?php //echo $_SESSION['tid']; ?>&&mid=NDA0&&tab=tab_9">Transfer to Payvice Wallet</a></li>-->
              
              <!--<li <?php //echo ($_GET['tab'] == 'tab_10') ? "class='active'" : ''; ?>><a href="transfer_fund.php?id=<?php //echo $_SESSION['tid']; ?>&&mid=NDA0&&tab=tab_10">Credit Transfer Balance</a></li>-->
              </ul>

             <div class="tab-content">

<?php

require_once "../config/nipBankTransfer_class.php";

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
    $reference =  date("yd").time();
    $account_number =  mysqli_real_escape_string($link, $_POST['account_number']);
    $bank_code =  mysqli_real_escape_string($link, $_POST['bank_code']);
	$b_name = mysqli_real_escape_string($link, $_POST['b_name']);
	$amount = preg_replace('/[^0-9.]/', '', mysqli_real_escape_string($link, $_POST['amt_totransfer']));
    $afnarration =  mysqli_real_escape_string($link, $_POST['reasons']);
    $SessionID = ($vnip_route == "SterlingBank") ? mysqli_real_escape_string($link, $_POST['stSessionId']) : "";
    $NEStatus = ($vnip_route == "SterlingBank") ? mysqli_real_escape_string($link, $_POST['NEStatus']) : "";
    $senderAcctNo = ($vnip_route == "SterlingBank") ? mysqli_real_escape_string($link, $_POST['FromAcct']) : "";
    $phone = $vo_phone;
	$currenctdate = date("Y-m-d H:i:s");
	
	$query = mysqli_query($link, "SELECT * FROM systemset") or die (mysqli_error($link));
    $r = mysqli_fetch_object($query);

    $transfer_charges = ($bank_transferCharges == "") ? $r->transfer_charges : $bank_transferCharges;
    $sysabb = $mvsenderid;
    
    $otp_code = ($votp_option == "Yes" || $votp_option == "Both") ? substr((uniqid(rand(),1)),3,6) : $myvepin;
    
    $otpChecker = ($votp_option == "Yes" || $votp_option == "Both") ? "otp" : "pin";
								
	$sms = "$sysabb>>>Dear $vuname! Your One Time Password is $otp_code";
                            	
    $sms_rate = $r->fax;
    $vmywallet_balance = $vwallet_balance - $sms_rate;
    $sms_refid = "EA-smsCharges-".time();
	
	//New AMount + Charges
    $newAmount = $amount + $transfer_charges;
	
	//Data Parser (array size = 6)
    $mydata = $reference."|".$bank_code."|".$account_number."|".$b_name."|".$amount."|".$newAmount."|".$afnarration."|".$SessionID."|".$NEStatus."|".$senderAcctNo."|".$vtransfer_balance;
    
    $key = base64_encode($mydata);
    
	if($vnip_route == ""){

        echo "<div class='alert bg-orange'>Sorry! Service not active, please contact the administrator to activate this features!!</div>";

    }
    elseif($vactive_status == "Suspended"){
        
        echo "<div class='alert bg-orange'>Oops! You are unable to make transfer at the moment!!</div>";
        
    }
    elseif($amount <= 0){

        echo "<div class='alert bg-orange'>Sorry! Invalid amount entered!!</div>";

    }
	elseif(($vtransfer_balance < $newAmount && ($votp_option == "Yes" || $votp_option == "Both")) || ($vtransfer_balance < $newAmount && $votp_option == "No")){
	    
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
    elseif($vvirtual_acctno === "$account_number" || $vuid === "$account_number"){
    
        mysqli_query($link, "UPDATE user SET status = 'Suspended' WHERE id = '$vuid'");
        mysqli_query($link, "INSERT INTO wallet_case VALUES(null,'$vcreated_by','','System','$vuid','Suspended','Frudulent Act Detected','$currenctdate')");
        
        echo "<div class='alert bg-orange'>Oops! Request Failed!!</div>";
        
    }
	else{
	    
	    $update = mysqli_query($link,"INSERT INTO otp_confirmation VALUES(null,'$vuid','$otp_code','$mydata','Pending','$currenctdate')")or die(mysqli_error($link));
        
        ($iotp_option == "No" ? "" : (($iotp_option == "Yes" || $iotp_option == "Both") ? $sendSMS->vendorGeneralAlert($sysabb, $vo_phone, $sms, $vcreated_by, $sms_refid, $sms_rate, $vuid, $vmywallet_balance, $debitWallet, $vendorid) : (($iotp_option == "Yes" || $iotp_option == "Both") && $sms_rate <= $vwallet_balance ? $sendSMS->vendorGeneralAlert($sysabb, $vo_phone, $sms, $vcreated_by, $sms_refid, $sms_rate, $vuid, $vmywallet_balance, $debitWallet, $vendorid) : "")));
        
        echo ($votp_option == "Yes" || $votp_option == "Both") ? "<div align='center'><img src='../image/checkmark.gif'><p style='color: #38A1F3;'>Great! Otp has been sent to your mobile phone to complete this transaction!!</p></div>" : "<div align='center'><img src='../image/checkmark.gif'><p style='color: #38A1F3;'>Transfer Request Received Successfully! Wait a moment to complete the transaction with your Pin</p></div>";
        echo '<meta http-equiv="refresh" content="2;url=transfer_fund.php?id='.$_SESSION['tid'].'&&mid=NDA0&&tab=tab_1&&token='.$key.'&&'.$otpChecker.'">';
        
	}
	
}
?>



<?php
if (isset($_POST['confirm']))
{
    $myotp = $_POST['otp'];
    $token = base64_decode($_GET['token']);
    
    $verify_otp = mysqli_query($link, "SELECT * FROM otp_confirmation WHERE otp_code = '$myotp' AND userid = '$vuid' AND status = 'Pending' AND data = '$token'");
	$fetch_data = mysqli_fetch_array($verify_otp);
	$otpnum = mysqli_num_rows($verify_otp);
	
	if($otpnum == 0){
						        
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
        $defaultCustomerBal = $parameter[10];
        $currency = $vcurrency;
        $originatorName = $accountName;
	    $tramsferid = substr((uniqid(rand(),1)),3,6);
	    //$totalWalletBalance = $bwallet_balance - $amountWithCharges;
        $calcCharges = $amountWithCharges - $amountWithNoCharges;
        
        //Get my wallet balance after debiting
        $senderBalance = $vtransfer_balance - $amountWithCharges;
        $senderEmail = $vo_email;
        $senderName = $vc_name;
        $merchantName = $mvcname;

        if($vnip_route == "Wallet Africa"){

            //Fetch Bank Name
            $search_bankname = mysqli_query($link, "SELECT * FROM bank_list WHERE bankcode = '$recipientBankCode'");
            $fetch_bankname = mysqli_fetch_array($search_bankname);
            $mybank_name = $fetch_bankname['bankname'];

            $transactionDateTime = date("Y-m-d h:i:s");
            //8 rows [0 - 8]
            $dataToProcess = $tReference."|".$amountWithNoCharges."|".$narration."|".$accountName."|".$mybank_name."|".$senderName."|".$recipientAcctNo."|".$recipientBankCode."|".$vtransfer_balance;
            //insert txt waiting list
            $mytxtstatus = 'Pending';
            ($amountWithCharges > $vtransfer_balance) ? "" : mysqli_query($link, "INSERT INTO api_txtwaitinglist VALUE(null,'$vuid','$tReference','$dataToProcess','$mytxtstatus','$transactionDateTime')");
            
            //Debit Customer Wallet
            ($amountWithCharges > $vtransfer_balance) ? "" : mysqli_query($link, "UPDATE user SET transfer_balance = '$senderBalance' WHERE id = '$vuid' AND created_by = '$vcreated_by'");

            //Fetch endpoint
            $search_restapi1 = mysqli_query($link, "SELECT * FROM restful_apisetup WHERE api_name = 'walletafrica_transfer'");
            $fetch_restapi1 = mysqli_fetch_object($search_restapi1);
            $client = $fetch_restapi1->api_url;

            ($amountWithCharges > $vtransfer_balance) ? "" : $result = $new->walletAfricaNIPBankTransfer($walletafrica_skey,$recipientBankCode,$recipientAcctNo,$accountName,$tReference,$amountWithNoCharges,$client);
            
            ($amountWithCharges > $vtransfer_balance) ? "" : $decodePro = json_decode($result, true);

            if($vtransfer_balance >= $amountWithCharges && ($decodePro['ResponseCode'] == "100" || $decodePro['ResponseCode'] == "200" || $result['ResponseCode'] == "100" || $result['ResponseCode'] == "200")){
                
                $gatewayResponse = $decodePro['Message'];
                $recipient = $recipientAcctNo.', '.$accountName.', '.$mybank_name;
                
                //UPDATE WAITING TXT
                mysqli_query($link, "UPDATE api_txtwaitinglist SET status = '$result' WHERE userid = '$vuid' AND refid = '$tReference' AND status = '$mytxtstatus'");

                //Log successful transaction history
                mysqli_query($link, "INSERT INTO wallet_history VALUE(null,'$vcreated_by','$tReference','$recipient','','$amountWithNoCharges','Debit','$vcurrency','BANK_TRANSFER','Transfer to $recipient','successful','$transactionDateTime','$vuid','$senderBalance','$vnip_route')");
                mysqli_query($link, "INSERT INTO wallet_history VALUE(null,'$vcreated_by','$tReference','$recipient','','$calcCharges','Debit','$vcurrency','Charges','Transfer to $recipient, Narration: $narration','successful','$transactionDateTime','$vuid','$senderBalance','$vnip_route')");

                $sendSMS->bankTransferEmailNotifier($senderEmail, $tReference, $senderName, $transactionDateTime, $accountName, $recipientAcctNo, $mybank_name, $merchantName, $vcurrency, $amountWithNoCharges, $senderBalance, $vemailConfigStatus, $vfetch_emailConfig);

                mysqli_query($link, "DELETE FROM otp_confirmation WHERE userid = '$vuid' AND otp_code = '$myotp' AND status = 'Pending'");
                    
                echo "<div class='alert bg-blue'>Transaction Successful! Current Transfer Wallet Balance is: <b style='color: white;'>".$vcurrency.number_format($senderBalance,2,'.',',')."</b></div>";
        	    echo '<meta http-equiv="refresh" content="10;url=transfer_fund.php?id='.$_SESSION['tid'].'&&mid=NDA0&&tab=tab_1">';
                    
            }
            else{

                $myWaitingList = mysqli_query($link, "SELECT * FROM api_txtwaitinglist WHERE userid = '$vuid' AND refid = '$tReference' AND status = '$mytxtstatus'");
                $fetchMyWaitingList = mysqli_fetch_array($myWaitingList);
                $myWaitingData = $fetchMyWaitingList['mydata'];

                $myParameter = (explode('|',$myWaitingData));
                $defaultBalance = $myParameter[8];
                
                //Reverse Customer Wallet
                mysqli_query($link, "UPDATE user SET transfer_balance = '$defaultBalance' WHERE id = '$vuid' AND created_by = '$vcreated_by'");
            
                //UPDATE WAITING TXT
                mysqli_query($link, "UPDATE api_txtwaitinglist SET status = '$result' WHERE userid = '$vuid' AND refid = '$tReference' AND status = '$mytxtstatus'");

                mysqli_query($link, "DELETE FROM otp_confirmation WHERE userid = '$vuid' AND otp_code = '$myotp' AND status = 'Pending'");
                    
                echo "<div class='alert bg-orange'>Opps!...Unable to conclude transaction, Please Try again later</div>";
                echo '<meta http-equiv="refresh" content="5;url=transfer_fund.php?tid='.$_SESSION['tid'].'&&mid=NDA0&&tab=tab_1">';
                
            }

        }
        elseif($vnip_route == "ProvidusBank"){

            //Fetch Bank Name
            $search_bankname = mysqli_query($link, "SELECT * FROM bank_list2 WHERE bankcode = '$recipientBankCode'");
            $fetch_bankname = mysqli_fetch_array($search_bankname);
            $mybank_name = $fetch_bankname['bankname'];
            
            $transactionDateTime = date("Y-m-d h:i:s");
            //8 rows [0 - 8]
            $dataToProcess = $tReference."|".$amountWithNoCharges."|".$narration."|".$accountName."|".$mybank_name."|".$senderName."|".$recipientAcctNo."|".$recipientBankCode."|".$vtransfer_balance;
            //insert txt waiting list
            $mytxtstatus = 'Pending';
            ($amountWithCharges > $vtransfer_balance) ? "" : mysqli_query($link, "INSERT INTO api_txtwaitinglist VALUE(null,'$vuid','$tReference','$dataToProcess','$mytxtstatus','$transactionDateTime')");

            //Debit Customer Wallet
            ($amountWithCharges > $vtransfer_balance) ? "" : mysqli_query($link, "UPDATE user SET transfer_balance = '$senderBalance' WHERE id = '$vuid' AND created_by = '$vcreated_by'");

            //fetch endpoint
            $search_restapi1 = mysqli_query($link, "SELECT * FROM restful_apisetup WHERE api_name = 'providus_endpoint'");
            $fetch_restapi1 = mysqli_fetch_object($search_restapi1);
            $api_url = $fetch_restapi1->api_url;

            ($amountWithCharges > $vtransfer_balance) ? "" : $client = new SoapClient($api_url);

            ($amountWithCharges > $vtransfer_balance) ? "" : $result = $new->providusNIPBankTransfer($providusUName,$providusPass,$amountWithNoCharges,$currency,$narration,$tReference,$recipientAcctNo,$recipientBankCode,$accountName,$originatorName,$client);

            if($result['responseCode'] == "00" && $vtransfer_balance >= $amountWithCharges){

                $gatewayResponse = $result['responseMessage'];
                $recipient = $recipientAcctNo.', '.$accountName.', '.$mybank_name;

                //UPDATE WAITING TXT
                mysqli_query($link, "UPDATE api_txtwaitinglist SET status = '$result' WHERE userid = '$vuid' AND refid = '$tReference' AND status = '$mytxtstatus'");
                    
                //Log successful transaction history
                mysqli_query($link, "INSERT INTO wallet_history VALUE(null,'$vcreated_by','$tReference','$recipient','','$amountWithNoCharges','Debit','$vcurrency','BANK_TRANSFER','Transfer to $recipient','successful','$transactionDateTime','$vuid','$senderBalance','$vnip_route')");
                mysqli_query($link, "INSERT INTO wallet_history VALUE(null,'$vcreated_by','$tReference','$recipient','','$calcCharges','Debit','$vcurrency','Charges','Transfer to $recipient, Narration: $narration','successful','$transactionDateTime','$vuid','$senderBalance','$vnip_route')");

                $sendSMS->bankTransferEmailNotifier($senderEmail, $tReference, $senderName, $transactionDateTime, $accountName, $recipientAcctNo, $mybank_name, $merchantName, $vcurrency, $amountWithNoCharges, $senderBalance, $vemailConfigStatus, $vfetch_emailConfig);

                mysqli_query($link, "DELETE FROM otp_confirmation WHERE userid = '$vuid' AND otp_code = '$myotp' AND status = 'Pending'");
                    
                echo "<div class='alert bg-blue'>Transaction Successful! Current Transfer Wallet Balance is: <b style='color: white;'>".$vcurrency.number_format($senderBalance,2,'.',',')."</b></div>";
        	    echo '<meta http-equiv="refresh" content="10;url=transfer_fund.php?id='.$_SESSION['tid'].'&&mid=NDA0&&tab=tab_1">';

            }
            else{

                $myWaitingList = mysqli_query($link, "SELECT * FROM api_txtwaitinglist WHERE userid = '$vuid' AND refid = '$tReference' AND status = '$mytxtstatus'");
                $fetchMyWaitingList = mysqli_fetch_array($myWaitingList);
                $myWaitingData = $fetchMyWaitingList['mydata'];

                $myParameter = (explode('|',$myWaitingData));
                $defaultBalance = $myParameter[8];
                
                //Reverse Customer Wallet
                mysqli_query($link, "UPDATE user SET transfer_balance = '$defaultBalance' WHERE id = '$vuid' AND created_by = '$vcreated_by'");

                //UPDATE WAITING TXT
                mysqli_query($link, "UPDATE api_txtwaitinglist SET status = '$result' WHERE userid = '$vuid' AND refid = '$tReference' AND status = '$mytxtstatus'");

                mysqli_query($link, "DELETE FROM otp_confirmation WHERE userid = '$vuid' AND otp_code = '$myotp' AND status = 'Pending'");

                echo "<div class='alert bg-orange'>Opps!...Unable to conclude transaction, Please Try again later</div>";
                echo '<meta http-equiv="refresh" content="5;url=transfer_fund.php?tid='.$_SESSION['tid'].'&&mid=NDA0&&tab=tab_1">';

            }

        }elseif($vnip_route == "SterlingBank"){

            //Fetch Bank Name
            $search_bankname = mysqli_query($link, "SELECT * FROM bank_list3 WHERE bankcode = '$recipientBankCode'");
            $fetch_bankname = mysqli_fetch_array($search_bankname);
            $mybank_name = $fetch_bankname['bankname'];

            $transactionDateTime = date("Y-m-d h:i:s");
            //9 rows [0 - 9]
            $dataToProcess = $tramsferid."|".$SessionID."|".$senderAcctNo."|".$recipientAcctNo."|".$amountWithNoCharges."|".$recipientBankCode."|".$NEStatus."|".$accountName."|".$tReference."|".$vtransfer_balance;
            //insert txt waiting list
            $mytxtstatus = 'Pending';
            ($amountWithCharges > $vtransfer_balance) ? "" : mysqli_query($link, "INSERT INTO api_txtwaitinglist VALUE(null,'$vuid','$tReference','$dataToProcess','$mytxtstatus','$transactionDateTime')");

            //Debit Customer Wallet
            ($amountWithCharges > $vtransfer_balance) ? "" : mysqli_query($link, "UPDATE user SET transfer_balance = '$senderBalance' WHERE id = '$vuid' AND created_by = '$vcreated_by'");

            //fetch endpoint
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
 
            ($amountWithCharges > $vtransfer_balance) ? "" : $process = $new->sterlingNIPBankTransfer($data_to_send_server,$client);
 
            ($amountWithCharges > $vtransfer_balance) ? "" : $processReturn = $process['data'];

            if($processReturn['status'] == "00" && $vtransfer_balance >= $amountWithCharges){

                $gatewayResponse = $processReturn['ResponseText'];
                $recipient = $recipientAcctNo.', '.$accountName.', '.$mybank_name;

                //UPDATE WAITING TXT
                mysqli_query($link, "UPDATE api_txtwaitinglist SET status = '$processReturn' WHERE userid = '$vuid' AND refid = '$tReference' AND status = '$mytxtstatus'");
                    
                //Log successful transaction history
                mysqli_query($link, "INSERT INTO wallet_history VALUE(null,'$vcreated_by','$tReference','$recipient','','$amountWithNoCharges','Debit','$vcurrency','BANK_TRANSFER','Transfer to $recipient','successful','$transactionDateTime','$vuid','$senderBalance','$vnip_route')");
                mysqli_query($link, "INSERT INTO wallet_history VALUE(null,'$vcreated_by','$tReference','$recipient','','$calcCharges','Debit','$vcurrency','Charges','Transfer to $recipient, Narration: $narration','successful','$transactionDateTime','$vuid','$senderBalance','$vnip_route')");

                $sendSMS->bankTransferEmailNotifier($senderEmail, $tReference, $senderName, $transactionDateTime, $accountName, $recipientAcctNo, $mybank_name, $merchantName, $vcurrency, $amountWithNoCharges, $senderBalance, $vemailConfigStatus, $vfetch_emailConfig);

                mysqli_query($link, "DELETE FROM otp_confirmation WHERE userid = '$vuid' AND otp_code = '$myotp' AND status = 'Pending'");
                    
                echo "<div class='alert bg-blue'>Transaction Successful! Current Transfer Wallet Balance is: <b style='color: white;'>".$vcurrency.number_format($senderBalance,2,'.',',')."</b></div>";
                echo '<meta http-equiv="refresh" content="10;url=transfer_fund.php?id='.$_SESSION['tid'].'&&mid=NDA0&&tab=tab_1">';

            }
            else{

                $myWaitingList = mysqli_query($link, "SELECT * FROM api_txtwaitinglist WHERE userid = '$vuid' AND refid = '$tReference' AND status = '$mytxtstatus'");
                $fetchMyWaitingList = mysqli_fetch_array($myWaitingList);
                $myWaitingData = $fetchMyWaitingList['mydata'];

                $myParameter = (explode('|',$myWaitingData));
                $defaultBalance = $myParameter[9];
                
                //Reverse Customer Wallet
                mysqli_query($link, "UPDATE user SET transfer_balance = '$defaultBalance' WHERE id = '$vuid' AND created_by = '$vcreated_by'");

                //UPDATE WAITING TXT
                mysqli_query($link, "UPDATE api_txtwaitinglist SET status = '$processReturn' WHERE userid = '$vuid' AND refid = '$tReference' AND status = '$mytxtstatus'");

                mysqli_query($link, "DELETE FROM otp_confirmation WHERE userid = '$vuid' AND otp_code = '$myotp' AND status = 'Pending'");
                 
                echo "<div class='alert bg-orange'>Opps!...Unable to conclude transaction, Please Try again later</div>";
                echo '<meta http-equiv="refresh" content="5;url=transfer_fund.php?tid='.$_SESSION['tid'].'&&mid=NDA0&&tab=tab_1">';

            }

        }
        elseif($vnip_route == "RubiesBank"){

            //Fetch Bank Name
            $search_bankname = mysqli_query($link, "SELECT * FROM bank_list4 WHERE bankcode = '$recipientBankCode'");
            $fetch_bankname = mysqli_fetch_array($search_bankname);
            $mybank_name = $fetch_bankname['bankname'];
            $draccountname = $vname;

            $transactionDateTime = date("Y-m-d h:i:s");
            //8 rows [0 - 8]
            $dataToProcess = $tReference."|".$amountWithNoCharges."|".$narration."|".$accountName."|".$mybank_name."|".$draccountname."|".$recipientAcctNo."|".$recipientBankCode."|".$vtransfer_balance;
            //insert txt waiting list
            $mytxtstatus = 'Pending';
            ($amountWithCharges > $vtransfer_balance) ? "" : mysqli_query($link, "INSERT INTO api_txtwaitinglist VALUE(null,'$vuid','$tReference','$dataToProcess','$mytxtstatus','$transactionDateTime')");
            
            //Debit Customer Wallet
            ($amountWithCharges > $vtransfer_balance) ? "" : mysqli_query($link, "UPDATE user SET transfer_balance = '$senderBalance' WHERE id = '$vuid' AND created_by = '$vcreated_by'");

            ($amountWithCharges > $vtransfer_balance) ? "" : $result = $new->rubiesNIPBankTransfer($tReference,$amountWithNoCharges,$narration,$accountName,$recipientBankCode,$recipientAcctNo,$rubbiesSecKey,$link,$draccountname);

            ($amountWithCharges > $vtransfer_balance) ? "" : $rubbies_generate = json_decode($result, true);

            if($rubbies_generate['responsecode'] == "00" && $rubbies_generate['nibsscode'] == "00" && $vtransfer_balance >= $amountWithCharges){

                $gatewayResponse = $rubbies_generate['nibssresponsemessage'];
                $recipient = $recipientAcctNo.', '.$accountName.', '.$mybank_name;

                //UPDATE WAITING TXT
                mysqli_query($link, "UPDATE api_txtwaitinglist SET status = '$result' WHERE userid = '$vuid' AND refid = '$tReference' AND status = '$mytxtstatus'");
                
                //Log successful transaction history
                mysqli_query($link, "INSERT INTO wallet_history VALUE(null,'$vcreated_by','$tReference','$recipient','','$amountWithNoCharges','Debit','$vcurrency','BANK_TRANSFER','Transfer to $recipient','successful','$transactionDateTime','$vuid','$senderBalance','$vnip_route')");
                mysqli_query($link, "INSERT INTO wallet_history VALUE(null,'$vcreated_by','$tReference','$recipient','','$calcCharges','Debit','$vcurrency','Charges','Transfer to $recipient, Narration: $narration','successful','$transactionDateTime','$vuid','$senderBalance','$vnip_route')");

                $sendSMS->bankTransferEmailNotifier($senderEmail, $tReference, $senderName, $transactionDateTime, $accountName, $recipientAcctNo, $mybank_name, $merchantName, $vcurrency, $amountWithNoCharges, $senderBalance, $vemailConfigStatus, $vfetch_emailConfig);

                mysqli_query($link, "DELETE FROM otp_confirmation WHERE userid = '$vuid' AND otp_code = '$myotp' AND status = 'Pending'");
                    
                echo "<div class='alert bg-blue'>Transaction Successful! Current Transfer Wallet Balance is: <b style='color: white;'>".$vcurrency.number_format($senderBalance,2,'.',',')."</b></div>";
        	    echo '<meta http-equiv="refresh" content="10;url=transfer_fund.php?id='.$_SESSION['tid'].'&&mid=NDA0&&tab=tab_1">';

            }
            elseif($rubbies_generate['responsecode'] == "-1" && $vtransfer_balance >= $amountWithCharges){

                $gatewayResponse = $rubbies_generate['nibssresponsemessage'];
                $recipient = $recipientAcctNo.', '.$accountName.', '.$mybank_name;

                //UPDATE WAITING TXT
                mysqli_query($link, "UPDATE api_txtwaitinglist SET status = '$result' WHERE userid = '$vuid' AND refid = '$tReference' AND status = '$mytxtstatus'");

                //Log processing transaction history
                mysqli_query($link, "INSERT INTO wallet_history VALUE(null,'$vcreated_by','$tReference','$recipient','','$amountWithNoCharges','Debit','$vcurrency','BANK_TRANSFER','Transfer to $recipient','Processing','$transactionDateTime','$vuid','$senderBalance','$vnip_route')");
                mysqli_query($link, "INSERT INTO wallet_history VALUE(null,'$vcreated_by','$tReference','$recipient','','$calcCharges','Debit','$vcurrency','Charges','Transfer to $recipient, Narration: $narration','Processing','$transactionDateTime','$vuid','$senderBalance','$vnip_route')");

                $sendSMS->bankTransferEmailNotifier($senderEmail, $tReference, $senderName, $transactionDateTime, $accountName, $recipientAcctNo, $mybank_name, $merchantName, $vcurrency, $amountWithNoCharges, $senderBalance, $vemailConfigStatus, $vfetch_emailConfig);

                mysqli_query($link, "DELETE FROM otp_confirmation WHERE userid = '$vuid' AND otp_code = '$myotp' AND status = 'Pending'");
                    
                echo "<div class='alert bg-blue'>Transaction in progress, Please do not retry until you confirmed failed! Click here to <a href='mywallet.php?id=".$_SESSION['tid']."&&mid=NDA0&&tab=tab_1'>Check Status</a>!!</div>";
                echo '<meta http-equiv="refresh" content="10;url=transfer_fund.php?id='.$_SESSION['tid'].'&&mid=NDA0&&tab=tab_1">';

            }
            else{

                $myWaitingList = mysqli_query($link, "SELECT * FROM api_txtwaitinglist WHERE userid = '$vuid' AND refid = '$tReference' AND status = '$mytxtstatus'");
                $fetchMyWaitingList = mysqli_fetch_array($myWaitingList);
                $myWaitingData = $fetchMyWaitingList['mydata'];

                $myParameter = (explode('|',$myWaitingData));
                $defaultBalance = $myParameter[8];
                
                //Reverse to Customer Wallet
                mysqli_query($link, "UPDATE user SET transfer_balance = '$defaultBalance' WHERE id = '$vuid' AND created_by = '$vcreated_by'");

                //UPDATE WAITING TXT
                mysqli_query($link, "UPDATE api_txtwaitinglist SET status = '$result' WHERE userid = '$vuid' AND refid = '$tReference' AND status = '$mytxtstatus'");

                mysqli_query($link, "DELETE FROM otp_confirmation WHERE userid = '$vuid' AND otp_code = '$myotp' AND status = 'Pending'");
                
                echo "<div class='alert bg-orange'>Opps!...Unable to conclude transaction, Please Try again later</div>";
                echo '<meta http-equiv="refresh" content="10;url=transfer_fund.php?tid='.$_SESSION['tid'].'&&mid=NDA0&&tab=tab_1">';

            }

        }
        elseif($vnip_route == "PrimeAirtime"){

            //Fetch Bank Name
            $search_bankname = mysqli_query($link, "SELECT * FROM bank_list WHERE bankcode = '$recipientBankCode'");
            $fetch_bankname = mysqli_fetch_array($search_bankname);
            $mybank_name = $fetch_bankname['bankname'];

            $transactionDateTime = date("Y-m-d h:i:s");
            //8 rows [0 - 8]
            $dataToProcess = $tReference."|".$amountWithNoCharges."|".$narration."|".$accountName."|".$mybank_name."|".$senderName."|".$recipientAcctNo."|".$recipientBankCode."|".$vtransfer_balance;
            //insert txt waiting list
            $mytxtstatus = 'Pending';
            ($amountWithCharges > $vtransfer_balance) ? "" : mysqli_query($link, "INSERT INTO api_txtwaitinglist VALUE(null,'$vuid','$tReference','$dataToProcess','$mytxtstatus','$transactionDateTime')");
            
            //Debit Customer Wallet
            ($amountWithCharges > $vtransfer_balance) ? "" : mysqli_query($link, "UPDATE user SET transfer_balance = '$senderBalance' WHERE id = '$vuid' AND created_by = '$vcreated_by'");

            ($amountWithCharges > $vtransfer_balance) ? "" : $result = $new->primeAirtimeFT($link,$accessToken,$recipientBankCode,$recipientAcctNo,$amountWithNoCharges,$narration,$tReference);
            
            ($amountWithCharges > $vtransfer_balance) ? "" : $decodePro = json_decode($result, true);

            if($vtransfer_balance >= $amountWithCharges && ($decodePro['status'] == "201" || $decodePro['status'] == "200")){
                
                $gatewayResponse = $decodePro['message'];
                $recipient = $recipientAcctNo.', '.$accountName.', '.$mybank_name;
                
                //UPDATE WAITING TXT
                mysqli_query($link, "UPDATE api_txtwaitinglist SET status = '$result' WHERE userid = '$vuid' AND refid = '$tReference' AND status = '$mytxtstatus'");

                //Log successful transaction history
                mysqli_query($link, "INSERT INTO wallet_history VALUE(null,'$vcreated_by','$tReference','$recipient','','$amountWithNoCharges','Debit','$vcurrency','BANK_TRANSFER','Transfer to $recipient','successful','$transactionDateTime','$vuid','$senderBalance','$vnip_route')");
                mysqli_query($link, "INSERT INTO wallet_history VALUE(null,'$vcreated_by','$tReference','$recipient','','$calcCharges','Debit','$vcurrency','Charges','Transfer to $recipient, Narration: $narration','successful','$transactionDateTime','$vuid','$senderBalance','$vnip_route')");

                $sendSMS->bankTransferEmailNotifier($senderEmail, $tReference, $senderName, $transactionDateTime, $accountName, $recipientAcctNo, $mybank_name, $merchantName, $vcurrency, $amountWithNoCharges, $senderBalance, $vemailConfigStatus, $vfetch_emailConfig);

                mysqli_query($link, "DELETE FROM otp_confirmation WHERE userid = '$vuid' AND otp_code = '$myotp' AND status = 'Pending'");
                    
                echo "<div class='alert bg-blue'>Transaction Successful! Current Transfer Wallet Balance is: <b style='color: white;'>".$vcurrency.number_format($senderBalance,2,'.',',')."</b></div>";
        	    echo '<meta http-equiv="refresh" content="10;url=transfer_fund.php?id='.$_SESSION['tid'].'&&mid=NDA0&&tab=tab_1">';
                    
            }
            else{

                $myWaitingList = mysqli_query($link, "SELECT * FROM api_txtwaitinglist WHERE userid = '$vuid' AND refid = '$tReference' AND status = '$mytxtstatus'");
                $fetchMyWaitingList = mysqli_fetch_array($myWaitingList);
                $myWaitingData = $fetchMyWaitingList['mydata'];

                $myParameter = (explode('|',$myWaitingData));
                $defaultBalance = $myParameter[8];
                
                //Reverse Customer Wallet
                mysqli_query($link, "UPDATE user SET transfer_balance = '$defaultBalance' WHERE id = '$vuid' AND created_by = '$vcreated_by'");
            
                //UPDATE WAITING TXT
                mysqli_query($link, "UPDATE api_txtwaitinglist SET status = '$result' WHERE userid = '$vuid' AND refid = '$tReference' AND status = '$mytxtstatus'");

                mysqli_query($link, "DELETE FROM otp_confirmation WHERE userid = '$vuid' AND otp_code = '$myotp' AND status = 'Pending'");
                    
                echo "<div class='alert bg-orange'>Opps!...Unable to conclude transaction, Please Try again later</div>";
                echo '<meta http-equiv="refresh" content="5;url=transfer_fund.php?tid='.$_SESSION['tid'].'&&mid=NDA0&&tab=tab_1">';
                
            }

        }
        elseif($vnip_route == "SuntrustBank"){

            //Fetch Bank Name
            $search_bankname = mysqli_query($link, "SELECT * FROM bank_list WHERE bankcode = '$recipientBankCode'");
            $fetch_bankname = mysqli_fetch_array($search_bankname);
            $mybank_name = $fetch_bankname['bankname'];

            $transactionDateTime = date("Y-m-d h:i:s");
            //8 rows [0 - 8]
            $dataToProcess = $tReference."|".$amountWithNoCharges."|".$narration."|".$accountName."|".$mybank_name."|".$senderName."|".$recipientAcctNo."|".$recipientBankCode."|".$vtransfer_balance;
            //insert txt waiting list
            $mytxtstatus = 'Pending';
            ($amountWithCharges > $vtransfer_balance) ? "" : mysqli_query($link, "INSERT INTO api_txtwaitinglist VALUE(null,'$vuid','$tReference','$dataToProcess','$mytxtstatus','$transactionDateTime')");
            
            //Debit Customer Wallet
            ($amountWithCharges > $vtransfer_balance) ? "" : mysqli_query($link, "UPDATE user SET transfer_balance = '$senderBalance' WHERE id = '$vuid' AND created_by = '$vcreated_by'");

            ($amountWithCharges > $vtransfer_balance) ? "" : $result = $new->newSunTrustNIPBankTransfer($link,$tReference,$narration,$amountWithNoCharges,$recipientAcctNo,$recipientBankCode,$onePipeSKey,$onePipeApiKey);
            
            ($amountWithCharges > $vtransfer_balance) ? "" : $decodePro = json_decode($result, true);

            $responseCode = ($amountWithCharges > $vtransfer_balance) ? "" : $decodePro['data']['provider_response_code'];

            if($vtransfer_balance >= $amountWithCharges && $responseCode == "00"){
                
                $gatewayResponse = $decodePro['message'];
                $recipient = $recipientAcctNo.', '.$accountName.', '.$mybank_name;
                
                //UPDATE WAITING TXT
                mysqli_query($link, "UPDATE api_txtwaitinglist SET status = '$result' WHERE userid = '$vuid' AND refid = '$tReference' AND status = '$mytxtstatus'");

                //Log successful transaction history
                mysqli_query($link, "INSERT INTO wallet_history VALUE(null,'$vcreated_by','$tReference','$recipient','','$amountWithNoCharges','Debit','$vcurrency','BANK_TRANSFER','Transfer to $recipient','successful','$transactionDateTime','$vuid','$senderBalance','$vnip_route')");
                mysqli_query($link, "INSERT INTO wallet_history VALUE(null,'$vcreated_by','$tReference','$recipient','','$calcCharges','Debit','$vcurrency','Charges','Transfer to $recipient, Narration: $narration','successful','$transactionDateTime','$vuid','$senderBalance','$vnip_route')");

                $sendSMS->bankTransferEmailNotifier($senderEmail, $tReference, $senderName, $transactionDateTime, $accountName, $recipientAcctNo, $mybank_name, $merchantName, $vcurrency, $amountWithNoCharges, $senderBalance, $vemailConfigStatus, $vfetch_emailConfig);

                mysqli_query($link, "DELETE FROM otp_confirmation WHERE userid = '$vuid' AND otp_code = '$myotp' AND status = 'Pending'");
                    
                echo "<div class='alert bg-blue'>Transaction Successful! Current Transfer Wallet Balance is: <b style='color: white;'>".$vcurrency.number_format($senderBalance,2,'.',',')."</b></div>";
        	    echo '<meta http-equiv="refresh" content="10;url=transfer_fund.php?id='.$_SESSION['tid'].'&&mid=NDA0&&tab=tab_1">';
                    
            }
            else{

                $myWaitingList = mysqli_query($link, "SELECT * FROM api_txtwaitinglist WHERE userid = '$vuid' AND refid = '$tReference' AND status = '$mytxtstatus'");
                $fetchMyWaitingList = mysqli_fetch_array($myWaitingList);
                $myWaitingData = $fetchMyWaitingList['mydata'];

                $myParameter = (explode('|',$myWaitingData));
                $defaultBalance = $myParameter[8];
                
                //Reverse Customer Wallet
                mysqli_query($link, "UPDATE user SET transfer_balance = '$defaultBalance' WHERE id = '$vuid' AND created_by = '$vcreated_by'");
            
                //UPDATE WAITING TXT
                mysqli_query($link, "UPDATE api_txtwaitinglist SET status = '$result' WHERE userid = '$vuid' AND refid = '$tReference' AND status = '$mytxtstatus'");

                mysqli_query($link, "DELETE FROM otp_confirmation WHERE userid = '$vuid' AND otp_code = '$myotp' AND status = 'Pending'");
                    
                echo "<div class='alert bg-orange'>Opps!...Unable to conclude transaction, Please Try again later</div>";
                echo '<meta http-equiv="refresh" content="5;url=transfer_fund.php?tid='.$_SESSION['tid'].'&&mid=NDA0&&tab=tab_1">';
                
            }

        }
        else{

            mysqli_query($link, "DELETE FROM otp_confirmation WHERE userid = '$vuid' AND otp_code = '$myotp' AND status = 'Pending'");
                 
            echo "<div class='alert bg-orange'>Opps!...Service not available at the moment!!</div>";
            echo '<meta http-equiv="refresh" content="3;url=transfer_fund.php?tid='.$_SESSION['tid'].'&&mid=NDA0&&tab=tab_1">';

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
                  if($vnip_route == "Wallet Africa" || $vnip_route == "PrimeAirtime" || $vnip_route == "SuntrustBank")
                  {
                 ?>

                 <div class="form-group">
                    <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Country</label>
                    <div class="col-sm-6">
                        <select name="country"  class="form-control select2" id="country" onchange="loadbank();" required>
                          <option value="" selected>Please Select Country</option>
                          <option value="NG">Nigeria</option>
                          <!--<option value="GH">Ghana</option>
                          <option value="KE">Kenya</option>
                          <option value="UG">Uganda </option>
                          <option value="TZ">Tanzania</option>-->
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
                  elseif($vnip_route == "ProvidusBank" || $vnip_route == "AccessBank" || $vnip_route == "SterlingBank" || $vnip_route == "RubiesBank"){
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
                        if($vnip_route == "ProvidusBank"){
                        ?>
                          <select name="bank_code"  class="form-control select2" id="bankCode" onchange="fetchbanklist();" required>
                        <?php
                        }
                        elseif($vnip_route == "SterlingBank"){
                        ?>
                          <select name="bank_code"  class="form-control select2" id="sterlingBankCode" onchange="fetchsterlingbanklist();" required>
                        <?php
                        }
                        elseif($vnip_route == "RubiesBank"){
                        ?>
                          <select name="bank_code"  class="form-control select2" id="rubiesBankCode" onchange="fetchrubiesbanklist();" required>
                        <?php
                        }
                        ?>
                          <option value="" selected>Select Bank</option>
                          <?php
                            if($vnip_route == "ProvidusBank"){ //ROUTE FOR PROVIDUS BANK

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

                            }elseif($vnip_route == "SterlingBank"){ //ROUTE FOR STERLING BANK

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

                            }elseif($vnip_route == "RubiesBank"){ //ROUTE FOR RUBIES BANK
                              
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
                        <span style="color: <?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>;">NOTE: Pease do not put comma (,) while entering the Amount to Transfer</span>
                    </div>
                    <label for="" class="col-sm-3 control-label"></label>
                  </div>
                  
                  <div class="form-group">
                    <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Reason(s)</label>
                    <div class="col-sm-6">
                        <textarea name="reasons" class="form-control" placeholder="State Reasons for Transfer e.g. For House rent, Pay Bonus etc." rows="2" cols="5" required></textarea>
                    </div>
                    <label for="" class="col-sm-3 control-label"></label>
                  </div>
			
			 </div>
			 
			 <div class="form-group" align="right">
                <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;"></label>
                <div class="col-sm-6">
                    <button name="africa_save" type="submit" class="btn bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?> btn-flat"><i class="fa fa-forward">&nbsp;Initiate Transfer</i></button>
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
    
    $reference = date("yd").time();
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
	$phone = $vo_phone;
	$currenctdate = date("Y-m-d H:i:s");
	
	$query = mysqli_query($link, "SELECT * FROM systemset") or die (mysqli_error($link));
    $r = mysqli_fetch_object($query);
    $intlpayment_charges = $row1->intlpayment_charges;
    $sysabb = $mvsenderid;
    
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
	        "ToCurrency"    =>  $vcurrency,
	        "Amount"        =>  $amountWithNoCharges
	        ]
	    );
	    
	$make_call = callAPI('POST', $api_url, json_encode($postdata));
	$result = json_decode($make_call, true);
    
    $convertedAmount = $result['data']['ToAmount'];
    
    $otp_code = ($votp_option == "Yes" || $votp_option == "Both") ? substr((uniqid(rand(),1)),3,6) : $myvepin;
    
    $otpChecker = ($votp_option == "Yes" || $votp_option == "Both") ? "otp" : "pin";
								
	$sms = "$sysabb>>>Dear $vuname! Your One Time Password is $otp_code";
    
    $sms_rate = $r->fax;
    $vmywallet_balance = $vwallet_balance - $sms_rate;
    $sms_refid = "EA-smsCharges-".time();
	
	//New AMount + Charges
    $amountWithCharges = ($intlpayment_charges * $convertedAmount) + $convertedAmount;
	
	//Data Parser (array size = 11)
    $mydata = $convertedAmount."|".$amountWithCharges."|".$intnarration."|".$currency."|".$reference."|".$b_name."|".$account_number."|".$routing_number."|".$swift_code."|".$bank_name."|".$b_addrs."|".$b_country."|".$vtransfer_balance;
    
    if($amountWithNoCharges <= 0){

        echo "<div class='alert bg-orange'>Sorry! Invalid amount entered!!</div>";

    }
    elseif($vactive_status == "Suspended"){
        
        echo "<div class='alert bg-orange'>Oops! You are unable to make transfer at the moment!!</div>";
        
    }
	elseif(($vtransfer_balance < $amountWithCharges && ($votp_option == "Yes" || $votp_option == "Both")) || ($vtransfer_balance < $amountWithCharges && $votp_option == "No")){
	    
	    echo "<div class='alert bg-orange'>Sorry! You need to have upto $vcurrency.number_format($amountWithCharges,2,'.',',') in your wallet to proceed!!</div>";
      
    }
    elseif($vwallet_balance < $sms_rate && ($votp_option == "Yes" || $votp_option == "Both")){
      
        echo "<div class='alert bg-orange'>Oops! You have insufficient fund in your Wallet to Receive OTP!!</div>";
      
    }
    elseif($amountWithNoCharges > $vtransferLimitPerTrans){

        echo "<div class='alert bg-orange'>Sorry! You can not transfer more than ".$vcurrency.number_format($vtransferLimitPerTrans,2,'.',',')." at once!!</div>";

    }
    elseif($vmyDailyTransferLimit == $vtransferLimitPerDay || (($amountWithNoCharges + $vmyDailyTransferLimit) > $vtransferLimitPerDay)){

        echo "<div class='alert bg-orange'>Oops! You have reached your daily limit of ".$vcurrency.number_format($vtransferLimitPerDay,2,'.',',')."</div>";

    }
    elseif($vvirtual_acctno === "$account_number" || $vuid === "$account_number"){
    
        mysqli_query($link, "UPDATE user SET status = 'Suspended' WHERE id = '$vuid'");
        mysqli_query($link, "INSERT INTO wallet_case VALUES(null,'$vcreated_by','','System','$vuid','Suspended','Frudulent Act Detected','$currenctdate')");
        
        echo "<div class='alert bg-orange'>Oops! Request Failed!!</div>";
        
    }
	else{
        
        ($iotp_option == "No" ? "" : (($iotp_option == "Yes" || $iotp_option == "Both") ? $sendSMS->vendorGeneralAlert($sysabb, $vo_phone, $sms, $vcreated_by, $sms_refid, $sms_rate, $vuid, $vmywallet_balance, $debitWallet, $vendorid) : (($iotp_option == "Yes" || $iotp_option == "Both") && $sms_rate <= $vwallet_balance ? $sendSMS->vendorGeneralAlert($sysabb, $vo_phone, $sms, $vcreated_by, $sms_refid, $sms_rate, $vuid, $vmywallet_balance, $debitWallet, $vendorid) : "")));

        $insert = mysqli_query($link, "INSERT INTO otp_confirmation VALUES(null,'$vuid','$otp_code','$mydata','Pending','$currenctdate')");
            
        echo ($votp_option == "Yes" || $votp_option == "Both") ? "<div align='center'><img src='../image/checkmark.gif'><p style='color: #38A1F3;'>Great! Otp has been sent to your mobile phone to complete this transaction!!</p></div>" : "<div align='center'><img src='../image/checkmark.gif'><p style='color: #38A1F3;'>Transfer Request Received Successfully! Wait a moment to complete the transaction with your Pin</p></div>";
        echo '<meta http-equiv="refresh" content="2;url=transfer_fund.php?id='.$_SESSION['tid'].'&&mid=NDA0&&tab=tab_2&&'.$otpChecker.'">';
        
	}
	
}
?>


<?php
if (isset($_POST['confirm']))
{
    
    //include("../config/walletafrica_restfulapis_call.php");
    include("../config/intlrestful_apicalls.php");
    
    $result = array();
    $result1 = array();
    $result2 = array();
    $myotp = $_POST['otp'];
    
    $verify_otp = mysqli_query($link, "SELECT * FROM otp_confirmation WHERE otp_code = '$myotp' AND userid = '$vuid' AND status = 'Pending'");
	$fetch_data = mysqli_fetch_array($verify_otp);
	$otpnum = mysqli_num_rows($verify_otp);
						    
	if($otpnum == 0){
						        
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
        $defaultCustomerBal = $parameter[12];
	    $calcCharges = $amountWithCharges - $amountWithNoCharges;
            
        //Get my wallet balance after debiting
        $senderBalance = $vtransfer_balance - $amountWithCharges;
        $senderEmail = $vo_email;
        $senderName = $vc_name;
        $merchantName = $mvcname;

        $transactionDateTime = date("Y-m-d h:i:s");
        //13 rows [0 - 13]
        $dataToProcess = $amountWithNoCharges."|".$rave_secret_key."|".$intnarration."|".$currency."|".$tReference."|".$accountName."|".$recipientAcctNo."|".$routing_number."|".$swift_code."|".$mybank_name."|".$b_addrs."|".$b_country."|".$vcurrency."|".$vtransfer_balance;
        //insert txt waiting list
        $mytxtstatus = 'Pending';
        ($amountWithCharges > $vtransfer_balance) ? "" : mysqli_query($link, "INSERT INTO api_txtwaitinglist VALUE(null,'$vuid','$tReference','$dataToProcess','$mytxtstatus','$transactionDateTime')");
            
        //Debit Customer Wallet
        ($amountWithCharges > $vtransfer_balance) ? "" : mysqli_query($link, "UPDATE user SET transfer_balance = '$senderBalance' WHERE id = '$vuid' AND created_by = '$vcreated_by'");

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
                "debit_currency"    =>  $vcurrency
        );
              
        ($amountWithCharges > $vtransfer_balance) ? "" : $make_call1 = intlCallAPI('POST', $api_url1, json_encode($postdata1));
        ($amountWithCharges > $vtransfer_balance) ? "" : $result1 = json_decode($make_call1, true);
            
        if($result1['status'] == "success" && $vtransfer_balance >= $amountWithCharges){
                
            $transfer_id = $result1['data']['id'];
            $transfers_fee = "Gateway Fee: ".$vcurrency.$result1['data']['fee']." | Inhouse Fee: ".$vcurrency.$calcCharges;
            $status = $result1['data']['status'];
            $recipient = $recipientAcctNo.', '.$accountName.', '.$routing_number.', '.$mybank_name;

            //UPDATE WAITING TXT
            mysqli_query($link, "UPDATE api_txtwaitinglist SET status = '$make_call1' WHERE userid = '$vuid' AND refid = '$tReference' AND status = '$mytxtstatus'");
            
            //Log successful transaction history
            mysqli_query($link, "INSERT INTO wallet_history VALUE(null,'$vcreated_by','$tReference','$recipient','','$amountWithNoCharges','Debit','$currency','BANK_TRANSFER','Transfer to $recipient','successful','$transactionDateTime','$vuid','$senderBalance','Flutterwave')");
            mysqli_query($link, "INSERT INTO wallet_history VALUE(null,'$vcreated_by','$tReference','$recipient','','$calcCharges','Debit','$currency','Charges','Transfer to $recipient, Narration: $intnarration','successful','$transactionDateTime','$vuid','$senderBalance','Flutterwave')");

            $sendSMS->bankTransferEmailNotifier($senderEmail, $tReference, $senderName, $transactionDateTime, $accountName, $recipientAcctNo, $mybank_name, $merchantName, $vcurrency, $amountWithNoCharges, $senderBalance, $vemailConfigStatus, $vfetch_emailConfig);

            mysqli_query($link, "DELETE FROM otp_confirmation WHERE userid = '$vuid' AND otp_code = '$myotp' AND status = 'Pending'");
                
            echo "<div class='alert bg-blue'>Transfer Initiated Successful! Current Transfer Wallet Balance is: <b style='color: white;'>".$vcurrency.number_format($senderBalance,2,'.',',')."</b></div>";
        	echo '<meta http-equiv="refresh" content="3;url=transfer_fund.php?id='.$_SESSION['tid'].'&&mid=NDA0&&tab=tab_2">';
                
        }
        else{

            $myWaitingList = mysqli_query($link, "SELECT * FROM api_txtwaitinglist WHERE userid = '$vuid' AND refid = '$tReference' AND status = '$mytxtstatus'");
            $fetchMyWaitingList = mysqli_fetch_array($myWaitingList);
            $myWaitingData = $fetchMyWaitingList['mydata'];

            $myParameter = (explode('|',$myWaitingData));
            $defaultBalance = $myParameter[13];
            
            //Reverse to Customer Wallet
            mysqli_query($link, "UPDATE user SET transfer_balance = '$defaultBalance' WHERE id = '$vuid' AND created_by = '$vcreated_by'");
            
            //UPDATE WAITING TXT
            mysqli_query($link, "UPDATE api_txtwaitinglist SET status = '$make_call1' WHERE userid = '$vuid' AND refid = '$tReference' AND status = '$mytxtstatus'");

            mysqli_query($link, "DELETE FROM otp_confirmation WHERE userid = '$vuid' AND otp_code = '$myotp' AND status = 'Pending'");
            
            echo "<div class='alert bg-orange'>Sorry! Unable to conclude transaction please try again later!!</div>";
            echo '<meta http-equiv="refresh" content="3;url=transfer_fund.php?id='.$_SESSION['tid'].'&&mid=NDA0&&tab=tab_2">';
            
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
                    <button name="inter_save" type="submit" class="btn bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?> btn-flat"><i class="fa fa-forward">&nbsp;Initiate Transfer</i></button>
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
    
    $reference = date("yd").time();
    $account_number =  mysqli_real_escape_string($link, $_POST['account_number']);
    $routing_number =  mysqli_real_escape_string($link, $_POST['routing_number']);
    $currency = mysqli_real_escape_string($link, $_POST['currency']);
	$amountWithNoCharges = preg_replace('/[^0-9.]/', '', mysqli_real_escape_string($link, $_POST['amt_totransfer']));
	$bname = mysqli_real_escape_string($link, $_POST['b_name']);
	$mnarration =  mysqli_real_escape_string($link, $_POST['reasons']);
	$phone = $vo_phone;
	$currenctdate = date("Y-m-d H:i:s");
	
	$query = mysqli_query($link, "SELECT * FROM systemset") or die (mysqli_error($link));
    $r = mysqli_fetch_object($query);
    $intlpayment_charges = $row1->intlpayment_charges;
    $sysabb = $mvsenderid;
    
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
	        "ToCurrency"    =>  $vcurrency,
	        "Amount"        =>  $amountWithNoCharges
	        ]
	    );
	    
	$make_call = callAPI('POST', $api_url, json_encode($postdata));
	$result = json_decode($make_call, true);
    
    $convertedAmount = $result['data']['ToAmount'];
    
    $otp_code = ($votp_option == "Yes" || $votp_option == "Both") ? substr((uniqid(rand(),1)),3,6) : $myvepin;
    
    $otpChecker = ($votp_option == "Yes" || $votp_option == "Both") ? "otp" : "pin";
								
	$sms = "$sysabb>>>Dear $vuname! Your One Time Password is $otp_code";
    
    $sms_rate = $r->fax;
    $vmywallet_balance = $vwallet_balance - $sms_rate;
    $sms_refid = "EA-smsCharges-".time();
	
	//New Amount + Charges
    $amountWithCharges = ($intlpayment_charges * $convertedAmount) + $convertedAmount;
	
	//Data Parser (array size = 6)
    $mydata = $account_number."|".$convertedAmount."|".$amountWithCharges."|".$mnarration."|".$currency."|".$reference."|".$bname."|".$vtransfer_balance;
    
    if($amountWithNoCharges <= 0){

        echo "<div class='alert bg-orange'>Sorry! Invalid amount entered!!</div>";

    }
    elseif($vactive_status == "Suspended"){
        
        echo "<div class='alert bg-orange'>Oops! You are unable to make transfer at the moment!!</div>";
        
    }
    elseif(($vtransfer_balance < $amountWithCharges && ($votp_option == "Yes" || $votp_option == "Both")) || ($vtransfer_balance < $amountWithCharges && $votp_option == "No")){
	    
	    echo "<div class='alert bg-orange'>Sorry! You need to have upto $vcurrency.number_format($amountWithCharges,2,'.',',') in your wallet to proceed!!</div>";
      
    }
    elseif($vwallet_balance < $sms_rate && ($votp_option == "Yes" || $votp_option == "Both")){
      
        echo "<div class='alert bg-orange'>Oops! You have insufficient fund in your Wallet to Receive OTP!!</div>";
      
    }
    elseif($amountWithNoCharges > $vtransferLimitPerTrans){

        echo "<div class='alert bg-orange'>Sorry! You can not transfer more than ".$vcurrency.number_format($vtransferLimitPerTrans,2,'.',',')." at once!!</div>";

    }
    elseif($vmyDailyTransferLimit == $vtransferLimitPerDay || (($amountWithNoCharges + $vmyDailyTransferLimit) > $vtransferLimitPerDay)){

        echo "<div class='alert bg-orange'>Oops! You have reached your daily limit of ".$vcurrency.number_format($vtransferLimitPerDay,2,'.',',')."</div>";

    }
    elseif($vvirtual_acctno === "$account_number" || $vuid === "$account_number"){
    
        mysqli_query($link, "UPDATE user SET status = 'Suspended' WHERE id = '$vuid'");
        mysqli_query($link, "INSERT INTO wallet_case VALUES(null,'$vcreated_by','','System','$vuid','Suspended','Frudulent Act Detected','$currenctdate')");
        
        echo "<div class='alert bg-orange'>Oops! Request Failed!!</div>";
        
    }
	else{
        
        ($iotp_option == "No" ? "" : (($iotp_option == "Yes" || $iotp_option == "Both") ? $sendSMS->vendorGeneralAlert($sysabb, $vo_phone, $sms, $vcreated_by, $sms_refid, $sms_rate, $vuid, $vmywallet_balance, $debitWallet, $vendorid) : (($iotp_option == "Yes" || $iotp_option == "Both") && $sms_rate <= $vwallet_balance ? $sendSMS->vendorGeneralAlert($sysabb, $vo_phone, $sms, $vcreated_by, $sms_refid, $sms_rate, $vuid, $vmywallet_balance, $debitWallet, $vendorid) : "")));

        $insert = mysqli_query($link, "INSERT INTO otp_confirmation VALUES(null,'$vuid','$otp_code','$mydata','Pending','$currenctdate')");
        
        echo ($votp_option == "Yes" || $votp_option == "Both") ? "<div align='center'><img src='../image/checkmark.gif'><p style='color: #38A1F3;'>Great! Otp has been sent to your mobile phone to complete this transaction!!</p></div>" : "<div align='center'><img src='../image/checkmark.gif'><p style='color: #38A1F3;'>Transfer Request Received Successfully! Wait a moment to complete the transaction with your Pin</p></div>";
        echo '<meta http-equiv="refresh" content="2;url=transfer_fund.php?id='.$_SESSION['tid'].'&&mid=NDA0&&tab=tab_3&&'.$otpChecker.'">';
    	
	}
}
?>




<?php
if (isset($_POST['confirm']))
{
    
    //include("../config/walletafrica_restfulapis_call.php");
    include("../config/intlrestful_apicalls.php");
    
    $result = array();
    $result1 = array();
    $result2 = array();
    $myotp = $_POST['otp'];
    
    $verify_otp = mysqli_query($link, "SELECT * FROM otp_confirmation WHERE otp_code = '$myotp' AND userid = '$vuid' AND status = 'Pending'");
	$fetch_data = mysqli_fetch_array($verify_otp);
	$otpnum = mysqli_num_rows($verify_otp);
						    
	if($otpnum == 0){
						        
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
        $defaultCustomerBal = $parameter[7];
        $calcCharges = $amountWithCharges - $amountWithNoCharges;
            
        //Get my wallet balance after debiting
        $senderBalance = $vtransfer_balance - $amountWithCharges;
        $senderEmail = $vo_email;
        $senderName = $vc_name;
        $merchantName = $mvcname;

        $transactionDateTime = date("Y-m-d h:i:s");
        //9 rows [0 - 9]
        $dataToProcess = "MPS|".$recipientAcctNo."|".$amountWithNoCharges."|".$rave_secret_key."|".$mnarration."|".$currency."|".$tReference."|".$accountName."|".$vcurrency."|".$vtransfer_balance;
        //insert txt waiting list
        $mytxtstatus = 'Pending';
        ($amountWithCharges > $vtransfer_balance) ? "" : mysqli_query($link, "INSERT INTO api_txtwaitinglist VALUE(null,'$vuid','$tReference','$dataToProcess','$mytxtstatus','$transactionDateTime')");

        //Debit Customer Wallet
        ($amountWithCharges > $vtransfer_balance) ? "" : mysqli_query($link, "UPDATE user SET transfer_balance = '$senderBalance' WHERE id = '$vuid' AND created_by = '$vcreated_by'");
        
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
            "debit_currency"    =>  $vcurrency
        );
              
        ($amountWithCharges > $vtransfer_balance) ? "" : $make_call1 = intlCallAPI('POST', $api_url1, json_encode($postdata1));
        ($amountWithCharges > $vtransfer_balance) ? "" : $result1 = json_decode($make_call1, true);
            
        if($result1['status'] == "success" && $vtransfer_balance >= $amountWithCharges){
                
            $transfer_id = $result['data']['id'];
            $transfers_fee = "Gateway Fee: ".$vcurrency.$result['data']['fee']." | Inhouse Fee: ".$vcurrency.$calcCharges;
            $mybank_name = $result['data']['bank_name'];
            $status = $result['data']['status'];
            $recipient = $recipientAcctNo.', '.$accountName.', MPS, '.$mybank_name;

            //UPDATE WAITING TXT
            mysqli_query($link, "UPDATE api_txtwaitinglist SET status = '$make_call1' WHERE userid = '$vuid' AND refid = '$tReference' AND status = '$mytxtstatus'");
                
            //Log successful transaction history
            mysqli_query($link, "INSERT INTO wallet_history VALUE(null,'$vcreated_by','$tReference','$recipient','','$amountWithNoCharges','Debit','$currency','BANK_TRANSFER','Transfer to $recipient','successful','$transactionDateTime','$vuid','$senderBalance','Flutterwave')");
            mysqli_query($link, "INSERT INTO wallet_history VALUE(null,'$vcreated_by','$tReference','$recipient','','$calcCharges','Debit','$currency','Charges','Transfer to $recipient, Narration: $mnarration','successful','$transactionDateTime','$vuid','$senderBalance','Flutterwave')");

            $sendSMS->bankTransferEmailNotifier($senderEmail, $tReference, $senderName, $transactionDateTime, $accountName, $recipientAcctNo, $mybank_name, $merchantName, $vcurrency, $amountWithNoCharges, $senderBalance, $vemailConfigStatus, $vfetch_emailConfig);

            mysqli_query($link, "DELETE FROM otp_confirmation WHERE userid = '$vuid' AND otp_code = '$myotp' AND status = 'Pending'");
                
            echo "<div class='alert bg-blue'>Transfer Initiated Successful! Current Wallet Balance is: <b style='color: white;'>".$vcurrency.number_format($senderBalance,2,'.',',')."</b></div>";
        	echo '<meta http-equiv="refresh" content="3;url=transfer_fund.php?id='.$_SESSION['tid'].'&&mid=NDA0&&tab=tab_3">';
                
        }
        else{

            $myWaitingList = mysqli_query($link, "SELECT * FROM api_txtwaitinglist WHERE userid = '$vuid' AND refid = '$tReference' AND status = '$mytxtstatus'");
            $fetchMyWaitingList = mysqli_fetch_array($myWaitingList);
            $myWaitingData = $fetchMyWaitingList['mydata'];

            $myParameter = (explode('|',$myWaitingData));
            $defaultBalance = $myParameter[9];
            
            //Reverse to Customer Wallet
            mysqli_query($link, "UPDATE user SET transfer_balance = '$defaultBalance' WHERE id = '$vuid' AND created_by = '$vcreated_by'");

            //UPDATE WAITING TXT
            mysqli_query($link, "UPDATE api_txtwaitinglist SET status = '$make_call1' WHERE userid = '$vuid' AND refid = '$tReference' AND status = '$mytxtstatus'");

            mysqli_query($link, "DELETE FROM otp_confirmation WHERE userid = '$vuid' AND otp_code = '$myotp' AND status = 'Pending'");
            
            echo "<div class='alert bg-orange'>Sorry! Unable to conclude transaction please try again later!!</div>";
            echo '<meta http-equiv="refresh" content="3;url=transfer_fund.php?id='.$_SESSION['tid'].'&&mid=NDA0&&tab=tab_3">';
            
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
                    <button name="mpesa_save" type="submit" class="btn bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?> btn-flat"><i class="fa fa-forward">&nbsp;Initiate Transfer</i></button>
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
    
    $reference = date("yd").time();
    $country =  mysqli_real_escape_string($link, $_POST['country']);
    $bank_id = mysqli_real_escape_string($link, $_POST['bank_id']);
    $branch_code = mysqli_real_escape_string($link, $_POST['branch_code']);
    $account_bank =  mysqli_real_escape_string($link, $_POST['account_bank']);
    $account_number =  mysqli_real_escape_string($link, $_POST['account_number']);
    $currency = "GHS";
	$amountWithNoCharges = preg_replace('/[^0-9.]/', '', mysqli_real_escape_string($link, $_POST['amt_totransfer']));
	$bname = mysqli_real_escape_string($link, $_POST['b_name']);
	$mnarration =  mysqli_real_escape_string($link, $_POST['reasons']);
	$phone = $vo_phone;
	$currenctdate = date("Y-m-d H:i:s");
	
	$query = mysqli_query($link, "SELECT * FROM systemset") or die (mysqli_error($link));
    $r = mysqli_fetch_object($query);
    $intlpayment_charges = $row1->intlpayment_charges;
    $sysabb = $mvsenderid;
    
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
	        "ToCurrency"    =>  $vcurrency,
	        "Amount"        =>  $amountWithNoCharges
	        ]
	    );
	    
	$make_call = callAPI('POST', $api_url, json_encode($postdata));
	$result = json_decode($make_call, true);
    
    $convertedAmount = $result['data']['ToAmount'];
    
    $otp_code = ($votp_option == "Yes" || $votp_option == "Both") ? substr((uniqid(rand(),1)),3,6) : $myvepin;
    
    $otpChecker = ($votp_option == "Yes" || $votp_option == "Both") ? "otp" : "pin";
								
	$sms = "$sysabb>>>Dear $vuname! Your One Time Password is $otp_code";
    
    $sms_rate = $r->fax;
    $vmywallet_balance = $vwallet_balance - $sms_rate;
    $sms_refid = "EA-smsCharges-".time();
	
	//New Amount + Charges
    $amountWithCharges = ($intlpayment_charges * $convertedAmount) + $convertedAmount;
	
	//Data Parser (array size = 8)
    $mydata = $account_bank."|".$account_number."|".$convertedAmount."|".$amountWithCharges."|".$mnarration."|".$currency."|".$reference."|".$bname."|".$branch_code."|".$vtransfer_balance;
    
    if($amountWithNoCharges <= 0){

        echo "<div class='alert bg-orange'>Sorry! Invalid amount entered!!</div>";

    }
    elseif($vactive_status == "Suspended"){
        
        echo "<div class='alert bg-orange'>Oops! You are unable to make transfer at the moment!!</div>";
        
    }
	elseif(($vtransfer_balance < $amountWithCharges && ($votp_option == "Yes" || $votp_option == "Both")) || ($vtransfer_balance < $amountWithCharges && $votp_option == "No")){
	    
	    echo "<div class='alert bg-orange'>Sorry! You need to have upto $vcurrency.number_format($amountWithCharges,2,'.',',') in your wallet to proceed!!</div>";
      
    }
    elseif($vwallet_balance < $sms_rate && ($votp_option == "Yes" || $votp_option == "Both")){
      
        echo "<div class='alert bg-orange'>Oops! You have insufficient fund in your Wallet to Receive OTP!!</div>";
      
    }
    elseif($amountWithNoCharges > $vtransferLimitPerTrans){

        echo "<div class='alert bg-orange'>Sorry! You can not transfer more than ".$vcurrency.number_format($vtransferLimitPerTrans,2,'.',',')." at once!!</div>";

    }
    elseif($vmyDailyTransferLimit == $vtransferLimitPerDay || (($amountWithNoCharges + $vmyDailyTransferLimit) > $vtransferLimitPerDay)){

        echo "<div class='alert bg-orange'>Oops! You have reached your daily limit of ".$vcurrency.number_format($vtransferLimitPerDay,2,'.',',')."</div>";

    }
    elseif($vvirtual_acctno === "$account_number" || $vuid === "$account_number"){
    
        mysqli_query($link, "UPDATE user SET status = 'Suspended' WHERE id = '$vuid'");
        mysqli_query($link, "INSERT INTO wallet_case VALUES(null,'$vcreated_by','','System','$vuid','Suspended','Frudulent Act Detected','$currenctdate')");
        
        echo "<div class='alert bg-orange'>Oops! Request Failed!!</div>";
        
    }
	else{
        
        ($iotp_option == "No" ? "" : (($iotp_option == "Yes" || $iotp_option == "Both") ? $sendSMS->vendorGeneralAlert($sysabb, $vo_phone, $sms, $vcreated_by, $sms_refid, $sms_rate, $vuid, $vmywallet_balance, $debitWallet, $vendorid) : (($iotp_option == "Yes" || $iotp_option == "Both") && $sms_rate <= $vwallet_balance ? $sendSMS->vendorGeneralAlert($sysabb, $vo_phone, $sms, $vcreated_by, $sms_refid, $sms_rate, $vuid, $vmywallet_balance, $debitWallet, $vendorid) : "")));

        $insert = mysqli_query($link, "INSERT INTO otp_confirmation VALUES(null,'$vuid','$otp_code','$mydata','Pending','$currenctdate')");
        
        echo ($votp_option == "Yes" || $votp_option == "Both") ? "<div align='center'><img src='../image/checkmark.gif'><p style='color: #38A1F3;'>Great! Otp has been sent to your mobile phone to complete this transaction!!</p></div>" : "<div align='center'><img src='../image/checkmark.gif'><p style='color: #38A1F3;'>Transfer Request Received Successfully! Wait a moment to complete the transaction with your Pin</p></div>";
        echo '<meta http-equiv="refresh" content="2;url=transfer_fund.php?id='.$_SESSION['tid'].'&&mid=NDA0&&tab=tab_4&&'.$otpChecker.'">';
        
	}
} 
?>



<?php
if (isset($_POST['confirm']))
{
    
    //include("../config/walletafrica_restfulapis_call.php");
    include("../config/intlrestful_apicalls.php");
    
    $result = array();
    $result1 = array();
    $result2 = array();
    $myotp = $_POST['otp'];
    
    $verify_otp = mysqli_query($link, "SELECT * FROM otp_confirmation WHERE otp_code = '$myotp' AND userid = '$vuid' AND status = 'Pending'");
	$fetch_data = mysqli_fetch_array($verify_otp);
	$otpnum = mysqli_num_rows($verify_otp);
	
	if($otpnum == 0){
						        
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
        $defaultCustomerBal = $parameter[9];
        $calcCharges = $amountWithCharges - $amountWithNoCharges;
            
        //Get my wallet balance after debiting
        $senderBalance = $vtransfer_balance - $amountWithCharges;
        $senderEmail = $vo_email;
        $senderName = $vc_name;
        $merchantName = $mvcname;

        $transactionDateTime = date("Y-m-d h:i:s");
        //10 rows [0 - 10]
        $dataToProcess = $account_bank."|".$recipientAcctNo."|".$amountWithNoCharges."|".$rave_secret_key."|".$mnarration."|".$currency."|".$tReference."|".$accountName."|".$vcurrency."|".$branch_code."|".$vtransfer_balance;
        //insert txt waiting list
        $mytxtstatus = 'Pending';
        ($amountWithCharges > $vtransfer_balance) ? "" : mysqli_query($link, "INSERT INTO api_txtwaitinglist VALUE(null,'$vuid','$tReference','$dataToProcess','$mytxtstatus','$transactionDateTime')");
        
        //Debit Customer Wallet
        ($amountWithCharges > $vtransfer_balance) ? "" : mysqli_query($link, "UPDATE user SET transfer_balance = '$senderBalance' WHERE id = '$vuid' AND created_by = '$vcreated_by'");
        
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
            "debit_currency"    =>  $vcurrency,
            "destination_branch_code"=> $branch_code
        );
            
        ($amountWithCharges > $vtransfer_balance) ? "" : $make_call1 = intlCallAPI('POST', $api_url1, json_encode($postdata1));
        ($amountWithCharges > $vtransfer_balance) ? "" : $result1 = json_decode($make_call1, true);
            
        if($result1['status'] == "success" && $vtransfer_balance >= $amountWithCharges){
                
            $transfer_id = $result['data']['id'];
            $transfers_fee = "Gateway Fee: ".$vcurrency.$result['data']['fee']." | Inhouse Fee: ".$vcurrency.$calcCharges;
            $mybank_name = $result['data']['bank_name'];
            $status = $result['data']['status'];
            $recipient = $recipientAcctNo.', '.$accountName.', '.$account_bank.', '.$mybank_name;

            //UPDATE WAITING TXT
            mysqli_query($link, "UPDATE api_txtwaitinglist SET status = '$make_call1' WHERE userid = '$vuid' AND refid = '$tReference' AND status = '$mytxtstatus'");
            
            //Log successful transaction history
            mysqli_query($link, "INSERT INTO wallet_history VALUE(null,'$vcreated_by','$tReference','$recipient','','$amountWithNoCharges','Debit','$currency','BANK_TRANSFER','Transfer to $recipient','successful','$transactionDateTime','$vuid','$senderBalance','Flutterwave')");
            mysqli_query($link, "INSERT INTO wallet_history VALUE(null,'$vcreated_by','$tReference','$recipient','','$calcCharges','Debit','$currency','Charges','Transfer to $recipient, Narration: $mnarration','successful','$transactionDateTime','$vuid','$senderBalance','Flutterwave')");

            $sendSMS->bankTransferEmailNotifier($senderEmail, $tReference, $senderName, $transactionDateTime, $accountName, $recipientAcctNo, $mybank_name, $merchantName, $vcurrency, $amountWithNoCharges, $senderBalance, $vemailConfigStatus, $vfetch_emailConfig);
            //$insert = mysqli_query($link, "INSERT INTO transfer_history VALUES(null,'$vcreated_by','$transfer_id','$reference','$account_number','$bname','$account_bank','$bank_name','$currency','$amountWithNoCharges','$calcCharges','$status','$mnarration','$transactionDateTime','$vuid','$senderBalance')");
            mysqli_query($link, "DELETE FROM otp_confirmation WHERE userid = '$vuid' AND otp_code = '$myotp' AND status = 'Pending'");
                
            echo "<div class='alert bg-blue'>Transfer Initiated Successful! Current Transfer Wallet Balance is: <b style='color: white;'>".$vcurrency.number_format($senderBalance,2,'.',',')."</b></div>";
        	echo '<meta http-equiv="refresh" content="3;url=transfer_fund.php?id='.$_SESSION['tid'].'&&mid=NDA0&&tab=tab_4">';
                
        }
        else{

            $myWaitingList = mysqli_query($link, "SELECT * FROM api_txtwaitinglist WHERE userid = '$vuid' AND refid = '$tReference' AND status = '$mytxtstatus'");
            $fetchMyWaitingList = mysqli_fetch_array($myWaitingList);
            $myWaitingData = $fetchMyWaitingList['mydata'];

            $myParameter = (explode('|',$myWaitingData));
            $defaultBalance = $myParameter[10];

            //Reverse to Customer Wallet
            mysqli_query($link, "UPDATE user SET transfer_balance = '$defaultBalance' WHERE id = '$vuid' AND created_by = '$vcreated_by'");
            
            //UPDATE WAITING TXT
            mysqli_query($link, "UPDATE api_txtwaitinglist SET status = '$make_call1' WHERE userid = '$vuid' AND refid = '$tReference' AND status = '$mytxtstatus'");

            mysqli_query($link, "DELETE FROM otp_confirmation WHERE userid = '$vuid' AND otp_code = '$myotp' AND status = 'Pending'");
            
            echo "<div class='alert bg-orange'>Sorry! Unable to conclude transaction please try again later!!</div>";
            echo '<meta http-equiv="refresh" content="3;url=transfer.php?tid='.$_SESSION['tid'].'&&acn='.$acctno.'&&mid=NDA4&&tab=tab_4">';
            
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
                    <button name="ghana_save" type="submit" class="btn bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?> btn-flat"><i class="fa fa-forward">&nbsp;Initiate Transfer</i></button>
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
    
    $reference = date("yd").time();
    $acctbank =  mysqli_real_escape_string($link, $_POST['account_bank']);
    $account_number =  mysqli_real_escape_string($link, $_POST['account_number']);
    $currency = "UGX";
    $amountWithNoCharges = preg_replace('/[^0-9.]/', '', mysqli_real_escape_string($link, $_POST['amt_totransfer']));
	$bname = mysqli_real_escape_string($link, $_POST['b_name']);
	$mnarration =  mysqli_real_escape_string($link, $_POST['reasons']);
	$phone = $vo_phone;
	$currenctdate = date("Y-m-d H:i:s");
	
	$query = mysqli_query($link, "SELECT * FROM systemset") or die (mysqli_error($link));
    $r = mysqli_fetch_object($query);
    $intlpayment_charges = $row1->intlpayment_charges;
    $sysabb = $mvsenderid;
    
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
	        "ToCurrency"    =>  $vcurrency,
	        "Amount"        =>  $amountWithNoCharges
	        ]
	    );
	    
	$make_call = callAPI('POST', $api_url, json_encode($postdata));
	$result = json_decode($make_call, true);
    
    $convertedAmount = $result['data']['ToAmount'];
    
    $otp_code = ($votp_option == "Yes" || $votp_option == "Both") ? substr((uniqid(rand(),1)),3,6) : $myvepin;
    
    $otpChecker = ($votp_option == "Yes" || $votp_option == "Both") ? "otp" : "pin";
								
	$sms = "$sysabb>>>Dear $vuname! Your One Time Password is $otp_code";
    
    $sms_rate = $r->fax;
    $vmywallet_balance = $vwallet_balance - $sms_rate;
    $sms_refid = "EA-smsCharges-".time();
	
	//New Amount + Charges
    $amountWithCharges = ($intlpayment_charges * $convertedAmount) + $convertedAmount;
	
	//Data Parser (array size = 7)
    $mydata = $acctbank."|".$account_number."|".$convertedAmount."|".$amountWithCharges."|".$mnarration."|".$currency."|".$reference."|".$bname."|".$vtransfer_balance;
    
    if($amountWithNoCharges <= 0){

        echo "<div class='alert bg-orange'>Sorry! Invalid amount entered!!</div>";

    }
    elseif($vactive_status == "Suspended"){
        
        echo "<div class='alert bg-orange'>Oops! You are unable to make transfer at the moment!!</div>";
        
    }
	elseif(($vtransfer_balance < $amountWithCharges && ($votp_option == "Yes" || $votp_option == "Both")) || ($vtransfer_balance < $amountWithCharges && $votp_option == "No")){
	    
	    echo "<div class='alert bg-orange'>Sorry! You need to have upto $vcurrency.number_format($amountWithCharges,2,'.',',') in your wallet to proceed!!</div>";
      
    }
    elseif($vwallet_balance < $sms_rate && ($votp_option == "Yes" || $votp_option == "Both")){
      
        echo "<div class='alert bg-orange'>Oops! You have insufficient fund in your Wallet to Receive OTP!!</div>";
      
    }
    elseif($amountWithNoCharges > $vtransferLimitPerTrans){

        echo "<div class='alert bg-orange'>Sorry! You can not transfer more than ".$vcurrency.number_format($vtransferLimitPerTrans,2,'.',',')." at once!!</div>";

    }
    elseif($vmyDailyTransferLimit == $vtransferLimitPerDay || (($amountWithNoCharges + $vmyDailyTransferLimit) > $vtransferLimitPerDay)){

        echo "<div class='alert bg-orange'>Oops! You have reached your daily limit of ".$vcurrency.number_format($vtransferLimitPerDay,2,'.',',')."</div>";

    }
    elseif($vvirtual_acctno === "$account_number" || $vuid === "$account_number"){
    
        mysqli_query($link, "UPDATE user SET status = 'Suspended' WHERE id = '$vuid'");
        mysqli_query($link, "INSERT INTO wallet_case VALUES(null,'$vcreated_by','','System','$vuid','Suspended','Frudulent Act Detected','$currenctdate')");
        
        echo "<div class='alert bg-orange'>Oops! Request Failed!!</div>";
        
    }
	else{
        
        ($iotp_option == "No" ? "" : (($iotp_option == "Yes" || $iotp_option == "Both") ? $sendSMS->vendorGeneralAlert($sysabb, $vo_phone, $sms, $vcreated_by, $sms_refid, $sms_rate, $vuid, $vmywallet_balance, $debitWallet, $vendorid) : (($iotp_option == "Yes" || $iotp_option == "Both") && $sms_rate <= $vwallet_balance ? $sendSMS->vendorGeneralAlert($sysabb, $vo_phone, $sms, $vcreated_by, $sms_refid, $sms_rate, $vuid, $vmywallet_balance, $debitWallet, $vendorid) : "")));

        $insert = mysqli_query($link, "INSERT INTO otp_confirmation VALUES(null,'$vuid','$otp_code','$mydata','Pending','$currenctdate')");
            
        echo ($votp_option == "Yes" || $votp_option == "Both") ? "<div align='center'><img src='../image/checkmark.gif'><p style='color: #38A1F3;'>Great! Otp has been sent to your mobile phone to complete this transaction!!</p></div>" : "<div align='center'><img src='../image/checkmark.gif'><p style='color: #38A1F3;'>Transfer Request Received Successfully! Wait a moment to complete the transaction with your Pin</p></div>";
        echo '<meta http-equiv="refresh" content="2;url=transfer_fund.php?id='.$_SESSION['tid'].'&&mid=NDA0&&tab=tab_5&&'.$otpChecker.'">';
    
	}
}
?>



<?php
if (isset($_POST['confirm']))
{
    
    //include("../config/walletafrica_restfulapis_call.php");
    include("../config/intlrestful_apicalls.php");
    
    $result = array();
    $result1 = array();
    $result2 = array();
    $myotp = $_POST['otp'];
    
    $verify_otp = mysqli_query($link, "SELECT * FROM otp_confirmation WHERE otp_code = '$myotp' AND userid = '$vuid' AND status = 'Pending'");
	$fetch_data = mysqli_fetch_array($verify_otp);
	$otpnum = mysqli_num_rows($verify_otp);
	
	if($otpnum == 0){
						        
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
        $defaultCustomerBal = $parameter[8];
        $calcCharges = $amountWithCharges - $amountWithNoCharges;
            
        //Get my wallet balance after debiting
        $senderBalance = $vtransfer_balance - $amountWithCharges;
        $senderEmail = $vo_email;
        $senderName = $vc_name;
        $merchantName = $mvcname;

        $transactionDateTime = date("Y-m-d h:i:s");
        //9 rows [0 - 9]
        $dataToProcess = $acctbank."|".$recipientAcctNo."|".$amountWithNoCharges."|".$rave_secret_key."|".$mnarration."|".$currency."|".$tReference."|".$accountName."|".$vcurrency."|".$vtransfer_balance;
        //insert txt waiting list
        $mytxtstatus = 'Pending';
        ($amountWithCharges > $vtransfer_balance) ? "" : mysqli_query($link, "INSERT INTO api_txtwaitinglist VALUE(null,'$vuid','$tReference','$dataToProcess','$mytxtstatus','$transactionDateTime')");
        
        //Debit Customer Wallet
        ($amountWithCharges > $vtransfer_balance) ? "" : mysqli_query($link, "UPDATE user SET transfer_balance = '$senderBalance' WHERE id = '$vuid' AND created_by = '$vcreated_by'");
            
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
            "debit_currency"    =>  $vcurrency
        );
            
        ($amountWithCharges > $vtransfer_balance) ? "" : $make_call1 = intlCallAPI('POST', $api_url1, json_encode($postdata1));
        ($amountWithCharges > $vtransfer_balance) ? "" : $result1 = json_decode($make_call1, true);
            
        if($result['status'] == "success" && $vtransfer_balance >= $amountWithCharges){
                
            $transfer_id = $result['data']['id'];
            $transfers_fee = "Gateway Fee: ".$vcurrency.$result['data']['fee']." | Inhouse Fee: ".$vcurrency.$calcCharges;
            $mybank_name = $result['data']['bank_name'];
            $status = $result['data']['status'];
            $recipient = $recipientAcctNo.', '.$accountName.', '.$acctbank.', '.$mybank_name;

            //UPDATE WAITING TXT
            mysqli_query($link, "UPDATE api_txtwaitinglist SET status = '$make_call1' WHERE userid = '$vuid' AND refid = '$tReference' AND status = '$mytxtstatus'");
                
            //Log successful transaction history
            mysqli_query($link, "INSERT INTO wallet_history VALUE(null,'$vcreated_by','$tReference','$recipient','','$amountWithNoCharges','Debit','$currency','BANK_TRANSFER','Transfer to $recipient','successful','$transactionDateTime','$vuid','$senderBalance','Flutterwave')");
            mysqli_query($link, "INSERT INTO wallet_history VALUE(null,'$vcreated_by','$tReference','$recipient','','$calcCharges','Debit','$currency','Charges','Transfer to $recipient, Narration: $mnarration','successful','$transactionDateTime','$vuid','$senderBalance','Flutterwave')");

            $sendSMS->bankTransferEmailNotifier($senderEmail, $tReference, $senderName, $transactionDateTime, $accountName, $recipientAcctNo, $mybank_name, $merchantName, $vcurrency, $amountWithNoCharges, $senderBalance, $vemailConfigStatus, $vfetch_emailConfig);

            mysqli_query($link, "DELETE FROM otp_confirmation WHERE userid = '$vuid' AND otp_code = '$myotp' AND status = 'Pending'");
                
            echo "<div class='alert bg-blue'>Transfer Initiated Successful! Current Wallet Balance is: <b style='color: white;'>".$bbcurrency.number_format($senderBalance,2,'.',',')."</b></div>";
        	echo '<meta http-equiv="refresh" content="3;url=transfer_fund.php?id='.$_SESSION['tid'].'&&mid=NDA0&&tab=tab_5">';
                
        }
        else{

            $myWaitingList = mysqli_query($link, "SELECT * FROM api_txtwaitinglist WHERE userid = '$vuid' AND refid = '$tReference' AND status = '$mytxtstatus'");
            $fetchMyWaitingList = mysqli_fetch_array($myWaitingList);
            $myWaitingData = $fetchMyWaitingList['mydata'];

            $myParameter = (explode('|',$myWaitingData));
            $defaultBalance = $myParameter[9];
            
            //Reverse to Customer Wallet
            mysqli_query($link, "UPDATE user SET transfer_balance = '$defaultBalance' WHERE id = '$vuid' AND created_by = '$vcreated_by'");
            
            //UPDATE WAITING TXT
            mysqli_query($link, "UPDATE api_txtwaitinglist SET status = '$make_call1' WHERE userid = '$vuid' AND refid = '$tReference' AND status = '$mytxtstatus'");

            mysqli_query($link, "DELETE FROM otp_confirmation WHERE userid = '$vuid' AND otp_code = '$myotp' AND status = 'Pending'");
            
            echo "<div class='alert bg-orange'>Sorry! Unable to conclude transaction please try again later!!</div>";
            echo '<meta http-equiv="refresh" content="3;url=transfer_fund.php?id='.$_SESSION['tid'].'&&mid=NDA0&&tab=tab_5">';
            
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
                    <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Reason(s)</label>
                    <div class="col-sm-6">
                        <textarea name="reasons" class="form-control" placeholder="State Reasons for Transfer e.g. For House rent, Pay Bonus etc." rows="2" cols="5" required></textarea>
                    </div>
                    <label for="" class="col-sm-3 control-label"></label>
                </div>

            </div>
            
            <div class="form-group" align="right">
                <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;"></label>
                <div class="col-sm-6">
                    <button name="ugandan_save" type="submit" class="btn bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?> btn-flat"><i class="fa fa-forward">&nbsp;Initiate Transfer</i></button>
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
  elseif($tab == 'tab_6')
  {
  ?>

    <!--
      <div class="tab-pane <?php echo ($_GET['tab'] == 'tab_6') ? 'active' : ''; ?>" id="tab_6">

             <div class="box-body">
           
       <form class="form-horizontal" method="post" enctype="multipart/form-data">


<?php
if(isset($_POST['savedb_save']))
{
    include("../config/restful_apicalls.php");
    
    $reference = date("yd").time();
    $recipient_id =  mysqli_real_escape_string($link, $_POST['recipient_id']);
    $search_recipient = mysqli_query($link, "SELECT * FROM transfer_recipient WHERE recipient_id = '$recipient_id'");
    $fetch_recipient = mysqli_fetch_array($search_recipient);
    //BANK DETAILS
    $account_number =  $fetch_recipient['acct_no'];
    $bank_code =  $fetch_recipient['bank_code'];
	$b_name = $fetch_recipient['bank_name'];
	$amount = preg_replace('/[^0-9.]/', '', mysqli_real_escape_string($link, $_POST['amt_totransfer']));
	$afnarration =  mysqli_real_escape_string($link, $_POST['reasons']);
	$phone = $vo_phone;
    $currenctdate = date("Y-m-d H:i:s");
    
    $search_restapi1 = mysqli_query($link, "SELECT * FROM restful_apisetup WHERE api_name = 'sterling_InterbankNameEnquiry'");
    $fetch_restapi1 = mysqli_fetch_object($search_restapi1);
    $client = $fetch_restapi1->api_url;

    ($vnip_route == "SterlingBank") ? require_once '../config/nipBankTransfer_class.php' : "";

    $data_to_send_server = array(
        "Referenceid"=>date("ymi").time(),
        "RequestType"=>161,
        "Translocation"=>"100,100",
        "ToAccount"=>$account_number,
        "DestinationBankCode"=>$bank_code
    );

    ($vnip_route == "SterlingBank") ? $process = $new->sterlingNIPBankTransfer($data_to_send_server,$client) : "";
    
    ($vnip_route == "SterlingBank") ? $processReturn = $process['data'] : "";

    $NEStatus = ($vnip_route == "SterlingBank") ? $processReturn['status'] : "";
    $SessionID = ($vnip_route == "SterlingBank") ? $processReturn['sessionID'] : "";
    $senderAcctNo = ($vnip_route == "SterlingBank") ? $processReturn['AccountNumber'] : "";
	
	$query = mysqli_query($link, "SELECT * FROM systemset") or die (mysqli_error($link));
    $r = mysqli_fetch_object($query);
    $transfer_charges = ($bank_transferCharges == "") ? $r->transfer_charges : $bank_transferCharges;
    $sysabb = $mvsenderid;
    
    $otp_code = ($votp_option == "Yes" || $votp_option == "Both") ? substr((uniqid(rand(),1)),3,6) : $myvepin;
    
    $otpChecker = ($votp_option == "Yes" || $votp_option == "Both") ? "otp" : "pin";
	
	$sms = "$sysabb>>>Dear $vuname! Your One Time Password is $otp_code";
    
    $sms_rate = $r->fax;
    $vmywallet_balance = $vwallet_balance - $sms_rate;
    $sms_refid = "EA-smsCharges-".time();
	
	//New AMount + Charges
    $newAmount = $amount + $transfer_charges;
	
	//Data Parser (array size = 6)
    $mydata = $reference."|".$bank_code."|".$account_number."|".$b_name."|".$amount."|".$newAmount."|".$afnarration."|".$SessionID."|".$NEStatus."|".$senderAcctNo."|".$vtransfer_balance;
    
    $key = base64_encode($mydata);

    if($vnip_route == ""){

        echo "<div class='alert bg-orange'>Sorry! Service not active, please contact the administrator to activate this features!!</div>";

    }
    elseif($vactive_status == "Suspended"){
        
        echo "<div class='alert bg-orange'>Oops! You are unable to make transfer at the moment!!</div>";
        
    }
    elseif($amount <= 0){

        echo "<div class='alert bg-orange'>Sorry! Invalid amount entered!!</div>";

    }
	elseif(($vtransfer_balance < $newAmount && ($votp_option == "Yes" || $votp_option == "Both")) || ($vtransfer_balance < $newAmount && $votp_option == "No")){
	    
	    echo "<div class='alert bg-orange'>Oops! You have insufficient fund in your Wallet!!</div>";
      
    }
    elseif($vwallet_balance < $sms_rate && ($votp_option == "Yes" || $votp_option == "Both")){
      
        echo "<div class='alert bg-orange'>Oops! You have insufficient fund in your Wallet to Receive OTP!!</div>";
      
    }
    elseif($vtransferLimitPerTrans > $amount){

        echo "<div class='alert bg-orange'>Sorry! You can not transfer more than ".$vcurrency.number_format($vtransferLimitPerTrans,2,'.',',')." at once!!</div>";

    }
    elseif($vmyDailyTransferLimit == $vtransferLimitPerDay || (($amountWithNoCharges + $vmyDailyTransferLimit) > $vtransferLimitPerDay)){

        echo "<div class='alert bg-orange'>Oops! You have reached your daily limit of ".$vcurrency.number_format($vtransferLimitPerDay,2,'.',',')."</div>";

    }
    elseif($vvirtual_acctno === "$account_number" || $vuid === "$account_number"){
    
        mysqli_query($link, "UPDATE user SET status = 'Suspended' WHERE id = '$vuid'");
        mysqli_query($link, "INSERT INTO wallet_case VALUES(null,'$vcreated_by','','System','$vuid','Suspended','Frudulent Act Detected','$currenctdate')");
        
        echo "<div class='alert bg-orange'>Oops! Request Failed!!</div>";
        
    }
	else{
	    
        ($iotp_option == "No" ? "" : (($iotp_option == "Yes" || $iotp_option == "Both") ? $sendSMS->vendorGeneralAlert($sysabb, $vo_phone, $sms, $vcreated_by, $sms_refid, $sms_rate, $vuid, $vmywallet_balance, $debitWallet, $vendorid) : (($iotp_option == "Yes" || $iotp_option == "Both") && $sms_rate <= $vwallet_balance ? $sendSMS->vendorGeneralAlert($sysabb, $vo_phone, $sms, $vcreated_by, $sms_refid, $sms_rate, $vuid, $vmywallet_balance, $debitWallet, $vendorid) : "")));

        $insert = mysqli_query($link, "INSERT INTO otp_confirmation VALUES(null,'$vuid','$otp_code','$mydata','Pending','$currenctdate')");
            
        echo ($votp_option == "Yes" || $votp_option == "Both") ? "<div align='center'><img src='../image/checkmark.gif'><p style='color: #38A1F3;'>Great! Otp has been sent to your mobile phone to complete this transaction!!</p></div>" : "<div align='center'><img src='../image/checkmark.gif'><p style='color: #38A1F3;'>Transfer Request Received Successfully! Wait a moment to complete the transaction with your Pin</p></div>";
        echo '<meta http-equiv="refresh" content="2;url=transfer_fund.php?id='.$_SESSION['tid'].'&&mid=NDA0&&tab=tab_6&&token='.$key.'&&'.$otpChecker.'">';
        
	}
	
}
?>



<?php
if (isset($_POST['confirm']))
{
    $myotp = $_POST['otp'];
    $token = base64_decode($_GET['token']);
    
    $verify_otp = mysqli_query($link, "SELECT * FROM otp_confirmation WHERE otp_code = '$myotp' AND userid = '$vuid' AND status = 'Pending' AND data = '$token'");
	$fetch_data = mysqli_fetch_array($verify_otp);
	$otpnum = mysqli_num_rows($verify_otp);
						    
	if($otpnum == 0){
						        
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
        $defaultCustomerBal = $parameter[10];
        $currency = $vcurrency;
        $originatorName = $accountName;
	    $tramsferid = substr((uniqid(rand(),1)),3,6);
	    //$totalWalletBalance = $bwallet_balance - $amountWithCharges;
        $calcCharges = $amountWithCharges - $amountWithNoCharges;
        
        //Get my wallet balance after debiting
        $senderBalance = $vtransfer_balance - $amountWithCharges;
        $senderEmail = $vo_email;
        $senderName = $vc_name;
        $merchantName = $mvcname;

        if($vnip_route == "Wallet Africa"){

            //Debit Customer Wallet
            mysqli_query($link, "UPDATE user SET transfer_balance = '$senderBalance' WHERE virtual_number = '$vvirtual_phone_no'");

            //Fetch Bank Name
            $search_bankname = mysqli_query($link, "SELECT * FROM bank_list WHERE bankcode = '$recipientBankCode'");
            $fetch_bankname = mysqli_fetch_array($search_bankname);
            $mybank_name = $fetch_bankname['bankname'];

            $search_restapi1 = mysqli_query($link, "SELECT * FROM restful_apisetup WHERE api_name = 'walletafrica_transfer'");
            $fetch_restapi1 = mysqli_fetch_object($search_restapi1);
            $client = $fetch_restapi1->api_url;

            require_once "../config/nipBankTransfer_class.php";

            $result = $new->walletAfricaNIPBankTransfer($walletafrica_skey,$recipientBankCode,$recipientAcctNo,$accountName,$tReference,$amountWithNoCharges,$client);
            
            $decodePro = json_decode($result, true);

            if($decodePro['ResponseCode'] == "100" || $decodePro['ResponseCode'] == "200" || $result['ResponseCode'] == "100" || $result['ResponseCode'] == "200"){
                
                $gatewayResponse = $decodePro['Message'];
                $transactionDateTime = date("Y-m-d h:i:s");
                $recipient = $recipientAcctNo.', '.$accountName.', '.$mybank_name;
                
                //Log successful transaction history
                mysqli_query($link, "INSERT INTO wallet_history VALUE(null,'$vcreated_by','$tReference','$recipient','','$amountWithNoCharges','Debit','$vcurrency','BANK_TRANSFER','Transfer to $recipient','successful','$transactionDateTime','$vuid','$senderBalance','$vnip_route')");
                mysqli_query($link, "INSERT INTO wallet_history VALUE(null,'$vcreated_by','$tReference','$recipient','','$calcCharges','Debit','$vcurrency','Charges','Transfer to $recipient, Narration: $narration','successful','$transactionDateTime','$vuid','$senderBalance','$vnip_route')");

                $sendSMS->bankTransferEmailNotifier($senderEmail, $tReference, $senderName, $transactionDateTime, $accountName, $recipientAcctNo, $mybank_name, $merchantName, $vcurrency, $amountWithNoCharges, $senderBalance, $vemailConfigStatus, $vfetch_emailConfig);

                mysqli_query($link, "DELETE FROM otp_confirmation WHERE userid = '$vuid' AND otp_code = '$myotp' AND status = 'Pending'");
                    
                echo "<div class='alert bg-blue'>Transaction Successful! Current Transfer Balance is: <b style='color: white;'>".$vcurrency.number_format($senderBalance,2,'.',',')."</b></div>";
                echo '<meta http-equiv="refresh" content="3;url=transfer_fund.php?id='.$_SESSION['tid'].'&&mid=NDA0&&tab=tab_6">';
                    
            }
            else{

                //Reverse to Customer Wallet
                mysqli_query($link, "UPDATE user SET transfer_balance = '$defaultCustomerBal' WHERE virtual_number = '$vvirtual_phone_no'");
                
                mysqli_query($link, "DELETE FROM otp_confirmation WHERE userid = '$vuid' AND otp_code = '$myotp' AND status = 'Pending'");
                   
                echo "<div class='alert bg-orange'>Opps!...Unable to conclude transaction, Please Try again later</div>";
                echo '<meta http-equiv="refresh" content="3;url=transfer_fund.php?tid='.$_SESSION['tid'].'&&mid=NDA0&&tab=tab_6">';
                
            }

        }
        elseif($vnip_route == "ProvidusBank"){

            //Debit Customer Wallet
            mysqli_query($link, "UPDATE user SET transfer_balance = '$senderBalance' WHERE virtual_number = '$vvirtual_phone_no'");

            //Fetch Bank Name
            $search_bankname = mysqli_query($link, "SELECT * FROM bank_list WHERE bankcode = '$recipientBankCode'");
            $fetch_bankname = mysqli_fetch_array($search_bankname);
            $mybank_name = $fetch_bankname['bankname'];

            $search_restapi1 = mysqli_query($link, "SELECT * FROM restful_apisetup WHERE api_name = 'providus_endpoint'");
            $fetch_restapi1 = mysqli_fetch_object($search_restapi1);
            $api_url = $fetch_restapi1->api_url;

            $client = new SoapClient($api_url);

            require_once "../config/nipBankTransfer_class.php";

            $result = $new->providusNIPBankTransfer($providusUName,$providusPass,$amountWithNoCharges,$currency,$narration,$tReference,$recipientAcctNo,$recipientBankCode,$accountName,$originatorName,$client);

            if($result['responseCode'] == "00"){

                $gatewayResponse = $result['responseMessage'];
                $transactionDateTime = date("Y-m-d h:i:s");
                $recipient = $recipientAcctNo.', '.$accountName.', '.$mybank_name;
                    
                //Log successful transaction history
                mysqli_query($link, "INSERT INTO wallet_history VALUE(null,'$vcreated_by','$tReference','$recipient','','$amountWithNoCharges','Debit','$vcurrency','BANK_TRANSFER','Transfer to $recipient','successful','$transactionDateTime','$vuid','$senderBalance','$vnip_route')");
                mysqli_query($link, "INSERT INTO wallet_history VALUE(null,'$vcreated_by','$tReference','$recipient','','$calcCharges','Debit','$vcurrency','Charges','Transfer to $recipient, Narration: $narration','successful','$transactionDateTime','$vuid','$senderBalance','$vnip_route')");

                $sendSMS->bankTransferEmailNotifier($senderEmail, $tReference, $senderName, $transactionDateTime, $accountName, $recipientAcctNo, $mybank_name, $merchantName, $vcurrency, $amountWithNoCharges, $senderBalance, $vemailConfigStatus, $vfetch_emailConfig);

                mysqli_query($link, "DELETE FROM otp_confirmation WHERE userid = '$vuid' AND otp_code = '$myotp' AND status = 'Pending'");
                    
                echo "<div class='alert bg-blue'>Transaction Successful! Current Transfer Balance is: <b style='color: white;'>".$vcurrency.number_format($senderBalance,2,'.',',')."</b></div>";
                echo '<meta http-equiv="refresh" content="3;url=transfer_fund.php?id='.$_SESSION['tid'].'&&mid=NDA0&&tab=tab_6">';

            }
            else{

                //Reverse to Customer Wallet
                mysqli_query($link, "UPDATE user SET transfer_balance = '$defaultCustomerBal' WHERE virtual_number = '$vvirtual_phone_no'");
                
                mysqli_query($link, "DELETE FROM otp_confirmation WHERE userid = '$vuid' AND otp_code = '$myotp' AND status = 'Pending'");
                   
                echo "<div class='alert bg-orange'>Opps!...Unable to conclude transaction, Please Try again later</div>";
                echo '<meta http-equiv="refresh" content="3;url=transfer_fund.php?tid='.$_SESSION['tid'].'&&mid=NDA0&&tab=tab_6">';

            }

        }elseif($vnip_route == "SterlingBank"){

            //Debit Customer Wallet
            mysqli_query($link, "UPDATE user SET transfer_balance = '$senderBalance' WHERE virtual_number = '$vvirtual_phone_no'");

            //Fetch Bank Name
            $search_bankname = mysqli_query($link, "SELECT * FROM bank_list3 WHERE bankcode = '$recipientBankCode'");
            $fetch_bankname = mysqli_fetch_array($search_bankname);
            $mybank_name = $fetch_bankname['bankname'];

            $search_restapi1 = mysqli_query($link, "SELECT * FROM restful_apisetup WHERE api_name = 'sterling_InterbankTransferReq'");
            $fetch_restapi1 = mysqli_fetch_object($search_restapi1);
            $client = $fetch_restapi1->api_url;

            require_once "../config/nipBankTransfer_class.php";

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
               $transactionDateTime = date("Y-m-d h:i:s");
               $recipient = $recipientAcctNo.', '.$accountName.', '.$mybank_name;
                   
               //Log successful transaction history
               mysqli_query($link, "INSERT INTO wallet_history VALUE(null,'$vcreated_by','$tReference','$recipient','','$amountWithNoCharges','Debit','$vcurrency','BANK_TRANSFER','Transfer to $recipient','successful','$transactionDateTime','$vuid','$senderBalance','$vnip_route')");
               mysqli_query($link, "INSERT INTO wallet_history VALUE(null,'$vcreated_by','$tReference','$recipient','','$calcCharges','Debit','$vcurrency','Charges','Transfer to $recipient, Narration: $narration','successful','$transactionDateTime','$vuid','$senderBalance','$vnip_route')");

               $sendSMS->bankTransferEmailNotifier($senderEmail, $tReference, $senderName, $transactionDateTime, $accountName, $recipientAcctNo, $mybank_name, $merchantName, $vcurrency, $amountWithNoCharges, $senderBalance, $vemailConfigStatus, $vfetch_emailConfig);
               
               mysqli_query($link, "DELETE FROM otp_confirmation WHERE userid = '$vuid' AND otp_code = '$myotp' AND status = 'Pending'");
                   
               echo "<div class='alert bg-blue'>Transaction Successful! Current Transfer Wallet Balance is: <b style='color: white;'>".$vcurrency.number_format($senderBalance,2,'.',',')."</b></div>";
               echo '<meta http-equiv="refresh" content="3;url=transfer_fund.php?id='.$_SESSION['tid'].'&&mid=NDA0&&tab=tab_1">';

           }elseif($processReturn['status'] == "x02"){

                //Reverse to Customer Wallet
                mysqli_query($link, "UPDATE user SET transfer_balance = '$defaultCustomerBal' WHERE virtual_number = '$vvirtual_phone_no'");

               mysqli_query($link, "DELETE FROM otp_confirmation WHERE userid = '$vuid' AND otp_code = '$myotp' AND status = 'Pending'");
                
               echo "<div class='alert bg-orange'>".$processReturn['ResponseText']."</div>";
               echo '<meta http-equiv="refresh" content="3;url=transfer_fund.php?tid='.$_SESSION['tid'].'&&mid=NDA0&&tab=tab_1">';

           }
           else{

                //Reverse to Customer Wallet
                mysqli_query($link, "UPDATE user SET transfer_balance = '$defaultCustomerBal' WHERE virtual_number = '$vvirtual_phone_no'");

               mysqli_query($link, "DELETE FROM otp_confirmation WHERE userid = '$vuid' AND otp_code = '$myotp' AND status = 'Pending'");
                
               echo "<div class='alert bg-orange'>Opps!...Unable to conclude transaction, Please Try again later</div>";
               echo '<meta http-equiv="refresh" content="3;url=transfer_fund.php?tid='.$_SESSION['tid'].'&&mid=NDA0&&tab=tab_1">';

           }

        }
        else{

            //Reverse to Customer Wallet
            mysqli_query($link, "UPDATE user SET transfer_balance = '$defaultCustomerBal' WHERE virtual_number = '$vvirtual_phone_no'");

            mysqli_query($link, "DELETE FROM otp_confirmation WHERE userid = '$vuid' AND otp_code = '$myotp' AND status = 'Pending'");
                   
            echo "<div class='alert bg-orange'>Opps!...Service not available at the moment!!</div>";
            echo '<meta http-equiv="refresh" content="3;url=transfer_fund.php?tid='.$_SESSION['tid'].'&&mid=NDA0&&tab=tab_6">';

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
                    <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Select Recipient</label>
                    <div class="col-sm-6">
                        <select name="recipient_id"  class="form-control select2" required style="width:100%">
                            <option value="" selected>Select Transfer Recipient</option>
                            <?php
                            $get = mysqli_query($link, "SELECT * FROM transfer_recipient WHERE companyid = '$vcreated_by' ORDER BY id") or die (mysqli_error($link));
                            while($rows = mysqli_fetch_array($get))
                            {
                            ?>
                            <option value="<?php echo $rows['recipient_id']; ?>"><?php echo $rows['full_name'].' ('.$rows['acct_no'].')'; ?></option>
                            <?php } ?>        
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
                    <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Reason(s)</label>
                    <div class="col-sm-6">
                        <textarea name="reasons" class="form-control" placeholder="State Reasons for Transfer e.g. For House rent, Pay Bonus etc." rows="2" cols="5" required></textarea>
                    </div>
                    <label for="" class="col-sm-3 control-label"></label>
                </div>
      
            </div>
       
            <div class="form-group" align="right">
                <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;"></label>
                <div class="col-sm-6">
                    <button name="savedb_save" type="submit" class="btn bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?> btn-flat"><i class="fa fa-forward">&nbsp;Initiate Transfer</i></button>
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
  elseif($tab == 'tab_7')
  {
  ?>
      
      <div class="tab-pane <?php echo ($_GET['tab'] == 'tab_7') ? 'active' : ''; ?>" id="tab_7">

             <div class="box-body">
           
       <form class="form-horizontal" method="post" enctype="multipart/form-data">


<?php
if(isset($_POST['savedbulk_save']))
{
    include("../config/restful_apicalls.php");
    
    $t_title =  mysqli_real_escape_string($link, $_POST['t_title']);
	$phone = $vo_phone;
	$currenctdate = date("Y-m-d H:i:s");
	
	$query = mysqli_query($link, "SELECT * FROM systemset") or die (mysqli_error($link));
    $r = mysqli_fetch_object($query);
    $transfer_charges = ($bank_transferCharges == "") ? $r->transfer_charges : $bank_transferCharges;
    $sysabb = $mvsenderid;
    
    $otp_code = ($votp_option == "Yes" || $votp_option == "Both") ? substr((uniqid(rand(),1)),3,6) : $myvepin;
    
    $otpChecker = ($votp_option == "Yes" || $votp_option == "Both") ? "otp" : "pin";

    $sms = "$sysabb>>>Dear $vuname! Your One Time Password is $otp_code";
    
	//SMS DATA
	$search_gateway = mysqli_query($link, "SELECT * FROM sms WHERE smsuser = '' AND status = 'Activated'");
    $fetch_gateway = mysqli_fetch_object($search_gateway);
    $gateway_uname = $fetch_gateway->username;
    $gateway_pass = $fetch_gateway->password;
    $gateway_api = $fetch_gateway->api;
    
    $sms_rate = $r->fax;
    $vmywallet_balance = $vwallet_balance - $sms_rate;
    $sms_refid = "EA-smsCharges-".time();
    
    if($vnip_route == ""){

        echo "<div class='alert bg-orange'>Sorry! Service not active yet, please contact our support to activate this features!!</div>";

    }
    elseif($vactive_status == "Suspended"){
        
        echo "<div class='alert bg-orange'>Oops! You are unable to make transfer at the moment!!</div>";
        
    }
	elseif($vwallet_balance < $sms_rate && ($votp_option == "Yes" || $votp_option == "Both")){
                  
        echo "<div class='alert bg-orange'>Oops! You have insufficient fund in your Wallet to Receive OTP!!</div>";
                  
    }
	elseif($_FILES['file']['name']){
	    
	    $filename = explode('.', $_FILES['file']['name']);
	        
	    if($filename[1] == 'csv'){
	        
	        $handle = fopen($_FILES['file']['tmp_name'], "r");
            fgetcsv($handle,1000,","); // pop the headers
            while($data = fgetcsv($handle,1000,",")){
                
                $reference = date("yd").time();
                $bankcode = mysqli_real_escape_string($link, $data[0]);
                $accountNumber = mysqli_real_escape_string($link, $data[1]);
                $amountWithNoCharges = preg_replace('/[^0-9.]/', '', mysqli_real_escape_string($link, $data[2]));
                $accountName = mysqli_real_escape_string($link, $data[3]);
                    
                $searchbank_list = mysqli_query($link, "SELECT * FROM bank_list WHERE bankcode = '$bankcode'");
                $fetchbank_list = mysqli_fetch_array($searchbank_list);
                $bankName = $fetchbank_list['bankname'];

                ($vnip_route == "Wallet Africa") ? $searchbank_list = mysqli_query($link, "SELECT * FROM bank_list WHERE bankcode = '$bankcode'") : "";
                ($vnip_route != "Wallet Africa") ? $searchbank_list = mysqli_query($link, "SELECT * FROM bank_list3 WHERE bankcode = '$bankcode'") : "";
                $fetchbank_list = mysqli_fetch_array($searchbank_list);
                $bankName = $fetchbank_list['bankname'];

                $search_restapi1 = mysqli_query($link, "SELECT * FROM restful_apisetup WHERE api_name = 'sterling_InterbankNameEnquiry'");
                $fetch_restapi1 = mysqli_fetch_object($search_restapi1);
                $client = $fetch_restapi1->api_url;

                ($vnip_route == "SterlingBank") ? require_once '../config/nipBankTransfer_class.php' : "";

                $data_to_send_server = array(
                    "Referenceid"=>date("ymi").time(),
                    "RequestType"=>161,
                    "Translocation"=>"100,100",
                    "ToAccount"=>$accountNumber,
                    "DestinationBankCode"=>$bankcode
                );

                ($vnip_route == "SterlingBank") ? $process = $new->sterlingNIPBankTransfer($data_to_send_server,$client) : "";
                
                ($vnip_route == "SterlingBank") ? $processReturn = $process['data'] : "";

                $NEStatus = ($vnip_route == "SterlingBank") ? $processReturn['status'] : "";
                $SessionID = ($vnip_route == "SterlingBank") ? $processReturn['sessionID'] : "";
                $senderAcctNo = ($vnip_route == "SterlingBank") ? $processReturn['AccountNumber'] : "";
                	
                //New AMount + Charges
                $newAmount = $amountWithNoCharges + $transfer_charges;
                    
                //Data Parser (array size = 6)
                $mydata = $reference."|".$bankcode."|".$accountNumber."|".$bankName."|".$amountWithNoCharges."|".$newAmount."|".$t_title."|".$SessionID."|".$NEStatus."|".$senderAcctNo;
                
                if($amountWithNoCharges <= 0){

                    echo "<div class='alert bg-orange'>Sorry! Invalid amount entered!!</div>";
            
                }
                elseif(($vtransfer_balance < $newAmount && ($votp_option == "Yes" || $votp_option == "Both")) || ($vtransfer_balance < $newAmount && $votp_option == "No")){
	    
            	    exit("Oops! You have insufficient fund in your Wallet to Proceed with others!!");
                  
                }
                elseif($vvirtual_acctno === "$accountNumber" || $vuid === "$accountNumber"){
    
                    mysqli_query($link, "UPDATE user SET status = 'Suspended' WHERE id = '$vuid'");
                    mysqli_query($link, "INSERT INTO wallet_case VALUES(null,'$vcreated_by','','System','$vuid','Suspended','Frudulent Act Detected','$currenctdate')");
                    
                    echo "<div class='alert bg-orange'>Oops! Request Failed!!</div>";
                    
                }
                else{
                    
                    $prevent_duplicate = mysqli_query($link, "SELECT * FROM otp_confirmation WHERE data = '$mydata' AND status = 'Pending' AND userid = '$vuid'");
                    
                    if(mysqli_num_rows($prevent_duplicate) == 1){
                        
                        echo "";
                        
                    }
                    else{
                        
                        $sql = "INSERT INTO otp_confirmation(id,userid,otp_code,data,status,datetime) VALUES(null,'$vuid','$otp_code','$mydata','Pending','$currenctdate')";
                        $result = mysqli_query($link,$sql);
                        
                        if(!$result)
            			{
            				echo "<script type=\"text/javascript\">
            					alert(\"Invalid File:Please Upload CSV File.\");
            				    </script>".mysqli_error($link);
            			}
                        
                    }
                    
                }
                
            }
            fclose($handle);
            
            ($iotp_option == "No" ? "" : (($iotp_option == "Yes" || $iotp_option == "Both") ? $sendSMS->vendorGeneralAlert($sysabb, $vo_phone, $sms, $vcreated_by, $sms_refid, $sms_rate, $vuid, $vmywallet_balance, $debitWallet, $vendorid) : (($iotp_option == "Yes" || $iotp_option == "Both") && $sms_rate <= $vwallet_balance ? $sendSMS->vendorGeneralAlert($sysabb, $vo_phone, $sms, $vcreated_by, $sms_refid, $sms_rate, $vuid, $vmywallet_balance, $debitWallet, $vendorid) : "")));
            
            echo ($votp_option == "Yes" || $votp_option == "Both") ? "<div align='center'><img src='../image/checkmark.gif'><p style='color: #38A1F3;'>Great! Otp has been sent to your mobile phone to complete this transaction!!</p></div>" : "<div align='center'><img src='../image/checkmark.gif'><p style='color: #38A1F3;'>Transfer Request Received Successfully! Wait a moment to complete the transaction with your Pin</p></div>";
            echo '<meta http-equiv="refresh" content="2;url=transfer_fund.php?id='.$_SESSION['tid'].'&&mid=NDA0&&tab=tab_6&&'.$otpChecker.'">';
            
	    }
	    
	}
	
}
?>


<?php
if (isset($_POST['confirm']))
{
    
    //include("../config/walletafrica_restfulapis_call.php");
    $result = array();
    $myotp = $_POST['otp'];
    
    $verify_otp = mysqli_query($link, "SELECT * FROM otp_confirmation WHERE otp_code = '$myotp' AND userid = '$vuid' AND status = 'Pending'");
	$fetch_data = mysqli_fetch_array($verify_otp);
	$otpnum = mysqli_num_rows($verify_otp);
						    
	if($otpnum == 0){
						        
	    echo "<div class='alert bg-orange'>Opps!...Invalid Credential!!</div>";
						        
	}else{
	    while($fetch_data = mysqli_fetch_array($verify_otp)){
	        
	        $rowId = $fetch_data['id'];
	        
	        $concat = $fetch_data['data'];
    
            $datetime = $fetch_data['datetime'];
                                
            $parameter = (explode('|',$concat));
        
            $reference = $parameter[0];
            
            $amountWithCharges = $parameter[5];
	    
    	    //Get my wallet balance after debiting
            $senderBalance = $vtransfer_balance - $amountWithCharges;
            
            //mysqli_query($link, "UPDATE user SET transfer_balance = '$senderBalance' WHERE virtual_number = '$vvirtual_phone_no'");
            mysqli_query($link, "UPDATE otp_confirmation SET status = 'batchList' WHERE userid = '$vuid' AND otp_code = '$myotp' AND status = 'Pending' AND id = '$rowId'");
	    
	    }
	    echo "<script type=\"text/javascript\">
						alert(\"Bulk Transfer made successfully.\");
					</script>";
		echo "<script>window.location='transfer_fund.php?id=".$_SESSION['tid']."&&mid=NDA0&&tab=tab_7'";
	}
    
}
?>



<?php
if(!(isset($_GET['otp']) || isset($_GET['pin'])))
{
?>

             <div class="box-body">
                 
                 <div class="form-group">
                    <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Transfer Title</label>
                    <div class="col-sm-6">
                        <input name="t_title" type="text" id="act_numb" class="form-control" placeholder="Enter Transfer Title e.g. May Staff Salary">
                        <span style="color: <?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>;">This section should carry the title of the transfer as described in the Text Field</span>
                    </div>
                    <label for="" class="col-sm-3 control-label"></label>
                  </div>
                  
                  <div class="form-group">
                    <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Upload File</label>
                    <div class="col-sm-6">
                        <input type='file' name="file" accept=".csv" class="btn bg-orange" required/>
                            <hr>
                          <p style="color:<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>"><b style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">NOTE:</b><br>
                            <span style="color:blue;">(1)</span> <i>Download the <a href="../sample/disburse_sample.csv"> <b class="btn bg-blue"><i class="fa fa-download"></i> disburse sample file</b></a> and attach it once you're done replacing those data with all your bank info.</i></p>
                            <span style="color:blue;">(2)</span> <span style="color:<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>"><i>Download the <a href="../sample/banks.csv"> <b class="btn bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>"><i class="fa fa-download"></i> List of Banks</b></a></i></span>
                    </div>
                    <label for="" class="col-sm-3 control-label"></label>
                  </div>
      
            </div>
            
            <div class="form-group" align="right">
                <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;"></label>
                <div class="col-sm-6">
                    <button name="savedbulk_save" type="submit" class="btn bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?> btn-flat"><i class="fa fa-upload">&nbsp;Initiate Transfer</i></button>
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

    -->

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
                        <span style="color: <?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>;">NOTE: Pease do not put comma (,) while entering the Amount to Convert</span>
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
      <!-- /.tab-pane -->
  
  <?php
  }
  elseif($tab == 'tab_9'){
      $search_payvice = mysqli_query($link, "SELECT * FROM payvice_accesspoint");
	  $payvice_num = mysqli_num_rows($search_payvice);
      ?>
      
      
      
      <div class="tab-pane <?php echo ($_GET['tab'] == 'tab_9') ? 'active' : ''; ?>" id="tab_9">

        <div class="box-body">

          <form class="form-horizontal" method="post" enctype="multipart/form-data">

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
                        <span style="color: <?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>;">Enter amount you want to transfer from your <b>Transfer Balance</b> to your <b>Payvice Wallet</b></span>
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
                    <button name="Payvice_transfer" type="submit" class="btn bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?> btn-flat" <?php echo ($payvice_num == 1) ? "" : "disabled"; ?>><i class="fa fa-forward">&nbsp;Transfer Fund</i></button>
                </div>
                <label for="" class="col-sm-3 control-label"></label>
             </div>

      <?php
if(isset($_POST['Payvice_transfer']))
{
    include("../config/restful_apicalls.php");
    
    $result = array();
    $ptype = "wallet2pv-transfer";
    $beneficiary =  mysqli_real_escape_string($link, $_POST['beneficiary']);
    $amt_totransfer =  mysqli_real_escape_string($link, $_POST['amt_totransfer']);
    $tpin =  mysqli_real_escape_string($link, $_POST['tpin']);
    //$myaccount = $_SESSION['acctno'];
    $date_time = date("Y-m-d");
    $final_date_time = date ('Y-m-d h:i:s', strtotime($date_time));
   
    $systemset = mysqli_query($link, "SELECT * FROM systemset");
	$row1 = mysqli_fetch_object($systemset);
	$seckey = $row1->secret_key;
	$vat_rate = $row1->vat_rate;
	$transfer_charges = $row1->transfer_charges;
	
	$newAmount = ($vat_rate * $amt_totransfer) + $amt_totransfer + $transfer_charges;
	
	$remainBalance = $vwallet_balance - $newAmount;
	
	$payvice = mysqli_query($link, "SELECT * FROM payvice_accesspoint");
	$row_payvice = mysqli_fetch_object($payvice);
	$pv_walletid = $row_payvice->pv_walletid;
	$pv_username = $row_payvice->pv_username;
	$pv_tpin = $row_payvice->pv_tpin;
	$pv_password = $row_payvice->pv_password;
	
	if($vwallet_balance < $newAmount){
	    
	    echo "<script>alert('Insufficient Fund in your Wallet!!'); </script>";
	    
		echo "<script>window.location='transfer_fund.php?id=".$_SESSION['tid']."&&mid=NDA0&&tab=tab_9'; </script>";
		
	}
	elseif($tpin != $myepin){
	    echo "<script>alert('Oops!...Invalid Transaction Pin!!'); </script>";
	    
		echo "<script>window.location='transfer_fund.php?id=".$_SESSION['tid']."&&mid=NDA0&&tab=tab_9'; </script>";
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
		    //$insert = mysqli_query($link, "INSERT INTO transaction VALUES(null,'$txn_ref','Transfer','Wallet-to-Wallet','$acctno','----','$myfn','$myln','$email2','$phone2','$amt_totransfer','System','Transfer from Esusu Super Wallet to Payvice Wallet',NOW(),'$bbranchid','$bsbranchid')");
		    $txn_ref = $result['txn_ref'];
		    $insert = mysqli_query($link, "INSERT INTO wallet_history VALUES(null,'$vcreated_by','$txn_ref','Payvice ID - $beneficiary','','$amt_totransfer','Debit','$vcurrency','$ptype','Transfer from Agent Wallet to Payvice Wallet','successful','$final_date_time','$vuid','$remainBalance','')") or die (mysqli_error($link));

		    $update_records = mysqli_query($link, "UPDATE institution_data SET wallet_balance = '$remainBalance' WHERE institution_id = '$vcreated_by'");
		    
		    echo "<div class='alert bg-orange'>".$result['message']."</div>";
		    echo "<script>window.location='transfer_fund.php?id=".$_SESSION['tid']."&&mid=NDA0&&tab=tab_9'; </script>";
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
  elseif($tab == 'tab_10'){
      ?>
      
      <div class="tab-pane <?php echo ($_GET['tab'] == 'tab_10') ? 'active' : ''; ?>" id="tab_10">

        <div class="box-body">

          <form class="form-horizontal" method="post" enctype="multipart/form-data">

<?php
if(isset($_POST['tbsave']))
{
  function myreference($limit)
  {
    return substr(base_convert(sha1(uniqid(mt_rand())), 16, 36), 0, $limit);
  }
  try{
    $ptype = "w2tb-transfer";
    $account =  mysqli_real_escape_string($link, $_POST['author']);
    $amount = mysqli_real_escape_string($link, $_POST['amount_tofund']);
    $remark = mysqli_real_escape_string($link, $_POST['remark']);
    //$remark .= "<br><b>Posted by:<br>".$name.'</b>';
    $date_time = date("Y-m-d");
    $final_date_time = date ('Y-m-d h:i:s', strtotime($date_time));
    $txid = 'EA-p2tbFunding-'.myreference(10);
    $tpin = mysqli_real_escape_string($link, $_POST['tpin']);
    $remain_merchantbalance = $vwallet_balance - $amount;

    $search_memset = mysqli_query($link, "SELECT * FROM member_settings WHERE companyid = '$vcreated_by'");
    $fetch_memset = mysqli_fetch_array($search_memset);
    //$sys_abb = $get_sys['abb'];
    $mycname = $fetch_memset['cname'];
    $mycphone = $inst_phone;
    
    $search_cbalance = mysqli_query($link, "SELECT * FROM user WHERE userid = '$account'");
    $fetch_cbalance = mysqli_fetch_object($search_cbalance);
    $cust_wallet_balance = $fetch_cbalance->transfer_balance;
    $totalwallet_balance = $cust_wallet_balance + $amount;
    //  $ph = $fetch_cbalance->phone;
    $em = $fetch_cbalance->email;
    $myname = $fetch_cbalance->name;
      
    if($amount < 0){
      throw new UnexpectedValueException();
    }
    elseif(preg_match('/^[0-9]+(?:\.[0-9]{0,2})?$/', $amount) == FALSE)
    {
      throw new UnexpectedValueException();
    }
    elseif($vwallet_balance < $amount){
        echo "<div class='alert bg-orange'>Oops! You have insufficient fund in your Wallet!!</div>";
    }
    elseif($tpin != $myvepin){
	    echo "<script>alert('Oops! Invalid Transaction Pin!'); </script>";
	}
    else{
      
      $insert = mysqli_query($link, "INSERT INTO wallet_history VALUES(null,'$vcreated_by','$txid','$account','','$amount','Debit','$vcurrency','$ptype','$remark','tbPending','$final_date_time','$vuid','$remain_merchantbalance','')") or die (mysqli_error($link));
      $update_inst = mysqli_query($link, "UPDATE institution_data SET wallet_balance = '$remain_merchantbalance' WHERE institution_id = '$vcreated_by'") or die (mysqli_error($link));
      
      if(!($update_inst && $insert))
      {
        echo "<div class='alert bg-orange'>Unable to Move Fund.....Please try again later</div>";
      }
      else{
        //include("alert_sender/p2p_alert.php");
        include("../cron/send_sp2tbtransfer_alertemail.php");
        echo "<div align='center'><img src='../image/checkmark.gif'><p style='color: #38A1F3;'>Transaction Successful!</p><p style='color: #38A1F3;'>Current Wallet Balance: <b style='color: orange;'>".$currency.number_format($totalwallet_balance,2,'.',',')."</b></p></div>";
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
                    <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Select Account</label>
                    <div class="col-sm-6">
                        <select name="author"  class="form-control select2" required>
                          <option value="" selected>Select Account</option>
                            <?php
                            $search = mysqli_query($link, "SELECT * FROM user WHERE created_by = '$vcreated_by'");
                            while($get_search = mysqli_fetch_array($search))
                            {
                            ?>
                            <option value="<?php echo $get_search['userid']; ?>"><?php echo $get_search['name']; ?> : [<?php echo $vcurrency.number_format($get_search['transfer_balance'],2,'.',','); ?>]</option>
                            <?php } ?>
                        </select>
                    </div>
                    <label for="" class="col-sm-3 control-label"></label>
                </div>
                
                <div class="form-group">
                    <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Amount to Fund</label>
                    <div class="col-sm-6">
                        <input name="amount_tofund" type="text" class="form-control" placeholder="Enter Amount Here" required>
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
                        <textarea name="remark"  class="form-control" rows="2" cols="80" placeholder="e.g Manual Funding to Transfer Balance from XXXXXX"></textarea>
                    </div>
                    <label for="" class="col-sm-3 control-label"></label>
                </div>

            </div>
            
            <div class="form-group" align="right">
                <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;"></label>
                <div class="col-sm-6">
                    <button name="tbsave" type="submit" class="btn bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>"><i class="fa fa-forward">&nbsp;Transfer Fund</i></button>
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