<div class="box">
        
	
	       <div class="box-body">
			<div class="panel panel-success">
            <div class="panel-heading bg-blue">
            <h3 class="panel-title"><i class="fa fa-gear"></i>&nbsp;Update Profile</h3>
            </div>
             <div class="box-body">

<div class="col-md-14">
            <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
             <li <?php echo ($_GET['tab'] == 'tab_1') ? "class='active'" : ''; ?>><a href="profile.php?id=<?php echo $_SESSION['tid']; ?>&&mid=NDAx&&tab=tab_1">Personal Details</a></li>
            
             <li <?php echo ($_GET['tab'] == 'tab_2') ? "class='active'" : ''; ?>><a href="profile.php?id=<?php echo $_SESSION['tid']; ?>&&mid=NDAx&&tab=tab_2">KYC Information</a></li>

             <!--<li <?php //echo ($_GET['tab'] == 'tab_3') ? "class='active'" : ''; ?>><a href=<?php //echo ($irole == "agent_manager" || $irole == "institution_super_admin" || $irole == "merchant_super_admin") ? "profile.php?id=".$_SESSION['tid']."&&mid=NDAx&&tab=tab_3" : '#'; ?>>Settlement Info</a></li>-->
            </ul>
             <div class="tab-content"> 

<?php 
$id = $_SESSION['tid'];
$call = mysqli_query($link, "SELECT * FROM user WHERE id='$id'");
$row = mysqli_fetch_array($call);
?>
               
