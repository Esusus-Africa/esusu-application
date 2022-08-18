<div class="row"> 
    
   <section class="content">
        
       <div class="box box-danger">
           <div class="box-body">
             <div class="table-responsive"> 
            <div class="box-body">
          <a href="listcooperative.php?id=<?php echo $_SESSION['tid']; ?>&&mid=NDE4"> <button type="button" class="btn btn-flat bg-orange"><i class="fa fa-reply-all"></i>&nbsp;Back</button></a>

     <div class="col-md-14">
            <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
             <li <?php echo ($_GET['tab'] == 'tab_1') ? "class='active'" : ''; ?>><a href="coopprofile_settings.php?id=<?php echo $_SESSION['tid']; ?>&&idm=<?php echo $_GET['idm']; ?>&&mid=<?php echo base64_encode("418"); ?>&&tab=tab_1">SMS Settings</a></li>

             <!--<li <?php echo ($_GET['tab'] == 'tab_2') ? "class='active'" : ''; ?>><a href="coopprofile_settings.php?id=<?php echo $_SESSION['tid']; ?>&&idm=<?php echo $_GET['idm']; ?>&&mid=<?php echo base64_encode("418"); ?>&&tab=tab_2">Bill Payment Settings</a></li>-->

             <li <?php echo ($_GET['tab'] == 'tab_3') ? "class='active'" : ''; ?>><a href="coopprofile_settings.php?id=<?php echo $_SESSION['tid']; ?>&&idm=<?php echo $_GET['idm']; ?>&&mid=<?php echo base64_encode("418"); ?>&&tab=tab_3">Other Settings</a></li>
              </ul>
             <div class="tab-content">
