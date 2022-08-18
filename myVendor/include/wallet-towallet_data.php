<div class="row">
	      <section class="content">
        
       <div class="box box-default">
           <div class="box-body">
             <div class="table-responsive"> 
            <div class="box-body">
            <a href="transfer_fund.php?id=<?php echo $_SESSION['tid']; ?>&&mid=NDA0"><button type="button" class="btn btn-flat bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>"><i class="fa fa-mail-reply-all"></i>&nbsp;Back</button></a> 
<button type="button" class="btn btn-flat bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>" align="left" disabled>&nbsp;<b>Wallet Balance:</b>&nbsp;
<strong class="alert bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">
<?php
echo $vcurrency.number_format($vwallet_balance,2,'.',',');
?> 
</strong>
  </button>
  
            <div class="col-md-14">
            <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
             <!--<li <?php //echo ($_GET['tab'] == 'tab_1') ? "class='active'" : ''; ?>><a href="wallet-towallet.php?id=<?php echo $_SESSION['tid']; ?>&&mid=NDA0&&tab=tab_1">Fund Customer Wallet</a></li>-->

             <li <?php echo ($_GET['tab'] == 'tab_2') ? "class='active'" : ''; ?>><a href="wallet-towallet.php?id=<?php echo $_SESSION['tid']; ?>&&mid=NDA0&&tab=tab_2">Fund other Vendor Wallet</a></li>

              </ul>
             <div class="tab-content">
<div class="alert bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>">NOTE that transfer to other peoples wallet within the system comes with Zero charges</div>
<hr>

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
/*if(isset($_POST['csave']))
{
  function myreference($limit)
  {
    return substr(base_convert(sha1(uniqid(mt_rand())), 16, 36), 0, $limit);
  }
  try{
    $ptype = "p2p-transfer";
    $account =  mysqli_real_escape_string($link, $_POST['author']);
    $amount = mysqli_real_escape_string($link, $_POST['amount']);
    $remark = mysqli_real_escape_string($link, $_POST['remark']);
    //$remark .= "<br><b>Posted by:<br>".$name.'</b>';
    $date_time = date("Y-m-d");
    $final_date_time = date ('Y-m-d h:i:s', strtotime($date_time));
    $txid = 'EA-p2pFunding-'.myreference(10);
    $tpin = mysqli_real_escape_string($link, $_POST['tpin']);

    $search_memset = mysqli_query($link, "SELECT * FROM member_settings WHERE companyid = '$vcreated_by'");
    $fetch_memset = mysqli_fetch_array($search_memset);
    //$sys_abb = $get_sys['abb'];
      
    if($amount < 0){
      throw new UnexpectedValueException();
    }
    elseif(preg_match('/^[0-9]+(?:\.[0-9]{0,2})?$/', $amount) == FALSE)
    {
      throw new UnexpectedValueException();
    }
    elseif($vwallet_balance < $amount){
        echo "<div class='alert bg-orange'>Oops! You have insufficient fund in your Wallet!!</div>";
    }
    elseif($tpin != $myvepin){
	    echo "<script>alert('Oops! Invalid Transaction Pin!'); </script>";
	}
    else{
      $search_cbalance = mysqli_query($link, "SELECT * FROM borrowers WHERE (account = '$account' OR phone = '$account')");
      $fetch_cbalance = mysqli_fetch_object($search_cbalance);
      $cust_wallet_balance = $fetch_cbalance->wallet_balance;
      $totalwallet_balance = $cust_wallet_balance + $amount;
      $ph = $fetch_cbalance->phone;
      $em = $fetch_cbalance->email;
      $myname = $fetch_cbalance->ln.' '.$fetch_cbalance->fn;
      
      $remain_merchantbalance = $vwallet_balance - $amount;
      $update = mysqli_query($link, "UPDATE vendor_reg SET wallet_balance = '$remain_merchantbalance' WHERE companyid = '$vendorid'");
      $update = mysqli_query($link, "UPDATE borrowers SET wallet_balance = '$totalwallet_balance' WHERE (account = '$account' OR phone = '$account')") or die (mysqli_error($link));
      $insert = mysqli_query($link, "INSERT INTO wallet_history VALUES(null,'$vendorid','$txid','$account','$amount','$vcurrency','$ptype','$remark','successful','$final_date_time')") or die (mysqli_error($link));
      if(!($update && $insert))
      {
        echo "<div class='alert bg-orange'>Unable to Transfer Fund.....Please try again later</div>";
      }
      else{
        include("alert_sender/p2p_alert.php");
        include("alert_sender/send_sp2ptransfer_alertemail.php");
        echo "<div align='center'><img src='../image/checkmark.gif'><p style='color: #38A1F3;'>Transaction Successful!</p><p style='color: #38A1F3;'>Current Wallet Balance: <b style='color: orange;'>".$currency.number_format($totalwallet_balance,2,'.',',')."</b></p></div>";
      }
    }
  }catch(UnexpectedValueException $ex)
  {
    echo "<div class='alert alert-danger'>Invalid Amount Entered!</div>";
  }
}*/
?>
<!--
             <div class="box-body">
      
      <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Account Number</label>
                  <div class="col-sm-10">
        <select name="author"  class="form-control select2" required>
          <option value="" selected>Select Customer Account</option>
