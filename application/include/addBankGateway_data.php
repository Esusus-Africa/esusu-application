<div class="box">
	       <div class="box-body">
			<div class="panel panel-success">
            <div class="panel-heading bg-blue">
            <h3 class="panel-title"><i class="fa fa-link"></i> Add Bank</h3>
            </div>

             <div class="box-body">
 <?php
if(isset($_POST['addBank']))
{
	//image
    $image = addslashes(file_get_contents($_FILES['image']['tmp_name']));
    $bankcat = mysqli_real_escape_string($link, $_POST['bankcat']);
	$bankName = mysqli_real_escape_string($link, $_POST['bankName']);
    $reg_type =  mysqli_real_escape_string($link, $_POST['reg_type']);
    $otp_option =  mysqli_real_escape_string($link, $_POST['otp_option']);
    $mynamespace1 = mysqli_real_escape_string($link, $_POST['mynamespace1']);
    $mynamespace2 = mysqli_real_escape_string($link, $_POST['mynamespace2']);
    $mynamespace3 = mysqli_real_escape_string($link, $_POST['mynamespace3']);
    $mynamespace4 = mysqli_real_escape_string($link, $_POST['mynamespace4']);
    $currentdate = date("Y-m-d h:i:s");

    if($image == ""){

        echo "<div class='alert bg-blue'>Opps!....Logo cannot be empty!!</div>";

    }else{

        $sourcepath = $_FILES["image"]["tmp_name"];
		$targetpath = "../img/" . $_FILES["image"]["name"];
		move_uploaded_file($sourcepath,$targetpath);
									
		$location = "img/".$_FILES['image']['name'];

        $insert = mysqli_query($link, "INSERT INTO account_openingbank VALUES(null,'$location','$bankcat','$bankName','$reg_type','$otp_option','$mynamespace1','$mynamespace2','$mynamespace3','$mynamespace4','Show','$currentdate')") or die ("Error: " . mysqli_error($link));

        if(!$insert){

            echo "<div class='alert bg-orange'>Opps!....Unable to add new bank!!</div>";
        
          }
          else{
        
            echo "<div class='alert bg-blue'>New Bank Added Successfully!!</div>";
        
          }

    }

}
?>           
			 <form class="form-horizontal" method="post" enctype="multipart/form-data">

             <div class="box-body">

             <div class="form-group">
                <label for="" class="col-sm-3 control-label" style="color:blue;">Bank Logo:</label>
                <div class="col-sm-6">
                    <input type='file' name="image" onChange="readURL(this);" />
       			    <img id="blah"  src="../<?php echo $row ['image'];?>" alt="Upload Logo Here" height="100" width="100"/>
                </div>
                <label for="" class="col-sm-3 control-label"></label>
            </div>

            <div class="form-group">
                <label for="" class="col-sm-3 control-label" style="color:blue;">Category:</label>
                <div class="col-sm-6">
                    <select name="bankcat"  class="form-control select2" required>
                      <option value="" selected>Select Category</option>
                      <option value="Conventional Bank">Conventional Banking</option>
                      <option value="Non-Interest Bank">Non-Interest Banking</option>
                    </select>
                  </div>
                <label for="" class="col-sm-3 control-label"></label>
            </div>

            <div class="form-group">
                <label for="" class="col-sm-3 control-label" style="color:blue;">Bank:</label>
                <div class="col-sm-6">
                    <select name="bankName" class="form-control select2" required>
                      <option value="" selected>Select Bank</option>
                      <?php
                        $searchBank = mysqli_query($link, "SELECT * FROM bank_list2");
                        while($bank = mysqli_fetch_array($searchBank))
                        {
                      ?>
                      <option value="<?php echo $bank['bankname']; ?>"><?php echo $bank['bankname']; ?></option>
                      <?php
                        }
                      ?>
                    </select>
                  </div>
                <label for="" class="col-sm-3 control-label"></label>
            </div>

            <div class="form-group">
                <label for="" class="col-sm-3 control-label" style="color:blue;">Reg. Type:</label>
                <div class="col-sm-6">
                    <select name="reg_type"  class="form-control select2" required>
                      <option value="" selected>Select Settings</option>
                      <option value="Reg_With_BVN">Reg_With_BVN</option>
                      <option value="Reg_Without_BVN">Reg_Without_BVN</option>
                      <option value="Both">Both</option>
                    </select>
                  </div>
                <label for="" class="col-sm-3 control-label"></label>
            </div>

            <div class="form-group">
                <label for="" class="col-sm-3 control-label" style="color:blue;">Require OTP:</label>
                <div class="col-sm-6">
                    <select name="otp_option"  class="form-control select2" required>
                      <option value="" selected>Select Settings</option>
                      <option value="Yes" selected>Yes</option>
                      <option value="No" selected>No</option>
                    </select>
                  </div>
                <label for="" class="col-sm-3 control-label"></label>
            </div>

            <div class="form-group">
                    <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">With BVN Namespace</label>
                    <div class="col-sm-6">
                        <input name="mynamespace1" type="text" class="form-control" placeholder="Enter API Namespace with BVN">
                    </div>
                    <label for="" class="col-sm-3 control-label"></label>
                  </div>

                  <div class="form-group">
                    <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Without BVN Namespace</label>
                    <div class="col-sm-6">
                        <input name="mynamespace2" type="text" class="form-control" placeholder="Enter API Namespace without BVN">
                    </div>
                    <label for="" class="col-sm-3 control-label"></label>
                  </div>

                  <div class="form-group">
                    <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">OTP Namespace</label>
                    <div class="col-sm-6">
                        <input name="mynamespace3" type="text" class="form-control" placeholder="Enter API Namespace for OTP Confirmation">
                    </div>
                    <label for="" class="col-sm-3 control-label"></label>
                  </div>

                  <div class="form-group">
                    <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Document Namespace</label>
                    <div class="col-sm-6">
                        <input name="mynamespace4" type="text" class="form-control" placeholder="Enter API Namespace for Document Upload">
                    </div>
                    <label for="" class="col-sm-3 control-label"></label>
                  </div>
	  
			 </div>
			 
			<div class="form-group" align="right">
                <label for="" class="col-sm-3 control-label" style="color:blue;"></label>
                <div class="col-sm-6">
                	<button name="addBank" type="submit" class="btn bg-blue"><i class="fa fa-plus">&nbsp;Submit</i></button>
                </div>
                <label for="" class="col-sm-3 control-label"></label>
            </div>
			  
			 </form> 

</div>	
</div>	
</div>
</div>