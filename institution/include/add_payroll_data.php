<div class="box">
	       <div class="box-body">
			<div class="panel panel-success">
            <div class="panel-heading bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">
            <h3 class="panel-title"> <a href="newpayroll.php?id=<?php echo $_SESSION['tid']; ?>&&mid=NDIz"><button type="button" class="btn btn-flat bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>"><i class="fa fa-mail-reply-all"></i>&nbsp;Back</button> </a> <i class="fa fa-paypal"></i>  Add Payroll</h3>
            </div>

             <div class="box-body">
<?php
if(isset($_POST['save']))
{
	$branchid = mysqli_real_escape_string($link, $_POST['branchid']);
	$staff_id =  $_GET['staff_id'];
	$bizname = mysqli_real_escape_string($link, $_POST['bizname']);
	$payroll_date =  mysqli_real_escape_string($link, $_POST['payroll_date']);
	$basic_pay =  mysqli_real_escape_string($link, $_POST['basic_pay']);
	$overtime =  mysqli_real_escape_string($link, $_POST['overtime']);
	$paid_leave =  mysqli_real_escape_string($link, $_POST['paid_leave']);
	$transport_allowance =  mysqli_real_escape_string($link, $_POST['transport_allowance']);
	$medical_allowance =  mysqli_real_escape_string($link, $_POST['medical_allowance']);
	$bonus =  mysqli_real_escape_string($link, $_POST['bonus']);
	$other_allowance =  mysqli_real_escape_string($link, $_POST['other_allowance']);
	$total_pay =  mysqli_real_escape_string($link, $_POST['total_pay']);
	$pension =  mysqli_real_escape_string($link, $_POST['pension']);
	$health_insurance =  mysqli_real_escape_string($link, $_POST['health_insurance']);
	$unpaid_leave =  mysqli_real_escape_string($link, $_POST['unpaid_leave']);
	$tax_deduction =  mysqli_real_escape_string($link, $_POST['tax_deduction']);
	$salary_loan=  mysqli_real_escape_string($link, $_POST['salary_loan']);
	$total_deduction =  mysqli_real_escape_string($link, $_POST['total_deduction']);
	$net_pay =  mysqli_real_escape_string($link, $_POST['net_pay']);
	$pmethod =  mysqli_real_escape_string($link, $_POST['pmethod']);
	$bname =  mysqli_real_escape_string($link, $_POST['bname']);
	$acctno =  mysqli_real_escape_string($link, $_POST['acctno']);
	$adesc =  $_POST['adesc'];
	$comment =  $_POST['comment'];
	
	$protocol = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
	
	$insert = mysqli_query($link, "INSERT INTO payroll VALUES(null,'$institution_id','$branchid','$staff_id','$bizname','$payroll_date','$basic_pay','$overtime','$paid_leave','$transport_allowance','$medical_allowance','$bonus','$other_allowance','$total_pay','$pension','$health_insurance','$unpaid_leave','$tax_deduction','$salary_loan','$total_deduction','$net_pay','$pmethod','$bname','$acctno','$adesc','$comment','$icurrency')") or die ("Error: " . mysqli_error($link));
	
	$verify_link = mysqli_query($link, "SELECT * FROM payroll ORDER BY id DESC")  or die ("Error: " . mysqli_error($link));
	$get_link = mysqli_fetch_object($verify_link);
	$idm = $get_link->id;
	
	$url = $protocol . $_SERVER['HTTP_HOST']."/institution/generate_payslip.php?id=".$idm;
	$id = rand(10000,99999);
	$shorturl = base_convert($id,20,36);
	
	$insert = mysqli_query($link, "INSERT INTO short_urls VALUES(null,'$url','$shorturl')") or die ("Error: " . mysqli_error($link));
	
	$shortenedurl = $protocol . $_SERVER['HTTP_HOST'] . '/'.$isenderid.'?key=' . $shorturl;
	
	$search_cust = mysqli_query($link, "SELECT * FROM user WHERE userid = '$staff_id'") or die("Error:" . mysqli_error($link));
	$get_cust = mysqli_fetch_object($search_cust);
		//$fname = $get_cust['fname'];
		//$email2 = $get_cust['email'];
		$query = mysqli_query($link, "SELECT email FROM systemset") or die (mysqli_error($link));
		$r = mysqli_fetch_object($query);
		//send email
		$to = "$get_cust->email";
		$subject = "Generated Payslip";
		$body = "\n Kindly click the link below to View / Print your Payslip for $get_link->pay_date";
		$body .= "\n $shortenedurl";
		$additionalheaders = "From:$inst_email\r\n";
		$additionalheaders .= "Reply-To:noreply@imon.com \r\n";
		$additionalheaders .= "MIME-Version: 1.0";
		$additionalheaders .= "Content-Type: text/html\r\n";
		if(mail($to,$subject,$body,$additionalheaders) || $insert)
		{
			echo "<hr>";
			echo "<div class='alert alert-success'>New Payroll Added Successfully!</div>";
		}
		else{
			echo "<hr>";
			echo "<div class='alert alert-danger'>Unable to Add Payroll.....Please try again later!</div>";
		}
}
?>           
			<form class="form-horizontal" method="post" enctype="multipart/form-data">
			 
             <div class="box-body">

