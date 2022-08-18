<div class="row">
	      <section class="content">
	          
       <div class="box box-default">
           <div class="box-body">
             <div class="table-responsive"> 
            <div class="box-body">

<button type="button" class="btn btn-flat bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>" align="left" disabled>&nbsp;<b>Super Wallet:</b>&nbsp;
    <strong class="alert bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">
    <?php
    echo "<span id='wallet_balance'>".$vcurrency.number_format($vwallet_balance,2,'.',',')."</span>";
    ?> 
    </strong>
</button>

<button type="button" class="btn btn-flat bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>" align="left" disabled>&nbsp;<b>Transfer Wallet:</b>&nbsp;
    <strong class="alert bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">
    <?php
    echo $vcurrency.number_format($vtransfer_balance,2,'.',',');
    ?> 
    </strong>
</button>


<?php
$search_memset = mysqli_query($link, "SELECT * FROM member_settings WHERE companyid = '$vcreated_by'");
$fetch_memset = mysqli_fetch_array($search_memset);
$votp_option = $fetch_memset['otp_option'];
?>

<hr>
<div class="slideshow-container">
<div class="alert bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>" style="font-size:17px;" align="center">
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

<a class="myprev" onclick="plusSlides(-1)">&#10094;</a>
<a class="mynext" onclick="plusSlides(1)">&#10095;</a>
</div>
</hr>


             <div class="box-body">
                 
<!--- DATA BUNDLE FORM START HERE -->			 

			 <form class="form-horizontal" method="post" enctype="multipart/form-data">

             <div class="box-body">

                <div class="form-group">
                    <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Phone Number:</label>
                    <div class="col-sm-6">
                        <input name="checkphone" type="text" class="form-control" id="phone">
                    </div>
                    <label for="" class="col-sm-3 control-label" align="left"></label>
                  </div>
                  
                  <div class="form-group">
                    <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;"></label>
                    <div class="col-sm-6">
                        <button name="Verifybill1" type="submit" onclick="myFunction()" class="btn bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?> btn-flat"><i class="fa fa-search">&nbsp;Verify</i></button>
                    </div>
                    <label for="" class="col-sm-3 control-label" align="left"></label>
                  </div>

                  <?php
                if(isset($_POST['Verifybill1'])){

                    $checkphone = preg_replace("/[^0-9]/", "", mysqli_real_escape_string($link, $_POST['checkphone']));

                    $search_restapi = mysqli_query($link, "SELECT * FROM restful_apisetup WHERE api_name = 'primeairtime_baseUrl'");
                    $fetch_restapi = mysqli_fetch_object($search_restapi);
                    $api_url = $fetch_restapi->api_url.'/api/datatopup/info/'.$checkphone;
                    
                    $curl = curl_init();
                    curl_setopt_array($curl, array(
                    CURLOPT_URL => $api_url,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => "",
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => false,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => "GET",
                    CURLOPT_HTTPHEADER => [
                        "Content-Type: application/json",
                        "Authorization: Bearer ".$accessToken            
                    ],
                    ));
                    
                    $airtime_response = curl_exec($curl);
                    $getAirtime = json_decode($airtime_response, true);

                    echo '<div class="form-group">
                            <label for="" class="col-sm-3 control-label" style="color:'.(($myaltrow['theme_color'] == '') ? "blue" : $myaltrow['theme_color']).';">Phone Number:</label>
                            <div class="col-sm-6">
                                <input name="myphone" type="text" class="form-control" value="'.$getAirtime['opts']['msisdn'].'" placeholder="Enter Phone Number" readonly>
                            </div>
                            <label for="" class="col-sm-3 control-label" align="left"></label>
                        </div>';

                    echo '<div class="form-group">
                            <label for="" class="col-sm-3 control-label" style="color:'.(($myaltrow['theme_color'] == '') ? "blue" : $myaltrow['theme_color']).';">Phone Number:</label>
                            <div class="col-sm-6">
                                <input name="telco" type="text" class="form-control" value="'.$getAirtime['opts']['operator'].'" readonly>
                            </div>
                            <label for="" class="col-sm-3 control-label" align="left"></label>
                        </div>';

                    echo '<div class="form-group">
                            <label for="" class="col-sm-3 control-label" style="color:'.(($myaltrow['theme_color'] == '') ? "blue" : $myaltrow['theme_color']).';">Data Products:</label>
                            <div class="col-sm-6">
                                <select name="dataproduct" class="form-control select2" id="databundle" required style="width:100%">
                                    <option value="" selected="selected">Select Data Product</option>';
                    
                                foreach($getAirtime['products'] as $key){

                                    echo '<option value="'.$key['product_id'].','.$key['topup_value'].','.$getAirtime['opts']['operator'].'">'.$getAirtime['opts']['operator'].' '.$key['data_amount'].'MB - '.$key['validity'].'</option>';
                                    
                                }

                    echo '</select>
                                </div>
                                <label for="" class="col-sm-3 control-label" align="left"></label>
                            </div>';

                }
                ?>

                  <span id='ShowValueFrank'></span>
                  <span id='ShowValueFrank'></span>
                  
                  <div class="form-group">
                    <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Transaction Pin:</label>
                    <div class="col-sm-6">
                        <input name="epin" type="password" inputmode="numeric" pattern="[0-9]*" class="form-control" placeholder="Transaction Pin" maxlength="4">
                    </div>
                    <label for="" class="col-sm-3 control-label"></label>
                    </div>
                
                </div>
                
                <div class="form-group" align="right">
                    <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;"></label>
                    <div class="col-sm-6">
                        <button name="PayBill1" type="submit" class="btn bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?> btn-flat"><i class="fa fa-mobile">&nbsp;Buy Airtime</i></button>
                    </div>
                    <label for="" class="col-sm-3 control-label"></label>
                </div>


