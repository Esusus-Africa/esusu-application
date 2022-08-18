<?php
include("../connect.php");
function fetch_data()
{	
	$output = '';
	include("../connect.php");
	$startDate = $_GET['dfrom'];
    $endDate = $_GET['dto'];
    $customer = $_GET['cardID'];

	$api_name =  "transaction_history";
  	$search_restapi = mysqli_query($link, "SELECT * FROM atmcard_gateway_apis WHERE api_name = '$api_name' AND issuer_name = 'Mastercard'");
  	$fetch_restapi = mysqli_fetch_array($search_restapi);
  	$api_url = $fetch_restapi['api_url'];
	
  	$systemset = mysqli_query($link, "SELECT * FROM systemset");
  	$row1 = mysqli_fetch_array($systemset);
  	$bancore_merchantID = $row1['bancore_merchant_acctid'];
  	$bancore_mprivate_key = $row1['bancore_merchant_pkey'];
	  
	$passcode = $bancore_merchantID.$customer.$startDate.$endDate.$bancore_mprivate_key;
  	$encKey = hash('sha256',$passcode);
	    
	$url = '?merchantID='.$bancore_merchantID;
	$url.= '&cardID='.$customer;
	$url.= '&startDate='.$startDate;
	$url.= '&endDate='.$endDate;
	$url.= '&encKey='.$encKey;
              		  
	$urltouse =  $api_url.$url;
	//$urltouse = "https://kegow.bancore.com/getit/api/merchant/ministatement.do".$url;

	//if ($debug) { echo "Request: <br>$urltouse<br><br>"; }
	//Open the URL to send the message
	$response = file_get_contents($urltouse);
	
	$xml = simplexml_load_string($response);
	//convert into json
	$json  = json_encode($xml);
	//convert into associative array
	$xmlArr = json_decode($json, true);
	
	foreach($xmlArr['transactions'] as $key) {
			
		$output .= '<tr >
						<td align="center">'.date("m/d/Y m:s a", strtotime($key['date'])).'</td>
						<td align="center">'.$key['transactionReference'].'</td>
						<td align="center">'.$key['fundsCredited'].'</td>
						<td align="center">'.$key['fundsDebited'].'</td>
						<td align="center"><b>'.$key['cardBalance'].'</b></td>
						<td>'.$key['description'].'</td>
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
$pdf->SetTitle('Mastercard Transaction Reports');

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
		<div style="color:orange; font-size:15px;" align="center"><b>(Mastercard Transaction Reports)</b></div>
		<div style="color: #0073b7; font-size:15px;" align="center">Report from ['.$_GET['dfrom'].' to '.$_GET['dto'].'] for CardID: '.$_GET['cardID'].'</div>
          <small class="pull-right"><div style="color:black">'.date('l, F, d, Y', strtotime(date('y:m:d'))).'<br><br></div>
		</small>
	<table class="table table-bordered table-striped" cellspacing="0" cellpadding="1" border="1">
	<thead>
	 <tr>
	 	<th><div align="center">Date</div></th>
		<th><div align="center">Reference</div></th>
        <th><div align="center">Funds Credited</div></th>
        <th><div align="center">Funds Debited</div></th>
        <th><div align="center">Card Balance</div></th>
		<th><div>Description</div></th>
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