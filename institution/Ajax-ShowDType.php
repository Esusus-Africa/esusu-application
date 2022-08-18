<?php
include ("../config/session1.php");
$PostType = $_GET['PostType'];

$myaltcall = mysqli_query($link, "SELECT * FROM member_settings WHERE companyid = '$institution_id'");
$myaltrow = mysqli_fetch_array($myaltcall);

if($PostType == "Flat Rate")
{
?>

<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Flat Rate</label>
                  <div class="col-sm-10">
                  <input name="dividend" type="number" class="form-control" placeholder="Enter Flat Rate e.g 1000, 2000, 7000 etc without any special character added to it" required>
                  </div>
                  </div> 
		
<?php
}
elseif($PostType == "Percentage")
{
?>

<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Percentage</label>
                  <div class="col-sm-10">
                  <input name="dividend" type="text" class="form-control" placeholder="Enter Percentage e.g 1, 2, 9 etc without % sign" required>
                  </div>
                  </div> 

<?php 
} 
elseif($PostType == "Ratio")
{
?>

<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Customer Shares</label>
                  <div class="col-sm-10">
                  <input name="dividend" type="text" class="form-control" placeholder="Enter Ratio e.g 10, 20, 60 etc" required>
                  </div>
                  </div> 
<!--
<div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Ratio</label>
                  <label class="col-sm-4">
                  <input name="dividend" type="number" class="form-control" placeholder="Enter Ratio e.g 1 - 10 etc" max="10" required>
                  </label> 
                  <label class="col-sm-1">
                      <div align="center">:</div>
                  </label>
                  <label class="col-sm-4">
                  <input name="dividend" type="number" class="form-control" placeholder="Enter Ratio e.g 1 - 10 etc" max="10" required>
                  </label>
                  </div> 
-->

<?php 
} 
?>

