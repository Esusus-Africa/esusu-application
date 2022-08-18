<?php include("include/header.php"); ?>
  <section class="invoice">
    <!-- Table row -->
    <section class="content">

      <!-- Default box -->
      <div class="box">

<?php
$lid = $_GET['lide'];
$act = $_GET['act'];
$select = mysqli_query($link, "SELECT * FROM wallet_loan_history WHERE lid = '$lid'") or die ("Error: " . mysqli_error($link));
$row = mysqli_fetch_array($select);
$baccount = $row['userid']; 
$currentLBal = $row['balance'];

$searchVAWN = mysqli_query($link, "SELECT * FROM virtual_account WHERE account_number = '$act' AND userid = '$baccount'");
$fetchVAWN = mysqli_fetch_array($searchVAWN);
$userid = $fetchVAWN['userid'];

$search_mystaff = mysqli_query($link, "SELECT * FROM user WHERE created_by = '$institution_id' AND id = '$userid'");
$sRowNum = mysqli_num_rows($search_mystaff);
$fetch_mystaff = mysqli_fetch_array($search_mystaff);

$search_borro = mysqli_query($link, "SELECT * FROM borrowers WHERE branchid = '$institution_id' AND account = '$userid'");
$bRowNum = mysqli_num_rows($search_borro);
$fetch_borro = mysqli_fetch_array($search_borro);

$userType = ($sRowNum == 0 && $bRowNum == 1) ? "Customer" : "User";
$userIde = ($sRowNum == 0 && $bRowNum == 1) ? $fetch_borro['id'] : $fetch_mystaff['userid'];
$userDob = ($sRowNum == 0 && $bRowNum == 1) ? $fetch_borro['dob'] : $fetch_mystaff['dob'];
$userPhone = ($sRowNum == 0 && $bRowNum == 1) ? $fetch_borro['phone'] : $fetch_mystaff['phone'];
$userLastName = ($sRowNum == 0 && $bRowNum == 1) ? $fetch_borro['lname'] : $fetch_mystaff['lname'];
$userVerifiedBVN = ($sRowNum == 0 && $bRowNum == 1) ? $fetch_borro['unumber'] : $fetch_mystaff['addr2'];
$defaultLBal = ($sRowNum == 0 && $bRowNum == 1) ? $fetch_borro['loan_balance'] : $fetch_mystaff['loan_balance'];
	