<?php
$staffid = $_GET['staff_id'];
$get = mysqli_query($link, "SELECT * FROM user WHERE userid = '$staffid'") or die (mysqli_error($link));
$rows = mysqli_fetch_object($get);

$get2 = mysqli_query($link, "SELECT * FROM systemset") or die (mysqli_error($link));
$rows2 = mysqli_fetch_object($get2);
?>				  

			<input name="branchid" type="hidden" class="form-control" value="<?php echo $rows->branchid; ?>">
			
			<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Employee Name</label>
                  <div class="col-sm-4">
                  <input name="emp_name" type="text" class="form-control" value="<?php echo $rows->name; ?>" placeholder="Employee Name" required readonly>
                  </div>
				  
				  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Payroll Date</label>
                  <div class="col-sm-4">
                  <input name="payroll_date" type="date" class="form-control" required>
                  </div>
                  </div>
				  
			<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Tracking Number</label>
                  <div class="col-sm-4">
                  <input name="emp_trackno" type="text" class="form-control" value="<?php echo $rows->id; ?>" placeholder="Employee Tracking Number" required readonly>
                  </div>
				  
				  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Business Name</label>
                  <div class="col-sm-4">
                  <input name="bizname" type="text" class="form-control" value="<?php echo $inst_name; ?>" required>
                  </div>
                  </div>
				  
			<div class="alert bg-orange fade in">
			<div class="form-group">
                  <div class="col-sm-2" align="right"><b>Description</b></div>
                  <div class="col-sm-4" align="right"><b>Amount</b></div>
				  
				  <div class="col-sm-2" align="right"><b>Description</b></div>
                  <div class="col-sm-4" align="right"><b>Amount</b></div>
                  </div>
			</div>
			
			<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Basic Pay</label>
                  <div class="col-sm-4">
                  <input name="basic_pay" type="text" class="form-control" id="basic_pay" onkeyup="payrollAdd();" required>
                  </div>
				  
				  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Pension</label>
                  <div class="col-sm-4">
                  <input name="pension" type="text" class="form-control" id="pension" onkeyup="payrollAdd();" required>
                  </div>
                  </div>
			
			<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Overtime</label>
                  <div class="col-sm-4">
                  <input name="overtime" type="text" class="form-control" id="overtime" onkeyup="payrollAdd();" required>
                  </div>
				  
				  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Health Insurance</label>
                  <div class="col-sm-4">
                  <input name="health_insurance" type="text" class="form-control" id="health_insurance" onkeyup="payrollAdd();" required>
                  </div>
                  </div>
				  
			<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Paid Leaves</label>
                  <div class="col-sm-4">
                  <input name="paid_leave" type="text" class="form-control" id="paid_leave" onkeyup="payrollAdd();" required>
                  </div>
				  
				  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Unpaid Leave</label>
                  <div class="col-sm-4">
                  <input name="unpaid_leave" type="text" class="form-control" id="unpaid_leave" onkeyup="payrollAdd();" required>
                  </div>
                  </div>
				  
			<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Transport Allowance</label>
                  <div class="col-sm-4">
                  <input name="transport_allowance" type="text" class="form-control" id="transport_allowance" onkeyup="payrollAdd();" required>
                  </div>
				  
				  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Tax Deduction</label>
                  <div class="col-sm-4">
                  <input name="tax_deduction" type="text" class="form-control" id="tax_deduction" onkeyup="payrollAdd();" required>
                  </div>
                  </div>
				  
			<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Medical Allowance</label>
                  <div class="col-sm-4">
                  <input name="medical_allowance" type="text" class="form-control" id="medical_allowance" onkeyup="payrollAdd();" required>
                  </div>
				  
				  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Salary Loan</label>
                  <div class="col-sm-4">
                  <input name="salary_loan" type="text" class="form-control" id="salary_loan" onkeyup="payrollAdd();" required>
                  </div>
                  </div>
				  
			<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Bonus</label>
                  <div class="col-sm-4">
                  <input name="bonus" type="text" class="form-control" id="bonus" onkeyup="payrollAdd();" required>
                  </div>
                  </div>
				  
			<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Other Allowance</label>
                  <div class="col-sm-4">
                  <input name="other_allowance" type="text" class="form-control" id="other_allowance" onkeyup="payrollAdd();" required>
                  </div>
                  </div>
				  
			<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Total Pay</label>
                  <div class="col-sm-4">
                  <input name="total_pay" type="text" class="form-control" id="total_output" required readonly>
                  </div>
				  
				  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Total Deduction</label>
                  <div class="col-sm-4">
                  <input name="total_deduction" type="text" class="form-control" id="total_deduct" readonly>
                  </div>
                  </div>
				  
			<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;"></label>
                  <div class="col-sm-4">
                  </div>
				  
				  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Net Pay</label>
                  <div class="col-sm-4">
                  <input name="net_pay" type="text" class="form-control" id="final_output" required readonly>
                  </div>
                  </div>
			
