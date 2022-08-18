<div class="box">
	       <div class="box-body">
			<div class="panel panel-success">
            <div class="panel-heading bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">
            <h3 class="panel-title">

            <button type="button" class="btn btn-flat bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>" align="left">&nbsp;<b>Transfer Wallet:</b>&nbsp;
                <strong class="alert bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>">
                <?php
                echo $icurrency.number_format($itransfer_balance,2,'.',',');
                ?> 
                </strong>
            </button>

            <button type="button" class="btn btn-flat bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>" align="left">&nbsp;<b>Pending Balance:</b>&nbsp;
                <strong class="alert bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>">
                <?php
                $search_Terminal = mysqli_query($link, "SELECT SUM(pending_balance) FROM terminal_reg WHERE merchant_id = '$institution_id'");
                $get_searchTerm = mysqli_fetch_array($search_Terminal);
                echo $icurrency.number_format($get_searchTerm['SUM(pending_balance)'],2,'.',',');
                ?> 
                </strong>
            </button>

            </h3>
            </div>

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

             <div class="box-body">
<?php
if(isset($_POST['reqWith']))
{
    //$result = array();
    $terminal = mysqli_real_escape_string($link, $_POST['terminal']);
    $bankname = mysqli_real_escape_string($link, $_POST['bankname']);
    $amt = mysqli_real_escape_string($link, $_POST['amt']);
    $phone = mysqli_real_escape_string($link, $_POST['phone']);
    $email = mysqli_real_escape_string($link, $_POST['email']);
    $tpin = mysqli_real_escape_string($link, $_POST['tpin']);
    $currentDate = date("Y-m-d h:i:s");

    $searchTerm = mysqli_query($link, "SELECT * FROM terminal_reg WHERE terminal_id = '$terminal'");
    $fetchTerm = mysqli_fetch_array($searchTerm);
    $channel = $fetchTerm['channel'];
    $traceid = date("ys").rand(1000000,9999999);
    $pending_balance = $fetchTerm['pending_balance'] + $amt;
    $ctype = $fetchTerm['ctype'];
    $charges = $fetchTerm['charges'];
    $myCgate_charges = ($ctype == "Percentage") ? (($charges / 100) * $amt) : $charges;

    $ussdBank = mysqli_query($link, "SELECT * FROM ussdbank WHERE bankname = '$bankname'");
    $fetchBank = mysqli_fetch_array($ussdBank);
    $ussdCode = $fetchBank['ussdcode'];
    $bankLogoPath = $fetchBank['banklogo'];
    
    $search_gateway = mysqli_query($link, "SELECT * FROM sms WHERE smsuser = '' AND status = 'Activated'");
    $fetch_gateway = mysqli_fetch_object($search_gateway);
    $gateway_uname = $fetch_gateway->username;
    $gateway_pass = $fetch_gateway->password;
    $gateway_api = $fetch_gateway->api;
    $sysabb = $isenderid;

    $query = mysqli_query($link, "SELECT * FROM systemset") or die (mysqli_error($link));
    $r = mysqli_fetch_object($query);

	if($tpin != $myiepin){
	    
        echo "<div class='alert bg-orange'>Opps!...Invalid Transaction Pin!!</div>";
	    
    }
    elseif($itransfer_balance < $myCgate_charges){

        echo "<div class='alert bg-orange'>Opps!...You have insufficient fund in your transfer balance!!</div>";

    }
	else{

        $search_restapi1 = mysqli_query($link, "SELECT * FROM restful_apisetup WHERE api_name = 'cgate_invokereference'");
        $fetch_restapi1 = mysqli_fetch_object($search_restapi1);
        $api_url1 = $fetch_restapi1->api_url;
            
        $postdata = '{"RequestHeader": {"userName":"'.$cgate_username.'","password": "'.$cgate_password.'"},' . 
                    '"RequestDetails": {"terminalId":"'.$terminal.'","Channel":"'.$channel.'","Amount":'.$amt.',' . 
                    '"MerchantId": "'.$cgate_mid.'","TransactionType":"0","SubMerchantName":"'.$inst_name.'",' .
                    '"TraceID": "'.$traceid.'"}}';
                    
        require_once "../config/cgate_class.php";
        
        //MID: 1057ESU10000001      TID: 1057ESU1
                    
        $encryptFile = $new->testingEncrypt($postdata,$api_url1);
        
        $decryptFile = $new->testingDecrypt();
        
        $decoding = json_decode($decryptFile, true);
        
        //print_r($decoding);
        
        $responseCode = $decoding['ResponseHeader']['ResponseCode'];
        
        if($responseCode == "00"){
            
            $Reference = $decoding['ResponseDetails']['Reference'];
            
            $TransactionID = $decoding['ResponseDetails']['TransactionID'];

            $TraceID = $decoding['ResponseDetails']['TraceID'];
    
            $workableUssdCode = "*$ussdCode*000*$Reference#";
            
            $DateTime = date('m/d/Y').' '.(date(h) + 1).':'.date('i A');
            
            $sms = "$sysabb>>>Dear Customer, Dial $workableUssdCode to confirm the WITHDRAWAL of $icurrency".number_format($amt,2,'.',',')." on ".$DateTime.". The Ref No. will EXPIRE in 5 minutes. Thanks";

            $max_per_page = 153;
            $sms_length = strlen($sms);
            $calc_length = ceil($sms_length / $max_per_page);
                
            $sms_charges = $calc_length * $r->fax;
    
            //$balAfterWithdrawalCharges = $itransfer_balance - $myCgate_charges;
    
            $mywallet_balance = $iassigned_walletbal - $sms_charges;
    
            $insert = mysqli_query($link, "INSERT INTO terminal_report VALUES(null,'$institution_id','$TransactionID','$terminal','','$channel','$ussdCode','$inst_name','$TraceID','','$Reference','$amt','$myCgate_charges','$icurrency','$pending_balance','$itransfer_balance','$iuid','Pending','$phone','$email','$currentDate','$isbranchid')") or die ("Error: " . mysqli_error($link));
            $insert = mysqli_query($link, "UPDATE terminal_reg SET pending_balance = '$pending_balance' WHERE merchant_id = '$institution_id' AND terminal_id = '$terminal'");
            ($phone == "" || $iassigned_walletbal < $sms_charges) ? "" : mysqli_query($link, "INSERT INTO wallet_history VALUE(null,'$institution_id','$TransactionID','$phone','$sms_charges','NGN','system','SMS Content: $sms','successful','$currentDate','$iuid','$mywallet_balance','')");
            ($phone == "" || $iassigned_walletbal < $sms_charges ? "" : (($irole != "agent_manager" || $irole != "institution_super_admin" || $irole != "merchant_super_admin") && $isubagent_wallet === "Enabled") ? mysqli_query($link, "UPDATE user SET wallet_balance = '$mywallet_balance' WHERE id = '$iuid'") : mysqli_query($link, "UPDATE institution_data SET wallet_balance = '$mywallet_balance' WHERE institution_id = '$institution_id'"));
    
            if(!$insert){
    
                echo "<div class='alert bg-blue'>Opps!....Unable to process request!!</div>";
    
            }
            else{
    
                ($phone == "" || $iassigned_walletbal < $sms_charges) ? "" : include("../config/send_general_sms.php");
                ($email == "") ? "" : include("../config/email_ussdReferenceCode.php");
                
                echo '<meta http-equiv="refresh" content="1;url=ussd_cardless.php?id='.$_SESSION['tid'].'&&mid=NzAw&&txid='.$TransactionID.'">';
                echo "<div class='bg-blue' align='center'>Request Sent successfully!!</div>";
    
            }
            
        }
        else{
            
            echo "<div class='alert bg-orange'>Opps!...Network Error, please try again later!!</div>";
            
        }

    }

}
?>   


