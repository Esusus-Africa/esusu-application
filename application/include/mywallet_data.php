<div class="row">     
        <section class="content">  
          <div class="box box-success">
            <div class="box-body">
              <div class="table-responsive">
             <div class="box-body">
<form method="post">
<a href="mywallet.php?id=<?php echo $_SESSION['tid']; ?>&&mid=NDA0"><button type="button" class="btn btn-flat bg-orange"><i class="fa fa-refresh"></i>&nbsp;Refresh</button></a> 
<?php echo ($backend_add_transfer_recipient == 1) ? '<a href="create_transfer_recipient?id='.$_SESSION['tid'].'&&mid=NDA0"><button type="button" class="btn btn-flat bg-orange"><i class="fa fa-user"></i>&nbsp;Add Recipient</button></a>' : ''; ?> 
<?php echo ($backend_view_all_recipient == 1) ? '<a href="view_transfer_recipient?id='.$_SESSION['tid'].'&&mid=NDA0"><button type="button" class="btn btn-flat bg-blue"><i class="fa fa-users"></i>&nbsp;All Recipients</button></a>' : ''; ?>

<?php
if($backend_add_fund == '1')
{
?>

    <a href="fund_superwallet?id=<?php echo $_SESSION['tid']; ?>&&mid=NDA0"><button name="FundWallet" type="button" class="btn bg-blue"><i class="fa fa-plus"></i> <b>Fund Wallet</b></button></a>
    <hr>
    <button type="button" class="btn btn-flat bg-white" align="left" disabled>&nbsp;<b>Central Verve Bal:</b>&nbsp;
    <strong class="alert bg-blue">
    
    <span id='vervewallet_balance'>Loading...</span>
    
    </strong>
      </button>
      
    <button type="button" class="btn btn-flat bg-white" align="left" disabled>&nbsp;<b>Super Wallet Bal:</b>&nbsp;
    <strong class="alert bg-blue">
    
    <?php
    $search_iNGN = mysqli_query($link, "SELECT SUM(wallet_balance) FROM institution_data");
    $get_i = mysqli_fetch_array($search_iNGN);
    $i_wb = $get_i['SUM(wallet_balance)'];
    
    $search_cNGN = mysqli_query($link, "SELECT SUM(wallet_balance) FROM cooperatives");
    $get_c = mysqli_fetch_array($search_cNGN);
    $c_wb = $get_c['SUM(wallet_balance)'];
    
    $total_NGN = number_format(($i_wb + $c_wb),2,'.',',');
    
    echo $fetchsys_config['currency'].$total_NGN;
    ?>
    
    </strong>
      </button>
     
    <button type="button" class="btn btn-flat bg-white" align="left" disabled>&nbsp;<b>Transfer Wallet Bal:</b>&nbsp;
    <strong class="alert bg-blue">
    
    <?php 
    $selectUB = mysqli_query($link, "SELECT SUM(transfer_balance) FROM user") or die (mysqli_error($link));
    $fetchUB = mysqli_fetch_array($selectUB);
    $ao_wb = $fetchUB['SUM(transfer_balance)'];
    
    $search_boNGN = mysqli_query($link, "SELECT SUM(wallet_balance) FROM borrowers");
    $get_bo = mysqli_fetch_array($search_boNGN);
    $bo_wb = $get_bo['SUM(wallet_balance)'];
    
    $totalBal = $ao_wb + $bo_wb;
    
    echo $fetchsys_config['currency'].number_format($totalBal,2,'.',',');
    ?>
    
    </strong>
    </button>

<?php
}
else{
  echo '';
}
?>

</form>  
  
  <hr> 
  
