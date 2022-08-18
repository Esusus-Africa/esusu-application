<?php include("../config/session1.php"); ?>
<div class="pfixing">
<?php
include('../config/ps_pagination.php');
$PostType = $_GET['PostType'];

$myaltcall = mysqli_query($link, "SELECT * FROM member_settings WHERE companyid = '$institution_id'");
$myaltrow = mysqli_fetch_array($myaltcall);

$detect_subplan = mysqli_query($link, "SELECT * FROM account_openingbank WHERE bankcat = '$PostType' AND status = 'Show' ORDER BY id DESC");

//Create a PS_Pagination object
$pager = new PS_Pagination($link, $detect_subplan, 8, 10);

//The paginate() function returns a mysql result set for the current page
$rs = $pager->paginate();

while($fetch_subplan = mysqli_fetch_assoc($detect_subplan))
{
  $logopath = $fetch_subplan['logo'];
  $regType = $fetch_subplan['reg_type'];
?>

<style>
div.columns {
  background: url(../'<?php echo $logopath; ?>') no-repeat;
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
    <a href="accountOpening.php?id=<?php echo $_SESSION['tid']; ?>&&mid=<?php echo base64_encode("922"); ?>&&bid=<?php echo $fetch_subplan['id']; ?>&&tab=<?php echo ($regType == "Reg_With_BVN" ? 'tab_1' : ($regType == "Reg_Without_BVN" ? 'tab_2' : 'tab_1')); ?>"><img src='../<?php echo $logopath; ?>' width='250px' height='200px'></a>
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