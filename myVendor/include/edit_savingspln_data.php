<div class="box">
	       <div class="box-body">
			<div class="panel panel-success">
            <div class="panel-heading bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">
            <h3 class="panel-title"> <a href="create_msavingsplan.php?id=<?php echo $_SESSION['tid']; ?>&&mid=NDkw"><button type="button" class="btn btn-flat bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>"><i class="fa fa-mail-reply-all"></i>&nbsp;Back</button></a> <i class="fa fa-plus"></i> Update Investment Plan</h3>
            </div>

             <div class="box-body">
<?php
if(isset($_POST['save']))
{
    $my_plid = $_GET['plid'];
    $pmethod = implode(',', mysqli_real_escape_string($link, $_POST['pmethod']));
	//MERCHANT COMMISSION SETTINGS
	$commtype = mysqli_real_escape_string($link, $_POST['commtype']);
	$commvalue = mysqli_real_escape_string($link, $_POST['commvalue']);

	$insert = mysqli_query($link, "UPDATE savings_plan SET commtype = '$commtype', commvalue = '$commvalue', pmethod = '$pmethod', status = 'Pending' WHERE id = '$my_plid')") or die ("Error: " . mysqli_error($link));

	echo "<script>alert('Plan updated successfully and presently under review!....\\nYou will be notify when approved by the moderator'); </script>";

}
?>          
			 <form class="form-horizontal" method="post" enctype="multipart/form-data">

             <div class="box-body">
<?php
$plid = $_GET['plid'];
$search_sinfo = mysqli_query($link, "SELECT * FROM savings_plan WHERE id = '$plid'");
$fetch_sinfo = mysqli_fetch_object($search_sinfo);
?>	 			
			<div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Plan Name</label>
                  <div class="col-sm-9">
                  <input name="spname" type="text" class="form-control" value="<?php echo $fetch_sinfo->plan_name; ?>" placeholder="Investment Plan Name" /readonly>
                  </div>
                  </div>

		    <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Investment Amount</label>
                  <div class="col-sm-9">
                  <input name="amount" type="text" class="form-control" value="<?php echo $fetch_sinfo->amount; ?>" placeholder="Enter Investment Amount" /readonly>
                  </div>
                  </div>

            <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Payment Method(s)</label>
                  <div class="col-sm-9">
                  <select name="pmethod[]" class="form-control select2" style="width: 100%;" multiple="multiple" /required>
                      <?php
                        $explodePlan = explode(",",$fetch_sinfo->pmethod);
    
                        $countPlan = (count($explodePlan) - 1);
                        
                        for($i = 0; $i <= $countPlan; $i++){
                            
                            echo '<option value="'.$explodePlan[$i].'" selected="selected">'.$explodePlan[$i].'</option>';
                            
                        }
                      ?>
        				<option value="wallet">Wallet</option>
                        <option value="card">Card</option>
                  </select>
				  </div>
            </div>
                  
            <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Subaccount Share Type</label>
                  <div class="col-sm-9">
                  <select name="commtype" class="form-control select2" style="width: 100%;" /required>
  				<option value="<?php echo $fetch_sinfo->commtype; ?>" selected="selected"><?php echo $fetch_sinfo->commtype; ?></option>
				<option value="flat">flat</option>
				<option value="percentage">percentage</option>
                  </select>
				  </div>
            </div>
            
            <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Subaccount Share</label>
                  <div class="col-sm-9">
                  <input name="commvalue" type="text" class="form-control" value="<?php echo $fetch_sinfo->commvalue; ?>" placeholder="Enter Subaccount Share" /required>
                  <span style="color: <?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>;">Note: Please do not enter symbol like %. The correct input are 50.6, 90, 98, 95 etc.</span>
                  </div>
                  </div>
			
			 </div>
			 
			  <div align="right">
              <div class="box-footer">
                				<button name="save" type="submit" class="btn bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>"><i class="fa fa-save">&nbsp;Save</i></button>

              </div>
			  </div>
			  
			 </form> 

</div>	
</div>	
</div>
</div>