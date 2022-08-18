<div class="modal modal-default" id="b" role="dialog">
    <div class="modal-dialog modal-lg">
    
      <!-- Modal content-->
      <div id="printarea">
	  <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
         <strong> <h4 class="modal-title" align="center">Insert Into Wallet</h4></strong></div>
        <div class="modal-body">
		    <form class="form-horizontal" method="post" enctype="multipart/form-data">
			 <div class="box-body">
			
			<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:#009900">Amount</label>
                  <div class="col-sm-10">
                  <input name="amt" type="number" class="form-control" placeholder="Amount" required>
                  </div>
                  </div>
				  
				   <div class="form-group">
                  	<label for="" class="col-sm-2 control-label" style="color:#009900">Description</label>
                  	<div class="col-sm-10">
					<textarea name="desc"  class="form-control" rows="4" cols="80"></textarea>
           		</div>
				</div>
				
				<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:#009900">Wallet Type</label>
                  <div class="col-sm-10">
				<select name="wtype"  class="form-control" required>
										<option value="debit">debit</option>
										<option value="credit">credit</option>
										</select>
										</div>
										</div>
										
										
		</div>

		<hr>
		<div align="right">
  		 <button type="submit" class="btn btn-success btn-flat" name="save"><i class="fa fa-save"></i>&nbsp;Save</button>
		<button type="button" class="btn btn-danger btn-flat" data-dismiss="modal">&nbsp;Close</button>
<?php
if(isset($_POST['save']))
{
	try{
		$tid = $_SESSION['tid'];
		$amt = mysqli_real_escape_string($link, $_POST['amt']);
		$desc = mysqli_real_escape_string($link, $_POST['desc']);
		$wtype = mysqli_real_escape_string($link, $_POST['wtype']);
		
		if($amt < 0){
				throw new UnexpectedValueException();
		}
		elseif($wtype == "debit")//This region works to add some amount to your wallet
		{
		$select = mysqli_query($link, "SELECT Total FROM twallet WHERE tid = '$tid'") or die (mysqli_error($link));
		if(mysqli_num_rows($select)==0)
		{
		$insert1 = mysqli_query($link, "INSERT INTO twallet VALUES('','$tid','$amt')") or die (mysqli_error($link));
		$insert2 = mysqli_query($link, "INSERT INTO mywallet VALUES('','$tid','NIL','$amt','$desc','$wtype','',NOW())") or die (mysqli_error($link));

		echo "<script>alert('You have successfully added New Payment!'); </script>";
		echo "<script>window.location='mywallet.php?tid=".$_SESSION['tid']."'; </script>";
		}
		else{
		$row3 = mysqli_fetch_array($select);
		$Get_amt3 = $row3['Total'];
		$Total =  $Get_amt3 + $amt;
		$update3 = mysqli_query($link, "UPDATE twallet SET Total='$Total' WHERE tid = '$tid'") or die (mysqli_error($link));
		$insert1 = mysqli_query($link, "INSERT INTO mywallet VALUES('','$tid','NIL','$amt','$desc','$wtype','',NOW())") or die (mysqli_error($link));
		if(!($update3 && $insert1))
		{
		echo "<script>alert('Record not inserted!....Please try again later!!'); </script>";
		echo "<script>window.location='mywallet.php?tid=".$_SESSION['tid']."'; </script>";
		}
		else{
		echo "<script>alert('You have successfully added New Payment!'); </script>";
		echo "<script>window.location='mywallet.php?tid=".$_SESSION['tid']."'; </script>";
		}
		}
		}
		elseif($wtype == "credit")//This region works to deduct some amount from your wallet
		{
		$select2 = mysqli_query($link, "SELECT Total FROM twallet WHERE tid = '$tid'") or die (mysqli_error($link));
		$row = mysqli_fetch_array($select2);
		$Get_amt = $row['Total'];
		$getTotal = $Get_amt - $amt;
		if(mysqli_num_rows($select2)==0)
		{
			echo "<script>alert('Sorry, You don't have enough balance in your wallet!!'); </script>";
			echo "<script>window.location='mywallet.php?tid=".$_SESSION['tid']."'; </script>";
		}
		elseif($getTotal < 0){
			throw new UnexpectedValueException();
		}
		else{
		$update = mysqli_query($link, "UPDATE twallet SET Total = '$getTotal' WHERE tid = '$tid'") or die (mysqli_error($link));
		$insert = mysqli_query($link, "INSERT INTO mywallet VALUES('','$tid','NIL','$amt','$desc','$wtype','',NOW())") or die (mysqli_error($link));
		if(!($update && $insert))
		{
		echo "<script>alert('Fund Not Credited.....Please try again later!'); </script>";
		echo "<script>window.location='mywallet.php?tid=".$_SESSION['tid']."'; </script>";
		}
		else{
		echo "<script>alert('Your Fund has been credited successfully!'); </script>";
		echo "<script>window.location='mywallet.php?tid=".$_SESSION['tid']."'; </script>";
		}
		}
		}
	}catch(UnexpectedValueException $ex)
	{
		echo "<script>alert('Invalid Amount Entered! OR No Enough Funds in Wallet!!'); </script>";
	}
}
?>
		</form> 
		</div>
        </div>
      </div>
    </div>
	</div>
  </div>
  
 
  
  <div class="modal modal-default" id="c" role="dialog">
    <div class="modal-dialog modal-lg">
    
      <!-- Modal content-->
      <div id="printarea">
	  <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
         <strong> <h4 class="modal-title" align="center">Money Transfer</h4></strong></div>
        <div class="modal-body">
		    <form class="form-horizontal" method="post" enctype="multipart/form-data">
			 <div class="box-body">
			
			<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:#009900">Amount</label>
                  <div class="col-sm-10">
                  <input name="amount" type="number" class="form-control" placeholder="Amount" required>
                  </div>
                  </div>
				  
				   <div class="form-group">
                  	<label for="" class="col-sm-2 control-label" style="color:#009900">Description</label>
                  	<div class="col-sm-10">
					<textarea name="desc"  class="form-control" rows="4" cols="80"></textarea>
           		</div>
				</div>
				
				<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:#009900">Staff</label>
                  <div class="col-sm-10">
				<select name="transfer_to"  class="form-control" required style="width:100%">
				<option value="" selected="selected" required>Select Staff to Transfer to</option>
				<?php
				$get = mysqli_query($link, "SELECT * FROM user WHERE id != '$tid'") or die (mysqli_error($link));
				while($rows = mysqli_fetch_array($get))
				{
				?>
				<option value="<?php echo $rows['id']; ?>"><?php echo $rows['name']; ?></option>
				<?php } ?>				
				</select>
										</div>
										</div>
										
										
		</div>
			
		
		<hr>
		<div align="right">
  		 <button type="submit" class="btn btn-success btn-flat" name="transfer"><i class="fa fa-save"></i>&nbsp;Transfer</button>
		<button type="button" class="btn btn-danger btn-flat" data-dismiss="modal">&nbsp;Close</button>
        </div>
