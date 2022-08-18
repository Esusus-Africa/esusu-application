<?php include("include/header.php"); ?>

<?php
$tmid = $_GET['tmid'];
$select = mysqli_query($link, "SELECT * FROM terminal_reg WHERE (terminal_id = '$tmid' OR trace_id = '$tmid')") or die (mysqli_error($link));
$row = mysqli_fetch_array($select);
$tidoperator = $row['tidoperator'];

$searchQuery = mysqli_query($link, "SELECT * FROM user WHERE id = '$tidoperator'");
$fetchQuery = mysqli_fetch_array($searchQuery);
$operatorName = $fetchQuery['virtual_acctno'].' - '.$fetchQuery['name'].' '.$fetchQuery['lname'].' '.$fetchQuery['mname'];
?>


    <div class="modal-dialog">
          <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
            <legend style="color: blue;"><b>Re-Assign Operator</b></legend>
        </div>
        <div class="modal-body">

        <form class="form-horizontal" method="post" enctype="multipart/form-data">

<?php
if(isset($_POST['reAssign'])){

    $terminalId = $_GET['tmid'];
    $terminalOperator = mysqli_real_escape_string($link, $_POST['terminalOperator']);
    $smsalert = mysqli_real_escape_string($link, $_POST['smsalert']);

    $searchUser = mysqli_query($link, "SELECT * FROM user WHERE id = '$terminalOperator'");
    $fetchUser = mysqli_fetch_array($searchUser);
    $opBranchid = $fetchUser['branchid'];

    $query = mysqli_query($link, "UPDATE terminal_reg SET tidoperator = '$terminalOperator', branchid = '$opBranchid', sms_alert = '$smsalert' WHERE terminal_status = 'Assigned' AND (terminal_id = '$terminalId' OR trace_id = '$terminalId')") or die ("Error: " . mysqli_error($link));

    if(!$query){

        echo "<div class='alert bg-blue'>Opps!....Unable to Update!!</div>";

    }
    else{

        echo "<div class='alert bg-blue'>Update Done Successfully!!</div>";

    }

}
?>

            <div class="box-body">

            <div class="form-group">
            <label for="" class="col-sm-4 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Operator:</label>
            <div class="col-sm-7">
                <select name="terminalOperator"  class="form-control select2" required>
                    <option value="<?php echo $tidoperator; ?>" selected><?php echo $operatorName; ?></option>
                    <?php
                    $search = mysqli_query($link, "SELECT * FROM user WHERE created_by = '$institution_id' AND comment = 'Approved'");
                    while($get_search = mysqli_fetch_array($search))
                    {
                    ?>
                    <option value="<?php echo $get_search['id']; ?>"><?php echo $get_search['virtual_acctno'].' - '.$get_search['name'].' '.$get_search['lname'].' '.$get_search['mname']; ?></option>
                    <?php } ?>
                </select>
            </div>
            <label for="" class="col-sm-1 control-label"></label>
            </div>

            <div class="form-group">
                <label for="" class="col-sm-4 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">SMS Alert:</label>
                <div class="col-sm-7">
                    <select name="smsalert" class="form-control select2" required>
                      <option value="<?php echo $row['sms_alert']; ?>" selected><?php echo $row['sms_alert']; ?></option>
                      <option value="Yes">Yes</option>
                      <option value="No">No</option>
                    </select>
                </div>
                <label for="" class="col-sm-1 control-label"></label>
            </div>
				  
			 </div>
			 
			<div class="form-group" align="right">
                <label for="" class="col-sm-4 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;"></label>
                <div class="col-sm-7">
                	<button name="reAssign" type="submit" class="btn bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>"><i class="fa fa-link">&nbsp;Submit</i></button>
                </div>
                <label for="" class="col-sm-1 control-label"></label>
            </div>

			 </form> 

        </div>
        <div style="font-size:10px;"><?php include("include/footer.php"); ?></div>
      </div>   
      
    </div>


