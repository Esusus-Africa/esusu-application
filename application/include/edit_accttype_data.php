<div class="box">
	       <div class="box-body">
			<div class="panel panel-success">
            <div class="panel-heading bg-blue">
            <h3 class="panel-title"> <a href="setupaccttype.php?id=<?php echo $_SESSION['tid']; ?>&&mid=<?php echo base64_encode("411"); ?>&&mid=NDEx"><button type="button" class="btn bg-orange"><i class="fa fa-mail-reply-all"></i>&nbsp;Back</button></a> <i class="fa fa-newspaper-o"></i>  Update Account Type</h3>
            </div>

             <div class="box-body">
 <?php
if(isset($_POST['save']))
{
	$idm = $_GET['idm'];
	$acct_type =  mysqli_real_escape_string($link, $_POST['acct_type']);
	$interest_rate =  mysqli_real_escape_string($link, $_POST['interest_rate']);
	$capped_amount =  mysqli_real_escape_string($link, $_POST['capped_amount']);
		
	$insert = mysqli_query($link, "UPDATE account_type SET acct_type = '$acct_type', interest_rate = '$interest_rate', capped_amount = '$capped_amount' WHERE id = '$idm'") or die ("Error: " . mysqli_error($link));
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
                <label for="" class="col-sm-2 control-label" style="color:blue;">Type</label>
				 <div class="col-sm-10">
                <input name="acct_type" type="text" class="form-control" value="<?php echo $fetch_accttype['acct_type']; ?>" placeholder="Account Type" required>
              </div>
			  </div>
				  
			<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Interest</label>
                  <div class="col-sm-10">
                  <input name="interest_rate" type="text" class="form-control" value="<?php echo $fetch_accttype['interest_rate']; ?>" placeholder="Interest FORMAT: 1.0, 0.2, 0.01 etc." required>
                  </div>
                  </div>
				  
			<div class="form-group">
                <label for="" class="col-sm-2 control-label" style="color:blue;">Capped Amount</label>
				 <div class="col-sm-10">
				     <input name="capped_amount" type="number" class="form-control" value="<?php echo $fetch_accttype['capped_amount']; ?>" placeholder="Enter Capped Amount" required>
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