<?php
if(isset($_GET['tab']) == true)
{
  $tab = $_GET['tab'];
  if($tab == 'tab_1')
  {
  ?>
             <div class="tab-pane <?php echo ($_GET['tab'] == 'tab_1') ? 'active' : ''; ?>" id="tab_1">

             <div class="box-body">

<?php
$coopid = $_GET['idm'];
$se = mysqli_query($link, "SELECT * FROM sms WHERE smsuser = '$coopid'") or die (mysqli_error($link));
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
if(isset($_POST['Update_sms']))
{
  $coopid = $_GET['idm'];
  $sms_gateway =  mysqli_real_escape_string($link, $_POST['sms_gateway']);
  $username =  mysqli_real_escape_string($link, $_POST['username']);
  $password =  mysqli_real_escape_string($link, $_POST['password']);
  $api =  mysqli_real_escape_string($link, $_POST['api']);
  $status =  mysqli_real_escape_string($link, $_POST['status']);

  $insert = mysqli_query($link, "UPDATE sms SET sms_gateway = '$sms_gateway', username = '$username', password = '$password', api = '$api', status = '$status' WHERE smsuser = '$coopid'") or die ("Error: " . mysqli_error($link));
  if(!$insert)
  {
    echo "<div class='alert alert-info'>Error.....Please try again later</div>";
  }
  else{
    echo "<div class='alert alert-success'>Setup Updated Successfully!</div>";
  }
}
?>

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
                  <input name="api" type="text" class="form-control" placeholder="API URL" value="<?php echo $api; ?>" required>
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
        <button name="Update_sms" type="submit" class="btn bg-blue btn-flat"><i class="fa fa-save">&nbsp;Update Settings</i></button>

     </div>
</div>

 </form>
<?php
}
else{
?>

<form class="form-horizontal" method="post" enctype="multipart/form-data">

<?php
if(isset($_POST['Save_sms']))
{
  $coopid = $_GET['idm'];
  $gateway_name =  mysqli_real_escape_string($link, $_POST['gateway_name']);
  $username =  mysqli_real_escape_string($link, $_POST['username']);
  $password =  mysqli_real_escape_string($link, $_POST['password']);
  $api =  mysqli_real_escape_string($link, $_POST['api']);

  $insert = mysqli_query($link, "INSERT INTO sms VALUES(null,'$gateway_name','$username','$password','$api','Activated','$coopid')") or die ("Error: " . mysqli_error($link));
  if(!$insert)
  {
    echo "<div class='alert alert-info'>Error.....Please try again later</div>";
  }
  else{
    echo "<div class='alert alert-success'>Setup Saved Successfully!</div>";
  }
}
?>

<div class="box-body">

 <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Gateway Name</label>
                  <div class="col-sm-10">
                  <input name="gateway_name" type="text" class="form-control" placeholder="Gateway Name" required>
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
                  <input name="api" type="text" class="form-control" placeholder="API URL" required>
                  </div>
                  </div>

</div>

<div align="right">
     <div class="box-footer">
        <button name="Save_sms" type="submit" class="btn bg-blue btn-flat"><i class="fa fa-save">&nbsp;Save Settings</i></button>

     </div>
</div>

 </form>

<?php
}
?>
       
              </div>
              <!-- /.tab-pane -->
  <?php
  }
  elseif($tab == 'tab_2')
  {
  ?>
              <div class="tab-pane <?php echo ($_GET['tab'] == 'tab_2') ? 'active' : ''; ?>" id="tab_2">

<?php
if(isset($_POST['Update_bill']))
{
  $coopid = $_GET['idm'];
  $token =  mysqli_real_escape_string($link, $_POST['token']);
  $api_url =  mysqli_real_escape_string($link, $_POST['api_url']);
  $email =  mysqli_real_escape_string($link, $_POST['email']);
  $username =  mysqli_real_escape_string($link, $_POST['username']);
  $password =  mysqli_real_escape_string($link, $_POST['password']);
  $status =  mysqli_real_escape_string($link, $_POST['status']);

  $insert = mysqli_query($link, "UPDATE billpayment SET token = '$token', api_url = '$api_url', email = '$email', username = '$username', password = '$password', status = '$status' WHERE companyid = '$coopid'") or die ("Error: " . mysqli_error($link));
  if(!$insert)
  {
    echo "<div class='alert alert-info'>Error.....Please try again later</div>";
  }
  else{
    echo "<div class='alert alert-success'>Setup Updated Successfully!</div>";
  }
}
?>
             <div class="box-body">

<?php
$coopid = $_GET['idm'];
$search_others = mysqli_query($link, "SELECT * FROM billpayment WHERE companyid = '$coopid'");
if(mysqli_num_rows($search_others) == 1){
$rows_other = mysqli_fetch_array($search_others);
?>
             <form class="form-horizontal" method="post" enctype="multipart/form-data">

             <div class="box-body">
            
      <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">API Tokens</label>
                  <div class="col-sm-10">
                  <input name="token" type="text" class="form-control" placeholder="API Tokens" value="<?php echo $rows_other['token']; ?>" required>
                  </div>
                  </div>

      <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">API URL</label>
                  <div class="col-sm-10">
                  <input name="api_url" type="text" class="form-control" placeholder="API URL" value="<?php echo $rows_other['api_url']; ?>" required>
                  </div>
                  </div>

            <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Account Email</label>
                  <div class="col-sm-10">
                  <input name="email" type="email" class="form-control" placeholder="Email Account Connected with your Bill Payment Gateway" value="<?php echo $rows_other['email']; ?>" required>
                  </div>
                  </div>

            <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Username</label>
                  <div class="col-sm-10">
                  <input name="username" type="text" class="form-control" placeholder="Bill Payment Gateway Username" value="<?php echo $rows_other['username']; ?>" required>
                  </div>
                  </div>

            <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Password</label>
                  <div class="col-sm-10">
                  <input name="password" type="text" class="form-control" placeholder="Bill Payment Gateway Password" value="<?php echo $rows_other['password']; ?>" required>
                  </div>
                  </div>

            <div class="form-group">
                <label for="" class="col-sm-2 control-label" style="color:blue;">Status</label>
                <div class="col-sm-10">
                <select name="status"  class="form-control select2" required style="width:100%">
          <option value="<?php echo $rows_other['status']; ?>" selected><?php echo $rows_other['status']; ?></option>
          <option value="Active">Active</option>
          <option value="NotActive">NotActive</option>
        </select>
                  </div>
                  </div>
      
       </div>
       
        <div align="right">
              <div class="box-footer">
                        <button name="Update_bill" type="submit" class="btn bg-blue btn-flat"><i class="fa fa-save">&nbsp;Update</i></button>

              </div>
        </div>
        
       </form> 
<?php
}
else{
?>
      <form class="form-horizontal" method="post" enctype="multipart/form-data">
<?php
if(isset($_POST['Save_bill']))
{
  $coopid = $_GET['idm'];
  $token =  mysqli_real_escape_string($link, $_POST['token']);
  $api_url =  mysqli_real_escape_string($link, $_POST['api_url']);
  $email =  mysqli_real_escape_string($link, $_POST['email']);
  $username =  mysqli_real_escape_string($link, $_POST['username']);
  $password =  mysqli_real_escape_string($link, $_POST['password']);

  $insert = mysqli_query($link, "INSERT INTO billpayment VALUES(null,'$coopid','$token','$api_url','$email','$username','$password','Active')") or die ("Error: " . mysqli_error($link));
  if(!$insert)
  {
    echo "<div class='alert alert-info'>Error.....Please try again later</div>";
  }
  else{
    echo "<div class='alert alert-success'>Setup Saved Successfully!</div>";
  }
}
?>
             <div class="box-body">
            
      <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">API Tokens</label>
                  <div class="col-sm-10">
                  <input name="token" type="text" class="form-control" placeholder="API Tokens" required>
                  </div>
                  </div>

      <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">API URL</label>
                  <div class="col-sm-10">
                  <input name="api_url" type="text" class="form-control" placeholder="API URL" required>
                  </div>
                  </div>

            <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Account Email</label>
                  <div class="col-sm-10">
                  <input name="email" type="email" class="form-control" placeholder="Email Account Connected with your Bill Payment Gateway" required>
                  </div>
                  </div>

            <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Username</label>
                  <div class="col-sm-10">
                  <input name="username" type="text" class="form-control" placeholder="Bill Payment Gateway Username" required>
                  </div>
                  </div>

            <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Password</label>
                  <div class="col-sm-10">
                  <input name="password" type="text" class="form-control" placeholder="Bill Payment Gateway Password" required>
                  </div>
                  </div>
      
       </div>
       
       <div align="right">
              <div class="box-footer">
                        <button name="Save_bill" type="submit" class="btn bg-blue btn-flat"><i class="fa fa-save">&nbsp;Save</i></button>

              </div>
        </div>

       </form>
<?php
}
?>

<?php 
}
elseif($tab == 'tab_3')
{
?>
              <div class="tab-pane <?php echo ($_GET['tab'] == 'tab_3') ? 'active' : ''; ?>" id="tab_3">

            <?php
            if(isset($_POST['update'])){

                $id= mysqli_real_escape_string($link, $_POST['companyid']);
                                  $cname = mysqli_real_escape_string($link, $_POST['cname']);
                                  $senderid = mysqli_real_escape_string($link, $_POST['senderid']);
                                  $currency = mysqli_real_escape_string($link, $_POST['currency']);
                                  $theme_color = mysqli_real_escape_string($link, $_POST['theme_color']);
                                  $alternate_color = mysqli_real_escape_string($link, $_POST['alternate_color']);
                                  $login_background = mysqli_real_escape_string($link, $_POST['login_background']);
                                  $login_bottoncolor = mysqli_real_escape_string($link, $_POST['login_bottoncolor']);
                                  $rave_secret_key = mysqli_real_escape_string($link, $_POST['secret_key']);
                                  $rave_public_key = mysqli_real_escape_string($link, $_POST['public_key']);
                                  $rave_status = mysqli_real_escape_string($link, $_POST['rave_status']);
                                  $tlimit = mysqli_real_escape_string($link, $_POST['tlimit']);
                                    
                                //image
                                $image = addslashes(file_get_contents($_FILES['image']['tmp_name']));
                                $image3 = addslashes(file_get_contents($_FILES['image3']['tmp_name']));

                $target_dir = "../img/";
                $target_dir3 = "../image/";

                $target_file = $target_dir.basename($_FILES["image"]["name"]);
                $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);

                $target_file3 = $target_dir3.basename($_FILES["image3"]["name"]);
                $imageFileType3 = pathinfo($target_file3,PATHINFO_EXTENSION);

                $check = getimagesize($_FILES["image"]["tmp_name"]);
                $check3 = getimagesize($_FILES["image3"]["tmp_name"]);
                
                
                  $sourcepath = $_FILES["image"]["tmp_name"];
                  $targetpath = "../img/" . $_FILES["image"]["name"];
                  move_uploaded_file($sourcepath,$targetpath);

                  $sourcepath3 = $_FILES["image3"]["tmp_name"];
                  $targetpath3 = "../image/" . $_FILES["image3"]["name"];
                  move_uploaded_file($sourcepath3,$targetpath3);
                  
                  $location = "img/".$_FILES['image']['name'];  

                  $lbackg = $_FILES["image3"]["name"];  
                  
                  if($image == "" && $image3 == ""){
                      mysqli_query($link,"UPDATE member_settings SET cname='$cname',sender_id='$senderid',currency='$currency',theme_color='$theme_color',alternate_color='$alternate_color',login_background='$login_background',login_bottoncolor='$login_bottoncolor',tlimit='$tlimit',rave_secret_key='$rave_secret_key',rave_public_key='$rave_public_key',rave_status='$rave_status' WHERE companyid ='$id'")or die(mysqli_error()); 
                      echo "<script>alert('Data Updated Successfully!'); </script>";
                  }elseif($image != "" && $image3 == ""){
                      mysqli_query($link,"UPDATE member_settings SET cname='$cname',logo='$location',sender_id='$senderid',currency='$currency',theme_color='$theme_color',alternate_color='$alternate_color',login_background='$login_background',login_bottoncolor='$login_bottoncolor',tlimit='$tlimit',rave_secret_key='$rave_secret_key',rave_public_key='$rave_public_key',rave_status='$rave_status' WHERE companyid ='$id'")or die(mysqli_error()); 
                      echo "<script>alert('Data Updated Successfully!'); </script>";
                  }elseif($image == "" && $image3 != ""){
                      mysqli_query($link,"UPDATE member_settings SET cname='$cname',frontpg_backgrd='$lbackg',sender_id='$senderid',currency='$currency',theme_color='$theme_color',alternate_color='$alternate_color',login_background='$login_background',login_bottoncolor='$login_bottoncolor',tlimit='$tlimit',rave_secret_key='$rave_secret_key',rave_public_key='$rave_public_key',rave_status='$rave_status' WHERE companyid ='$id'")or die(mysqli_error()); 
                      echo "<script>alert('Data Updated Successfully!'); </script>";
                  }elseif($image != "" && $image3 != ""){
                      mysqli_query($link,"UPDATE member_settings SET cname='$cname',logo='$location',frontpg_backgrd='$lbackg',sender_id='$senderid',currency='$currency',theme_color='$theme_color',alternate_color='$alternate_color',login_background='$login_background',login_bottoncolor='$login_bottoncolor',tlimit='$tlimit',rave_secret_key='$rave_secret_key',rave_public_key='$rave_public_key',rave_status='$rave_status' WHERE companyid ='$id'")or die(mysqli_error()); 
                      echo "<script>alert('Data Updated Successfully!'); </script>";
                  }
                  
                }
              ?>
             <div class="box-body">

