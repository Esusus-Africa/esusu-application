<div class="box">
	       <div class="box-body">
			<div class="panel panel-success">
            <div class="panel-heading bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">
            <h3 class="panel-title"><i class="fa fa-refresh"></i> <?php echo (isset($_GET['Takaful']) ? 'Search Takaful Plan' : (isset($_GET['Health']) ? 'Search Health Plan' : (isset($_GET['Donation']) ? 'Search Donation Plan' : (isset($_GET['Savings']) ? 'Search Savings Plan' : 'Search Investment Plan')))); ?></h3>
            </div>
             <div class="box-body">

			 <form class="form-horizontal" method="post" enctype="multipart/form-data">

<?php
$planType = (isset($_GET['Takaful']) ? "Takaful" : (isset($_GET['Health']) ? "Health" : (isset($_GET['Donation']) ? "Donation" : (isset($_GET['Investment']) ? "Investment" : "Savings"))));
?>

             <div class="box-body">
                 
            <input name="savings_plan2" type="hidden" class="form-control" id="savings_plan2" value="<?php echo (isset($_GET['Takaful']) ? 'Takaful' : (isset($_GET['Health']) ? 'Health' : (isset($_GET['Donation']) ? 'Donation' : (isset($_GET['Savings']) ? 'Savings' : 'Investment')))); ?>">
			
			<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;"><?php echo (isset($_GET['Takaful']) ? 'Search Takaful Plan' : (isset($_GET['Health']) ? 'Search Health Plan' : (isset($_GET['Donation']) ? 'Search Donation Plan' : (isset($_GET['Savings']) ? 'Search Savings Plan' : 'Search Investment Plan')))); ?></label>
                  <div class="col-sm-10">
                  <select name="savings_plan" class="select2" id="savings_plan" data-placeholder="Search By" style="width: 100%;" required>
                      <option value="" selected>---Select Plan---</option>
      				<option disabled>FILTER BY CATEGORY</option>
  	                <?php
  					$getin = mysqli_query($link, "SELECT DISTINCT categories FROM savings_plan WHERE merchantid_others = '$bbranchid' AND status = 'Active' AND planType = '$planType'") or die (mysqli_error($link));
  					while($row = mysqli_fetch_array($getin))
  					{
  					    echo '<option value="'.$row['categories'].'">'.$row['categories'].'</option>';
  					}
  					?>
  					
  					<option disabled>FILTER BY PLAN NAME</option>
				  <?php
					$getin = mysqli_query($link, "SELECT DISTINCT plan_name FROM savings_plan WHERE merchantid_others = '$bbranchid' AND status = 'Active' AND planType = '$planType'") or die (mysqli_error($link));
					while($row = mysqli_fetch_array($getin))
  					{
  					    echo '<option value="'.$row['plan_name'].'">'.$row['plan_name'].'</option>';
  					}
  					?>
  					
  					<option disabled>FILTER BY VENDOR</option>
				  <?php
					$getin = mysqli_query($link, "SELECT DISTINCT branchid FROM savings_plan WHERE merchantid_others = '$bbranchid' AND status = 'Active' AND planType = '$planType'") or die (mysqli_error($link));
					while($row = mysqli_fetch_array($getin))
  					{
  					    $myvendorid = $row['branchid'];
  					    $search_vendo = mysqli_query($link, "SELECT * FROM vendor_reg WHERE companyid = '$myvendorid'");
  					    $fetch_vendo = mysqli_fetch_array($search_vendo);
  					    
  					    echo '<option value="'.$myvendorid.'">'.$fetch_vendo['cname'].'</option>';
  					}
  					?>
                  </select>
				  </div>
            </div>

       <span id='ShowValueFrank'></span>
       <span id='ShowValueFrank'></span>
       <span id='ShowValueFrank'></span>
       <span id='ShowValueFrank'></span>

			 </div>
       		 
			  <div align="right">
              <div class="box-footer">
                	<a href="my_savings_plan.php?tid=<?php echo $_SESSION['tid']; ?>&&acn=<?php echo $_SESSION['acctno']; ?>&&mid=<?php echo (isset($_GET['Takaful']) ? 'NTA0' : (isset($_GET['Health']) ? 'MTAwMA==' : (isset($_GET['Savings']) ? 'NTAw' : (isset($_GET['Donation']) ? 'NjA0' : 'NDA3')))); ?><?php echo ((isset($_GET['Takaful'])) ? '&&Takaful' : ((isset($_GET['Health'])) ? '&&Health' : ((isset($_GET['Savings'])) ? '&&Savings' : ((isset($_GET['Donation'])) ? '&&Donation' : '')))); ?>"><button type="button" class="btn bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>"><i class="fa fa-mail-reply-all"></i>&nbsp;Back</button></a>
              </div>
			  </div>
			
			 </form> 


</div>	
</div>	
</div>
</div>