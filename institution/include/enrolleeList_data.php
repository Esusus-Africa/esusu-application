<div class="row">
		
	       
		    <section class="content">  
	        <div class="box box-success">
            <div class="box-body">
              <div class="table-responsive">
             <div class="box-body">
    <form method="post">
       
    <?php echo ($delete_enrollee == '1') ? '<button type="submit" class="btn btn-flat bg-'.(($myaltrow['theme_color'] == "") ? 'blue' : $myaltrow['theme_color']).'" name="trash"><i class="fa fa-times"></i> Trash</button>' : ''; ?>

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
    				($all_enrollee_list === "1" && $individual_enrollee_list != "1" && $branch_enrollee_list != "1") ? $get = mysqli_query($link, "SELECT * FROM enrollees WHERE companyid = '$institution_id' ORDER BY id DESC") or die (mysqli_error($link)) : "";
    				($all_enrollee_list != "1" && $individual_enrollee_list === "1" && $branch_enrollee_list != "1") ? $get = mysqli_query($link, "SELECT * FROM enrollees WHERE companyid = '$institution_id' AND staffid = '$iuid' ORDER BY id DESC") or die (mysqli_error($link)) : "";
    				($all_enrollee_list != "1" && $individual_enrollee_list != "1" && $branch_enrollee_list === "1") ? $get = mysqli_query($link, "SELECT * FROM enrollees WHERE companyid = '$institution_id' AND branchid = '$isbranchid' ORDER BY id DESC") or die (mysqli_error($link)) : "";
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
    					<option value="All">All Enrollee</option>

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
    				
                    	<?php
                        ////TO BE ACCESSIBLE BY THE SUPER ADMIN ONLY
                        if($list_branches == "1")
                        {
                        ?>
                        <option disabled>Filter By Branch</option>
                        <?php
                        $get5 = mysqli_query($link, "SELECT * FROM branches WHERE created_by = '$institution_id' ORDER BY id") or die (mysqli_error($link));
                        while($rows5 = mysqli_fetch_array($get5))
                        {
                        ?>
                        <option value="<?php echo $rows5['branchid']; ?>"><?php echo $rows5['bname'].' ['.$rows5['branchid'].']'; ?></option>
                        <?php } ?>
                        <?php
                        }
                        else{
                            //nothing
                        }
                        ?>
                
                        <option disabled>Filter By Staff</option>
                        <?php
                        ($list_employee == "1" && $list_branch_employee != "1") ? $get6 = mysqli_query($link, "SELECT * FROM user WHERE created_by = '$institution_id' ORDER BY id") : "";
                        ($list_employee != "1" && $list_branch_employee == "1") ? $get6 = mysqli_query($link, "SELECT * FROM user WHERE created_by = '$institution_id' AND branchid = '$isbranchid' ORDER BY id") : "";
                        while($rows6 = mysqli_fetch_array($get6))
                        {
                        ?>
                        <option value="<?php echo $rows6['id']; ?>"><?php echo $rows6['name'].' '.$rows6['lname'].' '.$rows6['mname']; ?></option>
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
                      <th>Action</th>
                      <th>Status</th>
                      <th>Branch</th>
                      <th>Operator</th>
                      <th>Transaction Type</th>
    				  <th>Enrollee Name</th>
                      <th>MOI</th>
					  <th>TrackingId</th>
                      <th>NIN Number</th>
                      <th>BVN Number</th>
					  <th>Phone Contact</th>
                      <th>Amount Paid</th>
                      <th>Balance Left</th>
                      <th>Expected Amount</th>
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
                                    $seachEnrollee = mysqli_query($link, "SELECT * FROM enrollees WHERE id = '$id[$i]'");
                                    $fetchEnrollee = mysqli_fetch_array($seachEnrollee);
                                    $trackingId = $fetchEnrollee['trackingId'];
                                    $wallet_date_time = date("Y-m-d h:i:s");

                                    //Get Information from User IP Address
                                    $myip = $sendSMS->getUserIP();
                                    //Get Information from User Browser
                                    $ua = $sendSMS->getBrowser();
                                    $yourbrowser = "Your browser: " . $ua['name'] . " " . $ua['version'] . " on " .$ua['platform'] . " reports: " . $ua['userAgent'];
                                    $activities_tracked = $iname . " make attempt to delete enrollee details with Tracking ID: " . $trackingId;
                                    
                                    mysqli_query($link, "INSERT INTO enrolleeLog VALUES(null,'$institution_id','$isbranchid','$iuid','$myip','$yourbrowser','$activities_tracked','$wallet_date_time')");
                                    $result = mysqli_query($link,"DELETE FROM enrollees WHERE id ='$id[$i]'");
                                                                    
                                    echo "<script>alert('Enrollee Trashed Successfully!!!'); </script>";
                                    echo "<script>window.location='enrolleeList.php?id=".$_SESSION['tid']."&&mid=".base64_encode("711")."'; </script>";
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