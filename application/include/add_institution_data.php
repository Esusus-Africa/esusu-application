<div class="row"> 
    
   <section class="content">
        
       <div class="box box-success">
           <div class="box-body">
             <div class="table-responsive"> 
            <div class="box-body">

     <div class="col-md-14">
            <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
             <li <?php echo ($_GET['tab'] == 'tab_1') ? "class='active'" : ''; ?>><a href="add_institution?id=<?php echo $_SESSION['tid']; ?>&&mid=<?php echo base64_encode("419"); ?>&&tab=tab_1">Client Onboarding Form</a></li>
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
if(isset($_POST['save']))
{
	
	function random_password($limit)
	{
	    return substr(base_convert(sha1(uniqid(mt_rand())), 16, 36), 0, $limit);
  }
  
	$query = mysqli_query($link, "SELECT * FROM systemset") or die (mysqli_error($link));		
	$r = mysqli_fetch_object($query);
	$sys_abb = $r->abb;
	$sys_email = $r->email;
	$subaccount_charges = $r->subaccount_charges;
  $wprefund_bal = $r->wallet_prefound_amt;
  //$defaultIdVerificationCharges = $r->defaultIdVerificationCharges;

  $refid = "EA-walletPrefund-".rand(1000000,9999999);

	//Client Basic Info
  $itype = mysqli_real_escape_string($link, $_POST['itype']);
  $icat = mysqli_real_escape_string($link, $_POST['icat']);
	$iname = mysqli_real_escape_string($link, $_POST['iname']);
	$license_no = mysqli_real_escape_string($link, $_POST['license_no']);
	$sender_id = mysqli_real_escape_string($link, $_POST['senderid']);
	$currency_type = mysqli_real_escape_string($link, $_POST['currency']);

	//Contact Person Details
	$drID = "MEM".time();
  $fname = mysqli_real_escape_string($link, $_POST['fname']);
  $lname = mysqli_real_escape_string($link, $_POST['lname']);
  $mname = mysqli_real_escape_string($link, $_POST['mname']);
  $gender = mysqli_real_escape_string($link, $_POST['gender']);
  $official_email = mysqli_real_escape_string($link, $_POST['official_email']);
  $official_phone = mysqli_real_escape_string($link, $_POST['official_phone']);
  $addrs = mysqli_real_escape_string($link, $_POST['addrs']);
	$state =  mysqli_real_escape_string($link, $_POST['state']);
	$country =  mysqli_real_escape_string($link, $_POST['country']);
	$iusername = mysqli_real_escape_string($link, $_POST['username']);
  $userBvn = mysqli_real_escape_string($link, $_POST['unumber']);
  $dob = mysqli_real_escape_string($link, $_POST['dob']);
	$ipassword = random_password(10);
  $institutionID =  ($itype == "agent" ? 'AGT-'.time() : ($itype == "institution" ? 'INST-'.time() : 'MER-'.time()));
	
	$verify_email = mysqli_query($link, "SELECT * FROM institution_data WHERE official_email = '$official_email'");
	$detect_email = mysqli_num_rows($verify_email);

	$verify_phone = mysqli_query($link, "SELECT * FROM institution_data WHERE official_phone = '$official_phone'");
	$detect_phone = mysqli_num_rows($verify_phone);

	$verify_username = mysqli_query($link, "SELECT * FROM user WHERE username = '$iusername'");
	$detect_username = mysqli_num_rows($verify_username);
	
	$verify_id = mysqli_query($link, "SELECT * FROM member_settings WHERE sender_id = '$sender_id'");
  $detect_id= mysqli_num_rows($verify_id);

  $copyright = "Copyright ".date('Y').". Powered by Esusu Africa.";

  ($userBvn == "") ? "" : require_once "../config/bvnVerification_class.php";
  
  ($userBvn == "") ? "" : $processBVN = $newBV->walletAfricaBVNVerifier($walletafrica_skey,$userBvn,$link);
  ($userBvn == "") ? "" : $ResponseCode = $processBVN['ResponseCode'];
	
	if($detect_email == 1){

		echo "<p style='font-size:24px; color:orange;'>Sorry, Email Address has already been used.</p>";
  
  }
	elseif($detect_phone == 1){

		echo "<p style='font-size:24px; color:orange;'>Sorry, Phone Number has already been used.</p>";
  
  }
	elseif($detect_username == 1){

		echo "<p style='font-size:24px; color:orange;'>Sorry, Username has already been used.</p>";
  
  }
	elseif($detect_id == 1){

		echo "<p style='font-size:24px; color:orange;'>Sorry, Sender ID has already been used.</p>";
  
  }
  elseif($ResponseCode != "200" && $userBvn != ""){

    echo "<p style='font-size:24px; color:orange;'>Sorry, We're unable to verify user BVN at the moment, please try again later!! </p>";
  
  }
	else{

		$target_dir = "../img/";
		$target_file = $target_dir.basename($_FILES["image"]["name"]);
		$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
		$check = getimagesize($_FILES["image"]["tmp_name"]);

		$sourcepath = $_FILES["image"]["tmp_name"];
		$targetpath = "../img/" . $_FILES["image"]["name"];
		move_uploaded_file($sourcepath,$targetpath);
		
		$location = $_FILES['image']['name'];
		
    echo $filename=$_FILES["file"]["tmp_name"];
    
		foreach ($_FILES['documents']['name'] as $key => $name){
        
      $newFilename = $name;
      
      if($newFilename == "")
      {
          echo "";
      }
      else{
          $newlocation = $newFilename;
      if(move_uploaded_file($_FILES['documents']['tmp_name'][$key], '../img/'.$newFilename))
      {
          mysqli_query($link, "INSERT INTO institution_legaldoc VALUES(null,'$institutionID','$newlocation')") or die (mysqli_error($link));
      }
      }
    
    }

  	//BVN Details
    $bvn_picture = $processBVN['Picture'];
    $dynamicStr = md5(date("Y-m-d h:i"));
    $image_converted = $newBV->base64_to_jpeg($bvn_picture, $dynamicStr.".png");
    $imagePath = $image_converted;

    //20 array row
    $mybvn_data = $processBVN['BVN']."|".$processBVN['FirstName']."|".$processBVN['LastName']."|".$processBVN['MiddleName']."|".$processBVN['DateOfBirth']."|".$processBVN['PhoneNumber']."|".$processBVN['Email']."|".$processBVN['EnrollmentBank']."|".$processBVN['EnrollmentBranch']."|".$processBVN['Gender']."|".$processBVN['LevelOfAccount']."|".$processBVN['LgaOfOrigin']."|".$processBVN['LgaOfResidence']."|".$processBVN['MaritalStatus']."|".$processBVN['NameOnCard']."|".$processBVN['Nationality']."|".$processBVN['StateOfOrigin']."|".$processBVN['StateOfResidence']."|".$processBVN['Title']."|".$processBVN['WatchListed']."|".$image_converted;

    $encrypt = base64_encode($ipassword);
    
    $date_time = date("Y-m-d h:i:s");
    $date = date("Y-m-d");

    $detectPrefix = ($itype == "agent" ? "AG" : ($itype == "institution" ? "INS" : "MER"));
    $defaultRole = ($itype == "agent" ? "agent_manager" : ($itype == "institution" ? "institution_super_admin" : "merchant_super_admin"));

    $transactionPin = substr((uniqid(rand(),1)),3,4);

    $rOrderID = "EA-bvnCharges-".time();

    $query = mysqli_query($link, "SELECT * FROM systemset") or die (mysqli_error($link));    
    $r = mysqli_fetch_object($query);
    $sys_abb = $r->abb;
    $sys_email = $r->email;

    $posPIN = substr((uniqid(rand(),1)),3,6);
    
    $sms = "$r->abb>>>Dear $iname! Your Account has been activated. Login via: https://esusu.app/$sender_id";

    ($userBvn == "") ? "" : $insert = mysqli_query($link, "INSERT INTO bvn_log VALUE(null,'$institutionID','','$drID','$uid','$mybvn_data','$bvn_fee','$date_time','$rOrderID')");

    ($userBvn == "") ? "" : $insert = mysqli_query($link, "INSERT INTO expenses VALUES(null,'','$rOrderID','BVN Verification','$bvn_fee','$date','$iname Contact Person BVN Verification Charges')");

		$insert_institution = mysqli_query($link, "INSERT INTO institution_data VALUES(null,'$institutionID','NIL','$icat','$location','$iname','$license_no','$addrs','$state','$country','$official_email','$official_phone','$userBvn','$itype','Approved','Enable','0.0','$wprefund_bal','NULL','$date_time','','','')") or die ("Error: " . mysqli_error($link));
        
    $insert_institution = mysqli_query($link, "INSERT INTO wallet_history VALUE(null,'$institutionID','$refid','self','$wprefund_bal','','Credit','NGN','Prefund_Balance','Remarks: New User Wallet Bonus','successful','$date_time','$drID','','')");
    
		$insert_institution_user = mysqli_query($link, "INSERT INTO user VALUES(null,'$fname','$lname','$mname','$official_email','$official_phone','$addrs','$userBvn','','$state','','$country','Approved','$iusername','$encrypt','$drID','$imagePath','$defaultRole','','Registered','$institutionID','$detectPrefix','$transactionPin','0.0','','0.0','','','','','$gender','$dob','Allow','Allow','Allow','$date_time','','','','','Pending','agent','$uid','NULL','No','NULL','Yes','$posPIN','0.0')") or die (mysqli_error($link));

    $insert_institution = mysqli_query($link, "INSERT INTO member_settings VALUE(null,'$institutionID','$iname','','','$sender_id','$currency_type','','NotActive','','','','','100000','','','','No','No','No','','','','Off','Off','Off','Off','Off','Off','Off','Off','Off','Off','Off','Off','Off','Off','Off','Off','Off','Off','Off','No','','','','','Off','Disabled','Disabled','Disabled','Off','Off','None','Off','Off','Off','Wallet Africa','Off','Off','Providus Bank','Providus Bank','Off','Off','PrimeAirtime','PrimeAirtime','PrimeAirtime','None','None','Off','','Off','$copyright','No','','No','Yes','No','','Off','','Off')") or die ("Error: " . mysqli_error($link));
    
    $insert_institution = mysqli_query($link, "INSERT INTO maintenance_history VALUE(null,'$institutionID','2.5','','flat','7.5','1000','Activated','Hybrid','50','0','50','30','30','0','0','0.01','0.005','Flat','0','0','4','0','0')") or die ("Error: " . mysqli_error($link));
    
		if(!($insert_institution && $insert_institution_user)){

			echo '<span style="font-size:24px; color: orange;">Unable to Register New Client!...Try again later!</span>';

		}
    else{

      $sms_refid = uniqid("EA-smsCharges-").time();
      $max_per_page = 153;
      $sms_length = strlen($sms);
      $calc_length = ceil($sms_length / $max_per_page);
      $sms_rate = $r->fax;
      $sms_charges = $calc_length * $sms_rate;

      $sendSMS->backendGeneralAlert($sys_abb, $official_phone, $sms, $sms_refid, $sms_charges, $uid);
      $sendSMS->clientRegNotifier($official_email, $iname, $iusername, $ipassword, $sender_id);
    	echo '<span style="font-size:24px; color: blue;">Client Account Created Successfully...</span>';
    
    }

  }
  
}
?>


             <div class="box-body">

