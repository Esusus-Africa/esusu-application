<div class="box">
	       <div class="box-body">
			<div class="panel panel-success">
            <div class="panel-heading bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">
            <h3 class="panel-title"> 
            <?php echo (isset($_GET['ref'])) ? "<a href='pay_bill1?id=".$_SESSION['tid']."&&del=".$_GET['ref']."&&mid=NDA0'><button type='button' class='btn btn-flat bg-".(($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color'])."'><i class='fa fa-mail-reply-all'></i>&nbsp;Back</button></a>" : "<a href='pay_bills?id=".$_SESSION['tid']."&&mid=NDA0'><button type='button' class='btn btn-flat bg-".(($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color'])."'><i class='fa fa-mail-reply-all'></i>&nbsp;Back</button></a>"; ?> 
            <?php echo (isset($_GET['ref'])) ? "<a href='pay_bill1?id=".$_SESSION['tid']."&&ref=".$_GET['ref']."&&mid=NDA0'><button type='button' class='btn btn-flat bg-".(($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color'])."'><i class='fa fa-refresh'></i>&nbsp;Refresh</button></a>" : "<a href='pay_bill1?id=".$_SESSION['tid']."&&mid=NDA0'><button type='button' class='btn btn-flat bg-".(($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color'])."'><i class='fa fa-refresh'></i>&nbsp;Refresh</button></a>"; ?>
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
        echo "<div class='alert bg-".(($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color'])."'>Customer Name: ".$jsonData['result']['name']." (".strtoupper($categories)." Subscription!)</div>";

    }elseif($smart_card == ""){
        
        echo "<hr>";
        echo "<div class='alert bg-".(($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color'])."'>Bill Payment (".strtoupper($categories)." Subscription!)</div>";
        
    }elseif($jsonData['response'] != "OK" && $smart_card != ""){
        
        mysqli_query($link, "DELETE FROM bill_payverification WHERE refid = '$myref'");
        echo "<script>alert('Oops! Network Error, please try again later!!'); </script>";
	    echo "<script>window.location='pay_bill1.php?id=".$_SESSION['tid']."&&mid=NDA0'; </script>";
        
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


        <form class="form-horizontal" method="post" enctype="multipart/form-data">
        <div class="box-body">

		<div class="form-group">
            <label for="" class="col-sm-3 control-label" style="color:blue;">Amount: </label>
		    <div class="col-sm-9">
		    <input name="my_amount" type="number" class="form-control" placeholder="Enter Amount (Minimum: 1000 & Maximum: 50000)" max-length="5" required>
		    <span style="font-size: 14px;"><b>NOTE:</b><i> Minimum Amount is: 1000 AND Maximum Amount is: 50000</i></span>
		    </div>
	    </div>
        
    	</div>
			
	</div>

        <div align="right">
          <div class="box-footer">
              <button name="ValidateC1" type="submit" class="btn bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?> btn-flat"><i class="fa fa-mobile">&nbsp;Pay Bills</i></button>
          </div>
        </div>

<?php
if(isset($_POST['ValidateC1']))
{
    $currency = $fetchsys_config['currency'];
    $my_amount =  mysqli_real_escape_string($link, $_POST['my_amount']);
    
    if($bwallet_balance < $my_amount){
        
        mysqli_query($link, "DELETE FROM bill_payverification WHERE refid = '$myref'");
        echo "<script>alert('Oops!..You have Insufficient fund in your wallet!!'); </script>";
        echo "<script>window.location='mywallet.php?id=".$_SESSION['tid']."&&mid=NDA0'; </script>"; 
        
    }elseif($my_amount < 1000 || $my_amount > 50000){
        
        echo "<script>alert('Oops!..Amount Entered is out of Range!!'); </script>";

    }
    else{
        
        //Initiate cURL.
        $ch2 = curl_init($url);
        
        $jsonData2['username']=$username;
        
        //Generate Hash
        $jsonData2['hash']=hash('sha512',$token.$email.$username.$myref);
        
        //Ref
        $jsonData2['ref']=$myref;
        
        //Category
        $jsonData2['category']=$categories;
        
        //Product
        $jsonData2['product']=$plan_code;
        
        //Plan Code
        $jsonData2['amount']=$my_amount;
        
        //Smart card
        ($smart_card == "") ? "" : $jsonData2['number']=$smart_card;
        
        //Prepaid
        ($categories == "electricity") ? $jsonData2['prepaid']=1 : "";
        	  
        //Send as a POST request.
        curl_setopt($ch2, CURLOPT_POST, 1);
        
        //Attach encoded JSON string to the POST fields.
        curl_setopt($ch2, CURLOPT_POSTFIELDS, $jsonData2);
        
        //Allow parsing response to string
        curl_setopt($ch2, CURLOPT_RETURNTRANSFER, true);
        
        //Execute the request
        $response2=curl_exec($ch2);
        
        $jsonData2 = json_decode($response3, true);
        
        curl_close ($ch2);
        
        $commission = $jsonData2['discounted_amount'] * $fetchsys_config['bp_commission'];
        
        $discounted_amount = $jsonData2['discounted_amount'] - $commission;
        
        if($jsonData2['response'] == "OK"){
            
            $transaction_id = $jsonData2['transaction_id'];
            
            $service_msg = ($categories == "electricity") ? "<b>Pin Code:</b> ".$jsonData2['pin_code']." " : "";
            $service_msg .= ($categories == "electricity") ? "<b>Service Description:</b> ".$jsonData2['pin_message'] : "";
            
    		$insert = mysqli_query($link, "INSERT INTO wallet_history VALUES(null,'','$transaction_id','$smart_card','$discounted_amount','$currency','$categories - WEB','$service_msg','successful',NOW())");
    		
    		$insert = mysqli_query($link, "INSERT INTO wallet_history VALUES(null,'','$transaction_id','$smart_card','$commission','$currency','Commission - WEB','$service_msg','successful',NOW())");
    		    
            mysqli_query($link, "DELETE FROM bill_payverification WHERE refid = '$myref'");
        	echo "<script>alert('".$jsonData2['message']."\\n ".$service_msg."'); </script>";
        	echo "<script>window.location='pay_bill1.php?id=".$_SESSION['tid']."&&mid=NDA0'; </script>";
            
        }else if($jsonData2['response'] == "P013"){
            
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
            <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">Product Plan</label>
            <div class="col-sm-9">
        <select name="p_plan"  class="form-control select2" required style="width:100%">
        <option value="" selected="selected">Select Product Plan...</option>
        <?php
        foreach ($jsonData1['result'] as $pkey1) {
            
            echo '<option value='.$pkey1['plan_id'].'>'.$pkey1['name'].' (Price: '.number_format($pkey1['price'],2,'.',',').')'.'</option>';
            
        }
        ?>
        </select>
            </div>
        </div>
        
        <div class="form-group">
            <label for="" class="col-sm-3 control-label" style="color:blue;">Amount: </label>
		    <div class="col-sm-9">
		    <input name="my_amount" type="number" class="form-control" placeholder="Enter Amount" max-length="5" required>
		    <span style="font-size: 14px;"><b>NOTE:</b><i> Please enter amount according to the product plan choosed above</i></span>
		    </div>
	    </div>
        
    	</div>
			
	</div>

        <div align="right">
          <div class="box-footer">
              <button name="ValidateC2" type="submit" class="btn bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?> btn-flat"><i class="fa fa-mobile">&nbsp;Pay Bills</i></button>
          </div>
        </div>

<?php
if(isset($_POST['ValidateC2']))
{

    $p_plan =  mysqli_real_escape_string($link, $_POST['p_plan']);
    $my_amount =  mysqli_real_escape_string($link, $_POST['my_amount']);
        
        //Initiate cURL.
        $ch3 = curl_init($url);
        
        $jsonData3['username']=$username;
        
        //Generate Hash
        $jsonData3['hash']=hash('sha512',$token.$email.$username.$myref);
        
        //Ref
        $jsonData3['ref']=$myref;
        
        //Category
        $jsonData3['category']=$categories;
        
        //Product
        $jsonData3['product']=$plan_code;
        
        //Plan Code
        $jsonData3['plan']=$p_plan;
        
        //Smart card
        ($smart_card == "") ? "" : $jsonData3['number']=$smart_card;
        	  
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
        
        $commission = $jsonData3['discounted_amount'] * $fetchsys_config['bp_commission'];
        
        $discounted_amount = $jsonData3['discounted_amount'] - $commission;
        
        if(($my_amount < $discounted_amount) || ($my_amount > $discounted_amount)){
            
            //mysqli_query($link, "DELETE FROM bill_payverification WHERE refid = '$myref'");
            echo "<script>alert('Oops!..You Have Entered Wrong Product Amount!!'); </script>";

        }else if(($jsonData3['response'] == "OK") && ($bwallet_balance >= $discounted_amount)){
            
            $transaction_id = $jsonData3['transaction_id'];
            
            foreach ($jsonData3['pins'] as $pkey)
                $serialNumber = $pkey['serialNumber'];
                $pin = $pkey['pin'];
                
            $service_msg = ($serialNumber == "") ? $categories." Payment" : "<b>Serial Number:</b> ".$serialNumber." ";
            $service_msg .= ($pin == "") ? $categories." Payment" : "<b>Pin:</p> ".$pin;
            
    		$insert = mysqli_query($link, "INSERT INTO wallet_history VALUES(null,'','$transaction_id','$smart_card','$discounted_amount','$currency','$categories - WEB','$service_msg','successful',NOW())");
    		
    		$insert = mysqli_query($link, "INSERT INTO wallet_history VALUES(null,'','$transaction_id','$smart_card','$commission','$currency','Commission - WEB','$service_msg','successful',NOW())");    
    		
            mysqli_query($link, "DELETE FROM bill_payverification WHERE refid = '$myref'");
        	echo "<script>alert('".$jsonData3['message']."\\n ".$service_msg."'); </script>";
        	echo "<script>window.location='pay_bill1.php?id=".$_SESSION['tid']."&&mid=NDA0'; </script>";
            
        }else if($jsonData3['response'] == "P013"){
            
            //mysqli_query($link, "DELETE FROM bill_payverification WHERE refid = '$myref'");
            echo "<script>alert('Oops!...Network Error, please try again later!!'); </script>";
            //echo "<script>alert('".$jsonData3['message']."'); </script>";

        }else{
            
            //mysqli_query($link, "DELETE FROM bill_payverification WHERE refid = '$myref'");
            //echo "<script>alert('Oops!...Network Error, please try again later!!'); </script>";
            echo "<script>alert('".$jsonData3['message']."'); </script>";
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
<hr>
<div class="alert bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>">Bill Payment </div>
        <div class="box-body">

		<div class="form-group">
            <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">Service Name</label>
            <div class="col-sm-9">
        <select name="bill_cat"  class="form-control select2" id="bill_cat" onchange="load_billcat();" required style="width:100%">
        <option value="" selected="selected">Select Service Name...</option>
        <option value="tv">Tv</option>
        <option value="internet">Internet</option>
        <option value="electricity">Electricity</option>
        <option value="waec">Waec Pin</option>
        </select>
            </div>
        </div>
        
        <div id="product_list"></div>
        
        <div id="verify_customer"></div>
        
    	</div>
			
	</div>

        <div align="right">
          <div class="box-footer">
              <button name="ValidateC" type="submit" class="btn bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?> btn-flat"><i class="fa fa-mobile">&nbsp;Validate to Proceed</i></button>
          </div>
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
	echo "<script>window.location='pay_bill1.php?id=".$_SESSION['tid']."&&ref=".$refid."&&mid=NDA0'; </script>";
}
?>
			 </form> 

<?php } ?>

</div>	
</div>	
</div>
</div>