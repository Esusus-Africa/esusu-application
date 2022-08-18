<div class="row">
	      <section class="content">
        
       <div class="box box-default">
           <div class="box-body">
             <div class="table-responsive"> 
            <div class="box-body">
            <a href="mywallet.php?id=<?php echo $_SESSION['tid']; ?>&&mid=NDA0&&tab=tab_1"><button type="button" class="btn btn-flat bg-orange"><i class="fa fa-mail-reply-all"></i>&nbsp;Back</button></a>
<?php
$my_systemset = mysqli_query($link, "SELECT * FROM systemset");
$my_row1 = mysqli_fetch_object($my_systemset);
$vat_rate = $my_row1->vat_rate;
$transfer_charges = $my_row1->transfer_charges;
?> 
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
             <li <?php echo ($_GET['tab'] == 'tab_1') ? "class='active'" : ''; ?>><a href="transfer_fund.php?id=<?php echo $_SESSION['tid']; ?>&&mid=NDA0&&tab=tab_1">African Bank Account Transfers</a></li>

             <li <?php echo ($_GET['tab'] == 'tab_2') ? "class='active'" : ''; ?>><a href="transfer_fund.php?id=<?php echo $_SESSION['tid']; ?>&&mid=NDA0&&tab=tab_2">International Transfers</a></li>

             <li <?php echo ($_GET['tab'] == 'tab_3') ? "class='active'" : ''; ?>><a href="transfer_fund.php?id=<?php echo $_SESSION['tid']; ?>&&mid=NDA0&&tab=tab_3">Mpesa Mobile Money Transfer</a></li>

             <li <?php echo ($_GET['tab'] == 'tab_4') ? "class='active'" : ''; ?>><a href="transfer_fund.php?id=<?php echo $_SESSION['tid']; ?>&&mid=NDA0&&tab=tab_4">Ghana Mobile Money Transfer</a></li>

             <li <?php echo ($_GET['tab'] == 'tab_5') ? "class='active'" : ''; ?>><a href="transfer_fund.php?id=<?php echo $_SESSION['tid']; ?>&&mid=NDA0&&tab=tab_5">Ugandan Mobile Money Transfer</a></li>

             <li <?php echo ($_GET['tab'] == 'tab_8') ? "class='active'" : ''; ?>><a href="transfer_fund.php?id=<?php echo $_SESSION['tid']; ?>&&mid=NDA0&&tab=tab_8">Check Exchange Rate</a></li>
              
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

    $reference =  date("dmyi").time();
    $account_number =  mysqli_real_escape_string($link, $_POST['account_number']);
    $bank_code =  mysqli_real_escape_string($link, $_POST['bank_code']);
	$b_name = mysqli_real_escape_string($link, $_POST['b_name']);
	$amount = preg_replace('/[^0-9.]/', '', mysqli_real_escape_string($link, $_POST['amt_totransfer']));
    $afnarration = mysqli_real_escape_string($link, $_POST['reasons']);
    $SessionID = ($nip_route == "SterlingBank") ? mysqli_real_escape_string($link, $_POST['stSessionId']) : "";
    $NEStatus = ($nip_route == "SterlingBank") ? mysqli_real_escape_string($link, $_POST['NEStatus']) : "";
    $senderAcctNo = ($nip_route == "SterlingBank") ? mysqli_real_escape_string($link, $_POST['FromAcct']) : "";
	$phone = $myiphone;
	$currenctdate = date("Y-m-d H:i:s");
	
	$query = mysqli_query($link, "SELECT * FROM systemset") or die (mysqli_error($link));
    $r = mysqli_fetch_object($query);
    $transfer_charges = $r->transfer_charges;
    
    $otp_code = $control_pin;
    
    $otpChecker = "pin";
	
	  //New AMount + Charges
    $newAmount = $amount + $transfer_charges;
	
	  //Data Parser (array size = 6)
    $mydata = $reference."|".$bank_code."|".$account_number."|".$b_name."|".$amount."|".$newAmount."|".$afnarration."|".$SessionID."|".$NEStatus."|".$senderAcctNo."|".$aggwallet_balance;

    $key = base64_encode($mydata);
    
    if($nip_route == ""){

        echo "<div class='alert bg-orange'>Sorry! Service not active, please contact our support to activate this features!!</div>";

    }
    elseif($gactive_status == "Suspended"){
        
        echo "<div class='alert bg-orange'>Oops! You are unable to make transfer at the moment!!</div>";
        
    }
    elseif($amount <= 0){

        echo "<div class='alert bg-orange'>Sorry! Invalid amount entered!!</div>";

    }
	elseif($aggwallet_balance < $newAmount){
	    
	    echo "<div class='alert bg-orange'>Oops! You have insufficient fund in your Wallet!!</div>";
      
    }
    elseif($amount > $aggrtransferLimitPerTrans){

        echo "<div class='alert bg-orange'>Sorry! You can not transfer more than ".$aggcurrency.number_format($aggrtransferLimitPerTrans,2,'.',',')." at once!!</div>";

    }
    elseif($amyDailyTransferLimit == $aggrtransferLimitPerDay || (($amount + $amyDailyTransferLimit) > $aggrtransferLimitPerDay)){

        echo "<div class='alert bg-orange'>Oops! You have reached your daily limit of ".$aggcurrency.number_format($aggrtransferLimitPerDay,2,'.',',')."</div>";

    }
    elseif($virtual_acctno === "$account_number" || $aggr_id === "$account_number"){
    
        mysqli_query($link, "UPDATE user SET status = 'Suspended' WHERE id = '$aggr_id'");
        mysqli_query($link, "INSERT INTO wallet_case VALUES(null,'','','System','$aggr_id','Suspended','Frudulent Act Detected','$currenctdate')");
  
        echo "<div class='alert bg-orange'>Oops! Request Failed!!</div>";
      
    }
	else{
	    
        $update = mysqli_query($link,"INSERT INTO otp_confirmation VALUES(null,'$aggr_id','$otp_code','$mydata','Pending','$currenctdate')")or die(mysqli_error($link));

        if(!$update)
        {
            echo "<div class='alert bg-orange'>Unable to Transfer Fund.....Please try again later</div>";
        }
        else{
            echo "<div align='center'><img src='../image/checkmark.gif'><p style='color: #38A1F3;'>Transfer Request Received Successfully! Wait a moment to complete the transaction with your Pin</p></div>";
            echo '<meta http-equiv="refresh" content="2;url=transfer_fund.php?id='.$_SESSION['tid'].'&&mid=NDA0&&tab=tab_1&&token='.$key.'&&'.$otpChecker.'">';
        }
        
	}
	
}
?>



