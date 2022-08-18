<div class="box">
	       <div class="box-body">
			<div class="panel panel-success">
            <div class="panel-heading bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">
            <h3 class="panel-title"> <i class="fa fa-unlock"></i> Account Verification</h3>
            </div>

             <div class="box-body">
 <?php
if(isset($_POST['save']))
{
	
}
?>           
			 <form class="form-horizontal" method="post" enctype="multipart/form-data">
       
<div class="alert bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>">
<b>Sorry for bothering you!</b> For better account functionality, kindly verify your virtual account with the <b>OTP</b> sent to your registered phone number
</div>
<hr>
             <div class="box-body">
			
			<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Otp Code</label>
                  <div class="col-sm-10">
                  <input name="otpCode" type="number" class="form-control" autocomplete="off" placeholder="Enter the otp code send to your registered phone number" /required>
                  </div>
                  </div>
                  
            <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">New tPin</label>
                  <div class="col-sm-10">
                  <input name="tpin" type="text" class="form-control" autocomplete="off" placeholder="Set Transaction Pin" /required>
                  <span style="color: <?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>">For security wise, you're required to set New Transaction Pin</span>
                  </div>
                  </div>
                          
            <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Confirm tPin</label>
                  <div class="col-sm-10">
                  <input name="ctpin" type="text" class="form-control" autocomplete="off" placeholder="Confirm Transaction Pin" /required>
                  </div>
                  </div>
			
			 </div>
			 
			  <div align="right">
              <div class="box-footer">
                	<button name="save" type="submit" class="btn bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>"><i class="fa fa-forward">&nbsp;Confirm</i></button>
              </div>
			  </div>
			  
			 </form> 

</div>	
</div>	
</div>
</div>