<?php
include ("../config/session.php");
include("../config/cardloader.php");
$PostType = $_GET['PostType'];

if($PostType == "Flutterwave")
{
?>          
            <?php //echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>
            
            <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">User</label>
                  <div class="col-sm-10">
				<select name="customer"  class="form-control select2" required>
					<option value='' selected='selected'>Select User</option>
                    <option disabled>CUSTOMER LIST</option>
					<?php
    				$search = mysqli_query($link, "SELECT * FROM borrowers WHERE lofficer = '$aggrid' ORDER BY id") or die (mysqli_error($link));
                    while($get_search = mysqli_fetch_array($search))
                    {
                    ?>
            
            <option value="<?php echo $get_search['account']; ?>"><?php echo $get_search['fname'].' '.$get_search['lname'].' ('.$get_search['phone'].') - Customer CardID: '.$get_search['card_id']; ?></option>
                              					
           <?php } ?>
				</select>
        <span style="color:orange;"><b>NOTE:</b> User Phone number must be in international format. If not, kindly update the user information first before you proceed with the card issuing / topup.</span>
			</div>
			</div>
                  
            <div class="form-group">
                  	<label for="" class="col-sm-2 control-label" style="color:blue;">Billing Address</label>
                  	<div class="col-sm-10">
					<textarea name="billing_addrs"  class="form-control" rows="4" cols="80" required></textarea>
           			 </div>
          	</div>
			  
			<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Billing City</label>
                  <div class="col-sm-10">
                  <input name="billing_city" type="text" class="form-control" placeholder="Billing City" required>
                  </div>
                  </div>
                  
      <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Billing State</label>
                  <div class="col-sm-10">
                  <input name="billing_state" type="text" class="form-control" placeholder="Billing State" required>
                  </div>
                  </div>
			
			<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Billing Postal/Zip Code</label>
                  <div class="col-sm-10">
                  <input name="postalcode" type="text" class="form-control" placeholder="Billing Postal/Zip Code" required>
                  </div>
                  </div>

            <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Billing Country</label>
                  <div class="col-sm-10">
				<select name="billing_country"  class="form-control select2" required>
										<option value='' selected='selected'>Select Billing Country&hellip;</option>
										<option value="NG">NG</option>
										<option value="US">US</option>
                    <option value="GB">UK</option>
				</select>
			</div>
			</div>

			<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Currency</label>
                  <div class="col-sm-10">
				<select name="currency_type"  class="form-control select2" required>
										<option value='' selected='selected'>Select Currency Type&hellip;</option>
										<option value="NGN">NGN</option>
										<option value="USD">USD</option>
                    <option value="GBP">GBP</option>
				</select>
			</div>
			</div>
			
			<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Amount</label>
                  <div class="col-sm-10">
                  <input name="amount" type="text" class="form-control" placeholder="Enter the amount to prefund the card with" required>
                  <span style="color: orange;"> <b>Here, you need to enter the Amount to Prefund the Card with on Card Creation.</span>
                  </div>
                  </div>
                  
<hr>
<div class="alert bg-orange">Card Security (Secure Customer Withdrawal via Card) </div>
<hr>
            
            <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Secure Option</label>
                  <div class="col-sm-10">
				<select name="secure_option"  class="form-control select2" required>
										<option value='' selected='selected'>Select Secure Option&hellip;</option>
										<option value="otp">OTP</option>
										<option value="pin">PIN</option>
				</select>
			</div>
			</div>
			
			<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">PIN (Optional)</label>
                  <div class="col-sm-10">
                  <input name="pin" type="text" class="form-control" value="1111" maxlength="4" readonly>
                  <span style="color: orange;"> <b>This section is applicable only if PIN is choosen above as Customer Security Check which they can later change easily via there end after issuing the card</span>
                  </div>
                  </div>

<?php
}
elseif($PostType == "Mastercard")
{
      
      $call = mysqli_query($link, "SELECT * FROM systemset");
	$row = mysqli_fetch_array($call);

?>
                  <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">User</label>
                  <div class="col-sm-10">
				<select name="customer"  class="form-control select2" required>
					<option value='' selected='selected'>Select User</option>
          <option disabled>CUSTOMER LIST</option>
					<?php
            ($individual_customer_records != "1" && $branch_customer_records != "1") ? $search = mysqli_query($link, "SELECT * FROM borrowers WHERE card_reg = 'Yes' AND card_issurer = 'Mastercard' AND branchid = '$institution_id' ORDER BY id") or die (mysqli_error($link)) : "";
    				($individual_customer_records === "1" && $branch_customer_records != "1") ? $search = mysqli_query($link, "SELECT * FROM borrowers WHERE card_reg = 'Yes' AND card_issurer = 'Mastercard' AND branchid = '$institution_id' AND lofficer = '$aggrid' ORDER BY id") or die (mysqli_error($link)) : "";
    				($individual_customer_records != "1" && $branch_customer_records === "1") ? $search = mysqli_query($link, "SELECT * FROM borrowers WHERE card_reg = 'Yes' AND card_issurer = 'Mastercard' AND branchid = '$institution_id' AND sbranchid = '$isbranchid' ORDER BY id") or die (mysqli_error($link)) : "";
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
          		$processBal = cardLoader($card_id,$debug);  
              $iparr = split ("\&", $processBal);
              $cardBal = substr("$iparr[1]",7);
              $cardCur = substr("$iparr[2]",9);
            ?>
            
            <option value="<?php echo $get_search['account']; ?>"><?php echo $get_search['fname'].' '.$get_search['lname'].' ('.$get_search['phone'].') - Customer CardID: '.$get_search['card_id']; ?> <?php echo ($cardBal == "") ? "" : "- Card Balance: ".$cardCur.$cardBal; ?></option>
                              					
           <?php } ?>
           
				</select>
        <span style="color:orange;"><b>NOTE:</b> User Phone number must be in international format. If not, kindly update the user information first before you proceed with the card issuing / topup.</span>
			</div>
			</div>
                  
                  <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Currency</label>
                  <div class="col-sm-10">
				<select name="currency_type" class="form-control" required>
					<option value='' selected='selected'>Select Currency</option>
					<option value="NGN">NGN</option>
					<option value="USD">USD</option>
                              <option value="GBP">GBP</option>
				</select>
			</div>
			</div>
			  
			<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Amount</label>
                  <div class="col-sm-10">
                  <input name="amount" type="text" class="form-control" placeholder="Enter the amount to prefund the card with" required>
                  <span style="color: orange;"> <b>Here, you need to enter the Amount to Prefund the Card with on Card Creation.</span>
                  </div>
                  </div>
           
