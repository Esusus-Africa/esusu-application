<div class="row">	
		
	 <section class="content">
		 
            <h3 class="panel-title"> Send Invite</h3>
        
		   <div class="box box-success">
           <div class="box-body">
             <div class="table-responsive"> 
            <div class="box-body">

		 <div class="col-md-14">
            <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
             <li <?php echo ($_GET['tab'] == 'tab_1') ? "class='active'" : ''; ?>><a href="sendinvite.php?id=<?php echo $_SESSION['tid']; ?>&&mid=<?php echo base64_encode("911"); ?>&&tab=tab_1">Send Individual Invite</a></li>
             <li <?php echo ($_GET['tab'] == 'tab_2') ? "class='active'" : ''; ?>><a href="sendinvite.php?id=<?php echo $_SESSION['tid']; ?>&&mid=<?php echo base64_encode("911"); ?>&&tab=tab_2">Send Bulk Invite</a></li>
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
    $fname = mysqli_real_escape_string($link, $_POST['fname']);
    $email = mysqli_real_escape_string($link, $_POST['email']);
    $phone = mysqli_real_escape_string($link, $_POST['phone']);
    $itype = mysqli_real_escape_string($link, $_POST['itype']);

    $query = mysqli_query($link, "SELECT * FROM systemset") or die (mysqli_error($link));
    $r = mysqli_fetch_object($query);
    $sms_rate = $r->fax;
    $sys_email = $r->email;

    //END CUSTOMER IDENTITY VERIFICATION
    $search_memset = mysqli_query($link, "SELECT * FROM member_settings WHERE companyid = '$institution_id'");
    $fetch_memset = mysqli_fetch_array($search_memset);
    $sysabb = $fetch_memset['sender_id'];
    $icname = $fetch_memset['cname'];
    $portalLink = ($fetch_memset['mobileapp_link'] == "") ? "https://esusu.app/$sysabb" : $fetch_memset['mobileapp_link'];

    $genRand = rand(1000000,10000000);
    $inviteCode = base_convert($genRand,20,36);
	$shortenedurl = "https://esusu.app/signup.php?id=$sysabb" . '&&ainv=' . $inviteCode;
	
    $sms = "$sysabb>>>Dear $fname! This is to notify you of an attempt by: $iname to register you as a member. Please click $shortenedurl to join $icname. Thanks";
    
    $max_per_page = 153;
    $sms_length = strlen($sms);
    $calc_length = ceil($sms_length / $max_per_page);
    
    $sms_charges = $calc_length * $sms_rate;
    $mywallet_balance = $itransfer_balance - $sms_charges;
    $refid = "EA-smsCharges-".time();

    $maintenance_row = mysqli_num_rows($isearch_maintenance_model);
    $debitWAllet = ($getSMS_ProviderNum == 1 || ($maintenance_row == 1 && $billing_type == "PAYGException")) ? "No" : "Yes"; 
    $userType = "user";

    $mydata = $fname."|".$email."|".$phone."|".$sysabb."|".$itype;

    $datetime = date("Y-m-d h:i:s");
  
	$insert = mysqli_query($link, "INSERT INTO invite VALUES(null,'$institution_id','$iuid','$mydata','$inviteCode','$shortenedurl','','Sent','$datetime')") or die (mysqli_error($link));

	($billing_type == "PAYGException" ? "" : ($debitWAllet == "No" ? $sendSMS->userGeneralAlert($ozeki_password, $ozeki_url, $sysabb, $phone, $sms, $institution_id, $refid, $sms_charges, $iuid, $mywallet_balance, $debitWallet, $userType) : ($debitWAllet == "Yes" && $sms_charges <= $itransfer_balance ? $sendSMS->userGeneralAlert($ozeki_password, $ozeki_url, $sysabb, $phone, $sms, $institution_id, $refid, $sms_charges, $iuid, $mywallet_balance, $debitWallet, $userType) : "")));
    $sendSMS->inviteEmailNotifier($email, $fname, $iname, $icname, $shortenedurl, $portalLink, $iemailConfigStatus, $ifetch_emailConfig);
		
    echo "<div class='alert alert-success'>Invite Sent Successfully!!</div>";
}
?>           
			 <form class="form-horizontal" method="post" enctype="multipart/form-data">
             <div class="box-body">

             <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Type</label>
                  <div class="col-sm-10">
				<select name="itype"  class="form-control select2" required>
					<option value="" selected='selected'>Select Type</option>
					<option value="0">Individual - 0</option>
					<option value="1">Agent - 1</option>
				</select>
    		</div>
    		</div>

                <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">First Name</label>
                  <div class="col-sm-10">
                    <input type="text" name="fname" type="text" class="form-control" placeholder="First Name" required>
                  </div>
				</div>
			
                <div class="form-group">
                    <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Mobile (Optional)</label>
                    <div class="col-sm-4">
                        <input name="phone" type="text" class="form-control" placeholder="Enter Phone Number">
					</div><?php echo (isMobileDevice()) ? "<br>" : ""; ?>
                    <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Email Address</label>
                    <div class="col-sm-4">
                        <input type="email" name="email" type="text" id="vbemail" onkeyup="veryBEmail();" class="form-control" placeholder="Email" required>
                        <div id="myvbemail"></div>
                    </div>  
                </div>

			 </div>
			 
			  <div align="right">
              <div class="box-footer">
                	<button name="save" type="submit" class="btn bg-blue"><i class="fa fa-forward">&nbsp;Submit</i></button>
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
				  
				   <form class="form-horizontal" method="post" enctype="multipart/form-data">
					   
