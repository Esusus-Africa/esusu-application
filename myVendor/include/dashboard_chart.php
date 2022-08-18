<div class="row">
    
<div class="col-md-12">
<div class="slideshow-container">
  <div class="alert bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>" style="font-size:17px;" align="center"><?php echo "<b>".strtoupper('Wallet Account Details')."</b>"; ?>: <i class='fa fa-hand-o-down'></i><br>
<?php
  $detectAcct = $vdefaultAcct;
  $parameter = (explode(',',$detectAcct));
  $countNum = count($parameter);

  for($i = 0; $i < $countNum; $i++){
      
    $mydefaultbank = ($parameter[$i] == "Monnify" ? "monify" : ($parameter[$i] == "Rubies Bank" ? "rubies" : ($parameter[$i] == "Flutterwave" ? "rave" : ($parameter[$i] == "Payant" ? "payant" : ($parameter[$i] == "Providus Bank" ? "providus" : ($parameter[$i] == "Wema Bank" ? "wema" : ($parameter[$i] == "Sterling Bank" ? "sterling" : "None")))))));
    
    $search_vaccount = mysqli_query($link, "SELECT * FROM virtual_account WHERE userid = '$vendorid' AND gateway_name = '$mydefaultbank' AND status = 'ACTIVE'");

    if(mysqli_num_rows($search_vaccount) == 0 && $mydefaultbank != "None"){

      $accountUserId = $vendorid;
      //$accountReference =  "EAVA-".myreference(10);
      $accountName = $vc_name;
      $currencyCode = $vcurrency;
      $customerEmail = $vo_email;
      $customerName = $vc_name;
      $userBvn = "";
      $TxtReference = uniqid('ESFUND').time();
      $phoneNumber = $vo_phone;
      $country = "NG";
      $mydate_time = date("Y-m-d h:i:s");

      require_once '../config/virtualBankAccount_class.php';

      ($parameter[$i] == "Monnify") ? $result = $newVA->monnifyVirtualAccount($accountName,$currencyCode,$customerEmail,$customerName,$userBvn,$mo_contract_code) : "";
      ($parameter[$i] == "Rubies Bank") ? $result1 = $newVA->rubiesVirtualAccount($accountName,$rubbiesSecKey) : "";
      ($parameter[$i] == "Flutterwave") ? $result1 = $newVA->wemaVirtualAccount($accountName,$customerEmail,$TxtReference,$rave_secret_key) : "";
      ($parameter[$i] == "Payant") ? $result1 = $newVA->sterlingPayantVirtualAcct($accountName,$customerEmail,$phoneNumber,$currencyCode,$country,$payantEmail,$payantPwd,$payantOrgId) : "";
      ($parameter[$i] == "Providus Bank") ? $result1 = $newVA->providusVirtualAccount($accountName,$userBvn,$providusClientId,$providusClientSecret) : "";
      ($parameter[$i] == "Wema Bank") ? $result1 = $newVA->directWemaVirtualAccount($accountName,$wemaVAPrefix) : "";
      ($parameter[$i] == "Sterling Bank") ? $result1 = $newVA->sterlingVirtualAccount($vfname,$vlname,$phoneNumber,$vdob,$vgender,$currencyCode,$sterlinkInputKey,$sterlingIv) : "";

      ($parameter[$i] == "Monnify" ? $myAccountReference = $result->responseBody->accountReference : (($parameter[$i] == "Rubies Bank") && $result1['responsecode'] == "00" ? $myAccountReference = "EAVA-".time() : (($parameter[$i] == "Flutterwave") && $result1['data']['response_code'] == "02" ? $myAccountReference = $TxtReference : (($parameter[$i] == "Payant") && $result1['statusCode'] == "200" ? $myAccountReference = $result1['data']['_id'] : (($parameter[$i] == "Providus Bank") && $result1['responseCode'] == "00" ? $myAccountReference = uniqid("EAVA-").time() : (($parameter[$i] == "Wema Bank") && $result1['responseCode'] == "00" ? $myAccountReference = uniqid("EAVA-").time() : (($parameter[$i] == "Sterling Bank") && $result1['response'] == "00" ? $myAccountReference = uniqid("EAVA-").time() : "")))))));
      ($parameter[$i] == "Monnify" ? $myAccountName = $result->responseBody->accountName : (($parameter[$i] == "Rubies Bank") && $result1['responsecode'] == "00" ? $myAccountName = $result1['virtualaccountname'] : (($parameter[$i] == "Flutterwave") && $result1['data']['response_code'] == "02" ? $myAccountName = $accountName : (($parameter[$i] == "Payant") && $result1['statusCode'] == "200" ? $myAccountName = $result1['data']['accountName'] : (($parameter[$i] == "Providus Bank") && $result1['responseCode'] == "00" ? $myAccountName = $result1['account_name'] : (($parameter[$i] == "Wema Bank") && $result1['responseCode'] == "00" ? $myAccountName = $result1['account_name'] : (($parameter[$i] == "Sterling Bank") && $result1['response'] == "00" ? $myAccountName = $result1['data']['lastname'].' '.$result1['data']['firstname'] : "")))))));
      ($parameter[$i] == "Monnify" ? $myAccountNumber = $result->responseBody->accountNumber : (($parameter[$i] == "Rubies Bank") && $result1['responsecode'] == "00" ? $myAccountNumber = $result1['virtualaccount'] : (($parameter[$i] == "Flutterwave") && $result1['data']['response_code'] == "02" ? $myAccountNumber = $result1['data']['account_number'] : (($parameter[$i] == "Payant") && $result1['statusCode'] == "200" ? $myAccountNumber = $result1['data']['accountNumber'] : (($parameter[$i] == "Providus Bank") && $result1['responseCode'] == "00" ? $myAccountNumber = $result1['account_number'] : (($parameter[$i] == "Wema Bank") && $result1['responseCode'] == "00" ? $myAccountNumber = $result1['account_number'] : (($parameter[$i] == "Sterling Bank") && $result1['response'] == "00" ? $myAccountNumber = $result1['data']['VIRTUALACCT'] : "")))))));
      ($parameter[$i] == "Monnify" ? $myBankName = $result->responseBody->bankName : (($parameter[$i] == "Rubies Bank") && $result1['responsecode'] == "00" ? $myBankName = $result1['bankname'] : (($parameter[$i] == "Flutterwave") && $result1['data']['response_code'] == "02" ? $myBankName = $result1['data']['bank_name'] : (($parameter[$i] == "Payant") && $result1['statusCode'] == "200" ? $myBankName = "Sterling Bank" : (($parameter[$i] == "Providus Bank") && $result1['responseCode'] == "00" ? $myBankName = "Providus Bank" : (($parameter[$i] == "Wema Bank") && $result1['responseCode'] == "00" ? $myBankName = "Wema Bank" : (($parameter[$i] == "Sterling Bank") && $result1['response'] == "00" ? $myBankName = "Sterling Bank" : "")))))));
      ($parameter[$i] == "Monnify" ? $myStatus = $result->responseBody->status : (($parameter[$i] == "Rubies Bank") && $result1['responsecode'] == "00" ? $myStatus = "ACTIVE" : (($parameter[$i] == "Flutterwave") && $result1['data']['response_code'] == "02" ? $myStatus = "ACTIVE" : (($parameter[$i] == "Payant") && $result1['statusCode'] == "200" ? $myStatus = $result1['data']['status'] : (($parameter[$i] == "Providus Bank") && $result1['responseCode'] == "00" ? $myStatus = "ACTIVE" : (($parameter[$i] == "Wema Bank") && $result1['responseCode'] == "00" ? $myStatus = "ACTIVE" : (($parameter[$i] == "Sterling Bank") && $result1['response'] == "00" ? $myStatus = "ACTIVE" : "")))))));
      ($parameter[$i] == "Monnify" ? $date_time = $result->responseBody->createdOn : (($parameter[$i] == "Rubies Bank") && $result1['responsecode'] == "00" ? $date_time = date("Y-m-d h:i:s") : (($parameter[$i] == "Flutterwave") && $result1['data']['response_code'] == "02" ? $date_time = date("Y-m-d h:i:s") : (($parameter[$i] == "Payant") && $result1['statusCode'] == "200" ? $date_time = date("Y-m-d h:i:s") : (($parameter[$i] == "Providus Bank") && $result1['responseCode'] == "00" ? $date_time = date("Y-m-d h:i:s") : (($parameter[$i] == "Wema Bank") && $result1['responseCode'] == "00" ? $date_time = date("Y-m-d h:i:s") : (($parameter[$i] == "Sterling Bank") && $result1['response'] == "00" ? $date_time = date("Y-m-d h:i:s") : "")))))));
      ($parameter[$i] == "Monnify" ? $provider = "monify" : (($parameter[$i] == "Rubies Bank") && $result1['responsecode'] == "00" ? $provider = "rubies" : (($parameter[$i] == "Flutterwave") && $result1['data']['response_code'] == "02" ? $provider = "rave" : (($parameter[$i] == "Payant") && $result1['statusCode'] == "200" ? $provider = "payant" : (($parameter[$i] == "Providus Bank") && $result1['responseCode'] == "00" ? $provider = "providus" : (($parameter[$i] == "Wema Bank") && $result1['responseCode'] == "00" ? $provider = "wema" : (($parameter[$i] == "Sterling Bank") && $result1['response'] == "00" ? $provider = "sterling" : "")))))));

      ($myAccountReference != "" || $myAccountName != "" || $myAccountNumber != "" || $myBankName != "" || $myStatus != "") ? mysqli_query($link, "INSERT INTO virtual_account VALUE(null,'$myAccountReference','$accountUserId','$myAccountName','$myAccountNumber','$myBankName','$myStatus','$mydate_time','$provider','$vcreated_by','vendor','Verified','','1000000','100000','10000','5000')") : "";
    
      $link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; 
      echo '<meta http-equiv="refresh" content="1;url='.$link.'">';
    
    }
    elseif($mydefaultbank == "None"){
      
      echo '<div class="mySlides">';
      
      echo "[<b>No Virtual Account is available at the moment... Please check back later</b>]";
      
      echo '</div>';
      
    }
    else{
      while($fetch_vaccount = mysqli_fetch_array($search_vaccount)){
      
        //MONIFY GATEWAY STATUS
        $mo_fund_status = $fetchsys_config['mo_status'];
        
        $my_gateway = $fetch_vaccount['gateway_name'];
        $bank_name = $fetch_vaccount['bank_name'];
        $account_number = $fetch_vaccount['account_number'];
        $account_name = $fetch_vaccount['account_name'];
        
        echo '<div class="mySlides">';

        echo "[<b>".strtoupper($bank_name)."</b> | ACCOUNT NAME: <b>".strtoupper($account_name)."</b> | ACCOUNT NO: <b>".strtoupper($account_number)."</b>]";
        
        echo '</div>';

      }
    }

  }
