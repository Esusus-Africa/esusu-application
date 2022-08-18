<div class="row">
		
	       
		    <section class="content">  
	        <div class="box box-success">
            <div class="box-body">
              <div class="table-responsive">
             <div class="box-body">
<form method="post">

			 <a href="dashboard.php?tid=<?php echo $_SESSION['tid']; ?>&&mid=NDkw"><button type="button" class="btn btn-flat bg-orange"><i class="fa fa-mail-reply-all"></i>&nbsp;Back</button> </a> 
	
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
						<option value="all">All Product Subscription</option>
						<option value="Pending">Pending</option>
						<option value="Approved">Approved</option>
						<option value="Stop">Stop</option>

            <option disabled>Filter By Customer</option>
						<?php
						$get4 = mysqli_query($link, "SELECT DISTINCT(acn) FROM savings_subscription WHERE vendorid = '$vendorid' ORDER BY id DESC") or die (mysqli_error($link));
						while($rows4 = mysqli_fetch_array($get4))
						{
              $myacn = $rows4['acn'];
              $get5 = mysqli_query($link, "SELECT * FROM borrowers WHERE branchid = '$vcreated_by' AND (account = '$myacn' OR virtual_acctno = '$myacn')");
              $rows5 = mysqli_fetch_array($get5);
            ?>
						<option value="<?php echo $myacn; ?>"><?php echo $rows5['virtual_acctno'].' - '.$rows5['lname'].' '.$rows5['fname'].' '.$rows5['mname']; ?></option>
						<?php } ?>

						<option disabled>Filter By Agent</option>
						<?php
						$get6 = mysqli_query($link, "SELECT DISTINCT(agentid) FROM savings_subscription WHERE vendorid = '$vendorid' ORDER BY id DESC") or die (mysqli_error($link));
						while($rows6 = mysqli_fetch_array($get6))
						{
              $agentid = $rows6['agentid'];
              $get7 = mysqli_query($link, "SELECT * FROM user WHERE created_by = '$vcreated_by' AND id = '$agentid'");
              $rows7 = mysqli_fetch_array($get7);
						?>
						<option value="<?php echo $rows7['id']; ?>"><?php echo $rows7['virtual_acctno'].' - '.$rows7['name'].' '.$rows7['lname'].' '.$rows7['mname']; ?></option>
						<?php } ?>
					</select>
				</div>
			</div>
			
			</div>

			<hr>
			<div class="table-responsive">
			<table id="savings_sub_data" class="table table-bordered table-striped">
				<thead>
				<tr>
				  <th><input type="checkbox" id="select_all"/></th>
				  <th>Action</th>
				  <th>Agent Name</th>
				  <th>Plan Name</th>
				  <th>Customer Name</th>
				  <th>Subscription Code</th>
				  <th>Amount</th>
				  <th>Duration</th>
				  <th>Status</th>
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