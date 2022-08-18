<div class="row">
    
		    <section class="content">  
	        <div class="box box-danger">
            <div class="box-body">
              <div class="table-responsive">
             <div class="box-body">
<form method="post">
			 <a href="agentperm.php?id=<?php echo $_SESSION['tid']; ?>&&mid=<?php echo base64_encode("413"); ?>"><button type="button" class="btn btn-flat bg-orange"><i class="fa fa-mail-reply-all"></i>&nbsp;Back</button> </a> 
	<hr>	

<form class="form-horizontal" method="post" enctype="multipart/form-data">

<div class="box-body">

			<div class="form-group">
				<label for="" class="col-sm-2 control-label" style="color:blue;">User Name:</label>
                <div class="col-sm-10">
				<?php
					$id = $_GET['id'];
					$search_user = mysqli_query($link, "SELECT * FROM agent_data WHERE agentid = '$id'") or die ("Error: " . mysqli_error($link));
					while($get_users = mysqli_fetch_array($search_user))
					{
					?>		
					<b style="color: orange;"><?php echo $get_users['fname']; ?></b>
				<?php } ?>
                </div>			
            </div>
			<div class="form-group">
			<hr><hr>
			<div>
			
<?php
$search = mysqli_query($link, "SELECT * FROM staff_module_permission WHERE staff_tid = '$id'") or die (mysqli_error($link));
$have = mysqli_fetch_array($search);
$idme= $have['id'];
?>			
			<table width="100%" border="1">
			  <tr bgcolor="orange">
				<td width="25%"><div align="left"><strong>&nbsp;BRANCH SETTINGS</strong></div></td>
			  </tr>
			
  			  <tr width="100%">
			   	<td><div align="center">Branch Tab:<p> <input name="branch_tab" type="checkbox" value="<?php echo ($have['branch_tab'] == "0") ? 1 : $have['branch_tab']; ?>" <?php echo ($have['branch_tab'] == "0") ? '' : 'checked'; ?>><p></div></td>
			   	<td><div align="center">Add:<p> <input name="add_branch" type="checkbox" value="<?php echo ($have['add_branch'] == "0") ? 1 : $have['add_branch']; ?>" <?php echo ($have['add_branch'] == "0") ? '' : 'checked'; ?>></p></div></td>
			   	<td><div align="center">List/View:<p> <input name="list_branch" type="checkbox" value="<?php echo ($have['list_branch'] == "0") ? 1 : $have['list_branch']; ?>" <?php echo ($have['list_branch'] == "0") ? '' : 'checked'; ?>></p></div></td>
			   	<td><div align="center">Edit/Update:<p> <input name="edit_branch" type="checkbox" value="<?php echo ($have['edit_branch'] == "0") ? 1 : $have['edit_branch']; ?>" <?php echo ($have['edit_branch'] == "0") ? '' : 'checked'; ?>></p></div></td>
				<td><div align="center">Delete:<p> <input name="del_branch" type="checkbox" value="<?php echo ($have['del_branch'] == "0") ? 1 : $have['del_branch']; ?>" <?php echo ($have['del_branch'] == "0") ? '' : 'checked'; ?>></p></div></td>
  			  </tr>
			  
			</table>
			<hr>

			<table width="100%" border="1">
			  <tr bgcolor="orange">
				<td width="25%"><div align="left"><strong>&nbsp;COOPERATIVE SETTINGS</strong></div></td>
			  </tr>
			
  			  <tr width="100%">
			   	<td><div align="center">Cooperative Tab:<p> <input name="cooperative_tab" type="checkbox" value="<?php echo ($have['cooperative_tab'] == "0") ? 1 : $have['cooperative_tab']; ?>" <?php echo ($have['cooperative_tab'] == "0") ? '' : 'checked'; ?>><p></div></td>
			   	<td><div align="center">Add Cooperative:<p> <input name="add_cooperative" type="checkbox" value="<?php echo ($have['add_cooperative'] == "0") ? 1 : $have['add_cooperative']; ?>" <?php echo ($have['add_cooperative'] == "0") ? '' : 'checked'; ?>></p></div></td>
			   	<td><div align="center">Add Member:<p> <input name="add_coop_member" type="checkbox" value="<?php echo ($have['add_coop_member'] == "0") ? 1 : $have['add_coop_member']; ?>" <?php echo ($have['add_coop_member'] == "0") ? '' : 'checked'; ?>></p></div></td>
			   	<td><div align="center">View Cooperatives:<p> <input name="view_cooperative" type="checkbox" value="<?php echo ($have['view_cooperative'] == "0") ? 1 : $have['view_cooperative']; ?>" <?php echo ($have['view_cooperative'] == "0") ? '' : 'checked'; ?>></p></div></td>
				<td><div align="center">View Members:<p> <input name="view_members" type="checkbox" value="<?php echo ($have['view_members'] == "0") ? 1 : $have['view_members']; ?>" <?php echo ($have['view_members'] == "0") ? '' : 'checked'; ?>></p></div></td>
  			  </tr>

  			  <tr width="100%">
			   	<td><div align="center">Update Cooperative:<p> <input name="update_cooperative" type="checkbox" value="<?php echo ($have['update_cooperative'] == "0") ? 1 : $have['update_cooperative']; ?>" <?php echo ($have['update_cooperative'] == "0") ? '' : 'checked'; ?>><p></div></td>
			   	<td><div align="center">Update Members:<p> <input name="update_coop_members" type="checkbox" value="<?php echo ($have['update_coop_members'] == "0") ? 1 : $have['update_coop_members']; ?>" <?php echo ($have['update_coop_members'] == "0") ? '' : 'checked'; ?>><p></div></td>
			   	<td><div align="center">Send SMS:<p> <input name="sendsms_cooperative" type="checkbox" value="<?php echo ($have['sendsms_cooperative'] == "0") ? 1 : $have['sendsms_cooperative']; ?>" <?php echo ($have['sendsms_cooperative'] == "0") ? '' : 'checked'; ?>></p></div></td>
  			  </tr>
			  
			</table>
			<hr>

			<table width="100%" border="1">
			  <tr bgcolor="orange">
				<td width="25%"><div align="left"><strong>&nbsp;INSTITUTION SETTINGS</strong></div></td>
			  </tr>
			
  			  <tr width="100%">
			   	<td><div align="center">Institution Tab:<p> <input name="institution_tab" type="checkbox" value="<?php echo ($have['institution_tab'] == "0") ? 1 : $have['institution_tab']; ?>" <?php echo ($have['institution_tab'] == "0") ? '' : 'checked'; ?>><p></div></td>
			   	<td><div align="center">Add Institution:<p> <input name="add_institution" type="checkbox" value="<?php echo ($have['add_institution'] == "0") ? 1 : $have['add_institution']; ?>" <?php echo ($have['add_institution'] == "0") ? '' : 'checked'; ?>></p></div></td>
			   	<td><div align="center">View Institution:<p> <input name="view_institution" type="checkbox" value="<?php echo ($have['view_institution'] == "0") ? 1 : $have['view_institution']; ?>" <?php echo ($have['view_institution'] == "0") ? '' : 'checked'; ?>></p></div></td>
			   	<td><div align="center">View Members:<p> <input name="view_inst_members" type="checkbox" value="<?php echo ($have['view_inst_members'] == "0") ? 1 : $have['view_inst_members']; ?>" <?php echo ($have['view_inst_members'] == "0") ? '' : 'checked'; ?>></p></div></td>
				<td><div align="center">Update Members:<p> <input name="update_inst_members" type="checkbox" value="<?php echo ($have['update_inst_members'] == "0") ? 1 : $have['update_inst_members']; ?>" <?php echo ($have['update_inst_members'] == "0") ? '' : 'checked'; ?>></p></div></td>
  			  </tr>

  			  <tr width="100%">
			   	<td><div align="center">Update Institution:<p> <input name="update_institution" type="checkbox" value="<?php echo ($have['update_institution'] == "0") ? 1 : $have['update_institution']; ?>" <?php echo ($have['update_institution'] == "0") ? '' : 'checked'; ?>><p></div></td>
			   	<td><div align="center">Send SMS:<p> <input name="sendsms_institution" type="checkbox" value="<?php echo ($have['sendsms_institution'] == "0") ? 1 : $have['sendsms_institution']; ?>" <?php echo ($have['sendsms_institution'] == "0") ? '' : 'checked'; ?>></p></div></td>
  			  </tr>
			  
			</table>
			<hr>

			<table width="100%" border="1">
			  <tr bgcolor="orange">
				<td width="25%"><div align="left"><strong>&nbsp;AGENTS SETTINGS</strong></div></td>
			  </tr>
			
  			  <tr width="100%">
			   	<td><div align="center">Agent Tab:<p> <input name="agent_tab" type="checkbox" value="<?php echo ($have['agent_tab'] == "0") ? 1 : $have['agent_tab']; ?>" <?php echo ($have['agent_tab'] == "0") ? '' : 'checked'; ?>><p></div></td>
			   	<td><div align="center">Add:<p> <input name="add_agent" type="checkbox" value="<?php echo ($have['add_agent'] == "0") ? 1 : $have['add_agent']; ?>" <?php echo ($have['add_agent'] == "0") ? '' : 'checked'; ?>></p></div></td>
			   	<td><div align="center">List/View:<p> <input name="view_agent" type="checkbox" value="<?php echo ($have['view_agent'] == "0") ? 1 : $have['view_agent']; ?>" <?php echo ($have['view_agent'] == "0") ? '' : 'checked'; ?>></p></div></td>
			   	<td><div align="center">Edit/Update:<p> <input name="update_agent" type="checkbox" value="<?php echo ($have['update_agent'] == "0") ? 1 : $have['update_agent']; ?>" <?php echo ($have['update_agent'] == "0") ? '' : 'checked'; ?>></p></div></td>
				<td><div align="center">Send SMS to Agents:<p> <input name="sendsms_agents" type="checkbox" value="<?php echo ($have['sendsms_agents'] == "0") ? 1 : $have['sendsms_agents']; ?>" <?php echo ($have['sendsms_agents'] == "0") ? '' : 'checked'; ?>></p></div></td>
  			  </tr>
			  
			</table>
			<hr>

			<table width="100%" border="1">
			  <tr bgcolor="orange">
				<td width="25%"><div align="left"><strong>&nbsp;SUBSCRIPTION SETTINGS</strong></div></td>
			  </tr>
			
  			  <tr width="100%">
			   	<td><div align="center">Subscription Tab:<p> <input name="subscription_tab" type="checkbox" value="<?php echo ($have['subscription_tab'] == "0") ? 1 : $have['subscription_tab']; ?>" <?php echo ($have['subscription_tab'] == "0") ? '' : 'checked'; ?>><p></div></td>
			   	<td><div align="center">Setup Saas Plan:<p> <input name="setup_saas_plan" type="checkbox" value="<?php echo ($have['setup_saas_plan'] == "0") ? 1 : $have['setup_saas_plan']; ?>" <?php echo ($have['setup_saas_plan'] == "0") ? '' : 'checked'; ?>></p></div></td>
			   	<td><div align="center">View Subscription Plan:<p> <input name="view_subscription_plan" type="checkbox" value="<?php echo ($have['view_subscription_plan'] == "0") ? 1 : $have['view_subscription_plan']; ?>" <?php echo ($have['view_subscription_plan'] == "0") ? '' : 'checked'; ?>></p></div></td>
			   	<td><div align="center">Update Saas Plan:<p> <input name="update_saas_plan" type="checkbox" value="<?php echo ($have['update_saas_plan'] == "0") ? 1 : $have['update_saas_plan']; ?>" <?php echo ($have['update_saas_plan'] == "0") ? '' : 'checked'; ?>></p></div></td>
				<td><div align="center">View Pending Subscription:<p> <input name="view_pending_sub" type="checkbox" value="<?php echo ($have['view_pending_sub'] == "0") ? 1 : $have['view_pending_sub']; ?>" <?php echo ($have['view_pending_sub'] == "0") ? '' : 'checked'; ?>></p></div></td>
  			  </tr>

  			  <tr width="100%">
			   	<td><div align="center">View Paid Subscription:<p> <input name="view_paid_sub" type="checkbox" value="<?php echo ($have['view_paid_sub'] == "0") ? 1 : $have['view_paid_sub']; ?>" <?php echo ($have['view_paid_sub'] == "0") ? '' : 'checked'; ?>><p></div></td>
			   	<td><div align="center">View Deactivated Subscription:<p> <input name="view_deactivated_sub" type="checkbox" value="<?php echo ($have['view_deactivated_sub'] == "0") ? 1 : $have['view_deactivated_sub']; ?>" <?php echo ($have['view_deactivated_sub'] == "0") ? '' : 'checked'; ?>></p></div></td>
			   	<td><div align="center">View Expired Subscription:<p> <input name="view_expired_sub" type="checkbox" value="<?php echo ($have['view_expired_sub'] == "0") ? 1 : $have['view_expired_sub']; ?>" <?php echo ($have['view_expired_sub'] == "0") ? '' : 'checked'; ?>></p></div></td>
  			  </tr>
			  
			</table>
			<hr>
			
			<table width="100%" border="1">
			  <tr bgcolor="orange">
				<td width="25%"><div align="left"><strong>&nbsp;CUSTOMER MGT. SETTINGS</strong></div></td>
			  </tr>
			
  			  <tr>
			   	<td><div align="center">Customer Tab:<p> <input name="customer_tab" type="checkbox" value="<?php echo ($have['customer_tab'] == "0") ? 1 : $have['customer_tab']; ?>" <?php echo ($have['customer_tab'] == "0") ? '' : 'checked'; ?>></p></div></td>
			   	<td><div align="center">Add:<p> <input name="add_customer" type="checkbox" value="<?php echo ($have['new_customer'] == "0") ? 1 : $have['new_customer']; ?>" <?php echo ($have['new_customer'] == "0") ? '' : 'checked'; ?>></p></div></td>
			   	<td><div align="center">List/View All:<p> <input name="list_customer" type="checkbox" value="<?php echo ($have['all_customer'] == "0") ? 1 : $have['all_customer']; ?>" <?php echo ($have['all_customer'] == "0") ? '' : 'checked'; ?>></p></div></td>
				<td><div align="center">View Borrowers:<p> <input name="list_borrower" type="checkbox" value="<?php echo ($have['borrower_list'] == "0") ? 1 : $have['borrower_list']; ?>" <?php echo ($have['borrower_list'] == "0") ? '' : 'checked'; ?>></p></div></td>
				<td><div align="center">Delete:<p> <input name="del_customer" type="checkbox" value="<?php echo ($have['del_customer'] == "0") ? 1 : $have['del_customer']; ?>" <?php echo ($have['del_customer'] == "0") ? '' : 'checked'; ?>></p></div></td>
				
  			  </tr>
			  <tr>
				<td><div align="center">View Account Info:<p> <input name="view_acct_info" type="checkbox" value="<?php echo ($have['view_account_info'] == "0") ? 1 : $have['view_account_info']; ?>" <?php echo ($have['view_account_info'] == "0") ? '' : 'checked'; ?>></p></div></td>
  			   	<td><div align="center">Edit/Update:<p> <input name="edit_customer" type="checkbox" value="<?php echo ($have['update_info'] == "0") ? 1 : $have['update_info']; ?>" <?php echo ($have['update_info'] == "0") ? '' : 'checked'; ?>></p></div></td>
  			   	<td><div align="center">Add borrower list:<p> <input name="add_to_borrower" type="checkbox" value="<?php echo ($have['add_to_borrower'] == "0") ? 1 : $have['add_to_borrower']; ?>" <?php echo ($have['add_to_borrower'] == "0") ? '' : 'checked'; ?>></p></div></td>
  			   	<td><div align="center">Send SMS:<p> <input name="send_sms" type="checkbox" value="<?php echo ($have['send_sms'] == "0") ? 1 : $have['send_sms']; ?>" <?php echo ($have['send_sms'] == "0") ? '' : 'checked'; ?>></p></div></td>
  				<td><div align="center">Send Email:<p> <input name="send_email" type="checkbox" value="<?php echo ($have['send_email'] == "0") ? 1 : $have['send_email']; ?>" <?php echo ($have['send_email'] == "0") ? '' : 'checked'; ?>></p></div></td>
			  </tr>
				  
			</table>
			<hr>
			
			<table width="100%" border="1">
			  <tr bgcolor="orange">
				<td width="25%"><div align="left"><strong>&nbsp;WALLET MGT. SETTINGS</strong></div></td>
			  </tr>
			
  			  <tr>
			   	<td><div align="center">Wallet Tab:<p> <input name="wallet_tab" type="checkbox" value="<?php echo ($have['mywallet_tab'] == "0") ? 1 : $have['mywallet_tab']; ?>" <?php echo ($have['mywallet_tab'] == "0") ? '' : 'checked'; ?>></p></div></td>
			   	<td><div align="center">Transfer Fund:<p> <input name="transfer_fund" type="checkbox" value="<?php echo ($have['transfer_money'] == "0") ? 1 : $have['transfer_money']; ?>" <?php echo ($have['transfer_money'] == "0") ? '' : 'checked'; ?>></p></div></td>
			   	<td><div align="center">Add Transfer Recipients:<p> <input name="add_transfer_recipients" type="checkbox" value="<?php echo ($have['add_transfer_recipients'] == "0") ? 1 : $have['add_transfer_recipients']; ?>" <?php echo ($have['add_transfer_recipients'] == "0") ? '' : 'checked'; ?>></p></div></td>
			   	<td><div align="center">Add Fund to Balance:<p> <input name="add_fund_tobalance" type="checkbox" value="<?php echo ($have['add_fund_tobalance'] == "0") ? 1 : $have['add_fund_tobalance']; ?>" <?php echo ($have['add_fund_tobalance'] == "0") ? '' : 'checked'; ?>></p></div></td>
				<td><div align="center">View Transfer Recipients:<p> <input name="view_transfer_recipients" type="checkbox" value="<?php echo ($have['view_transfer_recipients'] == "0") ? 1 : $have['view_transfer_recipients']; ?>" <?php echo ($have['view_transfer_recipients'] == "0") ? '' : 'checked'; ?>></p></div></td>
  			  </tr>

  			  <tr>
			   	<td><div align="center">Buy Airtime:<p> <input name="buy_airtime" type="checkbox" value="<?php echo ($have['buy_airtime'] == "0") ? 1 : $have['buy_airtime']; ?>" <?php echo ($have['buy_airtime'] == "0") ? '' : 'checked'; ?>></p></div></td>
			   </tr>
				  
			</table>
			<hr>

			<table width="100%" border="1">
			  <tr bgcolor="orange">
				<td width="25%"><div align="left"><strong>&nbsp;REFERRAL MANAGER</strong></div></td>
			  </tr>
			
  			  <tr>
			   	<td><div align="center">Referral Tab:<p> <input name="referral_tab" type="checkbox" value="<?php echo ($have['referral_tab'] == "0") ? 1 : $have['referral_tab']; ?>" <?php echo ($have['referral_tab'] == "0") ? '' : 'checked'; ?>></p></div></td>
			   	<td><div align="center">Set Referral Plan:<p> <input name="set_referral_plan" type="checkbox" value="<?php echo ($have['set_referral_plan'] == "0") ? 1 : $have['set_referral_plan']; ?>" <?php echo ($have['set_referral_plan'] == "0") ? '' : 'checked'; ?>></p></div></td>
			   	<td><div align="center">View Compensation Plan:<p> <input name="view_compensation_plan" type="checkbox" value="<?php echo ($have['view_compensation_plan'] == "0") ? 1 : $have['view_compensation_plan']; ?>" <?php echo ($have['view_compensation_plan'] == "0") ? '' : 'checked'; ?>></p></div></td>
			   	<td><div align="center">Update Compensation Plan:<p> <input name="update_compensation_plan" type="checkbox" value="<?php echo ($have['update_compensation_plan'] == "0") ? 1 : $have['update_compensation_plan']; ?>" <?php echo ($have['update_compensation_plan'] == "0") ? '' : 'checked'; ?>></p></div></td>
				<td><div align="center">Confirm Bonus:<p> <input name="confirm_bonus" type="checkbox" value="<?php echo ($have['confirm_bonus'] == "0") ? 1 : $have['confirm_bonus']; ?>" <?php echo ($have['confirm_bonus'] == "0") ? '' : 'checked'; ?>></p></div></td>
  			  </tr>

  			  <tr>
			   	<td><div align="center">Bonus Transaction:<p> <input name="bonus_transaction" type="checkbox" value="<?php echo ($have['bonus_transaction'] == "0") ? 1 : $have['bonus_transaction']; ?>" <?php echo ($have['bonus_transaction'] == "0") ? '' : 'checked'; ?>></p></div></td>
			   </tr>
				  
			</table>
			<hr>
			
			<table width="100%" border="1">
			  <tr bgcolor="orange">
				<td width="25%"><div align="left"><strong>&nbsp;LOAN MGT. SETTINGS</strong></div></td>
			  </tr>
			
  			  <tr>
			   	<td><div align="center">Loan Tab:<p> <input name="loan_tab" type="checkbox" value="<?php echo ($have['loan_tab'] == "0") ? 1 : $have['loan_tab']; ?>" <?php echo ($have['loan_tab'] == "0") ? '' : 'checked'; ?>></p></div></td>
			   	<td><div align="center">Add:<p> <input name="add_loan" type="checkbox" value="<?php echo ($have['new_loan'] == "0") ? 1 : $have['new_loan']; ?>" <?php echo ($have['new_loan'] == "0") ? '' : 'checked'; ?>></p></div></td>
			   	<td><div align="center">List/View All:<p> <input name="list_loan" type="checkbox" value="<?php echo ($have['view_loan'] == "0") ? 1 : $have['view_loan']; ?>" <?php echo ($have['view_loan'] == "0") ? '' : 'checked'; ?>></p></div></td>
			   	<td><div align="center">Due Loan:<p> <input name="due_loan" type="checkbox" value="<?php echo ($have['due_loan'] == "0") ? 1 : $have['due_loan']; ?>" <?php echo ($have['due_loan'] == "0") ? '' : 'checked'; ?>></p></div></td>
				<td><div align="center">View Past Maturity Date Loan:<p> <input name="past_maturity_date_loan" type="checkbox" value="<?php echo ($have['past_maturity_date'] == "0") ? 1 : $have['past_maturity_date']; ?>" <?php echo ($have['past_maturity_date'] == "0") ? '' : 'checked'; ?>></p></div></td>
  			  </tr>
			  <tr>
  			   	<td><div align="center">View Principal Outstanding Loan:<p> <input name="view_poutstanding" type="checkbox" value="<?php echo ($have['principal_outstanding'] == "0") ? 1 : $have['principal_outstanding']; ?>" <?php echo ($have['principal_outstanding'] == "0") ? '' : 'checked'; ?>></p></div></td>
  			   	<td><div align="center">Approve Loan:<p> <input name="approve_loan" type="checkbox" value="<?php echo ($have['approve_loan'] == "0") ? 1 : $have['approve_loan']; ?>" <?php echo ($have['approve_loan'] == "0") ? '' : 'checked'; ?>></p></div></td>
  			   	<td><div align="center">View Loan Details:<p> <input name="view_loan_details" type="checkbox" value="<?php echo ($have['loan_details'] == "0") ? 1 : $have['loan_details']; ?>" <?php echo ($have['loan_details'] == "0") ? '' : 'checked'; ?>></p></div></td>
  			   	<td><div align="center">Delete Loan:<p> <input name="del_loan" type="checkbox" value="<?php echo ($have['del_loan'] == "0") ? 1 : $have['del_loan']; ?>" <?php echo ($have['del_loan'] == "0") ? '' : 'checked'; ?>></p></div></td>
  				<td><div align="center">Print:<p> <input name="print_loan" type="checkbox" value="<?php echo ($have['print_loan'] == "0") ? 1 : $have['print_loan']; ?>" <?php echo ($have['print_loan'] == "0") ? '' : 'checked'; ?>></p></div></td>
			  </tr>
			  <tr>
				<td><div align="center">Export Excel:<p> <input name="export_excel_loan" type="checkbox" value="<?php echo ($have['export_excel_loanlist'] == "0") ? 1 : $have['export_excel_loanlist']; ?>" <?php echo ($have['export_excel_loanlist'] == "0") ? '' : 'checked'; ?>></p></div></td>
			  </tr>
				  
			</table>
			<hr>
			
			<table width="100%" border="1">
			  <tr bgcolor="orange">
				<td width="25%"><div align="left"><strong>&nbsp;LOAN REPAYMENT SETTINGS</strong></div></td>
			  </tr>
			
  			  <tr>
			   	<td><div align="center">Payment Tab:<p> <input name="loanpay_tab" type="checkbox" value="<?php echo ($have['payment_tab'] == "0") ? 1 : $have['payment_tab']; ?>" <?php echo ($have['payment_tab'] == "0") ? '' : 'checked'; ?>></p></div></td>
			   	<td><div align="center">Add:<p> <input name="add_payment" type="checkbox" value="<?php echo ($have['new_payment'] == "0") ? 1 : $have['new_payment']; ?>" <?php echo ($have['new_payment'] == "0") ? '' : 'checked'; ?>></p></div></td>
			   	<td><div align="center">List/View:<p> <input name="list_payment" type="checkbox" value="<?php echo ($have['list_payment'] == "0") ? 1 : $have['list_payment']; ?>" <?php echo ($have['list_payment'] == "0") ? '' : 'checked'; ?>></p></div></td>
			   	<td><div align="center">Delete:<p> <input name="del_payment" type="checkbox" value="<?php echo ($have['del_payment'] == "0") ? 1 : $have['del_payment']; ?>" <?php echo ($have['del_payment'] == "0") ? '' : 'checked'; ?>></p></div></td>
				<td><div align="center">Print:<p> <input name="print_payment" type="checkbox" value="<?php echo ($have['print_payment'] == "0") ? 1 : $have['print_payment']; ?>" <?php echo ($have['print_payment'] == "0") ? '' : 'checked'; ?>></p></div></td>
  			  </tr>
			  <tr>
				<td><div align="center">Export Excel:<p> <input name="export_excel_lpayment" type="checkbox" value="<?php echo ($have['export_excel_lpayment'] == "0") ? 1 : $have['export_excel_lpayment']; ?>" <?php echo ($have['export_excel_lpayment'] == "0") ? '' : 'checked'; ?>></p></div></td>
			  </tr>
				  
			</table>
			<hr>
			
			<table width="100%" border="1">
			  <tr bgcolor="orange">
				<td width="25%"><div align="left"><strong>&nbsp;EMPLOYEE MGT. SETTINGS</strong></div></td>
			  </tr>
			
  			  <tr>
			   	<td><div align="center">Employee Tab:<p> <input name="emp_tab" type="checkbox" value="<?php echo ($have['emp_tab'] == "0") ? 1 : $have['emp_tab']; ?>" <?php echo ($have['emp_tab'] == "0") ? '' : 'checked'; ?>></p></div></td>
			   	<td><div align="center">Add:<p> <input name="add_emp" type="checkbox" value="<?php echo ($have['new_emp'] == "0") ? 1 : $have['new_emp']; ?>" <?php echo ($have['new_emp'] == "0") ? '' : 'checked'; ?>></p></div></td>
			   	<td><div align="center">List/View:<p> <input name="list_emp" type="checkbox" value="<?php echo ($have['list_emp'] == "0") ? 1 : $have['list_emp']; ?>" <?php echo ($have['list_emp'] == "0") ? '' : 'checked'; ?>></p></div></td>
			   	<td><div align="center">Edit/Update:<p> <input name="edit_emp" type="checkbox" value="<?php echo ($have['edit_emp'] == "0") ? 1 : $have['edit_emp']; ?>" <?php echo ($have['edit_emp'] == "0") ? '' : 'checked'; ?>></p></div></td>
				<td><div align="center">Send SMS:<p> <input name="send_empsms" type="checkbox" value="<?php echo ($have['send_empsms'] == "0") ? 1 : $have['send_empsms']; ?>" <?php echo ($have['send_empsms'] == "0") ? '' : 'checked'; ?>></p></div></td>
  			  </tr>
			  <tr>
  			   	<td><div align="center">Delete:<p> <input name="del_emp" type="checkbox" value="<?php echo ($have['del_emp'] == "0") ? 1 : $have['del_emp']; ?>" <?php echo ($have['del_emp'] == "0") ? '' : 'checked'; ?>></p></div></td>
  			   	<td><div align="center">Print:<p> <input name="print_emp" type="checkbox" value="<?php echo ($have['print_emp'] == "0") ? 1 : $have['print_emp']; ?>" <?php echo ($have['print_emp'] == "0") ? '' : 'checked'; ?>></p></div></td>
  			   	<td><div align="center">Export Excel:<p> <input name="export_excel_emp" type="checkbox" value="<?php echo ($have['export_excel_emp'] == "0") ? 1 : $have['export_excel_emp']; ?>" <?php echo ($have['export_excel_emp'] == "0") ? '' : 'checked'; ?>></p></div></td>
			  </tr>
			 
			</table>
			<hr>
			
			<table width="100%" border="1">
			  <tr bgcolor="orange">
				<td width="25%"><div align="left"><strong>&nbsp;EXPENSES SETTINGS</strong></div></td>
			  </tr>
			
  			  <tr width="100%">
			   	<td><div align="center">Expense Tab:<p> <input name="expense_tab" type="checkbox" value="<?php echo ($have['expense_tab'] == "0") ? 1 : $have['expense_tab']; ?>" <?php echo ($have['expense_tab'] == "0") ? '' : 'checked'; ?>><p></div></td>
			   	<td><div align="center">Add:<p> <input name="add_expense" type="checkbox" value="<?php echo ($have['add_expense'] == "0") ? 1 : $have['add_expense']; ?>" <?php echo ($have['add_expense'] == "0") ? '' : 'checked'; ?>></p></div></td>
			   	<td><div align="center">List/View:<p> <input name="list_expense" type="checkbox" value="<?php echo ($have['view_expense'] == "0") ? 1 : $have['view_expense']; ?>" <?php echo ($have['view_expense'] == "0") ? '' : 'checked'; ?>></p></div></td>
			   	<td><div align="center">Edit/Update:<p> <input name="edit_expense" type="checkbox" value="<?php echo ($have['edit_expense'] == "0") ? 1 : $have['edit_expense']; ?>" <?php echo ($have['edit_expense'] == "0") ? '' : 'checked'; ?>></p></div></td>
				<td><div align="center">Delete:<p> <input name="del_expense" type="checkbox" value="<?php echo ($have['del_expense'] == "0") ? 1 : $have['del_expense']; ?>" <?php echo ($have['del_expense'] == "0") ? '' : 'checked'; ?>></p></div></td>
  			  </tr>
			  
			</table>
			<hr>
			
			<table width="100%" border="1">
			  <tr bgcolor="orange">
				<td width="25%"><div align="left"><strong>&nbsp;PAYROLL SETTINGS</strong></div></td>
			  </tr>
			
  			  <tr width="100%">
			   	<td><div align="center">Payroll Tab:<p> <input name="payroll_tab" type="checkbox" value="<?php echo ($have['payroll_tab'] == "0") ? 1 : $have['payroll_tab']; ?>" <?php echo ($have['payroll_tab'] == "0") ? '' : 'checked'; ?>><p></div></td>
			   	<td><div align="center">Add:<p> <input name="add_payroll" type="checkbox" value="<?php echo ($have['add_payroll'] == "0") ? 1 : $have['add_payroll']; ?>" <?php echo ($have['add_payroll'] == "0") ? '' : 'checked'; ?>></p></div></td>
			   	<td><div align="center">List/View:<p> <input name="list_payroll" type="checkbox" value="<?php echo ($have['view_payroll'] == "0") ? 1 : $have['view_payroll']; ?>" <?php echo ($have['view_payroll'] == "0") ? '' : 'checked'; ?>></p></div></td>
			   	<td><div align="center">Edit/Update:<p> <input name="edit_payroll" type="checkbox" value="<?php echo ($have['edit_payroll'] == "0") ? 1 : $have['edit_payroll']; ?>" <?php echo ($have['edit_payroll'] == "0") ? '' : 'checked'; ?>></p></div></td>
				<td><div align="center">Delete:<p> <input name="del_payroll" type="checkbox" value="<?php echo ($have['del_payroll'] == "0") ? 1 : $have['del_payroll']; ?>" <?php echo ($have['del_payroll'] == "0") ? '' : 'checked'; ?>></p></div></td>
  			  </tr>
			  
			</table>
			<hr>
			
			<table width="100%" border="1">
			  <tr bgcolor="orange">
				<td width="25%"><div align="left"><strong>&nbsp;SAVINGS SYS. SETTINGS</strong></div></td>
			  </tr>
			
  			  <tr>
			   	<td><div align="center">Savings Tab:<p> <input name="savings_tab" type="checkbox" value="<?php echo ($have['savings_tab'] == "0") ? 1 : $have['savings_tab']; ?>" <?php echo ($have['savings_tab'] == "0") ? '' : 'checked'; ?>></p></div></td>
			   	<td><div align="center">Deposit Fund:<p> <input name="s_deposit_fund" type="checkbox" value="<?php echo ($have['deposit_money'] == "0") ? 1 : $have['deposit_money']; ?>" <?php echo ($have['deposit_money'] == "0") ? '' : 'checked'; ?>></p></div></td>
			   	<td><div align="center">Withdraw Fund:<p> <input name="s_withdraw_fund" type="checkbox" value="<?php echo ($have['withdraw_money'] == "0") ? 1 : $have['withdraw_money']; ?>" <?php echo ($have['withdraw_money'] == "0") ? '' : 'checked'; ?>></p></div></td>
			   	<td><div align="center">View All Transaction:<p> <input name="list_transaction" type="checkbox" value="<?php echo ($have['all_transaction'] == "0") ? 1 : $have['all_transaction']; ?>" <?php echo ($have['all_transaction'] == "0") ? '' : 'checked'; ?>></p></div></td>
				<td><div align="center">Enable Verify Account Button:<p> <input name="verify_account" type="checkbox" value="<?php echo ($have['verify_account'] == "0") ? 1 : $have['verify_account']; ?>" <?php echo ($have['verify_account'] == "0") ? '' : 'checked'; ?>></p></div></td>
  			  </tr>
			  <tr>
  			   	<td><div align="center">Delete:<p> <input name="del_transaction" type="checkbox" value="<?php echo ($have['del_transaction'] == "0") ? 1 : $have['del_transaction']; ?>" <?php echo ($have['del_transaction'] == "0") ? '' : 'checked'; ?>></p></div></td>
  			   	<td><div align="center">Print:<p> <input name="print_transaction" type="checkbox" value="<?php echo ($have['print_transaction'] == "0") ? 1 : $have['print_transaction']; ?>" <?php echo ($have['print_transaction'] == "0") ? '' : 'checked'; ?>></p></div></td>
  			   	<td><div align="center">Export Excel:<p> <input name="export_excel_transaction" type="checkbox" value="<?php echo ($have['export_excel_transaction'] == "0") ? 1 : $have['export_excel_transaction']; ?>" <?php echo ($have['export_excel_transaction'] == "0") ? '' : 'checked'; ?>></p></div></td>
  			   	<td><div align="center">All Savings Subscription:<p> <input name="all_savings_subscription" type="checkbox" value="<?php echo ($have['all_savings_subscription'] == "0") ? 1 : $have['all_savings_subscription']; ?>" <?php echo ($have['all_savings_subscription'] == "0") ? '' : 'checked'; ?>></p></div></td>
			  </tr>
			 
			</table>
			<hr>
			
			<table width="100%" border="1">
			  <tr bgcolor="orange">
				<td width="25%"><div align="left"><strong>&nbsp;REPORT SYS. SETTINGS</strong></div></td>
			  </tr>
			
  			  <tr>
			   	<td><div align="center">Report Tab:<p> <input name="report_tab" type="checkbox" value="<?php echo ($have['report_tab'] == "0") ? 1 : $have['report_tab']; ?>" <?php echo ($have['report_tab'] == "0") ? '' : 'checked'; ?>></p></div></td>
			   	<td><div align="center">Borrower Report:<p> <input name="borrower_report" type="checkbox" value="<?php echo ($have['borrower_report'] == "0") ? 1 : $have['borrower_report']; ?>" <?php echo ($have['borrower_report'] == "0") ? '' : 'checked'; ?>></p></div></td>
			   	<td><div align="center">Collection Report:<p> <input name="collection_report" type="checkbox" value="<?php echo ($have['collection_report'] == "0") ? 1 : $have['collection_report']; ?>" <?php echo ($have['collection_report'] == "0") ? '' : 'checked'; ?>></p></div></td>
			   	<td><div align="center">Collector Report:<p> <input name="collector_report" type="checkbox" value="<?php echo ($have['collector_report'] == "0") ? 1 : $have['collector_report']; ?>" <?php echo ($have['collector_report'] == "0") ? '' : 'checked'; ?>></p></div></td>
  			  </tr>
				  
			</table>
			<hr>

			<table width="100%" border="1">
			  <tr bgcolor="orange">
				<td width="25%"><div align="left"><strong>&nbsp;CONFIGURATION SETTINGS</strong></div></td>
			  </tr>
			
  			  <tr>
			   	<td><div align="center">Configuration Tab:<p> <input name="config_tab" type="checkbox" value="<?php echo ($have['config_tab'] == "0") ? 1 : $have['config_tab']; ?>" <?php echo ($have['config_tab'] == "0") ? '' : 'checked'; ?>></p></div></td>
			   	<td><div align="center">Company Setup:<p> <input name="company_setup" type="checkbox" value="<?php echo ($have['company_setup'] == "0") ? 1 : $have['company_setup']; ?>" <?php echo ($have['company_setup'] == "0") ? '' : 'checked'; ?>></p></div></td>
			   	<td><div align="center">SMS Gateway Settings:<p> <input name="smsgateway_settings" type="checkbox" value="<?php echo ($have['smsgateway_settings'] == "0") ? 1 : $have['smsgateway_settings']; ?>" <?php echo ($have['smsgateway_settings'] == "0") ? '' : 'checked'; ?>></p></div></td>
  			  </tr>
				  
			</table>
			<hr>
			


		<div align="right">
            <div class="box-footer">
				<button type="submit" class="btn bg-blue btn-flat" name="save"><i class="fa fa-save">&nbsp;Update Module</i></button>
			</div>
		</div>
		
