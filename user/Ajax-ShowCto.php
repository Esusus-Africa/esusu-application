<?php
include("../config/session.php");

$PostType = $_GET['PostType'];

$myaltcall = mysqli_query($link, "SELECT * FROM member_settings WHERE companyid = '$bbranchid'");
$myaltrow = mysqli_fetch_array($myaltcall);

if($PostType == "Group")
{
?>

                <div class="form-group">
                    <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Upload Phone Numbers:</label>
                    <div class="col-sm-6">
                        <input type='file' name="my_file" class="alert bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'blue' : $myaltrow['alternate_color']; ?>" required>
  		  		        <div style="color:<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>;">Acceptable files are: .csv OR .txt file</div>
                    </div>
                    <label for="" class="col-sm-3 control-label"></label>
                </div>

<?php
}
elseif($PostType == "Personalize")
{
?>

                <div class="form-group">
                    <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Recipient Phone No:</label>
                    <div class="col-sm-6">
                        <textarea name="phone_nos" class="form-control" placeholder="Enter comma seperated phone numbers" rows="4" cols="5" required></textarea>
        				<div style="color:<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>;">Enter Comma Seperated Phone Numbers</div>
                    </div>
                    <label for="" class="col-sm-3 control-label"></label>
                </div>
      
<?php } ?>