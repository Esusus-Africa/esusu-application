<?php
include ("../config/session1.php");
$PostType = $_GET['PostType'];

$myaltcall = mysqli_query($link, "SELECT * FROM member_settings WHERE companyid = '$institution_id'");
$myaltrow = mysqli_fetch_array($myaltcall);

if($PostType == "Group")
{
?>          
      <div class="form-group">
                      <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">Group Name</label>
                      <div class="col-sm-10">
            <select name="gname" class="form-control select2" required>
                        <option value="" selected='selected'>Select Group Name&hellip;</option>
                      <?php
                      $search_group = mysqli_query($link, "SELECT * FROM lgroup_setup WHERE merchant_id = '$institution_id'");
                      while($fetch_group = mysqli_fetch_array($search_group))
                      {
                        $gname = $fetch_group['id'];
                        $search_borrower = mysqli_query($link, "SELECT * FROM borrowers WHERE rtype = 'Group' AND gname = '$gname'");
                        $gnum = mysqli_num_rows($search_borrower);
                      ?>
                        <option value="<?php echo $gname; ?>"><?php echo $fetch_group['gname'].' ('.number_format($gnum,0,'.',',').' of '.number_format($fetch_group['max_member'],0,'.',',').')'; ?></option>
                      <?php } ?>
            </select>
        </div>
        </div>

        <div class="form-group">
                      <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">Position in Group</label>
                      <div class="col-sm-10">
            <select name="g_position" class="form-control select2" required>
                        <option value="" selected='selected'>Select Position in Group&hellip;</option>
                        <option value="Leader">Leader</option>
                        <option value="Secretary">Secretary</option>
                        <option value="Treasurer">Treasurer</option>
                        <option value="Regular Member">Regular Member</option>
                        <option value="Other">Other</option>
            </select>
        </div>
        </div>

<?php
}
else{
?>

<input class="form-control" name="gname" type="hidden" id="HideValueFrank"/>
<input class="form-control" name="g_position" type="hidden" id="HideValueFrank"/>
      
<?php } ?>