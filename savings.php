<?php include "config/connect.php";?>
<!DOCTYPE html>
<html>
<head>

  
<?php 
$call = mysqli_query($link, "SELECT * FROM systemset");
if(mysqli_num_rows($call) == 0)
{
echo "<script>alert('Data Not Found!'); </script>";
}
else
{
while($row = mysqli_fetch_assoc($call)){
?>
<title><?php echo $row ['title']?></title>
<?php }}?>  

<?php 
$call = mysqli_query($link, "SELECT * FROM systemset");
if(mysqli_num_rows($call) == 0)
{
echo "<script>alert('Data Not Found!'); </script>";
}
else
{
while($row = mysqli_fetch_assoc($call)){
?>

<link href="img/<?php echo $row['image']; ?>" rel="icon" type="dist/img">
<?php }}?> 
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.6 -->
  <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/AdminLTE.min.css">

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
</head>

<body class="hold-transition lockscreen bg-blue">

<!-- Automatic element centering -->
<div class="lockscreen-wrapper">
  <div class="lockscreen-logo">
  <?php $sql = "SELECT * FROM systemset";
$result = mysqli_query($link,$sql);
  		
while ($row=mysqli_fetch_array($result))
		{
?>
    <a href="http://citycore.com.ng"><div style="color: white;"><h3><strong><?php echo $row ['name'];?></strong></h3></div></a>
  </div>
  
  <!-- User name -->
  <div class="lockscreen-name" style="color: white;"><b>Make Basic Savings</b></div>

  <!-- START LOCK SCREEN ITEM -->
  <div class="lockscreen-item" align="center">
    <!-- lockscreen image -->
			<?php }?>

    <!-- /.lockscreen-image -->

    <!-- lockscreen credentials (contains the form) -->
 <form >
   <button type="button" onclick="payWithPaystack()" class="btn bg-yellow"><i class="fa fa-send text-muted"></i> Proceed Now! </button> 
 </form>
<?php							
	//basic deposit amount to kickstart savings 
	$amount = $_POST['amount'];
	$acn = $_GET['acn'];
	
	$search_user = mysqli_query($link, "SELECT * FROM borrowers WHERE account = '$acn'") or die ("Error: " . mysqli_error($link));
	$get_buser = mysqli_fetch_object($search_user);
	
	$system_set = mysqli_query($link, "SELECT * FROM systemset") or die ("Error: " . mysqli_error($link));
	$row1 = mysqli_fetch_object($system_set);
?>		

<script>
  function payWithPaystack(){
    var handler = PaystackPop.setup({
      key: '<?php echo $row1->public_key; ?>',
      email: '<?php echo $get_buser->email; ?>',
      amount: 500 * 100,
	  //currency: '<?php //echo $get_systemset->currency; ?>',
      //ref: ''+Math.floor((Math.random() * 1000000000) + 1), // generates a pseudo-unique reference. Please replace with a reference you generated. Or remove the line entirely so our API will generate one for you
      firstname: '<?php echo $get_buser->fname; ?>',
      lastname: '<?php echo $get_buser->lname; ?>',
      // label: "Optional string that replaces customer email"
      metadata: {
         custom_fields: [
            {
                display_name: "Mobile Number",
                variable_name: "mobile_number",
                value: "<?php echo $get_buser->phone; ?>",
            }
         ]
      },
      callback: function(response){
		  alert('success. transaction ref is ' + response.reference);
		  window.location='complete_savings.php?acn=<?php echo $_GET['acn']; ?>' + '&&refid=' + response.reference;
      },
      onClose: function(){
          alert('window closed');
      }
    });
    handler.openIframe();
  }
</script>
  	
    <!-- /.lockscreen credentials -->
</div>
  <!-- /.lockscreen-item -->
  <div class="help-block text-center">
	  <br>
    <span style="color:white;">Please be informed that you are required to make a basic deposit of <b>N500.00</b> to start saving with us. <b>Also NOTE THAT:</b> this will be credited to your account once your account is Authorized by any of our Staff.</span>
  </div>
<br><br>
  <div class="lockscreen-footer text-center" style="color: white;">
    <?php 
				 $result= mysqli_query($link,"select * from systemset")or die(mysqli_error());
				 while($row=mysqli_fetch_array($result))
				 {
				 ?>
    <strong> <?php echo $row ['footer'];?> </strong>
	<?php }?>
  </div>
</div>
<!-- /.center -->

<!-- jQuery 2.2.3 -->
<script src="plugins/jQuery/jquery-2.2.3.min.js"></script>
<!-- Bootstrap 3.3.6 -->
<script src="bootstrap/js/bootstrap.min.js"></script>
<script src="https://js.paystack.co/v1/inline.js"></script>

</body>
</html>