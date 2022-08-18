<div class="row">       
		    <section class="content">  
	        <div class="box box-success">
            <div class="box-body">
              <div class="table-responsive">
             <div class="box-body">
<form method="post">
			 <a href="dashboard.php?id=<?php echo $_SESSION['tid']; ?>&&mid=<?php echo base64_encode("401"); ?>"><button type="button" class="btn btn-flat bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>"><i class="fa fa-mail-reply-all"></i>&nbsp;Back</button> </a> 

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
				  <th>Contact Number</th>
                  <th>Amount to Pay + Interest</th>
                  <th>Approve By</th>
                  <th>date Release</th>
                  <th>Approval Status</th>
				  <th>Update Status</th>
                  <th>Action</th>
                 </tr>
                </thead>
                <tbody> 
<?php
$pay_date = date("Y-m-d");
$select = mysqli_query($link, "SELECT * FROM loan_info WHERE vendorid = '$vendorid' AND p_status != 'PAID' AND status = 'Approved' AND pay_date > '$pay_date' ORDER BY id DESC") or die (mysqli_error($link));
if(mysqli_num_rows($select)==0)
{
echo "<div class='alert bg-".$myaltrow['theme_color'].' == "" ? blue : '.$myaltrow['theme_color']."'>No data found yet!.....Check back later!!</div>";
}
else{
    while($row = mysqli_fetch_array($select))
    {
    $id = $row['id'];
    $lid = $row['lid'];
    $borrower = $row['borrower'];
    $acn = $row['baccount'];
    $status = $row['status'];
    $upstatus = $row['upstatus'];
    $selectin = mysqli_query($link, "SELECT * FROM borrowers WHERE id = '$borrower'") or die (mysqli_error($link));
    $geth = mysqli_fetch_array($selectin);
    $name = $geth['lname'].' '.$geth['fname'];
    $myphone = $geth['phone'];
    
    //System settings
    $systemset = mysqli_query($link, "SELECT * FROM systemset");
    $rowsys = mysqli_fetch_array($systemset);
?> 
    <?php
    if($upstatus == "Pending")
    {
    ?>  
                    <tr>
    				<td><input id="optionsCheckbox" class="checkbox" name="selector[]" type="checkbox" value="<?php echo $row['id']; ?>"></td>
                    <td><b><?php echo $row['lid']; ?></b></td>
    				<td align="center"><?php echo $name.'<br>('.$row['baccount'].')'; ?></td>
    				<td><?php echo $myphone; ?></td>
    				<td><b><?php echo $rowsys['currency'].number_format($row['amount_topay'],2,'.',','); ?></b></td>
    			   <td><?php echo ($row['teller'] == "") ? '<span class="label bg-orange">Waiting for Review</span>' : $row['teller']; ?></td>
    				<td><?php echo $row['date_release']; ?></td>
                    <td>
    				 <span class="label bg-<?php if($status =='Approved')echo 'blue'; elseif($status =='Disapproved')echo 'orange'; else echo 'orange';?>"><?php echo $status; ?></span>
    				</td>
    			<td align="center" class="alert bg-orange"><?php echo $upstatus; ?><br><?php echo ($update_loan_records == '1') ? '<a href="updateloans.php?id='.$id.'&&mid='.base64_encode("405").'&&acn='.$acn.'&&lid='.$row['lid'].'&&tab=tab_0">Click here to complete Registration!</a>' : ''; ?></td>
    			<td>
    			<?php echo ($update_loan_records == '1') ? "<a href='updateloans.php?id=".$id."&&acn=".$row['baccount']."&&mid=".base64_encode("405")."&&lid=".$row['lid']."&&tab=tab_0'><button type='button' class='btn bg-".(($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color'])."'><i class='fa fa-eye'></i></button></a>" : "--"; ?><br>
    <?php
    $se = mysqli_query($link, "SELECT * FROM attachment WHERE get_id = '$id'") or die (mysqli_error($link));
    while($gete = mysqli_fetch_array($se))
    {
    ?>
    				<?php echo ($loan_details == '1') ? '<a href="'.$gete['attached_file'].'" target="_blank"><i class="fa fa-download"></i></a>&nbsp;&nbsp;' : '--'; ?>
    <?php } ?>
    				</td>		    
    			    </tr>
    <?php
    }
    else{
    ?>
    				<tr>
    				<td><input id="optionsCheckbox" class="checkbox" name="selector[]" type="checkbox" value="<?php echo $row['id']; ?>"></td>
                    <td><a href="newpayments.php?id=<?php echo $_SESSION['tid']; ?>&&mid=NDA4"><b><?php echo $row['lid']; ?></b></a></td>
    				<td align="center"><?php echo $name.'<br>('.$row['baccount'].')'; ?></td>
    				<td><?php echo $myphone; ?></td>
    				<td><b><?php echo $rowsys['currency'].number_format($row['amount_topay'],2,'.',','); ?></b></td>
    			    <td><?php echo ($row['teller'] == "") ? '<span class="label bg-orange">Waiting for Review</span>' : $row['teller']; ?></td>
    				<td><?php echo $row['date_release']; ?></td>
                    <td>
    				<span class="label bg-<?php if($status =='Approved')echo 'blue'; elseif($status =='Disapproved')echo 'orange'; else echo 'orange';?>"><?php echo $status; ?></span>
    				</td>
    				<td align="center" class="alert bg-blue"><?php echo $upstatus; ?></td>
    				<td>
    			    <?php echo ($update_loan_records == '1') ? "<a href='updateloans.php?id=".$id."&&acn=".$row['baccount']."&&mid=".base64_encode("405")."&&lid=".$row['lid']."&&tab=tab_0'><button type='button' class='btn bg-".(($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color'])."'><i class='fa fa-eye'></i></button></a>" : "--"; ?><br>
    <?php
    $se = mysqli_query($link, "SELECT * FROM attachment WHERE get_id = '$id'") or die (mysqli_error($link));
    while($gete = mysqli_fetch_array($se))
    {
    ?>
    				<?php echo ($update_loan_records == '1') ? '<a href="'.$gete['attached_file'].'" target="_blank"><i class="fa fa-download"></i></a>&nbsp;&nbsp;' : '--'; ?>  
    <?php } ?>
    				</td>	    
    			    </tr>
<?php } } } ?>
                 </tbody>
                    </table>  
<?php
						if(isset($_POST['delete'])){
						$idm = $_GET['id'];
							$id=$_POST['selector'];
							$N = count($id);
							$search_loan_by_id = mysqli_query($link, "SELECT * FROM loan_info WHERE id = '$id'");
							
							$getloan_lid = mysqli_fetch_object($search_loan_by_id);
						$get_lid = $getloan_lid->lid;
						if($id == ''){
						echo "<script>alert('Row Not Selected!!!'); </script>";	
						echo "<script>window.location='listloans.php?id=".$_SESSION['tid']."'; </script>";
							}
							else{
							for($i=0; $i < $N; $i++)
							{
								$result = mysqli_query($link,"DELETE FROM loan_info WHERE id ='$id[$i]'");
								$result = mysqli_query($link,"DELETE FROM pay_schedule WHERE get_id ='$id[$i]'");
								$result = mysqli_query($link,"DELETE FROM payment_schedule WHERE lid ='$get_lid'");
								$result = mysqli_query($link,"DELETE FROM interest_calculator WHERE lid ='$get_lid'");
								$result = mysqli_query($link,"DELETE FROM attachment WHERE get_id ='$id[$i]'");
								$result = mysqli_query($link,"DELETE FROM battachment WHERE get_id ='$id[$i]'");
								$result = mysqli_query($link,"DELETE FROM additional_fees WHERE get_id ='$id[$i]'");
								$result = mysqli_query($link,"DELETE FROM collateral WHERE idm ='$id[$i]'");

								echo "<script>alert('Row Delete Successfully!!!'); </script>";
								echo "<script>window.location='listloans.php?id=".$_SESSION['tid']."'; </script>";
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