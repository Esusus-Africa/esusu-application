<?php include "config/connect.php";?>

<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="dist/css/style.css" />
</head>
<body>
<br><br><br><br><br><br><br><br><br>
<div style="width:100%;text-align:center;vertical-align:bottom">
		<div class="loader"></div>
<?php
        //Agent Records
        $agentid = mysqli_real_escape_string($link, $_POST['agentid']);
        $bvn = mysqli_real_escape_string($link, $_POST['unumber']);

        $target_dir = "../img/";
        $target_file = $target_dir.basename($_FILES["image"]["name"]);
        $target_file_vimage = $target_dir.basename($_FILES["vimage"]["name"]);
        $target_file_utimage = $target_dir.basename($_FILES["utimage"]["name"]);
        $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
        $imageFileType_vimage = pathinfo($target_file_vimage,PATHINFO_EXTENSION);
        $imageFileType_utimage = pathinfo($target_file_utimage,PATHINFO_EXTENSION);
        $check = getimagesize($_FILES["image"]["tmp_name"]);
        $check_vimage = getimagesize($_FILES["vimage"]["tmp_name"]);
        $check_utimage = getimagesize($_FILES["utimage"]["tmp_name"]);

        $sourcepath = $_FILES["image"]["tmp_name"];
        $sourcepath_vimage = $_FILES["vimage"]["tmp_name"];
        $sourcepath_utimage = $_FILES["utimage"]["tmp_name"];
        $targetpath = "../img/" . $_FILES["image"]["name"];
        $targetpath_vimage = "../img/" . $_FILES["vimage"]["name"];
        $targetpath_utimage = "../img/" . $_FILES["utimage"]["name"];
        move_uploaded_file($sourcepath,$targetpath);
        move_uploaded_file($sourcepath_vimage,$targetpath_vimage);
        move_uploaded_file($sourcepath_utimage,$targetpath_utimage);
        
        $location = "img/".$_FILES['image']['name'];
        $loaction_vimage = "img/".$_FILES['vimage']['name'];
        $utilitybill = "img/".$_FILES['utimage']['name'];
          
          $insert_agent = mysqli_query($link, "UPDATE agent_data SET bvn = '$bvn', valid_id = '$loaction_vimage', utitlity_bill = '$utilitybill', id_card = '$location' WHERE agentid = '$agentid'") or die ("Error: " . mysqli_error($link));
          if($insert_agent)
          {
          	echo '<meta http-equiv="refresh" content="5;url=index.php">';
			echo '<br>';
			echo '<span class="itext" style="color: blue;">Application Completed Successfully!... You will be notify once your application is approved.</span>';
          }
          else{
              echo '<meta http-equiv="refresh" content="5;url=index.php">';
			  echo '<br>';
			  echo'<span class="itext" style="color: orange;">Unable to Complete the Application...Please Try Again Later!!</span>';
          }
      ?>
</div>
</body>
</html>