<?php
include ("../config/session1.php");

$PostType = $_GET['PostType'];

$myaltcall = mysqli_query($link, "SELECT * FROM member_settings WHERE companyid = '$institution_id'");
$myaltrow = mysqli_fetch_array($myaltcall);

if($PostType == "Yes"){
?>

        <input name="evnNumber" type="hidden" class="form-control" value="">

<?php
}
elseif($PostType == "No"){
?>

        <div class="form-group">
            <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">New EVN:</label>
            <div class="col-sm-6">
                <?php
                $evn = date("y").time();
                $search_customer = mysqli_query($link, "SELECT * FROM borrowers WHERE evn = '$evn'");
                $real_evn = (mysqli_num_rows($search_customer) == 0) ? $evn : date("y").rand(1000000000,9999999999);
                ?>
                <input name="evnNumber" type="text" class="form-control" value="<?php echo $real_evn; ?>" /readonly>
            </div>
            <label for="" class="col-sm-3 control-label"></label>
        </div>


<?php
}
else{
    echo "";
}
?>