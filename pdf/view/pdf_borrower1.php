<?php
include("../connect.php");
function fetch_data()
{
	$output = '';
	include("../connect.php");
	//$link = mysqli_connect('localhost','root','root','lms-2.0') or die('Unable to Connect to Database'); 
	$dfrom = $_GET['dfrom'];
    $dto = $_GET['dto'];
    $users = $_GET['oprt'];
	$sql = "SELECT * FROM loan_info WHERE date_release BETWEEN '$dfrom' AND '$dto' AND (teller = '$users' OR baccount = '$users' OR lid = '$users') ORDER BY id ASC";
	$result = mysqli_query($link, $sql);
	while($row = mysqli_fetch_array($result))
	{
		$id = $row['id'];
		$borrower = $row['borrower'];

		$select1 = mysqli_query($link, "SELECT * FROM systemset") or die (mysqli_error($link));
		$row1 = mysqli_fetch_array($select1);
		$currency = $row1['currency']; 

		$search_user = mysqli_query($link, "SELECT * FROM borrowers WHERE id = '$borrower'") or die (mysqli_error($link));
		$get_u = mysqli_fetch_array($search_user);
		$name = $get_u['fname'].'&nbsp;'.$get_u['lname'];

		$acctno = $get_u['account'];
		$sel_pend = mysqli_query($link, "SELECT sum(payment) FROM pay_schedule WHERE tid = '$acctno' AND status = 'UNPAID' AND schedule <= '$date_now'") or die (mysqli_error($link));
		$row_pend = mysqli_fetch_array($sel_pend);

		$sel_pend = mysqli_query($link, "SELECT sum(payment) FROM pay_schedule WHERE tid = '$acctno' AND status = 'UNPAID'") or die (mysqli_error($link));
		$row_pend = mysqli_fetch_array($sel_pend);

		$sel_pay = mysqli_query($link, "SELECT sum(amount_to_pay) FROM payments WHERE account_no = '$acctno'") or die (mysqli_error($link));
		$row_sel_pay = mysqli_fetch_array($sel_pay);

		$sel = mysqli_query($link, "SELECT * FROM loan_info WHERE borrower = '$borrower'") or die (mysqli_error($link));
		$num = mysqli_num_rows($sel);

		$selectb = mysqli_query($link, "SELECT sum(amount) FROM loan_info WHERE borrower = '$borrower'") or die (mysqli_error($link));
		$rowb = mysqli_fetch_array($selectb); 

		$selectbee = mysqli_query($link, "SELECT sum(amount), sum(interest_rate), sum(amount_topay) FROM loan_info WHERE borrower = '$borrower'") or die (mysqli_error($link));
		$rowbee = mysqli_fetch_array($selectbee);
		$get_rate = $rowbee['sum(interest_rate)'];
		$get_amt = $get_rate * $rowbee['sum(amount)'];

		$output .= '<tr>
						<td align="center"><b>'.$name.'</b></td>
						<td align="center"><div style="color: orange;"><b>'.$num.'</b></div></td>
						<td align="center"><div style="color: orange;">'.$currency.number_format($rowb['sum(amount)'],2,".",",").'</div></td>
						<td align="center"><div style="color: orange;">'.$currency.number_format($row_pend['sum(payment)'],2,'.',',').'</div></td>
						<td align="center"><div>'.$currency.number_format($row_sel_pay['sum(amount_to_pay)'],2,'.',',').'</div></td>
						<td align="center"><div style="color: orange;">'.$currency.number_format($row_pend['sum(payment)'],2,'.',',').'</div></td>
						<td align="center"><div style="color: orange;">'.$currency.number_format($get_amt,2,".",",").'</div></td>
						<td align="center"><div><b>'.$currency.number_format($rowbee['sum(amount_topay)'],2,".",",").'</b></div></td>
					</tr>
					';
	}
	return $output;
}
	require_once('tcpdf_include.php');
	// create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetTitle('Borrowers Lending Reports');

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
		<div style="color:orange; font-size:15px;" align="center"><b>(Borrowers Lending Report)</b></div>
		<div style="color: #0073b7; font-size:15px;" align="center">Report from ['.$_GET['dfrom'].' to '.$_GET['dto'].']</div>
          <small class="pull-right"><div style="color:black">'.date ('l, F, d, Y', strtotime(date('y:m:d'))).'<br><br></div>
		</small>
	<table class="table table-bordered table-striped" cellspacing="0" cellpadding="1" border="1">
	<thead>
	 <tr>
	    <th align="center"><b>Borrowers</b></th>
	    <th><div align="center"><b>Num. of Loan Released</b></div></th>
		<th><div align="center"><b>Principal Released</b></div></th>
        <th><div align="center"><b>Total Due Amount</b></div></th>
        <th><div align="center"><b>Total Repayment</b></div></th>
		<th><div align="center"><b>Loan Balance</b></div></th>
		<th><div align="center"><b>Total Interest Rate</b></div></th>
		<th><div align="center"><b>Grand Total</b></div></th>
	 </tr>
	</thead>
	<tbody>';
	$content .= fetch_data();
	$content .= '</tbody></table>';
	 //$obj_pdf->writeHTML($content);
	 // Print text using writeHTMLCell()
	$pdf->writeHTMLCell(0, 0, '', '', $content, 0, 1, 0, true, '', true);
	$pdf->Output('file.pdf','I');

// --------------------------------------------------------
?>