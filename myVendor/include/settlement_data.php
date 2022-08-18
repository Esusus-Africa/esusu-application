<div class="row">
		
	       
		    <section class="content">  
	        <div class="box box-success">
            <div class="box-body">
              <div class="table-responsive">
             <div class="box-body">
<form method="post">
    	
	<hr>	

			 <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                <th><input type="checkbox" id="select_all"/></th>
				  <th>Status</th>
                  <th>Reference</th>
                  <th>Request Amount</th>
                  <th>Destination Channel</th>
                  <th>A/c Details</th>
                  <th>DateTime</th>
                 </tr>
                </thead>
                <tbody>
<?php
$select = mysqli_query($link, "SELECT * FROM manual_investsettlement WHERE vendorid = '$vendorid' ORDER BY id DESC") or die (mysqli_error($link));
if(mysqli_num_rows($select)==0)
{
echo "<div class='alert bg-".(($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color'])."'>No data found yet!...Check back later!!</div>";
}
else{
while($row = mysqli_fetch_array($select))
{
$id = $row['id'];
?>    
                <tr>
                    <td><input id="optionsCheckbox" class="checkbox"  name="selector[]" type="checkbox" value="<?php echo $id; ?>"></td>
                    <td><?php echo ($row['status'] == "Settled" ? '<span class="label bg-blue">Settled</span>' : ($row['status'] == "Declined" ? '<span class="label bg-red">Declined</span>' : '<span class="label bg-orange">Pending</span>')); ?></td>
                    <td><?php echo $row['refid']; ?></td>
                    <td><?php echo $row['currency'].number_format($row['amount'],2,'.',','); ?></td>
                    <td><?php echo $row['destinationChannel']; ?></td>
                    <td><?php echo $row['details']; ?></td>
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