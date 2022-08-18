<div class="box">
	       <div class="box-body">
			<div class="panel panel-success">
            <div class="panel-heading bg-blue">
            <h3 class="panel-title"> <a href="p2plending_banner.php?id=<?php echo $_SESSION['tid']; ?>&&mid=NDEx"><button type="button" class="btn btn-flat bg-orange"><i class="fa fa-mail-reply-all"></i>&nbsp;Back</button></a> <i class="fa fa-calculator"></i> Edit Banner</h3>
            </div>

             <div class="box-body">


  <?php
if(isset($_POST['save']))
{
	$lprd_id = $_GET['idm'];
	$banner_caption =  mysqli_real_escape_string($link, $_POST['caption']);
	
	$insert = mysqli_query($link, "UPDATE lending_banner SET caption='$banner_caption' WHERE id = '$lprd_id'");
	if(!$insert)
	{
		echo "<div class='alert bg-orange'>Unable to Update Slide Caption.....Please try again later</div>";
	}
	else{
		echo "<div class='alert bg-blue'>Data Update Successfully!</div>";
	}
}
?>
       
			 <form class="form-horizontal" method="post" enctype="multipart/form-data">

             <div class="box-body">
<?php
$idm = $_GET['idm'];
$lprd_search = mysqli_query($link, "SELECT * FROM lending_banner WHERE id = '$idm'") or die ("Error: " . mysqli_error($link));
$get_lprd = mysqli_fetch_object($lprd_search);
?>			 
            <div class="form-group">
                  	<label for="" class="col-sm-2 control-label" style="color:blue">Slide Caption</label>
                  	<div class="col-sm-10"><textarea name="caption"  class="form-control" rows="2" cols="80" required><?php echo $get_lprd->caption; ?></textarea></div>
            </div>
				  
			 </div>
			 
			  <div align="right">
              <div class="box-footer">
                <button name="save" type="submit" class="btn bg-blue btn-flat"><i class="fa fa-save">&nbsp;Save</i></button>
              </div>
			  </div>
			  
			 </form> 

</div>	
</div>	
</div>
</div>