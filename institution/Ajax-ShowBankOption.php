<?php
include ("../config/session1.php");
$PostType = $_GET['PostType'];

$myaltcall = mysqli_query($link, "SELECT * FROM member_settings WHERE companyid = '$institution_id'");
$myaltrow = mysqli_fetch_array($myaltcall);

if($PostType == "Bank")
{
?>

            <div class="form-group">
                <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Country</label>
                <div class="col-sm-10">
                <select name="country" class="form-control select2" id="country" onchange="loadbank();" required>
                <option selected>Please Select Country</option>
                <option value="NG">Nigeria</option>
                <option value="GH">Ghana</option>
                <option value="KE">Kenya</option>
                <option value="UG">Uganda </option>
                <option value="TZ">Tanzania</option>
                </select>                 
            </div>
            </div>
          
			<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">Account Number</label>
                  <div class="col-sm-10">
                  <input name="acct_no" type="text" id="account_number" onkeydown="loadaccount();" class="form-control" placeholder="Account Number" required>
                  
                  </div>
                  </div>
                  
            <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Bank Code</label>
                  <div class="col-sm-10">
                    <div id="bank_list"></div>
        </div>
        </div>
        
        <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Account Holder</label></label>
                  <div class="col-sm-10">
                    <span id="act_numb"></span>
        </div>
        </div>


<?php
}
else{
    echo "";
}
?>