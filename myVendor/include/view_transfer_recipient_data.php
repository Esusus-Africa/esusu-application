<div class="row">       
		    <section class="content">  
	        <div class="box box-success">
            <div class="box-body">
              <div class="table-responsive">
             <div class="box-body">
<form method="post">
			 <a href="mywallet.php?id=<?php echo $_SESSION['tid']; ?>&&mid=NDA0"><button type="button" class="btn btn-flat bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>"><i class="fa fa-mail-reply-all"></i>&nbsp;Back</button></a>

			 <button type="submit" class="btn btn-flat bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>" name="delete"><i class="fa fa-times"></i>&nbsp;Multiple Delete</button>
			 
<a href="create_transfer_recipient.php?id=<?php echo $_SESSION['tid']; ?>&&mid=<?php echo base64_encode("404"); ?>"><button type="button" class="btn btn-flat bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>"><i class="fa fa-plus"></i>&nbsp;Add Transfer Recipient</button></a>
	<hr>		
			  
			 <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th><input type="checkbox" id="select_all"/></th>
                  <th>Recipient Name</th>
				  <th>Account Number</th>
				  <th>Bank Code</th>
                  <th>Bank Name</th>
				  <th>Date/Time</th>
                 </tr>
                </thead>
                <tbody> 
<?php
$date_now = date("Y-m-d");
$select = mysqli_query($link, "SELECT * FROM transfer_recipient WHERE companyid = '$vendorid' ORDER BY id") or die (mysqli_error($link));
if(mysqli_num_rows($select)==0)
{
echo "<div class='alert bg-blue'>No data found yet!.....Check back later!!</div>";
}
else{
while($row = mysqli_fetch_array($select))
{
$id = $row['id'];
$recipient_code = $row['recipient_code'];
?> 
                <tr>
				<td><input id="optionsCheckbox" class="checkbox" name="selector[]" type="checkbox" value="<?php echo $row['id']; ?>"></td>
				<td><b><?php echo $row['full_name']; ?></b></td>
                <td><?php echo $row['acct_no']; ?></td>
				<td><?php echo $row['bank_code']; ?></td>
				<td><?php echo $row['bank_name']; ?></td>
				<td><?php echo $row['date_time']; ?></td>
			    </tr>
<?php } } ?>
             </tbody>
                </table>  
				
						<?php
						if(isset($_POST['delete'])){

							include("../config/restful_apicalls.php");

							$idm = $_GET['id'];
							$id=$_POST['selector'];
							$N = count($id);
						if($id == ''){
						echo "<script>alert('Row Not Selected!!!'); </script>";	
						echo "<script>window.location='view_transfer_recipient.php?id=".$_SESSION['tid']."&&mid=NDA0'; </script>";
							}
							else{
							for($i=0; $i < $N; $i++)
							{
								$systemset = mysqli_query($link, "SELECT * FROM systemset");
								$row1 = mysqli_fetch_object($systemset);
								$seckey = $row1->secret_key;

								$result = array();
								$search_restapi = mysqli_query($link, "SELECT * FROM restful_apisetup WHERE api_name = 'delete_beneficiary'");
								$fetch_restapi = mysqli_fetch_object($search_restapi);
								$api_url = $fetch_restapi->api_url;

								// Pass the parameter here
								$postdata =  array(
									"id"	=> $id[$i],
									"seckey"=> $seckey
								);

								$make_call = callAPI('POST', $api_url, json_encode($postdata));
								$result = json_decode($make_call, true);

									if($result['status'] == "success"){

									$result = mysqli_query($link,"DELETE FROM transfer_recipient WHERE id ='$id[$i]'");
									echo "<script>alert('Row Delete Successfully!!!'); </script>";
									echo "<script>window.location='view_transfer_recipient.php?id=".$_SESSION['tid']."&&mid=NDA0'; </script>";
									}
									else{
										$message = $result['message'];
										echo "<script>alert('$message'); </script>";
										echo "<script>window.location='view_transfer_recipient.php?id=".$_SESSION['tid']."&&mid=NDA0'; </script>";
									}
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