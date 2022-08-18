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

    <?php echo ($deposit_money == '1') ? '<a href="deposit.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("410").'"><button type="button" class="btn btn-flat bg-'.(($myaltrow['theme_color'] == "") ? 'blue' : $myaltrow['theme_color']).'"><i class="fa fa-plus"></i>&nbsp;Deposit</button></a>' : ''; ?>
	<?php echo ($withdraw_money == '1') ? '<a href="withdraw.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("410").'"><button type="button" class="btn btn-flat bg-'.(($myaltrow['theme_color'] == "") ? 'blue' : $myaltrow['theme_color']).'"><i class="fa fa-plus"></i>&nbsp;Withdraw</button></a>' : ''; ?>

<?php
}
else{
    ?>
	<a href="dashboard.php?id=<?php echo $_SESSION['tid']; ?>&&mid=<?php echo base64_encode("401"); ?>"><button type="button" class="btn btn-flat bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>"><i class="fa fa-mail-reply-all"></i>&nbsp;Back</button> </a> 
    <?php //echo ($delete_transaction == '1') ? '<button type="submit" class="btn btn-flat bg-'.(($myaltrow['alternate_color'] == "") ? 'orange' : $myaltrow['alternate_color']).'" name="delete"><i class="fa fa-times"></i>&nbsp;Multiple Reversal</button>' : ''; ?>
	<?php echo ($deposit_money == '1') ? '<a href="deposit.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("410").'"><button type="button" class="btn btn-flat bg-'.(($myaltrow['theme_color'] == "") ? 'blue' : $myaltrow['theme_color']).'"><i class="fa fa-plus"></i>&nbsp;Make Deposit</button></a>' : ''; ?>
	<?php echo ($withdraw_money == '1') ? '<a href="withdraw.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("410").'"><button type="button" class="btn btn-flat bg-'.(($myaltrow['theme_color'] == "") ? 'blue' : $myaltrow['theme_color']).'"><i class="fa fa-plus"></i>&nbsp;Withdraw Money</button></a>' : ''; ?>
	<?php //echo ($print_transaction == '1') ? '<a href="printtransaction.php" target="_blank" class="btn bg-'.$myaltrow['alternate_color'].' == "" ? orange : '.$myaltrow['alternate_color'].' btn-flat"><i class="fa fa-print"></i>&nbsp;Print</a>' : ''; ?>
	<?php //echo ($export_transaction == '1') ? '<a href="transactionexcel.php" target="_blank" class="btn bg-'.$myaltrow['alternate_color'].' == "" ? orange : '.$myaltrow['alternate_color'].' btn-flat"><i class="fa fa-send"></i>&nbsp;Export Excel</a>' : ''; ?>	
<?php    
}
?>

	<hr>
	
	
