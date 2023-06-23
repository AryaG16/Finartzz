<?php
require 'config/constants.php';

//Connecting database
$connection = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME);

//Checking if connected
if(mysqli_errno($connection)){
    die(mysqli_error($connection));
}
?>