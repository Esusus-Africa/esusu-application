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
             <li <?php echo ($_GET['tab'] == 'tab_1') ? "class='active'" : ''; ?>><a href="update_coopinfo.php?id=<?php echo $_SESSION['tid']; ?>&&idm=<?php echo $_GET['idm']; ?>&&mid=<?php echo base64_encode("418"); ?>&&tab=tab_1">Update Cooperative</a></li>
             <!--
             <li <?php echo ($_GET['tab'] == 'tab_2') ? "class='active'" : ''; ?>><a href="update_coopinfo.php?id=<?php echo $_SESSION['tid']; ?>&&idm=<?php echo $_GET['idm']; ?>&&mid=<?php echo base64_encode("418"); ?>&&tab=tab_2">Update Multiple Cooperatives</a></li>
           -->
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
       
 <?php
if(isset($_POST['update']))
{
  $id = $_GET['idm'];
  $cname = mysqli_real_escape_string($link, $_POST['cname']);
  $coopid = mysqli_real_escape_string($link, $_POST['coopid']);
  $reg_no = mysqli_real_escape_string($link, $_POST['reg_no']);
  $addrs = mysqli_real_escape_string($link, $_POST['addrs']);
  $state = mysqli_real_escape_string($link, $_POST['state']);
  $country = mysqli_real_escape_string($link, $_POST['country']);
  $official_phone = mysqli_real_escape_string($link, $_POST['official_phone']);
  $mobile_phone = mysqli_real_escape_string($link, $_POST['mobile_phone']);
  $status = mysqli_real_escape_string($link, $_POST['status']);
  $frontend_reg = mysqli_real_escape_string($link, $_POST['frontend_reg']);
  $c_level = mysqli_real_escape_string($link, $_POST['c_level']);
  
  $search_mycommission = mysqli_query($link, "SELECT * FROM company_commission_level WHERE companyid = '$coopid'");
  //Compensation Plan
  $search_mycompensation = mysqli_query($link, "SELECT * FROM compensation_plan WHERE plan_level = '$c_level'");
  $fetch_mycompensation = mysqli_fetch_object($search_mycompensation);
  $mypercentage = $fetch_mycompensation->percentage;

  $image = addslashes(file_get_contents($_FILES['image']['tmp_name']));

  $verify_urlid = mysqli_query($link, "SELECT * FROM member_settings WHERE companyid = '$coopid'");
  $fetch_urlid = mysqli_fetch_object($verify_urlid);
  $sender_id = $fetch_urlid->sender_id;

  $search_coopm = mysqli_query($link, "SELECT * FROM coop_members WHERE coopid = '$coopid' AND member_role = 'Admin'");
  $fetch_coopm = mysqli_fetch_object($search_coopm);
  $memail = $fetch_coopm->email;
  $mpassword = $fetch_coopm->password;

  if($status == "Approved")
  {

    if($image == "")
    {
      $update = mysqli_query($link, "UPDATE cooperatives SET coopname = '$cname', reg_no = '$reg_no', address = '$addrs', state = '$state', country = '$country', official_phone = '$official_phone', mobile_phone = '$mobile_phone', status='$status', fontend_reg = '$frontend_reg' WHERE id = '$id'") or die (mysqli_error($link));
      
      if(mysqli_num_rows($search_mycommission) == 1){
        $update = mysqli_query($link, "UPDATE company_commission_level SET referral_level = '$c_level', percentage = '$mypercentage' WHERE companyid = '$coopid'");
      }else{
        $insert = mysqli_query($link, "INSERT INTO company_commission_level VALUES(null,'$coopid','$c_level','$mypercentage',NOW())");
      }
      
      $query = mysqli_query($link, "SELECT * FROM systemset") or die (mysqli_error($link));    
      $r = mysqli_fetch_object($query);
      $sys_abb = $r->abb;
      $sys_email = $r->email;

      $search_gateway = mysqli_query($link, "SELECT * FROM sms WHERE smsuser = ''");
      $fetch_gateway = mysqli_fetch_object($search_gateway);
      $gateway_uname = $fetch_gateway->username;
      $gateway_pass = $fetch_gateway->password;
      $gateway_api = $fetch_gateway->api;

      $sms = "$r->abb>>>Dear $cname! Your Account has been activated. Login via: https://app.esusu.africa/?id=$sender_id";
   
      if($update)
      {
        include('../cron/send_inst_sms.php');
        include('../cron/send_coop_approvalemail.php');
        echo "<div class='alert alert-success'>Update Successfully! A Notification has been sent to the Contact Person as regards this update</div>";
      }
      else{
        echo'<span class="itext" style="color: green;">Error...Please Try Again Later!!</span>';
      }
    }
    else{
      $target_dir = "../img/";
      $target_file = $target_dir.basename($_FILES["image"]["name"]);
      //$target_file_c_sign = $target_dir.basename($_FILES["c_sign"]["name"]);
      $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
      //$imageFileType_c_sign = pathinfo($target_file_c_sign,PATHINFO_EXTENSION);
      $check = getimagesize($_FILES["image"]["tmp_name"]);
      //$check_c_sign = getimagesize($_FILES["c_sign"]["tmp_name"]);
        
      $sourcepath = $_FILES["image"]["tmp_name"];
      //$sourcepath_c_sign = $_FILES["c_sign"]["tmp_name"];
      $targetpath = "../img/" . $_FILES["image"]["name"];
      //$targetpath_c_sign = "../img/" . $_FILES["c_sign"]["name"];
      move_uploaded_file($sourcepath,$targetpath);
      //move_uploaded_file($sourcepath_c_sign,$targetpath_c_sign);

      $location = "img/".$_FILES['image']['name'];
      //$loaction_c_sign = "img/".$_FILES['c_sign']['name'];

      $detect_default_image = mysqli_query($link, "SELECT * FROM cooperatives WHERE id = '$id'");
      $fetch_default_image = mysqli_fetch_object($detect_default_image);
      $default_image = "../".$fetch_default_image->cooplogo;
      unlink($default_image);
      
      $update = mysqli_query($link, "UPDATE cooperatives SET cooplogo = '$location', coopname = '$cname', reg_no = '$reg_no', address = '$addrs', state = '$state', country = '$country', official_phone = '$official_phone', mobile_phone = '$mobile_phone', status='$status', fontend_reg = '$frontend_reg' WHERE id = '$id'") or die (mysqli_error($link));
      
      if(mysqli_num_rows($search_mycommission) == 1){
        $update = mysqli_query($link, "UPDATE company_commission_level SET referral_level = '$c_level', percentage = '$mypercentage' WHERE companyid = '$coopid'");
      }else{
        $insert = mysqli_query($link, "INSERT INTO company_commission_level VALUES(null,'$coopid','$c_level','$mypercentage',NOW())");
      }
      
      $query = mysqli_query($link, "SELECT * FROM systemset") or die (mysqli_error($link));    
      $r = mysqli_fetch_object($query);
      $sys_abb = $r->abb;
      $sys_email = $r->email;

      $search_gateway = mysqli_query($link, "SELECT * FROM sms WHERE smsuser = ''");
      $fetch_gateway = mysqli_fetch_object($search_gateway);
      $gateway_uname = $fetch_gateway->username;
      $gateway_pass = $fetch_gateway->password;
      $gateway_api = $fetch_gateway->api;

      $sms = "$r->abb>>>Dear $cname! Your Account has been activated. Login via: https://app.esusu.africa/?id=$sender_id";

      if($update)
      {
        include('../cron/send_inst_sms.php');
        include('../cron/send_coop_approvalemail.php');
        echo "<div class='alert alert-success'>Update Successfully! A Notification has been sent to the Contact Person as regards this update</div>";
      }
      else{
        echo'<span class="itext" style="color: green;">Error...Please Try Again Later!!</span>';
      }
    }
  }
  elseif($status == "Disapproved")
  {

    if($image == "")
    {
      $update = mysqli_query($link, "UPDATE cooperatives SET coopname = '$cname', reg_no = '$reg_no', address = '$addrs', state = '$state', country = '$country', official_phone = '$official_phone', mobile_phone = '$mobile_phone', status='$status', fontend_reg = '$frontend_reg' WHERE id = '$id'") or die (mysqli_error($link));
      
      if(mysqli_num_rows($search_mycommission) == 1){
        $update = mysqli_query($link, "UPDATE company_commission_level SET referral_level = '$c_level', percentage = '$mypercentage' WHERE companyid = '$coopid'");
      }else{
        $insert = mysqli_query($link, "INSERT INTO company_commission_level VALUES(null,'$coopid','$c_level','$mypercentage',NOW())");
      }
      
      $query = mysqli_query($link, "SELECT * FROM systemset") or die (mysqli_error($link));    
      $r = mysqli_fetch_object($query);
      $sys_abb = $r->abb;
      $sys_email = $r->email;
/**
      $search_gateway = mysqli_query($link, "SELECT * FROM sms WHERE smsuser = ''");
      $fetch_gateway = mysqli_fetch_object($search_gateway);
      $gateway_uname = $fetch_gateway->username;
      $gateway_pass = $fetch_gateway->password;
      $gateway_api = $fetch_gateway->api;

      $sms = "$r->abb>>>ACCT. Notification | Sorry! Your Account was Disapproved by the Admin. Please Contact our Support for more details. Thanks.";
**/    
      if($update)
      {
        //include('../cron/send_inst_sms.php');
        include('../cron/send_coop_disapprovalemail.php');
        echo "<div class='alert alert-success'>Update Successfully! A Notification has been sent to the Contact Person as regards this update</div>";
      }
      else{
        echo'<span class="itext" style="color: green;">Error...Please Try Again Later!!</span>';
      }
    }
    else{
      $target_dir = "../img/";
      $target_file = $target_dir.basename($_FILES["image"]["name"]);
      //$target_file_c_sign = $target_dir.basename($_FILES["c_sign"]["name"]);
      $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
      //$imageFileType_c_sign = pathinfo($target_file_c_sign,PATHINFO_EXTENSION);
      $check = getimagesize($_FILES["image"]["tmp_name"]);
      //$check_c_sign = getimagesize($_FILES["c_sign"]["tmp_name"]);
        
      $sourcepath = $_FILES["image"]["tmp_name"];
      //$sourcepath_c_sign = $_FILES["c_sign"]["tmp_name"];
      $targetpath = "../img/" . $_FILES["image"]["name"];
      //$targetpath_c_sign = "../img/" . $_FILES["c_sign"]["name"];
      move_uploaded_file($sourcepath,$targetpath);
      //move_uploaded_file($sourcepath_c_sign,$targetpath_c_sign);

      $location = "img/".$_FILES['image']['name'];
      //$loaction_c_sign = "img/".$_FILES['c_sign']['name'];

      $detect_default_image = mysqli_query($link, "SELECT * FROM cooperatives WHERE id = '$id'");
      $fetch_default_image = mysqli_fetch_object($detect_default_image);
      $default_image = "../".$fetch_default_image->cooplogo;
      unlink($default_image);
      
      $update = mysqli_query($link, "UPDATE cooperatives SET cooplogo = '$location', coopname = '$cname', reg_no = '$reg_no', address = '$addrs', state = '$state', country = '$country', official_phone = '$official_phone', mobile_phone = '$mobile_phone', status='$status', fontend_reg = '$frontend_reg' WHERE id = '$id'") or die (mysqli_error($link));
      
      if(mysqli_num_rows($search_mycommission) == 1){
        $update = mysqli_query($link, "UPDATE company_commission_level SET referral_level = '$c_level', percentage = '$mypercentage' WHERE companyid = '$coopid'");
      }else{
        $insert = mysqli_query($link, "INSERT INTO company_commission_level VALUES(null,'$coopid','$c_level','$mypercentage',NOW())");
      }
      
      $query = mysqli_query($link, "SELECT * FROM systemset") or die (mysqli_error($link));    
      $r = mysqli_fetch_object($query);
      $sys_abb = $r->abb;
      $sys_email = $r->email;
/**
      $search_gateway = mysqli_query($link, "SELECT * FROM sms WHERE smsuser = ''");
      $fetch_gateway = mysqli_fetch_object($search_gateway);
      $gateway_uname = $fetch_gateway->username;
      $gateway_pass = $fetch_gateway->password;
      $gateway_api = $fetch_gateway->api;

     $sms = "$r->abb>>>ACCT. Notification | Sorry! Your Account was Disapproved by the Admin. Please Contact our Support for more details. Thanks.";
**/    
      if($update)
      {
        //include('../cron/send_inst_sms.php');
        include('../cron/send_coop_disapprovalemail.php');
        echo "<div class='alert alert-success'>Update Successfully! A Notification has been sent to the Contact Person as regards this update</div>";
      }
      else{
        echo'<span class="itext" style="color: green;">Error...Please Try Again Later!!</span>';
      }
    }
  }
  else{

    if($image == "")
    {
      $update = mysqli_query($link, "UPDATE cooperatives SET coopname = '$cname', reg_no = '$reg_no', address = '$addrs', state = '$state', country = '$country', official_phone = '$official_phone', mobile_phone = '$mobile_phone', fontend_reg = '$frontend_reg' WHERE id = '$id'") or die (mysqli_error($link));
      
      if(mysqli_num_rows($search_mycommission) == 1){
        $update = mysqli_query($link, "UPDATE company_commission_level SET referral_level = '$c_level', percentage = '$mypercentage' WHERE companyid = '$coopid'");
      }else{
        $insert = mysqli_query($link, "INSERT INTO company_commission_level VALUES(null,'$coopid','$c_level','$mypercentage',NOW())");
      }
      
      if($update)
      {
        echo "<div class='alert alert-success'>Update Successfully!</div>";
      }
      else{
        echo'<span class="itext" style="color: green;">Error...Please Try Again Later!!</span>';
      }
    }
    else{
      $target_dir = "../img/";
      $target_file = $target_dir.basename($_FILES["image"]["name"]);
      //$target_file_c_sign = $target_dir.basename($_FILES["c_sign"]["name"]);
      $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
      //$imageFileType_c_sign = pathinfo($target_file_c_sign,PATHINFO_EXTENSION);
      $check = getimagesize($_FILES["image"]["tmp_name"]);
      //$check_c_sign = getimagesize($_FILES["c_sign"]["tmp_name"]);
        
      $sourcepath = $_FILES["image"]["tmp_name"];
      //$sourcepath_c_sign = $_FILES["c_sign"]["tmp_name"];
      $targetpath = "../img/" . $_FILES["image"]["name"];
      //$targetpath_c_sign = "../img/" . $_FILES["c_sign"]["name"];
      move_uploaded_file($sourcepath,$targetpath);
      //move_uploaded_file($sourcepath_c_sign,$targetpath_c_sign);

      $location = "img/".$_FILES['image']['name'];
      //$loaction_c_sign = "img/".$_FILES['c_sign']['name'];

      $detect_default_image = mysqli_query($link, "SELECT * FROM cooperatives WHERE id = '$id'");
      $fetch_default_image = mysqli_fetch_object($detect_default_image);
      $default_image = "../".$fetch_default_image->cooplogo;
      unlink($default_image);
      
      $update = mysqli_query($link, "UPDATE cooperatives SET cooplogo = '$location', coopname = '$cname', reg_no = '$reg_no', address = '$addrs', state = '$state', country = '$country', official_phone = '$official_phone', mobile_phone = '$mobile_phone', fontend_reg = '$frontend_reg' WHERE id = '$id'") or die (mysqli_error($link));
      
      if(mysqli_num_rows($search_mycommission) == 1){
        $update = mysqli_query($link, "UPDATE company_commission_level SET referral_level = '$c_level', percentage = '$mypercentage' WHERE companyid = '$coopid'");
      }else{
        $insert = mysqli_query($link, "INSERT INTO company_commission_level VALUES(null,'$coopid','$c_level','$mypercentage',NOW())");
      }
      
      if($update)
      {
        echo "<div class='alert alert-success'>Update Successfully!</div>";
      }
      else{
        echo'<span class="itext" style="color: green;">Error...Please Try Again Later!!</span>';
      }
    }

  }
  //end
}
?>           
       <form class="form-horizontal" method="post" enctype="multipart/form-data">

             <div class="box-body">
