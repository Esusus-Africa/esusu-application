<?php
include("../connect.php");
function fetch_data()
{
	$output = '';
	include("../connect.php");
	$sid = $_GET['sid'];
	$nid = $_GET['nid'];
	$instid = $_GET['instid'];
    $date_now = date("Y-m-d");

	//GET SYSTEM CONFIGURATION SETTINGS
	$select1 = mysqli_query($link, "SELECT * FROM member_settings WHERE companyid = '$instid'") or die (mysqli_error($link));
	$row1 = mysqli_fetch_array($select1);
	$currency = $row1['currency'];

    $i = 0;    
    ($sid != "" && $sid != "all" && $nid != "") ? $select = mysqli_query($link, "SELECT DISTINCT(*) FROM loan_info WHERE agent = '$sid' AND branchid = '$instid' AND lproduct = '$nid' AND p_status != 'PAID' AND (status = 'Approved' OR status = 'Disbursed')") : "";
    ($sid != "" && $sid != "all" && $nid == "") ? $select = mysqli_query($link, "SELECT DISTINCT(*) FROM loan_info WHERE branchid = '$instid' AND agent = '$sid' AND p_status != 'PAID' AND (status = 'Approved' OR status = 'Disbursed')") : "";
    ($sid == "all" && $nid == "") ? $select = mysqli_query($link, "SELECT DISTINCT(*) FROM loan_info WHERE branchid = '$instid' AND p_status != 'PAID' AND (status = 'Approved' OR status = 'Disbursed')") : "";
    while($row = mysqli_fetch_array($select))
    {
        $i++;
        $borrowerAcct = $row['baccount'];
        $lproduct = $row['lproduct'];
        $searchG = mysqli_query($link, "SELECT * FROM loan_product WHERE id = '$lproduct'");
        $fetchG = mysqli_fetch_array($searchG);

        $searchB = mysqli_query($link, "SELECT * FROM borrowers WHERE account = '$borrowerAcct'");
        $fetchB = mysqli_fetch_array($searchB);

        $myExpAmt = mysqli_query($link, "SELECT SUM(balance), SUM(interest_rate), SUM(amount), SUM(amount_topay) FROM loan_info WHERE baccount = '$borrowerAcct' AND p_status != 'PAID' AND (status = 'Approved' OR status = 'Disbursed')");
        $getExpAmt = mysqli_fetch_array($myExpAmt);
        
        $selectDuePay = mysqli_query($link, "SELECT SUM(payment) FROM pay_schedule WHERE tid = '$borrowerAcct' AND schedule <= '$date_now' AND status = 'UNPAID'");
        $fetchDuePay = mysqli_fetch_array($selectDuePay);

        $mypdays = mysqli_query($link, "SELECT SUM(amount_to_pay) FROM payments WHERE account_no = '$borrowerAcct' AND remarks = 'paid'");
        $getpdays = mysqli_fetch_array($mypdays);

            $output .= '
                    <tr>
                        <td height="12px;"><div align="center"><b>'.$i.'</b></div></td>
                        <td height="12px;"><div align="center">'.$borrowerAcct.'</div></td>
                        <td height="12px;"><div align="center">'.strtoupper($fetchB['fname'])." ".strtoupper($fetchB['lname']).'</div></td>
                        <td height="12px;"><div align="center">'.$fetchG['pname'].'</div></td>
                        <td height="12px;"><div align="center">'.$row['loantype'].'</div></td>
                        <td height="12px;"><div align="center">'.number_format($row['SUM(amount)'],2,'.',',').'</div></td>
                        <td height="12px;"><div align="center">'.number_format($row['SUM(interest_rate)'],2,'.',',').'</div></td>
                        <td height="12px;"><div align="center">'.$fetchG['interest_type'].'</div></td>
                        <td height="12px;"><div align="center">'.number_format($row['SUM(amount_topay)'],2,'.',',').'</div></td>
                        <td height="12px;"><div align="center">'.number_format($row['SUM(balance)'],2,'.',',').'</div></td>
                        <td height="12px;"><div align="center">'.number_format($fetchDuePay['SUM(payment)'],2,'.',',').'</div></td>
                        <td height="12px;"><div align="center">'.number_format($getpdays['SUM(amount_to_pay)'],2,'',',').'</div></td>
                    </tr>';
    }
    return $output;
}
require_once('tcpdf_include.php');
// create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetTitle('Daily Field Collection Sheet');

