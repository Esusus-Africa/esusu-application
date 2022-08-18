<div class="row">       
		    <section class="content">  
	        <div class="box box-success">
            <div class="box-body">
              <div class="table-responsive">
             <div class="box-body">
<form method="post">

			 <?php echo ($delete_msavings_plan == 1) ? '<button type="submit" class="btn btn-flat bg-'.(($myaltrow['alternate_color'] == "") ? "orange" : $myaltrow['alternate_color']).'" name="delete"><i class="fa fa-times"></i>&nbsp;Multiple Delete</button>' : ''; ?>
			 
<a href="addsavingpln.php?id=<?php echo $_SESSION['tid']; ?>&&mid=<?php echo base64_encode("411"); ?>"><button type="button" class="btn btn-flat bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>"><i class="fa fa-plus"></i>&nbsp;Create Product Plan</button></a>
	<hr>		
			  
			 <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th><input type="checkbox" id="select_all"/></th>
				  <th>Plan</th>
				  <th>Category</th>
				  <th>Amount Range</th>
				  <th>Duration</th>
                  <th>Dividend</th>
				  <th>Maturity Period</th>
				  <th>Part Withdrawal <p style="font-size:10px;">(Before Maturity Period)</p></th>
				  <th>Subaccount Share</th>
				  <th>Agent Commission</th>
				  <th>Status</th>
                 </tr>
                </thead>
                <tbody> 
<?php
$id = $_GET['tid'];
$select = mysqli_query($link, "SELECT * FROM savings_plan WHERE merchantid_others = '$institution_id' ORDER BY id DESC") or die (mysqli_error($link));
if(mysqli_num_rows($select)==0)
{
echo "<div class='alert bg-".(($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color'])."'>No data found yet!.....Check back later!!</div>";
}
else{
while($row = mysqli_fetch_array($select))
{
$plid = $row['id'];
$pl_code = $row['plan_code'];
?> 
                <tr>
				<td><input id="optionsCheckbox" class="checkbox" name="selector[]" type="checkbox" value="<?php echo $row['plan_id']; ?>"></td>
                <td><b><?php echo $row['plan_name']; ?></b><br>
                	<a href="edit_savingspln.php?id=<?php echo $_SESSION['tid']; ?>&&plid=<?php echo $plid; ?>&&mid=NDEx" class="btn bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>"><b>Update Plan!</b></a>
                </td>
                <td><?php echo $row['categories']; ?></td>
				<td><?php echo $row['min_amount'].' - '.$row['max_amount']; ?></td>
				<td><?php echo $row['duration']; ?> <?php echo (($row['savings_interval'] === "daily" ? "day(s)" : ($row['savings_interval'] === "weekly" ? "week(s)" : ($row['savings_interval'] === "yearly" ? "year(s)" : ($row['savings_interval'] === "monthly" ? "month(s)" : "day"))))); ?></td>
				<td><?php echo ($row['dividend_type'] == "Flat Rate" ? $row['dividend'].' Flat' : ($row['dividend_type'] == "Ratio" ? '---' : $row['dividend'].'%')); ?></td>
				<td><?php echo ($row['lock_withdrawal'] === "Yes" ? $row['frequency'].(($row['maturity_period'] == "annually" ? ' Year(s)' : ($row['maturity_period'] == "monthly" ? ' Month(s)' : ' Week(s)'))) : ($row['lock_withdrawal'] === "Lock" ? 'Lock' : 'No')); ?></td>
				<td><?php echo ($row['part_withdrawal'] === "Yes") ? $row['no_of_times'].' time(s)' : 'No'; ?></td>
				<td><?php echo ($row['commtype'] == "flat") ? $row['currency'].number_format($row['commvalue'],2,'.',',').' Flat' : $row['commvalue'].'%'; ?></td>
				<td><?php echo $row['agentcomm']; ?>%</td>
				<td><?php echo ($row['status'] == "Active" ? '<span class="label bg-blue">Active</span>' : ($row['status'] == "Pending" ? '<span class="label bg-orange">Pending</span>' : '<span class="label bg-orange">Rejected</span>')); ?></td>
			    </tr>
<?php } } ?>
             </tbody>
                </table>
			
<?php
						if(isset($_POST['delete'])){
						    
						    include ("../config/restful_apicalls.php");
						    $response = array();
    						$idm = $_GET['id'];
    					    $id=$_POST['selector'];
    						$N = count($id);
    						
    						$systemset = mysqli_query($link, "SELECT * FROM systemset");
                        	$row1 = mysqli_fetch_object($systemset);
    						
    						if($id == ''){
    						    echo "<script>alert('Row Not Selected!!!'); </script>";	
    						    echo "<script>window.location='create_msavingsplan.php?id=".$_SESSION['tid']."&&mid=NDEx'; </script>";
    						}
							else{
							    for($i=0; $i < $N; $i++)
							    {
                                    $search_restapi = mysqli_query($link, "SELECT * FROM restful_apisetup WHERE api_name = 'cancel_paymentplans'");
                                    $fetch_restapi = mysqli_fetch_object($search_restapi);
                                	$api_url = $fetch_restapi->api_url;
                                    $api_link = $api_url.$id[$i].'/cancel';
                                    
                                	// Pass the plan's name, seckey
                                	$postdata =  array(
                                		'id'=> $id[$i],
                                		'seckey'	=> $row1->secret_key
                                	);
                                	
                                	$make_call = callAPI('POST', $api_link, json_encode($postdata));
                                	$response = json_decode($make_call, true);
                                	
                                	if($response['status'] == "success"){
                                	    
                                	    $result = mysqli_query($link,"DELETE FROM savings_plan WHERE plan_id ='$id[$i]'");
                                	    echo "<script>alert('Row Delete Successfully!!!'); </script>";
        								echo "<script>window.location='create_msavingsplan.php?id=".$_SESSION['tid']."&&mid=NDEx'; </script>";

                                	}
                                	else{
                                	    $result = mysqli_query($link,"DELETE FROM savings_plan WHERE plan_id ='$id[$i]'");
                                	    echo "<script>alert('Row Delete Successfully!!!'); </script>";
        								echo "<script>window.location='create_msavingsplan.php?id=".$_SESSION['tid']."&&mid=NDEx'; </script>";
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