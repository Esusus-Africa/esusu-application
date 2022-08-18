<div class="row">	
		
	 <section class="content">
		 
            <h3 class="panel-title"> 
            <a href="list_card.php?id=<?php echo $_SESSION['tid']; ?>&&mid=<?php echo base64_encode("404"); ?>&&tab=tab_1"><button type="button" class="btn btn-flat bg-orange"><i class="fa fa-mail-reply-all"></i>&nbsp;Back</button></a>
            
            <button type="button" class="btn btn-flat bg-orange" align="left" disabled>&nbsp;<b>Transfer Balance:</b>&nbsp;
                <strong class="alert bg-blue">
                <?php
                 echo $aggcurrency.number_format($aggwallet_balance,2,'.',',');
                ?> 
                </strong>
            </button>
            </h3>
        
		   <div class="box box-success">
           <div class="box-body">
             <div class="table-responsive"> 
            <div class="box-body">


<div class="alert bg-orange" style="font-size:17px;" align="center">
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
<hr>

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
	//include("../config/restful_apicalls.php");

	$result = array();
	//$result1 = array();
	$customer =  mysqli_real_escape_string($link, $_POST['customer']);
	$currency_type =  mysqli_real_escape_string($link, $_POST['currency_type']);
	$amount =  mysqli_real_escape_string($link, $_POST['amount']);
	$loadedAmt = $amount * 100;
	$bank =  mysqli_real_escape_string($link, $_POST['bank']);
	$tpin =  mysqli_real_escape_string($link, $_POST['tpin']);
    $refid = "EA-preFundCard-".mt_rand(10000,99999);
    $institution_id = "INST-191587338134";
    $inst_name = "Esusu Africa";

	$search_user = mysqli_query($link, "SELECT * FROM borrowers WHERE account = '$customer'");
	$get_user = mysqli_fetch_array($search_user);
	$billing_name = $get_user['fname'].' '.$get_user['lname'];
	$phone = $get_user['phone'];
	$mycurrency = $get_user['currency'];
	$mywallet = $get_user['wallet_balance'];
	$mycard_id = $get_user['card_id'];
	$mycard_reg = $get_user['card_reg'];

	$systemset = mysqli_query($link, "SELECT * FROM systemset");
	$row1 = mysqli_fetch_object($systemset);
	$seckey = $row1->secret_key;
	$sysabb = $row1->abb;
	$bancore_merchantID = $row1->bancore_merchant_acctid;
	$bancore_mprivate_key = $row1->bancore_merchant_pkey;
	$passcode = substr($phone, -13).$loadedAmt.$currency_type.$bancore_merchantID.$bancore_mprivate_key;
	$encKey = hash('sha256',$passcode);
	//echo $passcode;
	$myCurrectDateTime = date("Y-m-d h:i:s");

	if($bank == "Mastercard")
	{
		$txid = "EA-cOrder-".mt_rand(1000000,9999999);
		$api_name =  "card_load";
		$search_restapi = mysqli_query($link, "SELECT * FROM atmcard_gateway_apis WHERE api_name = '$api_name' AND issuer_name = '$bank'");
		$fetch_restapi = mysqli_fetch_object($search_restapi);
		$api_url = $fetch_restapi->api_url;
    
	    if($tpin != $myiepin){
	    
	      	echo "<script>alert('Oops! Invalid Transaction Pin!'); </script>";
	  	  	echo "<script>window.location='create_card?id=".$_SESSION['tid']."&&mid=".base64_encode('404')."&&tab=tab_1'; </script>";
	      
	    }elseif($aggwallet_balance < $amount){
			
			echo "<script>alert('Oops! You do not have sufficient fund in your wallet to complete this transaction!!'); </script>";
	  	   	echo "<script>window.location='create_card?id=".$_SESSION['tid']."&&mid=".base64_encode('404')."&&tab=tab_1'; </script>";
			
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
	  		      }
	  		      return($response);
	  		}
	  		
	  		$debug = true;
	  		$cardChecker = cardLoader($phone,$currency_type,$loadedAmt,$txid,$debug);
	  		$iparr = split ("\&", $cardChecker);
	  		$regStatus = substr("$iparr[0]",7);
	  		$mycardID = substr("$iparr[3]",7);
			$calc_walletBalance = $aggwallet_balance - $amount;
			
	  		if($mycard_id == "NULL" && $mycard_reg == "No"){
	  			
	  	      	echo "<script>alert('Oops! No enrollment was done yet!!');</script>";
	  	      	echo "<script>window.location='create_card?id=".$_SESSION['tid']."&&mid=".base64_encode('404')."&&tab=tab_1'; </script>";
	  		
	  		}
	  		elseif($regStatus == 100 || $regStatus != 30){
	  			
	  			echo "<script>alert('General failure. Please try again OR contact our support for further assistance!!');</script>";
	        	echo "<script>window.location='create_card?id=".$_SESSION['tid']."&&mid=".base64_encode('404')."&&tab=tab_1'; </script>";
	  			
	  		}
	  		elseif($regStatus == 30 && $mycard_id == "NULL" && $mycard_reg == "Yes"){
	  			
				mysqli_query($link, "UPDATE user SET transfer_balance = '$calc_walletBalance' WHERE id = '$aggrid'");
	  			mysqli_query($link, "UPDATE borrowers SET card_id = '$mycardID' WHERE account = '$customer'");
	  			mysqli_query($link, "INSERT INTO wallet_history VALUE(null,'$institution_id','$txid','$customer','$amount','','Credit','$currency_type','Topup-Prepaid_Card','Response: Prepaid Card was Topup with $currency_type.$amount','successful','$myCurrectDateTime','$aggrid','$calc_walletBalance','')");
	  
	  			echo "<script>alert('Prepaid Card Topup Successfully');</script>";
	        	echo "<script>window.location='create_card?id=".$_SESSION['tid']."&&mid=".base64_encode('404')."&&tab=tab_1'; </script>";
	  			
	  		}
	  		elseif($regStatus == 30 && $mycard_id != "NULL" && $mycard_reg == "Yes"){
	  			
				mysqli_query($link, "UPDATE user SET transfer_balance = '$calc_walletBalance' WHERE id = '$aggrid'");
	  			mysqli_query($link, "INSERT INTO wallet_history VALUE(null,'$institution_id','$txid','$customer','$amount','','Credit','$currency_type','Topup-Prepaid_Card','Response: Prepaid Card was Topup with $currency_type.$amount','successful','$myCurrectDateTime','$aggrid','$calc_walletBalance','')");
	  				
	  			echo "<script>alert('Prepaid Card Topup Successfully');</script>";
	        	echo "<script>window.location='create_card?id=".$_SESSION['tid']."&&mid=".base64_encode('404')."&&tab=tab_1'; </script>";
	  		}
	      
	    }
			
	}
	if($bank == "VerveCard"){
	    
	    $query = mysqli_query($link, "SELECT * FROM systemset") or die (mysqli_error($link));
        $r = mysqli_fetch_object($query);
        $smsfee = $r->fax;
        $transfer_charges = ($transferToCardCharges12 == "") ? $transferToCardCharges1 : $transferToCardCharges12;
        $isenderid = "esusu";
            
	    $verveAppId = $r->verveAppId;
    	$verveAppKey = $r->verveAppKey;
    	$amt_tofund = preg_replace("/[^0-9]/", "", $amount);
    	$amountWithCharges = $amt_tofund + $transfer_charges + $smsfee;
    	$calcCharges = $amountWithCharges - $amt_tofund;
		$pref = date("yd").time();
		
		$search_customerbal = mysqli_query($link, "SELECT * FROM borrowers WHERE account = '$customer'");
		$fetch_customernum = mysqli_num_rows($search_customerbal);
		$fetch_customerbal = mysqli_fetch_array($search_customerbal);
		
		$search_agtbal = mysqli_query($link, "SELECT * FROM user WHERE id = '$customer'");
		$fetch_agtnum = mysqli_num_rows($search_agtbal);
		$fetch_agtbal = mysqli_fetch_array($search_agtbal);
		
		$cust_email = ($fetch_customernum == 1 && $fetch_agtnum == 0) ? $fetch_customerbal['email'] : $fetch_agtbal['email'];
		$cust_fname = ($fetch_customernum == 1 && $fetch_agtnum == 0) ? $fetch_customerbal['fname'] : $fetch_agtbal['name'];
		$cust_lname = ($fetch_customernum == 1 && $fetch_agtnum == 0) ? $fetch_customerbal['lname'] : $fetch_agtbal['lname'];
		$cust_phone = ($fetch_customernum == 1 && $fetch_agtnum == 0) ? $fetch_customerbal['phone'] : $fetch_agtbal['phone'];
		$bank = ($fetch_customernum == 1 && $fetch_agtnum == 0) ? $fetch_customerbal['card_issurer'] : $fetch_agtbal['card_issurer'];
		$pan = ($fetch_customernum == 1 && $fetch_agtnum == 0) ? $fetch_customerbal['card_id'] : $fetch_agtbal['card_id'];
		$vAccount = ($fetch_customernum == 1 && $fetch_agtnum == 0) ? $fetch_customerbal['virtual_acctno'] : $fetch_agtbal['virtual_acctno']; 	
    	   
    	$search_gateway = mysqli_query($link, "SELECT * FROM sms WHERE smsuser = '' AND status = 'Activated'");
        $fetch_gateway = mysqli_fetch_object($search_gateway);
        $gateway_uname = $fetch_gateway->username;
        $gateway_pass = $fetch_gateway->password;
        $gateway_api = $fetch_gateway->api;
    	    
    	$data = $pref."|".$amt_tofund."|".$amountWithCharges;
    	    
    	if($tpin != $control_pin){
    	    
    	    echo "<script>alert('Oops! Invalid Transaction Pin!'); </script>";
    	  	echo "<script>window.location='create_card?id=".$_SESSION['tid']."&&mid=".base64_encode('404')."&&tab=tab_1'; </script>";
    	      
    	}elseif($aggwallet_balance < $amountWithCharges){
    			
    		echo "<script>alert('Oops! You do not have sufficient fund in your wallet to complete this transaction!!'); </script>";
    	  	echo "<script>window.location='create_card?id=".$_SESSION['tid']."&&mid=".base64_encode('404')."&&tab=tab_1'; </script>";
    			
    	}else{
			
			//Sender Remaining Balance After Transfer
            $senderBalance = ($card_transferCommission == "0" || $card_transferCommission == "") ? ($aggwallet_balance - $amountWithCharges) : (($aggwallet_balance + $card_transferCommission) - $amountWithCharges);
                    
            $api_name4 =  "card_load";
        	$search_restapi4 = mysqli_query($link, "SELECT * FROM atmcard_gateway_apis WHERE api_name = '$api_name4' AND issuer_name = '$bank'");
        	$fetch_restapi4 = mysqli_fetch_object($search_restapi4);
        	$api_url4 = $fetch_restapi4->api_url;
                        
            //$gatewayResponse = $result1['Message'];
            $transactionDateTime = date("Y-m-d H:i:s");
    	    
            $client = new SoapClient($api_url4);
            
            $param = array(
                'appId'=>$verveAppId,
                'appKey'=>$verveAppKey,
                'currencyCode'=>"566",
                'emailAddress'=>$cust_email,
                'firstName'=>$cust_fname,
                'lastName'=>$cust_lname,
                'mobileNr'=>$cust_phone,
                'amount'=>$amt_tofund,
                'pan'=>$pan,
                'PaymentRef'=>$pref
            );
                    
            $response = $client->PostIswCardFund($param);
                        
            $process = json_decode(json_encode($response), true);
                    
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
			$CurrCode = $aggcurrency;
			$DateTime = date('m/d/Y').' '.(date(h) + 1).':'.date('i A');
			$email = $cust_email;
            $fname = $cust_fname;
			$lname = $cust_lname;
			$account = $vAccount;
                        
            $sms = "$isenderid>>>Cr. Pan Number: $PAN_Masked has been credited with $aggcurrency".number_format($amt_tofund,2,'.',',').". Card Balance: $aggcurrency".number_format($availbal,2,'.',',').". ";
            $sms .= "Date ".$DateTime."";
                        
            if($responseCode === "90000"){
                            
                $transferCode = $process['PostIswCardFundResult']['transferCode'];
                $pin = $process['PostIswCardFundResult']['pin'];
                $cardPan = $process['PostIswCardFundResult']['cardPan'];
                $cvv = $process['PostIswCardFundResult']['cvv'];
                $expiryDate = $process['PostIswCardFundResult']['expiryDate'];
                $currenctdate = date("Y-m-d H:i:s");
                //$calc_walletBalance = $aggwallet_balance - $amt_tofund;
                            
                //$message = ($mycard_id == "NULL") ? "Card Pin is: ".$pin : "";
                            
                mysqli_query($link, "UPDATE user SET transfer_balance = '$senderBalance' WHERE id = '$aggrid'");
                            
                mysqli_query($link, "INSERT INTO wallet_history VALUE(null,'$institution_id','$pref','$customer','','$amt_tofund','Debit','$aggcurrency','Topup-Prepaid_Card','Response: Prepaid Card was Topup with $aggcurrency$amt_tofund','successful','$currenctdate','$aggrid','$senderBalance','')");
                            
                mysqli_query($link, "INSERT INTO wallet_history VALUE(null,'$institution_id','$pref','$PAN_Masked','','$calcCharges','Debit','$aggcurrency','Charges','Response: Prepaid Card was Topup with $aggcurrency$amt_tofund','successful','$currenctdate','$aggrid','$senderBalance','')");
				
				($card_transferCommission == "0" || $card_transferCommission == "") ? "" : mysqli_query($link, "INSERT INTO wallet_history VALUE(null,'$institution_id','$pref','$aggrid','$card_transferCommission','','Credit','$aggcurrency','PrepaidCard_Commission','Response: Prepaid Card Commission of $aggcurrency$card_transferCommission was credited','successful','$currenctdate','$aggrid','$senderBalance','')");

				mysqli_query($link, "INSERT INTO cardTransaction VALUES(null,'$customer','$cust_fname','$cust_lname','$cust_email','$phone','$pan','$PAN_Masked','$inst_name, LG LANG','$DateTime','$STAN','$RRN','$TrxnAmount','$availbal','$CurrCode','$tType','$institution_id','','$aggrid')");
				
                include("../cron/mygeneral_sms.php");
                            
                $debug = true;
				sendSms($isenderid,$cust_phone,$sms,$debug);
				include("../config/cardEmailNotifier.php");
            
                echo "<script>alert('Prepaid Card Topup Successfully');</script>";
                echo "<script>window.location='create_card?id=".$_SESSION['tid']."&&mid=".base64_encode('404')."&&tab=tab_1'; </script>";
                            
			}
			elseif($responseCode == "99"){
            
				echo "<div class='alert bg-orange'>Opps!..Access denied, please try again later!!</div>";
				
			}
			else{
				
				echo "<div class='alert bg-blue'>Opps!..Unable to fund card, please try again later!!</div>";
				
			}
                
    	}
	    
	}

}
?>			 
            
			 <form class="form-horizontal" method="post" enctype="multipart/form-data">

			<p style="color: blue;">CHARGES: <b><?php echo $aggcurrency.number_format($transferToCardCharges12,2,'.',','); ?></b></p>
    		<p style="color: blue;">COMMISSION: <b><?php echo $aggcurrency.number_format($card_transferCommission,2,'.',','); ?></b></p>
			<hr>
             <div class="box-body">

			<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Card Issuance</label>
                  <div class="col-sm-10">
				<select name="bank"  class="form-control select2" id="card_issuer" required>
						<option value='' selected='selected'>Select Card Issuance &hellip;</option>
