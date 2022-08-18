<?php
include ("../config/session1.php");
$PostType = $_GET['PostType'];

$myaltcall = mysqli_query($link, "SELECT * FROM member_settings WHERE companyid = '$vcreated_by'");
$myaltrow = mysqli_fetch_array($myaltcall);

if($PostType == "Yes")
{
?>
				  
			<div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Number of times</label>
                  <div class="col-sm-9">
                  <input name="nots" type="number" class="form-control" placeholder="Enter Number of time Allowed for withdrawal before Maturity Period Reach e.g 1, 2, 4, 5 etc." /required>
                  <span style="color: <?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>;"><u><b>NOTE:</b></u> You can enter the number of times allowed for withdrawal before maturity period reach.</span>
                  </div>
                  </div>
		
<?php
}
else
{
    echo "";
}
?>