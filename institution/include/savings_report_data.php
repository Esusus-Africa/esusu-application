<div class="box">
	       <div class="box-body">
			<div class="panel panel-success">
            <div class="panel-heading bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">
            <h3 class="panel-title"> <a href="dashboard.php?id=<?php echo $_SESSION['tid']; ?>&&mid=NDAx"><button type="button" class="btn btn-flat bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'blue' : $myaltrow['alternate_color']; ?>"><i class="fa fa-mail-reply-all"></i>&nbsp;Back</button> </a> <i class="fa fa-area-chart"></i>  Report</h3>
            </div>

             <div class="box-body">
			 
			<form class="form-horizontal" method="post" enctype="multipart/form-data">
			 <div class="alert bg-blue"> The Savings Collection Report shows the <b>Staff-in-charge, Deposit, Withdrawal Amount, Withdrawal charges, Total Till Balance, Total Unsettled Balance.</b>. </div>
			 
			 
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
					<option value="all">All Savings Report</option>

					<option disabled>Filter By Staff / Sub-agent</option>
					<?php
					$get2 = mysqli_query($link, "SELECT * FROM user WHERE created_by = '$institution_id' ORDER BY userid DESC") or die (mysqli_error($link));
					while($rows2 = mysqli_fetch_array($get2))
					{
					?>
					<option value="<?php echo $rows2['id']; ?>"><?php echo $rows2['name'].' '.$rows2['lname']; ?></option>
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
			 <table id="fetch_savingsrpt_data" class="table table-bordered table-striped">
                <thead>
                <tr>
				  <th><input type="checkbox" id="select_all"/></th>
				  <th>S/No</th>
				  <th>Staff Name</th>
				  <th>Branch</th>
				  <th>Pending Deposit {<?php echo $icurrency; ?>}</th>
				  <th>Approved Deposit {<?php echo $icurrency; ?>}</th>
				  <th>Pending Withdrawal {<?php echo $icurrency; ?>}</th>
				  <th>Approved Withdrawal {<?php echo $icurrency; ?>}</th>
				  <th>Approved Charges {<?php echo $icurrency; ?>}</th>
				  <th>Till Balance {<?php echo $icurrency; ?>}</th>
				  <th>Unsettled Balance {<?php echo $icurrency; ?>}</th>
				  <th>Commission Balance {<?php echo $icurrency; ?>}</th>
                 </tr>
                </thead>
                </table>
				</div>
				
			</div>


</div>	
</div>	
</div>
</div>