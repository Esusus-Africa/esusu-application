<div class="row">       
		    <section class="content">  
	        <div class="box box-success">
            <div class="box-body">
              <div class="table-responsive">
             <div class="box-body">
    <form method="post">

			 <?php echo "<button type='submit' class='btn btn-flat bg-".(($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color'])."' name='delete'><i class='fa fa-times'></i>&nbsp;Multiple Delete</button>"; ?>
			 
            <?php echo "<a href='addstock.php?id=".$_SESSION['tid']."&&mid=NDEx'><button type='button' class='btn btn-flat bg-".(($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color'])."'><i class='fa fa-plus'></i>&nbsp;Add Stock</button></a>"; ?>
	
            <hr> 
			  
			<table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th><input type="checkbox" id="select_all"/></th>
                  <th>SKU</th>
                  <th>Item Name</th>
                  <th>Amount (per Qty)</th>
                  <th>Qty</th>
                  <th>DateTime</th>
				          <th>Action</th>
                 </tr>
                </thead>
                <tbody> 
<?php
$date_now = date("Y-m-d");
$select = mysqli_query($link, "SELECT * FROM loan_stock WHERE merchantid = '$institution_id' ORDER BY id DESC") or die (mysqli_error($link));
if(mysqli_num_rows($select)==0)
{
echo "<div class='alert bg-".$myaltrow['theme_color'].' == "" ? blue : '.$myaltrow['theme_color']."'>No data found yet!.....Check back later!!</div>";
}
else{
while($row = mysqli_fetch_array($select))
{
$id = $row['id'];
?> 
                <tr>
				<td><input id="optionsCheckbox" class="checkbox" name="selector[]" type="checkbox" value="<?php echo $row['id']; ?>"></td>
                <td><?php echo $row['skuCode']; ?></td>
                <td><?php echo $row['item_name']; ?></td>
                <td><?php echo $row['amount']; ?></td>
                <td><?php echo $row['qty']; ?></td>
			    <td><?php echo $row['date_time']; ?></td>
                <td><a href="editStock.php?id=<?php echo $_SESSION['tid']; ?>&&idm=<?php echo $id; ?>&&mid=NDEx"><button type="button" class="btn bg-<?php echo ($myaltrow['theme_color'] == "") ? 'blue' : $myaltrow['theme_color']; ?> btn-flat"><i class="fa fa-edit"></i> Edit Stock</button></a></td>
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
						echo "<script>window.location='setupstock.php?id=".$_SESSION['tid']."&&mid=NDEx'; </script>";
							}
							else{
							for($i=0; $i < $N; $i++)
							{
								$result = mysqli_query($link,"DELETE FROM loan_stock WHERE id ='$id[$i]'");
								echo "<script>alert('Row Delete Successfully!!!'); </script>";
								echo "<script>window.location='setupstock.php?id=".$_SESSION['tid']."&&mid=NDEx'; </script>";
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