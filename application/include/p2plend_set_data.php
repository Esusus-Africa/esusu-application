<div class="box">	
	       <div class="box-body">
			<div class="panel panel-success">
            <div class="panel-heading bg-blue">
            <h3 class="panel-title"><i class="fa fa-gear"></i>&nbsp;P2p-Lending Setup</h3>
            </div>
             <div class="box-body">

			 <?php 
			$call = mysqli_query($link, "SELECT * FROM lending_setup");
			while($row = mysqli_fetch_assoc($call))
			{
			?>
               
			 <form class="form-horizontal" method="post" enctype="multipart/form-data">
			<input type="hidden" value="<?php echo $row ['sysid']; ?>" name="sysid">

             <div class="box-body">
				
			<div class="form-group">
            <label for="" class="col-sm-2 control-label" style="color:blue;">Top Menu Logo</label>
			<div class="col-sm-10">
  		  			 <input type='file' name="image" onChange="readURL(this);" class="alert bg-orange">
       				 <img id="blah"  src="../lend/images/logo/<?php echo $row['top_menu_logo']; ?>" alt="Top Menu Logo Here" height="60" width="200"/>
			</div>
			</div>

            <div class="form-group">
						<label for="" class="col-sm-2 control-label" style="color: blue;">Footer Logo</label>
						<div class="col-sm-10">
								 <input type='file' name="image2" class="alert bg-orange">
								 <img src="../lend/images/logo/<?php echo $row ['footer_logo']; ?>" alt="Footer Logo Here" height="60" width="200"/>
						</div>
						</div>
			
			<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Platform Name</label>
                  <div class="col-sm-10">
                  <input name="fname" type="text" class="form-control" value="<?php echo $row ['name']; ?>" required>
                  </div>
                  </div>
				  
		<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Local Line</label>
                  <div class="col-sm-10">
                  <input name="number" type="text" class="form-control" value="<?php echo $row ['mobile']; ?>" required>
                  </div>
                  </div>
                  
        <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">International No.</label>
                  <div class="col-sm-10">
                  <input name="intl_number" type="text" class="form-control" value="<?php echo $row ['intl_number']; ?>" required>
                  </div>
                  </div>
				  
		<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Support Email</label>
                  <div class="col-sm-10">
                  <input type="email" name="email" type="text" class="form-control" value="<?php echo $row['email']; ?>">
                  </div>
                  </div>
				  
				  <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Company Title</label>
                  <div class="col-sm-10">
                  <input type="text" name="title" type="text" class="form-control" value="<?php echo $row['title']; ?>">
                  </div>
                  </div>
				  
				   <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Company Abbreviation</label>
                  <div class="col-sm-10">
                  <input type="text" name="lend_abb" type="text" class="form-control" value="<?php echo $row['lend_abb']; ?>">
                  </div>
                  </div>

                  <div class="form-group">
                  	<label for="" class="col-sm-2 control-label" style="color:blue;">Short Description</label>
                  	<div class="col-sm-10">
					<textarea name="short_desc" class="form-control" rows="2" cols="80"><?php echo $row['short_desc']; ?></textarea>
           			 </div>
                  </div>
                  
                  <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Facebook Page URL</label>
                  <div class="col-sm-10">
                  <input type="text" name="fb_link" type="text" class="form-control" value="<?php echo $row['fb_link']; ?>">
                  </div>
                  </div>
                  
                  <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Twitter URL</label>
                  <div class="col-sm-10">
                  <input type="text" name="twitter_link" type="text" class="form-control" value="<?php echo $row['twitter_link']; ?>">
                  </div>
                  </div>
                  
                  <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">LinkedIn URL</label>
                  <div class="col-sm-10">
                  <input type="text" name="linkedin_link" type="text" class="form-control" value="<?php echo $row['linkedin_link']; ?>">
                  </div>
                  </div>

        <div class="form-group">
		                  <label for="" class="col-sm-2 control-label" style="color:blue;">Currency</label>
		                  <div class="col-sm-10">
						<select name="currency"  class="form-control" required>
							<option value="<?php echo $row['currency']; ?>"><?php echo $row['currency']; ?></option>
							<option value="NGN">NGN</option>
							<option value="USD">USD</option>
							<option value="EUR">EUR</option>
							<option value="GBP">GBP</option>
							<option value="UGX">UGX</option>
							<option value="TZS">TZS</option>
							<option value="GHS">GHS</option>
							<option value="KES">KES</option>
							<option value="ZAR">ZAR</option>
						</select>                 
						</div>
		                </div>
				  
				  
		 <div class="form-group">
                  	<label for="" class="col-sm-2 control-label" style="color:blue;">Company Address</label>
                  	<div class="col-sm-10">
					<textarea name="address" class="form-control" rows="2" cols="80"><?php echo $row['address']; ?></textarea>
           			 </div>
          </div>
		                
				
<hr>	
<div class="bg-orange">&nbsp;<b> CHARGES TO BE DEDUCTED FROM BORROWERS ACCOUNT BY THE SYSTEM AFTER DISBURSEMENT</b></div>
<hr>

                <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Service Charge (%)</label>
                  <div class="col-sm-10">
                  <input name="service_charge" type="text" class="form-control" value="<?php echo $row['service_charge']; ?>"required>
                  </div>
                </div>
									 
			 </div>
			 
			  <div align="right">
              <div class="box-footer">
                				<button name="save" type="submit" class="btn bg-blue"><i class="fa fa-upload">&nbsp;Update Now</i></button>

              </div>
			  </div>
			 <?php 
			 }
			 ?>
			 </form> 
			 <?php   
									if(isset($_POST['save'])) 
									{
										$id= $_POST['sysid'];
										$fname = $_POST['fname'];
										$number = $_POST['number'];
										$intl_number = $_POST['intl_number'];
                                        $email = $_POST['email'];
										$title = $_POST['title'];
										$lend_abb = $_POST['lend_abb'];
										$currency = $_POST['currency'];
                                        $address = $_POST['address'];
                                        $short_desc = $_POST['short_desc'];
                                        $service_charge = $_POST['service_charge'];
                                        $fb_link = $_POST['fb_link'];
                                        $twitter_link = $_POST['twitter_link'];
                                        $linkedin_link = $_POST['linkedin_link'];
										
										//this handles uploading of rentals image
										$image = addslashes(file_get_contents($_FILES['image']['tmp_name']));
                                        $image2 = addslashes(file_get_contents($_FILES['image2']['tmp_name']));
                                        										
										if($image == "" && $image2 == "")
										{
							mysqli_query($link,"UPDATE lending_setup SET name='$fname',mobile='$number',intl_number='$intl_number',email='$email',title='$title',lend_abb='$lend_abb',currency='$currency',address='$address',short_desc='$short_desc',service_charge='$service_charge',fb_link='$fb_link',twitter_link='$twitter_link',linkedin_link='$linkedin_link' WHERE sysid ='$id'")or die(mysqli_error()); 
											echo "<script>alert('System Configured Successfully!'); </script>";
											echo "<script>window.location='p2plend_set.php?id=".$_SESSION['tid']."&&mid=".base64_encode("411")."';</script>";
										}elseif($image != "")
										{
											$target_dir = "../lend/images/logo/";
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
												$targetpath = "../lend/images/logo/" . $_FILES["image"]["name"];
												move_uploaded_file($sourcepath,$targetpath);
												
												$top_menu_logo = $_FILES["image"]["name"];
							
							mysqli_query($link,"UPDATE lending_setup SET name='$fname',mobile='$number',intl_number='$intl_number',email='$email',title='$title',lend_abb='$lend_abb',currency='$currency',address='$address',short_desc='$short_desc',service_charge='$service_charge',top_menu_logo='$top_menu_logo',fb_link='$fb_link',twitter_link='$twitter_link',linkedin_link='$linkedin_link' WHERE sysid ='$id'")or die(mysqli_error()); 
											echo "<script>alert('System Configured Successfully!'); </script>";
											echo "<script>window.location='p2plend_set.php?id=".$_SESSION['tid']."&&mid=".base64_encode("411")."';</script>";
											}
										}elseif($image2 != ""){
											
											$target_dir2 = "../lend/images/logo/";
											$target_file2 = $target_dir2.basename($_FILES["image2"]["name"]);
											$imageFileType2 = pathinfo($target_file2,PATHINFO_EXTENSION);
											$check2 = getimagesize($_FILES["image2"]["tmp_name"]);
											if($check2 == false)
											{
												echo "<p style='font-size:24px; color:orange'>Invalid file type</p>";
											}
											elseif($_FILES["image2"]["size"] > 500000)
											{
												echo "<p style='font-size:24px; color:orange'>Image must not more than 500KB!</p>";
											}
											elseif($imageFileType2 != "jpg" && $imageFileType2 != "png" && $imageFileType2 != "jpeg" && $imageFileType2 != "gif")
											{
												echo "<p style='font-size:24px; color:orange'>Sorry, only JPG, JPEG, PNG & GIF Files are allowed for the Stamp.</p>";
											}
											else{
												$sourcepath2 = $_FILES["image2"]["tmp_name"];
												$targetpath2 = "../lend/images/logo/" . $_FILES["image2"]["name"];
												move_uploaded_file($sourcepath2,$targetpath2);
												
												$footer_logo = $_FILES["image2"]["name"];
												mysqli_query($link,"UPDATE lending_setup SET name='$fname',mobile='$number',intl_number='$intl_number',email='$email',title='$title',lend_abb='$lend_abb',currency='$currency',address='$address',short_desc='$short_desc',service_charge='$service_charge',footer_logo='$footer_logo',fb_link='$fb_link',twitter_link='$twitter_link',linkedin_link='$linkedin_link' WHERE sysid ='$id'")or die(mysqli_error()); 
											echo "<script>alert('System Configured Successfully!'); </script>";
											echo "<script>window.location='p2plend_set.php?id=".$_SESSION['tid']."&&mid=".base64_encode("411")."';</script>";
												
                                            }
                                        }
                                        else{
											$target_dir = "../lend/images/logo/";
											$target_file = $target_dir.basename($_FILES["image"]["name"]);
											$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
											$check = getimagesize($_FILES["image"]["tmp_name"]);

                                            $target_dir2 = "../lend/images/logo/";
											$target_file2 = $target_dir2.basename($_FILES["image2"]["name"]);
											$imageFileType2 = pathinfo($target_file2,PATHINFO_EXTENSION);
											$check2 = getimagesize($_FILES["image2"]["tmp_name"]);
                                            if($check == false && $check2 == false)
                                            {
                                                echo "<p style='font-size:24px; color:orange'>Invalid file type</p>";
                                            }
                                            elseif(($_FILES["image"]["size"] > 500000000) || ($_FILES["image2"]["size"] > 500000000))
                                            {
                                                echo "<p style='font-size:24px; color:orange'>Image must not more than 500KB!</p>";
                                            }
                                            elseif($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif")
                                            {
                                                echo "<p style='font-size:24px; color:orange'>Sorry, only JPG, JPEG, PNG & GIF Files are allowed for the Top Menu Logo.</p>";
											}
											elseif($imageFileType2 != "jpg" && $imageFileType2 != "png" && $imageFileType2 != "jpeg" && $imageFileType2 != "gif")
                                            {
                                                echo "<p style='font-size:24px; color:orange'>Sorry, only JPG, JPEG, PNG & GIF Files are allowed for the Footer Logo.</p>";
                                            }
                                            else{
                                                $sourcepath = $_FILES["image"]["tmp_name"];
                                                $targetpath = "../lend/images/logo/" . $_FILES["image"]["name"];
												move_uploaded_file($sourcepath,$targetpath);
												
												$sourcepath2 = $_FILES["image2"]["tmp_name"];
                                                $targetpath2 = "../lend/images/logo/" . $_FILES["image2"]["name"];
                                                move_uploaded_file($sourcepath2,$targetpath2);
                                                
                                                $top_menu_logo = $_FILES["image"]["name"];
                                                $footer_logo = $_FILES["image2"]["name"];
                                                
												mysqli_query($link,"UPDATE lending_setup SET name='$fname',mobile='$number',intl_number='$intl_number',email='$email',title='$title',lend_abb='$lend_abb',currency='$currency',address='$address',short_desc='$short_desc',service_charge='$service_charge',top_menu_logo='$top_menu_logo',footer_logo='$footer_logo',fb_link='$fb_link',twitter_link='$twitter_link',linkedin_link='$linkedin_link' WHERE sysid ='$id'")or die(mysqli_error()); 
												echo "<script>alert('System Configured Successfully!'); </script>";
												echo "<script>window.location='p2plend_set.php?id=".$_SESSION['tid']."&&mid=".base64_encode("411")."';</script>";															
                                            }
                                        }
									}
									?>


</div>	
</div>
</div>	
</div>