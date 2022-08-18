<div class="box">
	       <div class="box-body">
			<div class="panel panel-success">
            <div class="panel-heading bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">
            <h3 class="panel-title"> <a href="mywallet.php?id=<?php echo $_SESSION['tid']; ?>&&mid=NDA0&&tab=tab_1"><button type="button" class="btn btn-flat bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>"><i class="fa fa-mail-reply-all"></i>&nbsp;Back</button></a> <a href="pay_billsdata.php?id=<?php echo $_SESSION['tid']; ?>&&mid=NDA0"><button type="button" class="btn btn-flat bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>"><i class="fa fa-refresh"></i>&nbsp;Refresh</button></a> 
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

<?php
$search_memset = mysqli_query($link, "SELECT * FROM member_settings WHERE companyid = '$institution_id'");
$fetch_memset = mysqli_fetch_array($search_memset);
$otp_option = $fetch_memset['otp_option'];
?>

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
                 
<!--- DATA BUNDLE FORM START HERE -->			 

			 <form class="form-horizontal" method="post" enctype="multipart/form-data">

             <div class="box-body">
                 
                 <div class="form-group">
                    <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Phone Number:</label>
                    <div class="col-sm-6">
                        <input name="a_myphone" type="text" class="form-control" id="phone">
                    </div>
                    <label for="" class="col-sm-3 control-label" align="left"></label>
                  </div>
                  
                <div class="form-group">
                    <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;"></label>
                    <div class="col-sm-6">
                        <button name="Verifybill2" type="submit" onclick="myFunction()" class="btn bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?> btn-flat"><i class="fa fa-search">&nbsp;Verify</i></button>
                    </div>
                    <label for="" class="col-sm-3 control-label" align="left"></label>
                  </div>
      
<?php
if(isset($_POST['Verifybill2'])){
    
    //$link = mysqli_connect('localhost','root','root','loan2','8888') or die('Unable to Connect to Database');
	$jsonData=array();
	$search_billconfig = mysqli_query($link, "SELECT * FROM billpayment");
	$fetch_billconfig = mysqli_fetch_object($search_billconfig);
	
	$url = 'https://estoresms.com/data_list/v/2'; //API Url (Do not change)
	//$url = '$fetch_billconfig->api_url'; //API Url (Do not change)
    $token=$fetch_billconfig->token; //Replace with your API token
    $email=$fetch_billconfig->email;  //Replace with your account email address
    $username=$fetch_billconfig->username;  // Replace with your account username
    $ref = "txid-".time();
    $phone = mysqli_real_escape_string($link, $_POST['a_myphone']);
    
    if($phone == ""){
        
        echo "<script>alert('Oops!..Phone Number cannot be empty!!'); </script>";
        
    }else{

        //Initiate cURL.
        $ch = curl_init($url);
        
        $jsonData['username']=$username;
        
        //Ref
        //$jsonData['ref']=$ref;
        
        //Generate Hash
        $jsonData['hash']=hash('sha512',$token.$email.$username);
        
        //Category
        $jsonData['phone']=$phone;
        
        //$jsonData['product']='BPI-NGCA-ANA';
        	  
        //Send as a POST request.
        $jsonDataEncoded = json_encode($jsonData);
    
        //Send as a POST request.
        curl_setopt($ch, CURLOPT_POST, 1);
    
        //Attach encoded JSON string to the POST fields.
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonDataEncoded);
    
        //Set the content type as application/json
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
    
        //Allow parsing response to string
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    
        //Execute the request
        $response=curl_exec($ch);
        
        curl_close ($ch);
        
        if($response){
      	   $jsonData = json_decode($response, true);
      	   //var_dump($response);
      	   if($jsonData['response'] == "OK") {
    
                    echo '<div class="form-group">
                                <label for="" class="col-sm-3 control-label" style="color:'.(($myaltrow['theme_color'] == "") ? "blue" : $myaltrow['theme_color']).';">Receiver:</label>
                                <div class="col-sm-6">
                                    <input name="myphone" type="text" class="form-control" value="'.$phone.'" required/>
                                </div>
                                <label for="" class="col-sm-3 control-label"></label>
                            </div>
                            
                            <div class="form-group">
                                <label for="" class="col-sm-3 control-label" style="color:'.(($myaltrow['theme_color'] == "") ? "blue" : $myaltrow['theme_color']).';">Operator:</label>
                                <div class="col-sm-6">
                                    <input name="operator" type="text" class="form-control" value="'.$jsonData['info']['operator'].' ('.$jsonData['info']['country'].')" readonly/>
                                </div>
                                <label for="" class="col-sm-3 control-label"></label>
                            </div>';
                            
                    echo '<div class="form-group">
                                <label for="" class="col-sm-3 control-label" style="color:'.(($myaltrow['theme_color'] == "") ? "blue" : $myaltrow['theme_color']).';">Data Product</label>
                                <div class="col-sm-6">
                                    <select name="databundle_product"  class="form-control"  required style="width:100%">
                                      <option value="" selected="selected">Select Data Product...</option>';
                                      //$a = 0;
                                        foreach ($jsonData['products'] as $pkey) {
                                            //$a++;
                                            $data_conversion = ($pkey['data_amount'] >= 1000) ? ($pkey['data_amount']/1000).' GB' : $pkey['data_amount'].' MB'; 
                                          echo '<option value="'.$pkey['id'].'">'.$data_conversion.' Price: (NGN'.number_format($pkey['topup_amount'],2,'.',',').')</option>';
                
                                        }
                            echo '</select></div>
                                <label for="" class="col-sm-3 control-label"></label>
                            </div>';
                            
                    echo '<div class="form-group">
                                <label for="" class="col-sm-3 control-label" style="color:'.(($myaltrow['theme_color'] == "") ? "blue" : $myaltrow['theme_color']).';">Data Amount</label>
                                <div class="col-sm-6">
                                    <select name="amount_recharge" class="form-control" required style="width:100%">
                                      <option value="" selected="selected">Select Data Amount...</option>';
                                      //$a = 0;
                                        foreach ($jsonData['products'] as $pkey) {
                                            //$a++;
                                          echo '<option value="'.$pkey['topup_amount'].'">'.$pkey['topup_currency'].' '.number_format($pkey['topup_amount'],2,'.',',').'</option>';
                
                                        }
                            echo '</select><span style="font-size: 14px;"><b>NOTE:</b> <i>Select the amount acccording to the data plan selected</i></span></div>
                                <label for="" class="col-sm-3 control-label"></label>
                            </div>';
                    
              }
              
    		        
      	   }else{
      	       
      	       echo "<br><label class='label bg-orange'>Oops!...Network Error, Please try again later!!</label>";
      	       
      	   }
    }
}
?>

                <div class="form-group">
                    <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Transaction Pin:</label>
                    <div class="col-sm-6">
                        <input name="epin" type="password" class="form-control" placeholder="Transaction Pin" maxlength="4">
                    </div>
                    <label for="" class="col-sm-3 control-label"></label>
                </div>
			
	        </div>
	        
	        <div class="form-group" align="right">
                <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;"></label>
                <div class="col-sm-6">
                    <button name="PayBill2" type="submit" class="btn bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?> btn-flat"><i class="fa fa-mobile">&nbsp;Buy Airtime</i></button>
                </div>
                <label for="" class="col-sm-3 control-label"></label>
             </div>

