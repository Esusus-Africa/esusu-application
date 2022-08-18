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
            
            <form class="form-horizontal" method="post" enctype="multipart/form-data">

             <div class="box-body">

                <div class="form-group">
                    <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Category:</label>
                    <div class="col-sm-6">
                        <select name="billcategory" class="form-control select2" id="billpay" required style="width:100%">
                            <option value="" selected="selected">Select Category</option>
                            <option value="cabletv">Cabletv</option>
                            <option value="electricity">Electricity</option>
                        </select>
                    </div>
                    <label for="" class="col-sm-3 control-label" align="left"></label>
                  </div>

                  <span id='ShowValueFrank'></span>
                  <span id='ShowValueFrank'></span>
                 
                 <div class="form-group">
                    <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Customer ID:</label>
                    <div class="col-sm-6">
                        <input name="customerid" type="text" class="form-control" placeholder="Enter Customer ID (Account Number/IUC Number/Card Number)" id="customerid" onkeyup="loadCustomerDetails();" required>
                    </div>
                    <label for="" class="col-sm-3 control-label" align="left"></label>
                  </div>

                  <div class="form-group">
                    <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Customer Name</label>
                    <div class="col-sm-6">
                        <div id="cust_name">------------</div>
                    </div>
                    <label for="" class="col-sm-3 control-label"></label>
                  </div>

                  <div class="form-group">
                    <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Mobile Number</label>
                    <div class="col-sm-6">
                    <input name="phone" type="text" class="form-control" placeholder="Enter Mobile Number" required>
                    </div>
                    <label for="" class="col-sm-3 control-label"></label>
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
                        <button name="PayBill1" type="submit" class="btn bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?> btn-flat"><i class="fa fa-mobile">&nbsp;Pay Bills</i></button>
                    </div>
                    <label for="" class="col-sm-3 control-label"></label>
                </div>


<?php
if(isset($_POST['PayBill1']))
{
    $curl = curl_init();
    
    $reference = uniqid().time();
    $categories = mysqli_real_escape_string($link, $_POST['billcategory']);
    $pcode = mysqli_real_escape_string($link, $_POST['productcode']);
    $my_amount = preg_replace('/[^0-9.]/', '', mysqli_real_escape_string($link, $_POST['amount']));
    $billercode = mysqli_real_escape_string($link, $_POST['billercode']);
    $customerid = mysqli_real_escape_string($link, $_POST['customerid']);
    $cust_name = mysqli_real_escape_string($link, $_POST['cust_name']);
    $phone = mysqli_real_escape_string($link, $_POST['phone']);
    $servicename = mysqli_real_escape_string($link, $_POST['servicename']);
    $epin =  mysqli_real_escape_string($link, $_POST['epin']);

    if($my_amount <= 0){
	    
	    echo "<div class='alert bg-orange'>Invalid amount entered!!</div>";
		
	}elseif($vactive_status == "Suspended"){
        
        echo "<div class='alert bg-orange'>Oops! You are unable to make bill payment at the moment!!</div>";
        
    }elseif($vtransfer_balance < $my_amount){
	    
	    echo "<div class='alert bg-orange'>Insufficient Fund in your Wallet!!</div>";
		
	}elseif($epin != $myvepin || $epin == ""){
	    
	    echo "<div class='alert bg-orange'>Invalid Transaction Pin!</div>";
	    
    }else{

        $commission = $my_amount * $vbillPaymentCommission;
		$amount = $my_amount - $commission;

        $transactionDateTime = date("Y-m-d h:i:s");
        $dataToProcess = $reference."|".$my_amount."|".$customerid."|".$phone."|".$vtransfer_balance."|".$pcode."|".$cust_name."|".$billercode;
        //insert txt waiting list
        $mytxtstatus = 'RUPending';
        ($vtransfer_balance < $my_amount) ? "" : mysqli_query($link, "INSERT INTO api_txtwaitinglist VALUE(null,'$vuid','$reference','$dataToProcess','$mytxtstatus','$transactionDateTime')");
        
        //Get my wallet balance after debiting
        $senderBalance2 = $vtransfer_balance - $amount;
        ($vtransfer_balance < $my_amount) ? "" : mysqli_query($link, "UPDATE user SET transfer_balance = '$senderBalance2' WHERE id = '$vuid'");

        $search_restapi1 = mysqli_query($link, "SELECT * FROM restful_apisetup WHERE api_name = 'rubies_billerpurchase'");
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
                'billercustomerid'=>$customerid,
                'productcode'=>$pcode,
                'amount'=>$my_amount,
                'mobilenumber'=>$phone,
                'name'=>$cust_name,
                'billercode'=>$billercode
            ]),
            CURLOPT_HTTPHEADER => array(
            "Authorization: ".$rubbiesSecKey,
            "Content-Type: application/json"
            ),
        ));
                        
        ($vtransfer_balance < $my_amount) ? "" : $response = curl_exec($curl);
        ($vtransfer_balance < $my_amount) ? "" : $rubbies_generate = json_decode($response, true);

        if($rubbies_generate['responsecode'] == "00" && $vtransfer_balance >= $my_amount){

            $transaction_id = $rubbies_generate['reference'];
            $datetime = date("Y-m-d h:i:s");

            //UPDATE WAITING TXT
            mysqli_query($link, "UPDATE api_txtwaitinglist SET status = '$response' WHERE userid = '$vuid' AND refid = '$transaction_id' AND status = '$mytxtstatus'");
        
            $service_msg = "Customer Name: ".$cust_name;
            $service_msg .= ($categories == "electricity") ? " | Serial Number: ".$rubbies_generate['pin']['serialNumber']." | " : "";
            $service_msg .= ($categories == "electricity") ? "Pin Code: ".(($rubbies_generate['pin']['pinCode'] == '') ? 'NULL' : $rubbies_generate['pin']['pinCode'])." | " : "";
            $service_msg .= ($categories == "electricity") ? "Units: ".(($rubbies_generate['pin']['units'] == '') ? 'NULL' : $rubbies_generate['pin']['units']) : "";
        
        	$insert = mysqli_query($link, "INSERT INTO wallet_history VALUES(null,'$vcreated_by','$transaction_id','$customerid - $cust_name','','$amount','Debit','$vcurrency','$pcode - WEB','$service_msg','successful','$datetime','$vuid','$senderBalance2','')");
        	
        	$insert = mysqli_query($link, "INSERT INTO wallet_history VALUES(null,'$vcreated_by','$transaction_id','$customerid - $cust_name','$commission','','Credit','$vcurrency','$pcode - WEB','$service_msg','successful','$datetime','$vuid','$senderBalance2','')");
        
		    echo "<script>alert('Payment made successfully!'); </script>";
		    echo "<script>window.location='pay_rubiesbpay.php?id=".$_SESSION['tid']."&&mid=NDA0'; </script>";

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