<?php
if(isset($_POST['save']))
{
$tide = $_GET['id'];

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
$all_savings_subscription = (isset($_POST['all_savings_subscription'])) ? 1 : 0;

//Starting of Report System Settings
$report_tab = (isset($_POST['report_tab'])) ? 1 : 0;
$borrower_report = (isset($_POST['borrower_report'])) ? 1 : 0;
$collection_report = (isset($_POST['collection_report'])) ? 1 : 0;
$collector_report = (isset($_POST['collector_report'])) ? 1 : 0;
$contribution_report = (isset($_POST['contribution_report'])) ? 1 : 0;

//Starting of Global Settings
$config_tab = (isset($_POST['config_tab'])) ? 1 : 0;
$company_setup = (isset($_POST['company_setup'])) ? 1 : 0;
$smsgateway_settings = (isset($_POST['smsgateway_settings'])) ? 1 : 0;

$update = mysqli_query($link, "UPDATE staff_module_permission SET branch_tab='$branch_tab', add_branch='$add_branch', list_branch='$list_branch', edit_branch='$edit_branch', del_branch='$del_branch', customer_tab='$customer_tab', new_customer='$add_customer', all_customer='$list_customer', borrower_list='$list_borrower', send_sms='$send_sms', send_email='$send_email', del_customer='$del_customer', view_account_info='$view_acct_info', update_info='$edit_customer', add_to_borrower='$add_to_borrower', mywallet_tab='$wallet_tab', transfer_money='$transfer_fund', add_transfer_recipients='$add_transfer_recipients', add_fund_tobalance='$add_fund_tobalance', view_transfer_recipients='$view_transfer_recipients', buy_airtime='$buy_airtime', loan_tab='$loan_tab', new_loan='$add_loan', view_loan='$list_loan', due_loan='$due_loan', past_maturity_date='$past_maturity_date_loan', principal_outstanding='$view_poutstanding', approve_loan='$approve_loan', loan_details='$view_loan_details', del_loan='$del_loan', print_loan='$print_loan', export_excel_loanlist='$export_excel_loan', payment_tab='$loanpay_tab', new_payment='$add_payment', list_payment='$list_payment', del_payment='$del_payment', print_payment='$print_payment', export_excel_lpayment='$export_excel_lpayment', emp_tab='$emp_tab', new_emp='$add_emp', list_emp='$list_emp', edit_emp='$edit_emp', send_empsms='$send_empsms', del_emp='$del_emp', print_emp='$print_emp', export_excel_emp='$export_excel_emp', expense_tab='$expense_tab', add_expense='$add_expense', view_expense='$list_expense', del_expense='$del_expense', edit_expense='$edit_expense', payroll_tab='$payroll_tab', add_payroll='$add_payroll', view_payroll='$list_payroll', del_payroll='$del_payroll', edit_payroll='$edit_payroll', savings_tab='$savings_tab', deposit_money='$s_deposit_fund', withdraw_money='$s_withdraw_fund', all_transaction='$list_transaction', verify_account='$verify_account', del_transaction='$del_transaction', print_transaction='$print_transaction', export_excel_transaction='$export_excel_transaction', report_tab='$report_tab', borrower_report='$borrower_report', collection_report='$collection_report', collector_report='$collector_report', config_tab='$config_tab', company_setup='$company_setup', smsgateway_settings='$smsgateway_settings', cooperative_tab='$cooperative_tab', add_cooperative='$add_cooperative', add_coop_member='$add_coop_member', view_cooperative='$view_cooperative', view_members='$view_members', update_cooperative='$update_cooperative', update_coop_members='$update_coop_members', sendsms_cooperative='$sendsms_cooperative', institution_tab='$institution_tab', add_institution='$add_institution', view_institution='$view_institution', view_inst_members='$view_inst_members', update_inst_members='$update_inst_members', update_institution='$update_institution', sendsms_institution='$sendsms_institution', subscription_tab='$subscription_tab', setup_saas_plan='$setup_saas_plan', view_subscription_plan='$view_subscription_plan', update_saas_plan='$update_saas_plan', view_pending_sub='$view_pending_sub', view_paid_sub='$view_paid_sub', view_deactivated_sub='$view_deactivated_sub', view_expired_sub='$view_expired_sub', referral_tab='$referral_tab', set_referral_plan='$set_referral_plan', view_compensation_plan='$view_compensation_plan', update_compensation_plan='$update_compensation_plan', confirm_bonus='$confirm_bonus', bonus_transaction='$bonus_transaction', agent_tab='$agent_tab', add_agent='$add_agent', view_agent='$view_agent', update_agent='$update_agent', sendsms_agents='$sendsms_agents' WHERE staff_tid = '$tide'") or die ("Error: " . mysqli_error($link));

if(!$update)
{
echo "<script>alert('Record not updated.....Please try again later!'); </script>";
}
else{
echo "<script>alert('Permission Updated Successfully!!'); </script>";
echo "<script>window.location='agentperm.php?id=".$_SESSION['tid']."&&mid=".base64_encode("413")."'; </script>";
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