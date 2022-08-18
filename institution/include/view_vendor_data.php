<div class="box">
        
		 <div class="box-body">
			<div class="panel panel-success">
            <div class="panel-heading bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">
            <h3 class="panel-title"><i class="fa fa-user"></i> View Vendor Information for Update</h3>
            </div>
             <div class="box-body">

<?php
$id = $_GET['id'];
$select = mysqli_query($link, "SELECT * FROM user WHERE userid = '$id'") or die (mysqli_error($link));
while($rows = mysqli_fetch_array($select))
{
	$vendorid = $rows['branchid'];
	$search_maintenance = mysqli_query($link, "SELECT * FROM maintenance_history WHERE company_id = '$vendorid'");
	$fetch_maintenance = mysqli_fetch_array($search_maintenance);
  
    $search_vend = mysqli_query($link, "SELECT * FROM vendor_reg WHERE companyid = '$vendorid'");
    $fetch_vend = mysqli_fetch_array($search_vend);
?>			 
               
			 <form class="form-horizontal" method="post" enctype="multipart/form-data" action="process_vend.php?id=<?php echo $id; ?>">
             <div class="box-body">
               
               <input name="vendorID" type="hidden" class="form-control" value="<?php echo $fetch_vend['companyid']; ?>">
				
			<div class="form-group">
            <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Company Logo</label>
			<div class="col-sm-10">
  		  		<input type='file' name="image" onChange="readURL(this);" />
       			<img id="blah"  src="<?php echo $fetchsys_config['file_baseurl'].$fetch_vend['logo']; ?>" alt="Image Here" height="100" width="100"/>
			</div>
			</div>
			
			<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Company Name</label>
                  <div class="col-sm-10">
                  <input name="cname" type="text" class="form-control" value="<?php echo $fetch_vend['cname']; ?>" placeholder="Company Name" required>
                  </div>
                  </div>
                  
      <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Company Type</label>
                  <div class="col-sm-10">
				            <select name="ctype"  class="form-control select2" required>
										  <option value="<?php echo $fetch_vend['ctype']; ?>" selected><?php echo $fetch_vend['ctype']; ?></option>
                      <option value="Asset Management">Asset Management</option>
                      <option value="Takaful Insurance">Takaful Insurance</option>
                      <option value="HMO">HMO</option>
                      <option value="Halal Investment">Halal Investment</option>
                      <option value="Pension Finance">Pension Finance</option>
                      <option value="Mortgage">Mortgage</option>
                      <option value="Pension">Pension</option>
                      <option value="Onlending Firm">Onlending Firm</option>
										</select>                 
									 </div>
                 					 </div>
									  
			<div class="form-group">
                  	<label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Company Description</label>
                  	<div class="col-sm-10">
					<textarea name="cdesc"  class="form-control" rows="2" cols="80" required><?php echo $fetch_vend['cdesc']; ?></textarea>
           			 </div>
          </div>
          
    <div class="form-group">
                  	<label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Company Address</label>
                  	<div class="col-sm-10">
					<textarea name="caddrs"  class="form-control" id="autocomplete1" onFocus="geolocate()" rows="2" cols="80" required><?php echo $fetch_vend['caddrs']; ?></textarea>
           			 </div>
          </div>
				  
				  
		<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Company Email</label>
                  <div class="col-sm-10">
                  <input type="email" name="cemail" type="text" id="vemail" onkeyup="veryEmail();" value="<?php echo $fetch_vend['cemail']; ?>" class="form-control" placeholder="Company Email Address" required>
                  <div id="myvemail"></div>
                  </div>
                  </div>
				  
		<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Company Phone</label>
                  <div class="col-sm-10">
                  <input name="cphone" type="text" class="form-control" id="vphone" onkeyup="veryPhone();" value="<?php echo $fetch_vend['cphone']; ?>" placeholder="Company Phone" required>
                  <div id="myvphone"></div>
                  </div>
                  </div>
				  
		<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Currency</label>
                  <div class="col-sm-10">
				            <select name="currency"  class="form-control select2">
										  <option value="<?php echo $fetch_vend['currency']; ?>" selected><?php echo $fetch_vend['currency']; ?></option>
                      <option value="NGN">NGN</option>
                      <option value="USD">USD</option>
					  <option value="GHS">GHS</option>
					  <option value="KES">KES</option>
					  <option value="UGX">UGX</option>
					  <option value="TZS">TZS</option>
										</select>                 
									 </div>
                 					 </div>
									  
<hr>	
<div class="bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>">&nbsp;COMMISSION ACCOUNT</div>
<hr>
                 					 
        <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Halal Subaccount</label>
                  <div class="col-sm-10">
                  <input name="m_subaccount" type="text" class="form-control" value="<?php echo $fetch_vend['m_subaccount']; ?>" placeholder="Merchant Subaccount" required>
                  </div>
                  </div>


<hr>	
<div class="bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>">&nbsp;SETTLEMENT BANK</div>
<hr>
                 					 
        <div class="form-group">
              <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Account Number</label>
              <div class="col-sm-10">
                  <input name="acct_no" type="text" class="form-control" value="<?php echo $fetch_vend['account_number']; ?>" placeholder="Enter Settlement Bank Account Number" required>
              </div>
       </div>

       <div class="form-group">
              <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Bank Name</label>
              <div class="col-sm-10">
                  <input name="bankname" type="text" class="form-control" value="<?php echo $fetch_vend['bankname']; ?>" placeholder="Enter Settlement Bank Name" required>
              </div>
       </div>


<!--
<hr>	
<div class="bg-<?php //echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>">&nbsp;MAINTENANCE SETTINGS</div>
<hr>

			<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Billing Type</label>
                  <div class="col-sm-10">
                  <select name="billtype" class="form-control select2" required style="width:100%">
           <option value="<?php //echo $fetch_maintenance['billing_type']; ?>" selected><?php //echo $fetch_maintenance['billing_type']; ?></option>
           <option value="PAYG">PAYG</option>
           <option value="Hybrid">Hybrid</option>
          </select>
                  </div>
                  </div>
                  
<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php //echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Loan Booking</label>
                  <div class="col-sm-10">
                  <input name="lbooking" type="text" class="form-control" value="<?php //echo $fetch_maintenance['loan_booking']; ?>" placeholder="Maintenance Fee per Loan Booking" required>
                  </div>
                  </div>
                  
<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php //echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">ROI Commision Type</label>
                  <div class="col-sm-10">
                  <select name="tcharges_type" class="form-control select2" required style="width:100%">
           <option value="<?php //echo $fetch_maintenance['tcharges_type']; ?>" selected><?php //echo $fetch_maintenance['tcharges_type']; ?></option>
           <option value="flat">flat</option>
           <option value="percentage">percentage</option>
          </select>
                  </div>
                  </div>

 <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php //echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Commission Charges</label>
                  <div class="col-sm-10">
                  <input name="t_charges" type="text" class="form-control" value="<?php //echo $fetch_maintenance['t_charges']; ?>" placeholder="Commission Charges" required>
                  </div>
                  </div>

<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php //echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Capped Amount</label>
                  <div class="col-sm-10">
                  <input name="capped_amt" type="text" class="form-control" value="<?php //echo $fetch_maintenance['capped_amt']; ?>" placeholder="Capped Amount for Commission Charges" required>
                  </div>
                  </div>
-->
				  
<hr>	
<div class="bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>">&nbsp;SECURITY INFORMATION</div>
<hr>	
					
	       <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Username</label>
                  <div class="col-sm-10">
                  <input name="cusername" type="text" class="form-control" id="vusername" onkeyup="veryUsername();" value="<?php echo $fetch_vend['cusername']; ?>" placeholder="Username" required>
                  <div id="myusername"></div>
                  </div>
                  </div>
                  
              <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Password</label>
                  <div class="col-sm-10">
                  <input name="password" type="password" class="form-control" value="<?php echo base64_decode($fetch_vend['cpassword']); ?>" placeholder="Password" required>
                  </div>
                  </div>
				 
				<!-- 
				   <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Rave Secret Key</label>
                  <div class="col-sm-10">
                  <input name="rave_skey" type="text" class="form-control" value="<?php echo $fetch_vend['rave_secret_key']; ?>" placeholder="Rave Secret Key" readonly>
                  </div>
                  </div>
				  
				    <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Rave Public Key</label>
                  <div class="col-sm-10">
                  <input name="rave_pkey" type="text" class="form-control" value="<?php echo $fetch_vend['rave_public_key']; ?>" placeholder="Rave Public Key" readonly>
                  </div>
                  </div>
                  
           <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Rave Status</label>
                  <div class="col-sm-10">
				            <select name="rave_status"  class="form-control select2" readonly>
										  <option value="<?php echo $fetch_vend['rave_status']; ?>" selected><?php echo $fetch_vend['rave_status']; ?></option>
                      <option value="Enabled">Enable</option>
                      <option value="Disabled">Disable</option>
										</select>                 
									 </div>
                 					 </div>
                 					 
                -->
			  
			 </div>
			 
			  <div align="right">
              <div class="box-footer">
                				<button name="vendor" type="submit" class="btn bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>"><i class="fa fa-save">&nbsp;Save</i></button>

              </div>
			  </div>
			  
			 </form> 
<?php } ?>

</div>
</div>
</div>
</div>