<?php
if (isset($_POST['confirm']))
{
    $myotp = $_POST['otp'];
    $token = base64_decode($_GET['token']);
    
    $verify_otp = mysqli_query($link, "SELECT * FROM otp_confirmation WHERE otp_code = '$myotp' AND userid = '$aggr_id' AND status = 'Pending' AND data = '$token'");
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
        $currency = $aggcurrency;
        $originatorName = $accountName;
	    $tramsferid = substr((uniqid(rand(),1)),3,6);
	    //$totalWalletBalance = $bwallet_balance - $amountWithCharges;
        $calcCharges = $amountWithCharges - $amountWithNoCharges;

        //Get my wallet balance after debiting
        $senderBalance = $aggwallet_balance - $amountWithCharges;
        $senderEmail = $aggemail;
        $senderName = $myaggname;
        $merchantName = "ESUSU AFRICA AGGREGATOR";

        if($nip_route == "Wallet Africa"){

            //Fetch Bank Name
            $search_bankname = mysqli_query($link, "SELECT * FROM bank_list WHERE bankcode = '$recipientBankCode'");
            $fetch_bankname = mysqli_fetch_array($search_bankname);
            $mybank_name = $fetch_bankname['bankname'];

            $transactionDateTime = date("Y-m-d h:i:s");
            //8 rows [0 - 8]
            $dataToProcess = $tReference."|".$amountWithNoCharges."|".$narration."|".$accountName."|".$mybank_name."|".$senderName."|".$recipientAcctNo."|".$recipientBankCode."|".$aggwallet_balance;
            //insert txt waiting list
            $mytxtstatus = 'Pending';
            ($amountWithCharges > $aggwallet_balance) ? "" : mysqli_query($link, "INSERT INTO api_txtwaitinglist VALUE(null,'$aggr_id','$tReference','$dataToProcess','$mytxtstatus','$transactionDateTime')");
            
            //Debit Customer Wallet
            ($amountWithCharges > $aggwallet_balance) ? "" : mysqli_query($link, "UPDATE user SET transfer_balance = '$senderBalance' WHERE id = '$aggr_id' AND created_by = ''");

            //Fetch endpoint
            $search_restapi1 = mysqli_query($link, "SELECT * FROM restful_apisetup WHERE api_name = 'walletafrica_transfer'");
            $fetch_restapi1 = mysqli_fetch_object($search_restapi1);
            $client = $fetch_restapi1->api_url;

            ($amountWithCharges > $aggwallet_balance) ? "" : $result = $new->walletAfricaNIPBankTransfer($walletafrica_skey,$recipientBankCode,$recipientAcctNo,$accountName,$tReference,$amountWithNoCharges,$client);
            
            ($amountWithCharges > $aggwallet_balance) ? "" : $decodePro = json_decode($result, true);
            
            //print_r($decodePro);
    
            if($aggwallet_balance >= $amountWithCharges && ($decodePro['ResponseCode'] == "100" || $decodePro['ResponseCode'] == "200" || $result['ResponseCode'] == "100" || $result['ResponseCode'] == "200")){
                
                $gatewayResponse = $decodePro['Message'];
                $recipient = $recipientAcctNo.', '.$accountName.', '.$mybank_name;

                //UPDATE WAITING TXT
                mysqli_query($link, "UPDATE api_txtwaitinglist SET status = '$result' WHERE userid = '$aggr_id' AND refid = '$tReference' AND status = '$mytxtstatus'");
                                    
                //Log successful transaction history
                mysqli_query($link, "INSERT INTO wallet_history VALUE(null,'','$tReference','$recipient','','$amountWithNoCharges','Debit','$aggcurrency','BANK_TRANSFER','Transfer to $recipient','successful','$transactionDateTime','$aggr_id','$senderBalance','$nip_route')");
                mysqli_query($link, "INSERT INTO wallet_history VALUE(null,'','$tReference','$recipient','','$calcCharges','Debit','$aggcurrency','Charges','Transfer to $recipient, Gateway Response: $gatewayResponse','successful','$transactionDateTime','$aggr_id','$senderBalance','$nip_route')");

                $sendSMS->bankTransferEmailNotifier($senderEmail, $tReference, $senderName, $transactionDateTime, $accountName, $recipientAcctNo, $mybank_name, $merchantName, $aggcurrency, $amountWithNoCharges, $senderBalance, $aggremailConfigStatus, $aggrfetch_emailConfig);

                mysqli_query($link, "DELETE FROM otp_confirmation WHERE userid = '$aggr_id' AND otp_code = '$myotp' AND status = 'Pending'");
                    
                echo "<div class='alert bg-blue'>Transaction Successful! Current Transfer Wallet Balance is: <b style='color: white;'>".$aggcurrency.number_format($senderBalance,2,'.',',')."</b></div>";
                echo '<meta http-equiv="refresh" content="10;url=transfer_fund.php?id='.$_SESSION['tid'].'&&mid=NDA0&&tab=tab_1">';
                    
            }
            else{

                $myWaitingList = mysqli_query($link, "SELECT * FROM api_txtwaitinglist WHERE userid = '$aggr_id' AND refid = '$tReference' AND status = '$mytxtstatus'");
                $fetchMyWaitingList = mysqli_fetch_array($myWaitingList);
                $myWaitingData = $fetchMyWaitingList['mydata'];

                $myParameter = (explode('|',$myWaitingData));
                $defaultBalance = $myParameter[8];
                
                //Reverse Customer Wallet
                mysqli_query($link, "UPDATE user SET transfer_balance = '$defaultBalance' WHERE id = '$aggr_id' AND created_by = ''");
            
                //UPDATE WAITING TXT
                mysqli_query($link, "UPDATE api_txtwaitinglist SET status = '$result' WHERE userid = '$aggr_id' AND refid = '$tReference' AND status = '$mytxtstatus'");

                mysqli_query($link, "DELETE FROM otp_confirmation WHERE userid = '$aggr_id' AND otp_code = '$myotp' AND status = 'Pending'");
                
                echo "<div class='alert bg-orange'>Opps!...Unable to conclude transaction, Please Try again later5</div>";
                echo '<meta http-equiv="refresh" content="5;url=transfer_fund.php?tid='.$_SESSION['tid'].'&&mid=NDA0&&tab=tab_1">';
                
            }

        }
        elseif($nip_route == "ProvidusBank"){

            //Fetch Bank Name
            $search_bankname = mysqli_query($link, "SELECT * FROM bank_list2 WHERE bankcode = '$recipientBankCode'");
            $fetch_bankname = mysqli_fetch_array($search_bankname);
            $mybank_name = $fetch_bankname['bankname'];

            $transactionDateTime = date("Y-m-d h:i:s");
            //8 rows [0 - 8]
            $dataToProcess = $tReference."|".$amountWithNoCharges."|".$narration."|".$accountName."|".$mybank_name."|".$senderName."|".$recipientAcctNo."|".$recipientBankCode."|".$aggwallet_balance;
            //insert txt waiting list
            $mytxtstatus = 'Pending';
            ($amountWithCharges > $aggwallet_balance) ? "" : mysqli_query($link, "INSERT INTO api_txtwaitinglist VALUE(null,'$aggr_id','$tReference','$dataToProcess','$mytxtstatus','$transactionDateTime')");

            //Debit Customer Wallet
            ($amountWithCharges > $aggwallet_balance) ? "" : mysqli_query($link, "UPDATE user SET transfer_balance = '$senderBalance' WHERE id = '$aggr_id' AND created_by = ''");

            //fetch endpoint
            $search_restapi1 = mysqli_query($link, "SELECT * FROM restful_apisetup WHERE api_name = 'providus_endpoint'");
            $fetch_restapi1 = mysqli_fetch_object($search_restapi1);
            $api_url = $fetch_restapi1->api_url;

            ($amountWithCharges > $aggwallet_balance) ? "" : $client = new SoapClient($api_url);

            ($amountWithCharges > $aggwallet_balance) ? "" : $result = $new->providusNIPBankTransfer($providusUName,$providusPass,$amountWithNoCharges,$currency,$narration,$tReference,$recipientAcctNo,$recipientBankCode,$accountName,$originatorName,$client);

            if($result['responseCode'] == "00" && $aggwallet_balance >= $amountWithCharges){

                $gatewayResponse = $result['responseMessage'];
                $recipient = $recipientAcctNo.', '.$accountName.', '.$mybank_name;

                //UPDATE WAITING TXT
                mysqli_query($link, "UPDATE api_txtwaitinglist SET status = '$result' WHERE userid = '$aggr_id' AND refid = '$tReference' AND status = '$mytxtstatus'");
    
                //Log successful transaction history
                mysqli_query($link, "INSERT INTO wallet_history VALUE(null,'','$tReference','$recipient','','$amountWithNoCharges','Debit','$aggcurrency','BANK_TRANSFER','Transfer to $recipient','successful','$transactionDateTime','$aggr_id','$senderBalance','$nip_route')");
                mysqli_query($link, "INSERT INTO wallet_history VALUE(null,'','$tReference','$recipient','','$calcCharges','Debit','$aggcurrency','Charges','Transfer to $recipient, Gateway Response: $gatewayResponse','successful','$transactionDateTime','$aggr_id','$senderBalance','$nip_route')");

                $sendSMS->bankTransferEmailNotifier($senderEmail, $tReference, $senderName, $transactionDateTime, $accountName, $recipientAcctNo, $mybank_name, $merchantName, $aggcurrency, $amountWithNoCharges, $senderBalance, $aggremailConfigStatus, $aggrfetch_emailConfig);

                mysqli_query($link, "DELETE FROM otp_confirmation WHERE userid = '$aggr_id' AND otp_code = '$myotp' AND status = 'Pending'");
                    
                echo "<div class='alert bg-blue'>Transaction Successful! Current Transfer Wallet Balance is: <b style='color: white;'>".$aggcurrency.number_format($senderBalance,2,'.',',')."</b></div>";
                echo '<meta http-equiv="refresh" content="10;url=transfer_fund.php?id='.$_SESSION['tid'].'&&mid=NDA0&&tab=tab_1">';

            }
            else{

                $myWaitingList = mysqli_query($link, "SELECT * FROM api_txtwaitinglist WHERE userid = '$aggr_id' AND refid = '$tReference' AND status = '$mytxtstatus'");
                $fetchMyWaitingList = mysqli_fetch_array($myWaitingList);
                $myWaitingData = $fetchMyWaitingList['mydata'];

                $myParameter = (explode('|',$myWaitingData));
                $defaultBalance = $myParameter[8];
                
                //Reverse Customer Wallet
                mysqli_query($link, "UPDATE user SET transfer_balance = '$defaultBalance' WHERE id = '$aggr_id' AND created_by = ''");

                //UPDATE WAITING TXT
                mysqli_query($link, "UPDATE api_txtwaitinglist SET status = '$result' WHERE userid = '$aggr_id' AND refid = '$tReference' AND status = '$mytxtstatus'");

                mysqli_query($link, "DELETE FROM otp_confirmation WHERE userid = '$aggr_id' AND otp_code = '$myotp' AND status = 'Pending'");
                
                echo "<div class='alert bg-orange'>Opps!...Unable to conclude transaction, Please Try again later</div>";
                echo '<meta http-equiv="refresh" content="5;url=transfer_fund.php?tid='.$_SESSION['tid'].'&&mid=NDA0&&tab=tab_1">';

            }

        }
        elseif($nip_route == "SterlingBank"){

            //Fetch Bank Name
            $search_bankname = mysqli_query($link, "SELECT * FROM bank_list3 WHERE bankcode = '$recipientBankCode'");
            $fetch_bankname = mysqli_fetch_array($search_bankname);
            $mybank_name = $fetch_bankname['bankname'];

            $transactionDateTime = date("Y-m-d h:i:s");
            //9 rows [0 - 9]
            $dataToProcess = $tramsferid."|".$SessionID."|".$senderAcctNo."|".$recipientAcctNo."|".$amountWithNoCharges."|".$recipientBankCode."|".$NEStatus."|".$accountName."|".$tReference."|".$aggwallet_balance;
            //insert txt waiting list
            $mytxtstatus = 'Pending';
            ($amountWithCharges > $aggwallet_balance) ? "" : mysqli_query($link, "INSERT INTO api_txtwaitinglist VALUE(null,'$aggr_id','$tReference','$dataToProcess','$mytxtstatus','$transactionDateTime')");

            //Debit Customer Wallet
            ($amountWithCharges > $aggwallet_balance) ? "" : mysqli_query($link, "UPDATE user SET transfer_balance = '$senderBalance' WHERE id = '$aggr_id' AND created_by = ''");

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

            ($amountWithCharges > $aggwallet_balance) ? "" : $process = $new->sterlingNIPBankTransfer($data_to_send_server,$client);

            ($amountWithCharges > $aggwallet_balance) ? "" : $processReturn = $process['data'];

            if($processReturn['status'] == "00"){

                $gatewayResponse = $processReturn['ResponseText'];
                $recipient = $recipientAcctNo.', '.$accountName.', '.$mybank_name;

                //UPDATE WAITING TXT
                mysqli_query($link, "UPDATE api_txtwaitinglist SET status = '$processReturn' WHERE userid = '$aggr_id' AND refid = '$tReference' AND status = '$mytxtstatus'");
                    
                //Log successful transaction history
                mysqli_query($link, "INSERT INTO wallet_history VALUE(null,'','$tReference','$recipient','','$amountWithNoCharges','Debit','$aggcurrency','BANK_TRANSFER','Transfer to $recipient','successful','$transactionDateTime','$aggr_id','$senderBalance','$nip_route')");
                mysqli_query($link, "INSERT INTO wallet_history VALUE(null,'','$tReference','$recipient','','$calcCharges','Debit','$aggcurrency','Charges','Transfer to $recipient, Gateway Response: $gatewayResponse','successful','$transactionDateTime','$aggr_id','$senderBalance','$nip_route')");
                
                $sendSMS->bankTransferEmailNotifier($senderEmail, $tReference, $senderName, $transactionDateTime, $accountName, $recipientAcctNo, $mybank_name, $merchantName, $aggcurrency, $amountWithNoCharges, $senderBalance, $aggremailConfigStatus, $aggrfetch_emailConfig);

                mysqli_query($link, "DELETE FROM otp_confirmation WHERE userid = '$aggr_id' AND otp_code = '$myotp' AND status = 'Pending'");
                    
                echo "<div class='alert bg-blue'>Transaction Successful! Current Transfer Wallet Balance is: <b style='color: white;'>".$aggcurrency.number_format($senderBalance,2,'.',',')."</b></div>";
                echo '<meta http-equiv="refresh" content="10;url=transfer_fund.php?id='.$_SESSION['tid'].'&&mid=NDA0&&tab=tab_1">';

            }
            else{

                $myWaitingList = mysqli_query($link, "SELECT * FROM api_txtwaitinglist WHERE userid = '$aggr_id' AND refid = '$tReference' AND status = '$mytxtstatus'");
                $fetchMyWaitingList = mysqli_fetch_array($myWaitingList);
                $myWaitingData = $fetchMyWaitingList['mydata'];

                $myParameter = (explode('|',$myWaitingData));
                $defaultBalance = $myParameter[9];
                
                //Reverse Customer Wallet
                mysqli_query($link, "UPDATE user SET transfer_balance = '$defaultBalance' WHERE id = '$aggr_id' AND created_by = ''");

                //UPDATE WAITING TXT
                mysqli_query($link, "UPDATE api_txtwaitinglist SET status = '$processReturn' WHERE userid = '$aggr_id' AND refid = '$tReference' AND status = '$mytxtstatus'");

                mysqli_query($link, "DELETE FROM otp_confirmation WHERE userid = '$aggr_id' AND otp_code = '$myotp' AND status = 'Pending'");
                
                echo "<div class='alert bg-orange'>Opps!...Unable to conclude transaction, Please Try again later</div>";
                echo '<meta http-equiv="refresh" content="5;url=transfer_fund.php?tid='.$_SESSION['tid'].'&&mid=NDA0&&tab=tab_1">';

            }

        }
        elseif($nip_route == "RubiesBank"){

            //Fetch Bank Name
            $search_bankname = mysqli_query($link, "SELECT * FROM bank_list4 WHERE bankcode = '$recipientBankCode'");
            $fetch_bankname = mysqli_fetch_array($search_bankname);
            $mybank_name = $fetch_bankname['bankname'];
            $draccountname = $myaggname;

            $transactionDateTime = date("Y-m-d h:i:s");
            //8 rows [0 - 8]
            $dataToProcess = $tReference."|".$amountWithNoCharges."|".$narration."|".$accountName."|".$mybank_name."|".$draccountname."|".$recipientAcctNo."|".$recipientBankCode."|".$aggwallet_balance;
            //insert txt waiting list
            $mytxtstatus = 'Pending';
            ($amountWithCharges > $aggwallet_balance) ? "" : mysqli_query($link, "INSERT INTO api_txtwaitinglist VALUE(null,'$aggr_id','$tReference','$dataToProcess','$mytxtstatus','$transactionDateTime')");
            
            //Debit Customer Wallet
            ($amountWithCharges > $aggwallet_balance) ? "" : mysqli_query($link, "UPDATE user SET transfer_balance = '$senderBalance' WHERE id = '$aggr_id' AND created_by = ''");

            ($amountWithCharges > $aggwallet_balance) ? "" : $result = $new->rubiesNIPBankTransfer($tReference,$amountWithNoCharges,$narration,$accountName,$recipientBankCode,$recipientAcctNo,$rubbiesSecKey,$link,$draccountname);

            ($amountWithCharges > $aggwallet_balance) ? "" : $rubbies_generate = json_decode($result, true);

            if($rubbies_generate['responsecode'] == "00" && $rubbies_generate['nibsscode'] == "00" && $aggwallet_balance >= $amountWithCharges){

                $gatewayResponse = $rubbies_generate['nibssresponsemessage'];
                $recipient = $recipientAcctNo.', '.$accountName.', '.$mybank_name;

                //UPDATE WAITING TXT
                mysqli_query($link, "UPDATE api_txtwaitinglist SET status = '$result' WHERE userid = '$aggr_id' AND refid = '$tReference' AND status = '$mytxtstatus'");

                //Log successful transaction history
                mysqli_query($link, "INSERT INTO wallet_history VALUE(null,'','$tReference','$recipient','','$amountWithNoCharges','Debit','$aggcurrency','BANK_TRANSFER','Transfer to $recipient','successful','$transactionDateTime','$aggr_id','$senderBalance','$nip_route')");
                mysqli_query($link, "INSERT INTO wallet_history VALUE(null,'','$tReference','$recipient','','$calcCharges','Debit','$aggcurrency','Charges','Transfer to $recipient, Gateway Response: $gatewayResponse','successful','$transactionDateTime','$aggr_id','$senderBalance','$nip_route')");

                $sendSMS->bankTransferEmailNotifier($senderEmail, $tReference, $senderName, $transactionDateTime, $accountName, $recipientAcctNo, $mybank_name, $merchantName, $aggcurrency, $amountWithNoCharges, $senderBalance, $aggremailConfigStatus, $aggrfetch_emailConfig);

                mysqli_query($link, "DELETE FROM otp_confirmation WHERE userid = '$aggr_id' AND otp_code = '$myotp' AND status = 'Pending'");
                    
                echo "<div class='alert bg-blue'>Transaction Successful! Current Transfer Wallet Balance is: <b style='color: white;'>".$aggcurrency.number_format($senderBalance,2,'.',',')."</b></div>";
                echo '<meta http-equiv="refresh" content="10;url=transfer_fund.php?id='.$_SESSION['tid'].'&&mid=NDA0&&tab=tab_1">';

            }
            elseif($rubbies_generate['responsecode'] == "-1" && $aggwallet_balance >= $amountWithCharges){

                $gatewayResponse = $rubbies_generate['nibssresponsemessage'];
                $recipient = $recipientAcctNo.', '.$accountName.', '.$mybank_name;

                //UPDATE WAITING TXT
                mysqli_query($link, "UPDATE api_txtwaitinglist SET status = '$result' WHERE userid = '$aggr_id' AND refid = '$tReference' AND status = '$mytxtstatus'");

                mysqli_query($link, "INSERT INTO wallet_history VALUE(null,'','$tReference','$recipient','','$amountWithNoCharges','Debit','$aggcurrency','BANK_TRANSFER','Transfer to $recipient, $gatewayResponse','Processing','$transactionDateTime','$aggr_id','$senderBalance','$nip_route')");
                mysqli_query($link, "INSERT INTO wallet_history VALUE(null,'','$tReference','$recipient','','$calcCharges','Debit','$aggcurrency','Charges','Transfer to $recipient, Narration: $narration','Processing','$transactionDateTime','$aggr_id','$senderBalance','$nip_route')");

                $sendSMS->bankTransferEmailNotifier($senderEmail, $tReference, $senderName, $transactionDateTime, $accountName, $recipientAcctNo, $mybank_name, $merchantName, $aggcurrency, $amountWithNoCharges, $senderBalance, $aggremailConfigStatus, $aggrfetch_emailConfig);

                mysqli_query($link, "DELETE FROM otp_confirmation WHERE userid = '$aggr_id' AND otp_code = '$myotp' AND status = 'Pending'");
                    
                echo "<div class='alert bg-blue'>Transaction in progress, Please do not retry until you confirmed failed! Click here to <a href='mywallet.php?id=".$aggr_id."&&mid=NDA0&&tab=tab_1'>Check Status</a>!!</div>";
                echo '<meta http-equiv="refresh" content="10;url=transfer_fund.php?id='.$_SESSION['tid'].'&&mid=NDA0&&tab=tab_1">';

            }
            else{

                $myWaitingList = mysqli_query($link, "SELECT * FROM api_txtwaitinglist WHERE userid = '$aggr_id' AND refid = '$tReference' AND status = '$mytxtstatus'");
                $fetchMyWaitingList = mysqli_fetch_array($myWaitingList);
                $myWaitingData = $fetchMyWaitingList['mydata'];

                $myParameter = (explode('|',$myWaitingData));
                $defaultBalance = $myParameter[8];
                
                //Reverse to Customer Wallet
                mysqli_query($link, "UPDATE user SET transfer_balance = '$defaultBalance' WHERE id = '$aggr_id' AND created_by = ''");

                //UPDATE WAITING TXT
                mysqli_query($link, "UPDATE api_txtwaitinglist SET status = '$result' WHERE userid = '$aggr_id' AND refid = '$tReference' AND status = '$mytxtstatus'");

                mysqli_query($link, "DELETE FROM otp_confirmation WHERE userid = '$aggr_id' AND otp_code = '$myotp' AND status = 'Pending'");
                
                echo "<div class='alert bg-orange'>Opps!...Unable to conclude transaction, Please Try again later</div>";
                echo '<meta http-equiv="refresh" content="5;url=transfer_fund.php?tid='.$_SESSION['tid'].'&&mid=NDA0&&tab=tab_1">';

            }

        }
        elseif($nip_route == "PrimeAirtime"){

            //Fetch Bank Name
            $search_bankname = mysqli_query($link, "SELECT * FROM bank_list WHERE bankcode = '$recipientBankCode'");
            $fetch_bankname = mysqli_fetch_array($search_bankname);
            $mybank_name = $fetch_bankname['bankname'];

            $transactionDateTime = date("Y-m-d h:i:s");
            //8 rows [0 - 8]
            $dataToProcess = $tReference."|".$amountWithNoCharges."|".$narration."|".$accountName."|".$mybank_name."|".$senderName."|".$recipientAcctNo."|".$recipientBankCode."|".$aggwallet_balance;
            //insert txt waiting list
            $mytxtstatus = 'Pending';
            ($amountWithCharges > $aggwallet_balance) ? "" : mysqli_query($link, "INSERT INTO api_txtwaitinglist VALUE(null,'$aggr_id','$tReference','$dataToProcess','$mytxtstatus','$transactionDateTime')");
            
            //Debit Customer Wallet
            ($amountWithCharges > $aggwallet_balance) ? "" : mysqli_query($link, "UPDATE user SET transfer_balance = '$senderBalance' WHERE id = '$aggr_id' AND created_by = ''");

            ($amountWithCharges > $aggwallet_balance) ? "" : $result = $new->primeAirtimeFT($link,$accessToken,$recipientBankCode,$recipientAcctNo,$amountWithNoCharges,$narration,$tReference);
            
            ($amountWithCharges > $aggwallet_balance) ? "" : $decodePro = json_decode($result, true);
                
            if($aggwallet_balance >= $amountWithCharges && ($decodePro['status'] == "201" || $decodePro['status'] == "200")){
                
                $gatewayResponse = $decodePro['message'];
                $recipient = $recipientAcctNo.', '.$accountName.', '.$mybank_name;

                //UPDATE WAITING TXT
                mysqli_query($link, "UPDATE api_txtwaitinglist SET status = '$result' WHERE userid = '$aggr_id' AND refid = '$tReference' AND status = '$mytxtstatus'");
                                    
                //Log successful transaction history
                mysqli_query($link, "INSERT INTO wallet_history VALUE(null,'','$tReference','$recipient','','$amountWithNoCharges','Debit','$aggcurrency','BANK_TRANSFER','Transfer to $recipient','successful','$transactionDateTime','$aggr_id','$senderBalance','$nip_route')");
                mysqli_query($link, "INSERT INTO wallet_history VALUE(null,'','$tReference','$recipient','','$calcCharges','Debit','$aggcurrency','Charges','Transfer to $recipient, Gateway Response: $gatewayResponse','successful','$transactionDateTime','$aggr_id','$senderBalance','$nip_route')");

                $sendSMS->bankTransferEmailNotifier($senderEmail, $tReference, $senderName, $transactionDateTime, $accountName, $recipientAcctNo, $mybank_name, $merchantName, $aggcurrency, $amountWithNoCharges, $senderBalance, $aggremailConfigStatus, $aggrfetch_emailConfig);

                mysqli_query($link, "DELETE FROM otp_confirmation WHERE userid = '$aggr_id' AND otp_code = '$myotp' AND status = 'Pending'");
                    
                echo "<div class='alert bg-blue'>Transaction Successful! Current Transfer Wallet Balance is: <b style='color: white;'>".$aggcurrency.number_format($senderBalance,2,'.',',')."</b></div>";
                echo '<meta http-equiv="refresh" content="10;url=transfer_fund.php?id='.$_SESSION['tid'].'&&mid=NDA0&&tab=tab_1">';
                    
            }
            else{

                $myWaitingList = mysqli_query($link, "SELECT * FROM api_txtwaitinglist WHERE userid = '$aggr_id' AND refid = '$tReference' AND status = '$mytxtstatus'");
                $fetchMyWaitingList = mysqli_fetch_array($myWaitingList);
                $myWaitingData = $fetchMyWaitingList['mydata'];

                $myParameter = (explode('|',$myWaitingData));
                $defaultBalance = $myParameter[8];
                
                //Reverse Customer Wallet
                mysqli_query($link, "UPDATE user SET transfer_balance = '$defaultBalance' WHERE id = '$aggr_id' AND created_by = ''");
            
                //UPDATE WAITING TXT
                mysqli_query($link, "UPDATE api_txtwaitinglist SET status = '$result' WHERE userid = '$aggr_id' AND refid = '$tReference' AND status = '$mytxtstatus'");

                mysqli_query($link, "DELETE FROM otp_confirmation WHERE userid = '$aggr_id' AND otp_code = '$myotp' AND status = 'Pending'");
                
                echo "<div class='alert bg-orange'>Opps!...Unable to conclude transaction, Please Try again later5</div>";
                echo '<meta http-equiv="refresh" content="5;url=transfer_fund.php?tid='.$_SESSION['tid'].'&&mid=NDA0&&tab=tab_1">';
                
            }

        }
        elseif($nip_route == "SuntrustBank"){

            //Fetch Bank Name
            $search_bankname = mysqli_query($link, "SELECT * FROM bank_list WHERE bankcode = '$recipientBankCode'");
            $fetch_bankname = mysqli_fetch_array($search_bankname);
            $mybank_name = $fetch_bankname['bankname'];

            $transactionDateTime = date("Y-m-d h:i:s");
            //8 rows [0 - 8]
            $dataToProcess = $tReference."|".$amountWithNoCharges."|".$narration."|".$accountName."|".$mybank_name."|".$senderName."|".$recipientAcctNo."|".$recipientBankCode."|".$aggwallet_balance;
            //insert txt waiting list
            $mytxtstatus = 'Pending';
            ($amountWithCharges > $aggwallet_balance) ? "" : mysqli_query($link, "INSERT INTO api_txtwaitinglist VALUE(null,'$aggr_id','$tReference','$dataToProcess','$mytxtstatus','$transactionDateTime')");
            
            //Debit Customer Wallet
            ($amountWithCharges > $aggwallet_balance) ? "" : mysqli_query($link, "UPDATE user SET transfer_balance = '$senderBalance' WHERE id = '$aggr_id' AND created_by = ''");

            ($amountWithCharges > $aggwallet_balance) ? "" : $result = $new->newSunTrustNIPBankTransfer($link,$tReference,$narration,$amountWithNoCharges,$recipientAcctNo,$recipientBankCode,$onePipeSKey,$onePipeApiKey);
            
            ($amountWithCharges > $aggwallet_balance) ? "" : $decodePro = json_decode($result, true);
            
            $responseCode = ($amountWithCharges > $aggwallet_balance) ? "" : $decodePro['data']['provider_response_code'];
    
            if($aggwallet_balance >= $amountWithCharges && $responseCode == "00"){
                
                $gatewayResponse = $decodePro['message'];
                $recipient = $recipientAcctNo.', '.$accountName.', '.$mybank_name;

                //UPDATE WAITING TXT
                mysqli_query($link, "UPDATE api_txtwaitinglist SET status = '$result' WHERE userid = '$aggr_id' AND refid = '$tReference' AND status = '$mytxtstatus'");
                                    
                //Log successful transaction history
                mysqli_query($link, "INSERT INTO wallet_history VALUE(null,'','$tReference','$recipient','','$amountWithNoCharges','Debit','$aggcurrency','BANK_TRANSFER','Transfer to $recipient','successful','$transactionDateTime','$aggr_id','$senderBalance','$nip_route')");
                mysqli_query($link, "INSERT INTO wallet_history VALUE(null,'','$tReference','$recipient','','$calcCharges','Debit','$aggcurrency','Charges','Transfer to $recipient, Gateway Response: $gatewayResponse','successful','$transactionDateTime','$aggr_id','$senderBalance','$nip_route')");

                $sendSMS->bankTransferEmailNotifier($senderEmail, $tReference, $senderName, $transactionDateTime, $accountName, $recipientAcctNo, $mybank_name, $merchantName, $aggcurrency, $amountWithNoCharges, $senderBalance, $aggremailConfigStatus, $aggrfetch_emailConfig);

                mysqli_query($link, "DELETE FROM otp_confirmation WHERE userid = '$aggr_id' AND otp_code = '$myotp' AND status = 'Pending'");
                    
                echo "<div class='alert bg-blue'>Transaction Successful! Current Transfer Wallet Balance is: <b style='color: white;'>".$aggcurrency.number_format($senderBalance,2,'.',',')."</b></div>";
                echo '<meta http-equiv="refresh" content="10;url=transfer_fund.php?id='.$_SESSION['tid'].'&&mid=NDA0&&tab=tab_1">';
                    
            }
            else{

                $myWaitingList = mysqli_query($link, "SELECT * FROM api_txtwaitinglist WHERE userid = '$aggr_id' AND refid = '$tReference' AND status = '$mytxtstatus'");
                $fetchMyWaitingList = mysqli_fetch_array($myWaitingList);
                $myWaitingData = $fetchMyWaitingList['mydata'];

                $myParameter = (explode('|',$myWaitingData));
                $defaultBalance = $myParameter[8];
                
                //Reverse Customer Wallet
                mysqli_query($link, "UPDATE user SET transfer_balance = '$defaultBalance' WHERE id = '$aggr_id' AND created_by = ''");
            
                //UPDATE WAITING TXT
                mysqli_query($link, "UPDATE api_txtwaitinglist SET status = '$result' WHERE userid = '$aggr_id' AND refid = '$tReference' AND status = '$mytxtstatus'");

                mysqli_query($link, "DELETE FROM otp_confirmation WHERE userid = '$aggr_id' AND otp_code = '$myotp' AND status = 'Pending'");
                
                echo "<div class='alert bg-orange'>Opps!...Unable to conclude transaction, Please Try again later5</div>";
                echo '<meta http-equiv="refresh" content="5;url=transfer_fund.php?tid='.$_SESSION['tid'].'&&mid=NDA0&&tab=tab_1">';
                
            }

        }
        else{

            mysqli_query($link, "DELETE FROM otp_confirmation WHERE userid = '$aggr_id' AND otp_code = '$myotp' AND status = 'Pending'");
                
            echo "<div class='alert bg-orange'>Opps!...Service not available at the moment!!</div>";
            echo '<meta http-equiv="refresh" content="3;url=transfer_fund.php?tid='.$_SESSION['tid'].'&&mid=NDA0&&tab=tab_1">';

        }
	    
	}
    
}
?>


