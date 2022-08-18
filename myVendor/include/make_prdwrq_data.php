<div class="box">
	       <div class="box-body">
			<div class="panel panel-success">
            <div class="panel-heading bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">
            <h3 class="panel-title"><i class="fa fa-exchange"></i> Withdrawal Request Form</h3>
            </div>
             <div class="box-body">
            
			 <form class="form-horizontal" method="post" enctype="multipart/form-data">

             <div class="box-body">
                
             <div class="form-group">
                <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Request Amount</label>
                <div class="col-sm-6">
                    <input name="requestAmt" type="text" class="form-control" value="<?php echo $vwallet_balance; ?>" placeholder="Your Request Amount" required/>
                </div>
                <label for="" class="col-sm-3 control-label"></label>
            </div>

            <div class="form-group">
                <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Destination Channel</label>
                <div class="col-sm-6">
                <select name="destinationChannel" class="form-control select2" id="destination" required>
                    <option value="" selected>Select Destination Channel</option>
                    <option value="Transfer Balance">Transfer Balance : <?php echo number_format($vtransfer_balance,2,'.',','); ?></option>
                    <option value="Bank Account">Bank Account</option>
				</select>
                </div>
                <label for="" class="col-sm-3 control-label"></label>
            </div>

            <span id='ShowValueFrank'></span>
            <span id='ShowValueFrank'></span>

            <div class="form-group">
                <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Transaction Pin</label>
                <div class="col-sm-6">
                    <input name="tpin" type="password" class="form-control" placeholder="Your Transaction Pin" required/>
                </div>
                <label for="" class="col-sm-3 control-label"></label>
            </div>
                
            <div class="form-group" align="right">
                <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;"></label>
                <div class="col-sm-6">
                    <button name="save" type="submit" class="btn bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>"><i class="fa fa-exchange">&nbsp;Make Withdrawal</i></button>
                </div>
                <label for="" class="col-sm-3 control-label"></label>
            </div>
			 
<?php
if(isset($_POST['save']))
{   
    function random_password($limit)
	{
	    return substr(base_convert(sha1(uniqid(mt_rand())), 16, 36), 0, $limit);
	}
   
    $wtoken = "WTkn_".random_password(10);
    $requestAmt = mysqli_real_escape_string($link, $_POST['requestAmt']);
	$destinationChannel = mysqli_real_escape_string($link, $_POST['destinationChannel']);
	$details = mysqli_real_escape_string($link, $_POST['details']);
    $tpin = mysqli_real_escape_string($link, $_POST['tpin']);
    $todays_date = date('Y-m-d h:i:s');

	$search_user = mysqli_query($link, "SELECT * FROM user WHERE created_by = '$vcreated_by' AND (role = 'agent_manager' OR role = 'institution_super_admin' OR role = 'merchant_super_admin')");
	$fetch_user = mysqli_fetch_array($search_user);
    $merch_em = $fetch_user['email'];
    
    $systemset = mysqli_query($link, "SELECT * FROM systemset");
    $row1 = mysqli_fetch_object($systemset);
    $em = $row1->email.",".$merch_em;
    $vendorName = $vc_name;
    $vendorContact = $vo_phone;
    $req_status = "Pending";

    $calculateBal = $vwallet_balance - $requestAmt;

    if($requestAmt > $vwallet_balance){

        echo "<script>alert('Oops!....You have insufficient fund in your wallet!!'); </script>";

    }
    elseif($tpin != $myvepin){

        echo "<script>alert('Oops!....Invalid Transaction Pin!!'); </script>";

    }
    else{

        $insert_request = mysqli_query($link, "UPDATE vendor_reg SET wallet_balance = '$calculateBal' WHERE companyid = '$vendorid'");
        $insert_request = mysqli_query($link, "INSERT INTO manual_investsettlement VALUES(null,'$wtoken','$vcraeted_by','$vendorid','$vendorName','$vendorContact','$vcurrency','$requestAmt','$destinationChannel','$details','$req_status','$todays_date')");
    
    	if($insert_request)
    	{   
    	    include("../cron/send_withdrawReq_email.php");
    		echo "<script>alert('Withdrawal Request Sent Successfully...\\nYou will be notify once your request has been approved'); </script>";
    		echo "<script>window.location='pwithdrawal_req.php?tid=".$_SESSION['tid']."&&mid=NDkw'; </script>";
    	}
    	else{
    		echo "<script>alert('Error Occur...Please try again later!'); </script>";
    		echo "<script>window.location='make_prdwrq.php?tid=".$_SESSION['tid']."&&mid=NDkw'; </script>";
    	}

    }
}
?>		
			
			 </form> 


</div>	
</div>	
</div>
</div>