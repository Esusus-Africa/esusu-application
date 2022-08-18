<div class="row"> 
    
   <section class="content">
        
       <div class="box box-default">
           <div class="box-body">
             <div class="table-responsive"> 
            <div class="box-body">
          <a href="listmerchants.php?id=<?php echo $_SESSION['tid']; ?>&&mid=NDkw"> <button type="button" class="btn btn-flat bg-orange"><i class="fa fa-reply-all"></i>&nbsp;Back</button></a>

     <div class="col-md-14">
            <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
             <li <?php echo ($_GET['tab'] == 'tab_1') ? "class='active'" : ''; ?>><a href="update_merchant.php?id=<?php echo $_SESSION['tid']; ?>&&idm=<?php echo $_GET['idm']; ?>&&mid=<?php echo base64_encode("490"); ?>&&tab=tab_1">Update Merchant Details</a></li>
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
  $merchantID = mysqli_real_escape_string($link, $_POST['merchantID']);
  $company_name = mysqli_real_escape_string($link, $_POST['company_name']);
  $company_sector = mysqli_real_escape_string($link, $_POST['company_sector']);
  $state = mysqli_real_escape_string($link, $_POST['state']);
  $country = mysqli_real_escape_string($link, $_POST['country']);
  $official_email = mysqli_real_escape_string($link, $_POST['official_email']);
  $official_phone = mysqli_real_escape_string($link, $_POST['official_phone']);
  $contact_person = mysqli_real_escape_string($link, $_POST['contact_person']);
  $phone = mysqli_real_escape_string($link, $_POST['phone']);
  $cemail = mysqli_real_escape_string($link, $_POST['cemail']);
  $username = mysqli_real_escape_string($link, $_POST['username']);
  $status = mysqli_real_escape_string($link, $_POST['status']);
  $password = mysqli_real_escape_string($link, $_POST['password']);
  $c_level = mysqli_real_escape_string($link, $_POST['c_level']);
  $encrypt_passi = base64_encode($password);
  
  $search_umer = mysqli_query($link, "SELECT * FROM user WHERE created_by = '$merchantID' AND role = 'merchant_super_admin'");
  $fetch_umer = mysqli_fetch_object($search_umer);
  $myumer = $fetch_umer->userid;
  
  $verify_urlid = mysqli_query($link, "SELECT * FROM member_settings WHERE companyid = '$merchantID'");
  $fetch_urlid = mysqli_fetch_object($verify_urlid);
  $sender_id = $fetch_urlid->sender_id;
  
  $search_mycommission = mysqli_query($link, "SELECT * FROM company_commission_level WHERE companyid = '$merchantID'");
  //Compensation Plan
  $search_mycompensation = mysqli_query($link, "SELECT * FROM compensation_plan WHERE plan_level = '$c_level'");
  $fetch_mycompensation = mysqli_fetch_object($search_mycompensation);
  $mypercentage = $fetch_mycompensation->percentage;
  
  foreach ($_FILES['uploaded_file']['name'] as $key => $name){
        
            $newFilename = $name;
            
            if($newFilename == "")
            {
                echo "";
            }
            else{
                $newlocation = '../img/'.$newFilename;
        		if(move_uploaded_file($_FILES['uploaded_file']['tmp_name'][$key], 'img/'.$newFilename))
        		{
        		    mysqli_query($link, "INSERT INTO merchant_legaldoc VALUES(null,'$merchantID','$newlocation')") or die (mysqli_error($link));
        		}
            }
        	
        }

  if($status == "NotActive")
  {
    $update = mysqli_query($link, "UPDATE user SET email = '$official_email', username = '$username', password = '$encrypt_passi' WHERE userid = '$myumer'");
    $update = mysqli_query($link, "UPDATE merchant_reg SET company_name = '$company_name', msector = '$company_sector', state = '$state', country = '$country', official_email = '$official_email', official_phone = '$official_phone', contact_person = '$contact_person', contact_phone = '$phone', contact_email = '$cemail', username = '$username', password = '$password', status = '$status' WHERE id = '$id'") or die (mysqli_error($link));
    
    if(mysqli_num_rows($search_mycommission) == 1){
        $update = mysqli_query($link, "UPDATE company_commission_level SET referral_level = '$c_level', percentage = '$mypercentage' WHERE companyid = '$merchantID'");
    }else{
        $insert = mysqli_query($link, "INSERT INTO company_commission_level VALUES(null,'$merchantID','$c_level','$mypercentage',NOW())");
    }
      
    $query = mysqli_query($link, "SELECT abb, email FROM systemset") or die (mysqli_error($link));    
    $r = mysqli_fetch_object($query);
    $sys_abb = $r->abb;
    $sys_email = $r->email;

    $search_gateway = mysqli_query($link, "SELECT * FROM sms WHERE smsuser = ''");
    $fetch_gateway = mysqli_fetch_object($search_gateway);
    $gateway_uname = $fetch_gateway->username;
    $gateway_pass = $fetch_gateway->password;
    $gateway_api = $fetch_gateway->api;

    $sms = "$r->abb>>>ACCT. Update | Dear $company_name! Your Account has been Put on hold due to some reasons. Contact any of our supports to resolve the issues. Thanks";

    if($update)
    {
      include('../cron/send_inst_sms.php');
      echo "<div class='alert bg-blue'>Merchant Updated Successfully!</div>";
    }
    else{
      echo'<span class="itext" style="color: orange">Error...Please Try Again Later!!</span>';
    } 
  }
  else{
    $update = mysqli_query($link, "UPDATE user SET email = '$official_email', username = '$username', password = '$encrypt_passi' WHERE userid = '$myumer'");
    $update = mysqli_query($link, "UPDATE merchant_reg SET company_name = '$company_name', msector = '$company_sector', state = '$state', country = '$country', official_email = '$official_email', official_phone = '$official_phone', contact_person = '$contact_person', contact_phone = '$phone', contact_email = '$cemail', username = '$username', password = '$password', status = '$status' WHERE id = '$id'") or die (mysqli_error($link));
    
    if(mysqli_num_rows($search_mycommission) == 1){
        $update = mysqli_query($link, "UPDATE company_commission_level SET referral_level = '$c_level', percentage = '$mypercentage' WHERE companyid = '$merchantID'");
    }else{
        $insert = mysqli_query($link, "INSERT INTO company_commission_level VALUES(null,'$merchantID','$c_level','$mypercentage',NOW())");
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

	$sms = "$r->abb>>>Welcome $company_name! Your Account has been activated. Your Merchant ID: $merchantID, Transaction Pin: 0000. Logon to your email for your username and password.";
    
    if($update)
    {
      include('../cron/send_inst_sms.php');
      include('../cron/send_merchant_regemail.php');
      echo "<div class='alert bg-blue'>Records Update Successfully!. Notification has been sent to the Contact Person as regards this update</div>";
    }
    else{
      echo'<span class="itext" style="color: orange">Error...Please Try Again Later!!</span>';
    }
  }
}
?>           
       <form class="form-horizontal" method="post" enctype="multipart/form-data">

             <div class="box-body">
