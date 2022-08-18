   <?php 
   if(isset($_POST['my_bvn']))
   {
       include("../config/session1.php");
       include ("../config/restful_apicalls.php");
       //$link = mysqli_connect('localhost','root','root','loan2','8888') or die('Unable to Connect to Database');
       
       $result = array();
       $bvn = $_POST['my_bvn'];
       $systemset = mysqli_query($link, "SELECT * FROM systemset");
       $row1 = mysqli_fetch_object($systemset);
       $seckey = $row1->secret_key;
       $bvn_fee = $row1->bvn_fee;
       
       if($vwallet_balance < $bvn_fee){
           
           echo "<br><span class='bg-orange'>Sorry! You do not have insufficient Fund in your Wallet to perform this verification</span>";
           
       }
       else{
           
           $search_restapi = mysqli_query($link, "SELECT * FROM restful_apisetup WHERE api_name = 'bvn'");
           $fetch_restapi = mysqli_fetch_object($search_restapi);
           $api_url = $fetch_restapi->api_url;
           
           $url = $api_url.$bvn.'?seckey='.$seckey;
            	 
           $get_data = callAPI('GET', $url, false);
           $result = json_decode($get_data, true);
            	
           if($result['status'] == "success"){
               
               $wbalance = $vwallet_balance - $bvn_fee;
               
               $icm_id = "ICM".rand(10000,99999);
               
               $date_time = date("Y-m-d");
               
               $update_wallet = mysqli_query($link, "UPDATE vendor_reg SET wallet_balance = '$wbalance' WHERE companyid = '$vendorid'");
               
               $insert_income = mysqli_query($link, "INSERT INTO income VALUES(null,'','$icm_id','BVN','$bvn_fee','$date_time','BVN Charges')");
               
               echo "<br><span class='bg-blue'>Full Name: ".$result['data']['first_name'].' '.$result['data']['middle_name'].' '.$result['data']['last_name']."</span>";
          	   echo "<br><span class='bg-blue'>Date of Birth: ".$result['data']['date_of_birth'].' Phone No.:'.$result['data']['phone_number']."</span>";
            }
            else{
          		echo "<br><span class='bg-orange'>".$result['message']."</span>";
            }
           
       }
        
    }
?>