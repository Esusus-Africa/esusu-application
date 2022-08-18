<div class="box">
	       <div class="box-body">
			<div class="panel panel-success">
            <div class="panel-heading bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">
            <h3 class="panel-title"><i class="fa fa-exchange"></i> Create Group</h3>
            </div>
             <div class="box-body">
            
			 <form class="form-horizontal" method="post" enctype="multipart/form-data">

             <div class="box-body">

            <div class="form-group">
                <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Collection Channel</label>
                <div class="col-sm-6">
                <select name="cmode"  class="form-control select2" required>
					<option value="" selected>Select Collection Channel</option>
                    <option value="Wallet">Wallet</option>
				</select>
                </div>
                <label for="" class="col-sm-3 control-label"></label>
            </div>

            <div class="form-group">
                <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Disbursement Channel</label>
                <div class="col-sm-6">
                <select name="dmode"  class="form-control select2" required>
					<option value="" selected>Select Disbursement Channel</option>
                    <option value="Wallet">Wallet</option>
                    <option value="Bank">Bank Account</option>
				</select>
                </div>
                <label for="" class="col-sm-3 control-label"></label>
            </div>

            <div class="form-group">
                <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Group Name</label>
                <div class="col-sm-6">
                    <input name="gname" type="text" class="form-control" placeholder="Enter Group Name" required/>
                </div>
                <label for="" class="col-sm-3 control-label"></label>
            </div>

            <div class="form-group">
                <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Member Limit</label>
                <div class="col-sm-6">
                    <input name="glimit" type="number" class="form-control" placeholder="Enter Member Limit" required/>
                </div>
                <label for="" class="col-sm-3 control-label"></label>
            </div>

            <div class="form-group">
                <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Amount</label>
                <div class="col-sm-6">
                    <input name="amount" type="number" class="form-control" placeholder="Enter Amount" required/>
                </div>
                <label for="" class="col-sm-3 control-label"></label>
            </div>

            <div class="form-group">
                <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Cluster</label>
                <div class="col-sm-6">
                    <input name="cluster" type="text" class="form-control" placeholder="Enter Cluster e.g A00, BC00 etc." required/>
                </div>
                <label for="" class="col-sm-3 control-label"></label>
            </div>

            <div class="form-group">
                <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Marital Status</label>
                <div class="col-sm-6">
                    <select name="mstatus"  class="form-control select2" required>
                        <option value="" selected>Select Marital Status</option>
                        <option value="Single">Single</option>
                        <option value="Married">Married</option>
                        <option value="Divorce">Divorce</option>
                    </select>
                </div>
                <label for="" class="col-sm-3 control-label"></label>
            </div>

            <div class="form-group">
                <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Collection Interval</label>
                <div class="col-sm-6">
                    <select name="cinterval"  class="form-control select2" required>
                        <option value="" selected>Select Collection Interval</option>
                        <option value="daily">Daily</option>
                        <option value="weekly">weekly</option>
                        <option value="monthly">monthly</option>
                    </select>
                </div>
                <label for="" class="col-sm-3 control-label"></label>
            </div>

            <div class="form-group">
                <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Collection Duration</label>
                <div class="col-sm-6">
                    <input name="cduration" type="number" class="form-control" placeholder="Enter Contribution Duration" required/>
                    <span style="color:<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>">i.e If daily is selected and you input 30 in duration field, it means the fund will be contributed 30 times on daily basis</span>
                </div>
                <label for="" class="col-sm-3 control-label"></label>
            </div>

            <div class="form-group">
                <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Disbursement Interval</label>
                <div class="col-sm-6">
                    <select name="dinterval"  class="form-control select2" required>
                        <option value="" selected>Select Disbursement Interval</option>
                        <option value="daily">Daily</option>
                        <option value="weekly">weekly</option>
                        <option value="monthly">monthly</option>
                    </select>
                </div>
                <label for="" class="col-sm-3 control-label"></label>
            </div>

            <div class="form-group">
                <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Start Date</label>
                <div class="col-sm-6">
                    <input name="startDate" type="date" class="form-control" placeholder="Start Date" required/>
                </div>
                <label for="" class="col-sm-3 control-label"></label>
            </div>

            <div class="form-group">
                <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Transaction Pin</label>
                <div class="col-sm-6">
                    <input name="tpin" type="password" class="form-control" placeholder="Your Transaction Pin" required/>
                </div>
                <label for="" class="col-sm-3 control-label"></label>
            </div>
                
            <div class="form-group" align="right">
                <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;"></label>
                <div class="col-sm-6">
                    <button name="save" type="submit" class="btn bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>"><i class="fa fa-save">&nbsp;Submit</i></button>
                </div>
                <label for="" class="col-sm-3 control-label"></label>
            </div>
			 
