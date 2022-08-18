<?php
$PostType = $_GET['PostType'];

if($PostType == "daily"){
    echo "Day(s)";
}
elseif($PostType == "weekly"){
    echo "Week(s)";
}
elseif($PostType == "monthly"){
    echo "Month(s)";
}
elseif($PostType == "yearly"){
    echo "Year(s)";
}
elseif($PostType == "ONE-OFF"){
    echo "One-off";
}
else{
    echo "-frequency-";
}
?>