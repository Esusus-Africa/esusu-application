<div class="row">

   <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-orange">
            <div class="inner">
      
      <h4>
<?php
$systemset = mysqli_query($link, "SELECT * FROM systemset");
$row1 = mysqli_fetch_object($systemset);

$check_sms = mysqli_query($link, "SELECT * FROM billpayment WHERE status = 'Active' AND companyid = '$agentid'") or die (mysqli_error($link));
if(mysqli_num_rows($check_sms) == 1)
{
  $get_sms = mysqli_fetch_array($check_sms);
  $ozeki_user = $get_sms['username'];
  $ozeki_password = $get_sms['password'];
  $ozeki_url = $get_sms['api_url'];

  $url = 'username='.$ozeki_user;
  $url.= '&password='.$ozeki_password;
  $url.= '&balance='.'true&';

  $urltouse = $ozeki_url.$url;
  $response = file_get_contents($urltouse);
  
  echo number_format($response,2,'.',',');
}
else{
    echo '0.00';
}
?>      </h4>
              <p>Wallet Balance</p>
            </div>
            <div class="icon">
              <i class="fa fa-binoculars"></i>
            </div>
             <a href="topup_wallet.php?tid=<?php echo $_SESSION['tid']; ?>&&mid=NDAx" class="small-box-footer"> Top-up Wallet <i class="fa fa-arrow-circle-right"></i></a>
          </div>      
        </div>

        <!-- ./col -->
<?php
$check = mysqli_query($link, "SELECT * FROM my_permission WHERE urole = '$urole' OR urole = '$arole'") or die ("Error" . mysqli_error($link));
$get_check = mysqli_fetch_array($check);
$access_loan_tab = $get_check['access_loan_tab'];
$view_all_loans = $get_check['view_all_loans'];
if($view_all_loans == '1' || $access_loan_tab == '1')
{
?>
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-blue">
            <div class="inner">
      
<h4>
<?php 
$select = mysqli_query($link, "SELECT SUM(amount) FROM loan_info WHERE status = 'Approved' AND branchid = '$session_id'") or die (mysqli_error($link));
if(mysqli_num_rows($select)==0)
{
echo "0.00";
}
else{
while($row = mysqli_fetch_array($select))
{
echo number_format($row['SUM(amount)'],2,".",",")."</b>";
}
}
?>
</h4>
      <p>Loans Released</p>
            </div>
            <div class="icon"> 
      <i class="fa fa-hdd-o"></i> 
      </div>
            <?php echo ($view_all_loans == 1) ? '<a href="listloans.php?tid='.$_SESSION['tid'].'&&mid='.base64_encode("405").'" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a> </div>' : '<a href="#" class="small-box-footer">-------</a>'; ?>
        </div>
        <!-- ./col -->
<?php
}
else{
  echo '';
}
?>
        <!-- ./col -->
        
       <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-blue">
            <div class="inner">
      
      <h4>
<?php
$select = mysqli_query($link, "SELECT * FROM saas_subscription_trans WHERE status = 'Paid' AND coopid_instid = '$session_id'") or die (mysqli_error($link));
$num = mysqli_num_rows($select);
echo $num;
?>
      </h4>
              <p>Total Subscription</p>
            </div>
            <div class="icon">
              <i class="fa fa-users"></i>
            </div>
           <a href="saassub_history.php?tid=<?php echo $_SESSION['tid']; ?>&&mid=NDIw" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
    
<?php
$check = mysqli_query($link, "SELECT * FROM my_permission WHERE urole = '$urole' OR urole = '$arole'") or die ("Error" . mysqli_error($link));
$get_check = mysqli_fetch_array($check);
$access_employee_tab = $get_check['emp_tab'];
$list_employee = $get_check['list_employee'];
if($access_employee_tab == '1' || $list_employee == '1')
{
?>    
    <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-orange">
            <div class="inner">
      
      <h4>
<?php
$select = mysqli_query($link, "SELECT * FROM user WHERE created_by = '$session_id'") or die (mysqli_error($link));
$num = mysqli_num_rows($select);
echo $num;
?>
      </h4>
              <p>Employees</p>
            </div>
            <div class="icon">
              <i class="fa fa-user"></i>
            </div>
            <?php echo ($list_employee == 1) ? '<a href="listemployee.php?tid='.$_SESSION['tid'].'&&mid='.base64_encode("409").'" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>' : '<a href="#" class="small-box-footer">-------</a>'; ?>
          </div>
        </div>
<?php
}
else{
  echo '';
}
?>

