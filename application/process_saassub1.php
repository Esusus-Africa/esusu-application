<?php 
error_reporting(0); 
include "../config/session.php";
?>  

<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="../dist/css/style.css" />
</head>
<body>
<br><br><br><br><br><br><br><br><br>
<div style="width:100%;text-align:center;vertical-align:bottom">
		<div class="loader"></div>
<?php
if(isset($_POST['submit']))
{
	
	function random_password($limit)
	{
	    return substr(base_convert(sha1(uniqid(mt_rand())), 16, 36), 0, $limit);
	}

	$sub_plan = mysqli_real_escape_string($link, $_POST['plan_code']);
	$refid = mysqli_real_escape_string($link, $_POST['refid']);
	$sms_allocated = mysqli_real_escape_string($link, $_POST['sms_allocated']);
	$amount_paid_per_months = $_POST['amount_per_months'];
	$expiration_grace = mysqli_real_escape_string($link, $_POST['expiration_grace']);
	$staff_limit = mysqli_real_escape_string($link, $_POST['staff_limit']);
	$branch_limit = mysqli_real_escape_string($link, $_POST['branch_limit']);
	$customer_limit = mysqli_real_escape_string($link, $_POST['customer_limit']);
	$ccode = mysqli_real_escape_string($link, $_POST['ccode']);
	$date_from = mysqli_real_escape_string($link, $_POST['dfrom']);
	$number_of_months = mysqli_real_escape_string($link, $_POST['dto']);
	//Subscription Date Range
	$expiry_date = date('Y-m-d', strtotime("+$number_of_months months", strtotime($date_from)));

	//Calculate Amount to pay per year/month/day
	$total_amountpaid = ($amount_paid_per_months * $number_of_months);

	if($amount_paid_per_months == "0")
	{
		$sub_token = "Tkn_".random_password(12);
		$insert = mysqli_query($link, "INSERT INTO saas_subscription_trans VALUES(null,'$agentid','$refid','$sub_plan','$sms_allocated','$sub_token','$total_amountpaid','$date_from','$expiry_date','$expiration_grace','$staff_limit','$branch_limit','$customer_limit','Paid','Active',NOW())") or die ("Error: " . mysqli_error($link));
		$insert = mysqli_query($link, "UPDATE agent_data SET arole = 'i_a_demo' WHERE agentid = '$agentid'");
		if($insert){

			echo '<meta http-equiv="refresh" content="5;url=saassub_history.php?id='.$_SESSION['tid'].'&&mid=NDIw">';
			echo '<br>';
			echo'<span class="itext" style="color: blue">Subscription Initiated Successfully...</span>';

		}else{

			echo '<meta http-equiv="refresh" content="5;url=make_saas_sub.php?id='.$_SESSION['tid'].'&&mid=NDIw&&pcode='.$sub_plan.'">';
			echo '<br>';
			echo'<span class="itext" style="color: blue">Error.....Please try again later!</span>';

		}
	}
	elseif($ccode == "")
	{
		$sub_token = "Tkn_".random_password(12);

		$insert = mysqli_query($link, "INSERT INTO saas_subscription_trans VALUES(null,'$agentid','','$sub_plan','$sms_allocated','$sub_token','$total_amountpaid','$date_from','$expiry_date','$expiration_grace','$staff_limit','$branch_limit','$customer_limit','Pending','NotActive',NOW())") or die ("Error: " . mysqli_error($link));
		$insert = mysqli_query($link, "UPDATE agent_data SET arole = 'agent_manager' WHERE agentid = '$agentid'");
		if($insert){

			echo '<meta http-equiv="refresh" content="5;url=finalize_subpay.php?id='.$_SESSION['tid'].'&&token='.$sub_token.'&&mid=NDIw">';
			echo '<br>';
			echo'<span class="itext" style="color: blue">Subscription Initiated Successfully.....Please Wait wait patiently for the System to Redirect you to the last stage!</span>';

		}else{

			echo '<meta http-equiv="refresh" content="5;url=make_saas_sub.php?id='.$_SESSION['tid'].'&&mid=NDIw&&pcode='.$sub_plan.'">';
			echo '<br>';
			echo'<span class="itext" style="color: blue">Error.....Please try again later!</span>';

		}

	}
	else{

		$verify_coupon = mysqli_query($link, "SELECT * FROM coupon_setup WHERE coupon_code = '$ccode'");
		if(mysqli_num_rows($verify_coupon) == 0)
		{
			echo '<meta http-equiv="refresh" content="5;url=finalize_saassub.php?id='.$_SESSION['tid'].'&&mid=NDIw&&pcode='.$sub_plan.'">';
			echo '<br>';
			echo'<span class="itext" style="color: orange;">Invalid Coupon Entered!</span>';
		}
		else{
			$fetch_coupon_details = mysqli_fetch_object($verify_coupon);
			$ctype = $fetch_coupon_details->coupon_type;
			if($coupon_type == "one_off")
			{
				//$now = $date('Y-m-d');
				$dto = $fetch_coupon_details->start_date;
				$enddate = $fetch_coupon_details->end_date;
				$max_r = $fetch_coupon_details->max_redemption;
				$count = $fetch_coupon_details->redemption_count;

				$verify_sub = mysqli_query($link, "SELECT * FROM saas_subscription_trans WHERE coopid_instid = '$agentid' AND sub_token = '$ccode'");

				//NEW METHODS 1
				$date = date("Y-m-d h:m:s", strtotime($dto));
				$now = time(); // or your date as well
				$your_date = strtotime($date);
				$datediff = $your_date - $now;

				//NEW METHODS 2
				$date2 = date("Y-m-d h:m:s", strtotime($enddate));
				$now2 = time(); // or your date as well
				$your_date2 = strtotime($date);
				$datediff2 = $your_date2 - $now2;

				if($datediff >= 1)
				{
					echo '<meta http-equiv="refresh" content="5;url=finalize_saassub.php?id='.$_SESSION['tid'].'&&mid=NDIw&&pcode='.$sub_plan.'">';
					echo '<br>';
					echo'<span class="itext" style="color: orange;">Sorry! It not yet time to use the coupon!!</span>';
				}
				elseif($datediff2 <= 0)
				{
					echo '<meta http-equiv="refresh" content="5;url=finalize_saassub.php?id='.$_SESSION['tid'].'&&mid=NDIw&&pcode='.$sub_plan.'">';
					echo '<br>';
					echo'<span class="itext" style="color: orange;">Sorry! The Coupon you are trying to apply has expired!!</span>';
				}
				elseif(mysqli_num_rows($verify_sub) == 1){
					echo '<meta http-equiv="refresh" content="5;url=finalize_saassub.php?id='.$_SESSION['tid'].'&&mid=NDIw&&pcode='.$sub_plan.'">';
					echo '<br>';
					echo'<span class="itext" style="color: orange;">Sorry! Coupon has already been used!!</span>';
				}
				elseif($max_r == $count) {
					# code...
					echo '<meta http-equiv="refresh" content="5;url=finalize_saassub.php?id='.$_SESSION['tid'].'&&mid=NDIw&&pcode='.$sub_plan.'">';
					echo '<br>';
					echo'<span class="itext" style="color: orange;">Sorry! Coupon has expired</span>';
				}
				else{

					$amt_type = $fetch_coupon_details->amt_type;

					if($amt_type == "percent_off")
					{
						$rate = $fetch_coupon_details->rate;

						$calc_bonus = ($rate/100) * $total_amountpaid;

						$actual_price = $total_amountpaid - $calc_bonus;

						$total_count = $count + 1;

						$update = mysqli_query($link, "UPDATE coupon_setup SET redemption_count = '$total_count' WHERE coupon_code = '$ccode'");

						$insert = mysqli_query($link, "INSERT INTO saas_subscription_trans VALUES(null,'$agentid','','$sub_plan','$sms_allocated','$ccode','$actual_price','$date_from','$expiry_date','$expiration_grace','$staff_limit','$branch_limit','$customer_limit','Pending','NotActive',NOW())") or die ("Error: " . mysqli_error($link));
						$insert = mysqli_query($link, "UPDATE agent_data SET arole = 'agent_manager' WHERE agentid = '$agentid'");
						echo '<meta http-equiv="refresh" content="5;url=finalize_subpay.php?id='.$_SESSION['tid'].'&&token='.$ccode.'&&mid=NDIw">';
						echo '<br>';
						echo'<span class="itext" style="color: blue">Coupon Redeemed Successfully!</span>';
					}
					else{
						$rate = $fetch_coupon_details->rate;

						$actual_price = $total_amountpaid - $rate;

						$total_count = $count + 1;

						$update = mysqli_query($link, "UPDATE coupon_setup SET redemption_count = '$total_count' WHERE coupon_code = '$ccode'");

						$insert = mysqli_query($link, "INSERT INTO saas_subscription_trans VALUES(null,'$agentid','','$sub_plan','$sms_allocated','$ccode','$actual_price','$date_from','$expiry_date','$expiration_grace','$staff_limit','$branch_limit','$customer_limit','Pending','NotActive',NOW())") or die ("Error: " . mysqli_error($link));
						$insert = mysqli_query($link, "UPDATE agent_data SET arole = 'agent_manager' WHERE agentid = '$agentid'");
						echo '<meta http-equiv="refresh" content="5;url=finalize_subpay.php?id='.$_SESSION['tid'].'&&token='.$ccode.'&&mid=NDIw">';
						echo '<br>';
						echo'<span class="itext" style="color: blue">Coupon Redeemed Successfully!</span>';
					}

				}
			}
			else{

				$max_r = $fetch_coupon_details->max_redemption;
				$count = $fetch_coupon_details->redemption_count;

				if($max_r == $count) {
					# code...
					echo '<meta http-equiv="refresh" content="5;url=finalize_saassub.php?id='.$_SESSION['tid'].'&&mid=NDIw&&pcode='.$sub_plan.'">';
					echo '<br>';
					echo'<span class="itext" style="color: orange;">Sorry! Coupon has expired</span>';
				}
				else{

					$amt_type = $fetch_coupon_details->amt_type;

					if($amt_type == "percent_off")
					{
						$rate = $fetch_coupon_details->rate;

						$calc_bonus = ($rate/100) * $total_amountpaid;

						$actual_price = $total_amountpaid - $calc_bonus;

						$total_count = $count + 1;

						$update = mysqli_query($link, "UPDATE coupon_setup SET redemption_count = '$total_count' WHERE coupon_code = '$ccode'");

						$insert = mysqli_query($link, "INSERT INTO saas_subscription_trans VALUES(null,'$agentid','','$sub_plan','$sms_allocated','$ccode','$actual_price','$date_from','$expiry_date','$expiration_grace','$staff_limit','$branch_limit','$customer_limit','Pending','NotActive',NOW())") or die ("Error: " . mysqli_error($link));
						$insert = mysqli_query($link, "UPDATE agent_data SET arole = 'agent_manager' WHERE agentid = '$agentid'");
						echo '<meta http-equiv="refresh" content="5;url=finalize_subpay.php?id='.$_SESSION['tid'].'&&token='.$ccode.'&&mid=NDIw">';
						echo '<br>';
						echo'<span class="itext" style="color: blue">Coupon Redeemed Successfully!</span>';
					}
					else{
						$rate = $fetch_coupon_details->rate;

						$actual_price = $total_amountpaid - $rate;

						$total_count = $count + 1;

						$update = mysqli_query($link, "UPDATE coupon_setup SET redemption_count = '$total_count' WHERE coupon_code = '$ccode'");

						$insert = mysqli_query($link, "INSERT INTO saas_subscription_trans VALUES(null,'$agentid','','$sub_plan','$sms_allocated','$ccode','$actual_price','$date_from','$expiry_date','$expiration_grace','$staff_limit','$branch_limit','$customer_limit','Pending','NotActive',NOW())") or die ("Error: " . mysqli_error($link));
						$insert = mysqli_query($link, "UPDATE agent_data SET arole = 'agent_manager' WHERE agentid = '$agentid'");
						echo '<meta http-equiv="refresh" content="5;url=finalize_subpay.php?id='.$_SESSION['tid'].'&&token='.$ccode.'&&mid=NDIw">';
						echo '<br>';
						echo'<span class="itext" style="color: blue">Coupon Redeemed Successfully!</span>';
					}

				}

			}
		}

	}

}
?>
</div>
</body>
</html>