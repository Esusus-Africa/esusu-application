<div class="row">
    
		    <section class="content">  
	        <div class="box box-success">
            <div class="box-body">
              <div class="table-responsive">
             <div class="box-body">
<form method="post">
  <a href="view_fund_history.php?id=<?php echo $_SESSION['tid']; ?>&&mid=NTEw&&tab=tab_1"><button type="button" class="btn btn-flat bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>"><i class="fa fa-refresh"></i>&nbsp;Refresh</button></a> 
  <?php echo ($allocate_fund == '1') ? '<a href="allocate_fund.php?id='.$_SESSION['tid'].'&&idm='.$_GET['idm'].'&&mid=NTEw" class="btn btn-flat bg-'.$myaltrow['theme_color'].' == "" ? blue : '.$myaltrow['theme_color'].'" target="_blank"><i class="fa fa-plus"></i>&nbsp;Allocate Fund</a>' : ''; ?>

<?php
if($till_virtual_account_details == '1')
{
?>
      
    <button type="button" class="btn btn-flat bg-white" align="left" disabled>&nbsp;<b>Till Account Bal:</b>&nbsp;
    <strong class="alert bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">
    
    <?php
    $search_iNGN = mysqli_query($link, "SELECT SUM(balance) FROM till_account WHERE cashier = '$iuid'");
    $get_i = mysqli_fetch_array($search_iNGN);    
    $total_NGN = number_format($get_i['SUM(balance)'],2,'.',',');
    
    echo $icurrency.$total_NGN;
    ?>
    
    </strong>
      </button>

      <button type="button" class="btn btn-flat bg-white" align="left" disabled>&nbsp;<b>Till Account Details:</b>&nbsp;
    <strong class="alert bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">
    
    <?php
    $search_till = mysqli_query($link, "SELECT * FROM till_virtual_account WHERE userid = '$iuid'");
    $get_till = mysqli_fetch_array($search_till);
    $getTillNum = mysqli_num_rows($search_till);
    
    echo ($getTillNum == 1) ? $get_till['account_number'].' | '.$get_till['account_name'].' | '.$get_till['bank_name'] : "Account Not Found!";
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
             <li <?php echo ($_GET['tab'] == 'tab_1') ? "class='active'" : ''; ?>><a href="view_fund_history.php?tid=<?php echo $_SESSION['tid']; ?>&&mid=NTEw&&tab=tab_1">Till Account History</a></li>
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
				  <span style="color: <?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>;">Date format: 2018-05-01</span>
                  </div>
				  
				  <label for="" class="col-sm-1 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">End Date</label>
                  <div class="col-sm-3">
                  <input name="dto" type="date" id="endDate" class="form-control" placeholder="To Date: 2018-05-24" required>
				  <span style="color: <?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>;">Date format: 2018-05-24</span>
                  </div>
                  
                  <label for="" class="col-sm-1 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Type</label>
                  <div class="col-sm-3">
                  <select name="ttype" id="transType" class="form-control select2" style="width:100%" required>
                    <option value="" selected="selected">Filter By Transaction Type...</option>
                    <option value="all">All Transaction</option>
                    <option value="ACCOUNT_TRANSFER">Account Transfer</option>
                    <option value="MANUAL_FUNDING">Manual Funding</option>
                    <option value="LOAN_REPAYMENT">Loan Repayment</option>
                    <option value="LOAN_REPAYMENT_REVERSAL">Loan Repayment Reversal</option>
                    <option value="Deposit">Deposit</option>
                    <option value="REVERSED_DEPOSIT">Reversed Deposit</option>
                    <option value="Withdraw">Withdraw</option>
                    <option value="REVERSED_WITHDRAWAL">Reversed Withdrawal</option>
                    <option value="WITHDRAW_TILL_FUND">Withdrawn Till Fund</option>
                    <option value="WITHDRAW_TILL_COMMISSION">Withdrawn Till Commission</option>
                    <option value="TILL_SETTLEMENT">Till Settlement</option>
                    <option value="Charges">Other Charges</option>

                    <option disabled>Filter By Staff/Sub-Agent</option>
                    <?php
                      ($list_employee === "1" && $list_branch_employee != "1") ? $search = mysqli_query($link, "SELECT * FROM user WHERE created_by = '$institution_id' ORDER BY userid DESC") : "";
                      ($list_employee != "1" && $list_branch_employee === "1") ? $search = mysqli_query($link, "SELECT * FROM user WHERE created_by = '$institution_id' AND branchid = '$isbranchid' ORDER BY userid DESC") : "";
                      while($fetchSearch = mysqli_fetch_array($search)){

                        $mytillid = $fetchSearch['id'];
                        $verifyTillAcct = mysqli_query($link, "SELECT * FROM till_virtual_account WHERE userid = '$mytillid'");
                        $fetchTillAcct = mysqli_fetch_array($verifyTillAcct);
                        $staff_VA1 = ($fetchTillAcct['account_number'] == "") ? $fetchSearch['lname'].' '.$fetchSearch['name'].' ['.$fetchSearch['virtual_acctno'].']' : $fetchTillAcct['account_name'].' ['.$fetchTillAcct['account_number'].']';

                        echo '<option value="'.$mytillid.'">'.$staff_VA1.'</option>';

                      }

                    ?>
                  </select>
                  </div>
                </div>

                </div>
                
                
            <hr>
            <div class="table-responsive">
			    <table id="fetch_vatill_transaction_data" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th><input type="checkbox" id="select_all"/></th>
                  <th>RefID</th>
                  <th>Initiated By</th>
                  <th>Branch</th>
                  <th>Cashier</th>
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