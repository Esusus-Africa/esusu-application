<?php
include ("../config/session.php");
$PostType = $_GET['PostType'];

if($PostType == "USSD")
{
?>          
                <div class="form-group">
                <label for="" class="col-sm-3 control-label" style="color:blue;">Terminal ID:</label>
                <div class="col-sm-6">
                  <?php
                  /*$searchTerminal = mysqli_query($link, "SELECT * FROM terminal_reg WHERE channel = 'USSD' ORDER BY id DESC");
                  $fetchTerminal = mysqli_fetch_array($searchTerminal);
                  $terminalString = substr($fetchTerminal['terminal_id'],0,-3);
                  $terminalInt = substr($fetchTerminal['terminal_id'],5) + 1;
                  $myTerminalId = $terminalString.sprintf("%03d", $terminalInt);*/
                  ?>
                  <input name="terminal_id" type="text" value="4058ESU1" class="form-control" readonly>
                  </div>
                <label for="" class="col-sm-3 control-label"></label>
                </div>
                
                <div class="form-group">
                    <label for="" class="col-sm-3 control-label" style="color:blue;">MID:</label>
                    <div class="col-sm-6">
                      <input name="tOwner_code" type="text" value="<?php echo $cgate_mid; ?>" class="form-control" placeholder="Enter MID" readonly>
                      </div>
                    <label for="" class="col-sm-3 control-label"></label>
                </div>
                
                <div class="form-group">
                    <label for="" class="col-sm-3 control-label" style="color:blue;">Trace ID:</label>
                    <div class="col-sm-6">
                      <?php
                      $traceid = date("dys").rand(1000000,9999999);
                      ?>
                      <input name="trace_id" type="text" value="<?php echo $traceid; ?>" class="form-control" readonly>
                      </div>
                    <label for="" class="col-sm-3 control-label"></label>
                </div>
                
<?php
}
elseif($PostType == "POS"){
?>

                <div class="form-group">
                <label for="" class="col-sm-3 control-label" style="color:blue;">Terminal ID:</label>
                <div class="col-sm-6">
                  <input name="terminal_id" type="text" placeholder="Enter Terminal ID" class="form-control" required>
                  </div>
                <label for="" class="col-sm-3 control-label"></label>
                </div>
                
                <div class="form-group">
                    <label for="" class="col-sm-3 control-label" style="color:blue;">MID:</label>
                    <div class="col-sm-6">
                      <input name="tOwner_code" type="text" class="form-control" placeholder="Enter MID" required>
                      </div>
                    <label for="" class="col-sm-3 control-label"></label>
                </div>
                
                <input name="trace_id" type="hidden" value="" class="form-control">

<?php
}else{
    //Do nothing
}
?>