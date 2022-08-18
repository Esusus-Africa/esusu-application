<?php
include ("../config/session1.php");
$PostType = $_GET['PostType'];

$myaltcall = mysqli_query($link, "SELECT * FROM member_settings WHERE companyid = '$vcreated_by'");
$myaltrow = mysqli_fetch_array($myaltcall);

if($PostType == "Transfer Balance"){
    $searchUser = mysqli_query($link, "SELECT * FROM user WHERE branchid = '$vendorid' AND created_by = '$vcreated_by'");
    $fetchUser = mysqli_fetch_array($searchUser);
    $details = ($fetchUser['virtual_acctno'] == "") ? '' : 'A/C No: '.$fetchUser['virtual_acctno'].', Bank Name: '.$fetchUser['bankname'];
?>

			<div class="form-group">
                <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Account Details</label>
                <div class="col-sm-6">
                    <textarea name="details"  class="form-control" rows="2" cols="80" readonly="readonly" required><?php echo $details; ?></textarea>
                </div>
                <label for="" class="col-sm-3 control-label"></label>
            </div>
<?php
}
elseif($PostType == "Bank Account"){
    $searchBank = mysqli_query($link, "SELECT * FROM vendor_reg WHERE companyid = '$vendorid'");
    $fetchBank = mysqli_fetch_array($searchBank);
    $details = ($fetchBank['account_number'] == "") ? '' : 'A/C No: '.$fetchBank['account_number'].', Bank Name: '.$fetchBank['bankname']
?>

            <div class="form-group">
                <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Account Details</label>
                <div class="col-sm-6">
                    <textarea name="details"  class="form-control" rows="2" cols="80" readonly="readonly" required><?php echo $details; ?></textarea>
                </div>
                <label for="" class="col-sm-3 control-label"></label>
            </div>

<?php
}
else{
    //SHOW NOTHING
}
?>