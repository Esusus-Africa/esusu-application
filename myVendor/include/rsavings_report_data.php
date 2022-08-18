<div class="box">
	       <div class="box-body">
			<div class="panel panel-success">
            <div class="panel-heading bg-blue">
            <h3 class="panel-title"> <a href="dashboard.php?id=<?php echo $_SESSION['tid']; ?>&&mid=NDAx"><button type="button" class="btn btn-flat bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>"><i class="fa fa-mail-reply-all"></i>&nbsp;Back</button> </a> <i class="fa fa-area-chart"></i>  Report</h3>
            </div>

             <div class="box-body">
			 
			<form class="form-horizontal" method="post" enctype="multipart/form-data">
             <div class="box-body">
		  

			<input name="branchid" type="hidden" class="form-control" value="<?php echo $rows->branchid; ?>">
			
			<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">From</label>
                  <div class="col-sm-4">
                  <input name="dfrom" type="date" class="form-control" placeholder="To Date: 2018-05-01">
				  <span style="color: <?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>;">Date should be in this format: 2018-05-01</span>
                  </div>
				  
				  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">To</label>
                  <div class="col-sm-4">
                  <input name="dto" type="date" class="form-control" placeholder="To Date: 2018-05-24">
				  <span style="color: <?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>;">Date should be in this format: 2018-05-24</span>
                  </div>
             </div>

            <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Status</label>
                  <div class="col-sm-4">
                  <select name="status"  class="form-control select2" style="width:100%">
					<option value="" selected="selected">Filter By Status...</option>
					<option value="pending">Pending</option>
					<option value="successful">Success</option>
				  </select>
                  </div>
				  
				<label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Customer</label>
                  <div class="col-sm-4">
                 <select name="acctno"  class="form-control select2" style="width:100%">
				<option value="" selected="selected">Filter By Customer...</option>
				<?php
				$get = mysqli_query($link, "SELECT * FROM all_savingssub_transaction WHERE vendorid = '$vendorid' ORDER BY id") or die (mysqli_error($link));
				while($rows = mysqli_fetch_array($get))
				{
				    $accno = $rows['acn'];
				    $getin = mysqli_query($link, "SELECT * FROM borrowers WHERE account = '$accno'");
				    $fetch_in = mysqli_fetch_array($getin);
				?>
				<option value="<?php echo $accno; ?>"><?php echo $fetch_in['lname'].' '.$fetch_in['fname']; ?></option>
				<?php } ?>				
				</select>
                  </div>
                </div>

			 </div>
			 
			<div align="right">
              <div class="box-footer">
                	<button name="search" type="submit" class="btn bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?> btn-flat"><i class="fa fa-filter">&nbsp;Search</i></button>

              </div>
			</div>

			</form> 
			
<?php
if(isset($_POST['search'])){
	$dfrom = mysqli_real_escape_string($link, $_POST['dfrom']);
	$dto = mysqli_real_escape_string($link, $_POST['dto']);
	$acctno = mysqli_real_escape_string($link, $_POST['acctno']);
	$status = mysqli_real_escape_string($link, $_POST['status']);
	echo "<script>window.location='rsavings_report.php?id=".$_SESSION['tid']."&&dfrom=".$dfrom."&&dto=".$dto."&&st=".$status."&&acct=".$acctno."&&mid=NDI1'; </script>";
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
                  <th><div align="center">ID</div></th>
				  <th><div align="center">Invoice Code</div></th>
				  <th><div align="center">Sub. Code</div></th>
                  <th><div align="center">Ref. No.</div></th>
                  <th><div align="center">Amount</div></th>
                  <th><div align="center">Details</div></th>
                  <th><div align="center">Authentication</div></th>
                 </tr>
                </thead>
                <tbody>
<?php
$dfrom = date("Y-m-d h:m:s", strtotime($_GET['dfrom']));
$dto = date("Y-m-d h:m:s", strtotime($_GET['dto']));
$status = $_GET['st'];
$acctno = $_GET['acct'];
$select = mysqli_query($link, "SELECT * FROM all_savingssub_transaction WHERE date_time BETWEEN '$dfrom' AND '$dto' AND vendorid = '$vendorid' AND (status = '$status' OR acn = '$acctno') ORDER BY id") or die (mysqli_error($link));
while($row = mysqli_fetch_array($select))
{
$id = $row['id'];
//$merchantid = $row['merchantid'];


$select1 = mysqli_query($link, "SELECT * FROM systemset") or die (mysqli_error($link));
$row1 = mysqli_fetch_array($select1);
$currency = $row1['currency'];

?>

				<tr>
				<td align="center"><b><?php echo $id; ?></b></td>
				<td align="center"><?php echo $row['invoice_code']; ?></td>
				<td align="center"><?php echo $row['subscription_code']; ?></td>
				<td align="center"><?php echo $row['reference_no']; ?><p></p>
					<?php echo ($row['status'] == "successful" ? '<span class="label bg-blue">Success</span>' : ($row['status'] == "pending" ? '<span class="label bg-orange">Pending</span>' : '<span class="label bg-red">failed</span>')); ?>
				</td>
				<td align="center"><b><?php echo $currency.number_format($row['amount'],2,'.',','); ?></b></td>
				<td>
					<p>DETAILS:</p>
					<p><b>Full Name:</b> <?php echo $row['last_name '].' '.$row['first_name']; ?></p>
					<p><b>Customer Code:</b> <?php echo $row['customer_code']; ?></p>
				</td>
				<td>
					<p>Sensitives Details:</p>
					<p><b>Card No:</b> <?php echo $row['card_firstsix_digit '].'*****'.$row['card_lastfour_digit']; ?></p>
					<p><b>Card Type:</b> <?php echo $row['card_type']; ?></p>
					<p><b>Bank Name:</b> <?php echo $row['bank_name']; ?></p>
					<p><b>Country Code:</b> <?php echo $row['country_code']; ?></p>
				</td>
				</tr>
<?php } ?>
				</tbody>
                </table> 
</div>
               <form method="post">
                	<a href="excel_rsavings.php?dfrom=<?php echo $dfrom; ?>&&dto=<?php echo $dto; ?>&&st=<?php echo $status; ?>&&acct=<?php echo $acctno; ?>&&id=<?php echo $vendorid; ?>" class="btn bg-orange" target="_blank"><i class="fa fa-export"></i> Export to Excel</a>
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
	echo "<script>window.open('../pdf/view/pdf_rsavings1.php?dfrom=".$dfrom."&&dto=".$dto."&&st=".$status."&&acct=".$acctno."&&id=".$merchantid."', '_blank'); </script>";
}
?>


</div>	
</div>	
</div>
</div>