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
             <li <?php echo ($_GET['tab'] == 'tab_1') ? "class='active'" : ''; ?>><a href="addcustomer.php?id=<?php echo $_SESSION['tid']; ?>&&mid=<?php echo base64_encode("403"); ?>&&tab=tab_1">New Customer Registration Form</a></li>
             <li <?php echo ($_GET['tab'] == 'tab_2') ? "class='active'" : ''; ?>><a href="addcustomer.php?id=<?php echo $_SESSION['tid']; ?>&&mid=<?php echo base64_encode("403"); ?>&&tab=tab_2">Import Customer's Information in Excel</a></li>
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
$fname =  mysqli_real_escape_string($link, $_POST['fname']);
$lname = mysqli_real_escape_string($link, $_POST['lname']);
$email = mysqli_real_escape_string($link, $_POST['email']);
$phone = mysqli_real_escape_string($link, $_POST['phone']);

$gender =  mysqli_real_escape_string($link, $_POST['gender']);
$dob =  mysqli_real_escape_string($link, $_POST['bdate']);

$occupation = mysqli_real_escape_string($link, $_POST['occupation']);
$addrs = mysqli_real_escape_string($link, $_POST['addrs']);
$city = mysqli_real_escape_string($link, $_POST['city']);
$state = mysqli_real_escape_string($link, $_POST['state']);
$zip = mysqli_real_escape_string($link, $_POST['zip']);
$country = mysqli_real_escape_string($link, $_POST['country']);
$nok = mysqli_real_escape_string($link, $_POST['nok']);
$nok_rela = mysqli_real_escape_string($link, $_POST['nok_rela']);
$nok_phone = mysqli_real_escape_string($link, $_POST['nok_phone']);

$account = mysqli_real_escape_string($link, $_POST['account']);
$status = "Completed";
$lofficer = mysqli_real_escape_string($link, $_POST['lofficer']);
//$branchid = mysqli_real_escape_string($link, $_POST['branch']);

$username =  mysqli_real_escape_string($link, $_POST['username']);
$password =  mysqli_real_escape_string($link, $_POST['password']);

$verify_customer = mysqli_query($link, "SELECT * FROM borrowers WHERE branchid = '$agentid'");
$fetch_cusno = mysqli_num_rows($verify_customer);

