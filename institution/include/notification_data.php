<div class="row">     
        <section class="content">  
          <div class="box box-success">
            <div class="box-body">
              <div class="table-responsive">
             <div class="box-body">
<form>        
<div class="col-md-14">
            <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                <?php
                $search_notify1 = mysqli_query($link, "SELECT * FROM investment_notification WHERE merchantid = '$institution_id' AND status = 'Pending' ORDER BY id DESC");
                $num_notify1 = mysqli_num_rows($search_notify1);

                $search_notify2 = mysqli_query($link, "SELECT * FROM investment_notification WHERE merchantid = '$institution_id' AND status = 'Approved' ORDER BY id DESC");
                $num_notify2 = mysqli_num_rows($search_notify2);

                $search_notify3 = mysqli_query($link, "SELECT * FROM investment_notification WHERE merchantid = '$institution_id' AND status = 'Declined' ORDER BY id DESC");
                $num_notify3 = mysqli_num_rows($search_notify3);
                ?>
                <li <?php echo ($_GET['tab'] == 'tab_1') ? "class='active'" : ''; ?>><a href="notification.php?tid=<?php echo $_SESSION['tid']; ?>&&mid=NDkw&&tab=tab_1">Pending: <b>(<?php echo $num_notify1; ?>)</b> | Approved: <b>(<?php echo $num_notify2; ?>)</b> | Declined: <b>(<?php echo $num_notify3; ?>)</b></a></li>
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
                    <option disabled>Filter By Status</option>
                    <option value="all">All Payment Notification</option>
                    <option value="Pending">Pending</option>
                    <option value="Approved">Approved</option>
                    <option value="Declined">Declined</option>

                    <option disabled>Filter By Product Subscriber</option>
                    <?php
                    $get = mysqli_query($link, "SELECT * FROM investment_notification WHERE vendorid = '$vendorid' ORDER BY id DESC") or die (mysqli_error($link));
                    while($rows = mysqli_fetch_array($get))
                    {
                      $customerid = $rows['customerid'];
                      $search_borro = mysqli_query($link, "SELECT * FROM borrowers WHERE (account = '$customerid' OR virtual_acctno = '$customerid')");
                      $fetch_borro = mysqli_fetch_array($search_borro);
                      $bVA = $fetch_borro['virtual_acctno'];
                    ?>
                    <option value="<?php echo $customerid; ?>"><?php echo $rows['customer_name'].' - '.$bVA; ?></option>
                    <?php } ?>
                    
                    <option disabled>Filter By Vendor</option>
                    <?php
                    $get2 = mysqli_query($link, "SELECT * FROM user WHERE created_by = '$institution_id' AND reg_type = 'vendor' ORDER BY id DESC") or die (mysqli_error($link));
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
              <table id="fetch_allnotification_data" class="table table-bordered table-striped">
              <thead>
                    <tr>
                      <th><input type="checkbox" id="select_all"/></th>
                      <th>RefID</th>
                      <th>Vendor</th>
                      <th>Customer Name</th>
                      <th>Plan Code</th>
                      <th>Sub. Code</th>
                      <th>Plan Name</th>
                      <th>Amount Paid</th>
                      <th>Bank Details</th>
                      <th>Status</th>
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
  elseif($tab == 'tab_2')
  {
  ?>
            <div class="tab-pane <?php echo ($_GET['tab'] == 'tab_2') ? 'active' : ''; ?>" id="tab_2">


             </div>
             <!-- /.tab-pane --> 
             
<?php
  }
  elseif($tab == 'tab_3')
  {
  ?>
            <div class="tab-pane <?php echo ($_GET['tab'] == 'tab_3') ? 'active' : ''; ?>" id="tab_3">

             
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
<hr>
    
</div>