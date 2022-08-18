<div class="box">
	       <div class="box-body">
			<div class="panel panel-success">
            <div class="panel-heading bg-blue">
            <h3 class="panel-title"> <a href="list_category.php?id=<?php echo $_SESSION['tid']; ?>&&mid=NzUw&&tab=tab_2"><button type="button" class="btn btn-flat bg-orange"><i class="fa fa-mail-reply-all"></i>&nbsp;Back</button></a> <i class="fa fa-code"></i> Update Category</h3>
            </div>

             <div class="box-body">
 <?php
if(isset($_POST['save']))
{
	$idm = mysqli_real_escape_string($link, $_GET['idm']);
	$cat =  mysqli_real_escape_string($link, $_POST['cat']);
		
	$update = mysqli_query($link, "UPDATE team_category SET category = '$cat' WHERE id = '$idm'") or die ("Error: " . mysqli_error($link));
	if(!$update)
	{
		echo "<div class='alert bg-orange'>Unable to Update Category.....Please try again later</div>";
	}
	else{
		echo "<div class='alert bg-blue'>Category Updated Successfully!</div>";
	}
}
?>           
			 <form class="form-horizontal" method="post" enctype="multipart/form-data">

             <div class="box-body">
<?php
$idm = mysqli_real_escape_string($link, $_GET['idm']);
$search_cat = mysqli_query($link, "SELECT * FROM team_category WHERE id = '$idm'");
$fetch_cat = mysqli_fetch_object($search_cat);
?>
	
			<div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Category</label>
                  <div class="col-sm-9">
                  <input name="cat" type="text" class="form-control" value="<?php echo $fetch_cat->category; ?>" placeholder="Edit Category" required>
                  </div>
                  </div>
			
			 </div>
			 
			  <div align="right">
              <div class="box-footer">
                				<button name="save" type="submit" class="btn bg-blue"><i class="fa fa-edit">&nbsp;Update</i></button>
              </div>
			  </div>
			  
			 </form> 

</div>	
</div>	
</div>
</div>