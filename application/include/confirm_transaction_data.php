<div class="box">
	       <div class="box-body">
			<div class="panel panel-success">
            <div class="panel-heading bg-blue">
            <h3 class="panel-title"><a href="withdraw.php?id=<?php echo $_SESSION['tid']; ?>&&mid=NDEw" class="btn bg-orange"><i class="fa fa-reply-all"></i> Retry</a> <i class="fa fa-money"></i> OTP Confirmation Form</h3>
            </div>
             <div class="box-body">
            
			 <form class="form-horizontal" method="post" enctype="multipart/form-data">
             <div class="box-body">
<?php
if(isset($_POST['save']))
{

	$otp_code =  mysqli_real_escape_string($link, $_POST['otp_code']);
	$today = date("Y-m-d");
		
	$google_details = mysqli_query($link, "SELECT * FROM transaction_verification WHERE otp_code = '$otp_code'");
	if(mysqli_num_rows($google_details) == 0)
	{
		echo "<div class='alert bg-red'>Invalid OPT!!</div>";
	}
	else{
	    $google_details1 = mysqli_query($link, "SELECT * FROM transaction_verification WHERE otp_code = '$otp_code' AND t_type = 'Withdraw'");
		$get_details1 = mysqli_fetch_array($google_details1);
		$id = $get_details1['id'];
		$txid = $get_details1['txid'];
		$t_type = $get_details1['t_type'];
		$p_type = $get_details1['p_type'];
		$account = $get_details1['acctno'];
		$uname = $get_details1['uname'];
		$transfer_to = $get_details1['transfer_to'];
		$fn = $get_details1['fn'];
		$ln = $get_details1['ln'];
		$em = $get_details1['email'];
		$ph = $get_details1['phone'];
		$amount = $get_details1['amount'];
		$auid = $get_details1['posted_by'];
		$remark = $get_details1['remark'];
		$date_time = $get_details1['date_time'];
		$branchid = $get_details1['branchid'];
		$sbranchid = $get_details1['sbranchid'];
		$currency = $get_details1['currency'];
		$total = $get_details1['cust_balance'];
		$till_balance = $get_details1['till_balance'];
		
		$google_details11 = mysqli_query($link, "SELECT * FROM transaction_verification WHERE otp_code = '$otp_code' AND t_type = 'Withdraw-Charges'");
		$get_details11 = mysqli_fetch_array($google_details11);
		$id1 = $get_details11['id'];
		$txid1 = $get_details11['txid'];
		$t_type1 = $get_details11['t_type'];
		$p_type1 = $get_details11['p_type'];
		$account1 = $get_details11['acctno'];
		$uname1 = $get_details11['uname'];
		$transfer_to1 = $get_details11['transfer_to'];
		$fn1 = $get_details11['fn'];
		$ln1 = $get_details11['ln'];
		$em1 = $get_details11['email'];
		$ph1 = $get_details11['phone'];
		$amount1 = $get_details11['amount'];
		$auid1 = $get_details11['posted_by'];
		$remark1 = $get_details11['remark'];
		$date_time1 = $get_details11['date_time'];
		$branchid1 = $get_details11['branchid'];
		$sbranchid1 = $get_details11['sbranchid'];
		$currency1 = $get_details11['currency'];
		$total1 = $get_details11['cust_balance'];
		$till_balance1 = $get_details11['till_balance'];

		$search_memset = mysqli_query($link, "SELECT * FROM member_settings WHERE companyid = '$agentid'");
		$fetch_memset = mysqli_fetch_array($search_memset);
		
		$insert = mysqli_query($link, "INSERT INTO transaction VALUES(null,'$txid1','$t_type1','$p_type1','$account1','$transfer_to1','$fn1','$ln1','$em1','$ph1','$amount1','$auid1','$remark1',NOW(),'$branchid1','$sbranchid1','$currency1')") or die ("Error:" . mysqli_error($link));

		$insert = mysqli_query($link, "INSERT INTO transaction VALUES(null,'$txid','$t_type','$p_type','$account','$transfer_to','$fn','$ln','$em','$ph','$amount','$auid','$remark',NOW(),'$branchid','$sbranchid','$currency')") or die ("Error:" . mysqli_error($link));
				
		$update = mysqli_query($link, "UPDATE borrowers SET balance = '$total', last_withdraw_date = '$today' WHERE account = '$account'") or die ("Error:" . mysqli_error($link));
				
		$update1 = mysqli_query($link, "UPDATE till_account SET balance = '$till_balance' WHERE cashier = '$auid'");

		include("alert_sender/withdraw_alert1.php");
	    include("email_sender/send_swithdrawal_alertemail.php");
		$delete_confirmation = mysqli_query($link, "DELETE FROM transaction_verification WHERE id = '$id'");
	    $delete_confirmation = mysqli_query($link, "DELETE FROM transaction_verification WHERE acctno = '$account'");
		echo "<div align='center'><img src='../image/checkmark.gif'><p style='color: #38A1F3;'>Transaction Successful!</p><p style='color: #38A1F3;'>Customer Ledger Balance: <b style='color: orange;'>".$acurrency.number_format($total,2,'.',',')."</b></p></div>";
	}
}
?>

<hr>
<div class="alert bg-orange">Kindly confirm with the OTP Code send to Customer Registered Phone Number to complete this Transaction Processes.</div>
</hr>
			
			<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">OTP Code</label>
                  <div class="col-sm-10">
                  <input name="otp_code" type="text" class="form-control" placeholder="Enter OPT Code you received on your phone">
                  </div>
                  </div>

			 </div>
			 
			  <div align="right">
              <div class="box-footer">
                				<button name="save" type="submit" class="btn bg-blue"><i class="fa fa-refresh">&nbsp;Confirm</i></button>
              </div>
			  </div>
			
			 </form> 


</div>	
</div>	
</div>
</div>