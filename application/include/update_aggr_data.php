<div class="row"> 
    
   <section class="content">
        
       <div class="box box-danger">
           <div class="box-body">
             <div class="table-responsive"> 
            <div class="box-body">
          <a href="listaggregators.php?id=<?php echo $_SESSION['tid']; ?>&&mid=NDE5"> <button type="button" class="btn btn-flat bg-orange"><i class="fa fa-reply-all"></i>&nbsp;Back</button></a>

     <div class="col-md-14">
            <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
             <li <?php echo ($_GET['tab'] == 'tab_1') ? "class='active'" : ''; ?>><a href="update_aggr?id=<?php echo $_SESSION['tid']; ?>&&idm=<?php echo $_GET['idm']; ?>&&mid=<?php echo base64_encode("419"); ?>&&tab=tab_1">Update Aggregator</a></li>
             <!--
             <li <?php //echo ($_GET['tab'] == 'tab_2') ? "class='active'" : ''; ?>><a href="update_aggr?id=<?php //echo $_SESSION['tid']; ?>&&idm=<?php //echo $_GET['idm']; ?>&&mid=<?php //echo base64_encode("441"); ?>&&tab=tab_2">Update Multiple Aggregators</a></li>
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
    $merchant = mysqli_real_escape_string($link, $_POST['merchant']);
    $aggride = mysqli_real_escape_string($link, $_POST['aggride']);
    $fname = mysqli_real_escape_string($link, $_POST['fname']);
    $lname = mysqli_real_escape_string($link, $_POST['lname']);
    $mname = mysqli_real_escape_string($link, $_POST['mname']);
    $full_name = $lname.' '.$fname.' '.$mname;
    $dob =  mysqli_real_escape_string($link, $_POST['dob']);
    $addr1 = mysqli_real_escape_string($link, $_POST['addrs']);
    $state = mysqli_real_escape_string($link, $_POST['state']);

    $username = mysqli_real_escape_string($link, $_POST['username']);
	  $password = mysqli_real_escape_string($link, $_POST['password']);
    $encrypt = base64_encode($password);
    
    $email = mysqli_real_escape_string($link, $_POST['email']);
    $phone = mysqli_real_escape_string($link, $_POST['phone']);

    $gender =  mysqli_real_escape_string($link, $_POST['gender']);

    $aggr_co_type = mysqli_real_escape_string($link, $_POST['aggr_co_type']);
    $aggr_co_rate = mysqli_real_escape_string($link, $_POST['aggr_co_rate']);

    $status = mysqli_real_escape_string($link, $_POST['status']);

    foreach ($_FILES['documents']['name'] as $key => $name){
        
			$newFilename = $name;
			
			if($newFilename == "")
			{
				echo "";
			}
			else{
				$newlocation = $newFilename;
				if(move_uploaded_file($_FILES['uploaded_file']['tmp_name'][$key], 'img/'.$newFilename))
				{
					mysqli_query($link, "INSERT INTO institution_legaldoc VALUES(null,'$aggride','$newlocation')") or die (mysqli_error($link));
				}
			}
		  
		}

    $update = mysqli_query($link, "UPDATE aggregator SET fname = '$fname', lname = '$lname', mname = '$mname', gender = '$gender', dob = '$dob', email = '$email', phone = '$phone', username = '$username', password = '$encrypt', aggr_co_type = '$aggr_co_type', aggr_co_rate = '$aggr_co_rate', merchantid = '$merchant' WHERE id = '$id'") or die ("Error: " . mysqli_error($link));
    $update = mysqli_query($link, "UPDATE user SET name = '$fname', lname = '$lname', mname = '$mname', gender = '$gender', dob = '$dob', email = '$email', phone = '$phone', username = '$username', password = '$encrypt', addr1 = '$addr1', state = '$state', status = '$status' WHERE id = '$aggride'") or die ("Error: " . mysqli_error($link));

    if($update)
    {
        echo "<div class='alert bg-blue'>Account Updated Successfully.</div>";
    }
    else{
      echo'<span class="itext" style="color: orange">Error...Please Try Again Later!!</span>';
    } 
}
?>           
       <form class="form-horizontal" method="post" enctype="multipart/form-data">

             <div class="box-body">
