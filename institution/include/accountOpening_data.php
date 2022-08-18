<div class="row">
	      <section class="content">

          <h3 class="panel-title"><a href="openAccount.php?id=<?php echo $_SESSION['tid']; ?>&&mid=<?php echo base64_encode("922"); ?>"><button type="button" class="btn bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>"><i class="fa fa-mail-reply-all"></i>&nbsp;Back</button></a></h3>

       <div class="box box-default">
           <div class="box-body">
           
             <div class="table-responsive"> 
            <div class="box-body">
            <?php
            $bid = $_GET['bid'];
            $searchBankConfig = mysqli_query($link, "SELECT * FROM account_openingbank WHERE id = '$bid'");
            $fetchBankConfig = mysqli_fetch_array($searchBankConfig);
            $regType = $fetchBankConfig['reg_type'];
            $requireDocUpload = $fetchBankConfig['mynamespace4'];
            $bankname = $fetchBankConfig['bankname'];
            ?>

            <div class="col-md-14">
            <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
             <li <?php echo ($_GET['tab'] == 'tab_1') ? "class='active'" : ''; ?>><a href="accountOpening.php?id=<?php echo $_SESSION['tid']; ?>&&mid=OTIy&&bid=<?php echo $_GET['bid']; ?>&&tab=tab_1">Account Opening Form</a></li>
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
           
			 <form class="form-horizontal" method="post" enctype="multipart/form-data">