<?php
$today_record = date("Y-m-d");
if(isset($_GET['status']) && ($irole == "agent_manager" || $irole == "institution_super_admin" || $irole == "merchant_super_admin"))
{
?>

			  
			 <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th><input type="checkbox" id="select_all"/></th>
                  <th>TxID</th>
				  <th>Savings Product</th>
				  <th>Branch</th>
				  <th>AcctNo.</th>
				  <th>Acct. Name</th>
                  <th>Phone</th>
                  <th>Debit</th>
                  <th>Credit</th>
				  <th>Balance</th>
				  <th>Date/Time</th>
				  <th>Posted By</th>
                 </tr>
                </thead>
                <tbody>
<?php
$select = mysqli_query($link, "SELECT * FROM transaction WHERE date_time LIKE '$today_record%' AND branchid = '$institution_id' ORDER BY id DESC LIMIT 1000") or die (mysqli_error($link));
if(mysqli_num_rows($select)==0)
{
echo "<div class='alert bg-blue'>No data found yet!.....Check back later!!</div>";
}
else{
	$select2 = mysqli_query($link, "SELECT SUM(amount) FROM transaction WHERE branchid = '$institution_id' ORDER BY id DESC") or die (mysqli_error($link));
	$get_select2 = mysqli_fetch_array($select2);
while($row = mysqli_fetch_array($select))
{
$id = $row['id'];
$txid = $row['txid'];
$t_type = $row['t_type'];
$p_type = $row['p_type'];
$acctno = $row['acctno'];
$transfer_to = $row['transfer_to'];
$ph = $row['phone'];
$amt = $row['amount'];
$dt = $row['date_time'];
$posted_by = $row['posted_by'];
$auname = $row['fn'].' '.$row['ln'];
$remark = $row['remark'];
$tbranch = $row['sbranchid'];

$confirm_borr = mysqli_query($link, "SELECT * FROM borrowers WHERE account = '$acctno'");
$fetch_borr = mysqli_fetch_array($confirm_borr);

$confirm_tbranch = mysqli_query($link, "SELECT * FROM branches WHERE branchid = '$tbranch'");
$fetch_tbranch = mysqli_fetch_array($confirm_tbranch);

$select3 = mysqli_query($link, "SELECT name FROM user WHERE id = '$posted_by' ORDER BY id DESC") or die (mysqli_error($link));
$get_select3 = mysqli_fetch_array($select3);
?>    
                <tr>
				<td><input id="optionsCheckbox" class="checkbox"  name="selector[]" type="checkbox" value='.$id.'></td>
        		<td><a href="view_receipt.php?id=<?php echo $id; ?>" target="_blank"><?php echo $txid; ?></a></td>
				<td><?php echo ($fetch_borr['acct_type'] == "") ? "---" : $fetch_borr['acct_type']; ?></td>
				<td><b><?php echo ($tbranch == "") ? "Head Office" : $fetch_tbranch['bname']; ?></b></td>
                <td><?php echo $acctno; ?></td>
                <td><b><?php echo $auname; ?></b></td>
                <td><?php echo $ph; ?></td>
                <td><?php echo ($t_type == "Withdraw" || $t_type == "Withdraw-Charges" || $t_type == "Transfer") ? $row['currency'].number_format($amt,2,'.',',') : "---"; ?></td>
                <td><?php echo ($t_type == "Deposit" || $t_type == "Transfer-Received") ? $row['currency'].number_format($amt,2,'.',',') : "---"; ?></td>
        		<td><?php echo $row['currency'].number_format($row['balance'],2,'.',','); ?></td>
				<td><?php echo $dt; ?></td>
        		<td><?php echo $get_select3['name'].' '.$get_select3['lname'].' '.$get_select3['mname']; ?></td>
        		<!--<td align="center"><a href="view_receipt.php?id=<?php //echo $id; ?>" target="_blank"> <button type="button" class="btn bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?> btn-flat" data-target="#myModal'.$id.'" data-toggle="modal"><i class="fa fa-print"></i> Receipt</button></a></td>-->
				</tr>
<?php 
}
}
?>
             </tbody>
                </table>
                
                
                
<?php
}
elseif(isset($_POST['status']) && ($irole != "agent_manager" || $irole != "institution_super_admin" || $irole != "merchant_super_admin")){
?>
                


<table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th><input type="checkbox" id="select_all"/></th>
                  <th>TxID</th>
				  <th>Savings Product</th>
				  <th>Branch</th>
				  <th>AcctNo.</th>
				  <th>Acct. Name</th>
                  <th>Phone</th>
                  <th>Debit</th>
                  <th>Credit</th>
				  <th>Balance</th>
				  <th>Date/Time</th>
				  <th>Posted By</th>
                 </tr>
                </thead>
                <tbody>
<?php
$select = mysqli_query($link, "SELECT * FROM transaction WHERE date_time LIKE '$today_record%' AND branchid = '$institution_id' AND posted_by = '$iuid' ORDER BY id DESC LIMIT 500") or die (mysqli_error($link));
if(mysqli_num_rows($select)==0)
{
echo "<div class='alert bg-blue'>No data found yet!.....Check back later!!</div>";
}
else{
	$select2 = mysqli_query($link, "SELECT SUM(amount) FROM transaction WHERE branchid = '$institution_id' ORDER BY id DESC") or die (mysqli_error($link));
	$get_select2 = mysqli_fetch_array($select2);
while($row = mysqli_fetch_array($select))
{
$id = $row['id'];
$txid = $row['txid'];
$t_type = $row['t_type'];
$p_type = $row['p_type'];
$acctno = $row['acctno'];
$transfer_to = $row['transfer_to'];
$ph = $row['phone'];
$amt = $row['amount'];
$dt = $row['date_time'];
$posted_by = $row['posted_by'];
$auname = $row['fn'].' '.$row['ln'];
$remark = $row['remark'];
$tbranch = $row['sbranchid'];

$confirm_borr = mysqli_query($link, "SELECT * FROM borrowers WHERE account = '$acctno'");
$fetch_borr = mysqli_fetch_array($confirm_borr);

$confirm_tbranch = mysqli_query($link, "SELECT * FROM branches WHERE branchid = '$tbranch'");
$fetch_tbranch = mysqli_fetch_array($confirm_tbranch);

$select3 = mysqli_query($link, "SELECT name FROM user WHERE id = '$posted_by' ORDER BY id DESC") or die (mysqli_error($link));
$get_select3 = mysqli_fetch_array($select3);
?>    
                <tr>
				<td><input id="optionsCheckbox" class="checkbox"  name="selector[]" type="checkbox" value='.$id.'></td>
        		<td><a href="view_receipt.php?id=<?php echo $id; ?>" target="_blank"><?php echo $txid; ?></a></td>
				<td><?php echo ($fetch_borr['acct_type'] == "") ? "---" : $fetch_borr['acct_type']; ?></td>
				<td><b><?php echo ($tbranch == "") ? "Head Office" : $fetch_tbranch['bname']; ?></b></td>
                <td><?php echo $acctno; ?></td>
                <td><b><?php echo $auname; ?></b></td>
                <td><?php echo $ph; ?></td>
                <td><?php echo ($t_type == "Withdraw" || $t_type == "Withdraw-Charges" || $t_type == "Transfer") ? $row['currency'].number_format($amt,2,'.',',') : "---"; ?></td>
                <td><?php echo ($t_type == "Deposit" || $t_type == "Transfer-Received") ? $row['currency'].number_format($amt,2,'.',',') : "---"; ?></td>
        		<td><?php echo $row['currency'].number_format($row['balance'],2,'.',','); ?></td>
				<td><?php echo $dt; ?></td>
        		<td><?php echo $get_select3['name'].' '.$get_select3['lname'].' '.$get_select3['mname']; ?></td>
        		<!--<td align="center"><a href="view_receipt.php?id=<?php //echo $id; ?>" target="_blank"> <button type="button" class="btn bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?> btn-flat" data-target="#myModal'.$id.'" data-toggle="modal"><i class="fa fa-print"></i> Receipt</button></a></td>-->
				</tr>
<?php 
}
}
?>
             </tbody>
                </table>




<?php
}
else{
?>

           
             <div class="box-body">
                 
	           <div class="form-group">
                  <label for="" class="col-sm-1 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">From</label>
                  <div class="col-sm-5">
                  <input name="dfrom" type="date" id="startDate" class="form-control" placeholder="To Date: 2018-05-01">
				  <span style="color: <?php echo ($myaltrow['alternate_color'] == '') ? 'blue' : $myaltrow['alternate_color']; ?>;">Date should be in this format: 2018-05-01</span>
                  </div>
				  
				  <label for="" class="col-sm-1 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">End Date</label>
                  <div class="col-sm-5">
                  <input name="dto" type="date" id="endDate" class="form-control" placeholder="To Date: 2018-05-24">
				  <span style="color: <?php echo ($myaltrow['alternate_color'] == '') ? 'blue' : $myaltrow['alternate_color']; ?>;">Date should be in this format: 2018-05-24</span>
                  </div>
             </div>

            <div class="form-group">
                  <label for="" class="col-sm-1 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Type</label>
                  <div class="col-sm-5">
                  <select name="ptype" id="pmtType" class="form-control select2" style="width:100%">
					<option value="" selected="selected">Filter By Payment Type...</option>
					<option value="All">All Transaction</option>
					<option value="Deposit">Deposit</option>
					<option value="Withdraw">Withdraw</option>
					<option value="Withdraw-Charges">Withdraw-Charges</option>
					<option value="Cash">Cash</option>
					<option value="Bank">Bank</option>
				  </select>
                  </div>
				  
				  <label for="" class="col-sm-1 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Filter By</label>
                  <div class="col-sm-5">
                 <select name="filter_by" id="filterBy" class="form-control select2" style="width:100%" required>
    				<option value="" selected="selected">Filter By Staff, Customer, Branch...</option>

					<option disabled>Filter By Staff / Sub-agent</option>
					<?php
					($list_employee === "1" && $list_branch_employee != "1") ? $get2 = mysqli_query($link, "SELECT * FROM user WHERE created_by = '$institution_id' ORDER BY userid DESC") or die (mysqli_error($link)) : "";
					($list_employee != "1" && $list_branch_employee === "1") ? $get2 = mysqli_query($link, "SELECT * FROM user WHERE created_by = '$institution_id' AND branchid = '$isbranchid' ORDER BY userid DESC") or die (mysqli_error($link)) : "";
					while($rows2 = mysqli_fetch_array($get2))
					{
					?>
					<option value="<?php echo $rows2['id']; ?>"><?php echo $rows2['name'].' '.$rows2['fname'].' '.$rows2['mname']; ?></option>
					<?php } ?>
    				

				    <option disabled>Filter By Customer</option>
    				<?php
    				($individual_customer_records != "1" && $branch_customer_records != "1") ? $get4 = mysqli_query($link, "SELECT * FROM borrowers WHERE branchid = '$institution_id' ORDER BY id") or die (mysqli_error($link)) : "";
    				($individual_customer_records === "1" && $branch_customer_records != "1") ? $get4 = mysqli_query($link, "SELECT * FROM borrowers WHERE branchid = '$institution_id' AND (lofficer = '$iuid' OR lofficer = '$iuserid') ORDER BY id") or die (mysqli_error($link)) : "";
    				($individual_customer_records != "1" && $branch_customer_records === "1") ? $get4 = mysqli_query($link, "SELECT * FROM borrowers WHERE branchid = '$institution_id' AND sbranchid = '$isbranchid' ORDER BY id") or die (mysqli_error($link)) : "";
    				while($rows4 = mysqli_fetch_array($get4))
    				{
    				?>
    				<option value="<?php echo $rows4['account']; ?>"><?php echo $rows4['lname'].' '.$rows4['fname'].' ['.$rows4['account'].']'; ?></option>
    				<?php } ?>
    				
    				
    				<?php
    				////TO BE ACCESSIBLE BY THE SUPER ADMIN ONLY
    				if($list_branches === "1")
    				{
    				?>
    				<option disabled>Filter By Branch</option>
    				<?php
    				$get5 = mysqli_query($link, "SELECT * FROM branches WHERE created_by = '$institution_id' ORDER BY id") or die (mysqli_error($link));
    				while($rows5 = mysqli_fetch_array($get5))
    				{
    				?>
    				<option value="<?php echo $rows5['branchid']; ?>"><?php echo $rows5['bname'].' ['.$rows5['branchid'].']'; ?></option>
    				<?php } ?>
    				<?php
    				}
    				else{
    				    //nothing
    				}
    				?>
    				
				</select>
                  </div>
                </div>
                
                </div>
			 

		<hr>			
		<div class="table-responsive">
			 <table id="fetch_transaction_data" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th><input type="checkbox" id="select_all"/></th>
                  <th>TxID</th>
				  <th>Savings Product</th>
				  <th>Branch</th>
				  <th>AcctNo.</th>
				  <th>Acct. Name</th>
                  <th>Phone</th>
                  <th>Debit</th>
                  <th>Credit</th>
				  <th>Balance</th>
				  <th>Status</th>
				  <th>Date/Time</th>
				  <th>Posted By</th>
                 </tr>
                </thead>
                </table>
                </div>
			
        
<?php
}
?>