<?php
if(isset($_POST['requery'])){
    
    $txid = $_GET['txid'];
    
    $searchReport = mysqli_query($link, "SELECT * FROM terminal_report WHERE refid = '$txid'");
    $fetchReport = mysqli_fetch_array($searchReport);
    $terminalID = $fetchReport['terminalId'];
    $amt = $fetchReport['amount'];
    $tStatus = $fetchReport['status'];
    
    $search_restapi1 = mysqli_query($link, "SELECT * FROM restful_apisetup WHERE api_name = 'cgate_requery'");
    $fetch_restapi1 = mysqli_fetch_object($search_restapi1);
    $api_url1 = $fetch_restapi1->api_url;

    $postdata = '{"RequestHeader": {"Username":"'.$cgate_username.'","Password": "'.$cgate_password.'"},' . 
                '"RequestDetails": {"TerminalId":"'.$terminalID.'","Amount":'.$amt.',"MerchantId": "'.$cgate_mid.'",' .
                '"TransactionID":"'.$txid.'"}}';
    
    require_once "../config/cgate_requeryClass.php";
                    
    $encryptFile = $new->testingEncrypt($postdata,$api_url1);
        
    $decryptFile = $new->testingDecrypt();
        
    $decoding = json_decode($decryptFile, true);
        
    $responseCode = $decoding['responseCode'];
    
    $reference = $decoding['reference'];
        
    $TransactionID = $decoding['TransactionID'];
    
    $responsemessage = $decoding['responsemessage'];
    
    $tId = $decoding['terminalId'];
    
    $retrievalReference = $decoding['retrievalReference'];
        
    $shortName = $decoding['shortName'];
        
    $customer_mobile = $decoding['customer_mobile'];
        
    $amount = $decoding['amount'];
        
    //Customer Details
    $custEmail = ($fetchReport['cust_email'] == "") ? "" : ",".$fetchReport['cust_email'];
    $currencyCode = $fetchReport['currencyCode'];
    $charges = $fetchReport['charges'];
    $SubMerchantName = $fetchReport['subMerchantName'];
            
    $query = mysqli_query($link, "SELECT * FROM systemset") or die (mysqli_error($link));
    $r = mysqli_fetch_object($query);
        
    $type = "USSD";
    $subject = ($ihalalpay_module == "On") ? "HalalPAY Cardless Withdrawal" : "esusuPAY Cardless Withdrawal";
    $recipient = "Transfer From: ".$shortName;
    $wallet_date_time = date("Y-m-d H:i:s");
    $DateTime = date('m/d/Y').' '.(date(h) + 1).':'.date('i A');
            
    //Terminal Details
    $terminalId = $fetchReport['terminalId'];
    $searchTerminal = mysqli_query($link, "SELECT * FROM terminal_reg WHERE terminal_id = '$terminalId' AND terminal_status = 'Assigned'");
    $fetchTerminal = mysqli_fetch_array($searchTerminal);
    $settlmentType = $fetchTerminal['settlmentType'];
    $pendingBal = ($settlmentType == "manual") ? $fetchTerminal['pending_balance'] : ($fetchTerminal['pending_balance'] - $amount);
    $settledBal = ($settlmentType == "manual") ? $fetchTerminal['settled_balance'] : ($fetchTerminal['settled_balance'] + + ($amount - $charges));
    $tCount = $fetchTerminal['total_transaction_count'] + 1;
    $ctype = $fetchTerminal['ctype'];
    $myCommission = ($ctype == "Percentage") ? (($fetchTerminal['commission'] / 100) * $charges) : $fetchTerminal['commission'];

    //Initiator 
    $bbranchid = $fetchReport['userid'];
    $initiatedBy = $fetchReport['initiatedBy'];
    $searchUser = mysqli_query($link, "SELECT * FROM user WHERE id = '$initiatedBy'");
    $fetchUser = mysqli_fetch_array($searchUser);
    $transferBal = ($settlmentType == "manual") ? ($fetchUser['transfer_balance'] - $charges) : ($fetchUser['transfer_balance'] + $amount - $charges);
    $myPhone = $fetchUser['phone'];

    $searchAdmin = mysqli_query($link, "SELECT * FROM user WHERE created_by = '$bbranchid' AND (role = 'agent_manager' || role = 'institution_super_admin' || role = 'merchant_super_admin')");
    $fetchAdmin = mysqli_fetch_array($searchAdmin);
    $adminVA = $fetchAdmin['virtual_acctno'];
    $adminId = $fetchAdmin['id'];
    $adminBalance = ($myCommission == "0") ? $fetchAdmin['transfer_balance'] : ($fetchAdmin['transfer_balance'] + $myCommission);
            
    //Email Receiver
    $emailReceiver = $fetchUser['email'].$custEmail;
    
    /*if($responseCode == "00" && $tStatus == "Pending"){
            
        //mysqli_query($link, "UPDATE user SET transfer_balance = '$transferBal' WHERE id = '$initiatedBy'");

        //mysqli_query($link, "UPDATE user SET transfer_balance = '$adminBalance' WHERE virtual_acctno = '$adminVA'");
        ($myCommission == "0") ? "" : mysqli_query($link, "INSERT INTO wallet_history VALUE(null,'$bbranchid','$TransactionID','$adminId','$myCommission','$currencyCode','TRANSFER_COMMISSION','SMS Content: TRANSFER_COMMISSION','successful','$wallet_date_time','$initiatedBy','$transferBal','$adminBalance')");

        mysqli_query($link, "UPDATE terminal_report SET pending_balance = '$pendingBal', transfer_balance = '$transferBal', retrievalRef = '$retrievalReference', shortName = '$shortName', status = '$responsemessage' WHERE ussdReference = '$reference' AND refid = '$TransactionID'");
        mysqli_query($link, "UPDATE terminal_reg SET pending_balance = '$pendingBal', settled_balance = '$settledBal', total_transaction_count = '$tCount' WHERE terminal_id = '$terminalId' AND terminal_status = 'Assigned'");
        ($settlmentType == "manual") ? "" : mysqli_query($link, "INSERT INTO wallet_history VALUE(null,'$bbranchid','$TransactionID','$recipient','$amount','$currencyCode','$type','SMS Content: $recipient','successful','$wallet_date_time','$initiatedBy','$transferBal','')");
        mysqli_query($link, "INSERT INTO wallet_history VALUE(null,'$bbranchid','$TransactionID','self','$charges','$currencyCode','Charges','SMS Content: $recipient','successful','$wallet_date_time''$initiatedBy','$transferBal','')");
        include("../config/cgateEmailNotifier.php");
        
        echo "<div align='center'><img src='../image/checkmark.gif'><p style='color: #38A1F3;'>Transaction Successful!</p></div>";
		echo '<meta http-equiv="refresh" content="10;url=ussd_cardless.php?id='.$_SESSION['tid'].'&&mid=NzAw">';
        
    }else*/
    if($responseCode == "00" && $tStatus == "Success"){ 
        
        echo "<div align='center'><img src='../image/checkmark.gif'><p style='color: #38A1F3;'>Transaction Successful!</p></div>";
		echo '<meta http-equiv="refresh" content="1;url=ussd_cardless.php?id='.$_SESSION['tid'].'&&mid=NzAw">';
        
    }
    elseif($responseCode == "09" && $tStatus == "Pending"){ //PENDING STATUS
        
        mysqli_query($link, "UPDATE terminal_report SET status = 'Pending' WHERE ussdReference = '$reference' AND refid = '$TransactionID'");
        
        echo "<div class='alert bg-orange'>Still Pending...Please check again!!</div>";
        
    }elseif($responseCode == "25" && $tStatus == "Pending"){ //EXPIRED CODE
        
        $retrievalRef = $decoding['retrievalReference'];
    
        $sName = $decoding['shortName'];

        $amt2 = $decoding['amount'];

        $searchTerminal = mysqli_query($link, "SELECT * FROM terminal_reg WHERE terminal_id = '$tId' AND terminal_status = 'Assigned'");
        $fetchTerminal = mysqli_fetch_array($searchTerminal);
        $pBal = $fetchTerminal['pending_balance'] - $amt2;
        
        //Update Current Status;
        mysqli_query($link, "UPDATE terminal_report SET status = 'Expired' WHERE ussdReference = '$reference' AND refid = '$TransactionID'");
        mysqli_query($link, "UPDATE terminal_reg SET pending_balance = '$pBal' WHERE terminal_id = '$tId' AND terminal_status = 'Assigned'");
        
        echo "<div class='alert bg-orange'><i class='fa fa-times'>Ref. code has Expired!</i></div>";
        echo '<meta http-equiv="refresh" content="3;url=ussd_cardless.php?id='.$_SESSION['tid'].'&&mid=NzAw">';
        
    }elseif(($responseCode == "02" || $responseCode == "78") && $tStatus == "Pending"){ //Dormant Account(02) - Blacklisted Account(78)
        
        $retrievalRef = $decoding['retrievalReference'];
    
        $sName = $decoding['shortName'];

        $amt2 = $decoding['amount'];

        $searchTerminal = mysqli_query($link, "SELECT * FROM terminal_reg WHERE terminal_id = '$tId' AND terminal_status = 'Assigned'");
        $fetchTerminal = mysqli_fetch_array($searchTerminal);
        $pBal = $fetchTerminal['pending_balance'] - $amt2;
        
        //Update Current Status;
        mysqli_query($link, "UPDATE terminal_report SET status = '$responsemessage' WHERE ussdReference = '$reference' AND refid = '$TransactionID'");
        mysqli_query($link, "UPDATE terminal_reg SET pending_balance = '$pBal' WHERE terminal_id = '$tId' AND terminal_status = 'Assigned'");
        
        echo "<div class='alert bg-orange'><i class='fa fa-times'>".$responsemessage."</i></div>";
        echo '<meta http-equiv="refresh" content="3;url=ussd_cardless.php?id='.$_SESSION['tid'].'&&mid=NzAw">';
        
    }
    
}