?>

    <!-- Dots/bullets/indicators -->
    <div class="dot-container">
    <?php
    for($a = 0; $a < $countNum; $a++){
      $i = 1;
      $mydefaultbank2 = ($parameter[$a] == "Monnify" ? "monify" : ($parameter[$a] == "Rubies Bank" ? "rubies" : ($parameter[$a] == "Flutterwave" ? "wema" : ($parameter[$a] == "Payant" ? "sterling" : "None"))));
      $search_myva = mysqli_query($link, "SELECT * FROM virtual_account WHERE userid = '$vendorid' AND gateway_name = '$mydefaultbank2' AND status = 'ACTIVE'");
      while($getnumrow = mysqli_fetch_array($search_myva)){
    ?>
        <span class="dot" onclick="currentSlide(<?php echo $i; ?>)"></span>
    <?php
        $i++;
      }
    }
    ?>
    </div>

          </div>
<a class="myprev" onclick="plusSlides(-1)">&#10094;</a>
<a class="mynext" onclick="plusSlides(1)">&#10095;</a>
</div>
          <!-- /.col -->
          </div>
          </div>
          
<div class="row">
<?php
$check = mysqli_query($link, "SELECT * FROM my_permission WHERE urole = '$vrole'") or die ("Error" . mysqli_error($link));
$get_check = mysqli_fetch_array($check);
$transfer_fund = $get_check['transfer_fund'];
$access_wallet_tab = $get_check['access_wallet_tab'];
if($transfer_fund == '1' || $access_wallet_tab == '1')
{
?>

        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">
              <span class="info-box-icon bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?> elevation-1"><i class="fas fa-binoculars"></i></span>

              <div class="info-box-content">
                <span class="info-box-text"><a href="mywallet.php?tid=<?php echo $_SESSION['tid']; ?>&&mid=NDA0">Wallet Balance <i class="fa fa-arrow-circle-right"></i></a></span>
                <span class="info-box-number">
                  <?php
                      echo "<span id='wallet_balance'>".$vcurrency.number_format($vwallet_balance,2,'.',',')."</span>";
                  ?>
                </span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>

<?php
}
else{
  echo '';
}
?>
        <!-- ./col -->
        
        
        
