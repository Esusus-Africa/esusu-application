<?php 
   error_reporting(0); 
   if(isset($_POST['service_name']))
   {
    include("../config/session1.php");
    //$link = mysqli_connect('localhost','root','root','loan2','8888') or die('Unable to Connect to Database');

	$jsonData=array();
	$search_billconfig = mysqli_query($link, "SELECT * FROM billpayment");
    $fetch_billconfig = mysqli_fetch_object($search_billconfig);
    
    $myaltcall = mysqli_query($link, "SELECT * FROM member_settings WHERE companyid = '$institution_id'");
	$myaltrow = mysqli_fetch_array($myaltcall);
	
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
                            <label for="" class="col-sm-3 control-label" style="color:'.(($myaltrow['theme_color'] == "") ? "blue" : $myaltrow['theme_color']).';">Bill Product</label>
                            <div class="col-sm-6">
                                <select name="plan_list" class="form-control select2" required style="width:100%">
                                <option value="" selected="selected">Select Bill Product...</option>';
                                //$a = 0;
    		                    foreach ($jsonData['result'] as $pkey) {
    		                        //$a++;
    		                      echo '<option value="'.$pkey['product_id'].'">'.$pkey['name'].'</option>';
    		                    }
                                
                echo '</select>
                            </div>
                            <label for="" class="col-sm-3 control-label"></label>
                        </div>';
                        
		        
		        if($cat == "tv" || $cat == "electricity"){
		            
		            echo '<div class="form-group">
                                <label for="" class="col-sm-3 control-label" style="color:'.(($myaltrow['theme_color'] == "") ? "blue" : $myaltrow['theme_color']).';">Smart Card / Account No.:</label>
                                <div class="col-sm-6">
                                    <input name="scard" type="number" class="form-control" placeholder="Enter your Smart Card / Account Number" required>
                                </div>
                                <label for="" class="col-sm-3 control-label"></label>
                            </div>';
		 
		        }
		        
		        
  	   }else{
  	       
  	       echo "<br><label class='label bg-orange'>Oops!...Network Error, Please try again later!!</label>";
  	       
  	   }
    }
   }
   ?>