<div class="row">
	      <section class="content">
	          
       <div class="box box-default">
           <div class="box-body">
             <div class="table-responsive"> 
            <div class="box-body">

<button type="button" class="btn btn-flat bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>" align="left" disabled>&nbsp;<b>Super Wallet:</b>&nbsp;
    <strong class="alert bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">
    <?php
    echo "<span id='wallet_balance'>".$vcurrency.number_format($vwallet_balance,2,'.',',')."</span>";
    ?> 
    </strong>
</button>

<button type="button" class="btn btn-flat bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>" align="left" disabled>&nbsp;<b>Transfer Wallet:</b>&nbsp;
    <strong class="alert bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">
    <?php
    echo $vcurrency.number_format($vtransfer_balance,2,'.',',');
    ?> 
    </strong>
</button>


<?php
$search_memset = mysqli_query($link, "SELECT * FROM member_settings WHERE companyid = '$vcreated_by'");
$fetch_memset = mysqli_fetch_array($search_memset);
$votp_option = $fetch_memset['otp_option'];
?>

<hr>
<div class="slideshow-container">
<div class="alert bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>" style="font-size:17px;" align="center">
    <p>
        <?php
        //echo $walletafrica_status;
        if($mo_virtualacct_status == "Active" && ($walletafrica_status == "NotActive" || $walletafrica_status == "Active")){
            
            include("../config/monnify_virtualaccount.php");
            
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
</hr>


             <div class="box-body">
          
<!--- AIRTIME FORM START HERE -->
          
			 <form class="form-horizontal" method="post" enctype="multipart/form-data">

             <div class="box-body">
                 
                 <div class="form-group">
                    <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Phone Number:</label>
                    <div class="col-sm-6">
                        <input name="myphone" type="text" class="form-control" id="phone">
                    </div>
                    <label for="" class="col-sm-3 control-label" align="left"></label>
                  </div>
                  
                  <div class="form-group">
                    <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;"></label>
                    <div class="col-sm-6">
                        <button name="Verifybill1" type="submit" onclick="myFunction()" class="btn bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?> btn-flat"><i class="fa fa-search">&nbsp;Verify</i></button>
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
                    <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Transaction Pin:</label>
                    <div class="col-sm-6">
                        <input name="epin" type="password" class="form-control" placeholder="Transaction Pin" maxlength="4">
                    </div>
                    <label for="" class="col-sm-3 control-label"></label>
                </div>
			
	        </div>
	        
	        <div class="form-group" align="right">
                <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;"></label>
                <div class="col-sm-6">
                    <button name="PayBill1" type="submit" class="btn bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?> btn-flat"><i class="fa fa-mobile">&nbsp;Buy Airtime</i></button>
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
    $currenctdate = date("Y-m-d h:i:s");

    $debitWAllet = ($vgetSMS_ProviderNum == 1) ? "No" : "Yes";
    
    if($vactive_status == "Suspended"){
        
        echo "<div class='alert bg-orange'>Oops! You are unable to make transfer at the moment!!</div>";
        
    }elseif($vtransfer_balance < $amount_recharge){
	    
	    echo "<script>alert('Insufficient Fund in your Wallet!!'); </script>";
	    
		echo "<script>window.location='mywallet.php?id=".$_SESSION['tid']."&&mid=NDA0'; </script>";
		
	}elseif($epin != $myvepin || $epin == ""){
	    
	    echo "<script>alert('Invalid Transaction Pin!'); </script>";
	    
		echo "<script>window.location='pay_bills.php?id=".$_SESSION['tid']."&&mid=NDA0'; </script>";
	    
	}elseif($amount_recharge > $vglobal_airtimeLimitPerTrans){

        echo "<div class='alert bg-orange'>Sorry! You cannot transact more than ".$vcurrency.number_format($vglobal_airtimeLimitPerTrans,2,'.',',')." at once</div>";

    }elseif($vmyDailyAirtimeData == $vglobalDailyAirtime_DataLimit || (($amount_recharge + $vmyDailyAirtimeData) > $vglobalDailyAirtime_DataLimit)){

        echo "<div class='alert bg-orange'>Oops! You have reached your daily limit of ".$vcurrency.number_format($vglobalDailyAirtime_DataLimit,2,'.',',')."</div>";

    }elseif($otp_option === "Yes" || $otp_option === "Both"){
	    
	    //OTP Section
    	$otpcode = substr((uniqid(rand(),1)),3,6);
    	$phone = $vo_phone;
    	
    	//Data Parser (array size = 4)
        $mydata = $myref."|".$ph."|".$operator."|".$airtime_product."|".$amount_recharge;
        
        $query = mysqli_query($link, "SELECT * FROM systemset") or die (mysqli_error($link));
        $r = mysqli_fetch_object($query);
        $sysabb = $isenderid;
    								
    	$sms = "$sysabb>>>Dear $iusername! Your One Time Password is $otpcode";
    								
    	//SMS DATA
    	$search_gateway = mysqli_query($link, "SELECT * FROM sms WHERE smsuser = '' AND status = 'Activated'");
        $fetch_gateway = mysqli_fetch_object($search_gateway);
        $gateway_uname = $fetch_gateway->username;
        $gateway_pass = $fetch_gateway->password;
        $gateway_api = $fetch_gateway->api;
                            		
        $sms_rate = $r->fax;
        $imywallet_balance = $vwallet_balance - $sms_rate;
        $sms_refid = "EA-smsCharges-".time();
        
        //OTP ENABLED
        $update = mysqli_query($link,"INSERT INTO otp_confirmation VALUES(null,'$vuid','$otpcode','$mydata','Pending','$currenctdate')")or die(mysqli_error());
		//SEND SMS
        $sendSMS->instGeneralAlert($vozeki_password, $vozeki_url, $sysabb, $vo_phone, $sms, $vcreated_by, $sms_refid, $sms_rate, $vuid, $imywallet_balance, $debitWallet);

        echo "<div align='center'><img src='../image/checkmark.gif'><p style='color: #38A1F3;'>Great! Otp has been sent to your mobile phone to complete this transaction!!</p></div>";
        echo '<meta http-equiv="refresh" content="2;url=pay_bills.php?tid='.$_SESSION['tid'].'&&mid=NDA0&&otp">';
        
	}elseif($walletafrica_airtimestatus == "Active" && $otp_option == "No"){
            
            //Get my wallet balance after debiting
            $senderBalance = $vtransfer_balance - $amount_recharge;
            
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
                
                $insert = mysqli_query($link, "INSERT INTO wallet_history VALUES(null,'$vcreated_by','$myref','$ph','','$amount_recharge','Debit','$vcurrency','Airtime - WEB','Airtime Topup for $operator with refid: $myref via WEB','successful','$currenctdate','$vuid','$senderBalance','')");
        		    
        		//$insert = mysqli_query($link, "INSERT INTO wallet_history VALUES(null,'$acctno','$myref','$ph','$commission','$bbcurrency','Commission - WEB','Airtime Topup for $operator with refid: $myref via WEB','successful',NOW())");
        		    
        		$update_records = mysqli_query($link, "UPDATE user SET transfer_balance = '$senderBalance' WHERE id = '$vuid'");
		            
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
        $jsonData['callback']="https://esusu.app/myVendor/pay_bills.php";
        
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
		            $commission = $newOutput['amount'] * $vairtimeDataCommission;
		            $amount = $newOutput['amount'] - $commission;
                    
                    //Get my wallet balance after debiting
                    $senderBalance2 = $vtransfer_balance - $amount;
                    
	                //$remainBalance = $vwallet_balance - $amount;
        		    $insert = mysqli_query($link, "INSERT INTO wallet_history VALUES(null,'$vcreated_by','$transaction_id','$phone','','$amount','Debit','$vcurrency','Airtime - WEB','Airtime Topup for $operator with refid: $myref via WEB','successful','$currenctdate','$vuid','$senderBalance2','')");
        		    
        		    $insert = mysqli_query($link, "INSERT INTO wallet_history VALUES(null,'$vcreated_by','$transaction_id','$phone','$commission','','Credit','$vcurrency','Commission - WEB','Airtime Topup Commission for $operator with refid: $myref via WEB','successful','$currenctdate','$vuid','$senderBalance2','')");
        		    
        		    mysqli_query($link, "UPDATE user SET transfer_balance = '$senderBalance2' WHERE id = '$vuid'");
		            
		            echo "<script>alert('Airtime Purchased Successfully!'); </script>";
		            echo "<script>window.location='pay_bills.php?id=".$_SESSION['tid']."&&mid=NDA0'; </script>";
		            
		        }elseif($newOutput['response'] == "PENDING"){
		            
		            //pending transaction
		            $transaction_id = $newOutput['transaction_id'];
		            $commission = $newOutput['amount'] * $vairtimeDataCommission;
		            $amount = $newOutput['amount'] - $commission;
                    
	                //$remainBalance = $cwallet_balance - (($ccurrency != "NGN") ? $amount_recharge : $newOutput['amount_charged']);
        		    $insert = mysqli_query($link, "INSERT INTO wallet_history VALUES(null,'$vcreated_by','$transaction_id','$phone','','$amount','Debit','$vcurrency','Airtime - WEB','Airtime Topup for $operator with refid: $myref via WEB','pending','$currenctdate','$vuid','$vtransfer_balance','')");
        		    
        		    $insert = mysqli_query($link, "INSERT INTO wallet_history VALUES(null,'$vcreated_by','$transaction_id','$phone','$commission','','Credit','$vcurrency','Commission - WEB','Airtime Topup Commission for $operator with refid: $myref via WEB','pending','$currenctdate','$vuid','$vtransfer_balance','')");
        		    
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
    
    $search_memset = mysqli_query($link, "SELECT * FROM member_settings WHERE companyid = '$vcreated_by'");
    $fetch_memset = mysqli_fetch_array($search_memset);
				    
	$verify_otp = mysqli_query($link, "SELECT * FROM otp_confirmation WHERE otp_code = '$myotp' AND userid = '$vuid' AND status = 'Pending'");
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
    $currenctdate = date("Y-m-d h:i:s");
						    
	if($otpnum == 0){
						        
	    echo "<div class='alert bg-orange'>Opps!...Invalid Credentials!!</div>";
						        
	}elseif($walletafrica_airtimestatus == "Active"){
            
            //Get my wallet balance after debiting
            $senderBalance = $vtransfer_balance - $amount_recharge;
            
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
                
                $insert = mysqli_query($link, "INSERT INTO wallet_history VALUES(null,'$vuid','$myref','$ph','','$amount_recharge','Debit','$vcurrency','Airtime - WEB','Airtime Topup for $operator with refid: $myref via WEB','successful','$currenctdate','$vuid','$senderBalance','')");
        		    
        		//$insert = mysqli_query($link, "INSERT INTO wallet_history VALUES(null,'$acctno','$myref','$ph','$commission','$bbcurrency','Commission - WEB','Airtime Topup for $operator with refid: $myref via WEB','successful',NOW())");
        		    
        		$update_records = mysqli_query($link, "UPDATE user SET transfer_balance = '$senderBalance' WHERE id = '$vuid'");
        		
        		mysqli_query($link, "UPDATE otp_confirmation SET status = 'Verified' WHERE userid = '$vuid' AND otp_code = '$myotp' AND status = 'Pending'");
		            
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
        $jsonData['callback']="https://esusu.app/myVendor/pay_bills.php";
        
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
		            $commission = $newOutput['amount'] * $vairtimeDataCommission;
		            $amount = $newOutput['amount'] - $commission;
                    
                    //Get my wallet balance after debiting
                    $senderBalance = $vtransfer_balance - $amount;
                    
	                //$remainBalance = $bwallet_balance - $amount;
        		    $insert = mysqli_query($link, "INSERT INTO wallet_history VALUES(null,'$vcreated_by','$transaction_id','$phone','','$amount','Debit','$vcurrency','Airtime - WEB','Airtime Topup for $operator with refid: $myref via WEB','successful','$currenctdate','$vuid','$senderBalance','')");
        		    
        		    $insert = mysqli_query($link, "INSERT INTO wallet_history VALUES(null,'$vcreated_by','$transaction_id','$phone','$commission','','Credit','$vcurrency','Commission - WEB','Airtime Topup for $operator with refid: $myref via WEB','successful','$currenctdate','$vuid','$senderBalance','')");
        		    
        		    mysqli_query($link, "UPDATE user SET transfer_balance = '$senderBalance' WHERE id = '$vuid'");
        		    
        		    mysqli_query($link, "DELETE FROM otp_confirmation WHERE userid = '$vuid' AND otp_code = '$myotp' AND status = 'Pending'");
		            
		            echo "<div class='alert bg-blue'>Airtime Purchased Successfully!</div>";
		            echo '<meta http-equiv="refresh" content="2;url=pay_bills.php?id='.$_SESSION['tid'].'&&mid=NDA0">';
		            
		        }elseif($newOutput['response'] == "PENDING"){
		            
		            //pending transaction
		            $transaction_id = $newOutput['transaction_id'];
		            $commission = $newOutput['amount'] * $vairtimeDataCommission;
		            $amount = $newOutput['amount'] - $commission;
                    
	                //$remainBalance = $cwallet_balance - (($ccurrency != "NGN") ? $amount_recharge : $newOutput['amount_charged']);
        		    $insert = mysqli_query($link, "INSERT INTO wallet_history VALUES(null,'$vcreated_by','$transaction_id','$phone','','$amount','Debit','$vcurrency','Airtime - WEB','Airtime Topup for $operator with refid: $myref via WEB','pending','$currenctdate','$vuid','$vtransfer_balance','')");
        		    
        		    $insert = mysqli_query($link, "INSERT INTO wallet_history VALUES(null,'$vcreated_by','$transaction_id','$phone','$commission','','Credit','$vcurrency','Commission - WEB','Airtime Topup for $operator with refid: $myref via WEB','pending','$currenctdate','$vuid','$vtransfer_balance','')");
        		    
        		    mysqli_query($link, "UPDATE otp_confirmation SET status = 'Verified' WHERE userid = '$vuid' AND otp_code = '$myotp' AND status = 'Pending'");
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
</div>
</section>
</div>