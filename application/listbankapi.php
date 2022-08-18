   <select name="api_name"  class="form-control" required>
            <option value="" selected>Select Bank API List...</option>
            <option value="create-virtualcards">Create Va</option>
   <?php 
   /**
   if(isset($_POST['my_bank']))
   {
   include("../config/connect.php");
   //$link = mysqli_connect('localhost','root','root','loan2','8888') or die('Unable to Connect to Database');
   
   $my_bank = $_POST['my_bank'];

  $search_restapi = mysqli_query($link, "SELECT * FROM atmcard_gateway_apis WHERE issuer_name = '$my_bank'");
  while($fetch_restapi = mysqli_fetch_object($search_restapi)){
  ?>
      <option value="<?php echo $fetch_restapi->api_name; ?>"><?php echo $fetch_restapi->api_name; ?></option>

  <?php
  }
  }
  **/
  ?>
  </select>