<?php
if(isset($_POST['PayBill1']))
{
    $curl = curl_init();
    
    $reference = uniqid().time();
    $pcode = mysqli_real_escape_string($link, $_POST['pcode']);
    $amount_recharge = preg_replace('/[^0-9.]/', '', mysqli_real_escape_string($link, $_POST['amount']));
    $phone = preg_replace("/[^0-9]/", "", mysqli_real_escape_string($link, $_POST['myphone']));
    $operator = mysqli_real_escape_string($link, $_POST['telco']);
    $epin =  mysqli_real_escape_string($link, $_POST['epin']);

    if($amount_recharge <= 0){
	    
	    echo "<div class='alert bg-orange'>Invalid amount entered!!</div>";
		
	}elseif($vactive_status == "Suspended"){
        
        echo "<div class='alert bg-orange'>Oops! You are unable to purchase data at the moment!!</div>";
        
    }elseif($vtransfer_balance < $amount_recharge){
	    
	    echo "<div class='alert bg-orange'>Insufficient Fund in your Wallet!!</div>";
		
	}elseif($epin != $myvepin || $epin == ""){
	    
	    echo "<div class='alert bg-orange'>Invalid Transaction Pin!</div>";
	    
    }elseif($amount_recharge > $vglobal_airtimeLimitPerTrans){

        echo "<div class='alert bg-orange'>Sorry! You cannot transact more than ".$vcurrency.number_format($vglobal_airtimeLimitPerTrans,2,'.',',')." at once</div>";

    }elseif($vmyDailyAirtimeData == $vglobalDailyAirtime_DataLimit || (($amount_recharge + $vmyDailyAirtimeData) > $vglobalDailyAirtime_DataLimit)){

        echo "<div class='alert bg-orange'>Oops! You have reached your daily limit of ".$vcurrency.number_format($vglobalDailyAirtime_DataLimit,2,'.',',')."</div>";

    }else{

        $commission = $amount_recharge * $fetchsys_config['airtimedata_commission'];
		$amount = $amount_recharge - $commission;

        $transactionDateTime = date("Y-m-d h:i:s");
        $dataToProcess = $reference."|".$amount_recharge."|".$operator."|".$phone."|".$vtransfer_balance."|".$pcode;
        //insert txt waiting list
        $mytxtstatus = 'PAPending';
        ($vtransfer_balance < $amount_recharge) ? "" : mysqli_query($link, "INSERT INTO api_txtwaitinglist VALUE(null,'$vuid','$reference','$dataToProcess','$mytxtstatus','$transactionDateTime')");
        
        //Get my wallet balance after debiting
        $senderBalance2 = $vtransfer_balance - $amount;
        ($vtransfer_balance < $amount_recharge) ? "" : mysqli_query($link, "UPDATE user SET transfer_balance = '$senderBalance2' WHERE id = '$vuid'");

        $search_restapi1 = mysqli_query($link, "SELECT * FROM restful_apisetup WHERE api_name = 'primeairtime_baseUrl'");
        $fetch_restapi1 = mysqli_fetch_object($search_restapi1);
        $api_url = $fetch_restapi1->api_url.'/api/datatopup/exec/'.$phone;

        curl_setopt_array($curl, array(
            CURLOPT_URL => $api_url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode([
                'product_id'=>$pcode,
                'denomination'=>$amount_recharge,
                'send_sms'=>false,
                'sms_text'=>'',
                'customer_reference'=>$reference
            ]),
            CURLOPT_HTTPHEADER => array(
                "Content-Type: application/json",
                "Authorization: Bearer ".$accessToken
            ),
        ));
                        
        ($vtransfer_balance < $amount_recharge) ? "" : $response = curl_exec($curl);
        ($vtransfer_balance < $amount_recharge) ? "" : $prime_generate = json_decode($response, true);

        $transaction_id = $prime_generate['reference'];
        $datetime = date("Y-m-d h:i:s");

        if(($prime_generate['status'] == "201" || $prime_generate['status'] == "200") && $vtransfer_balance >= $amount_recharge){

            //UPDATE WAITING TXT
            mysqli_query($link, "UPDATE api_txtwaitinglist SET status = '$response' WHERE userid = '$vuid' AND refid = '$transaction_id' AND status = '$mytxtstatus'");
                
        	$insert = mysqli_query($link, "INSERT INTO wallet_history VALUES(null,'$vcreated_by','$transaction_id','$phone','','$amount','Debit','$vcurrency','Databundle - WEB','$operator Databundle Purchase for $pcode with refid: $transaction_id via WEB','successful','$datetime','$vuid','$senderBalance2','')");
        	
        	$insert = mysqli_query($link, "INSERT INTO wallet_history VALUES(null,'$vcreated_by','$transaction_id','$phone','$commission','','Credit','$vcurrency','Commission - WEB','$operator Databundle Purchase Commission for $pcode with refid: $transaction_id via WEB','successful','$datetime','$vuid','$senderBalance2','')");
        		
		    echo "<script>alert('Databundle Purchased Successfully!'); </script>";
		    echo "<script>window.location='pay_billsdata.php?id=".$_SESSION['tid']."&&mid=NDA0'; </script>";

        }elseif($prime_generate['status'] == "208"){

            $insert = mysqli_query($link, "INSERT INTO wallet_history VALUES(null,'$vcreated_by','$transaction_id','$phone','','$amount','Debit','$vcurrency','Databundle - WEB','$operator Databundle Purchase for $pcode with refid: $transaction_id via WEB','PAPending','$datetime','$vuid','$senderBalance2','')");
        	
        	$insert = mysqli_query($link, "INSERT INTO wallet_history VALUES(null,'$vcreated_by','$transaction_id','$phone','$commission','','Credit','$vcurrency','Commission - WEB','$operator Databundle Purchase Commission for $pcode with refid: $transaction_id via WEB','PAPending','$datetime','$vuid','$senderBalance2','')");

            echo "<script>alert('Databundle Purchase in Proceessing...!'); </script>";
            echo "<script>window.location='pay_billsdata.php?tid=".$_SESSION['tid']."&&acn=".$acctno."&&mid=NDA4'; </script>";
            
        }else{

            $myWaitingList = mysqli_query($link, "SELECT * FROM api_txtwaitinglist WHERE userid = '$vuid' AND refid = '$reference' AND status = '$mytxtstatus'");
            $fetchMyWaitingList = mysqli_fetch_array($myWaitingList);
            $myWaitingData = $fetchMyWaitingList['mydata'];

            $myParameter = (explode('|',$myWaitingData));
            $defaultBalance = $myParameter[4];
            
            //Reverse Customer Wallet
            mysqli_query($link, "UPDATE user SET transfer_balance = '$defaultBalance' WHERE id = '$vuid' AND created_by = '$vcreated_by'");
            
            //UPDATE WAITING TXT
            mysqli_query($link, "UPDATE api_txtwaitinglist SET status = '$response' WHERE userid = '$vuid' AND refid = '$reference' AND status = '$mytxtstatus'");
		    
		    echo "<script>alert('Oops! Network Error, please try again later!!'); </script>";
		    
		}

    }

}
?>
			 </form>

</div>	
</div>
</div>
</div>
</div>
</section>
</div>