<div class="box">
	       <div class="box-body">
			<div class="panel panel-success">
            <div class="panel-heading bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">
            <h3 class="panel-title"><i class="fa fa-money"></i> Bulk Savings Upload Form
            <button type="button" class="btn btn-flat bg-white" align="left">&nbsp;<b style="color: black;">Wallet Balance:</b>&nbsp;
			<strong class="alert bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>">
			<?php
			echo "<span id='wallet_balance'>".$icurrency.number_format($iassigned_walletbal,2,'.',',')."</span>";
			?>
			</strong>
			  </button>
            </h3>
            </div>
             <div class="box-body">
            
<form class="form-horizontal" method="post" enctype="multipart/form-data">

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

                $account = mysqli_real_escape_string($link, $data[0]);
                $ttype = ucwords(mysqli_real_escape_string($link, $data[1]));
                $amount = preg_replace('/[^0-9.]/', '', mysqli_real_escape_string($link, $data[2]));
                $remark = mysqli_real_escape_string($link, $data[3]);
                $final_charges =  mysqli_real_escape_string($link, $data[4]);
                $ptype = mysqli_real_escape_string($link, $data[5]);
                $balToImpact = strtolower(mysqli_real_escape_string($link, $data[6]));
                $totalamount = $amount + $final_charges;
                $currenctdate = date("Y-m-d h:i:s");
                //REFERENCE ID GENERATOR
                $real_txid = 'TXID-'.mt_rand(10000,99999).time().uniqid();
                $search_txid = mysqli_query($link, "SELECT * FROM transaction WHERE txid = '$real_txid'");
                $txid = (mysqli_num_rows($search_txid) == 0) ? $real_txid : 'TXID-'.date("dyi").time().uniqid();
                
                $maintenance_row = mysqli_num_rows($isearch_maintenance_model);
                $t_perc = $ifetch_maintenance_model['t_charges'];
                
                //Data Parser (array size = 13)
	            $mydata = $txid."|".$account."|".$ttype."|".$amount."|".$remark."|".$final_charges."|".$totalamount."|".$maintenance_row."|".$t_perc."|".$sysabb."|".$institution_id."|".$ptype."|".$icurrency."|".$balToImpact;

                $sql = "INSERT INTO otp_confirmation(id,userid,otp_code,data,status,datetime) VALUES(null,'$iuid','none','$mydata','batchSavings','$currenctdate')";
                $result = mysqli_query($link,$sql);

                if(!$result)
        		{
        			echo "<script type=\"text/javascript\">
        				alert(\"Invalid File:Please Upload CSV File.\");
        			    </script>".mysqli_error($link);
        		}
            		
            }
            fclose($handle);        	
            echo "<script type=\"text/javascript\">
					alert(\"Savings Uploaded Successfully...Wait few minutes for system to process the transaction.\");
				</script>";

        }

    }

}
?>

             <div class="box-body">
			
             <div class="form-group">
                <label for="" class="col-sm-2 control-label" style="color: <?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Import Savings:</label>
                <div class="col-sm-10">
                    <span class="fa fa-cloud-upload"></span>
                <span class="ks-text">Choose file</span>
                <input type="file" name="file" accept=".csv" required>
                </div>
            </div>
            
            <div class="form-group">
                <label for="" class="col-sm-2 control-label" style="color: <?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">NOTE:</label>
                <div class="col-sm-10">
                    <span style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">(1)</span> <i>Download the <a href="../sample/bulk_savings.csv"> <b class="btn bg-blue"><i class="fa fa-download"></i> sample file</b></a> to upload the savings (both deposit & withdrawal) once.</i>
                </div>
            </div>
            
            <div align="right">
            <div class="box-footer">
                <button class="btn bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?> ks-btn-file" type="submit" name="Import"><span class="fa fa-cloud-upload"></span> Import Data</button> 
            </div>
            </div>  

            </div>	
			
			 </form> 


</div>	
</div>	
</div>
</div>