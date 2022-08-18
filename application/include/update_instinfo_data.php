<div class="row"> 
    
   <section class="content">
 <?php
$id = $_GET['idm'];
$search_inst = mysqli_query($link, "SELECT * FROM institution_data WHERE id = '$id'");
$fetch_inst = mysqli_fetch_object($search_inst);
$insti_id = $fetch_inst->institution_id;
$iaccount_type = $fetch_inst->account_type;

$search_iuser = mysqli_query($link, "SELECT * FROM user WHERE created_by = '$insti_id'");
$fetch_iuser = mysqli_fetch_object($search_iuser);
?>      
       <div class="box box-danger">
           <div class="box-body">
             <div class="table-responsive"> 
            <div class="box-body">

          <a href="listinstitution.php?id=<?php echo $_SESSION['tid']; ?>&&mid=NDE5"> <button type="button" class="btn btn-flat bg-orange"><i class="fa fa-reply-all"></i>&nbsp;Back</button></a>

     <div class="col-md-14">
            <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
             <li <?php echo ($_GET['tab'] == 'tab_1') ? "class='active'" : ''; ?>><a href="update_instinfo.php?id=<?php echo $_SESSION['tid']; ?>&&idm=NDE5&&tab=tab_1">Update Client Info</a></li>
             <!--
             <li <?php //echo ($_GET['tab'] == 'tab_2') ? "class='active'" : ''; ?>><a href="update_instinfo.php?id=<?php //echo $_SESSION['tid']; ?>&&idm=<?php //echo $_GET['idm']; ?>&&mid=<?php //echo base64_encode("419"); ?>&&tab=tab_2">Update Multiple Institutions</a></li>
           -->
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
if(isset($_POST['update']))
{
  $id = $_GET['idm'];
  $institution_id = mysqli_real_escape_string($link, $_POST['institution_id']);
  $iname = mysqli_real_escape_string($link, $_POST['iname']);
  $license_no = mysqli_real_escape_string($link, $_POST['license_no']);
  $addrs = mysqli_real_escape_string($link, $_POST['addrs']);
  $state = mysqli_real_escape_string($link, $_POST['state']);
  $country = mysqli_real_escape_string($link, $_POST['country']);
  $official_phone = mysqli_real_escape_string($link, $_POST['official_phone']);
  $official_email = mysqli_real_escape_string($link, $_POST['official_email']);
  $usernamei = mysqli_real_escape_string($link, $_POST['usernamei']);
  $passwordi = mysqli_real_escape_string($link, $_POST['passwordi']);
  $encrypt_passi = base64_encode($passwordi);
  $mystatus = mysqli_real_escape_string($link, $_POST['status']);
  $frontend_reg = mysqli_real_escape_string($link, $_POST['frontend_reg']);

  //$c_level = mysqli_real_escape_string($link, $_POST['c_level']);
  
  /*$search_mycommission = mysqli_query($link, "SELECT * FROM company_commission_level WHERE companyid = '$institution_id'");
  //Compensation Plan
  $search_mycompensation = mysqli_query($link, "SELECT * FROM compensation_plan WHERE plan_level = '$c_level'");
  $fetch_mycompensation = mysqli_fetch_object($search_mycompensation);
  $mypercentage = $fetch_mycompensation->percentage;*/

  $image = addslashes(file_get_contents($_FILES['image']['tmp_name']));

  $verify_urlid = mysqli_query($link, "SELECT * FROM member_settings WHERE companyid = '$institution_id'");
  $fetch_urlid = mysqli_fetch_object($verify_urlid);
  $sender_id = $fetch_urlid->sender_id;

  $search_uinst = mysqli_query($link, "SELECT * FROM user WHERE created_by = '$institution_id' AND (role = 'agent_manager' OR role = 'institution_super_admin' OR role = 'merchant_super_admin')");
  $fetch_uinst = mysqli_fetch_object($search_uinst);
  $transactionPin = $fetch_uinst->tpin;
  $myuinstid = $fetch_uinst->userid;
  $iemail = $fetch_uinst->email;
  $iusername = $fetch_uinst->username;
  $ipassword = base64_decode($fetch_uinst->password);
  $idr = $fetch_uinst->id;

  $target_dir = "../img/";
  $target_file = $target_dir.basename($_FILES["image"]["name"]);
  $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
  $check = getimagesize($_FILES["image"]["tmp_name"]);
          
  $sourcepath = $_FILES["image"]["tmp_name"];
  $targetpath = "../img/" . $_FILES["image"]["name"];
  ($image == "") ? "" : move_uploaded_file($sourcepath,$targetpath);
    
  ($image == "") ? "" : $location = $_FILES['image']['name'];
    
  $detect_default_image = mysqli_query($link, "SELECT * FROM institution_data WHERE id = '$id'");
  $fetch_default_image = mysqli_fetch_object($detect_default_image);
  $currentStatus = $fetch_default_image->status;
  $default_image = $fetchsys_config['file_baseurl'].$fetch_default_image->institution_logo;
  ($image == "") ? "" : unlink($default_image);
  $status = ($mystatus == "Updated") ? $currentStatus : $mystatus;
  
  foreach ($_FILES['uploaded_file']['name'] as $key => $name){

    $newFilename = $name;
            
    if($newFilename == "")
    {
      echo "";
    }
    else{
        $newlocation = $newFilename;
     		if(move_uploaded_file($_FILES['uploaded_file']['tmp_name'][$key], '../img/'.$newFilename))
     		{
           mysqli_query($link, "INSERT INTO institution_legaldoc VALUES(null,'$institution_id','$newlocation')") or die ("Error: " .mysqli_error($link));
        }
    }
        	
  }

  ($image == "" && ($status == "Updated" || $status == "Approved")) ? $update = mysqli_query($link, "UPDATE institution_data SET institution_logo = '$location', institution_name = '$iname', license_no = '$license_no', location = '$addrs', state = '$state', country = '$country', official_phone = '$official_phone', official_email = '$official_email', status = '$status', fontend_reg = '$frontend_reg' WHERE id = '$id'") or die ("ERROR: " .mysqli_error($link)) : "";
  ($image == "" && ($status == "Updated" || $status == "Approved")) ? $update = mysqli_query($link, "UPDATE member_settings SET logo = '$location', cname = '$iname' WHERE companyid = '$institution_id'") or die ("ERROR1: " .mysqli_error($link)) : "";
  ($image == "" && ($status == "Updated" || $status == "Approved")) ? $update = mysqli_query($link, "UPDATE user SET comment = '$status', addr1 = '$addrs', email = '$official_email', username = '$usernamei', password = '$encrypt_passi' WHERE userid = '$myuinstid'") : "";

  ($image != "" && ($status != "Updated" || $status != "Approved")) ? $update = mysqli_query($link, "UPDATE institution_data SET institution_name = '$iname', license_no = '$license_no', location = '$addrs', state = '$state', country = '$country', official_phone = '$official_phone', official_email = '$official_email', status = '$status', fontend_reg = '$frontend_reg' WHERE id = '$id'") or die ("Error: " .mysqli_error($link)) : "";
  ($image != "" && ($status != "Updated" || $status != "Approved")) ? $update = mysqli_query($link, "UPDATE user SET comment = '$status', addr1 = '$addrs', email = '$official_email', username = '$usernamei', password = '$encrypt_passi' WHERE userid = '$myuinstid'") : "";

  ($image != "" && ($status == "Updated" || $status == "Approved")) ? $update = mysqli_query($link, "UPDATE institution_data SET institution_name = '$iname', license_no = '$license_no', location = '$addrs', state = '$state', country = '$country', official_phone = '$official_phone', official_email = '$official_email', fontend_reg = '$frontend_reg' WHERE id = '$id'") or die ("Error: " .mysqli_error($link)) : "";
  ($image != "" && ($status == "Updated" || $status == "Approved")) ? $update = mysqli_query($link, "UPDATE member_settings SET logo = '$location', cname = '$iname' WHERE companyid = '$institution_id'") or die ("ERROR1: " .mysqli_error($link)) : "";
  ($image != "" && ($status == "Updated" || $status == "Approved")) ? $update = mysqli_query($link, "UPDATE user SET addr1 = '$addrs', email = '$official_email', username = '$usernamei', password = '$encrypt_passi' WHERE userid = '$myuinstid'") or die ("Error: " .mysqli_error($link)) : "";

  ($status == "Suspended" || $status == "Approved") ? $update = mysqli_query($link, "UPDATE user SET comment = '$status' WHERE created_by = '$institution_id' AND (status = 'Approved' OR status = 'Suspended')") or die ("Error: " .mysqli_error($link)) : "";
      
  $query = mysqli_query($link, "SELECT * FROM systemset") or die ("Error: " .mysqli_error($link));    
  $r = mysqli_fetch_object($query);
  $sys_abb = $r->abb;
  $sys_email = $r->email;
  $sms_refid = uniqid("EA-smsCharges-").time();
    
  $sms = "$r->abb>>>Dear $iname! Your Account has been activated. Your Institution ID: $institution_id, Transaction Pin: $transactionPin. Login via: https://esusu.app/$sender_id";
  
  $max_per_page = 153;
  $sms_length = strlen($sms);
  $calc_length = ceil($sms_length / $max_per_page);
  $sms_rate = $r->fax;
  $sms_charges = $calc_length * $sms_rate;

  ($mystatus == "Approved") ? $sendSMS->backendGeneralAlert($sys_abb, $official_phone, $sms, $sms_refid, $sms_charges, $uid) : "";
  ($mystatus == "Approved") ? $sendSMS->clientRegNotifier($official_email, $iname, $iusername, $ipassword, $sender_id) : "";

  "<div class='alert alert-success'>Update Successfully!</div>";

}
?>           
       <form class="form-horizontal" method="post" enctype="multipart/form-data">

             <div class="box-body">

      <input name="institution_id" type="hidden" class="form-control" value="<?php echo $fetch_inst->institution_id; ?>">

       <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;"><?php echo ($iaccount_type === 'institution' ? 'Institution' : ($iaccount_type === 'agent' ? 'Agent' : 'Merchant')); ?></label>
                  <div class="col-sm-10">
                    <span style="color: orange; font-size: 20px;"><b><?php echo $fetch_inst->institution_name.' <span style="color: blue;">['.$fetch_inst->institution_id.']</span>'; ?></b></span>
                  </div>
                  </div>
        
      <div class="form-group">
            <label for="" class="col-sm-2 control-label" style="color:blue;">Logo</label>
      <div class="col-sm-10">
               <input type='file' name="image" onChange="readURL(this);">
               <img id="blah"  src="<?php echo $fetchsys_config['file_baseurl'].$fetch_inst->institution_logo; ?>" alt="Logo Here" height="100" width="100"/>
      </div>
      </div>
      
      <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Name</label>
                  <div class="col-sm-10">
                  <input name="iname" type="text" class="form-control" value="<?php echo $fetch_inst->institution_name; ?>" required>
                  </div>
                  </div>

     <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">License No.</label>
                  <div class="col-sm-10">
                  <input name="license_no" type="text" class="form-control" value="<?php echo $fetch_inst->license_no; ?>">
                  </div>
                  </div>

     <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Location</label>
                  <div class="col-sm-10">
                  <input name="addrs" type="text" id="autocomplete1" class="form-control" onFocus="geolocate()" value="<?php echo $fetch_inst->location; ?>" required>
                  </div>
                  </div>
          
    <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">State</label>
                  <div class="col-sm-10">
                  <input name="state" type="text" id="autocomplete2" onFocus="geolocate()" class="form-control" value="<?php echo $fetch_inst->state; ?>" required>
                  </div>
                  </div>
          
    <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Country</label>
                  <div class="col-sm-10">
                  <select name="country" class="form-control select2" required>
										<option value="<?php echo $fetch_inst->country; ?>" selected='selected'><?php echo $fetch_inst->country; ?></option>
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
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Official Contact</label>
                  <div class="col-sm-10">
                  <input name="official_phone" type="text" class="form-control" value="<?php echo $fetch_inst->official_phone; ?>" required>
                  </div>
                  </div>

    <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Official Email</label>
                  <div class="col-sm-10">
                  <input name="official_email" type="text" class="form-control" value="<?php echo $fetch_inst->official_email; ?>" required>
                  </div>
                  </div>
                  
    <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Username</label>
                  <div class="col-sm-10">
                  <input name="usernamei" type="text" class="form-control" value="<?php echo $fetch_iuser->username; ?>" placeholder="Username" required>
                  </div>
                  </div>

    <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Password</label>
                  <div class="col-sm-10">
                  <input name="passwordi" type="password" class="form-control" value="<?php echo base64_decode($fetch_iuser->password); ?>" placeholder="Password" required>
                  </div>
                  </div>
                  