if($fetch_cusno == $acustomer_limit)
{
	echo "<script>alert('Sorry, You can not add more than the limit assigned to you. Kindly Contact us for higher plan!'); </script>";
}
else{

$search_user = mysqli_query($link, "SELECT * FROM user WHERE userid = '$lofficer'");
$get_user = mysqli_fetch_array($search_user);
$branchid = $get_user['branchid'];

$search_plan = mysqli_query($link, "SELECT * FROM compensation_plan ORDER BY id ASC") or die ("Error: " . mysqli_error($link));
	$fetch_plan = mysqli_fetch_object($search_plan);
	$level = $fetch_plan->plan_level;
	$ptarget = $fetch_plan->plan_target;

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
	
	//$today = date("Y-m-d");
	$insert = mysqli_query($link, "INSERT INTO borrowers VALUES(null,'$fname','$lname','$email','$phone','$gender','$dob','$occupation','$addrs','$city','$state','$zip','$country','$nok','$nok_rela','$nok_phone','Borrower','$account','$username','$password','0.0','0.0','$location',NOW(),'0000-00-00','$status','$lofficer','','$session_id','Not-Activated')") or die (mysqli_error($link));
	
	$protocol = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
	$url = $protocol . $_SERVER['HTTP_HOST']."/app/index.php?acn=".$account;
	$id = rand(1000000,10000000);
	$shorturl = base_convert($id,20,36);
	
	$insert = mysqli_query($link, "INSERT INTO activate_member VALUES(null,'$url','$shorturl','No','$account')") or die ("Error: " . mysqli_error($link));
	
	$shortenedurl = $protocol . $_SERVER['HTTP_HOST'] . '/app/index.php?activation_key=' . $shorturl;
	$shortenedurl1 = $protocol . $_SERVER['HTTP_HOST'] . '/app/index.php?deactivation_key=' . $shorturl;
	
	$query = mysqli_query($link, "SELECT abb, email FROM systemset") or die (mysqli_error($link));
	$r = mysqli_fetch_object($query);
	//$sys_abb = $r->abb;
	$sys_email = $r->email;
	
	$search_memset = mysqli_query($link, "SELECT * FROM member_settings WHERE companyid = '$session_id'");
    $fetch_memset = mysqli_fetch_array($search_memset);
    //$sys_abb = $get_sys['abb'];
    $sysabb = $fetch_memset['sender_id'];

	$search_gateway = mysqli_query($link, "SELECT * FROM sms WHERE smsuser = '$session_id' AND status = 'Activated'");
	$fetch_gateway = mysqli_fetch_object($search_gateway);
	$gateway_uname = $fetch_gateway->username;
	$gateway_pass = $fetch_gateway->password;
	$gateway_api = $fetch_gateway->api;

	$sms = "$sysabb>>>ACCT. Created | Welcome $fname! Your Account ID is: $account. Logon to your email to proceed. Thanks.";

	if($insert)
	{
		include('../cron/send_general_sms.php');
		include('../cron/send_regemail.php');
		echo "<div class='alert alert-success'>New Customer Added Successfully!...A welcome Email/SMS has been sent to customer.</div>";
	}
	else{
		echo'<span class="itext" style="color: #FF0000">Unable to Register...Please Try Again Later!!</span>';
	}
}
}
?>           
			 <form class="form-horizontal" method="post" enctype="multipart/form-data">
             <div class="box-body">
				
			<div class="form-group">
            <label for="" class="col-sm-2 control-label" style="color:blue;">Your Image</label>
			<div class="col-sm-10">
  		  			 <input type='file' name="image" onChange="readURL(this);">
       				 <img id="blah"  src="../avtar/user2.png" alt="Image Here" height="100" width="100"/>
			</div>
			</div>
			
			 <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Account Number</label>
                  <div class="col-sm-10">