<?php
if(isset($_POST['transfer']))
{
	try{
		$tid = $_SESSION['tid'];
		$amount =  mysqli_real_escape_string($link, $_POST['amount']);
		$desc = mysqli_real_escape_string($link, $_POST['desc']);
		$wtype = "transfer";
		$transfer_to = mysqli_real_escape_string($link, $_POST['transfer_to']);
		$search_staff = mysqli_query($link, "SELECT * FROM user WHERE id = '$transfer_to'");
		$get_staff = mysqli_fetch_array($search_staff);
		$branchid = $get_staff['branchid'];
		
		$serach_branch = mysqli_query($link, "SELECT * FROM branches WHERE branchid = '$branchid'");
		$get_branch = mysqli_fetch_array($serach_branch);
		if($serach_branch == 1)
		{
			if($amount < 0){
					throw new UnexpectedValueException();
			}
			else{
			$slec = mysqli_query($link, "SELECT * FROM twallet WHERE tid = '$transfer_to'") or die (mysqli_error($link));
			$have = mysqli_fetch_array($slec);

			$slecin = mysqli_query($link, "SELECT * FROM twallet WHERE tid = '$tid'") or die (mysqli_error($link));
			$havein = mysqli_fetch_array($slecin);
			$Total = $havein['Total'];
			$Balance = $Total - $amount;
			
			$convert_amt = $amount * $get_branch['c_rate'];
			
			if($Total < $amount)
			{
				echo "<script>alert('Sorry! Insufficient Fund Detected!!'); </script>";
				echo "<script>window.location='mywallet.php?tid=".$_SESSION['tid']."'; </script>";
			}
			elseif(mysqli_num_rows($slec)==0)
			{
			//Deduct Money from Sender Wallet
			$update2 = mysqli_query($link, "UPDATE twallet SET Total = '$Balance', branchid = '$branchid' WHERE tid = '$tid'") or die (mysqli_error($link));
			//Add Money to Receiver Wallet
			$insert2 = mysqli_query($link, "INSERT INTO twallet VALUES('','$transfer_to','$convert_amt')") or die (mysqli_error($link));
			//Insert to wallet transaction list (Keeping Track all Transaction)
			$insert2 = mysqli_query($link, "INSERT INTO mywallet VALUES('','$tid','$transfer_to','$amount','$desc','$wtype','$branchid',NOW())") or die (mysqli_error($link));

			echo "<script>alert('Fund Transfered Successfully!'); </script>";
			echo "<script>window.location='mywallet.php?tid=".$_SESSION['tid']."'; </script>";
			}
			else{
			$update1 = mysqli_query($link, "UPDATE twallet SET Total = '$Balance' WHERE tid = '$tid'") or die (mysqli_error($link));
			$insert = mysqli_query($link, "INSERT INTO mywallet VALUES('','$tid','$transfer_to','$amount','$desc','$wtype','$branchid' ,NOW())") or die (mysqli_error($link));
			$slect = mysqli_query($link, "SELECT * FROM twallet WHERE tid = '$transfer_to'") or die (mysqli_error($link));
			$haveit = mysqli_fetch_array($slect);
			$Totalit = $haveit['Total'];
			$Balanceit = $Totalit + $convert_amt;
			$update1 = mysqli_query($link, "UPDATE twallet SET Total = '$Balanceit', branchid = '$branchid' WHERE tid = '$transfer_to'") or die (mysqli_error($link));
			if(!($update1 && $insert))
			{
			echo "<script>alert('Fund Not Transfer.....Please try again later!'); </script>";
			echo "<script>window.location='mywallet.php?tid=".$_SESSION['tid']."'; </script>";
			}
			else{
			echo "<script>alert('Fund Transfered Successfully!'); </script>";
			echo "<script>window.location='mywallet.php?tid=".$_SESSION['tid']."'; </script>";
			}
			}
			}
		}
		else{
			if($amount < 0){
					throw new UnexpectedValueException();
			}
			else{
			$slec = mysqli_query($link, "SELECT * FROM twallet WHERE tid = '$transfer_to'") or die (mysqli_error($link));
			$have = mysqli_fetch_array($slec);

			$slecin = mysqli_query($link, "SELECT * FROM twallet WHERE tid = '$tid'") or die (mysqli_error($link));
			$havein = mysqli_fetch_array($slecin);
			$Total = $havein['Total'];
			$Balance = $Total - $amount;
			if($Total < $amount)
			{
				echo "<script>alert('Sorry! Insufficient Fund Detected!!'); </script>";
				echo "<script>window.location='mywallet.php?tid=".$_SESSION['tid']."'; </script>";
			}
			elseif(mysqli_num_rows($slec)==0)
			{
			//Deduct Money from Sender Wallet
			$update2 = mysqli_query($link, "UPDATE twallet SET Total = '$Balance' WHERE tid = '$tid'") or die (mysqli_error($link));
			//Add Money to Receiver Wallet
			$insert2 = mysqli_query($link, "INSERT INTO twallet VALUES('','$transfer_to','$amount')") or die (mysqli_error($link));
			//Insert to wallet transaction list (Keeping Track all Transaction)
			$insert2 = mysqli_query($link, "INSERT INTO mywallet VALUES('','$tid','$transfer_to','$amount','$desc','$wtype','',NOW())") or die (mysqli_error($link));

			echo "<script>alert('Fund Transfered Successfully!'); </script>";
			echo "<script>window.location='mywallet.php?tid=".$_SESSION['tid']."'; </script>";
			}
			else{
			$update1 = mysqli_query($link, "UPDATE twallet SET Total = '$Balance' WHERE tid = '$tid'") or die (mysqli_error($link));
			$insert = mysqli_query($link, "INSERT INTO mywallet VALUES('','$tid','$transfer_to','$amount','$desc','$wtype','',NOW())") or die (mysqli_error($link));
			$slect = mysqli_query($link, "SELECT * FROM twallet WHERE tid = '$transfer_to'") or die (mysqli_error($link));
			$haveit = mysqli_fetch_array($slect);
			$Totalit = $haveit['Total'];
			$Balanceit = $Totalit + $amount;
			$update1 = mysqli_query($link, "UPDATE twallet SET Total = '$Balanceit' WHERE tid = '$transfer_to'") or die (mysqli_error($link));
			if(!($update1 && $insert))
			{
			echo "<script>alert('Fund Not Transfer.....Please try again later!'); </script>";
			echo "<script>window.location='mywallet.php?tid=".$_SESSION['tid']."'; </script>";
			}
			else{
			echo "<script>alert('Fund Transfered Successfully!'); </script>";
			echo "<script>window.location='mywallet.php?tid=".$_SESSION['tid']."'; </script>";
			}
			}
			}
		}
		
	}catch(UnexpectedValueException $ex)
	{
		echo "<script>alert('Invalid Amount Entered!!'); </script>";
	}
}
?>
		</form> 
		</div>
      </div>
    </div>
	</div>
  </div>
  
  
 <?php
