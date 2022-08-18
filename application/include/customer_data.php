<div class="row">
		
	       
		    <section class="content">  
	        <div class="box box-success">
            <div class="box-body">
              <div class="table-responsive">
             <div class="box-body">
<form method="post">
	<a href="dashboard.php?id=<?php echo $_SESSION['tid']; ?>&&mid=<?php echo base64_encode("401"); ?>"><button type="button" class="btn bg-orange"><i class="fa fa-mail-reply-all"></i>&nbsp;Back</button> </a> 
	 <?php echo ($delete_client_customer == '1') ? '<button type="submit" class="btn btn-flat bg-orange" name="delete"><i class="fa fa-times"></i>&nbsp;Multiple Delete</button>' : ''; ?>
	
	<hr>
             <div class="box-body">
                 
	           <div class="form-group">
                  <label for="" class="col-sm-1 control-label" style="color:blue;">From</label>
                  <div class="col-sm-5">
                  <input name="dfrom" type="date" id="startDate" class="form-control" placeholder="To Date: 2018-05-01">
				  <span style="color: orange;">Date should be in this format: 2018-05-01</span>
                  </div>
				  
				  <label for="" class="col-sm-1 control-label" style="color:blue;">To</label>
                  <div class="col-sm-5">
                  <input name="dto" type="date" id="endDate" class="form-control" placeholder="To Date: 2018-05-24">
				  <span style="color: orange;">Date should be in this format: 2018-05-24</span>
                  </div>
             </div>

            <div class="form-group">
                  <label for="" class="col-sm-1 control-label" style="color:blue;">Customer</label>
                  <div class="col-sm-5">
                  <select name="customer" id="byCustomer" class="form-control select2" style="width:100%">
					<option value="" selected>Filter By Customer</option>
    				<?php
    				$get = mysqli_query($link, "SELECT * FROM borrowers ORDER BY id") or die (mysqli_error($link));
    				while($rows = mysqli_fetch_array($get))
    				{
    				?>
    				<option value="<?php echo $rows['account']; ?>"><?php echo $rows['snum'].' - '.$rows['lname'].' '.$rows['fname'].' ['.$rows['account'].'] | '.$rows['phone']; ?></option>
    				<?php } ?>
				  </select>
                  </div>
				  
				  <label for="" class="col-sm-1 control-label" style="color:blue;">Filter By</label>
                  <div class="col-sm-5">
                 <select name="filter_by" id="filterBy" class="form-control select2" style="width:100%">
    				<option value="" selected="selected">Filter By...</option>
    				<option value="">None</option>
    				<option disabled>Filter By Institution</option>
    				<?php
    				$get = mysqli_query($link, "SELECT * FROM institution_data WHERE status = 'Approved' ORDER BY id") or die (mysqli_error($link));
    				while($rows = mysqli_fetch_array($get))
    				{
    				?>
    				<option value="<?php echo $rows['institution_id']; ?>"><?php echo $rows['institution_name']; ?></option>
    				<?php } ?>	
    				
    				<option disabled>Filter By Agent</option>
    				<?php
    				$get = mysqli_query($link, "SELECT * FROM agent_data WHERE status = 'Approved' ORDER BY id") or die (mysqli_error($link));
    				while($rows = mysqli_fetch_array($get))
    				{
    				?>
    				<option value="<?php echo $rows['agentid']; ?>"><?php echo $rows['fname']; ?></option>
    				<?php } ?>
    				
    				<option disabled>Filter By Merchant</option>
    				<?php
    				$get = mysqli_query($link, "SELECT * FROM merchant_reg WHERE status = 'Active' ORDER BY id") or die (mysqli_error($link));
    				while($rows = mysqli_fetch_array($get))
    				{
    				?>
				    <option value="<?php echo $rows['merchantID']; ?>"><?php echo $rows['company_name']; ?></option>
				    <?php } ?>
				    
				    <option disabled>Filter By Branch</option>
    				<?php
    				$get = mysqli_query($link, "SELECT * FROM branches ORDER BY id") or die (mysqli_error($link));
    				while($rows = mysqli_fetch_array($get))
    				{
    				?>
				    <option value="<?php echo $rows['branchid']; ?>"><?php echo $rows['bname']; ?></option>
				    <?php } ?>
				    
				    <option disabled>Filter By Staff</option>
    				<?php
    				$get = mysqli_query($link, "SELECT * FROM user ORDER BY id") or die (mysqli_error($link));
    				while($rows = mysqli_fetch_array($get))
    				{
    				?>
				    <option value="<?php echo $rows['id']; ?>"><?php echo $rows['name']; ?></option>
				    <?php } ?>
    				
				</select>
                  </div>
                </div>
                
                </div>
			 
		<hr>
            <div class="table-responsive">
			    <table id="fetch_allcustomer_data" class="table table-bordered table-striped">
			        <thead>
                    <tr>
					  <th><input type="checkbox" id="select_all"/></th>
					  <th>Actions</th>
					  <th>S/No.</th>
					  <th>Client Name</th>
                      <th>Client Branch</th>
                      <th>Staff Name</th>
    				  <th>Account ID</th>
                      <th>Name</th>
                      <th>Phone</th>
                      <th>Date/Time</th>
                      <th>Ledger Balance</th>
                      <th>Wallet Balance</th>
    				  <th>Status</th>
                     </tr>
                    </thead>
                </table>
            </div>
		
			

						<?php
						if(isset($_POST['delete'])){
						$idm = $_GET['id'];
							$id=$_POST['selector'];
							$N = count($id);
						if($id == ''){
						echo "<script>alert('Row Not Selected!!!'); </script>";	
						echo "<script>window.location='customer.php?id=".$_SESSION['tid']."&&mid=".base64_encode("419")."'; </script>";
							}
							else{
							for($i=0; $i < $N; $i++)
							{
								$result = mysqli_query($link,"DELETE FROM borrowers WHERE id ='$id[$i]'");
								echo "<script>alert('Row Delete Successfully!!!'); </script>";
								echo "<script>window.location='customer.php?id=".$_SESSION['tid']."&&mid=".base64_encode("419")."'; </script>";
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