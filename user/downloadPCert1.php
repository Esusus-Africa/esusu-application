<?php
include "../config/session.php";
// Include autoloader 
require_once "../dompdf/autoload.inc.php";

// Reference the Dompdf namespace 
use Dompdf\Dompdf;

// Instantiate and use the dompdf class 
$dompdf = new Dompdf();
$dompdf->set_option('isRemoteEnabled', TRUE);

$oPid = $_GET['oPid']; //get origin_planid
$subId = $_GET['subId']; //get sub id
$detect_subplan = mysqli_query($link, "SELECT * FROM savings_plan WHERE merchantid_others = '$bbranchid' AND plan_id = '$oPid'");
$fetch_subplan = mysqli_fetch_array($detect_subplan);
$merchantid_others = $fetch_subplan['merchantid_others'];
$vendorid = $fetch_subplan['branchid'];

$search_sSub= mysqli_query($link, "SELECT * FROM savings_subscription WHERE id = '$subId'");
$fetch_sSub = mysqli_fetch_array($search_sSub);
$subDate = $fetch_sSub['date_time'];
$subMaturityDate = $fetch_sSub['mature_date'];
  
$search_vendors = mysqli_query($link, "SELECT * FROM vendor_reg WHERE companyid = '$vendorid'");
$fetch_vendors = mysqli_fetch_object($search_vendors);
  
$select_memset = mysqli_query($link, "SELECT * FROM member_settings WHERE companyid = '$merchantid_others'") or die (mysqli_error($link));
$row_memset = mysqli_fetch_object($select_memset);

$select_instData = mysqli_query($link, "SELECT * FROM institution_data WHERE institution_id = '$merchantid_others'") or die (mysqli_error($link));
$row_instData = mysqli_fetch_object($select_instData); 
  
$logopath = ($vendorid == "") ? $row_memset->logo : $fetch_vendors->logo;
$cname = ($vendorid == "") ? $row_memset->cname : $fetch_vendors->cname;
$caddress = ($vendorid == "") ? $row_instData->location : $fetch_vendors->caddrs;
$cphone = ($vendorid == "") ? $row_instData->official_phone : $fetch_vendors->cphone;

$f = new NumberFormatter("en", NumberFormatter::SPELLOUT);
$f->setTextAttribute(NumberFormatter::DEFAULT_RULESET, "%spellout-numbering-verbose");

$mydate = date('jS F Y', strtotime($subDate));
$bits = explode(' ',$mydate);
$formatedDate = "<u>".$bits[0].'</u> day of <u>'.strtoupper($bits[1]).'</u> '.ucwords($f->format($bits[2]));

