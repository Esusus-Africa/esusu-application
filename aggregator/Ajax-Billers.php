<?php
include ("../config/session.php");
$PostType = $_GET['PostType'];

if($PostType == ""){
    echo "";
}
else{
    $parameter = (explode(',',$PostType));
    $productcode = $parameter[0];
    $billercode = $parameter[1];
    $price = $parameter[2];
    $servicename = $parameter[3];
?>

                <input name="productcode" type="hidden" class="form-control" value="<?php echo $productcode; ?>">

                <input name="billercode" type="hidden" class="form-control" value="<?php echo $billercode; ?>" id="bcode">

                <div class="form-group">
                    <label for="" class="col-sm-3 control-label" style="color:blue;">Service Name:</label>
                    <div class="col-sm-6">
                        <input name="servicename" type="text" class="form-control" value="<?php echo $servicename; ?>" readonly>
                    </div>
                    <label for="" class="col-sm-3 control-label" align="left"></label>
                </div>

                <div class="form-group">
                    <label for="" class="col-sm-3 control-label" style="color:blue;">Amount:</label>
                    <div class="col-sm-6">
                        <input name="amount" type="text" class="form-control" value="<?php echo ($price == "0.00") ? '' : $price; ?>" <?php echo ($price == "0.00") ? 'required' : 'readonly'; ?>>
                    </div>
                    <label for="" class="col-sm-3 control-label" align="left"></label>
                </div>

<?php
}
?>