<hr>
<div class="alert bg-orange"><b>BASIC CLIENT INFORMATION</b></div>
<hr> 

      <div class="form-group">
        <label for="" class="col-sm-2 control-label" style="color:blue;">Logo</label>
        <div class="col-sm-8">
                <input type='file' name="image" class="btn bg-orange" onChange="readURL(this);">
                <img id="blah"  src="<?php echo $fetchsys_config['file_baseurl']; ?>user2.png" alt="Institution Logo Here" height="100" width="100"/>
        </div>
        <label for="" class="col-sm-2 control-label"></label>
      </div>
      
      <div class="form-group">
 		      <label for="" class="col-sm-2 control-label" style="color:blue;">Legal Document</label>
				  <div class="col-sm-8">
				    <input type='file' name="documents[]" multiple required/>
				    <span style="color: orange;">Here, you are required to upload all the valid document provided by the Merchant. AND Also, You can upload more than one documents at a time.</span>
          </div>
          <label for="" class="col-sm-2 control-label"></label>
       </div>
                
      <div class="form-group">
          <label for="" class="col-sm-2 control-label" style="color:blue;">Account Type</label>
          <div class="col-sm-8">
        		<select name="itype"  class="form-control select2" required>
                    <option value="" selected='selected'>Select Account Type&hellip;</option>
                    <option value="agent">Agent/Thrift Collector</option>
                    <option value="institution">Cooperative/Institution</option>
                    <option value="merchant">Merchant</option>
        		</select>
          </div>
          <label for="" class="col-sm-2 control-label"></label>
      </div>
      

      <div class="form-group">
          <label for="" class="col-sm-2 control-label" style="color:blue;">Account Category</label>
          <div class="col-sm-8">
        		<select name="icat"  class="form-control select2" required>
                    <option value="" selected='selected'>Select Account Category&hellip;</option>
                    <option value="Independent Thrift Collector">Independent Thrift Collector</option>
                    <option value="Mobile Money Agent">Mobile Money Agent</option>
                    <option value="Super Agent">Super Agent</option>
                    <option value="Microfinance Institution">Microfinance Institution</option>
                    <option value="Cooperative Society">Cooperative Society</option>
                    <option value="NGO">NGO</option>
                    <option value="Microfinance Bank">Microfinance Bank</option>
                    <option value="Lending Film">Lending Film</option>
        		</select>
          </div>
          <label for="" class="col-sm-2 control-label"></label>
		  </div>
		   
                  
      <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Business Name</label>
                  <div class="col-sm-8">
                  <input name="iname" type="text" class="form-control" placeholder="Business Name" required>
                  </div>
                  <label for="" class="col-sm-2 control-label"></label>
                  </div>
          
    <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">License No.</label>
                  <div class="col-sm-8">
                  <input name="license_no" type="text" class="form-control" placeholder="License Number">
                  </div>
                  <label for="" class="col-sm-2 control-label"></label>
                  </div>

      <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Sender ID</label>
                  <div class="col-sm-8">
                  <input name="senderid" type="text" id="vsid" onkeyup="verySID();" class="form-control" placeholder="SMS Notification ID" required>
                  <div id="myvsid"></div>
                  </div>
                  <label for="" class="col-sm-2 control-label"></label>
                  </div>

      <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Currency</label>
                  <div class="col-sm-8">
                  <select name="currency" class="form-control select2" required>
                          <option value="" selected='selected'>Select Currency&hellip;</option>
                          <option value="NGN">NGN</option>
                          <option value="USD">USD</option>
                          <option value="GHS">GHS</option>
                          <option value="KES">KES</option>
                  </select>
                  </div>
                  <label for="" class="col-sm-2 control-label"></label>
		            </div>

