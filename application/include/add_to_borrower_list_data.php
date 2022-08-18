<div class="box">
	       <div class="box-body">
			<div class="panel panel-success">
            <div class="panel-heading bg-blue">
            <h3 class="panel-title"><i class="fa fa-user"></i> Update Customer Information</h3>
            </div>
             <div class="box-body">
<?php
$id = $_GET['id'];
$select = mysqli_query($link, "SELECT * FROM borrowers WHERE id = '$id'") or die (mysqli_error($link));
while($row = mysqli_fetch_array($select))
{   
?>                         
			 <form class="form-horizontal" method="post" enctype="multipart/form-data">
			  <?php echo '<div class="bg-orange fade in" >
			  <a href = "#" class = "close" data-dismiss= "alert"> &times;</a>
  				<strong>NOTE:&nbsp;</strong> Basic Information needed to be updated for Cardholder registration are:
				  <p><i class="fa fa-hand-o-right"></i> First and Last Name as writen on Customer Government Issued ID Card.</p>
				  <p><i class="fa fa-hand-o-right"></i> Mobile number to be in International Format.</p>
				  <p><i class="fa fa-hand-o-right"></i> Customer Date of Birth i.e. YYYY-MM-DD</p>
				  <p><i class="fa fa-hand-o-right"></i> Home Address</p>
				  <p><i class="fa fa-hand-o-right"></i> City, State and lastly, Zip/Postal Code</p>
				</div>'?>
             <div class="box-body">
				
			<div class="form-group">
            <label for="" class="col-sm-2 control-label">Your Image</label>
			<div class="col-sm-10">
  		  			 <input type='file' name="image" onChange="readURL(this);" /><br>
       				 <img id="blah"  src="<?php echo $fetchsys_config['file_baseurl'].$row['image']; ?>" alt="Image Here" height="100" width="100"/>
			</div>
			</div>
			
			 <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Account Number</label>
                  <div class="col-sm-10">
                  <input name="account" type="text" class="form-control" value="<?php echo $row['account']; ?>" placeholder="Account Number" readonly>
                  </div>
                  </div>
			
			<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">First Name</label>
                  <div class="col-sm-10">
                  <input name="fname" type="text" class="form-control" value="<?php echo $row['fname']; ?>" placeholder="First Name" required>
                  </div>
                  </div>
				  
		<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Last Name</label>
                  <div class="col-sm-10">
                  <input name="lname" type="text" class="form-control" value="<?php echo $row['lname']; ?>" placeholder="Last Name" required>
                  </div>
                  </div>
				  
		<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Email</label>
                  <div class="col-sm-10">
                  <input type="email" name="email" type="text" class="form-control" value="<?php echo $row['email']; ?>" placeholder="Email">
                  </div>
                  </div>
				  
		<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Mobile Number</label>
                  <div class="col-sm-10">
                  <input name="phone" type="text" class="form-control" value="<?php echo $row['phone']; ?>" placeholder="Mobile Number" required>
                  </div>
                  </div>
				  
		<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Date of Birth</label>
                  <div class="col-sm-10">
                  <input name="dob" type="date" class="form-control" value="<?php echo $row['dob']; ?>" placeholder="Date of Birth: 1991-11-20" required>
                  </div>
                  </div>
                  
        <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">BVN</label>
                  <div class="col-sm-10">
                  <input name="bvn" type="number" class="form-control" value="<?php echo $row['unumber']; ?>" placeholder="Customer BVN Number">
                  </div>
                  </div>
                  
        <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Occupation</label>
                  <div class="col-sm-10">
                  <input name="occupation" type="text" class="form-control" value="<?php echo $row['occupation']; ?>" placeholder="Occupation">
                  </div>
                  </div>
				  
		 <div class="form-group">
                  	<label for="" class="col-sm-2 control-label" style="color:blue;">Address</label>
                  	<div class="col-sm-10"><textarea name="addrs"  class="form-control" rows="4" cols="80"><?php echo $row['addrs']; ?></textarea></div>
          </div>
		  
		  <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">City</label>
                  <div class="col-sm-10">
                  <input name="city" type="text" class="form-control" value="<?php echo $row['city']; ?>" placeholder="City">
                  </div>
                  </div>
				  
		<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">State</label>
                  <div class="col-sm-10">
                  <input name="state" type="text" class="form-control" value="<?php echo $row['state']; ?>" placeholder="State">
                  </div>
                  </div>
				  
		<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Zip/Postal Code</label>
                  <div class="col-sm-10">
                  <input name="zip" type="text" class="form-control" value="<?php echo $row['zip']; ?>" placeholder="Zip / Postal Code">
                  </div>
                  </div>
				  
		<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Country</label>
                  <div class="col-sm-10">
				<select name="country"  class="form-control">
										<option value="<?php echo $row['country']; ?>" selected='selected'><?php echo $row['country']; ?></option>
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
										<option value="NG">Nigeria</option>
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
					                  <label for="" class="col-sm-2 control-label" style="color:blue;">Registrar Code</label>
					                  <div class="col-sm-10">
					<select name="branch"  class="form-control select2">
							<option value="<?php echo $row['branchid']; ?>" selected='selected'><?php echo $row['branchid']; ?></option>
					<?php 
					$search_branch = mysqli_query($link, "SELECT * FROM branches") or die (mysqli_error($link));
					while($get_branch = mysqli_fetch_object($search_branch))
					{
					?>
						<option value="<?php echo $get_branch->branchid; ?>"><?php echo $get_branch->bname; ?>( <?php echo $get_branch->branchid; ?> )</option>
					<?php } ?>
															</select>                 
														 </div>
					                 					 </div>
					                 					 
					           <div class="form-group">
					                  <label for="" class="col-sm-2 control-label" style="color:blue;">Status</label>
					                  <div class="col-sm-10">
					<select name="status"  class="form-control select2">
						<option value="<?php echo $row['acct_status']; ?>" selected='selected'><?php echo $row['acct_status']; ?></option>
						<option value="Activated">Activate Account</option>
						<option value="Not-Activated">Deactivate Account</option>
															</select>                 
														 </div>
					                 					 </div>
					                 					 
		<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Username</label>
                  <div class="col-sm-10">
                  <input name="username" type="text" class="form-control" value="<?php echo $row['username']; ?>" placeholder="Username" required/>
                  </div>
                  </div>
                  
        <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Password</label>
                  <div class="col-sm-10">
                  <input name="password" type="password" class="form-control" value="<?php echo $row['password']; ?>" placeholder="Password" required/>
                  </div>
                  </div>
					                 					 
<hr>
<div class="bg-orange">NEXT OF KIN DETAILS</div>
<hr>
                  
        <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Next of Kin</label>
                  <div class="col-sm-10">
                  <input name="nok" type="text" class="form-control" value="<?php echo $row['nok']; ?>" placeholder="Next of Kin">
                  </div>
                  </div>   
        
        <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Relationship</label>
                  <div class="col-sm-10">
                  <input name="nok_rela" type="text" class="form-control" value="<?php echo $row['nok_rela']; ?>" placeholder="Next of Kin">
                  </div>
                  </div> 
                 
        <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Phone</label>
                  <div class="col-sm-10">
                  <input name="nok_phone" type="text" class="form-control" value="<?php echo $row['nok_phone']; ?>" placeholder="Next of Kin Phone Number">
                  </div>
                  </div>
                 
        <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Enable OTP</label>
                  <div class="col-sm-10">
				<select name="otp"  class="form-control select2">
					<option value="<?php echo $row['opt_option']; ?>" selected="selected"><?php echo $row['opt_option']; ?></option>	
					<option value="Yes">Yes</option>
					<option value="No">No</option>
				</select>
		</div>
		</div>
		
		<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Allow Overdraft</label>
                  <div class="col-sm-10">
				<select name="overdraft"  class="form-control select2">
				            <option value="<?php echo $row['overdraft']; ?>" selected="selected"><?php echo $row['overdraft']; ?></option>
				            <option value="No">No</option>
							<option value="Yes">Yes</option>
				</select>
		</div>
		</div>

<!--
<hr>
<div class="bg-orange">SET INTERVAL</div>
<hr>

		<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Savings Contribution Interval</label>
                  <div class="col-sm-10">
				<select name="c_interval"  class="form-control select2">
					<option value="<?php echo $row['s_contribution_interval']; ?>" selected="selected"><?php echo $row['s_contribution_interval']; ?></option>	
					<option value="Daily">Daily</option>
					<option value="Weekly">Weekly</option>
					<option value="Monthly">Monthly</option>
					<option value="None">None</option>
				</select>
		</div>
		</div>

		<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Savings Disbursement Interval</label>
                  <div class="col-sm-10">
				<select name="d_interval"  class="form-control select2">
					<option value="<?php echo $row['s_disbursement_interval']; ?>" selected='selected'><?php echo $row['s_disbursement_interval']; ?></option>	
					<option value="Monthly">Monthly</option>
					<option value="Quarterly">Quarterly</option>
					<option value="Biannually">Biannually</option>
					<option value="Annually">Annually</option>
					<option value="None">None</option>
				</select>
		</div>
		</div>
		
<hr>
<div class="bg-orange">Update Scanned Copy of the Registration Form Filled by the Customer</div>
<hr>

        <div class="form-group">
            <label for="" class="col-sm-2 control-label" style="color:blue;">Scanned Copy</label>
			<div class="col-sm-10">
  		  			 <input type='file' name="c_sign" class="btn bg-orange" onChange="readURL(this);"/>
  		  			 <?php
$account = mysqli_real_escape_string($link, $_POST['account']);
$search_file = mysqli_query($link, "SELECT * FROM borrower_form WHERE accno = '$account'") or die ("Error: " . mysqli_error($link));
if(mysqli_num_rows($search_file) == 0){
  echo "<span style='color: red;'>No file attached!!</span>";
}else{
  $get_file = mysqli_fetch_array($search_file);
?>
<a href="<?php echo $get_file['bfile']; ?>" target="_blank"><img src="../img/file_attached.png" width="64" height="64"> Attached File</a>
<?php
}
?>
		</div>
		</div>
-->

			 </div>
			 
			  <div align="right">
              <div class="box-footer">
                	<a href="customer.php?id=<?php echo $_SESSION['tid']; ?>&&mid=NDAz"><button type="button" class="btn bg-orange "><i class="fa fa-reply">&nbsp;Back</i></button></a>
                	<button name="save" type="submit" class="btn bg-blue"><i class="fa fa-save">&nbsp;Save</i></button>
              </div>
			  </div>
<?php
if(isset($_POST['save']))
{
$id = $_GET['id'];
$fname =  mysqli_real_escape_string($link, $_POST['fname']);
$lname = mysqli_real_escape_string($link, $_POST['lname']);
$email = mysqli_real_escape_string($link, $_POST['email']);
$phone = mysqli_real_escape_string($link, $_POST['phone']);
$dob = mysqli_real_escape_string($link, $_POST['dob']);
$bvn = mysqli_real_escape_string($link, $_POST['bvn']);
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
$acct_status = mysqli_real_escape_string($link, $_POST['status']);
$status = "Completed";
$branchid = mysqli_real_escape_string($link, $_POST['branch']);

//$c_interval = mysqli_real_escape_string($link, $_POST['c_interval']);
//$d_interval = mysqli_real_escape_string($link, $_POST['d_interval']);
$otp = mysqli_real_escape_string($link, $_POST['otp']);
$username = mysqli_real_escape_string($link, $_POST['username']);
$password = mysqli_real_escape_string($link, $_POST['password']);
$overdraft = mysqli_real_escape_string($link, $_POST['overdraft']);

$bank = "Bancore";
$verify_customer = mysqli_query($link, "SELECT * FROM borrowers WHERE id = '$id'");
$fetch_customer = mysqli_fetch_array($verify_customer);
$card_reg = $fetch_customer['card_reg'];

$systemset = mysqli_query($link, "SELECT * FROM systemset");
$row1 = mysqli_fetch_object($systemset);
$seckey = $row1->secret_key;
$sysabb = $row1->abb;
$bancore_merchantID = $row1->bancore_merchant_acctid;
$bancore_mprivate_key = $row1->bancore_merchant_pkey;
$passcode = $bancore_merchantID.substr($phone, -13).$email.$dob.$bancore_mprivate_key;
$encKey = hash('sha256',$passcode);

//this handles uploading of image
$image = addslashes(file_get_contents($_FILES['image']['tmp_name']));
//$image_c_sign = addslashes(file_get_contents($_FILES['c_sign']['tmp_name']));

if($image == "" && $card_reg == "No")
{
	$insert = mysqli_query($link, "UPDATE borrowers SET fname='$fname', lname='$lname', email='$email', phone='$phone', dob='$dob', occupation='$occupation', addrs='$addrs', city='$city', state='$state', zip='$zip', country='$country', account='$account', sbranchid='$branchid', nok='$nok', nok_rela = '$nok_rela', nok_phone = '$nok_phone', acct_status = '$acct_status', opt_option = '$otp', username = '$username', password = '$password', overdraft = '$overdraft', unumber = '$bvn' WHERE id = '$id'") or die (mysqli_error($link));
	if(!$insert)
	{
		echo "<div class='alert alert-info'>Update Error.....Please try again later</div>";
	}
	else{
		echo "<script>alert('Customer Info Updated Successfully!');</script>";
		echo "<script>window.location='add_to_borrower_list.php?id=".$id."&&mid=".base64_encode("403")."';</script>";
	}
}
elseif($image == "" && $card_reg == "Yes")
{
	$api_name =  "cardholder_profile_update";
	$search_restapi = mysqli_query($link, "SELECT * FROM atmcard_gateway_apis WHERE api_name = '$api_name' AND issuer_name = '$bank'");
	$fetch_restapi = mysqli_fetch_object($search_restapi);
	$api_url = $fetch_restapi->api_url;
	$issuer_name = $fetch_restapi->issuer_name;
		
	function registerCardHolder($ph, $address, $ctry, $em, $fn, $ln, $zip, $city, $dob, $debug=false){
		global $bancore_merchantID,$encKey,$api_url;
		
		$url = '?merchantID='.$bancore_merchantID;
		$url.= '&phone='.urlencode(substr($ph, -13));
		$url.= '&address='.urlencode($address);
		$url.= '&country='.urlencode($ctry);
		$url.= '&email='.urlencode($em);
		$url.= '&firstName='.urlencode($fn);
		$url.= '&lastName='.urlencode($ln);
		$url.= '&zip='.urlencode($zip);
		$url.= '&city='.urlencode($city);
		$url.= '&dateOfBirth='.urlencode($dob);
		$url.= '&subscribed=true';
		$url.= '&encKey='.$encKey;
		
		$urltouse =  $api_url.$url;
		//if ($debug) { echo "Request: <br>$urltouse<br><br>"; }
		echo $id;
		//Open the URL to send the message
		$response = file_get_contents($urltouse);
		if ($debug) {
			/**
			echo "Response: <br><pre>".
		    str_replace(array("<",">"),array("&lt;","&gt;"),$response).
		    "</pre><br>"; 
			echo substr($response, 7);
			**/
		}
		return($response);
	}
		
	$debug = true;
	if(registerCardHolder($phone,$addrs,$country,$email,$fname,$lname,$zip,$city,$dob,$debug)){
		$insert = mysqli_query($link, "UPDATE borrowers SET fname='$fname', lname='$lname', email='$email', phone='$phone', dob='$dob', occupation='$occupation', addrs='$addrs', city='$city', state='$state', zip='$zip', country='$country', account='$account', sbranchid='$branchid', nok='$nok', nok_rela = '$nok_rela', nok_phone = '$nok_phone', acct_status = '$acct_status', opt_option = '$otp', username = '$username', password = '$password', overdraft = '$overdraft', unumber = '$bvn' WHERE id = '$id'") or die (mysqli_error($link));
				
		echo "<script>alert('Customer Info Updated Successfully!');</script>";
		echo "<script>window.location='add_to_borrower_list.php?id=".$id."&&mid=".base64_encode("403")."';</script>";
	}else{
		echo "<script>alert('Oops!....Network Error! Please try again later');</script>";
		echo "<script>window.location='add_to_borrower_list.php?id=".$id."&&mid=".base64_encode("403")."';</script>";
	}
}
elseif($image != "" && $card_reg == "No")
{
	$target_dir = "../img/";
	$target_file = $target_dir.basename($_FILES["image"]["name"]);
	$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
	$check = getimagesize($_FILES["image"]["tmp_name"]);
	
	if($check == false)
	{
		echo "<p style='font-size:24px; color:#FF0000'>Invalid file type</p>";
	}
	elseif($_FILES["image"]["size"] > 500000)
	{
		echo "<p style='font-size:24px; color:#FF0000'>Image must not more than 500KB!</p>";
	}
	elseif($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif")
	{
		echo "<p style='font-size:24px; color:#FF0000'>Sorry, only JPG, JPEG, PNG & GIF Files are allowed for the Profile Picture.</p>";
	}
	else{
		$sourcepath = $_FILES["image"]["tmp_name"];
		$targetpath = "../img/" . $_FILES["image"]["name"];
		move_uploaded_file($sourcepath,$targetpath);

		$location = $_FILES['image']['name'];
	
		$insert = mysqli_query($link, "UPDATE borrowers SET fname='$fname', lname='$lname', email='$email', phone='$phone', dob='$dob', occupation='$occupation', addrs='$addrs', city='$city', state='$state', zip='$zip', country='$country', account='$account', sbranchid='$branchid', nok='$nok', nok_rela = '$nok_rela', nok_phone = '$nok_phone', image='$location', acct_status = '$acct_status', opt_option = '$otp', username = '$username', password = '$password', overdraft = '$overdraft', unumber = '$bvn' WHERE id = '$id'") or die (mysqli_error($link));
		if(!$insert)
		{
			echo "<div class='alert alert-info'>Update Error.....Please try again later</div>";
		}
		else{
			echo "<script>alert('Customer Info Updated Successfully!');</script>";
			echo "<script>window.location='add_to_borrower_list.php?id=".$id."&&mid=".base64_encode("403")."';</script>";
		}
	}
}
else{
	$api_name =  "cardholder_profile_update";
	$search_restapi = mysqli_query($link, "SELECT * FROM atmcard_gateway_apis WHERE api_name = '$api_name' AND issuer_name = '$bank'");
	$fetch_restapi = mysqli_fetch_object($search_restapi);
	$api_url = $fetch_restapi->api_url;
	$issuer_name = $fetch_restapi->issuer_name;
		
	function registerCardHolder($ph, $address, $ctry, $em, $fn, $ln, $zip, $city, $dob, $debug=false){
		global $bancore_merchantID,$encKey,$id,$api_url;
		
		$url = '?merchantID='.$bancore_merchantID;
		$url.= '&phone='.urlencode(substr($ph, -13));
		$url.= '&address='.urlencode($address);
		$url.= '&country='.urlencode($ctry);
		$url.= '&email='.urlencode($em);
		$url.= '&firstName='.urlencode($fn);
		$url.= '&lastName='.urlencode($ln);
		$url.= '&zip='.urlencode($zip);
		$url.= '&city='.urlencode($city);
		$url.= '&dateOfBirth='.urlencode($dob);
		$url.= '&subscribed=true';
		$url.= '&encKey='.$encKey;
		
		$urltouse =  $api_url.$url;
		//if ($debug) { echo "Request: <br>$urltouse<br><br>"; }
		
		//Open the URL to send the message
		$response = file_get_contents($urltouse);
		if ($debug) {
			//echo "Response: <br><pre>".
		    //str_replace(array("<",">"),array("&lt;","&gt;"),$response).
		    //"</pre><br>"; 
			//echo substr($response, 7);
		}
		return($response);
	}
		
	$debug = true;
	if(registerCardHolder($phone,$addrs,$country,$email,$fname,$lname,$zip,$city,$dob,$debug)){
		$target_dir = "../img/";
		$target_file = $target_dir.basename($_FILES["image"]["name"]);
		$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
		$check = getimagesize($_FILES["image"]["tmp_name"]);
				
		if($check == false)
		{
			echo "<p style='font-size:24px; color:#FF0000'>Invalid file type</p>";
		}
		elseif($_FILES["image"]["size"] > 500000)
		{
			echo "<p style='font-size:24px; color:#FF0000'>Image must not more than 500KB!</p>";
		}
		elseif($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif")
		{
			echo "<p style='font-size:24px; color:#FF0000'>Sorry, only JPG, JPEG, PNG & GIF Files are allowed for the Profile Picture.</p>";
		}
		else{
			$sourcepath = $_FILES["image"]["tmp_name"];
			$targetpath = "../img/" . $_FILES["image"]["name"];
			move_uploaded_file($sourcepath,$targetpath);
			
			$location = $_FILES['image']['name'];
				
			$insert = mysqli_query($link, "UPDATE borrowers SET fname='$fname', lname='$lname', email='$email', phone='$phone', dob='$dob', occupation='$occupation', addrs='$addrs', city='$city', state='$state', zip='$zip', country='$country', account='$account', sbranchid='$branchid', nok='$nok', nok_rela = '$nok_rela', nok_phone = '$nok_phone', image='$location', acct_status = '$acct_status', opt_option = '$otp', username = '$username', password = '$password', overdraft = '$overdraft', unumber = '$bvn' WHERE id = '$id'") or die (mysqli_error($link));
			
			echo "<script>alert('Customer Info Updated Successfully!');</script>";
			echo "<script>window.location='add_to_borrower_list.php?id=".$id."&&mid=".base64_encode("403")."';</script>";
		}
	}
	
}
}
?>			  
			 </form> 
			 
<?php } ?>


</div>	
</div>	
</div>
</div>