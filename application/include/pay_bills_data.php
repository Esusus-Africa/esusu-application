<div class="box">
	       <div class="box-body">
			<div class="panel panel-success">
            <div class="panel-heading bg-blue">
            <h3 class="panel-title"> <a href="mywallet.php?id=<?php echo $_SESSION['tid']; ?>&&mid=NDA0"><button type="button" class="btn btn-flat bg-orange"><i class="fa fa-mail-reply-all"></i>&nbsp;Back</button></a> <a href="pay_bills.php?id=<?php echo $_SESSION['tid']; ?>&&mid=NDA0"><button type="button" class="btn btn-flat bg-orange"><i class="fa fa-refresh"></i>&nbsp;Refresh</button></a>
            <a href="pay_bill1.php?tid=<?php echo $_SESSION['tid']; ?>&&mid=NDA0"><button type="button" class="btn btn-flat bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>"><i class="fa fa-laptop"></i>&nbsp;Pay other Bills</button></a>
            </h3>
            </div>

             <div class="box-body">
          
<!--- AIRTIME FORM START HERE -->
          
			 <form class="form-horizontal" method="post" enctype="multipart/form-data">
<hr>
<div class="alert bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>">Airtime Topup Form!</div>
</hr>
             <div class="box-body">

      <div class="form-group">
          <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">Phone Number: </label>
          <div class="col-sm-9">
            <input name="phone" type="text" class="form-control" id="my_phone_no" onkeydown="load_airtime();" placeholder="e.g +2348111100001, +16158479894" required>
            <span style="font-size: 14px;"><b>NOTE:</b><i> Please enter your phone number in this format <b>(COUNTRY CODE + LAST 10 DIGIT NUNBER)</b> e.g  +2348111100001, +16158479894 etc.</i></span>
          </div>
      </div>
      
      <div id="airtime_list"></div>
			
	</div>

        <div align="right">
          <div class="box-footer">
              <button name="PayBill1" type="submit" class="btn bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?> btn-flat"><i class="fa fa-mobile">&nbsp;Buy Airtime</i></button>
          </div>
        </div>

