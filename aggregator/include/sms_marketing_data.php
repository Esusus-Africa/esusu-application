<div class="box">

	         <div class="box-body">
			<div class="panel panel-success">
            <div class="panel-heading bg-blue">

            <h3 class="panel-title">
                 
            <button type="button" class="btn btn-flat bg-white" align="left">&nbsp;<b style="color: black;">Transfer Balance:</b>&nbsp;
            <strong class="alert bg-orange">
            <span id='wallet_balance'><?php echo $aggcurrency.number_format($aggwallet_balance,2,'.',','); ?></span>
            
            </strong>
            &nbsp; <b style="color: black;"> == </b>
            <strong class="alert bg-orange">
           <span id='smsunit_balance'><?php echo number_format(($aggwallet_balance/$fetchsys_config['fax']),2,'.',',')." Unit(s) of sms"; ?></span>
            </strong>
              </button>
             
            </h3>

		   </div>
		   
		   <hr>
		   
		    <div align="center">
		                    
            <a href="export_agt_ph?id=<?php echo $_SESSION['tid']; ?>&&mid=<?php echo base64_encode("404"); ?>"><button type="button" class="btn btn-flat bg-orange"><i class="fa fa-file-o"></i>&nbsp;Client Phone</button></a>

            </div>
            
             <div class="box-body">


		<form class="form-horizontal" method="post" enctype="multipart/form-data">
	
			<div class="box-body">
			    
			<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Sender ID</label>
                  <div class="col-sm-10">
                  <input name="sender_id" type="text" class="form-control" placeholder="Enter your Sender ID not more than 11 characters" maxlength="11" required>
                  </div>
                  </div>
			 
			<div class="form-group">
                <label for="" class="col-sm-2 control-label" style="color:blue;">Category</label>
                <div class="col-sm-10">
				<select name="cto"  class="form-control select2" id="my_cto" style="width:100%" required>
    				<option value="" selected="selected">Select Category...</option>
                    <option value="Client">All Super Agent</option>
                    <option value="Personalize">Personalize Message</option>
    
                    <option disabled>SELECT CLIENT</option>
    				<?php
    				$get = mysqli_query($link, "SELECT * FROM institution_data WHERE aggr_id = '$aggr_id' ORDER BY id DESC") or die (mysqli_error($link));
    				while($rows = mysqli_fetch_array($get))
    				{
    				?>
    				<option value="<?php echo $rows['official_phone']; ?>"><?php echo $rows['institution_name']; ?></option>
                    <?php } ?>
				</select>
				</div>
			</div>
			
			<span id='ShowValueFrank'></span>

			<div class="form-group">
               	<label for="" class="col-sm-2 control-label" style="color:blue;">Message Contents</label>
               	<div class="col-sm-10">
					<textarea name="message" class="form-control" id="mysmsfield" rows="4" cols="5" required></textarea>
					<div id="charNum" style="color:orange;">0 character</div>
                   </div>
            </div>
                
			</div>
				
			<div align="right">
              <div class="box-footer">
                <button name="send_sms" type="submit" class="btn bg-blue"><i class="fa fa-save">&nbsp;Send</i></button>
              </div>
			</div>

	<?php						
	if (isset($_POST['send_sms']))
	{
        $my_cat = mysqli_real_escape_string($link, $_POST['cto']);
        $sys_abb = mysqli_real_escape_string($link, $_POST['sender_id']);
	    $sms = mysqli_real_escape_string($link, $_POST['message']);
	    
	    if($my_cat == "Client"){
	        
	        echo $my_file=$_FILES['my_file']['tmp_name'];
	        
	        $open = fopen($my_file,'r');
 
            while(!feof($open)) 
            {
            	$getTextLine = fgets($open);
            	$explodeLine = explode(",",$getTextLine);
            	
                list($phone_file) = $explodeLine;
                
                $price = $fetchsys_config['fax'];
            	
            	$qry = mysqli_query($link, "INSERT INTO sms_logs1(id,company_id,c_type,sender_id,recipient,sms_content,sms_status,date_time,price) VALUES(null,'$aggr_id','Aggregator','$sys_abb','$phone_file','$sms','Pending',NOW(),'$price')");
            	
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

                $price = $fetchsys_config['fax'];
                
                $qry = mysqli_query($link, "INSERT INTO sms_logs1(id,company_id,c_type,sender_id,recipient,sms_content,sms_status,date_time,price) VALUES(null,'$aggr_id','Aggregator','$sys_abb','$str_arr[$i]','$sms','Pending',NOW(),'$price')");
            	
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

            $price = $fetchsys_config['fax'];
	        
	        $qry = mysqli_query($link, "INSERT INTO sms_logs1(id,company_id,c_type,sender_id,recipient,sms_content,sms_status,date_time,price) VALUES(null,'$aggr_id','Aggregator','$sys_abb','$my_cat','$sms','Pending',NOW(),'$price')");
            	
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