$tid = $_SESSION['tid'];
$select = mysqli_query($link, "SELECT * FROM mywallet") or die (mysqli_error($link));
while($row = mysqli_fetch_array($select))
{
$id = $row['id'];
$amt = $row['Amount'];
$desc = $row['Desc'];
$wtype = $row['wtype'];
$tdate = $row['tdate'];
?>    
<form class="form-horizontal" method="post" enctype="multipart/form-data">
 <div class="modal modal-danger" id="d<?php echo $id;?>" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div id="printarea">
	  <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" style=" color:#FFFFFF">&times;</button>
         <strong> <h4 class="modal-title" style="color:#FFFFFF"align="center">Transaction Confirmation</h4></strong>
        </div>
        <div class="modal-body">
		  
			<div align="center" style="color: #FFFFFF"<strong>Are you sure you want to reverse the transaction selected&nbsp;?</strong></div>
		<hr>
  		 <a href="del_wallet.php?id=<?php echo $id; ?>&&wtype=<?php echo $wtype; ?>"><button type="button" class="btn btn-info btn-flat  btn-outline" ><i class="fa fa-trash"></i>Yes</button></a>
		<button type="button" class="btn btn-danger btn-flat btn-outline" data-dismiss="modal">No</button>
        </div>
      </div>
      
    </div>
	</div>
  </div>
</form>
 <?php } ?>