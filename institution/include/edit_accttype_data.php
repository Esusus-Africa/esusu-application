<div class="box">
	       <div class="box-body">
			<div class="panel panel-success">
            <div class="panel-heading bg-blue">
            <h3 class="panel-title"> <a href="setupaccttype.php?id=<?php echo $_SESSION['tid']; ?>&&mid=<?php echo base64_encode("411"); ?>&&mid=NDEx"><button type="button" class="btn bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>"><i class="fa fa-mail-reply-all"></i>&nbsp;Back</button></a> <i class="fa fa-newspaper-o"></i>  Update Account Type</h3>
            </div>

             <div class="box-body">
 <?php
if(isset($_POST['save']))
{
	$idm = $_GET['idm'];
	$acct_type = mysqli_real_escape_string($link, $_POST['acct_type']);
	$acct_name =  mysqli_real_escape_string($link, $_POST['acct_name']);
	$interest_rate =  preg_replace("/[^0-9-.\s]/", "", mysqli_real_escape_string($link, $_POST['interest_rate']));
	$tenor = mysqli_real_escape_string($link, $_POST['tenor']);
	$opening_balance =  preg_replace("/[^0-9\s]/", "", mysqli_real_escape_string($link, $_POST['opening_balance']));
		
	$insert = mysqli_query($link, "UPDATE account_type SET account_type = '$acct_type', acct_name = '$acct_name', interest_rate = '$interest_rate', tenor = '$tenor', opening_balance = '$opening_balance' WHERE id = '$idm'") or die ("Error: " . mysqli_error($link));
	
	if(!$insert)
	{
		echo "<div class='alert bg-orange'>Unable to Update Account Type.....Please try again later</div>";
	}
	else{
		echo "<div class='alert bg-blue'>Account Type Update Successfully!</div>";
	}
}
?>           
			 <form class="form-horizontal" method="post" enctype="multipart/form-data">

             <div class="box-body">
<?php
$idm = $_GET['idm'];
$search_accttype = mysqli_query($link, "SELECT * FROM account_type WHERE id = '$idm'");
$fetch_accttype = mysqli_fetch_array($search_accttype)
?>			

            <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Account Type</label>
                  <div class="col-sm-10">
                  <select name="acct_type"  class="form-control select2" required>
						<option value="<?php echo $fetch_accttype['account_type']; ?>" selected><?php echo $fetch_accttype['account_type']; ?></option>
						<option value="Regular">Regular</option>
						<option value="Fixed">Fixed</option>
					</select>
                  </div>
            </div>
            
			<div class="form-group">
                <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Account Name</label>
				 <div class="col-sm-10">
                <input name="acct_name" type="text" class="form-control" value="<?php echo $fetch_accttype['acct_name']; ?>" placeholder="Account Name e.g. Savings Account, Current Account, Daily Contribution Account etc." required>
              </div>
			  </div>
				  
			<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Interest</label>
                  <div class="col-sm-10">
                  <input name="interest_rate" type="text" class="form-control" value="<?php echo $fetch_accttype['interest_rate']; ?>" placeholder="Interest FORMAT: 1.0, 0.2, 0.01 etc." required>
                  </div>
                  </div>
                  
            <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Tenor</label>
                  <div class="col-sm-10">
                  <select name="tenor" class="form-control select2" required>
						<option value="<?php echo $fetch_accttype['tenor']; ?>" selected><?php echo $fetch_accttype['tenor']; ?></option>
						<option value="None">None</option>
						<option value="Annually">Annually</option>
						<option value="30days">30days</option>
						<option value="60days">60days</option>
						<option value="90days">90days</option>
						<option value="120days">120days</option>
						<option value="180days">180days</option>
					</select>
                  </div>
                  </div>
                  
            <div class="form-group">
                <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Min. Opening Balance</label>
				 <div class="col-sm-10">
				     <input name="opening_balance" type="number" class="form-control" value="<?php echo $fetch_accttype['opening_balance']; ?>" placeholder="Enter Minimum Opening Balance" required>
              </div>
			  </div>
			
			 </div>
			 
			  <div align="right">
              <div class="box-footer">
                <button name="save" type="submit" class="btn bg-blue btn-flat"><i class="fa fa-save">&nbsp;Save</i></button>
              </div>
			  </div>
			  
			 </form> 

</div>	
</div>	
</div>
</div>