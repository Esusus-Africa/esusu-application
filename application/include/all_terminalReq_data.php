<div class="row">
		
	       
		    <section class="content">  
	        <div class="box box-success">
            <div class="box-body">
              <div class="table-responsive">
             <div class="box-body">
<form method="post">
	<a href="dashboard.php?id=<?php echo $_SESSION['tid']; ?>&&mid=<?php echo base64_encode("401"); ?>"><button type="button" class="btn bg-orange"><i class="fa fa-mail-reply-all"></i>&nbsp;Back</button> </a> 
	<?php echo ($backend_approve_terminal_request == '1') ? '<button type="submit" class="btn btn-flat bg-blue" name="assign"><i class="fa fa-check"></i>&nbsp;Approve</button>' : ''; ?>
	<hr>				  
			 <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th><input type="checkbox" id="select_all"/></th>
                  <th>Merchant Name</th>
                  <th>Channel</th>
                  <th>Terminal ID</th>
                  <th>Trace ID</th>
                  <th>Model Code</th>
                  <th>Status</th>
                  <th>DateTime</th>
                  <th>Action</th>
                 </tr>
                </thead>
                <tbody>
<?php
$select = mysqli_query($link, "SELECT * FROM terminal_reg WHERE terminal_status = 'Booked' ORDER BY id DESC") or die (mysqli_error($link));
if(mysqli_num_rows($select)==0)
{
echo "<div class='alert bg-blue'>No data found yet!.....Check back later!!</div>";
}
else{
while($row = mysqli_fetch_array($select))
{
$id = $row['id'];
$reg_date = $row['dateCreated'];
$merchantID = $row['merchant_id'];

$utc_date = DateTime::createFromFormat('Y-m-d G:i:s',$reg_date,new DateTimeZone(date_default_timezone_get()));
$acst_date = clone $utc_date; // we don't want PHP's default pass object by reference here
$acst_date->setTimeZone(new DateTimeZone('GMT+1'));
$correctdate = $acst_date->format('Y-m-d g:i A');
?>    
                <tr>
                <td><input id="optionsCheckbox" class="checkbox"  name="selector[]" type="checkbox" value="<?php echo $id; ?>"></td>
                <td><?php echo $row['merchant_name'].' ('.$row['merchant_id'].')'; ?></td>
                <td><?php echo $row['channel']; ?></td>
                <td><?php echo $row['terminal_id']; ?></td>
                <td><?php echo ($row['trace_id'] == "") ? "None" : $row['trace_id']; ?></td>
                <td><?php echo $row['terminal_model_code']; ?></td>
                <td><?php echo "<span class='label bg-orange'>Booked <i class='fa fa-check'></i></span>"; ?></td>
                <td><?php echo $correctdate; ?></td>
                <td align="center">
                <div class="btn-group">
                    <div class="btn-group">
                        <button type="button" class="btn bg-blue btn-flat dropdown-toggle" data-toggle="dropdown">
                          <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu">
                          <?php echo ($backend_reject_terminal_request == "1") ? '<li><p><a href="withTerminal.php?id='.$_SESSION['tid'].'&&termId='.$row['terminal_id'].'&&mid='.base64_encode("700").'&&tab=tab_1" class="btn btn-default btn-flat"><i class="fa fa-times">&nbsp;<b>Reject Request</b></i></a></p></li>' : '---'; ?>
						            </ul>
                    </div>
                </div>
				</td>
				</tr>
<?php } } ?>
             </tbody>
                </table>  
						<?php
						if(isset($_POST['assign'])){
						$idm = $_GET['id'];
							$id=$_POST['selector'];
							$N = count($id);
						if($id == ''){
						echo "<script>alert('Row Not Selected!!!'); </script>";	
						echo "<script>window.location='all_terminalReq.php?id=".$_SESSION['tid']."&&mid=NzAw'; </script>";
							}
							else{
							for($i=0; $i < $N; $i++)
							{
                $searchTerminal = mysqli_query($link, "SELECT * FROM terminal_reg WHERE id ='$id[$i]'");
                $fetchTerminal = mysqli_fetch_array($searchTerminal);
                $merchant_name = $fetchTerminal['merchant_name'];
                $merchant_email = $fetchTerminal['merchant_email'];
                $merchant_phone_no = $fetchTerminal['merchant_phone_no'];
                $DateTime = date('m/d/Y').' '.(date(h) + 1).':'.date('i A');
                $status = "APPROVED";
                $terminalId = $fetchTerminal['terminal_id'];
                $trace_id = ($fetchTerminal['trace_id'] == "") ? "None" : $fetchTerminal['trace_id'];
                $activationFee = $fetchTerminal['activation_fee'];

                $query = mysqli_query($link, "SELECT * FROM systemset") or die (mysqli_error($link));
                $r = mysqli_fetch_object($query);
                $receiverEmail = $r->email.','.$merchant_email;

								$result = mysqli_query($link,"UPDATE terminal_reg SET terminal_status = 'Assigned', assignedBy = '$uid' WHERE id ='$id[$i]'");
                
                include("../config/terminalRequestNotifier.php");
                echo "<script>alert('Request Approved Successfully!!!'); </script>";
    						echo "<script>window.location='all_terminalReq.php?id=".$_SESSION['tid']."&&mid=NzAw'; </script>";
              }
						}
					}
					?>	
						
</form>
				

              </div>


	
</div>	
</div>
</div>	
</div>