<div class="box">
	       <div class="box-body">
			<div class="panel panel-success">
            <div class="panel-heading bg-blue">
            <h3 class="panel-title"> <a href="sms_marketing.php?id=<?php echo $_SESSION['tid']; ?>&&mid=NzYw"><button type="button" class="btn btn-flat bg-orange"><i class="fa fa-mail-reply-all"></i>&nbsp;Back</button></a> <i class="fa fa-area-chart"></i>  Report</h3>
            </div>

             <div class="box-body">
			 
			<form class="form-horizontal" method="post" enctype="multipart/form-data">
			 <div class="alert bg-orange"> The SMS delivery report shows the <b>sender id, the recipient phone number, status with date and time</b>. </div>
             <div class="box-body">
			  

			<input name="branchid" type="hidden" class="form-control" value="<?php echo $rows->branchid; ?>">
			
				<div class="form-group">
                  <div class="col-sm-4"><span style="color:blue;"><b>From</b></span><br>
                  <input name="dfrom" type="date" class="form-control" placeholder="To Date: 2018-05-01" required>
				  <span style="color: orange;">Date should be in this format: 2018-05-01</span>
                  </div>
				  
                  <div class="col-sm-4"><span style="color:blue;"><b>To</b></span><br>
                  <input name="dto" type="date" class="form-control" placeholder="To Date: 2018-05-24" required>
				  <span style="color: orange;">Date should be in this format: 2018-05-24</span>
                  </div>
                  
                  <div class="col-sm-4"><span style="color:blue;"><b>For</b></span><br>
                  <select name="spool_by"  class="form-control select2" style="width:100%">
				<option value="" selected="selected">For ESUSU AFRICA (Default)</option>

				<option disabled>SELECT INDIVIDUAL CUSTOMERS</option>
				<?php
				$get = mysqli_query($link, "SELECT * FROM borrowers ORDER BY id DESC") or die (mysqli_error($link));
				while($rows = mysqli_fetch_array($get))
				{
				?>
				<option value="<?php echo $rows['account']; ?>"><?php echo $rows['fname'].'&nbsp;'.$rows['lname']; ?></option>
                <?php } ?>	

                <option disabled>SELECT INDIVIDUAL SUPER AGENT</option>
				<?php
				$get = mysqli_query($link, "SELECT * FROM agent_data ORDER BY id DESC") or die (mysqli_error($link));
				while($rows = mysqli_fetch_array($get))
				{
				?>
				<option value="<?php echo $rows['agentid']; ?>"><?php echo $rows['fname']." (".$rows['bname'].")"; ?></option>
                <?php } ?>
                
                <option disabled>SELECT INDIVIDUAL MFI</option>
				<?php
				$get = mysqli_query($link, "SELECT * FROM institution_data ORDER BY id DESC") or die (mysqli_error($link));
				while($rows = mysqli_fetch_array($get))
				{
				?>
				<option value="<?php echo $rows['institution_id']; ?>"><?php echo $rows['institution_name']; ?></option>
                <?php } ?>

                <option disabled>SELECT INDIVIDUAL MERCHANT</option>
				<?php
				$get = mysqli_query($link, "SELECT * FROM merchant_reg ORDER BY id DESC") or die (mysqli_error($link));
				while($rows = mysqli_fetch_array($get))
				{
				?>
				<option value="<?php echo $rows['merchantID']; ?>"><?php echo $rows['company_name']; ?></option>
                <?php } ?>
                
                <option disabled>SELECT INDIVIDUAL COOPERATIVE</option>
				<?php
				$get = mysqli_query($link, "SELECT * FROM cooperatives ORDER BY id DESC") or die (mysqli_error($link));
				while($rows = mysqli_fetch_array($get))
				{
				?>
				<option value="<?php echo $rows['coopid']; ?>"><?php echo $rows['coopname']; ?></option>
                <?php } ?>

				</select>
                  </div>
							  
			 </div>
			 
			  <div align="right">
              <div class="box-footer">
                	<button type="reset" class="btn bg-orange btn-flat"><i class="fa fa-times">&nbsp;Reset</i></button>
                	<button name="search" type="submit" class="btn bg-blue btn-flat"><i class="fa fa-filter">&nbsp;Search</i></button>
              </div>
			  </div>
			</form> 
			
<?php
if(isset($_POST['search'])){
	$dfrom = mysqli_real_escape_string($link, $_POST['dfrom']);
	$dto = mysqli_real_escape_string($link, $_POST['dto']);
	$spool_by = mysqli_real_escape_string($link, $_POST['spool_by']);
	echo "<script>window.location='sms_reports.php?id=".$_SESSION['tid']."&&dfrom=".$dfrom."&&dto=".$dto."&&spby=".$spool_by."&&mid=NzYw'; </script>";
}
?>

<hr>
<div align="left" style="color: orange;"> <h4><b>SMS Delivery Reports From <?php echo $_GET['dfrom']; ?> - <?php echo $_GET['dto']; ?></b> (change dates above)</h4> </div>
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
$spool_by = $_GET['spby'];
if(!isset($_GET['dfrom'])){
    $select = mysqli_query($link, "SELECT * FROM sms_logs1 ORDER BY id DESC LIMIT 100") or die (mysqli_error($link));
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
                <td><?php echo $fetchsys_config['currency'].$fetchsys_config['fax']; ?></td>
                <td><?php echo ($row['sms_status'] == "Pending") ? "<label class='label bg-orange'>Pending</label>" : "<label class='label bg-blue'>Sent</label>"; ?></td>
                <td><?php echo $row['sms_content']; ?></td>	
                <td><?php echo $correctdate; ?></td>
				</tr>
				
<?php
}
}elseif($spool_by != ""){
    
    $select = mysqli_query($link, "SELECT * FROM sms_logs1 WHERE date_time BETWEEN '$dfrom' AND '$dto' AND company_id = '$spool_by' ORDER BY id DESC") or die (mysqli_error($link));
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
                <td><?php echo $fetchsys_config['currency'].$fetchsys_config['fax']; ?></td>
                <td><?php echo ($row['sms_status'] == "Pending") ? "<label class='label bg-orange'>Pending</label>" : "<label class='label bg-blue'>Sent</label>"; ?></td>
                <td><?php echo $row['sms_content']; ?></td>	
                <td><?php echo $correctdate; ?></td>
				</tr>
<?php 
}
}
elseif($spool_by == ""){
    $select = mysqli_query($link, "SELECT * FROM sms_logs1 WHERE date_time BETWEEN '$dfrom' AND '$dto' ORDER BY id DESC") or die (mysqli_error($link));
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
                <td><?php echo $fetchsys_config['currency'].$fetchsys_config['fax']; ?></td>
                <td><?php echo ($row['sms_status'] == "Pending") ? "<label class='label bg-orange'>Pending</label>" : "<label class='label bg-blue'>Sent</label>"; ?></td>
                <td><?php echo $row['sms_content']; ?></td>	
                <td><?php echo $correctdate; ?></td>
				</tr>
				
<?php 
}
}
?>
				</tbody>
                </table> 


</div>	
</div>	
</div>
</div>