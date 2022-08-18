<div class="box">
	       <div class="box-body">
			<div class="panel panel-success">
            <div class="panel-heading bg-blue">
            <h3 class="panel-title"><a href="newsboard.php?id=<?php echo $_SESSION['tid']; ?>&&mid=NDE1"><button type="button" class="btn btn-flat bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>"><i class="fa fa-mail-reply-all"></i>&nbsp;Back</button> </a>  <i class="fa fa-briefcase"></i> Notice Board</h3>
            </div>
             <div class="box-body">		

<?php
$nid = $_GET['nid'];
$select2 = mysqli_query($link, "SELECT * FROM newboard WHERE id = '$nid'") or die ("Error: " . mysqli_error($link));
while($row2 =  mysqli_fetch_array($select2))
{
	$search_cs = mysqli_query($link, "SELECT * FROM member_case WHERE mid = '$id' AND status = 'Defaulted'") or die ("Error: " . mysqli_error($link));
	$getting_cs = mysqli_fetch_object($search_cs);

	$search_user = mysqli_query($link, "SELECT * FROM borrowers WHERE id = '$id'") or die ("Error: " . mysqli_error($link));
	$getting_user = mysqli_fetch_object($search_user);
?>		  
				<div class="box-body">
					<p>
						<h4><b style="color: <?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;"><?php echo $row2['caption']; ?></b></h4><i><p> Posted on <?php echo date('l, F, d, Y', strtotime($row2['sent_date'])); ?></p></i>
					</p>
					<p>
						<?php echo $row2['details']; ?>
					</p>
				</div>
				<hr>
<?php } ?>


</div>	
</div>	
</div>
</div>