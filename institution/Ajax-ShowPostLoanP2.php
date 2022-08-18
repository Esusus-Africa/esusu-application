<?php
include ("../config/session1.php");
$PostType = $_GET['PostType'];

$myaltcall = mysqli_query($link, "SELECT * FROM member_settings WHERE companyid = '$institution_id'");
$myaltrow = mysqli_fetch_array($myaltcall);

$search = mysqli_query($link, "SELECT * FROM loan_product WHERE id='$PostType'") or die("Error: " . mysqli_error($link));
$get_lp = mysqli_fetch_array($search);
$interest = $get_lp['interest'];
$max_duration = $get_lp['duration'];
$tenor = $get_lp['tenor'];
?>
			<input class="form-control" name="interest" type="hidden" value="<?php echo $interest; ?>" id="HideValueFrank"/>
			
			<div class="form-group">
                <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Loan Tenor (<?php echo $tenor; ?>):</label>
				 <div class="col-sm-10">
                     <input name="pschedule" type="text" class="form-control" value="<?php echo $max_duration; ?>" readonly/>
                     <hr>
					 <span style="color: <?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;"><b>Please note that the repayment fee will be calculated automatically using this schedule.</b></span>
				     <hr>
				 </div>
			 </div>