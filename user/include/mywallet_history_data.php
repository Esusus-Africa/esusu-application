<div class="row">
		
	       
		    <section class="content">  
	        <div class="box box-success">
            <div class="box-body">
              <div class="table-responsive">
             <div class="box-body">
<form>
<?php
if(isMobileDevice()){
    //Your content or code for mobile devices goes here
?>

    <button type="button" class="btn btn-flat bg-white" align="left" disabled>&nbsp;<b>Balance:</b>&nbsp;
    <strong class="alert bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">
    <?php 
    $id = $_GET['acn'];
    $select = mysqli_query($link, "SELECT wallet_balance FROM borrowers WHERE account = '$id'") or die (mysqli_error($link));
    while($row = mysqli_fetch_array($select))
    {
    echo "<span id='wallet_balance'>".$bbcurrency.number_format($row['wallet_balance'],2,".",",")."</span>";
    }
    ?>
    </strong>
    </button>

    <a href="fund_wallet.php?tid=<?php echo $_SESSION['tid']; ?>&&acn=<?php echo $_SESSION['acctno']; ?>&&mid=NDA4"><button type="button" class="btn bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>"><i class="fa fa-refresh"></i> Fund Wallet!</button></a>
   <!--<hr><a href="wallet-towallet.php?id=<?php echo $_SESSION['tid']; ?>&&acn=<?php echo $_SESSION['acctno']; ?>&&mid=NDA4&&tab=tab_2"><button type="button" class="btn btn-flat bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>"><i class="fa fa-exchange"></i>&nbsp;Wallet-to-Wallet</button></a>
    <a href="pay_bills?id=<?php echo $_SESSION['tid']; ?>&&acn=<?php echo $_SESSION['acctno']; ?>&&mid=NDA4"><button type="button" class="btn btn-flat bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>"><i class="fa fa-globe"></i>&nbsp;Pay Bills</button></a>-->
<?php
}
else{
    ?>
    
	<a href="mywallet_history.php?tid=<?php echo $_SESSION['tid']; ?>&&acn=<?php echo $_SESSION['acctno']; ?>&&mid=NDA4&&tab=tab_1"><button type="button" class="btn bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>"><i class="fa fa-refresh"></i>&nbsp;Refresh</button></a>
	<button type="button" class="btn btn-flat bg-white" align="left" disabled>&nbsp;<b>Wallet Balance:</b>&nbsp;
    <strong class="alert bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">
    <?php 
    $id = $_GET['acn'];
    $select = mysqli_query($link, "SELECT wallet_balance FROM borrowers WHERE account = '$id'") or die (mysqli_error($link));
    while($row = mysqli_fetch_array($select))
    {
    echo "<span id='wallet_balance'>".$bbcurrency.number_format($row['wallet_balance'],2,".",",")."</span>";
    }
    ?>
    </strong>
    </button>

    <a href="fund_wallet.php?tid=<?php echo $_SESSION['tid']; ?>&&acn=<?php echo $_SESSION['acctno']; ?>&&mid=NDA4"><button type="button" class="btn bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>"><i class="fa fa-refresh"></i> Fund Wallet!</button></a>
    <!--<a href="wallet-towallet.php?id=<?php echo $_SESSION['tid']; ?>&&acn=<?php echo $_SESSION['acctno']; ?>&&mid=NDA4&&tab=tab_2"><button type="button" class="btn btn-flat bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>"><i class="fa fa-exchange"></i>&nbsp;Wallet-to-Wallet</button></a>
    <a href="pay_bills?id=<?php echo $_SESSION['tid']; ?>&&acn=<?php echo $_SESSION['acctno']; ?>&&mid=NDA4"><button type="button" class="btn btn-flat bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>"><i class="fa fa-globe"></i>&nbsp;Pay Bills</button></a>-->
<?php    
}
?>
</form>
	<hr>
  
<div class="col-md-14">
            <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
             <li <?php echo ($_GET['tab'] == 'tab_1') ? "class='active'" : ''; ?>><a href="mywallet_history.php?tid=<?php echo $_SESSION['tid']; ?>&&acn=<?php echo $_SESSION['acctno']; ?>&&mid=NDA4&&tab=tab_1">View Wallet History</a></li>

             <!--<li <?php //echo ($_GET['tab'] == 'tab_2') ? "class='active'" : ''; ?>><a href="mywallet_history.php?tid=<?php //echo $_SESSION['tid']; ?>&&acn=<?php //echo $_SESSION['acctno']; ?>&&mid=NDA4&&tab=tab_2">View Transfer History</a></li>-->

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
                    <option selected="selected">Filter By Transaction Type...</option>
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
                    <option value="p2p-transfer">P2P-Transfer</option>
                    <option value="BVN_Charges">BVN Charges</option>
                    <option value="VerveCard_Verification">VerveCard Verification</option>
                    <option value="BANK_TRANSFER">Inter-bank Transfer</option>
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

        <!--<div class="box-body">
            
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
                    <option value="all">All Transfer</option>
                    <option value="SUCCESSFUL">SUCCESSFUL</option>
                    <option value="PENDING">PENDING</option>
                    <option value="FAILED">FAILED</option>
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