<?php
if(isset($_POST['PayBill1']))
{
    $jsonData=array();
	$search_billconfig = mysqli_query($link, "SELECT * FROM billpayment");
	$fetch_billconfig = mysqli_fetch_object($search_billconfig);
	
	$url = 'https://estoresms.com/network_processing/v/2'; //API Url (Do not change)
	//$url = '$fetch_billconfig->api_url'; //API Url (Do not change)
    $token=$fetch_billconfig->token; //Replace with your API token
    $email=$fetch_billconfig->email;  //Replace with your account email address
    $username=$fetch_billconfig->username;  // Replace with your account username
    $myref = "txid-".rand(100000,999999);;
    $phone =  mysqli_real_escape_string($link, $_POST['phone']);
    $operator =  mysqli_real_escape_string($link, $_POST['operator']);
    $airtime_product =  mysqli_real_escape_string($link, $_POST['airtime_product']);
    $amount_recharge =  mysqli_real_escape_string($link, $_POST['amount_recharge']);
    $currency = $fetchsys_config['currency'];
    
    if($amount_recharge < 100){
	    
	    echo "<script>alert('You cannot recharge more than the amount given in the ranges!!'); </script>";
	    
		echo "<script>window.location='mywallet.php?id=".$_SESSION['tid']."&&mid=NDA0'; </script>";
		
	}
	else{
	    //Initiate cURL.
        $ch = curl_init($url);
        
        $jsonData['username']=$username;
        
        //Ref
        $jsonData['ref']=$myref;
        
        //Callback URL
        $jsonData['callback']="https://app.esusu.africa/application/pay_bills.php";
        
        //Generate Hash
        $jsonData['hash']=hash('sha512',$token.$email.$username.$myref);
        	  
        //MAKE REQUESTS (Duplicate this line to make multiple requests at a time)
        $jsonData['request'][]=['product'=>$airtime_product,'phone'=>$phone,'amount'=>$amount_recharge];
        
        //Encode the array into JSON.
        $jsonDataEncoded = json_encode($jsonData);
        
        //Send as a POST request.
        curl_setopt($ch, CURLOPT_POST, 1);
        
        //Attach encoded JSON string to the POST fields.
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonDataEncoded);
        
        //Set the content type as application/json
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        
        //Allow parsing response to string
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        
        //Execute the request
        $response=curl_exec($ch);
        
        $jsonData = json_decode($response, true);
        
        curl_close ($ch);

		if($jsonData['failed'] == "0")
		{
		    foreach($jsonData['result'] as $newOutput){
		        
		        if($newOutput['response'] == "OK"){
		            
		            //successful transaction
		            $transaction_id = $newOutput['transaction_id'];
		            $amount = $newOutput['amount'];
        		    $insert = mysqli_query($link, "INSERT INTO wallet_history VALUES(null,'','$transaction_id','$phone','$amount','$currency','Airtime - WEB','Airtime Topup for $operator with refid: $myref via WEB','successful',NOW())");
        		    
		            echo "<script>alert('Airtime Purchased Successfully!'); </script>";
		            echo "<script>window.location='pay_bills.php?id=".$_SESSION['tid']."&&mid=NDA0'; </script>";
		            
		        }elseif($newOutput['response'] == "PENDING"){
		            
		            //pending transaction
		            $transaction_id = $newOutput['transaction_id'];
		            $amount = $newOutput['amount'];
	                //$remainBalance = $cwallet_balance - (($ccurrency != "NGN") ? $amount_recharge : $newOutput['amount_charged']);
        		    $insert = mysqli_query($link, "INSERT INTO wallet_history VALUES(null,'','$transaction_id','$phone','$amount','$currency','Airtime - WEB','Airtime Topup for $operator with refid: $myref via WEB','pending',NOW())");
        		    
		            echo "<script>alert('Airtime Purchased in Proceessing...!'); </script>";
		            echo "<script>window.location='pay_bills.php?id=".$_SESSION['tid']."&&mid=NDA0'; </script>";
		            
		        }
		        
		    }
		}elseif($jsonData['response'] == "P013"){
		    
		    echo "<script>alert('Oops! Network Error, please try again later!!'); </script>";
		    
		}else{
		    
		    echo "<script>alert('".$newOutput['message']."'); </script>";
		    
		}
		
	}
}
?>
			 </form>
			 
			 
			 
<!--- DATA BUNDLE FORM START HERE -->			 

			 
			 
			 <form class="form-horizontal" method="post" enctype="multipart/form-data">
<hr>
<div class="alert bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>">Data Bundle Form!</div>
</hr>
             <div class="box-body">

      <div class="form-group">
          <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">Phone Number: </label>
          <div class="col-sm-9">
            <input name="myphone" type="text" class="form-control" id="myphone_no" onkeydown="load_databundle();" placeholder="e.g +2348111100001" required>
            <span style="font-size: 14px;"><b>NOTE:</b><i> Please enter your phone number in this format <b>(COUNTRY CODE + LAST 10 DIGIT NUNBER)</b> e.g  +2348111100001 etc.</i></span>
          </div>
      </div>
      
      <div id="databundle_list"></div>
      <div id="progress"></div>
     
			
	</div>

        <div align="right">
          <div class="box-footer">
              <button name="PayBill2" type="submit" class="btn bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?> btn-flat"><i class="fa fa-mobile">&nbsp;Buy Data</i></button>
          </div>
        </div>

