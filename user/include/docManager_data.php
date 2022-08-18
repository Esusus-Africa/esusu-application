<div class="box">
         <div class="box-body">
      <div class="panel panel-success">
            <div class="panel-heading bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">
<?php
if(isMobileDevice()){
    //Your content or code for mobile devices goes here
?>

<h3 class="panel-title">
<button type="button" class="btn btn-flat bg-white" align="left" style="color: black;">&nbsp;<b>Balance:</b>&nbsp;
<strong class="alert bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>">
<?php
echo "<span id='wallet_balance'>".$bbcurrency.number_format($bwallet_balance,2,'.',',')."</span>";
?> 
</strong>
  </button>
</h3>

<?php
}
else{
    ?>
            <h3 class="panel-title"> <a href="docManager.php?id=<?php echo $_SESSION['tid']; ?>&&acn=<?php echo $_SESSION['acctno']; ?>&&mid=OTUw"><button type="button" class="btn btn-flat bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>"><i class="fa fa-refresh"></i>&nbsp;Refresh</button></a>
            <button type="button" class="btn btn-flat bg-white" align="left" style="color: black;">&nbsp;<b>Balance:</b>&nbsp;
            <strong class="alert bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>">
            <?php
            echo "<span id='wallet_balance'>".$bbcurrency.number_format($bwallet_balance,2,'.',',')."</span>";
            ?> 
            </strong>
              </button>
            </h3>

<?php    
}
?>
            </div>
            
<div class="col-md-14">
            <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
             <li <?php echo ($_GET['tab'] == 'tab_1') ? "class='active'" : ''; ?>><a href="docManager.php?tid=<?php echo $_SESSION['tid']; ?>&&acn=<?php echo $_SESSION['acctno']; ?>&&mid=OTUw&&tab=tab_1">Personal Information</a></li>

             <!--<li <?php echo ($_GET['tab'] == 'tab_2') ? "class='active'" : ''; ?>><a href="docManager.php?tid=<?php echo $_SESSION['tid']; ?>&&acn=<?php echo $_SESSION['acctno']; ?>&&mid=OTUw&&tab=tab_2">Identity Verification</a></li>-->

             <li <?php echo ($_GET['tab'] == 'tab_3') ? "class='active'" : ''; ?>><a href="docManager.php?tid=<?php echo $_SESSION['tid']; ?>&&acn=<?php echo $_SESSION['acctno']; ?>&&mid=OTUw&&tab=tab_3">KYC Documents</a></li>

             <!--<li <?php echo ($_GET['tab'] == 'tab_4') ? "class='active'" : ''; ?>><a href="docManager.php?tid=<?php echo $_SESSION['tid']; ?>&&acn=<?php echo $_SESSION['acctno']; ?>&&mid=OTUw&&tab=tab_4">Documents Verification</a></li>-->
             
             <li <?php echo ($_GET['tab'] == 'tab_5') ? "class='active'" : ''; ?>><a href="docManager.php?tid=<?php echo $_SESSION['tid']; ?>&&acn=<?php echo $_SESSION['acctno']; ?>&&mid=OTUw&&tab=tab_5">Next of Kin</a></li>
             
             <li <?php echo ($_GET['tab'] == 'tab_6') ? "class='active'" : ''; ?>><a href="docManager.php?tid=<?php echo $_SESSION['tid']; ?>&&acn=<?php echo $_SESSION['acctno']; ?>&&mid=OTUw&&tab=tab_6">Work Information</a></li>

            </ul>
             <div class="tab-content"> 
             
<?php
    $systemset = mysqli_query($link, "SELECT * FROM systemset");
    $row1 = mysqli_fetch_object($systemset);
    
    $localpayment_charges = $row1->localpayment_charges;
    $capped_amount = $row1->capped_amount;
    $intlpayment_charges = $row1->intlpayment_charges;
?>

 <?php 
	$id = $_SESSION['tid'];
	$call = mysqli_query($link, "SELECT * FROM borrowers WHERE id='$id'") or die ("Error: " . mysqli_error($link));
	$row = mysqli_fetch_assoc($call);
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

			 <input type="hidden" value="<?php echo $row ['id']; ?>" name="id"  id="" required>
			 
