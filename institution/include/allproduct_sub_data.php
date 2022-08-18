<div class="row">
		
	       
		    <section class="content">  
	        <div class="box box-success">
            <div class="box-body">
              <div class="table-responsive">
             <div class="box-body">
<form method="post">

<?php
if(isMobileDevice()){
    //Your content or code for mobile devices goes here
?>
            
            <a href="dashboard.php?id=<?php echo $_SESSION['tid']; ?>&&mid=<?php echo base64_encode("401"); ?>"><button type="button" class="btn bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>"><i class="fa fa-mail-reply-all"></i>&nbsp;Back</button></a>
            <!--<p><a href="make_wrequest.php?tid=<?php //echo $_SESSION['tid']; ?>&&acn=<?php //echo $_SESSION['acctno']; ?>&&mid=NDA3"><button type="button" class="btn bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>"><i class="fa fa-plus"></i>&nbsp;Withdrawal Request</button></a> </p>-->

<?php
}
else{
    ?>
			 <a href="dashboard.php?id=<?php echo $_SESSION['tid']; ?>&&mid=<?php echo base64_encode("401"); ?>"><button type="button" class="btn bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>"><i class="fa fa-mail-reply-all"></i>&nbsp;Back</button></a>
			 <!--<a href="all_wrequest.php?tid=<?php //echo $_SESSION['tid']; ?>&&acn=<?php //echo $_SESSION['acctno']; ?>&&mid=NDA3"><button type="button" class="btn bg-<?php //echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>"><i class="fa fa-eye"></i>&nbsp;All Withdrawal Request</button></a>-->
			 <!--<a href="make_wrequest.php?tid=<?php //echo $_SESSION['tid']; ?>&&acn=<?php //echo $_SESSION['acctno']; ?>&&mid=NDA3"><button type="button" class="btn bg-<?php //echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>"><i class="fa fa-plus"></i>&nbsp;Make Withdrawal Request</button></a>--> 
<?php    
}
?>	
	<hr>		

  <div class="box-body">

			<div class="box-body">

			<div class="form-group">
				<label for="" class="col-sm-1 control-label" style="color:blue;">From</label>
				<div class="col-sm-3">
					<input name="dfrom" type="date" id="startDate" class="form-control" placeholder="To Date: 2018-05-01" required>
					<span style="color: blue;">Date format: 2018-05-01</span>
				</div>
							
				<label for="" class="col-sm-1 control-label" style="color:blue;">End Date</label>
				<div class="col-sm-3">
					<input name="dto" type="date" id="endDate" class="form-control" placeholder="To Date: 2018-05-24" required>
					<span style="color: blue;">Date format: 2018-05-24</span>
				</div>

				<label for="" class="col-sm-1 control-label" style="color:blue;">Filter By</label>
				<div class="col-sm-3">
					<select name="ttype" id="transType" class="form-control select2" style="width:100%" required>
						<option value="" selected="selected">Filter By...</option>
						<option value="all">All Savings Subscription</option>
						<option value="Pending">Pending</option>
						<option value="Approved">Approved</option>
						<option value="Stop">Stop</option>

						<option disabled>Filter By Customer</option>
						<?php
            ($individual_customer_records != "1" && $branch_customer_records != "1") ? $get4 = mysqli_query($link, "SELECT * FROM borrowers WHERE branchid = '$institution_id' AND virtual_acctno != '' ORDER BY id") or die (mysqli_error($link)) : "";
    				($individual_customer_records === "1" && $branch_customer_records != "1") ? $get4 = mysqli_query($link, "SELECT * FROM borrowers WHERE branchid = '$institution_id' AND lofficer = '$iuid' AND virtual_acctno != '' ORDER BY id") or die (mysqli_error($link)) : "";
    				($individual_customer_records != "1" && $branch_customer_records === "1") ? $get4 = mysqli_query($link, "SELECT * FROM borrowers WHERE branchid = '$institution_id' AND sbranchid = '$isbranchid' AND virtual_acctno != '' ORDER BY id") or die (mysqli_error($link)) : "";
						while($rows4 = mysqli_fetch_array($get4))
						{
						?>
						<option value="<?php echo $rows4['account']; ?>"><?php echo $rows4['virtual_acctno'].' - '.$rows4['lname'].' '.$rows4['fname'].' '.$rows4['mname']; ?></option>
						<?php } ?>
            
            <?php
    				////TO BE ACCESSIBLE BY PEOPLE WHO HAVE ACCESS TO VIEW VENDOR
    				if($list_vendor === "1")
    				{
    				?>

						<option disabled>Filter By Vendor</option>
						<?php
						$get2 = mysqli_query($link, "SELECT * FROM user WHERE created_by = '$institution_id' AND reg_type = 'vendor' ORDER BY id DESC") or die (mysqli_error($link));
						while($rows2 = mysqli_fetch_array($get2))
						{
						$vendorid = $rows2['branchid'];
						$searchVName = mysqli_query($link, "SELECT * FROM vendor_reg WHERE companyid = '$vendorid'");
						$fetchVName = mysqli_fetch_array($searchVName);
						?>
						<option value="<?php echo $vendorid; ?>"><?php echo $fetchVName['cname'].' - '.$rows2['virtual_acctno']; ?></option>
						<?php } ?>
            
            <?php
            }
            else{
              echo "";
            }
            ?>

						<option disabled>Filter By Staff/Agent</option>
						<?php
						($list_employee === "1" && $list_branch_employee != "1") ? $get6 = mysqli_query($link, "SELECT * FROM user WHERE created_by = '$institution_id' ORDER BY id") or die (mysqli_error($link)) : "";
            ($list_employee != "1" && $list_branch_employee === "1") ? $get6 = mysqli_query($link, "SELECT * FROM user WHERE created_by = '$institution_id' AND branchid = '$isbranchid' ORDER BY id") or die (mysqli_error($link)) : "";
						while($rows6 = mysqli_fetch_array($get6))
						{
						?>
						<option value="<?php echo $rows6['id']; ?>"><?php echo $rows6['name'].' '.$rows6['lname'].' '.$rows6['mname']; ?></option>
						<?php } ?>
					</select>
				</div>
			</div>
			
			</div>

			<hr>
			<div class="table-responsive">
			<table id="allproduct_sub_data" class="table table-bordered table-striped">
				<thead>
				<tr>
				  <th><input type="checkbox" id="select_all"/></th>
          <th>Action</th>
          <th>Type</th>
          <th>Vendor Name</th>
          <th>Customer Name</th>
          <th>Plan</th>
          <th>Sub. Code</th>
          <th>Amount</th>
          <th>Duration</th>
          <th>Status</th>
          <th>Activated By</th>
          <th>Activation Date</th>
          <th>Maturity Date</th>
          <th>Expected Amount</th>
				</tr>
				</thead>
			</table>
			</div>

			</div> 
						
</form>
				

              </div>

	
</div>	
</div>
</div>	


			
</div>