<?php
include ("../config/session.php");
$PostType = $_GET['PostType'];

$myaltcall = mysqli_query($link, "SELECT * FROM member_settings WHERE companyid = '$bbranchid'");
$myaltrow = mysqli_fetch_array($myaltcall);

if($PostType == "Individual" || $PostType == "Group")
{
?>


<hr>	
<div class="bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>">&nbsp;GUARANTOR INFORMATION</div>
<hr>
				  
			<div class="form-group">
				<label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Gurantor's Passport</label>
				<div class="col-sm-10">
  		  		<input type='file' name="image" onChange="readURL(this);" />
       			 <img id="blah"  src="../avtar/user2.png" alt="Image Here" height="100" width="100"/>
			</div>
			</div>
			
			
			<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Relationship</label>
                  <div class="col-sm-10">
                  <input name="grela" type="text" class="form-control" placeholder="Relationship" required>
                  </div>
                  </div>
			
			<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Guarantor's Name</label>
                  <div class="col-sm-10">
                  <input name="g_name" type="text" class="form-control" required placeholder = "Guarantor's Name">
                  </div>
                  </div>
				  
				  <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Guarantor's Phone Number</label>
                  <div class="col-sm-10">
                  <input name="g_phone" type="text" class="form-control" required placeholder = "Guarantor's Mobile Number">
				  <span style="color: <?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>;"> <b>Make sure you include country code but do not put spaces, or characters </b>in mobile othermise you won't be able to send SMS to this mobile </span><br>
                  </div>
                  </div>
                  
         <div class="form-group">
              <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Guarantor's BVN</label>
              <div class="col-sm-10">
				  <span style="color: <?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>;"> Note that BVN Verification cost <b style="color: <?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;"><?php echo $fetchsys_config['currency'].number_format($fetchsys_config['bvn_fee'],2,'.',','); ?> per request</b> which is routed through your Super Wallet (Nigeria Account Only). </span>
              <input name="g_unumber" type="text" id="unumber" onkeydown="loaddata2();" class="form-control" placeholder="BVN Number Here" maxlength="11">
			   <div id="bvn3"></div><br>
              </div>
              </div>
				 
				 <div class="form-group">
                  	<label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Guarantor's Address</label>
                  	<div class="col-sm-10">
					<textarea name="gaddress"  class="form-control" rows="4" cols="80"></textarea>
           			 </div>
          	</div>
			 

<?php
}
elseif($PostType == "Purchase")
{
?>


<hr>	
<div class="bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>">&nbsp;PRODUCT INFORMATION</div>
<hr>
				  
			<div class="form-group">
				<label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Product Invoice</label>
				<div class="col-sm-10">
  		  		<input type='file' name="image" class="alert bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>">
			</div>
			</div>
			
			<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Product Name</label>
                  <div class="col-sm-10">
                  <input name="grela" type="text" class="form-control" placeholder="Enter Product Name" required>
                  </div>
                  </div>
			
			<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Model Number</label>
                  <div class="col-sm-10">
                  <input name="g_name" type="text" class="form-control" required placeholder = "Enter Model Number / Engine Number">
                  </div>
                  </div>

            <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Serial Number</label>
                  <div class="col-sm-10">
                  <input name="g_phone" type="text" class="form-control" required placeholder = "Enter Serial Number">
                  </div>
                  </div>
                  
            <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Quantity</label>
                  <div class="col-sm-10">
                  <input name="qty" type="number" class="form-control" required placeholder = "Enter Quantity of Products" required>
                  </div>
                  </div>

            <div class="form-group">
                  	<label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Other Description</label>
                  	<div class="col-sm-10">
					<textarea name="gaddress"  class="form-control" rows="2" cols="80"></textarea>
           			 </div>
          	</div>


<?php
}
else{
	//NOTHING
}
?>