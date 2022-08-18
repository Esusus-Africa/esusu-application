<?php
include ("../config/session1.php");
$PostType = $_GET['PostType'];

$myaltcall = mysqli_query($link, "SELECT * FROM member_settings WHERE companyid = '$institution_id'");
$myaltrow = mysqli_fetch_array($myaltcall);

if($PostType == "Yes")
{
?>

            <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Maturity Period</label>
                  <div class="col-sm-10">
                  <select name="dinterval" class="form-control select2" style="width: 100%;" /required>
  				<option value="" selected="selected">---Choose Maturity Period---</option>
				<option value="weekly">Weekly</option>
				<option value="monthly">Monthly</option>
				<option value="annually">Yearly</option>
                  </select>
				  </div>
            </div>
				  
			<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Frequency</label>
                  <div class="col-sm-10">
                  <input name="freq" type="number" class="form-control" placeholder="Enter Frequency based on Maturity Period e.g 1, 2, 4, 5 etc." /required>
                  <span style="color: <?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>;"><u><b>NOTE:</b></u> If <b>frequency</b> is set to 2 and <b>Maturity Period</b> is set to annually, the customer would be able to withdraw his/her fund after 2 years.</span>
                  </div>
                  </div>
		
<?php
}elseif($PostType == "Lock"){
?>

            <input name="dinterval" type="hidden" class="form-control" value="None" /readonly>
            
            <input name="freq" type="hidden" class="form-control" value="0" /readonly>

<?php
}else{
    echo "";
}
?>