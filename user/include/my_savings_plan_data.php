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
            
            <a href="dashboard.php?tid=<?php echo $_SESSION['tid']; ?>&&acn=<?php echo $_SESSION['acctno']; ?>&&mid=<?php echo base64_encode("401"); ?>"><button type="button" class="btn bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>"><i class="fa fa-mail-reply-all"></i>&nbsp;Back</button></a>

<?php
}
else{
    ?>
			 <a href="dashboard.php?tid=<?php echo $_SESSION['tid']; ?>&&acn=<?php echo $_SESSION['acctno']; ?>&&mid=<?php echo base64_encode("401"); ?>"><button type="button" class="btn bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>"><i class="fa fa-mail-reply-all"></i>&nbsp;Back</button></a>
<?php    
}
?>	
	<hr>		
			  
			 <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th><input type="checkbox" id="select_all"/></th>
                  <th>Action</th>
                  <th>Plan</th>
                  <th>Sub. Code</th>
                  <th>Amount</th>
                  <th>Duration</th>
                  <th>Status</th>
                  <th>Created On</th>
                  <th>Maturity Date</th>
                  <th>Expected Amount</th>
                </tr>
                </thead>
                <tbody>
<?php
 $acn = $_SESSION['acctno'];
 $rec_type = ((isset($_GET['Takaful'])) ? 'Takaful' : ((isset($_GET['Health'])) ? 'Health' : ((isset($_GET['Savings'])) ? 'Savings' : ((isset($_GET['Donation'])) ? 'Donation' : 'Investment'))));
 $search_sub = mysqli_query($link, "SELECT * FROM savings_subscription WHERE rec_type = '$rec_type' AND (acn = '$acn' OR acn = '$bvirtual_acctno') ORDER BY id DESC");
 while($row_sub = mysqli_fetch_array($search_sub))
 {
     $id = $row_sub['id'];
     $plan_code = $row_sub['plan_code'];
     $new_plancode = $row_sub['new_plancode'];
     $scode = $row_sub['subscription_code'];
     $reference_no = $row_sub['reference_no'];
     $now = strtotime(date("Y-m-d h:m:s")); // or your date as well
     $date_time = strtotime($row_sub['date_time']);
     $mature_date = strtotime($row_sub['mature_date']);
     $sstatus = $row_sub['status'];
     $sub_type = $row_sub['sub_type'];
     $part_withdrawal = $row_sub['part_withdrawal'];
     $sinterval = $row_sub['savings_interval'];
     
     $utc_date = DateTime::createFromFormat('Y-m-d G:i:s',$row_sub['date_time'],new DateTimeZone(date_default_timezone_get()));
     $acst_date = clone $utc_date; // we don't want PHP's default pass object by reference here
     $acst_date->setTimeZone(new DateTimeZone('GMT+1'));
     $correctdate = $acst_date->format('Y-m-d g:i A');
    
     $search_subdetails = mysqli_query($link, "SELECT * FROM savings_plan WHERE plan_code = '$plan_code'");
     $num_splan = mysqli_num_rows($search_subdetails);
     $fetch_subdetails = mysqli_fetch_array($search_subdetails);
     
     $search_splan1 = mysqli_query($link, "SELECT * FROM target_savings WHERE plan_code = '$plan_code'");
     $num_splan1 = mysqli_num_rows($search_splan1);
     $fetch_splan1 = mysqli_fetch_array($search_splan1);
      
     $duratn = ($num_splan == 1 && $num_splan1 == 0) ? $fetch_subdetails['duration'] : $fetch_splan1['duration'];
     $pname = ($num_splan == 1 && $num_splan1 == 0) ? $fetch_subdetails['plan_name'] : $fetch_splan1['plan_name'];
     $currency = ($num_splan == 1 && $num_splan1 == 0) ? $fetch_subdetails['currency'] : $fetch_splan1['currency'];
     
     $search_trans = mysqli_query($link, "SELECT SUM(amount), invoice_code FROM all_savingssub_transaction WHERE status = 'successful' AND subscription_code = '$scode' AND subscription_code != ''");
     $fetch_trans = mysqli_fetch_array($search_trans);
     $txid = $fetch_trans['invoice_code'];
     $total_invested = $fetch_trans['SUM(amount)'];

     $search_transnum = mysqli_query($link, "SELECT * FROM all_savingssub_transaction WHERE status = 'successful' AND subscription_code = '$scode'");
     $fetchTrans_num = mysqli_num_rows($search_transnum);
     
     $datediff = $now - $date_time;
     $current_stage = round($datediff / (60 * 60 * 24));
     
     $datediff2 = $mature_date - $date_time;
     $furture_stage = (round($datediff2 / (60 * 60 * 24))) - 2;
     
     $percentage_calc = ($sinterval == "ONE-OFF") ? 100 : (($current_stage / $furture_stage) * 100);
?>    
                <tr>
    				<td><input id="optionsCheckbox" class="checkbox"  name="selector[]" type="checkbox" value="<?php echo $row_sub['id']; ?>"></td>
    				<td align="center">
    				<div class="btn-group">
                <div class="btn-group">
                  <button type="button" class="btn bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?> btn-flat dropdown-toggle" data-toggle="dropdown">
                    <span class="caret"></span>
                  </button>
                  <ul class="dropdown-menu">
                    <?php echo ($sstatus == "Disabled" && $sub_type == 'Auto') ? '<li><p><a href="enable_msub.php?tid='.$_SESSION['tid'].'&&txid='.$txid.'&&pcode='.$plan_code.'&&scode='.$scode.'&&mid='.((isset($_GET['Takaful'])) ? 'NTA0' : ((isset($_GET['Savings'])) ? 'NTAw' : ((isset($_GET['Donation'])) ? 'NjA0' : 'NDA3'))).''.((isset($_GET['Takaful'])) ? '&&Takaful' : ((isset($_GET['Health'])) ? '&&Health' : ((isset($_GET['Savings'])) ? '&&Savings' : ((isset($_GET['Donation'])) ? '&&Donation' : '')))).'" class="btn btn-default btn-flat"><i class="fa fa-check">&nbsp;Enable Plan</i></a></p></li>' : ''; ?>
                    <?php echo ($sstatus == "Approved" && $sub_type == 'Auto') ? '<li><p><a href="disable_msub.php?tid='.$_SESSION['tid'].'&&txid='.$txid.'&&pcode='.$plan_code.'&&scode='.$scode.'&&mid='.((isset($_GET['Takaful'])) ? 'NTA0' : ((isset($_GET['Savings'])) ? 'NTAw' : ((isset($_GET['Donation'])) ? 'NjA0' : 'NDA3'))).''.((isset($_GET['Takaful'])) ? '&&Takaful' : ((isset($_GET['Health'])) ? '&&Health' : ((isset($_GET['Savings'])) ? '&&Savings' : ((isset($_GET['Donation'])) ? '&&Donation' : '')))).'" class="btn btn-default btn-flat"><i class="fa fa-times">&nbsp;Disable Plan</i></a></p></li>' : ''; ?>
                    <?php echo ($sstatus == "Approved" && $sub_type == 'Manual' && $fetchTrans_num != $duratn) ? '<li><p><a href="stop_msub.php?tid='.$_SESSION['tid'].'&&scode='.$scode.'&&mid='.((isset($_GET['Takaful'])) ? 'NTA0' : ((isset($_GET['Savings'])) ? 'NTAw' : ((isset($_GET['Donation'])) ? 'NjA0' : 'NDA3'))).''.((isset($_GET['Takaful'])) ? '&&Takaful' : ((isset($_GET['Health'])) ? '&&Health' : ((isset($_GET['Savings'])) ? '&&Savings' : ((isset($_GET['Donation'])) ? '&&Donation' : '')))).'" class="btn btn-default btn-flat"><i class="fa fa-times">&nbsp;Stop Plan</i></a></p></li>' : ''; ?>
                    <?php //echo ($sstatus == "Approved" && $percentage_calc >= 100 && ($part_withdrawal == "Yes" || $part_withdrawal == "No")) ? '<li><p><a href="make_wrequest.php?tid='.$_SESSION['tid'].'&&acn='.$acctno.'&&sid='.$row_sub['id'].'&&mid='.base64_encode("490").'" class="btn btn-default btn-flat"><i class="fa fa-download">&nbsp;Withdrawal Request</i></a></p></li>' : ''; ?>
                    <?php //echo ($sstatus == "Approved" && $percentage_calc < 100 && $part_withdrawal == "Yes") ? '<li><p><a href="make_wrequest.php?tid='.$_SESSION['tid'].'&&acn='.$acctno.'&&sid='.$row_sub['id'].'&&mid='.base64_encode("490").'" class="btn btn-default btn-flat"><i class="fa fa-download">&nbsp;Withdrawal Request</i></a></p></li>' : ''; ?>
                    <?php echo ($sstatus == "Approved" && $fetchTrans_num != $duratn) ? '<li><p><a href="make_payment.php?tid='.$_SESSION['tid'].'&&id='.$id.'&&acn='.$acctno.'&&mid='.((isset($_GET['Takaful'])) ? 'NTA0' : ((isset($_GET['Savings'])) ? 'NTAw' : ((isset($_GET['Donation'])) ? 'NjA0' : 'NDA3'))).'&&pcode='.$new_plancode.''.((isset($_GET['Takaful'])) ? '&&Takaful' : ((isset($_GET['Health'])) ? '&&Health' : ((isset($_GET['Savings'])) ? '&&Savings' : ((isset($_GET['Donation'])) ? '&&Donation' : '')))).'" class="btn btn-default btn-flat"><i class="fa fa-money">&nbsp;Make Deposit</i></a></p></li>' : ''; ?>
                    <?php echo ($sstatus == "Pending" && $scode == "" && $sub_type == "Auto") ? '<li><p><a href="tokenize_card.php?tid='.$_SESSION['tid'].'&&id='.$id.'&&acn='.$acctno.'&&mid='.((isset($_GET['Takaful'])) ? 'NTA0' : ((isset($_GET['Savings'])) ? 'NTAw' : ((isset($_GET['Donation'])) ? 'NjA0' : 'NDA3'))).'&&pcode='.$new_plancode.''.((isset($_GET['Takaful'])) ? '&&Takaful' : ((isset($_GET['Health'])) ? '&&Health' : ((isset($_GET['Savings'])) ? '&&Savings' : ((isset($_GET['Donation'])) ? '&&Donation' : '')))).'" class="btn btn-default btn-flat"><i class="fa fa-check">&nbsp;Activate!</i></a></p></li>' : ''; ?>
                    <li><p><a href="walletVerifiedInfo.php?tid=<?php echo $_SESSION['tid']; ?>&&id=<?php echo $id; ?>&&uid=<?php echo $acctno; ?>&&mid=<?php echo ((isset($_GET['Takaful'])) ? 'NTA0' : ((isset($_GET['Savings'])) ? 'NTAw' : ((isset($_GET['Donation'])) ? 'NjA0' : 'NDA3'))); ?>&&pcode=<?php echo $plan_code; ?><?php echo ((isset($_GET['Takaful'])) ? '&&Takaful' : ((isset($_GET['Health'])) ? '&&Health' : ((isset($_GET['Savings'])) ? '&&Savings' : ((isset($_GET['Donation'])) ? '&&Donation' : '')))); ?>" target="_blank" class="btn btn-default btn-flat"><i class="fa fa-search">&nbsp;KYC / Document</i></a></p></li>
                    <?php echo ($sstatus == "Approved") ? '<li><p><a href="downloadPCert1.php?tid='.$_SESSION['tid'].'&&subId='.$id.'&&acn='.$acctno.'&&mid='.((isset($_GET['Takaful'])) ? 'NTA0' : ((isset($_GET['Savings'])) ? 'NTAw' : ((isset($_GET['Donation'])) ? 'NjA0' : 'NDA3'))).'&&oPid='.$row_sub['origin_planid'].''.((isset($_GET['Takaful'])) ? '&&Takaful' : ((isset($_GET['Health'])) ? '&&Health' : ((isset($_GET['Savings'])) ? '&&Savings' : ((isset($_GET['Donation'])) ? '&&Donation' : '')))).'" class="btn btn-default btn-flat" target="_blank"><i class="fa fa-download">&nbsp;Download Certificate</i></a></p></li>' : ''; ?>
                  </ul>
                </div>
            </div>
    				</td> 
            <td style="font-size:14px;"><?php echo $pname; ?></td>
    				<td style="font-size:14px;">
    				    <?php echo $row_sub['subscription_code']; ?>
    				    <div class="progress-group">
                          <p> 
                              <b>Amount Paid:</b><br>
                              <?php echo $currency.number_format($total_invested,2,'.',','); ?>
                          </p>
                          <span class="float-right"><?php echo ($current_stage >= $furture_stage) ? "0" : ($furture_stage - $current_stage); ?> <b>day(s) left</b></span>
                          <div class="progress progress-sm">
                            <div class="progress-bar bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>" style="width: <?php echo ($percentage_calc >= 100) ? 100 : $percentage_calc; ?>%"></div>
                          </div>
                        </div>
    				</td>
            <td style="font-size:14px;"><?php echo $currency.number_format($row_sub['amount'],2,'.',','); ?></td>
    				<td style="font-size:14px;"><?php echo (($row_sub['savings_interval'] === "daily" ? $row_sub['duration']." day(s)" : ($row_sub['savings_interval'] === "weekly" ? $row_sub['duration']." week(s)" : ($row_sub['savings_interval'] === "yearly" ? $row_sub['duration']." year(s)" : ($row_sub['savings_interval'] === "monthly" ? $row_sub['duration']." month(s)" : "ONE-OFF"))))); ?></td>
    				<td style="font-size:14px;"><?php echo $row_sub['status']; ?></td>
    				<td style="font-size:14px;"><?php echo $correctdate; ?></td>
    				<td style="font-size:14px;"><b><?php echo ($row_sub['mature_date'] == "") ? "---" : date("Y-m-d g:i A", strtotime($row_sub['mature_date'])); ?></b></td>
    				<td style="font-size:14px;"><?php echo ($row_sub['amt_plusInterest'] == "Undefine" || $row_sub['amt_plusInterest'] == "") ? "TBD" : $currency.number_format($row_sub['amt_plusInterest'],2,'.',','); ?></td>
				</tr>
<?php 
} 
?>

             </tbody>
                </table> 
						
</form>
				

              </div>

	
</div>	
</div>
</div>	


			
</div>