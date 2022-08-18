<div class="row">	
		
	 <section class="content">

<?php
$lide = $_GET['lide'];
$search_loan = mysqli_query($link, "SELECT * FROM loan_info WHERE lid = '$lide'");
$fetchLoan = mysqli_fetch_array($search_loan);
$userIde = $fetchLoan['borrower'];
$userid = $fetchLoan['baccount'];
$loantype = $fetchLoan['loantype'];
?>

	        <div class="box box-danger">
            <div class="box-body">
              <div class="table-responsive"> 
             <div class="box-body">

			 <div class="col-md-14">
             <div class="nav-tabs-custom">
             <ul class="nav nav-tabs">
				<li <?php echo ($_GET['tab'] == 'tab_1') ? "class='active'" : ''; ?>><a href="newloans.php?tid=<?php echo $_SESSION['tid']; ?>&&lide=<?php echo $lide; ?>&&acn=<?php echo $_SESSION['acctno']; ?>&&mid=NDA0&&tab=tab_1">Loan Information</a></li>
              	
				<?php
				  if($loantype == "Purchase"){
				?>
				<li <?php echo ($_GET['tab'] == 'tab_2') ? "class='active'" : ''; ?>><a href="<?php echo ($lide == "") ? '#' : 'newloans.php?tid='.$_SESSION['tid'].'&&lide='.$lide.'&&acn='.$_SESSION['acctno'].'&&mid=NDA0&&tab=tab_2'; ?>">Add Product</a></li>
              	<?php
				  }else{
					  echo "";
				  }
				  ?>

				<li <?php echo ($_GET['tab'] == 'tab_3') ? "class='active'" : ''; ?>><a href="<?php echo ($lide == "") ? '#' : 'newloans.php?tid='.$_SESSION['tid'].'&&lide='.$lide.'&&acn='.$_SESSION['acctno'].'&&mid=NDA0&&tab=tab_3'; ?>">Add Guarantor</a></li>
				
				<?php
				  if($_GET['refid'] == true){
				?>
				<li <?php echo ($_GET['tab'] == 'tab_4') ? "class='active'" : ''; ?>><a href="<?php echo ($lide == "") ? '#' : 'newloans.php?tid='.$_SESSION['tid'].'&&lide='.$lide.'&&acn='.$_SESSION['acctno'].'&&refid='.$_GET['refid'].'&&mid=NDA0&&tab=tab_4'; ?>">Card Tokenization (for recurring payment)</a></li>
              	<?php
				  }else{
				?>
				<li <?php echo ($_GET['tab'] == 'tab_4') ? "class='active'" : ''; ?>><a href="<?php echo ($lide == "") ? '#' : 'newloans.php?tid='.$_SESSION['tid'].'&&lide='.$lide.'&&acn='.$_SESSION['acctno'].'&&mid=NDA0&&tab=tab_4'; ?>">Card Tokenization (for recurring payment)</a></li>
				<?php
				}
				?>
				  
				<li <?php echo ($_GET['tab'] == 'tab_5') ? "class='active'" : ''; ?>><a href="<?php echo ($lide == "") ? '#' : 'newloans.php?tid='.$_SESSION['tid'].'&&lide='.$lide.'&&acn='.$_SESSION['acctno'].'&&mid=NDA0&&tab=tab_5'; ?>">Confirmation</a></li>
              </ul>

             <div class="tab-content">
