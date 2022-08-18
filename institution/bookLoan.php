<?php include("include/header.php"); ?>

<section class="invoice">
    <!-- Table row -->
    <section class="content">

      <!-- Default box -->
      <div class="box">

<?php
$acct_owner = $_GET['uid'];
$act = $_GET['act'];
$searchVAWN = mysqli_query($link, "SELECT * FROM virtual_account WHERE account_number = '$act' AND userid = '$acct_owner'");
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
$pendingLBal = ($sRowNum == 0 && $bRowNum == 1) ? $fetch_borro['loan_balance'] : $fetch_mystaff['loan_balance'];

$lide = $_GET['lide'];
?>


    <a href="loanHistory.php?id=<?php echo $_SESSION['tid']; ?>&&uid=<?php echo $acct_owner; ?>&&act=<?php echo $act; ?>"><button type="button" class="btn btn-flat bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>"><i class="fa fa-mail-reply-all"></i>&nbsp;Back</button> </a>

      <div class="col-md-14">
            <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
              <li <?php echo ($_GET['tab'] == 'tab_1') ? "class='active'" : ''; ?>><a href="bookLoan.php?id=<?php echo $_SESSION['tid']; ?>&&uid=<?php echo $acct_owner; ?>&&act=<?php echo $act; ?>&&lide=<?php echo $lide; ?>&&tab=tab_1">Loan Information</a></li>
              <li <?php echo ($_GET['tab'] == 'tab_2') ? "class='active'" : ''; ?>><a href="<?php echo ($lide == "") ? '#' : 'bookLoan.php?id='.$_SESSION['tid'].'&&uid='.$acct_owner.'&&act='.$act.'&&lide='.$lide.'&&tab=tab_2'; ?>">Add Guarantor</a></li>
              <li <?php echo ($_GET['tab'] == 'tab_3') ? "class='active'" : ''; ?>><a href="<?php echo ($lide == "") ? '#' : 'bookLoan.php?id='.$_SESSION['tid'].'&&uid='.$acct_owner.'&&act='.$act.'&&lide='.$lide.'&&tab=tab_3'; ?>">Confirmation</a></li>
              <li <?php echo ($_GET['tab'] == "tab_4") ? "class='active'" : ''; ?>><a href="#">Loan Balance: <b><?php echo ($pendingLBal == "") ? $icurrency."0.0" : $icurrency.number_format($pendingLBal,2,'.',','); ?></b></a></li>  
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

    $lid = 'wLID-'.date("dY").uniqid();
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
                mysqli_query($link, "INSERT INTO attachment VALUES(null,'$userIde','$userid','$iuid','$newlocation','ValidID','Pending',NOW())") or die (mysqli_error($link));
            }
        }
        
    }
    
    ($userVerifiedBVN != "" && $userType == "User") ? mysqli_query($link, "UPDATE user SET addr2 = '$mybvn' WHERE userid = '$userIde'") : "";
    ($userVerifiedBVN != "" && $userType == "Customer") ? mysqli_query($link, "UPDATE borrowers SET unumber = '$mybvn' WHERE id = '$userIde'") : "";
    mysqli_query($link, "INSERT INTO wallet_loan_history VALUES(null,'$lid','$userid','$userIde','$mybvn','$amount','$lproduct','$interestRate','$interestType','$interestAmt','$tenor','$duration','$income','$repaymentMethod','$iuid','$amountToPay','$myBalance','$icurrency','$institution_id','$isbranchid','$date_time','Pending','','$lreasons')");
    mysqli_query($link, "INSERT INTO payment_schedule VALUES(null,'$lid','$userIde','$duration','$tenor','$institution_id','','$lproduct')") or die ("Error: " . mysqli_error($link));

    echo "<div class='alert bg-blue'>Loan Booked Successfully...Please wait patiently to complete the last stage!</div>";
    echo '<meta http-equiv="refresh" content="5;url=bookLoan.php?id='.$_SESSION['tid'].'&&uid='.$acct_owner.'&&act='.$act.'&&lide='.$lid.'&&tab=tab_2">';

}
?>

            <div class="box-body">
                
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
                                    
                                    $search_bvnverify = mysqli_query($link, "SELECT * FROM bvn_log WHERE accountID = '$acct_owner'");
                                    $fetch_bvnverify = mysqli_fetch_array($search_bvnverify);
                                    $bvn_nos = mysqli_num_rows($search_bvnverify);
                                    $concat = $fetch_bvnverify['mydata'];
                                    $parameter = (explode('|',$concat));
                                    $old_picture = $parameter[20];
                                    
                                    $seach_membersttings = mysqli_query($link, "SELECT * FROM member_settings WHERE companyid = '$institution_id'");
                                    $fetch_memset = mysqli_fetch_array($seach_membersttings);
                                                                        
                                    ($bvn_nos == 1 && $old_picture != "") ? unlink("../img/".$old_picture) : "";
                                    
                                    ($bvn_nos == 1) ? $update_bvn = mysqli_query($link, "UPDATE bvn_log SET mydata = '$mybvn_data' WHERE accountID = '$acct_owner'") : $insert_bvn = mysqli_query($link, "INSERT INTO bvn_log VALUE(null,'$institution_id','$isbranchid','$acct_owner','$iuid','$mybvn_data','$ibvn_fee','$wallet_date_time','$rOrderID')");
                                    
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
                <label for="" class="col-sm-3 control-label"><button type="submit" class="btn bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>" name="verifyBVN"><i class="fa fa-eye">&nbsp;Verify BVN</i></button></label>
            </div>

