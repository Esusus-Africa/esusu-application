<div class="row"> 
    
   <section class="content">
        
       <div class="box box-success">
           <div class="box-body">
             <div class="table-responsive"> 
            <div class="box-body">
          <a href="view_coopmembers?cid=<?php echo $_GET['cid']; ?>&&mid=NDE4"> <button type="button" class="btn btn-flat bg-orange"><i class="fa fa-reply-all"></i>&nbsp;Back</button></a>

     <div class="col-md-14">
            <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
             <li <?php echo ($_GET['tab'] == 'tab_1') ? "class='active'" : ''; ?>><a href="edit_coopmembers?id=<?php echo $_SESSION['tid']; ?>&&idm=<?php echo $_GET['idm']; ?>&&cid=<?php echo $_GET['cid']; ?>&&mid=<?php echo base64_encode("418"); ?>&&tab=tab_1">Update Single Cooperative Member</a></li>
             <li <?php echo ($_GET['tab'] == 'tab_2') ? "class='active'" : ''; ?>><a href="edit_coopmembers?id=<?php echo $_SESSION['tid']; ?>&&idm=<?php echo $_GET['idm']; ?>&&cid=<?php echo $_GET['cid']; ?>&&mid=<?php echo base64_encode("418"); ?>&&tab=tab_2">Update Bulk Cooperative Members</a></li>
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
  $memberID = mysqli_real_escape_string($link, $_POST['memberID']);
  $fname = mysqli_real_escape_string($link, $_POST['fname']);
  $phone_no = mysqli_real_escape_string($link, $_POST['phone_no']);
  $meeting_freq = mysqli_real_escape_string($link, $_POST['meeting_freq']);
  $mrole = mysqli_real_escape_string($link, $_POST['mrole']);
  $password = mysqli_real_escape_string($link, $_POST['password']);

  $image = addslashes(file_get_contents($_FILES['image']['tmp_name']));

  if($image == "")
  {
     $update = mysqli_query($link, "UPDATE coop_members SET fullname = '$fname', phone = '$phone_no', meeting_freq = '$meeting_freq', member_role = '$mrole', password = '$password' WHERE memberid = '$memberID'") or die (mysqli_error($link));
  
    if($update)
    {
      echo "<div class='alert alert-success'>Update Successfully!</div>";
    }
    else{
      echo'<span class="itext" style="color: #FF0000">Error...Please Try Again Later!!</span>';
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

    $detect_default_image = mysqli_query($link, "SELECT * FROM coop_members WHERE memberid = '$memberID'");
    $fetch_default_image = mysqli_fetch_object($detect_default_image);
    $default_image = "../".$fetch_default_image->member_image;
    unlink($default_image);
    
    $update = mysqli_query($link, "UPDATE coop_members SET member_image = '$location', fullname = '$fname', phone = '$phone_no', meeting_freq = '$meeting_freq', member_role = '$mrole', password = '$password' WHERE memberid = '$memberID'") or die (mysqli_error($link));
  
    if($update)
    {
      echo "<div class='alert alert-success'>Update Successfully!</div>";
    }
    else{
      echo'<span class="itext" style="color: #FF0000">Error...Please Try Again Later!!</span>';
    }
  }
  
}
?>           
       <form class="form-horizontal" method="post" enctype="multipart/form-data">

             <div class="box-body">
<?php
$memid = $_GET['idm'];
$search_coopmembers = mysqli_query($link, "SELECT * FROM coop_members WHERE id = '$memid'");
$fetch_coopmembers = mysqli_fetch_object($search_coopmembers);
?>

<input name="memberID" type="hidden" class="form-control" value="<?php echo $fetch_coopmembers->memberid; ?>">

<?php
$coopid = $_GET['cid'];
$search_coop = mysqli_query($link, "SELECT * FROM cooperatives WHERE coopid = '$coopid'");
$fetch_coop = mysqli_fetch_object($search_coop);
?>

       <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Cooperative</label>
                  <div class="col-sm-10">
                    <span style="color: orange; font-size: 20px;"><b><?php echo $fetch_coop->coopname.' <span style="color: blue;">['.$fetch_coop->coopid.']</span>'; ?></b></span>
                  </div>
                  </div>
        
      <div class="form-group">
            <label for="" class="col-sm-2 control-label" style="color:blue;">Member Passport</label>
      <div class="col-sm-10">
               <input type='file' name="image" onChange="readURL(this);">
               <img id="blah"  src="../<?php echo $fetch_coopmembers->member_image; ?>" alt="Logo Here" height="100" width="100"/>
      </div>
      </div>
      
      <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Full Name</label>
                  <div class="col-sm-10">
                  <input name="fname" type="text" class="form-control" value="<?php echo $fetch_coopmembers->fullname; ?>" required>
                  </div>
                  </div>
          
    <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Phone Number</label>
                  <div class="col-sm-10">
                  <input name="phone_no" type="text" class="form-control" value="<?php echo $fetch_coopmembers->phone; ?>" required>
                  </div>
                  </div>
                  
    <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Email</label>
                  <div class="col-sm-10">
                  <input name="email" type="email" class="form-control" value="<?php echo $fetch_coopmembers->email; ?>" readonly>
                  </div>
                  </div>
          
    <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Password</label>
                  <div class="col-sm-10">
                  <input name="password" type="text" class="form-control" value="<?php echo $fetch_coopmembers->password; ?>" required>
                  </div>
                  </div>

    <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Meeting</label>
                  <div class="col-sm-10">
        <select name="meeting_freq"  class="form-control select2" required>
                    <option value="<?php echo $fetch_coopmembers->meeting_freq; ?>" selected='selected'><?php echo $fetch_coopmembers->meeting_freq; ?></option>
                    <option value="Weekly">Weekly</option>
                    <option value="Monthly">Monthly</option>
                    <option value="Daily">Daily</option>
        </select>
    </div>
    </div>

    <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Role</label>
                  <div class="col-sm-10">
                  <input name="mrole" type="text" class="form-control" value="<?php echo $fetch_coopmembers->member_role; ?>" required>
                  </div>
                  </div>

