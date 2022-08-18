<div class="box">
	       <div class="box-body">
			<div class="panel panel-success">
            <div class="panel-heading bg-default">
            <h3 class="panel-title">
            <button type="button" class="btn btn-flat bg-orange" align="left">&nbsp;<b>Transfer Wallet:</b>&nbsp;
                <strong class="alert bg-blue">
                <?php
                echo $aggcurrency.number_format($aggwallet_balance,2,'.',',');
                ?> 
                </strong>
            </button>
            </h3>
            </div>

<hr>
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
</hr>

             <div class="box-body">
<?php
if(isset($_POST['reqTerminal']))
{
    $terminal = mysqli_real_escape_string($link, $_POST['terminal']);
    $smsalert = mysqli_real_escape_string($link, $_POST['smsalert']);
    $operator = mysqli_real_escape_string($link, $_POST['operator']);
    $channel = mysqli_real_escape_string($link, $_POST['channel']);
    $fund_source = mysqli_real_escape_string($link, $_POST['fund_source']);
    $otpCode = ($fund_source == "1") ? "" : mysqli_real_escape_string($link, $_POST['otpCode']);
    $acct_no = "";
    $bank_code = "";
    $b_name = "";

    $currentDate = date("Y-m-d h:i:s");
    $DateTime = date('m/d/Y').' '.(date(h) + 1).':'.date('i A');

    $searchUser = mysqli_query($link, "SELECT * FROM user WHERE virtual_acctno = '$operator'");
    $fetchUser = mysqli_fetch_array($searchUser);
    $opBranchid = $fetchUser['branchid'];
    $operatorid = $fetchUser['id'];
    $institution_id = $fetchUser['created_by'];

    $searchInst = mysqli_query($link, "SELECT * FROM institution_data WHERE institution_id = '$institution_id'");
    $fetchInst = mysqli_fetch_array($searchInst);
    $merchant_name = $fetchInst['institution_name'];
    $merchant_email = $fetchInst['official_email'];
    $merchant_phone_no = $fetchInst['official_phone'];
    $hslip = $merchant_name;
    $fslip = $merchant_name;
    
    $source = ($fund_source == "0") ? $fetchUser['transfer_balance'] : $aggwallet_balance;

    $select = mysqli_query($link, "SELECT * FROM terminal_reg WHERE (trace_id = '$terminal' OR terminal_id = '$terminal')") or die ("Error: " . mysqli_error($link));
    $row = mysqli_fetch_array($select);
    $activationFee = $row['activation_fee'];
    $activationComm = $row['activation_comm'];
    $terminalId = $row['terminal_id'];
    $trace_id = ($row['trace_id'] == "") ? "None" : $row['trace_id'];
    $correctTID_TraceID = ($row['trace_id'] == "") ? $terminalId : $row['trace_id'];

    $verify_otp = mysqli_query($link, "SELECT * FROM otp_confirmation WHERE otp_code = '$otpCode' AND userid = '$aggr_id' AND status = 'Pending'");
	$fetch_data = mysqli_fetch_array($verify_otp);
	$otpnum = mysqli_num_rows($verify_otp);

    if($fund_source == "0" && $otpnum == 0){

        //DELETE OTP RECORDS
        mysqli_query($link, "DELETE FROM otp_confirmation WHERE userid = '$aggr_id' AND otp_code = '$otpCode' AND status = 'Pending'");
        echo "<div class='alert bg-orange'>Opps!...Invalid OTP Entered!!</div>";

    }
    elseif($source < $activationFee){
        
        //DELETE OTP RECORDS
        mysqli_query($link, "DELETE FROM otp_confirmation WHERE userid = '$aggr_id' AND otp_code = '$otpCode' AND status = 'Pending'");
        echo "<div class='alert bg-orange'>Opps!...Insufficient fund!!</div>";

    }
    else{

        $TransactionID = "REQ-".date("yi").time();
        $walletBal = $source - $activationFee;
        $wallet_date_time = date("Y-m-d H:i:s");

        $query = mysqli_query($link, "SELECT * FROM systemset") or die (mysqli_error($link));
        $r = mysqli_fetch_object($query);
        $receiverEmail = $r->email;
        $status = "PENDING";
        $aggr_commission = ($activationComm/100) * $activationFee;
        $realAggrBal = ($fund_source == "0") ? $aggwallet_balance : $walletBal;
        $aggrBal = $realAggrBal + $aggr_commission;

        $payer = ($fund_source == "0") ? $operatorid : $aggr_id;

        ($activationFee == "0" ? "" : (($fund_source == "0") ? $update_records = mysqli_query($link, "UPDATE user SET transfer_balance = '$walletBal' WHERE id = '$operatorid'") : ""));
        ($activationFee == "0" ? "" : (($fund_source == "1") ? $update_records = mysqli_query($link, "UPDATE user SET transfer_balance = '$walletBal' WHERE id = '$aggr_id'") : ""));
        ($activationFee == "0") ? "" : mysqli_query($link, "INSERT INTO wallet_history VALUE(null,'$institution_id','$TransactionID','$channel Request with TID/TraceID: $correctTID_TraceID','','$activationFee','Debit','$aggcurrency','Terminal_Activation_Fee','SMS Content: Payment of $channel terminal activation request with TID: $terminal_id, trace id: $trace_id','successful','$wallet_date_time','$payer','$walletBal','')");
        
        ($activationFee == "0") ? "" : $update_records = mysqli_query($link, "UPDATE user SET transfer_balance = '$aggrBal' WHERE id = '$aggr_id'");
        ($activationFee == "0") ? "" : mysqli_query($link, "INSERT INTO wallet_history VALUE(null,'$institution_id','$TransactionID','$channel Request Commission with TID/TraceID: $correctTID_TraceID','$aggr_commission','','Credit','$aggcurrency','TERMINAL_COMMISSION','SMS Content: Commission for the Payment of $channel terminal activation request with TID: $terminal_id, trace id: $trace_id','successful','$wallet_date_time','$aggr_id','$aggrBal','')");
        
        $update = mysqli_query($link, "UPDATE terminal_reg SET initiatedBy = '$aggr_id', tidoperator = '$operatorid', branchid = '$opBranchid', merchant_id = '$institution_id', merchant_name = '$merchant_name', merchant_email = '$merchant_email', merchant_phone_no = '$merchant_phone_no', sms_alert = '$smsalert', slip_header = '$hslip', slip_footer = '$fslip', terminal_status = 'Booked', dateUpdated = '$currentDate', assignedBy = '' WHERE (trace_id = '$terminal' OR terminal_id = '$terminal')") or die ("Error: " . mysqli_error($link));
        
        //Email Notification
        $sendSMS->terminalRequestEmailNotifier($receiverEmail, $status, $terminalId, $trace_id, $merchant_name, $merchant_email, $merchant_phone_no, $activationFee, $DateTime, $aggremailConfigStatus, $aggrfetch_emailConfig);
        
        echo "<div class='alert bg-blue'>Request Sent successfully!!</div>";

    }

}
?>           
			 <form class="form-horizontal" method="post" enctype="multipart/form-data">

             <div class="box-body">

             <div class="form-group">
                <label for="" class="col-sm-3 control-label" style="color:blue;">Operator:</label>
                <div class="col-sm-6">
                    <select name="operator" class="form-control select2" id="verify_virtualacct" required>
                      <option value="" selected>Select Operator</option>
                      <option disabled>Filter By Client</option>
                        <?php
                        $get = mysqli_query($link, "SELECT institution_id, institution_name FROM institution_data WHERE aggr_id = '$aggr_id' ORDER BY id DESC") or die (mysqli_error($link));
                        while($rows = mysqli_fetch_array($get))
                        {
                            $institution_id = $rows['institution_id'];
                            $instName = $rows['institution_name'];
                            $search = mysqli_query($link, "SELECT account_name, account_number FROM virtual_account WHERE companyid = '$institution_id'");
                            $get_search = mysqli_fetch_array($search);
                        ?>
                            <option value="<?php echo $get_search['account_number']; ?>"><?php echo $get_search['account_number'].' - '.$get_search['account_name']; ?> - <?php echo $instName; ?></option>
                        <?php } ?>

                        <option disabled>Filter By Agent Wallet</option>
                        <?php
                        $get2 = mysqli_query($link, "SELECT * FROM user WHERE virtual_acctno != '' AND acctOfficer = '$aggrid' AND reg_type = 'agent' ORDER BY userid DESC") or die ("Error: " . mysqli_error($link));
                        while($rows2 = mysqli_fetch_array($get2))
                        {
                        ?>
                        <option value="<?php echo $rows2['virtual_acctno']; ?>"><?php echo $rows2['virtual_acctno'].' - '.$rows2['name'].' '.$rows2['fname'].' '.$rows2['mname']; ?></option>
                        <?php } ?>
                    </select>
                </div>
                <label for="" class="col-sm-3 control-label"></label>
            </div>

            <div class="form-group">
                <label for="" class="col-sm-3 control-label" style="color:blue;">Channel:</label>
                <div class="col-sm-6">
                    <select name="channel" class="form-control select2" id="terminalChannel" required>
                      <option value="" selected>Select Channel</option>
                     <option value="POS">POS Device</option> 
                      <option value="USSD">USSD for Cardless Withdrawal</option>
                    </select>
                </div>
                <label for="" class="col-sm-3 control-label"></label>
            </div>

            <span id='ShowValueFrank'></span>
            <span id='ShowValueFrank'></span>

            <div class="form-group">
                <label for="" class="col-sm-3 control-label" style="color:blue;">Fund Source</label>
                <div class="col-sm-6">
                    <select name="fund_source" class="form-control select2" style="width: 100%;" id="fund_source" /required>
                        <option value="" selected="selected">---Choose Funding Source---</option>
                        <option value="0">Client Wallet</option>
                        <option value="1">My Wallet</option>
                    </select>
                </div>
                <label for="" class="col-sm-3 control-label"></label>
            </div>

            <span id='ShowValueFrank2'></span>
            <span id='ShowValueFrank2'></span>
            
            <div class="form-group">
                <label for="" class="col-sm-3 control-label" style="color:blue;">SMS Alert Option:</label>
                <div class="col-sm-6">
                    <select name="smsalert" class="form-control select2" required>
                      <option value="" selected>Select SMS Alert Option</option>
                      <option value="Yes">Yes</option>
                      <option value="No">No</option>
                    </select>
                </div>
                <label for="" class="col-sm-3 control-label"></label>
            </div>

		</div>
			 
			<div class="form-group" align="right">
                <label for="" class="col-sm-3 control-label" style="color:blue;"></label>
                <div class="col-sm-6">
                	<button name="reqTerminal" type="submit" class="btn bg-blue"><i class="fa fa-link">&nbsp;Submit</i></button>
                </div>
                <label for="" class="col-sm-3 control-label"></label>
            </div>
			  
			 </form> 

</div>	
</div>	
</div>
</div>