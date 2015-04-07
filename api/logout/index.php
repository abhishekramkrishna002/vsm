<?php
session_start();
include '../libs/helper.php';
header('Access-Control-Allow-Origin: *');
//header('countryname: USA');
if (isset($_GET['oauth_key']) && !empty($_GET['oauth_key'])) {
    //print_r($_GET['oaauth_key']);
   
        logout($_GET['oauth_key']);
    
    
}
?>