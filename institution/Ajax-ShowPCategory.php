<?php
include ("../config/session1.php");
$PostType = $_GET['PostType'];

$myaltcall = mysqli_query($link, "SELECT * FROM member_settings WHERE companyid = '$institution_id'");
$myaltrow = mysqli_fetch_array($myaltcall);

if($PostType == "Other")
{
?>

<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Other</label>
                  <div class="col-sm-9">
                  <input name="plan_category" type="text" class="form-control" placeholder="Enter Other Category" required>
                  </div>
                  </div> 
		
<?php
}
else
{
  echo "";
}
?>

