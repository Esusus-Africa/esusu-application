<div class="row"> 
    
   <section class="content">
        
       <div class="box box-success">
           <div class="box-body">
             <div class="table-responsive"> 
            <div class="box-body">

     <div class="col-md-14">
            <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
             <li <?php echo ($_GET['tab'] == 'tab_1') ? "class='active'" : ''; ?>><a href="add_aggregator.php?id=<?php echo $_SESSION['tid']; ?>&&mid=<?php echo base64_encode("419"); ?>&&tab=tab_1">Aggregator Onboarding Form</a></li>
             
             <!--<li <?php //echo ($_GET['tab'] == 'tab_2') ? "class='active'" : ''; ?>><a href="add_aggregator.php?id=<?php echo $_SESSION['tid']; ?>&&mid=<?php echo base64_encode("441"); ?>&&tab=tab_2">Register Bulk Aggregator</a></li>-->
          
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
                
       <form class="form-horizontal" method="post" enctype="multipart/form-data">

<?php
if(isset($_POST['aggregister']))
{
	$query = mysqli_query($link, "SELECT * FROM systemset") or die (mysqli_error($link));		
	$r = mysqli_fetch_object($query);
	$sysabb = $r->abb;

	//Aggregator Records
	$merchant = mysqli_real_escape_string($link, $_POST['merchant']);
	$fname = mysqli_real_escape_string($link, $_POST['fname']);
	$lname = mysqli_real_escape_string($link, $_POST['lname']);
	$mname = mysqli_real_escape_string($link, $_POST['mname']);
	$addrs = mysqli_real_escape_string($link, $_POST['addrs']);
	$state =  mysqli_real_escape_string($link, $_POST['state']);
	$country =  mysqli_real_escape_string($link, $_POST['country']);
  $full_name = $lname.' '.$fname.' '.$mname;
	$dob = mysqli_real_escape_string($link, $_POST['dob']);
	$userBvn = mysqli_real_escape_string($link, $_POST['unumber']);

  $username = mysqli_real_escape_string($link, $_POST['username']);
	$password = random_strings(10);
  $encrypt = base64_encode($password);
    
  $email = mysqli_real_escape_string($link, $_POST['email']);
  $phone = mysqli_real_escape_string($link, $_POST['phone']);

  $gender =  mysqli_real_escape_string($link, $_POST['gender']);
	$aggr_id = "AGGR".rand(1000,9999);
	$transactionPin = substr((uniqid(rand(),1)),3,4);

	$aggr_co_type = mysqli_real_escape_string($link, $_POST['commtype']);
  $aggr_co_rate = mysqli_real_escape_string($link, $_POST['commrate']);

  $verify_email = mysqli_query($link, "SELECT * FROM aggregator WHERE email = '$email'");
	$detect_email = mysqli_num_rows($verify_email);

	$verify_phone = mysqli_query($link, "SELECT * FROM aggregator WHERE phone = '$phone'");
	$detect_phone = mysqli_num_rows($verify_phone);

	$verify_username = mysqli_query($link, "SELECT * FROM aggregator WHERE username = '$username'");
	$detect_username = mysqli_num_rows($verify_username);

	$verify_username2 = mysqli_query($link, "SELECT * FROM user WHERE username = '$username'");
	$detect_username2 = mysqli_num_rows($verify_username2);
	
	$date_time = date("Y-m-d h:i:s");

	$verify_id = mysqli_query($link, "SELECT * FROM member_settings WHERE companyid = '$merchant'");
	$detect_id = mysqli_fetch_array($verify_id);
	$dedicated_ussdprefix = $detect_id['dedicated_ussd_prefix'];	
  
	($userBvn != "") ? $processBVN = $newBV->walletAfricaBVNVerifier($walletafrica_skey,$userBvn,$link) : "";
	($userBvn != "") ? $ResponseCode = $processBVN['ResponseCode'] : "";

	//BVN Details
	$bvnlname = $processBVN['LastName'];
	$bvndob = $processBVN['DateOfBirth'];
	$default_dob = date("d-M-Y", strtotime($dob));
  $bvn_picture = $processBVN['Picture'];
  $dynamicStr = md5(date("Y-m-d h:i"));
  $image_converted = $newBV->base64_to_jpeg($bvn_picture, $dynamicStr.".png");
  $imagePath = $image_converted;

  //20 array row
  $mybvn_data = $processBVN['BVN']."|".$processBVN['FirstName']."|".$processBVN['LastName']."|".$processBVN['MiddleName']."|".$processBVN['DateOfBirth']."|".$processBVN['PhoneNumber']."|".$processBVN['Email']."|".$processBVN['EnrollmentBank']."|".$processBVN['EnrollmentBranch']."|".$processBVN['Gender']."|".$processBVN['LevelOfAccount']."|".$processBVN['LgaOfOrigin']."|".$processBVN['LgaOfResidence']."|".$processBVN['MaritalStatus']."|".$processBVN['NameOnCard']."|".$processBVN['Nationality']."|".$processBVN['StateOfOrigin']."|".$processBVN['StateOfResidence']."|".$processBVN['Title']."|".$processBVN['WatchListed']."|".$image_converted;
    
  if($detect_email == 1){

    echo "<p style='font-size:24px; color:orange;'>Sorry, Email Address has already been used.</p>";

	}
	elseif($detect_phone == 1){

    echo "<p style='font-size:24px; color:orange;'>Sorry, Phone Number has already been used.</p>";

	}
	elseif($detect_username == 1 || $detect_username2 == 1){

		echo "<p style='font-size:24px; color:orange;'>Sorry, Username has already been used.</p>";

	}
    elseif($userBvn != "" && $ResponseCode != "200"){
 
		echo "<p style='font-size:24px; color:orange;'>Sorry, We're unable to verify aggregator BVN at the moment, please try again later!! </p>";
	  
	}
	elseif($userBvn != "" && ($bvnlname != strtoupper($lname) || $bvndob != $default_dob)){

		echo "<p style='font-size:24px; color:orange;'>Sorry, Aggregator Details does not match with BVN Record!!</p>";
		echo "<p style='font-size:24px; color:orange;'>BVN Record:- Last Name: ".$bvnlname." | Date of Birth: ".$bvndob."</p>";
		echo "<p style='font-size:24px; color:orange;'>Provided Info:- Last Name: ".strtoupper($lname)." | Date of Birth: ".$default_dob."</p>";

	}
	else{

		$protocol = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
		$url = $protocol.$_SERVER['HTTP_HOST']."/?id=".$aggr_id;
		$ide = time();
		$shorturl = base_convert($ide,20,36);
		
		$shortenedurl = $protocol . $_SERVER['HTTP_HOST'] . '/?agr_key=' . $shorturl;

		foreach($_FILES['documents']['name'] as $key => $name){
        
			$newFilename = $name;
			
			if($newFilename == "")
			{
				echo "";
			}
			else{
				$newlocation = $newFilename;
				if(move_uploaded_file($_FILES['documents']['tmp_name'][$key], 'img/'.$newFilename))
				{
					mysqli_query($link, "INSERT INTO institution_legaldoc VALUES(null,'$aggr_id','$newlocation')") or die (mysqli_error($link));
				}
			}
		  
		}

		$rOrderID = "EA-bvnCharges-".time();
        
    $sms = "$sysabb>>>Welcome $fname! Your Aggregator ID: $aggr_id, Username: $username, Password: $password, Transaction Pin: $transactionPin. Login here: https://esusu.app";
        
    $sendSMS->smsWithNoCharges($sysabb, $phone, $sms, $rOrderID, $uid);
		$sendSMS->aggregatorWelcomeEmail($email, $fname, $shortenedurl, $username, $password);

		($userBvn == "" ? "" : ($ResponseCode != "200" && $ResponseCode != "" ? "" : mysqli_query($link, "INSERT INTO bvn_log VALUE(null,'','','$aggr_id','$uid','$mybvn_data','$bvn_fee','$date_time','$rOrderID')")));

    ($userBvn == "" ? "" : ($ResponseCode != "200" && $ResponseCode != "" ? "" : mysqli_query($link, "INSERT INTO expenses VALUES(null,'','$rOrderID','BVN Verification','$bvn_fee','$date','$full_name BVN Verification Charges as an Aggregator')")));

		mysqli_query($link, "INSERT INTO activate_member VALUES(null,'$url','$shorturl','No','$aggr_id')") or die ("Error: " . mysqli_error($link));

    mysqli_query($link, "INSERT INTO aggregator VALUES(null,'$aggr_id','','$fname','$lname','$mname','$gender','$dob','$email','$phone','$username','$encrypt','NGN','$aggr_co_type','$aggr_co_rate','$date_time','$merchant')") or die (mysqli_error($link));
		
		mysqli_query($link, "INSERT INTO user VALUES(null,'$fname','$lname','$mname','$email','$phone','$addrs','$userBvn','','$state','','$country','Pending','$username','$encrypt','$aggr_id','$imagePath','aggregator','','Registered','$merchant','AGGR','$transactionPin','0.0','','0.0','','','','$dedicated_ussdprefix','$gender','$dob','Disallow','Disallow','Disallow','$date_time','','','','','Verified','agent','$uid','NULL','Yes','VerveCard','Yes','123456','0.0')") or die (mysqli_error($link));
		
    echo "<p style='font-size:20px; color:blue;'>Account Created Successfully! An email / sms notification has been sent to the Aggregator.</p>";
		
  }

}
?>

             <div class="box-body">

             <div class="form-group">
                      <label for="" class="col-sm-3 control-label" style="color:blue;">Merchant</label>
                      <div class="col-sm-7">
                          <select name="merchant" class="form-control select2" required>
                              <option value="" selected='selected'>Select Merchant of your choice&hellip;</option>
                              <option value="MER-90645141">Halalvest</option>
                              <option value="INST-191587338134">Esusu.me</option>
                              <option value="INST-271603798946">esusuPAY</option>
                          </select>
                      </div>
                      <label for="" class="col-sm-2 control-label"></label>
                  </div>

             <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">First Name</label>
                  <div class="col-sm-7">
                  <input name="fname" type="text" class="form-control" placeholder="Your First Name" required>
                  </div>
                  <label for="" class="col-sm-2 control-label"></label>
                  </div>
                  
                  <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Last Name</label>
                  <div class="col-sm-7">
                  <input name="lname" type="text" class="form-control" placeholder="Your Last Name" required>
                  </div>
                  <label for="" class="col-sm-2 control-label"></label>
                  </div>

                  <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Middle Name</label>
                  <div class="col-sm-7">
                  <input name="mname" type="text" class="form-control" placeholder="Your Middle Name" required>
                  </div>
                  <label for="" class="col-sm-2 control-label"></label>
                  </div>

                  <div class="form-group">
                      <label for="" class="col-sm-3 control-label" style="color:blue;">KYC Document</label>
                      <div class="col-sm-7">
                        <input type='file' name="documents[]" multiple required/>
                        <span style="color: orange;">You are required to upload aggregator KYC document such as National ID/Passport and Utility Bill for verification purpose.</span>
                      </div>
                      <label for="" class="col-sm-2 control-label"></label>
                  </div>

                  <div class="form-group">
                    <label for="" class="col-sm-3 control-label" style="color:blue;">BVN</label>
                    <div class="col-sm-7">
                      <input name="unumber" type="text" class="form-control" placeholder="Valid BVN">
                    </div>
                    <label for="" class="col-sm-2 control-label"></label>
                  </div>
                  
                  <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Gender</label>
                  <div class="col-sm-7">
                    <select name="gender" class="form-control" required>
                                <option value="" selected='selected'>Select Gender&hellip;</option>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                    </select>
                </div>
                <label for="" class="col-sm-2 control-label"></label>
                </div>
                

