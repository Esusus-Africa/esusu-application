<div class="row">	
		
	 <section class="content">
		 
            <h3 class="panel-title"> <a href="allinvitation.php?id=<?php echo $_SESSION['tid']; ?>&&mid=<?php echo base64_encode("120"); ?>"><button type="button" class="btn btn-flat bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>"><i class="fa fa-mail-reply-all"></i>&nbsp;Back</button> </a></h3>
        
		   <div class="box box-success">
           <div class="box-body">
             <div class="table-responsive"> 
            <div class="box-body">

		 <div class="col-md-14">
            <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
             <li <?php echo ($_GET['tab'] == 'tab_1') ? "class='active'" : ''; ?>><a href="sendinvitation.php?tid=<?php echo $_SESSION['tid']; ?>&&acn=<?php echo $_SESSION['acctno']; ?>&&mid=<?php echo base64_encode("401"); ?>&&tab=tab_1">Single Invite</a></li>
             <li <?php echo ($_GET['tab'] == 'tab_2') ? "class='active'" : ''; ?>><a href="sendinvitation.php?tid=<?php echo $_SESSION['tid']; ?>&&acn=<?php echo $_SESSION['acctno']; ?>&&mid=<?php echo base64_encode("401"); ?>&&tab=tab_2">Bulk Invite</a></li>
              </ul>
             <div class="tab-content">