<?php
$check = mysqli_query($link, "SELECT * FROM my_permission WHERE urole = '$vrole'") or die ("Error" . mysqli_error($link));
$get_check = mysqli_fetch_array($check);
$access_loan_tab = $get_check['access_loan_tab'];
$view_all_loans = $get_check['view_all_loans'];
if($view_all_loans == '1' && $v_ctype == "Onlending Firm")
{
?>
        
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">
              <span class="info-box-icon bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?> elevation-1"><i class="fas fa-american-sign-language-interpreting"></i></span>

              <div class="info-box-content">
                <span class="info-box-text"><?php echo ($view_all_loans == 1) ? '<a href="listloans.php?tid='.$_SESSION['tid'].'&&mid='.base64_encode("405").'">Loan Received <i class="fa fa-arrow-circle-right"></i></a>' : '<a href="#">Loan Received</a>'; ?></span>
                <span class="info-box-number">
                  <?php 
                    $select = mysqli_query($link, "SELECT SUM(amount) FROM loan_info WHERE status = 'Approved' AND vendorid = '$vendorid'") or die (mysqli_error($link));
                    if(mysqli_num_rows($select)==0)
                    {
                    echo "0.00";
                    }
                    else{
                    while($row = mysqli_fetch_array($select))
                    {
                    echo number_format($row['SUM(amount)'],2,".",",")."</b>";
                    }
                    }
                    ?>
                </span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <!-- /.col -->
        
<?php
}
else{
  echo '';
}
?>

