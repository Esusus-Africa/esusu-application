<div class="row">

<section class="content">  
	<div class="box box-success">
     <div class="box-body">
     <div class="table-responsive">
     <div class="box-body">


        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">
              <span class="info-box-icon bg-red elevation-1"><i class="fas fa-exclamation"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">Pending</span>
                <span class="info-box-number">
                  <?php 
                    ($view_all_customers === "1" && $individual_customer_records != "1" && $individual_wallet != "1" && $branch_customer_records != '1' && $branch_wallet != "1") ? $select_sm = mysqli_query($link, "SELECT * FROM invite WHERE companyid = '$institution_id' AND status = 'Pending'") or die (mysqli_error($link)) : "";
                    ($view_all_customers != "1" && ($individual_customer_records === "1" || $individual_wallet === "1") && $branch_customer_records != '1' && $branch_wallet != "1") ? $select_sm = mysqli_query($link, "SELECT * FROM invite WHERE companyid = '$institution_id' AND userid = '$iuid' AND status = 'Pending'") or die (mysqli_error($link)) : "";
                    $rownum = mysqli_num_rows($select_sm);
                    echo number_format($rownum,0,"",",")."</b>";
                    ?>
                </span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>



        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">
              <span class="info-box-icon bg-green elevation-1"><i class="fas fa-check"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">Sent</span>
                <span class="info-box-number">
                <?php 
                    ($view_all_customers === "1" && $individual_customer_records != "1" && $individual_wallet != "1" && $branch_customer_records != '1' && $branch_wallet != "1") ? $select_sm2 = mysqli_query($link, "SELECT * FROM invite WHERE companyid = '$institution_id' AND status != 'Pending'") or die (mysqli_error($link)) : "";
                    ($view_all_customers != "1" && ($individual_customer_records === "1" || $individual_wallet === "1") && $branch_customer_records != '1' && $branch_wallet != "1") ? $select_sm2 = mysqli_query($link, "SELECT * FROM invite WHERE companyid = '$institution_id' AND userid = '$iuid' AND status != 'Pending'") or die (mysqli_error($link)) : "";
                    $rownum2 = mysqli_num_rows($select_sm2);
                    echo number_format($rownum2,0,"",",")."</b>";
                    ?>
                </span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <!-- /.col -->


        
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">
              <span class="info-box-icon bg-orange elevation-1"><i class="fas fa-mouse-pointer"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">Clicked</span>
                <span class="info-box-number">
                <?php 
                    ($view_all_customers === "1" && $individual_customer_records != "1" && $individual_wallet != "1" && $branch_customer_records != '1' && $branch_wallet != "1") ? $select_sm3 = mysqli_query($link, "SELECT * FROM invite WHERE companyid = '$institution_id' AND status = 'Clicked'") or die (mysqli_error($link)) : "";
                    ($view_all_customers != "1" && ($individual_customer_records === "1" || $individual_wallet === "1") && $branch_customer_records != '1' && $branch_wallet != "1") ? $select_sm3 = mysqli_query($link, "SELECT * FROM invite WHERE companyid = '$institution_id' AND userid = '$iuid' AND status = 'Clicked'") or die (mysqli_error($link)) : "";
                    $rownum3 = mysqli_num_rows($select_sm3);
                    echo number_format($rownum3,0,"",",")."</b>";
                    ?>
                </span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <!-- /.col -->
        


        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">
              <span class="info-box-icon bg-blue elevation-1"><i class="fas fa-retweet"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">Registered</span>
                <span class="info-box-number">
                <?php 
                    ($view_all_customers === "1" && $individual_customer_records != "1" && $individual_wallet != "1" && $branch_customer_records != '1' && $branch_wallet != "1") ? $select_sm4 = mysqli_query($link, "SELECT * FROM invite WHERE companyid = '$institution_id' AND userid = '$iuid' AND status = 'Registered'") or die (mysqli_error($link)) : "";
                    ($view_all_customers != "1" && ($individual_customer_records === "1" || $individual_wallet === "1") && $branch_customer_records != '1' && $branch_wallet != "1") ? $select_sm4 = mysqli_query($link, "SELECT * FROM invite WHERE companyid = '$institution_id' AND userid = '$iuid' AND status = 'Registered'") or die (mysqli_error($link)) : "";
                    $rownum4 = mysqli_num_rows($select_sm4);
                    echo number_format($rownum4,0,"",",")."</b>";
                    ?>
                </span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <!-- /.col -->

        <hr><hr><hr><hr><hr><hr>

        <div class="box-body">
                 
	           <div class="form-group">
                  <label for="" class="col-sm-1 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">From</label>
                  <div class="col-sm-3">
                  <input name="dfrom" type="date" id="startDate" class="form-control" placeholder="To Date: 2018-05-01" required>
				  <span style="color: <?php echo ($myaltrow['alternate_color'] == '') ? 'blue' : $myaltrow['alternate_color']; ?>;">Date should be in this format: 2018-05-01</span>
                  </div>
				  
				  <label for="" class="col-sm-1 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">End Date</label>
                  <div class="col-sm-3">
                  <input name="dto" type="date" id="endDate" class="form-control" placeholder="To Date: 2018-05-24" required>
				  <span style="color: <?php echo ($myaltrow['alternate_color'] == '') ? 'blue' : $myaltrow['alternate_color']; ?>;">Date should be in this format: 2018-05-24</span>
                  </div>
                  
                  <label for="" class="col-sm-1 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Filter By</label>
                  <div class="col-sm-3">
                  <select name="ptype" id="transType" class="form-control select2" style="width:100%" required>
                    <option value="" selected="selected">Filter By...</option>
                    <option value="all">All Report</option>
                    <option value="Pending">Pending</option>
                    <option value="Sent">Sent</option>
                    <option value="Clicked">Clicked</option>
                    <option value="Registered">Registered</option>

                    <option disabled>Filter By Staff / Sub-agent</option>
                    <?php
                    ($list_employee === "1" && $list_branch_employee != "1") ? $searchUser = mysqli_query($link, "SELECT * FROM user WHERE created_by = '$institution_id' AND comment = 'Approved'") : "";
                    ($list_employee != "1" && $list_branch_employee === "1") ? $searchUser = mysqli_query($link, "SELECT * FROM user WHERE created_by = '$institution_id' AND comment = 'Approved'") : "";
                    while($fetchUser = mysqli_fetch_array($searchUser)){
                    ?>
                        <option value="<?php echo $fetchUser['id']; ?>"><?php echo $fetchUser['name'].' '.$fetchUser['lname'].' '.$fetchUser['mname']; ?></option>
                    <?php
                    }
                    ?>

                    <option disabled>Filter By Customer / Wallet User</option>
                    <?php
                    ($view_all_customers === "1" && $individual_customer_records != "1" && $individual_wallet != "1" && $branch_customer_records != '1' && $branch_wallet != "1") ? $searchB = mysqli_query($link, "SELECT * FROM borrowers WHERE branchid = '$institution_id' AND acct_status = 'Activated' AND virtual_acctno != ''") : "";
                    ($view_all_customers != "1" && ($individual_customer_records === "1" || $individual_wallet === "1") && $branch_customer_records != '1' && $branch_wallet != "1") ? $searchB = mysqli_query($link, "SELECT * FROM borrowers WHERE branchid = '$institution_id' AND lofficer = '$iuid' AND acct_status = 'Activated' AND virtual_acctno != ''") : "";
                    ($view_all_customers != "1" && $individual_customer_records != "1" && $individual_wallet != "1" && ($branch_customer_records === '1' || $branch_wallet === "1")) ? $searchB = mysqli_query($link, "SELECT * FROM borrowers WHERE branchid = '$institution_id' AND sbranchid = '$isbranchid' AND acct_status = 'Activated' AND virtual_acctno != ''") : "";
                    while($fetchB = mysqli_fetch_array($searchB)){
                    ?>
                        <option value="<?php echo $fetchB['account']; ?>"><?php echo $fetchB['virtual_acctno']; ?> - <?php echo $fetchB['fname'].' '.$fetchB['lname'].' '.$fetchB['mname']; ?></option>
                    <?php
                    }
                    ?>
                  </select>
                  </div>
             </div>
                
                </div>
		
			
        <hr>
            <div class="table-responsive">
			    <table id="fetch_invitereport_data" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th><input type="checkbox" id="select_all"/></th>
                  <th>InviteCode</th>
                  <th>Referrer</th>
                  <th>firstName</th>
                  <th>Email</th>
                  <th>Phone</th>
                  <th>DateTime</th>
                 </tr>
                </thead>
                <tbody>
                </table>
            </div>

              </div>

              </div>	
</div>
</div>	


		
</div>
        
</div>