<div class="box">
	       <div class="box-body">
			<div class="panel panel-success">
            <div class="panel-heading bg-blue">
            <h3 class="panel-title">All Pool Account</h3>
            </div>



             <div class="box-body">

			 <form class="form-horizontal" method="post" enctype="multipart/form-data">

             <div class="box-body">
			 
			<div class="form-group">
                  <label for="" class="col-sm-1 control-label" style="color:blue;">From</label>
                  <div class="col-sm-3">
                  <input name="dfrom" type="date" id="startDate" class="form-control" placeholder="To Date: 2018-05-01">
				  <span style="color: orange;">Date format: 2018-05-01</span>
                  </div>
				  
				  <label for="" class="col-sm-1 control-label" style="color:blue;">To</label>
                  <div class="col-sm-3">
                  <input name="dto" type="date" id="endDate" class="form-control" placeholder="To Date: 2018-05-24">
				  <span style="color: orange;">Date format: 2018-05-24</span>
                  </div>

                  <label for="" class="col-sm-1 control-label" style="color:blue;">Filter By</label>
                  <div class="col-sm-3">
                 <select name="filter_by" id="filterBy" class="form-control select2" style="width:100%">
    				<option value="" selected="selected">Filter By...</option>
                    <option value="all">All Pool Account</option>

                    <option disabled>Filter By Account Type</option>
                    <option value="agent">Agent</option>
                    <option value="corporate">Corporate</option>
                    
                    <option disabled>Filter By Individual</option>
                    <?php
                    $get2 = mysqli_query($link, "SELECT * FROM pool_account ORDER BY id DESC") or die (mysqli_error($link));
    				while($rows2 = mysqli_fetch_array($get2))
    				{
    				?>
    				<option value="<?php echo $rows2['userid']; ?>"><?php echo $rows2['account_number'].' - '.$rows2['account_name']; ?></option>
                    <?php } ?>
				</select>
                  </div>
                </div>
            
            
            <hr>
            <div class="table-responsive">
			    <table id="fetch_allpoolacct_data" class="table table-bordered table-striped">
			        <thead>
                    <tr>
                      <th><input type="checkbox" id="select_all"/></th>
                      <th>Acct. Type</th>
                      <th>Institution</th>
                      <th>Acct. Officer</th>
    				  <th>Acct. Number</th>
                      <th>Acct. Name</th>
                      <th>Bank Name</th>
                      <th>Available Balance</th>
                      <th>Opening Date</th>
                     </tr>
                    </thead>
                </table>
            </div>
			  
			 </form> 

</div>	
</div>	
</div>
</div>