<?php

include 'api/libs/helper.php';

$activation_code=$_GET['activation_code'];
if(isset($activation_code))
{
    activate($activation_code);
}






?>