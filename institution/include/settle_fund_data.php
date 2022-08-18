<div class="box">
	       <div class="box-body">
			<div class="panel panel-success">
            <div class="panel-heading bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">
            <h3 class="panel-title"> <i class="fa fa-desktop"></i> Settle Fund</h3>
            </div>
<?php
$id = mysqli_real_escape_string($link, $_GET['idm']);
$search_info = mysqli_query($link, "SELECT * FROM till_account WHERE id = '$id'");
$fetch_info = mysqli_fetch_object($search_info);
$mbranchid = $fetch_info->branch;
$cashier_id = $fetch_info->cashier;
$balance = $fetch_info->balance;
$unsettledBal = $fetch_info->unsettled_balance;

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

	$reference = uniqid().time();
	$teller = mysqli_real_escape_string($link, $_POST['teller']);
	$mycashier = mysqli_real_escape_string($link, $_POST['cashier']);
	$currency = mysqli_real_escape_string($link, $_POST['currency']);
	$amount_tosettle = preg_replace('/[^0-9.]/', '', mysqli_real_escape_string($link, $_POST['amount_tosettle']));
      $amount_unsettled = preg_replace('/[^0-9.]/', '', mysqli_real_escape_string($link, $_POST['amount_unsettled']));
	$note_comment = mysqli_real_escape_string($link, $_POST['note_comment']);

      if($amount_tosettle > $amount_unsettled){

            echo "<div class='alert alert-danger'>No fund is available!</div>";

      }else{

            $total_amount = $unsettledBal - $amount_tosettle;

            mysqli_query($link, "UPDATE till_account SET unsettled_balance = '$total_amount' WHERE cashier = '$mycashier'");
            //$insert = mysqli_query($link, "INSERT INTO fund_settlement VALUES(null,'$reference','$institution_id','$mbranchid','$teller','$mycashier','$currency','$amount_tosettle','$balance','$note_comment',NOW())") or die ("Error: " . mysqli_error($link));
            mysqli_query($link, "INSERT INTO fund_allocation_history VALUES(null,'$reference','$institution_id','$iuid','$mbranchid','$teller','$mycashier','$amount_tosettle','Debit','TILL_SETTLEMENT','$currency','$balance','$note_comment','successful','$mycurrentTime')") or die ("Error: " . mysqli_error($link));

            echo "<div class='alert alert-success'>Account Settled Successfully!</div>";

      }

}
?>           
		<form class="form-horizontal" method="post" enctype="multipart/form-data">
			<?php echo '<div class="alert bg-orange fade in" >
  				<strong>Fill up the form below to Settle Cashier Till Account</strong>
				</div>'?>

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
                  <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Till Balance</label>
                  <div class="col-sm-9">
                  <input name="amount_intill" type="text" class="form-control" value="<?php echo number_format($fetch_info->balance,2,'.',','); ?>" readonly>
                  </div>
                  </div>

            <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Unsettled Balance</label>
                  <div class="col-sm-9">
                  <input name="amount_unsettled" type="text" class="form-control" value="<?php echo number_format($fetch_info->unsettled_balance,2,'.',','); ?>" readonly>
                  </div>
                  </div>

            <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Amount to Settle</label>
                  <div class="col-sm-9">
                  <input name="amount_tosettle" type="text" class="form-control" placeholder="Enter Amount Received by the Collector" required>
                  </div>
                  </div>

            <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Remarks</label>
                  <div class="col-sm-9">
				  <textarea name="note_comment"  class="form-control" rows="2" cols="80" placeholder="Remarks" required></textarea>
                </div>
                </div>
				
		</div>
			 
		<div align="right">
              <div class="box-footer">
                	<button name="save" type="submit" class="btn bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>"><i class="fa fa-save">&nbsp;Settle</i></button>
              </div>
		</div>
		
		</form> 

</div>	
</div>	
</div>
</div>