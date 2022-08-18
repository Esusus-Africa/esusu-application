<?php 
error_reporting(0); 
ini_set('max_execution_time', 0); // to get unlimited php script execution time
include "../config/session1.php"; 
require_once "../config/smsAlertClass.php";
function fill_branches_select_box($connect)
{
  $output = '';
  $query = "SELECT * FROM installed_module ORDER BY id ASC";
  $statement = $connect->prepare($query);
  $statement->execute();
  $result = $statement->fetchAll();
  foreach($result as $rowkey)
  {
    $output .= '<option value="'.$rowkey["module_name"].'">'.$rowkey["module_name"].'</option>';
  }
  return $output;
}
?>
<html>
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
<?php 
$id = $_SESSION['tid'];
$call = mysqli_query($link, "SELECT * FROM vendor_reg WHERE companyid = '$vendorid' ORDER BY id DESC");
if(mysqli_num_rows($call) == 0)
{
echo "<script>alert('Data Not Found14!'); </script>";
}
else
{
while($row = mysqli_fetch_assoc($call)){
?>

<link href="<?php echo $fetchsys_config['file_baseurl'].$row['logo']; ?>" rel="icon" type="dist/img">
<?php 
}
}
$cur_date = date("Y-m-d");
$veryfy_usage = mysqli_query($link, "SELECT * FROM saas_subscription_trans WHERE duration_to LIKE '$cur_date%' AND companyid = '$vendorid' AND usage_status = 'Active' ORDER BY id DESC");
if(mysqli_num_rows($veryfy_usage) == 1)
{
    $update_usage = mysqli_query($link, "UPDATE saas_subscription_trans SET usage_status = 'Expired' WHERE companyid = '$vendorid' AND usage_status = 'Active'");
}
else{
    
}
?>
  <?php
  $id = $_SESSION['tid'];
	$call = mysqli_query($link, "SELECT * FROM vendor_reg WHERE companyid = '$vendorid' ORDER BY id DESC");
	$row = mysqli_fetch_assoc($call);
	?>
  <title><?php echo $row['cname']; ?></title>
   <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.6 -->
  <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
  
   <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
  
  
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="../dist/css/AdminLTE.min.css">
  
  <!-- Google Font: Source Sans Pro -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="../plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
  
  
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="../dist/css/skins/_all-skins.min.css">
  <!-- iCheck -->
  <link rel="stylesheet" href="../plugins/iCheck/flat/blue.css">
  <!-- Morris chart -->
  <link rel="stylesheet" href="../plugins/morris/morris.css">
  <!-- bootstrap slider -->
  <link rel="stylesheet" href="../plugins/bootstrap-slider/slider.css">
  <!-- jvectormap -->
  <link rel="stylesheet" href="../plugins/jvectormap/jquery-jvectormap-1.2.2.css">
  <!-- Date Picker -->
  <link rel="stylesheet" href="../plugins/datepicker/datepicker3.css">
  <!-- Daterange picker -->
  <link rel="stylesheet" href="../plugins/daterangepicker/daterangepicker.css">
  
  <link rel="stylesheet" href="../plugins/select2/select2.min.css">

  <style type="text/css">
    /* Slideshow container */
    .slideshow-container {
      position: relative;
    }
    
    /* Slides */
    .mySlides {
      display: none;
      padding: 0px;
      text-align: center;
    }
    
    /* mynext & myprevious buttons */
    .myprev, .mynext {
      cursor: pointer;
      position: absolute;
      top: 50%;
      width: auto;
      margin-top: -30px;
      padding: 16px;
      color: #888;
      font-weight: bold;
      font-size: 20px;
      border-radius: 0 3px 3px 0;
      user-select: none;
    }
    
    /* Position the "mynext button" to the right */
    .mynext {
      position: absolute;
      right: 0;
      border-radius: 3px 0 0 3px;
    }
    
    /* On hover, add a black background color with a little bit see-through */
    .myprev:hover, .mynext:hover {
      background-color: rgba(0,0,0,0.8);
      color: white;
    }
    
    /* The dot/bullet/indicator container */
    .dot-container {
      text-align: center;
      padding: 5px;
      background: #ddd;
    }
    
    /* The dots/bullets/indicators */
    .dot {
      cursor: pointer;
      height: 15px;
      width: 15px;
      margin: 0 2px;
      background-color: #bbb;
      border-radius: 50%;
      display: inline-block;
      transition: background-color 0.6s ease;
    }
    
    /* Add a background color to the active dot/circle */
    .active, .dot:hover {
      background-color: white;
    }
    
    /* Add an italic font style to all quotes */
    q {font-style: italic;}
    
    /* Add a blue color to the author */
    .author {color: cornflowerblue;}
    </style>
  
  <style type="text/css">
  
  /* ==========================================================================
   Chrome Frame prompt
   ========================================================================== */

    .chromeframe {
        margin: 0.2em 0;
        background: #ccc;
        color: #000;
        padding: 0.2em 0;
    }
    
    /* ==========================================================================
   Author's custom styles
   ========================================================================== */

    #loader-wrapper {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 1000;
    }
    #loader {
        display: block;
        position: relative;
        left: 50%;
        top: 50%;
        width: 150px;
        height: 150px;
        margin: -75px 0 0 -75px;
        border-radius: 50%;
        border: 3px solid transparent;
        border-top-color: #3498db;
    
        -webkit-animation: spin 2s linear infinite; /* Chrome, Opera 15+, Safari 5+ */
        animation: spin 2s linear infinite; /* Chrome, Firefox 16+, IE 10+, Opera */
    
        z-index: 1001;
    }

    #loader:before {
        content: "";
        position: absolute;
        top: 5px;
        left: 5px;
        right: 5px;
        bottom: 5px;
        border-radius: 50%;
        border: 3px solid transparent;
        border-top-color: #e74c3c;

        -webkit-animation: spin 3s linear infinite; /* Chrome, Opera 15+, Safari 5+ */
        animation: spin 3s linear infinite; /* Chrome, Firefox 16+, IE 10+, Opera */
    }

    #loader:after {
        content: "";
        position: absolute;
        top: 15px;
        left: 15px;
        right: 15px;
        bottom: 15px;
        border-radius: 50%;
        border: 3px solid transparent;
        border-top-color: #f9c922;

        -webkit-animation: spin 1s linear infinite; /* Chrome, Opera 15+, Safari 5+ */
          animation: spin 1s linear infinite; /* Chrome, Firefox 16+, IE 10+, Opera */
    }

    @-webkit-keyframes spin {
        0%   { 
            -webkit-transform: rotate(0deg);  /* Chrome, Opera 15+, Safari 3.1+ */
            -ms-transform: rotate(0deg);  /* IE 9 */
            transform: rotate(0deg);  /* Firefox 16+, IE 10+, Opera */
        }
        100% {
            -webkit-transform: rotate(360deg);  /* Chrome, Opera 15+, Safari 3.1+ */
            -ms-transform: rotate(360deg);  /* IE 9 */
            transform: rotate(360deg);  /* Firefox 16+, IE 10+, Opera */
        }
    }
    @keyframes spin {
        0%   { 
            -webkit-transform: rotate(0deg);  /* Chrome, Opera 15+, Safari 3.1+ */
            -ms-transform: rotate(0deg);  /* IE 9 */
            transform: rotate(0deg);  /* Firefox 16+, IE 10+, Opera */
        }
        100% {
            -webkit-transform: rotate(360deg);  /* Chrome, Opera 15+, Safari 3.1+ */
            -ms-transform: rotate(360deg);  /* IE 9 */
            transform: rotate(360deg);  /* Firefox 16+, IE 10+, Opera */
        }
    }

    #loader-wrapper .loader-section {
        position: fixed;
        top: 0;
        width: 51%;
        height: 100%;
        background: #222222;
        z-index: 1000;
        -webkit-transform: translateX(0);  /* Chrome, Opera 15+, Safari 3.1+ */
        -ms-transform: translateX(0);  /* IE 9 */
        transform: translateX(0);  /* Firefox 16+, IE 10+, Opera */
    }

    #loader-wrapper .loader-section.section-left {
        left: 0;
    }

    #loader-wrapper .loader-section.section-right {
        right: 0;
    }

    /* Loaded */
    .loaded #loader-wrapper .loader-section.section-left {
        -webkit-transform: translateX(-100%);  /* Chrome, Opera 15+, Safari 3.1+ */
            -ms-transform: translateX(-100%);  /* IE 9 */
                transform: translateX(-100%);  /* Firefox 16+, IE 10+, Opera */

        -webkit-transition: all 0.2s 0.1s cubic-bezier(0.645, 0.045, 0.355, 1.000);  
                transition: all 0.2s 0.1s cubic-bezier(0.645, 0.045, 0.355, 1.000);
    }

    .loaded #loader-wrapper .loader-section.section-right {
        -webkit-transform: translateX(100%);  /* Chrome, Opera 15+, Safari 3.1+ */
            -ms-transform: translateX(100%);  /* IE 9 */
                transform: translateX(100%);  /* Firefox 16+, IE 10+, Opera */

        -webkit-transition: all 0.2s 0.1s cubic-bezier(0.645, 0.045, 0.355, 1.000);  
                transition: all 0.2s 0.1s cubic-bezier(0.645, 0.045, 0.355, 1.000);
    }
    
    .loaded #loader {
        opacity: 0;
        -webkit-transition: all 0.1s ease-out;  
                transition: all 0.1s ease-out;
    }
    .loaded #loader-wrapper {
        visibility: hidden;

        -webkit-transform: translateY(-100%);  /* Chrome, Opera 15+, Safari 3.1+ */
            -ms-transform: translateY(-100%);  /* IE 9 */
                transform: translateY(-100%);  /* Firefox 16+, IE 10+, Opera */

        -webkit-transition: all 0.1s 0.3s ease-out;  
                transition: all 0.1s 0.3s ease-out;
    }
    
    /* JavaScript Turned Off */
    .no-js #loader-wrapper {
        display: none;
    }
    .no-js h1 {
        color: #222222;
    }
  </style>
  
  <!--<style> 
        body { 
            animation: fadeInAnimation ease 3s;
            opacity: 0.1; 
            transition: opacity 3s; 
        }
        @keyframes fadeInAnimation { 
            0% { 
                opacity: 0; 
                pointer-events: none;
                transition: opacity 3s;
            }
            20% { 
                opacity: 0.2; 
                pointer-events: none;
                transition: opacity 3s;
            }
            40% { 
                opacity: 0.3; 
                pointer-events: none;
                transition: opacity 3s;
            }
            60% { 
                opacity: 0.5; 
                pointer-events: none;
                transition: opacity 3s;
            }
            80% { 
                opacity: 0.7; 
                pointer-events: none;
                transition: opacity 3s;
            }
            100% { 
                opacity: 1; 
                transition: opacity 3s;
            } 
        } 
    </style>-->
  
  <!-- bootstrap wysihtml5 - text editor -->
  <link rel="stylesheet" href="../plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">
