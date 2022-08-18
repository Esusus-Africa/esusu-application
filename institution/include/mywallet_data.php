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

<?php
if($transfer_fund == 1 && $irole != 'tqwjr_product_marketer')
{
  ?>
 <button type="button" class="btn btn-flat bg-white" align="left" disabled>&nbsp;<b>Supper Wallet:</b>&nbsp;
<strong class="alert bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">
<?php
echo "<span id='wallet_balance'>".$icurrency.number_format($iassigned_walletbal,2,'.',',')."</span>";
?> 
</strong>
  </button>
  <hr>
<button type="button" class="btn btn-flat bg-white" align="left" disabled>&nbsp;<b>Transfer Wallet:</b>&nbsp;
    <strong class="alert bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">
    <?php
    echo $icurrency.number_format($itransfer_balance,2,'.',',');
    ?> 
    </strong>
</button>
<?php
}
else{
?>

<button type="button" class="btn btn-flat bg-white" align="left" disabled>&nbsp;<b>Transfer Wallet:</b>&nbsp;
    <strong class="alert bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">
    <?php
    echo $icurrency.number_format($itransfer_balance,2,'.',',');
    ?> 
    </strong>
</button>

<?php
}
?>

</form>

<form>
<?php
$system_set = mysqli_query($link, "SELECT * FROM systemset") or die ("Error: " . mysqli_error($link));
$row1 = mysqli_fetch_object($system_set);
?>
 <?php
if($add_fund == '1')
{
?>

  <a href="fund_wallet.php?id=<?php echo $_SESSION['tid']; ?>&&mid=NDA0"><button type="button" class="btn bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>"><i class="fa fa-refresh"></i> Fund Wallet</button></a>

<?php
}
else{
  echo '';
}
?><?php //echo ($recharge_airtime_or_data == 1) ? ' <a href="pay_bills.php?id='.$_SESSION['tid'].'&&mid=NDA0"><button type="button" class="btn btn-flat bg-'.$myaltrow['theme_color'].' == "" ? blue : '.$myaltrow['theme_color'].'"><i class="fa fa-globe"></i>&nbsp;Pay Bills</button></a>' : ''; ?>
</form>

<?php
}
else{
    //Your content or code for desktop or computers devices
?>
<a href="mywallet.php?id=<?php echo $_SESSION['tid']; ?>&&mid=NDA0&&tab=<?php echo $_GET['tab']; ?>"><button type="button" class="btn btn-flat bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>"><i class="fa fa-refresh"></i>&nbsp;Refresh</button></a> 
<?php echo ($transfer_fund == 1) ? '<a href="transfer_fund.php?id='.$_SESSION['tid'].'&&mid=NDA0&&tab=tab_1"><button type="button" class="btn btn-flat bg-'.(($myaltrow['theme_color'] == "") ? "blue" : $myaltrow['theme_color']).'" align="left"><i class="fa fa-cloud-upload"></i>&nbsp;Transfer Fund</button></a>' : ''; ?> 
<?php echo ($add_transfer_recipient == 1) ? '<a href="create_transfer_recipient.php?id='.$_SESSION['tid'].'&&mid=NDA0"><button type="button" class="btn btn-flat bg-'.(($myaltrow['alternate_color'] == "") ? 'orange' : $myaltrow['alternate_color']).'"><i class="fa fa-user"></i>&nbsp;Add Recipient</button></a>' : ''; ?> 
<?php echo ($view_all_recipient == 1) ? '<a href="view_transfer_recipient.php?id='.$_SESSION['tid'].'&&mid=NDA0"><button type="button" class="btn btn-flat bg-'.(($myaltrow['theme_color'] == "") ? "blue" : $myaltrow['theme_color']).'"><i class="fa fa-users"></i>&nbsp;All Recipients</button></a>' : ''; ?>
<?php
if($transfer_fund == 1 && $irole != 'tqwjr_product_marketer')
{
  ?>
<button type="button" class="btn btn-flat bg-white" align="left" disabled>&nbsp;<b>Super Wallet:</b>&nbsp;
<strong class="alert bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">
<?php
echo "<span id='wallet_balance'>".$icurrency.number_format($iassigned_walletbal,2,'.',',')."</span>";
?> 
</strong>
  </button>
<button type="button" class="btn btn-flat bg-white" align="left" disabled>&nbsp;<b>Transfer Wallet:</b>&nbsp;
    <strong class="alert bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">
    <?php
    echo $icurrency.number_format($itransfer_balance,2,'.',',');
    ?> 
    </strong>
</button>
<?php
}
else{
?>

<button type="button" class="btn btn-flat bg-white" align="left" disabled>&nbsp;<b>Transfer Wallet:</b>&nbsp;
    <strong class="alert bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">
    <?php
    echo $icurrency.number_format($itransfer_balance,2,'.',',');
    ?> 
    </strong>
</button>

<?php
}
?>
<form>
<?php
$system_set = mysqli_query($link, "SELECT * FROM systemset") or die ("Error: " . mysqli_error($link));
$row1 = mysqli_fetch_object($system_set);
?>
 <?php
if($add_fund == '1')
{
?>

  <a href="fund_wallet.php?id=<?php echo $_SESSION['tid']; ?>&&mid=NDA0"><button type="button" class="btn bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>"><i class="fa fa-refresh"></i> Fund Wallet</button></a>

<?php
}
else{
  echo '';
}
?><?php //echo ($recharge_airtime_or_data == 1) ? ' <a href="pay_bills.php?id='.$_SESSION['tid'].'&&mid=NDA0"><button type="button" class="btn btn-flat bg-'.(($myaltrow['theme_color'] == "") ? "blue" : $myaltrow['theme_color']).'"><i class="fa fa-globe"></i>&nbsp;Pay Bills</button></a>' : ''; ?>
</form>
  
<?php
}
?>
  <hr>    
 
