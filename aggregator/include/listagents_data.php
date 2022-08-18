<div class="row">
		
		    <section class="content">  
	        <div class="box box-success">
            <div class="box-body">
              <div class="table-responsive">
             <div class="box-body">
<form method="post">

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
			<option value="" selected="selected">Filter Client...</option>
			<option value="all">All Client</option>
			<option value="Pending">Pending</option>
			<option value="Approved">Approved</option>
			<option value="Disapproved">Disapproved</option>
			<option value="Suspend">Suspend</option>

			<option disabled>Filter By Client Type</option>
			<option value="agent">Agent/Thrift Collector</option>
			<option value="institution">Coorperative/Institution</option>
		
			<option disabled>Filter By Client</option>
			<?php
			$get = mysqli_query($link, "SELECT * FROM institution_data WHERE aggr_id = '$aggr_id' ORDER BY id DESC") or die (mysqli_error($link));
			while($rows = mysqli_fetch_array($get))
			{
			?>
			<option value="<?php echo $rows['institution_id']; ?>"><?php echo $rows['institution_name']; ?> - [<?php echo $rows['institution_id']; ?>]</option>
			<?php } ?>
	  	</select>
	</div>
 </div>
 
</div>

<hr>
<div class="table-responsive">
<table id="client_data" class="table table-bordered table-striped">
	<thead>
	<tr>
		<th><input type="checkbox" id="select_all"/></th>
		<th>Reg. Date</th>
		<th>Client ID</th>
        <th>Client Name</th>
        <th>License Number</th>
        <th>Address</th>
        <th>Official Contact</th>
        <th>Total Customer</th>
        <th>Total Transaction</th>
        <th>Wallet Balance</th>
        <th>Expiry Date</th>
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