<div class="row">
	      <section class="content">
        
       <div class="box box-default">
           <div class="box-body">
             <div class="table-responsive"> 
            <div class="box-body">

            <div class="col-md-14">
            <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
			
			<?php
			$call = mysqli_query($link, "SELECT * FROM systemset");
			$row = mysqli_fetch_assoc($call);
			?>

             <li <?php echo ($_GET['tab'] == 'tab_1') ? "class='active'" : ''; ?>><a href="system_set.php?id=<?php echo $_SESSION['tid']; ?>&&mid=NDEx&&tab=tab_1">Basic Profile</a></li>

              <li <?php echo ($_GET['tab'] == 'tab_2') ? "class='active'" : ''; ?>><a href="system_set.php?id=<?php echo $_SESSION['tid']; ?>&&mid=NDEx&&tab=tab_2">Maintenance Switching</a></li>
              
              <li <?php echo ($_GET['tab'] == 'tab_3') ? "class='active'" : ''; ?>><a href="system_set.php?id=<?php echo $_SESSION['tid']; ?>&&mid=NDEx&&tab=tab_3">Global Charges</a></li>

			  <li <?php echo ($_GET['tab'] == 'tab_4') ? "class='active'" : ''; ?>><a href="system_set.php?id=<?php echo $_SESSION['tid']; ?>&&mid=NDEx&&tab=tab_4">Global Commission</a></li>
			  
			  <li <?php echo ($_GET['tab'] == 'tab_5') ? "class='active'" : ''; ?>><a href="system_set.php?id=<?php echo $_SESSION['tid']; ?>&&mid=NDEx&&tab=tab_5">Integration Credentials</a></li>
              </ul>
             <div class="tab-content">

