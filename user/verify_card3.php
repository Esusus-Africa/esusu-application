<?php include("include/header.php"); ?>
<div class="wrapper">

<?php include("include/top_bar.php"); ?>
  <!-- Left side column. contains the logo and sidebar -->
<?php include("include/side_bar.php"); ?>
  <!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    
<?php
$ValidID = $fetch_kycRequirement['ValidID']; //Required or Optional
$UtilityBills = $fetch_kycRequirement['UtilityBills']; //Required or Optional
$mySignature = $fetch_kycRequirement['mySignature']; //Required or Optional
$myVerifiedBvn = $fetch_kycRequirement['bvn']; //Required or Optional
$myBiodata = $fetch_kycRequirement['biodata']; //Required or Optional
$myNok = $fetch_kycRequirement['nok']; //Required or Optional
$myOccupation = $fetch_kycRequirement['occupation']; //Required or Optional

if($bget_kycRequirementNum == 0){

  echo "";

}elseif(($fetch_ValidID == 0 && $ValidID == "Required") || ($fetch_Utility == 0 && $UtilityBills == "Required") || ($fetch_Signature == 0 && $mySignature == "Required") || ($bbvn == "" && $myVerifiedBvn == "Required")){

  echo "<script>alert('Sorry! Your kyc document/bvn needs to be updated...Click ok to do so!!'); </script>";
  echo "<script>window.location='docManager.php?tid=".$_SESSION['tid']."&&acn=".$_SESSION['acctno']."&&mid=".base64_encode("950")."&&tab=tab_2'; </script>";

}elseif($myBiodata == "Required" && ($mymn == "" || $baddrs == "" || $dateofbirth == "" || $bgender == "" || $bstate == "" || $lga == "" || $moi == "" || $mmaidenName == "" || $otherInfo == "")){

  echo "<script>alert('Sorry! Your biodata needs to be updated...Click ok to do so!!'); </script>";
  echo "<script>window.location='docManager.php?tid=".$_SESSION['tid']."&&acn=".$_SESSION['acctno']."&&mid=".base64_encode("950")."&&tab=tab_1'; </script>";

}elseif($myNok == "Required" && ($bnok == "" || $bnok_rela == "" || $nok_addrs == "")){

  echo "<script>alert('Sorry! Your next of kin information needs to be updated...Click ok to do so!!'); </script>";
  echo "<script>window.location='docManager.php?tid=".$_SESSION['tid']."&&acn=".$_SESSION['acctno']."&&mid=".base64_encode("950")."&&tab=tab_3'; </script>";

}elseif($myOccupation == "Required" && ($boccupation == "" || $bemployer == "")){

  echo "<script>alert('Sorry! You needs to update one more details...Click ok to do so!!'); </script>";
  echo "<script>window.location='docManager.php?tid=".$_SESSION['tid']."&&acn=".$_SESSION['acctno']."&&mid=".base64_encode("950")."&&tab=tab_4'; </script>";

}else{

  echo "";

}
?>
    
	<!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Make Payment
        <small>Control panel</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="dashboard.php?tid=<?php echo $_SESSION['tid']; ?>&&acn=<?php echo $_SESSION['acctno']; ?>&&mid=<?php echo base64_encode("401"); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Payment</li>
      </ol>
    </section>
	
    <section class="content">
		<?php include("include/verify_card3_data.php"); ?>
	</section>
</div>

<?php include("include/footer.php"); ?>