<?php
if(isset($_POST['withBVN']))
{
  $bid = $_GET['bid'];
  $accounttier = ($bankname == "ACCESS BANK") ? mysqli_real_escape_string($link, $_POST['accounttier']) : 'null';
  $userBvn = mysqli_real_escape_string($link, $_POST['bvn_num']);
  $title = mysqli_real_escape_string($link, $_POST['title']);
  $firstName = mysqli_real_escape_string($link, $_POST['fname']);
  $surName = mysqli_real_escape_string($link, $_POST['lname']);
  $middleName = mysqli_real_escape_string($link, $_POST['mname']);
  $dob = mysqli_real_escape_string($link, $_POST['dob']);
  $customerEmail = mysqli_real_escape_string($link, $_POST['email']);
  $phoneNumber = mysqli_real_escape_string($link, $_POST['phoneNumber']);
  $gender = mysqli_real_escape_string($link, $_POST['gender']);
  $addressLine = mysqli_real_escape_string($link, $_POST['addressLine']);
  $city = mysqli_real_escape_string($link, $_POST['city']);
  $state = mysqli_real_escape_string($link, $_POST['state']);
  $country = mysqli_real_escape_string($link, $_POST['country']);
  
  //Others
  $maritalStatus = mysqli_real_escape_string($link, $_POST['maritalStatus']);
  $maidenName = mysqli_real_escape_string($link, $_POST['maidenName']);
  $religion = mysqli_real_escape_string($link, $_POST['religion']);
  $profession = mysqli_real_escape_string($link, $_POST['profession']);
  $fullname = $surName.' '.$firstName.' '.$middleName;
  
  //Doc
  $docName = ($requireDocUpload == "") ? "null" : mysqli_real_escape_string($link, $_POST['docName']);

  $searchAOBank = mysqli_query($link, "SELECT * FROM account_openingbank WHERE id = '$bid'");
  $fetchAOBank = mysqli_fetch_array($searchAOBank);
  $namespace3 = $fetchAOBank['mynamespace3'];
  $otpChecker = ($namespace3 == "") ? "noneotp" : "otp";

  $tracekey = substr((uniqid(rand(),1)),3,6);

  $final_date_time = date ('Y-m-d h:i:s');
  
  $ReqReference = date("yd").time();
    
  $TxtReference = date("ymis").rand(100000,999999);
  
  $mydata = $userBvn."|".$ReqReference."|".$TxtReference."|".$phoneNumber."|".$title."|".$firstName."|".$surName."|".$middleName."|".$dob."|".$customerEmail."|".$gender."|".$addressLine."|".$city."|".$state."|".$country."|".$maritalStatus."|".$maidenName."|".$religion."|".$accounttier."|".$docName."|".$profession;

  if($bankname == "SUNTRUST BANK"){

    $processResult = $newVA->sunTrustAccountOpening($phoneNumber,$onePipeSKey,$onePipeApiKey,$walletafrica_skey,$ReqReference,$TxtReference,$title,$firstName,$surName,$middleName,$dob,$customerEmail,$gender,$addressLine,$city,$state,$country);
    $processAccount = json_decode($processResult, true);

    //print_r($processAccount);
    
    $key = base64_encode($mydata);
    
    $getStatus = $processAccount['status'];

    if($getStatus == "WaitingForOTP"){
        
        mysqli_query($link, "INSERT INTO otp_confirmation VALUES(null,'$iuid','$tracekey','$mydata','Pending','$final_date_time')");
        mysqli_query($link, "INSERT INTO api_txtwaitinglist VALUE(null,'$iuid','$TxtReference','$mydata','$getStatus','$final_date_time')");

        echo "<div align='center'><img src='../image/checkmark.gif'><p style='color: #38A1F3;'>".$processAccount['message']."</p></div>";
        echo '<meta http-equiv="refresh" content="10;url=accountOpening.php?id='.$_SESSION['tid'].'&&mid=OTIy&&bid='.$_GET['bid'].'&&tab=tab_1&&trace='.$tracekey.'&&key='.$key.'&&'.$otpChecker.'">';

    }
    else{
        echo "<div class='alert bg-orange'>".$processAccount['message']."</div>";
    }

  }
  elseif($bankname == "ACCESS BANK"){
      
	  $sourcepath = $_FILES["image"]["tmp_name"];
	  $targetpath = "../img/" . $_FILES["image"]["name"];
	  move_uploaded_file($sourcepath,$targetpath);
	
	  $location = "img/".$_FILES['image']['name'];
	  
	  $protocol = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
      $fullDocPath = $protocol . $_SERVER['HTTP_HOST'] . '/' . $location;
                
      $processAccount = $newVA->accessAccountOpening($accessBank_apimSubKey,$accessBank_auditId,$accessBank_appId,$docName,$fullDocPath,$accounttier,$title,$profession,$maritalStatus,$middleName,$religion,$addressLine,$city,$state,$customerEmail,$phoneNumber,$userBvn);
      
      print_r($processAccount);
      
      $getStatus = $processAccount['data']['status'];
      
      if($getStatus == "success"){
          
          $account_number = $processAccount['data']['accountNumber'];
          
          mysqli_query($link, "INSERT INTO bank_account VALUES(null,'$institution_id','$isbranchid','$iuid','','$userBvn','$ReqReference','$reference','$accounttier','$title','$firstName','$surName','$middleName','$maidenName','$maritalStatus','$religion','$dob','$phoneNumber','$customerEmail','$gender','$profession','$addressLine','$city','$state','$country','$account_number','$fullname','$icurrency','$bankname','044','0','$docName','$location','Active','$currentdate')");
          
          echo "<div class='alert bg-blue'>Account Created Successfully!</div>";
          echo '<meta http-equiv="refresh" content="10;url=accountOpening.php?id='.$_SESSION['tid'].'&&mid=OTIy&&bid='.$_GET['bid'].'&&tab=tab_1">';
          
      }
      else{
          
          echo "<div class='alert bg-orange'>".$processAccount['message']."</div>";
          
      }
  }
  else{
      
      echo "<div class='alert bg-orange'>Sorry!...Service not available at the moment, please try again later!!</div>";
            
  }

}
?>



