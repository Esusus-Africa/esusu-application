<div class="row">     
        <section class="content">  
          <div class="box box-success">
            <div class="box-body">
              <div class="table-responsive">
             <div class="box-body">
<div class="col-md-14">
            <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                <?php
                $search_notify1 = mysqli_query($link, "SELECT * FROM manual_investsettlement WHERE status = 'Pending'");
                $num_notify1 = mysqli_num_rows($search_notify1);
                ?>
                  <li <?php echo ($_GET['tab'] == 'tab_1') ? "class='active'" : ''; ?>><a href="settlement.php?tid=<?php echo $_SESSION['tid']; ?>&&mid=NDkw&&tab=tab_1">Pending (<?php echo $num_notify1; ?>)</a></li>
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
            <form method="post"> 

            <button type="submit" class="btn bg-blue" name="approve"><i class="fa fa-check"></i>&nbsp;Approve</button>

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
                    <option value="all">All Settlement History</option>
                    <option value="Pending">Pending</option>
                    <option value="Declined">Declined</option>
                    <option value="Settled">Settled</option>

                    <option disabled>Filter By Client</option>
                    <?php
                    $get = mysqli_query($link, "SELECT * FROM institution_data ORDER BY id DESC") or die (mysqli_error($link));
                    while($rows = mysqli_fetch_array($get))
                    {
                    ?>
                    <option value="<?php echo $rows['institution_id']; ?>"><?php echo $rows['institution_name']; ?> - [<?php echo $rows['institution_id']; ?>]</option>
                    <?php } ?>

                    <option disabled>Filter By Client Vendor</option>
                    <?php
                    $get2 = mysqli_query($link, "SELECT * FROM user WHERE reg_type = 'vendor' ORDER BY id DESC") or die (mysqli_error($link));
                    while($rows2 = mysqli_fetch_array($get2))
                    {
                    $vendorid = $rows2['branchid'];
                    $searchVName = mysqli_query($link, "SELECT * FROM vendor_reg WHERE companyid = '$vendorid'");
                    $fetchVName = mysqli_fetch_array($searchVName);
                    ?>
                    <option value="<?php echo $vendorid; ?>"><?php echo $fetchVName['cname'].' - '.$rows2['virtual_acctno']; ?></option>
                    <?php } ?>
                  </select>
                </div>
              </div>

              </div>

              <hr>
              <div class="table-responsive">
              <table id="settlement_history_data" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th><input type="checkbox" id="select_all"/></th>
                  <th>Status</th>
                  <th>Reference</th>
                  <th>Client Name</th>
                  <th>Vendor Name</th>
                  <th>Vendor Contact</th>
                  <th>Request Amount</th>
                  <th>Destination Channel</th>
                  <th>A/c Details</th>
                  <th>DateTime</th>
                </tr>
                </thead>
              </table>
              </div>
       
             </div>
             
             
             <?php
                if(isset($_POST['approve'])){
                  $idm = $_GET['id'];
                  $id=$_POST['selector'];
                  $N = count($id);
                  if($id == ''){
                      echo "<script>alert('Row Not Selected!!!'); </script>";	
                    echo "<script>window.location='settlement.php?id=".$_SESSION['tid']."&&mid=NDkw&&tab=tab_1'; </script>";
                  }
                  else{
                    for($i=0; $i < $N; $i++)
                    {
                      $searchHistory = mysqli_query($link, "SELECT * FROM manual_investsettlement WHERE id ='$id[$i]'");
                      $fetchHistory = mysqli_fetch_array($searchHistory);
                      $vcreated_by = $fetchHistory['companyid'];
                      $vendorName = $fetchHistory['vendorName'];
                      $vendorContact = $fetchHistory['vendorContact'];
                      $wtoken = $fetchHistory['refid'];
                      $requestAmt = $fetchHistory['amount'];
                      $destinationChannel = $fetchHistory['destinationChannel'];
                      $details = $fetchHistory['details'];
                      
                      $search_user = mysqli_query($link, "SELECT * FROM user WHERE created_by = '$vcreated_by' AND (role = 'agent_manager' OR role = 'institution_super_admin' OR role = 'merchant_super_admin')");
                      $fetch_user = mysqli_fetch_array($search_user);
                      $merch_em = $fetch_user['email'];
                        
                      $systemset = mysqli_query($link, "SELECT * FROM systemset");
                      $row1 = mysqli_fetch_object($systemset);
                      $em = $row1->email.",".$merch_em;
                      $req_status = "Settled";

                      $result = mysqli_query($link,"UPDATE manual_investsettlement SET status = 'Settled' WHERE id ='$id[$i]'");
                      
                      include("../cron/send_withdrawReq_email.php");
                      echo "<script>alert('Request Approved Successfully!!!'); </script>";
                      echo "<script>window.location='settlement.php?id=".$_SESSION['tid']."&&mid=NDkw&&tab=tab_1'; </script>";
                    }
                  }
                }
                ?>	
			
            </form>
             </div>
             <!-- /.tab-pane -->             
<?php
  }
  elseif($tab == 'tab_2')
  {
  ?>
            <div class="tab-pane <?php echo ($_GET['tab'] == 'tab_2') ? 'active' : ''; ?>" id="tab_2">
             
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
<hr>
    
</div>