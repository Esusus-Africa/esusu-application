<div class="row">
		
	       
		    <section class="content">  
	        <div class="box box-success">
            <div class="box-body">
              <div class="table-responsive">
             <div class="box-body">
<form method="post">
	<a href="dashboard.php?id=<?php echo $_SESSION['tid']; ?>&&mid=<?php echo base64_encode("401"); ?>"><button type="button" class="btn bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>"><i class="fa fa-mail-reply-all"></i>&nbsp;Back</button> </a> 
<?php
$date_now = date("Y-m-d");
$select = mysqli_query($link, "SELECT * FROM pay_schedule WHERE status = 'UNPAID' AND schedule <= '$date_now' AND branchid = '$institution_id'") or die ("Error: " . mysqli_error($link));
$num = mysqli_num_rows($select);	
?>
	<?php echo ($view_due_loans == 1) ? '<button type="button" class="btn btn-flat bg-'.(($myaltrow['alternate_color'] == "") ? "orange" : $myaltrow['alternate_color']).'"><i class="fa fa-times"></i>&nbsp;Overdue:&nbsp;'.number_format($num,0,'.',',').'</button>' : ''; ?>
	
    <?php echo ($view_due_loans == 1) ? '<button type="submit" name="reminder" class="btn btn-flat bg-'.(($myaltrow['theme_color'] == "") ? "blue" : $myaltrow['theme_color']).'"><i class="fa fa-bell"></i>&nbsp;Loan Reminder&nbsp;'.'</button>' : ''; ?>
    	
        <hr>		
			  
			 <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th><input type="checkbox" id="select_all"/></th>
                  <th>Loan ID</th>
				  <th>Account ID</th>
				  <th>Customer Name</th>
                  <th>Due Date</th>
                  <th>Amount to Pay</th>
                  <th>Balance</th>
                  <th>Action</th>
                 </tr>
                </thead>
                <tbody> 
