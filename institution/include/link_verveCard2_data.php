<div class="box">
	       <div class="box-body">
			<div class="panel panel-success">
            <div class="panel-heading bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">
            <h3 class="panel-title">
            <button type="button" class="btn btn-flat bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>" align="left">&nbsp;<b>Transfer Wallet:</b>&nbsp;
                <strong class="alert bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>">
                <?php
                echo $icurrency.number_format($itransfer_balance,2,'.',',');
                ?> 
                </strong>
            </button>
            
            </h3>
            </div>



             <div class="box-body">

<div class="slideshow-container">
<div class="alert bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>" style="font-size:17px;" align="center">
    <p>
        <?php
        if($mo_virtualacct_status == "Active" && ($walletafrica_status == "NotActive" || $walletafrica_status == "Active")){
            
            include("../config/monnify_virtualaccount.php");
            
        }
        elseif($mo_virtualacct_status == "NotActive" && $walletafrica_status == "Active"){
            
            include("../config/walletafrica_restfulapis_call.php");
            include("walletafrica_virtulaccount.php");
            
        }
        ?>
    </p>
</div>

<a class="myprev" onclick="plusSlides(-1)">&#10094;</a>
<a class="mynext" onclick="plusSlides(1)">&#10095;</a>
</div> 
<hr>

 <?php
if(isset($_POST['linkcard']))
{
    $curl = curl_init();
	$cardholder =  mysqli_real_escape_string($link, $_POST['cardholder']);
	$pan = mysqli_real_escape_string($link, $_POST['pan']);
	$validatePan = preg_match("/^([506]{3})([0-9]{1,16})$/", $pan, $match);
	$tpin =  mysqli_real_escape_string($link, $_POST['tpin']);
	
	$search_customerbal = mysqli_query($link, "SELECT * FROM borrowers WHERE account = '$cardholder'");
    $fetch_customernum = mysqli_num_rows($search_customerbal);
    $fetch_customerbal = mysqli_fetch_array($search_customerbal);
    
    $search_agtbal = mysqli_query($link, "SELECT * FROM user WHERE id = '$cardholder'");
    $fetch_agtnum = mysqli_num_rows($search_agtbal);
	$fetch_agtbal = mysqli_fetch_array($search_agtbal);

	$cust_phone = ($fetch_customernum == 1 && $fetch_agtnum == 0) ? $fetch_customerbal['phone'] : $fetch_agtbal['phone'];
	
	if($myiepin != $tpin){
	    
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
            
            ($fetch_customernum == 1 && $fetch_agtnum == 0) ? $update = mysqli_query($link, "UPDATE borrowers SET card_id = '$pan', card_reg = 'Yes', card_issurer = 'VerveCard' WHERE account = '$cardholder'") or die ("Error: " . mysqli_error($link)) : "";

            ($fetch_customernum == 0 && $fetch_agtnum == 1) ? $update = mysqli_query($link, "UPDATE user SET card_id = '$pan', card_reg = 'Yes', card_issurer = 'VerveCard' WHERE id = '$cardholder'") or die ("Error: " . mysqli_error($link)) : "";
            
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
                    <select name="cardholder"  class="form-control select2" required>
                      <option value="" selected>Select New Cardholder</option>
                        <?php
                        (($individual_customer_records != "1" && $branch_customer_records != "1") || ($individual_wallet != "1" && $branch_wallet != "1")) ? $search = mysqli_query($link, "SELECT * FROM borrowers WHERE branchid = '$institution_id' AND virtual_acctno != '' AND card_reg = 'Yes' AND card_issurer = 'VerveCard' AND card_id = 'NULL'")  : "";
                        (($individual_customer_records === "1" && $branch_customer_records != "1") || ($individual_wallet === "1" && $branch_wallet != "1")) ? $search = mysqli_query($link, "SELECT * FROM borrowers WHERE branchid = '$institution_id' AND lofficer = '$iuid' AND virtual_acctno != '' AND card_reg = 'Yes' AND card_issurer = 'VerveCard' AND card_id = 'NULL'")  : "";
                        (($individual_customer_records != "1" && $branch_customer_records === "1") || ($individual_wallet != "1" && $branch_wallet === "1")) ? $search = mysqli_query($link, "SELECT * FROM borrowers WHERE branchid = '$institution_id' AND sbranchid = '$isbranchid' AND virtual_acctno != '' AND card_reg = 'Yes' AND card_issurer = 'VerveCard' AND card_id = 'NULL'")  : "";
                        while($get_search = mysqli_fetch_array($search))
                        {
                        ?>
                      <option value="<?php echo $get_search['virtual_acctno']; ?>"><?php echo $get_search['virtual_acctno']; ?> - <?php echo $get_search['fname']; ?> <?php echo $get_search['lname']; ?> <?php echo $get_search['mname']; ?></option>
                        <?php } ?>
                        
                        <?php
                        ($individual_wallet != "1" && $branch_wallet != "1") ? $get3 = mysqli_query($link, "SELECT * FROM user WHERE created_by = '$institution_id' AND virtual_acctno != '' AND card_reg = 'Yes' AND card_issurer = 'VerveCard' AND card_id = 'NULL' ORDER BY userid DESC") or die (mysqli_error($link)) : "";
                        ($individual_wallet === "1" && $branch_wallet != "1") ? $get3 = mysqli_query($link, "SELECT * FROM user WHERE created_by = '$institution_id' AND virtual_acctno != '' AND acctOfficer = '$iuid' AND card_reg = 'Yes' AND card_issurer = 'VerveCard' AND card_id = 'NULL' ORDER BY userid DESC") or die (mysqli_error($link)) : "";
                        ($individual_wallet != "1" && $branch_wallet === "1") ? $get3 = mysqli_query($link, "SELECT * FROM user WHERE created_by = '$institution_id' AND virtual_acctno != '' AND branchid = '$isbranchid' AND card_reg = 'Yes' AND card_issurer = 'VerveCard' AND card_id = 'NULL' ORDER BY userid DESC") or die (mysqli_error($link)) : "";
                        while($get_search = mysqli_fetch_array($get3))
                        {
                        ?>
                      <option value="<?php echo $get_search['virtual_acctno']; ?>"><?php echo $get_search['virtual_acctno']; ?> - <?php echo $get_search['name']; ?> <?php echo $get_search['lname']; ?> <?php echo $get_search['mname']; ?></option>
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