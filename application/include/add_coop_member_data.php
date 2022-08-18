<div class="row"> 
    
   <section class="content">
        
       <div class="box box-success">
           <div class="box-body">
             <div class="table-responsive"> 
            <div class="box-body">

     <div class="col-md-14">
            <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
             <li <?php echo ($_GET['tab'] == 'tab_1') ? "class='active'" : ''; ?>><a href="add_coop_member?id=<?php echo $_SESSION['tid']; ?>&&mid=<?php echo base64_encode("418"); ?>&&tab=tab_1">Add Single Cooperative Members</a></li>
             <li <?php echo ($_GET['tab'] == 'tab_2') ? "class='active'" : ''; ?>><a href="add_coop_member?id=<?php echo $_SESSION['tid']; ?>&&mid=<?php echo base64_encode("418"); ?>&&tab=tab_2">Register Bulk Cooperative Members</a></li>
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
if(isset($_POST['save']))
{
  function random_password($limit)
  {
    return substr(base_convert(sha1(uniqid(mt_rand())), 16, 36), 0, $limit);
  }

  $coopmemberID = mysqli_real_escape_string($link, $_POST['coopmemberID']);
  $coopid =  mysqli_real_escape_string($link, $_POST['coopid']);
  $fname = mysqli_real_escape_string($link, $_POST['fname']);
  $phone_no = mysqli_real_escape_string($link, $_POST['phone_no']);
  $memail =  mysqli_real_escape_string($link, $_POST['email']);
  $password =  random_password(10);
  $bvn =  mysqli_real_escape_string($link, $_POST['unumber']);
  $meeting_freq = mysqli_real_escape_string($link, $_POST['meeting_freq']);
  $mrole = mysqli_real_escape_string($link, $_POST['mrole']);

  if($mrole == "member"){

    $target_dir = "../img/";
    $target_file = $target_dir.basename($_FILES["image"]["name"]);
    $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
    $check = getimagesize($_FILES["image"]["tmp_name"]);
  
    $sourcepath = $_FILES["image"]["tmp_name"];
    $targetpath = "../img/" . $_FILES["image"]["name"];
    move_uploaded_file($sourcepath,$targetpath);

    $location = "img/".$_FILES['image']['name'];
    
    //$today = date("Y-m-d");
    $insert = mysqli_query($link, "INSERT INTO coop_members VALUES(null,'$coopmemberID','$coopid','$location','$fname','$phone_no','$memail','$occupation','','$bvn','','','$password','$meeting_freq','$mrole',NOW())") or die (mysqli_error($link));

    echo $filename=$_FILES["document"]["tmp_name"];

      foreach($_FILES['document']['name'] as $key => $name){

      $newFilename = $name;
      move_uploaded_file($_FILES['document']['tmp_name'][$key], 'img/'.$newFilename);
      $finalfile = '../img/'.$newFilename;

      $insert_record = mysqli_query($link, "INSERT INTO cooperatives_legaldocuments VALUES(null,'$coopmemberID','$finalfile')");
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

  $sms = "$r->abb>>>ACCT. Created | Welcome $fname! Your Member ID is: $coopmemberID. Logon to your email as your login details has been sent their. Thanks.";

  include('../cron/send_coopmember_sms.php');
  include('../cron/send_coopmember_regemail.php');

  if(!$insert)
  {
    echo "<div class='alert bg-blue'>New Member Added Successfully!</div>";
  }
  else{
      echo'<span class="itext" style="color: orange">Error...Please Try Again Later!!</span>';
    }
}
elseif($mrole == "Admin"){

    $guarantors_otp = random_password(8);

    $grelationship = mysqli_real_escape_string($link, $_POST['grelationship']);
    $gname = mysqli_real_escape_string($link, $_POST['gname']);
    $gphone = mysqli_real_escape_string($link, $_POST['gphone']);
    $gaddrs = mysqli_real_escape_string($link, $_POST['gaddrs']);

    $target_dir = "../img/";
    $target_file = $target_dir.basename($_FILES["image"]["name"]);
    $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
    $check = getimagesize($_FILES["image"]["tmp_name"]);
  
    $target_file_gimage = $target_dir.basename($_FILES["gimage"]["name"]);
    $target_file_gpimage = $target_dir.basename($_FILES["gpimage"]["name"]);
    $imageFileType_gimage = pathinfo($target_file_gimage,PATHINFO_EXTENSION);
    $imageFileType_gpimage = pathinfo($target_file_gpimage,PATHINFO_EXTENSION);
    $check_gimage = getimagesize($_FILES["gimage"]["tmp_name"]);
    $check_gpimage = getimagesize($_FILES["gpimage"]["tmp_name"]);

    $sourcepath = $_FILES["image"]["tmp_name"];
    $targetpath = "../img/" . $_FILES["image"]["name"];

    $sourcepath_gimage = $_FILES["gimage"]["tmp_name"];
    $sourcepath_gpimage = $_FILES["gpimage"]["tmp_name"];
    
    $targetpath_gimage = "../img/" . $_FILES["gimage"]["name"];
    $targetpath_gpimage = "../img/" . $_FILES["gpimage"]["name"];

    move_uploaded_file($sourcepath,$targetpath);

    move_uploaded_file($sourcepath_gimage,$targetpath_gimage);

    move_uploaded_file($sourcepath_gpimage,$targetpath_gpimage);

    $location = "img/".$_FILES['image']['name'];

    $loaction_gimage = "img/".$_FILES['gimage']['name'];
    
    $loaction_gpimage = "img/".$_FILES['gpimage']['name'];
    
    //$today = date("Y-m-d");
    $insert = mysqli_query($link, "INSERT INTO coop_members VALUES(null,'$coopmemberID','$coopid','$location','$fname','$phone_no','$memail','$occupation','','$bvn','','','$password','$meeting_freq','$mrole',NOW())") or die (mysqli_error($link));

    $insert = mysqli_query($link, "INSERT INTO coop_admin_guarantors VALUES(null,'$coopid','loaction_gimage','$grelationship','$gname','$gphone','$gaddrs','$loaction_gpimage','$coopmemberID','$mrole','$guarantors_otp')");

    echo $filename=$_FILES["document"]["tmp_name"];

    foreach($_FILES['document']['name'] as $key => $name){
    $newFilename = $name;
    move_uploaded_file($_FILES['document']['tmp_name'][$key], 'img/'.$newFilename);
    $finalfile = '../img/'.$newFilename;

    $insert_record = mysqli_query($link, "INSERT INTO cooperatives_legaldocuments VALUES(null,'$coopid','$finalfile')");
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

    $sms = "$r->abb>>>ACCT. Created | Welcome $fname! Your Member ID is: $coopmemberID. Logon to your email as your login details has been sent their. Thanks.";

    $sms1 = "$r->abb>>>Guarantor Confirmation | Please confirm that you have agreed to stand as a Guarantor for $fname with this OTP: $guarantors_otp. Click Here to Confirm: https://esusu.africa/app/guarantor.php";

    include('../cron/send_coopmember_sms.php');
    include('../cron/send_coopmember_guarantor_sms.php');
    include('../cron/send_coopmember_regemail.php');

    if(!$insert)
    {
      echo "<div class='alert bg-blue'>Cooperative Admin Added Successfully!</div>";
    }
    else{
      echo'<span class="itext" style="color: orange">Error...Please Try Again Later!!</span>';
    }
}
}
?>           
       <form class="form-horizontal" method="post" enctype="multipart/form-data">
             <div class="box-body">

      <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Cooperative</label>
                  <div class="col-sm-10">
        <select name="coopid"  class="form-control select2" required>
                    <option selected='selected'>Select Cooperative&hellip;</option>
                    <?php
$search = mysqli_query($link, "SELECT * FROM cooperatives ORDER BY id");
while($get_search = mysqli_fetch_array($search))
{
?>
          <option value="<?php echo $get_search['coopid']; ?>"><?php echo $get_search['coopname']; ?></option>
<?php } ?>
        </select>
    </div>
    </div>
        
      <div class="form-group">
            <label for="" class="col-sm-2 control-label" style="color:blue;">Member Passport</label>
      <div class="col-sm-10">
               <input type='file' name="image" onChange="readURL(this);" required>
               <img id="blah"  src="../avtar/user2.png" alt="Logo Here" height="100" width="100"/>
      </div>
      </div>
      
<?php
$coopmemberID = 'CPMEM'.rand(1000000,9999999);
?>
      <input name="coopmemberID" type="hidden" class="form-control" value="<?php echo $coopmemberID; ?>">
      
      <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Full Name</label>
                  <div class="col-sm-10">
                  <input name="fname" type="text" class="form-control" placeholder="Full Name" required>
                  </div>
                  </div>
          
    <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Phone Number</label>
                  <div class="col-sm-10">
                  <input name="phone_no" type="text" class="form-control" placeholder="Phone Number" required>
                  </div>
                  </div>

    <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Email</label>
                  <div class="col-sm-10">
                  <input name="email" type="email" class="form-control" placeholder="Email Address" required>
                  </div>
                  </div>

    <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Occupation</label>
                  <div class="col-sm-10">
                  <input name="occupation" type="text" class="form-control" placeholder="Your Occupation" required>
                  </div>
                  </div>

    <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Verify your BVN</label>
                  <div class="col-sm-10">
                 <input name="unumber" type="text" class="form-control" id="unumber" onkeydown="loaddata();" placeholder="Enter your BVN for verification"/required><br>
                 <div id="bvn2"></div><br>
                  </div>
                  </div>

    <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Address</label>
                  <div class="col-sm-10">
                  <input name="addrs" type="text" id="autocomplete3" onFocus="geolocate()" class="form-control" placeholder="Member Address" required>
                  </div>
                  </div>

    <div class="form-group">
            <label for="" class="col-sm-2 control-label" style="color:blue;">Attach Document</label>
      <div class="col-sm-10">
               <input type='file' name="document[]" class="btn bg-orange" multiple required>
               <span style="color: orange">You are to upload the cooperatives members current utility bill & Valid ID Card here for ADDRESS VERIFICATION PURPOSE</span>
      </div>
      </div>

    <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Meeting</label>
                  <div class="col-sm-10">
        <select name="meeting_freq"  class="form-control select2" required>
                    <option selected='selected'>Select Meeting Frequency&hellip;</option>
                    <option value="Weekly">Weekly</option>
                    <option value="Monthly">Monthly</option>
                    <option value="Daily">Daily</option>
        </select>
    </div>
    </div>

    <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Role</label>
                  <div class="col-sm-10">
                    <select name="mrole"  class="form-control select2" id="cmRole" required>
                    <option selected='selected'>Select Role&hellip;</option>
                    <option value="Admin">Admin</option>
                    <option value="member">Member</option>
                  </select>
                  </div>
                  </div>

    <span id='ShowValueFrank'></span>
    <span id='ShowValueFrank'></span>
    <span id='ShowValueFrank'></span>

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
           <label for="" class="col-sm-4 control-label" style="color: blue;">Import Bulk Details Here:</label>
  <div class="col-sm-8">
        <span class="fa fa-cloud-upload"></span>
           <span class="ks-text">Choose file</span>
           <input type="file" name="file" accept=".csv" required>
  </div>
  </div>

   <hr>
  <p style="color:orange"><b style="color:blue;">NOTE:</b><br>
    <span style="color:blue;">(1)</span> <i>Kindly download the <a href="../sample/coop_members.csv"> <b class="btn bg-blue"><i class="fa fa-download"></i> sample file</b></a> to upload the details once.</i></p>
    <span style="color:blue;">(2)</span> <i style="color:orange;">Also, take note of the <span style="color: blue">member_image & valid_id</span> which is to be written in this format: <b style="color:blue;">img/image_name.image_format</b>. The image format can be <b style="color: blue;">.png, .jpg, etc.</b></i></p>
    <span style="color:blue;">(3)</span> <i style="color:orange;">Lastly, please note that the keywords you're expected to use while entering the meeting frequency are: <span style="color: blue">Weekly, Monthly OR Daily</span></i></p>
    <p><span style="color:blue;">(4)</span> <i style="color:orange;">Also, make sure you enter <span style="color: blue">Valid BVN</span> as it will be verify while uploading the csv file. And failure to validate the bvn will result to <span style="color: blue;"> Uploading not successful.</span></i></p>
                        
  <hr>
  
    <div align="right">
       <div class="box-footer">
       <button class="btn bg-blue ks-btn-file" type="submit" name="Import"><span class="fa fa-cloud-upload"></span> Import Details</button> 
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
            $result = array();
            $bvn = $emapData[8];

            $url = 'https://api.paystack.co/bank/resolve_bvn/'.$bvn;

            $curl = curl_init();
              curl_setopt_array($curl, array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "GET",
                CURLOPT_HTTPHEADER => array(
                  'Authorization: Bearer '.$row1->secret_key,
                  'Cache-Control: no-cache',
                ),
              ));

              $response = curl_exec($curl);
              $err = curl_error($curl);

              curl_close($curl);

              if($err){
                echo "cURL Error #:" . $err;
              }
              else{
                if ($response) {
                $result = json_decode($response, true);

              if($result){

                if($result['status'] == true){

                //$memid = 'CPMEM'.rand(1000000,9999999);

                //It wiil insert a row to our borrowers table from our csv file`
                 $sql = "INSERT INTO coop_members(id,memberid,coopid,member_image,valid_id,utility_bill,fullname,phone,email,dob,bvn,acctnumber,bank_code,password,meeting_freq,member_role,reg_date) VALUES(null,'$emapData[0]','$emapData[1]','$emapData[2]','$emapData[3]','$emapData[4]','$emapData[5]','$emapData[6]','$emapData[7]','','$emapData[8]','','','$emapData[9]','$emapData[10]','$emapData[11]',NOW())";
                //we are using mysql_query function. it returns a resource on true else False on error
                $result = mysqli_query($link,$sql);

                include('../cron/send_coopbulkmember_regemail.php');
                
                if(!$result)
                {
                  echo "<script type=\"text/javascript\">
                      alert(\"Invalid File:Please Upload CSV File.\");
                    </script>";
                }
              }
            }
          }
        }
      }
      fclose($file);
      //throws a message if data successfully imported to mysql database from excel file
      echo "<script type=\"text/javascript\">   
      alert(\"Data Uploaded successfully.\");
            </script>";
          }
        }
