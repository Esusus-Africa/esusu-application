<div class="row">
	      <section class="content">
        
       <div class="box box-default">
           <div class="box-body">
             <div class="table-responsive"> 
            <div class="box-body">
            <a href="mywallet.php?id=<?php echo $_SESSION['tid']; ?>&&mid=NDA0"><button type="button" class="btn btn-flat bg-orange"><i class="fa fa-mail-reply-all"></i>&nbsp;Back</button> </a> 

            <div class="col-md-14">
            <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
             <li <?php echo ($_GET['tab'] == 'tab_1') ? "class='active'" : ''; ?>><a href="transfer_fund.php?id=<?php echo $_SESSION['tid']; ?>&&mid=NDA0&&tab=tab_1">African Bank Account Transfers</a></li>

             <li <?php echo ($_GET['tab'] == 'tab_2') ? "class='active'" : ''; ?>><a href="transfer_fund.php?id=<?php echo $_SESSION['tid']; ?>&&mid=NDA0&&tab=tab_2">International Transfers</a></li>

              <li <?php echo ($_GET['tab'] == 'tab_3') ? "class='active'" : ''; ?>><a href="transfer_fund.php?id=<?php echo $_SESSION['tid']; ?>&&mid=NDA0&&tab=tab_3">Mpesa Mobile Money Transfer</a></li>

              <li <?php echo ($_GET['tab'] == 'tab_4') ? "class='active'" : ''; ?>><a href="transfer_fund.php?id=<?php echo $_SESSION['tid']; ?>&&mid=NDA0&&tab=tab_4">Ghana Mobile Money Transfer</a></li>

              <li <?php echo ($_GET['tab'] == 'tab_5') ? "class='active'" : ''; ?>><a href="transfer_fund.php?id=<?php echo $_SESSION['tid']; ?>&&mid=NDA0&&tab=tab_5">Ugandan Mobile Money Transfer</a></li>

              <li <?php echo ($_GET['tab'] == 'tab_6') ? "class='active'" : ''; ?>><a href="transfer_fund.php?id=<?php echo $_SESSION['tid']; ?>&&mid=NDA0&&tab=tab_6">Transfer to a saved beneficiary</a></li>
              
              <li <?php echo ($_GET['tab'] == 'tab_7') ? "class='active'" : ''; ?>><a href="transfer_fund.php?id=<?php echo $_SESSION['tid']; ?>&&mid=NDA0&&tab=tab_7">Bulk Transfer</a></li>
              
              <li <?php echo ($_GET['tab'] == 'tab_8') ? "class='active'" : ''; ?>><a href="transfer_fund.php?id=<?php echo $_SESSION['tid']; ?>&&mid=NDA0&&tab=tab_8">Check Exchange Rate</a></li>
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

             <div class="box-body">

      <div class="form-group">
                      <label for="" class="col-sm-2 control-label" style="color:blue;">Country</label>
                      <div class="col-sm-10">
            <select name="country"  class="form-control select2" id="country" onchange="loadbank();" required>
              <option value="" selected>Please Select Country</option>
              <option value="NG">Nigeria</option>
              <option value="GH">Ghana</option>
              <option value="KE">Kenya</option>
              <option value="UG">Uganda </option>
              <option value="TZ">Tanzania</option>
            </select>                 
            </div>
                    </div>

    <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Account Number</label>
                  <div class="col-sm-10">
                  <input name="account_number" type="text" id="account_number" onkeydown="loadaccount();" class="form-control" placeholder="Bank Account Number" required>
                  
                  </div>
                  </div>
                  
    <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Bank Code</label>
                  <div class="col-sm-10">
                    <div id="bank_list"></div>
                    <div id="act_numb"></div>
    </div>
    </div>

    <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Beneficiary Name</label>
                  <div class="col-sm-10">
                  <input name="b_name" type="text" id="act_numb" class="form-control" placeholder="Enter Beneficiary Name as Showned Above" required>
                  </div>
                  </div>

    <div class="form-group">
                      <label for="" class="col-sm-2 control-label" style="color:blue;">Currency</label>
                      <div class="col-sm-10">
            <select name="currency"  class="form-control select2" required>
              <option value="" selected>Please Select Currency</option>
              <option value="NGN">NGN</option>
              <option value="GHS">GHS</option>
              <option value="KES">KES</option>
              <option value="UGX">UGX</option>
              <option value="TZS">TZS</option>
            </select>                 
            </div>
                    </div>

    <div class="form-group">
                      <label for="" class="col-sm-2 control-label" style="color:blue;">Debit Currency</label>
                      <div class="col-sm-10">
    <select name="debit_currency"  class="form-control select2" required>
              <option value="" selected>Select Currency</option>
              <option value="NGN">NGN</option>
              <option value="USD">USD</option>
              <option value="GHS">GHS</option>
              <option value="KES">KES</option>
              <option value="UGX">UGX</option>
              <option value="TZS">TZS</option>
            </select>
          </div>
        </div>

    <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue">Amount</label>
                  <div class="col-sm-10">
                  <input name="amt_totransfer" type="text" class="form-control" placeholder="Enter Amount to Transfer" required>
                  <span style="color: orange;">NOTE: Pease do not put comma (,) while entering the Amount to Transfer</span>
                  </div>
                  </div>

            <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue">Reason(s)</label>
                  <div class="col-sm-10">
                 <textarea name="reasons" class="form-control" placeholder="State Reasons for Transfer e.g. For House rent, Pay Bonus etc." rows="4" cols="5" required></textarea>
                  </div>
                  </div>
			
			 </div>
			 
			  <div align="right">
              <div class="box-footer">
                				<button name="africa_save" type="submit" class="btn bg-blue btn-flat"><i class="fa fa-upload">&nbsp;Initiate Transfer</i></button>

              </div>
			  </div>
