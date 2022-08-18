<div class="row">	
		
	 <section class="content">
		 
            <h3 class="panel-title"> <a href="list_card.php?id=<?php echo $_SESSION['tid']; ?>&&mid=<?php echo base64_encode("550"); ?>"><button type="button" class="btn btn-flat bg-orange"><i class="fa fa-mail-reply-all"></i>&nbsp;Back</button> </a></h3>
        
		   <div class="box box-success">
           <div class="box-body">
             <div class="table-responsive"> 
            <div class="box-body">

		 <div class="col-md-14">
            <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
             <li <?php echo ($_GET['tab'] == 'tab_1') ? "class='active'" : ''; ?>><a href="create_card.php?id=<?php echo $_SESSION['tid']; ?>&&mid=<?php echo base64_encode("550"); ?>&&tab=tab_1">Load Card</a></li>
             <!--<li <?php //echo ($_GET['tab'] == 'tab_2') ? "class='active'" : ''; ?>><a href="#create_card.php?id=<?php //echo $_SESSION['tid']; ?>&&mid=<?php //echo base64_encode("550"); ?>&&tab=tab_2">View Card</a></li>
			 <li <?php //echo ($_GET['tab'] == 'tab_3') ? "class='active'" : ''; ?>><a href="create_card.php?id=<?php //echo $_SESSION['tid']; ?>&&mid=<?php //echo base64_encode("550"); ?>&&tab=tab_3">Withdraw from Mastercard</a></li>
			 <li <?php //echo ($_GET['tab'] == 'tab_4') ? "class='active'" : ''; ?>><a href="create_card.php?id=<?php //echo $_SESSION['tid']; ?>&&mid=<?php //echo base64_encode("550"); ?>&&tab=tab_4">Mastercard Reports</a></li>-->
			 <li <?php echo ($_GET['tab'] == 'tab_5') ? "class='active'" : ''; ?>><a href="create_card.php?id=<?php echo $_SESSION['tid']; ?>&&mid=<?php echo base64_encode("550"); ?>&&tab=tab_5">VerveCard Reports</a></li>
              </ul>
             <div class="tab-content">
