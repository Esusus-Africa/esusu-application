<div class="row">
    
		    <section class="content">  
	        <div class="box box-success">
            <div class="box-body">
              <div class="table-responsive">
             <div class="box-body">
<form method="post">
	
	<a href="dashboard.php?id=<?php echo $_SESSION['tid']; ?>&&mid=<?php echo base64_encode("401"); ?>"><button type="button" class="btn btn-flat bg-orange"><i class="fa fa-mail-reply-all"></i>&nbsp;Back</button> </a> 
	
	<?php echo ($delete_payroll == '1') ? '<button type="submit" class="btn btn-flat bg-orange" name="delete"><i class="fa fa-times"></i>&nbsp;Delete</button>' : ''; ?>	
	
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
                    <option value="all">All Payroll</option>
					<option value="all1">All Esusu Payroll Only</option>

                    <option disabled>Filter By Client</option>
                    <?php
                    $get = mysqli_query($link, "SELECT * FROM institution_data ORDER BY id DESC") or die (mysqli_error($link));
                    while($rows = mysqli_fetch_array($get))
                    {
                    ?>
                    <option value="<?php echo $rows['institution_id']; ?>"><?php echo $rows['institution_id'].' - '.$rows['institution_name']; ?></option>
					<?php } ?>
					
					<option disabled>Filter By Branch</option>
                    <?php
                    $get = mysqli_query($link, "SELECT * FROM branches ORDER BY id DESC") or die (mysqli_error($link));
                    while($rows = mysqli_fetch_array($get))
                    {
                    ?>
                    <option value="<?php echo $rows['branchid']; ?>"><?php echo $rows['branchid'].' - '.$rows['bname']; ?></option>
                    <?php } ?>
                  </select>
                </div>
              </div>

        </div>

		
              <hr>
              <div class="table-responsive">
              <table id="allpayroll_data" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th><input type="checkbox" id="select_all"/></th>
				  <th>Action</th>
				  <th>Company Name</th>
				  <th>Branch Name</th>
                  <th>Staff</th>
                  <th>Pay Date</th>
				  <th>Gross Amount</th>
                  <th>Deduction Amount</th>
                  <th>Paid/Net Amount</th>
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
						echo "<script>window.location='listpayroll.php?id=".$_SESSION['tid']."&&mid=NDIz'; </script>";
							}
							else{
							for($i=0; $i < $N; $i++)
							{
								$result = mysqli_query($link,"DELETE FROM payroll WHERE id ='$id[$i]'");
								echo "<script>alert('Row Delete Successfully!!!'); </script>";
								echo "<script>window.location='listpayroll.php?id=".$_SESSION['tid']."&&mid=NDIz'; </script>";
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