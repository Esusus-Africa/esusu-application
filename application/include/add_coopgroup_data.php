<div class="box">
	       <div class="box-body">
			<div class="panel panel-success">
            <div class="panel-heading bg-blue">
            <h3 class="panel-title"> <a href="setupsavingspln.php?id=<?php echo $_SESSION['tid']; ?>&&mid=<?php echo base64_encode("411"); ?>&&mid=NDEx"><button type="button" class="btn btn-flat bg-red"><i class="fa fa-mail-reply-all"></i>&nbsp;Back</button></a> <i class="fa fa-newspaper-o"></i> Add Savings Plan</h3>
            </div>

             <div class="box-body">
 <?php
if(isset($_POST['save']))
{
	$result = array();
	$spname =  mysqli_real_escape_string($link, $_POST['spname']);
	$interest_rate =  mysqli_real_escape_string($link, $_POST['interest_rate']);
	$amount =  mysqli_real_escape_string($link, $_POST['amount']);
	$pinterval =  mysqli_real_escape_string($link, $_POST['pinterval']);
	$mduration =  mysqli_real_escape_string($link, $_POST['mduration']);
	
	// Pass the plan's name, interval and amount
	$postdata =  array('name' => $spname,'interval' => $pinterval, 'amount' => $amount*100);
	
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL,"https://api.paystack.co/plan");
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS,json_encode($postdata));  //Post Fields
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

	$systemset = mysqli_query($link, "SELECT * FROM systemset");
	$row1 = mysqli_fetch_object($systemset);

	$headers = [
	  'Authorization: Bearer '.$row1->secret_key,
	  'Content-Type: application/json'
	];

	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

	$request = curl_exec ($ch);

	curl_close ($ch);
	
	if ($request) {
	  $result = json_decode($request, true);
	  
	  if($result){
	    if($result['data'] == true){
			
			//Get the Plan code from Paystack API
			$plan_code = $result['data']['plan_code'];
			
			$insert = mysqli_query($link, "INSERT INTO savings_plan VALUES(null,'$plan_code','$spname','$interest_rate','$amount','$pinterval','$mduration')") or die ("Error: " . mysqli_error($link));
			echo "<script>alert('New Savings Plan Added Successfully!'); </script>";

		}else{
			$message = $result['message'];
			echo "<script>alert('$message \\nPlease try another one'); </script>";
		}
	}else{
			echo "<script>alert('Network Error!.....\\nPlease retry'); </script>";
		}
  }
	
}
?>           
			 <form class="form-horizontal" method="post" enctype="multipart/form-data">

             <div class="box-body">
			 			
			<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:#009900">Plan Name</label>
                  <div class="col-sm-10">
                  <input name="spname" type="text" class="form-control" placeholder="Savings Plan Name" /required>
                  </div>
                  </div>
				  
			<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:#009900">Interest Rate <i>(Per Annum)</i></label>
                  <div class="col-sm-10">
                  <input name="interest_rate" type="number" class="form-control" placeholder="Interest Rate per Annum" /required>
                  </div>
                  </div>
				  
			<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:#009900">Recurring Amount</label>
                  <div class="col-sm-10">
                  <input name="amount" type="text" class="form-control" placeholder="Enter Recurring Amount to be Saving" /required>
                  </div>
                  </div>
				  
			<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:#009900">Interval</label>
                  <div class="col-sm-10">
                  <select name="pinterval" class="select2" style="width: 100%;" /required>
  				<option selected="selected">---Choose Interval for Savings---</option>
				<option value="hourly">Hourly</option>
				<option value="Daily">Daily</option>
				<option value="weekly">Weekly</option>
				<option value="monthly">Monthly</option>
				<option value="annually">Annually</option>
                  </select>
				  </div>
            </div>
				  
			<div class="form-group">
                <label for="" class="col-sm-2 control-label" style="color:#009900">Mature Duration</label>
				 <div class="col-sm-10">
                <select name="mduration" class="select2" style="width: 100%;" /required>
				<option selected="selected">--Select Mature. Duration--</option>
					<option value="3">3 Months</option>
					<option value="6">Bi-Annual (6 Months)</option>
                </select>
              </div>
			  </div>
			
			 </div>
			 
			  <div align="right">
              <div class="box-footer">
                				<button type="reset" class="btn bg-red btn-flat"><i class="fa fa-times">&nbsp;Reset</i></button>
                				<button name="save" type="submit" class="btn bg-blue btn-flat"><i class="fa fa-save">&nbsp;Save</i></button>

              </div>
			  </div>
			  
			 </form> 

</div>	
</div>	
</div>
</div>