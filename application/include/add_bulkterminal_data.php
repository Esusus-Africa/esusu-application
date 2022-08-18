<div class="box">
	       <div class="box-body">
			<div class="panel panel-success">
            <div class="panel-heading bg-blue">
            <h3 class="panel-title"><i class="fa fa-link"></i> Upload Terminal</h3>
            </div>

             <div class="box-body">
           
			 <form class="form-horizontal" method="post" enctype="multipart/form-data">

<?php
if(isset($_POST["addTerminal"])){
    
    if($_FILES['file']['name']){
        
        $filename = explode('.', $_FILES['file']['name']);
        
        if($filename[1] == 'csv'){
            
            $handle = fopen($_FILES['file']['tmp_name'], "r");
            fgetcsv($handle,1000,","); // pop the headers
            while($data = fgetcsv($handle,1000,",")){
                
                $empty_filesop = array_filter(array_map('trim', $data));
                
                if(!empty($empty_filesop)){

                    $terminalid = mysqli_real_escape_string($link, $data[0]);
                    $tissuer = mysqli_real_escape_string($link, $data[1]);
                    $mid = mysqli_real_escape_string($link, $data[2]);
                    $channel = strtoupper(mysqli_real_escape_string($link, $data[3]));
                    $tmodelcode = mysqli_real_escape_string($link, $data[4]);
                    $allowsettlement = mysqli_real_escape_string($link, $data[5]);
                    $settlementtype = strtolower(mysqli_real_escape_string($link, $data[6]));
                    $commtype = ucwords(mysqli_real_escape_string($link, $data[7]));
                    $charges = mysqli_real_escape_string($link, $data[8]);
                    $commrate = mysqli_real_escape_string($link, $data[9]);
                    $activationfee = mysqli_real_escape_string($link, $data[10]);
                    $activationcomm = mysqli_real_escape_string($link, $data[11]);
                    $ptsp = mysqli_real_escape_string($link, $data[12]);
                    $visibility = mysqli_real_escape_string($link, $data[13]);
                    $terminal_serial = mysqli_real_escape_string($link, $data[14]);
                    $stampduty = mysqli_real_escape_string($link, $data[15]);
                    $poolaccount = mysqli_real_escape_string($link, $data[16]);
                    $trace_id = ($channel == "USSD") ? date("dy").mt_rand(100000000,999999999) : "";
                    $currenctdate = date("Y-m-d h:i:s");

                    $sql = "INSERT INTO terminal_reg(id,terminal_id,terminal_issurer,terminal_owner_code,channel,initiatedBy,merchant_id,merchant_name,merchant_email,merchant_phone_no,terminal_model_code,sms_alert,bankcode,bank_account_no,bankname,acctName,slip_header,slip_footer,pending_balance,settled_balance,allow_settlement,settlmentType,total_transaction_count,terminal_status,dateCreated,dateUpdated,assignedBy,createdBy,ctype,charges,charge_comm,commission,stampduty_bound,tidoperator,branchid,activation_fee,activation_comm,trace_id,ptsp,visibility,terminal_serial,custom_logo,custom_merchantname,custom_address,custom_phone,custom_appname,custom_appversion,custom_appurl,custom_appphone,poolAccount) VALUES(null,'$terminalid','$tissuer','$mid','$channel','','','','','','$tmodelcode','','','','','','','','0.0','0.0','$allowsettlement','$settlementtype','0','Available','$currenctdate','$currenctdate','','$uid','$commtype','$charges','0','$commrate','$stampduty','','','$activationfee','$activationcomm','$trace_id','$ptsp','$visibility','$terminal_serial','','','','','','','','','$poolaccount')";
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
					alert(\"Terminal Uploaded Successfully!.\");
				</script>";

        }

    }

}
?>

             <div class="form-group">
                <label for="" class="col-sm-2 control-label" style="color: blue;">Import Terminal:</label>
                <div class="col-sm-7">
                    <span class="fa fa-cloud-upload"></span>
                <span class="ks-text">Choose file</span>
                <input type="file" name="file" accept=".csv" required>
                </div>
                <label for="" class="col-sm-3 control-label"></label>
            </div>
            
            <div class="form-group">
                <label for="" class="col-sm-2 control-label" style="color: blue;">NOTE:</label>
                <div class="col-sm-7">
                    <span style="color:blue;">(1)</span> <i>Download the <a href="../sample/bulk_terminal.csv"> <b class="btn bg-blue"><i class="fa fa-download"></i> sample file</b></a> to upload terminal in bulk.</i>
                </div>
                <label for="" class="col-sm-3 control-label"></label>
            </div>

			<div class="form-group" align="right">
                <label for="" class="col-sm-3 control-label"></label>
                <div class="col-sm-6">
                	<button name="addTerminal" type="submit" class="btn bg-blue"><span class="fa fa-cloud-upload"></span> Import Data</i></button>
                </div>
                <label for="" class="col-sm-3 control-label"></label>
            </div>
			  
		</form> 

</div>	
</div>	
</div>
</div>