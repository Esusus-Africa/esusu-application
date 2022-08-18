<div class="box">
        
		 <div class="box-body">
			<div class="panel panel-success">
            <div class="panel-heading bg-blue">
            <h3 class="panel-title"><i class="fa fa-user"></i> New Employee</h3>
            </div>
             <div class="box-body">

			 
               
			 <form class="form-horizontal" method="post" enctype="multipart/form-data" action="process_aemp.php">
			 <?php echo '<div class="bg-orange fade in" >
			  <a href = "#" class = "close" data-dismiss= "alert"> &times;</a>
  				<strong>Note that&nbsp;</strong> &nbsp;&nbsp;Some Fields are Rquired.
				</div>'?>
             <div class="box-body">
				
			<div class="form-group">
            <label for="" class="col-sm-2 control-label" style="color:blue;">Your Image</label>
			<div class="col-sm-10">
  		  			 <input type='file' name="image" onChange="readURL(this);" />
       				 <img id="blah"  src="../avtar/user2.png" alt="Image Here" height="100" width="100"/>
			</div>
			</div>
			
			<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Full Name</label>
                  <div class="col-sm-10">
                  <input name="name" type="text" class="form-control" placeholder="First Name" required>
                  </div>
                  </div>
				  
				  
		<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Email</label>
                  <div class="col-sm-10">
                  <input type="email" name="email" type="text" class="form-control" placeholder="Email" required>
                  </div>
                  </div>
				  
		<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Mobile Number</label>
                  <div class="col-sm-10">
                  <input name="phone" type="text" class="form-control" placeholder="Mobile Number" required>
                  </div>
                  </div>
				  
				  
		 <div class="form-group">
                  	<label for="" class="col-sm-2 control-label" style="color:blue;">Address 1</label>
                  	<div class="col-sm-10">
					<textarea name="addr1"  class="form-control" rows="4" cols="80" required></textarea>
           			 </div>
          </div>
					
			<div class="form-group">
                  	<label for="" class="col-sm-2 control-label" style="color:blue;">Address 2</label>
                  	<div class="col-sm-10">
					<textarea name="addr2"  class="form-control" rows="4" cols="80" ></textarea>
           			 </div>
          	</div>
			
			
			<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">City</label>
                  <div class="col-sm-10">
                  <input name="city" type="text" id="autocomplete1" onFocus="geolocate()" class="form-control" placeholder="City" required>
                  </div>
                  </div>
				  
		<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">State</label>
                  <div class="col-sm-10">
                  <input name="state" type="text" id="autocomplete2" onFocus="geolocate()" class="form-control" placeholder="State" required>
                  </div>
                  </div>
				  
				  <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Zip Code</label>
                  <div class="col-sm-10">
                  <input name="zip" type="text" class="form-control" placeholder="Zip Code" required>
                  </div>
                  </div>
				  
		<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Country</label>
                  <div class="col-sm-10">
				 <input name="country" type="text" id="autocomplete3" onFocus="geolocate()" class="form-control" placeholder="Country" required>  
									 </div>
                 					 </div>
			
<hr>	
<div class="bg-orange">&nbsp;EMPLOYEE LOGIN INFORMATION</div>
<hr>	
					
					 <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Username</label>
                  <div class="col-sm-10">
                  <input name="username" type="text" class="form-control" placeholder="Username" required>
                  </div>
                  </div>
				  
				   <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Password</label>
                  <div class="col-sm-10">
                  <input name="password" type="text" class="form-control" placeholder="Password" required>
                  </div>
                  </div>
				  
				    <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Confirm Password</label>
                  <div class="col-sm-10">
                  <input name="cpassword" type="text" class="form-control" placeholder="Confirm Password" required>
                  </div>
                  </div>
				  
				  	<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Select Role</label>
                  <div class="col-sm-9">
                  <table class="table table-responsive">
                    <?php
                    $search_module_properties = mysqli_query($link, "SELECT * FROM my_permission WHERE companyid = '$agentid'");
                    while($fetch_mproperties = mysqli_fetch_array($search_module_properties))
                    {
                    ?>
                    <tr>
                      <td>
                        <?php echo ucfirst(str_replace('_', ' ', $fetch_mproperties['urole'])); ?>
                      </td>
                      <td>
                        <input name="urole[]" type="checkbox" value="<?php echo $fetch_mproperties['urole']; ?>">
                      </td>
                    </tr>
                    <?php } ?>
                  </table>
                </div>
            </div> 
			  
			 </div>
			 
			  <div align="right">
              <div class="box-footer">
                				<button type="reset" class="btn bg-orange"><i class="fa fa-times">&nbsp;Reset</i></button>
                				<button name="emp" type="submit" class="btn bg-blue"><i class="fa fa-save">&nbsp;Save</i></button>

              </div>
			  </div>
			  
			 </form> 

</div>
</div>
</div>
</div>