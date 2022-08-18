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
                  <th>RefID</th>
                  <th>Unique ID <p style="font-size: 12px;" align="center"> (Subcription Token)</p></th>
          <th>Amount Paid <p style="font-size: 12px;"> (Total)</p></th>
          <th>Expired Date</th>
          <th>Trans. Date</th>
          <th>Status</th>
                 </tr>
                </thead>
                <tbody> 
<?php
$select = mysqli_query($link, "SELECT * FROM reg_transaction WHERE acn = '$acnt_id' ORDER BY id DESC") or die (mysqli_error($link));
if(mysqli_num_rows($select)==0)
{
echo "<div class='alert bg-".$myaltrow['theme_color'].' == "" ? blue : '.$myaltrow['theme_color']."'>No data found yet!.....Check back later!!</div>";
}
else{
while($row = mysqli_fetch_array($select))
{
$id = $row['id'];

$system_settings = mysqli_query($link, "SELECT * FROM systemset");
$fetch_sys_settings = mysqli_fetch_object($system_settings);
?> 
                <tr>
        <td><input id="optionsCheckbox" class="checkbox" name="selector[]" type="checkbox" value="<?php echo $row['id']; ?>"></td>
        <td><?php echo $row['refid']; ?></td>
        <td><b><?php echo $row['sub_token']; ?></b></td>
        <td><?php echo  $fetch_sys_settings->currency.number_format($row['amount_paid'],2,'.',','); ?></td>
        <td><b><?php echo $row['expiry_date']; ?></b></td>
        <td><?php echo $row['date_time']; ?></td>
        <td><?php echo "<label class='label bg-blue'>".$row['status']."</label>"; ?></td>
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