<?php
if(isset($_POST['africa_save']))
{
    include("../config/restful_apicalls.php");
    
    $reference =  "EA-atransfer-".myreference(10);
    $afcountry =  mysqli_real_escape_string($link, $_POST['country']);
    $account_number =  mysqli_real_escape_string($link, $_POST['account_number']);
    $bank_code =  mysqli_real_escape_string($link, $_POST['bank_code']);
	$b_name = mysqli_real_escape_string($link, $_POST['b_name']);
	$currency =  mysqli_real_escape_string($link, $_POST['currency']);
	$debit_currency = mysqli_real_escape_string($link, $_POST['debit_currency']);
	$amount = mysqli_real_escape_string($link, $_POST['amt_totransfer']);
	$afnarration =  mysqli_real_escape_string($link, $_POST['reasons']);
	$otp_code = mt_rand(100000,999999);
	
	$search_memset = mysqli_query($link, "SELECT * FROM systemset");
	$fetch_memset = mysqli_fetch_array($search_memset);
	$ourphone = $fetch_memset['mobile'];
	$tlimit = $fetch_memset['tlimit'];
	
	if($amount <= $tlimit){
	    $insert = mysqli_query($link, "INSERT INTO africa_pmt_confirmation VALUES(null,'$reference','$ourphone','$afcountry','$account_number','$bank_code','$b_name','$currency','$debit_currency','$amount','$afnarration','pin',NOW())");
	
    	if(!$insert)
    	{
    	    echo "<div class='alert bg-orange'>Transaction not Successful. Please try again later</div>";
    	}
    	else{
    		echo "<script>window.location='confirm_afri_trasfer.php?id=".$_SESSION['tid']."&&refid=".$reference."&&mid=NDA0'; </script>";
    	}
	}else{
	    $insert = mysqli_query($link, "INSERT INTO africa_pmt_confirmation VALUES(null,'$reference','$ourphone','$afcountry','$account_number','$bank_code','$b_name','$currency','$debit_currency','$amount','$afnarration','$otp_code',NOW())");
	
    	if(!$insert)
    	{
    	    echo "<div class='alert bg-orange'>Transaction not Successful. Please try again later</div>";
    	}
    	else{
    	    include("alert_sender/afri_bktransafer_otp.php");
    		echo "<script>window.location='confirm_afri_trasfer.php?id=".$_SESSION['tid']."&&mid=NDA0'; </script>";
    	}
	}
	
	
}
?>			  
			 </form> 

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

             <div class="box-body">
    
    <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Account Number</label>
                  <div class="col-sm-10">
                  <input name="account_number" type="text" class="form-control" placeholder="Bank Account Number" required>
                  </div>
                  </div>

    <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Routing Number</label>
                  <div class="col-sm-10">
                  <input name="routing_number" type="text" class="form-control" placeholder="Bank Routing Number" required>
                  </div>
                  </div>

    <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Swiftcode</label>
                  <div class="col-sm-10">
                  <input name="swift_code" type="text" class="form-control" placeholder="Bank SwiftCode" required>
                  </div>
                  </div>

    <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Bank Name</label>
                  <div class="col-sm-10">
                  <input name="bank_name" type="text" class="form-control" placeholder="e.g. BANK OF AMERICA, N.A., SAN FRANCISCO, CA" required>
                  </div>
                  </div>

    <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Beneficiary Name</label>
                  <div class="col-sm-10">
                  <input name="b_name" type="text" class="form-control" placeholder="Enter Beneficiary Name e.g. Mark Cuban" required>
                  </div>
                  </div>

    <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Beneficiary Address</label>
                  <div class="col-sm-10">
                  <input name="b_addrs" type="text" id="act_numb" class="form-control" placeholder="Enter Beneficiary Address e.g. San Francisco, 4 Newton" required>
                  </div>
                  </div>

    <div class="form-group">
                      <label for="" class="col-sm-2 control-label" style="color:blue;">Beneficiary Country</label>
                      <div class="col-sm-10">
            <select name="b_country"  class="form-control select2" id="country" required>
              <option value="" selected>Select Country</option>
              <option value="NG">Nigeria</option>
              <option value="GH">Ghana</option>
              <option value="KE">Kenya</option>
              <option value="UG">Uganda </option>
              <option value="TZ">Tanzania</option>
              <option value="US">United States</option>
              <option value="OT">Other countries</option>
            </select>                 
            </div>
                    </div>

    <div class="form-group">
                      <label for="" class="col-sm-2 control-label" style="color:blue;">Currency</label>
                      <div class="col-sm-10">
            <select name="currency"  class="form-control select2" required>
              <option value="" selected>Please Select Currency</option>
              <option value="NGN">NGN</option>
              <option value="USD">USD</option>
              <option value="GBP">GBP</option>
              <option value="EUR">EUR</option>
              <option value="GHS">GHS</option>
              <option value="KES">KES</option>
              <option value="UGX">UGX</option>
              <option value="TZS">TZS</option>
            </select>                 
            </div>
    </div>

    <div class="form-group">
                      <label for="" class="col-sm-2 control-label" style="color:blue;">Debit Currency</label>
                      <div class="col-sm-10">
    <select name="debit_currency"  class="form-control select2" required>
              <option selected>Select Currency</option>
              <option value="NGN">NGN</option>
              <option value="USD">USD</option>
              <option value="GHS">GHS</option>
              <option value="KES">KES</option>
              <option value="UGX">UGX</option>
              <option value="TZS">TZS</option>
            </select>
          </div>
        </div>

    <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue">Amount</label>
                  <div class="col-sm-10">
                  <input name="amt_totransfer" type="text" class="form-control" placeholder="Enter Amount to Transfer" required>
                  <span style="color: orange;">NOTE: Pease do not put comma (,) while entering the Amount to Transfer</span>
                  </div>
                  </div>

            <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue">Reason(s)</label>
                  <div class="col-sm-10">
                 <textarea name="reasons" class="form-control" placeholder="State Reasons for Transfer e.g. For House rent, Pay Bonus etc." rows="4" cols="5" required></textarea>
                  </div>
                  </div>
      
       </div>
       
        <div align="right">
              <div class="box-footer">
                        <button name="inter_save" type="submit" class="btn bg-blue btn-flat"><i class="fa fa-upload">&nbsp;Initiate Transfer</i></button>

              </div>
        </div>
