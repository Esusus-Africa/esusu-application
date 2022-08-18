<div class="box">
	       <div class="box-body">
			<div class="panel panel-success">
            <div class="panel-heading bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">
            <h3 class="panel-title"><a href="withdraw.php?id=<?php echo $_SESSION['tid']; ?>&&mid=NDEw" class="btn bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>"><i class="fa fa-reply-all"></i> Retry</a> <i class="fa fa-money"></i> OTP Confirmation Form</h3>
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
		while($get_details = mysqli_fetch_array($google_details)){
			$id = $get_details['id'];
			$txid = $get_details['txid'];
			$t_type = $get_details['t_type'];
			$p_type = $get_details['p_type'];
			$account = $get_details['acctno'];
			$uname = $get_details['uname'];
			$transfer_to = $get_details['transfer_to'];
			$fn = $get_details['fn'];
			$ln = $get_details['ln'];
			$em = $get_details['email'];
			$ph = $get_details['phone'];
			$amount = $get_details['amount'];
			$auid = $get_details['posted_by'];
			$remark = $get_details['remark'];
			$date_time = $get_details['date_time'];
			$branchid = $get_details['branchid'];
			$sbranchid = $get_details['sbranchid'];
			$currency = $get_details['currency'];
			$total = $get_details['cust_balance'];
			$till_balance = $get_details['till_balance'];

			$detect_smsremark = ($t_type == "Withdraw") ? $remark : "Charges";

			$search_memset = mysqli_query($link, "SELECT * FROM member_settings WHERE companyid = '$agentid'");
			$fetch_memset = mysqli_fetch_array($search_memset);

			$insert = mysqli_query($link, "INSERT INTO transaction VALUES(null,'$txid','$t_type','$p_type','$account','$transfer_to','$fn','$ln','$em','$ph','$amount','$auid','$detect_smsremark',NOW(),'$branchid','$sbranchid','$currency','')") or die (mysqli_error($link));
				
			$update = mysqli_query($link, "UPDATE borrowers SET balance = '$total', last_withdraw_date = '$today' WHERE account = '$account'") or die (mysqli_error($link));
				
			$update1 = mysqli_query($link, "UPDATE till_account SET balance = '$till_balance' WHERE cashier = '$auid'");

			if(!($insert && $update))
			{
				echo "<div class='alert bg-orange'>Transaction not Successful!!</div>";
			}
			else{
				include("alert_sender/awithdraw_alert1.php");
				include("email_sender/send_swithdrawal_alertemail.php");
				$delete_confirmation = mysqli_query($link, "DELETE FROM transaction_verification WHERE id = '$id'");
				$delete_confirmation = mysqli_query($link, "DELETE FROM transaction_verification WHERE acctno = '$account'");
				echo "<div align='center'><img src='../image/checkmark.gif'><p style='color: #38A1F3;'>Transaction Successful!</p><p style='color: #38A1F3;'>Customer Ledger Balance: <b style='color: orange;'>".$currency.number_format($total,2,'.',',')."</b></p></div>";
			}
		}
	}
}
?>

<hr>
<div class="alert bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>">Kindly confirm with the OTP Code send to Customer Registered Phone Number to complete this Transaction Processes.</div>
</hr>
			
			<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">OTP Code</label>
                  <div class="col-sm-10">
                  <input name="otp_code" type="text" class="form-control" placeholder="Enter OPT Code you received on your phone">
                  </div>
                  </div>

			 </div>
			 
			  <div align="right">
              <div class="box-footer">
                				<button name="save" type="submit" class="btn bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>"><i class="fa fa-refresh">&nbsp;Confirm</i></button>
              </div>
			  </div>
			
			 </form> 


</div>	
</div>	
</div>
</div>