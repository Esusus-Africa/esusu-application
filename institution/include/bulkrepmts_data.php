<div class="box">
        
	       <div class="box-body">
			<div class="panel panel-success">
            <div class="panel-heading bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">
            <h3 class="panel-title"><i class="fa fa-briefcase"></i>&nbsp;Upload Bulk Loans Repayments</h3>
            </div>
             <div class="box-body">

			 <form class="form-horizontal" method="post" enctype="multipart/form-data">
             
<div class="box-body">
    
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
    <i>Kindly download the <a href="../sample/loanrepmt_sample.csv"> <b class="btn bg-blue"><i class="fa fa-download"></i> sample file</b></a> to upload the details once.</i></p>
  </div>
  </div>
  
  <hr>
  
    <div align="right">
       <div class="box-footer">
       <button class="btn bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?> ks-btn-file" type="submit" name="Import"><span class="fa fa-cloud-upload"></span> Import All Repayment</button> 
       </div>
    </div>  

</div>  

<?php
if(isset($_POST["Import"])){

  function myreference($limit)
  {
    return substr(base_convert(sha1(uniqid(mt_rand())), 16, 36), 0, $limit);
  }

  $search_memset = mysqli_query($link, "SELECT * FROM member_settings WHERE companyid = '$institution_id'");
  $fetch_memset = mysqli_fetch_array($search_memset);
  //$sys_abb = $get_sys['abb'];\
  $sysabb = $fetch_memset['sender_id'];

  $query = mysqli_query($link, "SELECT * FROM systemset") or die (mysqli_error($link));
  $r = mysqli_fetch_object($query);
            //$sys_abb = $r->abb;
  $sys_email = $r->email;

    echo $filename=$_FILES["file"]["tmp_name"];
    $allowed_filetypes = array('csv');
    if(!in_array(end(explode(".", $_FILES['file']['name'])), $allowed_filetypes))
        {
        echo "<script type=\"text/javascript\">
            alert(\"The file you attempted to upload is not allowed.\");
          </script>";
        }    
    elseif($_FILES["file"]["size"] > 0)
     {
        $file = fopen($filename, "r");
           while (($emapData = fgetcsv($file, 10000, ",")) !== FALSE)
           {

            //account number
            $account_no = $emapData[1];
            $getin = mysqli_query($link, "SELECT * FROM borrowers WHERE branchid = '$institution_id' AND account = '$baccount' order by id") or die (mysqli_error($link));
            $fetch_getin = mysqli_fetch_array($getin);
            $customer = $fetch_getin['fname'].'&nbsp;'.$fetch_getin['lname'];
            $uname = $fetch_getin['username'];

            $tid = $_SESSION['tid'];
            $lid = $emapData[0];
            $amount_to_pay = $emapData[2];
            $loan_balance = $emapData[3];
            $refid = myreference(10);

            $search4 = mysqli_query($link, "SELECT * FROM pay_schedule WHERE lid = '$lid' AND status = 'UNPAID' ORDER BY id ASC") or die (mysqli_error($link));
            $get_search4 = mysqli_fetch_array($search4);
            $get_id = $get_search4['get_id'];
            $date_time = date("Y-m-d");
            $original_bal = $get_search4['balance'];
            $expected_pay = $get_search4['payment'];

            if($loan_balance == "0" && $amount_to_pay == $expected_pay)
            {

              $sql = "UPDATE loan_info SET balance = '$emapData[3]', p_status = 'PAID' WHERE lid = '$emapData[0]'";

              $sql2 = "INSERT INTO payments(id,tid,lid,refid,account_no,customer,loan_bal,pay_date,amount_to_pay,remarks,branchid) VALUES(null,'$tid','$lid','$refid','$emapData[1]','$customer','$emapData[3]','$emapData[4]','$emapData[2]','paid','$institution_id')";

              $sql3 = "UPDATE pay_schedule SET status = 'PAID' WHERE id = '$get_id'";
             
             //we are using mysql_query function. it returns a resource on true else False on error
              $result = mysqli_query($link,$sql);
              $result2 = mysqli_query($link,$sql2);
              $result3 = mysqli_query($link,$sql3);
              
              //include('../cron/send_general_sms.php');
              if(!($result && $result2 && $result3))
              {
              echo "<script type=\"text/javascript\">
                alert(\"Invalid File:Please Upload CSV File.\");
                  </script>";
              }
            }
            elseif($loan_balance == "0" && $amount_to_pay < $expected_pay)
            {
              $new_obal = $expected_pay - $amount_to_pay;

              $sql = "UPDATE loan_info SET balance = '$emapData[3]', p_status = 'PART-PAID' WHERE lid = '$emapData[0]'";

              $sql2 = "INSERT INTO payments(id,tid,lid,refid,account_no,customer,loan_bal,pay_date,amount_to_pay,remarks,branchid) VALUES(null,'$tid','$lid','$refid','$emapData[1]','$customer','$emapData[3]','$emapData[4]','$emapData[2]','paid','$institution_id')";

              $sql3 = "UPDATE pay_schedule SET payment = '$new_obal' WHERE id = '$get_id'";

              $sql4 = "INSERT INTO pay_schedule(id,lid,get_id,tid,schedule,balance,payment,status,branchid) VALUES(null,'$lid','$get_id','$emapData[1]','$date_time','$emapData[3]','-$emapData[2]','PAID','$institution_id')";

              //we are using mysql_query function. it returns a resource on true else False on error
              $result = mysqli_query($link,$sql);
              $result2 = mysqli_query($link,$sql2);
              $result3 = mysqli_query($link,$sql3);
              $result4 = mysqli_query($link,$sql4);
              
              //include('../cron/send_general_sms.php');
              if(!($result && $result2 && $result3 && $result4))
              {
              echo "<script type=\"text/javascript\">
                alert(\"Invalid File:Please Upload CSV File.\");
                  </script>";
              }

            }
            elseif($loan_balance != "0" && $amount_to_pay == $expected_pay)
            {

              $sql = "UPDATE loan_info SET balance = '$emapData[3]', p_status = 'PART-PAID' WHERE lid = '$emapData[0]'";

              $sql2 = "INSERT INTO payments(id,tid,lid,refid,account_no,customer,loan_bal,pay_date,amount_to_pay,remarks,branchid) VALUES(null,'$tid','$lid','$refid','$emapData[1]','$customer','$emapData[3]','$emapData[4]','$emapData[2]','paid','$institution_id')";

              $sql3 = "UPDATE pay_schedule SET status = 'PAID' WHERE id = '$get_id'";
             
             //we are using mysql_query function. it returns a resource on true else False on error
              $result = mysqli_query($link,$sql);
              $result2 = mysqli_query($link,$sql2);
              $result3 = mysqli_query($link,$sql3);

              //include('../cron/send_general_sms.php');
              if(!($result && $result2 && $result3))
              {
              echo "<script type=\"text/javascript\">
                alert(\"Invalid File:Please Upload CSV File.\");
                  </script>";
              }

            }
            elseif($loan_balance != "0" && $amount_to_pay < $expected_pay)
            {
              $new_pay = $expected_pay - $amount_to_pay;

              $sql = "UPDATE loan_info SET balance = '$emapData[3]', p_status = 'PART-PAID' WHERE lid = '$emapData[0]'";

              $sql2 = "INSERT INTO payments(id,tid,lid,refid,account_no,customer,loan_bal,pay_date,amount_to_pay,remarks,branchid) VALUES(null,'$tid','$lid','$refid','$emapData[1]','$customer','$emapData[3]','$emapData[4]','$emapData[2]','paid','$institution_id')";

              $sql3 = "UPDATE pay_schedule SET payment = '$new_pay' WHERE id = '$get_id'";

              $sql4 = "INSERT INTO pay_schedule(id,lid,get_id,tid,schedule,balance,payment,status,branchid) VALUES(null,'$lid','$get_id','$emapData[1]','$date_time','$emapData[3]','-$emapData[2]','PAID','$institution_id')";

              //we are using mysql_query function. it returns a resource on true else False on error
              $result = mysqli_query($link,$sql);
              $result2 = mysqli_query($link,$sql2);
              $result3 = mysqli_query($link,$sql3);
              $result4 = mysqli_query($link,$sql4);
              
              //include('../cron/send_general_sms.php');
              if(!($result && $result2 && $result3 && $result4))
              {
              echo "<script type=\"text/javascript\">
                alert(\"Invalid File:Please Upload CSV File.\");
                  </script>";
              }

            }
            elseif($loan_balance != "0" && $amount_to_pay > $expected_pay)
            {

              $find_payschedule = mysqli_query($link, "SELECT * FROM pay_schedule WHERE payment = '$expected_pay' AND status = 'UNPAID' AND branchid = '$institution_id'");
              $nums = mysqli_num_rows($find_payschedule);
              
              $total_selected_amt = $expected_pay * $nums;
              
              $amt_to_spreadover = $amount_to_pay / $nums;
              
              $final_bal = $total_selected_amt - $amount_to_pay;
              
              $sql2 = "INSERT INTO payments(id,tid,lid,refid,account_no,customer,loan_bal,pay_date,amount_to_pay,remarks,branchid) VALUES(null,'$tid','$lid','$refid','$emapData[1]','$customer','$emapData[3]','$emapData[4]','$emapData[2]','paid','$institution_id')";

              //we are using mysql_query function. it returns a resource on true else False on error
              $result2 = mysqli_query($link,$sql2);

              while($update_amt = mysqli_fetch_array($find_payschedule)){

                $new_amt_shared = ($update_amt['payment'] - $amt_to_spreadover);

                if($amt_to_spreadover == $expected_pay){

                  $sql = "UPDATE loan_info SET balance = '$emapData[3]', p_status = 'PAID' WHERE lid = '$emapData[0]'";

                  $sql3 = "UPDATE pay_schedule SET status = 'PAID' WHERE payment = '$amt_to_spreadover' AND status = 'UNPAID' AND branchid = '$institution_id'";

                  //we are using mysql_query function. it returns a resource on true else False on error
                  $result = mysqli_query($link,$sql);
                  $result3 = mysqli_query($link,$sql3);

                }
                else{
                    
                    $sql = "UPDATE loan_info SET balance = '$emapData[3]', p_status = 'PART-PAID' WHERE lid = '$emapData[0]'";

                    $sql3 = "UPDATE pay_schedule SET payment = '$new_amt_shared' WHERE payment = '$expected_pay' AND status = 'UNPAID' AND branchid = '$institution_id'";

                    //we are using mysql_query function. it returns a resource on true else False on error
                    $result = mysqli_query($link,$sql);
                    $result3 = mysqli_query($link,$sql3);
                    
                }

              }
              if($total_selected_amt > $amount_to_pay){

                $sql4 = "INSERT INTO pay_schedule(id,lid,get_id,tid,schedule,balance,payment,status,branchid) VALUES(null,'$lid','$get_id','$emapData[1]','$date_time','-$emapData[3]','$emapData[2]','PAID','$institution_id')";

                //we are using mysql_query function. it returns a resource on true else False on error
                $result4 = mysqli_query($link,$sql4);

              }

            }
 
           }
           fclose($file);
           //throws a message if data successfully imported to mysql database from excel file
           echo "<script type=\"text/javascript\">
            alert(\"Loans Uploaded successfully.\");
          </script>";
     }
  }  
?>    
           </form>
			  

           
</div>	
</div>
</div>
</div>