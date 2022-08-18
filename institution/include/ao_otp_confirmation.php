<div class="box-body">
     
     <div align="center"><img src="../img/otp_image.jpg" height="200" width="300"/></div>  
     
     <div class="form-group">
         <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;"><?php echo (isset($_GET['otp'])) ? 'OTP Code' : 'Transaction Pin'; ?></label>
         <div class="col-sm-6">
             <input name="otp" type="password" inputmode="numeric" pattern="[0-9]*" class="form-control" placeholder="<?php echo (isset($_GET['otp'])) ? 'OTP Code' : 'Transaction Pin'; ?>" maxlength="<?php echo (isset($_GET['otp'])) ? 6 : 4; ?>" required>
         </div>
         <label for="" class="col-sm-3 control-label"></label>
     </div>
     
 </div>
 
 <div class="form-group" align="right">
     <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;"></label>
     <div class="col-sm-6">
         <button name="confirm" type="submit" class="btn bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?> btn-flat"><i class="fa fa-unlock">&nbsp;Confirm</i></button>
     </div>
     <label for="" class="col-sm-3 control-label"></label>
 </div>