<div class="col-md-14">
            <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
             <li <?php echo ($_GET['tab'] == 'tab_1') ? "class='active'" : ''; ?>><a href="mywallet.php?tid=<?php echo $_SESSION['tid']; ?>&&mid=NDA0&&tab=tab_1">View Wallet History</a></li>

             <!--<li <?php //echo ($_GET['tab'] == 'tab_2') ? "class='active'" : ''; ?>><a href="mywallet.php?tid=<?php //echo $_SESSION['tid']; ?>&&mid=NDA0&&tab=tab_2">View Transfer History</a></li>-->

             <?php echo ($iussd_prefixStatus == "Active" && ($irole == "agent_manager" || $irole == "institution_super_admin" || $irole == "merchant_super_admin")) ? '<li '.(($_GET['tab'] == 'tab_3') ? "class='active'" : '').'><a href="mywallet.php?tid='.$_SESSION['tid'].'&&mid=NDA0&&tab=tab_3">USSD Session History</a></li>' : ''; ?>

             <?php echo ($ipos_manager == "On" && $terminal_report == "1") ? '<li '.(($_GET['tab'] == 'tab_4') ? "class='active'" : '').'><a href="terminal_report.php?tid='.$_SESSION['tid'].'&&mid=NDA0&&tab=tab_4">Terminal/POS History</a></li>' : ''; ?>

            </ul>
             <div class="tab-content">

