<div class="modal fade" id="myModal1" role="dialog">
    <div class="modal-dialog" id="printableArea">
          <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
<legend style="color: blue;"></legend>
        </div>
        <div class="modal-body">
<?php
$search_aNGN = mysqli_query($link, "SELECT SUM(wallet_balance) FROM agent_data");
$get_a = mysqli_fetch_array($search_aNGN);
$a_wb = $get_a['SUM(wallet_balance)'];

$search_iNGN = mysqli_query($link, "SELECT SUM(wallet_balance) FROM institution_data");
$get_i = mysqli_fetch_array($search_iNGN);
$i_wb = $get_i['SUM(wallet_balance)'];

$search_mNGN = mysqli_query($link, "SELECT SUM(wallet_balance) FROM merchant_reg");
$get_m = mysqli_fetch_array($search_iNGN);
$m_wb = $get_i['SUM(wallet_balance)'];

$search_cNGN = mysqli_query($link, "SELECT SUM(wallet_balance) FROM cooperatives WHERE coopid = '$comid_NGN'");
$get_c = mysqli_fetch_array($search_cNGN);
$c_wb = $get_c['SUM(wallet_balance)'];


$search_boNGN = mysqli_query($link, "SELECT SUM(wallet_balance) FROM borrowers");
$get_bo = mysqli_fetch_array($search_boNGN);
$bo_wb = $get_bo['SUM(wallet_balance)'];
?>	
			<div align="center" style="color: orange; font-size: 18px;"><h4><strong>ALL WALLET BALANCE</strong></h4></div>
			<hr>
			
			<table id="example1" class="table table-bordered table-striped">
				<tr>
				<td width="130">NGN: </td>
				<th style="color: blue;"><?php echo $total_NGN; ?></th>
				</tr>
                <tr>
				<td width="130">USD: </td>
				<th style="color: blue;"><?php echo $totalGBP; ?></th>
				</tr>
				<tr>
				<td width="130">GHS: </td>
				<th style="color: blue;"><?php echo $totalGHS; ?></th>
				</tr>
			</table>
			
        </div>
      </div>    
    </div>
</div>