<link rel="stylesheet" href="../dist/css/style.css" />
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <script type="text/javascript" src="../dist/js/calendar.js"></script>
    <strong> <link rel="stylesheet" href="../plugins/datatables/dataTables.bootstrap.css"></strong>

  <!-- Datatable new code -->
 <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>

<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/r/dt/jq-2.1.4,jszip-2.5.0,pdfmake-0.1.18,dt-1.10.9,af-2.0.0,b-1.0.3,b-colvis-1.0.3,b-html5-1.0.3,b-print-1.0.3,se-1.0.1/datatables.min.css"/>

<script type="text/javascript" src="https://cdn.datatables.net/r/dt/jq-2.1.4,jszip-2.5.0,pdfmake-0.1.18,dt-1.10.9,af-2.0.0,b-1.0.3,b-colvis-1.0.3,b-html5-1.0.3,b-print-1.0.3,se-1.0.1/datatables.min.js"></script>

	

<script type="text/javascript">
$(document).ready(function(){
    $('#select_all').on('click',function(){
        if(this.checked){
            $('.checkbox').each(function(){
                this.checked = true;
            });
        }else{
             $('.checkbox').each(function(){
                this.checked = false;
            });
        }
    });
    
    $('.checkbox').on('click',function(){
        if($('.checkbox:checked').length == $('.checkbox').length){
            $('#select_all').prop('checked',true);
        }else{
            $('#select_all').prop('checked',false);
        }
    });
});
</script>


  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
