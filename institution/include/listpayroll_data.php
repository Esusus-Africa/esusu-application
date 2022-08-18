<div class="row">
    
		    <section class="content">  
	        <div class="box box-success">
            <div class="box-body">
              <div class="table-responsive">
             <div class="box-body">
<form method="post">
	<a href="dashboard.php?id=<?php echo $_SESSION['tid']; ?>&&mid=<?php echo base64_encode("401"); ?>"><button type="button" class="btn btn-flat bg-orange"><i class="fa fa-mail-reply-all"></i>&nbsp;Back</button> </a> 
	<?php echo ($delete_payroll == '1') ? '<button type="submit" class="btn btn-flat bg-orange" name="delete"><i class="fa fa-times"></i>&nbsp;Delete</button>' : ''; ?>	
	
	<hr>		
			  
			 <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th><input type="checkbox" id="select_all"/></th>
                  <th>Staff</th>
                  <th>Pay Date</th>
				  <th>Gross Amount</th>
                  <th>Deduction Amount</th>
                  <th>Paid/Net Amount</th>
				  <th>Payslip</th>
                  <th>View/Modify</th>
                 </tr>
                </thead>
                <tbody>
<?php
$select = mysqli_query($link, "SELECT * FROM payroll WHERE companyid = '$institution_id'") or die (mysqli_error($link));
while($row = mysqli_fetch_array($select))
{
$id = $row['id'];
$staffid = $row['staff_id'];

$search_user = mysqli_query($link, "SELECT * FROM user WHERE userid = '$staffid'") or die (mysqli_error($link));
$get_u = mysqli_fetch_array($search_user);
$name = $get_u['name'].' '.$get_u['fname'];
?>    
                <tr>
                <td><input id="optionsCheckbox" class="checkbox" name="selector[]" type="checkbox" value="<?php echo $id; ?>"></td>
				<td><?php echo $name; ?></td>
				<td><?php echo $row['pay_date']; ?></td>
				<td><?php echo $icurrency.number_format($row['gross_amount'],2,".",","); ?></td>
				<td><?php echo $icurrency.number_format($row['total_deduction'],2,".",","); ?></td>
				<td><b><?php echo $icurrency.number_format($row['paid_amount'],2,".",","); ?></b></td>
				<td><?php echo ($generate_payslip == 1) ? '<a href="generate_payslip.php?id='.$id.'" target="_blank"><span class="label bg-blue"> Generate Payslip </span></a>' : '-----'; ?></td>
				<td><?php echo ($update_payroll == '1') ? '<a href="edit_payroll.php?id='.$_SESSION['tid'].'&&staff_id='.$staffid.'&&mid=NDIz" class="label bg-orange"> <i class="fa fa-eye"></i> View/Modify </a>' : ''; ?>  </td>
			    </tr>
<?php } ?>
             </tbody>
                </table>  
			
<?php
						if(isset($_POST['delete'])){
						$idm = $_GET['id'];
							$id=$_POST['selector'];
							$N = count($id);
						if($id == ''){
						echo "<script>alert('Row Not Selected!!!'); </script>";	
						echo "<script>window.location='listpayroll.php?id=".$_SESSION['tid']."&&mid=NDIz'; </script>";
							}
							else{
							for($i=0; $i < $N; $i++)
							{
								$result = mysqli_query($link,"DELETE FROM payroll WHERE id ='$id[$i]'");
								echo "<script>alert('Row Delete Successfully!!!'); </script>";
								echo "<script>window.location='listpayroll.php?id=".$_SESSION['tid']."&&mid=NDIz'; </script>";
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