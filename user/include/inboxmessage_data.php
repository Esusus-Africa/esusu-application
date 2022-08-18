<div class="row">
		       
		    <section class="content">  
	        <div class="box box-success">
            <div class="box-body">
              <div class="table-responsive">
             <div class="box-body">
<form method="post">

<?php
if(isMobileDevice()){
    //Your content or code for mobile devices goes here
?>

        <p>
        <?php
		$select = mysqli_query($link, "SELECT * FROM message WHERE mstatus = 'Pending' AND branchid = '$branchid' AND sender_id = '$tid'") or die ("Error: " . mysqli_error($link));
		$num = mysqli_num_rows($select);	
		?>
			<button type="button" class="btn btn-flat bg-orange"><i class="fa fa-times"></i>&nbsp;Pending:&nbsp;<?php echo number_format($num,0,'.',','); ?></button>
		
		<?php
		$select1 = mysqli_query($link, "SELECT * FROM message WHERE sender_id = '$tid' AND mstatus = 'Opened' AND branchid = '$branchid'") or die ("Error: " . mysqli_error($link));
		$num1 = mysqli_num_rows($select1);	
		?>
			<button type="button" class="btn btn-flat bg-orange"><i class="fa fa-mark"></i>&nbsp;Opened:&nbsp;<?php echo number_format($num1,0,'.',','); ?></button>
        </p>
        <p>
            <?php
		$select2 = mysqli_query($link, "SELECT * FROM message WHERE mstatus = 'Closed' AND branchid = '$branchid' AND sender_id = '$tid'") or die ("Error: " . mysqli_error($link));
		$num2 = mysqli_num_rows($select2);	
		?>
			<button type="button" class="btn btn-flat bg-blue"><i class="fa fa-tick"></i>&nbsp;Closed:&nbsp;<?php echo number_format($num2,0,'.',','); ?></button>
			
            <a href="newmessage.php?tid=<?php echo $_SESSION['tid']; ?>&&acn=<?php echo $_SESSION['acctno']; ?>&&mid=<?php echo base64_encode("406"); ?>"><button type="button" class="btn bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>"><i class="fa fa-plus"></i>&nbsp;New Ticket</button></a>
        </p>
	    
<?php
}
else{
    ?>
    
	<a href="dashboard.php?tid=<?php echo $_SESSION['tid']; ?>&&acn=<?php echo $_SESSION['acctno']; ?>&&mid=<?php echo base64_encode("401"); ?>"><button type="button" class="btn bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>"><i class="fa fa-mail-reply-all"></i>&nbsp;Back</button> </a> 

	<button type="submit" class="btn bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>" name="delete"><i class="fa fa-times"></i>&nbsp;Multiple Delete</button>
	
	<a href="outboxmessage.php?tid=<?php echo $_SESSION['tid']; ?>&&acn=<?php echo $_SESSION['acctno']; ?>&&mid=<?php echo base64_encode("406"); ?>"><button type="button" class="btn bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>"><i class="fa fa-envelope"></i>&nbsp;Closed Tickets</button></a>
	<a href="newmessage.php?tid=<?php echo $_SESSION['tid']; ?>&&acn=<?php echo $_SESSION['acctno']; ?>&&mid=<?php echo base64_encode("406"); ?>"><button type="button" class="btn bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>"><i class="fa fa-plus"></i>&nbsp;New Ticket</button></a>
	<?php
		$select = mysqli_query($link, "SELECT * FROM message WHERE mstatus = 'Pending' AND branchid = '$branchid' AND sender_id = '$tid'") or die ("Error: " . mysqli_error($link));
		$num = mysqli_num_rows($select);	
		?>
			<button type="button" class="btn btn-flat bg-orange"><i class="fa fa-times"></i>&nbsp;Pending:&nbsp;<?php echo number_format($num,0,'.',','); ?></button>
		
		<?php
		$select1 = mysqli_query($link, "SELECT * FROM message WHERE sender_id = '$tid' AND mstatus = 'Opened' AND branchid = '$branchid'") or die ("Error: " . mysqli_error($link));
		$num1 = mysqli_num_rows($select1);	
		?>
			<button type="button" class="btn btn-flat bg-orange"><i class="fa fa-mark"></i>&nbsp;Opened:&nbsp;<?php echo number_format($num1,0,'.',','); ?></button>
		
		<?php
		$select2 = mysqli_query($link, "SELECT * FROM message WHERE mstatus = 'Closed' AND branchid = '$branchid' AND sender_id = '$tid'") or die ("Error: " . mysqli_error($link));
		$num2 = mysqli_num_rows($select2);	
		?>
			<button type="button" class="btn btn-flat bg-blue"><i class="fa fa-tick"></i>&nbsp;Closed:&nbsp;<?php echo number_format($num2,0,'.',','); ?></button>

<?php    
}
?>

	<hr>		
			  
			 <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th><input type="checkbox" id="select_all"/></th>
                  <th>Ticket ID</th>
				  <th>Branch Code</th>
                  <th>Sender</th>
				  <th>Subject</th>
                 </tr>
                </thead>
                <tbody> 