<?php
$search = mysqli_query($link, "SELECT DISTINCT(issuer_name) FROM atmcard_gateway_apis WHERE issuer_name != 'Flutterwave' AND issuer_name != 'Mastercard' ORDER BY id DESC");
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
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Control Pin</label>
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
					   
		
		</div>
              <!-- /.tab-pane -->
	<?php
	}
	elseif($tab == 'tab_3')
	{
	?>

		<div class="tab-pane <?php echo ($_GET['tab'] == 'tab_3') ? 'active' : ''; ?>" id="tab_3">
					   
		
		</div>
              <!-- /.tab-pane -->
	<?php
	}
	elseif($tab == 'tab_4')
	{
	?>

		<div class="tab-pane <?php echo ($_GET['tab'] == 'tab_4') ? 'active' : ''; ?>" id="tab_4">
					   
		
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
			    
<div class="alert bg-orange">
<b>NOTE</b> that any card reports triggered on our system attract <b><?php echo $aggcurrency.$fetchsys_config['report_charges']; ?></b> per request
</div>
<hr>      
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
    				$get = mysqli_query($link, "SELECT * FROM borrowers WHERE card_reg = 'Yes' AND card_issurer = 'VerveCard' AND card_id != 'NULL' AND lofficer = '$aggrid' ORDER BY id") or die ("Error: " . mysqli_error($link));
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
    function ccMasking($number, $maskingCharacter = '*') {
        return substr($number, 0, 6) . str_repeat($maskingCharacter, strlen($number) - 10) . substr($number, -4);
	}
	
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
          
          $report_charges = $fetchsys_config['report_charges'];
		  $myCurrectDateTime = date("Y-m-d h:i:s");
		  
          if($aggwallet_balance < $report_charges){
              
              echo "<script>alert('Oops! You do not have sufficient fund in your wallet to complete this operation!!'); </script>";
    	  	  echo "<script>window.location='create_card?id=".$_SESSION['tid']."&&mid=".base64_encode('404')."&&tab=tab_1'; </script>";
    			
    	  }else{
        
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
        
              //print_r($process);
              $StatusCode = $process['responseCode']; //00 OR 400
              
              if($StatusCode === "00"){
                  
                  //REPORT CHARGES
              	  $rOrderID = "EA-rCharges-".mt_rand(30000000,99999999);
                  $calc_mywalletBalance = $aggwallet_balance - $report_charges;
		
            	  mysqli_query($link, "UPDATE user SET transfer_balance = '$calc_mywalletBalance' WHERE id = '$aggrid'");
            	  
            	  mysqli_query($link, "INSERT INTO wallet_history VALUE(null,'$institution_id','$rOrderID','self','','$report_charges','Debit','$aggcurrency','Report_Charges','Response: $aggcurrency.$report_charges was charged for triggering report for Card ID: $customer','successful','$myCurrectDateTime','$aggrid','$calc_mywalletBalance','')");
                  
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
                	echo '<a href="../pdf/view/pdf_cardreports2.php?dfrom='.$StartDate.'&&dto='.$EndDate.'&&cardID='.$PAN.'" target="_blank"><button type="button" class="btn bg-blue"><i class="fa fa-print"></i> Print Reports</button></a>';
                  
              }
              else{
                  
                  echo $JsonData;
                  
              }
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