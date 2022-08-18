<?php
include("../config/session1.php");

$lide = $_GET['lid'];
$select = mysqli_query($link, "SELECT * FROM loan_required_doc WHERE lid = '$lide' ORDER BY id DESC");
if(mysqli_num_rows($select)==0)
{
    echo "<div class='alert bg-".(($myaltrow['theme_color'] == "") ? 'blue' : $myaltrow['theme_color'])."'>No data found yet!.....Check back later!!</div>".$lide;
}
else{
    while($row = mysqli_fetch_array($select))
    {
        $id = $row['id'];
?>    
        <tr>
            <td><input id="optionsCheckbox" class="checkbox" name="selector[]" type="checkbox" value="<?php echo $id; ?>" <?php echo ($row['docStatus'] == "Approved") ? "disabled" : ""; ?>></td>
            <td><?php echo $row['docType']; ?></td>
            <td><a href="<?php echo $fetchsys_config['file_baseurl'].$row['docFile']; ?>" class="btn bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>" target="blank"><i class="fa fa-search"></i> View <?php echo $row['docType']; ?></a></td>
    		<td><?php echo ($row['expDate'] == "") ? "NONE" : $row['expDate']; ?></td>
    		<td><?php echo ($row['docStatus'] == "Approved" ? "<span class='label bg-blue'><i class='fa fa-check'></i>Approved</span>" : ($row['docStatus'] == "Pending" ? "<span class='label bg-orange'><i class='fa fa-exclamation'></i>Pending</span>" : "<span class='label bg-red'><i class='fa fa-times'></i>Declined</span>")); ?></td>
    	    <td><?php echo $row['dateCreated']; ?></td>
    		<td><?php echo $row['dateUpdated']; ?></td>
        </tr>
<?php 
    } 
}
?>