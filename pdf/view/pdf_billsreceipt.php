<?php
include("../connect.php");
function fetch_data()
{
	$output = '';
	include("../connect.php");
	//$link = mysqli_connect('localhost','root','root','lms-2.0') or die('Unable to Connect to Database'); 
	$refid = $_GET['refid'];
	$instid = $_GET['instid'];

	//GET SYSTEM CONFIGURATION SETTINGS
	$select1 = mysqli_query($link, "SELECT * FROM member_settings WHERE companyid = '$instid'") or die (mysqli_error($link));
	$row1 = mysqli_fetch_array($select1);
	$currency = $row1['currency'];

$select = mysqli_query($link, "SELECT SUM(credit), SUM(debit), recipient, currency, transaction_type, paymenttype, remark, status, date_time FROM wallet_history WHERE refid = '$refid'") or die (mysqli_error($link));
while($row = mysqli_fetch_array($select))
{
	//$idmet= $row['id'];
	$schedule = $row['date_time'];
	$recipient = $row['recipient'];
	$transaction_type = $row['transaction_type'];
	$debit = $row['SUM(debit)'];
	$credit = $row['SUM(credit)'];
	$amount = ($transaction_type == "Debit") ? $debit : $credit;
	$status = $row['status'];

		$output .= '
				<tr>
					<td height="27px;"><div align="center"><br><b>'.date ('l, M, d, Y', strtotime($schedule)).'</b></div></td>
					<td><div align="center"><br>'.$recipient.'</div></td>
					<td><div align="center"><br>'.$currency.number_format($amount,2,'.',',').'</div></td>
					<td><div align="center"><br>'.$status.'</div></td>
			  	</tr>';
}
	return $output;
}
	require_once('tcpdf_include.php');
	// create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetTitle('Payment Receipt');

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

$refid = $_GET['refid'];
$instid = $_GET['instid'];
$sys = mysqli_query($link, "SELECT * FROM member_settings WHERE companyid = '$instid'") or die (mysqli_error($link));
$row_sys = mysqli_fetch_array($sys);
$currency = $row_sys['currency'];
$cname = $row_sys['cname'];
$logo = $row_sys['logo'];

$protocol = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";

$select_bill = mysqli_query($link, "SELECT * FROM wallet_history WHERE refid = '$refid'") or die (mysqli_error($link));
$fetch_bill = mysqli_fetch_array($select_bill);

	$content = '
		<div align="center"><img src="'.($logo != "img/" || $logo != "" ? $protocol.$_SERVER['HTTP_HOST'].'/'.$logo : 'esusuafrica logo.png').'" width="80" height="80" class="user-image" alt="User Image">
		<div align="center"><h3><b style="font-size:20;">'.$cname.'</b></h3></div>
		<div align="center"><h3><b style="font-size: 15px;">(PAYMENT RECEIPT)</b></h3></div>
          <div style="color:black" align="left">'.date ('l, F, d, Y', strtotime(date('y:m:d'))).'<br></div>
          
		<table class="table table-bordered table-striped" width="100%" border="1">
	            <tr>
                  <th align="left" height="35px;"><b> PRODUCT TYPE:</b><br> '.$fetch_bill['paymenttype'].'</th>
                  <th align="left"><b> TRANSACTION ID:</b><br> '.$refid.'</th>
                  <th align="left"><b> DESCRIPTION:</b><br> '.$fetch_bill['remark'].'</th>
                </tr>';
    $content .= '</tbody></table>
	<table class="table table-bordered table-striped" width="100%" border="1">
			  	<tr>
                  <th height="25px;"><b style="font-size: 11px;"><br>PAYMENT DATE</b></th>
				  <th><b style="font-size: 11px;"><br>RECIPIENT</b></th>
				  <th><b style="font-size: 11px;"><br>AMOUNT PAID</b></th>
                  <th><b style="font-size: 11px;"><br>STATUS</b></th>
                </tr>';
	$content .= fetch_data();
	$content .= '</tbody></table>';
	 //$obj_pdf->writeHTML($content);
	 // Print text using writeHTMLCell()
	$pdf->writeHTMLCell(0, 0, '', '', $content, 0, 1, 0, true, '', true);
	$pdf->Output('file.pdf','I');

// --------------------------------------------------------
?>