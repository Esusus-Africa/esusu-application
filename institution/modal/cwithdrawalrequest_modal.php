<?php
$select = mysqli_query($link, "SELECT * FROM withdrawal_request") or die (mysqli_error($link));
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
<legend>Withdrawal Processing...</legend>
        </div>
        <div class="modal-body">
          <p>
		  
		  
		  <form class="form-horizontal" method="post"  enctype="multipart/form-data">
									
								<input type="hidden" value="<?php echo $id; ?>"  name="w_id">
								<input type="hidden" value="<?php echo $rows['b_id']; ?>"  name="b_id">
								<input type="hidden" value="<?php echo $rows['c_id']; ?>"  name="c_id">
								<input type="hidden" value="<?php echo $rows['wid']; ?>"  name="wid">

									<div class="form-group">
                  <label for="" class="col-sm-2 control-label"style="color:#FF0000">Status</label>
				  <div class="col-sm-10">
				  <select name="Status" class="form-control" data-placeholder="Status" style="width: 100%;" required>
									<!-----<select class="form-control select2 " multiple="multiple" type="text" name="Status" >------>
											<option selected> Select Status... </option>
											<option value="Approved">Approved</option>
											<option value="Desclined">Desclined</option>
										 </select>

                                    </div>
                                    </div>
									
						   		 <div class="form-group">
						            <label for="" class="col-sm-2 control-label" style="color:#009900">Note</label>
						            <div class="col-sm-10">
						   		<textarea name="wnote" class="form-control" rows="4" cols="80" Placeholder="Enter Note (Optional)"></textarea>
						              			 </div>
						             </div>
									 
								<div class="modal-footer">
								<button type="submit" name="update_status" class="btn btn-flat btn-success"><i class="icon-save"></i>&nbsp;Process</button>
								<button class="btn btn-flat btn-danger" data-dismiss="modal" aria-hidden="true"><i class="icon-remove icon-large"></i> Close</button>
								</div>
								</div>

								<?php
									if (isset($_POST['update_status'])) {
					
									$Status_save = $_POST['Status'];
									$w_id = $_POST['w_id'];
									$wnote = $_POST['wnote'];
									$b_id = $_POST['b_id'];
									$c_id = $_POST['c_id'];
									$wid = $_POST['wid'];
									$mytid = $_SESSION['tid'];
									
									if($wnote == "")
									{
										mysqli_query($link,"UPDATE withdrawal_request SET wstatus='$Status_save' WHERE id = '$w_id'")or die(mysqli_error($link)); 
										echo "<script>window.location='cwithdrawalrequest.php?id=".$_SESSION['tid']."&&mid=NDIx'; </script>";
									}
									else{
										mysqli_query($link, "INSERT INTO withdrawal_note VALUES('','$mytid','$b_id','$c_id','$wid','$Status_save','$wnote',NOW())");
										mysqli_query($link,"UPDATE withdrawal_request SET wstatus='$Status_save' WHERE id = '$w_id'")or die(mysqli_error($link));
										echo "<script>window.location='cwithdrawalrequest.php?id=".$_SESSION['tid']."&&mid=NDIx'; </script>";	
									}
								}
								?>
										</form>
		  
		  </p>
		  
	      	<div class="box">
			 
	             <div class="box-body table-responsive">
		 			<table width="100%" border="1" bordercolor="#000000">
						<div class="alert-danger" style="color: green;"><h4> Staff Note:</h4> </div>
						<?php
						$search_note = mysqli_query($link, "SELECT * FROM withdrawal_note WHERE id = '$id' ORDER BY id DESC");
						while($get_note = mysqli_fetch_object($search_note))
						{
							$staffid = $get_note->staffid;
							$search_staff = mysqli_query($link, "SELECT * FROM user WHERE id = '$staffid'");
							$get_staff = mysqli_fetch_object($search_staff);
						?>
		 			  <tr class="form-group" width="100%">
		 				<td><div class="col-sm-12" style="font-size: 13px"><b> <?php echo $get_staff->name; ?>: </b></div></td>
						<td><div class="col-sm-12" style="font-size: 12px"><i><?php echo $get_note->wnote; ?> <b>(<?php echo $get_note->status . '&nbsp;-&nbsp;' . $get_note->date; ?>)</b></i></div></td>
					</tr>
					<?php } ?>
				</table>
				</div>
				
			</div>
        </div>
      </div>    
    </div>
	<?php } ?>