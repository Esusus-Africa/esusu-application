<select name="bank_code"  class="form-control select2" id="bank_code" onchange="loadaccount();"required>
            <option selected>Select Bank Code...</option> 
   <?php 
   if(isset($_POST['my_country']))
   {
	 include("../config/connect.php");
	 include("../config/restful_apicalls.php");
	 //$link = mysqli_connect('localhost','root','root','loan2','8888') or die('Unable to Connect to Database');
	 
   $response = array();
   $my_country = $_POST['my_country'];
   
   $systemset = mysqli_query($link, "SELECT * FROM systemset");
   $row1 = mysqli_fetch_object($systemset);
   $public_key = $row1->public_key;

  $search_restapi = mysqli_query($link, "SELECT * FROM restful_apisetup WHERE api_name = 'banklist'");
  $fetch_restapi = mysqli_fetch_object($search_restapi);
  $api_url = $fetch_restapi->api_url;
  
  $url = $api_url.$my_country."?public_key=".$public_key;

  $get_data = callAPI('GET', $url, false);
  $response = json_decode($get_data, true);
	//print_r($response['data']);
   if($response['status'] == "success"){
  	//something came in
    foreach($response['data']['Banks'] as $key){
?>
			<option value="<?php echo $key['Code']; ?>"><?php echo $key['Code'].' - '.$key['Name']; ?></option>

	<?php
}
}
}
?>
</select>