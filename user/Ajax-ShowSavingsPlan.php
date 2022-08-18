<?php include("../config/session.php"); ?>
<div class="pfixing">
<?php
include('../config/ps_pagination.php');

function startsWith($string, $startString) 
{ 
    $len = strlen($startString); 
    return (substr($string, 0, $len) === $startString); 
}

$PostType = $_GET['PostType'];
$PostType2 = $_GET['PostType2'];

$myaltcall = mysqli_query($link, "SELECT * FROM member_settings WHERE companyid = '$bbranchid'");
$myaltrow = mysqli_fetch_array($myaltcall);

$detect_subplan = mysqli_query($link, "SELECT * FROM savings_plan WHERE merchantid_others = '$bbranchid' AND planType = '$PostType2' AND status = 'Active' AND (categories = '$PostType' OR plan_name = '$PostType' OR branchid = '$PostType')");

//Create a PS_Pagination object
$pager = new PS_Pagination($link, $detect_subplan, 8, 10);

//The paginate() function returns a mysql result set for the current page
$rs = $pager->paginate();

while($fetch_subplan = mysqli_fetch_assoc($detect_subplan))
{
  $explodeSubplan = explode(",",$fetch_subplan['plan_desc']);
  $countMD = (count($explodeSubplan) - 1);

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
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.1/jquery.modal.min.css" />

<div class="columns">
  <ul class="price">

    <li height="5px"><img src="<?php echo $fetchsys_config['file_baseurl'].$logopath; ?>" height="70px" width="70px"></li>

    <li class="bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">
        <!-- Link to open the modal -->
        <a href="#ex<?php for($i = 1; $i <= $countMD; $i++){echo $i;}?>" rel="modal:open" style="color:white;"><b><?php echo '<b>'.strtoupper($cname).'</b>'; ?></b><img src="<?php echo $fetchsys_config['file_baseurl']; ?>down-arrow-new.gif" width="20px" height="20px"></a>
        <!-- Modal HTML embedded directly into document -->
        
        <div id="ex<?php for($i = 1; $i <= $countMD; $i++){echo $i;}?>" class="modal">
            
        <?php

        if($fetch_subplan['plan_desc'] == ""){
            
            echo "NO AVAILABLE CONTENTS";
            
        }else{
                      
            for($i = 0; $i <= $countMD; $i++){
                
                echo '<p>'.ucwords($explodeSubplan[$i]).'</p>';
        
            }
            
        }
        ?>
        <a href="#" rel="modal:close">Close</a>
        </div>
    </li>
    
    <li class="bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>"><b><?php echo strtoupper($fetch_subplan['plan_name']); ?></b><br><b style="font-size:20px;"><?php echo $fetch_subplan['currency'].number_format($fetch_subplan['min_amount'],2,'.',','); ?></b> minimum</li>
    
    <li>Dividend / ROI: <br><b><?php echo ($fetch_subplan['dividend'] != "0") ? ($fetch_subplan['dividend_type'] == "Flat Rate" ? $fetch_subplan['currency'].number_format($fetch_subplan['dividend'],2,'.',',').' Flat' : ($fetch_subplan['dividend_type'] == "Percentage" ? $fetch_subplan['dividend'].'%' : 'TBD')) : 'None'; ?></b></li>
   
    <li>Plan Interval: <br>(<b><?php echo ucfirst($fetch_subplan['savings_interval']); ?></b>)</li>
       
    <li class="bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>">
      <a href="verify_card3.php?tid=<?php echo $_SESSION['tid']; ?>&&acn=<?php echo $acnt_id; ?><?php echo ($PostType2 == "Takaful" ? '&&mid=NTA0&&Takaful' : ($PostType2 == "Health" ? '&&mid=MTAwMA==&&Health' : ($PostType2 == "Donation" ? '&&mid=NjA0&&Donation' : ($PostType2 == "Savings" ? '&&mid=NTAw&&Savings' : '&&mid=NDA3')))); ?>&&pcode=<?php echo $fetch_subplan['plan_code']; ?>"><button type="button" class="btn bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>"> <i class="fa fa-search">&nbsp;Activate</i></button></a>
    </li>

  </ul>
</div>

<?php
} 

echo '<hr>';

//Display the navigation
//echo $pager->renderFullNav();
echo '<div style="text-align:center">'.$pager->renderFullNav().'</div>';
?>

</div>

<!-- Remember to include jQuery :) -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.0.0/jquery.min.js"></script>

<!-- jQuery Modal -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.1/jquery.modal.min.js"></script>