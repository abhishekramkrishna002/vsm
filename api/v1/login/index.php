<?php
include '../libs/helper.php';
include '../libs/accesscontrol.php';
if (isset($_POST['useragent']) && isset($_POST['username']) && isset($_POST['password'])) {
    $username_user = $_POST['username'];
    $password_user = md5($_POST['password']);
    $device = $_POST['useragent'];
    get_oauth($username_user, $password_user, $device);
    } 
    else {
        header($_SERVER["SERVER_PROTOCOL"]." ".$GLOBALS['bad_request']);
    $result_array = array('status'=>'error',
                    'message'=>'Method not allowed');
    print_r(json_encode($result_array));
   
}

?>
