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
    $status = mysqli_real_escape_string($link, $_POST['status']);

    foreach ($_FILES['uploaded_file']['name'] as $key => $name){
        
        $newFilename = $name;
        
        if($newFilename == "")
        {
            echo "";
        }
        else{
            $newlocation = 'document/'.$newFilename;
            if(move_uploaded_file($_FILES['uploaded_file']['tmp_name'][$key], '../application/document/'.$newFilename))
            {
                mysqli_query($link, "INSERT INTO stock_items VALUES(null,'$skuCode','$newlocation','$datetime')") or die (mysqli_error($link));
            }
        }
        
    }
		
	mysqli_query($link, "UPDATE loan_stock SET item_name = '$item_name', amount = '$amount', qty = '$qty', item_desc = '$item_desc', status = '$status' WHERE skuCode = '$skuCode' AND merchantid = '$institution_id'") or die ("Error: " . mysqli_error($link));
	
    echo "<div class='alert bg-blue'>Stock Item Update Successfully!</div>";
}
?>           
			 <form class="form-horizontal" method="post" enctype="multipart/form-data">

<?php
$idm = $_GET['idm'];
$lprd_search = mysqli_query($link, "SELECT * FROM loan_stock WHERE id = '$idm'") or die ("Error: " . mysqli_error($link));
$get_lprd = mysqli_fetch_object($lprd_search);
?>	
             <div class="box-body">
            
            <div class="form-group">
                <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">Item Image</label>
				 <div class="col-sm-6">
                 <input name="uploaded_file[]" type="file" class="btn bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>" multiple/>
                 <hr>
                    <?php
                    $sku_Code = $get_lprd['skuCode'];
                    $i = 0;
                    $search_file = mysqli_query($link, "SELECT * FROM stock_items WHERE skuCode = '$sku_Code'") or die ("Error: " . mysqli_error($link));
                    if(mysqli_num_rows($search_file) == 0){
                    	echo "<span style='color: ".(($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color'])."'>No file attached!!</span>";
                    }else{
                    	while($get_file = mysqli_fetch_array($search_file)){
                    		$i++;
                    ?>
                    <input class="checkbox" name="itemsSelector[]" type="checkbox" value="<?php echo $get_file['id']; ?>"><a href="../application/<?php echo $get_file['item_path']; ?>" target="_blank"><img src="../img/file_attached.png" width="64" height="64"> Picture<?php echo $i; ?></a>
                    <?php
                    	}
                    }
                    ?>
                <hr>
                </div>
                <label for="" class="col-sm-3 control-label"><button name="delItem" type="submit" class="btn bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>"><i class="fa fa-times">&nbsp;Delete</i></button></label>
			  </div>

              <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">SKU Code</label>
                  <div class="col-sm-9">
                  <input name="skuCode" type="text" class="form-control" value="<?php echo $get_lprd['skuCode']; ?>" readonly>
                  </div>
                  </div>
			 			
			<div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Product Name</label>
                  <div class="col-sm-9">
                  <input name="item_name" type="text" class="form-control" value="<?php echo $get_lprd['item_name']; ?>" placeholder="Item Name" required>
                  </div>
                  </div>

            <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Amount</label>
                  <div class="col-sm-9">
                  <input name="amount" type="text" class="form-control" value="<?php echo $get_lprd['amount']; ?>" placeholder="Amount" required>
                  </div>
                  </div>

            <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Quantity</label>
                  <div class="col-sm-9">
                  <input name="qty" type="number" inputmode="numeric" pattern="[0-9]*" class="form-control" value="<?php echo $get_lprd['qty']; ?>" placeholder="Qty" required>
                  </div>
                  </div>

            <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Item Description</label>
                  <div class="col-sm-9">
                    <textarea name="item_desc"  class="form-control" rows="2" cols="80" required><?php echo $get_lprd['item_desc']; ?></textarea>
                  </div>
                  </div>

            <div class="form-group">
                <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">Status</label>
				 <div class="col-sm-9">
                <select name="status" class="select2" style="width: 100%;" required>
				<option value="<?php echo $get_lprd['status']; ?>" selected="selected"><?php echo $get_lprd['status']; ?></option>
					<option value="Available">Available</option>
					<option value="OutOfStock">Out of Stock</option>
                </select>
              </div>
			  </div>
			
			 </div>
			 
			  <div align="right">
              <div class="box-footer">
                	<button name="save" type="submit" class="btn bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>"><i class="fa fa-save">&nbsp;Save</i></button>
              </div>
			  </div>

                <?php
					if(isset($_POST['delItem'])){

						$id=$_POST['itemsSelector'];
						$N = count($id);
                        
						if($id == ''){

						    echo "<script>alert('Row Not Selected!!!'); </script>";	

						}
						else{
							for($i=0; $i < $N; $i++)
							{

                                $searchItem = mysqli_query($link, "SELECT * FROM stock_items WHERE id = '$id[$i]'");
                                $fetchItem = mysqli_fetch_array($searchItem);
                                $item_path = $fetchItem['item_path'];

                                unlink("../application/".$item_path);
								$result = mysqli_query($link,"DELETE FROM loan_stock WHERE id ='$id[$i]'");

								echo "<script>alert('Delete Successfully!!!'); </script>";
								echo "<script>window.location='editStock.php?id=".$_SESSION['tid']."&&mid=NDEx'; </script>";

							}
						}
					}
                ?>
			  
			 </form> 

</div>	
</div>	
</div>
</div>