<?php
if(isset($_GET['tab']) == true)
{
  $tab = $_GET['tab'];
  if($tab == 'tab_1')
  {
 
  ?>
             <div class="tab-pane <?php echo ($_GET['tab'] == 'tab_1') ? 'active' : ''; ?>" id="tab_1">

             <div class="box-body">
           
             <div class="box-body">
                 
	            <div class="form-group">
                  <label for="" class="col-sm-1 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">From</label>
                  <div class="col-sm-3">
                  <input name="dfrom" type="date" id="startDate" class="form-control" placeholder="To Date: 2018-05-01" required>
				  <span style="color: <?php echo ($myaltrow['alternate_color'] == '') ? 'blue' : $myaltrow['alternate_color']; ?>;">Date format: 2018-05-01</span>
                  </div>
				  
				  <label for="" class="col-sm-1 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">End Date</label>
                  <div class="col-sm-3">
                  <input name="dto" type="date" id="endDate" class="form-control" placeholder="To Date: 2018-05-24" required>
				  <span style="color: <?php echo ($myaltrow['alternate_color'] == '') ? 'blue' : $myaltrow['alternate_color']; ?>;">Date format: 2018-05-24</span>
                  </div>
                  
                  <label for="" class="col-sm-1 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Type</label>
                  <div class="col-sm-3">
                  <select name="ttype" id="transType" class="form-control select2" style="width:100%" required>
                    <option value="" selected="selected">Filter By Transaction Type...</option>
                    <?php echo ($irole == "agent_manager" || $irole == "institution_super_admin" || $irole == "merchant_super_admin") ? '<option value="all">All Transaction</option>' : ''; ?>
                    <?php echo ($irole != "agent_manager" && $irole != "institution_super_admin" && $irole != "merchant_super_admin") ? '<option value="All1">All My Transaction</option>' : ''; ?>
                    <option value="Airtime - WEB">Airtime - WEB</option>
                    <option value="Airtime - POS">Airtime - POS</option>
                    <option value="Databundle - WEB">Databundle - WEB</option>
                    <option value="Databundle - POS">Databundle - POS</option>
                    <option value="Commission - WEB">VAS Commission - WEB</option>
                    <option value="Commission - POS">VAS Commission - POS</option>
                    <option value="tv - WEB">tv - WEB</option>
                    <option value="internet - WEB">internet - WEB</option>
                    <option value="Prepaid - WEB">Prepaid - WEB</option>
                    <option value="Postpaid - WEB">Postpaid - WEB</option>
                    <option value="waec - WEB">waec - WEB</option>
                    <option value="Airtime - USSD">Airtime - USSD</option>
                    <option value="Databundle - USSD">Databundle - USSD</option>
                    <option value="Commission - USSD">VAS Commission - USSD</option>
                    <option value="ACCOUNT_TRANSFER">Account Transfer</option>
                    <option value="card">Card Payment</option>
                    <option value="Stamp Duty">Stamp Duty</option>
                    <option value="Card_Withdrawal">Card Withdrawal</option>
                    <option value="Card_Reversal">Card Reversal</option>
                    <option value="Cardless_Withdrawal">Cardless Withdrawal</option>
                    <option value="Report Charges">Report Charges</option>
                    <option value="Topup-Prepaid_Card">PrepaidCard Topup</option>
                    <option value="PrepaidCard_Commission">PrepaidCard Commission</option>
                    <option value="p2p-transfer">P2P-Transfer</option>
                    <option value="BVN_Charges">BVN Charges</option>
                    <option value="DD_Activation">Direct Debit Activation</option>
                    <option value="VerveCard_Verification">VerveCard Verification</option>
                    <option value="USSD">USSD</option>
                    <option value="POS">POS</option>
                    <option value="TERMINAL_COMMISSION">Terminal Commission</option>
                    <option value="TRANSFER_COMMISSION">Transfer Commission</option>
                    <option value="BANK_TRANSFER">Inter-bank Transfer</option>
                    <option value="Prefund_Balance">Prefund Balance</option>
                    <option value="Card_Commission">Card Commission</option>
                    <option value="Terminal_Activation_Fee">Terminal Activation Fee</option>
                    <option value="TERMINAL_COMMISSION_REVERSAL">Terminal Commission Reversal</option>
                    <option value="Charges">Other Charges</option>
                    <option value="Billpayment">Billpayment POS</option>
                  </select>
                  </div>
                </div>

                </div>
                
                
            <hr>
            <div class="table-responsive">
			    <table id="fetch_wallet_transaction_data" class="table table-bordered table-striped">
                <thead>
                <tr>
                <th><input type="checkbox" id="select_all"/></th>
                  <th>RefID</th>
                  <th>Recipient</th>
                  <th>Purpose</th>
                  <th>Currency</th>
                  <th>Credit</th>
            	    <th>Debit</th>
            	    <th>Balance</th>
            	    <th>Initiated By</th>
                  <th>Status</th>
                  <th>Date/Time</th>
                </tr>
                </thead>
                </table>
            </div>
			 
			
       
             </div>
             
             </div>
             <!-- /.tab-pane -->             
<?php
  }
  elseif($tab == 'tab_2')
  {
  ?>
            <div class="tab-pane <?php echo ($_GET['tab'] == 'tab_2') ? 'active' : ''; ?>" id="tab_2">
        <!--
        <div class="box-body">
            
            <div class="box-body">
                 
	            <div class="form-group">
                  <label for="" class="col-sm-1 control-label" style="color:<?php //echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">From</label>
                  <div class="col-sm-3">
                  <input name="dfrom" type="date" id="startDate" class="form-control" placeholder="To Date: 2018-05-01" required>
				  <span style="color: <?php //echo ($myaltrow['alternate_color'] == '') ? 'blue' : $myaltrow['alternate_color']; ?>;">Date format: 2018-05-01</span>
                  </div>
				  
				  <label for="" class="col-sm-1 control-label" style="color:<?php //echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">End Date</label>
                  <div class="col-sm-3">
                  <input name="dto" type="date" id="endDate" class="form-control" placeholder="To Date: 2018-05-24" required>
				  <span style="color: <?php //echo ($myaltrow['alternate_color'] == '') ? 'blue' : $myaltrow['alternate_color']; ?>;">Date format: 2018-05-24</span>
                  </div>
                  
                  <label for="" class="col-sm-1 control-label" style="color:<?php //echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Type</label>
                  <div class="col-sm-3">
                  <select name="ttype" id="transType" class="form-control select2" style="width:100%" required>
                    <option value="" selected="selected">Filter Transaction...</option>
                    <?php //echo ($view_all_transaction === "1" && $individual_transaction_records != "1") ? '<option value="all">All Transfer</option>' : ''; ?>
                    <?php //echo ($view_all_transaction != "1" && $individual_transaction_records === "1") ? '<option value="'.$iuid.'">All My Transfer History</option>' : ''; ?>
                    
                    <option disabled>Filter By Staff / Sub-agent</option>
                    <?php
                    //($list_employee === "1" && $list_branch_employee != "1") ? $get = mysqli_query($link, "SELECT * FROM user WHERE created_by = '$institution_id' ORDER BY id") or die (mysqli_error($link)) : "";
                    //($list_employee != "1" && $list_branch_employee === "1") ? $get = mysqli_query($link, "SELECT * FROM user WHERE created_by = '$institution_id' AND id = '$iuid' ORDER BY id") or die (mysqli_error($link)) : "";
                    //while($rows = mysqli_fetch_array($get))
                    //{
                    ?>
                    <option value="<?php //echo $rows['id']; ?>"><?php //echo $rows['name'].' '.$rows['lname'].' '.$rows['mname'].' ['.$rows['virtual_acctno'].']'; ?></option>
                    <?php //} ?>

                    <option disabled>Filter By Customer</option>
                    <?php
                    //($individual_customer_records != "1" && $branch_customer_records != "1" && $view_all_customers === '1') ? $get = mysqli_query($link, "SELECT * FROM borrowers WHERE branchid = '$institution_id' ORDER BY id") or die (mysqli_error($link)) : "";
                    //($individual_customer_records === "1" && $branch_customer_records != "1" && $view_all_customers != '1') ? $get = mysqli_query($link, "SELECT * FROM borrowers WHERE branchid = '$institution_id' AND lofficer = '$iuid' ORDER BY id") or die (mysqli_error($link)) : "";
                    //($individual_customer_records != "1" && $branch_customer_records === "1" && $view_all_customers != '1') ? $get = mysqli_query($link, "SELECT * FROM borrowers WHERE branchid = '$institution_id' AND sbranchid = '$isbranchid' ORDER BY id") or die (mysqli_error($link)) : "";
                    //while($rows = mysqli_fetch_array($get))
                    //{
                    ?>
                    <option value="<?php //echo $rows['account']; ?>"><?php //echo $rows['account'].' - '.$rows['lname'].' '.$rows['fname'].' '.$rows['mname']; ?></option>
                    <?php //} ?>
                  </select>
                  </div>
                </div>

            </div>
          

          <hr>
          <div class="table-responsive">
			      <table id="bank_transfer_transaction_data" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th><input type="checkbox" id="select_all"/></th>
                  <th>RefID</th>
                  <th>Recipient</th>
                  <th>Amount</th>
                  <th>Charges</th>
                  <th>Balance</th>
                  <th>Initiated By</th>
                  <th>Status</th>
                  <th>Date/Time</th>
                </tr>
                </thead>
            </table>
          </div>
          
                  
             </div>-->
             
             </div>
             <!-- /.tab-pane --> 
             
<?php
  }
  elseif($tab == 'tab_3')
  {
  ?>
            <div class="tab-pane <?php echo ($_GET['tab'] == 'tab_3') ? 'active' : ''; ?>" id="tab_3">

        <div class="box-body">
            
            <form class="form-horizontal" method="post" enctype="multipart/form-data">
           
             <div class="box-body">
                 
	            <div class="form-group">
                  <label for="" class="col-sm-1 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">From</label>
                  <div class="col-sm-5">
                  <input name="dfrom" type="date" id="myStartDate" class="form-control" placeholder="To Date: 2018-05-01" required>
				  <span style="color: <?php echo ($myaltrow['alternate_color'] == '') ? 'blue' : $myaltrow['alternate_color']; ?>;">Date format: 2018-05-01</span>
                  </div>
				  
				  <label for="" class="col-sm-1 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">End Date</label>
                  <div class="col-sm-5">
                  <input name="dto" type="date" id="myEndDate" class="form-control" placeholder="To Date: 2018-05-24" required>
				  <span style="color: <?php echo ($myaltrow['alternate_color'] == '') ? 'blue' : $myaltrow['alternate_color']; ?>;">Date format: 2018-05-24</span>
                  </div>
                </div>
                
            </div>
            
                  
              <hr>
              <div class="table-responsive">
              <table id="fetch_ussd_transaction_data" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th><input type="checkbox" id="select_all"/></th>
                  <th>Date</th>
                  <th>Service Code</th>
                  <th>Phone Number</th>
                  <th>Hops</th>
                  <th>Duration</th>
                  <th>Cost</th>
                  <th>Status</th>
                 </tr>
                </thead>
                </table> 
            </div>
            
            
       </form>
       
             </div>
             
             </div>
             <!-- /.tab-pane --> 

<?php 
} 
} 
?>
</div>
</div>
</div>        
</form>       

              </div>


  
</div>  
</div>
</div> 
<hr>

</div>