<?php
function startsWith ($string, $startString) 
{ 
    $len = strlen($startString); 
    return (substr($string, 0, $len) === $startString); 
}

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
    include("../config/virtual_account_creation.php");

    $groupcode = $_GET['gcode'];
    $acct_type = "Normal";
    $reg_type = "Individual";
    $gname = "";
    $g_position = "";
    $snum =  "";
    $mstatus =  mysqli_real_escape_string($link, $_POST['mstatus']);
    $fname =  mysqli_real_escape_string($link, $_POST['fname']);
    $lname = mysqli_real_escape_string($link, $_POST['lname']);
    $mname = mysqli_real_escape_string($link, $_POST['mname']);
    $fullname = $lname.' '.$fname.' '.$mname;
    $email = mysqli_real_escape_string($link, $_POST['email']);
    //$ccode = mysqli_real_escape_string($link, $_POST['ccode']);
    $phone = mysqli_real_escape_string($link, $_POST['phone']);

    $gender =  mysqli_real_escape_string($link, $_POST['gender']);
    $dob =  mysqli_real_escape_string($link, $_POST['bdate']);

    $occupation = "";
    $addrs = "";
    $city = "";
    $state = "";
    //$zip = mysqli_real_escape_string($link, $_POST['zip']);
    $country = "";
    $nok = "";
    $nok_rela = "";
    $nok_phone = "";

    $account = mysqli_real_escape_string($link, $_POST['account']);
    $status = "Completed";
    $lofficer = "";
    //$otp = "";
    $overdraft = "";

    $username = mysqli_real_escape_string($link, $_POST['username']);
    $password = substr((uniqid(rand(),1)),3,6);

    $s_interval = "";
    $s_amount = "";
    $auto_doption = "";
    $c_interval = "";

    $verify_customer = mysqli_query($link, "SELECT * FROM borrowers WHERE branchid = '$bbranchid'");
    $fetch_cusno = mysqli_num_rows($verify_customer);

    //START CUSTOMER IDENTITY VERIFICATION
    $verify_email = mysqli_query($link, "SELECT * FROM borrowers WHERE email = '$email'");
    $detect_email = mysqli_num_rows($verify_email);

    $verify_phone = mysqli_query($link, "SELECT * FROM borrowers WHERE phone = '$phone'");	
    $detect_phone = mysqli_num_rows($verify_phone);

    $verify_username = mysqli_query($link, "SELECT * FROM borrowers WHERE username = '$username'");
    $detect_username = mysqli_num_rows($verify_username);

    $verify_Uusername = mysqli_query($link, "SELECT * FROM user WHERE username = '$username'");
    $detect_Uusername = mysqli_num_rows($verify_Uusername);

    $query = mysqli_query($link, "SELECT * FROM systemset") or die (mysqli_error($link));
    $r = mysqli_fetch_object($query);
    $sms_rate = $r->fax;
    $sys_email = $r->email;
    $walletafrica_skey = $r->walletafrica_skey;
    $mo_contract_code = $r->mo_contract_code;
    $accountReference = "EAVA-".date("dy").time();

    $refid = "EA-custReg-".time();
    $cust_charges = ($ifetch_maintenance_model['cust_mfee'] == "") ? $sms_rate : $bifetch_maintenance_model['cust_mfee'];
    $myiwallet_balance = $iwallet_balance - $cust_charges;
    $wallet_date_time = date("Y-m-d h:i:s");

    //END CUSTOMER IDENTITY VERIFICATION
    $search_memset = mysqli_query($link, "SELECT * FROM member_settings WHERE companyid = '$bbranchid'");
    $fetch_memset = mysqli_fetch_array($search_memset);
    $sysabb = $fetch_memset['sender_id'];
    $mobileapp_link = ($fetch_memset['mobileapp_link'] == "") ? "Login at https://esusu.app/$sysabb" : "Download mobile app: ".$fetch_memset['mobileapp_link'];
    //$dedicated_ussd_prefix = $fetch_memset['dedicated_ussd_prefix'];

    $transactionPin = substr((uniqid(rand(),1)),3,4);

    $inviteCode = substr((uniqid(rand(),1)),4,8);
        
    $sms = "$sysabb>>>Welcome $fname! Your Account ID: $account, Invitation Code: $inviteCode, Username: $username, Password: $password, Transaction Pin: $transactionPin. $mobileapp_link";
        
    $max_per_page = 153;
    $sms_length = strlen($sms);
    $calc_length = ceil($sms_length / $max_per_page);
        
    $sms_charges = $calc_length * $cust_charges;
    $mywallet_balance = $biwallet_balance - $sms_charges;
    $refid = "EA-smsCharges-".time();
        
    $verify_groupmember_limit = mysqli_query($link, "SELECT * FROM group_contribution WHERE groupcode = '$groupcode'");
    $fetch_vg = mysqli_fetch_array($verify_groupmember_limit);
    $max_member = $fetch_vg['glimit'];
    $totalcurrent_member = $fetch_vg['total_member'];
    $cluster = $fetch_vg['cluster'];
    $dinterval = $fetch_vg['dinterval'];
    $lastEndDate = $fetch_vg['endDate'];
    $total_queue = $fetch_vg['total_queue'];

    //Calculate Days
    $intervalPeriod = ($dinterval == "daily" ? 'day' : ($dinterval == "weekly" ? 'week' : 'month'));
    $endDate = date('Y-m-d', strtotime('+1 '.$intervalPeriod, strtotime($lastEndDate)));

    $verify_clusterOrder = mysqli_query($link, "SELECT * FROM group_member WHERE groupcode = '$groupcode' ORDER BY positionNum DESC");
    $fetch_clusterOrder = mysqli_fetch_array($verify_clusterOrder);
    $lastAssignedPosition = $fetch_clusterOrder['positionNum'];

    if($detect_email == 1){
        echo "<p style='font-size:24px; color:orange;'>Sorry, Email Address has already been used.</p>";
    }
    elseif($detect_phone == 1){
        echo "<p style='font-size:24px; color:orange;'>Sorry, Phone Number has already been used.</p>";
    }
    elseif($detect_username == 1 || $detect_Uusername == 1){
        echo "<p style='font-size:24px; color:orange;'>Sorry, Username has already been used.</p>";
    }
    elseif($max_member == $totalcurrent_member){
        echo "<p style='font-size:24px; color:orange;'>The Group has already reach the maximum limit of member's.</p>";
    }
    elseif($fetch_cusno == $bicustomer_limit && mysqli_num_rows($bsearch_maintenance_model) == 0)
    {
        echo "<script>alert('Sorry, You are unable to send invite, please contact your institution for for more details!'); </script>";
    }
    elseif($biwallet_balance < $cust_charges && mysqli_num_rows($bsearch_maintenance_model) == 1){
        echo "<script>alert('Sorry, You are unable to send invite, please contact your institution for for more details!!'); </script>";
    }
    elseif(($biwallet_balance >= $cust_charges && mysqli_num_rows($bsearch_maintenance_model) == 1) || mysqli_num_rows($bsearch_maintenance_model) == 0){
       
        $last_charge_date = date("Y-m-d h:m:s");
        $opening_date = date("Y-m-d");
        $todays_date = date('Y-m-d h:i:s');
        
        $processAccount = providusVirtualAccount($accountReference,$fullname,$mycurrencytype,$email,$fullname,$userBvn);
    
        $decode = json_decode(json_encode($processAccount), true);

        $myAccountReference = $decode['responseBody']['accountReference'];
    
        $myAccountName = $decode['responseBody']['accountName'];
                
        $myAccountNumber = $decode['responseBody']['accountNumber'];
                
        $myBankName = $decode['responseBody']['bankName'];
                
        $myStatus = $decode['responseBody']['status'];
                
        $date_time = $decode['responseBody']['createdOn'];

        $transactionPin = substr((uniqid(rand(),1)),3,4);

        $totalcurrent_member += 1;
        $lastAssignedPosition += 1;
        $total_queue += 1;

        $insert = mysqli_query($link, "INSERT INTO borrowers VALUES(null,'$snum','$fname','$lname','$mname','$email','$phone','$gender','$dob','$occupation','$addrs','$city','$state','','$country','$nok','$nok_rela','$nok_phone','Borrower','$account','$username','$password','0.0','0.0','$location',NOW(),'0000-00-00','$status','','','$bbranchid','$bsbranchid','Not-Activated','$s_interval','$s_amount','$auto_doption','$c_interval','$last_charge_date','','$otp','$icurrency','0.0','$overdraft','NULL','No','NULL','0000','$reg_type','$gname','$g_position','$acct_type','0.0','$opening_date','','','','$myAccountReference','$myAccountNumber','$myBankName','$idedicated_ussd_prefix')") or die (mysqli_error($link));
        $insert = mysqli_query($link, "INSERT INTO group_member VALUES(null,'$groupcode','$bbranchid','$virtual_acctno','$bname','$phone2','$cluster','$todays_date','$dateofbirth','$email2','$mstatus','$bgender','Member','0','Pending','$inviteCode','$lastAssignedPosition','$endDate','1')");
        $insert = mysqli_query($link, "INSERT INTO virtual_account VALUES(null,'$myAccountReference','$account','$myAccountName','$myAccountNumber','$myBankName','$myStatus','$date_time','monify')");
        $update = mysqli_query($link, "UPDATE group_contribution SET total_member = '$totalcurrent_member', total_queue = '$total_queue', endDate = '$endDate' WHERE groupcode = '$groupcode'");

        $protocol = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
        $url = $protocol . $_SERVER['HTTP_HOST']."/?acn=".$account;
        $id = rand(1000000,10000000);
        $shorturl = base_convert($id,20,36);
        
        $insert = mysqli_query($link, "INSERT INTO activate_member VALUES(null,'$url','$shorturl','No','$account')") or die ("Error: " . mysqli_error($link));
        
        $shortenedurl = $protocol . $_SERVER['HTTP_HOST'] . '/?activation_key=' . $shorturl;
        $shortenedurl1 = $protocol . $_SERVER['HTTP_HOST'] . '/?deactivation_key=' . $shorturl;
        
        $search_gateway = mysqli_query($link, "SELECT * FROM sms WHERE smsuser = '' AND status = 'Activated'");
        $fetch_gateway = mysqli_fetch_object($search_gateway);
        $gateway_uname = $fetch_gateway->username;
        $gateway_pass = $fetch_gateway->password;
        $gateway_api = $fetch_gateway->api;

        if($biwallet_balance >= $sms_charges)
        {   
            include('../cron/send_general_sms.php');
            include('../cron/send_regemail2.php');
            mysqli_query($link, "INSERT INTO wallet_history VALUE(null,'$bbranchid','$refid','$phone','$sms_charges','NGN','system','SMS Content: $sms','successful','$wallet_date_time','$iuid','$biwallet_balance')");
            mysqli_query($link, "INSERT INTO sms_logs1 VALUES(null,'$bbranchid','institution','$sysabb','$phone','$sms','Sent',NOW())");
            (($irole != "agent_manager" || $irole != "institution_super_admin" || $irole != "merchant_super_admin") && $isubagent_wallet === "Enabled") ? mysqli_query($link, "UPDATE user SET wallet_balance = '$mywallet_balance' WHERE id = '$iuid'") : mysqli_query($link, "UPDATE institution_data SET wallet_balance = '$mywallet_balance' WHERE institution_id = '$bbranchid'");
            echo "<div class='alert alert-success'>New Customer Added Successfully!...A welcome SMS/Email has been sent to the customer to join the group!!</div>";
        }
        else{
            include('../cron/send_regemail2.php');
            echo "<div class='alert alert-success'>New Customer Added Successfully!....A welcome SMS/Email has been sent to the customer to join the group!!</div>";
        }

    }
    else{
        
        echo "<div class='alert alert-danger'>Oops!....Unable to Register Customer at the moment. Please try again later!!</div>";

    }
}
?>           
			 <form class="form-horizontal" method="post" enctype="multipart/form-data">
             <div class="box-body">

            <div class="form-group">
                <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Marital Status</label>
                <div class="col-sm-6">
                    <select name="mstatus"  class="form-control select2" required>
                        <option value="" selected>Select Marital Status</option>
                        <option value="Single">Single</option>
                        <option value="Married">Married</option>
                        <option value="Divorce">Divorce</option>
                    </select>
                </div>
                <label for="" class="col-sm-3 control-label"></label>
            </div>

            <div class="form-group">
                <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">First Name</label>
                <div class="col-sm-6">
                    <input name="fname" type="text" class="form-control" placeholder="First Name" required>
                </div>
                <label for="" class="col-sm-3 control-label"></label>
            </div>

            <div class="form-group">
                <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Middle Name</label>
                <div class="col-sm-6">
                    <input name="mname" type="text" class="form-control" placeholder="Middle Name">
                </div>
                <label for="" class="col-sm-3 control-label"></label>
            </div>

            <div class="form-group">
                <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Last Name</label>
                <div class="col-sm-6">
                    <input name="lname" type="text" class="form-control" placeholder="Last Name" required>
                </div>
                <label for="" class="col-sm-3 control-label"></label>
            </div>

