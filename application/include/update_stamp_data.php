<div class="box">	
	       <div class="box-body">
			<div class="panel panel-success">
            <div class="panel-heading">
            <h3 class="panel-title"><i class="fa fa-gear"></i>&nbsp;Stamp Update</h3>
            </div>
             <div class="box-body">

			<?php 
			$id = $_GET['idm'];
			$call = mysqli_query($link, "SELECT * FROM branches WHERE id='$id'");
			$row = mysqli_fetch_assoc($call);
			?>
               
			 <form class="form-horizontal" method="post" enctype="multipart/form-data">
             <div class="box-body">
			
<?php   
if(isset($_FILE['image']))
{
	$id = $_GET['idm'];
	$target_dir = "../img/";
	$target_file = $target_dir.basename($_FILES["image"]["name"]);
	$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
	$check = getimagesize($_FILES["image"]["tmp_name"]);
	
	if($_FILES["image"]["error"] > 0)
	{
		echo "Error: " . $_FILES["image"]["error"] . "<br>";
	}
	elseif($check == false)
	{
		echo "<p style='font-size:24px; color:#FF0000'>Invalid file type</p>";
	}
	elseif($_FILES["image"]["size"] > 500000)
	{
		echo "<p style='font-size:24px; color:#FF0000'>Image must not more than 500KB!</p>";
	}
	elseif($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif")
	{
		echo "<p style='font-size:24px; color:#FF0000'>Sorry, only JPG, JPEG, PNG & GIF Files are allowed for the Stamp.</p>";
	}
	else{
		$sourcepath = $_FILES["image"]["tmp_name"];
		$targetpath = "../img/" . $_FILES["image"]["name"];
		$stamp = $_FILES["image"]["name"];
		if((move_uploaded_file($sourcepath,$targetpath))){

			mysqli_query($link,"UPDATE branches SET stamp='$stamp' WHERE id='$id'")or die(mysqli_error()); 
												
			echo "<script>alert('Stamp Uploaded Successfully!'); </script>";
			echo "<script>window.location='edit_branches.php?id=".$_SESSION['tid']."&&idm=".$id."&&mid=NDAy';</script>";
		}
	}
}
?>
				
			<p><span style="color: red;"> <b>This page is actually dedicated for stamp uploading, and strictly for Branch Settings Only.</b></span></p>

						<div class="form-group">
						<label for="" class="col-sm-2 control-label">Upload Stamp</label>
						<div class="col-sm-10">
								<input type='file' name="image" onChange="readURL(this);">
								<img id="blah"  src="<?php echo $fetchsys_config['file_baseurl'].$row['stamp']; ?>" alt="Branch Stamp Here" height="100" width="100"/>
						</div>
						</div>
									 
			 </div>
			 
			  <div align="right">
              <div class="box-footer">
                	<button type="reset" class="btn btn-primary btn-flat"><i class="fa fa-times">&nbsp;Reset</i></button>
                	<button name="save" type="submit" class="btn btn-success btn-flat"><i class="fa fa-upload">&nbsp;Update Now</i></button>

              </div>
			  </div>
			 </form> 


</div>	
</div>
</div>	
</div>