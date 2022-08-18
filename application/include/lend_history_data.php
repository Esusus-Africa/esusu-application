<div class="row">
		
	       
		    <section class="content">  
	        <div class="box box-success">
            <div class="box-body">
              <div class="table-responsive">
             <div class="box-body">
<form method="post">
	<a href="dashboard.php?id=<?php echo $_SESSION['tid']; ?>&&mid=<?php echo base64_encode("401"); ?>"><button type="button" class="btn bg-orange"><i class="fa fa-mail-reply-all"></i>&nbsp;Back</button> </a> 
	 <?php echo ($delete_customer == '1') ? '<button type="submit" class="btn btn-flat bg-orange" name="delete"><i class="fa fa-times"></i>&nbsp;Multiple Delete</button>' : ''; ?>
	
	<hr>				  
			 <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th><input type="checkbox" id="select_all"/></th>
                  <th>Transaction id</th>
                  <th>Lender's Name</th>
                  <th>Team</th>
				  <th>Campaign</th>
                  <th>Amount Lend</th>
                  <th>Loan ID</th>
                  <th>Loan Status</th>
				  <th>Payment Status</th>
                  <th>Payback Status</th>
                  <th>Actions</th>
                 </tr>
                </thead>
                <tbody>
<?php
$select = mysqli_query($link, "SELECT * FROM lend_history ORDER BY id DESC") or die (mysqli_error($link));
if(mysqli_num_rows($select)==0)
{
echo "<div class='alert bg-orange'>No data found yet!.....Check back later!!</div>";
}
else{
while($row = mysqli_fetch_array($select))
{
$id = $row['id'];
$lender_id = $row['funder_id'];
$team_id = $row['team_id'];
$campaign_id = $row['campaign_id'];

$utc_date = DateTime::createFromFormat('Y-m-d G:i:s',$date_time,new DateTimeZone('America/New_York'));
$acst_date = clone $utc_date; // we don't want PHP's default pass object by reference here
$acst_date->setTimeZone(new DateTimeZone('GMT+1'));
$correctdate = $acst_date->format('Y-m-d g:i A');

//GET BORROWERS NAME
$search_lender = mysqli_query($link, "SELECT * FROM borrowers WHERE account = '$lender_id'");
$fetch_lender = mysqli_fetch_array($search_lender);
$lender_name = $fetch_lender['lname'].' '.$fetch_lender['fname'];

//GET TEAM NAME
$search_team = mysqli_query($link, "SELECT * FROM myteam WHERE id = '$team_id'");
$fetch_team = mysqli_fetch_array($search_team);
$team_name = $fetch_team['team_name'];

//GET CAMPAIGN TITLE
$search_campaign = mysqli_query($link, "SELECT * FROM campaign WHERE id = '$campaign_id'");
$fetch_campaign = mysqli_fetch_array($search_campaign);
$campaign_title = $fetch_campaign['campaign_title'];
?>    
                <tr>
				<td><input id="optionsCheckbox" class="checkbox"  name="selector[]" type="checkbox" value="<?php echo $id; ?>"></td>
				<td><?php echo $row['trans_id']; ?></td>
				<td><?php echo $lender_name; ?></td>
				<td><?php echo $team_name; ?></td>
                <td><?php echo $campaign_title; ?></td>
				<td><?php echo $fname.'&nbsp;'.$lname; ?></td>
				<td><?php echo $fetch_lend_setup['currency'].number_format($row['amount_lend'],2,'.',','); ?></td>
                <td><?php echo ($row['loan_id'] == "") ? "-----" : '<a href="view_loan?id='.$_SESSION['tid'].'">'.$row['loan_id'].'</a>'; ?></td>
				<td><?php echo ($row['lend_status'] == "Onhold" ? "<span class='label bg-orange'>".$row['lend_status']."</span>" : ($row['lend_status'] == "Disbursed" ? "<span class='label bg-blue'>".$row['lend_status']."</span>" : "<span class='label bg-red'>".$row['lend_status']."</span>")); ?></td>
                <td><?php echo ($row['payment_status'] == "Pending" ? "<span class='label bg-orange'>".$row['payment_status']."</span>" : ($row['payment_status'] == "Successful" ? "<span class='label bg-blue'>".$row['payment_status']."</span>" : "<span class='label bg-red'>".$row['payment_status']."</span>")); ?></td>
                <td><?php echo ($row['payback_status'] == "No") ? "<span class='label bg-orange'>".$row['payback_status']."</span>" : "<span class='label bg-blue'>".$row['payback_status']."</span>"; ?></td>
				<td align="center">
				<div class="btn-group">
                      <div class="btn-group">
                        <button type="button" class="btn bg-blue btn-flat dropdown-toggle" data-toggle="dropdown">
                          <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu">
                          <?php echo ($refund_lender == '1') ? '<li><p><a href="#refund_lender.php?id='.$id.'&&mid='.base64_encode("750").'" class="btn btn-default btn-flat" target="_blank"><i class="fa fa-save">&nbsp;Refund</i></a></p></li>' : ''; ?>
						</ul>
                      </div>
                </div>
				</td>
				</tr>
<?php } } ?>
             </tbody>
                </table>  
						<?php
						if(isset($_POST['delete'])){
						$idm = $_GET['id'];
							$id=$_POST['selector'];
							$N = count($id);
						if($id == ''){
						echo "<script>alert('Row Not Selected!!!'); </script>";	
						echo "<script>window.location='lend_history.php?id=".$_SESSION['tid']."&&mid=NzUw'; </script>";
							}
							else{
							for($i=0; $i < $N; $i++)
							{
								$result = mysqli_query($link,"DELETE FROM lend_history WHERE id ='$id[$i]'");
								echo "<script>alert('Row Delete Successfully!!!'); </script>";
								echo "<script>window.location='lend_history.php?id=".$_SESSION['tid']."&&mid=NzUw'; </script>";
							}
							}
							}
						?>	
						
</form>
				

              </div>


	
</div>	
</div>
</div>	
</div>