<?php

include '../libs/helper.php';
header('Access-Control-Allow-Origin: *');
//header('countryname: USA');
if (isset($_POST['email']) && isset($_POST['name']) && isset($_POST['password'])) {
    $email = $_POST['email'];
    $name = $_POST['name'];
    $profile_password=$_POST['password'];
    print_r($_POST);
    if (!is_user_present($email)) {
        echo"hell";
        /*
         * registe the user || write to profle tabel
         */
        if (register_user($email, $name) > 0) {
            echo 'hiii';
            $link = get_activation_link($email,$profile_password);
            $msg = "you are sucessfully registered: <br/>click to <a href='$link'>activate</a>";
            $info = array('to_email' => $email, 'subject' => 'vsm registration', 'body' => $msg);
            sendMail($info);
            $result_array = array('status' => 'sucess',
                'statusCode' => 200,
                'message' => 'User registered && email sent');
        } else {
            $result_array = array('status' => 'failure',
                'statusCode' => 404,
                'message' => 'fail during registration of user');
        }
    } else {
        $result_array = array('status' => 'failure',
            'statusCode' => 404,
            'message' => 'User exists');
    }
    print_r(json_encode($result_array));
}
?>

