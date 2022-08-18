<div class="row">
		
	       
		    <section class="content">  
	        <div class="box box-success">
            <div class="box-body">
              <div class="table-responsive">
             <div class="box-body">
    <form method="post">
       
    <?php echo ($delete_client_enrollee == '1') ? '<button type="submit" class="btn btn-flat bg-'.(($myaltrow['theme_color'] == "") ? 'blue' : $myaltrow['theme_color']).'" name="trash"><i class="fa fa-times"></i> Trash</button>' : ''; ?>

	<hr>

    <div class="box-body">
                 
	           <div class="form-group">
                  <label for="" class="col-sm-1 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">From</label>
                  <div class="col-sm-5">
                  <input name="dfrom" type="date" id="startDate" class="form-control" placeholder="To Date: 2018-05-01">
				  <span style="color: <?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>;">Date format: 2018-05-01</span>
                  </div>
				  
				  <label for="" class="col-sm-1 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">To</label>
                  <div class="col-sm-5">
                  <input name="dto" type="date" id="endDate" class="form-control" placeholder="To Date: 2018-05-24">
				  <span style="color: <?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>;">Date format: 2018-05-24</span>
                  </div>
             </div>

            <div class="form-group">
                  <label for="" class="col-sm-1 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Enrollee</label>
                  <div class="col-sm-5">
                  <select name="customer" id="byCustomer" class="form-control select2" style="width:100%">
					 <option value="" selected>Filter By Enrollee</option>
    				<?php
    				$get = mysqli_query($link, "SELECT * FROM enrollees ORDER BY id DESC") or die (mysqli_error($link));
    				while($rows = mysqli_fetch_array($get))
    				{
    				?>
    				<option value="<?php echo $rows['trackingId']; ?>"><?php echo $rows['trackingId'].' - '.$rows['userLname'].' '.$rows['userFname'].' | '.$rows['phoneNo']; ?></option>
    				<?php } ?>
				  </select>
                  </div>
				  
				  <label for="" class="col-sm-1 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Filter By</label>
                  <div class="col-sm-5">
                 	<select name="filter_by" id="filterBy" class="form-control select2" style="width:100%">
    					<option value="" selected="selected">Filter By...</option>
    					<option value="All">All</option>

                        <option disabled>Filter By Transaction Type</option>
                        <option value="NIN_ENROLLMENT">NIN Enrollment</option>
 						<option value="NIN_REPRINT">NIN Reprint</option>
                        <option value="NIN_MODIFICATION">NIN Modification</option>
                        <option value="NATIONAL_ID_CARD">National ID Card</option>
                        <option value="NIN_CLEARANCE">NIN Clearance</option>
                        <option value="NIN_VALIDATION">NIN Validation</option>

                        <option disabled>Filter By Status</option>
                        <option value="Pending">Pending</option>
 						<option value="Completed">Completed</option>
                        <option value="Collected">Collected</option>
    				
                        <option disabled>Filter By Institution</option>
                        <?php
                        $get51 = mysqli_query($link, "SELECT * FROM institution_data WHERE status = 'Approved' ORDER BY id DESC") or die (mysqli_error($link));
                        while($rows51 = mysqli_fetch_array($get51))
                        {
                        ?>
                        <option value="<?php echo $rows51['institution_id']; ?>"><?php echo $rows51['institution_name']; ?></option>
                        <?php } ?>

                        <option disabled>Filter By Branch</option>
                        <?php
                        $get5 = mysqli_query($link, "SELECT * FROM branches WHERE bstatus = 'Operational' ORDER BY id DESC") or die (mysqli_error($link));
                        while($rows5 = mysqli_fetch_array($get5))
                        {
                        ?>
                        <option value="<?php echo $rows5['branchid']; ?>"><?php echo $rows5['bname'].' ['.$rows5['branchid'].']'; ?></option>
                        <?php } ?>
                
                        <option disabled>Filter By Staff</option>
                        <?php
                        $get6 = mysqli_query($link, "SELECT * FROM user WHERE comment = 'Approved' ORDER BY id DESC");
                        while($rows6 = mysqli_fetch_array($get6))
                        {
                        ?>
                        <option value="<?php echo $rows6['id']; ?>"><?php echo $rows6['name'].' '.$rows6['fname'].' '.$rows6['mname']; ?></option>
                        <?php } ?>

                        <option disabled>Filter By NIMN Partners</option>
                        <?php
                        $get61 = mysqli_query($link, "SELECT * FROM nimcPartner ORDER BY id DESC") or die (mysqli_error($link));
                        while($rows61 = mysqli_fetch_array($get61))
                        {
                        ?>
                        <option value="<?php echo $rows61['id']; ?>"><?php echo $rows61['partnerName']; ?></option>
                        <?php } ?>
					</select>
                  </div>
                </div>
                
                </div>
                
                
        <hr>
            <div class="table-responsive">
			    <table id="fetch_allenrollee_data" class="table table-bordered table-striped">
			        <thead>
                    <tr>
                      <th><input type="checkbox" id="select_all"/></th>
                      <th>Status</th>
                      <th>Institution</th>
                      <th>Branch</th>
                      <th>Operator</th>
                      <th>Transaction Type</th>
    				  <th>Enrollee Name</th>
                      <th>Gender</th>
                      <th>MOI</th>
					  <th>TrackingId</th>
                      <th>NIN Number</th>
                      <th>BVN Number</th>
					  <th>Phone Contact</th>
                      <th>Email Address</th>
                      <th>Payment Type</th>
                      <th>Amount Paid</th>
                      <th>Balance Left</th>
                      <th>dateCreated</th>
                      <th>lastUpdated</th>
                     </tr>
                    </thead>
                </table>
            </div>


						<?php
						if(isset($_POST['trash'])){
							$idm = $_GET['id'];
							$id=$_POST['selector'];
							$N = count($id);
							if($id == ''){
								echo "<script>alert('Row Not Selected!!!'); </script>";	
							}
							else{
                                for($i=0; $i < $N; $i++)
                                {
                                    $result = mysqli_query($link,"DELETE FROM enrollees WHERE id ='$id[$i]'");
                                                                    
                                    echo "<script>alert('Enrollee Trashed Successfully!!!'); </script>";
                                    echo "<script>window.location='enrolleeList.php?id=".$_SESSION['tid']."&&mid=".base64_encode("419")."'; </script>";
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