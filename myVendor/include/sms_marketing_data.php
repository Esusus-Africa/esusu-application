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
            echo "<span id='wallet_balance'>".$vcurrency.number_format($vwallet_balance,2,'.',',')."</span>";
            ?> 
            </strong>
            &nbsp; <b style="color: black;"> == </b>
            <strong class="alert bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>">
            <?php
            
            echo "<span id='smsunit_balance'>".number_format(($vwallet_balance/$fetchsys_config['fax']),2,'.',',')." Unit(s) of sms </span>";
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
            
             <div class="box-body">

		<form class="form-horizontal" method="post" enctype="multipart/form-data">
	
			<div class="box-body">
            
            <div class="form-group">
                <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Sender ID</label>
                <div class="col-sm-6">
                    <input name="sender_id" type="text" class="form-control" value="<?php echo $mvsenderid; ?>" placeholder="Enter your Sender ID not more than 11 characters" maxlength="11" required>
                </div>
                <label for="" class="col-sm-3 control-label"></label>
            </div>

            <div class="form-group">
                <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Category</label>
                <div class="col-sm-6">
                <select name="cto"  class="form-control select2" id="my_cto" required style="width:100%">
                    <option value="" selected="selected">Select Category...</option>
                    <option value="Personalize">Personalize Message</option>
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
	    
	    if($my_cat == "Personalize"){
	        
	        $phone_nos = mysqli_real_escape_string($link, $_POST['phone_nos']);
	        $str_arr = preg_split("/\,/", $phone_nos);
	        $length = count($str_arr);
	        
	        for($i = 0; $i < $length; $i++){
                
                $qry = mysqli_query($link, "INSERT INTO sms_logs1(id,company_id,c_type,sender_id,recipient,sms_content,sms_status,date_time) VALUES(null,'$vendorid','vendor','$sys_abb','$str_arr[$i]','$sms','Pending',NOW())");
            	
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
	        
	        $qry = mysqli_query($link, "INSERT INTO sms_logs1(id,company_id,c_type,sender_id,recipient,sms_content,sms_status,date_time) VALUES(null,'$vendorid','vendor','$sys_abb','$my_cat','$sms','Pending',NOW())");
            	
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