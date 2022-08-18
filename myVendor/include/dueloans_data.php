<div class="row">       
		    <section class="content">  
	        <div class="box box-success">
            <div class="box-body">
              <div class="table-responsive">
             <div class="box-body">
<form method="post">
			 <a href="dashboard.php?id=<?php echo $_SESSION['tid']; ?>&&mid=<?php echo base64_encode("401"); ?>"><button type="button" class="btn bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>"><i class="fa fa-mail-reply-all"></i>&nbsp;Back</button> </a> 
<?php
$date_now = date("Y-m-d");
$select = mysqli_query($link, "SELECT * FROM pay_schedule WHERE status = 'UNPAID' AND schedule <= '$date_now' AND vendorid = '$vendorid'") or die ("Error: " . mysqli_error($link));
$num = mysqli_num_rows($select);	
?>
	<?php echo ($view_due_loans == 1) ? '<button type="button" class="btn btn-flat bg-'.$myaltrow['alternate_color'].' == "" ? orange : '.$myaltrow['alternate_color'].'"><i class="fa fa-times"></i>&nbsp;Overdue:&nbsp;'.number_format($num,0,'.',',').'</button>' : ''; ?>
	
	<hr>		
			  
			 <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th><input type="checkbox" id="select_all"/></th>
                  <th>Loan ID</th>
				  <th>Account ID</th>
                  <th>Interest Rate</th>
                  <th>Amount to Pay + Interest</th>
                  <th>Balance</th>
                  <th>Approve By</th>
                  <th>Repayment Deadline</th>
                 </tr>
                </thead>
                <tbody> 
<?php
$date_now = date("Y-m-d");
$select_pso = mysqli_query($link, "SELECT * FROM loan_info WHERE vendorid = '$vendorid' AND status = 'Approved'");
$fetch_pso = mysqli_fetch_array($select_pso);
$mylid = $fetch_pso['lid'];
        
$select = mysqli_query($link, "SELECT * FROM pay_schedule WHERE vendorid = '$vendorid' AND lid = '$mylid' AND status = 'UNPAID' AND schedule <= '$date_now'") or die (mysqli_error($link));
if(mysqli_num_rows($select)==0)
{
echo "<div class='alert bg-".$myaltrow['theme_color'].' == "" ? blue : '.$myaltrow['theme_color']."'>No data found yet!.....Check back later!!</div>";
}
else{
    while($row = mysqli_fetch_array($select))
    {
        $lid = $row['lid'];
        $select_ps = mysqli_query($link, "SELECT * FROM loan_info WHERE branchid = '$merchantid' AND lid = '$lid' AND status = 'Approved'");
        $fetch_ps = mysqli_fetch_array($select_ps);
        $id = $fetch_ps['id'];
        $borrower = $fetch_ps['borrower'];
        $status = $fetch_ps['status'];
        $upstatus = $fetch_ps['upstatus'];
        
        $selectin = mysqli_query($link, "SELECT * FROM borrowers WHERE id = '$borrower'") or die (mysqli_error($link));
        $geth = mysqli_fetch_array($selectin);
        $name = $geth['lname'].' '.$geth['fname'];
        $myphone = $geth['phone'];
        
        $systemset = mysqli_query($link, "SELECT * FROM systemset");
        $rowsys = mysqli_fetch_array($systemset);
    ?> 
        		<tr>
				<td><input id="optionsCheckbox" class="checkbox" name="selector[]" type="checkbox" value="<?php echo $row['id']; ?>"></td>
                <td><?php echo $row['lid']; ?></td>
				<td><?php echo $fetch_ps['baccount']; ?></td>
                <td><?php echo $fetch_ps['interest_rate']; ?>% of <?php echo $mcurrency.number_format($fetch_ps['amount'],2,'.',','); ?></td>
				<td><b><?php echo $mcurrency.number_format($row['payment'],2,'.',','); ?></b></td>
				<td><?php echo $mcurrency.number_format($row['balance'],2,'.',','); ?></td>
			    <td><?php echo ($fetch_ps['teller'] == "") ? '<span class="label bg-orange">Waiting for Review</span>' : $fetch_ps['teller']; ?></td>
				<td><b><?php echo $row['schedule']; ?></b></td>
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