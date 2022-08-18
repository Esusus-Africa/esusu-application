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
        <?php
        if($arole == "agent_manager" || $arole == "i_a_demo")
        {
        $check_sms = mysqli_query($link, "SELECT * FROM sms WHERE status = 'Activated' AND smsuser = '$agentid'") or die (mysqli_error($link));
              if(mysqli_num_rows($check_sms) == 1)
              {
              $get_sms = mysqli_fetch_array($check_sms);
              $ozeki_user = $get_sms['username'];
              $ozeki_password = $get_sms['password'];
              $ozeki_url = $get_sms['api'];

              $url = 'username='.$ozeki_user;
              $url.= '&password='.$ozeki_password;
              $url.= '&balance='.'true&';

              $urltouse = $ozeki_url.$url;
              $response = file_get_contents($urltouse);
              ?>
              <label class="alert bg-orange btn-flat"><p><b>SMS Units:<b>: <b><?php echo number_format($response,2,'.',','); ?></b></p> </label>
              <a href="pay_sms.php?tid=<?php echo $_SESSION['tid']; ?>" class="btn bg-orange"><b>Top-up SMS</b></a>
              <?php
              }
              else{
              ?>
              <label class="alert bg-orange"><p><b>SMS Not Activated</b></p> </label>
        <?php 
          }
        }else{
        ?>

      <?php
              $check_sms = mysqli_query($link, "SELECT * FROM sms WHERE status = 'Activated' AND smsuser = ''") or die (mysqli_error($link));
              if(mysqli_num_rows($check_sms) == 1)
              {
              $get_sms = mysqli_fetch_array($check_sms);
              $ozeki_user = $get_sms['username'];
              $ozeki_password = $get_sms['password'];
              $ozeki_url = $get_sms['api'];

              $url = 'username='.$ozeki_user;
              $url.= '&password='.$ozeki_password;
              $url.= '&balance='.'true&';

              $urltouse = $ozeki_url.$url;
              $response = file_get_contents($urltouse);
              if($response == '0'){
              ?>
               <a href="pay_sms.php" class="btn bg-orange"><b>Top-up SMS</b></a>
              <?php
              }
              else{
              ?>
              <label class="alert bg-orange btn-flat"><p><b>SMS Units:<b>: <b><?php echo number_format($response,2,'.',','); ?></b></p> </label>
              <?php
              }
            }
              else{
              ?>
              <label class="alert alert-danger"><p><b>SMS Not Activated</b></p> </label>
              <?php } } ?>
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
if($arole == "agent_manager" || $arole == "i_a_demo")
{
?>

<?php
$search_duration = mysqli_query($link, "SELECT * FROM saas_subscription_trans WHERE sub_token = '$asub_token' AND usage_status = 'Active'");
if(mysqli_num_rows($search_duration) == 0){

  echo "<br><div style='color: white; font-size: 18px'>No Active Subscription: <a href='make_saas_sub.php?tid=".$_SESSION['tid']."&&mid=NDIw' class='btn bg-orange'><b>Activate Subscription</b></a></span></div>";

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

if($months == 0)
{
?>
<br>
<div style="color: white; font-size: 18px">Subscription Expired: <a href="make_saas_sub.php?id=<?php echo $_SESSION['tid']; ?>&&mid=NDIw" class="btn bg-yellow"><b>Renew Subscription</b></a></span></div>
<script type="text/javascript">
  alert('Subscription Expired!.. Click Ok to Renew Your Subscription!!');
  window.location='make_saas_sub.php?id=<?php echo $_SESSION['tid']; ?>&&cstaus=Expired&&mid=NDIw';
</script>
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
countdownDiv.innerHTML = "<b>" + dmin + "</b> m <b>" + dsec + "</b> s ";

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


<?php
}
else{
  echo "";
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
            <img src="<?php echo $fetchsys_config['file_baseurl'].$row['image']; ?>" class="user-image" alt="User Image">

          <span class="hidden-xs"><?php echo $row ['name']; ?></span>
            </a>
            <ul class="dropdown-menu">
              <!-- User image -->
              <li class="user-header">
                <img src="<?php echo $fetchsys_config['file_baseurl'].$row['image']; ?>" class="img-circle" alt="User Image">
                <p>
                  <?php echo 'Username:'. $row['username']; ?>
                </p>
        <?php 
        }
        elseif(mysqli_num_rows($call2) == 1){ 
          $get_ag = mysqli_fetch_assoc($call2);
        ?>

            <img src="<?php echo $fetchsys_config['file_baseurl']; ?>Electronic 1.png" class="user-image" alt="User Image">

          <span class="hidden-xs"><?php echo $get_ag['fname']; ?></span>
            </a>
            <ul class="dropdown-menu">
              <!-- User image -->
              <li class="user-header">
                <img src="<?php echo $fetchsys_config['file_baseurl']; ?>Electronic 1.png" class="img-circle" alt="User Image">
                <p>
                  <?php echo 'Username:'. $get_ag['username']; ?>
                </p>

      <?php } ?>
                  </li>
        
              <!-- Menu Body -->
              <li class="user-body">
                <div class="row">
                  <div class="col-xs-4 text-center">

                    <?php echo ($airtime_other_apis == 1) ? '<a href="profile.php?id='.$_SESSION['tid'].'">Profile</a>' : ''; ?>
          
                  </div>

<div class="col-xs-4 text-center"><a href="inboxmessage.php?id=<?php echo $_SESSION['tid']; ?>&&mid=<?php echo base64_encode("406"); ?>">All Tickets</a></div>
                </div>
                <!-- /.row -->
              </li>
              <!-- Menu Footer-->
              <li class="user-footer">
                <?php echo '<b style="font-size: 16px;">'.$asub_token.'</b>'; ?>
                <div class="pull-right">
                  <?php 
                if($arole == "agent_manager" || $arole == "i_a_demo")
                {
                    ?>
                  <a href="../logout.php?id=<?php echo $agentid; ?>" class="btn bg-orange"><i class="fa fa-sign-out"></i>Sign out</a>
                  <?php
                }
                else{
                    ?>
                    <a href="../logout.php" class="btn bg-orange"><i class="fa fa-sign-out"></i>Sign out</a>
                    <?php
                }
                ?>
                </div>
              </li>
            </ul>
          </li>
          <!-- Control Sidebar Toggle Button -->
        </ul>
      </div>
    </nav>
  </header>