<?php
$search = mysqli_query($link, "SELECT * FROM borrowers WHERE branchid = '$vcreated_by'");
while($get_search = mysqli_fetch_array($search))
{
?>
          <option value="<?php echo $get_search['account']; ?>"><?php echo $get_search['account']; ?>&nbsp; [<?php echo $get_search['fname']; ?>&nbsp;<?php echo $get_search['lname']; ?> : <?php echo $get_search['currency'].number_format($get_search['wallet_balance'],2,'.',','); ?>]</option>
<?php } ?>
        </select>
        </div>
            </div>
      
      <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Amount to Fund</label>
                  <div class="col-sm-10">
                  <input name="amount" type="number" class="form-control" placeholder="Enter Amount Here" required>
                  </div>
                  </div>
                  
        <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Transaction Pin</label>
                  <div class="col-sm-10">
                  <input name="tpin" type="password" class="form-control" maxlength="4" placeholder="Transaction Pin" required>
                  </div>
                  </div>

             <div class="form-group">
                <label for="" class="col-sm-2 control-label" style="color:blue;">Remark</label>
                <div class="col-sm-10">
                <textarea name="remark"  class="form-control" rows="4" cols="80" placeholder="e.g Manual Funding to Wallet from XXXXXX"></textarea>
                </div>
             </div>

       </div>
       
        <div align="right">
              <div class="box-footer">
                        <button name="csave" type="submit" class="btn bg-blue"><i class="fa fa-save">&nbsp;Fund</i></button>
              </div>
        </div>    
        
        -->
      
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
if(isset($_POST['asave']))
{
  function myreference($limit)
  {
    return substr(base_convert(sha1(uniqid(mt_rand())), 16, 36), 0, $limit);
  }
  try{
    $ptype = "p2p-transfer";
    $account =  mysqli_real_escape_string($link, $_POST['author']);
    $amount = mysqli_real_escape_string($link, $_POST['amount']);
    //$currency = mysqli_real_escape_string($link, $_POST['currency']);
    $remark = mysqli_real_escape_string($link, $_POST['remark']);
    //$remark .= "<br><b>Posted by:<br>".$name.'</b>';
    $date_time = date("Y-m-d");
    $final_date_time = date ('Y-m-d h:i:s', strtotime($date_time));
    $txid = 'EA-p2pFunding-'.myreference(10);
    $tpin = mysqli_real_escape_string($link, $_POST['tpin']);

    $search_memset = mysqli_query($link, "SELECT * FROM member_settings WHERE companyid = '$vcreated_by'");
    $fetch_memset = mysqli_fetch_array($search_memset);
      
    if($amount < 0){
      throw new UnexpectedValueException();
    }
    elseif(preg_match('/^[0-9]+(?:\.[0-9]{0,2})?$/', $amount) == FALSE)
    {
      throw new UnexpectedValueException();
    }
    elseif($vwallet_balance < $amount){
        echo "<div class='alert alert-danger'>Oops! You have insufficient fund in your Wallet!!</div>";
    }
    elseif($tpin != $myvepin){
	    echo "<script>alert('Oops! Invalid Transaction Pin!'); </script>";
	}
    else{
      $search_abalance = mysqli_query($link, "SELECT * FROM vendor_reg WHERE (companyid = '$account' OR official_phone = '$account')");
      $fetch_abalance = mysqli_fetch_object($search_abalance);
      $agent_wallet_balance = $fetch_abalance->wallet_balance;
      $totalwallet_balance = $agent_wallet_balance + $amount;
      $ph = $fetch_abalance->phone;
      $em = $fetch_abalance->email;
      $myname = $fetch_abalance->fname;

      if(mysqli_num_rows($search_abalance) == 1) {
        
        $remain_merchantbalance = $mwallet_balance - $amount;
        $update = mysqli_query($link, "UPDATE vendor_reg SET wallet_balance = '$remain_merchantbalance' WHERE companyid = '$vendorid'");
        $update = mysqli_query($link, "UPDATE vendor_reg SET wallet_balance = '$totalwallet_balance' WHERE (companyid = '$account' OR official_phone = '$account'))") or die (mysqli_error($link));
        $insert = mysqli_query($link, "INSERT INTO wallet_history VALUES(null,'$vcreated_by','$txid','$account','$amount','$vcurrency','$ptype','$remark','successful','$final_date_time','$vuid','$remain_merchantbalance','$totalwallet_balance')") or die (mysqli_error($link));
        if(!($update && $insert))
        {
          echo "<div class='alert bg-orange'>Unable to Transfer Fund.....Please try again later</div>";
        }
        else{
          //include("alert_sender/p2p_alert.php");
          include("alert_sender/send_sp2ptransfer_alertemail.php");
          echo "<div align='center'><img src='../image/checkmark.gif'><p style='color: #38A1F3;'>Transaction Successful!</p><p style='color: #38A1F3;'>Current Wallet Balance: <b style='color: orange;'>".$vcurrency.number_format($totalwallet_balance,2,'.',',')."</b></p></div>";
        }

      }else{
        echo "<div class='alert bg-orange'>Oops! Vendor ID / Phone Number does not exist!!</div>";
      }

    }
  }catch(UnexpectedValueException $ex)
  {
    echo "<div class='alert alert-danger'>Invalid Amount Entered!</div>";
  }
}
?>
             <div class="box-body">
      
      <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Vendor ID/Phone No.</label>
                  <div class="col-sm-10">
        <input name="author" type="text" class="form-control" placeholder="Enter Vendor ID OR Phone Number Here">
                  </div>
        </div>
      
      <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Amount to Fund</label>
                  <div class="col-sm-10">
                  <input name="amount" type="number" class="form-control" placeholder="Enter Amount Here">
                  </div>
                  </div>
                  
        <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Transaction Pin</label>
                  <div class="col-sm-10">
                  <input name="tpin" type="password" class="form-control" maxlength="4" placeholder="Transaction Pin" required>
                  </div>
                  </div>

             <div class="form-group">
                <label for="" class="col-sm-2 control-label" style="color:blue;">Remark</label>
                <div class="col-sm-10">
                <textarea name="remark"  class="form-control" rows="4" cols="80" placeholder="e.g Manual Funding to Wallet from Admin"></textarea>
                </div>
             </div>

       </div>
       
        <div align="right">
              <div class="box-footer">
                        <button name="asave" type="submit" class="btn bg-blue"><i class="fa fa-save">&nbsp;Fund</i></button>
              </div>
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