<div class="box">
	       <div class="box-body">
			<div class="panel panel-success">
            <div class="panel-heading bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">
            <h3 class="panel-title"> <a href="view_teller.php?id=<?php echo $_SESSION['tid']; ?>&&mid=NDEw"><button type="button" class="btn btn-flat bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>"><i class="fa fa-mail-reply-all"></i>&nbsp;Back</button> </a> <i class="fa fa-desktop"></i>  Add Till</h3>
            </div>

             <div class="box-body">
 <?php
if(isset($_POST['save']))
{
    $tillID = $_GET['idm'];
	$till_name =  mysqli_real_escape_string($link, $_POST['till_name']);
	$cashier = mysqli_real_escape_string($link, $_POST['cashier']);
	$office =  mysqli_real_escape_string($link, $_POST['office']);
	$till_desc =  mysqli_real_escape_string($link, $_POST['till_desc']);
	$status =  mysqli_real_escape_string($link, $_POST['status']);
	$commtype = mysqli_real_escape_string($link, $_POST['commtype']);
	$percentage = mysqli_real_escape_string($link, $_POST['percentage']);
	
	/*$search_user = mysqli_query($link, "SELECT * FROM user WHERE id = '$cashier'");
	$fetch_user = mysqli_fetch_array($search_user);
	$phone = $fetch_user['phone'];
	
	$search_memberset = mysqli_query($link, "SELECT * FROM member_settings WHERE companyid = '$institution_id'");
	$fetch_memberset = mysqli_fetch_array($search_memberset);
	$sysabb = $fetch_memberset['sender_id'];
	
	$sms = "$sysabb>>>This is to notify you that your Till Account have been Credited with the Total Amount of $icurrency.'-'.number_format("*/
	
	$update = mysqli_query($link, "UPDATE till_account SET branch = '$office', teller = '$till_name', cashier = '$cashier', commission_type = '$commtype', commission = '$percentage', description = '$till_desc', status = '$status' WHERE id = '$tillID'") or die ("Error: " . mysqli_error($link));
	if(!$update)
	{
		echo "<div class='alert alert-info'>Unable to Update Till Account.....Please try again later</div>";
	}
	else{
		echo "<div class='alert alert-success'>Till Account Updated Successfully!</div>";
	}
}
?>           
			 <form class="form-horizontal" method="post" enctype="multipart/form-data">

             <div class="box-body">
<?php
$tillID = $_GET['idm'];
$searchTill = mysqli_query($link, "SELECT * FROM till_account WHERE id = '$tillID'");
$fetchTill = mysqli_fetch_array($searchTill);
$sid = $fetchTill['cashier'];
$bid = $fetchTill['branch'];

$searchGet = mysqli_query($link, "SELECT * FROM user WHERE id = '$sid'") or die (mysqli_error($link));
$fetchRows = mysqli_fetch_array($searchGet);

$searchBid = mysqli_query($link, "SELECT * FROM branches WHERE branchid = '$bid'") or die (mysqli_error($link));
$fetchBid = mysqli_fetch_array($searchBid);
?>
                  <input name="branchid" type="hidden" class="form-control" value="">
			
			<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Till Name</label>
                  <div class="col-sm-10">
                  <input name="till_name" type="text" class="form-control" value="<?php echo $fetchTill['teller']; ?>" placeholder="Till Name" required>
                  </div>
                  </div>

			<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Cashier Name</label>
                  <div class="col-sm-10">
				<select name="cashier" class="form-control select2" required>
					<option value='<?php echo $sid; ?>' selected='selected'><?php echo $fetchRows['name'].' '.$fetchRows['lname'].' '.$fetchRows['mname']; ?></option>
					<?php
					$get = mysqli_query($link, "SELECT * FROM user WHERE created_by = '$institution_id' ORDER BY userid") or die (mysqli_error($link));
					while($rows = mysqli_fetch_array($get))
					{
					?>
					<option value="<?php echo $rows['id']; ?>"><?php echo $rows['name'].' '.$rows['lname'].' '.$rows['mname']; ?></option>
					<?php } ?>
									</select>
									 </div>
                 					 </div>

            <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Office</label>
                  <div class="col-sm-10">
				<select name="office" class="form-control select2">
										<option value='<?php echo $bid; ?>' selected='selected'><?php echo $fetchBid['bname']; ?></option>
										<option value=''>Head Office</option>
										<?php
					$get_office = mysqli_query($link, "SELECT * FROM branches WHERE created_by = '$institution_id' ORDER BY id") or die (mysqli_error($link));
					while($office_rows = mysqli_fetch_array($get_office))
					{
					?>
					<option value="<?php echo $office_rows['branchid']; ?>"><?php echo $office_rows['bname']; ?></option>
					<?php } ?>
									</select>
									 </div>
                 					 </div>
									  
			<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Commission Type</label>
                  <div class="col-sm-10">
				<select name="commtype" class="form-control select2">
										<option value="<?php echo $fetchTill['commission_type']; ?>" selected='selected'><?php echo $fetchTill['commission_type']; ?></option>
										<option value='Percentage'>Percentage</option>
										<option value='Flat'>Flat</option>
									</select>
									 </div>
                 					 </div>
                 					 
            <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Commision (%)</label>
                  <div class="col-sm-10">
                  <input name="percentage" type="text" class="form-control" value="<?php echo $fetchTill['commission']; ?>" placeholder="Deposit Commission like 2.2, 5, 10.... without putting % sign" required>
                  </div>
                  </div>

            <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Description</label>
                  <div class="col-sm-10">
				  <textarea name="till_desc"  class="form-control" rows="4" cols="80" placeholder="Description"><?php echo $fetchTill['description']; ?></textarea>
                </div>
                </div>

            <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Status</label>
                  <div class="col-sm-10">
				<select name="status" class="form-control select2" required>
										<option value='<?php echo $fetchTill['status']; ?>' selected='selected'><?php echo $fetchTill['status']; ?></option>
										<option value='Active'>Active</option>
										<option value='NotActive'>NotActive</option>
									</select>
									 </div>
                 					 </div>				  
				  
			 </div>
			 
			  <div align="right">
              <div class="box-footer">
                				<button name="save" type="submit" class="btn bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>"><i class="fa fa-save">&nbsp;Save</i></button>
              </div>
			  </div>
			  
			 </form> 

</div>	
</div>	
</div>
</div>