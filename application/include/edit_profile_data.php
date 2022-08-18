<div class="box">
        
	
	       <div class="box-body">
			<div class="panel panel-success">
            <div class="panel-heading bg-blue">
            <h3 class="panel-title"><i class="fa fa-gear"></i>&nbsp;Update Profile Status</h3>
            </div>
             <div class="box-body">
<?php   
			  				if (isset($_POST['save'])) 
								
								{
								$id= $_POST['id'];
                                $fname = $_POST['fname'];
                                $idp = $_POST['idp'];
                                $email = $_POST['email'];
							    $number = $_POST['number'];
                                $add1 = $_POST['ad1'];
                                $add2 = $_POST['ad2'];
                                $city = $_POST['city'];
                                $state = $_POST['state'];
								$zip = $_POST['zip'];
								$tpin = $_POST['tpin'];
                                //$country = $_POST['country'];
                                $user = $_POST['user'];
								$password = $_POST['password'];	
								$decript = base64_encode($password)	;					 	
                                //image
                                $image = addslashes(file_get_contents($_FILES['image']['tmp_name']));
                                if($image == "")
								{
									mysqli_query($link,"UPDATE user SET name='$fname',email='$email',phone='$number',addr1='$add1',addr2='$add2',
													city='$city',state='$state',zip='$zip',username='$user',password='$decript',id='$idp',tpin='$tpin' WHERE id ='$id'")or die(mysqli_error()); 
									echo "<script>window.location='profile.php';</script>";
								}
								else{
								$target_dir = "../img/";
								$target_file = $target_dir.basename($_FILES["image"]["name"]);
								$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
								$check = getimagesize($_FILES["image"]["tmp_name"]);
								
								if($check == false)
								{
									echo '<meta http-equiv="refresh" content="2;url=profile.php?tid='.$id.'">';
									echo '<br>';
									echo'<span class="itext" style="color: orange;">Invalid file type</span>';
								}
								elseif($_FILES["image"]["size"] > 500000000)
								{
									echo '<meta http-equiv="refresh" content="2;url=profile.php?tid='.$id.'">';
									echo '<br>';
									echo'<span class="itext" style="color: orange;">Image must not more than 500KB!</span>';
								}
								elseif($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif")
								{
									echo '<meta http-equiv="refresh" content="2;url=profile.php?tid='.$id.'">';
									echo '<br>';
									echo'<span class="itext" style="color: orange;">Sorry, only JPG, JPEG, PNG & GIF Files are allowed.</span>';
								}
								else{
									$sourcepath = $_FILES["image"]["tmp_name"];
									$targetpath = "../img/" . $_FILES["image"]["name"];
									move_uploaded_file($sourcepath,$targetpath);
									
									$location = $_FILES['image']['name'];				
					
					mysqli_query($link,"UPDATE user SET name='$fname',email='$email',phone='$number',addr1='$add1',addr2='$add2',
													city='$city',state='$state',zip='$zip',username='$user',password='$decript',id='$idp',
													image='$location',tpin='$tpin' WHERE id ='$id'")or die(mysqli_error()); 
									include("alert_sender/edit_profile_alert.php");
									echo "<script>window.location='profile.php';</script>";
									}
								}
								}
							?>
			 <?php 
			 $id = $_SESSION['tid'];
			$call = mysqli_query($link, "SELECT * FROM user WHERE id='$id'");
			while($row = mysqli_fetch_assoc($call))
			{
			?>
               
			  <form class="form-horizontal" method="post" enctype="multipart/form-data">
			 <input type="hidden" value="<?php echo $row ['id']; ?>" name="id"  id="" required>

             <div class="box-body">
				
			<div class="form-group">
            <label for="" class="col-sm-2 control-label" style="color: blue;">Your Image</label>
			<div class="col-sm-10">
  		  			 <input type='file' name="image" onChange="readURL(this);" />
       				 <img id="blah"  src="<?php echo $fetchsys_config['file_baseurl'].$row['image'];?>" alt="Upload Image Here" height="100" width="100"/>
			</div>
			</div>
			
			<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Full Name</label>
                  <div class="col-sm-10">
                  <input name="fname" type="text" class="form-control" value="<?php echo $row ['name'];?>">
                  </div>
                  </div>
				  
				  <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color: blue;">ID</label>
                  <div class="col-sm-10">
                  <input name="idp" type="text" class="form-control" value="<?php echo $row ['id'];?>" readonly="readonly">
                  </div>
                  </div>
				  
				  
		<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Email</label>
                  <div class="col-sm-10">
                  <input type="email" name="email" type="text" class="form-control" value="<?php echo $row ['email'];?>">
                  </div>
                  </div>
				  
		<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Mobile Number</label>
                  <div class="col-sm-10">
                  <input name="number" type="text" class="form-control" value="<?php echo $row ['phone'];?>">
                  </div>
                  </div>
				  
				  
		 <div class="form-group">
                  	<label for="" class="col-sm-2 control-label" style="color:blue;">Address 1</label>
                  	<div class="col-sm-10">
					<textarea name="ad1"  class="form-control" rows="4" cols="80"><?php echo $row ['addr1'];?></textarea>
           			 </div>
          </div>
					
			<div class="form-group">
                  	<label for="" class="col-sm-2 control-label" style="color:blue;">Address 2</label>
                  	<div class="col-sm-10">
					<textarea name="ad2"  class="form-control" rows="4" cols="80"><?php echo $row ['addr2'];?></textarea>
           			 </div>
          	</div>
			
			
			<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">City</label>
                  <div class="col-sm-10">
                  <input name="city" type="text" class="form-control" value="<?php echo $row ['city'];?>" required>
                  </div>
                  </div>
				  
		<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">State</label>
                  <div class="col-sm-10">
                  <input name="state" type="text" class="form-control"value="<?php echo $row ['state'];?>" required>
                  </div>
                  </div>
				  
				  <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Zip Code</label>
                  <div class="col-sm-10">
                  <input name="zip" type="text" class="form-control" value="<?php echo $row ['zip'];?>">
                  </div>
                  </div>
			
<hr>	
<div class="bg-orange">&nbsp;SECURITY INFORMATION</div>
<hr>	
					
					 <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Username</label>
                  <div class="col-sm-10">
                  <input name="user" type="text" class="form-control" value="<?php echo $row ['username'];?>" required readonly>
                  </div>
                  </div>
				  
				   <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Password</label>
                  <div class="col-sm-10">
                  <input name="password" type="password" class="form-control" value="<?php echo base64_decode($row ['password']); ?>" required>
                  </div>
                  </div>
				  
				  <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Transaction Pin</label>
                  <div class="col-sm-10">
                  <input name="tpin" type="password" class="form-control" value="<?php echo $row['tpin']; ?>" required>
                  </div>
                  </div>
				  
			 </div>
			 
			  <div align="right">
              <div class="box-footer">
                				<button name="save" type="submit" class="btn bg-blue"><i class="fa fa-save">&nbsp;Save</i></button>

              </div>
			  </div>
			 </form> 
			  <?php }?>



</div>	
</div>
</div>
</div>