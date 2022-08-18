<div class="box">	
	       <div class="box-body">
			<div class="panel panel-success">
            <div class="panel-heading bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">
            <h3 class="panel-title"><i class="fa fa-gear"></i>&nbsp;Company Setup</h3>
            </div>
             <div class="box-body">

			 <?php 
			$call = mysqli_query($link, "SELECT * FROM institution_data WHERE id = '$session_id'");
			while($row = mysqli_fetch_assoc($call))
			{
			?>
               
			 <form class="form-horizontal" method="post" enctype="multipart/form-data">
			<input type="hidden" value="<?php echo $row ['sysid']; ?>" name="sysid">
             <div class="box-body">
				
			<div class="form-group">
            <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Company Logo</label>
			<div class="col-sm-10">
  		  			 <input type='file' name="image" class="btn bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>" onChange="readURL(this);">
       				 <img id="blah"  src="../<?php echo $row['institution_logo']; ?>" alt="System Logo Here" height="100" width="100"/>
			</div>
			</div>
			
			<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Company Name</label>
                  <div class="col-sm-10">
                  <input name="fname" type="text" class="form-control" value="<?php echo $row ['institution_name']; ?>" required>
                  </div>
                  </div>
				  
		<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Company Phone</label>
                  <div class="col-sm-10">
                  <input name="number" type="text" class="form-control" value="<?php echo $row ['official_phone']; ?>" required>
                  </div>
                  </div>
				  
		<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Company Email</label>
                  <div class="col-sm-10">
                  <input type="email" name="email" type="text" class="form-control" value="<?php echo $row ['official_email']; ?>">
                  </div>
                  </div>			  
				  
		 <div class="form-group">
                  	<label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Company Address</label>
                  	<div class="col-sm-10">
					<textarea name="address" class="form-control" rows="4" cols="80"><?php echo $row ['location']; ?></textarea>
           			 </div>
          </div>
				
<hr>	
<div class="bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>">&nbsp;<b> CHARGES TO BE DEDUCTED FROM USERS ACCOUNT BY THE SYSTEM </b></div>
<hr>
						
				<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">SMS Charges(Monthly)</label>
                  <div class="col-sm-10">
                  <input name="sms_charges" type="number" class="form-control" value="<?php echo $row ['sms_charges']; ?>"required >
                  </div>
                  </div>
				  
				  
				<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Withdrawal Charges</label>
                  <div class="col-sm-10">
                  <input name="withdrawal_fee" type="number" class="form-control" value="<?php echo $row ['withdrawal_charges']; ?>"required >
                  </div>
                </div>
				
<hr>	
<div class="bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>">&nbsp;<b> PAYSTACK SETTINGS (TO ACCEPT VISA / MASTERCARD / VERVE CARD FOR LOAN PAYMENT FROM USER END) </b></div>
<hr>
				
				<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Secret Key</label>
                  <div class="col-sm-10">
                  <input name="secret_key" type="text" class="form-control" value="<?php echo $row ['paystack_secretkey']; ?>"required >
                  </div>
                </div>
				
				<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Public Key</label>
                  <div class="col-sm-10">
                  <input name="public_key" type="text" class="form-control" value="<?php echo $row ['paystack_publickey']; ?>"required >
                  </div>
                </div>
									 
			 </div>
			 
			  <div align="right">
              <div class="box-footer">
                				<button name="save" type="submit" class="btn bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>"><i class="fa fa-upload">&nbsp;Update Now</i></button>

              </div>
			  </div>
			 <?php 
			 }
			 ?>
			 </form> 
			 <?php   
			  					if(isset($_POST['save'])) 
								{
									try{
										$id= $_GET['id'];
										$fname = $_POST['fname'];
										$number = $_POST['number'];
										$email = $_POST['email'];
										$pemail = $_POST['pemail'];
										//$title = $_POST['title'];
										//$footer = $_POST['footer'];
										//$abb = $_POST['abb'];
										//$currency = $_POST['currency'];
										$address = $_POST['address'];
										//$fax = $_POST['fax'];
										//$website = $_POST['website'];
										//$map = $_POST['map'];
										//$timezone = $_POST['timezone'];
										$sms_charges = $_POST['sms_charges'];
										$withdrawal_fee = $_POST['withdrawal_fee'];
										//$subaccount_charges = $_POST['subaccount_charges'];
										$auth_charges = "50";
										//$campaign_fee = $_POST['campaign_fee'];
										//$theme_color = $_POST['theme_color'];
										//$min_interest_rate = $_POST['min_interest_rate'];
										//$max_interest_rate = $_POST['max_interest_rate'];
										$secret_key = $_POST['secret_key'];
										$public_key = $_POST['public_key'];
										
										//this handles uploading of rentals image
										$image = addslashes(file_get_contents($_FILES['image']['tmp_name']));
										//$image2 = addslashes(file_get_contents($_FILES['image2']['tmp_name']));

										//$image3 = addslashes(file_get_contents($_FILES['image3']['tmp_name']));
										
										if($sms_charges < 0){
											throw new UnexpectedValueException();
										}
										if($withdrawal_fee < 0){
											throw new UnexpectedValueException();
										}
										elseif($image == "")
										{
							mysqli_query($link,"UPDATE institution_data SET institution_name='$fname',official_phone='$number',official_email='$email',location='$address',sms_charges='$sms_charges',withdrawal_charges='$withdrawal_fee',auth_charges='$auth_charges',paystack_secretkey='$secret_key',paystack_publickey='$public_key' WHERE id ='$id'")or die(mysqli_error()); 
											echo "<script>alert('System Configured Successfully!'); </script>";
											echo "<script>window.location='system_set.php?id=".$_SESSION['tid']."&&mid=".base64_encode("411")."';</script>";
										}elseif($image != "")
										{
											$target_dir = "../img/";
											$target_file = $target_dir.basename($_FILES["image"]["name"]);
											$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
											$check = getimagesize($_FILES["image"]["tmp_name"]);
											if($check == false)
											{
												echo "<p style='font-size:24px; color:orange'>Invalid file type</p>";
											}
											elseif($_FILES["image"]["size"] > 10000000)
											{
												echo "<p style='font-size:24px; color:orange'>Image must not more than 500KB!</p>";
											}
											elseif($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif")
											{
												echo "<p style='font-size:24px; color:orange'>Sorry, only JPG, JPEG, PNG & GIF Files are allowed for the System Logo.</p>";
											}
											else{
												$sourcepath = $_FILES["image"]["tmp_name"];
												$targetpath = "../img/" . $_FILES["image"]["name"];
												move_uploaded_file($sourcepath,$targetpath);

												$location = "img/". $_FILES["image"]["name"];
							
							mysqli_query($link,"UPDATE systemset SET image='$location',institution_name='$fname',official_phone='$number',official_email='$email',location='$address',sms_charges='$sms_charges',withdrawal_charges='$withdrawal_fee',auth_charges='$auth_charges',paystack_secretkey='$secret_key',paystack_publickey='$public_key' WHERE id ='$id'")or die(mysqli_error()); 
											echo "<script>alert('System Configured Successfully!'); </script>";
											echo "<script>window.location='system_set.php?id=".$_SESSION['tid']."&&mid=".base64_encode("411")."';</script>";
											}
										}
									}catch(UnexpectedValueException $ex)
									{
										echo "<div class='alert alert-danger'>Invalid Amount Entered! (avoid entering negative number like -20, -50 etc.) </div>";
									}
								}
								?>


</div>	
</div>
</div>	
</div>