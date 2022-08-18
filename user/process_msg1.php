<?php include "../config/session.php"; ?>  

<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="../dist/css/style.css" />
</head>
<body>
<br><br><br><br><br><br><br><br><br>
<div style="width:100%;text-align:center;vertical-align:bottom">
		<div class="loader"></div>
<?php
if(isset($_POST['send']))
{
$tid = $_SESSION['tid'];
$ticketid = mysqli_real_escape_string($link, $_POST['ticketid']);
$subject = mysqli_real_escape_string($link, $_POST['subject']);
$message = mysqli_real_escape_string($link, $_POST['message']);
$receiver_id = mysqli_real_escape_string($link, $_POST['receiver_id']);

$insert = mysqli_query($link, "INSERT INTO message VALUES(null,'$ticketid','$tid','$bname','$receiver_id','$subject','$message','Pending','$bbranchid',NOW())") or die (mysqli_error($link));
if(!$insert)
{
 echo '<meta http-equiv="refresh" content="2;url=newmessage.php?tid='.$_SESSION['tid'].'&&acn='.$_SESSION['acctno'].'&&mid='.base64_encode("406").'">';
echo '<br>';
echo'<span class="itext" style="color: #FF0000">Unable to deliver message.....Please try again later!</span>';
}
else{
echo '<meta http-equiv="refresh" content="2;url=inboxmessage.php?tid='.$_SESSION['tid'].'&&acn='.$_SESSION['acctno'].'&&mid='.base64_encode("406").'">';
echo '<br>';
echo'<span class="itext" style="color: #FF0000">Sending Reply, Please Wait</span>';
}
}
?>
</div>
</body>
</html>