<?php
$check = mysqli_query($link, "SELECT * FROM my_permission WHERE urole = '$urole' OR urole = '$arole'") or die ("Error" . mysqli_error($link));
$get_check = mysqli_fetch_array($check);
$access_loan_tab = $get_check['access_loan_tab'];
$view_due_loans = $get_check['view_due_loans'];
$view_due_loans = $get_check['view_due_loans'];
if($view_due_loans == '1' || $access_loan_tab == '1')
{
?>
    <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-blue">
            <div class="inner">
      
      <h4>
<?php
$date_now = date("Y-m-d");
$select = mysqli_query($link, "SELECT SUM(payment) FROM pay_schedule WHERE status = 'UNPAID' AND schedule <= '$date_now' AND branchid = '$session_id'") or die ("Error: " . mysqli_error($link));
if(mysqli_num_rows($select)==0)
{
echo "0.00";
}
else{
while($row = mysqli_fetch_array($select))
{
echo number_format($row['SUM(payment)'],2,".",",")."</b>";
}
}
?>
      </h4>
              <p>Loan Due Amount </p>
            </div>
            <div class="icon">
              <i class="fa fa-hand-lizard-o"></i>
            </div>
            <?php echo ($view_all_loans == 1) ? '<a href="missedpayment.php?tid='.$_SESSION['tid'].'&&mid='.base64_encode("407").'" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>' : '<a href="#" class="small-box-footer">-------</a>'; ?>
          </div>
        </div>
<?php
}
else{
  echo '';
}
?>    

<?php
$check = mysqli_query($link, "SELECT * FROM my_permission WHERE urole = '$urole' OR urole = '$arole'") or die ("Error" . mysqli_error($link));
$get_check = mysqli_fetch_array($check);
$access_helpdesk_tab = $get_check['access_helpdesk_tab'];
$view_all_tickets = $get_check['view_due_loans'];
if($view_all_tickets == '1' || $access_helpdesk_tab == '1')
{
?>
    <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-orange">
            <div class="inner">
      
      <h4>
<?php
$select = mysqli_query($link, "SELECT * FROM message WHERE branchid = '$session_id'") or die (mysqli_error($link));
$num = mysqli_num_rows($select);
echo $num;
?>
      </h4>
              <p>Helpdesk Message</p>
            </div>
            <div class="icon">
              <i class="fa fa-headphones"></i>
            </div>
            <?php echo ($view_all_loans == 1) ? '<a href="inboxmessage.php?tid='.$_SESSION['tid'].'&&mid='.base64_encode("406").'" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>' : '<a href="#" class="small-box-footer">-------</a>'; ?>
          </div>
        </div>
<?php
}
else{
  echo '';
}
?>

<?php
$check = mysqli_query($link, "SELECT * FROM my_permission WHERE urole = '$urole' OR urole = '$arole'") or die ("Error" . mysqli_error($link));
$get_check = mysqli_fetch_array($check);
$income_tab = $get_check['access_expense_tab'];
$view_income = $get_check['view_expenses'];
if($income_tab == '1' || $view_income == '1')
{
?>  
       <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-orange">
            <div class="inner">
      
      <h4>
<?php 
$date_now = date("Y-m-d");
$select = mysqli_query($link, "SELECT SUM(icm_amt) FROM income WHERE companyid = '$session_id'") or die ("Error: " . mysqli_error($link));
if(mysqli_num_rows($select) == 0)
{
  echo '0.00';
}
else{
while($row = mysqli_fetch_array($select))
{
echo number_format($row['SUM(icm_amt)'],2,".",",")."</b>";
}
}
?>
      </h4>
              <p>Total Income</p>
            </div>
            <div class="icon" align="right">
              <i class="fa fa-money"></i>
            </div>
            <?php echo ($view_income == 1) ? '<a href="listincome.php?tid='.$_SESSION['tid'].'&&mid=NTAw" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>' : '<a href="#" class="small-box-footer">-------</a>'; ?>
          </div>
        </div>
<?php
}
else{
  echo '';
}
?>

