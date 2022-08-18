<?php
include ("../config/session1.php");
$PostType = $_GET['PostType'];

$myaltcall = mysqli_query($link, "SELECT * FROM member_settings WHERE companyid = '$institution_id'");
$myaltrow = mysqli_fetch_array($myaltcall);

if($PostType == "NIN-SEARCH"){
?>

        <div class="form-group">
            <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">NIN Number:</label>
            <div class="col-sm-10">
                <input name="searchParameter" type="text" class="form-control" placeholder="Enter NIN Number" required>
            </div>
        </div>

<?php
}elseif($PostType == "NIN-PHONE-SEARCH"){
?>

        <div class="form-group">
            <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Phone Number:</label>
            <div class="col-sm-10">
                <input name="searchParameter" type="text" class="form-control" placeholder="Enter Phone Number" required>
            </div>
        </div>

<?php
}elseif($PostType == "BVN-FULL-DETAILS"){
?>

        <div class="form-group">
            <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">BVN Number:</label>
            <div class="col-sm-10">
                <input name="searchParameter" type="text" class="form-control" placeholder="Enter BVN Number" required>
            </div>
        </div>

<?php
}elseif($PostType == "NIN-DEMOGRAPHIC-SEARCH"){
?>

        <div class="form-group">
            <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">First Name:</label>
            <div class="col-sm-10">
                <input name="firstName" type="text" class="form-control" placeholder="Enter First Name" required>
            </div>
        </div>

        <div class="form-group">
            <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Last Name:</label>
            <div class="col-sm-10">
                <input name="lastName" type="text" class="form-control" placeholder="Enter Last Name" required>
            </div>
        </div>

        <div class="form-group">
            <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Gender:</label>
            <div class="col-sm-10">
                <select name="gender" class="form-control select2" required>
                    <option value="" selected='selected'>Select Gender&hellip;</option>
                    <option value="m">Male</option>
                    <option value="f">Female</option>
                </select>
            </div>
        </div>

        <div class="form-group">
            <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Date of Birth:</label>
            <div class="col-sm-10">
                <input name="dob" type="date" class="form-control" placeholder="Enter Date of Birth" required>
            </div>
        </div>

<?php
}else{
    //Do nothing
}
?>