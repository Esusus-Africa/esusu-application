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

    <?php //echo ($delete_loan_repayment_records == '1') ? "<button type='submit' class='btn btn-flat bg-".(($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color'])."' name='delete'><i class='fa fa-times'></i>&nbsp;Delete</button>" : ""; ?>
	<?php echo ($remit_cash_payment == '1') ? "<a href='newpayments.php?id=".$_SESSION['tid']."&&mid=".base64_encode("408")."'><button type='button' class='btn btn-flat bg-".(($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color'])."'><i class='fa fa-dollar'></i>&nbsp;New Payment</button></a>" : ""; ?>

<?php
}
else{
    ?>
	<a href="dashboard.php?id=<?php echo $_SESSION['tid']; ?>&&mid=<?php echo base64_encode("401"); ?>"><button type="button" class="btn btn-flat bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>"><i class="fa fa-mail-reply-all"></i>&nbsp;Back</button> </a> 
	<?php //echo ($delete_loan_repayment_records == '1') ? '<button type="submit" class="btn btn-flat bg-'.(($myaltrow['alternate_color'] == "") ? "orange" : $myaltrow['alternate_color']).'" name="delete"><i class="fa fa-times"></i>&nbsp;Delete</button>' : ''; ?>
	<?php echo ($remit_cash_payment == '1') ? "<a href='newpayments.php?id=".$_SESSION['tid']."&&mid=".base64_encode("408")."'><button type='button' class='btn btn-flat bg-".(($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color'])."'><i class='fa fa-dollar'></i>&nbsp;New Payment</button></a>" : ""; ?>

<?php    
}
?>	
			<hr>
			<div class="box box-info">
				<div class="box-body">
					<div class="alert bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>" align="center" class="style2" style="color: #FFFFFF">TOTAL NUMBER OF LOAN REPAYMENT RECEIVED:&nbsp;
					<?php 
					($list_all_repayment == '1' && $list_individual_loan_repayment == '' && $list_branch_loan_repayment == '') ? $call3 = mysqli_query($link, "SELECT * FROM payments WHERE branchid = '$institution_id' ORDER BY id DESC") or die (mysqli_error($link)) : "";
					($list_all_repayment == '' && $list_individual_loan_repayment == '1' && $list_branch_loan_repayment == '') ? $call3 = mysqli_query($link, "SELECT * FROM payments WHERE branchid = '$institution_id' AND tid = '$iuid' ORDER BY id DESC") or die (mysqli_error($link)) : "";
					($list_all_repayment == '' && $list_individual_loan_repayment == '' && $list_branch_loan_repayment == '1') ? $call3 = mysqli_query($link, "SELECT * FROM payments WHERE branchid = '$institution_id' AND sbranchid = '$isbranchid' ORDER BY id DESC") or die (mysqli_error($link)) : "";
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
					<span style="color: <?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>;">Date format: 2018-05-01</span>
					</div>
					
					<label for="" class="col-sm-1 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">To</label>
					<div class="col-sm-3">
					<input name="dto" type="date" id="endDate" class="form-control" placeholder="To Date: 2018-05-24">
					<span style="color: <?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>;">Date format: 2018-05-24</span>
					</div>

					<label for="" class="col-sm-1 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Filter By</label>
					<div class="col-sm-3">
					<select name="filter_by" id="filterBy" class="form-control select2" style="width:100%">
					<option value="" selected="selected">Filter By...</option>
					<!-- FILTER BY ALL LOANS REPAYMENT, INDIVIDUAL LOANS REPAYMENT OR BRANCH LOANS REPAYMENT -->
					<?php echo ($list_all_repayment === '1' && $list_individual_loan_repayment === '' && $list_branch_loan_repayment === '' ? '<option value="all">All Repayment</option>' : ($list_all_repayment === '' && $list_individual_loan_repayment === '1' && $list_branch_loan_repayment === '' ? '<option value="all1">All Repayment</option>' : ($list_all_repayment === '' && $list_individual_loan_repayment === '' && $list_branch_loan_repayment === '1' ? '<option value="all2">All Repayment</option>' : ''))); ?>

					<option disabled>Filter By Customer</option>
					<?php
					($individual_customer_records != "1" && $branch_customer_records != "1") ? $get = mysqli_query($link, "SELECT * FROM borrowers WHERE branchid = '$institution_id' ORDER BY id DESC") or die (mysqli_error($link)) : "";
					($individual_customer_records === "1" && $branch_customer_records != "1") ? $get = mysqli_query($link, "SELECT * FROM borrowers WHERE branchid = '$institution_id' AND lofficer = '$iuid' ORDER BY id DESC") or die (mysqli_error($link)) : "";
					($individual_customer_records != "1" && $branch_customer_records === "1") ? $get = mysqli_query($link, "SELECT * FROM borrowers WHERE branchid = '$institution_id' AND sbranchid = '$isbranchid' ORDER BY id DESC") or die (mysqli_error($link)) : "";
					while($rows = mysqli_fetch_array($get))
					{
					?>
					<option value="<?php echo $rows['account']; ?>"><?php echo $rows['account'].' - '.$rows['lname'].' '.$rows['fname'].' '.$rows['mname']; ?></option>
					<?php } ?>

					
					<option disabled>Filter By Staff / Sub-agent</option>
					<?php
					($list_employee === "1" && $list_branch_employee != "1") ? $get2 = mysqli_query($link, "SELECT * FROM user WHERE created_by = '$institution_id' ORDER BY userid DESC") or die (mysqli_error($link)) : "";
					($list_employee != "1" && $list_branch_employee === "1") ? $get2 = mysqli_query($link, "SELECT * FROM user WHERE created_by = '$institution_id' AND branchid = '$isbranchid' ORDER BY userid DESC") or die (mysqli_error($link)) : "";
					while($rows2 = mysqli_fetch_array($get2))
					{
					?>
					<option value="<?php echo $rows2['id']; ?>"><?php echo $rows2['name'].' '.$rows2['fname'].' '.$rows2['mname']; ?></option>
					<?php } ?>

					<?php
    				////TO BE ACCESSIBLE BY THE SUPER ADMIN ONLY
    				if($list_branches === "1")
    				{
    				?>
    				<option disabled>Filter By Branch</option>
    				<?php
    				$get5 = mysqli_query($link, "SELECT * FROM branches WHERE created_by = '$institution_id' ORDER BY id") or die (mysqli_error($link));
    				while($rows5 = mysqli_fetch_array($get5))
    				{
    				?>
    				<option value="<?php echo $rows5['branchid']; ?>"><?php echo $rows5['bname'].' ['.$rows5['branchid'].']'; ?></option>
    				<?php } ?>
    				<?php
    				}
    				else{
    				    //nothing
    				}
    				?>
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
					<th>Branch</th>
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
