<div class="box">
	       <div class="box-body">
			<div class="panel panel-success">
            <div class="panel-heading bg-blue">
            <h3 class="panel-title"><i class="fa fa-forward"></i> Customer Transfer Form</h3>
            </div>

             <div class="box-body">
              
<form class="form-horizontal" method="post" enctype="multipart/form-data" action="process_transfer.php">

<div class="box-body">

  <input name="id" type="hidden" class="form-control" value="<?php echo $_GET['id']; ?>">

  <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Transfer to</label>
                  <div class="col-sm-9">
                  <select name="transfer_to" class="form-control select2" id="transfer_to" required>
                  <option selected>To: .............</option>
                   <option value="Institution">Institution</option>
                   <option value="Agent">Agent</option>
                  </select>
                  </div>
                  </div>

                  <span id='ShowValueFrank'></span>

</div>

  <div align="right">
              <div class="box-footer">
                        <button name="save" type="submit" class="btn bg-blue"><i class="fa fa-forward">&nbsp;Transfer</i></button>

              </div>
        </div>

 </form>

</div>	
</div>	
</div>
</div>