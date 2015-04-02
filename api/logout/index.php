<?php
include '../libs/helper.php';
header('Access-Control-Allow-Origin: *');
if(isset($_GET['oauth_key']) && !empty($_GET['oauth_key']))
{
    //print_r($_GET['oauth_key']);
   logout($_GET['oauth_key']); 
}


?>