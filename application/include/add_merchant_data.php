<div class="row">	
		
	 <section class="content">
		 
            <h3 class="panel-title"> <a href="customer.php?id=<?php echo $_SESSION['tid']; ?>&&mid=<?php echo base64_encode("403"); ?>"><button type="button" class="btn btn-flat bg-orange"><i class="fa fa-mail-reply-all"></i>&nbsp;Back</button> </a></h3>
        
		   <div class="box box-success">
           <div class="box-body">
             <div class="table-responsive"> 
            <div class="box-body">

		 <div class="col-md-14">
            <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
             <li <?php echo ($_GET['tab'] == 'tab_1') ? "class='active'" : ''; ?>><a href="add_merchant.php?id=<?php echo $_SESSION['tid']; ?>&&mid=<?php echo base64_encode("490"); ?>&&tab=tab_1">Add Single Merchant</a></li>
             <li <?php echo ($_GET['tab'] == 'tab_2') ? "class='active'" : ''; ?>><a href="add_merchant.php?id=<?php echo $_SESSION['tid']; ?>&&mid=<?php echo base64_encode("490"); ?>&&tab=tab_2">Register Bulk Merchant</a></li>
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
			 
<?php
if(isset($_POST['save']))
{
	//include("../config/restful_apicalls.php");
    $refid = "EA-walletPrefund-".rand(100000000,999999999);
	//$subaccount_charges = $r->subaccount_charges;

	//Merchant Records
	$merchantID =  mysqli_real_escape_string($link, $_POST['merchantID']);
	$companyname = mysqli_real_escape_string($link, $_POST['companyname']);
	$license_no = mysqli_real_escape_string($link, $_POST['license_no']);
	$company_sector = mysqli_real_escape_string($link, $_POST['company_sector']);
	$state =  mysqli_real_escape_string($link, $_POST['state']);
	$country =  mysqli_real_escape_string($link, $_POST['country']);
	$official_email = mysqli_real_escape_string($link, $_POST['official_email']);
	$official_phone = mysqli_real_escape_string($link, $_POST['official_phone']);
	$id = "MEM".rand(100000,999999);
	$my_senderid = mysqli_real_escape_string($link, $_POST['senderid']);
	$currency_type = mysqli_real_escape_string($link, $_POST['currency']);

	//Contact Person
	$contact_person = mysqli_real_escape_string($link, $_POST['contact_person']);
	$phone = mysqli_real_escape_string($link, $_POST['phone']);
	$cemail = mysqli_real_escape_string($link, $_POST['cemail']);
	$uname = mysqli_real_escape_string($link, $_POST['uname']);
	$password = rand(10000000,9999999);
	$encrypt = base64_encode($password);

	$verify_email = mysqli_query($link, "SELECT * FROM merchant_reg WHERE official_email = '$official_email'");
	$detect_email = mysqli_num_rows($verify_email);

	$verify_phone = mysqli_query($link, "SELECT * FROM merchant_reg WHERE official_phone = '$official_phone'");
	$detect_phone = mysqli_num_rows($verify_phone);

	$verify_username = mysqli_query($link, "SELECT * FROM merchant_reg WHERE username = '$uname'");
	$detect_username = mysqli_num_rows($verify_username);
	
	$verify_sid = mysqli_query($link, "SELECT * FROM member_settings WHERE sender_id = '$my_senderid'");
	$detect_sid= mysqli_num_rows($verify_sid);

	if($detect_email == 1){
		echo'<span class="itext" style="color: #FF0000">Sorry, Email Address has already been used.</span>';
	}
	elseif($detect_phone == 1){
		echo'<span class="itext" style="color: #FF0000">Sorry, Phone Number has already been used.</span>';
	}
	elseif($detect_username == 1){
		echo'<span class="itext" style="color: #FF0000">Sorry, Username has already been used.</span>';
	}
	elseif($detect_sid == 1){
		echo'<span class="itext" style="color: #FF0000">Sorry, Sender ID has already been used.</span>';
	}
	else{

		$target_dir = "../img/";
		$target_file = $target_dir.basename($_FILES["image"]["name"]);
		//$target_file_c_sign = $target_dir.basename($_FILES["c_sign"]["name"]);
		$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
		//$imageFileType_c_sign = pathinfo($target_file_c_sign,PATHINFO_EXTENSION);
		$check = getimagesize($_FILES["image"]["tmp_name"]);
		//$check_c_sign = getimagesize($_FILES["c_sign"]["tmp_name"]);
			
		$sourcepath = $_FILES["image"]["tmp_name"];
		//$sourcepath_c_sign = $_FILES["c_sign"]["tmp_name"];
		$targetpath = "../img/" . $_FILES["image"]["name"];
		//$targetpath_c_sign = "../img/" . $_FILES["c_sign"]["name"];
		move_uploaded_file($sourcepath,$targetpath);
		//move_uploaded_file($sourcepath_c_sign,$targetpath_c_sign);

		$location = "img/".$_FILES['image']['name'];
		//$loaction_c_sign = "img/".$_FILES['c_sign']['name'];
		
		echo $filename=$_FILES["file"]["tmp_name"];
		foreach ($_FILES['documents']['name'] as $key => $name){
			
			$newFilename = $name;
			$newlocation = '../img/'.$newFilename;
			if(move_uploaded_file($_FILES['documents']['tmp_name'][$key], '../img/'.$newFilename))
			{
				mysqli_query($link, "INSERT INTO merchant_legaldoc VALUES(null,'$merchantID','$newlocation')");
			}
			
		}
	
		$insert_records = mysqli_query($link, "INSERT INTO merchant_legaldoc VALUES(null,'$merchantID','$newlocation')");
    	$insert_records = mysqli_query($link, "INSERT INTO merchant_reg VALUES(null,'$merchantID','NIL','$location','$companyname','$license_no','$company_sector','$state','$country','$official_email','$official_phone','NIL','NIL','NIL','NIL','','$contact_person','$phone','$cemail','$uname','$password','Admin','Active','0.0','0.0','NULL',NOW(),'0000')") or die ("Error: " . mysqli_error($link));
        $insert_records = mysqli_query($link, "INSERT INTO user VALUES(null,'$contact_person','$cemail','$phone','','','','','','','Pending','$uname','$encrypt','$id','','merchant_super_admin','','Registered','$merchantID','MER','0000')") or die ("Error: " . mysqli_error($link));
		$insert_records = mysqli_query($link, "INSERT INTO member_settings VALUE(null,'$merchantID','$companyname','img/','','$my_senderid','$currency_type','','','','','100000')") or die ("Error: " . mysqli_error($link));
		$insert_records = mysqli_query($link, "INSERT INTO maintenance_history VALUE(null,'$merchantID','2.5','100','flat','10','2000','Activated','Hybrid')") or die ("Error: " . mysqli_error($link));
		
		$query = mysqli_query($link, "SELECT * FROM systemset") or die (mysqli_error($link));
		$r = mysqli_fetch_object($query);
		$sys_abb = $r->abb;
		$sys_email = $r->email;
		$wprefund_bal = $r->wallet_prefound_amt;
		
		$insert_records = mysqli_query($link, "INSERT INTO wallet_history VALUE(null,'$merchantID','$refid','self','$wprefund_bal','NGN','system','Remarks: New User Wallet Bonus','successful',NOW())") or die ("Error: " . mysqli_error($link));
	
		$search_gateway = mysqli_query($link, "SELECT * FROM sms WHERE smsuser = '' AND status = 'Activated'");
		$fetch_gateway = mysqli_fetch_object($search_gateway);
		$gateway_uname = $fetch_gateway->username;
		$gateway_pass = $fetch_gateway->password;
		$gateway_api = $fetch_gateway->api;
	
		$sms = "$r->abb>>>Welcome $companyname! Your Merchant ID is: $merchantID, Transaction Pin is: 0000. Logon to your email for your login details. Your dedicated URL is: https://esusu.app/$my_senderid .";

		if($insert_records)
		{
			include('../cron/send_inst_sms.php');
			include('../cron/send_merchant_regemail.php');
			echo "<div class='alert alert-success'>Accunt Created Successfully!...An email / sms notification has been sent to the merchant</div>";
		}
		else{
			echo'<span class="itext" style="color: #FF0000">Unable to Register...Please Try Again Later!!</span>';
		}
	}
}
?>           
			 <form class="form-horizontal" method="post" enctype="multipart/form-data">
             <div class="box-body">

