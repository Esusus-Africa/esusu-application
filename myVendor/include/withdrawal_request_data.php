<div class="row">
		
	       
		    <section class="content">  
	        <div class="box box-success">
            <div class="box-body">
              <div class="table-responsive">
             <div class="box-body">
<form method="post">
    
    <button type="submit" class="btn btn-flat bg-<?php echo ($myaltrow['alternate_color'] == "") ? "orange" : $myaltrow['alternate_color']; ?>" name="disapprove"><i class="fa fa-times"></i>&nbsp;Disapprove Withdrawal</button>
	
	<hr>	

			 <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th><input type="checkbox" id="select_all"/></th>
				  <th>Status</th>
				  <th>Date/Time</th>
				  <th>Customer Name</th>
				  <th>Withdrawal Token</th>
                  <th>Account ID</th>
                  <th>Categories</th>
                  <th>Plan Code</th>
                  <th>Subscription Code</th>
                  <th>Amount Requested</th>
                 </tr>
                </thead>
                <tbody>
<?php
$select = mysqli_query($link, "SELECT * FROM mcustomer_wrequest WHERE merchantid = '$vcreated_by' AND vendorid = '$vendorid' ORDER BY id DESC") or die (mysqli_error($link));
if(mysqli_num_rows($select)==0)
{
echo "<div class='alert bg-".(($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color'])."'>No data found yet!.....Check back later!!</div>";
}
else{
while($row = mysqli_fetch_array($select))
{
$id = $row['id'];
$merchantid = $row['merchantid'];
$status = $row['status'];
$wtokenid = $row['wtokenid'];
$virtualnum = $row['bank_details'];

$search_user = mysqli_query($link, "SELECT * FROM borrowers WHERE virtual_number = '$virtualnum'");
$fetch_user = mysqli_fetch_array($search_user);
$fullname = $fetch_user['lname'].' '.$fetch_user['fname'].' '.$fetch_user['mname'];

$utc_date = DateTime::createFromFormat('Y-m-d G:i:s',$row['date_time'],new DateTimeZone(date_default_timezone_get()));
$acst_date = clone $utc_date; // we don't want PHP's default pass object by reference here
$acst_date->setTimeZone(new DateTimeZone('GMT+1'));
$correctdate = $acst_date->format('Y-m-d g:i A');
?>    
                <tr>
				<td><input id="optionsCheckbox" class="checkbox"  name="selector[]" type="checkbox" value="<?php echo $id; ?>"></td>
                <td><?php echo $correctdate; ?></td>
				<td width="150"><b><?php echo $merchantid; ?></b><br>
                	<?php echo ($status == "Approved" ? '<span class="label bg-blue">Approve</span>' : ($status == "Disapproved" ? '<span class="label bg-orange">Disapproved</span>' : '<span class="label bg-red">Pending</span>')); ?>
                </td>
				<td><?php echo $fullname; ?></td>
                <td><b><?php echo ($status == "Approved" || $status == "Disapproved") ? $wtokenid : '<p>'.$wtokenid.'</p><br><a href="process_ifund.php?id='.$_SESSION['tid'].'&&tokenid='.$row['wtokenid'].'&&mid=NDkw&&tab=tab_6">Proccess Request</a>'; ?></b></td>
				<td><?php echo $row['account_number']; ?></td>
				<td><?php echo $row['savings_type']; ?></td>
				<td><?php echo $row['plan_code']; ?></td>
				<td><?php echo $row['subscription_code']; ?></td>
				<td><?php echo $row['amount_requested']; ?></td>
				</tr>
<?php } } ?>
             </tbody>
                </table>  
                
                <?php
						if(isset($_POST['disapprove'])){
						$idm = $_GET['id'];
							$id=$_POST['selector'];
							$N = count($id);
						if($id == ''){
						echo "<script>alert('Row Not Selected!!!'); </script>";	
						echo "<script>window.location='withdrawal_request.php?tid=".$_SESSION['tid']."&&mid=NDkw'; </script>";
							}
							else{
							for($i=0; $i < $N; $i++)
							{
								$result = mysqli_query($link,"UPDATE mcustomer_wrequest SET status = 'Disapproved' WHERE id ='$id[$i]'");
								echo "<script>alert('Request Disapproved Successfully!!!'); </script>";
								echo "<script>window.location='withdrawal_request.php?tid=".$_SESSION['tid']."&&mid=NDkw'; </script>";
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