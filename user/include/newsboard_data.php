<div class="row">       
		    <section class="content">  
	        <div class="box box-success">
            <div class="box-body">
              <div class="table-responsive">
             <div class="box-body">
<form method="post">
			 	<hr>		
			  
			 <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th><input type="checkbox" id="select_all"/></th>
                  <th>Username</th>
                  <th>Sent Date</th>
        				  <th>Caption</th>
        				  <th>Action</th>
                 </tr>
                </thead>
                <tbody> 
<?php
$date_now = date("Y-m-d");
$select = mysqli_query($link, "SELECT * FROM newboard ORDER BY id DESC") or die (mysqli_error($link));
if(mysqli_num_rows($select)==0)
{
echo "<div class='alert bg-".$myaltrow['theme_color'].' == "" ? blue : '.$myaltrow['theme_color']."'>No data found yet!.....Check back later!!</div>";
}
else{
while($row = mysqli_fetch_array($select))
{
$id = $row['id'];
$posted_by = $row['posted_by'];

$search_user = mysqli_query($link, "SELECT * FROM user WHERE id = '$posted_by'");
$fetch_user= mysqli_fetch_object($search_user);
?> 
                <tr>
				<td><input id="optionsCheckbox" class="checkbox" name="selector[]" type="checkbox" value="<?php echo $row['id']; ?>"></td>
                <td><?php echo $fetch_user->username; ?></td>
				<td><?php echo date('M, d Y', strtotime($row['sent_date'])); ?></td>
				<td><?php echo $row['caption']; ?></td>
				<td><a href="view_newsboard.php?tid=<?php echo $_SESSION['tid']; ?>&&acn=<?php echo $_SESSION['acctno']; ?>&&nid=<?php echo $id; ?>&&mid=NDE1"><b>View</b></a></td>
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