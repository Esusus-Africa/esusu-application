<div class="box">
        
		 <div class="box-body">
			<div class="panel panel-success">
            <div class="panel-heading">
            <h3 class="panel-title"><i class="fa fa-user"></i> New Campaign</h3>
            </div>
             <div class="box-body">

			 
               
			 <form class="form-horizontal" method="post" enctype="multipart/form-data" action="process_campaign.php">
             <div class="box-body">
				
			<div class="form-group">
            <label for="" class="col-sm-2 control-label">Campaign Image</label>
			<div class="col-sm-10">
  		  			 <input type='file' name="image" onChange="readURL(this);" />
       				 <img id="blah"  src="../avtar/user.png" alt="Campaign Image Here" height="150" width="150"/>
			</div>
			</div>
			
			<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:#009900">Customer / Author</label>
                  <div class="col-sm-10">
				<select name="author"  class="form-control select2" required>

					<option selected>Select Customer/Author Account</option>
<?php
$search = mysqli_query($link, "SELECT * FROM borrowers");
while($get_search = mysqli_fetch_array($search))
{
?>
					<option value="<?php echo $get_search['id']; ?>"><?php echo $get_search['fname'].'&nbsp;'.$get_search['lname']; ?></option>
<?php } ?>
				</select>
				</div>
            </div>
			
			
			<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:#009900">Project Tagline</label>
                  <div class="col-sm-10">
                  <input name="ptitle" type="text" class="form-control" placeholder="Project Tagline" required>
                  </div>
                  </div>
				  
			<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:#009900">Campaign Category</label>
                  <div class="col-sm-10">
				<select name="c_cat"  class="form-control select2" required>

					<option selected>Select Campaign Category</option>
<?php
$search = mysqli_query($link, "SELECT * FROM campaign_cat");
while($get_search = mysqli_fetch_array($search))
{
?>
					<option value="<?php echo $get_search['c_category']; ?>"><?php echo $get_search['c_category']; ?></option>
<?php } ?>
				</select>
				</div>
            </div>
				  
		 <div class="form-group">
                  	<label for="" class="col-sm-2 control-label" style="color:#009900">Project Description</label>
                  	<div class="col-sm-10">
					<textarea name="pdesc"  id="editor1" class="form-control" rows="4" cols="80" Placeholder="Enter Project Decription" required></textarea>
           			 </div>
          </div>
					
			<div class="form-group">
                  	<label for="" class="col-sm-2 control-label" style="color:#009900">Custom Message</label>
                  	<div class="col-sm-10">
					<textarea name="cmsg" id="editor1" class="form-control" rows="3" cols="80" Placeholder="Enter Thank you message" required></textarea>
					<span style="color: red;"> Setup automatic "thank you" message to donors </span>
           			 </div>
          	</div>
			
			
			<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:#009900">Twitter Handler</label>
                  <div class="col-sm-10">
                  <input name="thandler" type="text" class="form-control" placeholder="Your Twitter Handler" required>
                  </div>
                  </div>
				  
		<div class="form-group">
                  	<label for="" class="col-sm-2 control-label" style="color:#009900">Location</label>
                  	<div class="col-sm-10">
					<textarea name="location"  class="form-control" rows="3" cols="80" Placeholder="Enter Location" required></textarea>
           			 </div>
          	</div>
				  
				  <div class="form-group">
				  <?php 
					$call = mysqli_query($link, "SELECT * FROM systemset");
					$row = mysqli_fetch_array($call);
				  ?>
                  <label for="" class="col-sm-2 control-label" style="color:#009900">Budget in (<?php echo $row['currency']; ?>)</label>
                  <div class="col-sm-10">
                  <input name="budget" type="text" class="form-control" placeholder="Budget" required>
                  </div>
                  </div>
				  
				  
				  <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:#009900">Campaign Fee in (%)</label>
                  <div class="col-sm-10">
                  <input name="campaign_fee" type="text" class="form-control" placeholder="Campaign fee in Percentage e.g. 3 etc." required>
                  </div>
                  </div>
				  
				  
				  <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:#009900">Campaign Status</label>
                  <div class="col-sm-10">
				<select name="cstatus"  class="form-control select2" required>

					<option selected>Select Campaign Status</option>
					<option value="Pre-Approve">Pre-Approve</option>
					<option value="Approved">Approved</option>
					<option value="Disapprove">Disapprove</option>
					<option value="Pending">Pending</option>
				</select>
				</div>
            </div>
									 
<hr>	
<div class="alert-danger">&nbsp;Project Duration</div>
<hr>	
					
				<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:#009900">From</label>
                  <div class="col-sm-10">
                  <input name="dfrom" type="date" class="form-control" placeholder="Duration From" required>
                  </div>
                  </div>
				  
				   <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:#009900">To</label>
                  <div class="col-sm-10">
                  <input name="dto" type="date" class="form-control" placeholder="Duration End" required>
                  </div>
                  </div>
				  
<hr>	
<div class="alert-danger">&nbsp;Campaign Team</div>
<hr>
  				<div class="form-group">
                    <label for="" class="col-sm-2 control-label" style="color:#009900">Team Name</label>
                    <div class="col-sm-10">
                    <input name="tname" type="text" class="form-control" placeholder="Enter Team Name" required>
                    </div>
                    </div>
				  
  				   <div class="form-group">
                    <label for="" class="col-sm-2 control-label" style="color:#009900">Designation</label>
                    <div class="col-sm-10">
                    <input name="designation" type="date" class="form-control" placeholder="Enter Designation" required>
                    </div>
                    </div>
					
   				   <div class="form-group">
                     <label for="" class="col-sm-2 control-label" style="color:#009900">About Us</label>
                     <div class="col-sm-10">
                     <textarea name="aboutus" class="form-control" rows="3" cols="80" Placeholder="Information About Team" required></textarea>
                     </div>
                     </div>
			  
			 </div>
			 
			  <div align="right">
              <div class="box-footer">
                				<button type="reset" class="btn btn-primary btn-flat"><i class="fa fa-times">&nbsp;Reset</i></button>
                				<button name="cbutton" type="submit" class="btn btn-success btn-flat"><i class="fa fa-save">&nbsp;Create</i></button>

              </div>
			  </div>
			  
			 </form> 

</div>
</div>
</div>
</div>