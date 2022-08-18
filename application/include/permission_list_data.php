<div class="row">
    
		    <section class="content">  
	        <div class="box box-success">
            <div class="box-body">
              <div class="table-responsive">
             <div class="box-body">
<form method="post">

			 <a href="dashboard.php?id=<?php echo $_SESSION['tid']; ?>&&mid=<?php echo base64_encode("401"); ?>"><button type="button" class="btn btn-flat bg-orange"><i class="fa fa-mail-reply-all"></i>&nbsp;Back</button></a> 
	
			 <?php echo ($delete_client_permission == '1') ? '<button type="submit" class="btn btn-flat bg-orange" name="delete"><i class="fa fa-times"></i>&nbsp;Delete</button>' : ''; ?>
	
			 <hr>

			 <div class="box-body">

            <div class="box-body">

             <div class="form-group">
                  <label for="" class="col-sm-1 control-label" style="color:blue;">Filter By</label>
                  <div class="col-sm-4">
                  <select name="ttype" id="transType" class="form-control select2" style="width:100%" required>
                    <option value="" selected="selected">Filter By...</option>
                    <option value="all">All Role</option>
                    
                    <option disabled>Filter By Client</option>
                    <?php
                    $get = mysqli_query($link, "SELECT * FROM institution_data WHERE status = 'Approved' ORDER BY id DESC") or die (mysqli_error($link));
                    while($rows = mysqli_fetch_array($get))
                    {
                    ?>
                    <option value="<?php echo $rows['institution_id']; ?>"><?php echo $rows['institution_name']; ?> - [<?php echo $rows['institution_id']; ?>]</option>
                    <?php } ?>
                  </select>
                  </div>
             </div>
             
            </div>
        
          <hr>
          <div class="table-responsive">
			<table id="client_permission_data" class="table table-bordered table-striped">
                <thead>
                <tr>
				          <th><input type="checkbox" id="select_all"/></th>
				          <th>Actions</th>
                  <th>Client Name</th>
				          <th>Role Name</th>
                </tr>
                </thead>
            </table>
          </div>
        
    </div>

			
<?php
						if(isset($_POST['delete'])){
						$idm = $_GET['id'];
							$id=$_POST['selector'];
							$N = count($id);
						if($id == ''){
						echo "<script>alert('Row Not Selected!!!'); </script>";	
						echo "<script>window.location='permission_list.php?id=".$_SESSION['tid']."&&mid=".base64_encode("419")."'; </script>";
							}
							else{
							for($i=0; $i < $N; $i++)
							{
								$result = mysqli_query($link,"DELETE FROM my_permission WHERE id ='$id[$i]'");
								echo "<script>alert('Role Permission Deleted Successfully!!!'); </script>";
								echo "<script>window.location='permission_list.php?id=".$_SESSION['tid']."&&mid=".base64_encode("419")."'; </script>";
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