<script src="https://www.amcharts.com/lib/3/amcharts.js"></script>
<script src="https://www.amcharts.com/lib/3/serial.js"></script>
<script src="https://www.amcharts.com/lib/3/pie.js"></script>
<script src="https://www.amcharts.com/lib/3/plugins/export/export.min.js"></script>
<link rel="stylesheet" href="https://www.amcharts.com/lib/3/plugins/export/export.css" type="text/css" media="all" />
<script src="https://www.amcharts.com/lib/3/themes/light.js"></script>


<style>
#chartdiv {
	width		: 100%;
	height		: 400px;
	font-size	: 11px;
}					
.style2 {font-size: 24px}

.demo-container {
    width: 100%;
    max-width: 350px;
    margin: 10px auto;
}
form {
    margin: 30px;
}
.myinput {
    width: 100%;
    margin: 10px auto;
    display: block;
}
</style>

<script>
var chart = AmCharts.makeChart( "chartdiv", {
  "type": "serial",
  "theme": "light",
  "dataProvider": [ {
  			
    "country": "Approved",
    "visits": <?php 
			$call1 = mysqli_query($link, "SELECT * FROM loan_info WHERE status = 'Approved' AND vendorid = '$vendorid'");
			$num1 = mysqli_num_rows($call1);
			?>
			<?php echo $num1; ?>

  }, {
  
    "country": "Disapproved",
    "visits": <?php 
			$call2 = mysqli_query($link, "SELECT * FROM loan_info WHERE status = 'Disapproved' AND vendorid = '$vendorid'");
			$num2 = mysqli_num_rows($call2);
			?>
			<?php echo $num2; ?>
  }, {
  
    "country": "Pending",
    "visits": <?php 
			$call3 = mysqli_query($link, "SELECT * FROM loan_info WHERE status = 'Pending' AND vendorid = '$vendorid'");
			$num3 = mysqli_num_rows($call3);
			?>
			<?php echo $num3; ?>
  }, {
  
    "country": "Completed",
    "visits": <?php 
			$call4 = mysqli_query($link, "SELECT * FROM loan_info WHERE upstatus = 'Completed' AND vendorid = '$vendorid'");
			$num4 = mysqli_num_rows($call4);
			?>
			<?php echo $num4; ?>
  }, {
  
    "country": "Processing",
    "visits": <?php 
			$call5 = mysqli_query($link, "SELECT * FROM loan_info WHERE upstatus = 'Pending' AND vendorid = '$vendorid'");
			$num5 = mysqli_num_rows($call5);
			?>
			<?php echo $num5; ?>
  }, {
   
    "country": "Request",
    "visits": <?php 
			$call6 = mysqli_query($link, "SELECT * FROM loan_info WHERE vendorid = '$vendorid'");
			$num6 = mysqli_num_rows($call6);
			?>
			<?php echo $num6;?>
  } ],
  "valueAxes": [ {
    "gridColor": "#FFFFFF",
    "gridAlpha": 0.2,
    "dashLength": 0
  } ],
  "gridAboveGraphs": true,
  "startDuration": 1,
  "graphs": [ {
    "balloonText": "[[category]]: <b>[[value]]</b>",
    "fillAlphas": 0.8,
    "lineAlpha": 0.2,
    "type": "column",
    "valueField": "visits"
  } ],
  "chartCursor": {
    "categoryBalloonEnabled": false,
    "cursorAlpha": 0,
    "zoomable": false
  },
  "categoryField": "country",
  "categoryAxis": {
    "gridPosition": "start",
    "gridAlpha": 0,
    "tickPosition": "start",
    "tickLength": 20
  },
  "export": {
    "enabled": true
  }

} );
</script>

