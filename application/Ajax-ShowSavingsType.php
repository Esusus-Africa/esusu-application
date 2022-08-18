<?php
include ("config/connect.php");
$PostType = $_GET['PostType'];

if($PostType == "Normal")
{
?>
				<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Status</label>
                  <div class="col-sm-4">
                  <select name="status"  class="form-control select2" style="width:100%">
					<option selected="selected">Filter By Status...</option>
					<option value="Pending">Pending</option>
					<option value="Paid">Paid</option>
					<option value="Expired">Expired</option>
					<option value="Deactivated">Deactivated</option>
				  </select>
                  </div>
				  
				  <label for="" class="col-sm-2 control-label" style="color:blue;">Cooperatives</label>
                  <div class="col-sm-4">
                  <select name="coopid"  class="form-control select2" style="width:100%">
					<option selected="selected">Filter By Cooperative...</option>
					<?php
					$get = mysqli_query($link, "SELECT * FROM cooperatives ORDER BY id") or die (mysqli_error($link));
					while($rows = mysqli_fetch_array($get))
					{
					?>
					<option value="<?php echo $rows['coopid']; ?>"><?php echo $rows['coopname']; ?></option>
					<?php } ?>				
				  </select>
                  </div>
                </div>

            <div class="form-group">
                <label for="" class="col-sm-2 control-label" style="color:blue;">Institution </label>
                <div class="col-sm-4">
                <select name="instid"  class="form-control select2" style="width:100%">
				<option selected="selected">Filter By Institution...</option>
				<?php
				$get = mysqli_query($link, "SELECT * FROM institution_data WHERE status = 'Approved' ORDER BY id") or die (mysqli_error($link));
				while($rows = mysqli_fetch_array($get))
				{
				?>
				<option value="<?php echo $rows['institution_id']; ?>"><?php echo $rows['institution_name']; ?></option>
				<?php } ?>				
				</select>
                </div>

                <label for="" class="col-sm-2 control-label" style="color:blue;">Agent </label>
                <div class="col-sm-4">
                <select name="agentid"  class="form-control select2">
				<option selected="selected">Filter By Agent...</option>
				<?php
				$get = mysqli_query($link, "SELECT * FROM agent_data WHERE status = 'Approved' ORDER BY id") or die (mysqli_error($link));
				while($rows = mysqli_fetch_array($get))
				{
				?>
				<option value="<?php echo $rows['agentid']; ?>"><?php echo $rows['fname']; ?></option>
				<?php } ?>				
				</select>
                </div>
            </div>
           <hr>
		
<?php
}
elseif($PostType == "Recurring")
{
?>

			 <div align="right">
              <div class="box-footer">
                				<button name="search_normal" type="submit" class="btn bg-blue btn-flat"><i class="fa fa-filter">&nbsp;Search</i></button>

              </div>
		</div>

<?php
}
?>
