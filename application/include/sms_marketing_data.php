<div class="box">

	         <div class="box-body">
			<div class="panel panel-success">
            <div class="panel-heading bg-blue">

             <h3 class="panel-title">
                 
             
            </h3>

		   </div>
		   
		   <hr>
		   
		   <div align="center">
		   <a href="export_cust_phone?id=<?php echo $_SESSION['tid']; ?>&&mid=<?php echo base64_encode("760"); ?>"><button type="button" class="btn btn-flat bg-blue"><i class="fa fa-file"></i>&nbsp;Customer Phone</button></a>
             
             <a href="export_agt_ph?id=<?php echo $_SESSION['tid']; ?>&&mid=<?php echo base64_encode("760"); ?>"><button type="button" class="btn btn-flat bg-orange"><i class="fa fa-file-o"></i>&nbsp;Super-Agent Phone</button></a>
             
             <a href="export_mfi_ph?id=<?php echo $_SESSION['tid']; ?>&&mid=<?php echo base64_encode("760"); ?>"><button type="button" class="btn btn-flat bg-blue"><i class="fa fa-file-o"></i>&nbsp;All MFI Phone</button></a>
             
             <a href="export_mcht_ph?id=<?php echo $_SESSION['tid']; ?>&&mid=<?php echo base64_encode("760"); ?>"><button type="button" class="btn btn-flat bg-orange"><i class="fa fa-file-o"></i>&nbsp;Merchant Phone</button></a>
             
             <a href="export_coop_ph?id=<?php echo $_SESSION['tid']; ?>&&mid=<?php echo base64_encode("760"); ?>"><button type="button" class="btn btn-flat bg-blue"><i class="fa fa-file-o"></i>&nbsp;Cooperatives Phone</button></a>
             
             <a href="export_coopm_ph?id=<?php echo $_SESSION['tid']; ?>&&mid=<?php echo base64_encode("760"); ?>"><button type="button" class="btn btn-flat bg-orange"><i class="fa fa-file-o"></i>&nbsp;Cooperatives Member's Phone</button></a>
            </div>
            
             <div class="box-body">


		<form class="form-horizontal" method="post" enctype="multipart/form-data">
	
			<div class="box-body">
			    
			<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Sender ID</label>
                  <div class="col-sm-10">
                  <input name="sender_id" type="text" class="form-control" value="<?php echo $fetchsys_config['abb']; ?>" placeholder="Enter your Sender ID not more than 11 characters" maxlength="11" required>
                  </div>
                  </div>
			 
			<div class="form-group">
                <label for="" class="col-sm-2 control-label" style="color:blue;">Category</label>
                <div class="col-sm-10">
				<select name="cto"  class="form-control select2" id="my_cto" required style="width:100%">
				<option value="" selected="selected">Select Category...</option>
                <option value="All Customer">All Customer</option>
                <option value="All Super-Agent">All Super-Agent</option>
                <option value="All MFI">All MFI</option>
                <option value="All Merchant">All Merchant</option>
                <option value="All Cooperative">All Cooperative</option>
                <option value="All Coop-Members">All Cooperative Members</option>
                <option value="Personalize">Personalize Message</option>

				<option disabled>SELECT INDIVIDUAL CUSTOMERS</option>
				<?php
				$get = mysqli_query($link, "SELECT * FROM borrowers ORDER BY id DESC") or die (mysqli_error($link));
				while($rows = mysqli_fetch_array($get))
				{
				?>
				<option value="<?php echo $rows['phone']; ?>"><?php echo $rows['fname'].'&nbsp;'.$rows['lname']; ?></option>
                <?php } ?>	

                <option disabled>SELECT INDIVIDUAL SUPER AGENT</option>
				<?php
				$get = mysqli_query($link, "SELECT * FROM agent_data ORDER BY id DESC") or die (mysqli_error($link));
				while($rows = mysqli_fetch_array($get))
				{
				?>
				<option value="<?php echo $rows['phone']; ?>"><?php echo $rows['fname']." (".$rows['bname'].")"; ?></option>
                <?php } ?>
                
                <option disabled>SELECT INDIVIDUAL MFI</option>
				<?php
				$get = mysqli_query($link, "SELECT * FROM institution_data ORDER BY id DESC") or die (mysqli_error($link));
				while($rows = mysqli_fetch_array($get))
				{
				?>
				<option value="<?php echo $rows['official_phone']; ?>"><?php echo $rows['institution_name']; ?></option>
                <?php } ?>

                <option disabled>SELECT INDIVIDUAL MERCHANT</option>
				<?php
				$get = mysqli_query($link, "SELECT * FROM merchant_reg ORDER BY id DESC") or die (mysqli_error($link));
				while($rows = mysqli_fetch_array($get))
				{
				?>
				<option value="<?php echo $rows['official_phone']; ?>"><?php echo $rows['company_name']; ?></option>
                <?php } ?>
                
                <option disabled>SELECT INDIVIDUAL COOPERATIVE</option>
				<?php
				$get = mysqli_query($link, "SELECT * FROM cooperatives ORDER BY id DESC") or die (mysqli_error($link));
				while($rows = mysqli_fetch_array($get))
				{
				?>
				<option value="<?php echo $rows['mobile_phone']; ?>"><?php echo $rows['coopname']; ?></option>
                <?php } ?>

                <option disabled>SELECT INDIVIDUAL COOPERATIVE MEMBERS</option>
				<?php
				$get = mysqli_query($link, "SELECT * FROM coop_members ORDER BY id DESC") or die (mysqli_error($link));
				while($rows = mysqli_fetch_array($get))
				{
				?>
				<option value="<?php echo $rows['phone']; ?>"><?php echo $rows['fullname']; ?></option>
                <?php } ?>

                <option disabled>SELECT INDIVIDUAL SUB-AGENT</option>
				<?php
				$get = mysqli_query($link, "SELECT * FROM user WHERE role != 'super_admin' AND role != 'agent_manager' AND role != 'institution_super_admin' ORDER BY id DESC") or die (mysqli_error($link));
				while($rows = mysqli_fetch_array($get))
				{
				?>
				<option value="<?php echo $rows['phone']; ?>"><?php echo $rows['name']; ?></option>
                <?php } ?>

				</select>
				</div>
			</div>
			
			<span id='ShowValueFrank'></span>

			<div class="form-group">
               	<label for="" class="col-sm-2 control-label" style="color:blue;">Message Contents</label>
               	<div class="col-sm-10">
					<textarea name="message" class="form-control" id="field" rows="4" cols="5" required></textarea>
					<div id="charNum" style="color:orange;">0 character</div>
                   </div>
            </div>
                
			</div>
				
				<div align="right">
              <div class="box-footer">
                				<button type="reset" class="btn bg-orange"><i class="fa fa-times">&nbsp;Reset</i></button>
                				<button name="send_sms" type="submit" class="btn bg-blue"><i class="fa fa-save">&nbsp;Send</i></button>

              </div>
			  </div>

	<?php						
	if (isset($_POST['send_sms']))
	{
        $my_cat = mysqli_real_escape_string($link, $_POST['cto']);
        $sys_abb = mysqli_real_escape_string($link, $_POST['sender_id']);
	    $sms = mysqli_real_escape_string($link, $_POST['message']);
	    
	    if($my_cat == "All Customer" || $my_cat == "All Super-Agent" || $my_cat == "All MFI" || $my_cat == "All Merchant" || $my_cat == "All Cooperative" || $my_cat == "All Coop-Members"){
	        
	        echo $my_file=$_FILES['my_file']['tmp_name'];
	        
	        $open = fopen($my_file,'r');
 
            while(!feof($open)) 
            {
            	$getTextLine = fgets($open);
            	$explodeLine = explode(",",$getTextLine);
            	
            	list($phone_file) = $explodeLine;
            	
            	$qry = mysqli_query($link, "INSERT INTO sms_logs1(id,company_id,c_type,sender_id,recipient,sms_content,sms_status,date_time) VALUES(null,'','','$sys_abb','$phone_file','$sms','Pending',NOW())");
            	
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
	        
	        for($i = 0; $i < $length; $i++){
                
                $qry = mysqli_query($link, "INSERT INTO sms_logs1(id,company_id,c_type,sender_id,recipient,sms_content,sms_status,date_time) VALUES(null,'','','$sys_abb','$str_arr[$i]','$sms','Pending',NOW())");
            	
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
	 
	    }
	    else{
	        
	        $qry = mysqli_query($link, "INSERT INTO sms_logs1(id,company_id,c_type,sender_id,recipient,sms_content,sms_status,date_time) VALUES(null,'','','$sys_abb','$my_cat','$sms','Pending',NOW())");
            	
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