<?php
if(isset($_POST['PayBill2']))
{
    include("../config/walletafrica_restfulapis_call.php");
    
    $jsonData=array();
    $result=array();
    $result1=array();
	$search_billconfig = mysqli_query($link, "SELECT * FROM billpayment");
	$fetch_billconfig = mysqli_fetch_object($search_billconfig);
	
	$url = 'https://estoresms.com/data_processing/v/2'; //API Url (Do not change)
	//$url = '$fetch_billconfig->api_url'; //API Url (Do not change)
    $token=$fetch_billconfig->token; //Replace with your API token
    $email=$fetch_billconfig->email;  //Replace with your account email address
    $username=$fetch_billconfig->username;  // Replace with your account username
    $myref = "txid-".time();
    $phone =  mysqli_real_escape_string($link, $_POST['myphone']);
    $operator =  mysqli_real_escape_string($link, $_POST['operator']);
    $databundle_product =  mysqli_real_escape_string($link, $_POST['databundle_product']);
    $amount_recharge =  mysqli_real_escape_string($link, $_POST['amount_recharge']);
    $epin =  mysqli_real_escape_string($link, $_POST['epin']);
    
    if($itransfer_balance < $amount_recharge){
	    
	    echo "<script>alert('Insufficient Fund in your Wallet!!'); </script>";
	    
		echo "<script>window.location='mywallet.php?id=".$_SESSION['tid']."&&mid=NDA0'; </script>";
		
	}elseif($epin != $myiepin || $epin == ""){
	    
	    echo "<script>alert('Invalid Transaction Pin!'); </script>";
	    
		echo "<script>window.location='pay_bills.php?tid=".$_SESSION['tid']."&&mid=NDA0'; </script>";
	    
	}else{
	    //Initiate cURL.
        $ch = curl_init($url);
        
        $jsonData['username']=$username;
        
        //Ref
        $jsonData['ref']=$myref;
        
        //Callback URL
        $jsonData['callback']="https://esusu.app/institution/pay_bills.php";
        
        //Generate Hash
        $jsonData['hash']=hash('sha512',$token.$email.$username.$myref);
        	  
        //MAKE REQUESTS (Duplicate this line to make multiple requests at a time)
        //$jsonData['request'][]=['product'=>$airtime_product,'phone'=>$phone];
        $jsonData['request'][]=['product'=>$databundle_product,'phone'=>$phone,'amount'=>$amount_recharge];
        
        //Encode the array into JSON.
        $jsonDataEncoded = json_encode($jsonData);
        
        //Send as a POST request.
        curl_setopt($ch, CURLOPT_POST, 1);
        
        //Attach encoded JSON string to the POST fields.
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonDataEncoded);
        
        //Set the content type as application/json
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        
        //Allow parsing response to string
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        
        //Execute the request
        $response=curl_exec($ch);
        
        $jsonData = json_decode($response, true);
        
        curl_close ($ch);

		if($jsonData['failed'] == "0")
		{
		    foreach($jsonData['result'] as $newOutput){
		        
		        if($newOutput['response'] == "OK"){
		            
		            //successful transaction
		            $transaction_id = $newOutput['transaction_id'];
		            $commission = $newOutput['amount'] * $iairtimeDataCommission;
		            //$data_amount = $newOutput['data_amount'];
		            $amount = $newOutput['amount'] - $commission;
                    
                    //Get my wallet balance after debiting
                    $senderBalance = $itransfer_balance - $amount;
                    
	                //$remainBalance = $iassigned_walletbal - $amount;
        		    $insert = mysqli_query($link, "INSERT INTO wallet_history VALUES(null,'$institution_id','$transaction_id','$phone','','$amount','Debit','$icurrency','Databundle - WEB','$operator Databundle Purchase for $databundle_product with refid: $myref via WEB','successful',NOW(),'$iuid','$senderBalance','')");
        		    
        		    $insert = mysqli_query($link, "INSERT INTO wallet_history VALUES(null,'$institution_id','$transaction_id','$phone','$commission','','Credit','$icurrency','Commission - WEB','$operator Databundle Purchase Commission for $databundle_product with refid: $myref via WEB','successful',NOW(),'$iuid','$senderBalance','')");
        		    
        		    mysqli_query($link, "UPDATE user SET transfer_balance = '$senderBalance' WHERE id = '$iuid'");
		            
		            echo "<script>alert('Databundle Purchased Successfully!'); </script>";
		            echo "<script>window.location='pay_bills.php?id=".$_SESSION['tid']."&&mid=NDA0'; </script>";
		            
		        }elseif($newOutput['response'] == "PENDING"){
		            
		            //pending transaction
		            $transaction_id = $newOutput['transaction_id'];
		            $commission = $newOutput['amount'] * $iairtimeDataCommission;
		            $amount = $newOutput['amount'] - $commission;
                    
	                //$remainBalance = $cwallet_balance - (($ccurrency != "NGN") ? $amount_recharge : $newOutput['amount_charged']);
        		    $insert = mysqli_query($link, "INSERT INTO wallet_history VALUES(null,'$institution_id','$transaction_id','$phone','','$amount','Debit','$icurrency','Databundle - WEB','$operator Databundle Purchase for $databundle_product with refid: $myref via WEB','pending',NOW(),'$iuid','$itransfer_balance','')");
        		    
        		    $insert = mysqli_query($link, "INSERT INTO wallet_history VALUES(null,'$institution_id','$transaction_id','$phone','$commission','','Credit','$icurrency','Commission - WEB','$operator Databundle Purchase Commission for $databundle_product with refid: $myref via WEB','pending',NOW(),'$iuid','$itransfer_balance','')");
        		    //$update_records = mysqli_query($link, "UPDATE cooperatives SET wallet_balance = '$remainBalance' WHERE coopid = '$coopid'");
		            
		            echo "<script>alert('Databundle Purchased in Proceessing...!'); </script>";
		            echo "<script>window.location='pay_bills.php?id=".$_SESSION['tid']."&&mid=NDA0'; </script>";
		            
		        }
		        
		    }
		}elseif($jsonData['response'] == "P013"){
		    
		    echo "<script>alert('Oops! Network Error, please try again later!!'); </script>";
		    
		}else{
		    
		    echo "<script>alert('".$newOutput['message']."'); </script>";
		    
		}
		
	}
}
?>
			 </form>

</div>	
</div>	
</div>
</div>