<?php
if(!(isset($_GET['pin'])))
{
?>
             <div class="box-body">
                 
                 <?php
                  if($nip_route == "Wallet Africa" || $nip_route == "PrimeAirtime" || $nip_route == "SuntrustBank")
                  {
                 ?>

                 <div class="form-group">
                    <label for="" class="col-sm-3 control-label" style="color:blue;">Country</label>
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
                    <label for="" class="col-sm-3 control-label" style="color:blue;">Account Number</label>
                    <div class="col-sm-6">
                        <input name="account_number" type="text" id="account_number" onkeydown="loadaccount();" class="form-control" placeholder="Bank Account Number" required>
                    </div>
                    <label for="" class="col-sm-3 control-label"></label>
                  </div>
                  
                  <div class="form-group">
                    <label for="" class="col-sm-3 control-label" style="color:blue;">Bank Name</label>
                    <div class="col-sm-6">
                        <div id="bank_list">------------</div>
                    </div>
                    <label for="" class="col-sm-3 control-label"></label>
                  </div>

                  <?php
                  }
                  elseif($nip_route == "ProvidusBank" || $nip_route == "AccessBank" || $nip_route == "SterlingBank" || $nip_route == "RubiesBank"){
                  ?>

                  <div class="form-group">
                    <label for="" class="col-sm-3 control-label" style="color:blue;">Account Number</label>
                    <div class="col-sm-6">
                        <input name="account_number" type="text" id="accountNo" onkeydown="fetchbanklist();" class="form-control" placeholder="Bank Account Number" required>
                    </div>
                    <label for="" class="col-sm-3 control-label"></label>
                  </div>

                  <div class="form-group">
                    <label for="" class="col-sm-3 control-label" style="color:blue;">Recipient Bank</label>
                    <div class="col-sm-6">
                        <?php 
                        if($nip_route == "ProvidusBank"){
                        ?>
                          <select name="bank_code"  class="form-control select2" id="bankCode" onchange="fetchbanklist();" required>
                        <?php
                        }
                        elseif($nip_route == "SterlingBank"){
                        ?>
                          <select name="bank_code"  class="form-control select2" id="sterlingBankCode" onchange="fetchsterlingbanklist();" required>
                        <?php
                        }
                        elseif($nip_route == "RubiesBank"){
                        ?>
                          <select name="bank_code"  class="form-control select2" id="rubiesBankCode" onchange="fetchrubiesbanklist();" required>
                        <?php
                        }
                        ?>
                          <option value="" selected>Select Bank</option>
                          <?php
                          if($nip_route == "ProvidusBank"){ //ROUTE FOR PROVIDUS BANK
                              
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
                              
                          }elseif($nip_route == "SterlingBank"){ //ROUTE FOR STERLING BANK
                              
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
                              
                          }elseif($nip_route == "RubiesBank"){ //ROUTE FOR RUBIES BANK
                              
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
                    <label for="" class="col-sm-3 control-label" style="color:blue;">Beneficiary Name</label>
                    <div class="col-sm-6">
                        <div id="act_numb">------------</div>
                    </div>
                    <label for="" class="col-sm-3 control-label"></label>
                  </div>

                  <div class="form-group">
                    <label for="" class="col-sm-3 control-label" style="color:blue;">Amount</label>
                    <div class="col-sm-6">
                        <input name="amt_totransfer" type="text" class="form-control" placeholder="Enter Amount to Transfer" required>
                        <span style="color: orange;">NOTE: Pease do not put comma (,) while entering the Amount to Transfer</span>
                    </div>
                    <label for="" class="col-sm-3 control-label"></label>
                  </div>
                  
                  <div class="form-group">
                    <label for="" class="col-sm-3 control-label" style="color:blue;">Reason(s)</label>
                    <div class="col-sm-6">
                        <textarea name="reasons" class="form-control" placeholder="State Reasons for Transfer e.g. For House rent, Pay Bonus etc." rows="2" cols="5" required></textarea>
                    </div>
                    <label for="" class="col-sm-3 control-label"></label>
                  </div>
			
			 </div>
			 
			 <div class="form-group" align="right">
                <label for="" class="col-sm-3 control-label" style="color:blue;"></label>
                <div class="col-sm-6">
                    <button name="africa_save" type="submit" class="btn bg-blue btn-flat"><i class="fa fa-forward">&nbsp;Initiate Transfer</i></button>
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
    $phone = $myiphone;
    $currenctdate = date("Y-m-d H:i:s");
	
	  $query = mysqli_query($link, "SELECT * FROM systemset") or die (mysqli_error($link));
    $r = mysqli_fetch_object($query);
    $intlpayment_charges = $row1->intlpayment_charges;
    
    //Currency conversion
    $search_restapi = mysqli_query($link, "SELECT * FROM restful_apisetup WHERE api_name = 'Exchange_Rate'");
    $fetch_restapi = mysqli_fetch_object($search_restapi);
    $api_url = $fetch_restapi->api_url;
    
    // Pass the parameter here
    $postdata = array(
        "secret_key"	=>	$rave_secret_key,
        "service"       =>  "rates_convert",
        "service_method"    =>  "post",
        "service_version"   =>  "v1",
        "service_channel"   =>  "transactions",
        "service_channel_group" =>  "merchants",
        "service_payload"   =>  [
              "FromCurrency"  =>  $currency,
              "ToCurrency"    =>  $aggcurrency,
              "Amount"        =>  $amountWithNoCharges
            ]
        );
        
    $make_call = callAPI('POST', $api_url, json_encode($postdata));
    $result = json_decode($make_call, true);
    
    $convertedAmount = $result['data']['ToAmount'];
    
    $otp_code = $control_pin;
    
    $otpChecker = "pin";
	
	  //New AMount + Charges
    $amountWithCharges = ($intlpayment_charges * $convertedAmount) + $convertedAmount;
	
	  //Data Parser (array size = 11)
    $mydata = $convertedAmount."|".$amountWithCharges."|".$intnarration."|".$currency."|".$reference."|".$b_name."|".$account_number."|".$routing_number."|".$swift_code."|".$bank_name."|".$b_addrs."|".$b_country."|".$aggwallet_balance;
    
    if($amountWithNoCharges <= 0){

        echo "<div class='alert bg-orange'>Sorry! Invalid amount entered!!</div>";

    }
    elseif($gactive_status == "Suspended"){
        
        echo "<div class='alert bg-orange'>Oops! You are unable to make transfer at the moment!!</div>";
        
    }
	elseif($aggwallet_balance < $amountWithCharges){
	    
	    echo "<div class='alert bg-orange'>Sorry! You need to have upto $aggcurrency.number_format($amountWithCharges,2,'.',',') in your wallet to proceed!!</div>";
      
    }
    elseif($amountWithNoCharges > $aggrtransferLimitPerTrans){

        echo "<div class='alert bg-orange'>Sorry! You can not transfer more than ".$aggcurrency.number_format($aggrtransferLimitPerTrans,2,'.',',')." at once!!</div>";

    }
    elseif($amyDailyTransferLimit == $aggrtransferLimitPerDay || (($amountWithNoCharges + $amyDailyTransferLimit) > $aggrtransferLimitPerDay)){

        echo "<div class='alert bg-orange'>Oops! You have reached your daily limit of ".$aggcurrency.number_format($aggrtransferLimitPerDay,2,'.',',')."</div>";

    }
    elseif($virtual_acctno === "$account_number" || $aggr_id === "$account_number"){
    
        mysqli_query($link, "UPDATE user SET status = 'Suspended' WHERE id = '$aggr_id'");
        mysqli_query($link, "INSERT INTO wallet_case VALUES(null,'','','System','$aggr_id','Suspended','Frudulent Act Detected','$currenctdate')");
  
        echo "<div class='alert bg-orange'>Oops! Request Failed!!</div>";
      
    }
	else{
        
        $update = mysqli_query($link,"INSERT INTO otp_confirmation VALUES(null,'$aggr_id','$otp_code','$mydata','Pending','$currenctdate')") or die(mysqli_error($link));

        if(!$update)
        {
            echo "<div class='alert bg-orange'>Unable to Transfer Fund.....Please try again later</div>";
        }
        else{
            echo "<div align='center'><img src='../image/checkmark.gif'><p style='color: #38A1F3;'>Transfer Request Received Successfully! Wait a moment to complete the transaction with your Pin</p></div>";
            echo '<meta http-equiv="refresh" content="2;url=transfer_fund.php?id='.$_SESSION['tid'].'&&mid=NDA0&&tab=tab_2&&'.$otpChecker.'">';
        }
        
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
    
    $verify_otp = mysqli_query($link, "SELECT * FROM otp_confirmation WHERE otp_code = '$myotp' AND userid = '$aggr_id' AND status = 'Pending'");
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
        $senderBalance = $aggwallet_balance - $amountWithCharges;
        $senderEmail = $aggemail;
        $senderName = $myaggname;
        $merchantName = "ESUSU AFRICA AGGREGATOR";

        $transactionDateTime = date("Y-m-d h:i:s");
        //13 rows [0 - 13]
        $dataToProcess = $amountWithNoCharges."|".$rave_secret_key."|".$intnarration."|".$currency."|".$tReference."|".$accountName."|".$recipientAcctNo."|".$routing_number."|".$swift_code."|".$mybank_name."|".$b_addrs."|".$b_country."|".$aggcurrency."|".$aggwallet_balance;
        //insert txt waiting list
        $mytxtstatus = 'Pending';
        ($amountWithCharges > $aggwallet_balance) ? "" : mysqli_query($link, "INSERT INTO api_txtwaitinglist VALUE(null,'$aggr_id','$tReference','$dataToProcess','$mytxtstatus','$transactionDateTime')");
            
        //Debit Customer Wallet
        ($amountWithCharges > $aggwallet_balance) ? "" : mysqli_query($link, "UPDATE user SET transfer_balance = '$senderBalance' WHERE id = '$aggr_id' AND created_by = ''");

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
                "debit_currency"    =>  $aggcurrency
        );
    
        ($amountWithCharges > $aggwallet_balance) ? "" : $make_call1 = intlCallAPI('POST', $api_url1, json_encode($postdata1));
        ($amountWithCharges > $aggwallet_balance) ? "" : $result1 = json_decode($make_call1, true);
            
        if($result1['status'] == "success" && $aggwallet_balance >= $amountWithCharges){
                
            $transfer_id = $result1['data']['id'];
            $transfers_fee = "Gateway Fee: ".$aggcurrency.$result1['data']['fee']." | Inhouse Fee: ".$aggcurrency.$calcCharges;
            $status = $result1['data']['status'];
            $recipient = $recipientAcctNo.', '.$accountName.', '.$routing_number.', '.$mybank_name;

            //UPDATE WAITING TXT
            mysqli_query($link, "UPDATE api_txtwaitinglist SET status = '$make_call1' WHERE userid = '$aggr_id' AND refid = '$tReference' AND status = '$mytxtstatus'");
                
            //Log successful transaction history
            mysqli_query($link, "INSERT INTO wallet_history VALUE(null,'','$tReference','$recipient','','$amountWithNoCharges','Debit','$aggcurrency','BANK_TRANSFER','Transfer to $recipient','successful','$transactionDateTime','$aggr_id','$senderBalance','Flutterwave')");
            mysqli_query($link, "INSERT INTO wallet_history VALUE(null,'','$tReference','$recipient','','$calcCharges','Debit','$aggcurrency','Charges','Transfer to $recipient, Remark: $intnarration','successful','$transactionDateTime','$aggr_id','$senderBalance','Flutterwave')");
            
            $sendSMS->bankTransferEmailNotifier($senderEmail, $tReference, $senderName, $transactionDateTime, $accountName, $recipientAcctNo, $mybank_name, $merchantName, $aggcurrency, $amountWithNoCharges, $senderBalance, $aggremailConfigStatus, $aggrfetch_emailConfig);

            mysqli_query($link, "DELETE FROM otp_confirmation WHERE userid = '$aggr_id' AND otp_code = '$myotp' AND status = 'Pending'");
                
            echo "<div class='alert bg-blue'>Transfer Initiated Successful! Current Transfer Wallet Balance is: <b style='color: white;'>".$aggcurrency.number_format($senderBalance,2,'.',',')."</b></div>";
        	  echo '<meta http-equiv="refresh" content="3;url=transfer_fund.php?id='.$_SESSION['tid'].'&&mid=NDA0&&tab=tab_2">';
                
        }
        else{

            $myWaitingList = mysqli_query($link, "SELECT * FROM api_txtwaitinglist WHERE userid = '$aggr_id' AND refid = '$tReference' AND status = '$mytxtstatus'");
            $fetchMyWaitingList = mysqli_fetch_array($myWaitingList);
            $myWaitingData = $fetchMyWaitingList['mydata'];

            $myParameter = (explode('|',$myWaitingData));
            $defaultBalance = $myParameter[13];
            
            //Reverse to Customer Wallet
            mysqli_query($link, "UPDATE user SET transfer_balance = '$defaultBalance' WHERE id = '$aggr_id' AND created_by = ''");
            
            //UPDATE WAITING TXT
            mysqli_query($link, "UPDATE api_txtwaitinglist SET status = '$make_call1' WHERE userid = '$aggr_id' AND refid = '$tReference' AND status = '$mytxtstatus'");

            mysqli_query($link, "DELETE FROM otp_confirmation WHERE userid = '$aggr_id' AND otp_code = '$myotp' AND status = 'Pending'");
            
            echo "<div class='alert bg-orange'>Sorry! Unable to conclude transaction please try again later!!</div>";
            echo '<meta http-equiv="refresh" content="3;url=transfer_fund.php?id='.$_SESSION['tid'].'&&mid=NDA0&&tab=tab_2">';
            
        }
        
	}
    
}
?>


<?php
if(!(isset($_GET['pin'])))
{
?>
             <div class="box-body">
                 
                 <div class="form-group">
                    <label for="" class="col-sm-3 control-label" style="color:blue;">Account Number</label>
                    <div class="col-sm-6">
                        <input name="account_number" type="text" class="form-control" placeholder="Bank Account Number" required>
                    </div>
                    <label for="" class="col-sm-3 control-label"></label>
                  </div>
                  
                  <div class="form-group">
                    <label for="" class="col-sm-3 control-label" style="color:blue;">Routing Number</label>
                    <div class="col-sm-6">
                        <input name="routing_number" type="text" class="form-control" placeholder="Bank Routing Number" required>
                    </div>
                    <label for="" class="col-sm-3 control-label"></label>
                  </div>
                  
                  <div class="form-group">
                    <label for="" class="col-sm-3 control-label" style="color:blue;">Swiftcode</label>
                    <div class="col-sm-6">
                        <input name="swift_code" type="text" class="form-control" placeholder="Bank SwiftCode" required>
                    </div>
                    <label for="" class="col-sm-3 control-label"></label>
                  </div>
                  
                  <div class="form-group">
                    <label for="" class="col-sm-3 control-label" style="color:blue;">Bank Name</label>
                    <div class="col-sm-6">
                        <input name="bank_name" type="text" class="form-control" placeholder="e.g. BANK OF AMERICA, N.A., SAN FRANCISCO, CA" required>
                    </div>
                    <label for="" class="col-sm-3 control-label"></label>
                  </div>
                  
                  <div class="form-group">
                    <label for="" class="col-sm-3 control-label" style="color:blue;">Beneficiary Name</label>
                    <div class="col-sm-6">
                        <input name="b_name" type="text" class="form-control" placeholder="Enter Beneficiary Name e.g. Mark Cuban" required>
                    </div>
                    <label for="" class="col-sm-3 control-label"></label>
                  </div>
                  
                  <div class="form-group">
                    <label for="" class="col-sm-3 control-label" style="color:blue;">Beneficiary Address</label>
                    <div class="col-sm-6">
                        <input name="b_addrs" type="text" id="act_numb" class="form-control" placeholder="Enter Beneficiary Address e.g. San Francisco, 4 Newton" required>
                    </div>
                    <label for="" class="col-sm-3 control-label"></label>
                  </div>
                  
                  <div class="form-group">
                    <label for="" class="col-sm-3 control-label" style="color:blue;">Beneficiary Country</label>
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
                    <label for="" class="col-sm-3 control-label" style="color:blue;">Currency</label>
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
                    <label for="" class="col-sm-3 control-label" style="color:blue;">Amount</label>
                    <div class="col-sm-6">
                        <input name="amt_totransfer" type="text" class="form-control" placeholder="Enter Amount to Transfer" required>
                    </div>
                    <label for="" class="col-sm-3 control-label"></label>
                  </div>
                  
                  <div class="form-group">
                    <label for="" class="col-sm-3 control-label" style="color:blue;">Remark</label>
                    <div class="col-sm-6">
                        <textarea name="intreasons" class="form-control" placeholder="State Reasons for Transfer e.g. For House rent, Pay Bonus etc." rows="2" cols="5" required></textarea>
                    </div>
                    <label for="" class="col-sm-3 control-label"></label>
                  </div>
      
            </div>
            
            <div class="form-group" align="right">
                <label for="" class="col-sm-3 control-label" style="color:blue;"></label>
                <div class="col-sm-6">
                    <button name="inter_save" type="submit" class="btn bg-blue btn-flat"><i class="fa fa-forward">&nbsp;Initiate Transfer</i></button>
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
    $phone = $myiphone;
    $currenctdate = date("Y-m-d H:i:s");
	
	  $query = mysqli_query($link, "SELECT * FROM systemset") or die (mysqli_error($link));
    $r = mysqli_fetch_object($query);
    $intlpayment_charges = $row1->intlpayment_charges;
    
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
	        "ToCurrency"    =>  $aggcurrency,
	        "Amount"        =>  $amountWithNoCharges
	        ]
	    );
	    
    $make_call = callAPI('POST', $api_url, json_encode($postdata));
    $result = json_decode($make_call, true);
    
    $convertedAmount = $result['data']['ToAmount'];
    
    $otp_code = $control_pin;
    
    $otpChecker = "pin";
	
	  //New Amount + Charges
    $amountWithCharges = ($intlpayment_charges * $convertedAmount) + $convertedAmount;
	
	  //Data Parser (array size = 6)
    $mydata = $account_number."|".$convertedAmount."|".$amountWithCharges."|".$mnarration."|".$currency."|".$reference."|".$bname."|".$aggwallet_balance;
    
    if($amountWithNoCharges <= 0){

        echo "<div class='alert bg-orange'>Sorry! Invalid amount entered!!</div>";

    }
    elseif($gactive_status == "Suspended"){
        
        echo "<div class='alert bg-orange'>Oops! You are unable to make transfer at the moment!!</div>";
        
    }
	elseif($aggwallet_balance < $amountWithCharges){
	    
	    echo "<div class='alert bg-orange'>Sorry! You need to have upto $aggcurrency.number_format($amountWithCharges,2,'.',',') in your wallet to proceed!!</div>";
      
    }
    elseif($amountWithNoCharges > $aggrtransferLimitPerTrans){

        echo "<div class='alert bg-orange'>Sorry! You can not transfer more than ".$aggcurrency.number_format($aggrtransferLimitPerTrans,2,'.',',')." at once!!</div>";

    }
    elseif($amyDailyTransferLimit == $aggrtransferLimitPerDay || (($amountWithNoCharges + $amyDailyTransferLimit) > $aggrtransferLimitPerDay)){

        echo "<div class='alert bg-orange'>Oops! You have reached your daily limit of ".$aggcurrency.number_format($aggrtransferLimitPerDay,2,'.',',')."</div>";

    }
    elseif($virtual_acctno === "$account_number" || $aggr_id === "$account_number"){
    
        mysqli_query($link, "UPDATE user SET status = 'Suspended' WHERE id = '$aggr_id'");
        mysqli_query($link, "INSERT INTO wallet_case VALUES(null,'','','System','$aggr_id','Suspended','Frudulent Act Detected','$currenctdate')");
  
        echo "<div class='alert bg-orange'>Oops! Request Failed!!</div>";
      
    }
    else{
        
        $update = mysqli_query($link,"INSERT INTO otp_confirmation VALUES(null,'$aggr_id','$otp_code','$mydata','Pending','$currenctdate')")or die(mysqli_error($link));
        
        if(!$update)
        {
            echo "<div class='alert bg-orange'>Unable to Transfer Fund.....Please try again later</div>";
        }
        else{
            
            echo "<div align='center'><img src='../image/checkmark.gif'><p style='color: #38A1F3;'>Transfer Request Received Successfully! Wait a moment to complete the transaction with your Pin</p></div>";
            echo '<meta http-equiv="refresh" content="2;url=transfer_fund.php?id='.$_SESSION['tid'].'&&mid=NDA0&&tab=tab_3&&'.$otpChecker.'">';
        }
    	
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
    
    $verify_otp = mysqli_query($link, "SELECT * FROM otp_confirmation WHERE otp_code = '$myotp' AND userid = '$aggr_id' AND status = 'Pending'");
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
        $senderBalance = $aggwallet_balance - $amountWithCharges;
        $senderEmail = $aggemail;
        $senderName = $myaggname;
        $merchantName = "ESUSU AFRICA AGGREGATOR";

        $transactionDateTime = date("Y-m-d h:i:s");
        //9 rows [0 - 9]
        $dataToProcess = "MPS|".$recipientAcctNo."|".$amountWithNoCharges."|".$rave_secret_key."|".$mnarration."|".$currency."|".$tReference."|".$accountName."|".$aggcurrency."|".$aggwallet_balance;
        //insert txt waiting list
        $mytxtstatus = 'Pending';
        ($amountWithCharges > $aggwallet_balance) ? "" : mysqli_query($link, "INSERT INTO api_txtwaitinglist VALUE(null,'$aggr_id','$tReference','$dataToProcess','$mytxtstatus','$transactionDateTime')");

        //Debit Customer Wallet
        ($amountWithCharges > $aggwallet_balance) ? "" : mysqli_query($link, "UPDATE user SET transfer_balance = '$senderBalance' WHERE id = '$aggr_id' AND created_by = ''");
        
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
                "debit_currency"    =>  $aggcurrency
        );
              
        ($amountWithCharges > $aggwallet_balance) ? "" : $make_call1 = intlCallAPI('POST', $api_url1, json_encode($postdata1));
        ($amountWithCharges > $aggwallet_balance) ? "" : $result1 = json_decode($make_call1, true);
            
        if($result1['status'] == "success" && $aggwallet_balance >= $amountWithCharges){
        
            $transfer_id = $result['data']['id'];
            $transfers_fee = "Gateway Fee: ".$aggcurrency.$result['data']['fee']." | Inhouse Fee: ".$aggcurrency.$calcCharges;
            $mybank_name = $result['data']['bank_name'];
            $status = $result['data']['status'];
            $recipient = $recipientAcctNo.', '.$accountName.', MPS, '.$mybank_name;

            //UPDATE WAITING TXT
            mysqli_query($link, "UPDATE api_txtwaitinglist SET status = '$make_call1' WHERE userid = '$aggr_id' AND refid = '$tReference' AND status = '$mytxtstatus'");
                
            //Log successful transaction history
            mysqli_query($link, "INSERT INTO wallet_history VALUE(null,'','$tReference','$recipient','','$amountWithNoCharges','Debit','$aggcurrency','BANK_TRANSFER','Transfer to $recipient','successful','$transactionDateTime','$aggr_id','$senderBalance','Flutterwave')");
            mysqli_query($link, "INSERT INTO wallet_history VALUE(null,'','$tReference','$recipient','','$calcCharges','Debit','$aggcurrency','Charges','Transfer to $recipient, Remark: $mnarration','successful','$transactionDateTime','$aggr_id','$senderBalance','Flutterwave')");
            
            $sendSMS->bankTransferEmailNotifier($senderEmail, $tReference, $senderName, $transactionDateTime, $accountName, $recipientAcctNo, $mybank_name, $merchantName, $aggcurrency, $amountWithNoCharges, $senderBalance, $aggremailConfigStatus, $aggrfetch_emailConfig);

            mysqli_query($link, "DELETE FROM otp_confirmation WHERE userid = '$aggr_id' AND otp_code = '$myotp' AND status = 'Pending'");
                
            echo "<div class='alert bg-blue'>Transfer Initiated Successful! Current Transfer Wallet Balance is: <b style='color: white;'>".$aggcurrency.number_format($senderBalance,2,'.',',')."</b></div>";
        	echo '<meta http-equiv="refresh" content="3;url=transfer_fund.php?id='.$_SESSION['tid'].'&&mid=NDA0&&tab=tab_3">';
        
        }
        else{

            $myWaitingList = mysqli_query($link, "SELECT * FROM api_txtwaitinglist WHERE userid = '$aggr_id' AND refid = '$tReference' AND status = '$mytxtstatus'");
            $fetchMyWaitingList = mysqli_fetch_array($myWaitingList);
            $myWaitingData = $fetchMyWaitingList['mydata'];

            $myParameter = (explode('|',$myWaitingData));
            $defaultBalance = $myParameter[9];
            
            //Reverse to Customer Wallet
            mysqli_query($link, "UPDATE user SET transfer_balance = '$defaultBalance' WHERE id = '$aggr_id' AND created_by = ''");

            //UPDATE WAITING TXT
            mysqli_query($link, "UPDATE api_txtwaitinglist SET status = '$make_call1' WHERE userid = '$aggr_id' AND refid = '$tReference' AND status = '$mytxtstatus'");

            mysqli_query($link, "DELETE FROM otp_confirmation WHERE userid = '$aggr_id' AND otp_code = '$myotp' AND status = 'Pending'");
            
            echo "<div class='alert bg-orange'>Sorry! Unable to conclude transaction please try again later!!</div>";
            echo '<meta http-equiv="refresh" content="3;url=transfer_fund.php?id='.$_SESSION['tid'].'&&mid=NDA0&&tab=tab_3">';
            
        }
	    
	}
    
}
?>


