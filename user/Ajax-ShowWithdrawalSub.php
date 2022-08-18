<?php
include ("../config/session.php");
$PostType = $_GET['PostType'];

$myaltcall = mysqli_query($link, "SELECT * FROM member_settings WHERE companyid = '$bbranchid'");
$myaltrow = mysqli_fetch_array($myaltcall);

$search_stype = mysqli_query($link, "SELECT * FROM savings_subscription WHERE id = '$PostType'");
$fetch_stype = mysqli_fetch_array($search_stype);
$plancode = $fetch_stype['plan_code'];

$productname = mysqli_query($link, "SELECT * FROM savings_plan WHERE plan_code = '$plancode'");
$pnum = mysqli_num_rows($productname);
$fetchpname = mysqli_fetch_array($productname);

$productname1 = mysqli_query($link, "SELECT * FROM target_savings WHERE plan_code = '$plancode'");
$pnum1 = mysqli_num_rows($productname1);
$fetchpname1 = mysqli_fetch_array($productname1);

$myProductName = ($pnum == 1 && $pnum1 == 0) ? $fetchpname['plan_name'] : $fetchpname1['plan_name'];
$mysource = ($fetch_stype['rec_type'] == "Savings" ? "target" : (($fetch_stype['rec_type'] == "Takaful" || $fetch_stype['rec_type'] == "Investment" || $fetch_stype['rec_type'] == "Health") ? "investment" : "ledger"));
?>
			<input class="form-control" name="merchantid" type="hidden" value="<?php echo $fetch_stype['companyid']; ?>" id="HideValueFrank"/>
			<input class="form-control" name="vendorid" type="hidden" value="<?php echo $fetch_stype['vendorid']; ?>" id="HideValueFrank"/>
			<input class="form-control" name="stype" type="hidden" value="<?php echo $fetch_stype['categories']; ?>" id="HideValueFrank"/>
			<input class="form-control" name="plan_code" type="hidden" value="<?php echo $plancode; ?>" id="HideValueFrank"/>
			<input class="form-control" name="plan_name" type="hidden" value="<?php echo $myProductName; ?>" id="HideValueFrank"/>
			<input class="form-control" name="sub_code" type="hidden" value="<?php echo $fetch_stype['subscription_code']; ?>" id="HideValueFrank"/>
			<input class="form-control" name="validate_sub_bal" type="hidden" value="<?php echo $fetch_stype['sub_balance']; ?>" id="HideValueFrank"/>
			<input class="form-control" name="withdrawTime" type="hidden" value="<?php echo $fetch_stype['withdrawTime']; ?>" id="HideValueFrank"/>
			<input class="form-control" name="withdrawal_count" type="hidden" value="<?php echo $fetch_stype['withdrawal_count']; ?>" id="HideValueFrank"/>
			<input class="form-control" name="mature_date" type="hidden" value="<?php echo $fetch_stype['mature_date']; ?>" id="HideValueFrank"/>
			<input class="form-control" name="mysource" type="hidden" value="<?php echo $mysource; ?>" id="HideValueFrank"/>

			<div class="form-group">
                <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Balance Left</label>
                <div class="col-sm-6">
					<input name="amount" type="text" class="form-control" value="<?php echo number_format($fetch_stype['sub_balance'],0,'.',','); ?>" placeholder="Enter Amount to Withdraw" required>
                </div>
                <label for="" class="col-sm-3 control-label"></label>
            </div>