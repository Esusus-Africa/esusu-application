<div class="box">
	       <div class="box-body">
			<div class="panel panel-success">
            <div class="panel-heading bg-blue">
            <h3 class="panel-title"> <a href="mywallet.php?id=<?php echo $_SESSION['tid']; ?>&&mid=NDA0&&tab=tab_1"><button type="button" class="btn btn-flat bg-orange"><i class="fa fa-mail-reply-all"></i>&nbsp;Back</button></a> <a href="pay_bills.php?id=<?php echo $_SESSION['tid']; ?>&&mid=NDA0"><button type="button" class="btn btn-flat bg-orange"><i class="fa fa-refresh"></i>&nbsp;Refresh</button></a> 
<?php
$my_systemset = mysqli_query($link, "SELECT * FROM systemset");
$my_row1 = mysqli_fetch_object($my_systemset);
$vat_rate = $my_row1->vat_rate;
$transfer_charges = $my_row1->transfer_charges;
?> 
<button type="button" class="btn btn-flat bg-blue" align="left">&nbsp;<b>Transfer Wallet:</b>&nbsp;
<strong class="alert bg-orange">
<?php
echo $aggcurrency.number_format($aggwallet_balance,2,'.',',');
?> 
</strong>
</button>

  
            </h3>
            </div>


             <div class="box-body">

<div class="slideshow-container">
<div class="alert bg-orange" style="font-size:17px;" align="center">
    <p>
        <?php
        //echo $walletafrica_status;
        if($mo_virtualacct_status == "Active" && ($walletafrica_status == "NotActive" || $walletafrica_status == "Active")){
            
            include("../config/monnify_virtualaccount_esusu.php");
            
        }
        elseif($mo_virtualacct_status == "NotActive" && $walletafrica_status == "Active"){
            
            include("../config/walletafrica_restfulapis_call.php");
            include("walletafrica_virtulaccount.php");
            
        }
        ?>
    </p>
</div>

<a class="myprev" onclick="plusSlides(-1)">&#10094;</a>
<a class="mynext" onclick="plusSlides(1)">&#10095;</a>
</div> 
          
<!--- AIRTIME FORM START HERE -->
          
			 <form class="form-horizontal" method="post" enctype="multipart/form-data">

             <div class="box-body">
                 
                 <div class="form-group">
                    <label for="" class="col-sm-3 control-label" style="color:blue;">Phone Number:</label>
                    <div class="col-sm-6">
                        <input name="myphone" type="text" class="form-control" id="phone">
                    </div>
                    <label for="" class="col-sm-3 control-label" align="left"></label>
                  </div>
                  
                  <div class="form-group">
                    <label for="" class="col-sm-3 control-label" style="color:blue;"></label>
                    <div class="col-sm-6">
                        <button name="Verifybill1" type="submit" onclick="myFunction()" class="btn bg-blue btn-flat"><i class="fa fa-search">&nbsp;Verify</i></button>
                    </div>
                    <label for="" class="col-sm-3 control-label" align="left"></label>
                  </div>
        
