<div class="row">     
        <section class="content">  
          <div class="box box-success">
            <div class="box-body">
              <div class="table-responsive">
             <div class="box-body">
<form method="post">
<a href="poolAcct_history.php?id=<?php echo $_SESSION['tid']; ?>&&mid=NDE5&&tab=tab_1"><button type="button" class="btn btn-flat bg-orange"><i class="fa fa-refresh"></i>&nbsp;Refresh</button></a> 
<?php
if($view_all_pool_account == '1')
{
?>
      
    <button type="button" class="btn btn-flat bg-white" align="left" disabled>&nbsp;<b>Overall Pool Bal:</b>&nbsp;
    <strong class="alert bg-blue">
    
    <?php
    $search_iNGN = mysqli_query($link, "SELECT SUM(availableBal) FROM pool_account");
    $get_i = mysqli_fetch_array($search_iNGN);    
    $total_NGN = number_format($get_i['SUM(availableBal)'],2,'.',',');
    
    echo $fetchsys_config['currency'].$total_NGN;
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
             <li <?php echo ($_GET['tab'] == 'tab_1') ? "class='active'" : ''; ?>><a href="poolAcct_history.php?tid=<?php echo $_SESSION['tid']; ?>&&mid=NDE5&&tab=tab_1">All Pool Account History</a></li>
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
                    <option value="ACCOUNT_TRANSFER">Account Transfer</option>
                    <option value="POS">POS</option>
                    <option value="Charges">Other Charges</option>
                  </select>
                  </div>
				  
				  <label for="" class="col-sm-1 control-label" style="color:blue;">Filter By</label>
                  <div class="col-sm-5">
                 <select name="filter_by" id="filterBy" class="form-control select2" style="width:100%" required>
    				<option value="" selected="selected">Filter By Institution, Agent, Merchant, Customer...</option>
    				<option value="all">All Pool Account History</option>
    				
    				<?php
    				$get1 = mysqli_query($link, "SELECT * FROM pool_account ORDER BY id DESC") or die (mysqli_error($link));
    				while($rows1 = mysqli_fetch_array($get1))
    				{
    				?>
    				<option value="<?php echo $rows1['companyid']; ?>"><?php echo $rows1['account_number'].' - '.$rows1['account_name'].' ('.$rows1['bank_name'].')'; ?></option>
    				<?php } ?>
    				
				</select>
                  </div>
                </div>
                
                </div>
                
                
            <hr>    
            <div class="table-responsive">
			    <table id="fetch_poolAcct_transaction_data" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th><input type="checkbox" id="select_all"/></th>
                  <th>UserID</th>
                  <th>Sender</th>
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