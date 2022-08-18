<?php
include ("config/connect.php");
$PostType = $_GET['PostType'];

if($PostType == "Partnership")
{
?>

<input name="account" type="hidden" class="form-control" value="<?php echo $account; ?>" id="HideValueFrank"/>

<hr>
<div class="bg-blue" style="font-size: 16px;">&nbsp;<b> UPLOAD ALL OTHER DIRECTORATE EXCLUDING YOU USING THE CSV SAMPLE ATTACHED BELOW </b></div>
<hr>      


             
<div class="box-body">
    
  <div class="form-group">
           <label for="" class="col-sm-4 control-label" style="color: blue;">Import Other Directorate Here:</label>
  <div class="col-sm-8">
        <span class="fa fa-cloud-upload"></span>
           <span class="ks-text">Choose file</span>
           <input type="file" name="file" accept=".csv" class="btn bg-orange" required>
  </div>
  </div>

   <hr>
  <div class="form-group">
            <label for="" class="col-sm-4 control-label" style="color:blue;"></label>
      <div class="col-sm-8" style="font-size: 15px;">
  <p style="color:orange;"><b style="color:blue;">NOTE:</b><br>
    <span style="color:blue;">(1)</span> <i>Kindly download the <a href="sample/institution_user_sample.csv"> <b class="btn bg-blue"><i class="fa fa-download"></i> sample file</b></a> to upload the details once.</i></p>
    <p><span style="color:blue;">(2)</span> <i style="color:orange;">Please note that the <span style="color: blue">mio</span> stand for <span style="color: blue">Mode of Identification</span>. And it has to be either <span style="color: blue">National ID OR International Passport</span></i></p>
    <p><span style="color:blue;">(3)</span> <i style="color:orange;">Also, make sure you enter <span style="color: blue">Valid BVN</span> as it will be verify while uploading the csv file. And failure to validate the bvn will result to <span style="color: blue;"> Uploading not successful.</span></i></p>
  </div>
  </div>
                        
<hr>
<div <div class="bg-blue" style="font-size: 16px;">&nbsp;<b> FILL YOUR OWN INFORMATION HERE SO AS TO UPDATE YOUR RECORDS AS A DIRECTORATE OF THE INSTITUTION.</b></div>
<hr>
  <div class="form-group">
            <label for="" class="col-sm-4 control-label" style="color:blue;">Passport Photograph</label>
      <div class="col-sm-8">
               <input type='file' name="image" class="btn bg-orange" onChange="readURL(this);" required>
               <img id="blah"  src="avtar/user2.png" alt="Logo Here" height="100" width="100"/>
      </div>
      </div>

  <div class="form-group">
                  <label for="" class="col-sm-4 control-label" style="color:blue;">Gender</label>
                  <div class="col-sm-5">
        <select name="gender"  class="form-control select2" required>
                    <option selected='selected'>Select Gender&hellip;</option>
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
        </select>
    </div>
    </div>

  <div class="form-group">
                  <label for="" class="col-sm-4 control-label" style="color:blue;">Email Address</label>
                  <div class="col-sm-5">
                  <input name="demail" type="email" class="form-control" placeholder="Your Email Address" required>
                  </div>
                  </div>

  <div class="form-group">
                  <label for="" class="col-sm-4 control-label" style="color:blue;">Mobile Number</label>
                  <div class="col-sm-5">
                  <input name="mobile_no" type="text" class="form-control" placeholder="Your Personal Phone Number" required>
                  </div>
                  </div>

  <div class="form-group">
                  <label for="" class="col-sm-4 control-label" style="color:blue;">MOI</label>
                  <div class="col-sm-5">
        <select name="moi"  class="form-control select2" required>
                    <option selected='selected'>Select Mode of Identification&hellip;</option>
                    <option value="National ID">National ID</option>
                    <option value="International Passport">International Passport</option>
        </select>
    </div>
    </div>

    <div class="form-group">
                  <label for="" class="col-sm-4 control-label" style="color:blue;">ID Number</label>
                  <div class="col-sm-5">
                  <input name="idnumber" type="text" class="form-control" placeholder="ID Number e.g NIN, etc." required>
                  </div>
                  </div>

    <div class="form-group">
                  <label for="" class="col-sm-4 control-label" style="color:blue;">BVN Number</label>
                  <div class="col-sm-5">
                    <span style="color: orange;"> You need to verify your BVN here</span>
                  <input name="unumber" type="text" class="form-control" id="unumber" onkeydown="loaddata();" placeholder="Director BVN Number" maxlength="11" required>
                  <br>
                  <div id="bvn2"></div><br>
                  </div>
                  </div>

    <div class="form-group">
                  <label for="" class="col-sm-4 control-label" style="color:blue;">Username</label>
                  <div class="col-sm-5">
                  <input name="username" type="text" class="form-control" placeholder="Director's Username" required>
                  </div>
                  </div>

<hr>
<div class="bg-blue">&nbsp;<b> SETTLEMENT ACCOUNT DETAILS / CHARGES </b></div>
<hr>

     <div class="form-group">
                  <label for="" class="col-sm-4 control-label" style="color:blue;">State</label>
                  <div class="col-sm-8">
                  <input name="state" type="text" class="form-control" placeholder="Enter State Where You Operate" required>
                  </div>
                  </div>

    <div class="form-group">
                      <label for="" class="col-sm-4 control-label" style="color:blue;">Country</label>
                      <div class="col-sm-8">
            <select name="country"  class="form-control select2" id="country" onchange="loadbank();" required>
              <option selected="selected">Please Select Country</option>
              <option value="NG">Nigeria</option>
              <option value="GH">Ghana</option>
              <option value="KE">Kenya</option>
              <option value="UG">Uganda </option>
              <option value="TZ">Tanzania</option>
            </select>                 
            </div>
          </div>

     <div class="form-group">
                  <label for="" class="col-sm-4 control-label" style="color:blue;">Bank Code</label>
                  <div class="col-sm-8">
                    <div id="bank_list"></div>
    </div>
    </div>
    
    <div class="form-group">
                  <label for="" class="col-sm-4 control-label" style="color:blue;">Account Number</label>
                  <div class="col-sm-8">
                  <input name="account_number" type="text" id="account_number" onkeydown="loadaccount();" class="form-control" placeholder="Settlement Bank Account Number" required>
                  <div id="act_numb"></div>
                  </div>
                  </div>
  
   <div align="right">
              <div class="box-footer">
                        <button type="reset" class="btn bg-orange"><i class="fa fa-times">&nbsp;Reset</i></button>
                        <button name="save" type="submit" class="btn bg-blue ks-btn-file"><i class="fa fa-cloud-upload">&nbsp;Complete Application</i></button>

              </div>
        </div>

</div>  
			
<?php
}
elseif($PostType == "Sole Proprietorship"){
?>

<hr>
<div <div class="bg-blue" style="font-size: 16px;">&nbsp;<b> FILL THE INFORMATIONS BELOW SO AS TO UPDATE YOUR RECORDS AS A DIRECTORATE OF THE INSTITUTION.</b></div>
<hr>

      <div class="form-group">
            <label for="" class="col-sm-4 control-label" style="color:blue;">Passport Photograph</label>
      <div class="col-sm-8">
               <input type='file' name="image" class="btn bg-orange" onChange="readURL(this);" required>
               <img id="blah"  src="avtar/user2.png" alt="Logo Here" height="100" width="100"/>
      </div>
      </div>

  <div class="form-group">
                  <label for="" class="col-sm-4 control-label" style="color:blue;">Gender</label>
                  <div class="col-sm-8">
        <select name="gender"  class="form-control select2" required>
                    <option selected='selected'>Select Gender&hellip;</option>
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
        </select>
    </div>
    </div>

  <div class="form-group">
                  <label for="" class="col-sm-4 control-label" style="color:blue;">Email Address</label>
                  <div class="col-sm-8">
                  <input name="demail" type="email" class="form-control" placeholder="Your Email Address" required>
                  </div>
                  </div>

  <div class="form-group">
                  <label for="" class="col-sm-4 control-label" style="color:blue;">Mobile Number</label>
                  <div class="col-sm-8">
                  <input name="mobile_no" type="text" class="form-control" placeholder="Your Personal Phone Number" required>
                  </div>
                  </div>

  <div class="form-group">
                  <label for="" class="col-sm-4 control-label" style="color:blue;">MOI</label>
                  <div class="col-sm-8">
        <select name="moi"  class="form-control select2" required>
                    <option selected='selected'>Select Mode of Identification&hellip;</option>
                    <option value="National ID">National ID</option>
                    <option value="International Passport">International Passport</option>
        </select>
    </div>
    </div>

    <div class="form-group">
                  <label for="" class="col-sm-4 control-label" style="color:blue;">ID Number</label>
                  <div class="col-sm-8">
                  <input name="idnumber" type="text" class="form-control" placeholder="ID Number e.g NIN, etc." required>
                  </div>
                  </div>

    <div class="form-group">
                  <label for="" class="col-sm-4 control-label" style="color:blue;">BVN Number</label>
                  <div class="col-sm-8">
                    <span style="color: orange;"> <b>You need to verify your BVN here</b></span>
                  <input name="unumber" type="text" class="form-control" id="unumber" onkeydown="loaddata();" placeholder="Director BVN Number" maxlength="11" required>
                  <br>
                  <div id="bvn2"></div><br>
                  </div>
                  </div>

    <div class="form-group">
                  <label for="" class="col-sm-4 control-label" style="color:blue;">Username</label>
                  <div class="col-sm-8">
                  <input name="username" type="text" class="form-control" placeholder="Director's Username" required>
                  </div>
                  </div>

<hr>
<div class="bg-blue">&nbsp;<b> SETTLEMENT ACCOUNT DETAILS / CHARGES </b></div>
<hr>

    <div class="form-group">
                  <label for="" class="col-sm-4 control-label" style="color:blue;">State</label>
                  <div class="col-sm-8">
                  <input name="state" type="text" class="form-control" placeholder="Enter State Where You Operate" required>
                  </div>
                  </div>

    <div class="form-group">
                      <label for="" class="col-sm-4 control-label" style="color:blue;">Country</label>
                      <div class="col-sm-8">
            <select name="country"  class="form-control select2" id="country" onchange="loadbank();" required>
              <option selected="selected">Please Select Country</option>
              <option value="NG">Nigeria</option>
              <option value="GH">Ghana</option>
              <option value="KE">Kenya</option>
              <option value="UG">Uganda </option>
              <option value="TZ">Tanzania</option>
            </select>                 
            </div>
          </div>

     <div class="form-group">
                  <label for="" class="col-sm-4 control-label" style="color:blue;">Bank Code</label>
                  <div class="col-sm-8">
                    <div id="bank_list"></div>
    </div>
    </div>
    
    <div class="form-group">
                  <label for="" class="col-sm-4 control-label" style="color:blue;">Account Number</label>
                  <div class="col-sm-8">
                  <input name="account_number" type="text" id="account_number" onkeydown="loadaccount();" class="form-control" placeholder="Settlement Bank Account Number" required>
                  <div id="act_numb"></div>
                  </div>
                  </div>
  
   <div align="right">
              <div class="box-footer">
                        <button name="save" type="submit" class="btn bg-blue ks-btn-file"><i class="fa fa-cloud-upload">&nbsp;Complete Application</i></button>

              </div>
        </div>
			 
<?php 
}
?>