<?php
if(isset($_POST['inter_save']))
{
    include("../config/restful_apicalls.php");
    
    $reference =  "EA-atransfer-".myreference(10);
    $account_number =  mysqli_real_escape_string($link, $_POST['account_number']);
    $routing_number =  mysqli_real_escape_string($link, $_POST['routing_number']);
    $swift_code =  mysqli_real_escape_string($link, $_POST['swift_code']);
    $bank_name = mysqli_real_escape_string($link, $_POST['bank_name']);
	$b_name = mysqli_real_escape_string($link, $_POST['b_name']);
	$b_addrs = mysqli_real_escape_string($link, $_POST['b_addrs']);
	$b_country =  mysqli_real_escape_string($link, $_POST['b_country']);
	$currency = mysqli_real_escape_string($link, $_POST['currency']);
	$debit_currency = mysqli_real_escape_string($link, $_POST['debit_currency']);
	$amount = mysqli_real_escape_string($link, $_POST['amt_totransfer']);
	$intnarration =  mysqli_real_escape_string($link, $_POST['intreasons']);
	$otp_code = mt_rand(100000,999999);
	
	$search_memset = mysqli_query($link, "SELECT * FROM systemset");
	$fetch_memset = mysqli_fetch_array($search_memset);
	$ourphone = $fetch_memset['mobile'];
	$tlimit = $fetch_memset['tlimit'];
	
	if($amount <= $tlimit){
	    $insert = mysqli_query($link, "INSERT INTO inter_pmt_confirmation VALUES(null,'$reference','$account_number','$routing_number','$swift_code','$bank_name','$b_name','$b_addrs','$b_country','$currency','$debit_currency','$amount','$intnarration','$ourphone','pin',NOW())");
	
    	if(!$insert)
    	{
    	    echo "<div class='alert bg-orange'>Transaction not Successful. Please try again later</div>";
    	}
    	else{
    		echo "<script>window.location='confirm_inter_trasfer.php?id=".$_SESSION['tid']."&&mid=NDA0'; </script>";
    	}
	}else{
	    $insert = mysqli_query($link, "INSERT INTO inter_pmt_confirmation VALUES(null,'$reference','$account_number','$routing_number','$swift_code','$bank_name','$b_name','$b_addrs','$b_country','$currency','$debit_currency','$amount','$intnarration','$ourphone','$otp_code',NOW())");
	
    	if(!$insert)
    	{
    	    echo "<div class='alert bg-orange'>Transaction not Successful. Please try again later</div>";
    	}
    	else{
    	    include("alert_sender/inter_bktransafer_otp.php");
    		echo "<script>window.location='confirm_inter_trasfer.php?id=".$_SESSION['tid']."&&mid=NDA0'; </script>";
    	}
	}
}
?>        
       </form> 

      </div>

  <?php
  }
  elseif($tab == 'tab_3')
  {
  ?> 

      <div class="tab-pane <?php echo ($_GET['tab'] == 'tab_3') ? 'active' : ''; ?>" id="tab_3">

        <div class="box-body">

          <form class="form-horizontal" method="post" enctype="multipart/form-data">

            <div class="box-body">

    <input name="account_bank" type="hidden" class="form-control" value="MPS">
    
    <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Account Number</label>
                  <div class="col-sm-10">
                  <input name="account_number" type="text" class="form-control" placeholder="Recipient Mpesa Number" required>
                  <span style="color: orange"><b>NOTE:</b> It should always come with the prefix <b>233</b></span>
                  </div>
                  </div>

    <div class="form-group">
                      <label for="" class="col-sm-2 control-label" style="color:blue;">Currency</label>
                      <div class="col-sm-10">
            <select name="currency"  class="form-control select2" required>
              <option value="" selected>Select Currency</option>
              <option value="NGN">NGN</option>
              <option value="USD">USD</option>
              <option value="GHS">GHS</option>
              <option value="KES">KES</option>
              <option value="UGX">UGX</option>
              <option value="TZS">TZS</option>
            </select>                 
            </div>
                    </div>

    <div class="form-group">
                      <label for="" class="col-sm-2 control-label" style="color:blue;">Debit Currency</label>
                      <div class="col-sm-10">
    <select name="debit_currency"  class="form-control select2" required>
              <option value="" selected>Select Currency</option>
              <option value="NGN">NGN</option>
              <option value="USD">USD</option>
              <option value="GHS">GHS</option>
              <option value="KES">KES</option>
              <option value="UGX">UGX</option>
              <option value="TZS">TZS</option>
            </select>
          </div>
        </div>

    <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue">Amount</label>
                  <div class="col-sm-10">
                  <input name="amt_totransfer" type="text" class="form-control" placeholder="Enter Amount to Transfer" required>
                  <span style="color: orange;">NOTE: Pease do not put comma (,) while entering the Amount to Transfer</span>
                  </div>
                  </div>

    <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Beneficiary Name</label>
                  <div class="col-sm-10">
                  <input name="b_name" type="text" id="act_numb" class="form-control" placeholder="Enter Beneficiary Name e.g. Kwame Adew" required>
                  </div>
                  </div>

            <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue">Reason(s)</label>
                  <div class="col-sm-10">
                 <textarea name="reasons" class="form-control" placeholder="State Reasons for Transfer e.g. For House rent, Pay Bonus etc." rows="4" cols="5" required></textarea>
                  </div>
                  </div>

            </div>

        <div align="right">
              <div class="box-footer">
                        <button name="mpesa_save" type="submit" class="btn bg-blue btn-flat"><i class="fa fa-upload">&nbsp;Initiate Transfer</i></button>

              </div>
        </div>
<?php
if(isset($_POST['mpesa_save']))
{
    include("../config/restful_apicalls.php");
    
    $reference =  "EA-atransfer-".myreference(10);
    $account_number =  mysqli_real_escape_string($link, $_POST['account_number']);
    $routing_number =  mysqli_real_escape_string($link, $_POST['routing_number']);
    $currency = mysqli_real_escape_string($link, $_POST['currency']);
	$debit_currency = mysqli_real_escape_string($link, $_POST['debit_currency']);
	$amount = mysqli_real_escape_string($link, $_POST['amt_totransfer']);
	$bname = mysqli_real_escape_string($link, $_POST['b_name']);
	$mnarration =  mysqli_real_escape_string($link, $_POST['reasons']);
	$otp_code = mt_rand(100000,999999);
	
	$search_memset = mysqli_query($link, "SELECT * FROM systemset");
	$fetch_memset = mysqli_fetch_array($search_memset);
	$ourphone = $fetch_memset['mobile'];
	$tlimit = $fetch_memset['tlimit'];
	
	if($amount <= $tlimit){
	    $insert = mysqli_query($link, "INSERT INTO mpesa_pmt_confirmation VALUES(null,'$reference','$account_number','$currency','$debit_currency','$amount','$bname','$mnarration','$ourphone','pin',NOW())");
	
    	if(!$insert)
    	{
    	    echo "<div class='alert bg-orange'>Transaction not Successful. Please try again later</div>";
    	}
    	else{
    		echo "<script>window.location='confirm_mpesa_trasfer.php?id=".$_SESSION['tid']."&&mid=NDA0'; </script>";
    	}
	}else{
	    $insert = mysqli_query($link, "INSERT INTO mpesa_pmt_confirmation VALUES(null,'$reference','$account_number','$currency','$debit_currency','$amount','$bname','$mnarration','$ourphone','$otp_code',NOW())");
	
    	if(!$insert)
    	{
    	    echo "<div class='alert bg-orange'>Transaction not Successful. Please try again later</div>";
    	}
    	else{
    	    include("alert_sender/mpesa_bktransafer_otp.php");
    		echo "<script>window.location='confirm_mpesa_trasfer.php?id=".$_SESSION['tid']."&&mid=NDA0'; </script>";
    	}
	}
}
?>         
       </form> 

      </div>

  <?php
  }
  elseif($tab == 'tab_4')
  {
  ?> 

      <div class="tab-pane <?php echo ($_GET['tab'] == 'tab_4') ? 'active' : ''; ?>" id="tab_4">

        <div class="box-body">

          <form class="form-horizontal" method="post" enctype="multipart/form-data">

            <div class="box-body">

     <div class="form-group">
                      <label for="" class="col-sm-2 control-label" style="color:blue;">Country</label>
                      <div class="col-sm-10">
            <select name="country"  class="form-control select2" id="country" onchange="loadbank1();" required>
              <option value="" selected>Please Select Country</option>
              <option value="GH">Ghana</option>
            </select>                 
            </div>
                    </div>

      <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Bank</label>
                  <div class="col-sm-10">
                    <div id="bank_list1"></div>
    </div>
    </div>

    <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Branch Code</label>
                  <div class="col-sm-10">
                    <div id="branch_code"></div>
    </div>
    </div>

    <div class="form-group">
                      <label for="" class="col-sm-2 control-label" style="color:blue;">Account Bank</label>
                      <div class="col-sm-10">
            <select name="account_bank"  class="form-control select2" required>
              <option value="" selected>Select Account Bank</option>
              <option value="MTN">MTN</option>
              <option value="TIGO">TIGO</option>
              <option value="VODAFONE">VODAFONE</option>
              <option value="AIRTEL">AIRTEL</option>
            </select>  
            </div>
                    </div>
    <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Account Number</label>
                  <div class="col-sm-10">
                  <input name="account_number" type="text" class="form-control" placeholder="Recipient Mobile Money Number" required>
                  <span style="color: orange"><b>NOTE:</b> It should always come with the prefix <b>233</b></span>
                  </div>
                  </div>

    <div class="form-group">
                      <label for="" class="col-sm-2 control-label" style="color:blue;">Currency</label>
                      <div class="col-sm-10">
            <select name="currency" class="form-control select2" required>
              <option value="GHS" selected="selected">GHS</option>
            </select>                 
            </div>
                    </div>

    <div class="form-group">
                      <label for="" class="col-sm-2 control-label" style="color:blue;">Debit Currency</label>
                      <div class="col-sm-10">
    <select name="debit_currency"  class="form-control select2" required>
              <option value="" selected>Select Currency</option>
              <option value="NGN">NGN</option>
              <option value="USD">USD</option>
              <option value="GHS">GHS</option>
              <option value="KES">KES</option>
              <option value="UGX">UGX</option>
              <option value="TZS">TZS</option>
            </select>
          </div>
        </div>

    <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue">Amount</label>
                  <div class="col-sm-10">
                  <input name="amt_totransfer" type="text" class="form-control" placeholder="Enter Amount to Transfer" required>
                  <span style="color: orange;">NOTE: Pease do not put comma (,) while entering the Amount to Transfer</span>
                  </div>
                  </div>

    <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Beneficiary Name</label>
                  <div class="col-sm-10">
                  <input name="b_name" type="text" id="act_numb" class="form-control" placeholder="Enter Beneficiary Name e.g. Kwame Adew" required>
                  </div>
                  </div>

            <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue">Reason(s)</label>
                  <div class="col-sm-10">
                 <textarea name="reasons" class="form-control" placeholder="State Reasons for Transfer e.g. For House rent, Pay Bonus etc." rows="4" cols="5" required></textarea>
                  </div>
                  </div>

            </div>

        <div align="right">
              <div class="box-footer">
                        <button name="ghana_save" type="submit" class="btn bg-blue btn-flat"><i class="fa fa-upload">&nbsp;Initiate Transfer</i></button>

              </div>
        </div>
<?php
if(isset($_POST['ghana_save']))
{
    include("../config/restful_apicalls.php");
    
    $reference =  "EA-atransfer-".myreference(10);
    $country =  mysqli_real_escape_string($link, $_POST['country']);
    $bank_id = mysqli_real_escape_string($link, $_POST['bank_id']);
    $branch_code = mysqli_real_escape_string($link, $_POST['branch_code']);
    $account_bank =  mysqli_real_escape_string($link, $_POST['account_bank']);
    $account_number =  mysqli_real_escape_string($link, $_POST['account_number']);
    $currency = mysqli_real_escape_string($link, $_POST['currency']);
	$debit_currency = mysqli_real_escape_string($link, $_POST['debit_currency']);
	$amount = mysqli_real_escape_string($link, $_POST['amt_totransfer']);
	$bname = mysqli_real_escape_string($link, $_POST['b_name']);
	$mnarration =  mysqli_real_escape_string($link, $_POST['reasons']);
	$otp_code = mt_rand(100000,999999);
	
	$search_memset = mysqli_query($link, "SELECT * FROM systemset");
	$fetch_memset = mysqli_fetch_array($search_memset);
	$ourphone = $fetch_memset['mobile'];
	$tlimit = $fetch_memset['tlimit'];
	
	if($amount <= $tlimit){
	    $insert = mysqli_query($link, "INSERT INTO ghana_pmt_confirmation VALUES(null,'$reference','$country','$bank_id','$branch_code','$account_bank','$account_number','$currency','$debit_currency','$amount','$bname','$mnarration','$ourphone','pin',NOW())");
	
    	if(!$insert)
    	{
    	    echo "<div class='alert bg-orange'>Transaction not Successful. Please try again later</div>";
    	}
    	else{
    		echo "<script>window.location='confirm_ghana_trasfer.php?id=".$_SESSION['tid']."&&mid=NDA0'; </script>";
    	}
	}else{
	    $insert = mysqli_query($link, "INSERT INTO ghana_pmt_confirmation VALUES(null,'$reference','$country','$bank_id','$branch_code','$account_bank','$account_number','$currency','$debit_currency','$amount','$bname','$mnarration','$ourphone','$otp_code',NOW())");
	
    	if(!$insert)
    	{
    	    echo "<div class='alert bg-orange'>Transaction not Successful. Please try again later</div>";
    	}
    	else{
    	    include("alert_sender/ghana_bktransafer_otp.php");
    		echo "<script>window.location='confirm_ghana_trasfer.php?id=".$_SESSION['tid']."&&mid=NDA0'; </script>";
    	}
	}
} 
?>        
       </form> 

      </div>

  <?php
  }
  elseif($tab == 'tab_5')
  {
  ?> 

      <div class="tab-pane <?php echo ($_GET['tab'] == 'tab_5') ? 'active' : ''; ?>" id="tab_5">

        <div class="box-body">

          <form class="form-horizontal" method="post" enctype="multipart/form-data">

            <div class="box-body">

    <div class="form-group">
                      <label for="" class="col-sm-2 control-label" style="color:blue;">Account Bank</label>
                      <div class="col-sm-10">
            <select name="account_bank"  class="form-control select2" required>
              <option value="" selected>Select Account Bank</option>
              <option value="MTN">MTN</option>
              <option value="AIRTEL">AIRTEL</option>
            </select>  
            </div>
                    </div>
    <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Account Number</label>
                  <div class="col-sm-10">
                  <input name="account_number" type="text" class="form-control" placeholder="Recipient Mobile Money Number" required>
                  <span style="color: orange"><b>NOTE:</b> It should always come with the prefix <b>256</b></span>
                  </div>
                  </div>

    <div class="form-group">
                      <label for="" class="col-sm-2 control-label" style="color:blue;">Currency</label>
                      <div class="col-sm-10">
            <select name="currency"  class="form-control select2" required>
              <option value="UGX" selected="selected">UGX</option>
            </select>                 
            </div>
                    </div>

    <div class="form-group">
                      <label for="" class="col-sm-2 control-label" style="color:blue;">Debit Currency</label>
                      <div class="col-sm-10">
    <select name="debit_currency"  class="form-control select2" required>
              <option value="" selected>Select Currency</option>
              <option value="NGN">NGN</option>
              <option value="USD">USD</option>
              <option value="GHS">GHS</option>
              <option value="KES">KES</option>
              <option value="UGX">UGX</option>
              <option value="TZS">TZS</option>
            </select>
          </div>
        </div>

    <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue">Amount</label>
                  <div class="col-sm-10">
                  <input name="amt_totransfer" type="text" class="form-control" placeholder="Enter Amount to Transfer" required>
                  <span style="color: orange;">NOTE: Pease do not put comma (,) while entering the Amount to Transfer</span>
                  </div>
                  </div>

    <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Beneficiary Name</label>
                  <div class="col-sm-10">
                  <input name="b_name" type="text" id="act_numb" class="form-control" placeholder="Enter Beneficiary Name e.g. Kwame Adew" required>
                  </div>
                  </div>

            <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue">Reason(s)</label>
                  <div class="col-sm-10">
                 <textarea name="reasons" class="form-control" placeholder="State Reasons for Transfer e.g. For House rent, Pay Bonus etc." rows="4" cols="5" required></textarea>
                  </div>
                  </div>

            </div>

        <div align="right">
              <div class="box-footer">
                        <button name="ugandan_save" type="submit" class="btn bg-blue btn-flat"><i class="fa fa-upload">&nbsp;Initiate Transfer</i></button>

              </div>
        </div>
<?php
if(isset($_POST['ugandan_save']))
{
    include("../config/restful_apicalls.php");
    
    $reference =  "EA-atransfer-".myreference(10);
    $acctbank =  mysqli_real_escape_string($link, $_POST['account_bank']);
    $account_number =  mysqli_real_escape_string($link, $_POST['account_number']);
    $currency = mysqli_real_escape_string($link, $_POST['currency']);
	$debit_currency = mysqli_real_escape_string($link, $_POST['debit_currency']);
	$amount = mysqli_real_escape_string($link, $_POST['amt_totransfer']);
	$bname = mysqli_real_escape_string($link, $_POST['b_name']);
	$mnarration =  mysqli_real_escape_string($link, $_POST['reasons']);
	$otp_code = mt_rand(100000,999999);
	
	$search_memset = mysqli_query($link, "SELECT * FROM systemset");
	$fetch_memset = mysqli_fetch_array($search_memset);
	$ourphone = $fetch_memset['mobile'];
	$tlimit = $fetch_memset['tlimit'];
	
	if($amount <= $tlimit){
	    $insert = mysqli_query($link, "INSERT INTO ugandan_pmt_confirmation VALUES(null,'$reference','$acctbank','$account_number','$currency','$debit_currency','$amount','$bname','$mnarration','$ourphone','pin',NOW())");
	
    	if(!$insert)
    	{
    	    echo "<div class='alert bg-orange'>Transaction not Successful. Please try again later</div>";
    	}
    	else{
    		echo "<script>window.location='confirm_ugandan_trasfer.php?id=".$_SESSION['tid']."&&mid=NDA0'; </script>";
    	}
	}else{
	    $insert = mysqli_query($link, "INSERT INTO ugandan_pmt_confirmation VALUES(null,'$reference','$acctbank','$account_number','$currency','$debit_currency','$amount','$bname','$mnarration','$ourphone','$otp_code',NOW())");
	
    	if(!$insert)
    	{
    	    echo "<div class='alert bg-orange'>Transaction not Successful. Please try again later</div>";
    	}
    	else{
    	    include("alert_sender/ugandan_bktransafer_otp.php");
    		echo "<script>window.location='confirm_ugandan_trasfer.php?id=".$_SESSION['tid']."&&mid=NDA0'; </script>";
    	}
	}
}
?>         
       </form> 

      </div>

  <?php
  }
  elseif($tab == 'tab_6')
  {
  ?>

      <div class="tab-pane <?php echo ($_GET['tab'] == 'tab_6') ? 'active' : ''; ?>" id="tab_6">

             <div class="box-body">
           
       <form class="form-horizontal" method="post" enctype="multipart/form-data">

             <div class="box-body">

      <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue">Select Recipient</label>
                  <div class="col-sm-10">
        <select name="recipient_id"  class="form-control select2" required style="width:100%">
        <option value="" selected>Select Transfer Recipient</option>
        <?php
        $get = mysqli_query($link, "SELECT * FROM transfer_recipient ORDER BY id") or die (mysqli_error($link));
        while($rows = mysqli_fetch_array($get))
        {
        ?>
        <option value="<?php echo $rows['recipient_id']; ?>"><?php echo $rows['full_name'].' ('.$rows['acct_no'].')'; ?></option>
        <?php } ?>        
        </select>
      </div>
      </div>

    <div class="form-group">
                      <label for="" class="col-sm-2 control-label" style="color:blue;">Currency</label>
                      <div class="col-sm-10">
            <select name="currency"  class="form-control select2" required>
              <option selected="selected">Please Select Currency</option>
              <option value="NGN">NGN</option>
              <option value="USD">USD</option>
              <option value="GHS">GHS</option>
              <option value="KES">KES</option>
              <option value="UGX">UGX</option>
              <option value="TZS">TZS</option>
            </select>                 
            </div>
                    </div>

    <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue">Amount</label>
                  <div class="col-sm-10">
                  <input name="amt_totransfer" type="text" class="form-control" placeholder="Enter Amount to Transfer" required>
                  <span style="color: orange;">NOTE: Pease do not put comma (,) while entering the Amount to Transfer</span>
                  </div>
                  </div>
                  
    <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Beneficiary Name</label>
                  <div class="col-sm-10">
                  <input name="b_name" type="text" id="act_numb" class="form-control" placeholder="Enter Beneficiary Name e.g. Kwame Adew" required>
                  </div>
                  </div>

            <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue">Reason(s)</label>
                  <div class="col-sm-10">
                 <textarea name="reasons" class="form-control" placeholder="State Reasons for Transfer e.g. For House rent, Pay Bonus etc." rows="4" cols="5" required></textarea>
                  </div>
                  </div>
      
       </div>
       
        <div align="right">
              <div class="box-footer">
                        <button name="savedb_save" type="submit" class="btn bg-blue btn-flat"><i class="fa fa-upload">&nbsp;Initiate Transfer</i></button>

              </div>
        </div>
<?php
if(isset($_POST['savedb_save']))
{
    include("../config/restful_apicalls.php");
    
    $reference =  "EA-atransfer-".myreference(10);
    $recipient_id =  mysqli_real_escape_string($link, $_POST['recipient_id']);
    $currency = mysqli_real_escape_string($link, $_POST['currency']);
    $amount = mysqli_real_escape_string($link, $_POST['amt_totransfer']);
	$mnarration =  mysqli_real_escape_string($link, $_POST['reasons']);
	$b_name =  mysqli_real_escape_string($link, $_POST['b_name']);
	$otp_code = mt_rand(100000,999999);
	
	$search_memset = mysqli_query($link, "SELECT * FROM systemset");
	$fetch_memset = mysqli_fetch_array($search_memset);
	$ourphone = $fetch_memset['mobile'];
	$tlimit = $fetch_memset['tlimit'];
	
	if($amount <= $tlimit){
	    $insert = mysqli_query($link, "INSERT INTO sbeneficiary_pmt_confirmation VALUES(null,'$reference','$recipient_id','$currency','$amount','$b_name','$mnarration','$ourphone','pin',NOW())");
	
    	if(!$insert)
    	{
    	    echo "<div class='alert bg-orange'>Transaction not Successful. Please try again later</div>";
    	}
    	else{
    		echo "<script>window.location='confirm_sbeneficiary_trasfer.php?id=".$_SESSION['tid']."&&refid=".$reference."&&mid=NDA0'; </script>";
    	}
	}else{
	    $insert = mysqli_query($link, "INSERT INTO sbeneficiary_pmt_confirmation VALUES(null,'$reference','$recipient_id','$currency','$amount','$b_name','$mnarration','$ourphone','$otp_code',NOW())");
	
    	if(!$insert)
    	{
    	    echo "<div class='alert bg-orange'>Transaction not Successful. Please try again later</div>";
    	}
    	else{
    	    include("alert_sender/sbeneficiary_bktransafer_otp.php");
    		echo "<script>window.location='confirm_sbeneficiary_trasfer.php?id=".$_SESSION['tid']."&&mid=NDA0'; </script>";
    	}
	}
}
?>        
       </form> 

      </div>
      <!-- /.tab-pane -->

<?php
  }
  elseif($tab == 'tab_7')
  {
  ?>
      
      <div class="tab-pane <?php echo ($_GET['tab'] == 'tab_7') ? 'active' : ''; ?>" id="tab_7">

             <div class="box-body">
           
       <form class="form-horizontal" method="post" enctype="multipart/form-data">

             <div class="box-body">

      <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Transfer Title</label>
                  <div class="col-sm-10">
                  <input name="t_title" type="text" id="act_numb" class="form-control" placeholder="Enter Transfer Title e.g. May Staff Salary">
                  <span style="color: orange;">This section should carry the title of the transfer as described in the Text Field</span>
                  </div>
                  </div>

      <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue">Upload File</label>
                  <div class="col-sm-10">
        <input type='file' name="file" accept=".csv" class="btn bg-orange" required/>
        <hr>
      <p style="color:orange"><b style="color:blue;">NOTE:</b><br>
        <span style="color:blue;">(1)</span> <i>Download the <a href="../sample/disburse_sample.csv"> <b class="btn bg-blue"><i class="fa fa-download"></i> disburse sample file</b></a> and attach it once you're done replacing those data with all your bank info.</i></p>
        <span style="color:blue;">(2)</span> <span style="color:orange"><i>Download the <a href="../sample/banks.csv"> <b class="btn bg-blue"><i class="fa fa-download"></i> List of Banks</b></a></i></span>

      </div>
      </div>
      
                                
    <hr>
      
       </div>
       
        <div align="right">
              <div class="box-footer">
                        <button name="savedbulk_save" type="submit" class="btn bg-blue btn-flat"><i class="fa fa-upload">&nbsp;Initiate Transfer</i></button>

              </div>
        </div>
<?php
if(isset($_POST['savedbulk_save']))
{
    include("../config/restful_apicalls.php");
    
    $reference =  "EA-atransfer-".myreference(10);
    $t_title =  mysqli_real_escape_string($link, $_POST['t_title']);
    $otp_code = mt_rand(100000,999999);
	
	$search_memset = mysqli_query($link, "SELECT * FROM systemset");
	$fetch_memset = mysqli_fetch_array($search_memset);
	$ourphone = $fetch_memset['mobile'];
	$tlimit = $fetch_memset['tlimit'];
	
	echo $filename=$_FILES["file"]["tmp_name"];
	
	$allowed_filetypes = array('csv');
	if(!in_array(end(explode(".", $_FILES['file']['name'])), $allowed_filetypes))
	{
		echo "<script type=\"text/javascript\">
		alert(\"The file you attempted to upload is not allowed.\");
		</script>";
	}    
	elseif($_FILES["file"]["size"] > 0)
	{
	    $file = fopen($filename, "r");
	    while (($emapData = fgetcsv($file, 10000, ",")) !== FALSE)
	    {
	        if($emapData[2] <= $tlimit){
	            //It wiil insert a row to our borrowers table from our csv file`
    	        $sql = "INSERT INTO bulk_pmt_confirmation(id,refid,t_title,acctno,bank,amount,reasons,phone,otp_code,date_time) VALUES(null,'$reference','$t_title','$emapData[0]','$emapData[1]','$emapData[2]','$emapData[3]','$ourphone','pin',NOW())";
    	        //we are using mysql_query function. it returns a resource on true else False on error
    	        $result = mysqli_query($link,$sql);
    		    if(!$result)
    			{
    				echo "<script type=\"text/javascript\">
    					alert(\"Invalid File:Please Upload CSV File.\");
    					</script>";
    			}
	        }else{
	            //It wiil insert a row to our borrowers table from our csv file`
    	        $sql = "INSERT INTO bulk_pmt_confirmation(id,refid,t_title,acctno,bank,amount,reasons,phone,otp_code,date_time) VALUES(null,'$reference','$t_title','$emapData[0]','$emapData[1]','$emapData[2]','$emapData[3]','$ourphone','$otp_code',NOW())";
    	        //we are using mysql_query function. it returns a resource on true else False on error
    	        $result = mysqli_query($link,$sql);
    	        include("alert_sender/bulk_bktransafer_otp.php");
    		    if(!$result)
    			{
    				echo "<script type=\"text/javascript\">
    					alert(\"Invalid File:Please Upload CSV File.\");
    					</script>";
    			}
	        }
 
	   }
	   fclose($file);
	   //throws a message if data successfully imported to mysql database from excel file
	   echo "<script>window.location='confirm_bulk_trasfer.php?id=".$_SESSION['tid']."&&mid=NDA0'; </script>";
	    
	}
}
?>     
       </form> 

      </div>

  <?php
  }
  elseif($tab == 'tab_8')
  {
  ?>
  
    <div class="tab-pane <?php echo ($_GET['tab'] == 'tab_8') ? 'active' : ''; ?>" id="tab_8">

             <div class="box-body">
           
       <form class="form-horizontal" method="post" enctype="multipart/form-data">

             <div class="box-body">

      <div class="form-group">
                      <label for="" class="col-sm-4 control-label" style="color:blue;">Origin Currency</label>
                      <div class="col-sm-8">
            <select name="orcurrency"  class="form-control select2" required>
              <option value="" selected>Select Origin Currency</option>
              <option value="NGN">NGN</option>
              <option value="USD">USD</option>
              <option value="GBP">GBP</option>
              <option value="EUR">EUR</option>
              <option value="GHS">GHS</option>
              <option value="KES">KES</option>
              <option value="UGX">UGX</option>
              <option value="TZS">TZS</option>
            </select>                 
            </div>
    </div>
    
    <div class="form-group">
                      <label for="" class="col-sm-4 control-label" style="color:blue;">Destination Currency</label>
                      <div class="col-sm-8">
            <select name="decurrency"  class="form-control select2" required>
              <option value="" selected>Select Destination Currency</option>
              <option value="NGN">NGN</option>
              <option value="USD">USD</option>
              <option value="GBP">GBP</option>
              <option value="EUR">EUR</option>
              <option value="GHS">GHS</option>
              <option value="KES">KES</option>
              <option value="UGX">UGX</option>
              <option value="TZS">TZS</option>
            </select>                 
            </div>
    </div>
    
    <div class="form-group">
                  <label for="" class="col-sm-4 control-label" style="color:blue">Amount</label>
                  <div class="col-sm-8">
                  <input name="amt_toconvert" type="text" class="form-control" placeholder="Enter Amount to Convert" required>
                  <span style="color: orange;">NOTE: Pease do not put comma (,) while entering the Amount to Convert</span>
                  </div>
                  </div>
      
       </div>
       
        <div align="right">
              <div class="box-footer">
                        <button name="Convert_save" type="submit" class="btn bg-blue btn-flat"><i class="fa fa-refresh">&nbsp;Convert</i></button>

              </div>
        </div>
        
        <?php
if(isset($_POST['Convert_save']))
{
    include("../config/restful_apicalls.php");
    
    $result = array();
    $orcurrency =  mysqli_real_escape_string($link, $_POST['orcurrency']);
    $decurrency =  mysqli_real_escape_string($link, $_POST['decurrency']);
    $amt_toconvert =  mysqli_real_escape_string($link, $_POST['amt_toconvert']);
    
    $systemset = mysqli_query($link, "SELECT * FROM systemset");
	$row1 = mysqli_fetch_object($systemset);
	$seckey = $row1->secret_key;
	
	$search_restapi = mysqli_query($link, "SELECT * FROM restful_apisetup WHERE api_name = 'Exchange_Rate'");
	$fetch_restapi = mysqli_fetch_object($search_restapi);
	$api_url = $fetch_restapi->api_url;
	
	// Pass the parameter here
	$postdata =  array(
	    "secret_key"	=>	$seckey,
	    "service"       =>  "rates_convert",
	    "service_method"    =>  "post",
	    "service_version"   =>  "v1",
	    "service_channel"   =>  "transactions",
	    "service_channel_group" =>  "merchants",
	    "service_payload"   =>  [
	        "FromCurrency"  =>  $orcurrency,
	        "ToCurrency"    =>  $decurrency,
	        "Amount"        =>  $amt_toconvert
	        ]
	    );
	    
	    $make_call = callAPI('POST', $api_url, json_encode($postdata));
		$result = json_decode($make_call, true);

		if($result['status'] == "success"){
		    echo "<hr>";
		    echo "<div class='form-group'>
                  <label for='' class='col-sm-4 control-label' style='color:blue'>Exchange Rate:</label>
                  <div class='col-sm-8' style='color: orange; font-size:14px;'>
                  <input type='text' class='form-control' value='".$result['data']['ToCurrencyName'].$result['data']['Rate']." per ".$orcurrency."' readonly/>
                  </div>
                  </div>";
            echo "<div class='form-group'>
                  <label for='' class='col-sm-4 control-label' style='color:blue'>Exchange Fee:</label>
                  <div class='col-sm-8' style='color: orange; font-size:14px;'>
                  <input type='text' class='form-control' value='".$result['data']['Fee']."' readonly/>
                  </div>
                  </div>";
            echo "<div class='form-group'>
                  <label for='' class='col-sm-4 control-label' style='color:blue'>Convert From:</label>
                  <div class='col-sm-8' style='color: orange; font-size:14px;'>
                  <input type='text' class='form-control' value='".$orcurrency.number_format($amt_toconvert,2,'.',',')."' readonly/>
                  </div>
                  </div>";
            echo "<div class='form-group'>
                  <label for='' class='col-sm-4 control-label' style='color:blue'>Result To:</label>
                   <div class='col-sm-8' style='color: orange; font-size:14px;'>
                  <input type='text' class='form-control' value='".$result['data']['ToCurrencyName'].number_format($result['data']['ToAmount'],2,'.',',')."' readonly/>
                  </div>
                  </div>";
		}
		else{
		    echo "<p style='color: blue; font-size:18px;'>".$result['data']['Message']."</p>";
		}
}
?>     
       </form> 

      </div>
      <!-- /.tab-pane -->
  
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
</div>
</div>
</div>
</div>
</div>
</div>
</section>
</div>