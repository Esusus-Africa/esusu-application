<?php
include("../connect.php");
function fetch_data()
{
	$output = '';
	include("../connect.php");
	//$link = mysqli_connect('localhost','root','root','lms-2.0') or die('Unable to Connect to Database'); 
	$dfrom = $_GET['dfrom'];
	$dto = $_GET['dto'];
	$status = $_GET['status'];
	$coopid = $_GET['coopid'];
	$instid = $_GET['instid'];
	$agentid = $_GET['agentid'];
	$merchantid = $_GET['merchantid'];
	$sql = "SELECT * FROM saas_subscription_trans WHERE transaction_date BETWEEN '$dfrom' AND '$dto' AND coopid_instid = '$coopid' AND status = '$status' OR status = '$status' AND coopid_instid = '$instid' OR coopid_instid = '$agentid' AND status = '$status' OR status = '$status' AND coopid_instid = '$merchantid' ORDER BY id ASC";
	$result = mysqli_query($link, $sql);
	while($row = mysqli_fetch_array($result))
	{
		$select1 = mysqli_query($link, "SELECT * FROM systemset") or die (mysqli_error($link));
		$row1 = mysqli_fetch_array($select1);
		$currency = $row1['currency']; 

		$output .= '<tr>
						<td align="center"><b>'.$row['coopid_instid'].'</b></td>
						<td align="center">'.$row['refid'].'</td>
						<td align="center"><b>'.$row['sub_token'].'</b></td>
						<td align="center">'.$row['plan_code'].'</td>
						<td align="center"><b>'.number_format($row['amount_paid'],2,'.',',').'</b></td>
						<td align="center">'.$row['sms_allocated'].' SMS</td>
						<td align="center"><b>'.$row['duration_to'].'</b></td>
						<td align="center">'.$row['transaction_date'].'</td>
						<td align="center"><b>'.$row['status'].'</b></td>
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
$pdf->SetTitle('Software Subscription Reports');

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
		<div style="color:orange; font-size:15px;" align="center"><b>(Software Subscription Report)</b></div>
		<div style="color: #0073b7; font-size:15px;" align="center">Report from ['.$_GET['dfrom'].' to '.$_GET['dto'].']</div>
          <small class="pull-right"><div style="color:black">'.date ('l, F, d, Y', strtotime(date('y:m:d'))).'<br><br></div>
		</small>
	<table class="table table-bordered table-striped" cellspacing="0" cellpadding="1" border="1">
	<thead>
	 <tr>
	    <th align="center"><b>Company ID</b></th>
	    <th align="center"><b>Ref. ID</b></th>
	    <th align="center"><b>Token</b></th>
	    <th align="center"><b>Plan Code</b></th>
	    <th align="center"><b>Amount ('.$row_sys['currency'].')</b></th>
	    <th align="center"><b>SMS Allocated</b></th>
	    <th align="center"><b>Expired Date</b></th>
	    <th align="center"><b>Transaction Date</b></th>
	    <th align="center"><b>Status</b></th>
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