<?php   

			  				if (isset($_POST['save']))
							{
							    
                                $id = $_SESSION['tid'];
                                $fname = $_POST['fname'];
                                $mname = $_POST['mname'];
                                $email = $_POST['email'];
							    $newphone = str_replace(' ', '', $_POST['number']);
                                $user = $_POST['user'];
                                $username = $_POST['username'];
								$password = $_POST['password'];	
								$tpin = $_POST['tpin'];	
								$gender = $_POST['gender'];
								$dob =  $_POST['dob'];
								$myaddrs = $_POST['addrs'];
								$phone = str_replace(' ', '', $phone2);

                                $state = $_POST['state'];
                                $lga = $_POST['lga'];
                                $maidenName = $_POST['maidenName'];
                                $moi = $_POST['moi'];
                                $otherInfo = $_POST['otherInfo'];
								
								//OTP Section
								$mydata = $fname."|".$mname."|".$email."|".$gender."|".$newphone."|".$myaddrs."|".$username."|".$password."|".$tpin."|".$dob."|".$state."|".$lga."|".$maidenName."|".$moi."|".$otherInfo;
								$otpcode = substr((uniqid(rand(),1)),3,6);
								$currenctdate = date("Y-m-d h:i:s");
                                
                                $query = mysqli_query($link, "SELECT * FROM systemset") or die (mysqli_error($link));
                        		$r = mysqli_fetch_object($query);
                        		$sysabb = ($bsender_id == "") ? $r->abb : $bsender_id;
								
								($otp_option === "No") ? "" : $sms = "$sysabb>>>Dear $myln! Your One Time Password is $otpcode";
								                        		
                        		$search_insti = mysqli_query($link, "SELECT * FROM institution_data WHERE institution_id = '$bbranchid'");
                            	$fetch_myinst = mysqli_fetch_array($search_insti);
                            	$iwallet_balance = $fetch_myinst['wallet_balance'];
                            	
                            	$sms_rate = $r->fax;
                        		$imywallet_balance = $iwallet_balance - $sms_rate;
                            	$sms_refid = "EA-smsCharges-".rand(1000000,9999999);

                                $debitWAllet = ($bgetSMS_ProviderNum == 1) ? "No" : "Yes"; 

                                //image
                                $image = $_FILES['image']['tmp_name'];
                                if($image == "")
								{
									($otp_option === "No") ? mysqli_query($link,"UPDATE borrowers SET fname='$fname',mname='$mname',email='$email',gender='$gender',phone='$newphone',addrs='$myaddrs',username='$username',password='$password',tpin='$tpin',dob='$dob',state='$state',lga='$lga',mmaidenName='$maidenName',moi='$moi',otherInfo='$otherInfo' WHERE id ='$id'") : "";
									($otp_option === "Yes" || $otp_option === "Both") ? mysqli_query($link,"INSERT INTO otp_confirmation VALUES(null,'$acctno','$otpcode','$mydata2','Pending','$currenctdate')") : "";
                                    //SMS NOTIFICATION
                                    ($debitWAllet == "No" && ($otp_option === "Yes" || $otp_option === "Both") ? $sendSMS->instGeneralAlert($bozeki_password, $bozeki_url, $sysabb, $phone2, $sms, $bbranchid, $sms_refid, $sms_rate, $acctno, $imywallet_balance, $debitWallet) : ($debitWAllet == "Yes" && $sms_rate <= $iwallet_balance && ($otp_option === "Yes" || $otp_option === "Both") ? $sendSMS->instGeneralAlert($bozeki_password, $bozeki_url, $sysabb, $phone2, $sms, $bbranchid, $sms_refid, $sms_rate, $acctno, $imywallet_balance, $debitWallet) : ""));

									echo ($otp_option === "No") ? "<div class='alert bg-blue'>Profile Updated Successfully</div>" : "<div class='alert bg-blue'>Otp has been sent to your mobile phone to confirm the update</div>";
                                	echo ($otp_option === "No") ? '<meta http-equiv="refresh" content="2;url=docManager.php?tid='.$_SESSION['tid'].'&&acn='.$acctno.'&&mid=OTUw&&tab=tab_1">' : '<meta http-equiv="refresh" content="2;url=docManager.php?tid='.$_SESSION['tid'].'&&acn='.$acctno.'&&mid=OTUw&&tab=tab_1&&otp">';

								}
								elseif($image != ""){

								    $target_dir = "../img/";
								    $target_file = $target_dir.basename($_FILES["image"]["name"]);
								    $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
								    $check = getimagesize($_FILES["image"]["tmp_name"]);
								
									$sourcepath = $_FILES["image"]["tmp_name"];
									$targetpath = "../img/" . $_FILES["image"]["name"];
									move_uploaded_file($sourcepath,$targetpath);
									
									$location = $_FILES['image']['name'];
									
									$mydata2 = $fname."|".$mname."|".$email."|".$gender."|".$newphone."|".$myaddrs."|".$username."|".$password."|".$tpin."|".$location;
									
									($otp_option === "No") ? mysqli_query($link,"UPDATE borrowers SET fname='$fname',mname='$mname',email='$email',gender='$gender',phone='$newphone',addrs='$myaddrs',username='$username',password='$password',image='$location',tpin='$tpin',dob='$dob',state='$state',lga='$lga',mmaidenName='$maidenName',moi='$moi',otherInfo='$otherInfo' WHERE id ='$id'") : "";
									($otp_option === "Yes" || $otp_option === "Both") ? mysqli_query($link,"INSERT INTO otp_confirmation VALUES(null,'$acctno','$otpcode','$mydata2','Pending','$currenctdate')") : "";
									//SMS NOTIFICATION
                                    ($debitWAllet == "No" && ($otp_option === "Yes" || $otp_option === "Both") ? $sendSMS->instGeneralAlert($bozeki_password, $bozeki_url, $sysabb, $phone2, $sms, $bbranchid, $sms_refid, $sms_rate, $acctno, $imywallet_balance, $debitWallet) : ($debitWAllet == "Yes" && $sms_rate <= $iwallet_balance && ($otp_option === "Yes" || $otp_option === "Both") ? $sendSMS->instGeneralAlert($bozeki_password, $bozeki_url, $sysabb, $phone2, $sms, $bbranchid, $sms_refid, $sms_rate, $acctno, $imywallet_balance, $debitWallet) : ""));
					
								    echo ($otp_option === "No") ? "<div class='alert bg-blue'>Profile Updated Successfully</div>" : "<div class='alert bg-blue'>Otp has been sent to your mobile phone to confirm the update</div>";
                                	echo ($otp_option === "No") ? '<meta http-equiv="refresh" content="2;url=docManager.php?tid='.$_SESSION['tid'].'&&acn='.$acctno.'&&mid=OTUw&&tab=tab_1">' : '<meta http-equiv="refresh" content="2;url=docManager.php?tid='.$_SESSION['tid'].'&&acn='.$acctno.'&&mid=OTUw&&tab=tab_1&&otp">';
    
    							}
						    }
						?>
						
						<?php
						if (isset($_POST['confirm']))
						{
						    $myotp = $_POST['otp'];
						    
						    $verify_otp = mysqli_query($link, "SELECT * FROM otp_confirmation WHERE otp_code = '$myotp' AND userid = '$acctno' AND status = 'Pending'");
						    $fetch_data = mysqli_fetch_array($verify_otp);
						    $otpnum = mysqli_num_rows($verify_otp);
						    
						    if($otpnum == 0){
						        
						        echo "<div class='alert bg-orange'>Opps!...Invalid OTP Entered!!</div>";
						        
						    }else{
						        
						        $concat = $fetch_data['data'];
    
                                $datetime = $fetch_data['datetime'];
                                
                                $parameter = (explode('|',$concat));
                                
                                $fname = $parameter[0];
                                $mname = $parameter[1];
                                $email = $parameter[2];
                                $gender = $parameter[3];
                                $phone = $parameter[4];
                                $myaddrs = $parameter[5];
                                $username = $parameter[6];
                                $password = $parameter[7];
                                $tpin = $parameter[8];
                                $location = $parameter[9];
                                $dob = $parameter[10];
                                $state = $parameter[11];
                                $lga = $parameter[12];
                                $maidenName = $parameter[13];
                                $moi = $parameter[14];
                                $otherInfo = $parameter[15];
                                
                                ($location == "") ? mysqli_query($link,"UPDATE borrowers SET fname='$fname',mname='$mname',email='$email',gender='$gender',phone='$phone',addrs='$myaddrs',username='$username',password='$password',tpin='$tpin',dob='$dob',state='$state',lga='$lga',maidenName='$maidenName',moi='$moi',otherInfo='$otherInfo' WHERE id ='$id'") : "";
                                ($location != "") ? mysqli_query($link,"UPDATE borrowers SET fname='$fname',mname='$mname',email='$email',gender='$gender',phone='$phone',addrs='$myaddrs',username='$username',password='$password',image='$location',tpin='$tpin',dob='$dob',state='$state',lga='$lga',maidenName='$maidenName',moi='$moi',otherInfo='$otherInfo' WHERE id ='$id'") : "";
						        mysqli_query($link, "UPDATE otp_confirmation SET status = 'Verified' WHERE userid = '$acctno' AND otp_code = '$myotp' AND status = 'Pending'");
						        
						        echo "<div class='alert bg-blue'>Profile Updated Successfully</div>";
						        echo '<meta http-equiv="refresh" content="2;url=docManager.php?tid='.$_SESSION['tid'].'&&acn='.$acctno.'&&mid=OTUw&&tab=tab_1">';
						        
						    }
						    
						}
						?>
						

