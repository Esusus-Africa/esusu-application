<div class="box">
	       <div class="box-body">
			<div class="panel panel-success">
            <div class="panel-heading bg-blue">
            <h3 class="panel-title"><i class="fa fa-link"></i> Linking Vervecard</h3>
            </div>

             <div class="box-body">
 <?php
if(isset($_POST['linkcard']))
{
    
	$cardholder =  mysqli_real_escape_string($link, $_POST['cardholder']);
	$pan = mysqli_real_escape_string($link, $_POST['pan']);
	$validatePan = preg_match("/^([506]{3})([0-9]{1,16})$/", $pan, $match);
	$tpin =  mysqli_real_escape_string($link, $_POST['tpin']);
	
	$search_customerbal = mysqli_query($link, "SELECT * FROM borrowers WHERE account = '$cardholder'");
	$fetch_customerbal = mysqli_fetch_array($search_customerbal);
	$wallet_bal = $fetch_customerbal['wallet_balance'];
	$virtual_phoneno = $fetch_customerbal['virtual_number'];
	$cust_email = $fetch_customerbal['email'];
	$cust_fname = $fetch_customerbal['fname'];
	$cust_lname = $fetch_customerbal['lname'];
	$cust_phone = $fetch_customerbal['phone'];
	$bank = $fetch_customerbal['card_issurer'];
    $currency = $fetch_customerbal['currency'];
    $institution_id = $fetch_customerbal['branchid'];
    $isbranchid = $fetch_customerbal['sbranchid'];
	$txid = date("dy").time();
	$currenctdate = date("Y-m-d h:i:s");
	$isenderid = $fetchsys_config['abb'];
	$smsfee = $fetchsys_config['fax'];
	
	$search_gateway = mysqli_query($link, "SELECT * FROM sms WHERE smsuser = '' AND status = 'Activated'");
    $fetch_gateway = mysqli_fetch_object($search_gateway);
    $gateway_uname = $fetch_gateway->username;
    $gateway_pass = $fetch_gateway->password;
    $gateway_api = $fetch_gateway->api;
	
	$totalAmountToCharge = $verveCardLinkingFee + $verveCardPrefundAmt;
	
	$sms = "$isenderid>>>Dear $cust_lname, This is to notify you that your Verve card with Pan Number: ".panNumberMasking($pan)." has been linked to your account with a prefunded balance of $currency".number_format($verveCardPrefundAmt,2,'.',',')."";
    $sms .= "Time ".date('m/d/Y').' '.(date(h) + 1).':'.date('i a')."";
    
    $maskPan = panNumberMasking($pan);
	
	if($control_pin != $tpin){
	    
	    echo "<div class='alert bg-orange'>Opps!...Invalid Transaction Pin Entered</div>";
	    
	}
	elseif($totalAmountToCharge < $wallet_bal){
	    
	    echo "<div class='alert bg-orange'>Sorry!..Customer do not have suffuicient fund in his wallet for you to do the linking successfully!!</div>";
	    
	}elseif(!$validatePan){
	    
	    echo "<div class='alert bg-orange'>Opps!..Invalid VerveCard Pan Number Entered!!</div>";
	    
	}
	else{
	    
        //Sender Parameters
        $amountDebited = $totalAmountToCharge;
        $senderBalance = $wallet_bal - $totalAmountToCharge;
                        
        $gatewayResponse = $result1['Message'];
        $transactionDateTime = date("Y-m-d H:i:s");
            
        $api_name =  "card_load";
        $search_restapi2 = mysqli_query($link, "SELECT * FROM atmcard_gateway_apis WHERE api_name = '$api_name' AND issuer_name = '$bank'");
        $fetch_restapi2 = mysqli_fetch_object($search_restapi2);
        $api_url2 = $fetch_restapi2->api_url;
        	
        $client = new SoapClient($api_url2);

        $param = array(
          'appId'=>$verveAppId,
          'appKey'=>$verveAppKey,
          'currencyCode'=>"566",
          'emailAddress'=>$cust_email,
          'firstName'=>$cust_fname,
          'lastName'=>$cust_lname,
          'mobileNr'=>$cust_phone,
          'amount'=>$verveCardPrefundAmt,
          'pan'=>$pan,
          'PaymentRef'=>$txid
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
		$STAN = rand(000000,999999);
		$RRN = date("y").time();
		$CurrCode = "NGN";
		$DateTime = date('m/d/Y').' '.(date(h) + 1).':'.date('i A');
                
        if($responseCode == "90000"){
                    
            $update = mysqli_query($link, "UPDATE borrowers SET card_id = '$pan', card_reg = 'Yes', card_issurer = 'VerveCard', wallet_balance = '$senderBalance' WHERE account = '$cardholder'") or die ("Error: " . mysqli_error($link));
    	    
    	    $insert = mysqli_query($link, "INSERT INTO wallet_history VALUE(null,'','$txid','$maskPan','','$verveCardLinkingFee','Debit','NGN','VerveCard_Verification','Charges for linking verve card with pan number $pan','successful','$currenctdate','$uid','$senderBalance','')");

            $insert = mysqli_query($link, "INSERT INTO wallet_history VALUE(null,'','$txid','$cardholder','','$verveCardPrefundAmt','Debit','NGN','Topup-Prepaid_Card','Prefund Amount for verve card with pan number $pan','successful','$currenctdate','$uid','$senderBalance','')");
            
            mysqli_query($link, "INSERT INTO cardTransaction VALUES(null,'$customer','$cust_fname','$cust_lname','$cust_email','$cust_phone','$pan','$maskPan','ESUSU AFRICA LTD, LA LANG','$DateTime','$STAN','$RRN','$verveCardPrefundAmt','$availbal','$CurrCode','$tType','$institution_id','$isbranchid','$uid')");

            include("../cron/mygeneral_sms.php");
            
            $debug = true;
            sendSms($isenderid,$cust_phone,$sms,$debug);
            
            echo "<div class='alert bg-blue'>Vervecard linked successfully!!</div>";

        }
        elseif($responseCode == "99"){
                
            echo "<div class='alert bg-orange'>Opps!..Access denied, please try again later!!</div>";
                    
        }
        else{
            
            echo "<div class='alert bg-orange'>Opps!..Network Error, please try again later!!</div>";
            
        }
	    
    }
    
}
?>           
			 <form class="form-horizontal" method="post" enctype="multipart/form-data">

             <div class="box-body">
			 
			<div class="form-group">
                <label for="" class="col-sm-3 control-label" style="color:blue;">New Cardholder:</label>
                <div class="col-sm-6">
                    <select name="cardholder"  class="form-control select2" required>
                      <option value="" selected>Select New Cardholder</option>
                        <?php
                        $search = mysqli_query($link, "SELECT * FROM borrowers WHERE card_reg = 'Yes' AND card_issurer = 'VerveCard' AND card_id = 'NULL'");
                        while($get_search = mysqli_fetch_array($search))
                        {
                        ?>
                      <option value="<?php echo $get_search['account']; ?>"><?php echo $get_search['virtual_acctno'].' - '.$get_search['fname'].' '.$get_search['lname'].' '.$get_search['mname']; ?></option>
                        <?php } ?>
                </select>
                </div>
                <label for="" class="col-sm-3 control-label"></label>
            </div>
			
			<div class="form-group">
                <label for="" class="col-sm-3 control-label" style="color:blue;">Pan Number:</label>
                <div class="col-sm-6">
                  <input name="pan" type="text" class="form-control" placeholder="Enter the Card Pan" required>
                  <span style="color: orange;"> <b>Here, you need to enter the Card Pan Number.</span>
                  </div>
                <label for="" class="col-sm-3 control-label"></label>
            </div>
			
			<div class="form-group">
                <label for="" class="col-sm-3 control-label" style="color:blue;">Transaction Pin:</label>
                <div class="col-sm-6">
                  <input name="tpin" type="password" class="form-control" placeholder="Enter your transaction pin" maxlength="4" required>
                </div>
                <label for="" class="col-sm-3 control-label"></label>
            </div>
				  
			 </div>
			 
			<div class="form-group" align="right">
                <label for="" class="col-sm-3 control-label" style="color:blue;"></label>
                <div class="col-sm-6">
                	<button name="linkcard" type="submit" class="btn bg-blue"><i class="fa fa-link">&nbsp;Submit</i></button>
                </div>
                <label for="" class="col-sm-3 control-label"></label>
            </div>
			  
			 </form> 

</div>	
</div>	
</div>
</div>