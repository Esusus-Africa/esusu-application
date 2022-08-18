<div class="row">
	      <section class="content">
        
       <div class="box box-default">
           <div class="box-body">
             <div class="table-responsive"> 
            <div class="box-body">
            <a href="mywallet.php?id=<?php echo $_SESSION['tid']; ?>&&mid=NDA0"><button type="button" class="btn btn-flat bg-orange"><i class="fa fa-mail-reply-all"></i>&nbsp;Back</button> </a> 

            <div class="col-md-14">
            <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
             <li <?php echo ($_GET['tab'] == 'tab_1') ? "class='active'" : ''; ?>><a href="fund_wallet.php?id=<?php echo $_SESSION['tid']; ?>&&mid=NDA0&&tab=tab_1">Fund Individual / Transfer Wallet</a></li>

              <li <?php echo ($_GET['tab'] == 'tab_3') ? "class='active'" : ''; ?>><a href="fund_wallet.php?id=<?php echo $_SESSION['tid']; ?>&&mid=NDA0&&tab=tab_3">Fund Corporate Wallet</a></li>
              
              <li <?php echo ($_GET['tab'] == 'tab_6') ? "class='active'" : ''; ?>><a href="fund_wallet.php?id=<?php echo $_SESSION['tid']; ?>&&mid=NDA0&&tab=tab_6">Fund Vendor Wallet</a></li>

              <li <?php echo ($_GET['tab'] == 'tab_5') ? "class='active'" : ''; ?>><a href="fund_wallet.php?id=<?php echo $_SESSION['tid']; ?>&&mid=NDA0&&tab=tab_5">Fund Cooperative Wallet</a></li>
              </ul>
             <div class="tab-content">

