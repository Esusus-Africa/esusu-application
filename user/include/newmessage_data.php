<div class="box">

	         <div class="box-body">
			<div class="panel panel-success">
            <div class="panel-heading bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">
            <h3 class="panel-title"><i class="fa fa-plus"></i> New Tickets</h3>
            </div>
             <div class="box-body">
		<form class="form-horizontal" method="post" enctype="multipart/form-data" action="process_msg.php">
		
			 <div class="box-body">
			
			   <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Subject</label>
                  <div class="col-sm-10">
                  <input name="subject" type="text" class="form-control" placeholder="Subject" required>
                  </div>
                  </div>
				  
				   <div class="form-group">
                  	<label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Message</label>
                  	<div class="col-sm-10">
					<textarea name="message" id="editor1"  class="form-control" rows="4" cols="80"></textarea>
           		</div>
				</div>
				</div>
				
				<div align="right">
              <div class="box-footer">
                				<button name="send" type="submit" class="btn bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>"><i class="fa fa-save">&nbsp;Send Message</i></button>

              </div>
			  </div>
			  </form>


</div>	
</div>	
</div>
</div>