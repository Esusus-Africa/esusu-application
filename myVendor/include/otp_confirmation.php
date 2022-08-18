<div class="box-body">
     
     <div align="center"><img src="../img/otp_image.jpg" height="200" width="300"/></div>
     
         <?php
         if(isset($_GET['token'])){
             $token = base64_decode($_GET['token']);
             $searchToken = mysqli_query($link, "SELECT * FROM otp_confirmation WHERE data = '$token'");
             $fetchToken = mysqli_fetch_array($searchToken);
             $concat = $fetchToken['data'];
             $datetime = $fetchToken['datetime'];
             $parameter = (explode('|',$concat));
 
             $account_number = $parameter[2];
             $b_name = $parameter[3];
             $amountWithNoCharges = $parameter[4];
             $amountWithCharges = $parameter[5];
             $calcCharges = $amountWithCharges - $amountWithNoCharges;
 
             echo '<div align="center"><table align="center" width="48%" cellpadding="5" cellspacing="0" style="border-color:black;" border="1">
                     <tr>
                         <th colspan="2"><p align="center">CONFIRM TRANSACTION DETAILS</p></th>
                     </tr>
                     <tr>
                         <td align="left">
                             <p>Recipient Name: </p>
                         </td>
                         <td align="left">
                             <p>'.$b_name.'</p>
                         </td>
                     </tr>
                     <tr>
                         <td align="left">
                             <p>Account Number: </p>
                         </td>
                         <td align="left">
                             <p>'.$account_number.'</p>
                         </td>
                     </tr>
                     <tr>
                         <td align="left">
                             <p>Amount to Send: </p>
                         </td>
                         <td align="left">
                             <p>'.number_format($amountWithNoCharges,2,'.',',').'</p>
                         </td>
                     </tr>
                     <tr>
                         <td align="left">
                             <p>Service Charge: </p>
                         </td>
                         <td align="left">
                             <p>'.$calcCharges.'</p>
                         </td>
                     </tr>
                  </table></div><br>';
         }
         if(isset($_GET['key'])){
             $token = base64_decode($_GET['key']);
             $searchToken = mysqli_query($link, "SELECT * FROM otp_confirmation WHERE data = '$token'");
             $fetchToken = mysqli_fetch_array($searchToken);
             $concat = $fetchToken['data'];
             $datetime = $fetchToken['datetime'];
             $parameter = (explode('|',$concat));
 
             $account_number = $parameter[1];
             $amountWithNoCharges = $parameter[2];
             $b_name = $parameter[8];
 
             echo '<div align="center"><table align="center" width="48%" cellpadding="5" cellspacing="0" style="border-color:black;" border="1">
                     <tr>
                         <th colspan="2"><p align="center">CONFIRM TRANSACTION DETAILS</p></th>
                     </tr>
                     <tr>
                         <td align="left">
                             <p>Recipient Name: </p>
                         </td>
                         <td align="left">
                             <p>'.$b_name.'</p>
                         </td>
                     </tr>
                     <tr>
                         <td align="left">
                             <p>Account Number: </p>
                         </td>
                         <td align="left">
                             <p>'.$account_number.'</p>
                         </td>
                     </tr>
                     <tr>
                         <td align="left">
                             <p>Amount to Send: </p>
                         </td>
                         <td align="left">
                             <p>'.number_format($amountWithNoCharges,2,'.',',').'</p>
                         </td>
                     </tr>
                     <tr>
                         <td align="left">
                             <p>Service Charge: </p>
                         </td>
                         <td align="left">
                             <p>Free</p>
                         </td>
                     </tr>
                  </table></div><br>';
         }
         ?>
     
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