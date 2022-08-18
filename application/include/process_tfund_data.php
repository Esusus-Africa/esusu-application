<div class="box">
         <div class="box-body">
      <div class="panel panel-success">
            <div class="panel-heading bg-blue">
            <h3 class="panel-title"> <a href="mywallet.php?id=<?php echo $_SESSION['tid']; ?>&&mid=NDA0&&tab=tab_3"><button type="button" class="btn btn-flat bg-orange"><i class="fa fa-mail-reply-all"></i>&nbsp;Back</button></a> <a href="process_tfund.php?id=<?php echo $_SESSION['tid']; ?>&&mid=NDA0&&refid=<?php echo $_GET['refid']; ?>&&uid=<?php echo $_GET['uid']; ?>"><button type="button" class="btn btn-flat bg-orange"><i class="fa fa-refresh"></i>&nbsp;Refresh</button></a> <i class="fa fa-mobile"></i> Process Fund</h3>
            </div>
            
             <div class="box-body">
          
       <form class="form-horizontal" method="post" enctype="multipart/form-data">

<?php
if(isset($_POST['csave']))
{
    function myreference($limit)
    {
        return substr(base_convert(sha1(uniqid(mt_rand())), 16, 36), 0, $limit);
    }
    
    // Function to check string starting 
    // with given substring 
    function startsWith ($string, $startString) 
    { 
        $len = strlen($startString); 
        return (substr($string, 0, $len) === $startString); 
    }
  
    $userid = $_GET['uid'];
    $refid = $_GET['refid'];
    $amount = mysqli_real_escape_string($link, $_POST['amount']);
    $status = mysqli_real_escape_string($link, $_POST['status']);
    $remark = mysqli_real_escape_string($link, $_POST['remark']);
    $date_time = date("Y-m-d");
    $final_date_time = date ('Y-m-d h:i:s', strtotime($date_time));
    $tpin = mysqli_real_escape_string($link, $_POST['tpin']);
    
    $search_cbalance = mysqli_query($link, "SELECT * FROM user WHERE userid = '$userid'");
    $fetch_cbalance = mysqli_fetch_object($search_cbalance);
    $cust_wallet_balance = $fetch_cbalance->transfer_balance;
    $credited_amt = $cust_wallet_balance + $amount;
    //$ph = $fetch_cbalance->phone;
    $em = $fetch_cbalance->email;
    $myname = $fetch_cbalance->name;
    $companyid = $fetch_cbalance->created_by;
    $myrole = $fetch_cbalance->role;
    $myw_balance = $fetch_cbalance->wallet_balance;
    
    $search_memset = mysqli_query($link, "SELECT * FROM member_settings WHERE companyid = '$companyid'");
    $fetch_memset = mysqli_fetch_array($search_memset);
    $subagent_wallet = ($fetch_memset['subagent_wallet'] == "") ? "No" : $fetch_memset['subagent_wallet'];
    
    $search_fw = mysqli_query($link, "SELECT * FROM wallet_history WHERE recipient = '$userid' AND refid = '$refid'");
    $search_fw = mysqli_fetch_array($search_fw);
    $fw_remark = $search_fw['card_bank_details'];
    $final_remark = $fw_remark.' Comment:' . $remark; 
      
    if($tpin != $control_pin)
    {
	    echo "<script>alert('Oops! Invalid Transaction Pin!'); </script>";
    }
    elseif(($status == "declined" && $myrole == "institution_super_admin" && startsWith($companyid,"INST") && ($subagent_wallet == "Yes" || $subagent_wallet == "No")) || ($status == "declined" && $myrole != "institution_super_admin" && startsWith($companyid,"INST") && $subagent_wallet == "No")){
        
        $search_inst = mysqli_query($link, "SELECT * FROM institution_data WHERE institution_id = '$companyid'");
        $fetch_inst = mysqli_fetch_array($search_inst);
        $inst_walletb = $fetch_inst['wallet_balance'];
        $reversed_bal = $inst_walletb + $amount;
        
        $update = mysqli_query($link, "UPDATE institution_data SET wallet_balance = '$reversed_bal' WHERE institution_id = '$companyid'") or die (mysqli_error($link));
        $insert = mysqli_query($link, "UPDATE wallet_history SET card_bank_details = '$final_remark', status = '$status', date_time = '$final_date_time' WHERE recipient = '$userid' AND refid = '$refid'") or die (mysqli_error($link));
        
        echo "<div align='center'><img src='../image/checkmark.gif'><p style='color: #38A1F3;'>Transaction Declined Successfully!</p></div>";
        
    }
    elseif(($status == "declined" && $myrole == "agent_manager" && startsWith($companyid,"AGT") && ($subagent_wallet == "Yes" || $subagent_wallet == "No")) || ($status == "declined" && $myrole != "agent_manager" && startsWith($companyid,"AGT") && $subagent_wallet == "No")){
        
        $search_agt = mysqli_query($link, "SELECT * FROM agent_data WHERE agentid = '$companyid'");
        $fetch_agt = mysqli_fetch_array($search_agt);
        $agt_walletb = $fetch_agt['wallet_balance'];
        $reversed_bal = $agt_walletb + $amount;
        
        $update = mysqli_query($link, "UPDATE agent_data SET wallet_balance = '$reversed_bal' WHERE agentid = '$companyid'") or die (mysqli_error($link));
        $insert = mysqli_query($link, "UPDATE wallet_history SET card_bank_details = '$final_remark', status = '$status', date_time = '$final_date_time' WHERE recipient = '$userid' AND refid = '$refid'") or die (mysqli_error($link));
        
        echo "<div align='center'><img src='../image/checkmark.gif'><p style='color: #38A1F3;'>Transaction Declined Successfully!</p></div>";
        
    }
    elseif(($status == "declined" && $myrole == "merchant_super_admin" && startsWith($companyid,"MER") && ($subagent_wallet == "Yes" || $subagent_wallet == "No")) || ($status == "declined" && $myrole != "merchant_super_admin" && startsWith($companyid,"MER") && $subagent_wallet == "No")){
        
        $search_mer = mysqli_query($link, "SELECT * FROM merchant_reg WHERE merchantID = '$companyid'");
        $fetch_mer = mysqli_fetch_array($search_mer);
        $mer_walletb = $fetch_mer['wallet_balance'];
        $reversed_bal = $mer_walletb + $amount;
        
        $update = mysqli_query($link, "UPDATE merchant_reg SET wallet_balance = '$reversed_bal' WHERE merchantID = '$companyid'") or die (mysqli_error($link));
        $insert = mysqli_query($link, "UPDATE wallet_history SET card_bank_details = '$final_remark', status = '$status', date_time = '$final_date_time' WHERE recipient = '$userid' AND refid = '$refid'") or die (mysqli_error($link));
        
        echo "<div align='center'><img src='../image/checkmark.gif'><p style='color: #38A1F3;'>Transaction Declined Successfully!</p></div>";
        
    }
    elseif($status == "declined" && $subagent_wallet == "Yes" && ($myrole != "institution_super_admin" || $myrole != "merchant_super_admin" || $myrole != "agent_manager")){
        
        $reversed_bal = $myw_balance + $amount;
        
        $update = mysqli_query($link, "UPDATE user SET wallet_balance = '$reversed_bal' WHERE userid = '$userid'") or die (mysqli_error($link));
        $insert = mysqli_query($link, "UPDATE wallet_history SET card_bank_details = '$final_remark', status = '$status', date_time = '$final_date_time' WHERE recipient = '$userid' AND refid = '$refid'") or die (mysqli_error($link));
        
        echo "<div align='center'><img src='../image/checkmark.gif'><p style='color: #38A1F3;'>Transaction Declined Successfully!</p></div>";
        
    }
    elseif($status == "successful"){
      
        $update = mysqli_query($link, "UPDATE user SET transfer_balance = '$credited_amt' WHERE userid = '$userid'") or die (mysqli_error($link));
        $insert = mysqli_query($link, "UPDATE wallet_history SET card_bank_details = '$final_remark', status = '$status', date_time = '$final_date_time' WHERE recipient = '$userid' AND refid = '$refid'") or die (mysqli_error($link));
      
        //include("email_sender/send_sp2tbapproval_alertemail.php");
        echo "<div align='center'><img src='../image/checkmark.gif'><p style='color: #38A1F3;'>Transaction Successful!</p><p style='color: #38A1F3;'>Current Wallet Balance: <b style='color: orange;'>".$currency.number_format($totalwallet_balance,2,'.',',')."</b></p></div>";
        
    }
}
?>

             <div class="box-body">

