<div class="box">
	       <div class="box-body">
			<div class="panel panel-success">
            <div class="panel-heading bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">
            <h3 class="panel-title"> <a href="mywallet.php?id=<?php echo $_SESSION['tid']; ?>&&mid=NDA0&&tab=tab_1"><button type="button" class="btn btn-flat bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>"><i class="fa fa-mail-reply-all"></i>&nbsp;Back</button></a> <i class="fa fa-user"></i>  Add Transfer Recipient</h3>
            </div>

             <div class="box-body">
 <?php
if(isset($_POST['save']))
{
  include("../config/restful_apicalls.php");

  $result = array();
  //transferrecipient
  $account_number =  mysqli_real_escape_string($link, $_POST['acct_no']);
  $bank_code =  mysqli_real_escape_string($link, $_POST['bank_code']);

  $systemset = mysqli_query($link, "SELECT * FROM systemset");
  $row1 = mysqli_fetch_object($systemset);
  $seckey = $row1->secret_key;

  if($inip_route == "Wallet Africa"){

    $search_restapi = mysqli_query($link, "SELECT * FROM restful_apisetup WHERE api_name = 'transferrecipient'");
    $fetch_restapi = mysqli_fetch_object($search_restapi);
    $api_url = $fetch_restapi->api_url;
      
    // Pass the plan's name, interval and amount
    $postdata =  array(
      'account_number'  => $account_number,
      'account_bank'    => $bank_code,
      'seckey'          => $seckey
    );
    
    $make_call = callAPI('POST', $api_url, json_encode($postdata));
    $result = json_decode($make_call, true);
      
    if($result['status'] == "success"){

        //Get the Recipient Id from Rav API
        $recipient_id = $result['data']['id'];
        //Get the Bank Name from Rav API
        $bank_name = $result['data']['bank_name'];
        //Get the Recipient Full Name From Rav API
        $fullname = $result['data']['fullname'];

        $date_now = date("Y-m-d h:i:s");

        $insert = mysqli_query($link, "INSERT INTO transfer_recipient VALUES(null,'$institution_id','$recipient_id','$fullname','$account_number','$bank_code','$bank_name','$date_now','$iuid','$isbranchid')") or die ("Error: " . mysqli_error($link));
        echo "<script>alert('Recipient Added Successfully!'); </script>";

      }else{
        $message = $result['message'];
        echo "<script>alert('$message \\nPlease try another one'); </script>";
      }

  }
  elseif($inip_route == "ProvidusBank" || $inip_route == "AccessBank" || $inip_route == "SterlingBank"){

    $search_restapi = mysqli_query($link, "SELECT * FROM restful_apisetup WHERE api_name = 'providus_endpoint'");
  	$fetch_restapi = mysqli_fetch_object($search_restapi);
  	$api_url = $fetch_restapi->api_url;
     
    $client = new SoapClient($api_url);

    $param = array(
        'account_number'=>$account_number,
        'bank_code'=>$bank_code,
        'username'=>$row1->providusUName,
        'password'=>$row1->$providusPass
      );

    $response = $client->GetNIPAccount($param);
    
    $process = json_decode(json_encode($response), true);

    $processReturn = $process['return'];
        
    $process2 = json_decode($processReturn, true);

    if($process2['responseCode'] == "00"){

      //Get the Recipient Id from Rav API
      $recipient_id = substr((uniqid(rand(),1)),3,6);

      //Fetch Bank Name
      $search_bankname = mysqli_query($link, "SELECT * FROM bank_list WHERE bankcode = '$bank_code'");
      $fetch_bankname = mysqli_fetch_array($search_bankname);
      $mybank_name = $fetch_bankname['bankname'];

      //Get the Bank Name from Rav API
      $bank_name = $result['data']['bank_name'];
      //Get the Recipient Full Name From Rav API
      $fullname = $process2['accountName'];

      $date_now = date("Y-m-d h:i:s");

      $insert = mysqli_query($link, "INSERT INTO transfer_recipient VALUES(null,'$institution_id','$recipient_id','$fullname','$account_number','$bank_code','$bank_name','$date_now','$iuid','$isbranchid')") or die ("Error: " . mysqli_error($link));
      echo "<script>alert('Recipient Added Successfully!'); </script>";

  	}
  	else{
      $message = $process2['responseMessage'];
      echo "<script>alert('$message \\nPlease try another one'); </script>";
  		echo "<b style='font-size:18px;'>".$message."</b>";
  	}

  }
  else{



  }
  
}
?>           
		    <form class="form-horizontal" method="post" enctype="multipart/form-data">

             <div class="box-body">
                
                 <?php
                  if($inip_route == "Wallet Africa")
                  {
                 ?>

                <div class="form-group">
                    <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Country</label>
                    <div class="col-sm-6">
                        <select name="country"  class="form-control select2" id="country" onchange="loadbank();" required>
                          <option value="" selected>Select Country</option>
                          <option value="NG">Nigeria</option>
                          <!--<option value="GH">Ghana</option>
                          <option value="KE">Kenya</option>
                          <option value="UG">Uganda </option>
                          <option value="TZ">Tanzania</option>-->
                        </select>
                    </div>
                    <label for="" class="col-sm-3 control-label"></label>
                </div>
                
                <div class="form-group">
                    <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Account Number</label>
                    <div class="col-sm-6">
                        <input name="acct_no" type="text" id="account_number" onkeydown="loadaccount();" class="form-control" placeholder="Account Number" required>
                    </div>
                    <label for="" class="col-sm-3 control-label"></label>
                </div>
                
                <div class="form-group">
                    <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Bank Name</label>
                    <div class="col-sm-6">
                        <div id="bank_list"></div>
                    </div>
                    <label for="" class="col-sm-3 control-label"></label>
                </div>

                <?php
                  }
                  elseif($inip_route == "ProvidusBank" || $inip_route == "AccessBank" || $inip_route == "SterlingBank"){
                ?>

                  <div class="form-group">
                    <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Account Number</label>
                    <div class="col-sm-6">
                        <input name="acct_no" type="text" id="accountNo" onkeydown="fetchbanklist();" class="form-control" placeholder="Bank Account Number" required>
                    </div>
                    <label for="" class="col-sm-3 control-label"></label>
                  </div>

                  <div class="form-group">
                    <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Recipient Bank</label>
                    <div class="col-sm-6">
                        <select name="bank_code"  class="form-control select2" id="bankCode" onchange="fetchbanklist();" required>
                          <option value="" selected>Select Bank</option>
                          <?php
                            // CALL TO GET NIP BANK
                            try
                            {
                                $search_restapi1 = mysqli_query($link, "SELECT * FROM restful_apisetup WHERE api_name = 'providus_endpoint'");
                                $fetch_restapi1 = mysqli_fetch_object($search_restapi1);
                                $api_url1 = $fetch_restapi1->api_url;
                                
                                $client = new SoapClient($api_url1);

                                $response = $client->GetNIPBanks();
                            
                                $process = json_decode(json_encode($response), true);
        
                                $processReturn = $process['return'];
                                
                                $process2 = json_decode($processReturn, true);
                                
                                $processReturn2 = $process2['banks'];
                                
                                $i = 0;
                                
                                foreach($processReturn2 as $key){
                                    
                                    echo "<option value=".$processReturn2[$i]['bankCode'].">".$processReturn2[$i]['bankName']." - ".$processReturn2[$i]['bankCode']."</option>";
                                    $i++;
                                    
                                }
                            
                            }
                            catch( Exception $e )
                            {
                                // You should not be here anymore
                                echo $e->getMessage();
                            }
                            //END OF GET NIP BANK
                          ?>
                        </select>
                    </div>
                    <label for="" class="col-sm-3 control-label"></label>
                  </div>

                  <?php
                  }
                  else{

                    echo "<div align='center' style='font-size:20px;'>Kindly contact the Administrator to Activate this features if needed!!</div>";

                  }
                  ?>
                
                <div class="form-group">
                    <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Account Holder</label>
                    <div class="col-sm-6">
                        <div id="act_numb"></div>
                    </div>
                    <label for="" class="col-sm-3 control-label"></label>
                </div>
			
			 </div>
			 
			 <div class="form-group" align="right">
               <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;"></label>
               <div class="col-sm-6">
                    <button name="save" type="submit" class="btn bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>"><i class="fa fa-plus">&nbsp;Add Recipient</i></button>
               </div>
               <label for="" class="col-sm-3 control-label"></label>
            </div>
			  
			 </form> 

</div>	
</div>	
</div>
</div>