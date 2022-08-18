<div class="row">	
		
	 <section class="content">
		         
		   <div class="box box-success">
           <div class="box-body">
             <div class="table-responsive"> 
            <div class="box-body">

		 <div class="col-md-14">
            <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
             <li <?php echo ($_GET['tab'] == 'tab_1') ? "class='active'" : ''; ?>><a href="identifyVerification.php?id=<?php echo $_SESSION['tid']; ?>&&mid=<?php echo base64_encode("511"); ?>&&tab=tab_1">Verify Identity</a></li>
             <li <?php echo ($_GET['tab'] == 'tab_2') ? "class='active'" : ''; ?>><a href="#">Transfer Balance: <b><?php echo $icurrency.number_format($itransfer_balance,2,'.',','); ?></b></a></li>
              </ul>

            <div class="tab-content">
                
                
<hr>
<div class="slideshow-container">
<div class="alert bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>" style="font-size:17px;" align="center">
    <p>
        <?php
        //echo $walletafrica_status;
        if($mo_virtualacct_status == "Active" && ($walletafrica_status == "NotActive" || $walletafrica_status == "Active")){
            
            include("../config/monnify_virtualaccount.php");
            
        }
        elseif($mo_virtualacct_status == "NotActive" && $walletafrica_status == "Active"){
            
            include("../config/walletafrica_restfulapis_call.php");
            include("walletafrica_virtulaccount.php");
            
        }
        ?>
    </p>
</div>

<a class="myprev" onclick="plusSlides(-1)">&#10094;</a>
<a class="mynext" onclick="plusSlides(1)">&#10095;</a>
</div>
</hr>
                
                
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
    if(isset($_POST['verify']))
    {
        
        $verification_type = mysqli_real_escape_string($link, $_POST['verification_type']);
        $searchParam = mysqli_real_escape_string($link, $_POST['searchParameter']);
        $fName = mysqli_real_escape_string($link, $_POST['firstName']);
        $lName = mysqli_real_escape_string($link, $_POST['lastName']);
        $myGender = mysqli_real_escape_string($link, $_POST['gender']);
        $dateOfBirth = mysqli_real_escape_string($link, $_POST['dob']);
        
        $mySearchParameter = (($verification_type == "NIN-SEARCH" || $verification_type == "NIN-PHONE-SEARCH" || $verification_type == "BVN-FULL-DETAILS") ? $searchParam : (($verification_type == "NIN-DEMOGRAPHIC-SEARCH") ? $fName : ""));
        $transactionReference = uniqid('idv').time();
        $date_time = date("Y-m-d h:i:s");
        
        $sendData = [
            "verification_type" => $verification_type,
            "transactionReference" => $transactionReference,
            "searchParameter" => $mySearchParameter,
            "firstName" => ($verification_type == "NIN-DEMOGRAPHIC-SEARCH") ? $fName : "",
            "lastName" => ($verification_type == "NIN-DEMOGRAPHIC-SEARCH") ? $lName : "",
            "gender" => ($verification_type == "NIN-DEMOGRAPHIC-SEARCH") ? $myGender : "",
            "dob" => ($verification_type == "NIN-DEMOGRAPHIC-SEARCH") ? $dateOfBirth : "",
            "userid" => $vafrica_userid,
            "apiKey" => (($verification_type == "NIN-SEARCH") ? $vafrica_ninfullapi : (($verification_type == "NIN-PHONE-SEARCH") ? $vafrica_ninphonefullapi : (($verification_type == "NIN-DEMOGRAPHIC-SEARCH") ? $vafrica_ninnamefullapi : (($verification_type == "BVN-FULL-DETAILS") ? $vafrica_bvnfullapi : ""))))
        ];

        $iidentityVerificationCharges = (($verification_type == "NIN-SEARCH" || $verification_type == "NIN-PHONE-SEARCH" || $verification_type == "NIN-DEMOGRAPHIC-SEARCH") ? $ininVerificationCharges : (($verification_type == "BVN-FULL-DETAILS") ? $ibvnVerificationCharges : 0));
        $myiwallet_balance = $itransfer_balance - $iidentityVerificationCharges;

        if($iidentityVerificationCharges > $itransfer_balance){

            echo "<div class='alert alert-danger'>Insufficient fund in wallet!!</div>";

        }else{

            $verifyData = $sendSMS->identityVerifier2($sendData, $institution_id, $isbranchid, $iuid);
            $vStatus = $verifyData['verificationStatus'];
            $tStatus = $verifyData['transactionStatus'];
            $innerResponse = $verifyData['response'];
            
            if($verifyData['responseCode'] == "00"){
                
                mysqli_query($link, "UPDATE user SET transfer_balance = '$myiwallet_balance' WHERE id = '$iuid'");
                
                foreach($innerResponse as $keyData)
                    $photo = ($verification_type != "BVN-FULL-DETAILS") ? $keyData['photo'] : $innerResponse['imageBase64'];
                    $signature = ($verification_type != "BVN-FULL-DETAILS") ? $keyData['signature']: $innerResponse['basicDetailBase64'];
                
                //Convert from base64 to image
                $dynamicStr = md5(date("Y-m-d h:i"));
                $pixConverted = $sendSMS->base64Tojpeg($photo, "pix".$dynamicStr.".png");
                $otherConverted = $sendSMS->base64Tojpeg($signature, "others".$dynamicStr.".png");

                $myResponse = json_encode($innerResponse);
                
                mysqli_query($link, "INSERT INTO verification_history VALUE(null,'$transactionReference','$verification_type','$institution_id','$isbranchid','$iuid','','$myResponse','$pixConverted','$otherConverted','$vStatus','$tStatus','$date_time')");
                mysqli_query($link, "INSERT INTO wallet_history VALUES(null,'$institution_id','$transactionReference','Charges: $verification_type - $mySearchParameter','','$iidentityVerificationCharges','Debit','$icurrency','$verification_type','Description: Service Charge for $verification_type','successful','$date_time','$iuid','$myiwallet_balance','')");

                echo ($vStatus == "VERIFIED") ? "<div class='alert alert-success'>Identity Verified Successfully. <a href='viewIdentity.php?refid=".$transactionReference."' target='_blank'>CLICK HERE</a> to print</div>" : "<div class='alert alert-danger'>Verification ".$vStatus.", Transaction Status: ".$tStatus."</div>";

            }

        }
   
    }
    ?>

             <div class="box-body">
                 
                <div class="form-group">
 		            <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">Verification Type</label>
 		            <div class="col-sm-10">
 						<select name="verification_type" class="form-control select2" id="verification_type" required>
 							<option value="" selected='selected'>Select Verification Type&hellip;</option>
 							<?php
                            $explodeVA = explode(",",$iidVType);
                            $countVA = (count($explodeVA) - 1);
                            for($i = 0; $i <= $countVA; $i++){
                                echo '<option value="'.$explodeVA[$i].'">'.$explodeVA[$i].'</option>';
                            }
                            ?>
 						</select>
 				</div>
 				</div>
 				
      			<span id='ShowValueFrank'></span>
      			<span id='ShowValueFrank'></span>

			 </div>
			 
             <div align="right">
                <div class="box-footer">
                    <button name="verify" type="submit" class="btn bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>"><i class="fa fa-plus">&nbsp;Submit</i></button>
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
    </div>	
    </div>
    </div>
    </section>	
</div>