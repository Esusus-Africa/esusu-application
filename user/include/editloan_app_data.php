<div class="row">	
		
	 <section class="content">
	     
	        <div class="box box-success">
            <div class="box-body">
              <div class="table-responsive"> 
             <div class="box-body">

			 <div class="col-md-14">
             <div class="nav-tabs-custom">
             <ul class="nav nav-tabs">
			  <li <?php echo ($_GET['tab'] == 'tab_0') ? "class='active'" : ''; ?>><a href="updateloans.php?tid=<?php echo $_SESSION['tid']; ?>&&acn=<?php echo $_GET['acn']; ?>&&mid=NDA0&&lid=<?php echo $_GET['lid']; ?>&&tab=tab_0">Verify Your Card Details</a></li>
              </ul>
             <div class="tab-content">
<?php
if(isset($_GET['tab']) == true)
{
	$tab = $_GET['tab'];
	if($tab == 'tab_0')
	{
		$acn = $_GET['acn'];
		$search_customer = mysqli_query($link, "SELECT * FROM borrowers WHERE account = '$acn'");
		$get_customer = mysqli_fetch_object($search_customer);
		
		$select1 = mysqli_query($link, "SELECT * FROM systemset") or die (mysqli_error($link));
		$row1 = mysqli_fetch_object($select1);
	?>

	              <div class="tab-pane <?php echo ($_GET['tab'] == 'tab_0') ? 'active' : ''; ?>" id="tab_0">
			<?php
			if(isset($_GET['refid']) == true)
			{	
			    include ("../config/restful_apicalls.php");

				$systemset = mysqli_query($link, "SELECT * FROM systemset");
				$row1 = mysqli_fetch_object($systemset);
  
				$result = array();
				$refid = $_GET['refid'];
  				$acn = $_GET['acn'];
				//The parameter after verify/ is the transaction reference to be verified
				$search_restapi = mysqli_query($link, "SELECT * FROM restful_apisetup WHERE api_name = 'verify_transaction'");
				$fetch_restapi = mysqli_fetch_object($search_restapi);
				$api_url = $fetch_restapi->api_url;

				$data_array = array(
					"txref"   =>  $refid,
				    "SECKEY"  =>  $row1->secret_key
				);

				$make_call = callAPI('POST', $api_url, json_encode($data_array));
				$result = json_decode($make_call, true);
				    //print_r($result);
				if($result['status'] == 'success'){
				    // the transaction was successful, you can deliver value
				    $paymenttype        =   $result['data']['paymenttype'];
				    
				    foreach($result['data']['card']['card_tokens'] as $cardkey)
				    $cardtoken = $cardkey['embedtoken'];
				    
				    foreach($result['data']['account']['account_token'] as $acctkey)
				    $accttoken = $acctkey['token'];
				    
					$autgorization_code = 	($paymenttype == "card") ? $cardtoken : $accttoken;
					
					$refid = $_GET['refid'];
		  			$lid = $_GET['lid'];
		  			$acn = $_GET['acn'];
					$email = $get_customer->email;
						  
					$search_card = mysqli_query($link, "SELECT * FROM authorized_card WHERE lid = '$lid'");
					if(mysqli_num_rows($search_card) == 1){
							  //no action should be performed
					}else{
						$insert_authorization_record = mysqli_query($link, "INSERT INTO authorized_card VALUES(null,'$refid','$lid','$acn','$email','$autgorization_code',NOW())") or die ("Error" . mysqli_error($link));
					    $update_authorization_record = mysqli_query($link, "UPDATE loan_info SET status = 'Pending' WHERE lid = '$lid'") or die ("Error" . mysqli_error($link));
					    
					    echo "<script>alert('Card / Account Verified Successfully'); </script>";
					}
				}else{
				    // the transaction was not successful, do not deliver value'
				    // print_r($result);  //uncomment this line to inspect the result, to check why it failed.
				    echo "Transaction was not successful: Last gateway response was: ".$result['message'];
				}
			?>
			
			<br>
			<p style="color: <?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;"><i class="fa fa-check"></i><b> Card Details Verified Successfully!</b> Click <a href="listloans.php?tid=<?php echo $_SESSION['tid']; ?>&&acn=<?php echo $_SESSION['acctno']; ?>&&mid=NDA0" class="btn btn-success"><i class="fa fa-money"></i> HERE </a> to Go back to Loan Page</p>
			
			<hr>
			
			<div align="right">
              <div class="box-footer">
               <button type="submit" class="btn bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>" name="add_pay_schedule"><i class="fa fa-save">&nbsp;Submit Application</i></button>

              </div>
			  </div>
<?php
if(isset($_POST['add_pay_schedule']))
{
$idm = $_GET['id'];
$lid = $_GET['lid'];
$refid = $_GET['refid'];
$tid = $_SESSION['acctno'];
$insert = mysqli_query($link, "UPDATE loan_info SET upstatus = 'Completed' WHERE id = '$idm'") or die (mysqli_error($link));
	
if(!($insert))
{
	echo "<script>alert('Record not inserted.....Please try again later!'); </script>";
}
else{
	echo "<script>alert('Loan Application Submit Successfully!!'); </script>";
	echo "<script>window.location='listloans.php?id=".$idm."&&tid=".$_SESSION['tid']."&&acn=".$tid."&&mid=".base64_encode("404")."&&refid=".$_GET['refid']."&&lid=".$lid."'; </script>";
}
}
?>

		<?php
		}
		else{
			?>
				  <form >
				    <a href="verify_card2.php?tid=<?php echo $_SESSION['tid']; ?>&&acn=<?php echo $acnt_id; ?>&&mid=NDA0&&lid=<?php echo $_GET['lid']; ?>"><button type="button" class="btn bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>"><i class="fa fa-refresh"></i> Proceed to Verify Your Card Now! </button></a>
				  </form>
<?php
$select1 = mysqli_query($link, "SELECT * FROM systemset") or die (mysqli_error($link));
$row1 = mysqli_fetch_object($select1);
?>
				  <br>
				  <p style="color: <?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>;"><b><i>Note that you must verify your Bank Account Card details to proceed for us to Activate Automated Loan Repayment.</i></b></p>
				  <p style="color: <?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>;"><b>NOTE:</b><i> Be aware that you will be charge the total amount of <b><?php echo $row1->currency.number_format($row1->auth_charges,2,'.',','); ?></b> to confirm the validity of the card.</i></p>
				  
		<?php
	    }
		?>
		
              </div>
              
	<?php
	}
	elseif($tab == 'tab_5')
	{
	?>
			  
			  <div class="tab-pane <?php echo ($_GET['tab'] == 'tab_5') ? 'active' : ''; ?>" id="tab_5">
			<form class="form-horizontal" method="post" enctype="multipart/form-data">
			<div class="box-body">
<div align="center"><h4>Payment Schedule</h4></div>
<?php
$lid = $_GET['lid'];
$searchin = mysqli_query($link, "SELECT * FROM payment_schedule WHERE lid = '$lid'") or die (mysqli_error($link));
$haveit = mysqli_fetch_array($searchin);
$idmet= $haveit['id'];
?>
								<div class="form-group">
				                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">Estimated Time:</label>
				                  <div class="col-sm-10">
								  <select name="d1" class="form-control" readonly>
								 <option value="<?php echo $haveit['day']; ?>"><?php echo ($haveit['schedule'] == "Monthly") ? $haveit['day'].' Month(s)' : $haveit['day'].' Day(s)'; ?></option>
								  </select>
				                  </div>
				                  </div>
								  
								  <input name="schedule" type="hidden" value="<?php echo $haveit['schedule']; ?>" class="form-control">
				  
				   <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">Schedules:</label>
                  <div class="col-sm-10">
				<table>
                <tbody> 
<?php
$id = $_GET['id'];
$searchin = mysqli_query($link, "SELECT * FROM pay_schedule WHERE get_id = '$id'") or die (mysqli_error($link));
while($haveit = mysqli_fetch_array($searchin))
{
$idmet= $haveit['id'];
?>			
				<tr>
			<td width="30"><input id="optionsCheckbox" class="uniform_on" name="selector[]" type="checkbox" value="<?php echo $idmet; ?>" checked></td>
       <td width="400"><input name="schedulek[]" type="date" class="form-control pull-right" placeholder="Schedule" value="<?php echo $haveit['schedule']; ?>"></td>
           <td width="300"><input name="balance[]" type="text" class="form-control" placeholder="Balance" value="<?php echo $haveit['balance']; ?>" readonly></td>
			<td width="100"><input name="payment[]" type="text" class="form-control" placeholder="Payment" value="<?php echo $haveit['payment']; ?>" readonly></td>
			</tr>
<?php } ?>
				</tbody>
                </table>
<hr>
<?php
$id = $_GET['id'];
$searchin2 = mysqli_query($link, "SELECT * FROM pay_schedule WHERE get_id = '$id' AND balance = '0'") or die (mysqli_error($link));
$numit = mysqli_num_rows($searchin2);
?>
<?php echo ($numit == 1) ? "<span class='label label-success'>Completed</span>" : "<span class='label label-danger'>Progressing...(Schedule not completed yet).</span>"; ?>
<hr>
<div align="left">
              <div class="box-footer">

                				<button type="submit" class="btn btn-success btn-flat" name="add_sch_rows"><i class="fa fa-plus">&nbsp;Add Row</i></button>
                				<button name="delrow2" type="submit" class="btn btn-danger btn-flat"><i class="fa fa-trash">&nbsp;Delete Row</i></button> 
              </div>
			  </div>
   <?php
						if(isset($_POST['delrow2'])){
						$idm = $_GET['tid'];
							$lid = $_GET['lid'];
							$id=$_POST['selector'];
							$get_id = $_GET['id'];
							$N = count($id);
						if($id == ''){
						echo "<script>alert('Row Not Selected!!!'); </script>";	
						echo "<script>window.location='newloans.php?tid=".$idm."&&id=".$get_id."&&acn=".$_SESSION['acctno']."&&refid=".$_GET['refid']."&&mid=".base64_encode("404")."&&lid=".$lid."&&tab=tab_5'; </script>";
							}
							else{
							for($i=0; $i < $N; $i++)
							{
								$result = mysqli_query($link,"DELETE FROM pay_schedule WHERE id ='$id[$i]'");
								echo "<script>window.location='newloans.php?tid=".$idm."&&id=".$get_id."&&acn=".$_SESSION['acctno']."&&refid=".$_GET['refid']."&&mid=".base64_encode("404")."&&lid=".$lid."&&tab=tab_5'; </script>";
							}
							}
							}
?>

<?php
if(isset($_POST['add_sch_rows']))
{
$id = $_GET['id'];
$idm = $_SESSION['tid'];
$lid = $_GET['lid'];
$tid = $_SESSION['acctno'];
$get_id = $_GET['id'];

$day = mysqli_real_escape_string($link, $_POST['d1']);
$schedule_of_paymt = mysqli_real_escape_string($link, $_POST['schedule']);
	
	$process_data2 = mysqli_query($link, "SELECT * FROM loan_info WHERE lid = '$lid'") or die (mysqli_error($link));
	$fetch = mysqli_fetch_array($process_data2);
	$interest_rate = $fetch['interest_rate'];
	$baccount = $fetch['baccount'];
	$amount_borrowed = $fetch['amount'];
	
	$final_update_loaninfo = mysqli_query($link, "UPDATE loan_info SET amount_topay = '$amt_to_pay', balance = '$amt_to_pay' WHERE lid = '$lid'") or die (mysqli_error($link));
	
	if($schedule_of_paymt == "Monthly")
	{
		$calc_totalinterest = ($interest_rate/100)*$amount_borrowed;
		$amt_to_pay = ($calc_totalinterest * $day) + $amount_borrowed;
		$int_rate = number_format(($amt_to_pay / $day),0,'.','');
		$verify_data = mysqli_query($link, "SELECT * FROM pay_schedule WHERE lid = '$lid'") or die (mysqli_error($link));
		if(mysqli_num_rows($verify_data) == 0)
		{
			//$amt1 = $amt_to_pay - $int_rate;
			$insert = mysqli_query($link, "INSERT INTO interest_calculator VALUES(null,'$lid','$amt_to_pay','$int_rate')") or die (mysqli_error($link));
			
			//CONFIRMATION OF INTEREST RATE
			$select_int_calculator = mysqli_query($link, "SELECT * FROM interest_calculator WHERE lid = '$lid'") or die (mysqli_error($link));
			$get_calculator = mysqli_fetch_array($select_int_calculator);
			$balance = $get_calculator['amt_to_pay'];
			$lrate = $get_calculator['int_rate'];
			$new_balance = number_format(($balance - $lrate),0,'.','');
			
			if($balance <= $lrate)
			{
				$insert = mysqli_query($link, "INSERT INTO pay_schedule VALUES(null,'$lid','$id','$baccount','','0','$lrate','UNPAID','$bbranchid')") or die (mysqli_error($link));
				$update_calculator = mysqli_query($link, "UPDATE interest_calculator SET amt_to_pay = '0' WHERE lid = '$lid'") or die (mysqli_error($link));
				
				echo "<script>alert('Row Added Successfully'); </script>";
				echo "<script>window.location='newloans.php?tid=".$idm."&&id=".$get_id."&&acn=".$_SESSION['acctno']."&&refid=".$_GET['refid']."&&mid=".base64_encode("404")."&&lid=".$lid."&&tab=tab_5'; </script>";				
			}
			else{
			$insert = mysqli_query($link, "INSERT INTO pay_schedule VALUES(null,'$lid','$id','$baccount','','$new_balance','$int_rate','UNPAID','$bbranchid')") or die (mysqli_error($link));
			$update_calculator = mysqli_query($link, "UPDATE interest_calculator SET amt_to_pay = '$new_balance' WHERE lid = '$lid'") or die (mysqli_error($link));
			
			    echo "<script>alert('Row Added Successfully'); </script>";
				echo "<script>window.location='newloans.php?tid=".$idm."&&id=".$get_id."&&acn=".$_SESSION['acctno']."&&refid=".$_GET['refid']."&&mid=".base64_encode("404")."&&lid=".$lid."&&tab=tab_5'; </script>";
		}
		}else{
			$select_int_calculator = mysqli_query($link, "SELECT * FROM interest_calculator WHERE lid = '$lid'") or die (mysqli_error($link));
			$get_calculator = mysqli_fetch_array($select_int_calculator);
			$balance = $get_calculator['amt_to_pay'];
			$lrate = $get_calculator['int_rate'];
			$new_balance = number_format(($balance - $lrate),0,'.','');
			
			if($balance <= $lrate)
			{
				$insert = mysqli_query($link, "INSERT INTO pay_schedule VALUES(null,'$lid','$id','$baccount','','0','$balance','UNPAID','$bbranchid')") or die (mysqli_error($link));
				$update_calculator = mysqli_query($link, "UPDATE interest_calculator SET amt_to_pay = '0' WHERE lid = '$lid'") or die (mysqli_error($link));
				
				echo "<script>alert('Row Added Successfully'); </script>";
				echo "<script>window.location='newloans.php?tid=".$idm."&&id=".$get_id."&&acn=".$_SESSION['acctno']."&&refid=".$_GET['refid']."&&mid=".base64_encode("404")."&&lid=".$lid."&&tab=tab_5'; </script>";
			}
			else{
			$insert = mysqli_query($link, "INSERT INTO pay_schedule VALUES(null,'$lid','$id','$baccount','','$new_balance','$int_rate','UNPAID','$bbranchid')") or die (mysqli_error($link));
			$update_calculator = mysqli_query($link, "UPDATE interest_calculator SET amt_to_pay = '$new_balance' WHERE lid = '$lid'") or die (mysqli_error($link));
			
				echo "<script>alert('Row Added Successfully'); </script>";
				echo "<script>window.location='newloans.php?tid=".$idm."&&id=".$get_id."&&acn=".$_SESSION['acctno']."&&refid=".$_GET['refid']."&&mid=".base64_encode("404")."&&lid=".$lid."&&tab=tab_5'; </script>";	
			}
		}
	}
	else{
		$calc_totalinterest = ($day/100)*$amount_borrowed;
		$amt_to_pay = $calc_totalinterest + $amount_borrowed;
		$int_rate = number_format(($amt_to_pay),0,'.','');
		$verify_data = mysqli_query($link, "SELECT * FROM pay_schedule WHERE lid = '$lid'") or die (mysqli_error($link));
		
			$amt1 = $amt_to_pay;
			$insert = mysqli_query($link, "INSERT INTO interest_calculator VALUES(null,'$lid','$amt1','$int_rate')") or die (mysqli_error($link));
			
			//CONFIRMATION OF INTEREST RATE
			$select_int_calculator = mysqli_query($link, "SELECT * FROM interest_calculator WHERE lid = '$lid'") or die (mysqli_error($link));
			$get_calculator = mysqli_fetch_array($select_int_calculator);
			$balance = $get_calculator['amt_to_pay'];
			$lrate = $get_calculator['int_rate'];
			$new_balance = number_format(($balance - $lrate),0,'.','');
			
			$insert = mysqli_query($link, "INSERT INTO pay_schedule VALUES(null,'$lid','$id','$baccount','','0','$balance','UNPAID','$bbranchid')") or die (mysqli_error($link));
				$update_calculator = mysqli_query($link, "UPDATE interest_calculator SET amt_to_pay = '0' WHERE lid = '$lid'") or die (mysqli_error($link));
				
				echo "<script>alert('Row Added Successfully1'); </script>";
				echo "<script>window.location='newloans.php?tid=".$idm."&&id=".$get_id."&&acn=".$_SESSION['acctno']."&&refid=".$_GET['refid']."&&mid=".base64_encode("404")."&&lid=".$lid."&&tab=tab_5'; </script>";
	
	}
	
}
?>
                  </div>
                  </div>
				  
              </div>

			  </form>
			  </div>
	<?php
	}
}
?>
              <!-- /.tab-pane -->
            </div>
            <!-- /.tab-content -->
          </div>
				 
					
					
				
				
				</div>
				

              </div>
			 

	
</div>	
</div>
</div>	
</div>