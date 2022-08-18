<div class="row">
	       
		    <section class="content">  
	        <div class="box box-success">
            <div class="box-body">
              <div class="table-responsive">
             <div class="box-body">
<form method="post">
			 <a href="dashboard?id=<?php echo $_SESSION['tid']; ?>&&mid=<?php echo base64_encode("401"); ?>"><button type="button" class="btn btn-flat bg-orange"><i class="fa fa-mail-reply-all"></i>&nbsp;Back</button> </a> 
    
             <?php echo ($delete_client_branches == '1') ? '<button type="submit" class="btn bg-orange" name="delete"><i class="fa fa-times"></i>&nbsp;Multiple Delete</button>' : ''; ?>
    
    <hr>

        <div class="box-body">

            <div class="box-body">

             <div class="form-group">
                  <label for="" class="col-sm-1 control-label" style="color:blue;">Filter By</label>
                  <div class="col-sm-4">
                  <select name="ttype" id="transType" class="form-control select2" style="width:100%" required>
                    <option value="" selected="selected">Filter...</option>
                    <option value="all">All Institution Branch</option>
                    
                    <option disabled>Filter By Institution</option>
                    <?php
                    $get = mysqli_query($link, "SELECT * FROM institution_data WHERE status = 'Approved'") or die (mysqli_error($link));
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
			<table id="client_branches_data" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th><input type="checkbox" id="select_all"/></th>
                  <th>Actions</th>
                  <th>Institution</th>
				          <th>Branch ID</th>
                  <th>Branch Name</th>
                  <th>Phone</th>
                  <th>Transaction Count</th>
                  <th>Currency</th>
                  <th>Created</th>
                  <th>Status</th>
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