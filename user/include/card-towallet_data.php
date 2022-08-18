<div class="row">
	      <section class="content">
        
       <div class="box box-default">
           <div class="box-body">
             <div class="table-responsive"> 
            <div class="box-body">
            <a href="mywallet_history.php?tid=<?php echo $_SESSION['tid']; ?>&&acn=<?php echo $_SESSION['acctno']; ?>&&mid=NDA4"><button type="button" class="btn btn-flat bg-orange"><i class="fa fa-mail-reply-all"></i>&nbsp;Back</button></a> 
<button type="button" class="btn btn-flat bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>" align="left" disabled>&nbsp;<b>Wallet Balance:</b>&nbsp;
<strong class="alert bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">
<?php
echo $bbcurrency.number_format($bwallet_balance,2,'.',',');
?> 
</strong>
  </button>
  
            <div class="col-md-14">
            <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
             <li <?php echo ($_GET['tab'] == 'tab_1') ? "class='active'" : ''; ?>><?php echo ($issurer == "Bancore" && $card_id != "NULL") ? '<a href="card-towallet.php?id='.$_SESSION['tid'].'&&acn='.$_SESSION['acctno'].'&&mid=OTAw&&tab=tab_1">Mastercard-to-Wallet</a>' : ''; ?></li>

              </ul>
             <div class="tab-content">

      <div class="tab-pane <?php echo ($_GET['tab'] == 'tab_1') ? 'active' : ''; ?>" id="tab_2">

        <div class="box-body">
           
       <form class="form-horizontal" method="post" enctype="multipart/form-data">
<?php
if(isset($_POST['save']))
{
  	$amount =  mysqli_real_escape_string($link, $_POST['amount']);
  	$currency = mysqli_real_escape_string($link, $_POST['currency']);
  	$tpin =  mysqli_real_escape_string($link, $_POST['tpin']);	
  	$finalAmt = $amount * 100;
  	$OrderID = "EA-cardW-".mt_rand(202000000,999999999);

  	$api_name =  "withdraw_fund";
  	$search_restapi = mysqli_query($link, "SELECT * FROM atmcard_gateway_apis WHERE api_name = '$api_name' AND issuer_name = '$issurer'");
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
	
  	if($tpin != $myuepin){
		  
		  echo "<script>alert('Oops! Invalid Transaction Pin!'); </script>";
		  echo "<script>window.location='card-towallet?id=".$_SESSION['tid']."&&mid=".base64_encode('900')."&&tab=tab_1'; </script>";
    
  	}else{
		  
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
		$calc_mywalletBal = $bwallet_balance + $amount;
    
    if($requestStatus == 30){
			
			mysqli_query($link, "UPDATE borrowers SET wallet_balance = '$calc_mywalletBal' WHERE account = '$acctno'");
			mysqli_query($link, "INSERT INTO wallet_history VALUE(null,'$bbranchid','$OrderID','$acctno','$amount','$currency','Card_Withdrawal','Response: Withdrawal of $currency.$amount was made from My Card to My Wallet','successful',NOW(),'$acctno','$bwallet_balance')");
			
      echo "<script>alert('Withdrawal made successfully.');</script>";
      echo "<script>window.location='card-towallet?id=".$_SESSION['tid']."&&mid=".base64_encode('900')."&&tab=tab_1'; </script>";
			
		}
    else{
      
      		echo "<script>alert('Oops!.Network Error, please try again later OR contact our support for further assistance $cardW');</script>";
          echo "<script>window.location='card-towallet?id=".$_SESSION['tid']."&&mid=".base64_encode('900')."&&tab=tab_1'; </script>";
      
    	}
    
  	}
}
?>
             <div class="box-body">
      
      <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Amount</label>
                  <div class="col-sm-10">
					  <input name="amount" type="text" class="form-control" autocomplete="off" placeholder="Enter the Amount" /required>
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
      
       </form> 

      </div>
    </div>

	
</div>	
</div>
</div>
</div>
</div>
</div>
</div>
</section>
</div>