<?php
if(mysqli_num_rows($vsearch_maintenance_model) == 1)
{
    echo "";
}
else{
?>
        
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">
              <span class="info-box-icon bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?> elevation-1"><i class="fas fa-globe"></i></span>

              <div class="info-box-content">
                <span class="info-box-text"><?php echo ($view_saas_sub_plan == 1) ? '<a href="paid_sub.php?tid='.$_SESSION['tid'].'&&mid=NDIw">Total Sub. <i class="fa fa-arrow-circle-right"></i></a>' : '<a href="#">Total Sub.</a>'; ?></span>
                <span class="info-box-number">
                  <?php
                    $select = mysqli_query($link, "SELECT * FROM saas_subscription_trans WHERE status = 'Paid' AND coopid_instid = '$vendorid'") or die (mysqli_error($link));
                    $num = mysqli_num_rows($select);
                    echo $num;
                    ?>
                </span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        
        
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">
              <span class="info-box-icon bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?> elevation-1"><i class="fas fa-signal"></i></span>

              <div class="info-box-content">
                <span class="info-box-text"><?php echo ($paid__saas_subscription == 1) ? '<a href="paid_sub.php?tid='.$_SESSION['tid'].'&&mid=NDIw">Total Sub. Amount <i class="fa fa-arrow-circle-right"></i></a>' : '<a href="#">Total Sub. Amount</a>'; ?></span>
                <span class="info-box-number">
                  <?php 
                    $select = mysqli_query($link, "SELECT SUM(amount_paid) FROM saas_subscription_trans WHERE status = 'Paid' AND coopid_instid = '$vendorid'") or die (mysqli_error($link));
                    if(mysqli_num_rows($select)==0)
                    {
                    echo "0.00";
                    }
                    else{
                    while($row = mysqli_fetch_array($select))
                    {
                    echo number_format($row['SUM(amount_paid)'],2,".",",")."</b>";
                    }
                    }
                    ?>
                </span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        
<?php } ?>

<?php
$check = mysqli_query($link, "SELECT * FROM my_permission WHERE urole = '$vrole'") or die ("Error" . mysqli_error($link));
$get_check = mysqli_fetch_array($check);
$access_loan_tab = $get_check['access_loan_tab'];
$view_due_loans = $get_check['view_due_loans'];
$view_due_loans = $get_check['view_due_loans'];
if($view_due_loans == '1' && $v_ctype == "Onlending Firm")
{
?>

    <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">
              <span class="info-box-icon bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?> elevation-1"><i class="fas fa-hand-lizard-o"></i></span>

              <div class="info-box-content">
                <span class="info-box-text"><?php echo ($view_all_loans == 1) ? '<a href="missedpayment.php?tid='.$_SESSION['tid'].'&&mid='.base64_encode("407").'">Loan Due Amount <i class="fa fa-arrow-circle-right"></i></a>' : 'Loan Due Amount'; ?></span>
                <span class="info-box-number">
                  <?php
                    $date_now = date("Y-m-d");
                    $select = mysqli_query($link, "SELECT SUM(payment) FROM pay_schedule WHERE status = 'UNPAID' AND schedule <= '$date_now' AND vendorid = '$vendorid'") or die ("Error: " . mysqli_error($link));
                    if(mysqli_num_rows($select)==0)
                    {
                    echo "0.00";
                    }
                    else{
                    while($row = mysqli_fetch_array($select))
                    {
                    echo number_format($row['SUM(payment)'],2,".",",")."</b>";
                    }
                    }
                    ?>
                </span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <!-- /.col -->

<?php
}
else{
  echo '';
}
?>   
      

