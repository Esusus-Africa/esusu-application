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

    <a href="dashboard.php?id=<?php echo $_SESSION['tid']; ?>&&mid=<?php echo base64_encode("401"); ?>"><button type="button" class="btn btn-flat bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>"><i class="fa fa-mail-reply-all"></i>&nbsp;Back</button> </a> 

<?php
}
else{
    ?>
	<a href="dashboard.php?id=<?php echo $_SESSION['tid']; ?>&&mid=<?php echo base64_encode("401"); ?>"><button type="button" class="btn btn-flat bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>"><i class="fa fa-mail-reply-all"></i>&nbsp;Back</button> </a> 
<?php    
}
?>	
<hr>
<div class="box box-info">
            <div class="box-body">
            <div class="alert bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>" align="center" class="style2" style="color: #FFFFFF">TOTAL NUMBER OF LOAN REPAYMENT RECEIVED:&nbsp;
			<?php 
			$call3 = mysqli_query($link, "SELECT * FROM payments WHERE vendorid = '$vendorid'");
			$num3 = mysqli_num_rows($call3);
			?>
			<?php echo $num3; ?> 
			
			</div>
							
			</div>
			</div>
			  
			 <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th><input type="checkbox" id="select_all"/></th>
				  <th>Reference ID</th>
                  <th>Loan ID</th>
                  <th>Account ID</th>
				  <th>Customer Name</th>
				  <th>Loan Balance</th>
				  <th>Amount Payed</th>
                  <th>Date</th>
				  <th>Status</th>
                 </tr>
                </thead>
                <tbody>
<?php
$tid = $_SESSION['tid'];
$select = mysqli_query($link, "SELECT * FROM payments WHERE vendorid = '$vendorid' ORDER BY id DESC") or die (mysqli_error($link));
if(mysqli_num_rows($select)==0)
{
echo "<div class='alert bg-".$myaltrow['theme_color'].' == "" ? blue : '.$myaltrow['theme_color']."'>No data found yet!.....Check back later!!</div>";
}
else{
while($row = mysqli_fetch_array($select))
{
$id = $row['id'];
$refid = $row['refid'];
$lid = $row['lid'];
$tid = $row['tid'];
$account_no = $row['account_no'];
$remarks = $row['remarks'];
$customer = $row['customer'];
$loan_bal = $row['loan_bal'];
$amount_payed = $row['amount_to_pay'];

$select1 = mysqli_query($link, "SELECT * FROM systemset") or die (mysqli_error($link));
$row1 = mysqli_fetch_array($select1);
$currency = $row1['currency']; 
?>    
                <tr>
                <td><input id="optionsCheckbox" class="checkbox" name="selector[]" type="checkbox" value="<?php echo $id; ?>"></td>
                <td><?php echo $refid; ?></td>
				<td><?php echo $lid; ?></td>
				<td><?php echo $account_no; ?></td>
				<td><?php echo $customer; ?></td>
				<td><?php echo $currency.number_format($loan_bal,2,".",","); ?></td>
				<td><?php echo $currency.number_format($amount_payed,2,".",","); ?></td>
				<td><?php echo $row['pay_date']; ?></td>
				<td><?php echo ($remarks == 'paid') ? '<span class="label bg-blue">Paid</span>' : '<span class="label bg-orange">Pending...</span>'; ?></td>    
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
						echo "<script>window.location='listpayment.php?id=".$_SESSION['tid']."&&mid=".base64_encode("408")."'; </script>";
							}
							else{
							for($i=0; $i < $N; $i++)
							{
							    $search = mysqli_query($link, "SELECT * FROM pay_schedule WHERE pid ='$id[$i]'");
							    $fetch_search = mysqli_fetch_array($search);
							    $lid = $fetch_search['lid'];
							    $paid_amt = $fetch_search['payment'];
							    
							    $search_lns = mysqli_query($link, "SELECT * FROM loan_info WHERE lid = '$lid'");
							    $fetch_lns = mysqli_fetch_array($search_lns);
							    $lbal = $fetch_lns['balance'];
							    $reversed_amt = $lbal + $paid_amt;
							    
							    $result = mysqli_query($link, "UPDATE loan_info SET balance = '$reversed_amt' WHERE lid = '$lid'");
							    $result = mysqli_query($link, "DELECT FROM pay_schedule WHERE pid ='$id[$i]'");
								$result = mysqli_query($link,"DELETE FROM payments WHERE id ='$id[$i]'");
								echo "<script>alert('Row Delete Successfully!!!'); </script>";
								echo "<script>window.location='listpayment.php?id=".$_SESSION['tid']."&&mid=".base64_encode("408")."'; </script>";
							}
							}
							}
?>
