<?php include "../config/session.php"; ?>  

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
<?php
$institution_id = "INST-191587338134";
$call = mysqli_query($link, "SELECT * FROM member_settings WHERE companyid = '$institution_id'");
if(mysqli_num_rows($call) == 0)
{
echo "<script>alert('Data Not Found1!'); </script>";
}
else
{
while($row = mysqli_fetch_assoc($call)){
?>

<link href="../<?php echo $row['logo']; ?>" rel="icon" type="dist/img">
<?php }}?>

  <title><?php echo $inst_name; ?></title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.6 -->
  <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="../dist/css/AdminLTE.min.css">
    <link rel="stylesheet" href="../plugins/datatables/dataTables.bootstrap.css">
    
    <!-- Datatable new code -->
 <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>

 <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/r/dt/jq-2.1.4,jszip-2.5.0,pdfmake-0.1.18,dt-1.10.9,af-2.0.0,b-1.0.3,b-colvis-1.0.3,b-html5-1.0.3,b-print-1.0.3,se-1.0.1/datatables.min.css"/>

 <script type="text/javascript" src="https://cdn.datatables.net/r/dt/jq-2.1.4,jszip-2.5.0,pdfmake-0.1.18,dt-1.10.9,af-2.0.0,b-1.0.3,b-colvis-1.0.3,b-html5-1.0.3,b-print-1.0.3,se-1.0.1/datatables.min.js"></script>
  
 <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
  
 <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>


  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
</head>
<body>
<div class="wrapper">
  <!-- Main content -->
 
</div>
  <section class="invoice">
    <!-- Table row -->
    <section class="content">

      <!-- Default box -->
      <div class="box">
          <?php
          $aid = $_GET['uid'];
          $searchUser = mysqli_query($link, "SELECT * FROM user WHERE id = '$aid'");
          $fetcUNum = mysqli_num_rows($searchUser);
          $fetchUser = mysqli_fetch_array($searchUser);
          
          $searchBorrower = mysqli_query($link, "SELECT * FROM borrowers WHERE account = '$aid'");
          $fetcBNum = mysqli_num_rows($searchBorrower);
          $fetchBorrower = mysqli_fetch_array($searchBorrower);
          ?>
            <div class="box-header bg-blue" align="center">
              <h3 class="box-title"><?php echo ($fetcUNum == 1 && $fetcBNum == 0) ? (($fetchUser['businessName'] == "") ? $fetchUser['name'].' '.$fetchUser['lname'].' '.$fetchUser['mname'].' ('.$fetchUser['virtual_acctno'].')' : $fetchUser['businessName'].' ('.$fetchUser['virtual_acctno'].')') : $fetchBorrower['fname'].' '.$fetchBorrower['lname'].' '.$fetchBorrower['mname'].' ('.$fetchBorrower['virtual_acctno'].')'; ?></h3>
            </div>
	         <div class="box-body">
           
             <div class="box-body">
                 
	           <div class="form-group">
                  <label for="" class="col-sm-1 control-label" style="color:blue;">From</label>
                  <div class="col-sm-3">
                  <input name="dfrom" type="date" id="startDate" class="form-control" placeholder="To Date: 2018-05-01" required>
				  <span style="color: orange;">Date should be in this format: 2018-05-01</span>
                  </div>
				  
				  <label for="" class="col-sm-1 control-label" style="color:blue;">To</label>
                  <div class="col-sm-3">
                  <input name="dto" type="date" id="endDate" class="form-control" placeholder="To Date: 2018-05-24" required>
				  <span style="color: orange;">Date should be in this format: 2018-05-24</span>
                  </div>
                  
                  <label for="" class="col-sm-1 control-label" style="color:blue;">Type</label>
                  <div class="col-sm-3">
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
                    <option value="BANK_TRANSFER">Inter-bank Transfer</option>
                    <option value="card">Card Payment</option>
                    <option value="Stamp Duty">Stamp Duty</option>
                    <option value="Card_Withdrawal">Card Withdrawal</option>
                    <option value="Cardless_Withdrawal">Cardless Withdrawal</option>
                    <option value="Report Charges">Report Charges</option>
                    <option value="Topup-Prepaid_Card">PrepaidCard Topup</option>
                    <option value="p2p-transfer">P2P-Transfer</option>
                    <option value="p2p-reversal">P2P-Reversal</option>
                    <option value="p2p-debit">P2P-Debit</option>
                    <option value="BVN_Charges">BVN Charges</option>
                    <option value="DD_Activation">Direct Debit Activation</option>
                    <option value="VerveCard_Verification">VerveCard Verification</option>
                    <option value="USSD">USSD</option>
                    <option value="POS">POS</option>
                    <option value="Charges">Charges</option>
				  </select>
                  </div>
                </div>
                
                <input name="filter_by" id="filterBy" type="hidden" class="form-control" value="<?php echo $_GET['uid']; ?>"/>
                
            </div>
                
                
        <hr>    
            <div class="table-responsive">
			    <table id="fetch_indivWallet_history_data" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th><input type="checkbox" id="select_all"/></th>
                  <th>Account Name</th>
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
      <!-- /.box -->

    </section>
    <!-- /.content -->
  </div>
