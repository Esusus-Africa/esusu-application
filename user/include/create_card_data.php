<div class="row">	
		
	 <section class="content">
		 
            <h3 class="panel-title"> 
            <a href="list_card.php?id=<?php echo $_SESSION['tid']; ?>&&mid=<?php echo base64_encode("550"); ?>"><button type="button" class="btn btn-flat bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>"><i class="fa fa-mail-reply-all"></i>&nbsp;Back</button></a>
            
            <button type="button" class="btn btn-flat bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>" align="left" disabled>&nbsp;<b>Super Wallet:</b>&nbsp;
                <strong class="alert bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">
                <?php
                echo $bbcurrency.number_format($bwallet_balance,2,'.',',');
                ?> 
                </strong>
            </button>
            </h3>
        
		   <div class="box box-success">
           <div class="box-body">
             <div class="table-responsive"> 
            <div class="box-body">

		 <div class="col-md-14">
            <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
             <li <?php echo ($_GET['tab'] == 'tab_1') ? "class='active'" : ''; ?>><a href="create_card.php?id=<?php echo $_SESSION['tid']; ?>&&mid=<?php echo base64_encode("900"); ?>&&tab=tab_1">Create Card</a></li>
             <li <?php echo ($_GET['tab'] == 'tab_2') ? "class='active'" : ''; ?>><a href="<?php echo (isset($_GET['aId']) == true) ? '' : '#'; ?>create_card.php?id=<?php echo $_SESSION['tid']; ?>&&mid=<?php echo base64_encode("900"); ?>&&aId=<?php echo $_GET['aId']; ?>&&tab=tab_2">View Card</a></li>
             <li <?php echo ($_GET['tab'] == 'tab_3') ? "class='active'" : ''; ?>><a href="<?php echo (isset($_GET['aId']) == true) ? '' : '#'; ?>create_card.php?id=<?php echo $_SESSION['tid']; ?>&&mid=<?php echo base64_encode("900"); ?>&&aId=<?php echo $_GET['aId']; ?>&&tab=tab_3">Fund Card</a></li>
             <li <?php echo ($_GET['tab'] == 'tab_4') ? "class='active'" : ''; ?>><a href="<?php echo (isset($_GET['aId']) == true) ? '' : '#'; ?>create_card.php?id=<?php echo $_SESSION['tid']; ?>&&mid=<?php echo base64_encode("900"); ?>&&aId=<?php echo $_GET['aId']; ?>&&tab=tab_4">Card Reports</a></li>
              </ul>
             <div class="tab-content">