<?php
$id = $_GET['idm'];
$search_myaggr = mysqli_query($link, "SELECT * FROM aggregator WHERE id = '$id'");
$fetch_myaggr = mysqli_fetch_object($search_myaggr);
$aggrIde = $fetch_myaggr->aggr_id;
$merchantIde = $fetch_myaggr->merchantid;

$searchuser = mysqli_query($link, "SELECT * FROM user WHERE id = '$aggrIde'");
$fetchuser = mysqli_fetch_array($searchuser);
?>

                <input name="aggride" type="hidden" class="form-control" value="<?php echo $aggrIde; ?>">

                <div class="form-group">
                      <label for="" class="col-sm-3 control-label" style="color:blue;">Merchant</label>
                      <div class="col-sm-7">
                          <select name="merchant" class="form-control select2" required>
                              <?php
                              $searchAGGR = mysqli_query($link, "SELECT * FROM member_settings WHERE companyid = '$merchantIde'");
                              $fetchAGGR = mysqli_fetch_array($searchAGGR);
                              ?>
                              <option value="<?php echo $merchantIde; ?>" selected='selected'><?php echo $fetchAGGR['cname']; ?></option>
                              <option value="MER-90645141">Halal Invest</option>
                              <option value="INST-191587338134">Esusu.me</option>
                              <option value="INST-271603798946">esusuPAY</option>
                          </select>
                      </div>
                      <label for="" class="col-sm-2 control-label"></label>
                  </div>

                <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">First Name</label>
                  <div class="col-sm-7">
                  <input name="fname" type="text" class="form-control" value="<?php echo $fetch_myaggr->fname; ?>" placeholder="Your First Name" required>
                  </div>
                  <label for="" class="col-sm-2 control-label"></label>
                  </div>
                  
                  <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Last Name</label>
                  <div class="col-sm-7">
                  <input name="lname" type="text" class="form-control" value="<?php echo $fetch_myaggr->lname; ?>" placeholder="Your Last Name" required>
                  </div>
                  <label for="" class="col-sm-2 control-label"></label>
                  </div>

                  <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Middle Name</label>
                  <div class="col-sm-7">
                  <input name="mname" type="text" class="form-control" value="<?php echo $fetch_myaggr->mname; ?>" placeholder="Your Middle Name" required>
                  </div>
                  <label for="" class="col-sm-2 control-label"></label>
                  </div>

                  <div class="form-group">
                      <label for="" class="col-sm-3 control-label" style="color:blue;">KYC Document</label>
                      <div class="col-sm-7">
                        <input type='file' name="documents[]" multiple required/>
                        <span style="color: orange;">You are required to upload aggregator KYC document such as National ID/Passport and Utility Bill for verification purpose.</span>
                        <hr>
                        <?php
                            $i = 0;
                            $search_file = mysqli_query($link, "SELECT * FROM institution_legaldoc WHERE instid = '$aggrIde'") or die ("Error: " . mysqli_error($link));
                            if(mysqli_num_rows($search_file) == 0){
                              echo "<span style='color: orange'>No document attached!!</span>";
                            }else{
                              while($get_file = mysqli_fetch_array($search_file)){
                                $i++;
                            ?>
                            <a href="<?php echo $fetchsys_config['file_baseurl'].$get_file['document']; ?>" target="_blank"><img src="<?php echo $fetchsys_config['file_baseurl']; ?>file_attached.png" width="64" height="64"> Attachment<?php echo $i; ?></a>
                            <?php
                              }
                            }
                        ?>
                      </div>
                      <label for="" class="col-sm-2 control-label"></label>
                  </div>
                    
                  <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Gender</label>
                  <div class="col-sm-7">
                    <select name="gender" class="form-control" required>
                                <option value="<?php echo $fetch_myaggr->gender; ?>" selected='selected'><?php echo $fetch_myaggr->gender; ?></option>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                    </select>
                </div>
                <label for="" class="col-sm-2 control-label"></label>
                </div>
                
                <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Date of Birth</label>
                  <div class="col-sm-7">
                  <input name="dob" type="date" class="form-control" value="<?php echo $fetch_myaggr->dob; ?>" placeholder="Date Format: mm/dd/yyyy" required>
                  </div>
                  <label for="" class="col-sm-2 control-label"></label>
                  </div>

                
                  <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Email Address</label>
                  <div class="col-sm-7">
                  <input name="email" type="email" class="form-control" id="vbemail" onkeyup="veryEmail();" value="<?php echo $fetch_myaggr->email; ?>" placeholder="Your Email Address" required>
                  <div id="myvemail"></div>
                  </div>
                  <label for="" class="col-sm-2 control-label"></label>
                  </div>
                  
                  <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Mobile Number</label>
                  <div class="col-sm-7">
                  <input name="phone" type="text" class="form-control" id="vbphone" onkeyup="veryBPhone();" value="<?php echo $fetch_myaggr->phone; ?>" placeholder="+2348111111111" required>
                  <p style="color: orange; font-size: 16px;">Phone Format: <b style="color: blue;">COUNTRY CODE + MOBILE NUMBER e.g +2348111111111, +12226373738</b>.</p>
                  <div id="myvphone"></div>
                  </div>
                  <label for="" class="col-sm-2 control-label"></label>
                  </div>

                  <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Location</label>
                  <div class="col-sm-7">
                  <input name="addrs" type="text" id="autocomplete1" class="form-control" value="<?php echo $fetchuser['addr1']; ?>" placeholder="Location" required>
                  </div>
                  <label for="" class="col-sm-2 control-label"></label>
                  </div>
          
                  <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">State</label>
                  <div class="col-sm-7">
                  <input name="state" type="text" class="form-control" value="<?php echo $fetchuser['state']; ?>" placeholder="State" required>
                  </div>
                  <label for="" class="col-sm-2 control-label"></label>
                  </div>
                  
                  <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Username</label>
                  <div class="col-sm-7">
                  <input name="username" type="text" class="form-control" id="vbusername" onkeyup="veryBUsername();" value="<?php echo $fetch_myaggr->username; ?>" placeholder="Your Username" required>
                  <div id="myusername"></div>
                  </div>
                  <label for="" class="col-sm-2 control-label"></label>
                  </div>


				 <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Password</label>
                  <div class="col-sm-7">
                  <input name="password" type="password" class="form-control" value="<?php echo base64_decode($fetch_myaggr->password); ?>" placeholder="Your Password" required>
                  </div>
                  <label for="" class="col-sm-2 control-label"></label>
                  </div>

                <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Commission Type</label>
                  <div class="col-sm-7">
                    <select name="aggr_co_type" class="form-control" required>
                                <option value="<?php echo $fetch_myaggr->aggr_co_type; ?>" selected='selected'><?php echo $fetch_myaggr->aggr_co_type; ?></option>
                                <option value="Flat">Flat</option>
                                <option value="Percentage">Percentage</option>
                    </select>
                </div>
                <label for="" class="col-sm-2 control-label"></label>
                </div>

                <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Commission Rate</label>
                  <div class="col-sm-7">
                  <input name="aggr_co_rate" type="text" class="form-control" value="<?php echo $fetch_myaggr->aggr_co_rate; ?>" placeholder="Your Username" required>
                  </div>
                  <label for="" class="col-sm-2 control-label"></label>
                  </div>

                <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Status</label>
                  <div class="col-sm-7">
                    <select name="status" class="form-control" required>
                      <option value="<?php echo $fetchuser['comment']; ?>" selected='selected'><?php echo $fetchuser['comment']; ?></option>
                      <option value="Approved">Approve / Activate</option>
                      <option value="Deactivated">Disapprove / Deactivate</option>
                      <option value="Suspended">Suspend</option>
                    </select>
                </div>
                <label for="" class="col-sm-2 control-label"></label>
                </div>
        
        <div class="form-group" align="right">
                  <label for="" class="col-sm-3 control-label" style="color:blue;"></label>
                  <div class="col-sm-7">
                  <button name="update" type="submit" class="btn bg-blue btn-flat"><i class="fa fa-cloud-upload">&nbsp;Update</i></button>
                  </div>
                  <label for="" class="col-sm-2 control-label"></label>
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