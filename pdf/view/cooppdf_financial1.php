<?php
include("../connect.php");
function fetch_data()
{
	$output = '';
	include("../connect.php");
	//$link = mysqli_connect('localhost','root','root','lms-2.0') or die('Unable to Connect to Database'); 
	$dfrom = $_GET['dfrom'];
	$dto = $_GET['dto'];
	$id = $_GET['id'];

	//GET SYSTEM CONFIGURATION SETTINGS
	$select1 = mysqli_query($link, "SELECT * FROM systemset") or die (mysqli_error($link));
	$row1 = mysqli_fetch_array($select1);
	$currency = $row1['currency'];
	$subaccount_charges = $row1['subaccount_charges'];
$authorization_charges = $row1['auth_charges'];

////////////////////////////ALL INCOME//////////////////////////////////////

//REQUESTING FOR TOTAL SUBSCRIPTION RANGES FROM DATE1 TO DATE2
$select = mysqli_query($link, "SELECT SUM(amount_paid) FROM saas_subscription_trans WHERE duration_from BETWEEN '$dfrom' AND '$dto' AND status = 'Paid' AND coopid_instid = '$id' ORDER BY id") or die (mysqli_error($link));
while($row = mysqli_fetch_array($select))
{
	//TOTAL SAASSUBSCRIPTION TRANSACTION AMOUNT
	$saassubscription = $row['SUM(amount_paid)'];

	//PROCESS TO DETECT TOTAL SUBACCOUNT CHARGES
	$select1 = mysqli_query($link, "SELECT SUM(amount_contributed) FROM coopsavings_transaction WHERE trans_date BETWEEN '$dfrom' AND '$dto' AND remarks = 'success' AND coopid = '$id' ORDER BY id") or die (mysqli_error($link));
	while($fetch1 = mysqli_fetch_array($select1))
	{
		//TOTAL SUBACCOUNT CHARGES
		$sum1 = $fetch1['SUM(amount_contributed)']; //TOTAL AMOUNT CONTRIBUTED
		$total_subacctcharge = ($subaccount_charges/100) * $sum1;

		$select2 = mysqli_query($link, "SELECT * FROM coop_members WHERE coopid = '$id'");
		while($fetch2 = mysqli_fetch_array($select2))
		{
			$memberid = $fetch2['memberid'];
			$select3 = mysqli_query($link, "SELECT * FROM coop_authorized_card WHERE date_time BETWEEN '$dfrom' AND '$dto' AND memberid = '$memberid'");
			$num1 = mysqli_num_rows($select3);

			$select11 = mysqli_query($link, "SELECT SUM(icm_amt) FROM income WHERE icm_date BETWEEN '$dfrom' AND '$dto' AND companyid = '$id'");
			while($fetch11 = mysqli_fetch_array($select11))
			{
				//OTHER INCOME IN TOTAL
				$sum11 = $fetch11['SUM(icm_amt)'];

				//TOTAL AUTHORIZATION CHARGES.
				$totalauth_charges = $num1 * $authorization_charges;

				$select4 = mysqli_query($link, "SELECT SUM(amount) FROM referral_incomehistory WHERE tdate BETWEEN '$dfrom' AND '$dto' AND status = 'Paid' AND referral_id = '$id'");
				while($fetch4 = mysqli_fetch_array($select4))
				{
					//TOTAL REFERRAL BONUS PAID
					$sum4 = $fetch4['SUM(amount)'];

					//CALCULATE TOTAL INCOME
					$total_income = $sum1+$sum4+$sum11;

					$total_expenses = $saassubscription+$totalauth_charges+$total_subacctcharge;

					$total_prAndloss = $total_income - $total_expenses;

		$output .= '
				<tr>
					<td height="20px;"><div align="left">Total Contributions</div></td>
					<td><div align="right">'.number_format($sum1,2,'.',',').'</div></td>
					<td><div align="left">Total Subscription</div></td>
					<td><div align="right">'.number_format($saassubscription,2,'.',',').'</div></td>
			  	</tr>
			  	<tr>
					<td height="20px;"><div align="left">Total Referral Bonus Payout</div></td>
					<td><div align="right">'.number_format($sum4,2,'.',',').'</div></td>
					<td><div align="left">Authorization Charges</div></td>
					<td><div align="right">'.number_format($totalauth_charges,2,'.',',').'</div></td>
			  	</tr>
			  	<tr>
					<td height="20px;"><div align="left">Others</div></td>
					<td><div align="right">'.number_format($sum11,2,'.',',').'</div></td>
					<td><div align="left">Total Subaccount Charges</div></td>
					<td><div align="right">'.number_format($total_subacctcharge,2,'.',',').'</div></td>
			  	</tr>
			  	<tr>
			  		<td height="20px;"><div align="left"><b style="font-size: 14px;">TOTAL INCOME</b></div></td>
					<td><div align="right"><b style="font-size: 14px;">'.number_format($total_income,2,'.',',').'</b></div></td>
			  		<td><div align="left"><b style="font-size: 14px;">TOTAL EXPENSES</b></div></td>
					<td><div align="right"><b style="font-size: 14px;">'.number_format($total_expenses,2,'.',',').'</b></div></td>
			  	</tr>';
		if($total_prAndloss <= 0)
		{
		
		$output .= '<tr>
					<td height="20px;"><div align="left"><b style="font-size: 12px;">TOTAL PROFIT</b></div></td>
					<td><div align="right"><b style="font-size: 12px;">----------</b></div></td>
					<td><div align="left"><b style="font-size: 12px;">TOTAL LOSS</b></div></td>
					<td><div align="right"><b style="font-size: 12px;">'.$total_prAndloss.'</b></div></td>
			  	</tr>';

		}else{

		$output .= '<tr>
					<td height="20px;"><div align="left"><b style="font-size: 12px;">TOTAL PROFIT</b></div></td>
					<td><div align="right"><b style="font-size: 12px;">'.number_format($total_prAndloss,2,'.',',').'</b></div></td>
					<td><div align="left"><b style="font-size: 12px;">TOTAL LOSS</b></div></td>
					<td><div align="right"><b style="font-size: 12px;">----------</b></div></td>
			  	</tr>';

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