<?php
//MINIMUM DATE
$min_date = new DateTime(date("Y-m-d"));
$min_date->sub(new DateInterval('P60Y'));
$mymin_date = $min_date->format('Y-m-d');

//MAXIMUM DATE
$max_date = new DateTime(date("Y-m-d"));
$max_date->sub(new DateInterval('P18Y'));
$mymax_date = $max_date->format('Y-m-d');
?>

          <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Date of Birth</label>
                  <div class="col-sm-7">
                  <input name="dob" type="date" class="form-control" placeholder="Date Format: mm/dd/yyyy" id="txtDate" min="<?php echo $mymin_date; ?>" max="<?php echo $mymax_date; ?>" required>
                  </div>
                  <label for="" class="col-sm-2 control-label"></label>
                  </div>

                
                  <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Email Address</label>
                  <div class="col-sm-7">
                  <input name="email" type="email" class="form-control" id="vbemail" onkeyup="veryEmail();" placeholder="Your Email Address" required>
                  <div id="myvemail"></div>
                  </div>
                  <label for="" class="col-sm-2 control-label"></label>
                  </div>
                  
                  <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Mobile Number</label>
                  <div class="col-sm-7">
                  <input name="phone" type="text" class="form-control" id="vbphone" onkeyup="veryBPhone();" placeholder="+2348111111111" required>
                  <p style="color: orange; font-size: 16px;">Phone Format: <b style="color: blue;">COUNTRY CODE + MOBILE NUMBER e.g +2348111111111, +12226373738</b>.</p>
                  <div id="myvphone"></div>
                  </div>
                  <label for="" class="col-sm-2 control-label"></label>
                  </div>

                <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Location</label>
                  <div class="col-sm-7">
                  <input name="addrs" type="text" id="autocomplete1" class="form-control" onFocus="geolocate()" placeholder="Location" required>
                  </div>
                  <label for="" class="col-sm-2 control-label"></label>
                  </div>
          
            <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">State</label>
                  <div class="col-sm-7">
                  <input name="state" type="text" id="autocomplete2" onFocus="geolocate()" class="form-control" placeholder="State" required>
                  </div>
                  <label for="" class="col-sm-2 control-label"></label>
                  </div>
    
              <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Country</label>
                  <div class="col-sm-7">
                      <select name="country" class="form-control select2" required>
                        <option value="" selected="selected">Please Select Country</option>
                        <option value="NG">Nigeria</option>
                        <option value="GH">Ghana</option>
                        <option value="KE">Kenya</option>
                        <option value="UG">Uganda </option>
                        <option value="TZ">Tanzania</option>
                      </select>                 
                    </div>
                    <label for="" class="col-sm-2 control-label"></label>
                </div>

                <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Commission Type</label>
                  <div class="col-sm-7">
                      <select name="commtype" class="form-control select2" required>
                        <option value="" selected="selected">Please Commission Type</option>
                        <option value="Flat">Flat</option>
                        <option value="Percentage">Percentage</option>
                      </select>                 
                    </div>
                    <label for="" class="col-sm-2 control-label"></label>
                </div>

                <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Commission Rate</label>
                  <div class="col-sm-7">
                  <input name="commrate" type="text" class="form-control" placeholder="Commission Rate " required>
                  </div>
                  <label for="" class="col-sm-2 control-label"></label>
                </div>
                  
                  <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Username</label>
                  <div class="col-sm-7">
                  <input name="username" type="text" class="form-control" id="vbusername" onkeyup="veryBUsername();" placeholder="Your Username" required>
                  <div id="myusername"></div>
                  </div>
                  <label for="" class="col-sm-2 control-label"></label>
                  </div>
        
        <div class="form-group" align="right">
                  <label for="" class="col-sm-3 control-label" style="color:blue;"></label>
                  <div class="col-sm-7">
                  <button name="aggregister" type="submit" class="btn bg-blue btn-flat"><i class="fa fa-cloud-upload">&nbsp;Register</i></button>
                  </div>
                  <label for="" class="col-sm-2 control-label"></label>
                  </div>

       </div>
        
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

  <?php
  }
}
?>
              <!-- /.tab-pane -->
            </div>
            <!-- /.tab-content -->
      
          </div>          
      </div>
    
              </div>
  
</div>  
</div>
</div>
</section>  
</div>