<?php
include ("../config/connect.php");
$PostType = $_GET['PostType'];

$search = mysqli_query($link, "SELECT * FROM loan_product WHERE id='$PostType'") or die("Error: " . mysqli_error($link));
$get_lp = mysqli_fetch_array($search);
$interest = $get_lp['interest'];
$max_duration = $get_lp['duration'];
$tenor = $get_lp['tenor'];
?>
			<input class="form-control" name="interest" type="hidden" value="<?php echo $interest; ?>" id="HideValueFrank"/>
			
			<div class="form-group">
                <label for="" class="col-sm-2 control-label" style="color:blue;">Loan Tenor (<?php echo $tenor; ?>):</label>
				 <div class="col-sm-10">
                     <input name="pschedule" type="text" class="form-control" value="<?php echo $max_duration; ?>" readonly/>
                     <hr>
					 <span style="color: blue;"><b>Please note that the Repayment Fee will be Calculated Automatically When You Reach the Last Stage.</b></span>
				 </div>
			 </div>
  			<div class="form-group">
                    <label for="" class="col-sm-2 control-label" style="color:blue;">Amount</label>
                    <div class="col-sm-10">
                    <input name="amount" type="text" class="form-control" placeholder="Enter Amount to Borrow" required>
                    </div>
                    </div>
			 
 		    <script>
 		      $(function () {
 		        /* BOOTSTRAP SLIDER */
 		        $('.slider').slider()
 		      })
 		    </script>