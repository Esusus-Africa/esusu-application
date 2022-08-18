<?php
include("../incude/session.php");

$select = mysqli_query($link, "SELECT * FROM transaction ORDER BY id DESC") or die (mysqli_error($link));
//$select2 = mysqli_query($link, "SELECT SUM(amount) FROM transaction ORDER BY id DESC") or die (mysqli_error($link));
//$get_select2 = mysqli_fetch_array($select2);
while($row = mysqli_fetch_array($select))
{
$id = $row['id'];
$txid = $row['txid'];
$t_type = $row['t_type'];
$acctno = $row['acctno'];
$transfer_to = $row['transfer_to'];
$ph = $row['phone'];
$amt = $row['amount'];
$dt = $row['date_time'];
$posted_by = $row['posted_by'];
$auname = $row['fn'].' '.$row['ln'];

$select3 = mysqli_query($link, "SELECT name FROM user WHERE id = '$posted_by' ORDER BY id DESC") or die (mysqli_error($link));
$get_select3 = mysqli_fetch_array($select3);

$query = mysqli_query($link, "SELECT * FROM systemset");
$get_query = mysqli_fetch_array($query);
?>    
                <tr>
				<td><input id="optionsCheckbox" class="checkbox"  name="selector[]" type="checkbox" value="<?php echo $id; ?>"></td>
				<td><?php echo $row ['txid'];?></td>
				<td><?php echo $row ['t_type'];?></td>
				<td><?php echo $row ['p_type'];?></td>
                <td><?php echo $acctno; ?></td>
                <td><b><?php echo $auname; ?></b></td>
                <td><?php echo $ph; ?></td>
				<td><?php echo $get_query['currency'].number_format($amt,2,'.',','); ?></td>
				<td><?php echo $dt; ?></td>
				<td><?php echo $get_select3['name']; ?></td>
				<td align="center"><a href="#myModal <?php echo $id; ?>"> <button type="button" class="btn bg-blue btn-flat" data-target="#myModal<?php echo $id; ?>" data-toggle="modal"><i class="fa fa-print"></i> Receipt</button></a></td>
				</tr>
<?php 
}
?>