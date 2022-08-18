<?php
include("../connect.php");
function fetch_data()
{
	$output = '';
	include("../connect.php");
	//$link = mysqli_connect('localhost','root','root','lms-2.0') or die('Unable to Connect to Database'); 
	include("../restful_apicalls.php");

	$result = array();
	$dfrom = $_GET['dfrom'];
	$dto = $_GET['dto'];
	$pagesize = $_GET['pagesize'];
	$cardlist = $_GET['cardlist'];
	$systemset = mysqli_query($link, "SELECT * FROM systemset");
	$get_sys = mysqli_fetch_array($systemset);
	$seckey = $get_sys['secret_key'];

	$search_account = mysqli_query($link, "SELECT * FROM card_enrollment WHERE card_id = '$cardlist'");
	$fetch_account = mysqli_fetch_object($search_account);
	$issuer_name = $fetch_account->api_used;

	$search_restapi = mysqli_query($link, "SELECT * FROM atmcard_gateway_apis WHERE issuer_name = '$issuer_name' AND api_name = 'fetch-card-transactions'");
	$fetch_restapi = mysqli_fetch_object($search_restapi);
	$api_url = $fetch_restapi->api_url;

	// Pass the parameter here
	$postdata =  array(
		"FromDate"	=>	$dfrom,
		"ToDate"=>	$dto,
		"PageIndex"	=>	0,
		"PageSize"	=>	$pagesize,
		"CardId"	=>	$cardlist,
		"secret_key"	=>	$seckey
	);

	$make_call = callAPI('POST', $api_url, json_encode($postdata));
	$result = json_decode($make_call, true);

	var_dump($result);

	if($result['Status'] == "success")
	{

		foreach($result['data']['Transactions'] as $key)
		{
	
		$output .= '<tr>
						<td align="center"><b>'.$key['Id'].'</b></td>
						<td align="center">'.$key['Currency'].$key['TransactionAmount'].'</td>
						<td align="center"><b>'.$key['Fee'].'</b></td>
						<td align="center">'.$key['ProductName'].'</td>
						<td align="center"><b>'.$key['UniqueReferenceDetails'].'</b></td>
						<td align="center">'.$key['TransactionReference'].'</td>
						<td align="center"><b>'.$key['UniqueReference'].'</b></td>
						<td align="center">'.$key['StatusName'].'</td>
						<td align="center">'.$key['Description'].'</td>
						<td align="center">'.$key['Narration'].'</td>
						<td align="center">'.$key['DateCreated'].'</td>
					</tr>
					';
		}
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
		<div style="color:orange; font-size:15px;" align="center"><b>(Card Trasaction Report)</b></div>
		<div style="color: #0073b7; font-size:15px;" align="center">Report from ['.$_GET['dfrom'].' to '.$_GET['dto'].']</div>
          <small class="pull-right"><div style="color:black">'.date ('l, F, d, Y', strtotime(date('y:m:d'))).'<br><br></div>
		</small>
	<table class="table table-bordered table-striped" cellspacing="0" cellpadding="1" border="1">
	<thead>
	 <tr>
	    <th align="center"><b>ID</b></th>
	    <th align="center"><b>Transaction Amount</b></th>
	    <th align="center"><b>Fee</b></th>
	    <th align="center"><b>ProductName</b></th>
	    <th align="center"><b>UniqueReferenceDetails</b></th>
	    <th align="center"><b>TransactionReferenced</b></th>
	    <th align="center"><b>Card Number</b></th>
	    <th align="center"><b>StatusName</b></th>
	    <th align="center"><b>Description</b></th>
	    <th align="center"><b>Narration</b></th>
	    <th align="center"><b>DateCreated</b></th>
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