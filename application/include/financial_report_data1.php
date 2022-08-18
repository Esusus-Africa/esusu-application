<div class="box">
	       <div class="box-body">
			<div class="panel panel-success">
            <div class="panel-heading bg-blue">
            <h3 class="panel-title"> <a href="dashboard.php?id=<?php echo $_SESSION['tid']; ?>&&mid=NDAx"><button type="button" class="btn btn-flat bg-orange"><i class="fa fa-mail-reply-all"></i>&nbsp;Back</button> </a> <i class="fa fa-area-chart"></i>  Report</h3>
            </div>

             <div class="box-body">
			 
			<form class="form-horizontal" method="post" enctype="multipart/form-data">
			 <div class="alert bg-blue"> The Financial Report shows the <b>Income and Expenses in tabular form called Profit and Loss Account.</b>. </div>
             <div class="box-body">
		  

			<input name="branchid" type="hidden" class="form-control" value="<?php echo $rows->branchid; ?>">
			
			<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">From</label>
                  <div class="col-sm-4">
                  <input name="dfrom" type="date" class="form-control" placeholder="To Date: 2018-05-01">
				  <span style="color: orange;">Date should be in this format: 2018-05-01</span>
                  </div>
				  
				  <label for="" class="col-sm-2 control-label" style="color:blue;">To</label>
                  <div class="col-sm-4">
                  <input name="dto" type="date" class="form-control" placeholder="To Date: 2018-05-24">
				  <span style="color: orange;">Date should be in this format: 2018-05-24</span>
                  </div>
             </div>

			 </div>
			 
			<div align="right">
              <div class="box-footer">
                				<button name="search" type="submit" class="btn bg-blue btn-flat"><i class="fa fa-filter">&nbsp;Search</i></button>

              </div>
			</div>

			</form> 
			
<?php
if(isset($_POST['search'])){
	$dfrom = mysqli_real_escape_string($link, $_POST['dfrom']);
	$dto = mysqli_real_escape_string($link, $_POST['dto']);
	echo "<script>window.location='financial_report.php?id=".$_SESSION['tid']."&&dfrom=".$dfrom."&&dto=".$dto."&&mid=NDI1'; </script>";
}
?>