<?php
if(isset($_POST['PayBill2']))
{
    $jsonData=array();
	$search_billconfig = mysqli_query($link, "SELECT * FROM billpayment");
	$fetch_billconfig = mysqli_fetch_object($search_billconfig);
	
	$acn = $_SESSION['acctno'];
	$url = 'https://estoresms.com/data_processing/v/2'; //API Url (Do not change)
	//$url = '$fetch_billconfig->api_url'; //API Url (Do not change)
    $token=$fetch_billconfig->token; //Replace with your API token
    $email=$fetch_billconfig->email;  //Replace with your account email address
    $username=$fetch_billconfig->username;  // Replace with your account username
    $myref = "txid-".rand(100000,999999);;
    $phone =  mysqli_real_escape_string($link, $_POST['myphone']);
    $operator =  mysqli_real_escape_string($link, $_POST['operator']);
    $databundle_product =  mysqli_real_escape_string($link, $_POST['databundle_product']);
    $amount_recharge =  mysqli_real_escape_string($link, $_POST['amount_recharge']);
    
    if($amount_recharge < 0){
	    
	    echo "<script>alert('Invalid Amount Entered!!'); </script>";
	    
		echo "<script>window.location='mywallet.php?id=".$_SESSION['tid']."&&mid=NDA0'; </script>";
		
	}
	else{
	    //Initiate cURL.
        $ch = curl_init($url);
        
        $jsonData['username']=$username;
        
        //Ref
        $jsonData['ref']=$myref;
        
        //Callback URL
        $jsonData['callback']="https://app.esusu.africa/application/pay_bills.php";
        
        //Generate Hash
        $jsonData['hash']=hash('sha512',$token.$email.$username.$myref);
        	  
        //MAKE REQUESTS (Duplicate this line to make multiple requests at a time)
        //$jsonData['request'][]=['product'=>$airtime_product,'phone'=>$phone];
        $jsonData['request'][]=['product'=>$databundle_product,'phone'=>$phone,'amount'=>$amount_recharge];
        
        //Encode the array into JSON.
        $jsonDataEncoded = json_encode($jsonData);
        
        //Send as a POST request.
        curl_setopt($ch, CURLOPT_POST, 1);
        
        //Attach encoded JSON string to the POST fields.
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonDataEncoded);
        
        //Set the content type as application/json
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        
        //Allow parsing response to string
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        
        //Execute the request
        $response=curl_exec($ch);
        
        $jsonData = json_decode($response, true);
        
        curl_close ($ch);

		if($jsonData['failed'] == "0")
		{
		    foreach($jsonData['result'] as $newOutput){
		        
		        if($newOutput['response'] == "OK"){
		            
		            //successful transaction
		            $transaction_id = $newOutput['transaction_id'];
		            //$data_amount = $newOutput['data_amount'];
		            $amount = $newOutput['amount'];
        		    $insert = mysqli_query($link, "INSERT INTO wallet_history VALUES(null,'','$transaction_id','$phone','$amount','$currency','Databundle - WEB','$operator Databundle Purchase for $databundle_product with refid: $myref via WEB','successful',NOW())");
        		    
		            echo "<script>alert('Databundle Purchased Successfully!'); </script>";
		            echo "<script>window.location='pay_bills.php?id=".$_SESSION['tid']."&&mid=NDA0'; </script>";
		            
		        }elseif($newOutput['response'] == "PENDING"){
		            
		            //pending transaction
		            $transaction_id = $newOutput['transaction_id'];
		            $amount = $newOutput['amount'];
	                //$remainBalance = $cwallet_balance - (($ccurrency != "NGN") ? $amount_recharge : $newOutput['amount_charged']);
        		    $insert = mysqli_query($link, "INSERT INTO wallet_history VALUES(null,'','$transaction_id','$phone','$amount','$currency','Databundle - WEB','$operator Databundle Purchase for $databundle_product with refid: $myref via WEB','pending',NOW())");

		            echo "<script>alert('Databundle Purchased in Proceessing...!'); </script>";
		            echo "<script>window.location='pay_bills.php?id=".$_SESSION['tid']."&&mid=NDA0'; </script>";
		            
		        }
		        
		    }
		}elseif($jsonData['response'] == "P013"){
		    
		    echo "<script>alert('Oops! Network Error, please try again later!!'); </script>";
		    
		}else{
		    
		    echo "<script>alert('".$newOutput['message']."'); </script>";
		    
		}
		
	}
}
?>
			 </form> 

</div>	
</div>	
</div>
</div>