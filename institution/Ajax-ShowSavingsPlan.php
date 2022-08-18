<?php include("../config/session1.php"); ?>
<div class="pfixing">
<?php
include('../config/ps_pagination.php');
$PostType = $_GET['PostType'];
$PostType2 = $_GET['PostType2'];

$myaltcall = mysqli_query($link, "SELECT * FROM member_settings WHERE companyid = '$institution_id'");
$myaltrow = mysqli_fetch_array($myaltcall);

$detect_subplan = mysqli_query($link, "SELECT * FROM savings_plan WHERE planType != 'Savings' AND status = 'Active' AND (categories = '$PostType' OR plan_name = '$PostType' OR branchid = '$PostType')");

//Create a PS_Pagination object
$pager = new PS_Pagination($link, $detect_subplan, 8, 10);

//The paginate() function returns a mysql result set for the current page
$rs = $pager->paginate();

while($fetch_subplan = mysqli_fetch_assoc($detect_subplan))
{
  $search_currency = mysqli_query($link, "SELECT * FROM systemset");
  $fetch_currency = mysqli_fetch_object($search_currency);
  
  $merchantid_others = $fetch_subplan['merchantid_others'];
  $vendorid = $fetch_subplan['branchid'];
  
  $search_vendors = mysqli_query($link, "SELECT * FROM vendor_reg WHERE companyid = '$vendorid'");
  $fetch_vendors = mysqli_fetch_object($search_vendors);
  
  $select_rave = mysqli_query($link, "SELECT * FROM member_settings WHERE companyid = '$merchantid_others'") or die (mysqli_error($link));
  $row_rave = mysqli_fetch_object($select_rave);
  
  $logopath = ($vendorid == "") ? $row_rave->logo : $fetch_vendors->logo;
  $cname = ($vendorid == "") ? $row_rave->cname : $fetch_vendors->cname;
?>

<style>
div.columns {
  background: url(<?php echo $fetchsys_config['file_baseurl'].$logopath; ?>) no-repeat;
}

ul.price {
  background-color: #ffffff;
  opacity: 0.92;
  filter: alpha(opacity=60); /* For IE8 and earlier */
}

ul.price li {
  font-weight: bold;
  color: #000000;
}

.tooltip-inner{
 
    padding:2px 7px;
 
    color:#55AAAA;
 
    text-align:center;
 
    font-weight:900;
 
    background: -webkit-gradient(linear, left top, left 25, from(#F4F4F4), color-stop(4%, #B4C8D6), to(#F4F4F4));
 
    background: -moz-linear-gradient(top, #F4F4F4, #B4C8D6 1px, #F4F4F4 25px);
 
    border: 1px solid #55AAAA;
 
    -webkit-border-radius:9px;
 
    -moz-border-radius:9px;
 
    border-radius:9px;   
 
}
</style>

<div class="columns">
  <ul class="price">
    <li height="5px"><img src="<?php echo $fetchsys_config['file_baseurl'].$logopath; ?>" height="70px" width="70px"></li>
    <li class="bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>"><?php echo ($fetch_subplan['plan_desc'] === "") ? '<b>'.strtoupper($cname).'</b>' : '<a href="#" data-toggle="tooltip" data-html="true" data-placement="right" title="Plan Description:'.$fetch_subplan['plan_desc'].'" style="color:white;"><b>'.strtoupper($cname).'</b><img src="'.$fetchsys_config['file_baseurl'].'down-arrow-new.gif" width="20px" height="20px"></a>'; ?></li>
    <li class="bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>"><b><?php echo strtoupper($fetch_subplan['plan_name']); ?></b><br><b style="font-size:20px;"><?php echo $fetch_subplan['currency'].number_format($fetch_subplan['min_amount'],2,'.',','); ?></b> minimum</li>
    <li>Dividend / ROI: <br><b><?php echo ($fetch_subplan['dividend'] != "0") ? ($fetch_subplan['dividend_type'] == "Flat Rate" ? $fetch_subplan['currency'].number_format($fetch_subplan['dividend'],2,'.',',').' Flat' : ($fetch_subplan['dividend_type'] == "Percentage" ? $fetch_subplan['dividend'].'%' : 'TBD')) : 'None'; ?></b></li>
   <li>Investment Interval: <br>(<b><?php echo ucfirst($fetch_subplan['savings_interval']); ?></b>)</li>
   <li>Maturity Period: <br><b><?php echo ($fetch_subplan['lock_withdrawal'] == "Yes") ? $fetch_subplan['frequency'].(($fetch_subplan['maturity_period'] == "weekly" ? ' week(s)' : ($fetch_subplan['maturity_period'] == "monthly" ? ' month(s)' : ' year(s)'))) : 'None'; ?> </b></li>
    <li class="bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>">
      <a href="verify_card3.php?id=<?php echo $_SESSION['tid']; ?>&&mid=<?php echo base64_encode("1000"); ?>&&pcode=<?php echo $fetch_subplan['plan_code']; ?>"><button type="button" class="btn bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>"> <i class="fa fa-search">&nbsp;Activate</i></button></a>
    </li>
  </ul>
</div>

<script>
$(document).ready(function(){
  $('[data-toggle="tooltip"]').tooltip();
});
</script>

<?php } ?>

<?php
  //Display the navigation
  //echo $pager->renderFullNav();
  echo '<div style="text-align:center">'.$pager->renderFullNav().'</div>';
?>
</div>