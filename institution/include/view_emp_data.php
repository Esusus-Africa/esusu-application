<div class="box">
        
		 <div class="box-body">
			<div class="panel panel-success">
            <div class="panel-heading bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">
            <h3 class="panel-title"><i class="fa fa-user"></i> View Sub-Agent for Update/Approval</h3>
            </div>
             <div class="box-body">

		 
               
			 <form class="form-horizontal" method="post" enctype="multipart/form-data">
			     
<?php
if(isset($_POST['emp']))
{
    $myeuserid = $_GET['id'];
    $name =  mysqli_real_escape_string($link, $_POST['name']);
    $lname =  mysqli_real_escape_string($link, $_POST['lname']);
    $mname =  mysqli_real_escape_string($link, $_POST['mname']);
    $email =  mysqli_real_escape_string($link, $_POST['email']);
    $phone =  mysqli_real_escape_string($link, $_POST['phone']);
	$dob = mysqli_real_escape_string($link, $_POST['dob']);
    $addr1 =  mysqli_real_escape_string($link, $_POST['addr1']);
    $city =  mysqli_real_escape_string($link, $_POST['city']);
    $state =  mysqli_real_escape_string($link, $_POST['state']);
    $zip =  mysqli_real_escape_string($link, $_POST['zip']);
    $country =  mysqli_real_escape_string($link, $_POST['country']);
    $username =  mysqli_real_escape_string($link, $_POST['username']);
    $edit_role =  mysqli_real_escape_string($link, $_POST['urole']);
    $cust_reg =  mysqli_real_escape_string($link, $_POST['cust_reg']);
    $deposit =  mysqli_real_escape_string($link, $_POST['deposit']);
    $withdrawal =  mysqli_real_escape_string($link, $_POST['withdrawal']);
    $status = mysqli_real_escape_string($link, $_POST['status']);
    $dept = mysqli_real_escape_string($link, $_POST['dept']);
    $allow_auth = mysqli_real_escape_string($link, $_POST['allow_auth']);
    $password =  mysqli_real_escape_string($link, $_POST['password']);
    //$encrypt = base64_encode($password);
    
    //POS WAllet
    $posid = mysqli_real_escape_string($link, $_POST['wtid']);
    $walletId = mysqli_real_escape_string($link, $_POST['walletId']);
    $wallet_uname = mysqli_real_escape_string($link, $_POST['wallet_uname']);
    $wallet_pass = mysqli_real_escape_string($link, $_POST['wallet_pass']);
    
    $searchWallet = mysqli_query($link, "SELECT * FROM pos_wallet WHERE tid = '$posid'") or die ("Error: " . mysqli_error($link));
    $fetchWalletNum = mysqli_num_rows($searchWallet);
    
    $image = addslashes(file_get_contents($_FILES['image']['tmp_name']));
    
    if($image == "")
    {		
    	$branch =  mysqli_real_escape_string($link, $_POST['branch']);
    	
    	mysqli_query($link,"UPDATE user SET name='$name',lname='$lname',mname='$mname',email='$email',phone='$phone',dob='$dob',addr1='$addr1',city='$city',state='$state',zip='$zip',country='$country',username='$username',branchid='$branch',role='$edit_role',cust_reg='$cust_reg',deposit='$deposit',withdrawal='$withdrawal',comment='$status',dept='$dept',allow_auth='$allow_auth' WHERE id ='$myeuserid'") or die("Error: " . mysqli_error($link)); 
    	($pos_wallet_settings != '1' || $walletId == "" ? "" : (($fetchWalletNum == 1 ) ? mysqli_query($link,"UPDATE pos_wallet SET walletid = '$walletId', username = '$wallet_uname', password = '$wallet_pass' WHERE tid = '$posid'") or die ("Error: " . mysqli_error($link)) : mysqli_query($link,"INSERT INTO pos_wallet VALUES(null,'$institution_id','$myeuserid','$wallet_uname','$wallet_pass','$walletId',NOW())") or die ("Error: " . mysqli_error($link))));
    	
    	echo "<script>alert('Update made successfully!!'); </script>";
    	echo "<script>window.location='view_emp.php?id=".$myeuserid."&&mid=".base64_encode("419")."'; </script>";
    	
    }
    else{
    	$target_dir = "../img/";
    	$target_file = $target_dir.basename($_FILES["image"]["name"]);
    	$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
    	$check = getimagesize($_FILES["image"]["tmp_name"]);
    	
    	if($check == false)
    	{
    	    
    	    echo "<div class='alert alert-orange'>Invalid file type</div>";

    	}
    	elseif($_FILES["image"]["size"] > 500000)
    	{
    	    
    	    echo "<div class='alert alert-orange'>Image must not more than 500KB!</div>";
    		
    	}
    	elseif($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif")
    	{
    	    
    	    echo "<div class='alert alert-orange'>Sorry, only JPG, JPEG, PNG & GIF Files are allowed.</div>";
    		
    	}
    	else{
    		$sourcepath = $_FILES["image"]["tmp_name"];
    		$targetpath = "../img/" . $_FILES["image"]["name"];
    		move_uploaded_file($sourcepath,$targetpath);
    
    		$location = $_FILES['image']['name'];
    		$branch =  mysqli_real_escape_string($link, $_POST['branch']);
    	
    		//$update1 = mysqli_query($link,"UPDATE twallet SET branchid = '$branchid' WHERE tid = '$myeuserid'") or die (mysqli_error($link));
    		$update = mysqli_query($link,"UPDATE user SET name='$name',lname='$lname',mname='$mname',email='$email',phone='$phone',dob='$dob',addr1='$addr1',city='$city',state='$state',zip='$zip',country='$country',comment='$comment',username='$username', image='$location', branchid='$branch', role = '$edit_role', cust_reg='$cust_reg', deposit='$deposit', withdrawal='$withdrawal', comment = '$status', dept = '$dept', allow_auth = '$allow_auth' WHERE id ='$myeuserid'") or die("Error: " . mysqli_error($link)); 
    		($pos_wallet_settings != '1' || $walletId == "" ? "" : (($fetchWalletNum == 1 ) ? mysqli_query($link,"UPDATE pos_wallet SET walletid = '$walletId', username = '$wallet_uname', password = '$wallet_pass' WHERE tid = '$posid'") or die ("Error: " . mysqli_error($link)) : mysqli_query($link,"INSERT INTO pos_wallet VALUES(null,'$institution_id','$myeuserid','$wallet_uname','$wallet_pass','$walletId',NOW())") or die ("Error: " . mysqli_error($link))));
    		
    		echo "<script>alert('Update made successfully!!'); </script>";
    	    echo "<script>window.location='view_emp.php?id=".$myeuserid."&&mid=".base64_encode("419")."'; </script>";
    	
    	}
    }
}
?>
			     
             <div class="box-body">

<?php
$ide = $_GET['id'];
$eselect = mysqli_query($link, "SELECT * FROM user WHERE id = '$ide'") or die ("Error1: " . mysqli_error($link));
$erows = mysqli_fetch_array($eselect);
?>			
			<div class="form-group">
            <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Your Image</label>
			<div class="col-sm-10">
  		  			 <input type='file' name="image" onChange="readURL(this);" />
       				 <img id="blah"  src="<?php echo $fetchsys_config['file_baseurl'].$erows['image']; ?>" alt="Image Here" height="100" width="100"/>
			</div>
			</div>
			
			<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">First Name</label>
                  <div class="col-sm-10">
                  <input name="name" type="text" class="form-control" value="<?php echo $erows['name']; ?>" <?php echo ($ieditoption == "Enabled" && ($irole == "agent_manager" || $irole == "institution_super_admin" || $irole == "merchant_super_admin")) ? 'required' : 'readonly' ?>>
                  </div>
                  </div>

				  <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Last Name</label>
                  <div class="col-sm-10">
                  <input name="lname" type="text" class="form-control" value="<?php echo $erows['lname']; ?>" <?php echo ($ieditoption == "Enabled" && ($irole == "agent_manager" || $irole == "institution_super_admin" || $irole == "merchant_super_admin")) ? 'required' : 'readonly' ?>>
                  </div>
                  </div>

				  <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Middle Name</label>
                  <div class="col-sm-10">
                  <input name="mname" type="text" class="form-control" value="<?php echo $erows['mname']; ?>" <?php echo ($ieditoption == "Enabled" && ($irole == "agent_manager" || $irole == "institution_super_admin" || $irole == "merchant_super_admin")) ? 'required' : 'readonly' ?>>
                  </div>
                  </div>
				  
				  
		<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Email</label>
                  <div class="col-sm-10">
                  <input type="email" name="email" type="text" class="form-control" value="<?php echo $erows['email']; ?>" <?php echo ($ieditoption == "Enabled" && ($irole == "agent_manager" || $irole == "institution_super_admin" || $irole == "merchant_super_admin")) ? 'required' : 'readonly' ?>>
                  </div>
                  </div>
                  
        <div class="form-group">
                      <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Mobile Number</label>
                      <div class="col-sm-4">
                      <input name="phone" class="form-control" value="<?php echo $erows['phone']; ?>" <?php echo ($ieditoption == "Enabled" && ($irole == "agent_manager" || $irole == "institution_super_admin" || $irole == "merchant_super_admin")) ? 'required' : 'readonly' ?>>
                      </div><?php echo (isMobileDevice()) ? "<br>" : ""; ?>
                      <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Gender</label>
                      <div class="col-sm-4">
                            <select name="gender" class="form-control" required>
                                        <option value="<?php echo $erows['gender']; ?>" selected='selected'><?php echo $erows['gender']; ?></option>
                                        <option value="Male">Male</option>
                                        <option value="Female">Female</option>
                            </select>
                        </div>  
					</div>


		<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Date of Birth</label>
                  <div class="col-sm-10">
                  <input name="dob" type="date" class="form-control" value="<?php echo $erows['dob']; ?>" placeholder="Date of Birth: 1991-11-20" <?php echo ($ieditoption == "Enabled" && ($irole == "agent_manager" || $irole == "institution_super_admin" || $irole == "merchant_super_admin")) ? 'required' : 'readonly' ?>>
                  </div>
                  </div>
				  
				  
		 <div class="form-group">
                  	<label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Address</label>
                  	<div class="col-sm-10">
					<textarea name="addr1"  class="form-control" rows="2" cols="80" required><?php echo $erows['addr1']; ?></textarea>
           			 </div>
          </div>
			
			
			<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">City</label>
                  <div class="col-sm-10">
                  <input name="city" type="text" class="form-control" value="<?php echo $erows['city']; ?>" required >
                  </div>
                  </div>
				  
		<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">State</label>
                  <div class="col-sm-10">
                  <input name="state" type="text" class="form-control" value="<?php echo $erows['state']; ?>" required>
                  </div>
                  </div>
				  
				  <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Zip Code</label>
                  <div class="col-sm-10">
                  <input name="zip" type="text" class="form-control" value="<?php echo $erows['zip']; ?>" required>
                  </div>
                  </div>
				  
		<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Country</label>
                  <div class="col-sm-10">
				<select name="country"  class="form-control select2" required>
										<option value="<?php echo $erows['country']; ?>" selected><?php echo $erows['country']; ?></option>
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
			                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Branch Code</label>
			                  <div class="col-sm-10">
							<select name="branch"  class="form-control select2">
							    <?php
					    $brid = $erows['branchid'];
					    $read_branch = mysqli_query($link, "SELECT * FROM branches WHERE branchid = '$brid' AND created_by = '$institution_id'");
					    $fread_branch = mysqli_fetch_array($read_branch);
					    ?>
													<option value='<?php echo ($erows['branchid'] != "") ? $erows['branchid'] : ""; ?>' selected='selected'><?php echo ($erows['branchid'] != "") ? $fread_branch['bname'].' - '.$erows['branchid'] : "Head Office"; ?></option>
			<?php 
			$search_branch = mysqli_query($link, "SELECT * FROM branches WHERE created_by = '$institution_id'") or die (mysqli_error($link));
			while($get_branch = mysqli_fetch_object($search_branch))
			{
			?>
													<option value="<?php echo $get_branch->branchid; ?>"><?php echo $get_branch->bname; ?>( <?php echo $get_branch->branchid; ?> )</option>
			<?php } ?>
													</select>                 
												 </div>
			                 					 </div>

			<div class="form-group">
			    <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Status</label>
				<div class="col-sm-10">
					<select name="status" class="form-control select2">
						<option value='<?php echo $erows['comment']; ?>' selected='selected'><?php echo ($erows['comment'] == "Approved") ? "Activated" : $erows['comment']; ?></option>
						<option value='Not-Activated'>Not-Activated</option>
						<option value='Approved'>Activated</option>
					</select>                 
			 	</div>
			</div>

			<div class="form-group">
			    <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Allow Authorization</label>
				<div class="col-sm-10">
					<select name="allow_auth" class="form-control select2">
						<option value='<?php echo $erows['allow_auth']; ?>' selected='selected'><?php echo $erows['allow_auth']; ?></option>
						<option value='Yes'>Yes</option>
						<option value='No'>No</option>
					</select>                 
			 	</div>
			</div>
			
<hr>	
<div class="bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>">&nbsp;EMPLOYEE LOGIN INFORMATION</div>
<hr>	
	
			  <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Username</label>
                  <div class="col-sm-10">
                  <input name="username" type="text" class="form-control" value="<?php echo $erows['username']; ?>" readonly>
                  </div>
			  </div>
			  
              
<?php
if($idept_settings == "No"){
    
    echo "";
    
}
else{
    $dept_id = $erows['dept'];
    $search_dpt = mysqli_query($link, "SELECT * FROM dept WHERE id = '$dept_id'") or die (mysqli_error($link));
    $fetch_dpt = mysqli_fetch_array($search_dpt);
?>
                  
                  <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Department</label>
                  <div class="col-sm-10">
				    <select name="dept"  class="form-control select2">
						<option value="<?php echo $dept_id; ?>" selected><?php echo $fetch_dpt['dpt_name']; ?></option>
                        <?php 
                        $search_dept = mysqli_query($link, "SELECT * FROM dept WHERE companyid = '$institution_id'") or die (mysqli_error($link));
                        while($get_dept = mysqli_fetch_object($search_dept))
                        {
                        ?>
                        	<option value="<?php echo $get_dept->id; ?>"><?php echo $get_dept->dpt_name; ?></option>
                        <?php } ?>
					</select>                 
				 </div>
                </div>
              
<?php
}
?>



<?php
if($isavings_account == "Off"){
    
    echo "";
    
}
else{
?>

<hr>	
<div class="bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>">&nbsp;MOBILE APP SETTINGS</div>
<hr>

                <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Customer Registration</label>
                  <div class="col-sm-10">
				    <select name="cust_reg"  class="form-control select2" required>
						<option value="<?php echo $erows['cust_reg']; ?>" selected><?php echo $erows['cust_reg']; ?></option>
                        <option value="Allow">Allow</option>
                        <option value="Disallow">Disallow</option>
					</select>                 
				 </div>
                </div>

                <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Deposit</label>
                  <div class="col-sm-10">
				    <select name="deposit"  class="form-control select2" required>
						<option value="<?php echo $erows['deposit']; ?>" selected><?php echo $erows['deposit']; ?></option>
                        <option value="Allow">Allow</option>
                        <option value="Disallow">Disallow</option>
					</select>                 
				 </div>
                </div>

                <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Withdrawal</label>
                  <div class="col-sm-10">
				    <select name="withdrawal"  class="form-control select2" required>
						<option value="<?php echo $erows['withdrawal']; ?>" selected><?php echo $erows['withdrawal']; ?></option>
                        <option value="Allow">Allow</option>
                        <option value="Disallow">Disallow</option>
					</select>                 
				 </div>
                </div>

<?php
}
?>

			  
			  <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Select Role</label>
                  <div class="col-sm-9">
                  <table class="table table-responsive">
                    <?php
                    $search_module_properties = mysqli_query($link, "SELECT * FROM my_permission WHERE companyid = '$institution_id' OR companyid = 'all'");
                    while($fetch_mproperties = mysqli_fetch_array($search_module_properties))
                    {
                        $search_role = mysqli_query($link, "SELECT * FROM user WHERE created_by = '$institution_id' AND id = '$ide'");
                        $fetch_role = mysqli_fetch_array($search_role);
                        $myrole = $fetch_role['role'];
                    ?>
                    <tr>
                      <td>
                        <?php echo ucfirst(str_replace('_', ' ', $fetch_mproperties['urole'])); ?>
                      </td>
                      <td>
                        <input name="urole" type="radio" value="<?php echo $fetch_mproperties['urole']; ?>" <?php echo ($myrole == $fetch_mproperties['urole']) ? 'checked' : ''?>/>
                      </td>
                    </tr>
                    <?php } ?>
                  </table>
                </div>
            </div>
			  
			 </div>
			 
			  <div align="right">
              <div class="box-footer">
                <button name="emp" type="submit" class="btn bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>"><i class="fa fa-save">&nbsp;Save</i></button>
              </div>
			  </div>
			  
			 </form> 


</div>
</div>
</div>
</div>