<?php
if(!(isset($_GET['pin'])))
{
?>
            <div class="box-body">
                
                <input name="account_bank" type="hidden" class="form-control" value="MPS">
                
                <div class="form-group">
                    <label for="" class="col-sm-3 control-label" style="color:blue;">Account Number</label>
                    <div class="col-sm-6">
                        <input name="account_number" type="text" class="form-control" placeholder="Recipient Mpesa Number" required>
                        <span style="color: orange"><b>NOTE:</b> It should always come with the prefix <b>233</b></span>
                    </div>
                    <label for="" class="col-sm-3 control-label"></label>
                </div>
                
                <div class="form-group">
                    <label for="" class="col-sm-3 control-label" style="color:blue;">Currency</label>
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
                    <label for="" class="col-sm-3 control-label" style="color:blue;">Amount</label>
                    <div class="col-sm-6">
                        <input name="amt_totransfer" type="text" class="form-control" placeholder="Enter Amount to Transfer" required>
                    </div>
                    <label for="" class="col-sm-3 control-label"></label>
                </div>
                
                <div class="form-group">
                    <label for="" class="col-sm-3 control-label" style="color:blue;">Beneficiary Name</label>
                    <div class="col-sm-6">
                        <input name="b_name" type="text" id="act_numb" class="form-control" placeholder="Enter Beneficiary Name e.g. Kwame Adew" required>
                    </div>
                    <label for="" class="col-sm-3 control-label"></label>
                </div>
                
                <div class="form-group">
                    <label for="" class="col-sm-3 control-label" style="color:blue;">Remark</label>
                    <div class="col-sm-6">
                        <textarea name="reasons" class="form-control" placeholder="State Reasons for Transfer e.g. For House rent, Pay Bonus etc." rows="2" cols="5" required></textarea>
                    </div>
                    <label for="" class="col-sm-3 control-label"></label>
                </div>

            </div>
            
            <div class="form-group" align="right">
                <label for="" class="col-sm-3 control-label" style="color:blue;"></label>
                <div class="col-sm-6">
                    <button name="mpesa_save" type="submit" class="btn bg-blue btn-flat"><i class="fa fa-forward">&nbsp;Initiate Transfer</i></button>
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
    $phone = $myiphone;
    $currenctdate = date("Y-m-d H:i:s");
    
    $query = mysqli_query($link, "SELECT * FROM systemset") or die (mysqli_error($link));
    $r = mysqli_fetch_object($query);
    $intlpayment_charges = $row1->intlpayment_charges;
    
    
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
	        "ToCurrency"    =>  $aggcurrency,
	        "Amount"        =>  $amountWithNoCharges
	        ]
	    );
	    
    $make_call = callAPI('POST', $api_url, json_encode($postdata));
    $result = json_decode($make_call, true);
    
    $convertedAmount = $result['data']['ToAmount'];
    
    $otp_code = $control_pin;
    
    $otpChecker = "pin";
	
	  //New Amount + Charges
    $amountWithCharges = ($intlpayment_charges * $convertedAmount) + $convertedAmount;
	
	  //Data Parser (array size = 8)
    $mydata = $account_bank."|".$account_number."|".$convertedAmount."|".$amountWithCharges."|".$mnarration."|".$currency."|".$reference."|".$bname."|".$branch_code."|".$aggwallet_balance;

    if($amountWithNoCharges <= 0){

        echo "<div class='alert bg-orange'>Sorry! Invalid amount entered!!</div>";

    }
    elseif($gactive_status == "Suspended"){
        
        echo "<div class='alert bg-orange'>Oops! You are unable to make transfer at the moment!!</div>";
        
    }
	elseif($aggwallet_balance < $amountWithCharges){
	    
	    echo "<div class='alert bg-orange'>Sorry! You need to have upto $aggcurrency.number_format($amountWithCharges,2,'.',',') in your wallet to proceed!!</div>";
      
    }
    elseif($amountWithNoCharges > $aggrtransferLimitPerTrans){

        echo "<div class='alert bg-orange'>Sorry! You can not transfer more than ".$aggcurrency.number_format($aggrtransferLimitPerTrans,2,'.',',')." at once!!</div>";

    }
    elseif($amyDailyTransferLimit == $aggrtransferLimitPerDay || (($amountWithNoCharges + $amyDailyTransferLimit) > $aggrtransferLimitPerDay)){

        echo "<div class='alert bg-orange'>Oops! You have reached your daily limit of ".$aggcurrency.number_format($aggrtransferLimitPerDay,2,'.',',')."</div>";

    }
    elseif($virtual_acctno === "$account_number" || $aggr_id === "$account_number"){
    
        mysqli_query($link, "UPDATE user SET status = 'Suspended' WHERE id = '$aggr_id'");
        mysqli_query($link, "INSERT INTO wallet_case VALUES(null,'','','System','$aggr_id','Suspended','Frudulent Act Detected','$currenctdate')");
  
        echo "<div class='alert bg-orange'>Oops! Request Failed!!</div>";
      
    }
    else{
        
        $update = mysqli_query($link,"INSERT INTO otp_confirmation VALUES(null,'$aggr_id','$otp_code','$mydata','Pending','$currenctdate')") or die(mysqli_error($link));
        
        if(!$update)
        {
            echo "<div class='alert bg-orange'>Unable to Transfer Fund.....Please try again later</div>";
        }
        else{
            
            echo "<div align='center'><img src='../image/checkmark.gif'><p style='color: #38A1F3;'>Transfer Request Received Successfully! Wait a moment to complete the transaction with your Pin</p></div>";
            echo '<meta http-equiv="refresh" content="2;url=transfer_fund.php?id='.$_SESSION['tid'].'&&mid=NDA0&&tab=tab_4&&'.$otpChecker.'">';
        }
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
    
    $verify_otp = mysqli_query($link, "SELECT * FROM otp_confirmation WHERE otp_code = '$myotp' AND userid = '$aggr_id' AND status = 'Pending'");
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
        $senderBalance = $aggwallet_balance - $amountWithCharges;
        $senderEmail = $aggemail;
        $senderName = $myaggname;
        $merchantName = "ESUSU AFRICA AGGREGATOR";

        $transactionDateTime = date("Y-m-d h:i:s");
        //10 rows [0 - 10]
        $dataToProcess = $account_bank."|".$recipientAcctNo."|".$amountWithNoCharges."|".$rave_secret_key."|".$mnarration."|".$currency."|".$tReference."|".$accountName."|".$aggcurrency."|".$branch_code."|".$aggwallet_balance;
        //insert txt waiting list
        $mytxtstatus = 'Pending';
        ($amountWithCharges > $aggwallet_balance) ? "" : mysqli_query($link, "INSERT INTO api_txtwaitinglist VALUE(null,'$aggr_id','$tReference','$dataToProcess','$mytxtstatus','$transactionDateTime')");
        
        //Debit Customer Wallet
        ($amountWithCharges > $aggwallet_balance) ? "" : mysqli_query($link, "UPDATE user SET transfer_balance = '$senderBalance' WHERE id = '$aggr_id' AND created_by = ''");
        
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
                "debit_currency"    =>  $aggcurrency,
                "destination_branch_code"=> $branch_code
        );
            
        ($amountWithCharges > $aggwallet_balance) ? "" : $make_call1 = intlCallAPI('POST', $api_url1, json_encode($postdata1));
        ($amountWithCharges > $aggwallet_balance) ? "" : $result1 = json_decode($make_call1, true);
            
        if($result1['status'] == "success" && $aggwallet_balance >= $amountWithCharges){
                
            $transfer_id = $result['data']['id'];
            $transfers_fee = "Gateway Fee: ".$aggcurrency.$result['data']['fee']." | Inhouse Fee: ".$aggcurrency.$calcCharges;
            $mybank_name = $result['data']['bank_name'];
            $status = $result['data']['status'];
            $recipient = $recipientAcctNo.', '.$accountName.', '.$account_bank.', '.$mybank_name;

            //UPDATE WAITING TXT
            mysqli_query($link, "UPDATE api_txtwaitinglist SET status = '$make_call1' WHERE userid = '$aggr_id' AND refid = '$tReference' AND status = '$mytxtstatus'");
                
            //Log successful transaction history
            mysqli_query($link, "INSERT INTO wallet_history VALUE(null,'','$tReference','$recipient','','$amountWithNoCharges','Debit','$aggcurrency','BANK_TRANSFER','Transfer to $recipient','successful','$transactionDateTime','$aggr_id','$senderBalance','Flutterwave')");
            mysqli_query($link, "INSERT INTO wallet_history VALUE(null,'','$tReference','$recipient','','$calcCharges','Debit','$aggcurrency','Charges','Transfer to $recipient, Remark: $mnarration','successful','$transactionDateTime','$aggr_id','$senderBalance','Flutterwave')");
            
            $sendSMS->bankTransferEmailNotifier($senderEmail, $tReference, $senderName, $transactionDateTime, $accountName, $recipientAcctNo, $mybank_name, $merchantName, $aggcurrency, $amountWithNoCharges, $senderBalance, $aggremailConfigStatus, $aggrfetch_emailConfig);

            mysqli_query($link, "DELETE FROM otp_confirmation WHERE userid = '$aggr_id' AND otp_code = '$myotp' AND status = 'Pending'");
                
            echo "<div class='alert bg-blue'>Transfer Initiated Successful! Current Transfer Wallet Balance is: <b style='color: white;'>".$aggcurrency.number_format($senderBalance,2,'.',',')."</b></div>";
        	  echo '<meta http-equiv="refresh" content="3;url=transfer_fund.php?id='.$_SESSION['tid'].'&&mid=NDA0&&tab=tab_4">';
       
        }
        else{

            $myWaitingList = mysqli_query($link, "SELECT * FROM api_txtwaitinglist WHERE userid = '$aggr_id' AND refid = '$tReference' AND status = '$mytxtstatus'");
            $fetchMyWaitingList = mysqli_fetch_array($myWaitingList);
            $myWaitingData = $fetchMyWaitingList['mydata'];

            $myParameter = (explode('|',$myWaitingData));
            $defaultBalance = $myParameter[10];

            //Reverse to Customer Wallet
            mysqli_query($link, "UPDATE user SET transfer_balance = '$defaultBalance' WHERE id = '$aggr_id' AND created_by = ''");
            
            //UPDATE WAITING TXT
            mysqli_query($link, "UPDATE api_txtwaitinglist SET status = '$make_call1' WHERE userid = '$aggr_id' AND refid = '$tReference' AND status = '$mytxtstatus'");

            mysqli_query($link, "DELETE FROM otp_confirmation WHERE userid = '$aggr_id' AND otp_code = '$myotp' AND status = 'Pending'");
            
            echo "<div class='alert bg-orange'>Sorry! Unable to conclude transaction please try again later!!</div>";
            echo '<meta http-equiv="refresh" content="3;url=transfer.php?tid='.$_SESSION['tid'].'&&acn='.$acctno.'&&mid=NDA4&&tab=tab_4">';
            
        }
	    
	}
	
}
?>


