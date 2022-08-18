<?php
include ("../config/session.php");
$PostType = $_GET['PostType'];

if($PostType == "Yes")
{
?>          
                <div class="form-group">
                <label for="" class="col-sm-3 control-label" style="color:blue;">Settlement Type:</label>
                <div class="col-sm-6">
                    <select name="stype" class="form-control select2" required>
                      <option value="" selected>Select Settlement Type</option>
                      <option value="manual">manual</option>
                      <option value="auto">auto</option>
                    </select>
                </div>
                <label for="" class="col-sm-3 control-label"></label>
                
<?php
}
else{
?>

                <input name="stype" type="hidden" class="form-control" value="none">

<?php
}
?>