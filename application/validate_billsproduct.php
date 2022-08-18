   <?php 
   if(isset($_POST['service_name']))
   {
    include("../config/session.php");
	  //$link = mysqli_connect('localhost','root','root','loan2','8888') or die('Unable to Connect to Database');
	$jsonData=array();
	$search_billconfig = mysqli_query($link, "SELECT * FROM billpayment");
	$fetch_billconfig = mysqli_fetch_object($search_billconfig);
	
	$url = 'https://estoresms.com/bill_payment_processing/v/1/'; //API Url (Do not change)
	//$url = '$fetch_billconfig->api_url'; //API Url (Do not change)
    $token=$fetch_billconfig->token; //Replace with your API token
    $email=$fetch_billconfig->email;  //Replace with your account email address
    $username=$fetch_billconfig->username;  // Replace with your account username
    $ref = "txid-".rand(10000000,99999999);
    $cat = $_POST['service_name'];

    //Initiate cURL.
    $ch = curl_init($url);
    
    $jsonData['username']=$username;
    
    //Ref
    //$jsonData['ref']=$ref;
    
    //Generate Hash
    $jsonData['hash']=hash('sha512',$token.$email.$username);
    
    //Category
    $jsonData['category']=$cat;
    
    //$jsonData['product']='BPI-NGCA-ANA';
    	  
    //Send as a POST request.
    curl_setopt($ch, CURLOPT_POST, 1);
    
    //Attach encoded JSON string to the POST fields.
    curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
    
    //Allow parsing response to string
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    
    //Execute the request
    $response=curl_exec($ch);
    
    //curl_close ($ch);
    
    if($response){
  	   $jsonData = json_decode($response, true);
  	   //var_dump($response);
  	   if($jsonData['response'] == "OK"){
    
                echo '<div class="form-group">
                        <label for="" class="col-sm-3 control-label" style="color:blue;">Plan Name</label>
		                  <div class="col-sm-9">';
		                  //$a = 0;
		                    foreach ($jsonData['result'] as $pkey) {
		                        //$a++;
		                      echo '<p><input type="radio" name="plan_list" value='.$pkey['product_id'].'> '.$pkey['name'].'</p>';
		                    }
		        echo '</div></div>';
		        
		        if($cat == "tv" || $cat == "electricity"){
		            
		            echo '<div class="form-group">
                        <label for="" class="col-sm-3 control-label" style="color:blue;">Smart Card / Account No.:</label>
		                  <div class="col-sm-9">
		                  <input name="scard" type="text" class="form-control" placeholder="Enter your Smart Card / Account Number" required>
		                  </div></div>';
		 
		        }
		        
		        
  	   }else{
  	       
  	       echo "<br><label class='label bg-orange'>Oops!...Network Error, Please try again later!!</label>";
  	       
  	   }
    }
   }
   ?>
    