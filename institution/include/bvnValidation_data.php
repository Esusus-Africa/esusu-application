<div class="row">     
        <section class="content">  
          <div class="box box-success">
            <div class="box-body">
              <div class="table-responsive">
             <div class="box-body">
<form method="post">
<button type="button" class="btn btn-flat bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>" align="left" disabled>&nbsp;<b>Transfer Wallet:</b>&nbsp;
    <strong class="alert bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">
    <?php
    echo $icurrency.number_format($itransfer_balance,2,'.',',');
    ?> 
    </strong>
</button>
  <hr>    
        
<div class="col-md-14">
            <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
             <li <?php echo ($_GET['tab'] == 'tab_1') ? "class='active'" : ''; ?>><a href="bvnValidation.php?tid=<?php echo $_SESSION['tid']; ?>&&mid=OTQ0&&tab=tab_1">Verify BVN</a></li>

             <li <?php echo ($_GET['tab'] == 'tab_2') ? "class='active'" : ''; ?>><a href="bvnValidation.php?tid=<?php echo $_SESSION['tid']; ?>&&mid=OTQ0&&tab=tab_2">Log History</a></li>

            </ul>
             <div class="tab-content">

<?php
if(isset($_GET['tab']) == true)
{
  $tab = $_GET['tab'];
  if($tab == 'tab_1')
  {
    $systemset = mysqli_query($link, "SELECT * FROM systemset");
    $row1 = mysqli_fetch_object($systemset);
    $bvn_fee = ($ibvn_fee == "") ? $row1->bvn_fee : $ibvn_fee;
  ?>
             <div class="tab-pane <?php echo ($_GET['tab'] == 'tab_1') ? 'active' : ''; ?>" id="tab_1">

             <div class="box-body">
           
			<form class="form-horizontal" method="post" enctype="multipart/form-data">

             <div class="box-body">
               
     <p style="color: <?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>;"> Note that BVN Verification cost <b style="color: <?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;"><?php echo $icurrency.number_format($bvn_fee,2,'.',','); ?> per request</b> (Nigeria Account Only). </p>
			 			
			<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">BVN Number</label>
                  <div class="col-sm-7">
                  <input name="mybvn" type="number" class="form-control" placeholder="Enter your bvn number" required>
                  </div>
                  <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;"></label>
                  </div>

<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;"></label>
                  <div class="col-sm-7">
<?php 
   if(isset($_POST['save']))
   {
       $userBvn = mysqli_real_escape_string($link, $_POST['mybvn']);
       
       $systemset = mysqli_query($link, "SELECT * FROM systemset");
       $row1 = mysqli_fetch_object($systemset);
       $bvn_fee = ($ibvn_fee == "") ? $row1->bvn_fee : $ibvn_fee;
       
       if($itransfer_balance < $bvn_fee){
           
           echo "<br><span style='color:".(($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color'])."'>Sorry! You do not have sufficient fund in your Wallet to perform this verification</span>";
           
       }
       elseif(strlen($userBvn) != 11){
           
            echo "<br><span>BVN Number not Valid....</span>";
        
        }
        elseif($ibvn_route == "Wallet Africa"){
           
            require_once "../config/bvnVerification_class.php";
            
            $processBVN = $newBV->walletAfricaBVNVerifier($walletafrica_skey,$userBvn,$link);
            $ResponseCode = $processBVN['ResponseCode'];
        
            if($ResponseCode == "200"){
               
               $wbalance = $itransfer_balance - $bvn_fee;
               
               $rOrderID = "bvnVrf-".time();
               
               $date_time = date("Y-m-d");
               $wallet_date_time = date("Y-m-d h:i:s");
               
               //BVN Details
               $bvn_picture = $processBVN['Picture'];
               $dynamicStr = md5(date("Y-m-d h:i"));
               $image_converted = $newBV->base64_to_jpeg($bvn_picture, $dynamicStr.".png");

               //20 array row
               $mybvn_data = $processBVN['BVN']."|".$processBVN['FirstName']."|".$processBVN['LastName']."|".$processBVN['MiddleName']."|".$processBVN['DateOfBirth']."|".$processBVN['PhoneNumber']."|".$processBVN['Email']."|".$processBVN['EnrollmentBank']."|".$processBVN['EnrollmentBranch']."|".$processBVN['Gender']."|".$processBVN['LevelOfAccount']."|".$processBVN['LgaOfOrigin']."|".$processBVN['LgaOfResidence']."|".$processBVN['MaritalStatus']."|".$processBVN['NameOnCard']."|".$processBVN['Nationality']."|".$processBVN['StateOfOrigin']."|".$processBVN['StateOfResidence']."|".$processBVN['Title']."|".$processBVN['WatchListed']."|".$image_converted;
               
               $update_wallet = mysqli_query($link, "UPDATE user SET transfer_balance = '$wbalance' WHERE id = '$iuid'");
               
               $insert_income = mysqli_query($link, "INSERT INTO income VALUES(null,'','$rOrderID','BVN','$bvn_fee','$date_time','BVN Charges')");
               
               $insert_report = mysqli_query($link, "INSERT INTO bvn_log VALUE(null,'$institution_id','$isbranchid','','$iuid','$mybvn_data','$bvn_fee','$wallet_date_time','$rOrderID')");
               
               $insert_income = mysqli_query($link, "INSERT INTO wallet_history VALUE(null,'$bbranchid','$rOrderID','$userBvn','','$bvn_fee','Debit','$icurrency','BVN_Charges','Response: $icurrency.$bvn_fee was charged for Customer BVN Verification','successful','$wallet_date_time','$iuid','$wbalance','')");

               echo "<br><span style='color:".(($myaltrow['alternate_color'] == '') ? 'blue' : $myaltrow['alternate_color'])."'>BVN Verified Successfully! <a href='bvnInfo.php?id=".$_SESSION['tid']."&&mid=NDA0&&ref=".$rOrderID."' target='_blank'><b>CLICK HERE</b></a> to view the Report</span>";              

            }
            else{
                echo "<br><span class='bg-orange'>Oops! Network Error, please try again later </span>";
            }
           
       }
       else{
           //empty
            echo "Sorry! Service not available at the moment, please try again later!!";
        
        }
    }
?>			
</div>
   <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;"></label>
</div>

			       </div>
             
      
      <div class="form-group" align="right">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo (isset($_GET['id']) == true) ? 'black' : 'blue'; ?>;"></label>
                  <div class="col-sm-7">
                  <button name="save" type="submit" class="btn bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>"><i class="fa fa-check-circle-o">&nbsp;Verify</i></button>
                  </div>
                  <label for="" class="col-sm-3 control-label"></label>
                  </div>
			  
			 </form> 
       
             </div>
             
             </div>
             <!-- /.tab-pane -->             
<?php
  }
  elseif($tab == 'tab_2')
  {
  ?>
            <div class="tab-pane <?php echo ($_GET['tab'] == 'tab_2') ? 'active' : ''; ?>" id="tab_2">

            <div class="box-body">

            <form class="form-horizontal" method="post" enctype="multipart/form-data">

             <div class="box-body">
			 
			<div class="form-group">
                  <label for="" class="col-sm-1 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">From</label>
                  <div class="col-sm-3">
                  <input name="dfrom" type="date" id="startDate" class="form-control" placeholder="To Date: 2018-05-01">
				  <span style="color: <?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>;">Date format: 2018-05-01</span>
                  </div>
				  
				  <label for="" class="col-sm-1 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">To</label>
                  <div class="col-sm-3">
                  <input name="dto" type="date" id="endDate" class="form-control" placeholder="To Date: 2018-05-24">
				  <span style="color: <?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>;">Date format: 2018-05-24</span>
                  </div>

                  <label for="" class="col-sm-1 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Filter By</label>
                  <div class="col-sm-3">
                 <select name="filter_by" id="filterBy" class="form-control select2" style="width:100%">
    				<option value="" selected="selected">Filter By...</option>
                    <?php echo ($view_all_bvn_verification === "1") ? '<option value="all">All BVN Verification</option>' : ''; ?>
                    <?php echo ($view_individual_bvn_verification === "1") ? '<option value="all1">All My BVN Verification</option>' : ''; ?>
                    <?php echo ($view_branch_bvn_verification === "1") ? '<option value="all2">All Branch BVN Verification</option>' : ''; ?>

                    <option disabled>Filter By Customer</option>
                    <?php
    				($view_all_bvn_verification === "1" || $view_individual_bvn_verification != "1" || $view_branch_bvn_verification != "1") ? $get = mysqli_query($link, "SELECT * FROM borrowers WHERE branchid = '$institution_id' ORDER BY id DESC") or die (mysqli_error($link)) : "";
    				($view_all_bvn_verification != "1" || $view_individual_bvn_verification === "1" || $view_branch_bvn_verification != "1") ? $get = mysqli_query($link, "SELECT * FROM borrowers WHERE branchid = '$institution_id' AND lofficer = '$iuid' ORDER BY id DESC") or die (mysqli_error($link)) : "";
    				($view_all_bvn_verification != "1" || $view_individual_bvn_verification != "1" || $view_branch_bvn_verification === "1") ? $get = mysqli_query($link, "SELECT * FROM borrowers WHERE branchid = '$institution_id' AND sbranchid = '$isbranchid' ORDER BY id DESC") or die (mysqli_error($link)) : "";
    				while($rows = mysqli_fetch_array($get))
    				{
    				?>
    				<option value="<?php echo $rows['account']; ?>"><?php echo $rows['lname'].' '.$rows['fname'].' '.$rows['mname'].' - '.$rows['virtual_acctno']; ?></option>
                    <?php } ?>
                    
                    <option disabled>Filter By Staff</option>
                    <?php
                    ($view_all_bvn_verification === "1" || $view_individual_bvn_verification != "1" || $view_branch_bvn_verification != "1") ? $get2 = mysqli_query($link, "SELECT * FROM user WHERE created_by = '$institution_id' ORDER BY userid DESC") or die (mysqli_error($link)) : "";
                    ($view_all_bvn_verification != "1" || $view_individual_bvn_verification === "1" || $view_branch_bvn_verification != "1") ? $get2 = mysqli_query($link, "SELECT * FROM user WHERE created_by = '$institution_id' AND id = '$iuid' ORDER BY userid DESC") or die (mysqli_error($link)) : "";
    				($view_all_bvn_verification != "1" || $view_individual_bvn_verification != "1" || $view_branch_bvn_verification === "1") ? $get2 = mysqli_query($link, "SELECT * FROM user WHERE created_by = '$institution_id' AND branchid = '$isbranchid' ORDER BY userid DESC") or die (mysqli_error($link)) : "";
    				while($rows2 = mysqli_fetch_array($get2))
    				{
    				?>
    				<option value="<?php echo $rows2['id']; ?>"><?php echo $rows2['name'].' '.$rows2['fname'].' '.$rows2['mname'].' - '.$rows2['virtual_acctno']; ?></option>
                    <?php } ?>
				</select>
                  </div>
                </div>
            </div>
            
            <hr>
            <div class="table-responsive">
			    <table id="fetch_bvnvalidation_data" class="table table-bordered table-striped">
			        <thead>
                    <tr>
                      <th><input type="checkbox" id="select_all"/></th>
                      <th>Branch</th>
                      <th>Account Officer</th>
                      <th>Customer</th>
                      <th>Charges</th>
                      <th>Date/Time</th>
                      <th>Action</th>
                     </tr>
                    </thead>
                </table>
            </div>
			  
			 </form> 
       
             </div>
             
             </div>
             <!-- /.tab-pane --> 
             
<?php 
} 
} 
?>
</div>
</div>
</div>  
</form>       

              </div>


  
</div>  
</div>
</div> 

</div>