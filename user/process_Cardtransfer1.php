<?php 
error_reporting(0); 
include "../config/session.php";
?>  

<!DOCTYPE html>
<html>
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet" href="../dist/css/style.css" />
</head>
<body>
<br><br><br><br><br><br><br><br><br>
<div style="width:100%;text-align:center;vertical-align:bottom">
		<div class="loader"></div>
		
<?php
if(isset($_POST['Vervecard_transfer']))
{
    try{
    	$currency =  mysqli_real_escape_string($link, $_POST['currency']);
        $amt_totransfer =  mysqli_real_escape_string($link, $_POST['amount']);
        $tpin = mysqli_real_escape_string($link, $_POST['tpin']);
        $amt_tofund = number_format($amt_totransfer,2,'.','');
    	$pref = "EA-fundVerve-".mt_rand(100000000,999999999);
    	$pan = mysqli_real_escape_string($link, $_POST['pan']);
    	$cust_fname = $myfn;
    	$cust_lname = $myln;
    	$cust_email = $email2;

    	$systemset = mysqli_query($link, "SELECT * FROM systemset");
      	$row1 = mysqli_fetch_object($systemset);
      	$vat_rate = $row1->vat_rate;
      	$transfer_charges = $row1->transfer_charges;
        $verveAppId = $row1->verveAppId;
        $verveAppKey = $row1->verveAppKey;
    
        $txid = "EA-prefundCard-".mt_rand(7000000,9999999);
      	$api_name =  "card_load";
      	$search_restapi = mysqli_query($link, "SELECT * FROM atmcard_gateway_apis WHERE api_name = '$api_name' AND issuer_name = '$issurer'");
      	$fetch_restapi = mysqli_fetch_object($search_restapi);
      	$api_url = $fetch_restapi->api_url;
      	
      	$newAmount = ($vat_rate * $amt_tofund) + $amt_tofund + $transfer_charges;
  	
      	if($bwallet_balance < $newAmount){

      	    echo '<meta http-equiv="refresh" content="5;url=transfer.php?id='.$_SESSION['tid'].'&&acn='.$_GET['acn'].'&&mid=OTAw&&tab=tab_11">';
            echo '<br>';
            echo'<span class="itext" style="color: orange;">Insufficient Fund in your Wallet!!</span>';
            
      	}
        elseif($tpin != $myuepin){
          
          echo '<meta http-equiv="refresh" content="5;url=transfer.php?id='.$_SESSION['tid'].'&&acn='.$_GET['acn'].'&&mid=OTAw&&tab=tab_11">';
          echo '<br>';
          echo'<span class="itext" style="color: orange;">Oops! Invalid Transaction Pin!</span>';
          
        }
        else{
            
            $client = new SoapClient($api_url);
    
            $param = array(
              'appId'=>$verveAppId,
              'appKey'=>$verveAppKey,
              'currencyCode'=>$currency,
              'emailAddress'=>$cust_email,
              'firstName'=>$cust_fname,
              'lastName'=>$cust_lname,
              'mobileNr'=>$phone2,
              'amount'=>$amt_tofund,
              'pan'=>$pan,
              'PaymentRef'=>$pref
            );
        
            $response = $client->PostIswCardFund($param);
            
            $process = json_decode(json_encode($response), true);
            
            $responseCode = $process['PostIswCardFundResult']['responseCode']; //00 OR 99
            $responseCodeGrouping = $process['PostIswCardFundResult']['responseCodeGrouping'];
            
            if($responseCode === "00"){
                    
                $transferCode = $process['PostIswCardFundResult']['transferCode'];
                $pin = $process['PostIswCardFundResult']['pin'];
                $cardPan = $process['PostIswCardFundResult']['cardPan'];
                $cvv = $process['PostIswCardFundResult']['cvv'];
                $expiryDate = $process['PostIswCardFundResult']['expiryDate'];
                $remainBalance = $bwallet_balance - $newAmount;
                $mycurrentTime = date("Y-m-d h:i:s");
                    
                $message = ($card_id == "NULL") ? "Card Pin is: ".$pin : "";
                    
                ($card_id == "NULL") ? mysqli_query($link, "UPDATE borrowers SET card_id = '$cardPan', verve_expiry_date = '$expiryDate', wallet_balance = '$remainBalance' WHERE account = '$acctno'") : mysqli_query($link, "UPDATE borrowers SET wallet_balance = '$remainBalance' WHERE account = '$acctno'");
                
                mysqli_query($link, "INSERT INTO wallet_history VALUE(null,'$bbranchid','$txid','self','','$amt_tofund','Debit','$currency','Topup-Prepaid_Card','Response: Prepaid Card was Topup with $currency.$amt_tofund','successful','$mycurrentTime','$acctno','$remainBalance','')");
                
                echo '<meta http-equiv="refresh" content="5;url=transfer.php?id='.$_SESSION['tid'].'&&acn='.$_GET['acn'].'&&mid=OTAw&&tab=tab_11">';
              	echo '<br>';
              	echo'<span class="itext" style="color: blue;">Prepaid Card Topup Successfully</span>';
            }
            else{
                
                echo '<meta http-equiv="refresh" content="5;url=transfer.php?id='.$_SESSION['tid'].'&&acn='.$_GET['acn'].'&&mid=OTAw&&tab=tab_11">';
              	echo '<br>';
              	echo'<span class="itext" style="color: orange;">General failure. Please try again</span>';
                
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
</div>
</body>
</html>