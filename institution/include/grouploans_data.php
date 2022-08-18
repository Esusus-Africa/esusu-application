<div class="box">
<?php
$lide = $_GET['lide'];
$search_loan = mysqli_query($link, "SELECT * FROM loan_info WHERE lid = '$lide'");
$fetchLoan = mysqli_fetch_array($search_loan);
$userIde = $fetchLoan['borrower'];
$userid = $fetchLoan['baccount'];
?>
			<div class="col-md-14">
            <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
              <li <?php echo ($_GET['tab'] == 'tab_1') ? "class='active'" : ''; ?>><a href="grouploans.php?id=<?php echo $_SESSION['tid']; ?>&&lide=<?php echo $lide; ?>&&mid=NDA1&&tab=tab_1">Loan Information</a></li>
              <li <?php echo ($_GET['tab'] == 'tab_2') ? "class='active'" : ''; ?>><a href="<?php echo ($lide == "") ? '#' : 'grouploans.php?id='.$_SESSION['tid'].'&&lide='.$lide.'&&mid=NDA1&&tab=tab_2'; ?>">Add Guarantor</a></li>
			  <li <?php echo ($_GET['tab'] == 'tab_2a') ? "class='active'" : ''; ?>><a href="<?php echo ($lide == "") ? '#' : 'grouploans.php?id='.$_SESSION['tid'].'&&lide='.$lide.'&&mid=NDA1&&tab=tab_2a'; ?>">Upload Document</a></li>
              <li <?php echo ($_GET['tab'] == 'tab_3') ? "class='active'" : ''; ?>><a href="<?php echo ($lide == "") ? '#' : 'grouploans.php?id='.$_SESSION['tid'].'&&lide='.$lide.'&&mid=NDA1&&tab=tab_3'; ?>">Confirmation</a></li>
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

	$lproduct =  mysqli_real_escape_string($link, $_POST['lproduct']);
	$baccount = mysqli_real_escape_string($link, $_POST['account']);
	$amount = preg_replace('/[^0-9.]/', '', mysqli_real_escape_string($link, $_POST['amount']));
	$income_amt = preg_replace('/[^0-9.]/', '', mysqli_real_escape_string($link, $_POST['income']));
	$salary_date = mysqli_real_escape_string($link, $_POST['salary_date']);
	$employer =  mysqli_real_escape_string($link, $_POST['employer']);
	$agent = mysqli_real_escape_string($link, $_POST['agent']);
	$status = mysqli_real_escape_string($link, $_POST['status']);
	$upstatus = "Pending";
	$lreasons = mysqli_real_escape_string($link, $_POST['lreasons']);

	$search_interest = mysqli_query($link, "SELECT * FROM loan_product WHERE id = '$lproduct'");
	$get_interest = mysqli_fetch_object($search_interest);
	$max_duration  = $get_interest->duration;
	$interest_type = $get_interest->interest_type;
	$interest = preg_replace('/[^0-9.]/', '', $get_interest->interest)/100;
	$tenor = $get_interest->tenor;

	$amount_topay = ($interest == "0" || $interest_type == "Reducing Balance") ? $amount : (($amount * $interest) + $amount);

	$id = $_SESSION['tid'];
	$lid = uniqid('LID-').date("dy").time();

	$refid = "EA-loanBooking-".time();
	$billing_type = $ifetch_maintenance_model['billing_type'];
	$loan_booking = $ifetch_maintenance_model['loan_booking'];
	$myiwallet_balance = $iassigned_walletbal - $loan_booking;

	//PROCESS CUSTOMER BANK ACCOUNT FOR DISBURSEMENT
	$account_number =  mysqli_real_escape_string($link, $_POST['acct_no']);
	$bank_code =  mysqli_real_escape_string($link, $_POST['bank_code']);
	$explode = explode(":",$bank_code);
    $bank_name = $explode[1];
	$fullname = mysqli_real_escape_string($link, $_POST['b_name']);

	$verify_lbal = mysqli_query($link, "SELECT * FROM borrowers WHERE account = '$baccount'"); 
	$fetch_lbal = mysqli_fetch_array($verify_lbal);
	$borrower =  $fetch_lbal['id'];

	/**
	if($fetch_lbal['loan_balance'] > 0)
	{

		echo "<div class='alert bg-orange'>Sorry, The Customer Still Have Few Outstanding Loan to be Balanced.</div>";

	}
	else
	*/
	if($iassigned_walletbal < $loan_booking && mysqli_num_rows($isearch_maintenance_model) == 1){ // && $billing_type != "PAYGException"
		
		echo "<div class='alert bg-orange'>Sorry, You are unable to book for loan due to insufficient fund in your Wallet!!</div>";

	}
	else{

		$pschedule = mysqli_real_escape_string($link, $_POST['pschedule']);	
		$fetch_memset = $fetchsys_config['abb'];
		
		$bacinfo = "Bank Name: ".strtoupper($bank_name).", ";
		$bacinfo .= "Account Name: ".strtoupper($fullname).", ";
		$bacinfo .= "Account Number: ".$account_number;
		$wallet_date_time = date("Y-m-d h:i:s");
		
		mysqli_query($link, "UPDATE institution_data SET wallet_balance = '$myiwallet_balance' WHERE institution_id = '$institution_id'");
		//($getRecipNum == 0) ? mysqli_query($link, "INSERT INTO transfer_recipient VALUES(null,'$institution_id','$recipient_id','$fullname','$account_number','$bank_code','$bank_name',NOW(),'$iuid','$isbranchid')") or die ("Error: " . mysqli_error($link)) : "";
		$insert = mysqli_query($link, "INSERT INTO wallet_history VALUE(null,'$institution_id','$refid','self','','$loan_booking','Debit','NGN','Charges','Description: Maintenance fee for Loan Booking','successful','$wallet_date_time','$iuid','$myiwallet_balance','')");
		$insert = mysqli_query($link, "INSERT INTO loan_info VALUES(null,'$lid','$lproduct','Group','$borrower','$baccount','$income_amt','$salary_date','$employer','$bacinfo','$amount','','','$agent','','Pending','','','$interest','$amount_topay','$amount_topay','','$lreasons','$upstatus','$p_status','$institution_id','','$idept','$isbranchid','','','','','','','Pending','NotSent','','$wallet_date_time')") or die ("Error: " . mysqli_error($link));
		$insert = mysqli_query($link, "INSERT INTO payment_schedule VALUES(null,'$lid','$baccount','$pschedule','$tenor','$institution_id','','$lproduct')") or die ("Error: " . mysqli_error($link));
		
		echo "<div class='alert bg-blue'>Loan Booked Successfully...Please wait patiently to move to the next stage!</div>";
		echo '<meta http-equiv="refresh" content="5;url=grouploans.php?id='.$_SESSION['tid'].'&&lide='.$lid.'&&mid=NDA1&&tab=tab_2">';

	}
}
?>

             <div class="box-body">

            <div class="form-group">
                <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Group</label>
				 <div class="col-sm-10">
                <select name="mygroup" class="select2" id="mygroup" style="width: 100%;" required>
				<option value="" selected="selected">--Select Customer Group--</option>
				<?php
				$get = mysqli_query($link, "SELECT * FROM lgroup_setup WHERE merchant_id = '$institution_id' ORDER BY id") or die ("Error: " . mysqli_error($link));
				while($rows = mysqli_fetch_array($get))
				{
				echo '<option value="'.$rows['id'].'">'.$rows['gname'].'</option>';
				}
				?>
                </select>
              </div>
			</div>
			  
			  <span id='ShowValueFrank4'></span>
				
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
					$getin = mysqli_query($link, "SELECT * FROM loan_product WHERE category = 'Group' AND merchantid = '$institution_id' ORDER BY id") or die (mysqli_error($link));
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
				  
			  <div class="form-group">
                <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Income Amount</label>
                <div class="col-sm-10">
                <input name="income" type="text" class="form-control" value="<?php echo ($lide == "") ? '' : $fetchLoan['income']; ?>" placeholder="Enter Your Income" required>
                </div>
                </div>
				
		<div class="form-group">
                <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Salary Date</label>
                <div class="col-sm-10">
                <input name="salary_date" type="date" class="form-control" value="<?php echo ($lide == "") ? '' : $fetchLoan['salary_date']; ?>" required>
                </div>
                </div>
		
		<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Employer Name</label>
                  <div class="col-sm-10">
                  <input name="employer" type="text" class="form-control" value="<?php echo ($lide == "") ? '' : $fetchLoan['employer']; ?>" required>
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
			  <option value="RW">Rwanda</option>
			  <option value="ZA">South Africa</option>
			  <option value="ZM">Zambia</option>
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
                 	<label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Reasons for Loan.</label>
                 	<div class="col-sm-10">
                <select name="lreasons" class="form-control select2" style="width: 100%;" required>
					<option value="<?php echo ($lide == '') ? '' : $fetchLoan['remark']; ?>" selected="selected"><?php echo ($lide == '') ? '--Select Loan Type--' : $fetchLoan['remark']; ?></option>
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
				<span style="color: orange;"><b>TELL US WHY THE CUSTOMER NEED LOAN!!</b></span>
          			 </div>
				 </div>
			  
