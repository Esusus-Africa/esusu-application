<div class="row"> 
    
   <section class="content">
        
       <div class="box box-success">
           <div class="box-body">
             <div class="table-responsive"> 
            <div class="box-body">

     <div class="col-md-14">
            <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
             <li <?php echo ($_GET['tab'] == 'tab_1') ? "class='active'" : ''; ?>><a href="add_agent.php?id=<?php echo $_SESSION['tid']; ?>&&mid=<?php echo base64_encode("440"); ?>&&tab=tab_1">Add Agent</a></li>
             
             <li <?php echo ($_GET['tab'] == 'tab_2') ? "class='active'" : ''; ?>><a href="add_agent.php?id=<?php echo $_SESSION['tid']; ?>&&mid=<?php echo base64_encode("440"); ?>&&tab=tab_2">Register Bulk Agents</a></li>
          
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
                
       <form class="form-horizontal" method="post" enctype="multipart/form-data" action="process_agent_reg.php">
             <div class="box-body">

      <div class="form-group">
            <label for="" class="col-sm-2 control-label" style="color:blue;">Upload Passport</label>
      <div class="col-sm-10">
               <input type='file' name="image" class="btn bg-orange" onChange="readURL(this);" required>
               <img id="blah"  src="../avtar/user2.png" alt="Picture Here" height="100" width="100"/>
      </div>
      </div>
      
<?php
$AgentID = 'AGID-'.rand(10000,99999);
?>
      <input name="AgentID" type="hidden" class="form-control" value="<?php echo $AgentID; ?>">
     
      <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Agent Type</label>
                  <div class="col-sm-10">
                  <select name="agenttype" class="form-control" required>
                                <option value="" selected='selected'>Select Agent Type&hellip;</option>
                                <option value="Independent Thrift Collector">Independent Thrift Collector</option>
                                <option value="Mobile Money Agent">Mobile Money Agent</option>
                                <option value="Super Agent">Super Agent</option>
                                <option value="Others">Others</option>
                    </select>
                  </div>
                  </div>

      <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Full Name</label>
                  <div class="col-sm-10">
                  <input name="fname" type="text" class="form-control" placeholder="Full Name" required>
                  </div>
                  </div>
          
    <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Gender</label>
                  <div class="col-sm-10">
        <select name="gender"  class="form-control select2" required>
                    <option selected='selected'>Select Gender&hellip;</option>
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
        </select>
    </div>
    </div>

    <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Date of Birth</label>
                  <div class="col-sm-10">
                  <input name="dob" type="date" class="form-control" placeholder="Date of Birth" required>
                  </div>
                  </div>
          
    <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Business Name</label>
                  <div class="col-sm-10">
                  <input name="bname" type="text" class="form-control" placeholder="Business Name (Optional)">
                  </div>
                  </div>

    <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">RC No:</label>
                  <div class="col-sm-10">
                  <input name="rcnumber" type="text" class="form-control" placeholder="RC No. (Optional)">
                  </div>
                  </div>

    <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Sender ID</label>
                  <div class="col-sm-10">
                  <input name="senderid" type="text" class="form-control" placeholder="SMS Notification ID" required>
                  </div>
                  </div>
                  
    <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Currency</label>
                  <div class="col-sm-10">
                  <select name="currency_type" class="form-control" required>
                                <option value="" selected='selected'>Select Currency Type&hellip;</option>
                                <option value="NGN">NGN</option>
                                <option value="USD">USD</option>
                                <option value="GBP">GBP</option>
                                <option value="GHS">GHS</option>
                                <option value="KES">KES</option>
                    </select>
                  </div>
                  </div>

    <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Email Address</label>
                  <div class="col-sm-10">
                  <input name="email" type="email" class="form-control" id="vemail" onkeyup="veryEmail();" placeholder="Email Address" required>
                  <div id="myvemail"></div>
                  </div>
                  </div>

    <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Phone No.</label>
                  <div class="col-sm-10">
                  <input name="phone" type="text" class="form-control" id="vphone" onkeyup="veryPhone();" placeholder="Phone Number" required>
                  <div id="myvphone"></div>
                  </div>
                  </div>

    <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Username</label>
                  <div class="col-sm-10">
                  <input name="username" type="text" class="form-control" id="vusername" onkeyup="veryUsername();" placeholder="Username" required>
                  <div id="myusername"></div>
                  </div>
                  </div>

       </div>

       <div align="right">
              <div class="box-footer">
                        <button type="reset" class="btn bg-orange"><i class="fa fa-times">&nbsp;Reset</i></button>
                        <button name="save" type="submit" class="btn bg-blue ks-btn-file"><i class="fa fa-cloud-upload">&nbsp;Submit</i></button>

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
    <span style="color:blue;">(1)</span> <i>Kindly download the <a href="../sample/agent_data.csv"> <b class="btn bg-blue"><i class="fa fa-download"></i> sample file</b></a> to upload the details once.</i></p>
    <p><span style="color:blue;">(2)</span> <i style="color:orange;">Also, make sure you enter <span style="color: blue">Valid BVN</span> as it will be verify while uploading the csv file. And failure to validate the bvn will result to <span style="color: blue;"> Uploading not successful.</span></i></p>
    <p><span style="color:blue;">(3)</span> <i style="color:orange;">Finally, take note of the <span style="color: blue">Valid id</span> which are to be written in this format: <b style="color:blue;">img/image_name.image_format</b>. The image format can be <b style="color: blue;">.png, .jpg, etc.</b></i></p>
                        
  <hr>
  
    <div align="right">
       <div class="box-footer">
       <button class="btn bg-blue ks-btn-file" type="submit" name="ImportData"><span class="fa fa-cloud-upload"></span> Import Data</button> 
       </div>
    </div>  

