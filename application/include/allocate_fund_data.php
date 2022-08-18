<div class="box">
	       <div class="box-body">
			<div class="panel panel-success">
            <div class="panel-heading bg-blue">
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
$cashier = $fetch_cashier['name'];
?>			

             <div class="box-body">
 <?php
if(isset($_POST['save']))
{
	$issuerid = mysqli_real_escape_string($link, $_GET['id']);
	$teller =  mysqli_real_escape_string($link, $_POST['teller']);
	$cashier = mysqli_real_escape_string($link, $_POST['cashier']);
	$currency =  mysqli_real_escape_string($link, $_POST['currency']);
	$amount_allocated =  mysqli_real_escape_string($link, $_POST['amount_allocated']);
	$note_comment =  mysqli_real_escape_string($link, $_POST['note_comment']);
	
	$total_amount = $balance + $amount_allocated;

	$update_till = mysqli_query($link, "UPDATE till_account SET balance = '$total_amount' WHERE id = '$id'");
	$insert = mysqli_query($link, "INSERT INTO fund_allocation_history VALUES(null,'','$issuerid','$mbranchid','$teller','$cashier','$amount_allocated','$currency','$note_comment',NOW())") or die ("Error: " . mysqli_error($link));
	if(!$insert)
	{
		echo "<div class='alert alert-info'>Unable to Fund Till Account.....Please try again later</div>";
	}
	else{
		echo "<div class='alert alert-success'>Till Account Fund Successfully!</div>";
	}
}
?>           
			 <form class="form-horizontal" method="post" enctype="multipart/form-data">
			  <?php echo '<div class="alert bg-orange fade in" >
  				<strong>Fill up the form below to Fund Cashier Till Account</strong>
				</div>'?>
             <div class="box-body">
			 


			<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Office</label>
                  <div class="col-sm-10">
                  <input name="office" type="text" class="form-control" value="<?php echo ($fetch_info->branch == '') ? 'Head Office' : $bname; ?>" readonly>
                  </div>
                  </div>

			<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Teller</label>
                  <div class="col-sm-10">
                  <input name="teller" type="text" class="form-control" value="<?php echo $fetch_info->teller; ?>" readonly>
                  </div>
                  </div>

			<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Cashier Name</label>
                  <div class="col-sm-10">
				<select name="cashier" class="form-control select2" readonly>
										<option value="<?php echo $fetch_info->cashier; ?>" selected='selected'><?php echo $cashier; ?></option>
									</select>
									 </div>
                 					 </div>

            <div class="form-group">
                <label for="" class="col-sm-2 control-label" style="color:blue;">Currency</label>
                <div class="col-sm-10">
					<select name="currency" class="form-control select2" required>
						<option selected="selected">Select Currency</option>
						<option value="NGN">NGN</option>
						<option value="USD">USD</option>
						<option value="EUR">EUR</option>
						<option value="GBP">GBP</option>
						<option value="UGX">UGX</option>
						<option value="TZS">TZS</option>
						<option value="GHS">GHS</option>
						<option value="KES">KES</option>
						<option value="ZAR">ZAR</option>
					</select>
				</div>
            </div>


			<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Amount to Allocate</label>
                  <div class="col-sm-10">
                  <input name="amount_allocated" type="text" class="form-control" placeholder="Enter Amount to Allocate to the Cashier" required>
                  </div>
                  </div>

            <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Note/Comment</label>
                  <div class="col-sm-10">
				  <textarea name="note_comment"  class="form-control" rows="2" cols="80" placeholder="Note / Comment" required></textarea>
                </div>
                </div>		  
				  
			 </div>
			 
			  <div align="right">
              <div class="box-footer">
                				<button name="save" type="submit" class="btn bg-blue"><i class="fa fa-save">&nbsp;Allocate</i></button>
              </div>
			  </div>
			  
			 </form> 

</div>	
</div>	
</div>
</div>