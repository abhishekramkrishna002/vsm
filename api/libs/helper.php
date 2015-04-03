<?php

include '../../properties/config.php';
define("ENCRYPTION_KEY", "!@#$%^&*^&*");

function check_user($username_user, $password_user, $device) {
    include 'sql.php';
    $id = get_profile_id($username_user, $password_user);

    $SQL = "SELECT * FROM user WHERE profile_id=$id";

    $result = $conn->query($SQL);

    if ($result->num_rows > 0) {

        $row = $result->fetch_assoc();
        if (($row['username'] == $username_user) && ($row['password'] == $password_user)) {
            get_oauth($username_user, $password_user, $device);
            $conn->close();
        } else {
            $result_array = array('status' => 'error', 'statusCode' => 404,
                'message' => 'User not found');
            print_r(json_encode($result_array));
        }
    }
}

function get_profile_id($username_user, $password_user) {
    $profile_id = null;
    if (isset($username_user) && isset($password_user)) {
        include 'sql.php';
        $sql = "SELECT profile_id FROM " . $dbname . ".user where username='$username_user' and password='$password_user'";

        $result = $conn->query($sql);


        if ($result->num_rows > 0) {

            $row = $result->fetch_assoc();
            $profile_id = $row['profile_id'];
        }
    }

    return $profile_id;
}

function check_oauth($profile_id, $device) {
    $oauth_key = null;
    if (isset($profile_id) && isset($device)) {
        include 'sql.php';

        $date = date_create();
        date_timestamp_set($date, time());
        $today = date_format($date, "Y-m-d H:i:s");
        $sql = "SELECT * FROM " . $dbname . ".oauth where profile_id=$profile_id and device = '$device'  ";

        // echo $sql;
        $result = $conn->query($sql);
//        /print_r()
        if ($result->num_rows > 0) {

            $row = $result->fetch_assoc();
            $expiry_date = $row['oauth_expiry'];
            if ($expiry_date >= $today) {
                $oauth_key = $row['oauth_key'];
            }
        }
    }

    return $oauth_key;
}

function get_oauth($username_user, $password_user, $device) {
    if (isset($username_user) && isset($password_user) && isset($device)) {

        $profile_id = get_profile_id($username_user, $password_user);
        $oauth_key = check_oauth($profile_id, $device);
        $status = "failure";
        if ($profile_id != null && $oauth_key == null) {
            include 'sql.php';
            /*
             * generate and write the oauth key to db
             */
            $token = md5(uniqid(rand(), true));
            $date = date_create();
            date_timestamp_set($date, time() + ( 2 * 24 * 60 * 60));
            $dtm = date_format($date, "Y-m-d H:i:s");
            $sql = "insert into " . $dbname . ".oauth(oauth_key,oauth_expiry,device,profile_id) value ('$token','$dtm','$device',$profile_id)";
            $result = $conn->query($sql);
            $oauth_key = $token;




            $result_array = array('status' => 'success',
                'statusCode' => 200,
                'message' => 'User found', 'oauth' => $oauth_key, 'number_of_devices_logged_in' => get_users_logged_in($profile_id));
            $conn->close();
        } else if ($oauth_key != null) {
            $status = "sucess";
            $result_array = array('status' => 'success',
                'statusCode' => 200,
                'message' => 'User found', 'oauth' => $oauth_key, 'number_of_devices_logged_in' => get_users_logged_in($profile_id));
        } else {
            $result_array = array('status' => 'failure',
                'statusCode' => 404,
                'message' => 'User not found');
        }

        print_r(json_encode($result_array));
        print_r("<a href='../logout/?oauth_key=" . $oauth_key . "'>logout</a>");
    }
}

function get_users_logged_in($profile_id) {
    include 'sql.php';

    $date = date_create();
    date_timestamp_set($date, time());
    $today = date_format($date, "Y-m-d H:i:s");
    $sql = "SELECT * FROM " . $dbname . ".oauth where profile_id='$profile_id' ";

    //echo $sql;
    $result = $conn->query($sql);
    // echo $result;
    if (!$result) {
        die(sprintf("Error: %s", $conn->error));
    }
    $count = 0;
    while ($row = $result->fetch_assoc()) {
        //$row = $result->fetch_assoc();
        $expiry_date = $row['oauth_expiry'];
        if ($expiry_date >= $today) {
            //  $oauth_key = $row['oauth_key'];
            $count++;
        }
    }
    return $count;
}

function get_users_logged_in_devices($profile_id) {
    include 'sql.php';

    $date = date_create();
    date_timestamp_set($date, time());
    $today = date_format($date, "Y-m-d H:i:s");
    $sql = "SELECT * FROM " . $dbname . ".oauth where profile_id='$profile_id' ";

    //echo $sql;
    $result = $conn->query($sql);
    // echo $result;
    if (!$result) {
        die(sprintf("Error: %s", $conn->error));
    }
    $count = 0;
    $devices = array();
    while ($row = $result->fetch_assoc()) {
        //$row = $result->fetch_assoc();
        $expiry_date = $row['oauth_expiry'];
        if ($expiry_date >= $today) {
            $devices[] = $row['device'];
        }
    }
    return $devices;
}

