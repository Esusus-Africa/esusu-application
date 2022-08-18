<div class="box">
	       <div class="box-body">
			<div class="panel panel-success">
            <div class="panel-heading bg-blue">
            <h3 class="panel-title"> <a href="compensation_plan.php?id=<?php echo $_SESSION['tid']; ?>&&mid=NDE2"><button type="button" class="btn btn-flat bg-orange"><i class="fa fa-mail-reply-all"></i>&nbsp;Back</button></a> <i class="fa fa-newspaper-o"></i>  Add Compensation Plan</h3>
            </div>

             <div class="box-body">
 <?php
if(isset($_POST['save']))
{
	$plan_level =  mysqli_real_escape_string($link, $_POST['plan_level']);
	$percentage =  mysqli_real_escape_string($link, $_POST['percentage']);
		
	$insert = mysqli_query($link, "INSERT INTO compensation_plan VALUES(null,'$plan_level','$percentage')") or die ("Error: " . mysqli_error($link));
	if(!$insert)
	{
		echo "<div class='alert bg-red'>Error.....Please try again later</div>";
	}
	else{
		echo "<div class='alert bg-blue'>New Plan Added Successfully!</div>";
	}
}
?>           
			 <form class="form-horizontal" method="post" enctype="multipart/form-data">

             <div class="box-body">
			 			
			<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Plan Level</label>
                  <div class="col-sm-10">
                  <input name="plan_level" type="number" class="form-control" placeholder="Plan Level" required>
                  </div>
                  </div>

            <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Percentage</label>
                  <div class="col-sm-10">
                  <input name="percentage" type="text" class="form-control" placeholder="Percentage">
                  </div>
                  </div>
			
			 </div>
			 
			  <div align="right">
              <div class="box-footer">
                				<button type="reset" class="btn bg-orange"><i class="fa fa-times">&nbsp;Reset</i></button>
                				<button name="save" type="submit" class="btn bg-blue"><i class="fa fa-save">&nbsp;Save</i></button>

              </div>
			  </div>
			  
			 </form> 

</div>	
</div>	
</div>
</div>