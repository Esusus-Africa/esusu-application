<div class="box">
	       <div class="box-body">
			<div class="panel panel-success">
            <div class="panel-heading bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">
            <h3 class="panel-title"> <a href="listincometype.php?id=<?php echo $_SESSION['tid']; ?>&&mid=<?php echo base64_encode("422"); ?>&&mid=NDIy"><button type="button" class="btn btn-flat bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>"><i class="fa fa-mail-reply-all"></i>&nbsp;Back</button> </a> <i class="fa fa-newspaper-o"></i>  Edit Expense</h3>
            </div>

             <div class="box-body">
 <?php
if(isset($_POST['save']))
{
	$idm = $_GET['idm'];
	$icm_name =  mysqli_real_escape_string($link, $_POST['icm_name']);
		
	$insert = mysqli_query($link, "UPDATE icmtype SET incometype = '$icm_name' WHERE id = '$idm'") or die ("Error: " . mysqli_error($link));
	if(!$insert)
	{
		echo "<div class='alert alert-info'>Unable to Edit Income Type.....Please try again later</div>";
	}
	else{
		echo "<div class='alert alert-success'>Income Type Edited Successfully!</div>";
	}
}
?>           
			 <form class="form-horizontal" method="post" enctype="multipart/form-data">

             <div class="box-body">
<?php
$idm = $_GET['idm'];
$search_query = mysqli_query($link, "SELECT * FROM icmtype WHERE id ='$idm'");
$fetch_query = mysqli_fetch_array($search_query);
?>			 			
			<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Name</label>
                  <div class="col-sm-10">
                  <input name="icm_name" type="text" class="form-control" value="<?php echo $fetch_query['incometype']; ?>" placeholder="Income Type Name" required>
                  </div>
                  </div>
			
			 </div>
			 
			  <div align="right">
              <div class="box-footer">
                				<button name="save" type="submit" class="btn bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?> btn-flat"><i class="fa fa-save">&nbsp;Save</i></button>

              </div>
			  </div>
			  
			 </form> 

</div>	
</div>	
</div>
</div>