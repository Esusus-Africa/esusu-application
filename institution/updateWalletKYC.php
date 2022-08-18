<?php include("include/header.php"); ?>

<section class="invoice">
    <!-- Table row -->
    <section class="content">

      <!-- Default box -->
      <div class="box">

      <div class="col-md-14">
            <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
              <li <?php echo ($_GET['tab'] == 'tab_1') ? "class='active'" : ''; ?>><a href="updateWalletKYC.php?id=<?php echo $_SESSION['tid']; ?>&&uid=<?php echo $_GET['uid']; ?>&&tab=tab_1">Personal Information</a></li>

              <li <?php echo ($_GET['tab'] == 'tab_2') ? "class='active'" : ''; ?>><a href="updateWalletKYC.php?id=<?php echo $_SESSION['tid']; ?>&&uid=<?php echo $_GET['uid']; ?>&&tab=tab_2">KYC Verification / Documents</a></li>
              
              <li <?php echo ($_GET['tab'] == 'tab_3') ? "class='active'" : ''; ?>><a href="updateWalletKYC.php?id=<?php echo $_SESSION['tid']; ?>&&uid=<?php echo $_GET['uid']; ?>&&tab=tab_3">Next of Kin</a></li>
              
              <li <?php echo ($_GET['tab'] == 'tab_4') ? "class='active'" : ''; ?>><a href="updateWalletKYC.php?id=<?php echo $_SESSION['tid']; ?>&&uid=<?php echo $_GET['uid']; ?>&&tab=tab_4">Work Information</a></li>
            </ul>
             <div class="tab-content">

<?php 
	$id = $_GET['uid'];
	$call = mysqli_query($link, "SELECT * FROM borrowers WHERE account='$id'") or die ("Error: " . mysqli_error($link));
	$row = mysqli_fetch_assoc($call);
 ?>

