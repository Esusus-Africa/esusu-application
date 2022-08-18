<?php
$search_vaccount = mysqli_query($link, "SELECT * FROM virtual_account WHERE userid = '$acctno' AND gateway_name = 'walletafrica' AND status = 'ACTIVE'");
if(mysqli_num_rows($search_vaccount) == 0){
    
    //include("../config/walletafrica_restfulapis_call.php");
    
    $result = array();
    $result2 = array();
    $fname = $myfn;
    $lname = $myln;
    $customerEmail = $email2;
    $secretKey = $walletafrica_skey;
    $dob = $dateofbirth;
    
    if($bvirtual_phone_no != ""){
        
        //Getting Already Created Wallet Africa Account
        $search_restapi2 = mysqli_query($link, "SELECT * FROM restful_apisetup WHERE api_name = 'walletafrica_retrievenuban'");
      	$fetch_restapi2 = mysqli_fetch_object($search_restapi2);
      	$api_url2 = $fetch_restapi2->api_url;
    
    	$postdata2 =  array(
    		"phoneNumber" => $bvirtual_phone_no,
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
    	    
    	    mysqli_query($link, "INSERT INTO virtual_account VALUES(null,'$bvirtual_phone_no','$acctno','$accountName','$accountNumber','$bankName','ACTIVE','$datetime','walletafrica')");
    	    mysqli_query($link, "UPDATE borrowers SET virtual_number = '$bvirtual_phone_no' WHERE account = '$acctno'");
    	    
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
    		"currency"=> $bbcurrency,
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
    	    
    	    mysqli_query($link, "INSERT INTO virtual_account VALUES(null,'$unique_phone_no','$acctno','$accountName','$accountNumber','$bankName','ACTIVE','$datetime','walletafrica')");
    	    mysqli_query($link, "UPDATE borrowers SET virtual_number = '$unique_phone_no' WHERE account = '$acctno'");
    	    
    	    $link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; 
        	echo '<meta http-equiv="refresh" content="1;url='.$link.'">';
    	    //echo '<meta http-equiv="refresh" content="1;url=dashboard.php?tid='.$_SESSION['tid'].'&&acn='.$acctno.'&&mid=NDAx">';
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
    
    //($walletafrica_status === "waPending") ? "<script>window.location='verify_waaccount.php?tid=".$_SESSION['tid']."&&acn=".$_SESSION['acctno']."&&mid=NDAx';</script>" : "";
?>
    <address>
          <table>
            <tr>
              <td height="30px"><b style="color: <?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>; font-size: 12px;">Bank Name: </b></td>
              <td height="30px"><b style="color: <?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>; font-size:14px;">&nbsp;<?php echo strtoupper($bank_name) ?></b></td>
            </tr>
            <tr>
              <td height="30px"><b style="color: <?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>; font-size: 12px;">Account Name: </b></td>
              <td height="30px"><b style="color: <?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>; font-size:13px;">&nbsp;<?php echo strtoupper($account_name); ?> </b></td>
            </tr>
            <tr>
              <td height="30px"><b style="color: <?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>; font-size: 12px;">Account No.: </b></td>
              <td height="30px"><b style="color: <?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>; font-size:20px;">&nbsp;<?php echo $account_number; ?></b></td>
            </tr>
          </table>
    </address>
<?php
}
?>