<?php
if(isset($_GET['dfrom']))
{
	$system = mysqli_query($link, "SELECT * FROM systemset");
	$query = mysqli_fetch_array($system);
?>
<hr>
<div id='printarea'>
<div align="left" style="color: orange;"> <h4><b>From <?php echo $_GET['dfrom']; ?> - <?php echo $_GET['dto']; ?></b> (change dates above)</h4> </div>
<br>
<table class="table table-bordered table-striped" width="100%" border="1">
			  <tr>
				<td><div align="left">Dr</div></td>
				<td><div align="right"></div></td>
				<td><div align="left"></div></td>
				<td><div align="right">Cr</div></td>
			  </tr>
              <tr bgcolor="orange">
				<td><div align="left"><strong>&nbsp;Income</strong></div></td>
				<td><div align="right"><strong>&nbsp;<?php echo $query['currency']; ?></strong></div></td>
				<td><div align="left"><strong>&nbsp;Expenses</strong></div></td>
				<td><div align="right"><strong>&nbsp;<?php echo $query['currency']; ?></strong></div></td>
			  </tr>
<?php
//SEARCHING BY DATE
$dfrom = $_GET['dfrom'];
$dto = $_GET['dto'];
$id = $_GET['id'];
//GET SYSTEM CONFIGURATION SETTINGS
$select1 = mysqli_query($link, "SELECT * FROM systemset") or die (mysqli_error($link));
$row1 = mysqli_fetch_array($select1);
$currency = $row1['currency'];

////////////////////////////ALL INCOME//////////////////////////////////////

//REQUESTING FOR TOTAL SUBSCRIPTION RANGES FROM DATE1 TO DATE2
$select = mysqli_query($link, "SELECT SUM(amount_paid) FROM saas_subscription_trans WHERE duration_from BETWEEN '$dfrom' AND '$dto' AND status = 'Paid' AND coopid_instid = '$id' ORDER BY id") or die (mysqli_error($link));
while($row = mysqli_fetch_array($select))
{
	//TOTAL SAASSUBSCRIPTION TRANSACTION AMOUNT
	$saassubscription = $row['SUM(amount_paid)'];

	//PROCESS TO DETECT TOTAL SUBACCOUNT CHARGES
	$select1 = mysqli_query($link, "SELECT SUM(amount) FROM all_savingssub_transaction WHERE date_time BETWEEN '$dfrom' AND '$dto' AND status = 'success' AND merchant_id = '$id' ORDER BY id") or die (mysqli_error($link));
	while($fetch1 = mysqli_fetch_array($select1))
	{
		//TOTAL SAVINGS INCOME
		$sum1 = $fetch1['SUM(amount)'];

		$select2 = mysqli_query($link, "SELECT SUM(amount_to_pay) FROM payments WHERE pay_date BETWEEN '$dfrom' AND '$dto' AND remarks = 'paid' AND branchid = '$id' ORDER BY id") or die (mysqli_error($link));
		while($fetch2 = mysqli_fetch_array($select2))
		{
			//TOTAL LOAN REPAYMENT
			$sum2 = $fetch2['SUM(amount_to_pay)'];

			$select3 = mysqli_query($link, "SELECT SUM(amount) FROM transaction WHERE date_time BETWEEN '$dfrom' AND '$dto' AND t_type = 'Withdraw-Charges' AND branchid = '$id'");
			while($fetch3 = mysqli_fetch_array($select3))
			{
				//TOTAL WITHDRAWAL CHARGES
				$sum3 = $fetch3['SUM(amount)'];

				$select11 = mysqli_query($link, "SELECT SUM(icm_amt) FROM income WHERE icm_date BETWEEN '$dfrom' AND '$dto' AND companyid = '$id'");
				while($fetch11 = mysqli_fetch_array($select11))
				{
					//OTHER INCOME IN TOTAL
					$sum11 = $fetch11['SUM(icm_amt)'];

					$select4 = mysqli_query($link, "SELECT SUM(amount) FROM transaction WHERE date_time BETWEEN '$dfrom' AND '$dto' AND t_type = 'Deposit' AND branchid = '$id'");
					while($fetch4 = mysqli_fetch_array($select4))
					{
						//TOTAL DEPOSITED AMOUNT
						$sum4 = $fetch4['SUM(amount)'];

						///////////////////////////////ALL EXPENSES/////////////////////////////////
						$select5 = mysqli_query($link, "SELECT SUM(amount) FROM transaction WHERE date_time BETWEEN '$dfrom' AND '$dto' AND t_type = 'Withdraw' AND branchid = '$id'");
						while($fetch5 = mysqli_fetch_array($select5))
						{
							//TOTAL AMOUNT WITHDRAWN
							$sum5 = $fetch5['SUM(amount)'];

							$select6 = mysqli_query($link, "SELECT SUM(paid_amount) FROM payroll WHERE pay_date BETWEEN '$dfrom' AND '$dto' AND branchid = '$id'");
							while($fetch6 = mysqli_fetch_array($select6))
							{
								//TOTAL EMPLOYEE SALARY PAID
								$sum6 = $fetch6['SUM(paid_amount)'];

								$select7 = mysqli_query($link, "SELECT SUM(amount) FROM referral_incomehistory WHERE tdate BETWEEN '$dfrom' AND '$dto' AND status = 'Paid' AND referral_id = '$id'");
								while($fetch7 = mysqli_fetch_array($select7))
								{
									//TOTAL REFERRAL BONUS PAID
									$sum7 = $fetch7['SUM(amount)'];

									$select8 = mysqli_query($link, "SELECT SUM(amount) FROM loan_info WHERE date_release BETWEEN '$dfrom' AND '$dto' AND upstatus = 'Approved' AND branchid = '$id'");
									while($fetch8 = mysqli_fetch_array($select8))
									{
										//TOTAL LOAN RELEASED
										$sum8 = $fetch8['SUM(amount)'];

										$select9 = mysqli_query($link, "SELECT SUM(eamt) FROM expenses WHERE edate BETWEEN '$dfrom' AND '$dto' AND branchid = '$id'");
										while($fetch9 = mysqli_fetch_array($select9))
										{
											//TOTAL AMOUNT OF ALL OTHER EXPENSES ENTERED MANUALLY TO THE SYSTEM
											$sum9 = $fetch9['SUM(eamt)'];

											//CALCULATE TOTAL INCOME
											$total_income = $sum7+$sum3+$sum4+$sum2+$sum1+$sum11;

											$total_expenses = $saassubscription+$sum5+$sum6+$sum8+$sum9;

											$total_prAndloss = $total_income - $total_expenses;
?>

				<tr>
					<td><div align="left">All Savings Income</div></td>
					<td><div align="right"><?php echo number_format($sum1,2,'.',','); ?></div></td>
					<td><div align="left">Total Amount Withdrawn</div></td>
					<td><div align="right"><?php echo number_format($sum5,2,'.',','); ?></div></td>
			  	</tr>
			  	<tr>
					<td><div align="left">Total Withdrawal Charges</div></td>
					<td><div align="right"><?php echo number_format($sum3,2,'.',','); ?></div></td>
					<td><div align="left">Total Employee Salary Paid</div></td>
					<td><div align="right"><?php echo number_format($sum6,2,'.',','); ?></div></td>
			  	</tr>
			  	<tr>
					<td><div align="left">Total Deposit</div></td>
					<td><div align="right"><?php echo number_format($sum4,2,'.',','); ?></div></td>
					<td><div align="left">Total Software Subscription</div></td>
					<td><div align="right"><?php echo number_format($saassubscription,2,'.',','); ?></div></td>
			  	</tr>
			  	<tr>
					<td><div align="left">Total Loan Repayment Received</div></td>
					<td><div align="right"><?php echo number_format($sum2,2,'.',','); ?></div></td>
					<td><div align="left">Total Amount of Loan Released</div></td>
					<td><div align="right"><?php echo number_format($sum8,2,'.',','); ?></div></td>
			  	</tr>
			  	<tr>
					<td><div align="left">Total Referral Bonus Payout</div></td>
					<td><div align="right"><?php echo number_format($sum7,2,'.',','); ?></div></td>
					<td><div align="left">Total Software Subscription</div></td>
					<td><div align="right"><?php echo number_format($saassubscription,2,'.',','); ?></div></td>
				</tr>
				<tr>
					<td><div align="left">Others</div></td>
					<td><div align="right"><?php echo number_format($sum11,2,'.',','); ?></div></td>
					<td><div align="left">Others</div></td>
					<td><div align="right"><?php echo number_format($sum9,2,'.',','); ?></div></td>
			  	</tr>
			  	<tr>
					<td><div align="left"><b style="font-size: 20px;">TOTAL INCOME</b></div></td>
					<td><div align="right"><b style="font-size: 18px;"><?php echo number_format($total_income,2,'.',','); ?></b></div></td>
					<td><div align="left"><b style="font-size: 20px;">TOTAL EXPENSES</b></div></td>
					<td><div align="right"><b style="font-size: 18px;"><?php echo number_format($total_expenses,2,'.',','); ?></b></div></td>
			  	</tr>
			  	<tr>
					<td><div align="left"><b style="font-size: 18px;">TOTAL PROFIT</b></div></td>
					<td><div align="right"><b style="font-size: 16px;"><?php echo ($total_prAndloss <= 0) ? '----------' : number_format($total_prAndloss,2,'.',','); ?></b></div></td>

					<td><div align="left"><b style="font-size: 18px;">TOTAL LOSS</b></div></td>
					<td><div align="right"><b style="font-size: 16px;"><?php echo ($total_prAndloss <= 0) ? $total_prAndloss : '----------'; ?></b></div></td>
			  	</tr>
<?php } } } } } } } } } } } ?>
				</tbody>
                </table> 
</div>
               <form method="post">
                	<input type='button' id='btnprint' class='btn bg-blue' value='Print'>
					<input type="submit" name="generate_pdf" class="btn bg-orange" value="Generate PDF"/>
				</form>

<?php 
}
else{
	echo "";
}
?>

<?php
if(isset($_POST['generate_pdf']))
{
	echo "<script>window.open('../pdf/view/pdf_financial1.php?dfrom=".$dfrom."&&dto=".$dto."&&id=".$session_id."', '_blank'); </script>";
}
?>


</div>	
</div>	
</div>
</div>