<div class="box">
        
		 <div class="box-body">
			<div class="panel panel-success">
            <div class="panel-heading bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">
            <h3 class="panel-title"><i class="fa fa-user"></i> <?php echo ($istaff_manager === "On") ? "New Staff" : "New Sub-Agent"; ?></h3>
            </div>
             <div class="box-body">
<?php
//Function to check string starting with given substring 
function startsWith ($string, $startString) 
{ 
    $len = strlen($startString); 
    return (substr($string, 0, $len) === $startString); 
}
?>
               
			 <form class="form-horizontal" method="post" enctype="multipart/form-data">
<?php
if(isset($_POST['emp']))
{
    $lname =  mysqli_real_escape_string($link, $_POST['lname']);
    $fname =  mysqli_real_escape_string($link, $_POST['fname']);
    $mname =  mysqli_real_escape_string($link, $_POST['mname']);
    $fullname =  $lname.' '.$fname.' '.$mname;
    $email =  mysqli_real_escape_string($link, $_POST['email']);
    $phone =  mysqli_real_escape_string($link, $_POST['phone']);
    $addr1 =  mysqli_real_escape_string($link, $_POST['addr1']);
    $dept =  ($idept_settings === "On") ? mysqli_real_escape_string($link, $_POST['dept']) : "";
    $city =  mysqli_real_escape_string($link, $_POST['city']);
    $state =  mysqli_real_escape_string($link, $_POST['state']);
    $gender =  mysqli_real_escape_string($link, $_POST['gender']);
    $country =  mysqli_real_escape_string($link, $_POST['country']);
    $username =  mysqli_real_escape_string($link, $_POST['username']);
    $password = substr((uniqid(rand(),1)),3,6);
    $cust_reg =  mysqli_real_escape_string($link, $_POST['cust_reg']);
    $deposit =  mysqli_real_escape_string($link, $_POST['deposit']);
    $withdrawal =  mysqli_real_escape_string($link, $_POST['withdrawal']);
    $allow_auth = mysqli_real_escape_string($link, $_POST['allow_auth']);

    $final_role = mysqli_real_escape_string($link, $_POST['urole']);
    
    $target_dir = "../img/";
    $target_file = $target_dir.basename($_FILES["image"]["name"]);
    $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
    $check = getimagesize($_FILES["image"]["tmp_name"]);
    $branch =  ($ibranch_manager === "On") ? mysqli_real_escape_string($link, $_POST['branch']) : "";
    
    $encrypt = base64_encode($password);
    $id = "MEM-".rand(10000000,340000000);
    $type = ((startsWith($institution_id, "AGT")) ? "AG" : ((startsWith($institution_id, "INST")) ? "INS" : "MER"));
    
    $verify_staff = mysqli_query($link, "SELECT * FROM user WHERE created_by = '$institution_id'");
    $fetch_staff = mysqli_num_rows($verify_staff);
    
    $search_memberset = mysqli_query($link, "SELECT * FROM member_settings WHERE companyid = '$institution_id'");
    $fetch_memberset = mysqli_fetch_array($search_memberset);
    $sysabb = $fetch_memberset['sender_id'];
    $customDomain = ($iemailConfigStatus == "Activated") ? $ifetch_emailConfig['product_url'] : "https://esusu.app/$sysabb";
    $lastussdcode_prefix = $fetch_memberset['dedicated_ussd_prefix'];
    
    $transactionPin = substr((uniqid(rand(),1)),3,4);

    $posPIN = substr((uniqid(rand(),1)),3,6);
    	
    $sms = "$sysabb>>>Dear $lname! Account Created Successfully. Username: $username, Password: $password. Transaction Pin is: $transactionPin, Login to your account here: $customDomain";
    
    $max_per_page = 153;
    $sms_length = strlen($sms);
    $calc_length = ceil($sms_length / $max_per_page);
    $sms_rate = $fetchsys_config['fax'];
    $refid = "EA-empRegAlert-".rand(1000000,9999999);

    $maintenance_row = mysqli_num_rows($isearch_maintenance_model);
    $debitWAllet = ($getSMS_ProviderNum == 1 || ($maintenance_row == 1 && $billing_type == "PAYGException")) ? "No" : "Yes"; 
    $sms_charges = $calc_length * $sms_rate;
    $mybalance = $iwallet_balance - $sms_charges;

    if($fetch_staff == $istaff_limit)
    {
    	echo "<script>alert('Sorry, You can not add more than the limit assigned to you. Kindly Contact us for higher plan!'); </script>";
    }
    else{
        $protocol = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
        $url = $protocol.$_SERVER['HTTP_HOST']."/?id=".$id;
        $ide = time();
        $shorturl = base_convert($ide,20,36);
        
        $shortenedurl = $protocol . $_SERVER['HTTP_HOST'] . '/?a_key=' . $shorturl;
        $shortenedurl1 = $protocol . $_SERVER['HTTP_HOST'] . '/?d_key=' . $shorturl;
        
        $query = mysqli_query($link, "SELECT * FROM systemset") or die (mysqli_error($link));
        $r = mysqli_fetch_object($query);
        $sys_email = $r->email;
        
        $sourcepath = $_FILES["image"]["tmp_name"];
        $targetpath = "../img/" . $_FILES["image"]["name"];
        move_uploaded_file($sourcepath,$targetpath);
        
        $location = $_FILES['image']['name'];
        $datetime = date("Y-m-d h:i:s"); 
        
        mysqli_query($link, "INSERT INTO activate_member VALUES(null,'$url','$shorturl','No','$id')") or die ("Error: " . mysqli_error($link));
        mysqli_query($link, "INSERT INTO user VALUES(null,'$fname','$lname','$mname','$email','$phone','$addr1','','$city','$state','','$country','Not-Activated','$username','$encrypt','$id','$location','$final_role','$branch','Registered','$institution_id','$type','$transactionPin','0.0','$dept','0.0','','','','$lastussdcode_prefix','$gender','','$cust_reg','$deposit','$withdrawal','$datetime','','','','','Pending','agent','$iuid','NULL','No','NULL','$allow_auth','$posPIN','0.0')") or die ("Error:" . mysqli_error($link));
        
        ($billing_type == "PAYGException" ? "" : ($debitWAllet == "No" ? $sendSMS->instGeneralAlert($ozeki_password, $ozeki_url, $sysabb, $phone, $sms, $institution_id, $refid, $sms_charges, $iuid, $mybalance, $debitWallet) : ($allow_auth == "Yes" && $debitWAllet == "Yes" && $sms_rate <= $iwallet_balance ? $sendSMS->instGeneralAlert($ozeki_password, $ozeki_url, $sysabb, $phone, $sms, $institution_id, $refid, $sms_charges, $iuid, $mybalance, $debitWallet) : "")));
        $sendSMS->staffRegEmailNotifier($email, $sysabb, $fullname, $shortenedurl, $username, $password, $shortenedurl1, $iemailConfigStatus, $ifetch_emailConfig);
        
        echo "<script>alert('New Employee Registered Successfully!'); </script>";
    }
}
?> 
             <div class="box-body">
				
			<div class="form-group">
            <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Your Image</label>
			<div class="col-sm-10">
  		  			 <input type='file' name="image" onChange="readURL(this);" />
       				 <img id="blah"  src="<?php echo $fetchsys_config['file_baseurl']; ?>user2.png" alt="Image Here" height="100" width="100"/>
			</div>
			</div>
                  
            <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">First Name</label>
                  <div class="col-sm-10">
                  <input name="fname" type="text" class="form-control" placeholder="First Name" required>
                  </div>
                  </div>

                  <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Middle Name</label>
                  <div class="col-sm-10">
                  <input name="mname" type="text" class="form-control" placeholder="Middle Name">
                  </div>
                  </div>

                  <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Last Name</label>
                  <div class="col-sm-10">
                  <input name="lname" type="text" class="form-control" placeholder="Last Name" required>
                  </div>
                  </div>				  
				  
		<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Email</label>
                  <div class="col-sm-10">
                  <input type="email" name="email" type="text" id="vemail" onkeyup="veryEmail();" class="form-control" placeholder="Email" required>
                  <div id="myvemail"></div>
                  </div>
                  </div>


        <div class="form-group">
                      <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Mobile</label>
                      <div class="col-sm-4">
                      <input name="phone" type="tel" class="form-control" id="phone" onkeyup="veryPhone();" required>
                      <div id="myvphone"></div>
                      </div><?php echo (isMobileDevice()) ? "<br>" : ""; ?>
                      <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Gender</label>
                      <div class="col-sm-4">
                            <select name="gender" class="form-control" required>
                                        <option value="" selected='selected'>Select Gender&hellip;</option>
                                        <option value="Male">Male</option>
                                        <option value="Female">Female</option>
                            </select>
                        </div>  
					</div>
				  
				  
		 <div class="form-group">
                  	<label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Address</label>
                  	<div class="col-sm-10">
					<textarea name="addr1"  class="form-control" rows="2" cols="80" required></textarea>
           			 </div>
          </div>
			
			
			<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">City</label>
                  <div class="col-sm-10">
                  <input name="city" type="text" id="autocomplete1" onFocus="geolocate()" class="form-control" placeholder="City" required>
                  </div>
                  </div>
				  
		<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">State</label>
                  <div class="col-sm-10">
                  <input name="state" type="text" id="autocomplete2" onFocus="geolocate()" class="form-control" placeholder="State" required>
                  </div>
                  </div>
				 
				  
		<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Country</label>
                  <div class="col-sm-10">
				 <input name="country" type="text" id="autocomplete3" onFocus="geolocate()" class="form-control" placeholder="Country" required>  
									 </div>
                 					 </div>
                 					 
