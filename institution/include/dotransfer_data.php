<div class="box">
	       <div class="box-body">
			<div class="panel panel-success">
            <div class="panel-heading bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">
            <h3 class="panel-title"> <a href="manageAccount.php?id=<?php echo $_SESSION['tid']; ?>&&idm=<?php echo $_GET['idm']; ?>&&mid=OTIy&&tab=tab_1"><button type="button" class="btn btn-flat bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>"><i class="fa fa-mail-reply-all"></i>&nbsp;Back</button></a> <a href="dotransfer.php?id=<?php echo $_SESSION['tid']; ?>&&idm=<?php echo $_GET['idm']; ?>&&mid=OTIy"><button type="button" class="btn btn-flat bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>"><i class="fa fa-refresh"></i>&nbsp;Refresh</button></a> 
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

<?php
if(isset($_POST['dotransfer']))
{

    $sourceAccountId = $_GET['idm'];
    $ReqReference = date("yd").time().uniqid();
    $TxtReference = date("ys").substr((uniqid(rand(),1)),3,6).uniqid();
    $recipientAcctNo =  mysqli_real_escape_string($link, $_POST['account_number']);
    $recipientBankCode =  mysqli_real_escape_string($link, $_POST['bank_code']);
	$b_name = mysqli_real_escape_string($link, $_POST['b_name']);
	$amount = preg_replace('/[^0-9.]/', '', mysqli_real_escape_string($link, $_POST['amt_totransfer']));
    $narration =  mysqli_real_escape_string($link, $_POST['reasons']);

    $searchBank = mysqli_query($link, "SELECT * FROM bank_account WHERE id = '$sourceAccountId'");
    $fetchBank = mysqli_fetch_array($searchBank);
    $phoneNumber = $fetchBank['phoneNumber'];
    $customerid = $fetchBank['customerid'];
    $firstName = $fetchBank['firstName'];
    $surName = $fetchBank['surName'];
    $customerRef = $fetchBank['reference'];
    $customerEmail = $fetchBank['customerEmail'];
    $sourceAcctNo = $fetchBank['account_number'];
    $balanceLeft = $fetchBank['balance'];
    $currencyCode = $fetchBank['currency_code'];
    $sourceBank = $fetchBank['bank_name'];
	$currenctdate = date("Y-m-d h:i:s");
    
    $otpChecker = "otp";
    $tracekey = substr((uniqid(rand(),1)),3,6);
    
    $query = mysqli_query($link, "SELECT * FROM systemset") or die (mysqli_error($link));
    $r = mysqli_fetch_object($query);
    $transfer_charges = ($bank_transferCharges == "") ? $r->transfer_charges : $bank_transferCharges;
	//New AMount + Charges
    $amountWithCharges = $amount + $transfer_charges;
	
    if($amount <= 0){

        echo "<div class='alert bg-orange'>Sorry! Invalid amount entered!!</div>";

    }
	elseif($balanceLeft < $amountWithCharges){
	    
	    echo "<div class='alert bg-orange'>Oops! You have insufficient fund in your Wallet!!</div>";
      
    }
    elseif($amount > $itransferLimitPerTrans){

        echo "<div class='alert bg-orange'>Sorry! You can not transfer more than ".$icurrency.number_format($itransferLimitPerTrans,2,'.',',')." at once!!</div>";

    }
    elseif($imyDailyTransferLimit == $itransferLimitPerDay || (($amount + $imyDailyTransferLimit) > $itransferLimitPerDay)){

        echo "<div class='alert bg-orange'>Oops! You have reached your daily limit of ".$icurrency.number_format($itransferLimitPerDay,2,'.',',')."</div>";

    }
    elseif($sourceAcctNo === "$account_number"){
    
        mysqli_query($link, "UPDATE user SET status = 'Suspended' WHERE id = '$iuid'");
        mysqli_query($link, "INSERT INTO wallet_case VALUES(null,'$institution_id','$isbranchid','System','$iuid','Suspended','Frudulent Act Detected','$currenctdate')");
      
        echo "<div class='alert bg-orange'>Oops! Request Failed!!</div>";
      
    }
	else{

        //Data Parser (array size = 17)
        $mydata = $ReqReference."|".$TxtReference."|".$recipientBankCode."|".$recipientAcctNo."|".$b_name."|".$amount."|".$amountWithCharges."|".$narration."|".$sourceAcctNo."|".$itransfer_balance."|".$balanceLeft."|".$firstName."|".$surName."|".$customerRef."|".$customerEmail."|".$phoneNumber."|".$currencyCode."|".$customerid."|".$sourceBank;
        require_once "../config/nipBankTransfer_class.php";
        
        $processResult = $new->sunTrustNIPBankTransfer($link,$ReqReference,$sourceAcctNo,$TxtReference,$narration,$amountWithCharges,$customerRef,$firstName,$surName,$customerEmail,$phoneNumber,$recipientAcctNo,$recipientBankCode,$onePipeSKey,$onePipeApiKey);
        $processAccount = json_decode($processResult, true);
        
        $key = base64_encode($mydata);
        
        $getStatus = $processAccount['status'];

        if($getStatus == "WaitingForOTP"){
            
            mysqli_query($link, "INSERT INTO otp_confirmation VALUES(null,'$iuid','$tracekey','$mydata','Pending','$currenctdate')");
            mysqli_query($link, "INSERT INTO api_txtwaitinglist VALUE(null,'$iuid','$TxtReference','$mydata','$getStatus','$currenctdate')");

            echo "<div align='center'><img src='../image/checkmark.gif'><p style='color: #38A1F3;'>".$processAccount['message']."</p></div>";
            echo '<meta http-equiv="refresh" content="10;url=transfer_fund.php?id='.$_SESSION['tid'].'&&mid=OTIy&&idm='.$_GET['idm'].'&&tab=tab_1&&trace='.$tracekey.'&&key='.$key.'&&'.$otpChecker.'">';

        }
        else{
            echo "<div class='alert bg-orange'>".$processAccount['message']."</div>";
        }
	    
	}
	
}
?>


