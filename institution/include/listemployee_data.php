<div class="row">       
		    <section class="content">  
	        <div class="box box-success">
            <div class="box-body">
              <div class="table-responsive">
             <div class="box-body">
<form method="post">
<?php
if(isMobileDevice()){
    //Your content or code for mobile devices goes here
?>
    
    <?php echo ($block_employee == '1') ? "<button type='submit' class='btn btn-flat bg-".(($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color'])."' name='block'><i class='fa fa-times'></i>&nbsp;Block</button>" : ""; ?>
    <?php echo ($unblock_employee == '1') ? "<button type='submit' class='btn btn-flat bg-".(($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color'])."' name='unblock'><i class='fa fa-check'></i>&nbsp;Unblock</button>" : ""; ?>
    <?php echo ($add_employee == '1') ? "<a href='newemployee.php?id=".$_SESSION['tid']."&&mid=".base64_encode("409")."'><button type='button' class='btn btn-flat bg-".(($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color'])."'><i class='fa fa-user'></i>&nbsp;New Sub-Agent</button></a>" : ""; ?>

<?php
}
else{
    ?>
    
    <a href="dashboard.php?id=<?php echo $_SESSION['tid']; ?>&&mid=<?php echo base64_encode("401"); ?>"><button type="button" class="btn btn-flat bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>"><i class="fa fa-mail-reply-all"></i>&nbsp;Back</button> </a> 
	<?php echo ($block_employee == '1') ? "<button type='submit' class='btn btn-flat bg-".(($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color'])."' name='block'><i class='fa fa-times'></i>&nbsp;Block</button>" : ""; ?>
    <?php echo ($unblock_employee == '1') ? "<button type='submit' class='btn btn-flat bg-".(($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color'])."' name='unblock'><i class='fa fa-check'></i>&nbsp;Unblock</button>" : ""; ?>
	<?php echo ($delete_employee == '1') ? "<button type='submit' class='btn btn-flat bg-".(($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color'])."' name='delete'><i class='fa fa-times'></i>&nbsp;Delete</button>" : ''; ?>
	<?php echo ($add_employee == '1') ? "<a href='newemployee.php?id=".$_SESSION['tid']."&&mid=".base64_encode("409")."'><button type='button' class='btn btn-flat bg-".(($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color'])."'><i class='fa fa-user'></i>&nbsp;New Sub-Agent</button></a>" : ""; ?>

<?php    
}
?>
	
	
	<hr>		
			  
			 <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th><input type="checkbox" id="select_all"/></th>
				  <th>Actions</th>
                  <th>Account</th>
				  <th>Branch</th>
                  <th>Image</th>
				  <th>Name</th>
                  <th>Username</th>
				  <th>Email</th>
                  <th>Phone Number</th>
                  <?php echo ($isubagent_wallet == "Enabled") ? '<th>Wallet Balance</th>' : ''; ?>
				  <th>Transfer Balance</th>
				  <th>Date/Time</th>
                 </tr>
                </thead>
                <tbody>
<?php
$tid = $_SESSION['tid'];
$select = mysqli_query($link, "SELECT * FROM user WHERE created_by = '$institution_id' AND role != 'agent_manager' AND role != 'institution_super_admin' AND role != 'merchant_super_admin' AND bprefix != 'VEN' ORDER BY userid DESC") or die (mysqli_error($link));
if(mysqli_num_rows($select)==0)
{
echo "<div class='alert bg-".$myaltrow['alternate_color'].' == "" ? orange : '.$myaltrow['alternate_color']."'>No data found yet!.....Check back later!!</div>";
}
else{
while($row = mysqli_fetch_array($select))
{
$id = $row['userid'];
$utype = $row['utype'];
$name = $row['name'].' '.$row['lname'].' '.$row['mname'];
$image = $row['image'];
$username = $row['username'];
$email = $row['email']; 
$phone = $row['phone']; 
$branch = $row['branchid'];
//$role = $row['role'];
$wallet_balance = $row['wallet_balance'];

$search_branc = mysqli_query($link, "SELECT * FROM branches WHERE branchid = '$branch'");
$fetch_branc = mysqli_fetch_array($search_branc);
?>
                <tr>
                <td><input id="optionsCheckbox" class="checkbox" name="selector[]" type="checkbox" value="<?php echo $id; ?>"></td>
                <td><?php echo ($utype == 'Registered' && $update_employee_records == '1' ? '<a href="view_emp.php?id='.$row['id'].'&&mid='.base64_encode("409").'"><button type="button" class="btn btn-flat bg-'.(($myaltrow['theme_color'] == "") ? 'blue' : $myaltrow['theme_color']).'"><i class="fa fa-eye"></i>&nbsp;View</button></a>' : ($utype == 'Unregistered' && $update_employee_records == '1' ? '<a href="edit_unreg_user.php?id='.$_SESSION['tid'].'&&idm='.$row['id'].'&&mid=NDIz"><button type="button" class="btn btn-flat bg-'.(($myaltrow['theme_color'] == "") ? 'blue' : $myaltrow['theme_color']).'"><i class="fa fa-eye"></i>&nbsp;View</button></a>' : '<span style="color: '.(($myaltrow['alternate_color'] == "") ? 'orange' : $myaltrow['alternate_color']).';">-------</span>')); ?></td>
				<td><?php echo ($row['virtual_acctno'] == "") ? "---" : $row['virtual_acctno']; ?></td>
				<td><?php echo ($branch == '') ? 'Head Office' : $fetch_branc['bname']; ?></td>
				<td><img src="<?php echo ($image == '' || $image == 'img/') ? $fetchsys_config['file_baseurl'].'avatar.png' : $fetchsys_config['file_baseurl'].$image; ?>" width="40" height="40"></td>
				<td><?php echo $name; ?></td>
				<td><?php echo $username; ?></td>
				<td><?php echo $email; ?></td>
				<td><?php echo $phone; ?></td>
				<?php echo ($isubagent_wallet == "Enabled") ? '<td>'.$icurrency.number_format($wallet_balance,2,'.',',').'</td>' : ''; ?>
				<td><?php echo $icurrency.number_format($row['transfer_balance'],2,'.',','); ?></td>
				<td><?php echo ($row['date_time'] == "0000-00-00 00:00:00") ? "---" : $row['date_time']; ?></td>
			    </tr>
<?php } } ?>
             </tbody>
                </table>  

                    <?php
						if(isset($_POST['block'])){
						$idm = $_GET['id'];
						$id=$_POST['selector'];
						$N = count($id);
						
						if($id == ''){
						    echo "<script>alert('Account Not Selected!!!'); </script>";	
						    echo "<script>window.location='listemployee.php?id=".$_SESSION['tid']."&&mid=".base64_encode("409")."'; </script>";
						}
						else{
							for($i=0; $i < $N; $i++)
							{
								$result = mysqli_query($link,"UPDATE user SET status = 'Blocked' WHERE userid ='$id[$i]'");
								echo "<script>alert('Account Blocked Successfully!!!'); </script>";
								echo "<script>window.location='listemployee.php?id=".$_SESSION['tid']."&&mid=".base64_encode("409")."'; </script>";
							}
						}
					}
                    ?>
                    
                    <?php
						if(isset($_POST['unblock'])){
						$idm = $_GET['id'];
						$id=$_POST['selector'];
						$N = count($id);
						
						if($id == ''){
						    echo "<script>alert('Account Not Selected!!!'); </script>";	
						    echo "<script>window.location='listemployee.php?id=".$_SESSION['tid']."&&mid=".base64_encode("409")."'; </script>";
						}
						else{
							for($i=0; $i < $N; $i++)
							{
								$result = mysqli_query($link,"UPDATE user SET status = 'Approved' WHERE userid ='$id[$i]'");
								echo "<script>alert('Account Unblocked Successfully!!!'); </script>";
								echo "<script>window.location='listemployee.php?id=".$_SESSION['tid']."&&mid=".base64_encode("409")."'; </script>";
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