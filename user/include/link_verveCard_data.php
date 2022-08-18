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
    include("../config/walletafrica_restfulapis_call.php");
    
	$pan = mysqli_real_escape_string($link, $_POST['pan']);
	$validatePan = preg_match("/^([506]{3})([0-9]{1,16})$/", $pan, $match);
	$tpin =  mysqli_real_escape_string($link, $_POST['tpin']);
	$txid = date("dy").time();
	$currenctdate = date("Y-m-d H:i:s");
	$smsfee = ($bbranchid == "") ? 0 : $fetchsys_config['fax'];
	$isenderid = ($bbranchid = "") ? $fetchsys_config['abb'] : $bsender_id;
	
	$totalAmountToCharge = $verveCardLinkingFee + $verveCardPrefundAmt + $smsfee;
	
	$search_gateway = mysqli_query($link, "SELECT * FROM sms WHERE smsuser = '' AND status = 'Activated'");
    $fetch_gateway = mysqli_fetch_object($search_gateway);
    $gateway_uname = $fetch_gateway->username;
    $gateway_pass = $fetch_gateway->password;
    $gateway_api = $fetch_gateway->api;

	if($myuepin != $tpin){
	    
	    echo "<div class='alert bg-orange'>Opps!...Invalid Transaction Pin Entered</div>";
	    
	}
	elseif($totalAmountToCharge < $bwallet_balance){
	    
	    echo "<div class='alert bg-orange'>Sorry!..You do not have sufficient fund in your wallet to complete the card linking!!</div>";
	    
	}elseif(!$validatePan){
	    
	    echo "<div class='alert bg-orange'>Opps!..Invalid VerveCard Pan Number Entered!!</div>";
	    
	}
	else{
	    
	    $search_restapi = mysqli_query($link, "SELECT * FROM restful_apisetup WHERE api_name = 'walletafrica_debit'");
        $fetch_restapi = mysqli_fetch_object($search_restapi);
        $api_url = $fetch_restapi->api_url;
        
        $postdata =  array(
            "transactionReference" => $txid,
            "amount" => $totalAmountToCharge,
            "phoneNumber" => $bvirtual_phone_no,
            "secretKey" => $walletafrica_skey
        );
                           
        $make_call = callAPI('POST', $api_url, json_encode($postdata));
        $result = json_decode($make_call, true);
        
        if($result['Response']['ResponseCode'] == "200"){
            
            //Sender Parameters
            $amountDebited = $result['Data']['AmountCredited'];
            $senderBalance = $result['Data']['CustomerWalletBalance'];
            
            $search_restapi1 = mysqli_query($link, "SELECT * FROM restful_apisetup WHERE api_name = 'walletafrica_transfer'");
            $fetch_restapi1 = mysqli_fetch_object($search_restapi1);
            $api_url1 = $fetch_restapi1->api_url;
                    
            $postdata1 =  array(
                "SecretKey" => $walletafrica_skey,
                "BankCode" => $verveSettlementBankCode,
                "AccountNumber" => $verveSettlementAcctNo,
                "AccountName" => $verveSettlementAcctName,
                "TransactionReference" => $txid,
                "Amount" => $verveCardPrefundAmt
            );
                                       
            $make_call1 = callAPI('POST', $api_url1, json_encode($postdata1));
            $result1 = json_decode($make_call1, true);
            
            if($result1['ResponseCode'] == "100"){
                        
                $gatewayResponse = $result1['Message'];
                $transactionDateTime = date("Y-m-d H:i:s");
                
                $api_name =  "card_load";
        		$search_restapi2 = mysqli_query($link, "SELECT * FROM atmcard_gateway_apis WHERE api_name = '$api_name' AND issuer_name = '$issurer'");
        		$fetch_restapi2 = mysqli_fetch_object($search_restapi2);
        		$api_url2 = $fetch_restapi2->api_url;
        		
        		$client = new SoapClient($api_url2);

                $param = array(
                  'appId'=>$verveAppId,
                  'appKey'=>$verveAppKey,
                  'currencyCode'=>"566",
                  'emailAddress'=>$email2,
                  'firstName'=>$myfn,
                  'lastName'=>$myln,
                  'mobileNr'=>$phone2,
                  'amount'=>$verveCardPrefundAmt,
                  'pan'=>$pan,
                  'PaymentRef'=>$txid
                );
                
                $response = $client->PostIswCardFund($param);
                        
                $process = json_decode(json_encode($response), true);
                        
                $responseCode = $process['PostIswCardFundResult']['responseCode']; //90000 OR 99
                $responseCodeGrouping = $process['PostIswCardFundResult']['responseCodeGrouping'];
                
                $sms = "$isenderid>>>Dear $myln, This is to notify you that your Verve card with Pan Number: ".panNumberMasking($pan)." has been linked to your account with a prefunded balance of $bbcurrency".number_format($verveCardPrefundAmt,2,'.',',')."";
                $sms .= "Time ".date('m/d/Y').' '.(date(h) + 1).':'.date('i a')."";
                
                if($responseCode == "90000"){
                    
                    $update = mysqli_query($link, "UPDATE borrowers SET card_id = '$pan', card_reg = 'Yes', card_issurer = 'VerveCard', wallet_balance = '$senderBalance' WHERE account = '$acctno'") or die ("Error: " . mysqli_error($link));
    	    
    	            $insert = mysqli_query($link, "INSERT INTO wallet_history VALUE(null,'$bbranchid','$txid','$acctno','$verveCardLinkingFee','$bbcurrency','VerveCard_Verification','Charges for linking verve card with pan number $pan','successful','$currenctdate','$acctno','$bwallet_balance')");
                    
                    $insert = mysqli_query($link, "INSERT INTO wallet_history VALUE(null,'$bbranchid','$txid','$acctno','$verveCardPrefundAmt','$bbcurrency','Topup-Prepaid_Card','Prefund Amount for verve card with pan number $pan','successful','$currenctdate','$acctno','$bwallet_balance')");
                    
                    include("../cron/mygeneral_sms.php");
            
                    $debug = true;
                    sendSms($isenderid,$phone2,$sms,$debug);
                    
                    echo "<div class='alert bg-blue'>Vervecard linked successfully!!</div>";
        		    echo '<meta http-equiv="refresh" content="3;url=transfer.php?tid='.$_SESSION['tid'].'&&acn='.$acctno.'&&mid=OTAw&&tab=tab_11">';
                    
                }
                elseif($responseCode == "99"){
                    
                    $search_restapi3 = mysqli_query($link, "SELECT * FROM restful_apisetup WHERE api_name = 'walletafrica_credit'");
                    $fetch_restapi3 = mysqli_fetch_object($search_restapi3);
                    $api_url3 = $fetch_restapi3->api_url;
                    
                    $postdata3 =  array(
                        "transactionReference" => $txid,
                        "amount" => $verveCardPrefundAmt,
                        "phoneNumber" => $bvirtual_phone_no,
                        "secretKey" => $walletafrica_skey
                    );
                                       
                    $make_call3 = callAPI('POST', $api_url3, json_encode($postdata3));
                    $result3 = json_decode($make_call3, true);
                    
                    //Receivers Parameters
                    $amountCredited = $result3['Data']['AmountCredited'];
                    $receiverBalance = $result3['Data']['RecipientWalletBalance'];
                    
                    $update = mysqli_query($link, "UPDATE borrowers SET wallet_balance = '$receiverBalance' WHERE account = '$acctno'") or die ("Error: " . mysqli_error($link));
    	    
    	            $insert = mysqli_query($link, "INSERT INTO wallet_history VALUE(null,'$bbranchid','$txid','$acctno','$verveCardLinkingFee','$bbcurrency','VerveCard_Verification','Charges for linking verve card with pan number $pan','successful','$currenctdate','$acctno','$bwallet_balance')");

                    echo "<div class='alert bg-orange'>Opps!..Access denied, please try again later!!</div>";
                    
                }
                else{
                    
                    $data = $txid."|".$verveCardPrefundAmt."|".$totalAmountToCharge;
                    
                    $update = mysqli_query($link, "UPDATE borrowers SET card_id = '$pan', card_reg = 'Yes', card_issurer = 'VerveCard', wallet_balance = '$senderBalance' WHERE account = '$acctno'") or die ("Error: " . mysqli_error($link));
                    
                    $insert = mysqli_query($link, "INSERT INTO wallet_history VALUE(null,'$bbranchid','$txid','$acctno','$verveCardLinkingFee','$bbcurrency','VerveCard_Verification','Charges for linking verve card with pan number $pan','successful','$currenctdate','$acctno','$bwallet_balance')");
                    
                    $insert = mysqli_query($link, "INSERT INTO wallet_history VALUE(null,'$bbranchid','$txid','$acctno','$verveCardPrefundAmt','$bbcurrency','Topup-Prepaid_Card','Prefund Amount for verve card with pan number $pan','successful','$currenctdate','$acctno','$bwallet_balance')");
                    
                    mysqli_query($link, "INSERT INTO otp_confirmation VALUES(null,'$acctno','none','$data','batchCardDisbursement','$currenctdate')");
                    
                    include("../cron/mygeneral_sms.php");
            
                    $debug = true;
                    sendSms($isenderid,$phone2,$sms,$debug);
                    
                    echo "<div class='alert bg-blue'>Your request to link card as been recieved and will be process shortly!!</div>";
                    echo '<meta http-equiv="refresh" content="3;url=transfer.php?tid='.$_SESSION['tid'].'&&acn='.$acctno.'&&mid=OTAw&&tab=tab_11">';
                    
                }
                
            }
            
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