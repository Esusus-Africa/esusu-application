<div class="row">       
		    <section class="content">  
	        <div class="box box-success">
            <div class="box-body">
              <div class="table-responsive">
             <div class="box-body">
<form method="post">
			 <a href="dashboard.php?id=<?php echo $_SESSION['tid']; ?>&&mid=<?php echo base64_encode("401"); ?>"><button type="button" class="btn bg-orange"><i class="fa fa-mail-reply-all"></i>&nbsp;Back</button> </a> 

<?php echo ($add_loan == '1') ? '<a href="newloans.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("405").'"><button type="button" class="btn btn-flat bg-blue"><i class="fa fa-plus"></i>&nbsp;Add Loans</button></a>' : ''; ?>
	
	<hr>		
			  
			 <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th><input type="checkbox" id="select_all"/></th>
                  <th>Loan ID</th>
				  <th>Account ID</th>
                  <th>Interest Rate</th>
                  <th>Amount to Pay + Interest</th>
                  <th>Approve By</th>
                  <th>Repayment Deadline</th>
                  <th>Approval Status</th>
                 </tr>
                </thead>
                <tbody> 
<?php
$date_now = date("Y-m-d");
$select = mysqli_query($link, "SELECT * FROM loan_info WHERE pay_date <= '$date_now' AND branchid = '$session_id'") or die (mysqli_error($link));
if(mysqli_num_rows($select)==0)
{
echo "<div class='alert bg-blue'>No data found yet!.....Check back later!!</div>";
}
else{
while($row = mysqli_fetch_array($select))
{
$id = $row['id'];
$borrower = $row['borrower'];
$status = $row['status'];
$upstatus = $row['upstatus'];
$selectin = mysqli_query($link, "SELECT fname, lname FROM borrowers WHERE id = '$borrower'") or die (mysqli_error($link));
$geth = mysqli_fetch_array($selectin);
$name = $geth['fname'];

$systemset = mysqli_query($link, "SELECT * FROM systemset");
$rowsys = mysqli_fetch_array($systemset);
?> 
<?php
if($upstatus == "Pending")
{
?>  
                <tr>
				<td><input id="optionsCheckbox" class="checkbox" name="selector[]" type="checkbox" value="<?php echo $row['id']; ?>"></td>
                <td><?php echo $row['lid']; ?></td>
				<td><?php echo $row['baccount']; ?></td>
                <td><?php echo $row['interest_rate']; ?>% of <?php echo $rowsys['currency'].number_format($row['amount'],2,'.',','); ?></td>
				<td><?php echo $rowsys['currency'].number_format($row['amount_topay'],2,'.',','); ?></td>
			   <td><?php echo ($row['teller'] == "") ? '<span class="label bg-orange">Waiting for Review</span>' : $row['teller']; ?></td>
				<td><?php echo $row['date_release']; ?></td>
                <td>
				 <span class="label bg-<?php if($status =='Approved')echo 'blue'; elseif($status =='Disapproved')echo 'orange'; else echo 'orange';?>"><?php echo $status; ?></span>
				</td>	    
			    </tr>
<?php
}
else{
?>
				<tr>
				<td><input id="optionsCheckbox" class="checkbox" name="selector[]" type="checkbox" value="<?php echo $row['id']; ?>"></td>
                <td><?php echo $row['lid']; ?></td>
				<td><?php echo $row['baccount']; ?></td>
                <td><?php echo $row['interest_rate']; ?>% of <?php echo $rowsys['currency'].number_format($row['amount'],2,'.',','); ?></td>
				<td><?php echo $rowsys['currency'].number_format($row['amount_topay'],2,'.',','); ?></td>
			    <td><?php echo ($row['teller'] == "") ? '<span class="label bg-orange">Waiting for Review</span>' : $row['teller']; ?></td>
				<td><?php echo $row['date_release']; ?></td>
                <td>
				<span class="label bg-<?php if($status =='Approved')echo 'blue'; elseif($status =='Disapproved')echo 'orange'; else echo 'orange';?>"><?php echo $status; ?></span>
				</td>   
			    </tr>
<?php } } } ?>
             </tbody>
                </table>  

</form>				

              </div>


	
</div>	
</div>
</div>	
</div>