<?php
$select = mysqli_query($link, "SELECT * FROM campaign") or die (mysqli_error($link));
while($rows = mysqli_fetch_array($select))
{
$id = $rows['id'];
?>

<div class="modal fade" id="myModal<?php echo $id; ?>" role="dialog">
    <div class="modal-dialog">
          <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
<legend>Campaign Processing...</legend>
        </div>
        <div class="modal-body">
          <p>
		  
		  
		  <form class="form-horizontal" method="post"  enctype="multipart/form-data">
									
								<input type="hidden" value="<?php echo $id; ?>"  name="campaign_id">
                                <input type="hidden" value="<?php echo $rows['account_id']; ?>"  name="b_id">

									<div class="form-group">
                  <label for="" class="col-sm-2 control-label"style="color:blue;">Status</label>
				  <div class="col-sm-10">
				  <select name="Status" class="form-control" data-placeholder="Status" style="width: 100%;" required>
									<!-----<select class="form-control select2 " multiple="multiple" type="text" name="Status" >------>
											<option value="" selected> Select Status... </option>
											<option value="Approved">Approved</option>
											<option value="Disapproved">Disapproved</option>
											<option value="Pending">Pending</option>
										 </select>

                                    </div>
                                    </div>
									
						   		 <div class="form-group">
						            <label for="" class="col-sm-2 control-label" style="color:blue;">Note</label>
						            <div class="col-sm-10">
						   		<textarea name="cnote" class="form-control" rows="4" cols="80" Placeholder="Enter Note (Optional)"></textarea>
						              			 </div>
						             </div>
									 
								<div class="modal-footer">
								<button type="submit" name="update_status" class="btn btn-flat bg-blue"><i class="icon-save"></i>&nbsp;Process</button>
								<button class="btn btn-flat bg-orange" data-dismiss="modal" aria-hidden="true"><i class="icon-remove icon-large"></i> Close</button>
								</div>
								</div>

								<?php
									if (isset($_POST['update_status'])) {
					
									$Status_save = $_POST['Status'];
									$campaign_id = $_POST['campaign_id'];
									$b_id = $_POST['b_id'];
									$cnote = $_POST['cnote'];
									$mytid = $_SESSION['tid'];
									
									if($cnote == "")
									{
										mysqli_query($link,"UPDATE campaign SET campaign_status='$Status_save' WHERE id = '$campaign_id'")or die(mysqli_error($link)); 
										//include("alert_sender/campaign_alert.php");
										echo "<script>window.location='campaign_list.php?id=".$_SESSION['tid']."&&mid=NzUw&&tab=tab_1'; </script>";
									}
									else{
										mysqli_query($link, "INSERT INTO campaign_note VALUES(null,'$campaign_id','$mytid','$b_id','$Status_save','$cnote',NOW())");
										mysqli_query($link,"UPDATE campaign SET campaign_status='$Status_save' WHERE id = '$campaign_id'")or die(mysqli_error($link)); 
										//include("alert_sender/campaign_alert.php");
										echo "<script>window.location='campaign_list.php?id=".$_SESSION['tid']."&&mid=NzUw&&tab=tab_1'; </script>";		
									}
								}
								?>
										</form>
		  
		  </p>
		  
	      	<div class="box">
			 
	             <div class="box-body table-responsive">
		 			<table width="100%" border="1" bordercolor="orange">
						<div class="alert-danger" style="color: blue;"><h4> Staff Note:</h4> </div>
						<?php
						$search_note = mysqli_query($link, "SELECT * FROM campaign_note WHERE id = '$id' ORDER BY id DESC");
						while($get_note = mysqli_fetch_object($search_note))
						{
							$staffid = $get_note->staffid;
							$search_staff = mysqli_query($link, "SELECT * FROM user WHERE id = '$staffid'");
							$get_staff = mysqli_fetch_object($search_staff);
						?>
		 			  <tr class="form-group" width="100%">
		 				<td><div class="col-sm-12" style="font-size: 13px"><b> <?php echo $get_staff->name; ?>: </b></div></td>
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