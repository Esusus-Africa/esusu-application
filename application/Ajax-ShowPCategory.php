<?php
include ("config/connect.php");
$PostType = $_GET['PostType'];

if($PostType == "Other")
{
?>

<div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Other</label>
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