<?php
if(isset($_GET['tab']) == true)
{
  $tab = $_GET['tab'];
  if($tab == 'tab_1')
  {
  ?>
             <div class="tab-pane <?php echo ($_GET['tab'] == 'tab_1') ? 'active' : ''; ?>" id="tab_1">

             <div class="box-body">
           
			 <form class="form-horizontal" method="post" enctype="multipart/form-data">
<?php
if(isset($_POST['csave']))
{
    //include("../config/walletafrica_restfulapis_call.php");
    $ttype = mysqli_real_escape_string($link, $_POST['ttype']);
    $ptype = ($ttype == "Credit" ? "p2p-transfer" : ($ttype == "Debit" || $ttype == "Overdraft" ? "p2p-debit" : "p2p-reversal"));
    $account =  mysqli_real_escape_string($link, $_POST['author']);
    $amount = preg_replace("/[^0-9]/", "", mysqli_real_escape_string($link, $_POST['amount']));
    $currency = mysqli_real_escape_string($link, $_POST['currency']);
    $remark = mysqli_real_escape_string($link, $_POST['remark']);
    $remark .= " Posted by:".$name;
    $date_time = date("Y-m-d");
    $final_date_time = date ('Y-m-d H:i:s');
    $txid = ($ttype == "Credit" ? 'EA-p2pCR-'.date("dy").time() : ($ttype == "Debit" ? 'EA-p2pDR-'.date("dy").time() : 'EA-p2pRV-'.date("dy").time()));
    $tpin = mysqli_real_escape_string($link, $_POST['tpin']);

    $search_sys = mysqli_query($link, "SELECT * FROM systemset") or die("Error:" . mysqli_error($link));
    $get_sys = mysqli_fetch_array($search_sys);
    $ocurrency = $get_sys['currency'];
    
    //Customer Details
    $search_cbalance = mysqli_query($link, "SELECT * FROM borrowers WHERE virtual_number = '$account'");
    $fetch_cbalance = mysqli_fetch_array($search_cbalance);
    $mycnum = mysqli_num_rows($search_cbalance);
    $myccum_phone = $fetch_cbalance['phone'];
    $myccum_emil = $fetch_cbalance['email'];
    $myccum_fullname = $fetch_cbalance['lname'].' '.$fetch_cbalance['fname'];
    $myccum_vacctnumber = $fetch_cbalance['virtual_acctno'];
    $myccum_acctid = $fetch_cbalance['account'];
    $myccum_wbal = $fetch_cbalance['wallet_balance'];
    
    //Agent / Institution / Merchant Details
    $search_myibalance = mysqli_query($link, "SELECT * FROM user WHERE virtual_number = '$account'");
    $fetch_myibalance = mysqli_fetch_array($search_myibalance);
    $myinum = mysqli_num_rows($search_myibalance);
    $myi_phone = $fetch_myibalance['phone'];
    $myi_email = $fetch_myibalance['email'];
    $myi_name = $fetch_myibalance['name'];
    $myi_vacctnumber = $fetch_myibalance['virtual_acctno'];
    $myi_id = $fetch_myibalance['id'];
    $myi_wbal = $fetch_myibalance['transfer_balance'];
    
    //Detect Right User
    $ph = ($mycnum == 1 && $myinum == 0) ? $myccum_phone : $myi_phone;
    $em = ($mycnum == 1 && $myinum == 0) ? $myccum_emil : $myi_email;
    $myname = ($mycnum == 1 && $myinum == 0) ? $myccum_fullname : $myi_name;
    $receiverVAAcctNo = ($mycnum == 1 && $myinum == 0) ? $myccum_vacctnumber : $myi_vacctnumber;
    $receiverAcctId = ($mycnum == 1 && $myinum == 0) ? $myccum_acctid : $myi_id;
    $detectRightReceiver = ($mycnum == 1 && $myinum == 0) ? "Customer" : "Institution";
    
    //Balance Checking
    $cust_wallet_balance = ($mycnum == 1 && $myinum == 0) ? $myccum_wbal : $myi_wbal;
    $credited_amt = $cust_wallet_balance + $amount;
    $debited_amt = $cust_wallet_balance - $amount;
    $totalwallet_balance = ($ttype === "Credit") ? $credited_amt : $debited_amt;
      
    if($amount <= 0){
        echo "<div class='alert bg-orange'>Oops! Invalid amount entered, please try again later!!</div>";
    }
    elseif($tpin != $control_pin)
    {
        echo "<div class='alert bg-orange'>Oops! Invalid Transaction Pin!</div>";
    }
    elseif(($ttype === "Debit" || $ttype === "Reverse") && $amount > $cust_wallet_balance)
    {
        echo "<div class='alert bg-orange'>Oops! Insufficient balance in customer wallet</div>";
    }
    elseif(($ttype === "Debit" || $ttype === "Reverse") && $amount < $cust_wallet_balance)
    {            
        //Sender Parameters
        $amountDebited = $amount;
        $newBalance = $totalwallet_balance;
            
        ($detectRightReceiver == "Customer") ? $update = mysqli_query($link, "UPDATE borrowers SET wallet_balance = '$newBalance' WHERE virtual_number = '$account'") or die (mysqli_error($link)) : "";
        ($detectRightReceiver == "Institution") ? $update = mysqli_query($link, "UPDATE user SET transfer_balance = '$newBalance' WHERE virtual_number = '$account'") or die (mysqli_error($link)) : "";
        $insert = mysqli_query($link, "INSERT INTO wallet_history VALUES(null,'','$txid','$receiverAcctId','','$amountDebited','Debit','$currency','$ptype','$remark','successful','$final_date_time','$uid','','$newBalance')") or die (mysqli_error($link));
            
        //include("alert_sender/p2p_alert.php");
        include("email_sender/send_sp2ptransfer_alertemail.php");
        echo "<div align='center'><img src='../image/checkmark//.gif'><p style='color: #38A1F3;'>Transaction Successful!</p><p style='color: #38A1F3;'>Current Wallet Balance: <b style='color: orange;'>".$currency.number_format($totalwallet_balance,2,'.',',')."</b></p></div>";
        //echo '<meta http-equiv="refresh" content="5;url=fund_wallet.php?id='.$_SESSION['tid'].'&&mid=NDA0&&tab=tab_1">';
    }
    else{
        //Receivers Parameters
        $amountCredited = $amount;
        $receiverBalance = $totalwallet_balance;
            
        ($detectRightReceiver == "Customer") ? $update = mysqli_query($link, "UPDATE borrowers SET wallet_balance = '$receiverBalance' WHERE virtual_number = '$account'") or die (mysqli_error($link)) : "";
        ($detectRightReceiver == "Institution") ? $update = mysqli_query($link, "UPDATE user SET transfer_balance = '$receiverBalance' WHERE virtual_number = '$account'") or die (mysqli_error($link)) : "";
        $insert = mysqli_query($link, "INSERT INTO wallet_history VALUES(null,'','$txid','$receiverAcctId','$amountCredited','','Credit','$currency','$ptype','$remark','successful','$final_date_time','$uid','','$receiverBalance')") or die (mysqli_error($link));
            
        //include("alert_sender/p2p_alert.php");
        include("email_sender/send_sp2ptransfer_alertemail.php");
        echo "<div align='center'><img src='../image/checkmark.gif'><p style='color: #38A1F3;'>Transaction Successful!</p><p style='color: #38A1F3;'>Current Wallet Balance: <b style='color: orange;'>".$currency.number_format($totalwallet_balance,2,'.',',')."</b></p></div>";
        //echo '<meta http-equiv="refresh" content="5;url=fund_wallet.php?id='.$_SESSION['tid'].'&&mid=NDA0&&tab=tab_1">';
    }

}
?>
             <div class="box-body">
      
      <div class="form-group">
            <label for="" class="col-sm-3 control-label" style="color:blue;">Transaction Type:</label>
            <div class="col-sm-6">
              <select name="ttype" class="form-control select2" required>
                <option value="" selected>Select Transaction Type</option>
                <?php echo ($backend_add_fund == "1") ? '<option value="Credit">Credit</option>' : ''; ?>
                <?php echo ($backend_withdraw_from_wallet == "1") ? '<option value="Debit">Debit</option>' : ''; ?>
                <?php echo ($backend_withdraw_from_wallet == "1") ? '<option value="Reverse">Reverse</option>' : ''; ?>
                <?php echo ($backend_withdraw_from_wallet == "1") ? '<option value="Overdraft">Overdraft</option>' : ''; ?>
              </select>
            </div>
            <label for="" class="col-sm-3 control-label"></label>
      </div>
                  
                  
      <div class="form-group">
            <label for="" class="col-sm-3 control-label" style="color:blue;">Wallet Account:</label>
            <div class="col-sm-6">
                  <?php
                    $uid = $_GET['uid'];
                    $searchVA = mysqli_query($link, "SELECT * FROM virtual_account WHERE account_number = '$uid'");
                    $fetchVA = mysqli_fetch_array($searchVA);
                    if(isset($_GET['uid'])){
                    ?>
                      <input name="author" type="hidden" class="form-control" value="<?php echo $uid; ?>">
                      <input name="myauthor" type="text" class="form-control" value="<?php echo $fetchVA['account_name']; ?>" readonly required>
                    <?php
                    }
                    else{
                    ?>
                      <select name="author"  class="form-control select2" required>
                        <option selected>Select Customer Account</option>
                        <option disabled>Filter By Customer Wallet Account</option>
                          <?php
                          $search = mysqli_query($link, "SELECT * FROM borrowers WHERE virtual_number != '' AND virtual_acctno != ''");
                          while($get_search = mysqli_fetch_array($search))
                          {
                          ?>
                              <option value="<?php echo $get_search['virtual_number']; ?>"><?php echo $get_search['virtual_acctno']; ?>&nbsp; <?php echo $get_search['fname'].' '.$get_search['lname'].' '.$get_search['mname']; ?> : [<?php echo number_format($get_search['wallet_balance'],2,'.',','); ?>]</option>
                          <?php } ?>
                          
                          <option disabled>Filter By Institution / Agent / Merchant Wallet Account</option>
                          <?php
                          $search1 = mysqli_query($link, "SELECT * FROM user WHERE virtual_number != '' AND virtual_acctno != ''");
                          while($get_search1 = mysqli_fetch_array($search1))
                          {
                            $vaAcctNo = $get_search1['virtual_acctno'];
                            $mysearchVA = mysqli_query($link, "SELECT * FROM virtual_account WHERE account_number = '$vaAcctNo'");
                            $myfetchVA = mysqli_fetch_array($mysearchVA);
                          ?>
                                    <option value="<?php echo $get_search1['virtual_number']; ?>"><?php echo $vaAcctNo . ' - ' .$myfetchVA['account_name']; ?> : [<?php echo number_format($get_search1['transfer_balance'],2,'.',','); ?>]</option>
                          <?php } ?>
                      </select>
                    <?php
                    }
                    ?>
            </div>
            <label for="" class="col-sm-3 control-label"></label>
      </div>
      

      <div class="form-group">
            <label for="" class="col-sm-3 control-label" style="color:blue;">Currency:</label>
            <div class="col-sm-6">
              <select name="currency" class="form-control select2" required>
                <option selected>Select Currency</option>
                <option value="NGN">NGN</option>
                <option value="USD">USD</option>
                <option value="GBP">GBP</option>
                <option value="EUR">EUR</option>
                <option value="AUD">AUD</option>
                <option value="GHS">GHS</option>
                <option value="KES">KES</option>
                <option value="UGX">UGX</option>
                <option value="TZS">TZS</option>
              </select>
            </div>
            <label for="" class="col-sm-3 control-label"></label>
      </div>
      
      <div class="form-group">
            <label for="" class="col-sm-3 control-label" style="color:blue;">Amount to Fund:</label>
            <div class="col-sm-6">
                  <input name="amount" type="number" class="form-control" placeholder="Enter Amount Here" required>
                  </div>
            <label for="" class="col-sm-3 control-label"></label>
      </div>
                  
        <div class="form-group">
            <label for="" class="col-sm-3 control-label" style="color:blue;">Transaction Pin:</label>
            <div class="col-sm-6">
                  <input name="tpin" type="password" class="form-control" maxlength="4" placeholder="Transaction Pin" required>
            </div>
            <label for="" class="col-sm-3 control-label"></label>
      </div>

        <div class="form-group">
            <label for="" class="col-sm-3 control-label" style="color:blue;">Remark:</label>
            <div class="col-sm-6">
                <textarea name="remark"  class="form-control" rows="2" cols="80" placeholder="e.g Manual Funding to Wallet from Admin"></textarea>
               </div>
            <label for="" class="col-sm-3 control-label"></label>
      </div>

       </div>
       
        <div class="form-group" align="right">
            <label for="" class="col-sm-3 control-label" style="color:blue;"></label>
            <div class="col-sm-6">
                <button name="csave" type="submit" class="btn bg-blue"><i class="fa fa-save">&nbsp;Submit</i></button>
            </div>
            <label for="" class="col-sm-3 control-label"></label>
        </div>    
      
       </form>  

      </div>
    </div>
      <!-- /.tab-pane -->
  

  <?php
  }
  elseif($tab == 'tab_3')
  {
  ?> 

      <div class="tab-pane <?php echo ($_GET['tab'] == 'tab_3') ? 'active' : ''; ?>" id="tab_3">

        <div class="box-body">

          <form class="form-horizontal" method="post" enctype="multipart/form-data">
<?php
if(isset($_POST['isave']))
{
  function myreference($limit)
  {
    return substr(base_convert(sha1(uniqid(mt_rand())), 16, 36), 0, $limit);
  }
  try{
    $ttype = mysqli_real_escape_string($link, $_POST['ttype']);
    $ptype = ($ttype == "Credit" ? "p2p-transfer" : ($ttype == "Debit" || $ttype == "Overdraft" ? "p2p-debit" : "p2p-reversal"));
    $account =  mysqli_real_escape_string($link, $_POST['author']);
    $amount = mysqli_real_escape_string($link, $_POST['amount']);
    $currency = mysqli_real_escape_string($link, $_POST['currency']);
    $remark = mysqli_real_escape_string($link, $_POST['remark']);
    $remark .= " Posted by: ".$name;
    $date_time = date("Y-m-d");
    $final_date_time = date ('Y-m-d h:i:s', strtotime($date_time));
    $txid = ($ttype == "Credit" ? 'EA-p2pFunding-'.myreference(10) : ($ttype == "Debit" ? 'EA-p2pDebit-'.myreference(10) : 'EA-p2pReversal-'.myreference(10)));
    $tpin = mysqli_real_escape_string($link, $_POST['tpin']);

    $search_sys = mysqli_query($link, "SELECT * FROM systemset") or die("Error:" . mysqli_error($link));
    $get_sys = mysqli_fetch_array($search_sys);
    
    $search_ibalance = mysqli_query($link, "SELECT * FROM institution_data WHERE institution_id = '$account'");
    $fetch_ibalance = mysqli_fetch_object($search_ibalance);
    $inst_wallet_balance = $fetch_ibalance->wallet_balance;
    $credited_amt = $inst_wallet_balance + $amount;
    $debited_amt = $inst_wallet_balance - $amount;
    $totalwallet_balance = ($ttype === "Credit") ? $credited_amt : $debited_amt;
    $ph = $fetch_ibalance->official_phone;
    $em = $fetch_ibalance->official_email;
    $myname = $fetch_ibalance->institution_name;
      
    if($amount < 0){
      throw new UnexpectedValueException();
    }
    elseif(preg_match('/^[0-9]+(?:\.[0-9]{0,2})?$/', $amount) == FALSE)
    {
      throw new UnexpectedValueException();
    }
    elseif($tpin != $control_pin)
    {
	    echo "<script>alert('Oops! Invalid Transaction Pin!'); </script>";
    }
    elseif(($ttype === "Debit" || $ttype === "Reverse") && $amount > $inst_wallet_balance)
    {
         echo "<script>alert('Oops! Insufficient balance in institution wallet'); </script>";
    }
    else{

      $update = mysqli_query($link, "UPDATE institution_data SET wallet_balance = '$totalwallet_balance' WHERE institution_id = '$account'") or die (mysqli_error($link));
      ($ttype === "Credit") ? $insert = mysqli_query($link, "INSERT INTO wallet_history VALUES(null,'','$txid','$account','$amount','','Credit','$currency','$ptype','$remark','successful','$final_date_time','$uid','','$totalwallet_balance')") or die (mysqli_error($link)) : "";
      ($ttype === "Debit" || $ttype === "Reverse") ? $insert = mysqli_query($link, "INSERT INTO wallet_history VALUES(null,'','$txid','$account','','$amount','Debit','$currency','$ptype','$remark','successful','$final_date_time','$uid','','$totalwallet_balance')") or die (mysqli_error($link)) : "";
      if(!($update && $insert))
      {
        echo "<div class='alert bg-orange'>Unable to Transfer Fund.....Please try again later</div>";
      }
      else{
        //include("alert_sender/p2p_alert.php");
        include("email_sender/send_sp2ptransfer_alertemail.php");
        echo "<div align='center'><img src='../image/checkmark.gif'><p style='color: #38A1F3;'>Transaction Successful!</p><p style='color: #38A1F3;'>Current Wallet Balance: <b style='color: orange;'>".$currency.number_format($totalwallet_balance,2,'.',',')."</b></p></div>";
        echo '<meta http-equiv="refresh" content="5;url=fund_wallet.php?id='.$_SESSION['tid'].'&&mid=NDA0&&tab=tab_3">';
      }

    }
  }catch(UnexpectedValueException $ex)
  {
    echo "<div class='alert alert-danger'>Invalid Amount Entered!</div>";
  }
}
?>
             <div class="box-body">
      
      <div class="form-group">
            <label for="" class="col-sm-3 control-label" style="color:blue;">Transaction Type:</label>
            <div class="col-sm-6">
              <select name="ttype" class="form-control select2" required>
                <option value="" selected>Select Transaction Type</option>
                <?php echo ($backend_add_fund == "1") ? '<option value="Credit">Credit</option>' : ''; ?>
                <?php echo ($backend_withdraw_from_wallet == "1") ? '<option value="Debit">Debit</option>' : ''; ?>
                <?php echo ($backend_withdraw_from_wallet == "1") ? '<option value="Reverse">Reverse</option>' : ''; ?>
                <?php echo ($backend_withdraw_from_wallet == "1") ? '<option value="Overdraft">Overdraft</option>' : ''; ?>
              </select>
            </div>
            <label for="" class="col-sm-3 control-label"></label>
      </div>
                  
      <div class="form-group">
            <label for="" class="col-sm-3 control-label" style="color:blue;">Account ID:</label>
            <div class="col-sm-6">
                <select name="author"  class="form-control select2" required>
                  <option selected>Select Institution / Agent / Merchant Account</option>
                    <?php
                    $search = mysqli_query($link, "SELECT * FROM institution_data WHERE status = 'Approved'");
                    while($get_search = mysqli_fetch_array($search))
                    {
                      $theinst = $get_search['institution_id'];
                      $lookup_inst = mysqli_query($link, "SELECT * FROM member_settings WHERE companyid = '$theinst'");
                      while($fetch_inst = mysqli_fetch_array($lookup_inst))
                      {
                    ?>
                  <option value="<?php echo $get_search['institution_id']; ?>"><?php echo $get_search['institution_id']; ?>&nbsp; [<?php echo $get_search['institution_name']; ?> : <?php echo $fetch_inst['currency'].number_format($get_search['wallet_balance'],2,'.',','); ?>]</option>
                    <?php } } ?>
                </select>
            </div>
            <label for="" class="col-sm-3 control-label"></label>
      </div>

      <div class="form-group">
            <label for="" class="col-sm-3 control-label" style="color:blue;">Currency:</label>
            <div class="col-sm-6">
              <select name="currency" class="form-control select2" required>
                <option selected>Select Currency</option>
                <option value="NGN">NGN</option>
                <option value="USD">USD</option>
                <option value="GBP">GBP</option>
                <option value="EUR">EUR</option>
                <option value="AUD">AUD</option>
                <option value="GHS">GHS</option>
                <option value="KES">KES</option>
                <option value="UGX">UGX</option>
                <option value="TZS">TZS</option>
              </select>
            </div>
            <label for="" class="col-sm-3 control-label"></label>
      </div>
      
      <div class="form-group">
            <label for="" class="col-sm-3 control-label" style="color:blue;">Amount to Fund:</label>
            <div class="col-sm-6">
                  <input name="amount" type="number" class="form-control" placeholder="Enter Amount Here" required>
            </div>
            <label for="" class="col-sm-3 control-label"></label>
      </div>
                  
        <div class="form-group">
            <label for="" class="col-sm-3 control-label" style="color:blue;">Transaction Pin:</label>
            <div class="col-sm-6">
                  <input name="tpin" type="password" class="form-control" maxlength="4" placeholder="Transaction Pin" required>
            </div>
            <label for="" class="col-sm-3 control-label"></label>
      </div>

        <div class="form-group">
            <label for="" class="col-sm-3 control-label" style="color:blue;">Remark:</label>
            <div class="col-sm-6">
                <textarea name="remark"  class="form-control" rows="2" cols="80" placeholder="e.g Manual Funding to Wallet from Admin"></textarea>
            </div>
            <label for="" class="col-sm-3 control-label"></label>
      </div>

       </div>
       
        <div class="form-group" align="right">
            <label for="" class="col-sm-3 control-label" style="color:blue;"></label>
            <div class="col-sm-6">
                <button name="isave" type="submit" class="btn bg-blue"><i class="fa fa-save">&nbsp;Submit</i></button>
            </div>
            <label for="" class="col-sm-3 control-label"></label>
      </div>    
      
       </form> 

      </div>
    </div>
    

  <?php
  }
  elseif($tab == 'tab_5')
  {
  ?> 

      <div class="tab-pane <?php echo ($_GET['tab'] == 'tab_5') ? 'active' : ''; ?>" id="tab_5">

        <div class="box-body">

          <form class="form-horizontal" method="post" enctype="multipart/form-data">
<?php
if(isset($_POST['cosave']))
{
  function myreference($limit)
  {
    return substr(base_convert(sha1(uniqid(mt_rand())), 16, 36), 0, $limit);
  }
  try{
    $ttype = mysqli_real_escape_string($link, $_POST['ttype']);
    $ptype = ($ttype == "Credit" ? "p2p-transfer" : ($ttype == "Debit" || $ttype == "Overdraft" ? "p2p-debit" : "p2p-reversal"));
    $account =  mysqli_real_escape_string($link, $_POST['author']);
    $amount = mysqli_real_escape_string($link, $_POST['amount']);
    $currency = mysqli_real_escape_string($link, $_POST['currency']);
    $remark = mysqli_real_escape_string($link, $_POST['remark']);
    $remark .= " Posted by: ".$name;
    $date_time = date("Y-m-d");
    $final_date_time = date ('Y-m-d h:i:s', strtotime($date_time));
    $txid = ($ttype == "Credit" ? 'EA-p2pFunding-'.myreference(10) : ($ttype == "Debit" ? 'EA-p2pDebit-'.myreference(10) : 'EA-p2pReversal-'.myreference(10)));
    $tpin = mysqli_real_escape_string($link, $_POST['tpin']);

    $search_sys = mysqli_query($link, "SELECT * FROM systemset") or die("Error:" . mysqli_error($link));
    $get_sys = mysqli_fetch_array($search_sys);
    
    $search_cobalance = mysqli_query($link, "SELECT * FROM cooperatives WHERE coopid = '$account'");
    $fetch_cobalance = mysqli_fetch_object($search_cobalance);
    $coop_wallet_balance = $fetch_cobalance->wallet_balance;
    $credited_amt = $coop_wallet_balance + $amount;
    $debited_amt = $coop_wallet_balance - $amount;
    $totalwallet_balance = ($ttype === "Credit") ? $credited_amt : $debited_amt;
    $ph = $fetch_cobalance->official_phone;
    $em = $fetch_cobalance->official_email;
    $myname = $fetch_cobalance->coopname;
      
    if($amount < 0){
      throw new UnexpectedValueException();
    }
    elseif(preg_match('/^[0-9]+(?:\.[0-9]{0,2})?$/', $amount) == FALSE)
    {
      throw new UnexpectedValueException();
    }
    elseif($tpin != $control_pin)
    {
	    echo "<script>alert('Oops! Invalid Transaction Pin!'); </script>";
    }
    elseif(($ttype === "Debit" || $ttype === "Reverse") && $amount > $coop_wallet_balance)
    {
         echo "<script>alert('Oops! Insufficient balance in cooperative wallet'); </script>";
    }
    else{

      $update = mysqli_query($link, "UPDATE cooperatives SET wallet_balance = '$totalwallet_balance' WHERE coopid = '$account'") or die (mysqli_error($link));
      ($ttype === "Credit") ? $insert = mysqli_query($link, "INSERT INTO wallet_history VALUES(null,'','$txid','$account','$amount','','Credit','$currency','$ptype','$remark','successful','$final_date_time','$uid','','$totalwallet_balance')") or die (mysqli_error($link)) : "";
      ($ttype === "Debit" || $ttype === "Reverse") ? $insert = mysqli_query($link, "INSERT INTO wallet_history VALUES(null,'','$txid','$account','','$amount','Debit','$currency','$ptype','$remark','successful','$final_date_time','$uid','','$totalwallet_balance')") or die (mysqli_error($link)) : "";
      if(!($update && $insert))
      {
        echo "<div class='alert bg-orange'>Unable to Transfer Fund.....Please try again later</div>";
      }
      else{
        //include("alert_sender/p2p_alert.php");
        include("email_sender/send_sp2ptransfer_alertemail.php");
        echo "<div align='center'><img src='../image/checkmark.gif'><p style='color: #38A1F3;'>Transaction Successful!</p><p style='color: #38A1F3;'>Current Wallet Balance: <b style='color: orange;'>".$currency.number_format($totalwallet_balance,2,'.',',')."</b></p></div>";
        echo '<meta http-equiv="refresh" content="5;url=fund_wallet.php?id='.$_SESSION['tid'].'&&mid=NDA0&&tab=tab_5">';
      }
    }
  }catch(UnexpectedValueException $ex)
  {
    echo "<div class='alert alert-danger'>Invalid Amount Entered!</div>";
  }
}
?>
             <div class="box-body">
      
      <div class="form-group">
            <label for="" class="col-sm-3 control-label" style="color:blue;">Transaction Type:</label>
            <div class="col-sm-6">
              <select name="ttype" class="form-control select2" required>
                <option value="" selected>Select Transaction Type</option>
                <?php echo ($backend_add_fund == "1") ? '<option value="Credit">Credit</option>' : ''; ?>
                <?php echo ($backend_withdraw_from_wallet == "1") ? '<option value="Debit">Debit</option>' : ''; ?>
                <?php echo ($backend_withdraw_from_wallet == "1") ? '<option value="Reverse">Reverse</option>' : ''; ?>
                <?php echo ($backend_withdraw_from_wallet == "1") ? '<option value="Overdraft">Overdraft</option>' : ''; ?>
              </select>
            </div>
            <label for="" class="col-sm-3 control-label"></label>
      </div>
                  
      <div class="form-group">
            <label for="" class="col-sm-3 control-label" style="color:blue;">Account ID:</label>
            <div class="col-sm-6">
                <select name="author"  class="form-control select2" required>
                  <option selected>Select Cooperative Account</option>
                    <?php
                    $search = mysqli_query($link, "SELECT * FROM cooperatives WHERE status = 'Approved'");
                    while($get_search = mysqli_fetch_array($search))
                    {
                      $thecoop = $get_search['coopid'];
                      $lookup_coop = mysqli_query($link, "SELECT * FROM member_settings WHERE companyid = '$thecoop'");
                      while($fetch_coop = mysqli_fetch_array($lookup_coop))
                      {
                    ?>
                        <option value="<?php echo $get_search['coopid']; ?>"><?php echo $get_search['coopid']; ?>&nbsp; [<?php echo $get_search['coopname']; ?> : <?php echo $fetch_coop['currency'].number_format($get_search['wallet_balance'],2,'.',','); ?>]</option>
                    <?php } } ?>
                </select>
            </div>
            <label for="" class="col-sm-3 control-label"></label>
      </div>

      <div class="form-group">
            <label for="" class="col-sm-3 control-label" style="color:blue;">Currency:</label>
            <div class="col-sm-6">
              <select name="currency" class="form-control select2" required>
                <option selected>Select Currency</option>
                <option value="NGN">NGN</option>
                <option value="USD">USD</option>
                <option value="GBP">GBP</option>
                <option value="EUR">EUR</option>
                <option value="AUD">AUD</option>
                <option value="GHS">GHS</option>
                <option value="KES">KES</option>
                <option value="UGX">UGX</option>
                <option value="TZS">TZS</option>
              </select>
            </div>
            <label for="" class="col-sm-3 control-label"></label>
      </div>
      
      <div class="form-group">
            <label for="" class="col-sm-3 control-label" style="color:blue;">Amount to Fund:</label>
            <div class="col-sm-6">
                  <input name="amount" type="number" class="form-control" placeholder="Enter Amount Here" required>
            </div>
            <label for="" class="col-sm-3 control-label"></label>
      </div>
                  
        <div class="form-group">
            <label for="" class="col-sm-3 control-label" style="color:blue;">Transaction Pin:</label>
            <div class="col-sm-6">
                  <input name="tpin" type="password" class="form-control" maxlength="4" placeholder="Transaction Pin" required>
            </div>
            <label for="" class="col-sm-3 control-label"></label>
      </div>

        <div class="form-group">
            <label for="" class="col-sm-3 control-label" style="color:blue;">Remark:</label>
            <div class="col-sm-6">
                <textarea name="remark"  class="form-control" rows="2" cols="80" placeholder="e.g Manual Funding to Wallet from Admin"></textarea>
            </div>
            <label for="" class="col-sm-3 control-label"></label>
      </div>

       </div>
       
        <div class="form-group" align="right">
            <label for="" class="col-sm-3 control-label" style="color:blue;"></label>
            <div class="col-sm-6">
                <button name="cosave" type="submit" class="btn bg-blue"><i class="fa fa-save">&nbsp;Submit</i></button>
            </div>
            <label for="" class="col-sm-3 control-label"></label>
      </div>   
      
       </form> 

      </div>
    </div>

  <?php
  }
elseif($tab == 'tab_6')
  {
  ?>

      <div class="tab-pane <?php echo ($_GET['tab'] == 'tab_6') ? 'active' : ''; ?>" id="tab_6">

        <div class="box-body">
           
       <form class="form-horizontal" method="post" enctype="multipart/form-data">
<?php
if(isset($_POST['vendsave']))
{
  function myreference($limit)
  {
    return substr(base_convert(sha1(uniqid(mt_rand())), 16, 36), 0, $limit);
  }
  try{
    $ttype = mysqli_real_escape_string($link, $_POST['ttype']);
    $ptype = ($ttype == "Credit" ? "p2p-transfer" : ($ttype == "Debit" || $ttype == "Overdraft" ? "p2p-debit" : "p2p-reversal"));
    $account =  mysqli_real_escape_string($link, $_POST['vendor']);
    $amount = mysqli_real_escape_string($link, $_POST['amount']);
    $currency = mysqli_real_escape_string($link, $_POST['currency']);
    $remark = mysqli_real_escape_string($link, $_POST['remark']);
    $remark .= " Posted by: ".$name;
    $date_time = date("Y-m-d");
    $final_date_time = date ('Y-m-d h:i:s', strtotime($date_time));
    $txid = ($ttype == "Credit" ? 'EA-p2pFunding-'.myreference(10) : ($ttype == "Debit" ? 'EA-p2pDebit-'.myreference(10) : 'EA-p2pReversal-'.myreference(10)));
    $tpin = mysqli_real_escape_string($link, $_POST['tpin']);

    $search_sys = mysqli_query($link, "SELECT * FROM systemset") or die("Error:" . mysqli_error($link));
    $get_sys = mysqli_fetch_array($search_sys);
    
    $search_abalance = mysqli_query($link, "SELECT * FROM vendor_reg WHERE companyid = '$account'");
    $fetch_abalance = mysqli_fetch_object($search_abalance);
    $vendor_wallet_balance = $fetch_abalance->wallet_balance;
    $credited_amt = $vendor_wallet_balance + $amount;
    $debited_amt = $vendor_wallet_balance - $amount;
    $totalwallet_balance = ($ttype === "Credit") ? $credited_amt : $debited_amt;
    $ph = $fetch_abalance->cphone;
    $em = $fetch_abalance->cemail;
    $myname = $fetch_abalance->cname;
      
    if($amount < 0){
      throw new UnexpectedValueException();
    }
    elseif(preg_match('/^[0-9]+(?:\.[0-9]{0,2})?$/', $amount) == FALSE)
    {
      throw new UnexpectedValueException();
    }
    elseif($tpin != $control_pin){
	    echo "<script>alert('Oops! Invalid Transaction Pin!'); </script>";
    }
    elseif(($ttype === "Debit" || $ttype === "Reverse") && $amount > $vendor_wallet_balance)
    {
         echo "<script>alert('Oops! Insufficient balance in vendor wallet'); </script>";
    }
    else{
        
        $update = mysqli_query($link, "UPDATE vendor_reg SET wallet_balance = '$totalwallet_balance' WHERE companyid = '$account'") or die (mysqli_error($link));
        ($ttype === "Credit") ? $insert = mysqli_query($link, "INSERT INTO wallet_history VALUES(null,'','$txid','$account','$amount','','Credit','$currency','$ptype','$remark','successful','$final_date_time','$uid','','$totalwallet_balance')") or die (mysqli_error($link)) : "";
        ($ttype === "Debit" || $ttype === "Reverse") ? $insert = mysqli_query($link, "INSERT INTO wallet_history VALUES(null,'','$txid','$account','','$amount','Debit','$currency','$ptype','$remark','successful','$final_date_time','$uid','','$totalwallet_balance')") or die (mysqli_error($link)) : "";
        if(!($update && $insert))
        {
            echo "<div class='alert bg-orange'>Unable to Transfer Fund.....Please try again later</div>";
        }
        else{
            //include("alert_sender/p2p_alert.php");
            include("email_sender/send_sp2ptransfer_alertemail.php");
            echo "<div align='center'><img src='../image/checkmark.gif'><p style='color: #38A1F3;'>Transaction Successful!</p><p style='color: #38A1F3;'>Current Wallet Balance: <b style='color: orange;'>".$currency.number_format($totalwallet_balance,2,'.',',')."</b></p></div>";
            echo '<meta http-equiv="refresh" content="5;url=fund_wallet.php?id='.$_SESSION['tid'].'&&mid=NDA0&&tab=tab_6">';
        }
            
    }
  }catch(UnexpectedValueException $ex)
  {
    echo "<div class='alert alert-danger'>Invalid Amount Entered!</div>";
  }
}
?>
             <div class="box-body">
                 
      <div class="form-group">
            <label for="" class="col-sm-3 control-label" style="color:blue;">Transaction Type:</label>
            <div class="col-sm-6">
              <select name="ttype" class="form-control select2" required>
                <option value="" selected>Select Transaction Type</option>
                <?php echo ($backend_add_fund == "1") ? '<option value="Credit">Credit</option>' : ''; ?>
                <?php echo ($backend_withdraw_from_wallet == "1") ? '<option value="Debit">Debit</option>' : ''; ?>
                <?php echo ($backend_withdraw_from_wallet == "1") ? '<option value="Reverse">Reverse</option>' : ''; ?>
                <?php echo ($backend_withdraw_from_wallet == "1") ? '<option value="Overdraft">Overdraft</option>' : ''; ?>
              </select>
            </div>
            <label for="" class="col-sm-3 control-label"></label>
      </div>
      
      <div class="form-group">
            <label for="" class="col-sm-3 control-label" style="color:blue;">Vendor:</label>
            <div class="col-sm-6">
                <select name="vendor"  class="form-control select2" required>
                  <option value="" selected>Select Vendor Account</option>
                    <?php
                    $search = mysqli_query($link, "SELECT * FROM user WHERE role = 'vendor_manager'");
                    while($get_search = mysqli_fetch_array($search))
                    {
                        $VENDORid = $get_search['branchid'];
                        $search_vendor = mysqli_query($link, "SELECT * FROM vendor_reg WHERE companyid = '$VENDORid'");
                        $fetch_vendor = mysqli_fetch_array($search_vendor);
                    ?>
                        <option value="<?php echo $VENDORid; ?>"><?php echo $fetch_vendor['cname']; ?> [<?php echo $fetch_vendor['currency'].number_format($fetch_vendor['wallet_balance'],2,'.',','); ?>]</option>
                    <?php } ?>
                </select>
            </div>
            <label for="" class="col-sm-3 control-label"></label>
      </div>
            
      <div class="form-group">
            <label for="" class="col-sm-3 control-label" style="color:blue;">Currency:</label>
            <div class="col-sm-6">
              <select name="currency" class="form-control select2" required>
                <option selected>Select Currency</option>
                <option value="NGN">NGN</option>
                <option value="USD">USD</option>
                <option value="GBP">GBP</option>
                <option value="EUR">EUR</option>
                <option value="AUD">AUD</option>
                <option value="GHS">GHS</option>
                <option value="KES">KES</option>
                <option value="UGX">UGX</option>
                <option value="TZS">TZS</option>
              </select>
            </div>
            <label for="" class="col-sm-3 control-label"></label>
      </div>
      
      <div class="form-group">
            <label for="" class="col-sm-3 control-label" style="color:blue;">Amount to Fund:</label>
            <div class="col-sm-6">
                  <input name="amount" type="number" class="form-control" placeholder="Enter Amount Here">
            </div>
            <label for="" class="col-sm-3 control-label"></label>
      </div>
                  
        <div class="form-group">
            <label for="" class="col-sm-3 control-label" style="color:blue;">Transaction Pin:</label>
            <div class="col-sm-6">
                  <input name="tpin" type="password" class="form-control" maxlength="4" placeholder="Transaction Pin" required>
            </div>
            <label for="" class="col-sm-3 control-label"></label>
        </div>

        <div class="form-group">
            <label for="" class="col-sm-3 control-label" style="color:blue;">Remark:</label>
            <div class="col-sm-6">
                <textarea name="remark"  class="form-control" rows="2" cols="80" placeholder="e.g Manual Funding to Wallet from Admin"></textarea>
            </div>
            <label for="" class="col-sm-3 control-label"></label>
        </div>

       </div>
       
       <div class="form-group" align="right">
            <label for="" class="col-sm-3 control-label" style="color:blue;"></label>
            <div class="col-sm-6">
                <button name="vendsave" type="submit" class="btn bg-blue"><i class="fa fa-save">&nbsp;Submit</i></button>
            </div>
            <label for="" class="col-sm-3 control-label"></label>
      </div>
      
       </form> 

      </div>
    </div>

  <?php
  }
}
  ?>
	
</div>	
</div>
</div>
</div>
</div>
</div>
</div>
</section>
</div>