<?php
if(isset($_GET['tab']) == true)
{
	$tab = $_GET['tab'];
	if($tab == 'tab_1')
	{
	?>
             <div class="tab-pane <?php echo ($_GET['tab'] == 'tab_1') ? 'active' : ''; ?>" id="tab_1">

<?php

if(isset($_POST['save']))
{
	include("../config/restful_apicalls.php");

	$result = array();
	$customer =  $acctno;
	$billing_addrs = mysqli_real_escape_string($link, $_POST['billing_addrs']);
	$billing_city = mysqli_real_escape_string($link, $_POST['billing_city']);
	$billing_state = mysqli_real_escape_string($link, $_POST['billing_state']);
	$postalcode = mysqli_real_escape_string($link, $_POST['postalcode']);
	$billing_country = mysqli_real_escape_string($link, $_POST['billing_country']);
	$currency_type =  mysqli_real_escape_string($link, $_POST['currency_type']);
	$amount =  preg_replace("/[^0-9]/", "", mysqli_real_escape_string($link, $_POST['amount']));
	$secureoption =  "pin";
	$pin =  mysqli_real_escape_string($link, $_POST['tpin']);
	$refid = "EA-preFundCard-".mt_rand(10000,99999);

	$systemset = mysqli_query($link, "SELECT * FROM systemset");
	$row1 = mysqli_fetch_object($systemset);
	$seckey = $row1->secret_key;
    $sysabb = $row1->abb;
    
    //Currency conversion
    $search_restapi = mysqli_query($link, "SELECT * FROM restful_apisetup WHERE api_name = 'Exchange_Rate'");
	$fetch_restapi = mysqli_fetch_object($search_restapi);
	$api_url = $fetch_restapi->api_url;
	
	// Pass the parameter here
	$postdata =  array(
	    "secret_key"	=>	$rave_secret_key,
	    "service"       =>  "rates_convert",
	    "service_method"    =>  "post",
	    "service_version"   =>  "v1",
	    "service_channel"   =>  "transactions",
	    "service_channel_group" =>  "merchants",
	    "service_payload"   =>  [
	        "FromCurrency"  =>  $bbcurrency,
	        "ToCurrency"    =>  $currency_type,
	        "Amount"        =>  $amount
	        ]
	    );
	    
	$make_call = callAPI('POST', $api_url, json_encode($postdata));
	$result = json_decode($make_call, true);
    
    $convertedAmount = $result['data']['ToAmount'];

    if($convertedAmount > $bwallet_balance){

        echo '<meta http-equiv="refresh" content="5;url=create_card?id='.$_SESSION['tid'].'&&mid='.base64_encode("900").'&&tab=tab_1">';
        echo '<hr><span class="alert bg-orange">Opps!...You have Insufficient Balance in your Wallet!! Expected Balance is: '.$bbcurrency.number_format($convertedAmount,2,'.',',').'</span><hr>';
        
    }
    elseif($pin != $myuepin){

        echo '<meta http-equiv="refresh" content="5;url=create_card?id='.$_SESSION['tid'].'&&mid='.base64_encode("900").'&&tab=tab_1">';
        echo '<hr><span class="alert bg-orange">Opps!...Invalid Transaction Pin Entered!!</span><hr>';

    }
    else{

        $api_name =  "create-virtualcards";
        $search_restapi = mysqli_query($link, "SELECT * FROM atmcard_gateway_apis WHERE api_name = '$api_name' AND issuer_name = 'Flutterwave'");
        $fetch_restapi = mysqli_fetch_object($search_restapi);
        $api_url = $fetch_restapi->api_url;
        $issuer_name = $fetch_restapi->issuer_name;
        
        // Pass the parameter here
        $postdata =  array(
            "secret_key"	=>	$seckey,
            "currency"		=>	$currency_type,
            "amount"		=> 	$amount,
            "billing_name"	=>	$myfn.' '.$myln,
            "billing_address"	=>	$billing_addrs,
            "billing_city"	=>	$billing_city,
            "billing_state"	=>	$billing_state,
            "billing_postal_code"	=>	$postalcode,
            "billing_country"	=> $billing_country,
            "callback_url"	=> "https://esusu.app/cron/sub_signal.php?cu".$customer
        );
        
        $make_call = callAPI('POST', $api_url, json_encode($postdata));
        $result = json_decode($make_call, true);
            
        //var_dump($result);
        
        if($result['status'] == "success")
        {
            $id = $result['data']['id'];
            $AccountId = $result['data']['AccountId'];
            $card_hash = $result['data']['card_hash'];
            $cardpan = $result['data']['cardpan'];
            $maskedpan = $result['data']['maskedpan'];
            $cvv = $result['data']['cvv'];
            $expiration = $result['data']['expiration'];
            $card_type = $result['data']['card_type'];
            $name_on_card = $result['data']['name_on_card'];
            $is_active = $result['data']['is_active'];
            $total_walletbal = $bwallet_balance - $convertedAmount;
        
            $accno = ccMasking($cardpan);
        
            $date_time = date("Y-m-d h:i:s");
            $utc_date = DateTime::createFromFormat('Y-m-d G:i:s',$date_time,new DateTimeZone(date_default_timezone_get()));
            $acst_date = clone $utc_date; // we don't want PHP's default pass object by reference here
            $acst_date->setTimeZone(new DateTimeZone('GMT+1'));
            $correctdate = $acst_date->format('Y-m-d g:i A');
            $postedby = "Creating $card_type of Pan Number: $maskedpan with a Prefund Amount: $currency_type".number_format($convertedAmount,2,'.',',');
        
            $insert = mysqli_query($link, "INSERT INTO card_enrollment VALUES(null,'$bbranchid','$bsbranchid','$customer','$currency_type','$amount','$phone2','$billing_addrs','$billing_country','$id','$AccountId','$card_hash','$cardpan','$maskedpan','$cvv','$expiration','$card_type','$name_on_card','$issuer_name','$secureoption','$pin','$is_active','$date_time')");
            $insert = mysqli_query($link, "INSERT INTO wallet_history VALUES(null,'$institution_id','$refid','$bvirtual_acctno','','$amount','Debit','$currency_type','preFundCard','$postedby','successful','$date_time','$customer','$itransfer_balance','')");
            $insert = mysqli_query($link, "UPDATE borrowers SET wallet_balance = '$total_walletbal' WHERE account = '$customer'");

            /**$sms = "$sysabb>>>CR";
            $sms .= " Amt: ".$currency_type.number_format($amount,2,'.',',')."";
            $sms .= " Acc: ".$accno."";
            $sms .= " Desc: Prefund Card ";
            $sms .= " Time: ".$correctdate."";
            $sms .= " Wallet Bal: ".$mycurrency.number_format($total_walletbal,2,'.',',')."";**/
        
            //include("../cron/send_general_sms.php");
            echo '<meta http-equiv="refresh" content="5;url=create_card?id='.$_SESSION['tid'].'&&mid='.base64_encode("900").'&&aId='.$AccountId.'&&tab=tab_2">';
            echo '<br>';
            echo'<span class="itext" style="color: blue;">Card Created Successfully!!</span>';
        
        }
        else{
            echo '<meta http-equiv="refresh" content="5;url=create_card?id='.$_SESSION['tid'].'&&mid='.base64_encode("900").'&&tab=tab_1">';
            echo '<br>';
            echo'<span class="itext" style="color: orange;">'.$result['Message'].'</span>';
        }

    }
		
}
?>			 
            
             <form class="form-horizontal" method="post" enctype="multipart/form-data">
             
             <div class="box-body">

                <div class="form-group">
                    <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Amount to Prefund</label>
                    <div class="col-sm-6">
                        <input name="amount" type="number" class="form-control" placeholder="Enter Amount Here" required/>
                        <span style="color: <?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>;"> <b>Enter the Amount to Prefund the Card with upon creation.</span>
                    </div>
                    <label for="" class="col-sm-3 control-label"></label>
                </div>

                <div class="form-group">
                    <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Billing Address</label>
                    <div class="col-sm-6">
                    <textarea name="billing_addrs"  class="form-control" rows="2" cols="80" maxlength="26" required><?php echo $baddrs; ?></textarea>
                    </div>
                    <label for="" class="col-sm-3 control-label"></label>
                </div>

                <div class="form-group">
                    <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Billing City</label>
                    <div class="col-sm-6">
                    <input name="billing_city" type="text" class="form-control" value="<?php echo $bcity; ?>" placeholder="Billing City" required>
                    </div>
                    <label for="" class="col-sm-3 control-label"></label>
                </div>

                <div class="form-group">
                    <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Billing State</label>
                    <div class="col-sm-6">
                    <input name="billing_state" type="text" class="form-control" value="<?php echo $bstate; ?>" placeholder="Billing State" required>
                    </div>
                    <label for="" class="col-sm-3 control-label"></label>
                </div>

                <div class="form-group">
                    <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Billing Postal/Zip Code</label>
                    <div class="col-sm-6">
                    <input name="postalcode" type="text" class="form-control" value="<?php echo $bzip; ?>" placeholder="Billing Postal/Zip Code" required>
                    </div>
                    <label for="" class="col-sm-3 control-label"></label>
                </div>

                <div class="form-group">
                    <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Billing Country</label>
                    <div class="col-sm-6">
                    <select name="billing_country"  class="form-control select2" required>
						<option value='<?php echo $bcountry; ?>' selected='selected'><?php echo $bcountry; ?></option>
						<option value="NG">NG</option>
						<option value="US">US</option>
				    </select>
                    </div>
                    <label for="" class="col-sm-3 control-label"></label>
                </div>

                <div class="form-group">
                    <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Currency</label>
                    <div class="col-sm-6">
                    <select name="currency_type"  class="form-control select2" required>
						<option value='' selected='selected'>Select Currency Type&hellip;</option>
						<option value="NGN">NGN</option>
						<option value="USD">USD</option>
                    </select>
                    </div>
                    <label for="" class="col-sm-3 control-label"></label>
                </div>


                
                <div class="form-group">
                    <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Transaction Pin</label>
                    <div class="col-sm-6">
                        <input name="tpin" type="password" class="form-control" maxlength="4" autocomplete="off" placeholder="Transaction Pin" required/>
                    </div>
                    <label for="" class="col-sm-3 control-label"></label>
                </div>

             </div>
             
             <div class="form-group" align="right">
                <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;"></label>
                <div class="col-sm-6">
                    <button name="save" type="submit" class="btn bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?> btn-flat"><i class="fa fa-spinner">&nbsp;Create</i></button>
                </div>
                <label for="" class="col-sm-3 control-label"></label>
            </div>
			 
			 </form> 
			 
              </div>

    <?php
	}
	elseif($tab == 'tab_2')
	{
	?>

		<div class="tab-pane <?php echo ($_GET['tab'] == 'tab_2') ? 'active' : ''; ?>" id="tab_2">
					   
		<div class="box-body">

<?php
if(isset($_GET['aId']) == true){
    
    include("../config/restful_apicalls.php");
    
    $result2 = array();
    
	$aId = $_GET['aId'];
	$search_card = mysqli_query($link, "SELECT * FROM card_enrollment WHERE account_id = '$aId'");
    $fetch_card = mysqli_fetch_object($search_card);
    $cardid = $fetch_card->card_id;

    $api_name2 =  "get-virtualcards";
    $search_restapi2 = mysqli_query($link, "SELECT * FROM atmcard_gateway_apis WHERE api_name = '$api_name2' AND issuer_name = 'Flutterwave'");
    $fetch_restapi2 = mysqli_fetch_object($search_restapi2);
    $api_url2 = $fetch_restapi2->api_url;

    // Pass the parameter here
    $postdata2 =  array(
        "secret_key" =>	$rave_secret_key,
        "id" =>	$cardid
    );
    
    $make_call2 = callAPI('POST', $api_url2, json_encode($postdata2));
    $result2 = json_decode($make_call2, true);
    
    //print_r($postdata2);
?>
    
    <div align="center">
    
     <div class="payment-title">
        <h1>
            <button type="button" class="btn btn-flat bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>" align="left" disabled>&nbsp;<b>Card Balance:</b>&nbsp;
                <strong class="alert bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">
                <?php
                echo ($result2['status'] == "success") ? $result2['data']['currency'].number_format($result2['data']['amount'],2,'.',',') : "Loading...";
                ?> 
                </strong>
            </button>
        </h1>
    </div>
    <div class="container preload">
        <div class="creditcard">
            <div class="front">
                <div id="ccsingle"></div>
                <svg version="1.1" id="cardfront" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                    x="0px" y="0px" viewBox="0 0 750 471" style="enable-background:new 0 0 750 471;" xml:space="preserve">
                    <g id="Front">
                        <g id="CardBackground">
                            <g id="Page-1_1_">
                                <g id="amex_1_">
                                    <path id="Rectangle-1_1_" class="lightcolor grey" d="M40,0h670c22.1,0,40,17.9,40,40v391c0,22.1-17.9,40-40,40H40c-22.1,0-40-17.9-40-40V40
                            C0,17.9,17.9,0,40,0z" />
                                </g>
                            </g>
                            <path class="darkcolor greydark" d="M750,431V193.2c-217.6-57.5-556.4-13.5-750,24.9V431c0,22.1,17.9,40,40,40h670C732.1,471,750,453.1,750,431z" />
                        </g>
                        <text transform="matrix(1 0 0 1 60.106 295.0121)" id="svgnumber" class="st2 st3 st4">**** **** **** ****</text>
                        <text transform="matrix(1 0 0 1 54.1064 428.1723)" id="svgname" class="st2 st5 st6"><?php echo $fetch_card->name_on_card; ?></text>
                        <text transform="matrix(1 0 0 1 54.1074 389.8793)" class="st7 st5 st8">cardholder name</text>
                        <text transform="matrix(1 0 0 1 479.7754 388.8793)" class="st7 st5 st8">expiration</text>
                        <text transform="matrix(1 0 0 1 65.1054 241.5)" class="st7 st5 st8">card number</text>
                        <g>
                            <text transform="matrix(1 0 0 1 574.4219 433.8095)" id="svgexpire" class="st2 st5 st9"><?php echo date('m/y', strtotime($fetch_card->expiration)); ?></text>
                            <text transform="matrix(1 0 0 1 479.3848 417.0097)" class="st2 st10 st11">VALID</text>
                            <text transform="matrix(1 0 0 1 479.3848 435.6762)" class="st2 st10 st11">THRU</text>
                            <polygon class="st2" points="554.5,421 540.4,414.2 540.4,427.9 		" />
                        </g>
                        <g id="cchip">
                            <g>
                                <path class="st2" d="M168.1,143.6H82.9c-10.2,0-18.5-8.3-18.5-18.5V74.9c0-10.2,8.3-18.5,18.5-18.5h85.3
                        c10.2,0,18.5,8.3,18.5,18.5v50.2C186.6,135.3,178.3,143.6,168.1,143.6z" />
                            </g>
                            <g>
                                <g>
                                    <rect x="82" y="70" class="st12" width="1.5" height="60" />
                                </g>
                                <g>
                                    <rect x="167.4" y="70" class="st12" width="1.5" height="60" />
                                </g>
                                <g>
                                    <path class="st12" d="M125.5,130.8c-10.2,0-18.5-8.3-18.5-18.5c0-4.6,1.7-8.9,4.7-12.3c-3-3.4-4.7-7.7-4.7-12.3
                            c0-10.2,8.3-18.5,18.5-18.5s18.5,8.3,18.5,18.5c0,4.6-1.7,8.9-4.7,12.3c3,3.4,4.7,7.7,4.7,12.3
                            C143.9,122.5,135.7,130.8,125.5,130.8z M125.5,70.8c-9.3,0-16.9,7.6-16.9,16.9c0,4.4,1.7,8.6,4.8,11.8l0.5,0.5l-0.5,0.5
                            c-3.1,3.2-4.8,7.4-4.8,11.8c0,9.3,7.6,16.9,16.9,16.9s16.9-7.6,16.9-16.9c0-4.4-1.7-8.6-4.8-11.8l-0.5-0.5l0.5-0.5
                            c3.1-3.2,4.8-7.4,4.8-11.8C142.4,78.4,134.8,70.8,125.5,70.8z" />
                                </g>
                                <g>
                                    <rect x="82.8" y="82.1" class="st12" width="25.8" height="1.5" />
                                </g>
                                <g>
                                    <rect x="82.8" y="117.9" class="st12" width="26.1" height="1.5" />
                                </g>
                                <g>
                                    <rect x="142.4" y="82.1" class="st12" width="25.8" height="1.5" />
                                </g>
                                <g>
                                    <rect x="142" y="117.9" class="st12" width="26.2" height="1.5" />
                                </g>
                            </g>
                        </g>
                    </g>
                    <g id="Back">
                    </g>
                </svg>
            </div>
            <div class="back">
                <svg version="1.1" id="cardback" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                    x="0px" y="0px" viewBox="0 0 750 471" style="enable-background:new 0 0 750 471;" xml:space="preserve">
                    <g id="Front">
                        <line class="st0" x1="35.3" y1="10.4" x2="36.7" y2="11" />
                    </g>
                    <g id="Back">
                        <g id="Page-1_2_">
                            <g id="amex_2_">
                                <path id="Rectangle-1_2_" class="darkcolor greydark" d="M40,0h670c22.1,0,40,17.9,40,40v391c0,22.1-17.9,40-40,40H40c-22.1,0-40-17.9-40-40V40
                        C0,17.9,17.9,0,40,0z" />
                            </g>
                        </g>
                        <rect y="61.6" class="st2" width="750" height="78" />
                        <g>
                            <path class="st3" d="M701.1,249.1H48.9c-3.3,0-6-2.7-6-6v-52.5c0-3.3,2.7-6,6-6h652.1c3.3,0,6,2.7,6,6v52.5
                    C707.1,246.4,704.4,249.1,701.1,249.1z" />
                            <rect x="42.9" y="198.6" class="st4" width="664.1" height="10.5" />
                            <rect x="42.9" y="224.5" class="st4" width="664.1" height="10.5" />
                            <path class="st5" d="M701.1,184.6H618h-8h-10v64.5h10h8h83.1c3.3,0,6-2.7,6-6v-52.5C707.1,187.3,704.4,184.6,701.1,184.6z" />
                        </g>
                        <text transform="matrix(1 0 0 1 621.999 227.2734)" id="svgsecurity" class="st6 st7"><?php echo $fetch_card->cvv; ?></text>
                        <g class="st8">
                            <text transform="matrix(1 0 0 1 518.083 280.0879)" class="st9 st6 st10">security code</text>
                        </g>
                        <rect x="58.1" y="378.6" class="st11" width="375.5" height="13.5" />
                        <rect x="58.1" y="405.6" class="st11" width="421.7" height="13.5" />
                        <text transform="matrix(1 0 0 1 59.5073 228.6099)" id="svgnameback" class="st12 st13"><?php echo $fetch_card->name_on_card; ?></text>
                    </g>
                </svg>
            </div>
        </div>
    </div>
    <div class="form-container">
        
            <input id="name" maxlength="20" type="hidden">
       
        <div class="field-container">
            <span id="generatecard">VIEW CARD NUMBER</span>
            <input id="cardnumber" type="text" pattern="[0-9]*" inputmode="numeric" readonly>
            <svg id="ccicon" class="ccicon" width="750" height="471" viewBox="0 0 750 471" version="1.1" xmlns="http://www.w3.org/2000/svg"
                xmlns:xlink="http://www.w3.org/1999/xlink">

            </svg>
        </div>
            <input id="expirationdate" type="hidden" pattern="[0-9]*" inputmode="numeric">
            <input id="securitycode" type="hidden" pattern="[0-9]*" inputmode="numeric">
    </div>
    
    </div>
    

<?php
}
else{
	echo "<div class='alert bg-orange'>Sorry!...No Card to View!!</div>";
}
?>
		</div>
		</div>
              <!-- /.tab-pane -->

    <?php
	}
	elseif($tab == 'tab_3')
	{
	?>

		<div class="tab-pane <?php echo ($_GET['tab'] == 'tab_3') ? 'active' : ''; ?>" id="tab_3">
					   
            <div class="box-body">

            <form class="form-horizontal" method="post" enctype="multipart/form-data">


<?php
if(isset($_POST['fundCard']))
{
    include("../config/restful_apicalls.php");

    $result = array();
    $result2 = array();
    $reference = date("yd").time();
    $acctid = $_GET['aId'];
    $amountWithNoCharges = preg_replace("/[^0-9]/", "", mysqli_real_escape_string($link, $_POST['amount']));
    $currenctdate = date("Y-m-d H:i:s");

    $search_card = mysqli_query($link, "SELECT * FROM card_enrollment WHERE account_id = '$acctid'");
    $fetch_card = mysqli_fetch_array($search_card);
    $card_id = $fetch_card['card_id'];
    $currency_type = $fetch_card['currency_type'];
    
    $query = mysqli_query($link, "SELECT * FROM systemset") or die (mysqli_error($link));
    $r = mysqli_fetch_object($query);
    $sysabb = $bsender_id;
    $smsfee = $fetchsys_config['fax'];
    
    $otp_code = ($selfServiceTransAuth == "On" || $otp_option == "Yes" || $otp_option == "Both") ? substr((uniqid(rand(),1)),3,6) : $myuepin;
    
    $otpChecker = ($selfServiceTransAuth == "On" || $otp_option == "Yes" || $otp_option == "Both") ? "otp" : "pin";
								
	$sms = "$sysabb>>>Dear $myln! Your One Time Password is $otp_code";
								
	//SMS DATA
	$search_gateway = mysqli_query($link, "SELECT * FROM sms WHERE smsuser = '' AND status = 'Activated'");
    $fetch_gateway = mysqli_fetch_object($search_gateway);
    $gateway_uname = $fetch_gateway->username;
    $gateway_pass = $fetch_gateway->password;
    $gateway_api = $fetch_gateway->api;
    
    $search_insti = mysqli_query($link, "SELECT * FROM institution_data WHERE institution_id = '$bbranchid'");
    $fetch_myinst = mysqli_fetch_array($search_insti);
    $iwallet_balance = $fetch_myinst['wallet_balance'];

    //Currency conversion
    $search_restapi = mysqli_query($link, "SELECT * FROM restful_apisetup WHERE api_name = 'Exchange_Rate'");
	$fetch_restapi = mysqli_fetch_object($search_restapi);
	$api_url = $fetch_restapi->api_url;
	
	// CONVERT AMOUNT TO FUND HERE
	$postdata =  array(
	    "secret_key"	=>	$rave_secret_key,
	    "service"       =>  "rates_convert",
	    "service_method"    =>  "post",
	    "service_version"   =>  "v1",
	    "service_channel"   =>  "transactions",
	    "service_channel_group" =>  "merchants",
	    "service_payload"   =>  [
	        "FromCurrency"  =>  $currency_type,
	        "ToCurrency"    =>  $bbcurrency,
	        "Amount"        =>  $amountWithNoCharges
	        ]
	    );
	    
	$make_call = callAPI('POST', $api_url, json_encode($postdata));
	$result = json_decode($make_call, true);
    $convertedWithNoChargesAmount = $result['data']['ToAmount'];

    //CONVERT CHARGES HERE
	$postdata2 =  array(
	    "secret_key"	=>	$rave_secret_key,
	    "service"       =>  "rates_convert",
	    "service_method"    =>  "post",
	    "service_version"   =>  "v1",
	    "service_channel"   =>  "transactions",
	    "service_channel_group" =>  "merchants",
	    "service_payload"   =>  [
	        "FromCurrency"  =>  "NGN",
	        "ToCurrency"    =>  $bbcurrency,
	        "Amount"        =>  $transferToCardCharges
	        ]
	    );
	    
	$make_call2 = callAPI('POST', $api_url2, json_encode($postdata2));
	$result2 = json_decode($make_call2, true);
    $convertedTransferToCardCharges = $result2['data']['ToAmount'];

    $sms_rate = $r->fax;
    $imywallet_balance = $iwallet_balance - $sms_rate - $smsfee;
    $sms_refid = "EA-smsCharges-".time();
	
	//New AMount + Charges
    $amountWithCharges = $convertedWithNoChargesAmount + $convertedTransferToCardCharges;
	
	//Data Parser (array size = 2)
    $mydata = $reference."|".$convertedWithNoChargesAmount."|".$amountWithCharges."|".$card_id."|".$acctid;
    
    if($bwallet_balance < $amountWithCharges){
	    
	    echo "<div class='alert bg-orange'>Oops! You have Insufficient Fund in your Wallet!!</div>";
	    
	}
	else{
	    
	    $update = mysqli_query($link,"INSERT INTO otp_confirmation VALUES(null,'$acctno','$otp_code','$mydata','Pending','$currenctdate')")or die(mysqli_error());
        ($selfServiceTransAuth == "On" || $otp_option == "Yes" || $otp_option == "Both") ? $insert2 = mysqli_query($link, "INSERT INTO wallet_history VALUE(null,'$bbranchid','$sms_refid','$phone2','','$sms_rate','Debit','NGN','Charges','SMS Content: $sms','successful','$currenctdate','$acctno','$iwallet_balance','')") : "";
        ($selfServiceTransAuth == "On" || $otp_option == "Yes" || $otp_option == "Both") ? $update2 = mysqli_query($link, "UPDATE institution_data SET wallet_balance = '$imywallet_balance' WHERE institution_id = '$bbranchid'") : "";
            
        if(!$update)
        {
            echo "<div class='alert bg-orange'>Unable to Transfer Fund.....Please try again later</div>";
        }
        else{
            ($selfServiceTransAuth == "On" || $otp_option == "Yes" || $otp_option == "Both") ? include("../cron/send_general_sms.php") : "";
            echo ($selfServiceTransAuth == "On" || $otp_option == "Yes" || $otp_option == "Both") ? "<div align='center'><img src='../image/checkmark.gif'><p style='color: #38A1F3;'>Great! Otp has been sent to your mobile phone to complete this transaction!!</p></div>" : "<div align='center'><img src='../image/checkmark.gif'><p style='color: #38A1F3;'>Transfer Request Received Successfully! Wait a moment to complete the transaction with your Pin</p></div>";
            echo '<meta http-equiv="refresh" content="2;url=create_card.php?tid='.$_SESSION['tid'].'&&acn='.$acctno.'&&mid=OTAw&&aId='.$acctid.'&&tab=tab_3&&'.$otpChecker.'">';
        }
        
	}
	
}
?>


<?php
if (isset($_POST['confirm']))
{
    
    include("../config/restful_apicalls.php");
    
    $result3 = array();
    $myotp = $_POST['otp'];
    $isenderid = $bsender_id;
    
    $verify_otp = mysqli_query($link, "SELECT * FROM otp_confirmation WHERE otp_code = '$myotp' AND userid = '$acctno' AND status = 'Pending'");
	$fetch_data = mysqli_fetch_array($verify_otp);
	$otpnum = mysqli_num_rows($verify_otp);
	
	if($otpnum == 0){
						        
	    echo "<div class='alert bg-orange'>Opps!...Invalid Credential!!</div>";
						        
	}else{
	    
	    $concat = $fetch_data['data'];
    
        $datetime = $fetch_data['datetime'];
                                
        $parameter = (explode('|',$concat));
        
        $reference = $parameter[0];
        $amountWithNoCharges = preg_replace("/[^0-9]/", "", number_format($parameter[1],0,'',''));
        $amountWithCharges = $parameter[2];
        $cardid = $parameter[3];
        $acctid = $parameter[4];

        $calcCharges = $amountWithCharges - $amountWithNoCharges;
        
        $search_gateway = mysqli_query($link, "SELECT * FROM sms WHERE smsuser = '' AND status = 'Activated'");
        $fetch_gateway = mysqli_fetch_object($search_gateway);
        $gateway_uname = $fetch_gateway->username;
        $gateway_pass = $fetch_gateway->password;
        $gateway_api = $fetch_gateway->api;
          
        //Get my wallet balance after debiting
        $senderBalance = $bwallet_balance - $amountWithCharges;
            
        $api_name3 =  "fund-virtualcards";
        $search_restapi3 = mysqli_query($link, "SELECT * FROM atmcard_gateway_apis WHERE api_name = '$api_name2' AND issuer_name = 'Flutterwave'");
        $fetch_restapi3 = mysqli_fetch_object($search_restapi3);
        $api_url3 = $fetch_restapi3->api_url;
            
        $postdata3 =  array(
            "id" => $cardid,
            "amount" => $amountWithNoCharges,
            "debit_currency" => "NGN",
            "secret_key" => $rave_secret_key
        );
                               
        $make_call3 = callAPI('POST', $api_url3, json_encode($postdata3));
        $result3 = json_decode($make_call3, true);
            
        if($result3['Status'] == "success"){

            $transactionDateTime = date("Y-m-d H:i:s");
            //$result3['Reference']
                
            //Customer Details
            $pan = $cardid;
            $cust_fname = $myfn;
            $cust_lname = $myln;
            $cust_email = $email2;
                
            //$sms = "$isenderid>>>Dear $cust_lname, Your Verve card with Pan Number ".panNumberMasking($pan)." has been credited with $bbcurrency".number_format($amountWithNoCharges,2,'.',',')." ";
            //$sms .= "Time ".date('m/d/Y').' '.(date(h) + 1).':'.date('i a')."";
                
            $currenctdate = date("Y-m-d H:i:s");
    
            //$message = ($card_id == "NULL") ? "Card Pin is: ".$pin : "";
                        
            mysqli_query($link, "UPDATE borrowers SET wallet_balance = '$senderBalance' WHERE account = '$acctno'");
                    
            mysqli_query($link, "INSERT INTO wallet_history VALUE(null,'$bbranchid','$reference','self','','$amountWithNoCharges','Debit','$bbcurrency','Topup-Prepaid_Card','Response: Card was Topup with $bbcurrency.$amountWithNoCharges','successful','$currenctdate','$acctno','$bwallet_balance','')");
                    
            mysqli_query($link, "INSERT INTO wallet_history VALUE(null,'$bbranchid','$reference','self','','$calcCharges','Debit','$bbcurrency','Stamp Duty','Response: Card was Topup with $bbcurrency.$amountWithNoCharges','successful','$currenctdate','$acctno','$bwallet_balance','')");
            
            mysqli_query($link, "UPDATE otp_confirmation SET status = 'Verified' WHERE userid = '$acctno' AND otp_code = '$myotp' AND status = 'Pending'");
                    
            //include("../cron/mygeneral_sms.php");
                            
            //$debug = true;
            //sendSms($isenderid,$phone2,$sms,$debug);
                            
            echo '<meta http-equiv="refresh" content="5;url=create_card.php?id='.$_SESSION['tid'].'&&acn='.$acctno.'&&mid=OTAw&&aId='.$acctid.'&&tab=tab_2">';
            echo '<br>';
            echo '<span class="itext" style="color: blue;">'.$result3['Message'].'</span>';
                
        }
        else{
            
            echo "<div class='alert bg-orange'>Opps!...Network Error, Please Try again later</div>";
            
        }
        
	}
	
}
?>


<?php
if(!(isset($_GET['otp']) || isset($_GET['pin'])))
{
?>
            <div class="box-body">
                
                <div class="form-group">
                    <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Amount</label>
                    <div class="col-sm-6">
                        <input name="amount" type="number" class="form-control" placeholder="Enter Amount to Fund" /required>
                    </div>
                    <label for="" class="col-sm-3 control-label"></label>
                </div>

            </div>
            
            <div class="form-group" align="right">
                <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;"></label>
                <div class="col-sm-6">
                    <button name="fundCard" type="submit" class="btn bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?> btn-flat" <?php echo ($card_id != "NULL") ? "" : "disabled"; ?>><i class="fa fa-upload">&nbsp;Fund</i></button>
                </div>
                <label for="" class="col-sm-3 control-label"></label>
             </div>
             
<?php
}
else{
    include("otp_confirmation.php");
}
?>

      </form>

            </div>

        </div>


    <!-- /.tab-pane -->
	<?php
	}
	elseif($tab == 'tab_4')
	{
	?>

		<div class="tab-pane <?php echo ($_GET['tab'] == 'tab_4') ? 'active' : ''; ?>" id="tab_4">
					   
		<div class="box-body">

			<form class="form-horizontal" method="post" enctype="multipart/form-data">
			    
<div class="alert bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>">
<b>NOTE</b> that any card reports triggered on our system attract <b><?php echo "NGN".$fetchsys_config['report_charges']; ?></b> per request
</div>
<hr>      
             <div class="box-body">			
			
				<div class="form-group">
                  <label for="" class="col-sm-1 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">From</label>
                  <div class="col-sm-3">
                  <input name="dfrom" type="date" class="form-control" placeholder="To Date: 2018-05-01" required>
                  </div>
				  
				  <label for="" class="col-sm-1 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">End Date</label>
                  <div class="col-sm-3">
                  <input name="dto" type="date" class="form-control" placeholder="To Date: 2018-05-24" required>
                  </div>

                  <label for="" class="col-sm-1 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Page Size</label>
                  <div class="col-sm-3">
                  <input name="tcounts" type="number" class="form-control" placeholder="Specify how many transactions you want to retrieve">
                  </div>
             </div>

			 </div>
			 
			<div align="right">
              <div class="box-footer">
                	<button name="search" type="submit" class="btn bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?> btn-flat"><i class="fa fa-filter">&nbsp;Search</i></button>
              </div>
			</div>

<?php
if(isset($_POST['search']))
{	
    include("../config/restful_apicalls.php");
    
    $result = array();
    $result2 = array();
    $acctid = $_GET['aId'];
    $dfrom = mysqli_real_escape_string($link, $_POST['dfrom']);
    $StartDate = date("Y-m-d", strtotime($dfrom));
    	
    $dto = mysqli_real_escape_string($link, $_POST['dto']);
    $EndDate = date("Y-m-d", strtotime($dto));
    	
    $tcounts =  mysqli_real_escape_string($link, $_POST['tcounts']); //TRANSACTION COUNT
    $report_charges = $fetchsys_config['report_charges'];

    $search_card = mysqli_query($link, "SELECT * FROM card_enrollment WHERE account_id = '$acctid'");
    $fetch_card = mysqli_fetch_array($search_card);
    $card_id = $fetch_card['card_id'];
    $currency_type = $fetch_card['currency_type'];
    $masked_pan = $fetch_card['maskedpan'];

    //Currency conversion
    $search_restapi = mysqli_query($link, "SELECT * FROM restful_apisetup WHERE api_name = 'Exchange_Rate'");
	$fetch_restapi = mysqli_fetch_object($search_restapi);
	$api_url = $fetch_restapi->api_url;
	
	// CONVERT AMOUNT TO FUND HERE
	$postdata =  array(
	    "secret_key"	=>	$rave_secret_key,
	    "service"       =>  "rates_convert",
	    "service_method"    =>  "post",
	    "service_version"   =>  "v1",
	    "service_channel"   =>  "transactions",
	    "service_channel_group" =>  "merchants",
	    "service_payload"   =>  [
	        "FromCurrency"  =>  "NGN",
	        "ToCurrency"    =>  $bbcurrency,
	        "Amount"        =>  $report_charges
	        ]
	    );
	    
	$make_call = callAPI('POST', $api_url, json_encode($postdata));
	$result = json_decode($make_call, true);
    $convertedAmount = $result['data']['ToAmount'];
            
    if($bwallet_balance < $convertedAmount){
              
        echo "<script>alert('Oops!..Insufficient fund!! Expected Balance: $bbcurrency$convertedAmount'); </script>";
    	echo "<script>window.location='create_card?id=".$_SESSION['tid']."&&mid=".base64_encode('900')."&&aId=".$_GET['aId']."&&tab=tab_4'; </script>";
    			
    }else{

        $api_name =  "fetch-card-transactions";
        $search_restapi2 = mysqli_query($link, "SELECT * FROM atmcard_gateway_apis WHERE api_name = '$api_name' AND issuer_name = 'Flutterwave'");
        $fetch_restapi2 = mysqli_fetch_object($search_restapi2);
        $api_url2 = $fetch_restapi2->api_url;

        $postdata2 =  array(
            "FromDate" => $StartDate,
            "ToDate" => $EndDate,
            "PageIndex" => 0,
            "PageSize" => ($tcounts == "") ? 100 : $tcounts,
            "CardId" => $card_id,
            "secret_key" => $rave_secret_key
        );
                               
        $make_call2 = callAPI('POST', $api_url2, json_encode($postdata2));
        $result2 = json_decode($make_call2, true);

        //REPORT CHARGES
        $rOrderID = "EA-rCharges-".mt_rand(30000000,99999999);
        $calc_mywalletBalance = $bwallet_balance - $convertedAmount;
        $currentdate = date("Y-m-d h:i:s");
		
        mysqli_query($link, "UPDATE borrowers SET wallet_balance = '$calc_mywalletBalance' WHERE account = '$acctno'");
            	  
        mysqli_query($link, "INSERT INTO wallet_history VALUE(null,'$bbranchid','$rOrderID','self','','$report_charges','Debit','$bbcurrency','Report_Charges','Response: $bbcurrency.$convertedAmount was charged for triggering report for Card ID: $card_id','successful','$currentdate','$acctno','$bwallet_balance','')");
                  
        echo '<h2>Card Reports for Card ID: '.$masked_pan.' from '.$StartDate.' - '.$EndDate.'</h2>';
                  
        echo '<table id="example2" class="table table-bordered table-striped">
            <thead>
            <tr>
                <th><div align="center">Id</div></th>
    			<th><div align="center">ProductName</div></th>
                <th><div align="center">UniqueReference</div></th>
                <th><div align="center">TransactionAmount</div></th>
                <th><div align="center">Date/Time</div></th>
                <th><div align="center">Status</div></th>
            </tr>
            </thead>
            <tbody>';				
    	
        foreach($result2 as $key) {

            echo '<tr>';
            echo '<td align="center"><b>'.$key['Id'].'</b></td>';
            echo '<td align="center"><b>'.$key['ProductName'].'</b></td>';
            echo '<td align="center"><b>'.$key['UniqueReference'].'</b></td>';
            echo '<td align="center"><b>'.$key['Currency'].number_format($key['TransactionAmount'],2,'.',',').'</b></td>';
            echo '<td align="center"><b>'.date("d/m/Y G:i A", strtotime($key['DateCreated'])).'</b></td>';
            echo '<td align="center"><b>'.$key['StatusName'].'</b></td>';
            echo '</tr>';
                	
        }
                	
        echo '</tbody>
            </table>';
                	
        echo '<hr>';
        echo '<a href="../pdf/view/pdf_cardreports3.php?dfrom='.$StartDate.'&&dto='.$EndDate.'&&cardID='.$card_id.'" target="_blank"><button type="button" class="btn bg-blue"><i class="fa fa-print"></i> Print Reports</button></a>';
                  
    }
    
}
?>			  
			 </form> 
			
		</div>
		</div>
              <!-- /.tab-pane -->

    <?php
	}
    }
	?>
              <!-- /.tab-pane -->
            </div>
            <!-- /.tab-content -->
			
          </div>					
			</div>
		
              </div>
	
</div>	
</div>
</div>
</section>	
</div>