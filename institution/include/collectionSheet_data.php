<div class="row">
	       
		     <section class="content">  
	        <div class="box box-success">
            <div class="box-body">
              <div class="table-responsive">
             <div class="box-body">
             
<form class="form-horizontal" method="post" enctype="multipart/form-data">

<?php
if(isset($_POST['generate_pdf']))
{
    $filter_by = mysqli_real_escape_string($link, $_POST['filter_by']);

	echo "<script>window.open('../pdf/view/pdf_collectionSheet.php?sid=".$filter_by."&&instid=".$institution_id."', '_blank'); </script>";
}
?>

			<div class="box-body">

				<div class="form-group">

					<label for="" class="col-sm-1 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Filter By</label>
					<div class="col-sm-4">
					<select name="filter_by" class="form-control select2" style="width:100%" require>
                        <option value="" selected="selected">Filter By...</option>
                        <!-- FILTER BY ALL  -->
                        <option disabled>Filter By Staff / Sub-agent</option>
                        <?php
                        ($list_employee === "1" && $list_branch_employee != "1") ? $get2 = mysqli_query($link, "SELECT * FROM user WHERE created_by = '$institution_id' ORDER BY userid DESC") or die (mysqli_error($link)) : "";
                        ($list_employee != "1" && $list_branch_employee === "1") ? $get2 = mysqli_query($link, "SELECT * FROM user WHERE created_by = '$institution_id' AND branchid = '$isbranchid' ORDER BY userid DESC") or die (mysqli_error($link)) : "";
                        while($rows2 = mysqli_fetch_array($get2))
                        {
                        ?>
                        <option value="<?php echo $rows2['id']; ?>"><?php echo $rows2['name'].' '.$rows2['fname'].' '.$rows2['mname']; ?></option>
                        <?php } ?>
                        ?>
				    </select>
					</div>

                    <label for="" class="col-sm-1 control-label"></label>
					<div class="col-sm-3">
					    <button type="submit" class="btn bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>" name="generate_pdf"><i class="fa fa-print"> Print Collection Sheet</i></button>
					</div>

				</div>

			</div>

</form>

					</div>
</div>	
</div>			
</div>	
</div>


