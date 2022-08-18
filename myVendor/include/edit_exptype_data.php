<div class="box">
	       <div class="box-body">
			<div class="panel panel-success">
            <div class="panel-heading bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">
            <h3 class="panel-title"> <a href="listexptype.php?id=<?php echo $_SESSION['tid']; ?>&&mid=<?php echo base64_encode("422"); ?>&&mid=NDIy"><button type="button" class="btn btn-flat bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>"><i class="fa fa-mail-reply-all"></i>&nbsp;Back</button></a> <i class="fa fa-newspaper-o"></i>  Edit Expense</h3>
            </div>

             <div class="box-body">
 <?php
if(isset($_POST['save']))
{
	$idm = $_GET['idm'];
	$ename =  mysqli_real_escape_string($link, $_POST['ename']);
		
	$insert = mysqli_query($link, "UPDATE exptype SET etype = '$ename' WHERE id = '$idm'") or die ("Error: " . mysqli_error($link));
	if(!$insert)
	{
		echo "<div class='alert alert-info'>Unable to Edit Expense Type.....Please try again later</div>";
	}
	else{
		echo "<div class='alert alert-success'>Expense Type Edited Successfully!</div>";
	}
}
?>           
			 <form class="form-horizontal" method="post" enctype="multipart/form-data">

             <div class="box-body">
<?php
$idm = $_GET['idm'];
$search_query = mysqli_query($link, "SELECT * FROM exptype WHERE id ='$idm'");
$fetch_query = mysqli_fetch_array($search_query);
?>			 			
			<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Name</label>
                  <div class="col-sm-10">
                  <input name="ename" type="text" class="form-control" value="<?php echo $fetch_query['etype']; ?>" placeholder="Loan Expense Type Name" required>
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