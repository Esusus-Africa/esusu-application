   <?php 
   if(isset($_POST['my_pbid']))
   {
	 include("../config/session.php");
	 //$link = mysqli_connect('localhost','root','root','loan2','8888') or die('Unable to Connect to Database');


	  $billp_settings = mysqli_query($link, "SELECT * FROM billpayment WHERE companyid = '$bbranchid' AND status = 'Active'");
	  $row_bp = mysqli_fetch_object($billp_settings);

	  $url = 'https://estoresms.com/bill_payment_processing/v/1/'; //API Url (Do not change)
	  $token = $row_bp->token;
	  $act_email = $row_bp->email;
	  $username = $row_bp->username;
	  $pbid = $_POST['my_pbid'];
	  $cat = $_POST['my_cat'];
	  $ref = "txid-".rand(10000000,99999999);

	  //Initiate cURL.
	  $ch = curl_init($url);

	  $data=array();
	  $data['username']=$username;

	  //Generate Hash
	  $data['hash']=hash('sha512',$token.$act_email.$username);

	  //Category
	  $data['category']= $cat;
	  
	  //Product ID
	  $data['product']= $pbid;

	  //Send as a POST request.
	  curl_setopt($ch, CURLOPT_POST, 1);

	  //Attach encoded JSON string to the POST fields.
	  curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

	  //Allow parsing response to string
	  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

	  //Execute the request
	  $response=curl_exec($ch);

  curl_close ($ch);
	
  	 if($response){
  	   $data = json_decode($response, true);
  	   //print_r($result);
  	   if($data['response'] == "OK"){
  			   
  			    $plan_id = $data['result']['plan_id'];
  			    $openRange = $data['result']['openRange'];

  			    if($openRange == true){
  			    	//show list of product
			        echo '<div class="form-group">
			                  <label for="" class="col-sm-3 control-label" style="color:blue;">Plans</label>
			                  <div class="col-sm-9">
			                  <input name="prange" type="hidden" class="form-control" value='.$data['result']['openRange'].'/>
			                   <input name="min_d" type="hidden" class="form-control" value='.$data['result']['min_denomination'].'/>
			                    <input name="max_d" type="hidden" class="form-control" value='.$data['result']['max_denomination'].'/>
			                  <input name="plan_list" type="text" class="form-control" placeholder="'.$data['result']['min_denomination'].' - '.'.'.$data['result']['max_denomination'].'" required>';
			        echo '</div></div>';
  			    }
  			    else{
  			    	//show list of product
			        echo '<div class="form-group">
			                  <label for="" class="col-sm-3 control-label" style="color:blue;">Plans</label>
			                  <div class="col-sm-9">
			        <select name="plan_list" class="form-control select2" required>
			                    <option selected="selected">Select Plan&hellip;</option>';
			                    foreach ($data['result'] as $pkey) {
			                      echo '<option value='.$pkey['price'].'>'.$pkey['name'].' ('.$pkey['price'].')</option>';
			                    }
			        echo '</select></div></div>';
  			    }
		  	   	
  		   }
  		   else{
  			   echo "<br><label class='label bg-orange'>".$data['response']." (".$data['message'].")</label>";
  		   }
  	   }
       } 
   ?>