<?php
if($irole == 'institution_super_admin')
{
?>
		<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Account Officer</label>
                  <div class="col-sm-10">
				<select name="agent"  class="form-control select2" required>
					<?php
					$mystaffid = $fetchLoan['agent'];
					$searchUser = mysqli_query($link, "SELECT * FROM user WHERE created_by = '$institution_id' AND id = '$mystaffid'");
					$get_searchUser = mysqli_fetch_array($searchUser);
					?>
					<option value="<?php echo ($lide == '') ? '' : $mystaffid; ?>" selected="selected"><?php echo ($lide == '') ? '--Assign Staff--' : $get_searchUser['name'].' '.$get_searchUser['fname'].' ('.$get_searchUser['country'].')'; ?></option>
					<?php
					$search = mysqli_query($link, "SELECT * FROM user WHERE created_by = '$institution_id'");
					while($get_search = mysqli_fetch_array($search))
					{
					?>
					<option value="<?php echo $get_search['id']; ?>"><?php echo $get_search['name'].' '.$get_search['fname'].' ('.$get_search['country'].')'; ?></option>
					<?php } ?>
				</select>
				<span style="color: <?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>;"> This will allow you to download the collection sheet for each staff and the staff will know which customers to work with. </span><br>
		</div>
		</div>
<?php
}
else{
    ?>
  
    <input name="agent" type="hidden" class="form-control" value="<?php echo $iuid; ?>"/>    
    
<?php
}
?>
			
    <input name="status" type="hidden" class="form-control" value="Pending" readonly="readonly">

		</div>
			 
		<?php
		if($lide == ""){
		?>
		<div align="right">
            <div class="box-footer">
                <button name="save_loan" type="submit" class="btn bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>"><i class="fa fa-save">&nbsp;Proceed</i></button>
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
	elseif($tab == 'tab_2')
	{
	?>

            <div class="tab-pane <?php echo ($_GET['tab'] == 'tab_2') ? 'active' : ''; ?>" id="tab_2">
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
	
	mysqli_query($link, "INSERT INTO loan_guarantor VALUES(null,'$lid','$userIde','$userid','$location','$g_name','$g_phone','$g_bvn','$grela','$gaddress','Pending','','$iuid','$institution_id','$isbranchid','')");
    
    echo "<div class='alert bg-blue'>Guarantor Added Successfully!!</div>";

}
?>
    			 
    			 <div class="box-body">
    			    
        			 <div class="form-group">
    				    <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Gurantor's Passport</label>
    				    <div class="col-sm-10">
      		  		        <input type='file' name="image" onChange="readURL(this);" />
           			        <img id="blah"  src="<?php echo $fetchsys_config['file_baseurl']; ?>user2.png" alt="Image Here" height="100" width="100"/>
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
                      <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Guarantor's <?php echo ($bvn_label == "") ? "BVN" : $bvn_label; ?></label>
                      <div class="col-sm-10">
                      <input name="g_bvn" type="text" class="form-control" placeholder = "Guarantor's <?php echo ($bvn_label == "") ? "BVN" : $bvn_label; ?>" required>
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
                        <a href="grouploans.php?id=<?php echo $_SESSION['tid']; ?>&&lide=<?php echo $lide; ?>&&mid=NDA1&&tab=tab_2a"><button name="Next" type="button" class="btn bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>"><i class="fa fa-save"> Proceed</i></button></a> 
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
				  <th>Guarantor <?php echo ($bvn_label == "") ? "BVN" : $bvn_label; ?></th>
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
	elseif($tab == 'tab_2a')
	{
	?>

            <div class="tab-pane <?php echo ($_GET['tab'] == 'tab_2a') ? 'active' : ''; ?>" id="tab_2a">
    			<form class="form-horizontal" method="post" enctype="multipart/form-data">

<?php
if(isset($_POST['addDoc'])){
    
    $lid = $_GET['lide'];
	$docType = mysqli_real_escape_string($link, $_POST['docType']);
	$exp_date = mysqli_real_escape_string($link, $_POST['exp_date']);
    $date_time = date("Y-m-d h:i:s");

    $sourcepath = $_FILES["id_file"]["tmp_name"];
	$targetpath = "../img/" . $_FILES["id_file"]["name"];
	move_uploaded_file($sourcepath,$targetpath);
	
	$location = $_FILES['id_file']['name']; 
	
	mysqli_query($link, "INSERT INTO loan_required_doc VALUES(null,'$institution_id','$isbranchid','$iuid','$lid','$userIde','$userid','$docType','$location','$exp_date','Pending','$date_time','$date_time')");
    
    echo "<div class='alert bg-blue'>Document Added Successfully!!</div>";

}
?>
    			 
    			 <div class="box-body">

				 	<div class="form-group">
                      <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Document Type</label>
                      <div class="col-sm-10">
						<select name="docType"  class="form-control select2" required>
						<option value="" selected="selected">Select Document Type</option>
						<option value="Monthly_Statements">Monthly Statements</option>
						<option value="Credit_History">Credit History</option>
						<option value="Valid_ID_Card">Valid ID Card</option>
						<option value="Utility_Bill">Utility Bill</option>
						</select>
						</div>
						</div>

					<div class="form-group">
    				    <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Upload Doc.</label>
    				    <div class="col-sm-10">
						<input name="id_file" type="file" class="btn bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>" required/>
    			        </div>
    			    </div>
    			    
    			    <div class="form-group">
                      <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Expiry Date</label>
                      <div class="col-sm-10">
                      <input name="exp_date" type="date" class="form-control">
                      </div>
                    </div>
              	    
              	</div>

				<div align="right">
					<div class="box-footer">
						<button name="addDoc" type="submit" class="btn bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>"><i class="fa fa-upload"> Upload</i></button>
                        <a href="newloans.php?id=<?php echo $_SESSION['tid']; ?>&&lide=<?php echo $lide; ?>&&mid=NDA1&&tab=tab_3"><button name="Next" type="button" class="btn bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>"><i class="fa fa-save"> Proceed</i></button></a> 
					</div>
				</div>
			  
                
                <hr>
				
				<?php echo ($approve_loan_document == '1') ? '<button type="submit" class="btn bg-'.(($myaltrow['theme_color'] == "") ? "blue" : $myaltrow['theme_color']).'" name="approveDoc"><i class="fa fa-check"></i> Approve</button>' : ''; ?>
				<?php echo ($decline_loan_document == '1') ? '<button type="submit" class="btn bg-'.(($myaltrow['alternate_color'] == "") ? "orange" : $myaltrow['alternate_color']).'" name="declineDoc"><i class="fa fa-times"></i> Decline</button>' : ''; ?>

                <hr>

				<table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th><input type="checkbox" id="select_all"/></th>
				  <th>DocType</th>
				  <th>Document</th>
                  <th>Expiry Date</th>
				  <th>Status</th>
                  <th>createdOn</th>
                  <th>updatedOn</th>
                 </tr>
                </thead>
                <tbody>
					<span id='loan_required_doc'>Loading...</span>
             	</tbody>
                </table>

						<?php
						if(isset($_POST['approveDoc'])){
							
							$idm = $_GET['id'];
							$id=$_POST['selector'];
							$N = count($id);

							if($id == ''){
								echo "<script>alert('Row Not Selected!!!'); </script>";	
								echo "<script>window.location='grouploans.php?id=".$_SESSION['tid']."&&lide=".$lide."&&mid=NDA1&&&tab=tab_2a'; </script>";
							}
							else{
								for($i=0; $i < $N; $i++)
								{
									$date_time = date("Y-m-d h:i:s");
									$result = mysqli_query($link,"UPDATE loan_required_doc SET docStatus = 'Approved', dateUpdated = '$date_time' WHERE id ='$id[$i]'");
									echo "<script>alert('Document Approved Successfully!!!'); </script>";
									echo "<script>window.location='grouploans.php?id=".$_SESSION['tid']."&&lide=".$lide."&&mid=NDA1&&tab=tab_2a'; </script>";
								}
							}
						}

						if(isset($_POST['declineDoc'])){
							
							$idm = $_GET['id'];
							$id = $_POST['selector'];
							$N = count($id);

							if($id == ''){
								echo "<script>alert('Row Not Selected!!!'); </script>";
								echo "<script>window.location='grouploans.php?id=".$_SESSION['tid']."&&lide=".$lide."&&mid=NDA1&&tab=tab_2a'; </script>";
							}
							else{
								for($i=0; $i < $N; $i++)
								{
									$date_time = date("Y-m-d h:i:s");
									$result = mysqli_query($link,"UPDATE loan_required_doc SET docStatus = 'Declined', dateUpdated = '$date_time' WHERE id ='$id[$i]'");
									echo "<script>alert('Document Declined Successfully!!!'); </script>";
									echo "<script>window.location='grouploans.php?id=".$_SESSION['tid']."&&lide=".$lide."&&mid=NDA1&&tab=tab_2a'; </script>";
								}
							}
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
if(isset($_POST['Yes'])){

	$lid = $_GET['lide'];
	$baccount = $userid;
	$amount = $fetchLoan['amount'];
	$search_cust = mysqli_query($link, "SELECT * FROM borrowers WHERE account = '$baccount'") or die("Error:" . mysqli_error($link));
    $get_cust = mysqli_fetch_array($search_cust);
    $fname = $get_cust['fname'];
    $phone = $get_cust['phone'];
    $to = $get_cust['email'];
	$sms_checker = $get_cust['sms_checker'];

	$maintenance_row = mysqli_num_rows($isearch_maintenance_model);

	$message = "$isenderid>>>LOAN APPLICATION ";
    $message .= "Dear ".$fname."! Your loan application with Loan ID: ".$lid." of ".$icurrency.number_format($amount,2,'.',',')." has been initiated on ".date('d/m/Y')."";
    $message .= " Kindly await our response upon review. Thanks.";

	$max_per_page = 153;
	$sms_length = strlen($message);
	$calc_length = ceil($sms_length / $max_per_page);
	$sms_charges = $calc_length * $cust_charges;
	$mybalance = $iwallet_balance - $sms_charges;

	$debitWAllet = ($getSMS_ProviderNum == 1 || ($maintenance_row == 1 && $billing_type == "PAYGException")) ? "No" : "Yes"; 
	$refid = uniqid("EA-smsCharges-").time();
    
	($sms_checker == "No" || $billing_type == "PAYGException" ? "" : ($debitWAllet == "No" ? $sendSMS->instGeneralAlert($ozeki_password, $ozeki_url, $isenderid, $phone, $message, $institution_id, $refid, $sms_charges, $iuid, $mybalance, $debitWallet) : ($debitWAllet == "Yes" && $sms_charges <= $iwallet_balance ? $sendSMS->instGeneralAlert($ozeki_password, $ozeki_url, $isenderid, $phone, $message, $institution_id, $refid, $sms_charges, $iuid, $mybalance, $debitWallet) : "")));

    echo "<div class='alert bg-blue'>Loan application submit successfully!</div>";
    echo '<meta http-equiv="refresh" content="5;url=newloans.php?id='.$_SESSION['tid'].'&&mid=NDA1&&tab=tab_1">';
    
}
?>

<?php
if(isset($_POST['No'])){
    
    $lid = $_GET['lide'];
    
	mysqli_query($link, "DELETE FROM loan_guarantor WHERE lid = '$lid'");
    mysqli_query($link, "DELETE FROM loan_info WHERE lid = '$lid'");
    
    echo "<div class='alert bg-blue'>Loan application cancelled successfully!</div>";
    echo '<meta http-equiv="refresh" content="5;url=newloans.php?id='.$_SESSION['tid'].'&&mid=NDA1&&tab=tab_1">';
    
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