<div class="box">
             
	
	      <div class="box-body">
			<div class="panel panel-success">
            <div class="panel-heading">
            <h3 class="panel-title"><i class="fa fa-dollar"></i> Update Payment</h3>
            </div>
             <div class="box-body">
<?php
$id = $_GET['id'];
$select = mysqli_query($link, "SELECT * FROM payments WHERE id = '$id'") or die (mysqli_error($link));
while($get = mysqli_fetch_array($select))
{
?>
			 <form class="form-horizontal" method="post" enctype="multipart/form-data" action="process_pmt.php?id=<?php echo $id; ?>">

             <div class="box-body">
				
			<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">Loan ID#</label>
                  <div class="col-sm-10">
                  <input name="acte" type="text" class="form-control" value="<?php echo $get['lid']; ?>" required>
                  </div>
                  </div>
				  
			  <div class="form-group">
                <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">Loan</label>
				 <div class="col-sm-10">
                <select class="loan select2" name="loan" style="width: 100%;">
                 <?php
				 $id = $_GET['id'];
				$get = mysqli_query($link, "SELECT * FROM loan_info WHERE borrower = '$id'") or die (mysqli_error($link));
				while($rows = mysqli_fetch_array($get))
				{
				echo '<option value="'.$rows['amount_topay'].'">'."Flexible(".$rows['amount']."-"."&nbsp;"."bal:".$rows['amount_topay'].")".'</option>';
				}
				?>
                </select>
              </div>
			  </div>
<?php
$id = $_GET['id'];
$selected = mysqli_query($link, "SELECT * FROM payments WHERE id = '$id'") or die (mysqli_error($link));
while($getin = mysqli_fetch_array($selected))
{
?>			  
			  <div class="form-group">
                <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">Payment Date</label>
			 <div class="col-sm-10">
              <div class="input-group date">
                  <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                  </div>
                  <input type="text" class="form-control" value="<?php echo $getin['pay_date']; ?>" name="pay_date">
                </div>
              </div>
			  </div>
				  
		<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">Amount to Pay</label>
                  <div class="col-sm-10">
                  <input name="amount_to_pay" type="text" class="form-control" value="<?php echo $getin['amount_to_pay']; ?>" required>
                  </div>
                  </div>
				  
				   <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">Balance</label>
                  <div class="col-sm-10">
                  <input name="balance" type="text" class="form-control" value="<?php echo $getin['bal']; ?>" readonly>
                  </div>
                  </div>
				  
		<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">Teller By</label>
                  <div class="col-sm-10">
<?php
$id = $_GET['id'];
$selecte = mysqli_query($link, "SELECT * FROM user WHERE id = '".$_SESSION['tid']."'") or die (mysqli_error($link));
while($gete = mysqli_fetch_array($selecte))
{
?>
                  <input name="teller" type="text" class="form-control" value="<?php echo $gete['name']; ?>" readonly>
<?php } ?>
                  </div>
                  </div>
				  
				  
		 <div class="form-group">
                  	<label for="" class="col-sm-2 control-label" style="color:#009900">Remarks</label>
                  	<div class="col-sm-10">
					<textarea name="remarks"  class="form-control" rows="4" cols="80"><?php echo $getin['remarks']; ?></textarea>
           			 </div>
          </div>

			 </div>
			 
			  <div align="right">
              <div class="box-footer">
                				<button name="updatep" type="submit" class="btn bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?> btn-flat"><i class="fa fa-edit">&nbsp;Update Payment</i></button>

              </div>
			  </div>
			  </form>
			  
<?php } } ?>
           
</div>	
</div>
</div>
</div>