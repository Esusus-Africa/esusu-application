<div class="row">
		
	       
		    <section class="content">  
	        <div class="box box-success">
            <div class="box-body">
              <div class="table-responsive">
             <div class="box-body">
<form method="post">
	<a href="my_savings_plan.php?tid=<?php echo $_SESSION['tid']; ?>&&acn=<?php echo $_SESSION['acctno']; ?>&&mid=NDA3"><button type="button" class="btn bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>"><i class="fa fa-mail-reply-all"></i>&nbsp;Back</button> </a>
	<hr>				  
			 <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th><input type="checkbox" id="select_all"/></th>
				  <th>Created By</th>
				  <th>Invoice Code</th>
				  <th>Plan Name</th>
                  <th>Subscription Code</th>
                  <th>Reference No.</th>
                  <th>Amount</th>
                  <th>Date/Time</th>
                 </tr>
                </thead>
                <tbody>
<?php
$id = $_GET['acn'];
$select = mysqli_query($link, "SELECT * FROM all_savingssub_transaction WHERE acn = '$acctno' ORDER BY id DESC") or die (mysqli_error($link));
if(mysqli_num_rows($select)==0)
{
echo "<div class='alert bg-".$myaltrow['theme_color'].' == "" ? blue : '.$myaltrow['theme_color']."'>No data found yet!.....Check back later!!</div>";
}
else{
while($row = mysqli_fetch_array($select))
{
$id = $row['id'];
$merchantid = $row['merchant_id'];
$vendorid = $row['vendorid'];
$date_time = $row['date_time'];
$plan_code = $row['next_pmt_date'];

$search_creator1 = mysqli_query($link, "SELECT * FROM member_settings WHERE companyid = '$merchantid'");
$fetch_creator1 = mysqli_fetch_array($search_creator1);
$creator_name1 = $fetch_creator1['cname'];

$search_creator2 = mysqli_query($link, "SELECT * FROM vendor_reg WHERE companyid = '$vendorid'");
$fetch_creator2 = mysqli_fetch_array($search_creator2);
$creator_name2 = $fetch_creator2['cname'];

$search_plan = mysqli_query($link, "SELECT * FROM savings_plan WHERE plan_code = '$plan_code'");
$fetch_plan = mysqli_fetch_array($search_plan);
$plan1 = $fetch_plan['plan_name'];

$search_plan2 = mysqli_query($link, "SELECT * FROM target_savings WHERE plan_code = '$plan_code'");
$fetch_plan2 = mysqli_fetch_array($search_plan2);
$plan2 = $fetch_plan2['plan_name'];

$plan_creator = ($vendorid == "" && $merchantid != "" ? $creator_name1 : ($vendorid != "" && $merchantid != "" ? $creator_name2 : 'Self'));
$plan_name = ($plan1 == "") ? $plan2 : $plan1;

$utc_date = DateTime::createFromFormat('Y-m-d G:i:s',$date_time,new DateTimeZone(date_default_timezone_get()));
$acst_date = clone $utc_date; // we don't want PHP's default pass object by reference here
$acst_date->setTimeZone(new DateTimeZone('GMT+1')); 
$correctdate = $acst_date->format('Y-m-d g:i A');
?>    
                <tr>
				<td><input id="optionsCheckbox" class="checkbox"  name="selector[]" type="checkbox" value="<?php echo $id; ?>"></td>
                <td width="150"><b><?php echo $plan_creator; ?></b></td>
                <td><b><?php echo $row['invoice_code']; ?></b></td>
                <td><b><?php echo $plan_name; ?></b></td>
				<td><?php echo $row['subscription_code']; ?></td>
				<td><b><?php echo $row['reference_no']; ?></b><br>
                	<?php echo ($row['status'] == "successful" ? '<span class="label bg-blue">Success</span>' : ($row['status'] == "pending" ? '<span class="label bg-orange">Pending</span>' : '<span class="label bg-red">failed</span>')); ?>
				</td>
				<td><?php echo $row['currency'].number_format($row['amount'],2,'.',','); ?></td>
				<td><?php echo $correctdate; ?></td>
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