$dompdf->loadHtml('
    <style>
    .just {
      text-align: justify;
      text-justify: inter-word;
    }
    .rotate {
      opacity: 0.1;
      
      transform: rotate(-40deg);
    
      /* Legacy vendor prefixes that you probably dont need... */
      /* Safari */
      -webkit-transform: rotate(-40eg);
      /* Firefox */
      -moz-transform: rotate(-40deg);
      /* IE */
      -ms-transform: rotate(-40deg);
      /* Opera */
      -o-transform: rotate(-40deg);
      /* Internet Explorer */
      filter: progid:DXImageTransform.Microsoft.BasicImage(rotation=3);
    }
    </style>
    <div align="right" style="font-size: 13px;">
        <img src="'.$fetchsys_config['file_baseurl'].$logopath.'" height="80px" width="80px"/>
        <br><b>'.$cname.'</b>
        <br>'.$caddress.'
        <br>Tel: '.$cphone.'
    </div>
    <br>
    <div class="just" style="font-size: 13px;">
    We, <b><i>The '.$cname.'</i></b>, (hereinafter called the <b>"COMPANY"</b> having received a proposal and  Declaration containing 
    statements and particulars declared and warranted to be true and absolutely complete for the product subscription of 
    <b>'.strtoupper($bname).'</b> (hereinafter called the <b>"SUBSCRIBER"</b>) dated the <b><i>'.date('d/m/Y', strtotime($subDate)).'</i></b> which proposal and declaration 
    shall be the basis of this Subscription, and in consideration of the payment of the contribution mentioned in the schedule, 
    (the Company) will pay the Total Contribution on the event on which it is to become payable to the terms of the schedule 
    which together with the General Conditions and any future endorsement shall form an integral part of this plan.
    </div>
    <div align="center" style="font-size: 13px;"><h3>'.strtoupper($fetch_subplan['plan_name']).' CERTIFICATE</h3></div>
    <table cellpadding="0" cellspacing="0" border="1" style="font-size: 13px; border-color: #FFFFFF;">
        <tr>
            <td><b>PLAN NO</b></td>
            <td height="30px"><b>'.$fetch_subplan['plan_code'].'</b></td>
        </tr>
        <tr>
            <td>Name of Participant</td>
            <td height="30px"><b>'.$bname.'</b></td>
        </tr>
        <tr>
            <td>Residential  Address</td>
            <td height="30px"><b>'.$baddrs.'</b></td>
        </tr>
        <tr>
            <td>Occupation</td>
            <td height="30px"><b>'.$boccupation.'</b></td>
        </tr>
        <tr>
            <td>Date of Proposal and Declaration</td>
            <td height="30px"><b>'.date('d/m/Y', strtotime($subDate)).'</b></td>
        </tr>
        <tr>
            <td>Date of Birth</td>
            <td height="30px"><b>'.$dateofbirth.'</b></td>
        </tr>
        <tr>
            <td>Date of Commencement</td>
            <td height="30px"><b>'.date('d/m/Y', strtotime($subDate)).'</b></td>
        </tr>
        <tr>
            <td>Date of Maturity</td>
            <td height="30px"><b>'.date('d/m/Y', strtotime($subMaturityDate)).'</b></td>
        </tr>
        <tr>
            <td>Type of Plan</td>
            <td height="30px"><b>'.$fetch_subplan['plan_name'].'</b></td>
        </tr>
        <tr>
            <td><b>Benefit Payable at Maturity From:</b></td>
            <td height="30px">Total Contribution under Participants Contributable Fund Less Participants’ Risk Fund (PRF) Plus Proportionate surplus (if any) as at that last valuation.</td>
        </tr>
        <tr>
            <td>
                <b>Benefit Payable on Death From:</b>
                <ul>
                    <li>To Whom Payable</li>
                </ul>
            </td>
            <td>'.$bnok.'('.$bnok_rela.')</td>
        </tr>
        <tr>
            <td>Takaful Installment Payable</td>
            <td height="30px"><b>'.$fetch_sSub['currency'].number_format($fetch_sSub['amount'],2,'.',',').'</b></td>
        </tr>
        <tr>
            <td>How Payable</td>
            <td height="30px"><b>'.$fetch_sSub['savings_interval'].'</b></td>
        </tr>
        <tr>
            <td>Period during which payable</td>
            <td height="30px"><b>'.(($fetch_sSub['savings_interval'] == "daily") ? $fetch_sSub['duration']." Day(s)" : (($fetch_sSub['savings_interval'] == "weekly") ? $fetch_sSub['duration']." Week(s)" : (($fetch_sSub['savings_interval'] == "monthly") ? $fetch_sSub['duration']." Month(s)" : (($fetch_sSub['savings_interval'] == "yearly") ? $fetch_sSub['duration']." Year(s)" : $fetch_sSub['savings_interval'])))).'</b></td>
        </tr>
    </table>
    <span class="rotate" style="font-size: 55px;"><b>'.$cname.'</b></span>
    <div class="just" style="font-size: 13px;">
        In Witness whereof, signed digitally on behalf of <b>'.$cname.'</b> this '.$formatedDate.'
    </div>
    <p>
        Signed:
        <br><b>Managing Director</b>
        <br><b>'.$cname.'</b>
    </p>
    ');

// (Optional) Setup the paper size and orientation 
$dompdf->setPaper('A4', 'portrate'); 
 
// Render the HTML as PDF 
$dompdf->render(); 
 
// Output the generated PDF to Browser 
// (1 = download and 0 = preview)
$dompdf->stream(uniqid()."productCertificate.pdf", array("Attachment" => 0));
?>