<?php
if(isset($_GET['tab']) == true)
{
  $tab = $_GET['tab'];
  if($tab == 'tab_1')
  {
  ?>
             <div class="tab-pane <?php echo ($_GET['tab'] == 'tab_1') ? 'active' : ''; ?>" id="tab_1">
                 
             <div class="box-body">

			  <form class="form-horizontal" method="post" enctype="multipart/form-data">
			 <input type="hidden" value="<?php echo $row['id']; ?>" name="id" id="" required>

<?php   
if (isset($_POST['save'])) 
{
	$id= $_POST['id'];
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $mname = $_POST['mname'];
    $email = $_POST['email'];
	$number = str_replace(' ', '', $_POST['number']);
    $add1 = $_POST['ad1'];
    $city = $_POST['city'];
    $state = $_POST['state'];
	$zip = $_POST['zip'];
	$tpin = $_POST['tpin'];
    $user = $_POST['user'];
	$password = $_POST['password'];	
	$decript = base64_encode($password)	;
	$dob = $_POST['dob'];
    $gender = $_POST['gender'];
    //image
    $image = addslashes(file_get_contents($_FILES['image']['tmp_name']));

    if($image == "")
	{
		mysqli_query($link,"UPDATE user SET name='$fname',lname='$lname',mname='$mname',email='$email',phone='$number',addr1='$add1',city='$city',state='$state',zip='$zip',username='$user',password='$decript',tpin='$tpin',dob='$dob',gender='$gender' WHERE id ='$id'")or die(mysqli_error()); 
		
		echo'<span class="itext" style="color: blue;">Profile Update Successfully!!</span>';
		echo '<meta http-equiv="refresh" content="2;url=profile.php?id='.$_SESSION['tid'].'&&mid=NDAx&&tab=tab_1>';
		
	}
	else{
		$target_dir = "../img/";
		$target_file = $target_dir.basename($_FILES["image"]["name"]);
		$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
		$check = getimagesize($_FILES["image"]["tmp_name"]);
								
		if($check == false)
		{
			echo'<span class="itext" style="color: blue;">Profile Update Successfully!!</span>';
		    echo '<meta http-equiv="refresh" content="2;url=profile.php?id='.$_SESSION['tid'].'&&mid=NDAx&&tab=tab_1>';
		}
		elseif($_FILES["image"]["size"] > 500000000)
		{
			echo '<meta http-equiv="refresh" content="2;url=profile.php?id='.$_SESSION['tid'].'&&mid=NDAx&&tab=tab_1">';
			echo '<br>';
			echo'<span class="itext" style="color: orange;">Image must not more than 500KB!</span>';
		}
		elseif($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif")
		{
			echo'<span class="itext" style="color: blue;">Profile Update Successfully!!</span>';
		    echo '<meta http-equiv="refresh" content="2;url=profile.php?id='.$_SESSION['tid'].'&&mid=NDAx&&tab=tab_1>';
		}
		else{
			$sourcepath = $_FILES["image"]["tmp_name"];
			$targetpath = "../img/" . $_FILES["image"]["name"];
			move_uploaded_file($sourcepath,$targetpath);
									
			$location = "img/".$_FILES['image']['name'];				
					
			mysqli_query($link,"UPDATE user SET name='$fname',lname='$lname',mname='$mname',email='$email',phone='$number',addr1='$add1',city='$city',state='$state',zip='$zip',username='$user',password='$decript',image='$location',tpin='$tpin',dob='$dob',gender='$gender' WHERE id ='$id'")or die(mysqli_error()); 
									
			include("alert_sender/edit_profile_alert.php");
									
			echo'<span class="itext" style="color: blue;">Profile Update Successfully!!</span>';
		    echo '<meta http-equiv="refresh" content="2;url=profile.php?id='.$_SESSION['tid'].'&&mid=NDAx&&tab=tab_1>';						
		}					
	}						
}
?>

             <div class="box-body">
				
			<div class="form-group">
            <label for="" class="col-sm-2 control-label" style="color: <?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Your Image</label>
			<div class="col-sm-10">
  		  		<input type='file' name="image" onChange="readURL(this);" />
       			<img id="blah"  src="../<?php echo $row ['image'];?>" alt="Upload Image Here" height="100" width="100"/>
			</div>
			</div>
			
			<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">First Name</label>
                  <div class="col-sm-10">
                  <input name="fname" type="text" class="form-control" value="<?php echo $row['name']; ?>" required>
                  </div>
                  </div>

      <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Last Name</label>
                  <div class="col-sm-10">
                  <input name="lname" type="text" class="form-control" value="<?php echo $row['lname']; ?>" required>
                  </div>
                  </div>

      <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Middle Name</label>
                  <div class="col-sm-10">
                  <input name="mname" type="text" class="form-control" value="<?php echo $row['mname']; ?>">
                  </div>
                  </div>

				  
		<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Email</label>
                  <div class="col-sm-10">
                  <input type="email" name="email" type="text" class="form-control" value="<?php echo $row['email']; ?>" readonly>
                  </div>
                  </div>
				  
		<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Mobile Number</label>
                  <div class="col-sm-10">
                  <input name="number" type="text" class="form-control" value="<?php echo $row['phone'];?>" required>
                  </div>
                  </div>

		<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Gender</label>
                  <div class="col-sm-10">
                  <select name="gender" class="form-control" required>
                        <option value="<?php echo $row['gender'];?>" selected='selected'><?php echo $row['gender'];?></option>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                  </select>
                  </div>
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
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Date of Birth</label>
                  <div class="col-sm-10">
                  <input name="dob" type="date" class="form-control" value="<?php echo $row['dob'];?>" placeholder="Date Format: mm/dd/yyyy" id="txtDate" min="<?php echo $mymin_date; ?>" max="<?php echo $mymax_date; ?>" required>
                  </div>
                  </div>
				  
				  
		 <div class="form-group">
                  	<label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Address</label>
                  	<div class="col-sm-10">
					<textarea name="ad1"  class="form-control" rows="2" cols="80" required><?php echo $row['addr1'];?></textarea>
           			 </div>
          </div>
						
			
			<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">City</label>
                  <div class="col-sm-10">
                  <input name="city" type="text" class="form-control" value="<?php echo $row['city'];?>" required>
                  </div>
                  </div>
				  
		<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">State</label>
                  <div class="col-sm-10">
                  <input name="state" type="text" class="form-control"value="<?php echo $row['state'];?>" required>
                  </div>
                  </div>
				  
				  <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Zip Code</label>
                  <div class="col-sm-10">
                  <input name="zip" type="text" class="form-control" value="<?php echo $row['zip'];?>" required>
                  </div>
                  </div>

<hr>	
<div class="bg-orange">&nbsp;SECURITY INFORMATION</div>
<hr>	
					
					 <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Username</label>
                  <div class="col-sm-10">
                  <input name="user" type="text" class="form-control" value="<?php echo $row['username'];?>" readonly>
                  </div>
                  </div>
				  
				   <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Password</label>
                  <div class="col-sm-10">
                  <input name="password" type="password" class="form-control" value="<?php echo base64_decode($row['password']); ?>" required>
                  </div>
                  </div>
				  
				 <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Transaction Pin</label>
                  <div class="col-sm-10">
                  <input name="tpin" type="password" class="form-control" value="<?php echo $row['tpin'];?>" required>
                  </div>
                  </div> 
				  
			 </div>
			 
			  <div align="right">
              <div class="box-footer">
                	<button name="save" type="submit" onclick="myFunction()" class="btn bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>"><i class="fa fa-save">&nbsp;Save</i></button>
              </div>
			  </div>
			 </form> 

			 </div>  
        </div>
        <!-- /.tab-pane -->  


<?php
  }
  elseif($tab == 'tab_2')
  {
  ?>
  
        <div class="tab-pane <?php echo ($_GET['tab'] == 'tab_2') ? 'active' : ''; ?>" id="tab_2">
                 
             <div class="box-body">
          
       <form class="form-horizontal" method="post" enctype="multipart/form-data">

             <div class="box-body">

             <span id="success_message"></span>
                 
                 <div class="form-group">
                 <label for="" class="col-sm-3 control-label"></label>
                 <div class="col-sm-9">
                     <div class="form-group" id="process" style="display:none;">
                         <div class="progress">
                             <div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100">
                                 
                             </div>
                         </div>
                     </div> 
                </div>
                </div>

             <<div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">Upload Valid ID</label>
                  <div class="col-sm-7">
                  <input name="id_file" type="file" id="idFile" class="btn bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>"/>
                  <span style="color: <?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>;">Attach Copy e.g.  <b>Intl. Passport / National ID Card / Driving License</b></span>
                    </div>
                    <label class="col-sm-2"><button type="button" class="btn bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?> btn-flat" id="id_upload"><i class="fa fa-upload"> Upload</i></button></label>
                </div>


                <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">Upload Utility Bills</label>
                  <div class="col-sm-7">
                  <input name="utility_file" type="file" id="utilityFile" class="btn bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>"/>
                  <span style="color: <?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>;">Upload <b>as Proof of Address</b></span>
                    </div>
                    <label class="col-sm-2"><button type="button" class="btn bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?> btn-flat" id="utility_upload"><i class="fa fa-upload"> Upload</i></button></label>
                </div>

                <div id="myDiv">
                <div class="form-group">
                  <label for="" class="col-sm-3 control-label"></label>
                  <div class="col-sm-7">
                  <hr>
                  <div class="table-responsive">
                  <table id="example1" class="table table-bordered table-striped">
                    <thead>
                    <tr>
                    <th><input type="checkbox" id="select_all"/></th>
                    <th>File Title</th>
                    <th>File</th>
                    <th>Status</th>
                    <th>Date/Time</th>
                    </tr>
                    </thead>
                    <tbody>
    <?php
    $selectAttachment = mysqli_query($link, "SELECT * FROM attachment WHERE borrowerid = '$vuid' ORDER BY id DESC") or die (mysqli_error($link));
    if(mysqli_num_rows($selectAttachment)==0)
    {
    echo "<div class='alert bg-".(($myaltrow['theme_color'] == "") ? 'blue' : $myaltrow['theme_color'])."'>No data found yet!.....Check back later!!</div>";
    }
    else{
    while($rowAttachment = mysqli_fetch_array($select))
    {
    $id = $rowAttachment['id'];
    ?>    
                    <tr>
                        <td><input id="optionsCheckbox" class="checkbox" name="selector[]" type="checkbox" value="<?php echo $id; ?>"></td>
                        <td><?php echo $rowAttachment['file_title']; ?></td>
                        <td><a href="../img/<?php echo $rowAttachment['attached_file']; ?>" class="btn bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>" target="_blank"><i class="fa fa-eyes"></i> View Attachment</a></td>
                        <td><?php echo ($rowAttachment['fstatus'] == "Approved" ? "<span class='label bg-blue'><i class='fa fa-check'></i> ".$rowAttachment['fstatus']."</span>" : ($rowAttachment['fstatus'] == "Pending" ? "<span class='label bg-orange'><i class='fa fa-exclamation'></i> ".$rowAttachment['fstatus']."</span>" : "<span class='label bg-red'><i class='fa fa-times'></i> ".$rowAttachment['fstatus']."</span>")); ?></td>
                        <td><?php echo $rowAttachment['date_time']; ?></td>
                        
                    </tr>
    <?php } } ?>
                    </tbody>
                </table>
                </div>
                  <hr>
                  </div>
                  <label class="col-sm-2 control-label"></label>
                </div>
                </div>

                  
                <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">BVN <?php echo ($ibvn != "" && strlen($ibvn) == "11") ? '<span style="color: '.(($myaltrow['theme_color'] == "") ? "blue" : $myaltrow['theme_color']).'">[<b>Verified</b> <i class="fa fa-check"></i>]</span>' : '<span style="color: '.(($myaltrow['alternate_color'] == "") ? "orange" : $myaltrow['alternate_color']).'">[<b>Not-Verified</b> <i class="fa fa-times"></i>]</span>'; ?></label>
                  <label class="col-sm-7">
                  <input name="cust_bvn" type="text" class="form-control" value="<?php echo $ibvn; ?>" placeholder="BVN Number Here" maxlength="11">
                  <span style="color: <?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>;"> BVN Verification is for Nigeria Account Only.</span>
                  <br>
                  <div class="scrollable">
                  <?php
                  if(isset($_POST['verifyBVN'])){
                      
                       $userBvn = mysqli_real_escape_string($link, $_POST['cust_bvn']);
                       
					   if($iassigned_walletbal < $ibvn_fee){
           
							echo "<br><span class='bg-orange'>Sorry! No sufficient fund in the Wallet for this verification</span>";
						
						}  
					   elseif(strlen($userBvn) != 11){
                           
                           echo "<br><span>BVN Number not Valid....</span>";
                           
                       }
                       elseif($ibvn_route == "Wallet Africa"){
                           
                            require_once "../config/bvnVerification_class.php";
        
                            $processBVN = $newBV->walletAfricaBVNVerifier($walletafrica_skey,$userBvn,$link);
                            $ResponseCode = $processBVN['ResponseCode'];
                           
                            if($ResponseCode == "200"){
                               
                                $icm_id = "ICM".time();
                                $exp_id = "EXP".time();
                                $myOtp = substr((uniqid(rand(),1)),3,6);
                                $rOrderID = "EA-bvnCharges-".time();
                               
                                $date_time = date("Y-m-d");
                                $wallet_date_time = date("Y-m-d H:i:s");
                                
								$wbalance = $iwallet_balance - $fetchsys_config['bvn_fee'];
                                //substr()
                                //BVN Details
                                $bvn_fname = $processBVN['FirstName'];
                                $bvn_lname = $processBVN['LastName'];
                                $bvn_mname = $processBVN['MiddleName'];
                                $bvn_dob = $processBVN['DateOfBirth'];
                                $bvn_phone = "+234".substr($processBVN['PhoneNumber'],-10);
                                $correct_bvnPhone = $processBVN['PhoneNumber'];
                                $bvn_email = $processBVN['Email'];
                                $bvn_picture = $processBVN['Picture'];
                                $dynamicStr = md5(date("Y-m-d h:i"));
                                $image_converted = $newBV->base64_to_jpeg($bvn_picture, $dynamicStr.".png");
                                $default_dob = date("d-M-Y", strtotime($idob));

                                //20 array row
                                $mybvn_data = $processBVN['BVN']."|".$processBVN['FirstName']."|".$processBVN['LastName']."|".$processBVN['MiddleName']."|".$processBVN['DateOfBirth']."|".$processBVN['PhoneNumber']."|".$processBVN['Email']."|".$processBVN['EnrollmentBank']."|".$processBVN['EnrollmentBranch']."|".$processBVN['Gender']."|".$processBVN['LevelOfAccount']."|".$processBVN['LgaOfOrigin']."|".$processBVN['LgaOfResidence']."|".$processBVN['MaritalStatus']."|".$processBVN['NameOnCard']."|".$processBVN['Nationality']."|".$processBVN['StateOfOrigin']."|".$processBVN['StateOfResidence']."|".$processBVN['Title']."|".$processBVN['WatchListed']."|".$image_converted;
                                
                                $search_bvnverify = mysqli_query($link, "SELECT * FROM bvn_log WHERE accountID = '$vuid'");
                                $fetch_bvnverify = mysqli_fetch_array($search_bvnverify);
                                $bvn_nos = mysqli_num_rows($search_bvnverify);
                                $concat = $fetch_bvnverify['mydata'];
                                $parameter = (explode('|',$concat));
                                $old_picture = $parameter[20];
                                   
                                $seach_membersttings = mysqli_query($link, "SELECT * FROM member_settings WHERE companyid = '$institution_id'");
                                $fetch_memset = mysqli_fetch_array($seach_membersttings);
                                
                                //include("alert_sender/bvn_otp.php");
                                $update_wallet = mysqli_query($link, "UPDATE institution_data SET wallet_balance = '$wbalance' WHERE institution_id = '$institution_id'");
                                
                                ($bvn_nos == 1 && $old_picture != "") ? unlink("../img/".$old_picture) : "";
								
								($bvn_nos == 1) ? $update_bvn = mysqli_query($link, "UPDATE bvn_log SET mydata = '$mybvn_data' WHERE accountID = '$vuid'") : $insert_bvn = mysqli_query($link, "INSERT INTO bvn_log VALUE(null,'$institution_id','$isbranchid','$vuid','$iAcctOfficer','$mybvn_data','$ibvn_fee','$wallet_date_time','$rOrderID')");
                                
                                $insert_income = mysqli_query($link, "INSERT INTO wallet_history VALUE(null,'$institution_id','$rOrderID','$userBvn','','$ibvn_fee','Debit','$icurrency','BVN_Charges','Response: $icurrency.$ibvn_fee was charged for Customer BVN Verification','successful','$wallet_date_time','$vuid','$wbalance','')");
                                
                                $insert_income = mysqli_query($link, "INSERT INTO income VALUES(null,'','$icm_id','BVN','$ibvn_fee','$date_time','Employee BVN Verification Charges')");
                                
                                ($bvn_dob == $default_dob || $bvn_phone == $myiphone) ? mysqli_query($link, "UPDATE user SET addr2 = '$userBvn' WHERE id = '$vuid'") : "";
                                   
                                ($bvn_dob == $default_dob || $bvn_phone == $myiphone) ? mysqli_query($link, "UPDATE virtual_account SET acct_status = 'UnderReview' WHERE userid = '$vuid'") : "";

                                echo ($bvn_dob == $default_dob || $bvn_phone == $myiphone) ? '<p style="color: blue;"><b>Data Verified Successfully!</b> <i class="fa fa-check"></i></p>' : '<p style="color: orange;"><b>Data Verification Not Successful! Please try with correct BVN that Match your Information on our System</b> <i class="fa fa-times"></i></p>';
                                   
                                echo '<meta http-equiv="refresh" content="10;url=profile.php?id='.$_SESSION['tid'].'&&mid=NDAx&&tab=tab_2">';

                           }
                           else{
                          		echo "<br><span class='bg-orange'>Oops! Network Error, please try again later </span>";
                            }
                           
                       }
                       else{
                           
						   //empty
						   echo "Sorry! Service not available at the moment, please try again later!!";
                           
                       }
                      
                  }
                  ?>
                  </div>
                  </label>
                  <label class="col-sm-2"><button type="submit" class="btn bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?> btn-flat" name="verifyBVN"><i class="fa fa-eye">&nbsp;Verify</i></button></label>
                </div>

                <div class="form-group">
                  <label for="" class="col-sm-3 control-label"></label>
                  <label class="col-sm-7">
                  
                  </label>
                  <label class="col-sm-2"><button name="upload" type="submit" class="btn bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?> btn-flat"><i class="fa fa-save"> Save </i></button></label>
                </div>
                
                <?php
                $lookup_bvn = mysqli_query($link, "SELECT * FROM bvn_log WHERE accountID = '$vuid'");
                $fetch_lookupbvn = mysqli_fetch_array($lookup_bvn);
                $vry_bvn_num = mysqli_num_rows($lookup_bvn);
                if($vry_bvn_num == 1)
                {
                ?>
                <hr>
                <div align="center">
                    <img src="../img/bvn_logo.png" height="75" width="200"/>
                    <p><b>Last BVN Verification on <?php echo date("Y-m-d", strtotime($fetch_lookupbvn['date_time'])); ?></b></p>
                    <a href="bvnInfo.php?id=<?php echo $_SESSION['tid']; ?>&&mid=NDA0&&ref=<?php echo $fetch_lookupbvn['ref']; ?>" target="_blank"><b>Click here</b></a> to print your BVN Page
                </div>
                <?php
                }
                else{
                    echo "";
                }
                ?>
      
  </div>

<?php
if(isset($_POST['upload']))
{
    $id = $_GET['id'];
    $mybvn = mysqli_real_escape_string($link, $_POST['cust_bvn']);
    
    $search_Otp = mysqli_query($link, "SELECT * FROM bvn_log WHERE accountID = '$vuid'");
    if(mysqli_num_rows($search_Otp) == 1)
    {
        $fetch_otp = mysqli_fetch_array($search_Otp);
        $concat = $fetch_otp['mydata'];
        $parameter = (explode('|',$concat));
        $confirmed_fname = $parameter['1'];
        $confirmed_lname = $parameter['2'];
        $confirmed_mname = $parameter['3'];
        $confirmed_dob = $parameter['4'];

        ($mybvn == "") ? "" : mysqli_query($link, "UPDATE virtual_account SET acct_status = 'UnderReview' WHERE userid = '$vuid'");
        mysqli_query($link, "UPDATE user SET name = '$confirmed_fname', lname = '$confirmed_lname', mname = '$confirmed_mname', dob = '$confirmed_dob', addr2 = '$mybvn' WHERE id = '$vuid'");
        
        echo "<div class='alert bg-blue'>Profile Updated Successfully</div>";
	    echo '<meta http-equiv="refresh" content="2;url=profile.php?id='.$_SESSION['tid'].'&&mid=NDAx&&tab=tab_2">';
        
    }
    else{

        mysqli_query($link, "UPDATE user SET addr2 = '$mybvn' WHERE id = '$vuid'");
        
        echo "<div class='alert bg-blue'>Profile Updated Successfully</div>";
	    echo '<meta http-equiv="refresh" content="2;url=profile.php?id='.$_SESSION['tid'].'&&mid=NDAx&&tab=tab_2">';
        
    }

}
?>
      
       </form>
        </div>  
        </div>
        <!-- /.tab-pane --> 
        

<?php
  }
  elseif($tab == 'tab_3')
  {
  ?>
  
  <!--
        <div class="tab-pane <?php echo ($_GET['tab'] == 'tab_3') ? 'active' : ''; ?>" id="tab_3">
                 
             <div class="box-body">
          
       <form class="form-horizontal" method="post" enctype="multipart/form-data">

            <div class="box-body">

            <?php
            if($isettlement_acctno == "" || $isettlement_bankcode == ""){
            ?>

             <div class="form-group">
                <label for="" class="col-sm-3 control-label" style="color:blue;">Settlement Country:</label>
                <div class="col-sm-6">
                    <select name="country" class="form-control select2" id="country" onchange="loadbank();" required>
                      <option value="" selected>Select Country</option>
                      <option value="NG">Nigeria</option>
                      <option value="GH">Ghana</option>
                      <option value="KE">Kenya</option>
                      <option value="UG">Uganda </option>
                      <option value="TZ">Tanzania</option>
                    </select>
                </div>
                <label for="" class="col-sm-3 control-label"></label>
            </div>


            <div class="form-group">
                <label for="" class="col-sm-3 control-label" style="color:blue;">Account Number:</label>
                <div class="col-sm-6">
                    <input name="acct_no" type="text" id="account_number" onkeydown="loadaccount();" class="form-control" placeholder="Account Number" required>
                </div>
                <label for="" class="col-sm-3 control-label"></label>
            </div>


            <div class="form-group">
                <label for="" class="col-sm-3 control-label" style="color:blue;">Bank Name:</label>
                <div class="col-sm-6">
                    <div id="bank_list"></div>
                </div>
                <label for="" class="col-sm-3 control-label"></label>
            </div>


            <div class="form-group">
                <label for="" class="col-sm-3 control-label" style="color:blue;">Account Holder:</label>
                <div class="col-sm-6">
                    <span id="act_numb"></span>
                </div>
                <label for="" class="col-sm-3 control-label"></label>
            </div>

             </div>

             <?php
            }
            else{
            ?>

            <div class="form-group">
            <label for="" class="col-sm-3 control-label" style="color:blue;">Account Number:</label>
            <div class="col-sm-6">
                <input name="acct_no" type="text" class="form-control" value="<?php echo $isettlement_acctno; ?>" placeholder="Account Number" required>
            </div>
            <label for="" class="col-sm-3 control-label"></label>
            </div>


            <div class="form-group">
            <label for="" class="col-sm-3 control-label" style="color:blue;">Bank Name:</label>
            <div class="col-sm-6">
                <input name="bank_name" type="text" class="form-control" value="<?php echo $isettlement_bankname; ?>" required>
            </div>
            <label for="" class="col-sm-3 control-label"></label>
            </div>


            <div class="form-group">
            <label for="" class="col-sm-3 control-label" style="color:blue;">Account Holder:</label>
            <div class="col-sm-6">
            <input name="b_name" type="text" class="form-control" value="<?php echo $isettlement_acctname; ?>" placeholder="Account Holder">
            </div>
            <label for="" class="col-sm-3 control-label"></label>
            </div>

            <?php
            }
            ?>

            </div>

            <div class="form-group" align="right">
                <label for="" class="col-sm-3 control-label" style="color:blue;"></label>
                <div class="col-sm-6">
                	<button name="btnStlm" type="submit" class="btn bg-blue"><i class="fa fa-link">&nbsp;Submit</i></button>
                </div>
                <label for="" class="col-sm-3 control-label"></label>
            </div>

<?php
/**
if(isset($_POST['btnStlm'])){

    $country = mysqli_real_escape_string($link, $_POST['country']);
    $acct_no = mysqli_real_escape_string($link, $_POST['acct_no']);
    $bank_code = mysqli_real_escape_string($link, $_POST['bank_code']);
    $bank_name = mysqli_real_escape_string($link, $_POST['bank_name']);
    $b_name = mysqli_real_escape_string($link, $_POST['b_name']);

    if($isettlement_acctno == "" || $isettlement_bankcode == ""){
        
        $update = mysqli_query($link, "UPDATE institution_data SET settlement_country = '$country', settlement_acctno = '$acct_no$acct_no', settlement_bankcode = '$bank_code', settlement_bankname = '$bank_name', settlement_acctname = '$b_name' WHERE institution_id = '$institution_id'");

        echo "<div class='alert bg-blue'>Profile Updated Successfully</div>";
        echo '<meta http-equiv="refresh" content="2;url=profile.php?id='.$_SESSION['tid'].'&&mid=NDAx&&tab=tab_3">'; 

    }
    else{

        $update = mysqli_query($link, "UPDATE institution_data SET settlement_acctno = '$acct_no$acct_no', settlement_bankname = '$bank_name', settlement_acctname = '$b_name' WHERE institution_id = '$institution_id'");

        echo "<div class='alert bg-blue'>Profile Updated Successfully</div>";
        echo '<meta http-equiv="refresh" content="2;url=profile.php?id='.$_SESSION['tid'].'&&mid=NDAx&&tab=tab_3">'; 

    }


}
**/
?>

        </form>

        </div>  

        </div>-->
        <!-- /.tab-pane --> 


<?php
  }
}
?>

</div>
</div>
</div>
<!-- /.tab-closed -->

</div>	
</div>
</div>
</div>