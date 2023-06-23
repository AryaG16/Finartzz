<?php
require 'config/database.php';

if(isset($_POST['submit'])){
    //getiing saignup data after click
    $firstname= filter_var($_POST['firstname'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $lastname= filter_var($_POST['lastname'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $username= filter_var($_POST['username'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $email= filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
    $createpassword= filter_var($_POST['createpassword'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $confirmpassword= filter_var($_POST['confirmpassword'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $is_admin=filter_var($_POST['userrole'], FILTER_SANITIZE_NUMBER_INT);
    $avatar=$_FILES['avatar'];

    //validating inputs
    if(!$firstname){
        $_SESSION['add-user'] ="Please enter your First Name";
    } elseif(!$lastname) {
        $_SESSION['add-user'] ="Please enter your Last Name";
    } elseif(!$username) {
        $_SESSION['add-user'] ="Please enter your Username";
    } elseif(!$email) {
        $_SESSION['add-user'] ="Please enter a valid email";
    } elseif(strlen($createpassword)<8 || strlen($confirmpassword)<8) {
        $_SESSION['add-user'] ="Password should be 8+ characters";
    } elseif(!$avatar['name']) {
        $_SESSION['add-user'] ="Please add avatar";
    } else {//matching password
        if($createpassword !== $confirmpassword){
            $_SESSION['add-user'] ="Passwords do not match";
        } else{
            //hashing password to db
            $hashed_password = password_hash($createpassword,PASSWORD_DEFAULT);
            
            //checking if email & username isn't dupicate in DB
            $user_check_query ="SELECT * FROM users WHERE username='$username' OR email='$email'";
            $user_check_result=mysqli_query($connection,$user_check_query);
            if(mysqli_num_rows($user_check_result)>0){
                $_SESSION['add-user'] ="Username or Email already exists!";
            } else {
                //Avatar
                //Rename
                $time=time();
                $avatar_name=$time.$avatar['name'];
                $avatar_tmp_name = $avatar['tmp_name'];
                $avatar_destination_path='../images/' . $avatar_name;

                //make sure file is image
                $allowed_files=['jpg','png','jpeg'];
                $extension=explode('.',$avatar_name);
                $extension=end($extension);
                if(in_array($extension,$allowed_files)){
                    //check if image size < 2mb
                    if($avatar['size']<=2000000){
                        //uploading avatar
                        move_uploaded_file($avatar_tmp_name,$avatar_destination_path);
                    } else{
                        $_SESSION['add-user']="File Size too big. Should be less than equal to 2mb";
                    }
                } else{
                    $_SESSION['add-user']="File should be png, jpg or jpeg";
                }
            }
        }
    }

    //if any problem redirect back to add-user
    if(isset($_SESSION['add-user'])){
        //pass form data back to add-user
        $_SESSION['add-user-data']=$_POST;
        header('location: '. ROOT_URL . 'admin/add-user.php');
        die();
    } else{
        //inserting new user in db
        $insert_user_query ="INSERT INTO users (firstname, lastname, username, email, password,avatar,admin) VALUES('$firstname','$lastname','$username','$email','$hashed_password','$avatar_name','$is_admin')";
        $insert_user_result =mysqli_query($connection,$insert_user_query);

        if(!mysqli_errno($connection)){
            //succes reg, redirecting to sign in
            $_SESSION['add-user-success']="New User $firstname $lastname added successfully!";
            header('location: '. ROOT_URL . 'admin/manage-users.php');
            die();
        }
    }

} else{
    //if btn not click bounce back to add-user page
    header('location: '. ROOT_URL . 'admin/add-user.php');
    die();
}
