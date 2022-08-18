<div class="box">
	       <div class="box-body">
			<div class="panel panel-success">
            <div class="panel-heading bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">
            <h3 class="panel-title"><i class="fa fa-area-chart"></i> Loan Report</h3>
			</div>
			

			<div class="box-body">

				<div class="box-body">

				<div class="form-group">
					<label for="" class="col-sm-1 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">From</label>
					<div class="col-sm-5">
					<input name="dfrom" type="date" id="startDate" class="form-control" placeholder="To Date: 2018-05-01">
					<span style="color: <?php echo ($myaltrow['alternate_color'] == '') ? 'blue' : $myaltrow['alternate_color']; ?>;">Date format: 2018-05-01</span>
					</div>
					
					<label for="" class="col-sm-1 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">To</label>
					<div class="col-sm-5">
					<input name="dto" type="date" id="endDate" class="form-control" placeholder="To Date: 2018-05-24">
					<span style="color: <?php echo ($myaltrow['alternate_color'] == '') ? 'blue' : $myaltrow['alternate_color']; ?>;">Date format: 2018-05-24</span>
					</div>
				</div>

                <div class="form-group">
					<label for="" class="col-sm-1 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Filter By</label>
					<div class="col-sm-5">
					<select name="filter_by" id="filterBy" class="form-control select2" style="width:100%">
					<option value="" selected="selected">Filter By Staff / Branch...</option>
					<!-- FILTER BY ALL COLLECTION REPORT -->
					<option value="all">All</option>

                    <option disabled>Filter By Branch</option>
					<?php
					$get5 = mysqli_query($link, "SELECT * FROM branches WHERE created_by = '$institution_id' ORDER BY id DESC") or die (mysqli_error($link));
					while($rows5 = mysqli_fetch_array($get5))
					{
					?>
					<option value="<?php echo $rows5['branchid']; ?>"><?php echo $rows5['bname'].' ['.$rows5['branchid'].']'; ?></option>
					<?php } ?>

                    <!-- FILTER BY ALL  -->
                    <option disabled>Filter By Staff / Sub-agent</option>
                    <?php
                    ($list_employee === "1" && $list_branch_employee != "1") ? $get2 = mysqli_query($link, "SELECT * FROM user WHERE created_by = '$institution_id' ORDER BY userid DESC") or die (mysqli_error($link)) : "";
                    ($list_employee != "1" && $list_branch_employee === "1") ? $get2 = mysqli_query($link, "SELECT * FROM user WHERE created_by = '$institution_id' AND branchid = '$isbranchid' ORDER BY userid DESC") or die (mysqli_error($link)) : "";
                    while($rows2 = mysqli_fetch_array($get2))
                    {
                    ?>
                        <option value="<?php echo $rows2['id']; ?>"><?php echo $rows2['name'].' '.$rows2['lname']; ?></option>
                    <?php } ?>
				    </select>
					</div>

                    <label for="" class="col-sm-1 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Product</label>
					<div class="col-sm-5">
					<select name="loanProduct" id="loanProduct" class="form-control select2" style="width:100%">
					<option value="" selected="selected">Filter By Loan Product...</option>
                    <option value="all">All Loan Products</option>
                    <?php
                    $getlp = mysqli_query($link, "SELECT * FROM loan_product WHERE merchantid = '$institution_id' ORDER BY id DESC") or die (mysqli_error($link));
                    while($rowslp = mysqli_fetch_array($getlp))
                    {
                    ?>
                    <option value="<?php echo $rowslp['id']; ?>"><?php echo $rowslp['pname'] . ' - ' . $rowslp['duration'] . ' ' . (($rowslp['tenor'] == "Weekly") ? "Week(s)" : "Month(s)"); ?></option>
                    <?php } ?>
				    </select>
					</div>
				</div>

				</div>


				<hr>
				<div class="table-responsive">
				<table id="fetch_loanrpt_data" class="table table-bordered table-striped">
					<thead>
					<tr>
						<th><input type="checkbox" id="select_all"/></th>
						<th>S/No</th>
						<th>Staff Name</th>
				        <th>Branch</th>
						<th>Loan Product</th>
						<th>Loan Type</th>
						<th>Principal Amount {<?php echo $icurrency; ?>}</th>
						<th>Interest Rate</th>
						<th>Rate Method</th>
                        <th>Amount to Pay {<?php echo $icurrency; ?>}</th>
						<th>Loan Balance {<?php echo $icurrency; ?>}</th>
						<th>Overdue {<?php echo $icurrency; ?>}</th>
						<th>Total Repaid {<?php echo $icurrency; ?>}</th>
					</tr>
				</thead>
				</table>
				</div>
				
			</div>



</div>	
</div>
</div>