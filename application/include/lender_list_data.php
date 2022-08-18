<div class="row">
		
	       
		    <section class="content">  
	        <div class="box box-success">
            <div class="box-body">
              <div class="table-responsive">
             <div class="box-body">
<form method="post">
	<a href="dashboard.php?id=<?php echo $_SESSION['tid']; ?>&&mid=<?php echo base64_encode("401"); ?>"><button type="button" class="btn bg-orange"><i class="fa fa-mail-reply-all"></i>&nbsp;Back</button> </a> 
	 <?php echo ($delete_customer == '1') ? '<button type="submit" class="btn btn-flat bg-orange" name="delete"><i class="fa fa-times"></i>&nbsp;Multiple Delete</button>' : ''; ?>
	<?php echo ($add_customer == '1') ? '<a href="addcustomer.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("750").'"><button type="button" class="btn btn-flat bg-blue"><i class="fa fa-plus"></i>&nbsp;Add Customer/Lender</button></a>' : ''; ?>
	
	<hr>				  
			 <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th><input type="checkbox" id="select_all"/></th>
                  <th>S/No.</th>
                  <th>Branch</th>
                  <th>Staff Name</th>
				  <th>Account ID</th>
                  <th>Name</th>
                  <th>Phone</th>
                  <th>Date/Time</th>
                  <th>Wallet Balance</th>
				  <th>Status</th>
                  <th>Actions</th>
                 </tr>
                </thead>
                <tbody>
<?php
$select = mysqli_query($link, "SELECT * FROM borrowers WHERE community_role = 'Lender' ORDER BY id DESC") or die (mysqli_error($link));
if(mysqli_num_rows($select)==0)
{
echo "<div class='alert bg-orange'>No data found yet!.....Check back later!!</div>";
}
else{
while($row = mysqli_fetch_array($select))
{
$id = $row['id'];
$snum = $row['snum'];
$acctno = $row['account'];
$lname = $row['lname'];
$fname = $row['fname'];
$email = $row['email'];
$phone = $row['phone'];
$date_time = $row['date_time'];
$posts = $row['posts'];
$acct_status = $row['acct_status'];
$bal = $row['wallet_balance'];
//$image = $row['image'];
$mybranch = $row['branchid'];
$mysbranch = $row['sbranchid'];
$myofficer = $row['lofficer'];
//$image = $row['image'];

$utc_date = DateTime::createFromFormat('Y-m-d G:i:s',$date_time,new DateTimeZone('America/New_York'));
$acst_date = clone $utc_date; // we don't want PHP's default pass object by reference here
$acst_date->setTimeZone(new DateTimeZone('GMT+1'));
$correctdate = $acst_date->format('Y-m-d g:i A');

$search_branch = mysqli_query($link, "SELECT * FROM branches WHERE branchid = '$mysbranch'");
$fetch_branch = mysqli_fetch_array($search_branch);

$search_staff = mysqli_query($link, "SELECT * FROM user WHERE id = '$myofficer'");
$fetch_staff = mysqli_fetch_array($search_staff);
?>    
                <tr>
				<td><input id="optionsCheckbox" class="checkbox"  name="selector[]" type="checkbox" value="<?php echo $id; ?>"></td>
				<td><?php echo ($snum == "") ? "null" : $snum; ?></td>
				<td><?php echo ($mybranch != "" && $mysbranch == "") ? '<b>Head Office-'.$mybranch.'</b>' : $fetch_branch['bname']; ?></td>
				<td><?php echo ($myofficer == "") ? 'NIL' : $fetch_staff['name']; ?></td>
                <td width="150"><img class="img-circle" src="../<?php echo $row ['image'];?>" width="30" height="30" align="center"> <?php echo $acctno; ?></td>
				<td><?php echo $fname.'&nbsp;'.$lname; ?></td>
				<td><?php echo $phone; ?></td>
				<td><?php echo $correctdate; ?></td>
				<td><?php echo $row['currency'].number_format($bal,2,'.',','); ?></td>
				<td><?php echo ($acct_status == "Activated") ? "<span class='label bg-blue'>Active</span>" : "<span class='label bg-orange'>Not-Active</span>"; ?></td>
				<td align="center">
				<div class="btn-group">
                      <div class="btn-group">
                        <button type="button" class="btn bg-blue btn-flat dropdown-toggle" data-toggle="dropdown">
                          <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu">
                          <?php echo ($update_customers_info == '1') ? '<li><p><a href="add_to_borrower_list.php?id='.$id.'&&mid='.base64_encode("750").'" class="btn btn-default btn-flat" target="_blank"><i class="fa fa-save">&nbsp;Update Info.</i></a></p></li>' : ''; ?>
                          <?php echo ($view_account_info == '1') ? '<li><p><a href="invoice-print.php?id='.$_SESSION['tid'].'&&mid='.base64_encode('750').'&&uid='.$acctno.'" class="btn btn-default btn-flat" target="_blank"><i class="fa fa-search">&nbsp;Account Info.</i></a></p></li>' : ''; ?>
                          <?php echo ($view_loan_history == '1') ? '<li><p><a href="lend-history.php?id='.$_SESSION['tid'].'&&mid='.base64_encode('750').'&&uid='.$acctno.'" class="btn btn-default btn-flat" target="_blank"><i class="fa fa-book">&nbsp;Lending History.</i></a></p></li>' : ''; ?>
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
						echo "<script>window.location='lender_list.php?id=".$_SESSION['tid']."&&mid=NzUw'; </script>";
							}
							else{
							for($i=0; $i < $N; $i++)
							{
								$result = mysqli_query($link,"DELETE FROM borrowers WHERE id ='$id[$i]'");
								echo "<script>alert('Row Delete Successfully!!!'); </script>";
								echo "<script>window.location='lender_list.php?id=".$_SESSION['tid']."&&mid=NzUw'; </script>";
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