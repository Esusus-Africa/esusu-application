<?php
include("../connect.php");
function fetch_data()
{
	$output = '';
	include("../connect.php");
	//$link = mysqli_connect('localhost','root','root','lms-2.0') or die('Unable to Connect to Database'); 
	$dfrom = $_GET['dfrom'];
	$dto = $_GET['dto'];

	//GET SYSTEM CONFIGURATION SETTINGS
	$select1 = mysqli_query($link, "SELECT * FROM systemset") or die (mysqli_error($link));
	$row1 = mysqli_fetch_array($select1);
	$currency = $row1['currency'];
	$subaccount_charges = $row1['subaccount_charges'];
	$authorization_charges = $row1['auth_charges'];

	////////////////////////////ALL INCOME//////////////////////////////////////

	$sql = "SELECT SUM(amount_paid) FROM saas_subscription_trans WHERE duration_from BETWEEN '$dfrom' AND '$dto' AND status = 'Paid' ORDER BY id ASC";
	$result = mysqli_query($link, $sql);
	while($row = mysqli_fetch_array($result))
	{
		//TOTAL SAASSUBSCRIPTION TRANSACTION AMOUNT
	$saassubscription = $row['SUM(amount_paid)'];

	//PROCESS TO DETECT TOTAL SUBACCOUNT CHARGES
	$select1 = mysqli_query($link, "SELECT SUM(amount) FROM all_savingssub_transaction WHERE date_time BETWEEN '$dfrom' AND '$dto' AND status = 'success' ORDER BY id") or die (mysqli_error($link));
	while($fetch1 = mysqli_fetch_array($select1))
	{
		//TOTAL SAVINGS INCOME
		$sum1 = $fetch1['SUM(amount)'];

		$select2 = mysqli_query($link, "SELECT SUM(amount_to_pay) FROM payments WHERE pay_date BETWEEN '$dfrom' AND '$dto' AND remarks = 'paid' ORDER BY id") or die (mysqli_error($link));
		while($fetch2 = mysqli_fetch_array($select2))
		{
			//TOTAL LOAN REPAYMENT
			$sum2 = $fetch2['SUM(amount_to_pay)'];

			//CALCULATING TOTAL SUBACCOUNT CHARGES
			$total_sum = $sum1 + $sum2;
			$total_subacctcharge = ($subaccount_charges/100) * $total_sum;

			$select3 = mysqli_query($link, "SELECT SUM(amount) FROM transaction WHERE date_time BETWEEN '$dfrom' AND '$dto' AND t_type = 'Withdraw-Charges'");
			while($fetch3 = mysqli_fetch_array($select3))
			{
				//TOTAL WITHDRAWAL CHARGES
				$sum3 = $fetch3['SUM(amount)'];

				$select11 = mysqli_query($link, "SELECT SUM(icm_amt) FROM income WHERE icm_date BETWEEN '$dfrom' AND '$dto'");
				while($fetch11 = mysqli_fetch_array($select11))
				{
					//OTHER INCOME IN TOTAL
					$sum11 = $fetch11['SUM(icm_amt)'];

					$select4 = mysqli_query($link, "SELECT SUM(amount) FROM transaction WHERE date_time BETWEEN '$dfrom' AND '$dto' AND t_type = 'Deposit'");
					while($fetch4 = mysqli_fetch_array($select4))
					{
						//TOTAL DEPOSITED AMOUNT
						$sum4 = $fetch4['SUM(amount)'];

						$select10 = mysqli_query($link, "SELECT * FROM authorized_card WHERE date_time BETWEEN '$dfrom' AND '$dto'");
						$num = mysqli_num_rows($select10);

						$select11 = mysqli_query($link, "SELECT * FROM coop_authorized_card WHERE date_time BETWEEN '$dfrom' AND '$dto'");
						$num1 = mysqli_num_rows($select11);

						//TOTAL AUTHORIZATION CHARGES.
						$total_num = $num1 + $num2;
						$totalauth_charges = $total_num * $authorization_charges;

						///////////////////////////////ALL EXPENSES/////////////////////////////////
						$select5 = mysqli_query($link, "SELECT SUM(amount) FROM transaction WHERE date_time BETWEEN '$dfrom' AND '$dto' AND t_type = 'Withdraw'");
						while($fetch5 = mysqli_fetch_array($select5))
						{
							//TOTAL AMOUNT WITHDRAWN
							$sum5 = $fetch5['SUM(amount)'];

							$select6 = mysqli_query($link, "SELECT SUM(paid_amount) FROM payroll WHERE pay_date BETWEEN '$dfrom' AND '$dto'");
							while($fetch6 = mysqli_fetch_array($select6))
							{
								//TOTAL EMPLOYEE SALARY PAID
								$sum6 = $fetch6['SUM(paid_amount)'];

								$select7 = mysqli_query($link, "SELECT SUM(amount) FROM referral_incomehistory WHERE tdate BETWEEN '$dfrom' AND '$dto' AND status = 'Paid'");
								while($fetch7 = mysqli_fetch_array($select7))
								{
									//TOTAL REFERRAL BONUS PAID
									$sum7 = $fetch7['SUM(amount)'];

									$select8 = mysqli_query($link, "SELECT SUM(amount) FROM loan_info WHERE date_release BETWEEN '$dfrom' AND '$dto' AND upstatus = 'Approved'");
									while($fetch8 = mysqli_fetch_array($select8))
									{
										//TOTAL LOAN RELEASED
										$sum8 = $fetch8['SUM(amount)'];

										$select9 = mysqli_query($link, "SELECT SUM(eamt) FROM expenses WHERE edate BETWEEN '$dfrom' AND '$dto'");
										while($fetch9 = mysqli_fetch_array($select9))
										{
											//TOTAL AMOUNT OF ALL OTHER EXPENSES ENTERED MANUALLY TO THE SYSTEM
											$sum9 = $fetch9['SUM(eamt)'];

											//CALCULATE TOTAL INCOME
											$total_income = $saassubscription+$total_subacctcharge+$sum3+$totalauth_charges+$sum4+$sum2+$sum1+$sum11;

											$total_expenses = $sum5+$sum6+$sum7+$sum8+$sum9;

											$total_prAndloss = $total_income - $total_expenses;

		$output .= '
				<tr>
					<td height="20px;"><div align="left">Total Software Subscription</div></td>
					<td><div align="right">'.number_format($saassubscription,2,'.',',').'</div></td>
					<td><div align="left">Total Amount Withdrawn</div></td>
					<td><div align="right">'.number_format($sum5,2,'.',',').'</div></td>
			  	</tr>
			  	<tr>
					<td height="20px;"><div align="left">Total Subaccount Charges</div></td>
					<td><div align="right">'.number_format($total_subacctcharge,2,'.',',').'</div></td>
					<td><div align="left">Total Employee Salary Paid</div></td>
					<td><div align="right">'.number_format($sum6,2,'.',',').'</div></td>
			  	</tr>
			  	<tr>
					<td height="20px;"><div align="left">Total Withdrawal Charges</div></td>
					<td><div align="right">'.number_format($sum3,2,'.',',').'</div></td>
					<td><div align="left">Total Referral Bonus Payout</div></td>
					<td><div align="right">'.number_format($sum7,2,'.',',').'</div></td>
			  	</tr>
			  	<tr>
					<td height="20px;"><div align="left">Amount Charged per Account / ATM Card Validation [Authorization Charges]</div></td>
					<td><div align="right">'.number_format($totalauth_charges,2,'.',',').'</div></td>
					<td><div align="left">Total Amount of Loan Released</div></td>
					<td><div align="right">'.number_format($sum8,2,'.',',').'</div></td>
			  	</tr>
			  	<tr>
					<td height="20px;"><div align="left">Total Deposit</div></td>
					<td><div align="right">'.number_format($sum4,2,'.',',').'</div></td>
					<td><div align="left">Others</div></td>
					<td><div align="right">'.number_format($sum9,2,'.',',').'</div></td>
			  	</tr>
			  	<tr>
					<td height="20px;"><div align="left">Total Loan Repayment Received</div></td>
					<td><div align="right">'.number_format($sum2,2,'.',',').'</div></td>
					<td><div align="left"><b style="font-size: 14px;">TOTAL EXPENSES</b></div></td>
					<td><div align="right"><b style="font-size: 14px;">'.number_format($total_expenses,2,'.',',').'</b></div></td>
			  	</tr>
			  	<tr>
					<td height="20px;"><div align="left">All Savings Income</div></td>
					<td><div align="right">'.number_format($sum1,2,'.',',').'</div></td>';
		if($total_prAndloss <= 0)
		{
		$output .= '<td><div align="left"><b style="font-size: 12px;">TOTAL LOSS</b></div></td>
					<td><div align="right"><b style="font-size: 12px;">'.$total_prAndloss.'</b></div></td>';
		}
		else{
		$output .= '<td><div align="left"><b style="font-size: 12px;">TOTAL LOSS</b></div></td>
					<td><div align="right"><b style="font-size: 12px;">----------</b></div></td>';
		}

		$output .= '</tr>';
		$output .= '<tr>
					<td height="20px;"><div align="left">Others</div></td>
					<td><div align="right">'.number_format($sum11,2,'.',',').'</div></td>
					<td><div align="left"></div></td>
					<td><div align="right"></div></td>
				  	</tr>
				  	<tr>
						<td height="20px;"><div align="left"><b style="font-size: 14px;">TOTAL INCOME</b></div></td>
						<td><div align="right"><b style="font-size: 14px;">'.number_format($total_income,2,'.',',').'</b></div></td>
						<td><div align="left"></div></td>
						<td><div align="right"></div></td>
				  	</tr>
					';
		if($total_prAndloss <= 0)
		{
		
		$output .= '<tr>
					<td height="20px;"><div align="left"><b style="font-size: 12px;">TOTAL PROFIT</b></div></td>
					<td><div align="right"><b style="font-size: 12px;">----------</b></div></td>
					<td><div align="left"></div></td>
					<td><div align="right"></div></td>
			  	</tr>';

		}else{

		$output .= '<tr>
					<td height="20px;"><div align="left"><b style="font-size: 12px;">TOTAL PROFIT</b></div></td>
					<td><div align="right"><b style="font-size: 12px;">'.number_format($total_prAndloss,2,'.',',').'</b></div></td>
					<td><div align="left"></div></td>
					<td><div align="right"></div></td>
			  	</tr>';

		}
	}
}
}
}
}
}
}
}
}
}
}
	return $output;
}
	require_once('tcpdf_include.php');
	// create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetTitle('Financial Report');