<?php
$insid = $_GET['idm'];
$search_others = mysqli_query($link, "SELECT * FROM member_settings WHERE companyid='$insid'");
if(mysqli_num_rows($search_others) == 1){
$row = mysqli_fetch_assoc($search_others);
?>
             <form class="form-horizontal" method="post" enctype="multipart/form-data">

       <?php echo '<div class="bg-orange fade in" >
        <a href = "#" class = "close" data-dismiss= "alert"> &times;</a>
          <strong>Note that&nbsp;</strong> &nbsp;&nbsp;Some Fields are Rquired.
        </div>'?>
             <div class="box-body">
        
      <div class="form-group">
            <label for="" class="col-sm-2 control-label" style="color: blue;">Your Logo</label>
      <div class="col-sm-10">
               <input type='file' name="image" onChange="readURL(this);"/>
               <img id="blah"  src="../<?php echo $row ['logo'];?>" alt="Upload Logo Here" height="100" width="100"/>
      </div>
      </div>
      
      <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Business Name</label>
                  <div class="col-sm-10">
                  <input name="cname" type="text" class="form-control" value="<?php echo $row['cname']; ?>" required/>
                  </div>
                  </div>
          
          <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color: blue;">ID</label>
                  <div class="col-sm-10">
                  <input name="companyid" type="text" class="form-control" value="<?php echo $row['companyid'];?>" readonly="readonly">
                  </div>
                  </div>


      <div class="form-group">
        <label for="" class="col-sm-2 control-label" style="color: blue;">Upload Login Background</label>
        <div class="col-sm-10">
          <input type='file' name="image3"/>
          <img src="../image/<?php echo $row['frontpg_backgrd']; ?>" alt="Background Here" height="100" width="100"/>
        </div>
      </div>    
          
    <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Sender ID</label>
                  <div class="col-sm-10">
                  <input type="text" name="senderid" type="text" class="form-control" value="<?php echo $row['sender_id']; ?>" required/>
                  </div>
                  </div>

    <div class="form-group">
                      <label for="" class="col-sm-2 control-label" style="color:blue;">Currency</label>
                      <div class="col-sm-10">
            <select name="currency"  class="form-control select2" required>
              <option value="<?php echo $row['currency']; ?>"><?php echo $row['currency']; ?></option>
              <option value="NGN">NGN</option>
              <option value="USD">USD</option>
              <option value="EUR">EUR</option>
              <option value="GHS">GHS</option>
              <option value="KES">KES</option>
              <option value="UGX">UGX</option>
              <option value="TZS">TZS</option>
            </select>                 
            </div>
      </div>
      
      <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Transfer Limit (with PIN)</label>
                  <div class="col-sm-10">
                  <input type="number" name="tlimit" type="text" class="form-control" placeholder="Transfer Limit with Staff Pin" value="<?php echo $row['tlimit']; ?>" required>
                  </div>
                  </div>
      
      <div class="form-group">
		                  <label for="" class="col-sm-2 control-label" style="color:blue;">Theme Color</label>
		                  <div class="col-sm-10">
						<select name="theme_color" class="form-control">
							<option value="<?php echo $row['theme_color']; ?>"><?php echo $row['theme_color']; ?></option>
							<option value="black">Black</option>
							<option value="blue">Blue</option>
							<option value="green">Green</option>
							<option value="red">Red</option>
							<option value="yellow">Yellow</option>
							<option value="purple">Purple</option>
						</select>                 
						</div>
		                </div>
		                
	<div class="form-group">
		                  <label for="" class="col-sm-2 control-label" style="color:blue;">Alternate Color</label>
		                  <div class="col-sm-10">
						<select name="alternate_color" class="form-control">
							<option value="<?php echo $row['alternate_color']; ?>"><?php echo $row['alternate_color']; ?></option>
							<option value="black">Black</option>
							<option value="blue">Blue</option>
							<option value="green">Green</option>
							<option value="red">Red</option>
							<option value="yellow">Yellow</option>
							<option value="purple">Purple</option>
						</select>                 
						</div>
		                </div>
		                
		<div class="form-group">
		                  <label for="" class="col-sm-2 control-label" style="color:blue;">Login Background</label>
		                  <div class="col-sm-10">
						<select name="login_background" class="form-control">
							<option value="<?php echo $row['login_background']; ?>"><?php echo $row['login_background']; ?></option>
							<option value="black">Black</option>
							<option value="blue">Blue</option>
							<option value="green">Green</option>
							<option value="red">Red</option>
							<option value="yellow">Yellow</option>
							<option value="purple">Purple</option>
							<option value="">Default</option>
						</select>                 
						</div>
		                </div>
		                
    <div class="form-group">
		                  <label for="" class="col-sm-2 control-label" style="color:blue;">Login Action Color</label>
		                  <div class="col-sm-10">
						<select name="login_bottoncolor" class="form-control">
							<option value="<?php echo $row['login_bottoncolor']; ?>"><?php echo $row['login_bottoncolor']; ?></option>
							<option value="black">Black</option>
							<option value="blue">Blue</option>
							<option value="green">Green</option>
							<option value="red">Red</option>
							<option value="yellow">Yellow</option>
							<option value="purple">Purple</option>
							<option value="">Default</option>
						</select>                 
						</div>
		                </div>
                    
<hr>	
<div class="bg-orange">&nbsp;<b> FLUTTERWAVE PAYMENT GATEWAY SETTINGS (FOR TRANSFER AND TO ACCEPT CARD FOR RECURRING PAYMENT) </b></div>
<hr>
				
				<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Secret Key</label>
                  <div class="col-sm-10">
                  <input name="secret_key" type="text" class="form-control" value="<?php echo $row['rave_secret_key']; ?>">
                  </div>
                </div>
				
				<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Public Key</label>
                  <div class="col-sm-10">
                  <input name="public_key" type="text" class="form-control" value="<?php echo $row['rave_public_key']; ?>" >
                  </div>
                </div>
                
        <div class="form-group">
		                  <label for="" class="col-sm-2 control-label" style="color:blue;">Rave Status</label>
		                  <div class="col-sm-10">
						<select name="rave_status" class="form-control">
							<option value="<?php echo $row['rave_status']; ?>"><?php echo $row['rave_status']; ?></option>
							<option value="Enabled">Enable</option>
							<option value="Disabled">Disable</option>
						</select>                 
						</div>
		   </div>
          
       </div>
       
        <div align="right">
              <div class="box-footer">
                        <button name="update" type="submit" class="btn bg-blue"><i class="fa fa-save">&nbsp;Update</i></button>

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

                  $id= mysqli_real_escape_string($link, $_POST['companyid']);
                                  $cname = mysqli_real_escape_string($link, $_POST['cname']);
                                  $senderid = mysqli_real_escape_string($link, $_POST['senderid']);
                                  $currency = mysqli_real_escape_string($link, $_POST['currency']);
                                  $theme_color = mysqli_real_escape_string($link, $_POST['theme_color']);
                                  $alternate_color = mysqli_real_escape_string($link, $_POST['alternate_color']);
                                  $login_background = mysqli_real_escape_string($link, $_POST['login_background']);
                                  $login_bottoncolor = mysqli_real_escape_string($link, $_POST['login_bottoncolor']);
                                  $rave_secret_key = mysqli_real_escape_string($link, $_POST['secret_key']);
                                  $rave_public_key = mysqli_real_escape_string($link, $_POST['public_key']);
                                  $rave_status = mysqli_real_escape_string($link, $_POST['rave_status']);
                                  $tlimit = mysqli_real_escape_string($link, $_POST['tlimit']);
                                      
                                  //image
                                  $image = addslashes(file_get_contents($_FILES['image']['tmp_name']));
                                  $image3 = addslashes(file_get_contents($_FILES['image3']['tmp_name']));

                  $target_dir = "../img/";
                  $target_dir3 = "../image/";

                  $target_file = $target_dir.basename($_FILES["image"]["name"]);
                  $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);

                  $target_file3 = $target_dir3.basename($_FILES["image3"]["name"]);
                  $imageFileType3 = pathinfo($target_file3,PATHINFO_EXTENSION);

                  $check = getimagesize($_FILES["image"]["tmp_name"]);
                  $check3 = getimagesize($_FILES["image3"]["tmp_name"]);
                  
                    $sourcepath = $_FILES["image"]["tmp_name"];
                    $targetpath = "../img/" . $_FILES["image"]["name"];
                    move_uploaded_file($sourcepath,$targetpath);

                    $sourcepath3 = $_FILES["image3"]["tmp_name"];
                    $targetpath3 = "../image/" . $_FILES["image3"]["name"];
                    move_uploaded_file($sourcepath3,$targetpath3);
                    
                    $location = "img/".$_FILES['image']['name'];  

                    $lbackg = $_FILES["image3"]["name"];      
            
                    mysqli_query($link,"INSERT INTO member_settings VALUES(null,'$id','$cname','$location','$lbackg','$senderid','$currency','$theme_color','$alternate_color','$login_background','$login_bottoncolor','$tlimit','$rave_secret_key','$rave_public_key','$rave_status')")or die(mysqli_error()); 
                    echo "<script>alert('Data Saved Successfully!'); </script>";
                
                }
                ?>

       <?php echo '<div class="bg-orange fade in" >
        <a href = "#" class = "close" data-dismiss= "alert"> &times;</a>
          <strong>Note that&nbsp;</strong> &nbsp;&nbsp;Some Fields are Rquired.
        </div>'?>
             <div class="box-body">
        
      <div class="form-group">
            <label for="" class="col-sm-2 control-label" style="color: blue;">Your Logo</label>
      <div class="col-sm-10">
               <input type='file' name="image" onChange="readURL(this);"/>
               <img id="blah" alt="Upload Logo Here" height="100" width="100"/>
      </div>
      </div>
      
      <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Business Name</label>
                  <div class="col-sm-10">
                  <input name="cname" type="text" class="form-control" placeholder="Business Name Here" required/>
                  </div>
                  </div>
          
          <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color: blue;">ID</label>
                  <div class="col-sm-10">
                  <input name="companyid" type="text" class="form-control" value="<?php echo $insid; ?>" readonly="readonly">
                  </div>
                  </div>


      <div class="form-group">
        <label for="" class="col-sm-2 control-label" style="color: blue;">Upload Login Background</label>
        <div class="col-sm-10">
          <input type='file' name="image3" class="btn bg-orange"/>
        </div>
      </div>    
          
    <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Sender ID</label>
                  <div class="col-sm-10">
                  <input type="text" name="senderid" type="text" class="form-control" placeholder="Your Sender ID" maxlength="11" required/>
                  </div>
                  </div>

    <div class="form-group">
                      <label for="" class="col-sm-2 control-label" style="color:blue;">Currency</label>
                      <div class="col-sm-10">
            <select name="currency"  class="form-control select2" required>
              <option value="NGN">NGN</option>
              <option value="USD">USD</option>
              <option value="EUR">EUR</option>
              <option value="GHS">GHS</option>
              <option value="KES">KES</option>
              <option value="UGX">UGX</option>
              <option value="TZS">TZS</option>
            </select>                 
            </div>
    </div>
    
    <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Transfer Limit (with PIN)</label>
                  <div class="col-sm-10">
                  <input type="number" name="tlimit" type="text" class="form-control" placeholder="Transfer Limit with Staff Pin" required/>
                  </div>
                  </div>
    
    <div class="form-group">
		                  <label for="" class="col-sm-2 control-label" style="color:blue;">Theme Color</label>
		                  <div class="col-sm-10">
						<select name="theme_color" class="form-control">
							<option value="" selected>Default</option>
							<option value="black">Black</option>
							<option value="blue">Blue</option>
							<option value="green">Green</option>
							<option value="red">Red</option>
							<option value="yellow">Yellow</option>
							<option value="purple">Purple</option>
						</select>                 
						</div>
		                </div>
		                
	<div class="form-group">
		                  <label for="" class="col-sm-2 control-label" style="color:blue;">Alternate Color</label>
		                  <div class="col-sm-10">
						<select name="alternate_color" class="form-control">
							<option value="" selected>Default</option>
							<option value="black">Black</option>
							<option value="blue">Blue</option>
							<option value="green">Green</option>
							<option value="red">Red</option>
							<option value="yellow">Yellow</option>
							<option value="purple">Purple</option>
						</select>                 
						</div>
		                </div>
		                
	<div class="form-group">
		                  <label for="" class="col-sm-2 control-label" style="color:blue;">Login Background</label>
		                  <div class="col-sm-10">
						<select name="login_background" class="form-control">
							<option value="" selected>Default</option>
							<option value="black">Black</option>
							<option value="blue">Blue</option>
							<option value="green">Green</option>
							<option value="red">Red</option>
							<option value="yellow">Yellow</option>
							<option value="purple">Purple</option>
						</select>                 
						</div>
		                </div>
		                
    <div class="form-group">
		                  <label for="" class="col-sm-2 control-label" style="color:blue;">Login Action Color</label>
		                  <div class="col-sm-10">
						<select name="login_bottoncolor" class="form-control">
							<option value="" selected>Default</option>
							<option value="black">Black</option>
							<option value="blue">Blue</option>
							<option value="green">Green</option>
							<option value="red">Red</option>
							<option value="yellow">Yellow</option>
							<option value="purple">Purple</option>
						</select>                 
						</div>
		                </div>
                    