<!-- ./wrapper -->
<script src="../plugins/jQuery/jquery-2.2.3.min.js"></script>
<!-- Bootstrap 3.3.6 -->
<script src="../bootstrap/js/bootstrap.min.js"></script>
<!-- SlimScroll -->
<script src="../plugins/slimScroll/jquery.slimscroll.min.js"></script>
<!-- FastClick -->
<script src="../plugins/fastclick/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="../dist/js/app.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="../dist/js/demo.js"></script>
<!-- Bootstrap 3.3.6 -->
<!-- DataTables -->
<script src="../plugins/datatables/jquery.dataTables.min.js"></script>
<script src="../plugins/datatables/dataTables.bootstrap.min.js"></script>

<!-- FastClick -->
<script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
<!-- FastClick -->
<script src="https://cdn.datatables.net/buttons/1.6.2/js/dataTables.buttons.min.js"></script>
<!-- FastClick -->
<script src="https://cdn.datatables.net/buttons/1.6.2/js/buttons.flash.min.js"></script>
<!-- FastClick -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<!-- FastClick -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<!-- FastClick -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<!-- FastClick -->
<script src="https://cdn.datatables.net/buttons/1.6.2/js/buttons.html5.min.js"></script>
<!-- FastClick -->
<script src="https://cdn.datatables.net/buttons/1.6.2/js/buttons.print.min.js"></script>

<script type="text/javascript" language="javascript">
 $(document).ready(function(){

  var dataTable = $('#fetch_indivWallet_history_data').DataTable({
   "processing" : true,
   "serverSide" : true,
   'serverMethod': 'post',
   "ajax" : {
    url:"fetch_indivWallet_history.php",
    'data': function(data){
        // Read values
        var mydfrom = $('#startDate').val();
        var mydto = $('#endDate').val();
        var myptype = $('#transType').val();
        var myfilterby = $('#filterBy').val();

        // Append to data
        data.startDate = mydfrom;
        data.endDate = mydto;
        data.transType = myptype;
        data.filterBy = myfilterby;
    }
   },
   dom: 'lBfrtip',
   buttons: [
    'copy', 'csv', 'excel', 'pdf', 'print'
   ],
   "lengthMenu": [ [10, 25, 50, 100, 200, 300, 500, -1], [10, 25, 50, 100, 200, 300, 500, "All"] ]
  });
  
  $('#startDate').on('click', function () {
    dataTable.draw();
  });

  $('#endDate').on('click', function () {
    dataTable.draw();
  });
  
  $('#transType').change(function(){
    dataTable.draw();
  });
  
  $('#filterBy').change(function(){
    dataTable.draw();
  });
  
 });
 
</script>

</body>
</html>