<hr>
<div class="alert bg-orange"><b>CONTACT PERSON</b></div>
<hr>

    <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">First Name</label>
                  <div class="col-sm-8">
                  <input name="fname" type="text" class="form-control" placeholder="First Name" required>
                  </div>
                  <label for="" class="col-sm-2 control-label"></label>
                  </div>
    
    <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Last Name</label>
                  <div class="col-sm-8">
                  <input name="lname" type="text" class="form-control" placeholder="Last Name" required>
                  </div>
                  <label for="" class="col-sm-2 control-label"></label>
                  </div>

    <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Middle Name</label>
                  <div class="col-sm-8">
                  <input name="mname" type="text" class="form-control" placeholder="Middle Name" required>
                  </div>
                  <label for="" class="col-sm-2 control-label"></label>
                  </div>

     <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">BVN</label>
                  <div class="col-sm-8">
                  <input name="unumber" type="text" class="form-control" placeholder="Valid BVN">
                  </div>
                  <label for="" class="col-sm-2 control-label"></label>
                  </div>

    <div class="form-group">
        <label for="" class="col-sm-2 control-label" style="color:blue;">Gender</label>
        <div class="col-sm-8">
            <select name="gender" class="form-control select2" required>
              <option value="" selected="selected">Please Gender</option>
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
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Date of Birth</label>
                  <div class="col-sm-8">
                  <input name="dob" type="date" class="form-control" placeholder="Date Format: mm/dd/yyyy" id="txtDate" min="<?php echo $mymin_date; ?>" max="<?php echo $mymax_date; ?>" required>
                  </div>
                  <label for="" class="col-sm-2 control-label"></label>
                  </div>

    <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Email Address</label>
                  <div class="col-sm-8">
                  <input name="official_email" type="email" class="form-control" placeholder="Email Address" required>
                  </div>
                  <label for="" class="col-sm-2 control-label"></label>
                  </div>

    <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Phone Number</label>
                  <div class="col-sm-8">
                  <input name="official_phone" type="text" class="form-control" placeholder="Phone Number" required>
                  </div>
                  <label for="" class="col-sm-2 control-label"></label>
                  </div>

    <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Address</label>
                  <div class="col-sm-8">
                  <input name="addrs" type="text" id="autocomplete1" class="form-control" onFocus="geolocate()" placeholder="Location" required>
                  </div>
                  <label for="" class="col-sm-2 control-label"></label>
                  </div>
          
    <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">State</label>
                  <div class="col-sm-8">
                  <input name="state" type="text" id="autocomplete2" onFocus="geolocate()" class="form-control" placeholder="State" required>
                  </div>
                  <label for="" class="col-sm-2 control-label"></label>
                  </div>
    
    <div class="form-group">
        <label for="" class="col-sm-2 control-label" style="color:blue;">Country</label>
        <div class="col-sm-8">
            <select name="country" class="form-control select2" required>
              <option value="" selected="selected">Select Country</option>
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
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Username</label>
                  <div class="col-sm-8">
                  <input name="username" type="text" class="form-control" id="vusername" onkeyup="veryUsername();" placeholder="Username" required>
                  <div id="myusername"></div>
                  </div>
                  <label for="" class="col-sm-2 control-label"></label>
                  </div>

       </div>
       

       <div class="form-group" align="right">
                <label for="" class="col-sm-2 control-label" style="color:blue;"></label>
                <div class="col-sm-8">
                  <button name="save" type="submit" class="btn bg-blue"><i class="fa fa-save">&nbsp;Submit</i></button>
                </div>
                <label for="" class="col-sm-2 control-label"></label>
            </div>
        
       </form> 
       
              </div>
              <!-- /.tab-pane -->
  <?php
  }
  elseif($tab == 'tab_2')
  {
  ?>
<!--
              <div class="tab-pane <?php echo ($_GET['tab'] == 'tab_2') ? 'active' : ''; ?>" id="tab_2">
<hr>
<div class="bg-blue">&nbsp;<b> CSV FILE SECTION </b></div>
<hr>         
           <form class="form-horizontal" method="post" enctype="multipart/form-data">
             
<div class="box-body">
    
  <div class="form-group">
           <label for="" class="col-sm-4 control-label" style="color: blue;">Import Bulk Details Here:</label>
  <div class="col-sm-8">
        <span class="fa fa-cloud-upload"></span>
           <span class="ks-text">Choose file</span>
           <input type="file" name="file" accept=".csv" required>
  </div>
  </div>

   <hr>
  <p style="color:orange"><b style="color:blue;">NOTE:</b><br>
    <span style="color:blue;">(1)</span> <i>Kindly download the <a href="../sample/institution_bulk_sample.csv"> <b class="btn bg-blue"><i class="fa fa-download"></i> sample file</b></a> to upload the details once.</i></p>
    <p><span style="color:blue;">(2)</span> <i style="color:orange;">Please note that the <span style="color: blue">mio</span> stand for <span style="color: blue">Mode of Identification</span>. And it has to be either <span style="color: blue">National ID OR International Passport</span></i></p>
    <p><span style="color:blue;">(2)</span> <i style="color:orange;">Also note that the <span style="color: blue">d_type</span> stand for <span style="color: blue">Directorate Type</span>. And it has to be either <span style="color: blue">Partnership OR Sole Proprietorship</span></i></p>
    <p><span style="color:blue;">(3)</span> <i style="color:orange;">Also, make sure you enter <span style="color: blue">Valid BVN</span> as it will be verify while uploading the csv file. And failure to validate the bvn will result to <span style="color: blue;"> Uploading not successful.</span></i></p>
    <p><span style="color:blue;">(4)</span> <i style="color:orange;">Finally, take note of the <span style="color: blue">institution_logo AND d_image (directorate passport)</span> which are to be written in this format: <b style="color:blue;">img/image_name.image_format</b>. The image format can be <b style="color: blue;">.png, .jpg, etc.</b></i></p>
                        
  <hr>
  
    <div align="right">
       <div class="box-footer">
       <button class="btn bg-blue ks-btn-file" type="submit" name="ImportData"><span class="fa fa-cloud-upload"></span> Import Data</button> 
       </div>
    </div>  

</div>  

<?php
if(isset($_POST["ImportData"]))
{

    function random_password($limit)
    {
        return substr(base_convert(sha1(uniqid(mt_rand())), 16, 36), 0, $limit);
    }

    $systemset = mysqli_query($link, "SELECT * FROM systemset");
    $row1 = mysqli_fetch_object($systemset);

    echo $filename=$_FILES["file"]["tmp_name"];
    
    $allowed_filetypes = array('csv');
    if(!in_array(end(explode(".", $_FILES['file']['name'])), $allowed_filetypes))
        {
        echo "<script type=\"text/javascript\">
            alert(\"The file you attempted to upload is not allowed.\");
          </script>";
        }    
    elseif($_FILES["file"]["size"] > 0)
     {
        $file = fopen($filename, "r");
           while (($emapData = fgetcsv($file, 10000, ",")) !== FALSE)
           {
            $result = array();
            $bvn = $emapData[14];

            //The parameter after verify/ is the transaction reference to be verified
            $url = 'https://api.paystack.co/bank/resolve_bvn/'.$bvn;

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt(
              $ch, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer '.$row1->secret_key]
            );

            $request = curl_exec($ch);
            if(curl_error($ch)){
             echo 'error:' . curl_error($ch);
             }
            curl_close($ch);

            //START

            if($request){
                 $result = json_decode($request, true);
                 //print_r($result);
               }

                if (array_key_exists('data', $result) && array_key_exists('status', $result['data'])) {

                  //START2

                  $institutnID = "INST-".mt_rand(10200066,99999999);
                  $drID = 'DIR'.mt_rand(10000000,99999999);
                  $ipassword = random_password(10);

                  if($emapData[0] && $emapData[1] && $emapData[2] && $emapData[3] && $emapData[4] && $emapData[5] && $emapData[6] == "")
                  {

                    $sql2 = "INSERT INTO institution_user(id,directorate_id,d_type,d_image,d_name,gender,email,mobile_no,moi,id_number,username,password,bvn,institution_id,i_branchid,urole,reg_date) VALUES(null,'$drID','$emapData[10]','$emapData[11]','$emapData[12]','$emapData[13]','$emapData[14]','$emapData[15]','$emapData[16]','$emapData[17]','$emapData[18]','$ipassword','$emapData[19]','$institutnID','','institution_super_admin',NOW())";
                   //we are using mysql_query function. it returns a resource on true else False on error
                    $outcome = mysqli_query($link,$sql2);

                    include("email_sender/add_allbulkinstitutionmsg");

                  }
                  else{

                    //START3

                    $sql1 = "INSERT INTO institution_data(id,institution_id,institution_logo,institution_name,license_no,location,state,country,official_phone,reg_date) VALUES(null,'$institutnID','$emapData[0]','$emapData[1]','$emapData[2]','$emapData[3]','$emapData[4]','$emapData[5]','$emapData[6]','$emapData[7]','$emapData[8]','$emapData[9]','$emapData[10]','$emapData[11]','$emapData[12]',NOW())";
                    $outcome = mysqli_query($link,$sql1);

                    $sql2 = "INSERT INTO institution_user(id,directorate_id,d_type,d_image,d_name,gender,email,mobile_no,moi,id_number,username,password,institution_id,i_branchid,urole,reg_date) VALUES(null,'$drID','$emapData[10]','$emapData[11]','$emapData[12]','$emapData[13]','$emapData[14]','$emapData[15]','$emapData[16]','$emapData[17]','$emapData[18]','$ipassword','$emapData[19]','$institutnID','','institution_super_admin',NOW())";
                   //we are using mysql_query function. it returns a resource on true else False on error
                    $outcome2 = mysqli_query($link,$sql2);

                    include("email_sender/add_allbulkinstitutionmsg");

                    if(!$outcome)
                    {
                      echo "<script type=\"text/javascript\">
                      alert(\"Invalid File:Please Upload CSV File.\");
                      </script>";
                    }

                    //END3

                 }

                  //END2

               }
               else{
                  echo "<script type=\"text/javascript\">
                  alert('".$result['message']."');
                  </script>";
               }
             

            //END

           }
           fclose($file);
            //throws a message if data successfully imported to mysql database from excel file
            echo "<script type=\"text/javascript\">
                alert(\"Data Uploaded successfully.\");
              </script>";
          }
    }
?>    
           </form>
<hr>
<div class="bg-blue">&nbsp;<b> IMAGE SECTION </b></div>
<hr>
           <form class="form-horizontal" method="post" enctype="multipart/form-data">
             
<div class="box-body">
    
  <div class="form-group">
           <label for="" class="col-sm-4 control-label" style="color: blue;">Import Bulk Logos Here:</label>
  <div class="col-sm-8">
        <span class="fa fa-cloud-upload"></span>
           <span class="ks-text">Choose file</span>
           <input type="file" name="uploaded_file[]" multiple required>
  </div>
  </div>

   <hr>
  <p style="color:orange"><b style="color:blue;">NOTICE:</b><br>
    <span style="color:blue;">(1)</span> <i style="color:orange;">Upload the bulk image of those cooperatives logos as instructed with the uses of your <span style="color: blue;">Control Key.</span></i></p>
  <hr>
  
    <div align="right">
       <div class="box-footer">
       <button class="btn bg-blue ks-btn-file" type="submit" name="Import_logo_Passport"><span class="fa fa-cloud-upload"></span> Import Logos</button> 
       </div>
    </div>  

</div>  

<div class="scrollable">
<?php
if(isset($_POST["Import_logo_Passport"])){

    echo $filename=$_FILES["file"]["tmp_name"];
    foreach ($_FILES['uploaded_file']['name'] as $key => $name){
 
    $newFilename = $name;
    if(move_uploaded_file($_FILES['uploaded_file']['tmp_name'][$key], '../img/'.$newFilename))
    {
      echo "<p><span style='color: blue'><i>[".$newFilename."]</i></span> <span style='color: red;'>uploaded successfully...</span></p>";
    }
    else{
      echo "<script type=\"text/javascript\">
              alert(\"Error....Please try again later\");
            </script>";
    }
    }  
   
  }  
?> 
</div>   
           </form>
      </div>
    -->
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