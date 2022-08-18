<?php
$PostType = $_GET['PostType'];

if($PostType == "Client")
{
?>          
        <div class="form-group">
            <label for="" class="col-sm-2 control-label" style="color:blue;">Upload Phone Numbers:</label>
			<div class="col-sm-10">
  		  		<input type='file' name="my_file" class="alert bg-orange" required>
  		  		<div style="color:orange;">Acceptable files are: .csv OR .txt file</div>
	        </div>
		</div>

<?php
}
elseif($PostType == "Personalize")
{
?>

        <div class="form-group">
            <label for="" class="col-sm-2 control-label" style="color:blue;">Recipient Phone No:</label>
            <div class="col-sm-10">
				<textarea name="phone_nos" class="form-control" placeholder="Enter comma seperated phone numbers" rows="4" cols="5" required></textarea>
				<div style="color:orange;">Enter Comma Seperated Phone Numbers</div>
            </div>
        </div>
      
<?php } ?>