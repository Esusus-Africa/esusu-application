<div class="box">
        
		 <div class="box-body">
			<div class="panel panel-success">
            <div class="panel-heading bg-blue">
            <h3 class="panel-title"><i class="fa fa-user"></i> View Employee</h3>
            </div>
             <div class="box-body">

<?php
$ide = $_GET['id'];
$select = mysqli_query($link, "SELECT * FROM user WHERE id = '$ide'") or die (mysqli_error($link));
$rows = mysqli_fetch_array($select);
$id = $rows['userid'];
$institution_id = $rows['created_by'];

$searchWallet = mysqli_query($link, "SELECT * FROM pos_wallet WHERE tid = '$ide'");
$fetchWallet = mysqli_fetch_array($searchWallet);
?>			 
               
			 <form class="form-horizontal" method="post" enctype="multipart/form-data" action="process_emp2.php?id=<?php echo $id; ?>">

             <div class="box-body">
				
			<div class="form-group">
            <label for="" class="col-sm-2 control-label" style="color:blue;">Your Image</label>
			<div class="col-sm-10">
  		  			 <input type='file' name="image" onChange="readURL(this);" />
       				 <img id="blah"  src="<?php echo $fetchsys_config['file_baseurl'].$rows['image']; ?>" alt="Image Here" height="100" width="100"/>
			</div>
			</div>
			
			<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">First Name</label>
                  <div class="col-sm-10">
                  <input name="name" type="text" class="form-control" value="<?php echo $rows['name']; ?>" required>
                  </div>
                  </div>

			<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Last Name</label>
                  <div class="col-sm-10">
                  <input name="lname" type="text" class="form-control" value="<?php echo $rows['lname']; ?>" required>
                  </div>
                  </div>

			<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Middle Name</label>
                  <div class="col-sm-10">
                  <input name="mname" type="text" class="form-control" value="<?php echo $rows['mname']; ?>">
                  </div>
                  </div>
				
		<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Email</label>
                  <div class="col-sm-10">
                  <input type="email" name="email" type="text" class="form-control" value="<?php echo $rows['email']; ?>" required>
                  </div>
                  </div>
				  
		<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Mobile Number</label>
                  <div class="col-sm-10">
                  <input name="phone" type="text" class="form-control" value="<?php echo $rows['phone']; ?>" required>
                  </div>
                  </div>
				  
				  
		 <div class="form-group">
                  	<label for="" class="col-sm-2 control-label" style="color:blue;">Address</label>
                  	<div class="col-sm-10">
					<textarea name="addr1"  class="form-control" rows="2" cols="80" required><?php echo $rows['addr1']; ?></textarea>
           			 </div>
          </div>
			
			<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">City</label>
                  <div class="col-sm-10">
                  <input name="city" type="text" class="form-control" value="<?php echo $rows['city']; ?>" required >
                  </div>
                  </div>
				  
		<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">State</label>
                  <div class="col-sm-10">
                  <input name="state" type="text" class="form-control" value="<?php echo $rows['state']; ?>" required>
                  </div>
                  </div>
				  
				  <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Zip Code</label>
                  <div class="col-sm-10">
                  <input name="zip" type="text" class="form-control" value="<?php echo $rows['zip']; ?>">
                  </div>
                  </div>
				  
		<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Country</label>
                  <div class="col-sm-10">
				<select name="country"  class="form-control select2" required>
										<option value="<?php echo $rows['country']; ?>" selected><?php echo $rows['country']; ?></option>
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
			                  <label for="" class="col-sm-2 control-label" style="color:blue;">Branch Code</label>
			                  <div class="col-sm-10">
							<select name="branch"  class="form-control select2">
													<option value='<?php echo ($rows['branchid'] != "") ? $rows['branchid'] : ""; ?>' selected='selected'><?php echo ($rows['branchid'] != "") ? $rows['branchid'] : "Head Office"; ?></option>
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

<?php
$dept_id = $rows['dept'];
$search_dpt = mysqli_query($link, "SELECT * FROM dept WHERE id = '$dept_id'") or die (mysqli_error($link));
$fetch_dpt = mysqli_fetch_array($search_dpt);
?>
                  
                  <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Department</label>
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

			<div class="form-group">
			    <label for="" class="col-sm-2 control-label" style="color:blue;">Status</label>
				<div class="col-sm-10">
					<select name="status" class="form-control select2">
						<option value='<?php echo $rows['comment']; ?>' selected='selected'><?php echo ($rows['comment'] == "Approved") ? "Activated" : $rows['comment']; ?></option>
						<option value='Not-Activated'>Not-Activated</option>
						<option value='Approved'>Activated</option>
						<option value='Suspended'>Suspend</option>
					</select>                 
			 	</div>
			</div>

			<div class="form-group">
			    <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Allow Authorization</label>
				<div class="col-sm-10">
					<select name="allow_auth" class="form-control select2">
						<option value='<?php echo $rows['allow_authorization']; ?>' selected='selected'><?php echo $rows['allow_authorization']; ?></option>
						<option value='Yes'>Yes</option>
						<option value='No'>No</option>
					</select>                 
			 	</div>
			</div>
			
