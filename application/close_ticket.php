<?php include "../config/session.php"; ?>  

<!DOCTYPE html>
<html>
<head>

<style>
.loader {
  border: 16px solid #f3f3f3;
  border-radius: 50%;
  border-top: 16px solid blue;
  border-right: 16px solid green;
  border-bottom: 16px solid red;
  border-left: 16px solid pink;
  width: 120px;
  height: 120px;
  -webkit-animation: spin 2s linear infinite;
  animation: spin 2s linear infinite;
  margin:auto;
  
}

@-webkit-keyframes spin {
  0% { -webkit-transform: rotate(0deg); }
  100% { -webkit-transform: rotate(360deg); }
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}
</style>
</head>
<body>
<br><br><br><br><br><br><br><br><br>
<div style="width:100%;text-align:center;vertical-align:bottom">
		<div class="loader"></div>
<?php
$ticketid = $_GET['ticketid'];

$update = mysqli_query($link, "UPDATE message SET mstatus = 'Closed' WHERE ticketid = '$ticketid'") or die (mysqli_error($link));
if(!$update)
{
 echo '<meta http-equiv="refresh" content="2;url=inboxmessage.php?tid='.$_SESSION['tid'].'&&mid='.base64_encode("406").'">';
echo '<br>';
echo'<span class="itext" style="color: #FF0000">Unable to Close Ticket.....Please try again later!</span>';
}
else{
echo '<meta http-equiv="refresh" content="2;url=inboxmessage.php?tid='.$_SESSION['tid'].'&&mid='.base64_encode("406").'">';
echo '<br>';
echo'<span class="itext" style="color: #FF0000">Closing Ticket, Please Wait</span>';
}
?>
</div>
</body>
</html>
