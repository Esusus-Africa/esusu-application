<div class="row">     
        <section class="content">  
          <div class="box box-success">
            <div class="box-body">
              <div class="table-responsive">
             <div class="box-body">
<form method="post">
<a href="poolAcct_history.php?id=<?php echo $_SESSION['tid']; ?>&&mid=NDA0&&tab=tab_1"><button type="button" class="btn btn-flat bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>"><i class="fa fa-refresh"></i>&nbsp;Refresh</button></a> 
<?php
if($view_pool_account_history == '1')
{
?>
      
    <button type="button" class="btn btn-flat bg-white" align="left" disabled>&nbsp;<b>Pool Account Bal:</b>&nbsp;
    <strong class="alert bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">
    
    <?php
    $search_iNGN = mysqli_query($link, "SELECT SUM(availableBal) FROM pool_account WHERE userid = '$iuid'");
    $get_i = mysqli_fetch_array($search_iNGN);    
    $total_NGN = number_format($get_i['SUM(availableBal)'],2,'.',',');
    
    echo $icurrency.$total_NGN;
    ?>
    
    </strong>
      </button>

      <button type="button" class="btn btn-flat bg-white" align="left" disabled>&nbsp;<b>Pool Account Details:</b>&nbsp;
    <strong class="alert bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">
    
    <?php
    $search_pool = mysqli_query($link, "SELECT * FROM pool_account WHERE userid = '$iuid'");
    $get_pool = mysqli_fetch_array($search_pool);    
    
    echo $get_pool['account_number'].' | '.$get_pool['account_name'].' | '.$get_pool['bank_name'];
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
             <li <?php echo ($_GET['tab'] == 'tab_1') ? "class='active'" : ''; ?>><a href="poolAcct_history.php?tid=<?php echo $_SESSION['tid']; ?>&&mid=NDA0&&tab=tab_1">Pool Account History</a></li>
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
                    <option value="POS">POS</option>
                    <option value="Charges">Other Charges</option>
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