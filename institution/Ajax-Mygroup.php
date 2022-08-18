<?php
include ("../config/session1.php");
$PostType = $_GET['PostType'];

$myaltcall = mysqli_query($link, "SELECT * FROM member_settings WHERE companyid = '$institution_id'");
$myaltrow = mysqli_fetch_array($myaltcall);

if($PostType == "")
{
?>
	<div class="form-group">
                <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Borrower</label>
         <div class="col-sm-10">
                <select name="account" class="select2" style="width: 100%;" required>
        <option value="" selected="selected">--Select Borrower Under Selected Group--</option>
                </select>
              </div>
        </div>	
        
<?php
}
else{
    ?>
    
    <div class="form-group">
                <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Borrower</label>
         <div class="col-sm-10">
                <select name="account" class="select2" style="width: 100%;" required>
        <option value="" selected="selected">--Select Borrower Under Selected Group--</option>
        <?php
        $get = mysqli_query($link, "SELECT * FROM borrowers WHERE gname = '$PostType' AND branchid = '$institution_id' order by id") or die (mysqli_error($link));
        while($rows = mysqli_fetch_array($get))
        {
        echo '<option value="'.$rows['account'].'">'.(($rows['snum'] == "") ? "" : $rows['snum'].' - ').$rows['lname'].' '.$rows['fname'].' - '.$rows['account'].'</option>';
        }
        ?>
                </select>
              </div>
        </div>
    
<?php    
}
?>

<script>
  $(function () {
    //Initialize Select2 Elements
    $(".select2").select2();
  });
</script>