<hr>	
<div class="bg-orange">&nbsp;<b> FLUTTERWAVE PAYMENT GATEWAY SETTINGS (FOR TRANSFER AND TO ACCEPT CARD FOR RECURRING PAYMENT) </b></div>
<hr>
				
				<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Secret Key</label>
                  <div class="col-sm-10">
                  <input name="secret_key" type="text" class="form-control">
                  </div>
                </div>
				
				<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Public Key</label>
                  <div class="col-sm-10">
                  <input name="public_key" type="text" class="form-control">
                  </div>
                </div>
                
        <div class="form-group">
		                  <label for="" class="col-sm-2 control-label" style="color:blue;">Rave Status</label>
		                  <div class="col-sm-10">
						<select name="rave_status" class="form-control">
							<option value="" selected>Select Status</option>
							<option value="Enabled">Enable</option>
							<option value="Disabled">Disable</option>
						</select>                 
						</div>
		   </div>
          
       </div>
       
        <div align="right">
              <div class="box-footer">
                        <button name="save" type="submit" class="btn bg-blue"><i class="fa fa-plus">&nbsp;Save</i></button>

              </div>
        </div>
       </form> 
<?php } ?>
              <!-- /.tab-pane -->
            </div>
<?php } } ?>
            <!-- /.tab-content -->
      
          </div>          
      </div>
    
              </div>
  
</div>  
</div>
</div>
</section>  
</div>