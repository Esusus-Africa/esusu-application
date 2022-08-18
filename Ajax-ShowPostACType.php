<?php
//error_reporting(0);
include ("config/connect.php");
$PostType = $_GET['PostType'];
//$Referral = $_GET['PostType1'];
$mysendid = $_GET['id'];

$search_referralid = mysqli_query($link, "SELECT * FROM member_settings WHERE sender_id = '$mysendid'") or die ("Error: " . mysqli_error($link));
$fetch_referralid = mysqli_fetch_array($search_referralid);
$Referral = $fetch_referralid['companyid'];
$ussdcode = $fetch_referralid['dedicated_ussd_prefix'];

$ip = $_SERVER['REMOTE_ADDR']; // This will contain the ip of the request
$dataArray = json_decode(file_get_contents("http://www.geoplugin.net/json.gp?ip=".$ip), true);

$origincountry = $dataArray["geoplugin_countryName"];
$mycurrencytype = $dataArray["geoplugin_currencyCode"];
$origincity = $dataArray["geoplugin_city"];
$originprovince = $dataArray["geoplugin_region"];

if($PostType == "Aggregator")
{
?>
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
                
                <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Date of Birth</label>
                  <div class="col-sm-7">
                  <input name="dob" type="date" class="form-control" placeholder="Date Format: mm/dd/yyyy" required>
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
                  <p style="color: orange; font-size: 16px;">NOTE: <i>Please make sure you enter the phone number in this format: <b style="color: blue;">COUNTRY CODE + MOBILE NUMBER e.g +2348111111111, +12226373738</b>.</i></p>
                  <div id="myvphone"></div>
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


				 <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Password</label>
                  <div class="col-sm-7">
                  <input name="password" type="password" class="form-control" placeholder="Your Password" id="password-field" required>
                  <span toggle="#password-field" class="fa fa-fw fa-eye field-icon toggle-password"></span>
                  </div>
                  <label for="" class="col-sm-2 control-label"></label>
                  </div>
                  
                  <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;"></label>
                  <div class="col-sm-7">
                  <span style="color: <?php echo ($mysendid != "") ? 'black' : 'orange'; ?>;"><input class="input-checkbox100" id="ckb1" type="checkbox" name="acpt_policy" required> &nbsp; Accept our <a href="#">Terms and Conditions</a>.</span>
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
                  
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.2.2/jquery.min.js"></script>
<script>
  $(".toggle-password").click(function() {

  $(this).toggleClass("fa-eye fa-eye-slash");
  var input = $($(this).attr("toggle"));
  if (input.attr("type") == "password") {
    input.attr("type", "text");
  } else {
    input.attr("type", "password");
  }
});
</script>
		
			
<?php
}
elseif($PostType == "Corporate"){
?>
                
            <?php
            if($ussdcode == "0"){
            ?>
                
                <input name="account_type" type="hidden" class="form-control" value="agent1">
                
            <?php
            }
            else{
            ?>
                
                <div class="form-group">
                    <label for="" class="col-sm-3 control-label" style="color:<?php echo ($ussdcode == "0") ? 'black' : 'blue'; ?>;">Logo</label>
                  <div class="col-sm-7">
                           <input type='file' name="image" class="btn bg-<?php echo ($ussdcode == "0") ? 'black' : 'orange'; ?>" onChange="readURL(this);" required>
                           <img id="blah"  src="avtar/user2.png" alt="Logo Here" height="80" width="80"/>
                  </div>
                  <label for="" class="col-sm-2 control-label"></label>
                </div>
                
                
                <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Account Type</label>
                  <div class="col-sm-7">
                    <select name="account_type" class="form-control" required>
                                <option value="" selected='selected'>Select Type&hellip;</option>
                                <option value="agent">Agent</option>
                                <option value="institution">Cooperative/Institution</option>
                    </select>
                </div>
                <label for="" class="col-sm-2 control-label"></label>
                </div>
            
            <?php } ?>
            
            
            <?php
            if($ussdcode == "0"){
            ?>
                
                <input name="acct_cat" type="hidden" class="form-control" value="Agency Banking">
                
            <?php
            }
            else{
            ?>
            
                <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Account Category</label>
                  <div class="col-sm-7">
                    <select name="acct_cat" class="form-control" required>
                                <option value="" selected='selected'>Select Category&hellip;</option>
                                <option value="Independent Thrift Collector">Independent Thrift Collector</option>
                                <option value="Mobile Money Agent">Mobile Money Agent</option>
                                <option value="Super Agent">Super Agent</option>
                                <option value="Microfinance Institution">Microfinance Institution</option>
                                <option value="Cooperative Society">Cooperative Society</option>
                                <option value="NGO">NGO</option>
                                <option value="Microfinance Bank">Microfinance Bank</option>
                    </select>
                </div>
                <label for="" class="col-sm-2 control-label"></label>
                </div>
                
            <?php } ?>

            <div class="alert bg-orange">Contact Person</div>
                
                <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:<?php echo ($ussdcode == "0") ? 'black' : 'blue'; ?>;">First Name</label>
                  <div class="col-sm-7">
                  <input name="fname" type="text" class="form-control" placeholder="Enter First Name" required>
                  </div>
                  <label for="" class="col-sm-2 control-label"></label>
                  </div>

                  <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:<?php echo ($ussdcode == "0") ? 'black' : 'blue'; ?>;">Last Name</label>
                  <div class="col-sm-7">
                  <input name="lname" type="text" class="form-control" placeholder="Enter Last Name" required>
                  </div>
                  <label for="" class="col-sm-2 control-label"></label>
                  </div>

                  <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:<?php echo ($ussdcode == "0") ? 'black' : 'blue'; ?>;">Middle Name</label>
                  <div class="col-sm-7">
                  <input name="mname" type="text" class="form-control" placeholder="Enter Middle Name">
                  </div>
                  <label for="" class="col-sm-2 control-label"></label>
                  </div>
                
                <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:<?php echo ($ussdcode == "0") ? 'black' : 'blue'; ?>;">Business Name</label>
                  <div class="col-sm-7">
                  <input name="bname" type="text" class="form-control" placeholder="Your Business Name" required>
                  </div>
                  <label for="" class="col-sm-2 control-label"></label>
                  </div>

                  <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:<?php echo ($ussdcode == "0") ? 'black' : 'blue'; ?>;">License Number (Optional)</label>
                  <div class="col-sm-7">
                  <input name="license_no" type="text" class="form-control" placeholder="Your License Number / CAC Number">
                  </div>
                  <label for="" class="col-sm-2 control-label"></label>
                  </div>
                  
                  <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:<?php echo ($ussdcode == "0") ? 'black' : 'blue'; ?>;">Official Email Address</label>
                  <div class="col-sm-7">
                  <input name="email" type="email" class="form-control" id="vemail" onkeyup="veryEmail();" placeholder="Your Email Address" required>
                  <div id="myvemail"></div>
                  </div>
                  <label for="" class="col-sm-2 control-label"></label>
                  </div>
                  
                  <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:<?php echo ($ussdcode == "0") ? 'black' : 'blue'; ?>;">Official Phone Number</label>
                  <div class="col-sm-7">
                  <input name="phone" type="text" class="form-control" id="vphone" onkeyup="veryPhone();" placeholder="+2348111111111" required>
                  <p style="color: <?php echo ($ussdcode == "0") ? 'black' : 'orange'; ?>; font-size: 16px;">International Format: <b style="color: <?php echo ($ussdcode == "0") ? 'black' : 'orange'; ?>;"> e.g. +2348111111111, +12226373738</b>.</p>
                  <div id="myvphone"></div>
                  </div>
                  <label for="" class="col-sm-2 control-label"></label>
                  </div>

                  <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:<?php echo ($ussdcode == "0") ? 'black' : 'blue'; ?>;">Location</label>
                  <div class="col-sm-7">
                  <input name="addrs" type="text" class="form-control" id="autocomplete1" onFocus="geolocate()" placeholder="Location" required>
                  </div>
                  <label for="" class="col-sm-2 control-label"></label>
                  </div>
                
                
            <?php
            if($ussdcode == "0"){
            ?>
                
                <input name="my_senderid" type="hidden" class="form-control" value="<?php echo $mysendid; ?>">

            <?php
            }
            else{
            ?>
                  <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Sender ID</label>
                  <div class="col-sm-7">
                  <input name="my_senderid" type="text" class="form-control" id="vsid" onkeyup="verySID();" placeholder="SMS Alert Sender ID" maxlength="11" required>
                  <div id="myvsid"></div>
                  </div>
                  <label for="" class="col-sm-2 control-label"></label>
                  </div>
                  
            <?php } ?>
            
            
            <?php
            if($ussdcode == "0"){
            ?>
                
                <input name="currency_type" type="hidden" class="form-control" value="<?php echo $mycurrencytype; ?>">
                <input name="origin_country" type="hidden" class="form-control" value="<?php echo $origincountry; ?>">
                <input name="origin_city" type="hidden" class="form-control" value="<?php echo $origincity; ?>">
                <input name="origin_province" type="hidden" class="form-control" value="<?php echo $originprovince; ?>">

            <?php
            }
            else{
            ?>
            
                <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:<?php echo ($ussdcode == "0") ? 'black' : 'blue'; ?>;">Currency</label>
                  <div class="col-sm-7">
                    <select name="currency_type" class="form-control" required>
                                <option value="" selected='selected'>Select Currency Type&hellip;</option>
                                <option value="NGN">NGN</option>
                                <option value="USD">USD</option>
                                <option value="GBP">GBP</option>
                                <option value="GHS">GHS</option>
                                <option value="KES">KES</option>
                    </select>
                  </div>
                  <label for="" class="col-sm-2 control-label"></label>
                  </div>
                  
            <?php } ?>
                  
                  <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:<?php echo ($ussdcode == "0") ? 'black' : 'blue'; ?>;">Username</label>
                  <div class="col-sm-7">
                  <input name="username" type="text" class="form-control" id="vusername" onkeyup="veryUsername();" placeholder="Your Preferred Username" required>
                  <div id="myusername"></div>
                  </div>
                  <label for="" class="col-sm-2 control-label"></label>
                  </div>


		 <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:<?php echo ($ussdcode == "0") ? 'black' : 'blue'; ?>;">Password</label>
                  <div class="col-sm-7">
                  <input name="password" type="password" class="form-control" placeholder="Your Preferred Password" id="password-field" required>
                  <span toggle="#password-field" class="fa fa-fw fa-eye field-icon toggle-password"></span>
                  </div>
                  <label for="" class="col-sm-2 control-label"></label>
                  </div>
                  
                  
                  <!--<div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Referral ID (Optional)</label>
                  <div class="col-sm-9">
                  <input name="referral" type="text" class="form-control" placeholder="Referral ID if any" <?php echo ($arow == 1) ? 'value="'.$Referral.'" readonly' : ''; ?>>
                  </div>
                  </div>-->
                  
                
                  <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:<?php echo ($ussdcode == "0") ? 'black' : 'blue'; ?>;"></label>
                  <div class="col-sm-7">
                  <span style="color: <?php echo ($ussdcode == "0") ? 'black' : 'orange'; ?>;"><input class="input-checkbox100" id="ckb1" type="checkbox" name="acpt_policy" required> &nbsp; Accept our <a href="#">Terms and Conditions</a>.</span>
                  </div>
                  <label for="" class="col-sm-2 control-label"></label>
                  </div>
                  
                  <div class="form-group" align="right">
                  <label for="" class="col-sm-3 control-label" style="color:<?php echo ($ussdcode == "0") ? 'black' : 'blue'; ?>;"></label>
                  <div class="col-sm-7">
                  <button name="aregister" type="submit" class="btn bg-<?php echo ($ussdcode == "0") ? 'black' : 'blue'; ?> btn-flat"><i class="fa fa-save">&nbsp;Register</i></button>
                  </div>
                  <label for="" class="col-sm-2 control-label"></label>
                  </div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.2.2/jquery.min.js"></script>
<script>
  $(".toggle-password").click(function() {

  $(this).toggleClass("fa-eye fa-eye-slash");
  var input = $($(this).attr("toggle"));
  if (input.attr("type") == "password") {
    input.attr("type", "text");
  } else {
    input.attr("type", "password");
  }
});
</script>

<?php 
}
elseif($PostType == "Institution")
{
	$institutionID = 'INST-'.rand(10000,99999);
	$DirectorateID = 'DIR-'.rand(10000,99999);
	$search_allinst = mysqli_query($link, "SELECT * FROM institution_data WHERE institution_id = '$Referral'");
	$irow = mysqli_num_rows($search_allinst);
?>

<input name="institutionID" type="hidden" class="form-control" value="<?php echo $institutionID; ?>" id="HideValueFrank"/>

<input name="DirectorateID" type="hidden" class="form-control" value="<?php echo $DirectorateID; ?>" id="HideValueFrank"/>

				
				<div class="form-group">
                    <label for="" class="col-sm-3 control-label" style="color:blue;">Logo</label>
                  <div class="col-sm-7">
                           <input type='file' name="image" class="btn bg-orange" onChange="readURL(this);" required>
                           <img id="blah"  src="avtar/user2.png" alt="Logo Here" height="100" width="100"/>
                  </div>
                  <label for="" class="col-sm-2 control-label"></label>
                </div>
                
                <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Institution Type</label>
                  <div class="col-sm-7">
                    <select name="itype" class="form-control" required>
                                <option value="" selected='selected'>Select Type&hellip;</option>
                                <option value="Microfinance Institution">Microfinance Institution</option>
                                <option value="Cooperative Society">Cooperative Society</option>
                    </select>
                </div>
                <label for="" class="col-sm-2 control-label"></label>
                </div>
                
                <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Company Name</label>
                  <div class="col-sm-7">
                  <input name="iname" type="text" class="form-control" placeholder="Applicant Company Name" required>
                  </div>
                  <label for="" class="col-sm-2 control-label"></label>
                  </div>
                  
                <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">License Number</label>
                  <div class="col-sm-7">
                  <input name="license_no" type="text" class="form-control" placeholder="Your License Number" required>
                  </div>
                  <label for="" class="col-sm-2 control-label"></label>
                  </div>
                  
                <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Location</label>
                  <div class="col-sm-7">
                  <input name="addrs" type="text" class="form-control" id="autocomplete1" onFocus="geolocate()" placeholder="Location" required>
                  </div>
                  <label for="" class="col-sm-2 control-label"></label>
                  </div>
                  
                
                <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Official Email</label>
                  <div class="col-sm-7">
                  <input name="official_email" type="email" class="form-control" id="vemail" onkeyup="veryEmail();" placeholder="Your Official Email" required>
                  <div id="myvemail"></div>
                  </div>
                  <label for="" class="col-sm-2 control-label"></label>
                  </div>
                  
                  <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Official Phone</label>
                  <div class="col-sm-7">
                  <input name="official_phone" type="text" class="form-control" id="vphone" onkeyup="veryPhone();" placeholder="+2348111111111" required>
                  <p style="color: orange; font-size: 16px;">NOTE: <i>Please make sure you enter the phone number in this format: <b style="color: blue;">COUNTRY CODE + MOBILE NUMBER e.g +2348111111111, +12226373738</b>.</i></p>
                  <div id="myvphone"></div>
                  </div>
                  <label for="" class="col-sm-2 control-label"></label>
                  </div>
                  
                  
                <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Contact Person</label>
                  <div class="col-sm-7">
                  <input name="contact_person" type="text" class="form-control" placeholder="Enter Contact Person's Name" required>
                  </div>
                  <label for="" class="col-sm-2 control-label"></label>
                  </div>
                  
                <!--
                <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Username</label>
                  <div class="col-sm-7">
                  <input name="username" type="text" class="form-control" id="vusername" onkeyup="veryUsername();" placeholder="Your Preferred Username" required>
                  <div id="myusername"></div>
                  </div>
                  <label for="" class="col-sm-2 control-label"></label>
                  </div>


		 <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Password</label>
                  <div class="col-sm-7">
                  <input name="pass" type="password" class="form-control" placeholder="Your Preferred Password" required>
                  </div>
                  <label for="" class="col-sm-2 control-label"></label>
                  </div>
                  -->
   
				
		<div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;"></label>
                  <div class="col-sm-7">
                  <span style="color: orange;"><input class="input-checkbox100" id="ckb1" type="checkbox" name="acpt_policy" required> &nbsp; Accept our <a href="#">Terms and Conditions</a>.</span>
                  </div>
                  <label for="" class="col-sm-2 control-label"></label>
                  </div>
                  
        <div class="form-group" align="right">
                  <label for="" class="col-sm-3 control-label" style="color:blue;"></label>
                  <div class="col-sm-7">
                  <button name="iregister" type="submit" class="btn bg-blue btn-flat"><i class="fa fa-save">&nbsp;Register</i></button>
                  </div>
                  <label for="" class="col-sm-2 control-label"></label>
                  </div>


<?php
}
elseif($PostType == "Cooperative")
{
	$coopid = 'COOP-'.rand(10000,99999);
	$coopmemberID = 'CPMEM'.rand(10000,99999);
	$search_allcoop = mysqli_query($link, "SELECT * FROM cooperative WHERE coopid = '$Referral'");
	$icrow = mysqli_num_rows($search_allcoop);
?>

				<input name="coopmemberID" type="hidden" class="form-control" value="<?php echo $coopmemberID; ?>" id="HideValueFrank">

				<input name="coopid" type="hidden" class="form-control" value="<?php echo $coopid; ?>" id="HideValueFrank"/>

				<div class="form-group">
                    <label for="" class="col-sm-3 control-label" style="color:blue;">Logo</label>
                  <div class="col-sm-7">
                           <input type='file' name="image" class="btn bg-orange" onChange="readURL(this);" required>
                           <img id="blah"  src="avtar/user2.png" alt="Logo Here" height="100" width="100"/>
                  </div>
                  <label for="" class="col-sm-2 control-label"></label>
                </div>
                
                <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Group Type</label>
                  <div class="col-sm-7">
                    <select name="ctype" class="form-control" required>
                                <option value="" selected='selected'>Select Group Type&hellip;</option>
                                <option value="Farmer">Farmer</option>
                                <option value="Civil Servant">Civil Servant</option>
                                <option value="Traders">Traders</option>
                                <option value="Others">Others</option>
                    </select>
                </div>
                <label for="" class="col-sm-2 control-label"></label>
                </div>

				<div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Group Name</label>
                  <div class="col-sm-7">
                  <input name="cname" type="text" class="form-control" placeholder="Group Name" required>
                  </div>
                  <label for="" class="col-sm-2 control-label"></label>
                  </div>
                  
                <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Reg. Number (Optional)</label>
                  <div class="col-sm-7">
                  <input name="regno" type="text" class="form-control" placeholder="Your Registration Number (Optional)" required>
                  </div>
                  <label for="" class="col-sm-2 control-label"></label>
                  </div>
                  
                <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Official Location</label>
                  <div class="col-sm-7">
                  <input name="location" type="text" class="form-control" id="autocomplete1" onFocus="geolocate()" placeholder="Location" required>
                  </div>
                  <label for="" class="col-sm-2 control-label"></label>
                  </div>
                  
                <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">State</label>
                  <div class="col-sm-7">
                  <input name="state" type="text" class="form-control" id="autocomplete2" onFocus="geolocate()" placeholder="Enter State of Origin" required>
                  </div>
                  <label for="" class="col-sm-2 control-label"></label>
                  </div>
                  
                <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Country</label>
                  <div class="col-sm-7">
                  <input name="country" type="text" class="form-control" id="autocomplete3" onFocus="geolocate()" placeholder="Enter Country" required>
                  </div>
                  <label for="" class="col-sm-2 control-label"></label>
                  </div>

                <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Official Email</label>
                  <div class="col-sm-7">
                  <input name="email" type="email" class="form-control" id="vemail" onkeyup="veryEmail();" placeholder="Your Official Email" required>
                  <div id="myvemail"></div>
                  </div>
                  <label for="" class="col-sm-2 control-label"></label>
                  </div>
                  
                <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Official Phone</label>
                  <div class="col-sm-7">
                  <input name="phone" type="text" class="form-control" id="vphone" onkeyup="veryPhone();" placeholder="+2348111111111" required>
                  <p style="color: orange; font-size: 16px;">NOTE: <i>Please make sure you enter the phone number in thise format: <b style="color: blue;">COUNTRY CODE + MOBILE NUMBER e.g +2348111111111, +12226373738</b>.</i></p>
                  <div id="myvphone"></div>
                  </div>
                  <label for="" class="col-sm-2 control-label"></label>
                  </div>
                  
                  
                <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Mobile Number (Optional)</label>
                  <div class="col-sm-7">
                  <input name="mobile" type="text" class="form-control" placeholder="+2348111111111" required>
                  <p style="color: orange; font-size: 16px;">NOTE: <i>Please make sure you enter the phone number in thise format: <b style="color: blue;">COUNTRY CODE + MOBILE NUMBER e.g +2348111111111, +12226373738</b>.</i></p>
                  </div>
                  <label for="" class="col-sm-2 control-label"></label>
                  </div>


                <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Contact Person</label>
                  <div class="col-sm-7">
                  <input name="contact_person" type="text" class="form-control" placeholder="Enter Contact Person's Name" required>
                  </div>
                  <label for="" class="col-sm-2 control-label"></label>
                  </div>

                
                <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Occupation</label>
                  <div class="col-sm-7">
                  <input name="occupation" type="text" class="form-control" placeholder="Enter Occupation" required>
                  </div>
                  <label for="" class="col-sm-2 control-label"></label>
                  </div>
                  
                <div class="form-group">
                       <label for="" class="col-sm-3 control-label" style="color: blue; font-size: 14px;">License Document (If ANY):</label>
                  <div class="col-sm-7">
                          <input type="file" name="document[]" class="btn bg-orange">
                          <span style="color:blue">Accepted file types</span> <span style="color: orange;"><b>jpg, gif, png, doc, docx, pdf</b></span>
                  </div>
                  <label for="" class="col-sm-2 control-label"></label>
                </div>
                
                <!--
                <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Username</label>
                  <div class="col-sm-7">
                  <input name="username" type="text" class="form-control" id="vusername" onkeyup="veryUsername();" placeholder="Your Preferred Username" required>
                  <div id="myusername"></div>
                  </div>
                  <label for="" class="col-sm-2 control-label"></label>
                  </div>


		 <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Password</label>
                  <div class="col-sm-7">
                  <input name="pass" type="password" class="form-control" placeholder="Your Preferred Password" required>
                  </div>
                  <label for="" class="col-sm-2 control-label"></label>
                  </div>
                  -->
				
                                
		<div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;"></label>
                  <div class="col-sm-7">
                  <span style="color: orange;"><input class="input-checkbox100" id="ckb1" type="checkbox" name="acpt_policy" required> &nbsp; Accept our <a href="#">Terms and Conditions</a>.</span>
                  </div>
                  </div>
                  
                <div class="form-group" align="right">
                  <label for="" class="col-sm-3 control-label" style="color:blue;"></label>
                  <div class="col-sm-7">
                  <button name="cregister" type="submit" class="btn bg-blue"><i class="fa fa-save">&nbsp;Submit</i></button>
                  </div>
                  </div>


<?php
}
elseif($PostType == "Cooperative Member")
{
	$coopmemberID = 'CPMEM'.rand(10000,99999);
?>

				<input name="coopmemberID" type="hidden" class="form-control" value="<?php echo $coopmemberID; ?>" id="HideValueFrank">

				<input name="mrole" type="hidden" class="form-control" value="member" id="HideValueFrank">

				<div class="wrap-input50">
					<input type='file' name="image" class="btn bg-orange" onChange="readURL(this);">
					<span class="label-input50"><i class="fa fa-asterisk"></i>&nbsp; Member Passport </span>
				</div>

				<div class="wrap-input100">
					<select class="input100" name="coopid"  class="form-control select2" required>
	                    <option value='' selected='selected'>--------------------</option>
	<?php
	$search = mysqli_query($link, "SELECT * FROM cooperatives WHERE status = 'Approved' AND fontend_reg = 'Enable' ORDER BY id");
	while($get_search = mysqli_fetch_array($search))
	{
	?>
	          			<option value="<?php echo $get_search['coopid']; ?>"><?php echo $get_search['coopname']; ?></option>
        <?php } ?>
        			</select>
					<span class="focus-input100"></span>
					<span class="label-input50"><i class="fa fa-asterisk"></i>&nbsp; Select Cooperative<span style="color: orange"><b></b></span></span>
				</div>		

				<div class="wrap-input100">
					 <input name="fname" type="text" class="form-control" placeholder="Full Name" required>
					<span class="focus-input100"></span>
					<span class="label-input50"><i class="fa fa-asterisk"></i>&nbsp;Full Name</span>
				</div>

				<div class="wrap-input100">
					 <input name="phone_no" type="text" class="form-control" placeholder="Phone Number" required>
					<span class="focus-input100"></span>
					<span class="label-input50"><i class="fa fa-asterisk"></i>&nbsp;Phone Number</span>
				</div>

				<div class="wrap-input100">
					 <input name="email" type="email" class="form-control" placeholder="Email Address" required>
					<span class="focus-input100"></span>
					<span class="label-input50"><i class="fa fa-asterisk"></i>&nbsp;Email Address</span>
				</div>

				<div class="wrap-input100">
					<input name="unumber" type="text" class="form-control" id="unumber" onkeydown="loaddata();" placeholder="Enter your BVN for verification (Optional)"><br>
					<span class="focus-input100"></span>
					<span class="label-input50"><i class="fa fa-file"></i>&nbsp;Verify Your BVN Here (Optional)</span>
				</div>
				<div id="bvn2"></div><br>

				<div class="wrap-input100">
					 <input name="addrs" type="text" id="autocomplete1" class="form-control" onFocus="geolocate()" placeholder="Your Home / Office Address Here" required/>
					<span class="focus-input100"></span>
					<span class="label-input50"><i class="fa fa-asterisk"></i>&nbsp;Address</span>
				</div>

				<div class="wrap-input100">
					<input type='file' name="document[]" class="btn bg-orange" multiple required/>
					<span class="label-input50"><i class="fa fa-asterisk"></i>&nbsp;</span>
				</div>
				<span style="color: orange"><b>You're require to upload your Valid ID Card and your Utility Bills for Address Verification Purpose.</b></span>

				<div class="wrap-input100">
					 <input name="occupation" type="text" class="form-control" placeholder="Your Occupation" required>
					<span class="focus-input100"></span>
					<span class="label-input50"><i class="fa fa-asterisk"></i>&nbsp;Occupation</span>
				</div>
                
                <div class="flex-sb-m w-full p-t-3 p-b-32">
					<div class="contact100-form-checkbox">
							<input class="input-checkbox100" id="ckb1" type="checkbox" name="acpt_policy" required>
							<label class="label-checkbox100" for="ckb1">
								Accept our <a href="#">Terms and Conditions</a>.
							</label>
						</div>
					</div>

				<div align="right">
		              <div class="box-footer">
		                <button name="cmregister" type="submit" class="btn bg-blue btn-flat"><i class="fa fa-save">&nbsp;Register</i></button>
		              </div>
				</div>


<?php
}
?>



<script>
     $('#register_under').change(function(){
         var PostType=$('#register_under').val();
         $.ajax({url:"Ajax-ShowRegUnder.php?PostType="+PostType,cache:false,success:function(result){
             $('#ShowValueFrank').html(result);
         }});
     });
 	</script>	