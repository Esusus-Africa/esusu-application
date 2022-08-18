<?php include("include/header.php"); ?>
<div class="wrapper">

<?php include("include/top_bar.php"); ?>
  <!-- Left side column. contains the logo and sidebar -->
<?php include("include/side_bar.php"); ?>
  <!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    
	<!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Dashboard [<b><?php echo $v_ctype; ?> Platform</b>]
      </h1>
      <ol class="breadcrumb">
        <li><a href="dashboard.php<?php echo $_SESSION['tid']; ?>"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Dashboard</li>
      </ol>

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

  echo "<script>alert('Oops! No Active Subscription Yet... \\nClick Ok to Subscribe'); </script>";
  echo "<script>window.location='make_saas_sub.php?id=".$_SESSION['tid']."&&mid=NDIw'; </script>";

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
countdownDiv.innerHTML = "<b>" + dmin + "</b> m <b>" + dsec + "</b> s ";

setTimeout("countdown()",1000)
}
//else, if not yet
else
{
var countdownDiv = document.getElementById("countdown");
countdownDiv.innerHTML = "<p><div style='color: white; font-size: 17px' class='btn bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>'><font color=#ffffff><b style='font-size: 21px'>" + dday + "</b> days</font> <b style='font-size: 21px'> " + dhour + "</b> hours <b style='font-size: 21px'>" + dmin + "</b> minutes <b style='font-size: 21px'>" + dsec + "</b> seconds</div></p>";

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

<?php } ?>
    </section>

    <!-- Main content -->

    <section class="content">
      <!-- Small boxes (Stat box) -->
		<?php include("include/dashboard_chart.php"); ?>   
	</section>
</div>
		
<?php include("include/footer.php"); ?>