<?php
$id = $_GET['idm'];
$search_merchant = mysqli_query($link, "SELECT * FROM merchant_reg WHERE id = '$id'");
$fetch_merchant = mysqli_fetch_object($search_merchant);
?>
        <input name="merchantID" type="hidden" class="form-control" value="<?php echo $fetch_merchant->merchantID; ?>">
       <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Company Name</label>
                  <div class="col-sm-10">
                  <input name="company_name" type="text" class="form-control" value="<?php echo $fetch_merchant->company_name; ?>" placeholder="Company Name Name" required>
                  </div>
                  </div>
          
    <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Sector</label>
                  <div class="col-sm-10">
        <select name="company_sector"  class="form-control select2" id="company_sector" required>
                    <option value="<?php echo $fetch_merchant->msector; ?>" selected='selected'><?php echo $fetch_merchant->msector; ?></option>
                    <option value="Insurance">Insurance</option>
                    <option value="Microfinance">Microfinance</option>
                    <option value="Asset Management Firm">Asset Management Firm</option>
                    <option value="Financial Service Provider">Financial Service Provider</option>
                    <option value="Pension">Pension</option>
                    <option value="Cooperative">Cooperative</option>
                    <option value="Other">Other</option>
        </select>
    </div>
    </div>
          
   <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">State</label>
                  <div class="col-sm-10">
                  <input name="state" type="text" id="autocomplete2" value="<?php echo $fetch_merchant->state; ?>" onFocus="geolocate()" class="form-control" placeholder="State" required>
                  </div>
                  </div>
          
    <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Country</label>
                  <div class="col-sm-10">
                  <input name="country" type="text" id="autocomplete3" value="<?php echo $fetch_merchant->country; ?>" onFocus="geolocate()" class="form-control" placeholder="Country" required>
                  </div>
                  </div>

    <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Official Email</label>
                  <div class="col-sm-10">
                  <input name="official_email" type="email" class="form-control" value="<?php echo $fetch_merchant->official_email; ?>" placeholder="Official Email Address" required>
                  </div>
                  </div>

    <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Official Phone No.</label>
                  <div class="col-sm-10">
                  <input name="official_phone" type="text" class="form-control" value="<?php echo $fetch_merchant->official_phone; ?>" placeholder="Official Phone Number" required>
                  </div>
                  </div>
                  
