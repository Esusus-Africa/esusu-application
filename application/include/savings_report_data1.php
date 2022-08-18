<div class="box">
	       <div class="box-body">
			<div class="panel panel-success">
            <div class="panel-heading bg-blue">
            <h3 class="panel-title"> <a href="dashboard.php?id=<?php echo $_SESSION['tid']; ?>&&mid=NDAx"><button type="button" class="btn btn-flat bg-orange"><i class="fa fa-mail-reply-all"></i>&nbsp;Back</button> </a> <i class="fa fa-area-chart"></i>  Report</h3>
            </div>

             <div class="box-body">
			 
			<form class="form-horizontal" method="post" enctype="multipart/form-data">
			 <div class="alert bg-blue"> The Savings Transactions Report (Normal) shows the <b>Deposit, Withdrawal Amount, Withdrawal charges with transaction remarks.</b>. </div>
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
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Transaction Type</label>
                  <div class="col-sm-4">
                  <select name="ttype"  class="form-control select2" style="width:100%">
					<option selected="selected">Filter By Transaction Type...</option>
					<option value="Transfer">Transfer</option>
					<option value="Deposit">Deposit</option>
					<option value="Withdraw">Withdrawal</option>
					<option value="Withdraw-Charges">Withdrawal Charges</option>
				  </select>
                  </div>
				  
				<label for="" class="col-sm-2 control-label" style="color:blue;">Customer</label>
                  <div class="col-sm-4">
                 <select name="acctno"  class="form-control select2" style="width:100%">
				<option selected="selected">Filter By Customer...</option>
				<?php
				$get = mysqli_query($link, "SELECT * FROM borrowers WHERE branchid = '$session_id' ORDER BY id") or die (mysqli_error($link));
				while($rows = mysqli_fetch_array($get))
				{
				?>
				<option value="<?php echo $rows['account']; ?>"><?php echo $rows['lname'].' '.$rows['fname']; ?></option>
				<?php } ?>				
				</select>
                  </div>
                </div>

            <div class="form-group">
            	<label for="" class="col-sm-2 control-label" style="color:blue;">Staff</label>
                  <div class="col-sm-4">
                 <select name="staff"  class="form-control select2" style="width:100%">
				<option selected="selected">Filter By Staff...</option>
				<?php
				$get = mysqli_query($link, "SELECT * FROM user WHERE created_by = '$session_id' ORDER BY id") or die (mysqli_error($link));
				while($rows = mysqli_fetch_array($get))
				{
				?>
				<option value="<?php echo $rows['id']; ?>"><?php echo $rows['name']; ?></option>
				<?php } ?>				
				</select>
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
	$acctno = mysqli_real_escape_string($link, $_POST['acctno']);
	$ttype = mysqli_real_escape_string($link, $_POST['ttype']);
	$staff = mysqli_real_escape_string($link, $_POST['staff']);
	echo "<script>window.location='savings_report.php?id=".$_SESSION['tid']."&&dfrom=".$dfrom."&&dto=".$dto."&&ttype=".$ttype."&&acctno=".$acctno."&&staff=".$staff."&&mid=NDI1'; </script>";
}
?>

<?php
if(isset($_GET['dfrom']))
{
?>
<hr>
<div id='printarea'>
<div align="left" style="color: orange;"> <h4><b>From <?php echo $_GET['dfrom']; ?> - <?php echo $_GET['dto']; ?></b> (change dates above)</h4> </div>
<br>
<table class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th><div align="center">TxID</div></th>
				  <th><div align="center">Account Number</div></th>
				  <th><div align="center">Transfer Recipient</div></th>
                  <th><div align="center">Acct. Holder Name</div></th>
                  <th><div align="center">Phone</div></th>
                  <th><div align="center">Amount</div></th>
                  <th><div align="center">Posted By</div></th>
                  <th><div align="center">Remarks</div></th>
                  <th><div align="center">Transaction Date</div></th>
                 </tr>
                </thead>
                <tbody>
<?php
$dfrom = $_GET['dfrom'];
$dto = $_GET['dto'];
$ttype = $_GET['ttype'];
$acctno = $_GET['acctno'];
$staff = $_GET['staff'];
$select = mysqli_query($link, "SELECT * FROM transaction WHERE date_time BETWEEN '$dfrom' AND '$dto' AND t_type = '$ttype' AND acctno = '$acctno' OR date_time BETWEEN '$dfrom' AND '$dto' AND posted_by = '$staff' ORDER BY id") or die (mysqli_error($link));
while($row = mysqli_fetch_array($select))
{
$id = $row['id'];

$select1 = mysqli_query($link, "SELECT * FROM systemset") or die (mysqli_error($link));
$row1 = mysqli_fetch_array($select1);
$currency = $row1['currency'];
?>

				<tr>
				<td align="center"><b><?php echo $row['txid']; ?></b></td>
				<td align="center"><?php echo $row['acctno']; ?></td>
				<td align="center"><b><?php echo ($row['transfer_to'] == "----") ? 'NIL' : $row['transfer_to']; ?></b></td>
				<td align="center"><?php echo $row['ln'].' '.$row['fn']; ?></td>
				<td align="center"><?php echo $row['phone']; ?></td>
				<td align="center"><b><?php echo number_format($row['amount'],2,'.',','); ?></b></td>
				<td><?php echo $row['posted_by']; ?></td>
				<td align="center"><?php echo ($row['remark'] == "") ? 'NIL' : $row['remark']; ?></td>
				<td align="center"><?php echo $row['date_time']; ?></td>
				</tr>
<?php } ?>
				</tbody>
                </table> 
</div>
               <form method="post">
                	<input type='button' id='btnprint' class='btn bg-blue' value='Print'>
                	<a href="excel_normalsavings1.php?dfrom=<?php echo $dfrom; ?>&&dto=<?php echo $dto; ?>&&ttype=<?php echo $ttype; ?>&&acctno=<?php echo $acctno; ?>&&staff=<?php echo $staff; ?>" class="btn bg-orange" target="_blank"><i class="fa fa-export"></i> Export to Excel</a>
					<input type="submit" name="generate_pdf" class="btn bg-blue" value="Generate PDF"/>
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
	echo "<script>window.open('../pdf/view/pdf_normalsavings1.php?dfrom=".$dfrom."&&dto=".$dto."&&ttype=".$ttype."&&acctno=".$acctno."&&staff=".$staff."', '_blank'); </script>";
}
?>


</div>	
</div>	
</div>
</div>