<?php
$id = $_GET['idm'];
$search_coop = mysqli_query($link, "SELECT * FROM cooperatives WHERE id = '$id'");
$fetch_coop = mysqli_fetch_object($search_coop);
?>

        <input name="coopid" type="hidden" class="form-control" value="<?php echo $fetch_coop->coopid; ?>" required>

       <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Cooperative</label>
                  <div class="col-sm-10">
                    <span style="color: orange; font-size: 20px;"><b><?php echo $fetch_coop->coopname.' <span style="color: blue;">['.$fetch_coop->coopid.']</span>'; ?></b></span>
                  </div>
                  </div>
        
      <div class="form-group">
            <label for="" class="col-sm-2 control-label" style="color:blue;">Logo</label>
      <div class="col-sm-10">
               <input type='file' name="image" onChange="readURL(this);">
               <img id="blah"  src="../<?php echo $fetch_coop->cooplogo; ?>" alt="Logo Here" height="100" width="100"/>
      </div>
      </div>
      
      <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Name</label>
                  <div class="col-sm-10">
                  <input name="cname" type="text" class="form-control" value="<?php echo $fetch_coop->coopname; ?>" required>
                  </div>
                  </div>

     <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Reg No.</label>
                  <div class="col-sm-10">
                  <input name="reg_no" type="text" class="form-control" value="<?php echo $fetch_coop->reg_no; ?>">
                  </div>
                  </div>

     <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Location</label>
                  <div class="col-sm-10">
                  <input name="addrs" type="text" id="autocomplete1" class="form-control" onFocus="geolocate()" value="<?php echo $fetch_coop->address; ?>" required>
                  </div>
                  </div>
          
    <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">State</label>
                  <div class="col-sm-10">
                  <input name="state" type="text" id="autocomplete2" onFocus="geolocate()" class="form-control" value="<?php echo $fetch_coop->state; ?>" required>
                  </div>
                  </div>
          
    <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Country</label>
                  <div class="col-sm-10">
                  <input name="country" type="text" id="autocomplete3" onFocus="geolocate()" class="form-control" value="<?php echo $fetch_coop->country; ?>" required>
                  </div>
                  </div>
          
    <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Official Contact</label>
                  <div class="col-sm-10">
                  <input name="official_phone" type="text" class="form-control" value="<?php echo $fetch_coop->official_phone; ?>" required>
                  </div>
                  </div>

    <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Mobile Number</label>
                  <div class="col-sm-10">
                  <input name="mobile_phone" type="text" class="form-control" value="<?php echo $fetch_coop->mobile_phone; ?>">
                  </div>
                  </div>
                  
