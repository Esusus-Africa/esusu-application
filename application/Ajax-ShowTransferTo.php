<?php
include ("../config/session.php");
$PostType = $_GET['PostType'];

if($PostType == "Institution")
{
?>

<div class="form-group">
    <label for="" class="col-sm-3 control-label" style="color:blue;">Select Institution</label>
    <div class="col-sm-9">
      <select name="tto" class="form-control select2" required>
        <option selected>Select Institution to Transfer to</option>
<?php
$search_inst = mysqli_query($link, "SELECT * FROM institution_data WHERE status = 'Approved' ORDER BY id DESC");
while($fetch_inst = mysqli_fetch_array($search_inst))
{
?>
        <option value="<?php echo $fetch_inst['institution_id']; ?>"><?php echo $fetch_inst['institution_name']; ?></option>
<?php } ?>
      </select>
    </div>
</div>

<?php 
}
elseif($PostType == "Agent")
{
?>

<div class="form-group">
    <label for="" class="col-sm-3 control-label" style="color:blue;">Select Agent</label>
    <div class="col-sm-9">
      <select name="tto" class="form-control select2" required>
        <option selected>Select Agent to Transfer to</option>
<?php
$search_agt = mysqli_query($link, "SELECT * FROM agent_data WHERE status = 'Approved' ORDER BY id DESC");
while($fetch_agt = mysqli_fetch_array($search_agt))
{
?>
        <option value="<?php echo $fetch_agt['agentid']; ?>"><?php echo $fetch_agt['fname']; ?></option>
<?php } ?>
      </select>
    </div>
</div>

<?php } ?>