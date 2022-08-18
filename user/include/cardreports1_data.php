<div class="box">
	       <div class="box-body">
			<div class="panel panel-success">
            <div class="panel-heading bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">
            <h3 class="panel-title"> <i class="fa fa-area-chart"></i> VerveCard Reports</h3>
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
    function ccMasking($number, $maskingCharacter = '*') {
        return substr($number, 0, 6) . str_repeat($maskingCharacter, strlen($number) - 10) . substr($number, -4);
	}
	
    try
      {
      	$dfrom = mysqli_real_escape_string($link, $_POST['dfrom']);
        $StartDate = date("Y-m-d", strtotime($dfrom));
        	
        $dto = mysqli_real_escape_string($link, $_POST['dto']);
        $EndDate = date("Y-m-d", strtotime($dto));
        	
        $PAN =  mysqli_real_escape_string($link, $_POST['customer']); //CARD ID
        	
        $api_name =  "card_load";
        $search_restapi = mysqli_query($link, "SELECT * FROM atmcard_gateway_apis WHERE api_name = '$api_name' AND issuer_name = 'VerveCard'");
        $fetch_restapi = mysqli_fetch_object($search_restapi);
        $api_url = $fetch_restapi->api_url;
        	
        $systemset = mysqli_query($link, "SELECT * FROM systemset");
        $row1 = mysqli_fetch_object($systemset);
        $verveAppId = $row1->verveAppId;
        $verveAppKey = $row1->verveAppKey;
              
        $report_charges = $fetchsys_config['report_charges'];
    	  
      	if($bwallet_balance < $report_charges){
      			
      		echo "<script>alert('Oops! You do not have sufficient fund in your wallet to perform this operation!!'); </script>";
      	    echo "<script>window.location='cardreports?id=".$_SESSION['tid']."&&mid=".base64_encode('900')."'; </script>";
      			
      	}else{
    	
      		$client = new SoapClient($api_url);
            
            $param = array(
                'PAN'=>$PAN,
                'StartDate'=>$StartDate,
                'EndDate'=>$EndDate,
                'appId'=>$verveAppId,
                'appKey'=>$verveAppKey
            );
            
            $response = $client->GetIswPrepaidCardStatement($param);
                
            $process = json_decode(json_encode($response), true);
            
            print_r($process);
            $StatusCode = $process['responseCode']; //00 OR 400
            
            if($StatusCode === "00"){
                
                //REPORT CHARGES
          		$rOrderID = "EA-rCharges-".mt_rand(30000000,99999999);
          		$calc_mywalletBalance = $bwallet_balance - $report_charges;
          		
          		mysqli_query($link, "UPDATE borrowers SET wallet_balance = '$calc_mywalletBalance' WHERE account = '$acctno'");
          		mysqli_query($link, "INSERT INTO wallet_history VALUE(null,'$bbranchid','$rOrderID','$acctno','$report_charges','$bbcurrency','Report_Charges','Response: $bbcurrency.$report_charges was charged for triggering report for Card ID: $customer','successful',NOW(),'$acctno','$bwallet_balance')");
    
                echo '<h2>Card Reports for Card ID: '.ccMasking($PAN).' from '.$StartDate.' - '.$EndDate.'</h2>';
                  
                echo '<table id="example2" class="table table-bordered table-striped">
                    <thead>
                    <tr>
                      <th><div align="center">tranType</div></th>
    				  <th><div align="center">Local Date/Time</div></th>
                      <th><div align="center">Posted Date/Time</div></th>
                      <th><div align="center">Amount</div></th>
                      <th><div align="center">cardAcceptor Name / Location</div></th>
                     </tr>
                    </thead>
                    <tbody>';				
    	
                	foreach($process['statementRecords'] as $key) {
                		
                		echo '<tr>';
                		echo '<td align="center"><b>'.$key['tranType'].'</b></td>';
                		echo '<td align="center"><b>'.date("d/m/Y G:i A", strtotime($key['tranLocalDatetime'])).'</b></td>';
                		echo '<td align="center"><b>'.date("d/m/Y G:i A", strtotime($key['tranPostedDatetime'])).'</b></td>';
                		echo '<td align="center"><b>'.$key['tranAmount'].'</b></td>';
                		echo '<td align="center"><b>'.$key['cardAcceptorNameLocation'].'</b></td>';
                		echo '</tr>';
                	
                	}
                	
                	echo '</tbody>
                          </table>';
                	
                	echo '<hr>';
                	echo '<a href="../pdf/view/pdf_cardreports1.php?dfrom='.$StartDate.'&&dto='.$EndDate.'&&cardID='.$PAN.'" target="_blank"><button type="button" class="btn bg-blue"><i class="fa fa-print"></i> Print Reports</button></a>';
                  
              }
              else{
                  
                  echo $JsonData;
                  
              }
      	}
  		  
      }
      catch(Exception $e)
      {
          // You should not be here anymore
          echo $e->getMessage();
      }
}
?>
			  
			 </form> 

</div>	
</div>	
</div>
</div>