<style>
#chartdiv1 {
	width		: 100%;
	height		: 350px;
	font-size	: 11px;
}							
</style>

<!-- Chart code -->
<script>
var chart1 = AmCharts.makeChart( "chartdiv1", {
  "type": "pie",
  "theme": "light",
  "dataProvider": [ {
    "title": "Loan Balance",
    "value": 
	<?php
	$add=mysqli_query($link,'SELECT SUM(balance) from pay_schedule WHERE status = "UNPAID" AND vendorid = "$vendorid"');
  	while($row1= mysqli_fetch_array($add))
  	{
    $mark=$row1['SUM(balance)'];
 	?>
				
	<?php echo $mark; ?>
	<?php }?>

  }, {
    "title": "Loan Paid",
    "value": 
	<?php
	$add=mysqli_query($link,'SELECT SUM(payment) from pay_schedule WHERE status = "PAID" AND vendorid = "$vendorid"');
  	while($row2= mysqli_fetch_array($add))
  	{
    $mark2=$row2['SUM(payment)'];
 	?>
				
	<?php echo $mark2; ?>
	<?php }?>
  } ],
  "titleField": "title",
  "valueField": "value",
  "labelRadius": 5,

  "radius": "42%",
  "innerRadius": "60%",
  "labelText": "[[title]]",
  "export": {
    "enabled": true
  }
} );
</script>


<style>
#chartdiv7 {
	width	: 100%;
	height	: 400px;
}																		
</style>

