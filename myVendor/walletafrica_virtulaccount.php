<?php
$search_vaccount = mysqli_query($link, "SELECT * FROM virtual_account WHERE userid = '$vuid' AND gateway_name = 'walletafrica' AND status = 'ACTIVE'");
if(mysqli_num_rows($search_vaccount) == 0){
    
    //include("../config/walletafrica_restfulapis_call.php");
    
    $result = array();
    $result2 = array();
    $fullname_splitter = (explode(' ',$vname));
    $fname = $fullname_splitter[0];
    $lname = $fullname_splitter[1];
    $customerEmail = $vo_email;
    $secretKey = $walletafrica_skey;
    $dob = "1985-10-10";
    
    if($ivirtual_phone_no != ""){
        
        //Getting Already Created Wallet Africa Account
        $search_restapi2 = mysqli_query($link, "SELECT * FROM restful_apisetup WHERE api_name = 'walletafrica_retrievenuban'");
      	$fetch_restapi2 = mysqli_fetch_object($search_restapi2);
      	$api_url2 = $fetch_restapi2->api_url;
    
    	$postdata2 =  array(
    		"phoneNumber" => $vvirtual_phone_no,
    		"secretKey" => $secretKey
    	);
    					
    	$make_call2 = callAPI('POST', $api_url2, json_encode($postdata2));
    	$result2 = json_decode($make_call2, true);    
    	
    	//print_r($result2);
    	
    	if($result2['Response']['ResponseCode'] === "200"){
	    
    	    $accountNumber = $result2['Data']['AccountNumber'];
    	    $accountName = $result2['Data']['AccountName'];
    	    $bankName = $result2['Data']['Bank'];
    	    $datetime = date("Y-m-d H:i:s");
    	    
    	    mysqli_query($link, "INSERT INTO virtual_account VALUES(null,'$vvirtual_phone_no','$vuid','$accountName','$accountNumber','$bankName','ACTIVE','$datetime','walletafrica')");
    	    mysqli_query($link, "UPDATE user SET virtual_number = '$vvirtual_phone_no', virtual_acctno = '$accountNumber' WHERE id = '$vuid'");
    	    
    	    $link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; 
    	    echo '<meta http-equiv="refresh" content="1;url='.$link.'">';
    	    //echo "<script>window.location='verify_waaccount.php?tid=".$_SESSION['tid']."&&acn=".$_SESSION['acctno']."&&mid=NDAx';</script>";
    	    
    	}
    	else{
    	    echo "";
    	}
        
    }
    else{
        
        //Creating Wallet Africa Account
        $search_restapi = mysqli_query($link, "SELECT * FROM restful_apisetup WHERE api_name = 'walletafrica_virtualacct'");
      	$fetch_restapi = mysqli_fetch_object($search_restapi);
      	$api_url = $fetch_restapi->api_url;
    
    	$postdata =  array(
    		"firstName" => $fname,
    		"lastName" 	=> $lname,
    		"email"     => $customerEmail,
    		"currency"=> $vcurrency,
    		"secretKey" => $secretKey,
    		"dateOfBirth" => $dob
    	);
    					
    	$make_call = callAPI('POST', $api_url, json_encode($postdata));
    	$result = json_decode($make_call, true);    
    	
    	//print_r($result);
    	
    	//echo $result['Response']['ResponseCode'];
    	
    	if($result['Response']['ResponseCode'] === "200"){
    	    
    	    $accountNumber = $result['Data']['AccountNo'];
    	    $accountName = $result['Data']['AccountName'];
    	    $bankName = $result['Data']['Bank'];
    	    $unique_phone_no = $result['Data']['PhoneNumber'];
    	    $datetime = date("Y-m-d H:i:s");
    	    
    	    mysqli_query($link, "INSERT INTO virtual_account VALUES(null,'$unique_phone_no','$vuid','$accountName','$accountNumber','$bankName','ACTIVE','$datetime','walletafrica')");
    	    mysqli_query($link, "UPDATE user SET virtual_number = '$unique_phone_no', virtual_acctno = '$accountNumber' WHERE id = '$vuid'");
    	    
    	    $link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; 
    	    echo '<meta http-equiv="refresh" content="1;url='.$link.'">';
    	    //echo "<script>window.location='verify_waaccount.php?tid=".$_SESSION['tid']."&&acn=".$_SESSION['acctno']."&&mid=NDAx';</script>";
    	    
    	}
    	else{
    	    echo "";
    	}
    	
    }

}
else{
    $fetch_vaccount = mysqli_fetch_array($search_vaccount);
    
    //WALLET AFRICA ACCOUNT DETAILS
    $my_gateway = $fetch_vaccount['gateway_name'];
    $bank_name = $fetch_vaccount['bank_name'];
    $account_number = $fetch_vaccount['account_number'];
    $account_name = $fetch_vaccount['account_name'];
    
    echo "<b>".strtoupper('Transfer Account')."</b>: <i class='fa fa-hand-o-right'></i>&nbsp;&nbsp;[<b>".strtoupper($bank_name)."</b> | ACCOUNT NAME: <b>".strtoupper($account_name)."</b> | ACCOUNT NO: <b>".strtoupper($account_number)."</b>]";
    
    //($walletafrica_status === "waPending") ? "<script>window.location='verify_waaccount.php?tid=".$_SESSION['tid']."&&acn=".$_SESSION['acctno']."&&mid=NDAx';</script>" : "";
}
?>