<?php
if($ibranch_manager === "On")
{
?>

			<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Branch</label>
                  <div class="col-sm-10">
				<select name="branch"  class="form-control select2">
										<option value="">Head Office</option>
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
                 					 
<?php
}
else{
    echo "";
}
?>

    <div class="form-group">
			    <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Allow Authorization</label>
				<div class="col-sm-10">
					<select name="allow_auth" class="form-control select2" required>
						<option value='' selected='selected'>Select Settings</option>
						<option value='Yes'>Yes</option>
						<option value='No'>No</option>
					</select>                 
			 	</div>
			</div>

			
<hr>	
<div class="bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>">&nbsp;SUB-AGENT LOGIN INFORMATION</div>
<hr>	
					
					 <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Username</label>
                  <div class="col-sm-10">
                  <input name="username" type="text" class="form-control" id="vusername" onkeyup="veryUsername();" placeholder="Username" required>
                  <div id="myusername"></div>
                  </div>
                  </div>
				  
                  
<?php
if($idept_settings == "Off"){
    
    echo "";
    
}
else{
?>
                  
                  <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Department</label>
                  <div class="col-sm-10">
				    <select name="dept"  class="form-control select2">
						<option value="" selected>Select Department</option>
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
						<option value="" selected>Select Settings</option>
                        <option value="Allow">Allow</option>
                        <option value="Disallow">Disallow</option>
					</select>                 
				 </div>
                </div>

                <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Deposit</label>
                  <div class="col-sm-10">
				    <select name="deposit"  class="form-control select2" required>
						<option value="" selected>Select Settings</option>
                        <option value="Allow">Allow</option>
                        <option value="Disallow">Disallow</option>
					</select>                 
				 </div>
                </div>

                <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Withdrawal</label>
                  <div class="col-sm-10">
				    <select name="withdrawal"  class="form-control select2" required>
						<option value="" selected>Select Settings</option>
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
                    ?>
                    <tr>
                      <td>
                        <?php echo substr(ucfirst(str_replace('_', ' ', $fetch_mproperties['urole'])), 6); ?>
                      </td>
                      <td>
                        <input name="urole" type="radio" value="<?php echo $fetch_mproperties['urole']; ?>">
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