// set default header data
//$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' 001', PDF_HEADER_STRING, array(0,64,255), array(0,64,128));
$pdf->setFooterData(array(0,64,0), array(0,64,128));

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
	require_once(dirname(__FILE__).'/lang/eng.php');
	$pdf->setLanguageArray($l);
}

// ---------------------------------------------------------

// set default font subsetting mode
$pdf->setFontSubsetting(true);

// Set font
// dejavusans is a UTF-8 Unicode font, if you only need to
// print standard ASCII chars, you can use core fonts like
// helvetica or times to reduce file size.
$pdf->SetFont('dejavusans', '', 8, '', true);

// Add a page
// This method has several options, check the source code documentation for more information.
$pdf->AddPage();

// set text shadow effect
$pdf->setTextShadow(array('enabled'=>true, 'depth_w'=>0.2, 'depth_h'=>0.2, 'color'=>array(196,196,196), 'opacity'=>1, 'blend_mode'=>'Normal'));

$sys = mysqli_query($link, "SELECT * FROM systemset");
$row_sys = mysqli_fetch_array($sys);

	$content = '
		<div align="center"><b style="color:#0073b7; font-size:20;">'.$row_sys['name'].'</b></div>
		<div style="color:orange; font-size:15px;" align="center"><b>(Financial Report)</b></div>
		<div style="color: #0073b7; font-size:15px;" align="center">Report from ['.$_GET['dfrom'].' to '.$_GET['dto'].']</div>
          <small class="pull-right"><div style="color:black">'.date ('l, F, d, Y', strtotime(date('y:m:d'))).'<br><br></div>
		</small>
	<table class="table table-bordered table-striped" width="100%" border="1">
			  <tr>
				<td height="20px;"><div align="left"> Dr</div></td>
				<td><div align="right"></div></td>
				<td><div align="left"></div></td>
				<td><div align="right">Cr</div></td>
			  </tr>
              <tr bgcolor="orange">
				<td height="20px;"><div align="left"><strong>&nbsp;Income</strong></div></td>
				<td><div align="right"><strong>&nbsp;'.$row_sys['currency'].'</strong></div></td>
				<td><div align="left"><strong>&nbsp;Expenses</strong></div></td>
				<td><div align="right"><strong>&nbsp;'.$row_sys['currency'].'</strong></div></td>
			  </tr>';
	$content .= fetch_data();
	$content .= '</tbody></table>';
	 //$obj_pdf->writeHTML($content);
	 // Print text using writeHTMLCell()
	$pdf->writeHTMLCell(0, 0, '', '', $content, 0, 1, 0, true, '', true);
	$pdf->Output('file.pdf','I');

// --------------------------------------------------------
?>