function logout($ouath_key) {
    include 'sql.php';
// Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    $sql = "DELETE FROM vsn.oauth where oauth_key='$ouath_key'";
    //echo $sql;
    $result = $conn->query($sql);
    //print_r("home::".$GLOBALS['home_url']);
    header("Location: " . $GLOBALS['home_url']);

    $conn->close();
}

function is_user_present($email) {

    if (isset($email)) {
        include 'sql.php';
        $sql = "SELECT profile_id FROM " . $dbname . ".user where username='$email'";

        $result = $conn->query($sql);


        if ($result->num_rows > 0) {

            return true;
        }
    }

    return false;
}

function get_profile_id_email($email) {

    if (isset($email)) {
        include 'sql.php';
        $sql = "SELECT profile_id FROM " . $dbname . ".profile where profile_email='$email'";
        //echo $sql;
        $result = $conn->query($sql);


        if ($result->num_rows > 0) {

            $row = $result->fetch_assoc();
            $profile_id = $row['profile_id'];
            return $profile_id;
        }
    }

    return 0;
}

function register_user($email, $name) {
    include 'sql.php';
    $sql = "insert into profile(profile_email,profile_name,profile_activated) values('$email','$name',0)";

    $result = $conn->query($sql);

    return $result;
}

function sendMail($info) {
    require 'mail-lib/PHPMailerAutoload.php';

    $mail = new PHPMailer;

//$mail->SMTPDebug = 3;                               // Enable verbose debug output

    $mail->isSMTP();                                      // Set mailer to use SMTP
    $mail->Host = 'smtp.gmail.com';  // Specify main and backup SMTP servers
    $mail->SMTPAuth = true;                               // Enable SMTP authentication
    $mail->Username = 'fassha08@gmail.com';                 // SMTP username
    $mail->Password = 'noteb00k';                           // SMTP password
    $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
    $mail->Port = 587;                                    // TCP port to connect to

    $mail->From = 'fassha08@gmail.com';
    $mail->FromName = 'VSM';
    $mail->addAddress($info['to_email']);     // Add a recipient
    //$mail->addAddress('abhishek.ramkrishna002@gmail.com');               // Name is optional
    //$mail->addReplyTo('abhishek.ramkrishna002@gmail.com', 'Information');
    //$mail->addCC('abhishek.ramkrishna002@gmail.com');
   // $mail->addBCC('fassha08@gmail.com');

//$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
//$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
    $mail->isHTML(true);                                  // Set email format to HTML

    $mail->Subject = $info['subject'];
    $mail->Body = $info['body'];
    //$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

    if (!$mail->send()) {
        //echo 'Message could not be sent.';
        //echo 'Mailer Error: ' . $mail->ErrorInfo;
        return false;
    } else {
        //echo 'Message has been sent';
        return true;
    }
}

function get_activation_link($email, $profile_password) {
    $profile_id = get_profile_id_email($email);
    $string = md5($email);
    include 'sql.php';
    $sql = "insert into activation(profile_id,activation_code,profile_email,profile_password) values($profile_id,'$string','$email','$profile_password')";
    $result = $conn->query($sql);
    return "http://localhost/vsm/activation.php?activation_code=$string";
}

function activate($activation_code) {
    include 'sql.php';


    $sql = "SELECT profile_id,profile_email,profile_password FROM " . $dbname . ".activation where activation_code='$activation_code' ";

    //echo $sql;
    $result = $conn->query($sql);
    // echo $result;
    if (!$result) {
        die(sprintf("Error: %s", $conn->error));
    }

    $user = array();
    if ($row = $result->fetch_assoc()) {
        //$row = $result->fetch_assoc();
        $user['profile_id'] = $row['profile_id'];
        $user['profile_email'] = $row['profile_email'];
        $user['profile_password'] = $row['profile_password'];
    }
    $result_array = array();
    if (create_user($user) > 0) {
        $result_array = array('status' => 'sucess', 'statusCode' => 200,
            'message' => 'User activated sucessfully');
    } else {
        $result_array = array('status' => 'failure', 'statusCode' => 404,
            'message' => 'User activation failed!');
    }
    print_r(json_encode($result_array));
}

function create_user($user) {
    include 'sql.php';
    $username = $user['profile_email'];
    $password = md5($user['profile_password']);
    $profile_id = $user['profile_id'];
    $sql = "insert into user(username,password,profile_id) values('$username','$password',$profile_id)";

    $result = $conn->query($sql);

    return $result;
}
function update_user($email, $user_password) {
    include 'sql.php';
    $sql = "update user set password='$user_password' where username='$email'";
    //echo $sql;
    $result = $conn->query($sql);

    return $result;
}

?>