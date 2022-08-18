<div class="row">
		
	       
		    <section class="content">  
	        <div class="box box-success">
            <div class="box-body">
              <div class="table-responsive">
             <div class="box-body">
<form method="post">
	 
<?php echo ($delete_client == 1) ? '<button type="submit" class="btn btn-flat bg-orange" name="delete"><i class="fa fa-times"></i>&nbsp;Multiple Delete</button>' : ''; ?>
	
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
			<option value="" selected="selected">Filter Client...</option>
			<option value="all">All Client</option>
			<option value="Pending">Pending</option>
			<option value="Approved">Approved</option>
			<option value="Disapproved">Disapproved</option>
			<option value="Suspend">Suspend</option>

			<option disabled>Filter By Client Type</option>
			<option value="agent">Agent</option>
			<option value="institution">Institution</option>
			<option value="merchant">Merchant</option>
		
			<option disabled>Filter By Client</option>
			<?php
			$get = mysqli_query($link, "SELECT * FROM institution_data ORDER BY id DESC") or die (mysqli_error($link));
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
		<th>Actions</th>
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


						<?php
						if(isset($_POST['delete'])){
						$idm = $_GET['id'];
							$id=$_POST['selector'];
							$N = count($id);
						if($id == ''){
						echo "<script>alert('Row Not Selected!!!'); </script>";	
						echo "<script>window.location='listinstitution?id=".$_SESSION['tid']."&&mid=NDE5'; </script>";
							}
							else{
							for($i=0; $i < $N; $i++)
							{
								$search_cmem = mysqli_query($link,"SELECT * FROM institution_data WHERE id ='$id[$i]'");
								$fetch_cmem = mysqli_fetch_array($search_cmem);
								$instiid = $fetch_cmem['institution_id'];
								$instlogo = "../".$fetch_cmem['institution_logo'];

								$search_doc = mysqli_query($link,"SELECT * FROM institution_legaldoc WHERE instid ='$instiid'");
								$fetch_doc = mysqli_fetch_array($search_doc);
								$document = "../".$fetch_doc['document'];

								unlink($document);
								unlink($instlogo);
								$result = mysqli_query($link,"DELETE FROM institution_legaldoc WHERE instid = '$instiid'");
								$result = mysqli_query($link,"DELETE FROM user WHERE created_by = '$instiid'");
								$result = mysqli_query($link,"DELETE FROM borrowers WHERE branchid = '$instiid'");
								$result = mysqli_query($link,"DELETE FROM institution_data WHERE id ='$id[$i]'");
								echo "<script>alert('Row Delete Successfully!!!'); </script>";
								echo "<script>window.location='listinstitution?id=".$_SESSION['tid']."&&mid=NDE5'; </script>";
							}
							}
							}
						?>	
						
</form>
				

              </div>


	
</div>	
</div>
</div>	
</div>