<?php
$account = '10'.rand(10000000,99999999);
?>
                  <input name="account" type="text" class="form-control" value="<?php echo $account; ?>" placeholder="Account Number" readonly>
                  </div>
                  </div>
			
			<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">First Name</label>
                  <div class="col-sm-10">
                  <input name="fname" type="text" class="form-control" placeholder="First Name" required>
                  </div>
                  </div>
				  
		<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Last Name</label>
                  <div class="col-sm-10">
                  <input name="lname" type="text" class="form-control" placeholder="Last Name" required>
                  </div>
                  </div>
				  
		<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Gender</label>
                  <div class="col-sm-10">
				<select name="gender"  class="form-control select2" required>
										<option selected='selected'>Select Gender&hellip;</option>
										<option value="Male">Male</option>
										<option value="Female">Female</option>
				</select>
		</div>
		</div>
				  
		<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Email</label>
                  <div class="col-sm-10">
                  <input type="email" name="email" type="text" class="form-control" placeholder="Email">
                  </div>
                  </div>
				  
		<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Mobile</label>
                  <div class="col-sm-10">
                  <input name="phone" type="text" class="form-control" placeholder="Mobile Number" required>
				  <span style="color: orange;"> <b>Make sure you include country code but do not put spaces, or characters </b>in mobile otherwise the customer won't be able to receive SMS from this system </span><br>
                  </div>
                  </div>
				  
		<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Date of Birth</label>
                  <div class="col-sm-10">
                  <input name="bdate" type="date" class="form-control" required>
                  </div>
                  </div>
                  
        <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue">Occupation</label>
                  <div class="col-sm-10">
                  <input name="occupation" type="text" class="form-control" placeholder="Occupation" required>
                  </div>
                  </div>
				  
		 <div class="form-group">
                  	<label for="" class="col-sm-2 control-label" style="color:blue">Address</label>
                  	<div class="col-sm-10"><textarea name="addrs1"  class="form-control" rows="4" cols="80"></textarea></div>
          </div>
			
			<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue">City</label>
                  <div class="col-sm-10">
                  <input name="city" type="text" class="form-control" placeholder="City" required>
                  </div>
                  </div>
				  
		<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">State</label>
                  <div class="col-sm-10">
                  <input name="state" type="text" class="form-control" placeholder="State" required>
                  </div>
                  </div>
				  
				  <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Zip Code</label>
                  <div class="col-sm-10">
                  <input name="zip" type="text" class="form-control" placeholder="Zip Code" >
                  </div>
                  </div>
				  
		<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Country</label>
                  <div class="col-sm-10">
				<select name="country"  class="form-control" required>
										<option selected='selected'>Select Country</option>
										<option value="AX">&#197;land Islands</option>
										<option value="AF">Afghanistan</option>
										<option value="AL">Albania</option>
										<option value="DZ">Algeria</option>
										<option value="AD">Andorra</option>
										<option value="AO">Angola</option>
										<option value="AI">Anguilla</option>
										<option value="AQ">Antarctica</option>
										<option value="AG">Antigua and Barbuda</option>
										<option value="AR">Argentina</option>
										<option value="AM">Armenia</option>
										<option value="AW">Aruba</option>
										<option value="AU">Australia</option>
										<option value="AT">Austria</option>
										<option value="AZ">Azerbaijan</option>
										<option value="BS">Bahamas</option>
										<option value="BH">Bahrain</option>
										<option value="BD">Bangladesh</option>
										<option value="BB">Barbados</option>
										<option value="BY">Belarus</option>
										<option value="PW">Belau</option>
										<option value="BE">Belgium</option>
										<option value="BZ">Belize</option>
										<option value="BJ">Benin</option>
										<option value="BM">Bermuda</option>
										<option value="BT">Bhutan</option>
										<option value="BO">Bolivia</option>
										<option value="BQ">Bonaire, Saint Eustatius and Saba</option>
										<option value="BA">Bosnia and Herzegovina</option>
										<option value="BW">Botswana</option>
										<option value="BV">Bouvet Island</option>
										<option value="BR">Brazil</option>
										<option value="IO">British Indian Ocean Territory</option>
										<option value="VG">British Virgin Islands</option>
										<option value="BN">Brunei</option>
										<option value="BG">Bulgaria</option>
										<option value="BF">Burkina Faso</option>
										<option value="BI">Burundi</option>
										<option value="KH">Cambodia</option>
										<option value="CM">Cameroon</option>
										<option value="CA">Canada</option>
										<option value="CV">Cape Verde</option>
										<option value="KY">Cayman Islands</option>
										<option value="CF">Central African Republic</option>
										<option value="TD">Chad</option>
										<option value="CL">Chile</option>
										<option value="CN">China</option>
										<option value="CX">Christmas Island</option>
										<option value="CC">Cocos (Keeling) Islands</option>
										<option value="CO">Colombia</option>
										<option value="KM">Comoros</option>
										<option value="CG">Congo (Brazzaville)</option>
										<option value="CD">Congo (Kinshasa)</option>
										<option value="CK">Cook Islands</option>
										<option value="CR">Costa Rica</option>
										<option value="HR">Croatia</option>
										<option value="CU">Cuba</option>
										<option value="CW">Cura&Ccedil;ao</option>
										<option value="CY">Cyprus</option>
										<option value="CZ">Czech Republic</option>
										<option value="DK">Denmark</option>
										<option value="DJ">Djibouti</option>
										<option value="DM">Dominica</option>
										<option value="DO">Dominican Republic</option>
										<option value="EC">Ecuador</option>
										<option value="EG">Egypt</option>
										<option value="SV">El Salvador</option>
										<option value="GQ">Equatorial Guinea</option>
										<option value="ER">Eritrea</option>
										<option value="EE">Estonia</option>
										<option value="ET">Ethiopia</option>
										<option value="FK">Falkland Islands</option>
										<option value="FO">Faroe Islands</option>
										<option value="FJ">Fiji</option>
										<option value="FI">Finland</option>
										<option value="FR">France</option>
										<option value="GF">French Guiana</option>
										<option value="PF">French Polynesia</option>
										<option value="TF">French Southern Territories</option>
										<option value="GA">Gabon</option>
										<option value="GM">Gambia</option>
										<option value="GE">Georgia</option>
										<option value="DE">Germany</option>
										<option value="GH">Ghana</option>
										<option value="GI">Gibraltar</option>
										<option value="GR">Greece</option>
										<option value="GL">Greenland</option>
										<option value="GD">Grenada</option>
										<option value="GP">Guadeloupe</option>
										<option value="GT">Guatemala</option>
										<option value="GG">Guernsey</option>
										<option value="GN">Guinea</option>
										<option value="GW">Guinea-Bissau</option>
										<option value="GY">Guyana</option>
										<option value="HT">Haiti</option>
										<option value="HM">Heard Island and McDonald Islands</option>
										<option value="HN">Honduras</option>
										<option value="HK">Hong Kong</option>
										<option value="HU">Hungary</option>
										<option value="IS">Iceland</option>
										<option value="IN">India</option>
										<option value="ID">Indonesia</option>
										<option value="IR">Iran</option>
										<option value="IQ">Iraq</option>
										<option value="IM">Isle of Man</option>
										<option value="IL">Israel</option>
										<option value="IT">Italy</option>
										<option value="CI">Ivory Coast</option>
										<option value="JM">Jamaica</option>
										<option value="JP">Japan</option>
										<option value="JE">Jersey</option>
										<option value="JO">Jordan</option>
										<option value="KZ">Kazakhstan</option>
										<option value="KE">Kenya</option>
										<option value="KI">Kiribati</option>
										<option value="KW">Kuwait</option>
										<option value="KG">Kyrgyzstan</option>
										<option value="LA">Laos</option>
										<option value="LV">Latvia</option>
										<option value="LB">Lebanon</option>
										<option value="LS">Lesotho</option>
										<option value="LR">Liberia</option>
										<option value="LY">Libya</option>
										<option value="LI">Liechtenstein</option>
										<option value="LT">Lithuania</option>
										<option value="LU">Luxembourg</option>
										<option value="MO">Macao S.A.R., China</option>
										<option value="MK">Macedonia</option>
										<option value="MG">Madagascar</option>
										<option value="MW">Malawi</option>
										<option value="MY">Malaysia</option>
										<option value="MV">Maldives</option>
										<option value="ML">Mali</option>
										<option value="MT">Malta</option>
										<option value="MH">Marshall Islands</option>
										<option value="MQ">Martinique</option>
										<option value="MR">Mauritania</option>
										<option value="MU">Mauritius</option>
										<option value="YT">Mayotte</option>
										<option value="MX">Mexico</option>
										<option value="FM">Micronesia</option>
										<option value="MD">Moldova</option>
										<option value="MC">Monaco</option>
										<option value="MN">Mongolia</option>
										<option value="ME">Montenegro</option>
										<option value="MS">Montserrat</option>
										<option value="MA">Morocco</option>
										<option value="MZ">Mozambique</option>
										<option value="MM">Myanmar</option>
										<option value="NA">Namibia</option>
										<option value="NR">Nauru</option>
										<option value="NP">Nepal</option>
										<option value="NL">Netherlands</option>
										<option value="AN">Netherlands Antilles</option>
										<option value="NC">New Caledonia</option>
										<option value="NZ">New Zealand</option>
										<option value="NI">Nicaragua</option>
										<option value="NE">Niger</option>
										<option value="Nigeria">Nigeria</option>
										<option value="NU">Niue</option>
										<option value="NF">Norfolk Island</option>
										<option value="KP">North Korea</option>
										<option value="NO">Norway</option>
										<option value="OM">Oman</option>
										<option value="PK">Pakistan</option>
										<option value="PS">Palestinian Territory</option>
										<option value="PA">Panama</option>
										<option value="PG">Papua New Guinea</option>
										<option value="PY">Paraguay</option>
										<option value="PE">Peru</option>
										<option value="PH">Philippines</option>
										<option value="PN">Pitcairn</option>
										<option value="PL">Poland</option>
										<option value="PT">Portugal</option>
										<option value="QA">Qatar</option>
										<option value="IE">Republic of Ireland</option>
										<option value="RE">Reunion</option>
										<option value="RO">Romania</option>
										<option value="RU">Russia</option>
										<option value="RW">Rwanda</option>
										<option value="ST">S&atilde;o Tom&eacute; and Pr&iacute;ncipe</option>
										<option value="BL">Saint Barth&eacute;lemy</option>
										<option value="SH">Saint Helena</option>
										<option value="KN">Saint Kitts and Nevis</option>
										<option value="LC">Saint Lucia</option>
										<option value="SX">Saint Martin (Dutch part)</option>
										<option value="MF">Saint Martin (French part)</option>
										<option value="PM">Saint Pierre and Miquelon</option>
										<option value="VC">Saint Vincent and the Grenadines</option>
										<option value="SM">San Marino</option>
										<option value="SA">Saudi Arabia</option>
										<option value="SN">Senegal</option>
										<option value="RS">Serbia</option>
										<option value="SC">Seychelles</option>
										<option value="SL">Sierra Leone</option>
										<option value="SG">Singapore</option>
										<option value="SK">Slovakia</option>
										<option value="SI">Slovenia</option>
										<option value="SB">Solomon Islands</option>
										<option value="SO">Somalia</option>
										<option value="ZA">South Africa</option>
										<option value="GS">South Georgia/Sandwich Islands</option>
										<option value="KR">South Korea</option>
										<option value="SS">South Sudan</option>
										<option value="ES">Spain</option>
										<option value="LK">Sri Lanka</option>
										<option value="SD">Sudan</option>
										<option value="SR">Suriname</option>
										<option value="SJ">Svalbard and Jan Mayen</option>
										<option value="SZ">Swaziland</option>
										<option value="SE">Sweden</option>
										<option value="CH">Switzerland</option>
										<option value="SY">Syria</option>
										<option value="TW">Taiwan</option>
										<option value="TJ">Tajikistan</option>
										<option value="TZ">Tanzania</option>
										<option value="TH">Thailand</option>
										<option value="TL">Timor-Leste</option>
										<option value="TG">Togo</option>
										<option value="TK">Tokelau</option>
										<option value="TO">Tonga</option>
										<option value="TT">Trinidad and Tobago</option>
										<option value="TN">Tunisia</option>
										<option value="TR">Turkey</option>
										<option value="TM">Turkmenistan</option>
										<option value="TC">Turks and Caicos Islands</option>
										<option value="TV">Tuvalu</option>
										<option value="UG">Uganda</option>
										<option value="UA">Ukraine</option>
										<option value="AE">United Arab Emirates</option>
										<option value="GB">United Kingdom (UK)</option>
										<option value="US">United States (US)</option>
										<option value="UY">Uruguay</option>
										<option value="UZ">Uzbekistan</option>
										<option value="VU">Vanuatu</option>
										<option value="VA">Vatican</option>
										<option value="VE">Venezuela</option>
										<option value="VN">Vietnam</option>
										<option value="WF">Wallis and Futuna</option>
										<option value="EH">Western Sahara</option>
										<option value="WS">Western Samoa</option>
										<option value="YE">Yemen</option>
										<option value="ZM">Zambia</option>
										<option value="ZW">Zimbabwe</option>
									</select>                 
									 </div>
                 					 </div>
			
			<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Loan Officer Access (optional)</label>
                  <div class="col-sm-10">
				<select name="lofficer"  class="form-control select2">
										<option selected='selected'>Asign Staff to Loan&hellip;</option>
										<?php
