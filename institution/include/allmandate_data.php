<div class="row">       
		    <section class="content">  
	        <div class="box box-success">
            <div class="box-body">
              <div class="table-responsive">
             <div class="box-body">
<form method="post">
			 <a href="dashboard.php?id=<?php echo $_SESSION['tid']; ?>&&mid=<?php echo base64_encode("401"); ?>"><button type="button" class="btn bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>"><i class="fa fa-mail-reply-all"></i>&nbsp;Back</button> </a> 

<a href="allmandate.php?id=<?php echo $_SESSION['tid']; ?>&&mid=<?php echo base64_encode("405"); ?>"><button type="button" class="btn btn-flat bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>"><i class="fa fa-refresh"></i>&nbsp;Refresh</button></a>

	<hr>		
			  
			 <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th><input type="checkbox" id="select_all"/></th>
                  <th>Loan ID</th>
                  <th>RRR Number</th>
				  <th>Request ID</th>
				  <th>Account Owner</th>
                  <th>Principal Amount + Interest</th>
                  <th>Loan Balance</th>
                  <th>Mandate Status</th>
                 </tr>
                </thead>
                <tbody> 
<?php
($view_all_loans === "1" && $individual_loan_records === "" && $branch_loan_records === "") ? $select = mysqli_query($link, "SELECT * FROM loan_info WHERE branchid = '$institution_id' AND mandate_id != '' ORDER BY id DESC") or die (mysqli_error($link)) : "";
($view_all_loans === "" && $individual_loan_records === "1" && $branch_loan_records === "") ? $select = mysqli_query($link, "SELECT * FROM loan_info WHERE branchid = '$institution_id' AND sbranchid = '$isbranchid' AND mandate_id != '' ORDER BY id DESC") or die (mysqli_error($link)) : "";
($view_all_loans === "" && $individual_loan_records === "" && $branch_loan_records === "1") ? $select = mysqli_query($link, "SELECT * FROM loan_info WHERE branchid = '$institution_id' AND mandate_id != '' AND (agent = '$iuid' OR agent = '$iname') ORDER BY id DESC") or die (mysqli_error($link)) : "";
if(mysqli_num_rows($select)==0)
{
echo "<div class='alert bg-".(($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color'])."'>No data found yet!.....Check back later!!</div>";
}
else{
while($row = mysqli_fetch_array($select))
{
    $id = $row['id'];
    $borrower = $row['borrower'];
	$selectin = mysqli_query($link, "SELECT * FROM borrowers WHERE id = '$borrower'") or die (mysqli_error($link));
	$geth = mysqli_fetch_array($selectin);
	$name = $geth['lname'].' '.$geth['fname'];
	$myphone = $geth['phone'];
    ?> 
        		<tr>
				<td><input id="optionsCheckbox" class="checkbox" name="selector[]" type="checkbox" value="<?php echo $row['id']; ?>"></td>
                <td><?php echo $row['lid']; ?></td>
                <td><b><?php echo $row['mandate_id']; ?></b></td>
                <td><b><?php echo $row['request_id']; ?></b></td>
				<td align="center"><?php echo $name.'<br>('.$row['baccount'].')'; ?></td>
				<td><b><?php echo $icurrency.number_format($row['amount_topay'],2,'.',','); ?></b></td>
				<td><?php echo $icurrency.number_format($row['balance'],2,'.',','); ?></td>
			    <td><?php echo ($row['mandate_status'] === "Activated" ? '<span class="label bg-blue">Activated</span>' : ($row['mandate_status'] === "InProcess" ? '<span class="label bg-orange">InProcess</span>' : ($row['mandate_status'] === "Pending" ? '<span class="label bg-orange">Pending</span>' : '<span class="label bg-red">Stop</span>'))); ?></td>
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