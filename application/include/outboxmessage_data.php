<div class="row">
		
	       
		    <section class="content">  
	        <div class="box box-success">
            <div class="box-body">
              <div class="table-responsive">
             <div class="box-body">
<form method="post">
			 <a href="dashboard.php?id=<?php echo $_SESSION['tid']; ?>"><button type="button" class="btn btn-flat bg-orange"><i class="fa fa-mail-reply-all"></i>&nbsp;Back</button> </a> 
 	<?php echo ($delete_tickets == 1) ? '<button type="submit" class="btn btn-flat bg-orange" name="delete"><i class="fa fa-times"></i>&nbsp;Multiple Delete</button>' : ''; ?>
		
 	<?php
 	$select2 = mysqli_query($link, "SELECT * FROM message WHERE mstatus = 'Closed'") or die ("Error: " . mysqli_error($link));
 	$num2 = mysqli_num_rows($select2);	
 	?>
 		<?php echo ($close_tickets == 1) ? '<button type="button" class="btn btn-flat bg-blue"><i class="fa fa-tick"></i>&nbsp;Closed:&nbsp;'.number_format($num2,0,'.',',').'</button>' : ''; ?>
	<hr>		
			  
			 			 <table id="example1" class="table table-bordered table-striped">
			                 <thead>
			                 <tr>
			                   <th><input type="checkbox" id="select_all"/></th>
			                   <th>Ticket ID</th>
			 				  <th>Branch Code</th>
			                   <th>Customer</th>
			 				  <th>Subject</th>
			                  </tr>
			                 </thead>
			                 <tbody> 
			 <?php
			 $select = mysqli_query($link, "SELECT * FROM message WHERE mstatus = 'Closed' ORDER BY id DESC") or die (mysqli_error($link));
			 if(mysqli_num_rows($select)==0)
			 {
			 echo "<div class='alert bg-blue'>No Closed Ticket found!.....Check back later!!</div>";
			 }
			 else{
			 while($row = mysqli_fetch_array($select))
			 {
			 $id = $row['id'];
			 $ticketid = $row['ticketid'];
			 $subject = $row['subject'];
			 $sender_name = $row['sender_name'];
			 $mstatus = $row['mstatus'];
			 $branchid = $row['branchid'];
			 $date = $row['date_time'];
			 ?>   
			                 <tr>
			 				<td><input id="optionsCheckbox" class="checkbox" name="selector[]" type="checkbox" value="<?php echo $id; ?>"></td>
			                 <td><?php echo '<a href="view_msg1.php?ticketid='.$ticketid.'&&id='.$id.'&&mid='.base64_encode("406").'">'.$ticketid.'</a>'; ?>
			 				</td>
			 				<td><?php echo $branchid; ?></td>
			 				<td><?php echo $sender_name; ?></td>
			 				<td><?php echo $subject.' - '.'<span class="label bg-blue">'.$mstatus.'</span>'; ?></td>						    
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
						echo "<script>window.location='outboxmessage.php?id=".$_SESSION['tid']."'; </script>";
							}
							else{
							for($i=0; $i < $N; $i++)
							{
								$result = mysqli_query($link,"DELETE FROM message WHERE id ='$id[$i]'");
								echo "<script>alert('Row Delete Successfully!!!'); </script>";
								echo "<script>window.location='outboxmessage.php?id=".$_SESSION['tid']."'; </script>";
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