$search = mysqli_query($link, "SELECT * FROM user WHERE branchid = '$session_id'");
while($get_search = mysqli_fetch_array($search))
{
?>
					<option value="<?php echo $get_search['userid']; ?>"><?php echo $get_search['name']; ?></option>
<?php } ?>
				</select>
				<span style="color: orange;"> You can assign borrower to the above loan officers. This will allow you to download the collection sheet for each staff and the staff will know which borrower to chase for payment. </span><br>
		</div>
		</div>

		<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Username</label>
                  <div class="col-sm-10">
                  <input name="username" type="text" class="form-control" placeholder="Username" required>
                  </div>
                  </div>
				  
		<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Password</label>
                  <div class="col-sm-10">
                  <input name="password" type="password" class="form-control" placeholder="Password" required>
                  </div>
                  </div>
                  
<hr>
<div class="bg-orange">NEXT OF KIN DETAILS</div>
<hr>
                  
        <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue">Next of Kin</label>
                  <div class="col-sm-10">
                  <input name="nok" type="text" class="form-control" placeholder="Next of Kin" required>
                  </div>
                  </div>   
        
        <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue">Relationship</label>
                  <div class="col-sm-10">
                  <input name="nok_rela" type="text" class="form-control" placeholder="Next of Kin" required>
                  </div>
                  </div> 
                 
        <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue">Phone</label>
                  <div class="col-sm-10">
                  <input name="nok_phone" type="text" class="form-control" placeholder="Next of Kin Phone Number" required>
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
				  
				   <form class="form-horizontal" method="post" enctype="multipart/form-data">
					   
