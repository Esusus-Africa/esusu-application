<div class="box">
	       <div class="box-body">
			<div class="panel panel-success">
            <div class="panel-heading bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">
            <h3 class="panel-title">
            <button type="button" class="btn btn-flat bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>" align="left" disabled>&nbsp;<b>Transfer Wallet:</b>&nbsp;
                <strong class="alert bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">
                <?php
                echo $icurrency.number_format($itransfer_balance,2,'.',',');
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
if(isset($_POST['reqTerminal']))
{
    $terminal = mysqli_real_escape_string($link, $_POST['terminal']);
    $smsalert = mysqli_real_escape_string($link, $_POST['smsalert']);
    $operator = mysqli_real_escape_string($link, $_POST['operator']);
    $channel = mysqli_real_escape_string($link, $_POST['channel']);
    $acct_no = $isettlement_acctno;
    $bank_code = $isettlement_bankcode;
    $b_name = $isettlement_acctname;
    $hslip = $inst_name;
    $fslip = $inst_name;
	
	$search_code = mysqli_query($link, "SELECT * FROM bank_list WHERE bankcode = '$branch_code'");
    $fetch_code = mysqli_fetch_object($search_code);
    $bankName = $fetch_code->bankname;
    $merchant_name = $inst_name;
    $merchant_email = $inst_email;
    $merchant_phone_no = $inst_phone;
    $currentDate = date("Y-m-d h:i:s");
    $DateTime = date('m/d/Y').' '.(date(h) + 1).':'.date('i A');

    $searchUser = mysqli_query($link, "SELECT * FROM user WHERE id = '$operator'");
    $fetchUser = mysqli_fetch_array($searchUser);
    $opBranchid = $fetchUser['branchid'];

    $select = mysqli_query($link, "SELECT * FROM terminal_reg WHERE (trace_id = '$terminal' OR terminal_id = '$terminal')") or die ("Error: " . mysqli_error($link));
    $row = mysqli_fetch_array($select);
    $activationFee = $row['activation_fee'];
    $terminalId = $row['terminal_id'];
    $trace_id = ($row['trace_id'] == "") ? "None" : $row['trace_id'];

    if($itransfer_balance < $activationFee){
        
        echo "<div class='alert bg-orange'>Opps!...You have insufficient fund in your wallet!!</div>";

    }
    else{

        $TransactionID = "REQ-".date("yi").time();
        $walletBal = $itransfer_balance - $activationFee;
        $wallet_date_time = date("Y-m-d H:i:s");

        $query = mysqli_query($link, "SELECT * FROM systemset") or die (mysqli_error($link));
        $r = mysqli_fetch_object($query);
        $receiverEmail = $r->email;
        $status = "PENDING";

        ($activationFee == "0") ? "" : mysqli_query($link, "UPDATE user SET transfer_balance = '$walletBal' WHERE id = '$iuid'");
        ($activationFee == "0") ? "" : mysqli_query($link, "INSERT INTO wallet_history VALUE(null,'$institution_id','$TransactionID','$channel Request with Trace ID: $trace_id','','$activationFee','Debit','$icurrency','Terminal_Activation_Fee','SMS Content: Payment for the request of $channel terminal with TID: $terminal_id, trace id: $trace_id','successful','$wallet_date_time','$iuid','$walletBal','')");
        
        $update = mysqli_query($link, "UPDATE terminal_reg SET initiatedBy = '$iuid', tidoperator = '$operator', branchid = '$opBranchid', merchant_id = '$institution_id', merchant_name = '$merchant_name', merchant_email = '$merchant_email', merchant_phone_no = '$merchant_phone_no', sms_alert = '$smsalert', slip_header = '$hslip', slip_footer = '$fslip', terminal_status = 'Booked', dateUpdated = '$currentDate', assignedBy = '' WHERE (trace_id = '$terminal' OR terminal_id = '$terminal')") or die ("Error: " . mysqli_error($link));
        
        //Email Notification
        $sendSMS->terminalRequestEmailNotifier($receiverEmail, $status, $terminalId, $trace_id, $merchant_name, $merchant_email, $merchant_phone_no, $activationFee, $DateTime, $iemailConfigStatus, $ifetch_emailConfig);
        
        echo "<div class='alert bg-blue'>Request Sent successfully!!</div>";

    }

}
?>           
			 <form class="form-horizontal" method="post" enctype="multipart/form-data">

             <div class="box-body">

             <div class="form-group">
                <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Operator:</label>
                <div class="col-sm-6">
                    <select name="operator"  class="form-control select2" required>
                      <option value="" selected>Select Operator</option>
                        <?php
                        $search = mysqli_query($link, "SELECT * FROM user WHERE created_by = '$institution_id' AND comment = 'Approved'");
                        while($get_search = mysqli_fetch_array($search))
                        {
                        ?>
                      <option value="<?php echo $get_search['id']; ?>"><?php echo $get_search['virtual_acctno']; ?> - <?php echo $get_search['name'].' '.$get_search['lname'].' '.$get_search['mname']; ?></option>
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
                <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">SMS Alert Option:</label>
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
                <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;"></label>
                <div class="col-sm-6">
                	<button name="reqTerminal" type="submit" class="btn bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>"><i class="fa fa-link">&nbsp;Submit</i></button>
                </div>
                <label for="" class="col-sm-3 control-label"></label>
            </div>
			  
			 </form> 

</div>	
</div>	
</div>
</div>