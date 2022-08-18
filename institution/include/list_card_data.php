<div class="row">
		
	       
		    <section class="content">  
	        <div class="box box-success">
            <div class="box-body">
              <div class="table-responsive">
             <div class="box-body">
<form method="post">
	<a href="dashboard.php?id=<?php echo $_SESSION['tid']; ?>&&mid=<?php echo base64_encode("401"); ?>"><button type="button" class="btn bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>"><i class="fa fa-mail-reply-all"></i>&nbsp;Back</button> </a> 
	
	<hr>				  
			 <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th><input type="checkbox" id="select_all"/></th>
                  <th>S/No.</th>
                  <th>Branch</th>
        		  <th>Staff Name</th>
        		  <th>Account ID</th>
                  <th>Name</th>
                  <th>Phone</th>
                  <th>Date/Time</th>
				  <th>Card ID</th>
                  <th>Wallet Balance</th>
				  <th>Status</th>
                 </tr>
                </thead>
                <tbody>
<?php
($individual_customer_records != "1" && $branch_customer_records != "1") ? $select = mysqli_query($link, "SELECT * FROM borrowers WHERE branchid = '$institution_id' AND card_reg = 'Yes' AND card_id != 'NULL' ORDER BY id") or die (mysqli_error($link)) : "";
($individual_customer_records === "1" && $branch_customer_records != "1") ? $select = mysqli_query($link, "SELECT * FROM borrowers WHERE branchid = '$institution_id' AND lofficer = '$iuid' AND card_reg = 'Yes' AND card_id != 'NULL' ORDER BY id") or die (mysqli_error($link)) : "";
($individual_customer_records != "1" && $branch_customer_records === "1") ? $select = mysqli_query($link, "SELECT * FROM borrowers WHERE branchid = '$institution_id' AND sbranchid = '$isbranchid' AND card_reg = 'Yes' AND card_id != 'NULL'  ORDER BY id") or die (mysqli_error($link)) : "";
if(mysqli_num_rows($select)==0)
{
echo "<div class='alert bg-blue'>No data found yet!.....Check back later!!</div>";
}
else{
while($row = mysqli_fetch_array($select))
{
$id = $row['id'];
$snum = $row['snum'];
$acctno = $row['account'];
$lname = $row['lname'];
$fname = $row['fname'];
$email = $row['email'];
$phone = $row['phone'];
$date_time = $row['date_time'];
$acct_status = $row['acct_status'];
$bal = $row['balance'];
$mybranch = $row['branchid'];
$mysbranch = $row['sbranchid'];
$myofficer = $row['lofficer'];
$card_id = $row['card_id'];

//$image = $row['image'];

$search_branch = mysqli_query($link, "SELECT * FROM branches WHERE branchid = '$mysbranch'");
$fetch_branch = mysqli_fetch_array($search_branch);

$utc_date = DateTime::createFromFormat('Y-m-d G:i:s',$date_time,new DateTimeZone(date_default_timezone_get()));
$acst_date = clone $utc_date; // we don't want PHP's default pass object by reference here
$acst_date->setTimeZone(new DateTimeZone('GMT+1'));
$correctdate = $acst_date->format('Y-m-d g:i A');

$search_staff = mysqli_query($link, "SELECT * FROM user WHERE id = '$myofficer'");
$fetch_staff = mysqli_fetch_array($search_staff);
?>    
                <tr>
				<td><input id="optionsCheckbox" class="checkbox"  name="selector[]" type="checkbox" value="<?php echo $id; ?>"></td>
				<td><?php echo ($snum == "") ? "null" : $snum; ?></td>
				<td><?php echo ($mybranch != "" && $mysbranch == "") ? '<b>Head Office</b>' : '<b>'.$fetch_branch['bname'].'</b>'; ?></td>
				<td><?php echo ($myofficer == "") ? 'NIL' : $fetch_staff['name']; ?></td>
                <td width="150"><?php echo $acctno; ?></td>
				<td><?php echo $fname.'&nbsp;'.$lname; ?></td>
				<td><?php echo $phone; ?></td>
				<td><?php echo $correctdate; ?></td>
				<td><?php echo panNumberMasking($card_id); ?></td>
				<td><?php echo $row['currency'].number_format($row['wallet_balance'],2,'.',','); ?></td>
				<td><?php echo ($acct_status == "Activated") ? "<span class='label bg-blue'>Active</span>" : "<span class='label bg-orange'>Not-Active</span>"; ?></td>
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