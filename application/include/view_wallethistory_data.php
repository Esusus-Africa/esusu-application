<div class="row">
		
	       
		    <section class="content">  
	        <div class="box box-success">
            <div class="box-body">
              <div class="table-responsive">
             <div class="box-body">
<form>
	<a href="mywallet.php?tid=<?php echo $_SESSION['tid']; ?>&&mid=NDA0"><button type="button" class="btn bg-orange"><i class="fa fa-mail-reply-all"></i>&nbsp;Back</button></a>

	<hr>				  
			 <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th><input type="checkbox" id="select_all"/></th>
                  <th>UserID</th>
        		  <th>RefID</th>
        		  <th>Recipient</th>
        		  <th>Amount</th>
        		  <th>Payment Type</th>
                  <th>Details</th>
        		  <th>Status</th>
        		  <th>Date/Time</th>
                </tr>
                </thead>
                <tbody>
<?php
// Function to check string starting 
// with given substring 
function startsWith ($string, $startString) 
{ 
    $len = strlen($startString); 
    return (substr($string, 0, $len) === $startString); 
}

 $search_w = mysqli_query($link, "SELECT * FROM wallet_history ORDER BY id DESC LIMIT 5000");
 while($row_w = mysqli_fetch_array($search_w))
 {
    $userid = $row_w['userid'];
    $recipient = $row_w['recipient'];
     
    $search_a = mysqli_query($link, "SELECT * FROM agent_data WHERE agentid = '$userid' OR phone = '$userid'");
    $get_a = mysqli_fetch_array($search_a);
    $agent_name = $get_a['bname'];
    
    $search_a1 = mysqli_query($link, "SELECT * FROM agent_data WHERE agentid = '$recipient' OR phone = '$recipient'");
    $get_a1 = mysqli_fetch_array($search_a1);
    $agent_name1 = $get_a1['bname'];

    $search_i = mysqli_query($link, "SELECT * FROM institution_data WHERE institution_id = '$userid' OR official_phone = '$userid'");
    $get_i = mysqli_fetch_array($search_i);
    $i_name = $get_i['institution_name'];
    
    $search_i1 = mysqli_query($link, "SELECT * FROM institution_data WHERE institution_id = '$recipient' OR official_phone = '$recipient'");
    $get_i1 = mysqli_fetch_array($search_i1);
    $i_name1 = $get_i1['institution_name'];

    $search_m = mysqli_query($link, "SELECT * FROM merchant_reg WHERE merchantID = '$userid' OR official_phone = '$userid'");
    $get_m = mysqli_fetch_array($search_m);
    $m_name = $get_m['company_name'];
    
    $search_m1 = mysqli_query($link, "SELECT * FROM merchant_reg WHERE merchantID = '$recipient' OR official_phone = '$recipient'");
    $get_m1 = mysqli_fetch_array($search_m1);
    $m_name1 = $get_m1['company_name'];

    $search_c = mysqli_query($link, "SELECT * FROM cooperatives WHERE coopid = '$userid' OR official_phone = '$userid'");
    $get_c = mysqli_fetch_array($search_c);
    $c_name = $get_c['coopname'];
    
    $search_c1 = mysqli_query($link, "SELECT * FROM cooperatives WHERE coopid = '$recipient' OR official_phone = '$recipient'");
    $get_c1 = mysqli_fetch_array($search_c1);
    $c_name1 = $get_c1['coopname'];

    $search_bo = mysqli_query($link, "SELECT * FROM borrowers WHERE account = '$userid' OR phone = '$userid'");
    $get_bo = mysqli_fetch_array($search_bo);
    $bo_name = $get_bo['lname'].' '.$get_bo['fname'];
    
    $search_bo1 = mysqli_query($link, "SELECT * FROM borrowers WHERE account = '$recipient' OR phone = '$recipient'");
    $get_bo1 = mysqli_fetch_array($search_bo1);
    $bo_name1 = $get_bo1['lname'].' '.$get_bo1['fname'];
    
    $sender = (startsWith($userid,"AGT") ? $agent_name : (startsWith($userid,"INST") ? $i_name : (startsWith($userid,"MER") ? $m_name : (startsWith($userid,"COOP") ? $c_name : $bo_name))));
    
    $recipient_name = (startsWith($recipient,"AGT") ? $agent_name1 : (startsWith($recipient,"INST") ? $i_name1 : (startsWith($recipient,"MER") ? $m_name1 : (startsWith($recipient,"COOP") ? $c_name1 : $bo_name1))));
    
    $date_time = $row_w['date_time'];
    $utc_date = DateTime::createFromFormat('Y-m-d G:i:s',$date_time,new DateTimeZone(date_default_timezone_get()));
    $acst_date = clone $utc_date; // we don't want PHP's default pass object by reference here
    $acst_date->setTimeZone(new DateTimeZone('GMT+1'));
    $correctdate = $acst_date->format('Y-m-d g:i A');
?>    
                <tr>
				<td><input id="optionsCheckbox" class="checkbox"  name="selector[]" type="checkbox" value="<?php echo $row_w['id']; ?>"></td>
				<td><?php echo ($row_w['userid'] == "") ? "Esusu Africa Superadmin" : "<b>".$sender."</b> (".$row_w['userid'].")"; ?></td>
                <td><a href="../pdf/view/pdf_billsreceipt.php?refid=<?php echo $row_w['refid']; ?>&&instid=<?php echo ($row_w['userid'] == "") ? "" : $row_w['userid']; ?>" target="_blank"><?php echo $row_w['refid']; ?></a></td>
                <td><?php echo ($row_w['recipient'] == "") ? "Esusu Africa Superadmin" : "<b>".$recipient_name."</b> (".$row_w['recipient'].")"; ?></td>
                <td><b><?php echo $row_w['currency']. number_format($row_w['amount'],2,'.',','); ?></b></td>
        		<td><?php echo $row_w['paymenttype']; ?></td>
                <td><?php echo $row_w['card_bank_details']; ?></td>
        		<td><?php echo ($row_w['status'] == "successful") ? '<span class="label bg-blue">success</span>' : '<span class="label bg-orange">failed</span>'; ?></td>
                <td><?php echo $correctdate; ?></td>
				</tr>
<?php 
}
?>

             </tbody>
            </table>   
						
</form>			

              </div>
	
</div>	
</div>
</div>	
</div>