<?php
if (isset($_POST['confirm']))
{
    //$result = array();
    //$result1 = array();
    $trace = $_GET['trace'];
    $otp = mysqli_real_escape_string($link, $_POST['otp']);
    $key = base64_decode($_GET['key']);
    
	$verify_otp = mysqli_query($link, "SELECT * FROM otp_confirmation WHERE otp_code = '$trace' AND userid = '$iuid' AND status = 'Pending' AND data = '$key'");
	$fetch_data = mysqli_fetch_array($verify_otp);
	$otpnum = mysqli_num_rows($verify_otp);
	
	if($otpnum == 0){
						        
	    echo "<div class='alert bg-orange'>Opps!...Invalid Credentials!!</div>";
						        
	}else{

	    $concat = $fetch_data['data'];
    
        $datetime = $fetch_data['datetime'];
                                
        $parameter = (explode('|',$concat));
        
        $userBvn = $parameter[0];
        $ReqReference = date("yd").time();
        $TxtReference = $parameter[2];
        $phoneNumber = $parameter[3];
        $title = $parameter[4];
        $firstName = $parameter[5];
        $surName = $parameter[6];
        $middleName = $parameter[7];
        $dob = $parameter[8];
        $customerEmail = $parameter[9];
        $gender = $parameter[10];
        $addressLine = $parameter[11];
        $city = $parameter[12];
        $state = $parameter[13];
        $country = $parameter[14];
        $maritalStatus = $parameter[15];
        $maidenName = $parameter[16];
        $religion = $parameter[17];
        $accounttier = $parameter[18];
        $docName = $parameter[19];
        $profession = $parameter[20];
        $currentdate = date("Y-m-d h:i:s");
        
        if($bankname == "SUNTRUST BANK"){
            
            $stotp_generate = $newVA->sunTrustOTPAOConfirmation($otp,$ReqReference,$TxtReference,$onePipeSKey,$onePipeApiKey);
            
            $processAcctCreation = json_decode($stotp_generate, true);
            
            $responseCode = $processAcctCreation['data']['provider_response_code'];
            
            if($responseCode == "00"){
                
                $reference = $processAcctCreation['data']['provider_response']['reference'];
                $account_number = $processAcctCreation['data']['provider_response']['account_number'];
                $account_name = $processAcctCreation['data']['provider_response']['account_name'];
                $currency_code = $processAcctCreation['data']['provider_response']['currency_code'];
                $bank_name = $processAcctCreation['data']['provider_response']['bank_name'];
                $bank_code = $processAcctCreation['data']['provider_response']['bank_code'];
                $status = $processAcctCreation['data']['provider_response']['status'];
                
                //UPDATE WAITING TXT
                mysqli_query($link, "UPDATE api_txtwaitinglist SET status = '$stotp_generate' WHERE userid = '$iuid' AND refid = '$TxtReference'");
                
                mysqli_query($link, "INSERT INTO bank_account VALUES(null,'$institution_id','$isbranchid','$iuid','','$userBvn','$ReqReference','$TxtReference','$accounttier','$title','$firstName','$surName','$middleName','$maidenName','$maritalStatus','$religion','$dob','$phoneNumber','$customerEmail','$gender','$profession','$addressLine','$city','$state','$country','$account_number','$account_name','$currency_code','$bank_name','$bank_code','0','$docName','','$status','$currentdate')");
                
                mysqli_query($link, "DELETE FROM otp_confirmation WHERE userid = '$iuid' AND otp_code = '$trace' AND status = 'Pending'");
        
                echo "<div class='alert bg-blue'>Account Created Successfully!</div>";
                echo '<meta http-equiv="refresh" content="10;url=accountOpening.php?id='.$_SESSION['tid'].'&&mid=OTIy&&bid='.$_GET['bid'].'&&tab=tab_1">';
                
            }
            else{
                
                echo "<div class='alert bg-orange'>".$processAcctCreation['message']."</div>";
                
            }
        }
        /*elseif($bankname == "ACCESS BANK"){
            
            
            
        }*/
        else{
            
            echo "<div class='alert bg-orange'>Sorry!...Service not available at the moment, please try again later!!</div>";
            
        }
	    
	}
    
}
?>



