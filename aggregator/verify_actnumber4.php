<?php 
if(isset($_POST['my_actno']))
{
    include("../config/connect.php");
    require_once '../config/nipBankTransfer_class.php';

    $recipientAcctNo = $_POST['my_actno'];
    
    $recipientBankCode = $_POST['my_bcode'];

    $query = mysqli_query($link, "SELECT * FROM systemset") or die (mysqli_error($link));
    $r = mysqli_fetch_object($query);
    $rubbiesSecKey = $r->rubbiesSecKey;
      
    $process = $new->rubiesInterBankNameEnquiry($link,$rubbiesSecKey,$recipientBankCode,$recipientAcctNo);
    
  	if($process['responsecode'] == "00"){
  		echo "<b style='font-size:18px;'>".$process['accountname']."</b>";
        echo '<input name="b_name" type="hidden" value="'.$process['accountname'].'">';
  	}
  	else{
  		echo "<b style='font-size:18px;'>Invalid Account Number!</b>";
  	}
}
?>