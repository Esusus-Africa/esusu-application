<div class="box">
	       <div class="box-body">
			<div class="panel panel-success">
            <div class="panel-heading bg-blue">
            <h3 class="panel-title"> <a href="mywallet.php?id=<?php echo $_SESSION['tid']; ?>&&mid=NDA0&&tab=tab_1"><button type="button" class="btn btn-flat bg-orange"><i class="fa fa-mail-reply-all"></i>&nbsp;Back</button></a> <a href="pay_bills.php?id=<?php echo $_SESSION['tid']; ?>&&mid=NDA0"><button type="button" class="btn btn-flat bg-orange"><i class="fa fa-refresh"></i>&nbsp;Refresh</button></a> 
<?php
$my_systemset = mysqli_query($link, "SELECT * FROM systemset");
$my_row1 = mysqli_fetch_object($my_systemset);
$vat_rate = $my_row1->vat_rate;
$transfer_charges = $my_row1->transfer_charges;
?> 
<button type="button" class="btn btn-flat bg-blue" align="left">&nbsp;<b>Transfer Wallet:</b>&nbsp;
<strong class="alert bg-orange">
<?php
echo $aggcurrency.number_format($aggwallet_balance,2,'.',',');
?> 
</strong>
</button>

  
            </h3>
            </div>


             <div class="box-body">

