<div class="row">       
		    <section class="content">  
	        <div class="box box-success">
            <div class="box-body">
              <div class="table-responsive">
             <div class="box-body">
<form method="post">
			 <a href="dashboard.php?id=<?php echo $_SESSION['tid']; ?>&&mid=<?php echo base64_encode("401"); ?>"><button type="button" class="btn btn-flat bg-orange"><i class="fa fa-mail-reply-all"></i>&nbsp;Back</button> </a> 

<?php echo ($delete_loans == '1') ? '<button type="submit" class="btn btn-flat bg-orange" name="delete"><i class="fa fa-times"></i>&nbsp;Multiple Delete</button>' : ''; ?>

<a href="disapprloans.php?id=<?php echo $_SESSION['tid']; ?>&&mid=<?php echo base64_encode("405"); ?>"><button type="button" class="btn btn-flat bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>"><i class="fa fa-refresh"></i>&nbsp;Refresh</button></a>

	<hr>		
			  
			 <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th><input type="checkbox" id="select_all"/></th>
                  <th>Loan ID</th>
                  <th>RRR Number</th>
				  <th>Account ID</th>
				  <th>Contact Number</th>
                  <th>Principal Amount</th>
                  <th>Principal Amount + Interest</th>
                  <th>Booked By</th>
                  <th>Last Reviewed By</th>
                  <th>Date Release</th>
                  <th>Approval Status</th>
				  <th>Update Status</th>
                 </tr>
                </thead>
                <tbody> 
<?php
/*$search_loanp = mysqli_query($link, "SELECT * FROM loan_product WHERE (merchantid = '$institution_id') OR (merchantid != '$institution_id' AND visibility = 'Yes' AND authorize = '1')");
while($fetch_loanp = mysqli_fetch_array($search_loanp))
{
	$lp_id = $fetch_loanp['id'];*/
	
	($view_all_loans === "1" && $individual_loan_records === "" && $branch_loan_records === "") ? $select = mysqli_query($link, "SELECT * FROM loan_info WHERE branchid = '$institution_id' AND status = 'Disapproved' ORDER BY ID DESC") or die (mysqli_error($link)) : "";
	($view_all_loans === "" && $individual_loan_records === "1" && $branch_loan_records === "") ? $select = mysqli_query($link, "SELECT * FROM loan_info WHERE branchid = '$institution_id' AND (agent = '$iname' OR agent = '$iuid') AND status = 'Disapproved' ORDER BY ID DESC") or die (mysqli_error($link)) : "";
	($view_all_loans === "" && $individual_loan_records === "" && $branch_loan_records === "1") ? $select = mysqli_query($link, "SELECT * FROM loan_info WHERE branchid = '$institution_id' AND sbranchid = '$isbranchid' AND status = 'Disapproved' ORDER BY ID DESC") or die (mysqli_error($link)) : "";
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
	$acct_officer = $row['agent'];
	
	$search_user = mysqli_query($link, "SELECT * FROM user WHERE id = '$acct_officer' OR name = '$acct_officer'");
	$fetch_user = mysqli_fetch_array($search_user);
?> 

				<tr>
				<td><input id="optionsCheckbox" class="checkbox" name="selector[]" type="checkbox" value="<?php echo $row['id']; ?>"></td>
                <td><b><?php echo $row['lid']; ?></b></td>
                <td><b><?php echo ($row['mandate_status'] === "Pending") ? '----' : $row['mandate_id']; ?></b></td>
				<td align="center"><?php echo $name.'<br>('.$row['baccount'].')'; ?></td>
				<td><?php echo $myphone; ?></td>
				<td><b><?php echo $icurrency.number_format($row['amount'],2,'.',','); ?></b></td>
				<td><b><?php echo $icurrency.number_format($row['amount_topay'],2,'.',','); ?></b></td>
				<td><?php echo ($row['agent'] == "") ? 'Customer' : $fetch_user['name']; ?></td>
			    <td><?php echo ($row['teller'] == "") ? '<span class="label bg-orange">Waiting for Review</span>' : $row['teller']; ?></td>
				<td><?php echo $row['date_release']; ?></td>
                <td>
				<span class="label bg-<?php echo ($status =='Approved' ? 'blue' : ($status =='Disapproved' ? 'red' : 'orange')); ?>"><?php echo $status; ?></span>
				</td>
				<td align="center" class="alert bg-<?php echo ($upstatus =='Completed') ? 'blue' : 'orange'; ?>"><?php echo $upstatus; ?><br><?php echo ($update_loan_records == '1' && $upstatus != "Completed") ? '<a href="updateloans.php?id='.$id.'&&mid='.base64_encode("405").'&&acn='.$acn.'&&lid='.$row['lid'].'&&tab=tab_0">Click here to complete Registration!</a>' : ''; ?></td>
			    </tr>
<?php } //} ?>
             </tbody>
                </table>  
<?php
						if(isset($_POST['delete'])){
						    $idm = $_GET['id'];
							$id=$_POST['selector'];
							$N = count($id);
    						if($id == ''){
        						echo "<script>alert('Row Not Selected!!!'); </script>";	
        						echo "<script>window.location='disapprloans.php?id=".$_SESSION['tid']."&&mid=NDA1&&tab=tab_1'; </script>";
        					}
    						else{
    							for($i=0; $i < $N; $i++)
    							{
    							    $search_loan_by_id = mysqli_query($link, "SELECT * FROM loan_info WHERE id = '$id'");
        							$getloan_lid = mysqli_fetch_object($search_loan_by_id);
        						    $get_lid = $getloan_lid->lid;
        						    
    								$result = mysqli_query($link,"DELETE FROM loan_info WHERE id ='$id[$i]'");
    								$result = mysqli_query($link,"DELETE FROM pay_schedule WHERE get_id ='$id[$i]'");
    								$result = mysqli_query($link,"DELETE FROM payment_schedule WHERE lid ='$get_lid'");
    								$result = mysqli_query($link,"DELETE FROM interest_calculator WHERE lid ='$get_lid'");
    								$result = mysqli_query($link,"DELETE FROM attachment WHERE get_id ='$id[$i]'");
    								$result = mysqli_query($link,"DELETE FROM battachment WHERE get_id ='$id[$i]'");
    								//$result = mysqli_query($link,"DELETE FROM additional_fees WHERE get_id ='$id[$i]'");
    								//$result = mysqli_query($link,"DELETE FROM collateral WHERE idm ='$id[$i]'");
    
    								echo "<script>alert('Row Delete Successfully!!!'); </script>";
    								echo "<script>window.location='disapprloans.php?id=".$_SESSION['tid']."&&mid=NDA1&&tab=tab_1'; </script>";
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