<?php
}
elseif($PostType == "VerveCard")
{
?>

        <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">User</label>
                  <div class="col-sm-10">
				<select name="customer"  class="form-control select2" required>
					<option value='' selected='selected'>Select User</option>
                    <option disabled>CUSTOMER LIST</option>
                    <?php
                    $search = mysqli_query($link, "SELECT * FROM borrowers WHERE card_reg = 'Yes' AND card_issurer = 'VerveCard' AND card_id != 'NULL' AND lofficer = '$aggrid' ORDER BY id") or die (mysqli_error($link));
                    while($get_search = mysqli_fetch_array($search))
                    {
                        $api_name =  "display_card_bal";
                        $issurer = $get_search['card_issurer'];
                        $card_id = $get_search['card_id'];
                  		$search_restapi = mysqli_query($link, "SELECT * FROM atmcard_gateway_apis WHERE api_name = '$api_name' AND issuer_name = '$issurer'");
                  		$fetch_restapi = mysqli_fetch_object($search_restapi);
                  		$api_url = $fetch_restapi->api_url;
                      
                        $client = new SoapClient($api_url);

                        $param = array(
                          'AccountNo'=>$card_id,
                          'appId'=>$verveAppId,
                          'appKey'=>$verveAppKey
                        );
                    
                        $response = $client->GetIswPrepaidCardAccountBalance($param);
                            
                        $process = json_decode(json_encode($response), true);
                            
                        $availableBalance = $process['GetIswPrepaidCardAccountBalanceResult']['JsonData'];
                            
                        $decodeProcess = json_decode($availableBalance, true);
                        
                        $availbal = $decodeProcess['availableBalance'] / 100;
                    ?>
                    <option value="<?php echo $get_search['account']; ?>"><?php echo $get_search['fname'].' '.$get_search['lname']; ?><?php echo ($card_id === "NULL") ? "" : " | Card Bal: ".$icurrency.number_format($availbal,2,'.',','); ?></option>
                              					
                    <?php } ?>

                    <?php
                    $get3 = mysqli_query($link, "SELECT * FROM user WHERE (acctOfficer = '$aggrid' OR id = '$aggrid') AND card_reg = 'Yes' AND card_issurer = 'VerveCard' AND card_id != 'NULL' ORDER BY userid DESC") or die (mysqli_error($link));
                    while($get_search = mysqli_fetch_array($get3))
                    {
                        $api_name =  "display_card_bal";
                        $issurer = $get_search['card_issurer'];
                        $card_id = $get_search['card_id'];
                  		  $search_restapi = mysqli_query($link, "SELECT * FROM atmcard_gateway_apis WHERE api_name = '$api_name' AND issuer_name = '$issurer'");
                  		  $fetch_restapi = mysqli_fetch_object($search_restapi);
                  		  $api_url = $fetch_restapi->api_url;
                      
                        $client = new SoapClient($api_url);

                        $param = array(
                          'AccountNo'=>$card_id,
                          'appId'=>$verveAppId,
                          'appKey'=>$verveAppKey
                        );
                    
                        $response = $client->GetIswPrepaidCardAccountBalance($param);
                            
                        $process = json_decode(json_encode($response), true);
                            
                        $availableBalance = $process['GetIswPrepaidCardAccountBalanceResult']['JsonData'];
                            
                        $decodeProcess = json_decode($availableBalance, true);
                        
                        $availbal = $decodeProcess['availableBalance'] / 100;
                    ?>

                    <option value="<?php echo $get_search['id']; ?>"><?php echo $get_search['name'].' '.$get_search['lname']; ?><?php echo ($card_id === "NULL") ? "" : " | Card Bal: ".$icurrency.number_format($availbal,2,'.',','); ?></option>
                 
                    <?php } ?>
                </select>
			</div>
			</div>
			
		<!--<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Currency</label>
                  <div class="col-sm-10">
				<select name="currency_type" class="form-control" required>
					<option value='' selected='selected'>Select Currency</option>
					<option value="566">NGN (566)</option>
					<option value="840">USD (840)</option>
					<option value="826">GBP (826)</option>
					<option value="936">GHS (936)</option>
					<option value="404">KES (404)</option>
					<option value="646">RWF (646)</option>
				</select>
			</div>
			</div>-->
			  
			<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Amount</label>
                  <div class="col-sm-10">
                  <input name="amount" type="text" class="form-control" placeholder="Enter the amount to prefund the card with" required>
                  <span style="color: orange;"> <b>Here, you need to enter the Amount to Prefund the Card with.</span>
                  </div>
                  </div>

<?php
}
?>