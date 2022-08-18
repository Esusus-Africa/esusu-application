<div class="box">

	         <div class="box-body">
			<div class="panel panel-success">
            <div class="panel-heading">
            <h3 class="panel-title"> <a href="inboxmessage.php?id=<?php echo $_SESSION['tid']; ?>&&mid=<?php echo base64_encode('406'); ?>"><i class="fa fa-reply-all"></i></a> Reply Ticket (Closed)</h3>
            </div>
             <div class="box-body">
		  <hr>
<?php
$ticketid =  $_GET['ticketid'];
$select2 = mysqli_query($link, "SELECT * FROM message WHERE ticketid = '$ticketid'") or die (mysqli_error($link));
while($row2 =  mysqli_fetch_array($select2))
{
?>		  
				<div class="box-body">
					<p>
						<h4><b style="color: <?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;"><?php echo $row2['sender_name']; ?></b> <b style="color: <?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>;">(<?php echo $row2['subject']; ?>)</b> - <i><?php echo $row2['date_time']; ?></i></h4>
					</p>
					<p>
						<?php echo $row2['message']; ?>
					</p>
				</div>
				<hr>
<?php } ?>

</div>	
</div>	
</div>
</div>