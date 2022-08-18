<div class="box">
	       <div class="box-body">
			<div class="panel panel-success">
            <div class="panel-heading bg-blue">
            <h3 class="panel-title"> <a href="dashboard.php?id=<?php echo $_SESSION['tid']; ?>&&mid=NDAx"><button type="button" class="btn btn-flat bg-orange"><i class="fa fa-mail-reply-all"></i>&nbsp;Back</button> </a> <i class="fa fa-area-chart"></i>  Report</h3>
            </div>

             <div class="box-body">
			 
			<form class="form-horizontal" method="post" enctype="multipart/form-data">
			 <div class="alert bg-blue"> The Collectors report shows the <b>total capital, total interest, and total collection</b>. </div>
             <div class="box-body">
		  

			<input name="branchid" type="hidden" class="form-control" value="<?php echo $rows->branchid; ?>">
			
			<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">From</label>
                  <div class="col-sm-4">
                  <input name="dfrom" type="date" class="form-control" placeholder="To Date: 2018-05-01">
				  <span style="color: orange;">Date should be in this format: 2018-05-01</span>
                  </div>
				  
				  <label for="" class="col-sm-2 control-label" style="color:blue;">To</label>
                  <div class="col-sm-4">
                  <input name="dto" type="date" class="form-control" placeholder="To Date: 2018-05-24">
				  <span style="color: orange;">Date should be in this format: 2018-05-24</span>
                  </div>
                  </div>

             <div class="form-group">
				  <label for="" class="col-sm-2 control-label" style="color:blue;">Staff</label>
                  <div class="col-sm-4">
                  <select name="staffid"  class="form-control select2" style="width:100%">
					<option selected="selected">Filter By Staff...</option>
					<?php
					$get = mysqli_query($link, "SELECT * FROM agent_data WHERE agentid = 'session_id' ORDER BY id") or die (mysqli_error($link));
					while($rows = mysqli_fetch_array($get))
					{
					?>
					<option value="<?php echo $rows['id']; ?>"><?php echo $rows['fname']; ?></option>
					<?php } ?>				
				  </select>
                  </div>
                  </div>
			 </div>
			 
			  <div align="right">
              <div class="box-footer">
                				<button type="reset" class="btn bg-orange btn-flat"><i class="fa fa-times">&nbsp;Reset</i></button>
                				<button name="search" type="submit" class="btn bg-blue btn-flat"><i class="fa fa-filter">&nbsp;Search</i></button>

              </div>
			  </div>
			</form> 
			
<?php
if(isset($_POST['search'])){
	$dfrom = mysqli_real_escape_string($link, $_POST['dfrom']);
	$dto = mysqli_real_escape_string($link, $_POST['dto']);
	$bid = mysqli_real_escape_string($link, $_POST['branchid']);
	$sid = mysqli_real_escape_string($link, $_POST['staffid']);
	$instid = mysqli_real_escape_string($link, $_POST['instid']);
	echo "<script>window.location='collector_reports.php?id=".$_SESSION['tid']."&&dfrom=".$dfrom."&&dto=".$dto."&&staffid=".$sid."&&mid=NDI1'; </script>";
}
?>

