<?php
include ("../config/session.php");
$PostType = $_GET['PostType'];

if($PostType == "USSD")
{
?>
                <div class="form-group">
                <label for="" class="col-sm-3 control-label" style="color:blue;">Available Terminal:</label>
                <div class="col-sm-6">
                    <select name="terminal"  class="form-control select2" required>
                      <option value="" selected>Select Terminal</option>
                        <?php
                        $search = mysqli_query($link, "SELECT * FROM terminal_reg WHERE terminal_status = 'Available' AND channel = 'USSD' AND visibility = 'Yes' ORDER BY id DESC");
                        while($get_search = mysqli_fetch_array($search))
                        {
                        ?>
                      <option value="<?php echo $get_search['trace_id']; ?>"><?php echo $get_search['trace_id']; ?> - <?php echo $get_search['terminal_issurer']; ?> - Activation Fee: <?php echo $icurrency.number_format($get_search['activation_fee'],2,'.',','); ?></option>
                        <?php } ?>
                    </select>
                </div>
                <label for="" class="col-sm-3 control-label"></label>
                </div>
<?php
}
elseif($PostType == "POS")
{
?>
                <div class="form-group">
                <label for="" class="col-sm-3 control-label" style="color:blue;">Available Terminal:</label>
                <div class="col-sm-6">
                    <select name="terminal"  class="form-control select2" required>
                      <option value="" selected>Select Terminal</option>
                        <?php
                        $search = mysqli_query($link, "SELECT * FROM terminal_reg WHERE terminal_status = 'Available' AND channel = 'POS' AND visibility = 'Yes' ORDER BY id DESC");
                        while($get_search = mysqli_fetch_array($search))
                        {
                        ?>
                      <option value="<?php echo $get_search['terminal_id']; ?>"><?php echo $get_search['terminal_id']; ?> - <?php echo $get_search['terminal_issurer']; ?> - Activation Fee: <?php echo $icurrency.number_format($get_search['activation_fee'],2,'.',','); ?></option>
                        <?php } ?>
                    </select>
                </div>
                <label for="" class="col-sm-3 control-label"></label>
                </div>

<?php
}else{
    //Do nothing
}
?>