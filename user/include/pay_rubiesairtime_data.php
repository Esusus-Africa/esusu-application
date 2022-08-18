<div class="box">
         <div class="box-body">
      <div class="panel panel-success">
            <div class="panel-heading bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">
            <h3 class="panel-title"> <a href="mywallet_history.php?tid=<?php echo $_SESSION['tid']; ?>&&acn=<?php echo $_GET['acn']; ?>&&mid=NDA4&&tab=tab_1"><button type="button" class="btn btn-flat bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>"><i class="fa fa-mail-reply-all"></i>&nbsp;Back</button></a> <a href="pay_bills.php?tid=<?php echo $_SESSION['tid']; ?>&&acn=<?php echo $_GET['acn']; ?>&&mid=NDA4"><button type="button" class="btn btn-flat bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>"><i class="fa fa-refresh"></i>&nbsp;Refresh</button></a> 
<button type="button" class="btn btn-flat bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>" align="left" >&nbsp;<b>Super Wallet Bal:</b>&nbsp;
<strong class="alert bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>">
<?php
echo $bbcurrency.number_format($bwallet_balance,2,'.',',');

$my_systemset = mysqli_query($link, "SELECT * FROM systemset");
$my_row1 = mysqli_fetch_object($my_systemset);
$vat_rate = $my_row1->vat_rate;
$transfer_charges = $my_row1->transfer_charges;
?> 
</strong>
  </button>

            </h3>
            </div>

             <div class="box-body">
          
<!--- AIRTIME FORM START HERE -->
          
			 <form class="form-horizontal" method="post" enctype="multipart/form-data">

             <div class="box-body">

             <div class="form-group">
                    <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Mobile Telcos:</label>
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
                    <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Phone Number:</label>
                    <div class="col-sm-6">
                        <input name="myphone" type="text" class="form-control" placeholder="Enter Phone Number" required>
                    </div>
                    <label for="" class="col-sm-3 control-label" align="left"></label>
                  </div>

                  <div class="form-group">
                    <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Amount:</label>
                    <div class="col-sm-6">
                        <input name="amount" type="text" class="form-control" placeholder="Enter Amount" required>
                    </div>
                    <label for="" class="col-sm-3 control-label" align="left"></label>
                  </div>
                  
                  <div class="form-group">
                    <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Transaction Pin:</label>
                    <div class="col-sm-6">
                        <input name="epin" type="password" inputmode="numeric" pattern="[0-9]*" class="form-control" placeholder="Transaction Pin" maxlength="4" required>
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
    $phone = mysqli_real_escape_string($link, $_POST['myphone']);
    $operator = mysqli_real_escape_string($link, $_POST['telco']);
    $epin =  mysqli_real_escape_string($link, $_POST['epin']);

    if($amount_recharge <= 0){
	    
	    echo "<div class='alert bg-orange'>Invalid amount entered!!</div>";
		
	}elseif($bactive_status == "Suspended"){
        
        echo "<div class='alert bg-orange'>Oops! You are unable to make transfer at the moment!!</div>";
        
    }elseif($bwallet_balance < $amount_recharge){
	    
	    echo "<div class='alert bg-orange'>Insufficient Fund in your Wallet!!</div>";
		
	}elseif($epin != $myuepin || $epin == ""){
	    
	    echo "<div class='alert bg-orange'>Invalid Transaction Pin!</div>";
	    
    }elseif($amount_recharge > $bglobal_airtimeLimitPerTrans){

        echo "<div class='alert bg-orange'>Sorry! You cannot transact more than ".$bbcurrency.number_format($bglobal_airtimeLimitPerTrans,2,'.',',')." at once</div>";

    }elseif($bmyDailyAirtimeData == $bglobalDailyAirtime_DataLimit || (($amount_recharge + $bmyDailyAirtimeData) > $bglobalDailyAirtime_DataLimit)){

        echo "<div class='alert bg-orange'>Oops! You have reached your daily limit of ".$bbcurrency.number_format($bglobalDailyAirtime_DataLimit,2,'.',',')."</div>";

    }else{

        $commission = $amount_recharge * $bairtimeDataCommission;
		$amount = $amount_recharge - $commission;

        $transactionDateTime = date("Y-m-d h:i:s");
        $dataToProcess = $reference."|".$amount_recharge."|".$operator."|".$phone."|".$bwallet_balance;
        //insert txt waiting list
        $mytxtstatus = 'RUPending';
        ($bwallet_balance < $amount_recharge) ? "" : mysqli_query($link, "INSERT INTO api_txtwaitinglist VALUE(null,'$acctno','$reference','$dataToProcess','$mytxtstatus','$transactionDateTime')");
        
        //Get my wallet balance after debiting
        $senderBalance2 = $bwallet_balance - $amount;
        ($bwallet_balance < $amount_recharge) ? "" : mysqli_query($link, "UPDATE borrowers SET wallet_balance = '$senderBalance2' WHERE account = '$acctno'");

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
                        
        ($bwallet_balance < $amount_recharge) ? "" : $response = curl_exec($curl);
        ($bwallet_balance < $amount_recharge) ? "" : $rubbies_generate = json_decode($response, true);

        if($rubbies_generate['responsecode'] == "00" && $bwallet_balance >= $amount_recharge){

            $transaction_id = $rubbies_generate['reference'];
            $datetime = date("Y-m-d h:i:s");

            //UPDATE WAITING TXT
            mysqli_query($link, "UPDATE api_txtwaitinglist SET status = '$response' WHERE userid = '$acctno' AND refid = '$transaction_id' AND status = '$mytxtstatus'");
            
        	$insert = mysqli_query($link, "INSERT INTO wallet_history VALUES(null,'$bbranchid','$transaction_id','$phone','','$amount','Debit','$bbcurrency','Airtime - WEB','Airtime Topup for $operator with refid: $transaction_id via WEB','successful','$datetime','$acctno','$senderBalance2','')");
        	
        	$insert = mysqli_query($link, "INSERT INTO wallet_history VALUES(null,'$bbranchid','$transaction_id','$phone','$commission','','Credit','$bbcurrency','Commission - WEB','Airtime Topup Commission for $operator with refid: $transaction_id via WEB','successful','$datetime','$acctno','$senderBalance2','')");
		
		    echo "<script>alert('Airtime Purchased Successfully!'); </script>";
		    echo "<script>window.location='pay_bills.php?tid=".$_SESSION['tid']."&&acn=".$acctno."&&mid=NDA4'; </script>";

        }else{

            $myWaitingList = mysqli_query($link, "SELECT * FROM api_txtwaitinglist WHERE userid = '$acctno' AND refid = '$reference' AND status = '$mytxtstatus'");
            $fetchMyWaitingList = mysqli_fetch_array($myWaitingList);
            $myWaitingData = $fetchMyWaitingList['mydata'];

            $myParameter = (explode('|',$myWaitingData));
            $defaultBalance = $myParameter[4];
            
            //Reverse Customer Wallet
            mysqli_query($link, "UPDATE borrowers SET wallet_balance = '$defaultBalance' WHERE account = '$acctno' AND branchid = '$bbranchid'");
            
            //UPDATE WAITING TXT
            mysqli_query($link, "UPDATE api_txtwaitinglist SET status = '$response' WHERE userid = '$acctno' AND refid = '$reference' AND status = '$mytxtstatus'");
		    
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