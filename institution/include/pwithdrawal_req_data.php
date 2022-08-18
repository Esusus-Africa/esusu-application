<div class="row">
		
	       
		    <section class="content">  
	        <div class="box box-success">
            <div class="box-body">
              <div class="table-responsive">
             <div class="box-body">
<form method="post">
	<hr>				  
			 <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th><input type="checkbox" id="select_all"/></th>
				  <th>Withdrawal Token</th>
                  <th>Merchant Name</th>
                  <th>Account ID</th>
                  <th>Categories</th>
                  <th>Plan Name</th>
                  <th>Subscription Code</th>
                  <th>Amount Requested</th>
                  <th>Status</th>
                 </tr>
                </thead>
                <tbody>
<?php
($individual_customer_records == "1") ? $select = mysqli_query($link, "SELECT * FROM mcustomer_wrequest WHERE merchantid = '$institution_id' AND agentid = '$iuid' ORDER BY id DESC") or die (mysqli_error($link)) : "";
($individual_customer_records == "1") ? $select = mysqli_query($link, "SELECT * FROM mcustomer_wrequest WHERE merchantid = '$institution_id' AND agentid != '' ORDER BY id DESC") or die (mysqli_error($link)) : "";
if(mysqli_num_rows($select)==0)
{
echo "<div class='alert bg-".(($myaltrow['theme_color'] == "") ? 'blue' : $myaltrow['theme_color'])."'>No data found yet!.....Check back later!!</div>";
}
else{
while($row = mysqli_fetch_array($select))
{
$id = $row['id'];
$merchantid = ($row['vendorid'] == "") ? $row['merchantid'] : $row['vendorid'];
$wtoken = $row['wtokenid'];
$c_actno = $row['account_number'];
$pcode = $row['plan_code'];

$search_pcode = mysqli_query($link, "SELECT * FROM savings_plan WHERE plan_code = '$pcode'");
$fetch_pcode = mysqli_fetch_array($search_pcode);

$search_custi = mysqli_query($link, "SELECT * FROM borrowers WHERE account = '$c_actno'");
$fetch_custi = mysqli_fetch_array($search_custi);

$search_insti = mysqli_query($link, "SELECT * FROM institution_data WHERE institution_id = '$merchantid'");
$fetch_insti = mysqli_fetch_array($search_insti);

$search_vendi = mysqli_query($link, "SELECT * FROM vendor_reg WHERE companyid = '$merchantid'");
$fetch_num = mysqli_num_rows($search_vendi);
$fetch_vendi = mysqli_fetch_array($search_vendi);

$merchantname = ($fetch_num == 1) ? $fetch_vendi['cname'] : $fetch_insti['institution_name'];
?>    
                <tr>
				<td><input id="optionsCheckbox" class="checkbox"  name="selector[]" type="checkbox" value="<?php echo $id; ?>"></td>
                <td><b><?php echo $row['wtokenid']; ?></b></td>
                <td width="150"><b><?php echo $merchantname; ?></b></td>
				<td><?php echo $fetch_custi['fname'].' '.$fetch_custi['lname'].' ('.$row['account_number'].')'; ?></td>
				<td><?php echo $row['savings_type']; ?></td>
				<td><?php echo $fetch_pcode['plan_name']; ?></td>
				<td><?php echo $row['subscription_code']; ?></td>
				<td><?php echo $row['amount_requested']; ?></td>
                <td><?php echo ($row['status'] == "Approved" ? '<span class="label bg-blue">Approve</span>' : ($row['status'] == "Disapproved" ? '<span class="label bg-red">Disapproved</span>' : '<span class="label bg-orange">Pending</span>')); ?></td>
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