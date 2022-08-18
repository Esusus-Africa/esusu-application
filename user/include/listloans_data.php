<div class="row">       
		    <section class="content">  
	        <div class="box box-success">
            <div class="box-body">
              <div class="table-responsive">
             <div class="box-body">
<form method="post">

<?php
if(isMobileDevice()){
    //Your content or code for mobile devices goes here
?>

<?php
	$lid = 'LID-'.rand(2000000,100000000);
?>
	<a href="newloans.php?tid=<?php echo $_SESSION['tid']; ?>&&acn=<?php echo $_SESSION['acctno']; ?>&&mid=<?php echo base64_encode("404"); ?>&&lid=<?php echo $lid; ?>&&tab=tab_1"><button type="button" class="btn bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>"><i class="fa fa-plus"></i>&nbsp;Add Loans</button></a>
<?php
$baccount = $_SESSION['acctno'];
$date_now = date("Y-m-d");
$select = mysqli_query($link, "SELECT pay_date FROM loan_info WHERE baccount = '$baccount' AND p_status = 'UNPAID' AND pay_date <= '$date_now' AND pay_date != ''") or die ("Error: " . mysqli_error($link));
$num = mysqli_num_rows($select);	
?>
	<button type="button" class="btn bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>"><i class="fa fa-times"></i>&nbsp;Overdue:&nbsp;<?php echo number_format($num,0,'.',','); ?></button>

<?php
}
else{
    ?>
    
	<a href="dashboard.php?tid=<?php echo $_SESSION['tid']; ?>&&acn=<?php echo $_SESSION['acctno']; ?>&&mid=NDA0"><button type="button" class="btn bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>"><i class="fa fa-mail-reply-all"></i>&nbsp;Back</button></a>
<?php
	$lid = 'LID-'.rand(2000000,100000000);
?>
	<a href="newloans.php?tid=<?php echo $_SESSION['tid']; ?>&&acn=<?php echo $_SESSION['acctno']; ?>&&mid=<?php echo base64_encode("404"); ?>&&lid=<?php echo $lid; ?>&&tab=tab_1"><button type="button" class="btn bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>"><i class="fa fa-plus"></i>&nbsp;Add Loans</button></a>
<?php
$baccount = $_SESSION['acctno'];
$date_now = date("Y-m-d");
$select = mysqli_query($link, "SELECT pay_date FROM loan_info WHERE baccount = '$baccount' AND p_status = 'UNPAID' AND pay_date <= '$date_now' AND pay_date != ''") or die ("Error: " . mysqli_error($link));
$num = mysqli_num_rows($select);	
?>
	<button type="button" class="btn bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>"><i class="fa fa-times"></i>&nbsp;Overdue:&nbsp;<?php echo number_format($num,0,'.',','); ?></button>

<?php    
}
?>
	<hr>		
			  
			 <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th><input type="checkbox" id="select_all"/></th>
                  <th>Loan ID</th>
                  <th>Product Name</th>
                  <th>Principal Amount</th>
                  <th>Amount to Pay + Interest</th>
                  <th>Last Reviewed By</th>
                  <th>Date Release</th>
                  <th>Overall Payment Date</th>
                  <th>Status</th>
                 </tr>
                </thead>
                <tbody> 
<?php
$baccount = $_SESSION['acctno'];
$select = mysqli_query($link, "SELECT * FROM loan_info WHERE baccount = '$baccount'") or die (mysqli_error($link));
if(mysqli_num_rows($select)==0)
{
echo "<div class='alert bg-".$myaltrow['theme_color'].' == "" ? blue : '.$myaltrow['theme_color']."'>No data found yet!.....Check back later!!</div>";
}
else{
while($row = mysqli_fetch_array($select))
{
$id = $row['id'];
$borrower = $row['borrower'];
$status = $row['status'];
$lid = $row['lid'];
$lproduct = $row['lproduct'];

$search_product = mysqli_query($link, "SELECT * FROM loan_product WHERE id = '$lproduct'");
$fetch_product = mysqli_fetch_array($search_product);
$pname = $fetch_product['pname'];

$search_cardverification = mysqli_query($link, "SELECT * FROM authorized_card WHERE lid = '$lid'");
$get_cardverify = mysqli_num_rows($search_cardverification);

$systemset = mysqli_query($link, "SELECT * FROM systemset");
$rowsys = mysqli_fetch_array($systemset);
?> 
                <tr>
				<td><input id="optionsCheckbox" class="checkbox" name="selector[]" type="checkbox" value="<?php echo $row['id']; ?>"></td>
                <td><a href="<?php echo ($get_cardverify == 1) ? '#' : 'editloan_app.php?tid='.$_SESSION['tid'].'&&acn='.$_SESSION['acctno'].'&&lid='.$lid.'&&mid=NDA0&&tab=tab_0'; ?>"><?php echo $row['lid']; ?></a></td>
				<td><?php echo $pname; ?></td>
				<td><?php echo $bbcurrecy.number_format($row['amount'],2,'.',','); ?></td>
				<td><?php echo $bbcurrecy.number_format($row['amount_topay'],2,'.',','); ?></td>
			   <td><?php echo ($row['teller'] == "") ? '<span class="label bg-'.(($myaltrow['alternate_color'] == "") ? "orange" : $myaltrow['alternate_color']).'">Waiting for Review</span>' : $row['teller']; ?></td>
				<td><?php echo $row['date_release']; ?></td>
				<td><?php echo $row['pay_date']; ?></td>
                <td>
				 <span class="label bg-<?php echo ($status =='Approved' || $status =='Disbursed' ? 'blue' : ($status =='Disapproved' ? 'red' : 'orange')); ?>"><?php echo $status; ?></span>
				</td>						    
			    </tr>
<?php } } ?>
             </tbody>
                </table>  

</form>				

              </div>


	
</div>	
</div>
</div>	
</div>