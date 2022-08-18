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
                <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Subscription</label>
                <div class="col-sm-6">
                <select name="withdrawfrom"  class="form-control select2" id="withdrawfrom" required>
					<option value="" selected>Select Subscription to withdraw</option>
                    <option disabled>Product Subscription</option>
                    <?php
                    $search = mysqli_query($link, "SELECT * FROM savings_subscription WHERE acn = '$acctno' AND (status = 'Approved' OR status = 'Stop') AND rec_type != 'Savings' AND withdrawal_status != 'Lock'");
                    while($get_search = mysqli_fetch_array($search))
                    {
                    ?>
                    <option value="<?php echo $get_search['id']; ?>"><?php echo $get_search['acn'].' - '.$get_search['categories'].' - Balance: '.number_format($get_search['sub_balance'],2,'.',','); ?></option>
                    <?php 
                    }
                    ?>
                    <option disabled>Savings Subscription</option>
                    <?php
                    $search = mysqli_query($link, "SELECT * FROM savings_subscription WHERE acn = '$acctno' AND (status = 'Approved' OR status = 'Stop') AND rec_type = 'Savings'");
                    while($get_search = mysqli_fetch_array($search))
                    {
                    ?>
                    <option value="<?php echo $get_search['id']; ?>"><?php echo $get_search['acn'].' - '.$get_search['categories'].' - Balance: '.number_format($get_search['sub_balance'],2,'.',','); ?></option>
                    <?php 
                    } 
                    ?>
				</select>
                </div>
                <label for="" class="col-sm-3 control-label"></label>
            </div>

            <span id='ShowValueFrank'></span>
            <span id='ShowValueFrank'></span>
            <span id='ShowValueFrank'></span>

            <input name="bvirtual_phone_no" type="hidden" class="form-control" placeholder="<?php echo $acctno; ?>">

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

    $result = array();
    //recipient virtual phone no.
    $account_number =  mysqli_real_escape_string($link, $_POST['bvirtual_phone_no']);
    
    $wtoken = "WTkn_".random_password(10);
	$stype = mysqli_real_escape_string($link, $_POST['stype']);
    $plan_code = mysqli_real_escape_string($link, $_POST['plan_code']);
    $plan_name = mysqli_real_escape_string($link, $_POST['plan_name']);
	$sub_code = mysqli_real_escape_string($link, $_POST['sub_code']);
    $sum_amount = preg_replace("/[^0-9]/", "", mysqli_real_escape_string($link, $_POST['amount']));
    $validate_sub_bal = mysqli_real_escape_string($link, $_POST['validate_sub_bal']);
    $withdrawTime = mysqli_real_escape_string($link, $_POST['withdrawTime']);
    $withdrawal_count = mysqli_real_escape_string($link, $_POST['withdrawal_count']);
    $mature_date = mysqli_real_escape_string($link, $_POST['mature_date']);
    $tpin = mysqli_real_escape_string($link, $_POST['tpin']);
	$status = 'Pending';
	$my_merchantid = mysqli_real_escape_string($link, $_POST['merchantid']);
    $my_vendorid = mysqli_real_escape_string($link, $_POST['vendorid']);
    $mysource = mysqli_real_escape_string($link, $_POST['mysource']);
    $todays_date = date('Y-m-d h:i:s');

    $date_time = date("Y-m-d h:i:s");
	$correctdate = convertDateTime($date_time);
	
	$search_merch = mysqli_query($link, "SELECT * FROM user WHERE created_by = '$my_merchantid' AND (role = 'agent_manager' OR role = 'institution_super_admin' OR role = 'merchant_super_admin')");
	$fetch_merch = mysqli_fetch_array($search_merch);
	$merch_em = $fetch_merch['email'];
	
	$search_memberset = mysqli_query($link, "SELECT * FROM member_settings WHERE companyid = '$my_merchantid'");
	$fetch_memberset = mysqli_fetch_array($search_memberset);
	$merch_cname = $fetch_memberset['cname'];

	$search_vend = mysqli_query($link, "SELECT * FROM vendor_reg WHERE companyid = '$my_vendorid'");
	$fetch_vend = mysqli_fetch_array($search_vend);
	$vend_em = $fetch_vend['cemail'];
	$vend_cname = $fetch_vend['cname'];
    
    $systemset = mysqli_query($link, "SELECT * FROM systemset");
    $row1 = mysqli_fetch_object($systemset);
    $em = ($my_vendorid === "" && $my_merchantid != "" ? $merch_em.','.$email2 : ($my_vendorid === "" && $my_merchantid === "" ? $row1->email.','.$email2 : $vend_em.','.$merch_em.','.$email2));
    $cname = ($my_vendorid === "" && $my_merchantid != "" ? $merch_cname : ($my_vendorid === "" && $my_merchantid === "" ? $row1->name : $vend_cname));

    if($sum_amount > $validate_sub_bal){

        echo "<script>alert('Oops!....You have Insufficient Balance!!'); </script>";

    }
    elseif($tpin != $myuepin){

        echo "<script>alert('Oops!....Invalid Transaction Pin!!'); </script>";

    }
    elseif($withdrawTime != 0 && $withdrawal_count == $withdrawTime && ($mature_date > $todays_date)){

        echo "<script>alert('Sorry!...You can only withdraw $withdrawTime time(s) before maturity date!!'); </script>";

    }
    elseif($withdrawTime == 0 && ($mature_date > $todays_date)){

        echo "<script>alert('Sorry!...You can not withdraw before maturity date!!'); </script>";

    }
    else{

        $transactionType = "Withdraw";
        $ptype = "Wallet";
        $remark = "Withdrawal from Product Plan ".$plan_code." with Subscription code: ".$sub_code." to Wallet Account: ".$bvirtual_acctno;
        $bal = $validate_sub_bal - $sum_amount;
        $balanceToImpact = $mysource;
        $fullname = $myln.' '.$myfn;

        $sendSMS->withdrawalRequestNotifier($em, $fullname, $cname, $cname, $correctdate, $ptype, $account_number, $bbcurrency, $sum_amount, $bal, $remark, $emailConfigStatus, $fetch_emailConfig);

        ($balanceToImpact == "ledger") ? mysqli_query($link, "INSERT INTO transaction VALUES(null,'$wtoken','$transactionType','$ptype','$account_number','----','$myfn','$myln','$email2','$phone2','$sum_amount','','$remark','$correctdate','$my_merchantid','$bsbranchid','$bbcurrency','','$total','$status','0','1','0')") or die ("Error: " . mysqli_error($link)) : "";
		
		mysqli_query($link, "INSERT INTO ledger_withdrawal_request VALUES(null,'$wtoken','$my_merchantid','$bAcctOfficer','$account_number','$ptype','$sum_amount','$remark','Pending','$balanceToImpact','$bbcurrency','$email2','$phone2','$bsbranchid','0','1','$todays_date')") or die ("Error: " . mysqli_error($link));

    	echo "<script>alert('Withdrawal Request Sent Successfully...'); </script>";
    	echo "<script>window.location='all_wrequest.php?tid=".$_SESSION['tid']."&&acn=".$acctno."&&mid=NDA3'; </script>";

    }
}
?>		
			
			 </form> 


</div>	
</div>	
</div>
</div>