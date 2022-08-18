<div class="box">
        
		 <div class="box-body">
			<div class="panel panel-success">
            <div class="panel-heading bg-blue">
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
    $dept =  mysqli_real_escape_string($link, $_POST['dept']);
    $city =  mysqli_real_escape_string($link, $_POST['city']);
    $state =  mysqli_real_escape_string($link, $_POST['state']);
    $gender =  mysqli_real_escape_string($link, $_POST['gender']);
    $country =  mysqli_real_escape_string($link, $_POST['country']);
    $username =  mysqli_real_escape_string($link, $_POST['username']);
    $password = substr((uniqid(rand(),1)),4,8);
    $allow_auth = mysqli_real_escape_string($link, $_POST['allow_auth']);
    
    $final_role = mysqli_real_escape_string($link, $_POST['urole']);
    
    $target_dir = "../img/";
    $target_file = $target_dir.basename($_FILES["image"]["name"]);
    $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
    $check = getimagesize($_FILES["image"]["tmp_name"]);
    $branch =  mysqli_real_escape_string($link, $_POST['branch']);
    
    $encrypt = base64_encode($password);
    $id = "MEM-".rand(10000000,340000000);

    $verify_staff = mysqli_query($link, "SELECT * FROM user WHERE created_by = '$institution_id'");
    $fetch_staff = mysqli_num_rows($verify_staff);
    
    $sysabb = "ESUSUAFRICA";

    $transactionPin = substr((uniqid(rand(),1)),3,4);
    
    $posPIN = substr((uniqid(rand(),1)),3,6);
    	
    $sms = "$sysabb>>>Dear $lname! Account Created Successfully. Username: $username, Password: $password. Transaction Pin is: $transactionPin, Login to your account here: https://esusu.app/";
    
    $protocol = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
    $url = $protocol.$_SERVER['HTTP_HOST']."/?id=".$id;
    $ide = time();
    $shorturl = base_convert($ide,20,36);
          
    $shortenedurl = $protocol . $_SERVER['HTTP_HOST'] . '/?a_key=' . $shorturl;
    $shortenedurl1 = $protocol . $_SERVER['HTTP_HOST'] . '/?d_key=' . $shorturl;
        
    $query = mysqli_query($link, "SELECT * FROM systemset") or die (mysqli_error($link));
    $r = mysqli_fetch_object($query);
    $sys_email = $r->email;

    $sms_refid = uniqid("EA-smsCharges-").time();
    $max_per_page = 153;
    $sms_length = strlen($sms);
    $calc_length = ceil($sms_length / $max_per_page);
    $sms_rate = $r->fax;
    $sms_charges = $calc_length * $sms_rate;
        
    $sourcepath = $_FILES["image"]["tmp_name"];
    $targetpath = "../img/" . $_FILES["image"]["name"];
    move_uploaded_file($sourcepath,$targetpath);
    	
    $location = $_FILES['image']['name'];
    $datetime = date("Y-m-d h:i:s");

    $sendSMS->backendGeneralAlert($sysabb, $phone, $sms, $sms_refid, $sms_charges, $uid);
    $sendSMS->staffRegEmailNotifier($email, $sysabb, $fullname, $shortenedurl, $username, $password, $shortenedurl1, $iemailConfigStatus, $ifetch_emailConfig);
    
    mysqli_query($link, "INSERT INTO activate_member VALUES(null,'$url','$shorturl','No','$id')") or die ("Error: " . mysqli_error($link));
    mysqli_query($link, "INSERT INTO user VALUES(null,'$fname','$lname','$mname','$email','$phone','$addr1','','$city','$state','','$country','Not-Activated','$username','$encrypt','$id','$location','$final_role','','Registered','','','$transactionPin','0.0','$dept','0.0','','','','','$gender','','','','','$datetime','','','','','Pending','staff','$uid','NULL','No','NULL','$allow_auth','$posPIN','0.0')") or die (mysqli_error($link));
        
    echo "<script>alert('New Employee Registered Successfully!'); </script>";
}
?>
             <div class="box-body">
				
			<div class="form-group">
            <label for="" class="col-sm-2 control-label" style="color:blue;">Your Image</label>
			<div class="col-sm-10">
  		  			 <input type='file' name="image" onChange="readURL(this);" />
       				 <img id="blah"  src="../avtar/user2.png" alt="Image Here" height="100" width="100"/>
			</div>
			</div>
                  
            <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">First Name</label>
                  <div class="col-sm-10">
                  <input name="fname" type="text" class="form-control" placeholder="First Name" required>
                  </div>
                  </div>

                  <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Middle Name</label>
                  <div class="col-sm-10">
                  <input name="mname" type="text" class="form-control" placeholder="Middle Name">
                  </div>
                  </div>

                  <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Last Name</label>
                  <div class="col-sm-10">
                  <input name="lname" type="text" class="form-control" placeholder="Last Name" required>
                  </div>
                  </div>				  
				  
		<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Email</label>
                  <div class="col-sm-10">
                  <input type="email" name="email" type="text" id="vemail" onkeyup="veryEmail();" class="form-control" placeholder="Email" required>
                  <div id="myvemail"></div>
                  </div>
                  </div>


        <div class="form-group">
                      <label for="" class="col-sm-2 control-label" style="color:blue;">Mobile</label>
                      <div class="col-sm-4">
                      <input name="phone" type="tel" class="form-control" id="phone" onkeyup="veryPhone();" required>
                      <div id="myvphone"></div>
                      </div><?php echo (isMobileDevice()) ? "<br>" : ""; ?>
                      <label for="" class="col-sm-2 control-label" style="color:blue;">Gender</label>
                      <div class="col-sm-4">
                            <select name="gender" class="form-control" required>
                                        <option value="" selected='selected'>Select Gender&hellip;</option>
                                        <option value="Male">Male</option>
                                        <option value="Female">Female</option>
                            </select>
                        </div>  
					</div>
				  
				  
		 <div class="form-group">
                  	<label for="" class="col-sm-2 control-label" style="color:blue;">Address</label>
                  	<div class="col-sm-10">
					<textarea name="addr1"  class="form-control" rows="2" cols="80" required></textarea>
           			 </div>
          </div>
			
			
			<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">City</label>
                  <div class="col-sm-10">
                  <input name="city" type="text" id="autocomplete1" onFocus="geolocate()" class="form-control" placeholder="City" required>
                  </div>
                  </div>
				  
		<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">State</label>
                  <div class="col-sm-10">
                  <input name="state" type="text" id="autocomplete2" onFocus="geolocate()" class="form-control" placeholder="State" required>
                  </div>
                  </div>
				 
				  
		<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Country</label>
                  <div class="col-sm-10">
				 <input name="country" type="text" id="autocomplete3" onFocus="geolocate()" class="form-control" placeholder="Country" required>  
									 </div>
                 					 </div>
                 					 

			<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Branch</label>
                  <div class="col-sm-10">
				<select name="branch"  class="form-control select2">
										<option value="" selected>Head Office</option>
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
			    <label for="" class="col-sm-2 control-label" style="color:blue;">Allow Authorization</label>
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
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Username</label>
                  <div class="col-sm-10">
                  <input name="username" type="text" class="form-control" id="vusername" onkeyup="veryUsername();" placeholder="Username" required>
                  <div id="myusername"></div>
                  </div>
                  </div>
		
                  
                  <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Department</label>
                  <div class="col-sm-10">
				    <select name="dept"  class="form-control select2">
						<option value="" selected>Select Department</option>
                        <?php 
                        $search_dept = mysqli_query($link, "SELECT * FROM dept WHERE companyid = ''") or die (mysqli_error($link));
                        while($get_dept = mysqli_fetch_object($search_dept))
                        {
                        ?>
                        	<option value="<?php echo $get_dept->id; ?>"><?php echo $get_dept->dpt_name; ?></option>
                        <?php } ?>
					</select>                 
				 </div>
                </div>

				  
				  	<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Select Role</label>
                  <div class="col-sm-9">
                  <table class="table table-responsive">
                    <?php
                    $search_module_properties = mysqli_query($link, "SELECT * FROM my_permission2 WHERE urole != 'super_admin'");
                    while($fetch_mproperties = mysqli_fetch_array($search_module_properties))
                    {
                    ?>
                    <tr>
                      <td>
                        <?php echo ucfirst(str_replace('_', ' ', $fetch_mproperties['urole'])); ?>
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
                				<button name="emp" type="submit" class="btn bg-blue"><i class="fa fa-save">&nbsp;Save</i></button>

              </div>
			  </div>
			  
			 </form> 

</div>
</div>
</div>
</div>