<?php
include ("../config/session1.php");
$PostType = $_GET['PostType'];

$myaltcall = mysqli_query($link, "SELECT * FROM member_settings WHERE companyid = '$institution_id'");
$myaltrow = mysqli_fetch_array($myaltcall);

if($PostType == "Yes")
{
?>          
                <div class="form-group">
                    <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Savings Interval</label>
                    <div class="col-sm-7">
                        <select name="s_interval" class="form-control" required>
                            <option value="" selected='selected'>Select Interval&hellip;</option>	
                            <option value="daily">Daily</option>
                            <option value="weekly">Weekly</option>
                            <option value="monthly">Monthly</option>
                        </select>
                    </div>
                    <label for="" class="col-sm-2 control-label"></label>
                </div>
                
                <div class="form-group">
                      <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Avg. Savings Amount</label>
                      <div class="col-sm-7">
                          <input name="ave_samount" type="text" class="form-control" placeholder="AVerage Savings Amount" required>
                      </div>
                      <label for="" class="col-sm-2 control-label"></label>
                  </div>

                
                  <div class="form-group">
                      <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Duration</label>
                      <div class="col-sm-2">
                      <input name="duration" type="number" class="form-control" required>
                      <div style="color: <?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>"></div>
                      </div><?php echo (isMobileDevice()) ? "<br>" : ""; ?>
                      <label for="" class="col-sm-1 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Frequency</label>
                      <div class="col-sm-4">
                            <select name="frequency" class="form-control" required>
                                <option value="" selected='selected'>Select Frequency&hellip;</option>
                                <option value="Days">Days</option>
                                <option value="Week(s)">Week(s)</option>
                                <option value="Month(s)">Month(s)</option>
                                <option value="Year(s)">Year(s)</option>
                            </select>
                        </div>  
                    </div>

<?php
}
else{

    //Do Nothing

}
?>