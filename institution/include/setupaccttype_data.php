<div class="row">       
		    <section class="content">  
	        <div class="box box-success">
            <div class="box-body">
              <div class="table-responsive">
             <div class="box-body">
<form method="post">

	<?php echo ($delete_account_type == '1') ? "<button type='submit' class='btn btn-flat bg-".(($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color'])."' name='delete'><i class='fa fa-times'></i>&nbsp;Multiple Delete</button>" : ""; ?>
			 
	<?php echo ($add_account_type == '1') ? "<a href='addaccttype.php?id=".$_SESSION['tid']."&&mid=".base64_encode("411")."'><button type='button' class='btn btn-flat bg-".(($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color'])."'><i class='fa fa-plus'></i>&nbsp;Savings Product</button></a>" : ""; ?>
	<hr>		
			  
			 <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th><input type="checkbox" id="select_all"/></th>
                  <th>Product Code</th>
                  <th>Product Type</th>
                  <th>Product Name</th>
                  <th>Interest</th>
                  <th>Tenor</th>
                  <th>Minimum Opening Balance</th>
                 </tr>
                </thead>
                <tbody> 
<?php
$date_now = date("Y-m-d");
$select = mysqli_query($link, "SELECT * FROM account_type WHERE merchant_id = '$institution_id' ORDER BY id DESC") or die (mysqli_error($link));
if(mysqli_num_rows($select)==0)
{
echo "<div class='alert bg-blue'>No data found yet!.....Check back later!!</div>";
}
else{
while($row = mysqli_fetch_array($select))
{
$id = $row['id'];

$systemset = mysqli_query($link, "SELECT * FROM systemset");
$fetch_sys = mysqli_fetch_array($systemset);
?> 
                <tr>
				<td><input id="optionsCheckbox" class="checkbox" name="selector[]" type="checkbox" value="<?php echo $row['id']; ?>"></td>
				<td><?php echo $row['id']; ?></td>
				<td><?php echo $row['account_type']; ?></td>
                <td><?php echo ($update_account_type == "1") ? '<a href="edit_accttype.php?id='.$_SESSION['tid'].'&&idm='.$id.'&&mid=NDEx">'.$row['acct_name'].'</a>' : ''; ?></td>
                <td><?php echo $row['interest_rate']; ?>%</td>
                <td><?php echo ($row['tenor'] == "") ? "------" : $row['tenor']; ?></td>
				<td><?php echo $fetch_sys['currency'].number_format($row['opening_balance'],2,'.',','); ?></td>
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
						echo "<script>window.location='setupaccttype.php?id=".$_SESSION['tid']."&&mid=NDEx'; </script>";
							}
							else{
							for($i=0; $i < $N; $i++)
							{
								$result = mysqli_query($link,"DELETE FROM account_type WHERE id ='$id[$i]'");
								echo "<script>alert('Row Delete Successfully!!!'); </script>";
								echo "<script>window.location='setupaccttype.php?id=".$_SESSION['tid']."&&mid=NDEx'; </script>";
							}
							}
							}
?>		
</form>

              </div>


	
</div>	
</div>
</div>	
</section>
</div>