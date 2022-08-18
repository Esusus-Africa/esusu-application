<div class="row">
		
	       
		    <section class="content">  
	        <div class="box box-success">
            <div class="box-body">
              <div class="table-responsive">
             <div class="box-body">
<form method="post">

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
						<option value="all">All Product Transaction</option>
						<option value="pending">Pending</option>
						<option value="successful">Successful</option>

						<option disabled>Filter By Customer</option>
						<?php
						$get4 = mysqli_query($link, "SELECT DISTINCT(acn) FROM all_savingssub_transaction WHERE vendorid = '$vendorid' ORDER BY id DESC") or die (mysqli_error($link));
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
						$get6 = mysqli_query($link, "SELECT DISTINCT(agentid) FROM all_savingssub_transaction WHERE vendorid = '$vendorid' ORDER BY id DESC") or die (mysqli_error($link));
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
			<table id="savings_trans_data" class="table table-bordered table-striped">
				<thead>
				<tr>
				  <th><input type="checkbox" id="select_all"/></th>
              <th>Invoice Code</th>
				      <th>Agent Name</th>
              <th>Customer Name</th>
              <th>Plan Name</th>
              <th>Subscription Code</th>
              <th>Reference No.</th>
              <th>Amount</th>
              <th>Date/Time</th>
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