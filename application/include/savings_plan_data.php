<div class="row">
		
	       
		    <section class="content">  
	        <div class="box box-success">
            <div class="box-body">
              <div class="table-responsive">
             <div class="box-body">
<form method="post">
			 <a href="dashboard.php?tid=<?php echo $_SESSION['tid']; ?>&&acn=<?php echo $_SESSION['acctno']; ?>&&mid=<?php echo base64_encode("401"); ?>"><button type="button" class="btn btn-flat bg-orange"><i class="fa fa-mail-reply-all"></i>&nbsp;Back</button> </a> 
	
	<hr>		
			  
			 <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th><input type="checkbox" id="select_all"/></th>
				  <th>Account Number</th>
				  <th>Savings Plan</th>
				  <th>Subscription Code</th>
				  <th>Auth. Code</th>
				  <th>Amount</th>
				  <th>Interval</th>
                  <th>Next Charge</th>
                 </tr>
                </thead>
                <tbody>
<?php
 $acn = $_SESSION['acctno'];
 $systemset = mysqli_query($link, "SELECT * FROM systemset");
 $row1 = mysqli_fetch_object($systemset);
 
 $search_sub = mysqli_query($link, "SELECT * FROM savings_subscription");
 while($row_sub = mysqli_fetch_object($search_sub)){
 
 $result = array();
 $subscription_code = $row_sub->subscription_code;
 
 //The parameter after verify/ is the subscription code to be fetch
$url = 'https://api.paystack.co/subscription/'.$subscription_code;

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt(
  $ch, CURLOPT_HTTPHEADER, [
    'Authorization: Bearer '.$row1->secret_key]
);

$request = curl_exec($ch);
if(curl_error($ch)){
echo 'error:' . curl_error($ch);
}
curl_close($ch);

 if($request) {
   $result = json_decode($request, true);
}
   
if (array_key_exists('data', $result) && array_key_exists('status', $result['data'])) {
?>    
                <tr>
				<td><input id="optionsCheckbox" class="checkbox"  name="selector[]" type="checkbox" value="<?php echo $result['data']['id']; ?>"></td>
				<td><a href="invoice-print.php?id=<?php echo $_SESSION['tid']; ?>&&mid=NDAz&&uid=<?php echo $row_sub->acn; ?>" target="_blank"><b><?php echo $row_sub->acn; ?></b></td>
				<td><?php echo $result['data']['plan']['name']; ?></td>
				<td><?php echo $result['data']['subscription_code']; ?></td>
				<td><?php echo $result['data']['authorization']['authorization_code']; ?></td>
                <td><?php echo $result['data']['plan']['currency']. number_format($result['data']['amount']/100,2,'.',','); ?></td>
				<td><?php echo $result['data']['plan']['interval']; ?></td>
				<td><i><b><?php echo date ('l, F, d, Y h:i:s A', strtotime($result['data']['next_payment_date'])); ?></b></i></td>
				</tr>
<?php 
}
else{
	echo "<div class='alert bg-blue'>No data found yet!.....Check back later!!</div>";
} 
}
?>

             </tbody>
                </table>  
</form>
				

              </div>

	
</div>	
</div>
</div>	


			
</div>