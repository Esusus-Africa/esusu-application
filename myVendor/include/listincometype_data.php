<div class="row">       
		    <section class="content">  
	        <div class="box box-success">
            <div class="box-body">
              <div class="table-responsive">
             <div class="box-body">
<form method="post">
			<?php echo ($add_income == 1) ? '<a href="newincome.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("500").'"><button type="button" class="btn bg-orange"><i class="fa fa-mail-reply-all"></i>&nbsp;Back</button></a>' : ''; ?> 

			<?php echo ($delete_incometype == 1) ? '<button type="submit" class="btn btn-flat bg-orange" name="delete"><i class="fa fa-times"></i>&nbsp;Multiple Delete</button>' : ''; ?>
			 
			<?php echo ($add_view_incometype == 1) ? '<a href="addincometype.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("422").'"><button type="button" class="btn btn-flat bg-blue"><i class="fa fa-plus"></i>&nbsp;Add Income Type</button></a>' : ''; ?>

	<hr>		
			  
			 <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th><input type="checkbox" id="select_all"/></th>
                  <th>Name</th>
                  <th>Action</th>
                 </tr>
                </thead>
                <tbody> 
<?php
$date_now = date("Y-m-d");
$select = mysqli_query($link, "SELECT * FROM icmtype ORDER BY id") or die (mysqli_error($link));
if(mysqli_num_rows($select)==0)
{
echo "<div class='alert bg-blue'>No data found yet!.....Check back later!!</div>";
}
else{
while($row = mysqli_fetch_array($select))
{
$id = $row['id'];
?> 
                <tr>
				<td><input id="optionsCheckbox" class="checkbox" name="selector[]" type="checkbox" value="<?php echo $row['id']; ?>"></td>
                <td><?php echo $row['incometype']; ?></td>
                <td><?php echo ($edit_incometype == 1) ? '<a href="edit_icmtype.php?idm='.$id.'&&id='.$_SESSION['tid'].'&&mid='.base64_encode("500").'"><button type="button" class="btn bg-blue btn-flat "><i class="fa fa-eye"></i></button></a>' : '----'; ?></td>	    
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
						echo "<script>window.location='listincometype.php?id=".$_SESSION['tid']."&&mid=NDIy'; </script>";
							}
							else{
							for($i=0; $i < $N; $i++)
							{
								$result = mysqli_query($link,"DELETE FROM icmtype WHERE id ='$id[$i]'");
								echo "<script>alert('Row Delete Successfully!!!'); </script>";
								echo "<script>window.location='listincometype.php?id=".$_SESSION['tid']."&&mid=NDIy'; </script>";
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