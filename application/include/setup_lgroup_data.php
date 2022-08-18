<div class="box">
	       <div class="box-body">
			<div class="panel panel-success">
            <div class="panel-heading bg-blue">
            <h3 class="panel-title"> <a href="list_lgroup.php?id=<?php echo $_SESSION['tid']; ?>&&mid=<?php echo base64_encode("411"); ?>&&mid=NDEx"><button type="button" class="btn bg-orange"><i class="fa fa-mail-reply-all"></i>&nbsp;Back</button></a> <i class="fa fa-newspaper-o"></i>  Add Group Settings</h3>
            </div>

             <div class="box-body">
 <?php
if(isset($_POST['save']))
{
	$gname =  mysqli_real_escape_string($link, $_POST['gname']);
	$max_member =  mysqli_real_escape_string($link, $_POST['max_member']);
		
	$insert = mysqli_query($link, "INSERT INTO lgroup_setup VALUES(null,'','$gname','$max_member',NOW())") or die ("Error: " . mysqli_error($link));
	if(!$insert)
	{
		echo "<div class='alert bg-orange'>Unable to Enter Group Setup.....Please try again later</div>";
	}
	else{
		echo "<div class='alert bg-blue'>New Group Added Successfully!</div>";
	}
}
?>           
			 <form class="form-horizontal" method="post" enctype="multipart/form-data">

             <div class="box-body">
			 			
			<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Group Name</label>
                  <div class="col-sm-10">
                  <input name="gname" type="text" class="form-control" placeholder="Group Name" required>
                  </div>
                  </div>
				  
			<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Maximum Member</label>
                  <div class="col-sm-10">
                  <input name="max_member" type="number" class="form-control" placeholder="Maximum NUmber of Member's" required>
                  </div>
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