<?php
include ("../config/session.php");
$PostType = $_GET['PostType'];

$myaltcall = mysqli_query($link, "SELECT * FROM member_settings WHERE companyid = '$bbranchid'");
$myaltrow = mysqli_fetch_array($myaltcall);

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
                    <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Service Name:</label>
                    <div class="col-sm-6">
                        <input name="servicename" type="text" class="form-control" value="<?php echo $servicename; ?>" readonly>
                    </div>
                    <label for="" class="col-sm-3 control-label" align="left"></label>
                </div>

                <div class="form-group">
                    <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Amount:</label>
                    <div class="col-sm-6">
                        <input name="amount" type="text" class="form-control" value="<?php echo ($price == "0.00") ? '' : $price; ?>" <?php echo ($price == "0.00") ? 'required' : 'readonly'; ?>>
                    </div>
                    <label for="" class="col-sm-3 control-label" align="left"></label>
                </div>

<?php
}
?>