?>    
           </form>

<hr>
<div class="bg-blue">&nbsp;<b> UPLOAD GUARANTOR SECTION (For Cooperatives Admin Only)</b></div>
<hr>         
           <form class="form-horizontal" method="post" enctype="multipart/form-data">
             
<div class="box-body">
    
  <div class="form-group">
           <label for="" class="col-sm-4 control-label" style="color: blue;">Import Bulk Details Here:</label>
  <div class="col-sm-8">
        <span class="fa fa-cloud-upload"></span>
           <span class="ks-text">Choose file</span>
           <input type="file" name="file" accept=".csv" required>
  </div>
  </div>

   <hr>
  <p style="color:orange"><b style="color:blue;">NOTE:</b><br>
    <span style="color:blue;">(1)</span> <i>Kindly download the <a href="../sample/coop_admin_guarantor.csv"> <b class="btn bg-blue"><i class="fa fa-download"></i> sample file</b></a> to upload the details once.</i></p>
    <span style="color:blue;">(2)</span> <i style="color:orange;">Also, take note of the <span style="color: blue">gpassport & gvalid_id</span> which is to be written in this format: <b style="color:blue;">img/image_name.image_format</b>. The image format can be <b style="color: blue;">.png, .jpg, etc.</b></i></p>                        
  <hr>
  
    <div align="right">
       <div class="box-footer">
       <button class="btn bg-blue ks-btn-file" type="submit" name="Importg"><span class="fa fa-cloud-upload"></span> Import Guarantors</button> 
       </div>
    </div>  

