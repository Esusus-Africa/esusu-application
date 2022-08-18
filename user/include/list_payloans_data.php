<div class="row">
		    <section class="content">  
	        <div class="box box-success">
            <div class="box-body">
              <div class="table-responsive">
             <div class="box-body">
<form method="post">
			 <a href="dashboard.php?id=<?php echo $_SESSION['tid']; ?>&&acn=<?php echo $_GET['acn']; ?>&&mid=<?php echo base64_encode("401"); ?>"><button type="button" class="btn bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>"><i class="fa fa-mail-reply-all"></i>&nbsp;Back</button> </a> 
	 <button type="submit" class="btn bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>" name="delete"><i class="fa fa-times"></i>&nbsp;Delete</button>	
	<hr>		
			  
			 <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th><input type="checkbox" id="select_all"/></th>
                  <th>Reference ID</th>
				  <th>Loan Balance</th>
				  <th>Amount Payed</th>
                  <th>Date</th>
				  <th>Cashier</th>
                 </tr>
                </thead>
                <tbody>
<?php
$tid = $_SESSION['tid'];
$acn = $_GET['acn'];
$select = mysqli_query($link, "SELECT * FROM payments WHERE account_no = '$acn'") or die (mysqli_error($link));
if(mysqli_num_rows($select)==0)
{
echo "<div class='alert bg-blue'>No data found yet!.....Check back later!!</div>";
}
else{
while($row = mysqli_fetch_array($select))
{
$id = $row['id'];
$refid = $row['refid'];
$tid = $row['tid'];
$remarks = $row['remarks'];
$customer = $row['customer'];
$loan_bal = $row['loan_bal'];
$amount_payed = $row['amount_to_pay'];

$getin = mysqli_query($link, "SELECT name FROM user WHERE id = '$tid'") or die (mysqli_error($link));
$have = mysqli_fetch_array($getin);
$nameit = $have['name'];

$select1 = mysqli_query($link, "SELECT * FROM systemset") or die (mysqli_error($link));
$row1 = mysqli_fetch_array($select1);
$currency = $row1['currency']; 
?>    
                <tr>
                <td><?php echo ($remarks == 'paid') ? '<input name="selector[]" type="checkbox" value='.$id.' disabled>' : '<input id="optionsCheckbox" class="checkbox" name="selector[]" type="checkbox" value='.$id.'>'; ?></td>
                <td><?php echo $refid; ?></td>
				<td><?php echo $currency.number_format($loan_bal,2,".",","); ?></td>
				<td><?php echo $currency.number_format($amount_payed,2,".",","); ?></td>
				<td><?php echo $row['pay_date']; ?></td>
				<td><?php echo ($tid == 'Self') ? 'Self' : $nameit; ?></td>
				<td><?php echo ($remarks == 'paid') ? '<span class="label label-success">Paid</span>' : '<span class="label label-danger">Pending...</span>'; ?></td>	    
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
							}
							else{
							for($i=0; $i < $N; $i++)
							{
								$result = mysqli_query($link,"DELETE FROM payments WHERE id ='$id[$i]'");
								echo "<script>alert('Row Delete Successfully!!!'); </script>";
								echo "<script>window.location='list_payloans.php?id=".$_SESSION['tid']."&&acn=".$_GET['acn']."&&mid=".base64_encode("404")."'; </script>";
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