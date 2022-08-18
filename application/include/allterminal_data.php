<div class="row">
		
	       
		    <section class="content">  
	        <div class="box box-success">
            <div class="box-body">
              <div class="table-responsive">
             <div class="box-body">
<form method="post">
	
  <a href="dashboard.php?id=<?php echo $_SESSION['tid']; ?>&&mid=<?php echo base64_encode("401"); ?>"><button type="button" class="btn bg-orange"><i class="fa fa-mail-reply-all"></i>&nbsp;Back</button> </a> 
	
  <?php echo ($backend_delete_terminal == '1') ? '<button type="submit" class="btn btn-flat bg-blue" name="delete"><i class="fa fa-times"></i>&nbsp;Delete Terminal</button>' : ''; ?>
	
	<hr>

  <div class="box-body">

             <div class="box-body">

              <div class="form-group">
                <label for="" class="col-sm-1 control-label" style="color:blue;">From</label>
                <div class="col-sm-3">
                  <input name="dfrom" type="date" id="startDate" class="form-control" placeholder="To Date: 2018-05-01" required>
                  <span style="color: blue;">Date format: 2018-05-01</span>
                </div>
              
                <label for="" class="col-sm-1 control-label" style="color:blue;">End Date</label>
                <div class="col-sm-3">
                  <input name="dto" type="date" id="endDate" class="form-control" placeholder="To Date: 2018-05-24" required>
                  <span style="color: blue;">Date format: 2018-05-24</span>
                </div>

                <label for="" class="col-sm-1 control-label" style="color:blue;">Filter By</label>
                <div class="col-sm-3">
                  <select name="ttype" id="transType" class="form-control select2" style="width:100%" required>
                    <option value="" selected="selected">Filter By...</option>
                    <option value="all">All Terminal</option>
                    <option value="Available">Available</option>
                    <option value="Booked">Booked</option>
                    <option value="Assigned">Assigned</option>

                    <option disabled>Filter By Channel</option>
                    <option value="POS">POS</option>
                    <option value="USSD">USSD</option>

                    <option disabled>Filter By Client</option>
                    <?php
                    $get = mysqli_query($link, "SELECT * FROM institution_data ORDER BY id DESC") or die (mysqli_error($link));
                    while($rows = mysqli_fetch_array($get))
                    {
                    ?>
                    <option value="<?php echo $rows['institution_id']; ?>"><?php echo $rows['institution_id'].' - '.$rows['institution_name']; ?></option>
                    <?php } ?>

                    <option disabled>Filter By Client Staff/Agent</option>
                    <?php
                    $get6 = mysqli_query($link, "SELECT * FROM user WHERE virtual_acctno != '' ORDER BY id") or die (mysqli_error($link));
                    while($rows6 = mysqli_fetch_array($get6))
                    {
                    ?>
                    <option value="<?php echo $rows6['id']; ?>"><?php echo $rows6['virtual_acctno'].' '.$rows6['name'].' '.$rows6['lname']; ?></option>
                    <?php } ?>

                    <option disabled>Filter By Client Branch</option>
                    <?php
                    $get = mysqli_query($link, "SELECT * FROM branches ORDER BY id DESC") or die (mysqli_error($link));
                    while($rows = mysqli_fetch_array($get))
                    {
                    ?>
                    <option value="<?php echo $rows['branchid']; ?>"><?php echo $rows['branchid'].' - '.$rows['bname']; ?></option>
                    <?php } ?>
                  </select>
                </div>
              </div>

              </div>

              <hr>
              <div class="table-responsive">
              <table id="allterminal_data" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th><input type="checkbox" id="select_all"/></th>
                  <th>Actions</th>
                  <th>Client Name</th>
                  <th>Branch Name</th>
                  <th>TID/TraceID</th>
                  <th>Channel</th>
                  <th>Requested By</th>
                  <th>Operator</th>
                  <th>Model Code</th>
                  <th>Issued By</th>
                  <th>MID</th>
                  <th>Created By</th>
                  <th>Status</th>
                  <th>Date Created</th>
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
						echo "<script>window.location='allterminal.php?id=".$_SESSION['tid']."&&mid=NzAw'; </script>";
							}
							else{
							for($i=0; $i < $N; $i++)
							{
								$result = mysqli_query($link,"DELETE FROM terminal_reg WHERE id ='$id[$i]'");
                                
                                echo "<script>alert('Terminal Deleted Successfully!!!'); </script>";
    							echo "<script>window.location='allterminal.php?id=".$_SESSION['tid']."&&mid=NzAw'; </script>";
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