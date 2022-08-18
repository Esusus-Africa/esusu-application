<?php include("include/header.php"); ?>

<?php
$scode = $_GET['scode'];
$searchSub = mysqli_query($link, "SELECT * FROM savings_subscription WHERE subscription_code = '$scode'");
$fetchSub = mysqli_fetch_array($searchSub);
$agentid = $fetchSub['agentid'];
$acn = $fetchSub['acn'];
$plancode = $fetchSub['plan_code'];
$plancat = $fetchSub['categories'];
$planamount = $fetchSub['currency'].number_format($fetchSub['amount'],2,'.',',');
$mdate = $fetchSub['mature_date'];
$created_by = $fetchSub['merchant_id'];
$vendorid = $fetchSub['vendorid'];

$searchPlan = mysqli_query($link, "SELECT * FROM savings_plan WHERE plan_code = '$plancode'");
$fetchPlan = mysqli_fetch_array($searchPlan);
$planName = $fetchPlan['plan_name'];

$select1 = mysqli_query($link, "SELECT * FROM systemset") or die (mysqli_error($link));
$r = mysqli_fetch_object($select1);

$search_cust = mysqli_query($link, "SELECT * FROM borrowers WHERE account = '$acn'");
$fetch_cust = mysqli_fetch_array($search_cust);
$custName = $fetch_cust['fname'].' '.$fetch_cust['lname'].' '.$fetch_cust['mname'];
$custVAActNo = $fetch_cust['virtual_acctno'];
$phone = $fetch_cust['phone'];
$email1 = $fetch_cust['email'];

$search_agent = mysqli_query($link, "SELECT * FROM user WHERE id = '$agentid'");
$fetch_agent = mysqli_fetch_array($search_agent);
$phone = $fetch_agent['phone'];
$email2 = $fetch_agent['email'];

$emailReceiver = $email2.",".$email1;
?>


    <div class="modal-dialog modal-lg">
          <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
            <legend style="color: blue;"><b>Upload Policy Document / Certificate <i class='fa fa-info'></i></span></b></legend>
        </div>
        <div class="modal-body">

        <form class="form-horizontal" method="post" enctype="multipart/form-data">

<?php
if(isset($_POST['upgrade'])){

    $docType = mysqli_real_escape_string($link, $_POST['docType']);
    $date_time = date("Y-m-d h:i:s");

    foreach ($_FILES['uploaded_file']['name'] as $key => $name){
        
        $newFilename = $name;
        
        if($newFilename == "")
        {
            echo "";
        }
        else{
            $newlocation = $newFilename;
            if(move_uploaded_file($_FILES['uploaded_file']['tmp_name'][$key], '../img/'.$newFilename))
            {
                mysqli_query($link, "INSERT INTO policy_doc VALUES(null,'$created_by','$vendorid','$agentid','$acn','$scode','$docType','$newlocation','$date_time')");
            }
        }
        
    }
    mysqli_query($link, "UPDATE savings_subscription SET upload_doc = 'Yes' WHERE subscription_code = '$scode'");

    $sendSMS->vendorCertEmailNotifier($emailReceiver, $docType, $custName, $scode, $planName, $plancat, $planamount, $custVAActNo, $phone, $mdate, $iemailConfigStatus, $ifetch_emailConfig);

    echo "<div class='alert bg-blue'>Document Uploaded Successfully</div>";
    echo '<meta http-equiv="refresh" content="3;url=uploadCert.php?id='.$_SESSION['tid'].'&&scode='.$scode.'&&mid=MTAwMA==">';

}
?>

            <div class="box-body">

            <div class="form-group">
            <label for="" class="col-sm-4 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Upload Documents:</label>
            <div class="col-sm-7">
                <input name="uploaded_file[]" type="file" class="btn bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>" multiple/>
                <hr>
                    <?php
                    $acct_owner = $_GET['uid'];
                    $i = 0;
                    $search_file = mysqli_query($link, "SELECT * FROM policy_doc WHERE borrowerid = '$acn' AND scode = '$scode'") or die ("Error: " . mysqli_error($link));
                    if(mysqli_num_rows($search_file) == 0){
                    	echo "<span style='color: ".(($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color'])."'>No file attached!!</span>";
                    }else{
                    	while($get_file = mysqli_fetch_array($search_file)){
                    		$i++;
                    ?>
                    <a href="<?php echo $fetchsys_config['file_baseurl'].$get_file['attached_file']; ?>" target="_blank"><img src="<?php echo $fetchsys_config['file_baseurl']; ?>file_attached.png" width="64" height="64"> Document<?php echo $i; ?></a>
                    <?php
                    	}
                    }
                    ?>
                <hr>
            </div>
            <label for="" class="col-sm-1 control-label"></label>
            </div>

            <div class="form-group">
                <label for="" class="col-sm-4 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Document Type</label>
                <div class="col-sm-7">
                    <select name="docType" class="form-control select2" style="width: 100%;" /required>
                        <option value="" selected="selected">---Choose Document Type---</option>
                        <option value="Policy Dcoument">Policy Dcoument</option>
                        <option value="Certificate">Certificate</option>
                    </select>
                </div>
                <label for="" class="col-sm-1 control-label"></label>
            </div>
				  
			 </div>
			 
			<div class="form-group" align="right">
                <label for="" class="col-sm-4 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;"></label>
                <div class="col-sm-7">
                	<button name="upload" type="submit" class="btn bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>"><i class="fa fa-link">&nbsp;Submit</i></button>
                </div>
                <label for="" class="col-sm-1 control-label"></label>
            </div>

			 </form> 

        </div>
        <div style="font-size:10px;"><?php include("include/footer.php"); ?></div>
      </div>   
      
    </div>


