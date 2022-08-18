<div class="row">
		
	       
		    <section class="content">  
	        <div class="box box-success">
            <div class="box-body">
              <div class="table-responsive">
             <div class="box-body">
<form method="post">
	<a href="my_savings_plan.php?tid=<?php echo $_SESSION['tid']; ?>&&acn=<?php echo $_SESSION['acctno']; ?>&&mid=NDA3"><button type="button" class="btn bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>"><i class="fa fa-mail-reply-all"></i>&nbsp;Back</button> </a>
	<hr>				  
			 <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th><input type="checkbox" id="select_all"/></th>
				  <th>Cluster</th>
                  <th>Group Info.</th>
				  <th>Channel</th>
                  <th>Amount</th>
                  <th>Timeline</th>
                  <th>Total Member</th>
                  <th>Start Date</th>
                  <th>Status</th>
                  <th>Action</th>
                 </tr>
                </thead>
                <tbody>
<?php
$select = mysqli_query($link, "SELECT * FROM group_member WHERE userid = '$bvirtual_acctno' AND status = 'Approved' ORDER BY id DESC") or die (mysqli_error($link));
if(mysqli_num_rows($select)==0)
{
echo "<div class='alert bg-".$myaltrow['theme_color'].' == "" ? blue : '.$myaltrow['theme_color']."'>No data found yet!.....Check back later!!</div>";
}
else{
while($row = mysqli_fetch_array($select))
{
    $id = $row['id'];
    $groupcode = $row['groupcode'];
    $cluster = $row['cluster'].$row['positionNum'];

    $select2 = mysqli_query($link, "SELECT * FROM group_contribution WHERE groupcode = '$groupcode' AND isActive = 'true' AND status = 'Unlocked'");
    $fetch_select2 = mysqli_fetch_array($select2);
?>    
                <tr>
				<td><input id="optionsCheckbox" class="checkbox"  name="selector[]" type="checkbox" value="<?php echo $id; ?>"></td>
                <td><b><?php echo $cluster; ?></b></td>
                <td>
                    Group Code: <b><?php echo $fetch_select2['groupcode']; ?></b><br>
                    Group Name: <b><?php echo $fetch_select2['gname']; ?></b><br>
                    Group Limit: <b><?php echo $fetch_select2['glimit']; ?></b>
                </td>
                <td>
                    Collection: <b><?php echo $fetch_select2['cmode']; ?></b><br>
                    Disbursement: <b><?php echo $fetch_select2['dmode']; ?></b>
                </td>
				<td><?php echo $fetch_select2['amount']; ?></td>
				<td>
                    Collection Frequency: <b><?php echo $fetch_select2['cinterval']; ?></b><br>
                    Collection Duration: <b><?php echo $fetch_select2['duration']; ?></b><br>
                    Disbursment Frequency: <b><?php echo $fetch_select2['dinterval']; ?></b>
                </td>
				<td><?php echo $row['plan_code']; ?></td>
				<td><?php echo $row['subscription_code']; ?></td>
				<td><?php echo ($row['status'] == "Approved" ? '<span class="label bg-blue">Approve</span>' : ($row['status'] == "Disapproved" ? '<span class="label bg-orange">Disapproved</span>' : ($row['status'] == "Pending" ? '<span class="label bg-red">Pending</span>' : '<span class="label bg-blue">Pending</span>'))); ?></td>
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