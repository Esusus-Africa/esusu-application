<div class="row">
		
	       
		    <section class="content">  
	        <div class="box box-success">
            <div class="box-body">
              <div class="table-responsive">
             <div class="box-body">

			 <form method="post">

			 <a href="dashboard.php?id=<?php echo $_SESSION['tid']; ?>&&mid=<?php echo base64_encode("401"); ?>"><button type="button" class="btn btn-flat bg-orange"><i class="fa fa-mail-reply-all"></i>&nbsp;Back</button> </a> 
	 
			 <?php echo ($delete_ledger_transaction == '1') ? '<button type="submit" class="btn btn-flat bg-orange" name="delete"><i class="fa fa-times"></i>&nbsp;Multiple Delete</button>' : ''; ?>

			</form>	


             <div class="box-body">
                 
	           <div class="form-group">
                  <label for="" class="col-sm-1 control-label" style="color:blue;">From</label>
                  <div class="col-sm-5">
                  <input name="dfrom" type="date" id="startDate" class="form-control" placeholder="To Date: 2018-05-01" required>
				  <span style="color: orange;">Date should be in this format: 2018-05-01</span>
                  </div>
				  
				  <label for="" class="col-sm-1 control-label" style="color:blue;">To</label>
                  <div class="col-sm-5">
                  <input name="dto" type="date" id="endDate" class="form-control" placeholder="To Date: 2018-05-24" required>
				  <span style="color: orange;">Date should be in this format: 2018-05-24</span>
                  </div>
             </div>

            <div class="form-group">
                  <label for="" class="col-sm-1 control-label" style="color:blue;">Type</label>
                  <div class="col-sm-5">
                  <select name="ptype" id="pmtType" class="form-control select2" style="width:100%" required>
					<option value="" selected="selected">Filter By Payment Type...</option>
					<option value="All">All Transaction</option>
					<option value="Deposit">Deposit</option>
					<option value="Withdraw">Withdraw</option>
					<option value="Withdraw-Charges">Withdraw-Charges</option>
					<option value="Cash">Cash</option>
					<option value="Bank">Bank</option>
				  </select>
                  </div>
				  
				  <label for="" class="col-sm-1 control-label" style="color:blue;">Filter By</label>
                  <div class="col-sm-5">
                 <select name="filter_by" id="filterBy" class="form-control select2" style="width:100%" required>
    				<option value="" selected="selected">Filter By Institution, Agent, Merchant, Staff, Customer, Branch...</option>
    				<option disabled>Filter By Client</option>
    				<?php
    				$get1 = mysqli_query($link, "SELECT * FROM institution_data WHERE status = 'Approved' ORDER BY id") or die (mysqli_error($link));
    				while($rows1 = mysqli_fetch_array($get1))
    				{
    				?>
    				<option value="<?php echo $rows1['institution_id']; ?>"><?php echo $rows1['institution_name']; ?></option>
    				<?php } ?>	
				    
				    <option disabled>Filter By Client Customer</option>
    				<?php
    				$get4 = mysqli_query($link, "SELECT * FROM borrowers ORDER BY id") or die (mysqli_error($link));
    				while($rows4 = mysqli_fetch_array($get4))
    				{
    				?>
    				<option value="<?php echo $rows4['account']; ?>"><?php echo $rows4['lname'].' '.$rows4['fname'].' ['.$rows4['account'].']'; ?></option>
    				<?php } ?>
    				
    				<option disabled>Filter By Client Branch</option>
    				<?php
    				$get5 = mysqli_query($link, "SELECT * FROM branches ORDER BY id") or die (mysqli_error($link));
    				while($rows5 = mysqli_fetch_array($get5))
    				{
    				?>
    				<option value="<?php echo $rows5['branchid']; ?>"><?php echo $rows5['bname'].' ['.$rows5['branchid'].']'; ?></option>
    				<?php } ?>
    				
    				<option disabled>Filter By Client Staff/Agent</option>
    				<?php
    				$get6 = mysqli_query($link, "SELECT * FROM user ORDER BY id") or die (mysqli_error($link));
    				while($rows6 = mysqli_fetch_array($get6))
    				{
    				?>
    				<option value="<?php echo $rows6['id']; ?>"><?php echo $rows6['name'].' '.$rows6['lname'].' '.$rows6['mname']; ?></option>
    				<?php } ?>
    				
				</select>
                  </div>
                </div>
                
                </div>
			
<hr>		

			 <div class="table-responsive">
			 <table id="fetch_transaction_data" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th><input type="checkbox" id="select_all"/></th>
                  <th>TxID</th>
				  <th>Client Name</th>
				  <th>Client Branch</th>
				  <th>Savings Product</th>
				  <th>AcctNo.</th>
				  <th>Acct. Name</th>
                  <th>Phone</th>
                  <th>Debit</th>
				  <th>Credit</th>
				  <th>Balance</th>
				  <th>Date/Time</th>
				  <th>Posted By</th>
                 </tr>
                </thead>
                </table>
                </div>

                
        </form>
        
<?php
						if(isset($_POST['delete'])){
						$idm = $_GET['id'];
							$id=$_POST['selector'];
							$N = count($id);
						if($id == ''){
						echo "<script>alert('Row Not Selected!!!'); </script>";	
						echo "<script>window.location='listborrowers.php?id=".$_SESSION['tid']."&&mid=".base64_encode("419")."'; </script>";
							}
							else{
							for($i=0; $i < $N; $i++)
							{
								$search_trans = mysqli_query($link, "SELECT * FROM transaction WHERE id ='$id[$i]'");
								$get_trans = mysqli_fetch_object($search_trans);
								$bal = $get_trans->amount;
								$acn = $get_trans->acctno;

								$search_borrower = mysqli_query($link, "SELECT * FROM borrowers WHERE account ='$acn'");
								$get_borrower = mysqli_fetch_object($search_borrower);
								$balance = $get_borrower->balance;
								$newbalance = $balance - $bal;
			
								$update = mysqli_query($link, "UPDATE borrowers SET balance = '$newbalance' WHERE account ='$acn'");
								$result = mysqli_query($link,"DELETE FROM transaction WHERE id ='$id[$i]'");
								echo "<script>alert('Row Delete Successfully!!!'); </script>";
								echo "<script>window.location='transaction.php?id=".$_SESSION['tid']."&&mid=".base64_encode("419")."'; </script>";
							}
							}
							}
?>			

				

              </div>

	
</div>	
</div>
</div>	


			
</div>