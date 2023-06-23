<!-- FOR ACCESS CONTROL -->
<?php
require '../partials/header.php';


//checking login status
if(!isset($_SESSION['user-id'])){
    header('location: '.ROOT_URL.'signin.php');
    die();
}
?>
