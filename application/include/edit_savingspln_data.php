<div class="box">
	       <div class="box-body">
			<div class="panel panel-success">
            <div class="panel-heading">
            <h3 class="panel-title"> <a href="setupsavingspln.php?id=<?php echo $_SESSION['tid']; ?>&&mid=<?php echo base64_encode("411"); ?>&&mid=NDEx"><button type="button" class="btn btn-flat btn-warning"><i class="fa fa-mail-reply-all"></i>&nbsp;Back</button> </a> <i class="fa fa-newspaper-o"></i>  Update Savings Plan</h3>
            </div>

             <div class="box-body">
 <?php
if(isset($_POST['save']))
{	
	$result = array();
	$spname =  $_POST['spname'];
	$interest_rate =  $_POST['interest_rate'];
	$amount =  $_POST['amount'];
	$pinterval =  $_POST['pinterval'];
	$mduration =  $_POST['mduration'];
	$plan_code = $_GET['pl_code'];
	
	// Pass the plan's name, interval and amount
	$postdata =  array('name' => $spname,'interval' => $pinterval, 'amount' => $amount*100);
	
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL,"https://api.paystack.co/plan/".$plan_code);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS,json_encode($postdata));  //Post Fields
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

	$systemset = mysqli_query($link, "SELECT * FROM systemset");
	$row1 = mysqli_fetch_object($systemset);

	$headers = [
	  'Authorization: Bearer '.$row1->secret_key,
	  'Content-Type: application/json',
	];

	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

	$request = curl_exec ($ch);

	curl_close ($ch);
	
	if ($request) {
	  $result = json_decode($request, true);
	  
	  if($result['data']['plan_code'] == true){
			
			//UPDATING YOUR PLANS ON PAYSTACK AND ON YOUR DATABASE
			$insert = mysqli_query($link, "UPDATE savings_plan SET plan='$spname', interest='$interest_rate', amount='$amount', pinterval='$pinterval', effective_duration='$mduration' WHERE plan_code = '$plan_code'") or die ("Error: " . mysqli_error($link));
			echo "<script>alert('Savings Plan Updated Successfully!'); </script>";
			//echo "<script>window.location='setupsavingspln.php?id=".$_SESSION['tid']."&&mid=NDEx'; </script>";
	}else{
			echo "<script>alert('Network Error!.....\\nPlease retry'); </script>";
		}
	}
}
?>           
			 <form class="form-horizontal" method="post" enctype="multipart/form-data">

             <div class="box-body">
<?php
$pl_code = $_GET['pl_code'];
$search_sinfo = mysqli_query($link, "SELECT * FROM savings_plan WHERE plan_code = '$pl_code'");
$fetch_sinfo = mysqli_fetch_object($search_sinfo);
?>			 			
			<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:#009900">Plan Name</label>
                  <div class="col-sm-10">
                  <input name="spname" type="text" class="form-control" value="<?php echo $fetch_sinfo->plan; ?>" placeholder="Savings Plan Name" /required>
                  </div>
                  </div>
				  
			<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:#009900">Interest Rate <i>(Per Annum)</i></label>
                  <div class="col-sm-10">
                  <input name="interest_rate" type="number" class="form-control" value="<?php echo $fetch_sinfo->interest; ?>" placeholder="Interest Rate per Annum" /required>
                  </div>
                  </div>
				  
			<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:#009900">Recurring Amount</label>
                  <div class="col-sm-10">
                  <input name="amount" type="text" class="form-control" value="<?php echo $fetch_sinfo->amount; ?>" placeholder="Enter Recurring Amount to be Saving" /required>
                  </div>
                  </div>
				  
			<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:#009900">Interval</label>
                  <div class="col-sm-10">
                  <select name="pinterval" class="select2" style="width: 100%;" /required>
  				<option value="<?php echo $fetch_sinfo->pinterval; ?>" selected="selected"><?php echo $fetch_sinfo->pinterval; ?></option>
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
				<option value="<?php echo $fetch_sinfo->effective_duration; ?>" selected="selected"><?php echo $fetch_sinfo->effective_duration; ?> Months</option>
					<option value="3">3 Months</option>
					<option value="6">Bi-Annual (6 Months)</option>
                </select>
              </div>
			  </div>
			
			 </div>
			 
			  <div align="right">
              <div class="box-footer">
                				<button type="reset" class="btn btn-primary btn-flat"><i class="fa fa-times">&nbsp;Reset</i></button>
                				<button name="save" type="submit" class="btn btn-success btn-flat"><i class="fa fa-save">&nbsp;Update</i></button>

              </div>
			  </div>
			  
			 </form> 

</div>	
</div>	
</div>
</div>