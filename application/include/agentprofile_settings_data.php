<div class="row"> 
    
   <section class="content">
        
       <div class="box box-danger">
           <div class="box-body">
             <div class="table-responsive"> 
            <div class="box-body">
          <a href="listagents.php?id=<?php echo $_SESSION['tid']; ?>&&mid=NDQw"> <button type="button" class="btn btn-flat bg-orange"><i class="fa fa-reply-all"></i>&nbsp;Back</button></a>

     <div class="col-md-14">
            <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
             <li <?php echo ($_GET['tab'] == 'tab_1') ? "class='active'" : ''; ?>><a href="agentprofile_settings.php?id=<?php echo $_SESSION['tid']; ?>&&idm=<?php echo $_GET['idm']; ?>&&mid=<?php echo base64_encode("440"); ?>&&tab=tab_1">Maintenance Configuration</a></li>

             <!--<li <?php echo ($_GET['tab'] == 'tab_2') ? "class='active'" : ''; ?>><a href="agentprofile_settings.php?id=<?php echo $_SESSION['tid']; ?>&&idm=<?php echo $_GET['idm']; ?>&&mid=<?php echo base64_encode("440"); ?>&&tab=tab_2">Bill Payment Settings</a></li>-->

              <li <?php echo ($_GET['tab'] == 'tab_3') ? "class='active'" : ''; ?>><a href="agentprofile_settings.php?id=<?php echo $_SESSION['tid']; ?>&&idm=<?php echo $_GET['idm']; ?>&&mid=<?php echo base64_encode("440"); ?>&&tab=tab_3">Other Settings</a></li>
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
$agent = $_GET['idm'];
$se = mysqli_query($link, "SELECT * FROM maintenance_history WHERE company_id = '$agent'") or die (mysqli_error($link));
if(mysqli_num_rows($se) == 1)
{
$sel = mysqli_fetch_array($se);
$cust_mfee = $sel['cust_mfee'];
$lbooking = $sel['loan_booking'];
$tcharges_type = $sel['tcharges_type'];
$t_charges = $sel['t_charges'];
$capped_amt = $sel['capped_amt'];
$status = $sel['status'];
$billing_type = $sel['billing_type'];
?>
<form class="form-horizontal" method="post" enctype="multipart/form-data">

<?php
if(isset($_POST['Update_charges']))
{
  $agent = $_GET['idm'];
  $cust_mfee =  mysqli_real_escape_string($link, $_POST['cust_mfee']);
  $tcharges_type = mysqli_real_escape_string($link, $_POST['tcharges_type']);
  $t_charges =  mysqli_real_escape_string($link, $_POST['t_charges']);
  $capped_amt =  mysqli_real_escape_string($link, $_POST['capped_amt']);
  $status =  mysqli_real_escape_string($link, $_POST['status']);
  $billtype = mysqli_real_escape_string($link, $_POST['billtype']);

  $insert = mysqli_query($link, "UPDATE maintenance_history SET cust_mfee='$cust_mfee', tcharges_type = '$tcharges_type', t_charges = '$t_charges', capped_amt = '$capped_amt', status = '$status', billing_type = '$billtype' WHERE company_id = '$agent'") or die ("Error: " . mysqli_error($link));
  if(!$insert)
  {
    echo "<div class='alert alert-info'>Error.....Please try again later</div>";
  }
  else{
    echo "<div class='alert alert-success'>Setup Updated Successfully!</div>";
    echo "<script>window.location='instprofile_settings.php?id=".$_SESSION['tid']."&&idm=".$_GET['idm']."&&mid=".base64_encode("419")."&&tab=tab_1'; </script>";
  }
}
?>

<div class="box-body">
 
 <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Billing Type</label>
                  <div class="col-sm-10">
                  <select name="billtype" class="form-control select2" required style="width:100%">
           <option value="<?php echo $billing_type; ?>" selected><?php echo $billing_type; ?></option>
           <option value="PAYG">PAYG</option>
           <option value="Hybrid">Hybrid</option>
          </select>
                  </div>
                  </div>
                  
 <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Customer Charges</label>
                  <div class="col-sm-10">
                  <input name="cust_mfee" type="text" class="form-control" placeholder="Maintenance Fee per Customer Registered" value="<?php echo $cust_mfee; ?>" required>
                  </div>
                  </div>
                  
 <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Transaction Charges Type</label>
                  <div class="col-sm-10">
                  <select name="tcharges_type" class="form-control select2" required style="width:100%">
           <option value="<?php echo $tcharges_type; ?>" selected><?php echo $tcharges_type; ?></option>
           <option value="flat">flat</option>
           <option value="percentage">percentage</option>
          </select>
                  </div>
                  </div>

 <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Transactional Charges</label>
                  <div class="col-sm-10">
                  <input name="t_charges" type="text" class="form-control" placeholder="Transactional Charges" value="<?php echo $t_charges; ?>" required>
                  </div>
                  </div>

<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Capped Amount</label>
                  <div class="col-sm-10">
                  <input name="capped_amt" type="text" class="form-control" placeholder="Capped Amount for Transactional Charges" value="<?php echo $capped_amt; ?>" required>
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
        <button name="Update_charges" type="submit" class="btn bg-blue btn-flat"><i class="fa fa-save">&nbsp;Update Settings</i></button>

     </div>
</div>

 </form>
<?php
}
else{
?>

<form class="form-horizontal" method="post" enctype="multipart/form-data">

<?php
if(isset($_POST['Save_charges']))
{
  $agent = $_GET['idm'];
  $cust_mfee =  mysqli_real_escape_string($link, $_POST['cust_mfee']);
  $t_charges =  mysqli_real_escape_string($link, $_POST['t_charges']);
  $capped_amt =  mysqli_real_escape_string($link, $_POST['capped_amt']);
  $tcharges_type = mysqli_real_escape_string($link, $_POST['tcharges_type']);
  $billtype = mysqli_real_escape_string($link, $_POST['billtype']);

  $insert = mysqli_query($link, "INSERT INTO maintenance_history VALUES(null,'$agent','$cust_mfee','','$tcharges_type','$t_charges','$capped_amt','Activated','$billtype')") or die ("Error: " . mysqli_error($link));
  if(!$insert)
  {
    echo "<div class='alert alert-info'>Error.....Please try again later</div>";
  }
  else{
    echo "<div class='alert alert-success'>Setup Saved Successfully!</div>";
    echo "<script>window.location='instprofile_settings.php?id=".$_SESSION['tid']."&&idm=".$_GET['idm']."&&mid=".base64_encode("419")."&&tab=tab_1'; </script>";
  }
}
?>

<div class="box-body">
  
 <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Billing Type</label>
                  <div class="col-sm-10">
                  <select name="billtype" class="form-control select2" required style="width:100%">
           <option value="" selected>Select Billing Type</option>
           <option value="PAYG">PAYG</option>
           <option value="Hybrid">Hybrid</option>
          </select>
                  </div>
                  </div>
                  
 <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Customer Charges</label>
                  <div class="col-sm-10">
                  <input name="cust_mfee" type="text" class="form-control" value="2.5" placeholder="Maintenance Fee per Customer Registered" required>
                  </div>
                  </div>
                  
 <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Transaction Charges Type</label>
                  <div class="col-sm-10">
                  <select name="tcharges_type" class="form-control select2" required style="width:100%">
           <option value="" selected>Select Transaction Charges Type</option>
           <option value="flat">flat</option>
           <option value="percentage">percentage</option>
          </select>
                  </div>
                  </div>

 <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Transactional Charges</label>
                  <div class="col-sm-10">
                  <input name="t_charges" type="text" class="form-control" placeholder="Transactional Charges" required>
                  </div>
                  </div>

<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Capped Amount</label>
                  <div class="col-sm-10">
                  <input name="capped_amt" type="text" class="form-control" placeholder="Capped Amount for Transactional Charges" required>
                  </div>
                  </div>

</div>

<div align="right">
     <div class="box-footer">
        <button name="Save_charges" type="submit" class="btn bg-blue btn-flat"><i class="fa fa-save">&nbsp;Save Settings</i></button>

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
  $agent = $_GET['idm'];
  $token =  mysqli_real_escape_string($link, $_POST['token']);
  $api_url =  mysqli_real_escape_string($link, $_POST['api_url']);
  $email =  mysqli_real_escape_string($link, $_POST['email']);
  $username =  mysqli_real_escape_string($link, $_POST['username']);
  $password =  mysqli_real_escape_string($link, $_POST['password']);
  $status =  mysqli_real_escape_string($link, $_POST['status']);

  $insert = mysqli_query($link, "UPDATE billpayment SET token = '$token', api_url = '$api_url', email = '$email', username = '$username', password = '$password', status = '$status' WHERE companyid = '$agent'") or die ("Error: " . mysqli_error($link));
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
$agent = $_GET['idm'];
$search_others = mysqli_query($link, "SELECT * FROM billpayment WHERE companyid = '$agent'");
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
  $agent = $_GET['idm'];
  $token =  mysqli_real_escape_string($link, $_POST['token']);
  $api_url =  mysqli_real_escape_string($link, $_POST['api_url']);
  $email =  mysqli_real_escape_string($link, $_POST['email']);
  $username =  mysqli_real_escape_string($link, $_POST['username']);
  $password =  mysqli_real_escape_string($link, $_POST['password']);

  $insert = mysqli_query($link, "INSERT INTO billpayment VALUES(null,'$agent','$token','$api_url','$email','$username','$password','Active')") or die ("Error: " . mysqli_error($link));
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
                      mysqli_query($link,"UPDATE member_settings SET cname='$cname',sender_id='$senderid',currency='$currency',theme_color='$theme_color',alternate_color='$alternate_color',login_background='$login_background',login_bottoncolor='$login_bottoncolor',tlimit='$tlimit' WHERE companyid ='$id'")or die(mysqli_error()); 
                      echo "<script>alert('Data Updated Successfully!'); </script>";
                  }elseif($image != "" && $image3 == ""){
                      mysqli_query($link,"UPDATE member_settings SET cname='$cname',logo='$location',sender_id='$senderid',currency='$currency',theme_color='$theme_color',alternate_color='$alternate_color',login_background='$login_background',login_bottoncolor='$login_bottoncolor',tlimit='$tlimit' WHERE companyid ='$id'")or die(mysqli_error()); 
                      echo "<script>alert('Data Updated Successfully!'); </script>";
                  }elseif($image == "" && $image3 != ""){
                      mysqli_query($link,"UPDATE member_settings SET cname='$cname',frontpg_backgrd='$lbackg',sender_id='$senderid',currency='$currency',theme_color='$theme_color',alternate_color='$alternate_color',login_background='$login_background',login_bottoncolor='$login_bottoncolor',tlimit='$tlimit' WHERE companyid ='$id'")or die(mysqli_error()); 
                      echo "<script>alert('Data Updated Successfully!'); </script>";
                  }elseif($image != "" && $image3 != ""){
                      mysqli_query($link,"UPDATE member_settings SET cname='$cname',logo='$location',frontpg_backgrd='$lbackg',sender_id='$senderid',currency='$currency',theme_color='$theme_color',alternate_color='$alternate_color',login_background='$login_background',login_bottoncolor='$login_bottoncolor',tlimit='$tlimit' WHERE companyid ='$id'")or die(mysqli_error()); 
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
                  <input name="cname" type="text" class="form-control" value="<?php echo $row['cname']; ?>" required>
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
              <option value="orange">Orange</option>
							<option value="yellow">Yellow</option>
							<option value="purple">Purple</option>
							<option value="">Default</option>
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
              <option value="orange">Orange</option>
							<option value="yellow">Yellow</option>
							<option value="purple">Purple</option>
							<option value="">Default</option>
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
              <option value="orange">Orange</option>
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
              <option value="orange">Orange</option>
							<option value="yellow">Yellow</option>
							<option value="purple">Purple</option>
							<option value="">Default</option>
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
                  
                    $sourcepath = $_FILES["image"]["tmp_name"];
                    $targetpath = "../img/" . $_FILES["image"]["name"];
                    move_uploaded_file($sourcepath,$targetpath);

                    $sourcepath3 = $_FILES["image3"]["tmp_name"];
                    $targetpath3 = "../image/" . $_FILES["image3"]["name"];
                    move_uploaded_file($sourcepath3,$targetpath3);
                    
                    $location = "img/".$_FILES['image']['name'];  

                    $lbackg = $_FILES["image3"]["name"];      
            
                    mysqli_query($link,"INSERT INTO member_settings VALUES(null,'$id','$cname','$location','$lbackg','$senderid','$currency','$theme_color','$alternate_color','$login_background','$login_bottoncolor','$tlimit','','','','No','No','No','No','','','','')")or die(mysqli_error()); 
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
               <input type='file' name="image" onChange="readURL(this);" />
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
              <option value="orange">Orange</option>
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
              <option value="orange">Orange</option>
							<option value="yellow">Yellow</option>
							<option value="purple">s</option>
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
              <option value="orange">Orange</option>
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
              <option value="orange">Orange</option>
							<option value="yellow">Yellow</option>
							<option value="purple">Purple</option>
						</select>                 
						</div>
		                </div>>
          
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