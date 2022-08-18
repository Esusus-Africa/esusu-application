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
				  <th>Invoice Code</th>
				  <th>Customer Name</th>
				  <th>Plan Name</th>
                  <th>Subscription Code</th>
                  <th>Reference No.</th>
                  <th>Initiated By</th>
                  <th>Amount</th>
                  <th>Date/Time</th>
                 </tr>
                </thead>
                <tbody>
<?php
($individual_customer_records == "1") ? $select = mysqli_query($link, "SELECT * FROM all_savingssub_transaction WHERE merchant_id = '$institution_id' AND agentid = '$iuid' ORDER BY id DESC") or die (mysqli_error($link)) : "";
($individual_customer_records != "1") ? $select = mysqli_query($link, "SELECT * FROM all_savingssub_transaction WHERE merchant_id = '$institution_id' AND agentid != '' ORDER BY id DESC") or die (mysqli_error($link)) : "";
if(mysqli_num_rows($select)==0)
{
echo "<div class='alert bg-".(($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color'])."'>No data found yet!.....Check back later!!</div>";
}
else{
while($row = mysqli_fetch_array($select))
{
$id = $row['id'];
$merchantid = $row['merchant_id'];
$date_time = $row['date_time'];
$acn = $row['acn'];
$plan_code = $row['next_pmt_date'];
$agentid = $row['agentid'];

$search_plan = mysqli_query($link, "SELECT * FROM savings_plan WHERE plan_code = '$plan_code'");
$fetch_plan = mysqli_fetch_array($search_plan);
$plan1 = $fetch_plan['plan_name'];

$search_agent = mysqli_query($link, "SELECT * FROM user WHERE id = '$agentid' AND created_by = '$institution_id'");
$fetch_agent = mysqli_fetch_array($search_agent);
$agent_name = $fetch_agent['name'].' '.$fetch_agent['lname'];

$search_creator3 = mysqli_query($link, "SELECT * FROM borrowers WHERE (account = '$acn' OR virtual_acctno = '$acn')");
$fetch_creator3 = mysqli_fetch_array($search_creator3);
$creator_name3 = $fetch_creator3['lname'].' '.$fetch_creator3['fname'].' ('.$fetch_creator3['virtual_acctno'].')';

$utc_date = DateTime::createFromFormat('Y-m-d G:i:s',$date_time,new DateTimeZone(date_default_timezone_get()));
$acst_date = clone $utc_date; // we don't want PHP's default pass object by reference here
$acst_date->setTimeZone(new DateTimeZone('GMT+1'));
$correctdate = $acst_date->format('Y-m-d g:i A');
?>    
                <tr>
				<td><input id="optionsCheckbox" class="checkbox"  name="selector[]" type="checkbox" value="<?php echo $id; ?>"></td>
                <td><b style="font-size:14px;"><?php echo $row['invoice_code']; ?></b></td>
                <td width="150"><b><?php echo $creator_name3; ?></b></td>
                <td><b style="font-size:14px;"><?php echo $plan1; ?></b></td>
				<td style="font-size:14px;"><?php echo $row['subscription_code']; ?></td>
				<td><b style="font-size:14px;"><?php echo $row['reference_no']; ?></b><p></p>
                	Status: <?php echo ($row['status'] == "successful" ? '<span class="label bg-blue">Success</span>' : ($row['status'] == "pending" ? '<span class="label bg-orange">Pending</span>' : '<span class="label bg-red">failed</span>')); ?>
				</td>
                <td><b style="font-size:14px;"><?php echo $agent_name; ?></b></td>
				<td><?php echo $row['currency'].number_format($row['amount'],2,'.',','); ?></td>
				<td><b style="font-size:14px;"><i><?php echo $correctdate; ?></i></b></td>
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