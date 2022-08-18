<div class="box">
	       <div class="box-body">
			<div class="panel panel-success">
            <div class="panel-heading bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">
            <h3 class="panel-title"> <i class="fa fa-recycle"></i> Initiate Cardless ATM Withdrawal</h3>
            </div>

             <div class="box-body">
 <?php
if(isset($_POST['save']))
{
	$acn = $_SESSION['acctno'];
	$amount =  mysqli_real_escape_string($link, $_POST['amount']);
	$currency = mysqli_real_escape_string($link, $_POST['currency']);
	$tpin =  mysqli_real_escape_string($link, $_POST['tpin']);	
	$finalAmt = $amount * 100;
  $OrderID = "EA-cardlessW-".mt_rand(2000000,9999999);
  $sender_name = mysqli_real_escape_string($link, $_POST['sender_name']);	
  $senderid = mysqli_real_escape_string($link, $_POST['senderid']);
  $beneficiary_name = mysqli_real_escape_string($link, $_POST['beneficiary_name']);

	$api_name =  "cardless_withdrawal";
  $search_restapi = mysqli_query($link, "SELECT * FROM atmcard_gateway_apis WHERE api_name = '$api_name' AND issuer_name = '$issurer'");
  $fetch_restapi = mysqli_fetch_object($search_restapi);
  $api_url = $fetch_restapi->api_url;
	
	$systemset = mysqli_query($link, "SELECT * FROM systemset");
  $row1 = mysqli_fetch_object($systemset);
  $bancore_merchantID = $row1->bancore_merchant_acctid;
  $bancore_mprivate_key = $row1->bancore_merchant_pkey;
  $auth_charges = $row1->auth_charges;
              
  $passcode = $bancore_merchantID.substr($phone2, -13).$finalAmt.$currency.$bancore_mprivate_key;
  $encKey = hash('sha256',$passcode);
	
  if($tpin != $myuepin){
    
    echo "<script>alert('Oops! Invalid Transaction Pin!'); </script>";
		echo "<script>window.location='cardlesswith.php?tid=".$_SESSION['tid']."&&acn=".$_SESSION['acctno']."&&mid=OTAw'; </script>";
    
  }elseif($bwallet_balance < ($amount + $auth_charges)){
    
    echo "<script>alert('Sorry! You do not have sufficient fund to complete this transaction!!'); </script>";
		echo "<script>window.location='cardlesswith.php?tid=".$_SESSION['tid']."&&acn=".$_SESSION['acctno']."&&mid=OTAw'; </script>";
    
  }else{
    
    function cardlessWithdrawal($phone, $cardcurrency, $amt, $orderID, $senderName, $senderID, $beneficiaryName, $debug=false){
      global $bancore_merchantID,$encKey,$api_url;
		
			$url = '?merchantID='.$bancore_merchantID;
		  $url.= '&phone='.urlencode(substr($phone, -13));
			$url.= '&currency='.urlencode($cardcurrency);
		  $url.= '&amount='.urlencode($amt);
			$url.= '&orderID='.urlencode($orderID);
      $url.= '&senderName='.urlencode($senderName);
      $url.= '&senderID='.urlencode($senderID);
      $url.= '&beneficiaryName='.urlencode($beneficiaryName);
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
		$cardLess = cardlessWithdrawal($phone2,$currency,$finalAmt,$OrderID,$sender_name,$senderid,$beneficiary_name,$debug);
		$iparr = split ("\&", $cardLess);
		$requestStatus = substr("$iparr[0]",7);
    $transferCode = substr("$iparr[1]",5);
    $otp = substr("$iparr[2]",4);
    $benefName = substr("$iparr[5]",16);
    $income_id = "ICM".rand(200001,99999);
    $today = date("Y-m-d");
    $cOrderID = "EA-cardLessW-".mt_rand(20000000,99999999);
    $calc_walletBal = $bwallet_balance - $amount - $auth_charges;
    
    if($requestStatus == 30){
			
      mysqli_query($link, "UPDATE borrowers SET wallet_balance = '$calc_walletBal' WHERE account = '$acctno'");
		  mysqli_query($link, "INSERT INTO wallet_history VALUE(null,'$bbranchid','$cOrderID','self','$amount','$currency','Cardless_Withdrawal','Response: $currency.$amount was initiated for cardless withdrawal for Card ID: $card_id','successful',NOW(),'$acctno','$bwallet_balance')");
      mysqli_query($link, "INSERT INTO income VALUES(null,'$acctno','$income_id','Revenue','$auth_charges','$today','Cardless_Withdrawal')");
				
			echo "<script>alert('ATM Cardless Withdrawal Initiated successfully. \\nTransfer Code: $transferCode\\nPin: $otp');</script>";
      echo "<script>window.location='cardlesswith.php?tid=".$_SESSION['tid']."&&acn=".$_SESSION['acctno']."&&mid=OTAw'; </script>";
			
		}
    else{
      
      echo "<script>alert('Oops!.Network Error, please try again later $cardLess');</script>";
      echo "<script>window.location='cardlesswith.php?tid=".$_SESSION['tid']."&&acn=".$_SESSION['acctno']."&&mid=OTAw'; </script>";
      
    }
    
  }
}
?>           
			 <form class="form-horizontal" method="post" enctype="multipart/form-data">
       
<div class="alert bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>">
<b>NOTE</b> that the authorization charges for the cardless ATM withdrawal is <b><?php echo $bbcurrency.$fetchsys_config['auth_charges']; ?></b><br>
Also, note that the equivalent amount to be withdrawn at the ATM will be deducted from your <b>Wallet Balance</b> immediately after initiating this request.
</div>
<hr>
             <div class="box-body">

		    <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Amount</label>
                  <div class="col-sm-10">
                  <select name="amount" class="form-control select2" required>
                    <option value="1000">1000</option>
                    <option value="2000">2000</option>
                    <option value="3000">3000</option>
                    <option value="4000">4000</option>
                    <option value="5000">5000</option>
                    <option value="6000">6000</option>
                    <option value="7000">7000</option>
                    <option value="8000">8000</option>
                    <option value="9000">9000</option>
                    <option value="10000">10000</option>
                    <option value="11000">11000</option>
                    <option value="12000">12000</option>
                    <option value="13000">13000</option>
                    <option value="14000">14000</option>
                    <option value="15000">15000</option>
                    <option value="16000">16000</option>
                    <option value="17000">17000</option>
                    <option value="18000">18000</option>
                    <option value="19000">19000</option>
                    <option value="20000">20000</option>
                  </select>
                  </div>
                  </div>

            <div class="form-group">
                      <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Currency</label>
                      <div class="col-sm-10">
            <select name="currency"  class="form-control select2" required>
              <option value="" selected>Select Currency</option>
              <option value="<?php echo $bbcurrency; ?>"><?php echo $bbcurrency; ?></option>
            </select>                 
            </div>
    		</div>
			
			<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Sender ID</label>
                  <div class="col-sm-10">
                  <input name="senderid" type="text" class="form-control" autocomplete="off" placeholder="10 digit identifier of the sender e.g Account Number" /required>
                  </div>
                  </div>
                  
      <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Sender's Name</label>
                  <div class="col-sm-10">
                  <input name="sender_name" type="text" class="form-control" autocomplete="off" placeholder="Sender's Name" /required>
                  </div>
                  </div>
                  
      <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Beneficiary Name</label>
                  <div class="col-sm-10">
                  <input name="beneficiary_name" type="text" class="form-control" autocomplete="off" placeholder="Beneficiary Name" /required>
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
			  
			 </form> 

</div>	
</div>	
</div>
</div>