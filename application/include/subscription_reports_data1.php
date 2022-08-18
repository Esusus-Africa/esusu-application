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
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Status</label>
                  <div class="col-sm-4">
                  <select name="status"  class="form-control select2" style="width:100%">
					<option selected="selected">Filter By Status...</option>
					<option value="Pending">Pending</option>
					<option value="Paid">Paid</option>
					<option value="Expired">Expired</option>
					<option value="Deactivated">Deactivated</option>
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
	$status = mysqli_real_escape_string($link, $_POST['status']);
	echo "<script>window.location='subscription_reports.php?id=".$_SESSION['tid']."&&dfrom=".$dfrom."&&dto=".$dto."&&status=".$status."&&mid=NDI1'; </script>";
}
?>
<div id='printarea'>
<?php
if(isset($_GET['dfrom']))
{
?>	
<hr>
<div align="left" style="color: orange;"> <h4><b>From <?php echo $_GET['dfrom']; ?> - <?php echo $_GET['dto']; ?></b> (change dates above)</h4> </div>
<br>
<table class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>CoopID / InstID</th>
                  <th>RefID</th>
                  <th>Token ID</th>
                  <th>Plan Code</th>
				  <th>Amount Paid</th>
				  <th>Units Allocated <p style="font-size: 12px;"> (for SMS Sending)</p></th>
				  <th>Expired Date</th>
				  <th>Trans. Date</th>
				  <th>Status</th>
                 </tr>
                </thead>
                <tbody>
<?php
$dfrom = $_GET['dfrom'];
$dto = $_GET['dto'];
$status = $_GET['status'];
$data = array();
$select = mysqli_query($link, "SELECT * FROM saas_subscription_trans WHERE transaction_date BETWEEN '$dfrom' AND '$dto' AND status = '$status' AND coopid_instid = '$session_id' OR transaction_date BETWEEN '$dfrom' AND '$dto' AND coopid_instid = '$session_id' ORDER BY id") or die (mysqli_error($link));
while($row = mysqli_fetch_array($select))
{
$id = $row['id'];
$data = $row;
	$select1 = mysqli_query($link, "SELECT * FROM systemset") or die (mysqli_error($link));
	$row1 = mysqli_fetch_array($select1);
	$currency = $row1['currency']; 
?>

				<tr>
				<td><b><?php echo $row['coopid_instid']; ?></b></td>
				<td><?php echo $row['refid']; ?></td>
				<td><b><?php echo $row['sub_token']; ?></b></td>
				<td><?php echo $row['plan_code']; ?></td>
				<td><?php echo  $currency.number_format($row['amount_paid'],2,'.',','); ?></td>
				<td><?php echo number_format($row['sms_allocated'],2,'.',','); ?> Units</td>
				<td><b><?php echo $row['duration_to']; ?></b></td>
				<td><?php echo $row['transaction_date']; ?></td>
				<td><?php echo "<label class='label bg-blue'>".$row['status']."</label>"; ?></td>
				</tr>
<?php } ?>
				</tbody>
                </table> 

                

                <form method="post">
                	<input type='button' id='btnprint' class='btn bg-blue' value='Print'>
                <a href="excel_saassub1.php?dfrom=<?php echo $dfrom; ?>&&dto=<?php echo $dto; ?>&&status=<?php echo $status; ?>&&id=<?php echo $session_id; ?>" class="btn bg-orange" target="_blank"><i class="fa fa-export"></i> Export to Excel</a>
					<input type="submit" name="generate_pdf" class="btn bg-blue" value="Generate PDF"/>
				</form>
<?php 
}
else{
	echo "";
}
?>
</div>

<?php
if(isset($_POST['generate_pdf']))
{
	echo "<script>window.open('../pdf/view/pdf_saassub1.php?dfrom=".$dfrom."&&dto=".$dto."&&status=".$status."&&id=".$session_id."', '_blank'); </script>";
}
?>

</div>	
</div>	
</div>
</div>