<?php include("include/header.php"); ?>
<div class="wrapper">

<?php include("include/top_bar.php"); ?>
  <!-- Left side column. contains the logo and sidebar -->
<?php include("include/side_bar.php"); ?>
  <!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
<?php
if($igender == "" || $idob == "" || $icity == "" || $istate == ""){
  echo "<script>alert('Sorry! You need to update your KYC to proceed!!'); </script>";
  echo "<script>window.location='profile.php?id=".$_SESSION['tid']."&&mid=NDAx&&tab=tab_1'; </script>";
}
else{
  //empty
}
?>
	<!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <?php
        if(isMobileDevice()){
        ?>
        
        <div align="justify"><p>
        <?php echo ($add_customer === '1' || $isavings_account === "On") ? "<a href='addcustomer.php?id=".$_SESSION['tid']."&&mid=".base64_encode("403")."&&tab=tab_1' class='btn bg-".(($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color'])."'><i class='fa fa-user'></i> Register</a>" : ""; ?>
        <?php echo ($deposit_money === '1' && $isavings_account === "On") ? "<a href='deposit.php?id=".$_SESSION['tid']."&&mid=".base64_encode("410")."' class='btn bg-".(($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color'])."'><i class='fa fa-cloud-upload'></i> Deposit</a>" : ""; ?>
        </p></div>

        <div align="center"><p>
        <?php echo ($withdraw_money === '1' && $isavings_account === "On") ? "<a href='withdraw.php?id=".$_SESSION['tid']."&&mid=".base64_encode("410")."' class='btn bg-".(($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color'])."'><i class='fa fa-download'></i> Withdraw</a>" : ""; ?>
        <?php echo ($add_loan === '1' && $iloan_manager === "On") ? "<a href='newloans.php?id=".$_SESSION['tid']."&&mid=".base64_encode("405")."' class='btn bg-".(($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color'])."'><i class='fa fa-balance-scale'></i> New Loan</a>" : ""; ?>
        </p></div>

        <div align="justify"><p>
        <?php echo ($remit_cash_payment === '1' && $iloan_manager === "On") ? "<a href='newpayments.php?id=".$_SESSION['tid']."&&mid=".base64_encode("408")."' class='btn bg-".(($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color'])."'><i class='fa fa-plus'></i> Repayment</a>" : ""; ?>
        <?php echo (($view_all_loans == 1 || $individual_loan_records == 1 || $branch_loan_records == 1) && $iremitaMerchantId != "" && $iremitaApiKey != "" && $iremitaServiceId != "" && $iremitaApiToken != "" && $iloan_manager === "On") ? "<a href='allmandate.php?id=".$_SESSION['tid']."&&mid=".base64_encode("408")."' class='btn bg-".(($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color'])."'>Mandate Activated <span class='badge badge-orange right' id='mandate_status'>: ".number_format($iactivatedDD,0,'',',')." :</span></a>" : ""; ?>
        </p></div>

        <div align="justify"><p>
        <?php echo ($fund_card === '1' && $icard_issuance_manager === "On") ? "<a href='create_card.php?id=".$_SESSION['tid']."&&mid=".base64_encode("550")."&&tab=tab_1' class='btn bg-".(($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color'])."'><i class='fa fa-credit-card'></i> Fund Card</a>" : ""; ?>
        <?php echo ($list_all_product === '1' && $iproduct_manager === "On") ? "<a href='allproduct.php?id=".$_SESSION['tid']."&&mid=".base64_encode("1000")."' class='btn bg-".(($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color'])."'><i class='fa fa-gift'></i> Sell Product</a>" : ""; ?>
        </p></div>

        <div align="justify"><p>
        <?php echo ($transfer_fund === '1' && $iwallet_manager === "On") ? "<a href='transfer_fund.php?id=".$_SESSION['tid']."&&mid=NDA0&&tab=tab_1' class='btn bg-".(($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color'])."'><i class='fa fa-forward'></i> Transfer Money</a>" : ""; ?>
        <?php echo ($esusuPAY_cardless_withdrawal === '1' && $ihalalpay_module === "Off" && $ipos_manager === "On") ? "<a href='ussd_cardless.php?id=".$_SESSION['tid']."&&mid=".base64_encode("700")."' class='btn bg-".(($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color'])."'><i class='fa fa-wifi'></i> esusuPAY</a>" : ""; ?>
        <?php echo ($esusuPAY_cardless_withdrawal === '1' && $ihalalpay_module === "On" && $ipos_manager === "On") ? "<a href='ussd_cardless.php?id=".$_SESSION['tid']."&&mid=".base64_encode("700")."' class='btn bg-".(($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color'])."'><i class='fa fa-sun-o'></i> HalalPAY</a>" : ""; ?>
        </p></div>

        <div align="justify"><p>
        <?php echo (($create_wallet == 1 || $create_individual_wallet_only == 1 || $create_agent_wallet_only == 1 || $create_corporate_wallet_only == 1) && $iwallet_creation == "On") ? "<a href='createWallet.php?id=".$_SESSION['tid']."&&mid=".base64_encode("404")."&&tab=tab_1' class='btn bg-".(($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color'])."'><i class='fa fa-google-wallet'></i> Create Wallet</a>" : ""; ?>
        </p></div>

        <?php
        }
        else{
        ?>

        <?php echo ($add_customer === '1' || $isavings_account === "On") ? "<a href='addcustomer.php?id=".$_SESSION['tid']."&&mid=".base64_encode("403")."&&tab=tab_1' class='btn bg-".(($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color'])."'><i class='fa fa-user'></i> Register</a>" : ""; ?>
        <?php echo ($deposit_money === '1' && $isavings_account === "On") ? "<a href='deposit.php?id=".$_SESSION['tid']."&&mid=".base64_encode("410")."' class='btn bg-".(($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color'])."'><i class='fa fa-cloud-upload'></i> Deposit</a>" : ""; ?>
        <?php echo ($withdraw_money === '1' && $isavings_account === "On") ? "<a href='withdraw.php?id=".$_SESSION['tid']."&&mid=".base64_encode("410")."' class='btn bg-".(($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color'])."'><i class='fa fa-download'></i> Withdraw</a>" : ""; ?>
        <?php echo ($add_loan === '1' && $iloan_manager === "On") ? "<a href='newloans.php?id=".$_SESSION['tid']."&&mid=".base64_encode("405")."' class='btn bg-".(($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color'])."'><i class='fa fa-balance-scale'></i> New Loan</a>" : ""; ?>
        <?php echo ($remit_cash_payment === '1' && $iloan_manager === "On") ? "<a href='newpayments.php?id=".$_SESSION['tid']."&&mid=".base64_encode("408")."' class='btn bg-".(($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color'])."'><i class='fa fa-plus'></i> Repayment</a>" : ""; ?>
        <?php echo (($view_all_loans == 1 || $individual_loan_records == 1 || $branch_loan_records == 1) && $iremitaMerchantId != "" && $iremitaApiKey != "" && $iremitaServiceId != "" && $iremitaApiToken != "" && $iloan_manager === "On") ? "<a href='allmandate.php?id=".$_SESSION['tid']."&&mid=".base64_encode("408")."' class='btn bg-".(($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color'])."'>Mandate Activated <span class='badge badge-orange right' id='mandate_status'>: ".number_format($iactivatedDD,0,'',',')." :</span></a>" : ""; ?>
        <?php echo ($fund_card === '1' && $icard_issuance_manager === "On") ? "<a href='create_card.php?id=".$_SESSION['tid']."&&mid=".base64_encode("550")."&&tab=tab_1' class='btn bg-".(($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color'])."'><i class='fa fa-credit-card'></i> Fund Card</a>" : ""; ?>
        <?php echo ($list_all_product === '1' && $iproduct_manager === "On") ? "<a href='allproduct.php?id=".$_SESSION['tid']."&&mid=".base64_encode("1000")."' class='btn bg-".(($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color'])."'><i class='fa fa-gift'></i> Sell Product</a>" : ""; ?>
        <?php echo ($transfer_fund === '1' && $iwallet_manager === "On") ? "<a href='transfer_fund.php?id=".$_SESSION['tid']."&&mid=NDA0&&tab=tab_1' class='btn bg-".(($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color'])."'><i class='fa fa-forward'></i> Transfer Money</a>" : ""; ?>
        <?php echo ($esusuPAY_cardless_withdrawal === '1' && $ihalalpay_module === "Off" && $ipos_manager === "On") ? "<a href='ussd_cardless.php?id=".$_SESSION['tid']."&&mid=".base64_encode("700")."' class='btn bg-".(($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color'])."'><i class='fa fa-wifi'></i> esusuPAY</a>" : ""; ?>
        <?php echo ($esusuPAY_cardless_withdrawal === '1' && $ihalalpay_module === "On" && $ipos_manager === "On") ? "<a href='ussd_cardless.php?id=".$_SESSION['tid']."&&mid=".base64_encode("700")."' class='btn bg-".(($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color'])."'><i class='fa fa-sun-o'></i> HalalPAY</a>" : ""; ?>
        <?php //echo (($create_wallet == 1 || $create_individual_wallet_only == 1 || $create_agent_wallet_only == 1 || $create_corporate_wallet_only == 1) && $iwallet_creation == "On") ? "<a href='createWallet.php?id=".$_SESSION['tid']."&&mid=".base64_encode("404")."' class='btn bg-".(($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color'])."'><i class='fa fa-google-wallet'></i> Create Wallet</a>" : ""; ?>
      
        <?php
        }
        ?>
      </h1>
      <ol class="breadcrumb">
        
      </ol>


    </section>

    <!-- Main content -->

    <section class="content">
      <!-- Small boxes (Stat box) -->
		<?php include("include/dashboard_chart.php"); ?>   
	</section>
</div>
		
<?php include("include/footer.php"); ?>