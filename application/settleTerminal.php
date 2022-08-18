<?php include("include/header.php"); ?>

    <div class="modal-dialog">
          <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
            <legend style="color: blue;"><b>Make Settlement</b></legend>
        </div>
        <div class="modal-body">

        <form class="form-horizontal" method="post" enctype="multipart/form-data">

<?php
if(isset($_POST['settleAcct'])){

    //($smethod == "POS Wallet") ? include("../config/restful_apicalls.php") : "";
    
    //($smethod == "POS Wallet") ? $result = array() : "";
    //($smethod == "POS Wallet") ? $result2 = array() : "";
    $pAcct = mysqli_real_escape_string($link, $_POST['pAcct']);
    $tmid = mysqli_real_escape_string($link, $_POST['tmid']);
    $terminalOperator = mysqli_real_escape_string($link, $_POST['tidoperator']);
    $terminalId = mysqli_real_escape_string($link, $_POST['terminalId']);
    $bbranchid = mysqli_real_escape_string($link, $_POST['bbranchid']);
    $SubMerchantName = mysqli_real_escape_string($link, $_POST['SubMerchantName']);
    $emailReceiver = mysqli_real_escape_string($link, $_POST['emailReceiver']);
    $smethod = mysqli_real_escape_string($link, $_POST['smethod']);
    //$pos_walletid = mysqli_real_escape_string($link, $_POST['pos_walletid']);
    $sstatus = mysqli_real_escape_string($link, $_POST['sstatus']);
    $pbal = mysqli_real_escape_string($link, $_POST['pbal']);
    $amt_to_settle = mysqli_real_escape_string($link, $_POST['sbal']);
    $scharge = mysqli_real_escape_string($link, $_POST['scharge']);
    $aggrCommission = mysqli_real_escape_string($link, $_POST['aggrCommission']);
    $oldTransferBal = mysqli_real_escape_string($link, $_POST['oldtbal']);
    $pcommission = mysqli_real_escape_string($link, $_POST['pcommission']);
    $transferBal = $oldTransferBal + $amt_to_settle;

    $DateTime = date('m/d/Y').' '.(date('h') + 1).':'.date('i A');
    $wallet_date_time = date("Y-m-d h:i:s");
    $channel = mysqli_real_escape_string($link, $_POST['tidchannel']);
    $oldSettledBal = mysqli_real_escape_string($link, $_POST['settled_balance']);
    $oldpendingBal = mysqli_real_escape_string($link, $_POST['pending_balance']);

    $systemset = mysqli_query($link, "SELECT * FROM member_settings WHERE companyid = '$bbranchid'");
    $row1 = mysqli_fetch_object($systemset);
    $sysabb = $row1->sender_id;
    $currencyCode = $row1->currency;
    $recipient = $SubMerchantName;
    $bank = $row1->cname.' - '.$terminalId;

    $query = mysqli_query($link, "SELECT * FROM systemset") or die ("Error: " . mysqli_error($link));
    $r = mysqli_fetch_object($query);
    $vatRate = $r->vat_rate;

    //aggregator parameters
    $aggrid = mysqli_real_escape_string($link, $_POST['aggregatorid']);
    $searchAggr = mysqli_query($link, "SELECT * FROM user WHERE id = '$aggrid'");
    $fetchAggr = mysqli_fetch_array($searchAggr);
    $aggrWBalBforComm = $fetchAggr['transfer_balance'];
    $aggrWBalAfterComm = $aggrWBalBforComm + $aggrCommission;

    //Pool Account
    $searchPool = mysqli_query($link, "SELECT * FROM pool_account WHERE account_number = '$pAcct'");
    $fetchPool = mysqli_fetch_array($searchPool);
    $poolUserId = $fetchPool['userid'];
    $poolBal = $fetchPool['availableBal'];
    $poolcomm = ($pAcct == "") ? 0 : number_format(($pcommission * $scharge),2,'.','');
    $poolBalAfterDeduction = ($pAcct == "") ? $poolBal : ($poolBal - $pbal);

    $search_emailConfig = mysqli_query($link, "SELECT * FROM email_config WHERE companyid = '$bbranchid'");
    $fetch_emailConfig = mysqli_fetch_array($search_emailConfig);
    $emailConfigStatus = $fetch_emailConfig['status']; //Activated OR NotActivated

    $paymentMethod = $channel;
    $subject = $channel;
    $date = date("Y-m-d");
    
    if($pbal <= 0){
        
        echo "<script>alert('Opps! Unauthorize activities performed!!'); </script>";
        
    }
    elseif($pbal > $oldpendingBal){
        
        echo "<script>alert('Opps! You are not authorize to settled in excess!!'); </script>";
        
    }
    elseif($pAcct != "" && $pbal > $poolBal){
        
        echo "<script>alert('Opps! No sufficient fund in pool account!!'); </script>";
        
    }
    elseif($smethod == "Transfer Wallet" && $sstatus == "successful"){

        $TransactionID = date("myi").time();
        $status = "Approved";

        //Log and update amount settled for operator
        mysqli_query($link, "UPDATE user SET transfer_balance = '$transferBal' WHERE id = '$terminalOperator'");
        mysqli_query($link, "INSERT INTO wallet_history VALUE(null,'$bbranchid','$TransactionID','POS Settlement with TID: $terminalId','$amt_to_settle','','Credit','$currencyCode','$paymentMethod','SMS Content: Pos Settlement of $amt_to_settle','successful','$wallet_date_time','$terminalOperator','$transferBal','')");

        //Aggregator Commission Balance Update
        ($aggrCommission != "0") ? mysqli_query($link, "UPDATE user SET transfer_balance = '$aggrWBalAfterComm' WHERE id = '$aggrid'") : "";
        ($aggrCommission != "0") ? mysqli_query($link, "INSERT INTO wallet_history VALUE(null,'$bbranchid','$TransactionID','POS with TID: $terminalId','$aggrCommission','','Credit','$currencyCode','TERMINAL_COMMISSION','SMS Content: POS commission with TID: $terminalId','successful','$wallet_date_time','$aggrid','$aggrWBalAfterComm','')") : "";

        //Pool Account History
        ($pAcct != "" && $poolBal >= $pbal) ? mysqli_query($link, "UPDATE pool_account SET availableBal = '$poolBalAfterDeduction' WHERE account_number = '$pAcct'") : "";
        ($pAcct != "" && $poolBal >= $pbal) ? mysqli_query($link, "INSERT INTO pool_history VALUE(null,'$bbranchid','$TransactionID','POS Settlement with TID: $terminalId','','$pbal','Debit','$currencyCode','$paymentMethod','SMS Content: Pos Settlement of $pbal (Charges inclusive)','successful','$wallet_date_time','$terminalOperator','$poolBalAfterDeduction')") : "";
        //Pool Report for wallet history
        ($pAcct != "" && $poolBal >= $pbal && $poolcomm != "0") ? mysqli_query($link, "INSERT INTO wallet_history VALUE(null,'$bbranchid','$TransactionID','POS with TID: $terminalId','','$poolcomm','Debit','$currencyCode','POOL_COMMISSION','SMS Content: POS Commission with TID: $terminalId','successful','$wallet_date_time','$terminalOperator','$transferBal','')") : "";
        ($pAcct != "" && $poolBal >= $pbal && $poolcomm != "0") ? mysqli_query($link, "INSERT INTO income VALUE(null,'','$TransactionID','Charges','$poolcomm','$date','POOL_COMMISSION')") : "";

        //Update Terminal settled balance and total transaction count for both terminal_reg & terminal_report
        $settledBal = $oldSettledBal + $amt_to_settle;
        $pendingBal = $oldpendingBal - $pbal;
        mysqli_query($link, "UPDATE terminal_reg SET pending_balance = '$pendingBal', settled_balance = '$settledBal', dateUpdated = '$wallet_date_time' WHERE id = '$tmid'");

        //Email Notification
        $sendSMS->terminalSettlementEmailNotifier($emailReceiver, $subject, $status, $TransactionID, $DateTime, $paymentMethod, $bank, $SubMerchantName, $pbal, $scharge, $sstatus, $amt_to_settle, $settledAMount, $emailConfigStatus, $fetch_emailConfig);

		echo "<script>alert('Amount Settled Successfully!!!'); </script>";
		echo "<script>window.location='settleTerminal.php?id=".$_SESSION['tid']."&&termId=".$_GET['termId']."'; </script>";

    }
    /**
    elseif($smethod == "POS Wallet" && $sstatus == "successful"){

        $businessWalletID = $payvice_wallet['pv_walletid'];
        $businessUsername = $payvice_wallet['pv_username'];
        $businessPassword = $payvice_wallet['pv_password'];
        $businessTPin = $payvice_wallet['pv_tpin'];

        $search_restapi = mysqli_query($link, "SELECT * FROM restful_apisetup WHERE api_name = 'payvice_lookup'");
        $fetch_restapi = mysqli_fetch_object($search_restapi);
        $api_url = $fetch_restapi->api_url;
        
        //Pass the parameter here
        $postdata =  array(
            "vice_id"	=>	$businessWalletID,
            "user_name" =>  $businessUsername
            );
            
        $make_call = callAPI('POST', $api_url, json_encode($postdata));
        $result = json_decode($make_call, true);
        
        $mylookup = $result['token'];
        
        $search_restapi2 = mysqli_query($link, "SELECT * FROM restful_apisetup WHERE api_name = 'payvice_wallet2wallet'");
        $fetch_restapi2 = mysqli_fetch_object($search_restapi2);
        $api_url2 = $fetch_restapi2->api_url;
        
        //Pass the parameter here
        $postdata2 =  array(
            "vice_id"       =>	$businessWalletID,
            "user_name"     =>  $businessUsername,
            "amount"        =>  $amt_to_settle,
            "beneficiary"   =>  $pos_walletid,
            "auth"          =>  $businessTPin,
            "token"         =>  $mylookup,
            "pwd"           =>  $businessPassword
            );
            
        $make_call2 = callAPI('POST', $api_url, json_encode($postdata2));
        $result2 = json_decode($make_call2, true);

        if($result2['status'] == "1")
        {
            $TransactionID = $result2['txn_ref'];
            $status = "Approved";

            //Log and update amount settled for operator
            //mysqli_query($link, "UPDATE user SET transfer_balance = '$transferBal' WHERE id = '$terminalOperator'");
            mysqli_query($link, "INSERT INTO wallet_history VALUE(null,'$bbranchid','$TransactionID','Transfer to POS Wallet: $pos_walletid','$amt_to_settle','','Credit','$currencyCode','$paymentMethod','SMS Content: Pos Settlement of $amt_to_settle to POS Wallet with Wallet ID: $pos_walletid','successful','$wallet_date_time','$terminalOperator','$oldTransferBal','')");

            //Aggregator Commission Balance Update
            ($aggrCommission != "0") ? mysqli_query($link, "UPDATE user SET transfer_balance = '$aggrWBalAfterComm' WHERE id = '$aggrid'") : "";
            ($aggrCommission != "0") ? mysqli_query($link, "INSERT INTO wallet_history VALUE(null,'$bbranchid','$TransactionID','POS with TID: $terminalId','$aggrCommission','','Credit','$currencyCode','TERMINAL_COMMISSION','SMS Content: POS commission with TID: $terminalId','successful','$wallet_date_time','$aggrid','$aggrWBalAfterComm','')") : "";

            //Update Terminal settled balance and total transaction count for both terminal_reg & terminal_report
            $settledBal = $oldSettledBal + $amt_to_settle;
            $pendingBal = $oldpendingBal - $pbal;
            mysqli_query($link, "UPDATE terminal_reg SET pending_balance = '$pendingBal', settled_balance = '$settledBal', dateUpdated = '$wallet_date_time' WHERE id = '$tmid'");

            include("../cron/posEmailNotifier2.php");

            echo "<script>alert('Amount Settled Successfully!!!');</script>";
            echo "<script>window.location='settleTerminal.php?id=".$_SESSION['tid']."&&termId=".$_GET['termId']."'; </script>";
        }
        else{
            echo "<p style='color: blue; font-size:18px;'>".$result2['message']."</p>";
        }

    }
    */
    elseif($smethod == "Manual" && $sstatus == "successful"){

        $TransactionID = date("myi").time();
        $status = "Approved";
        $correctOldTransferBal = $oldTransferBal;

        //Log and update amount settled for operator
        //mysqli_query($link, "UPDATE user SET transfer_balance = '$correctOldTransferBal' WHERE id = '$terminalOperator'");
        mysqli_query($link, "INSERT INTO wallet_history VALUE(null,'$bbranchid','$TransactionID','POS Manual Settlement: $terminalId','$amt_to_settle','','Credit','$currencyCode','$paymentMethod','SMS Content: Pos Manual Settlement of $amt_to_settle to external account','successful','$wallet_date_time','$terminalOperator','$correctOldTransferBal','')") or die("Error: " . mysqli_error($link));

        //Pool Report for wallet history
        ($pAcct != "" && $poolBal >= $pbal && $poolcomm != "0") ? mysqli_query($link, "INSERT INTO wallet_history VALUE(null,'$bbranchid','$TransactionID','POS with TID: $terminalId','','$poolcomm','Debit','$currencyCode','POOL_COMMISSION','SMS Content: POS Commission with TID: $terminalId','successful','$wallet_date_time','$terminalOperator','$transferBal','')") : "";
        ($pAcct != "" && $poolBal >= $pbal && $poolcomm != "0") ? mysqli_query($link, "INSERT INTO income VALUE(null,'','$TransactionID','Charges','$poolcomm','$date','POOL_COMMISSION')") : "";

        //Aggregator Commission Balance Update
        ($aggrCommission != "0") ? mysqli_query($link, "UPDATE user SET transfer_balance = '$aggrWBalAfterComm' WHERE id = '$aggrid'") or die("Error1: " . mysqli_error($link)) : "";
        ($aggrCommission != "0") ? mysqli_query($link, "INSERT INTO wallet_history VALUE(null,'$bbranchid','$TransactionID','POS with TID: $terminalId','$aggrCommission','','Credit','$currencyCode','TERMINAL_COMMISSION','SMS Content: POS commission with TID: $terminalId','successful','$wallet_date_time','$aggrid','$aggrWBalAfterComm','')") or die("Error2: " . mysqli_error($link)) : "";

        //Pool Account History
        ($pAcct != "" && $poolBal >= $pbal) ? mysqli_query($link, "UPDATE pool_account SET availableBal = '$poolBalAfterDeduction' WHERE account_number = '$pAcct'") : "";
        ($pAcct != "" && $poolBal >= $pbal) ? mysqli_query($link, "INSERT INTO pool_history VALUE(null,'$bbranchid','$TransactionID','POS Settlement with TID: $terminalId','','$pbal','Debit','$currencyCode','$paymentMethod','SMS Content: Pos Settlement of $pbal (Charges inclusive)','successful','$wallet_date_time','$terminalOperator','$poolBalAfterDeduction')") : "";

        //Update Terminal settled balance and total transaction count for both terminal_reg & terminal_report
        $settledBal = $oldSettledBal + $amt_to_settle;
        $pendingBal = $oldpendingBal - $pbal;
        mysqli_query($link, "UPDATE terminal_reg SET pending_balance = '$pendingBal', settled_balance = '$settledBal', dateUpdated = '$wallet_date_time' WHERE id = '$tmid'") or die("Error3: " . mysqli_error($link));

        //Email Notification
        $sendSMS->terminalSettlementEmailNotifier($emailReceiver, $subject, $status, $TransactionID, $DateTime, $paymentMethod, $bank, $SubMerchantName, $pbal, $scharge, $sstatus, $amt_to_settle, $settledAMount, $emailConfigStatus, $fetch_emailConfig);

		echo "<script>alert('Amount Settled Successfully!!!'); </script>";
		echo "<script>window.location='settleTerminal.php?id=".$_SESSION['tid']."&&termId=".$_GET['termId']."'; </script>";

    }
    elseif($sstatus == "declined"){
        
        $TransactionID = date("myi").time();
        $status = "Declined";
        $settledAMount = "0.0";

        mysqli_query($link, "INSERT INTO wallet_history VALUE(null,'$bbranchid','$TransactionID','Settlement Declined - TID: $terminalId','$amt_to_settle','','Credit','$currencyCode','$paymentMethod','SMS Content: POS Settlement of $amt_to_settle has been declined','declined','$wallet_date_time','$terminalOperator','$oldTransferBal','')");

        //Update Terminal settled balance and total transaction count for both terminal_reg & terminal_report
        $pendingBal = $oldpendingBal - $pbal;
        mysqli_query($link, "UPDATE terminal_reg SET pending_balance = '$pendingBal', dateUpdated = '$wallet_date_time' WHERE id = '$tmid'");

        //Email Notification
        $sendSMS->terminalSettlementEmailNotifier($emailReceiver, $subject, $status, $TransactionID, $DateTime, $paymentMethod, $bank, $SubMerchantName, $pbal, $scharge, $sstatus, $amt_to_settle, $settledAMount, $emailConfigStatus, $fetch_emailConfig);

        echo "<script>alert('Settlement Declined Successfully!!!'); </script>";
        echo "<script>window.location='settleTerminal.php?id=".$_SESSION['tid']."&&termId=".$_GET['termId']."'; </script>";

    }

}
?>

