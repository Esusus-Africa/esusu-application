<div class="row">     
        <section class="content">  
          <div class="box box-success">
            <div class="box-body">
              <div class="table-responsive">
             <div class="box-body">
<form method="post">
<?php
if(isMobileDevice()){
    //Your content or code for mobile devices goes here
?>

<?php
if($transfer_fund == 1)
{
  ?>
 <button type="button" class="btn btn-flat bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>" align="left" disabled>&nbsp;<b>Balance:</b>&nbsp;
<strong class="alert bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">
<?php
echo "<span id='wallet_balance'>".$vcurrency.number_format($vwallet_balance,2,'.',',')."</span>";
?> 
</strong>
  </button>
  <hr>
<?php
}
else{
  echo "";
}
?>
<?php
}
else{
    //Your content or code for desktop or computers devices
?>
<?php
if($transfer_fund == 1)
{
  ?>
<button type="button" class="btn btn-flat bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>" align="left" disabled>&nbsp;<b>Wallet Balance:</b>&nbsp;
<strong class="alert bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">
<?php
echo "<span id='wallet_balance'>".$vcurrency.number_format($vwallet_balance,2,'.',',')."</span>";
?> 
</strong>
  </button>
<?php
}
else{
  echo "";
}
?>
  
<?php
}
?>
  <hr>    
        
<div class="col-md-14">
            <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
             <li <?php echo ($_GET['tab'] == 'tab_1') ? "class='active'" : ''; ?>><a href="bvnValidation.php?tid=<?php echo $_SESSION['tid']; ?>&&mid=OTAw&&tab=tab_1">Verify BVN</a></li>

             <li <?php echo ($_GET['tab'] == 'tab_2') ? "class='active'" : ''; ?>><a href="bvnValidation.php?tid=<?php echo $_SESSION['tid']; ?>&&mid=OTAw&&tab=tab_2">Log History</a></li>

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
               
     <p style="color: <?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>;"> Note that BVN Verification cost <b style="color: <?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;"><?php echo $fetchsys_config['currency'].number_format($fetchsys_config['bvn_fee'],2,'.',','); ?> per request</b> which is routed through your Super Wallet (Nigeria Account Only). </p>
			 			
			<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">BVN Number</label>
                  <div class="col-sm-7">
                  <input name="mybvn" type="number" class="form-control" placeholder="Enter your bvn number" required>
                  </div>
                  <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;"></label>
                  </div>

<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;"></label>
                  <div class="col-sm-7">
<?php 
   if(isset($_POST['save']))
   {
       include ("../config/restful_apicalls.php");
       //$link = mysqli_connect('localhost','root','root','loan2','8888') or die('Unable to Connect to Database');
       
       $result = array();
       $bvn = mysqli_real_escape_string($link, $_POST['mybvn']);
       
       $systemset = mysqli_query($link, "SELECT * FROM systemset");
       $row1 = mysqli_fetch_object($systemset);
       $seckey = $row1->secret_key;
       $bvn_fee = $row1->bvn_fee;
       
       if($vwallet_balance < $bvn_fee){
           
           echo "<br><span style='color:".(($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color'])."'>Sorry! You do not have sufficient fund in your Wallet to perform this verification</span>";
           
       }
       else{
           
           $search_restapi = mysqli_query($link, "SELECT * FROM restful_apisetup WHERE api_name = 'bvn'");
           $fetch_restapi = mysqli_fetch_object($search_restapi);
           $api_url = $fetch_restapi->api_url;
           
           $url = $api_url.$bvn.'?seckey='.$seckey;
            	 
           $get_data = callAPI('GET', $url, false);
           $result = json_decode($get_data, true);
            	
           if($result['status'] == "success"){
               
               $wbalance = $vwallet_balance - $bvn_fee;
               
               $icm_id = "ICM".rand(10000,99999);
               
               $date_time = date("Y-m-d");
               
               $report = "First Name: ".$result['data']['first_name'].', ';
               $report .= "Last Name: ".$result['data']['last_name'].', ';
               $report .= "Middle Name: ".$result['data']['middle_name'].', ';
               $report .= "Date of Birth: ".$result['data']['date_of_birth'].', ';
               $report .= "Phone Number: ".$result['data']['phone_number'];
               
               $update_wallet = mysqli_query($link, "UPDATE vendor_reg SET wallet_balance = '$wbalance' WHERE companyid = '$vendorid'");
               
               $insert_income = mysqli_query($link, "INSERT INTO income VALUES(null,'','$icm_id','BVN','$bvn_fee','$date_time','BVN Charges')");
               
               $insert_report = mysqli_query($link, "INSERT INTO bvn_log VALUES(null,'$vendorid','$bvn_fee','$report',NOW())");
               
               echo "<p>Full Name: <b>".$result['data']['first_name'].' '.$result['data']['middle_name'].' '.$result['data']['last_name']."</b></p>";
          	   echo "<p>Date of Birth: <b>".$result['data']['date_of_birth'].'</b> Phone No.:<b>'.$result['data']['phone_number']."</b></p>";
            }
            else{
              
              $insert_report = mysqli_query($link, "INSERT INTO bvn_log VALUES(null,'$vendorid','$bvn_fee','BVN Verification Failed',NOW())");
          		echo "<br><span style='color:".(($myaltrow['alternate_color'] == '') ? 'blue' : $myaltrow['alternate_color'])."'>".$result['message']."</span>";
            }
           
       }
        
    }
?>			
</div>
   <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;"></label>
</div>

			       </div>
             
      
      <div class="form-group" align="right">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo (isset($_GET['id']) == true) ? 'black' : 'blue'; ?>;"></label>
                  <div class="col-sm-7">
                  <button name="save" type="submit" class="btn bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>"><i class="fa fa-check-circle-o">&nbsp;Verify</i></button>
                  </div>
                  <label for="" class="col-sm-3 control-label"></label>
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

        <div class="box-body">
           
       <form class="form-horizontal" method="post" enctype="multipart/form-data">
               
              <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th><input type="checkbox" id="select_all"/></th>
                  <th>Charges</th>
                  <th>Request Response</th>
                  <th>Date/Time</th>
                 </tr>
                </thead>
                <tbody> 
<?php
$search_trec = mysqli_query($link, "SELECT * FROM bvn_log WHERE userid = '$vendorid' ORDER BY id DESC") or die ("ERROR: " . mysqli_error($link));
while($row_trec = mysqli_fetch_array($search_trec))
{
  $date_time = $row_trec['date_time'];
  $utc_date = DateTime::createFromFormat('Y-m-d G:i:s',$date_time,new DateTimeZone(date_default_timezone_get()));
  $acst_date = clone $utc_date; // we don't want PHP's default pass object by reference here
  $acst_date->setTimeZone(new DateTimeZone('GMT+1'));
  $correctdate = $acst_date->format('Y-m-d g:i A');
?>   
                <tr>
                  <td><input id="optionsCheckbox" class="checkbox"  name="selector[]" type="checkbox" value="<?php echo $row_trec['id']; ?>"></td>
                  <td><?php echo "NGN".$row_trec['charges']; ?></td>
                  <td><?php echo $row_trec['message']; ?></td>
                  <td><?php echo $correctdate; ?></td>
               </tr>
<?php 
}
?>
             </tbody>
                </table> 
             
       </form>
       
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
</form>       

              </div>


  
</div>  
</div>
</div> 

</div>