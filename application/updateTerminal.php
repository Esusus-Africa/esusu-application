<?php include("include/header.php"); ?>

<?php
$tmid = $_GET['tmid'];
$select = mysqli_query($link, "SELECT * FROM terminal_reg WHERE (terminal_id = '$tmid' OR trace_id = '$tmid')") or die (mysqli_error($link));
$row = mysqli_fetch_array($select);
$tidoperator = $row['tidoperator'];
$initiatedBy = $row['initiatedBy'];
$termStatus = $row['terminal_status'];
$poolAcct = $row['poolAccount'];

$searchQuery = mysqli_query($link, "SELECT * FROM user WHERE id = '$tidoperator'");
$fetchQuery = mysqli_fetch_array($searchQuery);
$realOpName = ($fetchQuery['businessName'] == "") ? $fetchQuery['name'].' '.$fetchQuery['lname'].' '.$fetchQuery['mname'] : $fetchQuery['businessName'];
$operatorName = $fetchQuery['virtual_acctno'].' - '.$realOpName;
$instid = $fetchQuery['created_by'];

$searchPool = mysqli_query($link, "SELECT * FROM pool_account WHERE userid = '$initiatedBy'");
$fetchPool = mysqli_fetch_array($searchPool);
$pool_acctno = $fetchPool['account_number'];
$pool_acctname = $fetchPool['account_name'];

$userIni = mysqli_query($link, "SELECT * FROM user WHERE id = '$initiatedBy'");
$fetchIni = mysqli_fetch_array($userIni);
$initiatorName = $fetchIni['name'].' '.$fetchIni['lname'].' '.$fetchIni['mname'].' ('.$fetchIni['virtual_acctno'].')';
?>


    <div class="modal-dialog">
          <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
            <legend style="color: blue;"><b>Terminal Configuration</b></legend>
        </div>
        <div class="modal-body">

        <form class="form-horizontal" method="post" enctype="multipart/form-data">

