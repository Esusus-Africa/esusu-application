<div class="row">       
		    <section class="content">  
	        <div class="box box-success">
            <div class="box-body">
              <div class="table-responsive">
             <div class="box-body">
<form method="post">

<button type="submit" class="btn btn-flat bg-orange" name="delete"><i class="fa fa-times"></i>&nbsp;Multiple Delete</button>
			 
<a href="add_coupon.php?id=<?php echo $_SESSION['tid']; ?>&&mid=NDEx"><button type="button" class="btn btn-flat bg-blue"><i class="fa fa-plus"></i>&nbsp;Add Coupon</button></a>
	<hr>		
			  
			 <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr align="center">
                  <th><input type="checkbox" id="select_all"/></th>
                  <th>Coupon Code</th>
                  <th>Coupon Type</th>
                  <th>RateType</th>
                  <th>Rate</th>
				  <th>Max Redemptions</th>
				  <th>Redemption Count</th>
				  <th>Created At</th>
                 </tr>
                </thead>
                <tbody> 
<?php
$date_now = date("Y-m-d");
$select = mysqli_query($link, "SELECT * FROM coupon_setup ORDER BY id DESC") or die (mysqli_error($link));
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
				<td><b><?php echo $row['coupon_code']; ?></b></td>
				<td><?php echo ($row['coupon_type'] == "one_off") ? "<span class='label bg-blue'>One-off</span>" : "<span class='label bg-orange'>Repeating</span>"; ?>
					<?php echo ($row['coupon_type'] == "one_off") ? '<hr>Duartion from:<br><span style="font-size: 10px;"><b>'.date("Y-m-d h:m:s", strtotime($row['start_date'])).' - '.date("Y-m-d h:m:s", strtotime($row['end_date'])).'</b></span>' : ''; ?>
				</td>
                <td><?php echo $row['amt_type']; ?></td>
				<td><?php echo ($row['amt_type'] == "percent_off") ? $row['rate'].'%' : number_format($row['rate'],2,'.',','); ?></td>
				<td><?php echo $row['max_redemption']; ?></td>
				<td><?php echo $row['redemption_count'].' / '.$row['max_redemption']; ?></td>
				<td><?php echo $row['date_time']; ?></td>
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
						echo "<script>window.location='setup_coupon.php?id=".$_SESSION['tid']."&&mid=NDEx'; </script>";
							}
							else{
							for($i=0; $i < $N; $i++)
							{
								$result = mysqli_query($link,"DELETE FROM coupon_setup WHERE id ='$id[$i]'");
								echo "<script>alert('Row Delete Successfully!!!'); </script>";
								echo "<script>window.location='setup_coupon.php?id=".$_SESSION['tid']."&&mid=NDEx'; </script>";
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