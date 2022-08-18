<?php
include ("../config/connect.php");
$PostType = $_GET['PostType'];

if($PostType == "All")
{
?>
<hr>
<div class="scrollable1">

    <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">SELECT PROPERTIES
                    <p style="color: orange;"><input type="checkbox" id="selectall" onClick="selectAll(this)"/> <b>Tick to Enable all</b></p></label>
                  <div class="col-sm-9">
    <table class="table table-responsive">
    <?php
      $search_module_properties = mysqli_query($link, "SELECT * FROM module_property WHERE mtype = 'backend' ORDER BY mname");
      while($fetch_mproperties = mysqli_fetch_array($search_module_properties))
      {
      ?>
      <tr>
        <td>
          <b><?php echo $fetch_mproperties['mname']; ?></b>
        </td>
        <td>
          <?php echo ucfirst(str_replace('_', ' ', $fetch_mproperties['mproperty'])); ?>
        </td>
        <td>
          <input name="pnameeee[]" type="text" value="<?php echo $fetch_mproperties['mproperty']; ?>" width="150px" disabled>
          <input id="optionsCheckbox" class="checkbox" name="pname[]" type="checkbox" value="<?php echo $fetch_mproperties['mproperty']; ?>">
        </td>
      </tr>
      <?php } ?>
    </table>
  </div>
  </div>

</div>
<hr>
    <div align="right">
       <div class="box-footer">
       <button class="btn bg-blue ks-btn-file" type="submit" name="save"><span class="fa fa-cloud-upload"></span> Submit </button> 
       </div>
    </div>
<?php
}
else{
  //Do Nothing
}
?>