<?php
$account = '22'.rand(10000000,99999999);
$search_customer = mysqli_query($link, "SELECT * FROM borrowers WHERE account = '$account'");
$real_acct = (mysqli_num_rows($search_customer) == 0) ? $account : rand('30000').rand(100000,999999);
?>
                  <input name="account" type="hidden" class="form-control" value="<?php echo $real_acct; ?>">
            
            <div class="form-group">
                <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Mobile</label>
                <div class="col-sm-3">
                    <input name="phone" type="tel" class="form-control" id="phone" onkeyup="veryBPhone();" required>
                    <div id="myvbphone"></div>
                </div>
                <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Gender</label>
                <div class="col-sm-3">
                    <select name="gender" class="form-control" required>
                        <option value="" selected='selected'>Select Gender&hellip;</option>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Email</label>
                <div class="col-sm-6">
                    <input type="email" name="email" type="text" id="vbemail" onkeyup="veryBEmail();" class="form-control" placeholder="Email Address" required>
                    <div id="myvbemail"></div>
                </div>
                <label for="" class="col-sm-3 control-label"></label>
            </div>

            <div class="form-group">
                <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Date of Birth</label>
                <div class="col-sm-6">
                    <input name="bdate" type="date" class="form-control" required>
                </div>
                <label for="" class="col-sm-3 control-label"></label>
            </div>

            <div class="form-group">
                <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Username</label>
                <div class="col-sm-6">
                    <input name="username" type="text" class="form-control" id="vbusername" onkeyup="veryBUsername();" placeholder="Username" required>
                    <div id="mybusername"></div>
                </div>
                <label for="" class="col-sm-3 control-label"></label>
            </div>

