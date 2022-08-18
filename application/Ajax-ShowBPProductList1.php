<?php
include ("../config/session.php");
$PostType = $_GET['PostType'];

  $billp_settings = mysqli_query($link, "SELECT * FROM billpayment WHERE companyid = '$agentid' AND status = 'Active'");
  $row_bp = mysqli_fetch_object($billp_settings);

  $url = 'https://estoresms.com/bill_payment_processing/v/1/'; //API Url (Do not change)
  $token = $row_bp->token;
  $act_email = $row_bp->email;
  $username = $row_bp->username;

  //Initiate cURL.
  $ch = curl_init($url);

  $data=array();
  $data['username']=$username;

  //Generate Hash
  $data['hash']=hash('sha512',$token.$act_email.$username);

  //Category
  $data['category']=$PostType;

  //Send as a POST request.
  curl_setopt($ch, CURLOPT_POST, 1);

  //Attach encoded JSON string to the POST fields.
  curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

  //Allow parsing response to string
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

  //Execute the request
  $response=curl_exec($ch);

  curl_close ($ch);
  if($response) {
    $data = json_decode($response, true);
    if($data["response"] == "OK")
    {
?>
 
        <div class="form-group">
            <label for="" class="col-sm-3 control-label" style="color:blue;">Product List</label>
          <div class="col-sm-9">
          <select name="product_list" class="form-control select2" required>
                      <option selected="selected">Select Product List&hellip;</option>
            <?php
            foreach($data['result'] as $key) {
              echo '<option value='.$key['product_id'].'>'.$key['name'].' ('.$key['product_id'].')</option>';
            }
            ?>
          </select>
          </div>
        </div>
        <input name="category" type="hidden" class="form-control" value="<?php echo $PostType; ?>" onload="loadpB1();" id="cat"/>
       
        <div class="form-group">
          <label for="" class="col-sm-3 control-label" style="color:blue">Product ID</label>
          <div class="col-sm-9">
            <input name="product_id" type="text" id="product_id" onkeyup="loadpB1();" class="form-control" placeholder="Enter Product ID from the Product List Choosen Above e.g BPD-NGCA-AQA" required>
          </div>
        </div>
        <div id="get_product"></div>
        <div class="form-group">
            <label for="" class="col-sm-3 control-label" style="color:blue">SmartCard Number</label>
            <div class="col-sm-9">
              <input name="smartcard" type="number" id="smartcard" onkeydown="loadValidation1();" class="form-control" placeholder="Enter Your SmartCard Number Here" required>
              <div id="validate_customer"></div>
            </div>
        </div>

        <div align="right">
          <div class="box-footer">
              <button name="PayBill1" type="submit" class="btn bg-blue btn-flat"><i class="fa fa-mobile">&nbsp;Pay Bills</i></button>
          </div>
        </div>
      <?php
      }
      else{
        echo "<br><label class='label bg-orange'>".$data['response']." (".$data['message'].")</label>";
      }
    }
    ?>