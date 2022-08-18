   <?php 
   if(isset($_POST['my_bvn']))
   {
       include("../config/connect.php");
	   include ("../config/restful_apicalls.php");
	   //$link = mysqli_connect('localhost','root','root','loan2','8888') or die('Unable to Connect to Database');
	   
	   $result = array();
	   $bvn = $_POST['my_bvn'];
	   $systemset = mysqli_query($link, "SELECT * FROM systemset");
       $row1 = mysqli_fetch_object($systemset);
       $seckey = $row1->secret_key;
    
       $search_restapi = mysqli_query($link, "SELECT * FROM restful_apisetup WHERE api_name = 'bvn'");
       $fetch_restapi = mysqli_fetch_object($search_restapi);
       $api_url = $fetch_restapi->api_url;
       
       $url = $api_url.$bvn.'?seckey='.$seckey;
       
       if(strlen($bvn) != 11){
           
           echo "<span>Loading request....</span>";
           
       }
       else{
    	 
           $get_data = callAPI('GET', $url, false);
           $result = json_decode($get_data, true);
        	
           if($result['status'] == "success"){
               
               echo "<span class='bg-blue'>Full Name: ".$result['data']['first_name'].' '.$result['data']['middle_name'].' '.$result['data']['last_name']."</span>";
          	   echo "<br><span class='bg-blue'>Date of Birth: ".$result['data']['date_of_birth'].' Phone No.:'.$result['data']['phone_number']."</span>";
            }
            else{
          		echo "<br><label class='label label-danger'>".$result['message']."</label>";
            }
            
       }
    }
    ?>