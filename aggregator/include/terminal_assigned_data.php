<div class="row">
		
	       
		    <section class="content">  
	        <div class="box box-success">
            <div class="box-body">
              <div class="table-responsive">
             <div class="box-body">
<form method="post">
	<a href="dashboard.php?id=<?php echo $_SESSION['tid']; ?>&&mid=<?php echo base64_encode("401"); ?>"><button type="button" class="btn bg-orange"><i class="fa fa-mail-reply-all"></i>&nbsp;Back</button></a> 
	<hr>				  
			 <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th><input type="checkbox" id="select_all"/></th>
                  <th>Channel</th>
                  <th>TID</th>
                  <th>Trace ID</th>
                  <th>Model Code</th>
                  <th>Settlement Type</th>
                  <th>Pending Balance</th>
                  <th>Settled Balance</th>
                  <th>Status</th>
                  <th>Operator</th>
                  <th>DateTime</th>
                 </tr>
                </thead>
                <tbody>
<?php
$select = mysqli_query($link, "SELECT * FROM terminal_reg WHERE initiatedBy = '$aggr_id' AND terminal_status = 'Assigned' ORDER BY id DESC") or die ("Error: " . mysqli_error($link));
if(mysqli_num_rows($select)==0)
{
echo "<div class='alert bg-blue'>No data found yet!.....Check back later!!</div>";
}
else{
while($row = mysqli_fetch_array($select))
{
$id = $row['id'];
$reg_date = $row['dateCreated'];
$tidoperator = $row['tidoperator'];

$utc_date = DateTime::createFromFormat('Y-m-d G:i:s',$reg_date,new DateTimeZone(date_default_timezone_get()));
$acst_date = clone $utc_date; // we don't want PHP's default pass object by reference here
$acst_date->setTimeZone(new DateTimeZone('GMT+1'));
$correctdate = $acst_date->format('Y-m-d g:i A');

$searchQuery = mysqli_query($link, "SELECT * FROM user WHERE id = '$tidoperator'");
$fetchQuery = mysqli_fetch_array($searchQuery);
$operatorName = $fetchQuery['name'].' '.$fetchQuery['lname'];
?>    
                <tr>
                <td><input id="optionsCheckbox" class="checkbox"  name="selector[]" type="checkbox" value="<?php echo $id; ?>"></td>
                <td><?php echo $row['channel']; ?></td>
                <td><?php echo $row['terminal_id']; ?></td>
                <td><?php echo ($row['trace_id'] == "") ? "---" : $row['trace_id']; ?></td>
                <td><?php echo $row['terminal_model_code']; ?></td>
                <td><?php echo $row['settlmentType']; ?></td>
                <td><?php echo number_format($row['pending_balance'],2,'.',','); ?></td>
                <td><?php echo number_format($row['settled_balance'],2,'.',','); ?></td>
                <td><?php echo "<span class='label bg-blue'>Assigned <i class='fa fa-check'></i></span>"; ?></td>
                <td><a href="reAssignTerminal.php?tmid=<?php echo ($row['trace_id'] == "") ? $row['terminal_id'] : $row['trace_id']; ?>" target="_blank"><?php echo $operatorName; ?> <i class="fa fa-external-link"></i></a></td>
                <td><?php echo $correctdate; ?></td>
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