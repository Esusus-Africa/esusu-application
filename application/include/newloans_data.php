<div class="box">
        
	       <div class="box-body">
			<div class="panel panel-success">
            <div class="panel-heading bg-blue">
            <h3 class="panel-title"><i class="fa fa-money"></i>&nbsp;Individual Loans</h3>
            </div>
             <div class="box-body">

			 <form class="form-horizontal" method="post" enctype="multipart/form-data" action="process_loan_info.php">

             <div class="box-body">
				
			<div class="form-group">
                <label for="" class="col-sm-2 control-label" style="color:blue;">Loan Product</label>
				 <div class="col-sm-10">
                <select name="lproduct" class="select2" id="loan_products" style="width: 100%;" required>
				<option value="" selected="selected">--Select Loan Product--</option>
	                <?php
					$getin = mysqli_query($link, "SELECT * FROM loan_product WHERE category = 'Individual' ORDER BY id") or die (mysqli_error($link));
					while($row = mysqli_fetch_array($getin))
					{
					echo '<option value="'.$row['id'].'">'.$row['pname'].' - '.'(Interest Rate: '. $row['interest'].'% based on tenor)'.'</option>';
					}
					?>
                </select>
              </div>
			</div>
			  
  			<span id='ShowValueFrank'></span>
  			<span id='ShowValueFrank'></span>
			  
			 <div class="form-group">
                <label for="" class="col-sm-2 control-label" style="color:blue;">Borrower</label>
				 <div class="col-sm-10">
                <select name="borrower" class="customer select2" style="width: 100%;" required>
				<option value="" selected="selected">--Select Customer Account--</option>
				<?php
				$get = mysqli_query($link, "SELECT * FROM borrowers ORDER BY id") or die (mysqli_error($link));
				while($rows = mysqli_fetch_array($get))
				{
				echo '<option value="'.$rows['id'].'">'.$rows['fname'].'&nbsp;'.$rows['lname'].'</option>';
				}
				?>
                </select>
              </div>
			  </div>
			  
			<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Account</label>
                  <div class="col-sm-10">
                  <select class="account select2" name="account" style="width: 100%;" required>
				<option value="" selected="selected">--Select Customer Account--</option>
                  <?php
				$getin = mysqli_query($link, "SELECT * FROM borrowers ORDER BY id") or die (mysqli_error($link));
				while($row = mysqli_fetch_array($getin))
				{
				echo '<option value="'.$row['account'].'">'.$row['account'].'</option>';
				}
				?>
				</select>
                  </div>
                  </div>
				  
		<div class="form-group">
              <label for="" class="col-sm-2 control-label" style="color:blue;">BVN Number</label>
              <div class="col-sm-10">
				  <span style="color: orange;"> Note that BVN Verification cost <b style="color: blue;"><?php echo $fetchsys_config['currency'].number_format($fetchsys_config['bvn_fee'],2,'.',','); ?></b> for Nigeria Account Only. </span>
              <input name="unumber" type="text" id="unumber" onkeydown="loaddata();" class="form-control" placeholder="BVN Number Here" maxlength="11" required>
			   <div id="bvn2"></div><br>
              </div>
              </div>
				  
		<div class="form-group">
                <label for="" class="col-sm-2 control-label" style="color:blue;">Income Amount</label>
                <div class="col-sm-10">
                <input name="income" type="text" class="form-control"  placeholder="Enter Your Income" required>
                </div>
                </div>
				
		<div class="form-group">
                <label for="" class="col-sm-2 control-label" style="color:blue;">Application Date</label>
                <div class="col-sm-10">
                <input name="salary_date" type="date" class="form-control">
                </div>
                </div>
		
		<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Employer Name</label>
                  <div class="col-sm-10">
                  <input name="employer" type="text" class="form-control" required>
                  </div>
                  </div>
		
		 <div class="form-group">
                  	<label for="" class="col-sm-2 control-label" style="color:blue;">Your Bank Account Info.</label>
                  	<div class="col-sm-10">
					<textarea name="bacinfo"  class="form-control" rows="2" cols="80" required></textarea>
					<span style="color: orange;"><b>PLEASE NOTE THAT YOU ARE TO PROVIDE VALID ACCOUNT DETAILS AS THIS IS WHERE YOUR FUND WILL BE SENT TO ONCE APPROVED!!</b></span>
           			 </div>
					 </div>
		<!--	  
		 <div class="form-group">
                <label for="" class="col-sm-2 control-label" style="color:blue;">Date Release</label>
			 <div class="col-sm-10">
              <div class="input-group date">
                  <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                  </div>
                  <input name="date_release" type="date" class="form-control pull-right" required>
                </div>
              </div>
			  </div>
		-->
			  
	 <div class="form-group">
                 	<label for="" class="col-sm-2 control-label" style="color:blue;">Reasons for Loan.</label>
                 	<div class="col-sm-10">
                <select name="lreasons" class="form-control select2" style="width: 100%;" required>
				    <option value="" selected="selected">--Select Reasons for Loan--</option>
	                <option value="Acquire Property">Acquire Property</option>
	                <option value="Appliances/Electronic Gadgets">Appliances/Electronic Gadgets</option>
	                <option value="Build Property">Build Property</option>
	                <option value="Car Purchase/Repairs">Car Purchase/Repairs</option>
	                <option value="Debt Consolidation">Debt Consolidation</option>
	                <option value="Expand Business">Expand Business</option>
	                <option value="Fashion Goods">Fashion Goods</option>
	                <option value="Funeral Expenses">Funeral Expenses</option>
	                <option value="Home Improvements">Home Improvements</option>
	                <option value="Medical Expenses">Medical Expenses</option>
	                <option value="Personal Emergency">Personal Emergency</option>
	                <option value="Portable Goods">Portable Goods</option>
	                <option value="Rent">Rent</option>
	                <option value="School Fees/Educational Expenses">School Fees/Educational Expenses</option>
	                <option value="Start a Business">Start a Business</option>
	                <option value="Travel/Holiday">Travel/Holiday</option>
	                <option value="Pilgrimage">Pilgrimage</option>
	                <option value="Wedding/Event">Wedding/Event</option>
	                <option value="Birthday">Birthday</option>
	                <option value="Other">Other</option>
                </select>
				<span style="color: orange;"><b>TELL US WHY THE CUSTOMER NEED LOAN!!</b></span>
          			 </div>
				 </div>
			  
			<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Account Officer</label>
                  <div class="col-sm-10">
