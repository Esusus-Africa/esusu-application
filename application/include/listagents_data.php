<div class="row">
		
	       
		    <section class="content">  
	        <div class="box box-success">
            <div class="box-body">
              <div class="table-responsive">
             <div class="box-body">
<form method="post">
	<?php echo ($delete_agent == 1) ? '<button type="submit" class="btn btn-flat bg-orange" name="delete"><i class="fa fa-times"></i>&nbsp;Multiple Delete</button>' : ''; ?>
	<?php echo ($send_sms_agent == 1) ? '<a href="sendsms_agents.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("440").'"><button type="button" class="btn btn-flat bg-blue"><i class="fa fa-mobile"></i>&nbsp;Send SMS</button></a>' : ''; ?>
	<hr>				  
			 <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th><input type="checkbox" id="select_all"/></th>
				  <th>Agent ID</th>
                  <th>Name</th>
                  <th>License Number</th>
                  <th>Address</th>
                  <th>Official Contact</th>
                  <th>Total Customer</th>
                  <th>Total Transaction</th>
                  <th>Wallet Balance</th>
                  <th>Expiry Date</th>
                  <th>Reg. Date</th>
                  <th>Actions</th>
                 </tr>
                </thead>
                <tbody>
<?php
$select = mysqli_query($link, "SELECT * FROM institution_data WHERE account_type = 'agent' ORDER BY id DESC") or die (mysqli_error($link));
if(mysqli_num_rows($select)==0)
{
echo "<div class='alert bg-blue'>No data found yet!.....Check back later!!</div>";
}
else{
while($row = mysqli_fetch_array($select))
{
$id = $row['id'];
$instid = $row['institution_id'];
$reg_date = $row['reg_date'];
$iaccount_type = $row['account_type'];

$utc_date = DateTime::createFromFormat('Y-m-d G:i:s',$reg_date,new DateTimeZone('America/New_York'));
$acst_date = clone $utc_date; // we don't want PHP's default pass object by reference here
$acst_date->setTimeZone(new DateTimeZone('GMT+1'));
$correctdate = $acst_date->format('Y-m-d g:i A');

$getcust_no = mysqli_query($link, "SELECT * FROM borrowers WHERE branchid = '$instid'");
$num = mysqli_num_rows($getcust_no);
$getrans_no = mysqli_query($link, "SELECT * FROM transaction WHERE branchid = '$instid'");
$num2 = mysqli_num_rows($getrans_no);

$my_membersettings = mysqli_query($link, "SELECT * FROM member_settings WHERE companyid = '$instid'");
$fetch_mysettings = mysqli_fetch_array($my_membersettings);

$sub_expriry_date = mysqli_query($link, "SELECT * FROM saas_subscription_trans WHERE coopid_instid = '$instid' ORDER BY id DESC");
$fetch_sub_expriry_date = mysqli_fetch_array($sub_expriry_date);
?>    
                <tr>
				<td><input id="optionsCheckbox" class="checkbox"  name="selector[]" type="checkbox" value="<?php echo $id; ?>"></td>
                <td width="150"><b><?php echo $instid; ?></b><?php echo ($row['status'] == "Approved" ? '<span class="label bg-blue">Approved</span>' : ($row['status'] == "Disapproved" ? '<span class="label bg-orange">Disapproved</span>' : ($row['status'] == "Pending" ? '<span class="label bg-red">Pending</span>' : '<span class="label bg-blue">Updated</span>'))); ?></td>
				<td><?php echo strtoupper($row['institution_name']); ?></td>
				<td><?php echo ($row['license_no'] === "") ? "------" : $row['license_no']; ?></td>
				<td><?php echo ($row['location'] === "") ? "------" : $row['location']; ?></td>
				<td><p><b>Phone:</b> <?php echo $row['official_phone']; ?></p>
				    <p><b>Email:</b> <?php echo $row['official_email']; ?></p>
				</td>
				<td><b><?php echo $num; ?></b></td>
				<td><b><?php echo $num2; ?></b></td>
				<td><?php echo $fetch_mysettings['currency'].number_format($row['wallet_balance'],2,'.',','); ?></td>
				<td><?php echo ($fetch_sub_expriry_date['duration_to'] == "") ? '-------' : '<b>'.$fetch_sub_expriry_date['duration_to'].'</b>'; ?></td>
				<td><?php echo $correctdate; ?></td>
				<td align="center">
				<div class="btn-group">
                      <div class="btn-group">
                        <button type="button" class="btn bg-blue dropdown-toggle" data-toggle="dropdown">
                          <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu">
                          <?php //echo ($view_institution_members == 1) ? '<li><p><a href="view_instmembers?instid='.$instid.'&&mid='.base64_encode("419").'" class="btn btn-default btn-flat" target="_blank"><i class="fa fa-search">&nbsp;View Members</i></a></p></li>' : '----------'; ?>
                          <?php echo ($update_agent == 1) ? '<li><p><a href="update_instinfo?idm='.$id.'&&id='.$_SESSION['tid'].'&&mid='.(($iaccount_type === "institution" ? "NDE5" : ($iaccount_type === "agent" ? "NDQw" : "NDkw"))).'&&tab=tab_1" class="btn btn-default btn-flat"><i class="fa fa-edit">&nbsp;Update Info.</i></a></p></li>' : '----------'; ?>
                          <?php echo ($update_agent == 1) ? '<li><p><a href="instprofile_settings?idm='.$instid.'&&id='.$_SESSION['tid'].'&&mid='.(($iaccount_type === "institution" ? "NDE5" : ($iaccount_type === "agent" ? "NDQw" : "NDkw"))).'&&tab=tab_1" class="btn btn-default btn-flat"><i class="fa fa-gear">&nbsp;Profile Settings</i></a></p></li>' : '-------'; ?>
						  <?php //echo ($row['status'] == "Pending") ? '<li><p><a href="resend_iemail.php?id='.$instid.'" class="btn btn-default btn-flat"><i class="fa fa-forward">Resend Email</i></a></p></li>' : ''; ?>
						</ul>
                      </div>
                </div>
				</td>
				</tr>
<?php } } ?>
             </tbody>
                </table>  
						<?php
						if(isset($_POST['delete'])){
						$idm = $_GET['id'];
							$id=$_POST['selector'];
							$N = count($id);
						if($id == ''){
						echo "<script>alert('Row Not Selected!!!'); </script>";	
						echo "<script>window.location='listagents.php?id=".$_SESSION['tid']."&&mid=NDQw'; </script>";
							}
							else{
							for($i=0; $i < $N; $i++)
							{
								$search_cmem = mysqli_query($link,"SELECT * FROM institution_data WHERE id ='$id[$i]'");
								$fetch_cmem = mysqli_fetch_array($search_cmem);
								$instiid = $fetch_cmem['institution_id'];
								$instlogo = "../".$fetch_cmem['institution_logo'];
								
								$search_adoc = mysqli_query($link,"SELECT * FROM institution_legaldoc WHERE instid = '$instiid'");
								$fetch_adoc = mysqli_fetch_array($search_adoc);
								$adoc = $fetch_adoc['document'];

								unlink($instlogo);
								unlink($adoc);
								$result = mysqli_query($link,"DELETE FROM user WHERE created_by = '$instiid'");
								$result = mysqli_query($link, "DELETE FROM institution_legaldoc WHERE instid = '$instiid'");
								$result = mysqli_query($link,"DELETE FROM institution_data WHERE id ='$id[$i]'");
								echo "<script>alert('Row Delete Successfully!!!'); </script>";
								echo "<script>window.location='listagents.php?id=".$_SESSION['tid']."&&mid=NDQw'; </script>";
							}
							}
							}
						?>	
						
</form>
				

              </div>


	
</div>	
</div>
</div>	
</div>