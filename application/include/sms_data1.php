<div class="box">
         <div class="box-body">
      <div class="panel panel-success">
            <div class="panel-heading bg-blue">
            <h3 class="panel-title"><i class="fa fa-envelope-o"></i>  SMS Settings</h3>
            </div>

             <div class="box-body">

<?php
$se = mysqli_query($link, "SELECT * FROM sms WHERE smsuser = '$session_id'") or die (mysqli_error($link));
if(mysqli_num_rows($se) == 1)
{
$sel = mysqli_fetch_array($se);
$api_name = $sel['sms_gateway'];
$username = $sel['username'];
$password = $sel['password'];
$api = $sel['api'];
$status = $sel['status'];
?>
<form class="form-horizontal" action="edit_api.php" method="post" enctype="multipart/form-data">

<div class="box-body">

 <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Gateway Name</label>
                  <div class="col-sm-10">
                  <input name="gateway_name" type="text" class="form-control" placeholder="Gateway Name" value="<?php echo $api_name; ?>" required>
                  </div>
                  </div>

 <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Username</label>
                  <div class="col-sm-10">
                  <input name="username" type="text" class="form-control" placeholder="Username" value="<?php echo $username; ?>" required>
                  </div>
                  </div>

<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Password</label>
                  <div class="col-sm-10">
                  <input name="password" type="text" class="form-control" placeholder="Password" value="<?php echo $password; ?>" required>
                  </div>
                  </div>

<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">API</label>
                  <div class="col-sm-10">
                  <input name="api" type="text" class="form-control" placeholder="API URL" value="<?php echo $api; ?>" readonly>
                  </div>
                  </div>

<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Status</label>
                  <div class="col-sm-10">
                  <select name="status" class="form-control select2" required style="width:100%">
           <option value="<?php echo $status; ?>" selected><?php echo $status; ?></option>
           <option value="NotActivated">NotActivated</option>
           <option value="Activated">Activated</option>
          </select>
                  </div>
                  </div>

</div>

<div align="right">
     <div class="box-footer">
        <button name="submit" type="submit" class="btn bg-blue btn-flat"><i class="fa fa-save">&nbsp;Update Settings</i></button>

     </div>
</div>

 </form>
<?php
}
else{
?>

<form class="form-horizontal" action="edit_api.php" method="post" enctype="multipart/form-data">

<div class="box-body">

 <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Gateway Name</label>
                  <div class="col-sm-10">
                  <input name="gateway_name" type="text" class="form-control" value="ZEEZZPLANET" placeholder="Gateway Name" required>
                  </div>
                  </div>

 <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Username</label>
                  <div class="col-sm-10">
                  <input name="username" type="text" class="form-control" placeholder="Username"  required>
                  </div>
                  </div>

<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Password</label>
                  <div class="col-sm-10">
                  <input name="password" type="text" class="form-control" placeholder="Password"  required>
                  </div>
                  </div>

<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">API</label>
                  <div class="col-sm-10">
                  <input name="api" type="text" class="form-control" value="http://sms.zeezzplanet.com/components/com_spc/smsapi.php?" placeholder="API URL" required>
                  </div>
                  </div>

</div>

<div align="right">
     <div class="box-footer">
        <button name="save" type="submit" class="btn bg-blue btn-flat"><i class="fa fa-save">&nbsp;Save Settings</i></button>

     </div>
</div>

 </form>

<?php
}
?>

</div>  
</div>  
</div>
</div>