   <?php 
   if(isset($_POST['myphone']))
   {
    include("../config/session.php");
	  //$link = mysqli_connect('localhost','root','root','loan2','8888') or die('Unable to Connect to Database');
	$jsonData=array();
	$search_billconfig = mysqli_query($link, "SELECT * FROM billpayment");
	$fetch_billconfig = mysqli_fetch_object($search_billconfig);
	
	$url = 'https://estoresms.com/network_list/v/2'; //API Url (Do not change)
	//$url = '$fetch_billconfig->api_url'; //API Url (Do not change)
    $token=$fetch_billconfig->token; //Replace with your API token
    $email=$fetch_billconfig->email;  //Replace with your account email address
    $username=$fetch_billconfig->username;  // Replace with your account username
    $ref = "txid-".rand(10000000,99999999);
    $phone = $_POST['myphone'];

    //Initiate cURL.
    $ch = curl_init($url);
    
    $jsonData['username']=$username;
    
    //Ref
    //$jsonData['ref']=$ref;
    
    //Generate Hash
    $jsonData['hash']=hash('sha512',$token.$email.$username);
    
    //Category
    $jsonData['phone']=$phone;
    
    //$jsonData['product']='BPI-NGCA-ANA';
    	  
    //Send as a POST request.
    $jsonDataEncoded = json_encode($jsonData);

    //Send as a POST request.
    curl_setopt($ch, CURLOPT_POST, 1);

    //Attach encoded JSON string to the POST fields.
    curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonDataEncoded);

    //Set the content type as application/json
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));

    //Allow parsing response to string
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    //Execute the request
    $response=curl_exec($ch);
    
    //curl_close ($ch);
    
    if($response){
  	   $jsonData = json_decode($response, true);
  	   //var_dump($response);
  	   if(($jsonData['response'] == "OK") && ($jsonData['info']['openRange'] == true)) {

                echo '<div class="form-group">
                        <label for="" class="col-sm-3 control-label" style="color:blue;">Operator:</label>
                      <div class="col-sm-9">
                      <input name="operator" type="text" class="form-control" value="'.$jsonData['info']['operator'].' ('.$jsonData['info']['country'].')" readonly/>
                      </div></div>';

                      foreach ($jsonData['products'] as $pkey) {

                        echo '<input name="airtime_product" type="hidden" class="form-control" value="'.$pkey['id'].'"/>';

                      }

                echo '<div class="form-group">
                        <label for="" class="col-sm-3 control-label" style="color:blue;">Amount:</label>
                      <div class="col-sm-9">
                      <input name="amount_recharge" type="number" class="form-control" placeholder="Enter Amount" required/>
                      <span style="font-size: 14px;"><b>NOTE:</b> <i>Minimum Amount you can Recharge is <b>NGN50</b> AND Maximum is NGN200000</i></span>
                      </div></div>';

          }elseif(($jsonData['response'] == "OK") && ($jsonData['info']['openRange'] == false)) {

                echo '<div class="form-group">
                        <label for="" class="col-sm-3 control-label" style="color:blue;">Operator:</label>
                      <div class="col-sm-9">
                      <input name="operator" type="text" class="form-control" value="'.$jsonData['info']['operator'].' ('.$jsonData['info']['country'].')" readonly/>
                      </div></div>';

                echo '<div class="form-group">
                        <label for="" class="col-sm-3 control-label" style="color:blue;">Airtime Product</label>
                      <div class="col-sm-9">
                      <select name="airtime_product"  class="form-control"  required style="width:100%">
                              <option value="" selected="selected">Select Airtime Product...</option>';
                      //$a = 0;
                        foreach ($jsonData['products'] as $pkey) {
                            //$a++;
                          echo '<option value="'.$pkey['id'].'">'.$pkey['topup_currency'].' '.$pkey['topup_amount'].' (NGN'.number_format($pkey['price'],2,'.',',').')</option>';

                        }

                echo '</select></div></div>';
                
                
                echo '<div class="form-group">
                        <label for="" class="col-sm-3 control-label" style="color:blue;">Airtime Amount</label>
                      <div class="col-sm-9">
                      <select name="amount_recharge"  class="form-control"  required style="width:100%">
                              <option value="" selected="selected">Select Airtime Amount...</option>';
                      //$a = 0;
                        foreach ($jsonData['products'] as $pkey) {
                            //$a++;
                          echo '<option value="'.$pkey['topup_amount'].'">'.$pkey['topup_currency'].' '.$pkey['topup_amount'].'</option>';

                        }
                echo '</select><span style="font-size: 14px;"><b>NOTE:</b> <i>Select the amount acccording to the plan choosed</span>';
                echo '</div></div>';

          } 
		        
  	   }else{
  	       
  	       echo "<br><label class='label bg-orange'>Oops!...Network Error, Please try again later!!</label>";
  	       
  	   }
    }
   ?>