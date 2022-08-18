<div class="box">
	      <div class="box-body">
			<div class="panel panel-success">
            <div class="panel-heading bg-blue">
            <h3 class="panel-title"><i class="fa fa-dollar"></i> New Payment</h3>
            </div>
             <div class="box-body">

			 <form class="form-horizontal" method="post" enctype="multipart/form-data" action="process_payment.php">
			  <?php echo '<div class="bg-orange fade in" >
			  <a href = "#" class = "close" data-dismiss= "alert"> &times;</a>
  				<strong>Note that&nbsp;</strong> &nbsp;&nbsp;Some Fields are Rquired.
				</div>'?>
             <div class="box-body">
				
			<div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Loan ID</label>
                  <div class="col-sm-9">
                  <input name="acte" type="text" class="form-control" placeholder="Account" required>
                  </div>
                  </div>
				  
				   <div class="form-group">
                <label for="" class="col-sm-3 control-label" style="color:blue;">Customer</label>
				 <div class="col-sm-9">
                <select class="customer select2" name="customer" style="width: 100%;">
				<option selected="selected">--Select Customer--</option>
                 <?php
				$get = mysqli_query($link, "SELECT * FROM borrowers WHERE branchid = '$session_id' order by id") or die (mysqli_error($link));
				while($rows = mysqli_fetch_array($get))
				{
				echo '<option value="'.$rows['id'].'">'.$rows['fname'].'&nbsp;'.$rows['lname'].'</option>';
				}
				?>
                </select>
              </div>
			  </div>
				  
		 <div class="form-group">
                <label for="" class="col-sm-3 control-label" style="color:blue;">Customer Account#</label>
				 <div class="col-sm-9">
                <select class="account select2" name="account" style="width: 100%;">
				<option selected="selected">--Select Customer Account--</option>
                  <?php
				$getin = mysqli_query($link, "SELECT * FROM borrowers WHERE branchid = '$session_id' order by id") or die (mysqli_error($link));
				while($row = mysqli_fetch_array($getin))
				{
				echo '<option value="'.$row['id'].'">'.$row['account'].'</option>';
				}
				?>
                </select>
              </div>
			  </div>
			  
			  <div class="form-group">
                <label for="" class="col-sm-3 control-label" style="color:blue;">Loan</label>
				 <div class="col-sm-9">
                <select class="loan select2" name="loan" style="width: 100%;">
				<option selected="selected">--Select Loan--</option>
                 <?php
				$get = mysqli_query($link, "SELECT * FROM pay_schedule WHERE status = 'UNPAID' AND branchid = '$session_id' order by id") or die (mysqli_error($link));
				while($rows = mysqli_fetch_array($get))
				{
				echo '<option value="'.$rows['id'].'">'."Amount to Pay(".$rows['payment']."-"."&nbsp;"."bal:".$rows['balance'].")".'</option>';
				}
				?>
                </select>
              </div>
			  </div>
			  
			  <div class="form-group">
                <label for="" class="col-sm-3 control-label" style="color:blue;">Payment Date</label>
			 <div class="col-sm-9">
              <div class="input-group date">
                  <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                  </div>
                  <input type="date" class="form-control pull-right" name="pay_date" /required>
                </div>
              </div>
			  </div>
				  
		<div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Amount to Pay</label>
                  <div class="col-sm-9">
                  <input name="amount_to_pay" type="number" class="form-control" placeholder="Amount to Pay" required>
                  </div>
                  </div>
				  
		<div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Posted By</label>
                  <div class="col-sm-9">
                 <?php
$tid = $_SESSION['tid'];
$sele = mysqli_query($link, "SELECT * from agent_data WHERE agentid = '$tid'") or die (mysqli_error($link));
while($row = mysqli_fetch_array($sele))
{
?>
                  <input name="teller" type="text" class="form-control" value="<?php echo $row['fname']; ?>" readonly>
<?php } ?>
                  </div>
                  </div>

			 </div>
			 
			  <div align="right">
              <div class="box-footer">
                				<button type="reset" class="btn bg-orange"><i class="fa fa-times">&nbsp;Reset</i></button>
                				<button name="save" type="submit" class="btn bg-blue"><i class="fa fa-save">&nbsp;Make Payment</i></button>

              </div>
			  </div>
			  </form>
			  

           
</div>	
</div>
</div>	
</div>