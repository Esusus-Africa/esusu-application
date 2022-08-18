<div class="row">
		
	       
		    <section class="content">  
	        <div class="box box-success">
            <div class="box-body">
              <div class="table-responsive">
             <div class="box-body">
<form method="post">
	<a href="dashboard.php?id=<?php echo $_SESSION['tid']; ?>&&mid=<?php echo base64_encode("401"); ?>"><button type="button" class="btn bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>"><i class="fa fa-mail-reply-all"></i>&nbsp;Back</button> </a> 
	
	<hr>				  
			 <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th><input type="checkbox" id="select_all"/></th>
                  <th>CardPan</th>
                  <th>Name on card</th>
                  <th>Card Type</th>
                  <th>Exp. Date</th>
                  <th>Billing Phone</th>
                  <th>Billing Address</th>
                  <th>Action</th>
                 </tr>
                </thead>
                <tbody>
<?php
$select = mysqli_query($link, "SELECT * FROM card_enrollment WHERE acctno = '$acctno' ORDER BY id") or die (mysqli_error($link));
if(mysqli_num_rows($select)==0)
{
    echo "<div class='alert bg-".$myaltrow['theme_color'].' == "" ? blue : '.$myaltrow['theme_color']."'>No data found yet!.....Check back later!!</div>";
}
else{
while($row = mysqli_fetch_array($select))
{
$id = $row['id'];
$createdOn = $row['date_time'];

$utc_date = DateTime::createFromFormat('Y-m-d G:i:s',$createdOn,new DateTimeZone(date_default_timezone_get()));
$acst_date = clone $utc_date; // we don't want PHP's default pass object by reference here
$acst_date->setTimeZone(new DateTimeZone('GMT+1'));
$correctdate = $acst_date->format('Y-m-d g:i A');
?>    
                <tr>
				<td><input id="optionsCheckbox" class="checkbox"  name="selector[]" type="checkbox" value="<?php echo $id; ?>"></td>
                <td><?php echo $row['maskedpan']; ?></td>
                <td><?php echo $row['name_on_card']; ?></td>
                <td><?php echo $row['card_type']; ?></td>
                <td><?php echo $row['expiration']; ?></td>
                <td><?php echo $row['billing_phone']; ?></td>
                <td><?php echo $row['billing_addrs']; ?></td>
                <td align="center">
				<div class="btn-group">
                      <div class="btn-group">
                        <button type="button" class="btn bg-blue btn-flat dropdown-toggle" data-toggle="dropdown">
                          <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu">
                          <li><p><a href="create_card.php?id=<?php echo $_SESSION['tid']; ?>&&mid=<?php echo base64_encode("900"); ?>&&aId=<?php echo $row['account_id']; ?>&&tab=tab_2" class="btn btn-default btn-flat"><i class="fa fa-eye">&nbsp;View Card</i></a></p></li>
                          <li><p><a href="create_card.php?id=<?php echo $_SESSION['tid']; ?>&&mid=<?php echo base64_encode("900"); ?>&&aId=<?php echo $row['account_id']; ?>&&tab=tab_3" class="btn btn-default btn-flat"><i class="fa fa-plus">&nbsp;Fund Card</i></a></p></li>
                          <li><p><a href="create_card.php?id=<?php echo $_SESSION['tid']; ?>&&mid=<?php echo base64_encode("900"); ?>&&aId=<?php echo $row['account_id']; ?>&&tab=tab_4" class="btn btn-default btn-flat"><i class="fa fa-line-chart">&nbsp;Card Reports</i></a></p></li>
                          <li><p><a href="terminateCard.php?id=<?php echo $_SESSION['tid']; ?>&&mid=<?php echo base64_encode("900"); ?>&&cid=<?php echo $id; ?>" class="btn btn-default btn-flat"><i class="fa fa-times">&nbsp;Terminate Card</i></a></p></li>
						</ul>
                      </div>
				</div>
				</td>
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