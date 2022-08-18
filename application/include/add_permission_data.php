<div class="row">
    
		    <section class="content">  
	        <div class="box box-success">
            <div class="box-body">
              <div class="table-responsive">
             <div class="box-body">
<form method="post">
			 <a href="permission_list.php?id=<?php echo $_SESSION['tid']; ?>&&mid=<?php echo base64_encode("413"); ?>"><button type="button" class="btn btn-flat bg-orange"><i class="fa fa-mail-reply-all"></i>&nbsp;Back</button></a> 
			 <input type="checkbox" id="select_all"/> <b>Tick Here to Enable all modules</b>

	<hr>	

<form class="form-horizontal" method="post" enctype="multipart/form-data">
			  <?php echo '<div class="alert bg-blue fade in" >
			  <a href = "#" class = "close" data-dismiss= "alert"> &times;</a>
  				<strong>Add Module Permission</strong> | <label style="color: white;">Here you declare which module you want the user to get access to in their respective account.</label></div>'?>	
<div class="box-body table-responsive">

			<table width="100%" border="1" bordercolor="#000000">
			  <tr class="form-group" width="100%">
				<td>
					
				<div class="form-group">
					<label for="" class="col-sm-2 control-label" style="color:blue;">User Name:</label>
	                <div class="col-sm-10">
	                <select name="tide"  class="form-control select2" required>
						<option selected>Selected Staff...</option>
						<?php
						$search_user = mysqli_query($link, "SELECT * FROM user WHERE id != '".$_SESSION['tid']."' AND utype = 'Registered'") or die ("Error: " . mysqli_error($link));
						while($get_users = mysqli_fetch_array($search_user))
						{
						?>		
						<option value="<?php echo $get_users['id']; ?>"><?php echo $get_users['name']; ?></option>
						<?php } ?>
					
					</select>
	                </div>			
	            </div>
				
				</td>
			  </tr>
			</table>
			
			<div class="form-group">
			<hr>
			<div>
				
			<table width="100%" border="1">
			  <tr bgcolor="orange">
				<td width="25%"><div align="left"><strong>&nbsp;BRANCH SETTINGS</strong></div></td>
			  </tr>
			
  			  <tr width="100%">
			   	<td><div align="center">Branch Tab:<p> <input name="branch_tab" id="optionsCheckbox" class="checkbox"  name="selector[]" type="checkbox" value="On"><p></div></td>
			   	<td><div align="center">Add:<p> <input name="add_branch" id="optionsCheckbox" class="checkbox"  name="selector[]" type="checkbox" value="On"></p></div></td>
			   	<td><div align="center">List/View:<p> <input name="list_branch" id="optionsCheckbox" class="checkbox"  name="selector[]" type="checkbox" value="On"></p></div></td>
			   	<td><div align="center">Edit/Update:<p> <input name="edit_branch" id="optionsCheckbox" class="checkbox"  name="selector[]" type="checkbox" value="On"></p></div></td>
				<td><div align="center">Delete:<p> <input name="del_branch" id="optionsCheckbox" class="checkbox"  name="selector[]" type="checkbox" value="On"></p></div></td>
  			  </tr>
			  
			</table>
			<hr>

			<table width="100%" border="1">
			  <tr bgcolor="orange">
				<td width="25%"><div align="left"><strong>&nbsp;COOPERATIVE SETTINGS</strong></div></td>
			  </tr>
			
  			  <tr width="100%">
			   	<td><div align="center">Cooperative Tab:<p> <input name="cooperative_tab" id="optionsCheckbox" class="checkbox"  name="selector[]" type="checkbox" value="On"></p></div></td>
			   	<td><div align="center">Add Cooperative:<p> <input name="add_cooperative" id="optionsCheckbox" class="checkbox"  name="selector[]" type="checkbox" value="On"></p></div></td>
			   	<td><div align="center">Add Member:<p> <input name="add_coop_member" id="optionsCheckbox" class="checkbox"  name="selector[]" type="checkbox" value="On"></p></div></td>
			   	<td><div align="center">View Cooperatives:<p> <input name="view_cooperative" id="optionsCheckbox" class="checkbox"  name="selector[]" type="checkbox" value="On"></p></div></td>
				<td><div align="center">View Members:<p> <input name="view_members" id="optionsCheckbox" class="checkbox"  name="selector[]" type="checkbox" value="On"></p></div></td>
  			  </tr>

  			  <tr width="100%">
			   	<td><div align="center">Update Cooperative:<p> <input name="update_cooperative" id="optionsCheckbox" class="checkbox"  name="selector[]" type="checkbox" value="On"><p></div></td>
			   	<td><div align="center">Update Members:<p> <input name="update_coop_members" id="optionsCheckbox" class="checkbox"  name="selector[]" type="checkbox" value="On"><p></div></td>
			   	<td><div align="center">Send SMS:<p> <input name="sendsms_cooperative" id="optionsCheckbox" class="checkbox"  name="selector[]" type="checkbox" value="On"></p></div></td>
  			  </tr>
			  
			</table>
			<hr>

			<table width="100%" border="1">
			  <tr bgcolor="orange">
				<td width="25%"><div align="left"><strong>&nbsp;INSTITUTION SETTINGS</strong></div></td>
			  </tr>
			
  			  <tr width="100%">
			   	<td><div align="center">Institution Tab:<p> <input name="institution_tab" id="optionsCheckbox" class="checkbox"  name="selector[]" type="checkbox" value="On"><p></div></td>
			   	<td><div align="center">Add Institution:<p> <input name="add_institution" id="optionsCheckbox" class="checkbox"  name="selector[]" type="checkbox" value="On"></p></div></td>
			   	<td><div align="center">View Institution:<p> <input name="view_institution" id="optionsCheckbox" class="checkbox"  name="selector[]" type="checkbox" value="On"></p></div></td>
			   	<td><div align="center">View Members:<p> <input name="view_inst_members" id="optionsCheckbox" class="checkbox"  name="selector[]" type="checkbox" value="On"></p></div></td>
				<td><div align="center">Update Members:<p> <input name="update_inst_members" id="optionsCheckbox" class="checkbox"  name="selector[]" type="checkbox" value="On"></p></div></td>
  			  </tr>

  			  <tr width="100%">
			   	<td><div align="center">Update Institution:<p> <input name="update_institution" id="optionsCheckbox" class="checkbox"  name="selector[]" type="checkbox" value="On"><p></div></td>
			   	<td><div align="center">Send SMS:<p> <input name="sendsms_institution" id="optionsCheckbox" class="checkbox"  name="selector[]" type="checkbox" value="On"></p></div></td>
  			  </tr>
			  
			</table>
			<hr>

			<table width="100%" border="1">
			  <tr bgcolor="orange">
				<td width="25%"><div align="left"><strong>&nbsp;AGENTS SETTINGS</strong></div></td>
			  </tr>
			
  			  <tr width="100%">
			   	<td><div align="center">Agent Tab:<p> <input name="agent_tab" id="optionsCheckbox" class="checkbox"  name="selector[]" type="checkbox" value="On"><p></div></td>
			   	<td><div align="center">Add:<p> <input name="add_agent" id="optionsCheckbox" class="checkbox"  name="selector[]" type="checkbox" value="On" <?php echo ($have['add_agent'] == "0") ? '' : 'checked'; ?>></p></div></td>
			   	<td><div align="center">List/View:<p> <input name="view_agent" id="optionsCheckbox" class="checkbox"  name="selector[]" type="checkbox" value="On"></p></div></td>
			   	<td><div align="center">Edit/Update:<p> <input name="update_agent" id="optionsCheckbox" class="checkbox"  name="selector[]" type="checkbox" value="On"></p></div></td>
				<td><div align="center">Send SMS to Agents:<p> <input name="sendsms_agents" id="optionsCheckbox" class="checkbox"  name="selector[]" type="checkbox" value="On"></p></div></td>
  			  </tr>
			  
			</table>
			<hr>

			<table width="100%" border="1">
			  <tr bgcolor="orange">
				<td width="25%"><div align="left"><strong>&nbsp;SUBSCRIPTION SETTINGS</strong></div></td>
			  </tr>
			
  			  <tr width="100%">
			   	<td><div align="center">Subscription Tab:<p> <input name="subscription_tab" id="optionsCheckbox" class="checkbox"  name="selector[]" type="checkbox" value="On"><p></div></td>
			   	<td><div align="center">Setup Saas Plan:<p> <input name="setup_saas_plan" id="optionsCheckbox" class="checkbox"  name="selector[]" type="checkbox" value="On"></p></div></td>
			   	<td><div align="center">View Subscription Plan:<p> <input name="view_subscription_plan" id="optionsCheckbox" class="checkbox"  name="selector[]" type="checkbox" value="On"></p></div></td>
			   	<td><div align="center">Update Saas Plan:<p> <input name="update_saas_plan" id="optionsCheckbox" class="checkbox"  name="selector[]" type="checkbox" value="On"></p></div></td>
				<td><div align="center">View Pending Subscription:<p> <input name="view_pending_sub" id="optionsCheckbox" class="checkbox" name="selector[]" type="checkbox" value="On"></p></div></td>
  			  </tr>

  			  <tr width="100%">
			   	<td><div align="center">View Paid Subscription:<p> <input name="view_paid_sub" id="optionsCheckbox" class="checkbox"  name="selector[]" type="checkbox" value="On"><p></div></td>
			   	<td><div align="center">View Deactivated Subscription:<p> <input name="view_deactivated_sub" id="optionsCheckbox" class="checkbox"  name="selector[]" type="checkbox" value="On"></p></div></td>
			   	<td><div align="center">View Expired Subscription:<p> <input name="view_expired_sub" id="optionsCheckbox" class="checkbox"  name="selector[]" type="checkbox" value="On"></p></div></td>
  			  </tr>
			  
			</table>
			<hr>
			
			<table width="100%" border="1">
			  <tr bgcolor="orange">
				<td width="25%"><div align="left"><strong>&nbsp;CUSTOMER MGT. SETTINGS</strong></div></td>
			  </tr>
			
  			  <tr>
			   	<td><div align="center">Customer Tab:<p> <input name="customer_tab" id="optionsCheckbox" class="checkbox"  name="selector[]" type="checkbox" value="On"></p></div></td>
			   	<td><div align="center">Add:<p> <input name="add_customer" id="optionsCheckbox" class="checkbox"  name="selector[]" type="checkbox" value="On"></p></div></td>
			   	<td><div align="center">List/View All:<p> <input name="list_customer" id="optionsCheckbox" class="checkbox"  name="selector[]" type="checkbox" value="On"></p></div></td>
				<td><div align="center">View Borrowers:<p> <input name="list_borrower" id="optionsCheckbox" class="checkbox"  name="selector[]" type="checkbox" value="On"></p></div></td>
				<td><div align="center">Delete:<p> <input name="del_customer" id="optionsCheckbox" class="checkbox"  name="selector[]" type="checkbox" value="On"></p></div></td>
			  <tr>
    			</tr>
				<td><div align="center">View Account Info:<p> <input name="view_acct_info" id="optionsCheckbox" class="checkbox"  name="selector[]" type="checkbox" value="On"></p></div></td>
  			   	<td><div align="center">Edit/Update:<p> <input name="edit_customer" id="optionsCheckbox" class="checkbox"  name="selector[]" type="checkbox" value="On"></p></div></td>
  			   	<td><div align="center">Add borrower list:<p> <input name="add_to_borrower" id="optionsCheckbox" class="checkbox"  name="selector[]" type="checkbox" value="On"></p></div></td>
  			   	<td><div align="center">Send SMS:<p> <input name="send_sms" id="optionsCheckbox" class="checkbox"  name="selector[]" type="checkbox" value="On"></p></div></td>
  				<td><div align="center">Send Email:<p> <input name="send_email" id="optionsCheckbox" class="checkbox"  name="selector[]" type="checkbox" value="On"></p></div></td>
			  </tr>
				  
			</table>
			<hr>
			
			<table width="100%" border="1">
			  <tr bgcolor="orange">
				<td width="25%"><div align="left"><strong>&nbsp;WALLET MGT. SETTINGS</strong></div></td>
			  </tr>
			
  			  <tr>
			   	<td><div align="center">Wallet Tab:<p> <input name="wallet_tab" id="optionsCheckbox" class="checkbox"  name="selector[]"  type="checkbox" value="On"></p></div></td>
			   	<td><div align="center">Transfer Fund:<p> <input name="transfer_fund" id="optionsCheckbox" class="checkbox"  name="selector[]" type="checkbox" value="On"></p></div></td>
			   	<td><div align="center">Add Transfer Recipients:<p> <input name="add_transfer_recipients" id="optionsCheckbox" class="checkbox"  name="selector[]" type="checkbox" value="On"></p></div></td>
			   	<td><div align="center">Add Fund to Balance:<p> <input name="add_fund_tobalance" id="optionsCheckbox" class="checkbox"  name="selector[]" type="checkbox" value="On"></p></div></td>
				<td><div align="center">View Transfer Recipients:<p> <input name="view_transfer_recipients" id="optionsCheckbox" class="checkbox"  name="selector[]" type="checkbox" value="On"></p></div></td>
  			  </tr>

  			  <tr>
			   	<td><div align="center">Buy Airtime:<p> <input name="buy_airtime" id="optionsCheckbox" class="checkbox"  name="selector[]" type="checkbox" value="On"></p></div></td>
			   </tr>
				  
			</table>
			<hr>

			<table width="100%" border="1">
			  <tr bgcolor="orange">
				<td width="25%"><div align="left"><strong>&nbsp;REFERRAL MANAGER</strong></div></td>
			  </tr>
			
  			  <tr>
			   	<td><div align="center">Referral Tab:<p> <input name="referral_tab" id="optionsCheckbox" class="checkbox"  name="selector[]" type="checkbox" value="On"></p></div></td>
			   	<td><div align="center">Set Referral Plan:<p> <input name="set_referral_plan" id="optionsCheckbox" class="checkbox"  name="selector[]" type="checkbox" value="On"></p></div></td>
			   	<td><div align="center">View Compensation Plan:<p> <input name="view_compensation_plan" id="optionsCheckbox" class="checkbox"  name="selector[]" type="checkbox" value="On"></p></div></td>
			   	<td><div align="center">Update Compensation Plan:<p> <input name="update_compensation_plan" id="optionsCheckbox" class="checkbox"  name="selector[]" type="checkbox" value="On"></p></div></td>
				<td><div align="center">Confirm Bonus:<p> <input name="confirm_bonus" id="optionsCheckbox" class="checkbox"  name="selector[]" type="checkbox" value="On"></p></div></td>
  			  </tr>

  			  <tr>
			   	<td><div align="center">Bonus Transaction:<p> <input name="bonus_transaction" id="optionsCheckbox" class="checkbox"  name="selector[]" type="checkbox" value="On"></p></div></td>
			   </tr>
				  
			</table>
			<hr>
			
			<table width="100%" border="1">
			  <tr bgcolor="orange">
				<td width="25%"><div align="left"><strong>&nbsp;LOAN MGT. SETTINGS</strong></div></td>
			  </tr>
			
  			  <tr>
			   	<td><div align="center">Loan Tab:<p> <input name="loan_tab" id="optionsCheckbox" class="checkbox"  name="selector[]" type="checkbox" value="On"></p></div></td>
			   	<td><div align="center">Add:<p> <input name="add_loan" id="optionsCheckbox" class="checkbox"  name="selector[]" type="checkbox" value="On"></p></div></td>
			   	<td><div align="center">List/View All:<p> <input name="list_loan" id="optionsCheckbox" class="checkbox"  name="selector[]" type="checkbox" value="On"></p></div></td>
			   	<td><div align="center">Due Loan:<p> <input name="due_loan" id="optionsCheckbox" class="checkbox"  name="selector[]" type="checkbox" value="On"></p></div></td>
				<td><div align="center">View Past Maturity Date Loan:<p> <input name="past_maturity_date_loan" id="optionsCheckbox" class="checkbox"  name="selector[]" type="checkbox" value="On"></p></div></td>
  			  </tr>
			  <tr>
  			   	<td><div align="center">View Principal Outstanding Loan:<p> <input name="view_poutstanding" id="optionsCheckbox" class="checkbox"  name="selector[]" type="checkbox" value="On"></p></div></td>
  			   	<td><div align="center">Approve Loan:<p> <input name="approve_loan" id="optionsCheckbox" class="checkbox"  name="selector[]" type="checkbox" value="On"></p></div></td>
  			   	<td><div align="center">View Loan Details:<p> <input name="view_loan_details" id="optionsCheckbox" class="checkbox"  name="selector[]" type="checkbox" value="On"></p></div></td>
  			   	<td><div align="center">Delete Loan:<p> <input name="del_loan" id="optionsCheckbox" class="checkbox"  name="selector[]" type="checkbox" value="On"></p></div></td>
  				<td><div align="center">Print:<p> <input name="print_loan" id="optionsCheckbox" class="checkbox"  name="selector[]" type="checkbox" value="On"></p></div></td>
			  </tr>
			  <tr>
				<td><div align="center">Export Excel:<p> <input name="export_excel_loan" id="optionsCheckbox" class="checkbox"  name="selector[]" type="checkbox" value="On"></p></div></td>
			  </tr>
				  
			</table>
			<hr>
			
			<table width="100%" border="1">
			  <tr bgcolor="orange">
				<td width="25%"><div align="left"><strong>&nbsp;LOAN PAYMENT SETTINGS</strong></div></td>
			  </tr>
			
  			  <tr>
			   	<td><div align="center">Payment Tab:<p> <input name="loanpay_tab" id="optionsCheckbox" class="checkbox"  name="selector[]" type="checkbox" value="On"></p></div></td>
			   	<td><div align="center">Add:<p> <input name="add_payment" id="optionsCheckbox" class="checkbox"  name="selector[]" type="checkbox" value="On"></p></div></td>
			   	<td><div align="center">List/View:<p> <input name="list_payment" id="optionsCheckbox" class="checkbox"  name="selector[]" type="checkbox" value="On"></p></div></td>
			   	<td><div align="center">Delete:<p> <input name="del_payment" id="optionsCheckbox" class="checkbox"  name="selector[]" type="checkbox" value="On"></p></div></td>
				<td><div align="center">Print:<p> <input name="print_payment" id="optionsCheckbox" class="checkbox"  name="selector[]" type="checkbox" value="On"></p></div></td>
  			  </tr>
			  <tr>
				<td><div align="center">Export Excel:<p> <input name="export_excel_lpayment" id="optionsCheckbox" class="checkbox"  name="selector[]" type="checkbox" value="On"></p></div></td>
			  </tr>
				  
			</table>
			<hr>
			
			<table width="100%" border="1">
			  <tr bgcolor="orange">
				<td width="25%"><div align="left"><strong>&nbsp;EMPLOYEE MGT. SETTINGS</strong></div></td>
			  </tr>
			
  			  <tr>
			   	<td><div align="center">Employee Tab:<p> <input name="emp_tab" id="optionsCheckbox" class="checkbox"  name="selector[]" type="checkbox" value="On"></p></div></td>
			   	<td><div align="center">Add:<p> <input name="add_emp" id="optionsCheckbox" class="checkbox"  name="selector[]" type="checkbox" value="On"></p></div></td>
			   	<td><div align="center">List/View:<p> <input name="list_emp" id="optionsCheckbox" class="checkbox"  name="selector[]" type="checkbox" value="On"></p></div></td>
			   	<td><div align="center">Edit/Update:<p> <input name="edit_emp" id="optionsCheckbox" class="checkbox"  name="selector[]" type="checkbox" value="On"></p></div></td>
				<td><div align="center">Send SMS:<p> <input name="send_empsms" id="optionsCheckbox" class="checkbox"  name="selector[]" type="checkbox" value="On"></p></div></td>
  			  </tr>
			  <tr>
  			   	<td><div align="center">Delete:<p> <input name="del_emp" id="optionsCheckbox" class="checkbox"  name="selector[]" type="checkbox" value="On"></p></div></td>
  			   	<td><div align="center">Print:<p> <input name="print_emp" id="optionsCheckbox" class="checkbox"  name="selector[]" type="checkbox" value="On"></p></div></td>
  			   	<td><div align="center">Export Excel:<p> <input name="export_excel_emp" id="optionsCheckbox" class="checkbox"  name="selector[]" type="checkbox" value="On"></p></div></td>
			  </tr>
			 
			</table>
			<hr>
			
			
			<table width="100%" border="1">
			  <tr bgcolor="orange">
				<td width="25%"><div align="left"><strong>&nbsp;EXPENSES SETTINGS</strong></div></td>
			  </tr>
			
  			  <tr width="100%">
			   	<td><div align="center">Expense Tab:<p> <input name="expense_tab" id="optionsCheckbox" class="checkbox"  name="selector[]" type="checkbox" value="On"><p></div></td>
			   	<td><div align="center">Add:<p> <input name="add_expense" id="optionsCheckbox" class="checkbox"  name="selector[]" type="checkbox" value="On"></p></div></td>
			   	<td><div align="center">List/View:<p> <input name="list_expense" id="optionsCheckbox" class="checkbox"  name="selector[]" type="checkbox" value="On"></p></div></td>
			   	<td><div align="center">Edit/Update:<p> <input name="edit_expense" id="optionsCheckbox" class="checkbox"  name="selector[]" type="checkbox" value="On"></p></div></td>
				<td><div align="center">Delete:<p> <input name="del_expense" id="optionsCheckbox" class="checkbox"  name="selector[]" type="checkbox" value="On"></p></div></td>
  			  </tr>
			  
			</table>
			<hr>
			
			<table width="100%" border="1">
			  <tr bgcolor="orange">
				<td width="25%"><div align="left"><strong>&nbsp;PAYROLL SETTINGS</strong></div></td>
			  </tr>
			
  			  <tr width="100%">
			   	<td><div align="center">Payroll Tab:<p> <input name="payroll_tab" id="optionsCheckbox" class="checkbox"  name="selector[]" type="checkbox" value="On"><p></div></td>
			   	<td><div align="center">Add:<p> <input name="add_payroll" id="optionsCheckbox" class="checkbox"  name="selector[]" type="checkbox" value="On"></p></div></td>
			   	<td><div align="center">List/View:<p> <input name="list_payroll" id="optionsCheckbox" class="checkbox"  name="selector[]" type="checkbox" value="On"></p></div></td>
			   	<td><div align="center">Edit/Update:<p> <input name="edit_payroll" id="optionsCheckbox" class="checkbox"  name="selector[]" type="checkbox" value="On"></p></div></td>
				<td><div align="center">Delete:<p> <input name="del_payroll" id="optionsCheckbox" class="checkbox"  name="selector[]" type="checkbox" value="On"></p></div></td>
  			  </tr>
			  
			</table>
			<hr>
			
			<table width="100%" border="1">
			  <tr bgcolor="orange">
				<td width="25%"><div align="left"><strong>&nbsp;SAVINGS SYS. SETTINGS</strong></div></td>
			  </tr>
			
  			  <tr>
			   	<td><div align="center">Savings Tab:<p> <input name="savings_tab" id="optionsCheckbox" class="checkbox"  name="selector[]" type="checkbox" value="On"></p></div></td>
			   	<td><div align="center">Deposit Fund:<p> <input name="s_deposit_fund" id="optionsCheckbox" class="checkbox"  name="selector[]" type="checkbox" value="On"></p></div></td>
			   	<td><div align="center">Withdraw Fund:<p> <input name="s_withdraw_fund" id="optionsCheckbox" class="checkbox"  name="selector[]" type="checkbox" value="On"></p></div></td>
			   	<td><div align="center">View All Transaction:<p> <input name="list_transaction" id="optionsCheckbox" class="checkbox"  name="selector[]" type="checkbox" value="On"></p></div></td>
				<td><div align="center">Enable Verify Account Button:<p> <input name="verify_account" id="optionsCheckbox" class="checkbox"  name="selector[]" type="checkbox" value="On"></p></div></td>
  			  </tr>
			  <tr>
  			   	<td><div align="center">Delete:<p> <input name="del_transaction" id="optionsCheckbox" class="checkbox"  name="selector[]" type="checkbox" value="On"></p></div></td>
  			   	<td><div align="center">Print:<p> <input name="print_transaction" id="optionsCheckbox" class="checkbox"  name="selector[]" type="checkbox" value="On"></p></div></td>
  			   	<td><div align="center">Export Excel:<p> <input name="export_excel_transaction" id="optionsCheckbox" class="checkbox"  name="selector[]" type="checkbox" value="On"></p></div></td>
			  </tr>
			 
			</table>
			<hr>
			
			<table width="100%" border="1">
			  <tr bgcolor="orange">
				<td width="25%"><div align="left"><strong>&nbsp;REPORT SYS. SETTINGS</strong></div></td>
			  </tr>
			
  			  <tr>
			   	<td><div align="center">Report Tab:<p> <input name="report_tab" id="optionsCheckbox" class="checkbox"  name="selector[]" type="checkbox" value="On"></p></div></td>
			   	<td><div align="center">Borrower Report:<p> <input name="borrower_report" id="optionsCheckbox" class="checkbox"  name="selector[]" type="checkbox" value="On"></p></div></td>
			   	<td><div align="center">Collection Report:<p> <input name="collection_report" id="optionsCheckbox" class="checkbox"  name="selector[]" type="checkbox" value="On"></p></div></td>
			   	<td><div align="center">Collector Report:<p> <input name="collector_report" id="optionsCheckbox" class="checkbox"  name="selector[]" type="checkbox" value="On"></p></div></td>
  			  </tr>
				  
			</table>
			<hr>

			<table width="100%" border="1">
			  <tr bgcolor="orange">
				<td width="25%"><div align="left"><strong>&nbsp;CONFIGURATION SETTINGS</strong></div></td>
			  </tr>
			
  			  <tr>
			   	<td><div align="center">Configuration Tab:<p> <input name="config_tab" id="optionsCheckbox" class="checkbox"  name="selector[]" type="checkbox" value="On"></p></div></td>
			   	<td><div align="center">Company Setup:<p> <input name="company_setup" id="optionsCheckbox" class="checkbox"  name="selector[]" type="checkbox" value="On"></p></div></td>
			   	<td><div align="center">SMS Gateway Settings:<p> <input name="smsgateway_settings" id="optionsCheckbox" class="checkbox"  name="selector[]" type="checkbox" value="On"></p></div></td>
  			  </tr>
				  
			</table>
			<hr>
	

		<div align="right">
            <div class="box-footer">
				<button type="submit" class="btn bg-blue" name="save"><i class="fa fa-save">&nbsp;Save Module</i></button>
			</div>
		</div>

