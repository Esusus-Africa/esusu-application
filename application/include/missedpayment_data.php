<div class="row">
		
	       
		    <section class="content">  
	        <div class="box box-success">
            <div class="box-body">
              <div class="table-responsive">
             <div class="box-body">
<form method="post">
	<a href="dashboard.php?id=<?php echo $_SESSION['tid']; ?>&&mid=<?php echo base64_encode("401"); ?>"><button type="button" class="btn bg-red bg-orange"><i class="fa fa-mail-reply-all"></i>&nbsp;Back</button> </a> 
<?php
$date_now = date("Y-m-d");
$select = mysqli_query($link, "SELECT * FROM pay_schedule WHERE status = 'UNPAID' AND schedule <= '$date_now'") or die ("Error: " . mysqli_error($link));
$num = mysqli_num_rows($select);	
?>
	<?php echo ($view_due_loans == 1) ? '<button type="button" class="btn btn-flat bg-orange"><i class="fa fa-times"></i>&nbsp;Overdue:&nbsp;'.number_format($num,0,'.',',').'</button>' : ''; ?>
		<hr>		
			  
			 <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th><input type="checkbox" id="select_all"/></th>
                  <th>Loan ID</th>
				          <th>Customer Account ID</th>
                  <th>Due Date</th>
                  <th>Amount to Pay</th>
                  <th>Balance</th>
                  <th>Action</th>
                 </tr>
                </thead>
                <tbody> 
<?php
$date_now = date("Y-m-d");
$select = mysqli_query($link, "SELECT * FROM pay_schedule WHERE status = 'UNPAID' AND schedule <= '$date_now'") or die (mysqli_error($link));
if(mysqli_num_rows($select)==0)
{
echo "<div class='alert bg-blue'>No data found yet!.....Check back later!!</div>";
}
else{
while($row = mysqli_fetch_array($select))
{
$id = $row['id'];
$status = $row['status'];
$lid = $row['lid'];

$systemset = mysqli_query($link, "SELECT * FROM systemset");
$rowsys = mysqli_fetch_array($systemset);

$search_payment = mysqli_query($link, "SELECT * FROM authorized_card WHERE lid = '$lid'") or die ("Error:" . mysqli_error($link));
$reg_pay_query = mysqli_fetch_object($search_payment);
?>  
                <tr>
        				<td><input id="optionsCheckbox" class="checkbox" name="selector[]" type="checkbox" value="<?php echo $row['id']; ?>"></td>
                        <td><?php echo $row['lid']; ?></td>
        				<td><?php echo $row['tid']; ?></td>
        				<td><?php echo $row['schedule']; ?></td>
                        <td><?php echo $rowsys['currency'].number_format($row['payment'],2,'.',','); ?></td>
        				<td><?php echo $rowsys['currency'].number_format($row['balance'],2,'.',','); ?></td>
        				<td>
					       <div class="btn-group">
                  <button type="button" class="btn bg-blue">Request Payment</button>
                  <button type="button" class="btn bg-blue dropdown-toggle" data-toggle="dropdown">
                    <span class="caret"></span>
                    <span class="sr-only">Toggle Dropdown</span>
                  </button>
                  <ul class="dropdown-menu" role="menu">
                    <?php echo ($claim_payment == 1) ? '<li><a href="request_pay.php?auth='.$reg_pay_query->authorized_code.'&&id='.$id.'&&perc=100">Collect <b>Payment</b></a></li>' : ''; ?>
                  </ul>
                </div>
        				</td>
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