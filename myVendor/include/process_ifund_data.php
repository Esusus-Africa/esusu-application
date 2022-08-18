<div class="row">
	      <section class="content">
        
       <div class="box box-default">
           <div class="box-body">
             <div class="table-responsive"> 
            <div class="box-body">
            <a href="withdrawal_request.php?tid=<?php echo $_SESSION['tid']; ?>&&mid=NDkw"><button type="button" class="btn btn-flat bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>"><i class="fa fa-mail-reply-all"></i>&nbsp;Back</button></a> 
<button type="button" class="btn btn-flat bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>" align="left" disabled>&nbsp;<b>Transfer Balance:</b>&nbsp;
<strong class="alert bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">
<?php
  echo "<span id='twallet_balance'>".$vcurrency.number_format($vtransfer_balance,2,'.',',')."</span>";
?> 
</strong>
  </button>


        <div class="col-md-14">
            <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
              <li <?php echo ($_GET['tab'] == 'tab_6') ? "class='active'" : ''; ?>><a href="process_ifund.php?id=<?php echo $_SESSION['tid']; ?>&&mid=NDkw&&tokenid=<?php echo $_GET['tokenid']; ?>&&tab=tab_6">Transfer to Investor</a></li>
              </ul>
             <div class="tab-content">

<div class="alert bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>" style="font-size:17px;" align="center">
    <p>
        <?php
        //echo $walletafrica_status;
        if($mo_virtualacct_status == "Active" && ($walletafrica_status == "NotActive" || $walletafrica_status == "Active")){
            
            echo "<b>NOTE</b> that transfer to other peoples wallet within the system comes with Zero charges";
            
        }
        elseif($mo_virtualacct_status == "NotActive" && $walletafrica_status == "Active"){
            
            include("../config/walletafrica_restfulapis_call.php");
            include("walletafrica_virtulaccount.php");
            
        }
        ?>
    </p>
</div>
</hr>


