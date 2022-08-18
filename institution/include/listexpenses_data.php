<div class="row">
    
		    <section class="content">  
	        <div class="box box-success">
            <div class="box-body">
              <div class="table-responsive">
             <div class="box-body">
<form method="post">
	<a href="dashboard.php?id=<?php echo $_SESSION['tid']; ?>&&mid=<?php echo base64_encode("401"); ?>"><button type="button" class="btn btn-flat bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>"><i class="fa fa-mail-reply-all"></i>&nbsp;Back</button> </a> 
	<?php echo ($del_expense == '1') ? '<button type="submit" class="btn btn-flat bg-'.$myaltrow['alternate_color'].' == "" ? orange : '.$myaltrow['alternate_color'].'" name="delete"><i class="fa fa-times"></i>&nbsp;Delete</button>' : ''; ?>	
	<a href="excel_expenses.php" target="_blank" class="btn bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?> btn-flat"><i class="fa fa-send"></i>&nbsp;Export Excel</a>
	
	<hr>
		
			 <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th><input type="checkbox" id="select_all"/></th>
                  <th>Code</th>
				  <th>ExpID</th>
                  <th>Type</th>
				  <th>Amount</th>
                  <th>Description</th>
                  <th>File</th>
                  <th>Action</th>
                 </tr>
                </thead>
                <tbody>
<?php
$tid = $_SESSION['tid'];
$select = mysqli_query($link, "SELECT * FROM expenses WHERE branchid = '$institution_id' ORDER BY id DESC") or die (mysqli_error($link));
if(mysqli_num_rows($select)==0)
{
echo "<div class='alert bg-".$myaltrow['theme_color'].' == "" ? blue : '.$myaltrow['theme_color']."'>No data found yet!.....Check back later!!</div>";
}
else{
while($row = mysqli_fetch_array($select))
{
$id = $row['id'];
$expid = $row['expid'];

$select1 = mysqli_query($link, "SELECT * FROM systemset") or die (mysqli_error($link));
$row1 = mysqli_fetch_array($select1);
$currency = $row1['currency']; 
?>    
                <tr>
                <td><input id="optionsCheckbox" class="checkbox" name="selector[]" type="checkbox" value="<?php echo $id; ?>"></td>
                <td><?php echo $row['id']; ?></td>
				<td><?php echo $row['expid']; ?></td>
				<td><?php echo $row['exptype']; ?></td>
				<td><?php echo $currency.number_format($row['eamt'],2,".",","); ?></td>
				<td><?php echo $row['edesc']; ?></td>
				<td>
<?php
$i = 0;
$select_doc = mysqli_query($link, "SELECT expid, newfilepath FROM expense_document WHERE expid = '$expid'") or die (mysqli_error($link));
if(mysqli_num_rows($select_doc)==0)
{
echo "<div class='alert bg-".$myaltrow['alternate_color'].' == "" ? orange : '.$myaltrow['alternate_color']."'>No Document Attached!!</div>";
}
else{
while($row_doc = mysqli_fetch_array($select_doc))
{
$i++;
?>
				<p><a href="<?php echo $fetchsys_config['file_baseurl'].$row_doc['newfilepath']; ?>" target="blank"> View File <?php echo $i; ?> </a></p>
<?php } } ?>
				</td>
				<td><?php echo ($edit_expense == '1') ? '<a href="edit_expenses.php?id='.$_SESSION['tid'].'&&idm='.$id.'&&mid=NDIy"> <button type="button" class="btn bg-'.$myaltrow['theme_color'].' == "" ? blue : '.$myaltrow['theme_color'].' btn-flat"><i class="fa fa-edit"></i> Edit</button></a>' : ''; ?></td>
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
						echo "<script>window.location='listexpenses.php?id=".$_SESSION['tid']."&&mid=".base64_encode("422")."'; </script>";
							}
							else{
							for($i=0; $i < $N; $i++)
							{
								$result = mysqli_query($link,"DELETE FROM expenses WHERE id ='$id[$i]'");
								echo "<script>alert('Row Delete Successfully!!!'); </script>";
								echo "<script>window.location='listexpenses.php?id=".$_SESSION['tid']."&&mid=".base64_encode("422")."'; </script>";
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