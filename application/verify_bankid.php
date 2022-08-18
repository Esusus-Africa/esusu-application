 <select name="branch_code"  class="form-control select2" required>
            <option selected>Select Bank Branch...</option>
   <?php 
   if(isset($_POST['my_bcode']))
   {
	 include("../config/connect.php");
	 include("../config/restful_apicalls.php");
	 //$link = mysqli_connect('localhost','root','root','loan2','8888') or die('Unable to Connect to Database');
	 
  	 $response = array();
  	 $my_bcode = $_POST['my_bcode'];
  	 $systemset = mysqli_query($link, "SELECT * FROM systemset");
	 $row1 = mysqli_fetch_object($systemset);
	 $public_key = $row1->public_key;

     $search_restapi = mysqli_query($link, "SELECT * FROM restful_apisetup WHERE api_name = 'bankbranch'");
     $fetch_restapi = mysqli_fetch_object($search_restapi);
     $api_url = $fetch_restapi->api_url;
	 
	 $url = $api_url.$my_bcode."?public_key=".$public_key;

     $get_data = callAPI('GET', $url, false);
     $response = json_decode($get_data, true);
	 //print_r($response);
     if($response['status'] == "success"){
  	 //something came in
  	 foreach($response['data']['Data'] as $key){
?>
		<option value="<?php echo $key['BranchCode']; ?>"><?php echo $key['BranchName']; ?></option>
	<?php
    }
    }
    }
    ?>
	</select>