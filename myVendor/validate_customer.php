   <?php 
   if(isset($_POST['my_scard']))
   {
   include("../config/session.php");
   //$link = mysqli_connect('localhost','root','root','loan2','8888') or die('Unable to Connect to Database');
   
  $billp_settings = mysqli_query($link, "SELECT * FROM billpayment");
  $row_bp = mysqli_fetch_object($billp_settings);

  $url = 'https://estoresms.com/bill_payment_processing/v/1/'; //API Url (Do not change)
  $token = $row_bp->token;
  $act_email = $row_bp->email;
  $username = $row_bp->username;
  $pbid = $_POST['my_plans'];
  $cat = $_POST['service_name'];
  $smartcard = $_POST['my_scard'];

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

  //Validate
  $data['validate']= $smartcard;

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
           
           echo "<br><label class='label bg-blue'>".$data['result']['name']."</label>";
         }
         else{
           echo "<br><label class='label bg-orange'>".$data['response']." (".$data['message'].")</label>";
         }
       }
       } 
   ?>