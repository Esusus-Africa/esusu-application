<div class="row">
 
<?php
if($transfer_fund === "1" && $irole != "agent_manager" && $irole != "institution_super_admin" && $irole != "merchant_super_admin")
{
?>

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
          
<?php
}
else{
?>


<div class="col-md-12">
     <div class="slideshow-container">
        <div class="alert bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>" style="font-size:17px;" align="center"><?php echo "<b>".strtoupper('Wallet Account Details')."</b>"; ?>: <i class='fa fa-hand-o-down'></i><br>

<?php
  $detectAcct = $idefaultAcct;
  $parameter = (explode(',',$detectAcct));
  $countNum = count($parameter);

  for($i = 0; $i < $countNum; $i++){
      
    $mydefaultbank = ($parameter[$i] == "Monnify" ? "monify" : ($parameter[$i] == "Rubies Bank" ? "rubies" : ($parameter[$i] == "Flutterwave" ? "rave" : ($parameter[$i] == "Payant" ? "payant" : ($parameter[$i] == "Providus Bank" ? "providus" : ($parameter[$i] == "Wema Bank" ? "wema" : ($parameter[$i] == "Sterling Bank" ? "sterling" : "None")))))));
  
    $search_vaccount = mysqli_query($link, "SELECT * FROM virtual_account WHERE userid = '$institution_id' AND gateway_name = '$mydefaultbank' AND status = 'ACTIVE'");
  
    if(mysqli_num_rows($search_vaccount) == 0 && $mydefaultbank != "None"){
      
      $accountUserId = $institution_id;
      $accountName = $inst_name;
      $currencyCode = $icurrency;
      $customerEmail = $inst_email;
      $customerName = $iname;
      $userBvn = $ibvn;
      $TxtReference = uniqid('ESFUND').time();
      $phoneNumber = $inst_phone;
      $country = "NG";
      $mydate_time = date("Y-m-d h:i:s");
      
      require_once '../config/virtualBankAccount_class.php';

      ($parameter[$i] == "Monnify") ? $result = $newVA->monnifyVirtualAccount($accountName,$currencyCode,$customerEmail,$customerName,$userBvn,$mo_contract_code) : "";
      ($parameter[$i] == "Rubies Bank") ? $result1 = $newVA->rubiesVirtualAccount($accountName,$rubbiesSecKey) : "";
      ($parameter[$i] == "Flutterwave") ? $result1 = $newVA->wemaVirtualAccount($accountName,$customerEmail,$TxtReference,$rave_secret_key) : "";
      ($parameter[$i] == "Payant") ? $result1 = $newVA->sterlingPayantVirtualAcct($accountName,$customerEmail,$phoneNumber,$currencyCode,$country,$payantEmail,$payantPwd,$payantOrgId) : "";
      ($parameter[$i] == "Providus Bank") ? $result1 = $newVA->providusVirtualAccount($accountName,$userBvn,$providusClientId,$providusClientSecret) : "";
      ($parameter[$i] == "Wema Bank") ? $result1 = $newVA->directWemaVirtualAccount($accountName,$wemaVAPrefix) : "";
      ($parameter[$i] == "Sterling Bank") ? $result1 = $newVA->sterlingVirtualAccount($ifname,$ilname,$phoneNumber,$idob,$igender,$currencyCode,$sterlinkInputKey,$sterlingIv) : "";

      ($parameter[$i] == "Monnify" ? $myAccountReference = $result->responseBody->accountReference : (($parameter[$i] == "Rubies Bank") && $result1['responsecode'] == "00" ? $myAccountReference = "EAVA-".time() : (($parameter[$i] == "Flutterwave") && $result1['data']['response_code'] == "02" ? $myAccountReference = $TxtReference : (($parameter[$i] == "Payant") && $result1['statusCode'] == "200" ? $myAccountReference = $result1['data']['_id'] : (($parameter[$i] == "Providus Bank") && $result1['responseCode'] == "00" ? $myAccountReference = uniqid("EAVA-").time() : (($parameter[$i] == "Wema Bank") && $result1['responseCode'] == "00" ? $myAccountReference = uniqid("EAVA-").time() : (($parameter[$i] == "Sterling Bank") && $result1['response'] == "00" ? $myAccountReference = uniqid("EAVA-").time() : "")))))));
      ($parameter[$i] == "Monnify" ? $myAccountName = $result->responseBody->accountName : (($parameter[$i] == "Rubies Bank") && $result1['responsecode'] == "00" ? $myAccountName = $result1['virtualaccountname'] : (($parameter[$i] == "Flutterwave") && $result1['data']['response_code'] == "02" ? $myAccountName = $accountName : (($parameter[$i] == "Payant") && $result1['statusCode'] == "200" ? $myAccountName = $result1['data']['accountName'] : (($parameter[$i] == "Providus Bank") && $result1['responseCode'] == "00" ? $myAccountName = $result1['account_name'] : (($parameter[$i] == "Wema Bank") && $result1['responseCode'] == "00" ? $myAccountName = $result1['account_name'] : (($parameter[$i] == "Sterling Bank") && $result1['response'] == "00" ? $myAccountName = $result1['data']['lastname'].' '.$result1['data']['firstname'] : "")))))));
      ($parameter[$i] == "Monnify" ? $myAccountNumber = $result->responseBody->accountNumber : (($parameter[$i] == "Rubies Bank") && $result1['responsecode'] == "00" ? $myAccountNumber = $result1['virtualaccount'] : (($parameter[$i] == "Flutterwave") && $result1['data']['response_code'] == "02" ? $myAccountNumber = $result1['data']['account_number'] : (($parameter[$i] == "Payant") && $result1['statusCode'] == "200" ? $myAccountNumber = $result1['data']['accountNumber'] : (($parameter[$i] == "Providus Bank") && $result1['responseCode'] == "00" ? $myAccountNumber = $result1['account_number'] : (($parameter[$i] == "Wema Bank") && $result1['responseCode'] == "00" ? $myAccountNumber = $result1['account_number'] : (($parameter[$i] == "Sterling Bank") && $result1['response'] == "00" ? $myAccountNumber = $result1['data']['VIRTUALACCT'] : "")))))));
      ($parameter[$i] == "Monnify" ? $myBankName = $result->responseBody->bankName : (($parameter[$i] == "Rubies Bank") && $result1['responsecode'] == "00" ? $myBankName = $result1['bankname'] : (($parameter[$i] == "Flutterwave") && $result1['data']['response_code'] == "02" ? $myBankName = $result1['data']['bank_name'] : (($parameter[$i] == "Payant") && $result1['statusCode'] == "200" ? $myBankName = "Sterling Bank" : (($parameter[$i] == "Providus Bank") && $result1['responseCode'] == "00" ? $myBankName = "Providus Bank" : (($parameter[$i] == "Wema Bank") && $result1['responseCode'] == "00" ? $myBankName = "Wema Bank" : (($parameter[$i] == "Sterling Bank") && $result1['response'] == "00" ? $myBankName = "Sterling Bank" : "")))))));
      ($parameter[$i] == "Monnify" ? $myStatus = $result->responseBody->status : (($parameter[$i] == "Rubies Bank") && $result1['responsecode'] == "00" ? $myStatus = "ACTIVE" : (($parameter[$i] == "Flutterwave") && $result1['data']['response_code'] == "02" ? $myStatus = "ACTIVE" : (($parameter[$i] == "Payant") && $result1['statusCode'] == "200" ? $myStatus = $result1['data']['status'] : (($parameter[$i] == "Providus Bank") && $result1['responseCode'] == "00" ? $myStatus = "ACTIVE" : (($parameter[$i] == "Wema Bank") && $result1['responseCode'] == "00" ? $myStatus = "ACTIVE" : (($parameter[$i] == "Sterling Bank") && $result1['response'] == "00" ? $myStatus = "ACTIVE" : "")))))));
      ($parameter[$i] == "Monnify" ? $date_time = $result->responseBody->createdOn : (($parameter[$i] == "Rubies Bank") && $result1['responsecode'] == "00" ? $date_time = date("Y-m-d h:i:s") : (($parameter[$i] == "Flutterwave") && $result1['data']['response_code'] == "02" ? $date_time = date("Y-m-d h:i:s") : (($parameter[$i] == "Payant") && $result1['statusCode'] == "200" ? $date_time = date("Y-m-d h:i:s") : (($parameter[$i] == "Providus Bank") && $result1['responseCode'] == "00" ? $date_time = date("Y-m-d h:i:s") : (($parameter[$i] == "Wema Bank") && $result1['responseCode'] == "00" ? $date_time = date("Y-m-d h:i:s") : (($parameter[$i] == "Sterling Bank") && $result1['response'] == "00" ? $date_time = date("Y-m-d h:i:s") : "")))))));
      ($parameter[$i] == "Monnify" ? $provider = "monify" : (($parameter[$i] == "Rubies Bank") && $result1['responsecode'] == "00" ? $provider = "rubies" : (($parameter[$i] == "Flutterwave") && $result1['data']['response_code'] == "02" ? $provider = "rave" : (($parameter[$i] == "Payant") && $result1['statusCode'] == "200" ? $provider = "payant" : (($parameter[$i] == "Providus Bank") && $result1['responseCode'] == "00" ? $provider = "providus" : (($parameter[$i] == "Wema Bank") && $result1['responseCode'] == "00" ? $provider = "wema" : (($parameter[$i] == "Sterling Bank") && $result1['response'] == "00" ? $provider = "sterling" : "")))))));

      ($myAccountReference != "" || $myAccountName != "" || $myAccountNumber != "" || $myBankName != "" || $myStatus != "") ? mysqli_query($link, "INSERT INTO virtual_account VALUE(null,'$myAccountReference','$accountUserId','$myAccountName','$myAccountNumber','$myBankName','$myStatus','$mydate_time','$provider','','','Verified','','1000000','100000','10000','5000')") : "";
    
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
        
        echo "[<b>".strtoupper($bank_name)."</b> - ACCOUNT NAME: <b>".strtoupper($account_name)."</b> - ACCOUNT NO: <b>".strtoupper($account_number)."</b>]";
        
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
      $search_myva = mysqli_query($link, "SELECT * FROM virtual_account WHERE userid = '$institution_id' AND gateway_name = '$mydefaultbank2' AND status = 'ACTIVE'");
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
        </div>
          <!-- /.col -->

<?php
}
?>

<?php
  $search_doc = mysqli_query($link, "SELECT * FROM institution_legaldoc WHERE instid = '$institution_id'");
  if(mysqli_num_rows($search_doc) == 0 && ($irole == 'agent_manager' || $irole == 'institution_super_admin' || $irole == 'merchant_super_admin')){
    echo "<div class='col-lg-12 col-xs-12'><div class='box box-info'>";
    echo "<div class='small-box alert bg-".(($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color'])."'><b>IMPORTANT!:</b> COMPLETE THE KYC UPDATE FORM</div>";
    echo "<p><i class='fa fa-hand-o-right'></i> To Access your Dashboard Statistical Reports</p>";
    echo "<p><i class='fa fa-hand-o-right'></i> To Have Access to other Interesting Features to Scale your Operation</p>";
    echo "<p>After filling the form by clicking the link below, just refresh this page to have access to your <b>dashboard statistical reports</b>.</p>";
    echo "<p>Please <a href='https://esusu.app/complete_reg.php?a_key=".$institution_id."&&sid=".$iuid."' target='_blank'><b>CLICK HERE</b></a> to complete the KYC UPDATE FORM</p>";
    echo "<p>For more enquiries to have access to other interesting features to scale your operation, please feel free to contact us via <b>+2348165904908, +2348111667094</b> OR <b>admin@esusu.africa</b></p>";
    echo "</div></div>";
 }
 else{
  ?>
     
          
<?php
$check = mysqli_query($link, "SELECT * FROM my_permission WHERE urole = '$irole'") or die ("Error" . mysqli_error($link));
$get_check = mysqli_fetch_array($check);
$transfer_fund = $get_check['transfer_fund'];
$access_wallet_tab = $get_check['access_wallet_tab'];
if($transfer_fund === "1" && $irole != "agent_manager" && $irole != "institution_super_admin" && $irole != "merchant_super_admin")
{
?>
        
    <div class="col-12 col-sm-6 col-md-3">
        <div class="info-box">
        <span class="info-box-icon bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?> elevation-1"><i class="fas fa-shopping-basket"></i></span>

          <div class="info-box-content">
            <span class="info-box-text"><a href="mywallet.php?tid=<?php echo $_SESSION['tid']; ?>&&mid=NDA0"> Transfer Balance <i class="fa fa-arrow-circle-right"></i></a></span>
            <span class="info-box-number">
                <?php echo "<span id='twallet_balance'>".$icurrency.number_format($itransfer_balance,2,'.',',')."</span>"; ?>  
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
?>

    <div class="col-12 col-sm-6 col-md-3">
        <div class="info-box">
        <span class="info-box-icon bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?> elevation-1"><i class="fas fa-shopping-basket"></i></span>

          <div class="info-box-content">
            <span class="info-box-text"><a href="mywallet.php?tid=<?php echo $_SESSION['tid']; ?>&&mid=NDA0"> Wallet Balance <i class="fa fa-arrow-circle-right"></i></a></span>
            <span class="info-box-number">
                <?php echo "<span id='wallet_balance'>".$icurrency.number_format($iassigned_walletbal,2,'.',',')."</span>"; ?>  
            </span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <!-- /.col -->

<?php
}
?>
        <!-- ./col -->
<?php
$check = mysqli_query($link, "SELECT * FROM my_permission WHERE urole = '$irole'") or die ("Error" . mysqli_error($link));
$get_check = mysqli_fetch_array($check);
$access_loan_tab = $get_check['access_loan_tab'];
$view_all_loans = $get_check['view_all_loans'];
if(($view_all_loans == '1' || $access_loan_tab == '1' || $individual_loan_records == '1' || $branch_loan_records == '1') && $iloan_manager == "On")
{
?>
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">
              <span class="info-box-icon bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?> elevation-1"><i class="fas fa-american-sign-language-interpreting"></i></span>

              <div class="info-box-content">
                <span class="info-box-text"><?php echo ($view_all_loans == '1' || $individual_loan_records == '1' || $branch_loan_records == '1') ? '<a href="listloans.php?tid='.$_SESSION['tid'].'&&mid='.base64_encode("405").'">Loan Released <i class="fa fa-arrow-circle-right"></i></a>' : '<a href="#" >Loan Released</a>'; ?></span>
                <span class="info-box-number">
                <?php 
                ($view_all_loans == '1' && $individual_loan_records == '' && $branch_loan_records == '') ? $select = mysqli_query($link, "SELECT SUM(amount) FROM loan_info WHERE status = 'Approved' AND branchid = '$institution_id'") or die (mysqli_error($link)) : "";
                ($view_all_loans == '' && $individual_loan_records == '1' && $branch_loan_records == '') ? $select = mysqli_query($link, "SELECT SUM(amount) FROM loan_info WHERE status = 'Approved' AND branchid = '$institution_id' AND (agent = '$iname' OR agent = '$iuid')") or die (mysqli_error($link)) : "";
                ($view_all_loans == '' && $individual_loan_records == '' && $branch_loan_records == '1') ? $select = mysqli_query($link, "SELECT SUM(amount) FROM loan_info WHERE status = 'Approved' AND branchid = '$institution_id' AND sbranchid = '$isbranchid'") or die (mysqli_error($link)) : "";
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

        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">
              <span class="info-box-icon bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?> elevation-1"><i class="fas fa-money"></i></span>

              <div class="info-box-content">
                <span class="info-box-text"><a href="#" >Total Loan Balance</a></span>
                <span class="info-box-number">
                <?php 
                ($view_all_loans == '1' && $individual_loan_records == '' && $branch_loan_records == '') ? $selectLBal = mysqli_query($link, "SELECT SUM(loan_balance) FROM borrowers WHERE branchid = '$institution_id' AND acct_status != 'Closed'") or die (mysqli_error($link)) : "";
                ($view_all_loans == '' && $individual_loan_records == '1' && $branch_loan_records == '') ? $selectLBal = mysqli_query($link, "SELECT SUM(loan_balance) FROM borrowers WHERE branchid = '$institution_id' AND lofficer = '$iuid' AND acct_status != 'Closed'") or die (mysqli_error($link)) : "";
                ($view_all_loans == '' && $individual_loan_records == '' && $branch_loan_records == '1') ? $selectLBal = mysqli_query($link, "SELECT SUM(loan_balance) FROM borrowers WHERE branchid = '$institution_id' AND sbranchid = '$isbranchid' AND acct_status != 'Closed'") or die (mysqli_error($link)) : "";
                $fetchLBal = mysqli_fetch_array($selectLBal);
                echo "<b>".number_format($fetchLBal['SUM(amount)'],2,".",",")."</b>";
                ?>
            </span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <!-- /.col -->

        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">
              <span class="info-box-icon bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?> elevation-1"><i class="fas fa-money"></i></span>

              <div class="info-box-content">
                <span class="info-box-text"><a href="#" >Total Asset Balance</a></span>
                <span class="info-box-number">
                <?php 
                ($view_all_loans == '1' && $individual_loan_records == '' && $branch_loan_records == '') ? $selectAsset = mysqli_query($link, "SELECT SUM(asset_acquisition_bal) FROM borrowers WHERE branchid = '$institution_id' AND acct_status != 'Closed'") or die (mysqli_error($link)) : "";
                ($view_all_loans == '' && $individual_loan_records == '1' && $branch_loan_records == '') ? $selectAsset = mysqli_query($link, "SELECT SUM(asset_acquisition_bal) FROM borrowers WHERE branchid = '$institution_id' AND lofficer = '$iuid' AND acct_status != 'Closed'") or die (mysqli_error($link)) : "";
                ($view_all_loans == '' && $individual_loan_records == '' && $branch_loan_records == '1') ? $selectAsset = mysqli_query($link, "SELECT SUM(asset_acquisition_bal) FROM borrowers WHERE branchid = '$institution_id' AND sbranchid = '$isbranchid' AND acct_status != 'Closed'") or die (mysqli_error($link)) : "";
                $fetchAsset = mysqli_fetch_array($selectAsset);
                echo "<b>".number_format($fetchAsset['SUM(asset_acquisition_bal)'],2,".",",")."</b>";
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
$check = mysqli_query($link, "SELECT * FROM my_permission WHERE urole = '$irole'") or die ("Error" . mysqli_error($link));
$get_check = mysqli_fetch_array($check);
$access_loan_tab = $get_check['access_loan_tab'];
$view_due_loans = $get_check['view_due_loans'];
if(($view_due_loans == '1' || $access_loan_tab == '1' || $individual_due_loans == '1' || $branch_due_loans == '1') && $iloan_manager == "On")
{
?>
    <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">
              <span class="info-box-icon bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?> elevation-1"><i class="fas fa-exclamation-triangle"></i></span>

              <div class="info-box-content">
                <span class="info-box-text"><?php echo ($view_due_loans == '1' || $individual_due_loans == '1' || $branch_due_loans == '1') ? '<a href="dueloans.php?tid='.$_SESSION['tid'].'&&mid='.base64_encode("405").'">Loan Due Amount <i class="fa fa-arrow-circle-right"></i></a>' : '<a href="#">Loan Due Amount</a>'; ?></span>
                <span class="info-box-number">
                    
                <?php
                $date = new DateTime(date("Y-m-d"));
                $date->sub(new DateInterval('P5D')); //substract 5 days from the original date
                $date_now = $date->format('Y-m-d');
                ($view_due_loans == '1' && $individual_due_loans == '' && $branch_due_loans == '') ? $select = mysqli_query($link, "SELECT SUM(payment) FROM pay_schedule WHERE status = 'UNPAID' AND schedule <= '$date_now' AND branchid = '$institution_id'") or die ("Error: " . mysqli_error($link)) : "";
                ($view_due_loans == '' && $individual_due_loans == '1' && $branch_due_loans == '') ? $select = mysqli_query($link, "SELECT SUM(payment) FROM pay_schedule WHERE status = 'UNPAID' AND schedule <= '$date_now' AND branchid = '$institution_id' AND lofficer = '$iuid'") or die ("Error: " . mysqli_error($link)) : "";
                ($view_due_loans == '' && $individual_due_loans == '' && $branch_due_loans == '1') ? $select = mysqli_query($link, "SELECT SUM(payment) FROM pay_schedule WHERE status = 'UNPAID' AND schedule <= '$date_now' AND branchid = '$institution_id' AND sbranchid = '$isbranchid'") or die ("Error: " . mysqli_error($link)) : "";
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
$check = mysqli_query($link, "SELECT * FROM my_permission WHERE urole = '$irole'") or die ("Error" . mysqli_error($link));
$get_check = mysqli_fetch_array($check);
$access_loanrepayment_tab = $get_check['access_loanrepayment_tab'];
$list_all_repayment = $get_check['list_all_repayment'];
if(($access_loanrepayment_tab == '1' || $list_all_repayment == '1' || $list_individual_loan_repayment == '1' || $list_branch_loan_repayment == '1') && $iloan_manager == "On")
{
?>
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">
              <span class="info-box-icon bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?> elevation-1"><i class="fas fa-calculator"></i></span>

              <div class="info-box-content">
                <span class="info-box-text"><?php echo ($list_all_repayment == '1' || $list_individual_loan_repayment == '1' || $list_branch_loan_repayment == '1') ? '<a href="listpayment.php?tid='.$_SESSION['tid'].'&&mid='.base64_encode("408").'">Total Loan Repaid <i class="fa fa-arrow-circle-right"></i></a>' : '<a href="#">Total Loan Repaid</a>'; ?></span>
                <span class="info-box-number">
                <?php 
                ($list_all_repayment == '1' && $list_individual_loan_repayment == '' && $list_branch_loan_repayment == '') ? $select = mysqli_query($link, "SELECT SUM(amount_to_pay) FROM payments WHERE remarks = 'paid' AND branchid = '$institution_id'") or die (mysqli_error($link)) : "";
                ($list_all_repayment == '' && $list_individual_loan_repayment == '1' && $list_branch_loan_repayment == '') ? $select = mysqli_query($link, "SELECT SUM(amount_to_pay) FROM payments WHERE remarks = 'paid' AND branchid = '$institution_id' AND tid = '$iuid'") or die (mysqli_error($link)) : "";
                ($list_all_repayment == '' && $list_individual_loan_repayment == '' && $list_branch_loan_repayment == '1') ? $select = mysqli_query($link, "SELECT SUM(amount_to_pay) FROM payments WHERE remarks = 'paid' AND branchid = '$institution_id' AND sbranchid = '$isbranchid'") or die (mysqli_error($link)) : "";
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
if(((mysqli_num_rows($isearch_maintenance_model) == 1 && $billing_type == "Hybrid") || mysqli_num_rows($isearch_maintenance_model) == 0) && ($irole == "agent_manager" || $irole == 'institution_super_admin' || $irole == "merchant_super_admin"))
{
?>

        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">
              <span class="info-box-icon bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?> elevation-1"><i class="fas fa-desktop"></i></span>

              <div class="info-box-content">
                <span class="info-box-text"><?php echo ($irole == "agent_manager" || $irole == "institution_super_admin" || $irole == "merchant_super_admin") ? '<a href="saassub_history.php?tid='.$_SESSION['tid'].'&&mid=NDIw">Total Sub. Amount <i class="fa fa-arrow-circle-right"></i></a>' : '<a href="#">Total Sub. Amount</a>'; ?></span>
                <span class="info-box-number">
                <?php 
                $select = mysqli_query($link, "SELECT SUM(amount_paid) FROM saas_subscription_trans WHERE status = 'Paid' AND coopid_instid = '$institution_id'") or die (mysqli_error($link));
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
        <!-- /.col -->

<?php
}
else{
  echo '';
}
?>



<?php
$check = mysqli_query($link, "SELECT * FROM my_permission WHERE urole = '$irole'") or die ("Error" . mysqli_error($link));
$get_check = mysqli_fetch_array($check);
$access_customer_tab = $get_check['access_customer_tab'];
$view_all_customers = $get_check['view_all_customers'];
if(($access_customer_tab == '1' || $view_all_customers == '1' || $individual_customer_records == '1' || $branch_customer_records == '1' || $add_customer == '1') && $icustomer_manager == 'On')
{
?>
      <div class="col-12 col-sm-6 col-md-3">
                <div class="info-box">
                  <span class="info-box-icon bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?> elevation-1"><i class="fas fa-users"></i></span>
    
                  <div class="info-box-content">
                    <span class="info-box-text"><?php echo ($view_all_customers == '1' || $individual_customer_records == '1' || $branch_customer_records == '1') ? '<a href="customer.php?tid='.$_SESSION['tid'].'&&mid='.base64_encode("403").'">Customers <i class="fa fa-arrow-circle-right"></i></a>' : '<a href="#">Customers</a>'; ?></span>
                    <span class="info-box-number">
                    <?php
                    ($view_all_customers == '1' && $individual_customer_records == '' && $branch_customer_records == '') ? $select = mysqli_query($link, "SELECT * FROM borrowers WHERE branchid = '$institution_id' AND acct_status != 'Closed'") or die (mysqli_error($link)) : "";
                    ($view_all_customers == '' && $individual_customer_records == '1' && $branch_customer_records == '') ? $select = mysqli_query($link, "SELECT * FROM borrowers WHERE branchid = '$institution_id' AND acct_status != 'Closed' AND lofficer = '$iuid'") or die (mysqli_error($link)) : "";
                    ($view_all_customers == '' && $individual_customer_records == '' && $branch_customer_records == '1') ? $select = mysqli_query($link, "SELECT * FROM borrowers WHERE branchid = '$institution_id' AND acct_status != 'Closed' AND sbranchid = '$isbranchid'") or die (mysqli_error($link)) : "";
                    $num = mysqli_num_rows($select);
                    echo $num;
                    ?>
                   </span>
                  </div>
                  <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
            </div>
            <!-- /.col -->

            <div class="col-12 col-sm-6 col-md-3">
                <div class="info-box">
                  <span class="info-box-icon bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?> elevation-1"><i class="fas fa-money"></i></span>
    
                  <div class="info-box-content">
                    <span class="info-box-text"><a href="#">Total Ledger Balance</a></span>
                    <span class="info-box-number">
                    <?php
                    ($view_all_customers == '1' && $individual_customer_records == '' && $branch_customer_records == '') ? $selectBal = mysqli_query($link, "SELECT SUM(balance) FROM borrowers WHERE branchid = '$institution_id' AND acct_status != 'Closed'") or die (mysqli_error($link)) : "";
                    ($view_all_customers == '' && $individual_customer_records == '1' && $branch_customer_records == '') ? $selectBal = mysqli_query($link, "SELECT SUM(balance) FROM borrowers WHERE branchid = '$institution_id' AND acct_status != 'Closed' AND lofficer = '$iuid'") or die (mysqli_error($link)) : "";
                    ($view_all_customers == '' && $individual_customer_records == '' && $branch_customer_records == '1') ? $selectBal = mysqli_query($link, "SELECT SUM(balance) FROM borrowers WHERE branchid = '$institution_id' AND acct_status != 'Closed' AND sbranchid = '$isbranchid'") or die (mysqli_error($link)) : "";
                    $fetchBal = mysqli_fetch_array($selectBal);
                    echo "<b>".number_format($fetchBal['SUM(balance)'],2,'.',',')."</b>";
                    ?>
                   </span>
                  </div>
                  <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
            </div>
            <!-- /.col -->

            <div class="col-12 col-sm-6 col-md-3">
                <div class="info-box">
                  <span class="info-box-icon bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?> elevation-1"><i class="fas fa-money"></i></span>
    
                  <div class="info-box-content">
                    <span class="info-box-text"><a href="#">Total Target Balance</a></span>
                    <span class="info-box-number">
                    <?php
                    ($view_all_customers == '1' && $individual_customer_records == '' && $branch_customer_records == '') ? $selectBal2 = mysqli_query($link, "SELECT SUM(target_savings_bal) FROM borrowers WHERE branchid = '$institution_id' AND acct_status != 'Closed'") or die (mysqli_error($link)) : "";
                    ($view_all_customers == '' && $individual_customer_records == '1' && $branch_customer_records == '') ? $selectBal2 = mysqli_query($link, "SELECT SUM(target_savings_bal) FROM borrowers WHERE branchid = '$institution_id' AND acct_status != 'Closed' AND lofficer = '$iuid'") or die (mysqli_error($link)) : "";
                    ($view_all_customers == '' && $individual_customer_records == '' && $branch_customer_records == '1') ? $selectBal2 = mysqli_query($link, "SELECT SUM(target_savings_bal) FROM borrowers WHERE branchid = '$institution_id' AND acct_status != 'Closed' AND sbranchid = '$isbranchid'") or die (mysqli_error($link)) : "";
                    $fetchBal2 = mysqli_fetch_array($selectBal2);
                    echo "<b>".number_format($fetchBal2['SUM(target_savings_bal)'],2,'.',',')."</b>";
                    ?>
                   </span>
                  </div>
                  <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
            </div>
            <!-- /.col -->

            <div class="col-12 col-sm-6 col-md-3">
                <div class="info-box">
                  <span class="info-box-icon bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?> elevation-1"><i class="fas fa-money"></i></span>
    
                  <div class="info-box-content">
                    <span class="info-box-text"><a href="#">Total Investment Balance</a></span>
                    <span class="info-box-number">
                    <?php
                    ($view_all_customers == '1' && $individual_customer_records == '' && $branch_customer_records == '') ? $selectBal3 = mysqli_query($link, "SELECT SUM(investment_bal) FROM borrowers WHERE branchid = '$institution_id' AND acct_status != 'Closed'") or die (mysqli_error($link)) : "";
                    ($view_all_customers == '' && $individual_customer_records == '1' && $branch_customer_records == '') ? $selectBal3 = mysqli_query($link, "SELECT SUM(investment_bal) FROM borrowers WHERE branchid = '$institution_id' AND acct_status != 'Closed' AND lofficer = '$iuid'") or die (mysqli_error($link)) : "";
                    ($view_all_customers == '' && $individual_customer_records == '' && $branch_customer_records == '1') ? $selectBal3 = mysqli_query($link, "SELECT SUM(investment_bal) FROM borrowers WHERE branchid = '$institution_id' AND acct_status != 'Closed' AND sbranchid = '$isbranchid'") or die (mysqli_error($link)) : "";
                    $fetchBal3 = mysqli_fetch_array($selectBal3);
                    echo "<b>".number_format($fetchBal3['SUM(investment_bal)'],2,'.',',')."</b>";
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
/**
$check = mysqli_query($link, "SELECT * FROM my_permission WHERE urole = '$irole'") or die ("Error" . mysqli_error($link));
$get_check = mysqli_fetch_array($check);
$access_helpdesk_tab = $get_check['access_helpdesk_tab'];
$view_all_tickets = $get_check['view_due_loans'];
if($view_all_tickets == '1' || $access_helpdesk_tab == '1')
{
?>
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">
              <span class="info-box-icon bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?> elevation-1"><i class="fas fa-commenting"></i></span>

              <div class="info-box-content">
                <span class="info-box-text"><a href="inboxmessage.php?tid=<?php echo $_SESSION['tid']; ?>&&mid=<?php echo base64_encode("406"); ?>">Helpdesk Message <i class="fa fa-arrow-circle-right"></i></a></span>
                <span class="info-box-number">
                <?php
                $select = mysqli_query($link, "SELECT * FROM message WHERE branchid = '$institution_id'") or die (mysqli_error($link));
                $num = mysqli_num_rows($select);
                echo $num;
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
*/
?>


<?php
$check = mysqli_query($link, "SELECT * FROM my_permission WHERE urole = '$irole'") or die ("Error" . mysqli_error($link));
$get_check = mysqli_fetch_array($check);
$access_expense_tab = $get_check['access_expense_tab'];
$view_expenses = $get_check['view_expenses'];
if(($access_expense_tab == '1' || $view_expenses == '1') && $iexpenses_module == 'On')
{
?>  
       <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">
              <span class="info-box-icon bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?> elevation-1"><i class="fas fa-money"></i></span>

              <div class="info-box-content">
                <span class="info-box-text"><?php echo ($view_expenses == 1) ? '<a href="listexpenses.php?tid='.$_SESSION['tid'].'&&mid='.base64_encode("422").'">Total Expenses <i class="fa fa-arrow-circle-right"></i></a>' : '<a href="#">Total Expenses</a>'; ?></span>
                <span class="info-box-number">
                <?php 
                $date_now = date("Y-m-d");
                $select = mysqli_query($link, "SELECT SUM(eamt) FROM expenses WHERE branchid = '$institution_id'") or die ("Error: " . mysqli_error($link));
                if(mysqli_num_rows($select) == 0)
                {
                echo '0.00';
                }
                else{
                    $row = mysqli_fetch_array($select);
                    echo number_format($row['SUM(eamt)'],2,".",",")."</b>";
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
$check = mysqli_query($link, "SELECT * FROM my_permission WHERE urole = '$irole'") or die ("Error:" . mysqli_error($link));
$get_check = mysqli_fetch_array($check);
$view_income = $get_check['view_income'];
if($view_income == '1' && (mysqli_num_rows($isearch_maintenance_model) == 1 || mysqli_num_rows($isearch_maintenance_model) == 0) && $model != "Hybrid" && $iincome_module == 'On')
{
?>  
       <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">
              <span class="info-box-icon bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?> elevation-1"><i class="fas fa-recycle"></i></span>

              <div class="info-box-content">
                <span class="info-box-text"><?php echo ($view_income == 1) ? '<a href="listincome.php?tid='.$_SESSION['tid'].'&&mid='.base64_encode("500").'">Total Income <i class="fa fa-arrow-circle-right"></i></a>' : '<a href="#">Total Income</a>'; ?></span>
                <span class="info-box-number">
                <?php 
                $date_now = date("Y-m-d");
                $select = mysqli_query($link, "SELECT SUM(icm_amt) FROM income WHERE companyid = '$institution_id'") or die ("Error: " . mysqli_error($link));
                if(mysqli_num_rows($select) == 0)
                {
                echo '0.00';
                }
                else{
                    $row = mysqli_fetch_array($select);
                    echo number_format($row['SUM(icm_amt)'],2,".",",")."</b>";
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
if($isavings_account == 'On')
{
?>

<?php
$check = mysqli_query($link, "SELECT * FROM my_permission WHERE urole = '$irole'") or die ("Error" . mysqli_error($link));
$get_check = mysqli_fetch_array($check);
$access_savings_tab = $get_check['access_savings_tab'];
$view_all_transaction = $get_check['view_all_transaction'];
$deposit_money = $get_check['deposit_money'];
if($access_savings_tab == '1' || $view_all_transaction == '1' || $individual_transaction_records == '1' || $branch_transaction_records == '1')
{
?> 

    <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">
              <span class="info-box-icon bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?> elevation-1"><i class="fas fa-plus"></i></span>

              <div class="info-box-content">
                <span class="info-box-text"><?php echo ($view_all_transaction == 1 || $individual_transaction_records == '1' || $branch_transaction_records == '1') ? '<a href="transaction.php?tid='.$_SESSION['tid'].'&&mid='.base64_encode("408").'">Total Deposit <i class="fa fa-arrow-circle-right"></i></a>' : '<a href="#">Total Deposit</a>'; ?></span>
                <span class="info-box-number">
                <?php 
                ($view_all_transaction == '1' && $individual_transaction_records == '' && $branch_transaction_records == '' && ($irole == "agent_manager" || $irole == "institution_super_admin" || $irole == "merchant_super_admin")) ? $select = mysqli_query($link, "SELECT SUM(amount) FROM transaction WHERE t_type = 'Deposit' AND branchid = '$institution_id'") or die (mysqli_error($link)) : "";
                ($view_all_transaction == '1' && $individual_transaction_records == '' && $branch_transaction_records == '' && ($irole != "agent_manager" && $irole != "institution_super_admin" && $irole != "merchant_super_admin")) ? $select = mysqli_query($link, "SELECT SUM(amount) FROM transaction WHERE t_type = 'Deposit' AND branchid = '$institution_id' AND posted_by = '$iuid'") or die (mysqli_error($link)) : "";
                ($view_all_transaction == '' && ($individual_transaction_records == '1' || $deposit_money == '1') && $branch_transaction_records == '') ? $select = mysqli_query($link, "SELECT SUM(amount) FROM transaction WHERE t_type = 'Deposit' AND branchid = '$institution_id' AND posted_by = '$iuid'") or die (mysqli_error($link)) : "";
                ($view_all_transaction == '' && $individual_transaction_records == '' && $branch_transaction_records == '1') ? $select = mysqli_query($link, "SELECT SUM(amount) FROM transaction WHERE t_type = 'Deposit' AND branchid = '$institution_id' AND sbranchid = '$isbranchid'") or die (mysqli_error($link)) : "";
                if(mysqli_num_rows($select) == 0)
                {
                echo "0.00";
                }
                else{
                    $row = mysqli_fetch_array($select);
                    echo number_format($row['SUM(amount)'],2,".",",")."</b>";
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
$check = mysqli_query($link, "SELECT * FROM my_permission WHERE urole = '$irole'") or die ("Error" . mysqli_error($link));
$get_check = mysqli_fetch_array($check);
$access_savings_tab = $get_check['access_savings_tab'];
$view_all_transaction = $get_check['view_all_transaction'];
if($access_savings_tab == '1' || $view_all_transaction == '1' || $individual_transaction_records == '1' || $branch_transaction_records == '1')
{
?>    
    
    <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">
              <span class="info-box-icon bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?> elevation-1"><i class="fas fa-minus"></i></span>

              <div class="info-box-content">
                <span class="info-box-text"><?php echo ($view_all_transaction == '1' || $individual_transaction_records == '1' || $branch_transaction_records == '1') ? '<a href="transaction.php?tid='.$_SESSION['tid'].'&&mid='.base64_encode("408").'">Total Withdraw <i class="fa fa-arrow-circle-right"></i></a>' : '<a href="#">Total Withdraw</a>'; ?></span>
                <span class="info-box-number">
                <?php 
                ($view_all_transaction == '1' && $individual_transaction_records == '' && $branch_transaction_records == '' && ($irole == "agent_manager" || $irole == "institution_super_admin" || $irole == "merchant_super_admin")) ? $select = mysqli_query($link, "SELECT SUM(amount) FROM transaction WHERE t_type = 'Withdraw' AND branchid = '$institution_id'") or die (mysqli_error($link)) : "";
                ($view_all_transaction == '1' && $individual_transaction_records == '' && $branch_transaction_records == '' && ($irole != "agent_manager" && $irole != "institution_super_admin" && $irole != "merchant_super_admin")) ? $select = mysqli_query($link, "SELECT SUM(amount) FROM transaction WHERE t_type = 'Withdraw' AND branchid = '$institution_id' AND posted_by = '$iuid'") or die (mysqli_error($link)) : "";
                ($view_all_transaction == '' && ($individual_transaction_records == '1' || $withdraw_money == '1') && $branch_transaction_records == '') ? $select = mysqli_query($link, "SELECT SUM(amount) FROM transaction WHERE t_type = 'Withdraw' AND branchid = '$institution_id' AND posted_by = '$iuid'") or die (mysqli_error($link)) : "";
                ($view_all_transaction == '' && $individual_transaction_records == '' && $branch_transaction_records == '1') ? $select = mysqli_query($link, "SELECT SUM(amount) FROM transaction WHERE t_type = 'Withdraw' AND branchid = '$institution_id' AND sbranchid = '$isbranchid'") or die (mysqli_error($link)) : "";
                if(mysqli_num_rows($select)==0)
                {
                echo "0.00";
                }
                else{
                    $row = mysqli_fetch_array($select);
                    echo number_format($row['SUM(amount)'],2,".",",")."</b>";
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
$check = mysqli_query($link, "SELECT * FROM my_permission WHERE urole = '$irole'") or die ("Error" . mysqli_error($link));
$get_check = mysqli_fetch_array($check);
$access_savings_tab = $get_check['access_savings_tab'];
$view_all_transaction = $get_check['view_all_transaction'];
if($access_savings_tab == '1' || $view_all_transaction == '1' || $individual_transaction_records == '1' || $branch_transaction_records == '1')
{
?>    
    <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">
              <span class="info-box-icon bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?> elevation-1"><i class="fas fa-map-marker"></i></span>

              <div class="info-box-content">
                <span class="info-box-text"><?php echo ($view_all_transaction == 1 || $individual_transaction_records == '1' || $branch_transaction_records == '1') ? '<a href="transaction.php?tid='.$_SESSION['tid'].'&&mid='.base64_encode("408").'">Total Balance<i class="fa fa-arrow-circle-right"></i></a>' : '<a href="#">Total Balance</a>'; ?></span>
                <span class="info-box-number">
                <?php 
                ($view_all_transaction == '1' && $individual_transaction_records == '' && $branch_transaction_records == '' && ($irole == "agent_manager" || $irole == "institution_super_admin" || $irole == "merchant_super_admin")) ? $select = mysqli_query($link, "SELECT SUM(balance) FROM borrowers WHERE branchid = '$institution_id'") or die (mysqli_error($link)) : "";
                ($view_all_transaction == '1' && $individual_transaction_records == '' && $branch_transaction_records == '' && ($irole != "agent_manager" && $irole != "institution_super_admin" && $irole != "merchant_super_admin")) ? $select = mysqli_query($link, "SELECT SUM(balance) FROM borrowers WHERE branchid = '$institution_id' AND lofficer = '$iuid'") or die (mysqli_error($link)) : "";
                ($view_all_transaction == '' && ($individual_transaction_records == '1' || $deposit_money == '1' || $withdraw_money == '1') && $branch_transaction_records == '') ? $select = mysqli_query($link, "SELECT SUM(balance) FROM borrowers WHERE branchid = '$institution_id' AND lofficer = '$iuid'") or die (mysqli_error($link)) : "";
                ($view_all_transaction == '' && $individual_transaction_records == '' && $branch_transaction_records == '1') ? $select = mysqli_query($link, "SELECT SUM(balance) FROM borrowers WHERE branchid = '$institution_id' AND sbranchid = '$isbranchid'") or die (mysqli_error($link)) : "";
                $num = mysqli_num_rows($select);
                if($num == 0)
                {
                echo "0.00";
                }
                else{
                    $row = mysqli_fetch_array($select);
                    $balance = number_format($row['SUM(balance)'],2,'.',',');
                    echo $balance."</b>";
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
$check = mysqli_query($link, "SELECT * FROM my_permission WHERE urole = '$irole'") or die ("Error" . mysqli_error($link));
$get_check = mysqli_fetch_array($check);
$access_savings_tab = $get_check['access_savings_tab'];
$view_all_transaction = $get_check['view_all_transaction'];
if($access_savings_tab == '1' || $view_all_transaction == '1' || $individual_transaction_records == '1' || $branch_transaction_records == '1')
{
?>  
    <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">
              <span class="info-box-icon bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?> elevation-1"><i class="fas fa-database"></i></span>

              <div class="info-box-content">
                <span class="info-box-text"><?php echo ($view_all_transaction == 1 || $individual_transaction_records == '1' || $branch_transaction_records == '1') ? '<a href="transaction.php?tid='.$_SESSION['tid'].'&&mid='.base64_encode("408").'">Total Transaction <i class="fa fa-arrow-circle-right"></i></a>' : '<a href="#">Total Transaction</a>'; ?></span>
                <span class="info-box-number">
                <?php 
                ($view_all_transaction == '1' && $individual_transaction_records == '' && $branch_transaction_records == '' && ($irole == "agent_manager" || $irole == "institution_super_admin" || $irole == "merchant_super_admin")) ? $selecte = mysqli_query($link, "SELECT * FROM transaction WHERE branchid = '$institution_id'") or die ("Error: " . mysqli_error($link)) : "";
                ($view_all_transaction == '1' && $individual_transaction_records == '' && $branch_transaction_records == '' && ($irole != "agent_manager" && $irole != "institution_super_admin" && $irole != "merchant_super_admin")) ? $selecte = mysqli_query($link, "SELECT * FROM transaction WHERE branchid = '$institution_id' AND posted_by = '$iuid'") or die ("Error: " . mysqli_error($link)) : "";
                ($view_all_transaction == '' && ($individual_transaction_records == '1' || $deposit_money == '1' || $withdraw_money == '1') && $branch_transaction_records == '') ? $selecte = mysqli_query($link, "SELECT * FROM transaction WHERE branchid = '$institution_id' AND posted_by = '$iuid'") or die ("Error: " . mysqli_error($link)) : "";
                ($view_all_transaction == '' && $individual_transaction_records == '' && $branch_transaction_records == '1') ? $selecte = mysqli_query($link, "SELECT * FROM transaction WHERE branchid = '$institution_id' AND sbranchid = '$isbranchid'") or die ("Error: " . mysqli_error($link)) : "";
                $nume = mysqli_num_rows($selecte);
                echo number_format($nume,0,'',',');
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
$today_record = date("Y-m-d");
$check = mysqli_query($link, "SELECT * FROM my_permission WHERE urole = '$irole'") or die ("Error" . mysqli_error($link));
$get_check = mysqli_fetch_array($check);
$access_savings_tab = $get_check['access_savings_tab'];
$view_all_transaction = $get_check['view_all_transaction'];
if($access_savings_tab == '1' || $view_all_transaction == '1' || $individual_transaction_records == '1' || $branch_transaction_records == '1' || $deposit_money == '1')
{
?> 
    
    <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">
              <span class="info-box-icon bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?> elevation-1"><i class="fas fa-plus"></i></span>

              <div class="info-box-content">
                <span class="info-box-text"><?php echo ($view_all_transaction == 1 || $individual_transaction_records == '1' || $branch_transaction_records == '1') ? '<a href="transaction.php?tid='.$_SESSION['tid'].'&&mid=NDEw&&status">Daily Deposit <i class="fa fa-arrow-circle-right"></i></a>' : '<a href="#">Daily Deposit</a>'; ?></span>
                <span class="info-box-number">
                <?php 
                ($view_all_transaction == '1' && $individual_transaction_records == '' && $branch_transaction_records == '' && ($irole == "agent_manager" || $irole == "institution_super_admin" || $irole == "merchant_super_admin")) ? $select = mysqli_query($link, "SELECT SUM(amount) FROM transaction WHERE date_time LIKE '$today_record%' AND t_type = 'Deposit' AND branchid = '$institution_id'") or die (mysqli_error($link)) : "";
                ($view_all_transaction == '1' && $individual_transaction_records == '' && $branch_transaction_records == '' && ($irole != "agent_manager" && $irole != "institution_super_admin" && $irole != "merchant_super_admin")) ? $select = mysqli_query($link, "SELECT SUM(amount) FROM transaction WHERE date_time LIKE '$today_record%' AND t_type = 'Deposit' AND branchid = '$institution_id' AND posted_by = '$iuid'") or die (mysqli_error($link)) : "";
                ($view_all_transaction == '' && ($individual_transaction_records == '1' || $deposit_money == '1') && $branch_transaction_records == '') ? $select = mysqli_query($link, "SELECT SUM(amount) FROM transaction WHERE date_time LIKE '$today_record%' AND t_type = 'Deposit' AND branchid = '$institution_id' AND posted_by = '$iuid'") or die (mysqli_error($link)) : "";
                ($view_all_transaction == '' && $individual_transaction_records == '' && $branch_transaction_records == '1') ? $select = mysqli_query($link, "SELECT SUM(amount) FROM transaction WHERE date_time LIKE '$today_record%' AND t_type = 'Deposit' AND branchid = '$institution_id' AND sbranchid = '$isbranchid'") or die (mysqli_error($link)) : "";
                if(mysqli_num_rows($select)==0)
                {
                echo "0.00";
                }
                else{
                    $row = mysqli_fetch_array($select);
                    echo number_format($row['SUM(amount)'],2,".",",")."</b>";
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
$check = mysqli_query($link, "SELECT * FROM my_permission WHERE urole = '$irole'") or die ("Error" . mysqli_error($link));
$get_check = mysqli_fetch_array($check);
$access_savings_tab = $get_check['access_savings_tab'];
$view_all_transaction = $get_check['view_all_transaction'];
if($access_savings_tab == '1' || $view_all_transaction == '1' || $individual_transaction_records == '1' || $branch_transaction_records == '1' || $withdraw_money == '1')
{
?>    
    
    <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">
              <span class="info-box-icon bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?> elevation-1"><i class="fas fa-minus"></i></span>

              <div class="info-box-content">
                <span class="info-box-text"><?php echo ($view_all_transaction == 1 || $individual_transaction_records == '1' || $branch_transaction_records == '1') ? '<a href="transaction.php?tid='.$_SESSION['tid'].'&&mid=NDEw&&status">Daily Withdraw <i class="fa fa-arrow-circle-right"></i></a>' : '<a href="#">Daily Withdraw</a>'; ?></span>
                <span class="info-box-number">
                <?php 
                ($view_all_transaction == '1' && $individual_transaction_records == '' && $branch_transaction_records == '' && ($irole == "agent_manager" || $irole == "institution_super_admin" || $irole == "merchant_super_admin")) ? $select = mysqli_query($link, "SELECT SUM(amount) FROM transaction WHERE date_time LIKE '$today_record%' AND t_type = 'Withdraw' AND branchid = '$institution_id'") or die (mysqli_error($link)) : "";
                ($view_all_transaction == '1' && $individual_transaction_records == '' && $branch_transaction_records == '' && ($irole != "agent_manager" && $irole != "institution_super_admin" && $irole != "merchant_super_admin")) ? $select = mysqli_query($link, "SELECT SUM(amount) FROM transaction WHERE date_time LIKE '$today_record%' AND t_type = 'Withdraw' AND branchid = '$institution_id' AND posted_by = '$iuid'") or die (mysqli_error($link)) : "";
                ($view_all_transaction == '' && ($individual_transaction_records == '1' || $withdraw_money == '1') && $branch_transaction_records == '') ? $select = mysqli_query($link, "SELECT SUM(amount) FROM transaction WHERE date_time LIKE '$today_record%' AND t_type = 'Withdraw' AND branchid = '$institution_id' AND posted_by = '$iuid'") or die (mysqli_error($link)) : "";
                ($view_all_transaction == '' && $individual_transaction_records == '' && $branch_transaction_records == '1') ? $select = mysqli_query($link, "SELECT SUM(amount) FROM transaction WHERE date_time LIKE '$today_record%' AND t_type = 'Withdraw' AND branchid = '$institution_id' AND sbranchid = '$isbranchid'") or die (mysqli_error($link)) : "";
                if(mysqli_num_rows($select)==0)
                {
                echo "0.00";
                }
                else{
                    $row = mysqli_fetch_array($select);
                    echo number_format($row['SUM(amount)'],2,".",",")."</b>";
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
$check = mysqli_query($link, "SELECT * FROM my_permission WHERE urole = '$irole'") or die ("Error" . mysqli_error($link));
$get_check = mysqli_fetch_array($check);
$access_savings_tab = $get_check['access_savings_tab'];
$view_all_transaction = $get_check['view_all_transaction'];
if($access_savings_tab == '1' || $view_all_transaction == '1' || $individual_transaction_records == '1' || $branch_transaction_records == '1' || $deposit_money == '1' || $withdraw_money == '1')
{
?>    
    
    <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">
              <span class="info-box-icon bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?> elevation-1"><i class="fas fa-map-marker"></i></span>

              <div class="info-box-content">
                <span class="info-box-text"><?php echo ($view_all_transaction == 1 || $individual_transaction_records == '1' || $branch_transaction_records == '1') ? '<a href="transaction.php?tid='.$_SESSION['tid'].'&&mid=NDEw&&status">Daily Balance <i class="fa fa-arrow-circle-right"></i></a>' : '<a href="#">Daily Balance</a>'; ?></span>
                <span class="info-box-number">
                <?php 
                ($view_all_transaction == '1' && $individual_transaction_records == '' && $branch_transaction_records == '' && ($irole == "agent_manager" || $irole == "institution_super_admin" || $irole == "merchant_super_admin")) ? $select = mysqli_query($link, "SELECT SUM(amount) FROM transaction WHERE date_time LIKE '$today_record%' AND branchid = '$institution_id' AND t_type = 'Withdraw'") or die (mysqli_error($link)) : "";
                ($view_all_transaction == '1' && $individual_transaction_records == '' && $branch_transaction_records == '' && ($irole != "agent_manager" && $irole != "institution_super_admin" && $irole != "merchant_super_admin")) ? $select = mysqli_query($link, "SELECT SUM(amount) FROM transaction WHERE date_time LIKE '$today_record%' AND branchid = '$institution_id' AND t_type = 'Withdraw' AND posted_by = '$iuid'") or die (mysqli_error($link)) : "";
                ($view_all_transaction == '' && ($individual_transaction_records == '1' || $deposit_money == '1' || $withdraw_money == '1') && $branch_transaction_records == '') ? $select = mysqli_query($link, "SELECT SUM(amount) FROM transaction WHERE date_time LIKE '$today_record%' AND branchid = '$institution_id' AND posted_by = '$iuid' AND t_type = 'Withdraw'") or die (mysqli_error($link)) : "";
                ($view_all_transaction == '' && $individual_transaction_records == '' && $branch_transaction_records == '1') ? $select = mysqli_query($link, "SELECT SUM(amount) FROM transaction WHERE date_time LIKE '$today_record%' AND branchid = '$institution_id' AND sbranchid = '$isbranchid' AND t_type = 'Withdraw'") or die (mysqli_error($link)) : "";
                $num = mysqli_num_rows($select);
                
                ($view_all_transaction == '1' && $individual_transaction_records == '' && $branch_transaction_records == '' && ($irole == "agent_manager" || $irole == "institution_super_admin" || $irole == "merchant_super_admin")) ? $select2 = mysqli_query($link, "SELECT SUM(amount) FROM transaction WHERE date_time LIKE '$today_record%' AND branchid = '$institution_id' AND t_type = 'Deposit'") or die (mysqli_error($link)) : "";
                ($view_all_transaction == '1' && $individual_transaction_records == '' && $branch_transaction_records == '' && ($irole != "agent_manager" && $irole != "institution_super_admin" && $irole != "merchant_super_admin")) ? $select2 = mysqli_query($link, "SELECT SUM(amount) FROM transaction WHERE date_time LIKE '$today_record%' AND branchid = '$institution_id' AND t_type = 'Deposit' AND posted_by = '$iuid'") or die (mysqli_error($link)) : "";
                ($view_all_transaction == '' && ($individual_transaction_records == '1' || $deposit_money == '1' || $withdraw_money == '1') && $branch_transaction_records == '') ? $select2 = mysqli_query($link, "SELECT SUM(amount) FROM transaction WHERE date_time LIKE '$today_record%' AND branchid = '$institution_id' AND posted_by = '$iuid' AND t_type = 'Deposit'") or die (mysqli_error($link)) : "";
                ($view_all_transaction == '' && $individual_transaction_records == '' && $branch_transaction_records == '1') ? $select2 = mysqli_query($link, "SELECT SUM(amount) FROM transaction WHERE date_time LIKE '$today_record%' AND branchid = '$institution_id' AND sbranchid = '$isbranchid' AND t_type = 'Deposit'") or die (mysqli_error($link)) : "";
                $num1 = mysqli_num_rows($select2);
                if($num == 0 && $num2 == 0)
                {
                echo "0.00";
                }
                else{
                    $row = mysqli_fetch_array($select);
                    $twithdraw = $row['SUM(amount)'];
                    
                    $row2 = mysqli_fetch_array($select2);
                    $tdeposit = $row2['SUM(amount)'];
                    
                    $balance = number_format(($tdeposit-$twithdraw),2,'.',',');
                    echo $balance."</b>";
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
$check = mysqli_query($link, "SELECT * FROM my_permission WHERE urole = '$irole'") or die ("Error" . mysqli_error($link));
$get_check = mysqli_fetch_array($check);
$access_savings_tab = $get_check['access_savings_tab'];
$view_all_transaction = $get_check['view_all_transaction'];
if($access_savings_tab == '1' || $view_all_transaction == '1' || $individual_transaction_records == '1' || $branch_transaction_records == '1' || $deposit_money == '1' || $withdraw_money == '1')
{
?>  
    
    <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">
              <span class="info-box-icon bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?> elevation-1"><i class="fas fa-database"></i></span>

              <div class="info-box-content">
                <span class="info-box-text"><?php echo ($view_all_transaction == 1 || $individual_transaction_records == '1' || $branch_transaction_records == '1') ? '<a href="transaction.php?tid='.$_SESSION['tid'].'&&mid=NDEw&&status">Daily Transaction <i class="fa fa-arrow-circle-right"></i></a>' : '<a href="#">Daily Transaction</a>'; ?></span>
                <span class="info-box-number">
                <?php 
                ($view_all_transaction == '1' && $individual_transaction_records == '' && $branch_transaction_records == '' && ($irole == "agent_manager" || $irole == "institution_super_admin" || $irole == "merchant_super_admin")) ? $selecte = mysqli_query($link, "SELECT * FROM transaction WHERE date_time LIKE '$today_record%' AND branchid = '$institution_id'") or die (mysqli_error($link)) : "";
                ($view_all_transaction == '1' && $individual_transaction_records == '' && $branch_transaction_records == '' && ($irole != "agent_manager" && $irole != "institution_super_admin" && $irole != "merchant_super_admin")) ? $selecte = mysqli_query($link, "SELECT * FROM transaction WHERE date_time LIKE '$today_record%' AND branchid = '$institution_id' AND posted_by = '$iuid'") or die (mysqli_error($link)) : "";
                ($view_all_transaction == '' && ($individual_transaction_records == '1' || $deposit_money == '1' || $withdraw_money == '1') && $branch_transaction_records == '') ? $selecte = mysqli_query($link, "SELECT * FROM transaction WHERE date_time LIKE '$today_record%' AND branchid = '$institution_id' AND posted_by = '$iuid'") or die (mysqli_error($link)) : "";
                ($view_all_transaction == '' && $individual_transaction_records == '' && $branch_transaction_records == '1') ? $selecte = mysqli_query($link, "SELECT * FROM transaction WHERE date_time LIKE '$today_record%' AND branchid = '$institution_id' AND sbranchid = '$isbranchid'") or die (mysqli_error($link)) : "";
                $nume = mysqli_num_rows($selecte);
                echo number_format($nume,0,'',',');
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
}
else{
    echo "";
}
?>




<?php
$check = mysqli_query($link, "SELECT * FROM my_permission WHERE urole = '$irole'") or die ("Error" . mysqli_error($link));
$get_check = mysqli_fetch_array($check);
$access_investment_tab = $get_check['access_investment_tab'];
$view_investment_transaction = $get_check['view_investment_transaction'];

if(($access_investment_tab == '1' || $view_investment_transaction == '1' || $all_product_transaction == '1') && ($iinvestment_manager == 'On' || $iproduct_manager == 'On'))
{
?> 
    <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">
              <span class="info-box-icon bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?> elevation-1"><i class="fas fa-handshake"></i></span>

              <div class="info-box-content">
                <span class="info-box-text"><?php echo ($view_investment_transaction == '1') ? '<a href="#">Total Product Savings <i class="fa fa-arrow-circle-right"></i></a>' : '<a href="#">Total Product Savings</a>'; ?></span>
                <span class="info-box-number">
                <?php 
                (($individual_customer_records != "1" && $branch_customer_records != "1") || ($individual_wallet != "1" && $branch_wallet != "1")) ? $select = mysqli_query($link, "SELECT SUM(amount) FROM all_savingssub_transaction WHERE status = 'successful' AND merchant_id = '$institution_id'") or die (mysqli_error($link)) : '';
                (($individual_customer_records === "1" && $branch_customer_records != "1") || ($individual_wallet === "1" && $branch_wallet != "1")) ? $select = mysqli_query($link, "SELECT SUM(amount) FROM all_savingssub_transaction WHERE status = 'successful' AND merchant_id = '$institution_id' AND agentid = '$iuid'") or die (mysqli_error($link)) : '';
                if(mysqli_num_rows($select)==0)
                {
                echo "0.00";
                }
                else{
                    $row = mysqli_fetch_array($select);
                    echo number_format($row['SUM(amount)'],2,".",",")."</b>";
                }
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



<?php
$check = mysqli_query($link, "SELECT * FROM my_permission WHERE urole = '$irole'") or die ("Error" . mysqli_error($link));
$get_check = mysqli_fetch_array($check);
$create_savings_plan = $get_check['create_savings_plan'];
$savings_subscription = $get_check['savings_subscription'];
if(($irole == 'agent_manager' || $irole == 'institution_super_admin' || $irole == "merchant_super_admin") && ($iinvestment_manager == 'On' || $iproduct_manager == 'On'))
{
?> 
    <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">
              <span class="info-box-icon bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?> elevation-1"><i class="fas fa-tasks"></i></span>

              <div class="info-box-content">
                <span class="info-box-text"><?php echo ($irole == 'agent_manager' || $irole == 'institution_super_admin' || $irole == "merchant_super_admin") ? '<a href="create_msavingsplan.php?tid='.$_SESSION['tid'].'&&mid='.base64_encode("490").'">All Product Plan <i class="fa fa-arrow-circle-right"></i></a>' : '<a href="#">All Product Plan</a>'; ?></span>
                <span class="info-box-number">
                <?php 
                $selecte = mysqli_query($link, "SELECT * FROM savings_plan WHERE merchantid_others = '$institution_id'") or die (mysqli_error($link));
                $nume = mysqli_num_rows($selecte);
                echo $nume;
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





<?php
$check = mysqli_query($link, "SELECT * FROM my_permission WHERE urole = '$irole'") or die ("Error" . mysqli_error($link));
$get_check = mysqli_fetch_array($check);
$access_employee_tab = $get_check['emp_tab'];
$list_employee = $get_check['list_employee'];
if(($access_employee_tab == '1' || $list_employee == '1' || $list_branch_employee == '1') && ($isubagent_manager == 'On' || $istaff_manager === "On"))
{
?>    
    <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">
              <span class="info-box-icon bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?> elevation-1"><i class="fas fa-user"></i></span>

              <div class="info-box-content">
                <span class="info-box-text"><?php echo ($list_employee == 1 || $list_branch_employee == '1') ? '<a href="listemployee.php?tid='.$_SESSION['tid'].'&&mid='.base64_encode("409").'">'.(($istaff_manager === "On") ? "Staff" : "Sub-Agent").' <i class="fa fa-arrow-circle-right"></i></a>' : '<a href="#">'.(($istaff_manager === "On") ? "Staff" : "Sub-Agent").'</a>'; ?></span>
                <span class="info-box-number">
                <?php
                ($list_employee == '1' && $list_branch_employee == '') ? $select = mysqli_query($link, "SELECT * FROM user WHERE id != '$tid' AND created_by = '$institution_id' AND role != 'agent_manager' AND role != 'institution_super_admin' AND role != 'merchant_super_admin' AND bprefix != 'VEN'") or die (mysqli_error($link)) : "";
                ($list_employee == '' && $list_branch_employee == '1') ? $select = mysqli_query($link, "SELECT * FROM user WHERE id != '$tid' AND created_by = '$institution_id' AND branchid = '$isbranchid' AND role != 'agent_manager' AND role != 'institution_super_admin' AND role != 'merchant_super_admin' AND bprefix != 'VEN'") or die (mysqli_error($link)) : "";
                $num = mysqli_num_rows($select);
                echo $num;
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
$check = mysqli_query($link, "SELECT * FROM my_permission WHERE urole = '$irole'") or die ("Error" . mysqli_error($link));
$get_check = mysqli_fetch_array($check);
$view_income = $get_check['view_income'];
if($view_income == '1' && mysqli_num_rows($isearch_maintenance_model) == 1 && $model == "Hybrid" && $iincome_module == 'On')
{
?>  
       <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">
              <span class="info-box-icon bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?> elevation-1"><i class="fas fa-recycle"></i></span>

              <div class="info-box-content">
                <span class="info-box-text"><?php echo ($view_income == 1) ? '<a href="listincome.php?tid='.$_SESSION['tid'].'&&mid='.base64_encode("500").'">Total Income <i class="fa fa-arrow-circle-right"></i></a>' : '<a href="#">Total Income</a>'; ?></span>
                <span class="info-box-number">
                <?php 
                $date_now = date("Y-m-d");
                $select = mysqli_query($link, "SELECT SUM(icm_amt) FROM income WHERE companyid = '$institution_id'") or die ("Error: " . mysqli_error($link));
                if(mysqli_num_rows($select) == 0)
                {
                echo '0.00';
                }
                else{
                    $row = mysqli_fetch_array($select);
                    echo number_format($row['SUM(icm_amt)'],2,".",",")."</b>";
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
if(($irole == "agent_manager" || $irole == 'institution_super_admin' || $irole == "merchant_super_admin") && $iwallet_manager === "On" && $access_wallet_tab == '1')
{
?>
      
<?php
$check = mysqli_query($link, "SELECT * FROM my_permission WHERE urole = '$irole'") or die ("Error" . mysqli_error($link));
$get_check = mysqli_fetch_array($check);
$access_wallet_tab = $get_check['access_wallet_tab'];
if($access_wallet_tab == '1')
{
?> 
    
    <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">
              <span class="info-box-icon bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?> elevation-1"><i class="fas fa-line-chart"></i></span>

              <div class="info-box-content">
                <span class="info-box-text"><?php echo ($access_wallet_tab == 1) ? '<a href="mywallet.php?tid='.$_SESSION['tid'].'&&mid=NDA0&&tab=tab_1">Wallet Transaction <i class="fa fa-arrow-circle-right"></i></a>' : '<a href="#">Wallet Transaction</a>'; ?></span>
                <span class="info-box-number">
                <?php 
                $select = mysqli_query($link, "SELECT * FROM wallet_history WHERE userid = '$institution_id' OR recipient = '$institution_id'") or die (mysqli_error($link));
                $number_wh = mysqli_num_rows($select);
                echo number_format($number_wh,0,".",",")."</b>";
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
}
else{
  echo '';
}
?>



<?php
$check = mysqli_query($link, "SELECT * FROM my_permission WHERE urole = '$irole'") or die ("Error" . mysqli_error($link));
$get_check = mysqli_fetch_array($check);
$create_card = $get_check['create_card'];
if($create_card == '1' && $irole != "agent_manager" && $irole != 'institution_super_admin' && $irole != "merchant_super_admin")
{
?> 
    
    <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">
              <span class="info-box-icon bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?> elevation-1"><i class="fas fa-line-chart"></i></span>

              <div class="info-box-content">
                <span class="info-box-text"><?php echo ($list_card == 1) ? '<a href="list_card.php?id='.$_SESSION['tid'].'&&mid=NTUw">Total Card Issued <i class="fa fa-arrow-circle-right"></i></a>' : '<a href="#">Total Card Issued</a>'; ?></span>
                <span class="info-box-number">
                <?php 
                (($individual_customer_records != "1" && $branch_customer_records != "1") || ($individual_wallet != "1" && $branch_wallet != "1")) ? $select = mysqli_query($link, "SELECT * FROM borrowers WHERE branchid = '$institution_id' AND card_reg = 'Yes' AND card_id != 'NULL' ORDER BY id") or die (mysqli_error($link)) : "";
                (($individual_customer_records === "1" && $branch_customer_records != "1") || ($individual_wallet === "1" && $branch_wallet != "1")) ? $select = mysqli_query($link, "SELECT * FROM borrowers WHERE branchid = '$institution_id' AND lofficer = '$iuid' AND card_reg = 'Yes' AND card_id != 'NULL' ORDER BY id") or die (mysqli_error($link)) : "";
                (($individual_customer_records != "1" && $branch_customer_records === "1") || ($individual_wallet != "1" && $branch_wallet === "1")) ? $select = mysqli_query($link, "SELECT * FROM borrowers WHERE branchid = '$institution_id' AND sbranchid = '$isbranchid' AND card_reg = 'Yes' AND card_id != 'NULL'  ORDER BY id") or die (mysqli_error($link)) : "";                
                $number_wh = mysqli_num_rows($select);
                echo number_format($number_wh,0,".",",")."</b>";
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
$check_till = mysqli_query($link, "SELECT * FROM till_account WHERE cashier = '$iuid'") or die ("Error" . mysqli_error($link));
if(mysqli_num_rows($check_till) == 1 && $iteller_manager == 'On')
{
    $fetch_till = mysqli_fetch_object($check_till);
?>
      <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">
              <span class="info-box-icon bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?> elevation-1"><i class="fas fa-google-wallet"></i></span>

              <div class="info-box-content">
                <span class="info-box-text"><a href="view_savings.php?tid=<?php echo $_SESSION['tid']; ?>&&idm=<?php echo $iuid; ?>&&mid=<?php echo base64_encode("401"); ?>">Till Balance <i class="fa fa-arrow-circle-right"></i></a></span>
                <span class="info-box-number">
                <?php
                echo number_format($fetch_till->balance,2,'.',',');
                ?>
                </span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <!-- /.col -->

        
    <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">
              <span class="info-box-icon bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?> elevation-1"><i class="fas fa-hand-grab-o"></i></span>

              <div class="info-box-content">
                <span class="info-box-text"><a href="#">Till Commission <i class="fa fa-arrow-circle-right"></i></a></span>
                <span class="info-box-number">
                <?php
                echo number_format($fetch_till->commission_balance,2,'.',',');
                ?>
                </span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <!-- /.col -->


        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">
              <span class="info-box-icon bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?> elevation-1"><i class="fas fa-google-wallet"></i></span>

              <div class="info-box-content">
                <span class="info-box-text"><a href="#">Unsettled Balance <i class="fa fa-arrow-circle-right"></i></a></span>
                <span class="info-box-number">
                <?php
                echo number_format($fetch_till->unsettled_balance,2,'.',',');
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
if($ipos_manager === "On")
{
?>

<?php
$check = mysqli_query($link, "SELECT * FROM my_permission WHERE urole = '$irole'") or die ("Error" . mysqli_error($link));
$get_check = mysqli_fetch_array($check);
$pos_tab = $get_check['pos_tab'];
$all_terminal_assigned = $get_check['all_terminal_assigned'];
$terminal_report = $get_check['terminal_report'];
if($pos_tab == '1' || $terminal_report == '1' || $all_terminal_assigned == '1')
{
?> 
    
    <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">
              <span class="info-box-icon bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?> elevation-1"><i class="fas fa-pie-chart"></i></span>

              <div class="info-box-content">
                <span class="info-box-text"><?php echo ($terminal_report == 1) ? '<a href="terminal_report.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("700").'">Pos Transaction <i class="fa fa-arrow-circle-right"></i></a>' : '<a href="#">Pos Transaction</a>'; ?></span>
                <span class="info-box-number">
                <?php 
                ($irole == 'agent_manager' || $irole == 'institution_super_admin' || $irole == 'merchant_super_admin') ? $select = mysqli_query($link, "SELECT SUM(total_transaction_count) FROM terminal_reg WHERE merchant_id = '$institution_id'") or die ("Error:" . mysqli_error($link)) : "";
                ($irole != 'agent_manager' && $irole != 'institution_super_admin' && $irole != 'merchant_super_admin') ? $select = mysqli_query($link, "SELECT SUM(total_transaction_count) FROM terminal_reg WHERE merchant_id = '$institution_id' AND (initiatedBy = '$iuid' OR tidoperator = '$iuid')") or die ("Error:" . mysqli_error($link)) : "";                
                $number_wh = mysqli_fetch_array($select);
                echo number_format($number_wh['SUM(total_transaction_count)'],0,".",",")."</b>";
                ?>
                </span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <!-- /.col --> 


        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">
              <span class="info-box-icon bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?> elevation-1"><i class="fas fa-sort-amount-desc"></i></span>

              <div class="info-box-content">
                <span class="info-box-text"><?php echo ($terminal_report == 1) ? '<a href="terminal_report.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("700").'">Pos Settlement <i class="fa fa-arrow-circle-right"></i></a>' : '<a href="#">Pos Settlement</a>'; ?></span>
                <span class="info-box-number">
                <?php 
                ($irole == 'agent_manager' || $irole == 'institution_super_admin' || $irole == 'merchant_super_admin') ? $select2 = mysqli_query($link, "SELECT SUM(settled_balance) FROM terminal_reg WHERE merchant_id = '$institution_id'") or die ("Error:" . mysqli_error($link)) : "";
                ($irole != 'agent_manager' && $irole != 'institution_super_admin' && $irole != 'merchant_super_admin') ? $select2 = mysqli_query($link, "SELECT SUM(settled_balance) FROM terminal_reg WHERE merchant_id = '$institution_id' AND (initiatedBy = '$iuid' OR tidoperator = '$iuid')") or die ("Error:" . mysqli_error($link)) : "";                
                $number_wh2 = mysqli_fetch_array($select2);
                echo number_format($number_wh2['SUM(settled_balance)'],2,".",",")."</b>";
                ?>
                </span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <!-- /.col -->


        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">
              <span class="info-box-icon bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?> elevation-1"><i class="fas fa-exclamation"></i></span>

              <div class="info-box-content">
                <span class="info-box-text"><?php echo ($terminal_report == 1) ? '<a href="terminal_report.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("700").'">Pos Pending Bal. <i class="fa fa-arrow-circle-right"></i></a>' : '<a href="#">Pos Pending Bal.</a>'; ?></span>
                <span class="info-box-number">
                <?php 
                ($irole == 'agent_manager' || $irole == 'institution_super_admin' || $irole == 'merchant_super_admin') ? $select3 = mysqli_query($link, "SELECT SUM(pending_balance) FROM terminal_reg WHERE merchant_id = '$institution_id'") or die ("Error:" . mysqli_error($link)) : "";
                ($irole != 'agent_manager' && $irole != 'institution_super_admin' && $irole != 'merchant_super_admin') ? $select3 = mysqli_query($link, "SELECT SUM(pending_balance) FROM terminal_reg WHERE merchant_id = '$institution_id' AND (initiatedBy = '$iuid' OR tidoperator = '$iuid')") or die ("Error:" . mysqli_error($link)) : "";                
                $number_wh3 = mysqli_fetch_array($select3);
                echo number_format($number_wh3['SUM(pending_balance)'],2,".",",")."</b>";
                ?>
                </span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <!-- /.col --> 

      
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">
              <span class="info-box-icon bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?> elevation-1"><i class="fas fa-sort-numeric-asc "></i></span>

              <div class="info-box-content">
                <span class="info-box-text"><?php echo ($terminal_report == 1) ? '<a href="terminal_assigned.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("700").'">Total Terminals <i class="fa fa-arrow-circle-right"></i></a>' : '<a href="#">Total Terminals</a>'; ?></span>
                <span class="info-box-number">
                <?php 
                ($irole == 'agent_manager' || $irole == 'institution_super_admin' || $irole == 'merchant_super_admin') ? $select4 = mysqli_query($link, "SELECT * FROM terminal_reg WHERE merchant_id = '$institution_id'") or die ("Error:" . mysqli_error($link)) : "";
                ($irole != 'agent_manager' && $irole != 'institution_super_admin' && $irole != 'merchant_super_admin') ? $select4 = mysqli_query($link, "SELECT * FROM terminal_reg WHERE merchant_id = '$institution_id' AND (initiatedBy = '$iuid' OR tidoperator = '$iuid')") or die ("Error:" . mysqli_error($link)) : "";                
                $number_wh4 = mysqli_num_rows($select4);
                echo number_format($number_wh4,0,".",",")."</b>";
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
}
else{
  echo '';
}
?>



<?php
if($iwallet_creation === "On")
{
?>


<?php
$check = mysqli_query($link, "SELECT * FROM my_permission WHERE urole = '$irole'") or die ("Error" . mysqli_error($link));
$get_check = mysqli_fetch_array($check);
$create_wallet = $get_check['create_wallet'];
$list_wallet = $get_check['list_wallet'];
$view_wallet_statement = $get_check['view_wallet_statement'];
if($create_wallet == '1' || $list_wallet == '1' || $view_wallet_statement == '1')
{
?> 
    
    <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">
              <span class="info-box-icon bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?> elevation-1"><i class="fas fa-briefcase"></i></span>

              <div class="info-box-content">
                <span class="info-box-text"><?php echo ($list_wallet == 1) ? '<a href="listWallet.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("404").'">Total Wallet Created <i class="fa fa-arrow-circle-right"></i></a>' : '<a href="#">Total Wallet Created</a>'; ?></span>
                <span class="info-box-number">
                <?php 
                //acctOfficer
                ($create_wallet == "1" && $individual_wallet != "1" && $branch_wallet != "1") ? $check1 = mysqli_query($link, "SELECT * FROM user WHERE created_by = '$institution_id' AND virtual_acctno != ''") or die ("Error:" . mysqli_error($link)) : "";
                ($create_wallet != "1" && $individual_wallet == "1" && $branch_wallet != "1") ? $check1 = mysqli_query($link, "SELECT * FROM user WHERE created_by = '$institution_id' AND acctOfficer = '$iuid' AND virtual_acctno != ''") or die ("Error:" . mysqli_error($link)) : "";
                ($create_wallet!= "1" && $individual_wallet != "1" && $branch_wallet == "1") ? $check1 = mysqli_query($link, "SELECT * FROM user WHERE created_by = '$institution_id' AND branchid = '$isbranchid' AND virtual_acctno != ''") or die ("Error:" . mysqli_error($link)) : "";
                $number_check1 = mysqli_num_rows($check1);
                ($create_wallet == "1" && $individual_wallet != "1" && $branch_wallet != "1") ? $check2 = mysqli_query($link, "SELECT * FROM borrowers WHERE branchid = '$institution_id' AND virtual_acctno != ''") or die ("Error:" . mysqli_error($link)) : "";
                ($create_wallet != "1" && $individual_wallet == "1" && $branch_wallet != "1") ? $check2 = mysqli_query($link, "SELECT * FROM borrowers WHERE branchid = '$institution_id' AND lofficer = '$iuid' AND virtual_acctno != ''") or die ("Error:" . mysqli_error($link)) : "";
                ($create_wallet != "1" && $individual_wallet != "1" && $branch_wallet == "1") ? $check2 = mysqli_query($link, "SELECT * FROM borrowers WHERE branchid = '$institution_id' AND sbranchid = '$isbranchid' AND virtual_acctno != ''") or die ("Error:" . mysqli_error($link)) : "";
                $number_check2 = mysqli_num_rows($check2);
                echo number_format(($number_check1 + $number_check2),0,"",",")."</b>";
                ?>
                </span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <!-- /.col --> 


        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">
              <span class="info-box-icon bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?> elevation-1"><i class="fas fa-minus"></i></span>

              <div class="info-box-content">
                <span class="info-box-text"><?php echo ($list_wallet == 1) ? '<a href="listWallet.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("404").'">Total Wallet Dr. <i class="fa fa-arrow-circle-right"></i></a>' : '<a href="#">Total Wallet Dr.</a>'; ?></span>
                <span class="info-box-number">
                <?php 
                //acctOfficer
                ($create_wallet == "1" && $individual_wallet != "1") ? $checkd1 = mysqli_query($link, "SELECT SUM(debit) FROM wallet_history WHERE userid = '$institution_id'") or die ("Error:" . mysqli_error($link)) : "";  
                ($create_wallet != "1" && $individual_wallet == "1") ? $checkd1 = mysqli_query($link, "SELECT SUM(debit) FROM wallet_history WHERE userid = '$institution_id' AND initiator = '$iuid'") or die ("Error:" . mysqli_error($link)) : "";  
                $number_checkd1 = mysqli_fetch_array($checkd1);
                echo number_format($number_checkd1['SUM(debit)'],2,".",",")."</b>";
                ?>
                </span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <!-- /.col -->


        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">
              <span class="info-box-icon bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?> elevation-1"><i class="fas fa-plus"></i></span>

              <div class="info-box-content">
                <span class="info-box-text"><?php echo ($list_wallet == 1) ? '<a href="listWallet.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("404").'">Total Wallet Cr. <i class="fa fa-arrow-circle-right"></i></a>' : '<a href="#">Total Wallet Cr.</a>'; ?></span>
                <span class="info-box-number">
                <?php 
                //acctOfficer
                ($create_wallet == "1" && $individual_wallet != "1") ? $checkc1 = mysqli_query($link, "SELECT SUM(credit) FROM wallet_history WHERE (userid = '$institution_id' OR recipient = '$institution_id')") or die ("Error:" . mysqli_error($link)) : "";
                ($create_wallet != "1" && $individual_wallet == "1") ? $checkc1 = mysqli_query($link, "SELECT SUM(credit) FROM wallet_history WHERE userid = '$institution_id' AND (recipient = '$iuid' OR initiator = '$iuid')") or die ("Error:" . mysqli_error($link)) : "";
                $number_checkc1 = mysqli_fetch_array($checkc1);
                echo number_format($number_checkc1['SUM(credit)'],2,".",",")."</b>";
                ?>
                </span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <!-- /.col --> 

      
        <!--<div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">
              <span class="info-box-icon bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?> elevation-1"><i class="fas fa-money"></i></span>

              <div class="info-box-content">
                <span class="info-box-text"><?php echo ($list_wallet == 1) ? '<a href="listWallet.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("404").'">Total Wallet Bal. <i class="fa fa-arrow-circle-right"></i></a>' : '<a href="#">Total Wallet Bal.</a>'; ?></span>
                <span class="info-box-number">
                <?php 
                //acctOfficer
                ($create_wallet == "1" && $individual_wallet != "1" && $branch_wallet != "1") ? $checktb1 = mysqli_query($link, "SELECT SUM(transfer_balance) FROM user WHERE created_by = '$institution_id' AND virtual_acctno != ''") or die ("Error:" . mysqli_error($link)) : "";
                ($create_wallet != "1" && $individual_wallet == "1" && $branch_wallet != "1") ? $checktb1 = mysqli_query($link, "SELECT SUM(transfer_balance) FROM user WHERE created_by = '$institution_id' AND acctOfficer = '$iuid' AND virtual_acctno != ''") or die ("Error:" . mysqli_error($link)) : "";
                ($create_wallet!= "1" && $individual_wallet != "1" && $branch_wallet == "1") ? $checktb1 = mysqli_query($link, "SELECT SUM(transfer_balance) FROM user WHERE created_by = '$institution_id' AND branchid = '$isbranchid' AND virtual_acctno != ''") or die ("Error:" . mysqli_error($link)) : "";
                $number_checktb1 = mysqli_fetch_array($checktb1);
                ($create_wallet == "1" && $individual_wallet != "1" && $branch_wallet != "1") ? $checktb2 = mysqli_query($link, "SELECT SUM(wallet_balance) FROM borrowers WHERE branchid = '$institution_id' AND virtual_acctno != ''") or die ("Error:" . mysqli_error($link)) : "";
                ($create_wallet != "1" && $individual_wallet == "1" && $branch_wallet != "1") ? $checktb2 = mysqli_query($link, "SELECT SUM(wallet_balance) FROM borrowers WHERE branchid = '$institution_id' AND lofficer = '$iuid' AND virtual_acctno != ''") or die ("Error:" . mysqli_error($link)) : "";
                ($create_wallet != "1" && $individual_wallet != "1" && $branch_wallet == "1") ? $checktb2 = mysqli_query($link, "SELECT SUM(wallet_balance) FROM borrowers WHERE branchid = '$institution_id' AND sbranchid = '$isbranchid' AND virtual_acctno != ''") or die ("Error:" . mysqli_error($link)) : "";
                $number_checktb2 = mysqli_fetch_array($checktb2);
                echo number_format(($number_checktb1['SUM(transfer_balance)'] + $number_checktb2['SUM(wallet_balance)']),2,".",",")."</b>";
                ?>
                </span>
              </div>
              <!-- /.info-box-content -->
            <!--</div>
            <!-- /.info-box -->
        <!--</div>
        <!-- /.col --> 

<?php
}
else{
  echo '';
}
?>

<?php
}
else{
  echo '';
}
?>


<?php
if($ipos_manager === "On")
{
?>

<?php
$check = mysqli_query($link, "SELECT * FROM my_permission WHERE urole = '$irole'") or die ("Error" . mysqli_error($link));
$get_check = mysqli_fetch_array($check);
$terminal_report = $get_check['terminal_report'];
if($terminal_report == '1')
{
?>
      <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">
              <span class="info-box-icon bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?> elevation-1"><i class="fas fa-map-pin"></i></span>

              <div class="info-box-content">
                <span class="info-box-text"><?php echo ($terminal_report == '1') ? '<a href="terminal_report.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("700").'">Daily POS Trans.<i class="fa fa-arrow-circle-right"></i></a>' : 'Daily POS Trans.'; ?></span>
                <span class="info-box-number">
                <?php 
                $today_record = date("Y-m-d");
                ($irole == 'agent_manager' || $irole == 'institution_super_admin' || $irole == 'merchant_super_admin') ? $select = mysqli_query($link, "SELECT SUM(amount), currencyCode FROM terminal_report WHERE date_time LIKE '$today_record%' AND userid = '$institution_id' AND (status = 'Success' OR status = 'Approved')") or die (mysqli_error($link)) : "";
                ($irole != 'agent_manager' && $irole != 'institution_super_admin' && $irole != 'merchant_super_admin') ? $select = mysqli_query($link, "SELECT SUM(amount), currencyCode FROM terminal_report WHERE date_time LIKE '$today_record%' AND userid = '$institution_id' AND initiatedBy = '$iuid' AND (status = 'Success' OR status = 'Approved')") or die (mysqli_error($link)) : "";
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


        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">
              <span class="info-box-icon bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?> elevation-1"><i class="fas fa-area-chart"></i></span>

              <div class="info-box-content">
                <span class="info-box-text"><?php echo ($terminal_report == '1') ? '<a href="terminal_report.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("700").'">Total POS Trans.<i class="fa fa-arrow-circle-right"></i></a>' : 'Total POS Trans.'; ?></span>
                <span class="info-box-number">
                <?php 
                ($irole == 'agent_manager' || $irole == 'institution_super_admin' || $irole == 'merchant_super_admin') ? $select = mysqli_query($link, "SELECT SUM(amount), currencyCode FROM terminal_report WHERE userid = '$institution_id' AND (status = 'Success' OR status = 'Approved')") or die (mysqli_error($link)) : "";
                ($irole != 'agent_manager' && $irole != 'institution_super_admin' && $irole != 'merchant_super_admin') ? $select = mysqli_query($link, "SELECT SUM(amount), currencyCode FROM terminal_report WHERE userid = '$institution_id' AND initiatedBy = '$iuid' AND (status = 'Success' OR status = 'Approved')") or die (mysqli_error($link)) : "";
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
}
else{
  echo '';
}
?>



<?php
if($iverification_manager === "On")
{
?>

<?php
$check = mysqli_query($link, "SELECT * FROM my_permission WHERE urole = '$irole'") or die ("Error" . mysqli_error($link));
$get_check = mysqli_fetch_array($check);
$all_verification_history = $get_check['all_verification_history'];
$individual_verification_history = $get_check['individual_verification_history'];
$branch_verification_history = $get_check['branch_verification_history'];
if($all_verification_history == '1' || $individual_verification_history == '1' || $branch_verification_history == '1'){
?>

        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">
              <span class="info-box-icon bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?> elevation-1"><i class="fas fa-check"></i></span>

              <div class="info-box-content">
                <span class="info-box-text"><a href="verificationHistory.php?id=<?php echo $_SESSION['tid']; ?>&&mid=<?php echo base64_encode("511"); ?>">Total Verified Identities<i class="fa fa-arrow-circle-right"></i></a></span>
                <span class="info-box-number">
                <?php 
                ($all_verification_history == '1' || $individual_verification_history != '1' || $branch_verification_history != '1') ? $selectVI = mysqli_query($link, "SELECT COUNT(*) FROM verification_history WHERE verificationStatus = 'VERIFIED' AND companyid = '$institution_id'") : "";
                ($all_verification_history != '1' || $individual_verification_history == '1' || $branch_verification_history != '1') ? $selectVI = mysqli_query($link, "SELECT COUNT(*) FROM verification_history WHERE verificationStatus = 'VERIFIED' AND companyid = '$institution_id' AND staffid = '$iuid'") : "";
                ($all_verification_history != '1' || $individual_verification_history != '1' || $branch_verification_history == '1') ? $selectVI = mysqli_query($link, "SELECT COUNT(*) FROM verification_history WHERE verificationStatus = 'VERIFIED' AND companyid = '$institution_id' AND branchid = '$isbranchid'") : "";
                $rowVI = mysqli_fetch_array($selectVI);
                echo number_format($rowVI['COUNT(*)'],0,"",",")."</b>";
                ?>
            </span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <!-- /.col -->


        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">
              <span class="info-box-icon bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?> elevation-1"><i class="fas fa-check"></i></span>

              <div class="info-box-content">
                <span class="info-box-text"><a href="verificationHistory.php?id=<?php echo $_SESSION['tid']; ?>&&mid=<?php echo base64_encode("511"); ?>">Total Unverified Identities<i class="fa fa-arrow-circle-right"></i></a></span>
                <span class="info-box-number">
                <?php
                ($all_verification_history == '1' || $individual_verification_history != '1' || $branch_verification_history != '1') ? $selectVI = mysqli_query($link, "SELECT COUNT(*) FROM verification_history WHERE verificationStatus != 'VERIFIED' AND companyid = '$institution_id'") : "";
                ($all_verification_history != '1' || $individual_verification_history == '1' || $branch_verification_history != '1') ? $selectVI = mysqli_query($link, "SELECT COUNT(*) FROM verification_history WHERE verificationStatus != 'VERIFIED' AND companyid = '$institution_id' AND staffid = '$iuid'") : "";
                ($all_verification_history != '1' || $individual_verification_history != '1' || $branch_verification_history == '1') ? $selectVI = mysqli_query($link, "SELECT COUNT(*) FROM verification_history WHERE verificationStatus != 'VERIFIED' AND companyid = '$institution_id' AND branchid = '$isbranchid'") : "";
                $rowVI = mysqli_fetch_array($selectVI);
                echo number_format($rowVI['COUNT(*)'],0,"",",")."</b>";
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
}
else{
  echo '';
}
?>



<?php
if($ienrolment_manager === "On")
{
?>

<?php
$check = mysqli_query($link, "SELECT * FROM my_permission WHERE urole = '$irole'") or die ("Error" . mysqli_error($link));
$get_check = mysqli_fetch_array($check);
$all_enrollee_list = $get_check['all_enrollee_list'];
$individual_enrollee_list = $get_check['individual_enrollee_list'];
$branch_enrollee_list = $get_check['branch_enrollee_list'];
if($all_enrollee_list == '1' || $individual_enrollee_list == '1' || $branch_enrollee_list == '1'){
?>

        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">
              <span class="info-box-icon bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?> elevation-1"><i class="fas fa-plus"></i></span>

              <div class="info-box-content">
                <span class="info-box-text"><a href="enrolleeList.php?id=<?php echo $_SESSION['tid']; ?>&&mid=<?php echo base64_encode("711"); ?>">Total NIN Registration<i class="fa fa-arrow-circle-right"></i></a></span>
                <span class="info-box-number">
                <?php 
                ($all_enrollee_list == '1' || $individual_enrollee_list != '1' || $branch_enrollee_list != '1') ? $selectEL = mysqli_query($link, "SELECT COUNT(*) FROM enrollees WHERE companyid = '$institution_id'") : "";
                ($all_enrollee_list != '1' || $individual_enrollee_list == '1' || $branch_enrollee_list != '1') ? $selectEL = mysqli_query($link, "SELECT COUNT(*) FROM enrollees WHERE companyid = '$institution_id' AND staffid = '$iuid'") : "";
                ($all_enrollee_list != '1' || $individual_enrollee_list != '1' || $branch_enrollee_list == '1') ? $selectEL = mysqli_query($link, "SELECT COUNT(*) FROM enrollees WHERE companyid = '$institution_id' AND branchid = '$isbranchid'") : "";
                $rowEL = mysqli_fetch_array($selectEL);
                echo number_format($rowVI['COUNT(*)'],0,"",",")."</b>";
                ?>
            </span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <!-- /.col -->


        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">
              <span class="info-box-icon bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?> elevation-1"><i class="fas fa-map"></i></span>

              <div class="info-box-content">
                <span class="info-box-text"><a href="enrolleeList.php?id=<?php echo $_SESSION['tid']; ?>&&mid=<?php echo base64_encode("711"); ?>">Total NIN Pending<i class="fa fa-arrow-circle-right"></i></a></span>
                <span class="info-box-number">
                <?php 
                ($all_enrollee_list == '1' || $individual_enrollee_list != '1' || $branch_enrollee_list != '1') ? $selectELP = mysqli_query($link, "SELECT COUNT(*) FROM enrollees WHERE companyid = '$institution_id' AND ninSlipStatus = 'Pending'") : "";
                ($all_enrollee_list != '1' || $individual_enrollee_list == '1' || $branch_enrollee_list != '1') ? $selectELP = mysqli_query($link, "SELECT COUNT(*) FROM enrollees WHERE companyid = '$institution_id' AND staffid = '$iuid' AND ninSlipStatus = 'Pending'") : "";
                ($all_enrollee_list != '1' || $individual_enrollee_list != '1' || $branch_enrollee_list == '1') ? $selectELP = mysqli_query($link, "SELECT COUNT(*) FROM enrollees WHERE companyid = '$institution_id' AND branchid = '$isbranchid' AND ninSlipStatus = 'Pending'") : "";
                $rowELP = mysqli_fetch_array($selectELP);
                echo number_format($rowELP['COUNT(*)'],0,"",",")."</b>";
                ?>
            </span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <!-- /.col -->


        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">
              <span class="info-box-icon bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?> elevation-1"><i class="fas fa-check"></i></span>

              <div class="info-box-content">
                <span class="info-box-text"><a href="enrolleeList.php?id=<?php echo $_SESSION['tid']; ?>&&mid=<?php echo base64_encode("711"); ?>">Total NIN Completed<i class="fa fa-arrow-circle-right"></i></a></span>
                <span class="info-box-number">
                <?php 
                ($all_enrollee_list == '1' || $individual_enrollee_list != '1' || $branch_enrollee_list != '1') ? $selectELC = mysqli_query($link, "SELECT COUNT(*) FROM enrollees WHERE companyid = '$institution_id' AND ninSlipStatus = 'Completed'") : "";
                ($all_enrollee_list != '1' || $individual_enrollee_list == '1' || $branch_enrollee_list != '1') ? $selectELC = mysqli_query($link, "SELECT COUNT(*) FROM enrollees WHERE companyid = '$institution_id' AND staffid = '$iuid' AND ninSlipStatus = 'Completed'") : "";
                ($all_enrollee_list != '1' || $individual_enrollee_list != '1' || $branch_enrollee_list == '1') ? $selectELC = mysqli_query($link, "SELECT COUNT(*) FROM enrollees WHERE companyid = '$institution_id' AND branchid = '$isbranchid' AND ninSlipStatus = 'Completed'") : "";
                $rowELC = mysqli_fetch_array($selectELC);
                echo number_format($rowELC['COUNT(*)'],0,"",",")."</b>";
                ?>
            </span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <!-- /.col -->


        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">
              <span class="info-box-icon bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?> elevation-1"><i class="fas fa-handshake"></i></span>

              <div class="info-box-content">
                <span class="info-box-text"><a href="enrolleeList.php?id=<?php echo $_SESSION['tid']; ?>&&mid=<?php echo base64_encode("711"); ?>">Total NIN Collected<i class="fa fa-arrow-circle-right"></i></a></span>
                <span class="info-box-number">
                <?php 
                ($all_enrollee_list == '1' || $individual_enrollee_list != '1' || $branch_enrollee_list != '1') ? $selectELCO = mysqli_query($link, "SELECT COUNT(*) FROM enrollees WHERE companyid = '$institution_id' AND ninSlipStatus = 'Collected'") : "";
                ($all_enrollee_list != '1' || $individual_enrollee_list == '1' || $branch_enrollee_list != '1') ? $selectELCO = mysqli_query($link, "SELECT COUNT(*) FROM enrollees WHERE companyid = '$institution_id' AND staffid = '$iuid' AND ninSlipStatus = 'Collected'") : "";
                ($all_enrollee_list != '1' || $individual_enrollee_list != '1' || $branch_enrollee_list == '1') ? $selectELCO = mysqli_query($link, "SELECT COUNT(*) FROM enrollees WHERE companyid = '$institution_id' AND branchid = '$isbranchid' AND ninSlipStatus = 'Collected'") : "";
                $rowELCO = mysqli_fetch_array($selectELCO);
                echo number_format($rowELCO['COUNT(*)'],0,"",",")."</b>";
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
}
else{
  echo '';
}
?>







        <!-- ./col -->  
    
   
      <div class="row">
    </div>
    
    
    <!--  Event codes starts here-->
  
     
          <div class="box box-info">
<?php
$check = mysqli_query($link, "SELECT * FROM my_permission WHERE urole = '$irole'") or die ("Error" . mysqli_error($link));
$get_check = mysqli_fetch_array($check);
$view_all_loans = $get_check['view_all_loans'];
$borrowers_reports = $get_check['borrowers_reports'];
if(($view_all_loans == '1' || $borrowers_reports == '1' || $individual_loan_records == '1' || $branch_loan_records == '1') && $iloan_manager == 'On' && $ireports_module == 'On')
{
?>  

            <div class="box-body">
                
       <div class="col-md-6">
         <div id="chartContainer" style="height: 370px; width: 100%;"></div>
         <script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
         <!--<div id="chartdiv"></div>-->
       </div>
      
      
      <div class="col-md-6">
          <div id="chartContainer2" style="height: 370px; width: 100%;"></div>
        <!--<div id="chartdiv7"></div>-->
      </div>
      
      

<?php
}
else{
  echo '';
}

$protocol = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
$ourCustomUrl = $protocol . $_SERVER['HTTP_HOST'];
?>  
      </div>
      
    <div class="box box-info">
      <div class="box-body">
<!--    
<span style="font-size: 20px;">Your Referral Link is: <a href="https://esusu.app/?rf=<?php echo $institution_id; ?>" target="_blank"><b>https://esusu.app/?rf=<?php echo $institution_id; ?></b></a></span>
    <hr>
-->
    <span style="font-size: 20px;">Your Portal Link is: <a href="<?php echo $ourCustomUrl.'/'.$fetch_icurrency->sender_id; ?>" target="_blank"><b><?php echo $ourCustomUrl.'/'.$fetch_icurrency->sender_id; ?></b></a></span>
</div>
            <div class="box-body">

              
 
        
</div>  
</div>
    
<?php } ?>      
</div>  