<hr>
<div class="bg-orange">&nbsp;<b>File Attached</b></div>
<hr>

<div class="form-group">
<label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">Upload Documents</label>
    <div class="col-sm-10">
     <input name="uploaded_file[]" type="file" class="btn bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>" multiple/>
     <span style="color: <?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>;">YOU CAN UPLOAD <b>MULTIPLE DOCUMENTS AT THE SAME TIME</b></span>
<hr>
<?php
$i = 0;
$search_file = mysqli_query($link, "SELECT * FROM institution_legaldoc WHERE instid = '$insti_id'") or die ("Error: " . mysqli_error($link));
if(mysqli_num_rows($search_file) == 0){
  echo "<span style='color: orange;'>No file attached!!</span>";
}else{
  while($get_file = mysqli_fetch_array($search_file)){
      $i++;
?>
<a href="<?php echo $get_file['document']; ?>" target="_blank"><img src="<?php echo $fetchsys_config['file_baseurl']; ?>file_attached.png" width="64" height="64"> Attachment <?php echo $i; ?></a>
<?php
  }
}
?>
    </div>
</div>
<hr>

    <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Status</label>
                  <div class="col-sm-10">
        <select name="status" class="form-control select2" required>
                    <option value="<?php echo $fetch_inst->status; ?>" selected='selected'><?php echo $fetch_inst->status; ?></option>
                    <option value="Approved">Approve</option>
                    <option value="Disapproved">Disapprove</option>
                    <option value="Suspended">Suspended</option>
                    <option value="Updated">Update</option>
        </select>
    </div>
    </div>

    <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Frontend Reg.</label>
                  <div class="col-sm-10">
        <select name="frontend_reg" class="form-control select2" required>
                    <option value="<?php echo $fetch_inst->fontend_reg; ?>" selected='selected'><?php echo $fetch_inst->fontend_reg; ?></option>
                    <option value="Enable">Enable</option>
                    <option value="Disable">Disable</option>
        </select>
    </div>
    </div>
    
