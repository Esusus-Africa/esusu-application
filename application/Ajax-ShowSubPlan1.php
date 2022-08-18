<div class="pfixing">
<?php
include ("../config/connect.php");
$PostType = $_GET['PostType'];

$detect_subplan = mysqli_query($link, "SELECT * FROM saas_subscription_plan WHERE plan_category = '$PostType'");
while($fetch_subplan = mysqli_fetch_array($detect_subplan))
{
  $search_currency = mysqli_query($link, "SELECT * FROM systemset");
  $fetch_currency = mysqli_fetch_object($search_currency);
?>

<div class="columns">
  <ul class="price">
    <li class="header"><?php echo $fetch_subplan['plan_name']; ?></li>
    <li class="grey"><b><?php echo $fetch_currency->currency.number_format($fetch_subplan['amount_per_months'],2,'.',','); ?></b> / month(s)</li>
    <li><b><?php echo number_format($fetch_subplan['sms_allocated']); ?></b> SMS</li>
    <?php echo ($fetch_subplan['expiration_grace'] == '') ? '' : '<li><b>'.$fetch_subplan['expiration_grace'].'</b> Days Expiration Grace</li>'; ?>
    <?php echo ($fetch_subplan['staff_limit'] == '') ? '' : '<li><b>'.$fetch_subplan['staff_limit'].'</b> Staff(s)/Member(s)</li>'; ?>
    <?php echo ($fetch_subplan['branch_limit'] == '') ? '' : '<li><b>'.$fetch_subplan['branch_limit'].'</b> Branches </li>'; ?>
    <?php echo ($fetch_subplan['customers_limit'] == '') ? '' : '<li><b>'.$fetch_subplan['customers_limit'].'</b> Customer(s) Allowed</li>'; ?>
    <li class="grey">
      <a href="finalize_saassub1.php?id=<?php echo $_SESSION['tid']; ?>&&mid=NDIw&&pcode=<?php echo $fetch_subplan['plan_code']; ?>" class="button"> <i class="fa fa-search">&nbsp;Activate</i></a>
    </li>
  </ul>
</div>

<?php } ?>

</div>