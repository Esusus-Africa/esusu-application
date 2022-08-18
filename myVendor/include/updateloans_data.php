<div class="row">	
		
	 <section class="content">
     
	        <div class="box box-success">
            <div class="box-body">
              <div class="table-responsive"> 
             <div class="box-body">
<?php
$id = $_GET['id'];
$select = mysqli_query($link, "SELECT * FROM loan_info WHERE id = '$id'") or die (mysqli_error($link));
$row = mysqli_fetch_array($select);
//$borrower = $row['borrower'];  
$baccount = $row['baccount']; 
?>
			 <div class="col-md-14">
             <div class="nav-tabs-custom">
             <ul class="nav nav-tabs">
              <li><a href="#tab_1" data-toggle="tab">Loan Information</a></li>
			  <li <?php echo ($_GET['tab'] == 'tab_0') ? "class='active'" : ''; ?>><a href="updateloans.php?id=<?php echo $_GET['id']; ?>&&acn=<?php echo $_GET['acn']; ?>&&mid=<?php echo base64_encode("405"); ?>&&lid=<?php echo $_GET['lid']; ?>&&tab=tab_0">ATM Card Verification</a></li>
              <li <?php echo ($_GET['tab'] == 'tab_3') ? "class='active'" : ''; ?>><a href="updateloans.php?id=<?php echo $_GET['id']; ?>&&acn=<?php echo $_GET['acn']; ?>&&mid=<?php echo base64_encode("405"); ?>&&lid=<?php echo $_GET['lid']; ?>&&tab=tab_3">Attachment</a></li>
              <li <?php echo ($_GET['tab'] == 'tab_5') ? "class='active'" : ''; ?>><a href="updateloans.php?id=<?php echo $_GET['id']; ?>&&acn=<?php echo $_GET['acn']; ?>&&mid=<?php echo base64_encode("405"); ?>&&lid=<?php echo $_GET['lid']; ?>&&tab=tab_5">Repayment Schedule</a></li>
			  <li <?php echo ($_GET['tab'] == "tab_4") ? "class='active'" : ''; ?>><a href="#">Loan Bal: <b><?php echo ($row['balance'] == "") ? $vcurrency."0.0" : $vcurrency.number_format($row['balance'],2,'.',','); ?></b></a></li>  
			</ul>
             <div class="tab-content">
             <div class="tab-pane <?php echo ($_GET['tab'] == 'tab_1') ? 'active' : ''; ?>" id="tab_1">
           
			 <form class="form-horizontal" method="post" enctype="multipart/form-data" action="process_loan_info2.php">
	
             <div class="box-body">
			
			 <div class="form-group">
                <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">Borrower</label>
				 <div class="col-sm-10">
				<?php
				$get = mysqli_query($link, "SELECT * FROM borrowers WHERE account = '$baccount'") or die (mysqli_error($link));
				while($rows = mysqli_fetch_array($get))
				{
				?>
					<input name="borrower" type="text" class="form-control" value=<?php echo $rows['fname'].'&nbsp;'.$rows['lname']; ?> readonly>
				<?php } ?>
              </div>
			  </div>
			  
			<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">Account</label>
                  <div class="col-sm-10">
                  <input name="account" type="text" class="form-control" value="<?php echo $row['baccount']; ?>" readonly>
                  </div>
                  </div>
				 
		<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">Amount</label>
                  <div class="col-sm-10">
                  <input name="amount" type="text" class="form-control" value="<?php echo $row['amount']; ?>" readonly>
                  </div>
                  </div>
				  
		<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">Salary Income</label>
                  <div class="col-sm-10">
                  <input name="income" type="text" class="form-control" value="<?php echo $row['income']; ?>" readonly>
                  </div>
                  </div>
				  
		<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">Application Date</label>
                  <div class="col-sm-10">
	               <div class="input-group date">
	                  <div class="input-group-addon">
	                    <i class="fa fa-calendar"></i>
	                  </div>
                  <input name="salary_date" type="text" class="form-control" value="<?php echo $row['salary_date']; ?>" readonly>
			  </div>
             </div>
           </div>
				  
		<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">Employer Name</label>
                  <div class="col-sm-10">
                  <input name="employer" type="text" class="form-control" value="<?php echo $row['employer']; ?>" readonly>
                  </div>
                  </div>
		
		 <div class="form-group">
                <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">Date Release</label>
			 <div class="col-sm-10">
              <div class="input-group date">
                  <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                  </div>
                  <input name="date_release" type="text" class="form-control pull-right" value="<?php echo $row['date_release']; ?>" readonly>
                </div>
              </div>
			  </div>
			  
			 <div class="form-group">
                  	<label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">Your Bank Account Info.</label>
                  	<div class="col-sm-10">
					<textarea name="bacinfo"  class="form-control" rows="4" cols="80" readonly><?php echo $row['desc']; ?></textarea>
           			</div>
          	 </div>
			 
			 <div class="form-group">
		                 	<label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">Reasons for Loan.</label>
		                 	<div class="col-sm-10">
						<textarea name="lreasons"  class="form-control" rows="4" cols="80" readonly><?php echo $row['remark']; ?></textarea>
		          			 </div>
						 </div>
			  
			<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">Account Officer</label>
                  <div class="col-sm-10">
                  <input name="agent" type="text" class="form-control" value="<?php echo $row['agent']; ?>" readonly>
                  </div>
                  </div>
				  
				 
				  
			<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">Status</label>
                  <div class="col-sm-10">
                  <input name="status" type="text" class="form-control" value="<?php echo $row['status']; ?>"readonly="readonly">
                  </div>
                  </div>