<?php
if(isset($_GET['tab']) == true)
{
  $tab = $_GET['tab'];
  if($tab == 'tab_1')
  {
  ?>
             <div class="tab-pane <?php echo ($_GET['tab'] == 'tab_1') ? 'active' : ''; ?>" id="tab_1">

             <div class="box-body">
           
			 <form class="form-horizontal" method="post" enctype="multipart/form-data">

<?php
if(isset($_POST['saveBasic'])){

	$id = mysqli_real_escape_string($link, $_POST['sysid']);
	$fname = mysqli_real_escape_string($link, $_POST['fname']);
	$number = mysqli_real_escape_string($link, $_POST['number']);
	$email = mysqli_real_escape_string($link, $_POST['email']);
	$pemail = mysqli_real_escape_string($link, $_POST['pemail']);
	$title = mysqli_real_escape_string($link, $_POST['title']);
	$footer = mysqli_real_escape_string($link, $_POST['footer']);
	$abb = mysqli_real_escape_string($link, $_POST['abb']);
	$currency = mysqli_real_escape_string($link, $_POST['currency']);
	$file_baseurl = mysqli_real_escape_string($link, $_POST['file_baseurl']);
	$fax = mysqli_real_escape_string($link, $_POST['fax']);
	$website = mysqli_real_escape_string($link, $_POST['website']);
	$wallet_prefound_amt = mysqli_real_escape_string($link, $_POST['wallet_prefound_amt']);
	$verveCardPrefundAmt = mysqli_real_escape_string($link, $_POST['verveCardPrefundAmt']);
	$theme_color = mysqli_real_escape_string($link, $_POST['theme_color']);
	$demo_type = mysqli_real_escape_string($link, $_POST['demo_type']);
	$demo_rate = mysqli_real_escape_string($link, $_POST['demo_rate']);

	//this handles uploading of rentals image
	$image = addslashes(file_get_contents($_FILES['image']['tmp_name']));
	$image2 = addslashes(file_get_contents($_FILES['image2']['tmp_name']));

	if($image == "" && $image2 == "")
	{
		mysqli_query($link,"UPDATE systemset SET name='$fname',mobile='$number',email='$email',paypal_email='$pemail',title='$title',abb='$abb',currency='$currency',file_baseurl='$file_baseurl',fax='$fax',website='$website',footer='$footer',demo_type='$demo_type',demo_rate='$demo_rate',wallet_prefound_amt='$wallet_prefound_amt',verveCardPrefundAmt='$verveCardPrefundAmt' WHERE sysid ='$id'")or die(mysqli_error()); 
	
		echo "<script>alert('System Configured Successfully!'); </script>";
		echo "<script>window.location='system_set.php?id=".$_SESSION['tid']."&&mid=".base64_encode("411")."&&tab=tab_1';</script>";
	
	}
	elseif($image != ""){

		$target_dir = "../img/";
		$target_file = $target_dir.basename($_FILES["image"]["name"]);
		$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
		$check = getimagesize($_FILES["image"]["tmp_name"]);
		if($check == false)
		{
			echo "<p style='font-size:24px; color:orange'>Invalid file type</p>";
		}
		elseif($_FILES["image"]["size"] > 10000000)
		{
			echo "<p style='font-size:24px; color:orange'>Image must not more than 500KB!</p>";
		}
		elseif($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif")
		{
			echo "<p style='font-size:24px; color:orange'>Sorry, only JPG, JPEG, PNG & GIF Files are allowed for the System Logo.</p>";
		}
		else{
			$sourcepath = $_FILES["image"]["tmp_name"];
			$targetpath = "../img/" . $_FILES["image"]["name"];
			$myfile = $_FILES["image"]["name"];
			move_uploaded_file($sourcepath,$targetpath);

			mysqli_query($link,"UPDATE systemset SET name='$fname',mobile='$number',email='$email',paypal_email='$pemail',title='$title',abb='$abb',currency='$currency',file_baseurl='$file_baseurl',fax='$fax',website='$website',footer='$footer',demo_type='$demo_type',demo_rate='$demo_rate',wallet_prefound_amt='$wallet_prefound_amt',verveCardPrefundAmt='$verveCardPrefundAmt',image='$myfile' WHERE sysid ='$id'")or die(mysqli_error()); 
	
			echo "<script>alert('System Configured Successfully!'); </script>";
			echo "<script>window.location='system_set.php?id=".$_SESSION['tid']."&&mid=".base64_encode("411")."&&tab=tab_1';</script>";
		}

	}
	else{

		$target_dir2 = "../img/";
		$target_file2 = $target_dir2.basename($_FILES["image2"]["name"]);
		$imageFileType2 = pathinfo($target_file2,PATHINFO_EXTENSION);
		$check2 = getimagesize($_FILES["image2"]["tmp_name"]);
		if($check2 == false)
		{
			echo "<p style='font-size:24px; color:orange'>Invalid file type</p>";
		}
		elseif($_FILES["image2"]["size"] > 500000)
		{
			echo "<p style='font-size:24px; color:orange'>Image must not more than 500KB!</p>";
		}
		elseif($imageFileType2 != "jpg" && $imageFileType2 != "png" && $imageFileType2 != "jpeg" && $imageFileType2 != "gif")
		{
			echo "<p style='font-size:24px; color:orange'>Sorry, only JPG, JPEG, PNG & GIF Files are allowed for the Stamp.</p>";
		}
		else{
			$sourcepath2 = $_FILES["image2"]["tmp_name"];
			$targetpath2 = "../img/" . $_FILES["image2"]["name"];
			move_uploaded_file($sourcepath2,$targetpath2);

			$lbackg = $_FILES["image2"]["name"];
			mysqli_query($link,"UPDATE systemset SET name='$fname',mobile='$number',email='$email',paypal_email='$pemail',title='$title',abb='$abb',currency='$currency',file_baseurl='$file_baseurl',fax='$fax',website='$website',footer='$footer',demo_type='$demo_type',demo_rate='$demo_rate',wallet_prefound_amt='$wallet_prefound_amt',verveCardPrefundAmt='$verveCardPrefundAmt',image='$lbackg' WHERE sysid ='$id'")or die(mysqli_error()); 
			
			echo "<script>alert('System Configured Successfully!'); </script>";
			echo "<script>window.location='system_set.php?id=".$_SESSION['tid']."&&mid=".base64_encode("411")."&&tab=tab_1';</script>";
		}

	}

}
?>

             <div class="box-body">
			
			 <input type="hidden" value="<?php echo $row ['sysid']; ?>" name="sysid">

			 <div class="form-group">
            <label for="" class="col-sm-3 control-label" style="color:blue;">Company Logo</label>
			<div class="col-sm-6">
  		  		<input type='file' name="image" onChange="readURL(this);" class="alert bg-orange">
       			<img id="blah"  src="<?php echo $row['file_baseurl'].$row['image']; ?>" alt="System Logo Here" height="100" width="100"/>
			</div>
			<label for="" class="col-sm-3 control-label"></label>
			</div>
			
			<div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Company Name</label>
                  <div class="col-sm-6">
                  <input name="fname" type="text" class="form-control" value="<?php echo $row['name']; ?>" required>
				  </div>
				  <label for="" class="col-sm-3 control-label"></label>
                  </div>
				  
		<div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Company Phone</label>
                  <div class="col-sm-6">
                  <input name="number" type="text" class="form-control" value="<?php echo $row ['mobile']; ?>" required>
				  </div>
				  <label for="" class="col-sm-3 control-label"></label>
                  </div>
				  
		<div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Company Email</label>
                  <div class="col-sm-6">
                  <input type="email" name="email" type="text" class="form-control" value="<?php echo $row ['email']; ?>">
				  </div>
				  <label for="" class="col-sm-3 control-label"></label>
                  </div>
				  
				  <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Company Title</label>
                  <div class="col-sm-6">
                  <input type="text" name="title" type="text" class="form-control" value="<?php echo $row ['title']; ?>">
				  </div>
				  <label for="" class="col-sm-3 control-label"></label>
                  </div>
				  
				  <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Company Footer</label>
                  <div class="col-sm-6">
                      <textarea name="footer" class="form-control" rows="2" cols="80" ><?php echo $row ['footer']; ?></textarea>
				  </div>
				  <label for="" class="col-sm-3 control-label"></label>
                  </div>
				  
				   <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Company Abbreviation</label>
                  <div class="col-sm-6">
                  <input type="text" name="abb" type="text" class="form-control" value="<?php echo $row ['abb']; ?>">
				  </div>
				  <label for="" class="col-sm-3 control-label"></label>
                  </div>

        <div class="form-group">
		                  <label for="" class="col-sm-3 control-label" style="color:blue;">Currency</label>
		                  <div class="col-sm-6">
						<select name="currency"  class="form-control" required>
							<option value="<?php echo $row['currency']; ?>"><?php echo $row['currency']; ?></option>
							<option value="NGN">NGN</option>
							<option value="USD">USD</option>
							<option value="EUR">EUR</option>
							<option value="GBP">GBP</option>
							<option value="UGX">UGX</option>
							<option value="TZS">TZS</option>
							<option value="GHS">GHS</option>
							<option value="KES">KES</option>
							<option value="ZAR">ZAR</option>
						</select>                 
						</div>
						<label for="" class="col-sm-3 control-label"></label>
		                </div>

			<div class="form-group">
                          <label for="" class="col-sm-3 control-label" style="color:blue;">File BaseUrl</label>
                          <div class="col-sm-6">
                          <input name="file_baseurl" type="text" class="form-control" value="<?php echo $row['file_baseurl']; ?>" required >
						  </div>
						  <label for="" class="col-sm-3 control-label"></label>
						</div>
					
			<div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">SMS Price</label>
                  <div class="col-sm-6">
                  <input name="fax" type="text" class="form-control" value="<?php echo $row ['fax']; ?>"required >
				  </div>
				  <label for="" class="col-sm-3 control-label"></label>
                  </div>

						<div class="form-group">
						<label for="" class="col-sm-3 control-label" style="color: blue;">Login Background</label>
						<div class="col-sm-6">
							<input type='file' name="image2" class="alert bg-orange">
							<img src="<?php echo $row['file_baseurl'].$row['lbackg']; ?>" alt="Background Here" height="100" width="100"/>
						</div>
						<label for="" class="col-sm-3 control-label"></label>
						</div>
						
						<div class="form-group">
		                  <label for="" class="col-sm-3 control-label" style="color:blue;">Theme Color</label>
		                  <div class="col-sm-6">
						<select name="theme_color"  class="form-control" required>
							<option value="<?php echo $row['theme_color']; ?>"><?php echo $row['theme_color']; ?></option>
							<option value="black">Black</option>
							<option value="blue">Blue</option>
							<option value="green">Green</option>
							<option value="red">Red</option>
							<option value="yellow">Yellow</option>
						</select>                 
						</div>
						<label for="" class="col-sm-3 control-label"></label>
		                </div>
		                
		                <div class="form-group">
		                  <label for="" class="col-sm-3 control-label" style="color:blue;">Demo Type</label>
		                  <div class="col-sm-6">
						<select name="demo_type"  class="form-control" required>
							<option value="<?php echo $row['demo_type']; ?>"><?php echo $row['demo_type']; ?></option>
							<option value="day">Day</option>
							<option value="month">Month</option>
						</select>                 
						</div>
						<label for="" class="col-sm-3 control-label"></label>
		                </div>
		                
		                <div class="form-group">
		                  <label for="" class="col-sm-3 control-label" style="color:blue;">Demo Rate</label>
		                  <div class="col-sm-6">
    						<input name="demo_rate" type="text" class="form-control" value="<?php echo $row ['demo_rate']; ?>" required>            
						</div>
						<label for="" class="col-sm-3 control-label"></label>
		                </div>
                        
                        <div class="form-group">
                          <label for="" class="col-sm-3 control-label" style="color:blue;">Wallet Prefund Amount</label>
                          <div class="col-sm-6">
                          <input name="wallet_prefound_amt" type="text" class="form-control" value="<?php echo $row ['wallet_prefound_amt']; ?>" required >
						  </div>
						  <label for="" class="col-sm-3 control-label"></label>
						</div>

						<div class="form-group">
						<label for="" class="col-sm-3 control-label" style="color:blue;">Card Prefund Amount</label>
						<div class="col-sm-6">
						<input name="verveCardPrefundAmt" type="text" class="form-control" value="<?php echo $row ['verveCardPrefundAmt']; ?>"required >
						</div>
						<label for="" class="col-sm-3 control-label"></label>
						</div>
						

				</div>
				
					<div class="form-group" align="right">
						<label for="" class="col-sm-3 control-label" style="color:blue;"></label>
						<div class="col-sm-6">
							<button name="saveBasic" type="submit" class="btn bg-blue"><i class="fa fa-save">&nbsp;Save</i></button>
						</div>
						<label for="" class="col-sm-3 control-label"></label>
					</div>    
      
       </form>  

      </div>
    </div>
      <!-- /.tab-pane -->
  

  <?php
  }
  elseif($tab == 'tab_2')
  {
  ?> 

      <div class="tab-pane <?php echo ($_GET['tab'] == 'tab_2') ? 'active' : ''; ?>" id="tab_2">

        <div class="box-body">

          <form class="form-horizontal" method="post" enctype="multipart/form-data">
<?php
if(isset($_POST['saveMaintenance'])){

	$id = mysqli_real_escape_string($link, $_POST['sysid']);
	$maintenance_mode = mysqli_real_escape_string($link, $_POST['maintenance_mode']);
	$dfrom = mysqli_real_escape_string($link, $_POST['dfrom']);
	$dto = mysqli_real_escape_string($link, $_POST['dto']);

	mysqli_query($link,"UPDATE systemset SET maintenance_mode='$maintenance_mode',mt_dfrom='$dfrom',mt_dto='$dto' WHERE sysid ='$id'")or die(mysqli_error()); 
	
	echo "<script>alert('Maintenance Settings Updated Successfully!'); </script>";
	echo "<script>window.location='system_set.php?id=".$_SESSION['tid']."&&mid=".base64_encode("411")."&&tab=tab_2';</script>";

}
?>
             <div class="box-body">
			
			 <input type="hidden" value="<?php echo $row ['sysid']; ?>" name="sysid">

			<div class="form-group">
				<label for="" class="col-sm-3 control-label" style="color:blue;">Mode</label>
                <div class="col-sm-6">
                    <select name="maintenance_mode"  class="form-control" required>
        				<option value="<?php echo $row ['maintenance_mode']; ?>" selected><?php echo $row ['maintenance_mode']; ?></option>
        				<option value="ON">ON</option>
        				<option value="OFF">OFF</option>
        			</select>
				</div>
				<label for="" class="col-sm-3 control-label"></label>
			</div>
			
			<div class="form-group">
				<label for="" class="col-sm-3 control-label" style="color:blue;">From</label>
                <div class="col-sm-6">
                    <input name="dfrom" type="date" class="form-control" value="<?php echo $row ['mt_dfrom']; ?>" placeholder="To Date: 2018-05-24" required>
            		<span style="color: orange;">Date should be in this format: 2018-05-24</span>
				</div>
				<label for="" class="col-sm-3 control-label"></label>
			</div>
			
			<div class="form-group">
				<label for="" class="col-sm-3 control-label" style="color:blue;">To</label>
                <div class="col-sm-6">
                	<input name="dto" type="date" class="form-control" value="<?php echo $row ['mt_dto']; ?>" placeholder="To Date: 2018-05-24" required>
            		<span style="color: orange;">Date should be in this format: 2018-05-24</span>
				</div>
				<label for="" class="col-sm-3 control-label"></label>
        	</div>


       </div>
       
        <div class="form-group" align="right">
            <label for="" class="col-sm-3 control-label" style="color:blue;"></label>
            <div class="col-sm-6">
                <button name="saveMaintenance" type="submit" class="btn bg-blue"><i class="fa fa-save">&nbsp;Save</i></button>
            </div>
            <label for="" class="col-sm-3 control-label"></label>
      </div>    
      
       </form> 

      </div>
    </div>
    

  <?php
  }
  elseif($tab == 'tab_3')
  {
  ?> 

      <div class="tab-pane <?php echo ($_GET['tab'] == 'tab_3') ? 'active' : ''; ?>" id="tab_3">

        <div class="box-body">

          <form class="form-horizontal" method="post" enctype="multipart/form-data">

<?php
if(isset($_POST['saveCharges'])){

	$id = mysqli_real_escape_string($link, $_POST['sysid']);
	$bvn_fee = mysqli_real_escape_string($link, $_POST['bvn_fee']);
	$subaccount_charges = mysqli_real_escape_string($link, $_POST['subaccount_charges']);
	$auth_charges = mysqli_real_escape_string($link, $_POST['auth_charges']);
	$transfer_charges = mysqli_real_escape_string($link, $_POST['transfer_charges']);
	$localpayment_charges = mysqli_real_escape_string($link, $_POST['localpayment_charges']);
	$capped_amount = mysqli_real_escape_string($link, $_POST['capped_amount']);
	$intlpayment_charges = mysqli_real_escape_string($link, $_POST['intlpayment_charges']);
	$vat_rate = mysqli_real_escape_string($link, $_POST['vat_rate']);
	$plan_upgrade = mysqli_real_escape_string($link, $_POST['plan_upgrade']);
	$report_charges = mysqli_real_escape_string($link, $_POST['report_charges']);
	$transferToCardCharges = mysqli_real_escape_string($link, $_POST['transferToCardCharges']);
	$verveCardLinkingFee = mysqli_real_escape_string($link, $_POST['verveCardLinkingFee']);

	mysqli_query($link,"UPDATE systemset SET bvn_fee='$bvn_fee',subaccount_charges='$subaccount_charges',auth_charges='$auth_charges',transfer_charges='$transfer_charges',localpayment_charges='$localpayment_charges',capped_amount='$capped_amount',intlpayment_charges='$intlpayment_charges',vat_rate='$vat_rate',plan_upgrade='$plan_upgrade',report_charges='$report_charges',transferToCardCharges='$transferToCardCharges',verveCardLinkingFee='$verveCardLinkingFee' WHERE sysid ='$id'")or die(mysqli_error()); 
	
	echo "<script>alert('Charges Updated Successfully!'); </script>";
	echo "<script>window.location='system_set.php?id=".$_SESSION['tid']."&&mid=".base64_encode("411")."&&tab=tab_3';</script>";

}
?>

             <div class="box-body">

			 	<input type="hidden" value="<?php echo $row ['sysid']; ?>" name="sysid">

				<div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">BVN Charges</label>
                  <div class="col-sm-6">
                  <input name="bvn_fee" type="text" class="form-control" value="<?php echo $row['bvn_fee']; ?>"required >
				  </div>
				  <label for="" class="col-sm-3 control-label"></label>
                </div>

                <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Subaccount Charges (%)</label>
                  <div class="col-sm-6">
                  <input name="subaccount_charges" type="text" class="form-control" value="<?php echo $row['subaccount_charges']; ?>"required >
				  </div>
				  <label for="" class="col-sm-3 control-label"></label>
                </div>

                <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Authorization Charges</label>
                  <div class="col-sm-6">
                  <input name="auth_charges" type="text" class="form-control" value="<?php echo $row['auth_charges']; ?>"required >
				  </div>
				  <label for="" class="col-sm-3 control-label"></label>
                </div>
                
                <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Bank Transfer Charges</label>
                  <div class="col-sm-6">
                  <input name="transfer_charges" type="text" class="form-control" value="<?php echo $row['transfer_charges']; ?>"required >
				  </div>
				  <label for="" class="col-sm-3 control-label"></label>
                </div>
                
                <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Local Payment (%)</label>
                  <div class="col-sm-6">
                  <input name="localpayment_charges" type="text" class="form-control" value="<?php echo $row['localpayment_charges']; ?>"required >
				  </div>
				  <label for="" class="col-sm-3 control-label"></label>
                </div>
                
                <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Capped Amount</label>
                  <div class="col-sm-6">
                  <input name="capped_amount" type="text" class="form-control" value="<?php echo $row['capped_amount']; ?>"required >
				  </div>
				  <label for="" class="col-sm-3 control-label"></label>
                </div>
                
                <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">International Payment (%)</label>
                  <div class="col-sm-6">
                  <input name="intlpayment_charges" type="text" class="form-control" value="<?php echo $row['intlpayment_charges']; ?>"required >
				  </div>
				  <label for="" class="col-sm-3 control-label"></label>
                </div>
                
                <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Vat Rate (%)</label>
                  <div class="col-sm-6">
                  <input name="vat_rate" type="text" class="form-control" value="<?php echo $row['vat_rate']; ?>"required >
                  <span style="color: orange;"><i>Vat Rate for Wallet Transfer + transfer charges</i></span>
				  </div>
				  <label for="" class="col-sm-3 control-label"></label>
                </div>
                
                <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Sub. Plan Upgrade</label>
                  <div class="col-sm-6">
                  <input name="plan_upgrade" type="text" class="form-control" value="<?php echo $row['plan_upgrade_amt']; ?>"required >
				  </div>
				  <label for="" class="col-sm-3 control-label"></label>
				</div>
				
				<div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Report Charge</label>
                  <div class="col-sm-6">
                  <input name="report_charges" type="text" class="form-control" value="<?php echo $row['report_charges']; ?>"required >
				  </div>
				  <label for="" class="col-sm-3 control-label"></label>
				</div>
				
				<div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Transfer to Card Charges</label>
                  <div class="col-sm-6">
                  <input name="transferToCardCharges" type="text" class="form-control" value="<?php echo $row['transferToCardCharges']; ?>"required >
				  </div>
				  <label for="" class="col-sm-3 control-label"></label>
				</div>
				
				<div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Card Linking Fee</label>
                  <div class="col-sm-6">
                  <input name="verveCardLinkingFee" type="text" class="form-control" value="<?php echo $row['verveCardLinkingFee']; ?>"required >
				  </div>
				  <label for="" class="col-sm-3 control-label"></label>
				</div>


		</div>
		
			<div class="form-group" align="right">
				<label for="" class="col-sm-3 control-label" style="color:blue;"></label>
				<div class="col-sm-6">
					<button name="saveCharges" type="submit" class="btn bg-blue"><i class="fa fa-save">&nbsp;Save</i></button>
				</div>
				<label for="" class="col-sm-3 control-label"></label>
			</div>   
      
       </form> 

      </div>
    </div>

<?php
}
elseif($tab == 'tab_4')
{
?>

      <div class="tab-pane <?php echo ($_GET['tab'] == 'tab_4') ? 'active' : ''; ?>" id="tab_4">

        <div class="box-body">
           
       <form class="form-horizontal" method="post" enctype="multipart/form-data">

<?php
if(isset($_POST['saveCommission'])){

	$id = mysqli_real_escape_string($link, $_POST['sysid']);
	$bp_commission = mysqli_real_escape_string($link, $_POST['bp_commission']);
	$aggr_co_type = mysqli_real_escape_string($link, $_POST['aggr_co_type']);
	$aggr_co_rate = mysqli_real_escape_string($link, $_POST['aggr_co_rate']);

	mysqli_query($link,"UPDATE systemset SET bp_commission='$bp_commission',aggr_co_type='$aggr_co_type',aggr_co_rate='$aggr_co_rate' WHERE sysid ='$id'")or die(mysqli_error()); 
	
	echo "<script>alert('Commission Updated Successfully!'); </script>";
	echo "<script>window.location='system_set.php?id=".$_SESSION['tid']."&&mid=".base64_encode("411")."&&tab=tab_4';</script>";

}
?>

             <div class="box-body">
				 
			 	<input type="hidden" value="<?php echo $row ['sysid']; ?>" name="sysid">

				<div class="form-group">
		            <label for="" class="col-sm-3 control-label" style="color:blue;">Bill Payment Comm. (%)</label>
		            <div class="col-sm-6">
    					<input name="bp_commission" type="text" class="form-control" value="<?php echo $row ['bp_commission']; ?>" required>            
					</div>
					<label for="" class="col-sm-3 control-label"></label>
		        </div>

				<div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Aggregator Comm. Type:</label>
                  <div class="col-sm-6">
                      <select name="aggr_co_type"  class="form-control" required>
							<option value="<?php echo $row ['aggr_co_type']; ?>" selected><?php echo $row ['aggr_co_type']; ?></option>
							<option value="Flat">Flat</option>
							<option value="Percentage">Percentage</option>
						</select>
				  </div>
				  <label for="" class="col-sm-3 control-label"></label>
                </div>
				
				<div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Aggregator Comm. Rate</label>
                  <div class="col-sm-6">
                  <input name="aggr_co_rate" type="text" class="form-control" value="<?php echo $row ['aggr_co_rate']; ?>"required >
				  </div>
				  <label for="" class="col-sm-3 control-label"></label>
				</div>
				

		</div>
		
		<div class="form-group" align="right">
				<label for="" class="col-sm-3 control-label" style="color:blue;"></label>
				<div class="col-sm-6">
					<button name="saveCommission" type="submit" class="btn bg-blue"><i class="fa fa-save">&nbsp;Save</i></button>
				</div>
				<label for="" class="col-sm-3 control-label"></label>
		</div>
		
       </form> 

      </div>
    </div>

	<?php
}
elseif($tab == 'tab_5')
{
?>

      <div class="tab-pane <?php echo ($_GET['tab'] == 'tab_5') ? 'active' : ''; ?>" id="tab_5">

        <div class="box-body">
           
	   <form class="form-horizontal" method="post" enctype="multipart/form-data">
	   
<?php
if(isset($_POST['saveCredential'])){

	$id = mysqli_real_escape_string($link, $_POST['sysid']);
	$email = mysqli_real_escape_string($link, $_POST['email']);
	$secret_key = mysqli_real_escape_string($link, $_POST['secret_key']);
	$public_key = mysqli_real_escape_string($link, $_POST['public_key']);
	$mo_api_key = mysqli_real_escape_string($link, $_POST['mo_api_key']);
	$mo_secret_key = mysqli_real_escape_string($link, $_POST['mo_secret_key']);
	$mo_contract_code = mysqli_real_escape_string($link, $_POST['mo_contract_code']);
	$mo_status = mysqli_real_escape_string($link, $_POST['mo_status']);
	$bancore_merchant_acctid = mysqli_real_escape_string($link, $_POST['bancore_merchant_acctid']);
	$bancore_merchant_pkey = mysqli_real_escape_string($link, $_POST['bancore_merchant_pkey']);
	$verveAppId = mysqli_real_escape_string($link, $_POST['verveAppId']);
	$verveAppKey = mysqli_real_escape_string($link, $_POST['verveAppKey']);
	$verveSettlementAcctNo = mysqli_real_escape_string($link, $_POST['verveSettlementAcctNo']);
	$verveSettlementAcctName = mysqli_real_escape_string($link, $_POST['verveSettlementAcctName']);
	$verveSettlementBankCode = mysqli_real_escape_string($link, $_POST['verveSettlementBankCode']);
	$walletafrica_skey = mysqli_real_escape_string($link, $_POST['walletafrica_skey']);
	$walletafrica_pkey = mysqli_real_escape_string($link, $_POST['walletafrica_pkey']);
	$wellahealth_clientid = mysqli_real_escape_string($link, $_POST['wellahealth_clientid']);
	$wellahealth_agentcode = mysqli_real_escape_string($link, $_POST['wellahealth_agentcode']);
	$wellahealth_clientsecretkey = mysqli_real_escape_string($link, $_POST['wellahealth_clientsecretkey']);
	$cgate_username = mysqli_real_escape_string($link, $_POST['cgate_username']);
	$cgate_password = mysqli_real_escape_string($link, $_POST['cgate_password']);
	$cgate_mid = mysqli_real_escape_string($link, $_POST['cgate_mid']);
	$onePipeSKey = mysqli_real_escape_string($link, $_POST['onePipeSKey']);
	$onePipeApiKey = mysqli_real_escape_string($link, $_POST['onePipeApiKey']);
	$providusUName = mysqli_real_escape_string($link, $_POST['providusUName']);
	$providusPass = mysqli_real_escape_string($link, $_POST['providusPass']);

	mysqli_query($link,"UPDATE systemset SET paypal_email='$email',secret_key='$secret_key',public_key='$public_key',mo_api_key='$mo_api_key',mo_secret_key='$mo_secret_key',mo_contract_code='$mo_contract_code',mo_status='$mo_status',bancore_merchant_acctid='$bancore_merchant_acctid',bancore_merchant_pkey='$bancore_merchant_pkey',verveAppId='$verveAppId',verveAppKey='$verveAppKey',verveSettlementAcctNo='$verveSettlementAcctNo',verveSettlementAcctName='$verveSettlementAcctName',verveSettlementBankCode='$verveSettlementBankCode',walletafrica_skey='$walletafrica_skey',walletafrica_pkey='$walletafrica_pkey',wellahealth_clientid='$wellahealth_clientid',wellahealth_agentcode='$wellahealth_agentcode',wellahealth_clientsecretkey='$wellahealth_clientsecretkey',cgate_username='$cgate_username',cgate_password='$cgate_password',cgate_mid='$cgate_mid',onePipeSKey='$onePipeSKey',onePipeApiKey='$onePipeApiKey',providusUName='$providusUName',providusPass='$providusPass' WHERE sysid ='$id'")or die(mysqli_error()); 
	
	echo "<script>alert('Configuration Saved Successfully!'); </script>";
	echo "<script>window.location='system_set.php?id=".$_SESSION['tid']."&&mid=".base64_encode("411")."&&tab=tab_5';</script>";

}
?>
	
             <div class="box-body">

			 <input type="hidden" value="<?php echo $row ['sysid']; ?>" name="sysid">

<hr>	
<div class="bg-orange">&nbsp;<b> PAYPAL SETTINGS TO ACCEPT PAYMENT FOR LOAN FROM USER END </b></div>
<hr>

		  		<div class="form-group">
		            <label for="" class="col-sm-3 control-label" style="color:blue;">Paypal Email Address</label>
		            <div class="col-sm-6">
		                <input type="email" name="pemail" type="text" class="form-control" value="<?php echo $row['paypal_email']; ?>">
						<span style="color: orange;"><i>(To Process Loan Disbursment / Campaign Contribution)</i></span>
					</div>
					<label for="" class="col-sm-3 control-label"></label>
		        </div>

<hr>	
<div class="bg-orange">&nbsp;<b> FLUTTERWAVE PAYMENT GATEWAY SETTINGS </b></div>
<hr>
				
				<div class="form-group">
                	<label for="" class="col-sm-3 control-label" style="color:blue;">Secret Key</label>
                  	<div class="col-sm-6">
                  		<input name="secret_key" type="text" class="form-control" value="<?php echo $row ['secret_key']; ?>"required >
					</div>
					<label for="" class="col-sm-3 control-label"></label>
                </div>
				
				<div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Public Key</label>
                  <div class="col-sm-6">
                  <input name="public_key" type="password" class="form-control" value="<?php echo $row ['public_key']; ?>"required >
				  </div>
				  <label for="" class="col-sm-3 control-label"></label>
                </div>
                
<hr>	
<div class="bg-orange">&nbsp;<b> MONNIFY PAYMENT GATEWAY SETTINGS </b></div>
<hr>
                
                <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">API Key / Username:</label>
                  <div class="col-sm-6">
                  <input name="mo_api_key" type="text" class="form-control" value="<?php echo $row ['mo_api_key']; ?>"required >
				  </div>
				  <label for="" class="col-sm-3 control-label"></label>
                </div>
                
                <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Secret Key / Password:</label>
                  <div class="col-sm-6">
                  <input name="mo_secret_key" type="password" class="form-control" value="<?php echo $row ['mo_secret_key']; ?>"required >
				  </div>
				  <label for="" class="col-sm-3 control-label"></label>
                </div>
                
                <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Contract Code:</label>
                  <div class="col-sm-6">
                  <input name="mo_contract_code" type="text" class="form-control" value="<?php echo $row ['mo_contract_code']; ?>"required >
				  </div>
				  <label for="" class="col-sm-3 control-label"></label>
                </div>
                
                <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Status:</label>
                  <div class="col-sm-6">
                      <select name="mo_status"  class="form-control" required>
							<option value="<?php echo $row ['mo_status']; ?>" selected><?php echo $row ['mo_status']; ?></option>
							<option value="NotActive">Not Active</option>
							<option value="Active">Active</option>
						</select>
				  </div>
				  <label for="" class="col-sm-3 control-label"></label>
                </div>
				
<hr>	
<div class="bg-orange">&nbsp;<b> BANCORE MASTERCARD INSUANCE SETTINGS </b></div>
<hr>

				<div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Merchant Account ID:</label>
                  <div class="col-sm-6">
                  <input name="bancore_merchant_acctid" type="text" class="form-control" value="<?php echo $row ['bancore_merchant_acctid']; ?>"required >
				  </div>
				  <label for="" class="col-sm-3 control-label"></label>
                </div>
                
                <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Merchant Private Key:</label>
                  <div class="col-sm-6">
                  <input name="bancore_merchant_pkey" type="password" class="form-control" value="<?php echo $row ['bancore_merchant_pkey']; ?>"required >
				  </div>
				  <label for="" class="col-sm-3 control-label"></label>
				</div>
				
<hr>	
<div class="bg-orange">&nbsp;<b> PROVIDUS BANK VERVE CARD INSUANCE SETTINGS </b></div>
<hr>
				
				<div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">App ID:</label>
                  <div class="col-sm-6">
                  <input name="verveAppId" type="text" class="form-control" value="<?php echo $row ['verveAppId']; ?>"required >
				  </div>
				  <label for="" class="col-sm-3 control-label"></label>
                </div>
                
                <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">App Key:</label>
                  <div class="col-sm-6">
                  <input name="verveAppKey" type="password" class="form-control" value="<?php echo $row ['verveAppKey']; ?>"required >
				  </div>
				  <label for="" class="col-sm-3 control-label"></label>
				</div>

				<div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Settlement Account No:</label>
                  <div class="col-sm-6">
                  <input name="verveSettlementAcctNo" type="text" class="form-control" value="<?php echo $row ['verveSettlementAcctNo']; ?>"required >
				  </div>
				  <label for="" class="col-sm-3 control-label"></label>
				</div>

				<div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Settlement Account Name:</label>
                  <div class="col-sm-6">
                  <input name="verveSettlementAcctName" type="text" class="form-control" value="<?php echo $row ['verveSettlementAcctName']; ?>"required >
				  </div>
				  <label for="" class="col-sm-3 control-label"></label>
				</div>

				<div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Settlement Bank Code:</label>
                  <div class="col-sm-6">
                  <input name="verveSettlementBankCode" type="text" class="form-control" value="<?php echo $row ['verveSettlementBankCode']; ?>"required >
				  </div>
				  <label for="" class="col-sm-3 control-label"></label>
				</div>

<hr>	
<div class="bg-orange">&nbsp;<b> WALLET AFRICA INTEGRATION KEYS </b></div>
<hr>

				<div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Secret Key:</label>
                  <div class="col-sm-6">
                  <input name="walletafrica_skey" type="text" class="form-control" value="<?php echo $row ['walletafrica_skey']; ?>"required >
				  </div>
				  <label for="" class="col-sm-3 control-label"></label>
				</div>

				<div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Public Key:</label>
                  <div class="col-sm-6">
                  <input name="walletafrica_pkey" type="password" class="form-control" value="<?php echo $row ['walletafrica_pkey']; ?>"required >
				  </div>
				  <label for="" class="col-sm-3 control-label"></label>
				</div>

<hr>	
<div class="bg-orange">&nbsp;<b> WELLAHEALTH INTEGRATION KEYS </b></div>
<hr>

				<div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Client ID:</label>
                  <div class="col-sm-6">
                  <input name="wellahealth_clientid" type="text" class="form-control" value="<?php echo $row ['wellahealth_clientid']; ?>"required >
				  </div>
				  <label for="" class="col-sm-3 control-label"></label>
				</div>

				<div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Agent Code:</label>
                  <div class="col-sm-6">
                  <input name="wellahealth_agentcode" type="text" class="form-control" value="<?php echo $row ['wellahealth_agentcode']; ?>"required >
				  </div>
				  <label for="" class="col-sm-3 control-label"></label>
				</div>

				<div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Secret Key:</label>
                  <div class="col-sm-6">
                  <input name="wellahealth_clientsecretkey" type="password" class="form-control" value="<?php echo $row ['wellahealth_clientsecretkey']; ?>"required >
				  </div>
				  <label for="" class="col-sm-3 control-label"></label>
				</div>

<hr>	
<div class="bg-orange">&nbsp;<b> C'GATE INTEGRATION KEYS </b></div>
<hr>

				<div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Username:</label>
                  <div class="col-sm-6">
                  <input name="cgate_username" type="text" class="form-control" value="<?php echo $row['cgate_username']; ?>"required >
				  </div>
				  <label for="" class="col-sm-3 control-label"></label>
				</div>

				<div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Password:</label>
                  <div class="col-sm-6">
                  <input name="cgate_password" type="password" class="form-control" value="<?php echo $row['cgate_password']; ?>"required >
				  </div>
				  <label for="" class="col-sm-3 control-label"></label>
				</div>

				<div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">MID:</label>
                  <div class="col-sm-6">
                  <input name="cgate_mid" type="text" class="form-control" value="<?php echo $row['cgate_mid']; ?>" required >
				  </div>
				  <label for="" class="col-sm-3 control-label"></label>
				</div>

<hr>	
<div class="bg-orange">&nbsp;<b> OnePipe Integration Keys </b></div>
<hr>

				<div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Secret Key:</label>
                  <div class="col-sm-6">
                  <input name="onePipeSKey" type="text" class="form-control" value="<?php echo $row['onePipeSKey']; ?>" required >
				  </div>
				  <label for="" class="col-sm-3 control-label"></label>
				</div>

				<div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">API Key:</label>
                  <div class="col-sm-6">
                  <input name="onePipeApiKey" type="password" class="form-control" value="<?php echo $row['onePipeApiKey']; ?>" required >
				  </div>
				  <label for="" class="col-sm-3 control-label"></label>
				</div>

<hr>	
<div class="bg-orange">&nbsp;<b> Providus Integration Credentials </b></div>
<hr>

				<div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Username:</label>
                  <div class="col-sm-6">
                  <input name="providusUName" type="text" class="form-control" value="<?php echo $row['providusUName']; ?>" required >
				  </div>
				  <label for="" class="col-sm-3 control-label"></label>
				</div>

				<div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Password:</label>
                  <div class="col-sm-6">
                  <input name="providusPass" type="password" class="form-control" value="<?php echo $row['providusPass']; ?>" required >
				  </div>
				  <label for="" class="col-sm-3 control-label"></label>
				</div>


		</div>
		
		<div class="form-group" align="right">
				<label for="" class="col-sm-3 control-label" style="color:blue;"></label>
				<div class="col-sm-6">
					<button name="saveCredential" type="submit" class="btn bg-blue"><i class="fa fa-save">&nbsp;Save</i></button>
				</div>
				<label for="" class="col-sm-3 control-label"></label>
		</div>
		
       </form> 

      </div>
    </div>

  <?php
  }
}
  ?>
	
</div>	
</div>
</div>
</div>
</div>
</div>
</div>
</section>
</div>