<div class="row">       
		    <section class="content">  
	        <div class="box box-success">
            <div class="box-body">
              <div class="table-responsive">
             <div class="box-body">
<form method="post">
			 <a href="dashboard.php?id=<?php echo $_SESSION['tid']; ?>&&mid=<?php echo base64_encode("401"); ?>"><button type="button" class="btn btn-flat bg-orange"><i class="fa fa-mail-reply-all"></i>&nbsp;Back</button> </a> 
	 
			 <?php echo ($delete_employee == '1') ? '<button type="submit" class="btn btn-flat bg-orange" name="delete"><i class="fa fa-times"></i>&nbsp;Delete</button>' : ''; ?>
	
			 <?php echo ($add_backend_employee == '1') ? '<a href="newemployee.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("409").'"><button type="button" class="btn btn-flat bg-blue"><i class="fa fa-user"></i>&nbsp;New Employee</button></a>' : ''; ?>
	
			 <hr>
			  
			 <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th><input type="checkbox" id="select_all"/></th>
				  <th>UserType</th>
				  <th>Branch</th>
				  <th>Name</th>
                  <th>Username</th>
				  <th>Email</th>
                  <th>Phone Number</th>
                  <th>Wallet Balance</th>
                  <th>Actions</th>
                 </tr>
                </thead>
                <tbody>
<?php
$tid = $_SESSION['tid'];
$select = mysqli_query($link, "SELECT * FROM user WHERE created_by = '' AND role != 'super_admin' AND role != 'aggregator' ORDER BY userid DESC") or die (mysqli_error($link));
if(mysqli_num_rows($select)==0)
{
echo "<div class='alert bg-orange'>No data found yet!.....Check back later!!</div>";
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
$wallet_balance = $row['wallet_balance'];

$search_branc = mysqli_query($link, "SELECT * FROM branches WHERE branchid = '$branch'");
$fetch_branc = mysqli_fetch_array($search_branc);
?>    
                <tr>
                <td><input id="optionsCheckbox" class="checkbox" name="selector[]" type="checkbox" value="<?php echo $id; ?>"></td>
				<td><?php echo ($utype == 'Registered') ? '<span class="label bg-blue">Registered</span>' : '<span class="label bg-orange">Unregistered</span>'; ?></td>
				<td><?php echo ($branch == '') ? '<b>Head Office</b>' : '<b>'.$fetch_branc['bname'].'</b>'; ?></td>
				<td><?php echo $name; ?></td>
				<td><?php echo $username; ?></td>				
				<td><?php echo $email; ?></td>
				<td><?php echo $phone; ?></td>
				<td><b><?php echo $wallet_balance; ?></b></td>
				<td><?php echo ($utype == 'Registered' && $update_backend_employee_records == '1' ? '<a href="view_emp.php?id='.$row['id'].'&&mid='.base64_encode("409").'"><button type="button" class="btn btn-flat bg-blue"><i class="fa fa-eye"></i>&nbsp;View</button></a>' : ($utype == 'Unregistered' && $update_backend_employee_records == '1' ? '<a href="edit_unreg_user.php?id='.$_SESSION['tid'].'&&idm='.$row['id'].'&&mid=NDIz"><button type="button" class="btn btn-flat bg-blue"><i class="fa fa-eye"></i>&nbsp;View</button></a>' : '<span style="color: orange;">-------</span>')); ?></td>
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
						echo "<script>window.location='listemployee.php?id=".$_SESSION['tid']."&&mid=".base64_encode("409")."'; </script>";
							}
							else{
							for($i=0; $i < $N; $i++)
							{
								$result = mysqli_query($link,"DELETE FROM user WHERE userid ='$id[$i]'");
								echo "<script>alert('Row Delete Successfully!!!'); </script>";
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