<?php
if(!(isset($_GET['pin'])))
{
?>
            <div class="box-body">
                
                <div class="form-group">
                    <label for="" class="col-sm-3 control-label" style="color:blue;">Country</label>
                    <div class="col-sm-6">
                        <select name="country"  class="form-control select2" id="country" onchange="loadbank1();" required>
                          <option value="" selected>Please Select Country</option>
                          <option value="GH">Ghana</option>
                        </select>
                    </div>
                    <label for="" class="col-sm-3 control-label"></label>
                </div>
                
                <div class="form-group">
                    <label for="" class="col-sm-3 control-label" style="color:blue;">Bank</label>
                    <div class="col-sm-6">
                        <div id="bank_list1"></div>
                    </div>
                    <label for="" class="col-sm-3 control-label"></label>
                </div>
                
                <div class="form-group">
                    <label for="" class="col-sm-3 control-label" style="color:blue;">Bank Branch</label>
                    <div class="col-sm-6">
                        <div id="branch_code"></div>
                    </div>
                    <label for="" class="col-sm-3 control-label"></label>
                </div>
                
                <div class="form-group">
                    <label for="" class="col-sm-3 control-label" style="color:blue;">Account Bank</label>
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
                    <label for="" class="col-sm-3 control-label" style="color:blue;">Account Number</label>
                    <div class="col-sm-6">
                        <input name="account_number" type="text" class="form-control" placeholder="Recipient Mobile Money Number" required>
                    </div>
                    <label for="" class="col-sm-3 control-label"></label>
                </div>
                
                <div class="form-group">
                    <label for="" class="col-sm-3 control-label" style="color:blue;">Amount</label>
                    <div class="col-sm-6">
                        <input name="amt_totransfer" type="text" class="form-control" placeholder="Enter Amount to Transfer" required>
                    </div>
                    <label for="" class="col-sm-3 control-label"></label>
                </div>
                
                <div class="form-group">
                    <label for="" class="col-sm-3 control-label" style="color:blue;">Beneficiary Name</label>
                    <div class="col-sm-6">
                        <input name="b_name" type="text" id="act_numb" class="form-control" placeholder="Enter Beneficiary Name e.g. Kwame Adew" required> 
                    </div>
                    <label for="" class="col-sm-3 control-label"></label>
                </div>
                
                <div class="form-group">
                    <label for="" class="col-sm-3 control-label" style="color:blue;">Remark</label>
                    <div class="col-sm-6">
                        <textarea name="reasons" class="form-control" placeholder="State Reasons for Transfer e.g. For House rent, Pay Bonus etc." rows="2" cols="5" required></textarea>
                    </div>
                    <label for="" class="col-sm-3 control-label"></label>
                </div>

            </div>
            
            <div class="form-group" align="right">
                <label for="" class="col-sm-3 control-label" style="color:blue;"></label>
                <div class="col-sm-6">
                    <button name="ghana_save" type="submit" class="btn bg-blue btn-flat"><i class="fa fa-forward">&nbsp;Initiate Transfer</i></button>
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
    $phone = $myiphone;
    $currenctdate = date("Y-m-d H:i:s");
    
    $query = mysqli_query($link, "SELECT * FROM systemset") or die (mysqli_error($link));
    $r = mysqli_fetch_object($query);
    $intlpayment_charges = $row1->intlpayment_charges;

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
	        "ToCurrency"    =>  $aggcurrency,
	        "Amount"        =>  $amountWithNoCharges
	        ]
	    );
	    
    $make_call = callAPI('POST', $api_url, json_encode($postdata));
    $result = json_decode($make_call, true);
    
    $convertedAmount = $result['data']['ToAmount'];
    
    $otp_code = $control_pin;
    
    $otpChecker = "pin";
	
	  //New Amount + Charges
    $amountWithCharges = ($intlpayment_charges * $convertedAmount) + $convertedAmount;
	
	  //Data Parser (array size = 7)
    $mydata = $acctbank."|".$account_number."|".$convertedAmount."|".$amountWithCharges."|".$mnarration."|".$currency."|".$reference."|".$bname."|".$aggwallet_balance;
	
    if($amountWithNoCharges <= 0){

        echo "<div class='alert bg-orange'>Sorry! Invalid amount entered!!</div>";

    }
    elseif($gactive_status == "Suspended"){
        
        echo "<div class='alert bg-orange'>Oops! You are unable to make transfer at the moment!!</div>";
        
    }
    elseif($aggwallet_balance < $amountWithCharges){
	    
	    echo "<div class='alert bg-orange'>Sorry! You need to have upto $aggcurrency.number_format($amountWithCharges,2,'.',',') in your wallet to proceed!!</div>";
      
    }
    elseif($amountWithNoCharges > $aggrtransferLimitPerTrans){

        echo "<div class='alert bg-orange'>Sorry! You can not transfer more than ".$aggcurrency.number_format($aggrtransferLimitPerTrans,2,'.',',')." at once!!</div>";

    }
    elseif($amyDailyTransferLimit == $aggrtransferLimitPerDay || (($amountWithNoCharges + $amyDailyTransferLimit) > $aggrtransferLimitPerDay)){

        echo "<div class='alert bg-orange'>Oops! You have reached your daily limit of ".$aggcurrency.number_format($aggrtransferLimitPerDay,2,'.',',')."</div>";

    }
    elseif($virtual_acctno === "$account_number" || $aggr_id === "$account_number"){
    
        mysqli_query($link, "UPDATE user SET status = 'Suspended' WHERE id = '$aggr_id'");
        mysqli_query($link, "INSERT INTO wallet_case VALUES(null,'','','System','$aggr_id','Suspended','Frudulent Act Detected','$currenctdate')");
  
        echo "<div class='alert bg-orange'>Oops! Request Failed!!</div>";
      
    }
    else{
        
        $update = mysqli_query($link,"INSERT INTO otp_confirmation VALUES(null,'$aggr_id','$otp_code','$mydata','Pending','$currenctdate')")or die(mysqli_error($link));
        
        if(!$update)
        {
            echo "<div class='alert bg-orange'>Unable to Transfer Fund.....Please try again later</div>";
        }
        else{
            
            echo "<div align='center'><img src='../image/checkmark.gif'><p style='color: #38A1F3;'>Transfer Request Received Successfully! Wait a moment to complete the transaction with your Pin</p></div>";
            echo '<meta http-equiv="refresh" content="2;url=transfer_fund.php?id='.$_SESSION['tid'].'&&mid=NDA0&&tab=tab_5&&'.$otpChecker.'">';
        }
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
    
    $verify_otp = mysqli_query($link, "SELECT * FROM otp_confirmation WHERE otp_code = '$myotp' AND userid = '$aggr_id' AND status = 'Pending'");
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
        $senderBalance = $aggwallet_balance - $amountWithCharges;
        $senderEmail = $aggemail;
        $senderName = $myaggname;
        $merchantName = "ESUSU AFRICA AGGREGATOR";

        $transactionDateTime = date("Y-m-d h:i:s");
        //9 rows [0 - 9]
        $dataToProcess = $acctbank."|".$recipientAcctNo."|".$amountWithNoCharges."|".$rave_secret_key."|".$mnarration."|".$currency."|".$tReference."|".$accountName."|".$aggcurrency."|".$aggwallet_balance;
        //insert txt waiting list
        $mytxtstatus = 'Pending';
        ($amountWithCharges > $aggwallet_balance) ? "" : mysqli_query($link, "INSERT INTO api_txtwaitinglist VALUE(null,'$aggr_id','$tReference','$dataToProcess','$mytxtstatus','$transactionDateTime')");
        
        //Debit Customer Wallet
        ($amountWithCharges > $aggwallet_balance) ? "" : mysqli_query($link, "UPDATE user SET transfer_balance = '$senderBalance' WHERE id = '$aggr_id' AND created_by = ''");
            
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
                "debit_currency"    =>  $aggcurrency
        );
            
        ($amountWithCharges > $aggwallet_balance) ? "" : $make_call1 = intlCallAPI('POST', $api_url1, json_encode($postdata1));
        ($amountWithCharges > $aggwallet_balance) ? "" : $result1 = json_decode($make_call1, true);
            
        if($result['status'] == "success" && $aggwallet_balance >= $amountWithCharges){
                
            $transfer_id = $result['data']['id'];
            $transfers_fee = "Gateway Fee: ".$aggcurrency.$result['data']['fee']." | Inhouse Fee: ".$aggcurrency.$calcCharges;
            $mybank_name = $result['data']['bank_name'];
            $status = $result['data']['status'];
            $recipient = $recipientAcctNo.', '.$accountName.', '.$acctbank.', '.$mybank_name;

            //UPDATE WAITING TXT
            mysqli_query($link, "UPDATE api_txtwaitinglist SET status = '$make_call1' WHERE userid = '$aggr_id' AND refid = '$tReference' AND status = '$mytxtstatus'");
                
            //Log successful transaction history
            mysqli_query($link, "INSERT INTO wallet_history VALUE(null,'','$tReference','$recipient','','$amountWithNoCharges','Debit','$aggcurrency','BANK_TRANSFER','Transfer to $recipient','successful','$transactionDateTime','$aggr_id','$senderBalance','Flutterwave')");
            mysqli_query($link, "INSERT INTO wallet_history VALUE(null,'','$tReference','$recipient','','$calcCharges','Debit','$aggcurrency','Charges','Transfer to $recipient, Remark: $mnarration','successful','$transactionDateTime','$aggr_id','$senderBalance','Flutterwave')");

            $sendSMS->bankTransferEmailNotifier($senderEmail, $tReference, $senderName, $transactionDateTime, $accountName, $recipientAcctNo, $mybank_name, $merchantName, $aggcurrency, $amountWithNoCharges, $senderBalance, $aggremailConfigStatus, $aggrfetch_emailConfig);

            mysqli_query($link, "DELETE FROM otp_confirmation WHERE userid = '$aggr_id' AND otp_code = '$myotp' AND status = 'Pending'");
                
            echo "<div class='alert bg-blue'>Transfer Initiated Successful! Current Transfer Wallet Balance is: <b style='color: white;'>".$bbcurrency.number_format($senderBalance,2,'.',',')."</b></div>";
        	  echo '<meta http-equiv="refresh" content="3;url=transfer_fund.php?id='.$_SESSION['tid'].'&&mid=NDA0&&tab=tab_5">';
                
        }
        else{

            $myWaitingList = mysqli_query($link, "SELECT * FROM api_txtwaitinglist WHERE userid = '$aggr_id' AND refid = '$tReference' AND status = '$mytxtstatus'");
            $fetchMyWaitingList = mysqli_fetch_array($myWaitingList);
            $myWaitingData = $fetchMyWaitingList['mydata'];

            $myParameter = (explode('|',$myWaitingData));
            $defaultBalance = $myParameter[9];
            
            //Reverse to Customer Wallet
            mysqli_query($link, "UPDATE user SET transfer_balance = '$defaultBalance' WHERE id = '$aggr_id' AND created_by = ''");
            
            //UPDATE WAITING TXT
            mysqli_query($link, "UPDATE api_txtwaitinglist SET status = '$make_call1' WHERE userid = '$aggr_id' AND refid = '$tReference' AND status = '$mytxtstatus'");

            mysqli_query($link, "DELETE FROM otp_confirmation WHERE userid = '$aggr_id' AND otp_code = '$myotp' AND status = 'Pending'");
            
            echo "<div class='alert bg-orange'>Sorry! Unable to conclude transaction please try again later!!</div>";
            echo '<meta http-equiv="refresh" content="3;url=transfer_fund.php?id='.$_SESSION['tid'].'&&mid=NDA0&&tab=tab_5">';
            
        }
	    
	}
	
}
?>