$search_user = mysqli_query($link, "SELECT * FROM user WHERE id = '$acct_officer' OR name = '$acct_officer'");
$fetch_user = mysqli_fetch_array($search_user);
?>
    
      <a href="loanHistory.php?id=<?php echo $_SESSION['tid']; ?>&&uid=<?php echo $baccount; ?>&&act=<?php echo $_GET['act']; ?>"><button type="button" class="btn btn-flat bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>"><i class="fa fa-mail-reply-all"></i>&nbsp;Back</button> </a>

      <div class="col-md-14">
            <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
              <li <?php echo ($_GET['tab'] == 'tab_1') ? "class='active'" : ''; ?>><a href="loanDetails.php?id=<?php echo $_GET['id']; ?>&&lide=<?php echo $_GET['lide']; ?>&&act=<?php echo $_GET['act']; ?>&&tab=tab_1">Loan Information</a></li>
              <li <?php echo ($_GET['tab'] == 'tab_2') ? "class='active'" : ''; ?>><a href="loanDetails.php?id=<?php echo $_GET['id']; ?>&&lide=<?php echo $_GET['lide']; ?>&&act=<?php echo $_GET['act']; ?>&&tab=tab_2">Guarantor Information</a></li>
              <li <?php echo ($_GET['tab'] == 'tab_3') ? "class='active'" : ''; ?>><a href="loanDetails.php?id=<?php echo $_GET['id']; ?>&&lide=<?php echo $_GET['lide']; ?>&&act=<?php echo $_GET['act']; ?>&&tab=tab_3">Repayment Schedule</a></li>
              <li <?php echo ($_GET['tab'] == "tab_4") ? "class='active'" : ''; ?>><a href="#">Loan Balance: <b><?php echo ($row['balance'] == "") ? $row['lcurrency']."0.0" : $row['lcurrency'].number_format($row['balance'],2,'.',','); ?></b></a></li>  
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
if(isset($_POST['bookLoan'])){

    $lid = $_GET['lide'];
    $mylid = mysqli_real_escape_string($link, $_POST['lid']);
    $mybvn = mysqli_real_escape_string($link, $_POST['cust_bvn']);
    $walletAcctNo = mysqli_real_escape_string($link, $_POST['walletAcctNo']);
    $amount = mysqli_real_escape_string($link, $_POST['amount']);
    $lproduct = mysqli_real_escape_string($link, $_POST['lproduct']);
    $income = mysqli_real_escape_string($link, $_POST['income']);
    $lreasons = mysqli_real_escape_string($link, $_POST['lreasons']);
    $repaymentMethod = mysqli_real_escape_string($link, $_POST['repaymentMethod']);
    $date_time = date("Y-m-d h:i:s");
    
    $selectLProduct = mysqli_query($link, "SELECT * FROM loan_product WHERE id = '$lproduct'");
    $fetchLProduct = mysqli_fetch_array($selectLProduct);
    $interestType = $fetchLProduct['interest_type'];
    $interestRate = $fetchLProduct['interest'];
    $tenor = $fetchLProduct['tenor'];
    $duration = $fetchLProduct['duration'];

    //Interest Calculation
    $interestAmt = ($interestType == "Flat Rate") ? (($interestRate/100) * $amount) : $amount;

    //Amount + Interest
    $amountToPay = ($interestType == "Flat Rate") ? ($amount + $interestAmt) : $amount;

    //Balance
    $myBalance = ($interestType == "Flat Rate") ? $amountToPay : $amount;

    /**
    foreach ($_FILES['uploaded_file']['name'] as $key => $name){
        
        $newFilename = $name;
        
        if($newFilename == "")
        {
            echo "";
        }
        else{
            $newlocation = 'document/'.$newFilename;
            if(move_uploaded_file($_FILES['uploaded_file']['tmp_name'][$key], '../application/document/'.$newFilename))
            {
                mysqli_query($link, "INSERT INTO attachment VALUES(null,'$userIde','$userid','$iuid','$newlocation',NOW())") or die (mysqli_error($link));
            }
        }
        
    }
     */
    
    ($userVerifiedBVN != "" && $userType == "User") ? mysqli_query($link, "UPDATE user SET addr2 = '$mybvn' WHERE userid = '$userIde'") : "";
    ($userVerifiedBVN != "" && $userType == "Customer") ? mysqli_query($link, "UPDATE borrowers SET unumber = '$mybvn' WHERE id = '$userIde'") : "";
    
    mysqli_query($link, "UPDATE wallet_loan_history SET userBvn = '$mybvn', tenor = '$tenor', duration = '$duration', lproduct = '$lproduct', interest_type = '$interestType', interest_rate = '$interestRate', loanAmount = '$amount', income = '$income', lreasons = '$lreasons', repaymentMethod = '$repaymentMethod' WHERE lid = '$lid'");
    mysqli_query($link, "UPDATE payment_schedule SET term = '$duration', schedule = '$tenor', lproduct = '$lproduct' WHERE lid = '$mylid'");

    echo "<div class='alert bg-blue'>Loan info updated successfully</div>";
    echo "<script>window.location='loanDetails.php?id=".$_SESSION['tid']."&&lide=".$_GET['lide']."&&act=".$_GET['act']."&&tab=tab_1'; </script>";

}
?>

            <div class="box-body">

            <input name="lid" type="hidden" class="form-control" value="<?php echo $lid; ?>">
            
            <div class="form-group">
                <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">BVN: <?php echo ($userVerifiedBVN != "" && strlen($userVerifiedBVN) == "11") ? '<span style="color: '.(($myaltrow['theme_color'] == "") ? "blue" : $myaltrow['theme_color']).'">[<b>Verified</b> <i class="fa fa-check"></i>]</span>' : '<span style="color: '.(($myaltrow['alternate_color'] == "") ? "orange" : $myaltrow['alternate_color']).'">[<b>Not-Verified</b> <i class="fa fa-times"></i>]</span>'; ?></label>
                <div class="col-sm-7">
                    <input name="cust_bvn" type="text" class="form-control" value="<?php echo $userVerifiedBVN; ?>" placeholder="BVN Number Here" maxlength="11" required>
                    <span style="color: <?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>;"> BVN Verification cost <b style="color: <?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;"><?php echo $icurrency.number_format($ibvn_fee,2,'.',','); ?></b>. </span>
                    <br>
                    <div class="scrollable">
                    <?php
                    if(isset($_POST['verifyBVN'])){
                        
                        $userBvn = mysqli_real_escape_string($link, $_POST['cust_bvn']);
                        
                        if($itransfer_balance < $ibvn_fee){
            
                            echo "<br><span class='bg-orange'>Sorry! No sufficient fund in your wallet for this verification</span>";
                            
                        }
                        elseif(strlen($userBvn) != 11){
                            
                            echo "<br><span>BVN Number not Valid....</span>";
                            
                        }
                        elseif($ibvn_route == "Wallet Africa"){
                            
                                require_once "../config/bvnVerification_class.php";
            
                                $processBVN = $newBV->walletAfricaBVNVerifier($walletafrica_skey,$userBvn,$link);
                                $ResponseCode = $processBVN['ResponseCode'];
                            
                                if($ResponseCode == "200"){
                                
                                    $icm_id = "ICM".time();
                                    $exp_id = "EXP".time();
                                    $myOtp = substr((uniqid(rand(),1)),3,6);
                                    $rOrderID = "EA-bvnCharges-".time();
                                
                                    $date_time = date("Y-m-d");
                                    $wallet_date_time = date("Y-m-d H:i:s");
                                    
                                    //Deduct fund
                                    $wbalance = $itransfer_balance - $ibvn_fee;
                                    $update_wallet = mysqli_query($link, "UPDATE user SET transfer_balance = '$wbalance' WHERE id = '$iuid'");

                                    //BVN Details
                                    $bvn_fname = $processBVN['FirstName'];
                                    $bvn_lname = $processBVN['LastName'];
                                    $bvn_mname = $processBVN['MiddleName'];
                                    $bvn_dob = $processBVN['DateOfBirth'];
                                    $bvn_phone = "+234".substr($processBVN['PhoneNumber'],-10);
                                    $correct_bvnPhone = $processBVN['PhoneNumber'];
                                    $bvn_email = $processBVN['Email'];
                                    $bvn_picture = $processBVN['Picture'];
                                    $dynamicStr = md5(date("Y-m-d h:i"));
                                    $image_converted = $newBV->base64_to_jpeg($bvn_picture, $dynamicStr.".png");
                                    $default_dob = date("d-M-Y", strtotime($userDob));

                                    //20 array row
                                    $mybvn_data = $processBVN['BVN']."|".$processBVN['FirstName']."|".$processBVN['LastName']."|".$processBVN['MiddleName']."|".$processBVN['DateOfBirth']."|".$processBVN['PhoneNumber']."|".$processBVN['Email']."|".$processBVN['EnrollmentBank']."|".$processBVN['EnrollmentBranch']."|".$processBVN['Gender']."|".$processBVN['LevelOfAccount']."|".$processBVN['LgaOfOrigin']."|".$processBVN['LgaOfResidence']."|".$processBVN['MaritalStatus']."|".$processBVN['NameOnCard']."|".$processBVN['Nationality']."|".$processBVN['StateOfOrigin']."|".$processBVN['StateOfResidence']."|".$processBVN['Title']."|".$processBVN['WatchListed']."|".$image_converted;
                                    
                                    $search_bvnverify = mysqli_query($link, "SELECT * FROM bvn_log WHERE accountID = '$userid'");
                                    $fetch_bvnverify = mysqli_fetch_array($search_bvnverify);
                                    $bvn_nos = mysqli_num_rows($search_bvnverify);
                                    $concat = $fetch_bvnverify['mydata'];
                                    $parameter = (explode('|',$concat));
                                    $old_picture = $parameter[20];
                                    
                                    $seach_membersttings = mysqli_query($link, "SELECT * FROM member_settings WHERE companyid = '$institution_id'");
                                    $fetch_memset = mysqli_fetch_array($seach_membersttings);
                                    
                                    ($bvn_nos == 1 && $old_picture != "") ? unlink("../img/".$old_picture) : "";
                                    
                                    ($bvn_nos == 1) ? $update_bvn = mysqli_query($link, "UPDATE bvn_log SET mydata = '$mybvn_data' WHERE accountID = '$userid'") : $insert_bvn = mysqli_query($link, "INSERT INTO bvn_log VALUE(null,'$institution_id','$isbranchid','$userid','$iuid','$mybvn_data','$ibvn_fee','$wallet_date_time','$rOrderID')");
                                    
                                    $insert_income = mysqli_query($link, "INSERT INTO wallet_history VALUE(null,'$institution_id','$rOrderID','$userBvn','','$ibvn_fee','Debit','$icurrency','BVN_Charges','Response: $icurrency.$ibvn_fee was charged for Customer BVN Verification','successful','$wallet_date_time','$iuid','$wbalance','')");
                                    
                                    $insert_income = mysqli_query($link, "INSERT INTO income VALUES(null,'','$icm_id','BVN','$ibvn_fee','$date_time','$userType BVN Verification Charges')");
                                    
                                    ($userType == "Customer" && $bvn_lname == $userLastName && ($bvn_dob == $default_dob || $bvn_phone == $userPhone || $userPhone == $correct_bvnPhone)) ? mysqli_query($link, "UPDATE borrowers SET unumber = '$userBvn', status = 'Verified' WHERE id = '$userIde'") : "";
                                    ($userType == "User" && $bvn_lname == $userLastName && ($bvn_dob == $default_dob || $bvn_phone == $userPhone || $userPhone == $correct_bvnPhone)) ? mysqli_query($link, "UPDATE user SET addr2 = '$userBvn', status = 'Verified' WHERE userid = '$userIde'") : "";
                                    
                                    echo ($bvn_lname == $userLastName && ($bvn_dob == $default_dob || $bvn_phone == $userPhone || $userPhone == $correct_bvnPhone)) ? '<p style="color: blue;"><b>Data Verified Successfully!</b> <i class="fa fa-check"></i></p>' : '<p style="color: orange;"><b>Data Verification Not Successful! Please try with correct BVN that Match your Information on our System</b> <i class="fa fa-times"></i></p>';

                            }
                            else{
                                echo "<br><span class='bg-orange'>Oops! Network Error, please try again later </span>";
                            }
                            
                        }
                        else{
                            
                            //empty
                            echo "Sorry! Service not available at the moment, please try again later!!";
                            
                        }
                        
                    }
                    ?>
                    </div>
                </div>
                <label for="" class="col-sm-3 control-label"><button type="submit" class="btn bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?> btn-flat" name="verifyBVN"><i class="fa fa-eye">&nbsp;Verify</i></button></label>
            </div>
            
            <div class="form-group">
                <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Loan Product</label>
				 <div class="col-sm-10">
                <select name="lproduct" class="form-control select2" style="width: 100%;" required>
                    <?php
                    $pid = $row['lproduct'];
                    $selectProduct = mysqli_query($link, "SELECT * FROM loan_product WHERE id = '$pid'");
                    $fetchLP = mysqli_fetch_array($selectProduct);
                    ?>
				    <option value="<?php echo ($lid == "") ? '' : $fetchLP['id']; ?>" selected="selected"><?php echo ($lid == "") ? '--Select Loan Product--' : $fetchLP['pname'].' - (Interest Rate: '.$fetchLP['interest'].'% for '.$fetchLP['duration'].' '.(($fetchLP['tenor'] == "Weekly") ? "Week(s)" : "Month(s)"); ?></option>
	                <?php
					$getin = mysqli_query($link, "SELECT * FROM loan_product WHERE (merchantid = '$institution_id' AND category = 'Individual') OR (merchantid != '$institution_id' AND category = 'Individual' AND visibility = 'Yes' AND authorize = '1') ORDER BY id") or die (mysqli_error($link));
					while($rowin = mysqli_fetch_array($getin))
					{
					echo '<option value="'.$rowin['id'].'">'.$rowin['pname'].' - '.'(Interest Rate: '. $rowin['interest'].'% based on tenor)'.'</option>';
					}
					?>
                </select>
              </div>
			 </div>
			 
			 <div class="form-group">
                <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Amount:</label>
                <div class="col-sm-10">
                    <input name="amount" type="text" class="form-control" value="<?php echo $row['loanAmount']; ?>" placeholder="Loan Amount" required>
                </div>
            </div>

                <hr>
                  <table id="example1" class="table table-bordered table-striped">
                    <thead>
                    <tr>
                    <th><input type="checkbox" id="select_all"/></th>
                    <th>File Title</th>
                    <th>File</th>
                    <th>Status</th>
                    <th>Date/Time</th>
                    </tr>
                    </thead>
                    <tbody>
    <?php
    $selectAttachment = mysqli_query($link, "SELECT * FROM attachment WHERE borrowerid = '$userid' ORDER BY id DESC") or die (mysqli_error($link));
    if(mysqli_num_rows($selectAttachment)==0)
    {
    echo "<div class='alert bg-".(($myaltrow['theme_color'] == "") ? 'blue' : $myaltrow['theme_color'])."'>No data found yet!.....Check back later!!</div>";
    }
    else{
    while($rowAttachment = mysqli_fetch_array($select))
    {
    $id = $rowAttachment['id'];
    ?>    
                    <tr>
                        <td><input id="optionsCheckbox" class="checkbox" name="selector[]" type="checkbox" value="<?php echo $id; ?>"></td>
                        <td><?php echo $rowAttachment['file_title']; ?></td>
                        <td><a href="../img/<?php echo $rowAttachment['attached_file']; ?>" class="btn bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>" target="_blank"><i class="fa fa-eyes"></i> View Attachment</a></td>
                        <td><?php echo ($rowAttachment['fstatus'] == "Approved" ? "<span class='label bg-blue'><i class='fa fa-check'></i> ".$rowAttachment['fstatus']."</span>" : ($rowAttachment['fstatus'] == "Pending" ? "<span class='label bg-orange'><i class='fa fa-exclamation'></i> ".$rowAttachment['fstatus']."</span>" : "<span class='label bg-red'><i class='fa fa-times'></i> ".$rowAttachment['fstatus']."</span>")); ?></td>
                        <td><?php echo $rowAttachment['date_time']; ?></td>
                        
                    </tr>
    <?php } } ?>
                    </tbody>
                </table>
                  <hr>

            
            <div class="form-group">
                <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Reasons</label>
                <div class="col-sm-10">
				<select name="lreasons" class="form-control select2" style="width: 100%;" required>
				    <option value="<?php echo ($lid == '') ? '' : $row['lreasons']; ?>" selected="selected"><?php echo ($lid == '') ? '--Select Loan Type--' : $row['lreasons']; ?></option>
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
				<span style="color: <?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>;"><b>TELL US WHY THE LOAN IS NEEDED!!</b></span>
          		</div>
			</div>

            <div class="form-group">
                <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Account Number:</label>
                <div class="col-sm-10">
                    <input name="walletAcctNo" type="text" class="form-control" value="<?php echo $act; ?>" placeholder="Wallet Account Number" readonly>
                </div>
            </div>

            <div class="form-group">
                <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Account Name:</label>
                <div class="col-sm-10">
                    <input name="walletAcctName" type="text" class="form-control" value="<?php echo $fetchVAWN['account_name']; ?>" placeholder="Wallet Account Name" readonly>
                </div>
            </div>

            <div class="form-group">
                <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Income Amount</label>
                <div class="col-sm-10">
                <input name="income" type="text" class="form-control" value="<?php echo ($lid == '') ? '' : $row['income']; ?>" placeholder="Enter Your Average Income Amount" required>
                </div>
                </div>
                
            <div class="form-group">
                <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Repayment Method</label>
                <div class="col-sm-10">
				<select name="repaymentMethod" class="form-control select2" style="width: 100%;" required>
				    <option value="<?php echo ($lid == '') ? '' : $row['repaymentMethod']; ?>" selected="selected"><?php echo ($lid == '') ? '--Select Repayment Method--' : $row['repaymentMethod']; ?></option>
	                <option value="Wallet">Wallet</option>
	                <option value="Others">Others</option>
                </select>
          		</div>
			</div>

			</div>
			
			<?php
			if($review_wallet_loan == '1'){
			?>
			 
			<div class="form-group" align="right">
                <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;"></label>
                <div class="col-sm-10">
                	<button name="bookLoan" type="submit" class="btn bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>"><i class="fa fa-link">&nbsp;Submit</i></button>
                </div>
            </div>
            
            <?php
			}else{
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
			    
			    <div class="box-body">
			        
<?php
if(isset($_POST['addComment'])){
    
    $lid = $_GET['lide'];
    $gstatus = mysqli_real_escape_string($link, $_POST['gstatus']);
    $gcoment = mysqli_real_escape_string($link, $_POST['gcoment']);
	
	mysqli_query($link, "UPDATE loan_guarantor SET gcomment = '$gcoment', gstatus = '$gstatus' WHERE lid = '$lid'");
    
    echo "<div class='alert bg-blue'>Guarantor $gstatus Successfully!!</div>";
    echo '<meta http-equiv="refresh" content="5;url=loanDetails.php?id='.$_SESSION['tid'].'&&lide='.$lid.'&&tab=tab_2">';
    
}
?>
    			   
    			 <?php
    			 if(isset($_GET['rej']) || isset($_GET['accpt'])){
    			 ?>
    			 
    			 <div class="box-body">
    			     
    			    <input name="gstatus" type="hidden" class="form-control" value="<?php echo (isset($_GET['rej'])) ? 'Rejected' : 'Accepted'; ?>">
    				 
    				<div class="form-group">
                      	<label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Comment</label>
                      	<div class="col-sm-10">
    					<textarea name="gcoment" class="form-control" rows="4" cols="80" required></textarea>
               			 </div>
              	    </div> 
              	    
              	</div>
			 
    			<div class="form-group" align="right">
                    <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;"></label>
                    <div class="col-sm-7">
                    </div>
                    <label for="" class="col-sm-3 control-label"><button name="addComment" type="submit" class="btn bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>"><i class="fa fa-save">&nbsp;Submit</i></button></label>
                </div>
    			 
    			 <?php
    			 }
    			 else{
    			     echo "";
    			 }
    			 ?>
			        
			    <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th><input type="checkbox" id="select_all"/></th>
                  <th>Action</th>
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
                    <td>
                        <div class='btn-group'>
                            <div class='btn-group'>
                            <button type='button' class='btn bg-blue btn-flat dropdown-toggle' data-toggle='dropdown'>
                            <span class='caret'></span>
                            </button>
                            <ul class='dropdown-menu'>
                                <?php echo ($accept_wallet_loan_guarantor == "1" && ($row['gstatus'] == "Pending" || $row['gstatus'] == "Rejected")) ? "<li><p><a href='bookLoan.php?id=".$_SESSION['tid']."&&accpt=".$_GET['lide']."&&uid=".$acct_owner."&&tab=tab_2' class='btn btn-default btn-flat'><i class='fa fa-check'> Accept</i></a></p></li>" : "---"; ?>
                                <?php echo ($reject_wallet_loan_guarantor == "1" && ($row['gstatus'] == "Pending" || $row['gstatus'] == "Accepted")) ? "<li><p><a href='bookLoan.php?id=".$_SESSION['tid']."&&rej=".$_GET['lide']."&&uid=".$acct_owner."&&tab=tab_2' class='btn btn-default btn-flat'><i class='fa fa-times'> Reject</i></a></p></li>" : "---"; ?>
                            </ul>
                            </div>
                        </div>
                    </td>
                    <td><a href="../<?php echo $row['picture']; ?>" class="btn bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>" target="blank"><i class="fa fa-search"></i> View Picture</a></td>
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
			        
			    </div>
			    
			</form>
	
	
	
	
	<?php
	}
	elseif($tab == 'tab_3')
	{
	?>

            <div class="tab-pane <?php echo ($_GET['tab'] == 'tab_3') ? 'active' : ''; ?>" id="tab_3">
			<form class="form-horizontal" method="post" enctype="multipart/form-data">
			    <div align="center"><h3><b>Loan Repayment Schedule</b></h3></div>
			<div class="box-body">

            <?php
            $lid = $_GET['lide'];
            $searchin = mysqli_query($link, "SELECT * FROM payment_schedule WHERE lid = '$lid'") or die ("Error: " . mysqli_error($link));
            $haveit = mysqli_fetch_array($searchin);
            $idmet= $haveit['id'];
            $iintr_type = $row['interest_type'];
            ?>

				<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">Duration Type:</label>
                  <div class="col-sm-8">
				  <select name="d1" class="form-control" readonly>
				    <option value="<?php echo $haveit['term']; ?>"><?php echo $haveit['schedule']; ?></option>
				  </select>
                  </div>
                  <label for="" class="col-sm-4 control-label"></label>
                  </div>
                  
                 <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">Interest Type:</label>
                  <div class="col-sm-8">
				  <select name="intr_type" class="form-control" readonly>
				    <option value="<?php echo $iintr_type; ?>"><?php echo $iintr_type; ?></option>
				  </select>
                  </div>
                  <label for="" class="col-sm-4 control-label"></label>
                  </div>
                  
                <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">Status:</label>
                  <div class="col-sm-8">
				  <select name="lstatus" class="form-control select2" required>
				    <option value="<?php echo $row['lstatus']; ?>"><?php echo $row['lstatus']; ?></option>
				    <?php echo ($review_wallet_loan == '1') ? '<option value="Approved">Approve</option>' : ''; ?>
				    <?php echo ($review_wallet_loan == '1') ? '<option value="Declined">Decline</option>' : ''; ?>
				  </select>
                  </div>
                  <label for="" class="col-sm-4 control-label"></label>
                </div>
				  
				  <input name="schedule" type="hidden" value="<?php echo $haveit['schedule']; ?>" class="form-control">
				  
				   <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">Schedules:</label>
                  <div class="col-sm-10">
				<table>
                <tbody> 
<?php
$searchin = mysqli_query($link, "SELECT * FROM wallet_pay_schedule WHERE lid = '$lid' ORDER BY id ASC") or die (mysqli_error($link));
while($haveit = mysqli_fetch_array($searchin))
{
$idmet= $haveit['id'];
?>			
				<tr>
        			<td><input id="optionsCheckbox" class="uniform_on" name="selector[]" type="hidden" value="<?php echo $idmet; ?>" checked></td>
                    <td width="400"><input name="schedulek[]" type="date" class="form-control pull-right" placeholder="Schedule" value="<?php echo $haveit['schedule_date']; ?>"></td>
                    <td width="300"><input name="balance[]" type="text" class="form-control" placeholder="Balance" value="<?php echo $haveit['balance']; ?>"></td>
        			<td width="130"><input name="payment[]" type="text" class="form-control" placeholder="Payment" value="<?php echo number_format($haveit['amount_topay'],2,'.',''); ?>"></td>
			    </tr>
<?php } ?>

<?php
$searchliin = mysqli_query($link, "SELECT SUM(amount_topay) FROM wallet_pay_schedule WHERE lid = '$lid'") or die (mysqli_error($link));
$fetchliin = mysqli_fetch_array($searchliin);
$overall_payment = $fetchliin['SUM(amount_topay)'];
?>
                <tr>
        			<td></td>
                    <td width="400"></td>
                    <td width="300"></td>
        			<td width="130"><b><?php echo number_format($overall_payment,2,'.',','); ?></b></td>
        			<td width="100"></td>
			    </tr>
				</tbody>
                </table>
                
<hr>
<?php
$id = $_GET['id'];
$searchin2 = mysqli_query($link, "SELECT * FROM wallet_pay_schedule WHERE lid = '$lid' AND (balance = '0' OR balance <= '0')") or die (mysqli_error($link));
$numit = mysqli_num_rows($searchin2);
?>
<?php echo ($numit == 1) ? "<span class='label bg-".(($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color'])."'>Completed</span>" : "<span class='label bg-".(($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color'])."'>Progressing...(Schedule not completed yet).</span>"; ?>
<hr>
<div align="left">
              <div class="box-footer">

                <?php
                    $comfirm_select = mysqli_query($link, "SELECT * FROM wallet_loan_history WHERE lid = '$lid'") or die (mysqli_error($link));
                    $comfirm_row = mysqli_fetch_array($comfirm_select);
                    if($comfirm_row['lstatus'] == "Approved" && ($review_wallet_loan == '1' || $review_wallet_loan == '')){
                        echo "";
                    }elseif($review_wallet_loan == '' && ($comfirm_row['lstatus'] == "Pending" || $comfirm_row['lstatus'] == "Approved" || $comfirm_row['lstatus'] == "Declined")){
                        echo "";
                    }else{
                ?>
                    <button type="submit" class="btn bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>" name="add_sch_rows" <?php echo ($numit == 1) ? 'disabled' : ''; ?>><i class="fa fa-plus">&nbsp;Generate Schedule</i></button>
                    <button name="delrow2" type="submit" class="btn bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>"><i class="fa fa-trash">&nbsp;Delete Schedule</i></button>
                    <button type="submit" class="btn bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>" name="add_pay_schedule"><i class="fa fa-save">&nbsp;Save</i></button>
                <?php
                    }
                ?>
                <?php
                    $comfirm_select = mysqli_query($link, "SELECT * FROM wallet_loan_history WHERE lid = '$lid'") or die (mysqli_error($link));
                    $comfirm_row = mysqli_fetch_array($comfirm_select);
                    if($comfirm_row['lstatus'] == "Approved" && ($review_wallet_loan == '1' || $review_wallet_loan == '')){
                ?>
                    <button type="submit" class="btn bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>" name="generate_pdf"><i class="fa fa-print">&nbsp;Print Schedule!</i></button>
                <?php
                    }
                    else{
                        echo "";
                    }
                ?>
              </div>
			  </div>
   <?php
						if(isset($_POST['delrow2'])){
						$idm = $_GET['id'];
							$lid = $_GET['lide'];
							$id=$_POST['selector'];
							$N = count($id);
						if($id == ''){
						echo "<script>alert('Row Not Selected!!!'); </script>";	
						echo "<script>window.location='loanDetails.php?id=".$_SESSION['tid']."&&lide=".$_GET['lide']."&&tab=tab_3'; </script>";
						}
						else{
							for($i=0; $i < $N; $i++)
							{
							    $update_interest_calc = mysqli_query($link, "DELETE FROM interest_calculator WHERE lid = '$lid'");
								$result = mysqli_query($link,"DELETE FROM wallet_pay_schedule WHERE id ='$id[$i]'");
								echo "<script>window.location='loanDetails.php?id=".$_SESSION['tid']."&&lide=".$_GET['lide']."&&tab=tab_3'; </script>";
							}
						}
						}
?>

<?php
if(isset($_POST['add_sch_rows']))
{
    $lid = $_GET['lide'];

    $day = mysqli_real_escape_string($link, $_POST['d1']);
    $intr_type = mysqli_real_escape_string($link, $_POST['intr_type']);
    $schedule_of_paymt = mysqli_real_escape_string($link, $_POST['schedule']);

    $N = $day;

    $process_data2 = mysqli_query($link, "SELECT * FROM wallet_loan_history WHERE lid = '$lid'") or die (mysqli_error($link));
    $fetch = mysqli_fetch_array($process_data2);
    $totalamount_topay = $fetch['amount_topay'];
    $baccount = $fetch['borrowerid'];
    $amount_borrowed = $fetch['loanAmount'];
    $duration = $fetch['duration'];
    $interest = $fetch['interest_rate'] / 100;

    $amt_topay_per_duration = $amount_borrowed / $duration;

    $new_interest = ($interest * $amount_borrowed) + $amt_topay_per_duration;

    $calc_myint = ($amount_borrowed / $duration) + (($amount_borrowed / $duration) * $interest);

    $int_rate = ($intr_type == "Flat Rate") ? ($totalamount_topay / $duration) : $new_interest;

    $first_balance = ($intr_type == "Flat Rate") ? number_format(($totalamount_topay - $int_rate),0,'.','') : number_format(($amount_borrowed - $amt_topay_per_duration),0,'.','');

    $verify_data = mysqli_query($link, "SELECT * FROM wallet_pay_schedule WHERE lid = '$lid'") or die (mysqli_error($link));
    $verify_data2 = mysqli_query($link, "SELECT * FROM interest_calculator WHERE lid = '$lid'") or die (mysqli_error($link));
    if((mysqli_num_rows($verify_data) == 0) && (mysqli_num_rows($verify_data2) == 0) && ($schedule_of_paymt == "Monthly") && ($intr_type == "Flat Rate"))
    {
        $insert = mysqli_query($link, "INSERT INTO interest_calculator VALUES(null,'$lid','$first_balance','$int_rate','$duration')") or die (mysqli_error($link));
        
        $cur_date = strtotime($fetch['date_time']);
        $lastDayThisMonth = date("Y-m-d", strtotime("+1 month", $cur_date));
        
        
        $insert = mysqli_query($link, "INSERT INTO wallet_pay_schedule VALUES(null,'$lid','$baccount','$lastDayThisMonth','$int_rate','$first_balance','$institution_id','$isbranchid','$iuid','Pending')") or die (mysqli_error($link));
                
        for($i = 1; $i < $N; $i++) {
            
            //CONFIRMATION OF INTEREST RATE
            $select_int_calculator = mysqli_query($link, "SELECT * FROM interest_calculator WHERE lid = '$lid'") or die (mysqli_error($link));
            $get_calculator = mysqli_fetch_array($select_int_calculator);
            $balance = $get_calculator['amt_to_pay'];
            $lrate = $get_calculator['int_rate'];
            $new_balance = number_format(($balance - $lrate),0,'.','');
            
            $verify_data1 = mysqli_query($link, "SELECT * FROM wallet_pay_schedule WHERE lid = '$lid' ORDER BY id DESC") or die (mysqli_error($link));
            $fetch_data1 = mysqli_fetch_array($verify_data1);
            $my_schedule = strtotime($fetch_data1['schedule_date']);
            $lastDayThisMonth2 = date("Y-m-d", strtotime("+1 month", $my_schedule));
            
            $insert = mysqli_query($link, "INSERT INTO wallet_pay_schedule VALUES(null,'$lid','$baccount','$lastDayThisMonth2','$int_rate','$new_balance','$institution_id','$isbranchid','$iuid','Pending')") or die (mysqli_error($link));
            $update_calculator = mysqli_query($link, "UPDATE interest_calculator SET amt_to_pay = '$new_balance' WHERE lid = '$lid'") or die (mysqli_error($link));
            
        }
        echo "<script>window.location='loanDetails.php?id=".$_SESSION['tid']."&&lide=".$_GET['lide']."&&tab=tab_2'; </script>";
    }
    elseif((mysqli_num_rows($verify_data) == 0) && (mysqli_num_rows($verify_data2) == 0) && ($schedule_of_paymt == "Monthly") && ($intr_type != "Flat Rate"))
    {
        $insert = mysqli_query($link, "INSERT INTO interest_calculator VALUES(null,'$lid','$first_balance','$amt_topay_per_duration','$duration')") or die (mysqli_error($link));
        
        $cur_date = strtotime($fetch['date_time']);
        $lastDayThisMonth = date("Y-m-d", strtotime("+1 month", $cur_date));
        
        $insert = mysqli_query($link, "INSERT INTO wallet_pay_schedule VALUES(null,'$lid','$baccount','$lastDayThisMonth','$int_rate','$first_balance','$institution_id','$isbranchid','$iuid','Pending')") or die (mysqli_error($link));
        
        for($i = 1; $i < $N; ++$i) {
                    
            //CONFIRMATION OF INTEREST RATE
            $select_int_calculator = mysqli_query($link, "SELECT * FROM interest_calculator WHERE lid = '$lid'") or die (mysqli_error($link));
            $get_calculator = mysqli_fetch_array($select_int_calculator);
            $balance = $get_calculator['amt_to_pay'];
            $myduration = $get_calculator['duration'] - 1;
            $divided_amt = $get_calculator['int_rate'];
            $next_amt_topay = ($interest * $balance) + $divided_amt;
            $new_balance = ($myduration <= 1) ? '0' : number_format(($balance - $divided_amt),0,'.','');
                    
            $verify_data1 = mysqli_query($link, "SELECT * FROM wallet_pay_schedule WHERE lid = '$lid' ORDER BY id DESC") or die (mysqli_error($link));
            $fetch_data1 = mysqli_fetch_array($verify_data1);
            $my_schedule = strtotime($fetch_data1['schedule_date']);
            $lastDayThisMonth2 = date("Y-m-d", strtotime("+1 month", $my_schedule));
            
            $insert = mysqli_query($link, "INSERT INTO wallet_pay_schedule VALUES(null,'$lid','$baccount','$lastDayThisMonth2','$next_amt_topay','$new_balance','$institution_id','$isbranchid','$iuid','Pending')") or die (mysqli_error($link));
            $update_calculator = mysqli_query($link, "UPDATE interest_calculator SET amt_to_pay = '$new_balance', duration = '$myduration' WHERE lid = '$lid'") or die (mysqli_error($link));
            
        }
        $verify_repaysum = mysqli_query($link, "SELECT SUM(amount_topay) FROM wallet_pay_schedule WHERE lid = '$lid'");
        $fetch_repaysum = mysqli_fetch_array($verify_repaysum);
        $total_repaysum = number_format($fetch_repaysum['SUM(amount_topay)'],2,'.','');
        mysqli_query($link, "UPDATE wallet_loan_history SET amount_topay = '$total_repaysum', balance = '$total_repaysum' WHERE lid = '$lid'");
        echo "<script>window.location='loanDetails.php?id=".$_SESSION['tid']."&&lide=".$_GET['lide']."&&tab=tab_2'; </script>";
    }
    elseif((mysqli_num_rows($verify_data) == 0) && (mysqli_num_rows($verify_data2) == 0) && ($schedule_of_paymt == "Weekly") && ($intr_type == "Flat Rate")) {
        
        $insert = mysqli_query($link, "INSERT INTO interest_calculator VALUES(null,'$lid','$first_balance','$int_rate','$duration')") or die (mysqli_error($link));
        
        $cur_date = strtotime($fetch['date_time']);
        $lastDayThisMonth = date("Y-m-d", strtotime("+1 week", $cur_date));
        
        $insert = mysqli_query($link, "INSERT INTO wallet_pay_schedule VALUES(null,'$lid','$baccount','$lastDayThisMonth','$int_rate','$first_balance','$institution_id','$isbranchid','$iuid','Pending')") or die (mysqli_error($link));
                
        for($i = 1; $i < $N; $i++) {
                    
            //CONFIRMATION OF INTEREST RATE
            $select_int_calculator = mysqli_query($link, "SELECT * FROM interest_calculator WHERE lid = '$lid'") or die (mysqli_error($link));
            $get_calculator = mysqli_fetch_array($select_int_calculator);
            $balance = $get_calculator['amt_to_pay'];
            $lrate = $get_calculator['int_rate'];
            $new_balance = number_format(($balance - $lrate),0,'.','');
                    
            $verify_data1 = mysqli_query($link, "SELECT * FROM wallet_pay_schedule WHERE lid = '$lid' ORDER BY id DESC") or die (mysqli_error($link));
            $fetch_data1 = mysqli_fetch_array($verify_data1);
            $my_schedule = strtotime($fetch_data1['schedule_date']);
            $lastDayThisMonth2 = date("Y-m-d", strtotime("+1 week", $my_schedule));
            
            $insert = mysqli_query($link, "INSERT INTO wallet_pay_schedule VALUES(null,'$lid','$baccount','$lastDayThisMonth2','$int_rate','$new_balance','$institution_id','$isbranchid','$iuid','Pending')") or die (mysqli_error($link));
            $update_calculator = mysqli_query($link, "UPDATE interest_calculator SET amt_to_pay = '$new_balance' WHERE lid = '$lid'") or die (mysqli_error($link));
                    
        }
        echo "<script>window.location='loanDetails.php?id=".$_SESSION['tid']."&&lide=".$_GET['lide']."&&tab=tab_2'; </script>";
    
    }
    elseif((mysqli_num_rows($verify_data) == 0) && (mysqli_num_rows($verify_data2) == 0) && ($schedule_of_paymt == "Weekly") && ($intr_type != "Flat Rate")) {
        
        $insert = mysqli_query($link, "INSERT INTO interest_calculator VALUES(null,'$lid','$first_balance','$amt_topay_per_duration','$duration')") or die (mysqli_error($link));
        
        $cur_date = strtotime($fetch['date_time']);
        $lastDayThisMonth = date("Y-m-d", strtotime("+1 week", $cur_date));
        
        $insert = mysqli_query($link, "INSERT INTO wallet_pay_schedule VALUES(null,'$lid','$baccount','$lastDayThisMonth','$int_rate','$first_balance','$institution_id','$isbranchid','$iuid','Pending')") or die (mysqli_error($link));
        
        for($i = 1; $i < $N; ++$i) {
            
            //CONFIRMATION OF INTEREST RATE
            $select_int_calculator = mysqli_query($link, "SELECT * FROM interest_calculator WHERE lid = '$lid'") or die (mysqli_error($link));
            $get_calculator = mysqli_fetch_array($select_int_calculator);
            $balance = $get_calculator['amt_to_pay'];
            $myduration = $get_calculator['duration'] - 1;
            $divided_amt = $get_calculator['int_rate'];
            $next_amt_topay = ($interest * $balance) + $divided_amt;
            $new_balance = ($myduration == 1) ? '0' : number_format(($balance - $divided_amt),0,'.','');
            
            $verify_data1 = mysqli_query($link, "SELECT * FROM wallet_pay_schedule WHERE lid = '$lid' ORDER BY id DESC") or die (mysqli_error($link));
            $fetch_data1 = mysqli_fetch_array($verify_data1);
            $my_schedule = strtotime($fetch_data1['schedule_date']);
            $lastDayThisMonth2 = date("Y-m-d", strtotime("+1 week", $my_schedule));
            
            $insert = mysqli_query($link, "INSERT INTO wallet_pay_schedule VALUES(null,'$lid','$baccount','$lastDayThisMonth2','$next_amt_topay','$new_balance','$institution_id','$isbranchid','$iuid','Pending')") or die (mysqli_error($link));
            $update_calculator = mysqli_query($link, "UPDATE interest_calculator SET amt_to_pay = '$new_balance', duration = '$myduration' WHERE lid = '$lid'") or die (mysqli_error($link));
            
        }
        $verify_repaysum = mysqli_query($link, "SELECT SUM(amount_topay) FROM wallet_pay_schedule WHERE lid = '$lid'");
        $fetch_repaysum = mysqli_fetch_array($verify_repaysum);
        $total_repaysum = number_format($fetch_repaysum['SUM(amount_topay)'],2,'.','');
        mysqli_query($link, "UPDATE wallet_loan_history SET amount_topay = '$total_repaysum', balance = '$total_repaysum' WHERE lid = '$lid'");
        echo "<script>window.location='loanDetails.php?id=".$_SESSION['tid']."&&lide=".$_GET['lide']."&&tab=tab_2'; </script>";
    
    }
}
?>
                  </div>
                  </div>
				  
              </div>

<?php
if(isset($_POST['add_pay_schedule']))
{
$idm = $_GET['id'];
$lid = $_GET['lide'];
$id= $_POST['selector'];
$lstatus = mysqli_real_escape_string($link, $_POST['lstatus']);
//$N = count($id);
$newBal = $currentLBal + $defaultLBal;
($userType == "Customer" && $lstatus == "Approved") ? mysqli_query($link, "UPDATE borrowers SET loan_balance = '$newBal' WHERE account = '$userid'") : "";
($userType == "User" && $lstatus == "Approved") ? mysqli_query($link, "UPDATE user SET loan_balance = '$newBal' WHERE id = '$userid'") : "";
	
$i = 0;
$tid = $_SESSION['tid'];
$acn = $_GET['acn'];
foreach($_POST['selector'] as $s)
{
    
	$schedule = mysqli_real_escape_string($link, $_POST['schedulek'][$i]);
	$update = mysqli_query($link, "UPDATE wallet_pay_schedule SET schedule_date = '$schedule' WHERE id = '$s'") or die (mysqli_error($link));
	$i++;
	$insert = mysqli_query($link, "UPDATE wallet_loan_history SET lstatus = '$lstatus' WHERE lid = '$lid'") or die (mysqli_error($link));
	if(!($update && $insert))
	{
	    echo "<script>alert('Record not inserted.....Please try again later!'); </script>";
	}
	else{
	    echo "<script>alert('Payment Scheduled Successfully!!'); </script>";
	    echo "<script>window.location='loanDetails.php?id=".$_SESSION['tid']."&&lide=".$_GET['lide']."&&tab=tab_2'; </script>";
	}
	
}
}
?>

<?php
if(isset($_POST['generate_pdf']))
{
	//echo "<script>window.open('../pdf/view/pdf_payschedule2.php?id=".$_SESSION['tid']."&&lide=".$_GET['lide']."&&instid=".$institution_id."', '_blank'); </script>";
}
?>
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
<div align="center"><?php include("include/footer.php"); ?></div>
    </section>
    <!-- /.content -->
