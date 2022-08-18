<?php
include ("../config/session.php");
$PostType = $_GET['PostType'];

$myaltcall = mysqli_query($link, "SELECT * FROM member_settings WHERE companyid = '$aggmerchant'");
$myaltrow = mysqli_fetch_array($myaltcall);

if($PostType == ""){

    echo "";

}
elseif($PostType == "electricity"){
?>

                <div class="form-group">
                    <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Billers:</label>
                    <div class="col-sm-6">
                        <select name="billers" class="form-control select2" id="ebillersDetails" required style="width:100%">
                            <option value="" selected="selected">Select Billers</option>
                            <?php
                            $curl = curl_init();
                            $search_restapi1 = mysqli_query($link, "SELECT * FROM restful_apisetup WHERE api_name = 'primeairtime_baseUrl'");
                            $fetch_restapi1 = mysqli_fetch_object($search_restapi1);
                            $api_url = $fetch_restapi1->api_url.'/api/billpay/country/NG/electricity';

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

                                echo '<option value="'.$key['product_id'].','.$PostType.','.$key['hasProductList'].','.$key['hasValidate'].','.$key['min_denomination'].','.$key['max_denomination'].'">'.$key['name'].'</option>';

                            }
                            ?>
                        </select>
                    </div>
                    <label for="" class="col-sm-3 control-label" align="left"></label>
                </div>

                <span id='ShowValueFrank2'></span>
                <span id='ShowValueFrank2'></span>

<?php
}else{
?>

                <div class="form-group">
                    <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Billers:</label>
                    <div class="col-sm-6">
                        <select name="billers" class="form-control select2" id="ebillersDetails" required style="width:100%">
                            <option value="" selected="selected">Select Billers</option>
                            <?php
                            $curl = curl_init();
                            $search_restapi1 = mysqli_query($link, "SELECT * FROM restful_apisetup WHERE api_name = 'primeairtime_baseUrl'");
                            $fetch_restapi1 = mysqli_fetch_object($search_restapi1);
                            $api_url = $fetch_restapi1->api_url.'/api/billpay/country/NG/'.$PostType;

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

                                $rightDeno = ($key['hasProductList'] == true) ? '' : $key['min_denomination'].','.$key['max_denomination'];

                                echo '<option value="'.$key['product_id'].','.$PostType.','.$key['hasProductList'].','.$key['hasValidate'].','.$rightDeno.'">'.$key['name'].'</option>';

                            }
                            ?>
                        </select>
                    </div>
                    <label for="" class="col-sm-3 control-label" align="left"></label>
                </div>

                <span id='ShowValueFrank2'></span>
                <span id='ShowValueFrank2'></span>
                
                
                

<?php
}
?>

<script>
     $('#ebillersDetails').change(function(){
         var PostType=$('#ebillersDetails').val();
         $.ajax({url:"Ajax-PrimeEBillersDetails.php?PostType="+PostType,cache:false,success:function(result){
             $('#ShowValueFrank2').html(result);
         }});
     });
 </script>
