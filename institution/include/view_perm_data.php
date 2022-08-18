<div class="row">
    <style>
      input.checkbox {
        width: 35px;
        height: 35px;
      }
    </style>
		    <section class="content">  
	        <div class="box box-danger">
            <div class="box-body">
              <div class="table-responsive">
             <div class="box-body">

			 <a href="permission_list.php?id=<?php echo $_SESSION['tid']; ?>&&mid=<?php echo base64_encode("413"); ?>"><button type="button" class="btn btn-flat bg-orange"><i class="fa fa-mail-reply-all"></i>&nbsp;Back</button></a> 

    	<form class="form-horizontal" method="post" enctype="multipart/form-data">
        
        <div class="box-body">
            
            <div class="scrollable1">

                        <div class="form-group">
                          <label for="" class="col-sm-1 control-label" style="color:blue;"></label>
                          <div class="col-sm-9">
                            <table class="table table-responsive">
                                <input name="permid" type="hidden" value="<?php echo $_GET['id']; ?>" id="permid">
                            <?php
                              $id = $_GET['id'];
                              $search_permission_given = mysqli_query($link, "SELECT * FROM my_permission WHERE id = '$id'");
                              while($fetch_permission_given = mysqli_fetch_field($search_permission_given))
                              {
                                  $pgivenName = $fetch_permission_given->name;
                                  
                                  $search_module_properties = mysqli_query($link, "SELECT * FROM module_property WHERE mtype = 'client' AND mproperty = '$pgivenName' ORDER BY mname DESC");
                                  $fetch_mproperties = mysqli_fetch_array($search_module_properties);
                                  $mproperty = $fetch_mproperties['mproperty'];
                                  
                                  $checkperm = mysqli_query($link, "SELECT * FROM my_permission WHERE $pgivenName = '1' AND id = '$id'");
                                  $fetchpermNum = mysqli_num_rows($checkperm);
                                  
                              ?>
                              <?php
                              if($pgivenName == "id" || $pgivenName == "companyid" || $pgivenName == "urole" || $fetch_mproperties['mname'] == "")
                              {
                                  //Do Nothing
                              }else{
                              ?>
                              <tr>
                                <td>
                                  <b><?php echo $fetch_mproperties['mname']; ?></b>
                                </td>
                                <td>
                                  <?php echo ucfirst(str_replace('_', ' ', $pgivenName)); ?>
                                </td>
                                <td>
                                    <p>
                                    <?php echo ($fetchpermNum == 1) ? '<i style="color: blue;" class="fa fa-check"></i>' : '<i style="color: orange;" class="fa fa-times"></i>'; ?>
                                    </p>
                                </td>
                              </tr>
                              <?php
                              }
                              }
                              ?>
                            </table>
                        </div>
                        </div>
		
            </div>
            
        </div>
        
    </form>
                </div>

				</div>	
				</div>
			
</div>	
					
       
</div>