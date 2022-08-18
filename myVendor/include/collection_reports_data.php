<div class="box">
	       <div class="box-body">
			<div class="panel panel-success">
            <div class="panel-heading bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">
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
				  <span style="color: orange;">Date should be in this format: 2018-05-01</span>
                  </div>
				  
				  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">To</label>
                  <div class="col-sm-4">
                  <input name="dto" type="date" class="form-control" placeholder="To Date: 2018-05-24">
				  <span style="color: orange;">Date should be in this format: 2018-05-24</span>
                  </div>
                  </div>
                  
            
            <div class="form-group">
                 <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Filter By:</label>
                <div class="col-sm-4">
                <select name="users"  class="form-control select2" style="width:100%" required>
                    
                    <option value="" selected="selected">Filter By?</option>
    				<option disabled>FILTER BY LOAN ID</option>
    				<?php
    				$get = mysqli_query($link, "SELECT * FROM loan_info WHERE vendorid = '$vendorid' ORDER BY id") or die (mysqli_error($link));
    				while($rows = mysqli_fetch_array($get))
    				{
    				?>
    				<option value="<?php echo $rows['lid']; ?>"><?php echo $rows['lid']; ?></option>
    				<?php } ?>
    				
				</select>
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
	$users = mysqli_real_escape_string($link, $_POST['users']);
	echo "<script>window.location='collection_reports.php?id=".$_SESSION['tid']."&&dfrom=".$dfrom."&&dto=".$dto."&&oprt=".$users."&&mid=NDI1'; </script>";
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
                  <th>Status</th>
                  <th>Reference ID</th>
				  <th>Loan ID</th>
				  <th>Loan Product</th>
				  <th>Account ID</th>
				  <th>Account Name</th>
                  <th>Amount Paid</th>
                  <th>Pay Date</th>
                  <th>Loan Balance</th>
                  <th>Staff</th>
                 </tr>
                </thead>
                <tbody>
<?php
$dfrom = $_GET['dfrom'];
$dto = $_GET['dto'];
$users = $_GET['oprt'];
$select = mysqli_query($link, "SELECT * FROM payments WHERE pay_date BETWEEN '$dfrom' AND '$dto' AND vendorid = '$vendorid' AND lid = '$users' OR (lid = '$users') ORDER BY id") or die (mysqli_error($link));
while($row = mysqli_fetch_array($select))
{
$id = $row['id'];
$borrower = $row['borrower'];
$lid = $row['lid'];
$tid = $row['tid'];

$search_lid = mysqli_query($link, "SELECT * FROM loan_info WHERE lid = '$lid'");
$fetch_lid = mysqli_fetch_array($search_lid);
$lproductid = $fetch_lid['lproduct'];

$search_lp = mysqli_query($link, "SELECT * FROM loan_product WHERE id = '$lproductid'");
$fetch_lp = mysqli_fetch_array($search_lp);

$search_staff = mysqli_query($link, "SELECT * FROM user WHERE id = '$tid'");
$fetch_staff = mysqli_fetch_array($search_staff);
$staff_name = $fetch_staff['name'];
?>

				<tr>
				<td><div style="color: blue;"><b><?php echo $row['remarks']; ?></b></div></td>
				<td><?php echo $row['refid']; ?></td>
				<td><?php echo $lid; ?></td>
				<td><?php echo $fetch_lp['pname']; ?></td>
				<td><?php echo $row['account_no']; ?></td>
				<td><?php echo $row['customer']; ?></td>
				<td><?php echo $icurrency.number_format($row['amount_to_pay'],2,'.',','); ?></td>
				<td><b><?php echo $row['pay_date']; ?></b></td>
				<td><?php echo $icurrency.number_format($row['loan_bal'],2,'.',','); ?></td>
				<td><b><?php echo $staff_name; ?></b></td>
				</tr>

<?php } ?>
				</tbody>
                </table> 

            </div>
            <form method="post">
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
	echo "<script>window.open('../pdf/view/pdf_collection.php?dfrom=".$dfrom."&&dto=".$dto."&&oprt=".$users."&&comny=".$vcreated_by."', '_blank'); </script>";
}
?>


</div>	
</div>	
</div>
</div>