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
if(isset($_POST['Mastercard_transfer']))
{
    $currency =  mysqli_real_escape_string($link, $_POST['currency']);
    $amt_totransfer =  mysqli_real_escape_string($link, $_POST['amount']);
    $tpin = mysqli_real_escape_string($link, $_POST['tpin']);
    $loadedAmt = $amt_totransfer * 100;
    //$myaccount = $_SESSION['acctno'];
   
    $systemset = mysqli_query($link, "SELECT * FROM systemset");
  	$row1 = mysqli_fetch_object($systemset);
  	$vat_rate = $row1->vat_rate;
  	$transfer_charges = $row1->transfer_charges;
    $bancore_merchantID = $row1->bancore_merchant_acctid;
  	$bancore_mprivate_key = $row1->bancore_merchant_pkey;
  	$passcode = substr($phone2, -13).$loadedAmt.$currency.$bancore_merchantID.$bancore_mprivate_key;
  	$encKey = hash('sha256',$passcode);
    
    $txid = "EA-prefundCard-".time();
  	$api_name =  "card_load";
  	$search_restapi = mysqli_query($link, "SELECT * FROM atmcard_gateway_apis WHERE api_name = '$api_name' AND issuer_name = '$issurer'");
  	$fetch_restapi = mysqli_fetch_object($search_restapi);
  	$api_url = $fetch_restapi->api_url;
    
    function cardLoader($ph, $cardcurrency, $amt, $orderID, $debug=false){
      global $bancore_merchantID,$encKey,$api_url;
      		
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
	
  	$newAmount = ($vat_rate * $amt_totransfer) + $amt_totransfer + $transfer_charges;
  	
    $remainBalance = $bwallet_balance - $newAmount;
    $mycurrentTime = date("Y-m-d h:i:s");
  	
  	if($bwallet_balance < $newAmount){
  	    
      echo '<meta http-equiv="refresh" content="5;url=transfer.php?id='.$_SESSION['tid'].'&&acn='.$_GET['acn'].'&&mid=OTAw&&tab=tab_10">';
      echo '<br>';
      echo'<span class="itext" style="color: orange;">Insufficient Fund in your Wallet!!</span>';
  		
  	}
    elseif($tpin != $myuepin){
      
      echo '<meta http-equiv="refresh" content="5;url=transfer.php?id='.$_SESSION['tid'].'&&acn='.$_GET['acn'].'&&mid=OTAw&&tab=tab_10">';
      echo '<br>';
      echo'<span class="itext" style="color: orange;">Oops! Invalid Transaction Pin!</span>';
      
    }
  	else{
  		$debug = true;
  		$cardChecker = cardLoader($phone2,$currency,$loadedAmt,$txid,$debug);
  		$iparr = split ("\&", $cardChecker);
  		$regStatus = substr("$iparr[0]",7);
      //echo $cardChecker;
      
  		if($regStatus == 100 || $regStatus != 30){
  			
        echo '<meta http-equiv="refresh" content="5;url=transfer.php?id='.$_SESSION['tid'].'&&acn='.$_GET['acn'].'&&mid=OTAw&&tab=tab_10">';
      	echo '<br>';
      	echo'<span class="itext" style="color: orange;">General failure. Please try again</span>';
  			
  		}
  		elseif($regStatus == 30){
  			
        $icm_id = "ICM".rand(5000000,9999999);
        
        $revenue = $newAmount - $amt_totransfer;
            
        $icm_date = date("Y/m/d");
        
		    mysqli_query($link, "INSERT INTO income VALUES(null,'','$icm_id','Revenue','$revenue','$icm_date','Super Wallet to Customer Mastercard')");
		    mysqli_query($link, "UPDATE borrowers SET wallet_balance = '$remainBalance' WHERE account = '$acctno'");
  			mysqli_query($link, "INSERT INTO wallet_history VALUE(null,'$bbranchid','$txid','$acctno','','$amt_totransfer','Debit','$currency','Topup-Prepaid_Card','Response: Prepaid Card was Topup with $currency.$amt_totransfer','successful','$mycurrentTime','$acctno','$remainBalance','')");
  			
        echo '<meta http-equiv="refresh" content="5;url=transfer.php?id='.$_SESSION['tid'].'&&acn='.$_GET['acn'].'&&mid=OTAw&&tab=tab_10">';
      	echo '<br>';
      	echo'<span class="itext" style="color: blue;">Prepaid Card Topup Successfully</span>';
  		}
    }
}
?>
</div>
</body>
</html>