<?php
if(isset($_GET['tab']) == true)
{
	$tab = $_GET['tab'];
    if($tab == 'tab_1')
	{
?>
            <div class="tab-pane <?php echo ($_GET['tab'] == 'tab_1') ? 'active' : ''; ?>" id="tab_1">

            <form class="form-horizontal" method="post" enctype="multipart/form-data">

<?php   
if (isset($_POST['save']))
{
    $id = $_GET['uid'];
    $fname = $_POST['fname'];
    $mname = $_POST['mname'];
    $email = $_POST['email'];
	$gender = $_POST['gender'];
	$dob =  $_POST['dob'];
	$myaddrs = $_POST['addrs'];
    $state = $_POST['state'];
    $lga = $_POST['lga'];
    $maidenName = $_POST['maidenName'];
    $moi = $_POST['moi'];
    $otherInfo = $_POST['otherInfo'];

    //image
    $image = $_FILES['image']['tmp_name'];

    if($image == "")
	{
		mysqli_query($link,"UPDATE borrowers SET fname='$fname',mname='$mname',email='$email',gender='$gender',addrs='$myaddrs',dob='$dob',state='$state',lga='$lga',mmaidenName='$maidenName',moi='$moi',otherInfo='$otherInfo' WHERE account ='$id'");
        
		echo "<div class='alert bg-blue'>Profile Updated Successfully</div>";
        echo '<meta http-equiv="refresh" content="2;url=updateWalletKYC.php?id='.$_SESSION['tid'].'&&uid='.$id.'&&tab=tab_1">';

	}
    elseif($image != ""){

		$target_dir = "../img/";
		$target_file = $target_dir.basename($_FILES["image"]["name"]);
		$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
		$check = getimagesize($_FILES["image"]["tmp_name"]);
		
		$sourcepath = $_FILES["image"]["tmp_name"];
		$targetpath = "../img/" . $_FILES["image"]["name"];
		move_uploaded_file($sourcepath,$targetpath);
		
		$location = $_FILES['image']['name'];
		
		mysqli_query($link,"UPDATE borrowers SET fname='$fname',mname='$mname',email='$email',gender='$gender',addrs='$myaddrs',image='$location',dob='$dob',state='$state',lga='$lga',mmaidenName='$maidenName',moi='$moi',otherInfo='$otherInfo' WHERE account ='$id'");
		
		echo "<div class='alert bg-blue'>Profile Updated Successfully</div>";
        echo '<meta http-equiv="refresh" content="2;url=updateWalletKYC.php?id='.$_SESSION['tid'].'&&uid='.$id.'&&tab=tab_1">';
    
    }
}
?>

             <div class="box-body">
				
			<div class="form-group">
            <label for="" class="col-sm-2 control-label" style="color: <?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Your Image</label>
			<div class="col-sm-10">
  		  			 <input type='file' name="image" onChange="readURL(this);" />
       				 <img id="blah"  src="<?php echo $fetchsys_config['file_baseurl'].$row['image'];?>" alt="Upload Image Here" height="100" width="100"/>
			</div>
			</div>
			
			<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color: <?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">First Name</label>
                  <div class="col-sm-10">
                  <input name="fname" type="text" class="form-control" value="<?php echo $row['fname']; ?>" /required>
                  </div>
                  </div>

                  <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color: <?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Middle Name</label>
                  <div class="col-sm-10">
                  <input name="mname" type="text" class="form-control" value="<?php echo $row['mname']; ?>" /required>
                  </div>
                  </div>

                  <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color: <?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Last Name</label>
                  <div class="col-sm-10">
                  <input name="lname" type="text" class="form-control" value="<?php echo $row['lname']; ?>" /readonly>
                  </div>
                  </div>
                  
            <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Gender</label>
                  <div class="col-sm-10">
				<select name="gender"  class="form-control select2" required>
					<option value="<?php echo $row['gender']; ?>" selected='selected'><?php echo $row['gender']; ?></option>
					<option value="Male">Male</option>
					<option value="Female">Female</option>
				</select>
    		</div>
    		</div>
    		
<?php
//MINIMUM DATE
$min_date = new DateTime(date("Y-m-d"));
$min_date->sub(new DateInterval('P60Y'));
$mymin_date = $min_date->format('Y-m-d');

//MAXIMUM DATE
$max_date = new DateTime(date("Y-m-d"));
$max_date->sub(new DateInterval('P18Y'));
$mymax_date = $max_date->format('Y-m-d');
?>

		        <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Date of Birth</label>
                  <div class="col-sm-10">
                  <input name="dob" type="date" class="form-control" value="<?php echo $row['dob'];?>" placeholder="Date Format: mm/dd/yyyy" id="txtDate" min="<?php echo $mymin_date; ?>" max="<?php echo $mymax_date; ?>" required>
                  </div>
                  </div>
				 
                <div class="form-group">
                        <label for="" class="col-sm-2 control-label" style="color: <?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Mobile Number</label>
                        <div class="col-sm-10">
                        <input name="number" type="text" class="form-control" value="<?php echo $row ['phone'];?>" readonly>
                        </div>
                        </div>

                <div class="form-group">
                        <label for="" class="col-sm-2 control-label" style="color: <?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">State of Origin</label>
                        <div class="col-sm-10">
                        <input type="text" name="state" class="form-control" value="<?php echo $row ['state'];?>" /required>
                        </div>
                        </div>

                <div class="form-group">
                        <label for="" class="col-sm-2 control-label" style="color: <?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Local Govt. Area</label>
                        <div class="col-sm-10">
                        <input name="lga" type="text" class="form-control" value="<?php echo $row ['lga'];?>" /required>
                        </div>
                        </div>

                <div class="form-group">
                        <label for="" class="col-sm-2 control-label" style="color: <?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Maiden Name</label>
                        <div class="col-sm-10">
                        <input name="maidenName" type="text" class="form-control" value="<?php echo $row ['mmaidenName'];?>" /required>
                        </div>
                        </div>

                <div class="form-group">
                        <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Mode of Identification</label>
                        <div class="col-sm-10">
                        <select name="moi" class="form-control select2" required>
                            <option value="<?php echo $row['moi']; ?>" selected='selected'><?php echo ($row['moi'] == "") ? "Select Mode of Identification" : $row['moi']; ?></option>
                            <option value="International Passport">International Passport</option>
                            <option value="National ID Card">National ID Card</option>
                            <option value="Driving License">Driving License</option>
                            <option value="Voters Card">Voters Card</option>
                        </select>
                    </div>
                    </div>
                        
                <div class="form-group">
                    <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">Address</label>
                    <div class="col-sm-10">
                        <textarea name="addrs" class="form-control" rows="2" cols="80" required><?php echo $row ['addrs'];?></textarea>
                    </div>
                </div>

                <div class="form-group">
                    <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">Other Info</label>
                    <div class="col-sm-10">
                        <textarea name="otherInfo" class="form-control" rows="2" cols="80" placeholder="Health Condition (Such as Blood Pressure, Height, Weight)" required><?php echo $row ['otherInfo'];?></textarea>
                    </div>
                </div>
				  
			 </div>
			 
			  <div align="right">
              <div class="box-footer">
                	<button name="save" type="submit" class="btn bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>"><i class="fa fa-save">&nbsp;Update Profile</i></button>
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

        <form class="form-horizontal" method="post" enctype="multipart/form-data" id="sample_form">

            <div class="box-body">

                <input name="myid" type="hidden" id="my_id" class="form-control" value="<?php echo $_GET['uid']; ?>">
                 
                <span id="success_message"></span>
                 
                <div class="form-group">
                <label for="" class="col-sm-3 control-label"></label>
                <div class="col-sm-9">
                    <div class="form-group" id="process" style="display:none;">
                        <div class="progress">
                            <div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100">
                                 
                            </div>
                        </div>
                    </div> 
                </div>
                </div>

                <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">Upload Valid ID</label>
                  <div class="col-sm-7">
                  <input name="id_wfile" type="file" id="idwFile" class="btn bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>"/>
                  <span style="color: <?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>;">Attach Copy e.g.  <b>Intl. Passport / National ID Card / Driving License</b></span>
                    </div>
                    <label class="col-sm-2"><button type="button" class="btn bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?> btn-flat" id="id_wupload"><i class="fa fa-upload"> Upload</i></button></label>
                </div>


                <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">Upload Utility Bills</label>
                  <div class="col-sm-7">
                  <input name="wutility_file" type="file" id="wutilityFile" class="btn bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>"/>
                  <span style="color: <?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>;">Upload <b>as Proof of Address</b></span>
                    </div>
                    <label class="col-sm-2"><button type="button" class="btn bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?> btn-flat" id="wutility_upload"><i class="fa fa-upload"> Upload</i></button></label>
                </div>


                <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">Upload Signature</label>
                  <div class="col-sm-7">
                  <input name="wsignature_file" type="file" id="wsignatureFile" class="btn bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>"/>
                  <span style="color: <?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>;">Upload <b>cleared signature on a plain sheet</b></span>
                  </div>
                  <label class="col-sm-2"><button type="button" class="btn bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?> btn-flat" id="wsignature_upload"><i class="fa fa-upload"> Upload</i></button></label>
                </div>
                
                
                <div id="myDiv">
                <div class="form-group">
                  <label for="" class="col-sm-3 control-label"></label>
                  <div class="col-sm-7">
                  <hr>
                  <div class="table-responsive">
                  <table id="example1" class="table table-bordered table-striped">
                    <thead>
                    <tr>
                    <th><input type="checkbox" id="select_all"/></th>
                    <th>File Title</th>
                    <th>File</th>
                    <th>Status</th>
                    <th>Date/Time</th>
                    </tr>
                    </thead>
                    <tbody>
    <?php
    $selectAttachment = mysqli_query($link, "SELECT * FROM attachment WHERE borrowerid = '$id' ORDER BY id DESC") or die (mysqli_error($link));
    if(mysqli_num_rows($selectAttachment)==0)
    {
    echo "<div class='alert bg-".(($myaltrow['theme_color'] == "") ? 'blue' : $myaltrow['theme_color'])."'>No data found yet!.....Check back later!!</div>";
    }
    else{
    while($rowAttachment = mysqli_fetch_array($selectAttachment))
    {
    $id = $rowAttachment['id'];
    ?>    
                    <tr>
                        <td><input id="optionsCheckbox" class="checkbox" name="selector[]" type="checkbox" value="<?php echo $id; ?>"></td>
                        <td><?php echo $rowAttachment['file_title']; ?></td>
                        <td><a href="<?php echo $fetchsys_config['file_baseurl'].$rowAttachment['attached_file']; ?>" class="btn bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>" target="_blank"><i class="fa fa-eyes"></i> View Attachment</a></td>
                        <td><?php echo ($rowAttachment['fstatus'] == "Approved" ? "<span class='label bg-blue'><i class='fa fa-check'></i> ".$rowAttachment['fstatus']."</span>" : ($rowAttachment['fstatus'] == "Pending" ? "<span class='label bg-orange'><i class='fa fa-exclamation'></i> ".$rowAttachment['fstatus']."</span>" : "<span class='label bg-red'><i class='fa fa-times'></i> ".$rowAttachment['fstatus']."</span>")); ?></td>
                        <td><?php echo $rowAttachment['date_time']; ?></td>
                        
                    </tr>
    <?php } } ?>
                    </tbody>
                </table>
                </div>
                  <hr>
                  </div>
                  <label class="col-sm-2 control-label"></label>
                </div>
                </div>
      
            </div>
      
        </form>

        </div>
        <!-- /.tab-pane --> 

<?php
}
elseif($tab == 'tab_3')
{
?>

        <div class="tab-pane <?php echo ($_GET['tab'] == 'tab_3') ? 'active' : ''; ?>" id="tab_3">
    	
            <form class="form-horizontal" method="post" enctype="multipart/form-data">

<?php
if (isset($_POST['save_nok'])){
    
    $id = $_GET['uid'];
    $nok = mysqli_real_escape_string($link, $_POST['nok']);
	$nok_rela = mysqli_real_escape_string($link, $_POST['nok_rela']);
    $nok_phone = mysqli_real_escape_string($link, $_POST['nok_phone']);
    $nok_addrs = mysqli_real_escape_string($link, $_POST['nok_addrs']);
    $name_of_trustee = mysqli_real_escape_string($link, $_POST['name_of_trustee']);
    
    mysqli_query($link,"UPDATE borrowers SET nok='$nok',nok_rela='$nok_rela',nok_phone='$nok_phone',nok_addrs='$nok_addrs',name_of_trustee='$name_of_trustee' WHERE account ='$id'")or die("Error: " . mysqli_error($link)); 
	
	echo "<div class='alert bg-blue'>Profile Updated Successfully</div>";
	echo '<meta http-equiv="refresh" content="2;url=updateWalletKYC.php?id='.$_SESSION['tid'].'&&uid='.$id.'&&tab=tab_3">';
    
}
?>
                <div class="box-body">
                    
                <div class="form-group">
                          <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Next of Kin</label>
                          <div class="col-sm-10">
                          <input name="nok" type="text" class="form-control" value="<?php echo $row['nok']; ?>" placeholder="Next of Kin" required>
                          </div>
                          </div>   
                
                <div class="form-group">
                          <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Relationship</label>
                          <div class="col-sm-10">
                          <input name="nok_rela" type="text" class="form-control" value="<?php echo $row['nok_rela']; ?>" placeholder="Next of Kin" required>
                          </div>
                          </div> 
                         
                <div class="form-group">
                          <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Phone</label>
                          <div class="col-sm-10">
                          <input name="nok_phone" type="text" class="form-control" value="<?php echo $row['nok_phone']; ?>" placeholder="Next of Kin Phone Number">
                          </div>
                          </div>
                
                <div class="form-group">
                    <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">Address</label>
                    <div class="col-sm-10">
                        <textarea name="nok_addrs" class="form-control" rows="2" cols="80" required><?php echo $row ['nok_addrs'];?></textarea>
                    </div>
                </div>

                <div class="form-group">
                          <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Name of Trustee</label>
                          <div class="col-sm-10">
                          <input name="name_of_trustee" type="text" class="form-control" value="<?php echo $row['name_of_trustee']; ?>" placeholder="Next of Kin Phone Number">
                          <span><i>(for beneficiaries below 18 years)</i></span>
                          </div>
                          </div>
                     
                </div> 
                
            <div align="right">
              <div class="box-footer">
                	<button name="save_nok" type="submit" class="btn bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>"><i class="fa fa-save">&nbsp;Save</i></button>
              </div>
			</div>
			
           </form>

        </div>
        <!-- /.tab-pane -->

<?php
}
elseif($tab == 'tab_4')
{
?>

        <div class="tab-pane <?php echo ($_GET['tab'] == 'tab_4') ? 'active' : ''; ?>" id="tab_4">
    	
            <form class="form-horizontal" method="post" enctype="multipart/form-data">

<?php
if(isset($_POST['save_emp'])){
    
    $id = $_GET['uid'];
    $occupation = $_POST['occupation'];
	$employer = $_POST['employer'];

    $update = mysqli_query($link,"UPDATE borrowers SET occupation='$occupation',employer='$employer' WHERE account ='$id'") or die("Error: " . mysqli_error($link));
	
	echo "<div class='alert bg-blue'>Profile Updated Successfully</div>";
	echo '<meta http-equiv="refresh" content="2;url=updateWalletKYC.php?id='.$_SESSION['tid'].'&&uid='.$id.'&&tab=tab_4">';

}
?>
                <div class="box-body">
                    
                    <div class="form-group">
                          <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Occupation</label>
                          <div class="col-sm-10">
                          <input name="occupation" type="text" class="form-control" value="<?php echo $row['occupation']; ?>" placeholder="Occupation">
                          </div>
                          </div>  
                          
                    <div class="form-group">
                          <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Employer's Name</label>
                          <div class="col-sm-10">
                          <input name="employer" type="text" class="form-control" value="<?php echo $row['employer']; ?>" placeholder="Employer">
                          </div>
                          </div>
                          
                </div>
                
                <div align="right">
                  <div class="box-footer">
                    	<button name="save_emp" type="submit" class="btn bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>"><i class="fa fa-save">&nbsp;Save</i></button>
                  </div>
    			</div>

            </form>

        </div>
        <!-- /.tab-pane --> 

<?php
}
}
?>

        </div>
        <!-- /.tab-content -->

        </div>
        </div>
      </div>
      <!-- /.box -->
<div align="center"><?php include("include/footer.php"); ?></div>
    </section>
    <!-- /.content -->
