<div class="row">
    
		    <section class="content">  
	        <div class="box box-success">
            <div class="box-body">
              <div class="table-responsive">
             <div class="box-body">
<form method="post">
			 <a href="dashboard.php?id=<?php echo $_SESSION['tid']; ?>&&mid=<?php echo base64_encode("401"); ?>"><button type="button" class="btn btn-flat bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>"><i class="fa fa-mail-reply-all"></i>&nbsp;Back</button></a> 
	<?php echo ($delete_income == '1') ? '<button type="submit" class="btn btn-flat bg-'.$myaltrow['alternate_color'].' == "" ? orange : '.$myaltrow['alternate_color'].'" name="delete"><i class="fa fa-times"></i>&nbsp;Delete</button>' : ''; ?>		
	<hr>		
			  
			 <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th><input type="checkbox" id="select_all"/></th>
                  <th>IncomeID</th>
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
$select = mysqli_query($link, "SELECT * FROM income WHERE companyid = '$vendorid' ORDER BY id DESC") or die (mysqli_error($link));
if(mysqli_num_rows($select)==0)
{
echo "<div class='alert bg-".(($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color'])."'>No data found yet!.....Check back later!!</div>";
}
else{
while($row = mysqli_fetch_array($select))
{
$id = $row['id'];
$icm_id = $row['icm_id'];

$select1 = mysqli_query($link, "SELECT * FROM systemset") or die (mysqli_error($link));
$row1 = mysqli_fetch_array($select1);
$currency = $row1['currency']; 
?>    
                <tr>
                <td><input id="optionsCheckbox" class="checkbox" name="selector[]" type="checkbox" value="<?php echo $id; ?>"></td>
                <td><?php echo $row['icm_id']; ?></td>
				<td><?php echo $row['icm_type']; ?></td>
				<td><?php echo $currency.number_format($row['icm_amt'],2,".",","); ?></td>
				<td><?php echo $row['icm_desc']; ?></td>
				<td>
<?php
$i = 0;
$select_doc = mysqli_query($link, "SELECT icm_id, icm_receipt FROM income_document WHERE icm_id = '$icm_id'") or die (mysqli_error($link));
if(mysqli_num_rows($select_doc)==0)
{
echo "<div class='alert bg-orange'>No Document Attached!!</div>";
}
else{
while($row_doc = mysqli_fetch_array($select_doc))
{
$i++;
?>
				<p><a href="<?php echo $fetchsys_config['file_baseurl'].$row_doc['icm_receipt']; ?>" target="blank"> View File <?php echo $i; ?> </a></p>
<?php } } ?>
				</td>
				<td><?php echo ($edit_income == '1') ? '<a href="edit_income.php?id='.$_SESSION['tid'].'&&idm='.$id.'&&mid=NTAw"> <button type="button" class="btn bg-'.$myaltrow['theme_color'].' == "" ? blue : '.$myaltrow['theme_color'].' btn-flat"><i class="fa fa-edit"></i> Edit</button></a>' : ''; ?></td>
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
						echo "<script>window.location='listincome.php?id=".$_SESSION['tid']."&&mid=".base64_encode("500")."'; </script>";
							}
							else{
							for($i=0; $i < $N; $i++)
							{
								$result = mysqli_query($link,"DELETE FROM income WHERE id ='$id[$i]'");
								echo "<script>alert('Row Delete Successfully!!!'); </script>";
								echo "<script>window.location='listincome.php?id=".$_SESSION['tid']."&&mid=".base64_encode("500")."'; </script>";
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