<div class="box-body">
		
	<div class="form-group">
           <label for="" class="col-sm-4 control-label" style="color: blue;">Import Customer's Here:</label>
	<div class="col-sm-8">
        <span class="fa fa-cloud-upload"></span>
           <span class="ks-text">Choose file</span>
           <input type="file" name="file" accept=".csv" required>
	</div>
	</div>
                        
	<hr>
  <p style="color:orange"><b style="color:blue;">NOTE:</b><br>
    <span style="color:blue;">(1)</span> <i>Kindly download the <a href="../sample/borrowers_sample.csv"> <b class="btn bg-blue"><i class="fa fa-download"></i> sample file</b></a> to upload the details once.</i></p>
    <span style="color:blue;">(2)</span> <i style="color:orange;">Finally, take note of the <span style="color: blue">image</span> which is to be written in this format: <b style="color:blue;">img/image_name.image_format</b>. The image format can be <b style="color: blue;">.png, .jpg, etc.</b></i></p>
                        
  <hr>
	
    <div align="right">
       <div class="box-footer">
	     <button class="btn bg-blue ks-btn-file" type="submit" name="Import"><span class="fa fa-cloud-upload"></span> Import Customer Details</button> 
       </div>
    </div>  

</div>	

<?php
if(isset($_POST["Import"])){

		echo $filename=$_FILES["file"]["tmp_name"];

		$search_compensation_plan = mysqli_query($link, "SELECT * FROM compensation_plan");
		$fetch_compensation_plan = mysqli_fetch_object($search_compensation_plan);
		$plan_level = $fetch_compensation_plan->plan_level;
		$plan_target = $fetch_compensation_plan->plan_target;
		
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
 
	          //It wiil insert a row to our borrowers table from our csv file`
	           $sql = "INSERT INTO borrowers(id,fname,lname,email,phone,gender,dob,community_role,account,username,password,balance,image,date_time,last_withdraw_date,status,lofficer,c_sign,branchid,acct_status,referral,plan_level,plan_target,referral_bonus,referral_count) VALUES(null,'$emapData[0]','$emapData[1]','$emapData[2]','$emapData[3]','$emapData[4]','$emapData[5]','','','','','','','','','','Borrower','$emapData[6]','$emapData[7]','$emapData[8]','0.0','0.0','$emapData[9]',NOW(),'0000-00-00','Completed','$emapData[10]','','$session_id','Activated','','$plan_level','$plan_target','0.0','0')";
	         //we are using mysql_query function. it returns a resource on true else False on error
	          $result = mysqli_query($link,$sql);
				if(!$result)
				{
					echo "<script type=\"text/javascript\">
							alert(\"Invalid File:Please Upload CSV File.\");
						</script>";
				}
 
	         }
	         fclose($file);
	         //throws a message if data successfully imported to mysql database from excel file
	         echo "<script type=\"text/javascript\">
						alert(\"Customers Information has been successfully Imported.\");
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
    <span style="color:blue;">(1)</span> <i style="color:orange;">Upload the bulk image of customer's as instructed with the uses of your <span style="color: blue;">Control Key.</span></i></p>
  <hr>
  
    <div align="right">
       <div class="box-footer">
       <button class="btn bg-blue ks-btn-file" type="submit" name="Import_logo"><span class="fa fa-cloud-upload"></span> Import Picture</button> 
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