<?php include("include/header.php"); ?>

  <section class="invoice">
    <!-- Table row -->
    <section class="content">

      <!-- Default box -->
      <div class="box">
          <?php
          $aid = $_GET['uid'];
          $act = $_GET['act'];
          $searchUser = mysqli_query($link, "SELECT * FROM virtual_account WHERE userid = '$aid' AND account_number = '$act'");
          $fetchUser = mysqli_fetch_array($searchUser);
          ?>
            <div class="box-header bg-blue" align="left">
              <h3 class="box-title">
                <?php echo $fetchUser['account_name'].' ('.$fetchUser['account_number'].')'; ?> 
                <?php echo ($book_wallet_loan == '1') ? '<a href="bookLoan.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("404").'&&uid='.$_GET['uid'].'&&act='.$_GET['act'].'&&tab=tab_1"><button type="button" class="btn btn-flat bg-'.(($myaltrow['theme_color'] == "") ? 'blue' : $myaltrow['theme_color']).'"><i class="fa fa-plus"></i>&nbsp;Book Loan</button></a>' : ''; ?> 
	            <?php echo ($wallet_loan_repayment == '1' || $individual_wallet_loan_repayment == '1' || $branch_wallet_loan_repayment == '1') ? '<a href="wlRepayment.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("404").'&&uid='.$_GET['uid'].'&&act='.$_GET['act'].'"><button type="button" class="btn btn-flat bg-'.(($myaltrow['theme_color'] == "") ? 'blue' : $myaltrow['theme_color']).'"><i class="fa fa-plus"></i>&nbsp;View Repayment</button></a>' : ''; ?>
                <?php echo ($wallet_due_payment == '1' || $individual_wallet_due_payment == '1' || $branch_wallet_due_payment == '1') ? '<a href="wlDuePayment.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("404").'&&uid='.$_GET['uid'].'&&act='.$_GET['act'].'"><button type="button" class="btn btn-flat bg-'.(($myaltrow['theme_color'] == "") ? 'blue' : $myaltrow['theme_color']).'"><i class="fa fa-plus"></i>&nbsp;View Due Payment</button></a>' : ''; ?>
              </h3>
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
                  
                  <label for="" class="col-sm-1 control-label" style="color:blue;">Filter By</label>
                  <div class="col-sm-3">
                  <select name="ttype" id="transType" class="form-control select2" style="width:100%" required>
                    <option value="" selected="selected">Filter By...</option>
                    <!-- FILTER BY ALL LOANS -->
					<option value="all">All Loans</option>

                    <!-- FILTER BY ALL PENDING LOANS -->
                    <option value="pend">Pending Loans</option>

                    <!-- FILTER BY ALL APPROVED LOANS -->
                    <option value="apprv">Approved Loans</option>

					
					<option disabled>Filter By Loan Officer</option>
					<?php
					($list_employee === "1" && $list_branch_employee != "1") ? $get2 = mysqli_query($link, "SELECT * FROM user WHERE created_by = '$institution_id' AND virtual_acctno != '' ORDER BY userid DESC") or die (mysqli_error($link)) : "";
					($list_employee != "1" && $list_branch_employee === "1") ? $get2 = mysqli_query($link, "SELECT * FROM user WHERE created_by = '$institution_id' AND branchid = '$isbranchid' AND virtual_acctno != '' ORDER BY userid DESC") or die (mysqli_error($link)) : "";
					while($rows2 = mysqli_fetch_array($get2))
					{
					?>
					<option value="<?php echo $rows2['id']; ?>"><?php echo $rows2['virtual_acctno'].' - '.$rows2['name'].' '.$rows2['fname'].' '.$rows2['mname']; ?></option>
					<?php } ?>

					<?php
    				//TO BE ACCESSIBLE BY THE SUPER ADMIN ONLY
    				if($list_branches === "1")
    				{
    				?>
    				<option disabled>Filter By Branch</option>
    				<?php
    				$get5 = mysqli_query($link, "SELECT * FROM branches WHERE created_by = '$institution_id' ORDER BY id") or die (mysqli_error($link));
    				while($rows5 = mysqli_fetch_array($get5))
    				{
    				?>
    				<option value="<?php echo $rows5['branchid']; ?>"><?php echo $rows5['bname'].' ['.$rows5['branchid'].']'; ?></option>
    				<?php } ?>
    				<?php
    				}
    				else{
    				    //nothing
    				}
    				?>
				  </select>
                  </div>
                </div>
                
                <input name="filter_by" id="filterBy" type="hidden" class="form-control" value="<?php echo $_GET['uid']; ?>"/>
                
                <input name="sfilter_by" id="sfilterBy" type="hidden" class="form-control" value="<?php echo $_GET['act']; ?>"/>
                
            </div>
            <!-- /.box -->
                
                
        <hr>    
            <div class="table-responsive">
			    <table id="fetch_walletloan_history_data" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th><input type="checkbox" id="select_all"/></th>
                  <th>Action</th>
                  <th>lid</th>
                  <th>Account Name</th>
            	  <th>Amount</th>
                  <th>Interest Rate</th>
            	  <th>Interest Amount</th>
            	  <th>Duration</th>
            	  <th>Amount+Interest</th>
            	  <th>Loan Balance</th>
            	  <th>Booked By</th>
                  <th>Reviewer</th>
                  <th>Status</th>
            	  <th>Date/Time</th>
                </tr>
                </thead>
                </table>
            </div>

       
             </div>
      <!-- /.box -->
    
    </div>
    <!-- /.box -->
    
    </section>
    <!-- /.content -->
  </section>
<!-- ./wrapper -->

<script type="text/javascript" language="javascript">
 $(document).ready(function(){

  var dataTable = $('#fetch_walletloan_history_data').DataTable({
   "processing" : true,
   "serverSide" : true,
   'serverMethod': 'post',
   "ajax" : {
    url:"fetch_walletloan_history.php",
    'data': function(data){
        // Read values
        var mydfrom = $('#startDate').val();
        var mydto = $('#endDate').val();
        var myptype = $('#transType').val();
        var myfilterby = $('#filterBy').val();
        var mysfilterBy = $('#sfilterBy').val();

        // Append to data
        data.startDate = mydfrom;
        data.endDate = mydto;
        data.transType = myptype;
        data.filterBy = myfilterby;
        data.sfilterBy = mysfilterBy;
    }
   },
   dom: 'lBfrtip',
   buttons: [
    'copy', 'csv', 'excel', 'pdf', 'print'
   ],
   "lengthMenu": [ [10, 25, 50, 100, 200, 300, 500, -1], [10, 25, 50, 100, 200, 300, 500, "All"] ]
  });
  
  $('#startDate').change(function () {
    dataTable.draw();
  });

  $('#endDate').change(function () {
    dataTable.draw();
  });
  
  $('#transType').change(function(){
    dataTable.draw();
  });
  
  $('#filterBy').change(function(){
    dataTable.draw();
  });
  
  $('#sfilterBy').change(function(){
    dataTable.draw();
  });
  
 });
 
</script>

<?php include("include/footer.php"); ?>