<?php
if(isset($_POST['save']))
{   
    function random_password($limit)
	{
	    return substr(base_convert(sha1(uniqid(mt_rand())), 16, 36), 0, $limit);
	}

    $groupcode = strtoupper(random_password(6));
    $cmode = mysqli_real_escape_string($link, $_POST['cmode']);
    $dmode = mysqli_real_escape_string($link, $_POST['dmode']);
    $gname = mysqli_real_escape_string($link, $_POST['gname']);
    $glimit = mysqli_real_escape_string($link, $_POST['glimit']);
	$amount = mysqli_real_escape_string($link, $_POST['amount']);
    $cinterval = mysqli_real_escape_string($link, $_POST['cinterval']);
    $cduration = mysqli_real_escape_string($link, $_POST['cduration']);
    $dinterval = mysqli_real_escape_string($link, $_POST['dinterval']);
    $tpin = mysqli_real_escape_string($link, $_POST['tpin']);
    $cluster = mysqli_real_escape_string($link, $_POST['cluster']);
    $mstatus = mysqli_real_escape_string($link, $_POST['mstatus']);
    $startDate = mysqli_real_escape_string($link, $_POST['startDate']);
    $todays_date = date('Y-m-d h:i:s');
    $isActive = "true";
    $status = "Unlocked";

    //Calculate Days
    $intervalPeriod = ($dinterval == "daily" ? 'day' : ($dinterval == "weekly" ? 'week' : 'month'));
    $endDate = date('Y-m-d', strtotime('+1 '.$intervalPeriod, strtotime($startDate)));

    if($tpin != $myuepin){

        echo "<script>alert('Oops!....Invalid Transaction Pin!!'); </script>";

    }
    else{

        $insert_group = mysqli_query($link, "INSERT INTO group_contribution VALUES(null,'$groupcode','$bbranchid','$virtual_acctno','$cmode','$dmode','$gname','$glimit','$amount','$cinterval','$duration','$dinterval','$todays_date','$isActive','Approved','1','$startDate','$endDate','0.0','0.0','0.0','1','0','$status')");
        
        $insert_gmember = mysqli_query($link, "INSERT INTO group_member (null,'$groupcode','$bbranchid','$virtual_acctno','$bname','$phone2','$cluster','$todays_date','$dateofbirth','$email2','$mstatus','$bgender','Leader','0','Approved','null','1','$endDate','1')");

    	if($insert_group && $insert_gmember)
    	{   
    		echo "<script>alert('Group Created Successfully!!'); </script>";
    		echo "<script>window.location='sendinvitation.php?tid=".$_SESSION['tid']."&&acn=".$acctno."&&gcode=".$groupcode."&&mid=MTIw'; </script>";
    	}
    	else{
    		echo "<script>alert('Error Occur...Please try again later!'); </script>";
    		echo "<script>window.location='creategroup.php?tid=".$_SESSION['tid']."&&acn=".$acctno."&&mid=MTIw'; </script>";
    	}

    }
}
?>		
			
			 </form> 


</div>	
</div>	
</div>
</div>