<?php
/*$check = mysqli_query($link, "SELECT * FROM my_permission WHERE urole = '$vrole'") or die ("Error" . mysqli_error($link));
$get_check = mysqli_fetch_array($check);
$access_helpdesk_tab = $get_check['access_helpdesk_tab'];
$view_all_tickets = $get_check['view_due_loans'];
if($view_all_tickets == '1' || $access_helpdesk_tab == '1')
{*/
?>
    
   <!-- <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">
              <span class="info-box-icon bg-<?php //echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?> elevation-1"><i class="fas fa-headphones"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">Helpdesk Message</span>
                <span class="info-box-number">
                  <?php
                   /* $select = mysqli_query($link, "SELECT * FROM message WHERE branchid = '$vendorid'") or die (mysqli_error($link));
                    $num = mysqli_num_rows($select);
                    echo $num;*/
                    ?>
                </span>
              </div>-->
              <!-- /.info-box-content -->
            <!--</div>-->
            <!-- /.info-box -->
        <!--</div>-->
        <!-- /.col -->
<?php
/*}
else{
  echo '';
}*/
?>

<?php
$check = mysqli_query($link, "SELECT * FROM my_permission WHERE urole = '$vrole'") or die ("Error" . mysqli_error($link));
$get_check = mysqli_fetch_array($check);
$access_expense_tab = $get_check['access_expense_tab'];
$view_expenses = $get_check['view_expenses'];
if($access_expense_tab == '1' || $view_expenses == '1')
{
?>
        
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">
              <span class="info-box-icon bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?> elevation-1"><i class="fas fa-money"></i></span>

              <div class="info-box-content">
                <span class="info-box-text"><?php echo ($view_expenses == 1) ? '<a href="listexpenses.php?tid='.$_SESSION['tid'].'&&mid='.base64_encode("422").'">Total Expenses <i class="fa fa-arrow-circle-right"></i></a>' : 'Total Expenses'; ?></span>
                <span class="info-box-number">
                  <?php 
                    $date_now = date("Y-m-d");
                    $select = mysqli_query($link, "SELECT SUM(eamt) FROM expenses WHERE branchid = '$vendorid'") or die ("Error: " . mysqli_error($link));
                    if(mysqli_num_rows($select) == 0)
                    {
                      echo '0.00';
                    }
                    else{
                    while($row = mysqli_fetch_array($select))
                    {
                    echo number_format($row['SUM(eamt)'],2,".",",")."</b>";
                    }
                    }
                    ?>
                </span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <!-- /.col -->
<?php
}
else{
  echo '';
}
?>
        <!-- ./col -->

<?php
$check = mysqli_query($link, "SELECT * FROM my_permission WHERE urole = '$vrole'") or die ("Error" . mysqli_error($link));
$get_check = mysqli_fetch_array($check);
$list_all_repayment = $get_check['list_all_repayment'];
if($list_all_repayment == '1' && $v_ctype == "Onlending Firm")
{
?>

        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">
              <span class="info-box-icon bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?> elevation-1"><i class="fas fa-calculator"></i></span>

              <div class="info-box-content">
                <span class="info-box-text"><?php echo ($list_all_repayment == 1) ? '<a href="listpayment.php?tid='.$_SESSION['tid'].'&&mid='.base64_encode("408").'">Loan Repayment<i class="fa fa-arrow-circle-right"></i></a>' : '<a href="#">Loan Repayment</a>'; ?></span>
                <span class="info-box-number">
                  <?php 
                    $select = mysqli_query($link, "SELECT SUM(amount_to_pay) FROM payments WHERE remarks = 'paid' AND vendorid = '$vendorid'") or die (mysqli_error($link));
                    if(mysqli_num_rows($select)==0)
                    {
                    echo "0.00";
                    }
                    else{
                    while($row = mysqli_fetch_array($select))
                    {
                    echo number_format($row['SUM(amount_to_pay)'],2,".",",")."</b>";
                    }
                    }
                    ?>
                </span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <!-- /.col -->
    
<?php
}
else{
  echo '';
}
?>



<?php
$check = mysqli_query($link, "SELECT * FROM my_permission WHERE urole = '$vrole'") or die ("Error" . mysqli_error($link));
$get_check = mysqli_fetch_array($check);
$savings_transaction = $get_check['all_product_transaction'];
$savings_subscription = $get_check['all_product_subscription'];

