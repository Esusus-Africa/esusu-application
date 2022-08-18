<?php
$select = mysqli_query($link, "SELECT * FROM loan_info") or die (mysqli_error($link));
while($row = mysqli_fetch_array($select))
{
$id = $row['id'];
$lid = $row['lid'];
?>
<div class="modal fade" id="myModal<?php echo $id; ?>" role="dialog">
    <div class="modal-dialog">
          <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
<legend><?php echo ($idept_settings == "No") ? "Edit Status" : "Internal Processing"; ?> <?php echo $id; ?></legend>
        </div>
        <div class="modal-body">
          <p>

<?php
$select_realloan = mysqli_query($link, "SELECT * FROM loan_info WHERE id = '$id'") or die (mysqli_error($link));
$fetch_realloan = mysqli_fetch_array($select_realloan);
$id = $fetch_realloan['id'];
$lide = $fetch_realloan['lid'];
?>
		  
		  <form class="form-horizontal" method="post"  enctype="multipart/form-data">

								<input type="hidden" value="<?php echo $id; ?>"  name="userid">
								<input type="hidden" value="<?php echo $lide; ?>"  name="lid">

									<div class="form-group">
                  <label for="" class="col-sm-3 control-label"style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">Status</label>
				  <div class="col-sm-9">
				  <select name="Status" class="form-control select2" data-placeholder="Select Status" style="width: 100%;" required>
									<!-----<select class="form-control select2 " multiple="multiple" type="text" name="Status" >------>
											<option value="" selected> Select Status</option>
											<?php echo ($internal_review == 1 && $idept_settings == "No" ? "" : ($internal_review == 1 && $idept_settings == "Yes" ? '<option value="Internal-Review">Internal-Review</option>' : "")); ?>
											<?php echo ($approved_loan == 1) ? '<option value="Approved">Approve</option>' : ""; ?>
											<?php echo ($disbursed_loan == 1 && $idept_settings == "No" ? "" : ($disbursed_loan == 1 && $idept_settings == "Yes" ? '<option value="Disbursed">Disburse</option>' : "")); ?>
											<?php echo ($disapproved_loan == 1) ? '<option value="Disapproved">Disapprove</option>' : ""; ?>
										 </select>

                                    </div>
                                    </div>
                                    <?php
                                    if($idept_settings == "No")
                                    {
                                        echo "";
                                    }else{
                                    ?>
                                    
                                    <div class="form-group">
                  <label for="" class="col-sm-3 control-label"style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">Forward to: </label>
				  <div class="col-sm-9">
				  <select name="forward_to" class="form-control select2" data-placeholder="Select Department to forward for further review" style="width: 100%;" required>
									<!-----<select class="form-control select2 " multiple="multiple" type="text" name="Status" >------>
											<option value="" selected>Select Department to forward for further review</option>
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
                                    
                                    <?php 
                                    } 
                                    ?>
                                    
                                <div class="form-group">
						            <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">Note</label>
						            <div class="col-sm-10">
						   		<textarea name="lnote" class="form-control" rows="2" cols="80" Placeholder="Enter Note" required></textarea>
						              			 </div>
						             </div>
						             
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
									
									    if($Status_save == "Internal-Review"){
									        $treat_loan = mysqli_query($link, "SELECT * FROM loan_info WHERE id = '$UserID'") or die ("Error: " . mysqli_error($link));
											$get_treatedloan = mysqli_fetch_array($treat_loan);
											$LID = $get_treatedloan['lid'];
											$uaccount = $get_treatedloan['baccount'];
											
											$look_user = mysqli_query($link, "SELECT * FROM user WHERE id = '$mytid'");
											$get_user = mysqli_fetch_array($look_user);
											$uname = $get_user['name'];
											
											mysqli_query($link, "INSERT INTO campaign_note VALUES(null,'$LID','$iuid','$uaccount','$Status_save','$lnote',NOW())");
											mysqli_query($link, "UPDATE loan_info SET status = '$Status_save', dept = '$forward_to', teller = '$uname', agent='$uname' WHERE id = '$UserID'");
									        
									        echo "<script>window.location='pendingloans.php?id=".$_SESSION['tid']."&&mid=NDA1'; </script>";
									    }
									    elseif($Status_save == "Approved"){
											$treat_loan = mysqli_query($link, "SELECT * FROM loan_info WHERE id = '$UserID'") or die ("Error: " . mysqli_error($link));
											$get_treatedloan = mysqli_fetch_array($treat_loan);
											$LID = $get_treatedloan['lid'];
											$lstatus = "NotPaid";
											$uaccount = $get_treatedloan['baccount'];
											$expire_date = $get_treatedloan['pay_date'];
											
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
											mysqli_query($link,"UPDATE loan_info SET status='$Status_save', teller='$uname', agent='$uname', amount_topay='$amount', balance='$amount', date_release='$date', pay_date='$schedule' WHERE id = '$UserID'")or die(mysqli_error()); 
											include("alert_sender/loan_approval_alert.php");
											echo "<script>window.location='pendingloans.php?id=".$_SESSION['tid']."&&mid=NDA1'; </script>";
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
											mysqli_query($link,"UPDATE loan_info SET status='$Status_save', teller='$uname', agent='$uname' WHERE id = '$UserID'")or die(mysqli_error()); 
											include("alert_sender/loan_disbured_alert.php");
											echo "<script>window.location='pendingloans.php?id=".$_SESSION['tid']."&&mid=NDA1'; </script>";
										}
										else{
											$treat_loan = mysqli_query($link, "SELECT * FROM loan_info WHERE id = '$UserID'") or die ("Error: " . mysqli_error($link));
											$get_treatedloan = mysqli_fetch_array($treat_loan);
											$LID = $get_treatedloan['lid'];
											$lstatus = "NotPaid";
											$uaccount = $get_treatedloan['baccount'];
											$expire_date = $get_treatedloan['pay_date'];
											
											$search_memset = mysqli_query($link, "SELECT * FROM member_settings WHERE companyid = '$institution_id'");
                                            $fetch_memset = mysqli_fetch_array($search_memset);
											
											mysqli_query($link,"UPDATE loan_info SET status='$Status_save' WHERE id = '$UserID'")or die(mysqli_error()); 
											include("alert_sender/loan_approval_alert2.php");
											echo "<script>window.location='pendingloans.php?id=".$_SESSION['tid']."&&mid=NDA1'; </script>";
										}			
    								}
    								?>
										</form>
		  
		  </p>
		  
		  <div class="box">
			 
	             <div class="box-body table-responsive">
		 			<table width="100%" border="1" bordercolor="<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">
						<div class="bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>"><h4> Note:</h4> </div>
						<?php
						$search_note = mysqli_query($link, "SELECT * FROM campaign_note WHERE cpid = '$lid' ORDER BY id DESC");
						while($get_note = mysqli_fetch_object($search_note))
						{
							$staffid = $get_note->staffid;
							$search_staff = mysqli_query($link, "SELECT * FROM user WHERE id = '$staffid'");
							$get_staff = mysqli_fetch_object($search_staff);
						?>
		 			  <tr class="form-group" width="100%">
		 				<td><div class="col-sm-12" style="font-size: 13px"><b> <?php echo $get_staff->name; ?> <?php echo ($get_staff->dept == "") ? "(CEO/MD)" : '('.$get_staff->dept.')'; ?> </b></div></td>
						<td><div class="col-sm-12" style="font-size: 12px"><i><?php echo $get_note->cnote; ?> <b>(<?php echo $get_note->cstatus . '&nbsp;-&nbsp;' . $get_note->note_date; ?>)</b></i></div></td>
					</tr>
					<?php } ?>
				</table>
				</div>
				
			</div>
			
			
		  
        </div>
      </div>    
    </div>
<?php } ?>