<div class="box">
	       <div class="box-body">
			<div class="panel panel-success">
            <div class="panel-heading bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">
            <h3 class="panel-title"> 
            <?php echo (isset($_GET['ref'])) ? "<a href='pay_bill1?id=".$_SESSION['tid']."&&del=".$_GET['ref']."&&acn=".$acctno."&&mid=NDA4'><button type='button' class='btn btn-flat bg-".(($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color'])."'><i class='fa fa-mail-reply-all'></i>&nbsp;Back</button></a>" : "<a href='mywallet_history?tid=".$_SESSION['tid']."&&acn=".$acctno."&&mid=NDA4&&tab=tab_1'><button type='button' class='btn btn-flat bg-".(($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color'])."'><i class='fa fa-mail-reply-all'></i>&nbsp;Back</button></a>"; ?> 
            <?php echo (isset($_GET['ref'])) ? "<a href='pay_bill1?id=".$_SESSION['tid']."&&ref=".$_GET['ref']."&&acn=".$acctno."&&mid=NDA4'><button type='button' class='btn btn-flat bg-".(($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color'])."'><i class='fa fa-refresh'></i>&nbsp;Refresh</button></a>" : "<a href='pay_bill1?id=".$_SESSION['tid']."&&acn=".$acctno."&&mid=NDA4'><button type='button' class='btn btn-flat bg-".(($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color'])."'><i class='fa fa-refresh'></i>&nbsp;Refresh</button></a>"; ?> 
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
<?php
if(isset($_GET['ref']) == true){
    
    $jsonData=array();
    $myref = $_GET['ref'];
    
    $search_pendingbills = mysqli_query($link, "SELECT * FROM bill_payverification WHERE refid = '$myref'");
    $fetch_pendingbills = mysqli_fetch_object($search_pendingbills);
    
	$search_billconfig = mysqli_query($link, "SELECT * FROM billpayment");
	$fetch_billconfig = mysqli_fetch_object($search_billconfig);
	
	$url = 'https://estoresms.com/bill_payment_processing/v/1/'; //API Url (Do not change)
	//$url = '$fetch_billconfig->api_url'; //API Url (Do not change)
    $token=$fetch_billconfig->token; //Replace with your API token
    $email=$fetch_billconfig->email;  //Replace with your account email address
    $username=$fetch_billconfig->username;  // Replace with your account username
    $categories = $fetch_pendingbills->cat;
    $plan_code = $fetch_pendingbills->plan_name;
    $smart_card = $fetch_pendingbills->scard;

    //Initiate cURL.
    $ch = curl_init($url);
    
    $jsonData['username']=$username;
    
    //Ref
    //$jsonData['ref']=$ref;
    
    //Generate Hash
    $jsonData['hash']=hash('sha512',$token.$email.$username);
    
    //Category
    $jsonData['category']=$categories;
    
    //Product
    $jsonData['product']=$plan_code;
    
    //Validate
    $jsonData['validate']=$smart_card;
    	  
    //Send as a POST request.
    curl_setopt($ch, CURLOPT_POST, 1);
    
    //Attach encoded JSON string to the POST fields.
    curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
    
    //Allow parsing response to string
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    
    //Execute the request
    $response=curl_exec($ch);
    
    $jsonData = json_decode($response, true);
    
    curl_close ($ch);
    
    if($jsonData['response'] == "OK" && $smart_card != ""){
        
        echo "<hr>";
        echo '<form class="form-horizontal" method="post" enctype="multipart/form-data">
        <div class="box-body">';
        echo '<input name="cust_name" type="hidden" class="form-control" value="'.$jsonData['result']['name'].'">';
        echo "<div class='alert bg-".(($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color'])."'>Customer Name: ".$jsonData['result']['name']." (".strtoupper($categories)." Subscription!)</div>";

    }elseif($smart_card == ""){
        
        echo "<hr>";
        echo "<div class='alert bg-".(($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color'])."'>Bill Payment (".strtoupper($categories)." Subscription!)</div>";
        
    }elseif($jsonData['response'] != "OK" && $smart_card != ""){
        
        mysqli_query($link, "DELETE FROM bill_payverification WHERE refid = '$myref'");
        echo "<script>alert('Oops! Network Error, please try again later!!'); </script>";
	    echo "<script>window.location='pay_bill1.php?id=".$_SESSION['tid']."&&mid=NDA4&&tab=tab_1'; </script>";
        
    }
    
    //Initiate cURL.
    $ch1 = curl_init($url);
    
    $jsonData1['username']=$username;
    
    //Ref
    //$jsonData['ref']=$ref;
    
    //Generate Hash
    $jsonData1['hash']=hash('sha512',$token.$email.$username);
    
    //Category
    $jsonData1['category']=$categories;
    
    //Product
    $jsonData1['product']=$plan_code;
    	  
    //Send as a POST request.
    curl_setopt($ch1, CURLOPT_POST, 1);
    
    //Attach encoded JSON string to the POST fields.
    curl_setopt($ch1, CURLOPT_POSTFIELDS, $jsonData1);
    
    //Allow parsing response to string
    curl_setopt($ch1, CURLOPT_RETURNTRANSFER, true);
    
    //Execute the request
    $response1=curl_exec($ch1);
    
    $jsonData1 = json_decode($response1, true);
    
    curl_close ($ch1);
    
    if($jsonData1['response'] == "B005"){
        
?>
<!-- START -->

		<div class="form-group">
                <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Amount:</label>
                <div class="col-sm-6">
                    <input name="my_amount" type="number" class="form-control" placeholder="Enter Amount (Minimum: 1000 & Maximum: 50000)" max-length="5" required>
		            <span style="font-size: 14px;"><b>NOTE:</b><i> Minimum Amount is: 1000 AND Maximum Amount is: 50000</i></span>
                </div>
                <label for="" class="col-sm-3 control-label"></label>
            </div>
	    
	    <?php
	    if($categories == "electricity"){
	    ?>
	        <div class="form-group">
                <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Payment Type:</label>
                <div class="col-sm-6">
                    <select name="ptype"  class="form-control select2" required style="width:100%">
                        <option value="" selected="selected">Select Payment Type...</option>
                        <option value="1">Prepaid</option>
                        <option value="0">Postpaid</option>
                    </select>
                </div>
                <label for="" class="col-sm-3 control-label"></label>
            </div>
	    <?php
	    }
	    else{
	        echo "";
	    }
	    ?>
	    
	        <div class="form-group">
                <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Transaction Pin:</label>
                <div class="col-sm-6">
                    <input name="epin" type="password" class="form-control" placeholder="Transaction Pin" maxlength="4" required>
                </div>
                <label for="" class="col-sm-3 control-label"></label>
            </div>
        
    	</div>
			
	</div>
	    
	    <div class="form-group" align="right">
                <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;"></label>
                <div class="col-sm-6">
                    <button name="ValidateC1" type="submit" class="btn bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?> btn-flat"><i class="fa fa-mobile">&nbsp;Pay Bills</i></button>
                </div>
                <label for="" class="col-sm-3 control-label"></label>
        </div>

<?php
if(isset($_POST['ValidateC1']))
{
    //include("../config/walletafrica_restfulapis_call.php");
    
    $jsonData2=array();
    $result = array();
    $myref2 = $_GET['ref'];
    $my_amount =  mysqli_real_escape_string($link, $_POST['my_amount']);
    $epin =  mysqli_real_escape_string($link, $_POST['epin']);
    $cust_name = mysqli_real_escape_string($link, $_POST['cust_name']);
    $ptype = mysqli_real_escape_string($link, $_POST['ptype']);
    //echo$cust_name;
    
    $search_pendingbills2 = mysqli_query($link, "SELECT * FROM bill_payverification WHERE refid = '$myref2'");
    $fetch_pendingbills2 = mysqli_fetch_object($search_pendingbills2);
    
	$search_billconfig2 = mysqli_query($link, "SELECT * FROM billpayment");
	$fetch_billconfig2 = mysqli_fetch_object($search_billconfig2);
	
	$url2 = 'https://estoresms.com/bill_payment_processing/v/1/'; //API Url (Do not change)
	//$url = '$fetch_billconfig2->api_url'; //API Url (Do not change)
    $token2=$fetch_billconfig2->token; //Replace with your API token
    $email2=$fetch_billconfig2->email;  //Replace with your account email address
    $username2=$fetch_billconfig2->username;  // Replace with your account username
    $categories2 = $fetch_pendingbills2->cat;
    $plan_code2 = $fetch_pendingbills2->plan_name;
    $smart_card2 = $fetch_pendingbills2->scard;
    
    if($bwallet_balance < $my_amount){
        
        mysqli_query($link, "DELETE FROM bill_payverification WHERE refid = '$myref2'");
        echo "<script>alert('Oops!..You have Insufficient fund in your wallet!!'); </script>";
        echo "<script>window.location='mywallet.php?id=".$_SESSION['tid']."&&mid=NDA4&&tab=tab_1'; </script>"; 
        
    }elseif($my_amount < 1000 || $my_amount >= 50000){
        
        echo "<script>alert('Oops!..Amount Entered is out of Range!!'); </script>";

    }elseif($myuepin != $epin){
	    
	    echo "<script>alert('Invalid Transaction Pin!'); </script>";
	    
	}else{
        
        //Initiate cURL.
        $ch2 = curl_init($url2);
        
        $jsonData2['username']=$username2;
        
        //Generate Hash
        $jsonData2['hash']=hash('sha512',$token2.$email2.$username2.$myref2);
        
        //Ref
        $jsonData2['ref']=$myref2;
        
        //Category
        $jsonData2['category']=$categories2;
        
        //Product
        $jsonData2['product']=$plan_code2;
        
        //Plan Code
        $jsonData2['amount']=$my_amount;
        
        //Smart card
        ($smart_card == "") ? "" : $jsonData2['number']=$smart_card2;
        
        //Prepaid
        ($categories == "electricity" && $ptype == "1" ? $jsonData2['prepaid']=$ptype : ($categories == "electricity" && $ptype == "0" ? $jsonData2['postpaid']=$ptype : ""));
        	  
        //Send as a POST request.
        curl_setopt($ch2, CURLOPT_POST, 1);
        
        //Attach encoded JSON string to the POST fields.
        curl_setopt($ch2, CURLOPT_POSTFIELDS, $jsonData2);
        
        //Allow parsing response to string
        curl_setopt($ch2, CURLOPT_RETURNTRANSFER, true);
        
        //Execute the request
        $response2=curl_exec($ch2);
        
        $jsonData2 = json_decode($response2, true);
        
        curl_close ($ch2);
        
        $commission = $jsonData2['discounted_amount'] * $bbillPaymentCommission;
        
        $discounted_amount = $jsonData2['discounted_amount'];
        
        if($jsonData2['response'] == "OK"){
            
            $transaction_id = $jsonData2['transaction_id'];
                    
            //Get my wallet balance after debiting
            $senderBalance = $bwallet_balance - $jsonData2['discounted_amount'];
            $currentdate = date("Y-m-d h:i:s");
            
            //$remainBalance = $bwallet_balance - $jsonData2['discounted_amount'];
            
            //$ptype = "Prepaid"
            $mypayment_type = ($categories == "electricity" && $ptype == "1" ? "Prepaid" : ($categories == "electricity" && $ptype == "0" ? "Postpaid" : $categories2));
                
            $service_msg = "<br><b> Customer Name:</b><br>".$cust_name."<br><br>";
            $service_msg .= ($categories == "electricity") ? "<br><br> <b>Service Description:</b><br>".$jsonData2['pin_message'] : "";
            
            $update_records = mysqli_query($link, "UPDATE borrowers SET wallet_balance = '$senderBalance' WHERE account = '$acctno'");
    		$insert = mysqli_query($link, "INSERT INTO wallet_history VALUES(null,'$bbranchid','$transaction_id','$smart_card','','$discounted_amount','Debit','$bbcurrency','$mypayment_type - WEB','$service_msg','successful','$currentdate','$acctno','$senderBalance','')");
    		
    		//$insert = mysqli_query($link, "INSERT INTO wallet_history VALUES(null,'$agentid','$transaction_id','$smart_card','$commission','$acurrency','Commission - WEB','$service_msg','successful',NOW())");
    		    
            mysqli_query($link, "DELETE FROM bill_payverification WHERE refid = '$myref'");
        	echo "<script>alert('".$jsonData2['message']."\\n ".$service_msg."'); </script>";
        	//echo "<script>window.open('../pdf/view/pdf_payschedule.php?id=".$id."&&acn=".$tid."&&mid=NDA1&&lid=".$lid."&&instid=".$institution_id."', '_blank'); </script>";
        	echo "<script>window.location='mywallet.php?id=".$_SESSION['tid']."&&mid=NDA4&&tab=tab_1'; </script>";
            
        }elseif($jsonData2['response'] == "P013"){
            
            //mysqli_query($link, "DELETE FROM bill_payverification WHERE refid = '$myref'");
            echo "<script>alert('Oops!...Network Error, please try again later!!'); </script>";
            //echo "<script>alert('".$jsonData2['message']."'); </script>";

        }else{
            
            //mysqli_query($link, "DELETE FROM bill_payverification WHERE refid = '$myref'");
            //echo "<script>alert('Oops!...Network Error, please try again later!!'); </script>";
            echo "<script>alert('".$jsonData2['message']."'); </script>";
        }
        
    }
  
}
?>
			 </form> 



<?php
}
elseif($jsonData1['response'] == "OK"){
    
?>

    
    <form class="form-horizontal" method="post" enctype="multipart/form-data">

        <div class="box-body">

    		<div class="form-group">
                <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Product Plan:</label>
                <div class="col-sm-6">
                    <select name="p_plan"  class="form-control select2" required style="width:100%">
                        <option value="" selected="selected">Select Product Plan...</option>
                        <?php
                        foreach ($jsonData1['result'] as $pkey1) {
                            
                            echo '<option value='.$pkey1['plan_id'].'>'.$pkey1['name'].' (Price: '.number_format($pkey1['price'],2,'.',',').')'.'</option>';
                            
                        }
                        ?>
                    </select>
                </div>
                <label for="" class="col-sm-3 control-label"></label>
            </div>
        
	        <div class="form-group">
                <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Amount:</label>
                <div class="col-sm-6">
                    <input name="my_amount" type="number" class="form-control" placeholder="Enter Amount" max-length="5" required>
		            <span style="font-size: 14px;"><b>NOTE:</b><i> Please enter amount according to the product plan choosed above</i></span>
                </div>
                <label for="" class="col-sm-3 control-label"></label>
            </div>
	    
	        <div class="form-group">
                <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Transaction Pin:</label>
                <div class="col-sm-6">
                    <input name="epin" type="password" class="form-control" placeholder="Transaction Pin" maxlength="4" required>
                </div>
                <label for="" class="col-sm-3 control-label"></label>
            </div>
        
    	</div>
			
	</div>
	    
	    <div class="form-group" align="right">
                <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;"></label>
                <div class="col-sm-6">
                    <button name="ValidateC2" type="submit" class="btn bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?> btn-flat"><i class="fa fa-mobile">&nbsp;Pay Bills</i></button>
                </div>
                <label for="" class="col-sm-3 control-label"></label>
        </div>

<?php
if(isset($_POST['ValidateC2']))
{
    //include("../config/walletafrica_restfulapis_call.php");
    
    $jsonData3=array();
    $result1=array();
    $myref3 = $_GET['ref'];
    $p_plan3 =  mysqli_real_escape_string($link, $_POST['p_plan']);
    $my_amount =  mysqli_real_escape_string($link, $_POST['my_amount']);
    $epin =  mysqli_real_escape_string($link, $_POST['epin']);
    
    $search_pendingbills3 = mysqli_query($link, "SELECT * FROM bill_payverification WHERE refid = '$myref3'");
    $fetch_pendingbills3 = mysqli_fetch_object($search_pendingbills3);
    
	$search_billconfig3 = mysqli_query($link, "SELECT * FROM billpayment");
	$fetch_billconfig3 = mysqli_fetch_object($search_billconfig3);
	
	$url3 = 'https://estoresms.com/bill_payment_processing/v/1/'; //API Url (Do not change)
	//$url = '$fetch_billconfig2->api_url'; //API Url (Do not change)
    $token3=$fetch_billconfig3->token; //Replace with your API token
    $email3=$fetch_billconfig3->email;  //Replace with your account email address
    $username3=$fetch_billconfig3->username;  // Replace with your account username
    $categories3 = $fetch_pendingbills3->cat;
    $plan_code3 = $fetch_pendingbills3->plan_name;
    $smart_card3 = $fetch_pendingbills3->scard;
    
    if($bwallet_balance < $my_amount){
        
        mysqli_query($link, "DELETE FROM bill_payverification WHERE refid = '$myref3'");
        echo "<script>alert('Oops!..You have Insufficient fund in your wallet!!'); </script>";
        echo "<script>window.location='mywallet.php?id=".$_SESSION['tid']."&&mid=NDA4&&tab=tab_1'; </script>"; 
        
    }elseif($myuepin != $epin){
	    
	    echo "<script>alert('Invalid Transaction Pin!'); </script>";
	    
	}else{
        
        //Initiate cURL.
        $ch3 = curl_init($url3);
        
        $jsonData3['username']=$username3;
        
        //Generate Hash
        $jsonData3['hash']=hash('sha512',$token3.$email3.$username3.$myref3);
        
        //Ref
        $jsonData3['ref']=$myref3;
        
        //Category
        $jsonData3['category']=$categories3;
        
        //Product
        $jsonData3['product']=$plan_code3;
        
        //Plan Code
        $jsonData3['plan']=$p_plan3;
        
        //Smart card
        ($smart_card3 == "") ? "" : $jsonData3['number']=$smart_card3;
        	  
        //Send as a POST request.
        curl_setopt($ch3, CURLOPT_POST, 1);
        
        //Attach encoded JSON string to the POST fields.
        curl_setopt($ch3, CURLOPT_POSTFIELDS, $jsonData3);
        
        //Allow parsing response to string
        curl_setopt($ch3, CURLOPT_RETURNTRANSFER, true);
        
        //Execute the request
        $response3=curl_exec($ch3);
        
        $jsonData3 = json_decode($response3, true);
        
        curl_close ($ch3);
        
        //$commission = $jsonData3['discounted_amount'] * $bbillPaymentCommission;
        
        $discounted_amount = $jsonData3['discounted_amount'];
        
        if($jsonData3['response'] == "OK"){
            
            $transaction_id = $jsonData3['transaction_id'];
                    
            //Get my wallet balance after debiting
            $senderBalance = $bwallet_balance - $discounted_amount;
            $currentdate = date("Y-m-d h:i:s");
            
            //$remainBalance = $bwallet_balance - $discounted_amount;
            
            foreach ($jsonData3['pins'] as $pkey)
                $serialNumber = $pkey['serialNumber'];
                $pin = $pkey['pin'];
                
            $service_msg = ($serialNumber == "") ? $categories." Payment" : "<b>Serial Number:</b> ".$serialNumber." ";
            $service_msg .= ($pin == "") ? $categories." Payment" : "<b>Pin:</p> ".$pin;
            
            $update_records = mysqli_query($link, "UPDATE borrowers SET wallet_balance = '$senderBalance' WHERE account = '$acctno'");
            
    		$insert = mysqli_query($link, "INSERT INTO wallet_history VALUES(null,'$bbranchid','$transaction_id','$smart_card','','$discounted_amount','Debit','$bbcurrency','$categories - WEB','$service_msg','successful','$currentdate','$acctno','$senderBalance','')");
    		
    		//$insert = mysqli_query($link, "INSERT INTO wallet_history VALUES(null,'$agentid','$transaction_id','$smart_card','$commission','$acurrency','Commission - WEB','$service_msg','successful',NOW())");    
    		
            mysqli_query($link, "DELETE FROM bill_payverification WHERE refid = '$myref'");
        	echo "<script>alert('".$jsonData3['message']."\\n ".$service_msg."'); </script>";
        	echo "<script>window.location='pay_bill1.php?id=".$_SESSION['tid']."&&mid=NDA4&&tab=tab_1'; </script>";
            
        }elseif($jsonData3['response'] == "P013"){
            
            //mysqli_query($link, "DELETE FROM bill_payverification WHERE refid = '$myref'");
            echo "<script>alert('Oops!...Network Error, please try again later!!'); </script>";
            //echo "<script>alert('".$jsonData3['message']."'); </script>";

        }else{
            
            //mysqli_query($link, "DELETE FROM bill_payverification WHERE refid = '$myref'");
            //echo "<script>alert('Oops!...Network Error, please try again later!!'); </script>";
            echo "<script>alert('".$jsonData3['message']."'); </script>";
        }
        
    }
	
}
?>
			 </form> 


<?php
}
?>
<!-- END -->
        






<?php
}
else{
?>

	<form class="form-horizontal" method="post" enctype="multipart/form-data">
	    
	    <div class="box-body">
	    
        <div class="form-group">
                <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Service Name</label>
                <div class="col-sm-6">
                    <select name="bill_cat"  class="form-control select2" id="bill_cat" onchange="load_billcat();" required style="width:100%">
                        <option value="" selected="selected">Select Service Name...</option>
                        <option value="tv">Tv</option>
                        <option value="internet">Internet</option>
                        <option value="electricity">Electricity</option>
                        <option value="waec">Waec Pin</option>
                    </select>
                </div>
                <label for="" class="col-sm-3 control-label"></label>
            </div>
        
            <div id="product_list"></div>
            
            <!--<div id="verify_customer"></div>-->
        
    	</div>
	
	   <div class="form-group" align="right">
           <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;"></label>
           <div class="col-sm-6">
           <button name="ValidateC" type="submit" class="btn bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>"><i class="fa fa-mobile">&nbsp;Validate</i></button>
           </div>
           <label for="" class="col-sm-3 control-label"></label>
        </div>
        

<?php
if(isset($_POST['ValidateC']))
{

    $bill_cat =  mysqli_real_escape_string($link, $_POST['bill_cat']);
    $plan_list = mysqli_real_escape_string($link, $_POST['plan_list']);
    $scard =  mysqli_real_escape_string($link, $_POST['scard']);
    //$myaccount = $_SESSION['acctno'];
    $refid = "EA-billpay-".rand(1000,9999);
  
	$insert = mysqli_query($link, "INSERT INTO bill_payverification VALUES(null,'$refid','$bill_cat','$plan_list','$scard')");
		    
	echo "<script>alert('Verification Loading... Click OK to proceed'); </script>";
	echo "<script>window.location='pay_bill1.php?id=".$_SESSION['tid']."&&ref=".$refid."&&mid=NDA4&&tab=tab_1'; </script>";
}
?>
			 </form> 

<?php } ?>

</div>	
</div>	
</div>
</div>