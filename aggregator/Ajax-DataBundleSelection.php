<?php
include ("../config/session.php");
$PostType = $_GET['PostType'];

if($PostType == ""){
    echo "";
}
else{
    $parameter = (explode(',',$PostType));
    $pcode = $parameter[0];
    $amount = $parameter[1];
    $operator = $parameter[2];
?>

                <input name="pcode" type="hidden" class="form-control" value="<?php echo $pcode; ?>">

                <div class="form-group">
                    <label for="" class="col-sm-3 control-label" style="color:blue;">Operator:</label>
                    <div class="col-sm-6">
                        <input name="telco" type="text" class="form-control" value="<?php echo $operator; ?>" readonly>
                    </div>
                    <label for="" class="col-sm-3 control-label" align="left"></label>
                </div>

                <div class="form-group">
                    <label for="" class="col-sm-3 control-label" style="color:blue;">Amount:</label>
                    <div class="col-sm-6">
                        <input name="amount" type="text" class="form-control" value="<?php echo $amount; ?>" readonly>
                    </div>
                    <label for="" class="col-sm-3 control-label" align="left"></label>
                </div>

<?php
}
?>