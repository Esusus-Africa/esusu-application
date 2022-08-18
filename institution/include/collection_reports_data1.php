<div class="box">
	       <div class="box-body">
			<div class="panel panel-success">
            <div class="panel-heading bg-blue">
            <h3 class="panel-title"> <a href="dashboard.php?id=<?php echo $_SESSION['tid']; ?>&&mid=NDAx"><button type="button" class="btn btn-flat bg-orange"><i class="fa fa-mail-reply-all"></i>&nbsp;Back</button> </a> <i class="fa fa-area-chart"></i>  Report</h3>
            </div>

             <div class="box-body">
			 
			<form class="form-horizontal" method="post" enctype="multipart/form-data">
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

             <div class="form-group">				  
				  <label for="" class="col-sm-2 control-label" style="color:blue;">Staff</label>
                  <div class="col-sm-10">
                  <select name="staffid"  class="form-control select2" style="width:100%">
					<option selected="selected">Filter By Staff...</option>
					<?php
					$get = mysqli_query($link, "SELECT * FROM agent_data WHERE agentid = '$session_id' ORDER BY id") or die (mysqli_error($link));
					while($rows = mysqli_fetch_array($get))
					{
					?>
					<option value="<?php echo $rows['id']; ?>"><?php echo $rows['fname']; ?></option>
					<?php } ?>				
				  </select>
                  </div>
                  </div>				  
			 </div>
			 
			  <div align="right">
              <div class="box-footer">
                				<button type="reset" class="btn bg-orange btn-flat"><i class="fa fa-times">&nbsp;Reset</i></button>
                				<button name="search" type="submit" class="btn bg-blue btn-flat"><i class="fa fa-filter">&nbsp;Search</i></button>

              </div>
			  </div>
			</form> 
			
<?php
if(isset($_POST['search'])){
	$dfrom = mysqli_real_escape_string($link, $_POST['dfrom']);
	$dto = mysqli_real_escape_string($link, $_POST['dto']);
	//$bid = mysqli_real_escape_string($link, $_POST['branchid']);
	$sid = mysqli_real_escape_string($link, $_POST['staffid']);
	//$instid = mysqli_real_escape_string($link, $_POST['instid']);
	echo "<script>window.location='collection_reports.php?id=".$_SESSION['tid']."&&dfrom=".$dfrom."&&dto=".$dto."&&staffid=".$sid."&&mid=NDI1'; </script>";
}
?>

