<?php
include '../libs/helper.php';
include '../libs/accesscontrol.php';
if(isset($_GET['oauth_key']) && !empty($_GET['oauth_key']))
{
    //print_r($_GET['oauth_key']);
   logout($_GET['oauth_key']); 
}


?>