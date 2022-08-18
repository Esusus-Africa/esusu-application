<div class="box">
	       <div class="box-body">
			<div class="panel panel-success">
            <div class="panel-heading bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">
            <h3 class="panel-title"> <a href="dashboard.php?id=<?php echo $_SESSION['tid']; ?>&&mid=NDAx"><button type="button" class="btn btn-flat bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'blue' : $myaltrow['alternate_color']; ?>"><i class="fa fa-mail-reply-all"></i>&nbsp;Back</button> </a> <i class="fa fa-area-chart"></i>  Report</h3>
            </div>

             <div class="box-body">
			 
			<form class="form-horizontal" method="post" enctype="multipart/form-data">
			 <div class="alert bg-blue"> The Identity Verification Report shows the <b>Operator-in-charge, Branch, Total Verified, Total UnVerified, Total Charges</b>. </div>
			 
			 
			 <div class="box-body">

				<div class="box-body">

				<div class="form-group">
					<label for="" class="col-sm-1 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">From</label>
					<div class="col-sm-3">
					<input name="dfrom" type="date" id="startDate" class="form-control" placeholder="To Date: 2018-05-01">
					<span style="color: <?php echo ($myaltrow['alternate_color'] == '') ? 'blue' : $myaltrow['alternate_color']; ?>;">Date format: 2018-05-01</span>
					</div>
					
					<label for="" class="col-sm-1 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">To</label>
					<div class="col-sm-3">
					<input name="dto" type="date" id="endDate" class="form-control" placeholder="To Date: 2018-05-24">
					<span style="color: <?php echo ($myaltrow['alternate_color'] == '') ? 'blue' : $myaltrow['alternate_color']; ?>;">Date format: 2018-05-24</span>
					</div>

					<label for="" class="col-sm-1 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Filter By</label>
					<div class="col-sm-3">
					<select name="filter_by" id="filterBy" class="form-control select2" style="width:100%">
					<option value="" selected="selected">Filter By...</option>
					<!-- FILTER BY ALL COLLECTION REPORT -->
					<option value="all">All Report</option>

					<option disabled>Filter By Staff</option>
                    <?php
                        ($list_employee == "1" && $list_branch_employee != "1") ? $rows2 = mysqli_query($link, "SELECT * FROM user WHERE created_by = '$institution_id' ORDER BY id") : "";
                        ($list_employee != "1" && $list_branch_employee == "1") ? $rows2 = mysqli_query($link, "SELECT * FROM user WHERE created_by = '$institution_id' AND branchid = '$isbranchid' ORDER BY id") : "";
                        while($rows2 = mysqli_fetch_array($rows2))
                        {
                        ?>
                        <option value="<?php echo $rows2['id']; ?>"><?php echo $rows2['name'].' '.$rows2['fname'].' '.$rows2['mname']; ?></option>
                    <?php } ?>

                    <option disabled>Filter By Branch</option>
					<?php
					$get5 = mysqli_query($link, "SELECT * FROM branches WHERE created_by = '$institution_id' ORDER BY id DESC") or die (mysqli_error($link));
					while($rows5 = mysqli_fetch_array($get5))
					{
					?>
					<option value="<?php echo $rows5['branchid']; ?>"><?php echo $rows5['bname'].' ['.$rows5['branchid'].']'; ?></option>
					<?php } ?>
				</select>
					</div>
				</div>
				</div>
			
			
			<hr>		

			 <div class="table-responsive">
			 <table id="fetch_idVerificationsrpt_data" class="table table-bordered table-striped">
                <thead>
                <tr>
				  <th><input type="checkbox" id="select_all"/></th>
				  <th>S/No</th>
				  <th>Operator-in-Charge</th>
				  <th>Branch</th>
				  <th>Total Verified</th>
				  <th>Total UnVerified</th>
                  <th>Total NIN VCharges {<?php echo $icurrency; ?>}</th>
                  <th>Total BVN VCharges {<?php echo $icurrency; ?>}</th>
                  <th>Grand Total {<?php echo $icurrency; ?>}</th>
                 </tr>
                </thead>
                </table>
				</div>
				
			</div>


</div>	
</div>	
</div>
</div>