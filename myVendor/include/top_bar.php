 <header class="main-header"><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <!-- Logo -->
   <a href="dashboard.php?id=<?php echo $_SESSION['tid']; ?>&&mid=NDAx" class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini"><?php echo $mvsenderid;?></span>
      <!-- logo for regular state and mobile devices -->
      <span class="logo-lg"><?php echo $vc_name;?></span>
    </a>

    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
      <!-- Sidebar toggle button-->
      <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
        <span class="sr-only">Toggle navigation</span>
      </a>

      <div class="navbar-custom-menu">
      
        <ul class="nav navbar-nav">
    
    
    
    
          </li>
          <!-- Notifications: style can be found in dropdown.less -->
          
            <ul class="dropdown-menu">
              <li>
                <!-- inner menu: contains the actual data -->
                <ul class="menu">
                  
                 
                  
                  
                </ul>
              </li>
            </ul>
          </li>
          <!-- Tasks: style can be found in dropdown.less -->
          <li class="dropdown tasks-menu">
              
<?php
if(mysqli_num_rows($vsearch_maintenance_model) == 1)
{
    echo "";
}
else{
?>

<?php
$search_duration = mysqli_query($link, "SELECT * FROM saas_subscription_trans WHERE sub_token = '$vsub_token' AND usage_status = 'Active'");
if(mysqli_num_rows($search_duration) == 0){

  echo "<script>alert('Oops! No Active Subscription for your Institution... \\nClick Ok to Subscribe'); </script>";
  echo "<script>window.location='../logout.php?id=<?php echo $vcreated_by; ?>'; </script>";

}
else{
$fetch_duration = mysqli_fetch_object($search_duration);
$dfrom = $fetch_duration->duration_from;
$dto = $fetch_duration->duration_to;
$expired_grace = $fetch_duration->expiration_grace;

$date1 = strtotime($dfrom);
$date2 = strtotime($dto);

while (($date1 = strtotime('+1 Days', $date1)) <= $date2)
    $months++;
$now = time(); // or your date as well
$your_date = strtotime($dto);

$datediff = $your_date - $now;
$total_day = round($datediff / (60 * 60 * 24));

if($total_day <= -1)
{
    mysqli_query($link, "UPDATE saas_subscription_trans SET usage_status = 'Expired' WHERE sub_token = '$vsub_token' AND coopid_instid = '$vendorid'");
?>
<br>
<div style="color: white; font-size: 18px">Subscription Expired: <a href="make_saas_sub.php?tid=<?php echo $_SESSION['tid']; ?>&&mid=NDIw" class="btn bg-yellow"><b>Renew Subscription</b></a></span></div>
<?php
}
else{
}
}
?>

<?php } ?>
            
            <ul class="dropdown-menu">
              
              <li>
                <!-- inner menu: contains the actual data -->
                <ul class="menu">
                  <li><!-- Task item -->
                    
                  </li>
                  <!-- end task item -->
                  
                  <!-- end task item -->
                  
                  <!-- end task item -->
                  
                  </li>
                  <!-- end task item -->
                </ul>
              </li>
              <li class="footer">
                <a href="#"></a>
              </li>
            </ul>
          </li>
          <!-- User Account: style can be found in dropdown.less -->
          <li class="dropdown user user-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
      <?php 
      $id = $_SESSION['tid'];
      $call = mysqli_query($link, "SELECT * FROM user WHERE id = '$id'");
      if(mysqli_num_rows($call) == 0)
      {
      echo "<script>alert('Data Not Found6!'); </script>";
      }
      else
      {
      while($row = mysqli_fetch_assoc($call))
      {
      ?>
              <img src="<?php echo ($row['image'] == '' || $row['image'] == 'img/') ? $fetchsys_config['file_baseurl'].'avatar.png' : $fetchsys_config['file_baseurl'].$row['image']; ?>" class="user-image" alt="User Image">

          <span class="hidden-xs"><?php echo $row['name'] ;?></span>
            </a>
            <ul class="dropdown-menu">
              <!-- User image -->
              <li class="user-header">
                <img src="<?php echo ($row['image'] == '' || $row['image'] == 'img/') ? $fetchsys_config['file_baseurl'].'avatar.png' : $fetchsys_config['file_baseurl'].$row['image']; ?>" class="img-circle" alt="User Image">
                <p>
                  <?php echo '<span style="color: black;">Member ID:'. $row ['id'].'</span>';?>
                </p>
          <?php }}?>
                  </li>
        
              <!-- Menu Body -->
              <li class="user-body">
                <div class="row">
                  <div class="col-xs-4 text-center">
                    <a href="profile.php?id=<?php echo $_SESSION['tid']; ?>&&mid=NDAx"><b>Profile Settings</b></a>
                  </div>
                <?php echo ($view_all_tickets == 1) ? '<div class="col-xs-4 text-center"><a href="inboxmessage.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("406").'">All Tickets</a></div>' : ''; ?>
                </div>
                <!-- /.row -->
              </li>
              <!-- Menu Footer-->
              <li class="user-footer">
                <?php echo '<b style="font-size: 16px;">'.((mysqli_num_rows($search_maintenance_model) == 1) ? "": $vsub_token).'</b>'; ?>
                <div class="pull-right">
                  <a href="../logout.php?id=<?php echo $vcreated_by; ?>" class="btn bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>"><i class="fa fa-sign-out"></i>Sign out</a>
                </div>
              </li>
            </ul>
          </li>
        <button type="button" class="btn btn-flat bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>" align="left">
            <strong class="alert bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>">
            <?php
            echo "<span id='smsunit_balance'>".number_format(($vwallet_balance/$fetchsys_config['fax']),2,'.',',')." SMS</span>";
            ?>
        </strong>
        </botton>
          <!-- Control Sidebar Toggle Button -->
        </ul>
      </div>
    </nav>
  </header>