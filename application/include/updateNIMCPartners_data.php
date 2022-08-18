<div class="box">
	       <div class="box-body">
			<div class="panel panel-success">
            <div class="panel-heading bg-blue">
            <h3 class="panel-title">Update NIMC Partner</h3>
            </div>

             <div class="box-body">

			 <form class="form-horizontal" method="post" enctype="multipart/form-data">

            <?php
            if(isset($_POST['updatePartner'])){

                $mypartnerid = $_GET['idm'];
                $partnerName = mysqli_real_escape_string($link, $_POST['partnerName']);
                $category = mysqli_real_escape_string($link, $_POST['category']);

                mysqli_query($link, "UPDATE nimcPartner SET partnerName = '$partnerName', category = '$category' WHERE id = '$mypartnerid'");
                
                echo "<div class='alert alert-success'>Info Updated Successfully!</div>";

            }
            ?>


            <?php
            $partnerid = $_GET['idm'];
            $seachPartner = mysqli_query($link, "SELECT * FROM nimcPartner WHERE id = '$partnerid'");
            $fetchPartner = mysqli_fetch_array($seachPartner);
            ?>
            <div class="box-body">
			
                <div class="form-group">
                    <label for="" class="col-sm-3 control-label" style="color:blue;">Partner Name</label>
                    <div class="col-sm-6">
                    <input name="partnerName" type="text" class="form-control" placeholder="Partner Name" value="<?php echo $fetchPartner['partnerName']; ?>" required>
                    </div>
                    <label for="" class="col-sm-3 control-label"></label>
                    </div>

                <div class="form-group">
                    <label for="" class="col-sm-3 control-label" style="color:blue;">Category</label>
                    <div class="col-sm-6">
                        <select name="category" class="form-control select2" required>
 							<option value="<?php echo $fetchPartner['category']; ?>" selected='selected'><?php echo $fetchPartner['category']; ?></option>
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
                	<button name="updatePartner" type="submit" class="btn bg-blue"><i class="fa fa-save">&nbsp;Submit</i></button>
                </div>
                <label for="" class="col-sm-3 control-label"></label>
            </div>
			  
			</form>

</div>	
</div>	
</div>
</div>