<?php
if(isset($_GET['tab']) == true)
{
	$tab = $_GET['tab'];
	if($tab == 'tab_1')
	{
	?>
             <div class="tab-pane <?php echo ($_GET['tab'] == 'tab_1') ? 'active' : ''; ?>" id="tab_1">
         
			 <form class="form-horizontal" method="post" enctype="multipart/form-data">

			 <?php
if(isset($_POST['save_loan']))
{
	include("../config/restful_apicalls.php");

	$ltype = mysqli_real_escape_string($link, $_POST['ltype']);
	$lproduct =  mysqli_real_escape_string($link, $_POST['lproduct']);
	$borrower =  mysqli_real_escape_string($link, $_POST['borrower']);
	$baccount = mysqli_real_escape_string($link, $_POST['account']);
	$amount = preg_replace('/[^0-9.]/', '', mysqli_real_escape_string($link, $_POST['amount']));
	$income_amt = preg_replace('/[^0-9.]/', '', mysqli_real_escape_string($link, $_POST['income']));
	$salary_date = mysqli_real_escape_string($link, $_POST['salary_date']);
	$employer =  mysqli_real_escape_string($link, $_POST['employer']);
	$agent = mysqli_real_escape_string($link, $_POST['agent']);
	$status = mysqli_real_escape_string($link, $_POST['status']);
	$upstatus = "Pending";
	$lreasons = mysqli_real_escape_string($link, $_POST['lreasons']);
	$rightTab = ($ltype == "Purchase") ? "tab_2" : "tab_3";

	$search_interest = mysqli_query($link, "SELECT * FROM loan_product WHERE id = '$lproduct'");
	$get_interest = mysqli_fetch_object($search_interest);
	$merchantid = $get_interest->merchantid;
	$max_duration  = $get_interest->duration;
	$interest_type = $get_interest->interest_type;
	$interest = preg_replace('/[^0-9.]/', '', $get_interest->interest)/100;
	$tenor = $get_interest->tenor;
	$vendorid = $get_interest->vendorid;

	$amount_topay = ($interest == "0" || $interest_type == "Reducing Balance") ? $amount : (($amount * $interest) + $amount);

	$id = $_SESSION['tid'];
	$lid = uniqid('LID-').date("dy").time();

	$refid = "EA-loanBooking-".time();
	$billing_type = $bfetch_maintenance_model['billing_type'];
	$loan_booking = $bfetch_maintenance_model['loan_booking'];

	$checkInstWallet = mysqli_query($link, "SELECT * FROM institution_data WHERE institution_id = '$merchantid'");
	$fetchInstWallet = mysqli_fetch_array($checkInstWallet);
	$myiwallet_balance = $fetchInstWallet['wallet_balance'] - $loan_booking;

	//PROCESS CUSTOMER BANK ACCOUNT FOR DISBURSEMENT
	$account_number =  mysqli_real_escape_string($link, $_POST['acct_no']);
	$bank_code =  mysqli_real_escape_string($link, $_POST['bank_code']);

	$systemset = mysqli_query($link, "SELECT * FROM systemset");
	$row1 = mysqli_fetch_object($systemset);
	$seckey = $row1->secret_key;

	$search_restapi = mysqli_query($link, "SELECT * FROM restful_apisetup WHERE api_name = 'transferrecipient'");
	$fetch_restapi = mysqli_fetch_object($search_restapi);
	$api_url = $fetch_restapi->api_url;
			
	// Pass the plan's name, interval and amount
	$postdata =  array(
	'account_number'  => $account_number,
	'account_bank'    => $bank_code,
	'seckey'          => $seckey
	);
	
	$make_call = callAPI('POST', $api_url, json_encode($postdata));
	$result = json_decode($make_call, true);

	$verify_lbal = mysqli_query($link, "SELECT * FROM borrowers WHERE id = '$borrower'"); 
	$fetch_lbal = mysqli_fetch_array($verify_lbal);

	if($fetch_lbal['loan_balance'] > 0)
	{

		echo "<div class='alert bg-orange'>Sorry, The Customer Still Have Few Outstanding Loan to be Balanced.</div>";

	}
	elseif($fetchInstWallet['wallet_balance'] < $loan_booking && mysqli_num_rows($bsearch_maintenance_model) == 1){ // && $billing_type != "PAYGException"
		
		echo "<div class='alert bg-orange'>Sorry, You are unable to book loan at the moment, kindly contact your institution for more details!!</div>";

	}
	else{

		$pschedule = mysqli_real_escape_string($link, $_POST['pschedule']);	
		$fetch_memset = $fetchsys_config['abb'];
		
		$verify_recip = mysqli_query($link, "SELECT * FROM transfer_recipient WHERE acct_no = '$account_number'"); 
	    $getRecipNum = mysqli_num_rows($verify_recip);
        $fetch_recip = mysqli_fetch_array($verify_recip);
		
		//Get the Recipient Id from Rav API
		$recipient_id = ($getRecipNum == 0) ? $result['data']['id'] : $fetch_recip['recipient_id'];
		//Get the Bank Name from Rav API
		$bank_name = $result['data']['bank_name'];
		//Get the Recipient Full Name From Rav API
		$fullname = $result['data']['fullname'];
		
		$bacinfo = "Bank Name: ".strtoupper($bank_name).", ";
		$bacinfo .= "Account Name: ".strtoupper($fullname).", ";
		$bacinfo .= "Account Number: ".$account_number;
		$wallet_date_time = date("Y-m-d h:i:s");
		
		mysqli_query($link, "UPDATE institution_data SET wallet_balance = '$myiwallet_balance' WHERE institution_id = '$bbranchid'");
		$insert = mysqli_query($link, "INSERT INTO wallet_history VALUE(null,'$bbranchid','$refid','Loan Booking for $fullname($baccount)','','$loan_booking','Debit','NGN','Charges','Description: Maintenance fee for Loan Booking by $fullname($baccount)','successful','$wallet_date_time','','$myiwallet_balance','')");
		($getRecipNum == 0) ? mysqli_query($link, "INSERT INTO transfer_recipient VALUES(null,'$bbranchid','$recipient_id','$fullname','$account_number','$bank_code','$bank_name',NOW(),'','$isbranchid')") or die ("Error: " . mysqli_error($link)) : "";
		$insert = mysqli_query($link, "INSERT INTO loan_info VALUES(null,'$lid','$lproduct','$ltype','$borrower','$baccount','$income_amt','$salary_date','$employer','$bacinfo','$amount','','','','','Pending','','','$interest','$amount_topay','$amount_topay','','$lreasons','$upstatus','$p_status','$merchantid','$vendorid','','$bsbranchid','','','','','','','Pending','NotSent','','$wallet_date_time')") or die ("Error: " . mysqli_error($link));
		$insert = mysqli_query($link, "INSERT INTO payment_schedule VALUES(null,'$lid','$baccount','$pschedule','$tenor','$merchantid','$vendorid','$lproduct')") or die ("Error: " . mysqli_error($link));
		
		echo "<div class='alert bg-blue'>Loan Booked Successfully...Please wait patiently to move to the next stage!</div>";
		echo '<meta http-equiv="refresh" content="5;url=newloans.php?tid='.$_SESSION['tid'].'&&lide='.$lid.'&&acn='.$_SESSION['acctno'].'&&mid=NDA0&&tab='.$rightTab.'">';

	}
}
?>

             <div class="box-body">
                
				<div class="form-group">
					<label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Loan Product</label>
					<div class="col-sm-10">
					<select name="lproduct" class="select2" id="loan_products" style="width: 100%;" required>
					<?php
                    $pid = $fetchLoan['lproduct'];
                    $selectProduct = mysqli_query($link, "SELECT * FROM loan_product WHERE id = '$pid'");
                    $fetchLP = mysqli_fetch_array($selectProduct);
                    ?>
				    <option value="<?php echo ($lide == "") ? '' : $fetchLP['id']; ?>" selected="selected"><?php echo ($lide == "") ? '--Select Loan Product--' : $fetchLP['pname'].' - (Interest Rate: '.$fetchLP['interest'].'% based on tenor'; ?></option>

						<?php
						$getin = mysqli_query($link, "SELECT * FROM loan_product WHERE merchantid = '$bbranchid' OR (merchantid != '$bbranchid' AND visibility = 'Yes' AND authorize = '1') ORDER BY id") or die (mysqli_error($link));
						while($row = mysqli_fetch_array($getin))
						{
						echo '<option value="'.$row['id'].'">'.$row['pname'].' - '.'(Interest Rate: '. $row['interest'].'% based on tenor)'.'</option>';
						}
						?>
					</select>
				</div>
				</div>
				
				<?php
				if($lide == ""){
				?>

					<span id='ShowValueFrank'></span>
					<span id='ShowValueFrank'></span>

				<?php
				}
				else{
				?>

				<div class="form-group">
					<label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Loan Tenor (<?php echo $fetchLP['tenor']; ?>):</label>
					<div class="col-sm-10">
						<input name="pschedule" type="text" class="form-control" value="<?php echo $fetchLP['duration']; ?>" required readonly/>
						<hr>
						<span style="color: <?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;"><b>Please note that the repayment fee will be calculated automatically using this schedule.</b></span>
					</div>
				</div>

				<div class="form-group">
					<label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">Amount</label>
					<div class="col-sm-10">
						<input name="amount" type="text" class="form-control" value="<?php echo $fetchLoan['amount']; ?>" placeholder="Enter Amount to Borrow" required>
					</div>
				</div>

				<?php
				}
				?>
				
<?php
$acn = $_GET['acn'];
$get = mysqli_query($link, "SELECT * FROM borrowers WHERE account = '$acn'") or die (mysqli_error($link));
$rows = mysqli_fetch_array($get);

$system = mysqli_query($link, "SELECT * FROM systemset") or die ("Error: " . mysqli_error($link));
$get_system =  mysqli_fetch_array($system);
?>			
				<div class="form-group">
					<label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Borrower</label>
					<div class="col-sm-10">
					
						<input name="borrower" type="text" class="form-control" value=<?php echo $rows['fname'].' '.$rows['lname']; ?> required readonly>
						<input name="borrowerid" type="hidden" class="form-control" value="<?php echo $rows['id']; ?>"/>
				</div>
				</div>
			  
				<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Account</label>
                  <div class="col-sm-10">
                  <input name="account" type="text" class="form-control" value="<?php echo $rows['account']; ?>" required readonly>
                  </div>
                  </div>
                  
            	<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">BVN <?php echo ($bbvn != "" && strlen($bbvn) == "11") ? '<span style="color: '.(($myaltrow['theme_color'] == "") ? "blue" : $myaltrow['theme_color']).'">[<b>Verified</b> <i class="fa fa-check"></i>]</span>' : '<span style="color: '.(($myaltrow['alternate_color'] == "") ? "orange" : $myaltrow['alternate_color']).'">[<b>Not-Verified</b> <i class="fa fa-times"></i>]</span>'; ?></label>
                  <div class="col-sm-10">
                  <input name="cust_bvn" type="text" class="form-control" value="<?php echo (isset($_GET['mybvn']) == true) ? $_GET['mybvn'] : $bbvn; ?>" placeholder="BVN Number Here" maxlength="11" required readonly>
                </div>
                  </div>
				  
	  			<div class="form-group">
	                    <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Income Amount</label>
	                    <div class="col-sm-10">
	                    <input name="income" type="text" class="form-control" value="<?php echo ($lide == '') ? '' : $fetchLoan['income']; ?>" placeholder="Enter Your Income" required>
	                    </div>
	                    </div>
	                    
						
	  			<div class="form-group">
	                    <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Salary Date</label>
	                    <div class="col-sm-10">
	                    <input name="salary_date" type="date" placeholder="YYYY-MM-DD" class="form-control" value="<?php echo ($lide == '') ? '' : $fetchLoan['salary_date']; ?>" required>
	                    </div>
	                    </div>
				
				<div class="form-group">
		                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Employer Name</label>
		                  <div class="col-sm-10">
		                  <input name="employer" type="text" class="form-control" value="<?php echo ($lide == '') ? '' : $fetchLoan['employer']; ?>" required>
		                  </div>
		                  </div>
				
				<?php
				if($lide == ""){
				?>

				<div class="form-group">
						<label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Country</label>
						<div class="col-sm-10">
							<select name="country"  class="form-control select2" id="country" onchange="loadbank();" required>
							<option value="" selected="selected">Select Country</option>
							<option value="NG">Nigeria</option>
							<option value="GH">Ghana</option>
							<option value="KE">Kenya</option>
							<option value="UG">Uganda </option>
							<option value="TZ">Tanzania</option>
							</select>                 
						</div>
				</div>
				  
          
			<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">Account Number</label>
                  <div class="col-sm-10">
                  <input name="acct_no" type="text" id="account_number" onkeydown="loadaccount();" class="form-control" placeholder="Account Number" required>
                  
                  </div>
                  </div>
                  
            <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Bank Code</label>
                  <div class="col-sm-10">
                    <div id="bank_list"></div>
			</div>
			</div>
        
        	<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Account Holder</label></label>
                  <div class="col-sm-10">
                    <span id="act_numb"></span>
			</div>
			</div>

			<?php
			}
			else{
			?>

			<div class="form-group">
						<label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">Your Bank Account Info.</label>
						<div class="col-sm-10">
						<textarea name="bacinfo" class="form-control" rows="2" cols="80" readonly><?php echo $fetchLoan['descs']; ?></textarea>
						</div>
				</div>

			<?php
			}
			?>
				
		    <div class="form-group">
                <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Reasons for Loan</label>
				 <div class="col-sm-10">
                <select name="lreasons" class="form-control select2" style="width: 100%;" required>
				    <option value="<?php echo ($lide == '') ? '' : $fetchLoan['remark']; ?>" selected="selected"><?php echo ($lide == '') ? '--Select Loan Reason--' : $fetchLoan['remark']; ?></option>
	                <option value="Acquire Property">Acquire Property</option>
	                <option value="Appliances/Electronic Gadgets">Appliances/Electronic Gadgets</option>
	                <option value="Build Property">Build Property</option>
	                <option value="Car Purchase/Repairs">Car Purchase/Repairs</option>
	                <option value="Debt Consolidation">Debt Consolidation</option>
	                <option value="Expand Business">Expand Business</option>
	                <option value="Fashion Goods">Fashion Goods</option>
	                <option value="Funeral Expenses">Funeral Expenses</option>
	                <option value="Home Improvements">Home Improvements</option>
	                <option value="Medical Expenses">Medical Expenses</option>
	                <option value="Personal Emergency">Personal Emergency</option>
	                <option value="Portable Goods">Portable Goods</option>
	                <option value="Rent">Rent</option>
	                <option value="School Fees/Educational Expenses">School Fees/Educational Expenses</option>
	                <option value="Start a Business">Start a Business</option>
	                <option value="Travel/Holiday">Travel/Holiday</option>
	                <option value="Pilgrimage">Pilgrimage</option>
	                <option value="Wedding/Event">Wedding/Event</option>
	                <option value="Birthday">Birthday</option>
	                <option value="Other">Other</option>
                </select>
				<span style="color: <?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>;"><b>TELL US WHY YOU NEED LOAN!!</b></span>
              </div>
			  </div>
			  
			   <div class="form-group">
                <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Loan Type</label>
				 <div class="col-sm-10">
                <select name="ltype" class="form-control select2" style="width: 100%;" required>
				    <option value="<?php echo ($lide == '') ? '' : $loantype; ?>" selected="selected"><?php echo ($lide == '') ? '--Select Loan Type--' : $loantype; ?></option>
				    <option value="Individual">Individual Loan</option>
	                <option value="Group">Group Loan</option>
	                <option value="Purchase">Purchase</option>
	           </select>
	           </div>
			  </div>
			
            <input name="status" type="hidden" class="form-control" value="Pending" readonly="readonly">
  
			 </div>
			 
			<?php
			if($lide == ""){
			?>
			  <div align="right">
              <div class="box-footer">
                	<button name="save_loan" type="submit" class="btn bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?> btn-flat"><i class="fa fa-save">&nbsp;Save</i></button>
              </div>
			  </div>
			<?php
			}
			else{
				echo "";
			}
			?>
			  </form>

              </div>
              <!-- /.tab-pane -->

	<?php
	}
	elseif($tab == 'tab_2' && $loantype == "Purchase")
	{
	?>

            <div class="tab-pane <?php echo ($_GET['tab'] == 'tab_2') ? 'active' : ''; ?>" id="tab_2">
    			<form class="form-horizontal" method="post" enctype="multipart/form-data">

<?php
if(isset($_POST['addProduct'])){

      $lid = $_GET['lide'];
      $skuCode = mysqli_real_escape_string($link, $_POST['skuCode']);
      $my_qty = mysqli_real_escape_string($link, $_POST['qty']);
      $loanRequestAmount = $fetchLoan['amount'];
      $currenctdate = date("Y-m-d h:i:s");
      
      $searchStock = mysqli_query($link, "SELECT * FROM loan_stock WHERE merchantid = '$bbranchid' AND skuCode = '$skuCode'");
      $get_Stock = mysqli_fetch_array($searchStock);
	  $item_name = $get_Stock['item_name'];
      $amount = $get_Stock['amount'];
      $availableQty = $get_Stock['qty'];
      $totalAmt = $amount * $my_qty;
      $balanceQty =  $availableQty - $my_qty;
      $updateStatus = ($balanceQty == 0) ? "OutOfStock" : "Available";

	  $searchOStock = mysqli_query($link, "SELECT SUM(total_amount) FROM outgoing_stock WHERE merchantid = '$bbranchid' AND lid = '$lid'");
      $get_OStock = mysqli_fetch_array($searchOStock);
      $currentOSAmt = $get_OStock['SUM(total_amount)'];
      $totalOSAmt = $currentOSAmt + $totalAmt;

      if($my_qty > $availableQty){

            echo "<div class='alert bg-orange'>Sorry!...You cannot add more than the quantity available in the Stock!</div>";

      }elseif($totalAmt > $loanRequestAmount || $totalOSAmt > $loanRequestAmount){

            echo "<div class='alert bg-orange'>Opps!...The value of the items is more than the total amount requested for in the previous form!!</div>";

      }else{

            mysqli_query($link, "INSERT INTO outgoing_stock VALUES(null,'$bbranchid','$bsbranchid','$lid','$skuCode','$item_name','$my_qty','$amount','$totalAmt','Pending','$currenctdate')");
            mysqli_query($link, "UPDATE loan_stock SET qty = '$balanceQty', status = '$updateStatus' WHERE merchantid = '$bbranchid' AND skuCode = '$skuCode'");

            echo "<div class='alert bg-blue'>Items Added Successfully!!</div>";

      }

}
?>

                  <div class="box-body">

                        <div class="form-group">
                              <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Stock Items</label>
                              <div class="col-sm-10">
                                    <select name="skuCode"  class="form-control select2" required>
                                          <option value="" selected='selected'>Select Item&hellip;</option>
                                          <?php
                                          $search = mysqli_query($link, "SELECT * FROM loan_stock WHERE merchantid = '$bbranchid' AND status = 'Available'");
                                          while($get_search = mysqli_fetch_array($search))
                                          {
                                          ?>
                                          <option value="<?php echo $get_search['skuCode']; ?>"><?php echo $get_search['skuCode'].' - '.$get_search['item_name'].' - '.number_format($get_search['amount'],2,'.',','); ?></option>
                                          <?php } ?>
                                    </select>
                              </div>
                        </div>
                              
                        <div class="form-group">
                              <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Quantity</label>
                              <div class="col-sm-10">
                              <input name="qty" type="number" inputmode="numeric" pattern="[0-9]*" class="form-control" required placeholder = "Enter Quantity" required>
                              </div>
                              </div>

                  </div>

                  <div align="right">
				<div class="box-footer">
					<button name="addProduct" type="submit" class="btn bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>"><i class="fa fa-plus"> Add Cart</i></button>
                    <a href="newloans.php?id=<?php echo $_SESSION['tid']; ?>&&lide=<?php echo $lide; ?>&&acn=<?php echo $_SESSION['acctno']; ?>&&mid=NDA0&&tab=tab_3"><button name="Next" type="button" class="btn bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>"><i class="fa fa-save"> Proceed</i></button></a> 
				</div>
			</div>
			  
                
                  <hr>
                
                
                  <table id="example1" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                        <th><input type="checkbox" id="select_all"/></th>
                        <th>SKU Code</th>
                        <th>Items Picture</th>
                        <th>Items Name</th>
                        <th>Qty</th>
                        <th>Total Amount</th>
						<th>Action</th>
                  </tr>
                  </thead>
                  <tbody>
<?php
$lid = $_GET['lide'];
$select = mysqli_query($link, "SELECT * FROM outgoing_stock WHERE lid = '$lid' ORDER BY id DESC") or die (mysqli_error($link));
if(mysqli_num_rows($select)==0)
{
echo "<div class='alert bg-".(($myaltrow['theme_color'] == "") ? 'blue' : $myaltrow['theme_color'])."'>No data found yet!.....Check back later!!</div>";
}
else{
while($row = mysqli_fetch_array($select))
{
$id = $row['id'];
$sku_Code = $row['skuCode'];
?>    
                <tr>
                  <td><input id="optionsCheckbox" class="checkbox" name="selector[]" type="checkbox" value="<?php echo $id; ?>"></td>
                  <td><b><?php echo $sku_Code; ?></b></td>
                  <td>
                  <?php
                    $i = 0;
                    $search_file = mysqli_query($link, "SELECT * FROM stock_items WHERE skuCode = '$sku_Code'") or die ("Error: " . mysqli_error($link));
                    if(mysqli_num_rows($search_file) == 0){
                    	echo "<span style='color: ".(($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color'])."'>---</span>";
                    }else{
                    	while($get_file = mysqli_fetch_array($search_file)){
                    		$i++;
                    ?>
                    <a href="../application/<?php echo $get_file['item_path']; ?>" target="_blank"><img src="../img/file_attached.png" width="20" height="20"><i class="fa fa-eye"></i> Picture<?php echo $i; ?></a>
                    <?php
                    	}
                    }
                  ?>
                  </td>
                  <td><?php echo $row['item_name']; ?></td>
    			<td><?php echo $row['qty']; ?></td>
    			<td><?php echo $row['total_amount']; ?></td>
    			<td><a href="newloans.php?tid=<?php echo $_SESSION['tid']; ?>&&lide=<?php echo $lide; ?>&&myidm=<?php echo $id; ?>&&acn=<?php echo $_SESSION['acctno']; ?>&&mid=NDA0&&tab=tab_2" class="btn bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>"><i class="fa fa-times"></i> Delete</a></td>
                </tr>
<?php } } ?>
             </tbody>
                </table>

<?php
if(isset($_GET['myidm'])){

      $myidm = $_GET['myidm'];

	  $selectOS = mysqli_query($link, "SELECT * FROM outgoing_stock WHERE id = '$myidm'");
	  $fetchOS = mysqli_fetch_array($selectOS);
	  $skuCode = $fetchOS['skuCode'];
	  $OSQty = $fetchOS['qty'];

	  $selectLS = mysqli_query($link, "SELECT * FROM loan_stock WHERE skuCode = '$skuCode'");
	  $fetchLS = mysqli_fetch_array($selectLS);
	  $curQty = $fetchLS['qty'];
	  $balQty = $curQty + $OSQty;
	
	  mysqli_query($link, "UPDATE loan_stock SET qty = '$balQty', status = 'Available' WHERE skuCode = '$skuCode'");
      mysqli_query($link, "DELETE FROM outgoing_stock WHERE id = '$myidm'");
      echo "<script>alert('Cart Delete Successfully!'); </script>";
	  echo '<script>window.location="newloans.php?tid='.$_SESSION['tid'].'&&lide='.$lide.'&&acn='.$_SESSION['acctno'].'&&mid=NDA0&&tab=tab_2"; </script>';

}
?>

				</form>
			</div>
             <!-- /.tab-pane -->

<?php
	}
	elseif($tab == 'tab_3')
	{
	?>

            <div class="tab-pane <?php echo ($_GET['tab'] == 'tab_3') ? 'active' : ''; ?>" id="tab_3">
    			<form class="form-horizontal" method="post" enctype="multipart/form-data">

<?php
if(isset($_POST['addGuarantor'])){

    $lid = $_GET['lide'];
    $g_name = mysqli_real_escape_string($link, $_POST['g_name']);
    $g_phone = mysqli_real_escape_string($link, $_POST['g_phone']);
    $g_bvn = mysqli_real_escape_string($link, $_POST['g_bvn']);
    $grela = mysqli_real_escape_string($link, $_POST['grela']);
    $gaddress = mysqli_real_escape_string($link, $_POST['gaddress']);
    
    $sourcepath = $_FILES["image"]["tmp_name"];
    $targetpath = "../img/" . $_FILES["image"]["name"];
	move_uploaded_file($sourcepath,$targetpath);
	
	$location = $_FILES['image']['name'];
	
	mysqli_query($link, "INSERT INTO loan_guarantor VALUES(null,'$lid','$userIde','$userid','$location','$g_name','$g_phone','$g_bvn','$grela','$gaddress','Pending','','','$bbranchid','$bsbranchid','')");
      
    echo "<div class='alert bg-blue'>Guarantor Added Successfully!!</div>";

}
?>
    			 
    			 <div class="box-body">
    			    
        			 <div class="form-group">
    				    <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Gurantor's Passport</label>
    				    <div class="col-sm-10">
      		  		        <input type='file' name="image" onChange="readURL(this);" />
           			        <img id="blah"  src="../avtar/user2.png" alt="Image Here" height="100" width="100"/>
    			        </div>
    			    </div>
    			    
    			    <div class="form-group">
                      <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Guarantor's Name</label>
                      <div class="col-sm-10">
                      <input name="g_name" type="text" class="form-control" placeholder = "Guarantor's Name" required>
                      </div>
                    </div>
                    
                    <div class="form-group">
                      <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Guarantor's Phone No.</label>
                      <div class="col-sm-10">
                      <input name="g_phone" type="text" class="form-control" placeholder = "Guarantor's Phone Number" required>
    				  <span style="color: <?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>;"> <b>Make sure you include country code e.g 234XXXXXXXXXX </span><br>
                      </div>
    			    </div>
    			    
    			    <div class="form-group">
                      <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Guarantor's BVN</label>
                      <div class="col-sm-10">
                      <input name="g_bvn" type="text" class="form-control" placeholder = "Guarantor's BVN" required>
                      </div>
                    </div>
    			
    			    <div class="form-group">
                      <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Relationship</label>
                      <div class="col-sm-10">
                      <input name="grela" type="text" class="form-control" placeholder="Relationship" required>
                      </div>
                     </div>
    				 
    				<div class="form-group">
                      	<label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Guarantor's Address</label>
                      	<div class="col-sm-10">
    					<textarea name="gaddress" class="form-control" rows="2" cols="80" required></textarea>
               			 </div>
              	    </div> 
              	    
              	</div>

			<div align="right">
				<div class="box-footer">
					<button name="addGuarantor" type="submit" class="btn bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>"><i class="fa fa-plus"> Add</i></button>
                    <a href="newloans.php?tid=<?php echo $_SESSION['tid']; ?>&&lide=<?php echo $lide; ?>&&acn=<?php echo $_SESSION['acctno']; ?>&&mid=NDA0&&tab=tab_3"><button name="Next" type="button" class="btn bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>"><i class="fa fa-save"> Proceed</i></button></a> 
				</div>
		      </div>
			  
                
                <hr>
                
                
                <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th><input type="checkbox" id="select_all"/></th>
                  <th>Picture</th>
				  <th>Guarantor Name</th>
			      <th>Guarantor Phone</th>
                  <th>Relationship</th>
			      <th>Guarantor BVN</th>
                  <th>Guarantor Address</th>
                  <th>Status</th>
                 </tr>
                </thead>
                <tbody>
<?php
$lid = $_GET['lide'];
$select = mysqli_query($link, "SELECT * FROM loan_guarantor WHERE lid = '$lid' ORDER BY id DESC") or die (mysqli_error($link));
if(mysqli_num_rows($select)==0)
{
echo "<div class='alert bg-".(($myaltrow['theme_color'] == "") ? 'blue' : $myaltrow['theme_color'])."'>No data found yet!.....Check back later!!</div>";
}
else{
while($row = mysqli_fetch_array($select))
{
$id = $row['id'];
?>    
                <tr>
                	<td><input id="optionsCheckbox" class="checkbox" name="selector[]" type="checkbox" value="<?php echo $id; ?>"></td>
                  	<td><a href="<?php echo $fetchsys_config['file_baseurl'].$row['picture']; ?>" class="btn bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>" target="blank"><i class="fa fa-search"></i> View Picture</a></td>
                  	<td><?php echo $row['gname']; ?></td>
    				<td><?php echo $row['gphone']; ?></td>
    		      	<td><?php echo $row['grela']; ?></td>
    				<td><?php echo $row['gbvn']; ?></td>
    				<td><?php echo $row['gaddress']; ?></td>
    				<td><?php echo ($row['gstatus'] == "Accepted" ? "<span class='label bg-blue'><i class='fa fa-check'></i>".$row['gstatus']."</span>" : ($row['gstatus'] == "Pending" ? "<span class='label bg-orange'><i class='fa fa-exclamation'></i>".$row['gstatus']."</span>" : "<span class='label bg-red'><i class='fa fa-times'></i>".$row['lstatus']."</span>")); ?></td>
                </tr>
<?php } } ?>
             </tbody>
                </table>

				</form>
			</div>
             <!-- /.tab-pane -->


	<?php
	}
	elseif($tab == 'tab_4')
	{
		$search_customer = mysqli_query($link, "SELECT * FROM borrowers WHERE account = '$userid'");
		$get_customer = mysqli_fetch_object($search_customer);
		
		$select1 = mysqli_query($link, "SELECT * FROM systemset") or die (mysqli_error($link));
		$row1 = mysqli_fetch_object($select1);
	?>
	        <div class="tab-pane <?php echo ($_GET['tab'] == 'tab_4') ? 'active' : ''; ?>" id="tab_5">
			<?php
			if(isset($_GET['refid']) == true)
			{	
				include ("../config/restful_apicalls.php");

				$systemset = mysqli_query($link, "SELECT * FROM systemset");
				$row1 = mysqli_fetch_object($systemset);
  
				$result = array();
				$refid = $_GET['refid'];
  				$acn = $userid;
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
				
				foreach($result['data']['card']['card_tokens'] as $key)
      
                    $authcode1 = $key['embedtoken'];
                    
                foreach($result['data']['account']['account_token'] as $key1)
      
                    $authcode2 = $key1['token'];
                    
                    $authorization_code = ($authcode1 == "") ? $authcode2 : $authcode1;
                    
				    //print_r($result);
				if($result['status'] == 'success'){
				    // the transaction was successful, you can deliver value
				    $paymenttype        =   $result['data']['paymenttype'];
					//$autgorization_code = 	($paymenttype == "card") ? $result['data']['card']['card_tokens']['embedtoken'] : $result['data']['account']['account_token']['token'];
					$refid = $_GET['refid'];
		  			$lid = $_GET['lide'];
		  			$acn = $userid;
					$email = $get_customer->email;
						  
					$search_card = mysqli_query($link, "SELECT * FROM authorized_card WHERE lid = '$lid'");
					if(mysqli_num_rows($search_card) == 1){
							  //no action should be performed
					}else{
						$insert_authorization_record = mysqli_query($link, "INSERT INTO authorized_card VALUES(null,'$refid','$lid','$acn','$email','$authorization_code',NOW())") or die ("Error" . mysqli_error($link));
						  
					    echo "<script>alert('Card / Account Verified Successfully'); </script>";
					}
				}else{
				    // the transaction was not successful, do not deliver value'
				    // print_r($result);  //uncomment this line to inspect the result, to check why it failed.
				    echo "Transaction was not successful: Last gateway response was: ".$result['message'];
				}
			?>
			
			<br>
			<p style="color: <?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;"><i class="fa fa-check"></i><b> Card Details Verified Successfully!</b></p>
			
			<hr>
			
			<div align="right">
              <div class="box-footer">
			   <a href="newloans.php?tid=<?php echo $_SESSION['tid']; ?>&&lide=<?php echo $lide; ?>&&acn=<?php echo $_SESSION['acctno']; ?>&&refid=<?php echo $_GET['refid']; ?>&&mid=NDA0&&tab=tab_3"><button name="Next" type="button" class="btn bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>"><i class="fa fa-save"> Proceed</i></button></a> 
              </div>
			  </div>
			
		<?php
		}
		else{
			?>
				  <form >
				    <a href="verify_card.php?tid=<?php echo $_SESSION['tid']; ?>&&id=<?php echo $_GET['id']; ?>&&acn=<?php echo $acnt_id; ?>&&mid=NDA0&&lid=<?php echo $_GET['lid']; ?>"><button type="button" class="btn bg-blue"><i class="fa fa-refresh"></i> Tokenize card! </button></a>
				  </form>
				  <br>
	<?php
	$select1 = mysqli_query($link, "SELECT * FROM systemset") or die (mysqli_error($link));
	$row1 = mysqli_fetch_object($select1);
	?>
				  <p style="color: <?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>;"><b><i>Note that you must verify your Bank Account / Card details for us to Activate Recurring Payment for Loan.</i></b></p>
				  <p style="color: <?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>;"><b>NOTE:</b><i> Be aware that you will be charge the total amount of <b><?php echo $row1->currency.number_format($row1->auth_charges,2,'.',','); ?></b> to confirm the validity of the card.</i></p>
				  
		<?php
	    }
		?>
		
              </div>
    <!-- /.tab-pane -->

	<?php
	}
	elseif($tab == 'tab_5')
	{
	?>

            <div class="tab-pane <?php echo ($_GET['tab'] == 'tab_5') ? 'active' : ''; ?>" id="tab_5">
    			<form class="form-horizontal" method="post" enctype="multipart/form-data">

<?php
if(isset($_POST['Yes'])){

	$lid = $_GET['lide'];
	$baccount = $userid;
	$amount = $fetchLoan['amount'];
	$iuid = "";

	$search_origin = mysqli_query($link, "SELECT * FROM institution_data WHERE institution_id = '$bbranchid'");
	$fetch_origin = mysqli_fetch_array($search_origin);
	$iwallet_balance = $fetch_origin['wallet_balance'];
	$isenderid = ($bsender_id == "") ? $fetchsys_config['abb'] : $bsender_id;

	$maintenance_row = mysqli_num_rows($bsearch_maintenance_model);
	$sms_rate = $fetchsys_config['fax'];

	$message = "$isenderid>>>LOAN APPLICATION ";
    $message .= "Dear ".$myfn."! Your loan application with Loan ID: ".$lid." of ".$bbcurrency.number_format($amount,2,'.',',')." has been initiated on ".date('d/m/Y')."";
    $message .= " Kindly await our response upon review. Thanks.";

	$max_per_page = 153;
	$sms_length = strlen($message);
	$calc_length = ceil($sms_length / $max_per_page);
	$sms_charges = $calc_length * $sms_rate;
	$mybalance = $iwallet_balance - $sms_charges;

	$debitWAllet = ($bgetSMS_ProviderNum == 1 || ($maintenance_row == 1 && $bbilling_type == "PAYGException")) ? "No" : "Yes"; 
	$refid = uniqid("EA-smsCharges-").time();

	mysqli_query($link, "UPDATE loan_info SET upstatus = 'Completed' WHERE lid = '$lid'");
    
	($bbilling_type == "PAYGException" ? "" : ($debitWAllet == "No" ? $sendSMS->instGeneralAlert($bozeki_password, $bozeki_url, $isenderid, $phone2, $message, $bbranchid, $refid, $sms_charges, $baccount, $mybalance, $debitWallet) : ($debitWAllet == "Yes" && $sms_charges <= $iwallet_balance ? $sendSMS->instGeneralAlert($bozeki_password, $bozeki_url, $isenderid, $phone2, $message, $bbranchid, $refid, $sms_charges, $baccount, $mybalance, $debitWallet) : "")));

    echo "<div class='alert bg-blue'>Loan application submit successfully!</div>";
    echo '<meta http-equiv="refresh" content="5;url=newloans.php?tid='.$_SESSION['tid'].'&&acn='.$_SESSION['acctno'].'&&mid=NDA0&&tab=tab_1">';
    
}
?>

<?php
if(isset($_POST['No'])){
      
      $lid = $_GET['lide'];
      
      mysqli_query($link, "DELETE FROM loan_guarantor WHERE lid = '$lid'");
      ($loantype == "Purchase") ? mysqli_query($link, "DELETE FROM outgoing_stock WHERE lid = '$lid'") : "";
      mysqli_query($link, "DELETE FROM loan_info WHERE lid = '$lid'");
      
      echo "<div class='alert bg-blue'>Loan application cancelled successfully!</div>";
      echo '<meta http-equiv="refresh" content="5;url=newloans.php?tid='.$_SESSION['tid'].'&&acn='.$_SESSION['acctno'].'&&mid=NDA0&&tab=tab_1">';
    
}
?>

    			    <div class="box-body">
    			        
    			        <b>Are you sure you want to submit this loan application?</b>
    			        
    			        <div class="form-group" align="left">
                            <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;"></label>
                            <div class="col-sm-7">
                            </div>
                            <label for="" class="col-sm-3 control-label">
                                <button name="Yes" type="submit" class="btn bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>"><i class="fa fa-check">&nbsp;Yes</i></button> 
                                <button name="No" type="submit" class="btn bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>"><i class="fa fa-times">&nbsp;No</i></button>              
                            </label>
                        </div>
    			        
    			    </div>
    			    
    			</form>
    		</div>
            <!-- /.tab-pane -->

<?php
	}
}
?>

			</div>
            <!-- /.tab-content -->

        </div>
        </div>
      </div>
      <!-- /.box -->
			 

	
</div>	
</div>
</div>	
</div>