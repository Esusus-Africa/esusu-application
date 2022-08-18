<div class="box">
        
	
	       <div class="box-body">
			<div class="panel panel-success">
            <div class="panel-heading bg-blue">
            <h3 class="panel-title"><i class="fa fa-gear"></i>&nbsp;Update Profile Status</h3>
            </div>
             <div class="box-body">
							<?php   
			  				if(isset($_POST['update'])){

								$id= $_POST['companyid'];
                                $cname = $_POST['cname'];
                                $senderid = $_POST['senderid'];
                              		 	
                                //image
                                $image = addslashes(file_get_contents($_FILES['image']['tmp_name']));
                                $image3 = addslashes(file_get_contents($_FILES['image3']['tmp_name']));

								$target_dir = "../img/";
								$target_dir3 = "../image/";

								$target_file = $target_dir.basename($_FILES["image"]["name"]);
								$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);

								$target_file3 = $target_dir3.basename($_FILES["image3"]["name"]);
								$imageFileType3 = pathinfo($target_file3,PATHINFO_EXTENSION);

								$check = getimagesize($_FILES["image"]["tmp_name"]);
								$check3 = getimagesize($_FILES["image3"]["tmp_name"]);
								
								if($check == false && $check3 == false)
								{
									echo '<meta http-equiv="refresh" content="2;url=profile.php?tid='.$id.'">';
									echo '<br>';
									echo'<span class="itext" style="color: orange;">Invalid file type</span>';
								}
								elseif($_FILES["image"]["size"] > 500000000 && $_FILES["image3"]["size"] > 500000000)
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
								elseif($imageFileType3 != "jpg" && $imageFileType3 != "png" && $imageFileType3 != "jpeg" && $imageFileType3 != "gif")
								{
									echo '<meta http-equiv="refresh" content="2;url=profile.php?tid='.$id.'">';
									echo '<br>';
									echo'<span class="itext" style="color: orange;">Sorry, only JPG, JPEG, PNG & GIF Files are allowed.</span>';
								}
								else{
									$sourcepath = $_FILES["image"]["tmp_name"];
									$targetpath = "../img/" . $_FILES["image"]["name"];
									move_uploaded_file($sourcepath,$targetpath);

									$sourcepath3 = $_FILES["image3"]["tmp_name"];
									$targetpath3 = "../image/" . $_FILES["image3"]["name"];
									move_uploaded_file($sourcepath3,$targetpath3);
									
									$location = "img/".$_FILES['image']['name'];	

									$lbackg = $_FILES["image3"]["name"];			
					
									mysqli_query($link,"UPDATE member_settings SET cname='$cname',logo='$location',frontpg_backgrd='$lbackg',sender_id='$senderid' WHERE companyid ='$id'")or die(mysqli_error()); 
									echo "<script>alert('Data Updated Successfully!'); </script>";
									echo "<script>window.location='profile.php';</script>";
									}
								}
								if(isset($_POST['save'])){

									$id= $_POST['companyid'];
	                                $cname = $_POST['cname'];
	                                $senderid = $_POST['senderid'];
	                              		 	
	                                //image
	                                $image = addslashes(file_get_contents($_FILES['image']['tmp_name']));
	                                $image3 = addslashes(file_get_contents($_FILES['image3']['tmp_name']));

									$target_dir = "../img/";
									$target_dir3 = "../image/";

									$target_file = $target_dir.basename($_FILES["image"]["name"]);
									$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);

									$target_file3 = $target_dir3.basename($_FILES["image3"]["name"]);
									$imageFileType3 = pathinfo($target_file3,PATHINFO_EXTENSION);

									$check = getimagesize($_FILES["image"]["tmp_name"]);
									$check3 = getimagesize($_FILES["image3"]["tmp_name"]);
									
									if($check == false && $check3 == false)
									{
										echo '<meta http-equiv="refresh" content="2;url=profile.php?tid='.$id.'">';
										echo '<br>';
										echo'<span class="itext" style="color: orange;">Invalid file type</span>';
									}
									elseif($_FILES["image"]["size"] > 500000000 && $_FILES["image3"]["size"] > 500000000)
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
									elseif($imageFileType3 != "jpg" && $imageFileType3 != "png" && $imageFileType3 != "jpeg" && $imageFileType3 != "gif")
									{
										echo '<meta http-equiv="refresh" content="2;url=profile.php?tid='.$id.'">';
										echo '<br>';
										echo'<span class="itext" style="color: orange;">Sorry, only JPG, JPEG, PNG & GIF Files are allowed.</span>';
									}
									else{
										$sourcepath = $_FILES["image"]["tmp_name"];
										$targetpath = "../img/" . $_FILES["image"]["name"];
										move_uploaded_file($sourcepath,$targetpath);

										$sourcepath3 = $_FILES["image3"]["tmp_name"];
										$targetpath3 = "../image/" . $_FILES["image3"]["name"];
										move_uploaded_file($sourcepath3,$targetpath3);
										
										$location = "img/".$_FILES['image']['name'];	

										$lbackg = $_FILES["image3"]["name"];			
						
										mysqli_query($link,"INSERT INTO member_settings VALUES(null,'$id','$cname','$location','$lbackg','$senderid')")or die(mysqli_error()); 
										echo "<script>alert('Data Saved Successfully!'); </script>";
										echo "<script>window.location='profile.php';</script>";
										}
								}
							?>
			<?php 
			//$id = $_SESSION['tid'];
			$call = mysqli_query($link, "SELECT * FROM member_settings WHERE companyid='$agentid'");
			if(mysqli_num_rows($call) == 1)
			{
				$row = mysqli_fetch_assoc($call);
			?>
               
			  <form class="form-horizontal" method="post" enctype="multipart/form-data">

			 <?php echo '<div class="bg-orange fade in" >
			  <a href = "#" class = "close" data-dismiss= "alert"> &times;</a>
  				<strong>Note that&nbsp;</strong> &nbsp;&nbsp;Some Fields are Rquired.
				</div>'?>
             <div class="box-body">
				
			<div class="form-group">
            <label for="" class="col-sm-2 control-label" style="color: blue;">Your Logo</label>
			<div class="col-sm-10">
  		  			 <input type='file' name="image" onChange="readURL(this);" required/>
       				 <img id="blah"  src="../<?php echo $row ['logo'];?>" alt="Upload Logo Here" height="100" width="100"/>
			</div>
			</div>
			
			<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Business Name</label>
                  <div class="col-sm-10">
                  <input name="cname" type="text" class="form-control" value="<?php echo $row ['cname']; ?>">
                  </div>
                  </div>
				  
				  <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color: blue;">ID</label>
                  <div class="col-sm-10">
                  <input name="companyid" type="text" class="form-control" value="<?php echo $row['companyid'];?>" readonly="readonly">
                  </div>
                  </div>


			<div class="form-group">
				<label for="" class="col-sm-2 control-label" style="color: blue;">Upload Login Background</label>
				<div class="col-sm-10">
					<input type='file' name="image3" required/>
					<img src="../image/<?php echo $row['frontpg_backgrd']; ?>" alt="Background Here" height="100" width="100"/>
				</div>
			</div>	  
				  
		<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Sender ID</label>
                  <div class="col-sm-10">
                  <input type="text" name="senderid" type="text" class="form-control" value="<?php echo $row['sender_id']; ?>">
                  </div>
                  </div>
				  
			 </div>
			 
			  <div align="right">
              <div class="box-footer">
                				<button name="update" type="submit" class="btn bg-blue"><i class="fa fa-save">&nbsp;Update</i></button>

              </div>
			  </div>
			 </form> 
	<?php 
	}
	else{
	?>

			<form class="form-horizontal" method="post" enctype="multipart/form-data">

			 <?php echo '<div class="bg-orange fade in" >
			  <a href = "#" class = "close" data-dismiss= "alert"> &times;</a>
  				<strong>Note that&nbsp;</strong> &nbsp;&nbsp;Some Fields are Rquired.
				</div>'?>
             <div class="box-body">
				
			<div class="form-group">
            <label for="" class="col-sm-2 control-label" style="color: blue;">Your Logo</label>
			<div class="col-sm-10">
  		  			 <input type='file' name="image" onChange="readURL(this);" />
       				 <img id="blah" alt="Upload Logo Here" height="100" width="100"/>
			</div>
			</div>
			
			<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Business Name</label>
                  <div class="col-sm-10">
                  <input name="cname" type="text" class="form-control" placeholder="Business Name Here" required/>
                  </div>
                  </div>
				  
				  <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color: blue;">ID</label>
                  <div class="col-sm-10">
                  <input name="companyid" type="text" class="form-control" value="<?php echo $instid; ?>" readonly="readonly">
                  </div>
                  </div>


			<div class="form-group">
				<label for="" class="col-sm-2 control-label" style="color: blue;">Upload Login Background</label>
				<div class="col-sm-10">
					<input type='file' name="image3">
				</div>
			</div>	  
				  
		<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Sender ID</label>
                  <div class="col-sm-10">
                  <input type="text" name="senderid" type="text" class="form-control" placeholder="Your Sender ID" maxlength="11" required/>
                  </div>
                  </div>
				  
			 </div>
			 
			  <div align="right">
              <div class="box-footer">
                				<button name="save" type="submit" class="btn bg-blue"><i class="fa fa-plus">&nbsp;Save</i></button>

              </div>
			  </div>
			 </form> 


<?php } ?>



</div>	
</div>
</div>
</div>