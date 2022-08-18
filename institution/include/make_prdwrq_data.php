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
					<option value="" selected>Select Subscription to withdraw from</option>
                    <?php
                    ($individual_customer_records == "1") ? $search = mysqli_query($link, "SELECT * FROM savings_subscription WHERE companyid = '$institution_id' AND agentid = '$iuid' AND status = 'Approved' AND rec_type != 'Savings' AND withdrawal_status != 'Lock'") : "";
                    ($individual_customer_records != "1") ? $search = mysqli_query($link, "SELECT * FROM savings_subscription WHERE companyid = '$institution_id' AND agentid != '' AND status = 'Approved' AND rec_type != 'Savings' AND withdrawal_status != 'Lock'") : "";
                    while($get_search = mysqli_fetch_array($search))
                    {
                        $acn = $get_search['acn'];
                        $oldPcode = $get_search['plan_code'];
                        $search_customer = mysqli_query($link, "SELECT * FROM borrowers WHERE branchid = '$insttitution_id' AND (account = '$acn' OR virtual_acctno = '$acn')");
                        $fetch_customer = mysqli_fetch_array($search_customer);
                        $custname = $fetch_customer['fname'].' '.$fetch_customer['lname'].' '.$fetch_customer['mname'];

                        $searchPlan = mysqli_query($link, "SELECT * FROM savings_plan WHERE plan_code = '$oldPcode'");
                        $fetchPlan = mysqli_fetch_array($searchPlan);
                    ?>
                        <option value="<?php echo $get_search['id']; ?>"><?php echo $acn.' - '.$custname.' ['.$fetchPlan['plan_name'].' - '.$get_search['subscription_code'].']'; ?></option>
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
    $wtoken = "WTkn_".random_password(10);
    $customer = mysqli_real_escape_string($link, $_POST['customer']);
	$stype = mysqli_real_escape_string($link, $_POST['stype']);
	$plan_code = mysqli_real_escape_string($link, $_POST['plan_code']);
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
    $todays_date = date('Y-m-d h:i:s');

    $search_mycustomer = mysqli_query($link, "SELECT * FROM borrowers WHERE account = '$customer' AND branchid = '$insttitution_id'");
    $fetch_mycustomer = mysqli_fetch_array($search_mycustomer);
    //recipient virtual phone no.
    $account_number =  $fetch_mycustomer['virtual_number'];
	
	$search_merch = mysqli_query($link, "SELECT * FROM user WHERE created_by = '$my_merchantid' ORDER BY id ASC");
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
    $em = ($my_vendorid === "" && $my_merchantid != "" ? $merch_em : ($my_vendorid === "" && $my_merchantid === "" ? $row1->email : $vend_em));
    $cname = ($my_vendorid === "" && $my_merchantid != "" ? $merch_cname : ($my_vendorid === "" && $my_merchantid === "" ? $row1->name : $vend_cname));

    if($sum_amount > $validate_sub_bal){

        echo "<script>alert('Oops!....You have Insufficient Balance!!'); </script>";

    }
    elseif($tpin != $myiepin){

        echo "<script>alert('Oops!....Invalid Transaction Pin!!'); </script>";

    }
    elseif($withdrawTime != 0 && $withdrawal_count == $withdrawTime && ($mature_date > $todays_date)){

        echo "<script>alert('Sorry!...You can only withdraw $withdrawTime time(s) before maturity date!!'); </script>";

    }
    elseif($withdrawTime == 0 && ($mature_date > $todays_date)){

        echo "<script>alert('Sorry!...You can not withdraw before maturity date!!'); </script>";

    }
    else{

        $insert_request = mysqli_query($link, "INSERT INTO mcustomer_wrequest VALUES(null,'$wtoken','$my_merchantid','$my_vendorid','$stype','$acctno','$plan_code','$sub_code','$sum_amount','$account_number','$status','$todays_date','$iuid')");
    
    	if($insert_request)
    	{   
    	    include("../cron/send_wrequest_email.php");
    		echo "<script>alert('Withdrawal Request Sent Successfully...\\nYou will be notify once your request has been review'); </script>";
    		echo "<script>window.location='pwithdrawal_req.php?id=".$_SESSION['tid']."&&mid=MTAwMA=='; </script>";
    	}
    	else{
    		echo "<script>alert('Error Occur...Please try again later!'); </script>";
    		echo "<script>window.location='make_prdwrq.php?id=".$_SESSION['tid']."&&mid=MTAwMA=='; </script>";
    	}

    }
}
?>		
			
			 </form> 


</div>	
</div>	
</div>
</div>