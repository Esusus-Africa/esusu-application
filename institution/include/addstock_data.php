<div class="box">
	       <div class="box-body">
			<div class="panel panel-success">
            <div class="panel-heading bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">
            <h3 class="panel-title"> <a href="setupstock.php?id=<?php echo $_SESSION['tid']; ?>&&mid=NDEx"><button type="button" class="btn btn-flat bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>"><i class="fa fa-mail-reply-all"></i>&nbsp;Back</button></a> <i class="fa fa-newspaper-o"></i>  Add New Stock</h3>
            </div>

             <div class="box-body">
<?php
if(isset($_POST['save']))
{
	$skuCode = mysqli_real_escape_string($link, $_POST['skuCode']);
	$item_name = mysqli_real_escape_string($link, $_POST['item_name']);
    $amount = preg_replace('/[^0-9.]/', '', mysqli_real_escape_string($link, $_POST['amount']));
	$qty = preg_replace('/[^0-9]/', '', mysqli_real_escape_string($link, $_POST['qty']));
    $item_desc = mysqli_real_escape_string($link, $_POST['item_desc']);
    $datetime = date("Y-m-d");

    foreach ($_FILES['uploaded_file']['name'] as $key => $name){
        
        $newFilename = $name;
        
        if($newFilename == "")
        {
            echo "";
        }
        else{
            $newlocation = $newFilename;
            if(move_uploaded_file($_FILES['uploaded_file']['tmp_name'][$key], '../img/'.$newFilename))
            {
                mysqli_query($link, "INSERT INTO stock_items VALUES(null,'$skuCode','$newlocation','$datetime')") or die (mysqli_error($link));
            }
        }
        
    }
		
	mysqli_query($link, "INSERT INTO loan_stock VALUES(null,'$institution_id','$skuCode','$item_name','$amount','$qty','$item_desc','Available','$datetime')") or die ("Error: " . mysqli_error($link));
	
    echo "<div class='alert bg-blue'>New Stock Added Successfully!</div>";
}
?>           
			 <form class="form-horizontal" method="post" enctype="multipart/form-data">

             <div class="box-body">
            
            <div class="form-group">
                <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">Item Image</label>
				 <div class="col-sm-9">
                 <input name="uploaded_file[]" type="file" class="btn bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>" multiple/>
              </div>
			  </div>

              <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">SKU Code</label>
                  <div class="col-sm-9">
                  <input name="skuCode" type="text" class="form-control" value="<?php echo strtoupper(random_strings(8)); ?>" readonly>
                  </div>
                  </div>
			
			<div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Product Name</label>
                  <div class="col-sm-9">
                  <input name="item_name" type="text" class="form-control" placeholder="Item Name" required>
                  </div>
                  </div>

            <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Amount</label>
                  <div class="col-sm-9">
                  <input name="amount" type="text" class="form-control" placeholder="Amount" required>
                  </div>
                  </div>

            <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Quantity</label>
                  <div class="col-sm-9">
                  <input name="qty" type="number" inputmode="numeric" pattern="[0-9]*" class="form-control" placeholder="Qty" required>
                  </div>
                  </div>

            <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Item Description</label>
                  <div class="col-sm-9">
                    <textarea name="item_desc"  class="form-control" rows="2" cols="80" required></textarea>
                  </div>
                  </div>
			
			 </div>
			 
			  <div align="right">
              <div class="box-footer">
                	<button name="save" type="submit" class="btn bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>"><i class="fa fa-save">&nbsp;Save</i></button>
              </div>
			  </div>
			  
			 </form> 

</div>	
</div>	
</div>
</div>