<div class="box-body">

<?php
if(isset($_POST["Import"])){
    
    $search_memset = mysqli_query($link, "SELECT * FROM member_settings WHERE companyid = '$institution_id'");
    $fetch_memset = mysqli_fetch_array($search_memset);
    //$sys_abb = $get_sys['abb'];
    $sysabb = $fetch_memset['sender_id'];
    
    if($_FILES['file']['name']){
        
        $filename = explode('.', $_FILES['file']['name']);
        
        if($filename[1] == 'csv'){
            
            $handle = fopen($_FILES['file']['tmp_name'], "r");
            fgetcsv($handle,1000,","); // pop the headers
            while($data = fgetcsv($handle,1000,",")){
                
                $fname = mysqli_real_escape_string($link, $data[1]);
                $email = mysqli_real_escape_string($link, $data[2]);
                $phone = mysqli_real_escape_string($link, $data[3]);
                $itype = mysqli_real_escape_string($link, $data[0]);

                $genRand = rand(1000000,10000000);
                $inviteCode = base_convert($genRand,20,36);
                $shortenedurl = "https://esusu.app/signup.php?id=$sysabb" . '&&ainv=' . $inviteCode;
                
                $mydata = $fname."|".$email."|".$phone."|".$sysabb."|".$itype;

                $datetime = date("Y-m-d h:i:s");
            	
                $sql = "INSERT INTO invite(id,companyid,userid,mydata,invite_code,invite_link,customerid,status,date_time) VALUES(null,'$institution_id','$iuid','$mydata','$inviteCode','$shortenedurl','','Pending','$datetime')";
                $result = mysqli_query($link,$sql);
                    
                if(!$result)
       			{
       				echo "<script type=\"text/javascript\">
       					alert(\"Invalid File:Please Upload CSV File.\");
       				    </script>".mysqli_error($link);
       			}
        			
            }
                
        }
        fclose($handle);
        echo "<script type=\"text/javascript\">
				alert(\"Invite Sent Successfully\");
			</script>";
            
        }
        
    }
?>
      			
	<div class="form-group">
        <label for="" class="col-sm-2 control-label" style="color: <?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Upload Invite:</label>
        <div class="col-sm-10">
            <span class="fa fa-cloud-upload"></span>
            <span class="ks-text">Choose file</span>
            <input type="file" name="file" accept=".csv" required>
        </div>
	</div>
                        
    <div class="form-group">
        <label for="" class="col-sm-2 control-label" style="color: <?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;"><b>NOTE:</b></label>
        <div class="col-sm-10">
        <span style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">(1)</span> <i>Kindly download the <a href="../sample/customerInvite_sample.csv"> <b class="btn bg-blue"><i class="fa fa-download"></i> sample file</b></a> to upload.</i>
        </div>
	</div>
	
    <div align="right">
       <div class="box-footer">
	     <button class="btn bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?> ks-btn-file" type="submit" name="Import"><span class="fa fa-cloud-upload"></span> Send</button> 
       </div>
    </div>  

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
<?php
$link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'] . "/signup.php?id=". $isenderid;
?>
<b>YOUR INVITE LINK: </b><a href='<?php echo $link."&&inv=".$iuid; ?>' target='_blank'><?php echo $link."&&inv=".$iuid; ?></a>

              </div>
	
</div>	
</div>
</div>
</section>	
</div>