<hr>	
<div class="bg-orange">&nbsp;EMPLOYEE LOGIN INFORMATION</div>
<hr>	
					
			  <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Username</label>
                  <div class="col-sm-10">
                  <input name="username" type="text" class="form-control" value="<?php echo $rows['username']; ?>" required>
                  </div>
			  </div>
			  
			  <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Password</label>
                  <div class="col-sm-10">
                  <input name="password" type="password" class="form-control" value="<?php echo base64_decode($rows['password']); ?>" required>
                  </div>
			  </div>

<?php
/**
if($pos_wallet_settings == '1' && $institution_id != ""){
?>
<!--
<hr>	
<div class="bg-orange">&nbsp;POS WALLET SETTINGS</div>
<hr>
			
			<input name="wtid" type="hidden" class="form-control" value="<?php echo $ide; ?>">

			<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Wallet ID</label>
                  <div class="col-sm-10">
                  <input name="walletId" type="text" class="form-control" value="<?php echo $fetchWallet['walletid']; ?>" placeholder="POS Wallet ID">
                  </div>
			</div>

			<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">POS Username</label>
                  <div class="col-sm-10">
                  <input name="wallet_uname" type="text" class="form-control" value="<?php echo $fetchWallet['username']; ?>" placeholder="POS Wallet Username">
                  </div>
			</div>

			<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">POS Password</label>
                  <div class="col-sm-10">
                  <input name="wallet_pass" type="password" class="form-control" value="<?php echo $fetchWallet['password']; ?>" placeholder="POS Wallet Password">
                  </div>
			</div>
-->
<?php
}
else{

	echo '<input name="wid" type="hidden" class="form-control" value="">';
	echo '<input name="walletId" type="hidden" class="form-control" value="">';
	echo '<input name="wallet_uname" type="hidden" class="form-control" value="">';
	echo '<input name="wallet_pass" type="hidden" class="form-control" value="">';

}
**/
?>


<?php
if($institution_id != ""){
?>

<hr>	
<div class="bg-orange">&nbsp;MOBILE APP SETTINGS</div>
<hr>

                <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Customer Registration</label>
                  <div class="col-sm-10">
				    <select name="cust_reg"  class="form-control select2" required>
						<option value="<?php echo $rows['cust_reg']; ?>" selected><?php echo $rows['cust_reg']; ?></option>
                        <option value="Allow">Allow</option>
                        <option value="Disallow">Disallow</option>
					</select>                 
				 </div>
                </div>

                <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Deposit</label>
                  <div class="col-sm-10">
				    <select name="deposit"  class="form-control select2" required>
						<option value="<?php echo $rows['deposit']; ?>" selected><?php echo $rows['deposit']; ?></option>
                        <option value="Allow">Allow</option>
                        <option value="Disallow">Disallow</option>
					</select>                 
				 </div>
                </div>

                <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Withdrawal</label>
                  <div class="col-sm-10">
				    <select name="withdrawal"  class="form-control select2" required>
						<option value="<?php echo $rows['withdrawal']; ?>" selected><?php echo $rows['withdrawal']; ?></option>
                        <option value="Allow">Allow</option>
                        <option value="Disallow">Disallow</option>
					</select>                 
				 </div>
                </div>

				<?php
}
else{

	echo '<input name="cust_reg" type="hidden" class="form-control" value="">';
	echo '<input name="deposit" type="hidden" class="form-control" value="">';
	echo '<input name="withdrawal" type="hidden" class="form-control" value="">';

}
?>

<hr>	
<div class="bg-orange">&nbsp;PREDEFINED ROLE</div>
<hr>
			  
			  <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Select Role</label>
                  <div class="col-sm-9">
                  <table class="table table-responsive">
                    <?php
					if($institution_id != ""){

						$id = mysqli_real_escape_string($link, $_GET['id']);
						$search_module_properties = mysqli_query($link, "SELECT * FROM my_permission WHERE companyid = '$institution_id' OR companyid = 'all'");
						while($fetch_mproperties = mysqli_fetch_array($search_module_properties))
						{
							$search_role = mysqli_query($link, "SELECT * FROM user WHERE created_by = '$institution_id' AND id = '$id'");
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
					<?php
						}
					 }else{
						$id = mysqli_real_escape_string($link, $_GET['id']);
						$search_module_properties = mysqli_query($link, "SELECT * FROM my_permission2 WHERE urole != 'super_admin'");
						while($fetch_mproperties = mysqli_fetch_array($search_module_properties))
						{
							$search_role = mysqli_query($link, "SELECT * FROM user WHERE id = '$id'");
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
					<?php
						}
					 }?>
                  </table>
                </div>
            </div>
			  
			 </div>
			 
			  <div align="right">
              <div class="box-footer">
                	<button name="emp" type="submit" class="btn bg-blue"><i class="fa fa-save">&nbsp;Save</i></button>
              </div>
			  </div>
			  
			 </form> 

</div>
</div>
</div>
</div>