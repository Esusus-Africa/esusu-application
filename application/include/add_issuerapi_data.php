<div class="box">
	       <div class="box-body">
			<div class="panel panel-success">
            <div class="panel-heading bg-blue">
            <h3 class="panel-title"> <a href="card_issuerapis.php?id=<?php echo $_SESSION['tid']; ?>&&mid=NDEx"><button type="button" class="btn btn-flat bg-orange"><i class="fa fa-mail-reply-all"></i>&nbsp;Back</button></a> <i class="fa fa-code"></i>  Add RESTful API</h3>
            </div>

             <div class="box-body">
 <?php
if(isset($_POST['save']))
{
	$issuer_name =  mysqli_real_escape_string($link, $_POST['issuer_name']);
	$api_name =  mysqli_real_escape_string($link, $_POST['api_name']);
	$api_url =  mysqli_real_escape_string($link, $_POST['api_url']);
		
	$insert = mysqli_query($link, "INSERT INTO atmcard_gateway_apis VALUES(null,'$issuer_name','$api_name','$api_url')") or die ("Error: " . mysqli_error($link));
	if(!$insert)
	{
		echo "<div class='alert bg-orange'>Unable to Create Bank API.....Please try again later</div>";
	}
	else{
		echo "<div class='alert bg-blue'>New API Added Successfully!</div>";
	}
}
?>           
			 <form class="form-horizontal" method="post" enctype="multipart/form-data">

             <div class="box-body">

            <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Issuer Name</label>
                  <div class="col-sm-9">
                  <input name="issuer_name" type="text" class="form-control" placeholder="e.g Flutterwave Bank, Wema Bank etc." required>
                  </div>
                  </div>

			<div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">API Name</label>
                  <div class="col-sm-9">
                  <input name="api_name" type="text" class="form-control" placeholder="e.g terminate-card, fund-card etc." required>
                  <span style="color: orange;">Please the api name must be in lower case with no space in-between.</span>
                  </div>
                  </div>
	
			<div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">API URL</label>
                  <div class="col-sm-9">
                  <input name="api_url" type="text" class="form-control" placeholder="Enter API URL" required>
                  </div>
                  </div>
			
			 </div>
			 
			  <div align="right">
              <div class="box-footer">
                				<button name="save" type="submit" class="btn bg-blue"><i class="fa fa-plus">&nbsp;Add</i></button>

              </div>
			  </div>
			  
			 </form> 

</div>	
</div>	
</div>
</div>