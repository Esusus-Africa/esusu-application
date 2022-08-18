<div class="row">	
		
	 <section class="content">
     
	        <div class="box box-success">
            <div class="box-body">
              <div class="table-responsive"> 
             <div class="box-body">
<?php
$id = $_GET['id'];
$lide = $_GET['lid'];
$select = mysqli_query($link, "SELECT * FROM loan_info WHERE id = '$id'") or die (mysqli_error($link));
$row = mysqli_fetch_array($select);
$loantype = $row['loantype'];  
$baccount = $row['baccount']; 

$search_cust = mysqli_query($link, "SELECT * FROM borrowers WHERE account = '$baccount'");
$get_cust = mysqli_fetch_array($search_cust);
$cemail = $get_cust['email'];

$acct_officer = $row['agent'];
	
$search_user = mysqli_query($link, "SELECT * FROM user WHERE id = '$acct_officer' OR name = '$acct_officer'");
$fetch_user = mysqli_fetch_array($search_user);
?>
			 <div class="col-md-14">
             <div class="nav-tabs-custom">
             <ul class="nav nav-tabs">
              <li <?php echo ($_GET['tab'] == 'tab_1') ? "class='active'" : ''; ?>><a href="updateloans.php?id=<?php echo $_GET['id']; ?>&&acn=<?php echo $_GET['acn']; ?>&&mid=<?php echo base64_encode("405"); ?>&&lid=<?php echo $_GET['lid']; ?>&&tab=tab_1">Loan Information</a></li>
              
              <?php
				 if($loantype == "Purchase"){
				?>
              <li <?php echo ($_GET['tab'] == 'tab_2a') ? "class='active'" : ''; ?>><a href="updateloans.php?id=<?php echo $_GET['id']; ?>&&acn=<?php echo $_GET['acn']; ?>&&mid=<?php echo base64_encode("405"); ?>&&lid=<?php echo $_GET['lid']; ?>&&tab=tab_2a">Asset Information</a></li>
              <?php
				}else{
				    echo "";
			    }
				?>
				
              <li <?php echo ($_GET['tab'] == 'tab_2b') ? "class='active'" : ''; ?>><a href="updateloans.php?id=<?php echo $_GET['id']; ?>&&acn=<?php echo $_GET['acn']; ?>&&mid=<?php echo base64_encode("405"); ?>&&lid=<?php echo $_GET['lid']; ?>&&tab=tab_2b">Guarantor Information</a></li>
              <li <?php echo ($_GET['tab'] == 'tab_2c') ? "class='active'" : ''; ?>><a href="updateloans.php?id=<?php echo $_GET['id']; ?>&&acn=<?php echo $_GET['acn']; ?>&&mid=<?php echo base64_encode("405"); ?>&&lid=<?php echo $_GET['lid']; ?>&&tab=tab_2c">Upload Document</a></li>
              <li <?php echo ($_GET['tab'] == 'tab_5') ? "class='active'" : ''; ?>><a href="updateloans.php?id=<?php echo $_GET['id']; ?>&&acn=<?php echo $_GET['acn']; ?>&&mid=<?php echo base64_encode("405"); ?>&&lid=<?php echo $_GET['lid']; ?>&&tab=tab_5">Repayment Schedule</a></li>
              <?php echo ($iremitaMerchantId === "" || $iremitaApiKey === "" || $iremitaServiceId === "" || $iremitaApiToken === "") ? "" : '<li '.(($_GET['tab'] == "tab_3") ? "class='active'" : "").'><a href="updateloans.php?id='.$_GET['id'].'&&acn='.$_GET['acn'].'&&mid='.base64_encode('405').'&&lid='.$_GET['lid'].'&&tab=tab_3">Direct Debit Activation <i><b>(for Autocharge)</b></i></a></li>'; ?>
              <li <?php echo ($_GET['tab'] == "tab_4") ? "class='active'" : ''; ?>><a href="#">Loan Bal: <b><?php echo ($row['balance'] == "") ? $icurrency."0.0" : $icurrency.number_format($row['balance'],2,'.',','); ?></b></a></li>  
            </ul>
             <div class="tab-content">
                 
    <?php
	if(isset($_GET['tab']) == true)
	{
	    $acn = $_GET['acn'];
		$lid = $_GET['lid'];
		$search_customer = mysqli_query($link, "SELECT * FROM borrowers WHERE account = '$acn'");
        $get_customer = mysqli_fetch_object($search_customer);
            
        $search_mloan = mysqli_query($link, "SELECT * FROM loan_info WHERE lid = '$lid'");
		$get_mloan = mysqli_fetch_object($search_mloan);
		$mandate_status = $get_mloan->mandate_status;

		$select1 = mysqli_query($link, "SELECT * FROM systemset") or die (mysqli_error($link));
		$row1 = mysqli_fetch_object($select1);
			
		$tab = $_GET['tab'];
		if($tab == 'tab_1')
		{
		?>
             <div class="tab-pane <?php echo ($_GET['tab'] == 'tab_1') ? 'active' : ''; ?>" id="tab_1">
           
			 <form class="form-horizontal" method="post" enctype="multipart/form-data">
	
	<?php
    if(isset($_POST['loansave']))
    {
        
        $id = $_GET['id'];
        $lproduct =  mysqli_real_escape_string($link, $_POST['lproduct2']);
        $amt = preg_replace('/[^0-9.]/', '', mysqli_real_escape_string($link, $_POST['amount']));
        $income_amt = preg_replace('/[^0-9.]/', '', mysqli_real_escape_string($link, $_POST['income']));
        $salary_date = mysqli_real_escape_string($link, $_POST['salary_date']);
        $employer =  mysqli_real_escape_string($link, $_POST['employer']);
        $lreasons = mysqli_real_escape_string($link, $_POST['lreasons']);
        $status = mysqli_real_escape_string($link, $_POST['status']);
        
        $search_interest = mysqli_query($link, "SELECT * FROM loan_product WHERE id = '$lproduct'");
        $get_interest = mysqli_fetch_object($search_interest);
        $max_duration  = $get_interest->duration;
        $interest_type = $get_interest->interest_type;
        $interest = preg_replace('/[^0-9.]/', '', $get_interest->interest)/100;
        $tenor = $get_interest->tenor;
        
        $amount_topay = ($interest == "0" || $interest_type == "Reducing Balance") ? $amt : (($amt * $interest) + $amt);
        
        $searchOStock = mysqli_query($link, "SELECT SUM(total_amount) FROM outgoing_stock WHERE merchantid = '$institution_id' AND lid = '$lid'");
        $get_OStock = mysqli_fetch_array($searchOStock);
        $currentOSAmt = $get_OStock['SUM(total_amount)'];

        if($currentOSAmt > $amt && $loantype == "Purchase"){
            
            echo "<div class='alert bg-orange'>Sorry!...You are require to reduce the asset value before downgrading the loan amount!!</div>";
        
        }else{
        
            mysqli_query($link, "UPDATE loan_info SET amount = '$amt', income = '$income_amt', salary_date = '$salary_date', employer = '$employer', remark = '$lreasons', amount_topay = '$amount_topay', balance = '$amount_topay', status = '$status' WHERE id = '$id'");
            
            echo "<div class='alert bg-blue'>Loan Information Update Successfully!</div>";
    		echo '<meta http-equiv="refresh" content="5;url=updateloans.php?id='.$id.'&&mid='.base64_encode("404").'&&acn='.$_GET['acn'].'&&mid=NDA1&&lid='.$_GET['lid'].'&&tab=tab_1">';
    		
        }
        
    }
    ?>
    
             <div class="box-body">
                 
			 <div class="form-group">
                <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">Borrower</label>
				 <div class="col-sm-10">
				<?php
				$get = mysqli_query($link, "SELECT * FROM borrowers WHERE account = '$baccount'") or die (mysqli_error($link));
				while($rows = mysqli_fetch_array($get))
				{
				?>
					<input name="borrower" type="text" class="form-control" value=<?php echo $rows['fname'].'&nbsp;'.$rows['lname']; ?> readonly>
				<?php } ?>
              </div>
			  </div>
			  
			 <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">Account</label>
                  <div class="col-sm-10">
                  <input name="account" type="text" class="form-control" value="<?php echo $row['baccount']; ?>" readonly>
                  </div>
                  </div>
			  
			<div class="form-group">
		              <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">Loan Product</label>
		  			 <div class="col-sm-10">
		              <select name="lproduct2" class="select2" id="loan_products2" style="width: 100%;" readonly>
		  	           <?php
		  	  			$id = $_GET['id'];
		  	  			$getin = mysqli_query($link, "SELECT * FROM loan_info WHERE id='$id' AND branchid='$institution_id'") or die (mysqli_error($link));
		  	  			$row1 = mysqli_fetch_array($getin);
						$idp = $row1['lproduct'];
							
			  	  		$search_lp = mysqli_query($link, "SELECT * FROM loan_product WHERE id='$idp' AND merchantid='$institution_id'") or die (mysqli_error($link));
			  	  		$row_lp = mysqli_fetch_array($search_lp);
		  				echo '<option value="'.$row_lp['id'].'" selected="selected">'.$row_lp['pname'].' - '.'(Interest Rate: '. $row_lp['interest'].'% per month)'.'</option>';
		  	  			?>
		              <?php
		  				$getin2 = mysqli_query($link, "SELECT * FROM loan_product WHERE merchantid='$institution_id' order by id") or die (mysqli_error($link));
		  				while($row2 = mysqli_fetch_array($getin2))
		  				{
		  				echo '<option value="'.$row2['id'].'">'.$row2['pname'].' - '.'(Interest Rate: '. $row2['interest'].'% per month)'.'</option>';
		  				}
		  				?>
		              </select>
		            </div>
		  	</div>
				  
	    	<span id='ShowValueFrank'></span>
	    	<span id='ShowValueFrank'></span>
			
	  		<input name="lid" type="hidden" class="form-control" value="<?php echo $_GET['lid']; ?>" readonly>
				 
		<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">Amount</label>
                  <div class="col-sm-10">
                  <input name="amount" type="text" class="form-control" value="<?php echo $row['amount']; ?>" required>
                  </div>
                  </div>
				  
		<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">Salary Income</label>
                  <div class="col-sm-10">
                  <input name="income" type="text" class="form-control" value="<?php echo $row['income']; ?>" required>
                  </div>
                  </div>
				  
		<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">Salary Date</label>
                  <div class="col-sm-10">
	               <div class="input-group date">
	                  <div class="input-group-addon">
	                    <i class="fa fa-calendar"></i>
	                  </div>
                  <input name="salary_date" type="text" class="form-control" value="<?php echo $row['salary_date']; ?>" required>
			  </div>
             </div>
           </div>
				  
		<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">Employer Name</label>
                  <div class="col-sm-10">
                  <input name="employer" type="text" class="form-control" value="<?php echo $row['employer']; ?>" required>
                  </div>
                  </div>
			  
			 <div class="form-group">
                  	<label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">Disbursement Info</label>
                  	<div class="col-sm-10">
					<textarea name="bacinfo"  class="form-control" rows="4" cols="80" readonly><?php echo $row['descs']; ?></textarea>
           			</div>
          	 </div>
			
						 
			<div class="form-group">
                 	<label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Reasons for Loan.</label>
                 	<div class="col-sm-10">
                <select name="lreasons" class="form-control select2" style="width: 100%;" required>
                    <option value="<?php echo $row['remark']; ?>" selected="selected"><?php echo $row['remark']; ?></option>
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
          			 </div>
				 </div>
			  
			<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">Account Officer</label>
                  <div class="col-sm-10">
                  <input name="agent" type="text" class="form-control" value="<?php echo $fetch_user['name']; ?>" readonly>
                  </div>
                  </div>
				  
            <div class="form-group">
                 	<label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Status</label>
                 	<div class="col-sm-10">
                <select name="status" class="form-control select2" style="width: 100%;" required>
                    <option value="<?php echo $row['status']; ?>" selected="selected"><?php echo $row['status']; ?></option>
	                <option value="UnderReview">UnderReview</option>
	                <option value="Pending">Pending</option>
                </select>
          			 </div>
				 </div>
                  
                  
				  
			 </div>	
			 
			 <div align="right">
              <div class="box-footer">
                	<button name="loansave" type="submit" class="btn bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>"><i class="fa fa-save">&nbsp;Update</i></button>
              </div>
			  </div>
			 
			  </form>

              </div>
              <!-- /.tab-pane -->
		
	
	<?php
	}
	elseif($tab == 'tab_2a' && $loantype == "Purchase")
	{
	?>
    
        <div class="tab-pane <?php echo ($_GET['tab'] == 'tab_2a') ? 'active' : ''; ?>" id="tab_2a">
	            
    			<form class="form-horizontal" method="post" enctype="multipart/form-data">
    			    
        <?php
        if(isset($_POST['addProduct'])){
        
              $lid = $_GET['lid'];
              $skuCode = mysqli_real_escape_string($link, $_POST['skuCode']);
              $my_qty = mysqli_real_escape_string($link, $_POST['qty']);
              $loanRequestAmount = $row['amount'];
              $currenctdate = date("Y-m-d h:i:s");
              
              $searchStock = mysqli_query($link, "SELECT * FROM loan_stock WHERE merchantid = '$institution_id' AND skuCode = '$skuCode'");
              $get_Stock = mysqli_fetch_array($searchStock);
        	  $item_name = $get_Stock['item_name'];
              $amount = $get_Stock['amount'];
              $availableQty = $get_Stock['qty'];
              $totalAmt = $amount * $my_qty;
              $balanceQty =  $availableQty - $my_qty;
              $updateStatus = ($balanceQty == 0) ? "OutOfStock" : "Available";
        
        	  $searchOStock = mysqli_query($link, "SELECT SUM(total_amount) FROM outgoing_stock WHERE merchantid = '$institution_id' AND lid = '$lid'");
              $get_OStock = mysqli_fetch_array($searchOStock);
              $currentOSAmt = $get_OStock['SUM(total_amount)'];
              $totalOSAmt = $currentOSAmt + $totalAmt;
        
              if($my_qty > $availableQty){
        
                    echo "<div class='alert bg-orange'>Sorry!...You cannot add more than the quantity available in the Stock!</div>";
        
              }elseif($totalAmt > $loanRequestAmount || $totalOSAmt > $loanRequestAmount){
        
                    echo "<div class='alert bg-orange'>Opps!...The value of the items is more than the total amount requested for in the previous form!!</div>";
        
              }else{
        
                    mysqli_query($link, "INSERT INTO outgoing_stock VALUES(null,'$institution_id','$isbranchid','$lid','$skuCode','$item_name','$my_qty','$amount','$totalAmt','Pending','$currenctdate')");
                    mysqli_query($link, "UPDATE loan_stock SET qty = '$balanceQty', status = '$updateStatus' WHERE merchantid = '$institution_id' AND skuCode = '$skuCode'");
        
                    echo "<div class='alert bg-blue'>Items Added Successfully!!</div>";
        
              }
        
        }
        ?>
    			    
                    <div class="box-body">

                        <div class="form-group">
                              <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Stock Items</label>
                              <div class="col-sm-10">
                                    <select name="skuCode"  class="form-control select2" required>
                                          <option value="" selected='selected'>Select Item&hellip;</option>
                                          <?php
                                          $search = mysqli_query($link, "SELECT * FROM loan_stock WHERE merchantid = '$institution_id' AND status = 'Available'");
                                          while($get_search = mysqli_fetch_array($search))
                                          {
                                          ?>
                                          <option value="<?php echo $get_search['skuCode']; ?>"><?php echo $get_search['skuCode'].' - '.$get_search['item_name'].' - '.number_format($get_search['amount'],2,'.',','); ?></option>
                                          <?php } ?>
                                    </select>
                              </div>
                        </div>
                              
                        <div class="form-group">
                              <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Quantity</label>
                              <div class="col-sm-10">
                              <input name="qty" type="number" inputmode="numeric" pattern="[0-9]*" class="form-control" required placeholder = "Enter Quantity" required>
                              </div>
                              </div>

                    </div>

                  <div align="right">
				<div class="box-footer">
					<button name="addProduct" type="submit" class="btn bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>"><i class="fa fa-plus"> Add Cart</i></button>
				</div>
			</div>
			  
                
                  <hr>
                
                
                  <table id="example1" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                        <th><input type="checkbox" id="select_all"/></th>
                        <th>Action</th>
                        <th>SKU Code</th>
                        <th>Items Picture</th>
                        <th>Items Name</th>
                        <th>Qty</th>
                        <th>Units Price</th>
                        <th>Total Amount</th>
                  </tr>
                  </thead>
                  <tbody>
<?php
$lid = $_GET['lid'];
$select = mysqli_query($link, "SELECT * FROM outgoing_stock WHERE lid = '$lid' ORDER BY id DESC") or die (mysqli_error($link));
if(mysqli_num_rows($select)==0)
{
echo "<div class='alert bg-".(($myaltrow['theme_color'] == "") ? 'blue' : $myaltrow['theme_color'])."'>No data found yet!.....Check back later!!</div>";
}
else{
while($row = mysqli_fetch_array($select))
{
$ide = $row['id'];
$sku_Code = $row['skuCode'];
?>    
                <tr>
                  <td><input id="optionsCheckbox" class="checkbox" name="selector[]" type="checkbox" value="<?php echo $id; ?>"></td>
                    <td>
                        <div class='btn-group'>
                            <div class='btn-group'>
                            <button type='button' class='btn bg-blue btn-flat dropdown-toggle' data-toggle='dropdown'>
                            <span class='caret'></span>
                            </button>
                            <ul class='dropdown-menu'>
                                <?php echo ($delete_loan_product_info == "1") ? "<li><p><a href='updateloans.php?id=".$_GET['id']."&&lid=".$lid."&&myidm=".$ide."&&acn=".$_GET['acn']."&&mid=NDA1&&tab=tab_2a' class='btn btn-default btn-flat'><i class='fa fa-times'></i> Delete</i></a></p></li>" : ""; ?>
                            </ul>
                            </div>
                        </div>
                    </td>
                  <td><b><?php echo $sku_Code; ?></b></td>
                  <td>
                  <?php
                    $i = 0;
                    $search_file = mysqli_query($link, "SELECT * FROM stock_items WHERE skuCode = '$sku_Code'") or die ("Error: " . mysqli_error($link));
                    if(mysqli_num_rows($search_file) == 0){
                    	echo "<span style='color: ".(($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color'])."'>---</span>";
                    }else{
                    	while($get_file = mysqli_fetch_array($search_file)){
                    		$i++;
                    ?>
                    <div><a href="<?php echo $fetchsys_config['file_baseurl'].$get_file['item_path']; ?>" target="_blank"><img src="../img/file_attached.png" width="20" height="20"><i class="fa fa-eye"></i> Picture<?php echo $i; ?></a></div>
                    <?php
                    	}
                    }
                  ?>
                  </td>
                  <td><?php echo $row['item_name']; ?></td>
    			  <td><b><?php echo $row['qty']; ?></b></td>
    			  <td><?php echo number_format($row['amount'],2,'.',','); ?></td>
    			  <td><b><?php echo number_format($row['total_amount'],2,'.',','); ?></b></td>
                </tr>
<?php } } ?>
             </tbody>
                </table>

<?php
if(isset($_GET['myidm'])){

      $myidm = $_GET['myidm'];

	  $selectOS = mysqli_query($link, "SELECT * FROM outgoing_stock WHERE id = '$myidm'");
	  $fetchOS = mysqli_fetch_array($selectOS);
	  $skuCode = $fetchOS['skuCode'];
	  $OSQty = $fetchOS['qty'];

	  $selectLS = mysqli_query($link, "SELECT * FROM loan_stock WHERE skuCode = '$skuCode'");
	  $fetchLS = mysqli_fetch_array($selectLS);
	  $curQty = $fetchLS['qty'];
	  $balQty = $curQty + $OSQty;
	
	  mysqli_query($link, "UPDATE loan_stock SET qty = '$balQty', status = 'Available' WHERE skuCode = '$skuCode'");
      mysqli_query($link, "DELETE FROM outgoing_stock WHERE id = '$myidm'");
      echo "<script>alert('Cart Delete Successfully!'); </script>";
	  echo '<script>window.location="updateloans.php?id='.$_GET['id'].'&&acn='.$_GET['acn'].'&&mid=NDA1&&lid='.$_GET['lid'].'&&tab=tab_2a"; </script>';

}
?>
			        
			</form>
			
		</div>
		<!-- /.tab-pane -->

    <?php
	}
	elseif($tab == 'tab_2b')
	{
	?>
	
		<div class="tab-pane <?php echo ($_GET['tab'] == 'tab_2b') ? 'active' : ''; ?>" id="tab_2b">
	            
			<form class="form-horizontal" method="post" enctype="multipart/form-data">
			    
			    <div class="box-body">
			        
            <?php
            if(isset($_POST['addComment'])){
                
                $lid = $_GET['lid'];
                $revG = $_GET['revG'];
                $gstatus = mysqli_real_escape_string($link, $_POST['gstatus']);
                $gcoment = mysqli_real_escape_string($link, $_POST['gcoment']);
            	
            	mysqli_query($link, "UPDATE loan_guarantor SET gcomment = '$gcoment', gstatus = '$gstatus' WHERE id = '$revG'");
                
                echo "<div class='alert bg-blue'>Guarantor $gstatus Successfully!!</div>";
                echo '<meta http-equiv="refresh" content="5;url=updateloans.php?id='.$_GET['id'].'&&acn='.$_GET['acn'].'&&mid='.base64_encode('405').'&&lid='.$lid.'&&tab=tab_2b">';
                
            }
            ?>
    			   
    			 <?php
    			 if(isset($_GET['revG'])){
    			 ?>
    			 
    			 <div class="box-body">
    			     
    			    <div class="form-group">
                        <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Status</label>
                        <div class="col-sm-10">
        				<select name="gstatus" class="form-control select2" style="width: 100%;" required>
        				    <option value="" selected="selected">--Select Status--</option>
        	                <?php echo ($accept_loan_guarantor == "1") ? '<option value="Accepted">Accept</option>' : ''; ?>
        	                <?php echo ($reject_loan_guarantor == "1") ? '<option value="Rejected">Reject</option>' : ''; ?>
                        </select>
                  		</div>
        			</div>
    				 
    				<div class="form-group">
                      	<label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Comment</label>
                      	<div class="col-sm-10">
    					<textarea name="gcoment" class="form-control" rows="4" cols="80" required></textarea>
               			 </div>
              	    </div> 
              	    
              	</div>
			 
    			<div class="form-group" align="right">
                    <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;"></label>
                    <div class="col-sm-7">
                    </div>
                    <label for="" class="col-sm-3 control-label"><button name="addComment" type="submit" class="btn bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>"><i class="fa fa-save">&nbsp;Submit</i></button></label>
                </div>
    			 
    			 <?php
    			 }
    			 else{
    			     echo "";
    			 }
    			 ?>
			        
			    <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th><input type="checkbox" id="select_all"/></th>
                  <th>Action</th>
                  <th>Picture</th>
				  <th>Guarantor Name</th>
				  <th>Guarantor Phone</th>
                  <th>Relationship</th>
				  <th>Guarantor BVN</th>
                  <th>Guarantor Address</th>
                  <th>Status</th>
                 </tr>
                </thead>
                <tbody>
<?php
$lid = $_GET['lid'];
$select = mysqli_query($link, "SELECT * FROM loan_guarantor WHERE lid = '$lid' ORDER BY id DESC") or die (mysqli_error($link));
if(mysqli_num_rows($select)==0)
{
echo "<div class='alert bg-".(($myaltrow['theme_color'] == "") ? 'blue' : $myaltrow['theme_color'])."'>No data found yet!.....Check back later!!</div>";
}
else{
while($row = mysqli_fetch_array($select))
{
$id = $row['id'];
?>    
                <tr>
                    <td><input id="optionsCheckbox" class="checkbox" name="selector[]" type="checkbox" value="<?php echo $id; ?>"></td>
                    <td>
                        <div class='btn-group'>
                            <div class='btn-group'>
                            <button type='button' class='btn bg-blue btn-flat dropdown-toggle' data-toggle='dropdown'>
                            <span class='caret'></span>
                            </button>
                            <ul class='dropdown-menu'>
                                <?php echo (($accept_loan_guarantor == "1" || $reject_loan_guarantor == "1") && $row['gstatus'] == "Pending") ? "<li><p><a href='updateloans.php?id=".$_GET['id']."&&revG=".$id."&&acn=".$_GET['acn']."&&mid=".base64_encode('405')."&&lid=".$_GET['lid']."&&tab=tab_2b' class='btn btn-default btn-flat'><i class='fa fa-check'> Accept/Reject</i></a></p></li>" : ""; ?>
                                <?php echo ($verify_bvn == 1) ? '<li><p><a href="bvnValidation.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("944").'&&tab=tab_1" target="_blank" class="btn btn-default btn-flat"><i class="fa fa-search"> Verify BVN</i></a></p></li>' : ''; ?>
                            </ul>
                            </div>
                        </div>
                    </td>
                    <td><a href="<?php echo $fetchsys_config['file_baseurl'].$row['picture']; ?>" class="btn bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>" target="blank"><i class="fa fa-search"></i> View Picture</a></td>
                    <td><?php echo $row['gname']; ?></td>
    				<td><?php echo $row['gphone']; ?></td>
    				<td><?php echo $row['grela']; ?></td>
    				<td><?php echo $row['gbvn']; ?></td>
    				<td><?php echo $row['gaddress']; ?></td>
    				<td><?php echo ($row['gstatus'] == "Accepted" ? "<span class='label bg-blue'><i class='fa fa-check'></i> ".$row['gstatus']."</span>" : ($row['gstatus'] == "Pending" ? "<span class='label bg-orange'><i class='fa fa-exclamation'></i> ".$row['gstatus']."</span>" : "<span class='label bg-red'><i class='fa fa-times'></i> ".$row['gstatus']."</span>")); ?></td>
                </tr>
<?php } } ?>
             </tbody>
                </table>
			        
			    </div>
			    
			</form>
			
		</div>

    
        <?php
	}
	elseif($tab == 'tab_2c')
	{
	?>
	
		<div class="tab-pane <?php echo ($_GET['tab'] == 'tab_2c') ? 'active' : ''; ?>" id="tab_2c">
	            
        <form class="form-horizontal" method="post" enctype="multipart/form-data">

<?php
if(isset($_POST['addDoc'])){
    
    $userIde = $_GET['id'];
	$userid = $_GET['acn'];
    $lid = $_GET['lid'];
	$docType = mysqli_real_escape_string($link, $_POST['docType']);
	$exp_date = mysqli_real_escape_string($link, $_POST['exp_date']);
    $date_time = date("Y-m-d h:i:s");

    $sourcepath = $_FILES["id_file"]["tmp_name"];
	$targetpath = "../img/" . $_FILES["id_file"]["name"];
	move_uploaded_file($sourcepath,$targetpath);
	
	$location = $_FILES['id_file']['name']; 
	
	mysqli_query($link, "INSERT INTO loan_required_doc VALUES(null,'$institution_id','$isbranchid','$iuid','$lid','$userIde','$userid','$docType','$location','$exp_date','Pending','$date_time','$date_time')");
    
    echo "<div class='alert bg-blue'>Document Added Successfully!!</div>";

}
?>
    			 
    			 <div class="box-body">

				 	<div class="form-group">
                      <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Document Type</label>
                      <div class="col-sm-10">
						<select name="docType"  class="form-control select2" required>
						<option value="" selected="selected">Select Document Type</option>
						<option value="Monthly_Statements">Monthly Statements</option>
						<option value="Credit_History">Credit History</option>
						<option value="Valid_ID_Card">Valid ID Card</option>
						<option value="Utility_Bill">Utility Bill</option>
						</select>
						</div>
						</div>

					<div class="form-group">
    				    <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Upload Doc.</label>
    				    <div class="col-sm-10">
						<input name="id_file" type="file" class="btn bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>" required/>
    			        </div>
    			    </div>
    			    
    			    <div class="form-group">
                      <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Expiry Date</label>
                      <div class="col-sm-10">
                      <input name="exp_date" type="date" class="form-control">
                      </div>
                    </div>
              	    
              	</div>

				<div align="right">
					<div class="box-footer">
						<button name="addDoc" type="submit" class="btn bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>"><i class="fa fa-upload"> Upload</i></button>
					</div>
				</div>
			  
            </form>
            
                <hr>

                <form class="form-horizontal" method="post" enctype="multipart/form-data">
				
				<?php echo ($approve_loan_document == '1') ? '<button type="submit" class="btn bg-'.(($myaltrow['theme_color'] == "") ? "blue" : $myaltrow['theme_color']).'" name="approveDoc"><i class="fa fa-check"></i> Approve</button>' : ''; ?>
				<?php echo ($decline_loan_document == '1') ? '<button type="submit" class="btn bg-'.(($myaltrow['alternate_color'] == "") ? "orange" : $myaltrow['alternate_color']).'" name="declineDoc"><i class="fa fa-times"></i> Decline</button>' : ''; ?>

                <hr>

				<table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th><input type="checkbox" id="select_all"/></th>
				  <th>DocType</th>
				  <th>Document</th>
                  <th>Expiry Date</th>
				  <th>Status</th>
                  <th>createdOn</th>
                  <th>updatedOn</th>
                 </tr>
                </thead>
                <tbody id='loan_required_doc'></tbody>
                </table>

						<?php
						if(isset($_POST['approveDoc'])){
							
							$idm = $_GET['id'];
							$id=$_POST['selector'];
							$N = count($id);

							if($id == ''){
								echo "<script>alert('Row Not Selected!!!'); </script>";	
								echo "<script>window.location='uploadloans.php?id=".$_SESSION['tid']."&&lid=".$_GET['lid']."&&mid=NDA1&&&tab=tab_2a'; </script>";
							}
							else{
								for($i=0; $i < $N; $i++)
								{
									$date_time = date("Y-m-d h:i:s");
									$result = mysqli_query($link,"UPDATE loan_required_doc SET docStatus = 'Approved', dateUpdated = '$date_time' WHERE id ='$id[$i]'");
									echo "<script>alert('Document Approved Successfully!!!'); </script>";
									echo "<script>window.location='uploadloans.php?id=".$_SESSION['tid']."&&lid=".$_GET['lid']."&&mid=NDA1&&tab=tab_2a'; </script>";
								}
							}
						}

						if(isset($_POST['declineDoc'])){
							
							$idm = $_GET['id'];
							$id = $_POST['selector'];
							$N = count($id);

							if($id == ''){
								echo "<script>alert('Row Not Selected!!!'); </script>";
								echo "<script>window.location='uploadloans.php?id=".$_SESSION['tid']."&&lid=".$_GET['lid']."&&mid=NDA1&&tab=tab_2a'; </script>";
							}
							else{
								for($i=0; $i < $N; $i++)
								{
									$date_time = date("Y-m-d h:i:s");
									$result = mysqli_query($link,"UPDATE loan_required_doc SET docStatus = 'Declined', dateUpdated = '$date_time' WHERE id ='$id[$i]'");
									echo "<script>alert('Document Declined Successfully!!!'); </script>";
									echo "<script>window.location='uploadloans.php?id=".$_SESSION['tid']."&&lid=".$_GET['lid']."&&mid=NDA1&&tab=tab_2a'; </script>";
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
	
        <?php
        if($iremitaMerchantId === "" || $iremitaApiKey === "" || $iremitaServiceId === "" || $iremitaApiToken === "")
        {
            echo "";
        }
        else{
        ?>
		
	       <div class="tab-pane <?php echo ($_GET['tab'] == 'tab_3') ? 'active' : ''; ?>" id="tab_3">
	                  
	       <form class="form-horizontal" method="post" enctype="multipart/form-data">
	           
			<?php
			if($mandate_status === "InProcess" || $mandate_status === "Activated" || $mandate_status === "Stop")
			{	
			    include ("../config/restful_apicalls.php");
			    
			    $systemset = mysqli_query($link, "SELECT * FROM systemset");
				$row1 = mysqli_fetch_object($systemset);
  
				$result = array();
                $requestId = $get_mloan->request_id;
                $mandateId = $get_mloan->mandate_id;
				
				//REMITAL CREDENTIALS
                $remita_merchantid = $fetch_icurrency->remitaMerchantId;
                $remita_apikey = $fetch_icurrency->remitaApiKey;
                $remita_serviceid = $fetch_icurrency->remitaServiceId;
                $api_token = $fetch_icurrency->remitaApiToken;

                $concat_param = $mandateId.$remita_merchantid.$requestId.$remita_apikey;
                $hash = hash("sha512", $concat_param);

                $search_restapi = mysqli_query($link, "SELECT * FROM restful_apisetup WHERE api_name = 'remita'");
                $fetch_restapi = mysqli_fetch_object($search_restapi);
                $url = $fetch_restapi->api_url;

                $api_url = $url."remita/exapp/api/v1/send/api/echannelsvc/echannel/mandate/status";

                $postdata = array(
                    'merchantId' => $remita_merchantid,
                    'mandateId' => $mandateId,
                    'hash'  => $hash,
                    'requestId' => $requestId
                );

                $make_call = callAPI('POST', $api_url, json_encode($postdata));
                
                $output2 = trim(json_decode(json_encode($make_call), true),'jsonp ();');
                $result = json_decode($output2, true);

				if($result['isActive'] == true){
				    
					mysqli_query($link, "UPDATE loan_info SET mandate_status = 'Activated' WHERE lid = '$lid'");
					
					echo "<p>Remita Retrieval Reference: <b>".$result['mandateId']."</b></p>";
                    echo "<p>Request ID: <b>".$result['requestId']."</b></p>";
                    echo "<p>Start Date: <b>".$result['startDate']."</b></p>";
                    echo "<p>End Date: <b>".$result['endDate']."</b></p>";
                    echo "<p style='color: ".(($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color'])."'><i class='fa fa-check'></i><b> Direct Debit Mandate Activated Successfully!</b></p>";
                    echo ($mandate_status === "Activated") ? '<button type="submit" class="btn bg-'.(($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']).'" name="stop_mandate"><i class="fa fa-times"></i>&nbsp;Stop Mandate</button>' : '';
				    
				}
				else{
					    
				    $concat_param3 = $remita_merchantid.$remita_apikey.$requestId;
                    $hash3 = hash("sha512", $concat_param3);
            
                    $api_url3 = $url."remita/ecomm/mandate/form/$remita_merchantid/$hash3/$mandateId/$requestId/rest.reg";
					    
				    // the transaction was not successful, do not deliver value'
				    // print_r($result);  //uncomment this line to inspect the result, to check why it failed.
                    echo "<p>Direct Debit Activation is still <b>under processing</b> by the Bank</p>";
                    echo "<p>Remita Retrieval Reference: <b>".$result['mandateId']."</b></p>";
                    echo "<p>Request ID: <b>".$result['requestId']."</b></p>";
                    echo "<p>Mandate Status: <b>$get_mloan->mandate_status</b></p>";
                    echo "<a href='$api_url3' target='_blank'><button type='button' class='btn bg-".(($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color'])."'><i class='fa fa-forward'></i>&nbsp;Click Here</button></a> to Re-Print <b>RRR Mandate Form</b></p>";

			    }
			?>
			
<?php
if(isset($_POST['stop_mandate'])){
    
    include ("../config/restful_apicalls.php");
    
    $requestId = $get_mloan->request_id;
    $mandateId = $get_mloan->mandate_id;
				
	//REMITAL CREDENTIALS
    $remita_merchantid = $fetch_icurrency->remitaMerchantId;
    $remita_apikey = $fetch_icurrency->remitaApiKey;

    $concat_param = $mandateId.$remita_merchantid.$requestId.$remita_apikey;
    $hash = hash("sha512", $concat_param);
    
    $search_restapi = mysqli_query($link, "SELECT * FROM restful_apisetup WHERE api_name = 'remita'");
    $fetch_restapi = mysqli_fetch_object($search_restapi);
    $url = $fetch_restapi->api_url;

    $api_url = $url."remita/exapp/api/v1/send/api/echannelsvc/echannel/mandate/stop";
    
    $postdata = array(
        'merchantId' => $remita_merchantid,
        'mandateId' => $mandateId,
        'hash'  => $hash,
        'requestId' => $requestId
        );

    $make_call = callAPI('POST', $api_url, json_encode($postdata));
                
    $output2 = trim(json_decode(json_encode($make_call), true),'jsonp ();');
    $result = json_decode($output2, true);
    
    if($result['statuscode'] === "00"){
        
        mysqli_query($link, "UPDATE loan_info SET mandate_status = 'Stop' WHERE lid = '$lid'");
        echo "<script>alert('Mandate Deactivated Successfully!!!'); </script>";
        echo "<script>window.location='updateloans.php?id=".$_GET['id']."&&acn=".$_GET['acn']."&&mid=NDA1&&lid=".$_GET['lid']."&&tab=tab_3'; </script>";
        
    }
    else{
        
        echo "<script>alert('Unable to Deactivated Mandate!'); </script>";
        echo "<script>window.location='updateloans.php?id=".$_GET['id']."&&acn=".$_GET['acn']."&&mid=NDA1&&lid=".$_GET['lid']."&&tab=tab_3'; </script>";
        
    }
    
}
?>
			
		<?php
		}
		else{
			?>
				  <br>
				  <p style="color: <?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>;"><i class="fa fa-times"></i><b> Direct Debit Not Yet Activated</b></p>

                  <form >
				    <a href="verify_card.php?id=<?php echo $_GET['id']; ?>&&acn=<?php echo $acn; ?>&&mid=NDA1&&lid=<?php echo $lid; ?>&&tab=tab_3"><button type="button" class="btn bg-blue"><i class="fa fa-refresh"></i> Proceed to Activate Direct Debit! </button></a>
				  </form>
	<?php
	$select1 = mysqli_query($link, "SELECT * FROM systemset") or die (mysqli_error($link));
	$row1 = mysqli_fetch_object($select1);
	?>
				  <p style="color: <?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>;"><b><i>Note that you must verify customer Bank Account details to allow direct debit.</i></b></p>
		<?php
	    }
		?>
		
              </div>
              
    </form>
              
        <?php
        }
        ?>
             
			  
	<?php
	}
	elseif($tab == 'tab_5')
	{
	?>
			  
			  <div class="tab-pane <?php echo ($_GET['tab'] == 'tab_5') ? 'active' : ''; ?>" id="tab_5">
			<form class="form-horizontal" method="post" enctype="multipart/form-data">
			    <div align="center"><h3><b>Loan Repayment Schedule</b></h3></div>
			<div class="box-body">

<?php
$lid = $_GET['lid'];
$searchin = mysqli_query($link, "SELECT * FROM payment_schedule WHERE lid = '$lid'") or die (mysqli_error($link));
$haveit = mysqli_fetch_array($searchin);
$idmet= $haveit['id'];
$lproduct = $haveit['lproduct'];

$search_mylp = mysqli_query($link, "SELECT * FROM loan_product WHERE id = '$lproduct'");
$fetch_mylp = mysqli_fetch_array($search_mylp);
$iintr_type = $fetch_mylp['interest_type'];
?>

				<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">Duration Type:</label>
                  <div class="col-sm-10">
				  <select name="d1" class="form-control" readonly>
				 <option value="<?php echo $haveit['term']; ?>"><?php echo $haveit['schedule']; ?></option>
				  </select>
                  </div>
                  </div>
                  
                 <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">Interest Type:</label>
                  <div class="col-sm-10">
				  <select name="intr_type" class="form-control" readonly>
				 <option value="<?php echo ($lproduct == "") ? 'Flat Rate' : $iintr_type; ?>"><?php echo ($lproduct == "") ? 'Flat Rate' : $iintr_type; ?></option>
				  </select>
                  </div>
                  </div>
				  
				  <input name="schedule" type="hidden" value="<?php echo $haveit['schedule']; ?>" class="form-control">
				  
				   <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">Schedules:</label>
                  <div class="col-sm-10">
				<table>
                <tbody> 
<?php
$get_id = $_GET['id'];
$searchin = mysqli_query($link, "SELECT * FROM pay_schedule WHERE get_id = '$get_id'") or die (mysqli_error($link));
while($haveit = mysqli_fetch_array($searchin))
{
$idmet= $haveit['id'];
?>			
				<tr>
        			<td><input id="optionsCheckbox" class="uniform_on" name="selector[]" type="hidden" value="<?php echo $idmet; ?>" checked></td>
                    <td width="400"><input name="schedulek[]" type="date" class="form-control pull-right" placeholder="Schedule" value="<?php echo $haveit['schedule']; ?>"></td>
                    <td width="300"><input name="balance[]" type="text" class="form-control" placeholder="Balance" value="<?php echo $haveit['balance']; ?>"></td>
        			<td width="130"><input name="payment[]" type="text" class="form-control" placeholder="Payment" value="<?php echo number_format($haveit['payment'],2,'.',''); ?>"></td>
        			<!--<td width="100"><input name="lstatus[]" type="text" class="form-control" placeholder="Status" value="<?php echo $haveit['status']; ?>" disabled></td>-->
			    </tr>
<?php } ?>

<?php
$get_lid = $_GET['lid'];
$searchliin = mysqli_query($link, "SELECT SUM(payment) FROM pay_schedule WHERE lid = '$get_lid'") or die (mysqli_error($link));
$fetchliin = mysqli_fetch_array($searchliin);
$overall_payment = $fetchliin['SUM(payment)'];
?>
                <tr>
        			<td></td>
                    <td width="400"></td>
                    <td width="300"></td>
        			<td width="130"><b><?php echo number_format($overall_payment,2,'.',','); ?></b></td>
        			<td width="100"></td>
			    </tr>
				</tbody>
                </table>
<hr>
<?php
$id = $_GET['id'];
$searchin2 = mysqli_query($link, "SELECT * FROM pay_schedule WHERE get_id = '$id' AND (balance = '0' OR balance <= '0')") or die (mysqli_error($link));
$numit = mysqli_num_rows($searchin2);
?>
<?php echo ($numit == 1) ? "<span class='label bg-".(($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color'])."'>Completed</span>" : "<span class='label bg-".(($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color'])."'>Progressing...(Schedule not completed yet).</span>"; ?>
<hr>
<div align="left">
              <div class="box-footer">

                				<?php
                                $id = $_GET['id'];
                                $comfirm_select1 = mysqli_query($link, "SELECT * FROM loan_info WHERE id = '$id'") or die (mysqli_error($link));
                                $comfirm_row1 = mysqli_fetch_array($comfirm_select1);
                                if($comfirm_row1['status'] == "Approved"){
                                    echo "";
                                }
                                else{
                                ?>
                                    <button type="submit" class="btn bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>" name="add_sch_rows" <?php echo ($numit == 1) ? 'disabled' : ''; ?>><i class="fa fa-plus"> Generate Schedule</i></button>
                				    <button name="delrow2" type="submit" class="btn bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>"><i class="fa fa-trash"> Delete Schedule</i></button>
                                    <button type="submit" class="btn bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>" name="add_pay_schedule"><i class="fa fa-save"> Save</i></button>
                                <?php
                                }
                                ?>
                                <?php
                                $id = $_GET['id'];
                                $comfirm_select = mysqli_query($link, "SELECT * FROM loan_info WHERE id = '$id'") or die (mysqli_error($link));
                                $comfirm_row = mysqli_fetch_array($comfirm_select);
                                if($comfirm_row['upstatus'] == "Completed"){
                                ?>
                                    <button type="submit" class="btn bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>" name="generate_pdf"><i class="fa fa-print"> Print Schedule!</i></button>
                                <?php
                                }else{
                                    echo "";
                                }
                                if($comfirm_row['status'] == "Approved"){
                                ?>
                                    <button type="submit" class="btn bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>" name="generate_offer"><i class="fa fa-print"> Print Offer</i></button>
                                <?php
                                }
                                if($comfirm_row['status'] == "Approved" && $cemail != ""){
                                ?>
                                    <button type="submit" class="btn bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>" name="send_offer"><i class="fa fa-mail"> Send Offer</i></button>
                                <?php
                                }else{
                                    echo "";
                                }
                                ?>
              </div>
			  </div>
   <?php
						if(isset($_POST['delrow2'])){
						$idm = $_GET['id'];
							$lid = $_GET['lid'];
							$id=$_POST['selector'];
							$N = count($id);
						if($id == ''){
						echo "<script>alert('Row Not Selected!!!'); </script>";	
						echo "<script>window.location='updateloans.php?id=".$idm."&&acn=".$_GET['acn']."&&mid=".base64_encode("405")."&&lid=".$lid."&&tab=tab_5'; </script>";
						}
						else{
							for($i=0; $i < $N; $i++)
							{
							    $update_interest_calc = mysqli_query($link, "DELETE FROM interest_calculator WHERE lid = '$lid'");
								$result = mysqli_query($link,"DELETE FROM pay_schedule WHERE id ='$id[$i]'");
								echo "<script>window.location='updateloans.php?id=".$idm."&&acn=".$_GET['acn']."&&mid=".base64_encode("405")."&&lid=".$lid."&&tab=tab_5'; </script>";
							}
						}
						}
?>

<?php
if(isset($_POST['add_sch_rows']))
{
$id = $_GET['id'];
$lid = $_GET['lid'];
$tid = $_GET['acn'];

$day = mysqli_real_escape_string($link, $_POST['d1']);
$intr_type = mysqli_real_escape_string($link, $_POST['intr_type']);
$schedule_of_paymt = mysqli_real_escape_string($link, $_POST['schedule']);

$N = $day;

//$count = ($N <= 2)

$process_data2 = mysqli_query($link, "SELECT * FROM loan_info WHERE lid = '$lid'") or die (mysqli_error($link));
$fetch = mysqli_fetch_array($process_data2);
$totalamount_topay = $fetch['amount_topay'];
$baccount = $fetch['baccount'];
$amount_borrowed = $fetch['amount'];
$lproduct = $fetch['lproduct'];

$search_product = mysqli_query($link, "SELECT * FROM loan_product WHERE id = '$lproduct'");
$get_product = mysqli_fetch_object($search_product);
$duration = $get_product->duration;
$interest = $get_product->interest / 100;
$penalty_type = $get_product->penalty_type;
$penalty_rate = $get_product->penalty_rate;

$amt_topay_per_duration = $amount_borrowed / $duration;

$new_interest = ($interest * $amount_borrowed) + $amt_topay_per_duration;

$calc_myint = ($amount_borrowed / $duration) + (($amount_borrowed / $duration) * $interest);

//$int_rate = ($intr_type == "Flat Rate") ? ($totalamount_topay / $duration) : $calc_myint;
$int_rate = ($intr_type == "Flat Rate") ? ($totalamount_topay / $duration) : $new_interest;
$firstfee_intRate = ($penalty_type == "Flat") ? ($int_rate + $penalty_rate) : (($penalty_rate / 100) * $int_rate);

$first_balance = ($intr_type == "Flat Rate") ? number_format(($totalamount_topay - $int_rate),0,'.','') : number_format(($amount_borrowed - $amt_topay_per_duration),0,'.','');

$verify_data = mysqli_query($link, "SELECT * FROM pay_schedule WHERE lid = '$lid'") or die (mysqli_error($link));
$verify_data2 = mysqli_query($link, "SELECT * FROM interest_calculator WHERE lid = '$lid'") or die (mysqli_error($link));
if((mysqli_num_rows($verify_data) == 0) && (mysqli_num_rows($verify_data2) == 0) && ($schedule_of_paymt == "Monthly") && ($intr_type == "Flat Rate"))
{
    $insert = mysqli_query($link, "INSERT INTO interest_calculator VALUES(null,'$lid','$first_balance','$int_rate','$duration')") or die (mysqli_error($link));
    
    $cur_date = strtotime($fetch['salary_date']);
    $lastDayThisMonth = date("Y-m-d", strtotime("+1 month", $cur_date));
	
    
    $insert = mysqli_query($link, "INSERT INTO pay_schedule VALUES(null,'$lid','$id','$baccount','','$lastDayThisMonth','$first_balance','$int_rate','UNPAID','$institution_id','','$isbranchid','$iuid','NotSent','','Pending','$firstfee_intRate')") or die (mysqli_error($link));
    //$update_calculator = mysqli_query($link, "UPDATE interest_calculator SET amt_to_pay = '$first_balance' WHERE lid = '$lid'") or die (mysqli_error($link));
    	    
    for($i = 1; $i < $N; $i++) {
    	        
    	//CONFIRMATION OF INTEREST RATE
        $select_int_calculator = mysqli_query($link, "SELECT * FROM interest_calculator WHERE lid = '$lid'") or die (mysqli_error($link));
        $get_calculator = mysqli_fetch_array($select_int_calculator);
        $balance = $get_calculator['amt_to_pay'];
        $lrate = $get_calculator['int_rate'];
        $new_balance = number_format(($balance - $lrate),0,'.','');
        $firstfee_intRate = ($penalty_type == "Flat") ? ($int_rate + $penalty_rate) : (($penalty_rate / 100) * $int_rate);
    	        
    	$verify_data1 = mysqli_query($link, "SELECT * FROM pay_schedule WHERE lid = '$lid' ORDER BY id DESC") or die (mysqli_error($link));
        $fetch_data1 = mysqli_fetch_array($verify_data1);
        $my_schedule = strtotime($fetch_data1['schedule']);
        $lastDayThisMonth2 = date("Y-m-d", strtotime("+1 month", $my_schedule));
        	    
        $insert = mysqli_query($link, "INSERT INTO pay_schedule VALUES(null,'$lid','$id','$baccount','','$lastDayThisMonth2','$new_balance','$int_rate','UNPAID','$institution_id','','$isbranchid','$iuid','NotSent','','Pending','$firstfee_intRate')") or die (mysqli_error($link));
        $update_calculator = mysqli_query($link, "UPDATE interest_calculator SET amt_to_pay = '$new_balance' WHERE lid = '$lid'") or die (mysqli_error($link));
    	        
    }
    echo "<script>window.location='updateloans.php?id=".$id."&&acn=".$tid."&&mid=NDA1&&lid=".$lid."&&tab=tab_5'; </script>";
	
}
elseif((mysqli_num_rows($verify_data) == 0) && (mysqli_num_rows($verify_data2) == 0) && ($schedule_of_paymt == "Monthly") && ($intr_type != "Flat Rate"))
{
    $insert = mysqli_query($link, "INSERT INTO interest_calculator VALUES(null,'$lid','$first_balance','$amt_topay_per_duration','$duration')") or die (mysqli_error($link));
    
    $cur_date = strtotime($fetch['salary_date']);
    $lastDayThisMonth = date("Y-m-d", strtotime("+1 month", $cur_date));
    //$amt_topayby_borrower = $amt_topay_per_duration + ($first_balance * $interest);
    
    $insert = mysqli_query($link, "INSERT INTO pay_schedule VALUES(null,'$lid','$id','$baccount','','$lastDayThisMonth','$first_balance','$int_rate','UNPAID','$institution_id','','$isbranchid','$iuid','NotSent','','Pending','$firstfee_intRate')") or die (mysqli_error($link));
    //$update_calculator = mysqli_query($link, "UPDATE interest_calculator SET amt_to_pay = '$first_balance' WHERE lid = '$lid'") or die (mysqli_error($link));
    
    for($i = 1; $i < $N; ++$i) {
    	        
    	//CONFIRMATION OF INTEREST RATE
        $select_int_calculator = mysqli_query($link, "SELECT * FROM interest_calculator WHERE lid = '$lid'") or die (mysqli_error($link));
        $get_calculator = mysqli_fetch_array($select_int_calculator);
        $balance = $get_calculator['amt_to_pay'];
        $myduration = $get_calculator['duration'] - 1;
        $divided_amt = $get_calculator['int_rate'];
        //$next_amt_topay = ($balance / $myduration) + (($balance / $myduration) * $interest);
        //$next_amt_topay = (($balance - $amt_topay_per_duration) * $interest) + $amt_topay_per_duration;
        $next_amt_topay = ($interest * $balance) + $divided_amt;
        $new_balance = ($myduration <= 1) ? '0' : number_format(($balance - $divided_amt),0,'.','');
        $firstfee_intRate = ($penalty_type == "Flat") ? ($next_amt_topay + $penalty_rate) : (($penalty_rate / 100) * $next_amt_topay);
    	        
    	$verify_data1 = mysqli_query($link, "SELECT * FROM pay_schedule WHERE lid = '$lid' ORDER BY id DESC") or die (mysqli_error($link));
        $fetch_data1 = mysqli_fetch_array($verify_data1);
        $my_schedule = strtotime($fetch_data1['schedule']);
        $lastDayThisMonth2 = date("Y-m-d", strtotime("+1 month", $my_schedule));
        	    
        $insert = mysqli_query($link, "INSERT INTO pay_schedule VALUES(null,'$lid','$id','$baccount','','$lastDayThisMonth2','$new_balance','$next_amt_topay','UNPAID','$institution_id','','$isbranchid','$iuid','NotSent','','Pending','$firstfee_intRate')") or die (mysqli_error($link));
        $update_calculator = mysqli_query($link, "UPDATE interest_calculator SET amt_to_pay = '$new_balance', duration = '$myduration' WHERE lid = '$lid'") or die (mysqli_error($link));
    	
    }
    $verify_repaysum = mysqli_query($link, "SELECT SUM(payment) FROM pay_schedule WHERE lid = '$lid' AND status = 'UNPAID'");
    $fetch_repaysum = mysqli_fetch_array($verify_repaysum);
    $total_repaysum = number_format($fetch_repaysum['SUM(payment)'],2,'.','');
    mysqli_query($link, "UPDATE loan_info SET amount_topay = '$total_repaysum', balance = '$total_repaysum' WHERE lid = '$lid'");
    echo "<script>window.location='updateloans.php?id=".$id."&&acn=".$tid."&&mid=NDA1&&lid=".$lid."&&tab=tab_5'; </script>";
	
}
elseif((mysqli_num_rows($verify_data) == 0) && (mysqli_num_rows($verify_data2) == 0) && ($schedule_of_paymt == "Weekly") && ($intr_type == "Flat Rate")) {
    
    $insert = mysqli_query($link, "INSERT INTO interest_calculator VALUES(null,'$lid','$first_balance','$int_rate','$duration')") or die (mysqli_error($link));
    
    $cur_date = strtotime($fetch['salary_date']);
    $lastDayThisMonth = date("Y-m-d", strtotime("+1 week", $cur_date));	
    
    $insert = mysqli_query($link, "INSERT INTO pay_schedule VALUES(null,'$lid','$id','$baccount','','$lastDayThisMonth','$first_balance','$int_rate','UNPAID','$institution_id','','$isbranchid','$iuid','NotSent','','Pending','$firstfee_intRate')") or die (mysqli_error($link));
    //$update_calculator = mysqli_query($link, "UPDATE interest_calculator SET amt_to_pay = '$first_balance' WHERE lid = '$lid'") or die (mysqli_error($link));
    	    
    for($i = 1; $i < $N; $i++) {
    	        
    	//CONFIRMATION OF INTEREST RATE
        $select_int_calculator = mysqli_query($link, "SELECT * FROM interest_calculator WHERE lid = '$lid'") or die (mysqli_error($link));
        $get_calculator = mysqli_fetch_array($select_int_calculator);
        $balance = $get_calculator['amt_to_pay'];
        $lrate = $get_calculator['int_rate'];
        $new_balance = number_format(($balance - $lrate),0,'.','');
    	        
    	$verify_data1 = mysqli_query($link, "SELECT * FROM pay_schedule WHERE lid = '$lid' ORDER BY id DESC") or die (mysqli_error($link));
        $fetch_data1 = mysqli_fetch_array($verify_data1);
        $my_schedule = strtotime($fetch_data1['schedule']);
        $lastDayThisMonth2 = date("Y-m-d", strtotime("+1 week", $my_schedule));
        $firstfee_intRate = ($penalty_type == "Flat") ? ($int_rate + $penalty_rate) : (($penalty_rate / 100) * $int_rate);
        	    
        $insert = mysqli_query($link, "INSERT INTO pay_schedule VALUES(null,'$lid','$id','$baccount','','$lastDayThisMonth2','$new_balance','$int_rate','UNPAID','$institution_id','','$isbranchid','$iuid','NotSent','','Pending','$firstfee_intRate')") or die (mysqli_error($link));
        $update_calculator = mysqli_query($link, "UPDATE interest_calculator SET amt_to_pay = '$new_balance' WHERE lid = '$lid'") or die (mysqli_error($link));
    	        
    }
    echo "<script>window.location='updateloans.php?id=".$id."&&acn=".$tid."&&mid=NDA1&&lid=".$lid."&&tab=tab_5'; </script>";
}
elseif((mysqli_num_rows($verify_data) == 0) && (mysqli_num_rows($verify_data2) == 0) && ($schedule_of_paymt == "Weekly") && ($intr_type != "Flat Rate")) {
    
    $insert = mysqli_query($link, "INSERT INTO interest_calculator VALUES(null,'$lid','$first_balance','$amt_topay_per_duration','$duration')") or die (mysqli_error($link));
    
    $cur_date = strtotime($fetch['salary_date']);
    $lastDayThisMonth = date("Y-m-d", strtotime("+1 week", $cur_date));
	//$amt_topayby_borrower = $amt_topay_per_duration + ($first_balance * $interest);
    
    $insert = mysqli_query($link, "INSERT INTO pay_schedule VALUES(null,'$lid','$id','$baccount','','$lastDayThisMonth','$first_balance','$int_rate','UNPAID','$institution_id','','$isbranchid','$iuid','NotSent','','Pending','$firstfee_intRate')") or die (mysqli_error($link));
    //$update_calculator = mysqli_query($link, "UPDATE interest_calculator SET amt_to_pay = '$first_balance' WHERE lid = '$lid'") or die (mysqli_error($link));
    	    
    for($i = 1; $i < $N; ++$i) {
    	        
    	//CONFIRMATION OF INTEREST RATE
        $select_int_calculator = mysqli_query($link, "SELECT * FROM interest_calculator WHERE lid = '$lid'") or die (mysqli_error($link));
        $get_calculator = mysqli_fetch_array($select_int_calculator);
        $balance = $get_calculator['amt_to_pay'];
        $myduration = $get_calculator['duration'] - 1;
        $divided_amt = $get_calculator['int_rate'];
        //$next_amt_topay = ($balance / $myduration) + (($balance / $myduration) * $interest);
        //$next_amt_topay = (($balance - $amt_topay_per_duration) * $interest) + $amt_topay_per_duration;
        $next_amt_topay = ($interest * $balance) + $divided_amt;
        $new_balance = ($myduration == 1) ? '0' : number_format(($balance - $divided_amt),0,'.','');
    	        
    	$verify_data1 = mysqli_query($link, "SELECT * FROM pay_schedule WHERE lid = '$lid' ORDER BY id DESC") or die (mysqli_error($link));
        $fetch_data1 = mysqli_fetch_array($verify_data1);
        $my_schedule = strtotime($fetch_data1['schedule']);
        $lastDayThisMonth2 = date("Y-m-d", strtotime("+1 week", $my_schedule));
        $firstfee_intRate = ($penalty_type == "Flat") ? ($next_amt_topay + $penalty_rate) : (($penalty_rate / 100) * $next_amt_topay);
        	    
        $insert = mysqli_query($link, "INSERT INTO pay_schedule VALUES(null,'$lid','$id','$baccount','','$lastDayThisMonth2','$new_balance','$next_amt_topay','UNPAID','$institution_id','','$isbranchid','$iuid','NotSent','','Pending','$firstfee_intRate')") or die (mysqli_error($link));
        $update_calculator = mysqli_query($link, "UPDATE interest_calculator SET amt_to_pay = '$new_balance', duration = '$myduration' WHERE lid = '$lid'") or die (mysqli_error($link));
    	        
    }
    $verify_repaysum = mysqli_query($link, "SELECT SUM(payment) FROM pay_schedule WHERE lid = '$lid' AND status = 'UNPAID'");
    $fetch_repaysum = mysqli_fetch_array($verify_repaysum);
    $total_repaysum = number_format($fetch_repaysum['SUM(payment)'],2,'.','');
    mysqli_query($link, "UPDATE loan_info SET amount_topay = '$total_repaysum', balance = '$total_repaysum' WHERE lid = '$lid'");
    echo "<script>window.location='updateloans.php?id=".$id."&&acn=".$tid."&&mid=NDA1&&lid=".$lid."&&tab=tab_5'; </script>";
}
}
?>
                  </div>
                  </div>
				  
              </div>

<?php
if(isset($_POST['add_pay_schedule']))
{
$idm = $_GET['id'];
$lid = $_GET['lid'];
$id=$_POST['selector'];
//$N = count($id);
	
$i = 0;
$tid = $_SESSION['tid'];
$acn = $_GET['acn'];
foreach($_POST['selector'] as $s)
{
	$schedule = mysqli_real_escape_string($link, $_POST['schedulek'][$i]);
	$update = mysqli_query($link, "UPDATE pay_schedule SET schedule = '$schedule' WHERE id = '$s'") or die (mysqli_error($link));
	$i++;
	$insert = mysqli_query($link, "UPDATE loan_info SET upstatus = 'Completed' WHERE id = '$idm'") or die (mysqli_error($link));
	if(!($update && $insert))
	{
	echo "<script>alert('Record not inserted.....Please try again later!'); </script>";
	}
	else{
	echo "<script>alert('Payment Scheduled Successfully!!'); </script>";
	echo "<script>window.location='updateloans.php?id=".$idm."&&acn=".$acn."&&mid=NDA1&&lid=".$lid."&&tab=tab_5'; </script>";
	}
}
}
?>

<?php
if(isset($_POST['generate_pdf']))
{
    $id = $_GET['id'];
    $lid = $_GET['lid'];
    $tid = $_GET['acn'];
	echo "<script>window.open('../pdf/view/pdf_payschedule.php?id=".$id."&&acn=".$tid."&&lid=".$lid."&&instid=".$institution_id."', '_blank'); </script>";
}
?>

<?php
if(isset($_POST['generate_offer']))
{
    $id = $_GET['id'];
    $lid = $_GET['lid'];
    $tid = $_GET['acn'];
	echo "<script>window.open('../pdf/view/pdf_loanoffer.php?id=".$id."&&acn=".$tid."&&lid=".$lid."&&instid=".$institution_id."', '_blank'); </script>";
}

if(isset($_POST['send_offer']))
{
    $id = $_GET['id'];
    $lid = $_GET['lid'];
    $acn = $_GET['acn'];

    $search_customer = mysqli_query($link, "SELECT * FROM borrowers WHERE account = '$acn'");
    $get_customer = mysqli_fetch_array($search_customer);
    $bemail = $get_customer['email'];
    $customerName = $get_customer['lname'].' '.$get_customer['fname'];
    $protocol = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
    $action_url = $protocol . $_SERVER['HTTP_HOST'] . '/pdf/view/pdf_loanoffer.php?id='.$id.'&&acn='.$acn.'&&lid='.$lid.'&&instid='.$institution_id;

    $sendSMS->loanOfferLetterEmailNotifier($bemail, $customerName, $action_url, $iemailConfigStatus, $ifetch_emailConfig);

    echo "<script>alert('Loan Offer Letter Sent Successfully!!'); </script>";
	echo "<script>window.location='updateloans.php?id=".$id."&&acn=".$acn."&&mid=NDA1&&lid=".$lid."&&tab=tab_5'; </script>";
}
?>

			  </form>
			  </div>
	<?php
	}
}
?>
              <!-- /.tab-pane -->
            </div>
            <!-- /.tab-content -->
          </div>
				 
					
					
				
				
				</div>
				

              </div>
			 

	
</div>	
</div>
</div>	
</div>