<?php
include '../libs/helper.php';
include '../libs/accesscontrol.php';

if(isset($_GET['oauth_key']) && !empty($_GET['oauth_key']) && isset($_GET['logout_all_devices']))
{
    
    logout_from_all_devices(get_profile_id_from_oauth($_GET['oauth_key']), $_GET['oauth_key']);   
}
else if(isset($_GET['oauth_key']) && !empty($_GET['oauth_key']))
{
    //print_r($_GET['oauth_key']);
   logout($_GET['oauth_key']); 
}


?>