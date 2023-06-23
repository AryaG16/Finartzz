<?php
require 'config/database.php';

//fetching current user from db
if(isset($_SESSION['user-id'])){
    $id=filter_var($_SESSION['user-id'], FILTER_SANITIZE_NUMBER_INT);
    $query="SELECT avatar FROM users WHERE id=$id";
    $result=mysqli_query($connection, $query);
    $avatar=mysqli_fetch_assoc($result);
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FinartZ</title>
    <meta name="description" content="Taxation Guides and Financial Updates">

    <!-- CUSTOM CSS -->
    <link rel="stylesheet" href="<?= ROOT_URL ?>css/style.css">
    <!-- ICONS -->
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css">
    <!-- FAVICON -->
    <link rel="shortcut icon" href="<?= ROOT_URL ?>assets/Fevicon B.jpg" type="image/x-icon">
    <!-- GOOGLE FONTS -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
</head>

<body>
    <nav>
        <div class="container nav_container">
            <a href="<?= ROOT_URL ?>index.php" class="nav_logo"> <img src="<?= ROOT_URL ?>assets/Logo B.png" alt="Finartz Logo"></a>
            <ul class="nav_items">
                <li><a href="<?= ROOT_URL ?>index.php">About</a></li>
                <li><a href="<?= ROOT_URL ?>blog.php">Blog</a></li>
                <li><a href="<?= ROOT_URL ?>calculator.php">Calculator</a></li> 
                <li><a href="<?= ROOT_URL ?>contact.php">Contact Us</a></li>
                <?php if (isset($_SESSION['user-id'])) : ?>
                    <!--Photo if signed in-->
                    <li class="nav_profile">
                        <div class="avatar">
                            <img src="<?= ROOT_URL . 'images/' . $avatar['avatar'] ?>">
                        </div>
                        <ul>
                            <li><a href="<?= ROOT_URL ?>admin/index.php">Dashboard</a></li>
                            <li><a href="<?= ROOT_URL ?>logout.php">Logout</a></li>
                        </ul>
                    </li>
                <?php else : ?>
                    <li><a href="<?= ROOT_URL ?>signin.php">Sign In</a></li>
                <?php endif ?>
            </ul>

            <button id="open_nav-btn"><i class="uil uil-bars"></i></button>
            <button id="close_nav-btn"><i class="uil uil-multiply"></i></button>
        </div>
    </nav>
    <!-- ========== END OF NAV ========== -->