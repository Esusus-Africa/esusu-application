<div class="box">
	       <div class="box-body">
			<div class="panel panel-success">
            <div class="panel-heading bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">
            <h3 class="panel-title"> <a href="sms_marketing.php?id=<?php echo $_SESSION['tid']; ?>&&mid=NzYw"><button type="button" class="btn btn-flat bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'blue' : $myaltrow['alternate_color']; ?>"><i class="fa fa-mail-reply-all"></i>&nbsp;Back</button></a> <i class="fa fa-area-chart"></i>  Report</h3>
            </div>

             <div class="box-body">
			 
			<form class="form-horizontal" method="post" enctype="multipart/form-data">
			 <div class="alert bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'blue' : $myaltrow['alternate_color']; ?>"> The SMS delivery report shows the <b>sender id, the recipient phone number, status with date and time</b>. </div>
             <div class="box-body">
			  

			<input name="branchid" type="hidden" class="form-control" value="<?php echo $rows->branchid; ?>">
			
				<div class="form-group">
                  <div class="col-sm-6"><span style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;"><b>From</b></span><br>
                  <input name="dfrom" type="date" class="form-control" placeholder="To Date: 2018-05-01" required>
				  <span style="color: <?php echo ($myaltrow['alternate_color'] == '') ? 'blue' : $myaltrow['alternate_color']; ?>;">Date should be in this format: 2018-05-01</span>
                  </div>
				  
                  <div class="col-sm-6"><span style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;"><b>To</b></span><br>
                  <input name="dto" type="date" class="form-control" placeholder="To Date: 2018-05-24" required>
				  <span style="color: <?php echo ($myaltrow['alternate_color'] == '') ? 'blue' : $myaltrow['alternate_color']; ?>;">Date should be in this format: 2018-05-24</span>
                  </div>
							  
			 </div>
			 
			  <div align="right">
              <div class="box-footer">
                	<button type="reset" class="btn bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'blue' : $myaltrow['alternate_color']; ?> btn-flat"><i class="fa fa-times">&nbsp;Reset</i></button>
                	<button name="search" type="submit" class="btn bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?> btn-flat"><i class="fa fa-filter">&nbsp;Search</i></button>
              </div>
			  </div>
			</form> 
			
<?php
if(isset($_POST['search'])){
	$dfrom = mysqli_real_escape_string($link, $_POST['dfrom']);
	$dto = mysqli_real_escape_string($link, $_POST['dto']);
	//$spool_by = mysqli_real_escape_string($link, $_POST['spool_by']);
	echo "<script>window.location='sms_reports.php?id=".$_SESSION['tid']."&&dfrom=".$dfrom."&&dto=".$dto."&&mid=NzYw'; </script>";
}
?>

<?php
if(isset($_GET['dfrom']))
{
?>

<hr>
<div align="left" style="color: <?php echo ($myaltrow['alternate_color'] == '') ? 'blue' : $myaltrow['alternate_color']; ?>;"> <h4><b>SMS Delivery Reports From <?php echo $_GET['dfrom']; ?> - <?php echo $_GET['dto']; ?></b> (change dates above)</h4> </div>
<br>
<table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th><div align="center">Sender ID</div></th>
                  <th><div align="center">Recipient</div></th>
                  <th><div align="center">Cost per SMS</div></th>
                  <th><div align="center">Status</div></th>
                  <th><div align="center">Message</div></th>
                  <th><div align="center">Date/Time</div></th>
                 </tr>
                </thead>
                <tbody>
<?php
$dfrom = $_GET['dfrom'];
$dto = $_GET['dto'];
//$spool_by = $_GET['spby'];
$select = mysqli_query($link, "SELECT * FROM sms_logs1 WHERE (company_id = '$institution_id') AND (date_time BETWEEN '$dfrom' AND '$dto') ORDER BY id") or die (mysqli_error($link));
while($row = mysqli_fetch_array($select))
{
	//$id = $row['id'];
	$date_time = $row['date_time'];
	$utc_date = DateTime::createFromFormat('Y-m-d G:i:s',$date_time,new DateTimeZone('America/New_York'));
    $acst_date = clone $utc_date; // we don't want PHP's default pass object by reference here
    $acst_date->setTimeZone(new DateTimeZone('GMT+1'));
    $correctdate = $acst_date->format('Y-m-d g:i A');
?>

				<tr align="center">
				<td><?php echo $row['sender_id']; ?></td>
                <td><?php echo $row['recipient']; ?></td>
                <td><?php echo $fetchsys_config['currency'].$row['price']; ?></td>
                <td><?php echo ($row['sms_status'] == "Pending") ? "<label class='label bg-orange'>Pending</label>" : "<label class='label bg-blue'>Sent</label>"; ?></td>
                <td><?php echo $row['sms_content']; ?></td>	
                <td><?php echo $correctdate; ?></td>
				</tr>
<?php 
}
?>
				</tbody>
                </table> 

<?php 
}
else{
	echo "";
}
?>


</div>	
</div>	
</div>
</div>