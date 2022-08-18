<div class="box">
	       <div class="box-body">
			<div class="panel panel-success">
            <div class="panel-heading bg-blue">
            <h3 class="panel-title"> <a href="p2plending_banner.php?id=<?php echo $_SESSION['tid']; ?>&&mid=NDEx"><button type="button" class="btn btn-flat bg-orange"><i class="fa fa-mail-reply-all"></i>&nbsp;Back</button></a> <i class="fa fa-newspaper-o"></i>  Add New Banner</h3>
            </div>

             <div class="box-body">
 <?php
if(isset($_POST['save']))
{
    $caption =  mysqli_real_escape_string($link, $_POST['caption']);
    
    $target_dir = "../lend/images/home/";
    $target_file = $target_dir.basename($_FILES["image"]["name"]);
    $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
    $check = getimagesize($_FILES["image"]["tmp_name"]);

    if($check == false)
    {
		echo "<p style='font-size:24px; color:orange'>Invalid file type</p>";
    }
    else{
        $sourcepath = $_FILES["image"]["tmp_name"];
	    $targetpath = "../lend/images/home/" . $_FILES["image"]["name"];
	    move_uploaded_file($sourcepath,$targetpath);

	    $banner_image = $_FILES['image']['name'];
	
        $insert = mysqli_query($link, "INSERT INTO lending_banner VALUES(null,'$banner_image','$caption')") or die ("Error: " . mysqli_error($link));
        
        if(!$insert)
        {
            echo "<div class='alert bg-orange'>Unable to Post New Banner.....Please try again later</div>";
        }
        else{
            echo "<div class='alert bg-blue'>New Banner Added Successfully!</div>";
        }
    }
}
?>           
			 <form class="form-horizontal" method="post" enctype="multipart/form-data">

             <div class="box-body">
                 
             <div class="form-group">
            <label for="" class="col-sm-2 control-label" style="color:blue;">Upload Banner</label>
			<div class="col-sm-10">
  		  			 <input type='file' name="image" onChange="readURL(this);" class="alert bg-orange" required>
                         <img id="blah"  src="../avtar/user2.png" alt="Image Here" width="500" height="200"/>
			</div>
			</div>

            <div class="form-group">
                  	<label for="" class="col-sm-2 control-label" style="color:blue">Slide Caption</label>
                  	<div class="col-sm-10"><textarea name="caption"  class="form-control" rows="2" cols="80" required></textarea></div>
            </div>
			
			 </div>
			 
			  <div align="right">
              <div class="box-footer">
                <button name="save" type="submit" class="btn bg-blue"><i class="fa fa-save">&nbsp;Save</i></button>
              </div>
			  </div>
			  
			 </form> 

</div>	
</div>	
</div>
</div>