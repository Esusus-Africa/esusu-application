<div class="box">
	       <div class="box-body">
			<div class="panel panel-success">
            <div class="panel-heading bg-blue">
            <h3 class="panel-title"><i class="fa fa-tree"></i>  Set Access Level</h3>
            </div>

             <div class="box-body">
              
<form class="form-horizontal" method="post" enctype="multipart/form-data" action="process_perm1.php">

<div class="box-body">

                <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Roles</label>
                  <div class="col-sm-9">
                  <select name="rname" class="form-control select2" required>
                  <option selected>Select Role Name</option>
<?php
$search_role = mysqli_query($link, "SELECT * FROM global_role");
while($fetch_role = mysqli_fetch_array($search_role)){
?>
                   <option value="<?php echo $fetch_role['role_name']; ?>"><?php echo $fetch_role['role_name']; ?></option>
<?php } ?>
                  </select>
                  </div>
                  </div>

                <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Modules</label>
                  <div class="col-sm-9">
                  <select name="mname" class="form-control select2" id="list_module1" required>
                  <option value="" selected>Select Module</option>
                   <option value="All">All Module(s)</option>
                  </select>
                  </div>
                  </div>

                  <span id='ShowValueFrank'></span>
                  <span id='ShowValueFrank'></span>
                  <span id='ShowValueFrank'></span>
                  <span id='ShowValueFrank'></span>
                  <span id='ShowValueFrank'></span>


</div>

 </form>

</div>	
</div>	
</div>
</div>