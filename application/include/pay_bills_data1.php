<div class="box">
	       <div class="box-body">
			<div class="panel panel-success">
            <div class="panel-heading bg-blue">
            <h3 class="panel-title"> <a href="dashboard.php?id=<?php echo $_SESSION['tid']; ?>&&mid=NDAx"><button type="button" class="btn btn-flat bg-orange"><i class="fa fa-mail-reply-all"></i>&nbsp;Back</button> </a> <i class="fa fa-mobile"></i> Initiate Bill Payment</h3>
            </div>

             <div class="box-body">
          
			 <form class="form-horizontal" method="post" enctype="multipart/form-data" action="process_billpay1.php">

             <div class="box-body">
			 			
			<div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue">Select Bills</label>
                  <div class="col-sm-9">
        <select name="bill_type"  class="form-control select2" id="bill_type1" required style="width:100%">
        <option selected="selected">Select bills to pay...</option>
        <option value="tv">Tv Bills</option>
        <option value="internet">Internet Bills</option>
        <option value="electricity">Electricity Bills</option>      
        </select>
                    </div>
                    </div>

      <span id='ShowValueFrank'></span>
      <span id='ShowValueFrank'></span>
      <span id='ShowValueFrank'></span>
      <span id='ShowValueFrank'></span>
      <span id='ShowValueFrank'></span>
      <span id='ShowValueFrank'></span>
      <span id='ShowValueFrank'></span>
      <span id='ShowValueFrank'></span>
      <span id='ShowValueFrank'></span>
			
			 </div>
			  
			 </form> 

</div>	
</div>	
</div>
</div>