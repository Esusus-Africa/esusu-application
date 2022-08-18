<div class="box">

	         <div class="box-body">
			<div class="panel panel-success">
            <div class="panel-heading bg-blue">

             <h3 class="panel-title">
            
            <?php
            if($sms_marketing == 1)
            {
            ?>
            <button type="button" class="btn btn-flat bg-white" align="left">&nbsp;<b style="color: black;">Wallet Balance:</b>&nbsp;
            <strong class="alert bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>">
            <?php
            echo "<span id='wallet_balance'>".$icurrency.number_format($iassigned_walletbal,2,'.',',')."</span>";
            ?> 
            </strong>
            &nbsp; <b style="color: black;"> == </b>
            <strong class="alert bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>">
            <?php
            
            echo "<span id='smsunit_balance'>".number_format(($iassigned_walletbal/$fetchsys_config['fax']),2,'.',',')." Unit(s) of sms </span>";
            ?> 
            </strong>
              </button>
            <?php
            }
            else{
              echo "";
            }
            ?>   
             
            </h3>

		   </div>
		   
		   <hr>
		   
		    <div align="center">
		        
		    <?php echo ($add_customer == '1') ? '<a href="export_cust_phone?id='.$_SESSION['tid'].'&&mid='.base64_encode("760").'"><button type="button" class="btn btn-flat bg-'.(($myaltrow['theme_color'] == "") ? "blue" : $myaltrow['theme_color']).'"><i class="fa fa-file"></i>&nbsp;Customer Phone</button></a>' : ''; ?>
             
            <?php echo ($add_employee == '1') ? '<a href="export_subagt_ph?id='.$_SESSION['tid'].'&&mid='.base64_encode("760").'"><button type="button" class="btn btn-flat bg-'.(($myaltrow['alternate_color'] == "") ? "orange" : $myaltrow['alternate_color']).'"><i class="fa fa-file-o"></i>&nbsp;Sub-Agent Phone</button></a>' : ''; ?>
            
            </div>
            
             <div class="box-body">


		<form class="form-horizontal" method="post" enctype="multipart/form-data">
	
			<div class="box-body">
			    
			    <div class="form-group">
                    <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Sender ID</label>
                    <div class="col-sm-6">
                        <input name="sender_id" type="text" class="form-control" value="<?php echo $fetch_icurrency->sender_id; ?>" placeholder="Enter your Sender ID not more than 11 characters" maxlength="11" required>
                    </div>
                    <label for="" class="col-sm-3 control-label"></label>
                </div>
                
                <div class="form-group">
                    <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Category</label>
                    <div class="col-sm-6">
                        <select name="cto"  class="form-control select2" id="my_cto" required style="width:100%">
            				<option value="" selected="selected">Select Category...</option>
                            <option value="All Customer">All Customer</option>
                            <option value="All Sub-Agent">All Sub-Agent</option>
                            <option value="Personalize">Personalize Message</option>
            
            				<option disabled>SELECT INDIVIDUAL CUSTOMERS</option>
            				<?php
            				$get = mysqli_query($link, "SELECT * FROM borrowers WHERE branchid = '$institution_id' ORDER BY id DESC") or die (mysqli_error($link));
            				while($rows = mysqli_fetch_array($get))
            				{
            				?>
            				<option value="<?php echo $rows['phone']; ?>"><?php echo $rows['fname'].'&nbsp;'.$rows['lname']; ?></option>
                            <?php } ?>	
            
                            <option disabled>SELECT INDIVIDUAL SUB AGENT</option>
            				<?php
            				$get = mysqli_query($link, "SELECT * FROM user WHERE created_by = '$institution_id' AND role != 'institution_super_admin' ORDER BY id DESC") or die (mysqli_error($link));
            				while($rows = mysqli_fetch_array($get))
            				{
            				?>
            				<option value="<?php echo $rows['phone']; ?>"><?php echo $rows['name']; ?></option>
                            <?php } ?>
        				</select>
                    </div>
                    <label for="" class="col-sm-3 control-label"></label>
                </div>
			
			    <span id='ShowValueFrank'></span>
			    
			    <div class="form-group">
                    <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Message Contents</label>
                    <div class="col-sm-6">
                        <textarea name="message" class="form-control" id="field" rows="4" cols="5" required></textarea>
					<div id="charNum" style="color:<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>;">0 character</div>
                    </div>
                    <label for="" class="col-sm-3 control-label"></label>
                </div>
                
			</div>
			
			<div class="form-group" align="right">
               <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;"></label>
               <div class="col-sm-6">
                   <button name="send_sms" type="submit" class="btn bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>"><i class="fa fa-forward">&nbsp;Send</i></button>
               </div>
               <label for="" class="col-sm-3 control-label"></label>
            </div>

	<?php						
	if (isset($_POST['send_sms']))
	{
        $my_cat = mysqli_real_escape_string($link, $_POST['cto']);
        $sys_abb = mysqli_real_escape_string($link, $_POST['sender_id']);
	    $sms = mysqli_real_escape_string($link, $_POST['message']);
	    
	    $sms_price = ($ifetch_maintenance_model['cust_mfee'] == "") ? $fetchsys_config['fax'] : $ifetch_maintenance_model['cust_mfee'];
	    
	    if($my_cat == "All Customer" || $my_cat == "All Sub-Agent"){
	        
	        echo $my_file=$_FILES['my_file']['tmp_name'];
	        
	        $open = fopen($my_file,'r');
 
            while(!feof($open)) 
            {
            	$getTextLine = fgets($open);
            	$explodeLine = explode(",",$getTextLine);
            	
            	list($phone_file) = $explodeLine;
            	
                
            	$qry = mysqli_query($link, "INSERT INTO sms_logs1(id,company_id,c_type,sender_id,recipient,sms_content,sms_status,date_time,price) VALUES(null,'$institution_id','institution','$sys_abb','$phone_file','$sms','Pending',NOW(),'$sms_price')");
            	
            	if(!isset($qry))
                {
                  echo "<script type=\"text/javascript\">
                      alert(\"Invalid File:Please Upload TXT File.\");
                      window.location = \"sms_marketing.php?id=".$_SESSION['tid']."&&mid=NzYw\"
                      </script>";    
                }
                else{
                    echo "<script type=\"text/javascript\">
                    alert(\"Bulk SMS Processed successfully.\");
                    window.location = \"sms_marketing.php?id=".$_SESSION['tid']."&&mid=NzYw\"
                  </script>";
                }
            }
            fclose($open);
	    }
	    elseif($my_cat == "Personalize"){
	        
	        $phone_nos = mysqli_real_escape_string($link, $_POST['phone_nos']);
	        $str_arr = preg_split("/\,/", $phone_nos);
	        $length = count($str_arr);

            $maintenance_row = mysqli_num_rows($isearch_maintenance_model);
            $debitWAllet = ($getSMS_ProviderNum == 1 || ($maintenance_row == 1 && $billing_type == "PAYGException")) ? "No" : "Yes"; 
            $sms_rate = $fetchsys_config['fax'];
            $overrallCharges = $sms_rate * $length;

            if(($iwallet_balance >= $overrallCharges && $debitWAllet == "Yes") || ($debitWAllet == "No")){

                for($i = 0; $i < $length; $i++){
                
                    
                    $refid = uniqid("EA-smsCharges-").time();
                    $mybalance = $iwallet_balance - $sms_rate;
    
                    ($billing_type == "PAYGException" ? "" : ($debitWAllet == "No" ? $sendSMS->instGeneralAlert($ozeki_password, $ozeki_url, $sys_abb, $phone_nos, $sms, $institution_id, $refid, $sms_rate, $iuid, $mybalance, $debitWallet) : ($debitWAllet == "Yes" && $sms_rate <= $iwallet_balance ? $sendSMS->instGeneralAlert($ozeki_password, $ozeki_url, $sys_abb, $phone_nos, $sms, $institution_id, $refid, $sms_rate, $iuid, $mybalance, $debitWallet) : "")));
                    $qry = mysqli_query($link, "INSERT INTO sms_logs1(id,company_id,c_type,sender_id,recipient,sms_content,sms_status,date_time,price) VALUES(null,'$institution_id','institution','$sys_abb','$str_arr[$i]','$sms','Sent',NOW(),'$sms_price')");
                    
                    if(!isset($qry))
                    {
                        echo "<script type=\"text/javascript\">
                              alert(\"Invalid File:Please Upload TXT File.\");
                              window.location = \"sms_marketing.php?id=".$_SESSION['tid']."&&mid=NzYw\"
                              </script>";    
                    }
                    else{
                        echo "<script type=\"text/javascript\">
                            alert(\"Personalize Message Processed successfully.\");
                            window.location = \"sms_marketing.php?id=".$_SESSION['tid']."&&mid=NzYw\"
                          </script>";
                    }
                }

            }else{

                echo "<script type=\"text/javascript\">
                    alert(\"Insufficient fund in wallet.\");
                    window.location = \"sms_marketing.php?id=".$_SESSION['tid']."&&mid=NzYw\"
                    </script>";

            }
	        
	        
	 
	    }
	    else{
	        
	        $qry = mysqli_query($link, "INSERT INTO sms_logs1(id,company_id,c_type,sender_id,recipient,sms_content,sms_status,date_time) VALUES(null,'$institution_id','institution','$sys_abb','$my_cat','$sms','Pending',NOW())");
            	
            if(!isset($qry))
            {
                echo "<script type=\"text/javascript\">
                    alert(\"Invalid File:Please Upload TXT File.\");
                    window.location = \"sms_marketing.php?id=".$_SESSION['tid']."&&mid=NzYw\"
                    </script>";    
            }
            else{
                echo "<script type=\"text/javascript\">
                    alert(\"SMS Processed successfully.\");
                    window.location = \"sms_marketing.php?id=".$_SESSION['tid']."&&mid=NzYw\"
                    </script>";
            }
	        
	    }
    }
	?>

			  </form>


</div>	
</div>	
</div>
</div>