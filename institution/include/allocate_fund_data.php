<div class="box">
	       <div class="box-body">
			<div class="panel panel-success">
            <div class="panel-heading bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">
            <h3 class="panel-title"> <i class="fa fa-desktop"></i>  Allocate Fund</h3>
            </div>
<?php
$id = mysqli_real_escape_string($link, $_GET['idm']);
$search_info = mysqli_query($link, "SELECT * FROM till_account WHERE id = '$id'");
$fetch_info = mysqli_fetch_object($search_info);
$mbranchid = $fetch_info->branch;
$cashier_id = $fetch_info->cashier;
$balance = $fetch_info->balance;

$search_branch = mysqli_query($link, "SELECT * FROM branches WHERE branchid = '$mbranchid'");
$fetch_branch = mysqli_fetch_array($search_branch);
$bname = $fetch_branch['bname'];

$search_cashier = mysqli_query($link, "SELECT * FROM user WHERE id = '$cashier_id'");
$fetch_cashier = mysqli_fetch_array($search_cashier);
$cashier2 = $fetch_cashier['name'].' '.$fetch_cashier['lname'];
?>

             <div class="box-body">
<?php
if(isset($_POST['save']))
{
	$ptype = "Credit";
	$txid = date("dy").time();
	$issuerid = mysqli_real_escape_string($link, $_GET['id']);
	$teller =  mysqli_real_escape_string($link, $_POST['teller']);
	$cashier = mysqli_real_escape_string($link, $_POST['cashier']);
	$currency =  mysqli_real_escape_string($link, $_POST['currency']);
	$amount_allocated =  mysqli_real_escape_string($link, $_POST['amount_allocated']);
	$note_comment =  mysqli_real_escape_string($link, $_POST['note_comment']);
	
	$total_amount = $balance + $amount_allocated;

	$search_user = mysqli_query($link, "SELECT * FROM user WHERE id = '$cashier'");
	$fetch_user = mysqli_fetch_array($search_user);
	$phone = $fetch_user['phone'];
	$email = $fetch_user['email'];
	
	$search_memberset = mysqli_query($link, "SELECT * FROM member_settings WHERE companyid = '$institution_id'");
	$fetch_memberset = mysqli_fetch_array($search_memberset);
	$sysabb = $fetch_memberset['sender_id'];
	
	$sms_rate = $fetchsys_config['fax'];
    $refid = uniqid("EA-emptillFund-").time();
    $mybalance = $iwallet_balance - $sms_rate;
    $formated_allocated_fund = number_format($amount_allocated,2,'.',',');
	$formated_balance = number_format($total_amount,2,'.',',');
	$mycurrentTime = date("Y-m-d h:i:s");

	$maintenance_row = mysqli_num_rows($isearch_maintenance_model);
	$debitWAllet = ($getSMS_ProviderNum == 1 || ($maintenance_row == 1 && $billing_type == "PAYGException")) ? "No" : "Yes";
	
	$sms = "$sysabb>>>Your Till Account have been Credited. Amount Credited: -$formated_allocated_fund, Till Balance: -$formated_balance";

	mysqli_query($link, "UPDATE till_account SET balance = '$total_amount' WHERE id = '$id'");
	mysqli_query($link, "INSERT INTO fund_allocation_history VALUES(null,'$txid','$institution_id','$issuerid','$mbranchid','$teller','$cashier','$amount_allocated','Credit','MANUAL_FUNDING','$currency','$total_amount','$note_comment','successful','$mycurrentTime')") or die ("Error: " . mysqli_error($link));
	
	(($billing_type == "PAYGException") ? "" : (($debitWAllet == "No") ? $sendSMS->instGeneralAlert($ozeki_password, $ozeki_url, $sysabb, $phone, $sms, $institution_id, $refid, $sms_rate, $iuid, $mybalance, $debitWallet) : (($debitWAllet == "Yes" && $sms_rate <= $iwallet_balance) ? $sendSMS->instGeneralAlert($ozeki_password, $ozeki_url, $sysabb, $phone, $sms, $institution_id, $refid, $sms_rate, $iuid, $mybalance, $debitWallet) : "")));
	$sendSMS->tillFundingEmailNotifier($email, $txid, $iusername, $ptype, $cashier, $icurrency, $formated_settled_fund, $formated_balance, $iemailConfigStatus, $ifetch_emailConfig);
	
	echo "<div class='alert alert-success'>Till Account Fund Successfully!</div>";

}
?>
			 <form class="form-horizontal" method="post" enctype="multipart/form-data">

             <div class="box-body">

			<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Office</label>
                  <div class="col-sm-10">
                  <input name="office" type="text" class="form-control" value="<?php echo ($fetch_info->branch == '') ? 'Head Office' : $bname; ?>" readonly>
                  </div>
                  </div>

			<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Teller</label>
                  <div class="col-sm-10">
                  <input name="teller" type="text" class="form-control" value="<?php echo $fetch_info->teller; ?>" readonly>
                  </div>
                  </div>

			<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Cashier Name</label>
                  <div class="col-sm-10">
				<select name="cashier" class="form-control select2" readonly>
										<option value="<?php echo $cashier_id; ?>" selected='selected'><?php echo $cashier2; ?></option>
									</select>
									 </div>
                 					 </div>

            <div class="form-group">
                <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Currency</label>
                <div class="col-sm-10">
					<select name="currency" class="form-control select2" required>
						<option value="" selected="selected">Select Currency</option>
						<option value="<?php echo $icurrency; ?>"><?php echo $icurrency; ?></option>
					</select>
				</div>
            </div>


			<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Amount to Allocate</label>
                  <div class="col-sm-10">
                  <input name="amount_allocated" type="text" class="form-control" placeholder="Enter Amount to Allocate to the Cashier" required>
                  </div>
                  </div>

            <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Note/Comment</label>
                  <div class="col-sm-10">
				  <textarea name="note_comment"  class="form-control" rows="2" cols="80" placeholder="Note / Comment" required></textarea>
                </div>
                </div>		  
				  
			 </div>
			 
			  <div align="right">
              <div class="box-footer">
                				<button name="save" type="submit" class="btn bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>"><i class="fa fa-save">&nbsp;Allocate</i></button>
              </div>
			  </div>
			  
			 </form> 

</div>	
</div>	
</div>
</div>