<div class="alert bg-orange fade in"> <b>Net Pay Distribution</b> </div>

			<div class="form-group">
                  <div class="col-sm-2" align="left">
				  <p><b>Payment Method</b></p>
				  <input name="pmethod" type="text" class="form-control" required>
                  </div>
				  
                  <div class="col-sm-3" align="left">
				  <p><b>Bank Name</b></p>
				  <input name="bname" type="text" class="form-control" required>
                  </div>
				  
				  <div class="col-sm-2" align="left">
				  <p><b>Account Number</b></p>
				  <input name="acctno" type="text" class="form-control" required>
                  </div>
				  
				  <div class="col-sm-3" align="left">
				  <p><b>Description</b></p>
				  <input name="adesc" type="text" class="form-control" required>
                  </div>
				  
				  <div class="col-sm-2" align="right">
				  <p><b>Paid Amount</b></p>
				  <input name="paid_amt" type="text" class="form-control" id="paid_amt" required readonly>
                  </div>
                  </div>
				  
			<div class="form-group">
                  <div class="col-sm-12" align="left">
				  <p><b>Comments</b></p>
				  <input name="comment" type="text" class="form-control">
                  </div>
				  </div>
				  
			
			<p><span style="color: blue;"><b>NOTE: </b></span><span style="color: orange;"> Please make sure there is an email address for the staff since a <b>copy of this Payslip</b> will be send to Staff Inbox. If not, please <a href="view_emp.php?id=<?php echo $_GET['staff_id']; ?>&&mid=NDA5" target="_blank">edit the staff</a> and enter email.
				  
			 </div>
			 
			  <div align="right">
              <div class="box-footer">
                				<button name="save" type="submit" class="btn bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>"><i class="fa fa-save">&nbsp;Save</i></button>

              </div>
			  </div>
			  
			</form> 

</div>	
</div>	
</div>
</div>