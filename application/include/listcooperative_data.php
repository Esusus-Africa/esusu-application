<div class="row">
		
	       
		    <section class="content">  
	        <div class="box box-success">
            <div class="box-body">
              <div class="table-responsive">
             <div class="box-body">
<form method="post">
	 <?php echo ($delete_cooperatives == 1) ? '<button type="submit" class="btn btn-flat bg-orange" name="delete"><i class="fa fa-times"></i>&nbsp;Multiple Delete</button>' : ''; ?>
	<?php echo ($send_sms_coop == 1) ? '<a href="sendsms_cooperatives?id='.$_SESSION['tid'].'&&mid='.base64_encode("418").'"><button type="button" class="btn btn-flat bg-blue"><i class="fa fa-mobile"></i>&nbsp;Send SMS</button></a>' : ''; ?>
	<hr>				  
			 <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th><input type="checkbox" id="select_all"/></th>
				  <th>Coop ID</th>
                  <th>Name</th>
                  <th>Address</th>
                  <th>Official Contact</th>
                  <th>Wallet Balance</th>
                  <th>Expiry Date</th>
                  <th>Reg. Date</th>
                  <th>Actions</th>
                 </tr>
                </thead>
                <tbody>
<?php
$select = mysqli_query($link, "SELECT * FROM cooperatives ORDER BY id DESC") or die (mysqli_error($link));
if(mysqli_num_rows($select)==0)
{
echo "<div class='alert alert-info'>No data found yet!.....Check back later!!</div>";
}
else{
while($row = mysqli_fetch_array($select))
{
$id = $row['id'];
$coopid = $row['coopid'];
$reg_date = $row['reg_date'];

$utc_date = DateTime::createFromFormat('Y-m-d G:i:s',$reg_date,new DateTimeZone('America/New_York'));
$acst_date = clone $utc_date; // we don't want PHP's default pass object by reference here
$acst_date->setTimeZone(new DateTimeZone('GMT+1'));
$correctdate = $acst_date->format('Y-m-d g:i A');

$my_membersettings = mysqli_query($link, "SELECT * FROM member_settings WHERE companyid = '$coopid'");
$fetch_mysettings = mysqli_fetch_array($my_membersettings);

$sub_expriry_date = mysqli_query($link, "SELECT * FROM saas_subscription_trans WHERE coopid_instid = '$coopid' ORDER BY id DESC");
$fetch_sub_expriry_date = mysqli_fetch_array($sub_expriry_date);
?>    
                <tr>
				<td><input id="optionsCheckbox" class="checkbox"  name="selector[]" type="checkbox" value="<?php echo $id; ?>"></td>
                <td width="150"><img class="img-circle" src="../<?php echo $row ['cooplogo'];?>" width="30" height="30" align="center"> <b><?php echo $coopid; ?></b><br>
                	<?php echo ($row['status'] == "Approved" ? '<span class="label bg-blue">Approve</span>' : ($row['status'] == "Disapproved" ? '<span class="label bg-orange">Disapproved</span>' : ($row['status'] == "Pending" ? '<span class="label bg-red">Pending</span>' : '<span class="label bg-blue">Updated</span>'))); ?></td>
				<td><?php echo $row['coopname']; ?>
				<hr>
				<b>Reg. Number:</b> <span class='label bg-blue'><?php echo $row['reg_no']; ?></span>
				</td>
				<td><?php echo $row['address']; ?></td>
				<td><?php echo $row['official_phone']; ?></td>
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
                          <?php echo ($view_coop_members == 1) ? '<li><p><a href="view_coopmembers?cid='.$coopid.'&&mid='.base64_encode("418").'" class="btn btn-default btn-flat" target="_blank"><i class="fa fa-search">&nbsp;View Members</i></a></p></li>' : '-------'; ?>
                          <?php echo ($view_coop_members == 1) ? '<li><p><a href="update_coopinfo?idm='.$id.'&&id='.$_SESSION['tid'].'&&mid='.base64_encode("418").'&&tab=tab_1" class="btn btn-default btn-flat"><i class="fa fa-edit">&nbsp;Update Info.</i></a></p></li>' : '-------'; ?>
                          <?php echo ($view_coop_members == 1) ? '<li><p><a href="coopprofile_settings?idm='.$coopid.'&&id='.$_SESSION['tid'].'&&mid='.base64_encode("418").'&&tab=tab_1" class="btn btn-default btn-flat"><i class="fa fa-gear">&nbsp;Profile Settings</i></a></p></li>' : '-------'; ?>
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
						echo "<script>window.location='listcooperative?id=".$_SESSION['tid']."&&mid=NDE4'; </script>";
							}
							else{
							for($i=0; $i < $N; $i++)
							{
								$search_cmem = mysqli_query($link,"SELECT * FROM cooperatives WHERE id ='$id[$i]'");
								$fetch_cmem = mysqli_fetch_array($search_cmem);
								$coopid = $fetch_cmem['coopid'];
								$cooplogo = "../".$fetch_cmem['cooplogo'];

								$search_mem = mysqli_query($link,"SELECT * FROM coop_members WHERE coopid ='$coopid[$i]'");
								$fetch_mem = mysqli_fetch_array($search_mem);
								$member_image = "../".$fetch_mem['member_image'];

								unlink($member_image);
								unlink($cooplogo);
								$result = mysqli_query($link,"DELETE FROM coop_members WHERE coopid = '$coopid[$i]'");
								$result = mysqli_query($link,"DELETE FROM cooperatives WHERE id ='$id[$i]'");
								echo "<script>alert('Row Delete Successfully!!!'); </script>";
								echo "<script>window.location='listcooperative?id=".$_SESSION['tid']."&&mid=NDE4'; </script>";
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