<?php
$merchantID = 'MER-'.rand(10000000,99999999);
?>
      		<input name="merchantID" type="hidden" class="form-control" value="<?php echo $merchantID; ?>">

			<div class="form-group">
 		                  <label for="" class="col-sm-2 control-label" style="color:blue;">Logo</label>
 		                  <div class="col-sm-10">
 						<input type='file' name="image" onChange="readURL(this);">
		               <img id="blah"  src="../avtar/user2.png" alt="Institution Logo Here" height="100" width="100"/>
 				</div>
 				</div>
                 
                <div class="form-group">
 		              <label for="" class="col-sm-2 control-label" style="color:blue;">Legal Document</label>
				      <div class="col-sm-10">
				               <input type='file' name="documents[]" multiple required/>
				               <span style="color: orange;">Here, you are required to upload all the valid document provided by the Merchant. AND Also, You can upload more than one documents at a time.</span>
				      </div>
 				</div>
      			
			<div class="form-group">
            	<label for="" class="col-sm-2 control-label" style="color:blue;">Company Name</label>
                  <div class="col-sm-10">
                  <input name="companyname" type="text" class="form-control" placeholder="Company Name" required>
				  </div>
			</div>
			
			 <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">License No.</label>
                  <div class="col-sm-10">
                  <input name="license_no" type="text" class="form-control" placeholder="License Number" required>
                  </div>
                  </div>
			
			<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Sector</label>
                  <div class="col-sm-10">
        		<select name="company_sector"  class="form-control select2" id="company_sector" required>
                    <option value="" selected='selected'>Select Company Sector&hellip;</option>
                    <option value="Insurance">Insurance</option>
                    <option value="Microfinance">Microfinance</option>
                    <option value="Asset Management Firm">Asset Management Firm</option>
                    <option value="Financial Service Provider">Financial Service Provider</option>
                    <option value="Pension">Pension</option>
                    <option value="Cooperative">Cooperative</option>
                    <option value="Other">Other</option>
        		</select>
                  </div>
		   </div>
		   
		   <span id='ShowValueFrank'></span>
				  
		<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">State</label>
                  <div class="col-sm-10">
                  <input name="state" type="text" id="autocomplete2" onFocus="geolocate()" class="form-control" placeholder="State" required>
                  </div>
                  </div>
				  
		<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Country</label>
                      <div class="col-sm-10">
            <select name="country"  class="form-control select2" required>
              <option value="" selected="selected">Please Select Country</option>
              <option value="NG">Nigeria</option>
              <option value="GH">Ghana</option>
              <option value="KE">Kenya</option>
              <option value="UG">Uganda </option>
              <option value="TZ">Tanzania</option>
            </select>
		</div>
		</div>
				  
		<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Official Email</label>
                  <div class="col-sm-10">
					  <input name="official_email" type="email" id="vemail" onkeyup="veryEmail();" class="form-control" placeholder="Official Email Address" required>
                  	  <div id="myvemail"></div>
                  </div>
                  </div>
				  
		<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Official Phone</label>
                  <div class="col-sm-10">
					  <input name="official_phone" type="text" id="vphone" onkeyup="veryPhone();" class="form-control" placeholder="Official Phone Number" required>
					  <span style="color: orange;"> <b>Make sure you include country code but do not put spaces, or characters </b>in mobile otherwise the customer won't be able to receive SMS from this system </span><br>
	                  <div id="myvphone"></div>
                  </div>
                  </div>
				  
		<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Sender ID</label>
                  <div class="col-sm-10">
                  <input name="senderid" type="text" id="vsid" onkeyup="verySID();" class="form-control" placeholder="SMS Notification ID" required>
                  <div id="myvsid"></div>
				  </div>
                  </div>
				  
		<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Currency</label>
                  <div class="col-sm-10">
        		<select name="currency"  class="form-control select2" required>
                    <option value="" selected='selected'>Select Currency&hellip;</option>
                    <option value="NGN">NGN</option>
                    <option value="USD">USD</option>
                    <option value="GHS">GHS</option>
                    <option value="KES">KES</option>
        		</select>
                  </div>
		   </div>
				  
		<hr>