<?php
if(isset($_GET['dfrom']))
{
?>
<?php
$staffid = $_GET['staffid'];
$get = mysqli_query($link, "SELECT * FROM agent_data WHERE id = '$staffid'") or die (mysqli_error($link));
$rows = mysqli_fetch_object($get);
$staffname = $rows->fname;

$get2 = mysqli_query($link, "SELECT * FROM systemset") or die (mysqli_error($link));
$rows2 = mysqli_fetch_object($get2);
?>	

<hr>
<div id='printarea'>
<div align="left" style="color: orange;"> <h4><b>From <?php echo $_GET['dfrom']; ?> - <?php echo $_GET['dto']; ?></b> (change dates above)</h4> </div>
<br>
<table class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>Loan Status</th>
                  <th></th>
				  <th><div align="center">Principal Amount</div></th>
                  <th><div align="center">Interest</div></th>
                 </tr>
                </thead>
                <tbody>
<?php
$dfrom = $_GET['dfrom'];
$dto = $_GET['dto'];
$branchid = $_GET['branchid'];
$instid = $_GET['instid'];
$select = mysqli_query($link, "SELECT borrower, sum(amount), sum(balance), sum(interest_rate), sum(amount_topay) FROM loan_info WHERE date_release BETWEEN '$dfrom' AND '$dto' AND teller = '$staffname' ORDER BY id") or die (mysqli_error($link));
while($row = mysqli_fetch_array($select))
{
$id = $row['id'];
$borrower = $row['borrower'];

$select1 = mysqli_query($link, "SELECT * FROM systemset") or die (mysqli_error($link));
$row1 = mysqli_fetch_array($select1);
$currency = $row1['currency']; 
?>

<!-- OPEN SECTION FOR PAID AMOUNT FOR OPEN LOAN (ON SCHEDULE) -->
				<tr>
				<td><b>Open Loans (On Schedule)</b></td>
				<td><div style="color: blue;">Paid</div></td>
				<td>
				<?php
				//GET TRACK OF TOTAL PAID AMOUNT HERE
				$date_now = date("Y-m-d");
				$select = mysqli_query($link, "SELECT borrower FROM loan_info WHERE status = 'Approved'") or die (mysqli_error($link));
				while($row = mysqli_fetch_array($select))
				{
					$b = $row['borrower'];
					$search_user = mysqli_query($link, "SELECT * FROM borrowers WHERE id = '$b'") or die (mysqli_error($link));
					while($get_u = mysqli_fetch_array($search_user))
					{
						$acctno = $get_u['account'];
						$sel_paid = mysqli_query($link, "SELECT sum(payment) FROM pay_schedule WHERE tid = '$acctno' AND status = 'PAID'") or die (mysqli_error($link));
						$row_paid = mysqli_fetch_array($sel_paid);
						echo "<div align='center' style='color: orange;'>".$currency.number_format($row_paid['sum(payment)'],2,'.',',')."</div>";
					}
				}
				?>
				</td>
				<td>
				<?php
				//TO GET TOTAL INTEREST PAID
				$select = mysqli_query($link, "SELECT borrower, sum(amount), sum(interest_rate) FROM loan_info WHERE status = 'Approved'") or die (mysqli_error($link));
				while($rows = mysqli_fetch_array($select))
				{
					$b = $rows['borrower'];
					$search_user = mysqli_query($link, "SELECT * FROM borrowers WHERE id = '$b'") or die (mysqli_error($link));
					while($get_u = mysqli_fetch_array($search_user))
					{
						$acctno = $get_u['account'];
						$sel_paid = mysqli_query($link, "SELECT * FROM pay_schedule WHERE tid = '$acctno' AND status = 'PAID'") or die (mysqli_error($link));
						$row_paid = mysqli_fetch_array($sel_paid);
						$num_paid = mysqli_num_rows($sel_paid);
						
						$sel_p = mysqli_query($link, "SELECT * FROM pay_schedule WHERE tid = '$acctno'") or die (mysqli_error($link));
						$nump = mysqli_num_rows($sel_p);
						
						$get_rate = $rows['sum(interest_rate)']/100;
						$get_amt = (($get_rate * $rows['sum(amount)'])/$nump)*$num_paid;
						echo "<div align='center' style='color: orange;'>".$currency.number_format($get_amt,2,'.',',')."</div>";
					}
				}
				?>
				</td>
				</tr>
<!-- CLOSE SECTION FOR PAID AMOUNT FOR OPEN LOAN (ON SCHEDULE) -->

<!-- OPEN SECTION FOR GROSS DUE PAYMENT FOR OPEN LOAN (ON SCHEDULE) -->
				<tr>
				<td></td>
				<td><div style="color: orange;">Gross Due</div></td>
				<td>
				<?php
				//GET TRACK OF TOTAL PAID AMOUNT HERE
				$date_now = date("Y-m-d");
				$sel_due = mysqli_query($link, "SELECT sum(balance) FROM loan_info WHERE borrower = '$borrower' AND pay_date <= '$date_now' AND status = 'Approved'") or die (mysqli_error($link));
				$row_due = mysqli_fetch_array($sel_due);
				echo "<div align='center' style='color: orange;'>".$currency.number_format($row_due['sum(balance)'],2,'.',',')."</div>";
				?>
				</td>
				<td>
				<?php
				//TO GET TOTAL INTEREST PAID
				$date_now = date("Y-m-d");
				$select = mysqli_query($link, "SELECT borrower, sum(amount), sum(interest_rate) FROM loan_info WHERE status = 'Approved'") or die (mysqli_error($link));
				while($rows = mysqli_fetch_array($select))
				{
					$b = $rows['borrower'];
					$search_user = mysqli_query($link, "SELECT * FROM borrowers WHERE id = '$b'") or die (mysqli_error($link));
					while($get_u = mysqli_fetch_array($search_user))
					{
						$acctno = $get_u['account'];
						$sel_paid = mysqli_query($link, "SELECT * FROM pay_schedule WHERE tid = '$acctno' AND status = 'UNPAID' AND schedule <= '$date_now'") or die (mysqli_error($link));
						$row_paid = mysqli_fetch_array($sel_paid);
						$num_paid = mysqli_num_rows($sel_paid);
						
						$sel_p = mysqli_query($link, "SELECT * FROM pay_schedule WHERE tid = '$acctno'") or die (mysqli_error($link));
						$nump = mysqli_num_rows($sel_p);
						
						$get_rate = $rows['sum(interest_rate)']/100;
						$get_amt = (($get_rate * $rows['sum(amount)'])/$nump)*$num_paid;
						echo "<div align='center' style='color: orange;'>".$currency.number_format($get_amt,2,'.',',')."</div>";
					}
				}
				?>
				</td>
				</tr>
<!-- CLOSE SECTION FOR GROSS DUE PAYMENT FOR OPEN LOAN (ON SCHEDULE) -->


<!--------------------------------------------------------------------->


<!-- OPEN SECTION FOR PAID AMOUNT FOR MISSED REPAYMENT LOAN AMOUNT -->
				<tr>
				<td><b>Missed Repayment Loans</b></td>
				<td><div style="color: blue;">Paid</div></td>
				<td>
				<?php
				//GET TRACK OF TOTAL PAID AMOUNT HERE
				$date_now = date("Y-m-d");
				$search_user = mysqli_query($link, "SELECT * FROM borrowers WHERE id = '$borrower'") or die (mysqli_error($link));
				while($get_u = mysqli_fetch_array($search_user))
				{
					$acctno = $get_u['account'];
					$sel_mispay = mysqli_query($link, "SELECT sum(payment) FROM pay_schedule WHERE tid = '$acctno' AND status = 'PAID' AND schedule <= '$date_now'") or die (mysqli_error($link));
					$row_mispay = mysqli_fetch_array($sel_mispay);
					echo "<div align='center' style='color: blue;'>".$currency.number_format($row_mispay['sum(payment)'],2,'.',',')."</div>";
				}
				?>
				</td>
				<td>
				<?php
				//TO GET TOTAL INTEREST PAID
				$search_user = mysqli_query($link, "SELECT * FROM borrowers WHERE id = '$borrower'") or die (mysqli_error($link));
				while($get_u = mysqli_fetch_array($search_user))
				{
					$acctno = $get_u['account'];
					$sel_mispay = mysqli_query($link, "SELECT sum(payment) FROM pay_schedule WHERE tid = '$acctno' AND status = 'PAID' AND schedule <= '$date_now'") or die (mysqli_error($link));
					$row_mispay = mysqli_fetch_array($sel_mispay);
					
					$get_rate = $row['sum(interest_rate)']/100;
					$get_mamt = $get_rate * $row_mispay['sum(amount_to_pay)'];
					echo "<div align='center' style='color: orange;'>".$currency.number_format($get_mamt,2,'.',',')."</div>";
				}
				?>
				</td>
				</tr>
<!-- CLOSE SECTION FOR PAID AMOUNT FOR MISSED REPAYMENT LOAN AMOUNT -->

<!-- OPEN SECTION FOR GROSS DUE PAYMENT FOR MISSED REPAYMENT LOAN AMOUNT -->
				<tr>
				<td></td>
				<td><div style="color: orange;">Gross Due</div></td>
				<td>
				<?php
				//GET TRACK OF TOTAL PAID AMOUNT HERE
				$date_now = date("Y-m-d");
				$search_user = mysqli_query($link, "SELECT * FROM borrowers WHERE id = '$borrower'") or die (mysqli_error($link));
				while($get_u = mysqli_fetch_array($search_user))
				{
					$acctno = $get_u['account'];
					$sel_mdue = mysqli_query($link, "SELECT sum(payment) FROM pay_schedule WHERE tid = '$acctno' AND status = 'UNPAID' AND schedule <= '$date_now'") or die (mysqli_error($link));
					$row_mdue = mysqli_fetch_array($sel_mdue);
					echo "<div align='center' style='color: orange;'>".$currency.number_format($row_mdue['sum(payment)'],2,'.',',')."</div>";
				}
				?>
				</td>
				<td>
				<?php
				//TO GET TOTAL INTEREST PAID
				$search_user = mysqli_query($link, "SELECT * FROM borrowers WHERE id = '$borrower'") or die (mysqli_error($link));
				while($get_u = mysqli_fetch_array($search_user))
				{
					$acctno = $get_u['account'];
					$sel_mdue = mysqli_query($link, "SELECT sum(payment) FROM pay_schedule WHERE tid = '$acctno' AND status = 'UNPAID' AND schedule <= '$date_now'") or die (mysqli_error($link));
					$row_mdue = mysqli_fetch_array($sel_mdue);
					
					$get_rate_due = $row['sum(interest_rate)']/100;
					$get_amt_due = $get_rate_due * $row_mdue['sum(payment)'];
					echo "<div align='center' style='color: orange;'>".$currency.number_format($get_amt_due,2,'.',',')."</div>";
				}
				?>
				</td>
				</tr>
<!-- CLOSE SECTION FOR GROSS DUE PAYMENT FOR MISSED REPAYMENT LOAN AMOUNT -->

<?php } ?>
				</tbody>
                </table> 

            </div>
            <input type='button' id='btnprint' class='btn bg-blue' value='Print'>

<?php 
}
else{
	echo "";
}
?>


</div>	
</div>	
</div>
</div>