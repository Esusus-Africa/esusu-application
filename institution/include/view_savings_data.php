<div class="row">
		
	       
		    <section class="content">  
	        <div class="box box-success">
            <div class="box-body">
              <div class="table-responsive">
             <div class="box-body">
<form method="post">
	<a href="view_teller.php?id=<?php echo $_SESSION['tid']; ?>&&mid=<?php echo base64_encode("510"); ?>"><button type="button" class="btn btn-flat bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>"><i class="fa fa-mail-reply-all"></i>&nbsp;Back</button></a> 
    <?php //echo ($delete_transaction == '1') ? '<button type="submit" class="btn btn-flat bg-'.(($myaltrow['alternate_color'] == "") ? "orange" : $myaltrow['alternate_color']).'" name="delete"><i class="fa fa-times"></i>&nbsp;Multiple Delete</button>' : ''; ?>
	<?php echo ($deposit_money == '1') ? '<a href="deposit.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("510").'"><button type="button" class="btn btn-flat bg-'.(($myaltrow['theme_color'] == "") ? "blue" : $myaltrow['theme_color']).'"><i class="fa fa-plus"></i>&nbsp;Make Deposit</button></a>' : ''; ?>
	<?php echo ($withdraw_money == '1') ? '<a href="withdraw.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("510").'"><button type="button" class="btn btn-flat bg-'.(($myaltrow['theme_color'] == "") ? "blue" : $myaltrow['theme_color']).'"><i class="fa fa-plus"></i>&nbsp;Withdraw Money</button></a>' : ''; ?>
	<?php echo ($fund_settlement == '1') ? '<a href="settlement_history.php?id='.$_SESSION['tid'].'&&idm='.$_GET['idm'].'&&mid='.base64_encode("510").'" target="_blank"><button type="button" class="btn btn-flat bg-'.(($myaltrow['alternate_color'] == "") ? "orange" : $myaltrow['alternate_color']).'"><i class="fa fa-calculator"></i>&nbsp;View Settlement</button></a>' : ''; ?>
	<?php echo ($settle_fund == '1') ? '<a href="settle_fund2.php?id='.$_SESSION['tid'].'&&idm='.$_GET['idm'].'&&mid='.base64_encode("510").'" target="_blank"><button type="button" class="btn btn-flat bg-'.(($myaltrow['alternate_color'] == "") ? "orange" : $myaltrow['alternate_color']).'"><i class="fa fa-money"></i>&nbsp;Settle Fund</button></a>' : ''; ?>
	<hr>		
			  

			<div class="box-body">
                 
				<div class="form-group">
					<label for="" class="col-sm-1 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">From</label>
					<div class="col-sm-3">
					<input name="dfrom" type="date" id="startDate" class="form-control" placeholder="To Date: 2018-05-01">
					<span style="color: <?php echo ($myaltrow['alternate_color'] == '') ? 'blue' : $myaltrow['alternate_color']; ?>;">Date should be in this format: 2018-05-01</span>
					</div>
					
					<label for="" class="col-sm-1 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">End Date</label>
					<div class="col-sm-3">
					<input name="dto" type="date" id="endDate" class="form-control" placeholder="To Date: 2018-05-24">
					<span style="color: <?php echo ($myaltrow['alternate_color'] == '') ? 'blue' : $myaltrow['alternate_color']; ?>;">Date should be in this format: 2018-05-24</span>
					</div>

					<label for="" class="col-sm-1 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Type</label>
					<div class="col-sm-3">
					<select name="ptype" id="pmtType" class="form-control select2" style="width:100%">
					  <option value="" selected="selected">Filter By Payment Type...</option>
					  <option value="All">All Transaction</option>
					  <option value="Deposit">Deposit</option>
					  <option value="Withdraw">Withdraw</option>
					  <option value="Withdraw-Charges">Withdraw-Charges</option>
					  <option value="Cash">Cash</option>
					  <option value="Bank">Bank</option>
					</select>
					</div>
					<input name="filter_by" type="hidden" id="filterBy" class="form-control" value="<?php echo $_GET['idm']; ?>">
			   	</div>
				  
			</div>
			   
  
  		  	<hr>			
		  	<div class="table-responsive">
			   <table id="fetch_transaction_data" class="table table-bordered table-striped">
				  <thead>
				  <tr>
					<th><input type="checkbox" id="select_all"/></th>
					<th>TxID</th>
					<th>Savings Product</th>
					<th>Branch</th>
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


					<?php
					if(isset($_POST['delete'])) {

						$idm = $_GET['id'];
						$id=$_POST['selector'];
						$N = count($id);
						if($id == ''){
						echo "<script>alert('Row Not Selected!!!'); </script>";	
						echo "<script>window.location='view_savings_data.php?id=".$_SESSION['tid']."&&idm=".$_GET['idm']."&&mid=NTEw'; </script>";
							}
							else{
							for($i=0; $i < $N; $i++)
							{
								$search_trans = mysqli_query($link, "SELECT * FROM transaction WHERE id ='$id[$i]'");
								while($get_trans = mysqli_fetch_object($search_trans))
								{
									$bal = $get_trans->amount;
									$acn = $get_trans->acctno;

									$search_borrower = mysqli_query($link, "SELECT * FROM borrowers WHERE account ='$acn'");
									$get_borrower = mysqli_fetch_object($search_borrower);
									$balance = $get_borrower->balance;
									$newbalance = $balance - $bal;
			
									$update = mysqli_query($link, "UPDATE borrowers SET balance = '$newbalance' WHERE account ='$acn'");
									$result = mysqli_query($link,"DELETE FROM transaction WHERE id ='$id[$i]'");
									echo "<script>alert('Row Delete Successfully!!!'); </script>";
									echo "<script>window.location='transaction.php?id=".$_SESSION['tid']."&&idm=".$_GET['idm']."&&mid=NTEw'; </script>";
								}
							}
						}
					}
					?>			
</form>
				

              </div>

	
</div>	
</div>
</div>	


			
			
</div>