<script>
var chart = AmCharts.makeChart("chartdiv7", {
    "type": "serial",
    "theme": "light",
    "marginTop":0,
    "marginRight": 80,
    "dataProvider": [{
	<?php 
	//$time = date("Y-m-d");
	//$datee = date("Y",strtotime($time));
	$call7 = mysqli_query($link, "SELECT SUM(amount_to_pay), pay_date FROM payments WHERE vendorid = '$vendorid'");
	$fetch = mysqli_fetch_array($call7);
	?>
        "year": "<?php echo date("Y", strtotime($fetch['pay_date'])); ?>",
        "value": <?php echo $fetch['SUM(amount_to_pay)']; ?>
    }, {
    <?php 
	//$time = date("Y-m-d");
	//$datee = date("m",strtotime($time));
	$call7 = mysqli_query($link, "SELECT SUM(balance), pay_date FROM loan_info WHERE vendorid = '$vendorid' ORDER BY id DESC");
	$fetch = mysqli_fetch_array($call7);
	?>
        "year": "<?php echo date("Y", strtotime($fetch['pay_date'])); ?>",
        "value": <?php echo $fetch['SUM(balance)']; ?>
    }],
    "valueAxes": [{
        "axisAlpha": 0,
        "position": "left"
    }],
    "graphs": [{
        "id":"g1",
        "balloonText": "[[category]]<br><b><span style='font-size:14px;'>[[value]]</span></b>",
        "bullet": "round",
        "bulletSize": 8,
        "lineColor": "#d1655d",
        "lineThickness": 2,
        "negativeLineColor": "#637bb6",
        "type": "smoothedLine",
        "valueField": "value"
    }],
    "chartScrollbar": {
        "graph":"g1",
        "gridAlpha":0,
        "color":"#888888",
        "scrollbarHeight":55,
        "backgroundAlpha":0,
        "selectedBackgroundAlpha":0.1,
        "selectedBackgroundColor":"#888888",
        "graphFillAlpha":0,
        "autoGridCount":true,
        "selectedGraphFillAlpha":0,
        "graphLineAlpha":0.2,
        "graphLineColor":"#c2c2c2",
        "selectedGraphLineColor":"#888888",
        "selectedGraphLineAlpha":1

    },
    "chartCursor": {
        "categoryBalloonDateFormat": "YYYY",
        "cursorAlpha": 0,
        "valueLineEnabled":true,
        "valueLineBalloonEnabled":true,
        "valueLineAlpha":0.5,
        "fullWidth":true
    },
    "dataDateFormat": "YYYY",
    "categoryField": "year",
    "categoryAxis": {
        "minPeriod": "YYYY",
        "parseDates": true,
        "minorGridAlpha": 0.1,
        "minorGridEnabled": true
    },
    "export": {
        "enabled": true
    }
});

chart.addListener("rendered", zoomChart);
if(chart.zoomChart){
	chart.zoomChart();
}

function zoomChart(){
    chart.zoomToIndexes(Math.round(chart.dataProvider.length * 0.4), Math.round(chart.dataProvider.length * 0.55));
}
</script>


  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesnt work if you view the page via file:// -->
  <!--[if lt IE 9] -->
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
<script type="text/javascript" src="jquery.min.js"></script>
  
<script type="text/javascript">
$(document).ready(function()
{
	
$(".customer").change(function()
{
var dataString = 'id='+ $(this).val();
$.ajax
({
type: "POST",
url: "ajax_cusact.php",
data: dataString,
cache: false,
success: function(html)
{
$(".account").html(html);
} 
});

});

$('.account').change(function(){									   
var dataString = 'id='+ $(this).val();
$.ajax
({
type: "POST",
url: "ajax_loan.php",
data: dataString,
cache: false,
success: function(html)
{
$(".loan").html(html);
} 
});

});



});
</script>


<script type="text/javascript">
  function veryUsername()
  {
   var verify_username=document.getElementById("vusername").value;

   if(verify_username)
   {
    $.ajax({
    type: "POST",
    url: "verify_username1.php",
    data: {
      my_username: verify_username
    },
    success: function(response) {
     // We get the element having id of display_info and put the response inside it
      //alert(response);
     $('#myusername').html(response);
    }
    });
   }
   else
   {
    $('#myusername').html("<label class='label label-danger'>Enter Unique Username</label>");
   }
  }
  
  function veryEmail()
  {
   var verify_email=document.getElementById("vemail").value;
   
   if(verify_email)
   {
    $.ajax({
    type: "POST",
    url: "verify_username1.php",
    data: {
      my_email: verify_email
    },
    success: function(response) {
     // We get the element having id of display_info and put the response inside it
      //alert(response);
     $('#myvemail').html(response);
    }
    });
   }
   else
   {
    $('#myvemail').html("<label class='label label-danger'>Enter Valid Email Address</label>");
   }
  }
  
  function veryPhone()
  {
   var verify_phone=document.getElementById("vphone").value;
   
   if(verify_phone)
   {
    $.ajax({
    type: "POST",
    url: "verify_username1.php",
    data: {
      my_phone: verify_phone
    },
    success: function(response) {
     // We get the element having id of display_info and put the response inside it
      //alert(response);
     $('#myvphone').html(response);
    }
    });
   }
   else
   {
    $('#myvphone').html("<label class='label label-danger'>Enter Phone Number</label>");
   }
  }
  </script>

