<div class="row">
		
	       
		     <section class="content">  
	        <div class="box box-success">
            <div class="box-body">
              <div class="table-responsive">
             <div class="box-body">
<form method="post">

			<a href="dashboard.php?id=<?php echo $_SESSION['tid']; ?>&&mid=<?php echo base64_encode("401"); ?>"><button type="button" class="btn btn-flat bg-orange"><i class="fa fa-mail-reply-all"></i>&nbsp;Back</button> </a> 
	 
			<hr>
			<div class="box box-info">
				<div class="box-body">
					<div class="alert bg-orange" align="center" class="style2" style="color: #FFFFFF">TOTAL NUMBER OF LOAN REPAYMENT RECEIVED:&nbsp;
					<?php 
					$call3 = mysqli_query($link, "SELECT * FROM payments WHERE remarks = 'paid' ORDER BY id DESC") or die (mysqli_error($link));
					$num3 = mysqli_num_rows($call3);
					?>
					<?php echo $num3; ?> 
					
					</div>			
				</div>
			</div>


			<div class="box-body">

				<div class="form-group">
					<label for="" class="col-sm-1 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">From</label>
					<div class="col-sm-3">
					<input name="dfrom" type="date" id="startDate" class="form-control" placeholder="To Date: 2018-05-01">
					<span style="color: orange;">Date format: 2018-05-01</span>
					</div>
					
					<label for="" class="col-sm-1 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">To</label>
					<div class="col-sm-3">
					<input name="dto" type="date" id="endDate" class="form-control" placeholder="To Date: 2018-05-24">
					<span style="color: orange;">Date format: 2018-05-24</span>
					</div>

					<label for="" class="col-sm-1 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Filter By</label>
					<div class="col-sm-3">
					<select name="filter_by" id="filterBy" class="form-control select2" style="width:100%">
					<option value="" selected="selected">Filter By...</option>
					<!-- FILTER BY ALL LOANS REPAYMENT -->
					<option value="all">All Loan Repayment</option>

					<option disabled>Filter By Client</option>
					<?php
					$get = mysqli_query($link, "SELECT * FROM institution_data ORDER BY id DESC") or die (mysqli_error($link));
					while($rows = mysqli_fetch_array($get))
					{
					?>
					<option value="<?php echo $rows['institution_id']; ?>"><?php echo $rows['institution_name']; ?> - [<?php echo $rows['institution_id']; ?>]</option>
					<?php } ?>

					<option disabled>Filter By Client Branch</option>
    				<?php
    				$get5 = mysqli_query($link, "SELECT * FROM branches ORDER BY id DESC") or die (mysqli_error($link));
    				while($rows5 = mysqli_fetch_array($get5))
    				{
    				?>
    				<option value="<?php echo $rows5['branchid']; ?>"><?php echo $rows5['bname'].' ['.$rows5['branchid'].']'; ?></option>
    				<?php } ?>

					<option disabled>Filter By Client Vendor</option>
					<?php
					$get2 = mysqli_query($link, "SELECT * FROM user WHERE reg_type = 'vendor' ORDER BY id DESC") or die (mysqli_error($link));
					while($rows2 = mysqli_fetch_array($get2))
					{
						$vendorid = $rows2['branchid'];
						$searchVName = mysqli_query($link, "SELECT * FROM vendor_reg WHERE companyid = '$vendorid'");
						$fetchVName = mysqli_fetch_array($searchVName);
					?>
					<option value="<?php echo $vendorid; ?>"><?php echo $fetchVName['cname'].' - '.$rows2['virtual_acctno']; ?></option>
					<?php } ?>

					<option disabled>Filter By Client Customer</option>
					<?php
					$get = mysqli_query($link, "SELECT * FROM borrowers ORDER BY id DESC") or die (mysqli_error($link));
					while($rows = mysqli_fetch_array($get))
					{
					?>
					<option value="<?php echo $rows['account']; ?>"><?php echo $rows['account'].' - '.$rows['lname'].' '.$rows['fname'].' '.$rows['mname']; ?></option>
					<?php } ?>
					
					<option disabled>Filter By Client Staff / Sub-agent</option>
					<?php
					$get2 = mysqli_query($link, "SELECT * FROM user ORDER BY userid DESC") or die (mysqli_error($link));
					while($rows2 = mysqli_fetch_array($get2))
					{
					?>
					<option value="<?php echo $rows2['id']; ?>"><?php echo $rows2['name'].' '.$rows2['fname'].' '.$rows2['mname']; ?></option>
					<?php } ?>
				</select>
					</div>
				</div>
			</div>


			<hr>
            <div class="table-responsive">
			    <table id="fetch_allrepayment_data" class="table table-bordered table-striped">
			        <thead>
                    <tr>
					<th><input type="checkbox" id="select_all"/></th>
					<th>Reference ID</th>
					<th>Client Name</th>
					<th>Client Branch</th>
					<th>Client Vendor</th>
					<th>Loan Officer</th>
					<th>Loan ID</th>
					<th>Account ID</th>
					<th>Account Name</th>
					<th>Amount Payed</th>
					<th>Loan Balance</th>
					<th>Date</th>
					<th>Status</th>
                 	</tr>
                </thead>
                </table>
            </div>

</form>

					</div>
</div>	
</div>			
</div>	
</div>
			
<?php
						if(isset($_POST['delete'])){
						$idm = $_GET['id'];
							$id=$_POST['selector'];
							$N = count($id);
						if($id == ''){
						echo "<script>alert('Row Not Selected!!!'); </script>";	
						echo "<script>window.location='listpayment.php?id=".$_SESSION['tid']."&&mid=".base64_encode("408")."'; </script>";
							}
							else{
							for($i=0; $i < $N; $i++)
							{
								$search = mysqli_query($link, "SELECT * FROM pay_schedule WHERE pid ='$id[$i]'");
							    $fetch_search = mysqli_fetch_array($search);
							    $lid = $fetch_search['lid'];
							    $paid_amt = $fetch_search['payment'];
							    
							    $search_lns = mysqli_query($link, "SELECT * FROM loan_info WHERE lid = '$lid'");
							    $fetch_lns = mysqli_fetch_array($search_lns);
							    $lbal = $fetch_lns['balance'];
							    $reversed_amt = $lbal + $paid_amt;
							    
							    $result = mysqli_query($link, "UPDATE loan_info SET balance = '$reversed_amt' WHERE lid = '$lid'");
							    $result = mysqli_query($link, "DELECT FROM pay_schedule WHERE pid ='$id[$i]'");
								$result = mysqli_query($link,"DELETE FROM payments WHERE id ='$id[$i]'");
								echo "<script>alert('Row Delete Successfully!!!'); </script>";
								echo "<script>window.location='listpayment.php?id=".$_SESSION['tid']."&&mid=".base64_encode("408")."'; </script>";
							}
							}
							}
?>
       
