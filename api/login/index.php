<?php

include '../libs/helper.php';
header('Access-Control-Allow-Origin: *');
//header('countryname: USA');
if (isset($_POST['useragent']) && isset($_POST['username']) && isset($_POST['password'])) {
    session_start();
    $_SESSION['device']=$_POST['useragent'];
    $username_user = $_POST['username'];
    $password_user = md5($_POST['password']);
    $device = $_POST['useragent'];
    get_oauth($username_user, $password_user, $device);
    print_r($_SESSION);
} else {
    session_start();
    $result_array = array('status' => 'error', 'statusCode' => 404,
        'message' => 'User not found');
    print_r(json_encode($result_array));
    session_abort();
}
?>
