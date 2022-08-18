<div class="box">
	       <div class="box-body">
			<div class="panel panel-success">
            <div class="panel-heading bg-blue">
            <h3 class="panel-title"><i class="fa fa-link"></i> Assign Terminal</h3>
            </div>

             <div class="box-body">
 <?php
if(isset($_POST['assignTerminal']))
{
	$merchant =  mysqli_real_escape_string($link, $_POST['merchant']);
    $terminal_id = mysqli_real_escape_string($link, $_POST['terminal']);
    $terminalInitiator = mysqli_real_escape_string($link, $_POST['terminalInitiator']);
    $terminalOperator = mysqli_real_escape_string($link, $_POST['terminalOperator']);
    $poolAccount = mysqli_real_escape_string($link, $_POST['poolAccount']);
    $ctype = mysqli_real_escape_string($link, $_POST['ctype']);
    $charges = mysqli_real_escape_string($link, $_POST['charges']);
    $charge_comm = mysqli_real_escape_string($link, $_POST['charge_comm']);
    $tpin =  mysqli_real_escape_string($link, $_POST['tpin']);
    $smsalert = mysqli_real_escape_string($link, $_POST['smsalert']);

    $search_mymerchant = mysqli_query($link, "SELECT * FROM institution_data WHERE institution_id = '$merchant'");
    $fetch_mymerchant = mysqli_fetch_array($search_mymerchant);
    $merchant_name = $fetch_mymerchant['institution_name'];
    $merchant_email = $fetch_mymerchant['official_email'];
    $merchant_phone_no = $fetch_mymerchant['official_phone'];
    $currentDate = date("Y-m-d h:i:s");

	if($control_pin != $tpin){
	    
	    echo "<div class='alert bg-orange'>Opps!...Invalid Transaction Pin Entered</div>";
	    
	}
	else{

        $update = mysqli_query($link, "UPDATE terminal_reg SET merchant_id = '$merchant', merchant_name = '$merchant_name', merchant_email = '$merchant_email', merchant_phone_no = '$merchant_phone_no', slip_header = '$merchant_name', slip_footer = 'Powered by $merchant_name', sms_alert = '$smsalert', initiatedBy = '$terminalInitiator', tidoperator = '$terminalOperator', ctype = '$ctype', charges = '$charges', poolAccount = '$poolAccount', charge_comm = '$charge_comm', terminal_status = 'Assigned', dateUpdated = '$currentDate', assignedBy = '$uid' WHERE (terminal_id = '$terminal_id' OR trace_id = '$terminal_id')") or die ("Error: " . mysqli_error($link));
        
        if(!$update){

            echo "<div class='alert bg-blue'>Opps!....Unable to assign terminal!!</div>"; 

        }
        else{

            echo "<div class='alert bg-blue'>Terminal assigned successfully!!</div>";

        }

    }

}
?>           
			 <form class="form-horizontal" method="post" enctype="multipart/form-data">

             <div class="box-body">
			 
			<div class="form-group">
                <label for="" class="col-sm-3 control-label" style="color:blue;">Institution / Agent:</label>
                <div class="col-sm-6">
                    <select name="merchant"  class="form-control select2" required>
                      <option value="" selected>Select Institution</option>
                        <?php
                        $search = mysqli_query($link, "SELECT * FROM institution_data WHERE status = 'Approved'");
                        while($get_search = mysqli_fetch_array($search))
                        {
                        ?>
                      <option value="<?php echo $get_search['institution_id']; ?>"><?php echo $get_search['institution_id']; ?> - <?php echo $get_search['institution_name']; ?></option>
                        <?php } ?>
                </select>
                </div>
                <label for="" class="col-sm-3 control-label"></label>
            </div>


            <div class="form-group">
                <label for="" class="col-sm-3 control-label" style="color:blue;">Terminal:</label>
                <div class="col-sm-6">
                    <select name="terminal"  class="form-control select2" required>
                      <option value="" selected>Select Terminal</option>
                        <?php
                        $search = mysqli_query($link, "SELECT * FROM terminal_reg WHERE terminal_status = 'Available'");
                        while($get_search = mysqli_fetch_array($search))
                        {
                        ?>
                      <option value="<?php echo ($get_search['trace_id'] == "") ? $get_search['terminal_id'] : $get_search['trace_id']; ?>"><?php echo ($get_search['trace_id'] == "") ? $get_search['terminal_id']."/".$get_search['terminal_serial'] : $get_search['trace_id']."/".$get_search['terminal_id']; ?> - <?php echo $get_search['terminal_model_code']; ?> - <?php echo $get_search['terminal_issurer']; ?> (<?php echo $get_search['channel']; ?>)</option>
                        <?php } ?>
                    </select>
                </div>
                <label for="" class="col-sm-3 control-label"></label>
            </div>

            
            <div class="form-group">
            <label for="" class="col-sm-3 control-label" style="color:blue;">Initiator/Aggregator:</label>
            <div class="col-sm-6">
                <select name="terminalInitiator"  class="form-control select2">
                    <option value="" selected>Select Initiator/Aggregator</option>
                    <?php
                    $search1 = mysqli_query($link, "SELECT * FROM user WHERE comment = 'Approved' AND virtual_acctno != ''");
                    while($get_search1 = mysqli_fetch_array($search1))
                    {
                    ?>
                    <option value="<?php echo $get_search1['id']; ?>"><?php echo $get_search1['virtual_acctno'].' - '.$get_search1['name'].' '.$get_search1['lname'].' '.$get_search1['mname']; ?></option>
                    <?php } ?>
                </select>
            </div>
            <label for="" class="col-sm-3 control-label"></label>
            </div>


            <div class="form-group">
            <label for="" class="col-sm-3 control-label" style="color:blue;">Operator:</label>
            <div class="col-sm-6">
                <select name="terminalOperator"  class="form-control select2">
                    <option value="" selected>Select Operator</option>
                    <?php
                    $search = mysqli_query($link, "SELECT * FROM user WHERE comment = 'Approved' AND virtual_acctno != ''");
                    while($get_search = mysqli_fetch_array($search))
                    {
                      $realOpName2 = ($get_search['businessName'] == "") ? $get_search['name'].' '.$get_search['lname'].' '.$get_search['mname'] : $get_search['businessName'];
                    ?>
                    <option value="<?php echo $get_search['id']; ?>"><?php echo $get_search['virtual_acctno'].' - '.$realOpName2; ?></option>
                    <?php } ?>
                </select>
            </div>
            <label for="" class="col-sm-3 control-label"></label>
            </div>


            <div class="form-group">
            <label for="" class="col-sm-3 control-label" style="color:blue;">Pool Account:</label>
            <div class="col-sm-6">
                <select name="poolAccount"  class="form-control select2">
                    <option value="" selected>Select Pool Account</option>
                    <?php
                    $search = mysqli_query($link, "SELECT * FROM pool_account");
                    while($get_search = mysqli_fetch_array($search))
                    {
                    ?>
                    <option value="<?php echo $get_search['account_number']; ?>"><?php echo $get_search['account_number'].' - '.$get_search['account_name']; ?></option>
                    <?php } ?>
                    <option value="">None</option>
                </select>
            </div>
            <label for="" class="col-sm-3 control-label"></label>
            </div>


            <div class="form-group">
                <label for="" class="col-sm-3 control-label" style="color:blue;">Charge Type:</label>
                <div class="col-sm-6">
                    <select name="ctype"  class="form-control select2" required>
                      <option value="" selected>Select Charge Type</option>
                      <option value="Percentage">Percentage</option>
                      <option value="Flat">Flat</option>
                    </select>
                </div>
                <label for="" class="col-sm-3 control-label"></label>
            </div>


            <div class="form-group">
                <label for="" class="col-sm-3 control-label" style="color:blue;">Charges:</label>
                <div class="col-sm-6">
                  <input name="charges" type="text" class="form-control" placeholder="Enter Charges" required>
                  </div>
                <label for="" class="col-sm-3 control-label"></label>
            </div>


            <div class="form-group">
                <label for="" class="col-sm-3 control-label" style="color:blue;">Charge Comm.(%):</label>
                <div class="col-sm-6">
                  <input name="charge_comm" type="text" class="form-control" placeholder="Enter Charge Commission without Symbol" value="0" required>
                  <span style="color: orange;"> <b>This is required only if POOL ACCOUNT is selected.</b></span>
                  </div>
                <label for="" class="col-sm-3 control-label"></label>
            </div>

            
            <div class="form-group">
                <label for="" class="col-sm-3 control-label" style="color:blue;">SMS Alert Option:</label>
                <div class="col-sm-6">
                    <select name="smsalert" class="form-control select2" required>
                      <option value="" selected>Select SMS Alert Option</option>
                      <option value="Yes">Yes</option>
                      <option value="No">No</option>
                    </select>
                </div>
                <label for="" class="col-sm-3 control-label"></label>
            </div>
			
			<div class="form-group">
                <label for="" class="col-sm-3 control-label" style="color:blue;">Authorization Pin:</label>
                <div class="col-sm-6">
                  <input name="tpin" type="password" inputmode="numeric" pattern="[0-9]*" class="form-control" placeholder="Enter your transaction pin" maxlength="4" required>
                </div>
                <label for="" class="col-sm-3 control-label"></label>
            </div>
				  
			 </div>
			 
			<div class="form-group" align="right">
                <label for="" class="col-sm-3 control-label" style="color:blue;"></label>
                <div class="col-sm-6">
                	<button name="assignTerminal" type="submit" class="btn bg-blue"><i class="fa fa-link">&nbsp;Submit</i></button>
                </div>
                <label for="" class="col-sm-3 control-label"></label>
            </div>
			  
			 </form> 

</div>	
</div>	
</div>
</div>