<?php
if(isset($_POST['save']))
{
//$id = $_POST['selector'];
$tide = mysqli_real_escape_string($link, $_POST['tide']);

$verify = mysqli_query($link, "SELECT * FROM staff_module_permission WHERE staff_tid = '$tide'") or die ("Error: " . mysqli_error($link));
$get_verify = mysqli_num_rows($verify);
if($get_verify == 1)
{
	echo "<script>alert('Error: Module Permission Already granted. Please visit permission list to see!!'); </script>";
}
else{
//Starting of Branch Settings
$branch_tab = (isset($_POST['branch_tab'])) ? 1 : 0;
$add_branch = (isset($_POST['add_branch'])) ? 1 : 0;
$list_branch = (isset($_POST['list_branch'])) ? 1 : 0;
$edit_branch = (isset($_POST['edit_branch'])) ? 1 : 0;
$del_branch = (isset($_POST['del_branch'])) ? 1 : 0;

//Starting of Cooperatives Settings
$cooperative_tab = (isset($_POST['cooperative_tab'])) ? 1 : 0;
$add_cooperative = (isset($_POST['add_cooperative'])) ? 1 : 0;
$add_coop_member = (isset($_POST['add_coop_member'])) ? 1 : 0;
$view_cooperative = (isset($_POST['view_cooperative'])) ? 1 : 0;
$view_members = (isset($_POST['view_members'])) ? 1 : 0;
$update_cooperative = (isset($_POST['update_cooperative'])) ? 1 : 0;
$update_coop_members = (isset($_POST['update_coop_members'])) ? 1 : 0;
$sendsms_cooperative = (isset($_POST['sendsms_cooperative'])) ? 1 : 0;

//Starting of Institution Settings
$institution_tab = (isset($_POST['institution_tab'])) ? 1 : 0;
$add_institution = (isset($_POST['add_institution'])) ? 1 : 0;
$view_institution = (isset($_POST['view_institution'])) ? 1 : 0;
$view_inst_members = (isset($_POST['view_inst_members'])) ? 1 : 0;
$update_inst_members = (isset($_POST['update_inst_members'])) ? 1 : 0;
$update_institution = (isset($_POST['update_institution'])) ? 1 : 0;
$sendsms_institution = (isset($_POST['sendsms_institution'])) ? 1 : 0;

//Starting of Agent Settings
$agent_tab = (isset($_POST['agent_tab'])) ? 1 : 0;
$add_agent = (isset($_POST['add_agent'])) ? 1 : 0;
$view_agent = (isset($_POST['view_agent'])) ? 1 : 0;
$update_agent = (isset($_POST['update_agent'])) ? 1 : 0;
$sendsms_agents = (isset($_POST['sendsms_agents'])) ? 1 : 0;

//Starting of Subscription Settings
$subscription_tab = (isset($_POST['subscription_tab'])) ? 1 : 0;
$setup_saas_plan = (isset($_POST['setup_saas_plan'])) ? 1 : 0;
$view_subscription_plan = (isset($_POST['view_subscription_plan'])) ? 1 : 0;
$update_saas_plan = (isset($_POST['update_saas_plan'])) ? 1 : 0;
$view_pending_sub = (isset($_POST['view_pending_sub'])) ? 1 : 0;
$view_paid_sub = (isset($_POST['view_paid_sub'])) ? 1 : 0;
$view_deactivated_sub = (isset($_POST['view_deactivated_sub'])) ? 1 : 0;
$view_expired_sub = (isset($_POST['view_expired_sub'])) ? 1 : 0;

//Starting of Customer Mgt. Settings
$customer_tab = (isset($_POST['customer_tab'])) ? 1 : 0;
$add_customer = (isset($_POST['add_customer'])) ? 1 : 0;
$list_customer = (isset($_POST['list_customer'])) ? 1 : 0;
$list_borrower = (isset($_POST['list_borrower'])) ? 1 : 0;
$del_customer = (isset($_POST['del_customer'])) ? 1 : 0;
$view_acct_info = (isset($_POST['view_acct_info'])) ? 1 : 0;
$edit_customer = (isset($_POST['edit_customer'])) ? 1 : 0;
$add_to_borrower = (isset($_POST['add_to_borrower'])) ? 1 : 0;
$send_sms = (isset($_POST['send_sms'])) ? 1 : 0;
$send_email = (isset($_POST['send_email'])) ? 1 : 0;

//Starting of Wallet Mgt. Settings
$wallet_tab = (isset($_POST['wallet_tab'])) ? 1 : 0;
$transfer_fund = (isset($_POST['transfer_fund'])) ? 1 : 0;
$add_transfer_recipients = (isset($_POST['add_transfer_recipients'])) ? 1 : 0;
$add_fund_tobalance = (isset($_POST['add_fund_tobalance'])) ? 1 : 0;
$view_transfer_recipients = (isset($_POST['view_transfer_recipients'])) ? 1 : 0;
$buy_airtime = (isset($_POST['buy_airtime'])) ? 1 : 0;

//Starting of Referral Mgt. Settings
$referral_tab = (isset($_POST['referral_tab'])) ? 1 : 0;
$set_referral_plan = (isset($_POST['set_referral_plan'])) ? 1 : 0;
$view_compensation_plan = (isset($_POST['view_compensation_plan'])) ? 1 : 0;
$update_compensation_plan = (isset($_POST['update_compensation_plan'])) ? 1 : 0;
$confirm_bonus = (isset($_POST['confirm_bonus'])) ? 1 : 0;
$bonus_transaction = (isset($_POST['bonus_transaction'])) ? 1 : 0;

//Starting of Loan Mgt. Settings
$loan_tab = (isset($_POST['loan_tab'])) ? 1 : 0;
$add_loan = (isset($_POST['add_loan'])) ? 1 : 0;
$list_loan = (isset($_POST['list_loan'])) ? 1 : 0;
$due_loan = (isset($_POST['due_loan'])) ? 1 : 0;
$past_maturity_date_loan = (isset($_POST['past_maturity_date_loan'])) ? 1 : 0;
$view_poutstanding = (isset($_POST['view_poutstanding'])) ? 1 : 0;
$approve_loan = (isset($_POST['approve_loan'])) ? 1 : 0;
$view_loan_details = (isset($_POST['view_loan_details'])) ? 1 : 0;
$del_loan = (isset($_POST['del_loan'])) ? 1 : 0;
$print_loan = (isset($_POST['print_loan'])) ? 1 : 0;
$export_excel_loan = (isset($_POST['export_excel_loan'])) ? 1 : 0;

//Starting of Loan Payment Settings
$loanpay_tab = (isset($_POST['loanpay_tab'])) ? 1 : 0;
$add_payment = (isset($_POST['add_payment'])) ? 1 : 0;
$list_payment = (isset($_POST['list_payment'])) ? 1 : 0;
$del_payment = (isset($_POST['del_payment'])) ? 1 : 0;
$print_payment = (isset($_POST['print_payment'])) ? 1 : 0;
$export_excel_lpayment = (isset($_POST['export_excel_lpayment'])) ? 1 : 0;

//Starting of Employee Management Settings
$emp_tab = (isset($_POST['emp_tab'])) ? 1 : 0;
$add_emp = (isset($_POST['add_emp'])) ? 1 : 0;
$list_emp = (isset($_POST['list_emp'])) ? 1 : 0;
$edit_emp = (isset($_POST['edit_emp'])) ? 1 : 0;
$send_empsms = (isset($_POST['send_empsms'])) ? 1 : 0;
$del_emp = (isset($_POST['del_emp'])) ? 1 : 0;
$print_emp = (isset($_POST['print_emp'])) ? 1 : 0;
$export_excel_emp = (isset($_POST['export_excel_emp'])) ? 1 : 0;

//Starting of Expenses Settings
$expense_tab = (isset($_POST['expense_tab'])) ? 1 : 0;
$add_expense = (isset($_POST['add_expense'])) ? 1 : 0;
$list_expense = (isset($_POST['list_expense'])) ? 1 : 0;
$edit_expense = (isset($_POST['edit_expense'])) ? 1 : 0;
$del_expense = (isset($_POST['del_expense'])) ? 1 : 0;

//Starting of Payroll Settings
$payroll_tab = (isset($_POST['payroll_tab'])) ? 1 : 0;
$add_payroll = (isset($_POST['add_payroll'])) ? 1 : 0;
$list_payroll = (isset($_POST['list_payroll'])) ? 1 : 0;
$edit_payroll = (isset($_POST['edit_payroll'])) ? 1 : 0;
$del_payroll = (isset($_POST['del_payroll'])) ? 1 : 0;

//Starting of Savings System Settings
$savings_tab = (isset($_POST['savings_tab'])) ? 1 : 0;
$s_deposit_fund = (isset($_POST['s_deposit_fund'])) ? 1 : 0;
$s_withdraw_fund = (isset($_POST['s_withdraw_fund'])) ? 1 : 0;
$list_transaction = (isset($_POST['list_transaction'])) ? 1 : 0;
$verify_account = (isset($_POST['verify_account'])) ? 1 : 0;
$del_transaction = (isset($_POST['del_transaction'])) ? 1 : 0;
$print_transaction = (isset($_POST['print_transaction'])) ? 1 : 0;
$export_excel_transaction = (isset($_POST['export_excel_transaction'])) ? 1 : 0;

//Starting of Report System Settings
$report_tab = (isset($_POST['report_tab'])) ? 1 : 0;
$borrower_report = (isset($_POST['borrower_report'])) ? 1 : 0;
$collection_report = (isset($_POST['collection_report'])) ? 1 : 0;
$collector_report = (isset($_POST['collector_report'])) ? 1 : 0;

//Starting of Global Settings
$config_tab = (isset($_POST['config_tab'])) ? 1 : 0;
$company_setup = (isset($_POST['company_setup'])) ? 1 : 0;
$smsgateway_settings = (isset($_POST['smsgateway_settings'])) ? 1 : 0;

$insert = mysqli_query($link, "INSERT INTO staff_module_permission VALUES(null,'$tide','$branch_tab','$add_branch','$list_branch','$edit_branch','$del_branch','$customer_tab','$add_customer','$list_customer','$list_borrower','$send_sms','$send_email','$del_customer','$view_acct_info','$edit_customer','$add_to_borrower','$wallet_tab','$transfer_fund','$add_transfer_recipients','$add_fund_tobalance','$view_transfer_recipients','$buy_airtime','$loan_tab','$add_loan','$list_loan','$due_loan','$past_maturity_date_loan','$view_poutstanding','$approve_loan','$view_loan_details','$del_loan','$print_loan','$export_excel_loan','$loanpay_tab','$add_payment','$list_payment','$del_payment','$print_payment','$export_excel_lpayment','$emp_tab','$add_emp','$list_emp','$edit_emp','$send_empsms','$del_emp','$print_emp','$export_excel_emp','$expense_tab','$add_expense','$list_expense','$del_expense','$edit_expense','$payroll_tab','$add_payroll','$list_payroll','$del_payroll','$edit_payroll','$savings_tab','$s_deposit_fund','$s_withdraw_fund','$list_transaction','$verify_account','$del_transaction','$print_transaction','$export_excel_transaction','$report_tab','$borrower_report','$collection_report','$collector_report','$config_tab','$company_setup','$smsgateway_settings','$cooperative_tab','$add_cooperative','$add_coop_member','$view_cooperative','$view_members','$update_cooperative','$update_coop_members','$sendsms_cooperative','$institution_tab','$add_institution','$view_institution','$view_inst_members','$update_inst_members','$update_institution','$sendsms_institution','$subscription_tab','$setup_saas_plan','$view_subscription_plan','$update_saas_plan','$view_pending_sub','$view_paid_sub','$view_deactivated_sub','$view_expired_sub','$referral_tab','$set_referral_plan','$view_compensation_plan','$update_compensation_plan','$confirm_bonus','$bonus_transaction','$agent_tab','$add_agent','$view_agent','$update_agent','$sendsms_agents')") or die ("Error: " . mysqli_error($link));

if(!$insert)
{
echo "<script>alert('Record not inserted.....Please try again later!'); </script>";
}
else{
echo "<script>alert('Permission Added Successfully!!'); </script>";
echo "<script>window.location='permission_list.php?id=".$_SESSION['tid']."&&mid=".base64_encode("413")."'; </script>";
}
}
}
?>
</div>				
</form>
                </div>

				</div>	
				</div>
			
</div>	
					
       
</div>