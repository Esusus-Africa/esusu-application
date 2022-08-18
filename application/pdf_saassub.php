<?php
include("../config/session.php");

function fetch_data()
{
	$output = '';
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
		$output .= '<tr>
						<td>'.$row['coopid_instid'].'</td>
						<td>'.$row['refid'].'</td>
						<td>'.$row['sub_token'].'</td>
						<td>'.$row['plan_code'].'</td>
						<td>'.$row['amount_paid'].'</td>
						<td>'.$row['sms_allocated'].'</td>
						<td>'.$row['duration_to'].'</td>
						<td>'.$row['transaction_date'].'</td>
						<td>'.$row['status'].'</td>
					</tr>
					';
	}
	return $output;
}
if(isset($_POST["generate_pdf"]))
{
	require_once('tcpdf_include.php');
	// create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Nicola Asuni');
$pdf->SetTitle('TCPDF Example 001');
$pdf->SetSubject('TCPDF Tutorial');
$pdf->SetKeywords('TCPDF, PDF, example, test, guide');

// set default header data
$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' 001', PDF_HEADER_STRING, array(0,64,255), array(0,64,128));
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
$pdf->SetFont('dejavusans', '', 14, '', true);

// Add a page
// This method has several options, check the source code documentation for more information.
$pdf->AddPage();

// set text shadow effect
$pdf->setTextShadow(array('enabled'=>true, 'depth_w'=>0.2, 'depth_h'=>0.2, 'color'=>array(196,196,196), 'opacity'=>1, 'blend_mode'=>'Normal'));

	$content = <<<EOD
	<h4 align="center">Generate HTML Table Data From Mysqli Database Using TCPDF in PHP</h4><br>
	<table class="table table-bordered table-striped">
	<thead>
	 <tr>
	    <th>Company ID</th>
	    <th>Ref. ID</th>
	    <th>Token</th>
	    <th>Plan Code</th>
	    <th>Amount</th>
	    <th>SMS Allocated</th>
	    <th>Expired Date</th>
	    <th>Transaction Date</th>
	    <th>Status</th>
	 </tr>
	</thead>
	<tbody>
	fetch_data();
	</tbody></table>
EOD;

	 //$obj_pdf->writeHTML($content);
	 // Print text using writeHTMLCell()
	$pdf->writeHTMLCell(0, 0, '', '', $content, 0, 1, 0, true, '', true);
	$pdf->Output('file.pdf','I');

// --------------------------------------------------------
}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Example</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css"/>
</head>
<body>
	<br>
	<div class="container">
		<h4 align="center">Generate PDF</h4><br>
		<div class="table-responsive">
			<div class="col-md-12" align="right">
				<form method="post">
					<input type="submit" name="generate_pdf" class="btn btn-success" value="Generate PDF"/>
				</form>
			</div>
			<br>
			<br>
			<table class="table table-bordered">
				<thead>
				<tr>
					<th>Company ID</th>
				    <th>Ref. ID</th>
				    <th>Token</th>
				    <th>Plan Code</th>
				    <th>Amount</th>
				    <th>SMS Allocated</th>
				    <th>Expired Date</th>
				    <th>Transaction Date</th>
				    <th>Status</th>
				</tr>
			</thead>
			<tbody>
		<?php
			echo fetch_data();
		?>
			</tbody>	
			</table>
		</div>
	</div>
</body>
</html>
