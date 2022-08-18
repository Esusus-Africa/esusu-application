   <?php 
   if(isset($_POST['my_bvn']))
   {
       include("../config/session1.php");
       include ("../config/restful_apicalls.php");
       //$link = mysqli_connect('localhost','root','root','loan2','8888') or die('Unable to Connect to Database');
       
       $result = array();
       $bvn = $_POST['my_bvn'];
       $actno = $_POST['my_actno'];
       $systemset = mysqli_query($link, "SELECT * FROM systemset");
       $row1 = mysqli_fetch_object($systemset);
       $seckey = $row1->secret_key;
       $bvn_fee = $row1->bvn_fee;
       
       $search_mycust = mysqli_query($link, "SELECT * FROM borrowers WHERE account = '$actno'");
       $fetch_mycust = mysqli_fetch_array($search_mycust);
       $fname = $fetch_mycust['fname'];
       $lname = $fetch_mycust['lname'];
       $dob = $fetch_mycust['dob'];
       $phone = $fetch_mycust['phone'];
       
       if($iwallet_balance < $bvn_fee){
           
           echo "<br><span class='bg-orange'>Sorry! You do not have sufficient fund in your Wallet for this verification</span>";
           
       }
       elseif(strlen($bvn) != 11){
           
           echo "<br><span>Loading request....</span>";
           
       }
       else{
           
           /*$search_restapi = mysqli_query($link, "SELECT * FROM restful_apisetup WHERE api_name = 'bvn'");
           $fetch_restapi = mysqli_fetch_object($search_restapi);
           $api_url = $fetch_restapi->api_url;
           
           $url = $api_url.$bvn.'?seckey='.$seckey;
            	 
           $get_data = callAPI('GET', $url, false);
           $result = json_decode($get_data, true);
            	
           if($result['status'] == "success"){
               
               $wbalance = $iwallet_balance - $bvn_fee;
               
               $icm_id = "ICM".rand(10000,99999);
               $exp_id = "EXP".rand(100000,999999);
               
               $date_time = date("Y-m-d");
               //substr()
               $bvn_fname = $result['data']['first_name'];
               $bvn_lname = $result['data']['last_name'];
               $bvn_dob = $result['data']['date_of_birth'];
               $bvn_phone = "+234".substr($result['data']['phone_number'],-10);
               $correct_bvnPhone = $result['data']['phone_number'];
               
               $update_wallet = mysqli_query($link, "UPDATE institution_data SET wallet_balance = '$wbalance' WHERE institution_id = '$institution_id'");
               
               $insert_income = mysqli_query($link, "INSERT INTO expenses VALUES(null,'$institution_id','$exp_id','BVN','$bvn_fee','$date_time','BVN Charges')");
               
               $insert_income = mysqli_query($link, "INSERT INTO income VALUES(null,'','$icm_id','BVN','$bvn_fee','$date_time','BVN Charges')");
               
               $message = "First Name: $bvn_fname ".(($bvn_fname == $fname) ? '<p style="color: blue;"><b>Data Matched with First Name in Database</b> <i class="fa fa-check"></i></p>' : '<p style="color: orange;"><b>Data Not-Matched with First Name in Database</b> <i class="fa fa-times"></i></p>');
               $message .= "Last Name: $bvn_lname ".(($bvn_lname == $lname) ? '<p style="color: blue;"><b>Data Matched with Last Name in Database</b> <i class="fa fa-check"></i></p>' : '<p style="color: orange;"><b>Data Not-Matched with Last Name in Database</b> <i class="fa fa-times"></i></p>');
               $message .= "Date of Birth: $bvn_dob ".(($bvn_dob == $dob) ? '<p style="color: blue;"><b>Data Matched with Date of Birth in Database</b> <i class="fa fa-check"></i></p>' : '<p style="color: orange;"><b>Data Not-Matched with Date of Birth in Database</b> <i class="fa fa-times"></i></p>');
               $message .= "Phone Number: $correct_bvnPhone ".(($bvn_phone == $phone) ? '<p style="color: blue;"><b>Data Matched with Phone Number in Database</b> <i class="fa fa-check"></i></p>' : '<p style="color: orange;"><b>Data Not-Matched with Phone Number in Database</b> <i class="fa fa-times"></i></p>');
               
               echo $message;
            }
            else{
          		echo "<br><span class='bg-orange'>Oops! Network Error, please try again later </span>";
            }*/
           
       }
        
    }
?>