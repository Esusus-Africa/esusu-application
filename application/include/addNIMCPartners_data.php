<div class="box">
	       <div class="box-body">
			<div class="panel panel-success">
            <div class="panel-heading bg-blue">
            <h3 class="panel-title">NIMC Partner Registration</h3>
            </div>

             <div class="box-body">

            <?php
            if(isset($_GET['bulk'])){
            ?>

            <form class="form-horizontal" method="post" enctype="multipart/form-data">
            
            <?php
            if(isset($_POST['addBulkPartner'])){

                if($_FILES['file']['name']){
        
                    $filename = explode('.', $_FILES['file']['name']);
                    
                    if($filename[1] == 'csv'){
                        
                        $handle = fopen($_FILES['file']['tmp_name'], "r");
                        $fp = file($_FILES['file']['tmp_name'], FILE_SKIP_EMPTY_LINES);
                        $countFile = count($fp);
                        fgetcsv($handle,1000,","); // pop the headers
                        while($data = fgetcsv($handle,1000,",")){
                            
                            $empty_filesop = array_filter(array_map('trim', $data));
                            
                            if(!empty($empty_filesop)){

                                $partnerName = strtoupper(mysqli_real_escape_string($link, $data[0]));
                                $category = ucwords(mysqli_real_escape_string($link, $data[1]));

                                $sql = "INSERT INTO nimcPartner(id,partnerName,category) VALUES(null,'$partnerName','$category')";
                                $result = mysqli_query($link,$sql);

                                if(!$result)
                                {
                                    echo "<script type=\"text/javascript\">
                                        alert(\"Invalid File:Please Upload CSV File.\");
                                        </script>".mysqli_error($link);
                                }

                            }

                        }
                        fclose($handle);
                        echo "<script type=\"text/javascript\">
                                    alert(\"All NIMC Partners Imported Successfully.\");
                                </script>";

                    }

                }

            }
            ?>
                <div class="box-body">

                    <div class="form-group">
                        <label for="" class="col-sm-3 control-label" style="color: blue;">Import NIMC Partners:</label>
                        <div class="col-sm-6">
                            <span class="fa fa-cloud-upload"></span>
                            <span class="ks-text">Choose file</span>
                            <input type="file" name="file" accept=".csv" required>
                        </div>
                        <label for="" class="col-sm-3 control-label"></label>
                    </div>
                    
                    <div class="form-group">
                        <label for="" class="col-sm-3 control-label" style="color: blue;">NOTE:</label>
                        <div class="col-sm-6">
                            <span style="color:blue;">(1)</span> <i>Kindly download the <a href="../sample/nimcpartner_sample.csv"> <b class="btn bg-blue"><i class="fa fa-download"></i> sample file</b></a> to upload the details once.</i>
                        </div>
                        <label for="" class="col-sm-3 control-label"></label>
                    </div>

                </div>

                <div class="form-group" align="right">
                    <label for="" class="col-sm-3 control-label" style="color:blue;"></label>
                    <div class="col-sm-6">
                        <button name="addBulkPartner" type="submit" class="btn bg-blue"><i class="fa fa-save">&nbsp;Submit</i></button>
                    </div>
                    <label for="" class="col-sm-3 control-label"></label>
                </div>

            </form>
            
            <?php
            }else{
            ?>

			<form class="form-horizontal" method="post" enctype="multipart/form-data">

            <?php
            if(isset($_POST['addPartner'])){

                $partnerName = mysqli_real_escape_string($link, $_POST['partnerName']);
                $category = mysqli_real_escape_string($link, $_POST['category']);

                mysqli_query($link, "INSERT INTO nimcPartner VALUES(null,'$partnerName','$category')");
                
                echo "<div class='alert alert-success'>NIMC Partner Added Successfully!</div>";

            }
            ?>


            <div class="box-body">
			
                <div class="form-group">
                    <label for="" class="col-sm-3 control-label" style="color:blue;">Partner Name</label>
                    <div class="col-sm-6">
                    <input name="partnerName" type="text" class="form-control" placeholder="Partner Name" required>
                    </div>
                    <label for="" class="col-sm-3 control-label"></label>
                    </div>

                <div class="form-group">
                    <label for="" class="col-sm-3 control-label" style="color:blue;">Category</label>
                    <div class="col-sm-6">
                        <select name="category" class="form-control select2" required>
 							<option value="" selected='selected'>Select Category</option>
 							<option value="Private Limited Liability Company">Private Limited Liability Company</option>
 							<option value="Non-Governmental Organization and Civil Society Organization">Non-Governmental Organization and Civil Society Organization </option>
                            <option value="Start-up Companies and Small, Medium Enterprises">Start-up Companies and Small, Medium Enterprises</option>
                            <option value="Small, Medium Enterprises SMES (B1)">Small, Medium Enterprises SMES (B1)</option>
                            <option value="Public Sector Institutions State Governments">Public Sector Institutions State Governments</option>
 						</select>
                    </div>
                    <label for="" class="col-sm-3 control-label"></label>
                    </div>
			
			</div>
			 
			<div class="form-group" align="right">
                <label for="" class="col-sm-3 control-label" style="color:blue;"></label>
                <div class="col-sm-6">
                	<button name="addPartner" type="submit" class="btn bg-blue"><i class="fa fa-save">&nbsp;Submit</i></button>
                </div>
                <label for="" class="col-sm-3 control-label"></label>
            </div>
			  
			</form>

            <?php
            }
            ?>

</div>	
</div>	
</div>
</div>