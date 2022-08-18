<?php
include ("config/connect.php");
$PostType = $_GET['PostType'];

if($PostType == "")
{
?>
        <div class="form-group">
                    <label for="" class="col-sm-2 control-label" style="color:blue;">Message</label>
                    <div class="col-sm-10">
          <textarea name="txtno" class="form-control" rows="4" cols="5">
            <?php
            $search_insti = mysqli_query($link, "SELECT * FROM institution_data ORDER BY id");
            while($get_insti = mysqli_fetch_array($search_insti)){
              echo $get_insti['official_phone'];
            }
            ?>
          </textarea>
              </div>
        </div>

<?php
}
?>