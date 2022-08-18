<div class="row">       
		    <section class="content">  
	        <div class="box box-success">
            <div class="box-body">
              <div class="table-responsive">
             <div class="box-body">
<form method="post">
<a href="dashboard.php?id=<?php echo $_SESSION['tid']; ?>&&mid=<?php echo base64_encode("401"); ?>"><button type="button" class="btn btn-flat bg-orange"><i class="fa fa-mail-reply-all"></i>&nbsp;Back</button> </a> 

<?php echo ($delete_loans == '1') ? '<button type="submit" class="btn btn-flat bg-orange" name="delete"><i class="fa fa-times"></i>&nbsp;Multiple Delete</button>' : ''; ?>

<a href="apprloans.php?id=<?php echo $_SESSION['tid']; ?>&&mid=<?php echo base64_encode("405"); ?>&&tab=tab_1"><button type="button" class="btn btn-flat bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>"><i class="fa fa-refresh"></i>&nbsp;Refresh</button></a>

	<hr>
</form>		




<div class="col-md-14">
            <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
             <li <?php echo ($_GET['tab'] == 'tab_1') ? "class='active'" : ''; ?>><a href="apprloans.php?id=<?php echo $_SESSION['tid']; ?>&&mid=NDA1&&tab=tab_1">All Loan Application</a></li>

             <li <?php echo ($_GET['tab'] == 'tab_2') ? "class='active'" : ''; ?>><a href="#apprloans.php?id=<?php echo $_SESSION['tid']; ?>&&lide=<?php echo $_GET['lide']; ?>&&mid=NDA1&&tab=tab_2">Action</a></li>

            </ul>
             <div class="tab-content">

