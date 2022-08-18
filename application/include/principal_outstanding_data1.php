<div class="row">       
		    <section class="content">  
	        <div class="box box-success">
            <div class="box-body">
              <div class="table-responsive">
             <div class="box-body">
<form method="post">
			 <a href="dashboard.php?id=<?php echo $_SESSION['tid']; ?>&&mid=<?php echo base64_encode("401"); ?>"><button type="button" class="btn btn-flat bg-orange"><i class="fa fa-mail-reply-all"></i>&nbsp;Back</button> </a> 

<?php echo ($add_loan == '1') ? '<a href="newloans.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("405").'"><button type="button" class="btn btn-flat bg-blue"><i class="fa fa-plus"></i>&nbsp;Add Loans</button></a>' : ''; ?>

<?php
//$baccount = $_SESSION['acctno'];
$date_now = date("Y-m-d");
$select = mysqli_query($link, "SELECT pay_date FROM loan_info WHERE p_status = 'UNPAID' AND pay_date <= '$date_now' AND branchid = '$session_id'") or die ("Error: " . mysqli_error($link));
$num = mysqli_num_rows($select);	
?>
	<?php echo ($view_due_loans == '1') ? '<button type="button" class="btn btn-flat bg-orange"><i class="fa fa-times"></i>&nbsp;Overdue:&nbsp;'.number_format($num,0,'.',',').'</button>' : ''; ?>
	
	<hr>		
			  
			 <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th><input type="checkbox" id="select_all"/></th>
                  <th>Loan ID</th>
				          <th>Account ID</th>
                  <th>Amount Paid</th>
                  <th>Balance</th>
                  <th>Last Payment</th>
                  <th>Status</th>
                 </tr>
                </thead>
                <tbody> 
<?php
$date_now = date("Y-m-d");
$select = mysqli_query($link, "SELECT * FROM pay_schedule WHERE status = 'UNPAID' AND branchid = '$session_id'") or die (mysqli_error($link));
if(mysqli_num_rows($select)==0)
{
echo "<div class='alert bg-blue'>No data found yet!.....Check back later!!</div>";
}
else{
while($row = mysqli_fetch_array($select))
{
$id = $row['id'];

$systemset = mysqli_query($link, "SELECT * FROM systemset");
$rowsys = mysqli_fetch_array($systemset);
?> 
                <tr>
				<td><input id="optionsCheckbox" class="checkbox" name="selector[]" type="checkbox" value="<?php echo $row['id']; ?>"></td>
                <td><?php echo $row['lid']; ?></td>
				<td><?php echo $row['tid']; ?></td>
                <td><?php echo $rowsys['currency'].number_format($row['payment'],2,'.',','); ?></td>
				<td><?php echo $rowsys['currency'].number_format($row['balance'],2,'.',','); ?></td>
				<td><?php echo $row['schedule']; ?></td>
                <td><?php echo $row['status']; ?></td>	    
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