<div class="box">

	         <div class="box-body">
			<div class="panel panel-success">
            <div class="panel-heading">
            <h3 class="panel-title"><i class="fa fa-plus"></i> New Tickets</h3>
            </div>
             <div class="box-body">
		<form class="form-horizontal" method="post" enctype="multipart/form-data" action="process_msg.php">
		 <?php echo '<div class="alert alert-info fade in" >
			  <a href = "#" class = "close" data-dismiss= "alert"> &times;</a>
  				<strong>Note that&nbsp;</strong> &nbsp;&nbsp;Some Fields are Rquired.
				</div>'?>
			 <div class="box-body">
			
			   <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:#009900">Subject</label>
                  <div class="col-sm-10">
                  <input name="subject" type="text" class="form-control" placeholder="Subject" required>
                  </div>
                  </div>
				  
				   <div class="form-group">
                  	<label for="" class="col-sm-2 control-label" style="color:#009900">Message</label>
                  	<div class="col-sm-10">
					<textarea name="message" id="editor1"  class="form-control" rows="4" cols="80"></textarea>
           		</div>
				</div>
				</div>
				
				<div align="right">
              <div class="box-footer">
                				<button type="reset" class="btn btn-primary btn-flat"><i class="fa fa-times">&nbsp;Reset</i></button>
                				<button name="send" type="submit" class="btn btn-success btn-flat"><i class="fa fa-save">&nbsp;Send</i></button>

              </div>
			  </div>
			  </form>


</div>	
</div>	
</div>
</div>