<div class="row">
    
		    <section class="content">  
	        <div class="box box-danger">
            <div class="box-body">
              <div class="table-responsive">
             <div class="box-body">
<form method="post">
			 <a href="agentperm.php?id=<?php echo $_SESSION['tid']; ?>&&mid=<?php echo base64_encode("413"); ?>"><button type="button" class="btn btn-flat bg-orange"><i class="fa fa-mail-reply-all"></i>&nbsp;Back</button></a> 
	<hr>	

<form class="form-horizontal" method="post" enctype="multipart/form-data">

<div class="box-body">

			<div class="form-group">
				<label for="" class="col-sm-2 control-label" style="color:blue;">User Name:</label>
                <div class="col-sm-10">
				<?php
					$id = $_GET['id'];
					$search_user = mysqli_query($link, "SELECT * FROM agent_data WHERE agentid = '$id' AND status = 'Approved' OR agentid = '$id' OR status = 'Updated'") or die ("Error: " . mysqli_error($link));
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
 			   	<td><div align="center">Branch Tab:<p><?php echo ($have['branch_tab'] == "0") ? '<i style="color: orange;" class="fa fa-times"></i>' : '<i style="color: blue;" class="fa fa-check"></i>'; ?><p></div></td>
 			   	<td><div align="center">Add:<p><?php echo ($have['add_branch'] == "0") ? '<i style="color: orange;" class="fa fa-times"></i>' : '<i style="color: blue;" class="fa fa-check"></i>'; ?></p></div></td>
 			   	<td><div align="center">List/View:<p> <?php echo ($have['list_branch'] == "0") ? '<i style="color: orange;" class="fa fa-times"></i>' : '<i style="color: blue;" class="fa fa-check"></i>'; ?></p></div></td>
 			   	<td><div align="center">Edit/Update:<p> <?php echo ($have['edit_branch'] == "0") ? '<i style="color: orange;" class="fa fa-times"></i>' : '<i style="color: blue;" class="fa fa-check"></i>'; ?></p></div></td>
 				<td><div align="center">Delete:<p> <?php echo ($have['del_branch'] == "0") ? '<i style="color: orange;" class="fa fa-times"></i>' : '<i style="color: blue;" class="fa fa-check"></i>'; ?></p></div></td>
   			  </tr>
			  
 			</table>
 			<hr>

      <table width="100%" border="1">
        <tr bgcolor="orange">
        <td width="25%"><div align="left"><strong>&nbsp;COOPERATIVE SETTINGS</strong></div></td>
        </tr>
      
          <tr width="100%">
          <td><div align="center">Cooperative Tab:<p> <?php echo ($have['cooperative_tab'] == "0") ? '<i style="color: orange;" class="fa fa-times"></i>' : '<i style="color: blue;" class="fa fa-check"></i>'; ?><p></div></td>
          <td><div align="center">Add Cooperative:<p> <?php echo ($have['add_cooperative'] == "0") ? '<i style="color: orange;" class="fa fa-times"></i>' : '<i style="color: blue;" class="fa fa-check"></i>'; ?></p></div></td>
          <td><div align="center">Add Member:<p> <?php echo ($have['add_coop_member'] == "0") ? '<i style="color: orange;" class="fa fa-times"></i>' : '<i style="color: blue;" class="fa fa-check"></i>'; ?></p></div></td>
          <td><div align="center">View Cooperatives:<p> <?php echo ($have['view_cooperative'] == "0") ? '<i style="color: orange;" class="fa fa-times"></i>' : '<i style="color: blue;" class="fa fa-check"></i>'; ?></p></div></td>
        <td><div align="center">View Members:<p> <?php echo ($have['view_members'] == "0") ? '<i style="color: orange;" class="fa fa-times"></i>' : '<i style="color: blue;" class="fa fa-check"></i>'; ?></p></div></td>
          </tr>

          <tr width="100%">
          <td><div align="center">Update Cooperative:<p> <?php echo ($have['update_cooperative'] == "0") ? '<i style="color: orange;" class="fa fa-times"></i>' : '<i style="color: blue;" class="fa fa-check"></i>'; ?></p></div></td>
          <td><div align="center">Update Members:<p> <?php echo ($have['update_coop_members'] == "0") ? '<i style="color: orange;" class="fa fa-times"></i>' : '<i style="color: blue;" class="fa fa-check"></i>'; ?><p></div></td>
          <td><div align="center">Send SMS:<p> <?php echo ($have['sendsms_cooperative'] == "0") ? '<i style="color: orange;" class="fa fa-times"></i>' : '<i style="color: blue;" class="fa fa-check"></i>'; ?></p></div></td>
          </tr>
        
      </table>
      <hr>

      <table width="100%" border="1">
        <tr bgcolor="orange">
        <td width="25%"><div align="left"><strong>&nbsp;INSTITUTION SETTINGS</strong></div></td>
        </tr>
      
          <tr width="100%">
          <td><div align="center">Institution Tab:<p> <?php echo ($have['institution_tab'] == "0") ? '<i style="color: orange;" class="fa fa-times"></i>' : '<i style="color: blue;" class="fa fa-check"></i>'; ?></p></div></td>
          <td><div align="center">Add Institution:<p> <?php echo ($have['add_institution'] == "0") ? '<i style="color: orange;" class="fa fa-times"></i>' : '<i style="color: blue;" class="fa fa-check"></i>'; ?></p></div></td>
          <td><div align="center">View Institution:<p> <?php echo ($have['view_institution'] == "0") ? '<i style="color: orange;" class="fa fa-times"></i>' : '<i style="color: blue;" class="fa fa-check"></i>'; ?></p></div></td>
          <td><div align="center">View Members:<p> <?php echo ($have['view_inst_members'] == "0") ? '<i style="color: orange;" class="fa fa-times"></i>' : '<i style="color: blue;" class="fa fa-check"></i>'; ?></p></div></td>
        <td><div align="center">Update Members:<p> <?php echo ($have['update_inst_members'] == "0") ? '<i style="color: orange;" class="fa fa-times"></i>' : '<i style="color: blue;" class="fa fa-check"></i>'; ?></p></div></td>
          </tr>

          <tr width="100%">
          <td><div align="center">Update Institution:<p> <?php echo ($have['update_institution'] == "0") ? '<i style="color: orange;" class="fa fa-times"></i>' : '<i style="color: blue;" class="fa fa-check"></i>'; ?></p></div></td>
          <td><div align="center">Send SMS:<p> <?php echo ($have['sendsms_institution'] == "0") ? '<i style="color: orange;" class="fa fa-times"></i>' : '<i style="color: blue;" class="fa fa-check"></i>'; ?></p></div></td>
          </tr>
        
      </table>
      <hr>

      <table width="100%" border="1">
        <tr bgcolor="orange">
        <td width="25%"><div align="left"><strong>&nbsp;AGENTS SETTINGS</strong></div></td>
        </tr>
      
          <tr width="100%">
          <td><div align="center">Agent Tab:<p> <?php echo ($have['agent_tab'] == "0") ? '<i style="color: orange;" class="fa fa-times"></i>' : '<i style="color: blue;" class="fa fa-check"></i>'; ?></p></div></td>
          <td><div align="center">Add:<p> <?php echo ($have['add_agent'] == "0") ? '<i style="color: orange;" class="fa fa-times"></i>' : '<i style="color: blue;" class="fa fa-check"></i>'; ?></p></div></td>
          <td><div align="center">List/View:<p> <?php echo ($have['view_agent'] == "0") ? '<i style="color: orange;" class="fa fa-times"></i>' : '<i style="color: blue;" class="fa fa-check"></i>'; ?></p></div></td>
          <td><div align="center">Edit/Update:<p> <?php echo ($have['update_agent'] == "0") ? '<i style="color: orange;" class="fa fa-times"></i>' : '<i style="color: blue;" class="fa fa-check"></i>'; ?></p></div></td>
        <td><div align="center">Send SMS to Agents:<p> <?php echo ($have['sendsms_agents'] == "0") ? '<i style="color: orange;" class="fa fa-times"></i>' : '<i style="color: blue;" class="fa fa-check"></i>'; ?></p></div></td>
          </tr>
        
      </table>
      <hr>

      <table width="100%" border="1">
        <tr bgcolor="orange">
        <td width="25%"><div align="left"><strong>&nbsp;SUBSCRIPTION SETTINGS</strong></div></td>
        </tr>
      
          <tr width="100%">
          <td><div align="center">Subscription Tab:<p> <?php echo ($have['subscription_tab'] == "0") ? '<i style="color: orange;" class="fa fa-times"></i>' : '<i style="color: blue;" class="fa fa-check"></i>'; ?></p></div></td>
          <td><div align="center">Setup Saas Plan:<p> <?php echo ($have['setup_saas_plan'] == "0") ? '<i style="color: orange;" class="fa fa-times"></i>' : '<i style="color: blue;" class="fa fa-check"></i>'; ?></p></div></td>
          <td><div align="center">View Subscription Plan:<p> <?php echo ($have['view_subscription_plan'] == "0") ? '<i style="color: orange;" class="fa fa-times"></i>' : '<i style="color: blue;" class="fa fa-check"></i>'; ?></p></div></td>
          <td><div align="center">Update Saas Plan:<p> <?php echo ($have['update_saas_plan'] == "0") ? '<i style="color: orange;" class="fa fa-times"></i>' : '<i style="color: blue;" class="fa fa-check"></i>'; ?></p></div></td>
        <td><div align="center">View Pending Subscription:<p> <?php echo ($have['view_pending_sub'] == "0") ? '<i style="color: orange;" class="fa fa-times"></i>' : '<i style="color: blue;" class="fa fa-check"></i>'; ?></p></div></td>
          </tr>

          <tr width="100%">
          <td><div align="center">View Paid Subscription:<p> <?php echo ($have['view_paid_sub'] == "0") ? '<i style="color: orange;" class="fa fa-times"></i>' : '<i style="color: blue;" class="fa fa-check"></i>'; ?></p></div></td>
          <td><div align="center">View Deactivated Subscription:<p> <?php echo ($have['view_deactivated_sub'] == "0") ? '<i style="color: orange;" class="fa fa-times"></i>' : '<i style="color: blue;" class="fa fa-check"></i>'; ?></p></div></td>
          <td><div align="center">View Expiorange Subscription:<p> <?php echo ($have['view_expired_sub'] == "0") ? '<i style="color: orange;" class="fa fa-times"></i>' : '<i style="color: blue;" class="fa fa-check"></i>'; ?></p></div></td>
          </tr>
        
      </table>
      <hr>
			
 			<table width="100%" border="1">
 			  <tr bgcolor="orange">
 				<td width="25%"><div align="left"><strong>&nbsp;CUSTOMER MGT. SETTINGS</strong></div></td>
 			  </tr>
			
   			  <tr>
 			   	<td><div align="center">Customer Tab:<p> <?php echo ($have['customer_tab'] == "0") ? '<i style="color: orange;" class="fa fa-times"></i>' : '<i style="color: blue;" class="fa fa-check"></i>'; ?></p></div></td>
 			   	<td><div align="center">Add:<p> <?php echo ($have['new_customer'] == "0") ? '<i style="color: orange;" class="fa fa-times"></i>' : '<i style="color: blue;" class="fa fa-check"></i>'; ?></p></div></td>
 			   	<td><div align="center">List/View All:<p> <?php echo ($have['all_customer'] == "0") ? '<i style="color: orange;" class="fa fa-times"></i>' : '<i style="color: blue;" class="fa fa-check"></i>'; ?></p></div></td>
 			   	<td><div align="center">View Borrowers:<p> <?php echo ($have['borrower_list'] == "0") ? '<i style="color: orange;" class="fa fa-times"></i>' : '<i style="color: blue;" class="fa fa-check"></i>'; ?></p></div></td>
 				<td><div align="center">Delete:<p> <?php echo ($have['del_customer'] == "0") ? '<i style="color: orange;" class="fa fa-times"></i>' : '<i style="color: blue;" class="fa fa-check"></i>'; ?> </p></div></td>
   			  </tr>
 			  <tr>
   			   	<td><div align="center">View Account Info:<p> <?php echo ($have['view_account_info'] == "0") ? '<i style="color: orange;" class="fa fa-times"></i>' : '<i style="color: blue;" class="fa fa-check"></i>'; ?> </p></div></td>
   			   	<td><div align="center">Edit/Update:<p> <?php echo ($have['update_info'] == "0") ? '<i style="color: orange;" class="fa fa-times"></i>' : '<i style="color: blue;" class="fa fa-check"></i>'; ?></p></div></td>
   			   	<td><div align="center">Add borrower list:<p> <?php echo ($have['add_to_borrower'] == "0") ? '<i style="color: orange;" class="fa fa-times"></i>' : '<i style="color: blue;" class="fa fa-check"></i>'; ?> </p></div></td>
   			   	<td><div align="center">Send SMS:<p> <?php echo ($have['send_sms'] == "0") ? '<i style="color: orange;" class="fa fa-times"></i>' : '<i style="color: blue;" class="fa fa-check"></i>'; ?> </p></div></td>
   				<td><div align="center">Send Email:<p> <?php echo ($have['send_email'] == "0") ? '<i style="color: orange;" class="fa fa-times"></i>' : '<i style="color: blue;" class="fa fa-check"></i>'; ?> </p></div></td>
 			  </tr>
				  
 			</table>
 			<hr>
			
 			<table width="100%" border="1">
        <tr bgcolor="orange">
        <td width="25%"><div align="left"><strong>&nbsp;WALLET MGT. SETTINGS</strong></div></td>
        </tr>
      
          <tr>
          <td><div align="center">Wallet Tab:<p> <?php echo ($have['mywallet_tab'] == "0") ? '<i style="color: orange;" class="fa fa-times"></i>' : '<i style="color: blue;" class="fa fa-check"></i>'; ?></p></div></td>
          <td><div align="center">Transfer Fund:<p> <?php echo ($have['transfer_money'] == "0") ? '<i style="color: orange;" class="fa fa-times"></i>' : '<i style="color: blue;" class="fa fa-check"></i>'; ?></p></div></td>
          <td><div align="center">Add Transfer Recipients:<p> <?php echo ($have['add_transfer_recipients'] == "0") ? '<i style="color: orange;" class="fa fa-times"></i>' : '<i style="color: blue;" class="fa fa-check"></i>'; ?></p></div></td>
          <td><div align="center">Add Fund to Balance:<p> <?php echo ($have['add_fund_tobalance'] == "0") ? '<i style="color: orange;" class="fa fa-times"></i>' : '<i style="color: blue;" class="fa fa-check"></i>'; ?></p></div></td>
        <td><div align="center">View Transfer Recipients:<p> <?php echo ($have['view_transfer_recipients'] == "0") ? '<i style="color: orange;" class="fa fa-times"></i>' : '<i style="color: blue;" class="fa fa-check"></i>'; ?></p></div></td>
          </tr>

          <tr>
          <td><div align="center">Buy Airtime:<p> <?php echo ($have['buy_airtime'] == "0") ? '<i style="color: orange;" class="fa fa-times"></i>' : '<i style="color: blue;" class="fa fa-check"></i>'; ?></p></div></td>
         </tr>
          
      </table>
      <hr>

      <table width="100%" border="1">
        <tr bgcolor="orange">
        <td width="25%"><div align="left"><strong>&nbsp;REFERRAL MANAGER</strong></div></td>
        </tr>
      
          <tr>
          <td><div align="center">Referral Tab:<p> <?php echo ($have['referral_tab'] == "0") ? '<i style="color: orange;" class="fa fa-times"></i>' : '<i style="color: blue;" class="fa fa-check"></i>'; ?></p></div></td>
          <td><div align="center">Set Referral Plan:<p> <?php echo ($have['set_referral_plan'] == "0") ? '<i style="color: orange;" class="fa fa-times"></i>' : '<i style="color: blue;" class="fa fa-check"></i>'; ?></p></div></td>
          <td><div align="center">View Compensation Plan:<p> <?php echo ($have['view_compensation_plan'] == "0") ? '<i style="color: orange;" class="fa fa-times"></i>' : '<i style="color: blue;" class="fa fa-check"></i>'; ?></p></div></td>
          <td><div align="center">Update Compensation Plan:<p> <?php echo ($have['update_compensation_plan'] == "0") ? '<i style="color: orange;" class="fa fa-times"></i>' : '<i style="color: blue;" class="fa fa-check"></i>'; ?></p></div></td>
        <td><div align="center">Confirm Bonus:<p> <?php echo ($have['confirm_bonus'] == "0") ? '<i style="color: orange;" class="fa fa-times"></i>' : '<i style="color: blue;" class="fa fa-check"></i>'; ?></p></div></td>
          </tr>

          <tr>
          <td><div align="center">Bonus Transaction:<p> <?php echo ($have['bonus_transaction'] == "0") ? '<i style="color: orange;" class="fa fa-times"></i>' : '<i style="color: blue;" class="fa fa-check"></i>'; ?></p></div></td>
         </tr>
          
      </table>
      <hr>
			
 			<table width="100%" border="1">
 			  <tr bgcolor="orange">
 				<td width="25%"><div align="left"><strong>&nbsp;LOAN MGT. SETTINGS</strong></div></td>
 			  </tr>
			
   			  <tr>
 			   	<td><div align="center">Loan Tab:<p> <?php echo ($have['loan_tab'] == "0") ? '<i style="color: orange;" class="fa fa-times"></i>' : '<i style="color: blue;" class="fa fa-check"></i>'; ?> </p></div></td>
 			   	<td><div align="center">Add:<p> <?php echo ($have['new_loan'] == "0") ? '<i style="color: orange;" class="fa fa-times"></i>' : '<i style="color: blue;" class="fa fa-check"></i>'; ?> </p></div></td>
 			   	<td><div align="center">List/View All:<p> <?php echo ($have['view_loan'] == "0") ? '<i style="color: orange;" class="fa fa-times"></i>' : '<i style="color: blue;" class="fa fa-check"></i>'; ?> </p></div></td>
 			   	<td><div align="center">Due Loan:<p> <?php echo ($have['due_loan'] == "0") ? '<i style="color: orange;" class="fa fa-times"></i>' : '<i style="color: blue;" class="fa fa-check"></i>'; ?> </p></div></td>
 				<td><div align="center">View Past Maturity Date Loan:<p> <?php echo ($have['past_maturity_date'] == "0") ? '<i style="color: orange;" class="fa fa-times"></i>' : '<i style="color: blue;" class="fa fa-check"></i>'; ?> </p></div></td>
   			  </tr>
 			  <tr>
   			   	<td><div align="center">View Principal Outstanding Loan:<p> <?php echo ($have['principal_outstanding'] == "0") ? '<i style="color: orange;" class="fa fa-times"></i>' : '<i style="color: blue;" class="fa fa-check"></i>'; ?> </p></div></td>
   			   	<td><div align="center">Approve Loan:<p> <?php echo ($have['approve_loan'] == "0") ? '<i style="color: orange;" class="fa fa-times"></i>' : '<i style="color: blue;" class="fa fa-check"></i>'; ?> </p></div></td>
   			   	<td><div align="center">View Loan Details:<p> <?php echo ($have['loan_details'] == "0") ? '<i style="color: orange;" class="fa fa-times"></i>' : '<i style="color: blue;" class="fa fa-check"></i>'; ?> </p></div></td>
   			   	<td><div align="center">Delete Loan:<p> <?php echo ($have['del_loan'] == "0") ? '<i style="color: orange;" class="fa fa-times"></i>' : '<i style="color: blue;" class="fa fa-check"></i>'; ?> </p></div></td>
   				<td><div align="center">Print:<p> <?php echo ($have['print_loan'] == "0") ? '<i style="color: orange;" class="fa fa-times"></i>' : '<i style="color: blue;" class="fa fa-check"></i>'; ?> </p></div></td>
 			  </tr>
 			  <tr>
 				<td><div align="center">Export Excel:<p> <?php echo ($have['export_excel_loanlist'] == "0") ? '<i style="color: orange;" class="fa fa-times"></i>' : '<i style="color: blue;" class="fa fa-check"></i>'; ?> </p></div></td>
 			  </tr>
				  
 			</table>
 			<hr>
			
 			<table width="100%" border="1">
 			  <tr bgcolor="orange">
 				<td width="25%"><div align="left"><strong>&nbsp;LOAN PAYMENT SETTINGS</strong></div></td>
 			  </tr>
			
   			  <tr>
 			   	<td><div align="center">Payment Tab:<p> <?php echo ($have['payment_tab'] == "0") ? '<i style="color: orange;" class="fa fa-times"></i>' : '<i style="color: blue;" class="fa fa-check"></i>'; ?> </p></div></td>
 			   	<td><div align="center">Add:<p> <?php echo ($have['new_payment'] == "0") ? '<i style="color: orange;" class="fa fa-times"></i>' : '<i style="color: blue;" class="fa fa-check"></i>'; ?> </p></div></td>
 			   	<td><div align="center">List/View:<p> <?php echo ($have['list_payment'] == "0") ? '<i style="color: orange;" class="fa fa-times"></i>' : '<i style="color: blue;" class="fa fa-check"></i>'; ?> </p></div></td>
 			   	<td><div align="center">Delete:<p> <?php echo ($have['del_payment'] == "0") ? '<i style="color: orange;" class="fa fa-times"></i>' : '<i style="color: blue;" class="fa fa-check"></i>'; ?> </p></div></td>
 				<td><div align="center">Print:<p> <?php echo ($have['print_payment'] == "0") ? '<i style="color: orange;" class="fa fa-times"></i>' : '<i style="color: blue;" class="fa fa-check"></i>'; ?> </p></div></td>
   			  </tr>
 			  <tr>
 				<td><div align="center">Export Excel:<p> <?php echo ($have['export_excel_lpayment'] == "0") ? '<i style="color: orange;" class="fa fa-times"></i>' : '<i style="color: blue;" class="fa fa-check"></i>'; ?> </p></div></td>
 			  </tr>
				  
 			</table>
 			<hr>
			
 			<table width="100%" border="1">
 			  <tr bgcolor="orange">
 				<td width="25%"><div align="left"><strong>&nbsp;EMPLOYEE MGT. SETTINGS</strong></div></td>
 			  </tr>
			
   			  <tr>
 			   	<td><div align="center">Employee Tab:<p> <?php echo ($have['emp_tab'] == "0") ? '<i style="color: orange;" class="fa fa-times"></i>' : '<i style="color: blue;" class="fa fa-check"></i>'; ?> </p></div></td>
 			   	<td><div align="center">Add:<p> <?php echo ($have['new_emp'] == "0") ? '<i style="color: orange;" class="fa fa-times"></i>' : '<i style="color: blue;" class="fa fa-check"></i>'; ?> </p></div></td>
 			   	<td><div align="center">List/View:<p> <?php echo ($have['list_emp'] == "0") ? '<i style="color: orange;" class="fa fa-times"></i>' : '<i style="color: blue;" class="fa fa-check"></i>'; ?> </p></div></td>
 			   	<td><div align="center">Edit/Update:<p> <?php echo ($have['edit_emp'] == "0") ? '<i style="color: orange;" class="fa fa-times"></i>' : '<i style="color: blue;" class="fa fa-check"></i>'; ?> </p></div></td>
 				<td><div align="center">Send SMS:<p> <?php echo ($have['send_empsms'] == "0") ? '<i style="color: orange;" class="fa fa-times"></i>' : '<i style="color: blue;" class="fa fa-check"></i>'; ?> </p></div></td>
   			  </tr>
 			  <tr>
   			   	<td><div align="center">Delete:<p> <?php echo ($have['del_emp'] == "0") ? '<i style="color: orange;" class="fa fa-times"></i>' : '<i style="color: blue;" class="fa fa-check"></i>'; ?> </p></div></td>
   			   	<td><div align="center">Print:<p> <?php echo ($have['print_emp'] == "0") ? '<i style="color: orange;" class="fa fa-times"></i>' : '<i style="color: blue;" class="fa fa-check"></i>'; ?> </p></div></td>
   			   	<td><div align="center">Export Excel:<p> <?php echo ($have['export_excel_emp'] == "0") ? '<i style="color: orange;" class="fa fa-times"></i>' : '<i style="color: blue;" class="fa fa-check"></i>'; ?>  </p></div></td>
 			  </tr>
			 
 			</table>
 			<hr>
			
 			<table width="100%" border="1">
 			  <tr bgcolor="orange">
 				<td width="25%"><div align="left"><strong>&nbsp;EXPENSES SETTINGS</strong></div></td>
 			  </tr>
			
   			  <tr width="100%">
 			   	<td><div align="center">Expense Tab:<p> <?php echo ($have['expense_tab'] == "0") ? '<i style="color: orange;" class="fa fa-times"></i>' : '<i style="color: blue;" class="fa fa-check"></i>'; ?> <p></div></td>
 			   	<td><div align="center">Add:<p> <?php echo ($have['add_expense'] == "0") ? '<i style="color: orange;" class="fa fa-times"></i>' : '<i style="color: blue;" class="fa fa-check"></i>'; ?> </p></div></td>
 			   	<td><div align="center">List/View:<p> <?php echo ($have['view_expense'] == "0") ? '<i style="color: orange;" class="fa fa-times"></i>' : '<i style="color: blue;" class="fa fa-check"></i>'; ?> </p></div></td>
 			   	<td><div align="center">Edit/Update:<p> <?php echo ($have['edit_expense'] == "0") ? '<i style="color: orange;" class="fa fa-times"></i>' : '<i style="color: blue;" class="fa fa-check"></i>'; ?> </p></div></td>
 				<td><div align="center">Delete:<p> <?php echo ($have['del_expense'] == "0") ? '<i style="color: orange;" class="fa fa-times"></i>' : '<i style="color: blue;" class="fa fa-check"></i>'; ?> </p></div></td>
   			  </tr>
			  
 			</table>
 			<hr>
			
 			<table width="100%" border="1">
 			  <tr bgcolor="orange">
 				<td width="25%"><div align="left"><strong>&nbsp;PAYROLL SETTINGS</strong></div></td>
 			  </tr>
			
   			  <tr width="100%">
 			   	<td><div align="center">Payroll Tab:<p> <?php echo ($have['payroll_tab'] == "0") ? '<i style="color: orange;" class="fa fa-times"></i>' : '<i style="color: blue;" class="fa fa-check"></i>'; ?> <p></div></td>
 			   	<td><div align="center">Add:<p> <?php echo ($have['add_payroll'] == "0") ? '<i style="color: orange;" class="fa fa-times"></i>' : '<i style="color: blue;" class="fa fa-check"></i>'; ?> </p></div></td>
 			   	<td><div align="center">List/View:<p> <?php echo ($have['view_payroll'] == "0") ? '<i style="color: orange;" class="fa fa-times"></i>' : '<i style="color: blue;" class="fa fa-check"></i>'; ?> </p></div></td>
 			   	<td><div align="center">Edit/Update:<p> <?php echo ($have['edit_payroll'] == "0") ? '<i style="color: orange;" class="fa fa-times"></i>' : '<i style="color: blue;" class="fa fa-check"></i>'; ?> </p></div></td>
 				<td><div align="center">Delete:<p> <?php echo ($have['del_payroll'] == "0") ? '<i style="color: orange;" class="fa fa-times"></i>' : '<i style="color: blue;" class="fa fa-check"></i>'; ?> </p></div></td>
   			  </tr>
			  
 			</table>
 			<hr>
			
 			<table width="100%" border="1">
 			  <tr bgcolor="orange">
 				<td width="25%"><div align="left"><strong>&nbsp;SAVINGS SYS. SETTINGS</strong></div></td>
 			  </tr>
			
   			  <tr>
 			   	<td><div align="center">Savings Tab:<p> <?php echo ($have['savings_tab'] == "0") ? '<i style="color: orange;" class="fa fa-times"></i>' : '<i style="color: blue;" class="fa fa-check"></i>'; ?> </p></div></td>
 			   	<td><div align="center">Deposit Fund:<p> <?php echo ($have['deposit_money'] == "0") ? '<i style="color: orange;" class="fa fa-times"></i>' : '<i style="color: blue;" class="fa fa-check"></i>'; ?> </p></div></td>
 			   	<td><div align="center">Withdraw Fund:<p> <?php echo ($have['withdraw_money'] == "0") ? '<i style="color: orange;" class="fa fa-times"></i>' : '<i style="color: blue;" class="fa fa-check"></i>'; ?> </p></div></td>
 			   	<td><div align="center">View All Transaction:<p> <?php echo ($have['all_transaction'] == "0") ? '<i style="color: orange;" class="fa fa-times"></i>' : '<i style="color: blue;" class="fa fa-check"></i>'; ?> </p></div></td>
 				<td><div align="center">Enable Verify Account Button:<p> <?php echo ($have['verify_account'] == "0") ? '<i style="color: orange;" class="fa fa-times"></i>' : '<i style="color: blue;" class="fa fa-check"></i>'; ?> </p></div></td>
   			  </tr>
 			  <tr>
   			   	<td><div align="center">Delete:<p> <?php echo ($have['del_transaction'] == "0") ? '<i style="color: orange;" class="fa fa-times"></i>' : '<i style="color: blue;" class="fa fa-check"></i>'; ?> </p></div></td>
   			   	<td><div align="center">Print:<p> <?php echo ($have['print_transaction'] == "0") ? '<i style="color: orange;" class="fa fa-times"></i>' : '<i style="color: blue;" class="fa fa-check"></i>'; ?> </p></div></td>
   			   	<td><div align="center">Export Excel:<p> <?php echo ($have['export_excel_transaction'] == "0") ? '<i style="color: orange;" class="fa fa-times"></i>' : '<i style="color: blue;" class="fa fa-check"></i>'; ?> </p></div></td>
 			  </tr>
			 
 			</table>
 			<hr>
			
 			<table width="100%" border="1">
 			  <tr bgcolor="orange">
 				<td width="25%"><div align="left"><strong>&nbsp;REPORT SYS. SETTINGS</strong></div></td>
 			  </tr>
			
   			  <tr>
 			   	<td><div align="center">Report Tab:<p> <?php echo ($have['report_tab'] == "0") ? '<i style="color: orange;" class="fa fa-times"></i>' : '<i style="color: blue;" class="fa fa-check"></i>'; ?> </p></div></td>
 			   	<td><div align="center">Borrower Report:<p> <?php echo ($have['borrower_report'] == "0") ? '<i style="color: orange;" class="fa fa-times"></i>' : '<i style="color: blue;" class="fa fa-check"></i>'; ?> </p></div></td>
 			   	<td><div align="center">Collection Report:<p> <?php echo ($have['collection_report'] == "0") ? '<i style="color: orange;" class="fa fa-times"></i>' : '<i style="color: blue;" class="fa fa-check"></i>'; ?> </p></div></td>
 			   	<td><div align="center">Collector Report:<p> <?php echo ($have['collector_report'] == "0") ? '<i style="color: orange;" class="fa fa-times"></i>' : '<i style="color: blue;" class="fa fa-check"></i>'; ?> </p></div></td>
 				<td><div align="center">Contribution Report:<p> <?php echo ($have['contribution_report'] == "0") ? '<i style="color: orange;" class="fa fa-times"></i>' : '<i style="color: blue;" class="fa fa-check"></i>'; ?></p></div></td>
   			  </tr>
				  
 			</table>
      <hr>

      <table width="100%" border="1">
        <tr bgcolor="orange">
        <td width="25%"><div align="left"><strong>&nbsp;CONFIGURATION SETTINGS</strong></div></td>
        </tr>
      
          <tr>
          <td><div align="center">Configuration Tab:<p> <?php echo ($have['config_tab'] == "0") ? '<i style="color: orange;" class="fa fa-times"></i>' : '<i style="color: blue;" class="fa fa-check"></i>'; ?> </p></div></td>
          <td><div align="center">Company Setup:<p> <?php echo ($have['company_setup'] == "0") ? '<i style="color: orange;" class="fa fa-times"></i>' : '<i style="color: blue;" class="fa fa-check"></i>'; ?> </p></div></td>
          <td><div align="center">SMS Gateway Settings:<p> <?php echo ($have['smsgateway_settings'] == "0") ? '<i style="color: orange;" class="fa fa-times"></i>' : '<i style="color: blue;" class="fa fa-check"></i>'; ?> </p></div></td>
          </tr>
          
      </table>

</div>				
</form>
                </div>

				</div>	
				</div>
			
</div>	
					
       
</div>