<script type="text/javascript">
  function veryBUsername()
  {
   var verify_username=document.getElementById("vbusername").value;

   if(verify_username)
   {
    $.ajax({
    type: "POST",
    url: "verify_username.php",
    data: {
      my_username: verify_username
    },
    success: function(response) {
     // We get the element having id of display_info and put the response inside it
      //alert(response);
     $('#mybusername').html(response);
    }
    });
   }
   else
   {
    $('#mybusername').html("<label class='label label-danger'>Enter Unique Username</label>");
   }
  }
  
  function veryBEmail()
  {
   var verify_email=document.getElementById("vbemail").value;
   
   if(verify_email)
   {
    $.ajax({
    type: "POST",
    url: "verify_username.php",
    data: {
      my_email: verify_email
    },
    success: function(response) {
     // We get the element having id of display_info and put the response inside it
      //alert(response);
     $('#myvbemail').html(response);
    }
    });
   }
   else
   {
    $('#myvbemail').html("<label class='label label-danger'>Enter Valid Email Address</label>");
   }
  }
  
  function veryBPhone()
  {
   var verify_ccode=document.getElementById("myccode").value;
   var verify_phone=document.getElementById("vbphone").value;
   
   if(verify_phone)
   {
    $.ajax({
    type: "POST",
    url: "verify_username.php",
    data: {
      my_ccode: verify_ccode,
      my_phone: verify_phone
    },
    success: function(response) {
     // We get the element having id of display_info and put the response inside it
      //alert(response);
     $('#myvbphone').html(response);
    }
    });
   }
   else
   {
    $('#myvbphone').html("<label class='label label-danger'>Enter Phone Number</label>");
   }
  }

  function verifyVA()
  {
   var verify_va=document.getElementById("verify_virtualacct").value;

   if(verify_va)
   {
    $.ajax({
    type: "POST",
    url: "verify_va.php",
    data: {
      my_va: verify_va,
    },
    success: function(response) {
     // We get the element having id of display_info and put the response inside it
      //alert(response);
     $('#myVA').html(response);
    }
    });
   }
   else
   {
    $('#myVA').html("<label class='label label-danger'>Enter Recipient Wallet Account Number</label>");
   }
  }
  </script>

<script type="text/javascript">
function loaddata()
{
 var bvn=document.getElementById("unumber").value;

 if(bvn)
 {
  $.ajax({
  type: "POST",
  url: "verify_bvn.php",
  data: {
  	my_bvn: bvn
  },
  success: function(response) {
   // We get the element having id of display_info and put the response inside it
	  //alert(response);
   $('#bvn2').html(response);
  }
  });
 }

 else
 {
  $('#bvn2').html("<p class='label label-success'>Please Enter Correct BVN Number Here</p>");
 }
}
</script>

<script type="text/javascript">
function loadaccount()
{
 var actnumb=document.getElementById("account_number").value;
 var bcode=document.getElementById("bank_code").value;

 if(actnumb && bcode)
 {
  $.ajax({
  type: "POST",
  url: "verify_actnumber.php",
  data: {
    my_actno: actnumb,
    my_bcode: bcode
  },
  success: function(response) {
   // We get the element having id of display_info and put the response inside it
    //alert(response);
   $('#act_numb').html(response);
  }
  });
 }

 else
 {
  $('#act_numb').html("<p class='label label-success'>Please Enter Correct Account Number Here</p>");
 }
}
</script>

<script type="text/javascript">
function loadCustomerDetails()
{
 var custid=document.getElementById("customerid").value;
 var bcode=document.getElementById("bcode").value;

 if(custid)
 {
  $.ajax({
  type: "POST",
  url: "verify_customerid.php",
  data: {
    my_custid: custid,
    my_bcode: bcode
  },
  success: function(response) {
   // We get the element having id of display_info and put the response inside it
    //alert(response);
   $('#cust_name').html(response);
  }
  });
 }

 else
 {
  $('#cust_name').html("<p class='label label-success'>Please Enter Correct Account Number/IUC Number Here</p>");
 }
}
</script>


