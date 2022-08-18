<div class="box">
        
		 <div class="box-body">
			<div class="panel panel-success">
            <div class="panel-heading bg-blue">
            <h3 class="panel-title"><i class="fa fa-user"></i> New Campaign</h3>
            </div>
             <div class="box-body">
             
               
			 <form class="form-horizontal" method="post" enctype="multipart/form-data" action="process_campaign.php">
             <div class="box-body">
                 
            <div class="form-group">
            <label for="" class="col-sm-2 control-label" style="color:blue;">Campaign Image</label>
			<div class="col-sm-10">
  		  			 <input type='file' name="image" onChange="readURL(this);" required class="alert bg-orange"/>
       				 <img id="blah"  src="../avtar/user.png" alt="Campaign Image Here" height="150" width="300"/>
			</div>
			</div>
			
			
			<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Customer</label>
                  <div class="col-sm-10">
				<select name="author"  class="form-control select2" required>

					<option selected>Select Customer Account</option>
<?php
$search = mysqli_query($link, "SELECT * FROM borrowers");
while($get_search = mysqli_fetch_array($search))
{
?>
					<option value="<?php echo $get_search['account']; ?>"><?php echo $get_search['fname'].'&nbsp;'.$get_search['lname']; ?></option>
<?php } ?>
				</select>
				</div>
            </div>
			
			
			<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Project Tagline</label>
                  <div class="col-sm-10">
                  <input name="ptitle" type="text" class="form-control" placeholder="Project Tagline" required>
                  </div>
                  </div>
				  
			<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Campaign Category</label>
                  <div class="col-sm-10">
    				<select name="c_cat"  class="form-control select2" required>
    
    					<option selected>Select Campaign Category</option>
    <?php
    $search = mysqli_query($link, "SELECT * FROM campaign_category");
    while($get_search = mysqli_fetch_array($search))
    {
    ?>
    					<option value="<?php echo $get_search['category']; ?>"><?php echo $get_search['category']; ?></option>
    <?php } ?>
    				</select>
				</div>
            </div>
				  
		 <div class="form-group">
                  	<label for="" class="col-sm-2 control-label" style="color:blue;">Project Description</label>
                  	<div class="col-sm-10">
					<textarea name="pdesc"  id="editor1" class="form-control" rows="4" cols="80" Placeholder="Enter Project Decription" required></textarea>
           			 </div>
          </div>
					
			<div class="form-group">
                  	<label for="" class="col-sm-2 control-label" style="color:blue;">Custom Message</label>
                  	<div class="col-sm-10">
					<textarea name="cmsg" id="editor1" class="form-control" rows="3" cols="80" Placeholder="Enter Thank you message" required></textarea>
					<span style="color: orange;"> Setup automatic "thank you" message to donors </span>
           			 </div>
          	</div>
			
			
			<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Twitter Handler</label>
                  <div class="col-sm-10">
                  <input name="thandler" type="text" class="form-control" placeholder="Your Twitter Handler" required>
                  </div>
                  </div>
				  
		    <div class="form-group">
                  	<label for="" class="col-sm-2 control-label" style="color:blue;">Location</label>
                  	<div class="col-sm-10">
					<textarea name="location"  class="form-control" rows="3" cols="80" Placeholder="Enter Location" required></textarea>
           			 </div>
          	</div>
				  
		    <div class="form-group">
				  <?php 
					$call = mysqli_query($link, "SELECT * FROM systemset");
					$row = mysqli_fetch_array($call);
				  ?>
                <label for="" class="col-sm-2 control-label" style="color:blue;">Budget in (USD)</label>
                <div class="col-sm-10">
                  <input name="budget" type="text" class="form-control" placeholder="Budget" required>
                </div>
            </div>
                  
                
            <div class="form-group">
            <label for="" class="col-sm-2 control-label" style="color:blue;">Other Campaign Image</label>
			<div class="col-sm-10">
  		  			 <input type='file' name="c_image[]" class="alert bg-orange" multiple/>
			</div>
			</div>
				  
									 
<hr>	
<div class="bg-orange">&nbsp;Project Duration</div>
<hr>	
					
				<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">From</label>
                  <div class="col-sm-10">
                  <input name="dfrom" type="date" class="form-control" placeholder="Duration From" required>
                  </div>
                  </div>
				  
				   <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">To</label>
                  <div class="col-sm-10">
                  <input name="dto" type="date" class="form-control" placeholder="Duration End" required>
                  </div>
                  </div>
				  
			  
			 </div>
			 
			  <div align="right">
              <div class="box-footer">
                				<button name="cbutton" type="submit" class="btn bg-blue btn-flat"><i class="fa fa-save">&nbsp;Create</i></button>

              </div>
			  </div>
			  
			 </form> 

</div>
</div>
</div>
</div>