?>

			 <form class="form-horizontal" method="post" enctype="multipart/form-data">
			     
			 <?php
			 if(isset($_GET['txid'])){
			     
			    $currenctTxid = $_GET['txid'];
			    
			    $searchTerm = mysqli_query($link, "SELECT * FROM terminal_report WHERE refid = '$currenctTxid'");
                $fetchTerm = mysqli_fetch_array($searchTerm);
                $institutionCode = $fetchTerm['institutionCode'];
                $Ref = $fetchTerm['ussdReference'];
                $dto = date('Y-m-d h:i:s', strtotime($fetchTerm['date_time'] . ' +5 minutes'));
                $myAmt = $fetchTerm['amount'];
                $mycharge = $fetchTerm['charges'];
                $cCode = $fetchTerm['currencyCode'];
                
			    $ussdBank = mysqli_query($link, "SELECT * FROM ussdbank WHERE ussdcode = '$institutionCode'");
                $fetchBank = mysqli_fetch_array($ussdBank);
                $bankLogoPath = $fetchBank['banklogo'];
                
                $workableUssdCode = "*$institutionCode*000*$Ref#";
			     
			    echo "<div align='center' style='font-size:18px;'><img src='../$bankLogoPath' width='150px' height='150px'><br>Use the reference code below to complete your payment using the selected Bank USSD<br>e.g (Dial: <b>$workableUssdCode</b>). Transaction ID: $currenctTxid</div>";
                echo "<div align='center' style='font-size:30px;'><b>$Ref</b></div>";
                echo '<div align="center"><table align="center" width="48%" cellpadding="5" cellspacing="0" style="border-color:black;" border="1">
                        <tr>
                            <th colspan="2"><p align="center">THIS REFCODE WILL EXPIRED IN 5 MINUTES '.$dto.'</p></th>
                        </tr>
                        <tr>
                            <td align="left">
                                <p>Amount: </p>
                            </td>
                            <td align="left">
                                <p>'.$cCode.number_format($myAmt,2,'.',',').'</p>
                            </td>
                        </tr>
                        <tr>
                            <td align="left">
                                <p>Service Charge: </p>
                            </td>
                            <td align="left">
                                <p>'.$cCode.number_format($mycharge,2,'.',',').'</p>
                            </td>
                        </tr>
                        <tr>
                            <td align="left">
                                <p>Amount to Settled: </p>
                            </td>
                            <td align="left">
                                <p>'.$cCode.number_format(($myAmt - $mycharge),2,'.',',').'</p>
                            </td>
                        </tr>
                    </table></div><br>';

                $now = time(); // or your date as well
                $your_date = strtotime($dto);

                $datediff = $your_date - $now;
                $total_day = round($datediff / (60 * 60 * 24));
            ?>
            
            <?php
            if($total_day <= -1){

                echo "<div class='bg-red' align='center'><b>Times Up!</b></div>";

            }
            else{
            ?>
                <script type="text/javascript">
                var yr=<?php echo date('Y', strtotime($dto)); ?>;
                var mo=<?php echo date('m', strtotime($dto)); ?>;
                var da=<?php echo date('d', strtotime($dto)); ?>;
                var ho=<?php echo date('h', strtotime($dto)); ?>;
                var mi=<?php echo date('m', strtotime($dto)); ?>;

                function countdown()
                {
                var today = new Date();
                var todayy = today.getFullYear();
                var todaym = today.getMonth();
                var todayd = today.getDate();
                var todayh = today.getHours();
                var todaymin = today.getMinutes();
                var todaysec = today.getSeconds();
                var todaystring = (todaym+1)+"/"+todayd+"/"+todayy+" "+todayh+":"+todaymin+":"+todaysec;

                futurestring = mo+"/"+da+"/"+yr+" "+ho+":"+mi+":"+"00";

                dd = Date.parse(futurestring)-Date.parse(todaystring);
                dday = Math.floor(dd/(60*60*1000*24)*1);
                dhour = Math.floor((dd%(60*60*1000*24))/(60*60*1000)*1);
                dmin = Math.floor(((dd%(60*60*1000*24))%(60*60*1000))/(60*1000)*1);
                dsec = Math.floor((((dd%(60*60*1000*24))%(60*60*1000))%(60*1000))/1000*1);

                if(document.getElementById)
                {
                if(dsec <= 0 && dmin <= 0 && dday <= 0)
                {
                //When countdown ends!!

                var countdownDiv = document.getElementById("countdown");
                countdownDiv.innerHTML = "<script><span class='small'><font color='red'>Times Up!</font></span></b>";

                }
                //if on day of occasion
                else if(todayy == yr && todaym == (mo-1) && todayd == da)
                {
                // need to handle this!!

                var countdownDiv = document.getElementById("countdown");
                countdownDiv.innerHTML = "<span class='l1-txt2 p-b-4 hours'>" + dmin + "</span><span class='m2-txt2'> Minutes </span> <span class='l1-txt2 p-b-22'>:</span> <span class='l1-txt2 p-b-4 hours'>" + dsec + "</span><span class='m2-txt2'> Seconds </span>";

                setTimeout("countdown()",1000)
                }
                //else, if not yet
                else
                {
                var countdownDiv = document.getElementById("countdown");
                countdownDiv.innerHTML = "<span class='l1-txt2 p-b-4 hours'>" + dday + "</span><span class='m2-txt2'> Day(s) </span> <span class='l1-txt2 p-b-22'>:</span> <span class='l1-txt2 p-b-4 hours'>" + dhour + "</span><span class='m2-txt2'> Hours </span> <span class='l1-txt2 p-b-22'>:</span> <span class='l1-txt2 p-b-4 hours'>" + dmin + "</span><span class='m2-txt2'> Minutes </span> <span class='l1-txt2 p-b-22'>:</span> <span class='l1-txt2 p-b-4 hours'>" + dsec + "</span><span class='m2-txt2'> Seconds </span>";

                setTimeout("countdown()",1000)
                }
                }
                }

                if(document.getElementById)
                {
                document.write("<div id=countdown></div>");
                //document.write("<br>");

                countdown();
                }
                else
                {
                document.write("<br>");
                document.write("<div></div>");
                document.write("<br>");
                }

                </script>
			
            <?php
             }
            }
			?>

             <div class="box-body">
			 
            <div class="form-group">
                <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Terminal ID:</label>
                <div class="col-sm-6">
                    <?php
                    $search = mysqli_query($link, "SELECT * FROM terminal_reg WHERE terminal_status = 'Assigned' AND merchant_id = '$institution_id' AND initiatedBy = '$iuid' AND channel = 'USSD'");
                    $get_search = mysqli_fetch_array($search);
                    ?>
                    <strong style="font-size:20px;"><?php echo $get_search['terminal_id']; ?></strong> <?php echo (isset($_GET['txid'])) ? '<button name="requery" type="submit" class="btn bg-'.(($myaltrow['theme_color'] == "") ? "blue" : $myaltrow['theme_color']).'"><i class="fa fa-refresh">&nbsp;Check Status</i></button>' : ''; ?>
                    <input name="terminal" value="<?php echo $get_search['terminal_id']; ?>" type="hidden">
                </div>
                <label for="" class="col-sm-3 control-label"></label>
            </div>

            
            <div class="form-group">
                <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Bank:</label>
                <div class="col-sm-6">
                    <select name="bankname" class="form-control select2">
                      <option value="" selected>Select Bank</option>
                      <?php
                        $search2 = mysqli_query($link, "SELECT * FROM ussdbank");
                        while($get_search2 = mysqli_fetch_array($search2))
                        {
                        ?>
                      <option value="<?php echo $get_search2['bankname']; ?>"><?php echo $get_search2['bankname']; ?> - <?php echo $get_search2['ussdcode']; ?></option>
                        <?php } ?>
                    </select>
                </div>
                <label for="" class="col-sm-3 control-label"></label>
            </div>

            <div class="form-group">
                <label for="" class="col-sm-3 control-label" style="color:blue;">Amount:</label>
                <div class="col-sm-6">
                  <input name="amt" type="text" class="form-control" placeholder="Enter Amount to Withdraw">
                  </div>
                <label for="" class="col-sm-3 control-label"></label>
            </div>

            <div class="form-group">
                <label for="" class="col-sm-3 control-label" style="color:blue;">Customer Phone (Optional):</label>
                <div class="col-sm-6">
                  <input name="phone" type="text" class="form-control" placeholder="Enter Account Owners Phone Number">
                  </div>
                <label for="" class="col-sm-3 control-label"></label>
            </div>

            <div class="form-group">
                <label for="" class="col-sm-3 control-label" style="color:blue;">Customer Email (Optional):</label>
                <div class="col-sm-6">
                  <input name="email" type="email" class="form-control" placeholder="Enter Account Owners Email">
                  </div>
                <label for="" class="col-sm-3 control-label"></label>
            </div>

            <div class="form-group">
                <label for="" class="col-sm-3 control-label" style="color:blue;">Transaction Pin:</label>
                <div class="col-sm-6">
                  <input name="tpin" type="password" class="form-control" placeholder="Enter your transaction pin">
                  </div>
                <label for="" class="col-sm-3 control-label"></label>
            </div>
				  
			 </div>
			 
			<div class="form-group" align="right">
                <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;"></label>
                <div class="col-sm-6">
                	<button name="reqWith" type="submit" class="btn bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>"><i class="fa fa-link">&nbsp;Submit</i></button>
                </div>
                <label for="" class="col-sm-3 control-label"></label>
            </div>
			  
			 </form> 

</div>	
</div>	
</div>
</div>