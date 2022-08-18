<?php
include("../connect.php");
function fetch_data()
{
	$output = '';
	include("../connect.php");
	$sid = $_GET['sid'];
	$instid = $_GET['instid'];

	//GET SYSTEM CONFIGURATION SETTINGS
	$select1 = mysqli_query($link, "SELECT * FROM member_settings WHERE companyid = '$instid'") or die (mysqli_error($link));
	$row1 = mysqli_fetch_array($select1);
	$currency = $row1['currency'];

    $i = 0;
    ($sid != "") ? $select = mysqli_query($link, "SELECT * FROM borrowers WHERE lofficer = '$sid' AND branchid = '$instid'") : "";
    ($sid == "") ? $select = mysqli_query($link, "SELECT * FROM borrowers WHERE branchid = '$instid'") : "";
    while($row = mysqli_fetch_array($select))
    {
        $i++;

            $output .= '
                    <tr>
                        <td height="12px;"><div align="center"><b>'.$i.'</b></div></td>
                        <td height="12px;"><div align="center">'.$row['account'].'</div></td>
                        <td height="12px;"><div align="center">'.strtoupper($row['fname'])." ".strtoupper($row['lname']).'</div></td>
                        <td height="12px;"><div align="center">'.$row['gname'].'</div></td>
                        <td height="12px;"><div align="center">'.$currency.number_format($row['balance'],2,'.',',').'</div></td>
                        <td height="12px;"><div align="center">'.$currency.number_format($row['target_savings_bal'],2,'.',',').'</div></td>
                        <td height="12px;"><div align="center">'.$currency.number_format($row['loan_balance'],2,'.',',').'</div></td>
                        <td height="12px;"><div align="center"><br></div></td>
                        <td height="12px;"><div align="center"><br></div></td>
                        <td height="12px;"><div align="center"><br></div></td>
                        <td height="12px;"><div align="center"><br></div></td>
                        <td height="12px;"><div align="center"><br></div></td>
                        <td height="12px;"><div align="center"><br></div></td>
                        <td height="12px;"><div align="center"><br></div></td>
                        <td height="12px;"><div align="center"><br></div></td>
                        <td height="12px;"><div align="center">'.$row['acct_opening_date'].'</div></td>
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
$instid = $_GET['instid'];
$sys = mysqli_query($link, "SELECT * FROM member_settings WHERE companyid = '$instid'") or die (mysqli_error($link));
$row_sys = mysqli_fetch_array($sys);
$currency = $row_sys['currency'];
$cname = $row_sys['cname'];
$logo = $row_sys['logo'];

$selectSysSet = mysqli_query($link, "SELECT * FROM systemset");
$fetchSysSet = mysqli_fetch_array($selectSysSet);
$imgBaseUrl = $fetchSysSet['file_baseurl'];

($sid != "") ? $searchStaff = mysqli_query($link, "SELECT * FROM user WHERE id = '$sid' AND created_by = '$instid'") : "";
($sid != "") ? $fetchStaff = mysqli_fetch_array($searchStaff) : "";
$sbranchid = $fetchStaff['sbranchid'];
$myfilename = ($sid == "") ? "Daily_Field_Collection_Sheet".uniqid() : $fetchStaff['name']."_".$fetchStaff['fname']."Daily_Field_Collection_Sheet".uniqid();

($sbranchid != "") ? $searchBranch = mysqli_query($link, "SELECT * FROM branches WHERE branchid = '$sbranchid' AND created_by = '$instid'") : "";
($sbranchid != "") ? $fetchBranch = mysqli_fetch_array($searchBranch) : "";

	$content = '
                <div align="center"><img src="'.($logo != "img/" || $logo != "" ? $imgBaseUrl.$logo : $imgBaseUrl.'esusuafrica 3.png').'" width="80" height="80" class="user-image" alt="User Image">
                <div align="center"><h3><b style="font-size:20;">'.$cname.'</b></h3></div>
                <div align="center"><h3><b style="font-size: 15px;">(Daily Field Collection Sheet)</b></h3></div>
                
                <table class="table table-bordered table-striped" width="100%" border="1">
	            <tr>
                  <th height="35px;"><b>Account Officer:</b><br>'.strtoupper($fetchStaff['fname'])." ".strtoupper($fetchStaff['name']).'</th>
                  <th><b>Branch:</b><br>'.(($sbranchid != "") ? $fetchBranch['bname'] : "Head Office").'</th>
				  <th><b>Collection Date:</b><br>'.date ('l, F, d, Y', strtotime(date('y:m:d'))).'</th>
                  <th><b>Total Collection:</b><br></th>
                </tr>';
    $content .= '</tbody></table>
	            <table class="table table-bordered table-striped" width="100%" border="1">
			  	<tr>
                  <th width="25px;">S/No</th>
				  <th width="70px;">Account Number</th>
				  <th width="86px;">Customer Name</th>
                  <th width="70px;">Group Name</th>
                  <th>Savings Bal</th>
                  <th>Target Bal</th>
                  <th>Loan Bal</th>
                  <th>Savings Product</th>
                  <th>Savings Amount Paid</th>
                  <th>Target Amount Paid</th>
                  <th>Loan Product</th>
                  <th>Union Purse</th>
                  <th>Overdue</th>
                  <th>No. of Days</th>
                  <th>Total Paid</th>
                  <th>Account Opening Date</th>
                </tr>';
	$content .= fetch_data();
	$content .= '</tbody></table>
	            <footer>
                <table class="table table-bordered table-striped" width="100%">
                <tr>
                <td><b><p>No. of Borrowers: _______________</p></b></td>
                <td><b><p>Total Loan Balance: _______________</p></b></td>
                <td><b><p>Savings Balance: _______________</p></b></td>
                <td><b><p>Union Purse Balance: _______________</p></b></td>
                </tr>';
    $content .= '</table></footer>';
	 //$obj_pdf->writeHTML($content);
	 // Print text using writeHTMLCell()
	$pdf->writeHTMLCell(0, 0, '', '', $content, 0, 1, 0, true, '', true);
	$pdf->Output($myfilename,'I');

// --------------------------------------------------------
?>