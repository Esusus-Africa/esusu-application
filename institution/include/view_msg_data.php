<div class="box">

	         <div class="box-body">
			<div class="panel panel-success">
            <div class="panel-heading">
            <h3 class="panel-title"> <a href="inboxmessage.php?id=<?php echo $_SESSION['tid']; ?>&&mid=<?php echo base64_encode('406'); ?>"><i class="fa fa-reply-all"></i></a> Reply Ticket</h3>
            </div>
             <div class="box-body">
<?php
$id =  $_GET['id'];
$select = mysqli_query($link, "SELECT * FROM message WHERE id = '$id'") or die (mysqli_error($link));
$row =  mysqli_fetch_array($select);
?>
		<form class="form-horizontal" method="post" enctype="multipart/form-data" action="process_msg1.php">

			 <div class="box-body">
			 
				<input name="ticketid" type="hidden" class="form-control" value="<?php echo $row['ticketid']; ?>">
				<input name="receiver_id" type="hidden" class="form-control" value="<?php echo $row['sender_id']; ?>">
				<input name="subject" type="hidden" class="form-control" value="<?php echo $row['subject'].' - Reply'; ?>">
				
				<div class="box-body">
					<p>
						<h4><b style="color: <?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;"><?php echo $row['sender_name']; ?></b> <b style="color: <?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>;">(<?php echo $row['subject']; ?>)</b> - <i><?php echo $row['date_time']; ?></i></h4>
					</p>
					<p>
						<?php echo $row['message']; ?>
					</p>
				</div>
			
  			 <div class="box-body">
				  
  				   <div class="form-group">
                    	<div class="col-sm-12">
  					<textarea name="message" id="editor1"  class="form-control" rows="2" cols="80"></textarea>
             		</div>
  				</div>
  				</div>
				
  				<div align="right">
                <div class="box-footer">
                  				<button name="send" type="submit" class="btn bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?> btn-flat"><i class="fa fa-save">&nbsp;Send</i></button>

                </div>
  			  </div>
		  </div>
		  </form>
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