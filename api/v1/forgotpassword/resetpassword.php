<?php 
include '../libs/helper.php';
include '../libs/sql.php';
print_r($_POST);
$acode=$_POST['code'];
$confirm_password= md5($_POST['confirm_password']);
$password=md5($_POST['password']);
    if($password == $confirm_password){
        $sql = "SELECT profile_email FROM activation WHERE activation_code='$acode'";
        $result=$conn->query($sql);
        if ($result->num_rows > 0) {

            $row = $result->fetch_assoc();
            $email = $row['profile_email'];
            echo $email;
        }
        update_user($email, $password);
        header("Location: " . $GLOBALS['home_url']);
    }else{
        echo "mismatch";
        }

?>