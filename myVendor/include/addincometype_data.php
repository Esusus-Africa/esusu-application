<div class="box">
	       <div class="box-body">
			<div class="panel panel-success">
            <div class="panel-heading bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">
            <h3 class="panel-title"> <a href="listincometype.php?id=<?php echo $_SESSION['tid']; ?>&&mid=<?php echo base64_encode("500"); ?>&&mid=NTAw"><button type="button" class="btn btn-flat bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>"><i class="fa fa-mail-reply-all"></i>&nbsp;Back</button></a> <i class="fa fa-calculator"></i>  Add Income Type</h3>
            </div>

             <div class="box-body">
 <?php
if(isset($_POST['save']))
{
	$icm_name =  mysqli_real_escape_string($link, $_POST['icm_name']);
		
	$insert = mysqli_query($link, "INSERT INTO icmtype VALUES(null,'$icm_name')") or die ("Error: " . mysqli_error($link));
	if(!$insert)
	{
		echo "<div class='alert alert-info'>Unable to Enter Income Type.....Please try again later</div>";
	}
	else{
		echo "<div class='alert alert-success'>New Income Type Added Successfully!</div>";
	}
}
?>           
			 <form class="form-horizontal" method="post" enctype="multipart/form-data">

             <div class="box-body">
			 			
			<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Name</label>
                  <div class="col-sm-10">
                  <input name="icm_name" type="text" class="form-control" placeholder="Income Type Name" required>
                  </div>
                  </div>
			
			 </div>
			 
			  <div align="right">
              <div class="box-footer">
                				<button name="save" type="submit" class="btn bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?> btn-flat"><i class="fa fa-save">&nbsp;Save</i></button>

              </div>
			  </div>
			  
			 </form> 

</div>	
</div>	
</div>
</div>