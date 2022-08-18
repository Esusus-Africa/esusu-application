<div class="box">
	       <div class="box-body">
			<div class="panel panel-success">
            <div class="panel-heading bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">
            <h3 class="panel-title"> <i class="fa fa-area-chart"></i> Mastercard Reports</h3>
            </div>

             <div class="box-body">           
			 <form class="form-horizontal" method="post" enctype="multipart/form-data">
       
<div class="alert bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>">
<b>NOTE</b> that any card reports triggered on our system attract <b><?php echo $bbcurrency.$fetchsys_config['report_charges']; ?></b> per request
</div>
<hr>
             <div class="box-body">

		        <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">From:</label>
                  <div class="col-sm-4">
                  <input name="dfrom" type="date" class="form-control" placeholder="To Date: 2018-05-01" required>
                  </div>
				  
				          <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">To</label>
                  <div class="col-sm-4">
                  <input name="dto" type="date" class="form-control" placeholder="To Date: 2018-05-24" required>
                  </div>
             </div>

			
			 </div>
			 
			  <div align="right">
              <div class="box-footer">
                	<button name="search" type="submit" class="btn bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>"><i class="fa fa-filter">&nbsp;Search</i></button>
              </div>
			  </div>

<?php
if(isset($_POST['search']))
{	
  	$dfrom = mysqli_real_escape_string($link, $_POST['dfrom']);
  	$startDate = date("d/m/Y", strtotime($dfrom));
  	
  	$dto = mysqli_real_escape_string($link, $_POST['dto']);
  	$endDate = date("d/m/Y", strtotime($dto));
  	
  	$customer =  $card_id; //CARD ID
  	
  	$api_name =  "transaction_history";
  	$search_restapi = mysqli_query($link, "SELECT * FROM atmcard_gateway_apis WHERE api_name = '$api_name' AND issuer_name = 'Mastercard'");
  	$fetch_restapi = mysqli_fetch_object($search_restapi);
  	$api_url = $fetch_restapi->api_url;
	
  	$systemset = mysqli_query($link, "SELECT * FROM systemset");
  	$row1 = mysqli_fetch_object($systemset);
  	$bancore_merchantID = $row1->bancore_merchant_acctid;
  	$bancore_mprivate_key = $row1->bancore_merchant_pkey;
              
  	$passcode = $bancore_merchantID.$customer.$startDate.$endDate.$bancore_mprivate_key;
  	$encKey = hash('sha256',$passcode);
	  
	  $report_charges = $fetchsys_config['report_charges'];
	  
  	if($bwallet_balance < $report_charges){
  			
  		echo "<script>alert('Oops! You do not have sufficient fund in your wallet to perform this operation!!'); </script>";
  	  echo "<script>window.location='cardreports?id=".$_SESSION['tid']."&&mid=".base64_encode('900')."'; </script>";
  			
  	}else{
	
  		function cardReports($cardID, $startdate, $enddate, $debug=false){
  	      	global $bancore_merchantID,$encKey,$api_url;
  			
  			$url = '?merchantID='.$bancore_merchantID;
  			$url.= '&cardID='.$cardID;
  			$url.= '&startDate='.$startdate;
  			$url.= '&endDate='.$enddate;
  			$url.= '&encKey='.$encKey;
  	              		  
  			$urltouse =  $api_url.$url;
  				  
  			//if ($debug) { echo "Request: <br>$urltouse<br><br>"; }
  			//Open the URL to send the message
  			$response = file_get_contents($urltouse);
  	              
  			if ($debug) {
  				//echo "Response: <br><pre>".
  				//str_replace(array("<",">"),array("&lt;","&gt;"),$response).
  				//"</pre><br>"; 
  			}
  			return($response);
  		}
  	    
  	    $debug = true;
  		$cardRpts = cardReports($customer,$startDate,$endDate,$debug);	
  		$xml = simplexml_load_string($cardRpts);
  		//convert into json
  		$json  = json_encode($xml);
  		//convert into associative array
  		$xmlArr = json_decode($json, true);
  		//REPORT CHARGES
  		$rOrderID = "EA-rCharges-".mt_rand(30000000,99999999);
  		$calc_mywalletBalance = $bwallet_balance - $report_charges;
  		
  		mysqli_query($link, "UPDATE borrowers SET wallet_balance = '$calc_mywalletBalance' WHERE account = '$acctno'");
  		mysqli_query($link, "INSERT INTO wallet_history VALUE(null,'$bbranchid','$rOrderID','$acctno','$report_charges','$bbcurrency','Report_Charges','Response: $bbcurrency.$report_charges was charged for triggering report for Card ID: $customer','successful',NOW(),'$acctno','$bwallet_balance')");
  
  		echo '<h2>Card Reports for Card ID: '.$xmlArr['cardID'].' from '.$xmlArr['startDate'].' - '.$xmlArr['endDate'].'</h2>';
  		
  		echo '<table id="example2" class="table table-bordered table-striped">
  	                <thead>
  	                <tr>
  	                  <th><div align="center">Date</div></th>
  					  <th><div align="center">Reference</div></th>
  	                  <th><div align="center">Funds Credited</div></th>
  	                  <th><div align="center">Funds Debited</div></th>
  	                  <th><div align="center">Card Balance</div></th>
  					  <th><div>Description</div></th>
  	                 </tr>
  	                </thead>
  	                <tbody>';				
  		
  		foreach($xmlArr['transactions'] as $key) {
  			
  			echo '<tr>';
  			echo '<td align="center"><b>'.date("d/m/Y m:s a", strtotime($key['date'])).'</b></td>';
  			echo '<td align="center"><b>'.$key['transactionReference'].'</b></td>';
  			echo '<td align="center"><b>'.$key['fundsCredited'].'</b></td>';
  			echo '<td align="center"><b>'.$key['fundsDebited'].'</b></td>';
  			echo '<td align="center"><b>'.$key['cardBalance'].'</b></td>';
  			echo '<td><b>'.$key['description'].'</b></td>';
  			echo '</tr>';
  		
  		}
  		
  		echo '</tbody>
  	          </table>';
  		
  		echo '<hr>';
  		echo '<a href="../pdf/view/pdf_cardreports1.php?dfrom='.$startDate.'&&dto='.$endDate.'&&cardID='.$customer.'&&merch='.$bbranchid.'" target="_blank"><button type="button" class="btn bg-blue"><i class="fa fa-print"></i> Print Reports</button></a>';
	
	}
}
?>
			  
			 </form> 

</div>	
</div>	
</div>
</div>