<?php
$userid = $_GET['uid'];
$refid = $_GET['refid'];
$search_fuser = mysqli_query($link, "SELECT * FROM wallet_history WHERE recipient = '$userid' AND refid = '$refid'");
$search_fuser = mysqli_fetch_array($search_fuser);
?>
      <div class="form-group">
            <label for="" class="col-sm-2 control-label" style="color:blue">Amount</label>
            <div class="col-sm-10">
              <input name="amount" type="text" class="form-control" value="<?php echo $search_fuser['amount']; ?>" placeholder="Enter Amount to be Fund" readonly>
            </div>
      </div>
      
      <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Transaction Pin</label>
                  <div class="col-sm-10">
                  <input name="tpin" type="password" class="form-control" maxlength="4" placeholder="Transaction Pin" required>
                  </div>
                  </div>
                  
        <div class="form-group">
            <label for="" class="col-sm-2 control-label" style="color:blue">Status</label>
            <div class="col-sm-10">
              <select name="status" class="form-control" required>
                  <option value="" selected>Select Status</option>
                  <option value="successful">Approve</option>
                  <option value="declined">Decline</option>
              </select>   
            </div>
      </div>
      
      <div class="form-group">
                <label for="" class="col-sm-2 control-label" style="color:blue;">Remark</label>
                <div class="col-sm-10">
                <textarea name="remark"  class="form-control" rows="2" cols="80" placeholder="e.g Request Approved"></textarea>
                </div>
             </div>
      
  </div>

        <div align="right">
          <div class="box-footer">
              <button name="FundWallet" type="submit" class="btn bg-blue btn-flat"><i class="fa fa-forward">&nbsp;Query</i></button>
          </div>
        </div>
        
       </form>

</div>  
</div>  
</div>
</div>