<?php
if(isset($_POST['reAssign'])){

    $terminalId = $_GET['tmid'];
    $terminalInitiator = mysqli_real_escape_string($link, $_POST['terminalInitiator']);
    $terminalOperator = mysqli_real_escape_string($link, $_POST['terminalOperator']);
    $ptsp = mysqli_real_escape_string($link, $_POST['ptsp']);
    $terminal_serial = mysqli_real_escape_string($link, $_POST['terminal_serial']);
    $ctype = mysqli_real_escape_string($link, $_POST['ctype']);
    $charges = mysqli_real_escape_string($link, $_POST['charges']);
    $charge_comm = mysqli_real_escape_string($link, $_POST['charge_comm']);
    $commission = mysqli_real_escape_string($link, $_POST['commission']);
    $smsalert = mysqli_real_escape_string($link, $_POST['smsalert']);
    $status = mysqli_real_escape_string($link, $_POST['status']);
    
    //Activation Fee
    $activation_fee = mysqli_real_escape_string($link, $_POST['activation_fee']);
    $activation_comm = mysqli_real_escape_string($link, $_POST['activation_comm']);

    //Settlement & Visibility
    $soption = mysqli_real_escape_string($link, $_POST['soption']);
    $stype = ($soption == "No") ? "" : mysqli_real_escape_string($link, $_POST['stype']);
    $visibility = mysqli_real_escape_string($link, $_POST['visibility']);

    $poolAccount = mysqli_real_escape_string($link, $_POST['poolAccount']);

    $searchMem = mysqli_query($link, "SELECT * FROM member_settings WHERE companyid = '$instid'");
    $fetchMem = mysqli_fetch_array($searchMem);
    $merchantName = $fetchMem['cname'];

    $searchMem2 = mysqli_query($link, "SELECT * FROM institution_data WHERE institution_id = '$instid'");
    $fetchMem2 = mysqli_fetch_array($searchMem2);
    $mEmail = $fetchMem2['official_email'];
    $mPhone = $fetchMem2['official_phone'];

    $mylink = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; 

    $query = mysqli_query($link, "UPDATE terminal_reg SET initiatedBy = '$terminalInitiator', assignedBy = '$uid', merchant_id = '$instid', merchant_name = '$merchantName', merchant_email = '$mEmail', merchant_phone_no = '$mPhone', ctype = '$ctype', charges = '$charges', charge_comm = '$charge_comm', commission = '$commission', tidoperator = '$terminalOperator', sms_alert = '$smsalert', allow_settlement = '$soption', settlmentType = '$stype', activation_fee = '$activation_fee', activation_comm = '$activation_comm', visibility = '$visibility', ptsp = '$ptsp', terminal_serial = '$terminal_serial', poolAccount = '$poolAccount', terminal_status = '$status', slip_header = '$merchantName', slip_footer = 'Powered by $merchantName' WHERE (terminal_id = '$terminalId' OR trace_id = '$terminalId')") or die ("Error: " . mysqli_error($link));

    if(!$query){

        echo "<div class='alert bg-blue'>Opps!....Unable to Update!!</div>";

    }
    else{

        echo "<div class='alert bg-blue'>Update Done Successfully!!</div>";
        echo '<meta http-equiv="refresh" content="3;url='.$mylink.'">';

    }

}
?>

            <div class="box-body">

            <div class="form-group">
            <label for="" class="col-sm-4 control-label" style="color:blue;">Initiator/Aggregator:</label>
            <div class="col-sm-7">
                <select name="terminalInitiator"  class="form-control select2">
                    <option value="<?php echo ($initiatedBy == '') ? '' : $initiatedBy; ?>" selected><?php echo ($initiatedBy == "") ? "---Select--" : $initiatorName; ?></option>
                    <?php
                    $search1 = mysqli_query($link, "SELECT * FROM user WHERE comment = 'Approved' AND virtual_acctno != ''");
                    while($get_search1 = mysqli_fetch_array($search1))
                    {
                    ?>
                    <option value="<?php echo $get_search1['id']; ?>"><?php echo $get_search1['virtual_acctno'].' - '.$get_search1['name'].' '.$get_search1['lname'].' '.$get_search1['mname']; ?></option>
                    <?php } ?>
                </select>
            </div>
            <label for="" class="col-sm-1 control-label"></label>
            </div>

            <div class="form-group">
            <label for="" class="col-sm-4 control-label" style="color:blue;">Operator:</label>
            <div class="col-sm-7">
                <select name="terminalOperator"  class="form-control select2">
                    <option value="<?php echo $tidoperator; ?>" selected><?php echo $operatorName; ?></option>
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
            <label for="" class="col-sm-1 control-label"></label>
            </div>

            <div class="form-group">
            <label for="" class="col-sm-4 control-label" style="color:blue;">Pool Account:</label>
            <div class="col-sm-7">
                <select name="poolAccount"  class="form-control select2">
                    <option value="<?php echo ($poolAcct == "") ? '' : $pool_acctno; ?>" selected><?php echo ($poolAcct == "") ? "None" : $pool_acctno.' - '.$pool_acctname; ?></option>
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
            <label for="" class="col-sm-1 control-label"></label>
            </div>

            <div class="form-group">
                <label for="" class="col-sm-4 control-label" style="color:blue;">Charge Type:</label>
                <div class="col-sm-7">
                    <select name="ctype"  class="form-control select2" required>
                      <option value="<?php echo $row['ctype']; ?>" selected><?php echo $row['ctype']; ?></option>
                      <option value="Percentage">Percentage</option>
                      <option value="Flat">Flat</option>
                    </select>
                </div>
                <label for="" class="col-sm-1 control-label"></label>
            </div>

            <div class="form-group">
                <label for="" class="col-sm-4 control-label" style="color:blue;">Charges:</label>
                <div class="col-sm-7">
                  <input name="charges" type="text" class="form-control" placeholder="Enter Charges" value="<?php echo $row['charges']; ?>" required>
                  </div>
                <label for="" class="col-sm-1 control-label"></label>
            </div>

            <div class="form-group">
                <label for="" class="col-sm-4 control-label" style="color:blue;">Charge Comm.(%):</label>
                <div class="col-sm-7">
                  <input name="charge_comm" type="text" class="form-control" placeholder="Enter Charge Commission without Symbol" value="<?php echo ($row['charge_comm'] == "") ? 0 : $row['charge_comm']; ?>" required>
                  <span style="color: orange;"> <b>This is required only if POOL ACCOUNT is selected.</b></span>
                  </div>
                <label for="" class="col-sm-1 control-label"></label>
            </div>

            <div class="form-group">
                <label for="" class="col-sm-4 control-label" style="color:blue;">PTSP:</label>
                <div class="col-sm-7">
                  <select name="ptsp" class="form-control select2" required>
                      <option value="<?php echo $row['ptsp']; ?>" selected><?php echo $row['ptsp']; ?></option>
                      <option value="ITEX">ITEX</option>
                      <option value="CGATE">CGATE</option>
                      <option value="ETOP">ETOP</option>
                      <option value="UP">UP</option>
                      <option value="RUBIES">RUBIES</option>
                    </select>
                  </div>
                <label for="" class="col-sm-1 control-label"></label>
            </div>

            <div class="form-group">
                <label for="" class="col-sm-4 control-label" style="color:blue;">Terminal Serial:</label>
                <div class="col-sm-7">
                  <input name="terminal_serial" type="text" class="form-control" value="<?php echo $row['terminal_serial']; ?>" placeholder="Enter Terminal Serial">
                  </div>
                <label for="" class="col-sm-1 control-label"></label>
            </div>

            <div class="form-group">
                <label for="" class="col-sm-4 control-label" style="color:blue;">SMS Alert:</label>
                <div class="col-sm-7">
                    <select name="smsalert" class="form-control select2">
                      <option value="<?php echo $row['sms_alert']; ?>" selected><?php echo $row['sms_alert']; ?></option>
                      <option value="Yes">Yes</option>
                      <option value="No">No</option>
                    </select>
                </div>
                <label for="" class="col-sm-1 control-label"></label>
            </div>

            <div class="form-group">
                <label for="" class="col-sm-4 control-label" style="color:blue;">Activation Fee:</label>
                <div class="col-sm-7">
                  <input name="activation_fee" type="text" class="form-control" value="<?php echo $row['activation_fee']; ?>" placeholder="Enter Activation Fee" <?php echo ($termStatus == "Assigned") ? 'readonly' : 'required'; ?>>
                  </div>
                <label for="" class="col-sm-1 control-label"></label>
            </div>
            
            <div class="form-group">
                <label for="" class="col-sm-4 control-label" style="color:blue;">Activation Comm.(%):</label>
                <div class="col-sm-7">
                  <input name="activation_comm" type="text" class="form-control" value="<?php echo ($row['activation_comm'] == '') ? 0 : $row['activation_comm']; ?>" placeholder="Enter Activation Commission in %" <?php echo ($termStatus == "Assigned") ? 'readonly' : 'required'; ?>>
                  </div>
                <label for="" class="col-sm-1 control-label"></label>
            </div>

            <div class="form-group">
                <label for="" class="col-sm-4 control-label" style="color:blue;">Aggregator:</label>
                <div class="col-sm-7">
                  <input name="aggr" type="text" class="form-control" value="<?php echo ($initiatedBy == "") ? "" : $initiatorName; ?>" readonly>
                  </div>
                <label for="" class="col-sm-1 control-label"></label>
            </div>

            <div class="form-group">
                <label for="" class="col-sm-4 control-label" style="color:blue;">Aggr. Commission:</label>
                <div class="col-sm-7">
                  <input name="commission" type="text" class="form-control" placeholder="Enter Commission" value="<?php echo $row['commission']; ?>" required>
                  </div>
                <label for="" class="col-sm-1 control-label"></label>
            </div>

            <div class="form-group">
                <label for="" class="col-sm-4 control-label" style="color:blue;">Allow Settlement</label>
                <div class="col-sm-7">
                    <select name="soption" class="form-control select2" required>
                      <option value="<?php echo $row['allow_settlement']; ?>" selected><?php echo $row['allow_settlement']; ?></option>
                      <option value="Yes">Yes</option>
                      <option value="No">No</option>
                    </select>
                </div>
                <label for="" class="col-sm-1 control-label"></label>
            </div>

            <?php
            if($row['allow_settlement'] == "No"){
              //Do Nothing
            }else{
            ?>

            <div class="form-group">
                <label for="" class="col-sm-4 control-label" style="color:blue;">Settlement Type</label>
                <div class="col-sm-7">
                    <select name="stype" class="form-control select2" required>
                      <option value="<?php echo $row['settlmentType']; ?>" selected><?php echo $row['settlmentType']; ?></option>
                      <option value="auto">auto</option>
                      <option value="manual">manual</option>
                    </select>
                </div>
                <label for="" class="col-sm-1 control-label"></label>
            </div>

            <?php
            }
            ?>

            <div class="form-group">
                <label for="" class="col-sm-4 control-label" style="color:blue;">Visibility</label>
                <div class="col-sm-7">
                    <select name="visibility" class="form-control select2" required>
                      <option value="<?php echo $row['visibility']; ?>" selected><?php echo $row['visibility']; ?></option>
                      <option value="Yes">Yes</option>
                      <option value="No">No</option>
                    </select>
                </div>
                <label for="" class="col-sm-1 control-label"></label>
            </div>

            <div class="form-group">
                <label for="" class="col-sm-4 control-label" style="color:blue;">Status:</label>
                <div class="col-sm-7">
                  <select name="status" class="form-control select2" required>
                      <option value="<?php echo $termStatus; ?>" selected><?php echo $termStatus; ?></option>
                      <option value="Available">Available</option>
                      <option value="Assigned">Assigned</option>
                    </select>
                  </div>
                <label for="" class="col-sm-1 control-label"></label>
            </div>
				  
			 </div>
			 
			<div class="form-group" align="right">
                <label for="" class="col-sm-4 control-label" style="color:blue;"></label>
                <div class="col-sm-7">
                	<button name="reAssign" type="submit" class="btn bg-blue"><i class="fa fa-link">&nbsp;Submit</i></button>
                </div>
                <label for="" class="col-sm-1 control-label"></label>
            </div>

			 </form> 

        </div>
        <div style="font-size:10px;"><?php include("include/footer.php"); ?></div>
      </div>   
      
    </div>