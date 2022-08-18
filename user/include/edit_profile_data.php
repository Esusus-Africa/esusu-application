<div class="box">
        
	
	       <div class="box-body">
			<div class="panel panel-success">
            <div class="panel-heading bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">
            <h3 class="panel-title"><i class="fa fa-gear"></i>&nbsp;Update Profile Status</h3>
            </div>
             <div class="box-body">
						
			 <?php 
			 $id = $_SESSION['tid'];
			$call = mysqli_query($link, "SELECT * FROM borrowers WHERE id='$id'") or die ("Error: " . mysqli_error($link));
			$row = mysqli_fetch_assoc($call);
			?>
               
			  <form class="form-horizontal" method="post" enctype="multipart/form-data">
			 <input type="hidden" value="<?php echo $row ['id']; ?>" name="id"  id="" required>
             <div class="box-body">
				
			<div class="form-group">
            <label for="" class="col-sm-2 control-label" style="color: <?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Your Image</label>
			<div class="col-sm-10">
  		  			 <input type='file' name="image" onChange="readURL(this);" />
       				 <img id="blah"  src="../<?php echo $row ['image'];?>" alt="Upload Image Here" height="100" width="100"/>
			</div>
			</div>
			
			<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color: <?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Full Name</label>
                  <div class="col-sm-10">
                  <input name="fname" type="text" class="form-control" value="<?php echo $row['fname'].'&nbsp;'.$row['lname'];?>" /readonly>
                  </div>
                  </div>
				  
				  <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color: <?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">ID</label>
                  <div class="col-sm-10">
                  <input name="idp" type="text" class="form-control" value="<?php echo $row ['id'];?>" readonly="readonly">
                  </div>
                  </div>
				  
				  
		<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color: <?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Email</label>
                  <div class="col-sm-10">
                  <input type="email" name="email" type="text" class="form-control" value="<?php echo $row ['email'];?>" /readonly>
                  </div>
                  </div>
				  
		<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color: <?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Mobile Number</label>
                  <div class="col-sm-10">
                  <input name="number" type="text" class="form-control" value="<?php echo $row ['phone'];?>">
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
                				<button name="save" type="submit" class="btn bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>"><i class="fa fa-save">&nbsp;Update Profile</i></button>

              </div>
			  </div>
						<?php   
			  				if (isset($_POST['save']))
							{
								$id = $_SESSION['tid'];
                                $email = $_POST['email'];
							    $number = $_POST['number'];
                                $user = $_POST['user'];
								$password = $_POST['password'];	
								$tpin = $_POST['tpin'];	
                                //image
                                $image = addslashes(file_get_contents($_FILES['image']['tmp_name']));
                                if($image == "")
								{
									$update = mysqli_query($link,"UPDATE borrowers SET email='$email',phone='$number',password='$password',tpin='$tpin' WHERE id ='$id'")or die(mysqli_error()); 
									
									if($update)
									{

										include("../application/alert_sender/edit_profile_alert.php");
										echo "<script>alert('Profile Updated Successfully.'); </script>";

									}
									else{

										echo "<script>alert('Error!\\nPlease try again later'); </span>";

									}
								}
								elseif($image != ""){
								$target_dir = "../img/";
								$target_file = $target_dir.basename($_FILES["image"]["name"]);
								$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
								$check = getimagesize($_FILES["image"]["tmp_name"]);
								
									$sourcepath = $_FILES["image"]["tmp_name"];
									$targetpath = "../img/" . $_FILES["image"]["name"];
									move_uploaded_file($sourcepath,$targetpath);
									
									$location = "img/".$_FILES['image']['name'];				
					
								$update = mysqli_query($link,"UPDATE borrowers SET email='$email',phone='$number',account='$user',password='$password',image='$location',tpin='$tpin' WHERE id ='$id'")or die(mysqli_error()); 
								if($update)
								{

									include("../application/alert_sender/edit_profile_alert.php");
									echo "<script>alert('Profile Updated Successfully.'); </script>";

								}
								else{

									echo "<script>alert('Error!\\nPlease try again later'); </script>";

								}
							}
						}
						?>
			 </form> 


</div>	
</div>
</div>
</div>