<hr>	
<div class="bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>">&nbsp;<?php echo ($row['loantype'] != 'Purchase') ? "GUARANTOR INFORMATION" : "PRODUCT INFORMATION"; ?></div>
<hr>
				  
			<div class="form-group">
				<label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>"><?php echo ($row['loantype'] != 'Purchase') ? "Gurantor's Passport" : "Product Invoice"; ?></label>
				<div class="col-sm-10">
  		  		
       			 <img id="blah"  src="../<?php echo $row ['g_image'] ;?>" alt="Image Here" height="100" width="100"/>
			</div>
			</div>
			
			<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>"><?php echo ($row['loantype'] != 'Purchase') ? "Relationship" : "Product Name"; ?></label>
                  <div class="col-sm-10">
                  <input name="grela" type="text" class="form-control" value="<?php echo $row['rela']; ?>" readonly="readonly">
                  </div>
                  </div>
			
			 <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>"><?php echo ($row['loantype'] != 'Purchase') ? "Guarantor's Name" : "Model Number"; ?></label>
                  <div class="col-sm-10">
                  <input name="agent" type="text" class="form-control" value="<?php echo $row['g_name']; ?>" readonly>
                  </div>
                  </div>
				  
				  <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>"><?php echo ($row['loantype'] != 'Purchase') ? "Guarantor's Phone Number" : "Serial Number"; ?></label>
                  <div class="col-sm-10">
                  <input name="agent" type="text" class="form-control" value="<?php echo $row['g_phone']; ?>" readonly>
                  </div>
                  </div>
                  
                  <?php
                  if($row['loantype'] != 'Purchase'){
                      echo "";
                  }
                  else{
                    ?>
                  
                  <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">Quantity</label>
                  <div class="col-sm-10">
                  <input name="qty" type="number" class="form-control" value="<?php echo $row['amount_topay'] / $row['amount']; ?>" readonly>
                  </div>
                  </div>  
                    
                  <?php
                  }
                  ?>
				  
				 
				 <div class="form-group">
                  	<label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>"><?php echo ($row['loantype'] != 'Purchase') ? "Guarantor's Address" : "Other Description"; ?></label>
                  	<div class="col-sm-10">
					<textarea name="gaddress"  class="form-control" rows="4" cols="80" readonly="readonly"><?php echo $row['g_address']; ?></textarea>
           			 </div>
          	    </div> 
			

