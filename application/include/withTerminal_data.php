<div class="box">
	       <div class="box-body">
			<div class="panel panel-success">
            <div class="panel-heading bg-blue">
            <h3 class="panel-title"><i class="fa fa-link"></i> Withdraw Terminal / Reject Terminal Request</h3>
            </div>

             <div class="box-body">
 <?php
if(isset($_POST['withTerminal']))
{
    $terminal_id = $_GET['termId'];
    $status = mysqli_real_escape_string($link, $_POST['status']);
    $search_term = mysqli_query($link, "SELECT * FROM terminal_reg WHERE terminal_id = '$terminal_id'");
    $fetch_term = mysqli_fetch_array($search_term);
    $merchant_id = $fetch_term['merchant_id'];
    $merchant_name = $fetch_term['merchant_name'];
    $merchant_email = $fetch_term['merchant_email'];
    $merchant_phone_no = $fetch_term['merchant_phone_no'];
    $comment = mysqli_real_escape_string($link, $_POST['comment']);
    $last_settled_bal = $fetch_term['settled_balance'];
    $total_transaction_count = $fetch_term['total_transaction_count'];
    $dateIssued = $fetch_term['dateUpdated'];
    $assignedBy = $fetch_term['assignedBy'];
    $bankcode = $fetch_term['bankcode'];
    $bank_account_no = $fetch_term['bank_account_no'];
    $bankName = $fetch_term['bankname'];
    $b_name = $fetch_term['acctName'];
    $dateWithdrawn = date("Y-m-d h:i:s");
    $tpin = mysqli_real_escape_string($link, $_POST['tpin']);

	if($control_pin != $tpin){
	    
	    echo "<div class='alert bg-orange'>Opps!...Invalid transaction pin</div>";
	    
	}
	else{
        
        $update = mysqli_query($link, "INSERT INTO terminal_withdrawal_log VALUES(null,'$terminal_id','$merchant_id','$merchant_name','$merchant_email','$merchant_phone_no','$comment','$last_settled_bal','$total_transaction_count','$dateIssued','$assignedBy','$uid','$bankcode','$bank_account_no','$bankName','$b_name','$status','$dateWithdrawn')");
        $update = mysqli_query($link, "UPDATE terminal_reg SET merchant_id = '', merchant_name = '', merchant_email = '', merchant_phone_no = '', sms_alert = '', bankcode = '', bank_account_no = '', bankname = '', acctName = '', slip_header = '', slip_footer = '', terminal_status = 'Available', dateUpdated = '$dateWithdrawn', assignedBy = '' WHERE terminal_id = '$terminal_id'") or die ("Error: " . mysqli_error($link));
        
        if(!$update){

            echo "<div class='alert bg-blue'>Opps!....Unable to execute request!!</div>";

        }
        else{

            echo "<div class='alert bg-blue'>Request proccessed successfully!!</div>";

        }

    }

}
?>           
			 <form class="form-horizontal" method="post" enctype="multipart/form-data">

             <div class="box-body">

            <div class="form-group">
                <label for="" class="col-sm-3 control-label" style="color:blue;">Comment:</label>
                <div class="col-sm-6">
                    <textarea name="comment" class="form-control" rows="2" cols="80" required>
                </div>
                <label for="" class="col-sm-3 control-label"></label>
            </div>

            <div class="form-group">
                <label for="" class="col-sm-3 control-label" style="color:blue;">Status:</label>
                <div class="col-sm-6">
                    <select name="status" class="form-control select2" required>
                      <option value="" selected>Select Status</option>
                      <option value="Withdrawn">Withdraw Terminal</option>
                      <option value="Rejected">Reject Request</option>
                    </select>
                </div>
                <label for="" class="col-sm-3 control-label"></label>
            </div>

			
			<div class="form-group">
                <label for="" class="col-sm-3 control-label" style="color:blue;">Transaction Pin:</label>
                <div class="col-sm-6">
                  <input name="tpin" type="password" class="form-control" placeholder="Enter your transaction pin" maxlength="4" required>
                </div>
                <label for="" class="col-sm-3 control-label"></label>
            </div>
				  
			 </div>
			 
			<div class="form-group" align="right">
                <label for="" class="col-sm-3 control-label" style="color:blue;"></label>
                <div class="col-sm-6">
                	<button name="withTerminal" type="submit" class="btn bg-blue"><i class="fa fa-link">&nbsp;Submit</i></button>
                </div>
                <label for="" class="col-sm-3 control-label"></label>
            </div>
			  
			 </form> 

</div>	
</div>	
</div>
</div>