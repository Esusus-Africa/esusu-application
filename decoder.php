<?php
$de= $_GET["key"];
 
$result=mysqli_query($link, "SELECT * from short_urls WHERE short_code='$de'");
while($row = mysqli_fetch_array($result))
{
$res = $row['long_url'];
header("location:".$res);
}
?>