<div class="box">
	       <div class="box-body">
			<div class="panel panel-success">
            <div class="panel-heading bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">
            <h3 class="panel-title"><i class="fa fa-envelope-o"></i>  SMS Settings</h3>
            </div>

             <div class="box-body">

<?php
$se = mysqli_query($link, "SELECT * FROM sms WHERE smsuser = '$institution_id'") or die (mysqli_error($link));
if(mysqli_num_rows($se) == 1)
{
$sel = mysqli_fetch_array($se);
$api_name = $sel['sms_gateway'];
$username = $sel['username'];
$password = $sel['password'];
$api = $sel['api'];
$status = $sel['status'];
?>

<form class="form-horizontal" method="post" enctype="multipart/form-data">

<?php
if(isset($_POST['submit'])){

   $gateway_name = mysqli_real_escape_string($link, $_POST['gateway_name']);
   $username = mysqli_real_escape_string($link, $_POST['username']);
   $password = mysqli_real_escape_string($link, $_POST['password']);
   $api = mysqli_real_escape_string($link, $_POST['api']);
   $status = mysqli_real_escape_string($link, $_POST['status']);
 
   $sql = mysqli_query($link, "UPDATE sms SET sms_gateway = '$gateway_name', username = '$username', password = '$password', api = '$api', status = '$status' WHERE smsuser = '$institution_id'") or die("Error: ". mysqli_error($link));
   
   echo "<div class='alert bg-blue'>SMS Settings Update successfully!</div>";
	 echo '<meta http-equiv="refresh" content="5;url=sms.php?id='.$_SESSION['tid'].'&&mid=NDEx">';

}
?>

<div class="box-body">

   <div class="form-group">
                    <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Gateway Name</label>
                    <div class="col-sm-6">
                        <input name="gateway_name" type="text" class="form-control" placeholder="Gateway Name" value="<?php echo $api_name; ?>" required>
                    </div>
                    <label for="" class="col-sm-3 control-label"></label>
                  </div>

   <div class="form-group">
                    <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Username</label>
                    <div class="col-sm-6">
                        <input name="username" type="text" class="form-control" placeholder="Username" value="<?php echo $username; ?>" required>
                    </div>
                    <label for="" class="col-sm-3 control-label"></label>
                  </div>

   <div class="form-group">
                    <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">API Key</label>
                    <div class="col-sm-6">
                        <input name="password" type="text" class="form-control" placeholder="API Key" value="<?php echo $password; ?>" required>
                    </div>
                    <label for="" class="col-sm-3 control-label"></label>
                  </div>

   <div class="form-group">
                    <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">API Url</label>
                    <div class="col-sm-6">
                        <input name="api" type="text" class="form-control" placeholder="API URL" value="<?php echo $api; ?>" required>
                    </div>
                    <label for="" class="col-sm-3 control-label"></label>
                  </div>

   <div class="form-group">
                    <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Status</label>
                    <div class="col-sm-6">
                        <select name="status" class="form-control select2" required>
                           <option value="<?php echo $status; ?>" selected="selected"><?php echo $status; ?></option>
                           <option value="NotActivated">NotActivate</option>
                           <option value="Activated">Activate</option>
                        </select>  
                    </div>
                    <label for="" class="col-sm-3 control-label"></label>
                  </div>

</div>

<div align="right">
     <div class="box-footer">
        <button name="submit" type="submit" class="btn bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?> btn-flat"><i class="fa fa-save">&nbsp;Update Settings</i></button>
     </div>
</div>

 </form>

<?php
}
else{
?>

<form class="form-horizontal" method="post" enctype="multipart/form-data">

<?php
if(isset($_POST['save'])){

  $gateway_name = mysqli_real_escape_string($link, $_POST['gateway_name']);
  $username = mysqli_real_escape_string($link, $_POST['username']);
  $password = mysqli_real_escape_string($link, $_POST['password']);
  $api = mysqli_real_escape_string($link, $_POST['api']);
  $status = mysqli_real_escape_string($link, $_POST['status']);

  $sql = mysqli_query($link, "INSERT INTO sms VALUES(null,'$gateway_name','$username','$password','$api','$status','$institution_id')") or die("Error: " . mysqli_error($link));
   
   echo "<div class='alert bg-blue'>SMS Settings Added successfully!</div>";
	echo '<meta http-equiv="refresh" content="5;url=sms.php?id='.$_SESSION['tid'].'&&mid=NDEx">';
   
}
?>

<div class="box-body">

   <div class="form-group">
                    <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Gateway Name</label>
                    <div class="col-sm-6">
                        <input name="gateway_name" type="text" class="form-control" placeholder="Gateway Name" required>
                    </div>
                    <label for="" class="col-sm-3 control-label"></label>
                  </div>

   <div class="form-group">
                    <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Username</label>
                    <div class="col-sm-6">
                        <input name="username" type="text" class="form-control" placeholder="Username" required>
                    </div>
                    <label for="" class="col-sm-3 control-label"></label>
                  </div>

   <div class="form-group">
                    <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">API Key</label>
                    <div class="col-sm-6">
                        <input name="password" type="text" class="form-control" placeholder="API Key" required>
                    </div>
                    <label for="" class="col-sm-3 control-label"></label>
                  </div>

   <div class="form-group">
                    <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">API Url</label>
                    <div class="col-sm-6">
                        <input name="api" type="text" class="form-control" placeholder="API URL" value="" required>
                    </div>
                    <label for="" class="col-sm-3 control-label"></label>
                  </div>

   <div class="form-group">
                    <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Status</label>
                    <div class="col-sm-6">
                        <select name="status" class="form-control select2" required>
                           <option value="" selected="selected">Select Status</option>
                           <option value="NotActivated">NotActivate</option>
                           <option value="Activated">Activate</option>
                        </select>  
                    </div>
                    <label for="" class="col-sm-3 control-label"></label>
                  </div>

</div>

<div align="right">
     <div class="box-footer">
        <button name="save" type="submit" class="btn bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?> btn-flat"><i class="fa fa-save">&nbsp;Save Settings</i></button>
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