</div>  

<?php
if(isset($_POST["ImportData"]))
{

    function random_password($limit)
    {
        return substr(base_convert(sha1(uniqid(mt_rand())), 16, 36), 0, $limit);
    }

    $systemset = mysqli_query($link, "SELECT * FROM systemset");
    $row1 = mysqli_fetch_object($systemset);

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
            //START2
            $AgentId = "AGID-".mt_rand(10200066,99999999);
            $upassword = base64_encode($emapData[11]);
            $id = "MEM".rand(10000,99999);
            $phone_format = "+".$emapData[9];

            $sql2 = "INSERT INTO agent_data(id,agentid,agenttype,fname,gender,dob,bname,rcnumber,occupation,addrs,email,phone,username,upassword,bvn,valid_id,utitlity_bill,id_card,status,arole,referral_bonus,wallet_balance,card_id,date_time) VALUES(null,'$AgentId','$emapData[0]','$emapData[1]','$emapData[2]','$emapData[3]','$emapData[4]','$emapData[5]','$emapData[6]','$emapData[7]','$emapData[8]','$emapData[9]','$emapData[10]','$emapData[11]','$emapData[12]','','','','Pending','agent_manager','0','0','NULL',NOW())";
            $sql2 = mysqli_query($link, "INSERT INTO user VALUES(null,'$emapData[1]','$emapData[8]','$phone_format','$emapData[7]','','','','','','Pending','$emapData[10]','$upassword','$id','','agent_manager','','Registered','$AgentId','AG')") or die (mysqli_error($link));
            //we are using mysql_query function. it returns a resource on true else False on error
            $outcome = mysqli_query($link,$sql2);
            //END
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
           <label for="" class="col-sm-4 control-label" style="color: blue;">Import Bulk ID Image Here:</label>
  <div class="col-sm-8">
        <span class="fa fa-cloud-upload"></span>
           <span class="ks-text">Choose file</span>
           <input type="file" name="uploaded_file[]" multiple required>
  </div>
  </div>

   <hr>
  <p style="color:red"><b style="color:blue;">NOTICE:</b><br>
    <span style="color:blue;">(1)</span> <i style="color:orange;">Upload the bulk image of those Valid ID as instructed with the uses of your <span style="color: blue;">Control Key.</span></i></p>
  <hr>
  
    <div align="right">
       <div class="box-footer">
       <button class="btn bg-blue ks-btn-file" type="submit" name="Import_Valid_ID"><span class="fa fa-cloud-upload"></span> Import Valid ID</button> 
       </div>
    </div>  

</div>  

<div class="scrollable">
<?php
if(isset($_POST["Import_logo_Passport"])){

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