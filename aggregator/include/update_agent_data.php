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
             <li <?php echo ($_GET['tab'] == 'tab_1') ? "class='active'" : ''; ?>><a href="update_agent.php?id=<?php echo $_SESSION['tid']; ?>&&idm=<?php echo $_GET['idm']; ?>&&mid=<?php echo base64_encode("440"); ?>&&tab=tab_1">Update Agent</a></li>
             <!--
             <li <?php //echo ($_GET['tab'] == 'tab_2') ? "class='active'" : ''; ?>><a href="update_agent.php?id=<?php echo $_SESSION['tid']; ?>&&idm=<?php echo $_GET['idm']; ?>&&mid=<?php echo base64_encode("440"); ?>&&tab=tab_2">Update Multiple Agents</a></li>
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
  $fname = mysqli_real_escape_string($link, $_POST['fname']);
  $atype = mysqli_real_escape_string($link, $_POST['atype']);
  $gender = mysqli_real_escape_string($link, $_POST['gender']);
  $bname = mysqli_real_escape_string($link, $_POST['bname']);
  $occupation = mysqli_real_escape_string($link, $_POST['occupation']);
  $addrs = mysqli_real_escape_string($link, $_POST['addrs']);
  $email = mysqli_real_escape_string($link, $_POST['email']);
  $phone = mysqli_real_escape_string($link, $_POST['phone']);
  $status = mysqli_real_escape_string($link, $_POST['status']);
  $username = mysqli_real_escape_string($link, $_POST['username']);
  $password = mysqli_real_escape_string($link, $_POST['password']);
  $encrypted_pass = base64_encode($password);
  $c_level = mysqli_real_escape_string($link, $_POST['c_level']);

  $search_agent = mysqli_query($link, "SELECT * FROM agent_data WHERE id = '$id'");
  $fetch_agent = mysqli_fetch_object($search_agent);
  $myagentid = $fetch_agent->agentid;
  
  
  $search_uagent = mysqli_query($link, "SELECT * FROM user WHERE created_by = '$myagentid'");
  $fetch_uagent = mysqli_fetch_object($search_uagent);
  $myuagentid = $fetch_uagent->userid;
  
  //$search_mycommission = mysqli_query($link, "SELECT * FROM company_commission_level WHERE companyid = '$myagentid'");
  //Compensation Plan
  //$search_mycompensation = mysqli_query($link, "SELECT * FROM compensation_plan WHERE plan_level = '$c_level'");
  //$fetch_mycompensation = mysqli_fetch_object($search_mycompensation);
  //$mypercentage = $fetch_mycompensation->percentage;

  $verify_urlid = mysqli_query($link, "SELECT * FROM member_settings WHERE companyid = '$myagentid'");
  $fetch_urlid = mysqli_fetch_object($verify_urlid);
  $sender_id = $fetch_urlid->sender_id;

  if($status == "Approved")
  {
    $update = mysqli_query($link, "UPDATE agent_data SET fname = '$fname', agenttype = '$atype', gender = '$gender', bname = '$bname', occupation = '$occupation', addrs = '$addrs', email = '$email', phone = '$phone', status = '$status', username = '$username', upassword = '$password' WHERE id = '$id'") or die (mysqli_error($link));
    $update = mysqli_query($link, "UPDATE user SET comment = '$status', username = '$username', password ='$encrypted_pass' WHERE userid = '$myuagentid'");

    /** 
    if(mysqli_num_rows($search_mycommission) == 1){
        $update = mysqli_query($link, "UPDATE company_commission_level SET referral_level = '$c_level', percentage = '$mypercentage' WHERE companyid = '$myagentid'");
    }else{
        $insert = mysqli_query($link, "INSERT INTO company_commission_level VALUES(null,'$myagentid','$c_level','$mypercentage',NOW())");
    }
    */

    $query = mysqli_query($link, "SELECT * FROM systemset") or die (mysqli_error($link));    
    $r = mysqli_fetch_object($query);
    $sysabb = $r->abb;
    $sys_email = $r->email;

    $search_gateway = mysqli_query($link, "SELECT * FROM sms WHERE smsuser = '' AND status = 'Activated'");
    $fetch_gateway = mysqli_fetch_object($search_gateway);
    $gateway_uname = $fetch_gateway->username;
    $gateway_pass = $fetch_gateway->password;
    $gateway_api = $fetch_gateway->api;

    $sms = "$sysabb>>>Dear $fname! Your Account has been activated. Login via: https://esusu.app/?id=$sender_id";

    if($update)
    {
      include('../cron/send_general_sms.php');
      include('../cron/send_agent_approvalemail.php');
      echo "<div class='alert bg-blue'>Agent Updated Successfully! A Notification has been sent to the Agent as regards this update.</div>";
    }
    else{
      echo'<span class="itext" style="color: orange">Error...Please Try Again Later!!</span>';
    } 
  }
  elseif($status == "Disapproved"){
    $update = mysqli_query($link, "UPDATE agent_data SET fname = '$fname', agenttype = '$atype', gender = '$gender', bname = '$bname', occupation = '$occupation', addrs = '$addrs', email = '$email', phone = '$phone', status = '$status', username = '$username', upassword = '$password' WHERE id = '$id'") or die (mysqli_error($link));
    $update = mysqli_query($link, "UPDATE user SET comment = '$status', username = '$username', password = '$encrypted_pass' WHERE userid = '$myuagentid'");
    
    /** 
    if(mysqli_num_rows($search_mycommission) == 1){
        $update = mysqli_query($link, "UPDATE company_commission_level SET referral_level = '$c_level', percentage = '$mypercentage' WHERE companyid = '$myagentid'");
    }else{
        $insert = mysqli_query($link, "INSERT INTO company_commission_level VALUES(null,'$myagentid','$c_level','$mypercentage',NOW())");
    }
    */
    
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
       //include('../cron/send_general_sms.php');
       include('../cron/send_agent_disapprovalemail.php');
      echo "<div class='alert bg-blue'>Agent Updated Successfully! A Notification has been sent to the Agent as regards this update.</div>";
    }
    else{
      echo'<span class="itext" style="color: orange">Error...Please Try Again Later!!</span>';
    }
  }
  else{
    $update = mysqli_query($link, "UPDATE agent_data SET fname = '$fname', agenttype = '$atype', gender = '$gender', bname = '$bname', occupation = '$occupation', addrs = '$addrs', email = '$email', phone = '$phone', username = '$username', upassword = '$password' WHERE id = '$id'") or die (mysqli_error($link));
    $update = mysqli_query($link, "UPDATE user SET username = '$username', password = '$encrypted_pass' WHERE userid = '$myuagentid'");
    
    /**
    if(mysqli_num_rows($search_mycommission) == 1){
        $update = mysqli_query($link, "UPDATE company_commission_level SET referral_level = '$c_level', percentage = '$mypercentage' WHERE companyid = '$myagentid'");
    }else{
        $insert = mysqli_query($link, "INSERT INTO company_commission_level VALUES(null,'$myagentid','$c_level','$mypercentage',NOW())");
    }
    */
    
    if($update)
    {
      echo "<div class='alert bg-blue'>Agent Updated Successfully!</div>";
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
$search_agent = mysqli_query($link, "SELECT * FROM agent_data WHERE id = '$id'");
$fetch_agent = mysqli_fetch_object($search_agent);
?>

       <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Full Name</label>
                  <div class="col-sm-10">
                  <input name="fname" type="text" class="form-control" value="<?php echo $fetch_agent->fname; ?>" placeholder="Full Name" required>
                  </div>
                  </div>
                  
        <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Agent Type</label>
                  <div class="col-sm-10">
                  <input name="atype" type="text" class="form-control" value="<?php echo $fetch_agent->agenttype; ?>" placeholder="Agent Type" required>
                  </div>
                  </div>
          
    <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Gender</label>
                  <div class="col-sm-10">
        <select name="gender"  class="form-control select2" required>
                    <option value="<?php echo $fetch_agent->fname; ?>" selected='selected'><?php echo $fetch_agent->gender; ?></option>
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
        </select>
    </div>
    </div>
          
    <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Business Name</label>
                  <div class="col-sm-10">
                  <input name="bname" type="text" class="form-control" value="<?php echo $fetch_agent->bname; ?>" placeholder="Business Name (Optional)">
                  </div>
                  </div>

    <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Occupation</label>
                  <div class="col-sm-10">
                  <input name="occupation" type="text" class="form-control" value="<?php echo $fetch_agent->occupation; ?>" placeholder="Occupation" required>
                  </div>
                  </div>

    <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Address</label>
                  <div class="col-sm-10">
                  <input name="addrs" type="text" id="autocomplete1" onFocus="geolocate()" class="form-control" value="<?php echo $fetch_agent->addrs; ?>" placeholder="Address" required>
                  </div>
                  </div>

    <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Email Address</label>
                  <div class="col-sm-10">
                  <input name="email" type="email" class="form-control" value="<?php echo $fetch_agent->email; ?>" placeholder="Email Address" required>
                  </div>
                  </div>

    <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Phone No.</label>
                  <div class="col-sm-10">
                  <input name="phone" type="text" class="form-control" value="<?php echo $fetch_agent->phone; ?>" placeholder="Phone Number" required>
                  </div>
                  </div>

    <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">BVN</label>
                  <div class="col-sm-10">
                  <input name="bvn" type="text" class="form-control" value="<?php echo $fetch_agent->bvn; ?>" readonly>
                  </div>
                  </div>
                  
    <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Username</label>
                  <div class="col-sm-10">
                  <input name="username" type="text" class="form-control" value="<?php echo $fetch_agent->username; ?>" placeholder="Username" required>
                  </div>
                  </div>

    <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Password</label>
                  <div class="col-sm-10">
                  <input name="password" type="text" class="form-control" value="<?php echo $fetch_agent->upassword; ?>" placeholder="Password" required>
                  </div>
                  </div>
<?php
$get_id = $fetch_agent->agentid;
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
$get_id = $fetch_agent->agentid;
$i = 0;
$search_file = mysqli_query($link, "SELECT * FROM agent_legaldocuments WHERE agentid = '$get_id'") or die ("Error: " . mysqli_error($link));
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
<hr>

                  <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Status</label>
                  <div class="col-sm-10">
        <select name="status" class="form-control select2" required>
                    <option value="<?php echo $fetch_agent->status; ?>" selected='selected'><?php echo $fetch_agent->status; ?></option>
                    <option value="Approved">Approve</option>
                    <option value="Disapproved">Disapprove</option>
                    <option value="Updated">Update</option>
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
    <span style="color:blue;">(1)</span> <i>Kindly download the <a href="../sample/update_bulkagent.csv"> <b class="btn bg-blue"><i class="fa fa-download"></i> sample file</b></a> to upload the updated details once.</i></p>
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
             $sql = "UPDATE agent_data SET fname = '$emapData[1]', gender = '$emapData[2]', bname = '$emapData[3]', occupation = '$emapData[4]', address = '$emapData[5]', email = '$emapData[6]', phone = '$emapData[7]' WHERE agentid = '$emapData[0]'";
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