<?php
if(isset($_POST['Verifybill1'])){
    
    include("../config/walletafrica_restfulapis_call.php");
    
    $jsonData=array();
	$search_billconfig = mysqli_query($link, "SELECT * FROM billpayment");
	$fetch_billconfig = mysqli_fetch_object($search_billconfig);
	
	$url = 'https://estoresms.com/network_list/v/2'; //API Url (Do not change)
	//$url = '$fetch_billconfig->api_url'; //API Url (Do not change)
    $token=$fetch_billconfig->token; //Replace with your API token
    $email=$fetch_billconfig->email;  //Replace with your account email address
    $username=$fetch_billconfig->username;  // Replace with your account username
    $ref = "txid-".time();
    $phone = mysqli_real_escape_string($link, $_POST['myphone']);
    
    if($phone == ""){
        
        echo "<script>alert('Oops!..Phone Number cannot be empty!!'); </script>";
        
    }else{

        //Initiate cURL.
        $ch = curl_init($url);
        
        $jsonData['username']=$username;
        
        //Ref
        //$jsonData['ref']=$ref;
        
        //Generate Hash
        $jsonData['hash']=hash('sha512',$token.$email.$username);
        
        //Category
        $jsonData['phone']=$phone;
        
        //$jsonData['product']='BPI-NGCA-ANA';
        	  
        //Send as a POST request.
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
        
        //curl_close ($ch);
        
        if($response){
      	   $jsonData = json_decode($response, true);
      	   //var_dump($response);
      	   if(($jsonData['response'] == "OK") && ($jsonData['info']['openRange'] == true) && $walletafrica_airtimestatus != "Active") {
    
                    echo '<div class="form-group">
                                <label for="" class="col-sm-3 control-label" style="color:'.(($myaltrow['theme_color'] == "") ? "blue" : $myaltrow['theme_color']).';">Receiver:</label>
                                <div class="col-sm-6">
                                    <input name="phone" type="text" class="form-control" value="'.$phone.'" required/>
                                </div>
                                <label for="" class="col-sm-3 control-label"></label>
                            </div>
                            
                        <div class="form-group">
                                <label for="" class="col-sm-3 control-label" style="color:'.(($myaltrow['theme_color'] == "") ? "blue" : $myaltrow['theme_color']).';">Operator:</label>
                                <div class="col-sm-6">
                                    <input name="operator" type="text" class="form-control" value="'.$jsonData['info']['operator'].' ('.$jsonData['info']['country'].')" readonly/>
                                </div>
                                <label for="" class="col-sm-3 control-label"></label>
                            </div>';
    
                          foreach ($jsonData['products'] as $pkey) {
    
                            echo '<input name="airtime_product" type="hidden" class="form-control" value="'.$pkey['id'].'"/>';
    
                          }
    
                    echo '<div class="form-group">
                                <label for="" class="col-sm-3 control-label" style="color:'.(($myaltrow['theme_color'] == "") ? "blue" : $myaltrow['theme_color']).';">Amount:</label>
                                <div class="col-sm-6">
                                    <input name="amount_recharge" type="number" class="form-control" placeholder="Enter Amount" required/>
                                    <span style="font-size: 14px;"><b>NOTE:</b> <i>Minimum Amount you can Recharge is <b>NGN50</b> AND Maximum is NGN200000</i></span>
                                </div>
                                <label for="" class="col-sm-3 control-label"></label>
                            </div>';
    
              }elseif(($jsonData['response'] == "OK") && ($jsonData['info']['openRange'] == true) && $walletafrica_status == "Active") {
    
                    echo '<div class="form-group">
                                <label for="" class="col-sm-3 control-label" style="color:'.(($myaltrow['theme_color'] == "") ? "blue" : $myaltrow['theme_color']).';">Receiver:</label>
                                <div class="col-sm-6">
                                    <input name="phone" type="text" class="form-control" value="'.$phone.'" required/>
                                </div>
                                <label for="" class="col-sm-3 control-label"></label>
                            </div>
                            
                        <div class="form-group">
                                <label for="" class="col-sm-3 control-label" style="color:'.(($myaltrow['theme_color'] == "") ? "blue" : $myaltrow['theme_color']).';">Airtime Provider:</label>
                                <div class="col-sm-6">
                                <select name="operator" class="form-control"  required style="width:100%">
                                  <option value="" selected="selected">Select Airtime Provider...</option>
                                  <option value="airtel">AIRTEL</option>
                                  <option value="mtn">MTN</option>
                                  <option value="glo">GLO</option>
                                  <option value="etisalat">ETISALAT</option>
                                </select>
                                </div>
                                <label for="" class="col-sm-3 control-label"></label>
                            </div>';
    
                    echo '<div class="form-group">
                                <label for="" class="col-sm-3 control-label" style="color:'.(($myaltrow['theme_color'] == "") ? "blue" : $myaltrow['theme_color']).';">Amount:</label>
                                <div class="col-sm-6">
                                    <input name="amount_recharge" type="number" class="form-control" placeholder="Enter Amount" required/>
                                    <span style="font-size: 14px;"><b>NOTE:</b> <i>Minimum Amount you can Recharge is <b>NGN50</b> AND Maximum is NGN200000</i></span>
                                </div>
                                <label for="" class="col-sm-3 control-label"></label>
                            </div>';
    
              }elseif(($jsonData['response'] == "OK") && ($jsonData['info']['openRange'] == false) && $walletafrica_airtimestatus != "Active") {
    
                    echo '<div class="form-group">
                                <label for="" class="col-sm-3 control-label" style="color:'.(($myaltrow['theme_color'] == "") ? "blue" : $myaltrow['theme_color']).';">Receiver:</label>
                                <div class="col-sm-6">
                                    <input name="phone" type="text" class="form-control" value="'.$phone.'" required/>
                                </div>
                                <label for="" class="col-sm-3 control-label"></label>
                            </div>
                            
                            <div class="form-group">
                                <label for="" class="col-sm-3 control-label" style="color:'.(($myaltrow['theme_color'] == "") ? "blue" : $myaltrow['theme_color']).';">Operator:</label>
                                <div class="col-sm-6">
                                    <input name="operator" type="text" class="form-control" value="'.$jsonData['info']['operator'].' ('.$jsonData['info']['country'].')" readonly/>
                                </div>
                                <label for="" class="col-sm-3 control-label"></label>
                            </div>';
    
                    echo '<div class="form-group">
                                <label for="" class="col-sm-3 control-label" style="color:'.(($myaltrow['theme_color'] == "") ? "blue" : $myaltrow['theme_color']).';">Airtime Product</label>
                                <div class="col-sm-6">
                                    <select name="airtime_product"  class="form-control"  required style="width:100%">
                                  <option value="" selected="selected">Select Airtime Product...</option>';
                                  //$a = 0;
                                    foreach ($jsonData['products'] as $pkey) {
                                        //$a++;
                                      echo '<option value="'.$pkey['id'].'">'.$pkey['topup_currency'].' '.$pkey['topup_amount'].' (NGN'.number_format($pkey['price'],2,'.',',').')</option>';
            
                                    }
                            echo '</select></div>
                                <label for="" class="col-sm-3 control-label"></label>
                            </div>';
                            
                    echo '<div class="form-group">
                                <label for="" class="col-sm-3 control-label" style="color:'.(($myaltrow['theme_color'] == "") ? "blue" : $myaltrow['theme_color']).';">Airtime Amount</label>
                                <div class="col-sm-6">
                                    <select name="amount_recharge"  class="form-control"  required style="width:100%">
                                      <option value="" selected="selected">Select Airtime Amount...</option>';
                                      //$a = 0;
                                        foreach ($jsonData['products'] as $pkey) {
                                            //$a++;
                                          echo '<option value="'.$pkey['topup_amount'].'">'.$pkey['topup_currency'].' '.$pkey['topup_amount'].'</option>';
                
                                        }
                            echo '</select><span style="font-size: 14px;"><b>NOTE:</b> <i>Select the amount acccording to the plan selected</span></div>
                                <label for="" class="col-sm-3 control-label"></label>
                            </div>';
    
              } 
    		        
      	   }else{
      	       
      	       echo "<br><label class='label bg-orange'>Oops!...Network Error, Please try again later!!</label>";
      	       
      	   }
    }
}
?>
      <!--<div id="airtime_list"></div>-->
      
                <div class="form-group">
                    <label for="" class="col-sm-3 control-label" style="color:blue;">Transaction Pin:</label>
                    <div class="col-sm-6">
                        <input name="epin" type="password" class="form-control" placeholder="Transaction Pin" maxlength="4">
                    </div>
                    <label for="" class="col-sm-3 control-label"></label>
                </div>
			
	        </div>
	        
	        <div class="form-group" align="right">
                <label for="" class="col-sm-3 control-label" style="color:blue;"></label>
                <div class="col-sm-6">
                    <button name="PayBill1" type="submit" class="btn bg-blue btn-flat"><i class="fa fa-mobile">&nbsp;Buy Airtime</i></button>
                </div>
                <label for="" class="col-sm-3 control-label"></label>
             </div>

