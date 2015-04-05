<?php

include '../libs/helper.php';
include '../libs/accesscontrol.php';
if (isset($_POST['email']) && isset($_POST['name']) && isset($_POST['password'])) {
    $email = $_POST['email'];
    $name = $_POST['name'];
    $profile_password=$_POST['password'];
    if (!is_user_present($email)) {
        
        /*
         * registe the user || write to profle tabel
         */
        if (register_user($email, $name) > 0) {
            
            $link = get_activation_link($email,$profile_password);
            $msg = "you are sucessfully registered: <br/>click to <a href='$link'>activate</a>";
            $info = array('to_email' => $email, 'subject' => 'vsm registration', 'body' => $msg);
            sendMail($info);
            header($_SERVER["SERVER_PROTOCOL"]." ".$GLOBALS['status_found']);
            $result_array = array('status' => 'sucess',
                                    'message' => 'User registered and email sent');
        } else {
            header($_SERVER["SERVER_PROTOCOL"]." ".$GLOBALS['status_notfound']);
            $result_array = array('status' => 'failure',
                                    'message' => 'fail during registration of user');
        }
    } else {
             header($_SERVER["SERVER_PROTOCOL"]." ".$GLOBALS['status_notfound']);
             $result_array = array('status' => 'failure',
                                    'message' => 'User exists');
    }
    print_r(json_encode($result_array));
}else {
        header($_SERVER["SERVER_PROTOCOL"]." ".$GLOBALS['bad_request']);
    $result_array = array('status'=>'error',
                    'message'=>'Method not allowed');
    print_r(json_encode($result_array));
   
}
?>