<?php
if(isset($_GET['tab']) == true)
{
  $tab = $_GET['tab'];
  if($tab == 'tab_1')
  {
 
  ?>
             <div class="tab-pane <?php echo ($_GET['tab'] == 'tab_1') ? 'active' : ''; ?>" id="tab_1">

             <div class="box-body">
           
			 <form class="form-horizontal" method="post" enctype="multipart/form-data">
               
               <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th><input type="checkbox" id="select_all"/></th>
                  <th>Loan ID</th>
                  <th>RRR Number</th>
				  <th>Account ID</th>
				  <th>Contact Number</th>
                  <th>Principal Amount</th>
                  <th>Principal Amount + Interest</th>
                  <th>Booked By</th>
                  <th>Last Reviewed By</th>
                  <th>Date Release</th>
                  <th>Approval Status</th>
				  <th>Update Status</th>
                  <th>Action</th>
                 </tr>
                </thead>
                <tbody> 
<?php
/*$search_loanp = mysqli_query($link, "SELECT * FROM loan_product WHERE (merchantid = '$institution_id') OR (merchantid != '$institution_id' AND visibility = 'Yes' AND authorize = '1')");
while($fetch_loanp = mysqli_fetch_array($search_loanp))
{
	$lp_id = $fetch_loanp['id'];*/
	
	($view_all_loans === "1" && $individual_loan_records === "" && $branch_loan_records === "") ? $select = mysqli_query($link, "SELECT * FROM loan_info WHERE branchid = '$institution_id' AND status = 'Approved' ORDER BY id DESC") or die (mysqli_error($link)) : "";
	($view_all_loans === "" && $individual_loan_records === "1" && $branch_loan_records === "") ? $select = mysqli_query($link, "SELECT * FROM loan_info WHERE branchid = '$institution_id' AND (agent = '$iname' OR agent = '$iuid') AND status = 'Approved' ORDER BY id DESC") or die (mysqli_error($link)) : "";
	($view_all_loans === "" && $individual_loan_records === "" && $branch_loan_records === "1") ? $select = mysqli_query($link, "SELECT * FROM loan_info WHERE branchid = '$institution_id' AND sbranchid = '$isbranchid' AND status = 'Approved' ORDER BY id DESC") or die (mysqli_error($link)) : "";
	while($row = mysqli_fetch_array($select))
	{
	$id = $row['id'];
	$lid = $row['lid'];
	$borrower = $row['borrower'];
	$acn = $row['baccount'];
	$status = $row['status'];
	$upstatus = $row['upstatus'];
	$selectin = mysqli_query($link, "SELECT * FROM borrowers WHERE id = '$borrower'") or die (mysqli_error($link));
	$geth = mysqli_fetch_array($selectin);
	$name = $geth['lname'].' '.$geth['fname'];
	$myphone = $geth['phone'];
	$acct_officer = $row['agent'];
	
	$search_user = mysqli_query($link, "SELECT * FROM user WHERE id = '$acct_officer' OR name = '$acct_officer'");
	$fetch_user = mysqli_fetch_array($search_user);
?> 

				<tr>
				<td><input id="optionsCheckbox" class="checkbox" name="selector[]" type="checkbox" value="<?php echo $row['id']; ?>"></td>
                <td><b><?php echo $row['lid']; ?></b></td>
                <td><b><?php echo ($row['mandate_status'] === "Pending") ? '----' : $row['mandate_id']; ?></b></td>
				<td align="center"><?php echo $name.'<br>('.$row['baccount'].')'; ?></td>
				<td><?php echo $myphone; ?></td>
				<td><b><?php echo $icurrency.number_format($row['amount'],2,'.',','); ?></b></td>
				<td><b><?php echo $icurrency.number_format($row['amount_topay'],2,'.',','); ?></b></td>
				<td><?php echo ($row['agent'] == "") ? 'Customer' : $fetch_user['name']; ?></td>
			    <td><?php echo ($row['teller'] == "") ? '<span class="label bg-orange">Waiting for Review</span>' : $row['teller']; ?></td>
				<td><?php echo $row['date_release']; ?></td>
                <td>
				<span class="label bg-<?php echo ($status =='Approved' ? 'blue' : ($status =='Disapproved' ? 'red' : ($status =='Disbursed' ? 'green' : 'orange'))); ?>"><?php echo $status; ?></span>
				</td>
				<td align="center" class="alert bg-<?php echo ($upstatus =='Completed') ? 'blue' : 'orange'; ?>"><?php echo $upstatus; ?><br><?php echo ($update_loan_records == '1' && $upstatus != "Completed") ? '<a href="updateloans.php?id='.$id.'&&mid='.base64_encode("405").'&&acn='.$acn.'&&lid='.$row['lid'].'&&tab=tab_0">Click here to complete Registration!</a>' : ''; ?></td>
				<td>
			    <?php echo ($update_loan_records == '1') ? "<a href='updateloans.php?id=".$id."&&acn=".$row['baccount']."&&mid=".base64_encode("405")."&&lid=".$row['lid']."&&tab=tab_0'><button type='button' class='btn bg-".(($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color'])."'><i class='fa fa-eye'></i></button></a>" : "--"; ?><br>
			    <?php echo ($approve_disapprove_loans == '1') ? "<a href='apprloans.php?id=".$_SESSION['tid']."&&lide=".$id."&&mid=NDA1&&tab=tab_2'> <button type='button' class='btn bg-".(($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color'])."'><i class='fa fa-edit'></i></button></a>" : "--"; ?>
<?php
$se = mysqli_query($link, "SELECT * FROM attachment WHERE get_id = '$id'") or die (mysqli_error($link));
while($gete = mysqli_fetch_array($se))
{
?>
				<?php echo ($update_loan_records == '1') ? '<a href="'.$gete['attached_file'].'" target="_blank"><i class="fa fa-download"></i></a>&nbsp;&nbsp;' : '--'; ?>  
<?php } ?>
				</td>	    
			    </tr>
<?php } //} ?>
             </tbody>
                </table>  
<?php
						if(isset($_POST['delete'])){
						    $idm = $_GET['id'];
							$id=$_POST['selector'];
							$N = count($id);
    						if($id == ''){
        						echo "<script>alert('Row Not Selected!!!'); </script>";	
        						echo "<script>window.location='apprloans.php?id=".$_SESSION['tid']."&&mid=NDA1&&tab=tab_1'; </script>";
        					}
    						else{
    							for($i=0; $i < $N; $i++)
    							{
    							    $search_loan_by_id = mysqli_query($link, "SELECT * FROM loan_info WHERE id = '$id'");
        							$getloan_lid = mysqli_fetch_object($search_loan_by_id);
        						    $get_lid = $getloan_lid->lid;
        						    
    								$result = mysqli_query($link,"DELETE FROM loan_info WHERE id ='$id[$i]'");
    								$result = mysqli_query($link,"DELETE FROM pay_schedule WHERE get_id ='$id[$i]'");
    								$result = mysqli_query($link,"DELETE FROM payment_schedule WHERE lid ='$get_lid'");
    								$result = mysqli_query($link,"DELETE FROM interest_calculator WHERE lid ='$get_lid'");
    								$result = mysqli_query($link,"DELETE FROM attachment WHERE get_id ='$id[$i]'");
    								$result = mysqli_query($link,"DELETE FROM battachment WHERE get_id ='$id[$i]'");
    								//$result = mysqli_query($link,"DELETE FROM additional_fees WHERE get_id ='$id[$i]'");
    								//$result = mysqli_query($link,"DELETE FROM collateral WHERE idm ='$id[$i]'");
    
    								echo "<script>alert('Row Delete Successfully!!!'); </script>";
    								echo "<script>window.location='apprloans.php?id=".$_SESSION['tid']."&&mid=NDA1&&tab=tab_1'; </script>";
    							}
							}
							}
?>
             
       </form>
       
             </div>
             
             </div>
             <!-- /.tab-pane -->             
<?php
  }
  elseif($tab == 'tab_2')
  {
  ?>
            <div class="tab-pane <?php echo ($_GET['tab'] == 'tab_2') ? 'active' : ''; ?>" id="tab_2">

        <div class="box-body">
           
<?php
$id = $_GET['lide'];
$select_realloan = mysqli_query($link, "SELECT * FROM loan_info WHERE id = '$id'") or die (mysqli_error($link));
$fetch_realloan = mysqli_fetch_array($select_realloan);
$mylid = $fetch_realloan['lid'];
$dept_id = $fetch_realloan['dept'];

$search_mydept = mysqli_query($link, "SELECT * FROM dept WHERE id = '$dept_id'");
$fetch_mydept = mysqli_fetch_array($search_mydept);
$dept_name = $fetch_mydept['dpt_name'];
?>
		  
		  <form class="form-horizontal" method="post"  enctype="multipart/form-data">

								<input type="hidden" value="<?php echo $id; ?>"  name="userid">
								<input type="hidden" value="<?php echo $mylid; ?>"  name="lid">
                  
                  <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Status</label>
                  <div class="col-sm-10">
                  <select name="Status" class="form-control select2" style="width: 100%;" required>
											<option value="" selected> Select Status</option>
											<?php echo ($internal_review == 1 && ($idept_settings == "Off" || $idept_settings === "") ? "" : ($internal_review == 1 && $idept_settings == "On" ? '<option value="Internal-Review">Internal-Review</option>' : "")); ?>
											<?php echo ($approved_loan == 1) ? '<option value="Approved">Approve</option>' : ""; ?>
											<?php echo ($disbursed_loan == 1 && ($idept_settings == "Off" || $idept_settings === "") ? "" : ($disbursed_loan == 1 && $idept_settings == "On" ? '<option value="Disbursed">Disburse</option>' : "")); ?>
											<?php echo ($disapproved_loan == 1) ? '<option value="Disapproved">Disapprove</option>' : ""; ?>
										 </select>
                  </div>
                  </div>
									
                                    <?php
                                    if($idept_settings == "Off" || $idept_settings === "")
                                    {
                                        echo "";
                                    }else{
                                    ?>
                    <div class="form-group">
                <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Forward to: </label>
				 <div class="col-sm-10">
				  <select name="forward_to" class="form-control select2" style="width: 100%;" required>
											<option value="<?php echo ($dept_id == '') ? '' : $dept_id; ?>" selected><?php echo ($dept_id == '') ? 'Select Department to forward for further review' : $dept_name; ?></option>
											<?php
											$searchDept = mysqli_query($link, "SELECT * FROM dept WHERE companyid = '$institution_id'");
											while($fetchDept = mysqli_fetch_array($searchDept))
											{
											?>
											    <option value="<?php echo $fetchDept['id']; ?>"><?php echo $fetchDept['dpt_name']; ?></option>
											<?php 
											} 
											?>
										 </select>

                                    </div>
                                    </div>
                                    
                                    <div class="form-group">
						            <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">Note</label>
						            <div class="col-sm-10">
						   		<textarea name="lnote" class="form-control" rows="2" cols="80" Placeholder="Enter Note" required></textarea>
						              			 </div>
						             </div>
                                    
                                    <?php 
                                    } 
                                    ?>
						             
								<div class="modal-footer">
								<button type="submit" name="update_status" class="btn bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>"><i class="icon-forward"></i>&nbsp;Process</button>
								<button class="btn bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>" data-dismiss="modal" aria-hidden="true"><i class="icon-remove icon-large"></i> Close</button>
								</div>
								</div>

    								<?php
    									if (isset($_POST['update_status'])) {
    					
    									$Status_save = $_POST['Status'];
    									$UserID = $_POST['userid'];
    									$LID = $_POST['lid'];
    									$mytid = $_SESSION['tid'];
    									$forward_to = $_POST['forward_to'];
										$lnote = $_POST['lnote'];
										$mycurrentTime = date("Y-m-d h:i:s");
									
									    if($Status_save == "Internal-Review"){
									        $treat_loan = mysqli_query($link, "SELECT * FROM loan_info WHERE id = '$UserID'") or die ("Error: " . mysqli_error($link));
											$get_treatedloan = mysqli_fetch_array($treat_loan);
											$LID = $get_treatedloan['lid'];
											$uaccount = $get_treatedloan['baccount'];
											$lproduct = $get_treatedloan['lproduct'];
											$review_date = date("Y-m-d G:i A");
											$amount = $get_treatedloan['amount'];
											
											$search_ploan = mysqli_query($link, "SELECT * FROM loan_product WHERE id = '$lproduct'");
											$fetch_ploan = mysqli_fetch_array($search_ploan);
											$pname = $fetch_ploan['pname'];
											
											$search_lboro = mysqli_query($link, "SELECT * FROM borrowers WHERE account = '$uaccount'");
											$fetch_lboro = mysqli_fetch_array($search_lboro);
											$bfull_name = $fetch_lboro['lname'].' '.$fetch_lboro['fname'];
											
											$look_user = mysqli_query($link, "SELECT * FROM user WHERE id = '$mytid'");
											$get_user = mysqli_fetch_array($look_user);
											$uname = $get_user['name'];
											
											$sql = mysqli_query($link, "SELECT * FROM sms WHERE status = 'Activated' AND smsuser = ''") or die (mysqli_error($link));
                                            $find = mysqli_fetch_array($sql);
                                            $gateway_uname = $find['username'];
                                            $gateway_pass = $find['password'];
                                            $gateway_api = $find['api'];

											$search_dept_details = mysqli_query($link, "SELECT * FROM dept WHERE id = '$forward_to'");
											$fetch_dept_details = mysqli_fetch_array($search_dept_details);
											$hod_email = $fetch_dept_details['hod_email'];
											$phone = $fetch_dept_details['hod_phone_no'];
											$dept_name = $fetch_dept_details['dpt_name'];
											$company_email = $inst_email;
											
											$sms_rate = $fetchsys_config['fax'];
                                            $refid = "EA-empRegAlert-".rand(1000000,9999999);
                                            $mybalance = $iassigned_walletbal - $sms_rate;
											
											$sysabb = $isenderid;
											
											$sms = "$sysabb>>>NOTIFICATION: This is to inform you that you have a pending loans to be reviewed. Login to your account here: https://esusu.app/$sysabb to view";
											
											(strlen($phone) == "14" || strlen($phone) == "13" || strlen($phone) == "11") ? include("../cron/send_general_sms.php") : "";
											(strlen($phone) == "14" || strlen($phone) == "13" || strlen($phone) == "11") ? mysqli_query($link, "INSERT INTO wallet_history VALUE(null,'$institution_id','$refid','$phone','','$sms_rate','Debit','NGN','Charges','SMS Content: $message','successful','$mycurrentTime','$iuid','$mybalance','')") : "";
                                            (strlen($phone) == "14" || strlen($phone) == "13" || strlen($phone) == "11") ? mysqli_query($link, "INSERT INTO sms_logs1 VALUES(null,'$institution_id','institution','$sender','$phone','$message','Sent',NOW())") : "";
                                            (strlen($phone) == "14" || strlen($phone) == "13" || strlen($phone) == "11") ? mysqli_query($link, "UPDATE institution_data SET wallet_balance = '$mybalance' WHERE institution_id = '$institution_id'") : "";
											
											mysqli_query($link, "INSERT INTO campaign_note VALUES(null,'$LID','$iuid','$uaccount','$Status_save','$lnote',NOW())");
											mysqli_query($link, "UPDATE loan_info SET status = '$Status_save', dept = '$forward_to', teller = '$uname' WHERE id = '$UserID'");
											
											include("../cron/send_loan_notification.php");
									        
									        echo "<script>window.location='pendingloans.php?id=".$_SESSION['tid']."&&mid=NDA1&&tab=tab_1'; </script>";
									    }
									    elseif($Status_save == "Approved"){
											$treat_loan = mysqli_query($link, "SELECT * FROM loan_info WHERE id = '$UserID'") or die ("Error: " . mysqli_error($link));
											$get_treatedloan = mysqli_fetch_array($treat_loan);
											$LID = $get_treatedloan['lid'];
											$lstatus = "NotPaid";
											$uaccount = $get_treatedloan['baccount'];
											$expire_date = $get_treatedloan['pay_date'];
											$amount = $get_treatedloan['amount'];
											
											$search_psche = mysqli_query($link, "SELECT sum(payment) FROM pay_schedule WHERE lid = '$LID'") or die ("Error: " . mysqli_error($link));
											$get_psche = mysqli_fetch_array($search_psche);
											$amount = $get_psche['sum(payment)'];
											$search_psche1 = mysqli_query($link, "SELECT schedule FROM pay_schedule WHERE lid = '$LID' ORDER BY id DESC") or die ("Error: " . mysqli_error($link));
											$get_psche1 = mysqli_fetch_array($search_psche1);
											$schedule = $get_psche1['schedule'];
											
											$search_memset = mysqli_query($link, "SELECT * FROM member_settings WHERE companyid = '$institution_id'");
                                            $fetch_memset = mysqli_fetch_array($search_memset);
											
											$look_user = mysqli_query($link, "SELECT * FROM user WHERE id = '$mytid'");
											$get_user = mysqli_fetch_array($look_user);
											$uname = $get_user['name'];
											$date = date("Y-m-d");
											
											//to update loan status in user account
											mysqli_query($link,"UPDATE loan_info SET status='$Status_save', teller='$uname', amount_topay='$amount', balance='$amount', date_release='$date', pay_date='$schedule' WHERE id = '$UserID'")or die(mysqli_error()); 
											include("alert_sender/loan_approval_alert.php");
											echo "<script>window.location='pendingloans.php?id=".$_SESSION['tid']."&&mid=NDA1&&tab=tab_1'; </script>";
										}
										elseif($Status_save == "Disbursed"){
											$treat_loan = mysqli_query($link, "SELECT * FROM loan_info WHERE id = '$UserID'") or die ("Error: " . mysqli_error($link));
											$get_treatedloan = mysqli_fetch_array($treat_loan);
											$LID = $get_treatedloan['lid'];
											$lstatus = "NotPaid";
											$uaccount = $get_treatedloan['baccount'];
											$expire_date = $get_treatedloan['pay_date'];
											$loan_amount = $get_treatedloan['amount'];
											
											$search_psche = mysqli_query($link, "SELECT sum(payment) FROM pay_schedule WHERE lid = '$LID'") or die ("Error: " . mysqli_error($link));
											$get_psche = mysqli_fetch_array($search_psche);
											$amount = $get_psche['sum(payment)'];
											$search_psche1 = mysqli_query($link, "SELECT schedule FROM pay_schedule WHERE lid = '$LID' ORDER BY id DESC") or die ("Error: " . mysqli_error($link));
											$get_psche1 = mysqli_fetch_array($search_psche1);
											$schedule = $get_psche1['schedule'];
											
											$search_memset = mysqli_query($link, "SELECT * FROM member_settings WHERE companyid = '$institution_id'");
                                            $fetch_memset = mysqli_fetch_array($search_memset);
											
											$look_user = mysqli_query($link, "SELECT * FROM user WHERE id = '$mytid'");
											$get_user = mysqli_fetch_array($look_user);
											$uname = $get_user['name'];
											$date = date("Y-m-d");
											
											//to update loan status in user account
											mysqli_query($link,"UPDATE loan_info SET status='$Status_save', teller='$uname' WHERE id = '$UserID'")or die(mysqli_error()); 
											include("alert_sender/loan_disbured_alert.php");
											echo "<script>window.location='pendingloans.php?id=".$_SESSION['tid']."&&mid=NDA1&&tab=tab_1'; </script>";
										}
										else{
											$treat_loan = mysqli_query($link, "SELECT * FROM loan_info WHERE id = '$UserID'") or die ("Error: " . mysqli_error($link));
											$get_treatedloan = mysqli_fetch_array($treat_loan);
											$LID = $get_treatedloan['lid'];
											$lstatus = "NotPaid";
											$uaccount = $get_treatedloan['baccount'];
											$expire_date = $get_treatedloan['pay_date'];
											$amount = $get_treatedloan['amount'];
											
											$search_memset = mysqli_query($link, "SELECT * FROM member_settings WHERE companyid = '$institution_id'");
                                            $fetch_memset = mysqli_fetch_array($search_memset);
											
											mysqli_query($link,"UPDATE loan_info SET status='$Status_save' WHERE id = '$UserID'")or die(mysqli_error()); 
											include("alert_sender/loan_approval_alert2.php");
											echo "<script>window.location='pendingloans.php?id=".$_SESSION['tid']."&&mid=NDA1&&tab=tab_1'; </script>";
										}			
    								}
    								?>
										</form>
		  
		  </p>
		  
		  <?php
            if($idept_settings === "No" || $idept_settings === "")
            {
                echo "";
            }else{
            ?>
            
		  <div class="box">
			 
	             <div class="box-body table-responsive">
		 			<table width="100%" border="1" bordercolor="<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">
						<div class="bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>"><h4> Note:</h4> </div>
						<?php
						$search_note = mysqli_query($link, "SELECT * FROM campaign_note WHERE cpid = '$mylid' ORDER BY id DESC");
						while($get_note = mysqli_fetch_object($search_note))
						{
							$staffid = $get_note->staffid;
							$search_staff = mysqli_query($link, "SELECT * FROM user WHERE id = '$staffid'");
							$get_staff = mysqli_fetch_object($search_staff);
						?>
		 			  <tr class="form-group" width="100%">
		 				<td><div class="col-sm-12" style="font-size: 13px"><b> <?php echo $get_staff->name; ?> <?php echo ($get_staff->dept == "") ? "(Admin)" : '('.$get_staff->dept.')'; ?> </b></div></td>
						<td><div class="col-sm-12" style="font-size: 12px"><i>To be Reviewed by: <b><?php echo $dept_name; ?></b> - NOTE: <?php echo $get_note->cnote; ?> <b>(<?php echo $get_note->note_date; ?>)</b></i></div></td>
					</tr>
					<?php } ?>
				</table>
				</div>
				
			</div>
			<?php
            }
            ?>
       
             </div>
             
             </div>
             <!-- /.tab-pane --> 
             
<?php 
} 
} 
?>
</div>
</div>
</div>

              </div>


	
</div>	
</div>
</div>	
</div>