<?php
$lid = $_GET['lide'];
$select = mysqli_query($link, "SELECT * FROM wallet_loan_history WHERE lid = '$lid'") or die ("Error: " . mysqli_error($link));
$rowloan = mysqli_fetch_array($select);
?>

            <div class="form-group">
                <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Loan Product</label>
				 <div class="col-sm-10">
                <select name="lproduct" class="form-control select2" id="loan_products" style="width: 100%;" required>
                    <?php
                    $pid = $rowloan['lproduct'];
                    $selectProduct = mysqli_query($link, "SELECT * FROM loan_product WHERE id = '$pid'");
                    $fetchLP = mysqli_fetch_array($selectProduct);
                    ?>
				    <option value="<?php echo ($lid == "") ? '' : $fetchLP['id']; ?>" selected="selected"><?php echo ($lid == "") ? '--Select Loan Product--' : $fetchLP['pname'].' - (Interest Rate: '.$fetchLP['interest'].'% based on tenor'; ?></option>
	                <?php
					$getin = mysqli_query($link, "SELECT * FROM loan_product WHERE (merchantid = '$institution_id' AND category = 'Individual') OR (merchantid != '$institution_id' AND category = 'Individual' AND visibility = 'Yes' AND authorize = '1') ORDER BY id") or die (mysqli_error($link));
					while($row = mysqli_fetch_array($getin))
					{
					echo '<option value="'.$row['id'].'">'.$row['pname'].' - '.'(Interest Rate: '. $row['interest'].'% based on tenor)'.'</option>';
					}
					?>
                </select>
              </div>
			 </div>

			  
  			<span id='ShowValueFrank'></span>
  			<span id='ShowValueFrank'></span>
  			

            <div class="form-group">
            <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Upload Documents:</label>
            <div class="col-sm-10">
                <input name="uploaded_file[]" type="file" class="btn bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>" multiple/>
                <span style="color: <?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>;">Document Accepted: <b>GOVERNMENT ISSUED ID CARD, INTL. PASSPORT, UTILITY BILLS, BANK ACCOUNT STATEMENT</b></span>
                <hr>
                    <?php
                    $acct_owner = $_GET['uid'];
                    $i = 0;
                    $search_file = mysqli_query($link, "SELECT * FROM attachment WHERE borrowerid = '$userid'") or die ("Error: " . mysqli_error($link));
                    if(mysqli_num_rows($search_file) == 0){
                    	echo "<span style='color: ".(($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color'])."'>No file attached!!</span>";
                    }else{
                    	while($get_file = mysqli_fetch_array($search_file)){
                    		$i++;
                    ?>
                    <a href="../application/<?php echo $get_file['attached_file']; ?>" target="_blank"><img src="../img/file_attached.png" width="64" height="64"> Attachment<?php echo $i; ?></a>
                    <?php
                    	}
                    }
                    ?>
                <hr>
            </div>
            <label for="" class="col-sm-1 control-label"></label>
            </div>
            
            
            
            <div class="form-group">
                <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Reasons</label>
                <div class="col-sm-10">
				<select name="lreasons" class="form-control select2" style="width: 100%;" required>
				    <option value="<?php echo ($lid == '') ? '' : $rowloan['lreasons']; ?>" selected="selected"><?php echo ($lid == '') ? '--Select Loan Type--' : $rowloan['lreasons']; ?></option>
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
                <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Account No.:</label>
                <div class="col-sm-10">
                    <input name="walletAcctNo" type="text" class="form-control" value="<?php echo $acct_owner; ?>" placeholder="Wallet Account Number" readonly>
                </div>
            </div>

            <div class="form-group">
                <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Account Name:</label>
                <div class="col-sm-10">
                    <input name="walletAcctName" type="text" class="form-control" value="<?php echo $fetchVAWN['account_name']; ?>" placeholder="Wallet Account Name" readonly>
                </div>
                <label for="" class="col-sm-1 control-label"></label>
            </div>
            
            <div class="form-group">
                <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Income Amount</label>
                <div class="col-sm-10">
                <input name="income" type="text" class="form-control" value="<?php echo ($lid == '') ? '' : $rowloan['income']; ?>" placeholder="Enter Your Average Income Amount" required>
                </div>
                </div>
                
            <div class="form-group">
                <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Repayment Method</label>
                <div class="col-sm-10">
				<select name="repaymentMethod" class="form-control select2" style="width: 100%;" required>
				    <option value="<?php echo ($lid == '') ? '' : $rowloan['repaymentMethod']; ?>" selected="selected"><?php echo ($lid == '') ? '--Select Repayment Method--' : $rowloan['repaymentMethod']; ?></option>
	                <option value="Wallet">Wallet</option>
	                <option value="Others">Others</option>
                </select>
          		</div>
			</div>
			

			</div>
			 
			 
			<?php
			if($lid == ''){
			?>
			
			<div class="form-group" align="right">
                <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;"></label>
                <div class="col-sm-7">
                </div>
                <label for="" class="col-sm-3 control-label"><button name="bookLoan" type="submit" class="btn bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>"><i class="fa fa-upload">&nbsp;Proceed</i></button></label>
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
	
	$location = "img/".$_FILES['image']['name'];
	
	mysqli_query($link, "INSERT INTO loan_guarantor VALUES(null,'$lid','$userIde','$userid','$location','$g_name','$g_phone','$g_bvn','$grela','$gaddress','Pending','','$iAcctOfficer','$institution_id','$isbranchid','')");
    
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
			 
    			<div class="form-group" align="right">
                    <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;"></label>
                    <div class="col-sm-7">
                    </div>
                    <label for="" class="col-sm-3 control-label">
                        <button name="addGuarantor" type="submit" class="btn bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>"><i class="fa fa-plus"> Add</i></button>
                        <a href="bookLoan.php?id=<?php echo $_SESSION['tid']; ?>&&uid=<?php echo $acct_owner; ?>&&lide=<?php echo $lide; ?>&&tab=tab_3"><button name="Next" type="button" class="btn bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>"><i class="fa fa-save"> Next</i></button></a>                    
                    </label>
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
                
    			    
    			</form>
			<div>
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
    
    echo "<div class='alert bg-blue'>Loan application submit successfully!</div>";
    echo '<meta http-equiv="refresh" content="5;url=loanHistory.php?id='.$_SESSION['tid'].'&&uid='.$acct_owner.'&&act='.$act.'">';
    
}
?>

<?php
if(isset($_POST['No'])){
    
    $lid = $_GET['lide'];
    
    mysqli_query($link, "DELETE FROM wallet_loan_history WHERE lid = '$lid'");
    mysqli_query($link, "DELETE FROM loan_guarantor WHERE lid = '$lid'");
    
    echo "<div class='alert bg-blue'>Loan application cancelled successfully!</div>";
    echo '<meta http-equiv="refresh" content="5;url=loanHistory.php?id='.$_SESSION['tid'].'&&uid='.$acct_owner.'&&act='.$act.'">';
    
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
                                <button name="No" type="submit" class="btn bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>"><i class="fa fa-times">&nbsp;No</i></button>              
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
<div align="center"><?php include("include/footer.php"); ?></div>
    </section>
    <!-- /.content -->



