<?php
include ("config/connect.php");
$PostType = $_GET['PostType'];

if($PostType == "Flat Rate")
{
?>

<div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Flat Rate</label>
                  <div class="col-sm-9">
                  <input name="dividend" type="number" class="form-control" placeholder="Enter Flat Rate e.g 1000, 2000, 7000 etc without any special character added to it" required>
                  </div>
                  </div> 
		
<?php
}
elseif($PostType == "Percentage")
{
?>

<div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Percentage</label>
                  <div class="col-sm-9">
                  <input name="dividend" type="text" class="form-control" placeholder="Enter Percentage e.g 1, 2, 9 etc without % sign" required>
                  </div>
                  </div> 

<?php } ?>

