<?php include ("../config/session.php"); ?>
<div class="pfixing">
<?php
include('../config/ps_pagination.php');

function startsWith($string, $startString) 
{ 
    $len = strlen($startString); 
    return (substr($string, 0, $len) === $startString); 
}

$PostType = $_GET['PostType'];
$InstType = $_GET['InstType'];

$detect_subplan = mysqli_query($link, "SELECT * FROM saas_subscription_plan WHERE plan_category = '$InstType' AND category = '$PostType' AND status = 'Active' ORDER BY plan_name ASC");

//Create a PS_Pagination object
$pager = new PS_Pagination($link, $detect_subplan, 4, 3);

//The paginate() function returns a mysql result set for the current page
$rs = $pager->paginate();

while($fetch_subplan = mysqli_fetch_array($detect_subplan))
{
    $explodeSubplan = explode(",",$fetch_subplan['others']);
    $countMD = (count($explodeSubplan) - 1);
    $demo_type = $fetchsys_config['demo_type'];
    $demo_rate = $fetchsys_config['demo_rate'];
?>

<style>
div.columns {
  background: url(<?php echo $fetchsys_config['image']; ?>) no-repeat;
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
    <li height="5px"><img src="<?php echo $fetchsys_config['image']; ?>" height="70px" width="70px"></li>

    <li class="bg-blue">
        <!-- Link to open the modal -->
        <a href="#ex<?php for($i = 1; $i <= $countMD; $i++){echo $i;}?>" rel="modal:open" style="color:white;"><b>VIEW MODULES</b><img src="../img/down-arrow-new.gif" width="20px" height="20px"></a>
        <!-- Modal HTML embedded directly into document -->
        
        <div id="ex<?php for($i = 1; $i <= $countMD; $i++){echo $i;}?>" class="modal">
            
        <?php

        if($fetch_subplan['others'] == ""){
            
            echo "NO AVAILABLE MODULE";
            
        }else{
                      
            for($i = 0; $i <= $countMD; $i++){
            
                
                echo '<p>=>('.$i.') '.ucwords(str_replace("_"," ",$explodeSubplan[$i])).'</p>';
        
            }
            
        }
        ?>
        <a href="#" rel="modal:close">Close</a>
        </div>
    </li>
    
    <li class="bg-orange"><b><?php echo strtoupper($fetch_subplan['plan_name']); ?></b><br><b style="font-size:20px;"><?php echo $fetch_currency->currency.number_format($fetch_subplan['amount_per_months'],2,'.',','); ?></b> /<?php echo ($demo_type == 'day' && $fetch_subplan['amount_per_months'] == '0' ? 'day(s)' : ($demo_type == 'month' && $fetch_subplan['amount_per_months'] == '0' ? 'month(s)' : 'month(s)')); ?></li>
    
    <li>Expiration Grace: <b><?php echo $fetch_subplan['expiration_grace'].' Day(s)'; ?></b></li>

    <li>No. of Staff: <b><?php echo $fetch_subplan['staff_limit']; ?></b></li>
   
    <li>No. of Branch: <b><?php echo $fetch_subplan['branch_limit']; ?></b></li>
   
    <li>No. of Customer: <b><?php echo $fetch_subplan['customers_limit']; ?> </b></li>
    
    <li class="bg-orange">
      <a href="finalize_saassub.php?id=<?php echo $_SESSION['tid']; ?>&&mid=NDIw&&pcode=<?php echo $fetch_subplan['plan_code']; ?>"><button type="button" class="btn bg-blue"> <i class="fa fa-search">&nbsp;Activate</i></button></a>
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