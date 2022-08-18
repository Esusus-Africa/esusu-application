<div class="row">       
		    <section class="content">  
	        <div class="box box-success">
            <div class="box-body">
              <div class="table-responsive">
             <div class="box-body">
<form method="post">

	<?php echo ($delete_loan_product == 1) ? '<button type="submit" class="btn btn-flat bg-orange" name="delete"><i class="fa fa-times"></i>&nbsp;Multiple Delete</button>' : ''; ?>

	<hr>

	<div class="box-body">

            <div class="box-body">

             <div class="form-group">
                  <label for="" class="col-sm-1 control-label" style="color:blue;">Filter By</label>
                  <div class="col-sm-4">
                  <select name="ttype" id="transType" class="form-control select2" style="width:100%" required>
                    <option value="" selected="selected">Filter By...</option>
                    <option value="all">All Loan Product</option>
					<option value="Individual">Individual</option>
					<option value="Group">Group</option>
					<option value="Purchase">Purchase</option>
                    
                    <option disabled>Filter By Client</option>
                    <?php
                    $get = mysqli_query($link, "SELECT * FROM institution_data WHERE status = 'Approved' ORDER BY id DESC") or die (mysqli_error($link));
                    while($rows = mysqli_fetch_array($get))
                    {
                    ?>
                    <option value="<?php echo $rows['institution_id']; ?>"><?php echo $rows['institution_name']; ?> - [<?php echo $rows['institution_id']; ?>]</option>
                    <?php } ?>

					<option disabled>Filter By Client Vendor</option>
                    <?php
                    $get = mysqli_query($link, "SELECT * FROM vendor_reg ORDER BY id DESC") or die (mysqli_error($link));
                    while($rows = mysqli_fetch_array($get))
                    {
                    ?>
                    <option value="<?php echo $rows['companyid']; ?>"><?php echo $rows['companyid'].' - '.$rows['cname']; ?></option>
                    <?php } ?>
                  </select>
                  </div>
             </div>
             
            </div>
        
          <hr>
          <div class="table-responsive">
			<table id="client_loanproduct_data" class="table table-bordered table-striped">
                <thead>
                <tr>
				  <th><input type="checkbox" id="select_all"/></th>
				  <th>ID</th>
				  <th>Action</th>
				  <th>Client Name</th>
				  <th>Client Vendor</th>
                  <th>Category</th>
                  <th>Product Name</th>
                  <th>Interest Type</th>
                  <th>Interest on Duration</th>
				  <th>Duration</th>
				  <th>USSD Prefix</th>
				  <th>USSD Visibility</th>
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
							echo "<script>window.location='setuploanprd.php?id=".$_SESSION['tid']."&&mid=NDA1'; </script>";
						}
						else{
							for($i=0; $i < $N; $i++)
							{
								$result = mysqli_query($link,"DELETE FROM loan_product WHERE id ='$id[$i]'");
								echo "<script>alert('Row Delete Successfully!!!'); </script>";
								echo "<script>window.location='setuploanprd.php?id=".$_SESSION['tid']."&&mid=NDA1'; </script>";
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