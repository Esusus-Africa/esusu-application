<?php
include("../config/session.php");

$PostType = $_GET['PostType'];

$myaltcall = mysqli_query($link, "SELECT * FROM member_settings WHERE companyid = '$bbranchid'");
$myaltrow = mysqli_fetch_array($myaltcall);

if($PostType == "Other")
{
?>

<div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Other</label>
                  <div class="col-sm-6">
                  <input name="other_purpose" type="text" class="form-control" placeholder="Enter your savings purpose" required>
                  </div>
                  <label for="" class="col-sm-3 control-label"></label>
                  </div> 

<?php
}
else
{
  echo "";
}
?>

