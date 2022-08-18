<div class="box">
	       <div class="box-body">
			<div class="panel panel-success">
            <div class="panel-heading bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">
            <h3 class="panel-title"><i class="fa fa-globe"></i>  Subscription Confirmation Form</h3>
            </div>

             <div class="box-body">
              
<form class="form-horizontal" method="post" enctype="multipart/form-data">

  <?php echo '<div class="bg-orange fade in" >
          <a href = "#" class = "close" data-dismiss= "alert"> &times;</a>
            <strong>Please confirm&nbsp;</strong> the plan name and the price before you proceed. And if probably you made a mistake in choosing the plan, kindly hit the <b>back botton</b> below to go back to the previous page.
          </div>'?>
<hr>
<div class="box-body">

<?php
$pcode = $_GET['pcode'];
$detect_subplan = mysqli_query($link, "SELECT * FROM savings_plan WHERE plan_code = '$pcode'");
$fetch_subplan = mysqli_fetch_array($detect_subplan);
$search_currency = mysqli_query($link, "SELECT * FROM systemset");
$fetch_currency = mysqli_fetch_object($search_currency);
?>

<input name="merchantid_others" type="hidden" class="form-control" value="<?php echo $fetch_subplan['merchantid_others']; ?>" id="HideValueFrank"/>

<input name="categories" type="hidden" class="form-control" value="<?php echo $fetch_subplan['categories']; ?>" id="HideValueFrank"/>

<input name="plan_code" type="hidden" class="form-control" value="<?php echo $fetch_subplan['plan_code']; ?>" id="HideValueFrank"/>

<input name="acn" type="hidden" class="form-control" value="<?php echo $acnt_id; ?>" id="HideValueFrank"/>

<div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Plan Name</label>
                  <div class="col-sm-9">
                  <span style="color: orange; font-size: 21px"><b><?php echo $fetch_subplan['plan_name']; ?></b></span>
                  </div>
                  </div>

<div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Amount (per month)</label>
                  <div class="col-sm-9">
                  <span style="color: orange; font-size: 21px"><b><?php echo $fetch_currency->currency.number_format($fetch_subplan['amount'],2,'.',','); ?></b></span>
                  </div>
                  </div>

<div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Start Date</label>
                  <div class="col-sm-6">
                    <input name="start_date" type="date" class="form-control" /required>
                  <br><span style="color: blue; font-size: 14px"><b>Please before selecting the start date, kindly make sure the displayed amount is available in your bank account for cool and easy transaction.</b></span>
                  </div>
                  </div>

</div>

<div align="right">
     <div class="box-footer">
      <a href="set_savingsplan.php?tid=<?php echo $_SESSION['tid']; ?>&&acn=<?php echo $_SESSION['acctno']; ?>&&mid=NDA3"><button type="button" class="btn bg-orange"><i class="fa fa-mail-reply-all"></i>&nbsp;Back</button></a>
        <button name="submit" type="submit" class="btn bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>"><i class="fa fa-cloud-upload">&nbsp;Proceed</i></button>

     </div>
</div>

<?php 
if(isset($_POST['submit']))
{
$result = array();
$plan_code = $_POST['plan_code'];
$acn = $_POST['acn'];
$merchantid_others = mysqli_real_escape_string($link, $_POST['merchantid_others']);
$categories = mysqli_real_escape_string($link, $_POST['categories']);
$start_date = date("c",strtotime($_POST['start_date']));
// Pass the customer's name, interval and amount
$postdata =  array('customer' => $email2,'plan' => $plan_code,'start_date' => $start_date);

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL,"https://api.paystack.co/subscription");
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS,json_encode($postdata));  //Post Fields
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$systemset = mysqli_query($link, "SELECT * FROM systemset");
$row1 = mysqli_fetch_object($systemset);

$headers = [
  'Authorization: Bearer '.$row1->secret_key,
  'Content-Type: application/json',
];

curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

$request = curl_exec ($ch);

curl_close ($ch);
if ($request) {
  $result = json_decode($request, true);
  if($result){
    if($result['data'] == true){
    
    $subscription_code = $result['data']['subscription_code'];

    $insert = mysqli_query($link, "INSERT INTO savings_subscription VALUES(null,'$merchantid_others','$categories','$plan_code','$subscription_code','$acn','Active',NOW())") or die ("ERROR: " . mysqli_error($link));
    echo "<script>alert('Savings Plan Set Successfully'); </script>";
    echo "<script>window.location='my_savings_plan.php?tid=".$_SESSION['tid']."&&acn=".$acnt_id."&&mid=NDA3'; </script>";
    
  }
}
}

}
?>    

 </form>

</div>	
</div>	
</div>
</div>