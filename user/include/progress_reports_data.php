<div class="box">
	       <div class="box-body">
			<div class="panel panel-success">
            <div class="panel-heading">
            <h3 class="panel-title"> <a href="dashboard.php?tid=<?php echo $_SESSION['tid']; ?>&&acn=<?php echo $_SESSION['acctno']; ?>&&mid=NDAx"><button type="button" class="btn btn-flat btn-warning"><i class="fa fa-mail-reply-all"></i>&nbsp;Back</button> </a> <i class="fa fa-area-chart"></i>  Report</h3>
            </div>

             <div class="box-body">
			 
			<form class="form-horizontal" method="post" enctype="multipart/form-data">
			 <div class="alert alert-info"> The Campaign report shows the <b>Progress on your Project / Campaign</b>. </div>
             <div class="box-body">

			
				<div class="form-group">
                  <div class="col-sm-4"><span style="color:#009900"><b>From</b></span><br>
                  <input name="dfrom" type="date" class="form-control" placeholder="To Date: 2018-05-01" required>
				  <span style="color: red;">Date should be in this format: 2018-05-01</span>
                  </div>
				  
                  <div class="col-sm-4"><span style="color:#009900"><b>To</b></span><br>
                  <input name="dto" type="date" class="form-control" placeholder="To Date: 2018-05-24" required>
				  <span style="color: red;">Date should be in this format: 2018-05-24</span>
                  </div>
				  
                  <div class="col-sm-4"><span style="color:#009900"><b>Project</b></span><br>
				  	<select name="c_cat"  class="form-control select2" required>

				  <?php
				  $b_id = $_SESSION['tid'];
				  $search = mysqli_query($link, "SELECT * FROM causes WHERE b_id = '$b_id' AND campaign_status = 'Approved'");
				  while($get_search = mysqli_fetch_array($search))
				  {
				  ?>
				  		<option value="<?php echo $get_search['id']; ?>"><?php echo $get_search['campaign_title']; ?></option>
				  <?php } ?>
				  	</select>
					<span style="color: red;">Select the Project Title / Project Tagline</span>
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
	echo "<script>window.location='progress_reports.php?tid=".$_SESSION['tid']."&&dfrom=".$dfrom."&&dto=".$dto."&&acn=".$_SESSION['acctno']."&&c_cat=".$c_cat."&&mid=NDA1'; </script>";
}
?>

<?php
if(isset($_GET['dfrom']) == true)
{
?>

<hr>
<div align="right" style="color: red;"> <h4><b>From <?php echo $_GET['dfrom']; ?> - <?php echo $_GET['dto']; ?></b> (change dates above)</h4> </div>
<br>
<table class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th><div align="center">Your Project </div></th>
                  <th><div align="center">Campaign Progress</div></th>
                 </tr>
                </thead>
                <tbody>
<?php
$dfrom = $_GET['dfrom'];
$dto = $_GET['dto'];
$c_cat = $_GET['c_cat'];
$tid = $_SESSION['tid'];

	$select = mysqli_query($link, "SELECT * FROM campaign_pay_history WHERE c_id = '$c_cat' AND pdate BETWEEN '$dfrom' AND '$dto' ORDER BY id") or die (mysqli_error($link));
	while($row = mysqli_fetch_array($select))
	{
	$id = $row['id'];
	$cid= $row['c_id'];
	
	$search_project = mysqli_query($link, "SELECT * FROM causes WHERE b_id = '$tid'") or die ("Error: " . mysqli_error($link));
	$get_project = mysqli_fetch_array($search_project);
	$budget = $get_project['budget'];
	$current_fund = $get_project['current_fund'];
	$cal_percentage = 100 / ($budget/$current_fund);
	if(mysqli_num_rows($search_project) == 0){
		echo "<div class='alert alert-info'>No data found!.....Check back later!!</div>";
	}else{
		
?>

				<tr>
				<td><?php echo $get_project['campaign_title']; ?></td>
				<td><?php echo ($current_fund == $budget) ? "<span class='label label-success'>".$cal_percentage."%</span>" : "<span class='label label-danger'>".$cal_percentage."%</span>" ; ?>
					<p></p>
					<p>
					<?php echo $get_project['current_fund'].'&nbsp;'.'of'.'&nbsp;'.$get_project['budget']; ?>
					</p>
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