<!--		
<hr>
<div class="bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>">Upload Scanned Copy of the Registration Form Filled by the Customer</div>
<hr>
        <div class="form-group">
            <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Scanned Copy</label>
			<div class="col-sm-10">
  		  			 <input type='file' name="c_sign" class="btn bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>" onChange="readURL(this);"/>
		</div>
		</div>
-->
			 </div>

             <div class="form-group" align="right">
                <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;"></label>
                <div class="col-sm-6">
                    <button name="save" type="submit" onclick="myFunction()" class="btn bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>"><i class="fa fa-plus">&nbsp;Submit</i></button>
                </div>
                <label for="" class="col-sm-3 control-label"></label>
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
				  
				   <form class="form-horizontal" method="post" enctype="multipart/form-data">
					   
<div class="box-body">

<?php
if(isset($_POST["Import"])){
    
    $search_memset = mysqli_query($link, "SELECT * FROM member_settings WHERE companyid = '$bbranchid'");
    $fetch_memset = mysqli_fetch_array($search_memset);
    $sysabb = $fetch_memset['sender_id'];
    $mobileapp_link = ($fetch_memset['mobileapp_link'] == "") ? "Login at https://esusu.app/$sysabb" : "Download mobile app: ".$fetch_memset['mobileapp_link'];
    
    $query = mysqli_query($link, "SELECT * FROM systemset") or die (mysqli_error($link));
    $r = mysqli_fetch_object($query);
    $sys_email = $r->email;

    $search_gateway = mysqli_query($link, "SELECT * FROM sms WHERE smsuser = '' AND status = 'Activated'");
    $fetch_gateway = mysqli_fetch_object($search_gateway);
    $gateway_uname = $fetch_gateway->username;
    $gateway_pass = $fetch_gateway->password;
    $gateway_api = $fetch_gateway->api;

    if($_FILES['file']['name']){
        
        $filename = explode('.', $_FILES['file']['name']);
        
        if($filename[1] == 'csv'){
            
            $handle = fopen($_FILES['file']['tmp_name'], "r");
            fgetcsv($handle,1000,","); // pop the headers
            while($data = fgetcsv($handle,1000,",")){
                
                $acct_type = "Normal";
                $reg_type = "Individual";
                $gname =  "";
                $g_position =  "";
                $snum = "";

                $verify_customer = mysqli_query($link, "SELECT * FROM borrowers WHERE branchid = '$bbranchid'");
                $fetch_cusno = mysqli_num_rows($verify_customer);

                $fname = mysqli_real_escape_string($link, $data[1]);
                $lname = mysqli_real_escape_string($link, $data[2]);
                $mname = mysqli_real_escape_string($link, $data[3]);
                $fullname = $lname.' '.$fname.' '.$mname;
                $email = mysqli_real_escape_string($link, $data[4]);
                $phone = mysqli_real_escape_string($link, $data[5]);
                $gender = mysqli_real_escape_string($link, $data[6]);
                $dob = date("Y-m-d", strtotime(mysqli_real_escape_string($link, $data[7])));
                $occupation = "";
                $address = "";
                $city = "";
                $state = "";
                $country = "";
                $next_of_kin = "";
                $next_of_kin_rela = "";
                $next_of_kin_phone = "";
                
                //LOGIN PARAMETER
                $username = mysqli_real_escape_string($link, $data[8]);
                $password = substr((uniqid(rand(),1)),3,6);

                $groupcode = mysqli_real_escape_string($link, $data[9]);
                $mstatus =  mysqli_real_escape_string($link, $data[10]);

                $currency = "";
                $account = date("hs").substr((uniqid(rand(),1)),3,6);

                $inviteCode = substr((uniqid(rand(),1)),4,8);
                $todays_date = date('Y-m-d h:i:s');
                
                $refid = "EA-custReg-".time();
                $cust_charges = ($bfetch_maintenance_model['cust_mfee'] == "") ? $fetchsys_config['fax'] : $bfetch_maintenance_model['cust_mfee'];
                $myiwallet_balance = $biwallet_balance - $cust_charges;
                              
                $opening_date = date("Y-m-d");
                $wallet_date_time = date("Y-m-d H:i:s");

                $verify_groupmember_limit = mysqli_query($link, "SELECT * FROM group_contribution WHERE groupcode = '$groupcode'");
                $fetch_vg = mysqli_fetch_array($verify_groupmember_limit);
                $max_member = $fetch_vg['glimit'];
                $totalcurrent_member = $fetch_vg['total_member'];
                $cluster = $fetch_vg['cluster'];
                $dinterval = $fetch_vg['dinterval'];
                $lastEndDate = $fetch_vg['endDate'];
                $total_queue = $fetch_vg['total_queue'];

                //Calculate Days
                $intervalPeriod = ($dinterval == "daily" ? 'day' : ($dinterval == "weekly" ? 'week' : 'month'));
                $endDate = date('Y-m-d', strtotime('+1 '.$intervalPeriod, strtotime($lastEndDate)));

                $verify_clusterOrder = mysqli_query($link, "SELECT * FROM group_member WHERE groupcode = '$groupcode' ORDER BY positionNum DESC");
                $fetch_clusterOrder = mysqli_fetch_array($verify_clusterOrder);
                $lastAssignedPosition = $fetch_clusterOrder['positionNum'];
                
                $transactionPin = substr((uniqid(rand(),1)),3,4);

                $sms = "$sysabb>>>Welcome $fname! Your Account ID: $account, Invitation Code: $inviteCode, Username: $username, Password: $password, Transaction Pin: $transactionPin. $mobileapp_link";
                
                if($max_member == $totalcurrent_member){

                    echo "<script>alert('The Group has already reach the maximum limit of member's.'); </script>";
                
                }
                elseif($fetch_cusno == $bicustomer_limit && mysqli_num_rows($bsearch_maintenance_model) == 0)
                {

                    echo "<script>alert('Sorry, You are unable to send invite, please contact your institution for for more details!'); </script>";
                
                }
                elseif($biwallet_balance < $cust_charges && mysqli_num_rows($bsearch_maintenance_model) == 1){

                    echo "<script>alert('Sorry, You are unable to send invite, please contact your institution for for more details!!'); </script>";
                
                }
                elseif(($biwallet_balance >= $cust_charges && mysqli_num_rows($bsearch_maintenance_model) == 1) || mysqli_num_rows($bsearch_maintenance_model) == 0){
                    
                    $totalcurrent_member += 1;
                    $lastAssignedPosition += 1;
                    $total_queue += 1;

                    $sql = "INSERT INTO borrowers(id,snum,fname,lname,email,phone,gender,dob,occupation,addrs,city,state,zip,country,nok,nok_rela,nok_phone,community_role,account,username,password,balance,investment_bal,image,date_time,last_withdraw_date,status,lofficer,c_sign,branchid,sbranchid,acct_status,s_contribution_interval,savings_amount,auto_debit_option,charge_interval,last_charges_date,s_disbursement_interval,opt_option,currency,wallet_balance,overdraft,card_id,card_reg,card_issurer,tpin,reg_type,gname,gposition,acct_type,expected_fixed_balance,acct_opening_date,unumber,verve_expiry_date,employer,virtual_number,virtual_acctno,bankname,dedicated_ussd_prefix) VALUES(null,'$snum','$fname','$lname','$email','$phone','$gender','$dob','$occupation','$address','$city','$state','','$country','$next_of_kin','$next_of_kin_rela','$next_of_kin_phone','Borrower','$account','$username','$password','0.0','0.0','',NOW(),'0000-00-00','Completed','','','$bbranchid','$bsbranchid','Activated','','0','No','','','','No','$currency','0.0','No','NULL','No','NULL','0000','$reg_type','$gname','$g_position','','0.0','$opening_date','','','','','','','$idedicated_ussd_prefix')";
                    $result = mysqli_query($link,$sql);

                    $insert = "INSERT INTO group_member(id,groupcode,companyid,userid,fullname,phone,cluster,joinedOn,dob,email,marital_stsatus,gender,position,totalpaid,status,inviteCode,positionNum,next_ddate,groupCycle) VALUES(null,'$groupcode','$bbranchid','$account','$fullname','$phone','$cluster','$todays_date','$dob','$email','$mstatus','$gender','Member','0','Pending','$inviteCode','$lastAssignedPosition','$endDate','1')";
                    $result2 = mysqli_query($link,$insert);

                    $update = mysqli_query($link, "UPDATE group_contribution SET total_member = '$totalcurrent_member', total_queue = '$total_queue', endDate = '$endDate' WHERE groupcode = '$groupcode'");

                    $protocol = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
                    $url = $protocol . $_SERVER['HTTP_HOST']."/?acn=".$account;
                    $id = rand(1000000,10000000);
                    $shorturl = base_convert($id,20,36);
                    
                    $insert2 = "INSERT INTO activate_member(id,url,shorturl,attempt,acn) VALUES(null,'$url','$shorturl','No','$account')";
                    $result3 = mysqli_query($link,$insert2);

                    $shortenedurl = $protocol . $_SERVER['HTTP_HOST'] . '/?activation_key=' . $shorturl;
                    $shortenedurl1 = $protocol . $_SERVER['HTTP_HOST'] . '/?deactivation_key=' . $shorturl;
                    
                    $prevent_duplicate = mysqli_query($link, "SELECT * FROM sms_logs1 WHERE recipient = '$phone' AND status = 'Pending' AND companyid = '$bbranchid'");
                    
                    if(mysqli_num_rows($prevent_duplicate) == 1){
                        
                        echo "";
                        
                    }
                    else{
                        
                        ((strlen($phone) == "14" || strlen($phone) == "13" || strlen($phone) == "11") && mysqli_num_rows($bsearch_maintenance_model) == 0) ? $sql1 = "INSERT INTO sms_logs1(id,company_id,c_type,sender_id,recipient,sms_content,sms_status,date_time,price) VALUES(null,'$bbranchid','institution','$sysabb','$phone','$sms','InvitePending',NOW(),'$cust_charges')" : $sql1 = "INSERT INTO sms_logs1(id,company_id,c_type,sender_id,recipient,sms_content,sms_status,date_time,price) VALUES(null,'$bbranchid','institution','$sysabb','$phone','$sms','InvitePending',NOW(),'$cust_charges')";
                   
                        $result1 = mysqli_query($link,$sql1);
                    }

                    include('../cron/send_regemail2.php');
                    
                    if(!$result)
        			{
        				echo "<script type=\"text/javascript\">
        					alert(\"Invalid File:Please Upload CSV File.\");
        				    </script>".mysqli_error($link);
        			}
        			
                }
                
            }
            
            fclose($handle);
            //(strlen($phone) == "14" || strlen($phone) == "13" || strlen($phone) == "11") ? include('../cron/send_general_sms.php') : "";
            echo "<script type=\"text/javascript\">
						alert(\"Invite Sent Successfully.\");
					</script>";
            
        }
        
    }
    
}
?>
    
    <div class="form-group">
        <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Import Invite:</label>
        <div class="col-sm-6">
            <span class="fa fa-cloud-upload"></span>
            <span class="ks-text">Choose file</span>
            <input type="file" name="file" accept=".csv" required>
        </div>
        <label for="" class="col-sm-3 control-label"></label>
    </div>
                        
	<hr>
    <p style="color:<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>"><b style="color:blue;">NOTE:</b><br>
    <span style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">(1)</span> <i>Kindly download the <a href="../sample/borrowers_sample.csv"> <b class="btn bg-blue"><i class="fa fa-download"></i> sample file</b></a> to upload the details once.</i></p>
    <hr>

    <div class="form-group" align="right">
        <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;"></label>
        <div class="col-sm-6">
            <button class="btn bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?> ks-btn-file" type="submit" name="Import"><span class="fa fa-cloud-upload"></span> Import</button> 
        </div>
        <label for="" class="col-sm-3 control-label"></label>
    </div>

</div>	
 
				   </form>
<!--
<hr>
<div class="bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">&nbsp;<b> IMAGE SECTION </b></div>
<hr>
           <form class="form-horizontal" method="post" enctype="multipart/form-data">
             
<div class="box-body">
    
  <div class="form-group">
           <label for="" class="col-sm-4 control-label" style="color: <?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Import Bulk Logos Here:</label>
  <div class="col-sm-8">
        <span class="fa fa-cloud-upload"></span>
           <span class="ks-text">Choose file</span>
           <input type="file" name="uploaded_file[]" multiple required>
  </div>
  </div>

   <hr>
  <p style="color:<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>"><b style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">NOTICE:</b><br>
  <hr>
  
    <div align="right">
       <div class="box-footer">
       <button class="btn bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?> ks-btn-file" type="submit" name="Import_logo"><span class="fa fa-cloud-upload"></span> Import Picture</button> 
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
-->
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