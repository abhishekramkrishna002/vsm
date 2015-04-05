<?php
include '../libs/sql.php';
include '../libs/helper.php';
if(isset($_POST['email'])){
   
    $email= $_POST['email'];
  $profile_id = get_profile_id_email($email);
  
    if($profile_id){
        $code = md5(1290*3+$profile_id);
        $link = "http://localhost/vsm/api/resetform.php?email=$email&code=$code";
       //$encrypt = md5(1290*3+$profile_id);
       $msg = "Please use the link to reset password.<br/>click to <a href='$link'>reset</a>";
       $info = array('to_email' => $email, 'subject' => 'vsm registration', 'body' =>$msg);
        sendMail($info);
        forgot_password_activation_code($email,$code);

         $apiResponse = array(
                'status' => 'success',
                'statusCode' => 200,
                'message' => 'Email  sent',
               
            );
  ;
         
   }
}else{
     $apiResponse = array(
                'status' => 'error',
                'statusCode' => 500,
                'message' => 'Email not set',
                'error' => 'try again'
            );
      
}
print_r(json_encode($apiResponse));
?>

