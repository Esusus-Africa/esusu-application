<div class="box">
	       <div class="box-body">
			<div class="panel panel-success">
            <div class="panel-heading bg-blue">
            <h3 class="panel-title"><i class="fa fa-link"></i> Linking Vervecard</h3>
            </div>

             <div class="box-body">
 <?php
if(isset($_POST['linkcard']))
{
    $curl = curl_init();
	$cardholder = mysqli_real_escape_string($link, $_POST['cardholder']);
	$pan = mysqli_real_escape_string($link, $_POST['pan']);
	$validatePan = preg_match("/^([506]{3})([0-9]{1,16})$/", $pan, $match);
	$tpin = mysqli_real_escape_string($link, $_POST['tpin']);
	
	$search_customerbal = mysqli_query($link, "SELECT * FROM borrowers WHERE virtual_acctno = '$cardholder'");
	$fetch_customernum = mysqli_num_rows($search_customerbal);
    $fetch_customerbal = mysqli_fetch_array($search_customerbal);
    
    $search_agtbal = mysqli_query($link, "SELECT * FROM user WHERE virtual_acctno = '$cardholder'");
    $fetch_agtnum = mysqli_num_rows($search_agtbal);
	$fetch_agtbal = mysqli_fetch_array($search_agtbal);
       
	$cust_phone = ($fetch_customernum == 1 && $fetch_agtnum == 0) ? $fetch_customerbal['phone'] : $fetch_agtbal['phone'];
	
	if($control_pin != $tpin){
	    
	    echo "<div class='alert bg-orange'>Opps!...Invalid Transaction Pin Entered</div>";
	    
	}
	elseif(!$validatePan){
	    
	    echo "<div class='alert bg-orange'>Opps!..Invalid VerveCard Pan Number Entered!!</div>";
	    
	}
	else{
	    
        putenv("API_URL=https://6y4c9bzelb.execute-api.us-east-2.amazonaws.com/cards/cardmapping");
    
        putenv("API_KEY=ESUSU-IUEIEUK89378873UJKMEKNBGUYU9");

        putenv("POOL_ACCOUNT=1624151268");
            
        $apikey = getenv('API_KEY');

        $api_url = getenv('API_URL');

        $poolacct = getenv('POOL_ACCOUNT');

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
                'accountnumber'=>$cardholder,
                'pan'=>$pan,
                'settlementaccount'=>$poolacct,
                'mobilenumber'=>$cust_phone
                ]),
                CURLOPT_HTTPHEADER => array(
                    "x-api-key: ".$apikey,
                    "Content-Type: application/json"
                ),
            ));
                        
        $response = curl_exec($curl);
        $rubbies_generate = json_decode($response, true);
        
        if($rubbies_generate['responsecode'] == "00"){
            
            ($fetch_customernum == 1 && $fetch_agtnum == 0) ? $update = mysqli_query($link, "UPDATE borrowers SET card_id = '$pan', card_reg = 'Yes', card_issurer = 'VerveCard' WHERE virtual_acctno = '$cardholder'") or die ("Error: " . mysqli_error($link)) : "";

            ($fetch_customernum == 0 && $fetch_agtnum == 1) ? $update = mysqli_query($link, "UPDATE user SET card_id = '$pan', card_reg = 'Yes', card_issurer = 'VerveCard' WHERE virtual_acctno = '$cardholder'") or die ("Error: " . mysqli_error($link)) : "";
         
            echo "<div class='alert bg-blue'>".$rubbies_generate['responsemessage']." <b>Default Pin is:</b> ".$rubbies_generate['cardinfo']['defaultpin']."</div>";
        
        }
        else{
            
            echo "<div class='alert bg-orange'>".$rubbies_generate['error']['message']."</div>";
            
        }
	    
    }
    
}
?>           
			 <form class="form-horizontal" method="post" enctype="multipart/form-data">

             <div class="box-body">
			 
			<div class="form-group">
                <label for="" class="col-sm-3 control-label" style="color:blue;">New Cardholder:</label>
                <div class="col-sm-6">
                    <select name="cardholder" class="form-control select2" required>
                      <option value="" selected>Select New Cardholder</option>
                        <?php
                        $search = mysqli_query($link, "SELECT * FROM virtual_account WHERE virtual_acctno != '' AND card_reg = 'Yes' AND card_issurer = 'VerveCard' AND card_id = 'NULL'");
                        while($get_search = mysqli_fetch_array($search))
                        {
                            $userid = $get_search['account'];
                            $searchVA = mysqli_query($link, "SELECT * FROM virtual_account WHERE userid = '$userid'");
                            $fetch
                        ?>
                      <option value="<?php echo $get_search['virtual_acctno']; ?>"><?php echo $get_search['virtual_acctno'].' - '.$get_search['lname'].' '.$get_search['fname'].' '.$get_search['mname'].' - '.$get_search['bankname']; ?></option>
                        <?php } ?>

                        <?php
                        $get3 = mysqli_query($link, "SELECT * FROM user WHERE virtual_acctno != '' AND card_reg = 'Yes' AND card_issurer = 'VerveCard' AND card_id = 'NULL' ORDER BY userid DESC") or die (mysqli_error($link));
                        while($get_search = mysqli_fetch_array($get3))
                        {
                        ?>
                      <option value="<?php echo $get_search['virtual_acctno']; ?>"><?php echo $get_search['virtual_acctno']; ?> - <?php echo $get_search['lname'].' '.$get_search['name'].' '.$get_search['mname'].' - '.$get_search['bankname']; ?></option>
                        <?php } ?>
                </select>
                </div>
                <label for="" class="col-sm-3 control-label"></label>
            </div>
			
			<div class="form-group">
                <label for="" class="col-sm-3 control-label" style="color:blue;">Pan Number:</label>
                <div class="col-sm-6">
                  <input name="pan" type="text" class="form-control" placeholder="Enter the Card Pan" required>
                  <span style="color: orange;"> <b>Here, you need to enter the Card Pan Number.</span>
                  </div>
                <label for="" class="col-sm-3 control-label"></label>
            </div>
			
			<div class="form-group">
                <label for="" class="col-sm-3 control-label" style="color:blue;">Transaction Pin:</label>
                <div class="col-sm-6">
                  <input name="tpin" type="password" class="form-control" placeholder="Enter your transaction pin" maxlength="4" required>
                </div>
                <label for="" class="col-sm-3 control-label"></label>
            </div>
				  
			 </div>
			 
			<div class="form-group" align="right">
                <label for="" class="col-sm-3 control-label" style="color:blue;"></label>
                <div class="col-sm-6">
                	<button name="linkcard" type="submit" class="btn bg-blue"><i class="fa fa-link">&nbsp;Submit</i></button>
                </div>
                <label for="" class="col-sm-3 control-label"></label>
            </div>
			  
			 </form> 

</div>	
</div>	
</div>
</div>