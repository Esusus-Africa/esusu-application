<div class="row">
		
	       
		    <section class="content">  
	        <div class="box box-success">
            <div class="box-body">
              <div class="table-responsive">
             <div class="box-body">

<form method="post">

<?php echo ($delete_aggregator == 1) ? '<button type="submit" class="btn btn-flat bg-orange" name="delete"><i class="fa fa-times"></i>&nbsp;Multiple Delete</button>' : ''; ?>

<hr>

			<div class="box-body">

			<div class="box-body">

			<div class="form-group">
				<label for="" class="col-sm-1 control-label" style="color:blue;">From</label>
				<div class="col-sm-3">
					<input name="dfrom" type="date" id="startDate" class="form-control" placeholder="To Date: 2018-05-01">
					<span style="color: blue;">Date format: 2018-05-01</span>
				</div>
							
				<label for="" class="col-sm-1 control-label" style="color:blue;">End Date</label>
				<div class="col-sm-3">
					<input name="dto" type="date" id="endDate" class="form-control" placeholder="To Date: 2018-05-24">
					<span style="color: blue;">Date format: 2018-05-24</span>
				</div>

				<label for="" class="col-sm-1 control-label" style="color:blue;">Filter By</label>
				<div class="col-sm-3">
					<select name="ttype" id="transType" class="form-control select2" style="width:100%">
						<option value="" selected="selected">Filter Aggregator...</option>
						<option value="all">All Aggregator</option>

						<option disabled>Filter By Individual Aggregator</option>
						<?php
						$get = mysqli_query($link, "SELECT * FROM aggregator ORDER BY id DESC") or die (mysqli_error($link));
						while($rows = mysqli_fetch_array($get))
						{
						?>
						<option value="<?php echo $rows['aggr_id']; ?>"><?php echo $rows['lname'].' '.$rows['fname'].' '.$rows['mname']; ?></option>
						<?php } ?>
					</select>
				</div>
			</div>
			
			</div>


			<hr>
			<div class="table-responsive">
			<table id="aggregator_data" class="table table-bordered table-striped">
				<thead>
				<tr>
				  <th><input type="checkbox" id="select_all"/></th>
				  <th>Actions</th>
				  <th>Aggregator ID</th>
                  <th>Name</th>
				  <th>Phone</th>
				  <th>Email</th>
				  <th>Username</th>
				  <th>Activities</th>
                  <th>Commission</th>
                  <th>Wallet Balance</th>
                  <th>Reg. Date</th>
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
								echo "<script>window.location='listaggregators.php?id=".$_SESSION['tid']."&&mid=NDE5'; </script>";
							}
							else{
							for($i=0; $i < $N; $i++)
							{
								$search_aggr = mysqli_query($link, "SELECT * FROM aggregator WHERE id ='$id[$i]'");
								$fetch_aggr = mysqli_fetch_array($search_aggr);
								$aggr_ide = $fetch_aggr['aggr_id'];
								$aggr_logo = "../".$fetch_aggr['logo'];

								$search_doc = mysqli_query($link,"SELECT * FROM institution_legaldoc WHERE instid ='$aggr_ide'");
								$fetch_doc = mysqli_fetch_array($search_doc);
								$document = "../".$fetch_doc['document'];

								unlink($document);
								unlink($aggr_logo);
								$result = mysqli_query($link,"DELETE FROM institution_legaldoc WHERE instid = '$aggr_ide'");
								$result = mysqli_query($link, "DELETE FROM user WHERE id = '$aggr_ide'");
								$result = mysqli_query($link, "DELETE FROM virtual_account WHERE userid = '$aggr_ide'");
								$result = mysqli_query($link,"DELETE FROM aggregator WHERE id ='$id[$i]'");
								echo "<script>alert('Account Deleted Successfully!!!'); </script>";
								echo "<script>window.location='listaggregators.php?id=".$_SESSION['tid']."&&mid=NDE5'; </script>";
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