<?php
if (isset($_POST['confirm']))
{
    $trace = $_GET['trace'];
    $otp = mysqli_real_escape_string($link, $_POST['otp']);
    $key = base64_decode($_GET['key']);
    
	$verify_otp = mysqli_query($link, "SELECT * FROM otp_confirmation WHERE otp_code = '$trace' AND userid = '$iuid' AND status = 'Pending' AND data = '$key'");
	$fetch_data = mysqli_fetch_array($verify_otp);
	$otpnum = mysqli_num_rows($verify_otp);
	
	if($otpnum == 0){
	
	    echo "<div class='alert bg-orange'>Opps!...Invalid Credential!!</div>";
	
	}else{
	    
	    $concat = $fetch_data['data'];
    
        $datetime = $fetch_data['datetime'];
                                
        $parameter = (explode('|',$concat));
        
        $ReqReference = date("yd").time();
        $TxtReference = $parameter[1];
        $recipientBankCode = $parameter[2];
	    $recipientAcctNo = $parameter[3];
	    $accountName = $parameter[4];
	    $amountWithNoCharges = $parameter[5];
	    $amountWithCharges = $parameter[6];
        $narration = $parameter[7];
        $sourceAcctNo = $parameter[8];
        $defaultCustomerBal = $parameter[9];
        $sourceBalanceLeft = $parameter[10];
        $firstName = $parameter[11];
        $surName = $parameter[12];
        $customerRef = $parameter[13];
        $customerEmail = $parameter[14];
        $phoneNumber = $parameter[15];
        $currencyCode = $parameter[16];
        $customerid = $parameter[17];
        $sourceBank = $parameter[18];
        $originatorName = $accountName;
        $calcCharges = $amountWithCharges - $amountWithNoCharges;
        $senderEmail = $customerEmail;
        $senderName = $surName.' '.$firstName;
        $merchantName = $inst_name;
        $remainingBal = $sourceBalanceLeft - $amountWithCharges;
        $currenctdate = date("Y-m-d h:i:s");
        $narration .= " | Transfer fund to ".$recipientBankCode." | ".$recipientAcctNo." | ".$accountName;

        require_once "../config/nipBankTransfer_class.php";

        $stotp_generate = $new->sunTrustOTPFTConfirmation($otp,$ReqReference,$TxtReference,$onePipeSKey,$onePipeApiKey);
        
        $processFT = json_decode($stotp_generate, true);
            
        $responseCode = $processFT['data']['provider_response_code'];

        if($responseCode == "00"){

            //UPDATE WAITING TXT
            mysqli_query($link, "UPDATE api_txtwaitinglist SET status = '$stotp_generate' WHERE userid = '$iuid' AND refid = '$TxtReference'");
            
            mysqli_query($link, "INSERT INTO bank_account_stmt VALUES(null,'$institution_id','$isbranchid','$iuid','$customerid','$customerRef','$TxtReference','$sourceBank','$sourceAcctNo','$firstName','$surName','$phoneNumber','$customerEmail','debit','$currencyCode','$amountWithCharges','$remainingBal','$narration','Successful','$currenctdate')");
            mysqli_query($link, "DELETE FROM otp_confirmation WHERE userid = '$iuid' AND otp_code = '$trace' AND status = 'Pending'");

            echo "<div class='alert bg-blue'>Fund Transfered Successfully!</div>";
            echo '<meta http-equiv="refresh" content="10;url=dotransfer.php?id='.$_SESSION['tid'].'&&idm='.$_GET['idm'].'&&mid=OTIy">';

        }
        else{
                
            echo "<div class='alert bg-orange'>".$processFT['message']."</div>";
            
        }

    }

}
?>


