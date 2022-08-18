<div class="row">
    
		    <section class="content">  
	        <div class="box box-success">
            <div class="box-body">
              <div class="table-responsive">
             <div class="box-body">
<form method="post">
  <a href="manageAccount.php?id=<?php echo $_SESSION['tid']; ?>&&mid=OTIy&&tab=tab_1"><button type="button" class="btn btn-flat bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>"><i class="fa fa-refresh"></i>&nbsp;Refresh</button></a> 
</form>
  <hr>

  <div class="col-md-14">
            <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
             <li <?php echo ($_GET['tab'] == 'tab_1') ? "class='active'" : ''; ?>><a href="manageAccount.php?tid=<?php echo $_SESSION['tid']; ?>&&mid=OTIy&&tab=tab_1">All Account</a></li>
            </ul>
             <div class="tab-content">

<?php
if(isset($_GET['tab']) == true)
{
  $tab = $_GET['tab'];
  if($tab == 'tab_1')
  {
 
  ?>
             <div class="tab-pane <?php echo ($_GET['tab'] == 'tab_1') ? 'active' : ''; ?>" id="tab_1">

             <div class="box-body">
           
             <div class="box-body">
                 
	            <div class="form-group">
                  <label for="" class="col-sm-1 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">From</label>
                  <div class="col-sm-3">
                  <input name="dfrom" type="date" id="startDate" class="form-control" placeholder="To Date: 2018-05-01" required>
				  <span style="color: <?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>;">Date format: 2018-05-01</span>
                  </div>
				  
				  <label for="" class="col-sm-1 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">End Date</label>
                  <div class="col-sm-3">
                  <input name="dto" type="date" id="endDate" class="form-control" placeholder="To Date: 2018-05-24" required>
				  <span style="color: <?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>;">Date format: 2018-05-24</span>
                  </div>

                  <label for="" class="col-sm-1 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Type</label>
                  <div class="col-sm-3">
                  <select name="ttype" id="transType" class="form-control select2" style="width:100%" required>
                    <option value="" selected="selected">Filter By...</option>
                    <?php echo ($manage_bank_account == "1" && $manage_individual_bank_account == "" && $manage_branch_bank_account == "") ? '<option value="all">All Account</option>' : ''; ?>
                    <?php echo ($manage_bank_account == "" && $manage_individual_bank_account == "1" && $manage_branch_bank_account == "") ? '<option value="all1">All Account</option>' : ''; ?>
                    <?php echo ($manage_bank_account == "" && $manage_individual_bank_account == "" && $manage_branch_bank_account == "1") ? '<option value="all2">All Account</option>' : ''; ?>

                    <option disabled>Filter By Staff/Sub-Agent</option>
                    <?php
                      ($list_employee === "1" && $list_branch_employee != "1") ? $search = mysqli_query($link, "SELECT * FROM user WHERE created_by = '$institution_id' ORDER BY userid DESC") : "";
                      ($list_employee != "1" && $list_branch_employee === "1") ? $search = mysqli_query($link, "SELECT * FROM user WHERE created_by = '$institution_id' AND branchid = '$isbranchid' ORDER BY userid DESC") : "";
                      while($fetchSearch = mysqli_fetch_array($search)){

                        echo '<option value="'.$fetchSearch['id'].'">'.$fetchSearch['lname'].' '.$fetchSearch['name'].'</option>';

                      }
                    ?>

                    <option disabled>Filter By Customer</option>
                    <?php
                      ($manage_bank_account == "1" && $manage_individual_bank_account == "" && $manage_branch_bank_account == "") ? $search1 = mysqli_query($link, "SELECT * FROM bank_account WHERE merchantid = '$institution_id' ORDER BY id DESC") : "";
                      ($manage_bank_account == "" && $manage_individual_bank_account == "1" && $manage_branch_bank_account == "") ? $search1 = mysqli_query($link, "SELECT * FROM bank_account WHERE merchantid = '$institution_id' AND staffid = '$iuid' ORDER BY id DESC") : "";
                      ($manage_bank_account == "" && $manage_individual_bank_account == "" && $manage_branch_bank_account == "1") ? $search1 = mysqli_query($link, "SELECT * FROM bank_account WHERE merchantid = '$institution_id' AND branchid = '$isbranchid' ORDER BY id DESC") : "";
                      while($fetchSearch1 = mysqli_fetch_array($search1)){

                        echo '<option value="'.$fetchSearch1['account_number'].'">'.$fetchSearch1['account_number'].' - '.$fetchSearch1['account_name'].'</option>';

                      }
                    ?>
                  </select>
                  </div>
                </div>

                </div>
                
                
            <hr>
            <div class="table-responsive">
			    <table id="fetch_manageAccount_data" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th><input type="checkbox" id="select_all"/></th>
                  <th>Action</th>
                  <th>Branch</th>
                  <th>A/c Officer</th>
                  <th>A/c Number</th>
                  <th>A/c Name</th>
                  <th>Bank Name</th>
                  <th>Phone Number</th>
                  <th>Email Address</th>
                  <th>Balance</th>
                  <th>Status</th>
                  <th>Date/Time</th>
                </tr>
                </thead>
          </table>
            </div>
			 
			
       
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
    

              </div>


  
</div>  
</div>
</div>  
</div>