<div class="slideshow-container">
<div class="alert bg-orange" style="font-size:17px;" align="center">
    <p>
        <?php
        //echo $walletafrica_status;
        if($mo_virtualacct_status == "Active" && ($walletafrica_status == "NotActive" || $walletafrica_status == "Active")){
            
            include("../config/monnify_virtualaccount_esusu.php");
            
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
                    <label for="" class="col-sm-3 control-label" style="color:blue;">Mobile Telcos:</label>
                    <div class="col-sm-6">
                        <select name="telco" class="form-control" required style="width:100%">
                            <option value="" selected="selected">Select Telco...</option>
                            <option value="mtn">MTN</option>
                            <option value="airtel">AIRTEL</option>
                            <option value="9mobile">9MOBILE</option>
                            <option value="glo">GLO</option>
                        </select>
                    </div>
                    <label for="" class="col-sm-3 control-label" align="left"></label>
                  </div>
                 
                 <div class="form-group">
                    <label for="" class="col-sm-3 control-label" style="color:blue;">Phone Number:</label>
                    <div class="col-sm-6">
                        <input name="myphone" type="text" class="form-control" placeholder="Enter Phone Number" required>
                    </div>
                    <label for="" class="col-sm-3 control-label" align="left"></label>
                  </div>

                  <div class="form-group">
                    <label for="" class="col-sm-3 control-label" style="color:blue;">Amount:</label>
                    <div class="col-sm-6">
                        <input name="amount" type="text" class="form-control" placeholder="Enter Amount" required>
                    </div>
                    <label for="" class="col-sm-3 control-label" align="left"></label>
                  </div>
                  
                  <div class="form-group">
                    <label for="" class="col-sm-3 control-label" style="color:blue;">Transaction Pin:</label>
                    <div class="col-sm-6">
                        <input name="epin" type="password" inputmode="numeric" pattern="[0-9]*" class="form-control" placeholder="Transaction Pin" maxlength="4" required>
                    </div>
                    <label for="" class="col-sm-3 control-label"></label>
                    </div>
                
                </div>
                
                <div class="form-group" align="right">
                    <label for="" class="col-sm-3 control-label" style="color:blue;"></label>
                    <div class="col-sm-6">
                        <button name="PayBill1" type="submit" class="btn bg-blue btn-flat"><i class="fa fa-mobile">&nbsp;Buy Airtime</i></button>
                    </div>
                    <label for="" class="col-sm-3 control-label"></label>
                </div>

<?php
if(isset($_POST['PayBill1']))
{
    $curl = curl_init();
    
    $reference = uniqid().time();
    $amount_recharge = preg_replace('/[^0-9.]/', '', mysqli_real_escape_string($link, $_POST['amount']));
    $phone = mysqli_real_escape_string($link, $_POST['myphone']);
    $operator = mysqli_real_escape_string($link, $_POST['telco']);
    $epin =  mysqli_real_escape_string($link, $_POST['epin']);

    if($amount_recharge <= 0){
	    
	    echo "<div class='alert bg-orange'>Invalid amount entered!!</div>";
		
	}elseif($gactive_status == "Suspended"){
        
        echo "<div class='alert bg-orange'>Oops! You are unable to make transfer at the moment!!</div>";
        
    }elseif($aggwallet_balance < $amount_recharge){
	    
	    echo "<div class='alert bg-orange'>Insufficient Fund in your Wallet!!</div>";
		
	}elseif($epin != $control_pin || $epin == ""){
	    
	    echo "<div class='alert bg-orange'>Invalid Transaction Pin!</div>";
	    
    }elseif($amount_recharge > $aglobal_airtimeLimitPerTrans){

        echo "<div class='alert bg-orange'>Sorry! You cannot transact more than ".$aggcurrency.number_format($aglobal_airtimeLimitPerTrans,2,'.',',')." at once</div>";

    }elseif($amyDailyAirtimeData == $aglobalDailyAirtime_DataLimit || (($amount_recharge + $amyDailyAirtimeData) > $aglobalDailyAirtime_DataLimit)){

        echo "<div class='alert bg-orange'>Oops! You have reached your daily limit of ".$aggcurrency.number_format($aglobalDailyAirtime_DataLimit,2,'.',',')."</div>";

    }else{

        $commission = $amount_recharge * $airtimeDataCommission;
        $amount = $amount_recharge - $commission;

        $transactionDateTime = date("Y-m-d h:i:s");
        $dataToProcess = $reference."|".$amount_recharge."|".$operator."|".$phone."|".$aggwallet_balance;
        //insert txt waiting list
        $mytxtstatus = 'RUPending';
        ($aggwallet_balance < $amount_recharge) ? "" : mysqli_query($link, "INSERT INTO api_txtwaitinglist VALUE(null,'$aggr_id','$reference','$dataToProcess','$mytxtstatus','$transactionDateTime')");
    
        //Get my wallet balance after debiting
        $senderBalance2 = $aggwallet_balance - $amount;
        ($aggwallet_balance < $amount_recharge) ? "" : mysqli_query($link, "UPDATE user SET transfer_balance = '$senderBalance2' WHERE id = '$aggr_id'");

        $search_restapi1 = mysqli_query($link, "SELECT * FROM restful_apisetup WHERE api_name = 'rubies_ctairtimepurchase'");
        $fetch_restapi1 = mysqli_fetch_object($search_restapi1);
        $api_url = $fetch_restapi1->api_url;

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
                'reference'=>$reference,
                'amount'=>$amount_recharge,
                'mobilenumber'=>$phone,
                'telco'=>$operator
            ]),
            CURLOPT_HTTPHEADER => array(
            "Authorization: ".$rubbiesSecKey,
            "Content-Type: application/json"
            ),
        ));
                        
        ($aggwallet_balance < $amount_recharge) ? "" : $response = curl_exec($curl);
        ($aggwallet_balance < $amount_recharge) ? "" : $rubbies_generate = json_decode($response, true);

        if($rubbies_generate['responsecode'] == "00" && $aggwallet_balance >= $amount_recharge){

            $transaction_id = $rubbies_generate['reference'];
            $datetime = date("Y-m-d h:i:s");

            //UPDATE WAITING TXT
            mysqli_query($link, "UPDATE api_txtwaitinglist SET status = '$response' WHERE userid = '$aggr_id' AND refid = '$transaction_id' AND status = '$mytxtstatus'");
        
        	$insert = mysqli_query($link, "INSERT INTO wallet_history VALUES(null,'$aggmerchant','$transaction_id','$phone','','$amount','Debit','$aggcurrency','Airtime - WEB','Airtime Topup for $operator with refid: $transaction_id via WEB','successful','$datetime','$aggr_id','$senderBalance2','')");
                    
            $insert = mysqli_query($link, "INSERT INTO wallet_history VALUES(null,'$aggmerchant','$transaction_id','$phone','$commission','','Credit','$aggcurrency','Commission - WEB','Airtime Topup Commission for $operator with refid: $transaction_id via WEB','successful','$datetime','$aggr_id','$senderBalance2','')");
        		
		    echo "<script>alert('Airtime Purchased Successfully!'); </script>";
		    echo "<script>window.location='pay_bills.php?id=".$_SESSION['tid']."&&mid=NDA0'; </script>";

        }else{

            $myWaitingList = mysqli_query($link, "SELECT * FROM api_txtwaitinglist WHERE userid = '$aggr_id' AND refid = '$reference' AND status = '$mytxtstatus'");
            $fetchMyWaitingList = mysqli_fetch_array($myWaitingList);
            $myWaitingData = $fetchMyWaitingList['mydata'];

            $myParameter = (explode('|',$myWaitingData));
            $defaultBalance = $myParameter[4];
                
            //Reverse Customer Wallet
            mysqli_query($link, "UPDATE user SET transfer_balance = '$defaultBalance' WHERE id = '$aggr_id' AND created_by = '$aggmerchant'");
            
            //UPDATE WAITING TXT
            mysqli_query($link, "UPDATE api_txtwaitinglist SET status = '$response' WHERE userid = '$aggr_id' AND refid = '$reference' AND status = '$mytxtstatus'");
		    
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