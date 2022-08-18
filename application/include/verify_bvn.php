   <?php 
   if(isset($_POST['my_bvn']))
   {
	 include("../config/connect.php");
	 //$link = mysqli_connect('localhost','root','root','loan2','8888') or die('Unable to Connect to Database');
	 
  	 $systemset = mysqli_query($link, "SELECT * FROM systemset");
  	 $row1 = mysqli_fetch_object($systemset);
	 
  	 $result = array();
  	 // Pass the customer's authorisation code, email and amount
  	 $bvn = $_POST['my_bvn'];
  	 $postdata =  array('bvn' => $bvn);

  	 $ch = curl_init();
  	 curl_setopt($ch, CURLOPT_URL,"https://api.paystack.co/bank/resolve_bvn");
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
  	   //print_r($result);
  	   if($result){
  	     if($result['data']){
  	       //something came in
  	       if($result['data']['bvn'] == $bvn){
  			   echo "<label class='label label-success'>".$result['data']['first_name'].' '.$result['data']['last_name']."</label>";
  		   }
  		   else{
  			   echo "<label class='label label-danger'>BVN Not Valid</label>";
  		   }
  	   }else{
  		   echo $result['message'];
  		   echo 'Authorization: Bearer '.$row1->secret_key;
  	   }
  	   }
  	   }	
   }	   
   ?>