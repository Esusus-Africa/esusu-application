<div class="row">

	<section class="content">  
	    <div class="box box-success">
        <div class="box-body">
        <div class="table-responsive">
        <div class="box-body">

        <form method="post">

            <div class="box-body">
                 
	            <div class="form-group">
                  <label for="" class="col-sm-1 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">From</label>
                  <div class="col-sm-3">
                  <input name="dfrom" type="date" id="startDate" class="form-control" placeholder="To Date: 2018-05-01">
				  <span style="color: <?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>;">Date format: 2018-05-01</span>
                  </div>
				  
				  <label for="" class="col-sm-1 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">To</label>
                  <div class="col-sm-3">
                  <input name="dto" type="date" id="endDate" class="form-control" placeholder="To Date: 2018-05-24">
				  <span style="color: <?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>;">Date format: 2018-05-24</span>
                  </div>

                  <label for="" class="col-sm-1 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Filter By</label>
                  <div class="col-sm-3">
                    <select name="filter_by" id="filterBy" class="form-control select2" style="width:100%">
                        <option value="" selected="selected">Filter By...</option>
                        <option value="All">All Verification</option>
      
                        <option disabled>Filter By Institution</option>
                        <?php
                        $get5 = mysqli_query($link, "SELECT * FROM institution_data WHERE status = 'Approved' ORDER BY id DESC") or die (mysqli_error($link));
                        while($rows5 = mysqli_fetch_array($get5))
                        {
                        ?>
                        <option value="<?php echo $rows5['institution_id']; ?>"><?php echo $rows5['institution_name']; ?></option>
                        <?php } ?>

                        <option disabled>Filter By Branch</option>
                        <?php
                        $get7 = mysqli_query($link, "SELECT * FROM branches WHERE bstatus = 'Operational' ORDER BY id DESC") or die (mysqli_error($link));
                        while($rows7 = mysqli_fetch_array($get7))
                        {
                        ?>
                        <option value="<?php echo $rows7['branchid']; ?>"><?php echo $rows7['bname'].' ['.$rows7['branchid'].']'; ?></option>
                        <?php } ?>
                
                        <option disabled>Filter By Staff</option>
                        <?php
                            $get2 = mysqli_query($link, "SELECT * FROM user WHERE comment = 'Approved' ORDER BY id DESC");
                            while($rows2 = mysqli_fetch_array($get2))
                            {
                            ?>
                            <option value="<?php echo $rows2['id']; ?>"><?php echo $rows2['name'].' '.$rows2['lname'].' '.$rows2['mname']; ?></option>
                        <?php } ?>
				    </select>
                  </div>
                </div>
            </div>
                
                
        <hr>
            <div class="table-responsive">
			    <table id="fetch_idverificationhistory_data" class="table table-bordered table-striped">
			        <thead>
                    <tr>
                      <th><input type="checkbox" id="select_all"/></th>
                      <th>Reference</th>
                      <th>Institution</th>
                      <th>Type</th>
    				  <th>Branch</th>
                      <th>Operator</th>
                      <th>AccountID</th>
                      <th>Verification Status</th>
                      <th>Transaction Status</th>
                      <th>DateTime</th>
                     </tr>
                    </thead>
                </table>
            </div>

        </form>

        </div>
        </div>	
        </div>
        </div>

</div>