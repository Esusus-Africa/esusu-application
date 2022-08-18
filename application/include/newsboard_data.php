<div class="row">       
		    <section class="content">  
	        <div class="box box-success">
            <div class="box-body">
              <div class="table-responsive">
             <div class="box-body">
<form method="post">

			<?php echo ($delete_notice == 1) ? '<button type="submit" class="btn btn-flat bg-orange" name="delete"><i class="fa fa-times"></i>&nbsp;Multiple Delete</button>' : ''; ?>
			 
	<?php echo ($add_notice == 1) ? '<a href="add_newsboard.php?id='.$_SESSION['tid'].'&&mid=NDE1"><button type="button" class="btn btn-flat bg-blue"><i class="fa fa-plus"></i>&nbsp;Add Notice</button></a>' : ''; ?>
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
echo "<div class='alert bg-blue'>No data found yet!.....Check back later!!</div>";
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
				<td><?php echo ($view_notice_info == 1) ? '<a href="view_newsboard.php?id='.$_SESSION['tid'].'&&nid='.$id.'&&mid=NDE1"><b>View</b></a>' : ''; ?></td>
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
						echo "<script>window.location='newsboard.php?id=".$_SESSION['tid']."&&mid=NDE1'; </script>";
							}
							else{
							for($i=0; $i < $N; $i++)
							{
								$result = mysqli_query($link,"DELETE FROM newboard WHERE id ='$id[$i]'");
								echo "<script>alert('Row Delete Successfully!!!'); </script>";
								echo "<script>window.location='newsboard.php?id=".$_SESSION['tid']."&&mid=NDE1'; </script>";
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