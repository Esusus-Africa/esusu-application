<?php
include "../config/connect.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<title>PLATFORM - MAINTENANCE MODE</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
<!--===============================================================================================-->	
	<link rel="icon" type="image/png" href="images/icons/favicon.ico"/>
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/bootstrap/css/bootstrap.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="fonts/font-awesome-4.7.0/css/font-awesome.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="fonts/iconic/css/material-design-iconic-font.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/animate/animate.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/select2/select2.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="css/util.css">
	<link rel="stylesheet" type="text/css" href="css/main.css">
<!--===============================================================================================-->
</head>
<body>

<?php 
$call = mysqli_query($link, "SELECT * FROM systemset");
$row = mysqli_fetch_array($call);
?>
	
	<div class="bg-img1 overlay1 size1 flex-w flex-c-m p-t-55 p-b-55 p-l-15 p-r-15" style="background-image: url('<?php echo $row['file_baseurl'].$row['lbackg']; ?>');">
		<div class="wsize1">
			<p class="txt-center p-b-23">
			    <img src="<?php echo $row['file_baseurl']; ?>maintenance.jpg" width="150px" height="150px">
				
			</p>

			<h3 class="l1-txt1 txt-center p-b-22">
            Temporarily down for maintenance.
            </h3>

			<p class="txt-center m2-txt1 p-b-67">
				Sorry for the inconvenience but we’re performing some maintenance/upgrade at the moment. we’ll be back online shortly!
            </p>

			<div class="flex-w flex-sa-m cd100 bor1 p-t-42 p-b-22 p-l-50 p-r-50 respon1">

            <?php
$search_duration = mysqli_query($link, "SELECT * FROM systemset WHERE maintenance_mode = 'ON'");
if(mysqli_num_rows($search_duration) == 0){
  echo "<script>window.location='../index.php?id=".$_GET['id']."'; </script>";
}
else{
$fetch_duration = mysqli_fetch_object($search_duration);
$dfrom = $fetch_duration->mt_dfrom;
$dto = $fetch_duration->mt_dto;

$now = time(); // or your date as well
$your_date = strtotime($dto);

$datediff = $your_date - $now;
$total_day = round($datediff / (60 * 60 * 24));

if($total_day <= -1)
{
    mysqli_query($link, "UPDATE systemset SET maintenance_mode = 'OFF'");
    echo "<script>window.location='../index.php?id=".$_GET['id']."'; </script>";
?>
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
countdownDiv.innerHTML = "<script><span class='small'><font color='red'>Times Up!</font></span></b>";

}
//if on day of occasion
else if(todayy == yr && todaym == (mo-1) && todayd == da)
{
// need to handle this!!

var countdownDiv = document.getElementById("countdown");
countdownDiv.innerHTML = "<span class='l1-txt2 p-b-4 hours'>" + dmin + "</span><span class='m2-txt2'> Minutes </span> <span class='l1-txt2 p-b-22'>:</span> <span class='l1-txt2 p-b-4 hours'>" + dsec + "</span><span class='m2-txt2'> Seconds </span>";

setTimeout("countdown()",1000)
}
//else, if not yet
else
{
var countdownDiv = document.getElementById("countdown");
countdownDiv.innerHTML = "<span class='l1-txt2 p-b-4 hours'>" + dday + "</span><span class='m2-txt2'> Day(s) </span> <span class='l1-txt2 p-b-22'>:</span> <span class='l1-txt2 p-b-4 hours'>" + dhour + "</span><span class='m2-txt2'> Hours </span> <span class='l1-txt2 p-b-22'>:</span> <span class='l1-txt2 p-b-4 hours'>" + dmin + "</span><span class='m2-txt2'> Minutes </span> <span class='l1-txt2 p-b-22'>:</span> <span class='l1-txt2 p-b-4 hours'>" + dsec + "</span><span class='m2-txt2'> Seconds </span>";

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
			</div>
        <!--
			<form class="flex-w flex-c-m contact100-form validate-form p-t-70">
				<div class="wrap-input100 validate-input where1" data-validate = "Email is required: ex@abc.xyz">
					<input class="s1-txt1 placeholder0 input100" type="text" name="email" placeholder="Email Address">
					<span class="focus-input100"></span>
				</div>

				<button class="flex-c-m s1-txt1 size2 how-btn trans-04 where1">
					Notify Me
				</button>
			</form>	
		-->
		</div>
	</div>



	

<!--===============================================================================================-->	
	<script src="vendor/jquery/jquery-3.2.1.min.js"></script>
<!--===============================================================================================-->
	<script src="vendor/bootstrap/js/popper.js"></script>
	<script src="vendor/bootstrap/js/bootstrap.min.js"></script>
<!--===============================================================================================-->
	<script src="vendor/select2/select2.min.js"></script>
<!--===============================================================================================-->
	<script src="vendor/countdowntime/moment.min.js"></script>
	<script src="vendor/countdowntime/moment-timezone.min.js"></script>
	<script src="vendor/countdowntime/moment-timezone-with-data.min.js"></script>
	<script src="vendor/countdowntime/countdowntime.js"></script>
	<script>
		$('.cd100').countdown100({
			/*Set Endtime here*/
			/*Endtime must be > current time*/
			endtimeYear: 0,
			endtimeMonth: 0,
			endtimeDate: 35,
			endtimeHours: 18,
			endtimeMinutes: 0,
			endtimeSeconds: 0,
			timeZone: "" 
			// ex:  timeZone: "America/New_York"
			//go to " http://momentjs.com/timezone/ " to get timezone
		});
	</script>
<!--===============================================================================================-->
	<script src="vendor/tilt/tilt.jquery.min.js"></script>
	<script >
		$('.js-tilt').tilt({
			scale: 1.1
		})
	</script>
<!--===============================================================================================-->
	<script src="js/main.js"></script>

</body>
</html>