<script type="text/javascript">
function loadMyCustomerDetails()
{
 var custid=document.getElementById("customerid").value;
 var serviceid=document.getElementById("serviceid").value;
 var productcode=document.getElementById("productcode").value;
 
 if(custid)
 {
  $.ajax({
  type: "POST",
  url: "verify_mycustomerid.php",
  data: {
    my_custid: custid,
    my_serviceid: serviceid,
    my_productcode: productcode
  },
  success: function(response) {
   // We get the element having id of display_info and put the response inside it
    //alert(response);
   $('#cust_name').html(response);
  }
  });
 }

 else
 {
  $('#cust_name').html("<p class='label label-success'>Please Enter Correct Account Number/IUC Number Here</p>");
 }
}
</script>


<script type="text/javascript">
function fetchbanklist()
{
 var actnumb=document.getElementById("accountNo").value;
 var bcode=document.getElementById("bankCode").value;

 if(actnumb && bcode)
 {
  $.ajax({
  type: "POST",
  url: "verify_actnumber2.php",
  data: {
    my_actno: actnumb,
    my_bcode: bcode
  },
  success: function(response) {
   // We get the element having id of display_info and put the response inside it
    //alert(response);
   $('#act_numb').html(response);
  }
  });
 }

 else
 {
  $('#act_numb').html("<p class='label label-success'>Please Enter Correct Account Number Here</p>");
 }
}

function fetchsterlingbanklist()
{
 var actnumb=document.getElementById("accountNo").value;
 var bcode=document.getElementById("sterlingBankCode").value;

 if(actnumb && bcode)
 {
  $.ajax({
  type: "POST",
  url: "verify_actnumber3.php",
  data: {
    my_actno: actnumb,
    my_bcode: bcode
  },
  success: function(response) {
   // We get the element having id of display_info and put the response inside it
    //alert(response);
   $('#act_numb').html(response);
  }
  });
 }

 else
 {
  $('#act_numb').html("<p class='label label-success'>Please Enter Correct Account Number Here</p>");
 }
}

function fetchrubiesbanklist()
{
 var actnumb=document.getElementById("accountNo").value;
 var bcode=document.getElementById("rubiesBankCode").value;

 if(actnumb && bcode)
 {
  $.ajax({
  type: "POST",
  url: "verify_actnumber4.php",
  data: {
    my_actno: actnumb,
    my_bcode: bcode
  },
  success: function(response) {
   // We get the element having id of display_info and put the response inside it
    //alert(response);
   $('#act_numb').html(response);
  }
  });
 }

 else
 {
  $('#act_numb').html("<p class='label label-success'>Please Enter Correct Account Number Here</p>");
 }
}
</script>

<script type="text/javascript">
function loadbranchcode()
{
 var bank_id=document.getElementById("bank_id").value;

 if(bank_id)
 {
  $.ajax({
  type: "POST",
  url: "verify_bankid.php",
  data: {
    my_bcode: bank_id
  },
  success: function(response) {
   // We get the element having id of display_info and put the response inside it
    //alert(response);
   $('#branch_code').html(response);
  }
  });
 }

 else
 {
  $('#branch_code').html("<p class='label label-success'>No Bank Selected!</p>");
 }
}
</script>

<script type="text/javascript">
function loadbank()
{
 var country=document.getElementById("country").value;

 if(country)
 {
  $.ajax({
  type: "POST",
  url: "list_bank.php",
  data: {
    my_country: country
  },
  success: function(response) {
   // We get the element having id of display_info and put the response inside it
    //alert(response);
   $('#bank_list').html(response);
  }
  });
 }

 else
 {
  $('#bank_list').html("<p class='label label-success'>Please Select Country</p>");
 }
}
</script>

<script type="text/javascript">
function loadbank1()
{
 var country=document.getElementById("country").value;

 if(country)
 {
  $.ajax({
  type: "POST",
  url: "list_bank1.php",
  data: {
    my_country: country
  },
  success: function(response) {
   // We get the element having id of display_info and put the response inside it
    //alert(response);
   $('#bank_list1').html(response);
  }
  });
 }

 else
 {
  $('#bank_list1').html("<p class='label label-success'>Please Select Country</p>");
 }
}
</script>

