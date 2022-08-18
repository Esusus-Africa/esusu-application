<div class="row">	
		
	 <section class="content">
	        <div class="box box-success">
            <div class="box-body">
              <div class="table-responsive"> 
             <div class="box-body">

<form method="post">
<button type="submit" class="btn btn-flat bg-orange" name="delete"><i class="fa fa-times"></i>&nbsp;Delete</button>
<hr>
			 <div class="col-md-14">
             <div class="nav-tabs-custom">
             <ul class="nav nav-tabs">
<?php
$search_count = mysqli_query($link, "SELECT * FROM campaign");
$count1 = mysqli_num_rows($search_count);
?>
              <li <?php echo ($_GET['tab'] == 'tab_1') ? "class='active'" : ''; ?>><a href="campaign_list.php??id=<?php echo $_SESSION['tid']; ?>&&mid=<?php echo base64_encode("750"); ?>&&tab=tab_1">All Campaign ( <?php echo $count1; ?> )</a></li>
<?php
$search_count2 = mysqli_query($link, "SELECT * FROM campaign WHERE campaign_status = 'Pending'");
$count2 = mysqli_num_rows($search_count2);
?>
			  <li <?php echo ($_GET['tab'] == 'tab_2' && $pending_campaign == "1") ? "class='active'" : ''; ?>><a href="campaign_list.php?id=<?php echo $_GET['id']; ?>&&mid=<?php echo base64_encode("750"); ?>&&status=Pending&&tab=tab_2">Pending ( <?php echo $count2; ?> )</a></li>
<?php
$search_count3 = mysqli_query($link, "SELECT * FROM campaign WHERE campaign_status = 'Disapproved'");
$count3 = mysqli_num_rows($search_count3);
?>
			  <li <?php echo ($_GET['tab'] == 'tab_3' && $campaign_disapproved == "1") ? "class='active'" : ''; ?>><a href="campaign_list.php?id=<?php echo $_GET['id']; ?>&&mid=<?php echo base64_encode("750"); ?>&&status=Disapprove&&tab=tab_3">Disapproved ( <?php echo $count3; ?> )</a></li>
<?php
$search_count4 = mysqli_query($link, "SELECT * FROM campaign WHERE campaign_status = 'Approved'");
$count4 = mysqli_num_rows($search_count4);
?>
			  <li <?php echo ($_GET['tab'] == 'tab_4' && $campaign_approved == "1") ? "class='active'" : ''; ?>><a href="campaign_list.php?id=<?php echo $_GET['id']; ?>&&mid=<?php echo base64_encode("750"); ?>&&status=Approved&&tab=tab_4">Published ( <?php echo $count4; ?> )</a></li>
<?php
$search_count5 = mysqli_query($link, "SELECT * FROM campaign WHERE campaign_status = 'Pre-Approve'");
$count5 = mysqli_num_rows($search_count5);
?>
			  <li <?php echo ($_GET['tab'] == 'tab_5') ? "class='active'" : ''; ?>><a href="campaign_list.php?id=<?php echo $_GET['id']; ?>&&mid=<?php echo base64_encode("750"); ?>&&status=Trash&&tab=tab_5">Pre-Approve  ( <?php echo $count5; ?> )</a></li>
              </ul>
             <div class="tab-content">