<?php
/**
$myinstid = $fetch_inst->institution_id;
$search_commission = mysqli_query($link, "SELECT * FROM company_commission_level WHERE companyid = '$myinstid'");
if(mysqli_num_rows($search_commission) == 1)
{
    $fetch_cm = mysqli_fetch_object($search_commission);
?>
    <div class="form-group">
        <label for="" class="col-sm-2 control-label" style="color:blue;">Commission Level</label>
        <div class="col-sm-10">
        <select name="c_level" class="form-control select2" required>
                    <option value="<?php echo $fetch_cm->referral_level; ?>" selected='selected'>Level <?php echo $fetch_cm->referral_level.' Percentage: '.$fetch_cm->percentage.'%'; ?></option>
                    <?php
                    $search_compensation = mysqli_query($link, "SELECT * FROM compensation_plan");
                    while($fetch_cmpe = mysqli_fetch_object($search_compensation))
                    {
                    ?>
                        <option value="<?php echo $fetch_cmpe->plan_level; ?>">Level <?php echo $fetch_cmpe->plan_level.' Percentage: '.$fetch_cmpe->percentage.'%'; ?></option>
                    <?php
                    }
                    ?>
        </select>
        </div>
    </div>
<?php
}
else{
    $search_compensation = mysqli_query($link, "SELECT * FROM compensation_plan");
?>
    <div class="form-group">
        <label for="" class="col-sm-2 control-label" style="color:blue;">Commission Level</label>
        <div class="col-sm-10">
        <select name="c_level" class="form-control select2">
            <option value="" selected>Set Referral Commission Level</option>
            <?php
            while($fetch_cmpe = mysqli_fetch_object($search_compensation))
            {
            ?>
                <option value="<?php echo $fetch_cmpe->plan_level; ?>">Level <?php echo $fetch_cmpe->plan_level.' Percentage: '.$fetch_cmpe->percentage.'%'; ?></option>
            <?php
            }
            ?>
        </select>
        </div>
    </div>
<?php
}
*/
?>

       </div>
       
        <div align="right">
              <div class="box-footer">
                        <button name="update" type="submit" class="btn bg-blue btn-flat"><i class="fa fa-edit">&nbsp;Update</i></button>

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
           <label for="" class="col-sm-4 control-label" style="color: blue;">Import Bulk Update:</label>
  <div class="col-sm-8">
        <span class="fa fa-cloud-upload"></span>
           <span class="ks-text">Choose file</span>
           <input type="file" name="file" accept=".csv" required>
  </div>
  </div>

   <hr>
  <p style="color:red"><b style="color:blue;">NOTE:</b><br>
    <span style="color:blue;">(1)</span> <i>Kindly download the <a href="../sample/update_bulkinstitution.csv"> <b class="btn bg-blue"><i class="fa fa-download"></i> sample file</b></a> to upload the updated details once.</i></p>
    <span style="color:blue;">(2)</span> <i style="color:red;">Also, take note of the <span style="color: blue">institution_logo</span> which is to be written in this format: <b style="color:blue;">img/image_name.image_format</b>. The image format can be <b style="color: blue;">.png, .jpg, etc.</b></i></p>
                        
  <hr>
  
    <div align="right">
       <div class="box-footer">
       <button class="btn bg-blue ks-btn-file" type="submit" name="Import"><span class="fa fa-cloud-upload"></span> Import Data</button> 
       </div>
    </div>  

