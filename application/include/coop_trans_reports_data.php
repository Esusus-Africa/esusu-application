<div class="box">
	       <div class="box-body">
			<div class="panel panel-success">
            <div class="panel-heading bg-blue">
            <h3 class="panel-title"> <a href="dashboard.php?id=<?php echo $_SESSION['tid']; ?>&&mid=NDAx"><button type="button" class="btn btn-flat bg-orange"><i class="fa fa-mail-reply-all"></i>&nbsp;Back</button> </a> <i class="fa fa-area-chart"></i>  Report</h3>
            </div>

             <div class="box-body">
			 
			<form class="form-horizontal" method="post" enctype="multipart/form-data">
			 <div class="alert bg-blue"> The Cooperation transactions report shows the <b>total amount charged from each cooperatives members account with reasons based on each transactions status.</b>. </div>
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
					<option value="success">Success</option>
					<option value="failed">Failed</option>
				  </select>
                  </div>

                <label for="" class="col-sm-2 control-label" style="color:blue;">Cooperatives </label>
                <div class="col-sm-4">
                <select name="coopid"  class="form-control select2" style="width:100%">
				<option selected="selected">Filter By Cooperative...</option>
				<?php
				$get = mysqli_query($link, "SELECT * FROM cooperatives WHERE status = 'Approved' ORDER BY id") or die (mysqli_error($link));
				while($rows = mysqli_fetch_array($get))
				{
				?>
				<option value="<?php echo $rows['coopid']; ?>"><?php echo $rows['coopname']; ?></option>
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
	$status = mysqli_real_escape_string($link, $_POST['status']);
	$coopid = mysqli_real_escape_string($link, $_POST['coopid']);
	echo "<script>window.location='coop_trans_reports.php?id=".$_SESSION['tid']."&&dfrom=".$dfrom."&&dto=".$dto."&&status=".$status."&&coopid=".$coopid."&&mid=NDI1'; </script>";
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
                  <th>Coop. ID</th>
                  <th><div align="center">Ref. ID</div></th>
				  <th><div align="center">Invite ID</div></th>
				  <th><div align="center">Position</div></th>
                  <th><div align="center">Amount Contributed</div></th>
                  <th><div align="center">Status</div></th>
                  <th><div align="center">Transaction Date</div></th>
                 </tr>
                </thead>
                <tbody>
<?php
$dfrom = $_GET['dfrom'];
$dto = $_GET['dto'];
$status = $_GET['status'];
$coopid = $_GET['coopid'];
$select = mysqli_query($link, "SELECT * FROM coopsavings_transaction WHERE trans_date BETWEEN '$dfrom' AND '$dto' AND coopid = '$coopid' AND remarks = '$status' ORDER BY id") or die (mysqli_error($link));
while($row = mysqli_fetch_array($select))
{
$id = $row['id'];

$select1 = mysqli_query($link, "SELECT * FROM systemset") or die (mysqli_error($link));
$row1 = mysqli_fetch_array($select1);
$currency = $row1['currency'];
?>

				<tr>
				<td><b><?php echo $row['coopid']; ?></b></td>
				<td><?php echo $row['refid']; ?></td>
				<td><b><?php echo $row['invitation_id']; ?></b></td>
				<td><?php echo $row['position']; ?></td>
				<td><?php echo  $currency.number_format($row['amount_contributed'],2,'.',','); ?></td>
				<td><?php echo "<label class='label bg-blue'>".$row['remarks']."</label>"; ?>
					<?php echo "Gateway Response: ".$row['gateway_response']."</label>"; ?>
				</td>
				<td><?php echo $row['trans_date']; ?></td>
				</tr>
<?php } ?>
				</tbody>
                </table> 
</div>
               <form method="post">
                	<input type='button' id='btnprint' class='btn bg-blue' value='Print'>
                	<a href="excel_cooptrans.php?dfrom=<?php echo $dfrom; ?>&&dto=<?php echo $dto; ?>&&status=<?php echo $status; ?>&&coopid=<?php echo $coopid; ?>" class="btn bg-orange" target="_blank"><i class="fa fa-export"></i> Export to Excel</a>
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
	echo "<script>window.open('../pdf/view/pdf_cooptrans.php?dfrom=".$dfrom."&&dto=".$dto."&&coopid=".$coopid."&&status=".$status."', '_blank'); </script>";
}
?>


</div>	
</div>	
</div>
</div>