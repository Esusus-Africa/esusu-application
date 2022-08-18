   <?php 
	 include("../config/connect.php");
	 //$link = mysqli_connect('localhost','root','root','loan2','8888') or die('Unable to Connect to Database');

	 $id = $_GET['id'];
	 $lid = $_GET['lid'];
	 $tid = $_GET['acn'];

	 $day = mysqli_real_escape_string($link, $_POST['d1']);
	 $schedule_of_paymt = mysqli_real_escape_string($link, $_POST['schedule']);

	 	$update = mysqli_query($link, "UPDATE payment_schedule SET day = '$day', schedule = '$schedule_of_paymt' WHERE lid = '$lid'") or die (mysqli_error($link));
	
	 	$process_data2 = mysqli_query($link, "SELECT * FROM loan_info WHERE lid = '$lid'") or die (mysqli_error($link));
	 	$fetch = mysqli_fetch_array($process_data2);
	 	$interest_rate = $fetch['interest_rate'];
	 	$baccount = $fetch['baccount'];
	 	$amount_borrowed = $fetch['amount'];
	 	$calc_totalinterest = ($interest_rate/100)*$amount_borrowed;
	 	$amt_to_pay = ($calc_totalinterest * $day) + $amount_borrowed;
	
	 	$final_update_loaninfo = mysqli_query($link, "UPDATE loan_info SET amount_topay = '$amt_to_pay', balance = '$amt_to_pay' WHERE lid = '$lid'") or die (mysqli_error($link));
	
	 	if($schedule_of_paymt == "Monthly")
	 	{
	 		$int_rate = number_format(($amt_to_pay / $day),0,'.','');
	 		$verify_data = mysqli_query($link, "SELECT * FROM pay_schedule WHERE lid = '$lid'") or die (mysqli_error($link));
	 		if(mysqli_num_rows($verify_data) == 0)
	 		{
	 			$amt1 = $amt_to_pay - $int_rate;
	 			$insert = mysqli_query($link, "INSERT INTO interest_calculator VALUES(null,'$lid','$amt1','$int_rate')") or die (mysqli_error($link));
			
	 			//CONFIRMATION OF INTEREST RATE
	 			$select_int_calculator = mysqli_query($link, "SELECT * FROM interest_calculator WHERE lid = '$lid'") or die (mysqli_error($link));
	 			$get_calculator = mysqli_fetch_array($select_int_calculator);
	 			$balance = $get_calculator['amt_to_pay'];
	 			$lrate = $get_calculator['int_rate'];
	 			$new_balance = number_format(($balance - $lrate),0,'.','');
			
	 			if($balance <= $lrate)
	 			{
	 				$insert = mysqli_query($link, "INSERT INTO pay_schedule VALUES(null,'$lid','$id','$baccount','','0','$lrate','UNPAID','')") or die (mysqli_error($link));
	 				$update_calculator = mysqli_query($link, "UPDATE interest_calculator SET amt_to_pay = '0' WHERE lid = '$lid'") or die (mysqli_error($link));
				
	 				echo "<script>alert('Row Added Successfully'); </script>";
	 				echo "<script>window.location='updateloans.php?id=".$id."&&acn=".$_GET['acn']."&&mid=".base64_encode("405")."&&lid=".$lid."&&tab=tab_5'; </script>";
				
	 			}
	 			else{
	 			$insert = mysqli_query($link, "INSERT INTO pay_schedule VALUES(null,'$lid','$id','$baccount','','$new_balance','$int_rate','UNPAID','')") or die (mysqli_error($link));
	 			$update_calculator = mysqli_query($link, "UPDATE interest_calculator SET amt_to_pay = '$new_balance' WHERE lid = '$lid'") or die (mysqli_error($link));
			
	 			    echo "<script>alert('Row Added Successfully'); </script>";
	 				echo "<script>window.location='updateloans.php?id=".$id."&&acn=".$_GET['acn']."&&mid=".base64_encode("405")."&&lid=".$lid."&&tab=tab_5'; </script>";
			
	 		}
	 		}else{
	 			$select_int_calculator = mysqli_query($link, "SELECT * FROM interest_calculator WHERE lid = '$lid'") or die (mysqli_error($link));
	 			$get_calculator = mysqli_fetch_array($select_int_calculator);
	 			$balance = $get_calculator['amt_to_pay'];
	 			$lrate = $get_calculator['int_rate'];
	 			$new_balance = number_format(($balance - $lrate),0,'.','');
			
	 			if($balance <= $lrate)
	 			{
	 				$insert = mysqli_query($link, "INSERT INTO pay_schedule VALUES(null,'$lid','$id','$baccount','','0','$balance','UNPAID','')") or die (mysqli_error($link));
	 				$update_calculator = mysqli_query($link, "UPDATE interest_calculator SET amt_to_pay = '0' WHERE lid = '$lid'") or die (mysqli_error($link));
				
	 				echo "<script>alert('Row Added Successfully'); </script>";
	 				echo "<script>window.location='updateloans.php?id=".$id."&&acn=".$_GET['acn']."&&mid=".base64_encode("405")."&&lid=".$lid."&&tab=tab_5'; </script>";
				
	 			}
	 			else{
	 			$insert = mysqli_query($link, "INSERT INTO pay_schedule VALUES(null,'$lid','$id','$baccount','','$new_balance','$int_rate','UNPAID','')") or die (mysqli_error($link));
	 			$update_calculator = mysqli_query($link, "UPDATE interest_calculator SET amt_to_pay = '$new_balance' WHERE lid = '$lid'") or die (mysqli_error($link));
			
	 				echo "<script>alert('Row Added Successfully'); </script>";
	 				echo "<script>window.location='updateloans.php?id=".$id."&&acn=".$_GET['acn']."&&mid=".base64_encode("405")."&&lid=".$lid."&&tab=tab_5'; </script>";
				
	 			}
	 		}
	 	}
	
	 }
	 ?>