<hr>	
<div class="bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>">&nbsp;PAYMENT INFORMATION</div>
<hr>	
				  
				  <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">Amount to Pay</label>
                  <div class="col-sm-10">
                  <input name="amount_topay" type="number" class="form-control" value="<?php echo $row['amount_topay']; ?>" readonly>
                  </div>
                  </div>
				  
				  <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">Teller By</label>
                  <div class="col-sm-10">
                  <input name="teller" type="text" class="form-control" value="<?php echo $row['teller']; ?>" readonly>
                  </div>
                  </div>
                  
                  <div class="form-group">
		              <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">Loan Product</label>
		  			 <div class="col-sm-10">
		              <select name="lproduct2" class="select2" id="loan_products2" style="width: 100%;" required>
		  	              <?php
		  	  			$id = $_GET['id'];
		  	  			$getin = mysqli_query($link, "SELECT * FROM loan_info WHERE id='$id' AND vendorid ='$vendorid'") or die (mysqli_error($link));
		  	  			$row = mysqli_fetch_array($getin);
						$idp = $row['lproduct'];
							
			  	  			$search_lp = mysqli_query($link, "SELECT * FROM loan_product WHERE id='$idp' AND merchantid='$vendorid'") or die (mysqli_error($link));
			  	  			$row_lp = mysqli_fetch_array($search_lp);
		  				echo '<option value="'.$row_lp['id'].'" selected="selected">'.$row_lp['pname'].' - '.'(Interest Rate: '. $row_lp['interest'].'% per month)'.'</option>';
		  	  				?>
		                  <?php
		  				$getin = mysqli_query($link, "SELECT * FROM loan_product WHERE merchantid='$vendorid' order by id") or die (mysqli_error($link));
		  				while($row = mysqli_fetch_array($getin))
		  				{
		  				echo '<option value="'.$row['id'].'">'.$row['pname'].' - '.'(Interest Rate: '. $row['interest'].'% per month)'.'</option>';
		  				}
		  				?>
		              </select>
		            </div>
		  		  </div>
				  
	    			<span id='ShowValueFrank'></span>
	    			<span id='ShowValueFrank'></span>
				  
	  			 <input name="lid" type="hidden" class="form-control" value="<?php echo $_GET['lid']; ?>" readonly>
				  
			 </div>			
			 
			  </form>

              </div>
              <!-- /.tab-pane -->
	<?php
	if(isset($_GET['tab']) == true)
	{
		$tab = $_GET['tab'];
		if($tab == 'tab_0')
		{
			$acn = $_GET['acn'];
			$lid = $_GET['lid'];
			$search_customer = mysqli_query($link, "SELECT * FROM borrowers WHERE account = '$acn'");
			$get_customer = mysqli_fetch_object($search_customer);
		
			$select1 = mysqli_query($link, "SELECT * FROM systemset") or die (mysqli_error($link));
			$row1 = mysqli_fetch_object($select1);
			
			$search_cardverification = mysqli_query($link, "SELECT * FROM authorized_card WHERE lid = '$lid'");
			$get_cardverify = mysqli_num_rows($search_cardverification);
		?>
		
	              <div class="tab-pane <?php echo ($_GET['tab'] == 'tab_0') ? 'active' : ''; ?>" id="tab_0">
			<?php
			if($get_cardverify == 1)
			{	
			    include ("../config/restful_apicalls.php");
			    
			    $systemset = mysqli_query($link, "SELECT * FROM systemset");
				$row1 = mysqli_fetch_object($systemset);
  
				$result = array();
				$get_cardverify = mysqli_fetch_object($search_cardverification);
				$refid = $get_cardverify->refid;
				$auth = $get_cardverify->authorized_code;
				
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
				
				if($result['data']['status'] == 'successful'){
				    
				    // the transaction was successful, you can deliver value
				    /* 
				    @ also remember that if this was a card transaction, you can store the 
				    @ card authorization to enable you charge the customer subsequently. 
				    @ The card authorization is in: 
				    @ $result['data']['authorization']['authorization_code'];
				    @ PS: Store the authorization with this email address used for this transaction. 
				    @ The authorization will only work with this particular email.
				    @ If the user changes his email on your system, it will be unusable
				    */
						  
					//$autgorization_code = $result['data']['authorization']['authorization_code'];
					echo "<p>Transaction Reference Number: <b>".$refid."</b></p>";
					echo "<p>Authorization Code: <b>".$auth."</b></p>";

					//no action should be performed
					}
					else{
					    
					    // the transaction was not successful, do not deliver value'
				          // print_r($result);  //uncomment this line to inspect the result, to check why it failed.
				          echo "Transaction was not successful: Last gateway response was: ".$result['data']['chargemessage'];
				    }
			?>
			
			<br>
			<p style="color: green;"><i class="fa fa-check"></i><b> Card Details Verified Successfully!</b></p>
			
		<?php
		}
		else{
			?>
				  <br>
				  <p style="color: <?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>;"><i class="fa fa-times"></i><b> Card Details Not Verified</b></p>
				  <p style="color: <?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>;"><b>PLEASE</b><i> Contact the Customer to do so as it's part of the requirement to get loan approved</i></p>
				  
		<?php
	    }
		?>
		
              </div>
              
	<?php
	}
	elseif($tab == 'tab_3')
	{
	?>
              <!-- /.tab-pane -->
              <div class="tab-pane <?php echo ($_GET['tab'] == 'tab_3') ? 'active' : ''; ?>" id="tab_3">
                <form class="form-horizontal" method="post" enctype="multipart/form-data">

                Attachments
Accepted file types <span style="color:<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>">jpg, gif, png, xls, xlsx, csv, doc, docx, pdf</span>
			 <input name="uploaded_file" type="file" class="btn bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>">
			 <div align="left">
              <div class="box-footer">
                				<button type="submit" class="btn bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?> btn-flat" name="upload"><i class="fa fa-upload">&nbsp;Upload</i></button>
			<br><br>
			<span style="color: <?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>;"><b>PLEASE UPLOAD GUARANTOR GOVERNMENT ISSUED ID CARD OR BORROWER BANK STATEMENT OF ACCOUNT FOR VERIFICATION AND AS A REQUIREMENT TO GET LOAN APPROVED</b></span>
              </div>
			  </div>
<?php
if(isset($_POST['upload']))
{
$id = $_GET['id'];
$tid = $_SESSION['tid'];

//upload random name/number
	 $rd2 = mt_rand(1000,9999)."_File"; 
	 
	 //Check that we have a file
	if((!empty($_FILES["uploaded_file"])) && ($_FILES['uploaded_file']['error'] == 0)) {
  //Check if the file is JPEG image and it's size is less than 350Kb
  $filename = basename($_FILES['uploaded_file']['name']);
  
  $ext = substr($filename, strrpos($filename, '.') + 1);
  
  if (($ext != "exe") && ($_FILES["uploaded_file"]["type"] != "application/x-msdownload"))  {
    //Determine the path to which we want to save this file      
	  //$newname = dirname(__FILE__).'/upload/'.$filename;
	  $newname="document/".$rd2."_".$filename;      
	  //Check if the file with the same name is already exists on the server
 
        //Attempt to move the uploaded file to it's new place
        if ((move_uploaded_file($_FILES['uploaded_file']['tmp_name'],$newname))) {
			//successful upload
          // echo "It's done! The file has been saved as: ".$newname;		   

$insert = mysqli_query($link, "INSERT INTO attachment VALUES(null,'$id','$tid','$newname',NOW())") or die (mysqli_error($link));
if(!$insert)
{
echo "<script>alert('Record not inserted.....Please try again later!'); </script>";
}
else{
echo "<script>alert('Documents Added Successfully!!'); </script>";
}
}
}
}
}
?>
<hr>
<?php
$get_id = $_GET['id'];
$i = 0;
$search_file = mysqli_query($link, "SELECT * FROM attachment WHERE get_id = '$get_id'") or die ("Error: " . mysqli_error($link));
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
			 </form> 
              </div>
			  
	<?php
	}
	elseif($tab == 'tab_5')
	{
	?>
			  
			  <div class="tab-pane <?php echo ($_GET['tab'] == 'tab_5') ? 'active' : ''; ?>" id="tab_5">
			<form class="form-horizontal" method="post" enctype="multipart/form-data">
			    <div align="center"><h3><b>Loan Repayment Schedule</b></h3></div>
			<div class="box-body">

<?php
$lid = $_GET['lid'];
$searchin = mysqli_query($link, "SELECT * FROM payment_schedule WHERE lid = '$lid'") or die (mysqli_error($link));
$haveit = mysqli_fetch_array($searchin);
$idmet= $haveit['id'];
$lproduct = $haveit['lproduct'];

$search_mylp = mysqli_query($link, "SELECT * FROM loan_product WHERE id = '$lproduct'");
$fetch_mylp = mysqli_fetch_array($search_mylp);
$iintr_type = $fetch_mylp['interest_type'];
?>

				<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">Duration Type:</label>
                  <div class="col-sm-10">
				  <select name="d1" class="form-control" readonly>
				 <option value="<?php echo $haveit['term']; ?>"><?php echo $haveit['schedule']; ?></option>
				  </select>
                  </div>
                  </div>
                  
                  <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">Interest Type:</label>
                  <div class="col-sm-10">
				  <select name="intr_type" class="form-control" readonly>
				 <option value="<?php echo ($lproduct == "") ? 'Flat Rate' : $iintr_type; ?>"><?php echo ($lproduct == "") ? 'Flat Rate' : $iintr_type; ?></option>
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
$get_id = $_GET['id'];
$searchin = mysqli_query($link, "SELECT * FROM pay_schedule WHERE get_id = '$get_id'") or die (mysqli_error($link));
while($haveit = mysqli_fetch_array($searchin))
{
$idmet= $haveit['id'];
?>			
				<tr>
			<td><input id="optionsCheckbox" class="uniform_on" name="selector[]" type="hidden" value="<?php echo $idmet; ?>" checked></td>
       <td width="400"><input name="schedulek[]" type="date" class="form-control pull-right" placeholder="Schedule" value="<?php echo $haveit['schedule']; ?>"></td>
           <td width="300"><input name="balance[]" type="text" class="form-control" placeholder="Balance" value="<?php echo $haveit['balance']; ?>"></td>
			<td width="130"><input name="payment[]" type="text" class="form-control" placeholder="Payment" value="<?php echo $haveit['payment']; ?>"></td>
			<!--<td width="100"><input name="lstatus[]" type="text" class="form-control" placeholder="Status" value="<?php echo $haveit['status']; ?>" disabled></td>-->
			</tr>
<?php } ?>
				</tbody>
                </table>
<hr>
<?php
$id = $_GET['id'];
$searchin2 = mysqli_query($link, "SELECT * FROM pay_schedule WHERE get_id = '$id' AND (balance = '0' OR balance <= '0')") or die (mysqli_error($link));
$numit = mysqli_num_rows($searchin2);
?>
<?php echo ($numit == 1) ? "<span class='label bg-".(($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color'])."'>Completed</span>" : "<span class='label bg-".(($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color'])."'>Progressing...(Schedule not completed yet).</span>"; ?>
<hr>
<div align="left">
              <div class="box-footer">

                				<button type="submit" class="btn bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>" name="add_sch_rows" <?php echo ($numit == 1) ? 'disabled' : ''; ?>><i class="fa fa-plus">&nbsp;Generate Schedule</i></button>
                				<button name="delrow2" type="submit" class="btn bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>"><i class="fa fa-trash">&nbsp;Delete Schedule</i></button> 
                                <?php
                                $id = $_GET['id'];
                                $comfirm_select1 = mysqli_query($link, "SELECT * FROM loan_info WHERE id = '$id'") or die (mysqli_error($link));
                                $comfirm_row1 = mysqli_fetch_array($comfirm_select1);
                                if($comfirm_row1['status'] == "Approved"){
                                    echo "";
                                }
                                else{
                                ?>
                                    <button type="submit" class="btn bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>" name="add_pay_schedule"><i class="fa fa-save">&nbsp;Save</i></button>
                                <?php
                                }
                                ?>
								<?php
                                $id = $_GET['id'];
                                $comfirm_select = mysqli_query($link, "SELECT * FROM loan_info WHERE id = '$id'") or die (mysqli_error($link));
                                $comfirm_row = mysqli_fetch_array($comfirm_select);
                                if($comfirm_row['upstatus'] == "Completed"){
                                ?>
                                    <button type="submit" class="btn bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>" name="generate_pdf"><i class="fa fa-print">&nbsp;Print Schedule!</i></button>
                                <?php
                                }else{
                                    echo "";
                                }
                                ?>
              </div>
			  </div>
   <?php
						if(isset($_POST['delrow2'])){
						$idm = $_GET['id'];
							$lid = $_GET['lid'];
							$id=$_POST['selector'];
							$N = count($id);
						if($id == ''){
						echo "<script>alert('Row Not Selected!!!'); </script>";	
						echo "<script>window.location='updateloans.php?id=".$idm."&&acn=".$_GET['acn']."&&mid=".base64_encode("405")."&&lid=".$lid."&&tab=tab_5'; </script>";
						}
						else{
							for($i=0; $i < $N; $i++)
							{
							    $update_interest_calc = mysqli_query($link, "DELETE FROM interest_calculator WHERE lid = '$lid'");
								$result = mysqli_query($link,"DELETE FROM pay_schedule WHERE id ='$id[$i]'");
								echo "<script>window.location='updateloans.php?id=".$idm."&&acn=".$_GET['acn']."&&mid=".base64_encode("405")."&&lid=".$lid."&&tab=tab_5'; </script>";
							}
						}
						}
?>

<?php
if(isset($_POST['add_sch_rows']))
{
$id = $_GET['id'];
$lid = $_GET['lid'];
$tid = $_GET['acn'];

$day = mysqli_real_escape_string($link, $_POST['d1']);
$intr_type = mysqli_real_escape_string($link, $_POST['intr_type']);
$schedule_of_paymt = mysqli_real_escape_string($link, $_POST['schedule']);

$N = $day;

$process_data2 = mysqli_query($link, "SELECT * FROM loan_info WHERE lid = '$lid'") or die (mysqli_error($link));
$fetch = mysqli_fetch_array($process_data2);
$totalamount_topay = $fetch['amount_topay'];
$baccount = $fetch['baccount'];
$amount_borrowed = $fetch['amount'];
$lproduct = $fetch['lproduct'];
$vendorid = $fetch['vendorid'];

$search_product = mysqli_query($link, "SELECT * FROM loan_product WHERE id = '$lproduct'");
$get_product = mysqli_fetch_object($search_product);
$duration = $get_product->duration;
$interest = $get_product->interest / 100;

$amt_topay_per_duration = $amount_borrowed / $duration;

$calc_myint = ($amount_borrowed / $duration) + (($amount_borrowed / $duration) * $interest);

$int_rate = ($intr_type == "Flat Rate") ? ($totalamount_topay / $duration) : $calc_myint;

$first_balance = ($intr_type == "Flat Rate") ? number_format(($totalamount_topay - $int_rate),0,'.','') : number_format(($amount_borrowed - $amt_topay_per_duration),0,'.','');

$verify_data = mysqli_query($link, "SELECT * FROM pay_schedule WHERE lid = '$lid'") or die (mysqli_error($link));
$verify_data2 = mysqli_query($link, "SELECT * FROM interest_calculator WHERE lid = '$lid'") or die (mysqli_error($link));
if((mysqli_num_rows($verify_data) == 0) && (mysqli_num_rows($verify_data2) == 0) && ($schedule_of_paymt == "Monthly") && ($intr_type == "Flat Rate"))
{
    $insert = mysqli_query($link, "INSERT INTO interest_calculator VALUES(null,'$lid','$first_balance','$int_rate','$duration')") or die (mysqli_error($link));
    
    $cur_date = strtotime($fetch['salary_date']);
    $lastDayThisMonth = date("Y-m-d", strtotime("+1 month", $cur_date));
	
    
    $insert = mysqli_query($link, "INSERT INTO pay_schedule VALUES(null,'$lid','$id','$baccount','','$lastDayThisMonth','$first_balance','$int_rate','UNPAID','$vcreated_by','$vendorid','Pending')") or die (mysqli_error($link));
    //$update_calculator = mysqli_query($link, "UPDATE interest_calculator SET amt_to_pay = '$first_balance' WHERE lid = '$lid'") or die (mysqli_error($link));
    	    
    for($i = 1; $i < $N; $i++) {
    	        
    	//CONFIRMATION OF INTEREST RATE
        $select_int_calculator = mysqli_query($link, "SELECT * FROM interest_calculator WHERE lid = '$lid'") or die (mysqli_error($link));
        $get_calculator = mysqli_fetch_array($select_int_calculator);
        $balance = $get_calculator['amt_to_pay'];
        $lrate = $get_calculator['int_rate'];
        $new_balance = number_format(($balance - $lrate),0,'.','');
    	        
    	$verify_data1 = mysqli_query($link, "SELECT * FROM pay_schedule WHERE lid = '$lid' ORDER BY id DESC") or die (mysqli_error($link));
        $fetch_data1 = mysqli_fetch_array($verify_data1);
        $my_schedule = strtotime($fetch_data1['schedule']);
        $lastDayThisMonth2 = date("Y-m-d", strtotime("+1 month", $my_schedule));
        	    
        $insert = mysqli_query($link, "INSERT INTO pay_schedule VALUES(null,'$lid','$id','$baccount','','$lastDayThisMonth2','$new_balance','$int_rate','UNPAID','$vcreated_by','$vendorid','Pending')") or die (mysqli_error($link));
        $update_calculator = mysqli_query($link, "UPDATE interest_calculator SET amt_to_pay = '$new_balance' WHERE lid = '$lid'") or die (mysqli_error($link));
    	        
    }
    echo "<script>window.location='updateloans.php?id=".$id."&&acn=".$tid."&&mid=NDA1&&lid=".$lid."&&tab=tab_5'; </script>";
	
}
elseif((mysqli_num_rows($verify_data) == 0) && (mysqli_num_rows($verify_data2) == 0) && ($schedule_of_paymt == "Monthly") && ($intr_type != "Flat Rate"))
{
    $insert = mysqli_query($link, "INSERT INTO interest_calculator VALUES(null,'$lid','$first_balance','$int_rate','$duration')") or die (mysqli_error($link));
    
    $cur_date = strtotime($fetch['salary_date']);
    $lastDayThisMonth = date("Y-m-d", strtotime("+1 month", $cur_date));
    
    $insert = mysqli_query($link, "INSERT INTO pay_schedule VALUES(null,'$lid','$id','$baccount','','$lastDayThisMonth','$first_balance','$int_rate','UNPAID','$vcreated_by','$vendorid')") or die (mysqli_error($link));
    //$update_calculator = mysqli_query($link, "UPDATE interest_calculator SET amt_to_pay = '$first_balance' WHERE lid = '$lid'") or die (mysqli_error($link));
    
    for($i = 1; $i < $N; $i++) {
    	        
    	//CONFIRMATION OF INTEREST RATE
        $select_int_calculator = mysqli_query($link, "SELECT * FROM interest_calculator WHERE lid = '$lid'") or die (mysqli_error($link));
        $get_calculator = mysqli_fetch_array($select_int_calculator);
        $balance = $get_calculator['amt_to_pay'];
        $myduration = $get_calculator['duration'] - 1;
        $next_amt_topay = ($balance / $myduration) + (($balance / $myduration) * $interest);
        $new_balance = ($myduration == 1) ? '0' : number_format(($balance - $next_amt_topay),0,'.','');
    	        
    	$verify_data1 = mysqli_query($link, "SELECT * FROM pay_schedule WHERE lid = '$lid' ORDER BY id DESC") or die (mysqli_error($link));
        $fetch_data1 = mysqli_fetch_array($verify_data1);
        $my_schedule = strtotime($fetch_data1['schedule']);
        $lastDayThisMonth2 = date("Y-m-d", strtotime("+1 month", $my_schedule));
        	    
        $insert = mysqli_query($link, "INSERT INTO pay_schedule VALUES(null,'$lid','$id','$baccount','','$lastDayThisMonth2','$new_balance','$next_amt_topay','UNPAID','$vcreated_by','$vendorid')") or die (mysqli_error($link));
        $update_calculator = mysqli_query($link, "UPDATE interest_calculator SET amt_to_pay = '$new_balance', duration = '$myduration' WHERE lid = '$lid'") or die (mysqli_error($link));
    	        
    }
    echo "<script>window.location='updateloans.php?id=".$id."&&acn=".$tid."&&mid=NDA1&&lid=".$lid."&&tab=tab_5'; </script>";
	
}
elseif((mysqli_num_rows($verify_data) == 0) && (mysqli_num_rows($verify_data2) == 0) && ($schedule_of_paymt == "Weekly") && ($intr_type == "Flat Rate")) {
    
    $insert = mysqli_query($link, "INSERT INTO interest_calculator VALUES(null,'$lid','$first_balance','$int_rate','$duration')") or die (mysqli_error($link));
    
    $cur_date = strtotime($fetch['salary_date']);
    $lastDayThisMonth = date("Y-m-d", strtotime("+1 week", $cur_date));
	
    
    $insert = mysqli_query($link, "INSERT INTO pay_schedule VALUES(null,'$lid','$id','$baccount','','$lastDayThisMonth','$first_balance','$int_rate','UNPAID','$vcreated_by','$vendorid')") or die (mysqli_error($link));
    //$update_calculator = mysqli_query($link, "UPDATE interest_calculator SET amt_to_pay = '$first_balance' WHERE lid = '$lid'") or die (mysqli_error($link));
    	    
    for($i = 1; $i < $N; $i++) {
    	        
    	//CONFIRMATION OF INTEREST RATE
        $select_int_calculator = mysqli_query($link, "SELECT * FROM interest_calculator WHERE lid = '$lid'") or die (mysqli_error($link));
        $get_calculator = mysqli_fetch_array($select_int_calculator);
        $balance = $get_calculator['amt_to_pay'];
        $lrate = $get_calculator['int_rate'];
        $new_balance = number_format(($balance - $lrate),0,'.','');
    	        
    	$verify_data1 = mysqli_query($link, "SELECT * FROM pay_schedule WHERE lid = '$lid' ORDER BY id DESC") or die (mysqli_error($link));
        $fetch_data1 = mysqli_fetch_array($verify_data1);
        $my_schedule = strtotime($fetch_data1['schedule']);
        $lastDayThisMonth2 = date("Y-m-d", strtotime("+1 week", $my_schedule));
        	    
        $insert = mysqli_query($link, "INSERT INTO pay_schedule VALUES(null,'$lid','$id','$baccount','','$lastDayThisMonth2','$new_balance','$int_rate','UNPAID','$vcreated_by','$vendorid')") or die (mysqli_error($link));
        $update_calculator = mysqli_query($link, "UPDATE interest_calculator SET amt_to_pay = '$new_balance' WHERE lid = '$lid'") or die (mysqli_error($link));
    	        
    }
    echo "<script>window.location='updateloans.php?id=".$id."&&acn=".$tid."&&mid=NDA1&&lid=".$lid."&&tab=tab_5'; </script>";
}
elseif((mysqli_num_rows($verify_data) == 0) && (mysqli_num_rows($verify_data2) == 0) && ($schedule_of_paymt == "Weekly") && ($intr_type != "Flat Rate")) {
    
    $insert = mysqli_query($link, "INSERT INTO interest_calculator VALUES(null,'$lid','$first_balance','$int_rate','$duration')") or die (mysqli_error($link));
    
    $cur_date = strtotime($fetch['salary_date']);
    $lastDayThisMonth = date("Y-m-d", strtotime("+1 week", $cur_date));
	
    
    $insert = mysqli_query($link, "INSERT INTO pay_schedule VALUES(null,'$lid','$id','$baccount','','$lastDayThisMonth','$first_balance','$int_rate','UNPAID','$vcreated_by','$vendorid')") or die (mysqli_error($link));
    //$update_calculator = mysqli_query($link, "UPDATE interest_calculator SET amt_to_pay = '$first_balance' WHERE lid = '$lid'") or die (mysqli_error($link));
    	    
    for($i = 1; $i < $N; $i++) {
    	        
    	//CONFIRMATION OF INTEREST RATE
        $select_int_calculator = mysqli_query($link, "SELECT * FROM interest_calculator WHERE lid = '$lid'") or die (mysqli_error($link));
        $get_calculator = mysqli_fetch_array($select_int_calculator);
        $balance = $get_calculator['amt_to_pay'];
        $myduration = $get_calculator['duration'] - 1;
        $next_amt_topay = ($balance / $myduration) + (($balance / $myduration) * $interest);
        $new_balance = ($myduration == 1) ? '0' : number_format(($balance - $next_amt_topay),0,'.','');
    	        
    	$verify_data1 = mysqli_query($link, "SELECT * FROM pay_schedule WHERE lid = '$lid' ORDER BY id DESC") or die (mysqli_error($link));
        $fetch_data1 = mysqli_fetch_array($verify_data1);
        $my_schedule = strtotime($fetch_data1['schedule']);
        $lastDayThisMonth2 = date("Y-m-d", strtotime("+1 week", $my_schedule));
        	    
        $insert = mysqli_query($link, "INSERT INTO pay_schedule VALUES(null,'$lid','$id','$baccount','','$lastDayThisMonth2','$new_balance','$next_amt_topay','UNPAID','$vcreated_by','$vendorid')") or die (mysqli_error($link));
        $update_calculator = mysqli_query($link, "UPDATE interest_calculator SET amt_to_pay = '$new_balance', duration = '$myduration' WHERE lid = '$lid'") or die (mysqli_error($link));
    	        
    }
    echo "<script>window.location='updateloans.php?id=".$id."&&acn=".$tid."&&mid=NDA1&&lid=".$lid."&&tab=tab_5'; </script>";
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
$lid = $_GET['lid'];
$id=$_POST['selector'];
//$N = count($id);
	
$i = 0;
$tid = $_SESSION['tid'];
$acn = $_GET['acn'];
foreach($_POST['selector'] as $s)
{
	$schedule = mysqli_real_escape_string($link, $_POST['schedulek'][$i]);
	$update = mysqli_query($link, "UPDATE pay_schedule SET schedule = '$schedule' WHERE id = '$s'") or die (mysqli_error($link));
	$i++;
	$insert = mysqli_query($link, "UPDATE loan_info SET upstatus = 'Completed' WHERE id = '$idm'") or die (mysqli_error($link));
	if(!($update && $insert))
	{
	echo "<script>alert('Record not inserted.....Please try again later!'); </script>";
	}
	else{
	echo "<script>alert('Payment Scheduled Successfully!!'); </script>";
	echo "<script>window.location='updateloans.php?id=".$idm."&&acn=".$acn."&&mid=NDA1&&lid=".$lid."&&tab=tab_5'; </script>";
	}
}
}
?>

<?php
if(isset($_POST['generate_pdf']))
{
    $id = $_GET['id'];
    $lid = $_GET['lid'];
    $tid = $_GET['acn'];
	echo "<script>window.open('../pdf/view/pdf_payschedule.php?id=".$id."&&acn=".$tid."&&mid=NDA1&&lid=".$lid."&&instid=".$vcreated_by."', '_blank'); </script>";
}
?>
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