<div class="col-md-14">
            <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
             <li <?php echo ($_GET['tab'] == 'tab_1') ? "class='active'" : ''; ?>><a href="mywallet.php?tid=<?php echo $_SESSION['tid']; ?>&&mid=NDA0&&tab=tab_1">All Wallet History</a></li>

             <!--<li <?php //echo ($_GET['tab'] == 'tab_2') ? "class='active'" : ''; ?>><a href="mywallet.php?tid=<?php //echo $_SESSION['tid']; ?>&&mid=NDA0&&tab=tab_2">Bank Transfer History</a></li>-->

             <li <?php echo ($_GET['tab'] == 'tab_3') ? "class='active'" : ''; ?>><a href="mywallet.php?tid=<?php echo $_SESSION['tid']; ?>&&mid=NDA0&&tab=tab_3">Institution Super Wallet</a></li>
             
             <li <?php echo ($_GET['tab'] == 'tab_4') ? "class='active'" : ''; ?>><a href="mywallet.php?tid=<?php echo $_SESSION['tid']; ?>&&mid=NDA0&&tab=tab_4">Sub-Agent Transfer Wallet</a></li>

             <li <?php echo ($_GET['tab'] == 'tab_5') ? "class='active'" : ''; ?>><a href="mywallet.php?tid=<?php echo $_SESSION['tid']; ?>&&mid=NDA0&&tab=tab_5">Customer Transfer Wallet</a></li>

             <li <?php echo ($_GET['tab'] == 'tab_6') ? "class='active'" : ''; ?>><a href="mywallet.php?tid=<?php echo $_SESSION['tid']; ?>&&mid=NDA0&&tab=tab_6">Reconciliation Report</a></li>

             <li <?php echo ($_GET['tab'] == 'tab_7') ? "class='active'" : ''; ?>><a href="mywallet.php?tid=<?php echo $_SESSION['tid']; ?>&&mid=NDA0&&tab=tab_7">Terminal/POS Report</a></li>

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
                  <label for="" class="col-sm-1 control-label" style="color:blue;">From</label>
                  <div class="col-sm-5">
                  <input name="dfrom" type="date" id="startDate" class="form-control" placeholder="To Date: 2018-05-01" required>
				  <span style="color: orange;">Date should be in this format: 2018-05-01</span>
                  </div>
				  
				  <label for="" class="col-sm-1 control-label" style="color:blue;">To</label>
                  <div class="col-sm-5">
                  <input name="dto" type="date" id="endDate" class="form-control" placeholder="To Date: 2018-05-24" required>
				  <span style="color: orange;">Date should be in this format: 2018-05-24</span>
                  </div>
                </div>

            <div class="form-group">
                <label for="" class="col-sm-1 control-label" style="color:blue;">Type</label>
                <div class="col-sm-5">
                  <select name="ttype" id="transType" class="form-control select2" style="width:100%" required>
                    <option value="" selected="selected">Filter By Transaction Type...</option>
                    <option value="all">All Transaction</option>
                    <option value="Airtime - WEB">Airtime - WEB</option>
                    <option value="Databundle - WEB">Databundle - WEB</option>
                    <option value="Commission - WEB">VAS Commission - WEB</option>
                    <option value="Billpayment - WEB">Billpayment - WEB</option>
                    <option value="Airtime - POS">Airtime - POS</option>
                    <option value="Databundle - POS">Databundle - POS</option>
                    <option value="Commission - POS">VAS Commission - POS</option>
                    <option value="Billpayment - POS">Billpayment - POS</option>
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
                    <option value="p2p-reversal">P2P-Reversal</option>
                    <option value="p2p-debit">P2P-Debit</option>
                    <option value="BVN_Charges">BVN Charges</option>
                    <option value="DD_Activation">Direct Debit Activation</option>
                    <option value="VerveCard_Verification">VerveCard Verification</option>
                    <option value="USSD">USSD</option>
                    <option value="POS">POS</option>
                    <option value="TERMINAL_COMMISSION">Terminal Commission</option>
                    <option value="TRANSFER_COMMISSION">Transfer Commission</option>
                    <option value="BANK_TRANSFER">Inter-bank Transfer</option>
                    <option value="Prefund_Balance">Prefund Balance</option>
                    <option value="Card_Commission">Card_Commission</option>
                    <option value="Terminal_Activation_Fee">Terminal_Activation_Fee</option>
                    <option value="TERMINAL_COMMISSION_REVERSAL">Terminal Commission Reversal</option>
                    <option value="Charges">Other Charges</option>
                  </select>
                  </div>
				  
				  <label for="" class="col-sm-1 control-label" style="color:blue;">Filter By</label>
                  <div class="col-sm-5">
                 <select name="filter_by" id="filterBy" class="form-control select2" style="width:100%" required>
    				<option value="" selected="selected">Filter By Institution, Agent, Merchant, Customer...</option>
    				<option value="all">All Wallet History</option>
    				<option disabled>Filter By Institution, Agent, Merchant</option>
    				<?php
    				$get1 = mysqli_query($link, "SELECT * FROM institution_data WHERE status = 'Approved' ORDER BY id") or die (mysqli_error($link));
    				while($rows1 = mysqli_fetch_array($get1))
    				{
    				?>
    				<option value="<?php echo $rows1['institution_id']; ?>"><?php echo $rows1['institution_name']; ?></option>
    				<?php } ?>	
				    
				    <option disabled>Filter By Customer</option>
    				<?php
    				$get4 = mysqli_query($link, "SELECT * FROM borrowers WHERE virtual_acctno != '' ORDER BY id") or die (mysqli_error($link));
    				while($rows4 = mysqli_fetch_array($get4))
    				{
    				?>
    				<option value="<?php echo $rows4['account']; ?>"><?php echo $rows4['lname'].' '.$rows4['fname'].' ['.$rows4['account'].']'; ?></option>
    				<?php } ?>
    				
    				<option disabled>Filter By Staff/Sub-Agent</option>
    				<?php
    				$get5 = mysqli_query($link, "SELECT * FROM user WHERE virtual_acctno != '' ORDER BY userid") or die (mysqli_error($link));
    				while($rows5 = mysqli_fetch_array($get5))
    				{
    				?>
    				<option value="<?php echo $rows5['id']; ?>"><?php echo $rows5['name'].' '.$rows5['lname'].' ['.$rows5['virtual_acctno'].']'; ?></option>
    				<?php } ?>
    				
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
                  <th>UserID</th>
                  <th>Sender</th>
                  <th>RefID</th>
                  <th>Recipient</th>
                  <th>Purpose</th>
                  <th>Currency</th>
                  <th>Credit</th>
                  <th>Debit</th>
                  <th>Balance</th>
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
                  <label for="" class="col-sm-1 control-label" style="color:blue;">From</label>
                  <div class="col-sm-3">
                  <input name="dfrom" type="date" id="startDate" class="form-control" placeholder="To Date: 2018-05-01" required>
				  <span style="color: orange;">Date format: 2018-05-01</span>
                  </div>
				  
				  <label for="" class="col-sm-1 control-label" style="color:blue;">To</label>
                  <div class="col-sm-3">
                  <input name="dto" type="date" id="endDate" class="form-control" placeholder="To Date: 2018-05-24" required>
				  <span style="color: orange;">Date format: 2018-05-24</span>
                  </div>
                  
                  <label for="" class="col-sm-1 control-label" style="color:blue;">Filter By</label>
                  <div class="col-sm-3">
                  <select name="ttype" id="transType" class="form-control select2" style="width:100%" required>
                    <option value="" selected="selected">Filter Transaction...</option>
                    <option value="all">All Transfer</option>
                    
                    <option disabled>Filter By Institution</option>
                    <?php
                    //$get = mysqli_query($link, "SELECT * FROM institution_data WHERE status = 'Approved'") or die (mysqli_error($link));
                    //while($rows = mysqli_fetch_array($get))
                    //{
                    ?>
                    <option value="<?php //echo $rows['institution_id']; ?>"><?php //echo $rows['institution_name']; ?> - [<?php //echo $rows['institution_id']; ?>]</option>
                    <?php //} ?>
                    
                    <option disabled>Filter By Staff / Sub-agent</option>
                    <?php
                    //$get = mysqli_query($link, "SELECT * FROM user WHERE virtual_acctno != ''") or die (mysqli_error($link));
                    //while($rows = mysqli_fetch_array($get))
                    //{
                        //$createdBy = $rows['created_by'];
                        //$searchCompany = mysqli_query($link, "SELECT * FROM institution_data WHERE institution_id = '$createdBy'");
                        //$fetchCompany = mysqli_fetch_array($searchCompany);
                    ?>
                    <option value="<?php //echo $rows['id']; ?>"><?php //echo $rows['name'].' '.$rows['lname'].' '.$rows['mname'].' ['.$rows['virtual_acctno'].']'; ?> - <?php //echo $fetchCompany['institution_name']; ?></option>
                    <?php //} ?>

                    <option disabled>Filter By Customer</option>
                    <?php
                    //$get = mysqli_query($link, "SELECT * FROM borrowers WHERE virtual_acctno != ''") or die (mysqli_error($link));
                    //while($rows = mysqli_fetch_array($get))
                    //{
                        //$createdBy = $rows['branchid'];
                        //$searchCompany = mysqli_query($link, "SELECT * FROM institution_data WHERE institution_id = '$createdBy'");
                        //$fetchCompany = mysqli_fetch_array($searchCompany);
                    ?>
                    <option value="<?php //echo $rows['account']; ?>"><?php //echo $rows['virtual_acctno'].' - '.$rows['lname'].' '.$rows['fname'].' '.$rows['mname']; ?>  [<?php echo $fetchCompany['institution_name']; ?>]</option>
                    <?php 
                    //}
                    ?>
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
                  <th>Institution</th>
                  <th>Account Name</th>
                  <th>RefID</th>
                  <th>Recipient</th>
                  <th>Amount</th>
                  <th>Charges</th>
                  <th>Balance</th>
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
            
            <div class="box-body">
            
            <div class="form-group">
                  <label for="" class="col-sm-1 control-label" style="color:blue;">Filter By</label>
                  <div class="col-sm-4">
                  <select name="ttype" id="transType" class="form-control select2" style="width:100%" required>
                    <option value="" selected="selected">Filter Wallet...</option>
                    <option value="all">All Corporate Wallet</option>
                    
                    <option disabled>Filter By Institution</option>
                    <?php
                    $get = mysqli_query($link, "SELECT * FROM institution_data WHERE status = 'Approved' ORDER BY id DESC") or die (mysqli_error($link));
                    while($rows = mysqli_fetch_array($get))
                    {
                    ?>
                    <option value="<?php echo $rows['institution_id']; ?>"><?php echo $rows['institution_name']; ?></option>
                    <?php } ?>
                  </select>
                  </div>
             </div>
             
            </div>
            
          <hr>
          <div class="table-responsive">
			      <table id="institution_wallet_data" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th><input type="checkbox" id="select_all"/></th>
            	  <th>Account Name</th>
            	  <th>Bank</th>
            	  <th>Account Number</th>
                  <th>Balance</th>
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
elseif($tab == 'tab_4')
  {
  ?>
             <div class="tab-pane <?php echo ($_GET['tab'] == 'tab_4') ? 'active' : ''; ?>" id="tab_4">
                 
            <div class="box-body">
            
            <div class="box-body">
            
            <div class="form-group">
                  <label for="" class="col-sm-1 control-label" style="color:blue;">Filter By</label>
                  <div class="col-sm-4">
                  <select name="ttype" id="transType" class="form-control select2" style="width:100%" required>
                    <option value="" selected="selected">Filter Wallet...</option>
                    <option value="all">All Sub-agent/Staff Wallet</option>
                    
                    <option disabled>Filter By Institution</option>
                    <?php
                    $get = mysqli_query($link, "SELECT * FROM institution_data WHERE status = 'Approved'") or die (mysqli_error($link));
                    while($rows = mysqli_fetch_array($get))
                    {
                    ?>
                    <option value="<?php echo $rows['institution_id']; ?>"><?php echo $rows['institution_name']; ?> - [<?php echo $rows['institution_id']; ?>]</option>
                    <?php } ?>
                    
                    <option disabled>Filter By Staff/Sub-Agent</option>
                    <?php
                    $get = mysqli_query($link, "SELECT * FROM user WHERE comment = 'Approved' AND virtual_acctno != '' ORDER BY id DESC") or die (mysqli_error($link));
                    while($rows = mysqli_fetch_array($get))
                    {
                    ?>
                    <option value="<?php echo $rows['virtual_acctno']; ?>"><?php echo $rows['name'].' '.$rows['lname'].' '.$rows['mname']; ?></option>
                    <?php } ?>
                  </select>
                  </div>
             </div>
             
            </div>
            
          <hr>
          <div class="table-responsive">
			      <table id="staff_wallet_data" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th><input type="checkbox" id="select_all"/></th>
                  <th>Institution</th>
            	  <th>Account Name</th>
            	  <th>Bank</th>
            	  <th>Account Number</th>
                  <th>Balance</th>
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
elseif($tab == 'tab_5')
  {
  ?>
             <div class="tab-pane <?php echo ($_GET['tab'] == 'tab_5') ? 'active' : ''; ?>" id="tab_5">
            
            <div class="box-body">
            
            <div class="box-body">
            
            <div class="form-group">
                  <label for="" class="col-sm-1 control-label" style="color:blue;">Filter By</label>
                  <div class="col-sm-4">
                  <select name="ttype" id="transType" class="form-control select2" style="width:100%" required>
                    <option value="" selected="selected">Filter Wallet...</option>
                    <option value="all">All Customer Wallet</option>
                    
                    <option disabled>Filter By Institution</option>
                    <?php
                    $get = mysqli_query($link, "SELECT * FROM institution_data WHERE status = 'Approved'") or die (mysqli_error($link));
                    while($rows = mysqli_fetch_array($get))
                    {
                    ?>
                    <option value="<?php echo $rows['institution_id']; ?>"><?php echo $rows['institution_name']; ?> - [<?php echo $rows['institution_id']; ?>]</option>
                    <?php } ?>
                    
                    <option disabled>Filter By Customer</option>
                    <?php
                    $get = mysqli_query($link, "SELECT * FROM borrowers WHERE acct_status = 'Activated' AND virtual_acctno != '' ORDER BY id DESC") or die (mysqli_error($link));
                    while($rows = mysqli_fetch_array($get))
                    {
                    ?>
                    <option value="<?php echo $rows['virtual_acctno']; ?>"><?php echo $rows['virtual_acctno']; ?> - <?php echo $rows['fname'].' '.$rows['lname'].' '.$rows['mname']; ?></option>
                    <?php } ?>
                  </select>
                  </div>
             </div>
             
            </div>
            
          <hr>
          <div class="table-responsive">
			      <table id="customer_wallet_data" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th><input type="checkbox" id="select_all"/></th>
                  <th>Institution</th>
            	  <th>Account Name</th>
            	  <th>Bank</th>
            	  <th>Account Number</th>
                  <th>Balance</th>
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
elseif($tab == 'tab_6')
{
?>
             <div class="tab-pane <?php echo ($_GET['tab'] == 'tab_6') ? 'active' : ''; ?>" id="tab_6">
            
            <div class="box-body">
            
            <div class="box-body">
            
            <div class="form-group">
                  <label for="" class="col-sm-1 control-label" style="color:blue;">From</label>
                  <div class="col-sm-3">
                    <input name="dfrom" type="date" id="startDate" class="form-control" placeholder="To Date: 2018-05-01" required>
				            <span style="color: orange;">Date format: 2018-05-01</span>
                  </div>
				  
				          <label for="" class="col-sm-1 control-label" style="color:blue;">To</label>
                  <div class="col-sm-3">
                    <input name="dto" type="date" id="endDate" class="form-control" placeholder="To Date: 2018-05-24" required>
				            <span style="color: orange;">Date format: 2018-05-24</span>
                  </div>
                  
                  <label for="" class="col-sm-1 control-label" style="color:blue;">Filter By</label>
                  <div class="col-sm-3">
                  <select name="ttype" id="transType" class="form-control select2" style="width:100%" required>
                    <option value="" selected="selected">Filter Transaction...</option>
                    <option value="all">All Report</option>
                    
                    <option disabled>Filter By Institution</option>
                    <?php
                    $get = mysqli_query($link, "SELECT * FROM institution_data WHERE status = 'Approved'") or die (mysqli_error($link));
                    while($rows = mysqli_fetch_array($get))
                    {
                    ?>
                    <option value="<?php echo $rows['institution_id']; ?>"><?php echo $rows['institution_name']; ?> - [<?php echo $rows['institution_id']; ?>]</option>
                    <?php } ?>
                    
                    <option disabled>Filter By Staff / Sub-agent</option>
                    <?php
                    $get = mysqli_query($link, "SELECT * FROM user WHERE comment = 'Approved'") or die (mysqli_error($link));
                    while($rows = mysqli_fetch_array($get))
                    {
                        $createdBy = $rows['created_by'];
                        $searchCompany = mysqli_query($link, "SELECT * FROM institution_data WHERE institution_id = '$createdBy'");
                        $fetchCompany = mysqli_fetch_array($searchCompany);
                    ?>
                    <option value="<?php echo $rows['id']; ?>"><?php echo $rows['name'].' '.$rows['lname'].' '.$rows['mname'].' ['.$rows['virtual_acctno'].']'; ?> - <?php echo $fetchCompany['institution_name']; ?></option>
                    <?php } ?>

                    <option disabled>Filter By Customer</option>
                    <?php
                    $get = mysqli_query($link, "SELECT * FROM borrowers WHERE virtual_acctno != ''") or die (mysqli_error($link));
                    while($rows = mysqli_fetch_array($get))
                    {
                        $createdBy = $rows['branchid'];
                        $searchCompany = mysqli_query($link, "SELECT * FROM institution_data WHERE institution_id = '$createdBy'");
                        $fetchCompany = mysqli_fetch_array($searchCompany);
                    ?>
                    <option value="<?php echo $rows['account']; ?>"><?php echo $rows['virtual_acctno'].' - '.$rows['lname'].' '.$rows['fname'].' '.$rows['mname']; ?>  [<?php echo $fetchCompany['institution_name']; ?>]</option>
                    <?php 
                    }
                    ?>
                  </select>
                  </div>
             </div>
             
            </div>
            
          <hr>
          <div class="table-responsive">
			      <table id="wallet_reconciliation_data" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th><input type="checkbox" id="select_all"/></th>
                  <th>Institution</th>
            	    <th>Account Name</th>
            	    <th>Total Credit<p style="font-size:11px;">(inValue)</p></th>
            	    <th>Total Debit<p style="font-size:11px;">(inValue)</p></th>
                  <th>Total Credit<p style="font-size:11px;">(inCount)</p></th>
                  <th>Total Debit<p style="font-size:11px;">(inCount)</p></th>
                  <th>Total Charges<p style="font-size:11px;">(inValue)</p></th>
                  <th>Total Commission<p style="font-size:11px;">(inValue)</p></th>
                  <th>Total Transaction<p style="font-size:11px;">(inValue)</p></th>
            	    <th>Total Transaction<p style="font-size:11px;">(inCount)</p></th>
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
elseif($tab == 'tab_7')
{
?>
             <div class="tab-pane <?php echo ($_GET['tab'] == 'tab_7') ? 'active' : ''; ?>" id="tab_7">
            
            <div class="box-body">
            
            <div class="box-body">

            <div class="form-group">
                  <label for="" class="col-sm-1 control-label" style="color:blue;">From</label>
                  <div class="col-sm-3">
                  <input name="dfrom" type="date" id="startDate" class="form-control" placeholder="To Date: 2018-05-01" required>
				  <span style="color: orange;">Date format: 2018-05-01</span>
                  </div>
				  
				  <label for="" class="col-sm-1 control-label" style="color:blue;">To</label>
                  <div class="col-sm-3">
                  <input name="dto" type="date" id="endDate" class="form-control" placeholder="To Date: 2018-05-24" required>
				  <span style="color: orange;">Date format: 2018-05-24</span>
                  </div>
                  
                  <label for="" class="col-sm-1 control-label" style="color:blue;">Filter</label>
                  <div class="col-sm-3">
                  <select name="ttype" id="transType" class="form-control select2" style="width:100%" required>
                    <option value="" selected="selected">Filter Report...</option>
                    <option value="all">All Terminal Reports</option>

                    <option disabled>Filter By Status</option>
                    <option value="Pending">Pending</option>
                    <option value="Success">Successful</option>
                    <option value="Expired">Expired</option>
                    <option value="Dormant Account">Dormant Account</option>
                    <option value="Blacklisted Account">Blacklisted Account</option>

                    <option disabled>Filter By Channel</option>
                    <option value="Pos">Pos</option>
                    <option value="USSD">USSD</option>
                    
                    <option disabled>Filter By Merchant</option>
                    <?php
                    $get = mysqli_query($link, "SELECT * FROM terminal_reg WHERE terminal_status = 'Assigned'") or die (mysqli_error($link));
                    while($rows = mysqli_fetch_array($get))
                    {
                        $merchantId = $rows['merchant_id'];
                    ?>
                    <option value="<?php echo $merchantId; ?>"><?php echo $rows['merchant_name']; ?></option>
                    <?php } ?>

                    <option disabled>Filter By Terminal</option>
                    <?php
                    $get = mysqli_query($link, "SELECT * FROM terminal_reg WHERE terminal_status = 'Assigned'") or die (mysqli_error($link));
                    while($rows = mysqli_fetch_array($get))
                    {
                        $terminalId = $rows['terminal_id'];
                    ?>
                    <option value="<?php echo $terminalId; ?>"><?php echo $rows['terminal_id'].' - '.$rows['terminal_model_code'].' ('.$rows['channel'].')'; ?></option>
                    <?php } ?>
                    
                    <option disabled>Filter By Staff</option>
                    <?php
                    $get = mysqli_query($link, "SELECT * FROM user WHERE virtual_acctno != '' AND comment = 'Approved'") or die (mysqli_error($link));
                    while($rows = mysqli_fetch_array($get))
                    {
                    ?>
                    <option value="<?php echo $rows['id']; ?>"><?php echo $rows['virtual_acctno'].' - '.$rows['name'].' '.$rows['lname']; ?></option>
                    <?php } ?>
                  </select>
                  </div>
             </div>
             
            </div>
            
          <hr>
          <div class="table-responsive">
			<table id="terminal_report_data" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th><input type="checkbox" id="select_all"/></th>
                  <th>RefID</th>
                  <th>Terminal ID</th>
                  <th>Operator</th>
                  <th>Merchant Name</th>
                  <th>Channel</th>
                  <th>Amount</th>
                  <th>Charges</th>
                  <th>Amount Settled</th>
                  <th>Pending Balance</th>
                  <th>Transfer Balance</th>
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
} 
?>
</div>
</div>
</div> 
    

              </div>


  
</div>  
</div>
</div>  
</div>