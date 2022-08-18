<div class="box">
	       <div class="box-body">
			<div class="panel panel-success">
            <div class="panel-heading">
            <h3 class="panel-title"><i class="fa fa-user"></i> Query Member (Case)</h3>
            </div>
             <div class="box-body">
                         
			 <form class="form-horizontal" method="post" enctype="multipart/form-data">
<hr>
<div style="color:red; font-size:15px;"><b>Kindly state reason(s) the member is been defaulted and how the case should be handled by all branches and head-office staff in case the member want to perform any transaction.</b></div>
<hr>
             <div class="box-body">

             	<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:#009900">Subject Line</label>
                  <div class="col-sm-10">
                  <input name="subject_line" type="text" class="form-control" placeholder="Enter Subject line for the case" required>
                  </div>
                  </div>
			
		 		<div class="form-group">
                  	<label for="" class="col-sm-2 control-label" style="color:#009900">Reason(s)</label>
                  	<div class="col-sm-10"><textarea name="m_case" id="editor1" class="form-control" rows="4" cols="80"></textarea></div>
          		</div>

          		<div class="form-group">
				  <label for="" class="col-sm-2 control-label" style="color:#009900">Status</label>
				  <div class="col-sm-10">
				  	<select name="status"  class="form-control select2" required>
				  		<option selected='selected'>Select the status of the Case&hellip;</option>
						<option value="Defaulted">Defaulted</option>
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
$subject_line =  mysqli_real_escape_string($link, $_POST['subject_line']);
$m_case =  mysqli_real_escape_string($link, $_POST['m_case']);
$status =  mysqli_real_escape_string($link, $_POST['status']);
$case_id = "CASE-".rand(1000000000,9999999999);
	
	$insert = mysqli_query($link, "INSERT INTO member_case VALUES(null,'$case_id','$id','$subject_line','$m_case','$status',NOW())") or die (mysqli_error($link));
	if(!$insert)
	{
		echo "<div class='alert alert-info'>Error.....Please try again later</div>";
	}
	else{
		echo "<script>alert('Case Created Successfully');</script>";
		echo "<script>window.location='query_member.php?id=".$id."&&mid=".base64_encode("403")."';</script>";
	}
}
?>			  
			 </form> 

<hr>
<?php
$id = $_GET['id'];
$select2 = mysqli_query($link, "SELECT * FROM member_case WHERE mid = '$id'") or die ("Error: " . mysqli_error($link));
$find_row = mysqli_num_rows($select2);
if(mysqli_num_rows($select2) == 0){
	echo "<div style='color: red; font-size:15px;'><b>No Case!</b></div>";
}else{
while($row2 =  mysqli_fetch_array($select2))
{
	$search_user = mysqli_query($link, "SELECT * FROM borrowers WHERE id = '$id'") or die ("Error: " . mysqli_error($link));
	$getting_user = mysqli_fetch_object($search_user);
?>		  
				<div class="box-body">
					<p>
						<h4><b style="color: green;"><?php echo $getting_user->fname.'&nbsp;'.$getting_user->lname; ?></b> <b style="color: red;">(<?php echo $row2['subject_line']; ?>)</b> - <i><?php echo $row2['date_time']; ?></i></h4>
					</p>
					<p>
						<?php echo $row2['details']; ?>
					</p>
				</div>
				<hr>
<?php } } ?>


</div>	
</div>	
</div>
</div>