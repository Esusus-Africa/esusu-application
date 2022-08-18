<?php
include ("../config/session1.php");
$PostType = $_GET['PostType'];

$myaltcall = mysqli_query($link, "SELECT * FROM member_settings WHERE companyid = '$vcreated_by'");
$myaltrow = mysqli_fetch_array($myaltcall);

if($PostType == "ONE-OFF")
{
?>

                <input name="duration" type="hidden" class="form-control" value="1" /readonly>
		
<?php
}
else{
?>

                <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Plan Duration</label>
                  <div class="col-sm-9">
                  <input name="duration" type="number" class="form-control" placeholder="Enter Duration" /required>
                  <span style="color: <?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>;"><u><b>NOTE:</b></u> If <b>Plan Duration</b> is set to 5 and <b>investment intervals</b> is set to monthly, the customer would be charged 5 months and then the investment stops.</span>
                  </div>
                  </div>

<?php
}
?>