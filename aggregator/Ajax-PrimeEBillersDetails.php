<?php
include ("../config/session.php");
$PostType = $_GET['PostType'];

$myaltcall = mysqli_query($link, "SELECT * FROM member_settings WHERE companyid = '$aggmerchant'");
$myaltrow = mysqli_fetch_array($myaltcall);

$parameter = (explode(',',$PostType));
$productcode = $parameter[0];
$serviceid = $parameter[1];
$hasProductList = $parameter[2];
$hasValidate = $parameter[3];
$min = $parameter[4];
$max = $parameter[5];

if($PostType == ""){
    echo "";
}
elseif($serviceid == "electricity"){
?>

                <input name="productcode" type="hidden" class="form-control" id="productcode" value="<?php echo $productcode; ?>">
                
                <div class="form-group">
                    <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Customer ID:</label>
                    <div class="col-sm-6">
                        <input name="customerid" type="text" class="form-control" placeholder="Enter Customer ID (Account Number/Meter Number/Card Number)" id="customerid" onkeyup="loadMyCustomerDetails();" required>
                        <input name="serviceid" type="hidden" class="form-control" id="serviceid" value="<?php echo $serviceid; ?>">
                    </div>
                    <label for="" class="col-sm-3 control-label" align="left"></label>
                </div>
                
                <div class="form-group">
                    <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Customer Name</label>
                    <div class="col-sm-6">
                        <div id="cust_name">------------</div>
                    </div>
                    <label for="" class="col-sm-3 control-label"></label>
                  </div>

                <div class="form-group">
                    <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Product Type:</label>
                    <div class="col-sm-6">
                        <select name="pcode" class="form-control select2" required style="width:100%">
                            <option value="" selected="selected">Select Type</option>
                            <option value="1">Prepaid</option>
                            <option value="0">Postpaid</option>
                        </select>
                    </div>
                    <label for="" class="col-sm-3 control-label" align="left"></label>
                </div>

                <div class="form-group">
                    <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Amount:</label>
                    <div class="col-sm-6">
                        <input name="amount" type="number" class="form-control" min="<?php echo $min; ?>" max="<?php echo $max; ?>" required>
                    </div>
                    <label for="" class="col-sm-3 control-label" align="left"></label>
                </div>

<?php
}else{
?>

                <input name="productcode" type="hidden" class="form-control" id="productcode" value="<?php echo $productcode; ?>">

                <?php
                if($hasValidate == true){
                ?>

                <div class="form-group">
                    <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Customer ID:</label>
                    <div class="col-sm-6">
                        <input name="customerid" type="text" class="form-control" placeholder="Enter IUC Number" id="customerid" onkeyup="loadMyCustomerDetails();" required>
                        <input name="serviceid" type="hidden" class="form-control" id="serviceid" value="<?php echo $serviceid; ?>">
                    </div>
                    <label for="" class="col-sm-3 control-label" align="left"></label>
                </div>
                
                <div class="form-group">
                    <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Customer Name</label>
                    <div class="col-sm-6">
                        <div id="cust_name">------------</div>
                    </div>
                    <label for="" class="col-sm-3 control-label"></label>
                  </div>
            

                <?php
                }
                else{
                ?>

                <input name="customerid" type="hidden" class="form-control">

                <?php } ?>

                <?php
                if($hasProductList == true){
                ?>

                <div class="form-group">
                    <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Product:</label>
                    <div class="col-sm-6">
                        <select name="plist" class="form-control select2" id="pListDetails" required style="width:100%">
                            <option value="" selected="selected">Select Product</option>
                            <?php
                            $curl = curl_init();
                            $search_restapi1 = mysqli_query($link, "SELECT * FROM restful_apisetup WHERE api_name = 'primeairtime_baseUrl'");
                            $fetch_restapi1 = mysqli_fetch_object($search_restapi1);
                            $api_url = $fetch_restapi1->api_url.'/api/billpay/'.$serviceid.'/'.$productcode;

                            $curl = curl_init();
                            curl_setopt_array($curl, array(
                                CURLOPT_URL => $api_url,
                                CURLOPT_RETURNTRANSFER => true,
                                CURLOPT_ENCODING => "",
                                CURLOPT_MAXREDIRS => 10,
                                CURLOPT_TIMEOUT => 0,
                                CURLOPT_FOLLOWLOCATION => false,
                                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                                CURLOPT_CUSTOMREQUEST => "GET",
                                CURLOPT_HTTPHEADER => [
                                    "Content-Type: application/json",
                                    "Authorization: Bearer ".$accessToken            
                                ],
                            ));
                            
                            $airtime_response = curl_exec($curl);
                            $getAirtime = json_decode($airtime_response, true);

                            foreach($getAirtime['products'] as $key){

                                echo '<option value="'.$key['code'].','.$key['topup_value'].'">'.$key['name'].'</option>';

                            }
                            ?>
                        </select>
                    </div>
                    <label for="" class="col-sm-3 control-label" align="left"></label>
                </div>

                <span id='ShowValueFrank3'></span>
                <span id='ShowValueFrank3'></span>

                <?php
                }
                else{
                ?>

                <input name="pcode" type="hidden" class="form-control" value="">

                <div class="form-group">
                    <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Amount:</label>
                    <div class="col-sm-6">
                        <input name="amount" type="number" class="form-control" min="<?php echo $min; ?>" max="<?php echo $max; ?>" required>
                    </div>
                    <label for="" class="col-sm-3 control-label" align="left"></label>
                </div>

                <?php } ?>

<?php
}
?>

<script>
     $('#pListDetails').change(function(){
         var PostType=$('#pListDetails').val();
         $.ajax({url:"Ajax-PrimePListDetails.php?PostType="+PostType,cache:false,success:function(result){
             $('#ShowValueFrank3').html(result);
         }});
     });
 </script>
 