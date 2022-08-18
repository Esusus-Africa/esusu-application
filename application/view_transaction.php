<?php include("include/header.php"); ?>
<div class="wrapper">
  <!-- Main content -->
 <section class="invoice">
      <!-- title row -->
<?php
$mybid = $_GET['idm'];
$verify_bid = mysqli_query($link, "SELECT * FROM branches WHERE branchid = '$mybid'") or die ("Error:" . mysqli_error($link));
$get_verified = mysqli_fetch_array($verify_bid);
?>
      <div class="row">
        <div class="col-xs-12">
          <h2 class="page-header">
            <i class="fa fa-globe"></i> Branch: <b><?php echo strtoupper($get_verified['bname']); ?></b>
          </h2>
        </div>
      </div>


	  <div align="center"><h3 class="page-header"><b>BRANCH TRANSACTION HISTORY</b></h3></div>
        <!-- Table row -->
        <div class="row">

              <div class="box-body">
                 
                 <div class="form-group">
                      <label for="" class="col-sm-1 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">From</label>
                      <div class="col-sm-3">
                      <input name="dfrom" type="date" id="startDate" class="form-control" placeholder="To Date: 2018-05-01">
                      <span style="color: <?php echo ($myaltrow['alternate_color'] == '') ? 'blue' : $myaltrow['alternate_color']; ?>;">Date should be in this format: 2018-05-01</span>
                      </div>
              
                      <label for="" class="col-sm-1 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">End Date</label>
                      <div class="col-sm-3">
                      <input name="dto" type="date" id="endDate" class="form-control" placeholder="To Date: 2018-05-24">
                      <span style="color: <?php echo ($myaltrow['alternate_color'] == '') ? 'blue' : $myaltrow['alternate_color']; ?>;">Date should be in this format: 2018-05-24</span>
                      </div>

                      <label for="" class="col-sm-1 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Type</label>
                      <div class="col-sm-3">
                      <select name="ptype" id="pmtType" class="form-control select2" style="width:100%">
                        <option value="" selected="selected">Filter By Payment Type...</option>
                        <option value="All">All Transaction</option>
                        <option value="Deposit">Deposit</option>
                        <option value="Withdraw">Withdraw</option>
                        <option value="Withdraw-Charges">Withdraw-Charges</option>
                        <option value="Cash">Cash</option>
                        <option value="Bank">Bank</option>
                      </select>
                      </div>
					            <input name="filter_by" type="hidden" id="filterBy" class="form-control" value="<?php echo $mybid; ?>">
                 </div>
    
              </div>
              
      
        <hr>
        <div class="table-responsive">
          <table id="fetch_transaction_data" class="table table-bordered table-striped">
            <thead>
              <tr>
                  <th><input type="checkbox" id="select_all"/></th>
                  <th>TxID</th>
                  <th>Client Name</th>
                  <th>Branch Name</th>
                  <th>Savings Product</th>
                  <th>AcctNo.</th>
                  <th>Acct. Name</th>
                  <th>Phone</th>
                  <th>Debit</th>
                  <th>Credit</th>
                  <th>Balance</th>
                  <th>Date/Time</th>
                  <th>Posted By</th>
              </tr>
            </thead>
          </table>
        </div>

        </div>


        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
      <!-- /.row -->
    </section>
  <!-- /.content -->
</div>
</body>

<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script>window.jQuery || document.write('<script src="../vendor/jquery/jquery-1.9.1.min.js"><\/script>')</script>
<script src="../vendor/jquery/main.js"></script>

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

  var dataTable = $('#fetch_transaction_data').DataTable({
   "processing" : true,
   "serverSide" : true,
   'serverMethod': 'post',
   "ajax" : {
    url:"fetch_transaction.php",
    'data': function(data){
        // Read values
        var mydfrom = $('#startDate').val();
        var mydto = $('#endDate').val();
        var myptype = $('#pmtType').val();
        var myfilterby = $('#filterBy').val();

        // Append to data
        data.startDate = mydfrom;
        data.endDate = mydto;
        data.pmtType = myptype;
        data.filterBy = myfilterby;
    }
   },
   dom: 'lBfrtip',
   buttons: [
    'copy', 'csv', 'excel', 'pdf', 'print'
   ],
   "lengthMenu": [ [10, 25, 50, -1], [10, 25, 50, "All"] ]
  });
  
  $('#startDate').on('click', function () {
    dataTable.draw();
  });

  $('#endDate').on('click', function () {
    dataTable.draw();
  });
  
  $('#pmtType').change(function(){
    dataTable.draw();
  });
  
  $('#filterBy').change(function(){
    dataTable.draw();
  });
  
 });
 
</script>
</html>
