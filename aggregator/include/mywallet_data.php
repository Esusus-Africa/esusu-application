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

<button type="button" class="btn btn-flat bg-white" align="left" disabled>&nbsp;<b>Transfer Wallet:</b>&nbsp;
    <strong class="alert bg-blue">
    <?php
    echo $aggcurrency.number_format($aggwallet_balance,2,'.',',');
    ?> 
    </strong>
</button>

</form>

<form>
<?php
$system_set = mysqli_query($link, "SELECT * FROM systemset") or die ("Error: " . mysqli_error($link));
$row1 = mysqli_fetch_object($system_set);
?>

  <a href="fund_wallet.php?id=<?php echo $_SESSION['tid']; ?>&&mid=NDA0"><button type="button" class="btn bg-blue"><i class="fa fa-refresh"></i> Fund Wallet</button></a>

</form>

<?php
}
else{
    //Your content or code for desktop or computers devices
?>
<a href="mywallet.php?id=<?php echo $_SESSION['tid']; ?>&&mid=NDA0&&tab=<?php echo $_GET['tab']; ?>"><button type="button" class="btn btn-flat bg-orange"><i class="fa fa-refresh"></i>&nbsp;Refresh</button></a> 

<button type="button" class="btn btn-flat bg-white" align="left" disabled>&nbsp;<b>Transfer Wallet:</b>&nbsp;
    <strong class="alert bg-blue">
    <?php
    echo $aggcurrency.number_format($aggwallet_balance,2,'.',',');
    ?> 
    </strong>
</button>

<form>
<?php
$system_set = mysqli_query($link, "SELECT * FROM systemset") or die ("Error: " . mysqli_error($link));
$row1 = mysqli_fetch_object($system_set);
?>

 <a href="fund_wallet.php?id=<?php echo $_SESSION['tid']; ?>&&mid=NDA0"><button type="button" class="btn bg-blue"><i class="fa fa-refresh"></i> Fund Wallet</button></a>

</form>
  
<?php
}
?>
  <hr>    
 
<div class="col-md-14">
            <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
             <li <?php echo ($_GET['tab'] == 'tab_1') ? "class='active'" : ''; ?>><a href="mywallet.php?tid=<?php echo $_SESSION['tid']; ?>&&mid=NDA0&&tab=tab_1">View Wallet History</a></li>
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
                  <div class="col-sm-3">
                  <input name="dfrom" type="date" id="startDate" class="form-control" placeholder="To Date: 2018-05-01" required>
				          <span style="color: orange;">Date format: 2018-05-01</span>
                  </div>
				  
				          <label for="" class="col-sm-1 control-label" style="color:blue;">End Date</label>
                  <div class="col-sm-3">
                  <input name="dto" type="date" id="endDate" class="form-control" placeholder="To Date: 2018-05-24" required>
				          <span style="color: orange;">Date format: 2018-05-24</span>
                  </div>
                  
                  <label for="" class="col-sm-1 control-label" style="color:blue;">Type</label>
                  <div class="col-sm-3">
                  <select name="ttype" id="transType" class="form-control select2" style="width:100%" required>
                    <option value="" selected="selected">Filter By Transaction Type...</option>
                    <option value="all">All Transaction</option>
                    <option value="Airtime - WEB">Airtime - WEB</option>
                    <option value="Databundle - WEB">Databundle - WEB</option>
                    <option value="Commission - WEB">VAS Commission - WEB</option>
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
       
             
             </div>
             <!-- /.tab-pane --> 
             
<?php
  }
  elseif($tab == 'tab_3')
  {
  ?>
            <div class="tab-pane <?php echo ($_GET['tab'] == 'tab_3') ? 'active' : ''; ?>" id="tab_3">

        
             
             </div>
             <!-- /.tab-pane --> 


<?php
}
elseif($tab == 'tab_4')
{
?>
        <div class="tab-pane <?php echo ($_GET['tab'] == 'tab_4') ? 'active' : ''; ?>" id="tab_4">

             
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