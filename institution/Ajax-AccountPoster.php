<?php
include ("../config/session1.php");
$PostType = $_GET['PostType'];

$myaltcall = mysqli_query($link, "SELECT * FROM member_settings WHERE companyid = '$institution_id'");
$myaltrow = mysqli_fetch_array($myaltcall);

if($PostType == "")
{
    echo "";
}
else{
    $search = mysqli_query($link, "SELECT * FROM borrowers WHERE branchid = '$institution_id' AND account = '$PostType'") or die (mysqli_error($link));
    $fetchB = mysqli_fetch_array($search);
?>

            <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Balance to Impact</label>
                  <div class="col-sm-10">
                    <select name="balanceToImpact" class="form-control select2" required>
                        <option value="" selected>Select Balance</option>
                        <option value="ledger">Ledger Balance: <?php echo $icurrency.number_format($fetchB['balance'],2,'.',','); ?></option>
                        <option value="target">Target Savings Balance: <?php echo $icurrency.number_format($fetchB['target_savings_bal'],2,'.',','); ?></option>
                        <option value="investment">Investment Balance: <?php echo $icurrency.number_format($fetchB['investment_bal'],2,'.',','); ?></option>
                        <option value="loan">Loan Balance: <?php echo $icurrency.number_format($fetchB['loan_balance'],2,'.',','); ?></option>
                        <option value="asset">Asset Acquisition Balance: <?php echo $icurrency.number_format($fetchB['asset_acquisition_bal'],2,'.',','); ?></option>
                    </select>
				</div>
            </div>

<?php
}
?>