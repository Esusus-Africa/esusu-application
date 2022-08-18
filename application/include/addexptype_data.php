<div class="box">
	       <div class="box-body">
			<div class="panel panel-success">
            <div class="panel-heading">
            <h3 class="panel-title"> <a href="listexptype.php?id=<?php echo $_SESSION['tid']; ?>&&mid=<?php echo base64_encode("422"); ?>&&mid=NDIy"><button type="button" class="btn btn-flat btn-warning"><i class="fa fa-mail-reply-all"></i>&nbsp;Back</button> </a> <i class="fa fa-newspaper-o"></i>  Add Expense</h3>
            </div>

             <div class="box-body">
 <?php
if(isset($_POST['save']))
{
	$ename =  mysqli_real_escape_string($link, $_POST['ename']);
		
	$insert = mysqli_query($link, "INSERT INTO exptype VALUES(null,'$ename')") or die ("Error: " . mysqli_error($link));
	if(!$insert)
	{
		echo "<div class='alert alert-info'>Unable to Enter Expense Type.....Please try again later</div>";
	}
	else{
		echo "<div class='alert alert-success'>New Expense Type Added Successfully!</div>";
	}
}
?>           
			 <form class="form-horizontal" method="post" enctype="multipart/form-data">

             <div class="box-body">
			 			
			<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:#009900">Name</label>
                  <div class="col-sm-10">
                  <input name="ename" type="text" class="form-control" placeholder="Loan Expense Type Name" required>
                  </div>
                  </div>
			
			 </div>
			 
			  <div align="right">
              <div class="box-footer">
                				<button type="reset" class="btn btn-primary btn-flat"><i class="fa fa-times">&nbsp;Reset</i></button>
                				<button name="save" type="submit" class="btn btn-success btn-flat"><i class="fa fa-save">&nbsp;Save</i></button>

              </div>
			  </div>
			  
			 </form> 

</div>	
</div>	
</div>
</div>