<div class="box">
	       <div class="box-body">
			<div class="panel panel-success">
            <div class="panel-heading bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">
            <h3 class="panel-title"> <a href="mywallet.php?id=<?php echo $_SESSION['tid']; ?>&&mid=NDA0"><button type="button" class="btn btn-flat bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>"><i class="fa fa-mail-reply-all"></i>&nbsp;Back</button></a> <i class="fa fa-user"></i>  Add Transfer Recipient</h3>
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

      $insert = mysqli_query($link, "INSERT INTO transfer_recipient VALUES(null,'$vendorid','$recipient_id','$fullname','$account_number','$bank_code','$bank_name',NOW())") or die ("Error: " . mysqli_error($link));
      echo "<script>alert('Transfer Recipient Added Successfully!'); </script>";

    }else{
      $message = $result['message'];
      echo "<script>alert('$message \\nPlease try another one'); </script>";
    }
}
?>           
		    <form class="form-horizontal" method="post" enctype="multipart/form-data">

             <div class="box-body">
            
            <div class="form-group">
                      <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Country</label>
                      <div class="col-sm-10">
            <select name="country"  class="form-control select2" id="country" onchange="loadbank();" required>
              <option selected>Please Select Country</option>
              <option value="NG">Nigeria</option>
              <option value="GH">Ghana</option>
              <option value="KE">Kenya</option>
              <option value="UG">Uganda </option>
              <option value="TZ">Tanzania</option>
            </select>                 
            </div>
            </div>
				  
			<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">Account Number</label>
                  <div class="col-sm-10">
                  <input name="acct_no" type="text" id="account_number" onkeydown="loadaccount();" class="form-control" placeholder="Account Number" required>
                  
                  </div>
                  </div>
                  
            <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Bank Code</label>
                  <div class="col-sm-10">
                    <div id="bank_list"></div>
        </div>
        </div>
        
        <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Account Holder</label></label>
                  <div class="col-sm-10">
                    <div id="act_numb"></div>
        </div>
        </div>
			
			 </div>
			 
			  <div align="right">
              <div class="box-footer">
                				<button name="save" type="submit" class="btn bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?> btn-flat"><i class="fa fa-plus">&nbsp;Add Recipient</i></button>

              </div>
			  </div>
			  
			 </form> 

</div>	
</div>	
</div>
</div>