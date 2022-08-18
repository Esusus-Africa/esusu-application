<div class="box">
	       <div class="box-body">
			<div class="panel panel-success">
            <div class="panel-heading">
            <h3 class="panel-title"><i class="fa fa-user"></i> Treat Member (Case)</h3>
            </div>
             <div class="box-body">		

<form class="form-horizontal" method="post" enctype="multipart/form-data">

             <div class="box-body">

             	<div class="form-group">
 <label for="" class="col-sm-2 control-label" style="color:#009900">Case to Treat</label>
 <div class="col-sm-10">
 	<select name="solution" class="form-control select2" required>
 	<option selected='selected'>Select Case to Treat&hellip;</option>
 	<?php
 	$id = $_GET['id'];
 	$respid = "RESP".rand(1000000000,9999999999);
 	$select_case = mysqli_query($link, "SELECT * FROM member_case WHERE status = 'Defaulted' AND mid = '$id'") or die ("Error: " . mysqli_error($link));
 	while($fetch_case = mysqli_fetch_object($select_case))
 	{
 	?>
 	<option value="<?php echo $fetch_case->caseid; ?>"><?php echo $fetch_case->subject_line.'&nbsp;'.'-CASE ID: '.$fetch_case->caseid.'&nbsp;'.'-RESPONSE ID: '.$respid; ?></option>
 	<?php
 	}
 	?>
 	</select>                 
 </div>
</div>
				<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:#009900">Case Resolution</label>
                  <div class="col-sm-10">
                  <textarea name="m_case" id="editor1" class="form-control" rows="4" cols="80"></textarea>
                  <b style="color:red; font-size:15px;">Kindly state the necessary feedback gotten from the Customer and send back to the Admin for further Review.</b>
                  </div>
          		</div>

          <div class="form-group">
 <label for="" class="col-sm-2 control-label" style="color:#009900">Status</label>
 <div class="col-sm-10">
 	<select name="status"  class="form-control select2" required>
 	<option selected='selected'>Select the status of the Case&hellip;</option>
<option value='Pending'>Under Review</option>
<option value='Resolved'>Resolved</option>
 	</select>                 
 </div>
</div>

</div>
 
 <div align="right">
              <div class="box-footer">
                <button type="reset" class="btn btn-primary btn-flat"><i class="fa fa-times">&nbsp;Reset</i></button>
                <button name="save" type="submit" class="btn btn-success btn-flat"><i class="fa fa-save">&nbsp;Save</i></button>

              </div>
 </div>
<?php
if(isset($_POST['save']))
{
$id = $_GET['id'];
$respid = "RESP".rand(1000000000,9999999999);
$solution =  mysqli_real_escape_string($link, $_POST['solution']);
$m_case =  mysqli_real_escape_string($link, $_POST['m_case']);
$status =  mysqli_real_escape_string($link, $_POST['status']);
$insert = mysqli_query($link, "INSERT INTO treat_case VALUES(null,'$solution','$respid','$id','$m_case','$status',NOW())") or die ("Error: " . mysqli_error($link));
if(!$insert)
{
echo "<div class='alert alert-info'>Error.....Please try again later</div>";
}
else{
echo "<script>alert('Done!');</script>";
echo "<script>window.location='treat_case.php?id=".$id."&&mid=".base64_encode("403")."';</script>";
}
}
?>	 
</form> 

<hr>
<?php
$id = $_GET['id'];
$select2 = mysqli_query($link, "SELECT * FROM treat_case WHERE mid = '$id' ORDER BY id DESC") or die ("Error: " . mysqli_error($link));
$find_row = mysqli_num_rows($select2);
if(mysqli_num_rows($select2) == 0){
	echo "<div style='color: red; font-size:15px;'><b>No Case Response!</b></div>";
}else{
while($row2 =  mysqli_fetch_array($select2))
{
	$search_cs = mysqli_query($link, "SELECT * FROM member_case WHERE mid = '$id' AND status = 'Defaulted'") or die ("Error: " . mysqli_error($link));
	$getting_cs = mysqli_fetch_object($search_cs);

	$search_user = mysqli_query($link, "SELECT * FROM borrowers WHERE id = '$id'") or die ("Error: " . mysqli_error($link));
	$getting_user = mysqli_fetch_object($search_user);
?>		  
				<div class="box-body">
					<p>
						<h4><b style="color: green;"><?php echo $getting_user->fname.'&nbsp;'.$getting_user->lname; ?></b> <a href="reply_case.php?id=<?php echo $id; ?>&&respid=<?php echo $row2['response_id']; ?>&&caseid=<?php echo $row2['caseid']; ?>&&mid=<?php echo base64_encode("403"); ?>"><b style="color: red;">(<?php echo $getting_cs->subject_line; ?>)</b><br style="color: blue;"><?php echo "CASE ID: ".$row2['caseid'].'&nbsp;'.'| RESPONSE ID: '.$row2['response_id']; ?></a> - <i><?php echo $row2['date_time']; ?></i></h4>
					</p>
					<p>
						<?php echo $row2['case_resolution']; ?>
					</p>
				</div>
				<hr>
<?php } } ?>


</div>	
</div>	
</div>
</div>