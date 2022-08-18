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

    <p><a href="add_targetsavings.php?tid=<?php echo $_SESSION['tid']; ?>&&acn=<?php echo $_SESSION['acctno']; ?>&&mid=NDA3"><button type="button" class="btn bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>"><i class="fa fa-plus"></i>&nbsp;Add Target Savings</button></a></p> 
	<p><button type="submit" class="btn btn-flat bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>" name="delete"><i class="fa fa-times"></i>&nbsp;Delete/Unsubscribe</button></p>

<?php
}
else{
    ?>
    
	<a href="my_savings_plan.php?tid=<?php echo $_SESSION['tid']; ?>&&acn=<?php echo $_SESSION['acctno']; ?>&&mid=NDA3"><button type="button" class="btn bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>"><i class="fa fa-mail-reply-all"></i>&nbsp;Back</button></a>
	<a href="add_targetsavings.php?tid=<?php echo $_SESSION['tid']; ?>&&acn=<?php echo $_SESSION['acctno']; ?>&&mid=NDA3"><button type="button" class="btn bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>"><i class="fa fa-plus"></i>&nbsp;Add Target Savings</button></a> 
	<button type="submit" class="btn btn-flat bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>" name="delete"><i class="fa fa-times"></i>&nbsp;Delete/Unsubscribe</button>

<?php    
}
?>
	<hr>				  
			 <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th><input type="checkbox" id="select_all"/></th>
				  <th>Plan Code / Amount Invested</th>
				  <th>Target Title / Progress</th>
				  <th>Amount</th>
				  <th>Interval</th>
				  <th>Duration</th>
				  <th>Status</th>
                </tr>
                </thead>
                <tbody>
