<?php

include '../libs/helper.php';
$profile_id=$_GET['profile_id'];
print_r(get_users_logged_in_devices($profile_id));

