<div class="row">       
		    <section class="content">  
	        <div class="box box-success">
            <div class="box-body">
              <div class="table-responsive">
             <div class="box-body">
<form method="post">

			 <?php echo ($delete_msavings_plan == 1) ? "<button type='submit' class='btn btn-flat bg-".(($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color'])."' name='delete'><i class='fa fa-times'></i>&nbsp;Multiple Delete</button>" : ""; ?>
			 
<?php echo ($add_mloan_product == 1) ? "<a href='addloanprd.php?id=".$_SESSION['tid']."&&mid=NDA1'><button type='button' class='btn btn-flat bg-".(($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color'])."'><i class='fa fa-plus'></i>&nbsp;Setup Loan Product</button></a>" : ""; ?>
	
	<hr>		
			  
			 <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th><input type="checkbox" id="select_all"/></th>
                  <th>Category</th>
                  <th>Product Name</th>
                  <th>Interest Type</th>
                  <th>Interest on Duration</th>
				  <th>Duration</th>
				  <th>Visible to Others</th>
				  <th>Action</th>
                 </tr>
                </thead>
                <tbody> 
<?php
$date_now = date("Y-m-d");
$select = mysqli_query($link, "SELECT * FROM loan_product WHERE vendorid = '$vendorid' ORDER BY id DESC") or die (mysqli_error($link));
if(mysqli_num_rows($select)==0)
{
echo "<div class='alert bg-".(($myaltrow['theme_color'] == "") ? 'blue' : $myaltrow['theme_color'])."'>No data found yet!.....Check back later!!</div>";
}
else{
while($row = mysqli_fetch_array($select))
{
$id = $row['id'];
?> 
                <tr>
				<td><input id="optionsCheckbox" class="checkbox" name="selector[]" type="checkbox" value="<?php echo $row['id']; ?>"></td>
                <td><?php echo $row['category']; ?></td>
                <td><?php echo $row['pname']; ?></td>
                <td><?php echo $row['interest_type']; ?></td>
				<td><?php echo $row['interest']; ?>%</td>
				<td><?php echo ($row['tenor'] == "Monthly") ? $row['duration']." Month(s)" : $row['duration']." Week(s)"; ?></td>
				<td><?php echo $row['visibility']; ?></td>
				<td><a href="edit_loanprd.php?id=<?php echo $_SESSION['tid']; ?>&&idm=<?php echo $id; ?>&&mid=NTAw"> <button type="button" class="btn bg-<?php echo ($myaltrow['theme_color'] == "") ? 'blue' : $myaltrow['theme_color']; ?> btn-flat"><i class="fa fa-edit"></i> Edit</button></a></td>
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
						echo "<script>window.location='setuploanprd.php?id=".$_SESSION['tid']."&&mid=NDA1'; </script>";
							}
							else{
							for($i=0; $i < $N; $i++)
							{
								$result = mysqli_query($link,"DELETE FROM loan_product WHERE id ='$id[$i]'");
								echo "<script>alert('Row Delete Successfully!!!'); </script>";
								echo "<script>window.location='setuploanprd.php?id=".$_SESSION['tid']."&&mid=NDA1'; </script>";
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