<?php
$check = mysqli_query($link, "SELECT * FROM my_permission WHERE urole = '$urole' OR urole = '$arole'") or die ("Error" . mysqli_error($link));
$get_check = mysqli_fetch_array($check);
$access_expense_tab = $get_check['access_expense_tab'];
$view_expenses = $get_check['view_expenses'];
if($access_expense_tab == '1' || $view_expenses == '1')
{
?>  
       <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-blue">
            <div class="inner">
      
      <h4>
<?php 
$date_now = date("Y-m-d");
$select = mysqli_query($link, "SELECT SUM(eamt) FROM expenses WHERE branchid = '$session_id'") or die ("Error: " . mysqli_error($link));
if(mysqli_num_rows($select) == 0)
{
  echo '0.00';
}
else{
while($row = mysqli_fetch_array($select))
{
echo number_format($row['SUM(eamt)'],2,".",",")."</b>";
}
}
?>
      </h4>
              <p>Total Expenses</p>
            </div>
            <div class="icon" align="right">
              <i class="fa fa-money"></i>
            </div>
            <?php echo ($view_expenses == 1) ? '<a href="listexpenses.php?tid='.$_SESSION['tid'].'&&mid='.base64_encode("422").'" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>' : '<a href="#" class="small-box-footer">-------</a>'; ?>
          </div>
        </div>
<?php
}
else{
  echo '';
}
?>
        <!-- ./col -->

<?php
$check = mysqli_query($link, "SELECT * FROM my_permission WHERE urole = '$urole' OR urole = '$arole'") or die ("Error" . mysqli_error($link));
$get_check = mysqli_fetch_array($check);
$access_loanrepayment_tab = $get_check['access_loanrepayment_tab'];
$list_all_repayment = $get_check['list_all_repayment'];
if($access_loanrepayment_tab == '1' || $list_all_repayment == '1')
{
?>
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-orange">
            <div class="inner">
      
      <h4>
<?php 
$select = mysqli_query($link, "SELECT SUM(amount_to_pay) FROM payments WHERE remarks = 'paid' AND branchid = '$session_id'") or die (mysqli_error($link));
if(mysqli_num_rows($select)==0)
{
echo "0.00";
}
else{
while($row = mysqli_fetch_array($select))
{
echo number_format($row['SUM(amount_to_pay)'],2,".",",")."</b>";
}
}
?>
      </h4>
              <p>Loan Repayment </p>
            </div>
            <div class="icon">
              <i class="fa fa-legal"></i>
            </div>
             <?php echo ($list_all_repayment == 1) ? '<a href="listpayment.php?tid='.$_SESSION['tid'].'&&mid='.base64_encode("408").'" class="small-box-footer">More info<i class="fa fa-arrow-circle-right"></i></a>' : '<a href="#" class="small-box-footer">-------</a>'; ?>
          </div>
        </div>
        <!-- ./col -->  
<?php
}
else{
  echo '';
}
?>

<?php
$check = mysqli_query($link, "SELECT * FROM my_permission WHERE urole = '$urole' OR urole = '$arole'") or die ("Error" . mysqli_error($link));
$get_check = mysqli_fetch_array($check);
$access_savings_tab = $get_check['access_savings_tab'];
$view_all_transaction = $get_check['view_all_transaction'];
$view_all_customers = $get_check['view_all_customers'];
if($access_savings_tab == '1' || $view_all_transaction == '1')
{
?> 
    
    <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-orange">
            <div class="inner">
      
      <h4>
<?php 
$select = mysqli_query($link, "SELECT SUM(amount) FROM transaction WHERE t_type = 'Deposit' AND branchid = '$session_id'") or die (mysqli_error($link));
if(mysqli_num_rows($select)==0)
{
echo "0.00";
}
else{
while($row = mysqli_fetch_array($select))
{
echo number_format($row['SUM(amount)'],2,".",",")."</b>";
}
}
?>
      </h4>
              <p>Total Deposit </p>
            </div>
            <div class="icon">
              <i class="fa fa-cloud-upload"></i>
            </div>
             <?php echo ($view_all_transaction == 1) ? '<a href="transaction.php?tid='.$_SESSION['tid'].'&&mid='.base64_encode("408").'" class="small-box-footer">More info<i class="fa fa-arrow-circle-right"></i></a>' : '<a href="#" class="small-box-footer">-------</a>'; ?>
          </div>
        </div>
        <!-- ./col -->  
<?php
}
else{
  echo '';
}
?>

