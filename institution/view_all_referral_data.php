<div class="row">
		
	       
		    <section class="content">  
	        <div class="box box-success">
            <div class="box-body">
              <div class="table-responsive">
             <div class="box-body">
<form method="post">
			 <a href="dashboard.php?id=<?php echo $_SESSION['tid']; ?>&&mid=<?php echo base64_encode("401"); ?>"><button type="button" class="btn btn-flat bg-orange"><i class="fa fa-mail-reply-all"></i>&nbsp;Back</button> </a>
	
	<hr>				  
			 <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th><input type="checkbox" id="select_all"/></th>
				  <th>Downline ID</th>
                  <th>Upline ID</th>
				  <th>Account Type</th>
                  <th>Date/Time</th>
                 </tr>
                </thead>
                <tbody>
<?php
$select = mysqli_query($link, "SELECT * FROM referral_records WHERE upline_id = '$agentid' ORDER BY id DESC") or die (mysqli_error($link));
if(mysqli_num_rows($select)==0)
{
echo "<div class='alert bg-blue'>No data found yet!.....Check back later!!</div>";
}
else{
while($row = mysqli_fetch_array($select))
{
$id = $row['id'];
$mydatetime = $row['reg_date'];

$utc_date = DateTime::createFromFormat('Y-m-d G:i:s',$mydatetime,new DateTimeZone('America/New_York'));
$acst_date = clone $utc_date; // we don't want PHP's default pass object by reference here
$acst_date->setTimeZone(new DateTimeZone('GMT+1'));
$correctdate = $acst_date->format('Y-m-d g:i A');
?>    
                <tr>
				<td><input id="optionsCheckbox" class="checkbox"  name="selector[]" type="checkbox" value="<?php echo $id; ?>"></td>
				<td><b><?php echo $row['downline_id']; ?></b></td>
				<td><?php echo $row['upline_id']; ?></td>
				<td><b><?php echo $row['accttype']; ?></b></td>
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