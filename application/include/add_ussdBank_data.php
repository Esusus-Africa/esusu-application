<div class="box">
	       <div class="box-body">
			<div class="panel panel-success">
            <div class="panel-heading bg-blue">
            <h3 class="panel-title"><i class="fa fa-link"></i> Add USSD Bank</h3>
            </div>

             <div class="box-body">
 <?php
if(isset($_POST['addBank']))
{
	//image
    $image = addslashes(file_get_contents($_FILES['image']['tmp_name']));

	$bankName = mysqli_real_escape_string($link, $_POST['bankName']);
    $ussdCode =  mysqli_real_escape_string($link, $_POST['ussdCode']);
    $currentdate = date("Y-m-d h:i:s");

    if($image == ""){

        echo "<div class='alert bg-blue'>Opps!....Logo cannot be empty!!</div>";

    }else{

        $sourcepath = $_FILES["image"]["tmp_name"];
		$targetpath = "../img/" . $_FILES["image"]["name"];
		move_uploaded_file($sourcepath,$targetpath);
									
		$location = "img/".$_FILES['image']['name'];

        $insert = mysqli_query($link, "INSERT INTO ussdbank VALUES(null,'$location','$bankName','$ussdCode','$currentdate')");

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
                <label for="" class="col-sm-3 control-label" style="color:blue;">Bank:</label>
                <div class="col-sm-6">
                    <select name="bankName"  class="form-control select2" required>
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
                <label for="" class="col-sm-3 control-label" style="color:blue;">Bank USSD Code:</label>
                <div class="col-sm-6">
                  <input name="ussdCode" type="text" value="" class="form-control" placeholder="Enter Bank USSD Code" required>
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