<?php
						if(isset($_POST['delete'])){
							$idm = $_GET['id'];
							$id=$_POST['selector'];
							$N = count($id);
							if($id == ''){
								echo "<script>alert('Row Not Selected!!!'); </script>";	
								echo "<script>window.location='transaction.php?id=".$_SESSION['tid']."&&mid=NDEw'; </script>";
							}
							else{
							for($i=0; $i < $N; $i++)
							{
								$search_trans = mysqli_query($link, "SELECT * FROM transaction WHERE id ='$id[$i]'");
								while($get_trans = mysqli_fetch_object($search_trans))
								{
									$bal = $get_trans->amount;
									$acn = $get_trans->acctno;

									$search_borrower = mysqli_query($link, "SELECT * FROM borrowers WHERE account ='$acn'");
									$get_borrower = mysqli_fetch_object($search_borrower);
									$balance = $get_borrower->balance;
									$newbalance = $balance - $bal;
			
									$update = mysqli_query($link, "UPDATE borrowers SET balance = '$newbalance' WHERE account ='$acn'");
									$result = mysqli_query($link,"DELETE FROM transaction WHERE id ='$id[$i]'");
									echo "<script>alert('Row Delete Successfully!!!'); </script>";
									echo "<script>window.location='transaction.php?id=".$_SESSION['tid']."&&mid=NDEw'; </script>";
								}
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