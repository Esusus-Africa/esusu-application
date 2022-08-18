<div class="box">
	       <div class="box-body">
			<div class="panel panel-success">
            <div class="panel-heading bg-blue">
            <h3 class="panel-title"><a href="transfer_fund.php?id=<?php echo $_SESSION['tid']; ?>&&mid=NDA0&&tab=tab_1" class="btn bg-orange"><i class="fa fa-reply-all"></i> Retry</a> <i class="fa fa-money"></i> OTP / PIN Confirmation Form</h3>
            </div>
             <div class="box-body">
            
			 <form class="form-horizontal" method="post" enctype="multipart/form-data">
             <div class="box-body">
<?php
if(isset($_POST['save']))
{
    include("../config/restful_apicalls.php");
	
	$result = array();
	$otp_code =  mysqli_real_escape_string($link, $_POST['otp_code']);
	$pin_code =  mysqli_real_escape_string($link, $_POST['pin_code']);
	$today = date("Y-m-d");
	$refid = $_GET['refid'];
		
	$google_details = mysqli_query($link, "SELECT * FROM africa_pmt_confirmation WHERE refid = '$refid' AND (otp_code = '$otp_code' OR otp_code = 'pin')");
	if(mysqli_num_rows($google_details) == 1 || $pin_code == $control_pin)
	{
	    $get_details1 = mysqli_fetch_array($google_details);
		$id = $get_details1['id'];
		$refid = $get_details1['refid'];
		$account_number = $get_details1['acctno'];
		$bank_code = $get_details1['bankcode'];
		$b_name = $get_details1['bname'];
		$currency = $get_details1['currency'];
		$debit_currency = $get_details1['debit_currency'];
		$amount = $get_details1['amount'];
		$narration = $get_details1['reasons'];
		$date_time = $get_details1['date_time'];
		$phone = $get_details1['phone'];

		$systemset = mysqli_query($link, "SELECT * FROM systemset");
    	$row1 = mysqli_fetch_object($systemset);
    	$seckey = $row1->secret_key;
    	$public_key = $row1->public_key;
    	
	        $search_restapi = mysqli_query($link, "SELECT * FROM restful_apisetup WHERE api_name = 'transfer'");
        	$fetch_restapi = mysqli_fetch_object($search_restapi);
        	$api_url = $fetch_restapi->api_url;
        	
        	// Pass the parameter here
        	$postdata =  array(
        		"account_bank"		=>	$bank_code,
        		"account_number"	=>	$account_number,
        		"amount"			=>	$amount,
        		"seckey"			=>	$seckey,
        		"narration"			=>	$narration,
        		"currency"			=>	$currency,
        		"reference"			=>	$refid,
        		"beneficiary_name"	=>	$b_name,
        		"debit_currency"	=>	$debit_currency
        	);
        	  
        	$make_call = callAPI('POST', $api_url, json_encode($postdata));
        	$result = json_decode($make_call, true);
        	
        	if($result['status'] == "success"){
        	    
        	    $transfer_id = $result['data']['id'];
        		$transfers_fee = "Gateway Fee: ".$debit_currency.$result['data']['fee'];
        		$status = $result['data']['status'];
        		$fullname = $result['data']['fullname'];
        		$bank_name = $result['data']['bank_name'];
        
        		$insert = mysqli_query($link, "INSERT INTO transfer_history VALUES(null,'','$transfer_id','$refid','$account_number','$fullname','$bank_code','$bank_name','$currency','$amount','$transfers_fee','$status','$narration',NOW())");
				
        		mysqli_query($link, "DELETE FROM africa_pmt_confirmation WHERE id = '$id'");
	            mysqli_query($link, "DELETE FROM africa_pmt_confirmation WHERE phone = '$phone' AND refid = '$refid'");
	            echo '<meta http-equiv="refresh" content="6;url=transfer_fund.php?id='.$_SESSION['tid'].'&&mid=NDA0&&tab=tab_1">';
		        echo '<br>';
		        echo "<div align='center'><img src='../image/checkmark.gif'><p style='color: #38A1F3;'>Transfer Initiated Successfully...<br>Kindly allow 2 - 3 minutes for the transaction to be confirmed!!</p></div>";
        	
			}
        	else{
        	    echo '<meta http-equiv="refresh" content="5;url=confirm_afri_trasfer.php?id='.$_SESSION['tid'].'&&mid=NDA0">';
		        echo '<br>';
        		echo'<span class="itext" style="color: blue;">'.$result['message'].'</span>';
        	}
	}elseif(mysqli_num_rows($google_details) == 1 && $pin_code == $control_pin){
	    echo "<div class='alert bg-red'>Oops!...You are required to enter just one Parameter, Please read instruction right behind those text field.</div>";
	}else{
	    echo "<div class='alert bg-red'>Invalid Transaction Pin / OTP Code!!</div>";
	}
}
?>

<?php
$search_memset = mysqli_query($link, "SELECT * FROM systemset");
$fetch_memset = mysqli_fetch_array($search_memset);
$tlimit = $fetch_memset['tlimit'];
?>
<hr>
<div class="alert bg-orange">Kindly confirm with the OTP Code send to Company Registered Phone Number to complete this Transfer if and only if the amount to transfer is above <b><?php echo $fetch_memset['currency'].number_format($tlimit,2,'.',','); ?></b></div>
</hr>
			
			<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">OTP Code</label>
                  <div class="col-sm-10">
                  <input name="otp_code" type="text" class="form-control" placeholder="Enter OPT Code you received on registered company phone number">
                  <span style="color: orange;">Otp Code is Required for Transaction Above <b><?php echo $fetch_memset['currency'].number_format($tlimit,2,'.',','); ?></b></span>
                  </div>
                  </div>
                  
            <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Transaction Pin</label>
                  <div class="col-sm-10">
                  <input name="pin_code" type="text" class="form-control" placeholder="Enter your 4 Digit Transaction Pin">
                  <span style="color: orange;">Only Pin is Required for Transaction Below <b><?php echo $fetch_memset['currency'].number_format($tlimit,2,'.',','); ?></b></span>
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