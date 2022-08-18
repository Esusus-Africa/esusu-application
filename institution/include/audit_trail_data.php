<div class="row">       
		    <section class="content">  
	        <div class="box box-success">
            <div class="box-body">
              <div class="table-responsive">
             <div class="box-body">
<form method="post">
			 <a href="dashboard.php?tid=<?php echo $_SESSION['tid']; ?>&&mid=<?php echo base64_encode("401"); ?>"><button type="button" class="btn bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>"><i class="fa fa-mail-reply-all"></i>&nbsp;Back</button> </a>	
	
	<hr>		
			  
			 <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th><input type="checkbox" id="select_all"/></th>
                  <th>Username</th>
				  <th>IP Address</th>
                  <th>Browser details</th>
                  <th>Activities Tracked</th>
				  <th>Status</th>
                  <th>Date/Time</th>
                 </tr>
                </thead>
                <tbody>
<?php
$tid = $_SESSION['tid'];
$select = mysqli_query($link, "SELECT * FROM audit_trail WHERE companyid = '$institution_id' ORDER BY id DESC") or die (mysqli_error($link));
if(mysqli_num_rows($select)==0)
{
echo "<div class='alert bg-blue'>Coming Soon!!</div>";
}
else{
while($row = mysqli_fetch_array($select))
{
	$status = $row['status'];
?>    
                <tr>
                <td><input id="optionsCheckbox" class="checkbox" name="selector[]" type="checkbox" value="<?php echo $row['id']; ?>"></td>
                <td><?php echo $username; ?></td>
                <td>
                	<a href="banned_ip.php?tid=<?php echo $_SESSION['tid']; ?>&&mid=<?php echo base64_encode("650"); ?>">
                	<?php echo $row['ip_addrs']; ?>
                	</a>
                </td>
				<td><?php echo $row['browser_details']; ?></td>
				<td><?php echo $row['activities_tracked']; ?></td>				
				<td><?php echo ($status == 'logged-in') ? '<span class="label label-success">Logged-in</span>' : '<span class="label label-danger">Logged-out</span>'; ?></td>
				<td><?php echo $row['date_time']; ?></td>
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