<?php
if(!isset($_GET['otp']))
{
?>
             <div class="box-body">
				
			<div class="form-group">
            <label for="" class="col-sm-2 control-label" style="color: <?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Your Image</label>
			<div class="col-sm-10">
  		  			 <input type='file' name="image" onChange="readURL(this);" />
       				 <img id="blah"  src="<?php echo $fetchsys_config['file_baseurl'].$row['image'];?>" alt="Upload Image Here" height="100" width="100"/>
			</div>
			</div>
			
			<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color: <?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">First Name</label>
                  <div class="col-sm-10">
                  <input name="fname" type="text" class="form-control" value="<?php echo $row['fname']; ?>" /required>
                  </div>
                  </div>

                  <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color: <?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Middle Name</label>
                  <div class="col-sm-10">
                  <input name="mname" type="text" class="form-control" value="<?php echo $row['mname']; ?>" /required>
                  </div>
                  </div>

                  <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color: <?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Last Name</label>
                  <div class="col-sm-10">
                  <input name="lname" type="text" class="form-control" value="<?php echo $row['lname']; ?>" /readonly>
                  </div>
                  </div>
                  
            <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Gender</label>
                  <div class="col-sm-10">
				<select name="gender"  class="form-control select2" required>
					<option value="<?php echo $row['gender']; ?>" selected='selected'><?php echo $row['gender']; ?></option>
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
				  
                  <input name="idp" type="hidden" class="form-control" value="<?php echo $row ['id'];?>">
				  
				  
		<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color: <?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Email</label>
                  <div class="col-sm-10">
                  <input type="email" name="email" class="form-control" value="<?php echo $row ['email'];?>" /readonly>
                  </div>
                  </div>
				  
		<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color: <?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Mobile Number</label>
                  <div class="col-sm-10">
                  <input name="number" type="text" class="form-control" value="<?php echo $row ['phone'];?>" id="phone" readonly>
                  </div>
                  </div>

        <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color: <?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">State of Origin</label>
                  <div class="col-sm-10">
                  <input type="text" name="state" class="form-control" value="<?php echo $row ['state'];?>" /required>
                  </div>
                  </div>

        <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color: <?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Local Govt. Area</label>
                  <div class="col-sm-10">
                  <input name="lga" type="text" class="form-control" value="<?php echo $row ['lga'];?>" /required>
                  </div>
                  </div>

        <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color: <?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Maiden Name</label>
                  <div class="col-sm-10">
                  <input name="maidenName" type="text" class="form-control" value="<?php echo $row ['mmaidenName'];?>" /required>
                  </div>
                  </div>

        <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Mode of Identification</label>
                  <div class="col-sm-10">
				<select name="moi" class="form-control select2" required>
					<option value="<?php echo $row['moi']; ?>" selected='selected'><?php echo ($row['moi'] == "") ? "Select Mode of Identification" : $row['moi']; ?></option>
					<option value="International Passport">International Passport</option>
					<option value="National ID Card">National ID Card</option>
                    <option value="Driving License">Driving License</option>
                    <option value="Voters Card">Voters Card</option>
				</select>
    		</div>
    		</div>
                  
        <div class="form-group">
            <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">Address</label>
            <div class="col-sm-10">
                <textarea name="addrs" class="form-control" rows="2" cols="80" required><?php echo $row ['addrs'];?></textarea>
            </div>
        </div>

        <div class="form-group">
            <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">Other Info</label>
            <div class="col-sm-10">
                <textarea name="otherInfo" class="form-control" rows="2" cols="80" placeholder="Health Condition (Such as Blood Pressure, Height, Weight)" required><?php echo $row ['otherInfo'];?></textarea>
            </div>
        </div>
				  
<hr>	
<div class="bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>">&nbsp;SECURITY INFORMATION</div>
<hr>	
				
				<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color: <?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Account ID</label>
                  <div class="col-sm-10">
                  <input name="user" type="text" class="form-control" value="<?php echo $row ['account'];?>" required readonly>
                  </div>
                  </div>
                  
                <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Username</label>
                  <div class="col-sm-10">
                  <input name="username" type="text" class="form-control" id="vusername" onkeyup="veryUsername();" value="<?php echo $row ['username'];?>" placeholder="Username" required>
                  <div id="myusername"></div>
                  </div>
                  </div>
				  
				   <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color: <?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Password</label>
                  <div class="col-sm-10">
                  <input name="password" type="password" class="form-control" value="<?php echo $row ['password']; ?>" required>
                  </div>
                  </div>
				  
				  <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color: <?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Transaction Pin</label>
                  <div class="col-sm-10">
                  <input name="tpin" type="password" class="form-control" value="<?php echo $row ['tpin']; ?>" required>
                  </div>
                  </div>
				  
			 </div>
			 
			  <div align="right">
              <div class="box-footer">
                	<button name="save" type="submit" onclick="myFunction()" class="btn bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>"><i class="fa fa-save">&nbsp;Update Profile</i></button>
              </div>
			  </div>
			  
<?php
}
else{
    include("otp_confirmation.php");
}
?>
						
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
          
       <form class="form-horizontal" method="post" enctype="multipart/form-data" id="sample_form">

            <div class="box-body">
                
                <div class="form-group">
					<label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Verification Type</label>
					<div class="col-sm-10">
						<select name="verificationType" class="form-control select2" id="verificationType" required>
							<option value="" selected="selected">Select Verification Type</option>
							<option value="Phone">Phone</option>
							<option value="NIN">NIN</option>
						</select>
					</div>
				</div>

                <span id='ShowValueFrank'></span>
				<span id='ShowValueFrank'></span>
                
                <?php
                $lookup_bvn = mysqli_query($link, "SELECT * FROM bvn_log WHERE accountID = '$acctno'");
                $fetch_lookupbvn = mysqli_fetch_array($lookup_bvn);
                $vry_bvn_num = mysqli_num_rows($lookup_bvn);
                if($vry_bvn_num == 1)
                {
                ?>
                <hr>
                <div align="center">
                    <img src="<?php echo $fetchsys_config['file_baseurl']; ?>bvn_logo.png" height="75" width="200"/>
                    <p><b>Last BVN Verification on <?php echo date("Y-m-d", strtotime($fetch_lookupbvn['date_time'])); ?></b></p>
                    <a href="identityInfo.php?tid=<?php echo $_SESSION['tid']; ?>&&acn=<?php echo $acctno; ?>&&mid=OTUw&&ref=<?php echo $fetch_lookupbvn['ref']; ?>" target="_blank"><b>Click here</b></a> to print your Identity Verification Page
                </div>
                <?php
                }
                else{
                    echo "";
                }
                ?>

                <div align="right">
                    <div class="box-footer">
                	    <button name="verifyIdentity" type="submit" class="btn bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>"><i class="fa fa-upload"> Verify Identity</i></button>
                    </div>
			    </div>
      
            </div>

