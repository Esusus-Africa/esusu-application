<div class="box">
	       <div class="box-body">
			<div class="panel panel-success">
            <div class="panel-heading bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">
            <h3 class="panel-title">All Wallet</h3>
            </div>



             <div class="box-body">

			 <form class="form-horizontal" method="post" enctype="multipart/form-data">

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
                    <?php echo ($create_wallet == "1") ? '<option value="all">All Individual Wallet</option>' : ''; ?>
                    <?php echo ($create_wallet == "1") ? '<option value="all1">All Agent Wallet</option>' : ''; ?>
                    <?php echo ($create_wallet == "1") ? '<option value="all2">All Corporate Wallet</option>' : ''; ?>
                    <?php echo ($individual_wallet == "1") ? '<option value="all3">All Wallet</option>' : ''; ?>

                    <option disabled>Filter By Individual Wallet User</option>
                    <?php
    				($individual_wallet != "1" && $branch_wallet != "1") ? $get = mysqli_query($link, "SELECT * FROM borrowers WHERE branchid = '$institution_id' AND virtual_acctno != '' ORDER BY id DESC") or die (mysqli_error($link)) : "";
    				($individual_wallet === "1" && $branch_wallet != "1") ? $get = mysqli_query($link, "SELECT * FROM borrowers WHERE branchid = '$institution_id' AND lofficer = '$iuid' AND virtual_acctno != '' ORDER BY id DESC") or die (mysqli_error($link)) : "";
    				($individual_wallet != "1" && $branch_wallet === "1") ? $get = mysqli_query($link, "SELECT * FROM borrowers WHERE branchid = '$institution_id' AND sbranchid = '$isbranchid' AND virtual_acctno != '' ORDER BY id DESC") or die (mysqli_error($link)) : "";
    				while($rows = mysqli_fetch_array($get))
    				{
    				?>
    				<option value="<?php echo $rows['account']; ?>"><?php echo $rows['virtual_acctno'].' - '.$rows['lname'].' '.$rows['fname'].' '.$rows['mname']; ?></option>
                    <?php } ?>
                    
                    <option disabled>Filter By Agent Wallet User</option>
                    <?php
                    ($individual_wallet != "1" && $branch_wallet != "1") ? $get2 = mysqli_query($link, "SELECT * FROM user WHERE created_by = '$institution_id' AND virtual_acctno != '' AND reg_type = 'agent' ORDER BY userid DESC") or die (mysqli_error($link)) : "";
                    ($individual_wallet === "1" && $branch_wallet != "1") ? $get2 = mysqli_query($link, "SELECT * FROM user WHERE created_by = '$institution_id' AND virtual_acctno != '' AND acctOfficer = '$iuid' AND reg_type = 'agent' ORDER BY userid DESC") or die (mysqli_error($link)) : "";
    				($individual_wallet != "1" && $branch_wallet === "1") ? $get2 = mysqli_query($link, "SELECT * FROM user WHERE created_by = '$institution_id' AND branchid = '$isbranchid' AND reg_type = 'agent' AND virtual_acctno != '' ORDER BY userid DESC") or die (mysqli_error($link)) : "";
    				while($rows2 = mysqli_fetch_array($get2))
    				{
    				?>
    				<option value="<?php echo $rows2['id']; ?>"><?php echo $rows2['virtual_acctno'].' - '.$rows2['name'].' '.$rows2['fname'].' '.$rows2['mname']; ?></option>
                    <?php } ?>
                    
                    <option disabled>Filter By Corporate Wallet User</option>
                    <?php
                    ($individual_wallet != "1" && $branch_wallet != "1") ? $get3 = mysqli_query($link, "SELECT * FROM user WHERE created_by = '$institution_id' AND virtual_acctno != '' AND reg_type = 'corporate' ORDER BY userid DESC") or die (mysqli_error($link)) : "";
                    ($individual_wallet === "1" && $branch_wallet != "1") ? $get3 = mysqli_query($link, "SELECT * FROM user WHERE created_by = '$institution_id' AND virtual_acctno != '' AND acctOfficer = '$iuid' AND reg_type = 'corporate' ORDER BY userid DESC") or die (mysqli_error($link)) : "";
    				($individual_wallet != "1" && $branch_wallet === "1") ? $get3 = mysqli_query($link, "SELECT * FROM user WHERE created_by = '$institution_id' AND branchid = '$isbranchid' AND reg_type = 'corporate' AND virtual_acctno != '' ORDER BY userid DESC") or die (mysqli_error($link)) : "";
    				while($rows3 = mysqli_fetch_array($get3))
    				{
    				?>
    				<option value="<?php echo $rows3['id']; ?>"><?php echo $rows3['virtual_acctno'].' - '.$rows3['businessName']; ?></option>
                    <?php } ?> 

                    <option disabled>Filter By Staff</option>
                    <?php
                    ($list_employee === "1" && $list_branch_employee != "1") ? $get2 = mysqli_query($link, "SELECT * FROM user WHERE created_by = '$institution_id' AND virtual_acctno != '' ORDER BY userid DESC") or die (mysqli_error($link)) : "";
    				($list_employee != "1" && $list_branch_employee === "1") ? $get2 = mysqli_query($link, "SELECT * FROM user WHERE created_by = '$institution_id' AND branchid = '$isbranchid' AND virtual_acctno != '' ORDER BY userid DESC") or die (mysqli_error($link)) : "";
    				while($rows2 = mysqli_fetch_array($get2))
    				{
    				?>
    				<option value="<?php echo $rows2['virtual_acctno']; ?>"><?php echo $rows2['virtual_acctno'].' - '.$rows2['name'].' '.$rows2['fname'].' '.$rows2['mname']; ?></option>
                    <?php } ?>   				
				</select>
                  </div>
                </div>
            
            
            <hr>
            <div class="table-responsive">
			    <table id="fetch_allwallet_data" class="table table-bordered table-striped">
			        <thead>
                    <tr>
                      <th><input type="checkbox" id="select_all"/></th>
                      <th>Actions</th>
                      <th>Acct. Type</th>
                      <th>Acct. Officer</th>
    				  <th>Acct. Number</th>
                      <th>Acct. Name</th>
                      <th>Bank Name</th>
                      <th>Phone Number</th>
                      <th>Email Address</th>
                      <th>Wallet Balance</th>
                      <th>Opening Date</th>
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