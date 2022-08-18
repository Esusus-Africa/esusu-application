<div class="box">
	       <div class="box-body">
			<div class="panel panel-success">
            <div class="panel-heading bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">
            <h3 class="panel-title">
            <button type="button" class="btn btn-flat bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>" align="left">&nbsp;<b>Transfer Wallet:</b>&nbsp;
                <strong class="alert bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>">
                <?php
                echo $icurrency.number_format($itransfer_balance,2,'.',',');
                ?> 
                </strong>
            </button>
            
            </h3>
            </div>



             <div class="box-body">

<div class="slideshow-container">
<div class="alert bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>" style="font-size:17px;" align="center">
    <p>
        <?php
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
<hr>

 <?php
if(isset($_POST['linkcard']))
{
	$cardholder =  mysqli_real_escape_string($link, $_POST['cardholder']);
	$pan = mysqli_real_escape_string($link, $_POST['pan']);
	$validatePan = preg_match("/^([506]{3})([0-9]{1,16})$/", $pan, $match);
	$tpin =  mysqli_real_escape_string($link, $_POST['tpin']);
	$smsfee = $fetchsys_config['fax'];
	
	$search_customerbal = mysqli_query($link, "SELECT * FROM borrowers WHERE account = '$cardholder'");
    $fetch_customernum = mysqli_num_rows($search_customerbal);
    $fetch_customerbal = mysqli_fetch_array($search_customerbal);
    
    $search_agtbal = mysqli_query($link, "SELECT * FROM user WHERE id = '$cardholder'");
    $fetch_agtnum = mysqli_num_rows($search_agtbal);
	$fetch_agtbal = mysqli_fetch_array($search_agtbal);
       
    $cust_email = ($fetch_customernum == 1 && $fetch_agtnum == 0) ? $fetch_customerbal['email'] : $fetch_agtbal['email'];
	$cust_fname = ($fetch_customernum == 1 && $fetch_agtnum == 0) ? $fetch_customerbal['fname'] : $fetch_agtbal['name'];
	$cust_lname = ($fetch_customernum == 1 && $fetch_agtnum == 0) ? $fetch_customerbal['lname'] : $fetch_agtbal['lname'];
	$cust_phone = ($fetch_customernum == 1 && $fetch_agtnum == 0) ? $fetch_customerbal['phone'] : $fetch_agtbal['phone'];
	$bank = ($fetch_customernum == 1 && $fetch_agtnum == 0) ? $fetch_customerbal['card_issurer'] : $fetch_agtbal['card_issurer'];
	
	$search_gateway = mysqli_query($link, "SELECT * FROM sms WHERE smsuser = '' AND status = 'Activated'");
    $fetch_gateway = mysqli_fetch_object($search_gateway);
    $gateway_uname = $fetch_gateway->username;
    $gateway_pass = $fetch_gateway->password;
    $gateway_api = $fetch_gateway->api;
	
	$vervecardLinking_Fee = ($verveCardLinkingFee2 == "") ? $verveCardLinkingFee : $verveCardLinkingFee2;
	
    $totalAmountToCharge = $vervecardLinking_Fee + $verveCardPrefundAmt + $smsfee;
    
    $initiatorBalance = ($card_transferCommission == "0" || $card_transferCommission == "") ? $itransfer_balance : ($itransfer_balance + $card_transferCommission);
	
	$txid = date("dy").time();
	
	if($myiepin != $tpin){
	    
	    echo "<div class='alert bg-orange'>Opps!...Invalid Transaction Pin Entered</div>";
	    
	}
	elseif($totalAmountToCharge > $itransfer_balance){
	    
	    echo "<div class='alert bg-orange'>Sorry!...You did not have sufficient fund in your wallet!!</div>";
	    
	}elseif(!$validatePan){
	    
	    echo "<div class='alert bg-orange'>Opps!..Invalid VerveCard Pan Number Entered!!</div>";
	    
	}
	else{
	    
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
        
        //print_r($param);
        //echo "<br><br>";
        //print_r($process);
        
        if($responseCode == "90000"){
            
            $senderBalance = ($card_transferCommission == "0" || $card_transferCommission == "") ? ($itransfer_balance - $totalAmountToCharge) : (($itransfer_balance + $card_transferCommission) - $totalAmountToCharge);
            $currenctdate = date("Y-m-d H:i:s");
            $maskPan = panNumberMasking($pan);
	        
	        $sms = "$isenderid>>>Dear $cust_lname, This is to notify you that your Verve card with Pan Number: ".$maskPan." has been linked to your account with a prefunded balance of $icurrency".number_format($verveCardPrefundAmt,2,'.',',')."";
            $sms .= "Time ".date('m/d/Y').' '.(date(h) + 1).':'.date('i a')."";
                
            ($fetch_customernum == 1 && $fetch_agtnum == 0) ? $update = mysqli_query($link, "UPDATE borrowers SET card_id = '$pan', card_reg = 'Yes', card_issurer = 'VerveCard' WHERE account = '$cardholder'") or die ("Error: " . mysqli_error($link)) : "";

            ($fetch_customernum == 0 && $fetch_agtnum == 1) ? $update = mysqli_query($link, "UPDATE user SET card_id = '$pan', card_reg = 'Yes', card_issurer = 'VerveCard' WHERE id = '$cardholder'") or die ("Error: " . mysqli_error($link)) : "";
    	
    	    $insert = mysqli_query($link, "INSERT INTO wallet_history VALUE(null,'$institution_id','$txid','$maskPan','','$vervecardLinking_Fee','Debit','$icurrency','VerveCard_Verification','Charges for linking verve card with pan number $maskPan','successful','$currenctdate','$iuid','$senderBalance','')");
    	    
            $insert = mysqli_query($link, "INSERT INTO wallet_history VALUE(null,'$institution_id','$txid','$cardholder','','$verveCardPrefundAmt','Debit','$icurrency','Topup-Prepaid_Card','Prefund Amount for verve card with pan number $maskPan','successful','$currenctdate','$iuid','$senderBalance','')");
            
            ($card_transferCommission == "0" || $card_transferCommission == "") ? "" : $insert = mysqli_query($link, "INSERT INTO wallet_history VALUE(null,'$institution_id','$txid','$cardholder','$card_transferCommission','','Credit','$icurrency','Card_Commission','Prefund Amount for verve card with pan number $maskPan','successful','$currenctdate','$iuid','$senderBalance','')");
    	
    	    $update = mysqli_query($link, "UPDATE user SET transfer_balance = '$senderBalance' WHERE id = '$iuid'") or die ("Error: " . mysqli_error($link));
            
            $sendSMS->smsWithNoCharges($isenderid, $cust_phone, $sms, $txid, $iuid);
            
            echo "<div class='alert bg-blue'>Vervecard linked successfully!!</div>";
            
        }
        elseif($responseCode == "99"){
            
            echo "<div class='alert bg-orange'>Opps!..Access denied, please try again later!!</div>";
            
        }
        else{
            
            echo "<div class='alert bg-blue'>Opps!..Unable to link card, please try again later!!</div>";
            
        }
        	
    }

}
?>           
			 <form class="form-horizontal" method="post" enctype="multipart/form-data">
            
            <p style="color: <?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">CARD LINKING CHARGES: <b><?php echo $icurrency.number_format($transferToCardCharges12,2,'.',','); ?></b></p>
    		<p style="color: <?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">AGENT COMMISSION: <b><?php echo $icurrency.number_format($card_transferCommission,2,'.',','); ?></b></p>
            <hr>
            
            <div class="box-body">
			 
			<div class="form-group">
                <label for="" class="col-sm-3 control-label" style="color:blue;">New Cardholder:</label>
                <div class="col-sm-6">
                    <select name="cardholder"  class="form-control select2" required>
                      <option value="" selected>Select New Cardholder</option>
                        <?php
                        (($individual_customer_records != "1" && $branch_customer_records != "1") || ($individual_wallet != "1" && $branch_wallet != "1")) ? $search = mysqli_query($link, "SELECT * FROM borrowers WHERE branchid = '$institution_id' AND card_reg = 'Yes' AND card_issurer = 'VerveCard' AND card_id = 'NULL'")  : "";
                        (($individual_customer_records === "1" && $branch_customer_records != "1") || ($individual_wallet === "1" && $branch_wallet != "1")) ? $search = mysqli_query($link, "SELECT * FROM borrowers WHERE branchid = '$institution_id'  AND lofficer = '$iuid' AND card_reg = 'Yes' AND card_issurer = 'VerveCard' AND card_id = 'NULL'")  : "";
                        (($individual_customer_records != "1" && $branch_customer_records === "1") || ($individual_wallet != "1" && $branch_wallet === "1")) ? $search = mysqli_query($link, "SELECT * FROM borrowers WHERE branchid = '$institution_id' AND sbranchid = '$isbranchid' AND card_reg = 'Yes' AND card_issurer = 'VerveCard' AND card_id = 'NULL'")  : "";
                        while($get_search = mysqli_fetch_array($search))
                        {
                        ?>
                      <option value="<?php echo $get_search['account']; ?>"><?php echo $get_search['virtual_acctno']; ?> - <?php echo $get_search['fname']; ?> <?php echo $get_search['lname']; ?> <?php echo $get_search['mname']; ?></option>
                        <?php } ?>
                        
                        <?php
                        ($individual_wallet != "1" && $branch_wallet != "1") ? $get3 = mysqli_query($link, "SELECT * FROM user WHERE created_by = '$institution_id' AND card_reg = 'Yes' AND card_issurer = 'VerveCard' AND card_id = 'NULL' ORDER BY userid DESC") or die (mysqli_error($link)) : "";
                        ($individual_wallet === "1" && $branch_wallet != "1") ? $get3 = mysqli_query($link, "SELECT * FROM user WHERE created_by = '$institution_id' AND acctOfficer = '$iuid' AND card_reg = 'Yes' AND card_issurer = 'VerveCard' AND card_id = 'NULL' ORDER BY userid DESC") or die (mysqli_error($link)) : "";
                        ($individual_wallet != "1" && $branch_wallet === "1") ? $get3 = mysqli_query($link, "SELECT * FROM user WHERE created_by = '$institution_id' AND branchid = '$isbranchid' AND card_reg = 'Yes' AND card_issurer = 'VerveCard' AND card_id = 'NULL' ORDER BY userid DESC") or die (mysqli_error($link)) : "";
                        while($get_search = mysqli_fetch_array($get3))
                        {
                        ?>
                      <option value="<?php echo $get_search['id']; ?>"><?php echo $get_search['virtual_acctno']; ?> - <?php echo $get_search['name']; ?> <?php echo $get_search['lname']; ?> <?php echo $get_search['mname']; ?></option>
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