<?php
$get_id = $fetch_merchant->merchantID;
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
<div class="bg-orange">&nbsp;<b>File Attached</b></div>
<hr>

<div class="form-group">
<label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">Upload Documents</label>
    <div class="col-sm-10">
     <input name="uploaded_file[]" type="file" class="btn bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>" multiple/>
     <span style="color: <?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>;">YOU CAN UPLOAD <b>MULTIPLE DOCUMENTS AT THE SAME TIME</b></span>
<hr>

<?php
$get_id = $fetch_merchant->merchantID;
$i = 0;
$search_file = mysqli_query($link, "SELECT * FROM merchant_legaldoc WHERE merchantid = '$get_id'") or die ("Error: " . mysqli_error($link));
if(mysqli_num_rows($search_file) == 0){
  echo "<span style='color: red;'>No file attached!!</span>";
}else{
  while($get_file = mysqli_fetch_array($search_file)){
      $i++;
?>
<a href="<?php echo $get_file['document']; ?>" target="_blank"><img src="../img/file_attached.png" width="64" height="64"> Attachment <?php echo $i; ?></a>
<?php
  }
}
?>
</div>
</div>
<hr>
<div class="bg-orange">&nbsp;<b> CONTACT PERSON </b></div>
<hr>

    <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Contact Person</label>
                  <div class="col-sm-10">
                  <input name="contact_person" type="text" class="form-control" value="<?php echo $fetch_merchant->contact_person; ?>" placeholder="Contact Person Here" required>
                  </div>
                  </div>

    <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Phone Number</label>
                  <div class="col-sm-10">
                  <input name="phone" type="text" class="form-control" value="<?php echo $fetch_merchant->contact_phone; ?>" placeholder="Phone Number" required>
                  </div>
                  </div>

    <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Email Address</label>
                  <div class="col-sm-10">
                  <input name="cemail" type="email" class="form-control" value="<?php echo $fetch_merchant->contact_email; ?>" placeholder="Contact Email Address" required>
                  </div>
                  </div>

    <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Username</label>
                  <div class="col-sm-10">
                  <input name="username" type="text" class="form-control" value="<?php echo $fetch_merchant->username; ?>" placeholder="Username" required>
                  </div>
                  </div>
                  
    <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Password</label>
                  <div class="col-sm-10">
                  <input name="password" type="password" class="form-control" value="<?php echo $fetch_merchant->password; ?>" placeholder="Password" required>
                  </div>
                  </div>

     <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Status</label>
                  <div class="col-sm-10">
        <select name="status" class="form-control select2" required>
                    <option value="<?php echo $fetch_merchant->status; ?>" selected='selected'><?php echo $fetch_merchant->status; ?></option>
                    <option value="Active">Active</option>
                    <option value="NotActive">Not-Active</option>
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