// set default header data
//$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' 001', PDF_HEADER_STRING, array(0,64,255), array(0,64,128));
$pdf->setFooterData(array(0,64,0), array(0,64,128));

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
//$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetMargins(0.7, 2, 0.7);
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
$pdf->AddPage('L');

// set text shadow effect
$pdf->setTextShadow(array('enabled'=>true, 'depth_w'=>0.2, 'depth_h'=>0.2, 'color'=>array(196,196,196), 'opacity'=>1, 'blend_mode'=>'Normal'));

$sid = $_GET['sid'];
$nid = $_GET['nid'];
$instid = $_GET['instid'];
$sys = mysqli_query($link, "SELECT * FROM member_settings WHERE companyid = '$instid'") or die (mysqli_error($link));
$row_sys = mysqli_fetch_array($sys);
$currency = $row_sys['currency'];
$cname = $row_sys['cname'];
$logo = $row_sys['logo'];

$selectSysSet = mysqli_query($link, "SELECT * FROM systemset");
$fetchSysSet = mysqli_fetch_array($selectSysSet);
$imgBaseUrl = $fetchSysSet['file_baseurl'];

($sid != "" && $sid != "all") ? $searchStaff = mysqli_query($link, "SELECT * FROM user WHERE id = '$sid' AND created_by = '$instid'") : "";
($sid != "" && $sid != "all") ? $fetchStaff = mysqli_fetch_array($searchStaff) : "";
$sbranchid = $fetchStaff['sbranchid'];
$myfilename = ($sid == "" || $sid == "all") ? "Loan_Collection_Reports".uniqid() : $fetchStaff['name']."_".$fetchStaff['fname']."Loan_Collection_Reports".uniqid();

($sbranchid != "") ? $searchBranch = mysqli_query($link, "SELECT * FROM branches WHERE branchid = '$sbranchid' AND created_by = '$instid'") : "";
($sbranchid != "") ? $fetchBranch = mysqli_fetch_array($searchBranch) : "";

$myPrincipalAmt = mysqli_query($link, "SELECT SUM(amount) FROM loan_info WHERE branchid = '$instid' AND p_status != 'PAID' AND (status = 'Approved' OR status = 'Disbursed')");
$getPrincipalAmt = mysqli_fetch_array($myPrincipalAmt);

	$content = '
                <div align="center"><img src="'.($logo != "img/" || $logo != "" ? $imgBaseUrl.$logo : $imgBaseUrl.'esusuafrica 3.png').'" width="80" height="80" class="user-image" alt="User Image">
                <div align="center"><h3><b style="font-size:20;">'.$cname.'</b></h3></div>
                <div align="center"><h3><b style="font-size: 15px;">(Loan Collection Reports)</b></h3></div>
                
                <table class="table table-bordered table-striped" width="100%" border="1">
	            <tr>
                  <th height="35px;"><b>Loan Officer:</b><br>'.(($sid != "" && $sid != "all") ? strtoupper($fetchStaff['fname'])." ".strtoupper($fetchStaff['name']) : "All").'</th>
                  <th><b>Branch:</b><br>'.(($sid != "" && $sid != "all" && $sbranchid != "") ? $fetchBranch['bname'] : (($sid != "" && $sid != "all" && $sbranchid == "") ? "Head Office" : "All")).'</th>
				  <th><b>Report Date:</b><br>'.date('l, F, d, Y', strtotime(date('y:m:d'))).'</th>
                  <th><b>Overall Principal Amount::</b><br>'.number_format($getPrincipalAmt['SUM(amount)'],2,'.',',').'</th>
                </tr>';
    $content .= '</tbody></table>
	            <table class="table table-bordered table-striped" width="100%" border="1">
			  	<tr>
                  <th width="25px;">S/No</th>
				  <th width="70px;">Account Number</th>
				  <th width="100px;">Customer Name</th>
                  <th width="70px;">Loan Product</th>
                  <th width="75px;">Loan Type/th>
                  <th>Principal Amount ('.$currency.')</th>
                  <th>Interest Rate</th>
                  <th>Rate Method</th>
                  <th>Amount to Pay ('.$currency.')</th>
                  <th>Loan Balance ('.$currency.')</th>
                  <th>Overdue ('.$currency.')</th>
                  <th>Total Paid ('.$currency.')</th>
                </tr>';
	$content .= fetch_data();
	$content .= '</tbody></table>';
	 //$obj_pdf->writeHTML($content);
	 // Print text using writeHTMLCell()
	$pdf->writeHTMLCell(0, 0, '', '', $content, 0, 1, 0, true, '', true);
	$pdf->Output($myfilename,'I');

// --------------------------------------------------------
?>