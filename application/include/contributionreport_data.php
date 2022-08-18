<div class="box">
	       <div class="box-body">
			<div class="panel panel-success">
            <div class="panel-heading">
            <h3 class="panel-title"> <a href="dashboard.php?id=<?php echo $_SESSION['tid']; ?>&&mid=NDAx"><button type="button" class="btn btn-flat btn-warning"><i class="fa fa-mail-reply-all"></i>&nbsp;Back</button> </a> <i class="fa fa-area-chart"></i>  Report</h3>
            </div>

             <div class="box-body">
			 
			<form class="form-horizontal" method="post" enctype="multipart/form-data">
			 <div class="alert alert-info"> The Contributions report shows the <b>Contributors Name, Email, Amount and Status</b>. </div>
             <div class="box-body">

<?php
$staffid = $_GET['staff_id'];
$get = mysqli_query($link, "SELECT * FROM user WHERE userid = '$staffid'") or die (mysqli_error($link));
$rows = mysqli_fetch_object($get);

$get2 = mysqli_query($link, "SELECT * FROM systemset") or die (mysqli_error($link));
$rows2 = mysqli_fetch_object($get2);
?>				  

			<input name="branchid" type="hidden" class="form-control" value="<?php echo $rows->branchid; ?>">
			
				<div class="form-group">
                  <div class="col-sm-4"><span style="color:#009900"><b>From</b></span><br>
                  <input name="dfrom" type="date" class="form-control" placeholder="To Date: 2018-05-01" required>
				  <span style="color: red;">Date should be in this format: 2018-05-01</span>
                  </div>
				  
                  <div class="col-sm-4"><span style="color:#009900"><b>To</b></span><br>
                  <input name="dto" type="date" class="form-control" placeholder="To Date: 2018-05-24" required>
				  <span style="color: red;">Date should be in this format: 2018-05-24</span>
                  </div>
				  
                  <div class="col-sm-4"><span style="color:#009900"><b>Project Categories</b></span><br>
				  	<select name="c_cat"  class="form-control select2" required>

				  		<option value="All" selected>All Project</option>
				  <?php
				  $search = mysqli_query($link, "SELECT * FROM campaign_cat");
				  while($get_search = mysqli_fetch_array($search))
				  {
				  ?>
				  		<option value="<?php echo $get_search['id']; ?>"><?php echo $get_search['c_category']; ?></option>
				  <?php } ?>
				  	</select>
					<span style="color: red;">Select the project categories</span>
                  </div>
                </div>
							  
			 </div>
			 
			  <div align="right">
              <div class="box-footer">
                				<button type="reset" class="btn btn-primary btn-flat"><i class="fa fa-times">&nbsp;Reset</i></button>
                				<button name="search" type="submit" class="btn btn-success btn-flat"><i class="fa fa-filter">&nbsp;Search</i></button>

              </div>
			  </div>
			</form> 
			
<?php
if(isset($_POST['search'])){
	$dfrom = mysqli_real_escape_string($link, $_POST['dfrom']);
	$dto = mysqli_real_escape_string($link, $_POST['dto']);
	$c_cat = mysqli_real_escape_string($link, $_POST['c_cat']);
	echo "<script>window.location='contributionreport.php?id=".$_SESSION['tid']."&&dfrom=".$dfrom."&&dto=".$dto."&&c_cat=".$c_cat."&&mid=NDI1'; </script>";
}
?>

<?php
if(isset($_GET['dfrom']))
{
?>

<hr>
<div align="right" style="color: red;"> <h4><b>From <?php echo $_GET['dfrom']; ?> - <?php echo $_GET['dto']; ?></b> (change dates above)</h4> </div>
<br>
<table class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>PayID</th>
				  <th><div align="center">Contributor</div></th>
                  <th><div align="center">Project Contributed for</div></th>
				  <th><div align="center">Email Address</div></th>
                  <th><div align="center">Amount Contributed</div></th>
                  <th><div align="center">Date</div></th>
				  <th><div align="center">Status</div></th>
                 </tr>
                </thead>
                <tbody>
<?php
$dfrom = $_GET['dfrom'];
$dto = $_GET['dto'];
$c_cat = $_GET['c_cat'];
if($c_cat == "All")
{
	$select = mysqli_query($link, "SELECT * FROM campaign_pay_history WHERE pdate BETWEEN '$dfrom' AND '$dto' ORDER BY id") or die (mysqli_error($link));
	while($row = mysqli_fetch_array($select))
	{
	$id = $row['id'];
	$pid= $row['pid'];
	
	$search_project = mysqli_query($link, "SELECT * FROM causes WHERE c_category = '$c_cat'") or die ("Error: " . mysqli_error($link));
	$get_project = mysqli_fetch_array($search_project);
	

	$select1 = mysqli_query($link, "SELECT * FROM systemset") or die (mysqli_error($link));
	$row1 = mysqli_fetch_array($select1);
	$currency = $row1['currency']; 
?>

				<tr>
				<td><b><?php echo $row['pid']; ?></b></td>
				<td><?php echo $row['name']; ?></td>
				<td><?php echo $get_project['campaign_title']; ?></td>
				<td><?php echo $row['email']; ?></td>
				<td><?php echo $row['amount']; ?></td>
				<td><?php echo $row['date']; ?></td>
				<td>
					<?php 
					if($row['status'] == 'Completed')
						{
							echo "<span class='label label-success'>Completed</span>";
						}	
						elseif($row['status'] == 'Fail')
						{
							echo "<span class='label label-danger'>Failed</span>";
						}
						else{
							echo "<span class='label label-info'>Pending</span>";
						}
					?>
				</td>
				</tr>
<?php 
}
}else{
	$select = mysqli_query($link, "SELECT * FROM campaign_pay_history WHERE pid = '$c_cat' AND pdate BETWEEN '$dfrom' AND '$dto' ORDER BY id") or die (mysqli_error($link));
	while($row = mysqli_fetch_array($select))
	{
	$id = $row['id'];
	$pid= $row['pid'];
	
	$search_project = mysqli_query($link, "SELECT * FROM causes WHERE id = '$c_cat'") or die ("Error: " . mysqli_error($link));
	$get_project = mysqli_fetch_array($search_project);

	$select1 = mysqli_query($link, "SELECT * FROM systemset") or die (mysqli_error($link));
	$row1 = mysqli_fetch_array($select1);
	$currency = $row1['currency']; 
?>

				<tr>
				<td><b><?php echo $row['pid'];	?></b></td>
				<td><?php echo $row['name'];	?></td>
				<td><?php echo $get_project['campaign_title']; ?></td>
				<td><?php echo $row['email'];	?></td>
				<td><?php echo $row['amount'];	?></td>
				<td><?php echo $row['date'];	?></td>
				<td>
					<?php 
					if($row['status'] == 'Completed')
						{
							echo "<span class='label label-success'>Completed</span>";
						}	
						elseif($row['status'] == 'Fail')
						{
							echo "<span class='label label-danger'>Failed</span>";
						}
						else{
							echo "<span class='label label-info'>Pending</span>";
						}
					?>
				</td>
				</tr>
<?php
}
}
?>
				</tbody>
                </table> 

<?php 
}
else{
	echo "";
}
?>


</div>	
</div>	
</div>
</div>