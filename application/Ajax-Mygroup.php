<?php
include ("../config/connect.php");
$PostType = $_GET['PostType'];
if($PostType == "")
{
?>
	<div class="form-group">
                <label for="" class="col-sm-2 control-label" style="color:blue;">Borrower</label>
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
                <label for="" class="col-sm-2 control-label" style="color:blue;">Borrower</label>
         <div class="col-sm-10">
                <select name="account" class="customer" style="width: 100%;" required>
        <option value="" selected="selected">--Select Borrower Under Selected Group--</option>
        <?php
        $get = mysqli_query($link, "SELECT * FROM borrowers WHERE gname = '$PostType' order by id") or die (mysqli_error($link));
        while($rows = mysqli_fetch_array($get))
        {
        echo '<option value="'.$rows['account'].'">'.$rows['lname'].' '.$rows['fname'].' - '.$rows['account'].'</option>';
        }
        ?>
                </select>
              </div>
        </div>
    
<?php    
}
?>