<?php
$get_id = $fetch_coop->coopid;
$search_commission = mysqli_query($link, "SELECT * FROM company_commission_level WHERE companyid = '$get_id'");
if(mysqli_num_rows($search_commission) == 1)
{
    $fetch_cm = mysqli_fetch_object($search_commission);
?>
    <div class="form-group">
        <label for="" class="col-sm-2 control-label" style="color:blue;">Commission Level</label>
        <div class="col-sm-10">
        <select name="c_level" class="form-control select2" required>
                    <option value="<?php echo $fetch_cm->referral_level; ?>" selected='selected'>Level <?php echo $fetch_cm->referral_level.' Percentage: '.$fetch_cm->percentage.'%'; ?></option>
                    <?php
                    $search_compensation = mysqli_query($link, "SELECT * FROM compensation_plan");
                    while($fetch_cmpe = mysqli_fetch_object($search_compensation))
                    {
                    ?>
                        <option value="<?php echo $fetch_cmpe->plan_level; ?>">Level <?php echo $fetch_cmpe->plan_level.' Percentage: '.$fetch_cmpe->percentage.'%'; ?></option>
                    <?php
                    }
                    ?>
        </select>
        </div>
    </div>
<?php
}
else{
    $search_compensation = mysqli_query($link, "SELECT * FROM compensation_plan");
?>
    <div class="form-group">
        <label for="" class="col-sm-2 control-label" style="color:blue;">Commission Level</label>
        <div class="col-sm-10">
        <select name="c_level" class="form-control select2" required>
            <option value="" selected>Set Referral Commission Level</option>
            <?php
            while($fetch_cmpe = mysqli_fetch_object($search_compensation))
            {
            ?>
                <option value="<?php echo $fetch_cmpe->plan_level; ?>">Level <?php echo $fetch_cmpe->plan_level.' Percentage: '.$fetch_cmpe->percentage.'%'; ?></option>
            <?php
            }
            ?>
        </select>
        </div>
    </div>
<?php
}
?>

   <hr>
 <div class="alert bg-orange">File Attached</div>