<?php
if(isset($_POST['verifyIdentity']))
{
    $verificationType = mysqli_real_escape_string($link, $_POST['verificationType']);
    $vType = mysqli_real_escape_string($link, $_POST['vType']);
    
    /**
    $search_Otp = mysqli_query($link, "SELECT * FROM bvn_log WHERE accountID = '$acctno' ORDER BY id DESC");
    if(mysqli_num_rows($search_Otp) >= 1)
    {
        $fetch_otp = mysqli_fetch_array($search_Otp);
        $concat = $fetch_otp['mydata'];
        $parameter = (explode('|',$concat));
        $confirmed_fname = $parameter['1'];
        $confirmed_lname = $parameter['2'];
        $confirmed_mname = $parameter['3'];
        $confirmed_dob = $parameter['4'];
        $confirmed_picture = $parameter['20'];
        $path_image = $confirmed_picture;
            
        ($mybvn == "") ? "" : mysqli_query($link, "UPDATE virtual_account SET acct_status = 'UnderReview' WHERE userid = '$acctno'");
        ($confirmed_picture == "") ? mysqli_query($link, "UPDATE borrowers SET fname = '$confirmed_fname', lname = '$confirmed_lname', mname = '$confirmed_mname', dob = '$confirmed_dob', unumber = '$mybvn' WHERE account = '$acctno'") : "";
        ($confirmed_picture != "") ? mysqli_query($link, "UPDATE borrowers SET fname = '$confirmed_fname', lname = '$confirmed_lname', mname = '$confirmed_mname', dob = '$confirmed_dob', unumber = '$mybvn', image = '$path_image' WHERE account = '$acctno'") : "";
        
        echo "<div class='alert bg-blue'>Profile Updated Successfully</div>";
	    echo '<meta http-equiv="refresh" content="2;url=docManager.php?tid='.$_SESSION['tid'].'&&acn='.$acctno.'&&mid=OTUw&&tab=tab_2">';    
        
    }
    else{

        mysqli_query($link, "UPDATE borrowers SET unumber = '$mybvn' WHERE account = '$acctno'");

        echo "<div class='alert bg-blue'>Profile Updated Successfully</div>";
	    echo '<meta http-equiv="refresh" content="2;url=docManager.php?tid='.$_SESSION['tid'].'&&acn='.$acctno.'&&mid=OTUw&&tab=tab_2">';    
        
    }
    */

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
  
        <div class="tab-pane <?php echo ($_GET['tab'] == 'tab_3') ? 'active' : ''; ?>" id="tab_3">
                 
             <div class="box-body">
          
       <form class="form-horizontal" method="post" enctype="multipart/form-data" id="sample_form">

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

                <div class="form-group">
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


                <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">Upload Signature</label>
                  <div class="col-sm-7">
                  <input name="signature_file" type="file" id="signatureFile" class="btn bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>"/>
                  <span style="color: <?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>;">Upload <b>cleared signature on a plain sheet</b></span>
                  </div>
                  <label class="col-sm-2"><button type="button" class="btn bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?> btn-flat" id="signature_upload"><i class="fa fa-upload"> Upload</i></button></label>
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
    $selectAttachment = mysqli_query($link, "SELECT * FROM attachment WHERE borrowerid = '$acctno' ORDER BY id DESC") or die (mysqli_error($link));
    if(mysqli_num_rows($selectAttachment)==0)
    {
    echo "<div class='alert bg-".(($myaltrow['theme_color'] == "") ? 'blue' : $myaltrow['theme_color'])."'>No data found yet!.....Check back later!!</div>";
    }
    else{
    while($rowAttachment = mysqli_fetch_array($selectAttachment))
    {
    $id = $rowAttachment['id'];
    ?>    
                    <tr>
                        <td><input id="optionsCheckbox" class="checkbox" name="selector[]" type="checkbox" value="<?php echo $id; ?>"></td>
                        <td><?php echo $rowAttachment['file_title']; ?></td>
                        <td><a href="<?php echo $fetchsys_config['file_baseurl'].$rowAttachment['attached_file']; ?>" class="btn bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>" target="_blank"><i class="fa fa-eyes"></i> View Attachment</a></td>
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
                  <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">BVN <?php echo ($bbvn != "" && strlen($bbvn) == "11") ? '<span style="color: '.(($myaltrow['theme_color'] == "") ? "blue" : $myaltrow['theme_color']).'">[<b>Verified</b> <i class="fa fa-check"></i>]</span>' : '<span style="color: '.(($myaltrow['alternate_color'] == "") ? "orange" : $myaltrow['alternate_color']).'">[<b>Not-Verified</b> <i class="fa fa-times"></i>]</span>'; ?></label>
                  <label class="col-sm-7">
                  <input name="cust_bvn" type="text" class="form-control" value="<?php echo $bbvn; ?>" placeholder="BVN Number Here" maxlength="11">
                  <span style="color: <?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>;"> BVN Verification cost <b style="color: <?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;"><?php echo $fetchsys_config['currency'].number_format($fetchsys_config['bvn_fee'],2,'.',','); ?></b> routed through your Super Wallet (Nigeria Account Only). </span>
                  <br>
                  <div class="scrollable">
                  <?php
                  if(isset($_POST['verifyBVN'])){
                      
                       $userBvn = mysqli_real_escape_string($link, $_POST['cust_bvn']);

                       $search_mycust = mysqli_query($link, "SELECT * FROM borrowers WHERE account = '$acctno'");
                       $fetch_mycust = mysqli_fetch_array($search_mycust);
                       $fname = $fetch_mycust['fname'];
                       $lname = $fetch_mycust['lname'];
                       $dob = $fetch_mycust['dob'];
                       $phone = $fetch_mycust['phone'];
                       $tbwallet_balance = $bwallet_balance - $bvn_fee;
                       
                       if($bwallet_balance < $bvn_fee){
           
                           echo "<br><span class='bg-orange'>Sorry! You do not have sufficient fund in your Wallet for this verification</span>";
                           
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

                                //20 array row
                                $mybvn_data = $processBVN['BVN']."|".$processBVN['FirstName']."|".$processBVN['LastName']."|".$processBVN['MiddleName']."|".$processBVN['DateOfBirth']."|".$processBVN['PhoneNumber']."|".$processBVN['Email']."|".$processBVN['EnrollmentBank']."|".$processBVN['EnrollmentBranch']."|".$processBVN['Gender']."|".$processBVN['LevelOfAccount']."|".$processBVN['LgaOfOrigin']."|".$processBVN['LgaOfResidence']."|".$processBVN['MaritalStatus']."|".$processBVN['NameOnCard']."|".$processBVN['Nationality']."|".$processBVN['StateOfOrigin']."|".$processBVN['StateOfResidence']."|".$processBVN['Title']."|".$processBVN['WatchListed']."|".$image_converted;

                                   
                                $search_bvnverify = mysqli_query($link, "SELECT * FROM bvn_log WHERE accountID = '$acctno'");
                                $fetch_bvnverify = mysqli_fetch_array($search_bvnverify);
                                $bvn_nos = mysqli_num_rows($search_bvnverify);
                                $concat = $fetch_bvnverify['mydata'];
                                $parameter = (explode('|',$concat));
                                $old_picture = $parameter[20];
                                   
                                $seach_membersttings = mysqli_query($link, "SELECT * FROM member_settings WHERE companyid = '$bbranchid'");
                                $fetch_memset = mysqli_fetch_array($seach_membersttings);
                                   
                                $update_wallet = mysqli_query($link, "UPDATE borrowers SET wallet_balance = '$tbwallet_balance' WHERE account = '$acctno'");
                                   
                                ($bvn_nos == 1 && $old_picture != "") ? unlink($fetchsys_config['file_baseurl'].$old_picture) : "";
                                ($bvn_nos == 1) ? $update_bvn = mysqli_query($link, "UPDATE bvn_log SET mydata = '$mybvn_data' WHERE accountID = '$acctno'") : $insert_bvn = mysqli_query($link, "INSERT INTO bvn_log VALUE(null,'$bbranchid','$bsbranchid','$acctno','$bAcctOfficer','$mybvn_data','$bvn_fee','$wallet_date_time','$rOrderID')");
                                   
                                $insert_income = mysqli_query($link, "INSERT INTO wallet_history VALUE(null,'$bbranchid','$rOrderID','$userBvn','','$bvn_fee','Debit','$bbcurrency','BVN_Charges','Response: $bbcurrency.$bvn_fee was charged for Customer BVN Verification','successful','$wallet_date_time','$acctno','$tbwallet_balance','')");
                                   
                                $insert_income = mysqli_query($link, "INSERT INTO income VALUES(null,'','$icm_id','BVN','$bvn_fee','$date_time','Customer BVN Verification Charges')");
                                   
                                (($bvn_lname == $lname || $bvn_lname == strtoupper($lname)) && $bvn_dob == $dob) ? mysqli_query($link, "UPDATE borrowers SET unumber = '$bvn' WHERE account = '$acctno'") : "";
                                (($bvn_lname == $lname || $bvn_lname == strtoupper($lname)) && $bvn_dob == $dob) ? mysqli_query($link, "UPDATE virtual_account SET acct_status = 'UnderReview' WHERE userid = '$acctno'") : "";

                                echo (($bvn_lname == $lname || $bvn_lname == strtoupper($lname)) && $bvn_dob == $dob) ? '<p style="color: blue;"><b>Data Verified Successfully!</b> <i class="fa fa-check"></i></p>' : '<p style="color: orange;"><b>Data Verification Not Successful! Please try with correct BVN that Match your Information on our System</b> <i class="fa fa-times"></i></p>';
                                   
                                echo '<meta http-equiv="refresh" content="10;url=docManager.php?tid='.$_SESSION['tid'].'&&acn='.$_SESSION['acctno'].'&&mid='.base64_encode("950").'&&tab=tab_2">';

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
                $lookup_bvn = mysqli_query($link, "SELECT * FROM bvn_log WHERE accountID = '$acctno'");
                $fetch_lookupbvn = mysqli_fetch_array($lookup_bvn);
                $vry_bvn_num = mysqli_num_rows($lookup_bvn);
                if($vry_bvn_num == 1)
                {
                ?>
                <hr>
                <div align="center">
                    <img src="<?php echo $fetchsys_config['file_baseurl']; ?>bvn_logo.png" height="75" width="200"/>
                    <p><b>Last BVN Verification on <?php echo date("Y-m-d", strtotime($fetch_lookupbvn['date_time'])); ?></b></p>
                    <a href="bvnInfo.php?tid=<?php echo $_SESSION['tid']; ?>&&acn=<?php echo $acctno; ?>&&mid=OTUw&&ref=<?php echo $fetch_lookupbvn['ref']; ?>" target="_blank"><b>Click here</b></a> to print your BVN Page
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
    $mybvn = mysqli_real_escape_string($link, $_POST['cust_bvn']);
    
    $search_Otp = mysqli_query($link, "SELECT * FROM bvn_log WHERE accountID = '$acctno' ORDER BY id DESC");
    if(mysqli_num_rows($search_Otp) >= 1)
    {
        $fetch_otp = mysqli_fetch_array($search_Otp);
        $concat = $fetch_otp['mydata'];
        $parameter = (explode('|',$concat));
        $confirmed_fname = $parameter['1'];
        $confirmed_lname = $parameter['2'];
        $confirmed_mname = $parameter['3'];
        $confirmed_dob = $parameter['4'];
        $confirmed_picture = $parameter['20'];
        $path_image = $confirmed_picture;
            
        ($mybvn == "") ? "" : mysqli_query($link, "UPDATE virtual_account SET acct_status = 'UnderReview' WHERE userid = '$acctno'");
        ($confirmed_picture == "") ? mysqli_query($link, "UPDATE borrowers SET fname = '$confirmed_fname', lname = '$confirmed_lname', mname = '$confirmed_mname', dob = '$confirmed_dob', unumber = '$mybvn' WHERE account = '$acctno'") : "";
        ($confirmed_picture != "") ? mysqli_query($link, "UPDATE borrowers SET fname = '$confirmed_fname', lname = '$confirmed_lname', mname = '$confirmed_mname', dob = '$confirmed_dob', unumber = '$mybvn', image = '$path_image' WHERE account = '$acctno'") : "";
        
        echo "<div class='alert bg-blue'>Profile Updated Successfully</div>";
	    echo '<meta http-equiv="refresh" content="2;url=docManager.php?tid='.$_SESSION['tid'].'&&acn='.$acctno.'&&mid=OTUw&&tab=tab_2">';    
        
    }
    else{

        mysqli_query($link, "UPDATE borrowers SET unumber = '$mybvn' WHERE account = '$acctno'");

        echo "<div class='alert bg-blue'>Profile Updated Successfully</div>";
	    echo '<meta http-equiv="refresh" content="2;url=docManager.php?tid='.$_SESSION['tid'].'&&acn='.$acctno.'&&mid=OTUw&&tab=tab_2">';    
        
    }

}
?>
      
       </form>
        </div>  
        </div>
        <!-- /.tab-pane --> 

<?php
  }
  elseif($tab == 'tab_4')
  {
  ?>
  
        <div class="tab-pane <?php echo ($_GET['tab'] == 'tab_4') ? 'active' : ''; ?>" id="tab_4">
                 
             <div class="box-body">
          
       <form class="form-horizontal" method="post" enctype="multipart/form-data" id="sample_form">

            <div class="box-body">
                 
                 


                
                <?php
                $lookup_bvn = mysqli_query($link, "SELECT * FROM bvn_log WHERE accountID = '$acctno'");
                $fetch_lookupbvn = mysqli_fetch_array($lookup_bvn);
                $vry_bvn_num = mysqli_num_rows($lookup_bvn);
                if($vry_bvn_num == 1)
                {
                ?>
                <hr>
                <div align="center">
                    <img src="<?php echo $fetchsys_config['file_baseurl']; ?>bvn_logo.png" height="75" width="200"/>
                    <p><b>Last BVN Verification on <?php echo date("Y-m-d", strtotime($fetch_lookupbvn['date_time'])); ?></b></p>
                    <a href="bvnInfo.php?tid=<?php echo $_SESSION['tid']; ?>&&acn=<?php echo $acctno; ?>&&mid=OTUw&&ref=<?php echo $fetch_lookupbvn['ref']; ?>" target="_blank"><b>Click here</b></a> to print your BVN Page
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
    

}
?>
      
       </form>
        </div>  
        </div>
        <!-- /.tab-pane -->
        
        
<?php
  }
  elseif($tab == 'tab_5')
  {
  ?>
  
        <div class="tab-pane <?php echo ($_GET['tab'] == 'tab_5') ? 'active' : ''; ?>" id="tab_5">
                 
             <div class="box-body">
          
           <form class="form-horizontal" method="post" enctype="multipart/form-data">
<?php   
if (isset($_POST['save_nok'])){
    
    $id = $_SESSION['tid'];
    $nok = mysqli_real_escape_string($link, $_POST['nok']);
	$nok_rela = mysqli_real_escape_string($link, $_POST['nok_rela']);
    $nok_phone = mysqli_real_escape_string($link, $_POST['nok_phone']);
    $nok_addrs = mysqli_real_escape_string($link, $_POST['nok_addrs']);
    $name_of_trustee = mysqli_real_escape_string($link, $_POST['name_of_trustee']);
    
    $update = mysqli_query($link,"UPDATE borrowers SET nok='$nok',nok_rela='$nok_rela',nok_phone='$nok_phone',nok_addrs='$nok_addrs',name_of_trustee='$name_of_trustee' WHERE id ='$id'")or die(mysqli_error()); 
									
	echo "<div class='alert bg-blue'>Profile Updated Successfully</div>";
	echo '<meta http-equiv="refresh" content="2;url=docManager.php?tid='.$_SESSION['tid'].'&&acn='.$acctno.'&&mid=OTUw&&tab=tab_3">';
    
}
?>
                <div class="box-body">
                    
                <div class="form-group">
                          <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Next of Kin</label>
                          <div class="col-sm-10">
                          <input name="nok" type="text" class="form-control" value="<?php echo $row['nok']; ?>" placeholder="Next of Kin" required>
                          </div>
                          </div>   
                
                <div class="form-group">
                          <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Relationship</label>
                          <div class="col-sm-10">
                          <input name="nok_rela" type="text" class="form-control" value="<?php echo $row['nok_rela']; ?>" placeholder="Next of Kin" required>
                          </div>
                          </div> 
                         
                <div class="form-group">
                          <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Phone</label>
                          <div class="col-sm-10">
                          <input name="nok_phone" type="text" class="form-control" value="<?php echo $row['nok_phone']; ?>" placeholder="Next of Kin Phone Number">
                          </div>
                          </div>
                
                <div class="form-group">
                    <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">Address</label>
                    <div class="col-sm-10">
                        <textarea name="nok_addrs" class="form-control" rows="2" cols="80" required><?php echo $row ['nok_addrs'];?></textarea>
                    </div>
                </div>

                <div class="form-group">
                          <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Name of Trustee</label>
                          <div class="col-sm-10">
                          <input name="name_of_trustee" type="text" class="form-control" value="<?php echo $row['name_of_trustee']; ?>" placeholder="Next of Kin Phone Number">
                          <span><i>(for beneficiaries below 18 years)</i></span>
                          </div>
                          </div>
                     
                </div> 
                
            <div align="right">
              <div class="box-footer">
                	<button name="save_nok" type="submit" class="btn bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>"><i class="fa fa-save">&nbsp;Save</i></button>
              </div>
			</div>
			
           </form>
            </div>  
        </div>
        <!-- /.tab-pane --> 
        
        
<?php
  }
  elseif($tab == 'tab_6')
  {
  ?>
  
        <div class="tab-pane <?php echo ($_GET['tab'] == 'tab_6') ? 'active' : ''; ?>" id="tab_6">
                 
             <div class="box-body">
          
           <form class="form-horizontal" method="post" enctype="multipart/form-data">
<?php   
if(isset($_POST['save_emp'])){
    
    $id = $_SESSION['tid'];
    $occupation = $_POST['occupation'];
	$employer = $_POST['employer'];

    $update = mysqli_query($link,"UPDATE borrowers SET occupation='$occupation',employer='$employer' WHERE id ='$id'")or die(mysqli_error()); 
									
	echo "<div class='alert bg-blue'>Profile Updated Successfully</div>";
	echo '<meta http-equiv="refresh" content="2;url=docManager.php?tid='.$_SESSION['tid'].'&&acn='.$acctno.'&&mid=OTUw&&tab=tab_4">';

}
?>
                <div class="box-body">
                    
                    <div class="form-group">
                          <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Occupation</label>
                          <div class="col-sm-10">
                          <input name="occupation" type="text" class="form-control" value="<?php echo $row['occupation']; ?>" placeholder="Occupation">
                          </div>
                          </div>  
                          
                    <div class="form-group">
                          <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Employer's Name</label>
                          <div class="col-sm-10">
                          <input name="employer" type="text" class="form-control" value="<?php echo $row['employer']; ?>" placeholder="Employer">
                          </div>
                          </div>
                          
                </div>
                
                <div align="right">
                  <div class="box-footer">
                    	<button name="save_emp" type="submit" class="btn bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>"><i class="fa fa-save">&nbsp;Save</i></button>
                  </div>
    			</div>

            </form>
            </div>  
        </div>
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