<?php
if(isset($_POST['PayBill1']))
{
    include("../config/walletafrica_restfulapis_call.php");
    
    $jsonData=array();
    $result=array();
    $result1=array();
    $result2=array();
    $result3=array();
	$search_billconfig = mysqli_query($link, "SELECT * FROM billpayment");
	$fetch_billconfig = mysqli_fetch_object($search_billconfig);
	
	$url = 'https://estoresms.com/network_processing/v/2'; //API Url (Do not change)
	//$url = '$fetch_billconfig->api_url'; //API Url (Do not change)
    $token=$fetch_billconfig->token; //Replace with your API token
    $email=$fetch_billconfig->email;  //Replace with your account email address
    $username=$fetch_billconfig->username;  // Replace with your account username
    
    $myref = "txid-".time();
    $phone =  mysqli_real_escape_string($link, $_POST['phone']);
    $operator =  mysqli_real_escape_string($link, $_POST['operator']);
    $airtime_product =  mysqli_real_escape_string($link, $_POST['airtime_product']);
    $amount_recharge =  mysqli_real_escape_string($link, $_POST['amount_recharge']);
    $epin =  mysqli_real_escape_string($link, $_POST['epin']);
    $datetime = date("Y-m-d h:i:s");
    
    if($aggwallet_balance < $amount_recharge){
	    
	    echo "<script>alert('Insufficient Fund in your Wallet!!'); </script>";
	    
		echo "<script>window.location='mywallet.php?id=".$_SESSION['tid']."&&mid=NDA0'; </script>";
		
	}elseif($epin != $control_pin || $epin == ""){
	    
	    echo "<script>alert('Invalid Transaction Pin!'); </script>";
	    
		echo "<script>window.location='pay_bills.php?id=".$_SESSION['tid']."&&mid=NDA0'; </script>";
	    
	}elseif($walletafrica_airtimestatus == "Active" && $otp_option == "No"){
            
        //Get my wallet balance after debiting
        $senderBalance = $aggwallet_balance - $amount_recharge;
            
        $search_restapi1 = mysqli_query($link, "SELECT * FROM restful_apisetup WHERE api_name = 'walletafrica_airtime'");
        $fetch_restapi1 = mysqli_fetch_object($search_restapi1);
        $api_url1 = $fetch_restapi1->api_url;
            
        $postdata1 =  array(
            "Code" => $operator,
            "Amount" => $amount_recharge,
            "PhoneNumber" => $ph,
            "SecretKey" => $walletafrica_skey
        );
                               
        $make_call1 = callAPI('POST', $api_url1, json_encode($postdata1));
        $result1 = json_decode($make_call1, true);
            
        if($result1['ResponseCode'] == "100"){
                
            $insert = mysqli_query($link, "INSERT INTO wallet_history VALUES(null,'$aggmerchant','$myref','$ph','','$amount_recharge','Debit','$aggcurrency','Airtime - WEB','Airtime Topup for $operator with refid: $myref via WEB','successful','$datetime','$aggr_id','$senderBalance','')");
        		    
        	  //$insert = mysqli_query($link, "INSERT INTO wallet_history VALUES(null,'$acctno','$myref','$ph','$commission','$bbcurrency','Commission - WEB','Airtime Topup for $operator with refid: $myref via WEB','successful','$datetime')");
        		    
        	  $update_records = mysqli_query($link, "UPDATE user SET transfer_balance = '$senderBalance' WHERE id = '$aggr_id'");
		            
		        echo "<div class='alert bg-blue'>Airtime Purchased Successfully!</div>";
		        echo '<meta http-equiv="refresh" content="2;url=pay_bills.php?id='.$_SESSION['tid'].'&&mid=NDA0">';
        
        }
        else{
                
            echo "<div class='alert bg-orange'>Opps!...Network Error, Please Try again later</div>";
            echo '<meta http-equiv="refresh" content="2;url=pay_bills.php?id='.$_SESSION['tid'].'&&mid=NDA0">';
                
        }
	    
	}else{
	    //Initiate cURL.
        $ch = curl_init($url);
        
        $jsonData['username']=$username;
        
        //Ref
        $jsonData['ref']=$myref;
        
        //Callback URL
        $jsonData['callback']="https://esusu.app/aggregator/pay_bills.php";
        
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
                    $commission = $newOutput['amount'] * $airtimeDataCommission;
                    $amount = $newOutput['amount'] - $commission;
                        
                    //Get my wallet balance after debiting
                    $senderBalance2 = $aggwallet_balance - $amount;
                        
                      //$remainBalance = $aggwallet_balance - $amount;
                    $insert = mysqli_query($link, "INSERT INTO wallet_history VALUES(null,'$aggmerchant','$transaction_id','$phone','','$amount','Debit','$aggcurrency','Airtime - WEB','Airtime Topup for $operator with refid: $myref via WEB','successful','$datetime','$aggr_id','$senderBalance2','')");
                    
                    $insert = mysqli_query($link, "INSERT INTO wallet_history VALUES(null,'$aggmerchant','$transaction_id','$phone','$commission','','Credit','$aggcurrency','Commission - WEB','Airtime Topup Commission for $operator with refid: $myref via WEB','successful','$datetime','$aggr_id','$senderBalance2','')");
                    
                    mysqli_query($link, "UPDATE user SET transfer_balance = '$senderBalance2' WHERE id = '$aggr_id'");
                    
                    echo "<script>alert('Airtime Purchased Successfully!'); </script>";
                    echo "<script>window.location='pay_bills.php?id=".$_SESSION['tid']."&&mid=NDA0'; </script>";
                    
                }elseif($newOutput['response'] == "PENDING"){
                    
                    //pending transaction
                    $transaction_id = $newOutput['transaction_id'];
                    $commission = $newOutput['amount'] * $airtimeDataCommission;
                    $amount = $newOutput['amount'] - $commission;
                        
                      //$remainBalance = $cwallet_balance - (($ccurrency != "NGN") ? $amount_recharge : $newOutput['amount_charged']);
                    $insert = mysqli_query($link, "INSERT INTO wallet_history VALUES(null,'$aggmerchant','$transaction_id','$phone','','$amount','Debit','$aggcurrency','Airtime - WEB','Airtime Topup for $operator with refid: $myref via WEB','pending','$datetime','$aggr_id','$aggwallet_balance','')");
                    
                    $insert = mysqli_query($link, "INSERT INTO wallet_history VALUES(null,'$aggmerchant','$transaction_id','$phone','$commission','','Credit','$aggcurrency','Commission - WEB','Airtime Topup Commission for $operator with refid: $myref via WEB','pending','$datetime','$aggr_id','$aggwallet_balance','')");
                    
                    //$update_records = mysqli_query($link, "UPDATE cooperatives SET wallet_balance = '$remainBalance' WHERE coopid = '$coopid'");
                    
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


<?php
if (isset($_POST['confirm']))
{
    include("../config/walletafrica_restfulapis_call.php");
    
    $result = array();
    $result1 = array();
    $result2 = array();
    
    $myotp = $_POST['otp'];
				    
    $verify_otp = mysqli_query($link, "SELECT * FROM otp_confirmation WHERE otp_code = '$myotp' AND userid = '$aggr_id' AND status = 'Pending'");
    $fetch_data = mysqli_fetch_array($verify_otp);
    $otpnum = mysqli_num_rows($verify_otp);
    
    $concat = $fetch_data['data'];
    
    $datetime = $fetch_data['datetime'];
    
    $parameter = (explode('|',$concat));
    
    $myref = $parameter[0];
    $phone = $parameter[1];
    $operator = $parameter[2];
    $airtime_product = $parameter[3];
    $amount_recharge = $parameter[4];
    $currenctdate = date("Y-m-d H:i:s");
						    
    if($otpnum == 0){
                      
        echo "<div class='alert bg-orange'>Opps!...Invalid Credentials!!</div>";
                      
    }elseif($walletafrica_airtimestatus == "Active"){
            
            //Get my wallet balance after debiting
            $senderBalance = $aggwallet_balance - $amount_recharge;
            
            $search_restapi1 = mysqli_query($link, "SELECT * FROM restful_apisetup WHERE api_name = 'walletafrica_airtime'");
            $fetch_restapi1 = mysqli_fetch_object($search_restapi1);
            $api_url1 = $fetch_restapi1->api_url;
            
            $postdata1 =  array(
                "Code" => $operator,
                "Amount" => $amount_recharge,
                "PhoneNumber" => $phone,
                "SecretKey" => $walletafrica_skey
            );
                               
            $make_call1 = callAPI('POST', $api_url1, json_encode($postdata1));
            $result1 = json_decode($make_call1, true);
            
            //print_r($result1);
            
            if($result1['ResponseCode'] == "100"){
                
                $insert = mysqli_query($link, "INSERT INTO wallet_history VALUES(null,'$aggmerchant','$myref','$ph','','$amount_recharge','Debit','$aggcurrency','Airtime - WEB','Airtime Topup for $operator with refid: $myref via WEB','successful','$currenctdate','$aggr_id','$senderBalance','')");
        		            		    
        		    $update_records = mysqli_query($link, "UPDATE user SET transfer_balance = '$senderBalance' WHERE id = '$aggr_id'");
        		
        		    mysqli_query($link, "DELETE FROM otp_confirmation WHERE userid = '$aggr_id' AND otp_code = '$myotp' AND status = 'Pending'");
		            
		            echo "<div class='alert bg-blue'>Airtime Purchased Successfully!</div>";
		            echo '<meta http-equiv="refresh" content="2;url=pay_bills.php?id='.$_SESSION['tid'].'&&mid=NDA0">';
                
            }           
            else{
            
                echo "<div class='alert bg-orange'>Opps!...unable to process request, please try again later</div>";
            
            }
	    
	}
	else{
	    
	    $jsonData=array();
    	$search_billconfig = mysqli_query($link, "SELECT * FROM billpayment");
    	$fetch_billconfig = mysqli_fetch_object($search_billconfig);
    	
    	$url = 'https://estoresms.com/network_processing/v/2'; //API Url (Do not change)
    	//$url = '$fetch_billconfig->api_url'; //API Url (Do not change)
        $token=$fetch_billconfig->token; //Replace with your API token
        $email=$fetch_billconfig->email;  //Replace with your account email address
        $username=$fetch_billconfig->username;  // Replace with your account username
	    
        //Initiate cURL.
        $ch = curl_init($url);
        
        $jsonData['username']=$username;
        
        //Ref
        $jsonData['ref']=$myref;
        
        //Callback URL
        $jsonData['callback']="https://esusu.app/aggregator/pay_bills.php";
        
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
		            $commission = $newOutput['amount'] * $airtimeDataCommission;
		            $amount = $newOutput['amount'] - $commission;
                    
                //Get my wallet balance after debiting
                $senderBalance = $aggwallet_balance - $amount;
                    
	              //$remainBalance = $bwallet_balance - $amount;
        		    $insert = mysqli_query($link, "INSERT INTO wallet_history VALUES(null,'$aggmerchant','$transaction_id','$phone','','$amount','Debit','$aggcurrency','Airtime - WEB','Airtime Topup for $operator with refid: $myref via WEB','successful','$datetime','$aggr_id','$senderBalance','')");
        		    
        		    $insert = mysqli_query($link, "INSERT INTO wallet_history VALUES(null,'$aggmerchant','$transaction_id','$phone','$commission','','Credit','$aggcurrency','Commission - WEB','Airtime Topup for $operator with refid: $myref via WEB','successful','$datetime','$aggr_id','$senderBalance','')");
        		    
        		    mysqli_query($link, "UPDATE user SET transfer_balance = '$senderBalance' WHERE id = '$aggr_id'");
        		    
        		    mysqli_query($link, "DELETE FROM otp_confirmation WHERE userid = '$aggr_id' AND otp_code = '$myotp' AND status = 'Pending'");
		            
		            echo "<div class='alert bg-blue'>Airtime Purchased Successfully!</div>";
		            echo '<meta http-equiv="refresh" content="2;url=pay_bills.php?id='.$_SESSION['tid'].'&&mid=NDA0">';
		            
		        }elseif($newOutput['response'] == "PENDING"){
		            
		            //pending transaction
		            $transaction_id = $newOutput['transaction_id'];
		            $commission = $newOutput['amount'] * $airtimeDataCommission;
		            $amount = $newOutput['amount'] - $commission;
                    
	              //$remainBalance = $cwallet_balance - (($ccurrency != "NGN") ? $amount_recharge : $newOutput['amount_charged']);
        		    $insert = mysqli_query($link, "INSERT INTO wallet_history VALUES(null,'$aggmerchant','$transaction_id','$phone','','$amount','Debit','$aggcurrency','Airtime - WEB','Airtime Topup for $operator with refid: $myref via WEB','pending','$datetime','$aggr_id','$aggwallet_balance','')");
        		    
        		    $insert = mysqli_query($link, "INSERT INTO wallet_history VALUES(null,'$aggmerchant','$transaction_id','$phone','$commission','','Credit','$aggcurrency','Commission - WEB','Airtime Topup for $operator with refid: $myref via WEB','pending','$datetime','$aggr_id','$aggwallet_balance','')");
        		    
        		    mysqli_query($link, "DELETE FROM otp_confirmation WHERE userid = '$aggr_id' AND otp_code = '$myotp' AND status = 'Pending'");
        		    //$update_records = mysqli_query($link, "UPDATE cooperatives SET wallet_balance = '$remainBalance' WHERE coopid = '$coopid'");
		            
		            echo "<div class='alert bg-blue'>Airtime Purchased in Proceessing...!</div>";
		            echo '<meta http-equiv="refresh" content="2;url=pay_bills.php?id='.$_SESSION['tid'].'&&mid=NDA0">';
		            
		        }
		        
		    }
		}elseif($jsonData['response'] == "P013"){
		    
		    echo "<div class='alert bg-orange'>Oops! Network Error, please try again later!!</div>";

		}else{
		    
		    echo "<div class='alert bg-orange'>".$newOutput['message']."</div>";

		}
						        
	}
						    
}
?>
			 </form>
			 

</div>	
</div>	
</div>
</div>