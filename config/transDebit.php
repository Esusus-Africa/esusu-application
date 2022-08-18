<?php
error_reporting(0);
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header("Access-Control-Allow-Methods: POST");

include("connect.php");

function sendSms($sender, $phone, $msg, $debug=false)
{
  global $gateway_uname,$gateway_pass,$gateway_api;

  $url = 'username='.$gateway_uname;
  $url.= '&password='.$gateway_pass;
  $url.= '&sender='.urlencode($sender);
  $url.= '&recipient='.urlencode($phone);
  $url.= '&message='.urlencode($msg);

  $urltouse =  $gateway_api.$url;
  //if ($debug) { echo "Request: <br>$urltouse<br><br>"; }

  //Open the URL to send the message
  $response3 = file_get_contents($urltouse);
  if ($debug) {
      //echo "Response: <br><pre>".
      //str_replace(array("<",">"),array("&lt;","&gt;"),$response3).
      //"</pre><br>"; 
  }
  return($response3);
}

function ccMasking($number, $maskingCharacter = '*') {
    return substr($number, 0, 3) . str_repeat($maskingCharacter, strlen($number) - 6) . substr($number, -3);
}

if($_SERVER['REQUEST_METHOD'] === "POST"){
    
    // Retrieve the request's body
	$input = @file_get_contents("php://input");
	
    $response = json_decode($input);

    $accountNo = $response->account;
    $amount = $response->amount;
    $charge = $response->charge;
    $reference = $response->reference;
    $narration = $response->narration;
    $amtWithCharges = $amount + $charge;
    $final_date_time = date("Y-m-d h:i:s");
    $DateTime = date('m/d/Y').' '.(date(h) + 1).':'.date('i A');

    $verifyTrans = mysqli_query($link, "SELECT * FROM wallet_history WHERE refid = '$reference'");
    $numTrans = mysqli_num_rows($verifyTrans);

    if($numTrans == 0){

        //User Verification
        $search_cbalance = mysqli_query($link, "SELECT * FROM borrowers WHERE virtual_acctno = '$accountNo'");
        $fetch_cbalance = mysqli_fetch_array($search_cbalance);
        $mycnum = mysqli_num_rows($search_cbalance);
        $myccum_phone = $fetch_cbalance['phone'];
        $myccum_emil = $fetch_cbalance['email'];
        $myccum_fullname = $fetch_cbalance['fname'].' '.$fetch_cbalance['lname'].' '.$fetch_cbalance['mname'];
        $myccum_balance = $fetch_cbalance['wallet_balance'];
        $bbranchid = $fetch_cbalance['branchid'];
        $myccum_acctid = $fetch_cbalance['account'];
        
        $search_myibalance = mysqli_query($link, "SELECT * FROM user WHERE virtual_acctno = '$accountNo'");
        $fetch_myibalance = mysqli_fetch_array($search_myibalance);
        $myinum = mysqli_num_rows($search_myibalance);
        $myi_phone = $fetch_myibalance['phone'];
        $myi_email = $fetch_myibalance['email'];
        $myi_name = $fetch_myibalance['name'].' '.$fetch_myibalance['lname'].' '.$fetch_myibalance['mname'];
        $myi_balance = $fetch_myibalance['transfer_balance'];
        $instid = $fetch_myibalance['created_by'];
        $myi_id = $fetch_myibalance['id'];
        
        //Detect Right User
        $ph = ($mycnum == 1 && $myinum == 0) ? $myccum_phone : $myi_phone;
        $em = ($mycnum == 1 && $myinum == 0) ? $myccum_emil : $myi_email;
        $myname = ($mycnum == 1 && $myinum == 0) ? $myccum_fullname : $myi_name;
        $userBalance = ($mycnum == 1 && $myinum == 0) ? $myccum_balance : $myi_balance;
        $detectRightUser = ($mycnum == 1 && $myinum == 0) ? "Customer" : "Institution";
        $institution_id = ($mycnum == 1 && $myinum == 0) ? $bbranchid : $instid;
        $receiverAcctId = ($mycnum == 1 && $myinum == 0) ? $myccum_acctid : $myi_id;

        $searchMemSet = mysqli_query($link, "SELECT * FROM member_settings WHERE companyid = '$institution_id'");
        $fetchMemSet = mysqli_fetch_array($searchMemSet);
        $currency = $fetchMemSet['currency'];
        $isenderid =  $fetchMemSet['sender_id'];
        $merchantName = $fetchMemSet['cname'];

        if($mycnum == 0 && $myinum == 0){

            $arr = array(
                "status"=>"notfound",
                "statuscode"=>"96",
                "reference"=>$reference,
                "account"=>$accountNo,
                "amount"=>$amount,
                "charge"=>$charge
            );
        
            echo json_encode($arr);

        }
        elseif($amtWithCharges > $userBalance){

            $arr = array(
                "status"=>"Declined",
                "statuscode"=>"99",
                "reference"=>$reference,
                "account"=>$accountNo,
                "amount"=>$amount,
                "charge"=>$charge
            );
        
            echo json_encode($arr);
        }
        else{

            $updatedBalance = $userBalance - $amtWithCharges;

            $sms = "$sysabb>>>DR";
            $sms .= " Amt: ".$currency.number_format($amount,2,'.',',')."";
            $sms .= " Charges: ".$currency.number_format($charge,2,'.',',')."";
            $sms .= " ID: ".ccMasking($accountNo)."";
            $sms .= " Desc: Card withdrawal | ".$reference."";
            $sms .= " Time: ".$DateTime."";
            $sms .= " Bal: ".$currency.number_format($updatedBalance,2,'.',',')."";

            $debug = true;
            sendSms($isenderid,$ph,$sms,$debug);

            ($detectRightUser == "Customer") ? $update = mysqli_query($link, "UPDATE borrowers SET wallet_balance = '$updatedBalance' WHERE virtual_acctno = '$accountNo'") or die ("Error: " . mysqli_error($link)) : "";
            ($detectRightUser == "Institution") ? $update = mysqli_query($link, "UPDATE user SET transfer_balance = '$updatedBalance' WHERE virtual_acctno = '$accountNo'") or die ("Error: " . mysqli_error($link)) : "";
            $insert = mysqli_query($link, "INSERT INTO wallet_history VALUES(null,'$institution_id','$reference','Debit Card Withdrawal','','$amtWithCharges','Debit','$currency','Card_Withdrawal','$narration','successful','$final_date_time','$receiverAcctId','$updatedBalance','')") or die ("Error: " . mysqli_error($link));

            include("cardEmailDebitNotifier.php");

            $arr = array(
                "status"=>"success",
                "statuscode"=>"00",
                "reference"=>$reference,
                "account"=>$accountNo,
                "amount"=>$amount,
                "charge"=>$charge
            );
        
            echo json_encode($arr);

        }

    }
    else{

        //Duplicate Reference Error
        $arr = array(
                "status"=>"failed - duplicate reference",
                "statuscode"=>"01",
                "reference"=>$reference,
                "account"=>$accountNo,
                "amount"=>$amount,
                "charge"=>$charge
            );
        
        echo json_encode($arr);

    }
    
}
?>