<?php
$tmid = $_GET['termId'];
$select = mysqli_query($link, "SELECT * FROM terminal_reg WHERE id = '$tmid'") or die ("Error: " . mysqli_error($link));
$row = mysqli_fetch_array($select);
$terminalId = ($row['trace_id'] == "") ? $row['terminal_id'] : $row['trace_id'];
$settled_balance = $row['settled_balance'];
$pending_balance = $row['pending_balance'];
$calcCharge = ($pending_balance != "0" ? (($row['ctype'] == "Percentage") ? ($row['charges'] / 100) : $row['charges']) : 0);
$charges = ($pending_balance != "0" ? (($row['ctype'] == "Percentage") ? (($row['charges'] / 100) * $pending_balance) : $row['charges']) : 0);
$aggrCommission = ($row['ctype'] == "Percentage") ? ($row['commission'] * $charges) : $row['commission'];
$amount = $pending_balance - $charges;
$bbranchid = $row['merchant_id'];
$tidoperator = $row['tidoperator'];
$SubMerchantName = $row['merchant_name'];
$aggregatorid = $row['initiatedBy'];
$tidchannel = $row['channel'];
$poolAcct = $row['poolAccount'];
$pcomm = $row['charge_comm'];
$vat_Rate = $fetchsys_config['vat_rate'];

