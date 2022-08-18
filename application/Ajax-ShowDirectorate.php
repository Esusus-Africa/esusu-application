<?php
include ("../config/connect.php");
$PostType = $_GET['PostType'];

if($PostType == "Partnership")
{
?>

<input name="account" type="hidden" class="form-control" value="<?php echo $account; ?>" id="HideValueFrank"/>

<hr>
<div class="bg-blue">&nbsp;<b> UPLOAD ALL DIRECTORATE USING THE CSV SAMPLE ATTACHED BELOW </b></div>
<hr>         
             
<div class="box-body">
    
  <div class="form-group">
           <label for="" class="col-sm-4 control-label" style="color: blue;">Import All Directorate Here:</label>
  <div class="col-sm-8">
        <span class="fa fa-cloud-upload"></span>
           <span class="ks-text">Choose file</span>
           <input type="file" name="file" accept=".csv" required>
  </div>
  </div>

   <hr>
  <p style="color:orange"><b style="color:blue;">NOTE:</b><br>
    <span style="color:blue;">(1)</span> <i>Kindly download the <a href="../sample/institution_user_sample.csv"> <b class="btn bg-blue"><i class="fa fa-download"></i> sample file</b></a> to upload the details once.</i></p>
    <p><span style="color:blue;">(2)</span> <i style="color:orange;">Please note that the <span style="color: blue">mio</span> stand for <span style="color: blue">Mode of Identification</span>. And it has to be either <span style="color: blue">National ID OR International Passport</span></i></p>
    <p><span style="color:blue;">(3)</span> <i style="color:orange;">Also, make sure you enter <span style="color: blue">Valid BVN</span> as it will be verify while uploading the csv file. And failure to validate the bvn will result to <span style="color: blue;"> Uploading not successful.</span></i></p>
                        
  <hr>
  
    <div align="right">
       <div class="box-footer">
       <button class="btn bg-blue ks-btn-file" type="submit" name="save"><span class="fa fa-cloud-upload"></span> Submit </button> 
       </div>
    </div>  

</div>  
			
<?php
}
elseif($PostType == "Sole Proprietorship"){
	$drID = 'DIR'.rand(10000000,99999999);
?>

<input name="drID" type="hidden" class="form-control" value="<?php echo $drID; ?>" id="HideValueFrank">

<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Full Name</label>
                  <div class="col-sm-10">
                  <input name="fname" type="text" class="form-control" placeholder="Director's Name" required>
                  </div>
                  </div>

    <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Gender</label>
                  <div class="col-sm-10">
        <select name="gender"  class="form-control select2" required>
                    <option selected='selected'>Select Gender&hellip;</option>
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
        </select>
    </div>
    </div>

    <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Email Address</label>
                  <div class="col-sm-10">
                  <input name="demails" type="email" class="form-control" placeholder="Director's Email Adddress" required>
                  </div>
                  </div>

    <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Mobile Number</label>
                  <div class="col-sm-10">
                  <input name="mobile_no" type="text" class="form-control" placeholder="Director's Personal Mobile No." required>
                  </div>
                  </div>

    <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">MOI</label>
                  <div class="col-sm-10">
        <select name="moi"  class="form-control select2" required>
                    <option selected='selected'>Select Mode of Identification&hellip;</option>
                    <option value="National ID">National ID</option>
                    <option value="International Passport">International Passport</option>
        </select>
    </div>
    </div>

    <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">ID Number</label>
                  <div class="col-sm-10">
                  <input name="idnumber" type="text" class="form-control" placeholder="ID Number e.g NIN, etc." required>
                  </div>
                  </div>

    <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">BVN Number</label>
                  <div class="col-sm-10">
                  	<span style="color: orange;"> You need to verify Directors BVN </span>
                  <input name="unumber" type="text" class="form-control" id="unumber" onkeydown="loaddata();" placeholder="Director BVN Number" maxlength="11" required>
                  <br>
                  <div id="bvn2"></div>
                  </div>
                  </div>

    <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Username</label>
                  <div class="col-sm-10">
                  <input name="username" type="text" class="form-control" placeholder="Director's Username" required>
                  </div>
                  </div>

    <div align="right">
              <div class="box-footer">
                        <button type="reset" class="btn bg-orange"><i class="fa fa-times">&nbsp;Reset</i></button>
                        <button name="save" type="submit" class="btn bg-blue ks-btn-file"><i class="fa fa-cloud-upload">&nbsp;Submit</i></button>

              </div>
        </div>
			 
<?php 
}
?>