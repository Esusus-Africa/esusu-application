<div class="box">
        
		 <div class="box-body">
			<div class="panel panel-success">
            <div class="panel-heading">
            <h3 class="panel-title"><i class="fa fa-bullhorn"></i> Edit Campaign</h3>
            </div>
             <div class="box-body">

			 
               
			 <form class="form-horizontal" method="post" enctype="multipart/form-data" action="process_campaign_update.php?id=<?php echo $_SESSION['tid']; ?>&&idm=<?php echo $_GET['idm']; ?>&&mid=NDIx">
             <div class="box-body">
<?php
$idm = $_GET['idm'];
$detect_campaign = mysqli_query($link, "SELECT * FROM causes WHERE id = '$idm'");
$fetch_campaign = mysqli_fetch_object($detect_campaign);
$b_id = $fetch_campaign->b_id;
?>
			<div class="form-group">
            <label for="" class="col-sm-2 control-label">Campaign Image</label>
			<div class="col-sm-10">
  		  			 <input type='file' name="image" onChange="readURL(this);" />
       				 <img id="blah"  src="../<?php echo $fetch_campaign->campaign_image; ?>" alt="Campaign Image Here" height="150" width="150"/>
			</div>
			</div>
			
			<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:#009900">Customer / Author</label>
                  <div class="col-sm-10">
				<select name="author"  class="form-control select2" required>

<?php
$search = mysqli_query($link, "SELECT * FROM borrowers WHERE id = '$b_id'");
while($get_search = mysqli_fetch_array($search))
{
?>
					<option value="<?php echo $get_search['id']; ?>" selected><?php echo $get_search['fname'].'&nbsp;'.$get_search['lname']; ?></option>
<?php } ?>
				</select>
				</div>
            </div>
			
			
			<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:#009900">Project Tagline</label>
                  <div class="col-sm-10">
                  <input name="ptitle" type="text" class="form-control" value="<?php echo $fetch_campaign->campaign_title; ?>"placeholder="Project Tagline" required>
                  </div>
                  </div>
				  
			<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:#009900">Campaign Category</label>
                  <div class="col-sm-10">
				<input name="c_cat" type="text" class="form-control" value="<?php echo $fetch_campaign->c_category; ?>" required readonly>
				</div>
            </div>
				  
		 <div class="form-group">
                  	<label for="" class="col-sm-2 control-label" style="color:#009900">Project Description</label>
                  	<div class="col-sm-10">
					<textarea name="pdesc"  id="editor1" class="form-control" rows="4" cols="80" Placeholder="Enter Project Decription" required><?php echo $fetch_campaign->campaign_desc; ?></textarea>
           			 </div>
          </div>
					
			<div class="form-group">
                  	<label for="" class="col-sm-2 control-label" style="color:#009900">Custom Message</label>
                  	<div class="col-sm-10">
					<textarea name="cmsg" id="editor1" class="form-control" rows="3" cols="80" Placeholder="Enter Thank you message" required><?php echo $fetch_campaign->msg_to_donor; ?></textarea>
					<span style="color: red;"> Setup automatic "thank you" message to donors </span>
           			 </div>
          	</div>
			
			
			<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:#009900">Twitter Handler</label>
                  <div class="col-sm-10">
                  <input name="thandler" type="text" class="form-control" value="<?php echo $fetch_campaign->twitter_handler; ?>" placeholder="Your Twitter Handler" required>
                  </div>
                  </div>
				  
		<div class="form-group">
                  	<label for="" class="col-sm-2 control-label" style="color:#009900">Location</label>
                  	<div class="col-sm-10">
					<textarea name="location"  class="form-control" rows="3" cols="80" Placeholder="Enter Location" required><?php echo $fetch_campaign->location; ?></textarea>
           			 </div>
          	</div>
				  
				  <div class="form-group">
				  <?php 
					$call = mysqli_query($link, "SELECT * FROM systemset");
					$row = mysqli_fetch_array($call);
				  ?>
                  <label for="" class="col-sm-2 control-label" style="color:#009900">Budget in (<?php echo $row['currency']; ?>)</label>
                  <div class="col-sm-10">
                  <input name="budget" type="text" class="form-control" value="<?php echo $fetch_campaign->budget; ?>" placeholder="Budget" required>
                  </div>
                  </div>
				  
				  
				  <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:#009900">Campaign Fee in (%)</label>
                  <div class="col-sm-10">
                  <input name="campaign_fee" type="text" class="form-control" value="<?php echo $fetch_campaign->campaign_fee; ?>" placeholder="Campaign fee in Percentage e.g. 3 etc." required>
                  </div>
                  </div>
									 
<hr>	
<div class="alert-danger">&nbsp;Project Duration</div>
<hr>	
					
				<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:#009900">From</label>
                  <div class="col-sm-10">
                  <input name="dfrom" type="date" class="form-control" value="<?php echo $fetch_campaign->dfrom; ?>" placeholder="Duration From" required>
                  </div>
                  </div>
				  
				   <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:#009900">To</label>
                  <div class="col-sm-10">
                  <input name="dto" type="date" class="form-control" value="<?php echo $fetch_campaign->dto; ?>" placeholder="Duration End" required>
                  </div>
                  </div>
				  
<hr>	
<div class="alert-danger">&nbsp;Campaign Team</div>
<hr>
  				<div class="form-group">
                    <label for="" class="col-sm-2 control-label" style="color:#009900">Team Name</label>
                    <div class="col-sm-10">
                    <input name="tname" type="text" class="form-control" value="<?php echo $fetch_campaign->tname; ?>" placeholder="Enter Team Name" required>
                    </div>
                    </div>
				  
  				   <div class="form-group">
                    <label for="" class="col-sm-2 control-label" style="color:#009900">Designation</label>
                    <div class="col-sm-10">
                    <input name="designation" type="text" class="form-control" value="<?php echo $fetch_campaign->designation; ?>" placeholder="Enter Designation" required>
                    </div>
                    </div>
					
   				   <div class="form-group">
                     <label for="" class="col-sm-2 control-label" style="color:#009900">About Us</label>
                     <div class="col-sm-10">
                     <textarea name="aboutus" class="form-control" rows="3" cols="80" Placeholder="Information About Team" required><?php echo $fetch_campaign->aboutus; ?></textarea>
                     </div>
                     </div>
			  
			 </div>
			 
			  <div align="right">
              <div class="box-footer">
                				<button type="reset" class="btn btn-primary btn-flat"><i class="fa fa-times">&nbsp;Reset</i></button>
                				<button name="cbutton" type="submit" class="btn btn-success btn-flat"><i class="fa fa-save">&nbsp;Update</i></button>

              </div>
			  </div>
			  
			 </form> 

</div>
</div>
</div>
</div>