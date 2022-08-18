<div class="row"> 
    
   <section class="content">
        
       <div class="box box-success">
           <div class="box-body">
             <div class="table-responsive"> 
            <div class="box-body">

     <div class="col-md-14">
            <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
             <li <?php echo ($_GET['tab'] == 'tab_1') ? "class='active'" : ''; ?>><a href="add_cooperative?id=<?php echo $_SESSION['tid']; ?>&&mid=<?php echo base64_encode("418"); ?>&&tab=tab_1">Add Single Cooperative</a></li>
             <li <?php echo ($_GET['tab'] == 'tab_2') ? "class='active'" : ''; ?>><a href="add_cooperative?id=<?php echo $_SESSION['tid']; ?>&&mid=<?php echo base64_encode("418"); ?>&&tab=tab_2">Register Bulk Cooperative</a></li>
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

<div class="scrollable">
 <?php
if(isset($_POST['save']))
{
$coopid = mysqli_real_escape_string($link, $_POST['coopid']);
$cname =  mysqli_real_escape_string($link, $_POST['cname']);
$ctype =  mysqli_real_escape_string($link, $_POST['ctype']);
$addrs = mysqli_real_escape_string($link, $_POST['location']);
$state = mysqli_real_escape_string($link, $_POST['state']);
$country =  mysqli_real_escape_string($link, $_POST['country']);
$coopemail =  mysqli_real_escape_string($link, $_POST['email']);
$phone =  mysqli_real_escape_string($link, $_POST['phone']);
$mobile = mysqli_real_escape_string($link, $_POST['mobile']);
$regno = mysqli_real_escape_string($link, $_POST['regno']);

//this handles uploading of rentals image
$image = addslashes(file_get_contents($_FILES['image']['tmp_name']));
$doc = addslashes(file_get_contents($_FILES['documents']['tmp_name']));

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
  
  //$today = date("Y-m-d");
  mysqli_query($link, "INSERT INTO cooperatives VALUES(null,'$coopid','$ctype','$cname','$location','$addrs','$state','$country','$coopemail','$phone','$mobile','$regno','Approved','Disable','0.0','0.0','NULL',NOW(),'0000')") or die (mysqli_error($link));

  echo $filename=$_FILES["file"]["tmp_name"];
  foreach ($_FILES['documents']['name'] as $key => $name){
 
  $newFilename = $name;
  $newlocation = '../img/'.$newFilename;
  if(move_uploaded_file($_FILES['documents']['tmp_name'][$key], '../img/'.$newFilename))
  {
    mysqli_query($link, "INSERT INTO cooperatives_legaldocuments VALUES(null,'$coopid','$newlocation')");
    include("email_sender/add_coopmsg.php");
    echo "<p><span style='color: blue'>New Cooperatives Registered Successfully <i>[".$newFilename."]</i></span> <span style='color: orange;'>with Document(s).</span></p>";
  }
  else{
    include("email_sender/add_coopmsg.php");
    echo "<script type=\"text/javascript\">
              alert(\"New Cooperative Registered Successfully\");
            </script>";
  }
  
}
}
?>   
</div>        
       <form class="form-horizontal" method="post" enctype="multipart/form-data">
             <div class="box-body">
        
      <div class="form-group">
            <label for="" class="col-sm-3 control-label" style="color:blue;">Logo</label>
      <div class="col-sm-9">
               <input type='file' name="image" onChange="readURL(this);" required>
               <img id="blah"  src="../avtar/user2.png" alt="Logo Here" height="100" width="100"/>
      </div>
      </div>
      
<?php
$coopid = 'COOP-'.rand(100000,999999);
?>
      <input name="coopid" type="hidden" class="form-control" value="<?php echo $coopid; ?>">
      
      <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Coop. Name</label>
                  <div class="col-sm-9">
                  <input name="cname" type="text" class="form-control" placeholder="Cooperative Name" required>
                  </div>
                  </div>

      <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Coop. Type</label>
                  <div class="col-sm-9">
                  <input name="ctype" type="text" class="form-control" placeholder="Cooperative Type" required>
                  <span style="color: orange"><b>Cooperative types might be either Farmer or Civil Servant etc.</b></span>
                  </div>
                  </div>
          
    <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Reg. No.</label>
                  <div class="col-sm-9">
                  <input name="regno" type="text" class="form-control" placeholder="Registration Number (Optional)">
                  </div>
                  </div>

    <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Location</label>
                  <div class="col-sm-9">
                  <input name="location" type="text" id="autocomplete1" class="form-control" onFocus="geolocate()" placeholder="Official Location" required>
                  </div>
                  </div>
          
    <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">State</label>
                  <div class="col-sm-9">
                  <input name="state" type="text" id="autocomplete2" onFocus="geolocate()" class="form-control" placeholder="State" required>
                  </div>
                  </div>
          
    <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Country</label>
                  <div class="col-sm-9">
                  <input name="country" type="text" id="autocomplete3" onFocus="geolocate()" class="form-control" placeholder="Country" required>
                  </div>
                  </div>

    <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Official Email</label>
                  <div class="col-sm-9">
                  <input name="email" type="email" class="form-control" placeholder="Official Email Address" required>
                  </div>
                  </div>

    <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Official Phone No.</label>
                  <div class="col-sm-9">
                  <input name="phone" type="text" class="form-control" placeholder="Official Phone Number" required>
                  </div>
                  </div>
          
    <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Mobile No.</label>
                  <div class="col-sm-9">
                  <input name="mobile" type="text" class="form-control" placeholder="Mobile Contact (Optional)">
                  </div>
                  </div>

      <div class="form-group">
            <label for="" class="col-sm-3 control-label" style="color:blue;">Upload Document</label>
      <div class="col-sm-9">
               <input type='file' name="documents[]" multiple/>
               <span style="color: orange;">Here, you are required to upload all the document backing the cooperative if any. AND Also, You can upload more than one documents at a time.</span>
      </div>
      </div>

      </div>
       
        <div align="right">
              <div class="box-footer">
                        <button type="reset" class="btn bg-orange"><i class="fa fa-times">&nbsp;Reset</i></button>
                        <button name="save" type="submit" class="btn bg-blue"><i class="fa fa-plus">&nbsp;Submit</i></button>

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
  <p style="color:orange"><b style="color:blue;">NOTE:</b><br>
    <span style="color:blue;">(1)</span> <i>Kindly download the <a href="../sample/cooperative_sample.csv"> <b class="btn bg-blue"><i class="fa fa-download"></i> sample file</b></a> to upload the details once.</i></p>
    <p><span style="color:blue;">(2)</span> <i style="color:orange;">Please be informed that the <span style="color: blue">id</span> field are not to be filled by you. Just leave it blank</i></p>
    <p><span style="color:blue;">(3)</span> <i style="color:orange;">Also, make sure the <span style="color: blue">reg_date</span> is in this format: <span style="color: blue">YYYY-MM-DD hr:mm:ss</span></i></p>
    <p><span style="color:blue;">(3)</span> <i style="color:orange;">Finally, take note of the <span style="color: blue">cooplogo</span> which is to be written in this format: <b style="color:blue;">img/image_name.image_format</b>. The image format can be <b style="color: blue;">.png, .jpg, etc.</b></i></p>
                        
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
            //It wiil insert a row to our borrowers table from our csv file`
             $sql = "INSERT INTO cooperatives(id,coopid,ctype,coopname,cooplogo,address,state,country,official_email,official_phone,mobile_phone,reg_no,status,fontend_reg,reg_date) VALUES(null,'$emapData[0]','$emapData[1]','$emapData[2]','$emapData[3]','$emapData[4]','$emapData[5]','$emapData[6]','$emapData[7]','$emapData[8]','$emapData[9]','$emapData[10]','$emapData[11]','$emapData[12]','$emapData[13]','$emapData[14]')";
           //we are using mysql_query function. it returns a resource on true else False on error
            $result = mysqli_query($link,$sql);

            include("email_sender/add_bulkcoopmsg.php");

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
<div class="bg-blue">&nbsp;<b> LEGAL DOCUMENTS SECTION </b></div>
<hr>
           <form class="form-horizontal" method="post" enctype="multipart/form-data">
             
<div class="box-body">
    
  <div class="form-group">
           <label for="" class="col-sm-4 control-label" style="color: blue;">Import Bulk Document:</label>
  <div class="col-sm-8">
        <span class="fa fa-cloud-upload"></span>
           <span class="ks-text">Choose file</span>
           <input type="file" name="uploaded_file2[]" multiple required>
  </div>
  </div>

   <hr>
  <p style="color:orange"><b style="color:blue;">NOTICE:</b><br>
    <span style="color:blue;">(1)</span> <i>Kindly download the <a href="../sample/cooperative_filessample.csv"> <b class="btn bg-blue"><i class="fa fa-download"></i> sample file</b></a> to upload there legal document once if any.</i></p>
    <p><span style="color:blue;">(2)</span> <i style="color:orange;">Upload the bulk document backing up those cooperatives here with the help of <span style="color: blue;">Control Key.</span></i></p>
    <p><span style="color:blue;">(3)</span> <i style="color:orange;">Finally, take note of the <span style="color: blue">file_name_with_path</span> which is to be written in this format: <b style="color:blue;">img/image_or_file_name.image_or_file_format</b>. The image / file format can be <b style="color: blue;">.png, .jpg, .pdf, .doc, .docx etc.</b></i></p>
  <hr>
  
    <div align="right">
       <div class="box-footer">
       <button class="btn bg-blue ks-btn-file" type="submit" name="Import_doc"><span class="fa fa-cloud-upload"></span> Import Documents</button> 
       </div>
    </div>  

</div>  

<div class="scrollable">
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
            //It wiil insert a row to our borrowers table from our csv file`
             $sql = "INSERT INTO  cooperatives_legaldocuments(id,coopid,document) VALUES(null,'$emapData[0]','$emapData[1]')";
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
            alert(\"Data Uploaded successfully.\");
          </script>";
     }
  }  
?>
</div>   

<hr>
<div class="bg-blue">&nbsp;<b> IMAGE / REAL FILE UPLOAD SECTION </b></div>
<hr>
           <form class="form-horizontal" method="post" enctype="multipart/form-data">
             
<div class="box-body">
    
  <div class="form-group">
           <label for="" class="col-sm-4 control-label" style="color: blue;">Import Bulk Logos Here:</label>
  <div class="col-sm-8">
        <span class="fa fa-cloud-upload"></span>
           <span class="ks-text">Choose file</span>
           <input type="file" name="uploaded_file[]" multiple required>
  </div>
  </div>

   <hr>
  <p style="color:orange"><b style="color:blue;">NOTICE:</b><br>
    <span style="color:blue;">(1)</span> <i style="color:orange;">Upload the real bulk image / files of those cooperatives here as instructed with the uses of your <span style="color: blue;">Control Key.</span></i></p>
  <hr>
  
    <div align="right">
       <div class="box-footer">
       <button class="btn bg-blue ks-btn-file" type="submit" name="Import_logo"><span class="fa fa-cloud-upload"></span> Import</button> 
       </div>
    </div>  

</div>  

<div class="scrollable">
<?php
if(isset($_POST["Import_logo"])){

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