<div class="box">
	       <div class="box-body">
			<div class="panel panel-success">
            <div class="panel-heading bg-blue">
            <h3 class="panel-title"><i class="fa fa-file-o"></i> Confirm OTP</h3>
            </div>

             <div class="box-body">
 <?php
if(isset($_POST['Ctransfer']))
{
  $result = array();
	$transfer_code =  $_GET['tcode'];
	$your_otp =  mysqli_real_escape_string($link, $_POST['your_otp']);

  $systemset = mysqli_query($link, "SELECT * FROM systemset");
  $row1 = mysqli_fetch_object($systemset);
		
	// Pass the plan's name, interval and amount
  $postdata =  array('transfer_code' => $transfer_code, 'otp' => $your_otp);
  
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL,"https://api.paystack.co/transfer/finalize_transfer");
  curl_setopt($ch, CURLOPT_POST, 1);
  curl_setopt($ch, CURLOPT_POSTFIELDS,json_encode($postdata));  //Post Fields
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

  $headers = [
    'Authorization: Bearer '.$row1->secret_key,
    'Content-Type: application/json',
  ];

  curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

  $request = curl_exec ($ch);

  curl_close ($ch);
  
  if ($request) {
    $result = json_decode($request, true);
    
    if($result){
      if($result['status'] == true){

      $update = mysqli_query($link, "UPDATE transfer_history SET status='completed' WHERE transfer_code = '$transfer_code'") or die ("Error: " . mysqli_error($link));
      echo "<script>alert('Transfer Done Successfully...'); </script>";
      echo "<script>window.location='mywallet.php?id=".$_SESSION['tid']."&&mid=NDA0'; </script>";

    }else{
      $message = $result['message'];
      echo "<script>alert('$message \\nPlease try another one'); </script>";
    }
  }else{
      echo "<script>alert('Network Error!.....\\nPlease retry'); </script>";
    }
  }
  
}
if(isset($_POST['resendOTP'])){

  $result = array();
  $transfer_code =  $_GET['tcode'];

  $systemset = mysqli_query($link, "SELECT * FROM systemset");
  $row1 = mysqli_fetch_object($systemset);
    
  // Pass the plan's name, interval and amount
  $postdata =  array('transfer_code' => $transfer_code);
  
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL,"https://api.paystack.co/transfer/resend_otp");
  curl_setopt($ch, CURLOPT_POST, 1);
  curl_setopt($ch, CURLOPT_POSTFIELDS,json_encode($postdata));  //Post Fields
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

  $headers = [
    'Authorization: Bearer '.$row1->secret_key,
    'Content-Type: application/json',
  ];

  curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

  $request = curl_exec ($ch);

  curl_close ($ch);
  
  if ($request) {
    $result = json_decode($request, true);
    
    if($result){
      if($result['status'] == true){

      echo "<script>alert('OTP has been resent'); </script>";
      echo "<script>window.location='finalize_transfer.php?id=".$_SESSION['tid']."&&mid=NDA0&&tcode=".$transfer_code."'; </script>";

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

              <p style="color:red;">Please confirm the <b style="color:blue">OPT</b> sent to your registered phone number to Complete the Transfer Operation and If probably you didn't receive the OPT within <b style="color:blue"><i>10 minutes</i></b>, kindly click on <b style="color:blue">Resend OTP</b> Button  </p>
              <hr>
				  
			<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue">Enter OTP</label>
                  <div class="col-sm-10">
                  <input name="your_otp" type="text" class="form-control" placeholder="Enter OTP to Confirm the Transfer">
                  </div>
                  </div>
			
			 </div>
			 
			  <div align="right">
              <div class="box-footer">
                        <button name="resendOTP" type="submit" class="btn bg-red btn-flat"><i class="fa fa-reply">&nbsp;Resend OTP</i></button>
                				<button name="Ctransfer" type="submit" class="btn bg-blue btn-flat"><i class="fa fa-file">&nbsp;Confirm Transfer</i></button>

              </div>
			  </div>
			  
			 </form> 

</div>	
</div>	
</div>
</div>