<?php
if(!(isset($_GET['otp']) || isset($_GET['noneotp'])))
{
?>

            <div align="center"><img src='../<?php echo $fetchBankConfig['logo']; ?>' width='200px' height='150px'></a></div>

             <div class="box-body">
                 
                 <?php echo ($bankname == "ACCESS BANK") ? '<input name="accounttier" type="hidden" class="form-control" value="2">' : ''; ?>
                 
                  <?php
                  if($regType == "Reg_Without_BVN")
                  {
                      //DO NOTHING
                  }
                  else{
                  ?>
                  
                  <div class="form-group">
                    <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">BVN Number</label>
                    <div class="col-sm-6">
                        <input name="bvn_num" type="number" class="form-control" placeholder="Enter BVN" required>
                    </div>
                    <label for="" class="col-sm-3 control-label"></label>
                  </div>
                  
                  <?php } ?>
                  
                  <div class="form-group">
                    <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Title:</label>
                    <div class="col-sm-6">
                        <select name="title" class="form-control select2" required>
                        <option value="" selected>Select Title</option>
                        <option value="Mr.">Mr.</option>
                        <option value="Mrs.">Mrs.</option>
                        <option value="Miss.">Miss.</option>
                        <option value="Dr.">Dr.</option>
                        <option value="Prof.">Prof.</option>
                        </select>
                    </div>
                    <label for="" class="col-sm-3 control-label"></label>
                </div>
                
                <div class="form-group">
                    <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">First Name</label>
                    <div class="col-sm-6">
                        <input name="fname" type="text" class="form-control" placeholder="Enter First Name" required>
                    </div>
                    <label for="" class="col-sm-3 control-label"></label>
                  </div>
                  
                <div class="form-group">
                    <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Last Name</label>
                    <div class="col-sm-6">
                        <input name="lname" type="text" class="form-control" placeholder="Enter Last Name" required>
                    </div>
                    <label for="" class="col-sm-3 control-label"></label>
                  </div>
                  
                <div class="form-group">
                    <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Middle Name</label>
                    <div class="col-sm-6">
                        <input name="mname" type="text" class="form-control" placeholder="Enter Middle Name" required>
                    </div>
                    <label for="" class="col-sm-3 control-label"></label>
                  </div>
                  
                <div class="form-group">
                      <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Phone Number</label>
                      <div class="col-sm-3">
                      <input name="phoneNumber" type="tel" class="form-control" id="phone" placeholder="Enter Phone Number" required>
                      <div id="myvphone"></div>
                      </div><?php echo (isMobileDevice()) ? "<br>" : ""; ?>
                      <label for="" class="col-sm-1 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Gender</label>
                      <div class="col-sm-2">
                            <select name="gender" class="form-control" required>
                                <option value="" selected='selected'>Select Gender&hellip;</option>
                                <option value="M">Male</option>
                                <option value="F">Female</option>
                            </select>
                        </div>  
                        <label for="" class="col-sm-3 control-label"></label>
                    </div>

                <div class="form-group">
                    <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Profession:</label>
                    <div class="col-sm-6">
                        <select name="profession"  class="form-control select2" required>
                        <option value="" selected>Select Profession</option>
                        <option value="Banker">Banker</option>
                        <option value="Teacher">Teacher</option>
                        <option value="Lawyer">Lawyer</option>
                        <option value="Engineer">Engineer</option>
                        <option value="Architect">Architect</option>
                        <option value="Physician">Physician</option>
                        <option value="Pharmacist">Pharmacist</option>
                        <option value="Accountant">Accountant</option>
                        <option value="Dentist">Dentist</option>
                        <option value="Electrician">Electrician</option>
                        <option value="Labourer">Labourer</option>
                        <option value="Veterinarian">Veterinarian</option>
                        <option value="Software Developer">Software Developer</option>
                        <option value="Student">Student</option>
                        <option value="Artist">Artist</option>
                        <option value="Hairdresser">Hairdresser</option>
                        <option value="Plumber">Plumber</option>
                        <option value="Secretary">Secretary</option>
                        <option value="Mechanic">Mechanic</option>
                        <option value="Farmer">Farmer</option>
                        <option value="Cashier">Cashier</option>
                        <option value="Chef">Chef</option>
                        <option value="Librarian">Librarian</option>
                        <option value="Technician">Technician</option>
                        <option value="Attendant">Attendant</option>
                        <option value="Actor">Actor</option>
                        <option value="Psychologist">Psychologist</option>
                        <option value="Trader">Trader</option>
                        <option value="Designer">Designer</option>
                        <option value="Military">Military</option>
                        <option value="Police">Police</option>
                        <option value="Carpenters">Carpenters</option>
                        <option value="Surveyor">Surveyor</option>
                        <option value="Tailor">Tailor</option>
                        </select>
                    </div>
                    <label for="" class="col-sm-3 control-label"></label>
                    </div>
                    
                <div class="form-group">
                    <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Marital Status:</label>
                    <div class="col-sm-6">
                        <select name="maritalStatus"  class="form-control select2" required>
                        <option value="" selected>Select Marital Status</option>
                        <option value="Single">Single</option>
                        <option value="Married">Married</option>
                        <option value="Divorced">Divorced</option>
                        </select>
                    </div>
                    <label for="" class="col-sm-3 control-label"></label>
                    </div>
                  
                  <div class="form-group">
                    <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Maiden Name</label>
                    <div class="col-sm-6">
                        <input name="maidenName" type="text" class="form-control" placeholder="Enter maidenName" required>
                    </div>
                    <label for="" class="col-sm-3 control-label"></label>
                  </div>
                  
                <div class="form-group">
                    <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Email Address</label>
                    <div class="col-sm-6">
                        <input name="email" type="email" class="form-control" placeholder="Enter Email Address" required>
                    </div>
                    <label for="" class="col-sm-3 control-label"></label>
                  </div>
                  
<?php
//MINIMUM DATE
$min_date = new DateTime(date("Y-m-d"));
$min_date->sub(new DateInterval('P60Y'));
$mymin_date = $min_date->format('Y-m-d');

//MAXIMUM DATE
$max_date = new DateTime(date("Y-m-d"));
$max_date->sub(new DateInterval('P15Y'));
$mymax_date = $max_date->format('Y-m-d');
?>
                
                <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Date of Birth</label>
                  <div class="col-sm-6">
                  <input name="dob" type="date" class="form-control" placeholder="Date Format: mm/dd/yyyy" id="txtDate" min="<?php echo $mymin_date; ?>" max="<?php echo $mymax_date; ?>" required>
                  </div>
                  <label for="" class="col-sm-3 control-label"></label>
                </div>
                
                <div class="form-group">
                    <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Religion</label>
                    <div class="col-sm-6">
                        <select name="religion"  class="form-control select2" required>
                            <option value="" selected>Select Religion</option>
                            <option value="Christianity">Christianity</option>
                            <option value="Islamic">Islamic</option>
                        </select>
                    </div>
                    <label for="" class="col-sm-3 control-label"></label>
                  </div>
                
                <div class="form-group">
                    <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Address</label>
                    <div class="col-sm-6">
                        <input name="addressLine" type="text" class="form-control" placeholder="Enter Address" required>
                    </div>
                    <label for="" class="col-sm-3 control-label"></label>
                  </div>

                  <div class="form-group">
                    <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">City</label>
                    <div class="col-sm-6">
                        <input name="city" type="text" class="form-control" placeholder="Enter City" required>
                    </div>
                    <label for="" class="col-sm-3 control-label"></label>
                  </div>

                  <div class="form-group">
                    <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">State</label>
                    <div class="col-sm-6">
                        <input name="state" type="text" class="form-control" placeholder="Enter State" required>
                    </div>
                    <label for="" class="col-sm-3 control-label"></label>
                  </div>
                  
                  <div class="form-group">
                    <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Country</label>
                    <div class="col-sm-6">
                        <select name="country" class="form-control select2" required>
                            <option value="" selected>Select Country</option>
                            <?php
                            $selectCountry = mysqli_query($link, "SELECT * FROM bcountries");
                            while($fetchCountry = mysqli_fetch_array($selectCountry))
                            {
                            ?>
                                <option value="<?php echo strtoupper($fetchCountry['alpha_2']); ?>"><?php echo $fetchCountry['name']; ?></option>
                            <?php
                            }
                            ?>
                        </select>
                    </div>
                    <label for="" class="col-sm-3 control-label"></label>
                  </div>
                
                <?php
                if($requireDocUpload == "")
                {
                  //DO NOTHING
                }
                else{
                ?>
                
                <div class="form-group">
                    <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Document Name:</label>
                    <div class="col-sm-6">
                        <select name="docName" class="form-control select2" required>
                        <option value="" selected>Select Document Name</option>
                        <option value="International Passport">International Passport</option>
                        <option value="National ID Card">National ID Card</option>
                        </select>
                    </div>
                    <label for="" class="col-sm-3 control-label"></label>
                </div>
                  
                <div class="form-group">
                      <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Upload Document</label>
                      <div class="col-sm-6">
                        <input name="uploaded_file" type="file" class="btn bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>"/>
                        <div style="color:<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>">UPLOAD <b>GOVERNMENT ISSUED ID CARD</b></div>
                      </div>
                      <label for="" class="col-sm-3 control-label"></label>
                  </div>
                
                <?php } ?>
                  
                </div>
                
                <div class="form-group" align="right">
                    <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;"></label>
                    <div class="col-sm-6">
                        <button name="withBVN" type="submit" class="btn bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>"><i class="fa fa-save">&nbsp;Submit</i></button>
                    </div>
                    <label for="" class="col-sm-3 control-label"></label>
                </div>
                
<?php
}
else{
    include("ao_otp_confirmation.php");
}
?>
      
       </form>  

    </div>
      <!-- /.tab-pane -->