if($savings_transaction == '1' || $savings_subscription == '1')
{
?> 

    <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">
              <span class="info-box-icon bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?> elevation-1"><i class="fas fa-shopping-basket"></i></span>

              <div class="info-box-content">
                <span class="info-box-text"><?php echo ($savings_transaction == 1) ? '<a href="msavings_trans.php?tid='.$_SESSION['tid'].'&&mid='.base64_encode("490").'" class="small-box-footer">Total Settlements<i class="fa fa-arrow-circle-right"></i></a>' : '<a href="#">Total Settlements</a>'; ?></span>
                <span class="info-box-number">
                  <?php 
                    $select = mysqli_query($link, "SELECT SUM(amount) FROM all_savingssub_transaction WHERE status = 'successful' AND vendorid = '$vendorid'") or die (mysqli_error($link));
                    if(mysqli_num_rows($select)==0)
                    {
                    echo "0.00";
                    }
                    else{
                    while($row = mysqli_fetch_array($select))
                    {
                    echo number_format($row['SUM(amount)'],2,".",",")."</b>";
                    }
                    }
                    ?>
                </span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <!-- /.col -->

<?php
}
else{
  echo '';
}
?>



<?php
$check = mysqli_query($link, "SELECT * FROM my_permission WHERE urole = '$vrole'") or die ("Error" . mysqli_error($link));
$get_check = mysqli_fetch_array($check);
$create_savings_plan = $get_check['create_savings_plan'];
$savings_subscription = $get_check['savings_subscription'];

if(($v_ctype != "Onlending Firm"))
{
?> 

    <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">
              <span class="info-box-icon bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?> elevation-1"><i class="fas fa-filter"></i></span>

              <div class="info-box-content">
                <span class="info-box-text"><?php echo ($v_ctype != "Onlending Firm") ? '<a href="create_msavingsplan.php?tid='.$_SESSION['tid'].'&&mid='.base64_encode("490").'">Investment Product<i class="fa fa-arrow-circle-right"></i></a>' : '<a href="#" class="small-box-footer">Investment Product</a>'; ?></span>
                <span class="info-box-number">
                  <h4>
                    <?php 
                    $selecte = mysqli_query($link, "SELECT * FROM savings_plan WHERE merchantid_others = '$vcreated_by' AND branchid = '$vendorid'") or die (mysqli_error($link));
                    $nume = mysqli_num_rows($selecte);
                    echo $nume;
                    ?>
                </span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <!-- /.col -->

<?php
}
else{
  echo '';
}
?>


<?php
$check = mysqli_query($link, "SELECT * FROM my_permission WHERE urole = '$vrole'") or die ("Error" . mysqli_error($link));
$get_check = mysqli_fetch_array($check);
$transfer_fund = $get_check['transfer_fund'];
$access_wallet_tab = $get_check['access_wallet_tab'];
if($transfer_fund == '1' || $access_wallet_tab == '1')
{
?>

        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">
              <span class="info-box-icon bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?> elevation-1"><i class="fas fa-database"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">Total Wallet Trans.</span>
                <span class="info-box-number">
                  <?php 
                    $selectwh = mysqli_query($link, "SELECT * FROM wallet_history WHERE userid = '$vcreated_by' AND (recipient = '$vuid' OR initiator = '$vuid')") or die (mysqli_error($link));
                    $numwh = mysqli_num_rows($selectwh);
                    echo $numwh;
                    ?>
                </span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>

<?php
}
else{
  echo '';
}
?>
        <!-- ./col -->


<?php
$check = mysqli_query($link, "SELECT * FROM my_permission WHERE urole = '$vrole'") or die ("Error" . mysqli_error($link));
$get_check = mysqli_fetch_array($check);
$savings_subscription = $get_check['all_product_subscription'];

if($savings_subscription == '1')
{
?> 

    <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">
              <span class="info-box-icon bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?> elevation-1"><i class="fas fa-battery-quarter"></i></span>

              <div class="info-box-content">
                <span class="info-box-text"><?php echo ($savings_subscription == 1) ? '<a href="view_msaving_sub.php?tid='.$_SESSION['tid'].'&&mid='.base64_encode("490").'">Active Sub.<i class="fa fa-arrow-circle-right"></i></a>' : '<a href="#" class="small-box-footer">Active Sub.</a>'; ?></span>
                <span class="info-box-number">
                  <h4>
                    <?php 
                    $select_active = mysqli_query($link, "SELECT * FROM savings_subscription WHERE vendorid = '$vendorid' AND status = 'Approved'") or die (mysqli_error($link));
                    $num_active = mysqli_num_rows($select_active);
                    echo $num_active;
                    ?>
                </span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <!-- /.col -->

<?php
}
else{
  echo '';
}
?>


<?php
$check = mysqli_query($link, "SELECT * FROM my_permission WHERE urole = '$vrole'") or die ("Error" . mysqli_error($link));
$get_check = mysqli_fetch_array($check);
$savings_subscription = $get_check['all_product_subscription'];

