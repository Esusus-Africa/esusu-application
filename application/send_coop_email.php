<?php
include("../config/session.php");

$email = $_POST['rcv'];
$subj = $_POST['subj'];
$msg = $_POST['msg'];
	
	switch ($email) {
		case ($email == "All"):
			$search_cust = mysqli_query($link, "SELECT * FROM cooperatives") or die("Error:" . mysqli_error($link));
			while($get_cust = mysqli_fetch_array($search_cust))
			{
				//$fname = $get_cust['fname'];
				$bulkmail = $get_cust['email']; // email id of user
				
				$query = mysqli_query($link, "SELECT email FROM systemset") or die (mysqli_error($link));
				$r = mysqli_fetch_object($query);
				//send email
				$subject = "$subj";
				$body = "$msg";
	  			$additionalheaders = "MIME-Version: 1.0" . "\r\n";
	  			$additionalheaders .= "Content-Type: text/html;charset=ISO-8859-1" . "\r\n";
	  			$additionalheaders .= "From:$r->email" . "\r\n";
	  			$additionalheaders .= "Reply-To:noreply@email.com" ."\r\n";
				if(mail($bulkmail,$subject,$body,$additionalheaders))
				{
					echo "<hr>";
					echo "<div class='alert alert-success'>Email Sent! </div>";
				}
				else{
					echo "<hr>";
					echo "<div class='alert alert-danger'>Failed to Send Email, try again later!! </div>";
				}
			}
			break;
			
		case ($email != "All"):
			$search_cust = mysqli_query($link, "SELECT * FROM borrowers WHERE email = '$email'") or die("Error:" . mysqli_error($link));
			$get_cust = mysqli_fetch_array($search_cust);
			//$fname = $get_cust['fname'];
			$query = mysqli_query($link, "SELECT email FROM systemset") or die (mysqli_error($link));
			$r = mysqli_fetch_object($query);
			//send email
			$to = "$email";
			$subject = "$subj";
			$body = "$msg";
			$additionalheaders = "MIME-Version: 1.0" . "\r\n";
			$additionalheaders .= "Content-Type: text/html;charset=ISO-8859-1" . "\r\n";
			$additionalheaders .= "From:$r->email" . "\r\n";
			$additionalheaders .= "Reply-To:noreply@imon.com" ."\r\n";
			if(mail($to,$subject,$body,$additionalheaders))
			{
				echo "<hr>";
				echo "<div class='alert alert-success'>Email Sent!</div>";
			}
			else{
				echo "<hr>";
				echo "<div class='alert alert-danger'>Failed to Send Email, try again later!!</div>";
			}
			break;
		default:
			echo "<hr>";
			echo "<div class='alert alert-danger'>Sorry! Network Error!!</div>";
	}
}
?>