<?php
if(isset($_GET['tab']) == true)
{
	$tab = $_GET['tab'];
	if($tab == 'tab_1')
	{
	?>
             <div class="tab-pane <?php echo ($_GET['tab'] == 'tab_1') ? 'active' : ''; ?>" id="tab_1">

<?php

if(isset($_POST['save']))
{
	include("../config/restful_apicalls.php");

	$result = array();
	$customer =  mysqli_real_escape_string($link, $_POST['customer']);
	$billing_addrs = mysqli_real_escape_string($link, $_POST['billing_addrs']);
	$billing_city = mysqli_real_escape_string($link, $_POST['billing_city']);
	$billing_state = mysqli_real_escape_string($link, $_POST['billing_state']);
	$postalcode = mysqli_real_escape_string($link, $_POST['postalcode']);
	$billing_country = mysqli_real_escape_string($link, $_POST['billing_country']);
	$currency_type =  mysqli_real_escape_string($link, $_POST['currency_type']);
	$amount =  mysqli_real_escape_string($link, $_POST['amount']);
	$loadedAmt = $amount * 100;
	$secureoption =  mysqli_real_escape_string($link, $_POST['secure_option']);
	$bank =  mysqli_real_escape_string($link, $_POST['bank']);
	$pin =  ($secureoption == "pin") ? mysqli_real_escape_string($link, $_POST['pin']) : 'null';
	$refid = "EA-preFundCard-".mt_rand(10000,99999);

	$search_user = mysqli_query($link, "SELECT * FROM borrowers WHERE account = '$customer'");
	$get_user = mysqli_fetch_array($search_user);
	$billing_name = $get_user['fname'].' '.$get_user['lname'];
	$phone = $get_user['phone'];
	$mycurrency = $get_user['currency'];
	$mywallet = $get_user['wallet_balance'];
	$mycard_id = $get_user['card_id'];
	$mycard_reg = $get_user['card_reg'];
	$institution_id = $get_user['branchid'];
    $isbranchid = $get_user['sbranchid'];

	$systemset = mysqli_query($link, "SELECT * FROM systemset");
	$row1 = mysqli_fetch_object($systemset);
	$seckey = $row1->secret_key;
	$sysabb = $row1->abb;
	$bancore_merchantID = $row1->bancore_merchant_acctid;
	$bancore_mprivate_key = $row1->bancore_merchant_pkey;
	$passcode = substr($phone, -13).$loadedAmt.$currency_type.$bancore_merchantID.$bancore_mprivate_key;
	$encKey = hash('sha256',$passcode);
	//echo $passcode;
	
	if($bank == "Flutterwave")
	{
		
		$api_name =  "create-virtualcards";
		$search_restapi = mysqli_query($link, "SELECT * FROM atmcard_gateway_apis WHERE api_name = '$api_name' AND issuer_name = '$bank'");
		$fetch_restapi = mysqli_fetch_object($search_restapi);
		$api_url = $fetch_restapi->api_url;
		$issuer_name = $fetch_restapi->issuer_name;
	
		// Pass the parameter here
		$postdata =  array(
			"secret_key"	=>	$seckey,
			"currency"		=>	$currency_type,
			"amount"		=> 	$amount,
			"billing_name"	=>	$billing_name,
			"billing_address"	=>	$billing_addrs,
			"billing_city"	=>	$billing_city,
			"billing_state"	=>	$billing_state,
			"billing_postal_code"	=>	$postalcode,
			"billing_country"	=> $billing_country,
			"callback_url"	=> "https://esusu.app/cron/sub_signal.php?cu".$customer
		);
	
		$make_call = callAPI('POST', $api_url, json_encode($postdata));
		$result = json_decode($make_call, true);
		
		//var_dump($result);
	
		if($result['status'] == "success")
		{
			$id = $result['data']['id'];
			$AccountId = $result['data']['AccountId'];
			$card_hash = $result['data']['card_hash'];
			$cardpan = $result['data']['cardpan'];
			$maskedpan = $result['data']['maskedpan'];
			$cvv = $result['data']['cvv'];
			$expiration = $result['data']['expiration'];
			$card_type = $result['data']['card_type'];
			$name_on_card = $result['data']['name_on_card'];
			$is_active = $result['data']['is_active'];
			$total_walletbal = $mywallet + $amount;
	
			$accno = ccMasking($customer);
	
			$date_time = date("Y-m-d H:i:s");
			$utc_date = DateTime::createFromFormat('Y-m-d G:i:s',$date_time,new DateTimeZone('America/New_York'));
	        $acst_date = clone $utc_date; // we don't want PHP's default pass object by reference here
	        $acst_date->setTimeZone(new DateTimeZone('GMT+1'));
	        $correctdate = $acst_date->format('Y-m-d g:i A');
	        $postedby = "<br><b>Posted by:<br>".$name."</b>";
	
			$insert = mysqli_query($link, "INSERT INTO card_enrollment VALUES(null,'$branchid','$csbranchid','$customer','$currency_type','$amount','$phone','$billing_addrs','$billing_country','$id','$AccountId','$card_hash','$cardpan','$maskedpan','$cvv','$expiration','$card_type','$name_on_card','$issuer_name','$secureoption','$pin','$is_active',NOW())");
			$insert = mysqli_query($link, "INSERT INTO wallet_history VALUES(null,'$customer','$refid','$amount','$currency_type','preFundCard','$postedby','successful',NOW(),'$uid','')");
			$update = mysqli_query($link, "UPDATE borrowers SET card_id = '$id' WHERE account = '$customer'");
	
			$sms = "$sysabb>>>CR";
			$sms .= " Amt: ".$currency_type.number_format($amount,2,'.',',')."";
			$sms .= " Acc: ".$accno."";
			$sms .= " Desc: Prefund Card ";
			$sms .= " Time: ".$correctdate."";
			$sms .= " Wallet Bal: ".$mycurrency.number_format($total_walletbal,2,'.',',')."";
	
			include("../cron/send_general_sms.php");
			echo'<span class="itext" style="color: blue;">Card Created Successfully!!</span>';
	
		}
		else{
			echo '<meta http-equiv="refresh" content="5;url=create_card?id='.$_SESSION['tid'].'&&mid='.base64_encode("550").'&&tab=tab_1">';
			echo '<br>';
			echo'<span class="itext" style="color: orange;">'.$result['Message'].'</span>';
		}
		
	}
	if($bank == "Mastercard")
	{
		$txid = "EA-cOrder-".mt_rand(1000000,9999999);
		$api_name =  "card_load";
		$search_restapi = mysqli_query($link, "SELECT * FROM atmcard_gateway_apis WHERE api_name = '$api_name' AND issuer_name = '$bank'");
		$fetch_restapi = mysqli_fetch_object($search_restapi);
		$api_url = $fetch_restapi->api_url;
    
		if($tpin != $control_pin){
		
		echo "<script>alert('Oops! Invalid Transaction Pin!'); </script>";
			echo "<script>window.location='create_card?id=".$_SESSION['tid']."&&mid=".base64_encode('550')."&&tab=tab_1'; </script>";
		
		}else{
			
			function cardLoader($ph, $cardcurrency, $amt, $orderID, $debug=false){
				global $bancore_merchantID,$encKey,$customer,$link,$api_url;
			
				$url = '?accountID='.substr($ph, -13);
				$url.= '&merchantID='.$bancore_merchantID;
				$url.= '&currency='.urlencode($cardcurrency);
				$url.= '&amount='.urlencode($amt);
				$url.= '&orderID='.urlencode($orderID);
				$url.= '&encKey='.$encKey;
							
				$urltouse =  $api_url.$url;
				
				//if ($debug) { echo "Request: <br>$urltouse<br><br>"; }
			
				//Open the URL to send the message
				$response = file_get_contents($urltouse);
					
				if ($debug) {
					//echo "Response: <br><pre>".
					//str_replace(array("<",">"),array("&lt;","&gt;"),$response).
					//"</pre><br>"; 
						//echo substr($response, 112);
					/**
						 $card_id = substr($response,108,-24);
					
					//echo $card_id;
					
					if(is_numeric($card_id)){
						mysqli_query($link, "UPDATE borrowers SET card_id = '$card_id' WHERE account = '$customer'");
					}
					else{
						//empty
					}
					**/
				}
				return($response);
			}
			
			$debug = true;
			$cardChecker = cardLoader($phone,$currency_type,$loadedAmt,$txid,$debug);
			$iparr = split ("\&", $cardChecker);
			$regStatus = substr("$iparr[0]",7);
			$mycardID = substr("$iparr[3]",7);
			if($mycard_id == "NULL" && $mycard_reg == "No"){
				
				echo "<script>alert('Oops! No enrollment was done yet!!');</script>";
				echo "<script>window.location='create_card?id=".$_SESSION['tid']."&&mid=".base64_encode('550')."&&tab=tab_1'; </script>";
			
			}
			elseif($regStatus == 100 || $regStatus != 30){
				
				echo "<script>alert('General failure. Please try again');</script>";
					echo "<script>window.location='create_card?id=".$_SESSION['tid']."&&mid=".base64_encode('550')."&&tab=tab_1'; </script>";
				
			}
			elseif($regStatus == 30 && $mycard_id == "NULL" && $mycard_reg == "Yes"){
				
				mysqli_query($link, "UPDATE borrowers SET card_id = '$mycardID' WHERE account = '$customer'");
				mysqli_query($link, "INSERT INTO wallet_history VALUE(null,'','$txid','$customer','$amount','$currency_type','Topup-Prepaid_Card','Response: Prepaid Card was Topup with $currency_type.$amount','successful',NOW(),'$uid','')");
	
				echo "<script>alert('Prepaid Card Topup Successfully $cardChecker');</script>";
				echo "<script>window.location='create_card?id=".$_SESSION['tid']."&&mid=".base64_encode('550')."&&tab=tab_1'; </script>";
				
			}
			elseif($regStatus == 30 && $mycard_id != "NULL" && $mycard_reg == "Yes"){
				
				mysqli_query($link, "INSERT INTO wallet_history VALUE(null,'','$txid','$customer','$amount','$currency_type','Topup-Prepaid_Card','Response: Prepaid Card was Topup with $currency_type.$amount','successful',NOW(),'$uid','')");
					
				echo "<script>alert('Prepaid Card Topup Successfully');</script>";
					echo "<script>window.location='create_card?id=".$_SESSION['tid']."&&mid=".base64_encode('550')."&&tab=tab_1'; </script>";
			}
		
		}
			
	}
	if($bank == "VerveCard"){

		$verveAppId = $row1->verveAppId;
    	$verveAppKey = $row1->verveAppKey;
    	$isenderid = $fetchsys_config['abb'];
    	$amt_tofund = preg_replace("/[^0-9]/", "", $amount);
    	$amountWithCharges = $amt_tofund + $transferToCardCharges;
    	$calcCharges = $amountWithCharges - $amt_tofund;
    	$pref = date("yd").time();
    	$cust_fname = $get_user['fname'];
    	$cust_lname = $get_user['lname'];
    	$cust_email = $get_user['email'];
    	$pan = $get_user['card_id'];
    	$tpin = mysqli_real_escape_string($link, $_POST['tpin']);
    	$bvirtual_phone_no = $get_user['virtual_number'];
    	    
    	$search_gateway = mysqli_query($link, "SELECT * FROM sms WHERE smsuser = '' AND status = 'Activated'");
        $fetch_gateway = mysqli_fetch_object($search_gateway);
        $gateway_uname = $fetch_gateway->username;
        $gateway_pass = $fetch_gateway->password;
        $gateway_api = $fetch_gateway->api;
    	    
    	$data = $pref."|".$amt_tofund."|".$amountWithCharges;
    	    
    	$api_name =  "card_load";
    	$search_restapi = mysqli_query($link, "SELECT * FROM atmcard_gateway_apis WHERE api_name = '$api_name' AND issuer_name = '$bank'");
    	$fetch_restapi = mysqli_fetch_object($search_restapi);
    	$api_url = $fetch_restapi->api_url;
    		
    	if($tpin != $control_pin){
	    
    	    echo "<script>alert('Oops! Invalid Transaction Pin!'); </script>";
    	  	echo "<script>window.location='create_card?id=".$_SESSION['tid']."&&mid=".base64_encode('550')."&&tab=tab_1'; </script>";
    	      
    	}elseif($mywallet < $amountWithCharges){
    			
    		echo "<script>alert('Oops! Customer do not have sufficient fund in his wallet to complete this transaction!!'); </script>";
    	  	echo "<script>window.location='create_card?id=".$_SESSION['tid']."&&mid=".base64_encode('550')."&&tab=tab_1'; </script>";
    			
    	}else{

			//Sender Remaining Balance After Transfer
            $senderBalance = $mywallet - $amountWithCharges;
                        
            //$gatewayResponse = $result1['Message'];
            $transactionDateTime = date("Y-m-d H:i:s");
                        
            $client = new SoapClient($api_url);

            $param = array(
                'appId'=>$verveAppId,
                'appKey'=>$verveAppKey,
                'currencyCode'=>"566",
                'emailAddress'=>$cust_email,
                'firstName'=>$cust_fname,
                'lastName'=>$cust_lname,
                'mobileNr'=>$phone,
                'amount'=>$amt_tofund,
                'pan'=>$pan,
                'PaymentRef'=>$pref
            );
                
            $response = $client->PostIswCardFund($param);
                        
            $process = json_decode(json_encode($response), true);
                        
            //print_r($param);
                        
            //print_r($process);
                        
            $responseCode = $process['PostIswCardFundResult']['responseCode']; //90000 OR 99
			$responseCodeGrouping = $process['PostIswCardFundResult']['responseCodeGrouping'];
			
			$api_name5 = "display_card_bal";
			$issurer5 = "VerveCard";
			$search_restapi5 = mysqli_query($link, "SELECT * FROM atmcard_gateway_apis WHERE api_name = '$api_name5' AND issuer_name = '$issurer5'");
			$fetch_restapi5 = mysqli_fetch_object($search_restapi5);
			$api_url5 = $fetch_restapi5->api_url;

			$client5 = new SoapClient($api_url5);

			$param5 = array(
				'AccountNo'=>$pan,
				'appId'=>$verveAppId,
				'appKey'=>$verveAppKey
			);
		  
			$response5 = $client5->GetIswPrepaidCardAccountBalance($param5);
				  
			$process5 = json_decode(json_encode($response5), true);
				  
			$statusCode5 = $process5['GetIswPrepaidCardAccountBalanceResult']['StatusCode'];
				  
			$availableBalance5 = $process5['GetIswPrepaidCardAccountBalanceResult']['JsonData'];
				  
			$decodeProcess5 = json_decode($availableBalance5, true);
			  
			$availbal = $decodeProcess5['availableBalance'] / 100;

			$tType = "Credit";
			$PAN_Masked = panNumberMasking($pan);
			$STAN = rand(000000,999999);
			$RRN = date("y").time();
			$TrxnAmount = $amt_tofund;
			$CurrCode = "NGN";
			$DateTime = date('m/d/Y').' '.(date(h) + 1).':'.date('i A');
			$email = $cust_email;
            $fname = $cust_fname;
			$lname = $cust_lname;
			$account = $customer;
                        
            $sms = "$isenderid>>>Cr Notification. Your Verve card with Pan Number: $PAN_Masked has been credited with NGN".number_format($amt_tofund,2,'.',',')." ";
            $sms .= "Date ".$DateTime."";
                        
            if($responseCode == "90000"){
                            
                $transferCode = $process['PostIswCardFundResult']['transferCode'];
                $pin = $process['PostIswCardFundResult']['pin'];
                $cardPan = $process['PostIswCardFundResult']['cardPan'];
                $cvv = $process['PostIswCardFundResult']['cvv'];
                $expiryDate = $process['PostIswCardFundResult']['expiryDate'];
                $currenctdate = date("Y-m-d H:i:s");
                            
                //$message = ($mycard_id == "NULL") ? "Card Pin is: ".$pin : "";
                            
                mysqli_query($link, "UPDATE borrowers SET wallet_balance = '$senderBalance' WHERE account = '$customer'");
                            
                mysqli_query($link, "INSERT INTO wallet_history VALUE(null,'','$pref','$customer','','$amt_tofund','Debit','NGN','Topup-Prepaid_Card','Response: Prepaid Card was Topup with NGN$amt_tofund','successful','$currenctdate','$uid','$senderBalance','')");
                            
                mysqli_query($link, "INSERT INTO wallet_history VALUE(null,'','$pref','$customer','','$calcCharges','Debit','NGN','Stamp Duty','Response: Prepaid Card was Topup with NGN$amt_tofund','successful','$currenctdate','$uid','$senderBalance','')");
				
				mysqli_query($link, "INSERT INTO cardTransaction VALUES(null,'$customer','$cust_fname','$cust_lname','$cust_email','$phone','$pan','$PAN_Masked','ESUSU AFRICA LTD, LA LANG','$DateTime','$STAN','$RRN','$TrxnAmount','$availbal','$CurrCode','$tType','$institution_id','$isbranchid','$uid')");

                include("../cron/mygeneral_sms.php");
                            
                $debug = true;
				sendSms($isenderid,$phone,$sms,$debug);
				include("../config/cardEmailNotifier.php");
                            
                echo "<script>alert('Prepaid Card Topup Successfully.');</script>";
                echo "<script>window.location='create_card?id=".$_SESSION['tid']."&&mid=".base64_encode('550')."&&tab=tab_1'; </script>";
                            
			}
			elseif($responseCode == "99"){
                
				echo "<div class='alert bg-orange'>Opps!..Access denied, please try again later!!</div>";
						
			}
			else{
				
				echo "<div class='alert bg-orange'>Opps!..Network Error, please try again later!!</div>";
				
			}
				
		}
    	    
    }

}
?>			 
            
			 <form class="form-horizontal" method="post" enctype="multipart/form-data">
             <div class="box-body">

			<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Card Issuance</label>
                  <div class="col-sm-10">
				<select name="bank"  class="form-control select2" id="card_issuer" required>
						<option value='' selected='selected'>Select Card Issuance &hellip;</option>
										<?php
$search = mysqli_query($link, "SELECT DISTINCT(issuer_name) FROM atmcard_gateway_apis ORDER BY id DESC");
while($get_search = mysqli_fetch_array($search))
{
?>
					<option value="<?php echo $get_search['issuer_name']; ?>"><?php echo $get_search['issuer_name']; ?></option>
<?php } ?>
				</select>
			</div>
			</div>
      
    
      <span id='ShowValueFrank'></span>
      <span id='ShowValueFrank'></span>
      <span id='ShowValueFrank'></span>  
	  
	  
	  <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Control Pin</label>
                  <div class="col-sm-10">
                  <input name="tpin" type="password" class="form-control" autocomplete="off" placeholder="Your Control pin" /required>
                  </div>
                  </div>    

			 </div>
			 
			  <div align="right">
              <div class="box-footer">
                <button name="save" type="submit" class="btn bg-blue"><i class="fa fa-spinner">&nbsp;Submit</i></button>

              </div>
			  </div>
			  
			 </form> 
			 
              </div>

    <?php
	}
	elseif($tab == 'tab_2')
	{
	?>

		<div class="tab-pane <?php echo ($_GET['tab'] == 'tab_2') ? 'active' : ''; ?>" id="tab_2">
					   
		<div class="box-body">

<?php
if(isset($_GET['aId']) == true){
	$aId = $_GET['aId'];
	$search_card = mysqli_query($link, "SELECT * FROM card_enrollment WHERE account_id = '$aId'");
	$fetch_card = mysqli_fetch_object($search_card);
?>
<div class="demo-container" align="left">
<div class='card-wrapper'></div>
<!-- CSS is included via this JavaScript file -->
<script src="../dist/card.js"></script>
<div class="form-container active">
<form name="cardw">
    <input type="text" class="myinput" value="<?php echo chunk_split($fetch_card->cardpan, 4, ' '); ?>" name="number"/readonly>
    <input type="text" class="myinput" value="<?php echo $fetch_card->name_on_card; ?>" name="name"/readonly>
    <input type="text" class="myinput" value="<?php echo date('m/Y', strtotime($fetch_card->expiration)); ?>" name="expiry"/readonly>
    <input type="text" class="myinput" value="<?php echo $fetch_card->cvv; ?>" name="cvc"/readonly>
</form>
</div>
</div>
<script>

var card = new Card({
    form: 'form',
    container: '.card-wrapper',

    placeholders: {
        number: '<?php echo chunk_split($fetch_card->cardpan, 4, ' '); ?>',
        name: '<?php echo $fetch_card->name_on_card; ?>',
        expiry: '<?php echo date('m/Y', strtotime($fetch_card->expiration)); ?>',
        cvc: '<?php echo $fetch_card->cvv; ?>'
    }
});
</script>
<?php
}
else{
	echo "<div class='alert bg-orange'>Sorry!...No Card to View!!</div>";
}
?>
		</div>
		</div>
              <!-- /.tab-pane -->
	<?php
	}
	elseif($tab == 'tab_3')
	{
	?>

		<div class="tab-pane <?php echo ($_GET['tab'] == 'tab_3') ? 'active' : ''; ?>" id="tab_3">
					   
		<div class="box-body">

			<form class="form-horizontal" method="post" enctype="multipart/form-data">
       
             <div class="box-body">
<?php
   function cardLoader2($card_id, $debug=false){
      global $bancore_merchantID,$encKey,$link,$api_url;
        		
      $url = '?cardID='.$card_id;
      $url.= '&merchantID='.$bancore_merchantID;
      $url.= '&encKey='.$encKey;
                      		  
      $urltouse =  $api_url.$url;
        			  
      //if ($debug) { echo "Request: <br>$urltouse<br><br>"; }
        		
      //Open the URL to send the message
      $response = file_get_contents($urltouse);
                      
      if ($debug) {
           //echo "Response: <br><pre>".
          //str_replace(array("<",">"),array("&lt;","&gt;"),$response).
          //"</pre><br>"; 
      	//echo substr($response, 112);        				   
      }
      return($response);
	}
?>			
			
			<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Card Issuance</label>
                  <div class="col-sm-10">
				<select name="bank"  class="form-control select2" required>
						<option value='' selected='selected'>Select Card Issuance &hellip;</option>
										<?php
$search = mysqli_query($link, "SELECT DISTINCT(issuer_name) FROM atmcard_gateway_apis ORDER BY id DESC");
while($get_search = mysqli_fetch_array($search))
{
?>
					<option value="<?php echo $get_search['issuer_name']; ?>"><?php echo $get_search['issuer_name']; ?></option>
<?php } ?>
				</select>
			</div>
			</div>
			
			<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">User</label>
                  <div class="col-sm-10">
				<select name="customer"  class="form-control select2" required>
					<option value='' selected='selected'>Select User</option>
          <option disabled>CUSTOMER LIST</option>
					<?php
            $search = mysqli_query($link, "SELECT * FROM borrowers WHERE card_id != 'NULL' ORDER BY id DESC");
            while($get_search = mysqli_fetch_array($search))
            {
              $api_name =  "display_card_bal";
              $issurer = $get_search['card_issurer'];
              $card_id = $get_search['card_id'];
          	  $search_restapi = mysqli_query($link, "SELECT * FROM atmcard_gateway_apis WHERE api_name = '$api_name' AND issuer_name = '$issurer'");
          	  $fetch_restapi = mysqli_fetch_object($search_restapi);
          	  $api_url = $fetch_restapi->api_url;
              
              $systemset = mysqli_query($link, "SELECT * FROM systemset");
              $row1 = mysqli_fetch_object($systemset);
              $bancore_merchantID = $row1->bancore_merchant_acctid;
              $bancore_mprivate_key = $row1->bancore_merchant_pkey;
              
              $passcode = $card_id.$bancore_merchantID.$bancore_mprivate_key;
              $encKey = hash('sha256',$passcode);

          	  $debug = true;
          	  $processBal2 = cardLoader2($card_id,$debug);  
              $iparr = split ("\&", $processBal2);
              $cardBal2 = substr("$iparr[1]",7);
              $cardCur2 = substr("$iparr[2]",9);
            ?>
            
            <option value="<?php echo $get_search['card_id']; ?>"><?php echo $get_search['fname'].' '.$get_search['lname'].' ('.$get_search['phone'].') - Customer CardID: '.$get_search['card_id']; ?> <?php echo ($cardBal2 == "") ? "" : "- Card Balance: ".$cardCur2.$cardBal2; ?></option>
                              					
           <?php } ?>
           
				</select>
        <span style="color:orange;"><b>NOTE:</b> User Phone number must be in international format. If not, kindly update the user information first before you proceed with the card issuing / topup.</span>
			</div>
			</div>
			
		    <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Amount</label>
                  <div class="col-sm-10">
					  <input name="amount" type="text" class="form-control" placeholder="Enter the Amount" /required>
                  </div>
                  </div>

            <div class="form-group">
                      <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Currency</label>
                      <div class="col-sm-10">
            <select name="currency"  class="form-control select2" required>
              <option value="" selected>Select Currency</option>
              <option value="NGN">NGN</option>
              <option value="USD">USD</option>
              <option value="GBP">GBP</option>
              <option value="XOF">XOF</option>
            </select>                 
            </div>
    		</div>
			
			<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Transaction Pin</label>
                  <div class="col-sm-10">
                  <input name="tpin" type="password" class="form-control" autocomplete="off" placeholder="Your transaction pin" /required>
                  </div>
                  </div>
			
			 </div>
			 
			  <div align="right">
              <div class="box-footer">
                	<button name="save" type="submit" class="btn bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>"><i class="fa fa-forward">&nbsp;Confirm</i></button>
              </div>
			  </div>
        
<?php
if(isset($_POST['save']))
{
	$bank = mysqli_real_escape_string($link, $_POST['bank']);
	$card_id = mysqli_real_escape_string($link, $_POST['customer']);
	$amount =  mysqli_real_escape_string($link, $_POST['amount']);
	$currency = mysqli_real_escape_string($link, $_POST['currency']);
	$tpin =  mysqli_real_escape_string($link, $_POST['tpin']);	
	$finalAmt = $amount * 100;
  	$OrderID = "EA-cardW-".mt_rand(5000000,9999999);

  	$api_name =  "withdraw_fund";
  	$search_restapi = mysqli_query($link, "SELECT * FROM atmcard_gateway_apis WHERE api_name = '$api_name' AND issuer_name = '$bank'");
  	$fetch_restapi = mysqli_fetch_object($search_restapi);
  	$api_url = $fetch_restapi->api_url;
	
  	$systemset = mysqli_query($link, "SELECT * FROM systemset");
  	$row1 = mysqli_fetch_object($systemset);
  	$bancore_merchantID = $row1->bancore_merchant_acctid;
  	$bancore_mprivate_key = $row1->bancore_merchant_pkey;
              
  	$passcode = $card_id.$finalAmt.$currency.$bancore_merchantID.$bancore_mprivate_key;
  	$encKey = hash('sha256',$passcode);
	  
	$search_cust = mysqli_query($link, "SELECT * FROM borrowers WHERE card_id = '$card_id'");
	$fetch_cust = mysqli_fetch_array($search_cust);
	$customer = $fetch_cust['account'];
	
  	if($tpin != $control_pin){
		  
		  echo "<script>alert('Oops! Invalid Transaction Pin!'); </script>";
		  echo "<script>window.location='create_card?id=".$_SESSION['tid']."&&mid=".base64_encode('550')."&&tab=tab_3'; </script>";
    
  	}elseif($bank == "Bancore"){
		  
	  	function cardWithdrawal($cardID, $cardcurrency, $amt, $orderID, $debug=false){
      		global $bancore_merchantID,$encKey,$api_url;
		
			$url = '?cardID='.$cardID;
			$url.= '&merchantID='.$bancore_merchantID;
			$url.= '&currency='.urlencode($cardcurrency);
			$url.= '&amount='.urlencode($amt);
			$url.= '&orderID='.urlencode($orderID);
			$url.= '&encKey='.$encKey;
              		  
			$urltouse =  $api_url.$url;
			  
			//if ($debug) { echo "Request: <br>$urltouse<br><br>"; }
			//Open the URL to send the message
			$response = file_get_contents($urltouse);
              
			if ($debug) {
		   		//echo "Response: <br><pre>".
		   		//str_replace(array("<",">"),array("&lt;","&gt;"),$response).
		   		//"</pre><br>"; 
			}
			return($response);
		}
    
    	$debug = true;
		$cardW = cardWithdrawal($card_id,$currency,$finalAmt,$OrderID,$debug);
		$iparr = split ("\&", $cardW);
		$requestStatus = substr("$iparr[0]",7);
    
    	if($requestStatus == 30){
		
			mysqli_query($link, "INSERT INTO wallet_history VALUE(null,'','$OrderID','$customer','$amount','$currency','Card_Withdrawal','Response: Withdrawal of $currency.$amount was made from Customer Card with Account ID: $customer','successful',NOW())");
			echo "<script>alert('Withdrawal made successfully.');</script>";
      		echo "<script>window.location='create_card?id=".$_SESSION['tid']."&&mid=".base64_encode('550')."&&tab=tab_3'; </script>";
			
		}
    	else{
      
      		echo "<script>alert('Oops!.Network Error, please try again later $cardW');</script>";
      		echo "<script>window.location='create_card?id=".$_SESSION['tid']."&&mid=".base64_encode('550')."&&tab=tab_3'; </script>";
      
    	}
    
  	}elseif($bank == "Flutterwave"){
		
		echo "<script>alert('Oops!...Function Still Under Implementation');</script>";
      	echo "<script>window.location='create_card?id=".$_SESSION['tid']."&&mid=".base64_encode('550')."&&tab=tab_3'; </script>";
		  
	}
}
?>
			  
			 </form> 
			
		</div>
		</div>
              <!-- /.tab-pane -->
	<?php
	}
	elseif($tab == 'tab_4')
	{
	?>

		<div class="tab-pane <?php echo ($_GET['tab'] == 'tab_4') ? 'active' : ''; ?>" id="tab_4">
					   
		<div class="box-body">

			<form class="form-horizontal" method="post" enctype="multipart/form-data">
       
             <div class="box-body">			
			
				<div class="form-group">
                  <label for="" class="col-sm-1 control-label" style="color:blue;">From</label>
                  <div class="col-sm-3">
                  <input name="dfrom" type="date" class="form-control" placeholder="To Date: 2018-05-01" required>
                  </div>
				  
				  <label for="" class="col-sm-1 control-label" style="color:blue;">End Date</label>
                  <div class="col-sm-3">
                  <input name="dto" type="date" class="form-control" placeholder="To Date: 2018-05-24" required>
                  </div>
				  
				  <label for="" class="col-sm-1 control-label" style="color:blue;">Customer:</label>
                <div class="col-sm-3">
                <select name="customer"  class="form-control select2" style="width:100%" required>
                    <option value="" selected="selected">Select Customer</option>
    				<?php
    				$get = mysqli_query($link, "SELECT * FROM borrowers WHERE card_reg = 'Yes' AND card_issurer = 'Mastercard' ORDER BY id") or die (mysqli_error($link));
    				while($rows = mysqli_fetch_array($get))
    				{
    				?>
    				<option value="<?php echo $rows['card_id']; ?>"><?php echo $rows['lname'].' '.$rows['fname']; ?></option>
    				<?php } ?>
    				
				</select>
                  </div>
             </div>

			 </div>
			 
			<div align="right">
              <div class="box-footer">
                				<button name="search" type="submit" class="btn bg-blue btn-flat"><i class="fa fa-filter">&nbsp;Search</i></button>

              </div>
			</div>

<?php
if(isset($_POST['search']))
{	
	$dfrom = mysqli_real_escape_string($link, $_POST['dfrom']);
	$startDate = date("d/m/Y", strtotime($dfrom));
	
	$dto = mysqli_real_escape_string($link, $_POST['dto']);
	$endDate = date("d/m/Y", strtotime($dto));
	
	$customer =  mysqli_real_escape_string($link, $_POST['customer']); //CARD ID
	
	$api_name =  "transaction_history";
  	$search_restapi = mysqli_query($link, "SELECT * FROM atmcard_gateway_apis WHERE api_name = '$api_name' AND issuer_name = 'Mastercard'");
  	$fetch_restapi = mysqli_fetch_object($search_restapi);
  	$api_url = $fetch_restapi->api_url;
	
  	$systemset = mysqli_query($link, "SELECT * FROM systemset");
  	$row1 = mysqli_fetch_object($systemset);
  	$bancore_merchantID = $row1->bancore_merchant_acctid;
  	$bancore_mprivate_key = $row1->bancore_merchant_pkey;
              
  	$passcode = $bancore_merchantID.$customer.$startDate.$endDate.$bancore_mprivate_key;
  	$encKey = hash('sha256',$passcode);
	
	function cardReports($cardID, $startdate, $enddate, $debug=false){
      	global $bancore_merchantID,$encKey,$api_url;
		
		$url = '?merchantID='.$bancore_merchantID;
		$url.= '&cardID='.$cardID;
		$url.= '&startDate='.$startdate;
		$url.= '&endDate='.$enddate;
		$url.= '&encKey='.$encKey;
              		  
		$urltouse =  $api_url.$url;
			  
		//if ($debug) { echo "Request: <br>$urltouse<br><br>"; }
		//Open the URL to send the message
		$response = file_get_contents($urltouse);
              
		if ($debug) {
			//echo "Response: <br><pre>".
			//str_replace(array("<",">"),array("&lt;","&gt;"),$response).
			//"</pre><br>"; 
		}
		return($response);
	}
    
    $debug = true;
	$cardRpts = cardReports($customer,$startDate,$endDate,$debug);	
	$xml = simplexml_load_string($cardRpts);
	//convert into json
	$json  = json_encode($xml);
	//convert into associative array
	$xmlArr = json_decode($json, true);

	echo '<h2>Card Reports for Card ID: '.$xmlArr['cardID'].' from '.$xmlArr['startDate'].' - '.$xmlArr['endDate'].'</h2>';
	
	echo '<table id="example2" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th><div align="center">Date</div></th>
				  <th><div align="center">Reference</div></th>
                  <th><div align="center">Funds Credited</div></th>
                  <th><div align="center">Funds Debited</div></th>
                  <th><div align="center">Card Balance</div></th>
				  <th><div>Description</div></th>
                 </tr>
                </thead>
                <tbody>';				
	
	foreach($xmlArr['transactions'] as $key) {
		
		echo '<tr>';
		echo '<td align="center"><b>'.date("d/m/Y m:s a", strtotime($key['date'])).'</b></td>';
		echo '<td align="center"><b>'.$key['transactionReference'].'</b></td>';
		echo '<td align="center"><b>'.$key['fundsCredited'].'</b></td>';
		echo '<td align="center"><b>'.$key['fundsDebited'].'</b></td>';
		echo '<td align="center"><b>'.$key['cardBalance'].'</b></td>';
		echo '<td><b>'.$key['description'].'</b></td>';
		echo '</tr>';
	
	}
	
	echo '</tbody>
          </table>';
	
	echo '<hr>';
	echo '<a href="../pdf/view/pdf_cardreports.php?dfrom='.$startDate.'&&dto='.$endDate.'&&cardID='.$customer.'" target="_blank"><button type="button" class="btn bg-blue"><i class="fa fa-print"></i> Print Reports</button></a>';
}
?>			  
			 </form> 
			
		</div>
		</div>
              <!-- /.tab-pane -->
	<?php
	}
	elseif($tab == 'tab_5')
	{
	?>

		<div class="tab-pane <?php echo ($_GET['tab'] == 'tab_5') ? 'active' : ''; ?>" id="tab_5">
					   
		<div class="box-body">

			<form class="form-horizontal" method="post" enctype="multipart/form-data">
       
             <div class="box-body">			
			
				<div class="form-group">
                  <label for="" class="col-sm-1 control-label" style="color:blue;">From</label>
                  <div class="col-sm-3">
                  <input name="dfrom" type="date" class="form-control" placeholder="To Date: 2018-05-01" required>
                  </div>
				  
				  <label for="" class="col-sm-1 control-label" style="color:blue;">End Date</label>
                  <div class="col-sm-3">
                  <input name="dto" type="date" class="form-control" placeholder="To Date: 2018-05-24" required>
                  </div>
				  
				  <label for="" class="col-sm-1 control-label" style="color:blue;">Customer:</label>
                <div class="col-sm-3">
                <select name="customer"  class="form-control select2" style="width:100%" required>
                    <option value="" selected="selected">Select Customer</option>
    				<?php
    				$get = mysqli_query($link, "SELECT * FROM borrowers WHERE card_reg = 'Yes' AND card_issurer = 'VerveCard' AND card_id != 'NULL' ORDER BY id") or die (mysqli_error($link));
    				while($rows = mysqli_fetch_array($get))
    				{
    				?>
    				<option value="<?php echo $rows['card_id']; ?>"><?php echo $rows['lname'].' '.$rows['fname']; ?></option>
    				<?php } ?>
    				
				</select>
                  </div>
             </div>

			 </div>
			 
			<div align="right">
              <div class="box-footer">
                				<button name="search" type="submit" class="btn bg-blue btn-flat"><i class="fa fa-filter">&nbsp;Search</i></button>

              </div>
			</div>

<?php
if(isset($_POST['search']))
{	
    try
      {
          $dfrom = mysqli_real_escape_string($link, $_POST['dfrom']);
    	  $StartDate = date("Y-m-d", strtotime($dfrom));
    	
    	  $dto = mysqli_real_escape_string($link, $_POST['dto']);
    	  $EndDate = date("Y-m-d", strtotime($dto));
    	
    	  $PAN =  mysqli_real_escape_string($link, $_POST['customer']); //CARD ID
    	
    	  $api_name =  "card_load";
      	  $search_restapi = mysqli_query($link, "SELECT * FROM atmcard_gateway_apis WHERE api_name = '$api_name' AND issuer_name = 'VerveCard'");
      	  
      	  $fetch_restapi = mysqli_fetch_object($search_restapi);
      	  $api_url = $fetch_restapi->api_url;
    	
      	  $systemset = mysqli_query($link, "SELECT * FROM systemset");
      	  $row1 = mysqli_fetch_object($systemset);
      	  $verveAppId = $row1->verveAppId;
          $verveAppKey = $row1->verveAppKey;
        
          $client = new SoapClient($api_url);
    
          $param = array(
              'PAN'=>$PAN,
              'StartDate'=>$StartDate,
              'EndDate'=>$EndDate,
              'appId'=>$verveAppId,
              'appKey'=>$verveAppKey
          );
    
          $response = $client->GetIswPrepaidCardStatement($param);
        
          $process = json_decode(json_encode($response), true);
            
          print_r($param);
          print_r($process);
          $StatusCode = $process['responseCode']; //00 OR 400

          if($StatusCode === "00"){
              
              echo '<h2>Card Reports for Card ID: '.ccMasking($PAN).' from '.$StartDate.' - '.$EndDate.'</h2>';
              
              echo '<table id="example2" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th><div align="center">tranType</div></th>
    			  <th><div align="center">Local Date/Time</div></th>
                  <th><div align="center">Posted Date/Time</div></th>
                  <th><div align="center">Amount</div></th>
                  <th><div align="center">cardAcceptor Name / Location</div></th>
                 </tr>
                </thead>
                <tbody>';				
	
            	foreach($process['statementRecords'] as $key) {
            		
            		echo '<tr>';
                	echo '<td align="center"><b>'.$key['tranType'].'</b></td>';
                	echo '<td align="center"><b>'.date("d/m/Y G:i A", strtotime($key['tranLocalDatetime'])).'</b></td>';
                	echo '<td align="center"><b>'.date("d/m/Y G:i A", strtotime($key['tranPostedDatetime'])).'</b></td>';
                	echo '<td align="center"><b>'.$key['tranAmount'].'</b></td>';
                	echo '<td align="center"><b>'.$key['cardAcceptorNameLocation'].'</b></td>';
                	echo '</tr>';
            	
            	}
            	
            	echo '</tbody>
                      </table>';
            	
            	echo '<hr>';
            	echo '<a href="../pdf/view/pdf_cardreports1.php?dfrom='.$StartDate.'&&dto='.$EndDate.'&&cardID='.$PAN.'" target="_blank"><button type="button" class="btn bg-blue"><i class="fa fa-print"></i> Print Reports</button></a>';
              
          }
          else{
              
              echo $JsonData;
              
          }
          
      }
      catch(Exception $e)
      {
        // You should not be here anymore
        echo $e->getMessage();
      }
    
}
?>			  
			 </form> 
			
		</div>
		</div>
              <!-- /.tab-pane -->
	<?php
	}
	}
	?>
  
              <!-- /.tab-pane -->
            </div>
            <!-- /.tab-content -->
			
          </div>					
			</div>
		
              </div>
	
</div>	
</div>
</div>
</section>	
</div>