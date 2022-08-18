<div class="row">
		
	       
		     <section class="content">  
	        <div class="box box-success">
            <div class="box-body">
              <div class="table-responsive">
             <div class="box-body">
<form method="post">
			 <a href="dashboard.php?id=<?php echo $_SESSION['tid']; ?>&&mid=<?php echo base64_encode("401"); ?>"><button type="button" class="btn btn-flat bg-red"><i class="fa fa-mail-reply-all"></i>&nbsp;Back</button> </a> 
	 <button type="submit" class="btn btn-flat bg-red" name="delete"><i class="fa fa-times"></i>&nbsp;Delete</button>	
	<a href="excelregtransaction.php" target="_blank" class="btn bg-blue btn-flat"><i class="fa fa-download"></i>&nbsp;Export Excel</a>
	
	<hr>		
			  
			 <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th><input type="checkbox" id="select_all"/></th>
				  <th>Reference ID</th>
                  <th>Account ID</th>
				  <th>Customer Name</th>
				  <th>Amount Paid</th>
				  <th>Status</th>
                  <th>Date</th>
                 </tr>
                </thead>
                <tbody>
<?php
$tid = $_SESSION['tid'];
$select = mysqli_query($link, "SELECT * FROM reg_transaction") or die (mysqli_error($link));
if(mysqli_num_rows($select)==0)
{
echo "<div class='alert bg-blue'>No data found yet!.....Check back later!!</div>";
}
else{
while($row = mysqli_fetch_array($select))
{
$id = $row['id'];
$refid = $row['refid'];
$account_no = $row['acn'];
$amount_payed = $row['amount_paid'];
$status = $row['status'];
$date_time = $row['date_time'];

$getin = mysqli_query($link, "SELECT fname, lname FROM borrowers WHERE account = '$account_no'") or die (mysqli_error($link));
$have = mysqli_fetch_array($getin);
$fullname = $have['lname'].' '.$have['fname'];

$select1 = mysqli_query($link, "SELECT * FROM systemset") or die (mysqli_error($link));
$row1 = mysqli_fetch_array($select1);
$currency = $row1['currency']; 
?>    
                <tr>
                <td><input id="optionsCheckbox" class="checkbox" name="selector[]" type="checkbox" value="<?php echo $id; ?>"></td>
                <td><?php echo $refid; ?></td>
				<td><?php echo $account_no; ?></td>
				<td><?php echo $fullname; ?></td>
				<td><?php echo $currency.number_format($amount_payed,2,".",","); ?></td>
				<td><?php echo ($status == 'paid') ? '<span class="label label-success">Paid</span>' : '<span class="label label-danger">Pending...</span>'; ?></td>  
				<td><?php echo $date_time; ?> </td>  
			    </tr>
<?php } } ?>
             </tbody>
                </table>  
</form>

					</div>
</div>	
</div>			
</div>	
</div>
			
<?php
						if(isset($_POST['delete'])){
						$idm = $_GET['id'];
							$id=$_POST['selector'];
							$N = count($id);
						if($id == ''){
						echo "<script>alert('Row Not Selected!!!'); </script>";	
						echo "<script>window.location='reg_transaction.php?id=".$_SESSION['tid']."&&mid=".base64_encode("417")."'; </script>";
							}
							else{
							for($i=0; $i < $N; $i++)
							{
								$result = mysqli_query($link,"DELETE FROM reg_transaction WHERE id ='$id[$i]'");
								echo "<script>alert('Transaction Deleted Successfully!!!'); </script>";
								echo "<script>window.location='reg_transaction.php?id=".$_SESSION['tid']."&&mid=".base64_encode("417")."'; </script>";
							}
							}
							}
?>