if($savings_subscription == '1')
{
?> 

    <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">
              <span class="info-box-icon bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?> elevation-1"><i class="fas fa-ban"></i></span>

              <div class="info-box-content">
                <span class="info-box-text"><?php echo ($savings_subscription == 1) ? '<a href="view_msaving_sub.php?tid='.$_SESSION['tid'].'&&mid='.base64_encode("490").'">Disabled Sub.<i class="fa fa-arrow-circle-right"></i></a>' : '<a href="#" class="small-box-footer">Disabled Sub.</a>'; ?></span>
                <span class="info-box-number">
                  <h4>
                    <?php 
                    $select_active = mysqli_query($link, "SELECT * FROM savings_subscription WHERE vendorid = '$vendorid' AND status = 'Disabled'") or die (mysqli_error($link));
                    $num_active = mysqli_num_rows($select_active);
                    echo $num_active;
                    ?>
                </span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <!-- /.col -->

<?php
}
else{
  echo '';
}
?>


<?php
$check = mysqli_query($link, "SELECT * FROM my_permission WHERE urole = '$vrole'") or die ("Error" . mysqli_error($link));
$get_check = mysqli_fetch_array($check);
$savings_subscription = $get_check['all_product_subscription'];

if($savings_subscription == '1')
{
?> 

        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">
              <span class="info-box-icon bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?> elevation-1"><i class="fas fa-handshake"></i></span>

              <div class="info-box-content">
                <span class="info-box-text"><?php echo ($savings_subscription == 1) ? '<a href="withdrawal_request.php?tid='.$_SESSION['tid'].'&&mid='.base64_encode("490").'">Pending Withdrawal<i class="fa fa-arrow-circle-right"></i></a>' : '<a href="#" class="small-box-footer">Pending Withdrawal</a>'; ?></span>
                <span class="info-box-number">
                  <h4>
                    <?php 
                    $select_active = mysqli_query($link, "SELECT * FROM mcustomer_wrequest WHERE merchantid = '$vcreated_by' AND vendorid = '$vendorid' AND status = 'Pending'") or die (mysqli_error($link));
                    $num_active = mysqli_num_rows($select_active);
                    echo $num_active;
                    ?>
                </span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <!-- /.col -->

<?php
}
else{
  echo '';
}
?>


<?php
$check = mysqli_query($link, "SELECT * FROM my_permission WHERE urole = '$vrole'") or die ("Error" . mysqli_error($link));
$get_check = mysqli_fetch_array($check);
$savings_subscription = $get_check['all_product_subscription'];

if($savings_subscription == '1')
{
?> 

        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">
              <span class="info-box-icon bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?> elevation-1"><i class="fas fa-upload"></i></span>

              <div class="info-box-content">
                <span class="info-box-text"><?php echo ($savings_subscription == 1) ? '<a href="withdrawal_request.php?tid='.$_SESSION['tid'].'&&mid='.base64_encode("490").'">Total Disbursement<i class="fa fa-arrow-circle-right"></i></a>' : '<a href="#" class="small-box-footer">Total Disbursement</a>'; ?></span>
                <span class="info-box-number">
                  <h4>
                    <?php 
                    $select_active = mysqli_query($link, "SELECT * FROM mcustomer_wrequest WHERE merchantid = '$vcreated_by' AND vendorid = '$vendorid' AND status = 'Approved'") or die (mysqli_error($link));
                    $num_active = mysqli_num_rows($select_active);
                    echo $num_active;
                    ?>
                </span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <!-- /.col -->

<?php
}
else{
  echo '';
}
?>



<?php
$check = mysqli_query($link, "SELECT * FROM my_permission WHERE urole = '$vrole'") or die ("Error" . mysqli_error($link));
$get_check = mysqli_fetch_array($check);
$view_all_loans = $get_check['view_all_loans'];
if($view_all_loans == '1' && $v_ctype == "Onlending Firm")
{
?>
        
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">
              <span class="info-box-icon bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?> elevation-1"><i class="fas fa-battery-three-quarters"></i></span>

              <div class="info-box-content">
                <span class="info-box-text"><?php echo ($view_all_loans == 1) ? '<a href="activeloans.php?tid='.$_SESSION['tid'].'&&mid='.base64_encode("405").'">Active Loan <i class="fa fa-arrow-circle-right"></i></a>' : '<a href="#">Active Loan</a>'; ?></span>
                <span class="info-box-number">
                  <?php 
                    $pay_date = date("Y-m-d");
                    $selectPa = mysqli_query($link, "SELECT * FROM loan_info WHERE vendorid = '$vendorid' AND p_status != 'PAID' AND status = 'Approved' AND pay_date > '$pay_date'") or die (mysqli_error($link));
                    $pnum = mysqli_num_rows($selectPa);
                    
                    echo $pnum;
                    ?>
                </span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <!-- /.col -->
        
<?php
}
else{
  echo '';
}
?>


