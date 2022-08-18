<div class="box">
	       <div class="box-body">
			<div class="panel panel-success">
            <div class="panel-heading bg-blue">
            <h3 class="panel-title"><i class="fa fa-link"></i> Add New Terminal</h3>
            </div>

             <div class="box-body">
 <?php
if(isset($_POST['configTerminal']))
{
    $curl = curl_init();
    $terminalserial =  mysqli_real_escape_string($link, $_POST['terminalserial']);
    $logo = mysqli_real_escape_string($link, $_POST['logo']);
    $merchantname =  mysqli_real_escape_string($link, $_POST['merchantname']);
    $address = mysqli_real_escape_string($link, $_POST['address']);
    $phone = mysqli_real_escape_string($link, $_POST['phone']);
    $appname =  mysqli_real_escape_string($link, $_POST['appname']);
    $appversion =  mysqli_real_escape_string($link, $_POST['appversion']);
    $appurl = mysqli_real_escape_string($link, $_POST['appurl']);
    $appphone = mysqli_real_escape_string($link, $_POST['appphone']);

    putenv("API_URL=https://5igp2ofnzc.execute-api.us-west-2.amazonaws.com/prod/terminalconfiguration");
    
    putenv("API_KEY=ESUSU-IUEIEUK89378873UJKMEKNBGUYU9");
        
    $apikey = getenv('API_KEY');

    $api_url = getenv('API_URL');

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
            'terminal_serial'=>$terminalserial,
            'logo'=>$logo,
            'merchantname'=>$merchantname,
            'address'=>$address,
            'phone'=>$phone,
            'appname'=>$appname,
            'appversion'=>$appversion,
            'appurl'=>$appurl,
            'appphone'=>$appphone,
            'priority'=>'3'
            ]),
            CURLOPT_HTTPHEADER => array(
                "Authorization: ".$apikey,
                "Content-Type: application/json"
            ),
        ));
                    
    $response = curl_exec($curl);
    $rubbies_generate = json_decode($response, true);
    
    if($rubbies_generate['responsecode'] == "00"){

        mysqli_query($link, "UPDATE terminal_reg SET custom_logo = '$logo', custom_merchantname = '$merchantname', custom_address = '$address', custom_phone = '$phone', custom_appname = '$appname', custom_appversion = '$appversion', custom_appurl = '$appurl', custom_appphone = '$appphone' WHERE terminal_serial = '$terminalserial'");

        echo "<div class='alert bg-blue'>".$rubbies_generate['responsemessage']."</div>";

    }
    else{

        echo "<div class='alert bg-orange'>Opps!....Unable to configure terminal!!</div>";

    }

}
?>           
			 <form class="form-horizontal" method="post" enctype="multipart/form-data">

             <div class="box-body">

             <div class="form-group">
            <label for="" class="col-sm-3 control-label" style="color:blue;">Terminal Serial:</label>
            <div class="col-sm-6">
                <select name="terminalserial" class="form-control select2" id="terminalSerial" required>
                    <option value="" selected>---Select Terminal---</option>
                    <?php
                    $search = mysqli_query($link, "SELECT * FROM terminal_reg WHERE terminal_serial != ''");
                    while($get_search = mysqli_fetch_array($search))
                    {
                    ?>
                    <option value="<?php echo $get_search['terminal_serial']; ?>"><?php echo $get_search['terminal_serial'].' - '.$get_search['merchant_name'].' - '.$get_search['terminal_model_code'].' ('.$get_search['ptsp'].')'.(($get_search['custom_logo'] == "") ? " : NOT-CONFIGURED" : " : CONFIGURED"); ?></option>
                    <?php } ?>
                </select>
            </div>
            <label for="" class="col-sm-3 control-label"></label>
            </div>

            <span id='ShowValueFrank'></span>
            <span id='ShowValueFrank'></span>
                 
			 </div>
			 
			<div class="form-group" align="right">
                <label for="" class="col-sm-3 control-label" style="color:blue;"></label>
                <div class="col-sm-6">
                	<button name="configTerminal" type="submit" class="btn bg-blue"><i class="fa fa-plus">&nbsp;Submit</i></button>
                </div>
                <label for="" class="col-sm-3 control-label"></label>
            </div>
			  
			 </form> 

</div>	
</div>	
</div>
</div>