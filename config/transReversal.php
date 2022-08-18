<?php
error_reporting(0);
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header("Access-Control-Allow-Methods: POST");

include("connect.php");

if($_SERVER['REQUEST_METHOD'] === "POST"){
    
    // Retrieve the request's body
	$input = @file_get_contents("php://input");
	
    $response = json_decode($input);

    $reference = $response->reference;
    $final_date_time = date("Y-m-d h:i:s");
    $DateTime = date('m/d/Y').' '.(date(h) + 1).':'.date('i A');

    $verifyTrans = mysqli_query($link, "SELECT * FROM wallet_history WHERE refid = '$reference' AND status = 'successful'");
    $numTrans = mysqli_num_rows($verifyTrans);
    $fetchTrans = mysqli_fetch_array($verifyTrans);
    $debitAmt = $fetchTrans['debit'];
    $accountNo = $fetchTrans['initiator'];

    if($numTrans == 1){

        //User Verification
        $search_cbalance = mysqli_query($link, "SELECT * FROM borrowers WHERE account = '$accountNo'");
        $fetch_cbalance = mysqli_fetch_array($search_cbalance);
        $mycnum = mysqli_num_rows($search_cbalance);
        $myccum_emil = $fetch_cbalance['email'];
        $myccum_fullname = $fetch_cbalance['fname'].' '.$fetch_cbalance['lname'].' '.$fetch_cbalance['mname'];
        $myccum_balance = $fetch_cbalance['wallet_balance'];
        $bbranchid = $fetch_cbalance['branchid'];
        $myccum_acctid = $fetch_cbalance['account'];
        
        $search_myibalance = mysqli_query($link, "SELECT * FROM user WHERE id = '$accountNo'");
        $fetch_myibalance = mysqli_fetch_array($search_myibalance);
        $myinum = mysqli_num_rows($search_myibalance);
        $myi_email = $fetch_myibalance['email'];
        $myi_name = $fetch_myibalance['name'].' '.$fetch_myibalance['lname'].' '.$fetch_myibalance['mname'];
        $myi_balance = $fetch_myibalance['transfer_balance'];
        $instid = $fetch_myibalance['created_by'];
        $myi_id = $fetch_myibalance['id'];
        
        //Detect Right User
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
                "status"=>"failed - account does not exist",
                "statuscode"=>"96"
            );
        
            echo json_encode($arr);

        }
        else{

            $updatedBalance = $userBalance + $debitAmt;
            $newref = $reference.date("dis");

            ($detectRightUser == "Customer") ? $update = mysqli_query($link, "UPDATE borrowers SET wallet_balance = '$updatedBalance' WHERE account = '$accountNo'") or die ("Error: " . mysqli_error($link)) : "";
            ($detectRightUser == "Institution") ? $update = mysqli_query($link, "UPDATE user SET transfer_balance = '$updatedBalance' WHERE id = '$accountNo'") or die ("Error: " . mysqli_error($link)) : "";
            $update = mysqli_query($link, "UPDATE wallet_history SET status = 'reversed' WHERE refid = '$reference'") or die ("Error: " . mysqli_error($link));
            $insert = mysqli_query($link, "INSERT INTO wallet_history VALUES(null,'$institution_id','$newref','Reversal on Card Withdrawal','$amtWithCharges','','Credit','$currency','Card_Reversal','$narration','successful','$final_date_time','$receiverAcctId','$updatedBalance','')") or die ("Error: " . mysqli_error($link));

            include("cardEmailReversalNotifier.php");

            $arr = array(
                "status"=>"reversalsuccess",
                "statuscode"=>"00"
            );
        
            echo json_encode($arr);

        }

    }
    else{
        
        $arr = array(
                "status"=>"failed - transaction not found",
                "statuscode"=>"01"
            );
        
        echo json_encode($arr);
        
    }

}
?>