<?php
$check = mysqli_query($link, "SELECT * FROM my_permission WHERE urole = '$urole' OR urole = '$arole'") or die ("Error" . mysqli_error($link));
$get_check = mysqli_fetch_array($check);
$access_savings_tab = $get_check['access_savings_tab'];
$view_all_transaction = $get_check['view_all_transaction'];
$view_all_customers = $get_check['view_all_customers'];
if($access_savings_tab == '1' || $view_all_transaction == '1')
{
?>    
    
    <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-blue">
            <div class="inner">
      
      <h4>
<?php 
$select = mysqli_query($link, "SELECT SUM(amount) FROM transaction WHERE t_type = 'Withdraw' AND branchid = '$session_id'") or die (mysqli_error($link));
if(mysqli_num_rows($select)==0)
{
echo "0.00";
}
else{
while($row = mysqli_fetch_array($select))
{
echo number_format($row['SUM(amount)'],2,".",",")."</b>";
}
}
?>
      </h4>
              <p>Total Withdraw </p>
            </div>
            <div class="icon">
              <i class="fa fa-cloud-download"></i>
            </div>
            <?php echo ($view_all_transaction == 1) ? '<a href="transaction.php?tid='.$_SESSION['tid'].'&&mid='.base64_encode("408").'" class="small-box-footer">More info<i class="fa fa-arrow-circle-right"></i></a>' : '<a href="#" class="small-box-footer">-------</a>'; ?>
          </div>
        </div>
        <!-- ./col -->  
<?php
}
else{
  echo '';
}
?>

<?php
$check = mysqli_query($link, "SELECT * FROM my_permission WHERE urole = '$urole' OR urole = '$arole'") or die ("Error" . mysqli_error($link));
$get_check = mysqli_fetch_array($check);
$access_savings_tab = $get_check['access_savings_tab'];
$view_all_transaction = $get_check['view_all_transaction'];
$view_all_customers = $get_check['view_all_customers'];
if($access_savings_tab == '1' || $view_all_transaction == '1')
{
?>    
    
    <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-orange">
            <div class="inner">
      
      <h4>
<?php 
$select = mysqli_query($link, "SELECT SUM(amount) FROM transaction WHERE t_type = 'Withdraw' AND branchid = '$session_id'") or die (mysqli_error($link));
$num = mysqli_num_rows($select);

$select2 = mysqli_query($link, "SELECT SUM(amount) FROM transaction WHERE t_type = 'Deposit' AND branchid = '$session_id'") or die (mysqli_error($link));
$num1 = mysqli_num_rows($select2);
if($num == 0 && $num1 == 0)
{
echo "0.00";
}
else{
while($row = mysqli_fetch_array($select))
{
    $twithdraw = $row['SUM(amount)'];
    while($row2 = mysqli_fetch_array($select2))
    {
        $tdeposit = $row2['SUM(amount)'];
        $balance = number_format(($tdeposit-$twithdraw),2,'.',',');
        echo $balance."</b>";
    }
}
}
?>
      </h4>
              <p>Total Balance </p>
            </div>
            <div class="icon">
              <i class="fa fa-cloud-download"></i>
            </div>
            <?php echo ($view_all_transaction == 1) ? '<a href="transaction.php?tid='.$_SESSION['tid'].'&&mid='.base64_encode("408").'" class="small-box-footer">More info<i class="fa fa-arrow-circle-right"></i></a>' : '<a href="#" class="small-box-footer">-------</a>'; ?>
          </div>
        </div>
        <!-- ./col -->  
<?php
}
else{
  echo '';
}
?>