<?php
$date_now = date("Y-m-d");
$select = mysqli_query($link, "SELECT * FROM pay_schedule WHERE status = 'UNPAID' AND schedule <= '$date_now' AND branchid = '$institution_id'") or die (mysqli_error($link));
if(mysqli_num_rows($select)==0)
{
echo "<div class='alert bg-".$myaltrow['theme_color'].' == "" ? blue : '.$myaltrow['theme_color']."'>No data found yet!.....Check back later!!</div>";
}
else{
while($row = mysqli_fetch_array($select))
{
$id = $row['id'];
$status = $row['status'];
$lid = $row['lid'];
$account_no = $row['tid'];

    $selectin = mysqli_query($link, "SELECT * FROM borrowers WHERE account = '$account_no'") or die (mysqli_error($link));
    $geth = mysqli_fetch_array($selectin);
    $name = $geth['lname'].' '.$geth['fname'];

$systemset = mysqli_query($link, "SELECT * FROM systemset");
$rowsys = mysqli_fetch_array($systemset);

$search_payment = mysqli_query($link, "SELECT * FROM authorized_card WHERE lid = '$lid'") or die ("Error:" . mysqli_error($link));
$reg_pay_query = mysqli_fetch_object($search_payment);
?>  
                <tr>
        				<td><input id="optionsCheckbox" class="checkbox" name="selector[]" type="checkbox" value="<?php echo $row['id']; ?>"></td>
                        <td><?php echo $row['lid']; ?></td>
        				<td><?php echo $row['tid']; ?></td>
        				<td><?php echo $name; ?></td>
        				<td><b><?php echo $row['schedule']; ?></b></td>
                        <td><?php echo $rowsys['currency'].number_format($row['payment'],2,'.',','); ?></td>
        				<td><?php echo $rowsys['currency'].number_format($row['balance'],2,'.',','); ?></td>
        				<td>
        				    <?php
        				    if($claim_payment == 1 && $reg_pay_query->authorized_code == '')
        				    {
        				        echo "<b>-----</b>";
        				    }
        				    else{
        				    ?>
					       <div class="btn-group">
                              <button type="button" class="btn bg-blue">Request Payment</button>
                              <button type="button" class="btn bg-blue dropdown-toggle" data-toggle="dropdown">
                                <span class="caret"></span>
                                <span class="sr-only">Toggle Dropdown</span>
                              </button>
                              <ul class="dropdown-menu" role="menu">
                                <li><a href="request_pay.php?auth=<?php echo $reg_pay_query->authorized_code; ?>&&id=<?php echo $id; ?>&&perc=100">Collect <b>Payment</b></a></li>
                              </ul>
                            </div>
                            <?php 
                            } 
                            ?>
        				</td>
        			 </tr>
<?php } } ?>
             </tbody>
                </table>  
                
                
                <?php
						if(isset($_POST['reminder'])){
                                                        
                            $search_memset = mysqli_query($link, "SELECT * FROM member_settings WHERE companyid = '$institution_id'");
                            $fetch_memset = mysqli_fetch_array($search_memset);
                            //$sys_abb = $get_sys['abb'];
                            $sysabb = $fetch_memset['sender_id'];
                            $mycurrentTime = date("Y-m-d h:i:s");
                            $date_now = date("Y-m-d");
						    //GET PAYMENT SCHEDULE DETAILS
							$search_ps = mysqli_query($link,"SELECT DISTINCT lid, tid FROM pay_schedule WHERE status = 'UNPAID' AND schedule <= '$date_now' AND branchid = '$institution_id' ORDER BY id DESC");
                            $get_rowno = mysqli_num_rows($search_ps);
                            while($fetch_ps = mysqli_fetch_array($search_ps))
                            {
                                $mylid = $fetch_ps['lid'];
                                $myacct = $fetch_ps['tid'];
                                
                                $search_ps2 = mysqli_query($link, "SELECT SUM(payment), schedule FROM pay_schedule WHERE tid = '$myacct' AND status = 'UNPAID' ORDER BY schedule DESC");
                                $fetch_ps2 = mysqli_fetch_array($search_ps2);
                                
                                $due_amt = $fetch_ps2['SUM(payment)'];
                                $due_date = $fetch_ps2['schedule'];
                                
                                //CALCULATE THE EXPECTED BALANCE AFTER PAYING THE DUE AMOUNT ABOVE
                                $search_lbal = mysqli_query($link, "SELECT * FROM loan_info WHERE baccount = '$myacct' AND status = 'Approved'");
                                $fetch_lbal = mysqli_fetch_array($search_lbal);
                                $current_lbal = $fetch_lbal['balance'];
                                
                                $search_myborrower = mysqli_query($link, "SELECT * FROM borrowers WHERE account = '$myacct'");
                                $fetch_myborrower = mysqli_fetch_array($search_myborrower);
                                $borrower_name = $fetch_myborrower['lname'].' '.$fetch_myborrower['fname'];
                                $phone = $fetch_myborrower['phone'];
                                $email = $fetch_myborrower['email'];
                                $mycurr = $fetch_myborrower['currency'];
                                
                                $sms = "Dear $borrower_name, ";
                                $sms .= "This is a reminder on your Loan Repayment with Loan ID: $mylid of ";
                                $sms .= $mycurr.number_format($due_amt,2,'.',',')." as outstanding with the balance of ";
                                $sms .= $mycurr.number_format($current_lbal,2,'.',',')." on due date of $due_date. Thanks.";
                                
                                $max_per_page = 152;
                            	$sms_length = strlen($sms);
                            	$calc_length = ceil($sms_length / $max_per_page);
                            	
                            	$sms_rate = $fetchsys_config['fax'];
                            	$sms_charges = $calc_length * $sms_rate;
                            	$mywallet_balance = $iwallet_balance - ($sms_charges * $get_rowno);
                            	$refid = "EA-smsCharges-".date("dY").time();
                                
                                //($iwallet_balance >= $sms_charges) ? include("../cron/send_general_sms.php") : "";
                                //include("../cron/send_loanreminder_email.php");
                                $sms_content = ($iwallet_balance >= ($sms_charges * $get_rowno)) ? $sms : "";
                                mysqli_query($link, "INSERT INTO pending_loan_reminder VALUE(null,'$myacct','$mylid','$borrower_name','$due_amt','$current_lbal','$sms_content','$phone','$email','$mycurr','$due_date','$sysabb','pending')");
                                ($iwallet_balance >= ($sms_charges * $get_rowno)) ? mysqli_query($link, "INSERT INTO wallet_history VALUE(null,'$institution_id','$refid','$phone','','$sms_charges','Debit','$mycurr','Charges','SMS Content: $sms','successful','$mycurrentTime','$iuid','$mywallet_balance','')") : "";
                                ($iwallet_balance >= ($sms_charges * $get_rowno)) ? mysqli_query($link, "INSERT INTO sms_logs1 VALUES(null,'$institution_id','institution','$sysabb','$phone','$sms','Sent',NOW())") : "";
                                ($iwallet_balance >= ($sms_charges * $get_rowno)) ? mysqli_query($link, "UPDATE institution_data SET wallet_balance = '$mywallet_balance' WHERE institution_id = '$institution_id'") : "";

								//($email != "" && $iwallet_balance >= $sms_charges ? "<div class='alert alert-success'>Loan Reminder Sent Successfully!</div>" : ($email != "" && $iwallet_balance <= $sms_charges ? "<div class='alert alert-success'>Loan Reminder Sent Successfully but with NO SMS due to insufficient balance in your wallet!</div>" : "<div class='alert bg-orange'>Oops!....Network Error, Please try again later</div>"));
                                echo "<script>alert('Loan Reminder Sent Successfully!'); </script>";
							    echo "<script>window.location='missedpayment.php?id=".$_SESSION['tid']."&&mid=NDA3'; </script>";
                            }
                            exit();
						}
						?>
                		

</form>				

              </div>


	
</div>	
</div>
</div>	
</div>	