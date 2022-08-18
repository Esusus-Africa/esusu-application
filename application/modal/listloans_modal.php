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
<legend>Edit Status</legend>
        </div>
        <div class="modal-body">
          <p>
		  
		  
		  <form class="form-horizontal" method="post"  enctype="multipart/form-data">
									
								<input type="hidden" value="<?php echo $id; ?>"  name="userid">
								<input type="hidden" value="<?php echo $lid; ?>"  name="lid">

									<div class="form-group">
                  <label for="" class="col-sm-2 control-label"style="color:#FF0000">Update Status</label>
				  <div class="col-sm-10">
				  <select name="Status" class="form-control" data-placeholder="Status" style="width: 100%;">
									<!-----<select class="form-control select2 " multiple="multiple" type="text" name="Status" >------>
											<option> </option>
											<option value="Approved">Approved</option>
											<option value="Disapproved">Disapproved</option>
										 </select>

                                    </div>
                                    </div>
								<div class="modal-footer">
								<button type="submit" name="update_status" class="btn btn-flat btn-success"><i class="icon-save"></i>&nbsp;Update</button>
								<button class="btn btn-flat btn-danger" data-dismiss="modal" aria-hidden="true"><i class="icon-remove icon-large"></i> Close</button>
								</div>
								</div>

								<?php
									if (isset($_POST['update_status'])) {
					
									$Status_save = $_POST['Status'];
									$UserID = $_POST['userid'];
									$LID = $_POST['lid'];
									$mytid = $_SESSION['tid'];
									
									if($Status_save == "Approved"){
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
											
											$search_sys = mysqli_query($link, "SELECT * FROM systemset") or die("Error:" . mysqli_error($link));
											$get_sys = mysqli_fetch_array($search_sys);
											
											$look_user = mysqli_query($link, "SELECT * FROM user WHERE id = '$mytid'");
											$get_user = mysqli_fetch_array($look_user);
											$uname = $get_user['name'];
											$date = date("Y-m-d");
											
											//to update loan status in user account
											mysqli_query($link,"UPDATE loan_info SET status='$Status_save', teller='$uname', agent='$uname', amount_topay='$amount', balance='$amount', date_release='$date', pay_date='$schedule' WHERE id = '$UserID'")or die(mysqli_error()); 
											include("alert_sender/loan_approval_alert.php");
											echo "<script>window.location='listloans.php?id=".$_SESSION['tid']."'; </script>";
										}
										else{
											$treat_loan = mysqli_query($link, "SELECT * FROM loan_info WHERE id = '$UserID'") or die ("Error: " . mysqli_error($link));
											$get_treatedloan = mysqli_fetch_array($treat_loan);
											$LID = $get_treatedloan['lid'];
											$lstatus = "NotPaid";
											$uaccount = $get_treatedloan['baccount'];
											$expire_date = $get_treatedloan['pay_date'];
											
											$search_sys = mysqli_query($link, "SELECT * FROM systemset") or die("Error:" . mysqli_error($link));
											$get_sys = mysqli_fetch_array($search_sys);
											
											mysqli_query($link,"UPDATE loan_info SET status='$Status_save' WHERE id = '$UserID'")or die(mysqli_error()); 
											include("alert_sender/loan_approval_alert2.php");
											echo "<script>window.location='listloans.php?id=".$_SESSION['tid']."'; </script>";
										}			
								}
								?>
										</form>
		  
		  </p>
        </div>
      </div>    
    </div>
	<?php } ?>