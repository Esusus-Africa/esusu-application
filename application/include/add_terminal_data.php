<div class="box">
	       <div class="box-body">
			<div class="panel panel-success">
            <div class="panel-heading bg-blue">
            <h3 class="panel-title"><i class="fa fa-link"></i> Add New Terminal</h3>
            </div>

             <div class="box-body">
 <?php
if(isset($_POST['addTerminal']))
{
  $terminal_id =  mysqli_real_escape_string($link, $_POST['terminal_id']);
  $trace_id = mysqli_real_escape_string($link, $_POST['trace_id']);
  $tOwner_code =  mysqli_real_escape_string($link, $_POST['tOwner_code']);
  $terminal_issurer = mysqli_real_escape_string($link, $_POST['terminal_issurer']);
  $terminal_serial = mysqli_real_escape_string($link, $_POST['terminal_serial']);
  $channel =  mysqli_real_escape_string($link, $_POST['channel']);
  $tModel_code =  mysqli_real_escape_string($link, $_POST['tModel_code']);
  $stype = mysqli_real_escape_string($link, $_POST['stype']);
  $ctype = mysqli_real_escape_string($link, $_POST['ctype']);
  $charges = mysqli_real_escape_string($link, $_POST['charges']);
  $commission = mysqli_real_escape_string($link, $_POST['commission']);
  $stampduty = mysqli_real_escape_string($link, $_POST['stampduty']);
  $soption = mysqli_real_escape_string($link, $_POST['soption']);
  $ptsp = mysqli_real_escape_string($link, $_POST['ptsp']);
  $visibility = mysqli_real_escape_string($link, $_POST['visibility']);
  $activation_fee = mysqli_real_escape_string($link, $_POST['activation_fee']);
  $activation_comm = mysqli_real_escape_string($link, $_POST['activation_comm']);
  $currentdate = date("Y-m-d h:i:s");

  $insert = mysqli_query($link, "INSERT INTO terminal_reg VALUE(null,'$terminal_id','$terminal_issurer','$tOwner_code','$channel','','','','','','$tModel_code','','','','','','','','0.0','0.0','$soption','$stype','0','Available','$currentdate','$currentdate','','$uid','$ctype','$charges','0','$commission','$stampduty','','','$activation_fee','$activation_comm','$trace_id','$ptsp','$visibility','$terminal_serial','','','','','','','','','')");
  
  if(!$insert){

    echo "<div class='alert bg-orange'>Opps!....Unable to add new terminal!!</div>";

  }
  else{

    echo "<div class='alert bg-blue'>New terminal created successfully!!</div>";

  }

}
?>           
			 <form class="form-horizontal" method="post" enctype="multipart/form-data">

             <div class="box-body">
                 
             <div class="form-group">
                <label for="" class="col-sm-3 control-label" style="color:blue;">Channel:</label>
                <div class="col-sm-6">
                    <select name="channel"  class="form-control select2" id="terminalChannel" required>
                      <option value="" selected>Select Channel</option>
                      <option value="POS">POS</option>
                      <option value="USSD">USSD</option>
                    </select>
                </div>
                <label for="" class="col-sm-3 control-label"></label>
             </div>
             
             <span id='ShowValueFrank2'></span>
             <span id='ShowValueFrank2'></span>

            <div class="form-group">
                <label for="" class="col-sm-3 control-label" style="color:blue;">Terminal Issurer:</label>
                <div class="col-sm-6">
                  <input name="terminal_issurer" type="text" class="form-control" placeholder="Enter Terminal Issurer" required>
                  <span style="color: orange;"> <b>Input the name of the Institution that issue the Terminal.</b></span>
                  </div>
                <label for="" class="col-sm-3 control-label"></label>
            </div>

            <div class="form-group">
                <label for="" class="col-sm-3 control-label" style="color:blue;">Terminal Serial:</label>
                <div class="col-sm-6">
                  <input name="terminal_serial" type="text" class="form-control" placeholder="Enter Terminal Serial" required>
                  </div>
                <label for="" class="col-sm-3 control-label"></label>
            </div>

            <div class="form-group">
                <label for="" class="col-sm-3 control-label" style="color:blue;">Activation Fee:</label>
                <div class="col-sm-6">
                  <input name="activation_fee" type="text" class="form-control" placeholder="Enter Activation Fee" required>
                  </div>
                <label for="" class="col-sm-3 control-label"></label>
            </div>
            
            <div class="form-group">
                <label for="" class="col-sm-3 control-label" style="color:blue;">Activation Commission(%):</label>
                <div class="col-sm-6">
                  <input name="activation_comm" type="text" class="form-control" placeholder="Enter Activation Commission in %" required>
                  </div>
                <label for="" class="col-sm-3 control-label"></label>
            </div>

        <div class="form-group">
                <label for="" class="col-sm-3 control-label" style="color:blue;">Charge Type:</label>
                <div class="col-sm-6">
                    <select name="ctype" class="form-control select2" required>
                      <option value="" selected>Select Type</option>
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
                <label for="" class="col-sm-3 control-label" style="color:blue;">Commission:</label>
                <div class="col-sm-6">
                  <input name="commission" type="text" class="form-control" placeholder="Enter Commission" required>
                  </div>
                <label for="" class="col-sm-3 control-label"></label>
            </div>

            <div class="form-group">
                <label for="" class="col-sm-3 control-label" style="color:blue;">Stampduty (>=10,000):</label>
                <div class="col-sm-6">
                  <input name="stampduty" type="number" class="form-control" placeholder="Enter Stampduty of Amount >= 10,000" required>
                  </div>
                <label for="" class="col-sm-3 control-label"></label>
            </div>

            <div class="form-group">
                <label for="" class="col-sm-3 control-label" style="color:blue;">PTSP/Terminal Provider:</label>
                <div class="col-sm-6">
                  <select name="ptsp" class="form-control select2" required>
                      <option value="" selected>Select PTSP</option>
                      <option value="ITEX">ITEX</option>
                      <option value="CGATE">CGATE</option>
                      <option value="ETOP">ETOP</option>
                      <option value="UP">UP</option>
                      <option value="RUBIES">RUBIES</option>
                    </select>
                  </div>
                <label for="" class="col-sm-3 control-label"></label>
            </div>
			
			    <div class="form-group">
                <label for="" class="col-sm-3 control-label" style="color:blue;">Terminal Model Code:</label>
                <div class="col-sm-6">
                  <input name="tModel_code" type="text" class="form-control" placeholder="Enter the Terminal Model Code e.g PAX-S90" required>
                  </div>
                <label for="" class="col-sm-3 control-label"></label>
            </div>

            <div class="form-group">
                <label for="" class="col-sm-3 control-label" style="color:blue;">Visibility:</label>
                <div class="col-sm-6">
                    <select name="visibility" class="form-control select2" required>
                      <option value="" selected>Select Visibility</option>
                      <option value="Yes">Yes</option>
                      <option value="No">No</option>
                    </select>
                </div>
                <label for="" class="col-sm-3 control-label"></label>
            </div>

            <div class="form-group">
                <label for="" class="col-sm-3 control-label" style="color:blue;">Allow Settlement</label>
                <div class="col-sm-6">
                    <select name="soption" class="form-control select2" id="settlementType" required>
                      <option value="" selected>Select Settings</option>
                      <option value="Yes">Yes</option>
                      <option value="No">No</option>
                    </select>
                </div>
                <label for="" class="col-sm-3 control-label"></label>
            </div>

            <span id='ShowValueFrank'></span>
            <span id='ShowValueFrank'></span>
				  
			 </div>
			 
			<div class="form-group" align="right">
                <label for="" class="col-sm-3 control-label" style="color:blue;"></label>
                <div class="col-sm-6">
                	<button name="addTerminal" type="submit" class="btn bg-blue"><i class="fa fa-plus">&nbsp;Submit</i></button>
                </div>
                <label for="" class="col-sm-3 control-label"></label>
            </div>
			  
			 </form> 

</div>	
</div>	
</div>
</div>