<?php
if(!(isset($_GET['otp']) || isset($_GET['pin'])))
{
?>

             <div class="box-body">
                 
                <div class="form-group">
                    <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Country</label>
                    <div class="col-sm-6">
                        <select name="country"  class="form-control select2" id="country" onchange="loadbank();" required>
                          <option value="" selected>Please Select Country</option>
                          <option value="NG">Nigeria</option>
                          <!--<option value="GH">Ghana</option>
                          <option value="KE">Kenya</option>
                          <option value="UG">Uganda </option>
                          <option value="TZ">Tanzania</option>-->
                        </select>
                    </div>
                    <label for="" class="col-sm-3 control-label"></label>
                  </div>
                  
                  <div class="form-group">
                    <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Account Number</label>
                    <div class="col-sm-6">
                        <input name="account_number" type="text" id="account_number" onkeydown="loadaccount();" class="form-control" placeholder="Bank Account Number" required>
                    </div>
                    <label for="" class="col-sm-3 control-label"></label>
                  </div>
                  
                  <div class="form-group">
                    <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Bank Name</label>
                    <div class="col-sm-6">
                        <div id="bank_list">------------</div>
                    </div>
                    <label for="" class="col-sm-3 control-label"></label>
                  </div>
      
                  <div class="form-group">
                    <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Beneficiary Name</label>
                    <div class="col-sm-6">
                        <div id="act_numb">------------</div>
                    </div>
                    <label for="" class="col-sm-3 control-label"></label>
                  </div>

                  <div class="form-group">
                    <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Amount</label>
                    <div class="col-sm-6">
                        <input name="amt_totransfer" type="text" class="form-control" placeholder="Enter Amount to Transfer" required>
                        <span style="color: <?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>;">NOTE: Pease do not put comma (,) while entering the Amount to Transfer</span>
                    </div>
                    <label for="" class="col-sm-3 control-label"></label>
                  </div>
                  
                  <div class="form-group">
                    <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Narration</label>
                    <div class="col-sm-6">
                        <textarea name="reasons" class="form-control" rows="2" cols="5" required></textarea>
                    </div>
                    <label for="" class="col-sm-3 control-label"></label>
                  </div>
			
	        </div>
	        
	        <div class="form-group" align="right">
                <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;"></label>
                <div class="col-sm-6">
                    <button name="dotransfer" type="submit" class="btn bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?> btn-flat"><i class="fa fa-save">&nbsp;Initiate Transfer</i></button>
                </div>
                <label for="" class="col-sm-3 control-label"></label>
             </div>

<?php
}
else{
    include("otp_confirmation.php");
}
?>
			 </form>
			 

</div>	
</div>	
</div>
</div>