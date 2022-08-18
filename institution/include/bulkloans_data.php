<div class="box">
        
	       <div class="box-body">
			<div class="panel panel-success">
            <div class="panel-heading bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">
            <h3 class="panel-title"><i class="fa fa-briefcase"></i>&nbsp;Upload Bulk Loans</h3>
            </div>
             <div class="box-body">

			 <form class="form-horizontal" method="post" enctype="multipart/form-data">
             
<div class="box-body">

  <div class="form-group">
                <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Loan Product</label>
         <div class="col-sm-10">
                <select name="lproduct" class="select2" id="loan_products1" style="width: 100%;" required>
        <option value="" selected="selected">--Select Loan Product--</option>
                  <?php
          $getin = mysqli_query($link, "SELECT * FROM loan_product WHERE merchantid = '$institution_id' order by id") or die (mysqli_error($link));
          while($row = mysqli_fetch_array($getin))
          {
          echo '<option value="'.$row['id'].'">'.$row['pname'].' - '.'(Interest Rate: '. $row['interest'].'% per tenor)'.'</option>';
          }
          ?>
                </select>
              </div>
        </div>
        
        <span id='ShowValueFrank'></span>
        <span id='ShowValueFrank'></span>
    
  <div class="form-group">
           <label for="" class="col-sm-2 control-label" style="color: <?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Import Files:</label>
  <div class="col-sm-10">
        <span class="fa fa-cloud-upload"></span>
           <span class="ks-text">Choose file</span>
           <input type="file" name="file" accept=".csv" class="bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>" required>
  </div>
  </div>
                        
  <hr>
  <div class="form-group">
      <label for="" class="col-sm-2 control-label" style="color: <?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">NOTE:</label>
      <div class="col-sm-10">
            <p style="color:<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>">
        <i>Kindly download the <a href="../sample/loan_sample.csv"> <b class="btn bg-blue"><i class="fa fa-download"></i> sample file</b></a> to upload the details once.</i></p>
      </div>
  </div>
  
  <hr>
  
    <div align="right">
       <div class="box-footer">
       <button class="btn bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?> ks-btn-file" type="submit" name="Import"><span class="fa fa-cloud-upload"></span> Import All Loans</button> 
       </div>
    </div>  

</div>  

<?php
if(isset($_POST["Import"])){

    
    if($_FILES['file']['name']){
        
        $filename = explode('.', $_FILES['file']['name']);
        
        if($filename[1] == 'csv'){
            
            $handle = fopen($_FILES['file']['tmp_name'], "r");
            fgetcsv($handle,1000,","); // pop the headers
            while($data = fgetcsv($handle,1000,",")){
    
                $search_memset = mysqli_query($link, "SELECT * FROM member_settings WHERE companyid = '$institution_id'");
                $fetch_memset = mysqli_fetch_array($search_memset);
                //$sys_abb = $get_sys['abb'];
                $lid = 'LID-'.rand(1000000,9999999);
                $lproduct =  mysqli_real_escape_string($link, $_POST['lproduct']);
    
                $search_interest = mysqli_query($link, "SELECT * FROM loan_product WHERE id = '$lproduct'");
                $get_interest = mysqli_fetch_object($search_interest);
                $max_duration  = $get_interest->duration;
                $interest = $get_interest->interest/100;
                $tenor = $get_interest->tenor;
                $pschedule = mysqli_real_escape_string($link, $_POST['pschedule']);
                $upstatus = "Pending";
                $p_status = "UNPAID";
    
                //account number
                $baccount = $emapData[1];
                $getin = mysqli_query($link, "SELECT * FROM borrowers WHERE branchid = '$institution_id' AND account = '$baccount' order by id") or die (mysqli_error($link));
                $fetch_getin = mysqli_fetch_array($getin);
                $borrower = $fetch_getin['id'];
                
                //$insert = mysqli_query($link, "INSERT INTO loan_info VALUES(null,'$lid','$lproduct','$loantype','$borrower','$baccount','$income_amt','$salary_date','$employer','$bacinfo','$amount','','','$agent','$unumber','$gname','$gphone','$g_address','','','$g_rela','$location','Pending','','','$interest','$amount_topay','$amount_topay','$teller','$lreasons','$upstatus','$p_status','$institution_id')") or die ("Error: " . mysqli_error($link));
                $sql = "INSERT INTO loan_info(id,lid,lproduct,loantype,borrower,baccount,income,salary_date,employer,descs,amount,disbursed_by,date_release,agent,unumber,g_name,g_phone,g_address,g_dob,g_bname,rela,g_image,status,remarks,pay_date,interest_rate,amount_topay,balance,teller,remark,upstatus,p_status,branchid,vendorid,dept,sbranchid,mandate_id,request_id,funcing_acct,funding_bankcode,remita_rrr,trans_ref,mandate_status,direct_debit_status,request_ts) VALUES(null,'$lid','$lproduct','$emapData[0]','$borrower','$emapData[1]','$emapData[2]','$emapData[3]','$emapData[4]','$emapData[5]','$emapData[6]','','','$iname','$emapData[7]','$emapData[8]','$emapData[9]','$emapData[10]','','','$emapData[11]','','Pending','','','$interest','$emapData[12]','$emapData[12]','$iname','$emapData[13]','$upstatus','$p_status','$institution_id','','$idept_settings','','','','','','','','','','')";
                $sql2 = "INSERT INTO payment_schedule(id,lid,tid,term,schedule,branchid,vendorid,lproduct) VALUES(null,'$lid','$emapData[1]','$pschedule','$tenor','$institution_id','','$lproduct')";
               
               //we are using mysql_query function. it returns a resource on true else False on error
                $result = mysqli_query($link,$sql);
                $result2 = mysqli_query($link,$sql2);
                
                //include('../cron/send_general_sms.php');
                if(!($result && $result2))
                {
                echo "<script type=\"text/javascript\">
                  alert(\"Invalid File:Please Upload CSV File.\");
                    </script>";
                }
     
            }
            fclose($handle);
            //throws a message if data successfully imported to mysql database from excel file
            echo "<script type=\"text/javascript\">
                alert(\"Loans Uploaded successfully.\");
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