//operator parameters
$searchUser = mysqli_query($link, "SELECT * FROM user WHERE id = '$tidoperator'");
$fetchUser = mysqli_fetch_array($searchUser);
$emailReceiver = $fetchUser['email'].',support@esusu.africa';
$oldtbal = $fetchUser['transfer_balance'];
$operatorName = $fetchUser['virtual_acctno'].'-'.$fetchUser['name'].' '.$fetchUser['lname'];
?>

            <div class="box-body">
            
            <input name="pAcct" type="hidden" class="form-control" value="<?php echo $poolAcct; ?>">
            <input name="tmid" type="hidden" class="form-control" value="<?php echo $tmid; ?>">
            <input name="terminalId" type="hidden" class="form-control" value="<?php echo $terminalId; ?>">
            <input name="bbranchid" type="hidden" class="form-control" value="<?php echo $bbranchid; ?>">
            <input name="SubMerchantName" type="hidden" class="form-control" value="<?php echo $SubMerchantName; ?>">
            <input name="emailReceiver" type="hidden" class="form-control" value="<?php echo $emailReceiver; ?>">
            <input name="oldtbal" type="hidden" class="form-control" value="<?php echo $oldtbal; ?>">
            <input name="aggregatorid" type="hidden" class="form-control" value="<?php echo $aggregatorid; ?>">
            <input name="aggrCommission" type="hidden" class="form-control" value="<?php echo $aggrCommission; ?>">
            <input name="tidchannel" type="hidden" class="form-control" value="<?php echo $tidchannel; ?>">
            <input name="settled_balance" type="hidden" class="form-control" value="<?php echo $settled_balance; ?>">
            <input name="pending_balance" type="hidden" class="form-control" value="<?php echo $pending_balance; ?>">
            <input name="pcommission" type="hidden" class="form-control" value="<?php echo $pcomm; ?>">

            <div class="form-group">
            <label for="" class="col-sm-4 control-label" style="color:blue;">Operator:</label>
            <div class="col-sm-7">
                <input name="tidoperator" type="hidden" class="form-control" value="<?php echo $tidoperator; ?>" id="tidoperator">
                <input name="terminalOperator" type="text" class="form-control" value="<?php echo $operatorName; ?>" readonly>
            </div>
            <label for="" class="col-sm-1 control-label"></label>
            </div>

            <div class="form-group">
            <label for="" class="col-sm-4 control-label" style="color:blue;">Pending Amount:</label>
            <div class="col-sm-7">
                <input name="pendamt" type="hidden" class="form-control" value="<?php echo $pending_balance; ?>">
                <input name="pbal" type="text" class="form-control" value="<?php echo $pending_balance; ?>" id="pbal" onkeyup="settleAdd();" required>
            </div>
            <label for="" class="col-sm-1 control-label"></label>
            </div>

            <div class="form-group">
            <label for="" class="col-sm-4 control-label" style="color:blue;">Charges:</label>
            <div class="col-sm-7">
                <input name="mycharges" type="hidden" class="form-control" value="<?php echo (($calcCharge * $vat_Rate) + $calcCharge); ?>" id="mycharges" onkeyup="settleAdd();">
                <input name="scharge" type="text" class="form-control" value="<?php echo $charges ?>" id="scharge" required readonly>
            </div>
            <label for="" class="col-sm-1 control-label"></label>
            </div>

            <div class="form-group">
            <label for="" class="col-sm-4 control-label" style="color:blue;">Amount to Settle:</label>
            <div class="col-sm-7">
                <input name="settledamt" type="hidden" class="form-control" value="<?php echo $amount; ?>">
                <input name="sbal" type="text" class="form-control" value="<?php echo $amount; ?>" id="sbal" required readonly>
            </div>
            <label for="" class="col-sm-1 control-label"></label>
            </div>

            <div class="form-group">
                <label for="" class="col-sm-4 control-label" style="color:blue;">Settlement Method:</label>
                <div class="col-sm-7">
                  <select name="smethod" class="form-control select2" id="settlementMethod" required>
                      <option value="" selected>Select Method</option>
                      <option value="Transfer Wallet">Transfer Wallet</option>
                      <!--<option value="POS Wallet">POS Wallet</option>-->
                      <?php echo ($urole == 'super_admin') ? '<option value="Manual">Manual</option>' : ''; ?>
                    </select>
                  </div>
                <label for="" class="col-sm-1 control-label"></label>
            </div>

            <span id='ShowValueFrank'></span>
            <span id='ShowValueFrank'></span>

            <div class="form-group">
                <label for="" class="col-sm-4 control-label" style="color:blue;">Status:</label>
                <div class="col-sm-7">
                  <select name="sstatus" class="form-control select2" required>
                      <option value="" selected>Select Status</option>
                      <?php echo ($approve_terminal_settlement == '1') ? '<option value="successful">Approve</option>' : ''; ?>
                      <?php echo ($decline_terminal_settlement == '1') ? '<option value="declined">Decline</option>' : ''; ?>
                    </select>
                  </div>
                <label for="" class="col-sm-1 control-label"></label>
            </div>
				  
			</div>
			
			<div class="form-group" align="right">
                <label for="" class="col-sm-4 control-label" style="color:blue;"></label>
                <div class="col-sm-7">
                	<button name="settleAcct" type="submit" class="btn bg-blue"><i class="fa fa-link">&nbsp;Submit</i></button>
                </div>
                <label for="" class="col-sm-1 control-label"></label>
            </div>

			 </form> 

        </div>
        <div style="font-size:10px;"><?php include("include/footer.php"); ?></div>
      </div>   
      
    </div>


