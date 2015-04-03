<?php 
include '../libs/helper.php';
//print_r($_POST);
$email=$_POST['email'];
$confirm_password= md5($_POST['confirm_password']);
$password=md5($_POST['password']);
    if($password == $confirm_password){
        update_user($email, $password);
        header("Location: " . $GLOBALS['home_url']);
    }else{
        echo "mismatch";
        }

?>