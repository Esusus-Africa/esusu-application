<?php
include ("../config/session.php");
$PostType = $_GET['PostType'];

if($PostType != "")
{
    $searchTerminal = mysqli_query($link, "SELECT * FROM terminal_reg WHERE terminal_serial = '$PostType'");
    $fetchTerminal = mysqli_fetch_array($searchTerminal);
?>

            <div class="form-group">
                <label for="" class="col-sm-3 control-label" style="color:blue;">Logo:</label>
                <div class="col-sm-6">
                  <input name="logo" type="text" class="form-control" value="<?php echo $fetchTerminal['custom_logo']; ?>" placeholder="Enter Logo URL" required>
                  <span style="color: orange;"> <b>NOTE: Logo must be in .bmp format</span>
                  </div>
                <label for="" class="col-sm-3 control-label"></label>
            </div>

            <div class="form-group">
                <label for="" class="col-sm-3 control-label" style="color:blue;">Merchant Name:</label>
                <div class="col-sm-6">
                  <input name="merchantname" type="text" class="form-control" value="<?php echo $fetchTerminal['custom_merchantname']; ?>" placeholder="Enter Merchant Name" required>
                  </div>
                <label for="" class="col-sm-3 control-label"></label>
            </div>

            <div class="form-group">
                <label for="" class="col-sm-3 control-label" style="color:blue;">Address:</label>
                <div class="col-sm-6">
                  <input name="address" type="text" class="form-control" value="<?php echo $fetchTerminal['custom_address']; ?>" placeholder="Enter Address" required>
                  </div>
                <label for="" class="col-sm-3 control-label"></label>
            </div>
            
            <div class="form-group">
                <label for="" class="col-sm-3 control-label" style="color:blue;">Phone:</label>
                <div class="col-sm-6">
                  <input name="phone" type="text" class="form-control" value="<?php echo $fetchTerminal['custom_phone']; ?>" placeholder="Enter Phone Number" required>
                  </div>
                <label for="" class="col-sm-3 control-label"></label>
            </div>

            <div class="form-group">
                <label for="" class="col-sm-3 control-label" style="color:blue;">App. Name:</label>
                <div class="col-sm-6">
                  <input name="appname" type="text" class="form-control" value="<?php echo $fetchTerminal['custom_appname']; ?>" placeholder="Enter Appname e.g Powered by esusu.africa" required>
                  </div>
                <label for="" class="col-sm-3 control-label"></label>
            </div>

            <div class="form-group">
                <label for="" class="col-sm-3 control-label" style="color:blue;">App. Version:</label>
                <div class="col-sm-6">
                  <input name="appversion" type="text" class="form-control" value="<?php echo $fetchTerminal['custom_appversion']; ?>" placeholder="Enter App Version e.g. v1" required>
                  </div>
                <label for="" class="col-sm-3 control-label"></label>
            </div>

            <div class="form-group">
                <label for="" class="col-sm-3 control-label" style="color:blue;">App. URL:</label>
                <div class="col-sm-6">
                  <input name="appurl" type="text" class="form-control" value="<?php echo $fetchTerminal['custom_appurl']; ?>" placeholder="Enter App URL e.g. www.esusu.africa" required>
                  </div>
                <label for="" class="col-sm-3 control-label"></label>
            </div>
			
			    <div class="form-group">
                <label for="" class="col-sm-3 control-label" style="color:blue;">App. Phone:</label>
                <div class="col-sm-6">
                  <input name="appphone" type="text" class="form-control" value="<?php echo $fetchTerminal['custom_appphone']; ?>" placeholder="Enter App Phone" required>
                  </div>
                <label for="" class="col-sm-3 control-label"></label>
            </div>

<?php
}else{

    //DO NOTHING

}
?>