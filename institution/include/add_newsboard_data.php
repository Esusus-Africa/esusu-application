<div class="box">
	       <div class="box-body">
			<div class="panel panel-success">
            <div class="panel-heading">
            <h3 class="panel-title"> <a href="newsboard.php?id=<?php echo $_SESSION['tid']; ?>&&mid=NDE1"><button type="button" class="btn btn-flat bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>"><i class="fa fa-mail-reply-all"></i>&nbsp;Back</button> </a> <i class="fa fa-signal"></i>  Add Notice</h3>
            </div>

             <div class="box-body">
 <?php
if(isset($_POST['submit']))
{
  $posted_by = $_SESSION['tid'];
	$caption =  mysqli_real_escape_string($link, $_POST['caption']);
	$details =  mysqli_real_escape_string($link, $_POST['details']);
		
	$insert = mysqli_query($link, "INSERT INTO newboard VALUES(null,'$posted_by',NOW(),'$caption','$details')") or die ("Error: " . mysqli_error($link));
	if(!$insert)
	{
		echo "<div class='alert alert-info'>Error.....Please try again later</div>";
	}
	else{
		echo "<div class='alert alert-success'>Notice Added Successfully!</div>";
	}
}
?>           
			 <form class="form-horizontal" method="post" enctype="multipart/form-data">

             <div class="box-body">
			 			
			<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">Caption (Headline)</label>
                  <div class="col-sm-10">
                  <input name="caption" type="text" class="form-control" placeholder="Caption / Headline" required>
                  </div>
                  </div>

            <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">Details</label>
                  <div class="col-sm-10">
                  <textarea name="details" id="editor1" class="form-control" rows="5" cols="80" placeholder="Enter the full notice for public to see."></textarea>
                  </div>
                  </div>
			
			 </div>
			 
			  <div align="right">
              <div class="box-footer">
                				<button name="submit" type="submit" class="btn bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?> btn-flat"><i class="fa fa-add">&nbsp;Submit</i></button>

              </div>
			  </div>
			  
			 </form> 

</div>	
</div>	
</div>
</div>