<?php
  }
  elseif($tab == 'tab_2')
  {
  ?>
             <div class="tab-pane <?php echo ($_GET['tab'] == 'tab_2') ? 'active' : ''; ?>" id="tab_2">

             <div class="box-body">
           
           <!--
			 <form class="form-horizontal" method="post" enctype="multipart/form-data">

             <div align="center"><img src='../<?php echo $fetchBankConfig['logo']; ?>' width='250px' height='200px'></a></div>

             <div class="box-body">

                <div class="form-group">
                    <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Title:</label>
                    <div class="col-sm-6">
                        <select name="accounttier"  class="form-control select2" required>
                        <option value="" selected>Select Title</option>
                        <option value="Mr.">Mr.</option>
                        <option value="Mrs.">Mrs.</option>
                        <option value="Miss.">Miss.</option>
                        <option value="Dr.">Dr.</option>
                        <option value="Prof.">Prof.</option>
                        </select>
                    </div>
                    <label for="" class="col-sm-3 control-label"></label>
                </div>

                <div class="form-group">
                    <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">AccountTier:</label>
                    <div class="col-sm-6">
                        <select name="accounttier"  class="form-control select2" required>
                        <option value="">Select Account Tier</option>
                        <option value="2">AccountTier 2</option>
                        </select>
                    </div>
                    <label for="" class="col-sm-3 control-label"></label>
                </div>

                <div class="form-group">
                    <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Profession</label>
                    <div class="col-sm-6">
                        <input name="profession" type="text" class="form-control" placeholder="Enter Your Profession e.g Banker" required>
                    </div>
                    <label for="" class="col-sm-3 control-label"></label>
                  </div>

                  <div class="form-group">
                    <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Marital Status:</label>
                    <div class="col-sm-6">
                        <select name="maritalStatus"  class="form-control select2" required>
                        <option value="" selected>Select Marital Status</option>
                        <option value="Single">Single</option>
                        <option value="Married">Married</option>
                        <option value="Divorced">Divorced</option>
                        </select>
                    </div>
                    <label for="" class="col-sm-3 control-label"></label>
                    </div>
                  
                  <div class="form-group">
                    <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Maiden Name</label>
                    <div class="col-sm-6">
                        <input name="maidenName" type="text" class="form-control" placeholder="Enter maidenName" required>
                    </div>
                    <label for="" class="col-sm-3 control-label"></label>
                  </div>

                  <div class="form-group">
                    <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Religion</label>
                    <div class="col-sm-6">
                        <select name="religion"  class="form-control select2" required>
                            <option value="" selected>Select Religion</option>
                            <option value="Christianity">Christianity</option>
                            <option value="Islamic">Islamic</option>
                        </select>
                    </div>
                    <label for="" class="col-sm-3 control-label"></label>
                  </div>

                  <div class="form-group">
                    <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Address</label>
                    <div class="col-sm-6">
                        <input name="addressLine" type="text" class="form-control" placeholder="Enter Address" required>
                    </div>
                    <label for="" class="col-sm-3 control-label"></label>
                  </div>

                  <div class="form-group">
                    <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">City</label>
                    <div class="col-sm-6">
                        <input name="city" type="text" class="form-control" placeholder="Enter City" required>
                    </div>
                    <label for="" class="col-sm-3 control-label"></label>
                  </div>

                  <div class="form-group">
                    <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">State</label>
                    <div class="col-sm-6">
                        <input name="state" type="text" class="form-control" placeholder="Enter State" required>
                    </div>
                    <label for="" class="col-sm-3 control-label"></label>
                  </div>

                  <div class="form-group">
                    <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Email Address</label>
                    <div class="col-sm-6">
                        <input name="email" type="email" class="form-control" placeholder="Enter Email Address" required>
                    </div>
                    <label for="" class="col-sm-3 control-label"></label>
                  </div>

                  <div class="form-group">
                    <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Phone</label>
                    <div class="col-sm-6">
                        <input name="mobileNumber" type="text" class="form-control" placeholder="Enter Mobile Number" required>
                    </div>
                    <label for="" class="col-sm-3 control-label"></label>
                  </div>


                  <div class="form-group">
                      <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Upload Document</label>
                      <div class="col-sm-6">
                        <input name="uploaded_file" type="file" class="btn bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>"/>
                        <div style="color:<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>">UPLOAD <b>GOVERNMENT ISSUED ID CARD</b></div>
                      </div>
                      <label for="" class="col-sm-3 control-label"></label>
                  </div>

                  
                </div>
                
                <div class="form-group" align="right">
                    <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;"></label>
                    <div class="col-sm-6">
                        <button name="withoutBVN" type="submit" class="btn bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>"><i class="fa fa-save">&nbsp;Submit</i></button>
                    </div>
                    <label for="" class="col-sm-3 control-label"></label>
                </div>

            </form>  
            -->

      </div>
    </div>
      <!-- /.tab-pane -->

<?php
}
}
?>
	
</div>	
</div>
</div>
</div>
</div>
</div>
</div>
</section>
</div>