<?php
if(isset($_GET['dfrom']))
{
?>
<?php
$staffid = $_GET['staffid'];
$get = mysqli_query($link, "SELECT * FROM agent_data WHERE id = '$staffid'") or die (mysqli_error($link));
$rows = mysqli_fetch_object($get);
$staffname = $rows->fname;

$get2 = mysqli_query($link, "SELECT * FROM systemset") or die (mysqli_error($link));
$rows2 = mysqli_fetch_object($get2);
?>	
<hr>
<div id='printarea'>
<div align="left" style="color: orange;"> <h4><b>From <?php echo $_GET['dfrom']; ?> - <?php echo $_GET['dto']; ?></b> (change dates above)</h4> </div>
<br>
<table class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>Collector (Staff)</th>
                  <th><div align="center">Total Capital Released</div></th>
				  <th><div align="center">Borrower List</div></th>
				  <th><div align="center">Total Interest</div></th>
                  <th><div align="center">Total Collection</div></th>
                 </tr>
                </thead>
                <tbody>
<?php
$dfrom = $_GET['dfrom'];
$dto = $_GET['dto'];
$branchid = $_GET['branchid'];
$instid = $_GET['instid'];
$select = mysqli_query($link, "SELECT borrower, sum(amount), agent, sum(interest_rate) FROM loan_info WHERE date_release BETWEEN '$dfrom' AND '$dto'AND teller = '$staffname' ORDER BY id") or die (mysqli_error($link));
while($row = mysqli_fetch_array($select))
{
$id = $row['id'];
$agent = $row['agent'];
$borrower = $row['borrower'];

$search_user = mysqli_query($link, "SELECT * FROM agent_data WHERE fname = '$agent'") or die (mysqli_error($link));
while($get_u = mysqli_fetch_array($search_user))
{
	$id = $get_u['id'];

	$select1 = mysqli_query($link, "SELECT * FROM systemset") or die (mysqli_error($link));
	$row1 = mysqli_fetch_array($select1);
	$currency = $row1['currency']; 
?>

				<tr>
				<td><b><?php echo $agent; ?></b></td>
				<td>
				<?php 
				$selectb = mysqli_query($link, "SELECT amount FROM loan_info WHERE agent = '$agent' AND status = 'Approved'") or die (mysqli_error($link));
				while($rowb = mysqli_fetch_array($selectb))
				{
				echo "<div align='center' style='color: orange;'><p>".$currency.number_format($rowb['amount'],2,".",",")."</p></div>"; 
				}
				?>
				</td>
				<td>
				<?php 
				$selectbee = mysqli_query($link, "SELECT * FROM loan_info WHERE agent = '$agent'  AND status = 'Approved'") or die ("Error; " . mysqli_error($link));
				while($rowbee = mysqli_fetch_array($selectbee))
				{
					$borrowers = $rowbee['borrower'];
					
					$search_borrower = mysqli_query($link, "SELECT * FROM borrowers WHERE id = '$borrowers'") or die ("Error; " . mysqli_error($link));
					$get_borrower = mysqli_fetch_array($search_borrower);
					$name = $get_borrower['fname'].'&nbsp;'.$get_borrower['lname'];
					$acctno = $get_borrower['account'];
					echo "<div align='center' style='color: orange;'><p>".$name.'&nbsp;('.$acctno.')'."</p></div>"; 
				}
				?>
				</td>
				<td>
				<?php 
				$selectbe = mysqli_query($link, "SELECT amount, interest_rate FROM loan_info WHERE agent = '$agent' AND status = 'Approved'") or die (mysqli_error($link));
				while($rowbe = mysqli_fetch_array($selectbe))
				{
				$get_rate = $rowbe['interest_rate']/100;
				$get_amt = $get_rate * $rowbe['amount'];
				echo "<div align='center' style='color: orange;'><p>".$currency.number_format($get_amt,2,".",",")."</p></div>"; 
				}
				?>
				</td>
				<td>
				<?php
				$selec = mysqli_query($link, "SELECT * FROM loan_info WHERE agent = '$agent' AND status = 'Approved'") or die ("Error; " . mysqli_error($link));
				while($rowbc = mysqli_fetch_array($selec))
				{
					$b = $rowbc['borrower'];
					$search_borrower = mysqli_query($link, "SELECT * FROM borrowers WHERE id = '$b'") or die ("Error; " . mysqli_error($link));
					while($get_borrower = mysqli_fetch_array($search_borrower))
					{
						$b_acctno = $get_borrower['account'];
						$search_box = mysqli_query($link, "SELECT sum(payment) FROM pay_schedule WHERE tid = '$b_acctno' AND status = 'PAID'") or die ("Error; " . mysqli_error($link));
						while($row_box = mysqli_fetch_array($search_box))
						{
							echo "<div align='center' style='color: blue;'><p><b>".$currency.number_format($row_box['sum(payment)'],2,".",",")."</b></p></div>"; 
						}
					}
				}
				?>
				</td>
				</tr>
<?php } } ?>
				</tbody>
                </table> 
</div>
                <input type='button' id='btnprint' class='btn bg-blue' value='Print'>

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