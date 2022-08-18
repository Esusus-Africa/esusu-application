<div class="row">
		
	       
		    <section class="content">  
	        <div class="box box-success">
            <div class="box-body">
              <div class="table-responsive">
             <div class="box-body">
<form method="post">
			 <a href="dashboard.php?id=<?php echo $_SESSION['tid']; ?>&&mid=<?php echo base64_encode("401"); ?>"><button type="button" class="btn btn-flat btn-warning"><i class="fa fa-mail-reply-all"></i>&nbsp;Back</button> </a> 
	 <button type="submit" class="btn btn-flat btn-danger" name="delete"><i class="fa fa-times"></i>&nbsp;Multiple Delete</button>
	<a href="addcustomer.php?id=<?php echo $_SESSION['tid']; ?>&&mid=<?php echo base64_encode("403"); ?>"><button type="button" class="btn btn-flat btn-success"><i class="fa fa-plus"></i>&nbsp;Add Customer</button></a>
	<a href="sendsms_customer.php?id=<?php echo $_SESSION['tid']; ?>&&mid=<?php echo base64_encode("403"); ?>"><button type="button" class="btn btn-flat btn-info"><i class="fa fa-envelope"></i>&nbsp;Send SMS</button></a>
	<a href="sendemail_customer.php?id=<?php echo $_SESSION['tid']; ?>&&mid=<?php echo base64_encode("403"); ?>"><button type="button" class="btn btn-flat btn-success"><i class="fa fa-envelope"></i>&nbsp;Send Email</button></a>
	
	<hr>				  
			 <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th><input type="checkbox" id="select_all"/></th>
				  <th>Account ID</th>
                  <th>Name</th>
                  <th>Email</th>
				  <th>ID</th>
                  <th>Balance</th>
                  <th>Actions</th>
                 </tr>
                </thead>
                <tbody>
<?php
$select = mysqli_query($link, "SELECT * FROM borrowers WHERE community_role = 'Borrower' ORDER BY id DESC") or die (mysqli_error($link));
if(mysqli_num_rows($select)==0)
{
echo "<div class='alert alert-info'>No data found yet!.....Check back later!!</div>";
}
else{
while($row = mysqli_fetch_array($select))
{
$id = $row['id'];
$acctno = $row['account'];
$lname = $row['lname'];
$fname = $row['fname'];
$email = $row['email'];
$bal = $row['balance'];
//$image = $row['image'];
?>    
                <tr>
				<td><input id="optionsCheckbox" class="checkbox"  name="selector[]" type="checkbox" value="<?php echo $id; ?>"></td>
                <td width="150"><img class="img-circle" src="../<?php echo $row ['image'];?>" width="30" height="30" align="center"> <?php echo $acctno; ?></td>
				<td><?php echo $fname.'&nbsp;'.$lname; ?></td>
				<td><?php echo $email; ?></td>
				<td><?php echo $id; ?></td>
<?php
$query = mysqli_query($link, "SELECT * FROM systemset");
$get_query = mysqli_fetch_array($query);
?>
				<td><?php echo $get_query['currency'].number_format($bal,2,'.',','); ?></td>
				<td align="center">
				<div class="btn-group">
                      <div class="btn-group">
                        <button type="button" class="btn btn-info btn-flat dropdown-toggle" data-toggle="dropdown">
                          <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu">
                          <li><p><a href="add_to_borrower_list.php?id=<?php echo $id; ?>&&mid=<?php echo base64_encode("410"); ?>" class="btn btn-default btn-flat"><i class="fa fa-save">&nbsp;Update Information</i></a></p></li>
                          <li><p><a href="invoice-print.php?id=<?php echo $_SESSION['tid']; ?>&&mid=<?php echo base64_encode('410'); ?>&&uid=<?php echo $acctno; ?>" class="btn btn-default btn-flat" target="_blank"><i class="fa fa-search">&nbsp;View Account Info.</i></a></p></li>
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
						echo "<script>window.location='listborrowers.php?id=".$_SESSION['tid']."'; </script>";
							}
							else{
							for($i=0; $i < $N; $i++)
							{
								$result = mysqli_query($link,"DELETE FROM borrowers WHERE id ='$id[$i]'");
								echo "<script>alert('Row Delete Successfully!!!'); </script>";
								echo "<script>window.location='listborrowers.php?id=".$_SESSION['tid']."'; </script>";
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