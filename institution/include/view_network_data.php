<div class="box">
	       <div class="box-body">
			<div class="panel panel-success">
            <div class="panel-heading">
            <h3 class="panel-title"><i class="fa fa-user"></i> Member Network (Genealogy) </h3>
            </div>
             <div class="box-body">
                         
             <div class="box-body">
<?php
$id = $_GET['id'];
$pick_direct_upline = mysqli_query($link, "SELECT * FROM borrowers WHERE id = '$id'");
$fetch_direct_upline = mysqli_fetch_object($pick_direct_upline);
$myaccount_id = $fetch_direct_upline->account;
?>
<div class="tree">
	<ul>
		<li>
			<a href="#">
				<?php echo $fetch_direct_upline->account.'<br><b>['.$fetch_direct_upline->lname.'&nbsp;'.$fetch_direct_upline->fname.']</b>'; ?><br>
				<?php echo $fetch_direct_upline->date_time; ?><br>
				R: <?php echo $fetch_direct_upline->referral; ?>
			</a>
			<ul>
				<?php
				$search_downline = mysqli_query($link, "SELECT referral_records.upline_id, borrowers.fname, referral_records.downline_id, borrowers.lname, referral_records.reg_date FROM referral_records LEFT JOIN borrowers ON referral_records.downline_id = borrowers.account WHERE referral_records.upline_id = '$myaccount_id'");
				while($drow = mysqli_fetch_array($search_downline))
				{
					$downline_id = $drow['downline_id'];
					$reg_date = $drow['reg_date'];
				?>
				<li>
					<a href="#">
						<?php echo $downline_id.'<br><b>'.$drow['lname'].' '.$drow['fname'].'</b>'; ?><br>
						<?php echo $reg_date; ?><br>
						R: <?php echo $drow['upline_id']; ?>
					</a>

					<ul>
						<?php
						$search_downline2 = mysqli_query($link, "SELECT referral_records.upline_id, borrowers.fname, referral_records.downline_id, borrowers.lname, referral_records.reg_date FROM referral_records LEFT JOIN borrowers ON referral_records.downline_id = borrowers.account WHERE referral_records.u_id = '$downline_id'");
						while($drow2 = mysqli_fetch_array($search_downline2))
						{
							$downline_id2 = $drow2['downline_id'];
							$reg_date2 = $drow2['reg_date'];
						?>
						<li>
							<a href="#">
								<?php echo $downline_id2.'<br><b>'.$drow2['lname'].' '.$drow2['fname'].'</b>'; ?><br>
								<?php echo $reg_date2; ?><br>
								R: <?php echo $drow2['upline_id']; ?>
							</a>
						</li>
						<?php
						}
						?>
					</ul>
					
				</li>

				<?php
				}
				?>
			</ul>
		</li>
	</ul>
</div>

			 </div>

</div>	
</div>	
</div>
</div>