<?php
if(isset($_GET['tab']) == true)
{
  $tab = $_GET['tab'];
  if($tab == 'tab_6')
  {
      $tokenid = $_GET['tokenid'];
      $search_wreq = mysqli_query($link, "SELECT * FROM mcustomer_wrequest WHERE wtokenid = '$tokenid'");
      $fetch_wreq = mysqli_fetch_array($search_wreq);
      $cust_virtual_number = $fetch_wreq['bank_details'];
      $sub_code = $fetch_wreq['subscription_code'];
      $amt_todisburse = $fetch_wreq['amount_requested'];

      $search_mysub = mysqli_query($link, "SELECT * FROM savings_subscription WHERE subscription_code = '$sub_code'");
      $fetch_mysub = mysqli_fetch_array($search_mysub);
      $withdrawal_count = $fetch_mysub['withdrawal_count'];
  ?>

      <div class="tab-pane <?php echo ($_GET['tab'] == 'tab_6') ? 'active' : ''; ?>" id="tab_6">

             <div class="box-body">
           
       <form class="form-horizontal" method="post" enctype="multipart/form-data">

             <div class="box-body">

             <input name="author" type="hidden" class="form-control" value="<?php echo $cust_virtual_number; ?>">

            <div class="form-group">
                <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Amount</label>
                <div class="col-sm-6">
                  <input name="amt_totransfer" type="text" class="form-control" value="<?php echo number_format($amt_todisburse,0,'',','); ?>" readonly>
                </div>
                <label for="" class="col-sm-3 control-label"></label>
            </div>

            <div class="form-group">
                <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Profit</label>
                <div class="col-sm-6">
                  <input name="surcharges" type="text" class="form-control" placeholder="Enter Profit in Flat Amount" required>
                </div>
                <label for="" class="col-sm-3 control-label"></label>
            </div>

            <div class="form-group">
              <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Remark</label>
              <div class="col-sm-6">
                  <textarea name="remark"  class="form-control" rows="2" cols="80" placeholder="e.g p2p transfer to customer"></textarea>
              </div>
              <label for="" class="col-sm-3 control-label"></label>
            </div>

            <div class="form-group">
                <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Transaction Pin</label>
                <div class="col-sm-6">
                  <input name="tpin" type="password" class="form-control" placeholder="Your Transaction Pin" required/>
                </div>
                <label for="" class="col-sm-3 control-label"></label>
            </div>
      
       </div>

       <div class="form-group" align="right">
          <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;"></label>
          <div class="col-sm-6">
          <button name="savedb_save" type="submit" class="btn bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?> btn-flat"><i class="fa fa-forward">&nbsp;Initiate Transfer</i></button>
          </div>
          <label for="" class="col-sm-3 control-label"></label>
        </div>
       
<?php
if(isset($_POST['savedb_save']))
{
    //include("../config/walletafrica_restfulapis_call.php");
    
    $ptype = "p2p-transfer";
    $account =  mysqli_real_escape_string($link, $_POST['author']);
    $amount = preg_replace("/[^0-9]/", "", mysqli_real_escape_string($link, $_POST['amt_totransfer']));
    $surcharges = mysqli_real_escape_string($link, $_POST['surcharges']);
    $totalAmtToCharge = $amount + $surcharges;

    $remark = mysqli_real_escape_string($link, $_POST['remark']);
    $date_time = date("Y-m-d");
    $final_date_time = date('Y-m-d H:i:s');
    $txid = 'EA-p2pFunding-'.time();
    $tpin = mysqli_real_escape_string($link, $_POST['tpin']);

    $search_abalance = mysqli_query($link, "SELECT * FROM borrowers WHERE virtual_number = '$account'");
    $fetch_abalance = mysqli_fetch_object($search_abalance);
    $bwallet_balance = $fetch_abalance->wallet_balance;
    $ph = $fetch_abalance->phone;
    $em = $fetch_abalance->email;
    $myname = $fetch_abalance->lname;
    $acctno = $fetch_abalance->account;
    $virtual_acctno = $fetch_abalance->virtual_acctno;
  
    if($tpin != $myvepin){

      echo "<div class='alert bg-orange'>Oops! Invalid Transaction Pin!!</div>";

    }
    elseif(preg_match('/^[0-9]+(?:\.[0-9]{0,2})?$/', $amount) == FALSE)
    {

      echo "<div class='alert alert-danger'>Oops! Invalid Amount Entered!!</div>";

    }
    elseif($totalAmtToCharge > $vtransfer_balance){
        
      echo "<div class='alert bg-orange'>Oops! You do not have enough fund in your transfer wallet!!</div>";
        
    }
    else{

        //Sender Parameters
        $amountDebited = $totalAmtToCharge;
        $senderBalance = $vtransfer_balance - $totalAmtToCharge;

        //Receivers Parameters
        $amountCredited = $totalAmtToCharge;
        $receiverBalance = $bwallet_balance + $totalAmtToCharge;

        $totalwithdrawal_count = $withdrawal_count + 1;
        
        $investbalDeduction = $fetch_abalance->investment_bal - $amount;

        $update = mysqli_query($link, "UPDATE mcustomer_wrequest SET status = 'Approved' WHERE wtokenid = '$tokenid'");
        $update = mysqli_query($link, "UPDATE savings_subscription SET withdrawal_count = '$totalwithdrawal_count' WHERE subscription_code = '$sub_code'");
        $update = mysqli_query($link, "UPDATE user SET transfer_balance = '$senderBalance' WHERE id = '$vuid'");
        $update = mysqli_query($link, "UPDATE borrowers SET wallet_balance = '$receiverBalance', investment_bal = '$investbalDeduction' WHERE virtual_number = '$account'") or die (mysqli_error($link));
        $insert = mysqli_query($link, "INSERT INTO wallet_history VALUES(null,'$vcreated_by','$txid','$virtual_acctno','','$amountCredited','Debit','$vcurrency','$ptype','$remark','successful','$final_date_time','$vuid','$vtransfer_balance','$bwallet_balance')") or die (mysqli_error($link));

        include("alert_sender/send_sp2ptransfer_alertemail.php");
        echo "<div align='center'><img src='../image/checkmark.gif'><p style='color: #38A1F3;'>Transaction Successful!</p></div>";

    }

}
?>        
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