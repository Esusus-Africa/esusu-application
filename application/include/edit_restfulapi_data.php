<div class="box">
	       <div class="box-body">
			<div class="panel panel-success">
            <div class="panel-heading bg-blue">
            <h3 class="panel-title"> <a href="restful_api_settings.php?id=<?php echo $_SESSION['tid']; ?>&&mid=NDEx"><button type="button" class="btn btn-flat bg-orange"><i class="fa fa-mail-reply-all"></i>&nbsp;Back</button></a> <i class="fa fa-code"></i>  Add RESTful API</h3>
            </div>

             <div class="box-body">
 <?php
if(isset($_POST['save']))
{
	$idm = mysqli_real_escape_string($link, $_GET['idm']);
	$api_name =  mysqli_real_escape_string($link, $_POST['api_name']);
	$api_url =  mysqli_real_escape_string($link, $_POST['api_url']);
		
	$update = mysqli_query($link, "UPDATE restful_apisetup SET api_name = '$api_name', api_url = '$api_url' WHERE id = '$idm'") or die ("Error: " . mysqli_error($link));
	if(!$update)
	{
		echo "<div class='alert bg-orange'>Unable to Update RESTful API.....Please try again later</div>";
	}
	else{
		echo "<div class='alert bg-blue'>API Updated Successfully!</div>";
	}
}
?>           
			 <form class="form-horizontal" method="post" enctype="multipart/form-data">

             <div class="box-body">
<?php
$idm = mysqli_real_escape_string($link, $_GET['idm']);
$search_restfulapi = mysqli_query($link, "SELECT * FROM restful_apisetup WHERE id = '$idm'");
$fetch_restfulapi = mysqli_fetch_object($search_restfulapi);
?>
			<div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">API Name</label>
                  <div class="col-sm-9">
                  <input name="api_name" type="text" class="form-control" value="<?php echo $fetch_restfulapi->api_name; ?>" placeholder="e.g balance, transfer, subaccount, etc." required>
                  <span style="color: orange;">Please the api name must be in lower case with no space in-between.</span>
                  </div>
                  </div>
	
			<div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">API URL</label>
                  <div class="col-sm-9">
                  <input name="api_url" type="text" class="form-control" value="<?php echo $fetch_restfulapi->api_url; ?>" placeholder="Enter API URL" required>
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