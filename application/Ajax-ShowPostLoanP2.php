<?php
include ("../config/connect.php");
$PostType = $_GET['PostType'];
$BAcctNo = $_GET['BAcctNo'];

$search = mysqli_query($link, "SELECT * FROM loan_product WHERE id='$PostType'") or die("Error: " . mysqli_error($link));
$get_lp = mysqli_fetch_array($search);
$interest = $get_lp['interest'];
$max_duration = $get_lp['lock_period'];

$search_schedule = mysqli_query($link, "SELECT * FROM payment_schedule WHERE tid='$BAcctNo'"); 
$fetch_schedule = mysqli_fetch_array($search_schedule);
$pschedule = $fetch_schedule['day'];

if($max_duration > 1)
{
?>
			<input class="form-control" name="interest" type="hidden" value="<?php echo $interest; ?>" id="HideValueFrank"/>
			
			
			<div class="form-group">
                <label for="" class="col-sm-2 control-label" style="color:#009900">Loan Tenor (Months):</label>
				 <div class="col-sm-10">
                     <input type="text" name="pschedule" class="slider form-control" data-slider-min="1" data-slider-max="<?php echo $max_duration; ?>" data-slider-step="1" data-slider-value="[1,<?php echo $pschedule; ?>]" data-slider-orientation="horizontal" data-slider-selection="before" data-slider-tooltip="show" data-slider-id="blue">
					 <hr>
					 <span style="color: blue;"><b>Please note that the Repayment Fee will be Calculated Automatically When You Reach the Last Stage.</b></span>
				 </div>
			 </div>
			 
 		    <script>
 		      $(function () {
 		        /* BOOTSTRAP SLIDER */
 		        $('.slider').slider()
 		      })
 		    </script>
<?php
}
else{
?>
			<input class="form-control" name="interest" type="hidden" value="<?php echo $interest; ?>" id="HideValueFrank"/>
			
			
			<div class="form-group">
                <label for="" class="col-sm-2 control-label" style="color:#009900">Loan Tenor (Days):</label>
				 <div class="col-sm-10">
                     <input type="text" name="pschedule" id="pScroll" class="slider form-control" data-slider-min="15" data-slider-max="<?php echo $interest; ?>" data-slider-step="5" data-slider-value="[15,<?php echo $pschedule; ?>]" data-slider-orientation="horizontal" data-slider-selection="before" data-slider-tooltip="show" data-slider-id="blue">
					 <hr>
					 <span style="color: blue;"><b>Please note that the Repayment Fee will be Calculated Automatically When You Reach the Last Stage.</b></span>
				 </div>
			 </div>
 			
			 
		     <script>
		       $(function () {
		         /* BOOTSTRAP SLIDER */
		         $('.slider').slider()
		       })
		     </script>
<?php } ?>