</div>  

<?php
if(isset($_POST["Importg"])){

    function random_password($limit)
    {
      return substr(base_convert(sha1(uniqid(mt_rand())), 16, 36), 0, $limit);
    }

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
          $guarantors_otp = random_password(8);
          //It wiil insert a row to our borrowers table from our csv file`
          $sql = "INSERT INTO coop_admin_guarantors(id,coopid,gpassport,grelationship,gname,gphone,gaddrs,gvalid_id,coop_memberid,role) VALUES(null,'$emapData[0]','$emapData[1]','$emapData[2]','$emapData[3]','$emapData[4]','$emapData[5]','$emapData[6]','$emapData[7]','$emapData[8]','$guarantors_otp')";
          //we are using mysql_query function. it returns a resource on true else False on error
          $result = mysqli_query($link,$sql);
          include("alert_sender/bulk_guarantors_confirmation.php");
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
      alert(\"Data Uploaded successfully.\");
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
           <label for="" class="col-sm-4 control-label" style="color: blue;">Import Bulk Image Here:</label>
  <div class="col-sm-8">
        <span class="fa fa-cloud-upload"></span>
           <span class="ks-text">Choose file</span>
           <input type="file" name="uploaded_file[]" multiple required>
  </div>
  </div>

   <hr>
  <p style="color:orange"><b style="color:blue;">NOTICE:</b><br>
    <span style="color:blue;">(1)</span> <i style="color:orange;">Upload the bulk image of those cooperatives members as instructed with the uses of your <span style="color: blue;">Control Key.</span></i></p>
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