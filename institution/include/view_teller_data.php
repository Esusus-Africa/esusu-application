<div class="row">
    
		    <section class="content">  
	        <div class="box box-success">
            <div class="box-body">
              <div class="table-responsive">
             <div class="box-body">
<form method="post">
			 <a href="dashboard.php?id=<?php echo $_SESSION['tid']; ?>&&mid=<?php echo base64_encode("401"); ?>"><button type="button" class="btn btn-flat bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>"><i class="fa fa-mail-reply-all"></i>&nbsp;Back</button></a> 
	
			 <?php echo ($delete_till_account == '1') ? '<button type="submit" class="btn btn-flat bg-'.$myaltrow['alternate_color'].' == "" ? orange : '.$myaltrow['alternate_color'].'" name="delete"><i class="fa fa-times"></i>&nbsp;Delete</button>' : ''; ?>		
	
			 <hr>	

			 <div class="box-body">

			<div class="box-body">

			<div class="form-group">
				<label for="" class="col-sm-1 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">From</label>
				<div class="col-sm-3">
					<input name="dfrom" type="date" id="startDate" class="form-control" placeholder="To Date: 2018-05-01" required>
					<span style="color: <?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Date format: 2018-05-01</span>
				</div>
							
				<label for="" class="col-sm-1 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">End Date</label>
				<div class="col-sm-3">
					<input name="dto" type="date" id="endDate" class="form-control" placeholder="To Date: 2018-05-24" required>
					<span style="color: <?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Date format: 2018-05-24</span>
				</div>

				<label for="" class="col-sm-1 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Filter By</label>
				<div class="col-sm-3">
					<select name="ttype" id="transType" class="form-control select2" style="width:100%" required>
						<option value="" selected="selected">Filter By...</option>
						<option value="all">All Till Account</option>
						<option value="Active">Active</option>
						<option value="NotActive">Not-Active</option>

						<option disabled>Filter By Staff/Sub-Agent</option>
						<?php
						$get = mysqli_query($link, "SELECT * FROM user WHERE created_by = '$institution_id' ORDER BY id DESC") or die (mysqli_error($link));
						while($rows = mysqli_fetch_array($get))
						{
						?>
						<option value="<?php echo $rows['id']; ?>"><?php echo $rows['name'].' '.$rows['lname'].' '.$rows['mname']; ?></option>
						<?php } ?>
					</select>
				</div>
			</div>
			
			</div>

			<hr>
			<div class="table-responsive">
			<table id="till_account_data" class="table table-bordered table-striped">
				<thead>
				<tr>
				  <th><input type="checkbox" id="select_all"/></th>
				  <th>Action</th>
                  <th>Branch</th>
				  <th>Teller</th>
                  <th>Cashier</th>
				  <th>Virtual Account</th>
				  <th>Commission Type</th>
                  <th>Commision</th>
                  <th>Till Balance</th>
                  <th>Commission Balance</th>
				  <th>Unsettled Balance</th>
                  <th>Status</th>
				  <th>Date/Time</th>
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
						echo "<script>window.location='view_teller.php?id=".$_SESSION['tid']."&&mid=".base64_encode("510")."'; </script>";
							}
							else{
							for($i=0; $i < $N; $i++)
							{
								$result = mysqli_query($link,"DELETE FROM till_account WHERE id ='$id[$i]'");
								echo "<script>alert('Row Delete Successfully!!!'); </script>";
								echo "<script>window.location='view_teller.php?id=".$_SESSION['tid']."&&mid=".base64_encode("510")."'; </script>";
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