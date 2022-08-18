<div class="box">
	       <div class="box-body">
			<div class="panel panel-success">
            <div class="panel-heading bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">
            <h3 class="panel-title"> <i class="fa fa-desktop"></i>  Withdraw Commission</h3>
            </div>
<?php
$id = mysqli_real_escape_string($link, $_GET['idm']);
$search_info = mysqli_query($link, "SELECT * FROM till_account WHERE id = '$id'");
$fetch_info = mysqli_fetch_object($search_info);
$companyid = $fetch_info->companyid;
$mbranchid = $fetch_info->branch;
$cashier_id = $fetch_info->cashier;
$balance = $fetch_info->balance;

$search_branch = mysqli_query($link, "SELECT * FROM branches WHERE branchid = '$mbranchid'");
$fetch_branch = mysqli_fetch_array($search_branch);
$bname = $fetch_branch['bname'];

$search_cashier = mysqli_query($link, "SELECT * FROM user WHERE id = '$cashier_id'");
$fetch_cashier = mysqli_fetch_array($search_cashier);
$cashier = $fetch_cashier['name'].' '.$fetch_cashier['lname'];
?>			

             <div class="box-body">
 <?php
if(isset($_POST['save']))
{
	$teller =  mysqli_real_escape_string($link, $_POST['teller']);
	$mycashier_id = mysqli_real_escape_string($link, $_POST['cashier']);
	$currency =  mysqli_real_escape_string($link, $_POST['currency']);
	$amount_incomm = preg_replace('/[^0-9.]/', '', mysqli_real_escape_string($link, $_POST['amount_incomm']));
	$amount_tocashout =  preg_replace('/[^0-9.]/', '', mysqli_real_escape_string($link, $_POST['amount_tocashout']));
	$note_comment =  mysqli_real_escape_string($link, $_POST['note_comment']);

	if($amount_incomm < $amount_tocashout){

		echo "<div class='alert bg-orange'>Oops! Insufficient Fund in Staff Commission Balance.</div>";

	}else{

		$remaining_balance = $amount_incomm - $amount_tocashout;

		$search_user = mysqli_query($link, "SELECT * FROM user WHERE id = '$mycashier_id'");
		$fetch_user = mysqli_fetch_array($search_user);
		$phone = $fetch_user['phone'];
		
		$search_memberset = mysqli_query($link, "SELECT * FROM member_settings WHERE companyid = '$institution_id'");
		$fetch_memberset = mysqli_fetch_array($search_memberset);
		$sysabb = $fetch_memberset['sender_id'];
		
		$sms_rate = $fetchsys_config['fax'];
		$refid = "EA-tillAlert-".rand(1000000,9999999);
		$mybalance = $iwallet_balance - $sms_rate;
		$formated_cashout_fund = number_format($amount_tocashout,2,'.',',');
		$formated_balance = number_format($remaining_balance,2,'.',',');
		$mycurrentTime = date("Y-m-d h:i:s");

		$maintenance_row = mysqli_num_rows($isearch_maintenance_model);
		$debitWAllet = ($getSMS_ProviderNum == 1 || ($maintenance_row == 1 && $billing_type == "PAYGException")) ? "No" : "Yes";

		$sms = "$sysabb>>>Your Till Account have been Debited. Amount Debited: -$formated_settled_fund, Till Balance: -$formated_balance";

		mysqli_query($link, "UPDATE till_account SET commission_balance = '$remaining_balance' WHERE cashier = '$mycashier_id'") or die ("Error: " . mysqli_error($link));
		//mysqli_query($link, "INSERT INTO fund_settlement VALUES(null,'$txid','$institution_id','$mbranchid','$teller','$mycashier_id','$currency','$amount_tocashout','$remaining_balance','$note_comment',NOW())") or die ("Error: " . mysqli_error($link));
        mysqli_query($link, "INSERT INTO fund_allocation_history VALUES(null,'$refid','$institution_id','$iuid','$mbranchid','$teller','$mycashier_id','$amount_tocashout','Debit','WITHDRAW_TILL_COMMISSION','$currency','$remaining_balance','$note_comment','successful','$mycurrentTime')") or die ("Error: " . mysqli_error($link));
        
		(($billing_type == "PAYGException") ? "" : (($debitWAllet == "No") ? $sendSMS->instGeneralAlert($ozeki_password, $ozeki_url, $sysabb, $phone, $sms, $institution_id, $refid, $sms_rate, $iuid, $mybalance, $debitWallet) : (($debitWAllet == "Yes" && $sms_rate <= $iwallet_balance) ? $sendSMS->instGeneralAlert($ozeki_password, $ozeki_url, $sysabb, $phone, $sms, $institution_id, $refid, $sms_rate, $iuid, $mybalance, $debitWallet) : "")));
		$sendSMS->tillFundingEmailNotifier($email, $refid, $iusername, $ptype, $mycashier_id, $icurrency, $formated_cashout_fund, $formated_balance, $iemailConfigStatus, $ifetch_emailConfig);

		echo "<div class='alert bg-blue'>Commission Withdraw Successfully!</div>";
		
	}
}
?>           
			 <form class="form-horizontal" method="post" enctype="multipart/form-data">

             <div class="box-body">

			<div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Office</label>
                  <div class="col-sm-9">
                  <input name="office" type="text" class="form-control" value="<?php echo ($fetch_info->branch == '') ? 'Head Office' : $bname; ?>" readonly>
                  </div>
                  </div>

			<div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Teller</label>
                  <div class="col-sm-9">
                  <input name="teller" type="text" class="form-control" value="<?php echo $fetch_info->teller; ?>" readonly>
                  </div>
                  </div>

			<div class="form-group">
                <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Cashier Name</label>
                <div class="col-sm-9">
				<select name="cashier" class="form-control select2" readonly>
					<option value="<?php echo $fetch_info->cashier; ?>" selected='selected'><?php echo $cashier; ?></option>
				</select>
				</div>
            </div>

            <div class="form-group">
                <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Currency</label>
                <div class="col-sm-9">
					<select name="currency" class="form-control select2" required>
						<option value="<?php echo $icurrency; ?>"><?php echo $icurrency; ?></option>
					</select>
				</div>
            </div>

            <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Commission Balance</label>
                  <div class="col-sm-9">
                  <input name="amount_incomm" type="text" class="form-control" value="<?php echo number_format($fetch_info->commission_balance,2,'.',','); ?>" readonly>
                  </div>
                  </div>

            <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Amount to Cashout</label>
                  <div class="col-sm-9">
                  <input name="amount_tocashout" type="text" class="form-control" placeholder="Enter Amount to Cashout" required>
                  </div>
                  </div>

            <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Note/Comment</label>
                  <div class="col-sm-9">
				  <textarea name="note_comment"  class="form-control" rows="2" cols="80" placeholder="Note / Comment" required></textarea>
                </div>
                </div>		  
				  
			 </div>
			 
			  <div align="right">
              <div class="box-footer">
                <button name="save" type="submit" class="btn bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>"><i class="fa fa-save">&nbsp;Withdrawal</i></button>
              </div>
			  </div>
			  
			 </form> 

</div>	
</div>	
</div>
</div>