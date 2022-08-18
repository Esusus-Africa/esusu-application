<?php 

putenv("MY_HOST=localhost");
    
putenv("DB_USERNAME=esusulive_esusuapp");
    
putenv("DB_PASSWORD=BoI9YR^zqs%M");
    
putenv("DB_NAME=esusulive_esusuapp");
    
$localhost = getenv('MY_HOST');
    
$dbusername = getenv('DB_USERNAME');
    
$dbpass = getenv('DB_PASSWORD');
    
$dbname = getenv('DB_NAME');

$link = mysqli_connect($localhost,$dbusername,$dbpass,$dbname) or die('Unable to Connect to Database');

$connect = new PDO("mysql:host=$localhost;dbname=$dbname","$dbusername","$dbpass") or die('Unable to Connect to Database');

?>