<?php
if(!(isset($_GET['pin'])))
{
?>
            <div class="box-body">
                
                <div class="form-group">
                    <label for="" class="col-sm-3 control-label" style="color:blue;">Account Bank</label>
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
                    <label for="" class="col-sm-3 control-label" style="color:blue;">Account Number</label>
                    <div class="col-sm-6">
                        <input name="account_number" type="text" class="form-control" placeholder="Recipient Mobile Money Number" required>
                        <span style="color: orange"><b>NOTE:</b> It should always come with the prefix <b>256</b></span>
                    </div>
                    <label for="" class="col-sm-3 control-label"></label>
                </div>
                
                <div class="form-group">
                    <label for="" class="col-sm-3 control-label" style="color:blue;">Amount</label>
                    <div class="col-sm-6">
                        <input name="amt_totransfer" type="text" class="form-control" placeholder="Enter Amount to Transfer" required>
                    </div>
                    <label for="" class="col-sm-3 control-label"></label>
                </div>
                
                <div class="form-group">
                    <label for="" class="col-sm-3 control-label" style="color:blue;">Beneficiary Name</label>
                    <div class="col-sm-6">
                        <input name="b_name" type="text" id="act_numb" class="form-control" placeholder="Enter Beneficiary Name e.g. Kwame Adew" required>
                    </div>
                    <label for="" class="col-sm-3 control-label"></label>
                </div>
                
                <div class="form-group">
                    <label for="" class="col-sm-3 control-label" style="color:blue;">Reason(s)</label>
                    <div class="col-sm-6">
                        <textarea name="reasons" class="form-control" placeholder="State Reasons for Transfer e.g. For House rent, Pay Bonus etc." rows="2" cols="5" required></textarea>
                    </div>
                    <label for="" class="col-sm-3 control-label"></label>
                </div>

            </div>
            
            <div class="form-group" align="right">
                <label for="" class="col-sm-3 control-label" style="color:blue;"></label>
                <div class="col-sm-6">
                    <button name="ugandan_save" type="submit" class="btn bg-blue btn-flat"><i class="fa fa-forward">&nbsp;Initiate Transfer</i></button>
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

      <div class="tab-pane <?php echo ($_GET['tab'] == 'tab_6') ? 'active' : ''; ?>" id="tab_6">

      </div>
    <?php
  }
  elseif($tab == 'tab_7')
  {
  ?>
      
      <div class="tab-pane <?php echo ($_GET['tab'] == 'tab_7') ? 'active' : ''; ?>" id="tab_7">

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
                    <label for="" class="col-sm-3 control-label" style="color:blue;">Origin Currency</label>
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
                    <label for="" class="col-sm-3 control-label" style="color:blue;">Destination Currency</label>
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
                    <label for="" class="col-sm-3 control-label" style="color:blue;">Amount</label>
                    <div class="col-sm-6">
                        <input name="amt_toconvert" type="text" class="form-control" placeholder="Enter Amount to Convert" required>
                        <span style="color: orange;">NOTE: Pease do not put comma (,) while entering the Amount to Convert</span>
                    </div>
                    <label for="" class="col-sm-3 control-label"></label>
                </div>
      
            </div>
            
            <div class="form-group" align="right">
                <label for="" class="col-sm-3 control-label" style="color:blue;"></label>
                <div class="col-sm-6">
                    <button name="Convert_save" type="submit" class="btn bg-blue btn-flat"><i class="fa fa-refresh">&nbsp;Convert</i></button>
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
  ?>
      <div class="tab-pane <?php echo ($_GET['tab'] == 'tab_9') ? 'active' : ''; ?>" id="tab_9">


      </div>
<?php  
  }
  elseif($tab == 'tab_10'){
  ?>
      
    <div class="tab-pane <?php echo ($_GET['tab'] == 'tab_10') ? 'active' : ''; ?>" id="tab_10">

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