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

	///////////////////////////////ALL EXPENSES/////////////////////////////////

//REQUESTING FOR TOTAL TRANSACTION FEE
$select = mysqli_query($link, "SELECT SUM(fee) FROM revenue_transaction WHERE date_time BETWEEN '$dfrom' AND '$dto' AND branchid = '$id' ORDER BY id") or die (mysqli_error($link));
while($row = mysqli_fetch_array($select))
{
	//TOTAL SAAS TRANSACTION FEE
	$saas_transaction_fee = $row['SUM(fee)'];

	//REQUESTING FOR OTHER TOTAL EXPENSES
	$select1 = mysqli_query($link, "SELECT SUM(eamt) FROM expenses WHERE edate BETWEEN '$dfrom' AND '$dto' AND branchid = '$id'");
	while($fetch1 = mysqli_fetch_array($select1))
	{
		//TOTAL AMOUNT OF ALL OTHER EXPENSES ENTERED MANUALLY TO THE SYSTEM
		$sum1 = $fetch1['SUM(eamt)'];
		
		//REQUESTING FOR OTHER TOTAL WALLET TRANSACTION
		$select2 = mysqli_query($link, "SELECT SUM(amount) FROM wallet_history WHERE date_time BETWEEN '$dfrom' AND '$dto' AND userid = '$id' AND recipient != 'self'");
		while($fetch2 = mysqli_fetch_array($select2))
		{
			//TOTAL AMOUNT OF ALL OTHER EXPENSES ENTERED MANUALLY TO THE SYSTEM
			$sum2 = $fetch2['SUM(amount)'];
			
			
			///////////////////////////////ALL INCOME/////////////////////////////////

			//REQUESTING FOR TOTAL REVENUE GENERATED (INFLOW)
			$select3 = mysqli_query($link, "SELECT SUM(amount) FROM revenue_transaction WHERE date_time BETWEEN '$dfrom' AND '$dto' AND branchid = '$id' ORDER BY id") or die (mysqli_error($link));
			while($row3 = mysqli_fetch_array($select3))
			{
				//TOTAL REVENUE GENERATED
				$sum3 = $row3['SUM(amount)'];
				
				//REQUESTING FOR OTHER INCOME
				$select4 = mysqli_query($link, "SELECT SUM(icm_amt) FROM income WHERE icm_date BETWEEN '$dfrom' AND '$dto' AND companyid = '$id'");
				while($fetch4 = mysqli_fetch_array($select4))
				{
					//OTHER INCOME IN TOTAL
					$sum4 = $fetch4['SUM(icm_amt)'];


					//CALCULATE TOTAL INCOME
					$total_income = $sum3+$sum4;
					
					//CALCULATE TOTAL EXPENSES
					$total_expenses = $saas_transaction_fee+$sum1+$sum2;

					$total_prAndloss = $total_income - $total_expenses;

		$output .= '
				<tr>
					<td height="20px;"><div align="left">Total Revenue Generated</div></td>
					<td><div align="right">'.number_format($sum3,2,'.',',').'</div></td>
					<td><div align="left">Total Transaction Fee</div></td>
					<td><div align="right">'.number_format($saas_transaction_fee,2,'.',',').'</div></td>
			  	</tr>
			  	<tr>
					<td height="20px;"><div align="left">Other Income</div></td>
					<td><div align="right">'.number_format($sum4,2,'.',',').'</div></td>
					<td><div align="left">Total Wallet Expenses</div></td>
					<td><div align="right">'.number_format($sum2,2,'.',',').'</div></td>
			  	</tr>
			  	<tr>
					<td height="20px;"><div align="left"></div></td>
					<td><div align="right"></div></td>
					<td><div align="left">Other Expenses</div></td>
					<td><div align="right">'.number_format($sum1,2,'.',',').'</div></td>
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
$pdf->SetTitle('REVENUE FINANCIAL REPORT');

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

$revenue_id = $_GET['id'];
$sys = mysqli_query($link, "SELECT * FROM member_settings WHERE companyid = '$revenue_id'");
$row_sys = mysqli_fetch_array($sys);

	$content = '
		<div align="center"><b style="color:#0073b7; font-size:20;">'.$row_sys['cname'].'</b></div>
		<div style="color:orange; font-size:15px;" align="center"><b>(Revenue Financial Report)</b></div>
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