<hr>
<div class="alert bg-orange">File Attached</div>
<hr>

<?php
$get_id = $fetch_coopmembers->memberid;
$get_role = $fetch_coopmembers->member_role;
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
if($get_role == "Admin")
{
$search_file = mysqli_query($link, "SELECT * FROM coop_admin_guarantors WHERE coopid = '$get_id'") or die ("Error: " . mysqli_error($link));
if(mysqli_num_rows($search_file) == 0){
  echo "<p><span style='color: orange;'>No Guarantor Yet!!</span></p>";
}else{
  while($get_file = mysqli_fetch_array($search_file)){
?>
<hr> 
<div class="bg-orange">&nbsp;GUARANTOR INFORMATION</div>
<hr>
<div class="form-group">
        <label for="" class="col-sm-4 control-label" style="color:blue;">Gurantor's Passport</label>
        <div class="col-sm-8">
            <img src="<?php echo $get_file['gpassport']; ?>" width="100" height="100">
      </div>
      </div>

<div class="form-group">
                  <label for="" class="col-sm-4 control-label" style="color:blue;">Relationship</label>
                  <div class="col-sm-8">
                  <span style="color: green; font-size: 15px"><b><?php echo $get_file['grelationship']; ?></b></span>
                  </div>
                  </div>

<div class="form-group">
                  <label for="" class="col-sm-4 control-label" style="color:blue;">Guarantor's Name</label>
                  <div class="col-sm-8">
                  <span style="color: green; font-size: 15px"><b><?php echo $get_file['gname']; ?></b></span>
                  </div>
                  </div>

<div class="form-group">
                  <label for="" class="col-sm-4 control-label" style="color:blue;">Guarantor's Phone Number</label>
                  <div class="col-sm-8">
                  <span style="color: green; font-size: 15px"><b><?php echo $get_file['gphone']; ?></b></span>
                  </div>
                  </div>

<div class="form-group">
                  <label for="" class="col-sm-4 control-label" style="color:blue;">Guarantor's Address</label>
                  <div class="col-sm-8">
                  <span style="color: green; font-size: 15px"><b><?php echo $get_file['gaddrs']; ?></b></span>
                  </div>
                  </div>

<div class="form-group">
        <label for="" class="col-sm-4 control-label" style="color:blue;">Gurantor's Valid ID</label>
        <div class="col-sm-8">
            <img src="<?php echo $get_file['gvalid_id']; ?>" width="100" height="100">
      </div>
      </div>

<div class="form-group">
                  <label for="" class="col-sm-4 control-label" style="color:blue;">Status</label>
                  <div class="col-sm-8">
                  <span style="color: green; font-size: 15px"><b><?php echo $get_file['status']; ?></b></span>
                  </div>
                  </div>
<?php
  }
}
}
?>

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
           <label for="" class="col-sm-4 control-label" style="color: blue;">Import Bulk Details Here:</label>
  <div class="col-sm-8">
        <span class="fa fa-cloud-upload"></span>
           <span class="ks-text">Choose file</span>
           <input type="file" name="file" accept=".csv" required>
  </div>
  </div>

   <hr>
  <p style="color:red"><b style="color:blue;">NOTE:</b><br>
    <span style="color:blue;">(1)</span> <i>Kindly download the <a href="../sample/update_coopmembers.csv"> <b class="btn bg-blue"><i class="fa fa-download"></i> sample file</b></a> to upload the updated details once.</i></p>
    <span style="color:blue;">(3)</span> <i style="color:red;">Also, take note of the <span style="color: blue">member_image</span> which is to be written in this format: <b style="color:blue;">img/image_name.image_format</b>. The image format can be <b style="color: blue;">.png, .jpg, etc.</b></i></p>
    <span style="color:blue;">(3)</span> <i style="color:red;">Lastly, please note that the keywords you're expected to use while entering the meeting frequency are: <span style="color: blue">Weekly, Monthly OR Daily</span></i></p>
                        
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
            //It wiil UPDATE row on our COOP. MEMBERS table from the csv file`
             $sql = "UPDATE coop_members SET member_image = '$emapData[1]', fullname = '$emapData[2]', phone = '$emapData[3]', meeting_freq = '$emapData[4]', member_role = '$emapData[5]' WHERE memberid = '$emapData[0]'";
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
           <label for="" class="col-sm-4 control-label" style="color: blue;">Import Bulk Image Here if needed:</label>
  <div class="col-sm-8">
        <span class="fa fa-cloud-upload"></span>
           <span class="ks-text">Choose file</span>
           <input type="file" name="uploaded_file[]" multiple required>
  </div>
  </div>

   <hr>
  <p style="color:red"><b style="color:blue;">NOTICE:</b><br>
    <span style="color:blue;">(1)</span> <i style="color:red;">Upload the bulk image of just updated cooperatives members if needed, with the uses of your <span style="color: blue;">Control Key.</span></i></p>
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