<?php
$tid = $_SESSION['tid'];
$select = mysqli_query($link, "SELECT * FROM message WHERE mstatus != 'Closed' AND sender_id = '$tid' OR receiver_id = '$tid' ORDER BY id DESC") or die (mysqli_error($link));
if(mysqli_num_rows($select)==0)
{
echo "<div class='alert bg-blue'>No Ticket found!.....Check back later!!</div>";
}
else{
while($row = mysqli_fetch_array($select))
{
$id = $row['id'];
$sender_id = $row['sender_id'];
$receiver_id= $row['receiver_id'];
$ticketid = $row['ticketid'];
$subject = $row['subject'];
$sender_name = $row['sender_name'];
$mstatus = $row['mstatus'];
$branchid = $row['branchid'];
$date = $row['date_time'];
?>   
                <tr>
				<td><input id="optionsCheckbox" class="checkbox" name="selector[]" type="checkbox" value="<?php echo $id; ?>"></td>
                <td><?php echo ($tid == $receiver_id && $mstatus == 'Pending' ? '<a href="view_msg.php?tid='.$_SESSION['tid'].'&&ticketid='.$ticketid.'&&id='.$id.'&&acn='.$_SESSION['acctno'].'&&mid='.base64_encode("406").'" target="_blank"><b>'.$ticketid.'</b></a>' : ($mstatus == 'Opened' && $tid == $sender_id ? '<a href="view_msg.php?tid='.$_SESSION['tid'].'&&ticketid='.$ticketid.'&&id='.$id.'&&acn='.$_SESSION['acctno'].'&&mid='.base64_encode("406").'" target="_blank">'.$ticketid.'</a>' : ($mstatus == 'Opened' && $tid != $sender_id ? '<a href="view_msg.php?tid='.$_SESSION['tid'].'&&ticketid='.$ticketid.'&&id='.$id.'&&acn='.$_SESSION['acctno'].'&&mid='.base64_encode("406").'" target="_blank">'.$ticketid.'</a>' : $ticketid))); ?>
				</td>
				<td><?php echo $branchid; ?></td>
				<td><?php echo $sender_name; ?></td>
				<td><?php echo ($mstatus == 'Opened' ? $subject.' - '.'<span class="label bg-orange">'.$mstatus.'</span>' : ($mstatus == 'Closed' ? $subject.' - '.'<span class="label bg-blue">'.$mstatus.'</span>' : $subject.' - '.'<span class="label bg-red">'.$mstatus.'</span>')); ?></td>						    
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
						echo "<script>window.location='inboxmessage.php?id=".$_SESSION['tid']."&&mid=".base64_encode("406")."'; </script>";
							}
							else{
							for($i=0; $i < $N; $i++)
							{
								$result = mysqli_query($link,"DELETE FROM message WHERE id ='$id[$i]'");
								echo "<script>alert('Row Delete Successfully!!!'); </script>";
								echo "<script>window.location='inboxmessage.php?id=".$_SESSION['tid']."&&mid=".base64_encode("406")."'; </script>";
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