<div class="row">
	       
		    <section class="content">  
	        <div class="box box-success">
            <div class="box-body">
              <div class="table-responsive">
             <div class="box-body">
<form method="post">
			 <a href="dashboard?id=<?php echo $_SESSION['tid']; ?>&&mid=<?php echo base64_encode("401"); ?>"><button type="button" class="btn btn-flat bg-orange"><i class="fa fa-mail-reply-all"></i>&nbsp;Back</button> </a> 
	 <?php echo ($delete_backend_branches == '1') ? '<button type="submit" class="btn bg-orange" name="delete"><i class="fa fa-times"></i>&nbsp;Multiple Delete</button>' : ''; ?>
	<hr>				  
			 <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th><input type="checkbox" id="select_all"/></th>
				  <th>Branch ID</th>
                  <th>Branch Name</th>
                  <th>Phone</th>
				  <th>Currency</th>
				  <th>Created</th>
				  <th>Status</th>
                  <th>Actions</th>
                 </tr>
                </thead>
                <tbody>
<?php
$select = mysqli_query($link, "SELECT * FROM branches WHERE created_by = '' ORDER BY id DESC") or die (mysqli_error($link));
if(mysqli_num_rows($select)==0)
{
echo "<div class='alert bg-blue'>No data found yet!.....Check back later!!</div>";
}
else{
while($row = mysqli_fetch_array($select))
{
$id = $row['id'];
$branchid = $row['branchid'];
$bname = $row['bname'];
$currency = $row['currency'];
$c_rate = $row['c_rate'];
$bopendate = $row['bopendate'];
$branch_mobile = $row['branch_mobile'];
$minloan_amount = $row['minloan_amount'];
$maxloan_amount = $row['maxloan_amount'];
$min_interest_rate = $row['min_interest_rate'];
$max_interest_rate = $row['max_interest_rate'];
$bstatus = $row['bstatus'];
?>    
                <tr>
				<td><input id="optionsCheckbox" class="checkbox"  name="selector[]" type="checkbox" value="<?php echo $id; ?>"></td>
                <td><?php echo $branchid; ?></td>
				<td><?php echo $bname; ?></td>
				<td><?php echo $branch_mobile; ?></td>
				<td><?php echo $currency; ?></td>
				<td><?php echo $bopendate; ?></td>
				<td><?php echo $bstatus; ?></td>
				<td align="center">
				<div class="btn-group">
                      <div class="btn-group">
                        <button type="button" class="btn bg-blue btn-flat dropdown-toggle" data-toggle="dropdown">
                          <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu">
                          <?php echo ($update_backend_branches == '1') ? '<li><p><a href="edit_branches?id='.$_SESSION['tid'].'&&idm='.$id.'&&mid=NDAy" class="btn bg-blue"><i class="fa fa-edit">&nbsp;Update Branch</i></a></p></li>' : '<span style="color: red;">--Not Authorized--</span>'; ?>
                          <?php //echo ($view_branch_operations == '1') ? '<li><p><a href="view_transaction?id='.$_SESSION['tid'].'&&idm='.$branchid.'&&mid=NDAy" class="btn bg-blue" target="_blank"><i class="fa fa-search">&nbsp;View Transaction</i></a></p></li>' : '<span style="color: red;">--Not Authorized--</span>'; ?>
						</ul>
                      </div>
                </div>
				</td>
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
						echo "<script>window.location='listbranches?id=".$_SESSION['tid']."&&mid=NDAy'; </script>";
							}
							else{
							for($i=0; $i < $N; $i++)
							{
								$result = mysqli_query($link,"DELETE FROM branches WHERE id ='$id[$i]'");
								echo "<script>alert('Row Delete Successfully!!!'); </script>";
								echo "<script>window.location='listbranches?id=".$_SESSION['tid']."&&mid=NDAy'; </script>";
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