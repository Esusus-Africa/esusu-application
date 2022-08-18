<?php
include ("../config/session1.php");
$PostType = $_GET['PostType'];

$myaltcall = mysqli_query($link, "SELECT * FROM member_settings WHERE companyid = '$vcreated_by'");
$myaltrow = mysqli_fetch_array($myaltcall);

if($PostType == ""){

    echo "";

}
else{
?>

                <div class="form-group">
                    <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Billers:</label>
                    <div class="col-sm-6">
                        <select name="billers" class="form-control select2" id="billers" required style="width:100%">
                            <option value="" selected="selected">Select Billers</option>
                            <?php
                            $curl = curl_init();
                            $search_restapi1 = mysqli_query($link, "SELECT * FROM restful_apisetup WHERE api_name = 'rubies_billers'");
                            $fetch_restapi1 = mysqli_fetch_object($search_restapi1);
                            $api_url = $fetch_restapi1->api_url;

                            curl_setopt_array($curl, array(
                                CURLOPT_URL => $api_url,
                                CURLOPT_RETURNTRANSFER => true,
                                CURLOPT_ENCODING => "",
                                CURLOPT_MAXREDIRS => 10,
                                CURLOPT_TIMEOUT => 0,
                                CURLOPT_FOLLOWLOCATION => true,
                                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                                CURLOPT_CUSTOMREQUEST => "POST",
                                CURLOPT_POSTFIELDS => json_encode([
                                        'biller'=>$PostType
                                    ]),
                                CURLOPT_HTTPHEADER => array(
                                    "Authorization: ".$rubbiesSecKey,
                                    "Content-Type: application/json"
                                ),
                            ));
                                
                            $response = curl_exec($curl);
                            $rubbies_generate = json_decode($response, true);

                            foreach($rubbies_generate['billers'] as $key){

                                echo '<option value="'.$key['productcode'].','.$key['billercode'].','.$key['price'].','.$key['servicename'].'">'.$key['servicename'].'</option>';

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
     $('#billers').change(function(){
         var PostType=$('#billers').val();
         $.ajax({url:"Ajax-Billers.php?PostType="+PostType,cache:false,success:function(result){
             $('#ShowValueFrank2').html(result);
         }});
     });
 </script>