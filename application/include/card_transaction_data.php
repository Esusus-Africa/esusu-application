<div class="box">
	       <div class="box-body">
			<div class="panel panel-success">
            <div class="panel-heading bg-blue">
            <h3 class="panel-title"> <a href="dashboard.php?id=<?php echo $_SESSION['tid']; ?>&&mid=NDAx"><button type="button" class="btn btn-flat bg-orange"><i class="fa fa-mail-reply-all"></i>&nbsp;Back</button> </a> <i class="fa fa-area-chart"></i> Card Transaction</h3>
            </div>

             <div class="box-body">
			 
			<form class="form-horizontal" method="post" enctype="multipart/form-data">
			 <div class="alert bg-blue"> The Card Transactions shows the <b>different charges that occur on a single card across the platform (Both Internally and Externally)</b>. </div>
             <div class="box-body">
		  

			<input name="branchid" type="hidden" class="form-control" value="<?php echo $rows->branchid; ?>">
			
			<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">From</label>
                  <div class="col-sm-4">
                  <input name="dfrom" type="date" class="form-control" placeholder="To Date: 2018-05-01" required>
				  <span style="color: orange;">Date should be in this format: 2018-05-01</span>
                  </div>
				  
				  <label for="" class="col-sm-2 control-label" style="color:blue;">To</label>
                  <div class="col-sm-4">
                  <input name="dto" type="date" class="form-control" placeholder="To Date: 2018-05-24" required>
				  <span style="color: orange;">Date should be in this format: 2018-05-24</span>
                  </div>
             </div>


             <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Status</label>
                  <div class="col-sm-4">
                  <input name="pagesize" type="text" class="form-control" placeholder="e.g 20" required>
				  <span style="color: orange;">Specify how many transactions you want to retrieve in a single call</span>
                  </div>
                  </div>

			 </div>
			 
			<div align="right">
              <div class="box-footer">
                				<button name="search" type="submit" class="btn bg-blue btn-flat"><i class="fa fa-filter">&nbsp;Search</i></button>

              </div>
			</div>

			</form> 
			
<?php
if(isset($_POST['search'])){
	$dfrom = mysqli_real_escape_string($link, $_POST['dfrom']);
	$dto = mysqli_real_escape_string($link, $_POST['dto']);
	$pagesize = mysqli_real_escape_string($link, $_POST['pagesize']);
	$cardlist = mysqli_real_escape_string($link, $_GET['cardId']);
	echo "<script>window.location='card_transaction.php?id=".$_SESSION['tid']."&&dfrom=".$dfrom."&&dto=".$dto."&&pagesize=".$pagesize."&&cardlist=".$cardlist."&&mid=NDI1'; </script>";
}
?>

<?php
if(isset($_GET['dfrom']))
{
	$system = mysqli_query($link, "SELECT * FROM systemset");
	$query = mysqli_fetch_array($system);
?>
<hr>
<div class="table-responsive">
<div class="box-body">
<div id='printarea'>
<div align="left" style="color: orange;"> <h4><b>From <?php echo $_GET['dfrom']; ?> - <?php echo $_GET['dto']; ?></b> (change dates above)</h4> </div>
<br>
<table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>ID</th>
                  <th>Transaction Amount</th>
                  <th>Fee</th>
                  <th>ProductName</th>
				  <th>UniqueReferenceDetails</th>
				  <th>TransactionReference</th>
				  <th>Card Number</th>
				  <th>StatusName</th>
				  <th>Description</th>
				  <th>Narration</th>
				  <th>DateCreated</th>
                 </tr>
                </thead>
                <tbody>
<?php
include("../config/restful_apicalls.php");

$result = array();
$dfrom = $_GET['dfrom'];
$dto = $_GET['dto'];
$pagesize = $_GET['pagesize'];
$cardlist = $_GET['cardlist'];

$systemset = mysqli_query($link, "SELECT * FROM systemset");
$get_sys = mysqli_fetch_array($systemset);
$seckey = $get_sys['secret_key'];

$search_account = mysqli_query($link, "SELECT * FROM card_enrollment WHERE card_id = '$cardlist'");
$fetch_account = mysqli_fetch_object($search_account);
$issuer_name = $fetch_account->api_used;

$search_restapi = mysqli_query($link, "SELECT * FROM atmcard_gateway_apis WHERE issuer_name = '$issuer_name' AND api_name = 'fetch-card-transactions'");
$fetch_restapi = mysqli_fetch_object($search_restapi);
$api_url = $fetch_restapi->api_url;

// Pass the parameter here
$postdata =  array(
	"FromDate"	=>	$dfrom,
	"ToDate"=>	$dto,
	"PageIndex"	=>	0,
	"PageSize"	=>	$pagesize,
	"CardId"	=>	$cardlist,
	"secret_key"	=>	$seckey
);

$make_call = callAPI('POST', $api_url, json_encode($postdata));
$result = json_decode($make_call, true);

//var_dump($result);

if($result['Status'] == "success")
{
?>
			<?php
			foreach($result['data']['Transactions'] as $key)
			{
			?>
				<tr>
				<td><b><?php echo $key['Id']; ?></b></td>
				<td><b><?php echo $key['Currency'].$key['TransactionAmount']; ?></b></td>
				<td><?php echo $key['Fee']; ?></td>
				<td><b><?php echo $key['ProductName']; ?></b></td>
				<td><?php echo $key['UniqueReferenceDetails']; ?></td>
				<td><b><?php echo $key['TransactionReference']; ?></b></td>
				<td><b><?php echo $key['UniqueReference']; ?></b></td>
				<td><?php echo $key['StatusName']; ?></td>
				<td><?php echo $key['Description']; ?></td>
				<td><?php echo $key['Narration']; ?></td>
				<td><?php echo $key['DateCreated']; ?></td>
				</tr>
		<?php } ?>
<?php
}
else{
    echo "<p style='color: blue;'>".$result['Message']."!</p>";
}
?>
				</tbody>
                </table> 

                <form method="post">
					<!---<input type="submit" name="generate_pdf" class="btn bg-blue" value="Generate PDF"/>-->
				</form>
<?php 
}
else{
	echo "";
}
?>
</div>
</div>
</div>
<?php
if(isset($_POST['generate_pdf']))
{
	echo "<script>window.open('../pdf/view/pdf_cardtransaction.php?dfrom=".$dfrom."&&dto=".$dto."&&pagesize=".$pagesize."&&cardlist=".$cardlist."', '_blank'); </script>";
}
?>

</div>	
</div>	
</div>
</div>