<?php
 $acn = $_SESSION['acctno'];
 $search_sub = mysqli_query($link, "SELECT * FROM target_savings WHERE acn = '$acn' ORDER BY id DESC");
 while($row_sub = mysqli_fetch_object($search_sub))
 {
 $plan_code = $row_sub->plan_code;
 $plan_id = $row_sub->plan_id;
 $txid = $row_sub->txid;
 $now = strtotime(date("Y-m-d h:m:s")); // or your date as well
 $date_time = strtotime($row_sub->date_time);
 $mature_date = strtotime($row_sub->mature_date);
 
 $search_trans = mysqli_query($link, "SELECT * FROM all_savingssub_transaction WHERE invoice_code = '$txid'");
 $fetch_trans = mysqli_fetch_array($search_trans);
 $scode = $fetch_trans['subscription_code'];
 
 $search_trans2 = mysqli_query($link, "SELECT SUM(amount), invoice_code FROM all_savingssub_transaction WHERE subscription_code = '$scode'");
 $fetch_trans2 = mysqli_fetch_array($search_trans2);
 $total_invested = $fetch_trans2['SUM(amount)'];
 
 $datediff = $now - $date_time;
 $current_stage = round($datediff / (60 * 60 * 24));
     
 $datediff2 = $mature_date - $date_time;
 $furture_stage = round($datediff2 / (60 * 60 * 24));
     
 $percentage_calc = ($current_stage / $furture_stage) * 100;
?>    
                <tr>
				<td><input id="optionsCheckbox" class="checkbox"  name="selector[]" type="checkbox" value="<?php echo $row_sub->plan_id; ?>"></td>
				<td style="font-size:14px;">
				    <p><?php echo $row_sub->plan_code; ?></p>
				    <div class="progress-group">
                      <p>
                          <b>Amount Invested:</b><br>
                          <?php echo $row_sub->currency.number_format($total_invested,2,'.',','); ?>
                      </p>
                    </div>
				</td>
				<td style="font-size:14px;">
				    <p><?php echo $row_sub->plan_name; ?></p>
				    <div class="progress-group">
                      <span class="float-right"><?php echo ($current_stage >= $furture_stage) ? "0" : ($furture_stage - $current_stage); ?> <b>day(s) left</b></span>
                      <div class="progress progress-sm">
                        <div class="progress-bar bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>" style="width: <?php echo ($percentage_calc >= 100) ? 100 : $percentage_calc; ?>%"></div>
                      </div>
                    </div>
				</td>
                <td style="font-size:14px;"><?php echo $row_sub->currency. number_format($row_sub->amount,2,'.',','); ?></td>
                <td style="font-size:14px;"><?php echo $row_sub->savings_interval; ?></td>
                <td style="font-size:14px;"><?php echo $row_sub->duration; ?> <?php echo (($row_sub->savings_interval === "daily" ? "day(s)" : ($row_sub->savings_interval === "weekly" ? "week(s)" : ($row_sub->savings_interval === "yearly" ? "year(s)" : ($row_sub->savings_interval === "quarterly" ? "quarter(s)" : "bi-anual"))))); ?></td>
				<td style="font-size:14px;"><?php echo ($row_sub->status == "Active" && $percentage_calc >= 100 ? '<a href="transfer_towallet.php?tid='.$_SESSION['tid'].'&&acn='.$_SESSION['acctno'].'&&mid=NDA3" class="btn bg-blue">Transfer to Wallet</a>' : ($row_sub->status == "Active" && $percentage_calc < 100 ? '<label class="bg-blue"> In Progress.. </label>' : ($row_sub->status == "Pending" ? '<a href="complete_target.php?tid='.$_SESSION['tid'].'&&acn='.$_SESSION['acctno'].'&&pid='.$plan_id.'&&mid=NDA3" class="btn bg-orange">Activate Target Savings</a>' : 'Not-Active'))); ?></td>
				</tr>
<?php 
}
?>

             </tbody>
            </table>   
            
            
            <?php
						if(isset($_POST['delete'])){
						    include ("../config/restful_apicalls.php");
						    $response = array();
						$idm = $_GET['id'];
							$id=$_POST['selector'];
							$N = count($id);
						if($id == ''){
						echo "<script>alert('Row Not Selected!!!'); </script>";	
						echo "<script>window.location='my_targetsavings.php?tid=".$_SESSION['tid']."&&acn=".$_SESSION['acctno']."&&mid=NDA3'; </script>";
							}
							else{
							for($i=0; $i < $N; $i++)
							{
							    
							    $systemset = mysqli_query($link, "SELECT * FROM systemset");
                            	$row1 = mysqli_fetch_object($systemset);
                            
                            	$search_restapi = mysqli_query($link, "SELECT * FROM restful_apisetup WHERE api_name = 'updatepaymentpln'");
                            	$fetch_restapi = mysqli_fetch_object($search_restapi);
                            	$api_url = $fetch_restapi->api_url.$id[$i]."/cancel";
                            	
                            	// Pass the plan's name, interval and amount
                            	$postdata =  array(
                            		'id'=> $id[$i],
                            		'seckey'	=> $row1->secret_key
                            	);
                            	
                            	$make_call = callAPI('POST', $api_url, json_encode($postdata));
                            	$response = json_decode($make_call, true);
                            	
                            	$search_target = mysqli_query($link, "SELECT * FROM target_savings WHERE plan_id = '$id[$i]'");
                            	$fetch_target = mysqli_fetch_array($search_target);
                            	
                            	$txid = $fetch_target['txid'];
                            	
                            	$search_restapi1 = mysqli_query($link, "SELECT * FROM restful_apisetup WHERE api_name = 'subscription'");
                            	$fetch_restapi1 = mysqli_fetch_object($search_restapi1);
                            	$api_url1 = $fetch_restapi1->api_url.$txid[$i]."/cancel?fetch_by_tx=1";
                            	
                            	// Pass the plan's name, interval and amount
                            	$postdata1 =  array(
                            		'id'=> $txid[$i],
                            		'fetch_by_tx'=> '1',
                            		'seckey'	=> $row1->secret_key
                            	);
                            	
                            	$make_call1 = callAPI('POST', $api_url1, json_encode($postdata1));
                            	$response1 = json_decode($make_call1, true);
                            	
                            	
                            	if($response['status'] == "success"){
                            	    
    								$result = mysqli_query($link, "DELETE FROM target_savings WHERE plan_id = '$id[$i]'");
    								echo "<script>alert('Action Completed Successfully!!!'); </script>";
    								echo "<script>window.location='my_targetsavings.php?tid=".$_SESSION['tid']."&&acn=".$_SESSION['acctno']."&&mid=NDA3'; </script>";
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