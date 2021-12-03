<?php
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
$conn = mysqli_connect("localhost", "root", "", "chatapp") or die();
if(!$conn){
    echo 'Kapcsolódás sikertelen!';
}
$conn->set_charset("utf8");
