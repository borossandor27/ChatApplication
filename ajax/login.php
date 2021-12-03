<?php
require_once './config.php';
$email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_STRING);
$password = filter_input(INPUT_POST, "password", FILTER_SANITIZE_STRING);
if(!empty($email) && !empty($password)){
    $sql = mysqli_query($conn, "SELECT unique_id FROM users WHERE email = '{$email}' AND password = '{$password}'");
    if(mysqli_num_rows($sql) > 0){
        $row = mysqli_fetch_assoc($sql);
        $_SESSION['unique_id'] = $row['unique_id'];
        echo 'success';
    } else {
        echo 'Email or password is incorrect!';
    }
} else {
    echo 'All input fields are required!';
}
