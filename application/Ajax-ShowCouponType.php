<?php
include ("config/connect.php");
$PostType = $_GET['PostType'];

if($PostType == "one_off")
{
?>

				<div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Start Date</label>
                  <div class="col-sm-9">
                  <input name="start_date" type="date" class="form-control" required>
                  </div>
                 </div> 

                 <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">End Date</label>
                  <div class="col-sm-9">
                  <input name="end_date" type="date" class="form-control" required>
                  </div>
                 </div> 
                 
<?php
}
else
{
  echo "";
}
?>