<?php
$check = mysqli_query($link, "SELECT * FROM my_permission WHERE urole = '$urole' OR urole = '$arole'") or die ("Error" . mysqli_error($link));
$get_check = mysqli_fetch_array($check);
$access_savings_tab = $get_check['access_savings_tab'];
$view_all_transaction = $get_check['view_all_transaction'];
$view_all_customers = $get_check['view_all_customers'];
if($access_savings_tab == '1' || $view_all_transaction == '1')
{
?>  
    
    <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-orange">
            <div class="inner">
      
      <h4>
<?php 
$selecte = mysqli_query($link, "SELECT * FROM transaction WHERE branchid = '$session_id'") or die (mysqli_error($link));
$nume = mysqli_num_rows($selecte);
echo $nume;
?>
      </h4>
              <p>Total Transaction </p>
            </div>
            <div class="icon">
              <i class="fa fa-database"></i>
            </div>
             <?php echo ($view_all_transaction == 1) ? '<a href="transaction.php?tid='.$_SESSION['tid'].'&&mid='.$nume.'" class="small-box-footer">More info<i class="fa fa-arrow-circle-right"></i></a>' : '<a href="#" class="small-box-footer">-------</a>'; ?>
          </div>
        </div>
        <!-- ./col -->
<?php
}
else{
  echo '';
}
?>

<?php
$check = mysqli_query($link, "SELECT * FROM my_permission WHERE urole = '$urole' OR urole = '$arole'") or die ("Error" . mysqli_error($link));
$get_check = mysqli_fetch_array($check);
$access_customer_tab = $get_check['access_customer_tab'];
$view_all_transaction = $get_check['view_all_transaction'];
$view_all_customers = $get_check['view_all_customers'];
if($access_customer_tab == '1' || $view_all_customers == '1' || $view_all_transaction == '1')
{
?>
      <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-blue">
            <div class="inner">
      
      <h4>
<?php
$select = mysqli_query($link, "SELECT * FROM borrowers WHERE branchid = '$session_id'") or die (mysqli_error($link));
$num = mysqli_num_rows($select);
echo $num;
?>
      </h4>
              <p>Customers</p>
            </div>
            <div class="icon">
              <i class="fa fa-users"></i>
            </div>
             <?php echo ($view_all_customers == 1) ? '<a href="customer.php?tid='.$_SESSION['tid'].'&&mid='.base64_encode("403").'" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>' : '<a href="#" class="small-box-footer">-------</a>'; ?>
          </div>
        </div>
<?php
}
else{
  echo '';
}
?>

<div class="col-lg-3 col-xs-6">
    <!-- small box -->
          
            
  <?php
  $bill_settings = mysqli_query($link, "SELECT * FROM billpayment WHERE companyid = '$agentid' AND status = 'Active'");
  $row_bill = mysqli_num_rows($bill_settings);
  ?>
    
<?php echo ($row_bill == 1) ? '<a href="pay_bills.php?id='.$_SESSION['tid'].'&&mid=NDA0"><img src="../image/bills_payment.jpg" height="120" width="230px"></a>' : '<div class="small-box bg-orange"><div class="inner"><h4>Not Activated!</h4><p>Bill Payment</p></div><div class="icon"><i class="fa fa-mobile"></i></div><a href="#" class="small-box-footer">-------</a></div>'; ?>

</div>

<div class="col-lg-12 col-xs-12">
<?php
$search_airtime_api = mysqli_query($link, "SELECT * FROM airtime_api WHERE status = 'Active'");
$fetch_airtime_api = mysqli_fetch_object($search_airtime_api);
?>
<a class="btn bg-blue" id="estore_airtime_api" value="<?php echo $fetch_airtime_api->username; ?>"> </a> 
</div>
    
   <section class="content">
      <div class="row">
    </div>
    
    <div class="box box-info">

            <div class="box-body">

              
    </form>
        
</div>  
</div>
    
    <!--  Event codes starts here-->
  
     
          <div class="box box-info">
<?php
$check = mysqli_query($link, "SELECT * FROM my_permission WHERE urole = '$urole' OR urole = '$arole'") or die ("Error" . mysqli_error($link));
$get_check = mysqli_fetch_array($check);
$view_all_loans = $get_check['view_all_loans'];
$borrowers_reports = $get_check['borrowers_reports'];
if($view_all_loans == '1' || $borrowers_reports == '1')
{
?>  

            <div class="box-body">
      <div class="bg-blue" align="center" class="style2" style="color: #FF0000"><b>LOAN INFORMATION CHART WITH YEARLY LOAN COLLECTION AND LAST DUE DATE</b></div>
             
       <div class="col-md-6">
         <div id="chartdiv"></div>                
       </div>
      
      
      <div class="col-md-6">
        <div id="chartdiv7"></div>
      </div>

<?php
}
else{
  echo '';
}
?>  
      </div>
    
</div>  
</div>  