<?php
if(isset($_GET['tab']) == true)
{
	$tab = $_GET['tab'];
	if($tab == 'tab_1')
	{
	?>
             <div class="tab-pane <?php echo ($_GET['tab'] == 'tab_1') ? 'active' : ''; ?>" id="tab_1">

				<table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th><input type="checkbox" id="select_all"/></th>
				  <th>Project Title</th>
                  <th>Type</th>
                  <th>Amount Lend</th>
                  <th>Days Remaining</th>
                  <th>Action</th>
                 </tr>
                </thead>
                <tbody> 
<?php
$select = mysqli_query($link, "SELECT * FROM campaign") or die (mysqli_error($link));
if(mysqli_num_rows($select)==0)
{
echo "<div class='alert bg-orange'>No data found yet!.....Check back later!!</div>";
}
else{
while($rows = mysqli_fetch_array($select))
{
$id = $rows['id'];
$now = time(); // or your date as well
$dto = strtotime($rows['dto']);
$datediff = $dto - $now;
$total_day = round($datediff / (60 * 60 * 24));
$call = mysqli_query($link, "SELECT * FROM systemset");
$row = mysqli_fetch_array($call);
?>   
                <tr>
				<td><input id="optionsCheckbox" class="checkbox" name="selector[]" type="checkbox" value="<?php echo $rows['id']; ?>"></td>
				<td><?php echo $rows['campaign_title'].'&nbsp;<br><b>('.$rows['campaign_status'].')</b>'; ?></td>
				<td><?php echo $rows['campaign_type']; ?></td>
                <td><?php echo $row['currency'].$rows['current_fund'].'&nbsp;'.'of'.'&nbsp;'.$row['currency'].$rows['budget']; ?></td>
				<td><?php echo ($total_day < 0) ? '0' : $total_day; ?></td>
				<td><a href="#myModal <?php echo $id; ?>"> <button type="button" class="btn bg-blue" data-target="#myModal<?php echo $id; ?>" data-toggle="modal"><i class="fa fa-eye"></i></button></a>
					<a href="updatecampaign.php?id=<?php echo $_SESSION['tid']; ?>&&idm=<?php echo $id; ?>&&mid=<?php echo base64_encode("750"); ?>"><button type="button" class="btn bg-orange"><i class="fa fa-edit"></i></button></a>
				</td>
			    </tr>
<?php } } ?>
             </tbody>
                </table>  
              </div>
              <!-- /.tab-pane -->
	<?php
	}
	elseif($tab == 'tab_2')
	{
	?>
              <div class="tab-pane <?php echo ($_GET['tab'] == 'tab_2') ? 'active' : ''; ?>" id="tab_2">
			  <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th><input type="checkbox" id="select_all"/></th>
				  <th>Project Title</th>
                  <th>Type</th>
                  <th>Amount Lend</th>
                  <th>Days Remaining</th>
                  <th>Action</th>
                 </tr>
                </thead>
                <tbody> 
<?php
$select = mysqli_query($link, "SELECT * FROM campaign WHERE campaign_status='Pending'") or die (mysqli_error($link));
if(mysqli_num_rows($select)==0)
{
echo "<div class='alert bg-orange'>No data found yet!.....Check back later!!</div>";
}
else{
while($rows = mysqli_fetch_array($select))
{
$id = $rows['id'];
$now = time(); // or your date as well
$dto = strtotime($rows['dto']);
$datediff = $dto - $now;
$total_day = round($datediff / (60 * 60 * 24));
$call = mysqli_query($link, "SELECT * FROM systemset");
$row = mysqli_fetch_array($call);
?>   
                <tr>
				<td><input id="optionsCheckbox" class="checkbox" name="selector[]" type="checkbox" value="<?php echo $rows['id']; ?>"></td>
				<td><?php echo $rows['campaign_title'].'&nbsp;<br><b>('.$rows['campaign_status'].')</b>'; ?></td>
				<td><?php echo $rows['campaign_type']; ?></td>
                <td><?php echo $row['currency'].$rows['current_fund'].'&nbsp;'.'of'.'&nbsp;'.$row['currency'].$rows['budget']; ?></td>
				<td><?php echo ($total_day < 0) ? '0' : $total_day; ?></td>
				<td><a href="#myModal <?php echo $id; ?>"> <button type="button" class="btn bg-blue" data-target="#myModal<?php echo $id; ?>" data-toggle="modal"><i class="fa fa-edit"></i></button></a>
					<a href="updatecampaign.php?id=<?php echo $_SESSION['tid']; ?>&&idm=<?php echo $id; ?>&&mid=<?php echo base64_encode("750"); ?>"><button type="button" class="btn bg-orange"><i class="fa fa-eye"></i></button></a>
				</td>
			    </tr>
<?php } } ?>
             </tbody>
                </table>
              </div>
	<?php
	}
	elseif($tab == 'tab_3')
	{
	?>
              <!-- /.tab-pane -->
              <div class="tab-pane <?php echo ($_GET['tab'] == 'tab_3') ? 'active' : ''; ?>" id="tab_3">
                 <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th><input type="checkbox" id="select_all"/></th>
				  <th>Project Title</th>
                  <th>Type</th>
                  <th>Amount Lend</th>
                  <th>Days Remaining</th>
                  <th>Action</th>
                 </tr>
                </thead>
                <tbody> 
<?php
$select = mysqli_query($link, "SELECT * FROM campaign WHERE campaign_status='Disapproved'") or die (mysqli_error($link));
if(mysqli_num_rows($select)==0)
{
echo "<div class='alert bg-orange'>No data found yet!.....Check back later!!</div>";
}
else{
while($rows = mysqli_fetch_array($select))
{
$id = $rows['id'];
$now = time(); // or your date as well
$dto = strtotime($rows['dto']);
$datediff = $dto - $now;
$total_day = round($datediff / (60 * 60 * 24));
$call = mysqli_query($link, "SELECT * FROM systemset");
$row = mysqli_fetch_array($call);
?>   
                <tr>
				<td><input id="optionsCheckbox" class="checkbox" name="selector[]" type="checkbox" value="<?php echo $rows['id']; ?>"></td>
				<td><?php echo $rows['campaign_title'].'&nbsp;<br><b>('.$rows['campaign_status'].')</b>'; ?></td>
				<td><?php echo $rows['campaign_type']; ?></td>
                <td><?php echo $row['currency'].$rows['current_fund'].'&nbsp;'.'of'.'&nbsp;'.$row['currency'].$rows['budget']; ?></td>
				<td><?php echo ($total_day < 0) ? '0' : $total_day; ?></td>
				<td><a href="#myModal <?php echo $id; ?>"> <button type="button" class="btn bg-blue" data-target="#myModal<?php echo $id; ?>" data-toggle="modal"><i class="fa fa-edit"></i></button></a>
					<a href="updatecampaign.php?id=<?php echo $_SESSION['tid']; ?>&&idm=<?php echo $id; ?>&&mid=<?php echo base64_encode("750"); ?>"><button type="button" class="btn bg-orange"><i class="fa fa-eye"></i></button></a>
				</td>
			    </tr>
<?php } } ?>
             </tbody>
                </table>
              </div>
			  
	<?php
	}
	elseif($tab == 'tab_4')
	{
	?>			
			<div class="tab-pane <?php echo ($_GET['tab'] == 'tab_4') ? 'active' : ''; ?>" id="tab_4">
 <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th><input type="checkbox" id="select_all"/></th>
				  <th>Project Title</th>
                  <th>Type</th>
                  <th>Amount Lend</th>
                  <th>Days Remaining</th>
                  <th>Action</th>
                 </tr>
                </thead>
                <tbody> 
<?php
$select = mysqli_query($link, "SELECT * FROM campaign WHERE campaign_status='Approved'") or die (mysqli_error($link));
if(mysqli_num_rows($select)==0)
{
echo "<div class='alert bg-orange'>No data found yet!.....Check back later!!</div>";
}
else{
while($rows = mysqli_fetch_array($select))
{
$id = $rows['id'];
$now = time(); // or your date as well
$dto = strtotime($rows['dto']);
$datediff = $dto - $now;
$total_day = round($datediff / (60 * 60 * 24));
$call = mysqli_query($link, "SELECT * FROM systemset");
$row = mysqli_fetch_array($call);
?>   
                <tr>
				<td><input id="optionsCheckbox" class="checkbox" name="selector[]" type="checkbox" value="<?php echo $rows['id']; ?>"></td>
				<td><?php echo $rows['campaign_title'].'&nbsp;<br><b>('.$rows['campaign_status'].')</b>'; ?></td>
				<td><?php echo $rows['campaign_type']; ?></td>
                <td><?php echo $row['currency'].$rows['current_fund'].'&nbsp;'.'of'.'&nbsp;'.$row['currency'].$rows['budget']; ?></td>
				<td><?php echo ($total_day < 0) ? '0' : $total_day; ?></td>
				<td><a href="#myModal <?php echo $id; ?>"> <button type="button" class="btn bg-blue" data-target="#myModal<?php echo $id; ?>" data-toggle="modal"><i class="fa fa-edit"></i></button></a>
					<a href="updatecampaign.php?id=<?php echo $_SESSION['tid']; ?>&&idm=<?php echo $id; ?>&&mid=<?php echo base64_encode("750"); ?>"><button type="button" class="btn bg-orange"><i class="fa fa-eye"></i></button></a>
				</td>
			    </tr>
<?php } } ?>
             </tbody>
                </table>
              </div>  

	<?php
	}
	elseif($tab == 'tab_5')
	{
	?>
			  
			  <div class="tab-pane <?php echo ($_GET['tab'] == 'tab_5') ? 'active' : ''; ?>" id="tab_5">
			 <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th><input type="checkbox" id="select_all"/></th>
				  <th>Project Title</th>
                  <th>Type</th>
                  <th>Amount Lend</th>
                  <th>Days Remaining</th>
                  <th>Action</th>
                 </tr>
                </thead>
                <tbody> 
<?php
$select = mysqli_query($link, "SELECT * FROM campaign WHERE campaign_status='Pre-Approve'") or die (mysqli_error($link));
if(mysqli_num_rows($select)==0)
{
echo "<div class='alert bg-orange'>No data found yet!.....Check back later!!</div>";
}
else{
while($rows = mysqli_fetch_array($select))
{
$id = $rows['id'];
$now = time(); // or your date as well
$dto = strtotime($rows['dto']);
$datediff = $dto - $now;
$total_day = round($datediff / (60 * 60 * 24));
$call = mysqli_query($link, "SELECT * FROM systemset");
$row = mysqli_fetch_array($call);
?>   
                <tr>
				<td><input id="optionsCheckbox" class="checkbox" name="selector[]" type="checkbox" value="<?php echo $rows['id']; ?>"></td>
				<td><?php echo $rows['campaign_title'].'&nbsp;<br><b>('.$rows['campaign_status'].')</b>'; ?></td>
				<td><?php echo $rows['campaign_type']; ?></td>
                <td><?php echo $row['currency'].$rows['current_fund'].'&nbsp;'.'of'.'&nbsp;'.$row['currency'].$rows['budget']; ?></td>
				<td><?php echo ($total_day < 0) ? '0' : $total_day; ?></td>
				<td><a href="#myModal <?php echo $id; ?>"> <button type="button" class="btn bg-blue" data-target="#myModal<?php echo $id; ?>" data-toggle="modal"><i class="fa fa-edit"></i></button></a>
					<a href="updatecampaign.php?id=<?php echo $_SESSION['tid']; ?>&&idm=<?php echo $id; ?>&&mid=<?php echo base64_encode("750"); ?>"><button type="button" class="btn bg-orange"><i class="fa fa-eye"></i></button></a>
				</td>
			    </tr>
<?php } } ?>
             </tbody>
                </table>
			  </div>
	<?php
	}
}
?>
              <!-- /.tab-pane -->
            </div>
            <!-- /.tab-content -->
          </div>
				 
					
					
				
				
				</div>
			<?php
				if(isset($_POST['delete'])){
				$idm = $_GET['id'];
				$id=$_POST['selector'];
				$N = count($id);
				if($id == ''){
					echo "<script>alert('Row Not Selected!!!'); </script>";	
					echo "<script>window.location='campaign_list.php?id=".$_SESSION['tid']."&&mid=".base64_encode('750')."&&tab=".$_GET['tab']."'; </script>";
				}
				else{
					for($i=0; $i < $N; $i++)
					{
						$result = mysqli_query($link,"DELETE FROM campaign WHERE id ='$id[$i]'");
						echo "<script>alert('Row Delete Successfully!!!'); </script>";
						echo "<script>window.location='campaign_list.php?id=".$_SESSION['tid']."&&mid=".base64_encode('750')."&&tab=".$_GET['tab']."'; </script>";
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