<script type="text/javascript">
function loadpB()
{
 var pbid=document.getElementById("product_id").value;
 var category=document.getElementById("cat").value;


 if(pbid)
 {
  $.ajax({
  type: "POST",
  url: "verify_plist.php",
  data: {
    my_pbid: pbid,
    my_cat: category
  },
  success: function(response) {
   // We get the element having id of display_info and put the response inside it
    //alert(response);
   $('#get_product').html(response);
  }
  });
 }

 else
 {
  $('#get_product').html("<p class='label label-success'>Please Enter Correct PRODUCT ID Here</p>");
 }
}
</script>

<script type="text/javascript">
function load_billcat()
{
 var myservice=document.getElementById("bill_cat").value;
 //var myplans=document.getElementById("plan_list").value;
 //var myscard=document.getElementById("my_scard").value;
 
 if(myservice)
 {
  $.ajax({
  type: "POST",
  url: "validate_billsproduct.php",
  data: {
    service_name: myservice
  },
  success: function(response) {
   // We get the element having id of display_info and put the response inside it
    //alert(response);
   $('#product_list').html(response);
  }
  });
 }
 
 else
 {
  $('#product_list').html("<p class='label bg-orange'>Waiting...</p>");
 }
 
}
</script>

<script type="text/javascript">
function load_airtime()
{
 var my_aphone=document.getElementById("my_phone_no").value;
 //var myplans=document.getElementById("plan_list").value;
 //var myscard=document.getElementById("my_scard").value;
 
 if(my_aphone)
 {
  $.ajax({
  type: "POST",
  url: "validate_airtimelist.php",
  data: {
    myphone: my_aphone
  },
  success: function(response) {
      //getProgress();
   // We get the element having id of display_info and put the response inside it
    //alert(response);
   $('#airtime_list').html(response);
  }
  });
 }
 
 else
 {
  $('#airtime_list').html("<p class='label bg-orange'>Loading...</p>");
 }
 
}
</script>

<script type="text/javascript">
//Start the long running process
    $.ajax({
        url: 'long_process.php',
        success: function(data) {
        }
    });
    //Start receiving progress
    function getProgress(){
        $.ajax({
            url: 'progress.php',
            success: function(data) {
                $("#progress").html(data);
                if(data<10){
                    getProgress();
                }
            }
        });
    }
</script>

<script type="text/javascript">
function load_databundle()
{
 var myaphone=document.getElementById("myphone_no").value;
 //var myplans=document.getElementById("plan_list").value;
 //var myscard=document.getElementById("my_scard").value;
 
 if(myaphone)
 {
  $.ajax({
  type: "POST",
  url: "validate_databundle.php",
  data: {
    a_myphone: myaphone
  },
  success: function(response) {
      //getProgress();
   // We get the element having id of display_info and put the response inside it
    //alert(response);
   $('#databundle_list').html(response);
  }
  });
  
 }
 
 else
 {
  $('#databundle_list').html("<p class='label bg-orange'>Data Loading...</p>");
 }
 
}
</script>

<script language="javascript" type="text/javascript">
	$().ready(function () {
		$('.modal.printable').on('shown.bs.modal', function() {
			$('modal-dialog', this).addClass('focused');
			$('body').addClass('modalprinter');
			
		if($(this).hasClass('autoprint')) {
			window.print();
		}
		}).on('hidden.bs.modal', function() {
			$('modal-dialog', this).removeClass('focused');
			$('body').removeClass('modalprinter');
		});
	});
</script>

<script language="javaScript">
<!-- 	
function enable_text(status)
{
//alert(status);
status=!status;	
document.f1.bcountry.disabled = status;
document.f1.currency.disabled = status;
}
//  End -->
</script>

<script type="text/javascript"> 
jQuery(document).ready(function(){
  jQuery(function() {
        jQuery(this).bind("contextmenu", function(event) {
            event.mypreventDefault();
        });
    });
});
</script>

</head>
<?php 
	$call = mysqli_query($link, "SELECT * FROM systemset");
	$row = mysqli_fetch_array($call);
	
	$myaltcall = mysqli_query($link, "SELECT * FROM member_settings WHERE companyid = '$vcreated_by'");
	$myaltrow = mysqli_fetch_array($myaltcall);
	?>
<body class="hold-transition skin-<?php echo ($myaltrow['theme_color'] == '') ? $row['theme_color'] : $myaltrow['theme_color']; ?> sidebar-mini";>
    
    <div id="loader-wrapper">
			<div id="loader"></div>

			<div class="loader-section section-left"></div>
            <div class="loader-section section-right"></div>

		</div>