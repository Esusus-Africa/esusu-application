<div class="box">
	       <div class="box-body">
			<div class="panel panel-success">
            <div class="panel-heading bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">
            <h3 class="panel-title"> <a href="mywallet.php?id=<?php echo $_SESSION['tid']; ?>&&mid=NDA0&&tab=tab_1"><button type="button" class="btn btn-flat bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>"><i class="fa fa-mail-reply-all"></i>&nbsp;Back</button></a> <a href="pay_bills.php?id=<?php echo $_SESSION['tid']; ?>&&mid=NDA0"><button type="button" class="btn btn-flat bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>"><i class="fa fa-refresh"></i>&nbsp;Refresh</button></a> 
<?php
$my_systemset = mysqli_query($link, "SELECT * FROM systemset");
$my_row1 = mysqli_fetch_object($my_systemset);
$vat_rate = $my_row1->vat_rate;
$transfer_charges = $my_row1->transfer_charges;
?> 
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
          
<!--- AIRTIME FORM START HERE -->
          
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
                    $api_url = $fetch_restapi->api_url.'/api/topup/info/'.$checkphone;
                    
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
                    
                    foreach($getAirtime['products'] as $key){
                        
                        echo '<input name="openRangeMin" type="hidden" class="form-control" value="'.$key['openRangeMin'].'">';
                        echo '<input name="product_id" type="hidden" class="form-control" value="'.$key['product_id'].'">';
                        echo '<input name="currency" type="hidden" class="form-control" value="'.$key['currency'].'">';
                        
                    }

                    echo '<div class="form-group">
                            <label for="" class="col-sm-3 control-label" style="color:'.(($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']).';">Amount:</label>
                            <div class="col-sm-6">
                                <input name="amount" type="text" class="form-control" placeholder="Enter Amount" required>
                            </div>
                            <label for="" class="col-sm-3 control-label" align="left"></label>
                        </div>';

                }
                ?>
                  
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
    $amount_recharge = preg_replace('/[^0-9.]/', '', mysqli_real_escape_string($link, $_POST['amount']));
    $phone = preg_replace("/[^0-9]/", "", mysqli_real_escape_string($link, $_POST['myphone']));
    $operator = mysqli_real_escape_string($link, $_POST['telco']);
    $product_id = mysqli_real_escape_string($link, $_POST['product_id']);
    $openRangeMin = mysqli_real_escape_string($link, $_POST['openRangeMin']);
    $mycurrency = mysqli_real_escape_string($link, $_POST['currency']);
    $epin =  mysqli_real_escape_string($link, $_POST['epin']);

    if($amount_recharge <= 0){
	    
        echo "<div class='alert bg-orange'>Invalid amount entered!!</div>";
	    		
    }elseif($amount_recharge < $openRangeMin){

        echo "<div class='alert bg-orange'>Sorry! You can not purchase below $mycurrency".$openRangeMin."!!</div>";

    }elseif($active_status == "Suspended"){
        
        echo "<div class='alert bg-orange'>Oops! You are unable to purchase airtime at the moment!!</div>";
        
    }elseif($itransfer_balance < $amount_recharge){
	    
	    echo "<div class='alert bg-orange'>Insufficient Fund in your Wallet!!</div>";
		
	}elseif($epin != $myiepin || $epin == ""){
	    
        echo "<div class='alert bg-orange'>Invalid Transaction Pin!</div>";
	    
    }elseif($amount_recharge > $iglobal_airtimeLimitPerTrans){

        echo "<div class='alert bg-orange'>Sorry! You cannot transact more than ".$icurrency.number_format($iglobal_airtimeLimitPerTrans,2,'.',',')." at once</div>";

    }elseif($imyDailyAirtimeData == $iglobalDailyAirtime_DataLimit || (($amount_recharge + $imyDailyAirtimeData) > $iglobalDailyAirtime_DataLimit)){

        echo "<div class='alert bg-orange'>Oops! You have reached your daily limit of ".$icurrency.number_format($iglobalDailyAirtime_DataLimit,2,'.',',')."</div>";

    }else{

        $commission = $amount_recharge * $iairtimeDataCommission;
        $amount = $amount_recharge - $commission;

        $transactionDateTime = date("Y-m-d h:i:s");
        $dataToProcess = $reference."|".$amount_recharge."|".$product_id."|".$phone."|".$itransfer_balance."|".$product_id;
        //insert txt waiting list
        $mytxtstatus = 'PAPending';
        ($itransfer_balance < $amount_recharge) ? "" : mysqli_query($link, "INSERT INTO api_txtwaitinglist VALUE(null,'$iuid','$reference','$dataToProcess','$mytxtstatus','$transactionDateTime')");
    
        //Get my wallet balance after debiting
        $senderBalance2 = $itransfer_balance - $amount;
        ($itransfer_balance < $amount_recharge) ? "" : mysqli_query($link, "UPDATE user SET transfer_balance = '$senderBalance2' WHERE id = '$iuid'");

        $search_restapi1 = mysqli_query($link, "SELECT * FROM restful_apisetup WHERE api_name = 'primeairtime_baseUrl'");
        $fetch_restapi1 = mysqli_fetch_object($search_restapi1);
        $api_url = $fetch_restapi1->api_url.'/api/topup/exec/'.$phone;

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
                'product_id'=>$product_id,
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
                        
        ($itransfer_balance < $amount_recharge) ? "" : $response = curl_exec($curl);
        ($itransfer_balance < $amount_recharge) ? "" : $prime_generate = json_decode($response, true);

        $transaction_id = $prime_generate['customer_reference'];
        $datetime = date("Y-m-d h:i:s");

        if(($prime_generate['status'] == "201" || $prime_generate['status'] == "200") && $itransfer_balance >= $amount_recharge){

            //UPDATE WAITING TXT
            mysqli_query($link, "UPDATE api_txtwaitinglist SET status = '$response' WHERE userid = '$iuid' AND refid = '$transaction_id' AND status = '$mytxtstatus'");
        
        	$insert = mysqli_query($link, "INSERT INTO wallet_history VALUES(null,'$institution_id','$transaction_id','$phone','','$amount','Debit','$icurrency','Airtime - WEB','Airtime Topup for $operator with refid: $transaction_id via WEB','successful','$datetime','$iuid','$senderBalance2','')");
        	
        	$insert = mysqli_query($link, "INSERT INTO wallet_history VALUES(null,'$institution_id','$transaction_id','$phone','$commission','','Credit','$icurrency','Commission - WEB','Airtime Topup Commission for $operator with refid: $transaction_id via WEB','successful','$datetime','$iuid','$senderBalance2','')");
        	
		    echo "<script>alert('Airtime Purchased Successfully!'); </script>";
		    echo "<script>window.location='pay_bills.php?id=".$_SESSION['tid']."&&mid=NDA0'; </script>";

        }elseif($prime_generate['status'] == "208"){

            $insert = mysqli_query($link, "INSERT INTO wallet_history VALUES(null,'$institution_id','$transaction_id','$phone','','$amount','Debit','$icurrency','Airtime - WEB','Airtime Topup for $operator with refid: $transaction_id via WEB','PAPending','$datetime','$iuid','$senderBalance2','')");
        	
        	$insert = mysqli_query($link, "INSERT INTO wallet_history VALUES(null,'$institution_id','$transaction_id','$phone','$commission','','Credit','$icurrency','Commission - WEB','Airtime Topup Commission for $operator with refid: $transaction_id via WEB','PAPending','$datetime','$iuid','$senderBalance2','')");

            echo "<script>alert('Airtime Purchase in Proceessing...!'); </script>";
            echo "<script>window.location='pay_bills.php?id=".$_SESSION['tid']."&&mid=NDA0'; </script>";

        }else{

            $myWaitingList = mysqli_query($link, "SELECT * FROM api_txtwaitinglist WHERE userid = '$iuid' AND refid = '$reference' AND status = '$mytxtstatus'");
            $fetchMyWaitingList = mysqli_fetch_array($myWaitingList);
            $myWaitingData = $fetchMyWaitingList['mydata'];

            $myParameter = (explode('|',$myWaitingData));
            $defaultBalance = $myParameter[4];
            
            //Reverse Customer Wallet
            mysqli_query($link, "UPDATE user SET transfer_balance = '$defaultBalance' WHERE id = '$iuid' AND created_by = '$institution_id'");
            
            //UPDATE WAITING TXT
            mysqli_query($link, "UPDATE api_txtwaitinglist SET status = '$response' WHERE userid = '$iuid' AND refid = '$reference' AND status = '$mytxtstatus'");
		    
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