<div class="row"> 
    
   <section class="content">
        
       <div class="box box-danger">
           <div class="box-body">
             <div class="table-responsive"> 
            <div class="box-body">
          <a href="view_instmembers.php?instid=<?php echo $_GET['instid']; ?>&&mid=NDE5"> <button type="button" class="btn btn-flat bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>"><i class="fa fa-reply-all"></i>&nbsp;Back</button></a>

     <div class="col-md-14">
            <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
             <li <?php echo ($_GET['tab'] == 'tab_1') ? "class='active'" : ''; ?>><a href="edit_instmembers.php?id=<?php echo $_SESSION['tid']; ?>&&idm=<?php echo $_GET['idm']; ?>&&instid=<?php echo $_GET['instid']; ?>&&mid=<?php echo base64_encode("419"); ?>&&tab=tab_1">Update Single Institution Member</a></li>
             <li <?php echo ($_GET['tab'] == 'tab_2') ? "class='active'" : ''; ?>><a href="edit_instmembers.php?id=<?php echo $_SESSION['tid']; ?>&&idm=<?php echo $_GET['idm']; ?>&&instid=<?php echo $_GET['instid']; ?>&&mid=<?php echo base64_encode("419"); ?>&&tab=tab_2">Update Bulk Institution Members</a></li>
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
  $email = mysqli_real_escape_string($link, $_POST['email']);
  $mobile_no = mysqli_real_escape_string($link, $_POST['mobile_no']);
  $urole = mysqli_real_escape_string($link, $_POST['urole']);

  $image = addslashes(file_get_contents($_FILES['image']['tmp_name']));

  if($image == "")
  {
     $update = mysqli_query($link, "UPDATE institution_user SET d_name = '$fname', email = '$email', mobile_no = '$mobile_no', urole = '$urole' WHERE directorate_id = '$memberID'") or die (mysqli_error($link));
  
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

    $detect_default_image = mysqli_query($link, "SELECT * FROM institution_user WHERE directorate_id = '$memberID'");
    $fetch_default_image = mysqli_fetch_object($detect_default_image);
    $default_image = "../".$fetch_default_image->d_image;
    unlink($default_image);
    
    $update = mysqli_query($link, "UPDATE institution_user SET d_image = '$location', d_name = '$fname', email = '$email', mobile_no = '$mobile_no', urole = '$urole' WHERE directorate_id = '$memberID'") or die (mysqli_error($link));
  
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
$search_instmembers = mysqli_query($link, "SELECT * FROM institution_user WHERE id = '$memid'");
$fetch_instmembers = mysqli_fetch_object($search_instmembers);
?>

<input name="memberID" type="hidden" class="form-control" value="<?php echo $fetch_instmembers->directorate_id; ?>">

<?php
$instid = $_GET['instid'];
$search_inst = mysqli_query($link, "SELECT * FROM institution_data WHERE institution_id = '$instid'");
$fetch_inst = mysqli_fetch_object($search_inst);
?>

       <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Institution</label>
                  <div class="col-sm-10">
                    <span style="color: <?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>; font-size: 20px;"><b><?php echo $fetch_inst->institution_name.' <span style="color: blue;">['.$fetch_inst->institution_id.']</span>'; ?></b></span>
                  </div>
                  </div>
        
      <div class="form-group">
            <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Passport</label>
      <div class="col-sm-10">
               <input type='file' name="image" onChange="readURL(this);">
               <img id="blah"  src="../<?php echo $fetch_instmembers->d_image; ?>" alt="Passport Here" height="100" width="100"/>
      </div>
      </div>
      
      <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Full Name</label>
                  <div class="col-sm-10">
                  <input name="fname" type="text" class="form-control" value="<?php echo $fetch_instmembers->d_name; ?>" required>
                  </div>
                  </div>

     <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Email Address</label>
                  <div class="col-sm-10">
                  <input name="email" type="email" class="form-control" value="<?php echo $fetch_instmembers->email; ?>" required>
                  </div>
                  </div> 
          
    <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Phone Number</label>
                  <div class="col-sm-10">
                  <input name="mobile_no" type="text" class="form-control" value="<?php echo $fetch_instmembers->mobile_no; ?>" required>
                  </div>
                  </div>

    <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Roles</label>
                  <div class="col-sm-10">
        <select name="urole" class="form-control select2" required>
                    <option value="<?php echo $fetch_instmembers->urole; ?>" selected='selected'><?php echo $fetch_instmembers->urole; ?></option>
                    <option value="Staff">Staff</option>
                    <option value="Admin">Administrator</option>
                    <option value="super-admin">Super Admin</option>
        </select>
    </div>
    </div>

       </div>
       
        <div align="right">
              <div class="box-footer">
                        <button name="update" type="submit" class="btn bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?> btn-flat"><i class="fa fa-edit">&nbsp;Update</i></button>

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
<div class="bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">&nbsp;<b> CSV FILE SECTION </b></div>
<hr>         
           <form class="form-horizontal" method="post" enctype="multipart/form-data">
             
<div class="box-body">
    
  <div class="form-group">
           <label for="" class="col-sm-4 control-label" style="color: <?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Import Bulk Update:</label>
  <div class="col-sm-8">
        <span class="fa fa-cloud-upload"></span>
           <span class="ks-text">Choose file</span>
           <input type="file" name="file" accept=".csv" required>
  </div>
  </div>

   <hr>
  <p style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>"><b style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">NOTE:</b><br>
    <span style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">(1)</span> <i>Kindly download the <a href="../sample/update_instmembers.csv"> <b class="btn bg-blue"><i class="fa fa-download"></i> sample file</b></a> to upload the updated details once.</i></p>
    <span style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">(3)</span> <i style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Also, take note of the <span style="color: blue">d_image</span> which is to be written in this format: <b style="color:blue;">img/image_name.image_format</b>. The image format can be <b style="color: blue;">.png, .jpg, etc.</b></i></p>
    <span style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">(3)</span> <i style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Lastly, please note that the keywords you're expected to use while entering the User Role are: <span style="color: blue">super-admin, Admin OR Staff</span></i></p>
                        
  <hr>
  
    <div align="right">
       <div class="box-footer">
       <button class="btn bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?> ks-btn-file" type="submit" name="Import"><span class="fa fa-cloud-upload"></span> Import Data</button> 
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
            //It wiil UPDATE row on our INST. MEMBERS table from the csv file`
             $sql = "UPDATE institution_user SET d_image = '$emapData[1]', d_name = '$emapData[2]', email = '$emapData[3]', mobile_no = '$emapData[4]', urole = '$emapData[5]' WHERE directorate_id = '$emapData[0]'";
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
           <label for="" class="col-sm-4 control-label" style="color: <?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Import Bulk Image Here if needed:</label>
  <div class="col-sm-8">
        <span class="fa fa-cloud-upload"></span>
           <span class="ks-text">Choose file</span>
           <input type="file" name="uploaded_file[]" multiple required>
  </div>
  </div>

   <hr>
  <p style="color:red"><b style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">NOTICE:</b><br>
    <span style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">(1)</span> <i style="color:red;">Upload the bulk image of just updated Institution members if needed, with the uses of your <span style="color: blue;">Control Key.</span></i></p>
  <hr>
  
    <div align="right">
       <div class="box-footer">
       <button class="btn bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?> ks-btn-file" type="submit" name="Import_Image"><span class="fa fa-cloud-upload"></span> Import Image</button> 
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