<?php
$tid = $_SESSION['tid'];
$sele = mysqli_query($link, "SELECT * from user WHERE id = '$tid'") or die (mysqli_error($link));
while($row = mysqli_fetch_array($sele))
{
?>
                  <input name="agent" type="text" class="form-control" value="<?php echo $row['name']; ?>" readonly>
<?php } ?>
                  </div>
                  </div>
<hr>	
<div class="bg-orange">&nbsp;GUARANTOR INFORMATION</div>
<hr>
				  
			<div class="form-group">
				<label for="" class="col-sm-2 control-label" style="color:blue;">Gurantor's Passport</label>
				<div class="col-sm-10">
  		  		<input type='file' name="image" onChange="readURL(this);">
       			 <img id="blah"  src="../avtar/user2.png" alt="Image Here" height="100" width="100"/>
			</div>
			</div>
			
			<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Relationship</label>
                  <div class="col-sm-10">
                  <input name="grela" type="text" class="form-control" placeholder="Relationship" required>
                  </div>
                  </div>
			
			<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Guarantor's Name</label>
                  <div class="col-sm-10">
                  <input name="g_name" type="text" class="form-control" required placeholder = "Guarantor's Name">
                  </div>
                  </div>
				  
				  <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Guarantor's Phone Number</label>
                  <div class="col-sm-10">
                  <input name="g_phone" type="text" class="form-control" required placeholder = "Guarantor's Mobile Number">
				  <span style="color: orange;"> <b>Make sure you include country code but do not put spaces, or characters </b>in mobile othermise you won't be able to send SMS to this mobile </span><br>
                  </div>
			  </div>
				 
				 <div class="form-group">
                  	<label for="" class="col-sm-2 control-label" style="color:blue;">Guarantor's Address</label>
                  	<div class="col-sm-10">
					<textarea name="gaddress"  class="form-control" rows="2" cols="80"></textarea>
           			 </div>
          	</div> 
			
                  <input name="status" type="hidden" class="form-control" value="Pending" readonly="readonly">

				  
				  <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Teller By</label>
                  <div class="col-sm-10">
<?php
$tid = $_SESSION['tid'];
$sele = mysqli_query($link, "SELECT * from user WHERE id = '$tid'") or die (mysqli_error($link));
while($row = mysqli_fetch_array($sele))
{
?>
                  <input name="teller" type="text" class="form-control" value="<?php echo $row['name']; ?>" readonly>
<?php } ?>
                  </div>
                  </div>

				  
			 </div>
			 
			  <div align="right">
              <div class="box-footer">
                				<button type="reset" class="btn bg-orange"><i class="fa fa-times">&nbsp;Reset</i></button>
                				<button name="save_loan" type="submit" class="btn bg-blue"><i class="fa fa-save">&nbsp;Proceed</i></button>

              </div>
			  </div>
			  </form>
			  

           
</div>	
</div>
</div>
</div>