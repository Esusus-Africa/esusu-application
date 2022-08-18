<?php
$PostType = $_GET['PostType'];

if($PostType == "Other")
{
?>

<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Other</label>
                  <div class="col-sm-10">
                  <input name="company_sector" type="text" class="form-control" placeholder="Enter Other Sector" required>
                  </div>
                  </div> 
		
<?php
}
else
{
  echo "";
}
?>