</div>  

<?php
if(isset($_POST["Import"])){

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
            //It wiil UPDATE row on our INST. table from the csv file`
             $sql = "UPDATE institution_data SET institution_logo = '$emapData[1]', institution_name = '$emapData[2]', license_no = '$emapData[3]', location = '$emapData[4]', state = '$emapData[5]', country = '$emapData[6]', official_phone = '$emapData[7]' WHERE institution_id = '$emapData[0]'";
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
            alert(\"Data Updated successfully.\");
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
           <label for="" class="col-sm-4 control-label" style="color: blue;">Import Bulk 
           logo Here if needed:</label>
  <div class="col-sm-8">
        <span class="fa fa-cloud-upload"></span>
           <span class="ks-text">Choose file</span>
           <input type="file" name="uploaded_file[]" multiple required>
  </div>
  </div>

   <hr>
  <p style="color:red"><b style="color:blue;">NOTICE:</b><br>
    <span style="color:blue;">(1)</span> <i style="color:red;">Upload the bulk image of just updated Institution if needed, with the uses of your <span style="color: blue;">Control Key.</span></i></p>
  <hr>
  
    <div align="right">
       <div class="box-footer">
       <button class="btn bg-blue ks-btn-file" type="submit" name="Import_Image"><span class="fa fa-cloud-upload"></span> Import Image</button> 
       </div>
    </div>  

</div>  

<div class="scrollable">
<?php
if(isset($_POST["Import_Image"])){

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