<hr>

<?php
$get_id = $fetch_coop->coopid;
$i = 0;
$search_file = mysqli_query($link, "SELECT * FROM cooperatives_legaldocuments WHERE coopid = '$get_id'") or die ("Error: " . mysqli_error($link));
if(mysqli_num_rows($search_file) == 0){
  echo "<span style='color: orange;'>No file attached!!</span>";
}else{
  while($get_file = mysqli_fetch_array($search_file)){
      $i++;
?>
<a href="<?php echo $get_file['document']; ?>" target="_blank"><img src="../img/file_attached.png" width="64" height="64"> Attachment <?php echo $i; ?></a>
<?php
  }
}
?>
<hr>

                  <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Status</label>
                  <div class="col-sm-10">
        <select name="status" class="form-control select2" required>
                    <option value="<?php echo $fetch_coop->status; ?>" selected='selected'><?php echo $fetch_coop->status; ?></option>
                    <option value="Approved">Approve</option>
                    <option value="Disapproved">Disapprove</option>
                    <option value="Updated">Update</option>
        </select>
    </div>
    </div>

    <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Frontend Reg.</label>
                  <div class="col-sm-10">
        <select name="frontend_reg" class="form-control select2" required>
                    <option value="<?php echo $fetch_coop->fontend_reg; ?>" selected='selected'><?php echo $fetch_coop->fontend_reg; ?></option>
                    <option value="Enable">Enable</option>
                    <option value="Disable">Disable</option>
        </select>
    </div>
    </div>

       </div>
       
        <div align="right">
              <div class="box-footer">
                        <button name="update" type="submit" class="btn bg-blue btn-flat"><i class="fa fa-edit">&nbsp;Update</i></button>

              </div>
        </div>
        
       </form> 
       
              </div>
              <!-- /.tab-pane -->
  <?php
  }
  elseif($tab == 'tab_2')
  {
  ?>
              <div class="tab-pane <?php echo ($_GET['tab'] == 'tab_2') ? 'active' : ''; ?>" id="tab_2">
<hr>
<div class="bg-blue">&nbsp;<b> CSV FILE SECTION </b></div>
<hr>         
           <form class="form-horizontal" method="post" enctype="multipart/form-data">
             
<div class="box-body">
    
  <div class="form-group">
           <label for="" class="col-sm-4 control-label" style="color: blue;">Import Bulk Update:</label>
  <div class="col-sm-8">
        <span class="fa fa-cloud-upload"></span>
           <span class="ks-text">Choose file</span>
           <input type="file" name="file" accept=".csv" required>
  </div>
  </div>

   <hr>
  <p style="color:red"><b style="color:blue;">NOTE:</b><br>
    <span style="color:blue;">(1)</span> <i>Kindly download the <a href="../sample/update_cooperatives.csv"> <b class="btn bg-blue"><i class="fa fa-download"></i> sample file</b></a> to upload the updated details once.</i></p>
    <span style="color:blue;">(2)</span> <i style="color:red;">Also, take note of the <span style="color: blue">cooplogo</span> which is to be written in this format: <b style="color:blue;">img/image_name.image_format</b>. The image format can be <b style="color: blue;">.png, .jpg, etc.</b></i></p>
                        
  <hr>
  
    <div align="right">
       <div class="box-footer">
       <button class="btn bg-blue ks-btn-file" type="submit" name="Import"><span class="fa fa-cloud-upload"></span> Import Data</button> 
       </div>
    </div>  

</div>  

<?php
if(isset($_POST["Import"])){

    echo $filename=$_FILES["file"]["tmp_name"];
    
    $allowed_filetypes = array('csv');
    if(!in_array(end(explode(".", $_FILES['file']['name'])), $allowed_filetypes))
        {
        echo "<script type=\"text/javascript\">
            alert(\"The file you attempted to upload is not allowed.\");
          </script>";
        }    
    elseif($_FILES["file"]["size"] > 0)
     {
        $file = fopen($filename, "r");
           while (($emapData = fgetcsv($file, 10000, ",")) !== FALSE)
           {
            //It wiil UPDATE row on our INST. table from the csv file`
             $sql = "UPDATE cooperatives SET coopname = '$emapData[1]', cooplogo = '$emapData[2]', address = '$emapData[3]', state = '$emapData[4]', country = '$emapData[5]', official_phone = '$emapData[6]', mobile_phone = '$emapData[7]', reg_no = '$emapData[8]' WHERE coopid = '$emapData[0]'";
           //we are using mysql_query function. it returns a resource on true else False on error
            $result = mysqli_query($link,$sql);
        if(!$result)
        {
          echo "<script type=\"text/javascript\">
              alert(\"Invalid File:Please Upload CSV File.\");
            </script>";
        }
 
           }
           fclose($file);
           //throws a message if data successfully imported to mysql database from excel file
           echo "<script type=\"text/javascript\">
            alert(\"Data Updated successfully.\");
          </script>";
     }
  }  
?>    
           </form>
<hr>
<div class="bg-blue">&nbsp;<b> IMAGE SECTION </b></div>
<hr>
           <form class="form-horizontal" method="post" enctype="multipart/form-data">
             
<div class="box-body">
    
  <div class="form-group">
           <label for="" class="col-sm-4 control-label" style="color: blue;">Import Bulk 
           logo Here if needed:</label>
  <div class="col-sm-8">
        <span class="fa fa-cloud-upload"></span>
           <span class="ks-text">Choose file</span>
           <input type="file" name="uploaded_file[]" multiple required>
  </div>
  </div>

   <hr>
  <p style="color:red"><b style="color:blue;">NOTICE:</b><br>
    <span style="color:blue;">(1)</span> <i style="color:red;">Upload the bulk image of just updated Institution if needed, with the uses of your <span style="color: blue;">Control Key.</span></i></p>
  <hr>
  
    <div align="right">
       <div class="box-footer">
       <button class="btn bg-blue ks-btn-file" type="submit" name="Import_Image"><span class="fa fa-cloud-upload"></span> Import Image</button> 
       </div>
    </div>  

</div>  

<div class="scrollable">
<?php
if(isset($_POST["Import_Image"])){

    echo $filename=$_FILES["file"]["tmp_name"];
    foreach ($_FILES['uploaded_file']['name'] as $key => $name){
 
    $newFilename = $name;
    if(move_uploaded_file($_FILES['uploaded_file']['tmp_name'][$key], '../img/'.$newFilename))
    {
      echo "<p><span style='color: blue'><i>[".$newFilename."]</i></span> <span style='color: red;'>uploaded successfully...</span></p>";
    }
    else{
      echo "<script type=\"text/javascript\">
              alert(\"Error....Please try again later\");
            </script>";
    }
    }  
   
  }  
?> 
</div>   
           </form>
      </div>
  <?php
  }
}
?>
              <!-- /.tab-pane -->
            </div>
            <!-- /.tab-content -->
      
          </div>          
      </div>
    
              </div>
  
</div>  
</div>
</div>
</section>  
</div>