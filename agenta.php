<div class="row">
    
		    <section class="content">  
	        <div class="box box-success">
            <div class="box-body">
              <div class="table-responsive">
             <div class="box-body">
<form method="post">
<?php
$check_till = mysqli_query($link, "SELECT * FROM till_account WHERE cashier = '$auid'") or die ("Error" . mysqli_error($link));
if(mysqli_num_rows($check_till) == 1)
{
  ?>
			 <a href="view_savings.php?id=<?php echo $_SESSION['tid']; ?>&&idm='<?php echo $auid; ?>'&&mid=<?php echo base64_encode("510"); ?>"><button type="button" class="btn btn-flat bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>"><i class="fa fa-mail-reply-all"></i>&nbsp;Back</button></a> 
<?php
}
else{
  ?>

      <a href="view_teller.php?id=<?php echo $_SESSION['tid']; ?>&&mid=<?php echo base64_encode("510"); ?>"><button type="button" class="btn btn-flat bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>"><i class="fa fa-mail-reply-all"></i>&nbsp;Back</button></a> 

<?php
}
?>
	<?php echo ($settle_fund == '1') ? '<a href="settle_fund.php?id='.$_SESSION['tid'].'&&idm='.$_GET['idm'].'&&mid=NTEw" class="btn btn-flat bg-'.$myaltrow['theme_color'].' == "" ? blue : '.$myaltrow['theme_color'].'" target="_blank"><i class="fa fa-minus"></i>&nbsp;Settle Fund</a>' : ''; ?>		
	<hr>		
			  
			 <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th><input type="checkbox" id="select_all"/></th>
                  <th>Branch</th>
				          <th>Teller</th>
                  <th>Cashier</th>
                  <th>Amount Settled</th>
                  <th>Balance</th>
                  <th>Note/Comment</th>
                  <th>Date/Time</th>
                 </tr>
                </thead>
                <tbody>
<?php
$tid = $_SESSION['tid'];
$idm = $_GET['idm'];
$select = mysqli_query($link, "SELECT * FROM fund_settlement WHERE companyid = '$agentid' ORDER BY id DESC") or die (mysqli_error($link));
if(mysqli_num_rows($select)==0)
{
echo "<div class='alert bg-".$myaltrow['theme_color'].' == "" ? blue : '.$myaltrow['theme_color']."'>No data found yet!.....Check back later!!</div>";
}
else{
while($row = mysqli_fetch_array($select))
{
$id = $row['id'];
$mbranchid = $row['branch'];
$cashier_id = $row['cashier']; 

$search_branch = mysqli_query($link, "SELECT * FROM branches WHERE branchid = '$mbranchid'");
$fetch_branch = mysqli_fetch_array($search_branch);
$bname = $fetch_branch['bname'];

$search_cashier = mysqli_query($link, "SELECT * FROM user WHERE id = '$cashier_id'");
$fetch_cashier = mysqli_fetch_array($search_cashier);
$cashier = $fetch_cashier['name'];
?>    
          <tr>
        <td><input id="optionsCheckbox" class="checkbox" name="selector[]" type="checkbox" value="<?php echo $id; ?>"></td>
				<td><?php echo ($row['bname'] == "") ? 'Head Office' : $row['bname']; ?></td>
				<td><?php echo $row['teller']; ?></td>
				<td><?php echo $cashier; ?></td>
				<td><?php echo $currency.number_format($row['amount'],2,".",","); ?></td>
				<td><?php echo $currency.number_format($row['balance'],2,".",","); ?></td>
				<td><?php echo $row['note']; ?></td>
				<td><?php echo $row['date_time']; ?></td>
			    </tr>
<?php } } ?>
             </tbody>
                </table>  
					
				
</form>
                </div>

				</div>	
				</div>
			
</div>		
       
</div>