<div class="bg-orange">&nbsp;<b> CONTACT PERSON </b></div>
<hr>

    <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Contact Person</label>
                  <div class="col-sm-10">
                  <input name="contact_person" type="text" class="form-control" placeholder="Contact Person Here" required>
                  </div>
                  </div>

    <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Phone Number</label>
                  <div class="col-sm-10">
                  <input name="phone" type="text" class="form-control" id="vphone" onkeyup="veryPhone();" placeholder="Phone Number" required>
                  <div id="myvphone"></div>
				  </div>
                  </div>

    <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Email Address</label>
                  <div class="col-sm-10">
                  <input name="cemail" type="email" class="form-control" id="vemail" onkeyup="veryEmail();" placeholder="Contact Email Address" required>
                  <div id="myvemail"></div>
				  </div>
                  </div>

    <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Username</label>
                  <div class="col-sm-10">
                  <input name="uname" type="text" class="form-control" id="vusername" onkeyup="veryUsername();" placeholder="Username" required>
                  <div id="myusername"></div>
				  </div>
                  </div>


			 </div>
			 
			  <div align="right">
              <div class="box-footer">
                				<button type="reset" class="btn bg-orange"><i class="fa fa-times">&nbsp;Reset</i></button>
                				<button name="save" type="submit" class="btn bg-blue"><i class="fa fa-plus">&nbsp;Submit</i></button>

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
    <span style="color:blue;">(1)</span> <i>Kindly download the <a href="../sample/merchant_reg_sample.csv"> <b class="btn bg-blue"><i class="fa fa-download"></i> sample file</b></a> to upload the details once.</i></p>
    <p><span style="color:blue;">(2)</span> <i style="color:orange;">Please note that the <span style="color: blue">settlement scheduling</span> must be in this format: <span style="color: blue"> auto, weekly, monthly OR manual</span></i></p>
    <p><span style="color:blue;">(3)</span> <i style="color:orange;">Finally, take note of the <span style="color: blue">logo</span> which are to be written in this format: <b style="color:blue;">img/image_name.image_format</b>. The image format can be <b style="color: blue;">.png, .jpg, etc.</b></i></p>
                        
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

    $query = mysqli_query($link, "SELECT * FROM systemset") or die (mysqli_error($link));   
    $r = mysqli_fetch_object($query);
    $sys_abb = $r->abb;
    $sys_email = $r->email;

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

          // Pass the subaccount name, settlement bank account, account number and percentage charges
          $postdata =  array("business_name" => $emapData[1], "settlement_bank" => $emapData[10], "account_number" => $emapData[8], "percentage_charge" => $emapData[11], "settlement_schedule" => $emapData[12]);

          $url = "https://api.paystack.co/subaccount";
          
          $ch = curl_init();
          curl_setopt($ch, CURLOPT_URL,$url);
          curl_setopt($ch, CURLOPT_POST, 1);
          curl_setopt($ch, CURLOPT_POSTFIELDS,json_encode($postdata));  //Post Fields
          curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

          $headers = [
            'Authorization: Bearer '.$r->secret_key,
            'Content-Type: application/json',
          ];

          curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

          $request = curl_exec($ch);
          if(curl_error($ch)){
           echo 'error:' . curl_error($ch);
           }
          curl_close($ch);
          
          if ($request) {
            $result = json_decode($request, true);

          if($result){

            if($result['status'] == true){

              $subaccount_code = $result['data']['subaccount_code'];
              
              $merchantID = "MER-".mt_rand(10200066,99999999);
              $upassword = random_password(10);

              $sql2 = "INSERT INTO merchant_reg(id,merchantID,subaccount_code,mlogo,company_name,mlicense_no,msector,state,country,official_email,official_phone,account_number,bank_code,bank_name,dividend,settlement_schedule,contact_person,contact_phone,contact_email,username,password,status,referral_bonus,wallet_balance,card_id,date_time,tpin) VALUES(null,'$merchantID','$subaccount_code','$emapData[0]','$emapData[1]','$emapData[2]','$emapData[3]','$emapData[4]','$emapData[5]','$emapData[6]','$emapData[7]','$emapData[8]','$emapData[9]','$emapData[10]','$emapData[11]','$emapData[12]','$emapData[13]','$emapData[14]','$emapData[15]','$emapData[16]','$upassword','Admin','Active','0.0','0.0','NULL',NOW(),'0000')";
              //we are using mysql_query function. it returns a resource on true else False on error
              $outcome = mysqli_query($link,$sql2);

              $sendemail = mysqli_query($link, "INSERT INTO email_log VALUES(null,'$sys_abb','$sys_email','$emapData[1]','$emapData[6]','merchant','reg','Pending',NOW())");

              include('../cron/send_bulkmerchant_regemail.php');

              if(!($sendemail && $outcome && $sendmessage))
              {

                echo "<script type=\"text/javascript\">
                      alert(\"Invalid File:Please Upload CSV File.\");
                      </script>";
              }

            }
            else{

              $message = $result['message'];
              echo "<script>alert('$message \\nPlease try again later'); </script>";

            }

          }

        }

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
<div class="bg-blue">&nbsp;<b> UPLOAD LEGAL FILES IN CSV FORMAT </b></div>
<hr>
           <form class="form-horizontal" method="post" enctype="multipart/form-data">
             
<div class="box-body">
    
  <div class="form-group">
           <label for="" class="col-sm-4 control-label" style="color: blue;">Import Bulk Files Here:</label>
  <div class="col-sm-8">
        <span class="fa fa-cloud-upload"></span>
           <span class="ks-text">Choose file</span>
           <input type="file" name="uploaded_file2[]" multiple required>
  </div>
  </div>

   <hr>
  <p style="color:orange"><b style="color:blue;">NOTICE:</b><br>
     <span style="color:blue;">(1)</span> <i>Kindly download the <a href="../sample/merchant_files_sample.csv"> <b class="btn bg-blue"><i class="fa fa-download"></i> sample file</b></a> to upload the details once.</i></p>
    <p><span style="color:blue;">(2)</span> <i style="color:orange;">Upload the bulk file of those merchant documents in csv format only</i></p>
    <p><span style="color:blue;">(3)</span> <i style="color:orange;">Finally, take note of the <span style="color: blue">document_path</span> which are to be written in this format: <b style="color:blue;">img/file_name.file_format</b>. The file format can be <b style="color: blue;">.png, .jpg, etc.</b></i></p>
  <hr>
  
    <div align="right">
       <div class="box-footer">
       <button class="btn bg-blue ks-btn-file" type="submit" name="Import_logo"><span class="fa fa-cloud-upload"></span> Import Logos</button> 
       </div>
    </div>  

</div>  

<div class="scrollable">
<?php
if(isset($_POST["Import_logo"])){

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

          $sql2 = "INSERT INTO merchant_legaldoc(id,merchantid,document) VALUES(null,'$emapData[0]','$emapData[1]')";
          //we are using mysql_query function. it returns a resource on true else False on error
          $outcome = mysqli_query($link,$sql2);
        }
        if(isset($outcome))
        {

          echo "<script type=\"text/javascript\">
              alert(\"Document Added Successfully\");
            </script>";

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
<hr>
<div class="bg-blue">&nbsp;<b> LOGO / LEGAL FILE SECTION </b></div>
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
    <span style="color:blue;">(1)</span> <i style="color:orange;">Upload the bulk image of those merchant logos as instructed with the uses of your <span style="color: blue;">Control Key.</span></i></p>
  <hr>
  
    <div align="right">
       <div class="box-footer">
       <button class="btn bg-blue ks-btn-file" type="submit" name="Import_logo"><span class="fa fa-cloud-upload"></span> Import Logos</button> 
       </div>
    </div>  

</div>  

<div class="scrollable">
<?php
if(isset($_POST["Import_logo"])){

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