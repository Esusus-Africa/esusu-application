<?php
include ("../config/connect.php");
$PostType = $_GET['PostType'];

if($PostType == 'Admin')
{
?>
 <hr> 
<div class="bg-orange">&nbsp;GUARANTOR INFORMATION</div>
<hr>
          
      <div class="form-group">
        <label for="" class="col-sm-2 control-label" style="color:blue;">Gurantor's Passport</label>
        <div class="col-sm-10">
            <input type='file' name="gimage" onChange="readGURIMG(this);" /required>
             <img id="blah3"  src="../avtar/user2.png" alt="Image Here" height="100" width="100"/>
      </div>
      </div>
      
      <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Relationship</label>
                  <div class="col-sm-10">
                  <input name="grelationship" type="text" class="form-control" placeholder="Relationship" required>
                  </div>
                  </div>
      
      <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Guarantor's Name</label>
                  <div class="col-sm-10">
                  <input name="gname" type="text" class="form-control" required placeholder = "Guarantor's Name" required>
                  </div>
                  </div>
          
          <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Guarantor's Phone Number</label>
                  <div class="col-sm-10">
                  <input name="gphone" type="text" class="form-control" required placeholder = "Guarantor's Mobile Number" required>
          <span style="color: orange;"> <b>Make sure you include country code but do not put spaces, or characters </b>in mobile othermise you won't be able to send SMS to this mobile </span><br>
                  </div>
        </div>
         
         <div class="form-group">
                    <label for="" class="col-sm-2 control-label" style="color:blue;">Guarantor's Address</label>
                    <div class="col-sm-10">
          <textarea name="gaddrs"  class="form-control" rows="4" cols="80" required></textarea>
                 </div>
            </div>

      <div class="form-group">
        <label for="" class="col-sm-2 control-label" style="color:blue;">Upload Valid ID</label>
        <div class="col-sm-10">
            <input type='file' name="gpimage"/>
      </div>
      </div>

<div align="right">
              <div class="box-footer">
                        <button type="reset" class="btn bg-orange btn-flat"><i class="fa fa-times">&nbsp;Reset</i></button>
                        <button name="save" type="submit" class="btn bg-blue btn-flat"><i class="fa fa-plus">&nbsp;Submit</i></button>

              </div>
        </div>

<?php
}
elseif($PostType == 'member'){
?>

<div align="right">
              <div class="box-footer">
                        <button type="reset" class="btn bg-orange btn-flat"><i class="fa fa-times">&nbsp;Reset</i></button>
                        <button name="save" type="submit" class="btn bg-blue btn-flat"><i class="fa fa-plus">&nbsp;Submit</i></button>

              </div>
        </div>

<?php } ?>