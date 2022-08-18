<?php
include ("../config/session.php");
$PostType = $_GET['PostType'];

$myaltcall = mysqli_query($link, "SELECT * FROM member_settings WHERE companyid = '$bbranchid'");
$myaltrow = mysqli_fetch_array($myaltcall);

if($PostType == "Phone"){
?>

                <div class="form-group">
                    <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">Phone</label>
                    <div class="col-sm-10">
                        <input name="vType" type="text" class="form-control" placeholder="Enter Phone" required>
                    </div>
                </div>

<?
}
elseif($PostType == "NIN"){
?>

                <div class="form-group">
                    <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">NIN Number</label>
                    <div class="col-sm-10">
                        <input name="vType" type="text" class="form-control" placeholder="Enter NIN Number" required>
                    </div>
                </div>

<?php
}
else{

    echo "";

}
?>