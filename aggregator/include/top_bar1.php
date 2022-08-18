 <header class="main-header">
    <!-- Logo -->
   <a href="dashboard.php?id=<?php echo $_SESSION['tid']; ?>&&mid=NDAx" class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini"><?php echo $row ['abb'];?></span>
      <!-- logo for regular state and mobile devices -->
      <span class="logo-lg"><?php echo ($row ['name'] == true) ? $row['name'] : $aname; ?></span>
    </a>

    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
      <!-- Sidebar toggle button-->
      <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
        <span class="sr-only">Toggle navigation</span>
      </a>

      <div class="navbar-custom-menu">
        <button type="button" class="btn btn-flat bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>" align="left">
            <strong class="alert bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>">
            <?php
            echo "<span id='smsunit_balance'>".number_format(($awallet_balance/$fetchsys_config['fax']),2,'.',',')." Unit(s) of sms</span>";
            ?>
        </strong>
        </botton>
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
$search_duration = mysqli_query($link, "SELECT * FROM saas_subscription_trans WHERE sub_token = '$asub_token' AND usage_status = 'Active'");
if(mysqli_num_rows($search_duration) == 0){
    echo "<br><div style='color: white; font-size: 18px'>Subscription Expired: <a href='make_saas_sub.php?tid=".$_SESSION['tid']."&&mid=NDIw' class='btn bg-orange'><b>Renew Subscription</b></a></span></div>";
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
    mysqli_query($link, "UPDATE saas_subscription_trans SET usage_status = 'Expired' WHERE sub_token = '$asub_token' AND coopid_instid = '$coopid'");
?>
<br>
<div style="color: white; font-size: 18px">Subscription Expired: <a href="make_saas_sub.php?id=<?php echo $_SESSION['tid']; ?>&&mid=NDIw" class="btn bg-yellow"><b>Renew Subscription</b></a></span></div>
<?php
}
else{
$datetime1 = new DateTime($dfrom);

$datetime2 = new DateTime($dto);

$difference = $datetime1->diff($datetime2);
?>
<script type="text/javascript">
var yr=<?php echo date('Y', strtotime($dto)); ?>;
var mo=<?php echo date('m', strtotime($dto)); ?>;
var da=<?php echo date('d', strtotime($dto)); ?>;
var ho=<?php echo date('h', strtotime($dto)); ?>;
var mi=<?php echo date('m', strtotime($dto)); ?>;

function countdown()
{
var today = new Date();
var todayy = today.getFullYear();

/**
if(todayy < 1000)
{
todayy += 1900;
}
**/

var todaym = today.getMonth();
var todayd = today.getDate();
var todayh = today.getHours();
var todaymin = today.getMinutes();
var todaysec = today.getSeconds();
var todaystring = (todaym+1)+"/"+todayd+"/"+todayy+" "+todayh+":"+todaymin+":"+todaysec;

futurestring = mo+"/"+da+"/"+yr+" "+ho+":"+mi+":"+"00";

dd = Date.parse(futurestring)-Date.parse(todaystring);
dday = Math.floor(dd/(60*60*1000*24)*1);
dhour = Math.floor((dd%(60*60*1000*24))/(60*60*1000)*1);
dmin = Math.floor(((dd%(60*60*1000*24))%(60*60*1000))/(60*1000)*1);
dsec = Math.floor((((dd%(60*60*1000*24))%(60*60*1000))%(60*1000))/1000*1);

if(document.getElementById)
{
if(dsec <= 0 && dmin <= 0 && dday <= 0)
{
//When countdown ends!!

var countdownDiv = document.getElementById("countdown");
countdownDiv.innerHTML = "<script><div class='small'><font color='red'>Times Up!</font></div></b>";


}
//if on day of occasion
else if(todayy == yr && todaym == (mo-1) && todayd == da)
{
// need to handle this!!

var countdownDiv = document.getElementById("countdown");
countdownDiv.innerHTML = "<p><div style='color: white; font-size: 17px' class='btn bg-yellow'><font color=#990000><b style='font-size: 21px'>" + dmin + "</b> minutes <b style='font-size: 21px'>" + dsec + "</b> seconds</div></p>";

setTimeout("countdown()",1000)
}
//else, if not yet
else
{
var countdownDiv = document.getElementById("countdown");
countdownDiv.innerHTML = "<p><div style='color: white; font-size: 17px' class='btn bg-yellow'><font color=#990000><b style='font-size: 21px'>" + dday + "</b> days</font> <b style='font-size: 21px'> " + dhour + "</b> hours <b style='font-size: 21px'>" + dmin + "</b> minutes <b style='font-size: 21px'>" + dsec + "</b> seconds</div></p>";

setTimeout("countdown()",1000)
}
}
}

if(document.getElementById)
{
document.write("<div id=countdown></div>");
//document.write("<br>");

countdown();
}
else
{
document.write("<br>");
document.write("<div></div>");
document.write("<br>");
}

</script>

<?php
}
}
?>

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
      $call2 = mysqli_query($link, "SELECT * FROM agent_data WHERE agentid = '$id'");
      if(mysqli_num_rows($call) == 1){
      $row = mysqli_fetch_assoc($call);
      ?>
            <img src="../<?php echo $row['image']; ?>" class="user-image" alt="User Image">

          <span class="hidden-xs"><?php echo $row ['name']; ?></span>
            </a>
            <ul class="dropdown-menu">
              <!-- User image -->
              <li class="user-header">
                <img src="../<?php echo $row['image']; ?>" class="img-circle" alt="User Image">
                <p>
                  <?php echo 'Username:'. $row['username']; ?>
                </p>
        <?php 
        }
        elseif(mysqli_num_rows($call2) == 1){ 
          $get_ag = mysqli_fetch_assoc($call2);
        ?>

            <img src="../img/Electronic 1.png" class="user-image" alt="User Image">

          <span class="hidden-xs"><?php echo $aname; ?></span>
            </a>
            <ul class="dropdown-menu">
              <!-- User image -->
              <li class="user-header">
                <img src="../img/Electronic 1.png" class="img-circle" alt="User Image">
                <p>
                  <?php echo 'Username:'. $ausername; ?>
                </p>

      <?php } ?>
                  </li>
        
              <!-- Menu Body -->
              <li class="user-body">
                <div class="row">
                  <div class="col-xs-4 text-center">

                    <a href="profile?id=<?php echo $_SESSION['tid']; ?>&&mid=NDAx"><b>Profile Settings</b></a>
          
                  </div>

<div class="col-xs-4 text-center"><a href="inboxmessage.php?id=<?php echo $_SESSION['tid']; ?>&&mid=<?php echo base64_encode("406"); ?>">All Tickets</a></div>
<div class="col-xs-4 pull-right"><a href="newmessage.php?id=<?php echo $_SESSION['tid']; ?>&&mid=<?php echo base64_encode("406"); ?>">Create Tickets</a></div>
                </div>
                <!-- /.row -->
              </li>
              <!-- Menu Footer-->
              <li class="user-footer">
                <?php echo '<b style="font-size: 16px;">'.$asub_token.'</b>'; ?>
                <div class="pull-right">
                  <a href="../logout.php?id=<?php echo $agentid; ?>" class="btn bg-orange"><i class="fa fa-sign-out"></i>Sign out</a>
                </div>
              </li>
            </ul>
          </li>
          <button type="button" class="btn btn-flat bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>" align="left">
            <strong class="alert bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>">
            <?php
            echo "<span id='smsunit_balance'>".number_format(($awallet_balance/$fetchsys_config['fax']),2,'.',',')." SMS</span>";
            ?>
        </strong>
        </botton>
          <!-- Control Sidebar Toggle Button -->
        </ul>
      </div>
    </nav>
  </header>