<?php
$check = mysqli_query($link, "SELECT * FROM my_permission WHERE urole = '$vrole'") or die ("Error" . mysqli_error($link));
$get_check = mysqli_fetch_array($check);
$view_all_loans = $get_check['view_all_loans'];
if($view_all_loans == '1' && $v_ctype == "Onlending Firm")
{
?>
        
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">
              <span class="info-box-icon bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?> elevation-1"><i class="fas fa-times"></i></span>

              <div class="info-box-content">
                <span class="info-box-text"><?php echo ($view_all_loans == 1) ? '<a href="dueloans.php?tid='.$_SESSION['tid'].'&&mid='.base64_encode("405").'">Expired Loan <i class="fa fa-arrow-circle-right"></i></a>' : '<a href="#">Expired Loan</a>'; ?></span>
                <span class="info-box-number">
                  <?php 
                    $pay_date = date("Y-m-d");
                    $selectPe = mysqli_query($link, "SELECT * FROM loan_info WHERE vendorid = '$vendorid' AND p_status != 'PAID' AND status = 'Approved' AND pay_date <= '$pay_date'") or die (mysqli_error($link));
                    $penum = mysqli_num_rows($selectPe);
                    
                    echo $penum;
                    ?>
                </span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <!-- /.col -->
        
<?php
}
else{
  echo '';
}
?>



<?php
$check = mysqli_query($link, "SELECT * FROM my_permission WHERE urole = '$vrole'") or die ("Error" . mysqli_error($link));
$get_check = mysqli_fetch_array($check);
$view_all_loans = $get_check['view_all_loans'];
if($view_all_loans == '1' && $v_ctype == "Onlending Firm")
{
?>
        
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">
              <span class="info-box-icon bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?> elevation-1"><i class="fas fa-exclamation"></i></span>

              <div class="info-box-content">
                <span class="info-box-text"><?php echo ($view_all_loans == 1) ? '<a href="pendingloans.php?tid='.$_SESSION['tid'].'&&mid='.base64_encode("405").'">Pending Loan <i class="fa fa-arrow-circle-right"></i></a>' : '<a href="#">Pending Loan</a>'; ?></span>
                <span class="info-box-number">
                  <?php 
                    $selectPend = mysqli_query($link, "SELECT * FROM loan_info WHERE vendorid = '$vendorid' AND status = 'Pending'") or die (mysqli_error($link));
                    $pendnum = mysqli_num_rows($selectPend);
                    
                    echo $pendnum;
                    ?>
                </span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <!-- /.col -->
        
<?php
}
else{
  echo '';
}
?>



<?php
$search_senderid = mysqli_query($link, "SELECT * FROM member_settings WHERE companyid = '$vcreated_by'");
$fetch_senderid = mysqli_fetch_object($search_senderid);
?>
    
        <!-- ./col -->  
    
   <section class="content">
      <div class="row">
    </div>
 <!--   
    <div class="box box-info">
    <span style="font-size: 20px;">Your Custom Link is: <a href="https://esusu.app/<?php echo $fetch_senderid->sender_id; ?>" target="_blank"><b>https://esusu.app/<?php echo $fetch_senderid->sender_id; ?></b></a></span>
            <div class="box-body">

              
    </form>
        
</div>  
</div>

-->
    
    <!--  Event codes starts here-->
  
     
          <div class="box box-info">
<?php
$check = mysqli_query($link, "SELECT * FROM my_permission WHERE urole = '$vrole'") or die ("Error" . mysqli_error($link));
$get_check = mysqli_fetch_array($check);
$view_all_loans = $get_check['view_all_loans'];
$borrowers_reports = $get_check['borrowers_reports'];
if($view_all_loans == '1' && $v_ctype == "Onlending Firm")
{
?>

            <div class="box-body">
      <div class="bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>" align="center" class="style2" style="color: #FF0000"><b>LOAN INFORMATION CHART WITH YEARLY LOAN COLLECTION AND LAST DUE DATE</b></div>
             
       <div class="col-md-6">
         <div id="chartdiv"></div>                
       </div>
      
      
      <div class="col-md-6">
        <div id="chartdiv7"></div>
      </div>

<?php
}
else{
  echo '';
}
?>  
      </div>
    
</div>  
</div>