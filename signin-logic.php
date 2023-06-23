<?php
require 'config/database.php';

if(isset($_POST['submit'])){
    //getting form data
    $username_email= filter_var($_POST['username_email'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $password= filter_var($_POST['password'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    //validation
    if(!$username_email){
        $_SESSION['signin'] ="Username or Email required!";
    } elseif(!$password){
        $_SESSION['signin'] ="Password required!";
    } else{
        //fetch user from database
        $fetch_user_query="SELECT * FROM users WHERE username='$username_email' OR email='$username_email'";
        $fetch_user_result=mysqli_query($connection,$fetch_user_query);

        if(mysqli_num_rows($fetch_user_result) == 1){
            //convert to associative array
            $user_record=mysqli_fetch_assoc($fetch_user_result);//NOTE: all columns of user here not only username/email
            $db_password=$user_record['password'];

            //comparing entered pass with dbpass
            if(password_verify($password,$db_password)){
                //setting session for access control
                $_SESSION['user-id']=$user_record['id'];
                //session for admin user power
                if($user_record['admin']==1){
                    $_SESSION['user_is_admin']=true;
                }

                //logged user in
                header('location: ' .ROOT_URL. 'admin/');  
            } else{//wrong password
                $_SESSION['signin']="Please Check your inputs!";
            }

        } else{//no db record for username/email
            $_SESSION['signin']="User not found!";
        }
    }

    //if any problem, redirect to sign in page
    if(isset($_SESSION['signin'])){
        $_SESSION['signin-data']=$_POST;
        header('location: '. ROOT_URL . 'signin.php');
        die();
    }
} else{
    header('location'.ROOT_URL . 'signin.php');
    die();
}
