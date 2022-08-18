<?php
include("../connect.php");
function fetch_data()
{
	$output = '';
	include("../connect.php");
	//$link = mysqli_connect('localhost','root','root','lms-2.0') or die('Unable to Connect to Database');
	$id = $_GET['id'];
	$acn = $_GET['acn'];
	$lid = $_GET['lid'];
	$instid = $_GET['instid'];

	//GET SYSTEM CONFIGURATION SETTINGS
	$select1 = mysqli_query($link, "SELECT * FROM member_settings WHERE companyid = '$instid'") or die (mysqli_error($link));
	$row1 = mysqli_fetch_array($select1);
	$currency = $row1['currency'];

$select = mysqli_query($link, "SELECT * FROM pay_schedule WHERE get_id = '$id'") or die (mysqli_error($link));
while($row = mysqli_fetch_array($select))
{
	$idmet= $row['id'];
	$schedule = $row['schedule'];
	$balance = $row['balance'];
	$payment = $row['payment'];
	$status = $row['status'];

		$output .= '
				<tr>
					<td height="27px;"><div align="center"><br><b>'.date ('l, M, d, Y', strtotime($schedule)).'</b></div></td>
					<td><div align="center"><br>'.$currency.number_format($payment,2,'.',',').'</div></td>
					<td><div align="center"><br>'.$currency.number_format($balance,2,'.',',').'</div></td>
			  	</tr>';
}
	return $output;
}
	require_once('tcpdf_include.php');
	// create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetTitle('Loan Payment Schedule');

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

$lid = $_GET['lid'];
$instid = $_GET['instid'];
$sys = mysqli_query($link, "SELECT * FROM member_settings WHERE companyid = '$instid'") or die (mysqli_error($link));
$row_sys = mysqli_fetch_array($sys);
$currency = $row_sys['currency'];
$cname = $row_sys['cname'];
$logo = $row_sys['logo'];

$selectSysSet = mysqli_query($link, "SELECT * FROM systemset");
$fetchSysSet = mysqli_fetch_array($selectSysSet);
$imgBaseUrl = $fetchSysSet['file_baseurl'];

$select_loan = mysqli_query($link, "SELECT * FROM loan_info WHERE lid = '$lid'") or die (mysqli_error($link));
$fetch_loan = mysqli_fetch_array($select_loan);
$lproduct = $fetch_loan['lproduct'];
$borrower = $fetch_loan['borrower'];

$select_loanp = mysqli_query($link, "SELECT * FROM loan_product WHERE id = '$lproduct'") or die (mysqli_error($link));
$fetch_loanp = mysqli_fetch_array($select_loanp);

$select_borrower = mysqli_query($link, "SELECT * FROM borrowers WHERE id = '$borrower'") or die (mysqli_error($link));
$fetch_borrower = mysqli_fetch_array($select_borrower);
$bname = $fetch_borrower['lname'].' '.$fetch_borrower['fname'];

	$content = '
		<div align="center"><img src="'.($logo != "img/" || $logo != "" ? $imgBaseUrl.$logo : $imgBaseUrl.'esusuafrica 3.png').'" width="80" height="80" class="user-image" alt="User Image">
		<div align="center"><h3><b style="font-size:20;">'.$cname.'</b></h3></div>
		<div align="center" style="font-size: 18px;"><b>LOAN OFFER LETTER</b><br></div>

        <div style="color:black" align="left"><b>Loan ID: </b>'.$fetch_loan['lid'].'</div>
        <div style="color:black" align="left"><b>Date: </b>'.date ('l, F, d, Y', strtotime(date('y:m:d'))).'</div>
        <div style="color:black" align="left"><b>To Borrower: </b><u>'.$bname.' ('.$fetch_loan['baccount'].')</u><br></div>
        
        <div style="color:black" align="left">Dear Sir/Madam,</div>
        <div style="color:black" align="left"><b><u>RE: APPLICATION FOR '.$fetch_loanp['pname'].' LOAN FACILITY</u></b><br></div>
		
        <div style="color:black" align="left">
            We are pleased to offer you an '.$fetch_loanp['pname'].' Loan Facility ("Facility") subject to the following terms and repayment schedule: -
            <br>
        </div>
        <table class="table table-bordered table-striped" width="100%" border="1">
	            <tr>
                  <th height="35px;"><b>PRINCIPAL AMOUNT:</b><br>'.$currency.number_format($fetch_loan['amount'],2,'.',',').'</th>
                  <th><b>INTEREST RATE:</b><br>'.($fetch_loan['interest_rate']*100).'%</th>
				  <th><b>AMOUNT + INTEREST:</b><br>'.$currency.number_format($fetch_loan['amount_topay'],2,'.',',').'<p><b>LOAN BALANCE:</b><br>'.$currency.number_format($fetch_loan['balance'],2,'.',',').'</p></th>
                </tr>
                <tr>
                  <th height="35px;"><b>DURATION:</b><br>'.(($fetch_loanp['tenor'] == "Weekly") ? $fetch_loanp['duration'].' Week(s)' : $fetch_loanp['duration'].' Month(s)').'</th>
                  <th><b>LOAN PRODUCT:</b><br>'.$fetch_loanp['pname'].'</th>
				  <th><b>APPROVAL DATE:</b><br>'.(($fetch_loan['date_release'] == "") ? "----" : $fetch_loan['date_release']).'</th>
                </tr>';
    $content .= '</tbody></table>
	<table class="table table-bordered table-striped" width="100%" border="1">
			  	<tr>
                  <th height="25px;"><b style="font-size: 11px;"><br>EXPECTED REPAYMENT DATE</b></th>
				  <th><b style="font-size: 11px;"><br>AMOUNT TO PAY</b></th>
				  <th><b style="font-size: 11px;"><br>BALANCE</b></th>
                </tr>';
	$content .= fetch_data();
	$content .= '</tbody></table>
                <p></p>
                <p></p>
                <div style="color:black" align="left">Yours faithfully,</div>
                <div style="color:black" align="left"><b>'.$cname.'</b> Manager.</div>';
	 //$obj_pdf->writeHTML($content);
	 // Print text using writeHTMLCell()
	$pdf->writeHTMLCell(0, 0, '', '', $content, 0, 1, 0, true, '', true);
	$pdf->Output('file.pdf','I');

// --------------------------------------------------------
?>